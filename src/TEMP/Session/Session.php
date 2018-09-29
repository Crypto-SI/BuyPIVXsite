<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Session Manager
 *
 *  Session Handler To Alter And Save All Session Data Within The Database.
 *
 */

namespace TEMP\Session;

use TEMP\Database\Database;
use TEMP\Input\Input;

class Session {

    // Set Ensure Login Works Even On Subdomains
    private $domain     = '';

    // Prevent Javascript From Being Able To Access The Session ID
    private $httpOnly   = true;

    // Database Table Name Used
    private $table      = 'sessions';


    // Define Time In Seconds Of Inactivity Expiration - Default = 4 Hours
    private $expiration = 14400;

    // Set Session Lifetime To 7 Days ( Session Automatically Destroyed After This Time )
    private $lifetime   = 604800;

    // Set A Custom Session Name
    private $name       = 's';

    // Session Path
    private $path       = '/';

    // Set To true If Using HTTPS.
    private $secure     = true;


    /**
     *  Define Class Dependencies
     */
    public function __construct(Database $db, Input $input, $domain, $secure = true) {
        $this->db = $db;
        $this->input    = $input;
        $this->domain   = $domain;
        $this->secure   = $secure;
    }


    /**
     *  Define Remaining Session Configuration & Start Session
     */
    public function start() {
        if (session_id() !== '') {
            return false;
        }

        session_set_save_handler(
            [$this, 'open'],
            [$this, 'close'],
            [$this, 'read'],
            [$this, 'write'],
            [$this, 'destroy'],
            [$this, 'gc']
        );

        // Set the hash function.
        if (in_array('sha512', hash_algos())) {
            ini_set('session.hash_function',        'sha512');
        }

        // How many bits per character of the hash.
        ini_set('session.hash_bits_per_character',  6);

        // Forces PHP Not to Include Session in Url
        ini_set('session.use_trans_sid',            0);

        // Forces Sessions to Only Use Cookies.
        ini_set('session.use_only_cookies',         1);

        // Prevent Obtaining Session by Using XSS
        ini_set('session.cookie_httponly',          1);

        // Prevent Session Fixation
        ini_set('session.use_strict_mode',          1);
        ini_set('session.entropy_file',             '/dev/urandom');
        ini_set('session.entropy_length',           16);

        // Extend Life of Sessions
        ini_set('session.gc_maxlifetime',           $this->lifetime);

        // Set Cookie Params
        session_set_cookie_params(
            $this->lifetime,
            $this->path,
            $this->domain,
            $this->secure,
            $this->httpOnly
        );

        // Set Session Name, and Start Session
        session_name($this->name);
        session_start();

        $this->gc();
    }


    /**
     *  Define Session Data
     *
     *  @param string $key          Session Var ID
     *  @param mixed  $value        Any Type Of Data
     */
    public function set($key, $value = '') {
        if (is_string($key)) {
            $_SESSION[$key] = $value;
        }
        elseif ($this->isAssoc($key)) {
            foreach ($key as $name => $value) {
                $this->set($name, $value);
            }
        }
    }
    public function isAssoc($array) {
        if (is_array($array)) {
            return array_keys($array) !== range(0, count($array) - 1);
        }
        return false;
    }


    /**
     *  If Session Key Not Set, Assign Default Value
     *
     *  @param string $key          Session Var ID
     *  @param string $default      Default Value Type
     */
    public function setDefault($key, $default = '') {
        $this->has($key) ?: $this->set($key, $default);
    }


    /**
     *  Get Session By Key
     *
     *  If Session Key Is Array It Will Not Be Filtered/Sanitized.
     *
     *  @param  string $key         Session Var ID
     *  @param  string $default     Default Value To Return
     *  @return mixed               Session Data
     */
    public function get($key = '', $default = '') {
        if ($key) {
            return $this->has($key) ? $this->input->get($key, 'session') : $default;
        }
        return '';
    }


    /**
     *  Is Session Key Exists
     *
     *  @param  string $key         Session Var ID
     *  @return bool                true|false
     */
    public function has($key) {
        return $key && isset($_SESSION[(string) $key]) ? true : false;
    }


    /**
     *  Add To End Of Session Array
     *
     *  @see                        $this->set Comments
     */
    public function add($key, $data) {

        // Set Default Value As Array To Ensure Data Can Be Appended
        $this->setDefault($key, []);

        // Append Data
        $_SESSION[$key][] = $data;
    }


    /**
     *  Merge Array Data With Session
     *
     *  @see                        $this->set Comments
     */
    public function merge($key = '', $value = []) {
        if ($this->has($key) && $value) {
            $_SESSION[$key] = array_merge($_SESSION[$key], (array) $value);
        }
    }


    /**
     *  Unset Session Key/Value
     *
     *  @param string $key          Session Var ID
     *  @param string $sub          Sub Session Key
     */
    public function remove($key) {
        if ($this->has($key)) {
            unset($_SESSION[$key]);
        }
    }


    /**
     *  Regenerate Session ( Generates New Session ID Destroying Old )
     *
     *  @param bool $flush          Should Session Data Be Cleared
     */
    public function regenerate($flush = false) {
        if ($flush === true) {
            $_SESSION = [];
        }
        session_regenerate_id(true);
        $this->start();
    }


    /**
     *  Check Open Database Connection
     *
     *  @return true|false          Based On Valid DB Connection
     */
    public function open() {
        $this->gc();
        return true;
    }


    /**
     *  Do Not Close DB Connection ( Using Persistent PDO )
     *
     *  @return true
     */
    public function close() {
        return true;
    }


    /**
     *  Read Session Data Based On ID
     *
     *  @param string $id           Session ID To Read From
     */
    public function read($id) {
        $id         = $this->input->sanitize($id);
        $session    = $this->db->fetch($this->table, ['id' => $id, 'status' => 0], 'LIMIT 1');

        return $session ? $session['data'] : '';
    }


    /**
     *  Write Session Data To DB
     *
     *  @param string $id           Session ID
     *  @param mixed  $data         Session Data
     */
    public function write($id, $data) {

        // Filter Session ID, and Define Expiration
        $id         = $this->input->sanitize($id);
        $expires    = time() + $this->expiration;

        // Search For Active Session
        $session    = $this->db->fetch($this->table, ['id' => $id, 'status' => 0], 'LIMIT 1');


        // Update Existing Session Or Create New
        if ($session) {
            $this->db->update($this->table, compact('expires', 'data'), compact('id'));
        } else {
            $this->db->insert($this->table, compact('id', 'expires', 'data'));
        }
        return true;
    }


    /**
     *  Destroy Session
     *
     *  @param string $id       Session ID To Destroy
     */
    public function destroy($id) {
        $this->db->update($this->table, ['status' => 1], ['id' => $this->input->sanitize($id)]);
        return true;
    }


    /**
     *  Delete Session
     *
     *  @param array $where     Params To Use When Deleting Session
     */
    public function delete($where, $rule) {
        $this->db->update($this->table, ['status' => 1], $where, $rule);
    }


    /**
     *  Garbage Collection ( Destroy Expired Sessions )
     *
     *  @param int $limit       Destroy Limit Per GC Call
     */
    public function gc($limit = 25) {
        $this->db->delete($this->table, [], 'status = 1 OR expires < ' . time() . ' LIMIT ' . $limit);
        return true;
    }
}

<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Input Helper
 *
 *  Filter/Sanitize User Input, As Well As Escaping/Decoding Input.
 *
 */

namespace TEMP\Input;

class Input {


    // Escaping Flags
    private $flags = ENT_QUOTES | ENT_HTML5;


    /**
     *  Grab GET, POST, SESSION, COOKIE, or SERVER Var and Sanitize
     *
     *  @param  string $name            Var/Field Name
     *  @param  string $type            Var Type
     *  @param  string $filter          Type Of Input Filter To Use ( Sanitation )
     *  @return string                  Return Var If Exists || Assoc Array || Empty String
     */
    public function get($name, $type = 'post', $filter = 'string') {

        if     ($type == 'get'      AND isset($_GET[$name]))       {   $value = $_GET[$name];      }
        elseif ($type == 'post'     AND isset($_POST[$name]))      {   $value = $_POST[$name];     }
        elseif ($type == 'session'  AND isset($_SESSION[$name]))   {   $value = $_SESSION[$name];  }
        elseif ($type == 'cookie'   AND isset($_COOKIE[$name]))    {   $value = $_COOKIE[$name];   }
        elseif ($type == 'server'   AND isset($_SERVER[$name]))    {   $value = $_SERVER[$name];   }

        if (isset($value)) {
            $value = is_array($value) ? $value : $this->sanitize($value, $filter);
        }
        elseif (!isset($value) || !$value || $value === null) {
            $value = '';
        }
        return $value;
    }


    /**
     *  Sanitize User Input
     *
     *  @param  string $input           Input To Sanitize
     *  @param  string $type            Input Type
     *  @return string                  Sanitized Input
     */
    public function sanitize($input = '', $type = 'string') {
        if(is_array($input)) {  return $input;  }

        switch ($type) {
            case 'email':
                $pattern= '/[^\pL\pN!#$%&\'\*\+\-\/\=\?\^\_`\{\|\}\~\@\.\[\]]/u';
                $input  = preg_replace($pattern, '', $input);

                $filter = FILTER_SANITIZE_EMAIL;
                break;

            case 'int':
                $filter = FILTER_SANITIZE_NUMBER_INT;
                break;

            case 'float':
                $filter = FILTER_SANITIZE_NUMBER_FLOAT;
                $flags  = [
                    FILTER_FLAG_ALLOW_FRACTION,
                    FILTER_FLAG_ALLOW_THOUSAND
                ];
                break;

            case 'url':
                $pattern= '/[^\pL\pN$-_.+!*\'\(\)\,\{\}\|\\\\\^\~\[\]`\<\>\#\%\"\;\/\?\:\@\&\=\.]/u';
                $input  = preg_replace($pattern, '', $input);

                $filter = FILTER_SANITIZE_URL;
                break;

            case 'string':
            default:
                $filter = FILTER_SANITIZE_STRING;
                $flags  = [
                    FILTER_FLAG_NO_ENCODE_QUOTES
                ];
                break;
        }
        return filter_var(trim($input), $filter, (isset($flags) ? compact('flags') : ''));
    }


    /**
     *  Input Validation
     *
     *  @param  string $input           Input Needing Validation
     *  @param  string $type            Type Of Validation To Run
     *  @return bool
     */
    public function validate($input, $type = 'email') {
        if(is_array($input)) {
            return $input;
        }

        switch ($type) {
            case 'email':
                $filter = FILTER_VALIDATE_EMAIL;
                break;

            case 'float':
                $filter = FILTER_VALIDATE_FLOAT;
                $flags  = [
                    FILTER_FLAG_ALLOW_THOUSAND
                ];
                break;

            case 'int':
                $filter = FILTER_VALIDATE_INT;
                break;

            case 'ip':
                $filter = FILTER_VALIDATE_IP;
                $flags  = [
                    FILTER_FLAG_NO_PRIV_RANGE,
                    FILTER_FLAG_NO_RES_RANGE
                ];
                break;
        }
        return (bool) filter_var(trim($input), $filter, (isset($flags) ? $flags : []));
    }


    /**
     *  Escape User Input
     *
     *  @param  string $input           Input To Escape
     *  @return string                  Escaped Input
     */
    public function escape($input = '') {
        return $input && (is_string($input) || is_numeric($input)) ? htmlspecialchars((string) $input, $this->flags, 'UTF-8', false) : '';
    }


    /**
     *  Decode User Input
     *
     *  @param  string $input           Input To Decode
     *  @return string                  Decoded Input
     */
    public function decode($input = '') {
        return $input ? htmlspecialchars_decode($input, $this->flags) : '';
    }
}

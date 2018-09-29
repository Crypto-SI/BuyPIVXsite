<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Array Helper
 *
 *  Provides Various Array Related Helper Functions To Make Life Easier.
 *
 */

namespace TEMP\Helpers;

use TEMP\Traits\DotNotation;
use TEMP\Input\Input;

class Arr {
    use DotNotation;


    /**
     *  Define Class Dependencies
     *
     *  @param object $input        Input Sanitizing/Escaping Helper
     */
    public function __construct(Input $input) {
        $this->input = $input;
    }


    /**
     *  Iterate Through Array, Escaping Values For Output When Escaping Entire
     *  Array Use Needles Param Slot
     *
     *  @param  string $needles     Data Keys To Set Using Input Helper
     *  @param  array  $haystack    Array To Iterate And Sanitize Values For Output
     *  @return array               Data After Needles Were Escaped
     */
    public function escape($needles = [], $haystack = []) {

        // Needles Is Sequential Array Of Keys To Escape Within Haystack
        if (!$this->isAssoc($needles) && $this->isAssoc($haystack)) {
            foreach ((array) $needles as $needle) {
                $haystack[$needle] = $this->input->escape($this->get($needle, $haystack));
            }
            return $haystack;
        }

        // Needles Is Now Haystack - Escape All Array Data
        elseif (is_array($needles) && !$haystack) {
            return $this->traverse($needles, 'input', 'escape');
        }
    }


    /**
     *  Flatten Array Using Dot Notation
     *
     *  @param  array  $data        Array To Flatten
     *  @param  string $prepend     String To Prepend Between Array Keys
     *  @param  bool   $list        If True Merge Array Sub Values Without Prepended Keys
     *  @return array               Flattened Array
     */
    public function flatten($data = [], $prepend = '', $list = false) {
        $results = [];

        if ($list) {
            foreach ($data as $d) {
                if (is_array($d)) {
                    $results = array_merge($results, $this->flatten($d, '', $list));
                } else {
                    $results[] = $d;
                }
            }
        } else {
            foreach ((array) $data as $key => $value) {
                if (is_array($value)) {
                    $results = array_merge($results, $this->flatten($value, $prepend . $key . '.', $list));
                } else {
                    $results[$prepend . $key] = $value;
                }
            }
        }
        return $results;
    }


    /**
     *  Iterate Through Array Sanitizing Array Values
     *
     *  @param  array $array        Array To Run Through Input Filter
     *  @return array               Filtered Array
     */
    public function sanitize($data = []) {
        return $this->traverse($data);
    }


    /**
     *  Private Array Traverse Helper Used For Sanitizing/Setting Array Values
     *
     *  Arrays Are Traversed & Passed To Input Helper For Sanitation/Output Setting
     *
     *  @param  array  $data        Array Data To Traverse
     *  @param  string $class       Class   To Use
     *  @param  string $method      Method  To Use  ( From Class Defined Above )
     *  @return array               Completed Data  ( Sanitized or Set For Output )
     */
    private function traverse($data = [], $class = 'input', $method = 'sanitize') {
        if (
            !$data                                      ||
            !in_array($class,  ['input'])               ||
            !in_array($method, ['escape', 'sanitize'])
        ) {
            return '';
        }

        // Assoc Array
        if ($this->isAssoc($data)) {
            foreach ($data as $key => $value) {
                $output[$this->input->{$method}($key)] = $this->traverse($value);
            }
        }

        // Data Is Sequential
        elseif (is_array($data)) {
            $output = [];

            foreach ($data as $d) {
                $output[] = $this->traverse($d);
            }
        }

        // Data Is A String And Can Be Passed To Input
        else {
            $output = $this->input->{$method}($data);
        }
        return $output;
    }


    /**
     *  Search Array For Value, If Not Found Return Default Text
     *
     *  @param  string $key         Keys To Search For Using "." Notation
     *  @param  array  $data        Array To Traverse
     *  @param  mixed  $default     Information To Return If Data Not Found
     *  @return mixed               Data Found Or Default Data
     */
    public function get($key, $data, $default = '') {
        $this->getdot($data, $key);
        return $data ? $data : $default;
    }


    /**
     *  Set Value Within Array Using '.' Notation
     *
     *  @param  array  $data        Array To Add Data To
     *  @param  string $key         Keys To Search For Using "." Notation
     *  @param  string $value       Value To Set
     */
    public function set($data, $key, $value = []) {
        if (is_string($key)) {
            $this->setdot($data, $key, $value);
        }
        elseif ($this->isAssoc($key)) {
            foreach ($key as $k => $value) {
                $data = $this->set($data, $k, $value);
            }
        }
        return $data;
    }


    /**
     *  Check If Needles Exists Within Haystack
     *
     *  @param  array $needles      Array Keys || Values To Use Within Search
     *  @param  array $haystack     Assoc || Sequential Array To Search Through
     *  @return bool
     */
    public function has($needles, $haystack = '') {
        if (!$haystack) {  return false;  }

        $haystack   = (array) $haystack;
        $type       = $this->isAssoc($haystack) ? 'assoc' : 'sequential';

        foreach ((array) $needles as $n) {
            if (
                ($type == 'assoc'       && !array_key_exists($n, $haystack)) ||
                ($type == 'sequential'  && !in_array($n, $haystack) && $haystack[$n])
            ) {
                return false;
            }
        }
        return true;
    }


    /**
     *  Encode To JSON
     *
     *  @param  array $input        Input To JSON Encode
     *  @return string              JSON Encoded Array
     */
    public function encode($input = []) {
        return $input ? json_encode((array) $input) : '';
    }


    /**
     *  Decode JSON
     *
     *  @param  string $input       JSON To Decode
     *  @param  string $assoc       Decode As Assoc Array (true || false)
     *  @return string              JSON Decoded Array
     */
    public function decode($input = '', $assoc = true) {
        return $input && $this->isJSON($input) ? json_decode($input, $assoc) : [];
    }


    /**
     *  Check If Array Is Associative Or Sequential
     *
     *  @param  array $array        Array To Check
     *  @return bool                True If Assoc Array
     */
    public function isAssoc($array) {
        if (is_array($array)) {
            return array_keys($array) !== range(0, count($array) - 1);
        }
        return false;
    }


    /**
     *  Check If Array Is Encoded In Json Format
     *
     *  @param  array $input        Input To Check
     *  @return bool                True If Json
     */
    public function isJSON($input = '') {
        if ($input && is_string($input)) {
            json_decode($input);
            return (json_last_error() == JSON_ERROR_NONE);
        }
        return false;
    }


    /**
     *  Print Array In Human Readable Format
     *
     *  @param mixed $input         Data To Output To Screen
     */
    public function printdata($input) {
        print '<pre>';
        print_r($input);
        print '</pre>';
        exit;
    }
}

<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Dot Notation Helper Methods
 *
 *  Use References And Dot Notation To Manipulate Or Retrieve Values From Arrays
 *
 */

namespace TEMP\Traits;

trait DotNotation {


    /**
     *  Get Data From Array
     *
     *  @param array  $array        Container To Reference For Data Retrieval
     *  @param string $key          Path To Get Data In '.' Notation
     */
    public function getdot(&$array, $key) {
        foreach (explode('.', (string) $key) as $k) {
            if (!$array = isset($array[$k]) ? $array[$k] : '') {
                break;
            }
        }
    }


    /**
     *  Set Data Within Array
     *
     *  @param array  $array        Container To Reference For Data Setting
     *  @param string $key          Path To Set Data In '.' Notation
     *  @param mixed  $value        Value To Set
     */
    public function setdot(&$array, $key, $value = '') {
        foreach (explode('.', (string) $key) as $k) {
            if (!isset($array[$k]) || !is_array($array[$k])) {
                $array[$k] = [];
            }
            $array = &$array[$k];
        }
        $array = $value;
    }
}

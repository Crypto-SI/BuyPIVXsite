<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Data Collections Helpers
 *
 *  Decorator Used To Manipulate/Access Large Groups ( Collections ) Of Data.
 *
 */

namespace TEMP\Helpers;

class Collections {


    // Data Container
    protected $data = [];


    /**
     *  Define Class Dependencies
     *
     *  @param object $array       Array Helper
     */
    public function __construct(Arr $array) {
        $this->array = $array;
    }


    /**
     *  Set Collections Data
     *
     *  @param  array $data     Data To Define Within Collection Container
     *  @return object          This Object
     */
    public function setData($data) {
        $this->data = $data;
        return $this;
    }


    /**
     *  Get All Collections Data
     *
     *  @return array           Data Container
     */
    public function getData() {
        return $this->data;
    }


    /**
     *  Has Collections Data
     *
     *  @return bool            Does Container Have Data
     */
    public function hasData() {
        return $this->data ? true : false;
    }


    /**
     *  Search Collection Data For Value, If Not Found Return Default
     *
     *  @see                    $this->array Comments
     */
    public function get($key, $default = '') {
        return $this->array->get($key, $this->data, $default);
    }


    /**
     *  Set Value Within Collection Data Using '.' Notation
     *
     *  @see                    $this->array Comments
     */
    public function set($key, $value = []) {
        $this->data = $this->array->set($this->data, $key, $value);
    }
}

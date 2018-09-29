<?php

/**
 *------------------------------------------------------------------------------
 *
 *  eSportspCMS Reflection Decorator - Dependency Injection Container Helper
 *
 *  The Reflection Class Is Responsible For Assisting With Dependency Injection
 *  Within The Container By Simplifying/Abstracting The Reflection Process For
 *  The Container.
 *
 */

namespace TEMP\Container;

use Exception;
use ReflectionClass;

class Reflection {


    // $class Reflection
    private $class;

    // $class Constructor Data
    private $constructor;

    // $class Parameter Data
    private $parameters;

    // $class Parameter Typehint Data
    private $typehints  = [];


    // Error Messages
    private $errors     = [
        'exists'            => 'IoC Container Error: Class Does Not Exist ',
        'instantiable'      => 'IoC Container Error: Class Is Not Instantiable ',
        'misconfigured'     => 'IoC Container Error: Class Empty Or Is Not A String '
    ];


    /**
     *  Create/Set A New Reflection Of $class
     *
     *  @param string $class        Class Name
     */
    public function __construct($class) {
        if ($error = $this->invalidClass($class)) {
            throw new Exception($this->error[$error] . $class);
        }
        $this->setReflection($class);
    }


    /**
     *  Return Error Code ID If Invalid
     *
     *  @param  string $class       Class Name
     *  @return string              Error ID | Empty String
     */
    private function invalidClass($class) {
        return !is_string($class) ? 'misconfigured' : (!class_exists($class) ? 'exists' : '');
    }


    /**
     *  Set Class Reflection
     *
     *  @see                        Constructor Comments
     */
    private function setReflection($class) {
        $this->class = new ReflectionClass($class);

        if (!$this->class->isInstantiable()) {
            throw new Exception($this->error['instantiable'] . $class);
        }
    }


    /**
     *  $this->class Constructor Data
     *
     *  If The Data Has Not Yet Been Set Create And Set, Then Return
     *
     *  @return mixed               Class Reflection Constructor Data
     */
    public function constructor() {
        if (!$this->constructor) {
            $this->constructor = $this->class->getConstructor();
        }
        return $this->constructor;
    }


    /**
     *  $this->class Public Constructor
     *
     *  Determines Whether Or Not A Constructor Is Set And Is Public
     *
     *  @return bool                Constructor Is Public Indicator
     */
    public function hasPublicConstructor() {
        return $this->constructor() && $this->constructor()->isPublic();
    }


    /**
     *  $this->class Constructor Parameter Data
     *
     *  If The Data Has Not Yet Been Set Create And Set, Then Return
     *
     *  @return mixed               Class Reflection Parameter Data
     */
    public function parameters() {
        if (!$this->parameters && $constructor = $this->constructor()) {
            $this->parameters = $constructor->getParameters();
        }
        return $this->parameters;
    }


    /**
     *  $this->class Constructor Typehint Data
     *
     *  If The Data Has Not Yet Been Set Create And Set, Then Return
     *
     *  @return array               Class Reflection Constructor Typehint Data
     */
    public function dependencyTypeHints() {
        if (!$this->typehints && $parameters = $this->parameters()) {
            $this->defineTypeHints($parameters);
        }
        return $this->typehints;
    }


    /**
     *  Define $this->class Constructor Typehints
     *
     *  @param array $parameters    Class Constructor Parameters
     */
    private function defineTypeHints($parameters) {
        foreach ((array) $parameters as $param) {
            if (!is_null($param = $param->getClass())) {
                $this->typehints[] = $param->getName();
            }
        }
    }
}

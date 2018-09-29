<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Dependency Builder + Injector + Container
 *
 *  The Container Is Responsible For Dependency Resolution And Injection Via
 *  Class Constructors.
 *
 */

namespace TEMP\Container;

use Closure;
use Exception;

class Container {


    // Alias Container
    protected $aliases    = [];

    // Bindings Container
    protected $bindings   = [];

    // Object Instance Cache Container
    protected $cache      = [];


    // Error Messages
    private $error      = [
        'build'     => 'IoC Container Error: Container Could Not Build Class ',

        'make'      => 'IoC Container Error: Could Not Resolve Alias %s, Both
                        Binding And Class Could Not Be Found'
    ];


    /**
     *  Set Data Within Containers
     *
     *  @param string $key              Key To Use When Setting Container Var
     *  @param mixed  $value            Value To Use When Setting Container Var
     */
    public function alias($key, $value) {   $this->aliases[$key]    = $value;   }
    public function bind ($key, $value) {   $this->bindings[$key]   = $value;   }
    public function cache($key, $value) {   $this->cache[$key]      = $value;   }


    /**
     *  Retrieve Alias Value
     *
     *  @param  string $key             Alias Key
     *  @return string                  Alias Value
     */
    public function getConcrete($key) {
        return isset($this->aliases[$key])  ? $this->aliases[$key]  : $key;
    }


    /**
     *  Create New Class Instance
     *
     *  Forces $this->make() To Instantiate A New Class Instead Of Returning
     *  A Cached Instance.
     *
     *  @see                            $this->make() Comments
     */
    public function newinstance($class, $params = []) {
        return $this->make($class, $params, false, true);
    }


    /**
     *  Pull Class From Cache , Call Binding Or Instantiate Class. Resolve The
     *  Dependencies, Set Dependencies Within Class Construct And Return.
     *
     *  @param  string $key             Alias Key | Binding Key | Class To Build
     *  @param  array  $params          Additional Params To Pass Through Constructor
     *  @param  bool   $cache           Determines If Class Can Be Cached
     *  @param  bool   $new             Forces New Instance Of Object
     *  @return object
     */
    public function make($key, $params = [], $cache = true, $new = false) {

        // Define Concrete Class
        $concrete = $this->getConcrete($key);


        // If Params Indicate Cached Instance Can Be Used And Exists Return
        if (!$new && isset($this->cache[$concrete])) {
            return $this->cache[$concrete];
        }

        /**
         *  Bindings Are Referenced By Alias/Annotation Key
         *
         *  Alias/Annotation Keys Are Used As Bindings To Allow Direct Concrete
         *  Access If Needed. For Reference See Framework Service Providers.
         */
        if (isset($this->bindings[$key]) && is_callable($this->bindings[$key])) {
            $class = $this->bindings[$key]($this);
        }

        // Cache And Binding Search Failed! Attempt To Build The Class
        elseif (class_exists($concrete)) {
            $class = $this->build($concrete, $params);
        }


        // If Class Created, Cache Class ( If Cacheable ) And Return
        if (isset($class)) {
            !$cache ?: $this->cache($concrete, $class);
            return $class;
        }


        // If We Made It This Far Object Wasn't Created Throw Exception
        throw new Exception(sprintf($this->error['make'], $key));
    }


    /**
     *  Build Concrete Class
     *
     *  @param  string $concrete        Name Of Class To Instantiate And Build Dependencies For
     *  @param  array  $params          Additional Params To Pass Through Constructor
     *  @return object
     */
    private function build($concrete, $params = []) {

        // Create Class Reflection Decorator
        $class = new Reflection($concrete);


        // Class Construct Is Missing Or Construct Exists With 0 Parameters
        if (!$class->constructor() || count($class->parameters()) === 0) {
            return new $concrete();
        }

        // Construct Exists But Not Public. We Cannot Build Class. Abandon Ship!
        if (!$class->hasPublicConstructor()) {
            throw new Exception($this->error['build'] . $concrete);
        }


        // Build Class Dependencies, Pass Via Construct And Return
        return new $concrete(...array_merge(
            (array) $this->buildDependencies($class->dependencyTypeHints()),
            (array) $params
        ));
    }


    /**
     *  Build Typehinted Dependencies
     *
     *  @param  array $typehints        Contains Construct Dependency Typehints
     *  @return array                   Instantiated Dependencies
     */
    private function buildDependencies($typehints = []) {
        if (!$typehints) {
            return;
        }

        $dependencies = [];
        foreach ($typehints as $dependency) {
            $dependencies[] = $this->make($dependency);
        }
        return $dependencies;
    }
}

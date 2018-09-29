<?php

/**
 *------------------------------------------------------------------------------
 *
 *  eSportspCMS Router - Route Mapper
 *
 *  The Router Is Responsible For Mapping The Http Request Against The Routes
 *  Defined Within The Application Configuration Directory. If A Route Is
 *  Not Matched The Router Will Use The Fallback Controller -> Method.
 *
 */

namespace TEMP\Routing;

class Router {

    // Default Controller, Method, Params Values
    private $controller = '';
    private $method     = 'index';
    private $params     = [];

    // Routes, Regex Container
    private $routes     = [];
    private $regex      = [
        ':any'  => '(.*)',
        ':num'  => '[0-9]+',
        ':slug' => '[a-zA-Z0-9-]+'
    ];

    // Fallback Controller -> Method
    private $default    = [
        'controller'        => '',
        'method'            => ''
    ];

    // Controller Namespace
    private $namespace  = [
        'controller'        => ''
    ];

    // Http Request Data
    private $subdomain, $uri;



    /**
     *  Define Class Dependencies
     *
     *  @param array  $namespace        Assoc Array Containing Controller Namespace
     *  @param string $subdomain        Site Subdomain
     *  @param string $uri              Site Uri
     *  @param array  $configuration    Assoc Array Containing Router Configuration
     */
    public function __construct($namespace, $subdomain, $uri, $configuration = []) {
        $this->namespace['controller'] = $namespace . '\\';
        $this->subdomain    = $subdomain;

        $this->uri          = $uri;
        $this->params       = explode('/', $uri);

        $this->default = $configuration['default'] ?? [];
        $this->routes = $configuration['routes'] ?? [];
    }


    /**
     *  Setters/Getters - Self Explanatory
     */
    public function setController($controller) {
        $this->controller = $controller;
    }
    public function setMethod($method) {
        $this->method = $method;
    }
    public function getParams() {
        return $this->params;
    }


    /**
     *  Map Request
     *
     *  @return array               Requested Controller, Method & Params
     */
    public function map() {

        // Map Route If Controller Has Not Been Set
        if (!$this->controller) {
            if (!array_key_exists($this->subdomain, $this->routes)) {
                $this->defaultRoute('Misconfigured Route: Subdomain Does Not Exist');
            }
            elseif (!$this->resolveRoute()) {
                $this->defaultRoute('Misconfigured Route: Route Resolution Failed');
            }
        }


        // Prepend Controller Namespace And Verify That Method Exists
        $this->controller = $this->namespace['controller'] . $this->controller;

        if(!in_array($this->method, (array) get_class_methods($this->controller))) {
            $this->defaultRoute('', 'Misconfigured Route: ' . $this->controller . ' Method Does Not Exist: ' . $this->method);
        }


        // Return Mapped Request
        return [
            'controller'    => $this->controller,
            'method'        => $this->method,
            'params'        => $this->params
        ];
    }


    /**
     *  Route Is Either Missing Or Something Occured In Preroute - Set Default Controller/Method
     *
     *  @param string $warning      If Route Was Not Found Log As Warning
     *  @param string $emergency    If A Method Is Missing From Controller Log As Emergency
     *  $param string $method       Pass A Method To Use Instead Of Default Method
     */
    public function defaultRoute($warning = '', $emergency = '', $method = '') {
        $this->controller   = $this->namespace['controller'] . $this->default['controller'];
        $this->method       = $method ? $method  : $this->default['method'];
    }


    /**
     *  Resolve Route
     *
     *  @return bool                Whether Or Not Route Was Found
     */
    private function resolveRoute() {
        $routes = $this->routes[$this->subdomain];
        $subdir = $this->subdomain;

        if (array_key_exists($this->uri, $routes)) {
            $route = $routes[$this->uri];
        }
        else {
            foreach ((array) $routes as $key => $value) {
                $filter     = str_replace(array_keys($this->regex), array_values($this->regex), $key);
                $pattern    = '/^' . str_replace('/', '\/', $filter) . '$/';

                if (preg_match($pattern, $this->uri)) {
                    $route  = $value;
                    break;
                }
            }
        }


        if (isset($route)) {
            $this->parse($route);
        }
        return (bool) isset($route);
    }


    /**
     *  Define Controller, Method, And Params
     *
     *  @param string $route        Route Config Provided By $this->resolve()
     */
    private function parse($route) {
        $explode            = explode('@', $route[0]);

        // Set Controller
        $this->controller   = $explode[0];

        // Set Method
        $method             = isset($explode[1]) ? $explode[1] : $this->method;
        $this->method       = str_replace('-', '', is_numeric($method) ? $this->params[$method] : $method);

        // Unset Params If Route Unset Param Defined
        if (isset($route[1]) && $this->params) {
            foreach((array) $route[1] as $p) {
                unset($this->params[$p]);
            }
        }
    }
}

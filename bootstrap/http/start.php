<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Load Global Bootstrapper
 *
 */

require __DIR__ . "/../start.php";


/**
 *------------------------------------------------------------------------------
 *
 *  Build
 *
 *  TODO: Replace Everything When Providers When Replacing TEMP
 *
 */

// Lazy Session Provider
$session = $container->make(TEMP\Session\Session::class, [
    $input->sanitize(ltrim($_SERVER['HTTP_HOST'], 'www.')),
    (bool) (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
]);
$session->start();
$session->delete([], "expires < " . time());


// URI
$uri = explode('?', trim($_SERVER['REQUEST_URI'], '/'))[0];


// Cache Redirect Class With Current URI
$container->make(TEMP\Http\Http::class, [$uri]);

// Cache View Handler - User 'Middleware' Sets Data
$view = $container->make(TEMP\View\View::class, [[
    'layouts' => "{$paths['resources.views']}/layout",
    'pages' => "{$paths['resources.views']}/pages",
    'templates' => "{$paths['resources.views']}/templates"
]]);


/**
 *------------------------------------------------------------------------------
 *
 *  Register Global View Data
 *
 */

$view->set('icon', new TEMP\Icon("{$paths['resources.assets']}/design-system/modules/icons/svg/"));
$view->set('paths', $paths);

$view->set('e', function($value) use ($input) {
    return $input->escape($value);
});

// Dependent On Above
$view->set('sitetitle', $app['sitetitle']);
$view->set('header', $view->render("{$paths['resources.views']}/templates/header.php"));
$view->set('footer', $view->render("{$paths['resources.views']}/templates/footer.php"));
$view->set('alerts', $container->make(TEMP\Flash\Flash::class)->dump());


/**
 *------------------------------------------------------------------------------
 *
 *  Match Route And Dispatch
 *
 */

// Lazy Router Provider
$router = $container->make(TEMP\Routing\Router::class, [
    App\Controllers::class, 'www', $uri, (require "{$paths['config']}/routes.php")
]);

// Map Request And Dispatch
$request = $router->map();

echo call_user_func_array(
    [$container->make($request['controller']), $request['method']], $request['params']
);

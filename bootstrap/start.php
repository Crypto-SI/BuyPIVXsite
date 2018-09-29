<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Load Configuration + Paths
 *
 */

$paths = require __DIR__ . "/../config/paths.php";
$app = require $paths['config'] . '/app.php';


/**
 *------------------------------------------------------------------------------
 *
 *  Temporary Error Handler
 *
 */

error_reporting(E_ALL | E_STRICT);
ini_set("log_errors", 1);
ini_set("error_log", $paths['storage'] . "/logs/error.log");
ini_set("display_errors", $app['production'] ? 0 : 1);


/**
 *------------------------------------------------------------------------------
 *
 *  Handle Emergency Shutdowns
 *
 */

if ($app['production'] && !isset($cron)) {
    register_shutdown_function(function () use ($paths) {
        if (error_get_last()['type'] == E_ERROR) {
            require_once "{$paths['resources.views']}/layout/shutdown.php";
            exit();
        }
    });
}


/**
 *------------------------------------------------------------------------------
 *
 *  Register Autoloader And Define Directories/Namespaces
 *
 *  TODO: Replace Everything In TEMP When We Have A Bit More Time
 *
 */

(require "{$paths['src']}/Autoloader.php")->addNamespaces([
    'App\\' => "{$paths['src']}/Application/",
    'TEMP\\'=> "{$paths['src']}/TEMP/",
    'Stripe\\'=> "{$paths['src']}/Vendor/Stripe/"
]);


/**
 *------------------------------------------------------------------------------
 *
 *  Build Services ( Lazy Providers )
 *
 */

// IOC Container
$container = new \TEMP\Container\Container();

// Lazy Database Provider
$db = $container->make(TEMP\Database\Database::class, [(require "{$paths['config']}/database.php")]);

// Input Sanitizer
$input = $container->make(TEMP\Input\Input::class);

// Cache Configuration Class With App Variable
$container->make(App\Entities\Configuration::class, [$app]);

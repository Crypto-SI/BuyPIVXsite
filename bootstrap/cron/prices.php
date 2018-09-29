<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Load Cron Bootstrapper
 *
 */

require 'start.php';


/**
 *------------------------------------------------------------------------------
 *
 *  Save New Prices
 *
 */

$container->make(App\Services\Prices::class)->update();
exit();

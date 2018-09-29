<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Define The List Of Paths Required By This Application
 *
 */

return [

    /**
     *--------------------------------------------------------------------------
     *
     *  Config
     *
     */

    'config' => realpath(__DIR__),


    /**
     *--------------------------------------------------------------------------
     *
     *  Public
     *
     */

    'public' => realpath(__DIR__ . '/../public'),


    /**
     *--------------------------------------------------------------------------
     *
     *  Src
     *
     */

    'src' => realpath(__DIR__ . '/../src'),


    /**
     *--------------------------------------------------------------------------
     *
     *  Resources
     *
     */

    'resources' => realpath(__DIR__ . '/../resources'),
    'resources.assets' => realpath(__DIR__ . '/../resources/assets'),
    'resources.views' => realpath(__DIR__ . '/../resources/views'),


    /**
     *--------------------------------------------------------------------------
     *
     *  Root Directory
     *
     */

    'root' => realpath(__DIR__ . '/..'),


    /**
     *--------------------------------------------------------------------------
     *
     *  Storage
     *
     */

    'storage' => realpath(__DIR__ . '/../storage'),


    'sslcert' => realpath(__DIR__ . '/../../ssl/certs/metavs_net_a9c94_27237_1539671648_40df1c462700c5c29f9e2e52339ef12a.crt')
];

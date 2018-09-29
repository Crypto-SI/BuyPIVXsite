<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Router Default Settings + Routes
 *
 */

return [

    /**
     *-------------------------------------------------------------
     *
     *  Url Routing
     *
     *-------------------------------------------------------------
     *
     *  Available Regex Options:
     *
     *  $regex = [
     *     ':any'      => '(.*)',
     *     ':num'      => '[0-9]+',
     *     ':slug'     => '[a-zA-Z0-9-]+'
     *  ];
     *
     *  ucfirst Is Forced On Classes ( Does Not Include Methods )
     *
     *  Route Format:
     *
     *  'SUBDOMAIN' => [
     *      'URI' => ['CONTROLLER@METHOD', [UNSET BY ARRAY ORDER]];
     *  ]
     *
     */

    'default'   => [
        'controller' => 'Index',
        'method' => 'index'
    ],
    'routes' => [
        'www' => [
            '' => ['Index'],

            'legal' => ['Pages@legal'],
            'privacy-policy' => ['Pages@privacy'],
            'terms' => ['Pages@terms'],

            // Replace/Create Messages
            'payment/failed' => ['Payment@failed'],
            'payment/success' => ['Payment@success'],

            'payment/paypal' => ['Paypal@ipn']
        ]
    ]
];

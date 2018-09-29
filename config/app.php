<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Basic Application Config
 *
 */

return [
    // Fee Percentage Needs To Be Set As A Float
    'fee' => 0.05,
	
	// Environment
    'production' => false,
    
    'sitetitle' => 'BuyPIVX',
    
    'ticker' => 'PIVX',
    
    // Email For Orders
    'email' => '',


    /**
     *--------------------------------------------------------------------------
     *
     *  Payment Configuration
     *
     *  Replace The Following In The 'payment.description' Key
     *  - [Brand Name] Replace With The Websites Name. IE: Crypto Gateway
     *  - [ticker]     Replace With Cryptocurrency Ticker. IE: BTC
     *  - [amount]     DO NOT TOUCH!
     *
     */

    'payment.description' => 'Purchasing [amount] PIVX From BuyPIVX',


    /**
     *--------------------------------------------------------------------------
     *
     *  Paypal Configuration
     *
     *  Follow Step 1 Only
     *  https://www.evoluted.net/thinktank/web-development/paypal-php-integration
     *
     */

    'paypal.email' => '',


    /**
     *--------------------------------------------------------------------------
     *
     *  Stripe Configuration
     *
     */

    'stripe.apikey.public' => '',
    'stripe.apikey.private' => ''
];

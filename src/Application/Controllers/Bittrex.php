<?php

namespace App\Controllers;

use Exception;
use TEMP\Curl\Curl;

class Bittrex
{

    private $api = "https://bittrex.com/api/v1.1/public/getticker?market=";

    private $market = [
        'base' => 'USD-BTC',
        'crypto' => 'BTC-PIVX'
    ];


    public function __construct(Curl $curl)
    {
        $this->curl = $curl;
    }


    public function execute()
    {
        $base = $this->curl->get("{$api}{$market['base']}");
        $crypto = $this->curl->get("{$api}{$market['crypto']}");

        if (!$base['success'] || !$crypto['success']) {
            throw new Exception("Bittrex API Call Failed");
        }
    }
}

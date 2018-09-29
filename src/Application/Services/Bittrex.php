<?php

namespace App\Services;

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


    public function fetchPrice()
    {
        $base = $this->fetchData('base');
        $crypto = $this->fetchData('crypto');

        if (!$base['success'] || !$crypto['success']) {
            throw new Exception("Bittrex API Call Failed");
        }

        $base = $this->largestPrice($base['result']);
        $crypto = $this->largestPrice($crypto['result']);
        $price = $base * $crypto;

        return compact('base', 'crypto', 'price');
    }

    private function fetchData($market)
    {
        return json_decode($this->curl->get("{$this->api}{$this->market[$market]}"), true);
    }

    private function largestPrice($result)
    {
        return $result['Ask'] > $result['Last'] ? $result['Ask'] : $result['Last'];
    }
}

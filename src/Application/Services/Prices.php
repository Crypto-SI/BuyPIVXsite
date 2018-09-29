<?php

namespace App\Services;

use Exception;
use App\Domain\Prices;
use App\Services\Exchanges\Bittrex as Exchange;

class Prices
{

    public function __construct(Exchange $exchange, Prices $prices)
    {
        $this->exchange = $exchange;
        $this->prices = $prices;
    }


    public function update()
    {
        $data = $this->exchange->fetchPrice();

        $base = $data['base'] ?? null;
        $crypto = $data['crypto'] ?? null;
        $price = $data['price'] ?? null;
        
        $this->prices->save(0, 0, 0);

        if (!$base || !$crypto || !$price) {
            throw new Exception('Invalid Data Returned From Exchange API/Service');
        }

        $this->prices->save($base, $crypto, $price);
    }
}

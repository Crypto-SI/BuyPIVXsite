<?php

namespace App\Services;

use Exception;
use App\Domain\Bittrex as BittrexDomain;
use App\Services\Bittrex as BittrexService;

class UpdatePrices {


    public function __construct(BittrexDomain $domain, BittrexService $service)
    {
        $this->domain = $domain;
        $this->service = $service;
    }


    public function execute()
    {
        $data = $this->service->fetchPrice();

        $base = $data['base'] ?? null;
        $crypto = $data['crypto'] ?? null;
        $price = $data['price'] ?? null;

        if (!$base || !$crypto || !$price) {
            throw new Exception('Invalid Data Returned From Exchange API/Service');
        }

        $this->domain->savePrice($base, $crypto, $price);
    }
}

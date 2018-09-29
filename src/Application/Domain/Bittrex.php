<?php

namespace App\Domain;

use TEMP\Database\Database;

class Prices
{

    public function __construct(Database $db)
    {
        $this->db = $db;
    }


    public function getLatest()
    {
        return $this->db->fetch('prices', [], 'ORDER BY id DESC LIMIT 1');
    }


    public function save($base, $crypto, $price)
    {
        $this->db->insert('prices', [
            'base' => $base,
            'crypto' => $crypto,
            'price' => $price,
            'time' => time()
        ]);
    }
}

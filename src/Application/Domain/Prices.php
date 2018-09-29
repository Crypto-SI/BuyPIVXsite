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
        return $this->db->fetch('prices', [], '`id` > 0 ORDER BY `id` DESC LIMIT 1');
    }
    
    
    public function needsUpdate() {
        $time = time() - (60 * 5);
        
        return !$this->db->fetch('prices', [], "time > $time");
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

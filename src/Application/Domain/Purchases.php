<?php

namespace App\Domain;

use TEMP\Database\Database;

class Purchases
{

    public function __construct(Database $db)
    {
        $this->db = $db;
    }


    public function create($crypto, $price, $fee, $total, $address, $provider)
    {
        $fields = compact('crypto', 'price', 'fee', 'total', 'address', 'provider');
        $fields['time'] = time();

        return $this->db->insert('purchases', $fields);
    }


    public function find(int $id)
    {
        return $this->db->fetch('purchases', compact('id'));
    }


    public function emailData() {
        return $this->db->fetchAll('purchases', ['status' => 1], '', 'id, crypto, address');
    }


    public function emailSent($data) {
        $update = [];

        if ($data) {
            foreach ($data as $d) {
                $update[] = $d['id'];
            }

            $this->db->update('purchases', ['status' => 2], [], 'id in(' . implode(',', $update) . ')');
        }
    }


    public function markComplete(int $id, string $data)
    {
        $this->db->update('purchases', [
            'data' => $data,
            'status' => 1
        ], compact('id'));
    }
}

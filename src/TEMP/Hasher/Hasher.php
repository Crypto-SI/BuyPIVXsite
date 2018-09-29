<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Data Hasher
 *
 *  One Way Data Hasher Using password_hash With The Default Hashing Algorithm
 *  And A Server Appropriate Cost Parameter.
 *
 */

namespace TEMP\Hasher;

class Hasher {


    // Cost Parameter Set When Creating Hash
    private $cost = 9;

    // Target Hashing Time Limit - Used By Benchmark To Define Appropriate Cost
    private $target = 0.1;


    /**
     *  Override Cost Setting If Set
     *
     *  @param integer $cost        Cost Setting For Hashing
     */
    public function __construct($cost = '') {
        $this->cost = ($cost && is_numeric($cost)) ? $cost : $this->cost;
    }


    /**
     *  Password Hash Cost Benchmarker
     *
     *  @param integer $target
     */
    public function benchmark($target = '') {
        $target = $target ? $target : $this->target;
        $cost   = 8;

        do {
            $cost++;
            $start  = microtime(true);
            password_hash('BenchmarkTest', PASSWORD_DEFAULT, ['cost' => $cost]);
            $end    = microtime(true);
        } while (($end - $start) < $target);

        exit('Appropriate Hashing Cost Found: ' . $cost);
    }


    /**
     *  Create Secure Hash ( One-Way Hash )
     *
     *  @param  string $input       String Of Text To Be Hashed
     *  @return string              One-Way Hashed String
     */
    public function create($input) {
        return password_hash($input, PASSWORD_DEFAULT, ['cost' => $this->cost]);
    }


    /**
     *  Verify If Password Matches Hash
     *
     *  @param string $password     Password To Match
     *  @param string $hash         Hash To Match
     */
    public function verify($password, $hash) {
        return password_verify($password, $hash);
    }
}

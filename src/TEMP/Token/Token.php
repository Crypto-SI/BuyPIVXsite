<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Generate Random/Unique Tokens
 *
 */

namespace TEMP\Token;

class Token
{

    public function create($length = 32)
    {
        $token = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
        return trim(substr(preg_replace('/[^A-Za-z0-9\-]/', '', base64_encode($token)), 0, $length));
    }
}

<?php

/**
 *------------------------------------------------------------------------------
 *
 *
 *
 */

namespace TEMP\Http;

class Http
{
    private $uri = '';


    public function __construct(string $uri = '')
    {
        $this->uri = $uri;
    }


    public function redirect(string $uri = '')
    {
        $uri = $uri ? $uri : "/{$this->uri}";

        header("Location: {$uri}");
        exit();
    }
}

<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Handle Flash Alerts
 *
 */

namespace TEMP\Flash;

use TEMP\Session\Session;

class Flash
{

    public function __construct(Session $session)
    {
        $this->session = $session;
    }


    public function error($msg)
    {
        $this->session->add('errors', $msg);
    }

    public function success($msg)
    {
        $this->session->add('success', $msg);
    }

    public function dump()
    {
        $dump = [
            'errors' => $this->session->get('errors'),
            'success' => $this->session->get('success')
        ];

        $this->session->set([
            'errors' => [],
            'success' => []
        ]);

        return $dump;
    }
}

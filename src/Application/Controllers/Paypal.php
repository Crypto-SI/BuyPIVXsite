<?php

namespace App\Controllers;

use App\Domain\Purchases;
use App\Entities\Configuration;
use App\Services\Paypal\Ipn as PaypalIpn;

use TEMP\Input\Input;

class Paypal
{

    private $sandbox = false;

    public function __construct(Configuration $configuration, Input $input, PaypalIpn $paypal, Purchases $purchases)
    {
        $this->configuration = $configuration;
        $this->input = $input;
        $this->paypal = $paypal;
        $this->purchases = $purchases;
    }


    public function ipn()
    {
        $verified = $this->paypal->verifyIPN();

        if ($verified) {
            if ($_POST["payment_status"] != "Completed") {
                return;
            }

            $gross = $_POST['mc_gross'];
            $id = $_POST['item_number'];

            $purchase = $this->purchases->find($id);

            // Gross Payment And DB May Differ By A Few Cents Since Paypal
            // Calculates Fee Based On The Purchase Total. We Add
            // 2.9% + $0.30 To Each Purchase.
            if (!$purchase || $purchase['total'] < ($gross - 1) || $_POST["mc_currency"] != "USD") {
                return;
            }

            $this->purchases->markComplete($id, json_encode($_POST));
        }

        // Reply with an empty 200 response to indicate to paypal the IPN was received correctly.
        header("HTTP/1.1 200 OK");
        return;
    }


    public function handle($id, $amount, $total)
    {
        $host = $this->input->sanitize(ltrim($_SERVER['HTTP_HOST'], 'www.'));

        $data = [
            'cmd' => '_xclick',

            'business' => $this->configuration->get('paypal.email'),
            'return' => stripslashes("https://www.{$host}/payment/success"),
            'cancel_return' => stripslashes("https://www.{$host}/payment/failed"),
            'notify_url' => "https://www.{$host}/payment/paypal",

            'item_name' => str_replace('[amount]', $amount, $this->configuration->get('payment.description')),
            "item_number" => $id,
            "amount" => $total,
            "currency_code" => "USD"
        ];

        // Build the query string from the data.
        $string = http_build_query($data);

        // Redirect To Paypal IPN
        header("location:https://www." . ($this->sandbox ? "sandbox." : "") . "paypal.com/cgi-bin/webscr?{$string}");
        exit();
    }
}

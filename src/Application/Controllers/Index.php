<?php

// TODO Split Into Individual Services On Next Update!
namespace App\Controllers;

use App\Domain\Prices;
use App\Domain\Purchases;
use App\Entities\Configuration;

use TEMP\Flash\Flash;
use TEMP\Http\Http;
use TEMP\Input\Input;
use TEMP\View\View;
use TEMP\Mail\Mail;

use App\Services\Exchanges\Bittrex as Exchange;

use Stripe;

class Index
{

    public function __construct(
        Configuration $config,
        Flash $flash,
        Http $http,
        Input $input,
        Prices $prices,
        View $view,
        Paypal $paypal,
        Purchases $purchases,
        Exchange $exchange,
        Mail $mail
    ) {
        $this->config = $config;
        $this->flash = $flash;
        $this->http = $http;
        $this->input = $input;
        $this->prices = $prices;
        $this->view = $view;

        // TODO: Should Be In Separate Service
        $this->paypal = $paypal;
        $this->purchases = $purchases;


        $this->check($exchange, $prices);
        $this->emails($mail);
    }


    public function index()
    {
        $fee = $this->config->get('fee');
        $price = $this->prices->getLatest()['price'];

        if ($_POST) {
            $this->post($fee, $price);
        }

        return $this->view->display('index', [
            'description' => $this->config->get('payment.description'),
            'fee' => $fee,
            'price' => $price,

            'stripeKey' => $this->config->get('stripe.apikey.public')
        ]);
    }

    // Hostgator Cron Jobs Suck So Unfortunately This Is The TEMP Fix
    private function emails($mail) {
        $purchases = $this->purchases->emailData();

        if (!$purchases) {
            return;
        }

        $message = "";

        foreach ($purchases as $purchase) {
            $message .= "{$purchase['crypto']} {$this->config->get('ticker')} - Address: {$purchase['address']} <br>";
        }

        $successful = $mail->send([$this->config->get('email') => 'admin'], 'Latest Orders', $message);

        if ($successful) {
            $this->purchases->emailSent($purchases);
        }
    }

    private function check($exchange, $prices) {
        if (!$prices->needsUpdate()) {
            return;
        }

        $data = $exchange->fetchPrice();

        $base = $data['base'] ?? null;
        $crypto = $data['crypto'] ?? null;
        $price = $data['price'] ?? null;

        if (!$base || !$crypto || !$price) {
           return;
        }

        $prices->save($base, $crypto, $price);
    }


    // TODO: Separate Into Form Handler/Service When Upgrading
    private function post($fee, $price)
    {
        $data = [
            'crypto' => $this->input->get('crypto'),
            'address' => $this->input->get('address'),

            // Payment Provider/Choice
            'payment' => $this->input->get('payment'),

            // Stripe Data
            'stripeamount' => $this->input->get('stripeamount'),
            'stripeemail' => $this->input->get('stripeemail'),
            'stripetoken' => $this->input->get('stripetoken')
        ];

        foreach ($data as $key => $value) {
            if ($data['payment'] === 'paypal' && in_array($key, ['stripeamount', 'stripeemail', 'stripetoken'])) {
                continue;
            }

            if (!$value) {
                $this->flash->error('All Fields Must Be Filled Out To Continue');
                return;
            }
        }

        if ((float) $price < 5) {
            $this->flash->error('All Purchases Must Be More Than $5');
            return;
        }

        if (!in_array($data['payment'], ['paypal', 'stripe'])) {
            $this->flash->error('Invalid Payment Provider Selected');
            return;
        }

        $this->processDetails($data, $fee, $price);
    }

    private function processDetails($data, $fee, $price)
    {
        // Calculate Total Purchase And Send To Checkout
        $data['crypto'] = round((float) $data['crypto'], 2);

        $fee = $price * $fee;
        $total = ($fee + $price) * $data['crypto'];

        // Add/Calculate Paypal/Stripe Fee
        $total = (($total * 0.029) + 0.3) + $total;
        $total = round((float) $total, 2);

        $id = $this->purchases->create($data['crypto'], $price, $fee, $total, $data['address'], $data['payment']);

        // Send To Paypal/Stripe
        if ($data['payment'] === 'paypal') {
            $this->paypal->handle($id, $data['crypto'], $total);
        }
        // Stripe
        else {
            Stripe\Stripe::setApiKey($this->config->get('stripe.apikey.private'));

            $charge = Stripe\Charge::create([
                'amount' => $data['stripeamount'],
                'currency' => 'usd',
                'description' => str_replace('[amount]', round((float) $data['stripeamount'] / 100, 2), $this->config->get('payment.description')),
                'source' => $data['stripetoken']
            ]);

            if ($charge->status === 'succeeded') {
                $this->purchases->markComplete($id, json_encode($charge));
                $this->http->redirect('/payment/success');
            }
            else {
                $this->http->redirect('/payment/failed');
            }
        }
    }
}

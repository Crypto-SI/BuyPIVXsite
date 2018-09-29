<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Simple Mail Helper
 *
 */
require 'phpmailer.php';

class Mail {

    public function __construct() {
        $this->phpmailer = new PHPMailer;
    }


    /**
     *  Send Email ( Currently Only Using PHPMailer )
     *
     *  @param  string $recipient   Recipient Email Address
     *  @param  string $subject     Email Subject Line
     *  @param  string $message     Email Message
     *  @return bool
     */
    public function send($recipient, $subject, $message) {

        // Email From, And Name
        $this->phpmailer->From       = 'noreply@cryptofiatgateway.com';
        $this->phpmailer->FromName   = 'Crypto Fiat Gateway Mailer';

        // Key = Email, Value = Name
        foreach ($recipient as $key => $value) {
            $this->phpmailer->addAddress($key, $value);
        }

        // Define Subject, And Body
        $this->phpmailer->Subject    = $subject;
        $this->phpmailer->Body       = $message;
        $this->phpmailer->AltBody    = $message;

        // Send Email, If Error Is Found Insert Site Log, And Return False
        if(!$this->phpmailer->send()) {
            return false;
        }
        $this->phpmailer->clearAddresses();

        return true;
    }
}

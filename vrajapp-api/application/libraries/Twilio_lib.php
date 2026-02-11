<?php


class Twilio_lib {

    protected $sid;
    protected $token;
    protected $from;

    public function __construct()
    {
        $this->sid   = 'AC7dda41efd0d768dddc379e95170769c7';
        $this->token = 'c7710227800bfb555e3b1c0221e1bf2d';
        $this->from  = '+1 (201) 270-8377'; // e.g. +1415xxxxxxx
    }


    public function sendSMS($to, $message)
    {
        try {
            $client = new \Twilio\Rest\Client($this->sid, $this->token);

            $sms = $client->messages->create(
                $to,
                [
                    'from' => $this->from,
                    'body' => $message
                ]
            );

            return $sms->sid; // success
        } catch (Exception $e) {
            return $e->getMessage(); // error
        }
    }
}

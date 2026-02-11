<?php


class Twilio_lib {

    protected $sid;
    protected $token;
    protected $from;

    public function __construct()
    {
        
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

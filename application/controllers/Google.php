<?php
defined('BASEPATH') or exit('No direct script access allowed');
error_reporting(0);
class Google extends CI_Controller
{
    public function index()
    {
        require_once 'vendor/autoload.php';
        $client = new Google_Client();

        $client->addScope('email');
        $client->addScope('profile');
        $client->setClientId(GOOGLE_CLIENT_ID);
        $client->setClientSecret(GOOGLE_CLIENT_SECRET);
        $redirect_uri = BASE_URL . 'Google/login';
        $client->setRedirectUri($redirect_uri);
        // $client->setAuthConfig(__DIR__ . '/client_credentials.json');

        $guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false, ), ));

        $client->setHttpClient($guzzleClient);

        // Your redirect URI can be any registered URI, but in this example
        // we redirect back to this same page
        // $redirect_uri = 'https://dev.thcitsolutions.com/vrajfresh_examples/Google/login';


        header("location:" . $client->createAuthUrl());
    }
    public function login()
    {
        //In dev it is work as expected Here is some issue.May be issue is either redirect_url or https on 1st june
        require_once 'vendor/autoload.php';
        $client = new Google\Client();
        $objOAuthService = new Google_Service_Oauth2($client);

        $client->setClientId('705571454677-3su3mmo7crjv12o2bers84rnu6ou7opn.apps.googleusercontent.com');
        $client->setClientSecret('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');
        $redirect_uri = BASE_URL . 'Google/login';
        $client->setRedirectUri($redirect_uri);

        // $client->setAuthConfig(__DIR__.'/client_credentials.json');
        $guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false, ), ));
        $client->setHttpClient($guzzleClient);

        if (isset($_GET['code'])) {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

            $userData = $objOAuthService->userinfo->get();
            // echo "Token: ".$token['access_token']."<br/>";
            // echo  "Name: ".$userData['name']."<br/>";
            // echo  "Email: ".$userData['email'];

            $url = API_URL . "Google/login";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'access_token=' . $token["access_token"] . '&email=' . $userData["email"] . '&first_name=' . $userData["givenName"] . '&last_name=' . $userData["familyName"] . '&display_name=' . $userData['name']);
            $result = curl_exec($ch);
            //var_dump($result)."<br/>";
            $final_result = json_decode($result);
            //echo "<pre>";print_r($final_result);exit;
            if ($final_result->is_successful) {
                session_start();
                $session_data = array(
                    'user_id' => $final_result->data[0]->user_id,
                    'email' => $final_result->data[0]->email,
                    'user_name' => $final_result->data[0]->user_name,
                    'first_name' => $final_result->data[0]->first_name,
                    'last_name' => $final_result->data[0]->last_name,
                    'display_name' => $final_result->data[0]->display_name,
                    'user_role_id' => $final_result->data[0]->user_role_id,
                    'Is_login' => true
                );
                $_SESSION['logged_in'] = $session_data;


                return redirect(BASE_URL . 'my-address');

            } else {
                return redirect(BASE_URL);
            }

        }
    }
}
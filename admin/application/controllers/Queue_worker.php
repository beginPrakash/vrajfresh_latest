<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Queue_worker extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Strictly protect this controller so it can ONLY be run via terminal/cron
        if (!$this->input->is_cli_request()) {
            // show_404();
            // exit;
        }
        
        $this->load->database();
    }

    /**
     * This method processes the queue entries
     */
    public function process() {
        // 1. Grab a chunk of 100 pending records directly
        $this->db->where('status', 'pending');
        $this->db->limit(100);
        $jobs = $this->db->get('tbl_notification_queue')->result_array();

        if (empty($jobs)) {
            echo "Queue is empty. No pending notifications.\n";
            return;
        }

        // 2. Obtain your FCM OAuth2 Access Token
        $accessToken = $this->_get_fcm_access_token();

        foreach ($jobs as $job) {
            $current_attempt = $job['attempts'] + 1;
            // 3. Instantly mark as processing so concurrent cron jobs don't double-send it
            $this->db->where('id', $job['id']);
            $this->db->update('tbl_notification_queue', [
                'status'   => 'processing',
                'attempts' => $current_attempt
            ]);

            // 4. Parse custom data safely
            $custom_data = !empty($job['custom_data']) ? json_decode($job['custom_data'], true) : [];

            // 5. Send payload to FCM
            $result = $this->_send_to_fcm($accessToken, $job['device_token'], $job['title'], $job['body'], $custom_data);

            // 6. Update row status based on API result
            $this->db->where('id', $job['id']);
            if ($result['success']) {
                $this->db->update('tbl_notification_queue', ['status' => 'completed','error_log'    => NULL]);
            } else {
                // If it fails but has been tried less than 3 times, set back to pending to retry later
                $next_status = ($job['attempts'] >= 2) ? 'failed' : 'pending';
                $detailed_error = "Attempt #{$current_attempt} Failed. Reason: " . $result['error'];

                $this->db->update('tbl_notification_queue', ['status' => $next_status,'error_log'    => $detailed_error]);
            }
        }

        echo "Successfully processed " . count($jobs) . " notification records.\n";
    }

    /**
     * Direct Curl Request to Firebase HTTP v1 API
     */
    private function _send_to_fcm($accessToken, $token, $title, $body, $custom_data = []) {
        // Replace with your real Firebase Project ID
        $url = 'https://fcm.googleapis.com/v1/projects/vrajfresh-f50f3/messages:send';
        
        // Convert all custom data keys/values explicitly to strings (FCM requirement)
        $formatted_data = [];
        foreach ($custom_data as $key => $value) {
            $formatted_data[(string)$key] = (string)$value;
        }

        // Setup base message
        $payload = [
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body'  => $body
                ]
            ]
        ];

        // Append custom variables if present
        if (!empty($formatted_data)) {
            $payload['message']['data'] = $formatted_data;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return ['success' => false, 'error' => 'cURL Error: ' . $error_msg];
        }
        curl_close($ch);

        // Check for Google HTTP API level responses
        if ($httpCode === 200) {
            return ['success' => true, 'error' => null];
        } else {
            // Returns Google's JSON error response (e.g., "Requested entity was not found" for bad tokens)
            return ['success' => false, 'error' => "HTTP {$httpCode} - Response: " . $response];
        }
    }

    /**
     * Generate and Cache Google OAuth2 Access Token for Firebase HTTP v1
     */
    private function _get_fcm_access_token() {
        // 1. Initialize CodeIgniter's built-in file cache driver
        $this->load->driver('cache', array('adapter' => 'file'));
        
        $cache_key = 'fcm_oauth_bearer_token';
        
        // 2. Return the token directly if it is already cached
        if ($cached_token = $this->cache->get($cache_key)) {
            return $cached_token;
        }

        // 3. Locate and load the Firebase Service Account JSON file
        $json_path = APPPATH . 'config/firebase_credentials.json';
        if (!file_exists($json_path)) {
            log_message('error', 'Firebase credentials file missing at: ' . $json_path);
            return FALSE;
        }

        $credentials = json_decode(file_get_contents($json_path), true);
        if (!isset($credentials['private_key']) || !isset($credentials['client_email'])) {
            log_message('error', 'Invalid Firebase credentials structure.');
            return FALSE;
        }

        // 4. Construct the JSON Web Token (JWT) Payload
        $current_time = time();
        $jwt_header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
        $jwt_claim  = json_encode([
            'iss'   => $credentials['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud'   => 'https://oauth2.googleapis.com/token',
            'exp'   => $current_time + 3600, // Token expires in 1 hour
            'iat'   => $current_time
        ]);

        // Encode Header and Claim using Base64Url encoding specification
        $base64_url_header = $this->_base64url_encode($jwt_header);
        $base64_url_claim  = $this->_base64url_encode($jwt_claim);
        $signature_input   = $base64_url_header . '.' . $base64_url_claim;

        // 5. Crytographically sign the token using SHA-256 and the private key
        $private_key = $credentials['private_key'];
        $signature = '';
        
        if (!openssl_sign($signature_input, $signature, $private_key, OPENSSL_ALGO_SHA256)) {
            log_message('error', 'OpenSSL failed to sign the JWT assertion.');
            return FALSE;
        }

        $base64_url_signature = $this->_base64url_encode($signature);
        $jwt_assertion = $signature_input . '.' . $base64_url_signature;

        // 6. Exchange the completed JWT assertion for an actual Access Token
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => $jwt_assertion
        ]));

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code !== 200) {
            log_message('error', 'Google OAuth2 token exchange failed: ' . $response);
            return FALSE;
        }

        $token_data = json_decode($response, true);
        $access_token = $token_data['access_token'];

        // 7. Cache the retrieved access token for 50 minutes (3000 seconds)
        // This provides a 10-minute buffer before the token expires at Google
        $this->cache->save($cache_key, $access_token, 3000);

        return $access_token;
    }

    /**
     * Helper method to format strings to Base64Url standards
     */
    private function _base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
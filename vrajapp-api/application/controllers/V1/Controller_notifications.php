<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'third_party/twilio_loader.php';

class Controller_notifications extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		header("Access-Control-Allow-Headers: content-type,Content-Type,X-Custom-Header, Upgrade-Insecure-Requests,Accept,x-requested-with");
		header('Content-Type: application/json');
		header('Access-Control-Allow-Credentials: true');
		header('Access-Control-Max-Age: 60');
		header('Access-Control-Allow-Headers: AccountKey,x-requested-with, Content-Type, content-type, origin, authorization, accept, client-security-token, host, date, cookie, cookie2');
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
		$this->load->model('notification_model');

		error_reporting(0);
	}
	public function get_notifications(){

        $limit = $this->input->get('limit')??10;
        $page_no  = $this->input->get('page_no')??1;

		// Get Bearer Token
		$authHeader = $this->input->get_request_header('Authorization', TRUE);
		if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
			$oauth_key = $matches[1];
		} else {
			$oauth_key = '';
		}

		$errors = $success_message = '';

		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			try {
                $this->db->where('access_token', $oauth_key);
                $query = $this->db->get('tbl_users_token');
                $user_token_data = $query->row_array();
                $user_id = $user_token_data['user_id'];

				$data = array(
					// 'search_keyword' => $json_obj->search_keyword,
					'user_id' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $user_id))),
					'limit' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $limit))),
					'page_no' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $page_no))),
				);

                $db_result = $this->notification_model->get_notifications($data);
                $result = $db_result['records'];
                $total_records = $db_result['total_records'];
                $total_pages = ceil($total_records / $limit);

				if (count($result) > 0) {
					$map_result = array_map(function($row) {
                        $rowArray = (array) $row; 
                        if (!empty($rowArray['custom_data'])) {
                            $customDataDecoded = json_decode($rowArray['custom_data'], true);
                            
                            if (is_array($customDataDecoded)) {
                                $rowArray = array_merge($rowArray, $customDataDecoded);
                            }
                        }
                        // Remove the original custom_data key
                        unset($rowArray['total_records']);
                        unset($rowArray['custom_data']);
                        return $rowArray;
                    }, $result);

					$ArrData = array(
                        'total_record' => $total_records,
                        'total_page'   => $total_pages,
                        'current_page' => $page_no,
                        'data'=> $map_result
                    );

                    $success_message = 'Data retrieved successfully!';
				} else {
					$errors = 'No Data Available';
				}
			} catch (Exception $e) {
				$ArrData = "There is problem";
			}
            send_response_to_api($ArrData, $errors, $success_message);
		}
    }

    public function mark_notification_read() {
        $json_str = file_get_contents('php://input');
        $json_obj = json_decode($json_str);

        // Get Bearer Token
        $authHeader = $this->input->get_request_header('Authorization', TRUE);
        if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $oauth_key = $matches[1];
        } else {
            $oauth_key = '';
        }

        $errors = $success_message = '';
        $ArrData = array();

        if (check_oauth_key($oauth_key)) {
            try {
                // Get user_id from token
                $this->db->where('access_token', $oauth_key);
                $query = $this->db->get('tbl_users_token');
                $user_token_data = $query->row_array();
                $user_id = $user_token_data['user_id'];

                // Sanitize the notification ID from payload
                $notification_id = isset($json_obj->notification_id) ? trim(htmlspecialchars(preg_replace('/[^0-9]/', '', $json_obj->notification_id))) : '';

                if (!empty($notification_id)) {
                    // Call model to update status
                    $is_marked = $this->notification_model->mark_as_read($notification_id, $user_id);

                    if ($is_marked) {
                        $record = $this->notification_model->get_notification_by_id($notification_id);

                        if ($record) {
                            // Flatten the custom_data column
                            if (!empty($record['custom_data'])) {
                                $customDataDecoded = json_decode($record['custom_data'], true);
                                if (is_array($customDataDecoded)) {
                                    $record = array_merge($record, $customDataDecoded);
                                }
                            }
                            unset($record['custom_data']);

                            $ArrData = $record;
                            $success_message = 'Notification marked as read successfully.';
                        }
                    } else {
                        $errors = 'Notification not found or already marked as read.';
                    }
                } else {
                    $errors = 'Notification ID is required.';
                }
            } catch (Exception $e) {
                $errors = "There is a problem updating the notification.";
            }
            send_response_to_api($ArrData, $errors, $success_message);
        }
    }
}
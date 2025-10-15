<?php
defined('BASEPATH') or exit('No direct script access allowed');
error_reporting(0);
class Google extends CI_Controller
{
    public function login()
    {
        
        $json_str = json_encode($_POST);
        $json_obj = json_decode($json_str);
       
                $this->load->model('Users_model','users_model');
                $result= $this->users_model->email_exist($json_obj->email);
                $ArrData=array();
                $errors=$success_message = '';
                if(count($result) > 0)
                {
                    $data = array(
                        'google_token'=>$json_obj->access_token,
                        'is_active' => '1',
                        'is_deleted'=>'0',
                        'modified_datetime'=>date('Y-m-d h:i:s')
                    );
                    $response=$this->users_model->update_user_by_email($data,$json_obj->email,'tbl_users');
                    
                    if($response)
                    {
                        $ArrData[] = $result[0];
                        
                        $success_message = '';
                    }
                    else
                    {
                        $errors = 'No Data Available';
                    }
                }
                else{
                    $data = array(
                        'google_token'=>$json_obj->access_token,
                        'email'=>$json_obj->email,
                        'user_name'=>$json_obj->email,
                        'first_name'=>$json_obj->first_name,
                        'last_name'=>$json_obj->last_name,
                        'display_name'=>$json_obj->display_name,
                        'is_active' => '1',
                        'is_deleted'=>'0',
                        'created_datetime'=>date('Y-m-d h:i:s')
                    );
                    $response=$this->users_model->add_user($data,'tbl_users');
                    if($response)
                    {
                        $ArrData[] = $result;
                        $success_message = '';
                    }
                    else
                    {
                        $errors = 'No Data Available';
                    }
                }
                send_response_to_api($ArrData, $errors, $success_message);
                // echo "Token: ".$token['access_token']."<br/>";
                // echo  "Name: ".$userData['name']."<br/>";
                // echo  "Email: ".$userData['email'];



        
    }
}
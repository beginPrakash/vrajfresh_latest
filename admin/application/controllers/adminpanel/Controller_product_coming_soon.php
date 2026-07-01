<?php

class Controller_product_coming_soon extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_commingsoon_model');
        $this->load->model('common_model');

        if (!IsUserLogin()) {
            $authorized_error = "You are not authorized to view this page....!";
            $this->session->set_flashdata('authorized_error', $authorized_error);
            redirect('login');
        }
    }

    public function index()
    {
        $ArrData = $this->product_commingsoon_model->getProductListData($_POST);

        if (@$_REQUEST['columns'] != "") {
            $output = array('sEcho' => $_REQUEST['draw'], 'iTotalRecords' => $ArrData['iTotalRecords'], 'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'], 'aaData' => array());

            $i = $_REQUEST['start'] + 1;
            foreach ($ArrData['result'] as $aRow) {
                $row = $category_name = array();
                $base_url = base_url();
                $comingsoon_id = $aRow['comingsoon_id'];
                $actions = '';
                $actions .= '<input type="checkbox" name="delete[]" class="chkDelete" value="' . $comingsoon_id . '" /><div class="btn-group">';
                $actions .= '<a href="' . $base_url . 'product/coming-soon/update/' . $comingsoon_id . '" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>';
                $actions .= '<a rel="' . $base_url . 'product/coming-soon/delete/' . $comingsoon_id . '" class="deleteRecord btn btn-default btn-sm"><i class="fa fa-trash-o"></i></a></div>';

                $row[] = $actions;
                $row[] = $aRow['product_name'];
                //$row[] = implode(',',$category_name);
                $row[] = $aRow['product_details'];
                $row[] = date('m-d-Y H:i:s', strtotime($aRow['created_datetime']));

                $i++;
                $output['aaData'][] = $row;
            }
            echo json_encode($output);
            exit;
        }
        /* DATA TABLE END */
        $ArrPageData = array();
        $ArrPageData['cms_title'] = 'Coming Soon Product';
        $ArrPageData['button_url'] = base_url() . 'product/coming-soon/add';
        $ArrPageData['button_label'] = 'Add Coming Soon Product';
        $ArrPageData['view_name'] = 'view_product_coming_soon_list.php';
        $this->load->view('admin_panel/admin_panel', $ArrPageData);
    }

    public function delete($id)
	{
		$result = $this->product_commingsoon_model->soft_delete_product($id);
		if ($result) {
			echo 'Yes';
		} else {
			echo 'No';
		}

	}

    public function delete_multiple_product_record()
	{
		$id = $_POST['primary_id'];
		$primary_idArr = explode(',', $id);
		$result = 0;
		foreach ($primary_idArr as $primary_id) {
			$result += $this->product_commingsoon_model->soft_delete_product($primary_id);
		}
		if ($result > 0) {
			echo 1;
		} else {
			echo 0;
		}

	}

    public function add($comingsoon_id = 0)
	{	
		
		$ArrData = array();
		$ArrData['ArrProducts'] = $this->product_commingsoon_model->product_list_data();
		if ($comingsoon_id > 0) {
            
            //GET PRODUCT DETAILS
			$ArrData['product'] = $this->product_commingsoon_model->getProductUsingID($comingsoon_id);
			$ArrData['cms_title'] = "Update Coming Soon Product";
			$ArrData['edit_id'] = $comingsoon_id;
		} else {
			$ArrData['cms_title'] = "Add Coming Soon Product";
			$ArrData['edit_id'] = '';
		}

		$ArrData['button_url'] = base_url() . 'product/coming-soon';
		$ArrData['button_label'] = 'View Coming Soon Product';
		$ArrData['view_name'] = 'view_product_coming_soon_addedit.php';

		$css_assets = array(
			array(ADMIN_PANEL_THEME_PATH . 'dist/css/jquery.multiselect.css'),
		);
		$js_assets = array(
            array(ADMIN_PANEL_THEME_PATH . 'plugins/ckeditor/ckeditor.js'),
			array(ADMIN_PANEL_THEME_PATH . 'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'),
		);
		$this->carabiner->css($css_assets);
		$this->carabiner->js($js_assets);
		if (isset($_POST['submit']) && ($_POST['submit'] == "Add" || $_POST['submit'] == "Update")) {
			
			/* validation Process Start */
			$this->load->library('form_validation');
			$this->form_validation->set_rules('product_name', 'Product Name', 'required');
			$this->form_validation->set_rules('product_details', 'Product Details', 'required');

			/* validation Process End */
			if ($this->form_validation->run()) {
				if ($comingsoon_id > 0) { //update process
					
					$product_associate_to_id = '';
                    $product_update_data = array(
                        'product_name' => $this->input->post('product_name'),
                        'product_details' => $this->input->post('product_details'),
                        'modified_datetime' => date('Y-m-d H:i:s'),
                        'modified_by' => get_current_admin_id(),
                    );

                    //echo "<pre>";print_r($product_update_data);exit;
                    $update = $this->product_commingsoon_model->update($comingsoon_id, $product_update_data);
                    if($update){
                        $this->session->set_flashdata('success_message', 'Product details has been updated successfully.');
                    } else {
                        $this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');
                    }


					redirect('product/coming-soon/');

				} else { // insert

                    $product_name = $this->input->post('product_name') ?? '';
                    $product_details = $this->input->post('product_details') ?? '';

					$product_data = array(
						'product_name' => $product_name,
						'product_details' => $product_details,
                        'modified_datetime' => date('Y-m-d H:i:s'),
                        'modified_by' => get_current_admin_id(),
						'created_datetime' => date('Y-m-d H:i:s'),
						'created_by' => get_current_admin_id()
					);

					$comingsoon_id = $this->product_commingsoon_model->add($product_data);
					if ($comingsoon_id > 0) {
                        //-------------------Start Delivery Status Push Notifications -------------------
                        $this->notification_coming_soon_product($product_name, $product_details);
                        //-------------------End Delivery Status Push Notifications -------------------
						$this->session->set_flashdata('success_message', 'Product details has been added successfully.');
					} else {
						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');
					}
					redirect('product/coming-soon/');
				}
			} else {
				$this->load->view('admin_panel/admin_panel', $ArrData);
			}
		} else {
			$this->load->view('admin_panel/admin_panel', $ArrData);
		}
	}

    private function notification_coming_soon_product($product_name, $product_details){
        
		$aColumns = array('tbl_users.user_id', 'tbl_users.first_name','tbl_users.last_name','tbl_users.display_name', 'tbl_users.user_name','tbl_users.email', 'tbl_users.mobile_no', 'tbl_users.is_active');
		$this->db->select('SQL_CALC_FOUND_ROWS ' . str_replace(' , ', ' ', implode(', ', $aColumns)), false);
		$this->db->where('user_role_id', 4);
		$this->db->where('is_active', 1);
		$this->db->where('is_deleted', 0);
        $this->db->order_by('tbl_users.user_id', 'desc');
        $query = $this->db->get('tbl_users');
        $result = $query->result_array();
        // echo count($result);
        // die(' count');

        if (!empty($result) && is_array($result)) {

            $title = "{$product_name} are Coming Soon!";
            $body  = substr($product_details, 0, 100);

            // Prepare shared custom data parameters
            $extra_data = [
                'type'     => 'COMING_SOON_PRODUCT',
            ];
            $json_custom_data = json_encode($extra_data);

            foreach ($result as $user) {
                $user_id = $user['user_id'];

                // ALWAYS log history in the master notification table
                $insert_notification_data = [
                    'user_id'     => $user_id,
                    'title'       => $title,
                    'body'        => $body,
                    'custom_data' => $json_custom_data,
                    'read_status'      => 0 // 0 = Unread app notification inbox item
                ];
                $this->db->insert('tbl_notification', $insert_notification_data);

                // Check if the user has any active device tokens for Push Notifications
                $this->db->where('user_id', $user_id);
                $query = $this->db->get('tbl_user_fcm_tokens');

                if ($query->num_rows() > 0) {
                    $fcm_tokens = $query->result_array();
                    $batch_queue_data = [];

                    // Loop through all found devices and prepare background queue payloads
                    foreach ($fcm_tokens as $row) {
                        if (!empty($row['fcm_token'])) {
                            $batch_queue_data[] = [
                                'user_id'      => $user_id,
                                'device_token' => $row['fcm_token'],
                                'title'        => $title,
                                'body'         => $body,
                                'custom_data'  => $json_custom_data,
                                'status'       => 'pending'
                            ];
                        }
                    }

                    // Fire a single batch insert to queue up all notifications simultaneously 
                    if (!empty($batch_queue_data)) {
                        $this->db->insert_batch('tbl_notification_queue', $batch_queue_data);
                    }
                }
            }

        }
        
    }
}

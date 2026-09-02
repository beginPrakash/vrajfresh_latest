<?php

class Controller_zone extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('zone_model');
        $this->load->model('common_model');

        if (!IsUserLogin()) {
            $authorized_error = "You are not authorized to view this page....!";
            $this->session->set_flashdata('authorized_error', $authorized_error);
            redirect('login');
        }
    }

    public function index()
    {
        $ArrData = $this->zone_model->getProductListData($_POST);

        if (@$_REQUEST['columns'] != "") {
            $output = array('sEcho' => $_REQUEST['draw'], 'iTotalRecords' => $ArrData['iTotalRecords'], 'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'], 'aaData' => array());

            $i = $_REQUEST['start'] + 1;
            foreach ($ArrData['result'] as $aRow) {
                $row = $category_name = array();
                $base_url = base_url();
                $zone_id = $aRow['zone_id'];
                $actions = '';
                $actions .= '<input type="checkbox" name="delete[]" class="chkDelete" value="' . $zone_id . '" /><div class="btn-group">';
                $actions .= '<a href="' . $base_url . 'zone/update/' . $zone_id . '" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>';
                $actions .= '<a rel="' . $base_url . 'zone/delete/' . $zone_id . '" class="deleteRecord btn btn-default btn-sm"><i class="fa fa-trash-o"></i></a></div>';

                $row[] = $actions;
                $row[] = $aRow['title'];
                //$row[] = implode(',',$category_name);
                $row[] = (!empty($aRow['holiday_start_date']) && !empty($aRow['holiday_end_date']))
				? date('m-d-Y', strtotime($aRow['holiday_start_date'])) . ' - ' . date('m-d-Y', strtotime($aRow['holiday_end_date']))
				: '';

                $i++;
                $output['aaData'][] = $row;
            }
            echo json_encode($output);
            exit;
        }
        /* DATA TABLE END */
        $ArrPageData = array();
        $ArrPageData['cms_title'] = 'Zone';
        $ArrPageData['button_url'] = base_url() . 'zone/add';
        $ArrPageData['button_label'] = 'Add Zone';
        $ArrPageData['view_name'] = 'view_zone_list.php';
        $this->load->view('admin_panel/admin_panel', $ArrPageData);
    }

    public function delete($id)
	{
		//update old zone is null
		$data_del = array(
			'zone_id' => NULL
		);

		$this->db->where('zone_id', $id);
		$this->db->update('tbl_zipcodes', $data_del);

		$result = $this->zone_model->soft_delete_product($id);
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
			//update old zone is null
			$data_del = array(
				'zone_id' => NULL
			);

			$this->db->where('zone_id', $primary_id);
			$this->db->update('tbl_zipcodes', $data_del);

			$result += $this->zone_model->soft_delete_product($primary_id);
		}
		if ($result > 0) {
			echo 1;
		} else {
			echo 0;
		}

	}

    public function add($zone_id = 0)
	{	
		
		$ArrData = array();

		if ($zone_id > 0) {
            
            //GET Zone
			$ArrData['product'] = $this->zone_model->getProductUsingID($zone_id);
			$ArrData['cms_title'] = "Update Zone";
			$ArrData['edit_id'] = $zone_id;
		} else {
			$ArrData['cms_title'] = "Add Zone";
			$ArrData['edit_id'] = '';
		}
		$ArrData['zipcodelist'] = $this->zone_model->getZipcodelist();
		$ArrData['button_url'] = base_url() . 'zone';
		$ArrData['button_label'] = 'View Zone';
		$ArrData['view_name'] = 'view_zone_add_edit.php';

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
			$this->form_validation->set_rules('title', 'Title', 'required');

			/* validation Process End */
			if ($this->form_validation->run()) {
				$zipcode_ids = !empty($_POST['zipcode_ids'])
				? $_POST['zipcode_ids']
				: [];

				$zipcode_ids = array_map('intval', $zipcode_ids);

				$zipcode_ids_string = implode(',', $zipcode_ids);
				if ($zone_id > 0) { //update process
					$holiday_date = $this->input->post('holiday_date');

					if (!empty($holiday_date)) {

						$dates = explode(' - ', $holiday_date);

						$start_date = !empty($dates[0])
						? DateTime::createFromFormat('m-d-Y', trim($dates[0]))->format('Y-m-d')
						: null;
											$end_date = !empty($dates[1])
						? DateTime::createFromFormat('m-d-Y', trim($dates[1]))->format('Y-m-d')
						: null;
	
					}
					$product_associate_to_id = '';
                    $product_update_data = array(
                        'title' => $this->input->post('title'),
                        'holiday_start_date' => $start_date,
						'holiday_end_date' => $end_date,
						'cutoff_time' => $this->input->post('cutoff_time'),
						'zipcode_ids' => $zipcode_ids_string,
                        'modified_datetime' => date('Y-m-d H:i:s'),
                        'modified_by' => get_current_admin_id(),
                    );

					if(count($zipcode_ids) > 0){
						//update old zone is null
						$data_del = array(
							'zone_id' => NULL
						);

						$this->db->where('zone_id', $zone_id);
						$this->db->update('tbl_zipcodes', $data_del);
						$data = array(
							'zone_id' => $zone_id,
							'cutoff_time' => $this->input->post('cutoff_time')
						);

						$this->db->where_in('zipcode_id', $zipcode_ids);
						$this->db->update('tbl_zipcodes', $data);
					}

                    //echo "<pre>";print_r($product_update_data);exit;
                    $update = $this->zone_model->update($zone_id, $product_update_data);
                    if($update){
                        $this->session->set_flashdata('success_message', 'Zone has been updated successfully.');
                    } else {
                        $this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');
                    }


					redirect('zone/');

				} else { // insert

                    $title = $this->input->post('title') ?? '';
                    $holiday_date = $this->input->post('holiday_date');

					if (!empty($holiday_date)) {

						$dates = explode(' - ', $holiday_date);

						$start_date = !empty($dates[0])
							? date('Y-m-d', strtotime($dates[0]))
							: null;

						$end_date = !empty($dates[1])
							? date('Y-m-d', strtotime($dates[1]))
							: null;
					}

					$product_data = array(
						'title' => $title,
                        'holiday_start_date' => $start_date,
						'holiday_end_date' => $end_date,
						'cutoff_time' => $this->input->post('cutoff_time'),
						'zipcode_ids' => $zipcode_ids_string,
                        'modified_datetime' => date('Y-m-d H:i:s'),
                        'modified_by' => get_current_admin_id(),
						'created_datetime' => date('Y-m-d H:i:s'),
						'created_by' => get_current_admin_id()
					);

					$zone_id = $this->zone_model->add($product_data);
					if(count($zipcode_ids) > 0){
						
						$data = array(
							'zone_id' => $zone_id,
							'cutoff_time' => $this->input->post('cutoff_time')
						);

						$this->db->where_in('zipcode_id', $zipcode_ids);
						$this->db->update('tbl_zipcodes', $data);
					}
					if ($zone_id > 0) {
						$this->session->set_flashdata('success_message', 'Zone has been added successfully.');
					} else {
						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');
					}
					redirect('zone/');
				}
			} else {
				$this->load->view('admin_panel/admin_panel', $ArrData);
			}
		} else {
			$this->load->view('admin_panel/admin_panel', $ArrData);
		}
	}

	public function check_zipcode_used()
	{
		$zipcode_id = $this->input->post('zipcode_id');
		$zone_id    = $this->input->post('zone_id');

		$this->db->where('zone_id !=', $zone_id);
		$this->db->where('is_deleted', 0);
		$this->db->where("FIND_IN_SET(" . (int)$zipcode_id . ", zipcode_ids) >", 0);

		$query = $this->db->get('tbl_zones');

		if ($query->num_rows() > 0) {

			echo json_encode(array(
				'status' => false,
				'message' => 'This zipcode is already used in another zone.'
			));

		} else {

			echo json_encode(array(
				'status' => true,
				'message' => ''
			));

		}
	}

}

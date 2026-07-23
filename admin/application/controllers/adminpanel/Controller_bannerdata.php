<?php



class Controller_bannerdata extends CI_Controller

{



	public function __construct()

	{

		parent::__construct();

		$this->load->model('appbannerdata_model');

		$this->load->model('special_products_model');

		$this->load->model('product_model');

		$this->load->model('common_model');



		if (!IsUserLogin()) {

			$authorized_error = "You are not authorized to view this page....!";

			$this->session->set_flashdata('authorized_error', $authorized_error);

			redirect('login');

		}



	}



	public function getabannerdata()

	{

		$ArrData = array();

		$ArrData['ArrBannerData'] = $this->appbannerdata_model->getbannerData();

		$ArrData['getbannerDataSingle'] = $this->appbannerdata_model->getbannerDataSingle();


		$ArrData['cms_title'] = 'Application Banner Details';

		$ArrData['view_name'] = 'view_app_bannerdata.php';

		$this->load->view('admin_panel/admin_panel', $ArrData);

	}

	public function save_bannerdata()

	{

		$bannerArr = $this->input->post('bannerArr');

$count = count($bannerArr['category_name']);

$bannerArr = $this->input->post('bannerArr');

$config['upload_path']   = 'uploads/bannerdata/';
$config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
//$config['encrypt_name']  = TRUE;

$this->load->library('upload');

//save single banner image
if (isset($_FILES['banner_image']) && is_uploaded_file($_FILES['banner_image']['tmp_name'])) {

						$config['upload_path'] = 'uploads/bannerdata/'; // set the filter image types

						$config['allowed_types'] = 'gif|jpg|png'; //load the upload library

						$this->load->library('upload', $config);

						$this->upload->initialize($config);

						$this->upload->set_allowed_types('*');

						$data['upload_data'] = '';



						if (!$this->upload->do_upload('banner_image')) {

							$data = array('msg' => $this->upload->display_errors());

						} else { //else, set the success message

							$data = array('msg' => "Upload success!");

							$data['upload_data'] = $this->upload->data();

						}

						$data['image_name'] = $this->upload->data();

					} else // new file is not uploaded

					{

						$data['image_name']['file_name'] = $this->input->post('cat_image');

					}
					

$count = count($bannerArr['category_name']);
$j = 1;
for ($i = 0; $i < $count; $i++) {
    // Old image
    $image = $this->input->post('old_category_logo')[$i];

    // Upload new image if selected
    if (!empty($_FILES['category_logo']['name'][$i])) {

        $_FILES['temp_file']['name']     = $_FILES['category_logo']['name'][$i];
        $_FILES['temp_file']['type']     = $_FILES['category_logo']['type'][$i];
        $_FILES['temp_file']['tmp_name'] = $_FILES['category_logo']['tmp_name'][$i];
        $_FILES['temp_file']['error']    = $_FILES['category_logo']['error'][$i];
        $_FILES['temp_file']['size']     = $_FILES['category_logo']['size'][$i];

        $this->upload->initialize($config);

        if ($this->upload->do_upload('temp_file')) {

            $uploadData = $this->upload->data();
            $image = trim($uploadData['file_name']);

        } else {

            echo $this->upload->display_errors();
            exit;

        }
    }

    $bdata = array(
        'category_name'         => $bannerArr['category_name'][$i],
        'category_logo'         => $image,
        'product_slider_title'  => $bannerArr['product_slider_title'][$i],
		'banner_image'          => $data['image_name']['file_name']
    );

    // Update query
    $this->db->where('banner_id', $j); // if you have id
    $this->db->update('tbl_appbannerdata', $bdata);
$j++;
    // OR Insert
    // $this->db->insert('tbl_appbannerdata', $data);
}




		$this->session->set_flashdata('success_message', 'Website configuration details has been saved successfully');

		redirect('app-bannerdata');

		exit;

	}



}
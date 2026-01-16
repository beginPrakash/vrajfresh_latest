<?php

class Controller_configuration extends CI_Controller{
	
	public function __construct()
    {
        parent::__construct();	
        $this->load->model('configuration_model');	
        $this->load->model('special_products_model');	
        $this->load->model('product_model');	
		$this->load->model('common_model');
		
		if(!IsUserLogin()){
			$authorized_error = "You are not authorized to view this page....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}
		
    }
	
	public function website()
	{
		$ArrData = array();
		$ArrData['ArrConfiguration'] = $this->configuration_model->getConfiguration();
		$ArrData['ArrSpecialProducts'] = $this->special_products_model->getSpecialProducts();
		//$ArrData['ArrProducts'] = $this->product_model->product_list_data();
		
		//echo "<pre>";print_r($ArrData['ArrProducts']);exit;
		$ArrData['cms_title'] = 'Website Configuration';
		$ArrData['view_name'] = 'view_website_configuration.php';		
		$this->load->view('admin_panel/admin_panel',$ArrData);
	}
	public function save_website_configuration()
	{
		$ArrData = $_POST['ArrData'];
		$ArrProducts = $_POST['ArrProducts'];
		
		if(count($ArrData)>0)
		{
			foreach($ArrData as $configuration_id=>$configuration_value)
			{
				$Arrdata = array(
				'configuration_value'=> $configuration_value,
				'modified_datetime' => date('Y-m-d H:i:s'),
				'modified_by' => get_current_admin_id(),
				);
				$this->configuration_model->update($configuration_id,$Arrdata);
			}
		}
		//echo "<pre>";print_r($ArrProducts);exit;
		if(count($ArrProducts)>0)
		{
			foreach($ArrProducts as $special_product_id=>$Arrproduct_ids)
			{
				$product_ids = implode(",",$Arrproduct_ids);
				$Arrdata = array(
				'product_ids'=> $product_ids,
				'modified_datetime' => date('Y-m-d H:i:s'),
				'modified_by' => get_current_admin_id(),
				);
				$this->special_products_model->update($special_product_id,$Arrdata);
			}
		}
			
		$this->session->set_flashdata('success_message', 'Website configuration details has been saved successfully');
		redirect('website-configuration');exit;
	}
	
}
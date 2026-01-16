<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Controller_historicorder extends CI_Controller{
	
	public function __construct()
    {
        parent::__construct();
        $this->load->model('historicorder_model');		
        $this->load->model('order_product_model');		
		$this->load->model('common_model');
        $this->load->model('product_model');	
        $this->load->model('transactions_model');	
				
		if(!IsUserLogin()){
			$authorized_error = "You are not authorized to view this page....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}
		
		}
		
	public function index()
	{
	    error_reporting(E_ALL);
		$ArrData = $this->historicorder_model->getOrderListData();
		
		if(@$_REQUEST['columns'] != "")
		{						
			$output = array('sEcho' => $_REQUEST['draw'],'iTotalRecords' => $ArrData['iTotalRecords'],'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'],'aaData' => array());
				
				$i = $_REQUEST['start']+1;
				foreach($ArrData['result'] as $aRow)
				{
					$row = array();
					$base_url = base_url();	
					$order_id = $aRow['order_number'];
					$actions = '';
                    
					// $row[] = $actions;
					$row[] = $aRow['order_number'];
					$row[] = $aRow['order_status'];
					$row[] = $aRow['order_date'];
					$row[] = $aRow['customer_note'];
					$row[] = $aRow['first_name'];
					$row[] = $aRow['last_name'];
					$row[] = $aRow['company'];
					$row[] = $aRow['address'];
					$row[] = $aRow['city'];
					$row[] = $aRow['state_code'];
					$row[] = $aRow['post_code'];
					$row[] = $aRow['country_code'];
					$row[] = $aRow['email'];
					$row[] = $aRow['phone'];
					$row[] = $aRow['payment_method_title'];
					$row[] = $aRow['cart_discount_amount'];
					$row[] = $aRow['order_subtotal'];
					$row[] = $aRow['delivery_method_title'];
					$row[] = $aRow['order_delivery_amount'];
					$row[] = $aRow['order_refund_amount'];
					$row[] = $aRow['order_total_amount'];
					$row[] = $aRow['order_total_tax_amont'];
					$row[] = $aRow['sku'];
					$row[] = $aRow['item'];
					$row[] = $aRow['item_name'];
					$row[] = $aRow['quantity'];
					$row[] = $aRow['item_cost'];


					//$row[] = getIsactiveButtonForList($aRow['is_active'],$order_id,'tbl_order','order_id');
					//$is_active = ($aRow['is_active']=='0')?'<small class="label label-warning">No</small>':'<small class="label label-info">Yes</small>';
					//$row[] = $is_active;
       
					$i++;
					$output['aaData'][] = $row;

				}
				echo json_encode($output);
				exit;
		}
		/* DATA TABLE END */
		$ArrPageData = array();
		$ArrPageData['cms_title'] = 'Historic Order List';
		$ArrPageData['button_url'] = '';
		$ArrPageData['button_label'] = '';
		$ArrPageData['view_name'] = 'view_historic_order.php';
		$this->load->view('admin_panel/admin_panel',$ArrPageData);
	}
}
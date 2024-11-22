<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Controller_product_variant extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('product_variant_master_model');
		$this->load->model('common_model');	
		$this->module_name = 'product_variant';
		if(!IsUserLogin()){
			$authorized_error = "You are not authorized to view this product_variant....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}
	}

	#LIST PAGE
	public function index()
	{		
		$column_order = array('null','tblproduct_variant_master.created_datetime','tblproduct_variant_master.product_variant_name','tblproduct_variant_master.is_active'); 
		
		$column_search = array('tblproduct_variant_master.product_variant_id','tblproduct_variant_master.created_datetime','tblproduct_variant_master.product_variant_name','tblproduct_variant_master.is_active'); 
		
		$aColumns = array('tblproduct_variant_master.product_variant_id','tblproduct_variant_master.created_datetime','tblproduct_variant_master.product_variant_name','tblproduct_variant_master.is_active'); 
		
		$sTable = 'tblproduct_variant_master';
		
		$i=0;
		foreach ($column_search as $item) { /*loop column */        
			if(@$_POST['search']['value'] || @$_POST['txtSearchKeyWord'])  /*if datatable send POST for search*/
			{                            
				if($i===0)
				{ 
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					if($_POST['search']['value'] && $_POST['txtSearchKeyWord']) {
						$this->db->like($item, $_POST['search']['value']);                        
						$this->db->or_like($item, $_POST['txtSearchKeyWord']);
					}  else if ($_POST['search']['value'] && !$_POST['txtSearchKeyWord']) {                            
						$this->db->like($item, $_POST['search']['value']);
					} else if (!$_POST['search']['value'] && $_POST['txtSearchKeyWord']) {                            
						$this->db->like($item, $_POST['txtSearchKeyWord']);
					}
				}
				else
				{
					if($_POST['search']['value']) { $this->db->or_like($item, $_POST['search']['value']);}
					if($_POST['txtSearchKeyWord']){$this->db->or_like($item, $_POST['txtSearchKeyWord']);}
				} 
				if(count($column_search) - 1 == $i) { $this->db->group_end(); /*close bracket*/ }
			}
			$i++;
		}

		if(@$_REQUEST['ddIsActive'] != "")
		{
		$this->db->where('is_active',$_REQUEST['ddIsActive']);		 
		}
		
		// set columns start
		if(@$_REQUEST['columns'] != "") 
		{
			if(@$_REQUEST['columns'] != ""){

			if(@$_REQUEST['length'] != -1){
			$this->db->limit($_REQUEST['length'], $_REQUEST['start']);
			}
			}

			$this->db->where('is_deleted',0);
			// Select Data
			$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $aColumns)), false);
			$order = array('tblproduct_variant_master.product_variant_id' => 'DESC'); 
			if(isset($_POST['order'])) { // here order processing
			$this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
			}  else if(isset($order)) {
			$order = $order;
			$this->db->order_by(key($order), $order[key($order)]);
			}


			$rResult = $this->db->get($sTable);

			// Data set length after filtering
			$this->db->select('FOUND_ROWS() AS found_rows');
			$iFilteredTotal = $this->db->get()->row()->found_rows;

			// Total data set length
			$iTotal = $this->db->count_all($sTable);

			// Output
			$output = array('sEcho' => $_REQUEST['draw'],'iTotalRecords' => $iTotal,'iTotalDisplayRecords' => $iFilteredTotal,'aaData' => array());
				
			$i = $_REQUEST['start']+1;
			foreach($rResult->result_array() as $aRow)
			{
				$row = array();				
				$row[] = $i;
				$row[] = changeDateFormat($aRow['created_datetime']);
				$row[] = $aRow['product_variant_name'];
				$row[] = getIsactiveButtonForList($aRow['is_active'],$aRow['product_variant_id'],'tblproduct_variant_master','product_variant_id');	
				if($aRow['product_variant_id']==1 OR $aRow['product_variant_id']==2)
				{
				$row[] = '<div class="btn-group">'.getActionButtonForList($aRow['product_variant_id'],$this->module_name,array("V","E"))."</div>";
				}
				else
				{
				$row[] = '<div class="btn-group">'.getActionButtonForList($aRow['product_variant_id'],$this->module_name,array("V","E","D"))."</div>";					
				}
				$i++;
				$output['aaData'][] = $row;
			}
			echo json_encode($output);
			exit;
		}
		/* DATA TABLE END */
		$ArrProductVariantData['cms_title'] = 'Product Variants';
		
		$ArrProductVariantData['button_url'] = $this->module_name.'-add';
		$ArrProductVariantData['button_label'] = '';//'Add Product Variant';
		$ArrProductVariantData['view_name'] = 'view_'.$this->module_name.'_list.php';		
		$this->load->view('admin_panel/admin_panel',$ArrProductVariantData);
	}

	#ADD/EDIT PAGE
	public function add($id=0)
	{
		$ArrProductVariantData = array();
		$ArrProductVariantData['ArrProductVariantData'] = array();
		if($id > 0 ){
			$ArrProductVariantData['ArrProductVariantData'] = $this->product_variant_master_model->getProductVariantDetailsUsingID($id);
			$ArrProductVariantData['cms_title'] = 'Update Product Variant';
			$ArrProductVariantData['button_url'] = base_url().$this->module_name;
		}else{
			$ArrProductVariantData['cms_title'] = 'Add Product Variant';
			$ArrProductVariantData['button_url'] = base_url().$this->module_name;
		}
		$ArrProductVariantData['button_label'] = 'View Product Variants';
		
		$ArrProductVariantData['view_name'] = 'view_'.$this->module_name.'_addedit.php';
		$js_assets = array(
			array(ADMIN_PANEL_THEME_PATH.'dist/js/'.$this->module_name.'-script.js'),
		);
		$this->carabiner->js( $js_assets );
		$this->load->view('admin_panel/admin_panel',$ArrProductVariantData);
	}

	#ADD/EDIT PAGE
	public function delete($id=0)
	{
		$result = $this->product_variant_master_model->delete($id);
		if($result){
			echo 'Yes';
		}else{
			echo 'No';
		}
	}
	
	#ADD/EDIT PROCESS
	public function save()
	{
		if( !isset($_POST['save_product_variant']) ){
			redirect($this->module_name);exit;
		}
		if(isset($_POST) && $_POST['save_product_variant']!="" && $_POST['save_product_variant']=="Add")
		{

			$product_variant_name = (trim($_POST['product_variant_name']))?$_POST['product_variant_name']:'';
			$is_active = $_POST['is_active'];

			
			$Arrdata = array(
				'product_variant_name' => $product_variant_name,
				'product_variant_value' => implode(",",$_POST['product_variant_value']),
				'created_by' => get_current_admin_id(),
				'created_datetime' => date('Y-m-d H:i:s'),
				'modified_by' => get_current_admin_id(),
				'modified_datetime' => NULL,
				'is_active' => $is_active
			);
			$product_variant_id = $this->product_variant_master_model->add($Arrdata);
			if($product_variant_id>0){
				$this->session->set_flashdata('success_message', 'Product Variants details has been saved successfully');
				redirect($this->module_name.'-update/'.$product_variant_id);exit;
			}else{
				$this->session->set_flashdata('error_message', 'Oops...! something went wrong to insert product_variant details, please try again');
				redirect($this->module_name.'-add');exit;
			}

		}
		if(isset($_POST) && $_POST['save_product_variant']!="" && $_POST['save_product_variant']=="Update" && $_POST['product_variant_id'] > 0)
		{

			$product_variant_name = (trim($_POST['product_variant_name']))?$_POST['product_variant_name']:'';
			$is_active = $_POST['is_active'];
			$product_variant_id = $_POST['product_variant_id'];
			
			

			$Arrdata = array(
				'product_variant_name' => $product_variant_name,
				'product_variant_value' => implode(",",$_POST['product_variant_value']),
				'modified_datetime' => date('Y-m-d H:i:s'),
				'modified_by' => get_current_admin_id(),
				'is_active' => $is_active
			);
			$result = $this->product_variant_master_model->update($product_variant_id,$Arrdata);
			if($result){
				$this->session->set_flashdata('success_message', 'Product Variants details has been updated successfully');
				//echo $this->session->flashdata('success_message');exit;
				redirect($this->module_name);exit;
			}else{
				$this->session->set_flashdata('error_message', 'Oops...! something went wrong to update product_variant details, please try again');
				redirect($this->module_name.'-update/'.$product_variant_id);exit;
			}
		}
	}

	#ADD/EDIT PAGE
	public function ajaxShowTblproduct_variantMasterData()
	{
		$product_variant_id = $_POST['product_variant_id'];
		$ArrProductVariantData['ArrFieldData'] = $this->product_variant_master_model->getProductVariantDetailsUsingID($product_variant_id);
		$this->load->view('admin_panel/view_product_variant_details_using_ajax',$ArrProductVariantData);
	}
	
	/* PAGE URL DUBLICATE */
	public function ajaxCheckUrl()
	{
		$option1 = $_POST['option1'];
		$product_variant_id = $_POST['product_variant_id'];
		$result = $this->product_variant_master_model->isUrlExist($option1,$product_variant_id);
		if($result == 1 ){
			echo 'Yes';
		}else{
			echo 'No';
		}

	}

	//- Export to csv of product_variant - START
	public function product_variant_list_export()
	{
		extract($_POST);
		$this->load->helper('csv_helper');
		$ArrDataList = $this->product_variant_master_model->ExportProductVariantListData($_POST);		
		$data = array();
		$no = 0;

		foreach ($ArrDataList as $ArrData) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $ArrData['created_datetime'];
			$row[] = $ArrData['product_variant_name'];
			$row[] = $ArrData['is_active'];			
			$data[] = $row;
		}
		$report_title = "qe_user_refer_list_".time();
		$ArrHeading = array('Sr No','Created Date','Product Variant','ProductVariant Url','Is Active');
		array_to_csv($ArrHeading,$data,$report_title);		
	}
	//- Export to csv of product_variant - END



}

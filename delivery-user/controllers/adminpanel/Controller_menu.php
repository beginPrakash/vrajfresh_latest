<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Controller_menu extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('menu_master_model');
		$this->load->model('common_model');	
		$this->module_name = 'menu';
		if(!IsUserLogin()){
			$authorized_error = "You are not authorized to view this menu....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}
	}

	#LIST PAGE
	public function index()
	{		
		$column_order = array('null','tbl_menu_master.created_datetime','tbl_menu_master.menu_name','tbl_menu_master.is_active'); 
		
		$column_search = array('tbl_menu_master.menu_id','tbl_menu_master.created_datetime','tbl_menu_master.menu_name','tbl_menu_master.is_active'); 
		
		$aColumns = array('tbl_menu_master.menu_id','tbl_menu_master.created_datetime','tbl_menu_master.menu_name','tbl_menu_master.is_active'); 
		
		$sTable = 'tbl_menu_master';
		
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

			// Select Data
			$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $aColumns)), false);
			$order = array('tbl_menu_master.menu_id' => 'DESC'); 
			if(isset($_POST['order'])) { // here order processing
			$this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
			}  else if(isset($order)) {
			$order = $order;
			$this->db->order_by(key($order), $order[key($order)]);
			}

			$this->db->where('is_deleted', 0);

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
				$row[] = $aRow['menu_name'];
				$row[] = getIsactiveButtonForList($aRow['is_active'],$aRow['menu_id'],'tbl_menu_master','menu_id');	
				if($aRow['menu_id']==1 OR $aRow['menu_id']==2)
				{
				$row[] = '<div class="btn-group">'.getActionButtonForList($aRow['menu_id'],$this->module_name,array("V","E"))."</div>";
				}
				else
				{
				$row[] = '<div class="btn-group">'.getActionButtonForList($aRow['menu_id'],$this->module_name,array("V","E","D"))."</div>";					
				}
				$i++;
				$output['aaData'][] = $row;
			}
			echo json_encode($output);
			exit;
		}
		/* DATA TABLE END */
		$ArrMenuData['cms_title'] = 'Menus';
		
		$ArrMenuData['button_url'] = $this->module_name.'-add';
		$ArrMenuData['button_label'] = '';
		$ArrMenuData['view_name'] = 'view_'.$this->module_name.'_list.php';		
		$this->load->view('admin_panel/admin_panel',$ArrMenuData);
	}

	#ADD/EDIT PAGE
	public function add($id=0)
	{
		$ArrData = array();
		$ArrData['ArrMenuData'] = array();
		$ArrData['ArrMenuItemsData'] = array();
		if($id > 0 ){
			$ArrData['ArrMenuData'] = $this->menu_master_model->getMenuDetailsUsingID($id);
			$ArrData['ArrMenuItemsData'] = $this->menu_master_model->getMenuItemsUsingMenuId($id);
			$ArrData['cms_title'] = 'Update Menu';
			$ArrData['button_url'] = base_url().$this->module_name;
		}else{
			$ArrData['cms_title'] = 'Add Menu';
			$ArrData['button_url'] = base_url().$this->module_name;
		}
		$ArrData['button_label'] = 'View Menus';
		
		$ArrData['view_name'] = 'view_'.$this->module_name.'_addedit.php';
		$js_assets = array(
			array(ADMIN_PANEL_THEME_PATH.'dist/js/'.$this->module_name.'-script.js'),
		);
		$this->carabiner->js( $js_assets );
		$this->load->view('admin_panel/admin_panel',$ArrData);
	}

	#ADD/EDIT PAGE
	public function delete($id=0)
	{
		$result = $this->menu_master_model->delete($id);
		if($result){
			echo 'Yes';
		}else{
			echo 'No';
		}
	}
	
	#ADD/EDIT PROCESS
	public function save()
	{
		if( !isset($_POST['save_menu']) ){
			redirect($this->module_name);exit;
		}
		//echo "<pre>";print_r($_POST);exit;
		
		if(isset($_POST) && $_POST['save_menu']!="" && $_POST['save_menu']=="Add")
		{

			$menu_name = (trim($_POST['menu_name']))?$_POST['menu_name']:'';
			$is_active = $_POST['is_active'];

			
			$Arrdata = array(
				'menu_name' => $menu_name,
				'created_by' => get_current_admin_id(),
				'created_datetime' => date('Y-m-d H:i:s'),
				'modified_by' => get_current_admin_id(),
				'modified_datetime' => NULL,
				'is_active' => $is_active
			);
			$menu_id = $this->menu_master_model->add($Arrdata);
			if($menu_id>0){
				
				//ADD MENU ITEMS
				/*$ArrMenuTitle = $_POST['menu_title'];
				$ArrMenuLink = $_POST['menu_link'];
				for($i=0;$i<count($ArrMenuTitle);$i++)
				{
					$Arrdata = array(
						'menu_id' => $menu_id,
						'menu_title' => $ArrMenuTitle[$i],
						'menu_link' => $ArrMenuLink[$i],
						'created_by' => get_current_admin_id(),
						'created_datetime' => date('Y-m-d H:i:s'),
						'modified_by' => get_current_admin_id(),
						'modified_datetime' => NULL,
						'is_active' => $is_active
					);
					$this->menu_master_model->addMenuItems($Arrdata);
				}*/
				$ArrMenuTitle = $_POST['menu_title'];
				$ArrMenuLink = $_POST['menu_link'];
				$ArrAllSubMenuLink = $_POST['sub_menu_link'];
				$ArrAllSubMenuTitle = $_POST['sub_menu_title'];
				foreach($ArrMenuTitle as $i=>$data)
				{
					if( isset($ArrMenuTitle[$i][0]) && isset($ArrMenuLink[$i][0]) )
					{
						$menu_title = $ArrMenuTitle[$i][0];
						$menu_link = $ArrMenuLink[$i][0];
						$Arrdata = array(
							'menu_id' => $menu_id,
							'menu_title' => $menu_title,
							'menu_link' => remove_special_characters($menu_link),
							'created_by' => get_current_admin_id(),
							'created_datetime' => date('Y-m-d H:i:s'),
							'modified_by' => get_current_admin_id(),
							'modified_datetime' => NULL,
							'is_active' => $is_active
						);
						$parent_menu_item_id = $this->menu_master_model->addMenuItems($Arrdata);
						
						if(isset($ArrAllSubMenuTitle[$i]) && $parent_menu_item_id>0)
						{
							$ArrSubMenuTitle = $ArrAllSubMenuTitle[$i];
							$ArrSubMenuLink = $ArrAllSubMenuLink[$i];
							if(is_array($ArrSubMenuTitle) && count($ArrSubMenuTitle)>0)
							{
								for($m=0;$m<count($ArrSubMenuTitle);$m++)
								{
									$Arrdata = array(
										'menu_id' => $menu_id,
										'parent_menu_item_id' => $parent_menu_item_id,
										'menu_title' => $ArrSubMenuTitle[$m],
										'menu_link' => remove_special_characters($ArrSubMenuLink[$m]),
										'created_by' => get_current_admin_id(),
										'created_datetime' => date('Y-m-d H:i:s'),
										'modified_by' => get_current_admin_id(),
										'modified_datetime' => NULL,
										'is_active' => $is_active
									);
									$this->menu_master_model->addMenuItems($Arrdata);
								}
							}
						}
					}
				}
				//END
				
				$this->session->set_flashdata('success_message', 'Menu details has been saved successfully');
				redirect($this->module_name.'-update/'.$menu_id);exit;
			}else{
				$this->session->set_flashdata('error_message', 'Oops...! something went wrong to insert menu details, please try again');
				redirect($this->module_name.'-add');exit;
			}

		}
		if(isset($_POST) && $_POST['save_menu']!="" && $_POST['save_menu']=="Update" && $_POST['menu_id'] > 0)
		{
			//echo "<pre>";print_r($_POST);exit;
			$menu_name = (trim($_POST['menu_name']))?$_POST['menu_name']:'';
			$is_active = $_POST['is_active'];
			$menu_id = $_POST['menu_id'];
			
			

			$Arrdata = array(
				'menu_name' => $menu_name,
				'modified_datetime' => date('Y-m-d H:i:s'),
				'modified_by' => get_current_admin_id(),
				'is_active' => $is_active
			);
			$result = $this->menu_master_model->update($menu_id,$Arrdata);
			
			//ADD MENU ITEMS
			$this->menu_master_model->deleteMenuItems($menu_id);
			/*$ArrMenuTitle = $_POST['menu_title'];
			$ArrMenuLink = $_POST['menu_link'];
			for($i=0;$i<count($ArrMenuTitle);$i++)
			{
				$Arrdata = array(
					'menu_id' => $menu_id,
					'menu_title' => $ArrMenuTitle[$i],
					'menu_link' => $ArrMenuLink[$i],
					'created_by' => get_current_admin_id(),
					'created_datetime' => date('Y-m-d H:i:s'),
					'modified_by' => get_current_admin_id(),
					'modified_datetime' => NULL,
					'is_active' => $is_active
				);
				$this->menu_master_model->addMenuItems($Arrdata);
			}*/
				if(isset($_POST['menu_title']))
					$ArrMenuTitle = $_POST['menu_title'];
				if(isset($_POST['menu_link']))
					$ArrMenuLink = $_POST['menu_link'];
				if(isset($_POST['sub_menu_link']))
					$ArrAllSubMenuLink = $_POST['sub_menu_link'];
				if(isset($_POST['sub_menu_title']))
					$ArrAllSubMenuTitle = $_POST['sub_menu_title'];
				if(isset($ArrMenuTitle) && count($ArrMenuTitle)>0)
				{
					foreach($ArrMenuTitle as $i=>$data)
					{
						if( isset($ArrMenuTitle[$i][0]) && isset($ArrMenuLink[$i][0]) )
						{
							$menu_title = $ArrMenuTitle[$i][0];
							$menu_link = $ArrMenuLink[$i][0];
							$Arrdata = array(
								'menu_id' => $menu_id,
								'menu_title' => $menu_title,
								'menu_link' => $menu_link,
								'created_by' => get_current_admin_id(),
								'created_datetime' => date('Y-m-d H:i:s'),
								'modified_by' => get_current_admin_id(),
								'modified_datetime' => NULL,
								'is_active' => $is_active
							);
							$parent_menu_item_id = $this->menu_master_model->addMenuItems($Arrdata);
							//echo "<pre>fff";print_r($ArrAllSubMenuTitle);echo "<pre>fff";print_r($ArrAllSubMenuLink);exit;
							if(isset($ArrAllSubMenuTitle[$i]) && $parent_menu_item_id>0)
							{
								$ArrSubMenuTitle = $ArrAllSubMenuTitle[$i];
								$ArrSubMenuLink = $ArrAllSubMenuLink[$i];
								if(is_array($ArrSubMenuTitle) && count($ArrSubMenuTitle)>0)
								{
									for($m=0;$m<count($ArrSubMenuTitle);$m++)
									{
										$Arrdata = array(
											'menu_id' => $menu_id,
											'parent_menu_item_id' => $parent_menu_item_id,
											'menu_title' => $ArrSubMenuTitle[$m],
											'menu_link' => remove_special_characters($ArrSubMenuLink[$m]),
											'created_by' => get_current_admin_id(),
											'created_datetime' => date('Y-m-d H:i:s'),
											'modified_by' => get_current_admin_id(),
											'modified_datetime' => NULL,
											'is_active' => $is_active
										);
										$this->menu_master_model->addMenuItems($Arrdata);
									//echo "<br>".$this->db->last_query();
									}
								}
							}
						}
					}
				}
			//END
			//echo "exit";exit;
			if($result){
				$this->session->set_flashdata('success_message', 'Menu details has been updated successfully');
				//echo $this->session->flashdata('success_message');exit;
				redirect($this->module_name);exit;
			}else{
				$this->session->set_flashdata('error_message', 'Oops...! something went wrong to update menu details, please try again');
				redirect($this->module_name.'-update/'.$menu_id);exit;
			}
		}
	}

	#ADD/EDIT PAGE
	public function ajaxShowTblmenuMasterData()
	{
		$menu_id = $_POST['menu_id'];
		$ArrMenuData['ArrFieldData'] = $this->menu_master_model->getMenuDetailsUsingID($menu_id);
		$ArrMenuData['ArrMenuItemsData'] = $this->menu_master_model->getMenuItemsUsingMenuId($menu_id);
		$this->load->view('admin_panel/view_menu_details_using_ajax',$ArrMenuData);
	}
	
	/* PAGE URL DUBLICATE */
	public function ajaxCheckUrl()
	{
		$option1 = $_POST['option1'];
		$menu_id = $_POST['menu_id'];
		$result = $this->menu_master_model->isUrlExist($option1,$menu_id);
		if($result == 1 ){
			echo 'Yes';
		}else{
			echo 'No';
		}

	}

	



}

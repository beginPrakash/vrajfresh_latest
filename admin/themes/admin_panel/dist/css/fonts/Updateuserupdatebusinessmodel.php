<?php
class Updateuserupdatebusinessmodel extends CI_Model 
{
	
	public function update_business_data($table,$data,$condition){
		$this->db->where($condition);
		$this->db->update($table, $data);
		$filename = "somefile.txt";
		$fh = fopen($filename, "a+");
		fwrite($fh, $this->db->last_query().PHP_EOL);
		fclose($fh);
	}

	public function to_get_platformID_by_name($site_master_id,$name){
		$this->db->select("plateform_id");	    
	    $this->db->where('plateform_name', $name);	    
	    $this->db->where('site_master_id', $site_master_id);	    
	    $q = $this->db->get("tblplateforms_list")->row_array();
	    if($q){
			return $q['plateform_id'];		
		}else{
			$ins_arr['plateform_name'] = $name;
			$ins_arr['status'] = 1;
			$ins_arr['site_master_id'] = $site_master_id;
			$ins_arr['created_date'] = date('Y-m-d H:i:s');
			$this-> db->insert('tblplateforms_list', $ins_arr);
			return $this->db->insert_id();	
		}
	}

	public function to_get_sitemaster_id($user_id){
		$this->db->select("site_master_id");	    
	    $this->db->where('user_id', $user_id);
	    $q = $this->db->get("tbl_users")->row_array();	    
		return $q['site_master_id'];
	}

	public function to_get_bus_cat_id_by_name($name){
		$this->db->select("business_category_id");	    
	    $this->db->where('business_category_name', $name);
	    $q = $this->db->get("tblbusiness_category")->row_array();
	    if($q){
			return $q['business_category_id'];		
		}else{
			$ins_arr['business_category_name'] = $name;
			$ins_arr['description'] = '';
			$ins_arr['business_category_image'] = '';
			$ins_arr['display_order'] = 1;
			$ins_arr['site_master_id'] = 1;
			$ins_arr['modified_by'] = 0;
			$ins_arr['last_modified_date'] = date('Y-m-d H:i:s');
			$this-> db->insert('tblbusiness_category', $ins_arr);
			$filename = "somefile.txt";
			$fh = fopen($filename, "a+");
			fwrite($fh, $this->db->last_query().PHP_EOL);
			fclose($fh);
			return $this->db->insert_id();
		}		

	}

	public function insert_user_business_category($data){
		$this-> db->insert('tbluser_business_category', $data);
		$filename = "somefile.txt";
		$fh = fopen($filename, "a+");
		fwrite($fh, $this->db->last_query().PHP_EOL);
		fclose($fh);
	}

	public function chk_user_bus_cat_exist($bus_id,$cat_id){
		$this->db->where('customer_business_id', $bus_id);
		$this->db->where('business_category_id', $cat_id);
		$query = $this->db->get('tbluser_business_category');
		if($query->num_rows() > 0){
			return 0;
		}else{
			return 1;
		}
	}

	public function get_userid_by_email($email){
		$this->db->where('alternate_email', $email);		
		$query = $this->db->get('tbl_users');
		if($query->num_rows() > 0){
			return 'Yes';
		}else{
			return 'No';
		}
	}
	public function get_userid_by_id($id){
		$this->db->where('user_id', $id);		
		$query = $this->db->get('tbl_users');
		if($query->num_rows() > 0){
			return 'Yes';
		}else{
			return 'No';
		}
	}

	public function data_exist_in_userBusiness_tbl($id){
		$this->db->where('customer_business_id', $id);		
		$query = $this->db->get('tbluser_business');
		if($query->num_rows() > 0){
			return 0;
		}else{
			return 1;
		}	
	}

	// fuction get the plateform id is for sitemaster id is 2 
	public function get_plateform_id_for_qe_html(){
		$query = $this->db->query('SELECT oa.order_allocation_id,oa.order_id,oa.website_platform,oa.site_master_id,pl.site_master_id AS platform_site_master_id,
pl.plateform_name, CASE WHEN pl1.plateform_id IS NULL THEN 0 ELSE pl1.plateform_id END AS correct_plateform_id
FROM tblorder_allocation oa
LEFT JOIN tblplateforms_list pl ON pl.plateform_id = oa.website_platform
LEFT JOIN tblplateforms_list pl1 ON pl1.plateform_name = pl.plateform_name AND pl1.site_master_id = 2
WHERE oa.site_master_id = 2 AND oa.website_platform IS NOT NULL AND pl.site_master_id != 2');
		return $query->result_array();
	}

	//  function to get the plateform and sitemaster id
	public function get_plateform_id_by_name_site_id($plateform_name,$site_master_id){		
		$this->db->select('plateform_id');
		$this->db->where('plateform_name', $plateform_name);
		$this->db->where('site_master_id', $site_master_id);		
		$query = $this->db->get("tblplateforms_list")->row_array();		
	    if(count($query) > 0){
			return $query['plateform_id'];		
		}else{
			$ins_arr['plateform_name'] = $plateform_name;			
			$ins_arr['site_master_id'] = 2;			
			$ins_arr['status'] = 1;			
			$ins_arr['created_date'] = date('Y-m-d H:i:s');
			$this-> db->insert('tblplateforms_list', $ins_arr);			
			return $this->db->insert_id();
		}	
	}

	// life cycle data
	public function get_life_cycle_data_query(){
		$query = $this->db->query("SELECT YEAR(tm.created_date) AS 'Year',
(COUNT(u1.user_id) + COUNT(u2.user_id) + COUNT(u3.user_id) + COUNT(u4.user_id) + COUNT(u5.user_id)) AS Registration ,
COUNT(u1.user_id) AS 'Trust',COUNT(u2.user_id) AS 'Try',
COUNT(u3.user_id) AS 'Buy',COUNT(u4.user_id) AS 'Reorder',COUNT(u5.user_id) AS 'Referred',COUNT(u6.user_id) AS 'Blocked'
FROM tbllead_master AS tm
JOIN tbl_users AS u ON u.user_id = tm.lead_owner_id
LEFT JOIN tbl_users AS u1 ON u1.user_id = tm.customer_id AND u1.qe_life_cycle_stage = 3 AND tm.lead_block = 'N'
LEFT JOIN tbl_users AS u2 ON u2.user_id = tm.customer_id AND u2.qe_life_cycle_stage = 4 AND tm.lead_block = 'N'
LEFT JOIN tbl_users AS u3 ON u3.user_id = tm.customer_id AND u3.qe_life_cycle_stage = 5 AND tm.lead_block = 'N'
LEFT JOIN tbl_users AS u4 ON u4.user_id = tm.customer_id AND u4.qe_life_cycle_stage = 6 AND tm.lead_block = 'N'
LEFT JOIN tbl_users AS u5 ON u5.user_id = tm.customer_id AND u5.qe_life_cycle_stage = 7 AND tm.lead_block = 'N'
LEFT JOIN tbl_users AS u6 ON u6.user_id = tm.customer_id AND tm.lead_block = 'Y'
WHERE (YEAR(tm.created_date) >= '0000' AND YEAR(tm.created_date)<= '3000')
GROUP BY YEAR(tm.created_date)
ORDER BY YEAR(tm.created_date) ASC");
		return $query->result_array();
	}

	// get the ids where data whole order is delievered 
	public function get_order_id_data_query(){
		$query = $this->db->query('SELECT  pl.order_process_log_id,pl.order_id,GROUP_CONCAT(DISTINCT(pl.order_product_id)) AS product_ids FROM tblorder_process_log AS pl WHERE pl.order_id IS NOT NULL AND pl.order_id != 0
			GROUP BY pl.order_id ORDER BY pl.order_id ASC');
		return $query->result_array();
	}

	// product ids deliver or not
	public function get_order_product_id_deliver_query($product_id){
		$query = $this->db->query('SELECT COUNT(order_product_id) AS is_deliever FROM tblorder_process_log 
WHERE order_status_id = 7 AND order_product_id = '. $product_id .'');
		//return $query->result_array()[0]['is_deliever'];
		return 0;
	}
	// product ids deliver or not
	public function get_order_product_id_not_deliver_query($product_id){		
	//	$query = $this->db->query('SELECT COUNT(order_product_id) AS is_deliever FROM tblorder_process_log 
//WHERE order_status_id != 7 AND order_product_id = '. $product_id .'');
$query = $this->db->query("SELECT COUNT(order_product_id) AS is_deliever FROM tblorder_allocation 
WHERE order_allocation_status IN ('3','4','5','6','8','10','15') AND order_product_id = ". $product_id ."");
		// return $query->result_array()[0]['is_deliever'];
		 return 0;
	}	

	// function to get the status of order payment

	public function get_order_paid_staus_deliver_query($order_id){
		$query = $this->db->query('SELECT COUNT(pp.order_id) AS is_unpaid
FROM tblshop_cart_part_payment pp WHERE pp.order_id = '. $order_id .' AND pp.payment_status = "Unpaid"');
		// return $query->result_array()[0]['is_unpaid'];
		return 0;
	}	

	// Function to get the total of the orders

	public function get_order_total_amount_query($order_ids){
		//$query = $this->db->query('SELECT SUM(cart_net_amount) AS total_amount FROM tblorder WHERE order_id IN ('. $order_ids .')');		
		$query = $this->db->query('SELECT SUM(regular_price * qty) as total_amount FROM tblorder_product WHERE order_id IN ('. $order_ids .')');				
		// return $query->result_array()[0]['total_amount'];
		return 0;
	}

	// Function to get the received amount of the orders

	public function get_order_received_amount_query($order_ids){
		$query = $this->db->query('SELECT SUM(payment_amount) AS received_amount FROM tblshop_cart_part_payment 
WHERE part_payment_status = "Success" AND order_id IN  ('. $order_ids .')');
		return $query->result_array()[0]['received_amount'];
	}

	// Function to get the outstandign amount of the orders

	public function get_order_outstanding_amount_query($order_ids){
		$query = $this->db->query('SELECT SUM(payment_amount) AS ourstanding_amount FROM tblshop_cart_part_payment 
WHERE (part_payment_status != "Success" OR part_payment_status IS NULL) AND order_id IN  ('. $order_ids .')');
		return $query->result_array()[0]['ourstanding_amount'];
	}

	// Function to get the discount amount of the orders

	public function get_order_discount_amount_query($order_ids){
		$query = $this->db->query('SELECT SUM((regular_price - member_price) + coupon_discount) total_discount FROM tblorder_product WHERE order_id IN ('. $order_ids .')');
		return $query->result_array()[0]['total_discount'];
	}

	// Function to get the member discount amount of the orders

	public function get_order_member_discount_amount_query($order_ids){
		$query = $this->db->query('SELECT SUM((regular_price - member_price)) as total_member_discount FROM tblorder_product WHERE order_id IN ('. $order_ids .')');
		return $query->result_array()[0]['total_member_discount'];
	}

	// Function to get the member discount amount of the orders

	public function get_order_promo_discount_amount_query($order_ids){
		$query = $this->db->query('SELECT SUM(coupon_discount) as total_promo_discount FROM tblorder_product WHERE order_id IN ('. $order_ids .')');
		return $query->result_array()[0]['total_promo_discount'];
	}


	// Function to get the amount of the orders

	public function get_order_next_amount_query($from_date,$to_date,$order_ids){
		$query = $this->db->query('SELECT CASE WHEN SUM(pp.payment_amount) IS NULL THEN 0 ELSE SUM(pp.payment_amount) END AS next_days_amount FROM tblshop_cart_part_payment AS pp WHERE (pp.part_payment_status != "Success" OR pp.part_payment_status IS NULL) AND pp.order_id IN  ('. $order_ids .') AND DATE(payment_expected_date) >= "'. $from_date .'" AND DATE(payment_expected_date) <= "'. $to_date .'"');
		
		return $query->result_array()[0]['next_days_amount'];
		/*echo $this->db->last_query();echo '<br/>';
		exit;*/
	}

	// Function to get the amount of the orders > 90 days

	public function get_order_amount_after_90_days_query($from_date,$order_ids){
		$query = $this->db->query('SELECT CASE WHEN SUM(pp.payment_amount) IS NULL THEN 0 ELSE SUM(pp.payment_amount) END AS next_days_amount FROM tblshop_cart_part_payment AS pp WHERE (pp.part_payment_status != "Success" OR pp.part_payment_status IS NULL) AND pp.order_id IN  ('. $order_ids .') AND DATE(payment_expected_date) >= "'. $from_date .'"');
		
		return $query->result_array()[0]['next_days_amount'];
		/*echo $this->db->last_query();echo '<br/>';
		exit;*/
	}

	// Function to get the amount of the orders > 90 days

	public function get_order_amount_due_till_now($order_ids){
		$query = $this->db->query('SELECT CASE WHEN SUM(pp.payment_amount) IS NULL THEN 0 ELSE SUM(pp.payment_amount) END 
			AS payment_due_till_now FROM tblshop_cart_part_payment AS pp WHERE (pp.part_payment_status != "Success" OR pp.part_payment_status IS NULL) AND pp.order_id IN  ('. $order_ids .') AND DATE(payment_expected_date) < 
			DATE(NOW())');
		
		return $query->result_array()[0]['payment_due_till_now'];
		/*echo $this->db->last_query();echo '<br/>';
		exit;*/
	}

	// Function to get the amount of the orders previous date

	public function get_order_last_amount_query($from_date,$to_date,$order_ids){
		$query = $this->db->query('SELECT CASE WHEN SUM(pp.payment_amount) IS NULL THEN 0 ELSE SUM(pp.payment_amount) END AS last_days_amount FROM tblshop_cart_part_payment AS pp WHERE (pp.part_payment_status != "Success" OR pp.part_payment_status IS NULL) AND pp.order_id IN  ('. $order_ids .') AND DATE(payment_expected_date) >= "'. $from_date .'" AND DATE(payment_expected_date) <= "'. $to_date .'"');
		
		return $query->result_array()[0]['last_days_amount'];
		/*echo $this->db->last_query();echo '<br/>';
		exit;*/
	}

	// Function to get the amount of the orders < 90 days

	public function get_order_amount_before_90_days_query($from_date,$order_ids){
		$query = $this->db->query('SELECT CASE WHEN SUM(pp.payment_amount) IS NULL THEN 0 ELSE SUM(pp.payment_amount) END AS next_days_amount FROM tblshop_cart_part_payment AS pp WHERE (pp.part_payment_status != "Success" OR pp.part_payment_status IS NULL) AND pp.order_id IN  ('. $order_ids .') AND DATE(payment_expected_date) <= "'. $from_date .'"');
		
		return $query->result_array()[0]['next_days_amount'];
		/*echo $this->db->last_query();echo '<br/>';
		exit;*/
	}

	// Function to get the amount recover in future

	public function get_order_amount_recover_in_future($order_ids){
		$query = $this->db->query('SELECT CASE WHEN SUM(pp.payment_amount) IS NULL THEN 0 ELSE SUM(pp.payment_amount) END 
			AS payment_due_till_now FROM tblshop_cart_part_payment AS pp WHERE (pp.part_payment_status != "Success" OR pp.part_payment_status IS NULL) AND pp.order_id IN  ('. $order_ids .') AND DATE(payment_expected_date) > 
			DATE(NOW())');
		
		return $query->result_array()[0]['payment_due_till_now'];
		/*echo $this->db->last_query();echo '<br/>';
		exit;*/
	}
}
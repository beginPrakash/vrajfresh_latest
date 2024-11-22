<?php
class taxreport_model extends CI_Model
{

	public function getOrderListData($Arr)
	{

        if(!empty(@$_REQUEST['src_month']) || !empty(@$_REQUEST['src_year']) 
           || !empty(@$_REQUEST['src_state'])){
		    $column_order = array(null,'state.state','tbl_orders.order_datetime');

            $aColumns = array('state.state','GROUP_CONCAT(tbl_orders.order_id SEPARATOR ",") as order_ids','sum(tbl_order_products.product_tax_amount) as total_tax','tbl_orders.order_id','state.tax', 'tbl_orders.order_datetime');

            $column_search = array('tbl_orders.order_id');

            $sTable = 'tbl_orders';
            
            $start_date = '01-'.@$_REQUEST['src_month'].'-'.@$_REQUEST['src_year'];
            $end_date = '31-'.@$_REQUEST['src_month'].'-'.@$_REQUEST['src_year'];
            if (!empty($start_date) && !empty($end_date)) {
                $tstart_date = date('Y-m-d', strtotime($start_date));
                $tend_date = date('Y-m-d', strtotime($end_date));
                $this->db->where('tbl_orders.order_datetime >=', $tstart_date . " 00:00:00");
                $this->db->where('tbl_orders.order_datetime <=', $tend_date . " 23:59:59");
            }
           
            if (@$_REQUEST['src_state'] != "") {
                $this->db->where('state.state_id', @$_REQUEST['src_state']);
            }
            
            if (@$_REQUEST['columns'] != "") {
                // set columns start
                if (@$_REQUEST['columns'] != "") {
                    if (@$_REQUEST['length'] != -1) {
                        $this->db->limit($_REQUEST['length'], $_REQUEST['start']);
                    }
                }
                // Select Data
                $this->db->select('SQL_CALC_FOUND_ROWS ' . str_replace(' , ', ' ', implode(', ', $aColumns)), false);



                if (isset($_POST['order'])) { // here order processing
                    //print_r($_POST['order']);
                    $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
                } else {
                    $this->db->order_by('state.state', 'asc');
                }
                $this->db->where('tbl_orders.is_deleted', 0);
                //$this->db->where('tbl_order_products.product_tax_amount <>', '0.00');
                $this->db->where_in('tbl_orders.order_status', ['completed','Payment Processed']);
                $this->db->join('tbl_order_products', 'tbl_order_products.order_id = tbl_orders.order_id', 'left');
                $this->db->join('state', 'state.state_id = tbl_orders.shipping_state_id ', 'left');    
                $this->db->group_by('tbl_orders.shipping_state_id');
                $rResult = $this->db->get($sTable);
                //echo $this->db->last_query();exit;
                $this->db->select('FOUND_ROWS() AS found_rows');
                $iFilteredTotal = $this->db->get()->row()->found_rows;
                $iTotal = $this->db->count_all($sTable);
                //print_r($rResult);exit;
                return array('iTotalRecords' => $iTotal, 'iTotalDisplayRecords' => $iFilteredTotal, 'result' => $rResult->result_array());

            }
        }else{
            return array('iTotalRecords' => 0, 'iTotalDisplayRecords' => 0, 'result' => []);
        }

	}

    public function ExportReportOrderData($Arr)
	{
        if(!empty(@$_REQUEST['src_month']) || !empty(@$_REQUEST['src_year']) 
           || !empty(@$_REQUEST['src_state'])){
		    $aColumns = array('state.state','GROUP_CONCAT(tbl_orders.order_id SEPARATOR ",") as order_ids','sum(tbl_order_products.product_tax_amount) as total_tax','tbl_orders.order_id','state.tax', 'tbl_orders.order_datetime');

            $column_search = array('tbl_orders.order_id');

            $sTable = 'tbl_orders';
            
            $start_date = '01-'.@$_REQUEST['src_month'].'-'.@$_REQUEST['src_year'];
            $end_date = '31-'.@$_REQUEST['src_month'].'-'.@$_REQUEST['src_year'];
            if (!empty($start_date) && !empty($end_date)) {
                $tstart_date = date('Y-m-d', strtotime($start_date));
                $tend_date = date('Y-m-d', strtotime($end_date));
                $this->db->where('tbl_orders.order_datetime >=', $tstart_date . " 00:00:00");
                $this->db->where('tbl_orders.order_datetime <=', $tend_date . " 23:59:59");
            }
           
            if (@$_REQUEST['src_state'] != "") {
                $this->db->where('state.state_id', @$_REQUEST['src_state']);
            }
           
            /* end */
            $this->db->select('SQL_CALC_FOUND_ROWS ' . str_replace(' , ', ' ', implode(', ', $aColumns)), false);

            $this->db->where('tbl_orders.is_deleted', 0);
            //$this->db->where('tbl_order_products.product_tax_amount <>', '0.00');
            $this->db->where_in('tbl_orders.order_status', ['completed','Payment Processed']);
            $this->db->join('tbl_order_products', 'tbl_order_products.order_id = tbl_orders.order_id', 'left');
            $this->db->join('state', 'state.state_id = tbl_orders.shipping_state_id ', 'left');    
            $this->db->group_by('tbl_orders.shipping_state_id');
            $this->db->order_by('state.state', 'asc');
            $rResult = $this->db->get($sTable);
            //echo $this->db->last_query();exit;
            return $rResult->result_array();
        }else{
            return [];
        }
	}

    public function state_list_data() // do not delete this function as it is used for getting brand list
	{
		$this->db->select("*");
		$this->db->from('state');
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
	}

    public function sum_order_no($orderId = []){
        $this->db->select("SUM(order_total_amount) AS order_total_amount");
		$this->db->from('tbl_orders');
        $this->db->where_in('tbl_orders.order_id', $orderId);
		$query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        
    }

    
}
?>
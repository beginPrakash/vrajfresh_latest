<?php
class customreport_model extends CI_Model
{

	public function getOrderListData($Arr)
	{

        if(!empty(@$_REQUEST['txtSearchFrom']) || !empty(@$_REQUEST['txtSearchTo']) 
            || !empty(@$_REQUEST['src_type'])){
		    $column_order = array(null,'tbl_users.created_datetime','tbl_orders.order_id','tbl_orders.user_id');

            $aColumns = array('tbl_orders.order_id','tbl_orders.user_id','tbl_users.created_datetime','tbl_users.email','count(tbl_orders.created_by) as cnt','GROUP_CONCAT(tbl_orders.order_id SEPARATOR ",") as order_id','sum(tbl_orders.order_total_amount) as order_total_amount','GROUP_CONCAT(tbl_orders.order_datetime SEPARATOR ",") as order_date');

            $column_search = array('tbl_orders.order_id');

            $sTable = 'tbl_orders';
            
            if (@$_REQUEST['txtSearchFrom'] != "" && @$_REQUEST['txtSearchTo'] != "") {
                $txtSearchFrom = date('Y-m-d', strtotime(@$_REQUEST['txtSearchFrom']));
                $txtSearchTo = date('Y-m-d', strtotime(@$_REQUEST['txtSearchTo']));
                $this->db->where('tbl_orders.order_datetime >=', $txtSearchFrom . " 00:00:00");
                $this->db->where('tbl_orders.order_datetime <=', $txtSearchTo . " 23:59:59");
            }
            if (@$_REQUEST['txtSearchFrom'] != "" && @$_REQUEST['txtSearchTo'] == "") {
                $txtSearchFrom = date('Y-m-d', strtotime(@$_REQUEST['txtSearchFrom']));
                $this->db->where('tbl_orders.order_datetime >=', $txtSearchFrom . " 00:00:00");
            }
            if (@$_REQUEST['txtSearchTo'] != "" && @$_REQUEST['txtSearchFrom'] == "") {
                $txtSearchTo = date('Y-m-d', strtotime(@$_REQUEST['txtSearchTo']));
                $this->db->where('tbl_orders.order_datetime <=', $txtSearchTo . " 23:59:59");
            }
            if (@$_REQUEST['src_type'] != "") {
                if(@$_REQUEST['src_type'] == "returning_customer"){
                    $this->db->having('cnt >', 1);
                }else{
                    if (@$_REQUEST['txtSearchFrom'] != "" && @$_REQUEST['txtSearchTo'] != "") {
                        $txtSearchFrom = date('Y-m-d', strtotime(@$_REQUEST['txtSearchFrom']));
                        $txtSearchTo = date('Y-m-d', strtotime(@$_REQUEST['txtSearchTo']));
                        $this->db->where('tbl_users.created_datetime >=', $txtSearchFrom . " 00:00:00");
                        $this->db->where('tbl_users.created_datetime <=', $txtSearchTo . " 23:59:59");
                    }
                    if (@$_REQUEST['txtSearchFrom'] != "" && @$_REQUEST['txtSearchTo'] == "") {
                        $txtSearchFrom = date('Y-m-d', strtotime(@$_REQUEST['txtSearchFrom']));
                        $this->db->where('tbl_users.created_datetime >=', $txtSearchFrom . " 00:00:00");
                    }
                    if (@$_REQUEST['txtSearchTo'] != "" && @$_REQUEST['txtSearchFrom'] == "") {
                        $txtSearchTo = date('Y-m-d', strtotime(@$_REQUEST['txtSearchTo']));
                        $this->db->where('tbl_users.created_datetime <=', $txtSearchTo . " 23:59:59");
                    }
                    $this->db->having('cnt <=', 1);
                }
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
                    $this->db->order_by('tbl_users.created_datetime', 'asc');
                }
                $this->db->where('tbl_orders.is_deleted', 0);
                $this->db->where('tbl_users.is_deleted', 0);
                $this->db->join('tbl_users', 'tbl_users.user_id = tbl_orders.user_id', 'left');
                $this->db->group_by('tbl_orders.user_id');
                
                $rResult = $this->db->get($sTable);
                
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
        if(!empty(@$_REQUEST['txtSearchFrom']) || !empty(@$_REQUEST['txtSearchTo']) 
            || !empty(@$_REQUEST['src_type'])){
            $aColumns = array('tbl_orders.order_id','tbl_orders.user_id','tbl_users.created_datetime','tbl_users.email','count(tbl_orders.order_id) as cnt','GROUP_CONCAT(tbl_orders.order_id SEPARATOR ",") as order_id','sum(tbl_orders.order_total_amount) as order_total_amount','GROUP_CONCAT(tbl_orders.order_datetime SEPARATOR ",") as order_date');

            $column_search = array('tbl_orders.order_id');

            $sTable = 'tbl_orders';
            
            if (@$_REQUEST['txtSearchFrom'] != "" && @$_REQUEST['txtSearchTo'] != "") {
                $txtSearchFrom = date('Y-m-d', strtotime(@$_REQUEST['txtSearchFrom']));
                $txtSearchTo = date('Y-m-d', strtotime(@$_REQUEST['txtSearchTo']));
                $this->db->where('tbl_orders.order_datetime >=', $txtSearchFrom . " 00:00:00");
                $this->db->where('tbl_orders.order_datetime <=', $txtSearchTo . " 23:59:59");
            }
            if (@$_REQUEST['txtSearchFrom'] != "" && @$_REQUEST['txtSearchTo'] == "") {
                $txtSearchFrom = date('Y-m-d', strtotime(@$_REQUEST['txtSearchFrom']));
                $this->db->where('tbl_orders.order_datetime >=', $txtSearchFrom . " 00:00:00");
            }
            if (@$_REQUEST['txtSearchTo'] != "" && @$_REQUEST['txtSearchFrom'] == "") {
                $txtSearchTo = date('Y-m-d', strtotime(@$_REQUEST['txtSearchTo']));
                $this->db->where('tbl_orders.order_datetime <=', $txtSearchTo . " 23:59:59");
            }
            if (@$_REQUEST['src_type'] != "") {
                if(@$_REQUEST['src_type'] == "returning_customer"){
                    $this->db->having("cnt > 1", null, false);
                }else{
                    $this->db->having("cnt <= 1", null, false);
                }
            }
           
            /* end */
            $this->db->select('SQL_CALC_FOUND_ROWS ' . str_replace(' , ', ' ', implode(', ', $aColumns)), false);

            $this->db->where('tbl_orders.is_deleted', 0);
            $this->db->where('tbl_users.is_deleted', 0);
            $this->db->join('tbl_users', 'tbl_users.user_id = tbl_orders.user_id', 'left');
            $this->db->group_by('tbl_orders.user_id');
            $this->db->order_by('tbl_users.created_datetime', 'asc');
            $rResult = $this->db->get($sTable);
            //echo $this->db->last_query();exit;
            return $rResult->result_array();
        }else{
            return [];
        }
	}
}
?>
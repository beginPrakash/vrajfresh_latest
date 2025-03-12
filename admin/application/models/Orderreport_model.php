<?php
class orderreport_model extends CI_Model
{

	public function getOrderListData($Arr)
	{

        if(!empty(@$_REQUEST['txtSearchFrom']) || !empty(@$_REQUEST['txtSearchTo']) || !empty(@$_REQUEST['ddIsActive'])
            || !empty(@$_REQUEST['paymentStatus']) || !empty(@$_REQUEST['txtSearchZipcode'])){
		$column_order = array(null, 'tbl_orders.order_id', 'tbl_orders.order_datetime', 'tbl_users.display_name', 'tbl_users.mobile_no','tbl_users.email','tbl_users.address','tbl_users.city','tbl_users.state','tbl_users.zip','tbl_orders.order_total_amount', 'tbl_orders.order_status', 'tbl_orders.is_active', 'tbl_orders.created_datetime');

            $aColumns = array('tbl_orders.order_id', 'tbl_orders.order_datetime', 'tbl_users.display_name', 'tbl_users.mobile_no','tbl_users.email','tbl_users.address','tbl_users.city','tbl_users.state','tbl_users.zip','tbl_orders.order_total_amount', 'tbl_orders.order_status', 'tbl_orders.is_active', 'tbl_orders.created_datetime');

            $column_search = array('tbl_orders.order_id', 'tbl_users.display_name', 'tbl_users.first_name', 'tbl_users.last_name', 'tbl_users.email', 'tbl_orders.order_status', 'tbl_users.mobile_no');

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
            if (@$_REQUEST['ddIsActive'] != "") {
                $this->db->where('tbl_orders.order_status', $_REQUEST['ddIsActive']);
            }
            if (@$_REQUEST['txtSearchZipcode'] != "") {
                $this->db->where('tbl_orders.shipping_zipcode', $_REQUEST['txtSearchZipcode']);
            }
            if (@$_REQUEST['paymentStatus'] != "") {
                $this->db->where('tbl_orders.amount_received_status', $_REQUEST['paymentStatus']);
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
                    $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
                } else {
                    $this->db->order_by('tbl_orders.user_id', 'desc');
                }
                $this->db->where('tbl_orders.is_deleted', 0);
                $this->db->join('tbl_users', 'tbl_users.user_id = tbl_orders.user_id', 'left');
                $rResult = $this->db->get($sTable);
                //echo '<pre>'; print_r( $this->db->last_query() );exit;
                $this->db->select('FOUND_ROWS() AS found_rows');
                $iFilteredTotal = $this->db->get()->row()->found_rows;
                $iTotal = $this->db->count_all($sTable);

                return array('iTotalRecords' => $iTotal, 'iTotalDisplayRecords' => $iFilteredTotal, 'result' => $rResult->result_array());

            }
        }else{
            return array('iTotalRecords' => 0, 'iTotalDisplayRecords' => 0, 'result' => []);
        }

	}

    public function ExportReportOrderData($Arr)
	{
        if(!empty(@$_REQUEST['txtSearchFrom']) || !empty(@$_REQUEST['txtSearchTo']) || !empty(@$_REQUEST['ddIsActive'])
        || !empty(@$_REQUEST['paymentStatus']) || !empty(@$_REQUEST['txtSearchZipcode'])){
            $aColumns = array('tbl_orders.order_id', 'tbl_orders.order_datetime', 'tbl_users.display_name', 'tbl_users.mobile_no','tbl_users.email','tbl_users.address','tbl_users.city','tbl_users.state','tbl_users.zip','tbl_orders.order_total_amount', 'tbl_orders.order_status', 'tbl_orders.is_active', 'tbl_orders.created_datetime');

            $column_search = array('tbl_orders.order_id', 'tbl_users.display_name', 'tbl_users.first_name', 'tbl_users.last_name', 'tbl_users.email', 'tbl_orders.order_status', 'tbl_users.mobile_no');

            $sTable = 'tbl_orders';
            /* search by keyword */
            

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
            if (@$_REQUEST['ddIsActive'] != "") {
                $this->db->where('tbl_orders.order_status', $_REQUEST['ddIsActive']);
            }
            if (@$_REQUEST['txtSearchZipcode'] != "") {
                $this->db->where('tbl_orders.shipping_zipcode', $_REQUEST['txtSearchZipcode']);
            }
            if (@$_REQUEST['paymentStatus'] != "") {
                $this->db->where('tbl_orders.amount_received_status', $_REQUEST['paymentStatus']);
            }
            /* end */
            $this->db->select('SQL_CALC_FOUND_ROWS ' . str_replace(' , ', ' ', implode(', ', $aColumns)), false);

            $this->db->where('tbl_orders.is_deleted', 0);
            $this->db->join('tbl_users', 'tbl_users.user_id = tbl_orders.user_id', 'left');
            $this->db->order_by('tbl_orders.order_id', 'desc');
            $rResult = $this->db->get($sTable);
            //echo $this->db->last_query();exit;
            return $rResult->result_array();
        }else{
            return [];
        }
	}
}
?>
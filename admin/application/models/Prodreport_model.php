<?php
class prodreport_model extends CI_Model
{

	public function getOrderListData($Arr)
	{

        if(!empty(@$_REQUEST['txtSearchFrom']) || !empty(@$_REQUEST['txtSearchTo']) 
            || !empty(@$_REQUEST['src_product'])){
		    $column_order = array(null,'tbl_orders.order_datetime','tbl_order_products.product_name', 'tbl_order_products.product_id', 'tbl_order_products.unit_price');

            $aColumns = array('sum(tbl_order_products.qty) as pro_qty','sum(tbl_order_products.total_amount) as pro_total_amount','tbl_orders.order_id', 'tbl_orders.order_datetime','tbl_order_products.unit_price', 'tbl_order_products.product_name','tbl_order_products.product_id');

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
            if (@$_REQUEST['src_product'] != "") {
                $this->db->where_in('tbl_order_products.product_id', @$_REQUEST['src_product']);
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
                    $this->db->order_by('tbl_orders.order_datetime', 'asc');
                }
                $this->db->where('tbl_orders.is_deleted', 0);
                $this->db->join('tbl_order_products', 'tbl_order_products.order_id = tbl_orders.order_id', 'left');
                $this->db->group_by('DATE(tbl_orders.order_datetime)');
                $this->db->group_by('tbl_order_products.product_id');
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
            || !empty(@$_REQUEST['src_product'])){
            $aColumns = array('sum(tbl_order_products.qty) as pro_qty','sum(tbl_order_products.total_amount) as pro_total_amount','tbl_orders.order_id', 'tbl_orders.order_datetime','tbl_order_products.unit_price', 'tbl_order_products.product_name','tbl_order_products.product_id');

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
            if (@$_REQUEST['src_product'] != "") {
                $this->db->where_in('tbl_order_products.product_id', @$_REQUEST['src_product']);
            }
           
            /* end */
            $this->db->select('SQL_CALC_FOUND_ROWS ' . str_replace(' , ', ' ', implode(', ', $aColumns)), false);

            $this->db->where('tbl_orders.is_deleted', 0);
            $this->db->join('tbl_order_products', 'tbl_order_products.order_id = tbl_orders.order_id', 'left');
            $this->db->group_by('DATE(tbl_orders.order_datetime)');
            $this->db->group_by('tbl_order_products.product_id');
            $this->db->order_by('tbl_orders.order_datetime', 'asc');
            $rResult = $this->db->get($sTable);
            //echo $this->db->last_query();exit;
            return $rResult->result_array();
        }else{
            return [];
        }
	}
}
?>
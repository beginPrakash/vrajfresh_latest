<?php
class Cashcredit_model extends CI_Model
{

    public function get_last_creditdetail()
    {
        $query = $this->db->select('credit_per')
            ->order_by('credit_id','desc')
            ->limit(1)
            ->get('tbl_cash_credit');
        return $query->row_array();
    }

    public function get_last_creditid()
    {
        $query = $this->db->select('credit_id')
            ->from('tbl_cash_credit')
            ->order_by('credit_id','desc')
            ->limit(1);
            $query = $this->db->get()->row();

            if (isset($query)) {
                return $query->credit_id;
    
            } else {
    
                return 0;
    
            }
    }
}
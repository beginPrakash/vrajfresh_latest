<?php
class Credittransaction_model extends CI_Model
{

    public function add_credittrans($data)
    {
        $this->db->insert('tbl_credit_transaction', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return 0;
        }
    }

    public function update_credittrans($data, $crt_id)
    {
        $this->db->where('crt_id', $category_id);
        $update = $this->db->update('tbl_credit_transaction', $data);
        return $update;
    }

    public function get_credit_sum($user_id)
    {
        $query = $this->db
        ->select_sum('cr.amount')
        ->join('tbl_orders o', 'cr.order_id=o.order_id', 'left')
        ->where('cr.user_id',$user_id)
        ->where_in('o.order_status',['Completed'])
        ->from('tbl_credit_transaction cr')
        ->get();
        return $query->row_array();
    }

    public function get_crtran_by_user_id_count($data)

    {

        $query = $this->db->select('cr.order_id,cr.amount,o.order_datetime,cr.user_id,o.order_status')
        ->from('tbl_credit_transaction cr')
            ->join('tbl_orders o', 'cr.order_id=o.order_id', 'left')
            ->where('cr.user_id', $data)
            ->order_by('cr.order_id', 'asc')
            ->get();

        return $query->result();

    }

    public function get_crtran_by_user_id($data,$limit, $offset)

    {

        $query = $this->db->select('cr.order_id,cr.amount,o.order_datetime,cr.user_id,o.order_status')
            ->from('tbl_credit_transaction cr')
            ->join('tbl_orders o', 'cr.order_id=o.order_id', 'left')
            ->where('cr.user_id', $data)

            ->order_by('cr.order_id', 'desc')
            ->limit($limit, $offset)
            ->get();

        return $query->result();

    }

}
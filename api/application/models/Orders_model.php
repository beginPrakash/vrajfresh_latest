<?php

class Orders_model extends CI_Model

{

    public function get_orders($data)

    {

        $query = $this->db->select('o.order_id,o.user_id,op.product_id,o.order_total_amount,o.order_datetime,o.order_status,o.is_active,op.product_tax_amount')

            ->join('tbl_order_products op', 'o.order_id=op.order_id')

            ->join('tbl_users u', 'o.user_id=u.user_id')

            ->where('o.is_deleted', '0')

            ->where('o.is_active', $data['is_active'])

            ->order_by('o.' . $data['sort_column'], $data['sort_order'])

            ->limit($data['limit'], $data['page_no'])

            ->get('tbl_orders o');



        return $query->result();

    }

    public function get_orders_by_search($data, $search_keyword)

    {

        $query = $this->db->select('o.order_id,o.user_id,op.product_id,o.order_total_amount,o.order_datetime,o.order_status,o.is_active,op.product_tax_amount')

            ->join('tbl_order_products op', 'o.order_id=op.order_id')

            ->join('tbl_users u', 'o.user_id=u.user_id')

            ->where('o.is_deleted', '0')

            ->where('o.is_active', $data['is_active']);



        foreach ($search_keyword as $key => $value) {

            $query = $this->db->like($key, $value);

        }

        $query = $this->db->order_by('o.' . $data['sort_column'], $data['sort_order'])

            ->limit($data['limit'], $data['page_no'])

            ->get('tbl_orders o');

        // echo $this->db->last_query();exit;

        return $query->result();



    }

    public function add_order($data, $table)

    {

        $this->db->insert($table, $data);

        if ($this->db->affected_rows() > 0) {

            return $this->db->insert_id();

        } else {

            return 0;

        }

    }

    public function update_order($data, $order_id, $table)

    {

        $this->db->where('is_deleted', '0')->where('order_id', $order_id);

        $update = $this->db->update($table, $data);

        return $update;

    }



    public function get_order_details($order_id)

    {

        $query = $this->db->select('o.*,s.state,b.state as billing_state,c.country_name as shipping_country')

            ->join('state s', 'o.shipping_state_id=s.state_id', 'left')

            ->join('state b', 'o.billing_state_id=b.state_id', 'left')

            ->join('country c', 'o.shipping_country=c.id', 'left')

            ->where('o.order_id', $order_id)

            ->get('tbl_orders o');

        return $query->result_array();

    }





    public function get_order_by_id($data)

    {

        $query = $this->db->select("o.*,s.state,b.state as billing_state,c.country_name as shipping_country, CASE 
        WHEN o.payment_methodtype = 'google_pay' THEN 'Google Pay'
        WHEN o.payment_methodtype = 'apple_pay' THEN 'Apple Pay'
        ELSE 'Stripe Card'
    END as payment_methodtype", false)

            ->join('state s', 'o.shipping_state_id=s.state_id')

            ->join('state b', 'o.billing_state_id=b.state_id', 'left')

            ->join('country c', 'o.shipping_country=c.id')

            ->where('o.order_id', $data['order_id'])

            ->where('o.is_active', '1')

            ->where('o.is_deleted', '0')

            ->get('tbl_orders o');

        return $query->result_array();

    }

	

    public function get_order_product_by_id_without_image($data)

    {

        $query = $this->db->select('op.qty,op.old_qty,op.product_name,op.total_amount,op.unit_price,v.product_variant_size,op.product_tax_amount')

            ->join('tbl_products p', 'op.product_id=p.product_id')

            ->join('tblproduct_variant v', 'op.product_variant_id =v.id', 'left')

            ->where('op.order_id', $data['order_id'])

            ->where('op.is_active', '1')

            ->where('op.is_deleted', '0')

            ->get('tbl_order_products op');

        return $query->result_array();

    }

    public function get_order_product_by_id($data)

    {

        $query = $this->db->select('op.qty,op.old_qty,op.product_name,op.total_amount,op.unit_price,pi.image,v.product_variant_size,op.product_tax_amount')

            ->join('tbl_products p', 'op.product_id=p.product_id')

            ->join('tblproduct_variant v', 'op.product_variant_id =v.id', 'left')

            ->join('tbl_product_images pi', 'p.product_id=pi.product_id', 'left')

            ->where('op.order_id', $data['order_id'])

            ->where('op.is_active', '1')

            ->where('op.is_deleted', '0')

            ->get('tbl_order_products op');

        //echo $this->db->last_query();exit;

        return $query->result_array();

    }



    public function get_orderproduct_details($data)

    {

        $query = $this->db->select('op.qty,op.product_name,op.total_amount,op.unit_price, "" AS image,v.product_variant_size,op.product_tax_amount')

            ->join('tbl_products p', 'op.product_id=p.product_id')

            ->join('tblproduct_variant v', 'op.product_variant_id =v.id', 'left')

            ->where('op.order_id', $data)

            ->where('op.is_active', '1')

            ->where('op.is_deleted', '0')

            ->get('tbl_order_products op');

        //echo $this->db->last_query();exit;

        return $query->result_array();

    }





 public function get_orderproduct_details_without_image($data)

    {

        $query = $this->db->select('op.qty,p.product_name,op.total_amount,op.unit_price,v.product_variant_size,op.product_tax_amount')

            ->join('tbl_products p', 'op.product_id=p.product_id')

            ->join('tblproduct_variant v', 'op.product_variant_id =v.id', 'left')

            ->where('op.order_id', $data)

            ->where('op.is_active', '1')

            ->where('op.is_deleted', '0')

            ->get('tbl_order_products op');

        //echo $this->db->last_query();exit;

        return $query->result_array();

    }



    public function get_users_data($order_id)

    {

        $query = $this->db->select('u.user_name,u.email')

            ->join('tbl_users u', 'o.user_id=u.user_id')

            ->where('o.order_id', $order_id)

            ->where('o.is_active', '1')

            ->where('o.is_deleted', '0')

            ->get('tbl_orders o');

        return $query->result();

    }

    public function delete_order($id, $table)

    {

        $this->db->where('order_id', $id);

        $this->db->delete($table);

        if ($this->db->affected_rows() > 0) {

            return true;

        } else {

            return false;

        }

    }

    public function get_order_by_user_id($data)

    {

        $query = $this->db->select("order_id,order_total_amount,order_datetime,order_status,user_id,
         CASE 
        WHEN payment_methodtype = 'google_pay' THEN 'Google Pay'
        WHEN payment_methodtype = 'apple_pay' THEN 'Apple Pay'
        ELSE 'Stripe Card'
    END as payment_method", false)

            ->where('user_id', $data)

            ->where('is_active', '1')

            ->where('is_deleted', '0')

            ->order_by('order_id', 'desc')

            ->get('tbl_orders');

            // echo $this->db->last_query();exit;

        return $query->result();

    }

    public function get_transaction_data($order_id)

    {

        $query = $this->db->select('t.transaction_amount,t.payment_intent_id,t.stripe_raw_response,t.transaction_datetime,t.payment_intent_status')

            ->join('tbl_transactions t', 'o.order_id=t.order_id')

            ->where('t.order_id', $order_id)

            ->where('o.is_active', '1')

            ->where('o.is_deleted', '0')

            ->get('tbl_orders o');

        return $query->result();

    }



    public function get_stored_card_details($user_id, $data)

    {

        $query = $this->db->where('is_deleted', '0')->where('user_id', $user_id);

        $update = $this->db->update('tbl_users', $data);

        // echo $this->db->last_query();exit;

        return $update;

    }



    public function get_user_id($order_id)

    {

        $query = $this->db->select('user_id')

            // ->where('is_active', '1')

            // ->where('is_deleted', '0')

            ->where('order_id', $order_id)

            ->get('tbl_orders');

        // echo $this->db->last_query();exit;

        return $query->result_array();

    }

}
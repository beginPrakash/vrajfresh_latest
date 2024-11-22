<?php
class site_configuration_model extends CI_Model
{

    public function update_variable_values($post_data)
    {
        $site_id = $post_data['site_id'];
        foreach ($post_data['variable_value'] as $key => $value) {
            if (is_array($value)) {
                $value = implode(",", $value);
            }
            $data = array('variable_value' => $value);
            $update[] = $this->db->update('tblsite_configuration', $data);
        }
        return $update;
    }

    public function get_variable_list($id)
    {
        $this->db->select('*');
        $this->db->from('tblsite_configuration');
        $this->db->where('site_id', $id);
        $this->db->order_by('type_of_setting', "ASC");
        return $this->db->get()->result_array();
    }

    /*
     *   function : getUserActionAccess
     *   Get the user or role action and page access
     */
    public function getUserActionAccess($role_id, $user_id)
    {
        $this->db->select("tp.cms_id as cur_page_id,tp.page_name,tr.*");
        $this->db->from('tbladmin_menu tp');
        $this->db->join('tbladmin_menu_rights tr', 'tp.cms_id = tr.cms_id', 'JOIN');
        $this->db->group_start();
        $this->db->where('role_id', $role_id);
        $this->db->or_where('user_id', $user_id);
        $this->db->group_end();
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getRoleId($site_id, $user_id)
    {
        if ($site_id == '0') {
            $site_id = '1';
        }
        $this->db->select("ur.role_id");
        $this->db->from('tblsitewise_user_role ur');
        $this->db->where('site_id', $site_id);
        $this->db->where('user_id', $user_id);
        $res_arr = $this->db->get()->row_array();
        if (count($res_arr) > 0) {
            return $res_arr['role_id'];
        } else {
            return 0;
        }
    }

}
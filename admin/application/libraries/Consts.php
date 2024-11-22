<?php
class Consts
{
    private $CI;
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->setConstants();
    }
    private function setConstants()
    {
        $this->CI->db->select('*');
        $this->CI->db->from('tbl_configurations');
        $query = $this->CI->db->get();
        foreach ($query->result_array() as $row) {
            define((string) $row['configuration_key'], $row['configuration_value']);
        }
        return;
    }
}

?>
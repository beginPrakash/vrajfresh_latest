<?php
/* RETURN ACTIVE MENU NAME FOR PAGE TOP NAVIGATION */
if (!function_exists('getActiveMenuName')) {
	function getActiveMenuName()
	{
		$ci =& get_instance();
		$ci->load->database();

		$controller_name = $ci->router->fetch_class();
		$action_name = $ci->router->fetch_method();
		//echo $controller_name.":".$action_name;

		$sql = "SELECT active_menu_name FROM tbldefinetopmenu WHERE controller_name ='$controller_name' AND action_name= '$action_name'";

		$query = $ci->db->query($sql);
		$data = $query->result_array();


		if (isset($data[0]['active_menu_name'])) {
			return $data[0]['active_menu_name'];
		} else {
			return 'dashboard';
		}
	}
}

if (!function_exists('getTopMenuItems')) {
	function getTopMenuItems($parent_menu_name)
	{
		$ci =& get_instance();
		$ci->load->database();

		$controller_name = $ci->router->fetch_class();
		$action_name = $ci->router->fetch_method();
		$sql = "SELECT * FROM tbltopmenu WHERE parent_menu_name ='$parent_menu_name'";
		$query = $ci->db->query($sql);
		$data = $query->result_array();
		return $data;
	}
}
<?php

if (!function_exists('sendSMS')) {
    function sendSMS($to, $body)
    {
		return true;
		if(MODE=="DEV")
		{
			return true;
		}
		if($to!='')
		{
			$id = "AC7d6f6f9b2c442e6e50c7929ba3cd05dd";
			$token = "197a07dc872735f1d26ed9f72b832b60";
			$from = '+14178052045';
			
			$COUNTRY = "IND";
			if($COUNTRY=="US")
			{
				$pre_fix = "1"; //US: 1 IND: 91
				$pre_fix_char_count = "1"; //US: 1 IND: 2
			}
			else
			{
				$pre_fix = "91"; //US: 1 IND: 91
				$pre_fix_char_count = "2"; //US: 1 IND: 2
			}
			//CHECK AND ADD COUNTRY CODE
			if(substr($to,0,1)!="+")
			{
				if(substr($to,0,$pre_fix_char_count)!=$pre_fix)
				{
					$to = "+".$pre_fix.$to;
				}
				else
				{
					$to = "+".$to;
				}
			}
			$url = "https://api.twilio.com/2010-04-01/Accounts/$id/Messages.json";
			$data = array(
				'From' => $from,
				'To' => $to,
				'Body' => $body,
			);
			$post = http_build_query($data);
			$x = curl_init($url);
			curl_setopt($x, CURLOPT_POST, true);
			curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($x, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($x, CURLOPT_USERPWD, "$id:$token");
			curl_setopt($x, CURLOPT_POSTFIELDS, $post);
			$y = curl_exec($x);
			curl_close($x);
			//var_dump($post);
			//var_dump($y);
		}
    }
}
if (!function_exists('getStateName')) {
    function getStateName($id)
    {
        $ci =& get_instance();
        $query = $ci->db->select('s.*')->where('s.geo_id', $id)->get('state s');
        $ArrState = $query->result();

        $state_name = '';
        if (count($ArrState) > 0) {
            foreach ($ArrState as $row) {
                if ($row['state_id'] == $id)
                    $state_name = $row['state'];
            }
        }
        return $state_name;
    }
}

/*if ( ! function_exists('getProductColorVariants'))
{
    function getProductColorVariants($product_id) 
    {
        $ci =&get_instance();
        $ArrColor = array();
        $ArrProductSelectedVariants = array();
        $tempArrProductSelectedVariants = $ci->product_variant_model->getProductVariants($product_id);
        foreach($tempArrProductSelectedVariants as $arr)
        {
            $ArrProductSelectedVariants[$arr['product_variant_name']] = $arr['product_variant'];
        }
        if (array_key_exists("Color",$ArrProductSelectedVariants))
        {
            $ArrColor = explode("|",$ArrProductSelectedVariants['Color']);
        }
        return $ArrColor;
    }
}
if ( ! function_exists('getProductSizeVariants'))
{
    function getProductSizeVariants($product_id) 
    {
        $ci =&get_instance();
        $ArrSize = array();
        $ArrProductSelectedVariants = array();
        $tempArrProductSelectedVariants = $ci->product_variant_model->getProductVariants($product_id);
        foreach($tempArrProductSelectedVariants as $arr)
        {
            $ArrProductSelectedVariants[$arr['product_variant_name']] = $arr['product_variant'];
        }
        if (array_key_exists("Size",$ArrProductSelectedVariants))
        {
            $ArrSize = explode("|",$ArrProductSelectedVariants['Size']);
        }
        return $ArrSize;
    }
}*/
if (!function_exists('cleanSlug')) {
    function cleanSlug($string)
    {
        $string = str_replace(' ', '-', strtolower(trim($string)));
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }
}
if (!function_exists('getProductColorVariants')) {
    function getProductColorVariants($product_id)
    {
        $ci =& get_instance();
        $ArrColor = array();
        $tempArrProductSelectedVariants = $ci->product_variant_model->getProductVariants($product_id);
        foreach ($tempArrProductSelectedVariants as $arr) {
            $ArrColor[] = $arr['product_variant_color'];
        }
        $ArrColor = array_unique($ArrColor);
        return $ArrColor;
    }
}
if (!function_exists('getProductSizeVariants')) {
    function getProductSizeVariants($product_id)
    {
        $ci =& get_instance();
        $ArrSize = array();
        $tempArrProductSelectedVariants = $ci->product_variant_model->getProductVariants($product_id);
        foreach ($tempArrProductSelectedVariants as $arr) {
            $ArrSize[] = $arr['product_variant_size'];
        }
        $ArrSize = array_unique($ArrSize);
        return $ArrSize;
    }
}
if (!function_exists('get_amount')) {
    function get_amount($number)
    {
        return number_format((float) $number, 0, '.', '');
    }
}
if (!function_exists('admin_media')) {
    function admin_media()
    {
        return ADMIN_PANEL_THEME_PATH;
    }
}
if (!function_exists('removeCommas')) {
    function removeCommas($str)
    {
        return str_replace(",", "", $str);
    }
}
/* CHANGE DATE FORMAT */
if (!function_exists('changeDateFormat')) {
    function changeDateFormat($date)
    {
        return date(DATE_FORMAT, strtotime($date));
    }
}
if (!function_exists('getMonthYear')) {
    function getMonthYear($date)
    {
        return date("M-Y", strtotime($date));
    }
}
/* RETURN IS ACTIVE BUTTON IN LIST PAGE */
if (!function_exists('getIsactiveButtonForList')) {
    function getIsactiveButtonForList($is_active, $primary_key_value, $table_name, $primary_key_name)
    {
        $is_active = ($is_active == '0') ? '<a href="javascript:void(0)" onclick=\'updateIsActiveValue(' . $primary_key_value . ',"' . $table_name . '","' . $primary_key_name . '")\'class="update_status_i' . $primary_key_value . '"><small class="label label-warning">No</small></a>' : '<a href="javascript:void(0)" onclick=\'updateIsActiveValue(' . $primary_key_value . ',"' . $table_name . '","' . $primary_key_name . '")\' class="update_status_i' . $primary_key_value . '"><small class="label label-info">Yes</small></a>';
        return $is_active;
    }
}
/* RETURN IS ACTIVE BUTTON IN LIST PAGE */
if (!function_exists('getActionButtonForList')) {
    function getActionButtonForList($primary_key_value, $module_name, $ArrButton = array("V", "E", "D"))
    {
        $action = '<div class="btn-group">';
        if (in_array("V", $ArrButton)) {
            $action .= '<a href="javascript:void(0)" class="btn btn-default btn-sm" data-toggle="modal" data-target="#recordDetailsPopUp" onclick="' . $module_name . '_detail(' . $primary_key_value . ')"><i class="fa fa-eye"></i></a>';
        }
        if (in_array("D", $ArrButton)) {
            $action .= '<a rel="' . base_url() . $module_name . '-delete/' . $primary_key_value . '" class="deleteRecord btn btn-default btn-sm"><i class="fa fa-trash-o"></i></a>';
        }
        if (in_array("E", $ArrButton)) {
            $action .= '<a href="' . base_url() . $module_name . '-update/' . $primary_key_value . '" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>';
        }
        $action .= '</div>';
        return $action;
    }
}

/* IS User Login*/
if (!function_exists('IsUserLogin')) // FOR ADMIN USER
{
    function IsUserLogin()
    {
        $ci =& get_instance();
        if (isset($ci->session->userdata['is_logged_in'])) {
            return true;
        } else {
            return false;
        }

    }
}



/* IS User Login*/
if (!function_exists('IsCustomerLogin')) // FOR Customer USER
{
    function IsCustomerLogin()
    {
        $ci =& get_instance();
        if (isset($ci->session->userdata[$ci->session->userdata['session_pre_fix'] . 'logged_in'])) {
            return true;
        } else {
            return false;
        }

    }
}

if (!function_exists('get_current_admin_id')) {
    function get_current_admin_id()
    {
        $ci =& get_instance();
        $user_id = 0;
        if (isset($ci->session->userdata['is_logged_in'])) {
            $user_id = $ci->session->userdata['admin_id'];
        }
        return $user_id;
    }
}
if (!function_exists('get_admin_detail')) {
    function get_admin_detail()
    {

        $ArrData = array();
        $user_id = get_current_admin_id();
        $ci =& get_instance();
        $ci->db->select('*');
        $ci->db->from('tbl_users');
        $ci->db->where('user_id', $user_id);
        $query = $ci->db->get();
        if ($query->num_rows() > 0) {
            $ArrData = $query->result_array()[0];
        }
        return $ArrData;
    }
}
if (!function_exists('get_user_type_id')) {
    function get_user_type_id()
    {
        $user_role_id = 0;
        $user_id = get_current_admin_id();
        $ci =& get_instance();
        $ci->db->select('user_role_id');
        $ci->db->from('tbl_users');
        $ci->db->where('user_id', $user_id);
        $query = $ci->db->get();
        if ($query->num_rows() > 0) {
            $ArrData = $query->row_array();
            $user_role_id = $ArrData['user_role_id'];
        }
        return $user_role_id;
    }
}
if (!function_exists('country_list_data')) {
    function country_list_data() // do not delete this function as it is used for getting category list
    {
        $ci =& get_instance();
        $ci->db->select("*");
        $ci->db->from('country');
        $query = $ci->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }
}


if (!function_exists('getOrderStatus')) {
    function getOrderStatus()
    {
        $ArrOrderStatus = array();
        $ci =& get_instance();
        $ci->db->select("*");
        $ci->db->from('tbl_order_status');
        $query = $ci->db->get();
        if ($query->num_rows() > 0) {
            $ArrStatus = $query->result_array();
            $ArrOrderStatus[''] = "--Order Status--";
            if (count($ArrStatus) > 0) {
                foreach ($ArrStatus as $row) {
                    $ArrOrderStatus[$row['order_status']] = $row['order_status'];
                }
            }
        }
        return $ArrOrderStatus;
    }
}

if (!function_exists('get_session_value')) {
    function get_session_value($var)
    {
        //echo '1:- '.$var;

        if (isset($_SESSION['session_pre_fix'])) {
            //echo '2:- '.$_SESSION[$_SESSION['session_pre_fix'].$var];

            if (isset($_SESSION[$_SESSION['session_pre_fix'] . $var]) && $_SESSION[$_SESSION['session_pre_fix'] . $var] != '') {
                $id = trim($_SESSION[$_SESSION['session_pre_fix'] . $var]);
                return $id;
            } else {
                return '';
            }
        } else {
            return '';
        }
    }
}
if (!function_exists('set_session_value')) {
    function set_session_value($var, $value)
    {
        if (isset($_SESSION['session_pre_fix'])) {
            $_SESSION[$_SESSION['session_pre_fix'] . $var] = $value;
        }
    }
}

if (!function_exists('get_all_usertype')) {
    function get_all_usertype()
    {
        $CI =& get_instance();
        $CI->db->select("*");
        $CI->db->from('tbl_user_roles');
        $CI->db->where('is_active', '1');
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }

    }
}

if (!function_exists('get_all_usertype_except')) {
    function get_all_usertype_except()
    {
        $CI =& get_instance();
        $CI->db->select("*");
        $CI->db->from('tbl_user_roles');
        $CI->db->where('is_active', '1');
        $CI->db->where('user_role_id !=', 4);
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }

    }
}

if (!function_exists('getUserNameByID')) {
    function getUserNameByID($user_id)
    {
        $CI =& get_instance();
        $CI->db->select("name,last_name,user_name");
        $CI->db->from('tbl_users');
        $CI->db->where('user_id', $user_id);
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            $ArrData = $query->row_array();
            if ($ArrData['name'] != "" && $ArrData['last_name'] != "") {
                $name = ucfirst($ArrData['name']) . ' ' . ucfirst($ArrData['last_name']);
            } elseif ($ArrData['name'] != "" && $ArrData['last_name'] == "") {
                $name = ucfirst($ArrData['name']);
            } else {
                $name = ucfirst($ArrData['user_name']);
            }
            return $name;
        }

    }
}

if (!function_exists('get_country')) {
    function get_country()
    {
        $CI =& get_instance();
        $CI->db->select("*");
        $CI->db->from('country');
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }
}

if (!function_exists('get_state')) {
    function get_state($id = '', $geo_id = '')
    {
        $CI =& get_instance();
        $CI->db->select("state.*");
        $CI->db->join('country', 'country.country_code = state.geo_id', 'LEFT');
        if ($id != '')
            $CI->db->where('id', $id);
        if ($geo_id != '')
            $CI->db->where('geo_id', $geo_id);
        $CI->db->from('state');
        $query = $CI->db->get();
        //echo $CI->db->last_query();exit;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }
}
if (!function_exists('get_india_state')) {
    function get_india_state()
    {
        $CI =& get_instance();
        $CI->db->select("*");
        $CI->db->from('state');
        $CI->db->where('geo_id', 'IN');
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }
}



if (!function_exists('get_country_new')) {
    function get_country_new()
    {
        $CI =& get_instance();
        $CI->db->select("*");
        $CI->db->order_by('country_name', 'asc');
        $CI->db->from('country');
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }
}

if (!function_exists('get_country_by_id')) {
    function get_country_by_id($id)
    {
        $CI =& get_instance();
        $CI->db->select("*");
        $CI->db->where('id', $id);
        $CI->db->from('country');
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }
}

if (!function_exists('get_state_by_country_code')) {
    function get_state_by_country_code($country_code)
    {
        $CI =& get_instance();
        $CI->db->select("*");
        $CI->db->from('state');
        $CI->db->where('geo_id', $country_code);
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }
}


if (!function_exists('get_country_list_by_order')) {
    function get_country_list_by_order($order_by = 'asc')
    {
        $ci =& get_instance();
        return $ci->db->select("*")->from('country')->order_by("country_name", $order_by)->get()->result_array();
    }
}

#GET TIMEZONE FROM MASTER TABLE
if (!function_exists('get_time_zone_by_order')) {
    function get_time_zone_by_order($order_by = 'asc')
    {
        $ci =& get_instance();
        return $ci->db->select("*")->from('tbltime_zone')->order_by("time_zone", $order_by)->get()->result_array();
    }
}


/* GET WEBSITE PLATFORM */
if (!function_exists('get_website_platform')) {
    function get_website_platform($url)
    {
        return '';
        if (!preg_match('/www/', $url)) {
            $url = str_replace("http://", "http://www.", $url, $count);
        }

        $input = trim($url, '/');

        if (!preg_match('#^http(s)?://#', $input)) {
            $input = 'http://' . $input;
        }

        $urlParts = parse_url($input);

        $domain = preg_replace('/^www\./', '', $urlParts['host']);
        $result = dns_get_record($domain, DNS_A + DNS_NS);

        $platform = '';
        if (count($result) > 0) {
            foreach ($result as $key => $value) {

                if ($value['type'] == 'A') {

                    $ARecord = $value['ip'];
                    $array_ARecord[] = $value['ip'];
                    if ($ARecord == '23.227.38.32') {
                        $platform = 8; //'Shopify';
                    }
                }

                if ($value['type'] == 'NS') {

                    $ns_record[] = $value['target'];

                }
            }

            $ns_records = implode(',', $ns_record);

            $data = explode(',', $ns_records);
            $ns_records = $data[0];
            $fram = explode('.', $ns_records);

            $expodfram = $fram[1];

            if ($expodfram == 'volusion') {
                $platform = 7; //'Volusion';
            }

            if ($expodfram == 'bigcommerce') {
                $platform = 9; //'Bigcommerce';
            }
        }
        return $platform;

        /* if(empty($ns_record[0])){
           $ns_record[0]='';
         }
         if(empty($ns_record[1])){
           $ns_record[1]='';
         }
         if(empty($ns_record[2])){
           $ns_record[2]='';
         }
         if(empty($ns_record[3])){
           $ns_record[3]='';
         }
         if(empty($array_ARecord[0])){
           $array_ARecord[0]='';
         }
         if(empty($array_ARecord[1])){
           $array_ARecord[1]='';
         }
         return  array('ARecord' => $ARecord,'ARecord2' => $array_ARecord[1],'ns_record1' => $ns_record[0],'ns_record2' => $ns_record[1],'ns_record3' => $ns_record[2],'ns_record4' => $ns_record[3],'url'=>$url,'platform' =>$platform);*/
    }
}

if (!function_exists('getCartNetAmount')) {
    function getCartNetAmount($cart_id)
    {
        $net_total = 0;
        $ci =& get_instance();
        $amountArr = $ci->db->select("net_total")->from('tblcart')->where('cart_id', $cart_id)->get()->row_array();

        $net_total = number_format($amountArr['net_total'], 0, '.', '');


        return $net_total;
    }
}


if (!function_exists('limitTextWords')) {
    function limitTextWords($content = false, $limit = false, $stripTags = false, $ellipsis = false)
    {
        if ($content && $limit) {
            $content = ($stripTags ? strip_tags($content) : $content);
            $ellipsis = ($ellipsis ? "..." : $ellipsis);
            $content = mb_strimwidth($content, 0, $limit, $ellipsis);
        }
        return $content;
    }
}
if (!function_exists('getRemoteFilesize')) {
    function getRemoteFilesize($url, $formatSize = true, $useHead = true)
    {
        $ch = curl_init($url);
        curl_setopt_array(
            $ch,
            array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_NOBODY => 1,
            )
        );
        if (false !== $useHead) {
            curl_setopt($ch, CURLOPT_NOBODY, 1);
        }
        curl_exec($ch);
        // content-length of download (in bytes), read from Content-Length: field
        $clen = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
        curl_close($ch);
        // cannot retrieve file size, return "-1"
        if (!$clen) {
            return -1;
        }
        if (!$formatSize) {
            return $clen; // return size in bytes
        }
        $size = $clen;
        switch ($clen) {
            case $clen < 1024:
                $size = $clen . ' B';
                break;
            case $clen < 1048576:
                $size = round($clen / 1024, 2) . ' KiB';
                break;
            case $clen < 1073741824:
                $size = round($clen / 1048576, 2) . ' MiB';
                break;
            case $clen < 1099511627776:
                $size = round($clen / 1073741824, 2) . ' GiB';
                break;
        }
        return $size; // return formatted size
    }
}
if (!function_exists('form_token')) {
    function form_token($from_name)
    {
        $hash_token = md5(uniqid(rand(), TRUE));
        $session_arr = array('form_token_session_' . $from_name => $hash_token);
        $ci =& get_instance();
        $ci->session->set_userdata($session_arr);
        return $hash_token;
    }
}

if (!function_exists('check_form_token')) {
    function check_form_token($token, $from_name)
    {
        $ci =& get_instance();
        $form_token_session = $ci->session->userdata('form_token_session_' . $from_name);
        if ($token != '' && $form_token_session != '') {
            if (trim($token) == trim($form_token_session)) {
                form_token($from_name);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }


    }
}

if (!function_exists('set_url')) {
    function set_url($url)
    {

        $input = trim(strtolower($url));
        if (!preg_match('#^http(s)?://#', $input)) {
            $input = 'http://' . $input;
        }
        return $input;
    }
}

if (!function_exists('remove_htt_www_from_url')) {
    function remove_htt_www_from_url($url)
    {
        if (!preg_match('/www/', $url)) {
            $url = str_replace("http://", "http://www.", $url, $count);
        }
        $input = trim($url, '/');

        if (!preg_match('#^http(s)?://#', $input)) {
            $input = 'http://' . $input;
        }

        $urlParts = parse_url($input);
        return preg_replace('/^www\./', '', $urlParts['host']);
    }
}

if (!function_exists('email_to_name')) {
    function email_to_name($email)
    {
        $parts = explode("@", $email);
        if (isset($parts[0]) && $parts[0] != "") {
            $name = $parts[0];
        } else {
            $name = 'visitor';
        }
        return $name;
    }
}


if (!function_exists('get_all_country')) {
    function get_all_country()
    {
        $ci =& get_instance();
        $ci->db->select('id,country_name,country_code');
        $ci->db->from('country');
        $ci->db->order_by('country_name', 'asc');
        $query = $ci->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }

    }
}

if (!function_exists('get_country_by_id')) {
    function get_country_by_id($id)
    {
        $ci =& get_instance();
        $ci->db->select('id,country_name,country_code');
        $ci->db->from('country');
        $ci->db->where('id', $id);
        $query = $ci->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return array();
        }

    }
}


function get_time_zone($country, $region)
{
    $timezone = '';
    switch ($country) {
        case "US":
            switch ($region) {
                case "AL":
                    $timezone = "America/Chicago";
                    break;
                case "AK":
                    $timezone = "America/Anchorage";
                    break;
                case "AZ":
                    $timezone = "America/Phoenix";
                    break;
                case "AR":
                    $timezone = "America/Chicago";
                    break;
                case "CA":
                    $timezone = "America/Los_Angeles";
                    break;
                case "CO":
                    $timezone = "America/Denver";
                    break;
                case "CT":
                    $timezone = "America/New_York";
                    break;
                case "DE":
                    $timezone = "America/New_York";
                    break;
                case "DC":
                    $timezone = "America/New_York";
                    break;
                case "FL":
                    $timezone = "America/New_York";
                    break;
                case "GA":
                    $timezone = "America/New_York";
                    break;
                case "HI":
                    $timezone = "Pacific/Honolulu";
                    break;
                case "ID":
                    $timezone = "America/Denver";
                    break;
                case "IL":
                    $timezone = "America/Chicago";
                    break;
                case "IN":
                    $timezone = "America/Indiana/Indianapolis";
                    break;
                case "IA":
                    $timezone = "America/Chicago";
                    break;
                case "KS":
                    $timezone = "America/Chicago";
                    break;
                case "KY":
                    $timezone = "America/New_York";
                    break;
                case "LA":
                    $timezone = "America/Chicago";
                    break;
                case "ME":
                    $timezone = "America/New_York";
                    break;
                case "MD":
                    $timezone = "America/New_York";
                    break;
                case "MA":
                    $timezone = "America/New_York";
                    break;
                case "MI":
                    $timezone = "America/New_York";
                    break;
                case "MN":
                    $timezone = "America/Chicago";
                    break;
                case "MS":
                    $timezone = "America/Chicago";
                    break;
                case "MO":
                    $timezone = "America/Chicago";
                    break;
                case "MT":
                    $timezone = "America/Denver";
                    break;
                case "NE":
                    $timezone = "America/Chicago";
                    break;
                case "NV":
                    $timezone = "America/Los_Angeles";
                    break;
                case "NH":
                    $timezone = "America/New_York";
                    break;
                case "NJ":
                    $timezone = "America/New_York";
                    break;
                case "NM":
                    $timezone = "America/Denver";
                    break;
                case "NY":
                    $timezone = "America/New_York";
                    break;
                case "NC":
                    $timezone = "America/New_York";
                    break;
                case "ND":
                    $timezone = "America/Chicago";
                    break;
                case "OH":
                    $timezone = "America/New_York";
                    break;
                case "OK":
                    $timezone = "America/Chicago";
                    break;
                case "OR":
                    $timezone = "America/Los_Angeles";
                    break;
                case "PA":
                    $timezone = "America/New_York";
                    break;
                case "RI":
                    $timezone = "America/New_York";
                    break;
                case "SC":
                    $timezone = "America/New_York";
                    break;
                case "SD":
                    $timezone = "America/Chicago";
                    break;
                case "TN":
                    $timezone = "America/Chicago";
                    break;
                case "TX":
                    $timezone = "America/Chicago";
                    break;
                case "UT":
                    $timezone = "America/Denver";
                    break;
                case "VT":
                    $timezone = "America/New_York";
                    break;
                case "VA":
                    $timezone = "America/New_York";
                    break;
                case "WA":
                    $timezone = "America/Los_Angeles";
                    break;
                case "WV":
                    $timezone = "America/New_York";
                    break;
                case "WI":
                    $timezone = "America/Chicago";
                    break;
                case "WY":
                    $timezone = "America/Denver";
                    break;
                default:
                    $timezone = "America/New_York";
                    break;
            }
            break;
        case "CA":
            switch ($region) {
                case "AB":
                    $timezone = "America/Edmonton";
                    break;
                case "BC":
                    $timezone = "America/Vancouver";
                    break;
                case "MB":
                    $timezone = "America/Winnipeg";
                    break;
                case "NB":
                    $timezone = "America/Halifax";
                    break;
                case "NL":
                    $timezone = "America/St_Johns";
                    break;
                case "NT":
                    $timezone = "America/Yellowknife";
                    break;
                case "NS":
                    $timezone = "America/Halifax";
                    break;
                case "NU":
                    $timezone = "America/Rankin_Inlet";
                    break;
                case "ON":
                    $timezone = "America/Toronto";
                    break;
                case "PE":
                    $timezone = "America/Halifax";
                    break;
                case "QC":
                    $timezone = "America/Montreal";
                    break;
                case "SK":
                    $timezone = "America/Regina";
                    break;
                case "YT":
                    $timezone = "America/Whitehorse";
                    break;
                default:
                    $timezone = "America/Winnipeg";
                    break;
            }
            break;
        case "AU":
            switch ($region) {
                case "01":
                    $timezone = "Australia/Sydney";
                    break;
                case "02":
                    $timezone = "Australia/Sydney";
                    break;
                case "03":
                    $timezone = "Australia/Darwin";
                    break;
                case "04":
                    $timezone = "Australia/Brisbane";
                    break;
                case "05":
                    $timezone = "Australia/Adelaide";
                    break;
                case "06":
                    $timezone = "Australia/Hobart";
                    break;
                case "07":
                    $timezone = "Australia/Melbourne";
                    break;
                case "08":
                    $timezone = "Australia/Perth";
                    break;
                default:
                    $timezone = "Australia/Sydney";
                    break;
            }
            break;
        case "AS":
            $timezone = "Pacific/Pago_Pago";
            break;
        case "CI":
            $timezone = "Africa/Abidjan";
            break;
        case "GH":
            $timezone = "Africa/Accra";
            break;
        case "DZ":
            $timezone = "Africa/Algiers";
            break;
        case "ER":
            $timezone = "Africa/Asmara";
            break;
        case "ML":
            $timezone = "Africa/Bamako";
            break;
        case "CF":
            $timezone = "Africa/Bangui";
            break;
        case "GM":
            $timezone = "Africa/Banjul";
            break;
        case "GW":
            $timezone = "Africa/Bissau";
            break;
        case "CG":
            $timezone = "Africa/Brazzaville";
            break;
        case "BI":
            $timezone = "Africa/Bujumbura";
            break;
        case "EG":
            $timezone = "Africa/Cairo";
            break;
        case "MA":
            $timezone = "Africa/Casablanca";
            break;
        case "GN":
            $timezone = "Africa/Conakry";
            break;
        case "SN":
            $timezone = "Africa/Dakar";
            break;
        case "DJ":
            $timezone = "Africa/Djibouti";
            break;
        case "SL":
            $timezone = "Africa/Freetown";
            break;
        case "BW":
            $timezone = "Africa/Gaborone";
            break;
        case "ZW":
            $timezone = "Africa/Harare";
            break;
        case "ZA":
            $timezone = "Africa/Johannesburg";
            break;
        case "UG":
            $timezone = "Africa/Kampala";
            break;
        case "SD":
            $timezone = "Africa/Khartoum";
            break;
        case "SS":
            $timezone = "Africa/Juba";
            break;
        case "RW":
            $timezone = "Africa/Kigali";
            break;
        case "NG":
            $timezone = "Africa/Lagos";
            break;
        case "GA":
            $timezone = "Africa/Libreville";
            break;
        case "TG":
            $timezone = "Africa/Lome";
            break;
        case "AO":
            $timezone = "Africa/Luanda";
            break;
        case "ZM":
            $timezone = "Africa/Lusaka";
            break;
        case "GQ":
            $timezone = "Africa/Malabo";
            break;
        case "MZ":
            $timezone = "Africa/Maputo";
            break;
        case "LS":
            $timezone = "Africa/Maseru";
            break;
        case "SZ":
            $timezone = "Africa/Mbabane";
            break;
        case "SO":
            $timezone = "Africa/Mogadishu";
            break;
        case "LR":
            $timezone = "Africa/Monrovia";
            break;
        case "KE":
            $timezone = "Africa/Nairobi";
            break;
        case "TD":
            $timezone = "Africa/Ndjamena";
            break;
        case "NE":
            $timezone = "Africa/Niamey";
            break;
        case "MR":
            $timezone = "Africa/Nouakchott";
            break;
        case "BF":
            $timezone = "Africa/Ouagadougou";
            break;
        case "ST":
            $timezone = "Africa/Sao_Tome";
            break;
        case "LY":
            $timezone = "Africa/Tripoli";
            break;
        case "TN":
            $timezone = "Africa/Tunis";
            break;
        case "AI":
            $timezone = "America/Anguilla";
            break;
        case "AG":
            $timezone = "America/Antigua";
            break;
        case "AW":
            $timezone = "America/Aruba";
            break;
        case "BB":
            $timezone = "America/Barbados";
            break;
        case "BZ":
            $timezone = "America/Belize";
            break;
        case "CO":
            $timezone = "America/Bogota";
            break;
        case "VE":
            $timezone = "America/Caracas";
            break;
        case "KY":
            $timezone = "America/Cayman";
            break;
        case "CR":
            $timezone = "America/Costa_Rica";
            break;
        case "DM":
            $timezone = "America/Dominica";
            break;
        case "SV":
            $timezone = "America/El_Salvador";
            break;
        case "GD":
            $timezone = "America/Grenada";
            break;
        case "FR":
            $timezone = "Europe/Paris";
            break;
        case "GP":
            $timezone = "America/Guadeloupe";
            break;
        case "GT":
            $timezone = "America/Guatemala";
            break;
        case "GY":
            $timezone = "America/Guyana";
            break;
        case "CU":
            $timezone = "America/Havana";
            break;
        case "JM":
            $timezone = "America/Jamaica";
            break;
        case "BO":
            $timezone = "America/La_Paz";
            break;
        case "PE":
            $timezone = "America/Lima";
            break;
        case "NI":
            $timezone = "America/Managua";
            break;
        case "MQ":
            $timezone = "America/Martinique";
            break;
        case "UY":
            $timezone = "America/Montevideo";
            break;
        case "MS":
            $timezone = "America/Montserrat";
            break;
        case "BS":
            $timezone = "America/Nassau";
            break;
        case "PA":
            $timezone = "America/Panama";
            break;
        case "SR":
            $timezone = "America/Paramaribo";
            break;
        case "PR":
            $timezone = "America/Puerto_Rico";
            break;
        case "KN":
            $timezone = "America/St_Kitts";
            break;
        case "LC":
            $timezone = "America/St_Lucia";
            break;
        case "VC":
            $timezone = "America/St_Vincent";
            break;
        case "HN":
            $timezone = "America/Tegucigalpa";
            break;
        case "YE":
            $timezone = "Asia/Aden";
            break;
        case "JO":
            $timezone = "Asia/Amman";
            break;
        case "TM":
            $timezone = "Asia/Ashgabat";
            break;
        case "IQ":
            $timezone = "Asia/Baghdad";
            break;
        case "BH":
            $timezone = "Asia/Bahrain";
            break;
        case "AZ":
            $timezone = "Asia/Baku";
            break;
        case "TH":
            $timezone = "Asia/Bangkok";
            break;
        case "LB":
            $timezone = "Asia/Beirut";
            break;
        case "KG":
            $timezone = "Asia/Bishkek";
            break;
        case "BN":
            $timezone = "Asia/Brunei";
            break;
        case "IN":
            $timezone = "Asia/Kolkata";
            break;
        case "LK":
            $timezone = "Asia/Colombo";
            break;
        case "BD":
            $timezone = "Asia/Dhaka";
            break;
        case "AE":
            $timezone = "Asia/Dubai";
            break;
        case "TJ":
            $timezone = "Asia/Dushanbe";
            break;
        case "HK":
            $timezone = "Asia/Hong_Kong";
            break;
        case "TR":
            $timezone = "Asia/Istanbul";
            break;
        case "IL":
            $timezone = "Asia/Jerusalem";
            break;
        case "AF":
            $timezone = "Asia/Kabul";
            break;
        case "PK":
            $timezone = "Asia/Karachi";
            break;
        case "NP":
            $timezone = "Asia/Kathmandu";
            break;
        case "KW":
            $timezone = "Asia/Kuwait";
            break;
        case "MO":
            $timezone = "Asia/Macau";
            break;
        case "PH":
            $timezone = "Asia/Manila";
            break;
        case "OM":
            $timezone = "Asia/Muscat";
            break;
        case "CY":
            $timezone = "Asia/Nicosia";
            break;
        case "KP":
            $timezone = "Asia/Pyongyang";
            break;
        case "QA":
            $timezone = "Asia/Qatar";
            break;
        case "MM":
            $timezone = "Asia/Rangoon";
            break;
        case "SA":
            $timezone = "Asia/Riyadh";
            break;
        case "KR":
            $timezone = "Asia/Seoul";
            break;
        case "SG":
            $timezone = "Asia/Singapore";
            break;
        case "TW":
            $timezone = "Asia/Taipei";
            break;
        case "GE":
            $timezone = "Asia/Tbilisi";
            break;
        case "BT":
            $timezone = "Asia/Thimphu";
            break;
        case "JP":
            $timezone = "Asia/Tokyo";
            break;
        case "LA":
            $timezone = "Asia/Vientiane";
            break;
        case "AM":
            $timezone = "Asia/Yerevan";
            break;
        case "BM":
            $timezone = "Atlantic/Bermuda";
            break;
        case "CV":
            $timezone = "Atlantic/Cape_Verde";
            break;
        case "FO":
            $timezone = "Atlantic/Faroe";
            break;
        case "IS":
            $timezone = "Atlantic/Reykjavik";
            break;
        case "GS":
            $timezone = "Atlantic/South_Georgia";
            break;
        case "SH":
            $timezone = "Atlantic/St_Helena";
            break;
        case "CL":
            $timezone = "America/Santiago";
            break;
        case "NL":
            $timezone = "Europe/Amsterdam";
            break;
        case "AD":
            $timezone = "Europe/Andorra";
            break;
        case "GR":
            $timezone = "Europe/Athens";
            break;
        case "YU":
            $timezone = "Europe/Belgrade";
            break;
        case "DE":
            $timezone = "Europe/Berlin";
            break;
        case "SK":
            $timezone = "Europe/Bratislava";
            break;
        case "BE":
            $timezone = "Europe/Brussels";
            break;
        case "RO":
            $timezone = "Europe/Bucharest";
            break;
        case "HU":
            $timezone = "Europe/Budapest";
            break;
        case "DK":
            $timezone = "Europe/Copenhagen";
            break;
        case "IE":
            $timezone = "Europe/Dublin";
            break;
        case "GI":
            $timezone = "Europe/Gibraltar";
            break;
        case "FI":
            $timezone = "Europe/Helsinki";
            break;
        case "SI":
            $timezone = "Europe/Ljubljana";
            break;
        case "GB":
            $timezone = "Europe/London";
            break;
        case "LU":
            $timezone = "Europe/Luxembourg";
            break;
        case "MT":
            $timezone = "Europe/Malta";
            break;
        case "BY":
            $timezone = "Europe/Minsk";
            break;
        case "MC":
            $timezone = "Europe/Monaco";
            break;
        case "NO":
            $timezone = "Europe/Oslo";
            break;
        case "CZ":
            $timezone = "Europe/Prague";
            break;
        case "LV":
            $timezone = "Europe/Riga";
            break;
        case "IT":
            $timezone = "Europe/Rome";
            break;
        case "SM":
            $timezone = "Europe/San_Marino";
            break;
        case "BA":
            $timezone = "Europe/Sarajevo";
            break;
        case "MK":
            $timezone = "Europe/Skopje";
            break;
        case "BG":
            $timezone = "Europe/Sofia";
            break;
        case "SE":
            $timezone = "Europe/Stockholm";
            break;
        case "EE":
            $timezone = "Europe/Tallinn";
            break;
        case "AL":
            $timezone = "Europe/Tirane";
            break;
        case "LI":
            $timezone = "Europe/Vaduz";
            break;
        case "VA":
            $timezone = "Europe/Vatican";
            break;
        case "AT":
            $timezone = "Europe/Vienna";
            break;
        case "LT":
            $timezone = "Europe/Vilnius";
            break;
        case "PL":
            $timezone = "Europe/Warsaw";
            break;
        case "HR":
            $timezone = "Europe/Zagreb";
            break;
        case "IR":
            $timezone = "Asia/Tehran";
            break;
        case "MG":
            $timezone = "Indian/Antananarivo";
            break;
        case "CX":
            $timezone = "Indian/Christmas";
            break;
        case "CC":
            $timezone = "Indian/Cocos";
            break;
        case "KM":
            $timezone = "Indian/Comoro";
            break;
        case "MV":
            $timezone = "Indian/Maldives";
            break;
        case "MU":
            $timezone = "Indian/Mauritius";
            break;
        case "YT":
            $timezone = "Indian/Mayotte";
            break;
        case "RE":
            $timezone = "Indian/Reunion";
            break;
        case "FJ":
            $timezone = "Pacific/Fiji";
            break;
        case "TV":
            $timezone = "Pacific/Funafuti";
            break;
        case "GU":
            $timezone = "Pacific/Guam";
            break;
        case "NR":
            $timezone = "Pacific/Nauru";
            break;
        case "NU":
            $timezone = "Pacific/Niue";
            break;
        case "NF":
            $timezone = "Pacific/Norfolk";
            break;
        case "PW":
            $timezone = "Pacific/Palau";
            break;
        case "PN":
            $timezone = "Pacific/Pitcairn";
            break;
        case "CK":
            $timezone = "Pacific/Rarotonga";
            break;
        case "WS":
            $timezone = "Pacific/Pago_Pago";
            break;
        case "KI":
            $timezone = "Pacific/Tarawa";
            break;
        case "TO":
            $timezone = "Pacific/Tongatapu";
            break;
        case "WF":
            $timezone = "Pacific/Wallis";
            break;
        case "TZ":
            $timezone = "Africa/Dar_es_Salaam";
            break;
        case "VN":
            $timezone = "Asia/Phnom_Penh";
            break;
        case "KH":
            $timezone = "Asia/Phnom_Penh";
            break;
        case "CM":
            $timezone = "Africa/Lagos";
            break;
        case "DO":
            $timezone = "America/Santo_Domingo";
            break;
        case "ET":
            $timezone = "Africa/Addis_Ababa";
            break;
        case "FX":
            $timezone = "Europe/Paris";
            break;
        case "HT":
            $timezone = "America/Port-au-Prince";
            break;
        case "CH":
            $timezone = "Europe/Zurich";
            break;
        case "AN":
            $timezone = "America/Curacao";
            break;
        case "BJ":
            $timezone = "Africa/Porto-Novo";
            break;
        case "EH":
            $timezone = "Africa/El_Aaiun";
            break;
        case "FK":
            $timezone = "Atlantic/Stanley";
            break;
        case "GF":
            $timezone = "America/Cayenne";
            break;
        case "IO":
            $timezone = "Indian/Chagos";
            break;
        case "MD":
            $timezone = "Europe/Chisinau";
            break;
        case "MP":
            $timezone = "Pacific/Saipan";
            break;
        case "MW":
            $timezone = "Africa/Blantyre";
            break;
        case "NA":
            $timezone = "Africa/Windhoek";
            break;
        case "NC":
            $timezone = "Pacific/Noumea";
            break;
        case "PG":
            $timezone = "Pacific/Port_Moresby";
            break;
        case "PM":
            $timezone = "America/Miquelon";
            break;
        case "PS":
            $timezone = "Asia/Gaza";
            break;
        case "PY":
            $timezone = "America/Asuncion";
            break;
        case "SB":
            $timezone = "Pacific/Guadalcanal";
            break;
        case "SC":
            $timezone = "Indian/Mahe";
            break;
        case "SJ":
            $timezone = "Arctic/Longyearbyen";
            break;
        case "SY":
            $timezone = "Asia/Damascus";
            break;
        case "TC":
            $timezone = "America/Grand_Turk";
            break;
        case "TF":
            $timezone = "Indian/Kerguelen";
            break;
        case "TK":
            $timezone = "Pacific/Fakaofo";
            break;
        case "TT":
            $timezone = "America/Port_of_Spain";
            break;
        case "VG":
            $timezone = "America/Tortola";
            break;
        case "VI":
            $timezone = "America/St_Thomas";
            break;
        case "VU":
            $timezone = "Pacific/Efate";
            break;
        case "RS":
            $timezone = "Europe/Belgrade";
            break;
        case "ME":
            $timezone = "Europe/Podgorica";
            break;
        case "AX":
            $timezone = "Europe/Mariehamn";
            break;
        case "GG":
            $timezone = "Europe/Guernsey";
            break;
        case "IM":
            $timezone = "Europe/Isle_of_Man";
            break;
        case "JE":
            $timezone = "Europe/Jersey";
            break;
        case "BL":
            $timezone = "America/St_Barthelemy";
            break;
        case "MF":
            $timezone = "America/Marigot";
            break;
        case "MH":
            $timezone = "Pacific/Kwajalein";
            break;
        case "UM":
            $timezone = "Pacific/Wake";
            break;
        case "AR":
            switch ($region) {
                case "01":
                    $timezone = "America/Argentina/Buenos_Aires";
                    break;
                case "02":
                    $timezone = "America/Argentina/Catamarca";
                    break;
                case "03":
                    $timezone = "America/Argentina/Tucuman";
                    break;
                case "04":
                    $timezone = "America/Argentina/Rio_Gallegos";
                    break;
                case "05":
                    $timezone = "America/Argentina/Cordoba";
                    break;
                case "06":
                    $timezone = "America/Argentina/Tucuman";
                    break;
                case "07":
                    $timezone = "America/Argentina/Buenos_Aires";
                    break;
                case "08":
                    $timezone = "America/Argentina/Buenos_Aires";
                    break;
                case "09":
                    $timezone = "America/Argentina/Tucuman";
                    break;
                case "10":
                    $timezone = "America/Argentina/Jujuy";
                    break;
                case "11":
                    $timezone = "America/Argentina/San_Luis";
                    break;
                case "12":
                    $timezone = "America/Argentina/La_Rioja";
                    break;
                case "13":
                    $timezone = "America/Argentina/Mendoza";
                    break;
                case "14":
                    $timezone = "America/Argentina/Buenos_Aires";
                    break;
                case "15":
                    $timezone = "America/Argentina/San_Luis";
                    break;
                case "16":
                    $timezone = "America/Argentina/Buenos_Aires";
                    break;
                case "17":
                    $timezone = "America/Argentina/Salta";
                    break;
                case "18":
                    $timezone = "America/Argentina/San_Juan";
                    break;
                case "19":
                    $timezone = "America/Argentina/San_Luis";
                    break;
                case "20":
                    $timezone = "America/Argentina/Rio_Gallegos";
                    break;
                case "21":
                    $timezone = "America/Argentina/Buenos_Aires";
                    break;
                case "22":
                    $timezone = "America/Argentina/Catamarca";
                    break;
                case "23":
                    $timezone = "America/Argentina/Ushuaia";
                    break;
                case "24":
                    $timezone = "America/Argentina/Tucuman";
                    break;
                default:
                    $timezone = "America/Argentina/Tucuman";
                    break;
            }
            break;
        case "BR":
            switch ($region) {
                case "01":
                    $timezone = "America/Rio_Branco";
                    break;
                case "02":
                    $timezone = "America/Maceio";
                    break;
                case "03":
                    $timezone = "America/Sao_Paulo";
                    break;
                case "04":
                    $timezone = "America/Manaus";
                    break;
                case "05":
                    $timezone = "America/Bahia";
                    break;
                case "06":
                    $timezone = "America/Fortaleza";
                    break;
                case "07":
                    $timezone = "America/Sao_Paulo";
                    break;
                case "08":
                    $timezone = "America/Sao_Paulo";
                    break;
                case "11":
                    $timezone = "America/Campo_Grande";
                    break;
                case "13":
                    $timezone = "America/Belem";
                    break;
                case "14":
                    $timezone = "America/Cuiaba";
                    break;
                case "15":
                    $timezone = "America/Sao_Paulo";
                    break;
                case "16":
                    $timezone = "America/Belem";
                    break;
                case "17":
                    $timezone = "America/Recife";
                    break;
                case "18":
                    $timezone = "America/Sao_Paulo";
                    break;
                case "20":
                    $timezone = "America/Fortaleza";
                    break;
                case "21":
                    $timezone = "America/Sao_Paulo";
                    break;
                case "22":
                    $timezone = "America/Recife";
                    break;
                case "23":
                    $timezone = "America/Sao_Paulo";
                    break;
                case "24":
                    $timezone = "America/Porto_Velho";
                    break;
                case "25":
                    $timezone = "America/Boa_Vista";
                    break;
                case "26":
                    $timezone = "America/Sao_Paulo";
                    break;
                case "27":
                    $timezone = "America/Sao_Paulo";
                    break;
                case "28":
                    $timezone = "America/Maceio";
                    break;
                case "29":
                    $timezone = "America/Sao_Paulo";
                    break;
                case "30":
                    $timezone = "America/Recife";
                    break;
                case "31":
                    $timezone = "America/Araguaina";
                    break;
                default:
                    $timezone = "America/Recife";
                    break;
            }
            break;
        case "CD":
            switch ($region) {
                case "01":
                    $timezone = "Africa/Kinshasa";
                    break;
                case "02":
                    $timezone = "Africa/Kinshasa";
                    break;
                case "03":
                    $timezone = "Africa/Kinshasa";
                    break;
                case "04":
                    $timezone = "Africa/Lubumbashi";
                    break;
                case "05":
                    $timezone = "Africa/Lubumbashi";
                    break;
                case "06":
                    $timezone = "Africa/Kinshasa";
                    break;
                case "07":
                    $timezone = "Africa/Lubumbashi";
                    break;
                case "08":
                    $timezone = "Africa/Kinshasa";
                    break;
                case "09":
                    $timezone = "Africa/Lubumbashi";
                    break;
                case "10":
                    $timezone = "Africa/Lubumbashi";
                    break;
                case "11":
                    $timezone = "Africa/Lubumbashi";
                    break;
                case "12":
                    $timezone = "Africa/Lubumbashi";
                    break;
                default:
                    $timezone = "Africa/Lubumbashi";
                    break;
            }
            break;
        case "CN":
            switch ($region) {
                case "01":
                    $timezone = "Asia/Shanghai";
                    break;
                case "02":
                    $timezone = "Asia/Shanghai";
                    break;
                case "03":
                    $timezone = "Asia/Shanghai";
                    break;
                case "04":
                    $timezone = "Asia/Shanghai";
                    break;
                case "05":
                    $timezone = "Asia/Harbin";
                    break;
                case "06":
                    $timezone = "Asia/Chongqing";
                    break;
                case "07":
                    $timezone = "Asia/Shanghai";
                    break;
                case "08":
                    $timezone = "Asia/Harbin";
                    break;
                case "09":
                    $timezone = "Asia/Shanghai";
                    break;
                case "10":
                    $timezone = "Asia/Shanghai";
                    break;
                case "11":
                    $timezone = "Asia/Chongqing";
                    break;
                case "12":
                    $timezone = "Asia/Shanghai";
                    break;
                case "13":
                    $timezone = "Asia/Urumqi";
                    break;
                case "14":
                    $timezone = "Asia/Chongqing";
                    break;
                case "15":
                    $timezone = "Asia/Chongqing";
                    break;
                case "16":
                    $timezone = "Asia/Chongqing";
                    break;
                case "18":
                    $timezone = "Asia/Chongqing";
                    break;
                case "19":
                    $timezone = "Asia/Harbin";
                    break;
                case "20":
                    $timezone = "Asia/Harbin";
                    break;
                case "21":
                    $timezone = "Asia/Chongqing";
                    break;
                case "22":
                    $timezone = "Asia/Harbin";
                    break;
                case "23":
                    $timezone = "Asia/Shanghai";
                    break;
                case "24":
                    $timezone = "Asia/Chongqing";
                    break;
                case "25":
                    $timezone = "Asia/Shanghai";
                    break;
                case "26":
                    $timezone = "Asia/Chongqing";
                    break;
                case "28":
                    $timezone = "Asia/Shanghai";
                    break;
                case "29":
                    $timezone = "Asia/Chongqing";
                    break;
                case "30":
                    $timezone = "Asia/Chongqing";
                    break;
                case "31":
                    $timezone = "Asia/Chongqing";
                    break;
                case "32":
                    $timezone = "Asia/Chongqing";
                    break;
                case "33":
                    $timezone = "Asia/Chongqing";
                    break;
                default:
                    $timezone = "Asia/Chongqing";
                    break;
            }
            break;
        case "EC":
            switch ($region) {
                case "01":
                    $timezone = "Pacific/Galapagos";
                    break;
                case "02":
                    $timezone = "America/Guayaquil";
                    break;
                case "03":
                    $timezone = "America/Guayaquil";
                    break;
                case "04":
                    $timezone = "America/Guayaquil";
                    break;
                case "05":
                    $timezone = "America/Guayaquil";
                    break;
                case "06":
                    $timezone = "America/Guayaquil";
                    break;
                case "07":
                    $timezone = "America/Guayaquil";
                    break;
                case "08":
                    $timezone = "America/Guayaquil";
                    break;
                case "09":
                    $timezone = "America/Guayaquil";
                    break;
                case "10":
                    $timezone = "America/Guayaquil";
                    break;
                case "11":
                    $timezone = "America/Guayaquil";
                    break;
                case "12":
                    $timezone = "America/Guayaquil";
                    break;
                case "13":
                    $timezone = "America/Guayaquil";
                    break;
                case "14":
                    $timezone = "America/Guayaquil";
                    break;
                case "15":
                    $timezone = "America/Guayaquil";
                    break;
                case "17":
                    $timezone = "America/Guayaquil";
                    break;
                case "18":
                    $timezone = "America/Guayaquil";
                    break;
                case "19":
                    $timezone = "America/Guayaquil";
                    break;
                case "20":
                    $timezone = "America/Guayaquil";
                    break;
                case "22":
                    $timezone = "America/Guayaquil";
                    break;
                case "24":
                    $timezone = "America/Guayaquil";
                    break;
                default:
                    $timezone = "America/Guayaquil";
                    break;
            }
            break;
        case "ES":
            switch ($region) {
                case "07":
                    $timezone = "Europe/Madrid";
                    break;
                case "27":
                    $timezone = "Europe/Madrid";
                    break;
                case "29":
                    $timezone = "Europe/Madrid";
                    break;
                case "31":
                    $timezone = "Europe/Madrid";
                    break;
                case "32":
                    $timezone = "Europe/Madrid";
                    break;
                case "34":
                    $timezone = "Europe/Madrid";
                    break;
                case "39":
                    $timezone = "Europe/Madrid";
                    break;
                case "51":
                    $timezone = "Africa/Ceuta";
                    break;
                case "52":
                    $timezone = "Europe/Madrid";
                    break;
                case "53":
                    $timezone = "Atlantic/Canary";
                    break;
                case "54":
                    $timezone = "Europe/Madrid";
                    break;
                case "55":
                    $timezone = "Europe/Madrid";
                    break;
                case "56":
                    $timezone = "Europe/Madrid";
                    break;
                case "57":
                    $timezone = "Europe/Madrid";
                    break;
                case "58":
                    $timezone = "Europe/Madrid";
                    break;
                case "59":
                    $timezone = "Europe/Madrid";
                    break;
                case "60":
                    $timezone = "Europe/Madrid";
                    break;
                default:
                    $timezone = "Europe/Madrid";
                    break;
            }
            break;
        case "GL":
            switch ($region) {
                case "01":
                    $timezone = "America/Thule";
                    break;
                case "02":
                    $timezone = "America/Godthab";
                    break;
                case "03":
                    $timezone = "America/Godthab";
                    break;
            }
            break;
        case "ID":
            switch ($region) {
                case "01":
                    $timezone = "Asia/Pontianak";
                    break;
                case "02":
                    $timezone = "Asia/Makassar";
                    break;
                case "03":
                    $timezone = "Asia/Jakarta";
                    break;
                case "04":
                    $timezone = "Asia/Jakarta";
                    break;
                case "05":
                    $timezone = "Asia/Jakarta";
                    break;
                case "06":
                    $timezone = "Asia/Jakarta";
                    break;
                case "07":
                    $timezone = "Asia/Jakarta";
                    break;
                case "08":
                    $timezone = "Asia/Jakarta";
                    break;
                case "09":
                    $timezone = "Asia/Jayapura";
                    break;
                case "10":
                    $timezone = "Asia/Jakarta";
                    break;
                case "11":
                    $timezone = "Asia/Pontianak";
                    break;
                case "12":
                    $timezone = "Asia/Makassar";
                    break;
                case "13":
                    $timezone = "Asia/Makassar";
                    break;
                case "14":
                    $timezone = "Asia/Makassar";
                    break;
                case "15":
                    $timezone = "Asia/Jakarta";
                    break;
                case "16":
                    $timezone = "Asia/Makassar";
                    break;
                case "17":
                    $timezone = "Asia/Makassar";
                    break;
                case "18":
                    $timezone = "Asia/Makassar";
                    break;
                case "19":
                    $timezone = "Asia/Pontianak";
                    break;
                case "20":
                    $timezone = "Asia/Makassar";
                    break;
                case "21":
                    $timezone = "Asia/Makassar";
                    break;
                case "22":
                    $timezone = "Asia/Makassar";
                    break;
                case "23":
                    $timezone = "Asia/Makassar";
                    break;
                case "24":
                    $timezone = "Asia/Jakarta";
                    break;
                case "25":
                    $timezone = "Asia/Pontianak";
                    break;
                case "26":
                    $timezone = "Asia/Pontianak";
                    break;
                case "28":
                    $timezone = "Asia/Jayapura";
                    break;
                case "29":
                    $timezone = "Asia/Makassar";
                    break;
                case "30":
                    $timezone = "Asia/Jakarta";
                    break;
                case "31":
                    $timezone = "Asia/Makassar";
                    break;
                case "32":
                    $timezone = "Asia/Jakarta";
                    break;
                case "33":
                    $timezone = "Asia/Jakarta";
                    break;
                case "34":
                    $timezone = "Asia/Makassar";
                    break;
                case "35":
                    $timezone = "Asia/Pontianak";
                    break;
                case "36":
                    $timezone = "Asia/Jayapura";
                    break;
                case "37":
                    $timezone = "Asia/Pontianak";
                    break;
                case "38":
                    $timezone = "Asia/Makassar";
                    break;
                case "39":
                    $timezone = "Asia/Jayapura";
                    break;
                case "40":
                    $timezone = "Asia/Pontianak";
                    break;
                case "41":
                    $timezone = "Asia/Makassar";
                    break;
                default:
                    $timezone = "Asia/Jakarta";
                    break;
            }
            break;
        case "KZ":
            switch ($region) {
                case "01":
                    $timezone = "Asia/Almaty";
                    break;
                case "02":
                    $timezone = "Asia/Almaty";
                    break;
                case "03":
                    $timezone = "Asia/Qyzylorda";
                    break;
                case "04":
                    $timezone = "Asia/Aqtobe";
                    break;
                case "05":
                    $timezone = "Asia/Qyzylorda";
                    break;
                case "06":
                    $timezone = "Asia/Aqtau";
                    break;
                case "07":
                    $timezone = "Asia/Oral";
                    break;
                case "08":
                    $timezone = "Asia/Qyzylorda";
                    break;
                case "09":
                    $timezone = "Asia/Aqtau";
                    break;
                case "10":
                    $timezone = "Asia/Qyzylorda";
                    break;
                case "11":
                    $timezone = "Asia/Almaty";
                    break;
                case "12":
                    $timezone = "Asia/Qyzylorda";
                    break;
                case "13":
                    $timezone = "Asia/Aqtobe";
                    break;
                case "14":
                    $timezone = "Asia/Qyzylorda";
                    break;
                case "15":
                    $timezone = "Asia/Almaty";
                    break;
                case "16":
                    $timezone = "Asia/Aqtobe";
                    break;
                case "17":
                    $timezone = "Asia/Almaty";
                    break;
                default:
                    $timezone = "Asia/Oral";
                    break;
            }
            break;
        case "MN":
            switch ($region) {
                case "06":
                    $timezone = "Asia/Choibalsan";
                    break;
                case "11":
                    $timezone = "Asia/Ulaanbaatar";
                    break;
                case "17":
                    $timezone = "Asia/Choibalsan";
                    break;
                case "19":
                    $timezone = "Asia/Hovd";
                    break;
                case "20":
                    $timezone = "Asia/Ulaanbaatar";
                    break;
                case "21":
                    $timezone = "Asia/Ulaanbaatar";
                    break;
                case "25":
                    $timezone = "Asia/Ulaanbaatar";
                    break;
            }
            break;
        case "MX":
            switch ($region) {
                case "01":
                    $timezone = "America/Mexico_City";
                    break;
                case "02":
                    $timezone = "America/Tijuana";
                    break;
                case "03":
                    $timezone = "America/Hermosillo";
                    break;
                case "04":
                    $timezone = "America/Merida";
                    break;
                case "05":
                    $timezone = "America/Mexico_City";
                    break;
                case "06":
                    $timezone = "America/Chihuahua";
                    break;
                case "07":
                    $timezone = "America/Monterrey";
                    break;
                case "08":
                    $timezone = "America/Mexico_City";
                    break;
                case "09":
                    $timezone = "America/Mexico_City";
                    break;
                case "10":
                    $timezone = "America/Mazatlan";
                    break;
                case "11":
                    $timezone = "America/Mexico_City";
                    break;
                case "12":
                    $timezone = "America/Mexico_City";
                    break;
                case "13":
                    $timezone = "America/Mexico_City";
                    break;
                case "14":
                    $timezone = "America/Mazatlan";
                    break;
                case "15":
                    $timezone = "America/Chihuahua";
                    break;
                case "16":
                    $timezone = "America/Mexico_City";
                    break;
                case "17":
                    $timezone = "America/Mexico_City";
                    break;
                case "18":
                    $timezone = "America/Mazatlan";
                    break;
                case "19":
                    $timezone = "America/Monterrey";
                    break;
                case "20":
                    $timezone = "America/Mexico_City";
                    break;
                case "21":
                    $timezone = "America/Mexico_City";
                    break;
                case "22":
                    $timezone = "America/Mexico_City";
                    break;
                case "23":
                    $timezone = "America/Cancun";
                    break;
                case "24":
                    $timezone = "America/Mexico_City";
                    break;
                case "25":
                    $timezone = "America/Mazatlan";
                    break;
                case "26":
                    $timezone = "America/Hermosillo";
                    break;
                case "27":
                    $timezone = "America/Merida";
                    break;
                case "28":
                    $timezone = "America/Monterrey";
                    break;
                case "29":
                    $timezone = "America/Mexico_City";
                    break;
                case "30":
                    $timezone = "America/Mexico_City";
                    break;
                case "31":
                    $timezone = "America/Merida";
                    break;
                case "32":
                    $timezone = "America/Monterrey";
                    break;
            }
            break;
        case "MY":
            switch ($region) {
                case "01":
                    $timezone = "Asia/Kuala_Lumpur";
                    break;
                case "02":
                    $timezone = "Asia/Kuala_Lumpur";
                    break;
                case "03":
                    $timezone = "Asia/Kuala_Lumpur";
                    break;
                case "04":
                    $timezone = "Asia/Kuala_Lumpur";
                    break;
                case "05":
                    $timezone = "Asia/Kuala_Lumpur";
                    break;
                case "06":
                    $timezone = "Asia/Kuala_Lumpur";
                    break;
                case "07":
                    $timezone = "Asia/Kuala_Lumpur";
                    break;
                case "08":
                    $timezone = "Asia/Kuala_Lumpur";
                    break;
                case "09":
                    $timezone = "Asia/Kuala_Lumpur";
                    break;
                case "11":
                    $timezone = "Asia/Kuching";
                    break;
                case "12":
                    $timezone = "Asia/Kuala_Lumpur";
                    break;
                case "13":
                    $timezone = "Asia/Kuala_Lumpur";
                    break;
                case "14":
                    $timezone = "Asia/Kuala_Lumpur";
                    break;
                case "15":
                    $timezone = "Asia/Kuching";
                    break;
                case "16":
                    $timezone = "Asia/Kuching";
                    break;
            }
            break;
        case "NZ":
            switch ($region) {
                case "85":
                    $timezone = "Pacific/Auckland";
                    break;
                case "E7":
                    $timezone = "Pacific/Auckland";
                    break;
                case "E8":
                    $timezone = "Pacific/Auckland";
                    break;
                case "E9":
                    $timezone = "Pacific/Auckland";
                    break;
                case "F1":
                    $timezone = "Pacific/Auckland";
                    break;
                case "F2":
                    $timezone = "Pacific/Auckland";
                    break;
                case "F3":
                    $timezone = "Pacific/Auckland";
                    break;
                case "F4":
                    $timezone = "Pacific/Auckland";
                    break;
                case "F5":
                    $timezone = "Pacific/Auckland";
                    break;
                case "F6":
                    $timezone = "Pacific/Auckland";
                    break;
                case "F7":
                    $timezone = "Pacific/Chatham";
                    break;
                case "F8":
                    $timezone = "Pacific/Auckland";
                    break;
                case "F9":
                    $timezone = "Pacific/Auckland";
                    break;
                case "G1":
                    $timezone = "Pacific/Auckland";
                    break;
                case "G2":
                    $timezone = "Pacific/Auckland";
                    break;
                case "G3":
                    $timezone = "Pacific/Auckland";
                    break;
            }
            break;
        case "PT":
            switch ($region) {
                case "02":
                    $timezone = "Europe/Lisbon";
                    break;
                case "03":
                    $timezone = "Europe/Lisbon";
                    break;
                case "04":
                    $timezone = "Europe/Lisbon";
                    break;
                case "05":
                    $timezone = "Europe/Lisbon";
                    break;
                case "06":
                    $timezone = "Europe/Lisbon";
                    break;
                case "07":
                    $timezone = "Europe/Lisbon";
                    break;
                case "08":
                    $timezone = "Europe/Lisbon";
                    break;
                case "09":
                    $timezone = "Europe/Lisbon";
                    break;
                case "10":
                    $timezone = "Atlantic/Madeira";
                    break;
                case "11":
                    $timezone = "Europe/Lisbon";
                    break;
                case "13":
                    $timezone = "Europe/Lisbon";
                    break;
                case "14":
                    $timezone = "Europe/Lisbon";
                    break;
                case "16":
                    $timezone = "Europe/Lisbon";
                    break;
                case "17":
                    $timezone = "Europe/Lisbon";
                    break;
                case "18":
                    $timezone = "Europe/Lisbon";
                    break;
                case "19":
                    $timezone = "Europe/Lisbon";
                    break;
                case "20":
                    $timezone = "Europe/Lisbon";
                    break;
                case "21":
                    $timezone = "Europe/Lisbon";
                    break;
                case "22":
                    $timezone = "Europe/Lisbon";
                    break;
                case "23":
                    $timezone = "Atlantic/Azores";
                    break;
            }
            break;
        case "RU":
            switch ($region) {
                case "01":
                    $timezone = "Europe/Volgograd";
                    break;
                case "02":
                    $timezone = "Asia/Irkutsk";
                    break;
                case "03":
                    $timezone = "Asia/Novokuznetsk";
                    break;
                case "04":
                    $timezone = "Asia/Novosibirsk";
                    break;
                case "05":
                    $timezone = "Asia/Vladivostok";
                    break;
                case "06":
                    $timezone = "Europe/Moscow";
                    break;
                case "07":
                    $timezone = "Europe/Volgograd";
                    break;
                case "08":
                    $timezone = "Europe/Samara";
                    break;
                case "09":
                    $timezone = "Europe/Moscow";
                    break;
                case "10":
                    $timezone = "Europe/Moscow";
                    break;
                case "11":
                    $timezone = "Asia/Irkutsk";
                    break;
                case "12":
                    $timezone = "Europe/Volgograd";
                    break;
                case "13":
                    $timezone = "Asia/Yekaterinburg";
                    break;
                case "14":
                    $timezone = "Asia/Irkutsk";
                    break;
                case "15":
                    $timezone = "Asia/Anadyr";
                    break;
                case "16":
                    $timezone = "Europe/Samara";
                    break;
                case "17":
                    $timezone = "Europe/Volgograd";
                    break;
                case "18":
                    $timezone = "Asia/Krasnoyarsk";
                    break;
                case "20":
                    $timezone = "Asia/Irkutsk";
                    break;
                case "21":
                    $timezone = "Europe/Moscow";
                    break;
                case "22":
                    $timezone = "Europe/Volgograd";
                    break;
                case "23":
                    $timezone = "Europe/Kaliningrad";
                    break;
                case "24":
                    $timezone = "Europe/Volgograd";
                    break;
                case "25":
                    $timezone = "Europe/Moscow";
                    break;
                case "26":
                    $timezone = "Asia/Kamchatka";
                    break;
                case "27":
                    $timezone = "Europe/Volgograd";
                    break;
                case "28":
                    $timezone = "Europe/Moscow";
                    break;
                case "29":
                    $timezone = "Asia/Novokuznetsk";
                    break;
                case "30":
                    $timezone = "Asia/Vladivostok";
                    break;
                case "31":
                    $timezone = "Asia/Krasnoyarsk";
                    break;
                case "32":
                    $timezone = "Asia/Omsk";
                    break;
                case "33":
                    $timezone = "Asia/Yekaterinburg";
                    break;
                case "34":
                    $timezone = "Asia/Yekaterinburg";
                    break;
                case "35":
                    $timezone = "Asia/Yekaterinburg";
                    break;
                case "36":
                    $timezone = "Asia/Anadyr";
                    break;
                case "37":
                    $timezone = "Europe/Moscow";
                    break;
                case "38":
                    $timezone = "Europe/Volgograd";
                    break;
                case "39":
                    $timezone = "Asia/Krasnoyarsk";
                    break;
                case "40":
                    $timezone = "Asia/Yekaterinburg";
                    break;
                case "41":
                    $timezone = "Europe/Moscow";
                    break;
                case "42":
                    $timezone = "Europe/Moscow";
                    break;
                case "43":
                    $timezone = "Europe/Moscow";
                    break;
                case "44":
                    $timezone = "Asia/Magadan";
                    break;
                case "45":
                    $timezone = "Europe/Samara";
                    break;
                case "46":
                    $timezone = "Europe/Samara";
                    break;
                case "47":
                    $timezone = "Europe/Moscow";
                    break;
                case "48":
                    $timezone = "Europe/Moscow";
                    break;
                case "49":
                    $timezone = "Europe/Moscow";
                    break;
                case "50":
                    $timezone = "Asia/Yekaterinburg";
                    break;
                case "51":
                    $timezone = "Europe/Moscow";
                    break;
                case "52":
                    $timezone = "Europe/Moscow";
                    break;
                case "53":
                    $timezone = "Asia/Novosibirsk";
                    break;
                case "54":
                    $timezone = "Asia/Omsk";
                    break;
                case "55":
                    $timezone = "Europe/Samara";
                    break;
                case "56":
                    $timezone = "Europe/Moscow";
                    break;
                case "57":
                    $timezone = "Europe/Samara";
                    break;
                case "58":
                    $timezone = "Asia/Yekaterinburg";
                    break;
                case "59":
                    $timezone = "Asia/Vladivostok";
                    break;
                case "60":
                    $timezone = "Europe/Kaliningrad";
                    break;
                case "61":
                    $timezone = "Europe/Volgograd";
                    break;
                case "62":
                    $timezone = "Europe/Moscow";
                    break;
                case "63":
                    $timezone = "Asia/Yakutsk";
                    break;
                case "64":
                    $timezone = "Asia/Sakhalin";
                    break;
                case "65":
                    $timezone = "Europe/Samara";
                    break;
                case "66":
                    $timezone = "Europe/Moscow";
                    break;
                case "67":
                    $timezone = "Europe/Samara";
                    break;
                case "68":
                    $timezone = "Europe/Volgograd";
                    break;
                case "69":
                    $timezone = "Europe/Moscow";
                    break;
                case "70":
                    $timezone = "Europe/Volgograd";
                    break;
                case "71":
                    $timezone = "Asia/Yekaterinburg";
                    break;
                case "72":
                    $timezone = "Europe/Moscow";
                    break;
                case "73":
                    $timezone = "Europe/Samara";
                    break;
                case "74":
                    $timezone = "Asia/Krasnoyarsk";
                    break;
                case "75":
                    $timezone = "Asia/Novosibirsk";
                    break;
                case "76":
                    $timezone = "Europe/Moscow";
                    break;
                case "77":
                    $timezone = "Europe/Moscow";
                    break;
                case "78":
                    $timezone = "Asia/Yekaterinburg";
                    break;
                case "79":
                    $timezone = "Asia/Irkutsk";
                    break;
                case "80":
                    $timezone = "Asia/Yekaterinburg";
                    break;
                case "81":
                    $timezone = "Europe/Samara";
                    break;
                case "82":
                    $timezone = "Asia/Irkutsk";
                    break;
                case "83":
                    $timezone = "Europe/Moscow";
                    break;
                case "84":
                    $timezone = "Europe/Volgograd";
                    break;
                case "85":
                    $timezone = "Europe/Moscow";
                    break;
                case "86":
                    $timezone = "Europe/Moscow";
                    break;
                case "87":
                    $timezone = "Asia/Novosibirsk";
                    break;
                case "88":
                    $timezone = "Europe/Moscow";
                    break;
                case "89":
                    $timezone = "Asia/Vladivostok";
                    break;
                case "90":
                    $timezone = "Asia/Yekaterinburg";
                    break;
                case "91":
                    $timezone = "Asia/Krasnoyarsk";
                    break;
                case "92":
                    $timezone = "Asia/Anadyr";
                    break;
                case "93":
                    $timezone = "Asia/Irkutsk";
                    break;
            }
            break;
        case "UA":
            switch ($region) {
                case "01":
                    $timezone = "Europe/Kiev";
                    break;
                case "02":
                    $timezone = "Europe/Kiev";
                    break;
                case "03":
                    $timezone = "Europe/Uzhgorod";
                    break;
                case "04":
                    $timezone = "Europe/Zaporozhye";
                    break;
                case "05":
                    $timezone = "Europe/Zaporozhye";
                    break;
                case "06":
                    $timezone = "Europe/Uzhgorod";
                    break;
                case "07":
                    $timezone = "Europe/Zaporozhye";
                    break;
                case "08":
                    $timezone = "Europe/Simferopol";
                    break;
                case "09":
                    $timezone = "Europe/Kiev";
                    break;
                case "10":
                    $timezone = "Europe/Zaporozhye";
                    break;
                case "11":
                    $timezone = "Europe/Simferopol";
                    break;
                case "12":
                    $timezone = "Europe/Kiev";
                    break;
                case "13":
                    $timezone = "Europe/Kiev";
                    break;
                case "14":
                    $timezone = "Europe/Zaporozhye";
                    break;
                case "15":
                    $timezone = "Europe/Uzhgorod";
                    break;
                case "16":
                    $timezone = "Europe/Zaporozhye";
                    break;
                case "17":
                    $timezone = "Europe/Simferopol";
                    break;
                case "18":
                    $timezone = "Europe/Zaporozhye";
                    break;
                case "19":
                    $timezone = "Europe/Kiev";
                    break;
                case "20":
                    $timezone = "Europe/Simferopol";
                    break;
                case "21":
                    $timezone = "Europe/Kiev";
                    break;
                case "22":
                    $timezone = "Europe/Uzhgorod";
                    break;
                case "23":
                    $timezone = "Europe/Kiev";
                    break;
                case "24":
                    $timezone = "Europe/Uzhgorod";
                    break;
                case "25":
                    $timezone = "Europe/Uzhgorod";
                    break;
                case "26":
                    $timezone = "Europe/Zaporozhye";
                    break;
                case "27":
                    $timezone = "Europe/Kiev";
                    break;
            }
            break;
        case "UZ":
            switch ($region) {
                case "01":
                    $timezone = "Asia/Tashkent";
                    break;
                case "02":
                    $timezone = "Asia/Samarkand";
                    break;
                case "03":
                    $timezone = "Asia/Tashkent";
                    break;
                case "05":
                    $timezone = "Asia/Samarkand";
                    break;
                case "06":
                    $timezone = "Asia/Tashkent";
                    break;
                case "07":
                    $timezone = "Asia/Samarkand";
                    break;
                case "08":
                    $timezone = "Asia/Samarkand";
                    break;
                case "09":
                    $timezone = "Asia/Samarkand";
                    break;
                case "10":
                    $timezone = "Asia/Samarkand";
                    break;
                case "12":
                    $timezone = "Asia/Samarkand";
                    break;
                case "13":
                    $timezone = "Asia/Tashkent";
                    break;
                case "14":
                    $timezone = "Asia/Tashkent";
                    break;
            }
            break;
        case "TL":
            $timezone = "Asia/Dili";
            break;
        case "PF":
            $timezone = "Pacific/Marquesas";
            break;
        case "SX":
            $timezone = "America/Curacao";
            break;
        case "BQ":
            $timezone = "America/Curacao";
            break;
        case "CW":
            $timezone = "America/Curacao";
            break;
        case "AQ":
            $timezone = "Antarctica/South_Pole";
            break;
        case "BV":
            $timezone = "Antarctica/Syowa";
            break;
        case "FM":
            $timezone = "Pacific/Pohnpei";
            break;
    }
    return $timezone;
}




//count days between two dates
if (!function_exists('getDays')) {
    function getDays($datetime1, $datetime2)
    {
        if ($datetime1 != '' && $datetime2 != '') {
            $datetime1 = date_create(date("d-m-Y", strtotime($datetime1)));
            $datetime2 = date_create(date("d-m-Y", strtotime($datetime2)));

            $diff = date_diff($datetime1, $datetime2);
            $days = $diff->format("%a");
            if ($days == 0)
                return 1;
            else
                return $days;
        } else {
            return 0;
        }
    }
}
function GUID()
{
    if (function_exists('com_create_guid') === true) {
        return trim(com_create_guid(), '{}');
    }

    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}
function remove_special_characters($string)
{
    $new_string = str_replace(
        array(
            "'",
            '"',
            '%',
            '!',
            '`',
            '^',
            '&',
            " ' ",
            ' " ',
            ' % ',
            ' ! ',
            ' ` ',
            ' ^ ',
            ' & '
        ),
        '-',
        $string
    );
    return strtolower(trim(str_replace(' ', '', $new_string)));
}
function compressImage($source, $destination, $quality)
{

    $imgInfo = getimagesize($source);
    $mime = $imgInfo['mime'];

    // Create a new image from file 
    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($source);
            imagejpeg($image, $destination, $quality);
            break;
        case 'image/png':
            $image = imagecreatefrompng($source);
            imagepng($image, $destination, $quality);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($source);
            imagegif($image, $destination, $quality);
            break;
        /* default: 
             $image = imagecreatefromjpeg($source); 
            imagejpeg($image, $destination, $quality);*/
    }


    // Return compressed image 
    return $destination;
}

function send_mail33($email, $subject, $message)
{
    $ci = &get_instance();
    $config = array();
    $config['protocol'] = 'smtp';
	$config['smtp_host'] = SMTP_HOST;
	$config['smtp_user'] = SMTP_USER;
	$config['smtp_pass'] = SMTP_PASSWORD;
	$config['smtp_port'] = SMTP_PORT;
    $config['mailtype'] = 'html';
    $config['charset'] = 'utf-8';
    $config['wordwrap'] = true;
	// $config['smtp_crypto'] = 'ssl';
	// $mail_config['_smtp_auth'] = TRUE;

    $ci->email->initialize($config);
	$ci->email->set_newline("\r\n");
    $ci->email->set_mailtype("html");
    $ci->email->from(SMTP_USER, "Vraj Fresh Admin");
    $ci->email->to($email);
    $ci->email->subject($subject);
    $ci->email->message($message);
    if ($ci->email->send()) {
        return true;
    } else {
		$myfile = fopen("logs.txt", "w") or die("Unable to open file!");
		$txt = $ci->email->print_debugger();
		fwrite($myfile, $txt);
		fclose($myfile);
        return false;
    }
}

function send_mail_old($email, $subject, $message)
{
    $ci = &get_instance();
    
    // Include PHPMailer library
    // require 'application/libraries/phpmailer/src/PHPMailerAutoload.php';
	
	// require getcwd() .'/application/libraries/phpmailer/src/Exception.php';
	// require getcwd() .'/application/libraries/phpmailer/src/PHPMailer.php';
	// require getcwd() .'/application/libraries/phpmailer/src/SMTP.php';
    
    // use PHPMailer\PHPMailer\PHPMailer;
	// use PHPMailer\PHPMailer\Exception;

	
	// Create a new PHPMailer instance
    // $mail = new PHPMailer();
	
	// Load PHPMailer library
	$ci->load->library('phpmailer_lib');
    
	// PHPMailer object
	$mail = $ci->phpmailer_lib->load();

    // SMTP configuration
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USER;
    $mail->Password = SMTP_PASSWORD;
    $mail->SMTPSecure = 'tls'; // Use 'tls' instead of 'ssl'
    $mail->Port = SMTP_PORT;
    
    // Email content
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $message;
    
    // Set 'From' address
    $mail->setFrom(SMTP_USER, "Vraj Fresh Admin");
    
    // Add recipient
    $mail->addAddress($email);
    
    // Disable peer verification for self-signed certificates (similar to your previous code)
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
        ),
    );
    
    // Set CRLF (Carriage Return and Line Feed) to "\r\n" for Windows compatibility
    // $mail->setCRLF("\r\n");

    // Attempt to send the email
    if ($mail->send()) {
        return true;
    } else {
        // Handle errors (You can log or return false as needed)
        $error_message = $mail->ErrorInfo;
        
        // Log the error message or handle it as needed
        $myfile = fopen("logs2.txt", "w");
        fwrite($myfile, $error_message);
        fclose($myfile);
        
        return false;
    }
}

function send_mail($email, $subject, $message,$attachment='') {
	// return true;
	$ci = &get_instance();
	
	// Include PHPMailer library
	
	// Google API credentials
	$clientID = '17611857669-17auiif79mg37o0m7o5q5vkhcn9571h8.apps.googleusercontent.com';
	$clientSecret = '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';
	// $refreshToken = 'YOUR_REFRESH_TOKEN';

	// Sender's email address (Google Workspace)
	$senderEmail = 'orders@vrajfresh.com';
	$senderName = 'Vraj Fresh';

	// Recipient's email address
	$recipientEmail = $email;
	$recipientName = $email;

	// Email content
	// $subject = 'Subject of your email';
	$body = $message;

	// Load PHPMailer
	$ci->load->library('phpmailer_lib');

	$mail = $ci->phpmailer_lib->load();

	try {
		// Server settings
		$mail->isSMTP();
		$mail->Host = 'smtp.gmail.com';
		$mail->SMTPAuth = true;
		$mail->Username = $senderEmail;
		// $mail->Password = 'uwttjgpqsmbigcei'; // Your email password or app password if 2-Step Verification is enabled
		$mail->Password = 'dsgvjyolexvianvm'; // Your email password or app password if 2-Step Verification is enabled
		$mail->SMTPSecure = 'tls';
		$mail->Port = 587;
		// $mail->SMTPDebug = 4;

		// Set OAuth2 authentication
		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true,
			),
		);

		// $mail->AuthType = 'OAUTHBEARER'; //'XOAUTH2';
		$mail->oauthUserEmail = $senderEmail;
		$mail->password = 'orders5899';
		// $mail->oauthClientId = $clientID;
		// $mail->oauthClientSecret = $clientSecret;
		// $mail->oauthRefreshToken = $refreshToken;

		// Recipient
		$mail->setFrom($senderEmail, $senderName);
		$mail->addAddress($recipientEmail, $recipientName);

		// Content
		$mail->isHTML(true);
		$mail->Subject = $subject;
		$mail->Body = $body;
        if(!empty($attachment)){
            $mail->addAttachment($attachment);
        }

		// Send email
		if ($mail->send()) {
			return true;
			// echo 'Email sent successfully';
		} else {
			// Handle errors (You can log or return false as needed)
			// echo $mail->ErrorInfo;
			$myfile = fopen("logs2.txt", "w");
			fwrite($myfile, $mail->ErrorInfo);
			fclose($myfile);
			return false;
		}
		
	} catch (Exception $e) {
		// echo 'Email could not be sent. Error: ', $mail->ErrorInfo;
		$myfile = fopen("logs2.txt", "w");
		fwrite($myfile, $mail->ErrorInfo);
		fclose($myfile);
		
		return false;
	}
}

if (!function_exists('getUnreadReportCount')) {
    function getUnreadReportCount($report_type)
    {
        $ci =& get_instance();
        if($report_type == 'about_order'):
            $query = $ci->db->select("order_complain_id")->from('tbl_order_complains')->where('is_read', 1)->get();
        else:
            $query = $ci->db->select("requested_product_id")->from('tbl_requested_product')->where('is_read', 1)->get();
        endif;
        return $query->num_rows();
    }
}

if (!function_exists('getProcessingOrderCount')) {
    function getProcessingOrderCount()
    {
        $ci =& get_instance();
        $query = $ci->db->select("order_id")->from('tbl_orders')->where('order_status', 'Processing')->get();
        return $query->num_rows();
    }
}

?>

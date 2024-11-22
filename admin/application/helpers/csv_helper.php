<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
if (!function_exists('array_to_csv')) {
	function array_to_csv($ArrHeading, $ArrData, $fileName = "ExportData")
	{


		header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=\"" . $fileName . ".csv\"");
		header("Pragma: no-cache");
		//	header("Expires: 0");
		$handle = fopen('php://output', 'w');
		fputcsv($handle, $ArrHeading);
		//echo"<PRE>"; print_r($ArrData); 
		foreach ($ArrData as $data) {

			//echo"<PRE>"; print_r($data);  exit;
			fputcsv($handle, $data);
		}
		fclose($handle);
		exit;
	}
}

/**
 * Generate CSV from a query result object
 *
 * @param	object	$query		Query result object
 * @param	string	$delim		Delimiter (default: ,)
 * @param	string	$newline	Newline character (default: \n)
 * @param	string	$enclosure	Enclosure (default: ")
 * @return	string
 */
function custom_csv_from_result($query, $delim = ',', $newline = "\n", $enclosure = '"')
{
	if (!is_object($query) or !method_exists($query, 'list_fields')) {
		show_error('You must submit a valid result object');
	}

	$out = '';
	$out .= '"Sr No.",';
	// First generate the headings from the table column names
	foreach ($query->list_fields() as $name) {
		$name = str_replace("formatted_", "", $name);
		$name = str_replace("_", " ", $name);
		$name = ucwords($name);
		$out .= $enclosure . str_replace($enclosure, $enclosure . $enclosure, $name) . $enclosure . $delim;
	}

	$out = substr(rtrim($out), 0, -strlen($delim)) . $newline;

	// Next blast through the result array and build out the rows
	$lin = 1;
	while ($row = $query->unbuffered_row('array')) {
		$out .= '"' . ($lin++) . '",';
		foreach ($row as $item) {
			$out .= $enclosure . str_replace($enclosure, $enclosure . $enclosure, $item) . $enclosure . $delim;
		}
		$out = substr(rtrim($out), 0, -strlen($delim)) . $newline;
	}

	return $out;
}
?>
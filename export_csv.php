<?php
global $wpdb;

if(!isset($wpdb))
{
require_once('../../../wp-config.php');
//require_once('../../../wp-load.php');
require_once('../../../wp-includes/wp-db.php');
}
//error_reporting(0);

	 function cleanData(&$str) { 
		 $str = preg_replace("/\t/", "\\t", $str); 
		 $str = preg_replace("/\r?\n/", "\\n", $str); 
		 if(strstr($str, '"')) 
			$str = '"' . str_replace('"', '""', $str) . '"'; 
	 } 


	function correct_data($str)
	{
		$str = str_replace('"', '\"', $str);
		return $str;
	}



	$file = "S NO,Name,Email\r";

	$select_data = $wpdb->get_results("select * from tbl_news_letter")  or mysql_error();
	
	$i=1;
	foreach($select_data as $res){
	
		$file.=  "\n".$i.','.
					correct_data($res->name).','.
					correct_data($res->email).',';
		$i++;				
	}
	
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private", false);
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"report.csv\";");
			header("Content-Transfer-Encoding: binary");
        	echo $file;

exit;
?>
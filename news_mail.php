<?php
global $wpdb;

if(!isset($wpdb))
{
	require_once('../../../wp-config.php');
	//require_once('../../../wp-load.php');
	require_once('../../../wp-includes/wp-db.php');
}

$admin_email = get_option('admin_email');
$email = $_GET['email'];
$name = $_GET['name'];

$email_results = $wpdb->get_results("select * from tbl_news_letter where email='".$email."'");
$num_rows = $wpdb->num_rows;

$subject='Newsletter Subscription';
$to=$email; 

$message= "<font  style=font-family:arial; >Dear Subscriber,<br><br>
Thank you for your joining. We will keep you updated with all up-to-date news, events and other info. <br><br>Thanks again.<br><br>
</font>";

$headers[] = "MIME-Version: 1.0\n";
$headers[] ="Content-type: text/html; charset=iso-8859-1\n";
$headers[] ="From: <".$admin_email.">";

if($num_rows < 1){
	
		$pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$";
		if(!eregi($pattern, $email))
		{
				echo $msg = "<font color='#FF0000' style='font-family:arial; font-size:11px;'><strong>Email Not Valid</strong></font>"; 
			
		}else{
				$mail = wp_mail($to,$subject,$message,$headers);
				if($mail)
				{
					echo $msg = "<font color='#FF0000' style='font-family:arial;  font-size:11px;'><strong>Thanks for subscribing</strong></font>";
				}else{
					
					echo $msg = "<font color='#FF0000' style='font-family:arial;  font-size:11px;'><strong>Thanks for subscribing</strong></font>";
				}
				$wpdb->query("insert into tbl_news_letter set email='".$email."', name='".$name."', create_date=Now(), status='publish' ") or die(mysql_error());
		 }
}else{
	
	echo $msg = "<font color='#FF0000' style='font-family:arial; font-size:11px;'><strong>You are already subscribed to this newsletter!</strong></font>";
}
?>
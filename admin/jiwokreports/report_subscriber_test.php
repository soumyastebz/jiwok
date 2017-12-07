<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::>Jiwok-Report
   Programmer	::> Deepa S 
   Date			::> 27/Jan/2011
   DESCRIPTION::::>>>> Jiwok Reports section. This index page  displays the report summary of all sections  - All users, Register, Subscriber, Ex-subscriber,1 euro transactions, gift code transactions
  
*****************************************************************************/
error_reporting(1);
	include_once('includes/config_test.php');
	$admin_title = "JIWOK REPORTS";
	
	if($_REQUEST['langId'] != ""){
		$lanId = $_REQUEST['langId'];
	}
	else{
		$lanId = 1;
	}
	
	$curdate = date('Y-m-d');
    $query = "SELECT user_master.user_id as user_id,user_master.user_fname,user_master.user_lname,user_master.user_email,user_master.user_gender,FLOOR((TO_DAYS(NOW())- TO_DAYS(STR_TO_DATE( user_master.user_dob, '%d/%m/%Y' ))) / 365.25) as age,user_country,brand_master.brand_name,user_master.user_language
			FROM user_master
			LEFT JOIN brand_user ON ( user_master.user_id = brand_user.user_id )
			LEFT JOIN brand_master ON ( brand_master.brand_master_id = brand_user.brand_master_id )
			LEFT JOIN payment ON user_master.user_id = payment.payment_userid
			WHERE user_master.user_type =1
			AND user_master.user_id !=0
			AND user_master.user_id !=''
			AND user_master.user_id IS NOT NULL
			AND payment.payment_userid !=0
			AND payment.payment_userid IS NOT NULL
			AND (user_master.user_unsubscribed=0 or user_master.user_unsubscribed=1) ";
	
	
	
	if($type=='free') {		
	$query .= " AND payment.payment_status =0
				AND payment.payment_date IS NULL ";
	}
	if($type=='paid') {		
	$query .= "	AND payment.payment_expdate > '".addslashes($curdate)."'
				AND payment.payment_status =1
				AND payment.payment_date IS NOT NULL ";
	}
 
 	/*if($brand_master_id!='')
 	{
 		$query .= " AND brand_user.brand_master_id IN(".$brand_master_id.")"; 
 	}*/
    if(trim($condition)!='')
    	{ $query .= $condition; }
	$query .= " GROUP BY user_master.user_id ";
	
	if($lower_limit!='' && $maxrows!='')
	{ $query .= " limit $lower_limit,$maxrows "; }
	$result = mysql_unbuffered_query($query) or die(mysql_error());  
   
    while($row = mysql_fetch_array( $result )) {
	// Print out the contents of each row into a table
	echo "\n\n"; 
	echo $row['user_fname'];
	echo "\n\n"; 
	echo $row['user_lname'];
	echo "\n\n"; 
} 

	
		 
	
	
	?>
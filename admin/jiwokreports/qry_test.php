<?php
	//include_once('includeconfig.php');
	//include_once("includes/classes/class.report.php");
	/*@define("DB_USER", "jiwok_com_new");
	@define("DB_PASSWD", "mHXqAm1l");
	@define("DB_HOST", "localhost");
	@define("DB_NAME", "jiwok_com");*/
	@define("DB_USER", "jiwokgandi");
	@define("DB_PASSWD", "#pabram#");
	@define("DB_HOST", "localhost");
	@define("DB_NAME", "jiwok_com");
	
	mysql_pconnect(DB_HOST,DB_USER,DB_PASSWD) or die(mysql_error());
//echo "Connected to MySQL<br />";
mysql_select_db(DB_NAME) or die(mysql_error());
/*$selectQry1	=	"SELECT a.*,b.*,
					CONCAT_WS(' ',`um`.`user_fname`, `um`.`user_lname`) AS `name`, `um`.`user_email`, 
					(CASE `um`.`user_gender` WHEN 0 THEN 'MALE' ELSE 'FEMALE' END) AS `gender`, 
					`um`.`user_dob`, `um`.`user_country`, `um`.`user_language`, `um`.`user_doj`,`bu`.`brand_master_id`,`bm`.`brand_name`,
					IF(`payment_amount` IS NOT NULL,
					   IF(`payment_amount`< 9.91,
						  IF(`payment_amount` NOT IN( 7.9,9.9),'DISCOUNT','NORMAL'),'GIFTCODE'),
					   IF(`endtime` IS NULL,'REGISTER','FREE WORK')) AS `type`,
					`payment_amount`
					
					FROM (
					SELECT 
					sum(if(isnull(`payment`.`payment_date`),1,0)) AS `NULLDATA`,
					sum(if(isnull(`payment`.`payment_date`),0,1)) AS `PAIDDATA`,
					count(`payment`.`payment_userid`) AS `nousers`,
					sum(`payment`.`payment_status`) AS `paidstatus`,`payment_userid`,`payment_date`
					FROM payment
					WHERE payment.payment_userid IS NOT NULL
					GROUP BY payment.payment_userid
					ORDER BY payment.payment_date ASC
					) AS `a` 
					
					LEFT JOIN (
					SELECT program_queue.end_time AS endtime,user_id
					FROM program_queue
					WHERE user_id !=0
					AND user_id IS NOT NULL
					AND program_queue.status =11
					AND program_queue.end_time != '0000-00-00 00:00:00'
					GROUP BY user_id
					ORDER BY program_queue.end_time ASC
					) AS `b` ON a.payment_userid=b.user_id
					
					LEFT JOIN (
					SELECT * FROM (	
					   SELECT `payment_date` AS `cdate`,`payment_amount`,`payment_userid` FROM 
					   `payment` WHERE `payment_date` IS NOT NULL AND `payment_status`=1 
					   GROUP BY `payment_userid` ORDER BY `payment_date` ASC
					) AS `tmp`
					) AS `c` ON `a`.`payment_userid`=`c`.`payment_userid`
					
					LEFT JOIN `user_master` AS `um` ON `um`.`user_id`=`a`.`payment_userid`
					LEFT JOIN `brand_user` as `bu` ON `bu.`user_id` = `a`.`payment_userid`
					LEFT JOIN `brand_master` as `bm` ON `bm`.`brand_master_id` = `bu`.brand_master_id )
					LIMIT 0,30";*/
					
	/*$selectQry	=	"SELECT p.payment_date, p.payment_userid, p.payment_status
FROM payment p
JOIN program_queue pq ON p.payment_userid = pq.user_id
JOIN payment x ON x.payment_userid = p.payment_userid
JOIN user_master um ON um.user_id = p.payment_userid
LEFT JOIN brand_user bu ON bu.user_id = p.payment_userid
LEFT JOIN brand_master bm ON bm.brand_master_id = bu.brand_master_id
LEFT JOIN discount_users du ON du.user_id = p.payment_userid";*/
	
	
	$selectQry_temp1	=	"SELECT count(*) FROM `report_final`";
	$result		=	mysql_query($selectQry_temp1);
	$noOfUsers	=	mysql_fetch_row($result);
	echo $noOfUsers[0];
	$selectQry_temp		=	"SELECT * FROM `report_final` LIMIT 0,30";
/*
	$resultCount		=	mysql_query($selectQry_temp);
	$noOfUsers		=	mysql_num_rows($selectQry_temp);
*/
	

	$result1		=	mysql_query($selectQry_temp);
	


	while($row	=	mysql_fetch_array($result1,MYSQL_ASSOC)){
		$dbArray[] = $row;
	}
	
	echo "No of users ";
	
	echo "<pre>";
	print_r($dbArray);
	
	
	
?>

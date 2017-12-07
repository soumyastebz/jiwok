<?php
error_reporting(1);
	include_once('includeconfig.php');
	include_once("includes/classes/class.report.php");
	$admin_title = "JIWOK REPORTS";
	$start=	0;
	$end=	50;
	$selectQry1	=	"SELECT `payment_userid`,SUM(IF(ISNULL(`payment_date`),1,0)) AS 'NULLDATA',
						SUM(IF(ISNULL(`payment_date`),0,1)) AS 'PAIDDATA',
						COUNT(`payment_userid`) AS 'nousers',SUM(`payment_status`) AS 'paidstatus'
						FROM `payment`  WHERE 1 GROUP BY `payment_userid`  ORDER BY `payment_date`";// Find no of rows
	$selectQryTemp	=	"SELECT * FROM `jiwok_report1` WHERE 1";
	$result1		=	mysql_query($selectQryTemp);
	$noOfRows	=	mysql_num_rows($result1);

	$selectQry	=	"SELECT `payment_userid`,SUM(IF(ISNULL(`payment_date`),1,0)) AS 'NULLDATA',
						SUM(IF(ISNULL(`payment_date`),0,1)) AS 'PAIDDATA',
						COUNT(`payment_userid`) AS 'nousers',SUM(`payment_status`) AS 'paidstatus'
						FROM `payment`  WHERE 1 GROUP BY `payment_userid`  ORDER BY `payment_date` LIMIT {$start},{$end}";
	$result		=	mysql_unbuffered_query($selectQry);

	while($row	=	mysql_fetch_array($result,MYSQL_ASSOC)){
		$dbArray[]	=	$row;
	}
	echo "No of Rows Found : ".$noOfRows;
	echo "<pre>";
	print_r($dbArray);
?>

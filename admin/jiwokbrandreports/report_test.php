<?php
error_reporting(1);
	include_once('includeconfig.php');
	include_once("includes/classes/class.report.php");
	$admin_title = "JIWOK REPORTS";
	
	if($_REQUEST['langId'] != ""){
		$lanId = $_REQUEST['langId'];
	}
	else{
		$lanId = 1;
	}
	
				 
	/*
	 Instantiating the classes.
	*/
	$objGen      =	 new General();
	$objReport	 = 	 new Report($lanId);
	$objDb       =   new DbAction();
	
	$heading = "Subscriber Reports";
	
		
	/*--------------------coding for date  filter ends here ---------------------------------*/
	//echo "hi";
	$reports1 = $objReport->getReportOfSubscribersCount($user_type,$whereSql,$sort_sql,'','');
	
    ?>
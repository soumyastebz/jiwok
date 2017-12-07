<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-Report
   Programmer	::> Dileep.E 
   Date			::> 23/05/11
   
   DESCRIPTION::::>>>>
   To generate the report for the New payment.
  
*****************************************************************************/
	include_once('includeconfig.php');
	//require_once 'includes/classes/Payment/class.payment.php';
	include_once('../includes/classes/class.newpayment.php');
	include_once('../includes/classes/class.trainer.php');
	
//	error_reporting(0);

	if($_REQUEST['langId'] != ""){
		$lanId = $_REQUEST['langId'];
	}
	else{
		$lanId = 1;
	}
	//Select which tab is displayed
	if($_REQUEST['tab'] != ""){
		$tab = $_REQUEST['tab'];
	}
	else{
		$tab = 1;
	}
	
	$selected	=	'selected="selected"';
	/*
	Take all the languages to an array.
	*/
	
	$languageArray = $siteLanguagesConfig;
	reset($languageArray);
						 
	/*
	 Instantiating the classes.
	*/
	$paymentClass	=	new newPayment();
	$objGen      =	 new General();
	$objTrainer	 = 	 new Trainer($lanId);
	$objDb       =   new DbAction();
	
	$heading = "New payment Report";
	$countriesArray = $objTrainer->_getCountries();
	
	if(isset($_REQUEST['param'])){
		extractParams($_REQUEST['param']);
	}
	$param	= '';
	//for generating the month and year specified report for the members and the download
	//$today = getdate();
	$todayDetails	= getdate();
	if($_POST['year']){
	 $currentYear =  $_POST['year'];
	 $param	.=	'&year='.$_POST['year'];
	}
	else
     $currentYear = date('Y');	 
	
	if($_POST['month']){
	 $currentMonth =  $_POST['month'];
	 $param	.=	'&month='.$_POST['month'];
	}
	else
	 $currentMonth = date('m');
	 
	// Computing the date range...
	/**
	*    Computing the date range
	*/
	// If the first drop down was selected..
	if($_POST['daterange'] == 1){
		$param	.=	'&daterange='.$_POST['daterange'];
		if(isset($_POST['dropdown1'])){
			$param	.=	'&dropdown1='.$_POST['dropdown1'];
		}
		// get today's details
		//$todayDetails = getdate();
		$today = date('Y-m-d');
		
		switch($_POST['dropdown1']){
			case 'today':
				if($tab == 2 || $tab == 3)//Cancel/Refund
					$whereSql .= " AND STR_TO_DATE( PC.join_date, '%Y-%m-%d' )  = '".$today."'";				
				elseif($tab == 4)
				{
					if($page_name!= "")
					{
						$whereSql .= " AND STR_TO_DATE(UM.user_doj, '%Y-%m-%d' )  = '".$today."'";
					}
					else
					{
						$whereSql .= " AND STR_TO_DATE( ST.join_date, '%Y-%m-%d' )  = '".$today."'";
					}
				}
				elseif($tab == 5)
					$whereSql .= " AND STR_TO_DATE( PP.unsubscribed_date, '%Y-%m-%d' )  = '".$today."'";						
				else
					$whereSql .= " AND STR_TO_DATE( PS.payment_date, '%Y-%m-%d' )  = '".$today."'";		
				break;
			
			case 'yest':
				$lastDayDetails = getdate(strtotime('yesterday'));
				$yesterday		= date('Y-m-d',$lastDayDetails[0]);
				
				if($tab == 2 || $tab == 3)//Cancel/Refund
					$whereSql .= " AND STR_TO_DATE( PC.join_date, '%Y-%m-%d' ) = '".$yesterday."'";
				elseif($tab == 4)
				{
					if($page_name!= "")
					{
						$whereSql .= " AND STR_TO_DATE(UM.user_doj, '%Y-%m-%d' ) = '".$yesterday."'";
					}
					else
					{
						$whereSql .= " AND STR_TO_DATE( ST.join_date, '%Y-%m-%d' ) = '".$yesterday."'";	}
				}
				elseif($tab == 5)
					$whereSql .= " AND STR_TO_DATE( PP.unsubscribed_date, '%Y-%m-%d' ) = '".$yesterday."'";								
				else
					$whereSql .= " AND STR_TO_DATE( PS.payment_date, '%Y-%m-%d' ) = '".$yesterday."'";
				
				break;
				
			case 'last7':
				$sevenDayBeforeDetails = getdate(strtotime('-7 days'));
				$requiredDate		   = date('Y-m-d',$sevenDayBeforeDetails[0]);
				if($tab == 2 || $tab == 3)//Cancel/Refund
					$whereSql .= " AND STR_TO_DATE( PC.join_date, '%Y-%m-%d' ) BETWEEN '".$requiredDate."' AND '".$today."'";
				elseif($tab == 4)
				if($page_name!= "")
					{
						$whereSql .= " AND STR_TO_DATE(UM.user_doj, '%Y-%m-%d' ) BETWEEN '".$requiredDate."' AND '".$today."'";	
					}
					else
					{
						$whereSql .= " AND STR_TO_DATE(ST.join_date, '%Y-%m-%d' ) BETWEEN '".$requiredDate."' AND '".$today."'";	
					}
		
				elseif($tab == 5)
					$whereSql .= " AND STR_TO_DATE( PP.unsubscribed_date, '%Y-%m-%d' ) BETWEEN '".$requiredDate."' AND '".$today."'";		
				else	
					$whereSql .= " AND STR_TO_DATE( PS.payment_date, '%Y-%m-%d' ) BETWEEN '".$requiredDate."' AND '".$today."'";
				
				break;
			
			case 'thismonth':	
				$thisMonth = $todayDetails['mon'];
				$thisYear  = $todayDetails['year'];
				if($tab == 2 || $tab == 3)//Cancel/Refund
					$whereSql .= " AND MONTH(STR_TO_DATE( PC.join_date, '%Y-%m-%d' )) = '".$thisMonth."'  AND YEAR(STR_TO_DATE( PC.join_date, '%Y-%m-%d' )) = '".$thisYear."'";	
				elseif($tab == 4)
				if($page_name!= "")
					{
					$whereSql .= " AND MONTH(STR_TO_DATE(UM.user_doj, '%Y-%m-%d' )) = '".$thisMonth."'  AND YEAR(STR_TO_DATE( UM.user_doj, '%Y-%m-%d' )) = '".$thisYear."'";	
					}
					else
					{
					$whereSql .= " AND MONTH(STR_TO_DATE( ST.join_date, '%Y-%m-%d' )) = '".$thisMonth."'  AND YEAR(STR_TO_DATE( ST.join_date, '%Y-%m-%d' )) = '".$thisYear."'";	
					}
				elseif($tab == 5)
					$whereSql .= " AND MONTH(STR_TO_DATE( PP.unsubscribed_date, '%Y-%m-%d' )) = '".$thisMonth."'  AND YEAR(STR_TO_DATE( PP.unsubscribed_date, '%Y-%m-%d' )) = '".$thisYear."'";		
				else
					$whereSql 	 .= " AND MONTH(STR_TO_DATE( PS.payment_date, '%Y-%m-%d' )) = '".$thisMonth."'  AND YEAR(STR_TO_DATE( PS.payment_date, '%Y-%m-%d' )) = '".$thisYear."'";
				
				break;
				
			case 'lastmonth':	
				$lastMonthDetails = getdate(strtotime('last month'));
				$lasMonth		  = $lastMonthDetails['mon'];
				$lasYear		  = $lastMonthDetails['year'];
				if($tab == 2 || $tab == 3)//Cancel/Refund
					$whereSql .= " AND MONTH(STR_TO_DATE( PC.join_date, '%Y-%m-%d' )) = '".$lasMonth."'  AND YEAR(STR_TO_DATE( PC.join_date, '%Y-%m-%d' )) = '".$lasYear."'";
				elseif($tab == 4)
				if($page_name!= "")
					{
						$whereSql .= " AND MONTH(STR_TO_DATE( UM.user_doj, '%Y-%m-%d' )) = '".$lasMonth."'  AND YEAR(STR_TO_DATE( UM.user_doj, '%Y-%m-%d' )) = '".$lasYear."'";
					}
					else
					{
						$whereSql .= " AND MONTH(STR_TO_DATE( ST.join_date, '%Y-%m-%d' )) = '".$lasMonth."'  AND YEAR(STR_TO_DATE( ST.join_date, '%Y-%m-%d' )) = '".$lasYear."'";
					}
				elseif($tab == 5)
					$whereSql .= " AND MONTH(STR_TO_DATE( PP.unsubscribed_date, '%Y-%m-%d' )) = '".$lasMonth."'  AND YEAR(STR_TO_DATE( PP.unsubscribed_date, '%Y-%m-%d' )) = '".$lasYear."'";		
				else
					$whereSql 	 .= " AND MONTH(STR_TO_DATE( PS.payment_date, '%Y-%m-%d' )) = '".$lasMonth."'  AND YEAR(STR_TO_DATE( PS.payment_date, '%Y-%m-%d' )) = '".$lasYear."'";
				
				break;
				
				case 'last3month':	
				$last3MonthDetails = getdate(strtotime('-3 month'));
				$requiredDate	   = date('Y-m-d',$last3MonthDetails[0]);
				if($tab == 2 || $tab == 3)//Cancel/Refund
					$whereSql .= " AND STR_TO_DATE( PC.join_date, '%Y-%m-%d' ) BETWEEN '".$requiredDate."' AND '".$today."'";
				elseif($tab == 4)
				if($page_name!= "")
					{
						$whereSql .= " AND STR_TO_DATE( UM.user_doj, '%Y-%m-%d' ) BETWEEN '".$requiredDate."' AND '".$today."'";	
					}
					else
					{
						$whereSql .= " AND STR_TO_DATE( ST.join_date, '%Y-%m-%d' ) BETWEEN '".$requiredDate."' AND '".$today."'";	
					}
				elseif($tab == 5)
					$whereSql .= " AND STR_TO_DATE( PP.unsubscribed_date, '%Y-%m-%d' ) BETWEEN '".$requiredDate."' AND '".$today."'";		
				else	
					$whereSql .= " AND STR_TO_DATE( PS.payment_date, '%Y-%m-%d' ) BETWEEN '".$requiredDate."' AND '".$today."'";
				
				break;
				
				case 'last6month':	
				$last6MonthDetails = getdate(strtotime('-6 month'));
				$requiredDate	   = date('Y-m-d',$last6MonthDetails[0]);
				if($tab == 2 || $tab == 3)//Cancel/Refund
					$whereSql .= " AND STR_TO_DATE( PC.join_date, '%Y-%m-%d' ) BETWEEN '".$requiredDate."' AND '".$today."'";	
				elseif($tab == 4)
				if($page_name!= "")
					{
						$whereSql .= " AND STR_TO_DATE(UM.user_doj, '%Y-%m-%d' ) BETWEEN '".$requiredDate."' AND '".$today."'";
					}
					else
					{
						$whereSql .= " AND STR_TO_DATE( ST.join_date, '%Y-%m-%d' ) BETWEEN '".$requiredDate."' AND '".$today."'";
					}
				elseif($tab == 5)
					$whereSql .= " AND STR_TO_DATE( PP.unsubscribed_date, '%Y-%m-%d' ) BETWEEN '".$requiredDate."' AND '".$today."'";	
				else	
					$whereSql .= " AND STR_TO_DATE( PS.payment_date, '%Y-%m-%d' ) BETWEEN '".$requiredDate."' AND '".$today."'";
				
				break;
				
				case 'lastyear':	
				$lastyear = getdate(strtotime('last year'));
				$lasYear  = $lastyear['year'];
				if($tab == 2 || $tab == 3)//Cancel/Refund
					$whereSql .= " AND YEAR(STR_TO_DATE( PC.join_date, '%Y-%m-%d' )) = '".$lasYear."'";	
				elseif($tab == 4)
				if($page_name!= "")
					{
						$whereSql .= " AND YEAR(STR_TO_DATE(UM.user_doj, '%Y-%m-%d' )) = '".$lasYear."'";	
					}
					else
					{
						$whereSql .= " AND YEAR(STR_TO_DATE( ST.join_date, '%Y-%m-%d' )) = '".$lasYear."'";	
					}
				elseif($tab == 5)
					$whereSql .= " AND YEAR(STR_TO_DATE( PP.unsubscribed_date, '%Y-%m-%d' )) = '".$lasYear."'";			
				else	
					$whereSql .= " AND YEAR(STR_TO_DATE( PS.payment_date, '%Y-%m-%d' )) = '".$lasYear."'";
				
				break;
				
			case 'alltime':	
				$whereSql 	 .= "";
				
				break;
		}
		$whereSql_2 .= $whereSql;
	
	} elseif($_POST['daterange'] == 2) { // No the second drop down was selected... :)
		$param	.=	'&daterange='.$_POST['daterange'];
		$param	.=	'&frY='.$_POST['frY'].'&frM='.$_POST['frM'].'&frD='.$_POST['frD'];
		$param	.=	'&toY='.$_POST['toY'].'&toM='.$_POST['toM'].'&toD='.$_POST['toD'];
		
		$startDate = $_POST['frY'].'-'.$_POST['frM'].'-'.$_POST['frD'];
		$endDate   = $_POST['toY'].'-'.$_POST['toM'].'-'.$_POST['toD'];
		
		if($startDate > $endDate){
			$errorMsg[] = "Start date should be smaller than end date";
			//unset($_POST['frY'], $_POST['frM'], $_POST['frD'], $_POST['toY'], $_POST['toM'], $_POST['toD']);
			
		}
		if(count($errorMsg) == 0){
			if($startDate != $endDate){
				if($tab == 2 || $tab == 3)
					$whereSql 	 .= " AND STR_TO_DATE( PC.join_date, '%Y-%m-%d' ) BETWEEN '".$startDate."' AND '".$endDate."'";
				elseif($tab == 4)
				if($page_name!= "")
					{
						$whereSql 	 .= " AND STR_TO_DATE(UM.user_doj, '%Y-%m-%d' ) BETWEEN '".$startDate."' AND '".$endDate."'";
					}
					else
					{
						$whereSql 	 .= " AND STR_TO_DATE( ST.join_date, '%Y-%m-%d' ) BETWEEN '".$startDate."' AND '".$endDate."'";
					}
				elseif($tab == 5)
					$whereSql 	 .= " AND STR_TO_DATE( PP.unsubscribed_date, '%Y-%m-%d' ) BETWEEN '".$startDate."' AND '".$endDate."'";	
				else
					$whereSql 	 .= " AND STR_TO_DATE( PS.payment_date, '%Y-%m-%d' ) BETWEEN '".$startDate."' AND '".$endDate."'";	
				
			}else{ // Then we don't need a BETWEEN clause :)
				if($tab == 2 || $tab == 3)
					$whereSql .= " AND STR_TO_DATE( PC.join_date, '%Y-%m-%d' ) = '".$startDate."'";
				elseif($tab == 4)
				if($page_name!= "")
					{
						$whereSql .= " AND STR_TO_DATE(UM.user_doj, '%Y-%m-%d' ) = '".$startDate."'";
					}
					else
					{
						$whereSql .= " AND STR_TO_DATE( ST.join_date, '%Y-%m-%d' ) = '".$startDate."'";
					}
				elseif($tab == 5)
					$whereSql .= " AND STR_TO_DATE( PP.unsubscribed_date, '%Y-%m-%d' ) = '".$startDate."'";		
				else
					$whereSql .= " AND STR_TO_DATE( PS.payment_date, '%Y-%m-%d' ) = '".$startDate."'";	
			}			
		}
		$whereSql_2 .= $whereSql;
	}
	
	if(isset($_POST['report']) && $_POST['report']!='all'){
		$param	.=	'&report='.$_POST['report'];
	}
	
	if($_POST['report'] == 'country' && $_POST['user_country']!="" && $_POST['user_country']!="0"){
		$param	.=	'&user_country='.$_POST['user_country'];
		$whereSql .= " AND UM.user_country = ". $_POST['user_country']." ";
	}
	//For Brand filter
	if($_POST['user_brand']!="" ){
		$param	.=	'&user_brand='.$_POST['user_brand'];
		if($_POST['user_brand']	==	1)
			$whereSql .= " AND b.brand_master_id IS NULL ";
		else
			$whereSql .= " AND b.brand_master_id = ". $_POST['user_brand']." ";
	}
	/*if($_POST['report'] == 'act' || $_POST['report'] == 'inac'){
	    if($_POST['report'] == 'act')
		   $chkCondition = '1';
		if($_POST['report'] == 'inac')
		  $chkCondition = '2';
		 $whereSql .= " AND UM.user_status = ".$chkCondition; 
	}*/
	
	$payment_qry	= '';
	/*if(isset($_POST['report']) && ( $_POST['report']=='paid' || $_POST['report']=='free')){
		if(createPaidUsers()===true){
			if($_POST['report']=='paid'){
				$payment_qry	=	" INNER JOIN paid_users_temp AS PA ON PA.payment_userid = PS.user_id ";
			}elseif($_POST['report']=='free'){
				$whereSql	.=	" AND PA.payment_userid IS NULL ";
				$payment_qry	=	" LEFT JOIN paid_users_temp AS PA ON PA.payment_userid = PS.user_id ";
			}
		}
	}*/
	
	/*$query	= "SELECT count(*) AS total	
				FROM payment AS PS
				INNER JOIN user_master AS UM ON UM.user_id = PS.payment_userid
				INNER JOIN payment_paybox AS PP ON PP.user_id = PS.payment_userid = UM.user_id 
				WHERE PS.payment_status='1' AND PP.status != 'EXPIRED'";*/
				
				
	//***********for polishadmin
	if($page_name!= "")
	{
		
		$joinquery	=	" and UM.user_language =5";
	}
	else
	{
		$joinquery	=	"";
	}
	//**************************			
	if($tab == 2)
	{
		$query		=	"SELECT count(*) AS total_condition	FROM stripe_transaction AS PC INNER JOIN payment AS 	
					     PS ON PC.payment_id = PS.payment_id INNER JOIN user_master AS UM ON UM.user_id = PC.user_id 
					     left join brand_user b on b.user_id=UM.user_id left join brand_master c on 
					     c.brand_master_id=b.brand_master_id WHERE PS.payment_status='0' AND (PS.version = 'stripe' OR PS.version = 'polishstripe') AND  
					     PC.status='CANCELLED'  $whereSql $joinquery";
	
	
	}
	elseif($tab == 3)
	{
		$query		=	"SELECT count(distinct(PC.user_id)) AS total_condition	FROM stripe_transaction AS PC INNER JOIN payment AS 
		    			PS ON PC.payment_id = PS.payment_id INNER JOIN user_master AS UM ON UM.user_id = PC.user_id 
		    			left join brand_user b on b.user_id=UM.user_id left join brand_master c on 
		    			c.brand_master_id=b.brand_master_id WHERE PS.payment_status='1' AND(PS.version = 'stripe' OR PS.version = 'polishstripe') AND 
		    			PC.status='REFUND' $whereSql $joinquery";
	}
	elseif($tab == 4)
	{
		if($page_name!= "")
		{
					//$query		=	"SELECT count(*) AS total_condition	FROM payment As PS INNER JOIN user_master AS UM ON UM.user_id = PS.payment_userid  left join brand_user b on b.user_id=UM.user_id left join brand_master c on c.brand_master_id=b.brand_master_id WHERE UM.user_status !=0 AND PS. payment_status	=1 AND PS.payment_currency='Zloty' $whereSql $joinquery ";
			$query		=	"SELECT count(*) AS total_condition	FROM payment As PS INNER JOIN user_master AS UM ON UM.user_id = PS.payment_userid  
					               left join brand_user b on b.user_id=UM.user_id left join brand_master c on c.brand_master_id=b.brand_master_id WHERE 
					               UM.user_status !=0 AND PS. payment_status	=1 AND   
		                           (PS.version = 'stripe' OR PS.version = 'polishstripe')  $whereSql $joinquery ";  
					        		
		}
		else
		{
	         $query		=	"SELECT count(*) AS total_condition	FROM stripe_transaction AS ST 
	                                INNER JOIN user_master AS UM ON UM.user_id = ST.user_id 
	                                left join brand_user b on b.user_id=UM.user_id 
							        left join brand_master c on c.brand_master_id=b.brand_master_id join payment as p on p.payment_id=ST.payment_id
							        WHERE UM.user_status !=0 AND ST.status='PAID' and (p.version='stripe' OR p.version = 'polishstripe') $whereSql $joinquery";
		}
	}
	elseif($tab == 5)
	{
		
		$query		=	"SELECT count( DISTINCT (
P.payment_userid
) )  AS total_condition	FROM stripe_payment AS PP INNER JOIN user_master AS UM 		
						ON UM.user_id = PP.user_id left join brand_user b on b.user_id=UM.user_id left join          		
								brand_master c on c.brand_master_id=b.brand_master_id JOIN payment AS P ON P.payment_userid = PP.user_id WHERE UM.user_status !=0 AND            
								    			PP.status	!=	'ACTIVE' AND (P.version = 'stripe' OR P.version = 'polishstripe')  $whereSql $joinquery";
		
	}
	else
	{
		
		$query		=	"SELECT count(*) AS total_condition	FROM payment AS PS LEFT JOIN user_master AS UM ON 
		                 UM.user_id = PS.payment_userid left join brand_user b on b.user_id=UM.user_id left join 
		                 brand_master c on c.brand_master_id=b.brand_master_id WHERE PS.payment_status='1' AND   
		                 (PS.version = 'stripe' OR PS.version = 'polishstripe')  $whereSql $joinquery";
	}
	
	

	$totals = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);
	/*$query	= "SELECT count(*) AS total_condition	FROM payment AS PS				
				INNER JOIN user_master AS UM ON UM.user_id = PS.payment_userid
				INNER JOIN payment_paybox AS PP ON PP.user_id = PS.payment_userid = UM.user_id
				WHERE PS.payment_status='1' AND PP.status != 'EXPIRED' $whereSql";*/
				//die($query);
	//$query	= "SELECT count(*) AS total_condition	FROM payment_paybox AS PP INNER JOIN payment AS PS ON PP.user_id = PS.payment_useridINNER JOIN user_master AS UM ON UM.user_id = PP.user_id WHERE PS.payment_status='1' AND PS.version='New' $whereSql";
				
	$total_condition	= $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

	if($total_condition['total_condition']>0){
		$cent_unit	= round((100/$total_condition['total_condition']), 3);
		if($tab == 2)
		{ 
	   $sql ="SELECT UM.user_fname, UM.user_lname,UM.user_email,PC.*,PS.payment_currency,PS.payment_amount,PS.payment_date
		       FROM stripe_transaction AS PC 
		       INNER JOIN   payment AS PS ON PC.payment_id = PS.payment_id  
		       INNER JOIN user_master AS UM ON UM.user_id = PC.user_id 
		       left join brand_user b on b.user_id=UM.user_id 
		       left join brand_master c on c.brand_master_id=b.brand_master_id 
		       WHERE PS.payment_status='0' AND 
		      (PS.version = 'stripe' OR PS.version = 'polishstripe') AND PC.status ='CANCELLED' $whereSql $joinquery";
		}
		elseif($tab == 3)
		{
		$sql = "SELECT UM.user_fname, UM.user_lname,UM.user_email,PC.* 					
					FROM stripe_transaction AS PC										
					INNER JOIN payment AS PS ON PC.payment_id = PS.payment_id
					INNER JOIN user_master AS UM ON UM.user_id = PC.user_id 
					left join brand_user b on b.user_id=UM.user_id 
					left join brand_master c on c.brand_master_id=b.brand_master_id
					WHERE PS.payment_status='1' AND (PS.version = 'stripe' OR PS.version = 'polishstripe') AND PC.status='REFUND'
					$whereSql $joinquery
					";
		}
		elseif($tab == 4)
		{
			if($page_name!= "")
			{
				  /*$sql = "SELECT UM.user_fname, UM.user_lname,UM.user_email,UM.user_doj,PS. payment_status FROM payment As PS INNER JOIN user_master AS UM ON UM.user_id = PS.payment_userid left join brand_user b on b.user_id=UM.user_id 
					left join brand_master c on c.brand_master_id=b.brand_master_id
					WHERE UM.user_status !=0 AND PS. payment_status	=1 AND PS.payment_currency='Zloty' $whereSql $joinquery ";*/
			      /*previous code $sql = "SELECT UM.user_fname, UM.user_lname,UM.user_email,UM.user_doj,PS. payment_status FROM payment 
					As PS INNER JOIN user_master AS UM ON UM.user_id = PS.payment_userid left join brand_user b on b.user_id=UM.user_id 
					left join brand_master c on c.brand_master_id=b.brand_master_id
					WHERE UM.user_status !=0 AND PS. payment_status	=1  $whereSql $joinquery ";
				  */
					$sql = "SELECT UM.user_fname, UM.user_lname,UM.user_email,UM.user_doj,PS. payment_status FROM payment 
					As PS INNER JOIN user_master AS UM ON UM.user_id = PS.payment_userid left join brand_user b on b.user_id=UM.user_id 
					left join brand_master c on c.brand_master_id=b.brand_master_id
					WHERE UM.user_status !=0 AND PS. payment_status	=1 AND ( PS.version = 'stripe' OR PS.version = 'polishstripe' )  $whereSql $joinquery ";
			}
			else
			{
				/*
				    SELECT count(PS.payment_userid) 
					FROM payment AS PS															
					LEFT JOIN user_master AS UM ON UM.user_id = PS.payment_userid 
					left join brand_user b on b.user_id=UM.user_id 
					left join brand_master c on c.brand_master_id=b.brand_master_id
					WHERE PS.payment_status='1' AND (
                    PS.version = 'stripe'
                    OR PS.version = 'polishstripe
				 */
		 $sql = "SELECT UM.user_fname, UM.user_lname,UM.user_email,ST.join_date,ST.status as 'payment_status',ST.status,ST.brand ,p.version , p.payment_id					
					FROM stripe_transaction AS ST														
					INNER JOIN user_master AS UM ON UM.user_id = ST.user_id 
					left join brand_user b on b.user_id=UM.user_id 
					left join brand_master c on c.brand_master_id=b.brand_master_id join payment as p on p.payment_id=ST.payment_id
					WHERE UM.user_status !=0 AND ST.status='PAID' $whereSql $joinquery";
			}
		}
		elseif($tab == 5)
		{
			
				$sql = "SELECT DISTINCT (
P.payment_userid
), UM.user_fname, UM.user_lname, UM.user_email, PP.unsubscribed_date, PP.status, PP.brand
FROM stripe_payment AS PP
INNER JOIN user_master AS UM ON UM.user_id = PP.user_id
LEFT JOIN brand_user b ON b.user_id = UM.user_id
LEFT JOIN brand_master c ON c.brand_master_id = b.brand_master_id
INNER JOIN payment AS P ON P.payment_userid = PP.user_id
WHERE UM.user_status !=0
AND PP.status != 'ACTIVE'
AND (
P.version = 'stripe'
OR P.version = 'polishstripe'
) $whereSql $joinquery";
			
		}
		else
		{
			$sql = "SELECT UM.user_fname, UM.user_lname,UM.user_email,PS.payment_amount,PS.payment_currency,
			         PS.payment_expdate,UM.user_id, PC.join_date AS payment_date,PC.trans_refrns_id 					
					FROM payment AS PS																				
					LEFT JOIN stripe_transaction AS PC ON PS.payment_id = PC.payment_id
					LEFT JOIN user_master AS UM ON UM.user_id = PS.payment_userid 
					left join brand_user b on b.user_id=UM.user_id 
					left join brand_master c on c.brand_master_id=b.brand_master_id
					WHERE PS.payment_status='1' AND (PS.version = 'stripe' OR PS.version = 'polishstripe') AND PC.status = 'PAID' $whereSql $joinquery
					";	
		}
	//*************************** Countrywice report for the users starts here ****************************
	//$sql = $sqlStart.$fromSql.$whereSql." GROUP BY user_master.user_id ORDER BY user_master.user_doj DESC";
		/*$paging_query	= "SELECT count(PS.payment_userid) 
					FROM payment AS PS 					
					INNER JOIN user_master AS UM ON UM.user_id = PS.payment_userid
					INNER JOIN payment_paybox AS PP ON PP.user_id = PS.payment_userid = UM.user_id
					WHERE payment_status='1' AND PP.status != 'EXPIRED' 
					$whereSql";*/
		if($tab == 2)
		{   
			 $paging_query	= "SELECT count(PC.user_id) 
					FROM stripe_transaction AS PC					
					INNER JOIN payment AS PS ON PC.payment_id = PS.payment_id
					INNER JOIN user_master AS UM ON UM.user_id = PC.user_id 
					left join brand_user b on b.user_id=UM.user_id 
					left join brand_master c on c.brand_master_id=b.brand_master_id
					WHERE PS.payment_status='0' AND (PS.version = 'stripe'
                    OR PS.version = 'polishstripe'
                    ) AND PC.status='CANCELLED'  
					$whereSql $joinquery";
		}
		elseif($tab == 3)
		{
			$paging_query	= "SELECT count(PC.user_id) 
					FROM stripe_transaction AS PC					
					INNER JOIN payment AS PS ON PC.payment_id = PS.payment_id
					INNER JOIN user_master AS UM ON UM.user_id = PC.user_id 
					left join brand_user b on b.user_id=UM.user_id 
					left join brand_master c on c.brand_master_id=b.brand_master_id
					WHERE PS.payment_status='1' AND (PS.version = 'stripe'
                    OR PS.version = 'polishstripe'
                    ) AND PC.status='REFUND'  
					$whereSql $joinquery";
		}
		elseif($tab == 4)
		{
			if($page_name!= "")
				{				
					
					$paging_query	= "SELECT count(PS.payment_userid) 
					FROM payment AS PS									
					INNER JOIN user_master AS UM ON UM.user_id = PS.payment_userid
					left join brand_user b on b.user_id=UM.user_id 
					left join brand_master c on c.brand_master_id=b.brand_master_id
					WHERE UM.user_status !=0 AND PS. payment_status	=1 AND (PS.version = 'stripe'
                    OR PS.version = 'polishstripe'
                    )  $whereSql $joinquery";	
				}
				else
				{
					$paging_query	= "SELECT count(ST.user_id) 
					FROM stripe_transaction AS ST										
					INNER JOIN user_master AS UM ON UM.user_id = PP.user_id 
					left join brand_user b on b.user_id=UM.user_id 
					left join brand_master c on c.brand_master_id=b.brand_master_id
					WHERE UM.user_status !=0 AND ST.status='PAID' $whereSql $joinquery";
				}
		}
		elseif($tab == 5)
		{
			$paging_query	= "SELECT count(PP.user_id) 
					FROM stripe_payment AS PP										
					INNER JOIN user_master AS UM ON UM.user_id = PP.user_id 
					left join brand_user b on b.user_id=UM.user_id 
					left join brand_master c on c.brand_master_id=b.brand_master_id
					WHERE UM.user_status !=0 AND PP.status	!=	'ACTIVE' $whereSql $joinquery";
		}
		else
		{
			$paging_query	= "SELECT count(PS.payment_userid) 
					FROM payment AS PS															
					LEFT JOIN user_master AS UM ON UM.user_id = PS.payment_userid 
					left join brand_user b on b.user_id=UM.user_id 
					left join brand_master c on c.brand_master_id=b.brand_master_id
					WHERE PS.payment_status='1' AND (PS.version = 'stripe' OR PS.version = 'polishstripe')  
					$whereSql $joinquery";
		}
		$totalRecs		= $GLOBALS['db']->getOne($paging_query);
//////////////////////////////// PAGINATION //////////////////////////////////
		$param	= substr($param, 1);
		$param	= base64_encode($param);
		if(!$_REQUEST['maxrows'])
			$_REQUEST['maxrows'] = $_POST['maxrows'];
		if($_REQUEST['pageNo']){
			if($_REQUEST['pageNo']*$_REQUEST['maxrows'] >= $totalRecs+$_REQUEST['maxrows']){
				$_REQUEST['pageNo'] = 1;
			}
			$fromLimit = $_REQUEST['maxrows']*($_REQUEST['pageNo'] - 1);
			$toLimit = $_REQUEST['maxrows'];
			
			$sql.= " LIMIT {$fromLimit}, {$toLimit} ";
			$result=$objDb->_getList($sql);
		}
		else{
		/***********************Selects Records at initial stage***********************************************/
			$_REQUEST['pageNo'] = 1;
		
			//$result = $objHomepage->_showPage($totalRecs,$_REQUEST['pageNo'],$_REQUEST['maxrows'],$field,$type,$searchQuery);
			$fromLimit = $_REQUEST['maxrows']*($_REQUEST['pageNo'] - 1);
			$toLimit = $_REQUEST['maxrows'];
			
			$sql.= " LIMIT {$fromLimit}, {$toLimit} ";
			$result=$objDb->_getList($sql);
			
			/*echo count($result);*/
			if(count($result) <= 0)
				$errMsg = "No Records.";
		}		
		if($totalRecs <= $_REQUEST['pageNo']*$_REQUEST['maxrows']){
			//For showing range of displayed records.
			if($totalRecs <= 0)
				$startNo = 0;
			else
				$startNo = $_REQUEST['pageNo']*$_REQUEST['maxrows']-$_REQUEST['maxrows']+1;
			$endNo = $totalRecs;
			$displayString = "Viewing $startNo to $endNo of $endNo Homepage";
			
		}
		else{
			//For showing range of displayed records.
			if($totalRecs <= 0)
				$startNo = 0;
			else
				$startNo = $_REQUEST['pageNo']*$_REQUEST['maxrows']-$_REQUEST['maxrows']+1;
			$endNo = $_REQUEST['pageNo']*$_REQUEST['maxrows'];
			$displayString = "Viewing $startNo to $endNo of $totalRecs homepage";
			
		}
		//Pagin 
		$noOfPage = @ceil($totalRecs/$_REQUEST['maxrows']); 		
		if($_REQUEST['pageNo'] == 1){
			$prev = 1;
		}
		else
			$prev = $_REQUEST['pageNo']-1;
		if($_REQUEST['pageNo'] == $noOfPage){
			$next = $_REQUEST['pageNo'];
		}
		else
			$next = $_REQUEST['pageNo']+1;
	////////////////////////////////////Pagination ends here/////////////////////////////////////////
		
	}
	
	//*************************** Countrywice report for the users Ends  here *****************************	
	
	//echo($_POST['dropdown1']);


function extractParams($param){
	$en_scode=base64_decode($param);
	$strVal=explode("&",$en_scode);
	//print_r($strVal);
	for($i=0, $strVal_size=sizeof($strVal);$i<$strVal_size;$i++){	
		$seperateValues=explode("=",$strVal[$i]);
		if(!isset($_REQUEST[$seperateValues[0]])){
			$_REQUEST[$seperateValues[0]]	= $seperateValues[1];
		}
		if(!isset($_POST[$seperateValues[0]])){
			$_POST[$seperateValues[0]]	= $seperateValues[1];
		}
	}
}

?>	
<HTML><HEAD><TITLE><?=$admin_title?></TITLE>
<link href="./js/jscalendar/calendar-blue.css" rel="stylesheet" type="text/css" media="all">
<script language="javascript" src="./js/jscalendar/calendar.js"></script>
<script language="javascript" src="./js/jscalendar/calendar-en.js"></script>
<script language="javascript" src="./js/jscalendar/calendar-setup.js"></script>
<script language="javascript">
//for submiting the form
function change() {
	var $data = document.getElementById("report").value; 
	if($data == 'country'){
		document.getElementById("countryDisplay").style.display="block";
		return false;
	}
	document.reportFrm.submit();
}
function chkValue() {
   	var $value = document.getElementById("dropdown1").value; 
	if($value == 'selectperiod'){
		document.getElementById("daterange2").checked=true;
		document.getElementById("periodDisplay").style.display="block";
	}else{
		document.reportFrm.submit();	
	}
}
//chkValue
</script>
<script type="text/javascript" src="js/overlib421/overlib.js"><!-- overLIB (c) Erik Bosrup --></script>
<script type="text/javascript">
//ol_closeclick	= 1;
function myFunction(id){
	ol_texts[id] = 	document.getElementById(id).innerHTML.toString();
}
ol_sticky		= 1;
ol_closeclick	= 1;
ol_fgcolor		= '#000000';
ol_bgcolor		= '#000000';
ol_width		= 400;
ol_closecolor	= '#FFFFFF';
</script>
<style type="text/css">
.popup_box {background-color:#ffffff; font-size:12px; font: tahoma; font-weight: bold; width:100%}
td.boldC {font-weight: bold}
.hidden_div {visibility:hidden; height:0;}
#navigation {
	background: #FFF;
	color: #000;
	font: 62.5% "Lucida Grande", Verdana, Geneva, Helvetica, sans-serif;
	margin: 0;
	padding: 0;
	background: #AFD5E0 url("../images/bg-nav.gif") repeat-x;
	border: 1px solid #979797;
	border-width: 1px 0;
	font-size: 13px;
	margin-top: 1em;
	padding-top: .6em;
}

#navigation ul, #navigation ul li {
	list-style: none;
	margin: 0;
	padding: 0;
}

#navigation ul {
	padding: 5px 0;
	text-align: center;
}

#navigation ul li {
	display: inline;
	margin-right: .75em;
}

#navigation ul li.last {
	margin-right: 0;
}

#navigation ul li a {
	background: url("../images/tab-right.gif") no-repeat 100% 0;
	color: #06C;
	padding: 5px 0;
	text-decoration: none;
}

#navigation ul li a span {
	background: url("../images/tab-left.gif") no-repeat;
	padding: 5px 1em;
}

#navigation ul li a:hover span {
	color: #69C;
	text-decoration: underline;
}

/*\*//*/
#navigation ul li a {
	display: inline-block;
	white-space: nowrap;
	width: 1px;
}

#navigation ul {
	padding-bottom: 0;
	margin-bottom: -1px;
}
/**/
</style>
<? include_once('metadata.php');?>
<BODY class="bodyStyle">
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<TABLE cellSpacing=0 cellPadding=0 width="779" align="center" border="1px" bordercolor="#E6E6E6"> 
  <TR>
    <TD vAlign=top align=left bgColor=#ffffff><? include("header.php");?></TD>
  </TR>
  <TR height="5">
    <TD vAlign=top align=left class="topBarColor">&nbsp;</TD>
  </TR>
  <TR>
    <TD vAlign="top" align="left" height="340"> 
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 class="middleTableBg">
        <TR> 
          <TD vAlign=top align=left width="175" rowSpan="2" > 
            <TABLE cellSpacing="0" cellPadding="0" width="175" border=0>
              <TR> 
                <TD valign="top">
				 <TABLE cellSpacing=0 cellPadding=2 width=175 border=0>
                    <TBODY> 
                    <TR valign="top"> 
                      <TD valign="top"><? include ('leftmenu.php');?></TD>
                    </TR>
                    
                    </TBODY> 
                  </TABLE>
				</TD>
              </TR>
            </TABLE>
          </TD>
          <TD vAlign=top align=left width=0></TD>
         
        </TR>
        <TR> 
          <TD valign="top" width="1067"><!---Contents Start Here----->
		  
		  <form name="reportFrm" action="#" method="post">
            <TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
              <TR> 
                <TD class=smalltext width="98%" valign="top">
				
				  <table width="75%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr> 
                <td width="10" height="9"><img src="images/top_left.jpg" width="9" height="9"></td>
                <td width="543" height="9" background="images/top_mdl.jpg"><img src="images/top_mdl.jpg" width="9" height="9"></td>
                <td width="11" height="9"><img src="images/top_right.jpg" width="9" height="9"></td>
              </tr>
              <tr> 
                <td background="images/side1.jpg"><img src="images/side1.jpg" width="9" height="9"></td>
                <td valign="top"> 
				
				
				
				<TABLE cellSpacing=0 cellPadding=0 border=0 align="center">
                    <TR> 
                      <TD vAlign=top width=564 bgColor=white> 
                       
			   
                        
				  <table width=553 height="227" border=0 cellpadding=0 cellspacing=0 class="paragraph2">
				  <tr>
						<td height="4" colspan="4" align="center" valign="bottom" class="sectionHeading"><?=$heading;?></td>
					</tr>
				  
				  <tr>
				    <td colspan="2" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;					</td>
				    <td width="117" height="27" align="center" valign="bottom" class="sectionHeading"><a href="excel_newpayment_stripe.php?inf=<? echo base64_encode($sql);?>&tab=<?php echo $tab;?>&whereSql=<?php echo base64_encode($whereSql);?>"><img src="../images/sports/english/download.gif" style="float:right" border="0"></a></td>
				  </tr>
				    
				  <tr style="padding-top:10px;">
				    <td width="191" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
				    <td width="147" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
				    <td width="117" height="19" align="center" valign="bottom" class="sectionHeading">&nbsp;</td>
				  </tr>
			  
				  <tr style="padding-top:10px;">
				    <td height="21" colspan="3" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;"><span class="successAlert">Choose date range </span></td>
				    </tr>
				  <tr style="padding-top:10px;">
				  <td  colspan="3" align="left" valign="bottom">
				  <div id="dateDisplay">
				  <table><tr> 
					<td height="21" colspan="3" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;"><span class="successAlert">
				      <input name="daterange" id="daterange1" type="radio" value="1" <? if($_POST['daterange'] ==1 or $_POST['daterange'] == '') echo 'checked="checked"';?> >
                      <select name="dropdown1" id="dropdown1" onChange="chkValue();">
                        <?
							$str = '';
							foreach($dropDownArray as $key => $value){
								$str .= '<option value="'.$key.'"';
								if($_POST['dropdown1'] == $key)
									$str .= ' selected = "selected"';
								$str .= '>'.$value.'</option>';
							}
							echo $str;
						 
						?>
                      </select>
				    </span></td></tr></table>
					</div>
					</td>
					</tr>
				  <tr style="padding-top:10px;">
				   <td  colspan="3" align="left" valign="bottom">
				  <div id="periodDisplay" <? if($_POST['daterange'] == 2){?>style="display:block" <? }else{?>style="display:none"<? } ?>>
				  <table width="548">
				    <tr>  
					<td height="21" colspan="2" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;"><span class="successAlert">
				      <input name="daterange" id="daterange2" type="radio" value="2" <? if($_POST['daterange'] == 2) echo 'checked="checked"';?>>
                      <select name="frD">
                        <?
							$str = '';
							for($i=1;$i<32;$i++){
								$day_value	= $i;
								if(strlen($day_value)==1){
									$day_value	= '0'.$day_value;
								}
								$str .= '<option value="'.$day_value.'"';
								if($_POST['frD'] == $day_value)
									$str .= ' selected = "selected"';
								$str .= '>'.$day_value.'</option>';
							}
							echo $str;
						?>
                      </select>
                      <select name="frM">
                        <?
							$mArray = array(
										"01" => "January",
										"02" => "February",
										"03" => "March",
										"04" => "April",
										"05" => "May",
										"06" => "June", 
										"07" => "July", 
										"08" => "August", 
										"09" => "September", 
										"10" => "October", 
										"11" => "Novemer", 
										"12" => "December"
									  );
							$str = '';
							foreach($mArray as $key => $value){
								$str .= '<option value="'.$key.'"';
								if($_POST['frM'] == $key)
									$str .= ' selected = "selected"';
								$str .= '>'.$value.'</option>';
							}
							echo $str;
								
						?>
                      </select>
                      <select name="frY">
                        <?
							$str = '';
							for($i=$todayDetails['year']-5;$i<=$todayDetails['year'];$i++){
								$str .= '<option value="'.$i.'"';
								if($_POST['frY'] == "" and $i == $todayDetails['year'])
									$str .= 'selected="selected"';
								elseif($_POST['frY'] == $i)
									$str .= 'selected="selected"';
								$str .= '>'.$i.'</option>';	
							}
							echo $str;
						?>
                      </select>
-
<select name="toD">
  <?
							$str = '';
							for($i=1;$i<32;$i++){
								$day_value	= $i;
								if(strlen($day_value)==1){
									$day_value	= '0'.$day_value;
								}
								$str .= '<option value="'.$day_value.'"';
								if($_POST['toD'] == $day_value)
									$str .= ' selected = "selected"';
								$str .= '>'.$day_value.'</option>';
							}
							echo $str;
						?>
</select>
<select name="toM">
  <?
							$str = '';
							foreach($mArray as $key => $value){
								$str .= '<option value="'.$key.'"';
								if($_POST['toM'] == $key)
									$str .= ' selected = "selected"';
								$str .= '>'.$value.'</option>';
							}
							echo $str;
						?>
</select>
<select name="toY">
  <?
							$str = '';
							for($i=$todayDetails['year']-5;$i<=$todayDetails['year'];$i++){
								$str .= '<option value="'.$i.'"';
								if($_POST['toY'] == "" and $i == $todayDetails['year'])
									$str .= 'selected="selected"';
								elseif($_POST['toY'] == $i)
									$str .= 'selected="selected"';
								$str .= '>'.$i.'</option>';	
							}
							echo $str;
						?>
</select>
				    </span></td>
					<td width="109"><input name="image" type="image"  style="float:right;"  onClick="this.form.submit" src="../images/sports/english/generate.gif"></td>
					</tr></table></div>
					
				    </tr>
					<? if(count($errorMg) >0){?>
				  <tr style="padding-top:10px;">
				    <td height="21" colspan="3" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;"><? print $objGen->_adminmessage_box($errorMsg,$mode='error',$url='normal') ?></td>
				    </tr>
					<? }?>				  
				  <tr style="padding-top:10px;">
				    <td height="21" colspan="3" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">Brand </td>
				    </tr>
				  <tr style="padding-top:10px;">
				    <td height="24" colspan="3" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">
				      <select id="user_brand" name="user_brand"  onChange="this.form.submit();">
				        <option value="">--Select--</option>
                        <option value="1" <?php if($_POST[user_brand]	==	1) echo $selected;?>>Jiwok</option>                        
                        <option value="21" <?php if($_POST[user_brand]	==	21) echo $selected;?>>Kalenji</option>
                        <option value="28" <?php if($_POST[user_brand]	==	28) echo $selected;?>>Parismarathon</option>
                        <option value="25" <?php if($_POST[user_brand]	==	25) echo $selected;?>>Domyos</option>
                        <option value="26" <?php if($_POST[user_brand]	==	26) echo $selected;?>>Nabaiji</option>
                        <option value="29" <?php if($_POST[user_brand]	==	29) echo $selected;?>>Semideparis</option>
				      </select>
                      </td>
				    </tr>
				  <tr style="padding-top:10px;">
				    <td height="21" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">Search For </td>
				    <td width="147" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;"><input type="hidden" name="tab" id="tab" value="<?php echo $tab;?>"></td>
				    <td width="117" height="4" align="center" valign="bottom" class="sectionHeading">&nbsp;</td>
				  </tr>
				  <tr style="padding-top:10px;">
				    <td height="24" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">
					<select id="report" name="report" onChange="change();">
					   <option value="all" <? if(!isset($_POST['report']) || $_REQUEST['report']=='all') echo 'selected="selected"'?> >Whole</option>
					   <option value="country" <? if($_REQUEST['report']=='country') echo 'selected="selected"'?>>Per country</option>
					   <!--<option value="subc" <? if($_REQUEST['report']=='subc') echo 'selected="selected"'?>>Not Subscribed Yet</option> -->
					   <!--<option value="down" <? if($_REQUEST['report']=='down') echo 'selected="selected"'?>>Not Downloaded Yet</option> -->
					   <!--<option value="inac" <? if($_REQUEST['report']=='inac') echo 'selected="selected"'?>>Inactive Members</option>
					   <option value="act" <? if($_REQUEST['report']=='act') echo 'selected="selected"'?>>Active Members</option>
					   <option value="free" <?	if(isset($_POST['report']) && $_POST['report']=='free') echo('selected="selected"');	?>>Free Members</option>
						<option value="paid" <?	if(isset($_POST['report']) && $_POST['report']=='paid') echo('selected="selected"');	?>>Paid Members</option>-->
				    </select>
					</td>
				    <td width="147" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
				    <td width="117" height="4" align="center" valign="bottom" class="sectionHeading">&nbsp;</td>
				  </tr>
				  <? //if($_REQUEST['report']=='country') {?>
				  <tr style="padding-top:10px;">
				    <td align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;"><div id="countryDisplay" <? if($_REQUEST['report']=='country') {?>style="display:block" <? }else{?>style="display:none"<? } ?> ><table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr><td height="19" class="sectionHeading"> Country</td>
					</tr>
                      <tr>
                        					
				    <td width="247" height="30" align="left" valign="middle" class="sectionHeading" ><select id="user_country" name="user_country"  onChange="this.form.submit();">
                                    <option value="0">--Select--</option>
                          <? 
                                while(list($code,$name) = each($countriesArray)){
                                    $string = "<option value={$code}";
                                    if($code == $_REQUEST['user_country']){
                                        $string .= " selected";
                                    }
                                    $string .= ">{$name}</option>";
                                    print $string;
                                }
                           ?>
                                </select></td>
                      </tr>
					  
                    </table></div></td>
				    <td width="147" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
				    <td width="117" height="7" align="center" valign="bottom" class="sectionHeading">&nbsp;</td>
				  </tr>
				  <? //}?>
                  <tr style="padding-top:10px;">
				    <td height="21" align="right" valign="bottom" class="sectionHeading" style="padding-left:10px;"><span class="successAlert"><strong>Total Members</strong> </span></td>
				    <td height="21" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;"><?php echo $total_condition['total_condition'];?></td>
				    <td height="21" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
				    </tr>                  
                  <tr style="padding-top:10px;">
				    <td height="7" colspan="3" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;"><div id="navigation">
	<ul>		
        <li><a href="report_stripe_Payment.php?pageNo=<?=$_REQUEST['pageNo']?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>&tab=1"><span>Payments</span></a></li>
		<li><a href="report_stripe_Payment.php?pageNo=<?=$_REQUEST['pageNo']?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>&tab=2"><span>Cancel</span></a></li>
		<li><a href="report_stripe_Payment.php?pageNo=<?=$_REQUEST['pageNo']?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>&tab=3"><span>Refund</span></a></li>
		<li><a href="report_stripe_Payment.php?pageNo=<?=$_REQUEST['pageNo']?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>&tab=4"><span>Members</span></a></li>
        <?php if($page_name== "")
			  {?>
					
        <li><a href="report_stripe_Payment.php?pageNo=<?=$_REQUEST['pageNo']?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>&tab=5"><span>Unsubscribed </span></a></li><?php } ?>

	</ul>
</div></td>
				    </tr>
                  
                  
                  
                  
				  <tr><td colspan="4"></td></tr>
					<?php if($confMsg != ""){?>
					<tr> <td height="18" colspan="4" align="center" class="successAlert"><?=$confMsg?></td> 
					</tr>
					<?php }
						if(count($errorMsg) > 0){
					?>			<tr>
						<td colspan="4" align="center"  class="successAlert"><?=$errorMsg[0]?></td>
					</tr>
					<?php } ?>
					
					<TR> 
					<TD height="2" colspan="4" align="left"></TD>
					</TR>
				  </table><br/> 
                  <?php if($tab == 2){
					  	if(count($result)>0)
				  		{						 
			  $amountQuery	=	"SELECT sum( PS.payment_amount ) AS Total, PS.payment_currency FROM  		 		
						  									stripe_transaction AS PC
 											INNER JOIN payment AS PS ON PC.payment_id = PS.payment_id
 											INNER JOIN user_master AS UM ON UM.user_id = PC.user_id WHERE                    	
 																PS.payment_status='0' AND (PS.version = 'stripe'
OR PS.version = 'polishstripe'
) AND PC.status='CANCELLED'   											$whereSql $joinquery GROUP BY PS.payment_currency";					
						  $resultAmount	=	$objDb->_getList($amountQuery);	
					  $PlanQuery	=	"SELECT sum( PS.payment_amount ) AS Total,COUNT( PS.payment_amount )  																				 											
					                             As count,PS.payment_currency,PS.payment_amount FROM                             				
					                             stripe_transaction AS PC INNER JOIN payment AS PS ON PC.payment_id = PS.payment_id 	
					                             INNER JOIN user_master AS UM ON UM.user_id = PC.user_id  									
					                             	WHERE PS.payment_status='0' AND (PS.version = 'stripe' OR PS.version = 'polishstripe') AND                                           
					                           	PC.status='CANCELLED'  $whereSql $joinquery GROUP BY PS.payment_amount ORDER BY  
					                           	 `PS`.`payment_currency` DESC";					
						  $resultPlan	=	$objDb->_getList($PlanQuery);	
						  ?> 
						<fieldset>
							<legend>Cancellation details </legend>
							<table width="90%" align="center" cellpadding="3" >
							<tr>
								<td width="28%" align="left"><strong>Total Cancellation</strong></td>
								<td width="72%"><?=$total_condition['total_condition'];?></td>
							</tr>
							<?php foreach( $resultAmount	as  $resultAmounts)
							{?>
							<tr>
								<td align="left"><strong>Total Amount(<?=$resultAmounts[payment_currency];?>)</strong></td>
								<td><?=round($resultAmounts[Total],2);?></td>
							</tr>                        
							<?php }?>
							<tr>
							  <td colspan="2" align="left">
								<fieldset>
									<legend>Cancellation details by plan </legend>
									<table width="90%" align="center" cellpadding="3" >
									<tr>
										<td width="25%" align="Center"><strong>Amount</strong></td>
										<td width="37%" align="Center"><strong>Total Cancellation</strong></td>
										<td width="38%" align="Center"><strong>Total Amount</strong></td>
									</tr>
									<?php foreach( $resultPlan	as  $resultPlans)
									{?>
									<tr>
										<td align="center"><?=$resultPlans[payment_amount]." ".$resultPlans[payment_currency];?></td>
										<td align="center"><?=$resultPlans[count];?></td>
										<td align="center"><?=round($resultPlans[Total],2)." ".$resultPlans[payment_currency];?></td>
									</tr>
									<?php }?>											                      
									</table>
								</fieldset>
							   </td>
							  </tr>											                      
							</table>
						</fieldset><br/>
                  <?php }?> 				
				  <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="553">
                    <TBODY>
                      <TR class="tableHeaderColor">
                        <TD width="6%" align="center" >#</TD>
                        <TD width="17%" align="center" >Name </TD>
                        <TD width="34%" align="center" >Email </TD>
                        <TD width="14%" align="center" >Amount</TD>
                        <TD width="10%" align="center" >Currency</TD>
                        <TD width="19%" align="center" >Date</TD>                        
                      </TR>
                      <?php if($errMsg != ""){?>
                      <TR class="listingTable">
                        <TD align="center" colspan="7" ><font color="#FF0000">
                          <?=$errMsg?>
                        </font> </TD>
                      </TR>
                      <? }?>
                      <? if(count($result)>0){
					            $totalCount = 0;
					            foreach($result as $key =>$val){
								$totalCount += $val['countCon'];
								}
					  
					  ?>
					  
                      <tr class="listingTable">
                        <TD align="center" colspan="7" class="tblbackgnd"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <?
				$count = $startNo; 
				foreach($result as $key =>$val){ 
					$val['user_fname'] = htmlspecialchars(stripslashes($val['user_fname']));
					$val['user_lname'] = htmlspecialchars(stripslashes($val['user_lname']));
			?><tr  <? if(($key%2) ==1){?> bgcolor="#FFFFFF" <? } ?> >
                              <TD width="9%" height="19" align="center"><? echo $count;?></TD>
                              <TD width="16%" height="19" align="left" style="padding-left:10px;"><? echo $val['user_fname']." ".$val['user_lname'];?></TD>
                              <TD width="32%" height="19" align="left" style="padding-left:10px;"><?php echo $val['user_email'];?></TD>
                              <TD width="11%" align="center" style="padding-left:10px;">
							  	<?php echo $val['payment_amount'];?>
                               </TD>
                              <TD width="14%" align="center" style="padding-left:10px;"><?php echo $val['payment_currency'];?></TD>
                              
                              <TD width="18%" align="center" style="padding-left:10px;"><?php echo $val['payment_date'];?></TD>
                            </tr>
							
                            <? 
					$count++;
				}
			?>							
                        </table>
						<!-- PAGING START-->
						<table cellspacing=0 cellpadding=0 width=553 border=0 class="topColor">
                                <tbody>		
					<tr><?php if($noOfPage > 1) { ?>
						<td align="left" colspan = "6" class="leftmenu">
						<a href="report_stripe_Payment.php?pageNo=1&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>&tab=<?=$tab?>">
						<img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>
						<a href="report_stripe_Payment.php?pageNo=<?=$prev?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>&tab=<?=$tab?>">
						<img src="images/mail-arrowlg.gif" border=0 width="20" height="20" align="absmiddle" alt="Previous Page"></a>[Page 
						<select name="pageNo" class="paragraph"  onChange="form.submit()">
							<?php
							if($noOfPage){
								for($i = 1; $i <= $noOfPage; $i++){
							?>
								<option value="<?=$i?>" <? if($i==$_REQUEST['pageNo']) echo "selected";?>><?=$i?></option>
							<?php
								}
							}
							else{
								echo "<option value=\"\">0</option>";
							}
							?>
						</select>
							 of <?=$noOfPage?>]
							 <a href="report_stripe_Payment.php?pageNo=<?=$next?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>&tab=<?=$tab?>">
							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>
							<a href="report_stripe_Payment.php?pageNo=<?=$noOfPage?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>&tab=<?=$tab?>">
							<img src="images/mail-arrowlastg.gif" border=0 width="20" height="20" align="absmiddle" alt="Last Page"></a>						</td><?php } ?>
						<td width="233" align=right class="paragraph2"><?=$heading;?> per page: 
			
						<select class="paragraph"  size=1 onChange="this.form.submit()" name="maxrows">
						<? foreach($siteRowListConfig as $key=>$data){ ?>
						  <option value="<?=$key?>" <? if($_REQUEST['maxrows']==$key) echo"selected"; ?>><?=$data;?></option>
						 <? } ?>
						</select>					</td>
					</tr>
				   </tbody>
			 	</table>
						<!-- PAGING END-->						</TD>
                      </tr>
                      <? }
					  else{
					  ?>
					  <tr class="listingTable"><TD align="center" colspan="7" >No Records</TD></tr><? } ?>
                    </tbody>
                  </table>
                  <?php }
				  elseif($tab == 3){
					  if(count($result)>0)
				  		{					
							//~ echo $amountQuery	=	"SELECT sum( PS.payment_amount ) AS Total, PS.payment_currency,PC.balance,PC.join_date,UM.* FROM  		 		
						  									//~ stripe_transaction AS PC
 											//~ INNER JOIN payment AS PS ON PC.payment_id = PS.payment_id
 											//~ INNER JOIN user_master AS UM ON UM.user_id = PC.user_id WHERE                    	
 																//~ PS.payment_status='1' AND (PS.version = 'stripe'
//~ OR PS.version = 'polishstripe'
//~ ) AND PC.status='REFUND'  $whereSql $joinquery GROUP BY PS.payment_currency order by PC.id desc ";			
 $amountQuery	=	"SELECT ( PS.payment_amount - PC.balance ) AS Total, PS.payment_currency,PC.balance,PC.join_date,UM.* FROM  		 		
						  									stripe_transaction AS PC
 											INNER JOIN payment AS PS ON PC.payment_id = PS.payment_id
 											INNER JOIN user_master AS UM ON UM.user_id = PC.user_id WHERE                    	
 																PS.payment_status='1' AND (PS.version = 'stripe'
OR PS.version = 'polishstripe'
) AND PC.balance !=''  $whereSql $joinquery and ( PS.payment_amount - PC.balance ) != '0' order by PC.id desc ";			
						  $result	=	$objDb->_getList($amountQuery);	//print_r($result);
						  ?> 
						<fieldset>
							<legend>Refund details </legend>
							<table width="90%" align="center" cellpadding="3" >
							<tr>
								<td width="28%" align="left"><strong>Total Refunds</strong></td>
								<td width="72%"><?=$total_condition['total_condition'];?></td>
							</tr>
							<?php if($euroAmount	!=	0)
							{?>
							<tr>
								<td align="left"><strong>Total Amount(Euro)</strong></td>
								<td><?=round($euroAmount,2);?></td>
							</tr>                        
							<?php }
							if($dollarAmount	!=	0)
							{?>
							<tr>
								<td align="left"><strong>Total Amount(Dollar)</strong></td>
								<td><?=round($dollarAmount,2);?></td>
							</tr>                        
							<?php }	?>													                      
							</table>
						</fieldset><br/>
                  <?php }
					  
					  ?>                   
				  <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="553">
                    <TBODY>
                      <TR class="tableHeaderColor">
                        <TD width="6%" align="center" >#</TD>
                        <TD width="17%" align="center" >Name </TD>
                        <TD width="40%" align="center" >Email </TD>
                        <TD width="11%" align="center" >Amount</TD>
                        <TD width="10%" align="center" >Currency</TD>
                        <TD width="16%" align="center" >Date</TD>                        
                      </TR>
                      <?php if($errMsg != ""){?>
                      <TR class="listingTable">
                        <TD align="center" colspan="7" ><font color="#FF0000">
                          <?=$errMsg?>
                        </font> </TD>
                      </TR>
                      <? }?>
                      <? if(count($result)>0){
					            $totalCount = 0;
					            foreach($result as $key =>$val){
								$totalCount += $val['countCon'];
								}
					  
					  ?>
					  
                      <tr class="listingTable">
                        <TD align="center" colspan="7" class="tblbackgnd"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <?
				$count = $startNo; 
				foreach($result as $key =>$val){ 
					$val['user_fname'] = htmlspecialchars(stripslashes($val['user_fname']));
					$val['user_lname'] = htmlspecialchars(stripslashes($val['user_lname']));
			?><tr  <? if(($key%2) ==1){?> bgcolor="#FFFFFF" <? } ?> >
                              <TD width="6%" height="19" align="center"><? echo $count;?></TD>
                              <TD width="20%" height="19" align="left" style="padding-left:10px;"><? echo $val['user_fname']." ".$val['user_lname'];?></TD>
                              <TD width="24%" height="19" align="left" style="padding-left:10px;"><?php echo $val['user_email'];?></TD>
                              <TD width="8%" align="center" style="padding-left:10px;">
							  	<?php 
									
									echo $val['Total'];
									
								?>
                               </TD>
                              <TD width="11%" align="center" style="padding-left:10px;"><?php echo $val['payment_currency']?></TD>
                              
                              <TD width="15%" align="center" style="padding-left:10px;"><?php echo $val['join_date'];?></TD>
                            </tr>
							
                            <? 
					$count++;
				}
			?>							
                        </table>
						<!-- PAGING START-->
						<table cellspacing=0 cellpadding=0 width=553 border=0 class="topColor">
                                <tbody>		
					<tr><?php if($noOfPage > 1) { ?>
						<td align="left" colspan = "6" class="leftmenu">
						<a href="report_stripe_Payment.php?pageNo=1&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>&tab=<?=$tab?>">
						<img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>
						<a href="report_stripe_Payment.php?pageNo=<?=$prev?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>&tab=<?=$tab?>">
						<img src="images/mail-arrowlg.gif" border=0 width="20" height="20" align="absmiddle" alt="Previous Page"></a>[Page 
						<select name="pageNo" class="paragraph"  onChange="form.submit()">
							<?php
							if($noOfPage){
								for($i = 1; $i <= $noOfPage; $i++){
							?>
								<option value="<?=$i?>" <? if($i==$_REQUEST['pageNo']) echo "selected";?>><?=$i?></option>
							<?php
								}
							}
							else{
								echo "<option value=\"\">0</option>";
							}
							?>
						</select>
							 of <?=$noOfPage?>]
							 <a href="report_stripe_Payment.php?pageNo=<?=$next?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>&tab=<?=$tab?>">
							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>
							<a href="report_stripe_Payment.php?pageNo=<?=$noOfPage?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>&tab=<?=$tab?>">
							<img src="images/mail-arrowlastg.gif" border=0 width="20" height="20" align="absmiddle" alt="Last Page"></a>						</td><?php } ?>
						<td width="233" align=right class="paragraph2"><?=$heading;?> per page: 
			
						<select class="paragraph"  size=1 onChange="this.form.submit()" name="maxrows">
						<? foreach($siteRowListConfig as $key=>$data){ ?>
						  <option value="<?=$key?>" <? if($_REQUEST['maxrows']==$key) echo"selected"; ?>><?=$data;?></option>
						 <? } ?>
						</select>					</td>
					</tr>
				   </tbody>
			 	</table>
						<!-- PAGING END-->						</TD>
                      </tr>
                      <? }
					  else{
					  ?>
					  <tr class="listingTable"><TD align="center" colspan="7" >No Records</TD></tr><? } ?>
                    </tbody>
                  </table>
                  <?php }
				  elseif($tab == 4){?> 
					  
					  
					  
					  
					                    
				  <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="553">
                    <TBODY>
                      <TR class="tableHeaderColor">
                        <TD width="6%" align="center" >#</TD>
                        <TD width="20%" align="center" >Name </TD>
                        <TD width="32%" align="center" >Email </TD>
                        <TD width="13%" align="center" >Brand</TD>
                        <TD width="13%" align="center" >Status</TD>
                        <TD width="16%" align="center" >Join Date</TD>                        
                      </TR>
                      <?php if($errMsg != ""){?>
                      <TR class="listingTable">
                        <TD align="center" colspan="7" ><font color="#FF0000">
                          <?=$errMsg?>
                        </font> </TD>
                      </TR>
                      <? }?>
                      <? if(count($result)>0){
					            $totalCount = 0;
					            foreach($result as $key =>$val){
								$totalCount += $val['countCon'];
								}
					  
					  ?>
					  
                      <tr class="listingTable">
                        <TD align="center" colspan="7" class="tblbackgnd"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <?
				$count = $startNo; 
				foreach($result as $key =>$val){ 
					$val['user_fname'] = htmlspecialchars(stripslashes($val['user_fname']));
					$val['user_lname'] = htmlspecialchars(stripslashes($val['user_lname']));
			?><tr  <? if(($key%2) ==1){?> bgcolor="#FFFFFF" <? } ?> >
                              <TD width="8%" height="19" align="center"><? echo $count;?></TD>
                              <TD width="19%" height="19" align="left" style="padding-left:10px;"><? echo $val['user_fname']." ".$val['user_lname'];?></TD>
                              <TD width="29%" height="19" align="left" style="padding-left:10px;"><?php echo $val['user_email'];?></TD>
                              
            <?php if($page_name!= "")
			{
				?> 
                <TD width="16%" align="center" style="padding-left:10px;"><?php echo "Jiwok"; ?></TD>
                 <TD width="11%" align="center" style="padding-left:10px;"><?php if($val['payment_status']	== '1') echo "<img src=\"images/y.gif\" width=\"14\" height=\"14\">"; else echo "<img src=\"images/n.gif\" width=\"14\" height=\"14\">"; ?></TD>
                <TD width="17%" align="center" style="padding-left:10px;"><?php echo $val['user_doj'];?></TD>
				
			<?php }
			else
			{
				?> 
                <TD width="16%" align="center" style="padding-left:10px;"><?php echo $val['brand'];?></TD>
                <TD width="11%" align="center" style="padding-left:10px;"><?php if($val['payment_status']	== 'PAID') echo "<img src=\"images/y.gif\" width=\"14\" height=\"14\">"; else echo "<img src=\"images/n.gif\" width=\"14\" height=\"14\">"; ?></TD> 
			
				 <TD width="17%" align="center" style="padding-left:10px;"><?php echo $val['join_date'];?></TD>
               <?php } ?>
                              
                             
                            </tr>
							
                            <? 
					$count++;
				}
			?>							
                        </table>
						<!-- PAGING START-->
						<table cellspacing=0 cellpadding=0 width=553 border=0 class="topColor">
                                <tbody>		
					<tr><?php if($noOfPage > 1) { ?>
						<td align="left" colspan = "6" class="leftmenu">
						<a href="report_stripe_Payment.php?pageNo=1&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>&tab=<?=$tab?>">
						<img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>
						<a href="report_stripe_Payment.php?pageNo=<?=$prev?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>&tab=<?=$tab?>">
						<img src="images/mail-arrowlg.gif" border=0 width="20" height="20" align="absmiddle" alt="Previous Page"></a>[Page 
						<select name="pageNo" class="paragraph"  onChange="form.submit()">
							<?php
							if($noOfPage){
								for($i = 1; $i <= $noOfPage; $i++){
							?>
								<option value="<?=$i?>" <? if($i==$_REQUEST['pageNo']) echo "selected";?>><?=$i?></option>
							<?php
								}
							}
							else{
								echo "<option value=\"\">0</option>";
							}
							?>
						</select>
							 of <?=$noOfPage?>]
							 <a href="report_stripe_Payment.php?pageNo=<?=$next?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>&tab=<?=$tab?>">
							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>
							<a href="report_stripe_Payment.php?pageNo=<?=$noOfPage?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>&tab=<?=$tab?>">
							<img src="images/mail-arrowlastg.gif" border=0 width="20" height="20" align="absmiddle" alt="Last Page"></a>						</td><?php } ?>
						<td width="233" align=right class="paragraph2"><?=$heading;?> per page: 
			
						<select class="paragraph"  size=1 onChange="this.form.submit()" name="maxrows">
						<? foreach($siteRowListConfig as $key=>$data){ ?>
						  <option value="<?=$key?>" <? if($_REQUEST['maxrows']==$key) echo"selected"; ?>><?=$data;?></option>
						 <? } ?>
						</select>					</td>
					</tr>
				   </tbody>
			 	</table>
						<!-- PAGING END-->						</TD>
                      </tr>
                      <? }
					  else{
					  ?>
					  <tr class="listingTable"><TD align="center" colspan="7" >No Records</TD></tr><? } ?>
                    </tbody>
                  </table>
                  
                  
                  <?php }
				  elseif($tab == 5){?>                   
				  <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="553">
                    <TBODY>
                      <TR class="tableHeaderColor">
                        <TD width="6%" align="center" >#</TD>
                        <TD width="20%" align="center" >Name </TD>
                        <TD width="32%" align="center" >Email </TD>
                        <TD width="13%" align="center" >Brand</TD>
                        <TD width="13%" align="center" >Status</TD>
                        <TD width="16%" align="center" >Unsubscribed Date</TD>                        
                      </TR>
                      <?php if($errMsg != ""){?>
                      <TR class="listingTable">
                        <TD align="center" colspan="7" ><font color="#FF0000">
                          <?=$errMsg?>
                        </font> </TD>
                      </TR>
                      <? }?>
                      <? if(count($result)>0){
					            $totalCount = 0;
					            foreach($result as $key =>$val){
								$totalCount += $val['countCon'];
								}
					  
					  ?>
					  
                      <tr class="listingTable">
                        <TD align="center" colspan="7" class="tblbackgnd"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <?
				$count = $startNo; 
				foreach($result as $key =>$val){ 
					$val['user_fname'] = htmlspecialchars(stripslashes($val['user_fname']));
					$val['user_lname'] = htmlspecialchars(stripslashes($val['user_lname']));
			?><tr  <? if(($key%2) ==1){?> bgcolor="#FFFFFF" <? } ?> >
                              <TD width="8%" height="19" align="center"><? echo $count;?></TD>
                              <TD width="19%" height="19" align="left" style="padding-left:10px;"><? echo $val['user_fname']." ".$val['user_lname'];?></TD>
                              <TD width="29%" height="19" align="left" style="padding-left:10px;"><?php echo $val['user_email'];?></TD>
                              <TD width="16%" align="center" style="padding-left:10px;"><?php echo $val['brand'];?></TD>
                              <?php if($page_name!= "")
							  {?> <TD width="11%" align="center" style="padding-left:10px;"><?php echo $val['payment_status'];?></TD><?php
							  }
							  else
							  {?>
                              <TD width="11%" align="center" style="padding-left:10px;"><?php echo $val['status'];?></TD><?php } ?>
                              
                              <TD width="17%" align="center" style="padding-left:10px;"><?php echo $val['unsubscribed_date'];?></TD>
                            </tr>
							
                            <? 
					$count++;
				}
			?>							
                        </table>
						<!-- PAGING START-->
						<table cellspacing=0 cellpadding=0 width=553 border=0 class="topColor">
                                <tbody>		
					<tr><?php if($noOfPage > 1) { ?>
						<td align="left" colspan = "6" class="leftmenu">
						<a href="report_stripe_Payment.php?pageNo=1&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>&tab=<?=$tab?>">
						<img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>
						<a href="report_stripe_Payment.php?pageNo=<?=$prev?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>&tab=<?=$tab?>">
						<img src="images/mail-arrowlg.gif" border=0 width="20" height="20" align="absmiddle" alt="Previous Page"></a>[Page 
						<select name="pageNo" class="paragraph"  onChange="form.submit()">
							<?php
							if($noOfPage){
								for($i = 1; $i <= $noOfPage; $i++){
							?>
								<option value="<?=$i?>" <? if($i==$_REQUEST['pageNo']) echo "selected";?>><?=$i?></option>
							<?php
								}
							}
							else{
								echo "<option value=\"\">0</option>";
							}
							?>
						</select>
							 of <?=$noOfPage?>]
							 <a href="report_stripe_Payment.php?pageNo=<?=$next?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>&tab=<?=$tab?>">
							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>
							<a href="report_stripe_Payment.php?pageNo=<?=$noOfPage?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>&tab=<?=$tab?>">
							<img src="images/mail-arrowlastg.gif" border=0 width="20" height="20" align="absmiddle" alt="Last Page"></a>						</td><?php } ?>
						<td width="233" align=right class="paragraph2"><?=$heading;?> per page: 
			
						<select class="paragraph"  size=1 onChange="this.form.submit()" name="maxrows">
						<? foreach($siteRowListConfig as $key=>$data){ ?>
						  <option value="<?=$key?>" <? if($_REQUEST['maxrows']==$key) echo"selected"; ?>><?=$data;?></option>
						 <? } ?>
						</select>					</td>
					</tr>
				   </tbody>
			 	</table>
						<!-- PAGING END-->						</TD>
                      </tr>
                      <? }
					  else{
					  ?>
					  <tr class="listingTable"><TD align="center" colspan="7" >No Records</TD></tr><? } ?>
                    </tbody>
                  </table>
                  <?php }
				  else{
				  if(count($result)>0)
				  {
					  $amountQuery	=	"SELECT sum( PS.payment_amount ) AS Total, PS.payment_currency
										FROM payment AS PS LEFT JOIN user_master AS UM ON UM.user_id = 																																																																																	 										PS.payment_userid left join brand_user b on b.user_id=UM.user_id 
										left join brand_master c on c.brand_master_id=b.brand_master_id										
										WHERE PS.payment_status = '1' AND (PS.version = 'stripe' OR PS.version = 'polishstripe')
										$whereSql $joinquery GROUP BY PS.payment_currency";
					  $resultAmount	=	$objDb->_getList($amountQuery);	
					  $PlanQuery	=	"SELECT sum( PS.payment_amount ) AS Total,COUNT( PS.payment_amount ) As 		 										count,PS.payment_currency,PS.payment_amount
										FROM payment AS PS LEFT JOIN user_master AS UM ON UM.user_id = 																																																																																	 										PS.payment_userid left join brand_user b on b.user_id=UM.user_id 
										left join brand_master c on c.brand_master_id=b.brand_master_id											
										WHERE PS.payment_status = '1' AND (PS.version = 'stripe' OR PS.version = 'polishstripe') 
										$whereSql $joinquery GROUP BY PS.payment_amount ORDER BY `PS`.`payment_currency`  																									 										DESC";
					  $resultPlan	=	$objDb->_getList($PlanQuery);	
					  
					
					  ?> 
                  	<fieldset>
						<legend>Payment details </legend>
                        <table width="90%" align="center" cellpadding="3" >
						<tr>
							<td width="28%" align="left"><strong>Total Payments</strong></td>
                            <td width="72%"><?=$total_condition['total_condition'];?></td>
                       	</tr>
                        <?php foreach( $resultAmount	as  $resultAmounts)
						{?>
                        <tr>
							<td align="left"><strong>Total Amount(<?=$resultAmounts[payment_currency];?>)</strong></td>
							<td><?=round($resultAmounts[Total],2);?></td>
                       	</tr>                        
                        <?php }?>
                        <tr>
                          <td colspan="2" align="left">
                          	<fieldset>
								<legend>Payment details by plan</legend>
                                <table width="90%" align="center" cellpadding="3" >
                                <tr>
                                    <td width="25%" align="Center"><strong>Amount</strong></td>
                                    <td width="37%" align="Center"><strong>Total Payments</strong></td>
                                    <td width="38%" align="Center"><strong>Total Amount</strong></td>
                                </tr>
                                <?php
                                foreach( $resultPlan	as  $resultPlans)
                                {?>
                                <tr>
                                    <td align="center"><?=$resultPlans[payment_amount]." ".$resultPlans[payment_currency];?></td>
                                    <td align="center"><?=$resultPlans[count];?></td>
                                    <td align="center"><?=round($resultPlans[Total],2)." ".$resultPlans[payment_currency];?></td>
                                </tr>
                                <?php }?>											                      
                                </table>
							</fieldset>
                           </td>
                          </tr>											                      
                        </table>
					</fieldset><br/>
                  <?php }?>                 
				  <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="553">
                    <TBODY>
                      <TR class="tableHeaderColor">
                        <TD width="6%" align="center" >#</TD>
                        <TD width="37%" align="center" >Name </TD>
                        <TD width="31%" align="center" >Amount</TD>
                        <TD width="26%" align="center" >Date</TD>
                        </TR>
                      <?php if($errMsg != ""){?>
                      <TR class="listingTable">
                        <TD align="center" colspan="4" ><font color="#FF0000">
                          <?=$errMsg?>
                        </font> </TD>
                      </TR>
                      <? }?>
                      <? if(count($result)>0){
					            $totalCount = 0;
					            foreach($result as $key =>$val){
								$totalCount += $val['countCon'];
								}
					  
					  ?>
					  
                      <tr class="listingTable">
                        <TD align="center" colspan="4" class="tblbackgnd"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <?
				$count = $startNo; 
				foreach($result as $key =>$val){ 
					$val['user_fname'] = htmlspecialchars(stripslashes($val['user_fname']));
					$val['user_lname'] = htmlspecialchars(stripslashes($val['user_lname']));
			?><tr  <? if(($key%2) ==1){?> bgcolor="#FFFFFF" <? } ?> >
                              <TD width="6%" height="19" align="center"><? echo $count;?></TD>
                              <TD width="37%" height="19" align="left" style="padding-left:10px;"><a href="javascript:;" onMouseOver="myFunction('<?=$val['user_id'].$key?>')" onClick="return overlib(INARRAY, <?=$val['user_id'].$key?>, CAPTION, '<? echo addslashes($val['user_fname'])." ".addslashes($val['user_lname']);?>');" ><? echo $val['user_fname']." ".$val['user_lname'];?></a></TD>
                              <TD width="25%" height="19" align="center" style="padding-left:10px;"><?php echo $val['payment_amount']." ".$val['payment_currency'];?></TD>
                              <TD width="32%" height="19" align="center" style="padding-left:10px;"><?php echo $val['payment_date'];?></TD>
                              </tr>
							
                            <? 
					$count++;
				}
			?>							
                        </table>
						<!-- PAGING START-->
						<table cellspacing=0 cellpadding=0 width=553 border=0 class="topColor">
                                <tbody>		
					<tr><?php if($noOfPage > 1) { ?>
						<td align="left" colspan = "6" class="leftmenu">
						<a href="report_stripe_Payment.php?pageNo=1&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>&tab=<?=$tab?>">
						<img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>
						<a href="report_stripe_Payment.php?pageNo=<?=$prev?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>&tab=<?=$tab?>">
						<img src="images/mail-arrowlg.gif" border=0 width="20" height="20" align="absmiddle" alt="Previous Page"></a>[Page 
						<select name="pageNo" class="paragraph"  onChange="form.submit()">
							<?php
							if($noOfPage){
								for($i = 1; $i <= $noOfPage; $i++){
							?>
								<option value="<?=$i?>" <? if($i==$_REQUEST['pageNo']) echo "selected";?>><?=$i?></option>
							<?php
								}
							}
							else{
								echo "<option value=\"\">0</option>";
							}
							?>
						</select>
							 of <?=$noOfPage?>]
							 <a href="report_stripe_Payment.php?pageNo=<?=$next?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>&tab=<?=$tab?>">
							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>
							<a href="report_stripe_Payment.php?pageNo=<?=$noOfPage?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>&tab=<?=$tab?>">
							<img src="images/mail-arrowlastg.gif" border=0 width="20" height="20" align="absmiddle" alt="Last Page"></a>						</td><?php } ?>
						<td width="233" align=right class="paragraph2"><?=$heading;?> per page: 
			
						<select class="paragraph"  size=1 onChange="this.form.submit()" name="maxrows">
						<? foreach($siteRowListConfig as $key=>$data){ ?>
						  <option value="<?=$key?>" <? if($_REQUEST['maxrows']==$key) echo"selected"; ?>><?=$data;?></option>
						 <? } ?>
						</select>					</td>
					</tr>
				   </tbody>
			 	</table>
						<!-- PAGING END-->						</TD>
                      </tr>
                      <? }
					  else{
					  ?>
					  <tr class="listingTable"><TD align="center" colspan="4" >No Records</TD></tr><? } ?>
                    </tbody>
                  </table>
                  <?php }?>
                     </TD>
                    </TR>
                  </TABLE>
				  </td>
                <td background="images/side2.jpg">&nbsp;</td>
              </tr>
              <tr> 
                <td width="10" height="9"><img src="images/btm_left.jpg" width="9" height="9"></td>
                <td height="9" background="images/btm_mdl.jpg"><img src="images/btm_mdl.jpg" width="9" height="9"></td>
                <td width="11" height="9"><img src="images/btm_right.jpg" width="9" height="9"></td>
              </tr>
            </table>

                </TD>
              </TR>
            </TABLE>
		</form>
          </TD>
        </TR>
		 <TR height="2">
    <TD vAlign=top align=left class="topBarColor" colspan="3">&nbsp;</TD>
  </TR>
      </TABLE>
        <?php 
			include_once("footer.php");
			for($i=0, $max=sizeof($result);$i<$max;$i++){
				//$trans_user_id	=	explode(",",$result[$i]['trans_refrns_id']);
				//$trans_user_id	= explode(',', $result[$i]['trans_refrns_id']);
				//unset($trans_user_id[0]);
				//$reffer_id		= implode(',',$trans_user_id);				
		?><div id="<?=$result[$i]['user_id'].$i?>" class="hidden_div" >
			<div class="popup_box">
				<table width="100%">
					<tr>
						<td class="boldC" align="center">User details</td>
					</tr>
                    <tr>
					<td class="tblbackgnd">
					<table width="100%" cellspacing="0" cellpadding="1">
					  <tr bgcolor="#FFFFFF">
						<td width="27%">Email : </td>
						<td width="73%"><?php echo $result[$i]['user_email'];?></td>
					  </tr>
                      <tr>
						<td width="27%">Expire Date : </td>
						<td width="73%"><?php echo $result[$i]['payment_expdate'];?></td>
					  </tr>
                      <tr bgcolor="#FFFFFF">
						<td width="27%">Reffer id : </td>
						<td width="73%"><?php echo $result[$i]['trans_refrns_id'];?></td>
					  </tr>                       
					</table>
					</td>
				</tr>                    
				<?php
				//for($j=0, $max2=sizeof($result[$i]['user_id']); $j<$max2; $j++){
					/*for($k=0, $max3=sizeof($programs[$result[$i]['user_id']]); $k<$max3; $k++){
				?><tr>
					<td class="tblbackgnd" colspan="2">
					<table width="100%" cellspacing="0" cellpadding="1">
					  <tr <? if(($k%2) ==1){?> bgcolor="#FFFFFF" <? } ?>>
						<td width="70%"><?=$programs[$result[$i]['user_id']][$k]['program_title']?></td>
						<td width="30%"><?=$programs[$result[$i]['user_id']][$k]['subscribed_date']?></td>
					  </tr>
					</table>
					</td>
				</tr>
				<?
					}*/
				//} 
				?></table>
			</div>
		</div>
		<? 
			}
		?></body>
</html>

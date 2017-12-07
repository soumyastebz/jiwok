<?php
include_once('includeconfig.php');
include_once("../includes/classes/class.faq.php");
include_once('../includes/classes/class.trainer.php');
/*
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);
*/
if($_REQUEST['langId'] != ""){
	$lanId = $_REQUEST['langId'];
}else{
	$lanId = 1;
}
$languageArray = $siteLanguagesConfig;
reset($languageArray);

$objFaq      =   new Faq($lanId);
$objGen      =	 new General();
$objTrainer	 = 	 new Trainer($lanId);
$objDb       =   new DbAction();

$heading = "Adword Report";
$countriesArray = $objTrainer->_getCountries();

if(isset($_REQUEST['param'])){
	extractParams($_REQUEST['param']);
}


if($_REQUEST["orderData"]!=""){
	$orderData	=	$_REQUEST["orderData"];
}
if($_REQUEST["orderEle"]!=""){
	$orderEle	=	$_REQUEST["orderEle"];
}

$flrtVal	=	"";

if($_REQUEST['dropdown1']!=""){
	if($_REQUEST['dropdown1']!="0"){
		$fltrCond	=	" and a.referred_medium = '".$_REQUEST['dropdown1']."' ";
		$flrtVal	=	$_REQUEST['dropdown1'];
	}
}



function getAmoundFromResponse($resData){
	$resData	=	base64_decode($resData);
	$resAry		=	unserialize($resData);
	
	$resAry		=	array();
	
	
	$resAry	[]	=	($resAry['MONTANT']/100);
	
	if($resAry['DEVISE']=='840'){
		$resAry[]	=	"Doller";
	}else{
		$resAry[]	=	"Euro";
	}
	
	
}







$param	= '';	
//for generating the month and year specified report for the members and the download
$todayCalendar = getdate();
//for generating the month and year specified report for the members and the download
$today = getdate();
if($_POST['year']){
	$currentYear =  $_POST['year'];
	$param	.=	'&year='.$_POST['year']; }
else
    $currentYear = date('Y');	 

if($_POST['month']){
	$currentMonth =  $_POST['month'];
	$param	.=	'&month='.$_POST['month'];}
else
	$currentMonth = date('m');
	
//$sqlQry	=	"select (select count(*) from jiwok_referral_shares b where b.user_id=a.user_id and b.media=1 #SHARECOND# ) as fbcnt,(select count(*) from jiwok_referral_shares b where b.user_id=a.user_id and b.media=2 #SHARECOND#) as twcnt,(select count(*) from jiwok_referral_mails c where c.user_id = a.user_id and mailId!='' #MAILCOND#) as mailcnt,(select count(*) from jiwok_referrals d where d.referrer_user_id=a.user_id #JOINCOND#) as joincnt,a.user_id as userid,a.user_fname,a.user_lname,a.user_alt_email AS user_email from user_master a";	

//$sqlStart	=	"select a.*,CONCAT(IFNULL((select sum(b.payment_amount) from payment b where 1=1 and b.payment_userid=a.user_id and b.payment_status=1 and payment_currency='Euro' #paidCond#),0),' ','EURO') as amtPaid,c.user_id as userid,c.user_fname,c.user_lname,c.user_alt_email AS user_email  ";


$sqlStart	=	"select a.*,IFNULL((select sum(b.payment_amount) from payment b where 1=1 and b.payment_userid=a.user_id and b.payment_status=1),0) as amtPaid,c.user_id as userid,c.user_fname,c.user_lname,c.user_alt_email AS user_email  ";

$sqlStartAll =	"select IFNULL(sum(b.payment_amount),0) as amtPaid from payment b where b.payment_userid in (select a.user_id from jiwok_referrals a where a.referrer_user_id=0 #paidCond#	".$fltrCond." ) and b.payment_status=1 ";

$sqlCount	=	"select count(*) as cnt ";

$sqlPlanData	=	"SELECT concat( b.payment_amount, ' ', b.payment_currency ) AS plan, sum( b.payment_amount ) AS amt, count( * ) AS cnt
FROM `payment` b
WHERE b.payment_userid
IN (
SELECT a.user_id
FROM jiwok_referrals a
WHERE b.version = 'New' AND a.referrer_user_id =0	#paidCond# ".$fltrCond."
)    
GROUP BY concat( b.payment_amount, ' ', b.payment_currency )";
// b.version = 'New' AND
/*
$sqlPlanData	=	"SELECT concat( b.payment_amount, ' ', b.payment_currency ) as plan , sum( b.payment_amount ) as amt, count(*) as cnt
FROM `payment` b
WHERE `version` = 'New' and b.payment_userid in (select a.user_id from jiwok_referrals a where a.referrer_user_id=0 ".$fltrCond." ) #paidCond#  
GROUP BY concat( b.payment_amount, ' ', b.payment_currency )";*/

$sqlPaymSts	=	"select * from payment_transactions b where b.status='#stsData#' and b.user_id 
IN (
SELECT a.user_id
FROM jiwok_referrals a
WHERE a.referrer_user_id =0 ".$fltrCond." #paidSts#
 )";



$fromSqlTrans  = " from payment b WHERE  b.payment_userid in (select a.user_id from jiwok_referrals a where b.version = 'New'AND a.referrer_user_id=0 #paidCond#	".$fltrCond." ) and b.payment_status=1 ";



$fromSql  = " from jiwok_referrals a LEFT JOIN user_master c ON c.user_id=a.user_id ";
$whereSql = " WHERE a.referrer_user_id = 0 ".$fltrCond;
$whereSqlAll = " WHERE a.referrer_user_id = 0 ".$fltrCond;

//Total num of jiwok users
$sqlTotNum = "SELECT count(*) as count ".$fromSql.$whereSql;
// Computing the date range...
/**
*    Computing the date range
*/
// If the first drop down was selected..
if($_POST['daterange'] == 1){
$param	.=	'&daterange='.$_POST['daterange'];
if(isset($_POST['dropdownTime'])){
	$param	.=	'&dropdownTime='.$_POST['dropdownTime'];
}


	
	
	

// get today's details
$todayDetails = getdate();
$today = date('Y-m-d');
switch($_REQUEST['dropdownTime']){
	case 'today':
		//Added date filter
		$paidCond	.=	" AND  DATE(IFNULL(a.added_date,'1700-1-1'))='".$today."' ";
		//For registration count
		$countWhere	 =	" AND DATE(IFNULL(a.added_date,'1700-1-1'))='".$today."' ";
		//For getting refund and cancel
		$paidSts	.=	" AND  DATE(IFNULL(a.added_date,'1700-1-1'))='".$today."' ";
		//For select all and count 
		$whereSql 	.= 	" AND  DATE(IFNULL(a.added_date,'1700-1-1'))='".$today."' ";
		break;
	case 'yest':
		$lastDayDetails = getdate(strtotime('yesterday'));
		$yesterday		= date('Y-m-d',$lastDayDetails[0]);
		//Added date filter
		$paidCond	.=	" AND  DATE(IFNULL(a.added_date,'1700-1-1'))='".$yesterday."' ";
		//For registration count
		$countWhere	.=	" AND  DATE(IFNULL(a.added_date,'1700-1-1'))='".$yesterday."' ";
		//For getting refund and cancel
		$paidSts	.=	" AND  DATE(IFNULL(a.added_date,'1700-1-1'))='".$yesterday."' ";
		//For select all and count 
		$whereSql 	.= 	" AND  DATE(IFNULL(a.added_date,'1700-1-1'))='".$yesterday."' ";
		break;
	case 'last7':
		$sevenDayBeforeDetails = getdate(strtotime('-7 days'));
		$requiredDate		   = date('Y-m-d',$sevenDayBeforeDetails[0]);
		//Added date filter
		$paidCond	.=	" AND  DATE(IFNULL(a.added_date,'1700-1-1')) BETWEEN '".$requiredDate."' AND '".$today."'";
		//For registration count
		$countWhere	.=	" AND  DATE(IFNULL(a.added_date,'1700-1-1')) BETWEEN '".$requiredDate."' AND '".$today."'";
		//For getting refund and cancel
		$paidSts	.=	" AND  DATE(IFNULL(a.added_date,'1700-1-1')) BETWEEN '".$requiredDate."' AND '".$today."'";
		//For select all and count 
		$whereSql 	.= 	" AND  DATE(IFNULL(a.added_date,'1700-1-1')) BETWEEN '".$requiredDate."' AND '".$today."'";
		break;
	case 'thismonth':	
		$thisMonth = $todayDetails['mon'];
		$thisYear  = $todayDetails['year'];
		//Added date filter
		$paidCond	.=	"  AND MONTH(DATE(IFNULL(a.added_date,'1700-1-1'))) = '".$thisMonth."'  AND YEAR(DATE(IFNULL(a.added_date,'1700-1-1'))) = '".$thisYear."' ";
		//For registration count
		$countWhere	.=	"  AND MONTH(DATE(IFNULL(a.added_date,'1700-1-1'))) = '".$thisMonth."'  AND YEAR(DATE(IFNULL(a.added_date,'1700-1-1'))) = '".$thisYear."' ";
		//For getting refund and cancel
		$paidSts	.=	"  AND MONTH(DATE(IFNULL(a.added_date,'1700-1-1'))) = '".$thisMonth."'  AND YEAR(DATE(IFNULL(a.added_date,'1700-1-1'))) = '".$thisYear."' ";
		//For select all and count 
		$whereSql 	 .= "  AND MONTH(DATE(IFNULL(a.added_date,'1700-1-1'))) = '".$thisMonth."'  AND YEAR(DATE(IFNULL(a.added_date,'1700-1-1'))) = '".$thisYear."' ";
		break;
	case 'lastmonth':	
		$lastMonthDetails = getdate(strtotime('last month'));
		$lasMonth		  = $lastMonthDetails['mon'];
		$lasYear		  = $lastMonthDetails['year'];
		//Added date filter
		$paidCond	.=	"  AND MONTH(DATE(IFNULL(a.added_date,'1700-1-1'))) = '".$lasMonth."'  AND YEAR(DATE(IFNULL(a.added_date,'1700-1-1'))) = '".$lasYear."' ";
		//For registration count
		$countWhere	.=	"  AND MONTH(DATE(IFNULL(a.added_date,'1700-1-1'))) = '".$lasMonth."'  AND YEAR(DATE(IFNULL(a.added_date,'1700-1-1'))) = '".$lasYear."' ";
		//For getting refund and cancel
		$paidSts	.=	"  AND MONTH(DATE(IFNULL(a.added_date,'1700-1-1'))) = '".$lasMonth."'  AND YEAR(DATE(IFNULL(a.added_date,'1700-1-1'))) = '".$lasYear."' ";
		//For select all and count 		
		$whereSql 	 .= "  AND MONTH(DATE(IFNULL(a.added_date,'1700-1-1'))) = '".$lasMonth."'  AND YEAR(DATE(IFNULL(a.added_date,'1700-1-1'))) = '".$lasYear."' ";
		break;
	case 'last3month':	
		$last3MonthDetails = getdate(strtotime('-3 month'));
		$requiredDate	   = date('Y-m-d',$last3MonthDetails[0]);
		//Added date filter
		$paidCond	.=	" AND  DATE(IFNULL(a.added_date,'1700-1-1')) BETWEEN '".$requiredDate."' AND '".$today."'";
		//For registration count
		$countWhere	.=	" AND  DATE(IFNULL(a.added_date,'1700-1-1')) BETWEEN '".$requiredDate."' AND '".$today."'";
		//For getting refund and cancel
		$paidSts	.=	" AND  DATE(IFNULL(a.added_date,'1700-1-1')) BETWEEN '".$requiredDate."' AND '".$today."'";
		//For select all and count
		$whereSql 	.= 	" AND  DATE(IFNULL(a.added_date,'1700-1-1')) BETWEEN '".$requiredDate."' AND '".$today."'";
		break;
	case 'last6month':	
		$last6MonthDetails = getdate(strtotime('-6 month'));
		$requiredDate	   = date('Y-m-d',$last6MonthDetails[0]);
		//Added date filter
		$paidCond	.=	" AND  DATE(IFNULL(a.added_date,'1700-1-1')) BETWEEN '".$requiredDate."' AND '".$today."'";
		//For registration count
		$countWhere	.=	" AND  DATE(IFNULL(a.added_date,'1700-1-1')) BETWEEN '".$requiredDate."' AND '".$today."'";
		//For getting refund and cancel
		$paidSts	.=	" AND  DATE(IFNULL(a.added_date,'1700-1-1')) BETWEEN '".$requiredDate."' AND '".$today."'";
		//For select all and count
		$whereSql 	.= 	" AND  DATE(IFNULL(a.added_date,'1700-1-1')) BETWEEN '".$requiredDate."' AND '".$today."'";
		break;
	case 'lastyear':	
		$lastyear = getdate(strtotime('last year'));
		$lasYear  = $lastyear['year'];
		//Added date filter
		$paidCond	.=	" AND  YEAR(DATE(IFNULL(a.added_date,'1700-1-1')))='".$lasYear."' ";
		//For registration count
		$countWhere	.=	" AND  YEAR(DATE(IFNULL(a.added_date,'1700-1-1')))='".$lasYear."' ";
		//For getting refund and cancel
		$paidSts	.=	" AND  YEAR(DATE(IFNULL(a.added_date,'1700-1-1')))='".$lasYear."' ";
		//For select all and count
		$whereSql 	.= 	" AND  YEAR(DATE(IFNULL(a.added_date,'1700-1-1')))='".$lasYear."' ";
		break;
	case 'alltime':	
		$whereSql 	 .= "";
		break;
}


} elseif($_POST['daterange'] == 2) { // No the second drop down was selected... :)
$param	.=	'&daterange='.$_POST['daterange'];
$param	.=	'&frY='.$_POST['frY'].'&frM='.$_POST['frM'].'&frD='.$_POST['frD'];
$param	.=	'&toY='.$_POST['toY'].'&toM='.$_POST['toM'].'&toD='.$_POST['toD'];
$startDate = $_POST['frY'].'-'.$_POST['frM'].'-'.$_POST['frD'];
$endDate   = $_POST['toY'].'-'.$_POST['toM'].'-'.$_POST['toD'];
if($startDate > $endDate)
	$errorMsg[] = "Start date should be smaller than end date";
	if(count($errorMsg) == 0){
		if($startDate != $endDate){
			//Added date filter
			$paidCond	.=	" AND  DATE(IFNULL(a.added_date,'1700-1-1')) BETWEEN '".$startDate."' AND '".$endDate."'";
			//For registration count
			$countWhere	.=	" AND  DATE(IFNULL(a.added_date,'1700-1-1')) BETWEEN '".$startDate."' AND '".$endDate."'";
			//For getting refund and cancel
			$paidSts	.=	" AND  DATE(IFNULL(a.added_date,'1700-1-1')) BETWEEN '".$startDate."' AND '".$endDate."'";
			//For select all and count
			$whereSql 	.=	" AND  DATE(IFNULL(a.added_date,'1700-1-1')) BETWEEN '".$startDate."' AND '".$endDate."'";
		}else{ // Then we don't need a BETWEEN clause :)
			//Added date filter
			$paidCond	.=	" AND  DATE(IFNULL(a.added_date,'1700-1-1'))='".$startDate."' ";
			//For registration count
			$countWhere	.=	" AND  DATE(IFNULL(a.added_date,'1700-1-1'))='".$startDate."' ";
			//For getting refund and cancel
			$paidSts	.=	" AND  DATE(IFNULL(a.added_date,'1700-1-1'))='".$startDate."' ";
			//For select all and count
			$whereSql 	.= 	" AND  DATE(IFNULL(a.added_date,'1700-1-1'))='".$startDate."' ";
		}			
	}
}



if($orderData==""){
	$orderData	=	"desc";	
}

if($orderEle==""){
	$orderEle	=	"amount";	
}



$imgTpl	=	"<br /><img src='images/#imgName#'>";

if($orderEle=="amount"){
	$orderSql = " ORDER BY IFNULL((select sum(b.payment_amount) from payment b where 1=1 and b.payment_userid=a.user_id and b.payment_status=1),0) ".$orderData;
	$fbShareImg	=	str_replace('#imgName#',$orderData.".jpg",$imgTpl);
	$fbOrder	=	$orderData;
}




if(isset($_POST['report']) && $_POST['report']!='all'){
	$param	.=	'&report='.$_POST['report'];
}

if($_POST['report'] == 'country' && $_POST['user_country']!="" && $_POST['user_country']!="0"){ 
	$param	.=	'&user_country='.$_POST['user_country'];
	$whereSql .= " AND c.user_country = ". $_POST['user_country']." ";//echo $whereSql;exit;
}

if($_POST['report'] == 'act' || $_POST['report'] == 'inac'){
	if($_POST['report'] == 'act')
	   $chkCondition = '1';
	if($_POST['report'] == 'inac')
	  $chkCondition = '2';
	 //$whereSql .= " AND user_master.user_status = ".$chkCondition; 
}	


$orderSql	=	str_replace("#paidCond#",$paidCond,$orderSql);
$sqlStart	=	str_replace("#paidCond#",$paidCond,$sqlStart);
$sqlStartAll	=	str_replace("#paidCond#",$paidCond,$sqlStartAll);
$sqlTrnsAll		=	$sqlCount.$fromSqlTrans;
$sqlTrnsAll		=	str_replace("#paidCond#",$paidCond,$sqlTrnsAll);
$sqlPlanData	=	str_replace("#paidCond#",$paidCond,$sqlPlanData);

$sqlPaymSts		=	str_replace("#paidSts#",$paidSts,$sqlPaymSts);


$sqlPaymtRefunt	=	str_replace("#stsData#","REFUND",$sqlPaymSts);
$sqlPaymtCancel	=	str_replace("#stsData#","CANCELLED",$sqlPaymSts);


//sqlPaymSts"stsData";

$paymtCancelData	=	$objDb->_getList($sqlPaymtCancel);


$paymtRefuntData	=	$objDb->_getList($sqlPaymtRefunt);

$refAry	=	array("Doller"=>0,"Euro"=>0);
$refCnt	=	0;
foreach($paymtRefuntData as $refndItem){
	$dataAry	=	getAmoundFromResponse($refndItem["details"]);
	$refAry[$dataAry[1]]	+=	$dataAry[0];
	$refCnt++;
}

$cancelAry	=	array("Doller"=>0,"Euro"=>0);
$cancelCnt	=	0;
foreach($paymtRefuntData as $refndItem){
	$dataAry	=	getAmoundFromResponse($refndItem["details"]);
	$cancelAry[$dataAry[1]]	+=	$dataAry[0];
	$cancelCnt++;
}


$PlanData		=	$objDb->_getList($sqlPlanData);

$trnsCntData		=	$objDb->_getList($sqlTrnsAll);

$regCntData		=	$objDb->_getList($sqlCount.$fromSql.$whereSql.$countWhere);
//print_r($sql);
//$sqlStartAll	.=	$fromSql.$whereSql;

$resultAll=$objDb->_getList($sqlStartAll);

//print_r($resultAll);

//*************************** Countrywice report for the users starts here ****************************

$sql = $sqlStart.$fromSql.$whereSql." GROUP BY a.user_id ".$orderSql;
$sql_count	= 'SELECT COUNT(*) as count '.$fromSql.$whereSql;

//echo $sql;

//$result=$objDb->_getList($sql);

$selected_record_count	= $objDb->_getList($sql_count);
$totalRecs = $selected_record_count[0]['count'];
//***************************report section ends here *****************************	

/* Following Code is for doing paging 	*/
$param	= substr($param, 1);
$param	= base64_encode($param);
if(!$_REQUEST['maxrows'])
	$_REQUEST['maxrows'] = $_POST['maxrows'];
if($_REQUEST['pageNo']){
	if($_REQUEST['pageNo']*$_REQUEST['maxrows'] >= $totalRecs+$_REQUEST['maxrows']){
		$_REQUEST['pageNo'] = 1;
	}
	//$result =  $objHomepage->_showPage($totalRecs,$_REQUEST['pageNo'],$_REQUEST['maxrows'],$field,$type,$searchQuery);
	$fromLimit = $_REQUEST['maxrows']*($_REQUEST['pageNo'] - 1);
	$toLimit = $_REQUEST['maxrows'];
	$sql.= " LIMIT {$fromLimit}, {$toLimit} ";
	$result=$objDb->_getList($sql);
}else{
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
}else{
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
}else
	$prev = $_REQUEST['pageNo']-1;
if($_REQUEST['pageNo'] == $noOfPage){
	$next = $_REQUEST['pageNo'];
}else
	$next = $_REQUEST['pageNo']+1;
////////////////////////////////////Pagination ends here/////////////////////////////////////////


if($totalRecs <= 0)
	$errMsg = "No Records";
$resultNum=$objDb->_getList($sqlTotNum);
//percentage of members
$memPercentage=round(($totalRecs/$resultNum[0]['count'])*100,2);
//*************************** Countrywice report for the users Ends  here *****************************	
if($memPercentage == '')
	$memPercentage=0;
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

/**************payment details of users through choosen medium******/
//echo $whereSql;exit;
$myquery	="SELECT b.payment_amount,a.referred_medium, c.user_fname,c.user_lname,c.user_alt_email AS user_email FROM 

payment  AS b   LEFT JOIN jiwok_referrals AS  a ON (a.user_id = b.payment_userid AND 

a.referrer_user_id	='0' ) LEFT JOIN user_master AS c ON c.user_id=a.user_id 

 ". $whereSql .$fltrCond. " and b.payment_status=1
 GROUP BY a.user_id ";// 
$myqueryCount	=	"SELECT COUNT(b.payment_amount) as count FROM payment  AS b   LEFT JOIN jiwok_referrals 

AS  a ON (a.user_id = b.payment_userid AND a.referrer_user_id	='0' ) LEFT JOIN 

user_master AS c ON c.user_id=a.user_id WHERE b.payment_status=1 ".$fltrCond."
 GROUP BY a.user_id ";

$myquery_totalRecs = mysql_num_rows(mysql_query($myqueryCount));

/* Following Code is for doing paging 	*/
$_POST['maxrows1']	=	10;
if(!$_REQUEST['maxrows1'])
	$_REQUEST['maxrows1'] = $_POST['maxrows1'];
if($_REQUEST['pageNo1']){
	if($_REQUEST['pageNo1']*$_REQUEST['maxrows1'] >= $myquery_totalRecs+$_REQUEST['maxrows1']){
		$_REQUEST['pageNo1'] = 1;
	}
	//$result =  $objHomepage->_showPage($totalRecs,$_REQUEST['pageNo'],$_REQUEST['maxrows'],$field,$type,$searchQuery);
	$fromLimit1 = $_REQUEST['maxrows1']*($_REQUEST['pageNo1'] - 1);
	$toLimit1 = $_REQUEST['maxrows1'];
	$myquery.= " LIMIT {$fromLimit1}, {$toLimit1} ";
	$result1=$objDb->_getList($myquery);
}else{
	/***********************Selects Records at initial stage***********************************************/
	$_REQUEST['pageNo1'] = 1;
	//$result = $objHomepage->_showPage($totalRecs,$_REQUEST['pageNo'],$_REQUEST['maxrows'],$field,$type,$searchQuery);
	$fromLimit1 = $_REQUEST['maxrows1']*($_REQUEST['pageNo1'] - 1);
	$toLimit1 = $_REQUEST['maxrows1'];
	$myquery.= " LIMIT {$fromLimit1}, {$toLimit1} ";
	$result1=$objDb->_getList($myquery);
	/*echo count($result);*/
	}
	if(count($result1) <= 0){ 
		$errMsg1 = "No Records.";
}


//echo "<pre/>";print_r($result1);exit;
if($myquery_totalRecs <= $_REQUEST['pageNo1']*$_REQUEST['maxrows1']){
	//For showing range of displayed records.
	if($myquery_totalRecs <= 0)
		$startNo1 = 0;
	else
		$startNo1 = $_REQUEST['pageNo1']*$_REQUEST['maxrows1']-$_REQUEST['maxrows1']+1;
	$endNo1 = $myquery_totalRecs;
	$displayString = "Viewing $startNo1 to $endNo1 of $endNo1 Homepage";
}else{
	//For showing range of displayed records.
	if($myquery_totalRecs <= 0)
		$startNo1 = 0;
	else
		$startNo1 = $_REQUEST['pageNo1']*$_REQUEST['maxrows1']-$_REQUEST['maxrows1']+1;
	$endNo1 = $_REQUEST['pageNo1']*$_REQUEST['maxrows1'];
	$displayString = "Viewing $startNo1 to $endNo1 of $myquery_totalRecs homepage";
}
//Pagin 
$noOfPage1 = @ceil($myquery_totalRecs/$_REQUEST['maxrows1']); 
if($_REQUEST['pageNo1'] == 1){
	$prev1 = 1;
}else
	$prev1 = $_REQUEST['pageNo1']-1;
if($_REQUEST['pageNo1'] == $noOfPage1){
	$next1 = $_REQUEST['pageNo1'];
}else
	$next1 = $_REQUEST['pageNo1']+1;
//----------------------------------Pagination ends here
if($myquery_totalRecs <= 0)
	$errMsg1 = "No Records"; 
/************************************************************/

?>	

<HTML><HEAD><TITLE><?=$admin_title?></TITLE>

<link href="./js/jscalendar/calendar-blue.css" rel="stylesheet" type="text/css" media="all">

<script language="javascript" src="./js/jscalendar/calendar.js"></script>

<script language="javascript" src="./js/jscalendar/calendar-en.js"></script>

<script language="javascript" src="./js/jscalendar/calendar-setup.js"></script>

<script language="javascript">

//for submiting the form

function orderReferralData(orderElement,orderData){
	
	if(orderData=="asc"){
		orderData	=	"desc";
	}else{
		orderData	=	"asc";	
	}
	
	document.getElementById("orderEle").value	=	orderElement;
	document.getElementById("orderData").value	=	orderData;
	
	document.reportFrm.submit();
	
}


function change() {

    document.reportFrm.submit();

	var $data = document.getElementById("report").value; 

		if($data == 'country'){

		 document.getElementById("countryDisplay").style.display="block";

		}

	



}

function chkValue() {

   	var $value = document.getElementById("dropdown1").value; 

	if($value == 'selectperiod'){

	document.getElementById("daterange2").checked=true;

	document.getElementById("periodDisplay").style.display="block";

	}	

    else

	document.reportFrm.submit();

}

chkValue

</script>

<? include_once('metadata.php');?>

<BODY class="bodyStyle">

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

                       

			   <form name="reportFrm" action="#" method="post">

                        

				  <table width=553 height="227" border=0 cellpadding=0 cellspacing=0 class="paragraph2">

				  <tr>

						<td height="4" colspan="4" align="center" valign="bottom" class="sectionHeading"><?=$heading;?></td>

					</tr>

				    

				  <tr>

				    <td colspan="2" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;					</td>


				    <td width="117" height="27" align="center" valign="bottom" class="sectionHeading"><a href="excel_mem.php?inf=<? echo base64_encode($sql);?>"><img src="../images/sports/english/download.gif" style="float:right" border="0"></a></td>

				  </tr>

					

				  <tr style="padding-top:10px;">

				    

				    <td width="191" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>

				    <td width="147" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>

				    <td width="117" height="19" align="center" valign="bottom" class="sectionHeading">&nbsp;</td>

				  </tr>

				  <!--<tr style="padding-top:10px;">

				    

				    <td width="191" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>

				    <td width="147" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>

				    <td width="117" height="16" align="center" valign="bottom" class="sectionHeading"><input name="image" type="image"  style="float:right;"  onClick="this.form.submit" src="../images/sports/english/generate.jpg"></td>

				  </tr>-->

				  

				  <tr style="padding-top:10px;">

				    <td height="21" colspan="3" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;"></td>

				    </tr>

				  <tr style="padding-top:10px;">

				  <td  colspan="3" align="left" valign="bottom">

				  <div id="dateDisplay">

				  <table>
                  <tr><td>
					  <span class="successAlert">
                      <select name="dropdown1" id="dropdown2" onChange="document.reportFrm.submit();">
                      <option value="0" <?php if($flrtVal=="0"){?>selected<?php } ?>>All</option>
                      <option value="adwords" <?php if($flrtVal=="adwords"){?>selected<?php } ?>>adwords</option>
                      <option value="fbcampaign" <?php if($flrtVal=="fbcampaign"){?>selected<?php } ?>>fbcampaign</option>
                      <option value="coaching sportif" <?php if($flrtVal=="coaching sportif"){?>selected<?php } ?>>coaching sportif</option>
                      <option value="coaching course à pied" <?php if($flrtVal=="coaching course à pied"){?>selected<?php } ?>>coaching course à pied</option>
                      <option value="Entrainement Marathon" <?php if($flrtVal=="Entrainement Marathon"){?>selected<?php } ?>>Entrainement Marathon</option>
                      <option value="entrainement semi-marathon" <?php if($flrtVal=="entrainement semi-marathon"){?>selected<?php } ?>>entrainement semi-marathon</option>
                      <option value="Coaching perte de poids générique" <?php if($flrtVal=="Coaching perte de poids générique"){?>selected<?php } ?>>Coaching perte de poids générique</option>
                      <option value="Course à pied perte de poids" <?php if($flrtVal=="Course à pied perte de poids"){?>selected<?php } ?>>Course à pied perte de poids</option>
                      <option value="Email perte poids socialweb" <?php if($flrtVal=="Email perte poids socialweb"){?>selected<?php } ?>>Email perte poids socialweb</option>
                      <option value="Entrainement salle de sport générique" <?php if($flrtVal=="Entrainement salle de sport générique"){?>selected<?php } ?>>Entrainement salle de sport générique</option>
                      <option value="Entrainement tapis de course" <?php if($flrtVal=="Entrainement tapis de course"){?>selected<?php } ?>>Entrainement tapis de course</option>
                      <option value="Entrainement tapis marche" <?php if($flrtVal=="Entrainement tapis marche"){?>selected<?php } ?>>Entrainement tapis marche</option>
                      <option value="Entrainement vélo d'appartement" <?php if($flrtVal=="Entrainement vélo d'appartement"){?>selected<?php } ?>>Entrainement vélo d'appartement</option>
                      <option value="Entrainement vélo elliptique" <?php if($flrtVal=="Entrainement vélo elliptique"){?>selected<?php } ?>>Entrainement vélo elliptique</option>
                      <option value="fb_sport" <?php if($flrtVal=="fb_sport"){?>selected<?php } ?>>fb_sport</option>
                      <option value="fb_forme" <?php if($flrtVal=="fb_forme"){?>selected<?php } ?>>fb_forme</option>
                      </select>

				       </span>
                   	   </td></tr>
                  
                  <tr><td><span class="successAlert">Choose date range </span></td></tr>
                  <tr> 

					<td height="21" colspan="3" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;"><span class="successAlert">

				      <input name="daterange" id="daterange1" type="radio" value="1" <? if($_POST['daterange'] ==1 or $_POST['daterange'] == '') echo 'checked="checked"';?> >
                      <select name="dropdownTime" id="dropdown1" onChange="chkValue();">

                        <?

							$str = '';

							foreach($dropDownArray as $key => $value){

								$str .= '<option value="'.$key.'"';

								if($_POST['dropdownTime'] == $key)

									$str .= ' selected = "selected"';

								$str .= '>'.$value.'</option>';

							}

							echo $str;

						 

						?>

                      </select>
                      </span>
                      </td></tr></table>

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

								if($_POST['toD'] == $day_value)

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

							for($i=$todayCalendar['year']-5;$i<=$todayCalendar['year'];$i++){

								$str .= '<option value="'.$i.'"';

								if($_POST['frY'] == "" and $i == $todayCalendar['year'])

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

							for($i=$todayCalendar['year']-5;$i<=$todayCalendar['year'];$i++){

								$str .= '<option value="'.$i.'"';

								if($_POST['toY'] == "" and $i == $todayCalendar['year'])

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

				    <td height="21" colspan="3" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>

				    </tr>

				  

				  <tr style="padding-top:10px;">

				    <td height="21" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">Search For </td>

				    <td width="147" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>

				    <td width="117" height="4" align="center" valign="bottom" class="sectionHeading">&nbsp;</td>

				  </tr>

				  <tr style="padding-top:10px;">

				    <td height="24" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">

					<select id="report" name="report" onChange="change();">

					   <option value="all" <? if($_REQUEST['report']=='all') echo 'selected="selected"'?> >Whole</option>

					   <option value="country" <? if($_REQUEST['report']=='country') echo 'selected="selected"'?>>Per country</option>

					   <option value="subc" <? if($_REQUEST['report']=='subc') echo 'selected="selected"'?>>Not Subscribed Yet</option>

					   <option value="down" <? if($_REQUEST['report']=='down') echo 'selected="selected"'?>>Not Downloaded Yet</option>

					   <option value="inac" <? if($_REQUEST['report']=='inac') echo 'selected="selected"'?>>Inactive Members</option>

					   <option value="act" <? if($_REQUEST['report']=='act') echo 'selected="selected"'?>>Active Members</option>

				       </select>	</td>

				    <td width="147" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>

				    <td width="117" height="4" align="center" valign="bottom" class="sectionHeading">&nbsp;</td>

				  </tr>

				  <? if($_REQUEST['report']=='country') {?>

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

				  <? }?>

					<tr>

				    

				    <td colspan="4" height="20px"></td>
                    </tr>
			
				  <tr>

				    

				    <td colspan="4">
                    <table>
                    <tr>
                      	<td><b>Number of registration</b></td><td><?php echo $regCntData[0]["cnt"]; ?></td>
                      </tr>
                      <tr>
                      	<td><b>Number of transaction</b></td><td><?php echo $trnsCntData[0]["cnt"]; ?></td>
                      </tr>
                      <?php if(1){ ?>
                       <tr>
                      	<td><b>Transaction by plan</b></td><td></td>
                      </tr>
                      <tr>
                      	<td colspan="2" align="center">
                        	<table width="70%">
                            <tr><td>Plan</td><td>Count</td></tr>
                            <?php foreach($PlanData as $planItem){ ?>
                            	<tr><td><?php echo $planItem["plan"]; ?></td><td><?php echo $planItem["cnt"]; ?></td></tr>
                            <?php } ?>
                            </table>
                        </td>
                      </tr>
                      <?php } ?>
                      
                      
                      
                      
					  <tr>
                      	<td><b>Refund </b></td><td><?php echo "AMOUNT - ".$refAry["Euro"]." Euro + ".$refAry["Doller"]." Doller"; echo " |  COUNT - ".$refCnt; ?></td>
                      </tr>
                      <tr>
                      	<td><b>Cancel </b></td><td><?php echo "AMOUNT - ".$cancelAry["Euro"]." Euro + ".$cancelAry["Doller"]." Doller"; echo " |  COUNT - ".$cancelCnt; ?></td>
                      </tr>
                      <tr>
                      	<td colspan="2"></td>
                      </tr>
                    
                    </table>
                    
                    </td>

				    </tr>

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

					<TD height="2" colspan="4" align="left">					</TD>

					</TR>

				  </table><br/>

                  <TABLE cellSpacing=1 cellPadding=2 width="553">

                    <TBODY>

                      <TR >

                        <TD width="34%" align="left" class="sectionHeading"><!--Percentage of members :--></TD>

                        <TD width="66%" align="left" class="sectionHeading"><? //echo $memPercentage;?><!--% --></TD>

                      </TR>

                      </tbody>

                  </table><br/>  
                  
                 
                  
                  
                  
                  
				<input type="hidden" id="orderEle" name="orderEle" value="<?php echo $orderEle; ?>" />		                 
                <input type="hidden" id="orderData" name="orderData" value="<?php echo $orderData; ?>" />
				  <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="553">

                    <TBODY>

                   

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

					  <tr><td>
                      
                      
                      </td></tr>
                      
                      
                      
                      <tr class="listingTable">

                        <TD align="center" colspan="7" class="tblbackgnd"><table width="100%" border="0" cellspacing="0" cellpadding="0">
<TR class="tableHeaderColor">

                        <TD width="6%" align="center" >#</TD>

                        <TD width="21%" align="center" >Member Name </TD>

                        <TD width="26%" align="center" >Email </TD>
                        
                        <TD width="17%" align="center" >Through </TD>
                        <TD width="20%" align="center" >Date</TD>

                        <TD width="10%" align="center" ><a href="javascript:orderReferralData('amount','<?php echo $fbOrder; ?>');">Amount Paid <?php echo $fbShareImg; ?></a></TD>
                       

                      </TR>
							<?php
							if(count($resultAll)>0){
							?>
<tr>

                              <TD width="6%" height="19" align="center"></TD>

                              <TD width="21%" height="19" align="left" style="padding-left:10px;">TOTAL</TD>
                              <TD width="26%" height="19" align="left" style="padding-left:10px;"></TD>
                              <TD width="17%" height="19" align="left" style="padding-left:10px;"></TD>
                              <TD width="20%" height="19" align="left" style="padding-left:10px;"></TD>
                              <TD width="10%" height="19" align="left" style="padding-left:10px;"><? echo $resultAll[0]['amtPaid']?></TD>
                          

                            </tr>
                            
                            
                            <?
							}

							$count = $startNo;

							 foreach($result as $key =>$val){  ?>

                            <tr  <? if(($key%2) ==0){?> bgcolor="#FFFFFF" <? } ?> >

                              <TD width="6%" height="19" align="center"><? echo $count;?></TD>

                              <TD width="21%" height="19" align="left" style="padding-left:10px;"><? echo $val['user_fname']." ".$val['user_lname'];?></TD>
                              <TD width="26%" height="19" align="left" style="padding-left:10px;"><? echo $val['user_email']?></TD>
                              <TD width="17%" height="19" align="left" style="padding-left:10px;"><?php if($val['referred_medium'] == "" ) echo 'coaching sportif'; else echo $val['referred_medium'];?></TD>
                              <TD width="20%" height="19" align="left" style="padding-left:10px;"><? echo $val['added_date']?></TD>
                              <TD width="10%" height="19" align="left" style="padding-left:10px;"><? echo $val['amtPaid']?></TD>


                            </tr>

							

                            <? 

							$count++;

							}?>

							

                        </table>

						<table cellspacing=0 cellpadding=0 width=553 border=0 class="topColor">

                                <tbody>		

					<tr><?php if($noOfPage > 1) { ?>

						<td align="left" colspan = "6" class="leftmenu">

						<a href="report_addword.php?pageNo=1&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>">

						<img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>

						<a href="report_addword.php?pageNo=<?=$prev?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>">

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

							 <a href="report_addword.php?pageNo=<?=$next?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>">

							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>

							<a href="report_addword.php?pageNo=<?=$noOfPage?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>">

							<img src="images/mail-arrowlastg.gif" border=0 width="20" height="20" align="absmiddle" alt="Last Page"></a>

						</td><?php } ?>

						<td width="233" align=right class="paragraph2"><?=$heading;?> per page: 

			

						<select class="paragraph"  size=1 onChange="this.form.submit()" name="maxrows">

						<? foreach($siteRowListConfig as $key=>$data){ ?>

						  <option value="<?=$key?>" <? if($_REQUEST['maxrows']==$key) echo"selected"; ?>><?=$data;?></option>

						 <? } ?>

						</select>

					</td>

					</tr>

				   </tbody>

			 	</table>

						

						</TD>

                      </tr>

                      <? }?>

                    </tbody>
                        
                    <!------------------------------------------NEw--------->
                     <table width=553  border=0 cellpadding=0 cellspacing=0 class="paragraph2">
				  <tr>
						<td height="4" colspan="4" align="center" valign="bottom" class="sectionHeading"><?=$heading_sub;?></td>
					</tr></table><br/><br/>
                    <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="553">

                    <TBODY>

                   

                      <?php if($errMsg1 != ""){?>

                      <TR class="listingTable">

                        <TD align="center" colspan="4" ><font color="#FF0000">

                          <?=$errMsg1?>

                        </font> </TD>

                      </TR>

                      <? }?>

                      <? if(count($result1)>0){

					            $totalCount = 0;

					            foreach($result1 as $key =>$val){

								$totalCount += $val['countCon'];

								}

					  

					  ?>

					  <tr><td>
                      
                      
                      </td></tr>
                      
                      
                      
                      <tr class="listingTable">

                        <TD align="center" colspan="7" class="tblbackgnd"><table width="100%" border="0" cellspacing="0" cellpadding="0">
<TR class="tableHeaderColor">

                        <TD width="6%" align="center" >#</TD>

                        <TD width="21%" align="center" >Member Name </TD>

                        <TD width="26%" align="center" >Email </TD>
                        
                        <TD width="17%" align="center" >Through </TD>
                       

                        <TD width="10%" align="center" >Amount Paid</TD>
                       

                      </TR>
                            <?
						
							$count = $startNo1;

							 foreach($result1 as $key =>$val){  ?>

                            <tr  <? if(($key%2) ==0){?> bgcolor="#FFFFFF" <? } ?> >

                              <TD width="6%" height="19" align="center"><? echo $count;?></TD>

                              <TD width="21%" height="19" align="left" style="padding-left:10px;"><? echo $val['user_fname']." ".$val['user_lname'];?></TD>
                              <TD width="26%" height="19" align="left" style="padding-left:10px;"><? echo $val['user_email']?></TD>
                              <TD width="17%" height="19" align="left" style="padding-left:10px;"><?php if($val['referred_medium'] == "" ) echo 'coaching sportif'; else echo $val['referred_medium'];?></TD>
                              
                              <TD width="10%" height="19" align="left" style="padding-left:10px;"><? echo $val['payment_amount']?></TD>


                            </tr>

							

                            <? 

							$count++;

							}?>

							

                        </table>

						<table cellspacing=0 cellpadding=0 width=553 border=0 class="topColor">

                                <tbody>		

					<tr><?php if($noOfPage1 > 1) { ?>

						<td align="left" colspan = "6" class="leftmenu">

						<a href="report_addword.php?pageNo1=1&langId=<?=$lanId?>&maxrows1=<?=$_REQUEST['maxrows1']?>&param=<?=$param?>">

						<img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>

						<a href="report_addword.php?pageNo1=<?=$prev1?>&langId=<?=$lanId?>&maxrows1=<?=$_REQUEST['maxrows1']?>&param=<?=$param?>">

						<img src="images/mail-arrowlg.gif" border=0 width="20" height="20" align="absmiddle" alt="Previous Page"></a>[Page 


						
                        
						<select name="pageNo1" class="paragraph"  onChange="form.submit()">

							<?php

							if($noOfPage1){

								for($i = 1; $i <= $noOfPage1; $i++){

							?>

								<option value="<?=$i?>" <? if($i==$_REQUEST['pageNo1']) echo "selected";?>><?=$i?></option>

							<?php

								}

							}

							else{

								echo "<option value=\"\">0</option>";

							}

							?>

						</select>

							 of <?=$noOfPage1?>]

							 <a href="report_addword.php?pageNo1=<?=$next1?>&langId=<?=$lanId?>&maxrows1=<?=$_REQUEST['maxrows1']?>&param=<?=$param?>">

							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>

							<a href="report_addword.php?pageNo1=<?=$noOfPage1?>&langId=<?=$lanId?>&maxrows1=<?=$_REQUEST['maxrows1']?>&param=<?=$param?>">

							<img src="images/mail-arrowlastg.gif" border=0 width="20" height="20" align="absmiddle" alt="Last Page"></a>

						</td><?php } ?>

						<td width="233" align=right class="paragraph2"><?=$heading;?> per page: 

			

						<select class="paragraph"  size=1 onChange="this.form.submit()" name="maxrows1">

						<? foreach($siteRowListConfig as $key=>$data){ ?>

						  <option value="<?=$key?>" <? if($_REQUEST['maxrows1']==$key) echo"selected"; ?>><?=$data;?></option>

						 <? } ?>

						</select>

					</td>

					</tr>

				   </tbody>

			 	</table>

						

						</TD>

                      </tr>

                      <? }?>

                    </tbody>
             
                    
                    <!-------------------------------------------NEW--------->
                  </table>

				  

				</form>

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



          </TD>

        </TR>

		 <TR height="2">

    <TD vAlign=top align=left class="topBarColor" colspan="3">&nbsp;</TD>

  </TR>

      </TABLE>

        <?php include_once("footer.php");?>

</body>

</html>
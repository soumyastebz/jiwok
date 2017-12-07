<?php
include_once('includeconfig.php');
include_once('../includes/classes/Referrel/class.referal.php');

$heading = $_REQUEST['heading'];
$errorMsg	=	array();

$genObj   =	new General();
$dbObj    =	new DbAction();
$referral    =	new referal();

$startData	=	'1970-1-1';
if($_REQUEST["sd"]!=""){
	$startData	=	$_REQUEST["sd"];	
}

$endData	=	date('Y-m-d');
if($_REQUEST["ed"]!=""){
	$endData	=	$_REQUEST["ed"];	
}

$lanId	=	2;
if($_REQUEST["lanid"]!=""){
	$lanId	=	$_REQUEST["lanid"];	
}

$endData	=	date('Y-m-d',strtotime ( '1 day' , strtotime($endData) ));
//echo $startData."-----".$endData."------".$lanId;


//Get all referral mails from DB
$mailRefData	=	$referral->getRefMailIds($startData,$endData,$lanId);

echo "<u>Referral mailids (".count($mailRefData).").</u><br /><br />";

foreach($mailRefData as $refMailId){
	echo $refMailId['mailId']."<br />";
}
?>
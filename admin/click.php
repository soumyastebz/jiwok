<?php
/**
 * Registers click, saves cookies and compute per click commissions - if any
 */

//require_once 'bootstrap.php';
//@include_once('../include/Compiled/Click.php');
//Gpf_Session::create(new Pap_Tracking_ModuleBase(), null, false);

//include_once '../../papGlobal.php';
	
//$tracker = Pap_Tracking_ClickTracker::getInstance();
//$tracker->track();

$a_aid 		= trim($_GET['a_aid']);
$a_bid 		= trim($_GET['a_bid']);
$desturl	= trim($_GET['desturl']);
$strSep		= '';
if(strpos($desturl, '?')>0){
	$strSep		= '&';
}else{
	$strSep		= '?';
}
header("Location:".$desturl.$strSep."a_aid=".$a_aid."&a_bid=".$a_bid);

?>

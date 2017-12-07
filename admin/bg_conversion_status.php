<?
  include_once("./includes/config.php"); 
  include_once("./includes/globals.php");
  include_once('./includes/classes/class.General.php');
  include_once('./includes/classes/class.DbAction.php');

  if($_REQUEST['t'] == 'bG'){
  	
	$sql = "SELECT conversion_status
  		  	FROM conversion_status 
		  	WHERE conversion_type = 'BACKGROUND'"; 
  }elseif($_REQUEST['t'] == 'vC'){
  	$sql = "SELECT conversion_status
  		  	FROM conversion_status 
		  	WHERE conversion_type = 'VOCAL'";
  
  }
  $resConv	= $GLOBALS['db']->getRow($sql,DB_FETCHMODE_ASSOC);
  
  if($resConv['conversion_status'] == 1) {
  		echo 'Processing...';
  }elseif($resConv['conversion_status'] == 0){
  		echo 'Completed';
  }
?>

<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-reseller Details
   Programmer	::> jasmin
   Date			::> 04/02/2009
   
   DESCRIPTION::::>>>>
   This  code userd to produce invoice.
   
*****************************************************************************/
include_once('includeconfig.php');
	include("../includes/classes/class.reseller.php");
	
	/*
	 Instantiating the classes.
	*/
	if($_REQUEST['langId'] != ""){   
		$lanId = $_REQUEST['langId'];
	}
	else{
		$lanId = 1;
	}
	
	$objTesti	 = new reseller($lanId);
	$objGen  	 =	new General();
	
	if($_REQUEST['masterId'] and count($errorMsg)==0){
		$result = $objTesti->_getAllById($_REQUEST['masterId']);                                           
		}      
		$n	= 0;
		if(count($result) != 0){
			$name 	= $result[$n]['reseller_name'];
			$id 	= $result[$n]['reseller_id'];
			$email 	= $result[$n]['reseller_email'];
			$web 	= $result[$n]['reseller_web'];		
		}
		if($_REQUEST['obj'])
		{
			$details1=explode(",",$_REQUEST['obj']);
			$count1=$details1[0];
			if($details1[1]){$sum1=$details1[1];}else{$sum1=0;}
			$count2=$details1[2];
			if($details1[3]){$sum2=$details1[3];}else{$sum2=0;}
			$count3=$details1[4];
		}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<div id="printable">
<table width="557" border="0" cellspacing="0" cellpadding="0">
  <tr><th height="36" colspan="2">Reseller details</th>
  </tr>
  <tr>
    <td width="170">Name:</td>
    <td width="387"><?=$name;?></td>
  </tr>
  <tr>
    <td>ID:</td>
    <td><?=$id;?></td>
  </tr>
  <tr>
    <td height="24">E-mail:</td>
    <td><?=$email;?></td>
  </tr>
  <tr>
    <td height="41" valign="baseline">URL:</td>
    <td><?=$web;?></td>
  </tr>
  <tr>
    <td height="24">No.of Code Purchased:</td>
    <td><?=$count1;?></td>
  </tr>
  <tr>
    <td height="23">No.of Code Used:</td>
    <td><?=$count2;?></td>
  </tr>
  <tr>
    <td>Total Amount:</td>
    <td><?php echo $sum1+$sum2." Euro";?></td>
  </tr>
</table>
</div>
<div align="center"><a href="#" onclick="window.print()">Print</a></div>
</body>
</html>

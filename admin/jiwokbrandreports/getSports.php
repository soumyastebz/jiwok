<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::>Jiwok-Report
   Programmer	::> Deepa S 
   Date			::> 27/Jan/2011
   DESCRIPTION::::>>>> get all the sports according to the language passed
  
*****************************************************************************/
	include_once('includeconfig.php');
	include_once("includes/classes/class.report.php");
	
	if($_REQUEST['lan'] != ""){
		$lanId = $_REQUEST['lan'];
	}
	else{
		$lanId = 1;
	}
	
	$objReport	 = 	 new Report($lanId);
	$sports = $objReport->_getSportsArray($lanId);
	$string = '';
	if(count($sports)>0)
	{
		$string.= "<select id='sports[]' name='sports[]'  style='font-size:11px;width:212px;' multiple size='5'>";
		foreach ($sports as $key => $value)
		{
            $string.="<option value='".$key."'>".$value."</option>";
        }
		$string.="</select>";
		echo $string;
	}
	?>

	
	
<?php 
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 	::> Admin Management
   Programmer	::> Soumya.A
   Date		::> 17/01/2013
   
   DESCRIPTION::::>>>>
   Admin Language management
*****************************************************************************/
$permsnMsg	=	"";
$filename	=	basename($_SERVER['PHP_SELF']);
if($page_name!= "")
	{ 
		if($_REQUEST['langId'] == 0)
		{		
			if (in_array("5", $lang_permission))
			{
				$lanId	=	5;
			}
			elseif (in_array("4", $lang_permission))
			{
				$lanId	=	4;
			}
			elseif (in_array("3", $lang_permission))
			{
				$lanId	=	3;
			}
			elseif (in_array("2", $lang_permission))
			{
				$lanId	=	2;
			}
			elseif (in_array("1", $lang_permission))
			{
				$lanId	=	1;
			}
		}
		else
		{
			$lanId = $_REQUEST['langId'];
		
			if (!in_array($lanId, $lang_permission))
			{
				//$permsnMsg	=	"You have no permission to access this functionality";
				header("Location:$filename?permsnMsg=".base64_encode('You have no permission to access this functionality'));
				
			}
			else
			{
				$permsnMsg	=	"";
			}
		}
	}

?>
<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-Forgot mail Management
   Programmer	::>Sreejith E C
   Date			::> 03/02/2007
   
   DESCRIPTION::::>>>>
   This  code used to send forgot mail.
*****************************************************************************/
	include_once('../includes/config.php');	
	include_once('../includes/classes/class.General.php');
	$objGen = new General();
	$admin_title	="Jiwok admin password retrival";
	$errorMsg	=	array();
		
	if($_POST['Submit'])
	{
		$admin_mail = $_POST['admin_mail'];
	
		if(trim($_POST['admin_mail']) == "")
			$errorMsg = "Email id required";
		else if(!$objGen->_validate_email($_POST['admin_mail']))
			$errorMsg = "Email id does not valid";
		
		if(!$errorMsg){
	
								
				$sel_qry	=	"SELECT * FROM admin WHERE admin_email='".addslashes($admin_mail)."'";
				
				$res_qry	=	$GLOBALS['db']->getAll($sel_qry,DB_FETCHMODE_ASSOC);
				
				$num_rows	=	count($res_qry);
				
				if($num_rows>0)
				{
					foreach($res_qry as $key => $arradmin)
					{
						$mail_id=$arradmin['admin_email'];
						$username=$arradmin['admin_login'];
						$password=base64_decode($objGen->_output($arradmin['admin_pwd']));
						
						$message="<div align='center'>Password Retrieval&nbsp;&nbsp;&nbsp;</div>";
						$message.="<br><br>";
						$message.="<div align='center'>Jiwok Administrator Authentication Details</div>";
						$message.="<hr width='20%' align='center'>";			
						$message.="<div align='center'>&nbsp;&nbsp;&nbsp;Username: <font color=#0D2048><b>$username</b>&nbsp;&nbsp;&nbsp;</font></div>";
						$message.="<div align='center'>Password: <font color=#0D2048><b>$password</b></font></div>";
						$subject="Password Retrieval";
						
						#----------------Mail functionality-----------------------
						$headers  = "MIME-Version: 1.0\r\n";
						$headers .= "Content-type: text/html; charset=UTF-8;\r\n";
						$headers .= "From: Jiwok Team";
						//print $message;
						mail ("$mail_id","$subject","$message","$headers") or die("Mail send Error!!");
						#----------------Mail functionality-----------------------
						$msg= "<font class='successAlert'> Your login details will be mailed to the provided e-mail address.</font>";
					}
				}
				else 
				{
					$msg = "<font class='Summary'>The email address does not match any record in our database.</font><br><br>";
					$msg.= "<a href='send_password.php?admin_mail=".$objGen->_output($_POST['admin_mail'])."' class='successAlert'>Please Try Again</a>"; 
				}
		}else{
					$msg = "<font class='Summary'>$errorMsg</font><br><br>";
					$msg.= "<a href='send_password.php?admin_mail=".$objGen->_output($_POST['admin_mail'])."' class='successAlert'>Please Try Again</a>"; 
				}
		
		
		
		
		
		
	}
?>
<html>
<head>
<title><?=$admin_title?></title>
<? include_once('metadata.php');?>
</head>
<body>
<table align="center" width="80%" border="1" cellspacing="0">
  <tr>
    <td width="78%" align="center">
	  <table widht="78%" align="center" border="0" cellpadding="" cellspacing="0">
	  	<form action="sendmail.php" method="post" name="frm">
		<tr>
		  <td colspan="2" bgcolor='#ffffff' align="center"><img src="images/jiwoklogo.jpg" width=180 height="100"></td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
		  <td align="center" colspan="2" width="78%">Administrator Password Retrieval</td>
		</tr>
		<tr><td colspan="2" width="78%">&nbsp;</td></tr>
		<tr>
		  <td colspan="2">&nbsp;</td>
		</tr>
		<tr>
		  <td width="53%" colspan="2" align="center"><?=$msg;?></td>
		</tr>
		<tr>
		  <td width="25%" align="center"></td>
		</tr>
		<tr>
		  <td width="25%" align="center">&nbsp;</td>
		</tr>
		<tr>
		  <td colspan="2">&nbsp;</td>
		</tr>  
		<tr>
		  <td colspan="2" align="center"><a href="#" onClick="window.close()"><font face="Verdana, Arial, Helvetica, sans-serif" color="02019B" size="2">Close Window </font></a></td>
		</tr>
		<tr>
		  <td colspan="2" align="center"></td>
		</tr>
	  </table>
	</td>
  </tr>
	
</table>
</body>
</html>
<? 
include_once('../includes/config.php');
include_once('../includes/classes/class.General.php');
$objGen = new General();
$admin_title	="Jiwok admin password retrival";
$email = $objGen->_output($_REQUEST['admin_mail']);

if($objGen->_validate_email($email)){
		
		$retrieveSQL = "SELECT admin_login,admin_pwd FROM admin WHERE admin_email = '".$email."'";
		$resRetrieve = $GLOBALS['db']->getRow($retrieveSQL,DB_FETCHMODE_ASSOC);
		
		

}
else{
		$errorMsg = "Email address not valid";
		
}

 ?>
<html>
<head>
<title><?=$admin_title?></title>
<? include_once('metadata.php');?>
<script language="javascript">
function call(){
document.frm.Submit.value='Retrive';
document.frm.submit();
}
</script>
<LINK href="images/style.css" type=text/css rel=stylesheet>
</head>
<body>
<table align="center" width="80%" border="1" cellspacing="0">
  <tr>
    <td width="78%" align="center" >
	  <table widht="78%" align="center" border="0" cellpadding="" cellspacing="0">
	  	<form action="mail_send.php" method="post" name="frm">
		<tr>
		  <td  colspan="2" bgcolor='#ffffff' align="center"><span style="FONT-SIZE: 8pt; FONT-FAMILY: Verdana"><img src="images/jiwoklogo.jpg" width=180 height="100"></span></td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
		  <td align="center" colspan="2" width="78%">Administrator Password Retrieval</td>
		</tr>
		<tr><td colspan="2" width="78%"><? print $errorMsg?></td></tr>
		<tr>
		  <td colspan="2"><font color="E83E1B">Note:</font>
		     <font face="Verdana, Arial, Helvetica, sans-serif">Please enter the E-mail address which you have provided in the Administrator section.</font> 
		  </td>
		</tr>
		<tr>
		  <td width="25%">&nbsp;</td>
		  <td width="53%" colspan="2">&nbsp;</td>
		</tr>
		<tr>
		  <td width="65%" align="center" >
		    
		    Email-ID&nbsp;
		    <input type="text" name="admin_mail" onBlur="call();" value="<?=$email?>"></td>
		  <td width="13%"><input type="submit" name="Submit" value="Retrieve">
		  				<input type="hidden" name="Submit" value="Retrieve"></td>
		</tr>
		<tr>
		  <td width="25%" align="center">&nbsp;</td>
		  <td width="53%">&nbsp;</td>
		 
		</tr>
		<tr>
		  <td colspan="2">&nbsp;</td>
		</tr>  
		<tr>
		  <td colspan="2" align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><strong><a href="#" onClick="window.close()" class="smallLink">Close Window</a></strong></font></td>
		</tr>
		</form>  
	  </table>
	</td>
  </tr>
	
</table>
</body>
</html>
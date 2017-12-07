<?php
    session_start();
	include_once('includes/config.php');	
	include_once('includes/classes/class.General.php');
	$genObj =new General();
	

	if($_POST['submit'])
	{
		if(trim($_POST["user_name"])=="")
			$errMsg ="User Name Required";
		else if(trim($_POST["upass"])=="")
			$errMsg ="Password Required";	
		else{
					$username=$_POST["user_name"]; 
					$password=base64_encode($_POST["upass"]);


					
			//new block
			/*$query="SELECT * FROM admin WHERE BINARY admin_login='".addslashes($username)."' and  
						admin_pwd='".addslashes($password)."' and admin_status='1'";

			$row = $GLOBALS['db']->getRow($query,DB_FETCHMODE_ASSOC);

			if(DB::isError($row)) {
				echo $row->getDebugInfo();
			}
			elseif($row['admin_id']){
				session_register('adm_id_report');
				session_register('sessAdminEmail_report');
				$_SESSION['adm_id_report']	    	=	$row['admin_id'];
				$_SESSION['sessAdminEmail_report'] = 	$row['admin_email'];
				header("Location:index.php");*/
				$query="SELECT * FROM admin WHERE BINARY admin_login='".addslashes($username)."' and  
						admin_pwd='".addslashes($password)."' and admin_status='1'";

  			$result = mysql_unbuffered_query($query) or die(mysql_error());  
			$row = mysql_fetch_array($result);
				if($row['admin_id']){
				//session_register('adm_id_report');
				//session_register('sessAdminEmail_report');
				$_SESSION['adm_id_report']	    	=	$row['admin_id'];
				$_SESSION['sessAdminEmail_report'] = 	$row['admin_email'];
				header("Location:index.php");
									
			}else{
				$errMsg ="User Name or Password is Invalid";
			}

				//ends
		}
		
		
		
	}
?>
<HTML><HEAD><TITLE>
Welcome to Jiwok Reports Area
</TITLE>
<? include_once('metadata.php');?>
<script language="javascript">
function notcontrolpaste(e){

if (e.keyCode) code = e.keyCode;
else if (e.which) code = e.which;

	if(e.button == 2){
		alert("Sorry, you do not have permission to right click");
		return false;
		}
	if(e.ctrlKey && code ==86) {
			document.form1.upass.value = '';
	}
}

</script>


<script language="Javascript1.2">


// Set the message for the alert box
am = "This function is disabled!";

// do not edit below this line
// ===========================
bV  = parseInt(navigator.appVersion)
bNS = navigator.appName=="Netscape"
bIE = navigator.appName=="Microsoft Internet Explorer"

function nrc(e) {
   if (bNS && e.which > 1){
      alert(am)
      return false
   } else if (bIE && (event.button >1)) {
     alert(am)
     return false;
   }
}

document.onmousedown = nrc;
if (document.layers) window.captureEvents(Event.MOUSEDOWN);
if (bNS && bV<5) window.onmousedown = nrc;

</script>
<script>

function hidestatus(){
window.status=''
return true
}

if (document.layers)
document.captureEvents(Event.MOUSEOVER | Event.MOUSEOUT)

document.onmouseover=hidestatus
document.onmouseout=hidestatus
</script>


</HEAD>
<BODY class="middleTableBg" leftMargin=0 topMargin=0 marginheight="0" marginwidth="0" onLoad="javascript:document.form1.user_name.focus();" background="gradient_thead.gif;" background-repeat:repeat; >
<FORM action="login.php" method="post" name="form1">
<DIV align=left>
<TABLE height="100%" cellSpacing=0 cellPadding=0 width="100%" border=0>
  <TBODY>
  	<TR>
      <TD width="100%">
       <DIV align=center><CENTER>
        <TABLE style="BORDER-RIGHT: #000000 2px solid; BORDER-TOP: #000000 2px solid; BORDER-LEFT: #000000 2px solid; BORDER-BOTTOM: #000000 2px solid" 
         cellSpacing=0 cellPadding=0 width=500 bgColor=#efefef border=0>
         <TBODY>
          <TR>
            <TD vAlign=top width=180 bgColor="#666666"><IMG height=322 alt="adminlogin-image.gif" 
             src="images/adminlogin-image.jpg" width=180></TD>
            <TD vAlign=top bgcolor="#FFFFFF"> 
            <DIV align=left>
               <TABLE width="100%" border=0 align="center" cellPadding=20 cellSpacing=0 bgcolor="#FFFFFF">
                 <TBODY> 
                   <TR>
                     <TD width="100%" height=323 align="center"> 
                      
                         <table align="center"  border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td align="center" width="100%" bgcolor="#FFFFFF"><span style="FONT-SIZE: 8pt; FONT-FAMILY: Verdana"><img src="images/jiwoklogo.jpg" width="140" height="70"></span></td>
                            </tr>
                          </table>
                          <P align=center><SPAN style="FONT-SIZE: 8pt; FONT-FAMILY: Verdana">
						  <FONT color=#002f8d>Please enter your username &amp; password...</FONT></SPAN> 
                          </P>
			 
			<?php
				if($errMsg){
			?>
					<FONT color='red' style="font-size:10px; font-family:Verdana, Arial, Helvetica, sans-serif" ><?=$errMsg?>
					</FONT>
			<?php
				}
			?>			     
                  <DIV align=left>
                  <TABLE cellSpacing=0 cellPadding=3 width="100%" border=0>
                    <TBODY>
                      <TR>
                         <TD width="35%"><STRONG><SPAN style="FONT-SIZE: 8pt; FONT-FAMILY: Verdana"><font color="E83E1B">User 
                                    Name:</font></SPAN></STRONG></TD>
                         <TD width="65%" colspan="2"><INPUT  type="text" class="field" value="<? print $genObj->_output($_POST['user_name'])?>" style="FONT-SIZE: 10pt; FONT-FAMILY: Arial" size=13 
                          name="user_name"> 
						 </TD>
					  </TR>
                      <TR>
                         <TD width="35%"><STRONG><SPAN style="FONT-SIZE: 8pt; FONT-FAMILY: Verdana"><font color="E83E1B">Password:</font></SPAN></STRONG></TD>
                         <TD width="65%" colspan="2"><INPUT class="field" style="FONT-SIZE: 10pt; FONT-FAMILY: Arial" 
                          type="password" value="<? print $genObj->_output($_POST['upass'])?>" size=13 name="upass" onKeyUp="javascript:notcontrolpaste(event);" onMouseUp="javascript:notcontrolpaste(event);" > 
						 </TD>
					  </TR>
                      <TR>
                      	<TD width="35%"></TD>
                        <TD width="25%"><INPUT  type="submit" value="login" name="submit"></TD>
						<TD width="40%" align="left"><a href="javascript:;" title="Password Retrieval" onClick="window.open('send_password.php','wnd2','top=40,left=90,width=500,height=400,scrollbars=no')"><STRONG><SPAN style="FONT-SIZE: 8pt; FONT-FAMILY: Verdana"><font color="#FF0000">Forgot Password</font></SPAN></STRONG></a></TD>
                        
					  </TR>
					  
				    </TBODY>
				  </TABLE></DIV>
                  <P align="left"><SPAN style="FONT-SIZE: 8pt; FONT-FAMILY: Verdana"><FONT color=#002f8d><B>Jiwok.com</B>,Copyright  <?=date('Y');?> Jiwok Inc. Developed by <A href="http://www.reubro.com/" target=_blank>Reubro International.</A>
											   
				  </FONT></SPAN></P>
                </TD>
			   </TR>
			  </TBODY>
			 </TABLE>
                  </DIV>
                </TD></TR></TBODY></TABLE>
      <TABLE width=505 border=0 align="center" cellPadding=5 cellSpacing=0>
        <TBODY>
        <TR>
                <TD width="505" height=32 align="center"><div align="justify" class="label">
				<B><font color="E83E1B">WARNING!</font></B> ACCESS 
                    AND USE OF THIS COMPUTER SYSTEM BY <B>ANYONE</B> WITHOUT THE 
                    PERMISSION OF THE OWNER, <FONT 
            color=#e89800><B><font color="E83E1B">Jiwok.com</font></B></FONT>, 
                    IS <B>STRICTLY PROHIBITED</B> BY STATE AND FEDERAL LAWS AND 
                    MAY SUBJECT AN UNAUTHORIZED USER, INCLUDING EMPLOYEES NOT HAVING 
                    AUTHORIZATION, TO CRIMINAL AND CIVIL PENALTIES AS WELL AS COMPANY-INITIATED 
                    DISCIPLINARY ACTION. DEVELOPED BY <A href="http://www.reubro.com/" 
            target=_blank><FONT color=#e89800><B><font color="E83E1B">Reubro International 
                    </font></B></FONT>.</A> <?=date('Y');?> ALL RIGHTS RESERVED</FONT></div></TD>
              </TR></TBODY></TABLE></CENTER></DIV></TD></TR></TBODY></TABLE></DIV>

  </FORM>
</BODY></HTML>
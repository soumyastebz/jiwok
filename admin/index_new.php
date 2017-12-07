<?php

	ob_start();

	session_start();

	

	include_once("../includes/config.php");
	include_once('adm_sess_check.php');

	include_once("../includes/globals.php"); 

	$admin_title = "Jiwok Administrators Home";

?>

<HTML><HEAD><TITLE><?=$admin_title?></TITLE>

<? include_once('metadata.php');?>



<!-- Fo AJAX status check-->

  <script language="javascript" src="../includes/js/broucerCheck.js"></script>

<script language="javascript">



function getStatus(sTr){

    var url="bg_conversion_status.php"

	

	

	parameters = 't='+sTr

	

	xmlHttpUser=myXMLHttpObject(stateChanged)

	xmlHttpUser.open("POST",url,true)

	xmlHttpUser.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8")

	xmlHttpUser.send(parameters)

	

  }

  function stateChanged(){	

		if (xmlHttpUser.readyState==4 || xmlHttpUser.readyState=="complete"){		

			document.getElementById("Status").innerHTML=xmlHttpUser.responseText

		}

		else{

			document.getElementById("Status").innerHTML= 'Please wait...'

				

		}

  } 

  

  </script>



















</HEAD>

<BODY class="bodyStyle"> 

<TABLE cellSpacing=0 cellPadding=0 width="779" align="center" border="1px" bordercolor="#E6E6E6"> 

  <TR>

    <TD vAlign=top align=left bgColor=#ffffff><? include("header.php");?></TD>

  </TR>

  

  <TR height="5">

    <TD vAlign=top align=left>

	<table width="100%" class="topBarColor"><tr><td>&nbsp;</td></tr></table>

	</TD>

  </TR>

  

  <TR>

    <TD width="100%" valign="top" align="left" height="340"> 

      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 class="middleTableBg">

        <TR> 

          <TD vAlign=top align=left width="175" rowSpan="3" > 

            <TABLE cellSpacing="0" cellPadding="0" width="175" border=0>

              <TR> 

                <TD width="175" align="left" valign="top">

				  <TABLE cellSpacing=0 cellPadding=2 width=175 

                  border=0>

                    <TBODY> 

                    <TR valign="top"> 

                      <TD align="left" valign="top"><? include ('leftmenu_new.php');?></TD>

                    </TR>

                    </TBODY> 

                  </TABLE>				</TD>

              </TR>

            </TABLE>          </TD>

          <TD vAlign=top align=left width=0></TD>

        </TR>

		

        <TR align="center" valign="top"> 

          <TD align="center" valign="top">

		  		<br><br>

              <table width="75%" border="0" align="center" cellpadding="0" cellspacing="0">

                <tr>

                  <td width="10" height="9"><img src="images/top_left.jpg" width="9" height="9"></td>

                  <td width="543" height="9" background="images/top_mdl.jpg"><img src="images/top_mdl.jpg" width="9" height="9"></td>

                  <td width="11" height="9"><img src="images/top_right.jpg" width="9" height="9"></td>

                </tr>

                <tr>

                  <td background="images/side1.jpg"><img src="images/side1.jpg" width="9" height="9"></td>

                  <td valign="top">

			<table width="100%">

                      <tr>

                        <td colspan="3" align="center" class="tableHeaderColor">Welcome Administrator</td>

                      </tr>

                      <tr>

                        <td width="100%" class="topColor"><p align="justify">The <strong>Administrator</strong> can add/edit any kind  data that resides and is the sole person responsible for the content management of the whole web site.</p></td>

                      </tr>

                      <tr>

                        <td colspan="3" class="normal">&nbsp;</td>

                      </tr>

                      <tr>

                        <td colspan="3" class="bigheadings">Please note the following:</td>

                      </tr>

                      <tr>

                        <td colspan="3" bgcolor="#E2E2E2" class="normal"><ul>

                            <li>This site is best viewed on Internet Explorer 5.x or above.</li>

                          <li>Make sure the browser supports Client Side Scripting(JavaScript) and that it is enabled.</li>

                          <li>Browser should also support cookies and should be enabled</li>

                        </ul></td>

                      </tr>

                  </table></td>

                  <td background="images/side2.jpg">&nbsp;</td>

                </tr>

                <tr>

                  <td width="10" height="9"><img src="images/btm_left.jpg" width="9" height="9"></td>

                  <td height="9" background="images/btm_mdl.jpg"><img src="images/btm_mdl.jpg" width="9" height="9"></td>

                  <td width="11" height="9"><img src="images/btm_right.jpg" width="9" height="9"></td>

                </tr>

              </table>

            <!---Contents Start Here-----></TD>

        </TR>

        <TR align="center" valign="top">

          <TD align="center" valign="top">

		  <a name="#c">&nbsp;</a>

		  <table width="75%" border="0" align="center" cellpadding="0" cellspacing="0">

            <tr>

              <td width="10" height="9"><img src="images/top_left.jpg" width="9" height="9"></td>

              <td width="543" height="9" background="images/top_mdl.jpg"><img src="images/top_mdl.jpg" width="9" height="9"></td>

              <td width="11" height="9"><img src="images/top_right.jpg" width="9" height="9"></td>

            </tr>

            <tr>

              <td background="images/side1.jpg"><img src="images/side1.jpg" width="9" height="9"></td>

              <td valign="top"><table width="100%">

                  

                  <tr>

                    <td colspan="3" bgcolor="#E2E2E2" class="normal">&nbsp;</td>

                  </tr>

                  <tr>

                    <td colspan="3" bgcolor="#E2E2E2" class="normal">

					<a href="./convert_background_test.php?t=vC">Convert all vocal mp3 to the wave format </a><br>

					<? if($_REQUEST['vC'] == "1"){?>

					<a href="" onClick="javascript:getStatus('vC')">Check Status :</a>

					<span id="Status">Processing...</span></td>

					<? }?>

                  </tr>

                  <tr>

                    <td colspan="3" bgcolor="#E2E2E2" class="normal" >

					<a href="./convert_background_test.php?t=bG">Convert all backgrounds to wave format </a><br>

					<? if($_REQUEST['bG'] == "1"){?>

					<a href="" onClick="javascript:getStatus('bG')">Check Status :</a>

					<span id="Status">Processing...</span>

					<? }?>

										

					</td>

                  </tr>

              </table></td>

              <td background="images/side2.jpg">&nbsp;</td>

            </tr>

            <tr>

              <td width="10" height="9"><img src="images/btm_left.jpg" width="9" height="9"></td>

              <td height="9" background="images/btm_mdl.jpg"><img src="images/btm_mdl.jpg" width="9" height="9"></td>

              <td width="11" height="9"><img src="images/btm_right.jpg" width="9" height="9"></td>

            </tr>

          </table></TD>

        </TR>

		 <TR height="2">

    <TD vAlign=top align=left class="topBarColor" colspan="3">&nbsp;</TD>

  </TR>

</TABLE>

  <?php include_once("footer.php");?>

</body>

</html>
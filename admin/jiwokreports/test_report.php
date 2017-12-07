<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::>Jiwok-Report
   Programmer	::> Deepa S 
   Date			::> 27/Jan/2011
   DESCRIPTION::::>>>> Jiwok Reports section. This index page  displays the report summary of all sections  - All users, Register, Subscriber, Ex-subscriber,1 euro transactions, gift code transactions
  
*****************************************************************************/
error_reporting(1);
	$admin_title = "JIWOK REPORTS";
	
	if($_REQUEST['langId'] != ""){
		$lanId = $_REQUEST['langId'];
	}
	else{
		$lanId = 1;
	}
	$_POST['maxrows'] = 10;
	@define("DB_USER", "root");
	@define("DB_PASSWD", "");
	@define("DB_HOST", "localhost");
	@define("DB_NAME", "jiwok_ver2");
	
	mysql_pconnect(DB_HOST,DB_USER,DB_PASSWD) or die(mysql_error());
//echo "Connected to MySQL<br />";
mysql_select_db(DB_NAME) or die(mysql_error());			 
		
	$heading = "Subscriber Reports";
	
	
	$orderby	=	$_REQUEST['orderby'];
	$sortby		=	$_REQUEST['sortby'];
//===========Code for sorting =============

	
	$selectQry_temp		=	"select user_fname,user_lname from user_master ";
	
		
	/*--------------------coding for date  filter ends here ---------------------------------*/
	
	//$reports1 = $objReport->getReportOfSubscribers($user_type,$whereSql,$sort_sql);
	$lim='';
	//if(count($reports1)>0)
	//{ 
	  	   //////////////////////////////// PAGINATION //////////////////////////////////
	  
		$param	= substr($param, 1);
		$param	= base64_encode($param);
		if(!$_REQUEST['maxrows'])
			$_REQUEST['maxrows'] = $_POST['maxrows'];
		if($_REQUEST['pageNo']){
			
			$_SESSION['pageNo'] = 1;
			$fromLimit = $_REQUEST['maxrows']*($_REQUEST['pageNo'] - 1);
			$toLimit = $_REQUEST['maxrows'];
			$lim = " LIMIT ".$fromLimit.",".$toLimit;
			
			$selectQry_temp.=$lim;
			//$reports = $objReport->getReportOfSubscribers($user_type,$whereSql,$sort_sql,$lim );
		}
		else{
		/***********************Selects Records at initial stage***********************************************/
			$_REQUEST['pageNo'] = 1;
			$_SESSION['pageNo'] = 1;
		
			//$result = $objHomepage->_showPage($totalRecs,$_REQUEST['pageNo'],$_REQUEST['maxrows'],$field,$type,$searchQuery);
			$fromLimit = $_REQUEST['maxrows']*($_REQUEST['pageNo'] - 1);
			$toLimit = $_REQUEST['maxrows'];
			$fromLimit = (int)$fromLimit;
			$toLimit   = (int)$toLimit; 
			$lim = " LIMIT ".$fromLimit.",".$toLimit;
			$selectQry_temp.=$lim;
			
		}
		
		$result1		=	mysql_query($selectQry_temp);
		
		//Pagin 
		
		if($_REQUEST['pageNo'] == 1){
			$prev = 1;
		}
		else
			$prev = $_REQUEST['pageNo']-1;
		if($_REQUEST['pageNo'] == $noOfPage){
			$next = $_REQUEST['pageNo'];
		}
		else
			$next = $_REQUEST['pageNo']+1;
	////////////////////////////////////Pagination ends here/////////////////////////////////////////
	    
	?>	

<HTML><HEAD>
<TITLE>JIWOK REPORTS</TITLE>
<script language="javascript" type="text/javascript">
function selectCountry(myvar)
{
	var myVar = myvar;
	if(myVar=='')
	 document.getElementById('countrydiv').style.display='none';
	else  document.getElementById('countrydiv').style.display='block';
	return true;
}

function selectBrand(myvar)
{
	var myVar = myvar;
	if(myVar=='')
	 document.getElementById('branddiv').style.display='none';
	else  document.getElementById('branddiv').style.display='block';
	return true;
}

function selectSports(myvar)
{
	var myVar = myvar;
	var xmlhttp;
	if(myVar.value=='')
	 document.getElementById('sportsdiv').style.display='none';
	else  
	{
	  
		if (window.XMLHttpRequest)
  		{// code for IE7+, Firefox, Chrome, Opera, Safari
  			xmlhttp=new XMLHttpRequest();
  		}
		else
  		{// code for IE6, IE5
  			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  		}
		xmlhttp.onreadystatechange=function()
  		{
  			if (xmlhttp.readyState==4 && xmlhttp.status==200)
    		{
    		document.getElementById('sportsdiv').style.display='block';
			document.getElementById("sportsdiv").innerHTML=xmlhttp.responseText;
    		}
  		}
		xmlhttp.open("GET","getSports.php?lan="+myVar.value,true);
		xmlhttp.send();
	
	}
	return true;
}

function selectLanguage(myvar)
{
	var myVar = myvar;
	if(myVar=='')
	 document.getElementById('languagediv').style.display='none';
	else  document.getElementById('languagediv').style.display='block';
	return true;
}
</script>
<? include_once('metadata.php');?>
<LINK href="images/sortnav.css" type='text/css' rel='stylesheet'/>
</HEAD>
<BODY class="bodyStyle">
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<TABLE cellSpacing=0 cellPadding=0 width="100%" align="center" border="1px" bordercolor="#E6E6E6"> 
  <TR>
    <TD vAlign=top align=left bgColor=#ffffff><? include("header.php");?></TD>
  </TR>
  <TR height="5">
    <TD vAlign=top align=left class="topBarColor">&nbsp;</TD>
  </TR>
  <TR>
    <TD vAlign="top" align="left" height="340"> 
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 class="middleTableBg">
        <TR> 
          <TD vAlign=top align=left width="175" rowSpan="2" > 
                <TABLE cellSpacing="0" cellPadding="0" width="175" border=0>
                  <TR> 
                    <TD valign="top">
                         <TABLE cellSpacing=0 cellPadding=2 width=175 border=0>
                            <TBODY> 
                            <TR valign="top"> 
                              <TD valign="top"><? include ('leftmenu.php');?></TD>
                            </TR>
                            
                            </TBODY> 
                          </TABLE>
                    </TD>
                  </TR>
                </TABLE>
          </TD>
          <TD vAlign=top align=left width=0></TD>
         
        </TR>
            <TR> 
              <TD valign="top" width="1067"><!---Contents Start Here----->
              
              <form name="reportFrm" action="report_subscriber.php" method="post" enctype="multipart/form-data">
                <TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
                  <TR> 
                    <TD class=smalltext width="98%" valign="top">
                    
                          <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
                        <td width="10" height="9"><img src="images/top_left.jpg" width="9" height="9"></td>
                        <td width="543" height="9" background="images/top_mdl.jpg"><img src="images/top_mdl.jpg" width="9" height="9"></td>
                        <td width="11" height="9"><img src="images/top_right.jpg" width="9" height="9"></td>
                      </tr>
                          <tr> 
                            <td background="images/side1.jpg"><img src="images/side1.jpg" width="9" height="9"></td>
                            <td valign="top"> 
                            
                            
                            
                                <TABLE cellSpacing=0 cellPadding=0 border=0 align="center" width="100%">
                                    <TR> 
                                      <TD valign='top' bgColor='white'><table width=100% height="227" border=0 cellpadding=0 cellspacing=0 class="paragraph2">
                                        <tr>
                                          <td height="6" colspan="4" align="center" valign="bottom" class="sectionHeading" style="font-size:16px; padding-top:10px;">Subscriber Reports</td>
                                        </tr>
                                        <tr>
                                           <td  colspan="6" height="27" align="right" valign="bottom" class="sectionHeading" style="padding-right:20px;" ><font style="font-size:14px;background-color:#09F; color:#FFF; padding:3px;">EXPORT TO CSV</font></td>
                                        </tr>
                                        <tr style="padding-top:10px;">
                                          <td  colspan="6" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
                                        </tr>
                                        <tr style="padding-top:10px;">
                                          <td height="21" colspan="6" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
                                        </tr>
                                                                                
                                     <tr>
                                          <td height="4" colspan="4" align="left" valign="bottom" class="sectionHeading" style="font-size:13px; color:#36C; padding-left:20px; padding-top:20px; padding-bottom:20px;"><strong>Filter By Parameters</strong></td>
                                        </tr>   
                                    
                                        <tr style="padding-top:10px;">
                                          <td height="21" colspan="6" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px; padding-top:20px;"><span class="successAlert" >Filter by date</span></td>
                                        </tr>
                                      
                                        <tr style="padding-top:10px;">
                                          <td height="21" colspan="3" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                          <td colspan="4"></td>
                                        </tr>
                                        <tr>
                                          <td height="2" colspan="4" align="left"></td>
                                        </tr>
                                      </table>
                                        <br/>
                                  <TABLE cellSpacing=1 cellPadding=2 width="553">
                                    <TBODY>
                                    <tr>
                                          <td height="6" colspan="4" align="left" valign="bottom" class="sectionHeading" style="font-size:14px; padding-top:10px;">Subscriber Search Results </td>
                                        </tr>
                                      
                                      </tbody>
                                  </table><br/>   
                                  <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="100%" border="0">
                                 
                                      <TR class="tableHeaderColor" >
                                      <TD width="13%" align="center" ><a href="report_subscriber.php?sortby=brand&orderby=<?=$orderby_sql?>&maxrows=<?=$maxrows;?>&display=<?=base64_encode($display);?>" class="tbl_header">first</a><?=$cat_orderImage?></TD>
                                        <TD width="20%" align="center" ><a href="report_subscriber.php?sortby=name&orderby=<?=$orderby_sql?>&maxrows=<?=$maxrows;?>&display=<?=base64_encode($display);?>" class="tbl_header">Last</a><?=$cat_orderImage?></TD>
                                         
                                      </TR>
                                     
                         <?php //if(count($reports)>0) { 
								while($report	=	mysql_fetch_array($result1,MYSQL_ASSOC)){
							      	$fname	= trim(stripslashes($report['user_fname']));
									$lfname	= trim(stripslashes($report['user_lname']));
									   ?>                   
                                    
                        			   <tr class="listingTable1">
                                  	   <TD height="19" align="left" style="padding-left:10px;"><?=$fname?></TD>
                                       <TD height="19" align="left" style="padding-left:2px;"><?=$lfname?></TD>
                                       </tr>
                                      <?php 
									   }
									 ?>
                                     
                                  </table>
                                 
                                  <table cellspacing=0 cellpadding=0 width='800' border=0 class="topColor">
                                <tbody>		
					<tr>
						<td align="right" colspan = "6" class="leftmenu">
						
						<a href="test_report.php?pageNo=<?=$prev?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>">
						<img src="images/mail-arrowlg.gif" border=0 width="20" height="20" align="absmiddle" alt="Previous Page"></a>
							 <a href="test_report.php?pageNo=<?=$next?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>">
							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>
						</td>
						<td width="233" align=right class="paragraph2">&nbsp;
			
					</td>
					</tr>
				   </tbody>
			 	</table>     
               
                                             
                 <br/></TD>
                                    </TR>
                                  </TABLE>
                              </td>
                            <td background="images/side2.jpg">&nbsp;</td>
                          </tr>
                      <tr> 
                        <td width="10" height="9"><img src="images/btm_left.jpg" width="9" height="9"></td>
                        <td height="9" background="images/btm_mdl.jpg"><img src="images/btm_mdl.jpg" width="9" height="9"></td>
                        <td width="11" height="9"><img src="images/btm_right.jpg" width="9" height="9"></td>
                      </tr>
                    </table>
    
                    </TD>
                  </TR>
                </TABLE>
            </form>
              </TD>
            </TR>
		 <TR height="2">
    <TD vAlign=top align=left class="topBarColor" colspan="3">&nbsp;</TD>
  </TR>
      </TABLE>
      </td>
      </tr>
      </table>
        <?php 
			include_once("footer.php");
			
		?></body>
</html>
<?php
if($_POST['country_select'] == '1' && $_POST['user_country']!=""){
		echo "<script language='javascript' type='text/javascript'>selectCountry(1);</script>";
}
if($_POST['language_select'] == '1' && $_POST['user_language']!=""){
		echo "<script language='javascript' type='text/javascript'>selectLanguage(1);</script>";
}
if($_POST['brand_select'] == '1'){
		echo "<script language='javascript' type='text/javascript'>selectBrand(1);</script>";
}
?>

<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-reseller Details
   Programmer	::> jasmin
   Date			::> 04/02/2009
   
   DESCRIPTION::::>>>>
   This  code userd to display reseller details.
   
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
	
	$heading = "Reseller Details";
	/*
	Take all the languages to an array.
	*/
	$languageArray=$siteLanguagesConfig;
	reset($languageArray);
	
	$errorMsg	=	array();
		
	//if edit following will execute on loading
	if($_REQUEST['masterId'] and count($errorMsg)==0){
		$result = $objTesti->_getAllById($_REQUEST['masterId']);
		$details = $objTesti->_getDetailsById($_REQUEST['masterId']);
		}
		$n	= 0;
		if(count($result) != 0){
			$_POST['re_id'] 	= $result[$n]['reseller_id'];	
		}
		if($details)
		{
			$details1=explode(",",$details);
		}
		?>


<HTML><HEAD><TITLE><?=$admin_title?></TITLE>
<? include_once('metadata.php');?>
<script language="JavaScript">
function openNewWindow(URLtoOpen,windowName,width,height)
{
windowFeatures ="menubar=no,scrollbars=no,location=no,favorites=no,resizable=no,status=no,toolbar=no,directories=no";
var test = "'";
winLeft = (screen.width-width)/2;
winTop = (screen.height-(height+110))/2;
window.open(URLtoOpen,windowName,"width=" + width +",height=" + height + ",left=" + winLeft + ",top=" + winTop + test + windowFeatures + test);

}
</script>
</HEAD>
<BODY  class="bodyStyle">
<TABLE cellSpacing=0 cellPadding=0 width="779" align="center" border="1px" bordercolor="#E6E6E6">
  <TR>
    <TD valign="top" align=left bgColor=#ffffff><? include("header.php");?></TD>
  </TR>
  <TR height="5">
    <TD valign="top" align=left class="topBarColor">&nbsp;</TD>
  </TR>
  
  <TR>
    <TD align="left" valign="top"> 
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 class="middleTableBg">
        <TR> 
          <TD  valign="top" align=left width="175" rowSpan="2" > 
            <TABLE cellSpacing="0" cellPadding="0" width="175" border=0>
              <TR> 
                <TD valign="top">
				 <TABLE cellSpacing=0 cellPadding=2 width=175  border=0>
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
          <TD valign="top" align=left width=0></TD>
         
        </TR>
        <TR> 
          <TD valign="top" width="1067"><!---Contents Start Here----->
		  
		  
            <TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
              <TR> 
                <TD  width="98%" valign="top">
				
				  <table width="75%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr> 
                <td width="10" height="9"><img src="images/top_left.jpg" width="9" height="9"></td>
                <td width="543" height="9" background="images/top_mdl.jpg"><img src="images/top_mdl.jpg" width="9" height="9"></td>
                <td width="11" height="9"><img src="images/top_right.jpg" width="9" height="9"></td>
              </tr>
              <tr> 
                <td background="images/side1.jpg"><img src="images/side1.jpg" width="9" height="9"></td>
                <td valign="top"> 
				
				
				
				<TABLE cellSpacing=0 cellPadding=0 border=0 align="center">
                    <TR> 
                      <TD valign="top" width=564 bgColor=white> 
                       
						  
				<form name="faqform" action="reseller_details.php" method="post" onSubmit="return formChecking()" enctype="multipart/form-data">
						  <TABLE cellSpacing=0 cellPadding=4 width=561 border=0>
                          <TBODY> 
                          <TR> 
                            <TD valign="top">
								   <table class="paragraph2" cellspacing=0 cellpadding=0 width=553 border=0>
								  <tr>
										<td colspan="2" height="50" align="center" valign="bottom" class="sectionHeading"><?=$heading;?></td>
									</tr>
									<?php 
										if($errorMsg){ ?>
									<tr>
										<td align="center"><? print $objGen->_adminmessage_box($errorMsg,$mode='error',$url='normal') ?></td>
									</tr>
									<?php } ?>
				                     <tr>
									 <td align="left"><table height="50" class="topActions"><tr><td valign="middle" width="50"><a href="list_reseller.php?pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=stripslashes(stripslashes($_REQUEST['keyword']))?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;List   </a></td>
										</tr></table>
									 </td>
									 </tr>
									<TR> 
									<TD height="41" align="left">
										Reseller ID &nbsp;&nbsp;:
										<input type="text" readonly="readonly" name="re_id" value="<?=$objGen->_output($_POST['re_id']);?>"> 
									</TD><td align="right"></td>
									</TR>
									
								  </table>
                 	 
					          
				  <TABLE class="paragraph2" border="1" cellSpacing=0 cellPadding=2 width="100%">
				   <TBODY> 
					 
					 <tr height="30px">
						<td width="30%" align="right">No. of Code purchased:&nbsp;</td>
						<td>
						<input type="text" readonly="readonly" name="nop" id="nop" value="<?=$details1[0];?>"/> <span style="padding-left:20px"> Amount: &nbsp;<?php if($details1[1]){echo $details1[1];} else{echo "0";}?>&nbsp;Euro</span>
						</td>
					</tr>
					<tr height="30px">
						<td width="30%" align="right">No. of Code used:&nbsp;</td>
						<td>
						<input type="text" readonly="readonly" name="nou" id="nou" value="<?=$details1[2];?>"/> <span style="padding-left:20px"> Amount: &nbsp;<?php if($details1[3]){echo $details1[3];} else{echo "0";}?>&nbsp;Euro</span>
						</td>
					</tr>
					<!--<tr height="30px">
						<td width="30%" align="right">No. of Code unused:&nbsp;<br/>
						</td>
						<td>
						<input type="text" readonly="readonly" name="noun" id="noun" value="<?$details1[4];?>"/>
						</td>
					</tr>-->
					<tr >
						<td colspan="2" align="center">
							<input type="button" name="add" onClick="window.open('new.php?obj=<?php echo $details;?>&masterId=<?php echo $_REQUEST['masterId'];?>','popup','width=500,height=500,scrollbars=no,resizable=yes,toolbar=no,directories=no,location=no,menubar=no,status=no,left=0,top=0'); return false;" value="Generate Invoice"></td>
					</tr>
				    </tbody>
			 	  </table>
				</TD>
                          </TR>
                          </TBODY>
                        </TABLE>
				<input type="hidden" name="langId" value="<?=$_REQUEST['langId']?>">  
				<input type="hidden" name="masterId" value="<?=$_REQUEST['masterId']?>">
			   <input type="hidden" name="pageNo" value="<?=$_REQUEST['pageNo']?>">
			   <input type="hidden" name="maxrows" value="<?=$_REQUEST['maxrows']?>">
			   <input type="hidden" name="type" value="<?=$_REQUEST['type']?>">
				<input type="hidden" name="field" value="<?=$_REQUEST['field']?>">
                <input type="hidden" name="keyword" value="<?=stripslashes(stripslashes($_REQUEST['keyword']))?>">
				</form>
						  
                      </TD>
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

          </TD>
        </TR>
		 <TR height="2">
    <TD valign="top" align=left class="topBarColor" colspan="3">&nbsp;</TD>
  </TR>
      </TABLE>

        <?php include_once("footer.php");?>
</td></tr></table>		
</body>
</html>
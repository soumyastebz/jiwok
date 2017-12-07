<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-gift code manage section
   Programmer	::> jasmin
   Date			::> 09/12/2009
   
   DESCRIPTION	::::>>>>
   Admin can manage the giftcodes
*****************************************************************************/
	include_once('includeconfig.php');
	include_once('../includes/classes/class.giftcode.php');
	include('giftcodegen.php');
	include_once('define.php');
	
	
	$obj= new gift();
	$heading = "Manage Gift Code";
	$errorMsg	=	array();
	
	if($_REQUEST['langId'] != 0){
		$lanId = $_REQUEST['langId'];
	}
	else{
		$lanId = 1;
	}
	
	/* Take all the languages to an array. */
	$languageArray = $siteLanguagesConfig;
						 
	/*
	 Instantiating the classes.
	*/
	$objGen   	 =	new General();
	
	if($_POST['update']=='Generate')
	{
	   $no=trim($_POST['number']);
	   $month=$_POST['month'];
	   switch($month)
	   {
	     case 1: {$period="4 months";$amt=FOUR_MNTH_AMT;}break;
		 case 2: {$period="6 months";$amt=SIX_MNTH_AMT; }break;
		  case 3: {$period="12 months";$amt=TWELVE_MNTH_AMT; }break;
	   }
	   for($i=0;$i<$no;$i++)
	   {
	   	  	$gcode[]=get_code();
	   }
	   $message="Generated successfully";
	   for($i=0;$i<$no;$i++)
	   {  
	   	  	$result=$obj->_insertgift($period,$amt,$gcode[$i]);
			if($result== false)
			{
			  $message="";
			}
			
	   }
	   
	   
	}
?>
<HTML><HEAD><TITLE><?=$admin_title?></TITLE>
<? include_once('metadata.php');?>
</HEAD>
<script type="text/javascript">
function validate(frmcode)
{
  var iNum= "0123456789";
  var month= document.getElementById('month');
  if(document.frmcode.number.value=='' || document.frmcode.number.value==0)
  {
     alert("Please Enter a valid number!");
     document.frmcode.number.focus();
	 return false;
  }
  else
  {
    for(var i=0; i<document.frmcode.number.value.length; i++)
		{
		  if(iNum.indexOf(document.frmcode.number.value.charAt(i))==-1)
		  {
			alert("Please Enter a valid number!");
			document.frmcode.number.focus();
			return false;
		  }
		}
	 if(month.selectedIndex ==0)
	  {
		 alert("Please Select a period!");
		 document.frmcode.month.focus();
		 return false;
	  }
	 return true; 
  }

}
</script>
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
                       
			  			   
                      
				  <table class="paragraph2" cellspacing=0 cellpadding=0 width=553 border=0>
				  <tr>
						<td height="50" align="center" valign="bottom" class="sectionHeading"><?=$heading;?></td>
					</tr>
					<?php 
						if($message){ ?>
					<tr>
						<td align="center" class="successAlert"><?=$message;?></td>
					</tr>
					<?php } ?>

					<TR height="20"><TD align="left">&nbsp;</TD></TR>
					
				  </table>
				  
                 <form name="frmcode" action="manage_giftcode.php" method="post" onSubmit="return validate(this.form)">         
				  <table cellSpacing=1 cellPadding=0 width="100%" bgcolor="">
				   <tbody> 
				   <tr >
				     <th colspan="2" align="left">Generate Gift Code</th>
				     </tr>
				   <tr >
				     <td width="30%" height="30" >&nbsp;No: of code to Generate </td>
						
                                      <td width="70%"  >:
                                        <input type="text" name="number"> </td>
                                    </tr>
					<tr >
					  <td  >&nbsp;Month Period  </td>
					  <td  >:
					    <select name="month" id="month" class="paragraph">
                            <option value="0">..select..</option>
							<option value="1">4 months</option>
							<option value="2">6 months</option>
							<option value="3">12 months</option> 
                          </select>                      </td>
					  </tr>			   
                                    <tr> 
                                      <td height="51" colspan="3" align="center">
                                        <p>
                                          <input name="update" type="submit" value="Generate">
                                        </p></td>
					</tr>
				    </tbody>
			 	  </table>
			   
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
                      <TD vAlign=top width=564 bgColor=white> 
                       
			   <form name="frmfaqs" action="list_faqs.php" method="post">
                        
				  <table class="paragraph2" cellspacing=0 cellpadding=0 width=553 border=0>
				  <tr>
						<td height="50" align="center" valign="bottom" class="sectionHeading"><?=$heading;?></td>
					</tr>
					<?php if($confMsg != ""){?>
					<tr> <td align="center" class="successAlert"><?=$confMsg?></td> </tr>
					<?php }
						if($errorMsg != ""){
					?>
					<tr>
						<td align="center"  class="successAlert"><?=$errorMsg?></td>
					</tr>
					<?php } ?>
					
					<TR> 
					<TD align="left">
						
				   		<table height="50"  width="100%"class="topActions"><tr>
						<?  if($objGen->_output($_REQUEST['keyword'])){ ?>
							<td valign="middle" width="50"><a href="list_faqs.php?maxrows=<?=$_REQUEST['maxrows']?>"><img src="images/list.gif" alt="Listing Record">&nbsp;List </a></td>
						<? }else{ ?>
							<td valign="middle" width="50" class="noneAnchor"><img src="images/list.gif" alt="Listing Record">&nbsp;List </td>
						<? } ?>
						<td valign="middle" width="50"><a href="addedit_faqs.php?action=add&pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=$objGen->_output($_REQUEST['keyword'])?>"><img src="images/add.gif" border="0" alt="Add Record">&nbsp;Add   </a></td>
						<td valign="middle" class="extraLabels"  align="right">Keyword&nbsp;<input name="keyword" type="text" size="10" value="<?=$objGen->_output($_REQUEST['keyword']);?>">&nbsp;<input type="button" name="search" onClick="javascript:this.form.submit();" value="Search"></td>
						</tr></table>
					</TD>
					</TR>
					
				  </table>
                     <table  cellspacing=0 cellpadding=0 width="553" border=0 class="topColor">
                      <tbody>
	    			    	<tr> 
					   <td width="204" valign=top class="paragraph2"><?=$displayString?>
					   </td>
							
						
					   <td width="166" valign=top class="paragraph2">Language:
                         <select name="langId" class="paragraph" onChange="this.form.submit()">
                           <?
									$string = "";
									while (list ($key, $val) = each ($languageArray)) {
											$string .= "<option value={$key}";
											if($key == $lanId){
												$string .= " selected";
											}
											$string	.= ">{$val}</option>";
   									}
									echo $string;
								?>
                         </select></td>
					   <td width="183" align=right class="paragraph2"><?=$heading;?> per page: 
			
						<select class="paragraph"  size=1 onChange="this.form.submit()" name="maxrows">
						<? foreach($siteRowListConfig as $key=>$data){ ?>
						  <option value="<?=$key?>" <? if($_REQUEST['maxrows']==$key) echo"selected"; ?>><?=$data;?></option>
						 <? } ?>
						</select>
					</td>
				    </tr>	
			     
										
                                
                                </tbody>
                              </table>
				  <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="553">
				   <TBODY> 
					   <TR class="tableHeaderColor">
						<TD width="9%" align="center" >#</TD>
						<TD width="47%" ><?=$heading;?>&nbsp;Name
						
						<a href="list_faqs.php?field=manager_question&type=asc&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&pageNo=<?=$_REQUEST['pageNo']?>&keyword=<?=$objGen->_output($_REQUEST['keyword'])?>"><img src="images/up.gif" border="0" alt="Ascending Sort">&nbsp;</a>
						<a href="list_faqs.php?field=manager_question&type=desc&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&pageNo=<?=$_REQUEST['pageNo']?>&keyword=<?=$objGen->_output($_REQUEST['keyword'])?>"><img src="images/down.gif" border="0" alt="Descending Sort"></a>
						
						
						</TD>
						
						<TD width="8%" align="center" >Status</TD>
						<TD width="24%" align="center" >Action</TD>
					  </TR>
					  <?php if($errMsg != ""){?>
						  <TR class="listingTable"> 
							<TD align="center" colspan="4" ><font color="#FF0000"><?=$errMsg?></font> 
							</TD>
						  </TR>
					 <?php }
					   	
					   	$count = $startNo;
						foreach($result as $row){
							
						?>
						    <tr class="listingTable">
						    	<TD align="center"><?=$count?></TD>
							<TD><?=stripslashes(stripslashes(stripslashes($row['manager_question'])));?></TD>
							
							<TD align="center"><?php if($row['faq_status'] == 1) echo "<img src=\"images/y.gif\" width=\"14\" height=\"14\">"; else echo "<img src=\"images/n.gif\" width=\"14\" height=\"14\">";?></TD>
							<TD align="center">
								<a href = "addedit_faqs.php?faqId=<?=$row['faq_id']?>&pageNo=<?=$_REQUEST['pageNo']?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&type=<?=$_REQUEST['type']?>&keyword=<?=$objGen->_output($_REQUEST['keyword'])?>&action=edit" class="smallLink">Edit</a>&nbsp;
								|
								<a href = "list_faqs.php?faqId=<?=$row['faq_id']?>&pageNo=<?=$_REQUEST['pageNo']?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=$objGen->_output($_REQUEST['keyword'])?>&action=delete" class="smallLink" onClick="return confirm('Are you sure that you want to delete the selected Record? If yes click Ok, if not click Cancel.')">Delete</a></TD> 
								
						    </tr>
						<?php
						$count++;
						}
						?>
					</tbody>
			 	</table>
				<table cellspacing=0 cellpadding=0 width=553 border=0 class="topColor">
                                <tbody>		
					<tr>
						<td align="left" colspan = "6" class="leftmenu">
						<a href="list_faqs.php?pageNo=1&type=<?=$_REQUEST['type']?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keyword=<?=$objGen->_output($_REQUEST['keyword'])?>">
						<img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>
						<a href="list_faqs.php?pageNo=<?=$prev?>&type=<?=$_REQUEST['type']?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keyword=<?=$objGen->_output($_REQUEST['keyword'])?>">
						<img src="images/mail-arrowlg.gif" border=0 width="20" height="20" align="absmiddle" alt="Previous Page"></a>[Page 
						<select name="pageNo" class="paragraph"  onChange="form.submit()">
							<?php
							if($noOfPage){
								for($i = 1; $i <= $noOfPage; $i++){
							?>
								<option value="<?=$i?>" <? if($i==$_REQUEST['pageNo']) echo "selected";?>><?=$i?></option>
							<?php
								}
							}
							else{
								echo "<option value=\"\">0</option>";
							}
							?>
						</select>
							 of <?=$noOfPage?>]
							 <a href="list_faqs.php?pageNo=<?=$next?>&type=<?=$_REQUEST['type']?>&langId=<?=$lanId?>&field=<?=$_REQUEST['field']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=$objGen->_output($_REQUEST['keyword'])?>">
							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>
							<a href="list_faqs.php?pageNo=<?=$noOfPage?>&type=<?=$_REQUEST['type']?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keyword=<?=$objGen->_output($_REQUEST['keyword'])?>">
							<img src="images/mail-arrowlastg.gif" border=0 width="20" height="20" align="absmiddle" alt="Last Page"></a>
						</td>
					</tr>
					
					
                              	
				   </tbody>
			 	</table>
				<input type="hidden" name="type" value="<?=$_REQUEST['type']?>">
				<input type="hidden" name="field" value="<?=$_REQUEST['field']?>">
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
</body>
</html>
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
	include("../includes/classes/class.testimonials.php");
	include("../includes/classes/class.reseller.php");
				 
	error_reporting(0);
	
	if($_REQUEST['langId'] != ""){
		$lanId = $_REQUEST['langId'];
	}
	else{
		$lanId = 1;
	}
	
	/*
	Take all the languages to an array.
	*/
	$languageArray = $siteLanguagesConfig;
	reset($languageArray);
		
	/*
	 Instantiating the classes.
	*/
	$objTestimon	 = new Testimonial($lanId);
	$objTesti	 = new reseller($lanId);
	$objGen  		 =	new General();
	
	$heading = "Code List Tracker";
			
	//Sorting field decides here
	if($_REQUEST['field']){
		$field = $_REQUEST['field'];
		$type = $_REQUEST['type'];
	}else{
		$field = "reseller_id";
		$type = "ASC";
	}
	
	
	
	if($_REQUEST['keywordb'])
	{
			$keywordb	=	$_REQUEST['keywordb'];		
	}
	else if($_POST['keywordb'])
	{
	 $keywordb	=	$_POST['keywordb'];
	}
	else
	{
	$keywordb='0';
	}
	
	##############################################################################################################
	/*                 Following Code is for doing  Gift code list                                              */
	##############################################################################################################
	 
	 $totalRecsb = $objTesti->_getTotalCountCodeList($keywordb);
	if($totalRecsb <= 0)
		$errMsg2 = "No Records";
	
	if(!$_REQUEST['maxrowsb'])
	{  
	    if($_POST['maxrowsb'])
		$_REQUEST['maxrowsb'] = $_POST['maxrowsb'];
		else
		$_REQUEST['maxrowsb'] =10;
	}
		//echo $_REQUEST['maxrowsb'];
	if($_REQUEST['pageNob']){
		if($_REQUEST['pageNob']*$_REQUEST['maxrowsb'] >= $totalRecsb+$_REQUEST['maxrowsb']){
			$_REQUEST['pageNob'] = 1;
		}
		$result2 =  $objTesti->_showPageCodeList($keywordb,$totalRecsb,$_REQUEST['pageNob'],$_REQUEST['maxrowsb']);
	}
	else{ 
	/***********************Selects Records at initial stage***********************************************/
		$_REQUEST['pageNob'] = 1;
		$result2 = $objTesti->_showPageCodeList($keywordb,$totalRecsb,$_REQUEST['pageNob'],$_REQUEST['maxrowsb']);
		//print_r($resultb);
		if(count($result2) <= 0)
			$errMsg3 = "No Records.";
		}
		
	if($totalRecsb <= $_REQUEST['pageNob']*$_REQUEST['maxrowsb'])
	{
		//For showing range of displayed records.
		if($totalRecsb <= 0)
			$startNob = 0;
		else
			$startNob = $_REQUEST['pageNob']*$_REQUEST['maxrowsb']-$_REQUEST['maxrowsb']+1;
		$endNob = $totalRecsb;
		$displayStringb = "Viewing $startNob to $endNob of $endNob List's";
		
	}
	else
	{
		//For showing range of displayed records.
		if($totalRecsb <= 0)
			$startNob = 0;
		else
			$startNob = $_REQUEST['pageNob']*$_REQUEST['maxrowsb']-$_REQUEST['maxrowsb']+1;
		$endNob = $_REQUEST['pageNob']*$_REQUEST['maxrowsb'];
		$displayStringb = "Viewing $startNob to $endNob of $totalRecsb List's";
		
	}
	//Pagin 
	
	$noOfPageb = @ceil($totalRecsb/$_REQUEST['maxrowsb']); 
	if($_REQUEST['pageNob'] == 1){
		$prevb = 1;
	}
	else
		$prevb = $_REQUEST['pageNob']-1;
	if($_REQUEST['pageNob'] == $noOfPageb){
		$nextb = $_REQUEST['pageNob'];
	}
	else
		$nextb = $_REQUEST['pageNob']+1;
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
						<td align="center"><?php echo $message; ?></td>
					</tr>
					<?php } ?>
					<TR height="20"><TD align="left">&nbsp;</TD></TR>
					
				  </table>
				  
                 
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
                      <TD width=564 vAlign=top bgColor=white> 
                       
			   <form name="frmgcode" action="reseller_codelist.php" method="post">
                        
                     <table  cellspacing=0 cellpadding=0 width="553" border=0 class="topColor">
                      <tbody>
					  <TR>
					    <th height="37" colspan="3" align="left"> Code List </th>
					  <TR>
	    			    	<tr> 
					   <td width="204" colspan="2" valign=top class="paragraph2">Type:<select class="paragraph2" onChange="this.form.submit()" name="keywordb">
					   <option value="0" <?php if($keywordb=='0') {?> selected="selected"<?php }?>>ALL</option>
					   <option <?php if($keywordb=='purchased') {?> selected="selected"<?php }?>>purchased</option>
					   <option <?php if($keywordb=='used') {?> selected="selected"<?php }?>>used</option>
					   </select>
					   </td>
							
						
					   
					   <td width="183" align=right class="paragraph2">View per page: 
			
						<select class="paragraph"  size=1 onChange="this.form.submit()" name="maxrowsb">
						<? foreach($siteRowListConfig as $key=>$data){ ?>
						  <option value="<?=$key?>" <? if($_REQUEST['maxrowsb']==$key) echo"selected"; ?>><?=$data;?></option>
						 <? } ?>
						</select>
					</td>
				    </tr>	
			     
										
                                
                                </tbody>
                              </table>
				  <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="553">
				   <TBODY> 
					   <TR class="tableHeaderColor">
						<TD width="5%" align="center" >#</TD>
						<TD width="23%" >Gift Code</TD>
						<TD width="28%" >Reseller ID </TD>
						<TD width="8%" >Date</TD>
						
						<TD width="23%" align="center" >Purchased By </TD>
						<TD width="13%" align="center" >Amount</TD>
					  </TR>
					  <?php if($errMsg2 != ""){?>
						  <TR class="listingTable"> 
							<TD align="center" colspan="5" ><font color="#FF0000"><?=$errMsg2?></font> 
							</TD>
						  </TR>
					 <?php }
					   	
					   	$count = $startNob;
						foreach($result2 as $row){
							
						?>
						    <tr class="listingTable">
						    	<TD align="center"><?=$count?></TD>
							<TD align="center"><?=$row['code'];?></TD>
							<TD align="center"><?=$row['reseller_id'];?></TD> 
							<TD align="center"><?=$row['purchasedate'];?></TD>
							<TD align="center"><?=$row['email'];?></TD>
							<TD align="center"><?=$row['codeamount'];?></TD>
								
						    </tr>
						<?php
						$count++;
						}
						?>
						<!--<tr><td colspan="8" align="right"> <?php// echo $totalamount;?></tr>-->
					</tbody>
			 	</table>
				<table cellspacing=0 cellpadding=0 width=553 border=0 class="topColor">
                                <tbody>		
					<tr>
						<td align="left" colspan = "6" class="leftmenu">
						<a href="reseller_codelist.php?pageNob=1&typeb=<?=$_REQUEST['typeb']?>&langIdb=<?=$lanIdb?>&maxrowsb=<?=$_REQUEST['maxrowsb']?>&fieldb=<?=$_REQUEST['fieldb']?>&keywordb=<?=$_REQUEST['keywordb']?>">
						<img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>
						<a href="reseller_codelist.php?pageNob=<?=$prevb?>&typeb=<?=$_REQUEST['typeb']?>&langIdb=<?=$lanIdb?>&maxrowsb=<?=$_REQUEST['maxrowsb']?>&fieldb=<?=$_REQUEST['fieldb']?>&keywordb=<?=$_REQUEST['keywordb']?>">
						<img src="images/mail-arrowlg.gif" border=0 width="20" height="20" align="absmiddle" alt="Previous Page"></a>[Page 
						<select name="pageNob" class="paragraph"  onChange="form.submit()">
							<?php
							if($noOfPageb){
								for($i = 1; $i <= $noOfPageb; $i++){
							?>
								<option value="<?=$i?>" <? if($i==$_REQUEST['pageNob']) echo "selected";?>><?=$i?></option>
							<?php
								}
							}
							else{
								echo "<option value=\"\">0</option>";
							}
							?>
						</select>
							 of <?=$noOfPageb?>]
							 <a href="reseller_codelist.php?pageNob=<?=$nextb?>&typeb=<?=$_REQUEST['typeb']?>&langIdb=<?=$lanIdb?>&fieldb=<?=$_REQUEST['fieldb']?>&maxrowsb=<?=$_REQUEST['maxrowsb']?>&keywordb=<?=$_REQUEST['keywordb']?>">
							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>
							<a href="reseller_codelist.php?pageNob=<?=$noOfPageb?>&typeb=<?=$_REQUEST['typeb']?>&langIdb=<?=$lanIdb?>&maxrowsb=<?=$_REQUEST['maxrowsb']?>&fieldb=<?=$_REQUEST['fieldb']?>&keywordb=<?=$_REQUEST['keywordb']?>">
							<img src="images/mail-arrowlastg.gif" border=0 width="20" height="20" align="absmiddle" alt="Last Page"></a>
						</td>
					</tr>
					
					
                              	
				   </tbody>
			 	</table>
				<input type="hidden" name="typeb" value="<?=$_REQUEST['typeb']?>">
				<input type="hidden" name="fieldb" value="<?=$_REQUEST['fieldb']?>">
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
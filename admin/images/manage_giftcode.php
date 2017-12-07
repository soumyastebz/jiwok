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
	//include_once('define.php');
	
	
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
	   
	   //echo "1";
	   $no=trim($_POST['number']);
	   $month=$_POST['month'];
	   $membqry="SELECT `membership_fee` FROM `settings`";
       $membreslt = $GLOBALS['db']->getAll($membqry, DB_FETCHMODE_ASSOC);
	  $fee= $membreslt[0]['membership_fee'];
	   switch($month)
	   {
	     case 1: {$period="4 months";$amt=4*$fee;}break;
		 case 2: {$period="6 months";$amt=6*$fee; }break;
		  case 3: {$period="12 months";$amt=12*$fee; }break;
	   }
	   for($i=0;$i<$no;$i++)
	   {   //echo "2no:".$no."....";
	   $unique=false;
	   	  	do
			{
			  $cap_code = get_code();
			  //echo $numchk=$obj->_getchecked($cap_code);
			  $numchk=$obj->new1($cap_code);
			  if($numchk ==0)
			     {$unique=true;}	 
			} while(!$unique); 
		   $gcode[]=$cap_code;
		  // print_r($gcode);
		   //$gcode[]=get_code();
	   }
	   $message="Generated successfully";
	   for($i=0;$i<$no;$i++)
	   {  
	   	  	//echo "4";
			$result1=$obj->_insertgift($period,$amt,$gcode[$i]);
			if($result1== false)
			{
			  $message="";
			}
			
	   }
	   
	   
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
	
	if($_REQUEST['keyb'])
	{
			$keyb	=	$_REQUEST['keyb'];		
	}
	else if($_POST['keyb'])
	{
	 $keyb	=	$_POST['keyb'];
	}
	else
	{
	$keyb='0';
	}
	
	##############################################################################################################
	/*                 Following Code is for doing paging  of Gift code payment list                                              */
	##############################################################################################################
	 $totalRecs = $obj->_getTotalCount();
	if($totalRecs <= 0)
		$errMsg = "No Records";

	if(!$_REQUEST['maxrows'])
		$_REQUEST['maxrows'] = $_POST['maxrows'];
	if($_REQUEST['pageNo']){
		if($_REQUEST['pageNo']*$_REQUEST['maxrows'] >= $totalRecs+$_REQUEST['maxrows']){
			$_REQUEST['pageNo'] = 1;
		}
		$result =  $obj->_showPage($totalRecs,$_REQUEST['pageNo'],$_REQUEST['maxrows']);
	}
	else{ 
	/***********************Selects Records at initial stage***********************************************/
		$_REQUEST['pageNo'] = 1;
		$result = $obj->_showPage($totalRecs,$_REQUEST['pageNo'],$_REQUEST['maxrows']);
		////////print_r($result);
		if(count($result) <= 0)
			$errMsg = "No Records.";
		}
		
	if($totalRecs <= $_REQUEST['pageNo']*$_REQUEST['maxrows'])
	{
		//For showing range of displayed records.
		if($totalRecs <= 0)
			$startNo = 1;
		else
			$startNo = $_REQUEST['pageNo']*$_REQUEST['maxrows']-$_REQUEST['maxrows']+1;
		$endNo = $totalRecs;
		$displayString = "Viewing $startNo to $endNo of $endNo FAQs";
		
	}
	else
	{
		//For showing range of displayed records.
		if($totalRecs <= 0)
			$startNo = 1;
		else
			$startNo = $_REQUEST['pageNo']*$_REQUEST['maxrows']-$_REQUEST['maxrows']+1;
		$endNo = $_REQUEST['pageNo']*$_REQUEST['maxrows'];
		$displayString = "Viewing $startNo to $endNo of $totalRecs FAQ's";
		
	}
	//Pagin 
	
	$noOfPage = @ceil($totalRecs/$_REQUEST['maxrows']); 
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
	
	##############################################################################################################
	/*                 Following Code is for doing  Gift code list                                              */
	##############################################################################################################
	 $totalamount = $obj->_getTotalAmount($keywordb,$keyb);
	 $totalRecsb = $obj->_getTotalCountCodeList($keywordb,$keyb);
	if($totalRecsb <= 0)
		$errMsg2 = "No Records";
	
	if(!$_REQUEST['maxrowsb'])
	{  
	    if($_POST['maxrowsb'])
		$_REQUEST['maxrowsb'] = $_POST['maxrowsb'];
		else
		$_REQUEST['maxrowsb'] =10;
	}
		
	if($_REQUEST['pageNob']){
		if($_REQUEST['pageNob']*$_REQUEST['maxrowsb'] >= $totalRecsb+$_REQUEST['maxrowsb']){
			$_REQUEST['pageNob'] = 1;
		}
		$result2 =  $obj->_showPageCodeList($keywordb,$keyb,$totalRecsb,$_REQUEST['pageNob'],$_REQUEST['maxrowsb']);
	}
	else{ 
	/***********************Selects Records at initial stage***********************************************/
		$_REQUEST['pageNob'] = 1;
		$result2 = $obj->_showPageCodeList($keywordb,$keyb,$totalRecsb,$_REQUEST['pageNob'],$_REQUEST['maxrowsb']);
		//print_r($resultb);
		if(count($result2) <= 0)
			$errMsg3 = "No Records.";
		}
		
	if($totalRecsb <= $_REQUEST['pageNob']*$_REQUEST['maxrowsb'])
	{
		//For showing range of displayed records.
		if($totalRecsb <= 0)
			$startNob = 1;
		else
			$startNob = $_REQUEST['pageNob']*$_REQUEST['maxrowsb']-$_REQUEST['maxrowsb']+1;
		$endNob = $totalRecsb;
		$displayStringb = "Viewing $startNob to $endNob of $endNob List's";
		
	}
	else
	{
		//For showing range of displayed records.
		if($totalRecsb <= 0)
			$startNob = 1;
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
            </table><p>&nbsp;</p>

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
                      <TD width=574 vAlign=top bgColor=white> 
                       
			   <form name="frmfaqs" action="manage_giftcode.php" method="post">
                        
                     <table  cellspacing=0 cellpadding=0 width="553" border=0 class="topColor">
                      <tbody>
					  <TR><th height="32" colspan="3" align="left">Gift Code Payment List</th>
					  <TR>
	    			    	<tr> 
					   <td width="204" valign=top class="paragraph2">
					   </td>
							
						
					   <td width="166" valign=top class="paragraph2">&nbsp;</td>
					   <td width="183" align=right class="paragraph2">View per page: 
			
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
						<TD width="4%" align="center" >#</TD>
						<TD width="16%" >Gift Code</a>						</TD>
						<TD width="16%" >Code Type</a>						</TD>
						<TD width="23%" >Customer email</a>						</TD>
						
						<TD width="13%" align="center" >Payment date </TD>
						<TD width="14%" align="center" >Status </TD>
						<TD width="14%" align="center" >Amount</TD>
					  </TR>
					  <?php if($errMsg != ""){?>
						  <TR class="listingTable"> 
							<TD align="center" colspan="6" ><font color="#FF0000"><?=$errMsg?></font> 
							</TD>
						  </TR>
					 <?php }
					   	
					   	$count = $startNo;
						foreach($result as $row){
							
						?>
						    <tr class="listingTable">
						    	<TD align="center"><?=$count?></TD>
								<TD><?=$row['code'];?></TD>
								<TD><?=$row['codetype'];?></TD>
							<TD><?=$row['email'];?></TD>
							
							<TD align="center"><?=$row['purchasedate'];?></TD>
							<TD align="center"><?=$row['codestatus'];?></TD>
							<TD align="center"><?=$row['codeamount'];?></TD> 
								
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
						<a href="manage_giftcode.php?pageNo=1&type=<?=$_REQUEST['type']?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keyword=<?=$objGen->_output($_REQUEST['keyword'])?>">
						<img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>
						<a href="manage_giftcode.php?pageNo=<?=$prev?>&type=<?=$_REQUEST['type']?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keyword=<?=$objGen->_output($_REQUEST['keyword'])?>">
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
							 <a href="manage_giftcode.php?pageNo=<?=$next?>&type=<?=$_REQUEST['type']?>&langId=<?=$lanId?>&field=<?=$_REQUEST['field']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=$objGen->_output($_REQUEST['keyword'])?>">
							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>
							<a href="manage_giftcode.php?pageNo=<?=$noOfPage?>&type=<?=$_REQUEST['type']?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keyword=<?=$objGen->_output($_REQUEST['keyword'])?>">
							<img src="images/mail-arrowlastg.gif" border=0 width="20" height="20" align="absmiddle" alt="Last Page"></a>
						</td>
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
			
			<p>&nbsp;</p>
			
			
			
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
                      <TD width=577 vAlign=top bgColor=white> 
                       
			   <form name="frmgcode" action="manage_giftcode.php" method="post">
                        
                     <table  cellspacing=0 cellpadding=0 width="567" border=0 class="topColor">
                      <tbody>
					  <TR><th height="37" colspan="3" align="left"> Code List</th>
					  <TR>
	    			    	<tr> 
					   <td colspan="2" valign=top class="paragraph2">Type:
					     <select class="paragraph2" onChange="this.form.submit()" name="keywordb">
					   <option <?php if($keywordb=='0') {?> selected="selected"<?php }?>value="0">All Type</option>
					   <option <?php if($keywordb=='unused') {?> selected="selected"<?php }?>>unused</option>
					   <option <?php if($keywordb=='purchased') {?> selected="selected"<?php }?>>purchased</option>
					   <option <?php if($keywordb=='used') {?> selected="selected"<?php }?>>used</option>
					   </select>&nbsp;&nbsp;
					   Period:
					     <select class="paragraph2" onChange="this.form.submit()" name="keyb">
					   <option <?php if($keyb=='0') {?> selected="selected"<?php }?>value="0">All Type</option>
					   <option <?php if($keyb=='1') {?> selected="selected"<?php }?>value="1">4 months</option>
					   <option <?php if($keyb=='2') {?> selected="selected"<?php }?> value="2">6 months</option>
					   <option <?php if($keyb=='3') {?> selected="selected"<?php }?> value="3">12 months</option>
					   </select>
					   </td>
							
						
					   
					   <td width="197" align=right class="paragraph2">View per page: 
			
						<select class="paragraph"  size=1 onChange="this.form.submit()" name="maxrowsb">
						<? foreach($siteRowListConfig as $key=>$data){ ?>
						  <option value="<?=$key?>" <? if($_REQUEST['maxrowsb']==$key) echo"selected"; ?>><?=$data;?></option>
						 <? } ?>
						</select>
					</td>
				    </tr>	
			     
										
                                
                                </tbody>
                              </table>
				  <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="565">
				   <TBODY> 
					   <TR class="tableHeaderColor">
						<TD width="5%" align="center" >#</TD>
						<TD width="23%" >Gift Code</TD>
						<TD width="23%" >Code Type</TD>
						<TD width="29%" >Bought by</TD>
						<TD width="7%" >Date</TD>
						<TD width="23%" align="center" >Used by  </TD>
						<TD width="13%" align="center" >Date</TD>
					  </TR>
					  <?php if($errMsg2 != ""){?>
						  <TR class="listingTable"> 
							<TD align="center" colspan="7" ><font color="#FF0000"><?=$errMsg2?></font> 
							</TD>
						  </TR>
					 <?php }
					   	$count = $startNob;
						foreach($result2 as $row){
						?>
						    <tr class="listingTable">
						    	<TD align="center"><?=$count?></TD>
								<TD><?=$row['code'];?></TD>
								<TD><?=$row['codetype'];?></TD>
								<TD><?=$row['email'];?></TD>
								<TD align="center"><?=$row['purchasedate'];?></TD>
								<TD align="center"><?=$row['user_email'];?></TD> 
								<TD align="center"><?=$row['usedate'];?></TD>
						    </tr>
						<?php
						$count++;
						}
						?>
						<tr><td colspan="6" align="right"> <?php echo $totalamount."  Euro";?></tr>
					</tbody>
			 	</table>
				<table cellspacing=0 cellpadding=0 width=569 border=0 class="topColor">
                                <tbody>		
					<tr>
						<td width="569" colspan = "6" align="left" class="leftmenu">
						<a href="manage_giftcode.php?pageNob=1&typeb=<?=$_REQUEST['typeb']?>&langIdb=<?=$lanIdb?>&maxrowsb=<?=$_REQUEST['maxrowsb']?>&fieldb=<?=$_REQUEST['fieldb']?>&keywordb=<?=$_REQUEST['keywordb']?>&keyb=<?=$_REQUEST['keyb']?>">
						<img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>
						<a href="manage_giftcode.php?pageNob=<?=$prevb?>&typeb=<?=$_REQUEST['typeb']?>&langIdb=<?=$lanIdb?>&maxrowsb=<?=$_REQUEST['maxrowsb']?>&fieldb=<?=$_REQUEST['fieldb']?>&keywordb=<?=$_REQUEST['keywordb']?>&keyb=<?=$_REQUEST['keyb']?>">
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
							 <a href="manage_giftcode.php?pageNob=<?=$nextb?>&typeb=<?=$_REQUEST['typeb']?>&langIdb=<?=$lanIdb?>&fieldb=<?=$_REQUEST['fieldb']?>&maxrowsb=<?=$_REQUEST['maxrowsb']?>&keywordb=<?=$_REQUEST['keywordb']?>&keyb=<?=$_REQUEST['keyb']?>">
							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>
							<a href="manage_giftcode.php?pageNob=<?=$noOfPageb?>&typeb=<?=$_REQUEST['typeb']?>&langIdb=<?=$lanIdb?>&maxrowsb=<?=$_REQUEST['maxrowsb']?>&fieldb=<?=$_REQUEST['fieldb']?>&keywordb=<?=$_REQUEST['keywordb']?>&keyb=<?=$_REQUEST['keyb']?>">
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
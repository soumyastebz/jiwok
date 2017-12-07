<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-Gift code manage section
   Programmer	::> Shilpa
   Date			::> 26/01/2013
   
   DESCRIPTION	::::>>>>
   Admin can manage the gift codes
*****************************************************************************/

	include_once('includeconfig.php');
	include_once('../includes/classes/class.geo_giftcode.php');
	include('geo_giftcodeGenerate.php');
	//include_once('define.php');
	
	
	$obj= new Giftcode();

	$heading 		= "Manage Gift Code";
	$errorMsg		=	array();
	?>
	<?php
	$objGen   	 	=	new General();
	if($_POST['Publish'])
	{
		if(isset($_POST['chk'])){
		
		  if (is_array($_POST['chk'])) {
			foreach($_POST['chk'] as $value){
			$res	=	 $obj->_updateDiscgeo($value);
			}
		  } else {
			$res	=	$obj->_updateDiscgeo($_POST['chk']);
		 }
		 if($res==false)
		 {
		  $msge="";
		}
		else
		{
			$msge	=	"Published successfully";
		}
			
	}
	else
	{
		$errmessage="Select a code";
	}
		
}
		//$obj->_deletegift(base64_decode($_REQUEST['code']));
	
	if($_POST['update']=='Generate')
	{//On clicking generate
	   
	   $no				=	trim($_POST['number']);
	   $isreuse			=	trim($_POST['reusable']);
	   $reusetimes		=	trim($_POST['reusetimes']);	
	   $totreuse		=	trim($_POST['reusage']);	
	   $expiry_usage	=	trim($_POST['expiry_usage']);	

	   for($i=0;$i<$no;$i++)
	   {   //echo "2no:".$no."....";
	   $unique=false;
	   	  	do
			{
			  $cap_code = get_giftcode();
			  //echo $numchk=$obj->_getchecked($cap_code);
			  $numchk=$obj->generatecode($cap_code);
			  if($numchk ==0)
			     {$unique=true;}	 
			} while(!$unique); 
		   $gcode[]=$cap_code;
		  // print_r($gcode);
		   //$gcode[]=get_code();
	   }
	   
	   for($i=0;$i<$no;$i++)
	   {  
	   	  	//echo "4";
			$result1=$obj->_insertdisc_geoadmin($gcode[$i], $isreuse,$reusetimes,$totreuse,$expiry_usage);
			if($result1== false)
			{
			  $msggenerate		=	"";
			}
			else
			{
				$msggenerate	=	"Generated successfully";
			}
			
	   }
	   
	}
	
	
	##############################################################################################################
	/*                 Following Code is for doing paging  of status1                                              */
	##############################################################################################################
	 $totalRecs = $obj->_getTotalCountgeoupdstatus();
	if($totalRecs <= 0)
		$errMsg = "No Records";

	if(!$_REQUEST['maxrows'])
		$_REQUEST['maxrows'] = $_POST['maxrows'];
	if($_REQUEST['pageNo']){
		if($_REQUEST['pageNo']*$_REQUEST['maxrows'] >= $totalRecs+$_REQUEST['maxrows']){
			$_REQUEST['pageNo'] = 1;
		}
		$result =  $obj->_showPagegeostatus1($totalRecs,$_REQUEST['pageNo'],$_REQUEST['maxrows']);
	}
	else{ 
	/***********************Selects Records at initial stage***********************************************/
		$_REQUEST['pageNo'] = 1;
		$result = $obj->_showPagegeostatus1($totalRecs,$_REQUEST['pageNo'],$_REQUEST['maxrows']);
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
	/*                 Following Code is for doing  Gift code status 0                                            */
	##############################################################################################################
	 $totalRecsb = $obj->_getTotalCountgeo();
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
		$result2 =  $obj->_showPagegeostatus0($totalRecsb,$_REQUEST['pageNob'],$_REQUEST['maxrowsb']);
	}
	else{ 
	/***********************Selects Records at initial stage***********************************************/
		$_REQUEST['pageNob'] = 1;
		$result2 = $obj->_showPagegeostatus0($totalRecsb,$_REQUEST['pageNob'],$_REQUEST['maxrowsb']);
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
<script language="javascript" src="js/mask.js"></script>
<link href="./js/jscalendar/calendar-blue.css" rel="stylesheet" type="text/css" media="all">
<script language="javascript" src="./js/jscalendar/calendar.js"></script>
<script language="javascript" src="./js/jscalendar/calendar-en.js"></script>
<script language="javascript" src="./js/jscalendar/calendar-setup.js"></script>

<script language="JavaScript" src="../calendar/tigra/calendar_us.js"></script>
<link rel="stylesheet" href="../calendar/tigra/calendar.css">
<script type="text/javascript">

function validate(frmcode)
{

  var iNum= "0123456789";
   if(document.frmcode.number.value=='') 
  {
     alert("Please Enter no:of codes");
   
	 return false;
  }
 
  
  else
  {
  	
	 if(document.frmcode.number.value!='')
	{
			for(var i=0; i<document.frmcode.number.value.length; i++)
			{
				if((iNum.indexOf(document.frmcode.number.value.charAt(i))==-1)||(document.frmcode.number.value=='0'))
			  {
				alert("Please Enter valid no :of codes!");
				
				return false;
			  }
	  
		   }
	}
	  if (document.frmcode.reusable[0].checked){

			if(document.frmcode.reusetimes.value=='')
			{
				alert("Please Enter reusable times!");
				
				return false;
			}
			else
			{
				for(var i=0; i<document.frmcode.reusetimes.value.length; i++)
				{
					if((iNum.indexOf(document.frmcode.reusetimes.value.charAt(i))==-1)||(document.frmcode.reusetimes.value=='0'))
			  		{
						alert("Please Enter a valid reusable time!");
						return false;
			 		 }
	  
		  	      }
				  if((document.frmcode.reusetimes.value)<(document.frmcode.reusage.value))
				  {
				  	
				  	alert("Reusable times should be greater than total no:of reusage");
					return false;
				  }
				  else
				  {
				  	frmcode.submit;
					return true;
				  }
			}
			
		}
		else
		{
			frmcode.submit;
			return true;
		}
	}
}


function show_reusetimes(reusable)
{
	if(reusable.value=="Yes")
	{
		document.getElementById('reusetime').style.display = 'block';
	}
	else
	{
		document.getElementById('reusetime').style.display = 'none';
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
						<td height="53" align="center" valign="bottom" class="sectionHeading"><?=$heading;?></td>
					</tr>
					<?php 
						if($msggenerate){?>
					<tr>
						<td align="center"><?php echo $msggenerate; ?></td>
					</tr>
					<?php } ?>
					<TR height="20"><TD align="left">&nbsp;</TD></TR>
					
				  </table>
				  
                 <form name="frmcode" action="geo_manage_giftcode.php" method="post" >         
				  <table cellSpacing=1 cellPadding=0 width="100%" bgcolor="">
				   <tbody> 
				   <tr >
				     <th colspan="2" align="left">Generate Gift Code</th>
				     </tr>
				   <tr >
				     <td width="30%" height="30" >&nbsp;No: of code to Generate </td>
						
                                      <td width="70%"  >:
                                        <input type="text" name="number"/> </td>
                   </tr>
                    <tr >
				     <td width="30%" height="30" >&nbsp;Reusable </td>
                                      <td width="70%"  >:
                                        <input type="radio" name="reusable" value="Yes" onChange="show_reusetimes(this);" id="reusable" >&nbsp;Yes
                                        <input type="radio" name="reusable" value="No" checked="checked" onChange="show_reusetimes(this);" id="reusable">&nbsp;No                     </td>
                   </tr>
				   <tr>
				     <td  colspan="2" >
						 <table width="100%" cellSpacing=1 cellPadding=0 id="reusetime" bgcolor="" style="display:none;">
						 <tbody width="100%">
							  <tr>
								<td width="30%">&nbsp;Reusable times</td>
								<td >&nbsp;:<input type="text" name="reusetimes" style="margin-left:6px;"/></td>
							  </tr>
							  <tr>
								<td width="30%">&nbsp;Total No:of Reusable Usage</td>
								<td width="70%">&nbsp;:<input type="text" name="reusage" value="" style="margin-left:6px;"/></td>
							  </tr>
						</tbody>
						</table>					 
					</td>
                  </tr>
				   <tr><td colspan="2">
				   <table cellSpacing=1 cellPadding=0 width="100%" bgcolor="" id="id_exp" >
					<tbody width="100%">
					   <tr>
						 <td width="166" >&nbsp;Expiry Date </td>
						 <td width="389" >:
								<input type="text" name="expiry_usage" readonly />
								<script language="JavaScript">
								new tcal ({
									// form name
									'formname': 'frmcode',
									// input name
									'controlname': 'expiry_usage'
								});

								</script>						 </td>
					   </tr>
					   </tbody>
                   </table>
                   </td></tr>
								   
                                    <tr> 
                                      <td height="51" colspan="3" align="center">
                                        <p>
                                          <input name="update" type="submit" value="Generate" onClick="return validate(this.form);">
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
				<TR><?php 
						if($msge){?>
					<tr>
						<td align="center"><?php echo $msge; ?></td>
					</tr>
					<?php } 
						else if($errmessage){?>
					<tr>
						<td align="center"><font color="#FF0000"><?php echo $errmessage; ?></font></td>
					</tr>
					<?php } ?>
					<TR height="20"><TD align="left">&nbsp;</TD></TR>
                    <TR> 
                      <TD width=574 vAlign=top bgColor=white> 
                       
			   <form name="frmfaqs" action="geo_manage_giftcode.php" method="post">
                        
                     <table  cellspacing=0 cellpadding=0 width="553" border=0 class="topColor">
                      <tbody>
					  <TR><th height="32" colspan="3" align="center">Gift Codes of status 0</th>
					  <TR>
	    			    	<tr> 
					    
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
					   	<td align="center">&nbsp; </td>
						<TD align="center" >#</TD>
						<TD align="center">Gift Code</TD>
						<TD align="center">Expiration Date</TD>
						<TD align="center" >Reusable Status</TD>
						<TD align="center">Reusable Times</TD>
						<TD align="center">Total No:of Reusage</TD>
						<TD align="center" >Status</TD>
						
					  </TR>
					 <?php
					  if(($errMsg2 != "")||($errMsg3 != "")){?>
						  <TR class="listingTable"> 
							<TD align="center" colspan="10" ><font color="#FF0000"><?php if($errMsg2 != ""){echo $errMsg2; }else if($errMsg3 != ""){echo $errMsg3;}?></font> 
							</TD>
						  </TR>
					 <?php }
					   	$count	=	$startNob;
						foreach($result2 as $row){
							
						?>
						    <tr class="listingTable">
								<TD align="center" ><input type="checkbox" name="chk[]" value="<?=$row['code']; ?>"></TD>
						    	<TD align="center"><?=$count?></TD>
								<TD><?=$row['code'];?></TD>
								<TD><?=$row['expiration_date'];?></TD>
								<TD><?=$row['is_reusable'];?></TD>
								<TD><?=$row['reusable_times'];?></TD>
								<TD><?=$row['total_number_of_reusable_usage'];?></TD>
								<TD><?=$row['status'];?></TD>
						    </tr>
						<?php
						$count++;
						}
						?>
					</tbody>
					 
			 	</table>
				<table class="listTableStyle" cellSpacing=1 cellPadding=2 width="553">
				<tr><td align="center"><input type="submit" name="Publish" value="Publish" align="middle" onClick="javascript:this.form.submit();"/></td></tr>
				</table>
				<table cellspacing=0 cellpadding=0 width=553 border=0 class="topColor">
                                <tbody>		
					<tr>
						<td align="left" colspan = "6" class="leftmenu">
						<a href="geo_manage_giftcode.php?pageNob=1&type=<?=$_REQUEST['type']?>&maxrowsb=<?=$_REQUEST['maxrowsb']?>&field=<?=$_REQUEST['field']?>">
						<img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>
						<a href="geo_manage_giftcode.php?pageNob=<?=$prev?>&type=<?=$_REQUEST['type']?>&maxrowsb=<?=$_REQUEST['maxrowsb']?>&field=<?=$_REQUEST['field']?>">
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
							 <a href="geo_manage_giftcode.php?pageNob=<?=$nextb?>&type=<?=$_REQUEST['type']?>&field=<?=$_REQUEST['field']?>&maxrowsb=<?=$_REQUEST['maxrowsb']?>">
							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>
							<a href="geo_manage_giftcode.php?pageNob=<?=$noOfPageb?>&type=<?=$_REQUEST['type']?>&maxrowsb=<?=$_REQUEST['maxrowsb']?>&field=<?=$_REQUEST['field']?>">
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
                      <TD width=574 vAlign=top bgColor=white> 
                       
			   <form name="frmfaqs" action="geo_manage_giftcode.php" method="post">
                        
                     <table  cellspacing=0 cellpadding=0 width="553" border=0 class="topColor">
                      <tbody>
					  <TR><th height="32" colspan="3" align="center">Published Gift Codes</th>
					  <TR>
	    			    	<tr> 
					    
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
						<TD align="center" >#</TD>
						<TD align="center">Gift Code</TD>
						<TD align="center">Expiration Date</TD>
						<TD align="center">Reusable Status</TD>
						<TD align="center">Reusable Times</TD>
						<TD align="center">Total No:of Reusage</TD>
						<TD align="center" >Status</TD>
						
					  </TR>
					 
					  <?php if($errMsg != ""){?>
						  <TR class="listingTable"> 
							<TD align="center" colspan="10" ><font color="#FF0000"><?=$errMsg?></font> 
							</TD>
						  </TR>
					 <?php }
					   	$count	=	$startNo;
						foreach($result as $row){
							
						?>
						    <tr class="listingTable">
						    	<TD align="center"><?=$count?></TD>
								<TD><?=$row['code'];?></TD>
								<TD><?=$row['expiration_date'];?></TD>
								<TD><?=$row['is_reusable'];?></TD>
								<TD><?=$row['reusable_times'];?></TD>
								<TD><?=$row['total_number_of_reusable_usage'];?></TD>
								<TD><?=$row['status'];?></TD>
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
						<a href="geo_manage_giftcode.php?pageNo=1&type=<?=$_REQUEST['type']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>">
						<img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>
						<a href="geo_manage_giftcode.php?pageNo=<?=$prev?>&type=<?=$_REQUEST['type']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>">
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
							 <a href="geo_manage_giftcode.php?pageNo=<?=$next?>&type=<?=$_REQUEST['type']?>&field=<?=$_REQUEST['field']?>&maxrows=<?=$_REQUEST['maxrows']?>">
							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>
							<a href="geo_manage_giftcode.php?pageNo=<?=$noOfPage?>&type=<?=$_REQUEST['type']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>">
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

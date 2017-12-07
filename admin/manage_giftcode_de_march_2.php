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
	?>
<script language="JavaScript" type="text/javascript">
	function CheckAll(chk)
	{
	for (i = 0; i < chk.length; i++)
	chk[i].checked = true ;
	}
	function UnCheckAll(chk)
	{
	for (i = 0; i < chk.length; i++)
	chk[i].checked = false ;
	}
</script>
	<?php
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
	
	if ($_REQUEST['delete']=="true" && trim($_REQUEST['code'])!="") {
		$obj->_deletegift(base64_decode($_REQUEST['code']));
	}
	
	$objGen   	 =	new General();
	if($_POST['update']=='Generate')
	{//On clicking generate
		if ($_POST['reusable']=="FX"){//if reusable
			$split_usage	=	explode("/",$_POST['expiry_usage']);
			$expiry_usage	=	$split_usage[2]."-".$split_usage[0]."-".$split_usage[1];
			$no_usage = $_POST['no_usage'];
		}
		else{//if not reusable
			$expiry_usage = 0;
			$no_usage = 0;
		}
	   //echo "1";
	   $no=trim($_POST['number']);
	   $reusable=$_POST['reusable'];
	   $periodhide = $_POST['periodhide'];
	   //$month=$_POST['month'];
	   $membqry="SELECT `membership_fee`, `reusable_membership_fee` FROM `settings`";
       $membreslt = $GLOBALS['db']->getAll($membqry, DB_FETCHMODE_ASSOC);
       if ($reusable=="FX"){//if reusable is selected
		//$fee= $membreslt[0]['reusable_membership_fee'];
		$fee= $membreslt[0]['membership_fee'];
	   }
	   else {
		$fee= $membreslt[0]['membership_fee'];
	   }
	   if($periodhide == '1')
	   {
	   		$period = $_POST['month']." months";
			$amt=$_POST['month']*$fee;
	   }
	   else
	   {
	   		$period = $_POST['week']." weeks";
			$amt=$_POST['week']*($fee/4);
	   }
	   //$period=$month." months";
	   //$amt=$month*$fee;
	   /*switch($month)
	   {
	     case 1: {$period="4 months";$amt=4*$fee;}break;
		 case 2: {$period="6 months";$amt=6*$fee; }break;
		  case 3: {$period="12 months";$amt=12*$fee; }break;
	   }*/
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
			$result1=$obj->_insertgiftadmin($period,$amt,$reusable.$gcode[$i],$expiry_usage,$no_usage);
			if($result1== false)
			{
			  $message="";
			}
			
	   }
	   header('Location:manage_giftcode_di.php');
	   exit();
	}
	
	
	if($_REQUEST['keywordab'])
	{
			$keywordab	=	$_REQUEST['keywordab'];		
	}
	else if($_POST['keywordab'])
	{
	 $keywordab	=	$_POST['keywordab'];
	}
	else
	{
	$keywordab='0';
	}
	
	if($_REQUEST['keyab'])
	{
			$keyab	=	$_REQUEST['keyab'];	
	}
	else if(isset($_POST['sr_periodhide']))
	{		
		if($_POST['sr_periodhide'] == '1')
	 		$keyab	=	$_POST['keyab_month'];
		else
			$keyab	=	$_POST['keyab_week'];	
	}
	else
	{
	$keyab='0';
	}	
	//echo $objGen->_output($keyab);
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
	elseif(isset($_POST['sr2_periodhide']))
	{
	 //$keyb	=	$_POST['keyb'];
	 if($_POST['sr2_periodhide'] == '1')
	 		$keyb	=	$_POST['keyb_month'];
		else
			$keyb	=	$_POST['keyb_week'];	
	}
	else
	{
	$keyb='0';
	}
	
	//check whether the search keyword is existing
	if(trim($_REQUEST['searchkey'])){
			$cleanData	=	str_replace("'","\\\\\\\\\\\'",trim($_REQUEST['searchkey']));
		$cleanData	=	str_replace("%"," ",trim($cleanData));
		if(preg_match('/["%","$","#","^","!"]/',trim($_REQUEST['searchkey']))){
		$errMsg2nd = "Special characters are not allowed";
		}else{ 
			$searchQuery	=	"(t1.code like '%".$cleanData."%' or t4.user_email like '%".$cleanData."%' or t3.email like '%".$cleanData."%')";}		
	}
	
	//////////////////////////////////
	
	if($_POST['unused_id'] && $_POST['make']=="Set the code as distributed")
	{
	   $unusedid=$_POST['unused_id'];
	   for($i=0;$i<count($unusedid);$i++)
	   {
	      $res=$obj->_makepurchased($unusedid[$i]);
	   }
	}
	//////////////////////////////////
	if($_POST['unused_id'] && $_POST['export']=="Export the code as document")
	{
	   $unusedid=$_POST['unused_id'];
	   if(count($unusedid)>0)
	   {$fp = fopen("../giftcodelists.txt", "w") or die("Couldn't create new file"); 
		   for($i=0;$i<count($unusedid);$i++)
		   {
		   fwrite($fp, "\r\n".$unusedid[$i]);
		   }
		  echo "<script type='text/javascript'>window.open('text_gift.php');</script>";		  
		 fclose($fp);  
	   }
	}
	if($_REQUEST['from_date'] && $_REQUEST['to_date'])
	{
	    $fromd=strtotime($_REQUEST['from_date']);
		$tod=strtotime($_REQUEST['to_date']);
		$diff=($tod-$fromd)/(60*60*24);
		if($diff==0)
		{
			$searchdate=" (t1.gen_date='".$_REQUEST['from_date']."')";
		}
		elseif($diff>0)
		{
		    $searchdate=" (t1.gen_date BETWEEN '".$_REQUEST['from_date']."' AND '".$_REQUEST['to_date']."')";
		}
		else
		{
		    $errordate="Please enter a valid date range! ";
		}
		/*SELECT *
FROM `gift_code`
WHERE gen_date
BETWEEN '2005-01-0 '
AND '2011-12-31'*/
	}
	##############################################################################################################
	/*                 Following Code is for doing paging  of Gift code payment list                                              */
	##############################################################################################################
	 $totalRecs = $obj->_getTotalCount($keywordab,$keyab);
	if($totalRecs <= 0)
		$errMsg = "No Records";

	if(!$_REQUEST['maxrows'])
		$_REQUEST['maxrows'] = $_POST['maxrows'];
	if($_REQUEST['pageNo']){
		if($_REQUEST['pageNo']*$_REQUEST['maxrows'] >= $totalRecs+$_REQUEST['maxrows']){
			$_REQUEST['pageNo'] = 1;
		}
		$result =  $obj->_showPage($keywordab,$keyab,$totalRecs,$_REQUEST['pageNo'],$_REQUEST['maxrows']);
	}
	else{ 
	/***********************Selects Records at initial stage***********************************************/
		$_REQUEST['pageNo'] = 1;
		$result = $obj->_showPage($keywordab,$keyab,$totalRecs,$_REQUEST['pageNo'],$_REQUEST['maxrows']);
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
	 $totalRecsb = $obj->_getTotalCountCodeList($keywordb,$keyb,$searchQuery,$searchdate);
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
		$result2 =  $obj->_showPageCodeList($keywordb,$keyb,$totalRecsb,$_REQUEST['pageNob'],$_REQUEST['maxrowsb'],$searchQuery,$searchdate);
	}
	else{ 
	/***********************Selects Records at initial stage***********************************************/
		$_REQUEST['pageNob'] = 1;
		$result2 = $obj->_showPageCodeList($keywordb,$keyb,$totalRecsb,$_REQUEST['pageNob'],$_REQUEST['maxrowsb'],$searchQuery,$searchdate);
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
function confirm_delete(cod){
	if(confirm('Are you sure to delete?')) {
		window.location.href = "manage_giftcode_di.php?delete=true&code="+cod;
	}else return false;
}
function checkselected()
{
      var objform=document.getElementsByName('unused_id[]');
	  var test=0;
	  for(var i=0;i<objform.length;i++)
	  {
	  		if(objform[i].checked==true)
			{
			  test=1;
			}
	  }
	  if(test==0)
	  {
	     alert("Please select an unused code");
		 return false;
	  }
	  //document.frmgcode.submit();
	  return true;
}
function checkdate()
{
      var objform=document.getElementById('from_date').value;
	  var objform1=document.getElementById('to_date').value;
	  var test=0;
	  if(objform=='' || objform1=='')
	  {
	        alert("Please select dates correctly!");
		    return false;
	  }
	  return true;
	 /* if(test==0)
	  {
	     alert("Please select an unused code");
		 return false;
	  }
	  document.frmgcode.submit();
	  return true;*/
}
function validate(frmcode)
{
  show_hide_reusable();
  show_hide_period();
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
	 if((document.frmcode.periodhide[0].checked) && (month.selectedIndex ==0))
	  {
		 alert("Please Select a month for period!");
		 document.frmcode.month.focus();
		 return false;
	  }
	   if((document.frmcode.periodhide[1].checked) && (week.selectedIndex ==0))
	  {
		 alert("Please Select a week for period!");
		 document.frmcode.week.focus();
		 return false;
	  }
	 //return true; 
  }
  if (document.frmcode.reusable[0].checked){//if reusable selected
	if (document.frmcode.expiry_usage.value == ''){//if expiry date is null
		alert("Please Enter Expiry Date");
		document.frmcode.expiry_usage.focus();
		return false;
	}
	if (document.frmcode.no_usage.value == '' || document.frmcode.no_usage.value == 0 || isNaN( parseInt( document.frmcode.no_usage.value ))){//if no. of usages is null or 0 or not a no.
		alert("Please Enter a valid number!");
		document.frmcode.no_usage.focus();
		return false;
	}
	document.frmcode.no_usage.value = parseInt( document.frmcode.no_usage.value );
  }
	return true;
}
function show_hide_reusable(){//to show/hide the reusable related fields
	if (document.frmcode.reusable[0].checked){//if reusable is yes
		document.getElementById('id_reusable').style.display = 'block';
		//document.getElementById('id_no_usage').style.display = 'block';
	}
	else {
		document.getElementById('id_reusable').style.display = 'none';
		//document.getElementById('id_no_usage').style.display = 'none';
	}
}
function show_hide_period(){//to show/hide the reusable related fields
	if (document.frmcode.periodhide[0].checked){//if reusable is yes
		document.getElementById('month').style.display = 'inherit';
		document.getElementById('week').style.display = 'none';
		//document.getElementById('id_no_usage').style.display = 'block';
	}
	else {
		document.getElementById('month').style.display = 'none';
		document.getElementById('week').style.display = 'inherit';
	}
}
function show_hide_period_sr(){//to show/hide the reusable related fields
	if (document.frmfaqs.sr_periodhide[0].checked){//if reusable is yes
		document.getElementById('keyab_month').style.display = 'inherit';
		document.getElementById('keyab_week').style.display = 'none';
		//document.getElementById('id_no_usage').style.display = 'block';
	}
	else {
		document.getElementById('keyab_month').style.display = 'none';
		document.getElementById('keyab_week').style.display = 'inherit';
	}
}
function show_hide_period_sr2(){//to show/hide the reusable related fields
	if (document.frmgcode.sr2_periodhide[0].checked){//if reusable is yes
		document.getElementById('keyb_month').style.display = 'inherit';
		document.getElementById('keyb_week').style.display = 'none';
		//document.getElementById('id_no_usage').style.display = 'block';
	}
	else {
		document.getElementById('keyb_month').style.display = 'none';
		document.getElementById('keyb_week').style.display = 'inherit';
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
						if($message){?>
					<tr>
						<td align="center"><?php echo $message; ?></td>
					</tr>
					<?php } ?>
					<TR height="20"><TD align="left">&nbsp;</TD></TR>
					
				  </table>
				  
                 <form name="frmcode" action="manage_giftcode_di.php" method="post" onSubmit="return validate(this.form)">         
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
				     <td width="30%" height="30" >&nbsp;Reusable </td>
                                      <td width="70%"  >:
                                        <input type="radio" name="reusable" value="FX" onClick="show_hide_reusable();">&nbsp;Yes
                                        <input type="radio" name="reusable" value="" checked="checked" onClick="show_hide_reusable();">&nbsp;No
                     </td>
                   </tr>
				   <tr><td colspan="2">
				   <table cellSpacing=1 cellPadding=0 width="100%" bgcolor="" id="id_reusable" style="display:none;">
					<tbody width="100%">
					   <tr>
						 <td width="300" height="30" >&nbsp;Expiry Date </td>
						 <td width="70%"  >:
								<input type="text" name="expiry_usage" readonly />
								<script language="JavaScript">
								new tcal ({
									// form name
									'formname': 'frmcode',
									// input name
									'controlname': 'expiry_usage'
								});

								</script>
						 </td>
					   </tr>
					   
					   <tr>
						 <td width="30%" height="30" >&nbsp;No. of Usage </td>
						 <td width="70%"  >:
								<input type="text" name="no_usage" />
						 </td>
					   </tr>
					</tbody>
                   </table>
                   </td></tr>
					<tr >
					  <td  >&nbsp;Period  </td>
					  <td >:<input type="radio" name="periodhide" value="1" checked="checked" onClick="show_hide_period();">&nbsp;Month
                               <input type="radio" name="periodhide" value="2" onClick="show_hide_period();">&nbsp;Week&nbsp;&nbsp;&nbsp;
							   <select name="month" id="month" class="paragraph">
                            		<option value="0">..select..</option>
									<?php for($i=1;$i<=12;$i++)
							   		{?><option value="<?php echo $i;?>"><?=$i?></option><?php }?>
                          		</select>
								<select name="week" id="week" class="paragraph" style="display:none;">
                            		<option value="0">..select..</option>
									<?php for($i=1;$i<=20;$i++)
							   		{?><option value="<?php echo $i;?>"><?=$i?></option><?php }?>
                          		</select>					                    
						  </td>
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
                       
			   <form name="frmfaqs" action="manage_giftcode_di.php" method="post">
                        
                     <table  cellspacing=0 cellpadding=0 width="553" border=0 class="topColor">
                      <tbody>
					  <TR><th height="32" colspan="3" align="left">Gift Code Payment List</th>
					  <TR>
	    			    	<tr> 
					    <td colspan="2" valign=top class="paragraph2">Type:
					     <select class="paragraph2" onChange="this.form.submit()" name="keywordab">
					   <option <?php if($keywordab=='0') {?> selected="selected"<?php }?>value="0">All Type</option>
					   <option <?php if($keywordab=='purchased') {?> selected="selected"<?php }?>>purchased</option>
					   <option <?php if($keywordab=='used') {?> selected="selected"<?php }?>>used</option>
					   </select>&nbsp;&nbsp;
					   Period:
					   <input type="radio" name="sr_periodhide" value="1" checked="checked" onClick="show_hide_period_sr();">&nbsp;Month
                        <input type="radio" name="sr_periodhide" value="2" onClick="show_hide_period_sr();">&nbsp;Week&nbsp;&nbsp;&nbsp;
					   
					     <select class="paragraph2" onChange="this.form.submit()" name="keyab_month" id="keyab_month">
					   <option <?php if($keyab=='0') {?> selected="selected"<?php }?>value="0">All Type</option>
					   <?php for($i=1;$i<=12;$i++)
							   {?><option value="<?php echo $i." months";?>" <?php $j = $i." months"; if($keyab==$j) {?> selected="selected"<?php }?>><?=$i." months"?></option>
							 <?php }?>
					   </select>
					   
					   <select class="paragraph2" onChange="this.form.submit()" name="keyab_week" id="keyab_week" style="display:none;">
					   <option <?php if($keyab=='0') {?> selected="selected"<?php }?>value="0">All Type</option>
					   <?php for($i=1;$i<=20;$i++)
							   {?><option value="<?php echo $i." weaks";?>" <?php $j = $i." weeks"; if($keyab==$i) {?> selected="selected"<?php }?>><?=$i." weeks"?></option>
							 <?php }?>
					   </select>
					   </td>
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
						<TD >Gift Code</a>						</TD>
						<TD >Code Type</a>						</TD>
						<TD >Customer email</a>						</TD>
						
						<TD align="center" >Payment date </TD>
						<TD align="center" >Status </TD>
						<TD align="center" >Amount</TD>
						<td>Expiry date/No. of Uses</td>
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
							<TD align="center"><?=$row['codeamount'];?>&nbsp;<?php if($row['purchase_currency']!=''){echo $row['purchase_currency']; }else{echo "Euro";}?></TD> 
							<td><?php
									if (substr($row['code'],0,2)=="FX"){
										echo $row['expiry_usage']."/".$row['no_usage'];
									}
									?>
							</td>
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
						<a href="manage_giftcode_di.php?pageNo=1&type=<?=$_REQUEST['type']?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keywordab=<?=$objGen->_output($_REQUEST['keywordab'])?>&keyab=<?=$objGen->_output($keyab)?>">
						<img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>
						<a href="manage_giftcode_di.php?pageNo=<?=$prev?>&type=<?=$_REQUEST['type']?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keywordab=<?=$objGen->_output($_REQUEST['keywordab'])?>&keyab=<?=$objGen->_output($keyab)?>">
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
							 <a href="manage_giftcode_di.php?pageNo=<?=$next?>&type=<?=$_REQUEST['type']?>&langId=<?=$lanId?>&field=<?=$_REQUEST['field']?>&maxrows=<?=$_REQUEST['maxrows']?>&keywordab=<?=$objGen->_output($_REQUEST['keywordab'])?>&keyab=<?=$objGen->_output($keyab)?>">
							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>
							<a href="manage_giftcode_di.php?pageNo=<?=$noOfPage?>&type=<?=$_REQUEST['type']?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keywordab=<?=$objGen->_output($_REQUEST['keywordab'])?>&keyab=<?=$objGen->_output($$keyab)?>">
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
                       
			   <form name="frmgcode" action="manage_giftcode_di.php" method="post">
                        
                     <table  cellspacing=0 cellpadding=0 width="691" border=0 class="topColor">
                      <tbody>
					  <?php if($errMsg2nd != ""){?>
						  <TR class="listingTable"> 
							<TD align="center" colspan="3" ><font color="#FF0000"><?=$errMsg2nd?></font> 
							</TD>
						  </TR>
					 <?php }?>
					  <?php if($errordate != ""){?>
						  <TR class="listingTable"> 
							<TD align="center" colspan="3" ><font color="#FF0000"><?=$errordate?></font> 
							</TD>
						  </TR>
					 <?php }?>
					  <TR><th width="161" height="43"  align="left"> Code List</th>
					   <td colspan="2" align="center"  height="43"><input type="submit" name="export" onClick="return checkselected();" value="Export the code as document">&nbsp;<input type="submit" name="make" onClick="return checkselected();" value="Set the code as distributed"></td>
					</TR>
	    			    	<tr> 
					   <td height="32" colspan="1" valign=top class="paragraph2">Type:
					     <select class="paragraph2" onChange="this.form.submit()" name="keywordb">
					   <option <?php if($keywordb=='0') {?> selected="selected"<?php }?>value="0">All Type</option>
					   <option <?php if($keywordb=='unused') {?> selected="selected"<?php }?>>unused</option>
					   <option <?php if($keywordb=='purchased') {?> selected="selected"<?php }?>>purchased</option>
					   <option <?php if($keywordb=='used') {?> selected="selected"<?php }?>>used</option>
					   </select></td>
					   <td colspan="2" valign=top class="paragraph2">&nbsp;&nbsp;
					   Period:<input type="radio" name="sr2_periodhide" value="1" checked="checked" onClick="show_hide_period_sr2();">&nbsp;Month
                        <input type="radio" name="sr2_periodhide" value="2" onClick="show_hide_period_sr2();">&nbsp;Week&nbsp;&nbsp;&nbsp;
					     <select class="paragraph2" onChange="this.form.submit()" name="keyb_month" id="keyb_month">
					    <option <?php if($keyb=='0') {?> selected="selected"<?php }?>value="0">All Type</option>
					   <?php for($i=1;$i<=12;$i++)
							   {?><option value="<?php echo $i." months";?>" <?php  $j = $i." months"; if($keyb==$j) {?> selected="selected"<?php }?>><?=$i." months"?></option>
							 <?php }?>
					   </select>
					   <select class="paragraph2" onChange="this.form.submit()" name="keyb_week" id="keyb_week" style="display:none;">
					    <option <?php if($keyb=='0') {?> selected="selected"<?php }?>value="0">All Type</option>
					   <?php for($i=1;$i<=20;$i++)
							   {?><option value="<?php echo $i." weeks";?>" <?php  $j = $i." weeks"; if($keyb==$j) {?> selected="selected"<?php }?>><?=$i." weeks"?></option>
							 <?php }?>
					   </select>
					  &nbsp;&nbsp;&nbsp;
							  <input name="searchkey" type="text" size="20" value="<?=$objGen->_output(stripslashes($_REQUEST['searchkey']));?>">&nbsp;<input type="button" name="search" onClick="javascript:this.form.submit();" value="Search"></td>
						
					   </tr>
					   <tr>
					   <td height="29" colspan="1" align="right" class="paragraph2"><input type="text" name="from_date" id="from_date" readonly="readonly" value="<?=$_REQUEST['from_date']?>" size="8"> <input type="button" id="from_date_btn" value="Select">&nbsp;-&nbsp;</td><td colspan="2" class="paragraph2"><input type="text" name="to_date" id="to_date" readonly="readonly" value="<?=$_REQUEST['to_date']?>" size="8"> <input type="button" id="to_date_btn" value="Select">&nbsp;<input type="submit" name="Go" onClick="return checkdate()" value="Go">&nbsp;&nbsp;
					   <span style="padding-left:50px">View per page: 
			
						<select class="paragraph"  size=1 onChange="this.form.submit()" name="maxrowsb">
						<? foreach($siteRowListConfig as $key=>$data){ ?>
						  <option value="<?=$key?>" <? if($_REQUEST['maxrowsb']==$key) echo"selected"; ?>><?=$data;?></option>
						 <? } ?>
						</select></span>
						<script type="text/javascript">
  Calendar.setup(
    {
      inputField  : "from_date",         // ID of the input field
      ifFormat    : "%Y-%m-%d",    // the date format
      button      : "from_date_btn"       // ID of the button
    }
  );
  Calendar.setup(
    {
      inputField  : "to_date",         // ID of the input field
      ifFormat    : "%Y-%m-%d",    // the date format
      button      : "to_date_btn"       // ID of the button
    }
  );
</script>
					</td>
				    </tr>	
			     
										
                                
                                </tbody>
                              </table>
				  <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="691">
				   <TBODY> 
					   <TR class="tableHeaderColor">
						<TD align="center" ><input type="button" onClick="javascript:CheckAll(document.frmgcode.unused)" value="Select All">&nbsp;/&nbsp;
						  <input name="button" type="button" onClick="javascript:UnCheckAll(document.frmgcode.unused)" value="Un Select All"></TD>
						<TD >Gift Code</TD>
						<TD >Code Type</TD>
						<TD >Bought</TD>
						<TD align="center" >Used</TD>
						<td>Generated Date</td>
						<td>Action</td>
						<td>Expiry date/No. of Uses</td>
					  </TR>
					  
					  <?php if($errMsg2 != ""){?>
						  <TR class="listingTable"> 
							<TD align="center" colspan="7" ><font color="#FF0000"><?=$errMsg2?></font>							</TD>
						  </TR>
					 <?php }
					   	$count = $startNob;
						foreach($result2 as $row){
						?>
						    <tr class="listingTable">
						    	<TD align="center"><?php if ($row['codestatus']=='unused'){?><input type="checkbox" id="unused" name="unused_id[]" value="<?php echo $row['code'];?>"><?php } ?><?=$count?></TD>
								<TD><?=$row['code'];?></TD>
								<TD><?=$row['codetype'];?></TD>
								<TD><?=$row['email'];?><?php if (trim($row['purchasedate'])!="" && trim($row['purchasedate'])!="0000-00-00")  echo " on ".$row['purchasedate'];?></TD>
								<TD align="center"><?=$row['user_email'];?><?php if (trim($row['usedate'])!="" && trim($row['usedate'])!="0000-00-00") echo " on ".$row['usedate'];?></TD>
								<td><?php if($row['gen_date']!='0000-00-00'){?><?=$row['gen_date'];?><?php }?></td>
								<td><?php if ($row['codestatus']=='unused') { ?><a href="javascript:void(0);" onClick="javascript:confirm_delete('<?=base64_encode($row['id'])?>');">Delete</a><?php } ?></td>
								<td><?php
									if (substr($row['code'],0,2)=="FX"){
										echo $row['expiry_usage']."/".$row['no_usage'];
									}
									?>
								</td>
						    </tr>
						<?php
						$count++;
						}
						?>
							<tr><td colspan="7" align="right"> <?php echo $totalamount;?></td></tr>
					</tbody>
			 	</table>
				<table cellspacing=0 cellpadding=0 width=691 border=0 class="topColor">
                                <tbody>			
					<tr>
						<td  colspan = "3" align="left" class="leftmenu">
						<a href="manage_giftcode_di.php?pageNob=1&typeb=<?=$_REQUEST['typeb']?>&langIdb=<?=$lanIdb?>&maxrowsb=<?=$_REQUEST['maxrowsb']?>&fieldb=<?=$_REQUEST['fieldb']?>&keywordb=<?=$_REQUEST['keywordb']?>&keyb=<?=$keyb?>&searchkey=<?=$_REQUEST['searchkey']?>&from_date=<?=$_REQUEST['from_date']?>&to_date=<?=$_REQUEST['to_date']?>">
						<img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>
						<a href="manage_giftcode_di.php?pageNob=<?=$prevb?>&typeb=<?=$_REQUEST['typeb']?>&langIdb=<?=$lanIdb?>&maxrowsb=<?=$_REQUEST['maxrowsb']?>&fieldb=<?=$_REQUEST['fieldb']?>&keywordb=<?=$_REQUEST['keywordb']?>&keyb=<?=$keyb?>&searchkey=<?=$_REQUEST['searchkey']?>&from_date=<?=$_REQUEST['from_date']?>&to_date=<?=$_REQUEST['to_date']?>">
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
							 <a href="manage_giftcode_di.php?pageNob=<?=$nextb?>&typeb=<?=$_REQUEST['typeb']?>&langIdb=<?=$lanIdb?>&fieldb=<?=$_REQUEST['fieldb']?>&maxrowsb=<?=$_REQUEST['maxrowsb']?>&keywordb=<?=$_REQUEST['keywordb']?>&keyb=<?=$keyb?>&searchkey=<?=$_REQUEST['searchkey']?>&from_date=<?=$_REQUEST['from_date']?>&to_date=<?=$_REQUEST['to_date']?>">
							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>
							<a href="manage_giftcode_di.php?pageNob=<?=$noOfPageb?>&typeb=<?=$_REQUEST['typeb']?>&langIdb=<?=$lanIdb?>&maxrowsb=<?=$_REQUEST['maxrowsb']?>&fieldb=<?=$_REQUEST['fieldb']?>&keywordb=<?=$_REQUEST['keywordb']?>&keyb=<?=$keyb?>&searchkey=<?=$_REQUEST['searchkey']?>&from_date=<?=$_REQUEST['from_date']?>&to_date=<?=$_REQUEST['to_date']?>">
							<img src="images/mail-arrowlastg.gif" border=0 width="20" height="20" align="absmiddle" alt="Last Page"></a>
						</td>
						<td colspan="3" align="right">&nbsp;</td>
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

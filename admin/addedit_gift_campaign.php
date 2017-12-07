<?php
	include_once('includeconfig.php');
	include("../includes/classes/class.GiftCodeCampaign.php");
	
	$errorMsg	=	array();
	//setiing the default languge as english other vice the languge will be the selected one fromm the dropdrown 
	if($_REQUEST['langId']!="")
	{
	  	$lanId=$_REQUEST['langId'];
	}
	else
	{
	  	$lanId=1; 
	}
	
	/*
	 Instantiating the classes.
	*/
	
	$objCamp = new GiftCodeCampaign($lanId);
	$objGen   =	new General();
	
	$heading = "Gift Code Campaign";
	/*
	Take all the languages to an array.
	*/
	$languageArray=$siteLanguagesConfig;
	reset($languageArray);
	
	$errorMsg	=	array();
	
	//$camp_id			=	$objCamp->_createcode();
		
		if(isset($_POST['add'])||isset($_POST['update']))
		{
			
			if(trim($_POST['camp_name'])=='')
				$errorMsg[]	=	"Campaign name required";
				elseif(is_numeric(trim($_REQUEST['camp_name'])))
				$errorMsg[]	=	"Campaign name does not valid";
			
			if(trim($_POST['valid_months'])==0)
				$errorMsg[]	=	"Plan period required";
				elseif(!is_numeric(trim($_REQUEST['valid_months'])))
				$errorMsg[]	=	"Plan period should be numeric";
			
			if(trim($_POST['camp_price'])=='')
				$errorMsg[]	=	"Price Amount required";
				elseif(!is_numeric(trim($_REQUEST['camp_price'])))
				$errorMsg[]	=	"Price Amount should be numeric";
				
				if(trim($_POST['amount_month'])=='')
				$errorMsg[]	=	"Price Amount per month required";
				elseif(!is_numeric(trim($_REQUEST['amount_month'])))
				$errorMsg[]	=	"Price Amount per month should be numeric";
				
				if(trim($_POST['camp_discount'])=='')
				$errorMsg[]	=	"Discount % required";
				elseif(!eregi("^[a-zA-Z0-9]+$", trim($_POST['camp_discount'])))
				$errorMsg[]	=	"Discount % does not valid";
				
			if(trim($_POST['created_date'])=='')
				$errorMsg[]	=	"Start date required";
				
			
				
				if($_POST['add'])
				{
					//check admin already exists or not
						
					if(count($errorMsg)==0)	
					{
							$camp_discount				=	$_POST['camp_discount'];
							$camp_name					= 	$_POST['camp_name'];
							$amount_month				=	$_POST['amount_month'];
							$valid_months				= 	$_POST['valid_months'];
							$value						= 	$_POST['camp_price'];							
							$created_date				= 	$_POST['created_date'];
							$split_start				=	explode("/",$created_date);
							$created_date				=	$split_start[2]."-".$split_start[0]."-".$split_start[1];
							$status						= 	$_POST['status'];
							$elmts						= 	array("camp_discount" => $camp_discount,"camp_name" => $camp_name,"amount_month" => $amount_month,"valid_months" => $valid_months,"camp_price" => $value,"created_date" => $created_date,"status" => $status);
							
							$result =  $objCamp->_insertGiftPayCamp($elmts,count($languageArray));
						
						header("Location:manage_giftcode_campaign.php?status=success_add&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']);
					}
				}
					
					//On clicking update button
	
				if($_POST['update'])
				{
					
					if(count($errorMsg)==0)	
					{
			 		$query = "SELECT  count(*) as dataCount FROM gift_pay_campaign WHERE id =".$_REQUEST['id'];
					$res = mysql_query($query);
					
				$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);
					$coundRecs = $result[0]->dataCount;
			//		$coundRecs = mysql_num_rows($res);
					
					if(DB::isError($result)) 
						echo $result->getDebugInfo();
						if($coundRecs >= 1)
						{
							
							$camp_discount				=	$_POST['camp_discount'];
							$camp_name					= 	$_POST['camp_name'];
							$amount_month					=   $_POST['amount_month'];
							$valid_months				= 	$_POST['valid_months'];
							$camp_price					= 	$_POST['camp_price'];
							$created_date				= 	$_POST['created_date'];
							$split_start				=	explode("/",$created_date);
							$created_date				=	$split_start[2]."-".$split_start[0]."-".$split_start[1];
							$status						= 	$_POST['status'];
							$elmts						= 	array("camp_discount" => $camp_discount,"camp_name" => $camp_name,"amount_month" => $amount_month,"valid_months" => $valid_months,"camp_price" => $camp_price,"created_date" => $created_date,"status" => $status);
							
							$result 					= $objCamp->_updateCamp($_REQUEST['id'],$elmts);
						}
						else
						{
							
							$camp_discount				=	$_POST['camp_discount'];
						    $camp_name					= 	$_POST['camp_name'];
							$amount_month				=   $_POST['amount_month'];
							$valid_months				= 	$_POST['valid_months'];
							$camp_price					= 	$_POST['camp_price'];
							$created_date				= 	$_POST['created_date'];
							$split_start				=	explode("/",$created_date);
							$created_date				=	$split_start[2]."-".$split_start[0]."-".$split_start[1];	
							$status						=	$_POST['status'];
							$result 					= 	$objCamp->_insertOneCamp($_REQUEST['id'],$camp_name,$amount_month,$camp_discount,$valid_months,$camp_price,$created_date,$status);
						}
				
			
			header("Location:manage_giftcode_campaign.php?status=success_update&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']);
			
				}
		}
	}
	//if edit following will execute on loading
	if($_REQUEST['id'] and count($errorMsg)==0){
		//Some security check here
		$result = $objCamp->_getAllById($_REQUEST['id']);//print_r($result);exit;
		}
		$payPlansql		=	"SELECT DISTINCT (`plan_duration`) FROM  `jiwok_payment_plan`";
		$payPlansRes	= 	$GLOBALS['db']->getAll($payPlansql, DB_FETCHMODE_ASSOC);
		
		
		
	
?>
<HTML><HEAD><TITLE><?=$admin_title?></TITLE>
<? include_once('metadata.php');?>
<script language="javascript" src="js/mask.js"></script>
<SCRIPT language="JavaScript1.2" src="../includes/js/tooltip.js" type="text/javascript"></SCRIPT>
<script language="JavaScript" src="../calendar/tigra/calendar_us.js"></script>
<link rel="stylesheet" href="../calendar/tigra/calendar.css">
<link href="../resources/style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
#tooltip {

    padding: 3px;

    background:#FFFFFF;

    border: 1px solid #333333;

    text-align: center;

    font-size: 10px;

	color:#CC0000;

	font-family: Verdana, Arial, Helvetica, sans-serif;

	text-align: center;

}



span.tip {

    border-bottom: 1px solid #FFCC00;

	text-align:left;

}
</style>
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
                       
						  
				<form name="campform" action="addedit_gift_campaign.php" method="post" onSubmit="return formChecking()">
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
				
									<TR> 
									<TD align="left">
										
										<table height="50" class="topActions"><tr><td valign="middle" width="50"><a href="manage_giftcode_campaign.php?pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=$_REQUEST['keyword']?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;List   </a></td>
										<td valign="middle" class="noneAnchor"><img src="images/add.gif" border="0" alt="Add Record">&nbsp;Add </td>
										</tr></table>
									</TD><td align="right"><?php echo REQUIRED_MESSAGE;?></td>
									</TR>
									
								  </table>
                              
				  <TABLE class="paragraph2" cellSpacing=0 cellPadding=2 width="100%" align="center">
				   <TBODY> 
					 <tr>
					  <td colspan="2" align="right">&nbsp;
				     <?
							if(count($result) != 0){
								//$camp_discount[0]['hex'] = stripslashes(stripslashes(stripslashes($result[0]['camp_discount'])));
								$_POST['camp_discount'] = stripslashes(stripslashes(stripslashes($result[0]['camp_discount'])));
								$_POST['camp_name'] 	= stripslashes(stripslashes(stripslashes($result[0]['camp_name'])));
								$_POST['amount_month'] 	= stripslashes(stripslashes(stripslashes($result[0]['amount_month'])));
								$_POST['camp_price'] 	= stripslashes(stripslashes(stripslashes($result[0]['camp_price'])));
								$_POST['valid_months']	= stripslashes(stripslashes(stripslashes($result[0]['valid_months'])));
								$date_db			 	= stripslashes(stripslashes(stripslashes($result[0]['created_date'])));
								$split_start1			=	explode("-",$date_db);
							    $_POST['created_date']	=	$split_start1[1]."/".$split_start1[2]."/".$split_start1[0];								
								$_POST['status']  		=  $result[0]['status'];
							}
					?> 
					<fieldset ><legend><?php echo $val; ?></legend>
				   <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
						<tr>
						<td width="35%" align="right">
						  Campaign Name:&nbsp;						</td>
						<td width="79%">
						<input type="text" name="camp_name" size="25" maxlength="100"  value="<?=$_POST['camp_name']?>">           
						</td></tr>
                        <tr>
					  <td colspan="2" align="center">&nbsp;</td>
					  </tr>
                        <tr>
					  <td width="35%" align="right">
						  Plan Period:&nbsp;						</td>
						<td width="79%">
				     <select name="valid_months" id="valid_months" class="paragraph" style="width:157px">
                            		<option value="0">..select..</option>                                    
									<?php foreach($payPlansRes as $val)		
							   		{ ?><option value="<?=$val['plan_duration'];?>" <?php if($val['plan_duration'] ==$_POST['valid_months'] )  echo "selected";?> ><?=$val['plan_duration'];?></option><?php }?>
                          		</select> &nbsp;<span class="tooltip" onMouseOver="tooltip('Duration of free period in months');" onMouseOut="exit();">[?]</span> (In Months)
						</td>
						</tr>
                        <tr>
					  <td colspan="2" align="center">&nbsp;</td>
					  </tr>
                        <tr>
					  <td width="35%" align="right">
						  Amount:&nbsp;						</td>
						<td width="79%">
						<input type="text" name="camp_price" size="25" maxlength="100"  value="<?=$_POST['camp_price']?>">&nbsp;<span class="tooltip" onMouseOver="tooltip('Value of the card');" onMouseOut="exit();">[?]</span> 
                        
						</td>
						</tr>
                        <tr>
					  <td colspan="2" align="center">&nbsp;</td>
					  </tr>
                        <tr>
						<td width="35%" align="right">
						  Amount / month:&nbsp;						</td>
						<td width="79%">
						<input type="text" name="amount_month" size="25" maxlength="100"  value="<?=$_POST['amount_month']?>">           
						</td></tr>
							<tr>
					  <td colspan="2" align="center">&nbsp;</td>
					  </tr>
					  <tr>
					  <td width="35%" align="right">
						  Discount %:&nbsp;						</td>
						<td width="79%">
						<input type="text" name="camp_discount" size="25" maxlength="100"  value="<?=$_POST['camp_discount']?>"> &nbsp;<span class="tooltip" onMouseOver="tooltip('Campaign Code');" onMouseOut="exit();">[?]</span>             
						</td>
						</tr>
						
						 
						
						 
						<tr>
					  <td colspan="2" align="center">&nbsp;</td>
					  </tr>
					  <tr>
					  <td width="35%" align="right">
						  Campaign starting date:&nbsp;						</td>
						<td width="79%">
						<input type="text" name="created_date" size="25" maxlength="100"  value="<?=$_POST['created_date']?>" readonly>&nbsp;
						<script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'campform',
		// input name
		'controlname': 'created_date'
	});

	</script>&nbsp;<span class="tooltip" onMouseOver="tooltip('Starting date of the campaign');" onMouseOut="exit();">[?]</span>     
						</td>
						</tr>
						<tr>
					  <td colspan="2" align="center">&nbsp;</td>
					  </tr>
					  
					  </table>
					  </fieldset>
					
					</td></tr>
					<tr height="30px">
						<td width="30%" align="right"> Status:&nbsp;</td>
						<td>
						<input type="radio" name="status" id="active" value="1" <?php if($_POST['status'] == 1) echo "checked";?>><label for="active">Active</label>
						<input type="radio" name="status" id="inactive" value="0" <?php if($_POST['status'] == 0) echo "checked";?>><label for="inactive">Inactive</label></td>
					</tr>
					<?php 	if(!$_REQUEST['id']){ 	?>
					<tr >
						<td colspan="2" align="center">
							<input type="submit" name="add" value="&nbsp;Add&nbsp;"></td>
					</tr>
					<?php	}else{	?>
					<tr>
						<td colspan="2" align="center">
							<input type="submit" name="update" value="&nbsp;Update&nbsp;"></td>
					</tr>
					<?php	}	?>
				    </tbody>
			 	  </table>
				</TD>
                          </TR>
                          </TBODY>
                        </TABLE>
				<input type="hidden" name="langId" value="<?=$_REQUEST['langId']?>">  
				<input type="hidden" name="id" value="<?=$_REQUEST['id']?>">
			   <input type="hidden" name="pageNo" value="<?=$_REQUEST['pageNo']?>">
			   <input type="hidden" name="maxrows" value="<?=$_REQUEST['maxrows']?>">
			   <input type="hidden" name="type" value="<?=$_REQUEST['type']?>">
				<input type="hidden" name="field" value="<?=$_REQUEST['field']?>">
                <input type="hidden" name="keyword" value="<?=$_REQUEST['keyword']?>">
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
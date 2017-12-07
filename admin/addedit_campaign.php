<?php
	include_once('includeconfig.php');
	include("../includes/classes/class.campaign.php");
	
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
	
	$objCamp = new Campaign($lanId);
	$objGen   =	new General();
	
	$heading = "Campaign";
	/*
	Take all the languages to an array.
	*/
	$languageArray=$siteLanguagesConfig;
	reset($languageArray);
	
	$errorMsg	=	array();
	
	//$camp_id			=	$objCamp->_createcode();
		
		if(isset($_POST['add'])||isset($_POST['update']))
		{
		if(trim($_POST['camp_id'])=='')
				$errorMsg[]	=	"Jiwok code required";
				elseif(!eregi("^[a-zA-Z0-9]+$", trim($_POST['camp_id'])))
				$errorMsg[]	=	"Jiwok code does not valid";

			if(trim($_POST['camp_name'])=='')
				$errorMsg[]	=	"Campaign name required";
				elseif(is_numeric(trim($_REQUEST['camp_name'])))
				$errorMsg[]	=	"Campaign name does not valid";
			
			if(trim($_POST['no_of_months'])=='')
				$errorMsg[]	=	"Free period required";
				elseif(!is_numeric(trim($_REQUEST['no_of_months'])))
				$errorMsg[]	=	"Free period should be numeric";
			
			if(trim($_POST['camp_value'])=='')
				$errorMsg[]	=	"Value required";
				elseif(!is_numeric(trim($_REQUEST['camp_value'])))
				$errorMsg[]	=	"Value should be numeric";
				
			if(trim($_POST['camp_start_date'])=='')
				$errorMsg[]	=	"Start date required";
				
			if(trim($_POST['camp_end_date'])=='')
				$errorMsg[]	=	"End date required";
			
			if(trim($_POST['company_name'])=='')
				$errorMsg[]	=	"Company name required";
				elseif(is_numeric(trim($_REQUEST['company_name'])))
				$errorMsg[]	=	"Company name does not valid";
			
			if(trim($_POST['email']) == "")
				$errorMsg[] = "Email id required";
				else if(!$objGen->_validate_email($_POST['email']))
				$errorMsg[] = "Email id does not valid";
				/*else
				{
					if($_REQUEST['update'])
					{
						if($objCamp->_mailid_exist(trim($_POST['email']),$_REQUEST['id']))
						$errorMsg[] = "Email already exist ";
					}
					else
					{
						if($objCamp->_mailid_exist(trim($_POST['email']),''))
						$errorMsg[] = "Email already exist ";
					}
				}*/
				
		
				
				if($_POST['add'])
				{
					//check admin already exists or not
						
					if(count($errorMsg)==0)	
					{
							$camp_id					=	$_POST['camp_id'];
							$camp_name				= $_POST['camp_name'];
							$no_of_months			= $_POST['no_of_months'];
							$value						= $_POST['camp_value'];
							$company_name			= $_POST['company_name'];
							$email						= $_POST['email'];
							$camp_start_date		= $_POST['camp_start_date'];
							$split_start					=	explode("/",$camp_start_date);
							$camp_start_date		=	$split_start[2]."-".$split_start[0]."-".$split_start[1];
							$camp_end_date		= $_POST['camp_end_date'];
							$split_end					=	explode("/",$camp_end_date);
							$camp_end_date		=	$split_end[2]."-".$split_end[0]."-".$split_end[1];
							$created_date			= date('Y-m-d');
							$status						= $_POST['status'];
							$elmts	= array("camp_id" => $camp_id,"camp_name" => $camp_name,"no_of_months" => $no_of_months,"camp_value" => $value,"start_date" => $camp_start_date,"end_date" => $camp_end_date,"created_date" => $created_date,"company_name" => $company_name,"email" => $email,"mail" => "Not sent","language_id" => 1,"status" => $status);
							
							$result =  $objCamp->_insertCamp($elmts,count($languageArray));
						
						header("Location:manage_campaign.php?status=success_add&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']);
					}
				}
					
					//On clicking update button
	
				if($_POST['update'])
				{
			
					if(count($errorMsg)==0)	
					{
			 		$query = "SELECT  count(*) as dataCount FROM campaign_manage WHERE id =".$_REQUEST['id']." AND language_id=".$lanId."";
				//	$res = mysql_query($query);
					
				$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);
					$coundRecs = $result[0]->dataCount;
			//		$coundRecs = mysql_num_rows($res);
					
					if(DB::isError($result)) 
						echo $result->getDebugInfo();
						if($coundRecs >= 1)
						{
							$camp_id					=	$_POST['camp_id'];
							$camp_name				= $_POST['camp_name'];
							$no_of_months			= $_POST['no_of_months'];
							$value						= $_POST['camp_value'];
							$camp_start_date		= $_POST['camp_start_date'];
							$split_start					=	explode("/",$camp_start_date);
							$camp_start_date		=	$split_start[2]."-".$split_start[0]."-".$split_start[1];
							$camp_end_date		= $_POST['camp_end_date'];
							$split_end					=	explode("/",$camp_end_date);
							$camp_end_date		=	$split_end[2]."-".$split_end[0]."-".$split_end[1];
							$created_date			= date('Y-m-d');
							$company_name			= $_POST['company_name'];
							$email						= $_POST['email'];
							$status						= $_POST['status'];
							$elmts	= array("camp_id" => $camp_id,"camp_name" => $camp_name,"no_of_months" => $no_of_months,"camp_value" => $value,"start_date" => $camp_start_date,"end_date" => $camp_end_date,"created_date" => $created_date,"company_name" => $company_name,"email" => $email,"language_id" => 1,"status" => $status);
							$result = $objCamp->_updateCamp($_REQUEST['id'],$lanId,$elmts);
						}
						else
						{
							$camp_id					=	$_POST['camp_id'];
						    $camp_name				= $_POST['camp_name'];
							$no_of_months			= $_POST['no_of_months'];
							$value						= $_POST['camp_value'];
							$camp_start_date		= $_POST['camp_start_date'];
							$split_start					=	explode("/",$camp_start_date);
							$camp_start_date		=	$split_start[2]."-".$split_start[0]."-".$split_start[1];
							$camp_end_date		= $_POST['camp_end_date'];
							$split_end					=	explode("/",$camp_end_date);
							$camp_end_date		=	$split_end[2]."-".$split_end[0]."-".$split_end[1];
							$created_date			= date('Y-m-d');
							$company_name			= $_POST['company_name'];
							$email						= $_POST['email'];
							$status						= $_POST['status'];
							$result = $objCamp->_insertOneCamp($_REQUEST['id'],$lanId,$camp_id,$camp_name,$no_of_months,$value,$camp_start_date,$camp_end_date,$created_date,$company_name,$email,$status);
						}
				
			
			header("Location:manage_campaign.php?status=success_update&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']);
			
				}
		}
	}
	//if edit following will execute on loading
	if($_REQUEST['id'] and count($errorMsg)==0){
		//Some security check here
		$result = $objCamp->_getAllById($_REQUEST['id']);
		}
	
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
                       
						  
				<form name="campform" action="addedit_campaign.php" method="post" onSubmit="return formChecking()">
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
										
										<table height="50" class="topActions"><tr><td valign="middle" width="50"><a href="manage_campaign.php?pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=$_REQUEST['keyword']?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;List   </a></td>
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
								$camp_id[0]['hex'] = stripslashes(stripslashes(stripslashes($result[0]['camp_id'])));
								$_POST['camp_id'] = stripslashes(stripslashes(stripslashes($result[0]['camp_id'])));
								$_POST['camp_name'] = stripslashes(stripslashes(stripslashes($result[0]['camp_name'])));
								$_POST['camp_value'] = stripslashes(stripslashes(stripslashes($result[0]['camp_value'])));
								$_POST['no_of_months'] = stripslashes(stripslashes(stripslashes($result[0]['no_of_months'])));
								$date_db = stripslashes(stripslashes(stripslashes($result[0]['end_date'])));
								$split_start1							=	explode("-",$date_db);
							    $_POST['camp_end_date']		=	$split_start1[1]."/".$split_start1[2]."/".$split_start1[0];
								$date_db_strt							= stripslashes(stripslashes(stripslashes($result[0]['start_date'])));
								$split_start2							=	explode("-",$date_db_strt);
							    $_POST['camp_start_date']		=	$split_start2[1]."/".$split_start2[2]."/".$split_start2[0];
								$_POST['company_name'] = stripslashes(stripslashes(stripslashes($result[0]['company_name'])));
								$_POST['email'] = stripslashes(stripslashes(stripslashes($result[0]['email'])));
								$_POST['status']  =  $result[0]['status'];
							}
					?> 
					<fieldset ><legend><?php echo $val; ?></legend>
				   <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
						<tr>
						<td width="35%" align="right">
						  Jiwok Code:&nbsp;						</td>
						<td width="79%">
						<input type="text" name="camp_id" size="25" maxlength="100"  value="<?=$_POST['camp_id']?>">           
						</td>
							<tr>
					  <td colspan="2" align="center">&nbsp;</td>
					  </tr>
					  <tr>
					  <td width="35%" align="right">
						  Campaign Name:&nbsp;						</td>
						<td width="79%">
						<input type="text" name="camp_name" size="25" maxlength="100"  value="<?=$_POST['camp_name']?>"> &nbsp;<span class="tooltip" onMouseOver="tooltip('Name of the Campaign');" onMouseOut="exit();">[?]</span>             
						</td>
						</tr>
						<tr>
					  <td colspan="2" align="center">&nbsp;</td>
					  </tr>
						 <tr>
					  <td width="35%" align="right">
						  Free Period:&nbsp;						</td>
						<td width="79%">
						<input type="text" name="no_of_months" size="25" maxlength="100"  value="<?=$_POST['no_of_months']?>"> &nbsp;<span class="tooltip" onMouseOver="tooltip('Duration of free period in months');" onMouseOut="exit();">[?]</span> (In Months)               
						</td>
						</tr>
						<tr>
					  <td colspan="2" align="center">&nbsp;</td>
					  </tr>
						 <tr>
					  <td width="35%" align="right">
						  Value:&nbsp;						</td>
						<td width="79%">
						<input type="text" name="camp_value" size="25" maxlength="100"  value="<?=$_POST['camp_value']?>">&nbsp;<span class="tooltip" onMouseOver="tooltip('Value of the card in cents');" onMouseOut="exit();">[?]</span> (In Cents)
						</td>
						</tr>
						<tr>
					  <td colspan="2" align="center">&nbsp;</td>
					  </tr>
					  <tr>
					  <td width="35%" align="right">
						  Campaign starting date:&nbsp;						</td>
						<td width="79%">
						<input type="text" name="camp_start_date" size="25" maxlength="100"  value="<?=$_POST['camp_start_date']?>" readonly>&nbsp;
						<script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'campform',
		// input name
		'controlname': 'camp_start_date'
	});

	</script>&nbsp;<span class="tooltip" onMouseOver="tooltip('Starting date of the campaign');" onMouseOut="exit();">[?]</span>     
						</td>
						</tr>
						<tr>
					  <td colspan="2" align="center">&nbsp;</td>
					  </tr>
					  <tr>
					  <td width="35%" align="right">
						  Campaign ending date:&nbsp;						</td>
						<td width="79%">
						<input type="text" name="camp_end_date" size="25" maxlength="100"  value="<?=$_POST['camp_end_date']?>" readonly>&nbsp;
						<script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'campform',
		// input name
		'controlname': 'camp_end_date'
	});

	</script>&nbsp;<span class="tooltip" onMouseOver="tooltip('Ending date of the campaign');" onMouseOut="exit();">[?]</span>     
						</td>
						</tr>
						<tr>
					  <td colspan="2" align="center">&nbsp;</td>
					  </tr>
						 <tr>
					  <td width="35%" align="right">
						  Company Name:&nbsp;						</td>
						<td width="79%">
						<input type="text" name="company_name" size="25" maxlength="100"  value="<?=$_POST['company_name']?>">  &nbsp;<span class="tooltip" onMouseOver="tooltip('Name of the company');" onMouseOut="exit();">[?]</span>              
						</td>
						</tr>
						<tr>
					  <td colspan="2" align="center">&nbsp;</td>
					  </tr>
						 <tr>
					  <td width="35%" align="right">
						  Email:&nbsp;						</td>
						<td width="79%">
						<input type="text" name="email" size="25" maxlength="100"  value="<?=$_POST['email']?>"> &nbsp;<span class="tooltip" onMouseOver="tooltip('Company email');" onMouseOut="exit();">[?]</span>               
						</td>
						
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
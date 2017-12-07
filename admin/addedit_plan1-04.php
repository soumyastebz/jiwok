<?php 
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-Plan
   Programmer	::> Jestin
   Date			::> 21/03/2011
   
   DESCRIPTION::::>>>>
   This  code is used to add/edit plan.
   
*****************************************************************************/
	include_once('includeconfig.php');
	include("../includes/classes/class.plan.php");
	include_once('../includes/classes/class.General.php');
	include_once('../includes/classes/class.DbAction.php');
	include_once('forumpass.php');
		
	$heading = "Plans";
		
	$errorMsg	=	array();
		
	//setiing the default languge as english other vice the languge will be the selected one fromm the dropdrown 

	if($_REQUEST['langId']!=""){

	  	$lanId=$_REQUEST['langId'];

	}

	else{

	  	$lanId=1; 

	}
	 
	/*$objPlan	= 	new Plan();*/

	$objGen   	=	new General();

	$objAction	= 	new DbAction();

	if($_POST['add']||$_POST['update']){

	/* Validation for add and update*/

		if(trim($_REQUEST['plan_name'])=='')

			$errorMsg[]	=	"Plan name required";

			elseif(is_numeric(trim($_REQUEST['plan_name'])))

			$errorMsg[]	=	"Plan name not valid";

	
		if(trim($_REQUEST['plan_amount'])=='')

			$errorMsg[]	=	"Plan amount required";

			elseif(!is_numeric(trim($_REQUEST['plan_amount'])))

			$errorMsg[]	=	"Plan amount not valid";

			
		if(trim($_REQUEST['plan_amount_month'])=='')

			$errorMsg[]	=	"Plan Amount per month required";

			elseif(!is_numeric(trim($_REQUEST['plan_amount_month'])))

			$errorMsg[]	=	"Plan amount per month not valid";


		if(trim($_REQUEST['discount_percent'])=='')

			$errorMsg[]	=	"Plan discount required";

			elseif(!is_numeric(trim($_REQUEST['discount_percent'])))

			$errorMsg[]	=	"Plan discount not valid";

	

		/* *****IF THERE IS NO ERROR ...START ADD/UPDATE PROCCESS***** */

		if(count($errorMsg)==0){



				if($_POST['add']){}


				if($_POST['update']){}	

				

		}

		/* *****END OF ADD/UPDATE PROCCESS***** */

	}
	//if edit following will execute on loading
	if($_REQUEST['planId'] and count($errorMsg)==0){
		//Some security check here
		$result = $objPlan->_getAllById($_REQUEST['planId']);
		
	}
		
?>
<HTML><HEAD><TITLE><?=$admin_title?></TITLE>
<? include_once('metadata.php');?>
<script language="javascript" src="js/mask.js"></script>
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
                       
						  
				<form name="planform" action="addedit_plan.php" method="post" onSubmit="return formChecking()">
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
										
										<table height="50" class="topActions"><tr><td valign="middle" width="50"><a href="list_plan.php?pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=$_REQUEST['keyword']?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;List   </a></td>
										<td valign="middle" class="noneAnchor"><img src="images/add.gif" border="0" alt="Add Record">&nbsp;Add </td>
										</tr></table>
									</TD><td align="right"><?php echo REQUIRED_MESSAGE;?></td>
									</TR>
									
								  </table>
                              
				  <TABLE class="paragraph2" cellSpacing=0 cellPadding=2 width="100%">
				   <TBODY> 
					 <tr>
					  <td colspan="2" align="right">&nbsp;
				     
					<fieldset ><legend>Plan</legend>
					<table width="100%" align="center" cellspacing="2" cellpadding="2">
				   <tr height="10">

                       <td align="right" colspan="2">&nbsp;</td>

                     </tr>

                     <tr>

                       <td width="40%" align="right">

                         Name<?php echo REQUIRED;?>:&nbsp; </td>

                       <td><input type="text" name="plan_name" size="32" maxlength="100" value="<?=$_POST['plan_name']?>">                       </td>

                     </tr>
					 <tr>

                       <td width="40%" align="right">Amount<?php echo REQUIRED;?>:&nbsp; </td>

                       <td>

                           <input name="plan_amount" id="plan_amount" value="<?=$_POST['plan_amount']?>" class="paragraph" style="width:180px; background-color:#F3F3F3;">                          		</td>

                     </tr>
					 <tr>

                       <td width="40%" align="right">Amount / month<?php echo REQUIRED;?>:&nbsp; </td>

                       <td>

                           <input name="plan_amount_month" id="plan_amount_month" value="<?=$_POST['plan_amount_month']?>" class="paragraph" style="width:180px; background-color:#F3F3F3;">                          		</td>

                     </tr>
					 <tr>

                       <td width="40%" align="right">Discount %<?php echo REQUIRED;?>:&nbsp; </td>

                       <td>

                           <input name="discount_percent" id="discount_percent" value="<?=$_POST['discount']?>" class="paragraph" style="width:180px; background-color:#F3F3F3;">                          		</td>

                     </tr>
				   <tr height="30px">
						<td width="30%" align="right"> Status:&nbsp;</td>
						<td>
						<input type="radio" name="plan_status" id="active" value="1" <?php if($_POST['plan_status'] == 1) echo "checked";?>><label for="active">Active</label>
						<input type="radio" name="plan_status" id="inactive" value="0" <?php if($_POST['plan_status'] == 0) echo "checked";?>><label for="inactive">Inactive</label></td>
					</tr>
					</table>
					  </fieldset>
					
					</td></tr>
					
					<?php 	if(!$_REQUEST['planId']){ 	?>
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
				<input type="hidden" name="planId" value="<?=$_REQUEST['planId']?>">
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
		</td></tr></table>	
</body>
</html>
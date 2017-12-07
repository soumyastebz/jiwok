<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 	::> Admin-Admin add/edit delete management
   Programmer	::> Vijay 
   Date		::> 01-02-2007
   
   DESCRIPTION::::>>>>
   This  code used to add/edit an administrator to the site  .
   Super Admin can add/edit/delete the adminis .. 
*****************************************************************************/
	include_once('includeconfig.php');

	include_once('../includes/classes/class.Admin.php');

	$heading = "Administrators";
	$errorMsg	=	array();


	$adminObj = 	new Admin();
	$genObj   =	new General();
	$dbObj    =	new DbAction();
		
	//Add Administrators into database
	if(!$_REQUEST['adminId']){
		if($_SESSION['adm_id'] != 1){
			header("Location:list_admin.php?status=unauth");
		}
		
	}
	
	
	if($_POST['add']||$_POST['update']){
		
		if(trim($_POST['admin_name'])=='')
			$errorMsg[] = "Name required";
		if(trim($_POST['admin_login'])=='')
			$errorMsg[] = "Login required";
			
		if(trim($_POST['admin_pwd'])=='')
			$errorMsg[] = "Password required";
		
		if($_POST['add']){
			if(trim($_POST['conf_pwd'])=='')
				$errorMsg[] = "Confirm password required";
			if(trim($_POST['conf_pwd'])!=trim($_POST['admin_pwd']))
				$errorMsg[] = "Password mismatch";
		}
		
		if(trim($_POST['admin_email'])=='')
			$errorMsg[] = "Email required";
		elseif(!$genObj->_validate_email(trim($_POST['admin_email'])))
			$errorMsg[] = "Email does not valid";
		if(trim($_POST['admin_phone'])=='')
			$errorMsg[] = "Phone required";
			
			
					
		
			if(count($errorMsg)==0){		
					
					if($_POST['add']){
		
							$query = "SELECT admin_id FROM admin WHERE admin_email='".addslashes($_POST['admin_email'])."'";	
								$result = $GLOBALS['db']->query($query);

						$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);
						if(DB::isError($result)) 
							echo $result->getDebugInfo();
						else if(count($result) >= 1)
							$errorMsg[] = "Email Address Exists";

								
		
						$query = "SELECT admin_id FROM admin WHERE admin_login='".addslashes($_POST['admin_login'])."'";	
						$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);
						if(DB::isError($result)) 
							echo $result->getDebugInfo();
						else if(count($result) >= 1)
							$errorMsg[] = "Admin Login Exists";
									
					}	
			
					if($_POST['update']){
			
						$query = "SELECT admin_id FROM admin WHERE admin_email='".addslashes($_POST['admin_email'])."'";	
						$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);
						if(DB::isError($result)) 
							echo $result->getDebugInfo();
						else if(count($result) >= 1){
			
						if($result[0]['admin_id'] && $result[0]['admin_id'] != $_REQUEST['adminId'] )
							$errorMsg[] = "Email Address Exists";
						}
							
						$query = "SELECT admin_id FROM admin WHERE admin_login='".addslashes($_POST['admin_login'])."'";	
						$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);
						if(DB::isError($result)) 
							echo $result->getDebugInfo();
						else if(count($result) >= 1){

							if($result[0]['admin_id'] && ($result[0]['admin_id'] != $_REQUEST['adminId']) )
								$errorMsg[] = "Admin Login Exists";
						}

					}
			
			}
			

			if(count($errorMsg)==0){
			
						if($_POST['add']){
								$lelmts = $_POST;
								$relmts = array_slice($_POST,3,2);
								$elmts 	= array_merge(array_slice($_POST,0,3),array_slice($_POST,4,4));
								$elmts['admin_pwd'] = $genObj->_encodeValue($_POST['admin_pwd']);
								$elmts = $genObj->_clearElmts($elmts);
								//print_r($elmts);die;
								$result = $dbObj->_insertRecord('admin',$elmts);
								header("Location:list_admin.php?status=success_add&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']);
										
							}//end of adding
						
						
				if($_POST['update']){
					if($_SESSION['adm_id']==1)
						$elmts = array_slice($_POST,0,7);
			 		else
			  			$elmts = array_slice($_POST,0,6);
						$elmts['admin_pwd'] = $genObj->_encodeValue($_POST['admin_pwd']);
						$elmts = $genObj->_clearElmts($elmts);
						$where = "admin_id=".$_REQUEST['adminId'];
						$result = $dbObj->_updateRecord("admin",$elmts,$where);
			header("Location:list_admin.php?status=success_update&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']);
			
				}
		}
 
		
} 
	//end of add/edit

	
	//if edit following will execute on loading
	
if(!$_POST['add'] && !$_POST['update']){
	if($_REQUEST['adminId']){
		//Some security check here
		if($_REQUEST['adminId'] == 1 && $_SESSION['adm_id'] != 1){
			header("Location:list_admin.php?status=unauth");
		}
		if($_SESSION['adm_id'] != 1 && $_SESSION['adm_id'] != $_REQUEST['adminId']){
			header("Location:list_admin.php?status=unauth");
		}
			
	
		$query = "SELECT * FROM admin WHERE admin_id=".$_REQUEST['adminId'];
		$result = $GLOBALS['db']->query($query);

		if($result->fetchInto($row,DB_FETCHMODE_OBJECT)){

			foreach($row as $k=>$v){
				$_POST[$k] = stripslashes($v);
				if($k=='admin_pwd')
					$_POST['admin_pwd'] = $genObj->_decodeValue($v);
			}

		}
	}
	
}	

	
	//Decides wich should be selected
	
	if($_REQUEST['adminId']){
		if($_POST['admin_status'] == 1)
			$act_status = "Checked";
		else
			$inact_status = "Checked";
	}else{
		$_POST['admin_gender']=1;
		if($_POST['admin_status'] == 1)
			$act_status = "Checked";
		elseif($_POST['admin_status'] == 2)
			$inact_status = "Checked";
		else
			$act_status = "Checked";
		
	}

?>
<HTML><HEAD><TITLE><?=$admin_title?></TITLE>
<? include_once('metadata.php');?>
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
                       
			  			   <form name="frmadmin" action="addedit_admin.php" method="post">
                      
				  <table class="paragraph2" cellspacing=0 cellpadding=0 width=553 border=0>
				  <tr>
						<td height="50" align="center" valign="bottom" class="sectionHeading"><?=$heading;?></td>
					</tr>
					<?php 
						if($errorMsg){ ?>
					<tr>
						<td align="center"><? print $genObj->_adminmessage_box($errorMsg,$mode='error',$url='normal') ?></td>
					</tr>
					<?php } ?>

					<TR> 
					<TD align="left">
						
				   		<table height="50" class="topActions"><tr><td valign="middle" width="50"><a href="list_admin.php?action=add&pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&type=<?=$_REQUEST['type']?>&keyword=<?=$_REQUEST['keyword']?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;List   </a></td>
						<td valign="middle" class="noneAnchor"><img src="images/add.gif" border="0" alt="Add Record">&nbsp;Add </td>
						</tr></table>
					</TD>
					</TR>
					
				  </table>
                              
				  <TABLE  cellSpacing=0 cellPadding=2 width="100%">
				   <TBODY> 
					<tr>
						<td width="40%" align="right" >Admin Name<?=REQUIRED?>:&nbsp;
						</td>
						<td>
							<input type="text" name="admin_name" size="30" maxlength="100" value="<?=$genObj->_output($_POST['admin_name'])?>">
						</td>
					</tr>
					<tr>
						<td width="40%" align="right" >Admin Login
						    <?=REQUIRED?>
						    :&nbsp;						</td>
						<td>
							<input type="text" name="admin_login" size="30" maxlength="100" value="<?=$genObj->_output($_POST['admin_login'])?>">
						</td>
					</tr>
					<tr>
						<td width="40%" align="right" >Admin Password
						    <?=REQUIRED?>
						    :&nbsp;						</td>
						<td>
							<input type="password" name="admin_pwd" size="30" maxlength="29" value="<?=$genObj->_output($_POST['admin_pwd'])?>">
						</td>
					</tr>
					<?php
						if(!$_REQUEST['adminId']){
					?>
					<tr>
						<td width="40%" align="right" >Confirm Password
						    <?=REQUIRED?>
						    :&nbsp;						</td>
						<td>
							<input type="password" name="conf_pwd" size="30" maxlength="30" value="<?=$genObj->_output($_POST['conf_pwd'])?>">
						</td>
					</tr>
					<?php
						}
					?>
					<tr>
						<td width="40%" align="right" >Admin Email Address
						    <?=REQUIRED?>
						    :&nbsp;						</td>
						<td>
							<input type="text" name="admin_email" size="30" maxlength="100" value="<?=$genObj->_output($_POST['admin_email'])?>">
						</td>
					</tr>
					<tr>
						<td width="40%" align="right" >Admin Phone
						    <?=REQUIRED?>
						    :&nbsp;						</td>
						<td>
							<input type="text" name="admin_phone" size="30" maxlength="100" value="<?=$genObj->_output($_POST['admin_phone'])?>">
						</td>
					</tr>
					<tr>
						<td width="40%" align="right" >Admin Gender
						    
						    :&nbsp;						</td>
						<td >
							<input type="radio" name="admin_gender" id="male" value="1" <?php if($_POST['admin_gender']==1){ print 'Checked'; } ?>>Male
							
							
							<input type="radio" name="admin_gender" id="female" value="0" <?php if($_POST['admin_gender']==0){ print 'Checked'; } ?>>Female 
						</td>
					</tr>

					<? if($_SESSION['adm_id']==1){ ?>
					<tr>
						<td width="40%" align="right" >Admin Status
						    
						    :&nbsp;						</td>
						<td >
							<input type="radio" name="admin_status" id="active" value="1" <?php echo $act_status;?>>Active
							<?php
						if($_REQUEST['adminId']!=1){ ?>
							
							<input type="radio" name="admin_status" id="inactive" value="2" <?php echo $inact_status;?>>Inactive <? } ?>
						</td>
					</tr>
					<?php
					}
						if(!$_REQUEST['adminId']){
					?>
					<tr height="40">
						<td colspan="2" align="center" valign="bottom">
							<input type="submit" name="add" value="&nbsp;&nbsp;Add&nbsp;&nbsp;">
						</td>
					</tr>
					<?php
						}else{
					?>
					<tr height="40">
						<td colspan="2" align="center">
							<input type="submit" name="update" value="Update">
						</td>
					</tr>
					<?php
						}
					?>
				    </tbody>
			 	  </table>
			   
			   <input type="hidden" name="adminId" value="<?=$_REQUEST['adminId']?>">
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
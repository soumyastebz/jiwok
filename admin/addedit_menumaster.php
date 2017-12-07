<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 	::> Admin-Admin add/edit delete management
   Programmer	::> Vijay
   Date		::> 02-02-2007
   
   DESCRIPTION::::>>>>
   This  code used to add/edit  master menu to the site  .
   Admin can add/edit master menus .. 
*****************************************************************************/
	include_once('includeconfig.php');
	include_once('../includes/classes/class.Paging.php');
	$heading = "Administrators";
	$errorMsg	=	array();

	$genObj   =	new General();
	$dbObj    =	new DbAction();
	
	if($_POST['add']||$_POST['update']){
		
		if(trim($_POST['menumaster_name'])=='')
			$errorMsg[] = "Menu name required";
	}



if(count($errorMsg)==0){
	//Add Menu into database
	if($_POST['add']){
		//check admin already exists or not
		$query = "SELECT * FROM menu_master WHERE menumaster_name='".addslashes($_POST['menumaster_name'])."'";	

		$result = $GLOBALS['db']->query($query);


		if($result->numRows() <= 0){
				
					$elmts 		= 	array_slice($_POST,0,1);
					$elmts 		= 	$genObj->_clearElmts($elmts);
					$result 	= 	$dbObj->_insertRecord("menu_master",$elmts);
					header("Location:list_menumaster.php?status=success_add&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&keyword=".$_REQUEST['keyword']);
				
		}else
			$errorMsg[] = "Menu already exists";

	}
	//On clicking update button

	
	if($_POST['update']){
	
		$query = "SELECT * FROM menu_master WHERE menumaster_name='".$genObj->_clean_data($_POST['menumaster_name'])."'";	
		$result = $GLOBALS['db']->query($query);
		
		if($result->numRows()>0){
			$result->fetchInto($row,DB_FETCHMODE_ASSOC);
			if($row['menumaster_id'] && $row['menumaster_id'] != $_REQUEST['masterId'] ){
				$errorMsg[] = "Master Menu exists";
			}
		}

			if(count($errorMsg)==0){

				$elmts = array_slice($_POST,0,1);
				$elmts = $genObj->_clearElmts($elmts);
				$where = "menumaster_id=".$_REQUEST['masterId'];
				$result = $dbObj->_updateRecord("menu_master",$elmts,$where);
				header("Location:list_menumaster.php?status=success_update&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&keyword=".$_REQUEST['keyword']);
			}
		
	}
}	
	
	
	if(!$_POST['update'] && !$_POST['add']){
	//if edit following will execute on loading
			if($_REQUEST['masterId']){
				//Some security check here
					
				$query = "SELECT * FROM menu_master WHERE menumaster_id=".$_REQUEST['masterId'];
				$result = $dbObj->_execQuery($query);
			
				if($result->fetchInto($row,DB_FETCHMODE_OBJECT)){
					foreach($row as $k=>$v){
						$_POST[$k] = stripslashes($v);
					}
				}
				
			}
	}
	
	
?>
<HTML><HEAD><TITLE><?=$admin_title?></TITLE>
<? include_once('metadata.php');?>
<BODY  class="bodyStyle">
<TABLE cellSpacing=0 cellPadding=0 width="779" align="center" border="1px" bordercolor="#E6E6E6">
  <TR>
    <TD vAlign=top align=left bgColor=#ffffff><? include("header.php");?></TD>
  </TR>
  <TR height="5">
    <TD vAlign=top align=left class="topBarColor">&nbsp;</TD>
  </TR>
  <TR>
    <TD vAlign="top" align="left" height="340"> 
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 class="middleTableBg">
        <TR> 
          <TD vAlign=top align=left width="175" rowSpan="2"> 
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
          <TD vAlign=top align=left width=0></TD>
         
        </TR>
        <TR> 
          <TD valign="top" width="1067"><!---Contents Start Here----->
		  
		  
            <TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
              <TR> 
                <TD class=smalltext width="98%" valign="top">
				
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
                       
			  			   <form name="frmadmin" action="addedit_menumaster.php" method="post">
                      
				  <table class="paragraph2" cellspacing=0 cellpadding=0 width=553 border=0>
				  <tr>
						<td height="50" align="center" valign="bottom" class="sectionHeading"><?=$heading;?></td>
					</tr>
					<?php 
						if($errorMsg){
					?>
					<tr>
						<td align="center"><? print $genObj->_adminmessage_box($errorMsg,$mode='error',$url='normal') ?></td>
					</tr>
					<?php } ?>
					
					<TR> 
					<TD align="left">
						
				   		<table height="50" class="topActions"><tr><td valign="middle" width="50"><a href="list_menumaster.php?pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&type=<?=$_REQUEST['type']?>&keyword=<?=$_REQUEST['keyword']?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;List   </a></td>
						<td valign="middle" class="noneAnchor"><img src="images/add.gif" border="0" alt="Add Record">&nbsp;Add </td>
						</tr></table>
					</TD>
					</TR>
					
				  </table>
                              
				    <TABLE class="paragraph2" cellSpacing=0 cellPadding=2 width="100%">
				   <TBODY> 
					<tr>
						<td width="40%" align="right"> Name:&nbsp;
						</td>
						<td>
							<input type="text" name="menumaster_name" size="30" maxlength="100" value="<?=$genObj->_output($_POST['menumaster_name']);?>">
						</td>
					</tr>
					
					
					<?php
						if(!$_REQUEST['masterId']){
					?>
					<tr  height ="30">
						<td colspan="2" align="center" valign="bottom">
							<input type="submit" name="add" value="Add">
						</td>
					</tr>
					<?php
						}else{
					?>
					<tr>
						<td colspan="2" align="center">
							<input type="submit" name="update" value="Update">
						</td>
					</tr>
					<?php
						}
					?>
				    </tbody>
			 	  </table>
			   
			   <input type="hidden" name="masterId" value="<?=$_REQUEST['masterId']?>">
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
    <TD vAlign=top align=left class="topBarColor" colspan="3">&nbsp;</TD>
  </TR>
      </TABLE>
        <?php include_once("footer.php");?>
</body>
</html>
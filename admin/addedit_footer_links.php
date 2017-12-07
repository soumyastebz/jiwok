<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 	::> Admin-Footer Links add/edit delete management
   Programmer	::> Prasanth Bendra
   Date		::> 01-02-2007
   
   DESCRIPTION::::>>>>
   This  code used to add/edit Footer Links .
  
*****************************************************************************/
	include_once('includeconfig.php');
	include_once('../includes/classes/class.footerLinks.php');
	include_once('../includes/classes/class.DbAction.php');
    
	$heading = "Footer Links";
	$errorMsg	=	array();
   

	$lanObj = 	new footerLinks();
	$genObj   =	new General();
	$dbObj    =	new DbAction();
	$lanObj->_getLanguageArray();
	$languageArray=$siteLanguagesConfig;
	
	if($_REQUEST['langId']!="")
	  $langId=$_REQUEST['langId'];
	else
	  $langId=1; 
	    //validation strts herefor feilds 
		if($_POST['add']||$_POST['update']){
					
				if(trim($_POST['footerName'])=='')
					$errorMsg[] = "Footer name is empty";
				if(trim($_POST['link'])=='')
					$errorMsg[] = "Link for footer is empty";
					
			    //form validation ends here	
				
					
		
			if(count($errorMsg)==0){
			        //after successful validaation checking the existence in the database			
					if($_POST['update']){
					
					        $Id=$_POST['lanId'];
					  
							$query = "SELECT id  FROM footer_links WHERE id !=".$Id." AND footer_name ='".addslashes($_POST['footerName'])."'	AND	lanId	=	'".$_POST['langId']."'";	
							$result = $GLOBALS['db']->query($query);
							$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);
							if(DB::isError($result)) 
								echo $result->getDebugInfo();
							else if(count($result) >= 1)
								$errorMsg[] = "Footer Link already exist";
					}
			}
           
			if(count($errorMsg)==0){					
						
				if($_POST['update']){ 
								
						$languageName = array();
						$languageName['footer_name'] = $_POST['footerName'];
						$languageName['link']     = $_POST['link'];
						$languageName['status']   = $_POST['status'];
						
						$Id=$_POST['lanId'];
						
						$dbObj->_updateRecord("footer_links",$languageName,"id = $Id");;
												
						header("Location:list_footer_links.php?status=success_update&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']);
						
				}
				
				
				if($_POST['add']){
								
				        $languageName = array();
						$languageName['footer_name'] = $_POST['footerName'];
						$languageName['link']     = $_POST['link'];  
						$languageName['status']   = $_POST['status'];
						$languageName['lanId']   = $_REQUEST['langId'];
						
						$languageName	= 	$genObj->_clearElmts($languageName);
						
												
						$dbObj->_insertRecord("footer_links",$languageName);
												
						header("Location:list_footer_links.php?status=success_add&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']);
						
				}	
		}
		
			
	} 
	//end of add/edit

//if edit following will execute on loading
	
if($_REQUEST['lanId'] && !$_POST['update']){
$Id=$_REQUEST['lanId'];
$languageDetails	= $lanObj->_getFooterDetails($Id);	
$footerName 		= $genObj->_output($languageDetails['footer_name']);
$link 		    = $genObj->_output($languageDetails['link']);
$status 		= $genObj->_output($languageDetails['status']);
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
                       
			  			   <form action="addedit_footer_links.php" method="post" enctype="multipart/form-data" name="frmlanguages">
                      
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
						
				   		<table height="50" class="topActions"><tr><td valign="middle" width="50"><a href="list_footer_links.php?action=add&pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&type=<?=$_REQUEST['type']?>&keyword=<?=$_REQUEST['keyword']?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;List   </a></td>
						<td valign="middle" class="noneAnchor">&nbsp;</td>
						</tr></table>
					</TD>
					</TR>
					<TR><TD colspan="2" align="right"><?php echo REQUIRED_MESSAGE;?></TD></TR>
				  </table>
                              
				  <TABLE  cellSpacing=0 cellPadding=2 width="100%">
				   <TBODY> 
				   <tr><td colspan="2">
				   
				   <table width="100%" border="0" cellspacing="0" cellpadding="0">
                   
				             
					<tr>
						<td colspan="3" align="left" style="padding-left:20px;" class="sectionHeading"></td>
						</tr>
					<tr >
					  <td colspan="3" align="center" >&nbsp;</td>
					  </tr>
					<tr>
					  <td height="24" align="right" valign="top">&nbsp;</td>
					  <td colspan="2" align="left" valign="top">&nbsp;</td>
					  </tr>
					<tr>
					  <td height="24" align="right" valign="top">Footer Name<?php echo REQUIRED;?> :&nbsp;</td>
					  <td colspan="2" align="left" valign="top"><input type="text" name="footerName" size="30" maxlength="100" value="<?php if($_POST['footerName']) echo $_POST['footerName']; else echo $footerName;  ?>"></td>
					</tr>
					
					<tr>
					  <td height="24" align="right" valign="top">Link<?php echo REQUIRED;?> :&nbsp;</td>
					  <td colspan="2" align="left" valign="top"><input type="text" name="link" size="30" maxlength="100" value="<?php if($_POST['link']) echo $_POST['link']; else echo $link;  ?>"></td>
					</tr>
					<tr>
					  <td height="24" align="right" valign="top">Status<?php echo REQUIRED;?> :&nbsp;</td>
					  <td colspan="2" align="left" valign="top"><input type="radio" name="status" checked="checked" value="1">&nbsp;Active&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="status" value="0" <?php if($status == 0){?> checked="checked" <?php }?>>&nbsp;Inactive</td>
					</tr>
					
					</table>
				   
				  
				  
				   </td></tr>
					
					
					
					<tr>
						<td width="40%" height="38" align="right" >&nbsp;						</td>
						<td >&nbsp;</td>
					</tr>
					<?php
					
						if(!$_REQUEST['lanId']){
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
							<input type="submit" name="update" value="Update">						</td>
					</tr>
					<?php
						}
					?>
				    </tbody>
			 	  </table>
			   <input type="hidden" name="lanId" value="<?=$_REQUEST['lanId']?>">
			    <input type="hidden" name="langId" value="<?=$_REQUEST['langId']?>"> 
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
<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 	::> Admin-Categories add/edit delete management
   Programmer	::> Vijay
   Date		::> 02-02-2007
   
   DESCRIPTION::::>>>>
   This  code used to add/edit  categories to the site  .
   Admin can add/edit categories .. 
*****************************************************************************/
	include_once('includeconfig.php');
	include_once('../includes/classes/class.Paging.php');
	$heading = $_REQUEST['heading'];
	$errorMsg	=	array();

	$genObj   =	new General();
	$dbObj    =	new DbAction();

	
	if($_POST['add']||$_POST['update']){
		

	     foreach($siteLanguagesConfig as $key=>$data){
		$fieldName	= 'label_name_'.$key;
		if(trim($_POST[$fieldName])=='')
			$errorMsg[] = "Category name in $data required";
		
	
	     }

	//Add Menu into database
	if($_POST['add']){
		//check admin already exists or not

		foreach($siteLanguagesConfig as $key=>$data){
			$fieldName	= 'label_name_'.$key;
			$query = "SELECT categories.category_id FROM categories,label_manager WHERE label_manager.label_name='".$genObj->_clean_data($_POST[$fieldName])."' and categories.category_parent =".$_REQUEST['masterId']." and categories.category_id=label_manager.labeltype_id and label_manager.label_type='CATEGORY' and label_manager.language_id=".$key ; 

			$result = $GLOBALS['db']->query($query);
			if($result->numRows() > 0){
				$errorMsg[] = "Category already exists in ".$data;
			}
		}

			if(count($errorMsg)==0){

					$query 		= "SELECT max(category_id) as max FROM categories";
					$result 	= $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);
					

					$elmts['category_id'] 	= 	$nextCategoryId = $result[0]->max +1;
					$elmts['category_status'] 	= 	$_POST['category_status'];
					$elmts['category_parent']	=	$_REQUEST['masterId'];
					$elmts 			= 	$genObj->_clearElmts($elmts);
					$result 		= 	$dbObj->_insertRecord("categories",$elmts);

					foreach($siteLanguagesConfig as $key=>$data){
						$fieldName	= 'label_name_'.$key;
						$elmts	=	array();
						$elmts['language_id']	=	$key;
						$elmts['labeltype_id']	=	$nextCategoryId;
						$elmts['label_type']	=	'CATEGORY';
						$elmts['label_name']	=	$genObj->_clean_data($_POST[$fieldName]);
						$result 		= 	$dbObj->_insertRecord("label_manager",$elmts);
					}

					
					$heading = urlencode($_REQUEST['heading']);
					header("Location:list_categories.php?status=success_add&pageNo=".$_REQUEST['pageNo']."&masterId=".$_REQUEST['masterId']."&langId=".$_REQUEST['langId']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&maxrows=".$_REQUEST['maxrows']."&heading=$heading"."&keyword=".$_REQUEST['keyword']);
			}
		
		
	}
	//On clicking update button
	
	if($_POST['update']){
	
		foreach($siteLanguagesConfig as $key=>$data){
			$fieldName	= 'label_name_'.$key;
			$query = "SELECT categories.category_id FROM categories,label_manager WHERE label_manager.label_name='".$genObj->_clean_data($_POST[$fieldName])."' and categories.category_parent =".$_REQUEST['masterId']." and categories.category_id=label_manager.labeltype_id and label_manager.label_type='CATEGORY' and label_manager. language_id=".$key." and categories.category_id !=".$_REQUEST['catId'] ;
			$result = $GLOBALS['db']->query($query);
			if($result->numRows() > 0){
				$errorMsg[] = "Category name already exists in ".$data;
			}
		}
			
		if(count($errorMsg)==0){

					$elmts['category_status'] 	= 	$_POST['category_status'];
					$elmts['category_parent']	=	$_REQUEST['masterId'];
					$elmts 			= 	$genObj->_clearElmts($elmts);
					$where = "category_id=".$_REQUEST['catId'];
					$result 		= 	$dbObj->_updateRecord('categories',$elmts,$where);
					
					foreach($siteLanguagesConfig as $key=>$data){
					
						
						 //searching for the existence of the menu 
                        $query = "SELECT  count(*) as dataCount FROM label_manager WHERE labeltype_id=".$_REQUEST['catId']." AND label_type='CATEGORY' AND language_id=".$key."";	
						$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);
						$coundRecs = $result[0]->dataCount;
							if($coundRecs>0){						
								$fieldName	= 'label_name_'.$key;
								$elmts	=	array();
								$where = "labeltype_id=".$_REQUEST['catId']." and language_id=".$key." and label_type='CATEGORY'";
								$elmts['label_name']	=	$genObj->_clean_data($_POST[$fieldName]);
								$result 		= 	$dbObj->_updateRecord('label_manager',$elmts,$where);
							}else{
								$fieldName	= 'label_name_'.$key;
								$elmts	=	array();
								$elmts['language_id']	=	$key;
								$elmts['labeltype_id']	=	$_REQUEST['catId'];
								$elmts['label_type']	=	'CATEGORY';
								$elmts['label_name']	=	$genObj->_clean_data($_POST[$fieldName]);
								$result 				= 	$dbObj->_insertRecord("label_manager",$elmts);
						}
					}
					
			$heading = urlencode($_REQUEST['heading']);
			header("Location:list_categories.php?status=success_update&pageNo=".$_REQUEST['pageNo']."&masterId=".$_REQUEST['masterId']."&langId=".$_REQUEST['langId']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&maxrows=".$_REQUEST['maxrows']."&heading=$heading"."&keyword=".$_REQUEST['keyword']);
		}
	}

}// End of Error Checking

if(!$_POST['add']&&!$_POST['update']){
	//if edit following will execute on loading
	if($_REQUEST['catId']){
		//Some security check here
		$query = "SELECT * FROM categories WHERE category_id=".$_REQUEST['catId'];
		$result = $dbObj->_execQuery($query);
			
			if($result->fetchInto($row,DB_FETCHMODE_OBJECT)){
					foreach($row as $k=>$v){
						$_POST[$k] = $genObj->_output($v);
					}
				}
		//Some security check here
		$query = "SELECT  label_name,language_id FROM label_manager WHERE labeltype_id =".$_REQUEST['catId']." and label_type='CATEGORY'";
		$result = $dbObj->_execQuery($query);
			
			while($result->fetchInto($row,DB_FETCHMODE_ASSOC)){
						
					$_POST['label_name_'.$row['language_id']] = $genObj->_output($row['label_name']);
				}
	}
}


	//Decides wich should be selected
	
	if($_REQUEST['catId']){
		if($_POST['category_status'] == 1){
			$act_status = "Checked";
		}else{
			$inact_status = "Checked";
		}
	}else{
		if($_POST['category_status'] == 1){
			$act_status = "Checked";
		}elseif($_POST['category_status'] == 2){
			$inact_status = "Checked";
		}else{
			$act_status = "Checked";
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
                       
			  			   <form action="addedit_categories.php" method="post" enctype="multipart/form-data" name="frmadmin">
                      
				  <table class="paragraph2" cellspacing=0 cellpadding=0 width=553 border=0 >
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
						
				   		<table height="50" class="topActions"><tr><td valign="middle"><a href="list_categories.php?masterId=<?=$_REQUEST['masterId']?>&heading=<?=$_REQUEST['heading']?>&pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=$_REQUEST['keyword']?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;List&nbsp;<?=$_REQUEST['heading']?></a>&nbsp;</td>
						<td valign="middle" class="noneAnchor" ><img src="images/add.gif" border="0" alt="Add Record">&nbsp;Add </td>
						
						</tr></table>
					</TD>
					</TR>
					
				  </table><br>
                              
				    <TABLE class="paragraph2" cellSpacing=0 cellPadding=2 width="100%">
				   <TBODY> 

					<? foreach($siteLanguagesConfig as $key=>$data){ 
					 $fieldName	= 'label_name_'.$key;
?>
					<tr height="30">
						<td width="40%" align="right"> Name in <? print $data;?><?=REQUIRED?>:&nbsp;						</td>
						<td>
							<input type="text" name="<?=$fieldName;?>" size="30" value="<?=$genObj->_output($_POST[$fieldName])?>">						</td>
					</tr>
					
					<? } ?>

					<tr>
						<td width="40%" align="right"> Status:&nbsp;						</td>
						<td>
							<input type="radio" name="category_status" id="active" value="1" <?php echo $act_status;?>><label for="active">Active</label>
							<input type="radio" name="category_status" id="inactive" value="2" <?php echo $inact_status;?>><label for="inactive">Inactive</label>						</td>
					</tr>
					<?php
						if(!$_REQUEST['catId']){
					?>
					<tr>
						<td colspan="2" align="center">
							<input type="submit" name="add" value="Add">						</td>
					</tr>
					<?php
						}else{
					?>
					<tr>
						<td colspan="2" align="center">
							<input type="submit" name="update" value="Update">  <input type="hidden" name="picturePath" value="<?=$_POST['picturePath'];?>"> 						</td>
					</tr>
					<?php
						}
					?>
				    </tbody>
			 	  </table>
			   <input type="hidden" name="masterId" value="<?=$_REQUEST['masterId']?>">
			   <input type="hidden" name="catId" value="<?=$_REQUEST['catId']?>">
			   <input type="hidden" name="pageNo" value="<?=$_REQUEST['pageNo']?>">
			   <input type="hidden" name="maxrows" value="<?=$_REQUEST['maxrows']?>">
			   <input type="hidden" name="type" value="<?=$_REQUEST['type']?>">
			   <input type="hidden" name="langId" value="<?=$_REQUEST['langId']?>">
				<input type="hidden" name="field" value="<?=$_REQUEST['field']?>">
				<input type="hidden" name="heading" value="<?=$_REQUEST['heading']?>">

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
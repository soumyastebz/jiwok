<?php

/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-Custompage Management
   Programmer	::> Herenkumar K
   Date			::> 02/03/2009
   
   DESCRIPTION::::>>>>
   This  code userd to add/edit custompage.
   
*****************************************************************************/
	include_once('includeconfig.php');
	include("../includes/classes/class.custompage.php");
	include_once('../FCKeditor/fckeditor.php');
	
	/*
	 Instantiating the classes.
	*/
	$lanId = 1;
	$objCustompage = new Custompage($lanId);
	$objGen   =	new General();
	
	$heading = "Dynamic Page";
	
	$errorMsg	=	array();
	
	/*
	Take all the languages to an array.
	*/
	$languageArray = $siteLanguagesConfig;
		
	if($_POST['add']||$_POST['update']){
	
		reset($languageArray);
		while(list($key,$value) = each($languageArray)){
			
			if(trim($_POST['manager_title_'.$key])=='')
				$errorMsg[] = "Title required for {$value}";
		
			if(trim($_POST['manager_body_'.$key])=='')
				$errorMsg[] = "Body required for {$value}";
				
			if(trim($_POST['manager_url'])=='')
				$errorMsg[] = "Url required for {$value}";
				
				
		}
						
		
		if($_POST['add'])	{
			reset($languageArray);
					// check whether already existing while adding (Checking url)
					$check	= $objCustompage->_isCustompageExists($objGen->_clean_data($_REQUEST['manager_url']));
					if($check) 
						$errorMsg[] = "Custompage url already exists.";
		}
			
		if($_POST['update'])	{
			reset($languageArray);
			// check whether question is already existing while updating
				$check	= $objCustompage->_isCustompageExists($objGen->_clean_data($_REQUEST['manager_url']),$_REQUEST['custompageId']);
				if($check){ 
						$errorMsg[] = "Custompage url already exists.";
						break;
				}
				
		}
	
	if($_POST['add']){
		//check custompage already exists or not
		
		if(count($errorMsg)==0)	{
			$objCustompage->_insertMaster($_POST['custompage_status']);
			reset($languageArray);
			while(list($key,$value) = each($languageArray)){
				$title	= $_POST['manager_title_'.$key];
				$body	= $_POST['manager_body_'.$key];
				$url	= $_POST['manager_url'];
				$status	= $_POST['custompage_status'];
				
				$elmts	= array("manager_title" => $title,"manager_body" => $body, "manager_url" => $url,"custompage_status" => $status,"language_id" => $key);
				
				$result =  $objCustompage->_insertCustompage($elmts,count($languageArray));
			}
			header("Location:list_newpage.php?status=success_add&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']);
		}
	}
	//On clicking update button
	
	if($_POST['update']){
			
		if(count($errorMsg)==0)	{
			reset($languageArray);
			while(list($key,$value) = each($languageArray)){
			
			        $query = "SELECT  count(*) as dataCount FROM custompage_manager WHERE custompagemaster_id  =".$_REQUEST['custompageId']." AND language_id=".$key."";
					$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);
					$coundRecs = $result[0]->dataCount;
					if(DB::isError($result)) 
						echo $result->getDebugInfo();
					if($coundRecs >= 1){	
						$title	= $_POST['manager_title_'.$key];
						$body	= $_POST['manager_body_'.$key];
						$url	= $_POST['manager_url'];
						$status	= $_POST['custompage_status'];
						
						$elmts	= array("manager_title" => $title,"manager_body" => $body, "manager_url" => $url,"custompage_status" => $status);
						$result = $objCustompage->_updateCustompage($_REQUEST['custompageId'],$key,$elmts);
					}
					else{
					    $title	= $_POST['manager_title_'.$key];
						$body	= $_POST['manager_body_'.$key];
						$url	= $_POST['manager_url'];
					    $result = $objCustompage->_insertOneRow($_REQUEST['custompageId'],$key,$title,$body,$url);
					}
			}
			header("Location:list_newpage.php?status=success_update&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']);
		}
	}
}	
	//if edit following will execute on loading
	if($_REQUEST['custompageId'] and count($errorMsg)==0){
		//Some security check here
		$result = $objCustompage->_getAllById($_REQUEST['custompageId']);
		
				
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
                       
						  
				<form name="custompageform" action="addedit_newpage.php" method="post" onSubmit="return formChecking()">
						  <TABLE cellSpacing=0 cellPadding=4 width=561 border=0>
                          <TBODY> 
                          <TR> 
                            <TD>
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
										
										<table height="50" class="topActions"><tr><td valign="middle" width="50"><a href="list_newpage.php?pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=$_REQUEST['keyword']?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;List   </a></td>
										<td valign="middle" class="noneAnchor"><img src="images/add.gif" border="0" alt="Add Record">&nbsp;Add </td>

<td align="right">&nbsp;</td>
										</tr></table>
									</TD>
									</TR>
									
								  </table>
                       <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
					<script src="../ckeditornew/ckeditor/ckeditor.js"></script>
                    <script src="../ckeditornew/ckeditor/adapters/jquery.js"></script>        
				  <TABLE class="paragraph2" cellSpacing="0" cellPadding="0" width="95%">
				   <TBODY> 
				    <tr>
					   <td colspan="2" align="right">&nbsp;<?php echo REQUIRED_MESSAGE;?></td>
					   </tr>
					 <tr>
					  <td colspan="2" align="center">&nbsp;
					 <? 
					 	$n	= 0;
					 	reset($languageArray);
						while(list($key,$val) = each($languageArray)){
							if(count($result) != 0){
								$_POST['manager_title_'.$key] 	= $result[$n]['manager_title'];
								$_POST['manager_body_'.$key]  	=  $result[$n]['manager_body'];
								$_POST['custompage_status']  	=  $result[$n]['custompage_status'];
								$_POST['manager_url']  			=  $result[$n]['manager_url'];
							}
					?> 
					 <fieldset ><legend><?php echo $val; ?></legend>
				   <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#EFEFDE">
					
					 
					
						<td width="10%" align="left">
						  Title<?php echo REQUIRED;?>:&nbsp;						</td>
						<td>
							
							<input type="text" name="manager_title_<?=$key?>" size="48" value="<?=$objGen->_output($_POST['manager_title_'.$key])?>"></td>
					</tr>
					
					 
					 <tr>
						<td colspan="2" align="left">Body<?php echo REQUIRED;?>:&nbsp;</td>
					</tr>
					
					<script>
					CKEDITOR.disableAutoInline = true;
					$( document ).ready( function() {
							$( '#manager_body_<?php echo $key; ?>' ).ckeditor(); // Use CKEDITOR.replace() if element is <textarea>.
					});
							
					</script>
					 <tr>
						<td colspan="2">	
						
						<?

						 			/*$oFCKeditor = new FCKeditor('manager_body_'.$key) ;	

									$oFCKeditor->BasePath = '../FCKeditor/' ;

									$oFCKeditor->Width	= '545' ;

									$oFCKeditor->Height	= '350' ;

									if ( isset($_GET['Toolbar']) )

									$oFCKeditor->ToolbarSet = $_GET['Toolbar'] ;

									$oFCKeditor->Value =$objGen->_output($_POST['manager_body_'.$key]);

									$oFCKeditor->create();*/

								?>
						
						<textarea name="manager_body_<?php echo $key; ?>" id="manager_body_<?php echo $key; ?>" ><?php echo $_POST['manager_body_'.$key]; ?></textarea>
						</td>
					</tr>
					
					
					
					
					
					
					
					
					<tr>
					  <td colspan="2" align="center">&nbsp;</td>
					  </tr>
					  
					  </table>
					  </fieldset>
					<? 
						$n++;
						}
					?>
					</td></tr>
					
					<tr>
						<td width="30%" align="left" style="padding:5px;"> Page Name:&nbsp;</td>
						<td colspan="2"><input type="text" name="manager_url" size="48" value="<?=stripslashes($_POST['manager_url'])?>">
						</td>
					</tr>	
					
									
					<tr>
						<td width="30%" align="right"> Status:&nbsp;</td>
						<td>
						<input type="radio" name="custompage_status" id="active" value="1" <?php if($_POST['custompage_status'] == 1) echo "checked";?>><label for="active">Active</label>
						<input type="radio" name="custompage_status" id="inactive" value="0" <?php if($_POST['custompage_status'] == 0) echo "checked";?>><label for="inactive">Inactive</label></td>
					</tr>
					
					  
					
					
					
					<?php 	if(!$_REQUEST['custompageId']){ 	?>
					<tr>
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
				<input type="hidden" name="custompageId" value="<?=$_REQUEST['custompageId']?>">
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

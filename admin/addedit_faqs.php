<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-FAQ
   Programmer	::> NISHA
   Date			::> 04/02/2009
   
   DESCRIPTION::::>>>>
   This  code userd to add/edit faq.
   
*****************************************************************************/
	include_once('includeconfig.php');
	include("../includes/classes/class.faq.php");
	
	/*
	 Instantiating the classes.
	*/
	$lanId = $_REQUEST['langId'];
	
	$objFaq = new Faq($lanId);
	$objGen   =	new General();
	
	$heading = "FAQ's";
	/*
	Take all the languages to an array.
	*/
	$languageArray=$siteLanguagesConfig;
	reset($languageArray);
	
	$errorMsg	=	array();
		
	if($_POST['add']||$_POST['update']){
	
		reset($languageArray);
		while(list($key,$value) = each($languageArray)){
				if(trim($_POST['manager_question_'.$key])=='')
					$errorMsg[] = "Question required for {$value}";
					
				if(trim($_POST['manager_answer_'.$key])=='')
					$errorMsg[] = "Answer required for {$value}";
		}
						
		if($_POST['add'])	{
			reset($languageArray);
			while(list($key,$value) = each($languageArray)){
				// check whether question is already existing while adding
				$check	= $objFaq->_isFaqExists($objGen->_clean_data($_REQUEST['manager_question_'.$key]));
				if($check) 
					$errorMsg[] = "Question already exists for {$value}";
			}				
				
		}
			
		if($_POST['update'])	{
			reset($languageArray);
			while(list($key,$value) = each($languageArray)){
				// check whether question is already exixting while updating
				$check = false;
				$check	= $objFaq->_isFaqExists($objGen->_clean_data($_REQUEST['manager_question_'.$key]),$_REQUEST['faqId']);
				if($check) 
						$errorMsg[] = "Question already exists";
			}			
				echo $check;
		}
	
	if($_POST['add']){
		//check admin already exists or not
			
		if(count($errorMsg)==0)	{
			$nextId = $objFaq->_insertMaster($_POST['faq_status']);
			reset($languageArray);
			while(list($key,$value) = each($languageArray)){
				$title	= $_POST['manager_question_'.$key];
				$body	= $_POST['manager_answer_'.$key];
				$status	= $_POST['faq_status'];
				$elmts	= array("manager_question" => $title,"manager_answer" => $body,"faq_status" => $status,"language_id" => $key);
				
				$result =  $objFaq->_insertFaq($elmts,count($languageArray));
			}
			header("Location:list_faqs.php?status=success_add&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']);
		}
	}
	//On clicking update button
	
	if($_POST['update']){
			
		if(count($errorMsg)==0)	{
			reset($languageArray);
			while(list($key,$value) = each($languageArray)){
					$query = "SELECT  count(*) as dataCount FROM faq_manager WHERE faqmaster_id =".$_REQUEST['faqId']." AND language_id=".$key."";
					$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);
					$coundRecs = $result[0]->dataCount;
					
					if(DB::isError($result)) 
						echo $result->getDebugInfo();
						if($coundRecs >= 1){
							$title	= $_POST['manager_question_'.$key];
							$body	= $_POST['manager_answer_'.$key];
							$status	= $_POST['faq_status'];
							$elmts	= array("manager_question" => $title,"manager_answer" => $body,"faq_status" => $status);
							$result = $objFaq->_updateFaq($_REQUEST['faqId'],$key,$elmts);
						}
						else
						{
						    $title	= $_POST['manager_question_'.$key];
							$body	= $_POST['manager_answer_'.$key];
							$result = $objFaq->_insertOneFaq($_REQUEST['faqId'],$key,$title,$body);
						}
				
			}
			header("Location:list_faqs.php?status=success_update&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']);
			
		}
	}
}	
	//if edit following will execute on loading
	if($_REQUEST['faqId'] and count($errorMsg)==0){
		//Some security check here
		$result = $objFaq->_getAllById($_REQUEST['faqId']);
		
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
                       
						  
				<form name="faqform" action="addedit_faqs.php" method="post" onSubmit="return formChecking()">
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
										
										<table height="50" class="topActions"><tr><td valign="middle" width="50"><a href="list_faqs.php?pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=$_REQUEST['keyword']?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;List   </a></td>
										<td valign="middle" class="noneAnchor"><img src="images/add.gif" border="0" alt="Add Record">&nbsp;Add </td>
										</tr></table>
									</TD><td align="right"><?php echo REQUIRED_MESSAGE;?></td>
									</TR>
									
								  </table>
                              
				  <TABLE class="paragraph2" cellSpacing=0 cellPadding=2 width="100%">
				   <TBODY> 
					 <tr>
					  <td colspan="2" align="right">&nbsp;
				     <? 
					 	$n	= 0;
					 	reset($languageArray);
						while(list($key,$val) = each($languageArray)){
							if(count($result) != 0){
								$_POST['manager_question_'.$key] = stripslashes(stripslashes(stripslashes($result[$n]['manager_question'])));
								$_POST['manager_answer_'.$key]  =  stripslashes(stripslashes(stripslashes($result[$n]['manager_answer'])));
								$_POST['faq_status']  =  $result[$n]['faq_status'];
							}
					?> 
					<fieldset ><legend><?php echo $val; ?></legend>
				   <table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr><td width="21%" align="right">
						  Question<?php echo REQUIRED;?>:&nbsp;						</td>
						<td width="79%">
							
							<textarea name="manager_question_<?=$key?>" cols="50" rows="1" ><?=$objGen->_output($_POST['manager_question_'.$key])?></textarea></td>
					</tr>
					<tr>
					  <td colspan="2" align="center">&nbsp;</td>
					  </tr>
					<tr>
						<td width="21%" align="right">Answer<?php echo REQUIRED;?>:&nbsp;</td>
						<td><textarea name="manager_answer_<?=$key?>" cols="50" rows="5" ><?=$objGen->_output($_POST['manager_answer_'.$key])?></textarea>	</td>
					</tr>
					  </table>
					  </fieldset>
					<? 
						$n++;
						}
					?>
					</td></tr>
					<tr height="30px">
						<td width="30%" align="right"> Status:&nbsp;</td>
						<td>
						<input type="radio" name="faq_status" id="active" value="1" <?php if($_POST['faq_status'] == 1) echo "checked";?>><label for="active">Active</label>
						<input type="radio" name="faq_status" id="inactive" value="0" <?php if($_POST['faq_status'] == 0) echo "checked";?>><label for="inactive">Inactive</label></td>
					</tr>
					<?php 	if(!$_REQUEST['faqId']){ 	?>
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
				<input type="hidden" name="faqId" value="<?=$_REQUEST['faqId']?>">
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
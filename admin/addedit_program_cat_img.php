<?php

/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin- category image Management
   Programmer	::> Georgina,soumya
   Date			::> 1/7/15
   
   DESCRIPTION::::>>>>
   This  code userd to add/edit category images.
   
*****************************************************************************/
	include_once('includeconfig.php');
	include("../includes/classes/class.program_image.php");	
	
	if($_REQUEST['langId'] != ""){
		$lanId = $_REQUEST['langId'];
	}
	else{
		$lanId = 1;
	}
	/*
	 Instantiating the classes.
	*/
	$langval = $lanId;
	$objImg  =  new ProgramImage($lanId);
	$objGen  =	new General();
	$heading =  "Category Image Edit";
	$errorMsg	  =	array();
	
	//if edit following will execute on loading
	if($_REQUEST['flex_id'] and count($errorMsg)==0){
		
		//Some security check here
		$result = $objImg->_getdetailsById_cat($_REQUEST['flex_id']);
		
		}
		if($_POST['update']){
	if(trim($_FILES['category_image']['name'] != "")){
		$returnValue	=	$objGen->_checkUploadImage($_FILES['category_image']['type'] );
			if(!$returnValue){ 
			$errorMsg[] = "Please upload images only";
			}
		}
		else
		{
			$errorMsg[] = "Please upload image";
		}
	}			
	
	if($_POST['update']){
		if(count($errorMsg)==0)	{
			if($_FILES['category_image']['name'] != ""){
							$fileName   = uniqid();
                            $extension  = end(explode(".",$_FILES['category_image']['name']));
						  //  $fileName  = $_POST['current_image'];
						    //~ $fileName  = explode(".",$fileName);
						    $nextUpload = $objGen->_fileUploadWithOutImageResize('category_image','../uploads/category/',$fileName);
                            $fileName = $fileName.".".$extension;
                            $_POST['category_image']    = $fileName;
                            if($_POST['category_image'] !="" && is_file("../uploads/category/".$_POST['category_image'])){
							 unlink("../uploads/category/".$_POST['current_image']);
							 }
                     
                      }else{
                            $_POST['category_image']    = $_POST['current_image'];
                       }
                       
                       $elmtsCats	= array("category_image" =>  $_POST['category_image']);
			
			
			
			$result = $objImg->_updateCategory($_REQUEST['flex_id'],$elmtsCats,$_REQUEST['langId']);
			header("Location:program_cat_img.php?status=success_update&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".stripslashes(stripslashes($_REQUEST['keyword'])));
		}
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
                       
						  
				<form name="faqform" action="addedit_program_cat_img.php" method="post"  enctype="multipart/form-data">
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
										
										<table height="50" class="topActions"><tr><td valign="middle" width="50"><a href="program_cat_img.php?pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=stripslashes(stripslashes($_REQUEST['keyword']))?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;List   </a></td>
										<td valign="middle" class="noneAnchor"><img src="images/add.gif" border="0" alt="Edit Image">&nbsp;Edit Image</td>
										</tr></table>
									</TD><td align="right"><?php echo REQUIRED_MESSAGE;?></td>
									</TR>
									
								  </table>
                 	 
					          
				  <TABLE class="paragraph2" cellSpacing=0 cellPadding=2 width="100%">
				   <TBODY> 
					 
					   	<?php
					if(count($result) != 0){
					  $currentImage   				= $result['category_image'];
					 }
					?>	
					<tr height="30px">
						<td width="40%" align="right"> Image <?php echo REQUIRED;?>:&nbsp;<br/>
<!--
						(Best view in 78px*85px)
-->
						</td>
						
                        <td>
						   <input type="file" id="category_image" name="category_image">
		          		   <input type="hidden" name="current_image" value="<?=$currentImage?>"/>
		          		  
						</td>
					</tr>
					
					 <?php if($currentImage){?>
					<tr>
						<td width="40%" align="right"></td>
						<td>

							<a href="#" title="Previous image" onClick="openNewWindow('../uploads/category/<?=$currentImage.'?='.time()?>','windowname',<?=(100);?>,<?=(50);?>)">View</a>
                        </td>
					</tr>
					<?php }?>
					<tr>
						 <td colspan="2" align="center">
							  <input type="hidden" name="flex_id" value="<?=$_REQUEST['flex_id']?>">
						 <input type="submit" name="update" onClick="return formChecking()" value="&nbsp;EDIT&nbsp;"></td>
					</tr>
					
					
					
				    </tbody>
			 	  </table>
				</TD>
                          </TR>
                          </TBODY>
                        </TABLE>
				<input type="hidden" name="langId" value="<?=$_REQUEST['langId']?>">  
			  
			   <input type="hidden" name="pageNo" value="<?=$_REQUEST['pageNo']?>">
			   <input type="hidden" name="maxrows" value="<?=$_REQUEST['maxrows']?>">
			   <input type="hidden" name="type" value="<?=$_REQUEST['type']?>">
				<input type="hidden" name="field" value="<?=$_REQUEST['field']?>">
                <input type="hidden" name="keyword" value="<?=stripslashes(stripslashes($_REQUEST['keyword']))?>">
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
<script>
	//~ function formChecking(){
		//~ 
	//~ 
		//~ var category_image=document.getElementById("category_image").value;
		//~ if(category_image==""){
			//~ alert("Upload category image");
			//~ return false;
		//~ }
		//~ 
		//~ 
	//~ }
	
function openNewWindow(URLtoOpen,windowName,width,height)
{
windowFeatures ="menubar=no,scrollbars=no,location=no,favorites=no,resizable=no,status=no,toolbar=no,directories=no";
var test = "'";
winLeft = (screen.width-width)/2;
winTop = (screen.height-(height+110))/2;
window.open(URLtoOpen,windowName,"width=" + width +",height=" + height + ",left=" + winLeft + ",top=" + winTop + test + windowFeatures + test);

}

</script>
</html>

<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-testimonials Management
   Programmer	::> Ajith
   Date			::> 04/02/2009
   
   DESCRIPTION::::>>>>
   This  code userd to add/edit testimonials.
   
*****************************************************************************/
	include_once('includeconfig.php');
	include("../includes/classes/class.program_image.php");	
	/*
	 Instantiating the classes.
	*/
	error_reporting(0);
	if($_REQUEST['langId'] != ""){
		$lanId = $_REQUEST['langId'];
	}
	else{
		$lanId = 1;
	}
    $langval = $lanId;
	$objImg  =  new ProgramImage($lanId);
	$objGen  =	new General();
	$heading =  "Program's Image Edit";
	$errorMsg	=	array();
	$currentImage="";
	
	//if edit following will execute on loading
	if($_REQUEST['program_id'] and count($errorMsg)==0){
		
		//Some security check here
		$result = $objImg->_getimagedetailsById($_REQUEST['program_id']);
		
		}
	
	if($_POST['update']){
	if(trim($_FILES['program_image']['name'] != "")){
		$returnValue	=	$objGen->_checkUploadImage($_FILES['program_image']['type'] );
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
			if($_FILES['program_image']['name'] != ""){
				            //~ echo $_POST['program_image'];
				            //~ echo $_POST['current_image'];
				            //~ exit;
                            //~ if($_POST['program_image'] !="" && is_file("../uploads/programs/newlarge/".$_POST['program_image'])){
							 //~ unlink("../uploads/programs/newlarge/".$_POST['current_image']);
							 //~ }
						    $fileName   = $_POST['current_image'];
						    //$fileName  = explode(".",$fileName);
						    //$extension  = end(explode(".",$fileName ));
						    $nextUpload = $objGen->_fileUploadWithOutImageResize_new('program_image','../uploads/programs/newlarge/',$fileName);
                      }
			header("Location:program_img.php?status=success_update&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".stripslashes(stripslashes($_REQUEST['keyword'])));
		}
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
				
				  <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr> 
                <td width="9" height="9"><img src="images/top_left.jpg" width="9" height="9"></td>
                <td width="561" height="9" background="images/top_mdl.jpg"><img src="images/top_mdl.jpg" width="9" height="9"></td>
                <td width="18" height="9"><img src="images/top_right.jpg" width="9" height="9"></td>
              </tr>
              <tr> 
                <td background="images/side1.jpg"><img src="images/side1.jpg" width="9" height="9"></td>
                <td valign="top"> 
				
				
				
				<TABLE cellSpacing=0 cellPadding=0 border=0 align="center">
                    <TR> 
                      <TD valign="top" width=564 bgColor=white> 
                       <form name="form_img" action="" method="post"  enctype="multipart/form-data">
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
										
										<table height="50" class="topActions"><tr><td valign="middle" width="50"><a href="program_img.php?pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=stripslashes(stripslashes($_REQUEST['keyword']))?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;List   </a></td>
										<td valign="middle" class="noneAnchor"><img src="images/add.gif" border="0" alt="Edit Image">&nbsp;Edit Image</td>
										</tr></table>
									</TD><td align="right"><?php echo REQUIRED_MESSAGE;?></td>
									</TR>
									
								  </table>
                 	 
					          
				  <TABLE class="paragraph2" cellSpacing=0 cellPadding=2 width="100%">
				   <TBODY> 
					 
					   	<?php
					if(count($result) != 0){
					  $currentImage   				= $result['program_image'];
					 }
					?>	
					<tr height="30px">
						<td width="44%" align="right">Image:&nbsp;<br/>
<!--
						(Best view in 78px*85px)
-->                     </td>
						<td>
						   <input type="file" id="program_image" rand=<?php echo rand(); ?> name="program_image">*
		          		   <input type="hidden" name="current_image" value="<?=$currentImage?>"/>
						</td>
					</tr>
					
					 <?php
					  $path="../uploads/programs/newlarge/".$currentImage;
					  if(($currentImage)&&(file_exists($path))){?>
					<tr>
						<td width="40%" align="right"></td>
						<td>

							<a href="#" title="Previous image" onClick="openNewWindow('../uploads/programs/newlarge/<?=$currentImage.'?='.time()?>','windowname',<?=(100);?>,<?=(50);?>)">View</a>
                        </td>
					</tr>
					<?php }?>
					<tr>
						 <td colspan="2" align="center">
							 
						 <input type="submit" name="update"  value="&nbsp;EDIT&nbsp;"></td>
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
                  
			  
			  <td background="images/side2.jpg">&nbsp;</td>
              </tr>
              <tr> 
                <td width="9" height="9"><img src="images/btm_left.jpg" width="9" height="9"></td>
                <td height="9" background="images/btm_mdl.jpg"><img src="images/btm_mdl.jpg" width="9" height="9"></td>
                <td width="18" height="9"><img src="images/btm_right.jpg" width="9" height="9"></td>
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
<script>
	//~ function formChecking(){
		//~ 
		//~ //var program_image=$("#program_image").val();
		//~ var program_image=document.getElementById("program_image").value;
		//~ if(program_image==""){
			//~ alert("Upload program image");
			//~ return false;
		//~ }
		//~ 
		//~ 
	//~ }
	function openNewWindow(URLtoOpen,windowName,width,height){
	windowFeatures ="menubar=no,scrollbars=no,location=no,favorites=no,resizable=no,status=no,toolbar=no,directories=no";
	var test = "'";
	winLeft = (screen.width-width)/2;
	winTop = (screen.height-(height+110))/2;
	window.open(URLtoOpen,windowName,"width=" + width +",height=" + height + ",left=" + winLeft + ",top=" + winTop + test + windowFeatures + test);

   }

</script>

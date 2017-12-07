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
	include("../includes/classes/class.testimonials.php");	
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
	$objTestimon = new Testimonial($lanId);
	$objGen  	 =	new General();
	
	$heading = "Testimonial";
	/*
	Take all the languages to an array.
	*/
	$languageArray=$siteLanguagesConfig;
	reset($languageArray);
	
	$brandName=$objTestimon->GetBrandName();
	
	$errorMsg	=	array();
		
	if($_POST['add']||$_POST['update']){
	
		reset($languageArray);
		if(trim($_POST['user_name'])=='')
					$errorMsg[] = "User name required";
		
				if(trim($_POST['testimonial_desc'])=='')
					$errorMsg[] = "Description required";
					
			
		if(trim($_FILES['user_image']['name'] != "")){
		$returnValue	=	$objGen->_checkUploadImage($_FILES['user_image']['type'] );
			if(!$returnValue){
			$errorMsg[] = "Please upload images only";
			}
		}			
						
		if($_POST['add'])	{
			reset($languageArray);
			
				// check whether question is already existing while adding
				$check	= $objTestimon->_isTestimonExists($objGen->_clean_data($_REQUEST['user_name']));
				if($check) 
					$errorMsg[] = "User name already exists";
			
				
		}
			
		if($_POST['update'])	{
			reset($languageArray);
			
				// check whether question is already exixting while updating
				$check = false;
				$check	= $objTestimon->_isTestimonExists($objGen->_clean_data($_REQUEST['user_name']),$_REQUEST['masterId']);
				if($check) 
						$errorMsg[] = "User name already exists";
		}
	
	if($_POST['add']){
		//check admin already exists or not
			
		if(count($errorMsg)==0)	{
		// Insert user image
		if($_FILES['user_image']['name'] != ""){
                            $fileName   = uniqid();
                            $extension  = end(explode(".",$_FILES['user_image']['name']));
                            $nextUpload = $objGen->_fileUploadWithImageResize('user_image','../uploads/testimonials/',$fileName,80,85);
                            $fileName = $fileName.".".$extension;
                            $_POST['user_image']    = $fileName;
           }
		// Insert Data
			$userName			=	$_POST['user_name'];
			$lan_name			=	$_POST['lan_name'];
			$userImage			=	$_POST['user_image'];
			$testimonialStatus	=	$_POST['testimonial_status'];
			$homepage			=	$_POST['testimonial_homepage'];
			$brandname			=implode(',',$_POST['brand_name']);

			
			$insArr				=	array('user_name' => $userName,'user_image' => $userImage,'testimonial_status' => $testimonialStatus,'testimonial_homepage' => $homepage ,'brandname' => $brandname);
			
			$nextId = $objTestimon->_insertMaster($insArr);
			reset($languageArray);
		//	while(list($key,$value) = each($languageArray)){
				$desc	= $_POST['testimonial_desc'];
				$elmts	= array("testimonial_desc" => $desc,"master_id" => $nextId,"lang_id" => $lan_name);
				
				$result =  $objTestimon->_insertTestimonial($elmts,count($languageArray));
			//}
			header("Location:list_testimonials.php?status=success_add&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']);
		}
	}
	//On clicking update button
	
	if($_POST['update']){
			
		if(count($errorMsg)==0)	{
							
			//Image updation
			if($_FILES['user_image']['name'] != ""){
                           
						   $fileName   = uniqid();
                            $extension  = end(explode(".",$_FILES['user_image']['name']));
                            $nextUpload = $objGen->_fileUploadWithImageResize('user_image','../uploads/testimonials/',$fileName,80,85);
                            $fileName = $fileName.".".$extension;
                            $_POST['user_image']    = $fileName;
							if($_POST['user_image'] !="" && is_file("../uploads/testimonials/".$_POST['current_image'])){
							unlink("../uploads/testimonials/".$_POST['current_photo']);
							}
                    }
            else{
                            $_POST['user_image']    = $_POST['current_image'];
								
                }
			// data updation
			$masterId			=	$_REQUEST['masterId'];
			$lan_name			=	$_POST['lan_name'];
			$userName			=	$_POST['user_name'];
			$userImage			=	$_POST['user_image'];
			$testimonialStatus	=	$_POST['testimonial_status'];
			$homepage			=	$_POST['testimonial_homepage'];
			$brandname			=implode(',',$_POST['brand_name']);
					
			$elmtsMaster		= array("user_name" => $userName,"user_image" => $userImage,"testimonial_status" => $testimonialStatus,"testimonial_homepage" => $homepage,"brandname" => $brandname);
			$result = $objTestimon->_updateTestimoniMaster($_REQUEST['masterId'],$elmtsMaster);
			reset($languageArray);
			//while(list($key,$value) = each($languageArray)){
					 $query = "SELECT  count(*) as dataCount FROM testimonial WHERE master_id =".$masterId." AND lang_id=".$langval.""; 
					$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);
					$coundRecs = $result[0]->dataCount;
					
					if(DB::isError($result)) 
						echo $result->getDebugInfo();
						if($coundRecs >= 1){
							$desc	= $_POST['testimonial_desc'];
							$elmts	= array("testimonial_desc" => $desc);
							$result = $objTestimon->_updateTestimoni($masterId,$langval,$elmts);
						}
						else
						{
						    $desc	= $_POST['testimonial_desc'];
							$result = $objTestimon->_insertOneTestimoni($masterId,$langval,$desc);
						}
				
		//	}
			header("Location:list_testimonials.php?status=success_update&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".stripslashes(stripslashes($_REQUEST['keyword'])));
			
		}
	}
	$currentImage			= $_POST['current_image'];
}	
	//if edit following will execute on loading
	if($_REQUEST['masterId'] and count($errorMsg)==0){
		//Some security check here
		$result = $objTestimon->_getAllTestimoniById($_REQUEST['masterId']);
		}
// for getting home page title
	$queryHome = "SELECT homepage_master.master_id AS masterId, homepage_title FROM homepage, homepage_master WHERE homepage.lang_id  = ".$_REQUEST['langId']." AND homepage.master_id = homepage_master.master_id ORDER BY homepage_title ASC" ;
	$resultHome = $GLOBALS['db']->getAll($queryHome, DB_FETCHMODE_OBJECT);

// getting image details
if($currentImage != ""){
        $imageDetails = getimagesize('../uploads/testimonials/'.$currentImage);
    }
	
?>
<HTML><HEAD><TITLE><?=$admin_title?></TITLE>
<? include_once('metadata.php');?>
<script language="JavaScript">
function openNewWindow(URLtoOpen,windowName,width,height)
{
windowFeatures ="menubar=no,scrollbars=no,location=no,favorites=no,resizable=no,status=no,toolbar=no,directories=no";
var test = "'";
winLeft = (screen.width-width)/2;
winTop = (screen.height-(height+110))/2;
window.open(URLtoOpen,windowName,"width=" + width +",height=" + height + ",left=" + winLeft + ",top=" + winTop + test + windowFeatures + test);

}
function createAjaxFn()

	{

  		var xmlHttp;

		try

  		{

  			// Firefox, Opera 8.0+, Safari

  			xmlHttp=new XMLHttpRequest();

  		}

		catch (e)

  		{

  			// Internet Explorer

  		try

    	{

    		xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");

    	}

  		catch (e)

    	{

		try

      	{

      		xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");

      	}

    	catch (e)

      	{

      		alert("Your browser does not support AJAX!");

      		return false;

      	}

    }

  }

	return xmlHttp;



}

function fun(id)
{
		xmlHttp2=createAjaxFn();
		
		if (xmlHttp2==null)
		{
		alert ("Browser does not support HTTP Request");
		return;
		}
		url	=	"getlang.php";
		url=url+"?id="+id;
		xmlHttp2.onreadystatechange=function(){
		if (xmlHttp2.readyState==4)
		{
		document.getElementById('test').innerHTML=xmlHttp2.responseText;
		}
		}
		xmlHttp2.open("GET",url,true);
		xmlHttp2.send(null);
		

	/*if(id == 1)
	{
		document.getElementById("lanname").value = 1;
	}
	else
	{
		document.getElementById("lanname").value = 2;
	}*/
}
</script>
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
                       
						  
				<form name="faqform" action="addedit_testimonials.php" method="post" onSubmit="return formChecking()" enctype="multipart/form-data">
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
										
										<table height="50" class="topActions"><tr><td valign="middle" width="50"><a href="list_testimonials.php?pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=stripslashes(stripslashes($_REQUEST['keyword']))?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;List   </a></td>
										<td valign="middle" class="noneAnchor"><img src="images/add.gif" border="0" alt="Add Record">&nbsp;Add </td>
										</tr></table>
									</TD><td align="right"><?php echo REQUIRED_MESSAGE;?></td>
									</TR>
									
								  </table>
                 	 
					          
				  <TABLE class="paragraph2" cellSpacing=0 cellPadding=2 width="100%">
				   <TBODY> 
					<?php
					$n	= 0;
					if(count($result) != 0){
					//print_r($result[0]);
						$_POST['user_name'] 			= $result[$n]['user_name'];
						$_POST['testimonial_homepage'] 	= $result[$n]['testimonial_homepage'];
						$_POST['testimonial_status'] 	= $result[$n]['testimonial_status'];
						$_POST['testimonial_desc']=$result[$n]['testimonial_desc'];
						$_POST['brand_name']=$result[$n]['brandname'];
						$currentImage   				= $result[$n]['user_image'];
					}
					?> 
					 <tr height="30px">
						<td width="40%" align="right"> User Name<?php echo REQUIRED;?>:&nbsp;</td>
						<td width="60%">
						<input type="text" name="user_name" id="user_name" value="<?=$objGen->_output($_POST['user_name']);?>"/>
						</td>
					</tr>
					<tr height="30px">
						<td width="40%" align="right"> Testimonial for:&nbsp;</td>
						<td>
						<select name="testimonial_homepage" id="testimonial_homepage" >
						<option value="0" <?php if($_POST['testimonial_homepage']== 0) echo "selected";?> >Default</option>
						<?php for($i=0;$i<count($resultHome);$i++){ ?>
						<option value="<?=$resultHome[$i]->masterId;?>" <?php if($resultHome[$i]->masterId==$_POST['testimonial_homepage']) echo "selected";?> ><?=$resultHome[$i]->homepage_title;?></option>
						<?php }?>
						</select>
						</td>
					</tr>
					<tr height="30px">
					<td width="40%" align="right"> Brand Name:&nbsp;</td>
						<td>
						<? $newarray=explode(',', $_POST['brand_name']);
									?>
						<select name="brand_name[]" id="brand_name" multiple="multiple">
						<?php foreach($brandName as $val){ ?>						
						<option value="<?=$val['brand_name'];?>" <?php if(in_array($val['brand_name'],$newarray)) echo "selected"; ?>><? echo $val['brand_name'];}?></option>
						</select>
						</td>
						</tr>
					<tr height="30px">
						<td width="40%" align="right"> User Image:&nbsp;<br/>
						(Best view in 78px*85px)
						</td>
						<td>
						<input type="file" name="user_image">
		          		<input type="hidden" name="current_image" value="<?=$currentImage?>"/>
						  <? if($currentImage != ""){?>
						  <a href="#" onClick="openNewWindow('../uploads/testimonials/<?=$currentImage?>','windowname',<?=($imageDetails[0]+100);?>,<?=($imageDetails[1]+50);?>)">View</a>
						  <? }?>
						</td>
					</tr>
					<? 
					 	
					 //	reset($languageArray);
						//while(list($key,$val) = each($languageArray)){
						//	if(count($result) != 0){
						//		$_POST['testimonial_desc_'.$key] = stripslashes(stripslashes(stripslashes($result[$n]['testimonial_desc'])));
							//	}
					?>   
<?php  if($_REQUEST["action"] != 'edit') {?><tr height="30px">
						<td width="40%" align="right"> Language:&nbsp;</td>
						<td>
						<input type="radio" name="lan_name" id="active" value="1" <?php if($langval == 1) echo "checked";?>  onClick="fun(this.value);"><label for="active">English</label>
						<input type="radio" name="lan_name" id="inactive" value="2" <?php if($langval == 2) echo "checked";?> onClick="fun(this.value);"><label for="inactive" >French</label>
                        
                         <input type="radio" name="lan_name" id="inactive" value="3" <?php if($langval == 3) echo "checked";?> onClick="fun(this.value);"><label for="inactive" >Spanish</label>
                        
                        <input type="radio" name="lan_name" id="inactive" value="4" <?php if($langval == 4) echo "checked";?> onClick="fun(this.value);"><label for="inactive" >Italian</label>
                        
                        <input type="radio" name="lan_name" id="inactive" value="5" <?php if($langval == 5) echo "checked";?> onClick="fun(this.value);"><label for="inactive" >Polish</label>
<input type="hidden" name="lanname" id="lanname" value="">
<?php  echo $_POST["lanname"];?>
</td>
					</tr> 
<?php } ?>
					 <tr>
					  <td colspan="2" align="right">&nbsp;
				     
					<fieldset ><legend><label id="test"><?php if($langval == 1) echo "English";elseif($langval == 2)

{

	echo "French";

}
elseif($langval == 3)

{

	echo "Spanish";

}
elseif($langval == 4)

{

	echo "Italian";

}
elseif($langval == 5)

{

	echo "Polish";

}?></label></legend>
				   <table width="100%" border="0" cellspacing="0" cellpadding="0">
						
					<tr>
					  <td colspan="2" align="center">&nbsp;</td>
					  </tr>
					<tr>
						<td width="21%" align="right">Description<?php echo REQUIRED;?>:&nbsp;</td>
						<td><textarea name="testimonial_desc" cols="50" rows="5" ><?=$objGen->_output($_POST['testimonial_desc'])?></textarea>	</td>
					</tr>
					  </table>
					  </fieldset>
					<? 
					//	$n++;
					//	}
					?>
					</td></tr>
					<tr height="30px">
						<td colspan="2" align="left"> Do you want to display this on jiwok site?:&nbsp;
						<input type="radio" name="testimonial_status" id="active" value="1" <?php if($_POST['testimonial_status'] == 1) echo "checked";?>>
						<label for="active">Yes</label>
						<input type="radio" name="testimonial_status" id="inactive" value="0" <?php if($_POST['testimonial_status'] == 0) echo "checked";?>>
						<label for="inactive">No</label></td>
					</tr>
					<?php 	if(!$_REQUEST['masterId']){ 	?>
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
				<input type="hidden" name="masterId" value="<?=$_REQUEST['masterId']?>">
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
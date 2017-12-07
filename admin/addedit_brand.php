<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-Brand Management
   Programmer	::> jasmin
   Date			::> 05/04/2010
   
   DESCRIPTION::::>>>>
   This  code userd to add/edit brands.
   
*****************************************************************************/
	include_once('includeconfig.php');
	include_once('../includes/classes/class.Languages.php');
	include("../includes/classes/class.brand.php");
	include_once('../mail_gift.php');
	include('./movedir.php');
	
	/*
	 Instantiating the classes.
	*/
	if($_REQUEST['langId'] != ""){
		$lanId = $_REQUEST['langId'];
	}
	else{
		$lanId = 1;
	}
	//$parObj 		=   new Contents('userreg2.php');
	$lanObj 		= 	new Language();
	$objTesti	 = new BrandVersion();
	$objGen  	 =	new General();
	
	$heading = "Brand";
	/*
	Take all the languages to an array.
	*/
	//collecting data from the xml for the static contents
		
	
	$errorMsg	=	array();
		
	if($_POST['add']||$_POST['update']){
	
		
		if(trim($_POST['brand_name'])=='')
					$errorMsg[] = "Brand name required";
		/*if(trim($_POST['re_id'])=='')
					$errorMsg[] = "Reseller Id required";*/
		if(trim($_POST['brand_url'])=='')
					$errorMsg[] = "URL of brand required";
		if(trim($_POST['brand_url'])!='')
			        {
						$urlregex = "^(https?|ftp)\:\/\/([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)*(\:[0-9]{2,5})?(\/([a-z0-9+\$_-]\.?)+)*\/?(\?[a-z+&\$_.-][a-z0-9;:@/&%=+\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?\$";
if (eregi($urlregex, trim($_POST['brand_url']))){}  else {$errorMsg[] = "Enter a valid URL";}  
		  }	
		  if(trim($_POST['brand_email'])==''){
					$errorMsg[] = "Email required";}
			else
			{			
			$resultemail=ereg("^[^@ ]+@[^@ ]+\.[^@ ]+$",trim($_POST['brand_email']),$trashed);
				if(!$resultemail){
				$errorMsg[] = "Enter a valid E-mail Address";
				}			
			}
		  if(trim($_POST['description'])=='')
					$errorMsg[] = "Short Description of brand required";			
		if($_POST['add'])	{
			//reset($languageArray);
			    //echo  "12222";
				// check whether brand is already existing while adding
				 $check	= $objTesti->_isResExists($objGen->_clean_data($_REQUEST['brand_name']));
				if($check) {
					$errorMsg[] = "Brand Name already exists";}
		}
			
		if($_POST['update'])	{
			//reset($languageArray);
			
				// check whether brand is already exixting while updating
				$check	= $objTesti->_isResExists($objGen->_clean_data($_REQUEST['brand_name']),$_REQUEST['masterId']);
				if($check) {
						$errorMsg[] = "Brand Name already exists";}
		}
	
	if($_POST['add']){
		//check admin already exists or not
			//echo "3";
		if(count($errorMsg)==0)	{
		// Insert Data
			$userName	=	$_POST['brand_name'];
			$email	=	$_POST['brand_email'];
			$pass	=	time();
			$passenc=base64_encode($pass);
			$url	=	$_POST['brand_url'];
			$status	=	$_POST['testimonial_status'];
			$description	=	$_POST['description'];
			$unique=false;
				//echo "4";	
			$insArr				=	array('brand_name' => $userName,'brand_email' => $email,'brand_password' => $passenc,'brand_url' => $url,'brand_status' => $status,'brand_description' => $description); 
			$src='../templates/default';
			$dst="../templates/".$userName;
			recurse_move($src,$dst);
			$nextId = $objTesti->_insertMaster($insArr);
			//echo "5";
			/////////////////////////////
			
			
			$subject ="Brand Account";
			$siteUrl = "http://www.jiwok.com/Templatefiles/".$userName."/";
			$msg = "Bonjour Jiwok

Bienvenue et merci d'avoir créé un compte de revendeur dans Jiwok.
Je rappelle à vos informations pour vous connecter à votre compte Jiwok

Votre identité: {name}
Votre Mot de passe: {pass}
URL: {url}

À bientôt
Denis Jiwok";
							$msg = nl2br($msg);
							$msg=str_replace('{name}',$userName,$msg);
							$msg=str_replace('{pass}',$pass,$msg);
						    $msg=str_replace('{url}',$siteUrl,$msg);
						$unid = md5(uniqid(time()));
								$headers = "From: Jiwok <info@jiwok.com>\r\n";
								$headers .= "Reply-To: info@jiwok.com \r\n";
								$headers .= "MIME-Version: 1.0\r\n";
								$headers .= "Content-Type: multipart/mixed; boundary=\"".$unid."\"\r\n\r\n";
								$headers .= "This is a multi-part message in MIME format.\r\n";
								$headers .= "--".$unid."\r\n";
								$headers .= "Content-type:text/html; charset=iso-8859-1\r\n";
								$headers .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
								$headers .= $msg."\r\n\r\n";
						@mail($email,$subject,"",$headers);
						//////////////////////////////////////
			//echo  "here";
			header("Location:list_brands.php?status=success_add&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']);
		}
	}
	//On clicking update button
	
	if($_POST['update']){
			
		if(count($errorMsg)==0)	{
						
                
			// data updation
			$masterId			=	$_REQUEST['masterId'];
			$userName			=	$_POST['brand_name'];
			$email	=	$_POST['brand_email'];
			$url	=	$_POST['brand_url'];
			$status	=	$_POST['testimonial_status'];
			$description	=	$_POST['description'];
					
			$elmtsMaster		= array("brand_name" => $userName,'brand_email' => $email,'brand_url' => $url,'brand_status' => $status,'brand_description' => $description);
			$result = $objTesti->_updateResMaster($_REQUEST['masterId'],$elmtsMaster);
			//reset($languageArray);	
			header("Location:list_brands.php?status=success_update&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".stripslashes(stripslashes($_REQUEST['keyword'])));
		}
}	
}
	//if edit following will execute on loading
	if($_REQUEST['masterId'] and count($errorMsg)==0){
		//Some security check here
		$result = $objTesti->_getAllById($_REQUEST['masterId']);
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
          <TD valign="top" colspan="2"><!---Contents Start Here----->
		  
		  
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
                       
						  
				<form name="faqform" action="addedit_brand.php" method="post" onSubmit="return formChecking()" enctype="multipart/form-data">
						  <table cellSpacing=0 cellPadding=4 width=561 border=0>
                          <tbody> 
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
										<table height="50" class="topActions"><tr><td valign="middle" width="50"><a href="list_brands.php?pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=stripslashes(stripslashes($_REQUEST['keyword']))?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;List   </a></td>
										<td valign="middle" class="noneAnchor"><img src="images/add.gif" border="0" alt="Add Record">&nbsp;Add </td>
										</tr></table>
									</td><td align="right"><?php echo REQUIRED_MESSAGE;?></td>
									</tr>
								  </table>
                 	    
						
						
						
						
						
					          
				  
				</td>
                          </tr>
						  <tr><td>
						  
						 <table class="paragraph2" cellSpacing=0 cellPadding=2 width="100%">
				   <tbody> 
					<?php
					$n	= 0;
					if(count($result) != 0){
						$_POST['brand_name'] 			= $result[$n]['brand_name'];
						$_POST['brand_email'] 	= $result[$n]['brand_email'];
						$_POST['brand_description'] = $result[$n]['brand_description'];
						$_POST['brand_url']    				= $result[$n]['brand_url'];
						$_POST['testimonial_status'] 	= $result[$n]['brand_status'];
						$_POST['description']    				= $result[$n]['brand_description'];
					}
					?> 
					 <tr height="30px">
						<td width="30%" align="right"> Brand Name<?php echo REQUIRED;?>:&nbsp;</td>
						<td>
						<input type="text" name="brand_name" id="brand_name" value="<?=$objGen->_output($_POST['brand_name']);?>"/>
						</td>
					</tr>
					<tr height="30px">
						<td width="30%" align="right"> URL<?php echo REQUIRED;?>:&nbsp;<br/>
						</td>
						<td>
						<input type="text" name="brand_url" id="brand_url" value="<?=$objGen->_output($_POST['brand_url']);?>"/>
						</td>
					</tr>
					<tr height="30px">
						<td width="30%" align="right"> Email<?php echo REQUIRED;?>:&nbsp;<br/>
						</td>
						<td>
						<input type="text" name="brand_email" id="brand_email" value="<?=$objGen->_output($_POST['brand_email']);?>"/>
						</td>
					</tr>
					 <tr height="30px">
						<td width="30%" align="right"> Description<?php echo REQUIRED;?>:&nbsp;<br/>
						</td>
						<td>
						<textarea name="description"><?=$objGen->_output($_POST['description']);?></textarea>
						</td>
					</tr>
					<tr height="30px">
						<td width="30%" align="right"> Status:&nbsp;</td>
						<td>
						<input type="radio" name="testimonial_status" id="active" value="1" <?php if($_POST['testimonial_status'] == 1) echo "checked";?>><label for="active">Active</label>
						<input type="radio" name="testimonial_status" id="inactive" value="0" <?php if($_POST['testimonial_status'] == 0) echo "checked";?>><label for="inactive">Inactive</label></td>
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
						
						  
						  
						  </td></tr>
                          </tbody>
                        </table>
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
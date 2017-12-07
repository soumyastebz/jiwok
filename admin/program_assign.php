<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-Brand Program ASSIGNMENT
   Programmer	::> jasmin
   Date			::> 05/04/2010
   
   DESCRIPTION::::>>>>
   This  code userd to assign programs for brands.
   
*****************************************************************************/
	include_once('includeconfig.php');
	include_once('../includes/classes/class.Languages.php');
	include("../includes/classes/class.brand.php");
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
	$brandObj	    =   new BrandVersion();
	$objGen  	    =	new General();
	
	$heading = "Program Assignment";
	
	if($_POST['update'])
	{
		//echo "here";
		//print_r($_POST['pid']);
		$idlist="";
		$idArray=$_POST['pid'];
		$catIdArray=$_POST['cat'];
		$subIdArray=$_POST['sub'];
		//print_r($idArray);
		foreach($idArray as $value)
		{
			$idlist.=$value.",";
		}
		foreach($catIdArray as $value1)
		{
			$catidlist.=$value1.",";
		}
		foreach($subIdArray as $value2)
		{
			$subidlist.=$value2.",";
		}
		
		if($idlist)$idlist=substr($idlist,0,-1);
		if($catidlist)$catidlist=substr($catidlist,0,-1);
		if($subidlist)$subidlist=substr($subidlist,0,-1);
	    $checkfalg=$brandObj->isRowProgram($_REQUEST['masterId']);
		//echo $idlist;
		if($checkfalg==0)
		{
			$brandObj->insertPrograms($_REQUEST['masterId'],$idlist,$subidlist,$catidlist);
			$confMsg="Assigned programs Successfully";
		}
		else
		{
			$brandObj->updatePrograms($_REQUEST['masterId'],$idlist,$subidlist,$catidlist);
			$confMsg="Assigned programs Successfully";
		}
	}
	$errorMsg	=	array();
    $checkfalg=$brandObj->isRowProgram($_REQUEST['masterId']);
	if($checkfalg) ////////if its edit , it will list the details of already submitted values as checked
	  {
	  	$brandPrograms=$brandObj->selectBrandProgms($_REQUEST['masterId']);
		//echo $brandPrograms['program_id'];
		$brandProgramlist=explode(",",$brandPrograms['program_id']);
		//echo "<pre>";print_r($brandProgramlist);echo "</pre>";
		$brandSubList=$brandObj->selectBrandSubCat($_REQUEST['masterId']);
		foreach($brandSubList as $val)
		{
			$brandSubCatList[]=$val['flex_id'];
		}
		$brandCatArray=$brandObj->selectBrandCat($_REQUEST['masterId']);
		foreach($brandCatArray as $val)
		{
			$brandCatList[]=$val['flex_id'];
		}
		//echo "<pre>";print_r($brandCatList);echo "</pre>";
	  }
	$treeval="dd.add (0, -1, 'program tree structure','');";
	$dat2=$brandObj->selectCategory();
	if($dat2)
	{ // echo count($dat2);
		foreach($dat2 as $val)
		{  //echo "<br>****";
		   //echo $val['flex_id']."<br>";
		    if($checkfalg){
				 	if(array_search($val['flex_id'],$brandCatList)!==false){$checked="checked=\'checked\' ";//echo $val['flex_id'];
					}
					else $checked="";
					}
			else
			       $checked="";
			 $treeval.="dd.add ('".$val['flex_id']."','".$val['parent_id']."', '<input type=\'checkbox\' alt=\'check\' name=\'cat[]\' ".$checked." value=\'".$val['flex_id']."\' id=\'0\' onClick=\'checkStatus(".$val['flex_id'].",this)\'> ".addslashes($val['category_name'])."');";
		}
	}
	//echo "<pre>".$treeval."</pre>"; ".if(in_array($val['program_id'],$brandProgramlist)){."checked=\'checked\' ".}."
	$dat1=$brandObj->selectSubcategory();
	if($dat1)
	{//echo count($dat1);
		foreach($dat1 as $val)
		{ 
			 if($checkfalg){
				 	if(array_search($val['flex_id'],$brandSubCatList)!==false){$checked="checked=\'checked\' ";//echo $val['flex_id'];
					}
					else $checked="";
					}
			else
			       $checked="";
			$treeval.="dd.add ('".$val['flex_id']."','".$val['parent_id']."', '<input type=\'checkbox\' alt=\'check\' name=\'sub[]\' ".$checked." value=\'".$val['flex_id']."\' id=\'".$val['parent_id']."\' onClick=\'checkStatus(".$val['flex_id'].",this)\'> ".addslashes($val['category_name'])."');";
		}
	}
	$dat=$brandObj->selectPrograms();
	if($dat)
	{//echo count($dat)."<br>";
		 //echo $datval.=$val['program_category_flex_id']."**";
		     foreach($dat as $val)
		       { 
				 $cat_val_array=explode(',',$val['program_category_flex_id']);
				 if(count($cat_val_array)>0)
				 {
				 	foreach($cat_val_array as $val_cat)
					{ //echo "<br>".$val['program_id']."-".$val_cat;
					 if($checkfalg){
				      //echo $val['program_id'];
				      $catcheck=$brandObj->checkCatSelect($val_cat,$_REQUEST['masterId']);
				   //$catcheck=1;
				 	if((array_search($val['program_id'],$brandProgramlist)!==false) && ($catcheck>0)){$checked="checked=\'checked\' ";
					//echo $val['program_id'];
					}
					else $checked="";
					}
				
					
					$treeval.="dd.add ('1.".$val['program_id']."','".$val_cat."', '<input type=\'checkbox\' alt=\'check\' name=\'pid[]\' ".$checked." value=\'".$val['program_id']."\' id=\'".$val_cat."\' onClick=\'checkStatus(1.".$val['program_id'].",this)\'> ".addslashes($val['program_title'])."');";
				    }
				}
			   }
	}
	//if edit following will execute on loading
	if($_REQUEST['masterId'] and count($errorMsg)==0){
		//Some security check here
		$result = $brandObj->_getRowById($_REQUEST['masterId']);
		}
?>
<HTML><HEAD><TITLE><?=$admin_title?></TITLE>
<script type="text/javascript" src="./dtree/dtree.js"></script>
<? include_once('metadata.php');?>
<link rel="StyleSheet" href="./dtree/dtree.css" type="text/css" />
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
                       
						  
				<form name="faqform" action="program_assign.php" method="post" onSubmit="return formChecking()" enctype="multipart/form-data">
						  <table cellSpacing=0 cellPadding=4 width=561 border=0>
                          <tbody> 
                          <TR> 
                            <TD valign="top">
								   <table class="paragraph2" cellspacing=0 cellpadding=0 width=553 border=0>
								  <tr>
										<td colspan="2" height="50" align="center" valign="bottom" class="sectionHeading"><?php echo $result." :: "; ?><?=$heading;?></td>
									</tr>
									<?php 
										if($errorMsg){ ?>
									<tr>
										<td colspan="2" align="center"><? print $objGen->_adminmessage_box($errorMsg,$mode='error',$url='normal') ?></td>
									</tr>
									<?php } ?>
									<?php if($confMsg != ""){?>
										<tr> <td colspan="2" align="center" class="successAlert"><?=$confMsg?></td> </tr>
									<?php }?>
				
									<TR> 
									<TD align="left">
										<table height="50" class="topActions"><tr><td valign="middle" width="50"><a href="list_brands.php?pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=stripslashes(stripslashes($_REQUEST['keyword']))?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;List   </a></td>
										<td valign="middle" class="noneAnchor"></td>
										</tr></table>
									</td><td align="right"><?php //echo REQUIRED_MESSAGE;?></td>
									</tr>
								  </table>
				</td>
                          </tr>
						  <tr><td colspan="2" width="260px">
						  
						 
						 <p> <a href="javascript: dd.openAll();"> Expand All</a>
|<a href="javascript:dd.closeAll();"> Hide All </a> </p>
<p><a href="javascript: checkAllPrograms();">Check All</a> | <a href="javascript: UncheckAllPrograms();">Uncheck All</a></p>
<script type="text/javascript">
dd = new dTree('dd');
<?php echo $treeval;?>
document.write(dd);
</script> </td></tr>
						  <tr><td colspan="2" align="center"><input type="button" onClick="window.location='list_brands.php?pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=stripslashes(stripslashes($_REQUEST['keyword']))?>'" name="cancel" value="&nbsp;Cancel&nbsp;">&nbsp;&nbsp;&nbsp;<input type="submit" name="update" value="&nbsp;Submit&nbsp;"></td></tr>
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
<?php

/**************************************************************************** 

   Project Name	::> Jiwok 

   Module 		::> Admin-Limit Category 

   Programmer	::> Vinitha

   Date			::> 07/05/2010

   DESCRIPTION::::>>>>

   This  code userd to  limit the categories/subcategories for jiwok .

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

	$lanObj 		= 	new Language();

	$brandObj	    =   new BrandVersion();

	$objGen  	    =	new General();

	

	$version=$_REQUEST['version'];

	if($version=='eng'){$language='1';$lanName='English';} else if($version=='polish'){$language='5';$lanName='Polish';} else{$language='2';$lanName='French';}

	$heading = $lanName." : Limit categories and sub-categories";

	if($_POST['update'])

	{

		$catArray=$_POST['cat'];
		
		$subcatArray=$_POST['sub'];

		$okay=$brandObj->limitSubcategory($subcatArray,$version);

		$okay=$brandObj->limitCategory($catArray,$version);

		if($okay)

		{

			$confMsg="Categories limited successfully";

		}

		

	}

	$errorMsg	=	array();

    	$treeval="dd.add (0, -1, 'Category tree structure','');";

	if($version=='eng'){$status="english_status";}

	else {$status="status";}

	$dat2=$brandObj->selectCategory($language);
	
	if($dat2)

	{ 

		foreach($dat2 as $val)

		{ 

			    $checkflag=$val[$status];

		  if($checkflag==1){

				 	$checked="checked=\'checked\' ";//echo $val['flex_id'];

					}

					else 

					{

					$checked="";

					}

			 $treeval.="dd.add ('".$val['flex_id']."','".$val['parent_id']."', '<input type=\'checkbox\' alt=\'check\' name=\'cat[]\' ".$checked." value=\'".$val['flex_id']."\' id=\'0\' onClick=\'checkStatus(".$val['flex_id'].",this)\'> ".addslashes($val['category_name'])."');";

		}

	}

	

	$dat1=$brandObj->selectSubcategory($language);

	if($dat1)

	{//echo count($dat1);

		foreach($dat1 as $val)

		{ 

		

			    $checkflag=$val[$status];

			 if($checkflag==1){

				 	$checked="checked=\'checked\' ";

					}

					else

					{  $checked="";

					}

			

			$treeval.="dd.add ('".$val['flex_id']."','".$val['parent_id']."', '<input type=\'checkbox\' alt=\'check\' name=\'sub[]\' ".$checked." value=\'".$val['flex_id']."\' id=\'".$val['parent_id']."\' onClick=\'checkStatus(".$val['flex_id'].",this)\'> ".addslashes($val['category_name'])."');";

		}

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

                       

						  

				<form name="faqform" action="list_limitcategories.php<?php if($_REQUEST['version']=='eng'){?>?version=eng<?php }if ($_REQUEST['version']=='polish'){?>?version=polish<?php }?>" method="post" onSubmit="return formChecking()" enctype="multipart/form-data">

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

										<td colspan="2" align="center"><? print $objGen->_adminmessage_box($errorMsg,$mode='error',$url='normal') ?></td>

									</tr>

									<?php } ?>

									<?php if($confMsg != ""){?>

										<tr> <td colspan="2" align="center" class="successAlert"><?=$confMsg?></td> </tr>

									<?php }?>

				

									<TR> 

									<TD align="left">

										<br/>

									<!--</td><td align="right"> <?php /*if($_REQUEST['version']!='eng'){*/?><a href="list_limitcategories.php?version=eng">English version</a><?php /*} else{*/?><a href="list_limitcategories.php">French version</a><?php// }?> >>></td>-->
</td><td align="right"> <?php if($_REQUEST['version']==''){?>&nbsp;&nbsp;&nbsp;<a href="list_limitcategories.php?version=eng">English version</a>>>><br/><a href="list_limitcategories.php?version=polish">Polish version</a><?php }else if($_REQUEST['version']=='eng'){?><a href="list_limitcategories.php">French version</a>>>><br/><a href="list_limitcategories.php?version=polish">Polish version</a><?php } else{?><a href="list_limitcategories.php">French version</a>>>>&nbsp;&nbsp;<br/><a href="list_limitcategories.php?version=eng">English version</a><?php } ?> >>></td>
									</tr>

									

								  </table>

				</td>

                          </tr>

						  

						  <tr><td>To inactivate the categories, uncheck the corresponding boxes </td> </tr>

						  <tr><td colspan="2" width="260px">

						  

						 

						 <p> <a href="javascript: dd.openAll();"> Expand All</a>

						 

|<a href="javascript:dd.closeAll();"> Hide All </a> </p>

<p><a href="javascript: checkAllPrograms();">Check All</a> | <a href="javascript: UncheckAllPrograms();">Uncheck All</a></p>

<script type="text/javascript">

dd = new dTree('dd');

<?php echo $treeval; ?>

document.write(dd);

</script> </td></tr>

						  <tr><td colspan="2" align="center"><input type="button" onClick="window.location='list_limitcategories.php'" name="cancel" value="&nbsp;Cancel&nbsp;">&nbsp;&nbsp;&nbsp;<input type="submit" name="update" value="&nbsp;Limit&nbsp;"></td></tr>

                          </tbody>

                        </table>

				<input type="hidden" name="langId" value="<?=$_REQUEST['langId']?>">  

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
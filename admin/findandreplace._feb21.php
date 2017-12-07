<?php
/**************************************************************************** 
   Project Name	::> Jiwok
   Module 		::> Color Workout Generation
   Programmer	::> Dijo
   Date			::> 25.11.2011
   
   DESCRIPTION::::>>>>
   This  code used for Synchronize the workout details and program details table..
*****************************************************************************/
	error_reporting(1);
	include_once('includeconfig.php');
	include_once('../includes/classes/class.color.php');
	include_once('../includes/classes/class.member.php');
	$objColor		= new Color();
	$msg	= $msg1		= "";
	if(isset($_POST['replace']))
	{
		$field				=	$_POST['program_field'];
		$find				=	$_POST['text_to_find'];
		$replace			= 	$_POST['text_to_replace'];
		$prgm_flex_id		=	$_POST['prgm_flex_id'];
		if($prgm_flex_id	==	"")
				$sql_where	= 	"";
		else
				$sql_where	= 	" where flex_id ='".$prgm_flex_id."'";
		$tbl				=	"program_detail_color";
		$flag				=	$objColor->_findAndReplace($tbl,$field,$find,$replace,$sql_where);
		if($flag)
			$msg	=	"Successfully Replaced";
		else
			$msg	=	"Replacement Failed";
	}
	if(isset($_POST['replace1']))
	{
		$field		=	$_POST['workout_field'];
		$find		=	$_POST['text_to_find1'];
		$replace	= 	$_POST['text_to_replace1'];
		
		$wrk_out_flex_id	=	$_POST['wrk_out_flex_id'];
		if($wrk_out_flex_id	== "")
				$sql_where	= "";
		else
				$sql_where	= " where workout_flex_id='".$wrk_out_flex_id."'";
				
		$tbl		=	"workout_details_color";
		$flag		=	$objColor->_findAndReplace($tbl,$field,$find,$replace,$sql_where);
		if($flag)
			$msg1	=	"Successfully Replaced";
		else
			$msg1	=	"Replacement Failed";
	}
?>
<html><head><title><?=$admin_title?></title>
<? include_once('metadata.php');?>
<script type="text/javascript">
	function validate()
	{
		if(document.getElementById('program_field').value == '')
		{
			alert('Please Select Field');
			document.getElementById('program_field').focus();
			return false;
		}
		else if(document.getElementById('text_to_find').value == '')
		{
			alert('Please Enter Text for Search');
			document.getElementById('text_to_find').focus();
			return false;
		}
		else if(document.getElementById('text_to_replace').value == '')
		{
			alert('Please Enter Text for Replace');
			document.getElementById('text_to_replace').focus();
			return false;
		}
		return true;
	}
	function validate1()
	{
		if(document.getElementById('workout_field').value == '')
		{
			alert('Please Select Field');
			document.getElementById('workout_field').focus();
			return false;
		}
		else if(document.getElementById('text_to_find1').value == '')
		{
			alert('Please Enter Text for Search');
			document.getElementById('text_to_find1').focus();
			return false;
		}
		else if(document.getElementById('text_to_replace1').value == '')
		{
			alert('Please Enter Text for Replace');
			document.getElementById('text_to_replace1').focus();
			return false;
		}
		return true;
	}
</script>
<body  class="bodyStyle" onLoad="check()">
<table cellSpacing=0 cellPadding=0 width="779" align="center" border="1px" bordercolor="#E6E6E6">
  <tr>
    <td vAlign=top align=left bgColor=#ffffff><? include("header.php");?></td>
  </tr>
  <tr height="5">
    <td vAlign=top align=left class="topBarColor">&nbsp;</td>
  </tr>
  <tr>
    <td vAlign="top" align="left" height="340"> 
      <table cellSpacing=0 cellPadding=0 width="100%" border=0 class="middleTableBg">
        <tr> 
          <td vAlign=top align=left width="175" rowSpan="2"> 
            <table cellSpacing="0" cellPadding="0" width="175" border=0>
              <tr> 
                <td valign="top">
				 <table cellSpacing=0 cellPadding=2 width=175  border=0>
                    <tbody> 
                    <tr valign="top"> 
                      <td valign="top"><? include ('leftmenu.php');?></td>
                    </tr>
                    
                    </tbody> 
                  </table>
				</td>
              </tr>
            </table>
          </td>
          <td vAlign=top align=left width=0></td>
         
        </tr>
        <tr> 
          <td valign="top" width="1067"><!---Contents Start Here----->
		  
		  
            <table cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
              <tr> 
                <td class=smalltext width="98%" valign="top">
				
				  <table width="75%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr> 
                <td width="10" height="9"><img src="images/top_left.jpg" width="9" height="9"></td>
                <td width="543" height="9" background="images/top_mdl.jpg"><img src="images/top_mdl.jpg" width="9" height="9"></td>
                <td width="11" height="9"><img src="images/top_right.jpg" width="9" height="9"></td>
              </tr>
              <tr> 
                <td background="images/side1.jpg"><img src="images/side1.jpg" width="9" height="9"></td>
                <td valign="top"> 
				
				
				
				<table cellSpacing=0 cellPadding=0 border=0 align="center">
                    <tr> 
                      <td vAlign=top width=564 bgColor=white> 
                       
			  	 <form name="mailform" action="findandreplace.php" method="post" onSubmit="return validate()">
                      
				  <table class="paragraph2" cellspacing=0 cellpadding=0 width=553 border=0 >
				  <tr>
						<td height="50" align="center" valign="bottom" class="sectionHeading"></td>
					</tr>
					
					<tr>
						<td align="center"><h3><? echo $msg; ?></h3></td>
					</tr>
					
					
					<tr> 
					<td align="left"><fieldset>
                            <legend>Find and Replace Program Details Color Table</legend>
                             <table cellSpacing=0 cellPadding=0 border=0 align="center">
                      <tr>
                             <td vAlign=top align=right width=21>&nbsp;</td>
                             <td width=10>&nbsp;</td>
                      </tr>
                      
                     <!-- Contents --->
                     <tr>
                             <td vAlign=top align=left width=200>Program Flex Id</td>
                             <td width=200>
                             	<input type="text" id="prgm_flex_id" name="prgm_flex_id" width="400">
                             </td>
                      </tr>
                      <tr>
                             <td vAlign=top align=right width=21>&nbsp;</td>
                             <td width=10>&nbsp;</td>
                      </tr>
                      <tr>
                             <td vAlign=top align=left width=200>Select Field *</td>
                             <td width=200>
                             	<select name="program_field" id="program_field">
                                	<option value="">Select One</option>
                                	<option value="program_title">Program Title</option>
                                    <option value="program_desc">Program Description</option>
                                    <option value="program_target">Program Target</option>
                                    <option value="program_provide">Program Provide</option>
                                </select>
                             </td>
                      </tr>
                      <tr>
                             <td vAlign=top align=right width=21>&nbsp;</td>
                             <td width=10>&nbsp;</td>
                      </tr>
                       <tr>
                             <td vAlign=top align=left width=200>Text To Find*</td>
                             <td width=200>
                             	<input type="text" id="text_to_find" name="text_to_find" width="400">
                             </td>
                      </tr>
                       <tr>
                             <td vAlign=top align=right width=21>&nbsp;</td>
                             <td width=10>&nbsp;</td>
                      </tr>                                             
                       <tr>
                             <td vAlign=top align=left width=200>Text To Replace*</td>
                             <td width=200>
                             	<input type="text" id="text_to_replace" name="text_to_replace" >
                             </td>
                      </tr>
                       <tr>
                             <td vAlign=top align=right width=21>&nbsp;</td>
                             <td width=10>&nbsp;</td>
                      </tr>
                      <tr>
                             <td vAlign=top align=left width=200>&nbsp;</td>
                             <td width=200>
                             	<input type="submit" name="replace" value="Replace" >
                             </td>
                      </tr>
                     <!-- Contents --->
                      
                      
                      <tr>
                                        <td width=21>&nbsp;</td>
                                        <td width=564 background="images/box-bttmtrim.gif">&nbsp;</td>                <td width=10>&nbsp;</td>
                      </tr>
                    </table></fieldset></td>
					</tr>
					
				  </table><br>
			  	  </form>
                      </td>
                    </tr>
                  </table>
				  
				  <table cellSpacing=0 cellPadding=0 border=0 align="center">
                    <tr> 
                      <td vAlign=top width=564 bgColor=white> 
                       
			  	 <form name="mailform" action="findandreplace.php" method="post" onSubmit="return validate1()">
                      
				  <table class="paragraph2" cellspacing=0 cellpadding=0 width=553 border=0 >
				  <tr>
						<td height="50" align="center" valign="bottom" class="sectionHeading"></td>
					</tr>
					
					<tr>
						<td align="center"><h3><? echo $msg1; ?></h3></td>
					</tr>
					
					
					<tr> 
					<td align="left"><fieldset>
                            <legend>Find and Replace Workout Details Color Table</legend>
                             <table cellSpacing=0 cellPadding=0 border=0 align="center">
                      <tr>
                             <td vAlign=top align=right width=21>&nbsp;</td>
                             <td width=10>&nbsp;</td>
                      </tr>
                      
                     <!-- Contents --->
                       <tr>
                             <td vAlign=top align=left width=200>Workout Flex Id</td>
                             <td width=200>
                             	<input type="text" id="wrk_out_flex_id" name="wrk_out_flex_id" width="400">
                             </td>
                      </tr>
                     
                      <tr>
                             <td vAlign=top align=right width=21>&nbsp;</td>
                             <td width=10>&nbsp;</td>
                      </tr>
                      <tr>
                             <td vAlign=top align=left width=200>Select Field *</td>
                             <td width=200>
                             	<select name="workout_field" id="workout_field">
                                	<option value="">Select One</option>
                                    <option value="workout_title">Workout Title</option>
                                	<option value="workout_desc">Workout Description</option>
                                    <option value="workout_target">Workout Traget</option>
                                    <option value="workout_provide">Workout Provide</option>
                                </select>
                             </td>
                      </tr>
                      <tr>
                             <td vAlign=top align=right width=21>&nbsp;</td>
                             <td width=10>&nbsp;</td>
                      </tr>
                       <tr>
                             <td vAlign=top align=left width=200>Text To Find*</td>
                             <td width=200>
                             	<input type="text" id="text_to_find1" name="text_to_find1" width="400">
                             </td>
                      </tr>
                       <tr>
                             <td vAlign=top align=right width=21>&nbsp;</td>
                             <td width=10>&nbsp;</td>
                      </tr>                                             
                       <tr>
                             <td vAlign=top align=left width=200>Text To Replace*</td>
                             <td width=200>
                             	<input type="text" id="text_to_replace1" name="text_to_replace1" >
                             </td>
                      </tr>
                       <tr>
                             <td vAlign=top align=right width=21>&nbsp;</td>
                             <td width=10>&nbsp;</td>
                      </tr>
                      <tr>
                             <td vAlign=top align=left width=200>&nbsp;</td>
                             <td width=200>
                             	<input type="submit" name="replace1" value="Replace" >
                             </td>
                      </tr>
                     <!-- Contents --->
                      
                      
                      <tr>
                                        <td width=21>&nbsp;</td>
                                        <td width=564 background="images/box-bttmtrim.gif">&nbsp;</td>                <td width=10>&nbsp;</td>
                      </tr>
                    </table></fieldset></td>
					</tr>
					
				  </table><br>
			  	  </form>
                      </td>
                    </tr>
                  </table>
				  
				  
				  </td>
                <td background="images/side2.jpg">&nbsp;</td>
              </tr>
              <tr> 
                <td width="10" height="9"><img src="images/btm_left.jpg" width="9" height="9"></td>
                <td height="9" background="images/btm_mdl.jpg"><img src="images/btm_mdl.jpg" width="9" height="9"></td>
                <td width="11" height="9"><img src="images/btm_right.jpg" width="9" height="9"></td>
              </tr>
            </table>

                </td>
              </tr>
            </table>

          </td>
        </tr>
		 <tr height="2">
    <td vAlign=top align=left class="topBarColor" colspan="3">&nbsp;</td>
  </tr>
      </table>
        <?php include_once("footer.php");?>
</body>
</html>

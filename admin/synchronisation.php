<?php
/**************************************************************************** 
   Project Name	::> Jiwok
   Module 		::> Admin- Synchronize Color Tables
   Programmer	::> Dijo
   Date			::> 14.12.2011
   
   DESCRIPTION::::>>>>
   This  code used forAdmin- Synchronize Color Tables .
*****************************************************************************/
	error_reporting(1);
	include_once('includeconfig.php');
	include_once('../includes/classes/class.color.php');
	include_once('../includes/classes/class.member.php');
	
	$heading 		= 'Synchronisation';
	$errorMsg		= array();
		
	
	$objMember		= new Member(1);
	$objGen			= new General();
	$objColor		= new Color();
	
	//echo $objColor->_getMaxValue('program_detail','');
	$msg			=	"";
	
	if($_REQUEST['action'])
	{
			//compose all recipient type
			$type	 =	base64_decode($_REQUEST['action']);
			if($type == 'synch_program_details')
			{
				$res	=	$objColor->_synchronizeProgramDetails();				
			}
			else if($type == 'synch_workout_details')
			{
				$res	= 	$objColor->_synchronizeWorkoutDetails();
			}
			
			if($res)
				$msg = "Synchronized Successfully";
			else
				$msg =  "Synchronization Error";
	}
	
?>
<html><head><title><?=$admin_title?></title>
<? include_once('metadata.php');?>

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
		  
		  	
            <table cellSpacing=0 cellPadding=0 width="100%" border="0" style="margin-top:300px" align=center border=0>
             
			 <tr>
             <td colspan="3" align="center"><h3><?php echo $msg; ?></h3></td>
             </tr>
             
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
                      <td vAlign=top width=564  bgColor=white> 
                       <p>
			  				<a href= "synchronisation.php?action=<?php echo base64_encode('synch_program_details'); ?>">Synchronize The Program Details Table >> </a>
                       </p>
                        <p>
                            <a href= "synchronisation.php?action=<?php echo base64_encode('synch_workout_details'); ?>">Synchronize The Workout Details Table >></a>
                        </p>
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

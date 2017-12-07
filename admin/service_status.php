<?php
ob_start();
include_once('includeconfig.php');
include_once('../includes/classes/class.Contents.php'); ##not need
include_once('../includes/classes/class.member.php');
include_once('../includes/classes/class.DbAction.php');
$heading	=	"Service Status";
$row		=	1;
if($_REQUEST['catId']	==	"")
	$catid		=	"Programs";
else	
	$catid		=	$_REQUEST['catId'];
	
if($catid	==	"Programs")
{
	$j			=	1;
	$count		=	$j+3;
}
if($catid	==	"Sessions")
{
	$j			=	4;
	$count		=	$j+2;
}
if($catid	==	"Users")
{
	$j			=	6;
	$count		=	$j+2;
}

$service1	=	"http://geonaute.jiwok.com/programs//workouts.xml";
$service2	=	"http://geonaute.jiwok.com/programs/180.xml";
$service3	=	"http://geonaute.jiwok.com/programs/prices.xml";
$service4	=	"http://geonaute.jiwok.com/sessions/937.xml";
$service5	=	"http://geonaute.jiwok.com/sessions//workout.xml";
$service6	=	"http://geonaute.jiwok.com/users//programs.xml";
$service7	=	"http://geonaute.jiwok.com/users/registerfree.xml";
?>
<?php
function checkStatus($url,$i)
{
	$url	=	urldecode($url);	
	$headr	=	get_headers($url, 1);	
	$xmlValid	= 	$headr['Content-Type'];
	if($xmlValid == 'application/xml') 
	{
		//$server1Img.$i	=	'status_green.png';
		return 1;
	} 
	else
	{
		//$server1Img.$i	=	'status_red.png';
		return 2;
	}		
}?>

<HTML><HEAD><TITLE><?=$admin_title?></TITLE>

<? include_once('metadata.php');?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8;" />

</HEAD>

<BODY class="bodyStyle">

<TABLE cellSpacing=0 cellPadding=0 width="779" align="center" border="1px" bordercolor="#E6E6E6" >

  <TR>

    <TD vAlign=top align=left bgColor=#ffffff><? include("header.php");?></TD>

  </TR>

  <TR height="5">

    <TD vAlign=top align=left class="topBarColor">&nbsp;</TD>

  </TR>

  <TR>

    <TD vAlign="top" align="left" height="340"> 

      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 class="middleTableBg">

        <TR> 

          <TD vAlign=top align=left width="175" rowSpan="2" > 

            <TABLE cellSpacing="0" cellPadding="0" width="175"  border=0>

              <TR> 

                <TD valign="top">

				 <TABLE cellSpacing=0 cellPadding=2 width=175 

                  border=0>

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

          <TD vAlign=top align=left width=0></TD>

         

        </TR>

        <TR> 

          <TD valign="top" width="1067" ><!---Contents Start Here----->

		  

		  

            <TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>

              <TR> 

                <TD class=smalltext width="98%" valign="top">

				

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

                      <TD vAlign=top width=564 bgColor=white> 

                       

			   <form name="frmadmin" action="service_status.php" method="post">

                        

				  <table class="paragraph2" cellspacing=0 cellpadding=0 width=553 border=0>
					
				  <tr>

						<td height="50" colspan="2" align="center" valign="bottom" class="sectionHeading"><?=$heading;?></td>

					</tr>
					<tr height="30px"> &nbsp;</tr>
					
					<?php if($confMsg != ""){?>

					<tr> <td colspan="2" align="center" class="successAlert"><?=$confMsg?></td> </tr>

					<?php }

						if($errorMsg != ""){

					?>

					<tr>

						<td colspan="2" align="center"  class="successAlert"><?=$errorMsg?></td>

					</tr>

					<?php } ?>
				

				  </table>

					<table  cellspacing=0 cellpadding=0 width="553" border=0 class="topColor">

                      <tbody>

				    

	    			   <tr> 

					   <td width="361" valign=top class="paragraph2">&nbsp;&nbsp;&nbsp;		   </td>

							

						

					   

					   <td width="192" align=right class="paragraph2">Category 

			

						<select name="catId" class="paragraph" onChange="this.form.submit()">
                           
									<option value="Programs" <?php if($_REQUEST['catId']=="Programs"){?> selected="selected" <?php }?>>Programs</option>
									<option value="Sessions" <?php if($_REQUEST['catId']=="Sessions"){?> selected="selected" <?php }?>> Sessions</option>
									<option value="Users" <?php if($_REQUEST['catId']=="Users"){?> selected="selected" <?php }?> >Users</option>
									
                         </select>			
						 
						</td>
	
						</tr>	

                    </tbody>

                   </table>
							  
                      <table class="listTableStyle" cellspacing=1 cellpadding=2 width="553">

                       <tbody>

                         <tr class="tableHeaderColor">

                           <td width="8%" align="center" >                             #</td>

						   <td width="32%" align="left">&nbsp; Service</td>

                           <td width="10%" align="center" >Status</td>

                         </tr>

                         <?php if($errMsg != ""){?>

                         <tr class="listingTable">

                           <td align="center" colspan="6" ><font color="#FF0000">

                             <?=$errMsg?>

                           </font> </td>

                         </tr>

                         <?php }
						 
						 for($i=$j;$i<$count;$i++,$row++)
						{
							$srvc	=	"service".$i;
							$status	=	checkStatus($$srvc,$i);
							if($status	==	1)
							{

						 ?>

                         <tr class="listingTable">

                           <td align="center" ><?=$row;?></td>

                           <td  align="left">&nbsp; <?=$$srvc;?></td>

                           <td  align="center"><img src="http://www.jiwok.com/admin/images/status_green.png" /></td>
                           
                         </tr>

                         <?php

						}
						else
						
						{
						
						?>
							<tr class="listingTable">

                           <td align="center" ><?=$row;?></td>

                           <td  align="left">&nbsp; <?=$$srvc;?></td>

                           <td  align="center"><img src="http://www.jiwok.com/admin/images/status_red.png" /></td>
                           
                         </tr>
						 
                       </tbody>
					<?php	
						}
					}?>
                     </table>

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

    <TD vAlign=top align=left class="topBarColor" colspan="3">&nbsp;</TD>

  </TR>

      </TABLE>

        <?php include_once("footer.php");?>

</body>

</html>
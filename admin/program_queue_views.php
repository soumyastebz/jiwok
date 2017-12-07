<?php
ob_start();
/**************************************************************************** 

	Project Name	::> Jiwok 

   	Module 			::> Admin-Program queue entries list

   	Programmer		::>	Dileep.E  

   	Date			::> 28/04/12
	
   	DESCRIPTION::::>>>>
	
		To list the users who having the entries in the progm queue table.  

*****************************************************************************/
include_once('includeconfig.php');
include_once('../includes/classes/class.Contents.php'); ##not need
include_once('../includes/classes/class.member.php');

//error_reporting(E_ALL);
//ini_set('display_errors','On');

$dbObj	 =	new DbAction();
$heading = "Queue List";
$errMesg = "";
$confMsg = "";
//Checking the user id
if($_REQUEST['userId']	!=	"")
  $userId	=	$_REQUEST['userId'];
else
  header('Location:program_queue_list.php?msg='.base64_encode('Invalid call of url...!!!!!'));  

//setiing the default languge as english other vice the languge will be the selected one fromm the dropdrown 

if($_REQUEST['langId']!="")
  $lanId=$_REQUEST['langId'];
else
  $lanId=1;  

$memObj 	=	new Member($lanId);
$genObj   	=	new General();
//Confirmation message generates here

if($_REQUEST['status'] == "success_delete")
{
	$confMsg = "Successfully Deleted";
}
/*if($_REQUEST['status'] == "success_update")
{
	$confMsg = "Successfully Updated";
}*/
//Sorting field decides here

if($_REQUEST['field'])
{
	$field 	= 	'PQ.'.$_REQUEST['field'];
	$type 	= 	$_REQUEST['type'];
}
else
{
	$field 	= 	"PQ.invoke_time";
	$type 	= 	"DESC";
}
//check whether the search keyword is existing

if(trim($_REQUEST['keyword']))
{
	$cleanData	=	str_replace("'",'\\\\\\\\\'',trim($_REQUEST['keyword']));
	$cleanData	=	str_replace("%"," ",trim($cleanData));
	if(preg_match('/["%","$","#","^","!"]/',trim($_REQUEST['keyword'])))
	{
		$errMsg = "Special characters are not allowed";
	}
	else
	{
		$exp_keywords=explode(" ",$cleanData);
		if(count($exp_keywords) > 1)	
		{		
			$searchQuery	=	" and  (PQ.workout_flex_id like '%".$exp_keywords[0]."%' OR PQ.workout_flex_id like '%".$exp_keywords[1]."%')";		
		}	
		else	
		{	
			$searchQuery	=	" and  (PQ.workout_flex_id like '%".$cleanData."%')";
		}
	}
}
//Delete from program queue
if($_REQUEST['action'] == "delete")
{
	if($_REQUEST['queue_id'])
	{		
		$delWhere	=	"queue_id	=	'".$_REQUEST['queue_id']."'";
		$dbObj		->	_deleteData('program_queue',$delWhere);
		$confMsg 	= 	"Successfully Deleted";
	}
	else
		$errorMsg	=	"Invalid operation";
}
	
$query = "SELECT count(PQ.queue_id) as max FROM program_queue AS PQ WHERE PQ.user_name	!=	''	AND	PQ.user_id='".$userId."' ";
if($searchQuery)
	$query .= 	$searchQuery; 	
$result 	= 	$GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);
$totalRecs 	= 	$result[0]->max;
if($totalRecs <= 0)
	$errMsg = "No Records";
##############################################################################################################

/*                        Following Code is for doing paging                                                */

##############################################################################################################
if(!$_REQUEST['maxrows'])
	$_REQUEST['maxrows'] 	=	$_POST['maxrows'];
if($_REQUEST['pageNo'])
{
	if($_REQUEST['pageNo']*$_REQUEST['maxrows'] >= $totalRecs+$_REQUEST['maxrows'])
	{
		$_REQUEST['pageNo'] 	=	1;
	}
$result 	= 	$memObj->_showPageQueueView($userId,$totalRecs,$_REQUEST['pageNo'],$_REQUEST['maxrows'],$field,$type,$searchQuery);
}
else
{
	/***********************Selects Records at initial stage***********************************************/
	$_REQUEST['pageNo'] 	= 	1;
	$result		=	$memObj->_showPageQueueView($userId,$totalRecs,$_REQUEST['pageNo'],$_REQUEST['maxrows'],$field,$type,$searchQuery);
	if(count($result) <= 0)
		$errMsg = "No Records.";
}
if($totalRecs <= $_REQUEST['pageNo']*$_REQUEST['maxrows'])
{
	//For showing range of displayed records.
	if($totalRecs <= 0)
		$startNo 	= 	0;
	else
		$startNo 	= 	$_REQUEST['pageNo']*$_REQUEST['maxrows']-$_REQUEST['maxrows']+1;
	$endNo 	= 	$totalRecs;
	$displayString 	= 	"Viewing $startNo to $endNo of $endNo ".$heading;
}
else
{
	//For showing range of displayed records.
	if($totalRecs <= 0)
		$startNo 	= 	0;
	else
		$startNo 	= 	$_REQUEST['pageNo']*$_REQUEST['maxrows']-$_REQUEST['maxrows']+1;
	$endNo 	= 	$_REQUEST['pageNo']*$_REQUEST['maxrows'];
	$displayString 	= 	"Viewing $startNo to $endNo of $totalRecs ".$heading;
}
//Pagin 
$noOfPage 	= 	@ceil($totalRecs/$_REQUEST['maxrows']); 
if($_REQUEST['pageNo'] == 1)
{
	$prev 	= 	1;
}
else
	$prev 	= 	$_REQUEST['pageNo']-1;
if($_REQUEST['pageNo'] == $noOfPage)
{
	$next 	= 	$_REQUEST['pageNo'];
}
else
	$next 	= 	$_REQUEST['pageNo']+1;
$languageArray	=	$siteLanguagesConfig;
?>
<HTML><HEAD><TITLE><?=$admin_title?></TITLE>

<? include_once('metadata.php');?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8;" />
<style>
.myClass{padding-right:2px;color:#2D497F;padding-left:10px;}
</style>
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

                       

			   <form name="frmadmin" action="program_queue_views.php" method="post">

                        

				  <table class="paragraph2" cellspacing=0 cellpadding=0 width=553 border=0>

				  <tr>

						<td height="50" align="center" valign="bottom" class="sectionHeading"><?=$heading;?></td>

					</tr>

					<?php if($confMsg != ""){?>

					<tr> <td align="center" class="successAlert"><?=$confMsg?></td> </tr>

					<?php }

						if($errorMsg != ""){

					?>

					<tr>

						<td align="center"  class="successAlert"><?=$errorMsg?></td>

					</tr>

					<?php } ?>

					

					<TR> 

					<TD align="left">

						

				   		<table height="50" width="100%" class="topActions"><tr>
							<td width="17%" valign="middle"><a href="program_queue_list.php?maxrows=<?=$_REQUEST['maxrows']?>"><img src="images/list.gif" alt="Listing Record">&nbsp;List </a></td>
						<td width="83%"  align="right" valign="middle" class="extraLabels">Keyword&nbsp;<input name="keyword" type="text" size="10" value="<?=$genObj->_output($_REQUEST['keyword']);?>">&nbsp;<input type="submit" name="search" onClick="javascript:this.form.submit();" value="Search"></td>

						</tr>				   		  
				   		</table>

					</TD>

					</TR>

					<TR> 

					<TD align="left">

						<table width="100%" class="topActions1"><tr>
							<td width="17%" valign="middle" class="extraLabels">Python Status</td>
						<td width="83%"  align="left" valign="middle" ><strong class="myClass">7:</strong>Invoked<strong class="myClass">8:</strong>Started<strong class="myClass">9:</strong>Finished<strong class="myClass">11:</strong>Downloaded<strong class="myClass">15:</strong>Failed</td>

						</tr>
						  <tr>
						    <td valign="middle" class="extraLabels">Tag Status</td>
						    <td  align="left" valign="middle"><strong class="myClass">1:</strong>Invoked<strong class="myClass">2:</strong>Started<strong class="myClass">3:</strong>Finished<strong class="myClass">5:</strong>Failed</td>
						    </tr>
						  <tr>
						    <td valign="middle">&nbsp;</td>
						    <td  align="right" valign="middle" class="extraLabels">&nbsp;</td>
						    </tr>				   		  
				   		</table>			   		

					</TD>

					</TR>

				  </table>

                     <table  cellspacing=0 cellpadding=0 width="553" border=0 class="topColor">

                      <tbody>

				    

	    			    	<tr> 

					   <td width="361" valign=top class="paragraph2"><?=$displayString?>					   </td>

							

						

					   

					   <td width="192" align=right class="paragraph2"><?=$heading;?> per page: 

			

						<select class="paragraph"  size=1 onChange="this.form.submit()" name="maxrows">

						<? foreach($siteRowListConfig as $key=>$data){ ?>

						  <option value="<?=$key?>" <? if($_REQUEST['maxrows']==$key) echo"selected"; ?>><?=$data;?></option>

						 <? } ?>

						</select>					</td>

				    </tr>	

                                </tbody>

                              </table>

				     <table class="listTableStyle" cellspacing=1 cellpadding=2 width="553">

                       <tbody>

                         <tr class="tableHeaderColor">

                           <td width="6%" align="center" >                             #</td>

						   <td width="7%" align="left">&nbsp;Nmbr</td>

                           <td width="23%" align="center">&nbsp;Program name</td>
                           <td width="31%" align="center">&nbsp;Invoked Time 

						   <a href="program_queue_views.php?field=invoke_time&type=asc&maxrows=<?=$_REQUEST['maxrows']?>&pageNo=<?=$_REQUEST['pageNo']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>&userId=<?=$userId?>"><img src="images/up.gif" border="0" alt="Ascending Sort">&nbsp;</a> <a href="program_queue_views.php?field=invoke_time&type=desc&maxrows=<?=$_REQUEST['maxrows']?>&pageNo=<?=$_REQUEST['pageNo']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>&userId=<?=$userId?>"><img src="images/down.gif" border="0" alt="Descending Sort"></a>				</td>

                           <td width="7%" align="center" >Status</td>
                           <td width="18%" align="center" >Completed time</td>

                           <td width="8%" align="center" >Action</td>

                         </tr>

                         <?php if($errMsg != ""){?>

                         <tr class="listingTable">

                           <td align="center" colspan="8" ><font color="#FF0000">

                             <?=$errMsg?>

                           </font> </td>

                         </tr>

                         <?php }

					   	

					   	$count = $startNo;						
						while($result->fetchInto($row,DB_FETCHMODE_OBJECT)){

						

						?>

                         <tr class="listingTable">

                           <td align="center" ><?=$count?></td>

                           <td  align="left">&nbsp; <?=$genObj->_output($row->workoutOrderNumber)?></td>

                           <td  align="center">&nbsp; <?=$genObj->_output($row->program_title)?></td>
                           <td  align="center">&nbsp;<?=$genObj->_output($row->invoke_time);?></td>

                           <td align="center"><?=$row->status; ?></td>
                           <td align="center">&nbsp;<?=$genObj->_output($row->end_time);?></td>

                           <td align="center"><a href = "program_queue_views.php?queue_id=<?=$row->queue_id?>&pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&type=<?=$_REQUEST['type']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>&action=delete&userId=<?=$userId?>" class="smallLink" onClick="return confirm('Are you sure that you want to delete the selected program queue Record? If yes click Ok, if not click Cancel.')"><img src="images/n.gif" width="14" height="14" title="Delete"></a></td>

                         </tr>

                         <?php

						$count++;

						}

						?>

                       </tbody>

                     </table>

				     <table cellspacing=0 cellpadding=0 width=553 border=0 class="topColor">

                                <tbody>		

					<tr>

						<td align="left" colspan = "3" class="leftmenu" style="padding-left:5px; padding-top:5px;"></td>

						<?php if($noOfPage > 1) { ?>

						<td width="297" colspan = "3" align="right" class="leftmenu" style="padding-left:5px; padding-top:5px;">

						<a href="program_queue_views.php?pageNo=1&type=<?=$_REQUEST['type']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>&userId=<?=$userId?>">

						<img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>

						<a href="program_queue_views.php?pageNo=<?=$prev?>&type=<?=$_REQUEST['type']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>&userId=<?=$userId?>">

						<img src="images/mail-arrowlg.gif" border=0 width="20" height="20" align="absmiddle" alt="Previous Page"></a>[Page 

						<select name="pageNo" class="paragraph"  onChange="form.submit()">

							<?php

							if($noOfPage){

								for($i = 1; $i <= $noOfPage; $i++){

							?>

								<option value="<?=$i?>" <? if($i==$_REQUEST['pageNo']) echo "selected";?>><?=$i?></option>

							<?php

								}

							}

							else{

								echo "<option value=\"\">0</option>";

							}

							?>

						</select>

							 of <?=$noOfPage?>]

							 <a href="program_queue_views.php?pageNo=<?=$next?>&type=<?=$_REQUEST['type']?>&field=<?=$_REQUEST['field']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>&userId=<?=$userId?>">

							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>

							<a href="program_queue_views.php?pageNo=<?=$noOfPage?>&type=<?=$_REQUEST['type']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>&userId=<?=$userId?>">

							<img src="images/mail-arrowlastg.gif" border=0 width="20" height="20" align="absmiddle" alt="Last Page"></a>						</td>

						<?php }?>	

					</tr>

				   </tbody>

			 	</table>

				<input type="hidden" name="type" value="<?=$_REQUEST['type']?>">

				<input type="hidden" name="field" value="<?=$_REQUEST['field']?>">
				<input type="hidden" name="userId" value="<?=$_REQUEST['userId']?>">
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
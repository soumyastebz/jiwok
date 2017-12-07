<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-CMS Management
   Programmer	::> Deepa S
   Date			::> 26/12/2008
   
   DESCRIPTION::::>>>>
   This  code userd to list training programs of a particular trainer .
   
*****************************************************************************/
	include_once('includeconfig.php');
	include_once('../includes/classes/class.trainer.php');
	include_once('../includes/classes/class.DbAction.php'); 
	
		
   $lanId = 1;
	
	/*
	Take all the languages to an array.
	*/
	$languageArray = $siteLanguagesConfig;
	reset($languageArray);
						 
	/*
	 Instantiating the classes.
	*/
	$genObj   =	new General();
	$objDb    = new DbAction();
	$memObj   = new Trainer($lanId);
	
	$heading = "Training Programs";
	$userId =    base64_decode($_REQUEST['userId']);
	
	$sql = "SELECT program_manager.program_name as programname,program_manager.programmaster_id as programid FROM program_manager,programs WHERE program_manager.programmaster_id=programs.program_id AND program_manager.language_id = 1 AND programs.program_createdby='$userId'  GROUP BY program_manager.programmaster_id ORDER BY programs.program_id DESC";	
	
	
	
	$result1=$objDb->_getList($sql);
	
	
	
	$totalRecs = $objDb->_isExist($sql);
	if($totalRecs <= 0)
		$errMsg = "No Records Exists";
	
	
	$_REQUEST['maxrows1'] = 20;
	if($_REQUEST['pageNoo']){
		if($_REQUEST['pageNoo']*$_REQUEST['maxrows1'] >= $totalRecs+$_REQUEST['maxrows1']){
			$_REQUEST['pageNoo'] = 1;
		}
		$fromLimit = $_REQUEST['maxrows1']*($_REQUEST['pageNoo'] - 1);
		$toLimit = $_REQUEST['maxrows1'];
		$sql .= " LIMIT $fromLimit,$toLimit";
		
		$result=$objDb->_getList($sql);
	}
	else{
	/***********************Selects Records at initial stage***********************************************/
	$_REQUEST['pageNoo'] = 1;
		$fromLimit = $_REQUEST['maxrows1']*($_REQUEST['pageNoo'] - 1);
		$toLimit = $_REQUEST['maxrows1'];
		$sql .= " LIMIT $fromLimit,$toLimit";
		$result=$objDb->_getList($sql);
		
	}

		
	if($totalRecs <= $_REQUEST['pageNoo']*$_REQUEST['maxrows1'])
	{
		//For showing range of displayed records.
		if($totalRecs <= 0)
			$startNo = 0;
		else
			$startNo = $_REQUEST['pageNoo']*$_REQUEST['maxrows1']-$_REQUEST['maxrows1']+1;
		$endNo = $totalRecs;//$result->numrows();
		$displayString = "Viewing $startNo to $endNo of $endNo ".$heading;
		
	}
	else
	{
		//For showing range of displayed records.
		if($totalRecs <= 0)
			$startNo = 0;
		else
			$startNo = $_REQUEST['pageNoo']*$_REQUEST['maxrows1']-$_REQUEST['maxrows1']+1;
		$endNo = $_REQUEST['pageNoo']*$_REQUEST['maxrows1'];
		$displayString = "Viewing $startNo to $endNo of $totalRecs ".$heading;
		
	}
	//Pagin 

	
	$noOfPage = @ceil($totalRecs/$_REQUEST['maxrows1']); 
	if($_REQUEST['pageNoo'] == 1){
		$prev = 1;
	}
	else
		$prev = $_REQUEST['pageNoo']-1;
	if($_REQUEST['pageNoo'] == $noOfPage){
		$next = $_REQUEST['pageNoo'];
	}
	else
		$next = $_REQUEST['pageNoo']+1;
	
		
?>
<HTML><HEAD><TITLE><?=$admin_title?></TITLE>

<? include_once('metadata.php');?>
<BODY class="bodyStyle">
<TABLE cellSpacing=0 cellPadding=0 width="779" align="center" border="1px" bordercolor="#E6E6E6"> 
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
            <TABLE cellSpacing="0" cellPadding="0" width="175" border=0>
              <TR> 
                <TD valign="top">
				 <TABLE cellSpacing=0 cellPadding=2 width=175 border=0>
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
          <TD valign="top" width="1067"><!---Contents Start Here----->
		  
		  
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
                       
			                           
				  <table class="paragraph2" cellspacing=0 cellpadding=0 width=553 border=0>
				  <tr>
						<td height="50" align="center" valign="bottom" class="sectionHeading"><?=$heading;?></td>
					</tr>
				  	<tr>
						<td valign="middle" height="30" align="right" ><a href="list_trainers.php?pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&type=<?=$_REQUEST['type']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>"><img src="images/list.gif" alt="Listing Record">&nbsp;List Trainers</a></td>
					</tr>
				  </table>
                     
				  <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="553">
				   <TBODY> 
					   <TR class="tableHeaderColor">
						<TD width="16%" align="center" >#</TD>
						<TD width="53%" align="left" >Program&nbsp;Name</TD>
						
						<TD width="31%" align="left" colspan="2" >No. of Workouts</TD>
						
					  </TR>
					  <?php if($errMsg != ""){?>
						  <TR class="listingTable"> 
							<TD align="center" colspan="4" height="50"  valign="middle"><font color="#FF0000"><?=$errMsg?></font> 
							</TD>
						  </TR>
						  <? }?>
					      <? if(count($result)>0){?>
						    <tr class="listingTable">
						    	<TD align="center" colspan="4" class="tblbackgnd"><table width="100%" border="0" cellspacing="0" cellpadding="0">
							<? 
							$count = $startNo;
							foreach($result as $key =>$val){
							$pgmid = $val['programid'];  
							$sql = "SELECT * FROM workouts WHERE  program_id='$pgmid'  GROUP BY program_id";	
							$workoutnum = $objDb->_isExist($sql);
							if($workoutnum <= 0) $workoutnum = 0;
							else  $workoutnum = $workoutnum;		 			
							?>	
                            <tr  <? if(($key%2) ==1){?> bgcolor="#FFFFFF" <? } ?> >
						    <TD width="16%" height="19" align="center"><?=$count?></TD>
							<TD width="53%" height="19" align="left" style="padding-left:10px;"><? echo $val['programname'];?></TD>
							
							<TD  width="31%" height="19" align="left" style="padding-left:30px;"><? echo $workoutnum;?></TD>
						
								
						    </tr>
							<? }?>
                                </table></TD>
							
								
						    </tr>
				    	<? }?>
					</tbody>
			 	</table>
						
                      </TD>
                    </TR>
                  </TABLE>
				  <?php
					  if($totalRecs > 0)
					  {
					  ?>
				  <table cellspacing=0 cellpadding=0 width=553 border=0 class="topColor">
                                <tbody>		
					<tr>
						<td align="left" colspan = "6" class="leftmenu">
						<a href="view_programs.php?pageNoo=1&maxrows1=<?=$_REQUEST['maxrows1']?>&pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&type=<?=$_REQUEST['type']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>&userId=<?=$_REQUEST['userId']?>">
						<img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>
						<a href="view_programs.php?pageNoo=<?=$prev?>&maxrows1=<?=$_REQUEST['maxrows1']?>&pageNo=<?=$_REQUEST['pageNo']?>&type=<?=$_REQUEST['type']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>&userId=<?=$_REQUEST['userId']?>">
						<img src="images/mail-arrowlg.gif" border=0 width="20" height="20" align="absmiddle" alt="Previous Page"></a>[Page <?=$_REQUEST['pageNoo']?> of <?=$noOfPage?>]
							 <a href="view_programs.php?pageNoo=<?=$next?>&maxrows1=<?=$_REQUEST['maxrows1']?>&pageNo=<?=$_REQUEST['pageNo']?>&type=<?=$_REQUEST['type']?>&field=<?=$_REQUEST['field']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>&userId=<?=$_REQUEST['userId']?>">
							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>
							<a href="view_programs.php?pageNoo=<?=$noOfPage?>&maxrows1=<?=$_REQUEST['maxrows1']?>&pageNo=<?=$_REQUEST['pageNo']?>&type=<?=$_REQUEST['type']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>&userId=<?=$_REQUEST['userId']?>">
							<img src="images/mail-arrowlastg.gif" border=0 width="20" height="20" align="absmiddle" alt="Last Page"></a>						</td>
					</tr>
				   </tbody>
			 	</table>
				 <?php } ?> 
				  
				  
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
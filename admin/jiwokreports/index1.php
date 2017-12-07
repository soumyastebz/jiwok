<?php
	include_once('includeconfig.php');
	include_once("includes/classes/class.report.php");
	$admin_title = "JIWOK REPORTS";
	
	if($_REQUEST['langId'] != ""){
		$lanId = $_REQUEST['langId'];
	}
	else{
		$lanId = 1;
	}
	
	$objGen      =	 new General();
	$objReport	 = 	 new Report($lanId);
	$objDb       =   new DbAction();
	
	
	// get all members of both jiwok and brands together
	$allMembers 		 	= $objReport->getAllUsersCount();
	$activeMembers 		 	= $objReport->getAllUsersCount(" AND (user_master.user_status=1 or user_master.user_status=3) ");
	$inactiveMembers 	 	= $objReport->getAllUsersCount(" AND (user_master.user_status=2) ");
	$subscribedMembers 	 	= $objReport->getAllUsersCount(" AND (user_master.user_unsubscribed=0 or user_master.user_unsubscribed=1) ");
	$unsubscribedMembers 	= $objReport->getAllUsersCount(" AND (user_master.user_unsubscribed=2)");
	//$freeUsers 				= $objReport->getUsersFreePaid('0');
	//$paidUsers				= $objReport->getUsersFreePaid('1');
	// get the user ids of users who are members of brands
	$brandUsersIds			= $objReport->getBrandUsersIds();
	
	// get the count of users who are members of only jiwok,not in brands
	$jiwokUsersAll		 	= $objReport->getJiwokUsersCount($brandUsersIds);
	$jiwokUsersActive	 	= $objReport->getJiwokUsersCount($brandUsersIds," AND (user_master.user_status=1 or user_master.user_status=3)");
	$jiwokUsersInactive	 	= $objReport->getJiwokUsersCount($brandUsersIds," AND user_master.user_status=2");
	$jiwokUsersSubscribed	= $objReport->getJiwokUsersCount($brandUsersIds," AND (user_master.user_unsubscribed=0 or user_master.user_unsubscribed=1)");
	$jiwokUsersUnsubscribed	= $objReport->getJiwokUsersCount($brandUsersIds," AND user_master.user_unsubscribed=2");
	
	// get all the brand users 
	$brandUsersAll			= $objReport->getAllBrands();
?>
<HTML><HEAD><TITLE><?=$admin_title?></TITLE>
<? include_once('metadata.php');?>

<!-- Fo AJAX status check-->
  <script language="javascript" src="../includes/js/broucerCheck.js"></script>
</HEAD>
<BODY class="bodyStyle"> 
<TABLE cellSpacing=0 cellPadding=0 width="100%" align="center" border="1px" bordercolor="#E6E6E6"> 
  <TR>
    <TD vAlign=top align=left bgColor=#ffffff><? include("header.php");?></TD>
  </TR>
  
  <TR height="5">
    <TD vAlign=top align=left>
	<table width="100%" class="topBarColor"><tr><td>&nbsp;</td></tr></table>
	</TD>
  </TR>
  
  <TR>
    <TD width="100%" valign="top" align="left" height="340"> 
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 class="middleTableBg">
        <TR> 
          <TD vAlign=top align=left width="175" rowSpan="3" > 
            <TABLE cellSpacing="0" cellPadding="0" width="175" border=0>
              <TR> 
                <TD width="175" align="left" valign="top">
				  <TABLE cellSpacing=0 cellPadding=2 width=175 
                  border=0>
                    <TBODY> 
                    <TR valign="top"> 
                      <TD align="left" valign="top"><? include ('leftmenu.php');?></TD>
                    </TR>
                    </TBODY> 
                  </TABLE>				</TD>
              </TR>
            </TABLE>          </TD>
          <TD vAlign=top align=left width=0></TD>
        </TR>
		
        <TR align="center" valign="top"> 
          <TD align="center" valign="top">
		  		<br><br>
              <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="10" height="9"><img src="images/top_left.jpg" width="9" height="9"></td>
                  <td width="543" height="9" background="images/top_mdl.jpg"><img src="images/top_mdl.jpg" width="9" height="9"></td>
                  <td width="11" height="9"><img src="images/top_right.jpg" width="9" height="9"></td>
                </tr>
               
                <tr>
                  <td background="images/side1.jpg"><img src="images/side1.jpg" width="9" height="9"></td>
                  <td valign="top">
			<table width="100%">
            
                      <tr>
                        <td colspan="6" align="left" style="padding:5px; background-color: #6CF"><strong>Member Reports</strong></td>
                      </tr>
                      <tr>
                      <td width="100%">
                      <table width="100%" ><tr>
                        <td width="30%" style="padding-top:3px;" >Total No. of Users</td><td width="70%">:&nbsp;<?=$allMembers;?></td>
                      </tr>
                      <tr>
                        <td style="padding-top:3px;">No. of Active Users</td><td>:&nbsp;<?=$activeMembers;?></td>
                      </tr>
                      <tr>
                        <td style="padding-top:3px;">No. of Inactive Users</td><td>:&nbsp;<?=$inactiveMembers;?></td>
                      </tr>
                      <tr>
                        <td style="padding-top:3px;">No. of Subscribed Users</td><td>:&nbsp;<?=$subscribedMembers;?></td>
                      </tr>
                      <tr>
                        <td style="padding-top:3px;">No. of Unsubscribed Users</td><td>:&nbsp;<?=$unsubscribedMembers;?></td>
                      </tr>
                      <tr>
                        <td style="padding-top:3px;">No. of Free Users</td><td>:&nbsp;<?=$freeUsers;?></td>
                      </tr> 
                      <tr>
                        <td style="padding-top:3px;">No. of Paid Users</td><td>:&nbsp;<?=$paidUsers;?></td>
                      </tr>  
                      </table>
                      </td>
                      </tr>
                      
                      <tr>
                        <td class="normal" width="100%"><TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="100%">
                                    <TBODY>
                                      <TR class="tableHeaderColor" >
                                      <TD width="18%" align="center" >Brand</TD>
                                        <TD width="14%" align="center" >Total Users</TD>
                                         <TD width="15%" align="center" >Active Users</TD>
                                          <TD width="16%" align="center" >Inactive Users</TD>
                                           <TD width="19%" align="center" >Subscribed Users</TD>
                                            <TD width="18%" align="center" >Unsubscribed Users</TD>
                                        </TR>
                                     
                                                          
                                     <tr class="listingTable">
                        <TD align="center" colspan="6" class="tblbackgnd"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        			   <tr>
                                  		<TD width="18%"  height="19" align="left" style="padding-left:10px;">Jiwok</TD>
                                      <TD width="14%"  height="19" align="center" style="padding-left:10px;"><?=$jiwokUsersAll?></TD>
                                      <TD width="15%"  height="19" align="center" style="padding-left:10px;"><?=$jiwokUsersActive?></TD>
                                      <TD width="16%"  height="19" align="center" style="padding-left:10px;"><?=$jiwokUsersInactive?></TD>
                                      <TD width="19%"  height="19" align="center" style="padding-left:10px;"><?=$jiwokUsersSubscribed?></TD>
                                      <TD width="18%"  height="19" align="center" style="padding-left:10px;"><?=$jiwokUsersUnsubscribed?></TD>
                                       </tr>
                                       <?php if(count($brandUsersAll)>0) { 
									   foreach ($brandUsersAll as $value)
   									   {
									   ?>
                                       <tr>
                                  		<TD width="18%"  height="19" align="left" style="padding-left:10px;"><?=stripslashes($value['brand_name']);?></TD>
                                      <TD width="14%"  height="19" align="center" style="padding-left:10px;"><?=stripslashes($value['numcount']);?></TD>
                                      <TD width="15%"  height="19" align="center" style="padding-left:10px;"><?=$objReport->getEachBrandsCount($value['brand_master_id']," AND (user_master.user_status=1 or user_master.user_status=3)");?></TD>
                                      <TD width="16%"  height="19" align="center" style="padding-left:10px;"><?=$objReport->getEachBrandsCount($value['brand_master_id']," AND user_master.user_status=2");?></TD>
                                      <TD width="19%"  height="19" align="center" style="padding-left:10px;"><?=$objReport->getEachBrandsCount($value['brand_master_id']," AND (user_master.user_unsubscribed=0 or user_master.user_unsubscribed=1)");?></TD>
                                      <TD width="18%"  height="19" align="center" style="padding-left:10px;"><?=$objReport->getEachBrandsCount($value['brand_master_id']," AND user_master.user_unsubscribed=2 ");?></TD>
                                       </tr> 
                                      <?php }} ?>  
                                            
                                       </table>
                                       </TD>
                                      </tr>                      
                                    </tbody>
                                  </table></td>
                      </tr>
                      <tr>
                        <td colspan="3" class="bigheadings" align="right" style="padding-top:10px; padding-right:50px;"><a href="#" style="padding:5px; background-color: #CAE4FF">View Detailed Reports</a></td>
                      </tr>
                      <tr>
                        <td colspan="3" class="bigheadings" align="right">&nbsp;</td>
                      </tr>
                     </table>
                  <table width="100%">
                      <tr>
                        <td colspan="3" align="left" style="padding:5px; background-color: #6CF"><strong>Registers</strong></td>
                      </tr>
                      <tr>
                        <td colspan="3" class="bigheadings" style="padding:10px;">Total number of members:</td>
                      </tr>
                      <tr>
                        <td class="normal"><TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="100%">
                                    <TBODY>
                                      <TR class="tableHeaderColor" >
                                      <TD width="19%" align="center" >Brand</TD>
                                        <TD width="32%" align="center" >No. of Members</TD>
                                        </TR>
                                     
                                                          
                                     <tr class="listingTable">
                        <TD align="center" colspan="6" class="tblbackgnd"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        					<tr>
                                        
                                       <TD width="32%"  height="19" align="left" style="padding-left:10px;">Denis Dhakeir</TD>
                                      <TD width="32%"  height="19" align="left" style="padding-left:10px;">Denis Dhakeir</TD>
                                       </tr>
                                           <tr>
                                        
                                       <TD width="32%"  height="19" align="left" style="padding-left:10px;">Denis Dhakeir</TD>
                                      <TD width="32%"  height="19" align="left" style="padding-left:10px;">Denis Dhakeir</TD>
                                       </tr>
                                            
                                       </table>
                                       </TD>
                                      </tr>                      
                                    </tbody>
                                  </table></td>
                      </tr>
                      <tr>
                        <td colspan="3" class="bigheadings">Please note the following:</td>
                      </tr>
                      <tr>
                        <td colspan="3" class="normal">test</td>
                      </tr>
                  </table>
                  <table width="100%">
                      <tr>
                        <td colspan="3" align="left" style="padding:5px; background-color: #6CF"><strong>Subscriber Reports</strong></td>
                      </tr>
                      <tr>
                        <td colspan="3" class="bigheadings" style="padding:10px;">Total number of members:</td>
                      </tr>
                      <tr>
                        <td class="normal"><TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="100%">
                                    <TBODY>
                                      <TR class="tableHeaderColor" >
                                      <TD width="19%" align="center" >Brand</TD>
                                        <TD width="32%" align="center" >No. of Members</TD>
                                        </TR>
                                     
                                                          
                                     <tr class="listingTable">
                        <TD align="center" colspan="6" class="tblbackgnd"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        					<tr>
                                        
                                       <TD width="32%"  height="19" align="left" style="padding-left:10px;">Denis Dhakeir</TD>
                                      <TD width="32%"  height="19" align="left" style="padding-left:10px;">Denis Dhakeir</TD>
                                       </tr>
                                           <tr>
                                        
                                       <TD width="32%"  height="19" align="left" style="padding-left:10px;">Denis Dhakeir</TD>
                                      <TD width="32%"  height="19" align="left" style="padding-left:10px;">Denis Dhakeir</TD>
                                       </tr>
                                            
                                       </table>
                                       </TD>
                                      </tr>                      
                                    </tbody>
                                  </table></td>
                      </tr>
                      <tr>
                        <td colspan="3" class="bigheadings">Please note the following:</td>
                      </tr>
                      <tr>
                        <td colspan="3" class="normal">test</td>
                      </tr>
                  </table>
                  <table width="100%">
                      <tr>
                        <td colspan="3" align="left" style="padding:5px; background-color: #6CF"><strong>1 Euro Transactions</strong></td>
                      </tr>
                      <tr>
                        <td colspan="3" class="bigheadings" style="padding:10px;">Total number of members:</td>
                      </tr>
                      <tr>
                        <td class="normal"><TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="100%">
                                    <TBODY>
                                      <TR class="tableHeaderColor" >
                                      <TD width="19%" align="center" >Brand</TD>
                                        <TD width="32%" align="center" >No. of Members</TD>
                                        </TR>
                                     
                                                          
                                     <tr class="listingTable">
                        <TD align="center" colspan="6" class="tblbackgnd"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        					<tr>
                                        
                                       <TD width="32%"  height="19" align="left" style="padding-left:10px;">Denis Dhakeir</TD>
                                      <TD width="32%"  height="19" align="left" style="padding-left:10px;">Denis Dhakeir</TD>
                                       </tr>
                                           <tr>
                                        
                                       <TD width="32%"  height="19" align="left" style="padding-left:10px;">Denis Dhakeir</TD>
                                      <TD width="32%"  height="19" align="left" style="padding-left:10px;">Denis Dhakeir</TD>
                                       </tr>
                                            
                                       </table>
                                       </TD>
                                      </tr>                      
                                    </tbody>
                                  </table></td>
                      </tr>
                      <tr>
                        <td colspan="3" class="bigheadings">Please note the following:</td>
                      </tr>
                      <tr>
                        <td colspan="3" class="normal">test</td>
                      </tr>
                  </table>
                  <table width="100%">
                      <tr>
                        <td colspan="3" align="left" style="padding:5px; background-color: #6CF"><strong>Gift Card Transactions</strong></td>
                      </tr>
                      <tr>
                        <td colspan="3" class="bigheadings" style="padding:10px;">Total number of members:</td>
                      </tr>
                      <tr>
                        <td class="normal"><TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="100%">
                                    <TBODY>
                                      <TR class="tableHeaderColor" >
                                      <TD width="19%" align="center" >Brand</TD>
                                        <TD width="32%" align="center" >No. of Members</TD>
                                        </TR>
                                     
                                                          
                                     <tr class="listingTable">
                        <TD align="center" colspan="6" class="tblbackgnd"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        					<tr>
                                        
                                       <TD width="32%"  height="19" align="left" style="padding-left:10px;">Denis Dhakeir</TD>
                                      <TD width="32%"  height="19" align="left" style="padding-left:10px;">Denis Dhakeir</TD>
                                       </tr>
                                           <tr>
                                        
                                       <TD width="32%"  height="19" align="left" style="padding-left:10px;">Denis Dhakeir</TD>
                                      <TD width="32%"  height="19" align="left" style="padding-left:10px;">Denis Dhakeir</TD>
                                       </tr>
                                            
                                       </table>
                                       </TD>
                                      </tr>                      
                                    </tbody>
                                  </table></td>
                      </tr>
                      <tr>
                        <td colspan="3" class="bigheadings">Please note the following:</td>
                      </tr>
                      <tr>
                        <td colspan="3" class="normal">test</td>
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
            <!---Contents Start Here-----></TD>
        </TR>
        <TR align="center" valign="top">
          <TD align="center" valign="top">
		  <a name="#c">&nbsp;</a>
		  </TD>
        </TR>
		 <TR height="2">
    <TD vAlign=top align=left class="topBarColor" colspan="3">&nbsp;</TD>
  </TR>
</TABLE>
  <?php include_once("footer.php");?>
</body>
</html>
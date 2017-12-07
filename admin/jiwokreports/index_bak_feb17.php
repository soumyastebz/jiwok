<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::>Jiwok-Report
   Programmer	::> Deepa S 
   Date			::> 27/Jan/2011
   DESCRIPTION::::>>>> Jiwok Reports section. This index page  displays the report summary of all sections  - All users, Register, Subscriber, Ex-subscriber,1 euro transactions, gift code transactions
  
*****************************************************************************/
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
	$activeMembers 		 	= $objReport->getAllUsersCount(" AND (user_master.user_status=1 or user_master.user_status=3) AND (user_master.user_unsubscribed=0 or user_master.user_unsubscribed=1)");
	$inactiveMembers 	 	= $objReport->getAllUsersCount(" AND (user_master.user_status=2) ");
	$subscribedMembers 	 	= $objReport->getAllUsersCount(" AND (user_master.user_unsubscribed=0 or user_master.user_unsubscribed=1) ");
	$unsubscribedMembers 	= $objReport->getAllUsersCount(" AND (user_master.user_unsubscribed=2)");
	$freeUsers 				= count($objReport->getUsersFreePaid('0'));
	$paidUsers				= count($objReport->getUsersFreePaid('1'));
	
	// get the user ids of users who are members of brands
	$brandUsersIds			= $objReport->getBrandUsersIds();
	
	// get the count of users who are members of only jiwok,not in brands
	$jiwokUsersAll		 	= $objReport->getJiwokUsersCount($brandUsersIds);
	$jiwokUsersActive	 	= $objReport->getJiwokUsersCount($brandUsersIds," AND (user_master.user_status=1 or user_master.user_status=3) AND (user_master.user_unsubscribed=0 or user_master.user_unsubscribed=1)");
	$jiwokUsersInactive	 	= $objReport->getJiwokUsersCount($brandUsersIds," AND user_master.user_status=2");
	$jiwokUsersSubscribed	= $objReport->getJiwokUsersCount($brandUsersIds," AND (user_master.user_unsubscribed=0 or user_master.user_unsubscribed=1)");
	$jiwokUsersUnsubscribed	= $objReport->getJiwokUsersCount($brandUsersIds," AND user_master.user_unsubscribed=2");
	
	// get all the brand users 
	$brandUsersAll			= $objReport->getAllBrands();
	
	//  functions called for getting details of register users.  total registers, register - tested, register-not tested
	$registerUsers 			= $objReport->getUsersFreePaid('0');
	
?>
<HTML><HEAD><TITLE><?=$admin_title?></TITLE>
<? include_once('metadata.php');?>

<!-- Fo AJAX status check-->
  <script language="javascript" src="../includes/js/broucerCheck.js"></script>
</HEAD>
<BODY class="bodyStyle"> 
<TABLE cellSpacing=0 cellPadding=0 width="100%" align="center" border="1px" bordercolor="#E6E6E6"> 
  <TR>
    <TD valign=top align=left bgColor=#ffffff><? include("header.php");?></TD>
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
                        <td colspan="6" align="left" style="padding:5px; background-color: #6CF"><strong>All Users</strong></td>
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
                        <td style="padding-top:3px;">No. of Active Free Users</td><td>:&nbsp;<?=$freeUsers;?></td>
                      </tr> 
                      <tr>
                        <td style="padding-top:3px;">No. of Active Paid Users</td><td>:&nbsp;<?=count($objReport->getBrandSubscribers('subscriber','',''));?></td>
                      </tr>  
                      </table>
                      </td>
                      </tr>
                      
                      <tr>
                        <td class="normal" width="100%"><TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="100%" border="0">
                                    <TBODY>
                                      <TR class="tableHeaderColor" >
                                      <TD width="11%" align="center" >Brand</TD>
                                        <TD width="17%" align="center" >Total Users</TD>
                                         <TD width="10%" align="center" >Active</TD>
                                          <TD width="12%" align="center" >Inactive</TD>
                                           <TD width="17%" align="center" >Subscribed</TD>
                                            <TD width="14%" align="center" >Unsubscribed</TD>
                                            <TD width="11%" align="center" >Free</TD>
                                            <TD width="8%" align="center" >Paid</TD>
                                        </TR>
                                     
                                                          
                                    
                        			   <tr class="listingTable1">
                                  		<TD height="19" align="left" style="padding-left:10px;">Jiwok</TD>
                                      <TD height="19" align="center" style="padding-left:10px;"><?=$jiwokUsersAll?></TD>
                                      <TD height="19" align="center" style="padding-left:10px;"><?=$jiwokUsersActive?></TD>
                                      <TD height="19" align="center" style="padding-left:10px;"><?=$jiwokUsersInactive?></TD>
                                      <TD height="19" align="center" style="padding-left:10px;"><?=$jiwokUsersSubscribed?></TD>
                                      <TD height="19" align="center" style="padding-left:10px;"><?=$jiwokUsersUnsubscribed?></TD>
                                      <TD height="19" align="center" style="padding-left:10px;"><?=count($objReport->getFreeUsersJiwok($brandUsersIds));?></TD>
                                      <TD height="19" align="center" style="padding-left:10px;"><?=count($objReport->getBrandSubscribers('subscriber','jiwok',''));?></TD>
                                       </tr>
                                       <?php if(count($brandUsersAll)>0) { 
									   foreach ($brandUsersAll as $value)
   									   {
									   ?>
                                       <tr class="listingTable1">
                                  		<TD height="19" align="left" style="padding-left:10px;"><?=stripslashes($value['brand_name']);?></TD>
                                      <TD height="19" align="center" style="padding-left:10px;"><?=stripslashes($value['numcount']);?></TD>
                                      <TD height="19" align="center" style="padding-left:10px;"><?=$objReport->getEachBrandsCount($value['brand_master_id']," AND (user_master.user_status=1 or user_master.user_status=3) AND (user_master.user_unsubscribed=0 or user_master.user_unsubscribed=1)");?></TD>
                                      <TD height="19" align="center" style="padding-left:10px;"><?=$objReport->getEachBrandsCount($value['brand_master_id']," AND user_master.user_status=2");?></TD>
                                      <TD height="19" align="center" style="padding-left:10px;"><?=$objReport->getEachBrandsCount($value['brand_master_id']," AND (user_master.user_unsubscribed=0 or user_master.user_unsubscribed=1)");?></TD>
                                      <TD height="19" align="center" style="padding-left:10px;"><?=$objReport->getEachBrandsCount($value['brand_master_id']," AND user_master.user_unsubscribed=2 ");?></TD>
                                      <TD height="19" align="center" style="padding-left:10px;"><?=count($objReport->getFreeUsersBrand($value['brand_master_id']));?></TD>
                                      <TD height="19" align="center" style="padding-left:10px;"><?=count($objReport->getBrandSubscribers('subscriber','brand',$value['brand_master_id']));?></TD>
                                       </tr> 
                                      <?php }} ?>  
                                                        
                                    </tbody>
                                  </table></td>
                      </tr>
                     
                      <tr>
                        <td colspan="3" class="bigheadings" align="right">&nbsp;</td>
                      </tr>
                     </table>
                  <table width="100%">
            
                      <tr>
                        <td colspan="6" align="left" style="padding:5px; background-color: #6CF"><strong>Register Reports</strong></td>
                      </tr>
                      <tr>
                      <td width="100%">
                      <table width="100%" ><tr>
                        <td width="30%" style="padding-top:3px;" >Total No. of Register</td><td width="70%">:&nbsp;<?=count($registerUsers);?></td>
                      </tr>
                      <tr>
                        <td style="padding-top:3px;">No. of Register Tested</td><td>:&nbsp;<?=count($objReport->getRegisterTested());?></td>
                      </tr>
                      <tr>
                        <td style="padding-top:3px;">No. of Register Not Tested</td><td>:&nbsp;<?=count($objReport->getRegisterNotTested());?></td>
                      </tr>
                     </table>
                      </td>
                      </tr>
                      
                      <tr>
                        <td class="normal" width="100%"><TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="100%" border="0">
                                    <TBODY>
                                      <TR class="tableHeaderColor">
                                      	<TD width="11%" align="center">Brand</TD>
                                        <TD width="17%" align="center">Register Tested</TD>
                                        <TD width="10%" align="center">Register Not Tested</TD>
                                     </TR>
                                     
                                                          
                                    
                        			   <tr class="listingTable1">
                                  		<TD height="19" align="left" style="padding-left:10px;">Jiwok</TD>
                                      <TD height="19" align="center" style="padding-left:10px;"><?=count($objReport->getJiwokRegisterTested($brandUsersIds));?></TD>
                                      <TD height="19" align="center" style="padding-left:10px;"><?=count($objReport->getJiwokRegisterNotTested($brandUsersIds));?></TD>
                                     
                                      </tr>
                                       <?php if(count($brandUsersAll)>0) { 
									   foreach ($brandUsersAll as $value)
   									   {
									   ?>
                                       <tr class="listingTable1">
                                  		<TD height="19" align="left" style="padding-left:10px;"><?=stripslashes($value['brand_name']);?></TD>
                                      <TD height="19" align="center" style="padding-left:10px;"><?=count($objReport->getBrandRegisterTested($value['brand_master_id']));?></TD>
                                      <TD height="19" align="center" style="padding-left:10px;"><?=count($objReport->getBrandRegisterNotTested($value['brand_master_id']));?></TD>
                                     
                                      </tr> 
                                      <?php }} ?>  
                                                        
                                    </tbody>
                                  </table></td>
                      </tr>
                      <!--<tr>
                        <td colspan="3" class="bigheadings" align="right" style="padding-top:10px; padding-right:50px;"><a href="#" style="padding:5px; background-color: #CAE4FF">View Detailed Reports</a></td>
                      </tr>-->
                      <tr>
                        <td colspan="3" class="bigheadings" align="right">&nbsp;</td>
                      </tr>
                     </table>
                  <table width="100%">
            
                      <tr>
                        <td colspan="6" align="left" style="padding:5px; background-color: #6CF"><strong>Subscriber Reports</strong></td>
                      </tr>
                      <tr>
                      <td width="100%">
                      <table width="100%" ><tr>
                        <td width="30%" style="padding-top:3px;" >No. of Paid Subscribers</td><td width="70%">:&nbsp;<?=count($objReport->getBrandSubscribers('subscriber','',''));?></td>
                      </tr>
                      <tr>
                        <td style="padding-top:3px;">No. of Ex-Subscribers</td><td>:&nbsp;<?=count($objReport->getBrandSubscribers('exsubscriber','',''));?></td>
                      </tr>
                      </table>
                      </td>
                      </tr>
                      
                      <tr>
                        <td class="normal" width="100%"><TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="100%" border="0">
                                    <TBODY>
                                      <TR class="tableHeaderColor">
                                      	<TD width="11%" align="center">Brand</TD>
                                        <TD width="17%" align="center">Paid Subscribers</TD>
                                        <TD width="10%" align="center">Ex-Subscribers</TD>
                                     </TR>
                                     
                                                          
                                    
                        			   <tr class="listingTable1">
                                  		<TD height="19" align="left" style="padding-left:10px;">Jiwok</TD>
                                      <TD height="19" align="center" style="padding-left:10px;"><?=count($objReport->getBrandSubscribers('subscriber','jiwok',''));?></TD>
                                      <TD height="19" align="center" style="padding-left:10px;"><?=count($objReport->getBrandSubscribers('exsubscriber','jiwok',''));?></TD>
                                     
                                      </tr>
                                       <?php if(count($brandUsersAll)>0) { 
									   foreach ($brandUsersAll as $value)
   									   {
									   ?>
                                       <tr class="listingTable1">
                                  		<TD height="19" align="left" style="padding-left:10px;"><?=stripslashes($value['brand_name']);?></TD>
                                      <TD height="19" align="center" style="padding-left:10px;"><?=count($objReport->getBrandSubscribers('subscriber','brand',$value['brand_master_id']));?></TD>
                                      <TD height="19" align="center" style="padding-left:10px;"><?=count($objReport->getBrandSubscribers('exsubscriber','brand',$value['brand_master_id']));?></TD>
                                     
                                      </tr> 
                                      <?php }} ?>  
                                                        
                                    </tbody>
                                  </table></td>
                      </tr>
                      <!--<tr>
                        <td colspan="3" class="bigheadings" align="right" style="padding-top:10px; padding-right:50px;"><a href="#" style="padding:5px; background-color: #CAE4FF">View Detailed Reports</a></td>
                      </tr>-->
                      <tr>
                        <td colspan="3" class="bigheadings" align="right">&nbsp;</td>
                      </tr>
                     </table>
                  <table width="100%">
            
                      <tr>
                        <td colspan="6" align="left" style="padding:5px; background-color: #6CF"><strong>1 Euro Transactions</strong></td>
                      </tr>
                      <tr>
                      <td width="100%">
                      <table width="100%" ><tr>
                        <td width="30%" style="padding-top:3px;" >No. of 1 Euro Transactions</td><td width="70%">:&nbsp;<?=count($objReport->getOneEuroTransactions('',''));?></td>
                      </tr>
                      </table>
                      </td>
                      </tr>
                      
                      <tr>
                        <td class="normal" width="100%"><TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="100%" border="0">
                                    <TBODY>
                                      <TR class="tableHeaderColor">
                                      	<TD width="11%" align="center">Brand</TD>
                                        <TD width="17%" align="center">No. of Transactions</TD>
                                       </TR>
                                     
                                                          
                                    
                        			   <tr class="listingTable1">
                                  		<TD height="19" align="left" style="padding-left:10px;">Jiwok</TD>
                                      <TD height="19" align="center" style="padding-left:10px;"><?=count($objReport->getOneEuroTransactions('jiwok',''));?></TD>
                                   </tr>
                                       <?php if(count($brandUsersAll)>0) { 
									   foreach ($brandUsersAll as $value)
   									   {
									   ?>
                                       <tr class="listingTable1">
                                  		<TD height="19" align="left" style="padding-left:10px;"><?=stripslashes($value['brand_name']);?></TD>
                                      <TD height="19" align="center" style="padding-left:10px;"><?=count($objReport->getOneEuroTransactions('brand',trim($value['brand_master_id'])));?></TD>
                                      </tr> 
                                      <?php }} ?>  
                                                        
                                    </tbody>
                                  </table></td>
                      </tr>
                      <!--<tr>
                        <td colspan="3" class="bigheadings" align="right" style="padding-top:10px; padding-right:50px;"><a href="#" style="padding:5px; background-color: #CAE4FF">View Detailed Reports</a></td>
                      </tr>-->
                      <tr>
                        <td colspan="3" class="bigheadings" align="right">&nbsp;</td>
                      </tr>
                     </table>
                  <table width="100%">
            
                      <tr>
                        <td colspan="6" align="left" style="padding:5px; background-color: #6CF"><strong>Gift Code Transactions</strong></td>
                      </tr>
                      <tr>
                      <td width="100%">
                      <table width="100%" ><tr>
                        <td width="30%" style="padding-top:3px;" >Total Gift Code Transactions</td><td width="70%">:&nbsp;<?=count($objReport->getGiftCodeTransactions('',''));?></td>
                      </tr>
                      </table>
                      </td>
                      </tr>
                      
                      <tr>
                        <td class="normal" width="100%"><TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="100%" border="0">
                                    <TBODY>
                                      <TR class="tableHeaderColor">
                                      	<TD width="11%" align="center">Brand</TD>
                                        <TD width="17%" align="center">No. of Transactions</TD>
                                       </TR>
                                     
                                                          
                                    
                        			   <tr class="listingTable1">
                                  		<TD height="19" align="left" style="padding-left:10px;">Jiwok</TD>
                                      <TD height="19" align="center" style="padding-left:10px;"><?=count($objReport->getGiftCodeTransactions('jiwok',''));?></TD>
                                   </tr>
                                       <?php if(count($brandUsersAll)>0) { 
									   foreach ($brandUsersAll as $value)
   									   {
									   ?>
                                       <tr class="listingTable1">
                                  		<TD height="19" align="left" style="padding-left:10px;"><?=stripslashes($value['brand_name']);?></TD>
                                      <TD height="19" align="center" style="padding-left:10px;"><?=count($objReport->getGiftCodeTransactions('brand',trim($value['brand_master_id'])));?></TD>
                                      </tr> 
                                      <?php }} ?>  
                                                        
                                    </tbody>
                                  </table></td>
                      </tr>
                      <!--<tr>
                        <td colspan="3" class="bigheadings" align="right" style="padding-top:10px; padding-right:50px;"><a href="#" style="padding:5px; background-color: #CAE4FF">View Detailed Reports</a></td>
                      </tr>-->
                      <tr>
                        <td colspan="3" class="bigheadings" align="right">&nbsp;</td>
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
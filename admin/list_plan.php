<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Listing Plans
   Programmer	::> Jestin
   Date			::> 21/3/2011
   
   DESCRIPTION::::>>>>
   This  code userd to list the all plans .
   Admin can add/edit/delete the plans .. 
*****************************************************************************/
   ob_start();
	
 	include_once('includeconfig.php');

	include_once("../includes/classes/Payment/class.admin.new_payment.php");

	include_once('../includes/classes/class.DbAction.php');

	$dbObj	 =	new DbAction();
	
	$adminPayment	 =	new adminPayment();

	$fields	=	array();
	 
	$fields = $adminPayment->getAllPricePlanList();
	
	// foreach($fields as $key=>$data){
//	 
//	 echo $fields[$key][plan_name];
//	 echo $fields[$key][id];
//	 exit;
//	 
//	 }
	//echo $fields[0]['plan_name'] ; exit;
   
	$heading = "PLANS";

	$errMesg = "";

	$confMsg = ""; 

	//setiing the default languge as english other vice the languge will be the selected one from the dropdrown 

	if($_REQUEST['langId']!="")

	  $lanId=$_REQUEST['langId'];

	else

	  $lanId=1;  

	

	$genObj   =	new General();



	//Confirmation message generates here

	if($_REQUEST['status'] == "success_add"){

		$confMsg = "Successfully Added";

	}

	if($_REQUEST['status'] == "success_update"){

		$confMsg = "Successfully Updated";

	}



	//Sorting field decides here

	if($_REQUEST['field']){

		$field = $_REQUEST['field'];

		$type = $_REQUEST['type'];

	}else{

		$field = "plan_name";

		$type = "ASC";

	}

	//Delete the recordes 

	if($_REQUEST['action'] == "delete"){
		   if($_REQUEST['delId']){
                 
				extract($_REQUEST);
				$adminPayment->deletePricePlan($delId);
                header("location:http:list_plan.php");
				$confMsg = "Successfully Deleted";
			}
	}	

	//Delete the recordes 

	$returnValue='';

	if($_REQUEST['deleterec']){

	 $returnValue=$adminPayment->deletePricePlan($_REQUEST['chkdelete']); 

		if($returnValue == 0){ 

		$errMsg="Please select atleast one record for deletion!";

		} 

		elseif($returnValue == 'admin') 

		{ 

		 $errMsg = "You cannot delete the forum admin";

		}

		else{

		$confMsg = "Successfully Deleted";

		}

	}
              
 
?>

<HTML><HEAD><TITLE><?=$admin_title?></TITLE>

<? include_once('metadata.php');?>

<script language="javascript">

var success=0; cRef=""; cRefType=""; cPage="";

var L10qstr,L10pc,L10ref,L10a,L10pg; L10pg=document.URL.toString(); L10ref=document.referrer;

if(top.document.location==document.referrer || (document.referrer == "" && top.document.location != "")) {L10ref=top.document.referrer;}

L10qStr = "pg="+escape(L10pg)+"&ref="+escape(L10ref)+"&os="+escape(navigator.userAgent)+"&nn="+escape(navigator.appName)+"&nv="+escape(navigator.appVersion)+"&nl="+escape(navigator.language)+"&sl="+escape(navigator.systemLanguage)+"&sa="+success+"&cR="+escape(cRef)+"&cRT="+escape(cRefType)+"&cPg="+escape(cPage);

if(navigator.appVersion.substring(0,1) > "3") { L10d = new Date(); L10qStr = L10qStr+"&cd="+screen.colorDepth+"&sx="+screen.width+"&sy="+screen.height+"&tz="+L10d.getTimezoneOffset();}

<!-- The L10 Hit Counter logo and links must not be removed or altered -->

</script>
<script  language="javascript" type="text/javascript">

function checkAllMembers(member) {

	var theForm = member.form, z = 0;

	 for(z=0; z<theForm.length;z++){

      if(theForm[z].type == 'checkbox' && theForm[z].name != 'chkdeleteAll'){

	  theForm[z].checked = member.checked;

	  }

     }

    }

</script>


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
			   <form name="frmplan" action="list_plan.php" method="post">
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
						<td valign="middle"><a href="addedit_plan.php?action=add&field=<?=$_REQUEST['field']?>&type=<?=$_REQUEST['type']?>"><img src="images/add.gif" border="0" alt="Add Record">&nbsp;Add   </a></td>
                      </tr></table>
					</TD>
					</TR>
				  </table>
				  
                     <table class="listTableStyle" cellspacing=1 cellpadding=2 width="553">
                       <tbody>
                         <tr class="tableHeaderColor">
                           <!--<td width="5%" align="center" >
						   	<input type="checkbox" name="chkdeleteAll[]" value="" onClick="return checkAllMembers(this);"></td>-->
						  <!-- <td width="7%" align="center" >#</td>-->
                           <td width="38%" align="left">&nbsp; Plan  <!--<a href="list_plan.php?field=planName&type=asc"><img src="images/up.gif" border="0" alt="Ascending Sort">&nbsp;</a> <a href="list_plan.php?field=planName&type=desc"><img src="images/down.gif" border="0" alt="Descending Sort"></a>--> </td>
                           <td width="20%" align="center">Plan Amount<!--<a href="list_plan.php?field=planAmount&type=asc"><img src="images/up.gif" border="0" alt="Ascending Sort">&nbsp;</a><a href="list_plan.php?field=planAmount&type=desc"><img src="images/down.gif" border="0" alt="Descending Sort"></a>--></td>
                           <td width="9%" align="center" >Status</td>
                           <td width="18%" align="center" >Action</td>
                         </tr>
                         <?php if($errMsg != ""){?>

                         <tr class="listingTable">

                           <td align="center" colspan="7" ><font color="#FF0000">

                             <?=$errMsg?>

                           </font> </td>
                            <?php } ?>
                         </tr>
                           <?php
						    foreach($fields as $key=>$data){ 
							?>
                         <tr class="listingTable">
						
						    <!--<td width="5%" align="center" ><input type="checkbox" name="chkdelete[]" value="<?=$fields[$key][id];?>"></td>
-->
                          <!-- <td align="center">></td>-->

                           <td  align="left">&nbsp;<?=$plan_id[$fields[$key][plan_duration]];?></td>

                           <td  align="center"><?=$fields[$key][plan_amount];?></td>

                           <td align="center"><?php if($fields[$key][plan_status] == 1 ) echo "<img src=\"images/y.gif\" width=\"14\" height=\"14\">"; else echo "<img src=\"images/n.gif\" width=\"14\" height=\"14\">";?></td>

                           <td align="center"><a href = "addedit_plan.php?editId=<?=base64_encode($fields[$key][id])?>&action=edit" class="smallLink">Edit</a>&nbsp;

                             | <a href = "list_plan.php?delId=<?=base64_encode($fields[$key][id])?>&action=delete" class="smallLink" onClick="return confirm('Are you sure that you want to delete the selected Record? If yes click Ok, if not click Cancel.')">Delete</a>

					 </td>
					   
                         </tr>
						 <?php  }?>
                       </tbody>
                     </table>

				     <table cellspacing=0 cellpadding=0 width=553 border=0 class="topColor">
                      <tbody>		
                     <!--<tr>
					 <td align="left" colspan = "3" class="leftmenu" style="padding-left:5px; padding-top:5px;"><span class="leftmenu" style="padding-left:5px; padding-top:5px;"><input type="submit" name="deleterec" value="Delete Records" onClick="return confirm('Are you sure that you want to delete the record(s)? If Yes click OK, or click Cancel.')" style="cursor:pointer;"></span>
					</td>
					</tr>-->
				   </tbody>
			 	</table>
				
				<input type="hidden" name="type" value="<?=$_REQUEST['type']?>">
				<input type="hidden" name="field" value="<?=$_REQUEST['field']?>">

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
		 </td>
		 </tr>
		 </table>
 </body>

</html>
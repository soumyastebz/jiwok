<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-CMS Management
   Programmer	::> Sreejith E C
   Date			::> 31/1/2007
   
   DESCRIPTION::::>>>>
   This  code userd to list the all servicess .
   Admin can add/edit/delete the services .. 
*****************************************************************************/
	include_once('includeconfig.php');
	include("../includes/classes/class.slide.php");
	
	error_reporting(0);
	
	if($_REQUEST['langId'] != ""){
		$lanId = $_REQUEST['langId'];
	}
	else{
		$lanId = 1;
	}
	
	/*
	Take all the languages to an array.
	*/
	$languageArray = $siteLanguagesConfig;
	reset($languageArray);
						 
	/*
	 Instantiating the classes.
	*/
	$objService = new Service($lanId);
	$objGen   =	new General();
	
	$heading = "Home Page Links";
		
	//Sorting field decides here
	if($_REQUEST['field']){
		$field = "slide_manager.".$_REQUEST['field'];
		$type = $_REQUEST['type'];
	}else{
		$field = "slide_id";
		$type = "ASC";
	}
	
	//check whether the search keyword is existing
	if(trim($_REQUEST['keyword'])){
			$cleanData	=	str_replace("'",'\\\\\\\\\'',trim($_REQUEST['keyword']));
		$cleanData	=	str_replace("%"," ",trim($cleanData));
		if(preg_match('/["%","$","#","^","!"]/',trim($_REQUEST['keyword']))){
		$errMsg = "Special characters are not allowed";
		}else{ 
			$searchQuery	=	" AND slide_content like '%".$cleanData."%'";	}	
				
	}
	
	
	//Confirmation message generates here
	
	if($_REQUEST['status'] == "success_add"){
		$confMsg = "Successfully Added";
	}
	if($_REQUEST['status'] == "success_update"){
		$confMsg = "Successfully Updated";
	}
	
	
	//Delete service
	if($_REQUEST['action'] == "delete"){
		$id		 = $_REQUEST['slideId'];
		$result	 = $objService->_deleteService($id);
		$confMsg = "Successfully Deleted";
	}	
	$totalRecs = $objService->_getTotalCount($searchQuery,$lanId);
	
	if($totalRecs <= 0)
		$errMsg = "No Records";
	
	##############################################################################################################
	/*                        Following Code is for doing paging                                                */
	##############################################################################################################
	if(!$_REQUEST['maxrows'])
		$_REQUEST['maxrows'] = $_POST['maxrows'];
	if($_REQUEST['pageNo']){
		if($_REQUEST['pageNo']*$_REQUEST['maxrows'] >= $totalRecs+$_REQUEST['maxrows']){
			$_REQUEST['pageNo'] = 1;
		}
		$result =  $objService->_showPage($totalRecs,$_REQUEST['pageNo'],$_REQUEST['maxrows'],$field,$type,$searchQuery);
		
	}
	else{
	/***********************Selects Records at initial stage***********************************************/
		$_REQUEST['pageNo'] = 1;
		$result = $objService->_showPage($totalRecs,$_REQUEST['pageNo'],$_REQUEST['maxrows'],$field,$type,$searchQuery);
		
		if(count($result) <= 0)
			$errMsg = "No Records.";
		}
		
	if($totalRecs <= $_REQUEST['pageNo']*$_REQUEST['maxrows'])
	{
		//For showing range of displayed records.
		if($totalRecs <= 0)
			$startNo = 0;
		else
			$startNo = $_REQUEST['pageNo']*$_REQUEST['maxrows']-$_REQUEST['maxrows']+1;
		$endNo = $totalRecs;
		$displayString = "Viewing $startNo to $endNo of $endNo Service's";
		
	}
	else
	{
		//For showing range of displayed records.
		if($totalRecs <= 0)
			$startNo = 0;
		else
			$startNo = $_REQUEST['pageNo']*$_REQUEST['maxrows']-$_REQUEST['maxrows']+1;
		$endNo = $_REQUEST['pageNo']*$_REQUEST['maxrows'];
		$displayString = "Viewing $startNo to $endNo of $totalRecs service's";
		
	}
	//Pagin 
	
	$noOfPage = @ceil($totalRecs/$_REQUEST['maxrows']); 
	if($_REQUEST['pageNo'] == 1){
		$prev = 1;
	}
	else
		$prev = $_REQUEST['pageNo']-1;
	if($_REQUEST['pageNo'] == $noOfPage){
		$next = $_REQUEST['pageNo'];
	}
	else
		$next = $_REQUEST['pageNo']+1;
	
	
		
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
    <TD vAlign="top" align="left" height="100" > 
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
        </TR>
        <TR> 
          <TD valign="top" width=""><!---Contents Start Here----->
        <!--  <TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
              <TR> 
                <TD class=smalltext width="98%" valign="middle">




				
				  <table width="75%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr> 
                <td width="10" height="9"><img src="images/top_left.jpg" width="9" height="9"></td>
                <td width="543" height="9" background="images/top_mdl.jpg"><img src="images/top_mdl.jpg" width="9" height="9"></td>
                <td width="11" height="9"><img src="images/top_right.jpg" width="9" height="9"></td>
              </tr>
              <tr> 
                <td background="images/side1.jpg"><img src="images/side1.jpg" width="9" height="9"></td>
                <td valign="middle"> 
				
				   <table>
<tr>
<td>
<ui>
<li><a href="list_slides.php">Slides</a></li>
<li><a href="list_hometext.php">Home Texts</a></li>
<li><a href="list_program.php">Index Programs</a></li>
<li><a href="list_category.php">Index Category</a></li>
</ui></td>
</tr>
</table> -->
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>

				  <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="500" align="center">
				   <TBODY> 

					   <TR class="tableHeaderColor">
						<TD width="51" align="center">#</TD>
						<TD align="center" width="436" ><?=$heading;?>&nbsp;						</TD>
						
					<!--	<TD width="8%" align="center" >Status</TD> -->
					  </TR>			
<tr class="listingTable">
<TD  align="center">1</TD>
<TD>&nbsp;<a href="list_slides.php">Home Page Slides</a></TD>
</TR>
<tr class="listingTable">
<TD  align="center">2</TD>
<TD>&nbsp;<a href="list_hometext.php">Home Page Texts</a></TD>
</TR>

<tr class="listingTable">
<TD  align="center">3</TD>
<TD>&nbsp;<a href="list_homeprogram.php">Home Page Programs</a></TD>
</TR>

<tr class="listingTable">
<TD  align="center">4</TD>
<TD>&nbsp;<a href="list_homecategory.php">Home Page Categories</a></TD>
</TR>
<tr class="listingTable">
<TD  align="center">5</TD>
<TD>&nbsp;<a href="list_homebottomtext.php">Home Page Bottom Texts</a></TD>
</TR>

<!--<TD><a href="list_hometext.php">Home Texts</a></TD>
<ui>
<li><a href="list_slides.php">Slides</a></li>
<li><a href="list_hometext.php">Home Texts</a></li>
<li><a href="list_program.php">Index Programs</a></li>
<li><a href="list_category.php">Index Category</a></li>
</ui></TD> -->
							
							
								
						   
					</tbody>
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
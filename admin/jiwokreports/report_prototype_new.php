<?php

	$heading = "Subscriber Reports";
	//for generating the month and year specified report for the members and the download
	$todayCalendar = getdate();

	//for generating the month and year specified report for the members and the download
	$today = getdate();
	
?>	

<HTML><HEAD><TITLE>Jiwok Reports</TITLE>

<? include_once('metadata.php');?>
<BODY class="bodyStyle">
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<TABLE cellSpacing=0 cellPadding=0 width="100%" align="center" border="1px" bordercolor="#E6E6E6"> 
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
              
              <form name="reportFrm" action="#" method="post">
                <TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
                  <TR> 
                    <TD class=smalltext width="98%" valign="top">
                    
                          <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
                        <td width="10" height="9"><img src="images/top_left.jpg" width="9" height="9"></td>
                        <td width="543" height="9" background="images/top_mdl.jpg"><img src="images/top_mdl.jpg" width="9" height="9"></td>
                        <td width="11" height="9"><img src="images/top_right.jpg" width="9" height="9"></td>
                      </tr>
                          <tr> 
                            <td background="images/side1.jpg"><img src="images/side1.jpg" width="9" height="9"></td>
                            <td valign="top"> 
                            
                            
                            
                                <TABLE cellSpacing=0 cellPadding=0 border=0 align="center" width="100%">
                                    <TR> 
                                      <TD valign='top' bgColor='white'><table width=100% height="227" border=0 cellpadding=0 cellspacing=0 class="paragraph2">
                                        <tr>
                                          <td height="6" colspan="4" align="center" valign="bottom" class="sectionHeading" style="font-size:16px; padding-top:10px;">Subscriber Reports</td>
                                        </tr>
                                        <tr>
                                           <td  colspan="6" height="27" align="right" valign="bottom" class="sectionHeading" style="padding-right:20px;" ><font style="font-size:14px;background-color:#09F; color:#FFF; padding:3px;">EXPORT TO CSV</font></td>
                                        </tr>
                                        <tr style="padding-top:10px;">
                                          <td  colspan="6" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
                                        </tr>
                                        <tr style="padding-top:10px;">
                                          <td height="21" colspan="6" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                         <td style="padding-top:0px; font-size:12px;padding-left:10px" colspan="4"><strong>Select Subscriber Type</strong>&nbsp;&nbsp;&nbsp;&nbsp;<select id="report_users" name="report_users" style="font-size:11px;width:212px;">
                                            <option value="" selected>All</option>
                                            <option value="country">New Subscribers</option>
                                            <option value="country">Permanent Subscribers</option>
                                            <option value="country">Free Subscribers</option>
                                            <option value="country">Paid Subscribers</option>
                                          </select></td>
                                          
                                          
                                        </tr>
                                        
                                     <tr>
                                          <td height="4" colspan="4" align="left" valign="bottom" class="sectionHeading" style="font-size:13px; color:#36C; padding-left:20px; padding-top:20px; padding-bottom:20px;"><strong>Filter By Parameters</strong></td>
                                        </tr>   
                                       
                                        <tr >
                                          <td align="left" valign="middle" style="padding-left:20px;"><input name="daterange" id="daterange1" value="1" type="checkbox" checked>&nbsp;<strong>Country</strong>&nbsp;&nbsp;</td>
                                          <td style="padding-top:10px;"><select id="user_country" name="user_country" style="font-size:11px; width:212px;"  multiple>
                                            <option value="0">India</option>
                                            <option value="0">France</option>
                                            <option value="0">Pakistan</option>
                                            <option value="0">Newzeland</option>
                                            <option value="0">Afghanistan</option>
                                          </select></td>
                                          <td align="left" valign="middle" style="padding-left:10px;"><input name="daterange" id="daterange1" value="1" type="checkbox" checked>&nbsp;<strong>Age</strong></td>
                                          <td ><input type="text" name="age" size="5" />&nbsp;- &nbsp;<input type="text" name="age" size="5" /></td>
                                        </tr>
                                        <tr >
                                          <td align="left" valign="middle" style="padding-left:20px; padding-top:10px;"><input name="daterange" id="daterange1" value="1" type="checkbox" checked>&nbsp;<strong>Sex</strong></td>
                                          <td style="padding-top:10px;"><select id="user_country" name="user_country" style="font-size:11px;" multiple>
                                            <option value="0">Male</option>
                                            <option value="0">Female</option>
                                          </select></td>
                                          <td align="left" valign="middle" style="padding-left:10px;padding-top:10px;"><input name="daterange" id="daterange1" value="1" type="checkbox">&nbsp;<strong>Language</strong></td>
                                          <td style="padding-top:10px;"><select id="user_country" name="user_country" style="font-size:11px;" multiple >
                                           
                                            <option value="0">English</option>
                                            <option value="0">French</option>
                                          </select></td>
                                        </tr>
                                         <tr >
                                          <td align="left" valign="middle" style="padding-left:20px; padding-top:10px;"><input name="daterange" id="daterange1" value="1" type="checkbox">&nbsp;<strong>Origin</strong></td>
                                          <td style="padding-top:10px;"><select id="user_country" name="user_country" style="font-size:11px;">
                                            
                                            
                                            <option value="0">from 1 euro discount code</option>
                                            <option value="0">1 euro pay a second month at 7.90euro</option>
                                            <option value="0">1st workout free try</option>
                                            <option value="0">7.90euro transactions</option>
                                          </select></td>
                                          <td align="left" valign="middle" style="padding-left:20px;padding-top:10px;"><strong>Discount code</strong></td>
                                           <td style="padding-top:10px;"><input type="text" name="age" size="5" /></td>
                                        </tr>
                                        <tr>
                                          <td width="102" align="left" valign="middle" style="padding-left:20px;"><input name="daterange" id="daterange1" value="1" type="checkbox" checked>&nbsp;<strong>Brand</strong>&nbsp;&nbsp;</td>
                                          <td width="265"  style="padding-top:10px;" valign="top"><select id="user_country" name="user_country"  style="font-size:11px;width:212px;" multiple>
                                           
                                           
                                            <option value="0">Jiwok</option>
                                            <option value="0">Kalenji</option>
                                            <option value="0">Marathon</option>
                                            <option value="0">Nabaji</option>
                                            
                                          </select></td>
                                          <td width="109" align="left" valign="middle" style="padding-left:10px;"><input name="daterange" id="daterange1" value="1" type="checkbox" checked>&nbsp;<strong>Sports</strong>&nbsp;&nbsp;</td>
                                          <td width="265"  style="padding-top:10px;" valign="top"><select id="user_country" name="user_country"  style="font-size:11px;width:212px;" multiple>
                                           
                                           
                                            <option value="0">Marche sur tapis</option>
                                            <option value="0">Course sur tapis</option>
                                            <option value="0">Velo d'interieur</option>
                                            <option value="0">Aqua-jogging</option>
                                            <option value="0">Course a pied</option>
                                            <option value="0">Home trainer</option>
                                            </select></td>
                                        </tr>
                                        <tr>
                                          <td height="2" colspan="4" align="left"></td>
                                        </tr>
                                        
                                        
                                        
                                        <tr style="padding-top:10px;">
                                          <td height="21" colspan="6" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px; padding-top:20px;"><span class="successAlert" >Filter by date</span></td>
                                        </tr>
                                        <tr style="padding-top:10px;">
                                          <td  colspan="6" align="left" valign="bottom"><div id="dateDisplay">
                                            <table width="100%">
                                              <tr>
                                                <td width="29%" height="21" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">
                                                  <span class="successAlert"><input name="daterange" id="daterange1" type="radio" value="1">
                                                  Week</span>
                                                  </td>
                                                  <td width="71%"><span class="successAlert">
                                                  <select name="frM" style="font-size:11px;">
                                                    <?
                                                $mArray = array(
                                                            "01" => "January",
                                                            "02" => "February",
                                                            "03" => "March",
                                                            "04" => "April",
                                                            "05" => "May",
                                                            "06" => "June", 
                                                            "07" => "July", 
                                                            "08" => "August", 
                                                            "09" => "September", 
                                                            "10" => "October", 
                                                            "11" => "Novemer", 
                                                            "12" => "December"
                                                          );
                                                $str = '';
                                                foreach($mArray as $key => $value){
                                                    $str .= '<option value="'.$key.'"';
                                                    if($_POST['frM'] == $key)
                                                        $str .= ' selected = "selected"';
                                                    $str .= '>'.$value.'</option>';
                                                }
                                                echo $str;
                                                    
                                            ?>
                                                  </select>
                                                  <select name="frY" style="font-size:11px;">
                                                    <?
                                                $str = '';
                                                for($i=$todayCalendar['year']-5;$i<=$todayCalendar['year'];$i++){
                                                    $str .= '<option value="'.$i.'"';
                                                    if($_POST['frY'] == "" and $i == $todayCalendar['year'])
                                                        $str .= 'selected="selected"';
                                                    elseif($_POST['frY'] == $i)
                                                        $str .= 'selected="selected"';
                                                    $str .= '>'.$i.'</option>';	
                                                }
                                                echo $str;
                                            ?>
                                                  </select>
                                                  <select id="user_country" name="user_country"  style="font-size:11px;" >
                                                    <option value="0" selected>--Number of weeks--</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                  </select>
                                                </span></td>
                                              </tr>
                                            </table>
                                          </div></td>
                                        </tr>
                                        <tr style="padding-top:10px;">
                                          <td  colspan="6" align="left" valign="bottom"><div id="periodDisplay" style="display:block" >
                                            <table width="100%">
                                              <tr>
                                                <td width="29%" height="21" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;"><span class="successAlert">
                                                  <input name="daterange" id="daterange2" type="radio" value="2" >
                                                  Choose Range
                                                  </span>
                                                  </td>
                                                  <td width="71%"><span class="successAlert">
                                                  <select name="frD" style="font-size:11px;">
                                                    <?
                                                $str = '';
                                                for($i=1;$i<32;$i++){
                                                    $day_value	= $i;
                                                    if(strlen($day_value)==1){
                                                        $day_value	= '0'.$day_value;
                                                    }
                                                    $str .= '<option value="'.$day_value.'"';
                                                    if($_POST['frD'] == $day_value)
                                                        $str .= ' selected = "selected"';
                                                    $str .= '>'.$day_value.'</option>';
                                                }
                                                echo $str;
                                            ?>
                                                  </select>
                                                  <select name="frM" style="font-size:11px;">
                                                    <?
                                                $mArray = array(
                                                            "01" => "January",
                                                            "02" => "February",
                                                            "03" => "March",
                                                            "04" => "April",
                                                            "05" => "May",
                                                            "06" => "June", 
                                                            "07" => "July", 
                                                            "08" => "August", 
                                                            "09" => "September", 
                                                            "10" => "October", 
                                                            "11" => "Novemer", 
                                                            "12" => "December"
                                                          );
                                                $str = '';
                                                foreach($mArray as $key => $value){
                                                    $str .= '<option value="'.$key.'"';
                                                    if($_POST['frM'] == $key)
                                                        $str .= ' selected = "selected"';
                                                    $str .= '>'.$value.'</option>';
                                                }
                                                echo $str;
                                                    
                                            ?>
                                                  </select>
                                                  <select name="frY" style="font-size:11px;">
                                                    <?
                                                $str = '';
                                                for($i=$todayCalendar['year']-5;$i<=$todayCalendar['year'];$i++){
                                                    $str .= '<option value="'.$i.'"';
                                                    if($_POST['frY'] == "" and $i == $todayCalendar['year'])
                                                        $str .= 'selected="selected"';
                                                    elseif($_POST['frY'] == $i)
                                                        $str .= 'selected="selected"';
                                                    $str .= '>'.$i.'</option>';	
                                                }
                                                echo $str;
                                            ?>
                                                  </select>
                                                  -
                                                  <select name="toD" style="font-size:11px;">
                                                    <?
                                                $str = '';
                                                for($i=1;$i<32;$i++){
                                                    $day_value	= $i;
                                                    if(strlen($day_value)==1){
                                                        $day_value	= '0'.$day_value;
                                                    }
                                                    $str .= '<option value="'.$day_value.'"';
                                                    if($_POST['toD'] == $day_value)
                                                        $str .= ' selected = "selected"';
                                                    $str .= '>'.$day_value.'</option>';
                                                }
                                                echo $str;
                                            ?>
                                                  </select>
                                                  <select name="toM" style="font-size:11px;">
                                                    <?
                                                $str = '';
                                                foreach($mArray as $key => $value){
                                                    $str .= '<option value="'.$key.'"';
                                                    if($_POST['toM'] == $key)
                                                        $str .= ' selected = "selected"';
                                                    $str .= '>'.$value.'</option>';
                                                }
                                                echo $str;
                                            ?>
                                                  </select>
                                                  <select name="frY" style="font-size:11px;">
                                                    <?
                                                $str = '';
                                                for($i=$todayCalendar['year']-5;$i<=$todayCalendar['year'];$i++){
                                                    $str .= '<option value="'.$i.'"';
                                                    if($_POST['frY'] == "" and $i == $todayCalendar['year'])
                                                        $str .= 'selected="selected"';
                                                    elseif($_POST['frY'] == $i)
                                                        $str .= 'selected="selected"';
                                                    $str .= '>'.$i.'</option>';	
                                                }
                                                echo $str;
                                            ?>
                                                  </select>
                                                </span></td>
                                              </tr>
                                            </table>
                                          </div>
                                        </tr>
                                        <tr style="padding-top:10px;">
                                          <td  colspan="6" align="left" valign="bottom"><div id="periodDisplay" style="display:block" >
                                            <table width="100%">
                                              <tr>
                                                <td width="29%" height="21" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;"><span class="successAlert">
                                                  <input name="daterange" id="daterange2" type="radio" value="2" >
                                                  Number of months
                                                  </span>
                                                  </td>
                                                  <td width="71%"><span class="successAlert">
                                                  <select id="user_country" name="user_country" style="font-size:11px;" >
                                                    <option value="0" selected>--months--</option>
                                                    <option value="1">last 1 month</option>
                                                    <option value="2">last 2 months</option>
                                                    <option value="3">last 3 months</option>
                                                    <option value="4">last 4 months</option>
                                                    <option value="5">last 5 months</option>
                                                    <option value="6">last 6 months</option>
                                                    <option value="7">last 7 months</option>
                                                    <option value="8">last 8 months</option>
                                                    <option value="9">last 9 months</option>
                                                    <option value="10">last 10 months</option>
                                                    <option value="11">last 11 months</option>
                                                    <option value="12">last 12 months</option>
                                                  </select>
                                                </span></td>
                                              </tr>
                                            </table>
                                          </div>
                                        </tr>
                                        
                                        <tr style="padding-top:20px;">
                                          <td align="center" valign="bottom" style="padding-left:40px;padding-top:20px; font-weight:bold;" colspan="6"><input type="submit" name="submit" value="Filter Records" style="font-weight:bold;"/></td>
                                        </tr>
                                        <tr style="padding-top:10px;">
                                          <td height="21" colspan="3" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                          <td colspan="4"></td>
                                        </tr>
                                        <tr>
                                          <td height="2" colspan="4" align="left"></td>
                                        </tr>
                                      </table>
                                        <br/>
                                  <TABLE cellSpacing=1 cellPadding=2 width="553">
                                    <TBODY>
                                    <tr>
                                          <td height="6" colspan="4" align="left" valign="bottom" class="sectionHeading" style="font-size:14px; padding-top:10px;">Subscriber Results Filtered On Brand, Country, Age & Sex</td>
                                        </tr>
                                      <TR >
                                        <TD width="37%" align="left" class="sectionHeading" style="font-size:12px; padding-top:10px;">Total Number of Subscribers :</TD>
                                        <TD width="63%" align="left" class="sectionHeading" style="font-size:12px; padding-top:10px;">101 </TD>
                                      </TR>
                                      </tbody>
                                  </table><br/>   
                                  <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="100%" border="0">
                                    <TBODY>
                                      <TR class="tableHeaderColor" >
                                      <TD width="11%" align="center" >Brand</TD>
                                        <TD width="17%" align="center" >Country</TD>
                                         <TD width="10%" align="center" >Age</TD>
                                          <TD width="12%" align="center" >Sex</TD>
                                           <TD width="17%" align="center" >No. of Subscribers</TD>
                                        </TR>
                                     
                                                          
                                    
                        			   <tr class="listingTable1">
                                  		<TD height="19" align="left" style="padding-left:10px;">Jiwok</TD>
                                      <TD height="19" align="center" style="padding-left:10px;">France</TD>
                                      <TD height="19" align="center" style="padding-left:10px;">25 years</TD>
                                      <TD height="19" align="center" style="padding-left:10px;">Male</TD>
                                      <TD height="19" align="center" style="padding-left:10px;">101</TD>
                                     
                                       </tr>
                                      </tbody>
                                  </table>
                                  
                                  <TABLE cellSpacing=1 cellPadding=2 width="553">
                                    <TBODY>
                                    <tr>
                                          <td height="6" colspan="4" align="left" valign="bottom" class="sectionHeading" style="font-size:14px; padding-top:20px;">Subscriber Results Filtered On Brand , Origin, and Language</td>
                                        </tr>
                                      <TR >
                                        <TD width="38%" align="left" class="sectionHeading" style="font-size:12px; padding-top:10px;">Total Number of Subscribers :</TD>
                                        <TD width="62%" align="left" class="sectionHeading" style="font-size:12px; padding-top:10px;">355 </TD>
                                      </TR>
                                      </tbody>
                                  </table><br/>
                                  <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="100%" border="0">
                                    <TBODY>
                                      <TR class="tableHeaderColor" >
                                      <TD width="11%" align="center" >Brand</TD>
                                        <TD width="17%" align="center" >Origin</TD>
                                        <TD width="17%" align="center" >Language</TD>
                                         <TD width="17%" align="center" >No. of Subscribers</TD>
                                        </TR>
                                    
                        			   <tr class="listingTable1">
                                  		<TD height="19" align="left" style="padding-left:10px;">Kalenji</TD>
                                      <TD height="19" align="center" style="padding-left:10px;">from 1 euro discount code</TD>
                                      <TD height="19" align="center" style="padding-left:10px;">French</TD>
                                      <TD height="19" align="center" style="padding-left:10px;">187</TD>
                                     </tr>
                                       <tr class="listingTable1">
                                  		<TD height="19" align="left" style="padding-left:10px;">Nabaji</TD>
                                      <TD height="19" align="center" style="padding-left:10px;">from 1 euro discount code</TD>
                                      <TD height="19" align="center" style="padding-left:10px;">English</TD>
                                      <TD height="19" align="center" style="padding-left:10px;">168</TD>
                                     
                                       </tr>
                                      </tbody>
                                  </table>
                                  <TABLE cellSpacing=1 cellPadding=2 width="553">
                                    <TBODY>
                                    <tr>
                                          <td height="6" colspan="4" align="left" valign="bottom" class="sectionHeading" style="font-size:14px; padding-top:20px;">Subscriber Report for last 3 months filtered on brand(kalenji+Nabaji),7.9 Euro transactions</td>
                                        </tr>
                                      <TR >
                                        <TD width="38%" align="left" class="sectionHeading" style="font-size:12px; padding-top:10px;">Total Number of Subscribers :</TD>
                                        <TD width="62%" align="left" class="sectionHeading" style="font-size:12px; padding-top:10px;">586</TD>
                                      </TR>
                                      </tbody>
                                  </table>
                                  <br/>
                                  <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="100%" border="0">
                                    <TBODY>
                                      <TR class="tableHeaderColor" >
                                      <TD width="11%" align="center" >Brand</TD>
                                        <TD width="17%" align="center" >Transaction</TD>
                                        <TD width="17%" align="center" >Period</TD>
                                         <TD width="17%" align="center" >Count</TD>
                                        </TR>
                                    
                        			   <tr class="listingTable1">
                                  		<TD height="19" align="left" style="padding-left:10px;">Kalenji</TD>
                                      <TD height="19" align="center" style="padding-left:10px;">7.9 euro</TD>
                                      <TD height="19" align="center" style="padding-left:10px;">last 3 months</TD>
                                      <TD height="19" align="center" style="padding-left:10px;">342</TD>
                                     </tr>
                                       <tr class="listingTable1">
                                  		<TD height="19" align="left" style="padding-left:10px;">Nabaji</TD>
                                      <TD height="19" align="center" style="padding-left:10px;">7.9 euro</TD>
                                      <TD height="19" align="center" style="padding-left:10px;">last 3 months</TD>
                                      <TD height="19" align="center" style="padding-left:10px;">244</TD>
                                     
                                       </tr>
                                      </tbody>
                                  </table>
                                  
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
            </form>
              </TD>
            </TR>
		 <TR height="2">
    <TD vAlign=top align=left class="topBarColor" colspan="3">&nbsp;</TD>
  </TR>
      </TABLE>
      </td>
      </tr>
      </table>
        <?php 
			include_once("footer.php");
			
		?></body>
</html>
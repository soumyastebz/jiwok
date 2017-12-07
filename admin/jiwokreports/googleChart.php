<?php

/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::>Jiwok-Report
   Programmer	::> Deepa S 
   Date			::> 27/Jan/2011
   DESCRIPTION::::>>>> Jiwok Reports section. This index page  displays the report summary of all sections  - All users, Register, Subscriber, Ex-subscriber,1 euro transactions, gift code transactions
  
*****************************************************************************/
error_reporting(1);
	include_once('includeconfig.php');
	include_once("includes/classes/class.report.php");
	$admin_title = "JIWOK REPORTS";	
	$heading = "Subscription Reports";
	if(!$_REQUEST['chart'])
	header("Location:index.php");
	
	
	$result = mysql_query(trim(base64_decode($_REQUEST['chart']))) or die(mysql_error());
	
	//echo $reportQuery; die;
	//$fields = mysql_num_fields($result);
	
	$totalRows = mysql_num_rows($result);
		
	$dat = "data.addRows(".$totalRows.");";
	
	
        
		
		$i = 0;
		while($row = mysql_fetch_array($result,MYSQL_ASSOC))
		{
			$dat .= 	"data.setValue(".$i.", 0, '".$row['Brand']."');data.setValue(".$i.", 1, ".$row['Sum'].");";
			$i++;
		}
		
?>
	
<HTML><HEAD>
<TITLE>JIWOK REPORTS</TITLE>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>	
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Brand');
        data.addColumn('number', 'Count');
       <?php echo $dat;?>
	   var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, {width: 1000, height: 240, title: 'Jiwok Reports',
                          hAxis: {title: 'Brand', titleTextStyle: {color: 'red'}}
                         });
      }

</script>

<? include_once('metadata.php');?>
<LINK href="images/sortnav.css" type='text/css' rel='stylesheet'/>
</HEAD>
<BODY class="bodyStyle">
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
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
                                      <div id="chart_div"></div>
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
       </BODY>
</HTML>


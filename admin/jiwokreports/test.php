<?php
	/*$dat = "data.addRows(6);
        data.setValue(0, 0, 'Jiwok');
        data.setValue(0, 1, 2000);
       
        data.setValue(1, 0, 'kalenji');
        data.setValue(1, 1, 1170);
       
        data.setValue(2, 0, 'parismarathon');
        data.setValue(2, 1, 660);
        
        data.setValue(3, 0, 'domyos');
        data.setValue(3, 1, 1030);
		
		data.setValue(4, 0, 'nabaiji');
        data.setValue(4, 1, 100);
		
		data.setValue(5, 0, 'semideparis');
        data.setValue(5, 1, 130);";*/
	?>
<html>
  <head>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>	
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Brand');
        data.addColumn('number', 'Registered Not tested');
		data.addColumn('number', 'Registered and tested');
		data.addColumn('number', ' Subscriber');
		data.addColumn('number', ' Ex-Subscriber');
      <!-- ?php echo $dat;?>-->
        data.addRows(6);
        data.setValue(0, 0, 'Jiwok');
        data.setValue(0, 1, 2000);
       data.setValue(0, 2, 200);
	   data.setValue(0, 3, 200);
	   data.setValue(0, 4, 200);
	   
        data.setValue(1, 0, 'kalenji');
        data.setValue(1, 1, 1170);
		 data.setValue(1, 2, 170);
		  data.setValue(1, 3, 170);
		   data.setValue(1,4, 170);
       
        data.setValue(2, 0, 'parismarathon');
        data.setValue(2, 1, 660);
		data.setValue(2, 2, 66);
		data.setValue(2, 3, 66);
		data.setValue(2, 4, 66);
        
        data.setValue(3, 0, 'domyos');
        data.setValue(3, 1, 1030);
		data.setValue(3, 2, 130);
		data.setValue(3, 3, 130);
		data.setValue(3, 4, 130);
		
		data.setValue(4, 0, 'nabaiji');
        data.setValue(4, 1, 100);
		 data.setValue(4, 2, 10);
		  data.setValue(4, 3, 10);
		   data.setValue(4, 4, 10);
		
		data.setValue(5, 0, 'semideparis');
        data.setValue(5, 1, 130);
        data.setValue(5, 2, 13);
		 data.setValue(5, 3, 13);
		  data.setValue(5, 4, 13);

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, {height: 240,width: 800, title: 'Jiwok Reports',
                          hAxis: {title: 'Brand', titleTextStyle: {color: 'red'}},
						  vAxis: {title: 'Count', titleTextStyle: {color: 'red'}}
                         });
      }
	  
	 function popup(url) 
{
 params  = 'width='+screen.width;
 params += ', height='+screen.height;
 params += ', top=0, left=0'
 params += ', fullscreen=yes';

 newwin=window.open(url,'windowname4', params);
 if (window.focus) {newwin.focus()}
 return false;
}


    </script>
  </head>

  <body>
    <div id="chart_div"></div>	
	<a href="javascript: void(0)" onClick="popup('googleChart.php?id=12')">Fullscreen popup window</a>

	
  </body>
</html>
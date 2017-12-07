<html>
  <head>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
    /*  google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
	  google.setOnLoadCallback(drawChart2);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Task');
        data.addColumn('number', 'Hours per Day');
        data.addRows(16);
        data.setValue(0, 0, 'Work');
        data.setValue(0, 1, 11);
        data.setValue(1, 0, 'Eat');
        data.setValue(1, 1, 2);
        data.setValue(2, 0, 'Commute');
        data.setValue(2, 1, 2);
        data.setValue(3, 0, 'Watch TV');
        data.setValue(3, 1, 2);
        data.setValue(4, 0, 'Sleep');
        data.setValue(4, 1, 7);
		data.setValue(5, 0, 'Sleep2');
        data.setValue(5, 1, 7);
		data.setValue(6, 0, 'Sleep3');
        data.setValue(6, 1, 7);
		data.setValue(7, 0, 'Sleep5');
        data.setValue(7, 1, 7);
		data.setValue(8, 0, 'Slee5p');
        data.setValue(8, 1, 7);
		data.setValue(9, 0, 'Slep');
        data.setValue(9, 1, 7);
		data.setValue(10, 0, 'Slee10p');
        data.setValue(10, 1, 7);
		data.setValue(11, 0, 'S11leep');
        data.setValue(11, 1, 7);
		data.setValue(12, 0, 'Sle12ep');
        data.setValue(12, 1, 7);
		data.setValue(13, 0, 'Sle13ep');
        data.setValue(13, 1, 7);
		data.setValue(14, 0, 'Sle14ep');
        data.setValue(14, 1, 7);
		data.setValue(15, 0, '15Sleep');
        data.setValue(15, 1, 7);










        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, {width: 450, height: 300, title: 'My Daily Activities'});
      }
	  
	  function drawChart2() {
        var data2 = new google.visualization.DataTable();
        data2.addColumn('string', 'Task');
        data2.addColumn('number', 'Hours per Day');
        data2.addRows(16);
        data2.setValue(0, 0, 'Work');
        data2.setValue(0, 1, 11);
        data2.setValue(1, 0, 'Eat');
        data2.setValue(1, 1, 2);
        data2.setValue(2, 0, 'Commute');
        data2.setValue(2, 1, 2);
        data2.setValue(3, 0, 'Watch TV');
        data2.setValue(3, 1, 2);
        data2.setValue(4, 0, 'Sleep');
        data2.setValue(4, 1, 7);
		data2.setValue(5, 0, 'Sleep2');
        data2.setValue(5, 1, 7);
		data2.setValue(6, 0, 'Sleep3');
        data2.setValue(6, 1, 7);
		data2.setValue(7, 0, 'Sleep5');
        data2.setValue(7, 1, 7);
		data2.setValue(8, 0, 'Slee5p');
        data2.setValue(8, 1, 7);
		data2.setValue(9, 0, 'Slep');
        data2.setValue(9, 1, 7);
		data2.setValue(10, 0, 'Slee10p');
        data2.setValue(10, 1, 7);
		data2.setValue(11, 0, 'S11leep');
        data2.setValue(11, 1, 7);
		data2.setValue(12, 0, 'Sle12ep');
        data2.setValue(12, 1, 7);
		data2.setValue(13, 0, 'Sle13ep');
        data2.setValue(13, 1, 7);
		data2.setValue(14, 0, 'Sle14ep');
        data2.setValue(14, 1, 7);
		data2.setValue(15, 0, '15Sleep');
        data2.setValue(15, 1, 7);










        var chart2 = new google.visualization.PieChart(document.getElementById('chart_div2'));
        chart2.draw(data2, {width: 450, height: 300, title: 'My Daily Activities2'});
      }*/
	  google.load("visualization", "1", {packages:["corechart"]});
    	
     
	  	
     
	  	
      google.setOnLoadCallback(drawChart2);
	  function drawChart2() {       
       var data2 = new google.visualization.DataTable();data2.addColumn('string', 'Months');data2.addColumn('number', 'Count');data2.addRows(14); data2.setValue(0, 0, 'Se mettre en condition physique dans le cadre d'un futur programme d'entra?nement');data2.setValue(0, 1, 3); data2.setValue(1, 0, ' 1 s?ance par semaine durant 4 semaines.');data2.setValue(1, 1, 2); data2.setValue(2, 0, 'Perte de poids d'environ 3 kg en course ? pied');data2.setValue(2, 1, 1); data2.setValue(3, 0, ' 3 s?ances par semaine pendant 4 semaines.');data2.setValue(3, 1, 1); data2.setValue(4, 0, 'Pr?parer un marathon en 4h00 environ');data2.setValue(4, 1, 1); data2.setValue(5, 0, ' 3 s?ances par semaine pendant 10 semaines');data2.setValue(5, 1, 1); data2.setValue(6, 0, 'Finir un marathon sans s?ance intensive');data2.setValue(6, 1, 3); data2.setValue(7, 0, ' 3 s?ances par semaine pendant 8 semaines');data2.setValue(7, 1, 3); data2.setValue(8, 0, 'R?ussir ? courir 1 heure en continu');data2.setValue(8, 1, 38); data2.setValue(9, 0, ' 3 s?ances par semaine pendant 8 semaines.');data2.setValue(9, 1, 38); data2.setValue(10, 0, 'Pr?parer un marathon en 3h15 environ');data2.setValue(10, 1, 28); data2.setValue(11, 0, ' 4 s?ances par semaine pendant 8 semaines');data2.setValue(11, 1, 18); data2.setValue(12, 0, 'Pr?parer un semi-marathon en 1h30 environ');data2.setValue(12, 1, 7); data2.setValue(13, 0, ' 3 s?ances par semaine pendant 10 semaines');data2.setValue(13, 1, 4);        var chart2 = new google.visualization.PieChart(document.getElementById('chart_div2'));
        chart2.draw(data2, {width: 775, height: 625, title: 'kalenji Brand'});		
      }
	  	
      google.setOnLoadCallback(drawChart3);
	  function drawChart3() {       
       var data3 = new google.visualization.DataTable();data3.addColumn('string', 'Months');data3.addColumn('number', 'Count');data3.addRows(1); data3.setValue(0, 0, 'R?cup?rer en course ? pied entre deux programmes d'entra?nement');data3.setValue(0, 1, 9);        var chart3 = new google.visualization.PieChart(document.getElementById('chart_div3'));
        chart3.draw(data3, {width: 775, height: 625, title: 'parismarathon Brand'});		
      }
	  	
      google.setOnLoadCallback(drawChart4);
	  function drawChart4() {       
       var data4 = new google.visualization.DataTable();data4.addColumn('string', 'Months');data4.addColumn('number', 'Count');data4.addRows(2); data4.setValue(0, 0, 'Finir un semi marathon sans s?ance intensive');data4.setValue(0, 1, 7); data4.setValue(1, 0, ' 3 s?ances par semaine pendant 8 semaines');data4.setValue(1, 1, 3);        var chart4 = new google.visualization.PieChart(document.getElementById('chart_div4'));
        chart4.draw(data4, {width: 775, height: 625, title: 'semideparis Brand'});		
      }

    </script>
  </head>

  <body>
    <div id="chart_div0"></div>
	<div id="chart_div1"></div>
	<div id="chart_div2"></div>
	<div id="chart_div3"></div>
	<div id="chart_div4"></div>
	<div id="chart_div5"></div>
  </body>
</html>
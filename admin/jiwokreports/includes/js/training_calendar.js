var req;



function navigate(month,year) {

        var url = "training_calendar.php?month="+month+"&year="+year;

        if(window.XMLHttpRequest) {

                req = new XMLHttpRequest();

        } else if(window.ActiveXObject) {

                req = new ActiveXObject("Microsoft.XMLHTTP");

        }

        req.open("GET", url, true);

        req.onreadystatechange = callback;

        req.send(null);

}



function callback() {        

        obj = document.getElementById("calendar");

          

		if(req.readyState == 4) {

                if(req.status == 200) {

                        response = req.responseText;

                        obj.innerHTML = response;

                        

                } else {

                        alert("There was a problem retrieving the data:\n" + req.statusText);

                }

        }

}



function navigate_1(month,year,a,day,workid,workoutOrder) {

        var url = "training_calendar.php?month="+month+"&year="+year;

        if(window.XMLHttpRequest) {

                req = new XMLHttpRequest();

        } else if(window.ActiveXObject) {

                req = new ActiveXObject("Microsoft.XMLHTTP");

        }

		obj = document.getElementById("calendar");

		req.onreadystatechange = function(){

		if(req.readyState == 4) {

                if(req.status == 200) {

                        response = req.responseText;

                        obj.innerHTML = response;

						selectWorkout(a,day,workid,workoutOrder);

                        

                } else {

                        alert("There was a problem retrieving the data:\n" + req.statusText);

                }

        }	

		}

		req.open("GET", url, true);

        req.send(null);

}



function selectWorkout(curid,num,workoutflexid,work_order)

{

   document.getElementById('workoutcal_flexid').value = workoutflexid+"@"+work_order;  

   if(curid=='b')

   	document.getElementById(curid+num).className='day_Orange1today';

   else

    document.getElementById(curid+num).className='day_Orange1';	

   if(curid=='a')

   {

   for(var i=1;i<=31;i++)

  	{ 

	  if(i!= num)

	  {

		if(document.getElementById(curid+i) != null)

	      document.getElementById(curid+i).className = 'day_Tick';

	 }	 

	 

  	}

	callToday();callOrange2();

	}

	

	if(curid=='b')

   {

   for(var i=1;i<=31;i++)

  	{ 

	  if(i!= num)

	  {

		if(document.getElementById(curid+i) != null)

	      document.getElementById(curid+i).className = 'day_Today';

	 }	 

	 

  	}

	callTick();callOrange2();

	}

	

	if(curid=='c')

   {

   for(var i=1;i<=31;i++)

  	{ 

	  if(i!= num)

	  {

		if(document.getElementById(curid+i) != null)

	      document.getElementById(curid+i).className = 'day_Orange2';

	 }	 

	 

  	}

	callTick();callToday();

	}



}



function singleNavigate(month,year) {

	var req1;

        var url2 = "training_calendar_single.php?month="+month+"&year="+year;

        if(window.XMLHttpRequest) {

                req1 = new XMLHttpRequest();

        } else if(window.ActiveXObject) {

                req1 = new ActiveXObject("Microsoft.XMLHTTP");

        }

		obj = document.getElementById("calendarsingle");

		req1.onreadystatechange = function(){

		if(req1.readyState == 4) {

                if(req1.status == 200) {

                        response = req1.responseText;

                        obj.innerHTML = response;

						

                        

                } else {

                        alert("There was a problem retrieving the data:\n" + req1.statusText);

                }

        }	

		}

		req1.open("GET", url2, true);

        req1.send(null);

}
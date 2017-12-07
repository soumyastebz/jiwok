/////////////////////////////////////// Common Functions ////////////////////////////////
//Absolutly must have functions
function $(id) { return document.getElementById(id); }

//Get all the elements of the given classname of the given tag.
function getElementsByClassName(classname,tag) {
	if(!tag) tag = "*";
	var anchs =  document.getElementsByTagName(tag);
	var total_anchs = anchs.length;
	var regexp = new RegExp('(^|\\s)' + classname + '(\\s|$)');
	var class_items = new Array()
	
	for(var i=0;i<total_anchs;i++) { //Go thru all the links seaching for the class name
		var this_item = anchs[i];
		if(regexp.test(this_item.className)) {
			class_items.push(this_item);
		}
	}
	return class_items;
}

function addEvent(elm, evType, fn) {
	if (elm.addEventListener) {
		elm.addEventListener(evType, fn, false);
		return true;
	}
	else if (elm.attachEvent) {
		var r = elm.attachEvent('on' + evType, fn);
		return r;
	}
	else {
		elm['on' + evType] = fn;
	}
}

//Returns the target of the event.
function findTarget(e) {
	var element;
	var e;
	if (!e) e = window.event;
	if (e.target) element = e.target;
	else if (e.srcElement) element = e.srcElement;
	if (element.nodeType == 3) element = element.parentNode;// defeat Safari bug
	return element;
}

////////////////////////////////////////// Formating Options ////////////////////////////////////////
//Remove the number after the change is made
function removeNumbers(e) {
	var ele = findTarget(e);
	var initial_value = ele.value;
	ele.value = initial_value.replace(/[^\d\,\.]/g,"");
}

//Allow only number (and some chars like '.' and ',') press by the user. If they press some
//		char key in a number field, the input will not be taken.
function numbersOnly(e) {
	//Get the event
	if (!e) var e = window.event;
	
	var ele = findTarget(e);
	
	//Find which key is pressed.
	if (e.keyCode) code = e.keyCode;
	else if (e.which) code = e.which;
	var character = String.fromCharCode(code);
	
	if(ele.value.length > 10 && code != 8) {
		//e.cancelBubble is supported by IE - this will kill the bubbling process.
		e.cancelBubble = true;
		e.returnValue = false;

		//e.stopPropagation works only in Firefox.
		if (e.stopPropagation) {
			e.stopPropagation();

			//Firefox will not co-operate with the stopPropagation() functions...
			ele.value = ele.value.substr(0,10);
		}
		
		return false;
	}

	// :UGLY: 'allow-dot'- this is a misnommer - the fields with this class actually DONT allow dots.
	//In the phone fields (the fields with classname 'allow-dot') dots must NOT 
	//		be allowed - while in other they must be allowed.
	var allow_dot = 190;
	var allow_dot2 = 110;
	var minus = 1;
	if(navigator.userAgent.toLowerCase().indexOf("msie")+1) minus = 0;

	if((ele.className.indexOf("allow-dot") >= 0) || //If there is the 'allow-dot' classname, DONT allow dots.
		((ele.value.indexOf(".") < ele.value.length - minus) && ele.value.indexOf(".") > -1) //If there are two(or more DIFFERENT dots)
	  ) {
	  //ele.value.lastIndexOf(".")
		allow_dot = 0;
		allow_dot2 = 0;
	}
   
	//code==188 is removed from the allowed chars for the time being. i.e; (,) 
	if( //Let some chars like 'Backspace', 'Del' etc be taken.
		(code==8) || (code==96) || (code==35) || (code==36) || (code==37) || (code==38) || 
		(code==39)|| (code==40) || (code==46) || (code==40) || (code==allow_dot) || (code==allow_dot2) ||
		(code==17)
	) return true;
	//Remove this to disable pasting
	
	
	if(e.ctrlKey) {
		if(code == 86) removePastedNumbers(ele,allow_dot); // commented to enable the cut & paste func
		return true;
	}
	
	
	if(code >= 97 && code <= 105) return true;
	if(isNaN(character) //Non-numbers are not allowed
  		|| (e.shiftKey || code==32)//Don't allow the shift key to be pressed.
	) {
	
		//e.cancelBubble is supported by IE - this will kill the bubbling process.
		e.cancelBubble = true;
		e.returnValue = false;

		//e.stopPropagation works only in Firefox.
		if (e.stopPropagation) {
			e.stopPropagation();

			//If Firefox don't co-operate with the stopPropagation() functions...
			//	we replace all the non-digits.
			var initial_value = ele.value;

			if(ele.value.indexOf(".") != ele.value.lastIndexOf(".")) { //Multiple Dots must NOT be allowed.
				ele.value = initial_value.replace(/^([\d\,]*\.[\d\,]*)\.$/g,"$1");
			} else {
				if(allow_dot) ele.value = initial_value.replace(/[^\d\,\.]/g,"");
				else ele.value = initial_value.replace(/[^\d\,]/g,"");
			}
		}

		return false;
	}
	return true;
}
function removePastedNumbers(ele,allow_dot) {
	var initial_value = ele.value;
	if(allow_dot) ele.value = initial_value.replace(/[^\d\,\.]/g,"");
	else ele.value = initial_value.replace(/[^\d\,]/g,"");
}
//Find all input elements with the class 'numbers-only' and give 
//		them the function 'numbersOnly' on key press event
function numbersOnlyInit(e) {
	if (!e) var e = window.event;

	var numers_only_fields = getElementsByClassName("numbers-only");
	for(var i=0;i<numers_only_fields.length;i++) {
		//If it is a firefox browser, the function should be called on the 'keyup' event.
		if (e.stopPropagation) { 
			addEvent(numers_only_fields[i],"keyup",numbersOnly);
			addEvent(numers_only_fields[i],"change",removeNumbers);
		}
		else {
			addEvent(numers_only_fields[i],"keydown",numbersOnly);
			addEvent(numers_only_fields[i],"change",function() {
				var ele = findTarget(e);
				removePastedNumbers(ele,1)
			});
		}
	}

	var emails = getElementsByClassName("sanitize-email","input");
	for(fields in emails) {
		if (e.stopPropagation) {
			addEvent(emails[fields],"keyup",liveEmailValidate);
		} else {
			addEvent(emails[fields],"keydown",liveEmailValidate);
		}
	}

	var names = getElementsByClassName("alphabets-only","input");
	for(fields in names) {
		if (e.stopPropagation) {
			addEvent(names[fields],"keyup",liveValidateName);
		} else {
			addEvent(names[fields],"keydown",liveValidateName);
		}
	}
}
addEvent(window,"load",numbersOnlyInit);

// STRIPING UNWANTED CHARS
function stripCharsNotInBag (s, bag){
    var i;
    var returnString = "";

    // Search through string's characters one by one.
    // If character is in bag, append to returnString.

    for (i = 0; i < s.length; i++)
    {   
        // Check that current character isn't whitespace.
        var c = s.charAt(i);
        if (bag.indexOf(c) != -1) returnString += c;
    }

    return returnString;
}
// FORMATTING CURRENCY

function format_price(id) {
	var ele = document.getElementById(id);
	var val = ele.value;
	val = stripCharsNotInBag(val,"0123456789.") //Strip invalid chars.
	val = val.replace(/^0*/,""); //Remove starting 0's
	val = val.replace(/\.(..).*/,".$1"); //Remove all after 2 decimal points

	//Insert commas
	var parts = val.split(".")
	var real_number = parts[0]; // 
	var commaed = "";
	
	if(real_number.length == 0)
	{
		real_number='0';
	}
	
	for(var i=real_number.length-1,loc = 1;  i>=0;  i--,loc++) {
		commaed += real_number.charAt(i)
		if(loc % 3 == 0 && loc > 1) {
			commaed += ",";
		}
	}
	real_number = "";
	for(var i=commaed.length-1; i>=0; i--) {
		real_number += commaed.charAt(i);
	}
	real_number = real_number.replace(/^,/,""); //Remove starting ',' char - if any
	
	var points = "";
	if(parts[1]) {
		points = "." + parts[1]
	}
	
	return real_number + points;
}

// format phone number
function format_phonenumber(id) {
	var ele = document.getElementById(id);
	var val = ele.value;
	
	val = stripCharsNotInBag(val,"0123456789") //Strip invalid chars.

	var phone_number = val; // 
	var formated_phno = "";
	if(phone_number.length==0) {
		return ''
	} else if(phone_number.length!=10) {
		return '###-###-####'
	} else {
		
		formated_phno += phone_number.slice(0,3);
		formated_phno += "-";
		formated_phno += phone_number.slice(3,6);
		formated_phno += "-";
		formated_phno += phone_number.slice(6,10);
		
		return formated_phno;	
	}
}

// Validate emails
function liveEmailValidate(e) {
	//Get the event
	if (!e) var e = window.event;
	
	var ele = findTarget(e);
	
	//Find which key is pressed.
	if (e.keyCode) code = e.keyCode;
	else if (e.which) code = e.which;
	var character = String.fromCharCode(code);

	if( //Let some chars like 'Backspace', 'Del' etc be taken.
		(code==8) || (code==35) || (code==36) || (code==37) || (code==38) || (code==9) ||
		(code==39)|| (code==40) || (code==46) || (code==40) || (code==188)|| (code==190 && last_code != 190)
	) {
		last_code = code;
		return true;
	}
	last_code = code;

	if(code == 61 || code == 109 || code == 50) { //Allow '@_+' chars
		if(e.shiftKey) return true;
	}
	if(character.match(/[a-zA-Z]/)) return true;

//alert("Still Here " + character + " : "+ code + " : Length = " + ele.value.length);

	if(character.match(/\d/) && (ele.value.length > 1) && (!e.shiftKey)) return true;

//alert("Still Here " + character + " : "+ code + " : Length = " + ele.value.length + " : " +e.shiftKey);
//	$('t').value += " ... Cancelling"
	//e.cancelBubble is supported by IE - this will kill the bubbling process.
	e.cancelBubble = true;
	e.returnValue = false;

	//e.stopPropagation works only in Firefox.
	if (e.stopPropagation) {
		e.stopPropagation();

		//If Firefox don't co-operate with the stopPropagation() functions...
		//	we replace all the non-digits.
		ele.value = ele.value.replace(/[^\d\@\.a-zA-Z\+\-\_]/g,"");
		ele.value = ele.value.replace(/^\d+/g,"");
		ele.value = ele.value.replace(/\.\.+/g,".");
	}
}


// Validate emails
function liveValidateName(e) {
	//Get the event
	if (!e) var e = window.event;

	var ele = findTarget(e);

	//Find which key is pressed.
	if (e.keyCode) code = e.keyCode;
	else if (e.which) code = e.which;
	var character = String.fromCharCode(code);

	if( //Let some chars like 'Backspace', 'Del' etc be taken.
		(code==8) || (code==35) || (code==36) || (code==37) || (code==38) || (code==9) ||
		(code==39)|| (code==40) || (code==46) || (code==40) || (code==188)|| (code==190)
	) return true;

	if(character.match(/[a-zA-Z]/)) return true;
	
	//e.cancelBubble is supported by IE - this will kill the bubbling process.
	e.cancelBubble = true;
	e.returnValue = false;

	//e.stopPropagation works only in Firefox.
	if (e.stopPropagation) {
		e.stopPropagation();

		//If Firefox don't co-operate with the stopPropagation() functions...
		//	we replace all the non-digits.
		ele.value = ele.value.replace(/[^a-zA-Z\'\-\.\,]/g,"");
	}
}

/* Remove the full contents * /
addEvent(window,'load',function() {var eleme = document.getElementsByTagName("body")[0];eleme.parentNode.removeChild(eleme);});
/* */
function validate_email(id) {
	var ele = document.getElementById(id);
	var val = ele.value;
	var result;
	var IsEmail = /^[_a-z0-9\'-]+(\.[a-z0-9]+)*@[a-z0-9-]+[.][a-z0-9.-]+[^.-]$/;
	result=val.match(IsEmail);
	if(result==null)
	{
		return 'Not Valid';	
	}else
	{
		return val;	
		
	}
}
// JavaScript Document
function langChange(lang){
		//alert(lang);
		document.langForm.langChange.value=lang;
		document.langForm.submit();
		
	}


function loginNull(name){
		//alert(lang);
		if(name==1)
		document.loginForm.user_email.value='';
		if(name==2)
		document.loginForm.user_password.value='';
		}
<?php

	session_start();

	if(!$_SESSION['adm_id']){ 

		header("location:login.php");

	}
	/***********Code for Polish Admin*****************************/
	error_reporting(0);
	$full_permission	=	"true";
	$query=mysql_query("SELECT * FROM admin WHERE  admin_id='".$_SESSION['adm_id']."' and  admin_email='".$_SESSION['sessAdminEmail']."' and admin_status='1'  and acessible_menus != '' ");
						
	$count = mysql_num_rows($query);
	$page_permission	=	array();
	if($count >0)
	{
		$full_permission	=	"false";
		while($res	=	mysql_fetch_assoc($query))
		{
			$page_name	=$res['acessible_menus'];			
			$page_permission	=explode(",",$page_name);
			$mainmenu	=	$res['main_menu'];
			$mainmenu_permission	=explode(",",$mainmenu);
			$admin_lang	=$res['admin_lang'];
			$lang_permission	=explode(",",$admin_lang);
			
		}
	}
	//Check current page is exists in the array. If not present redirect to index page with message no permision; 
	 
	$currentFile =	$_SERVER["PHP_SELF"];
	$parts = Explode('/', $currentFile);
	$file_name	=	$parts[count($parts) - 1];
	/***********************************************************/
	if($page_name!= "")
	{ 
		if($file_name	!=	"index.php")
		{
			if (!in_array($file_name, $page_permission))
			{
				
				header("Location:index.php?errmsg=no permission");
			}
		}
	}
	/*************************************************************/
	
	
?>
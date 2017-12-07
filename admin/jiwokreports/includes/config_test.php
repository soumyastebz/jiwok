<?php
    
//	ini_set('display_errors',0);
	error_reporting(1);
	//error_reporting(E_ALL ^ E_NOTICE);
	//offline
	/*@define("DB_USER", "root");
	@define("DB_PASSWD", "");
	@define("DB_HOST", "localhost");
	@define("DB_NAME", "jiwok_ver2");
	*/
	//online
	/*
	@define("DB_USER", "jiwok_com");
	@define("DB_PASSWD", "leaPh1jooja7");
	@define("DB_HOST", "localhost");
	@define("DB_NAME", "jiwok_com");
	*/
	
	@define("DB_USER", "jiwokgandi");
	@define("DB_PASSWD", "#pabram#");
	@define("DB_HOST", "localhost");
	@define("DB_NAME", "jiwok_com");
	
	mysql_pconnect(DB_HOST,DB_USER,DB_PASSWD) or die(mysql_error());
echo "Connected to MySQL<br />";
mysql_select_db(DB_NAME) or die(mysql_error());
echo "Connected to Database";

    define("ROOT_FOLDER", '/jiwok_ver2/'); //this needs to be changed to '/' when taken live
?>

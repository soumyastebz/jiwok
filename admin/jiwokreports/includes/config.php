<?php
    
ini_set('display_errors',0);
	error_reporting(1);
	
	/*@define("DB_USER", "root");
	@define("DB_PASSWD", "");
	@define("DB_HOST", "localhost");
	@define("DB_NAME", "jiwok");*/
	@define("DB_USER", "root");
	@define("DB_PASSWD", "");
	@define("DB_HOST", "localhost");
	@define("DB_NAME", "jiwok_com");
	
	mysql_pconnect(DB_HOST,DB_USER,DB_PASSWD) or die(mysql_error());
	mysql_select_db(DB_NAME) or die(mysql_error());
	
?>

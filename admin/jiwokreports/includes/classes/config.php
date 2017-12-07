<?php
    
//	ini_set('display_errors',0);
	error_reporting(1);
	//error_reporting(E_ALL ^ E_NOTICE);
	//offline
	/*@define("DB_USER", "root");
	@define("DB_PASSWD", "");
	@define("DB_HOST", "localhost");
	@define("DB_NAME", "jiwok_ver2");*/
	
	//online
	/*
	@define("DB_USER", "jiwok_com");
	@define("DB_PASSWD", "leaPh1jooja7");
	@define("DB_HOST", "localhost");
	@define("DB_NAME", "jiwok_com");
	*/
	@define("DB_USER", "root");
	@define("DB_PASSWD", "");
	@define("DB_HOST", "localhost");
	@define("DB_NAME", "jiwok_com");
	
	require_once('DB.php');
	
	/*@define("DB_USER", "jiwokgandi");
	@define("DB_PASSWD", "#pabram#");
	@define("DB_HOST", "localhost");
	@define("DB_NAME", "jiwok_com");*/
	
	@define("DSN", 'mysql://' . DB_USER . ':' . DB_PASSWD . '@' . DB_HOST . '/' . DB_NAME);
	
	$db = DB::connect(DSN);
	if(DB::isError($db)) {
		die("Unable to connect to database: " . $db->getMessage() . "\n"
                                          . $db->getDebugInfo() . "\n");

	}
	$GLOBALS['db'] = $db;
	
	/*
	$query = 'SET character_set_results = NULL';
	$GLOBALS['db']->query($query);
	*/
	$query = "SET NAMES 'utf8'";
	$s = $GLOBALS['db']->query($query);
	if (DB::isError($s)) {
    die("Unable execute query: " . $s->getMessage() . "\n"
                                 . $s->getDebugInfo() . "\n");
}


    define("ROOT_FOLDER", '/jiwok_ver2/'); //this needs to be changed to '/' when taken live
?>

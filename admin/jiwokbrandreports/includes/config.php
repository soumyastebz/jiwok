<?php
	@define("DB_USER", "root");
	@define("DB_PASSWD", "");
	@define("DB_HOST", "localhost");
	@define("DB_NAME", "jiwok_com");
    $server1 = 'http://95.142.162.55/mp3dir/';
    define("SERVER1", $server1);
    $server2 = 'http://95.142.162.56/mp3dir/';
    define("SERVER2", $server2);
	@define("DSN", 'mysql://' . DB_USER . ':' . DB_PASSWD . '@' . DB_HOST . '/' . DB_NAME);
	require_once('DB.php');
	$db = DB::connect(DSN);
	if(DB::isError($db)){
		echo $db->getMessage();
	}

	$GLOBALS['db'] = $db;
	$query			= "SET NAMES 'utf8'";
	$GLOBALS['db']->query($query);
    define("ROOT_FOLDER", '/');
    //For Twitter Referel System
    define("TWITTER_CONSUMER_KEY","I5R18Hmaa75jA5vUWBE8Q");
	define("TWITTER_CONSUMER_SECRET","NZKG7O9Af3mAulReW7zIe1dvYBtfw3W2hiwnL8P9jY");
	define("FACEBOOK_APP_ID","159622664088064");
	define("FACEBOOK_SECRET_KEY","be86e9373eea53a4f07eefd80fc993af");
?>
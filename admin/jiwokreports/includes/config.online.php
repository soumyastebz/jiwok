<? 
    
	ini_set('display_errors',0);
	error_reporting(E_ERROR);
	
	//offline
/*	@define("DB_USER", "root");
	@define("DB_PASSWD", "");
	@define("DB_HOST", "localhost");
	@define("DB_NAME", "jiwok_ver2");*/
	
	//online
	@define("DB_USER", "jiwok_ver2_user");
	@define("DB_PASSWD", "nike2009");
	@define("DB_HOST", "localhost");
	@define("DB_NAME", "jiwok_ver2");
	
	@define("DSN", 'mysql://' . DB_USER . ':' . DB_PASSWD . '@' . DB_HOST . '/' . DB_NAME);
	require_once('DB.php');
	$db = DB::connect(DSN);
	if(DB::isError($db)) {
		echo $db->getMessage();
	}
	$GLOBALS['db']	= $db;
	$query		= "SET NAMES 'utf8'";
	$GLOBALS['db']->query($query);
	define("ROOT_FOLDER", '/jiwokv2/');
	/*assert_options(ASSERT_ACTIVE, 1);
	assert_options(ASSERT_WARNING, 1);
	//assert_options(ASSERT_BAIL, 1);
	*/
      
?>
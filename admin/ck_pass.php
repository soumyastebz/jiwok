<? 
include_once('forumpass.php');
$objPass = new ForumPass();
$test = "test123";

$result = $objPass->phpbb_hash($test);
print_r($result);
?>

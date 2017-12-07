<?php
//include_once("../includes/config.php");
	//include_once('../includes/classes/class.DbAction.php');
	if($_SERVER['HTTP_HOST']=='91.0.0.99')
	{
	$db=mysql_connect("localhost","root","");
		mysql_select_db('jiwok_ver2',$db);
	}
	else
	{
	$db=mysql_connect("localhost","jiwok_com","leaPh1jooja7");
	mysql_select_db('jiwok_com',$db);
	}
	
class queryListing
{
	function listquery1($searchqry)
	{
			$fromLimit = $no_rec*($i - 1);
			$toLimit = $no_rec;
			//$date=date();
			
		   $query="SELECT user_master . * , brand_master.*,(YEAR(CURDATE())-YEAR(STR_TO_DATE(user_master.user_dob,'%d/%m/%Y')))-(RIGHT(CURDATE(),5)< RIGHT(user_master.user_dob,5)) AS age
		FROM brand_user
		RIGHT JOIN user_master ON ( user_master.user_id = brand_user.user_id ) 
		LEFT JOIN brand_master ON ( brand_master.brand_master_id = brand_user.brand_master_id ) WHERE user_master.user_type =1";
			if($searchqry!='')
			{
			$query.=$searchqry;
			}
			 $query.=" ORDER BY user_master.user_doj DESC";
			echo $query;
			$result = mysql_query($query)or die(mysql_error());
			
			
			return $result;
	}
	
	
	//////////////function for listing brand name for select boxes
		public function getAllBrandName(){
			$query = "SELECT * FROM brand_master ORDER BY brand_name ASC";
			//print $query; 
			$result = mysql_query($query)or die(mysql_error());
			//$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);
			//$result1=mysql_fetch_array($result,MYSQL_BOTH);
			//print_r($result1);
			return $result;
		}
	///////////////////
	
		public function listquery2($searchqry)
		{
			$query="SELECT user_master.*, discount_users.start_date AS payDate, discount_users.discount_code AS discCode, discount_users.payment_status AS payStatus, COUNT( discount_users.user_id ) AS count, brand_master.brand_name, period_diff( date_format( now( ), '%Y%m' ) , date_format( discount_users.start_date, '%Y%m' ) ) AS months
FROM brand_user
RIGHT JOIN user_master ON ( user_master.user_id = brand_user.user_id ) 
LEFT JOIN brand_master ON ( brand_master.brand_master_id = brand_user.brand_master_id ) 
INNER JOIN discount_users ON user_master.user_id = discount_users.user_id
WHERE user_master.user_type =1
AND period_diff( date_format( now( ) , '%Y%m' ) , date_format( discount_users.start_date, '%Y%m' ) ) >=1
";
			if($searchqry!='')
			{
			$query.=$searchqry;
			}
			 $query.=" GROUP BY user_master.user_id ORDER BY COUNT( discount_users.user_id ) DESC";
			echo $query;
			$result = mysql_query($query)or die(mysql_error());
			
			
			return $result;
		}
}	
?>
	
	
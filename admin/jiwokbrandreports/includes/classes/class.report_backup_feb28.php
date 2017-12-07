<?
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 	::> Class for report management
   Programmer	::> Deepa S
   Date		::> 24-Jan-2011
   DESCRIPTION::::>>>>
   This is a Class code used to manage the reports of Jiwok/Brands.
*****************************************************************************/

include_once("class.DbAction.php");

class Report extends DbAction{

	

	public $language;

	public $objDb;

	private $today;

	private static $last_transaction_details		= NULL;

	private static $last_subscription_start_details	= NULL;

	private static $subscription_expiry_date		= NULL;

	private static $error_code_mapping				= NULL;	

	public function __construct($language){

		//setting the language 

		$this->language		= $language;
		$this->today		= date('Y-m-d');
		

	}

	
	public function _showPage($userType,$totalRecs,$i = 0,$no_rec = 0,$field,$type,$searchQuery){

			$fromLimit = $no_rec*($i - 1);

			$toLimit = $no_rec;

			if(trim($searchQuery)!=''){

				$query = "SELECT * FROM user_master WHERE user_type=".$userType.$searchQuery." ORDER BY $field $type LIMIT $fromLimit,$toLimit";

			}else{

			$query = "SELECT * FROM user_master WHERE user_type=".$userType." ORDER BY $field $type LIMIT $fromLimit,$toLimit";

			}

			$result = $GLOBALS['db']->query($query);

			return $result;

	} 

	//following function for listing the req for unsubscribed users 

	//function

	public function _showReqUnsubPage($i = 0,$no_rec = 0,$field,$type,$searchQuery){



 			$fromLimit = $no_rec*($i - 1);

			$toLimit = $no_rec;

			if(trim($searchQuery)!=''){

				$query = "SELECT * FROM user_master WHERE user_unsubscribed= 1 ".$searchQuery." ORDER BY $field $type LIMIT $fromLimit,$toLimit";

			}else{

			$query = "SELECT * FROM user_master WHERE user_unsubscribed= 1 ORDER BY $field $type LIMIT $fromLimit,$toLimit";

			}

			$result = $GLOBALS['db']->query($query);

			return $result;

	}

	//following function for listing the req for unsubscribed users 

	//function

	public function _showUnsubscribedUsers($i = 0,$no_rec = 0,$field,$type,$searchQuery){



 			$fromLimit = $no_rec*($i - 1);

			$toLimit = $no_rec;

			if(trim($searchQuery)!=''){

				$query = "SELECT * FROM user_master WHERE user_unsubscribed= 2 ".$searchQuery." ORDER BY $field $type LIMIT $fromLimit,$toLimit";

			}else{

			$query = "SELECT * FROM user_master WHERE user_unsubscribed= 2 ORDER BY $field $type LIMIT $fromLimit,$toLimit";

			}

			$result = $GLOBALS['db']->query($query);

			return $result;

	}  

	public function _getOneUser($userId){



 			$sql = "SELECT * FROM user_master WHERE user_id= '".$userId."'";

			

			$result = $GLOBALS['db']->getRow($sql,DB_FETCHMODE_ASSOC);

			return $result;

	} 

	/*

	To get all countries from the countries table	

	*/	

	public function _getCountries(){

		$sql 	= "SELECT countries_id,countries_name FROM countries";

		$result = $GLOBALS['db']->getAll($sql,DB_FETCHMODE_ASSOC);

		$countriesArray =array();

		foreach($result as $key => $name){

			$ccode		= $name['countries_id'];

			$cname		= $name['countries_name'];

			

			$countriesArray[$ccode] = $cname;

		}

		return $countriesArray;

	}

	

	/*

	To get all timezone from the timezone table	

	*/	

	public function _getTimezone(){

		$sql 	= "SELECT time_tz,time_name FROM timezone order by time_id";

		$result = $GLOBALS['db']->getAll($sql,DB_FETCHMODE_ASSOC);

		$TimezoneArray =array();

		foreach($result as $key => $name){

			$time_tz		= $name['time_tz'];

			$time_name		= $name['time_name'];

			

			$TimezoneArray[$time_tz] = $time_name;

		}

		return $TimezoneArray;

	}

	

	//get country name added by abhi on dec 10

	public function _getCountryName($id, $language=''){

		$sql 	= "SELECT countries_id,countries_name, countries_name_fr FROM countries WHERE countries_id = ".$id."";

		$result = $GLOBALS['db']->getAll($sql,DB_FETCHMODE_ASSOC);

		foreach($result as $key => $name){

			if($language=='french') {

				$cname		= $name['countries_name_fr'];

			} else {

				$cname		= $name['countries_name'];

			}

		}

		return $cname;

	}

	
		//get all Sports

		public function _getAllSports($lanId){

				

				$langName		=	strtolower($this->_getLanName($lanId));

				$lang_sport		=	"sport_".$langName;

				$sql_sport		=	"select sport_id,sport_{$langName} from sport";

				$res_sport		=	$GLOBALS['db']->getAll($sql_sport,DB_FETCHMODE_ASSOC);

				$sportArray		=	array();

				foreach($res_sport as $key=>$val_sport)

					{

						$sportArray[$val_sport['sport_id']]	=	$val_sport[$lang_sport];

					}

					

				return $sportArray;

		}

		

		//get language name

		public function _getLanName($lanId){

		

			//get language name

				$sql_lan		=	"select language_name from languages WHERE language_id = {$lanId}";

				$res_lan		= 	$GLOBALS['db']->getRow($sql_lan,DB_FETCHMODE_ASSOC);

				$lanName		=	$res_lan['language_name'];

			

			return $lanName;

		

		}

		//get user details by user id

		public function _getAllByUserId($userId){

			

			$selectQuery	=	"select * from user_master where user_id=".$userId; 

			$result 		= 	$GLOBALS['db']->getRow($selectQuery,DB_FETCHMODE_ASSOC);

			return $result;

		}


	public function prepareSearchKeyword($keyword, $surround = 0){

		$not_allowed_chars	= array("%","$","#","^","!");

		$search_keyword		= str_replace('&quot;', '"', $keyword);

		$search_keyword		= str_replace($not_allowed_chars, "_", $search_keyword);

		$search_keyword		= str_replace('*', '%', $search_keyword);

		if($surround==1) {

			$search_keyword	= '%'.$search_keyword.'%';

		}

		

		return $search_keyword;

	}

	

	public function prepareSearchQuery($keyword){

		$keyword	= $this->prepareSearchKeyword($keyword);

		$searchQuery	= '';

		if($keyword != ''){

			$exp_keywords	= explode(" ",$keyword, 2);

			$keyword = '%'.$keyword.'%';

			$keyword = $GLOBALS['db']->quoteSmart($keyword);

			if(sizeof($exp_keywords) == 2){

				$exp_keywords[0]    = '%'.$exp_keywords[0].'%';

				$exp_keywords[0]    = $GLOBALS['db']->quoteSmart($exp_keywords[0]);

				$exp_keywords[1]    = '%'.$exp_keywords[1].'%';

				$exp_keywords[1]    = $GLOBALS['db']->quoteSmart($exp_keywords[1]);

				$searchQuery	= " and  (user_fname like {$exp_keywords[0]} OR user_lname like {$exp_keywords[1]} OR user_email like {$keyword} OR user_alt_email like {$keyword})";	

			} else {

				$searchQuery	= " and  (user_fname like {$keyword} OR user_lname like {$keyword} OR user_email like {$keyword} OR user_alt_email like {$keyword})";	

			}

		}

		

		return $searchQuery;

	}

	

	public function getCount($searchQuery='', $user_type=1){

		$count			= 0;

		$query	= "SELECT count(*) FROM user_master WHERE user_type = '{$user_type}'" ;

		if($searchQuery != ''){

			$query			.= $searchQuery;

		}

		$count	= $GLOBALS['db']->getOne($query);

		

		return $count;

	}

	

	public function getPaymentStatus($user_id){

		$result	= array();

		$query	= "SELECT * FROM `payment` 

					WHERE payment_userid = ? AND payment_status = 1 

					ORDER BY payment_expdate DESC LIMIT 0, 1";

		$res	=& $GLOBALS['db']->getRow($query, array($user_id), DB_FETCHMODE_ASSOC);

		if(!PEAR::isError($res)){

			$result	= $res;

		} 

		

		return $result;

	}

	
	public function getCountryList($language_name) {

		if ($language_name == 'french') {

			$sql 	= "SELECT countries_id, countries_name_fr AS countries_name FROM countries ORDER BY countries_name_fr";

		} else {

			$sql 	= "SELECT countries_id, countries_name FROM countries ORDER BY countries_name";

		}

		$countries = $GLOBALS['db']->getAll($sql, DB_FETCHMODE_ASSOC);

		

		return $countries;

	}
	
	////get all timezones
	public function getTimezoneList() {
          
		  $sql 	= "SELECT * FROM timezone ORDER BY time_id";
              $timezones = $GLOBALS['db']->getAll($sql, DB_FETCHMODE_ASSOC);
		return $timezones;
	}
	////get timezone name from time zone value
	public function _getTimezoneName($timezone,$lanId="")
	{
	  $sql 	= "SELECT * FROM timezone where   	
time_tz='$timezone'";
              $timezone = $GLOBALS['db']->getRow($sql, DB_FETCHMODE_ASSOC);
			  if($lanId==1) return $timezone['time_name'];
			  else return $timezone['gmt_timezone'];
	}
	
	public function getAllBrandName(){
			$query = "SELECT * FROM brand_master ORDER BY brand_name ASC";
			//print $query; 
			$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);
			return $result;
		}
		
	public function getLastSubscriptionDetails($userId){

		if(is_array(self::$last_subscription_start_details)){ // check whether in cache

			return self::$last_subscription_start_details;

		}

		$subscription_start_details	= array();

		$select_query	= "SELECT * FROM `payment` 

							WHERE `payment_userid` = ? 

								AND `payment_firstdate` <> '0000-00-00' 

								AND `payment_status` = 1

								AND `payment_expdate` <> '' 

							ORDER BY `payment_id` DESC 

							LIMIT 0, 1";

		$res	=& $GLOBALS['db']->getRow($select_query, array($userId), DB_FETCHMODE_ASSOC);

		if(PEAR::isError($res)){

			echo $res->getDebugInfo();

		} else {

			$subscription_start_details	= $res;

			self::$last_subscription_start_details	= $res; // store in cache

		}

		

		return $subscription_start_details;

	}
	
	public function checkIfInSubscriptionPeriod($userId) {



		$last_subscription_row	= $this->getLastSubscriptionDetails($userId);

		if (sizeof($last_subscription_row) > 0) {

			$subscription_expiry_date	= $this->getSubscriptionExpiryDate($userId);

			if ($subscription_expiry_date > $this->today) {

				return true;

			}

		}

		

		return false;

	}
	
	public function checkLastTransactionStatus($userId){

		$last_transaction	= $this->getLastPayboxTransaction($userId);

		if (sizeof($last_transaction)==0) { // No transaction

			return 0;

		} elseif ($last_transaction['payment_error_code']=='00000') { // successful transaction

			return 1;

		} else { // failed transaction

			return -1;

		}

	}
	
	public function checkIfPaidAsOfThisDay($userId){

		$result			=	false;

		 $select_query	= "SELECT COUNT(*) FROM `payment` 

							WHERE `payment_userid` = ? 

								AND `payment_expdate` >= CURDATE() 

								AND `payment_status` = 1 ";

		$res	=& $GLOBALS['db']->getOne($select_query, $userId);

		if(PEAR::isError($res)){

			echo $res->getDebugInfo();

		} else {

			if($res > 0) {

				$result	= true;

			} else {

				$result	= false;

			}

		}

		

		return $result;

	}
	
	public function getSubscriptionExpiryDate($userId){

		if(self::$subscription_expiry_date != NULL){ // check whether in cache

			return self::$subscription_expiry_date;

		}

		$subscription_expiry_date	= '';

		if($this->checkIfPaidAsOfThisDay($userId)) {

			$last_subscription_details	= $this->getLastSubscriptionDetails($userId);

			if (sizeof($last_subscription_details)>0) {

				$subscription_start_date	= $last_subscription_details['payment_firstdate'];

				$subscription_expiry_date	= date('Y-m-d', strtotime($subscription_start_date.' + 12 month'));

				self::$subscription_expiry_date	= $subscription_expiry_date; // store in cache

			}

		}

		

		return $subscription_expiry_date;

	}
	
/*========================== new  functions added by Deepa S from jan-24-2011. ==============================================*/	

function getAllUsersCount($condition='')
{
	$query = "SELECT count(user_master.user_id) AS numbercount FROM brand_user
			 RIGHT JOIN user_master ON ( user_master.user_id = brand_user.user_id ) 
			 LEFT JOIN brand_master ON ( brand_master.brand_master_id = brand_user.brand_master_id ) WHERE user_master.user_type =1  AND user_master.user_id!=0";
			 if($condition!='')
			 {
				 $query.= $condition;
			 }
			
$result = $GLOBALS['db']->getRow($query,DB_FETCHMODE_ASSOC);
$numcount = $result['numbercount'];
return $numcount;

}

function getBrandUsersIds()
{
	$query1 = "SELECT brand_user.user_id as user_id FROM brand_user
			 INNER JOIN user_master ON ( user_master.user_id = brand_user.user_id ) 
			 INNER JOIN brand_master ON ( brand_master.brand_master_id = brand_user.brand_master_id ) WHERE user_master.user_type =1 AND user_master.user_id!=0";
			 $result1 = $GLOBALS['db']->getAll($query1,DB_FETCHMODE_ASSOC);
			 $r = array();
			 for($i=0;$i<count($result1);$i++)
			 {
				 $r[$i] = trim($result1[$i]['user_id']);
			 }
			 $userIdArray = implode(',',$r);
			return $userIdArray;

}

function getJiwokUsersCount($userIdArray,$condition='')
{
	$query = "SELECT count(user_id) AS numbercount FROM user_master where user_master.user_id NOT IN (".$userIdArray.") and user_master.user_type =1 AND user_master.user_id!=0";
			 if($condition!='')
			 {
				 $query.= $condition;
			 }
$result = $GLOBALS['db']->getRow($query,DB_FETCHMODE_ASSOC);
$numcount = $result['numbercount'];
return $numcount;

}

function getAllBrands($condition='')
{
	$query = "SELECT brand_user.brand_master_id as brand_master_id,brand_name, count( brand_user.brand_master_id ) as numcount
			 FROM brand_user
			 INNER JOIN user_master ON ( user_master.user_id = brand_user.user_id )
			 LEFT JOIN brand_master ON ( brand_master.brand_master_id = brand_user.brand_master_id )
			 WHERE user_master.user_type =1 AND user_master.user_id!=0
			 GROUP BY brand_user.brand_master_id order by brand_name ASC";
			 if($condition!='')
			 {
				 $query.= $condition;
			 }
$result = $GLOBALS['db']->getAll($query,DB_FETCHMODE_ASSOC);
return $result;

}

function getEachBrandsCount($brand_master_id,$condition='')
{
	$query = "SELECT count( brand_user.brand_master_id ) as numcount
			 FROM brand_user
			 INNER JOIN user_master ON ( user_master.user_id = brand_user.user_id )
			 INNER JOIN brand_master ON ( brand_master.brand_master_id = brand_user.brand_master_id )
			 WHERE user_master.user_type =1 AND user_master.user_id!=0";
	if($brand_master_id!='')
	{ $query.= " AND brand_user.brand_master_id=".addslashes($brand_master_id);}
	if($condition!='')
	{
	 $query.= $condition;
	}
			 
	//$query.=" GROUP BY brand_user.brand_master_id";
	//echo $query;
$result = $GLOBALS['db']->getRow($query,DB_FETCHMODE_ASSOC);
$numcount = $result['numcount'];
return $numcount;

}

function getUsersFreePaid($paymentstatus)
{
	$query = "SELECT user_master.user_id FROM user_master 
			 LEFT JOIN payment on user_master.user_id = payment.payment_userid
	where user_master.user_type =1 AND user_master.user_id!=0 AND (user_master.user_unsubscribed=0 or user_master.user_unsubscribed=1)";
			 if($paymentstatus!='')
			 {
				 $query.= " AND payment.payment_status=".$paymentstatus;
			 }
			 if($paymentstatus=='0')
			 {
				 $query.= " AND payment.payment_date IS NULL ";
			 }
			 if($paymentstatus=='1')
			 {
				 $query.= " AND payment.payment_date IS NOT NULL ";
			 }
			 
	$query .= " GROUP BY payment.payment_userid ORDER BY payment_date DESC";
	$result = $GLOBALS['db']->getAll($query,DB_FETCHMODE_ASSOC);
	return $result;

}

function getJiwokUsersFreePaid($userIdArray,$paymentstatus)
{
	$query = "SELECT user_master.user_id FROM user_master 
			 LEFT JOIN payment on user_master.user_id = payment.payment_userid
	where user_master.user_id NOT IN (".$userIdArray.") and user_master.user_type =1 AND user_master.user_id!=0 AND (user_master.user_unsubscribed=0 or user_master.user_unsubscribed=1)";
			 if($paymentstatus!='')
			 {
				 $query.= " AND payment.payment_status=".$paymentstatus;
			 }
			 if($paymentstatus=='0')
			 {
				 $query.= " AND payment.payment_date IS NULL ";
			 }
			 if($paymentstatus=='1')
			 {
				 $query.= " AND payment.payment_date IS NOT NULL ";
			 }
			 
	$query .= " GROUP BY payment.payment_userid ORDER BY payment_date DESC";		 
			 
$result = $GLOBALS['db']->getAll($query,DB_FETCHMODE_ASSOC);
return $result;

}

function getEachBrandsUsersFreePaid($brand_master_id,$paymentstatus)
{
	$query = "SELECT user_master.user_id  
			 FROM brand_user
			 INNER JOIN user_master ON ( user_master.user_id = brand_user.user_id )
			 INNER JOIN brand_master ON ( brand_master.brand_master_id = brand_user.brand_master_id )
			 LEFT JOIN payment on user_master.user_id = payment.payment_userid
			 WHERE user_master.user_type =1 AND user_master.user_id!=0 AND (user_master.user_unsubscribed=0 or user_master.user_unsubscribed=1)";
	if($brand_master_id!='')
	{ $query.= " AND brand_user.brand_master_id=".addslashes($brand_master_id);}
	if($paymentstatus!='')
	{
	 $query.= " AND payment.payment_status=".$paymentstatus;
	}
	if($paymentstatus=='0')
			 {
				 $query.= " AND payment.payment_date IS NULL ";
			 }
			 if($paymentstatus=='1')
			 {
				 $query.= " AND payment.payment_date IS NOT NULL ";
			 }
			 
	$query .= " GROUP BY payment.payment_userid ORDER BY payment_date DESC";	
	//echo $query;
$result = $GLOBALS['db']->getAll($query,DB_FETCHMODE_ASSOC);
return $result;

}

function getRegisterTested()
{
	$query = "SELECT user_master.user_id
			 FROM user_master
			 LEFT JOIN payment ON user_master.user_id = payment.payment_userid
			 LEFT JOIN program_queue ON user_master.user_id = program_queue.user_id
			 WHERE user_master.user_type =1
			 AND user_master.user_id !=0
			 AND (user_master.user_unsubscribed=0 or user_master.user_unsubscribed=1)
			 AND payment.payment_status =0
			 AND payment.payment_date IS NULL
			 AND program_queue.status =11
			 GROUP BY user_master.user_id
			 ORDER BY payment_date DESC";
		 
	$result = $GLOBALS['db']->getAll($query,DB_FETCHMODE_ASSOC);
	return $result;

}

function getRegisterNotTested()
{
	$query = 	"SELECT user_master.user_id FROM `user_master`
				LEFT JOIN payment ON user_master.user_id = payment.payment_userid
				WHERE user_master.user_type =1
				AND user_master.user_id !=0
				AND (user_master.user_unsubscribed=0 or user_master.user_unsubscribed=1)
				AND payment.payment_status =0
				AND payment.payment_date IS NULL
				AND user_master.user_id NOT IN (
					SELECT user_master.user_id
					FROM user_master
					LEFT JOIN payment ON user_master.user_id = payment.payment_userid
					LEFT JOIN program_queue ON user_master.user_id = program_queue.user_id
					WHERE user_master.user_type =1
					AND user_master.user_id !=0
					AND payment.payment_status =0
					AND payment.payment_date IS NULL
					AND program_queue.status =11
					GROUP BY user_master.user_id
					ORDER BY payment_date DESC
				) GROUP BY user_master.user_id ORDER BY payment_date DESC";
		 
	$result = $GLOBALS['db']->getAll($query,DB_FETCHMODE_ASSOC);
	return $result;

}

function getJiwokRegisterTested($userIdArray)
{
	$query = "SELECT user_master.user_id
			 FROM user_master
			 LEFT JOIN payment ON user_master.user_id = payment.payment_userid
			 LEFT JOIN program_queue ON user_master.user_id = program_queue.user_id
			 WHERE user_master.user_type =1
			 AND user_master.user_id !=0
			 AND (user_master.user_unsubscribed=0 or user_master.user_unsubscribed=1)
			 AND payment.payment_status =0
			 AND payment.payment_date IS NULL
			 AND program_queue.status =11
			 AND user_master.user_id NOT IN(".$userIdArray.")
			 GROUP BY user_master.user_id
			 ORDER BY payment_date DESC";
		 
	$result = $GLOBALS['db']->getAll($query,DB_FETCHMODE_ASSOC);
	return $result;

}
function getFreeUsersJiwok($userIdArray)
{
	$query = "SELECT user_master.user_id
			 FROM user_master
			 LEFT JOIN payment ON user_master.user_id = payment.payment_userid
			 LEFT JOIN program_queue ON user_master.user_id = program_queue.user_id
			 WHERE user_master.user_type =1
			 AND user_master.user_id !=0
			 AND (user_master.user_unsubscribed=0 or user_master.user_unsubscribed=1)
			 AND payment.payment_status =0
			 AND payment.payment_date IS NULL
			 AND user_master.user_id NOT IN(".$userIdArray.")
			 GROUP BY user_master.user_id
			 ORDER BY payment_date DESC";
		 
	$result = $GLOBALS['db']->getAll($query,DB_FETCHMODE_ASSOC);
	return $result;

}
function getFreeUsersBrand($brand_master_id)
{
  $query = "SELECT user_master.user_id
			FROM user_master
			LEFT JOIN brand_user ON ( user_master.user_id = brand_user.user_id )
			LEFT JOIN brand_master ON ( brand_master.brand_master_id = brand_user.brand_master_id )
			LEFT JOIN payment ON user_master.user_id = payment.payment_userid
			LEFT JOIN program_queue ON user_master.user_id = program_queue.user_id
			WHERE user_master.user_type =1
			AND user_master.user_id !=0
			AND (user_master.user_unsubscribed=0 or user_master.user_unsubscribed=1)
			AND payment.payment_status =0
			AND payment.payment_date IS NULL
			AND brand_user.brand_user_id IS NOT NULL
			AND brand_user.brand_master_id=".addslashes($brand_master_id)." 
			GROUP BY user_master.user_id
			ORDER BY payment_date DESC";
	
	$result = $GLOBALS['db']->getAll($query,DB_FETCHMODE_ASSOC);
	return $result;
}

function getJiwokRegisterNotTested($userIdArray)
{
	$query = 	"SELECT user_master.user_id FROM `user_master`
				LEFT JOIN payment ON user_master.user_id = payment.payment_userid
				WHERE user_master.user_type =1
				AND user_master.user_id !=0
				AND (user_master.user_unsubscribed=0 or user_master.user_unsubscribed=1)
				AND payment.payment_status =0
				AND payment.payment_date IS NULL
				AND user_master.user_id NOT IN(".$userIdArray.")
				AND user_master.user_id NOT IN (
					SELECT user_master.user_id
					FROM user_master
					LEFT JOIN payment ON user_master.user_id = payment.payment_userid
					LEFT JOIN program_queue ON user_master.user_id = program_queue.user_id
					WHERE user_master.user_type =1
					AND user_master.user_id !=0
					AND payment.payment_status =0
					AND payment.payment_date IS NULL
					AND program_queue.status =11
					AND user_master.user_id NOT IN(".$userIdArray.")
					GROUP BY user_master.user_id
					ORDER BY payment_date DESC
				) GROUP BY user_master.user_id ORDER BY payment_date DESC";
		 
	$result = $GLOBALS['db']->getAll($query,DB_FETCHMODE_ASSOC);
	return $result;

}

function getBrandRegisterTested($brand_master_id)
{
  $query = "SELECT user_master.user_id
			FROM user_master
			LEFT JOIN brand_user ON ( user_master.user_id = brand_user.user_id )
			LEFT JOIN brand_master ON ( brand_master.brand_master_id = brand_user.brand_master_id )
			LEFT JOIN payment ON user_master.user_id = payment.payment_userid
			LEFT JOIN program_queue ON user_master.user_id = program_queue.user_id
			WHERE user_master.user_type =1
			AND user_master.user_id !=0
			AND (user_master.user_unsubscribed=0 or user_master.user_unsubscribed=1)
			AND payment.payment_status =0
			AND payment.payment_date IS NULL
			AND brand_user.brand_user_id IS NOT NULL
			AND program_queue.status =11
			AND brand_user.brand_master_id=".addslashes($brand_master_id)." 
			GROUP BY user_master.user_id
			ORDER BY payment_date DESC";
	
	$result = $GLOBALS['db']->getAll($query,DB_FETCHMODE_ASSOC);
	return $result;
}

function getBrandRegisterNotTested($brand_master_id)
{
	$query = "SELECT user_master.user_id
			FROM user_master
			LEFT JOIN brand_user ON ( user_master.user_id = brand_user.user_id )
			LEFT JOIN brand_master ON ( brand_master.brand_master_id = brand_user.brand_master_id )
			LEFT JOIN payment ON user_master.user_id = payment.payment_userid
			LEFT JOIN program_queue ON user_master.user_id = program_queue.user_id
			WHERE user_master.user_type =1
			AND user_master.user_id !=0
			AND (user_master.user_unsubscribed=0 or user_master.user_unsubscribed=1)
			AND payment.payment_status =0
			AND payment.payment_date IS NULL
			AND brand_user.brand_user_id IS NOT NULL
			AND program_queue.status!=11
			AND brand_user.brand_master_id=".addslashes($brand_master_id)." 
			GROUP BY user_master.user_id
			ORDER BY payment_date DESC";
		 
	$result = $GLOBALS['db']->getAll($query,DB_FETCHMODE_ASSOC);
	return $result;

}

function getBrandSubscribers($type='',$brand='',$brand_master_id='')
{
	$curdate = date('Y-m-d');
  $query = "SELECT user_master.user_id
			FROM user_master
			LEFT JOIN brand_user ON ( user_master.user_id = brand_user.user_id )
			LEFT JOIN brand_master ON ( brand_master.brand_master_id = brand_user.brand_master_id )
			LEFT JOIN payment ON user_master.user_id = payment.payment_userid
			WHERE user_master.user_type =1
			AND user_master.user_id !=0
			AND (user_master.user_unsubscribed=0 or user_master.user_unsubscribed=1)
			AND payment.payment_status =1
			AND payment.payment_date IS NOT NULL
			";
			
 if($brand=="brand")
 {
 $query .= " AND brand_user.brand_user_id IS NOT NULL";
 }
 if($brand=="jiwok")
 {
 $query .= " AND brand_user.brand_user_id IS NULL";
 }
 if($brand_master_id!='')
 {
 $query .= " AND brand_user.brand_master_id=".addslashes($brand_master_id); 
 }
  if($type=='subscriber')
  {
  $query .= " AND payment.payment_expdate > '".addslashes($curdate)."'";
  }
  if($type=='exsubscriber')
  {
  $query .= " AND payment.payment_expdate <= '".addslashes($curdate)."'";
  }
 $query .= " GROUP BY user_master.user_id
			ORDER BY payment_date DESC";
			
	$result = $GLOBALS['db']->getAll($query,DB_FETCHMODE_ASSOC);
	return $result;
}

function getOneEuroTransactions($brand='',$brand_master_id='')
{
	
	/*SELECT discount_users . * , user_master . * , brand_user . *
FROM discount_users
INNER JOIN user_master ON discount_users.user_id = user_master.user_id
LEFT JOIN brand_user ON ( user_master.user_id = brand_user.user_id )
LEFT JOIN brand_master ON ( brand_master.brand_master_id = brand_user.brand_master_id )
WHERE user_master.user_type =1
AND user_master.user_id !=0
AND discount_users.discount_type = 'DISC'
AND discount_users.payment_status = 'success'*/

	$query = "SELECT discount_users.user_id
			FROM discount_users
			INNER JOIN user_master ON discount_users.user_id = user_master.user_id
			LEFT JOIN brand_user ON ( user_master.user_id = brand_user.user_id )
			LEFT JOIN brand_master ON ( brand_master.brand_master_id = brand_user.brand_master_id )
			WHERE user_master.user_type =1
			AND user_master.user_id !=0
			AND discount_users.discount_type='DISC'
			AND discount_users.payment_status='success' ";
			
 if($brand=="brand")
 {
 $query .= " AND brand_user.brand_user_id IS NOT NULL";
 }
 if($brand=="jiwok")
 {
 $query .= " AND brand_user.brand_user_id IS NULL";
 }
 if($brand_master_id!='')
 {
 $query .= " AND brand_user.brand_master_id=".addslashes($brand_master_id); 
 }
  
 //$query .= " GROUP BY user_master.user_id ORDER BY payment_date DESC";
	
	$result = $GLOBALS['db']->getAll($query,DB_FETCHMODE_ASSOC);
	return $result;
}

function getGiftCodeTransactions($brand='',$brand_master_id='')
{
	
	$query = "SELECT gift_userdetails.user_id
			 FROM gift_userdetails
			 INNER JOIN user_master ON gift_userdetails.user_id = user_master.user_id
			 INNER JOIN gift_code ON gift_userdetails.code = gift_code.code
			 INNER JOIN gift_member ON gift_userdetails.purchaseid = gift_member.purchaseid
			 INNER JOIN payment ON gift_userdetails.payment_id = payment.payment_id
			 LEFT JOIN brand_user ON ( user_master.user_id = brand_user.user_id )
			 LEFT JOIN brand_master ON ( brand_master.brand_master_id = brand_user.brand_master_id )
			 WHERE user_master.user_type=1
			 AND user_master.user_id!=0
			 AND gift_userdetails.user_id!=0
			 AND gift_userdetails.payment_id IS NOT NULL
			 AND gift_userdetails.payment_id!=0
			 AND payment.payment_status=1 ";
			
   if($brand=="brand")
 	{
 		$query .= " AND brand_user.brand_user_id IS NOT NULL";
 	}
 	if($brand=="jiwok")
 	{
 		$query .= " AND brand_user.brand_user_id IS NULL";
 	}
 	if($brand_master_id!='')
 	{
 		$query .= " AND brand_user.brand_master_id=".addslashes($brand_master_id); 
 	}
  
	$result = $GLOBALS['db']->getAll($query,DB_FETCHMODE_ASSOC);
	return $result;
}

public function _getSportsArray($lanId){
	  $sql = "SELECT flex_id ,item_name FROM general where language_id=".mysql_real_escape_string($lanId)." and table_name='sports' ORDER BY item_name ASC";
	  
	  $res = $GLOBALS['db']->getAll($sql, DB_FETCHMODE_ASSOC);
			if(DB::isError($res)) {
				echo $res->getDebugInfo();
			}
			else{
				$data = array();
				if(!empty($res)) {
					foreach($res as $languages){
					$key=$languages['flex_id'];	
					$data[$key]=$languages['item_name'];
											
					}
				}
				
			}
			
			return $data;
	  	  
	  }
	  
	  
function getReportOfSubscribers($type='',$condition='',$sort,$limit)
{
	
	$curdate = date('Y-m-d');
    /*$query = "SELECT user_master.user_id as user_id,user_master.user_fname,user_master.user_lname,user_master.user_email,user_master.user_gender,FLOOR((TO_DAYS(NOW())- TO_DAYS(STR_TO_DATE( user_master.user_dob, '%d/%m/%Y' ))) / 365.25) as age,user_country,brand_master.brand_name,user_master.user_language,origin.user_id as origin_userid,origin.end_time as end_time
			FROM user_master
			LEFT JOIN brand_user ON ( user_master.user_id = brand_user.user_id )
			LEFT JOIN brand_master ON ( brand_master.brand_master_id = brand_user.brand_master_id )
			JOIN payment ON user_master.user_id = payment.payment_userid
			left join 
			(
 			  select user_id,end_time from (select user_id,end_time from program_queue where status=11 order by end_time) as  pq group by pq.user_id 
			) as origin on origin.user_id=user_master.user_id

			WHERE user_master.user_type =1
			AND user_master.user_id NOT IN('NULL',0,'')
			AND (user_master.user_unsubscribed=0 or user_master.user_unsubscribed=1) ";
	
	
	
	if($type=='free') {		
	$query .= " AND payment.payment_status =0
				AND payment.payment_date IS NULL ";
	}
	if($type=='paid') {		
	$query .= "	AND payment.payment_expdate > '".addslashes($curdate)."'
				AND payment.payment_status =1
				AND payment.payment_date IS NOT NULL ";
	}
 */
 	/*if($brand_master_id!='')
 	{
 		$query .= " AND brand_user.brand_master_id IN(".$brand_master_id.")"; 
 	}*/
	
$query = "select * from report_final ";
    if(trim($condition)!='')
    	{ $query .= $condition; }
	
	$query .= $sort;
	if($limit!='')
	{ 
	 $query .= $limit; }
	echo $query;	
	$result = $GLOBALS['db']->getAll($query,DB_FETCHMODE_ASSOC);
	return $result;
}

function getReportOfSubscribersCount($type='',$condition='',$sort,$lower_limit,$maxrows)
{
	
	/*$sql = "SELECT (
YEAR( CURDATE( ) ) - YEAR( STR_TO_DATE( user_master.user_dob, '%m/%d/%Y' ) )
) - ( RIGHT( CURDATE( ) , 5 ) < RIGHT( STR_TO_DATE( user_master.user_dob, '%m/%d/%Y' ) , 5 ) ) AS age
FROM user_master";
		echo $sql;*/
	$curdate = date('Y-m-d');
    $query = "SELECT user_master.user_id as user_id,user_master.user_fname,user_master.user_lname,user_master.user_email,user_master.user_gender,FLOOR((TO_DAYS(NOW())- TO_DAYS(STR_TO_DATE( user_master.user_dob, '%d/%m/%Y' ))) / 365.25) as age,user_country,brand_master.brand_name,user_master.user_language
			FROM user_master
			LEFT JOIN brand_user ON ( user_master.user_id = brand_user.user_id )
			LEFT JOIN brand_master ON ( brand_master.brand_master_id = brand_user.brand_master_id )
			LEFT JOIN payment ON user_master.user_id = payment.payment_userid
			WHERE user_master.user_type =1
			AND user_master.user_id !=0
			AND user_master.user_id !=''
			AND user_master.user_id IS NOT NULL
			AND payment.payment_userid !=0
			AND payment.payment_userid IS NOT NULL
			AND (user_master.user_unsubscribed=0 or user_master.user_unsubscribed=1) ";
	
	
	
	if($type=='free') {		
	$query .= " AND payment.payment_status =0
				AND payment.payment_date IS NULL ";
	}
	if($type=='paid') {		
	$query .= "	AND payment.payment_expdate > '".addslashes($curdate)."'
				AND payment.payment_status =1
				AND payment.payment_date IS NOT NULL ";
	}
 
 	/*if($brand_master_id!='')
 	{
 		$query .= " AND brand_user.brand_master_id IN(".$brand_master_id.")"; 
 	}*/
    if(trim($condition)!='')
    	{ $query .= $condition; }
	$query .= " GROUP BY user_master.user_id ";
	$query .= $sort;
	if($lower_limit!='' && $maxrows!='')
	{ $query .= " limit $lower_limit,$maxrows "; }
	
	$result = $GLOBALS['db']->getAll($query,DB_FETCHMODE_ASSOC);
	
	return $result;
}


function checkOneEuroOrigin($userid)
{
	
	$query = "SELECT MIN(start_date) as startdate
			FROM discount_users
			WHERE discount_users.user_id ='".$userid."'
			 AND discount_users.user_id NOT IN('NULL',0,'')
			AND discount_users.discount_type='DISC'
			AND discount_users.payment_status='success' 
			GROUP BY discount_users.user_id ORDER BY start_date ASC";
	
	$result = $GLOBALS['db']->getAll($query,DB_FETCHMODE_ASSOC);
	if(count($result)>0)
	{
		 $startdate = $result[0]['startdate'];
	}
	else  $startdate = '';
	return $startdate;
	
		
}
function getOneEuroOriginDiscountCode($userid)
{
	
	$query = "SELECT discount_code as discountcode
			FROM discount_users
			WHERE discount_users.user_id ='".$userid."'
			 AND discount_users.user_id NOT IN('NULL',0,'')
			 AND discount_users.discount_type='DISC'
			AND discount_users.payment_status='success' 
			GROUP BY discount_users.user_id ORDER BY start_date ASC";
	
	$result = $GLOBALS['db']->getAll($query,DB_FETCHMODE_ASSOC);
	if(count($result)>0)
	{
		 $discountcode = $result[0]['discountcode'];
	}
	else  $discountcode = '';
	return $discountcode;
	
		
}

function checkGiftCodeTransaction($userid)
{
	
	$query = "SELECT MIN(usedate) as startdate
			 FROM gift_userdetails
			 INNER JOIN gift_code ON gift_userdetails.code = gift_code.code
			 INNER JOIN gift_member ON gift_userdetails.purchaseid = gift_member.purchaseid
			 INNER JOIN payment ON gift_userdetails.payment_id = payment.payment_id
			 WHERE gift_userdetails.user_id='".$userid."' 
			 AND gift_userdetails.user_id NOT IN('NULL',0)
			 AND gift_userdetails.payment_id NOT IN('NULL',0)
			 AND gift_userdetails.usedate!='0000-00-00' 
			 AND gift_userdetails.usedate IS NOT NULL 
			 AND payment.payment_status=1 
			 AND payment_amount >=7.9 and payment_amount NOT IN('NULL',0)
			 GROUP BY gift_userdetails.user_id ORDER BY usedate ASC";
   
	$result = $GLOBALS['db']->getAll($query,DB_FETCHMODE_ASSOC);
	if(count($result)>0)
	{
		 $giftcodedate = $result[0]['startdate'];
		 
	}
	else  $giftcodedate = '';
	return $giftcodedate;
}
function getOriginGiftCode($userid)
{
	
	$query = "SELECT gift_userdetails.code as giftcode
			 FROM gift_userdetails
			 INNER JOIN gift_code ON gift_userdetails.code = gift_code.code
			 INNER JOIN gift_member ON gift_userdetails.purchaseid = gift_member.purchaseid
			 INNER JOIN payment ON gift_userdetails.payment_id = payment.payment_id
			 WHERE gift_userdetails.user_id='".$userid."' 
			 AND gift_userdetails.user_id NOT IN('NULL',0)
			 AND gift_userdetails.payment_id NOT IN('NULL',0)
			 AND gift_userdetails.usedate!='0000-00-00' 
			 AND gift_userdetails.usedate IS NOT NULL 
			 AND payment.payment_status=1 
			 AND payment_amount >=7.9 and payment_amount NOT IN('NULL',0)
			 GROUP BY gift_userdetails.user_id ORDER BY usedate ASC";
   
	$result = $GLOBALS['db']->getAll($query,DB_FETCHMODE_ASSOC);
	if(count($result)>0)
	{
		 $giftcode = $result[0]['giftcode'];
		 
	}
	else  $giftcode = '';
	return $giftcode;
}

function checkPaymentTransactionforDiscount($userid)
{
	
	$query = "SELECT payment.payment_id as paymentid,payment.payment_date as paymentdate
			 FROM payment
			 WHERE payment.payment_userid ='".$userid."'
			 AND payment.payment_userid NOT IN('NULL','0')
			 AND payment.payment_date NOT IN('NULL','0000-00-00')
			 AND payment.payment_status=1
			 AND (payment_amount <7.9 or payment_amount<9.9) 
			 AND (payment_amount!=7.9 and payment_amount!=9.9) 
			 and payment_amount NOT IN('NULL',0)
		     GROUP BY payment.payment_userid ORDER BY payment.payment_date ASC";
			 
	$result = $GLOBALS['db']->getAll($query,DB_FETCHMODE_ASSOC);
	if(count($result)>0)
	{
	$paymentid 		= $result[0]['paymentid'];
	$paymentdate	= $result[0]['paymentdate'];
	}
	else $paymentdate = '';
	return $paymentdate;
	
}

function checkNormalPaymentTransaction($userid)
{
	
	$query = "SELECT payment.payment_id as paymentid,payment.payment_date as paymentdate
			 FROM payment
			 WHERE payment.payment_userid ='".$userid."'
			 AND payment.payment_userid NOT IN('NULL','0')
			 AND payment.payment_date NOT IN('NULL','0000-00-00')
			 AND payment.payment_status=1
			 AND (payment_amount='7.9' or payment_amount='9.9' ) 
			 and payment_amount NOT IN('NULL',0)
		     GROUP BY payment.payment_userid ORDER BY payment.payment_date ASC";
			
			 
	$result = $GLOBALS['db']->getAll($query,DB_FETCHMODE_ASSOC);
	if(count($result)>0)
	{
	$paymentid 		= $result[0]['paymentid'];
	$paymentdate	= $result[0]['paymentdate'];
	}
	else $paymentdate = '';
	return $paymentdate;

}

function getFreeWorkoutOrigin($userid)
{
	$query = "SELECT program_queue.end_time as endtime
			 FROM user_master
			 LEFT JOIN program_queue ON user_master.user_id = program_queue.user_id
			 WHERE user_master.user_type =1
			 AND user_master.user_id NOT IN('NULL',0)
			 AND user_master.user_id ='".$userid."'
			 AND (user_master.user_unsubscribed=0 or user_master.user_unsubscribed=1)
			 AND program_queue.status =11
			 AND program_queue.end_time!='0000-00-00 00:00:00'
			 GROUP BY user_master.user_id
			 ORDER BY program_queue.end_time ASC";
			 
	 
	$result = $GLOBALS['db']->getAll($query,DB_FETCHMODE_ASSOC);
	if(count($result)>0)
	{
	$workoutDownloadDate	= $result[0]['endtime'];
	$workoutDownloadDate	= date('Y-m-d',strtotime($workoutDownloadDate));
	}
	else $workoutDownloadDate = '';
	return $workoutDownloadDate;

}

public function getOneBrandName($brand_master_id){
			$query = "SELECT brand_name FROM brand_master where brand_master_id=".$brand_master_id;
			//print $query; 
			$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);
			if(count($result)>0)
			{
				$brand_name	= $result[0]['brand_name'];
				
			}
			else $brand_name = '';
			return $brand_name;
		}

function compareOriginDates($discountCodeDate,$giftCodeDate,$normalPaymentDate,$workoutDownloadDate)
{
	$a = array();
	
	if(trim($discountCodeDate)!='')
	$a['1 Euro Origin']   	= $discountCodeDate;
	if(trim($giftCodeDate)!='')
	$a['By Gift Code']    	=  $giftCodeDate;
	if(trim($normalPaymentDate)!='')
	$a['7.9 Euro Transaction'] 	=  $normalPaymentDate;
	if(trim($workoutDownloadDate)!='')
	$a['Free Workout Try']  =  $workoutDownloadDate;
	if(trim($discountCodeDate)=='' && trim($giftCodeDate)=='' && trim($normalPaymentDate)=='' && trim(			$workoutDownloadDate)=='')
	$a['Registered Only']   = '';
	asort($a);
	reset($a); //Safety - sets pointer to top of array
	$origin = key($a);
	return $origin;
	
}

function selectRecordsFromTemp($condition='',$sort,$lower_limit,$maxrows)
{
	
	$query = "SELECT *
			FROM reports_temp
			WHERE reports_temp.user_id !=0 ";
	
	if($condition!='')
	{
	  $query.=$condition;
	}
	$query .= " GROUP BY reports_temp.user_id ";
	$query .= $sort;
	
	if(trim($lower_limit)!='' && trim($maxrows)!='')
	{ 
	$query .= " limit $lower_limit,$maxrows ";
	
	}
	$result = $GLOBALS['db']->getAll($query,DB_FETCHMODE_ASSOC);
	return $result;
	
}
public function _getUserTrainingProgram($userid){

		  
		  $query 	= 	"SELECT t2.programs_subscribed_id as programs_subscribed_id FROM program_master as t1,programs_subscribed as t2,program_detail as t3 WHERE t2.program_id=t1.program_id  and t3.program_master_id=t1.program_id and t2.user_id=".addslashes($userid)." AND t2.program_type='program' AND t2.complete_status='p' AND t2.subscribe_status='1'";

		$result = $GLOBALS['db']->getAll($query,DB_FETCHMODE_ASSOC);
	if(count($result)>0)
	{
	  return 'Yes';
	} 
	else
	{ return 'No'; }

}


}



?>

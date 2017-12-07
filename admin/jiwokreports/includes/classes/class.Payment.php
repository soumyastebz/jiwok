<?

/*

	Project: Jiwok 

	Module: Payment

	Programmer: Sreekumar

	Date: 21-05-09

	Desc:

*/

class Payment {

	

	private $today;

	private static $last_transaction_details		= NULL;

	private static $last_subscription_start_details	= NULL;

	private static $subscription_expiry_date		= NULL;

	private static $error_code_mapping				= NULL;



	public function __construct() {

		$this->today		= date('Y-m-d');

	}

/*

Not used anywhere; safe to delete

*/	

	public function insertRow($table_name, $insert_array){

		$result			= false;

		$field_names	= array_keys($insert_array);

		$values			= array_values($insert_array);

		$values_place_holders	= array();

		$params			= array(); 

		foreach ($values as $value) {

			if ($value == 'NOW()' || $value == 'CURDATE()') {

				$values_place_holders[]	= $value;

			} else {

				$values_place_holders[]	= '?';

				$params[]				= $value;

			}

		}

		$insert_query	=	"INSERT INTO ".$table_name." ( ";

		$insert_query	.=	implode(", ", $field_names).") ";

		$insert_query	.=	" VALUES ( ";

		$insert_query	.=	implode(", ", $values_place_holders);

		$insert_query	.=	" )";



		$res	= $GLOBALS['db']->query($insert_query, $params);

		if(!PEAR::isError($res)) {

			$res	= $GLOBALS['db']->getOne('LAST_INSERT_ID()');

			if(!PEAR::isError($res)) {

				$result	= $res;

			}

		}

		

		return $result;

	}

/*

Get record of first transaction of the last 12 month subscription

Get the last record from payment table, of the user where payment_firstdate is set, expiry date is set and status is 1 

*/

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

/* 

Can the user renew subscription today 

if true: return true

else: return  false



User can subscribe if any of the below three is true

	1. 12 month period is over

	2. only 15 days in 12 month period is remaining

	3. last subscription has been canceled. (last transaction refused, with error code belonging to a given list)

	

	ToDo: First checking should be, if you user is paid as of today. If not allow user to subscribe. This is under the assumption that if transaction fails for any month, the entire subscription is cancelled.

*/	

	public function canRenewSubscriptionToday($userId) {

		if($this->checkIfPaidAsOfThisDay($userId)== false) {

			return true;

		}

		if($this->checkIfInSubscriptionPeriod($userId) == true){

			// Date, 15 Days Before Expiryesy date of 12 month subscription

			$subscription_expiry_date		= $this->getSubscriptionExpiryDate($userId);

			$date_15_days_before_expiry 	= date('Y-m-d', strtotime("$subscription_expiry_date - 15 day"));

			if ($this->today > $date_15_days_before_expiry) { 

			// If Today is in Last 15 Days of subscription expiry or after it.

				return true;

			}

		}



		//Check if last transaction was a failure

		$subscription_cancelled		= false;

		$last_transaction_status	= $this->checkLastTransactionStatus($userId);

		if($last_transaction_status == -1) { // If last transaction was a failure, check the reasons

			$last_transaction_details	= $this->getLastPayboxTransaction($userId);

			$subscription_cancelled	= $this->isSubscriptionCancelled($last_transaction_details['payment_error_code']);

			if ($subscription_cancelled == true) {

				return true;

			}

		}

		

		return false;

	}

/*

Get last paybox transaction for given user 

return an array of the transaction details

Get last record of user from payment table, where error code is set.

*/

	public function getLastPayboxTransaction($userId){

		if(is_array(self::$last_transaction_details)) { // check whether in cache

			return self::$last_transaction_details;

		}

		

		$transaction_details	= array();

		$select_query	= "SELECT * FROM `payment` 

							WHERE `payment_userid` = ? 

								AND `payment_error_code` <> ''

							ORDER BY `payment_id` DESC 

							LIMIT 0, 1";

		$res	=& $GLOBALS['db']->getRow($select_query, array($userId), DB_FETCHMODE_ASSOC);

		if(PEAR::isError($res)){

			echo $res->getDebugInfo();

		} else {

			$transaction_details	= $res;

			self::$last_transaction_details	= $res; // store in cache

		}

		

		return $transaction_details;

	}

/*

Check status of last paybox transaction of this user

if no transaction, return 0

if successful transaction, return 1

if failed transaction, return -1

*/

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

	

	/* 

	To check if user is a paid member today

	Will return true if user is a member

	Else will return false

	*/

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

	

	public function getLastTransactionErrorCode($userId){

		$error_code	= '';

		$last_transaction	= $this->getLastPayboxTransaction($userId);

		if (sizeof($last_transaction) > 0) {

			$error_code	= $last_transaction['payment_error_code'];

		}

		

		return $error_code;

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

/* For given error code, check if subscription is cancelled */	

	public function isSubscriptionCancelled($error_code){

		$subscription_cancelled	= false;

		switch($error_code) { // Check error code for reasons of transaction failure

			case '00133': //expired card.

			case '00151': //insufficient funds or over credit limit.

			case '00154': //expiry date of the card passed. 

			case '00141': //lost card.

			case '00143': //stolen card. 

				$subscription_cancelled	= true;

				break;

			// Following 4 lines added so that subscription is cancelled, if payment is not done for a month, whatever the reason

			case '00000':

				break;

			default	:

				$subscription_cancelled	= true;

		}

		

		return $subscription_cancelled;

	}

	

	public function setAllErrorCodeMapping(){

		if (!is_array(self::$error_code_mapping)) {



			self::$error_code_mapping	= array(

				'00102'	=>	'contact the card issuer.',

				'00103'	=>	'invalid retailer.',

				'00104'	=>	'keep the card.',

				'00105'	=>	'do not honour.',

				'00107'	=>	'keep the card, special conditions.',

				'00108'	=>	'approve after holder identification.',

				'00112'	=>	'invalid transaction.',

				'00113'	=>	'invalid amount.',

				'00114'	=>	'invalid holder number.',

				'00115'	=>	'card issuer unknown.',

				'00117'	=>	'client cancellation.',

				'00119'	=>	'repeat the transaction later.',

				'00120'	=>	'error in reply (error in the server\'s domain).',

				'00124'	=>	'file update not withstood.',

				'00125'	=>	'impossible to situate the record in the file.',

				'00126'	=>	'record duplicated, former record replaced.',

				'00127'	=>	'error in „edit\' in file up-date field.',

				'00128'	=>	'access to file denied.',

				'00129'	=>	'file up-date impossible.',

				'00130'	=>	'error in format.',

				'00131'	=>	'identifier of purchasing body unknown.',

				'00133'	=>	'expired card.',

				'00138'	=>	'too many attempts at secret code.',

				'00141'	=>	'lost card.',

				'00143'	=>	'stolen card.',

				'00151'	=>	'insufficient funds or over credit limit.',

				'00154'	=>	'expiry date of the card passed.',

				'00155'	=>	'error in secret code.',

				'00156'	=>	'card absent from file.',

				'00157'	=>	'transaction not permitted for this holder.',

				'00158'	=>	'transaction forbidden at this terminal.',

				'00159'	=>	'suspicion of fraud.',

				'00160'	=>	'card accepter must contact purchaser.',

				'00161'	=>	'amount of withdrawal past the limit.',

				'00163'	=>	'security regulations not respected.',

				'00168'	=>	'reply not forthcoming or received too late.',

				'00175'	=>	'too many attempts at secret code.',

				'00176'	=>	'holder already on stop, former record kept.',

				'00190'	=>	'temporary halt of the system.',

				'00191'	=>	'card issuer not accessible.',

				'00194'	=>	'request duplicated.',

				'00196'	=>	'system malfunctioning.',

				'00197'	=>	'time of global surveillance has expired.',

				'00198'	=>	'server inaccessible (set by the server).',

				'00199'	=>	'incident in the initiating domain.'

			);

		}

	}

	

	public function getErrorCodeMapping($error_code) {

		$error_message	= '';

		if (!is_array(self::$error_code_mapping)) {

			$this->setAllErrorCodeMapping();

		}

		if (isset(self::$error_code_mapping[$error_code])) {

			$error_message	= self::$error_code_mapping[$error_code];

		}

		

		return $error_message;

	}

	

	public function debug_getAllVars(){

		return get_class_vars(get_class($this));

	}

}
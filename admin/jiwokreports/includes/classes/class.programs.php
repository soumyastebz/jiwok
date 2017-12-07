<?php


	/**************************************************************************** 

   Project Name	::> Jiwok 

   Module 		::> Training program Management

   Programmer	::> Deepa S

   Date			::> 04/02/2009
   DESCRIPTION::::>>>>

   This is class that can be used to manipulate the training program section 

   *****************************************************************************/

   include_once("class.DbAction.php");

	class Programs extends DbAction{

		public $language;

		public $objDb;

		

		public function Programs($language=''){

			//setting the language of the training

			$this->language		= $language;

		}

		

		//  get language name

		public function _getLanguageName($lanId)

		{

		

		

		}

		

		/*

		Function   			: _displayTrainingProgram

		Usage	   			: Fetch training program details with single programid. The programid & languageid is passed to the function.

		Variable Passing 	: $pgmid is passed as reference.

		Returns	   			: row

		*/

		public function _displayTrainingProgram(&$pgmid,$lanId) {

			$sql = "SELECT  t1.flex_id as flex_id,t1.training_type_flex_id as training_type_flex_id,t1.program_category_flex_id as program_category_flex_id,t1.program_level_flex_id as program_level_flex_id,t1.program_for as program_for,t1.program_image as program_image,t1.program_author as program_author,t1.program_schedule as program_schedule,t1.program_rythm as program_rythm,t1.schedule_type as schedule_type,t2.program_title as program_title,t2.program_desc as program_desc,t2.program_target as program_target,t2.program_provide as program_provide FROM  "

			."program_master as t1,program_detail as t2 WHERE t1.program_id = t2.program_master_id "

			."AND t1.program_id = {$pgmid} AND t2.language_id = {$lanId}";

			$res = $GLOBALS['db']->getRow($sql,DB_FETCHMODE_ASSOC);

			return $res;

			

		}

		// get flex id

		public function _getProgramFlexId(&$pgmid) {

			$sql = "SELECT  flex_id FROM program_master WHERE program_id = {$pgmid}";

			$res = $GLOBALS['db']->getRow($sql,DB_FETCHMODE_ASSOC);

			return trim(stripslashes($res['flex_id']));

			

		}

		

		// insert details to database table

		public function _insertDetails($insArray,$table){

			$this->_insertRecord($table,$insArray);

		}

		// get ids from ticket or forum

		public function _getForumTicketId($name,$fieldname,$table,$condnfield)

		{

			$query = "SELECT  $name FROM $table WHERE $fieldname = '".$condnfield."'";

			$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

			return $result;

		}

		// update password in forum & ticket

		public function _updateForumTicketPass($tablename,$fieldname,$fieldvalue,$condname,$condvalue)

		{

			$query = "UPDATE $tablename SET $fieldname='".addslashes(trim($fieldvalue))."' WHERE $condname ={$condvalue}"; 

			$res = $GLOBALS['db']->query($query); 

			return $res;

		}

				/*

			Function 			: _getWorkoutCount

			Usage	   			: To get the total number of workouts for a training program

			Variable Passing 	: program flex id

		*/

		public function _getWorkoutCount($pgm_flexid,$lanId){

			$query = "SELECT count(*) as max FROM program_workout WHERE training_flex_id='".addslashes(trim($pgm_flexid))."' AND lang_id={$lanId}";

			$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);

			$totalRecs = $result[0]->max;

			return $totalRecs;

		}

		/*

			Function 			: getWorkoutsOfProgram

			Usage	   			: To get the workouts for a training program

			Variable Passing 	: program flex id, language id

		*/

		public function getWorkoutsOfProgram($flex_id, $language_id){

			$result	= array();

			$query	=	"SELECT WD.workout_flex_id, WD.workout_title 

						FROM program_workout AS PW 

						INNER JOIN workout_details AS WD 

							ON PW.workout_flex_id = WD.workout_flex_id AND PW.lang_id = WD.lang_id

						WHERE PW.training_flex_id = ? AND PW.lang_id = ?";

			$res	=	$GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC, array($flex_id, $language_id));

			if (!PEAR::isError($res)) {

				$result	= $res;

			}

			

			return $result;

		}

		/*get user selected genre names from table corresponding to user selected ids */

		public function _getSelectedGenres($genreIds,$userid){

			if($genreIds!="")

			{

			  $condn = " AND id IN ($genreIds) ";

			}		

			$query = "SELECT genre_name,remember_status FROM tag_detected_genre WHERE user_id ={$userid}".$condn;

			$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);

			return $result;

		}

		/* update status of genre as 1 in table if user select 'remeber your choice' */

		public function _updateGenreStatus($genreIds,$userid)

		{

			if($genreIds!="")

			{

			  $condn = " AND id IN ($genreIds) ";

			}		

			$query = "UPDATE tag_detected_genre SET remember_status=1,last_updated=NOW() WHERE user_id ={$userid}".$condn;

			$res = $GLOBALS['db']->query($query); 

			return $res;

		}

		

		/* update status of genre as 1 in table if user select 'remeber your choice' */

		public function _updateMemoryGenreStatus($genreList, $vocal_type, $random_genre_status, $rem, $userid)

		{

			$query = "UPDATE user_memory_wrk_genre SET genre_name_memory='$genreList',last_updated=NOW(), vocal_coach_status = '$vocal_type', random_genre_status = '$random_genre_status', remember_status ='$rem'  WHERE user_id ={$userid}";

			//mail('webtesters@gmail.com',"Update Query",$query);

			$res = $GLOBALS['db']->query($query); 

			return $res;

		}

		

		/*Insert in to user memory table for memorize the songs*/

		public function _insertInToUserSongMemory($username, $user_id, $last_updated, $vocal_coach_status, $random_genre_status, $remember_status, $genre_name_memory){

			$query	="insert into user_memory_wrk_genre (username, user_id, last_updated, vocal_coach_status, 	random_genre_status, remember_status, genre_name_memory ) values ('$username', '$user_id', '$last_updated', '$vocal_coach_status', 	'$random_genre_status', '$remember_status', '$genre_name_memory')";	

			//mail('webtesters@gmail.com',"INS Query",$query);

			$res = $GLOBALS['db']->query($query); 

			return $res;

		}


		

		

		// update user password

		public function _updatePassword($pass,$userid)

		{

			$query = "UPDATE user_master SET user_password='".addslashes(base64_encode(trim($pass)))."' WHERE user_id ={$userid}"; 

			$res = $GLOBALS['db']->query($query); 

			return $res;

		}

		

		/*

			Function 			: _getName

			Usage	   			: To get name of a training program parameter from general table

			Variable Passing 	: flex id

		*/

		public function _getName($flex_id,$lanId){

			$query = "SELECT item_name FROM general WHERE flex_id='".addslashes(trim($flex_id))."' and language_id={$lanId} and table_name='training_type'";

			$result = $GLOBALS['db']->getRow($query,DB_FETCHMODE_ASSOC);

			$itemname = $result['item_name'];

			return $itemname;

		}

		// get category name. category flex id,language id passed as argument

		public function _getCatName($flex_id,$lanId){

			$query = "SELECT category_name,parent_id FROM sub_category WHERE flex_id='".addslashes(trim($flex_id))."' and language_id={$lanId}";

			$result = $GLOBALS['db']->getRow($query,DB_FETCHMODE_ASSOC);

			$itemname = $result['category_name'];

			return $itemname;

		}

		// get item name.  flex id,language id and table name passed as argument

		public function _getName1($flex_id,$lanId,$table_name){

			$query = "SELECT item_name FROM general WHERE flex_id='".addslashes(trim($flex_id))."' and language_id={$lanId} and table_name='".addslashes(trim($table_name))."'";

			$result = $GLOBALS['db']->getRow($query,DB_FETCHMODE_ASSOC);

			$itemname = $result['item_name'];

			return $itemname;

		}

		// get program for categories like men,women....  flex id,language id and table name passed as argument

		public function _getGroups($flex_id,$lanId,$table_name){

		    $pgm_for = array();

			$query = "SELECT item_name FROM general WHERE flex_id IN ($flex_id) and language_id={$lanId} and table_name='".addslashes(trim($table_name))."'";

			$result = $GLOBALS['db']->getAll($query,DB_FETCHMODE_ASSOC);

			$cnt= count($result);

		    for($i=0;$i<$cnt;$i++)

			{

		  	$pgm_for[] = $result[$i]['item_name'];

			}

			$programFor = implode(',',$pgm_for);

			return $programFor;

		}	

		/*

			Function 			: _getWorkoutFlexIds

			Usage	   			: To get all workout flex ids of a training program 

			Variable Passing 	: training program flex id

		*/

		public function _getWorkoutFlexIds($pgm_flexid,$lanId){

			$query = "SELECT workout_flex_id FROM program_workout WHERE training_flex_id='".addslashes(trim($pgm_flexid))."' AND lang_id={$lanId} ORDER BY workout_order ASC";

			$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);

			return $result;

		}

		

		public function _getWorkoutOriginSource($workout_flex_id){

			$query = "SELECT workout_id,workout_origin_force,workout_origin_file FROM workout_master WHERE workout_flex_id ='".addslashes(trim($workout_flex_id))."'";

			$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

			return $result;

		}

		public function _getWorkoutOriginSourceFile($workoutid){

			$query = "SELECT workout_origin_file FROM workout_master WHERE workout_id ={$workoutid}";

			$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

			return $result;

		}

		/*

			Usage	   			: To get all feedbacks of a training program 

			Variable Passing 	: training program id

		*/

		public function _getFeedbacks($pgm_id){

			$query = "SELECT t1.feedback_desc as description,t2.user_fname as firstname FROM feedback as t1,user_master as t2 WHERE t1.program_id = {$pgm_id} AND t1.user_id  = t2.user_id  and t2.user_status='1' ORDER BY feedback_datetime DESC";

			$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);

			return $result;

		}

		// get feedbacks given by user

		public function _getUserFeedbacks($userid){

			$query = "SELECT t1.feedback_id as feedback_id,t1.feedback_desc as description,t1.program_id as program_id,t1.workout_flex_id as workout_flex_id FROM feedback as t1,user_master as t2 WHERE t1.user_id  = t2.user_id AND t1.user_id  ={$userid} ORDER BY feedback_datetime DESC";

			$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);

			return $result;

		}

		// get feedbacks given by user for a workout

		public function _getWorkoutUserFeedbacks($userid,$pgmid,$workout_flexid,$lanId=''){

		if($lanId)
			{
					$cnd	=	"and t1.lang_id=".$lanId;
			}
		else
			{

					$cnd	=	'';
			}

			$query = "SELECT t1.feedback_id as feedback_id,t1.feedback_desc as description FROM feedback as t1,user_master as t2 WHERE t1.user_id  = t2.user_id AND t1.user_id  ={$userid} AND t1.program_id ={$pgmid} AND t1.workout_flex_id ='".addslashes(trim($workout_flexid))."' ".$cnd." AND public_status='1' ORDER BY feedback_datetime DESC";

			$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);

			return $result;

		}

		// get feedback details.  pass - feedback id

		public function _getFeedbackDetails($feedback_id){

			$query = "SELECT feedback_desc from feedback WHERE feedback_id = {$feedback_id}";

			$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

			return $result;

		}

		// update feedback description -  feedback id passed

		public function _updateFeedback($tablename,$feedback_id,$desc)

		{

			$query = "UPDATE ".$tablename." SET feedback_desc='".addslashes($desc)."' WHERE feedback_id ={$feedback_id}";

			$res = $GLOBALS['db']->query($query); 

			return $res;

		}

		// delete from program queue

		public function _deleteFromQueue($tablename,$flexid,$userid)

		{

			$query = "DELETE FROM ".$tablename." WHERE program_flex_id='".addslashes($flexid)."' AND status=1 AND user_id ={$userid}";

			$res = $GLOBALS['db']->query($query); 

			return $res;

		}

		/*

			Function 			: _getWorkoutDetail

			Usage	   			: To get detail description of a workout

			Variable Passing 	: workout flex id

		*/

		public function _getWorkoutDetail($workout_flexid,$lanId){

			$query = "SELECT workout_desc FROM workout_details WHERE workout_flex_id='".addslashes(trim($workout_flexid))."' and lang_id={$lanId}";

			$result = $GLOBALS['db']->getRow($query,DB_FETCHMODE_ASSOC);

			$workout_desc = $result['workout_desc'];

			return $workout_desc;

		}

		/*

			Function 			: _getWorkoutDetail

			Usage	   			: To get titl & description of a workout

			Variable Passing 	: workout flex id

		*/

		public function _getWorkoutDetailAll($workout_flexid,$lanId){

			$query = "SELECT workout_title, workout_desc, workout_provide FROM workout_details WHERE workout_flex_id='".addslashes(trim($workout_flexid))."' and lang_id={$lanId}";

			$result = $GLOBALS['db']->getRow($query,DB_FETCHMODE_ASSOC);

			return $result;

		}

		/*

			Function 			: _getUserDetails

			Usage	   			: To get user details

			Variable Passing 	:  userid

		*/

		public function _getUserDetails($userid){

			$query = "SELECT * FROM user_master WHERE user_id={$userid}";

			$result = $GLOBALS['db']->getRow($query,DB_FETCHMODE_ASSOC);

			return $result;

		}

		//get user nike details

		public function _getNikeDetails($userid){

			$query = "SELECT * FROM nike WHERE nike_userid={$userid}";

			$result = $GLOBALS['db']->getRow($query,DB_FETCHMODE_ASSOC);

			return $result;

		}

		//get user nike details . pass - userid

		public function _getNikeUserPassword($userid){

			$query = "SELECT * FROM nike WHERE nike_login!='' and nike_password!='' and nike_userid={$userid}";

			$result = $GLOBALS['db']->getRow($query,DB_FETCHMODE_ASSOC);

			return $result;

		}

		/*

			Function 			: _getFirstWorkout

			Usage	   			: To get first workout of a training program

			Variable Passing 	: training program flex id

		*/

		public function _getFirstWorkout($pgm_flexid,$lanId){

			$query = "SELECT workout_flex_id FROM program_workout WHERE training_flex_id='".addslashes(trim($pgm_flexid))."' AND lang_id={$lanId} ORDER BY workout_order ASC";

			$result = $GLOBALS['db']->getRow($query,DB_FETCHMODE_ASSOC);

			$workout_flexid = stripslashes(trim($result['workout_flex_id']));

			$query1 = "SELECT workout_desc FROM workout_details WHERE workout_flex_id='".addslashes(trim($workout_flexid))."' and lang_id={$lanId}";

			$result = $GLOBALS['db']->getRow($query1,DB_FETCHMODE_ASSOC);

			return $result['workout_desc'];

		}

		// get first workout ID of a program

		public function _getFirstWorkoutId($pgm_flexid,$lanId){

			$query = "SELECT workout_flex_id FROM program_workout WHERE training_flex_id='".addslashes(trim($pgm_flexid))."' AND lang_id={$lanId} ORDER BY workout_order ASC";

			$result = $GLOBALS['db']->getRow($query,DB_FETCHMODE_ASSOC);

			$workout_flexid = stripslashes(trim($result['workout_flex_id']));

			return $workout_flexid;

		}

		 // get corresponding workout day ie,3,5...

		public function _getWorkoutOrder($pgm_flexid,$workout_flexid,$lanId)

		{

		$query 	= 	"SELECT workout_order FROM program_workout WHERE training_flex_id='".trim($pgm_flexid)."' and workout_flex_id='".trim($workout_flexid)."' and lang_id={$lanId} ORDER BY workout_order ASC";

		  $row = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC); 

		  return $row['workout_order'];

		

		}

		// get workout flexid using program flex id,language id and workout order.

		public function _getWorkoutTodayLast($pgm_flexid,$workOrder,$lanId)

		{

		$query 	= 	"SELECT workout_flex_id FROM program_workout WHERE training_flex_id='".addslashes(trim($pgm_flexid))."' and workout_order={$workOrder} and lang_id={$lanId} ORDER BY workout_order ASC";

		  $row = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC); 

		  return stripslashes(trim($row['workout_flex_id']));

		

		}

		// get tag offline detected genres. pass userid as argyument

		public function _getUserGenres($userid){

			$query = "SELECT id,genre_name,remember_status,file_count FROM tag_detected_genre WHERE user_id ={$userid}";

			$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);

			return $result;

		}

		

		// get tag offline detected genres that contain in the table 'user_memory_wrk_genre' (for memorize regarding genre selected). pass userid as argyument

		public function _getUserMemoryGenres($userid){

			$query = "SELECT id, genre_name_memory,vocal_coach_status, random_genre_status, remember_status FROM user_memory_wrk_genre WHERE user_id ={$userid}";

			//mail('webtesters@gmail.com',"Update Query",$query);

			$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);

			return $result;

		}

		

		// for fetching the training pgm for listing

		public function _getAllTraining($lanId,$field,$type,$start,$len){

			$query = "SELECT program_title, program_target, program_master_id, program_master.program_image as programImage" 

			." FROM program_detail,program_master WHERE " 

			."program_master_id = program_id AND language_id = {$lanId} ORDER BY {$field} {$type} LIMIT $start,$len";

			

			$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);

			return $result;

		}

		// for fetching the training pgm for listing

		public function _getTotalCount($lanId){

			$query = "SELECT COUNT(program_master_id) AS cnt FROM program_detail,program_master WHERE " 

			."program_master_id = program_id AND language_id = {$lanId} ";

			

			$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC); //

			return $result;

		}

		

		// for fetching the training general item for listing

		public function _getAllGenItem($genItem,$lanId){

			$query = "SELECT item_name, flex_id, general_id FROM general WHERE table_name = '".$genItem."' AND language_id = {$lanId} ORDER BY item_name ";

			

			$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);

			return $result;

		}

		

		// for fetching the training general item for listing

		public function _getAllPgmItem($pgmItem,$lanId,$orderBy){

			$query = "SELECT  $pgmItem, flex_id, program_id FROM program_master WHERE program_status = '4' ORDER BY {$orderBy} ";

			

			$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);

			return $result;

		}

		// for fetching the training detail

		public function _getAllPgmDetail($pgmDetail,$lanId,$orderBy){

			$query = "SELECT  $pgmDetail FROM program_detail, program_master WHERE program_master.flex_id = program_detail.flex_id  AND program_master.program_status = '4' AND program_detail.language_id ={$lanId} ORDER BY {$orderBy}";

			

			$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);

			return $result;

		}

		// get specific program detail

		public function _getOnePgmDetail($pgmId,$pgmDetail,$lanId){

			$query = "SELECT  $pgmDetail FROM program_detail WHERE program_master_id = {$pgmId} AND language_id ={$lanId}";

			$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

			return $result;

		}

		//checks user login or not

		public function _checkLogin(){

			$bool	= true;

			if(!isset($_SESSION['user']['userId']))

			 $bool	= false;

			return $bool;

		}

		// find number of free days left for user

		public function _findBalanceFreeDays($userid,$freedays){

			$curdate = date('Y-m-d');

			$query 	= 	"SELECT user_doj as joindate FROM user_master WHERE user_id=".addslashes($userid);

			$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

			$joindate = date('Y-m-d',strtotime(trim($result['joindate'])));

			$qry = "SELECT ADDDATE('$joindate', INTERVAL '$freedays' DAY )"; 

			$res = $GLOBALS['db']->getRow($qry, DB_FETCHMODE_ARRAY);

			$freeEndDate = 	$res[0];

			$qry ="SELECT DATEDIFF('$freeEndDate', '$curdate')";

			$res = $GLOBALS['db']->getRow($qry, DB_FETCHMODE_ARRAY);

			$dayCount = $res[0];

			return $dayCount;

			

		}

		/*  Checks whether the user generated any workout before */
		public function _checkUserGeneratedWorkout($userid){
		$bool= false;
		$query 	= 	"SELECT count(*) as max FROM payment WHERE payment_userid=".addslashes($userid)." AND payment_status='1'";
			$r = $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);
			$num = $r[0]->max;
			if($num > 0)
			{
			$bool= true;
			}
			return $bool;
		
		}

		/*  checks whether user is in free period */

		public function _checkUserFreePeriod($userid,$freedays){

			$bool	= true;

			$curdate = date('Y-m-d');

			$query 	= 	"SELECT user_doj as joindate FROM user_master WHERE user_id=".addslashes($userid);

			$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

			$joindate = date('Y-m-d',strtotime(trim($result['joindate'])));

			$qry = "SELECT ADDDATE('$joindate', INTERVAL '$freedays' DAY )"; 

			$res = $GLOBALS['db']->getRow($qry, DB_FETCHMODE_ARRAY);

			$freeEndDate = 	$res[0];

			$qry ="SELECT DATEDIFF('$freeEndDate', '$curdate')";

			$res = $GLOBALS['db']->getRow($qry, DB_FETCHMODE_ARRAY);

			$dayCount = 	$res[0];

			

			if($dayCount < 0)

				$bool = false;

			return $bool;

			

			

		}

		// checks whether program start date is in free period

		public function _checkFreePeriod($userid,$freedays,$startdate){

			$bool	= true;

			$curdate = date('Y-m-d');

			$query 	= 	"SELECT user_doj as joindate FROM user_master WHERE user_id=".addslashes($userid);

			$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

			$joindate = date('Y-m-d',strtotime(trim($result['joindate'])));

			$qry = "SELECT ADDDATE('$joindate', INTERVAL '$freedays' DAY )"; 

			$res = $GLOBALS['db']->getRow($qry, DB_FETCHMODE_ARRAY);

			$freeEndDate = 	$res[0];

			$startdate = date('Y-m-d',strtotime(trim($startdate)));

			$qry ="SELECT DATEDIFF('$freeEndDate', '$startdate')";

			$res = $GLOBALS['db']->getRow($qry, DB_FETCHMODE_ARRAY);

			$dayCount = 	$res[0];

			if($dayCount <= 0)

				$bool = false;

			return $bool;

		}

		

		// checks whether user is inside the paid perid

		public function _checkUserPaymentPeriod($userid){

			$bool	= true;

			$curdate = date('Y-m-d');

			$query 	= 	"SELECT count(*) as max FROM payment WHERE payment_userid=".addslashes($userid)." AND payment_status='1'";

			$r = $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);

			$num = $r[0]->max;

			if($num > 0)

			{

				$query 	= 	"SELECT payment_expdate FROM payment WHERE payment_userid=".addslashes($userid)." AND payment_status='1' ORDER BY payment_expdate DESC";

				$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

				$PaymentExpDate = $result['payment_expdate'];

				$qry ="SELECT DATEDIFF('$PaymentExpDate', '$curdate')";

				$res = $GLOBALS['db']->getRow($qry, DB_FETCHMODE_ARRAY);

				$dayCount = 	$res[0];

				if($dayCount < 0)

					$bool = false;

			}

			else { 	$bool = false; }

			return $bool;

			

		}



		// checks whether user is inside the paid perid even if he has preponded the workout

		public function _checkUserPaymentPeriod_evenIfPreponed($userid,$workoutday,$pgm_subscribe_date){

			$bool	= true;

			$curdate = date('Y-m-d');

			$query 	= 	"SELECT count(*) as max FROM payment WHERE payment_userid=".addslashes($userid)." AND payment_status='1'";

			$r = $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);

			$num = $r[0]->max;

			if($num > 0)

			{

				$query 	= 	"SELECT payment_expdate FROM payment WHERE payment_userid=".addslashes($userid)." AND payment_status='1' ORDER BY payment_expdate DESC";

				$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

				$PaymentExpDate = $result['payment_expdate'];



				$pgm_subscribe_date;

				$workoutday;

				$dy = strtotime($pgm_subscribe_date." + ".$workoutday." Days");

				$pgm_subscribe_date_workoutday = date("Y-m-d",$dy);

				$qry ="SELECT DATEDIFF('$PaymentExpDate', '$pgm_subscribe_date_workoutday')";

				//$qry ="SELECT DATEDIFF('$first_workout_date', '$pgm_subscribe_date_workoutday')";

				$res = $GLOBALS['db']->getRow($qry, DB_FETCHMODE_ARRAY);

				$dayCount = 	$res[0];

				if($dayCount < 0)

					$bool = false;

			}

			else { 	$bool = false; }

			return $bool;

			

		}

		

		//Check the  duration between current date and the workout date

		public function  _validateMp3GenarationPeriod($userid,$workoutday){

				$bool	= true;

				$query	= "select  T1.codetype as codetype  from  gift_code as T1, gift_userdetails as T2 where  T2.code= T1.code and T2.user_id = '".addslashes($userid)."'";//Query for collecting the gift cod period (if the client uses the gift code)

				$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

				

				if(count($result)>0){

					$codetype	= $result['codetype'];//Giftcode valid period (example : 4 months)

					$tmpArr		= explode(' ',$codetype);

					$validMonths= $tmpArr[0];//Get the gift valid months

					$validDates	= $validMonths*31;//Convert gift code valid months in to days

					

					$query			= "select user_doj from user_master where user_id = ' ".addslashes($userid)." ' ";//Get the date of join of user

					$result 			= $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

					$tmpArr		= explode(' ',$result['user_doj']);//Date of join will be in datetime format of mysql

					$dateOfJoin	= $tmpArr[0];//parsing date from datetime format

					

					$query			= "SELECT DATEDIFF('$workoutday','$dateOfJoin') as days ";//Take the differance of workout date and date of oin

					$result 			= $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

					$diffDays		= $result ['days'];//Get the date diff.

					if($diffDays>$validDates){// If the period of the gift code is over

						return 	true;

					}else{//if($diffDays>$validDates)//If the user in gift perid

						$query			= "SELECT DATEDIFF('$workoutday', CURDATE( )) AS days";

						$result 			= $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

						$diffDays		= $result ['days'];

						if($diffDays>31){//The workout date is not in the one month period

							return 	false;	

						}else{

							return 	true;//The workout date is in the one month period

						}

					}//else of if($diffDays>$validDates)					

				}else{//if(count($result)>0)

					return 	true;

				}//else of if(count($result)>0)

		}//public function  _validateMp3GenarationPeriod($userid,$workoutday)
		
		
		
		
		//Check the  duration between current date and the workout date in case of oxylane

		public function  _validateOxylaneUser($userid,$workoutday){

				$bool	= true;

				$query	= "select  T1.no_of_months as free_period  from  campaign_manage as T1, user_oxylane_details as T2 where  T2.jiwok_code= T1.camp_id and T2.user_id = '".addslashes($userid)."' order by T2.payment_date desc limit 0,1";//Query for collecting the oxylane card period (if the client uses the oxylane card for payment)

				$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

				

				if(count($result)>0){

					$validMonths= $result['free_period'];//Get the oxylane card valid months

					$validDates	= $validMonths*31;//Convert gift code valid months in to days

					

					$query			= "select user_doj from user_master where user_id = ' ".addslashes($userid)." ' ";//Get the date of join of user

					$result 			= $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

					$tmpArr		= explode(' ',$result['user_doj']);//Date of join will be in datetime format of mysql

					$dateOfJoin	= $tmpArr[0];//parsing date from datetime format

					

					$query			= "SELECT DATEDIFF('$workoutday','$dateOfJoin') as days ";//Take the differance of workout date and date of join

					$result 			= $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

					$diffDays		= $result ['days'];//Get the date diff.

					if($diffDays>$validDates){// If the period of theoxylane card is over

						return 	true;

					}else{

						$query			= "SELECT DATEDIFF('$workoutday', CURDATE( )) AS days";

						$result 			= $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

						$diffDays		= $result ['days'];

						if($diffDays>31){//The workout date is not in the one month period

							return 	false;	

						}else{

							return 	true;//The workout date is in the one month period

						}

					}//else of if($diffDays>$validDates)					

				}else{//if(count($result)>0)

					return 	true;

				}//else of if(count($result)>0)

		}//public function  _validateOxylaneUser($userid,$workoutday)



	public function _checkoxylaneUser($userid){
	
	$query 	= 	"SELECT * from user_oxylane_details where user_id=".addslashes($userid)." AND status='1'";

	$result 	= mysql_query($query);
	$num		=	mysql_num_rows($result);
			if($num>0)
			{
			return true;
			}
			else
			{
			return false;
			}
	
	}




		// find account expiry date of user

		public function _findAccountExpireDate($userid){

			$PaymentExpDate	= '';

			$query 	= 	"SELECT count(*) as max FROM payment WHERE payment_userid=".addslashes($userid)." AND payment_status='1'";

			$r = $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);

			$num = $r[0]->max;

			if($num > 0)

			{

				$query 	= 	"SELECT payment_firstdate FROM payment WHERE payment_userid=".addslashes($userid)." AND payment_status='1' && payment_firstdate!='0000-00-00' ORDER BY payment_firstdate DESC";

				$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

				$PaymentFirstDate = $result['payment_firstdate'];

				$qry = "SELECT ADDDATE( '$PaymentFirstDate', INTERVAL 12 MONTH ) "; 

				$res = $GLOBALS['db']->getRow($qry, DB_FETCHMODE_ARRAY);

				$PaymentExpDate = 	$res[0];

			}

				return $PaymentExpDate;

			

		}

		// find number of days for payment period to expire

		public function _getUserPaymentBalanceDays($userid){

			$curdate = date('Y-m-d');

			$query 	= 	"SELECT payment_expdate FROM payment WHERE payment_userid=".addslashes($userid)." AND payment_status='1' ORDER BY payment_expdate DESC";

				$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

				$PaymentExpDate = $result['payment_expdate'];

				$qry ="SELECT DATEDIFF('$PaymentExpDate', '$curdate')";

				$res = $GLOBALS['db']->getRow($qry, DB_FETCHMODE_ARRAY);

				$dayCount = 	$res[0];

				return $dayCount;

			}

		// find payment expire date of user

		public function _getPaymentExpDate($userid)

		{

		  $query 	= 	"SELECT payment_expdate FROM payment WHERE payment_userid=".addslashes($userid)." AND payment_status='1' ORDER BY payment_expdate DESC";

				$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

				$PaymentExpDate = $result['payment_expdate'];

				return $PaymentExpDate;

		}

		

		// checks whether program start date is inside the paid perid

		

		public function _checkPaymentPeriod($userid,$startdate){

			$bool	= true;

			$curdate = date('Y-m-d');

			$startdate = date('Y-m-d',strtotime(trim($startdate)));

			$query 	= 	"SELECT count(*) as max FROM payment WHERE payment_userid=".addslashes($userid)." AND payment_status='1'";

			$r = $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);

			$num = $r[0]->max;

			if($num > 0)

			{

				$query 	= 	"SELECT payment_expdate FROM payment WHERE payment_userid=".addslashes($userid)." AND payment_status='1' ORDER BY payment_expdate DESC";

				$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

				$PaymentExpDate = $result['payment_expdate'];

				$qry ="SELECT DATEDIFF('$PaymentExpDate', '$startdate')";

				$res = $GLOBALS['db']->getRow($qry, DB_FETCHMODE_ARRAY);

				$dayCount = 	$res[0];

				if($dayCount < 0)

					$bool = false;

			}

			else { 	$bool = false; }

			return $bool;

		}

		// checks whether program start date is greater than or equal to current date. if yes,return true else false

		public function _checkValidDate($startdate){

			$bool	= true;

			$startdate = date('Y-m-d',strtotime($startdate));

			$curdate = date('Y-m-d');

			$qry ="SELECT DATEDIFF('$startdate', '$curdate')";

			$res = $GLOBALS['db']->getRow($qry, DB_FETCHMODE_ARRAY);

			$dayCount = $res[0];

			if($dayCount < 0)

			 $bool = false;

			return $bool; 

		}

		// get free days from user master table

		public function _getFreeDays($userid){

			$query = "SELECT user_free_period FROM user_master WHERE user_id='$userid'";

			$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);
            if($result)
			{
			$freeDays = $result['user_free_period'];
			}
			else
			{
			$freeDays=0;
			}

			return $freeDays;

		}

		// get the training program expire date using the selected start date

		public function _getProgramExpireDate_apr1($programid,$startdate){

			$query = "SELECT program_schedule,schedule_type FROM program_master WHERE program_id='$programid'";

			$result = $GLOBALS['db']->getRow($query,DB_FETCHMODE_ASSOC);

			$program_schedule = $result['program_schedule']; // returns no: of week or day  .  example 5,7,...

			$schedule_type = $result['schedule_type']; // return 'week' or 'day'

			$schedule_type = strtolower($schedule_type);

			if(trim($schedule_type)=="week" || trim($schedule_type)=="weeks")

			{

			 $qry = "SELECT ADDDATE( '$startdate', INTERVAL '$program_schedule' WEEK ) "; 

			

			}

			if(trim($schedule_type)=="day" || trim($schedule_type)=="days")

			{

			 $qry = "SELECT ADDDATE( '$startdate', INTERVAL '$program_schedule' DAY ) "; 

			}

			

			$res = $GLOBALS['db']->getRow($qry, DB_FETCHMODE_ARRAY);

			$programExpDate = 	$res[0];

			return $programExpDate;

		}

		// get the training program expire date using the selected start date

		public function _getProgramExpireDate($programid,$startdate){

			$lanId = $this->language;

			$query = "SELECT flex_id FROM program_master WHERE program_id='$programid'";

			$result = $GLOBALS['db']->getRow($query,DB_FETCHMODE_ASSOC);

			$pgm_flexid = trim(stripslashes($result['flex_id']));

			$query1 	= 	"SELECT workout_flex_id,workout_date FROM program_workout WHERE training_flex_id='".trim($pgm_flexid)."'  and lang_id={$lanId} ORDER BY workout_order ASC";

		  

		  $r = $GLOBALS['db']->getAll($query1, DB_FETCHMODE_ASSOC);

		  $cnt= count($r);

		  $workoutDate='';

		  for($i=0;$i<$cnt;$i++)

		{

		  $workoutDay = $r[$i]['workout_date'] - 1;

		  $qry = "SELECT ADDDATE( '$startdate', INTERVAL '$workoutDay' DAY ) "; 

		  $res = $GLOBALS['db']->getRow($qry, DB_FETCHMODE_ARRAY);

		  $workoutDate = $res[0];	

		  

		}

			$qry1 = "SELECT ADDDATE( '$workoutDate', INTERVAL 1 DAY ) "; 

		  	$res1 = $GLOBALS['db']->getRow($qry1, DB_FETCHMODE_ARRAY);

		  	$programExpDate = $res1[0];	

			return $programExpDate;

		}

		// check whether the user has already subscribed for other program at that time. if yes,return true ,else false

		public function _checkProgramSubscription_old($userid)

		{

		  $bool=false;

		  $query 	= 	"SELECT count(*) as max FROM programs_subscribed WHERE user_id=".addslashes($userid);

			$r = $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);

			$num = $r[0]->max;

			if($num > 0)

			{

				$query 	= 	"SELECT program_expdate FROM programs_subscribed WHERE user_id=".addslashes($userid)." ORDER BY program_expdate DESC";

				$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

				$ProgramExpDate = $result['program_expdate'];

				$curdate = date('Y-m-d');

				$qry ="SELECT DATEDIFF('$ProgramExpDate','$curdate')";

				$res = $GLOBALS['db']->getRow($qry, DB_FETCHMODE_ARRAY);

				$dayCount = $res[0];

				if($dayCount >= 0)

			 	 $bool = true;

		   }

		   return $bool;

		}

                // checks whether user subscribed for single/program program training

		public function _checkProgramTypeSubscribed($userid, $workout_flex_id)

		{

		  $bool=false;

		  $curdate = date('Y-m-d');

		  $query = "SELECT program_type FROM programs_subscribed WHERE user_id=".addslashes($userid)." and  program_id='".$workout_flex_id."'";

		  $result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

		  $program_type = $result['program_type'];

		   

		   return $program_type;

		}

		// checks whether user subscribed for single/long program training

		public function _checkProgramSubscription($userid,$programType)

		{

		  $bool=false;

		  $curdate = date('Y-m-d');

		  if(trim($programType=="program")) // // check for other long program training subscription

			{

		  $query 	= 	"SELECT count(*) as max FROM programs_subscribed WHERE user_id=".addslashes($userid)." AND program_type='program' AND complete_status='p' AND subscribe_status='1'";

			$r = $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);

			$num = $r[0]->max;

			if($num > 0)

			{

			  $bool = true;

			} 

		   }

		   return $bool;

		}

		// check whether the particular workout of user exists in queue

		public function _checkWorkoutExistsInQueue($userid,$pgm_flexid,$workout_flexid)

		{

			$bool=false;

		  	$query 	= 	"SELECT count(*) as max FROM program_queue WHERE user_id=".addslashes($userid)." AND program_flex_id='".addslashes($pgm_flexid)."' AND workout_flex_id='".addslashes($workout_flexid)."' AND status='1'";

			$r = $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);

			$num = $r[0]->max;

			if($num > 0)

			{

			  $bool = true;

			} 

		    return $bool;

		}

		// check whether user has subscribed for the specified training or not

		public function _checkUserSubscribed($userid,$programid) 

		{

		  $bool=false;

			$query 	= 	"SELECT count(*) as max FROM programs_subscribed WHERE user_id=".addslashes($userid)." AND program_id='".addslashes($programid)."' AND complete_status='p' AND subscribe_status='1'";

			$r = $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);

		 	$num = $r[0]->max;

			if($num > 0)

			{

			  $bool = true;

			} 

		  return $bool;

		}

		// find next date

		public function _findDateNext($value,$workoutDay)

		{

		 $qry = "SELECT ADDDATE( '$value', INTERVAL '$workoutDay' DAY ) "; 

		 $res = $GLOBALS['db']->getRow($qry, DB_FETCHMODE_ARRAY);

		 $postdate = $res[0];

		 return $postdate;

		}

		// find previous date

		public function _findDatePrev($value,$workoutDay)

		{

		 $qry = "SELECT DATE_SUB( '$value', INTERVAL '$workoutDay' DAY ) "; 

		 $res = $GLOBALS['db']->getRow($qry, DB_FETCHMODE_ARRAY);

		 $postdate = $res[0];

		 return $postdate;

		}

		// get training program subscription Id

		public function _getSubscriptionDetails($userid,$programid) 

		{

		 

		  $query 	= 	"SELECT programs_subscribed_id,subscribed_date,program_startdate,posponded_date,program_expdate,program_type FROM programs_subscribed WHERE user_id=".addslashes($userid)." AND program_id=".addslashes($programid)." AND complete_status='p' AND subscribe_status='1'";

			$result = $GLOBALS['db']->getRow($query,DB_FETCHMODE_ASSOC);

			return $result;

		}

		// check whether user has already subscribed for program

		public function _checkSubscribedDate($startdate,$userid,$programid,$programType)

		{

		$lanId = $this->language;

		  $bool=false;

		  if(trim($programType=="single")) // // check for other long program training subscription

			{

		  $query 	= 	"SELECT t1.flex_id as flex_id,t2.program_startdate as program_startdate FROM program_master as t1,programs_subscribed as t2 WHERE t2.program_id=t1.program_id and t2.user_id=".addslashes($userid)." AND t2.program_type='program' AND t2.complete_status='p'";

		  }

		  if(trim($programType=="program")) // // check for other long program training subscription

			{

		  $query 	= 	"SELECT t1.flex_id as flex_id,t2.program_startdate as program_startdate FROM program_master as t1,programs_subscribed as t2 WHERE t2.program_id=t1.program_id and t2.user_id=".addslashes($userid)." AND t2.program_type='single' AND t2.complete_status='p'";

		  }

		  $row = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

		  $pgm_flexid = $row['flex_id'];

		  $pgmstartdate = $row['program_startdate'];

		  $query 	= 	"SELECT workout_date FROM program_workout WHERE training_flex_id='".trim($pgm_flexid)."'  and lang_id={$lanId} ORDER BY workout_order ASC";

		  $r = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);

		  $c= count($r);

		  $workdate=array();

		for($i=0;$i<$c;$i++)

		{

		  $day = $r[$i]['workout_date'] - 1;

	  	  $qry = "SELECT ADDDATE( '$pgmstartdate', INTERVAL '$day' DAY ) "; 

		  $res = $GLOBALS['db']->getRow($qry, DB_FETCHMODE_ARRAY);

		  $workdate[] = $res[0];	

		}

		if (in_array($startdate, $workdate)) {

    		$bool =true;

		}

		

		   return $bool;

		}

		// get workout orders of a training program

		public function _getWorkoutOrders($pgm_flexid)

		{

		$lanId = $this->language;

		$order=array();

		$query 	= 	"SELECT workout_order FROM program_workout WHERE training_flex_id='".trim($pgm_flexid)."'  and lang_id={$lanId} ORDER BY workout_order ASC";

		  

		  $r = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);

		$cnt= count($r);

		 for($i=0;$i<$cnt;$i++)

		{

		  $order[] = $r[$i]['workout_order'];

		}

		return $order;

		}

		

		

		/* get the workout flex ids and find corresponding workout dates of program subscribed by user and store as an associative array . workoutDates[workoutflexid]=workoutdate*/

		

		public function _getTrainingCalWorkoutDates($userid)

		{

		  $lanId = $this->language;

		  $query 	= 	"SELECT t1.flex_id as flex_id,t2.program_startdate as program_startdate,t2.program_expdate as program_expdate,t2.posponded_date as posponded_date FROM program_master as t1,programs_subscribed as t2 WHERE t2.program_id=t1.program_id  and t2.user_id=".addslashes($userid)." AND t2.program_type='program' AND t2.complete_status='p' AND t2.subscribe_status='1'";

		  

		  $row = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

		  $pgm_flexid = $row['flex_id'];

		  $pgmstartdate = $row['program_startdate'];

		  $posponded_date = trim($row['posponded_date']);

		  $query 	= 	"SELECT workout_flex_id,workout_date FROM program_workout WHERE training_flex_id='".trim($pgm_flexid)."'  and lang_id={$lanId} ORDER BY workout_order ASC";

		  

		  $r = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);

		  $cnt= count($r);



		  $workoutDates=array();

		  

		if($posponded_date != "")

		{

		  $workoutArray = explode(",",$posponded_date);

		  for($i=0;$i<$cnt;$i++)

		{

		  $workout_flexid = $r[$i]['workout_flex_id'];

		  $workout_flexid = $workout_flexid."@@".$i;

	  	  $workoutDates[$workout_flexid] = $workoutArray[$i];	

		}

		

		}

		else

		{ 

		for($i=0;$i<$cnt;$i++)

		{

		  $workoutDay = $r[$i]['workout_date'] - 1;

		  $workout_flexid = $r[$i]['workout_flex_id'];

		  $workout_flexid = $workout_flexid."@@".$i;

	  	  $qry = "SELECT ADDDATE( '$pgmstartdate', INTERVAL '$workoutDay' DAY ) "; 

		  $res = $GLOBALS['db']->getRow($qry, DB_FETCHMODE_ARRAY);

		  $workoutDates[$workout_flexid] = $res[0];	

		  

		}

		}

		

		

		   return $workoutDates;

		}



		//to get the workout days 1,3,7,.....45, 48, etc

		public function _getTrainingCalWorkoutDays($userid)

		{

		  $lanId = $this->language;

		  $query 	= 	"SELECT t1.flex_id as flex_id,t2.program_startdate as program_startdate,t2.program_expdate as program_expdate,t2.posponded_date as posponded_date FROM program_master as t1,programs_subscribed as t2 WHERE t2.program_id=t1.program_id  and t2.user_id=".addslashes($userid)." AND t2.program_type='program' AND t2.complete_status='p' AND t2.subscribe_status='1'";

		  

		  $row = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

		  $pgm_flexid = $row['flex_id'];

		  $pgmstartdate = $row['program_startdate'];

		  $posponded_date = trim($row['posponded_date']);

		  $query 	= 	"SELECT workout_flex_id,workout_date FROM program_workout WHERE training_flex_id='".trim($pgm_flexid)."'  and lang_id={$lanId} ORDER BY workout_order ASC";

		  

		  $r = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);

		  $cnt= count($r);

		  $workoutDates=array();

		  for($i=1;$i<=$cnt;$i++)

		{

			$j = $i - 1;

			$workoutDates[$i] = $r[$j]['workout_date'];

		}

		

		   return $workoutDates;

		}



		// get work out dates of  specified program

		public function _getTrainingCalWorkoutDatesPgm($userid)

		{

		  $lanId = $this->language;

		  $workoutDates=array();

		   //$query1 	= 	"SELECT t1.program_id as program_id,t1.flex_id as flex_id,t2.program_startdate as program_startdate,t2.program_expdate as program_expdate,t2.posponded_date as posponded_date FROM program_master as t1,programs_subscribed as t2 WHERE t2.program_id=t1.program_id  and t2.user_id=".addslashes($userid)." AND t2.program_type='program' AND t2.subscribe_status='1'";

		   if($userid==77){

		   $query1 	= 	"SELECT t1.program_id as program_id,t1.flex_id as flex_id,t2.program_startdate as program_startdate,t2.program_expdate as program_expdate,t2.posponded_date as posponded_date FROM program_master as t1,programs_subscribed as t2 WHERE t2.program_id=t1.program_id  and t2.user_id=".addslashes($userid)." AND t2.program_type='program' AND (t2.subscribe_status='1' or  t2.subscribe_status='0')";

		   //mail('webtesters@gmail.com','Query Result',$query1);

		   }else{

		   $query1 	= 	"SELECT t1.program_id as program_id,t1.flex_id as flex_id,t2.program_startdate as program_startdate,t2.program_expdate as program_expdate,t2.posponded_date as posponded_date FROM program_master as t1,programs_subscribed as t2 WHERE t2.program_id=t1.program_id  and t2.user_id=".addslashes($userid)." AND t2.program_type='program' AND (t2.subscribe_status='1' or  t2.subscribe_status='0')";

		   

		   }

		   

		   $rest = $GLOBALS['db']->getAll($query1, DB_FETCHMODE_ASSOC);

			for($j=0;$j<count($rest);$j++){

					$pgm_flexid = trim(stripslashes($rest[$j]['flex_id']));

					$program_id = trim(stripslashes($rest[$j]['program_id']));

					$posponded_date = trim(stripslashes($rest[$j]['posponded_date']));

					$pgmstartdate = trim(stripslashes($rest[$j]['program_startdate']));

					$query 	= 	"SELECT workout_flex_id,workout_date FROM program_workout WHERE training_flex_id='".trim($pgm_flexid)."'  and lang_id={$lanId} ORDER BY workout_order ASC";

					

			  $r = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);

			  $cnt= count($r);

			if($posponded_date != "")

			{

				$workoutArray = explode(",",$posponded_date);

				for($i=0;$i<$cnt;$i++)

				{

//***The code below is for removing the overlapping while showing the history calendar****



				//if($userid==77){

					$nxtPgmStartDate 	= trim(stripslashes($rest[$j+1]['program_startdate']));

					$tempDateSplitArr	= explode('-',$nxtPgmStartDate);

				  	if(count($tempDateSplitArr)>1){

						$nxtPgmStartTime	= mktime(1,1,1,$tempDateSplitArr[1],$tempDateSplitArr[0],$tempDateSplitArr[2]);

				  		$tempDateSplitArr	= explode('-',$workoutArray[$i]);

				  		$workOutDatetime	= mktime(1,1,1,$tempDateSplitArr[1],$tempDateSplitArr[0],$tempDateSplitArr[2]);

						if($nxtPgmStartTime<= $workOutDatetime){

							unset($nxtPgmStartDat);

							unset($tempDateSplitArr);

							unset($workOutDatetime);

							break;

						}				  		

					}//if(count($tempDateSplitArr)>1)

				//}//if($userid==77) for Gifcy's account only

//***The code above is for removing the overlapping while showing the history calendar****					



				

				  	$workoutDates[] = $program_id."|".$r[$i]['workout_flex_id']."|".$workoutArray[$i];	

				}

			

			}

			else

			{ 

				for($i=0;$i<$cnt;$i++)

				{

				  $workoutDay = $r[$i]['workout_date'] - 1;

				  $qry = "SELECT ADDDATE( '$pgmstartdate', INTERVAL '$workoutDay' DAY ) "; 

				  $res = $GLOBALS['db']->getRow($qry, DB_FETCHMODE_ARRAY);



//***The code below is for removing the overlapping while showing the history calendar****



				//if($userid==77){

					$nxtPgmStartDate 	= trim(stripslashes($rest[$j+1]['program_startdate']));

					$tempDateSplitArr	= explode('-',$nxtPgmStartDate);

				  	if(count($tempDateSplitArr)>1){

						$nxtPgmStartTime	= mktime(1,1,1,$tempDateSplitArr[1],$tempDateSplitArr[0],$tempDateSplitArr[2]);

				  		$tempDateSplitArr	= explode('-',$res[0]);

				  		$workOutDatetime	= mktime(1,1,1,$tempDateSplitArr[1],$tempDateSplitArr[0],$tempDateSplitArr[2]);

						if($nxtPgmStartTime<= $workOutDatetime){

							unset($nxtPgmStartDat);

							unset($tempDateSplitArr);

							unset($workOutDatetime);

							break;

						}				  		

					}//if(count($tempDateSplitArr)>1)

				//}//if($userid==77) for Gifcy's account only

//***The code above is for removing the overlapping while showing the history calendar****					

				  

				  $workoutDates[] = $program_id."|".$r[$i]['workout_flex_id']."|".$res[0];	

				}

			}

			}//for($j=0;$j<count($rest);$j++)

			/*if($userid==77){

				$msg	= "$userid";

				for($i=0;$i<count($workoutDates);$i++){

					$msg	.= $workoutDates[$i]."<br>";

				}//for($i=0;$i<count($workoutDates);$i++)

				mail('webtesters@gmail.com','Query Result',$msg);

			

			}//if($userid==77)*/

		   	return $workoutDates;

		}

		

		/* get training program id of training subscribed by user */

		public function _getUserTrainingProgram($userid)

		{

		  $lanId = $this->language;

		  $query 	= 	"SELECT t1.flex_id as flex_id,t1.program_id as program_id,t1.program_image as program_image,t2.program_expdate as program_expdate,t2.posponded_date FROM program_master as t1,programs_subscribed as t2 WHERE t2.program_id=t1.program_id  and t2.user_id=".addslashes($userid)." AND t2.program_type='program' AND t2.complete_status='p' AND t2.subscribe_status='1'";

		$row = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);  

		return $row;

		} 

		/* get training program id and program subscribe id of training subscribed by user */

		public function _getUserTrainingProgramConfirm($userid){

		  $lanId = $this->language;

		  $query 	= 	"SELECT t1.flex_id as flex_id,t1.program_id as program_id,t2.programs_subscribed_id as programs_subscribed_id,t3.program_title as program_title FROM program_master as t1,programs_subscribed as t2,program_detail as t3 WHERE t2.program_id=t1.program_id  and t3.program_master_id=t1.program_id and t3.language_id={$lanId} and t2.user_id=".addslashes($userid)." AND t2.program_type='program' AND t2.complete_status='p' AND t2.subscribe_status='1'";

		$row = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);  

		return $row;

		} 

		

		/* get training program id of training subscribed by user */

		public function _getAllTrainingPrograms($userid)

		{

		  $lanId = $this->language;

		  $query 	= 	"SELECT t1.flex_id as flex_id,t1.program_id as program_id,t2.program_startdate as program_startdate,t2.program_expdate as program_expdate,t2.posponded_date as posponded_date FROM program_master as t1,programs_subscribed as t2 WHERE t2.program_id=t1.program_id  and t2.user_id=".addslashes($userid)." AND t2.program_type='program' AND t2.subscribe_status='1'";

		 $row = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);  

		return $row;

		} 

		// get all single training programs of user

		public function _getAllSingleTrainingPrograms($userid,$start,$len)

		{

		  $lanId = $this->language;

		  $query 	= 	"SELECT DISTINCT(t1.program_id) as program_id,t2.program_image as program_image,t3.program_title as program_title,t3.program_target as program_target,t2.program_level_flex_id as program_level_flex_id,t2.program_for as program_for,t2.program_schedule as program_schedule,t2.schedule_type as schedule_type FROM programs_subscribed as t1,program_master as t2,program_detail as t3 WHERE t1.program_id=t2.program_id  and t2.program_id=t3.program_master_id  and t1.user_id=".addslashes($userid)." AND t1.program_type='single' AND t1.subscribe_status='1' AND t3.language_id={$lanId} GROUP BY t1.program_id ORDER BY programs_subscribed_id desc limit $start,$len";

		

		$row = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);  

		return $row;

		} 

		// get count of all single training programs subscribed by user

		public function _getAllSingleTrainingProgramsCount($userid)

		{

		  $lanId = $this->language;

		  $query 	= 	"SELECT COUNT(DISTINCT(t1.program_id)) as cnt FROM programs_subscribed as t1,program_master as t2,program_detail as t3 WHERE t1.program_id=t2.program_id  and t2.program_id=t3.program_master_id  and t1.user_id=".addslashes($userid)." AND t1.program_type='single' AND t1.subscribe_status='1' AND t3.language_id={$lanId} GROUP BY t1.program_id ORDER BY programs_subscribed_id desc ";

		

		$row = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);  

		return $row['cnt'];

		} 

		/* unsubscribe from a training program.set status to 0 */

		public function _unsubscribeTraining($subscribeId,$userid)

		{

		  $lanId = $this->language;

		  $query 	= 	"UPDATE programs_subscribed SET subscribe_status='0' where programs_subscribed_id={$subscribeId} AND user_id={$userid}";

		$res = $GLOBALS['db']->query($query);  

		} 

		

		/* unsubscribe from a training program.set status to 0 and completed status=c*/

		public function _unsubscribeTrainingConfirm($subscribeId,$userid)

		{

		  $lanId = $this->language;

		  $query 	= 	"UPDATE programs_subscribed SET complete_status='c' where programs_subscribed_id={$subscribeId} AND user_id={$userid}";

		$res = $GLOBALS['db']->query($query);  

		} 

			

		//for displaying the search wizard result

		

	public function _getAllSearchPgm($userGoal,$userLevel,$user_sports,$userDuration,$noSession,$lanId){

			
           if($lanId==1){$statusfield="english_status";}else{$statusfield="status";}
			/*$querySearch		=	"SELECT program_master_id, program_detail.flex_id FROM program_master, program_detail " 

			." WHERE program_master.program_status = '4' AND program_master_id = program_id AND program_detail.flex_id = program_master.flex_id AND program_detail.language_id =".$lanId;*/

			$querySearch		=	"SELECT program_master_id, program_detail.flex_id FROM program_master, program_detail,sub_category " 

			." WHERE program_master_id = program_id AND program_detail.flex_id = program_master.flex_id AND ( FIND_IN_SET(sub_category.flex_id, program_master.`program_category_flex_id` ) AND sub_category.".$statusfield."=1   AND program_detail.language_id =".$lanId;

			

			if($userGoal != ''){

				

				$querySearch	.=	" AND program_detail.program_title LIKE '%".$userGoal."%' ";

				}



			if($userLevel != ''){

				

				//findout the level flex id 

				$tableName	=	"levels";

				$levelName	=	$userLevel;

				

				$user_level_flex_id	=	$this->_getGeneralFlexId($tableName,$levelName,$lanId);

				$querySearch	.=	" AND program_master.program_level_flex_id = '".$user_level_flex_id."' ";

				

				}



			if($user_sports != ''){

				

				$querySearch	.=	" AND program_master.program_sport_flex_id LIKE '%".$user_sports."%' ";

				}

					

			if($userDuration != ''){

				///user schedule and user schedule type

				$durationArray			=	explode(' ',$userDuration );

				$durationSchedule		=	$durationArray[0];

				$durationScheduleType	=	$durationArray[1];

				

				$querySearch	.=	" AND program_master.program_schedule = ".$durationSchedule

				." AND program_master.schedule_type = '".$durationScheduleType."'";

				}

			

			if($noSession != ''){

				$useressionArray		=	explode(' ',$noSession);

				$noSession				=	$useressionArray[0];

				$querySearch	.=	" AND program_master.program_rythm = '".$noSession."'";

			}

			//print $querySearch;die;

			$result = $GLOBALS['db']->getAll($querySearch, DB_FETCHMODE_ASSOC);

			return $result;

			

		}



//for displaying the search category result

		

	public function _getAllPgmByCate($catFlexId,$lanId,$bid='',$goal='',$sport=''){

		if($goal!=''){
			$goal_qry= "SELECT * FROM goal_program WHERE flex_id ='".$goal."'" ;
			$res_goal =  $GLOBALS['db']->getAll($goal_qry, DB_FETCHMODE_ASSOC); 
			foreach($res_goal as $goal_det){
				if($goal_det['program_id']!=""){
					$goals .= "'".$goal_det['program_id']."',";
				}
			}
			 if($goals!=""){
				 $fgoals=substr_replace($goals ,"",-1);
				 $Goal_Sql="AND  program_master.flex_id  IN ($fgoals)";
			}
			else{
				$Goal_Sql='';
			}
		}
		else{
			$Goal_Sql='';
		}
		if($sport!=''){
		
			$sport_sql="SELECT `flex_id` FROM `program_master` WHERE ( `program_sport_flex_id` LIKE '%".$sport.",%' OR `program_sport_flex_id` LIKE '%,".$sport.",%' OR `program_sport_flex_id` LIKE '%,".$sport."%' ) OR program_sport_flex_id = '".$sport."'" ;
			$res_sport =  $GLOBALS['db']->getAll($sport_sql, DB_FETCHMODE_ASSOC); 
			foreach($res_sport as $sport_det){
				if($sport_det['flex_id']!=""){
					$sports .= "'".$sport_det['flex_id']."',";
				}	
			}
			if($sports!=""){
				 $fsports=substr_replace($sports ,"",-1);
				 $Sport_Sql="AND  program_master.flex_id  IN ($fsports)";
			}
			else{
				$Sport_Sql='';
			}
		}
		else{
			$Sport_Sql='';
		}

			 /*$querySearch		=	"SELECT program_master_id, program_detail.flex_id FROM program_master, program_detail " 

			." WHERE program_master.program_status = '4' AND program_master_id = program_id  AND program_detail.flex_id = program_master.flex_id " 

			." AND program_detail.language_id =".$lanId." AND program_category_flex_id ='".$catFlexId."'";*/
	if($bid){
		$query="SELECT program_id FROM brand_programs WHERE brand_master_id ='$bid'";
	    $res = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);
	    $bidlist=$res['program_id'];
		$DefaultCondition=" AND program_master_id IN ($bidlist)"; 
	}
	else{
		$DefaultCondition ='';
	}
	
			$cat_list = explode(",",$catFlexId);
			for($i=0;$i<count($cat_list);$i++)
			{
				$category_array	=	"(program_category_flex_id like '".$cat_list[$i].",%' or program_category_flex_id like '%,".$cat_list[$i].",%' or program_category_flex_id like '%,".$cat_list[$i]."' or program_category_flex_id like '".$cat_list[$i]."')";
				$category_array .=" or";
			}
			$category_array = rtrim($category_array," or");
			
			$querySearch		=	"SELECT program_master_id, program_detail.flex_id FROM program_master, program_detail " 

			." WHERE program_master_id = program_id  AND program_detail.flex_id = program_master.flex_id " 

			."AND program_status=4 AND program_detail.language_id =".$lanId." AND ".$category_array.$DefaultCondition .$Sport_Sql. $Goal_Sql ;

			//print $querySearch;

			$result = $GLOBALS['db']->getAll($querySearch, DB_FETCHMODE_ASSOC);

			return $result;

			

		}

	

	

		//for searched trainign pgm - for search result listing 

		public function _getSearchPgm($pgmMasterIds,$lanId,$field,$type,$start,$len){

		

		$limit=" LIMIT ".$start.", ".$len;

		

			$query = "SELECT program_title, program_target, program_master_id, program_master.program_image as programImage"

			." FROM program_detail,program_master WHERE " 

			." program_master_id = program_id AND program_master_id IN(".$pgmMasterIds.")"

			." AND language_id = {$lanId} ORDER BY {$field} {$type} $limit";

			$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);

			return $result;

		}

		

		//for searched trainign pgm - for search result listing 

		public function _getOnePgm($pgmMasterId,$lanId){

		

			$query = "SELECT program_title, program_target, program_master_id, program_master.program_image as programImage,program_master.program_status as program_status,program_master.program_level_flex_id as program_level_flex_id,program_master.program_for as program_for,program_master.program_schedule as program_schedule,program_master.schedule_type as schedule_type,program_master.program_rythm as program_rythm"

			." FROM program_detail,program_master WHERE " 

			." program_master_id = program_id AND program_master_id = '".$pgmMasterId."' AND language_id = {$lanId} ";

			

			$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

			return $result;

		}

	

		//for searched trainign pgm count - for search result listing 

		public function _getSearchPgmCnt($pgmMasterIds,$lanId){

		

		$query = "SELECT COUNT(program_master_id) AS cnt FROM program_detail,program_master WHERE " 

			."program_master_id = program_id AND program_master_id IN(".$pgmMasterIds.") AND language_id = {$lanId} ";

			$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

			return $result;

		}

	

	

		// for display training program by keyword search

		public function _getAllPgmByKey($key,$lanId,$bid='',$goal,$sport){
        if($lanId==1){$statusfield="english_status";}else{$statusfield="status";}
		if($goal!=''){
			$goal_qry= "SELECT * FROM goal_program WHERE flex_id ='".$goal."'" ;
			$res_goal =  $GLOBALS['db']->getAll($goal_qry, DB_FETCHMODE_ASSOC); 
			foreach($res_goal as $goal_det){
				if($goal_det['program_id']!=""){
					$goals .= "'".$goal_det['program_id']."',";
				}
			}
			 if($goals!=""){
				 $fgoals=substr_replace($goals ,"",-1);
				 $Goal_Sql="AND  program_master.flex_id  IN ($fgoals)";
			}
			else{
				$Goal_Sql='';
			}
		}
		else{
			$Goal_Sql='';
		}
		if($sport!=''){
		
			$sport_sql="SELECT program_master.flex_id FROM  program_master, sub_category  WHERE ( program_master.program_sport_flex_id LIKE '%".$sport.",%' OR  program_master.program_sport_flex_id  LIKE '%,".$sport.",%' OR program_master.program_sport_flex_id  LIKE '%,".$sport."%' ) OR program_master.program_sport_flex_id = '".$sport."'  AND  ( FIND_IN_SET( sub_category.flex_id, program_master.program_category_flex_id ) ) AND  sub_category.".$statusfield."=1  " ;
			$res_sport =  $GLOBALS['db']->getAll($sport_sql, DB_FETCHMODE_ASSOC); 
			foreach($res_sport as $sport_det){
				if($sport_det['flex_id']!=""){
					$sports .= "'".$sport_det['flex_id']."',";
				}	
			}
			if($sports!=""){
				 $fsports=substr_replace($sports ,"",-1);
				 $Sport_Sql="AND  program_master.flex_id  IN ($fsports)";
			}
			else{
				$Sport_Sql='';
			}
		}
		else{
			$Sport_Sql='';
		}

			/*$DefaultCondition	=	" SELECT DISTINCT ( program_master_id ) FROM program_detail, program_master, general"

			." where program_status = '4' AND program_detail.flex_id = program_master.flex_id AND program_detail.program_master_id = program_master.program_id AND general.language_id = program_detail.language_id AND program_detail.language_id = {$lanId}";*/		

			$DefaultCondition	=	" SELECT DISTINCT ( program_master_id ) FROM program_detail, program_master,sub_category,general"

			." where program_detail.flex_id = program_master.flex_id AND program_detail.program_master_id = program_master.program_id AND general.language_id = program_detail.language_id AND ( FIND_IN_SET( sub_category.flex_id, program_master.program_category_flex_id ) ) AND sub_category.".$statusfield."=1 AND program_detail.language_id = {$lanId}";

			if($bid)
			{
				$query="SELECT program_id FROM brand_programs WHERE brand_master_id ='$bid'";
	            $res = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);
	            $bidlist=$res['program_id'];
				$DefaultCondition.=" AND program_master_id
IN ($bidlist)"; 
			}
			#__________________________________________________________________________#

			//$english_search_key 	= preg_replace($allpattern, "%", $key);

			$search_keyword = $key ;

			#___________________________________________________________________________#

			

			if($search_keyword != ''){	

				

				$keywordsArray 	= 	split(' ',$search_keyword);	

				$resultArray	=	array_unique($keywordsArray);

				if(count($resultArray) > 0){

					$querySearch='';

					$querySearch	.= " AND ( ";

					$querySearch	.= "  ( program_title LIKE '%".$resultArray[0]."%' ";

						for($i=1;$i<count($resultArray);$i++){

							$querySearch	.= " OR program_title LIKE '%".$resultArray[$i]."%' ";

						}

						$querySearch	.= " ) ";

					

					$querySearch	.= " OR ( program_desc LIKE '%".$resultArray[0]."%' ";

						for($i=1;$i<count($resultArray);$i++){

							$querySearch	.= " OR program_desc LIKE '%".$resultArray[$i]."%' ";

						}

						$querySearch	.= " ) ";

						

					$querySearch	.= " OR ( program_target LIKE '%".$resultArray[0]."%' ";

						for($i=1;$i<count($resultArray);$i++){

							$querySearch	.= " OR program_target LIKE '%".$resultArray[$i]."%' ";

						}

						$querySearch	.= " ) ";

						

					$querySearch	.= " OR ( program_provide LIKE '%".$resultArray[0]."%' ";

						for($i=1;$i<count($resultArray);$i++){

							$querySearch	.= " OR program_provide LIKE '%".$resultArray[$i]."%' ";

						}

						$querySearch	.= " ) ";

						

					$querySearch	.= " OR ( schedule_type LIKE '%".$resultArray[0]."%' ";

						for($i=1;$i<count($resultArray);$i++){

							$querySearch	.= " OR schedule_type LIKE '%".$resultArray[$i]."%' ";

						}

						$querySearch	.= " ) ";

						

					$querySearch	.= " OR ( program_rythm LIKE '%".$resultArray[0]."%' ";

						for($i=1;$i<count($resultArray);$i++){

							$querySearch	.= " OR program_rythm LIKE '%".$resultArray[$i]."%' ";

						}

						$querySearch	.= " ) ";

						

					$querySearch	.= " OR ( program_schedule LIKE '%".$resultArray[0]."%' ";

						for($i=1;$i<count($resultArray);$i++){

							$querySearch	.= " OR program_schedule LIKE '%".$resultArray[$i]."%' ";

						}

						$querySearch	.= " ) ";			

						

					$querySearch	.= " OR ( ( FIND_IN_SET( general.flex_id,program_master.program_category_flex_id ) )" 

					." AND general.table_name = 'category' AND ( general.item_name LIKE '%".$resultArray[0]."%' ";

						for($i=1;$i<count($resultArray);$i++){

							$querySearch	.= " OR general.item_name LIKE '%".$resultArray[$i]."%' ";

						}

						$querySearch	.= " ) )";

						

					$querySearch	.= " OR ( general.flex_id IN( program_master.program_sport_flex_id )" 

					." AND general.table_name = 'sports' AND ( general.item_name LIKE '%".$resultArray[0]."%' ";

						for($i=1;$i<count($resultArray);$i++){

							$querySearch	.= " OR general.item_name LIKE '%".$resultArray[$i]."%' ";

						}

						$querySearch	.= " ) )";

					$querySearch	.= " OR ( general.flex_id = program_master.program_level_flex_id " 

					." AND general.table_name = 'levels' AND (general.item_name LIKE '%".$resultArray[0]."%' ";

						for($i=1;$i<count($resultArray);$i++){

							$querySearch	.= " OR general.item_name LIKE '%".$resultArray[$i]."%' ";

						}

						$querySearch	.= " ) )";			

						

					$querySearch	.= " ) ";		

					}

				}
              
			//print $querySearch;

			if($querySearch !='')

			

				$query	=	$DefaultCondition.$querySearch.$Goal_Sql.$Sport_Sql;

				else

				$query	=	$DefaultCondition.$Goal_Sql.$Sport_Sql;

			//print $query;

			//die;

			$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);

			return $result;

			

		}

		

		// for getting levels flex 

		

		public function _getGeneralFlexId($tableName,$levelName,$lanId){

		$query	=	"SELECT flex_id  FROM general WHERE table_name='".$tableName."' AND item_name='".$levelName."' AND language_id=".$lanId;

		

		$result = $GLOBALS['db']->getRow($query,DB_FETCHMODE_ASSOC);

		$flexId = $result['flex_id'];

		return $flexId;

		}

		// function to resize image

		public function _imageResize($imgFile,$uploadPath,$x_width,$y_hei)

		{		

	        $params = array(); 

			if($imgFile != null)

			{	

				$ext = end(explode(".",$imgFile));

							

				$path=$uploadPath.$imgFile;

				list($width,$height) = getimagesize($path);//to get the size of the upladed image 

				//############################ to find the resizing percent ##########################

				if($width<$x_width && $height<$y_hei)

			      {

			        $percent=1;

			      }

                  else

			      {

			           if($width<$x_width && $height>$y_hei)

			             {

				             $percent=$y_hei/$height;

				         }

			           else

			             {

						  if($width>$x_width && $height<$y_hei)

			                 {

				              $percent=$x_width/$width;

				             }

			             else{

				     if($width<$height)

					 {

					  $a=$height;

					  $percent=$y_hei/$height;

					  }

					   else{

					         $a=$width;

							  $percent=$x_width/$width;

							}

							

				   			

				 }

			   }

			  } 

			  			  

			  	//####################################################################################

				  //following should be the size of the resizing image

		          $newwidth = $width * $percent;

                  $newheight = $height * $percent;

				  $params[0] = trim($newwidth);

				  $params[1] = trim($newheight);

				  return $params;

			

		}	

	}

	

	// get latest 5 forum posts

	

	public function _getForumPosts()

	{

	 	//$query = "select post_subject, post_text, forum_id, topic_id from forum_posts where post_approved = 1 order by post_id desc limit 0,10";
		$query = "select fp. post_subject, fp.post_text, fp.forum_id, fp.topic_id from forum_posts fp,forum_forums ff  where ff.forum_id = fp.forum_id and ff.brand_id = 0 and fp.post_approved = 1 order by fp.post_id desc limit 0,10";

		$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);

		return $result;

	

	}

	// for changing the schedule type 

	public function _getChangedArrayStruct($scheduleType)

	{	

		$chgdArray	= array();

	  	for($i=0;$i<count($scheduleType);$i++){

		$chgdArray[$scheduleType[$i]['flex_id']]=$scheduleType[$i]['item_name'];

		

		}

	return $chgdArray;

	}

	public function _getSiteLanguage($lanId)

	{

	  	$query = "select language_name from languages where language_id={$lanId}";

		$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

		return $result['language_name'];

	

	}

	// get campaign id from PAP using banner id

	public function _getCampaignId($bannerid)

	{

	  	$query = "select campaignid from qu_pap_banners where bannerid='".addslashes(trim($bannerid))."'";

		$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

		return trim($result['campaignid']);

	

	}

	// set affiliate id and banner id to null after sales tracking

	public function _updateAffiliate($userid)

		{

		    $userid = trim(addslashes($userid));

			$query = "UPDATE user_master SET aff_refid='',aff_bannerid='' WHERE user_id ={$userid}"; 


			$res = $GLOBALS['db']->query($query); 

			return $res;

		}

		// get post  affiliate pro user id

	public function _getUserIdPap($refid)

		{

	  	$query = "select userid from qu_pap_users where refid='".addslashes(trim($refid))."'";

		$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

		return stripslashes(trim($result['userid']));

	

		}

		// get referel id for discount code

		public function _getDiscountAffiliateRefferalId($discountcode)

		{

	  	$query = "select refid from affiliate_discountcode where discount_code='".addslashes(trim($discountcode))."'";

		$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

		return stripslashes(trim($result['refid']));

	

		}

		// insert click values to PAP database table

		public function _insertPapValues($insArray)

		{

		  $query = "select MAX(clickid) as max from qu_pap_rawclicks";

		 $result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC); 

		 $clickid = trim($result['max'])+1;

		 $sql = "INSERT INTO qu_pap_rawclicks VALUES('$clickid','".$insArray['userid']."','".$insArray['bannerid']."','".$insArray['campaignid']."','','','".$insArray['rtype']."','".$insArray['datetime']."','".$insArray['refererurl']."','".$insArray['ip']."','','','','','P')";

		 $res = $GLOBALS['db']->query($sql); 

		 }

		 

		 public function getCategories($lanId, $parent_id=0,$bid='')

		 {   
		     if($lanId==1){$statusfield="english_status";}else{$statusfield="status";}
		 	 $checkstatus=" AND sc.".$statusfield."=1";
			 if($bid!=''){
			 	  $query="SELECT * FROM brand_programs WHERE brand_master_id ='$bid'";
				  $res = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);
				  $bidlist=$res['program_id'];
				  $catbidlist=$res['cat_id'];
				   $listquery="SELECT DISTINCT sc.flex_id FROM `program_master` pm INNER JOIN sub_category sc ON ( FIND_IN_SET( sc.flex_id, pm.`program_category_flex_id` )) AND sc.parent_id != '0' AND sc.language_id = '2' AND pm.program_id IN ($bidlist)";
		    	  if($res['subcat_id'])
				  {
				     $listquery.=" and sc.flex_id IN ($res[subcat_id])"; 
				  }
				  $result = $GLOBALS['db']->getAll($listquery, DB_FETCHMODE_ASSOC);
				  //print_r($result);
				  $b_qry= "AND flex_id IN (".$listquery.")";
				  $b_cat_qry="AND sc.flex_id IN (".$listquery.")";
				  $b_cat_qry1="AND sc1.flex_id IN (".$listquery.")";
				  $checkstatus="";
			  }
			  if($parent_id==0)
			  {
			  	 $query="SELECT DISTINCT sc . *
FROM sub_category sc
CROSS JOIN program_master pm ON ((FIND_IN_SET(pm.`program_category_flex_id`, sc.flex_id ))
AND sc.parent_id =0
AND sc.language_id ='$lanId'".$checkstatus." ".$b_cat_qry." )
UNION SELECT DISTINCT sc. *
FROM sub_category sc
WHERE sc.flex_id
IN (
SELECT DISTINCT sc1.parent_id
FROM `program_master` pm
INNER JOIN sub_category sc1 ON ((FIND_IN_SET( sc1.flex_id, pm.`program_category_flex_id` ))
AND sc1.parent_id != '0' )
) ".$b_cat_qry."
AND sc.parent_id = '0'".$checkstatus."  
 AND sc.language_id = '$lanId' ";
             $result =$GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);
			 //echo "test";
			  }
			  else
			  {
			 /* $query =	"SELECT * FROM `sub_category` 
						WHERE `parent_id` = ?  AND status=1
						AND `language_id` = ? ".$b_qry."
						ORDER BY `category_name`";
						$result =&$GLOBALS['db']->getAll($query, array($parent_id, $lanId), DB_FETCHMODE_ASSOC); */
				 $query ="SELECT distinct sc.* 
FROM `program_master` pm
INNER JOIN sub_category sc ON ( FIND_IN_SET( sc.flex_id, pm.`program_category_flex_id` ) )
AND sc.parent_id = '$parent_id'
AND sc.language_id = '$lanId' ".$checkstatus." ".$b_cat_qry." ORDER BY `category_name`";
//echo $query;
               $result =$GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);
			   }
				//echo "test123";
				return $result;
		 }

		 

		 public function getRowFromSubCategory($lanId, $category_name, $field='flex_id',$bid='',$goal='',$sport='',$flxid='')
		 { 
			if($goal!=''){
				$goal_qry= "SELECT DISTINCT ( program_master.program_category_flex_id ) FROM goal_program, program_master WHERE goal_program.flex_id = '".$goal."' AND program_master.flex_id = goal_program.program_id" ;
				$res_goal =  $GLOBALS['db']->getAll($goal_qry, DB_FETCHMODE_ASSOC); 
				foreach($res_goal as $goal_det){
					if($goal_det['program_category_flex_id']!=""){
						$goals .= "'".$goal_det['program_category_flex_id']."',";
					}
				}
				 if($goals!=""){
					 $fgoals=substr_replace($goals ,"",-1);
					 $Goal_Sql="AND  flex_id  IN ($fgoals)";
				}
				else{
					$Goal_Sql='';
				}
			}
			else{
				$Goal_Sql='';
			}
	
		 
			if($sport!=''){
				 $sport_sql="SELECT * FROM `program_master` WHERE ( `program_sport_flex_id` LIKE '%".$sport.",%' OR `program_sport_flex_id` LIKE '%,".$sport.",%' OR `program_sport_flex_id` LIKE '%,".$sport."%' ) OR 'program_sport_flex_id' = '".$sport."'" ;
				$res_sport =  $GLOBALS['db']->getAll($sport_sql, DB_FETCHMODE_ASSOC); 
				foreach($res_sport as $sport_det){
					if($sport_det['program_category_flex_id']!=""){
						$sports .= "'".$sport_det['program_category_flex_id']."',";
					}	
				}
				if($sports!=""){
					 $fsports=substr_replace($sports ,"",-1);
					 $Sport_Sql="AND  flex_id  IN ($fsports)";
				}
				else{
					$Sport_Sql='';
				}
			}	
			else{
				$Sport_Sql='';
			}
			if($flxid)
			{
				$condc	=	"  AND flex_id=".$flxid."";
			}
			else
			{	
				$condc	=	"";
			}
		 	 $query =	"SELECT * FROM `sub_category` WHERE `category_name` = ? AND `language_id` = ? {$Sport_Sql} {$Goal_Sql} ".$condc; 

			  $result =& $GLOBALS['db']->getRow($query, array($category_name,$lanId), DB_FETCHMODE_ASSOC);

			  return $result;

		 }
		 
		 public function getRowFromSubCategoryPage($flexid, $lanId)

		 {
            
		 	$query =	"SELECT * FROM `sub_category_pagedetails` WHERE `flex_id`= ? and `language_id`= ?";

			$result =& $GLOBALS['db']->getRow($query, array($flexid,$lanId), DB_FETCHMODE_ASSOC);

			return $result;
		 }
		 

		 public function _getCommissionType($campaignId)

		{

	  	$query = "select commtypeid from qu_pap_commissiontypes where campaignid='".addslashes(trim($campaignId))."' and rtype='S'";

		$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

		return stripslashes(trim($result['commtypeid']));

		}

		public function _getCommissionGroupId($campaignId)

		{

	  	$query = "select commissiongroupid from qu_pap_commissiongroups where campaignid='".addslashes(trim($campaignId))."'";

		$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

		return stripslashes(trim($result['commissiongroupid']));

	

		}

		public function _getCommissionDetail($commissionGroupId,$commissionTypeId)

		{

	  	$query = "select * from qu_pap_commissions where commissiongroupid='".addslashes(trim($commissionGroupId))."' and commtypeid='".addslashes(trim($commissionTypeId))."'";

		$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

		return $result;

		//return stripslashes(trim($result['commissiongroupid']));

	

		}

		

		public function _generateTransId($length=8,$level=2)

		{

   			list($usec, $sec) = explode(' ', microtime());

   			srand((float) $sec + ((float) $usec * 100000));



   			$validchars[2] = "0123456789abcdfghjkmnpqrstvwxyz";

   

   			$code  = "";

   			$counter   = 0;



  			while ($counter < $length) {

     		$actChar = substr($validchars[$level], rand(0, strlen($validchars[$level])-1), 1);



     		// All character must be different

     		if (!strstr($password, $actChar)) {

        		$code .= $actChar;

        		$counter++;

     		}

   		}



   		return $code;



		}

		// calculate PAP commission

		 public function _calculatePapCommissionPro($aff_id,$banner_id,$amount)

		 {

		   $papuserid = $this->_getUserIdPap(trim($aff_id));

		   $campaignId = $this->_getCampaignId($banner_id);

		   $commissionTypeId = $this->_getCommissionType($campaignId);

		   $commissionGroupId = $this->_getCommissionGroupId($campaignId);

		   $commissionDetail =  $this->_getCommissionDetail($commissionGroupId,$commissionTypeId);

		   $commission_type = stripslashes(trim($commissionDetail['commissiontype']));

		   $commission_value =stripslashes(trim($commissionDetail['commissionvalue'])); 

		   $transcode = trim($this->_generateTransId(8,2));

		   if($commission_type=='$')

		      $commissionAmount = $commission_value;

		 else

		 	{

			  $commissionAmount = round((($amount)*($commission_value/100)),2); 

			}	

			$elemts['transid']		=	trim($transcode);

		    $elemts['userid']		=	trim($papuserid);

			$elemts['bannerid']		=	trim($banner_id);

			$elemts['campaignid']	=	trim($campaignId);

			$elemts['rstatus']		=	'A';

			$elemts['rtype']		=	'S';

			$elemts['dateinserted']	=	date('Y-m-d H:i:s');

			$elemts['dateapproved']	=	date('Y-m-d H:i:s');

			$elemts['payoutstatus']	=	'U';

			$elemts['refererurl']	=	"http://www.jiwok.com";

			$elemts['ip']			=	$_SERVER['REMOTE_ADDR'];

			$elemts['browser']		=	'1ff6b3';

			$elemts['commission']	=	trim($commissionAmount);

			$elemts['clickcount']	=	'1';

			$elemts['trackmethod']	=	'R';

			$elemts['totalcost']	=	trim($amount);

			$elemts['tier']			=	'1';

			$elemts['commtypeid']	=	trim($commissionTypeId);	

			$this->_insertRecord("qu_pap_transactions",$elemts); 

		 }

		 

		 public function getCategoryListingUrlOnLangChange($categoryName, $langChange){

		 /* find url to redirect if language is changed while listing a category or subcategory */

		 	if($langChange =='1'){$search_link="training";}else{$search_link="entrainement";}
			$url		= ROOT_FOLDER.$search_link.'/';

			$query		=	"SELECT flex_id FROM `sub_category` WHERE `category_name` = ? ";

			$flex_id	=& $GLOBALS['db']->getOne($query, $categoryName);

			if(PEAR::isError($flex_id)){

				return $url;

			}

			$query		=	"SELECT * FROM `sub_category` WHERE `flex_id` = ? AND `language_id` = ?";		

			$category_arr	=& $GLOBALS['db']->getRow($query, array($flex_id, $langChange), DB_FETCHMODE_ASSOC);

			if(sizeof($category_arr)==0){

				return $url;

			} else {

				if($category_arr['url']!=''){

					$category_arr['url']	= urlencode($category_arr['url']);

			// If '/' is the last character, it should not be urlencoded. If so search and replace it back to '/'.

					$reg_exp	= '/^(.+)%2F$/';

					$category_arr['url']	= preg_replace($reg_exp, '${1}/', $category_arr['url']);

					$url	= ROOT_FOLDER.$category_arr['url'];

				} else {

					$category_arr['category_name']	= urlencode($category_arr['category_name']);

					$category_arr['category_name']	= str_replace('+%2F+', '+%252F+', $category_arr['category_name']);

					$url	= ROOT_FOLDER.$search_link.'/'.$category_arr['category_name'];

				}

			}



			return $url;

		 }

		 

		 

		 public function _getUserPaymentTemp($userId){

		

			$query = "SELECT * FROM user_payment_temp WHERE user_id ={$userId} order by pay_date desc"; 

			$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

			return $result;

		}

		public function _getUserTagOfflineLogin($userId){

			$bool = true;


			$query = "SELECT user_tag_login_status from user_master WHERE user_id = {$userId}";

			$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

			$login = stripslashes($result['user_tag_login_status']);

			if($login==1)

			  $bool = false;

			 return $bool; 

		}

		

	public function getProgramIdFromTitle($program_title) {

			$query = "SELECT `program_master_id` FROM `program_detail` WHERE `program_title` = ? "; 

			$result = $GLOBALS['db']->getOne($query, $program_title);



			return $result;

		}
		
		public function getProgramIdFromTitle_lan($program_title,$program_id) {

			 $query = 'SELECT `program_master_id` FROM `program_detail` WHERE `program_title` like "%'.$program_title.'%" and program_master_id='.$program_id;

			$result = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

			$result_id = stripslashes($result['program_master_id']);

			return $result_id;

		}

		

		public function makeProgramTitleUrl($program_title) {
		
		

			// change program_title to a form suitable in URL;  first replace spaces with '-' ; then urldecode
			$program_title	= str_replace('-', '_', $program_title);
			$program_title	= str_replace("'", '-', $program_title);
			
			$program_title	= str_replace(',', '-', $program_title);

			$program_title	= str_replace(' ', '-', $program_title);
			
			

			//$program_title_url	= urlencode($program_title);

			

			return $program_title;

		}
		
		public function normal_url($string)
		{
			$accented = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'A', 'A','Ç', 'C', 'C', 'Œ','D', 'Ð','à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'a', 'a','ç', 'c', 'c', 'œ','d', 'd','È', 'É', 'Ê', 'Ë', 'E', 'E','G',
	'Ì', 'Í', 'Î', 'Ï', 'I','L', 'L', 'L','è', 'é', 'ê', 'ë', 'e', 'e','g','ì', 'í', 'î', 'ï', 'i','l', 'l', 'l','Ñ', 'N', 'N','Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'O','R', 'R','S', 'S', 'Š','ñ', 'n', 'n','ò', 'ó', 'ô', 'ö', 'ø', 'o','r', 'r','s', 's', 'š','T', 'T','Ù', 'Ú', 'Û', 'U', 'Ü', 'U', 'U','Ý', 'ß','Z', 'Z', 'Ž','t', 't','ù', 'ú', 'û', 'u', 'ü', 'u', 'u','ý', 'ÿ','z', 'z', 'ž','?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?','?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?','?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?','?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?');
	
			$replace   = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'A', 'A','C', 'C', 'C', 'CE','D', 'D','a', 'a', 'a', 'a', 'a', 'a', 'ae', 'a', 'a','c', 'c', 'c', 'ce','d', 'd','E', 'E', 'E', 'E', 'E', 'E','G','I', 'I', 'I', 'I', 'I','L', 'L', 'L','e', 'e', 'e', 'e', 'e', 'e','g','i', 'i', 'i', 'i', 'i','l', 'l', 'l','N', 'N', 'N','O', 'O', 'O', 'O', 'O', 'O', 'O','R', 'R','S', 'S', 'S','n', 'n', 'n','o', 'o', 'o', 'o', 'o', 'o','r','r','s', 's', 's','T', 'T','U', 'U', 'U', 'U', 'U', 'U', 'U','Y', 'Y','Z', 'Z', 'Z','t', 't', 'u', 'u', 'u', 'u', 'u', 'u', 'u','y', 'y','z', 'z', 'z', 'A', 'B', 'B', 'r', 'A', 'E', 'E', 'X', '3', 'N', 'N', 'K', 'N', 'M', 'H', 'O', 'N', 'P', 'a', 'b', 'b', 'r', 'a', 'e', 'e', 'x', '3', 'n', 'n', 'k', 'n', 'm', 'h', 'o', 'p', 'C', 'T', 'Y', 'O', 'X', 'U', 'u', 'W', 'W', 'b', 'b', 'b', 'E', 'O', 'R', 'c', 't', 'y', 'o', 'x', 'u', 'u', 'w', 'w', 'b', 'b', 'b', 'e', 'o', 'r');
	
			$new_string=str_replace($accented, $replace, iconv("UTF-8", "CP1252", $string));		
			return $new_string;
		}


public function _getProgramStatus(&$pgmid) {

			$sql = "SELECT  program_status FROM program_master WHERE program_id = {$pgmid}";

			$res = $GLOBALS['db']->getRow($sql,DB_FETCHMODE_ASSOC);

			return trim(stripslashes($res['program_status']));

			

		}

		

		public function getProgramTitleFromUrl($program_title_url) {

			// change to correct program_title;  first urldecode;  then replace '-' with spaces

			$program_title_url	= urldecode(trim($program_title_url));

			$program_title	= str_replace('-', ' ', $program_title_url);
			$program_title	= str_replace('_', '-', $program_title);

			if (get_magic_quotes_gpc()) {

				$program_title	= stripslashes($program_title);

			}
			return $program_title;
		}
		
		public function makeCategoryTitle($cat_title) {

			// change program_title to a form suitable in URL;  first replace spaces with '-' ; then urldecode
		   $cat_title	= str_replace('-', '_', $cat_title);
			
			$cat_title	= str_replace("'", '-', $cat_title);
			
			return $cat_title	= str_replace(',', '-', $cat_title);

		}
		
		public function findCatName($flex_id,$lanId) {
		
		$query = "SELECT `category_name` FROM `sub_category` WHERE `flex_id` ='".$flex_id."' and language_id=".$lanId; 

		$result = $GLOBALS['db']->getOne($query, $program_title);

		return $result;
		
		}

		

		public function getSearchWizardListing( $lanId){

			

		}

		function checkWorkoutGenerated($userid){
		
			$bool	= true;
			$query = "SELECT * FROM program_queue WHERE user_id ='".$userid."'"; 

			$r = $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);
			
			$count = count($r);
			if($count>0):
				$bool = false;
			endif;
			
			return $bool;
		}
		
		function checkUserPaymentStaus($userid){
		
			$query 	= 	"SELECT * FROM payment WHERE payment_userid=".addslashes($userid)." AND payment_status = '1'";

			$r = $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);
			$count = count($r);		
		
			return $count;
			
		}
		
		function checkProgramSubscribed($userid){
		
			$query 	= 	"SELECT * FROM programs_subscribed WHERE subscribe_status = 1 and user_id = ".$userid;

			$r = $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);
			$count = count($r);	
			
			if($count == 0):
				$bool = true;
			else:
				$bool = false;
			endif;	
		
			return $bool;
			
		}

	}

	?>
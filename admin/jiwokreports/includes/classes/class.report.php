<?
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 	::> Class for report management
   Programmer	::> Deepa S
   Date		::> 24-Jan-2011
   DESCRIPTION::::>>>>
   This is a Class code used to manage the reports of Jiwok/Brands.
*****************************************************************************/

class Report{

	
	public function __construct(){

		
	}

	
	public function _showPage($userType,$totalRecs,$i = 0,$no_rec = 0,$field,$type,$searchQuery){

			$fromLimit = $no_rec*($i - 1);

			$toLimit = $no_rec;

			if(trim($searchQuery)!=''){

				$query = "SELECT * FROM user_master WHERE user_type=".$userType.$searchQuery." ORDER BY $field $type LIMIT $fromLimit,$toLimit";

			}else{

			$query = "SELECT * FROM user_master WHERE user_type=".$userType." ORDER BY $field $type LIMIT $fromLimit,$toLimit";

			}

			

	} 

	

	public function _getCountries(){

		$sql 	= "SELECT countries_id,countries_name FROM countries";

		$result = mysql_query($sql) or die(mysql_error());

		$countriesArray =array();

		while($name = mysql_fetch_array($result,MYSQL_ASSOC))
		{

			$ccode		= $name['countries_id'];

			$cname		= $name['countries_name'];

			$countriesArray[$ccode] = $cname;
		}

		return $countriesArray;

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

	
	public function getAllBrandName(){
			$sql 	= "SELECT brand_master_id,brand_name FROM brand_master where brand_master_id!=20";

		$result = mysql_query($sql) or die(mysql_error());

		$brandArray =array();

		while($name = mysql_fetch_array($result,MYSQL_ASSOC))
		{

			$ccode		= $name['brand_master_id'];

			$cname		= $name['brand_name'];

			$brandArray[$ccode] = $cname;
		}

		return $brandArray;

		}
		
		
		public function getAllOxyBrandName(){
			$sql 	= "SELECT id,camp_name,camp_value FROM campaign_manage where status=1";

		$result = mysql_query($sql) or die(mysql_error());

		$brandArray =array();
		$i = 0;

		while($name = mysql_fetch_array($result,MYSQL_ASSOC))
		{

			//$ccode		= $name['campaign_name'];

			//$cname		= $name['campaign_name'];

			$brandArray[$i] ['id']= $name['id'];
			$brandArray[$i] ['camp_name']= $name['camp_name'];
			$brandArray[$i] ['camp_value']= $name['camp_value'];
			$i++;
		}

		return $brandArray;

		}
		
		public function getAllOxyCodes(){
			$sql 	= "SELECT distinct 	gift_card_no FROM campaign_reports where status=1";

		$result = mysql_query($sql) or die(mysql_error());

		$brandArray =array();

		while($name = mysql_fetch_array($result,MYSQL_ASSOC))
		{

			//$ccode		= $name['gift_card_no'];

			$cname		= $name['gift_card_no'];

			$brandArray[] = $cname;
		}

		return $brandArray;

		}
		
		
		
		
		
/*========================== new  functions added by Deepa S from jan-24-2011. ==============================================*/	

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

public function getDiscountCodesUsed(){
			$sql 	= "SELECT distinct discount_code FROM discount_users where discount_type='DISC'";

		$result = mysql_query($sql) or die(mysql_error());

		$discArray =array();

		while($name = mysql_fetch_array($result,MYSQL_ASSOC))
		{

			$ccode		= $name['discount_code'];

			$cname		= $name['discount_code'];

			$discArray[$ccode] = $cname;
		}

		return $discArray;

		}


}



?>

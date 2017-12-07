<?


	/**************************************************************************** 

   Project Name	::> Jiwok 

   Module 		::> Training program Management

   Programmer	::> Deepa S

   Date			::> 04/02/2009
   DESCRIPTION::::>>>>

   This is class that can be used to manipulate the training program section 

   *****************************************************************************/

   include_once("class.DbAction.php");

	class Download extends DbAction{

		public $language;

		public $objDb;

		

		public function Download($language=''){

			//setting the language of the training

			$this->language		= $language;

		}
		
		public function getQueueDetails($quid,$lanId){
		
		  echo	$query 	= 	"select * from program_queue where queue_id =".$quid." and end_time !='0000-00-00 00:00:00' ";

			$res = $GLOBALS['db']->getRow($query,DB_FETCHMODE_ASSOC);         
            
			return $res;

		}

/* function to get program_flex_id and workout_flexid */
		public function getQueueDetailsnew($quid,$lanId){
		
		  	$query 	= 	"SELECT pq.program_flex_id,pq.workout_flex_id,pd.program_title,pq.workoutOrderNumber FROM program_queue pq,program_detail pd WHERE pd.flex_id = pq.program_flex_id AND queue_id =".$quid." and end_time !='0000-00-00 00:00:00' ";

			$res = $GLOBALS['db']->getRow($query,DB_FETCHMODE_ASSOC);         
            
			return $res;

		}
        public function updateStatus($quid) {

			$query = "UPDATE program_queue SET status='11', download_time = NOW() WHERE queue_id = ".$quid."";

			$res = $GLOBALS['db']->query($query);

			return $res;

		}

	}

	?>
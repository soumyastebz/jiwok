<?
	/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-CMS Management
   Programmer	::> Sreejith E C
   Date			::> 31/1/2007
   
   DESCRIPTION::::>>>>
   This is class that can be used to manipulate the SERVICE section from the admin side.
   *****************************************************************************/
	class pricePlan{
		public $language;
		
		public function Service($language=''){
			//setting the language of the Service
			$this->language		= $language;
		}
		

function get_dateselect()
			{
					$query="SELECT * FROM month_amount";
					$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);
					return $result;
			}
function get_combo_arr($name,$arr,$valname,$dataname,$selectd="",$params="")
			{
				$str		=	"<select name='$name' $params>";
				if($selectd	==	"")		$str		.=	"<option selected value=''>Select</option>";
				else					$str		.=	"<option value=''>Select</option>";
				if(is_array($arr))
					{
						foreach ($arr as $key	=>	$val)
							{
								$val[$dataname]		=	stripslashes($val[$dataname]);
								
								if($selectd)
									{
										if($selectd	==	$val[$valname])
											$str		.=	"<option selected value='".$val[$valname]."'>".stripslashes($val[$dataname])."</option>";
										else
											$str		.=	"<option value='".$val[$valname]."'>".stripslashes($val[$dataname])."</option>";
									}
								else
									$str		.=	"<option value='".$val[$valname]."'>".$val[$dataname]."</option>";
							}
					}
				$str		.=	"</select>";
				return $str;
			}	



  function selectSubFees()
	{
		$sql="select * from settings";
		$res = $GLOBALS['db']->getRow($sql,DB_FETCHMODE_ASSOC);
        return $res;
	}

		
		
		/*
		Function   			: _getAllById
		Usage	   			: Fetch all slide records with single slide_id. The service_id is passed to the function.
		Variable Passing 	: $id is passed as reference.
		Returns	   			: array
		*/
		public function _getAllById(&$id) {
			$sql = "Select * from priceplan where month_id='".$id."'ORDER BY id ASC";
			
		
			$res = $GLOBALS['db']->getAll($sql, DB_FETCHMODE_ASSOC);
			if(DB::isError($res)) {
				echo $res->getDebugInfo();
			}
			else{
				$data = array();
				if(!empty($res)) {
					foreach($res as $service){
						$data[]	= $this->_setValues($service);
					}
				}
				if($data != "")
					return $data;
				else 
					return false;
			}
		}
		
		
		
		public function _insertPriceplan($month,$currency,$discount,$amount){
		$sql = "INSERT INTO
						priceplan(month_id,
							currency,discount,disamount
						) VALUES (
							?,?,?,?
						)";
			$sth = $GLOBALS['db']->prepare($sql);
			if(DB::isError($sth)) {
				echo $sth->getMessage();
			}
			
			$data = array($month,$currency,$discount,$amount);
			//Insering into service table
				$res = $GLOBALS['db']->execute($sth, $data);
			if(DB::isError($res)) {
				echo $res->getDebugInfo();
			}
			
			return $res;
		
		}
	
		/*
		Function   			: _updateService
		Usage	   			: update an slide to the database. Records are updated to two tables namely slide,slide_manager.
		Variable Passing 	: $id is passed as reference.
		Returns	   			: boolean
		*/
		public function _updatePriceplan($month,$currency,$discount,$amount) {
			$bool = true;
			$this->_setStatus($id,$updateArray['month'],$updateArray['amount']);
			
		    $sql = "UPDATE
						priceplan
					SET
						discount = ?,disamount = ?
					WHERE
						month_id = ? AND currency = ?"; 
			$sth = $GLOBALS['db']->prepare($sql);
			if(DB::isError($sth)) {
				echo $sth->getMessage();
				$bool = false;
			}
			$data 		= array(
							$discount,$amount,$month,$currency
						  );
						 // print_r($data);
			echo $res = $GLOBALS['db']->execute($sth, $data);
			if(DB::isError($res)) {
				echo $res->getDebugInfo();
				$bool = false;
			}
			
			return $bool;
		}
		
		
		
		public function _getPlan(&$id)
		{
			 $query="select * from priceplan where month_id='$id'";
			$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);
			return($result); 
		}
	
		/*
			Function			: _setStatus
			Usage				: To set the status of a Service.
			Variable Passing 	: $id is passed as reference.
			Returns				: Boolean
		*/
		public function _setStatus(&$id,$month,$amount){
			$bool = true;
			 $sql = "UPDATE
							month_amount
						SET
							month = '".$month."',
							amount = '".$amount."'
						WHERE
							id = '".$id."'";

					$res	=	mysql_query($sql);
				//$sth = $GLOBALS['db']->prepare($sql);
			/*	if(DB::isError($sth)) {
					echo $sth->getMessage();
					$bool = false;$sql = "INSERT INTO
					hometext_manager (
						text_content,language_id,hometextmaster_id
					) VALUES (
						?,?,?
					)";
			$sth = $GLOBALS['db']->prepare($sql);
			if(DB::isError($sth)) {
				echo $sth->getMessage();
			}
			$objGen = new General();
			$content	= $objGen->_clean_data($insertArray['text_content']);
			$data 		= array($content,$insertArray['language_id'],$nextId);
			
			$res = $GLOBALS['db']->execute($sth, $data);
			if(DB::isError($res)) {
				echo $res->getDebugInfo();
			}	
					
				}*/
				
			//	$data = array(
					//$status,flid,$id
				//);
				
				//$res = $GLOBALS['db']->execute($sth, $data);
			//	if(DB::isError($res)) {
				//	echo $res->getDebugInfo();
				//	$bool = false;
				//}
			//	return $bool;
		}
	
		/*
			Function			: _deleteService
			Usage				: To delete a slide. This deletes entries from two tables namely, slide,slide_manager.
			Variable Passing 	: $id is passed as reference.
			Returns				: Boolean
		*/
		public function _deleteService(&$id) {
			$bool= true;
			//Delete the value from service_manager.
			$sql = "DELETE FROM month_amount WHERE id = '{$id}'"; 
			$res = $GLOBALS['db']->query($sql);
			if(DB::isError($res)) {
				echo $res->getDebugInfo();
				$bool = false;
			}
			//Delete the value from service.
		/*	$sql = "DELETE FROM hometext WHERE text_id = '{$id}'";
			$res = $GLOBALS['db']->query($sql);
			if(DB::isError($res)) {
				echo $res->getDebugInfo();
				$bool = false;
			}*/
			return $bool;
		}
		

		/*
		Function 			: _setValues

		Usage	   			: setting the result array to a more simple format.
		Variable Passing 	: $res is passed as reference.
		*/
		function _setValues(&$res){
			$val = array(
					"slide_id" 			=> $res['id'],
					"amount"	=> $res['amount']
					//"language_id"		=> $res['language_id'],
					//"text_status"		=> $res['text_status']
				   );
			return $val;
		}
		public function _getTotalCount()
		{
			$query="SELECT count( DISTINCT `month_id` ) as cnt
FROM priceplan";
			$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);

			$totalRecs = $result[0]->cnt;

			return $totalRecs;
		}
		/*
		Function 			: _showPage
		Usage	   			: To list the slides in the admin side with paging.
		Variable Passing 	:
		*/
		public function _showPage($totalRecs,$i = 0,$no_rec = 0,$field,$type,$searchQuery){
			
			$fromLimit = $no_rec*($i - 1);
			$toLimit = $no_rec;
			if(trim($searchQuery)!=''){
				$query = "SELECT * from priceplan ".$searchQuery." group by month_id ORDER BY month_id {$type} LIMIT {$fromLimit},{$toLimit}";
			}
			else{

			$query = "SELECT * from priceplan group by month_id ORDER BY month_id {$type} LIMIT {$fromLimit},{$toLimit}";
			
			}
			
			$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);
			
			return $result;
		}

		
		
		/*
			Function 			: _isPlanExists
			Usage	   			: To check whether the Plan already exists
			Variable Passing 	:
		*/
		public function _isPlanExists($month){
			$bool	= false;
				$query 	= 	"SELECT * FROM priceplan WHERE month_id ='".$month."' "; 
					$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);
				
					if(count($result) >0){
						$bool = true;
					}
			
			return $bool;
		}
		
		
		
}

?>

<?php
  class footerLinks{
  
  public function _showPage($totalRecs,$i = 0,$no_rec = 0,$field,$type,$lanId,$searchQuery){
		$fromLimit = $no_rec*($i - 1);
		$toLimit = $no_rec;
		if(trim($searchQuery)!=''){
			$query = "SELECT id ,footer_name,link,status  FROM footer_links where ".$searchQuery." and lanId='$lanId' ORDER BY $field $type LIMIT $fromLimit,$toLimit";
		}else{
		$query = "SELECT id ,footer_name,link,status FROM footer_links where  lanId='$lanId' ORDER BY $field $type LIMIT $fromLimit,$toLimit";
		}
		//echo $query;
		$result = $GLOBALS['db']->query($query);
		return $result;
	}
	//to insert into the database
	//passing parameter should be the language name 
	
	public function _getFooterDetails(&$id){
	  $sql="SELECT id ,footer_name,link,status  FROM footer_links WHERE id={$id}";
	  $res = $GLOBALS['db']->getRow($sql, DB_FETCHMODE_ASSOC);
				if(DB::isError($res)) {
					echo $res->getDebugInfo();
				}
	 
	  return $res;			
	  
	  }
	  
	  public function _getLanguageArray(){
	  $sql = "SELECT id ,footer_name,link,status  FROM footer_links  ORDER BY footer_name";
	  
	  $res = $GLOBALS['db']->getAll($sql, DB_FETCHMODE_ASSOC);
			if(DB::isError($res)) {
				echo $res->getDebugInfo();
			}
			 return $res;
	  }
	  
	  public function _getFooterArray($lanId){
	  $sql = "SELECT id,footer_name,link,status FROM footer_links WHERE status = '1' and lanId='$lanId' ORDER BY footer_name";
	  
	  $res = $GLOBALS['db']->getAll($sql, DB_FETCHMODE_ASSOC);
			if(DB::isError($res)) {
				echo $res->getDebugInfo();
			}
			 return $res;
	  }
			
  
  }

?>
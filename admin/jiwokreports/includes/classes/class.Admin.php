<?
class Admin{
	
	
		/******************Function For Paging*****************************************************************/
	
	
	public function _showPage($totalRecs,$i = 0,$no_rec = 0,$field,$type,$searchQuery){
		$fromLimit = $no_rec*($i - 1);
		$toLimit = $no_rec;
		if(trim($searchQuery)!=''){
			$query = "SELECT * FROM admin ".$searchQuery." ORDER BY $field $type LIMIT $fromLimit,$toLimit";
		}else{
		$query = "SELECT * FROM admin ORDER BY $field $type LIMIT $fromLimit,$toLimit";
		}
		$result = $GLOBALS['db']->query($query);
		return $result;
	}
		
}

?>

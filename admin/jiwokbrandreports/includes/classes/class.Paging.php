<?
class Paging{
	
	
		/******************Function For Paging*****************************************************************/
	
	
	public function _showPage($table_name,$totalRecs,$i = 0,$no_rec = 0,$field,$type,$searchQuery){
		$fromLimit = $no_rec*($i - 1);
		$toLimit = $no_rec;
		if(trim($searchQuery)!=''){
			$query = "SELECT * FROM ".$table_name ." ".$searchQuery." ORDER BY $field $type LIMIT $fromLimit,$toLimit";
		}else{
		$query = "SELECT * FROM ".$table_name ."  ORDER BY $field $type LIMIT $fromLimit,$toLimit";
		}
		$result = $GLOBALS['db']->query($query);
		return $result;
	}
	
	public function _showMenuPage($langId,$totalRecs,$menuMasterId,$i = 0,$no_rec = 0,$field,$type,$searchQuery){
		$fromLimit = $no_rec*($i - 1);
		$toLimit = $no_rec;
		
		if(trim($searchQuery)!=''){
			$query = "SELECT * FROM menus,label_manager WHERE menus.menumaster_id = ".$menuMasterId." and menus.menu_id =label_manager.labeltype_id  and  label_manager.label_type='MENU' and label_manager.language_id=".$langId.$searchQuery." ORDER BY $field $type LIMIT 		
			$fromLimit,$toLimit";
		}else{

		$query = "SELECT * FROM menus,label_manager WHERE menus.menumaster_id = ".$menuMasterId." and menus.menu_id =label_manager.labeltype_id and label_manager.language_id=".$langId ." and label_manager.label_type='MENU' ORDER BY $field $type LIMIT $fromLimit,$toLimit";
		}

		$result = $GLOBALS['db']->query($query);
		return $result;
	}

	public function _showCategoryPage($langId,$totalRecs,$parentId,$i = 0,$no_rec = 0,$field,$type,$searchQuery){
		$fromLimit = $no_rec*($i - 1);
		$toLimit = $no_rec;
		
		if(trim($searchQuery)!=''){
			$query = "SELECT * FROM categories,label_manager WHERE categories.category_parent = ".$parentId." and categories.category_id =label_manager.labeltype_id  and  label_manager.label_type='CATEGORY' and label_manager.language_id=".$langId.$searchQuery." ORDER BY $field $type LIMIT 		
			$fromLimit,$toLimit";
		}else{

		$query = "SELECT * FROM categories,label_manager WHERE categories.category_parent = ".$parentId." and categories.category_id =label_manager.labeltype_id and label_manager.language_id=".$langId ." and label_manager.label_type='CATEGORY' ORDER BY $field $type LIMIT $fromLimit,$toLimit";
		}

		$result = $GLOBALS['db']->query($query);
		return $result;
	}
		
}


class MenuPaging{
	
	
		/******************Function For Paging*****************************************************************/
	
	
	public function _showPage($masterId,$totalRecs,$i = 0,$no_rec = 0,$field,$type,$searchQuery){
		$fromLimit = $no_rec*($i - 1);
		$toLimit = $no_rec;
				if(trim($searchQuery)!=''){
			$query = "SELECT * FROM menu WHERE menumaster_id = ".$_REQUEST['masterId'].$searchQuery." ORDER BY $field $type LIMIT 		
			$fromLimit,$toLimit";
		}else{

		$query = "SELECT * FROM menu WHERE menumaster_id = ".$_REQUEST['masterId']." 
				ORDER BY $field $type LIMIT $fromLimit,$toLimit";
				}
		$result = $GLOBALS['db']->query($query);
		return $result;
	}
		
}

?>

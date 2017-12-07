<?php
class Language{
    //following fuction for the paging options 
	public function _showPage($totalRecs,$i = 0,$no_rec = 0,$field,$type,$searchQuery){
		$fromLimit = $no_rec*($i - 1);
		$toLimit = $no_rec;
		if(trim($searchQuery)!=''){
			$query = "SELECT language_id ,language_name  FROM languages ".$searchQuery." ORDER BY $field $type LIMIT $fromLimit,$toLimit";
		}else{
		$query = "SELECT language_id ,language_name FROM languages  ORDER BY $field $type LIMIT $fromLimit,$toLimit";
		}
		$result = $GLOBALS['db']->query($query);
		return $result;
	}
	//to insert into the database
	//passing parameter should be the language name
	public function _insertLanguage($languageName){
	//Getting the value of next id to put it in news.
		 $insertFlag=true;	
		 $sql=" INSERT INTO 
				 languages (language_name 
				 ) VALUES ( ?
				 )";
		 $sth = $GLOBALS['db']->prepare($sql);
		   if(DB::isError($sth)){
			   echo $sth->getMessage();
			   $insertFlag=false;
			 }
		   
		 $data= array($languageName);
		 $res = $GLOBALS['db']->execute($sth, $data);
			if(DB::isError($res)){
			   echo $res->getDebugInfo();
			   $insertFlag=false;
			 } 	
			
		 
	  }
	  //to get the language details from the database
	  //passing parameter should be the reference of the id
	  public function _getLanguageDetails(&$id){
	  $sql="SELECT language_name,language_flag FROM languages WHERE language_id={$id}";
	  $res = $GLOBALS['db']->getRow($sql, DB_FETCHMODE_ASSOC);
				if(DB::isError($res)) {
					echo $res->getDebugInfo();
				}
	 
	  return $res;			
	  
	  }
	  //to get the language Name from the database
	  //passing parameter should be the reference of the id
	  public function _getLanguagename(&$id){
	  $sql="SELECT language_name,language_flag FROM languages WHERE language_id={$id}";
	  $res = $GLOBALS['db']->getRow($sql, DB_FETCHMODE_ASSOC);
				if(DB::isError($res)) {
					echo $res->getDebugInfo();
				}
	  
	  return $res['language_name'];			
	  
	  }
      //to update the data
	  //passing parameter should be the reference of the id and the language name;
	  public function _updateLanguage($languageName,$lanId){
	    $updateflag=true;
		$sql="UPDATE 
					languages 
				SET language_name =?
				WHERE 
					language_id =? "; 
					
		$sth = $GLOBALS['db']->prepare($sql);
				if(DB::isError($sth)){
					echo $sth->getMessage();
					}			
		$data=array($languageName,$lanId);
		$res = $GLOBALS['db']->execute($sth, $data);
				if(DB::isError($res)){
					echo $res->getDebugInfo();
					$updateflag=false;
					} 		    
	    return $updateflag;
	  }
	  //to get the all languages
	  public function _getLanguageArray(){
	  $sql = "SELECT language_id ,language_name FROM languages  ORDER BY language_id asc";
	  
	  $res = $GLOBALS['db']->getAll($sql, DB_FETCHMODE_ASSOC);
			if(DB::isError($res)) {
				echo $res->getDebugInfo();
			}
			else{
				$data = array();
				if(!empty($res)) {
					foreach($res as $languages){
					$key=$languages['language_id'];	
					$data[$key]=$languages['language_name'];
											
					}
				}
				
			}
			
			return $data;
	  	  
	  }
	  //to get the all language flag names
	  public function _getFlagArray(){
	  $sql = "SELECT language_id ,language_name,language_flag FROM languages  ORDER BY language_name";
	  $res = $GLOBALS['db']->getAll($sql, DB_FETCHMODE_ASSOC);
	  if(DB::isError($res)) {
			echo $res->getDebugInfo();
	  }
	  else{
			return($res);
	  }
			
		
	  }
	  //the folowing function for delete a language from the table
	  //the deletetion of the language will results into the deletion of all records for a certain language
	  //passing paraneters should be the reference of languageid
	  public function _deleteLanguage(&$lanId,$languageArray,$nonDeleteId){
          if(count($languageArray)>0){
              $Flag= true;
              //deletinng the records coresponds to a language
 			  foreach($languageArray as $key => $val){
                 
           		$sql="DELETE FROM ".$key." WHERE ".$val."=".$lanId."";
                 $res = $GLOBALS['db']->query($sql);
 				if(DB::isError($res)){
 					  echo $res->getDebugInfo();
 					  $Flag = false;
 					 } 
               }
              
              //selecting the user records curresponds to language;
              $query = "SELECT  user_id FROM user_master WHERE user_language=".$lanId."";
				//echo $query;	
			  $result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);
   			  if(count($result)>0){
				foreach($result as $userArray){
                    $userId=$userArray['user_id'];
                    echo $nonDeleteId."<br>";
                    echo $userId;
					    $sql="UPDATE 
					    user_master 
						SET user_language=?
						WHERE 
							user_id=? "; 
							
							$sth = $GLOBALS['db']->prepare($sql);
						if(DB::isError($sth)){
							echo $sth->getMessage();
							}			
						$data=array($nonDeleteId,$userId);
                        $res = $GLOBALS['db']->execute($sth, $data);
							if(DB::isError($res)){
							echo $res->getDebugInfo();
							$Flag=false;
							} 		    
                }
			  }
             $sql="DELETE FROM languages WHERE language_id=".$lanId."";
                 $res = $GLOBALS['db']->query($sql);
 				if(DB::isError($res)){
 					  echo $res->getDebugInfo();
 					  $Flag = false;
 					 } 
               

		  }

		  


      }
	  public  function _setValues(&$res){
	  
	 
// 			$val = array(
// 					$res['language_id ']	=> $res['language_name']
// 					
// 					
// 				   );
// 			return $val;
		}

}
?>

<?php
include_once('class.Languages.php');
class Settings extends Language{

//function _register
//registering the session for the common settings 
	public function _register(){
	   /*if(!session_is_registered('language'))
               session_register('language');*/
			    session_start();  
			   if(!isset($_SESSION['language']))
			   $_SESSION['language'];
	}
//function _registerSports
//assigning the path and common settings for the sports category
	public function _registerLang($lanId){
	
	    $lanName=strtolower($this->_getLanguagename($lanId));
		unset($_SESSION['language']);
                $_SESSION['language']=array(
 					    "xml"          => "xml/".$lanName."/page.xml",
						"langId"       => $lanId,
						
						);
        }
}  
?>
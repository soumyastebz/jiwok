<?php
if($_REQUEST['xmlVar'])
{
	$xmlVar		=	$_REQUEST['xmlVar'];
	$xmlValid	= 	simplexml_load_string($xmlVar);
	if($xmlValid===FALSE) 
	{
		exit('error=0002');
	} 
	else 
	{		
		echo "<pre/>";
		print_r($xmlValid[0]['user_details']);die;
		/*foreach ( $xmlValid[2]['user_details'] as $key => $value )
		{
			echo $value;
			echo "<br/>";
			if($value == 'user_details')
			{	
				foreach($value as $vals => $val)
				{
					echo $val;
				}	
			}		
		}*/
		
		//$xml_array=object2array($xml_object); 
		
		//$array = simplexml_load_string ( $xml );
    	/*$newArray = array ( ) ;
    	$xmlValid = ( array ) $xmlValid ;
    	foreach ( $xmlValid as $key => $value )
    	{
        	$value = ( array ) $value ;
        	$newArray [ $key] = $value [ 0 ] ;
    	}
    	$newArray = array_map("trim", $newArray);
  

		
		print_r($newArray);die;*/die;
		
		
		
		
		
		include_once("../includes/config.php");
		include_once("../includes/globals.php");  
		include_once('../includes/classes/class.General.php');
		include_once('../includes/classes/class.DbAction.php');	
		/*
		Take all the languages to an array.
		*/
		$languageArray = $siteLanguagesConfig;
		reset($languageArray);
								 
		/*
		 Instantiating the classes.
		*/
		$objGen   =	new General();
		$xml = simplexml_load_file("test.xml");
		echo $xml->getName() . "<br />";
		foreach($xml->children() as $child)
		{
			echo $child->getName() . ": " . $child . "<br />";
		}
	}
	
	
	
}
else
{
	exit('error=0001');
}
/*
List of errors.
0000	:	Success
0001	:	Parameter error
0002	:	XML format error
*/
?>

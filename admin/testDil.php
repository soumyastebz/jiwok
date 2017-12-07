<?php

	include_once('includeconfig.php');
	include_once('../includes/classes/class.member.php');
	
	///--------------------------------------
	function __unserialize($sObject) {
   
    $__ret =preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", $sObject );
   
    return unserialize($__ret);
   
}
	///--------------------------------------
	$sqlQry		    	=	"SELECT * FROM `payment_transactions`";
	$result				=	$GLOBALS['db']->getAll($sqlQry,DB_FETCHMODE_ASSOC);
	echo "<pre/>";
	foreach($result	as $key=>$results)
	{
		//$result	=	base64_encode($results[details]);
		/*$query    = "UPDATE `payment_transactions` 
						 	SET details = ? 
						 	WHERE id = ? ";*/
				//$update	  = $GLOBALS['db']->query($query, array($result, $results[id]));
				//echo $query;
				

			//$sObject3 = 'a:2:{i:0;s:1:"1";i:1;s:3654:"1a1dc91c907325c69271ddf0c944bc72";}';
				

		print_r( __unserialize(base64_decode($results[details])));




			
			
			//print_r(unserialize(base64_decode($results[details])));	
			echo "here".$key."<br/>";	
	}
	die();
	
	///--------------------------------------	

	
	$arrayInsert	=	array();
	
    $arrayInsert['DATEQ'] 		= '20102011121045';
    $arrayInsert[TYPE] 			= '00053';
    $arrayInsert[NUMQUESTION] 	= '0940893746';
    $arrayInsert[MONTANT] 		= '790';
    $arrayInsert[SITE] 			= '1999888';
    $arrayInsert[RANG] 			= '99';
    $arrayInsert[REFERENCE] 	= '0,0,48561,0,0,0,0,0,0,0,0,Jiwok,Euro,en,18-10-2011';
    $arrayInsert[REFABONNE] 	= 'dileepe@reubro.com';
    $arrayInsert[VERSION] 		= '00104';
    $arrayInsert[CLE] 			= '1999888I';
    $arrayInsert[IDENTIFIANT] 	= ''; 
    $arrayInsert[DEVISE] 		= '978';
    $arrayInsert[PORTEUR] 		= 'SLDLrcsLMPC';
    $arrayInsert[DATEVAL] 		= '0112';
    $arrayInsert[CVV] 			= '123';
    $arrayInsert[ACTIVITE] 		= '024';
    $arrayInsert[ARCHIVAGE] 	= 'Jiwokcoach';
    $arrayInsert[NUMAPPEL] 		= '0001016633';
    $arrayInsert[NUMTRANS] 		= '0000642475';
    $arrayInsert[AUTORISATION]  = ''; 
    $arrayInsert[PAYS] 			= ''; 
	
	$text1	=	base64_encode(serialize($arrayInsert));
	$text2	=	serialize($arrayInsert);
	$text3	=	json_encode($arrayInsert);
	
	$query	= "INSERT INTO `testDil`
		(`testText`, `testText2`, `testText3`) 
			VALUES 
		(?,?,?)";
	//$res  = $GLOBALS['db']->query($query, array($text1, $text2, $text3));
	//print_r(json_encode($arrayInsert));
	//die();

	$sqlQry		    	=	"SELECT * FROM `testDil`";
	$result				=	$GLOBALS['db']->getAll($sqlQry,DB_FETCHMODE_ASSOC);
	foreach($result	as $results)
	{
		//print_r(unserialize($results[testText2]));
		print_r(unserialize(base64_decode($results[testText])));
		//echo "<pre/>";
		//$re=(json_decode($results[testText3]));
		//print_r($re[REFERENCE]);
		/*$result	=	base64_encode($results[details]);
		$query    = "UPDATE `payment_transactions` 
						 	SET details = ? 
						 	WHERE id = ? ";*/
				//$update	  = $GLOBALS['db']->query($query, array($result, $results[id]));
				//echo $query;		
	}
	//die();
	
	///--------------------------------------		
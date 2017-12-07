	<?php
$report_criteria = trim($_POST['report_criteria']);
	if($_POST['frD']!='' &&$_POST['frM']!='' && $_POST['frY']!='' &&  $_POST['toD']!='' &&  $_POST['toM']!='' &&  $_POST['toY']!='')
	{	
	$frD = $_POST['frD'];
	$frM = $_POST['frM'];
	$frY = $_POST['frY'];
	$toD = $_POST['toD'];
	$toM = $_POST['toM'];
	$toY = $_POST['toY'];	
	
	/*if($toM=='12')
	{
	$toM = '01';
	$toY = $toY+1;
	}
	else
	$toM  = $toM+1;*/
	}
	else
	{		
		$frD = "01";
		$frM = date('m');
		$frY = date('Y');
		//$toD = date("d",mktime(0, 0, 0, (date('m') + 1), 0, date('Y')));		
		$toD =	date('t', strtotime('now'));
		$toM =  date('m');
		$toY = date('Y');
		/*if(strlen($lastMonthsDetails['mon'])==1)
		$frM = "0".$lastMonthsDetails['mon'];
		else
		$frM = $lastMonthsDetails['mon'];
		
	    $frY = $lastMonthsDetails['year'];
	    $toM = date('m');
	    $toY = date('Y');*/
		/*$frM 	= "01";
		$toM = date('m');
		$frY 	= "2005";
		$toY = date('Y');
		$all = 1;*/
	}
	
	if($_POST['country_select'] == '1'){
		if($_POST['user_country_1']!="")
		{
			$countryCondn = $_POST['user_country_1'];
		}
		elseif($_POST['user_country']!="")
		{
		$user_country = implode(',',$_POST['user_country']);
		$countryCondn = " AND user_country IN(". $user_country.") ";
		}
	}
		/*if($_POST['grouping']=="brand_name")
	{
	echo  $_POST['user_gender_1'];
	die;
	}	*/
	if($_POST['user_gender_1']!="")
	{
		$genderCondn = $_POST['user_gender_1'];
	}
	elseif($_POST['user_gender']!=""){
		$user_gender = implode(',',$_POST['user_gender']);
		$genderCondn = " AND user_gender IN(". $user_gender.") ";
		
	}

	/*if($_POST['user_origin']!=""){
		$originCondn = " AND origin='".trim($_POST['user_origin'])."'";
		if(trim($_POST['user_origin'])=='1')
		{
			if(trim($_POST['code'])!="")
			{
				$discCondn = " AND discount_code='".trim($_POST['code'])."'";
			}
		}
	
	}*/
	if($_POST['origin_select'] == '1'){
		if($_POST['user_origin_1']!="")
		{
			$originCondn = stripslashes($_POST['user_origin_1']);
		}
		elseif($_POST['user_origin']!="")
		{		
		$user_origin = implode(',',$_POST['user_origin']);		
		$originCondn = " AND origin IN(". $user_origin.") ";			
		}	
	}
	
	
	
	
	
	if(!$_POST['user_fromage']  && !$_POST['user_toage'])
	{
		$_POST['user_fromage']  = 1;
		$_POST['user_toage'] = 99;
	}
	$fromage = trim(stripslashes($_POST['user_fromage']));
	$toage   = trim(stripslashes($_POST['user_toage']));
	
	if(trim(stripslashes($_POST['user_fromage']))!=""){
		$ageCondn1 = " AND FLOOR(extract(YEAR FROM from_days(DATEDIFF(curdate(), STR_TO_DATE( a.user_dob, '%d/%m/%Y' )))))>=".$fromage;
	}
	if(trim(stripslashes($_POST['user_toage']))!=""){
		$ageCondn2 = " AND FLOOR(extract(YEAR FROM from_days(DATEDIFF(curdate(), STR_TO_DATE( a.user_dob, '%d/%m/%Y' )))))<=".$toage;
	}
	if(isset($ageCondn1) && isset($ageCondn2))
	{
		$ageCondn1 = " AND FLOOR(extract(YEAR FROM from_days(DATEDIFF(curdate(), STR_TO_DATE( a.user_dob, '%d/%m/%Y' ))))) BETWEEN ".$fromage." AND ".$toage;
		$ageCondn2 = "";
	}
	
	/*if(trim(stripslashes($_POST['user_program']))!=""){
		if(trim(stripslashes($_POST['user_program']))=='Yes')
		  { $programCondn = " AND training=1 "; }
		else
		  { $programCondn = " AND training=0"; }
	}*/
	if($_POST['program_select'] == '1'){
		if($_POST['user_program_1']!="")
		{
			$programCondn = stripslashes($_POST['user_program_1']);
		}
		elseif($_POST['user_program']!="")
		{		
		$user_program = implode(',',$_POST['user_program']);		
		$programCondn = " AND training IN(". $user_program.") ";			
		}	
	}	
	
	//print_r($_POST['user_brand']);
	if($_POST['brand_select'] == '1'){
		if($_POST['user_brand_1']!="")
		{
			$brandCondn = stripslashes($_POST['user_brand_1']);
		}
		elseif($_POST['user_brand']!="")
		{		
		$user_brand = implode(',',$_POST['user_brand']);
		$brandCondn = "  AND (";
		$conditionArray = array();
		foreach ($_POST['user_brand'] as $value) {
			if(trim($value)=='0')
				{ 
					$conditionArray[]= " b.brand_master_id IS NULL ";
				}
				else{  
		    	 $conditionArray[]= " b.brand_master_id=".$value; 
				}
			  
		}
		$brandCondn.= implode(' OR',$conditionArray);
		$brandCondn .= " ) ";		
		}	
	}
	
	if($_POST['type_select'] == '1'){
		if($_POST['user_type_1']!="")
		{
			$typeCondn = stripslashes($_POST['user_type_1']);
		}
		elseif($_POST['user_type']!="")
		{		
		$user_type = implode(',',$_POST['user_type']);		
		$typeCondn = " AND type IN(". $user_type.") ";			
		}	
	}	
	if($_POST['cmp_select'] == '1'){
		if($_POST['user_cmp_1']!="")
		{
			$cmpCondn = stripslashes($_POST['user_cmp_1']);
		}
		elseif($_POST['user_cmp']!="")
		{		
		$user_cmp = implode(',',$_POST['user_cmp']);		
		$cmpCondn = " AND cn.id IN(". $user_cmp.") ";			
		}	
	}
	if(stripslashes($_POST['user_code'])!=""){
		
			$codeCondn = " AND cr.gift_card_no='".trim($_POST['user_code'])."'";
	}
	if($_POST['user_language_1']!="")
	{
		$languageCondn = $_POST['user_language_1'];
	}
	elseif($_POST['user_language']!=""){
		$user_language = implode(',',$_POST['user_language']);
		$languageCondn = " AND user_language IN(". $user_language.") ";
		
	}
	?>
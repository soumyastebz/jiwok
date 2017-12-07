<?
include_once("class.DbAction.php");
class General extends DbAction{
	
	/* Function to fetch all menu_id's of main menu from label_manager table */
	public function _fetchAllCategories($root,$level,$lanId){
		
		global $catArray;

		$sql 	= "SELECT category_id FROM categories WHERE  category_status =1 AND category_parent=".$root;
		$result = $GLOBALS['db']->getAll($sql,DB_FETCHMODE_ASSOC);
		foreach($result as $key => $data){
		
			$level++;
				
			$query	= "SELECT labeltype_id,label_name FROM label_manager WHERE labeltype_id = ".$data['category_id']." AND language_id = ".$lanId." AND label_type = 'CATEGORY'";
			$res	= $GLOBALS['db']->getRow($query,DB_FETCHMODE_ASSOC);
			if($level==1)
				$catArray[$res['labeltype_id']]	= str_repeat("----",($level-1)).stripslashes($res['label_name']);
			else
				$catArray[$res['labeltype_id']]	= str_repeat("----",($level-1)).">".stripslashes($res['label_name']);

			$this->_fetchAllCategories($data['category_id'],$level--,$lanId);
		}	
		$level--;
	}
	
	
	/*
		Function to fetch menu name from label_manager table
	*/
	public function _fetchMenuName($masterId,$lanId){
		$sql 	= "SELECT menu_id FROM menus WHERE menu_id = {$masterId}";
		$result = $GLOBALS['db']->getAll($sql,DB_FETCHMODE_ASSOC);
		foreach($result as $key => $data){
			$menuId = $data['menu_id'];
			$query	= "SELECT labeltype_id,label_name FROM label_manager WHERE labeltype_id = {$menuId} AND language_id = {$lanId} AND label_type = 'MENU'";

			$res	= $GLOBALS['db']->getRow($query,DB_FETCHMODE_ASSOC);
			$returnArray[]	= stripslashes($res['label_name']);
		}
		return $returnArray[0];
	}
	
	/*
		Function to fetch category name from label_manager table
	*/
	public function _fetchCategoryName($masterId,$lanId){
		$sql 	= "SELECT distinct category_id FROM categories WHERE category_id  = {$masterId}";
		$result = $GLOBALS['db']->getAll($sql,DB_FETCHMODE_ASSOC);

		foreach($result as $key => $data){
			$menuId = $data['category_id'];
			$query	= "SELECT labeltype_id,label_name FROM label_manager WHERE labeltype_id = {$menuId} AND language_id = {$lanId} AND label_type = 'CATEGORY'";
			//print "<br>".$query;

			$res	= $GLOBALS['db']->getRow($query,DB_FETCHMODE_ASSOC);
			$returnArray[]	= stripslashes($res['label_name']);
		}
		
		return $returnArray[0];
	}

	public function _checkUploadImage($image){
		$flag;
		switch ($image){
			case "image/gif" 	: $flag = TRUE;
						break;
			case "image/JPEG" 	: $flag = TRUE;
						break;
			case "image/jpeg" 	: $flag = TRUE;
						break;
			case "image/pjpg" 	: $flag = TRUE;
						break;
			case "image/png" 	: $flag = TRUE;
						break;		
			case "image/pJPEG" 	: $flag = TRUE;
						break;
			case "image/pJPG" 	: $flag = TRUE;
						break;
			case "image/Pjpg" 	: $flag = TRUE;
						break;
			case "image/pjpeg" 	: $flag = TRUE;
						break;
			
			default: $flag = FALSE;
		}
		return $flag;
	}
	public function _checkUploadAudio($ext){
		$flag;
		switch ($ext){
			
			case "audio/mpeg" 	: $flag = TRUE;
						break;
			default: $flag = FALSE;
		}
	
		return $flag;
	}




	public function _upload($strTmpName,$strFileName){ 
		
		if(move_uploaded_file($strTmpName,$strFileName)){ 
		        
				$flag = TRUE;
		}
		else{
			$flag = FALSE;
		}
		
		return $flag;
		
	}

	
	public function _clearElmts($elmts){
		foreach($elmts as $k=>$v){
			$v = trim($v);
			$elmts[$k] = addslashes($v);
		}
		return $elmts;
	}

	public function _clearElmtsSingle($elmts){
		if (!get_magic_quotes_gpc())
		{
			return addslashes($elmts);
		}
		else
		{
			return $elmts;
		}
	}
	
	public function _clearElmtsWithoutTrim($elmts){

		foreach($elmts as $k=>$v){
				$elmts[$k] = addslashes($v);
		}
		return $elmts;
	}

	public function _clearTags($elmts){
		foreach($elmts as $k=>$v){
			$elmts[$k] = strip_tags($v);
		}
		return $elmts;
	}

	public function _validInt($int){
		$pattern = '/[^0-9]/';
		if(!preg_match($pattern,$int))
		{
			return TRUE;
		}
		else
			return FALSE;
	}
	// for getting the current page name
	function curPageName() {
	 return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
	}	
	
	// for getting the current page name with query string
	function curQueryPageName() {
	
	  $self = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
		
		 if($_SERVER["QUERY_STRING"]) {
			$finalurl = $self . "?" . $_SERVER["QUERY_STRING"];   
		 } else {
			$finalurl = $self;  
		 }
	  return $finalurl;
	}	
	
	// For escaping and trimming strings
	public function _clean_data($data){
		return addslashes(trim($data));
	}

	// For printing data
	public function _output($data){
		//return htmlspecialchars(stripslashes($data));
		return stripslashes(stripslashes($data));
	}
	
	public function _outputLimit($data,$lowLimit,$upLimit){
		//return htmlspecialchars(stripslashes($data));
		return stripslashes(stripslashes(substr($data,$lowLimit,$upLimit)));
	}
	////////////////////////////////////////////////////////////////////////////////////////////////
  // Function    : validate_email
  // Arguments   : email   email address to be checked
  // Return      : true  - valid email address
  //               false - invalid email address
  ////////////////////////////////////////////////////////////////////////////////////////////////
 
  public function _validate_email($email){ 
 $result = TRUE;
  if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)) {
    $result = FALSE;
  }
  return $result;
}
   // $valid_address = true;
//    $mail_pat = '^(.+)@(.+)$';
//    $valid_chars = "[^] \(\)<>@,;:\.\\\"\[]";
//    $atom = "$valid_chars+";
//    $quoted_user='(\"[^\"]*\")';
//    $word = "($atom|$quoted_user)";
//    $user_pat = "^$word(\.$word)*$";
//    $ip_domain_pat='^\[([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\]$';
//    $domain_pat = "^$atom(\.$atom)*$";
//
//    if (eregi($mail_pat, $email, $components)) {
//      $user = $components[1];
//      $domain = $components[2];
//      // validate user
//      if (eregi($user_pat, $user)) {
//        // validate domain
//        if (eregi($ip_domain_pat, $domain, $ip_components)) {
//          // this is an IP address
//      	  for ($i=1;$i<=4;$i++) {
//      	    if ($ip_components[$i] > 255) {
//      	      $valid_address = false;
//      	      break;
//      	    }
//          }
//        }
//        else {
//          // Domain is a name, not an IP
//          if (eregi($domain_pat, $domain)) {
//            /* domain name seems valid, but now make sure that it ends in a valid TLD or ccTLD
//               and that there's a hostname preceding the domain or country. */
//            $domain_components = explode(".", $domain);
//            // Make sure there's a host name preceding the domain.
//            if (sizeof($domain_components) < 2) {
//              $valid_address = false;
//            } else {
//              $top_level_domain = strtolower($domain_components[sizeof($domain_components)-1]);
//              // Allow all 2-letter TLDs (ccTLDs)
//              if (eregi('^[a-z][a-z]$', $top_level_domain) != 1) {
//                $tld_pattern = '';
//                // Get authorized TLDs from text file
//               // $tlds = file(SITE_URL.'includes/tld.txt');
//			   $tlds = file('tld.txt');
//                while (list(,$line) = each($tlds)) {
//                  // Get rid of comments
//                  $words = explode('#', $line);
//                  $tld = trim($words[0]);
//                  // TLDs should be 3 letters or more
//                  if (eregi('^[a-z]{3,}$', $tld) == 1) {
//                    $tld_pattern .= '^' . $tld . '$|';
//                  }
//                }
//                // Remove last '|'
//                $tld_pattern = substr($tld_pattern, 0, -1);
//                if (eregi("$tld_pattern", $top_level_domain) == 0) {
//                    $valid_address = false;
//                }
//              }
//            }
//          }
//          else {
//      	    $valid_address = false;
//      	  }
//      	}
//      }
//      else {
//        $valid_address = false;
//      }
//    }
//    else {
//      $valid_address = false;
//    }
//  if ($valid_address && ENTRY_EMAIL_ADDRESS_CHECK == 'true') {
//      if (!checkdnsrr($domain, "MX") && !checkdnsrr($domain, "A")) {
//        $valid_address = false;
//      }
//    } 
//    return $valid_address;
//  }
//
//
//
///* Function which is used to add number of days to a particilar date
//  Arguments : date to be process, number of days ,operator(+/-)
//  Return	: date (format(Y-m-d))*/
//
//public function _processDate($firstDate,$numDays,$operator){
//
//	$date	=	date('Y-m-d',strtotime($firstDate.$operator.$numDays." days"));
//	return $date;
//}


/* ******  Encode the value ******* 
Argument	: value for encode
Return type : encoded vaue
*/
public function _encodeValue($value){
	if(trim($value)!='')
		return base64_encode($value);
	else
		return '';
}

/* ******  Decode the value ******* 
Argument	: value for decode
Return type : decoded vaue
*/
public function _decodeValue($value){
	if(trim($value)!='')
		return base64_decode($value);
	else
		return '';
}


public function _datediff($interval, $datefrom, $dateto, $using_timestamps = false) {
  /*
    $interval can be:
    yyyy - Number of full years
    q - Number of full quarters
    m - Number of full months
    y - Difference between day numbers
      (eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
    d - Number of full days
    w - Number of full weekdays
    ww - Number of full weeks
    h - Number of full hours
    n - Number of full minutes
    s - Number of full seconds (default)
  */
  
  if (!$using_timestamps) {
    $datefrom = strtotime($datefrom, 0);
    $dateto = strtotime($dateto, 0);
  }
  $difference = $dateto - $datefrom; // Difference in seconds
   
  switch($interval) {
   
    case 'yyyy': // Number of full years

      $years_difference = floor($difference / 31536000);
      if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom)+$years_difference) > $dateto) {
        $years_difference--;
      }
      if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) {
        $years_difference++;
      }
      $datediff = $years_difference;
      break;

    case "q": // Number of full quarters

      $quarters_difference = floor($difference / 8035200);
      while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
        $months_difference++;
      }
      $quarters_difference--;
      $datediff = $quarters_difference;
      break;

    case "m": // Number of full months

      $months_difference = floor($difference / 2678400);
      while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
        $months_difference++;
      }
      $months_difference--;
      $datediff = $months_difference;
      break;

    case 'y': // Difference between day numbers

      $datediff = date("z", $dateto) - date("z", $datefrom);
      break;

    case "d": // Number of full days

      $datediff = floor($difference / 86400);
      break;

    case "w": // Number of full weekdays

      $days_difference = floor($difference / 86400);
      $weeks_difference = floor($days_difference / 7); // Complete weeks
      $first_day = date("w", $datefrom);
      $days_remainder = floor($days_difference % 7);
      $odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
      if ($odd_days > 7) { // Sunday
        $days_remainder--;
      }
      if ($odd_days > 6) { // Saturday
        $days_remainder--;
      }
      $datediff = ($weeks_difference * 5) + $days_remainder;
      break;

    case "ww": // Number of full weeks

      $datediff = floor($difference / 604800);
      break;

    case "h": // Number of full hours

      $datediff = floor($difference / 3600);
      break;

    case "n": // Number of full minutes

      $datediff = floor($difference / 60);
      break;

    default: // Number of full seconds (default)

      $datediff = $difference;
      break;
  }    

  return $datediff;

}



/* To Print Error messages
Argument: Message to print as array
*/
public function _adminmessage_box($arrError,$mode='error',$url='normal')
{
	global $config;
	$path = "images/exclamation.gif";
	$bulletPath	=	 "images/bullet.gif";
	if($mode=='error') {
		$html=<<<EOD
		<table class="Summary" cellpadding="0" cellspacing="0" border="0" width="98%"><tr><td>
		<img src="$path"> Please correct the following errors.<ul>
EOD;
		foreach($arrError as $key=>$value)
			$html .= "<img src=$bulletPath>&nbsp;$value <br>";
			//$html .= "<li> $value </li>";
		$html .= "</td></tr></table>";
	}
	else {
		$html=<<<EOD
		<table class="successMessage" cellpadding="0" cellspacing="0" border="0" width="95%"><tr><td>
EOD;
		foreach($arrError as $key=>$value)
			$html .= "<li> $value </li>";
		$html .= "</td></tr></table>";
	}
	
	return $html;
}

public function _errormessage_box($arrError,$mode='error',$url='normal')
{
	global $config;
	$path = "./admin/images/ValidationHeader.GIF";
	$bulletPath	=	 "./admin/images/bullet.gif";
	if($mode=='error') {
		$html=<<<EOD
		<table class="Summary" cellpadding="0" cellspacing="0" border="0" width="98%"><tr><td>
		<img src="$path"> Please correct the following errors.<ul>
EOD;
		foreach($arrError as $key=>$value)
			$html .= "<img src=$bulletPath>&nbsp;$value <br>";
			//$html .= "<li> $value </li>";
		$html .= "</td></tr></table>";
	}
	else {
		$html=<<<EOD
		<table class="successMessage" cellpadding="0" cellspacing="0" border="0" width="95%"><tr><td>
EOD;
		foreach($arrError as $key=>$value)
			$html .= "<li> $value </li>";
		$html .= "</td></tr></table>";
	}
	
	return $html;
}

/**
#
 * Smarty shared plugin
#
 * @package Smarty
#
 * @subpackage plugins
#
 */

/**
#
 * Function: smarty_make_timestamp<br>
#
 * Purpose:  used by other smarty functions to make a timestamp
#
 *           from a string.
#
 * @param string 
#
 * @return string 
#
 */

function _make_timestamp($string)
#
{
#
    if(empty($string)) {
#
        $string = "now";
#
    }
#
    $time = strtotime($string);
#
    if (is_numeric($time) && $time != -1)
#
        return $time;
#
 
#
    // is mysql timestamp format of YYYYMMDDHHMMSS?
#
    if (preg_match('/^\d{14}$/', $string)) {
#
        $time = mktime(substr($string,8,2),substr($string,10,2),substr($string,12,2),
#
               substr($string,4,2),substr($string,6,2),substr($string,0,4));
#
 
#
        return $time;
#
    }
#
 
#
    // couldn't recognize it, try to return a time
#
    $time = (int) $string;
#
    if ($time > 0)
#
        return $time;
#
    else
#
        return time();
#
}
#
 
#
/* vim: set expandtab: */
#
 
#
#
function _modifier_date_format($string, $format="%b %e, %Y", $default_date=null)
#
{
#
    if($string != '') {
#
        return strftime($format, $this->_make_timestamp($string));
#
    } elseif (isset($default_date) && $default_date != '') {        
#
        return strftime($format, $this->_make_timestamp($default_date));
#
    } else {
#
        return;
#
    }
#
}

function _show($array){
	echo "<pre>";print_r($array);
}
	
function _printError($errorMsg){
    echo '<table width="100%">';
    $i = 0;
    while($i < count($errorMsg)){
        echo '<tr><td align="right" width="6%" class="errortext"><img src="images/err1.png" width="20" height="20" />';
       
        echo '</td>';
        echo '<td   class="errortext" align="left">';
        echo $errorMsg[$i];
        echo '</td></tr>';
        $i++;
    }
    echo '</table>';

}
//function to resize the image
public function _fileUploadWithOutImageResize($imgFileObject,$uploadPath,$fileNameWithoutExtension)
	{		
	        
			if($_FILES[$imgFileObject]['name']!=null)
			{	$filename1 = $_FILES[$imgFileObject]['tmp_name'];
				$filename3 = $_FILES[$imgFileObject]['name'];
				$path_parts = pathinfo($filename3);
				$ext=$path_parts["extension"];//the extension for the uploading image
				
				$filename2=$fileNameWithoutExtension;//base image for the resizing
				$filename2=$filename2.".".$ext;
				$uploadStatus=move_uploaded_file($filename1,$uploadPath.$filename2);
		
		}
		return 	$uploadStatus;
	}
//function to resize the image
public function _fileUploadWithImageResize($imgFileObject,$uploadPath,$fileNameWithoutExtension,$x_width,$y_hei)
	{		
	        
			if($_FILES[$imgFileObject]['name']!=null)
			{	$filename1 = $_FILES[$imgFileObject]['tmp_name'];
				$filename3 = $_FILES[$imgFileObject]['name'];
				$path_parts = pathinfo($filename3);
				$ext=$path_parts["extension"];//the extension for the uploading image
				
				$filename2=$fileNameWithoutExtension;//base image for the resizing
				$filename2="ph_".$filename2.".".$ext;
				$uploadStatus=move_uploaded_file($filename1,$uploadPath.$filename2);
				$path=$uploadPath.$filename2;
				list($width,$height) = getimagesize($path);//to get the size of the uploaded image 
				//############################ to find the resizing percent ##########################
				if($width<$x_width && $height<$y_hei)
			      {
			        $percent=1;
			      }
                  else
			      {
			           if($width<$x_width && $height>$y_hei)
			             {
				             $percent=$y_hei/$height;
				         }
			           else
			             {
						  if($width>$x_width && $height<$y_hei)
			                 {
				              $percent=$x_width/$width;
				             }
			             else{
				     if($width<$height)
					 {
					  $a=$height;
					  $percent=$y_hei/$height;
					  }
					   else{
					         $a=$width;
							  $percent=$x_width/$width;
							}
							
				   			
				 }
			   }
			  } 
			  			  
			  	//####################################################################################
				  //following should be the size of the resizing image
		          $newwidth = $width * $percent;
                  $newheight = $height * $percent;
				  $filename_upload=$uploadPath.$filename2;
				  $filename_todelete = $uploadPath.$filename2;
				  
				 
				  if(($ext=='jpg')||($ext=='jpeg')||($ext=='JPG')||($ext=='JPEG')||($ext=='png')||($ext=='PNG')||($ext=='gif')||($ext=='GIF'))
				  {
				  
				  /* exit;*/
				  if(($ext=='jpg')||($ext=='jpeg')||($ext=='JPG')||($ext=='JPEG'))
				              {
							  /*header ("Content-type: image/jpeg");*/
				              $source = @imagecreatefromjpeg($filename_upload);
							  }
				  if(($ext=='png')||($ext=='PNG'))
				              {
							 /* header ("Content-type: image/png");*/
				  			  $source = @imagecreatefrompng($filename_upload);
							  }
				  if(($ext=='gif')||($ext=='GIF'))
				              {
							  /*header ("Content-type: image/gif");*/
				              $source = @imagecreatefromgif($filename_upload);	
							  }	 
				 	   
                  $thumb = imagecreatetruecolor($newwidth, $newheight);
				  imagecopyresampled ($thumb, $source, 0, 0, 0, 0,$newwidth, $newheight, $width,$height);
				  $filename2=$fileNameWithoutExtension;//base image for the resizing
				  $filename2=$filename2.".".$ext;
				 
				  $filename_upload=$uploadPath.$filename2;
				  if(($ext=='jpg')||($ext=='jpeg')||($ext=='JPG')||($ext=='JPEG'))
				              imagejpeg($thumb,$filename_upload);
				  if(($ext=='png')||($ext=='PNG'))
				  			  imagepng($thumb,$filename_upload);
				  if(($ext=='gif')||($ext=='GIF'))
				              imagegif($thumb,$filename_upload);  
                  
				  }
		
				/*if(is_file($filename_todelete))
						unlink($filename_todelete);*/
				return basename($filename_upload);
			
		}	
	}
public function _fileUploadWithImageResizeHomePgm($imgFileObject,$ext,$uploadPath,$fileNameWithoutExtension,$x_width,$y_hei)
	{		
	      $imgFileObject;
			if($imgFileObject!=null)
			{

	$filename1 = $imgFileObject; 
				$uploadPath;
				//exit;
				//$filename3 = $_FILES[$imgFileObject]['name'];
				//$path_parts = pathinfo($filename1);
				//$ext=$path_parts["extension"];//the extension for the uploading image
					$ext	=	$ext;
				$filename2=$fileNameWithoutExtension;//base image for the resizing
				$filename2="ph_".$filename2.".".$ext; 
				$uploadStatus=copy("../uploads/programs/".$filename1,$uploadPath.$filename2);
				$path=$uploadPath.$filename2;
				list($width,$height) = getimagesize($path);//to get the size of the uploaded image 
				//############################ to find the resizing percent ##########################
				if($width<$x_width && $height<$y_hei)
			      {
			        $percent=1;
			      }
                  else
			      {
			           if($width<$x_width && $height>$y_hei)
			             {
				             $percent=$y_hei/$height;
				         }
			           else
			             {
						  if($width>$x_width && $height<$y_hei)
			                 {
				              $percent=$x_width/$width;
				             }
			             else{
				     if($width<$height)
					 {
					  $a=$height;
					  $percent=$y_hei/$height;
					  }
					   else{
					         $a=$width;
							  $percent=$x_width/$width;
							}
							
				   			
				 }
			   }
			  } 
			  			  
			  	//####################################################################################
				  //following should be the size of the resizing image
		       $newwidth = $width * $percent;
                  $newheight = $height * $percent;
				  $filename_upload=$uploadPath.$filename2;
				  $filename_todelete = $uploadPath.$filename2;
				  
				 
				  if(($ext=='jpg')||($ext=='jpeg')||($ext=='JPG')||($ext=='JPEG')||($ext=='png')||($ext=='PNG')||($ext=='gif')||($ext=='GIF'))
				  {
				  
				  /* exit;*/
				  if(($ext=='jpg')||($ext=='jpeg')||($ext=='JPG')||($ext=='JPEG'))
				              {
							  /*header ("Content-type: image/jpeg");*/
				              $source = @imagecreatefromjpeg($filename_upload);
							  }
				  if(($ext=='png')||($ext=='PNG'))
				              {
							 /* header ("Content-type: image/png");*/
				  			  $source = @imagecreatefrompng($filename_upload);
							  }
				  if(($ext=='gif')||($ext=='GIF'))
				              {
							  /*header ("Content-type: image/gif");*/
				              $source = @imagecreatefromgif($filename_upload);	
							  }	 
				 	   
                 $thumb = imagecreatetruecolor($newwidth, $newheight);
				$imc= imagecopyresampled ($thumb, $source, 0, 0, 0, 0,$newwidth, $newheight, $width,$height);
				$filename2=$fileNameWithoutExtension;//base image for the resizing 

				  $filename2=$filename2.".".$ext;
				 
				  $filename_upload=$uploadPath.$filename2;
				  if(($ext=='jpg')||($ext=='jpeg')||($ext=='JPG')||($ext=='JPEG'))
				             $h = imagejpeg($thumb,$filename_upload);
				  if(($ext=='png')||($ext=='PNG'))
				  			  imagepng($thumb,$filename_upload);
				  if(($ext=='gif')||($ext=='GIF'))
				              imagegif($thumb,$filename_upload);  
                  
				  }
		
				if(is_file($filename_todelete))
						unlink($filename_todelete);
				return basename($filename_upload);
			
		}	
	}

//function to resize the image
public function _fileImageResize($imgFile,$uploadPath,$fileNameWithoutExtension,$x_width,$y_hei)
	{		
	        
			if($imgFile != null)
			{	
				$filename3 = $imgFile;
				$ext = end(explode(".",$imgFile));
				
				$filename2=$fileNameWithoutExtension.".".$ext;//base image for the resizing
				//$filename2="ph_".$filename2.".".$ext;
				//$uploadStatus=move_uploaded_file($filename1,$uploadPath.$filename2);
				$path=$uploadPath.$imgFile;
				list($width,$height) = getimagesize($path);//to get the size of the upladed image 
				//############################ to find the resizing percent ##########################
				if($width<$x_width && $height<$y_hei)
			      {
			        $percent=1;
			      }
                  else
			      {
			           if($width<$x_width && $height>$y_hei)
			             {
				             $percent=$y_hei/$height;
				         }
			           else
			             {
						  if($width>$x_width && $height<$y_hei)
			                 {
				              $percent=$x_width/$width;
				             }
			             else{
				     if($width<$height)
					 {
					  $a=$height;
					  $percent=$y_hei/$height;
					  }
					   else{
					         $a=$width;
							  $percent=$x_width/$width;
							}
							
				   			
				 }
			   }
			  } 
			  			  
			  	//####################################################################################
				  //following should be the size of the resizing image
		          $newwidth = $width * $percent;
                  $newheight = $height * $percent;
				  $filename_upload=$uploadPath.$filename2;
				 // $filename_todelete = $uploadPath.$filename2;
				  
				 
				  if(($ext=='jpg')||($ext=='jpeg')||($ext=='JPG')||($ext=='JPEG')||($ext=='png')||($ext=='PNG')||($ext=='gif')||($ext=='GIF'))
				  {
				  
				  /* exit;*/
				  if(($ext=='jpg')||($ext=='jpeg')||($ext=='JPG')||($ext=='JPEG'))
				              {
							  /*header ("Content-type: image/jpeg");*/
				              $source = @imagecreatefromjpeg($path);
							  }
				  if(($ext=='png')||($ext=='PNG'))
				              {
							 /* header ("Content-type: image/png");*/
				  			  $source = @imagecreatefrompng($path);
							  }
				  if(($ext=='gif')||($ext=='GIF'))
				              {
							  /*header ("Content-type: image/gif");*/
				              $source = @imagecreatefromgif($path);	
							  }	 
				 	   
                  $thumb = imagecreatetruecolor($newwidth, $newheight);
				  imagecopyresampled ($thumb, $source, 0, 0, 0, 0,$newwidth, $newheight, $width,$height);
				  /*$filename2=$fileNameWithoutExtension;//base image for the resizing
				  $filename2=$filename2.".".$ext;*/
				 
				  $filename_upload=$uploadPath.$filename2;
				  if(($ext=='jpg')||($ext=='jpeg')||($ext=='JPG')||($ext=='JPEG'))
				              imagejpeg($thumb,$filename_upload);
				  if(($ext=='png')||($ext=='PNG'))
				  			  imagepng($thumb,$filename_upload);
				  if(($ext=='gif')||($ext=='GIF'))
				              imagegif($thumb,$filename_upload);  
                  
				  }
		
				/*if(is_file($filename_todelete))
						unlink($filename_todelete);*/
				
			
		}	
	}

//function to resize the image
public function _fileImageResize2($imgFile,$uploadPath,$fileName,$x_width,$y_hei)
	{		
	        
			if($imgFile != null)
			{	
				$filename3 = $imgFile;
				$ext = end(explode(".",$imgFile));
				
				$filename2=$fileName;//base image for the resizing
				//$filename2="ph_".$filename2.".".$ext;
				//$uploadStatus=move_uploaded_file($filename1,$uploadPath.$filename2);
				$path=$uploadPath.$imgFile;
				list($width,$height) = getimagesize($path);//to get the size of the upladed image 
				//############################ to find the resizing percent ##########################
				if($width<$x_width && $height<$y_hei)
			      {
			        $percent=1;
			      }
                  else
			      {
			           if($width<$x_width && $height>$y_hei)
			             {
				             $percent=$y_hei/$height;
				         }
			           else
			             {
						  if($width>$x_width && $height<$y_hei)
			                 {
				              $percent=$x_width/$width;
				             }
			             else{
				     if($width<$height)
					 {
					  $a=$height;
					  $percent=$y_hei/$height;
					  }
					   else{
					         $a=$width;
							  $percent=$x_width/$width;
							}
							
				   			
				 }
			   }
			  } 
			  			  
			  	//####################################################################################
				  //following should be the size of the resizing image
		          $newwidth = $width * $percent;
                  $newheight = $height * $percent;
				  $filename_upload=$uploadPath.$filename2;
				 // $filename_todelete = $uploadPath.$filename2;
				  
				 
				  if(($ext=='jpg')||($ext=='jpeg')||($ext=='JPG')||($ext=='JPEG')||($ext=='png')||($ext=='PNG')||($ext=='gif')||($ext=='GIF'))
				  {
				  
				  /* exit;*/
				  if(($ext=='jpg')||($ext=='jpeg')||($ext=='JPG')||($ext=='JPEG'))
				              {
							  /*header ("Content-type: image/jpeg");*/
				              $source = imagecreatefromjpeg($path);
							  }
				  if(($ext=='png')||($ext=='PNG'))
				              {
							 /* header ("Content-type: image/png");*/
				  			  $source = @imagecreatefrompng($path);
							  }
				  if(($ext=='gif')||($ext=='GIF'))
				              {
							  /*header ("Content-type: image/gif");*/
				              $source = @imagecreatefromgif($path);	
							  }	 
				 	   
                  $thumb = imagecreatetruecolor($newwidth, $newheight);
				  imagecopyresampled ($thumb, $source, 0, 0, 0, 0,$newwidth, $newheight, $width,$height);
				  /*$filename2=$fileNameWithoutExtension;//base image for the resizing
				  $filename2=$filename2.".".$ext;*/
				 
				  $filename_upload=$uploadPath.$filename2;
				  if(($ext=='jpg')||($ext=='jpeg')||($ext=='JPG')||($ext=='JPEG'))
				              imagejpeg($thumb,$filename_upload);
				  if(($ext=='png')||($ext=='PNG'))
				  			  imagepng($thumb,$filename_upload);
				  if(($ext=='gif')||($ext=='GIF'))
				              imagegif($thumb,$filename_upload);  
                  
				  }
		
				/*if(is_file($filename_todelete))
						unlink($filename_todelete);*/
			//	unlink($path);
			
		}	
	}

/*To get the files in a particular directry
the passing parameter should be the directry name 
Return should be the array curresponding to the files in a directry
*/
public function _dirList ($directory){
	
		// create an array to hold directory list
		$results = array();
	
		// create a handler for the directory
		$handler = opendir($directory);
	
		// keep going until all files in directory have been read
		while ($file = readdir($handler)) {
	
			// if $file isn't this directory or its parent, 
			// add it to the results array
			if ($file != '.' && $file != '..')
				$results[] = $file;
		}
	
		// tidy up: close the handler
		closedir($handler);
	
		// done!
		return $results;
	
	}

  //to dispaly the star for the rating
  //passing parameter should be the program id
  //return should be the html string corresponding table
  public function _displayRating($programId){
  		
		$sql="SELECT program_ratingcount,program_rating FROM programs WHERE program_id=".$programId;
		$ratingDetails = $this->_getlist($sql);
		//to avoid the divided by zero error
		if($ratingDetails[0]['program_ratingcount']>0) { 
		   $points	= $ratingDetails[0]['program_rating']/$ratingDetails[0]['program_ratingcount'];
		   $points  = floor($points/2); //Added BY Sujith on Nov 27 2007
		}else {
		   $points = 0;
	    }
	   
	
		$htmlString = '<table width="320" border="0" cellspacing="0" cellpadding="0"><tr>';
		$i=0;
		while($i<$points){
			$htmlString .=	'<td width="17" align="left" valign="middle"><img src="images/programe_listing_05.jpg" width="15" height="15" style="padding:0px;" /></td >';
			$i++;
		}
		//$balance  = 10-$points;	
		$balance 	= 5-$points;	 //Added by Sujith on 27 Nov 2007
		$i=0;
		while($i<$balance){								  
		 $htmlString .=	'<td width="17" align="left" valign="middle"><img src="images/programe_listing_04.jpg" width="15" height="16" style="padding:0px; " /></td>';
		 $i++;
		 }										  
		$htmlString .=	'<td >&nbsp;</td></tr> </table>';
		return $htmlString;									  
  }
  
  //Function - _ipToCountryCode
  //get the ip of the visitor and returns the two digit country code
  
  function _ipToCountryCode($ip) {   
        $numbers = preg_split( "/\./", $ip);   
      //  include("../ip/".$numbers[0].".php");
		//for online 
		include("/var/www/vhosts/default/htdocs/includes/ip/".$numbers[0].".php");
        $code=($numbers[0] * 16777216) + ($numbers[1] * 65536) + ($numbers[2] * 256) + ($numbers[3]);   
        foreach($ranges as $key => $value){
            if($key<=$code){
                if($ranges[$key][0]>=$code){$two_letter_country_code=$ranges[$key][1];break;}
                }
        }
        if($two_letter_country_code==""){$two_letter_country_code="unkown";}
        return $two_letter_country_code;
  }
  //to find out the date difference  
  public function date_difference($datefrom,$dateto){
		
		$datefrom = strtotime($datefrom, 0);
		$dateto   = strtotime($dateto, 0);
		// calc difference in seconds
		$difference = $dateto - $datefrom;
		// reformat to days
		$datediff = floor($difference / 86400);
		// print result
		return $datediff;
	}
	
public function _getUrlKeyword($urlValue){
   
   			 //$urlValue = "http://www.google.com/search?hl=en&q=extract+a+string+using+regular+expressions+in+php&start=10&sa=N";
			 $result=array();
			 //for getting the domain name from url
			 $siteNameArr=substr(strstr($urlValue, "//"), 2);
			 $strPos=strpos($siteNameArr, "/");
			 $siteName=substr($siteNameArr,0,$strPos);
			 $result[0]=$siteName;
			 
			 //for getting the keyword
			 $queryString=substr(strrchr($urlValue, "?"), 1);
			 $queryStringArr=substr(strstr($queryString, "q="), 2);
			 $queryStringPos=strpos($queryStringArr, "&");
			 $keyW=substr($queryStringArr,0,$queryStringPos);
			 $keyWord=explode("+",$keyW);
			 $result[1]=$keyWord;
			 
			 return  $result;
			 	
		}
	// for getting the last visited page details
	public function _lastVistedPage($value){
			
			if(isset($_SERVER['HTTP_REFERER'])) {
				return $_SERVER['HTTP_REFERER'];
				}
				else{
				return $value;
				}
			}	
	
	// for changing the array struct 
	public function _changeArrayStruct($presentArray){
	
		for($i=0;$i<count($presentArray);$i++){
			foreach($presentArray[$i] as $key => $data){
			$return[$key][$i]	=	$data;
			}
		}
		return $return;
	}
	//for search section.
	public function _keyToArraySplit($key){
	
	$resultArray	=	explode(' ', $key);	
	return $resultArray;
	
	}
	
	//chage the date format to m/d/Y
	public function _dateTomdY($date){
	
	$dateTimeArr			=	explode('-',$date);
	$splittedDate			=	$dateTimeArr[1].'/'.$dateTimeArr[2].'/'.$dateTimeArr[0];
	return $splittedDate;
	
	}
	//Add month to particular date
	public function _addMonthToDate($date,$noMonth=0){
		if($noMonth == ''){
		$noMonth	=	0;
		}
			$extendDatetime			=	strtotime($date.' +'.$noMonth.' month');
			$extendDate				=	date('m/d/Y',$extendDatetime);
			return $extendDate;
		
	}
	//Add month to particular date and return y/m/d format
	public function _addMonthToDateYmd($date,$noMonth=0){
		if($noMonth == ''){
		$noMonth	=	0;
		}
			$extendDatetime			=	strtotime($date.' +'.$noMonth.' month');
			$extendDate				=	date('Y-m-d',$extendDatetime);
			return $extendDate;
		
	}
	// for cleaning the search page
	function prepareSearchKeyword($variable)
	{
	  $variable=str_replace('"','',$variable);
	  $variable=str_replace('or','',$variable);
	  $variable=str_replace('OR','',$variable);
	  $variable=str_replace('%','',$variable);
	  $variable=str_replace("'",'',$variable);
	  $variable=str_replace(";",'',$variable);
	  
	  //$variable=addslashes(trim($variable));
	  return $variable;
	}
	// for add perios(day or month or year) to any date value
	function dateAdd($interval, $number, $date)
	{
			
	settype( $timestamp, double );
		$date_time_array = getdate($date);
		$hours = $date_time_array['hours'];
		$minutes = $date_time_array['minutes'];
		$seconds = $date_time_array['seconds'];
		$month = $date_time_array['mon'];
		$day = $date_time_array['mday'];
		$year = $date_time_array['year'];
	
		switch ($interval) {
		
			case 'yyyy':
				$year+=$number;
				break;
			case 'q':
				$year+=($number*3);
				break;
			case 'm':
				$month+=$number;
				break;
			case 'y':
			case 'd':
			case 'w':
				$day+=$number;
				break;
			case 'ww':
				$day+=($number*7);
				break;
			case 'h':
				$hours+=$number;
				break;
			case 'n':
				$minutes+=$number;
				break;
			case 's':
				$seconds+=$number;
				break;            
			}
			//echo $hours."m=".$minutes."=".$seconds.$month.$day.$year;
			   $timestamp= mktime($hours,$minutes,$seconds,$month,$day,$year);
			   
			return $timestamp;
		
		}
	public function getCurrency($string,$lanId)
		{
				if($lang == ' ') 
				$lang = 1; 
				$pattern = "/^[{\"](pp)[(](en:)[.\d]+[;](fr:)[.\d*]+[)]+[}\"]/";
				$string = "{pp(en:2462;fr:5676.75687)} means ghfghgfty ghfghgfhjf ytyty ufjh";
				$bb	=	preg_match($pattern, $string,$val);
				
				//$string =  str_replace('{', '', str_replace('}', '', strstr($string, '{')));
				if($bb == 1)
				{
						$val[0];
						$string1 =  str_replace('(', '', str_replace('}', '',str_replace(')', '', strstr($val[0], '('))));
						$arr		=	explode(";",$string1);
						$en		=	explode(":",$arr[0]);
						$en[1];
						$fr		=	explode(":",$arr[1]);
						$fr[1];
		
				}
		}
	public function getTomeZonePHP($userId)
		{
			if($userId)
				{
					$sql	=	"SELECT t.*,u.user_id FROM `timezone` t  inner join user_master u on 
								t.time_tz=u.user_timezone where user_id ='$userId'";
					$result = $GLOBALS['db']->getAll($sql,DB_FETCHMODE_ASSOC);
					return $result[0]["tz_identifier"];
				}
		}
	
}


?>

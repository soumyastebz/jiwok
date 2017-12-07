<? 

 //for generating the excell sheet for the report
/*
	ini_set('display_errors',1);
	error_reporting(E_ALL|E_STRICT);*/

  
	ini_set("max_execution_time", "0");
    include_once('includeconfig.php');

	include_once('../includes/classes/class.DbAction.php');

    include_once('../includes/classes/class.General.php');
 
	require_once 'Spreadsheet/Excel/Writer.php';//for including the pear class for excell sheet 


    //Setting the language to English if no language is selected

    if($lanId == ""){

        $lanId = 1;

    }   

    $objGen   	    =  new General(); // to fetch the general functions

    $objDb			=  new DbAction();

	
	$extractArray = explode("FROM",base64_decode($_REQUEST['inf']));

	$PostQuerry   = $extractArray[1];

	

	if($PostQuerry != ""){

	

	$sql = "SELECT user_master.user_id,user_fname,user_gender,user_lname,user_alt_email AS user_email,user_address,user_city,user_state,user_country,user_doj AS date,user_dob,user_language	 FROM ".$PostQuerry;

	
	$memberList = $objDb->_getList($sql);
	ob_end_clean();

	$workbook = new Spreadsheet_Excel_Writer();

	$workbook->setVersion(8);



    



    // Creating a worksheet

    $worksheet =& $workbook->addWorksheet('Jiwok Report');



	// The actual data

	

	//for formaing the text that displayed on the sheet 

	$format_bold =& $workbook->addFormat();

    $format_bold->setBold();

	

	//for formating the data in the excell sheet

	

	$format_title =& $workbook->addFormat(array('Size'      => 12,

											     'Align'    => 'Right',

												 'Color'    => 'red',

												 'Pattern'  => 1,

												 'FgColor' => 'white',

												 'Underline'=> 1));

	

	

	

	//for merging the cells in the sheet

	$format_title->setAlign('merge');

	

	$worksheet->write(0, 1, " Member Report  ", $format_title);

	

	$worksheet->write(1,0, 'Index', $format_bold);

	$worksheet->write(1,1, 'Name', $format_bold);

    $worksheet->write(1,2, 'Gender', $format_bold);   

	//$worksheet->write(1,3, 'Email', $format_bold);

	$worksheet->write(1,3, 'Date of join', $format_bold);

	//$worksheet->write(1,5, 'City', $format_bold);

	//$worksheet->write(1,6, 'State', $format_bold);

	$worksheet->write(1,4, 'Country', $format_bold);

	//$worksheet->write(1,8, 'Total Download', $format_bold);

	$worksheet->write(1,5, 'Total Subscription', $format_bold);

	//$worksheet->write(1,10, 'Login Status', $format_bold);

	//$worksheet->write(1,11, 'Last Login', $format_bold);
$worksheet->write(1,6, 'Age', $format_bold);
	
	$worksheet->setInputEncoding('UTF-8');
	//for writing the each cell in the exel sheet 

	if(count($memberList)>0){

	    

		foreach($memberList as $key=>$val){

		if(count($val['user_fname']) > 0){

		$row = $key+2;

		$worksheet->write($row,0, ($row-1));
		
		$name =$val['user_fname']." ".$val['user_lname'];
		//============================
		
        $name1 = mb_convert_encoding($name, 'UTF-8','UTF-8' ) ;

		//==========================
	

		$worksheet->write($row,1, $name1);

		

        if($val['user_gender'] == 0)

            $worksheet->write($row,2,'Male');

        elseif($val['user_gender'] == 1)

            $worksheet->write($row,2,'Female');

        

        

        

      //  $worksheet->write($row,3, $val['user_email']);

		$worksheet->write($row,3, $val['date']);

		//$worksheet->write($row,5, $val['user_city']);

		//$worksheet->write($row,6, $val['user_state']);

		

		//to get the counrtry name curresponding to the id

		$sqlCountry = "SELECT countries_name FROM countries WHERE countries_id = ".$val['user_country'];

		$coutryList = $objDb->_getList($sqlCountry);

		$worksheet->write($row,4, $coutryList[0]['countries_name']);

		

		//to dispaly the total no of dowmloads for a user 

		$sqlDowload = "SELECT count(report_download_id) AS COUNT FROM report_download WHERE user_id = ".$val['user_id'];

		$DownLoad   = $objDb->_getList($sqlDowload);

	//	$worksheet->write($row,8, $DownLoad[0]['COUNT']);

		

		//to dispaly the total no of dowmloads for a user 

		$sqlSub = "SELECT count(DISTINCT(program_id)) AS COUNT FROM programs_subscribed WHERE user_id = ".$val['user_id'];

		$subscribe   = $objDb->_getList($sqlSub);
		
	
		$worksheet->write($row,5, $subscribe[0]['COUNT']);

		

		//for finding out the login status for a user from the member_login page

		$sqlinac = "SELECT login_id ,user_id, DATE_FORMAT(login_date, '%W %D %M %Y') as date_inac FROM member_login WHERE user_id = ".$val['user_id']." AND DATE_ADD(login_date, INTERVAL 60 DAY) > NOW() ORDER BY login_date DESC";

		$inacList = $objDb->_getList($sqlinac);

		

		if(count($inacList)>0)

		  $status = 'Active';

		else

		  $status = 'Inactive';  

		

		//$worksheet->write($row,10, $status);

		//$worksheet->write($row,11, $inacList[0]['date_inac']);
		//------------------------------
		
		$tz  = new DateTimeZone('Asia/Kolkata');
$age = DateTime::createFromFormat('d/m/Y', $val['user_dob'], $tz)
     ->diff(new DateTime('now', $tz))
     ->y;
	 
	 $worksheet->write($row,6, $age );
	 
	 
	 $sql1	=	"SELECT  count(payment_userid)	 AS count, SUM(payment_amount) AS totPay FROM `payment` WHERE  payment_userid = ".$val['user_id']." AND payment_status = 1";
	 $paylist =  $GLOBALS['db']->getRow($sql1, DB_FETCHMODE_ASSOC);
	 if($paylist['count'] > 0)
	 {
		 $pgmrow	=	'Numberofpayments';
		  $worksheet->write(1,7,$pgmrow , $format_bold);
		  $totpay	=	'TotalAmount';
		   $worksheet->write(1,8,$totpay , $format_bold);
		  $numpay	=	 $paylist['count'];
		  $amt		=	$paylist['totPay'];
		  
		   $worksheet->write($row,7, $numpay );
		   $worksheet->write($row,8, $amt );
		   $mysql	=	"SELECT payment_amount FROM `payment` WHERE  payment_userid = ".$val['user_id']." AND payment_status = 1";
		   $myamt	=	$objDb->_getList($mysql);
		   //echo "<pre/>";print_r($myamt);
		 $j=9;
		
		 for($i=0;$i<$paylist['count'];$i++)
		 {
			 
			  $pgmdaysrow	=	'PaymentAmount'.$i;
			 $worksheet->write(1,$j,$pgmdaysrow , $format_bold);
			$myamt1	=$myamt[$i]['payment_amount'] ;
			$worksheet->write($row,$j, $myamt1 );
			 $j++;
		 }
//   echo $val['user_id']."<br/>";
	//  echo  $name1 ."<br/>";
		//  echo "<pre/>";print_r($paylist );
	 }
	
	 
	// echo $val['user_dob'] ."<br/>";
	 //echo  $age ."<br/>";

	    }

		}

	}


//exit;
	$workbook->send('Member_report_jiwok_'.date('d_m_Y').'.xls');

	// Let's send the file

	$workbook->close();

	}

	

?>


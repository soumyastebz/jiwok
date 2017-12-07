<? 

 //for generating the excell sheet for the report

 

  

    include_once('includeconfig.php');

	include_once('../includes/classes/class.DbAction.php');

    include_once('../includes/classes/class.General.php');

	require_once 'Spreadsheet/Excel/Writer.php';//for including the pear class for excell sheet 
	include_once('../includes/classes/class.GiftCodeCampaign.php');

	

	  

    //Setting the language to English if no language is selected

    if($lanId == ""){

        $lanId = 1;

    }   

    $objGen   	    =  new General(); // to fetch the general functions

    $objDb			=  new DbAction();
	$objgiftpay		=	 new GiftCodeCampaign();


	

	$extractArray 		= explode("FROM",base64_decode($_REQUEST['inf']));

	$PostQuerry   		= $extractArray[1];

	$totalconditions	=	base64_decode($_REQUEST['whereSql']);
	$tot_payments		=	$objgiftpay->getTotPayments();
	$listcampaigns		=	$objgiftpay->listCampaigns();
	
	if($PostQuerry != ""){
		
	$sql 			=	 "SELECT UM.user_fname,UM.user_gender,UM.user_lname,UM.user_email,g.paid_status,
						  c.camp_name,c.camp_price,c.camp_discount,c.valid_months AS freeperiod,
						  DATE_FORMAT(p.payment_date,'%W %D %M %Y') AS paiddate,DATE_FORMAT(p.payment_expdate,'%W %D %M %Y') AS expirydate FROM ".$PostQuerry;

	$memberList = $objDb->_getList($sql);

	$workbook = new Spreadsheet_Excel_Writer();

	



    



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

	

	$worksheet->write(0, 1, " Gift Code Pop Up Payment Report  ", $format_title);
	$position = 1;
	if($tot_payments > 0)
	{
		$total_payments	=	$objgiftpay->getTotPayments("",$totalconditions);
		$worksheet->write($position, 0, "Payment details", $format_bold);
		$position++;
		$worksheet->write($position,0,"Total Payments", $format_bold);
		$worksheet->write($position,1,$total_payments);
		$position++;
		
		foreach( $listcampaigns	as  $campid => $listcampaign)
		{
			$worksheet->write($position, 0, "Total Payments( ".$listcampaign['camp_name']. ")", $format_bold);
			$percamp_toPayment = $objgiftpay->getTotPayments($listcampaign['id'],$totalconditions);
			$worksheet->write($position,1,$percamp_toPayment);
			$position++;
			$totalpaymentamount =$objgiftpay->getTotPaymentAmount($listcampaign['id'],$totalconditions);
			$worksheet->write($position, 0, "Total Amount( ".$listcampaign['camp_name']. ")", $format_bold);
			$worksheet->write($position,1,$totalpaymentamount);
			$position++;
			
			
		}
	}
	

	$worksheet->write($position,0, 'Index', $format_bold);

	$worksheet->write($position,1, 'Name', $format_bold);

    $worksheet->write($position,2, 'Gender', $format_bold);   

	$worksheet->write($position,3, 'Email', $format_bold);

	$worksheet->write($position,4, 'Paid Status', $format_bold);

	$worksheet->write($position,5, 'Campaign', $format_bold);

	$worksheet->write($position,6, 'Discount %', $format_bold);

	$worksheet->write($position,7, 'Price', $format_bold);

	$worksheet->write($position,8, 'Valid Period(in months)', $format_bold);

	$worksheet->write($position,9, 'Paid Date', $format_bold);

	$worksheet->write($position,10, 'Expiry Date', $format_bold);

	

	

	//for writing the each cell in the exel sheet 

	if(count($memberList)>0){

	    

		foreach($memberList as $key=>$val){

		if(count($val['user_fname']) > 0){

		$row = $key+$position+1;

		$worksheet->write($row,0, ($key+1));

		$worksheet->write($row,1, $objGen->_output($val['user_fname'])." ".$objGen->_output($val['user_lname']));

		

        if($val['user_gender'] == 0)

            $worksheet->write($row,2,'Male');

        elseif($val['user_gender'] == 1)

            $worksheet->write($row,2,'Female');

        

        

        

        $worksheet->write($row,3, $val['user_email']);

		$worksheet->write($row,4, $val['paid_status']);

		$worksheet->write($row,5, $val['camp_name']);

		$worksheet->write($row,6, $val['camp_discount']);

		$worksheet->write($row,7, $val['camp_price']);

		$worksheet->write($row,8, $val['freeperiod']);

		$worksheet->write($row,9, $val['paiddate']);

		$worksheet->write($row,10, $val['expirydate']);
		


	    }

		}

	}

	

	$workbook->send('Giftcode_Payment_report_jiwok_'.date('d_m_Y').'.xls');

	// Let's send the file

	$workbook->close();

	}

	

?>


<? 
 //for generating the excell sheet for the report
 
  
    include_once('includeconfig.php');
	include_once('../includes/classes/class.DbAction.php');
    include_once('../includes/classes/class.General.php');
	require_once 'Spreadsheet/Excel/Writer.php';//for including the pear class for excell sheet 
	include_once('../includes/classes/class.newpayment.php');
	  
    //Setting the language to English if no language is selected
    if($lanId == ""){
        $lanId = 1;
    }   
    $objGen   	    =  new General(); // to fetch the general functions
    $objDb			=  new DbAction();
	$paymentClass	=	new newPayment();
	$extractArray = explode("FROM",base64_decode($_REQUEST['inf']));
	$PostQuerry   = $extractArray[1];
	
	if($PostQuerry != ""){
	
	//$sql = "SELECT user_id,user_fname,user_lname,count,percent FROM ".$PostQuerry;
	$sql 		= 	base64_decode($_REQUEST['inf']);
	$sqlArray	=	explode("LIMIT", $sql);
	$sql 		=	$sqlArray[0];
	$memberList = $objDb->_getList($sql);
	ob_end_clean();
	$workbook	 = new Spreadsheet_Excel_Writer();
	

    $workbook->setVersion(8); 



    // Creating a worksheet
    $worksheet =& $workbook->addWorksheet('Jiwok Report');
	$worksheet->setInputEncoding('UTF-8');
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
	//For inner contents
	$format_inner =& $workbook->addFormat(array('Align'    => 'Left'));
		
	
	//for merging the cells in the sheet
	$position	=	0; 
	$format_title->setAlign('merge');
	
	$worksheet->write($position, 1, " Payment Report  ", $format_title);
	//-----------
	$position++;
	$tab		=	$_REQUEST[tab];
	$whereSql	=	base64_decode($_REQUEST[whereSql]);	
	if($tab	==	1	&&	count($memberList)>0)
	{
		
		$amountQuery	=	"SELECT sum( PS.payment_amount ) AS Total, PS.payment_currency FROM payment AS PS LEFT 					 							JOIN user_master AS UM ON UM.user_id = PS.payment_userid left join brand_user b on   									  							b.user_id=UM.user_id left join brand_master c on c.brand_master_id=b.brand_master_id
							WHERE PS.payment_status = '1' AND (PS.version = 'stripe' OR PS.version = 'polishstripe') $whereSql GROUP BY 																			 							PS.payment_currency";
		$resultAmount	=	$objDb->_getList($amountQuery);	
		$PlanQuery		=	"SELECT sum( PS.payment_amount ) AS Total,COUNT( PS.payment_amount ) As count,      	 							PS.payment_currency,PS.payment_amount FROM payment AS PS LEFT JOIN user_master AS UM ON 							UM.user_id = PS.payment_userid left join brand_user b on b.user_id=UM.user_id 
							left join brand_master c on c.brand_master_id=b.brand_master_id WHERE PS.payment_status 							= '1' AND (PS.version = 'stripe' OR PS.version = 'polishstripe') $whereSql GROUP BY PS.payment_amount ORDER BY                				`PS`.`payment_currency` DESC";
		$resultPlan	=	$objDb->_getList($PlanQuery);
		$worksheet->write($position, 0, "Payment details", $format_bold);
		$position++;
		$worksheet->write($position,0,"Total Payments");
		$worksheet->write($position,1,count($memberList));
		$position++;
		foreach( $resultAmount	as  $resultAmounts)
		{
			$worksheet->write($position,0,"Total Amount(".$resultAmounts[payment_currency].")",$format_inner);
			$worksheet->write($position,1,round($resultAmounts[Total],2),$format_inner);	
			$position++;
		}
		$worksheet->write($position, 0, "By plan", $format_bold);
		$position++;
		$worksheet->write($position, 0, "Amount", $format_bold);
		$worksheet->write($position, 1, "Total Payments", $format_bold);
		$worksheet->write($position, 2, "Total Amount", $format_bold);
		$position++;
		foreach( $resultPlan	as  $resultPlans)
		{
			$worksheet->write($position,0, $resultPlans[payment_amount]." ".$resultPlans[payment_currency],$format_inner);
			
			$worksheet->write($position,1, $resultPlans[count],$format_inner);
        	$worksheet->write($position,2, round($resultPlans[Total],2)." ".$resultPlans[payment_currency],$format_inner);
			$position++;
		}
		$worksheet->write($position,0, 'Index', $format_bold);
		$worksheet->write($position,1, 'Name', $format_bold);
		$worksheet->write($position,2, 'Email', $format_bold);   
		$worksheet->write($position,3, 'Amount', $format_bold);
		$worksheet->write($position,4, 'Currency', $format_bold);
		$worksheet->write($position,5, 'Payment date', $format_bold);
		$worksheet->write($position,6, 'Exp.date', $format_bold);		
		//for writing the each cell in the exel sheet 
		foreach($memberList as $key=>$val){
		if(count($val['user_fname']) > 0){
		$row = $position+$key+1;
		$worksheet->write($row,0, ($key+1));
		$worksheet->write($row,1, $val['user_fname']." ".$val['user_lname']);
        $worksheet->write($row,2,$val['user_email']);
        $worksheet->write($row,3, $val['payment_amount'],$format_inner);		
		$worksheet->write($row,4, $val['payment_currency']);
		$worksheet->write($row,5, $val['payment_date']);
		$worksheet->write($row,6, $val['payment_expdate']);
		
	    }
		}
	}	
	//-----------
	if(($tab	==	2	||	$tab	==	3)	&&	count($memberList)>0)
	{
		
		if($tab	==	2)
		{
			$amountQuery	=	"SELECT sum( PS.payment_amount ) AS Total, PS.payment_currency FROM   stripe_transaction AS PC INNER JOIN payment AS PS ON PC.payment_id= PS.payment_id 	INNER JOIN user_master AS UM ON UM.user_id = PC.user_id left join brand_user b on  	b.user_id=UM.user_id left join brand_master c on c.brand_master_id = 	b.brand_master_id WHERE PS.payment_status='0' AND (PS.version = 'stripe' OR PS.version = 'polishstripe') AND              					PC.status='CANCELLED' $whereSql GROUP BY PS.payment_currency";					
			$resultAmount	=	$objDb->_getList($amountQuery);	
			$PlanQuery		=	"SELECT sum( PS.payment_amount ) AS Total,COUNT( PS.payment_amount ) As	count, 																										 								PS.payment_currency,PS.payment_amount FROM stripe_transaction AS PC INNER JOIN  							 								payment AS PS ON PC.payment_id = PS.payment_id INNER JOIN user_master AS UM ON  UM.user_id = PC.user_id left join brand_user b on b.user_id=UM.user_id 
								left join brand_master c on c.brand_master_id=b.brand_master_id	WHERE PS.payment_status='0' AND  (PS.version = 'stripe' OR PS.version = 'polishstripe') AND PC.status='CANCELLED'  		 								$whereSql GROUP BY PS.payment_amount ORDER BY `PS`.`payment_currency` DESC";	
			$resultPlan		=	$objDb->_getList($PlanQuery);
			$worksheet->write($position, 0, "Cancel details", $format_bold);
			$position++;
			$worksheet->write($position,0,"Total Cancels");		
			$worksheet->write($position,1,count($memberList));
			$position++;
			foreach( $resultAmount	as  $resultAmounts)
			{
				$worksheet->write($position,0,"Total Amount(".$resultAmounts[payment_currency].")",$format_inner);
				$worksheet->write($position,1,round($resultAmounts[Total],2),$format_inner);	
				$position++;
			}
			$worksheet->write($position, 0, "By plan", $format_bold);
			$position++;
			$worksheet->write($position, 0, "Amount", $format_bold);
			$worksheet->write($position, 1, "Total Cancels", $format_bold);			
			$worksheet->write($position, 2, "Total Amount", $format_bold);
			$position++;
			foreach( $resultPlan	as  $resultPlans)
			{
				$worksheet->write($position,0, $resultPlans[payment_amount]." ".$resultPlans[payment_currency],$format_inner);
				
				$worksheet->write($position,1, $resultPlans[count],$format_inner);
				$worksheet->write($position,2, round($resultPlans[Total],2)." ".$resultPlans[payment_currency],$format_inner);
				$position++;
			}
		}
		else
		{
			$dollarAmount	=	0;
			$euroAmount		=	0;				
			foreach($memberList as $refundVal)
			{
				$RefundDetails		=	$paymentClass->unserializeArray(base64_decode($refundVal['details']));
				$refundAmound		=	$RefundDetails['MONTANT']/100;
				if($RefundDetails['DEVISE'] == '840')
					$dollarAmount	=	$dollarAmount+$refundAmound;
				else
					$euroAmount		=	$euroAmount+$refundAmound;	
			}
			$worksheet->write($position, 0, "Refund details", $format_bold);
			$position++;
			$worksheet->write($position,0,"Total Refunds");		
			$worksheet->write($position,1,count($memberList));
			$position++;
			if($euroAmount	!=	0)
			{
				$worksheet->write($position,0,"Total Amount(Euro)");
				$worksheet->write($position,1,round($euroAmount,2));
				$position++;
			}
			if($dollarAmount	!=	0)
			{
				$worksheet->write($position,0,"Total Amount(Dollar)");
				$worksheet->write($position,1,round($dollarAmount,2));
				$position++;
			}	
		}
		$worksheet->write($position,0, 'Index', $format_bold);
		$worksheet->write($position,1, 'Name', $format_bold);
		$worksheet->write($position,2, 'Email', $format_bold);   
		$worksheet->write($position,3, 'Amount', $format_bold);
		$worksheet->write($position,4, 'Currency', $format_bold);
		$worksheet->write($position,5, 'Date', $format_bold);			
		//for writing the each cell in the exel sheet 
		foreach($memberList as $key=>$val){
			if(count($val['user_fname']) > 0){
				$row = $position+$key+1;
				$worksheet->write($row,0, ($key+1));
				$worksheet->write($row,1, $val['user_fname']." ".$val['user_lname']);
				$worksheet->write($row,2,$val['user_email']);
				
				$payDetails	=	$paymentClass->unserializeArray(base64_decode($val['details']));		
				if($payDetails['DEVISE'] == '840')
				{
					$currency	=	'Dollar';
				}
				else
				{
					$currency	=	'Euro';	
				}
				$worksheet->write($row,3, $payDetails['MONTANT']/100,$format_inner);		
				$worksheet->write($row,4, $currency);
				$worksheet->write($row,5, $val['date']);			
			}
		}
	}	
	if(($tab	==	4	||	$tab	==	5)	&&	count($memberList)>0)
	{			
		$worksheet->write($position,0, 'Index', $format_bold);
		$worksheet->write($position,1, 'Name', $format_bold);
		$worksheet->write($position,2, 'Email', $format_bold);   
		$worksheet->write($position,3, 'Brand', $format_bold);
		$worksheet->write($position,4, 'Status', $format_bold);
		$worksheet->write($position,5, 'Date', $format_bold);		
		//for writing the each cell in the exel sheet 
		foreach($memberList as $key=>$val){
			if(count($val['user_fname']) > 0){
				$row = $position+$key+1;
				$worksheet->write($row,0, ($key+1));
				$worksheet->write($row,1, $val['user_fname']." ".$val['user_lname']);
				$worksheet->write($row,2, $val['user_email']);				
				$worksheet->write($row,3, $val['brand'],$format_inner);		
				$worksheet->write($row,4, $val['status']);
				if($tab	==	4)
					$worksheet->write($row,5, $val['join_date']);
				else
					$worksheet->write($row,5, $val['unsubscribed_date']);				
			}
		}
	}	
	
	$workbook->send('Payment_report_jiwok_'.date('d_m_Y').'.xls');
	// Let's send the file
	$workbook->close();
	}
	
?>

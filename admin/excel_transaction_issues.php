<? 
 //for generating the excell sheet for the report
 
  
    include_once('includeconfig.php');
	ini_set('display_errors', 1);
	error_reporting(E_ERROR | E_WARNING);
	include_once('../includes/classes/class.DbAction.php');
    include_once('../includes/classes/class.General.php');
	require_once 'Spreadsheet/Excel/Writer.php';//for including the pear class for excell sheet 
	
	  
    //Setting the language to English if no language is selected
    if($lanId == ""){
        $lanId = 1;
    }   
    $objGen   	    =  new General(); // to fetch the general functions
    $objDb			=  new DbAction();
	
	//$extractArray = explode("FROM",base64_decode($_REQUEST['inf']));
	//$PostQuerry   = $extractArray[1];
	
	if(isset($_REQUEST['inf'])){
	//$params		= explode('&', $_REQUEST['inf'])
	$whereSql	= base64_decode($_REQUEST['inf']);
	$fromLimit	= (empty($_REQUEST['fromLimit'])) ? 0 : $_REQUEST['fromLimit'];
	$toLimit	= (empty($_REQUEST['toLimit'])) ? 10 : $_REQUEST['toLimit'];
if(!get_magic_quotes_gpc()){
	$whereSql	= addslashes($whereSql);
	$toLimit	= addslashes($toLimit);
	$fromLimit	= addslashes($fromLimit);
}
	$sql 		= "SELECT UM.user_fname, UM.user_lname, P.payment_userid, 
					P.payment_error_code, DATE_FORMAT(P.payment_date, '%D %M %Y') AS payment_date 					FROM payment AS P 
					INNER JOIN user_master AS UM ON UM.user_id = P.payment_userid 
					WHERE P.payment_error_code <> '' AND P.payment_error_code <> '00000' 
					$whereSql
					ORDER BY UM.user_fname ASC, P.payment_date DESC
					LIMIT $fromLimit, $toLimit";
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
	
	$worksheet->write(0, 1, " Transaction Issues Report ", $format_title);
	
	$worksheet->write(1,0, 'Index', $format_bold);
	$worksheet->write(1,1, 'Name', $format_bold);
    $worksheet->write(1,2, 'Error Code', $format_bold);   
	$worksheet->write(1,3, 'Date', $format_bold);
	
	//for writing the each cell in the exel sheet 
	if(is_array($memberList) && count($memberList)>0){
	    
		foreach($memberList as $key=>$val){
		if(count($val['user_fname']) > 0){
		$row = $key+2;
		$worksheet->write($row,0, ($row-1));
		$worksheet->write($row,1, $val['user_fname']." ".$val['user_lname']);
        $worksheet->write($row,2,$val['payment_error_code']);
        $worksheet->write($row,3, $val['payment_date']);
		
	    }
		}
	}
	
	$workbook->send('transaction_issues_report_jiwok_'.date('d_m_Y').'.xls');
	// Let's send the file
	$workbook->close();
	}
	
?>

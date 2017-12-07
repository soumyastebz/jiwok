<? 
 //for generating the excell sheet for the report
 
  
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
	
	//$sql = "SELECT user_id,user_fname,user_lname,count,percent FROM ".$PostQuerry;
	$sql = base64_decode($_REQUEST['inf']);
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
	
	$worksheet->write(0, 1, " Unsubscription Report  ", $format_title);
	
	$worksheet->write(1,0, 'Index', $format_bold);
	$worksheet->write(1,1, 'Name', $format_bold);
    $worksheet->write(1,2, 'Date of Joining', $format_bold);   
	$worksheet->write(1,3, 'Date of Unsubscription', $format_bold);
	
	//for writing the each cell in the exel sheet 
	if(count($memberList)>0){
	    
		foreach($memberList as $key=>$val){
		if(count($val['user_fname']) > 0){
		$row = $key+2;
		$worksheet->write($row,0, ($row-1));
		$worksheet->write($row,1, $val['user_fname']." ".$val['user_lname']);
        $worksheet->write($row,2, $val['user_doj']);
        $worksheet->write($row,3, $val['user_req_unsubscribe']);
		
	    }
		}
	}
	
	$workbook->send('Unsubscription_report_jiwok_'.date('d_m_Y').'.xls');
	// Let's send the file
	$workbook->close();
	}
	
?>

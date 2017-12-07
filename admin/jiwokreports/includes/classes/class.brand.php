<?php error_reporting (E_ALL ^ E_NOTICE);
/**************************************************************************** 

   Project Name	::> Jiwok 

   Module 		::> BRAND MANAGEMENT AND PROGRAM ASSIGNMENT

   Programmer	::> Jasmin N

   Date			::> 10/04/2010

   

   DESCRIPTION::::>>>>

   This is class that can be used to manipulate the Brands and the same is used in brand site for program listing,category listing,sub cat listing and mp3 training and limiting categories/sub categories in jiwok main site.

*****************************************************************************/
include_once("../includes/config.php");
	include_once("../includes/globals.php"); 	
	include_once('../includes/classes/class.General.php');
	include_once('../includes/classes/class.DbAction.php');
class BrandVersion
{
	function selectPrograms()
	{
	  	  $lang	=	2;
	  if($_SESSION["brand"]	==	"parismarathon" || $_SESSION["brand"]	==	"semideparis"){
	  	$lang	=	$_SESSION["language"]["langId"];
	  }
	  $query="SELECT pm.`program_id` , pm.`program_category_flex_id` , pd.program_title
FROM `program_master` pm
LEFT JOIN program_detail pd ON ( pm.program_id = pd.program_master_id )
WHERE pd.language_id = '$lang' and pm.program_status='4'";
	  $result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);
	  return $result;
	} 
	
	function selectSubcategory($lanId='')
	{ 
	  if($lanId!=1) $lanId=2;
	  $query="SELECT distinct sc.* 
FROM `program_master` pm
INNER JOIN sub_category sc ON (( FIND_IN_SET( sc.flex_id, pm.`program_category_flex_id` ) )
AND sc.parent_id != '0'
AND sc.language_id = '$lanId' 
AND pm.program_status='4')
";
	  $result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);
	  return $result;
	}
	function selectCategory($lanId='')
	{
	  if($lanId!=1) $lanId=2;
	  $query="SELECT DISTINCT sc . *
FROM sub_category sc
CROSS JOIN program_master pm ON (( FIND_IN_SET( sc.flex_id, pm.`program_category_flex_id` ) )
AND sc.parent_id =0
AND sc.language_id =$lanId 
AND pm.program_status='4')
UNION SELECT DISTINCT sc. *
FROM sub_category sc
WHERE sc.flex_id
IN (
SELECT DISTINCT sc1.parent_id
FROM `program_master` pm
INNER JOIN sub_category sc1 ON (( FIND_IN_SET( sc1.flex_id, pm.`program_category_flex_id` ) )
AND sc1.parent_id != '0'
AND pm.program_status='4' )
)
AND sc.parent_id = '0'
AND sc.language_id = '$lanId'";
	  $result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);
	  return $result;
	}
	
	/*added by vinitha on 07/05/2010 for limit category */
	
	function limitSubcategory($sublist,$version='')
	{
	  if($version=='eng'){$fieldname='english_status';}else{$fieldname='status';}
	  $query="UPDATE `sub_category` SET `".$fieldname."` = 0  WHERE `parent_id` !='0'";
			$result=$GLOBALS['db']->query($query);
			
			foreach($sublist as $key=>$value){
			 $query="UPDATE `sub_category` SET `".$fieldname."` = 1  WHERE `flex_id` ='$value'";
			$result=$GLOBALS['db']->query($query);
			
			}
			 if($result){return true;} 
			 return false;
	}
	
	function limitCategory($list,$version='')
	{
	if($version=='eng'){$fieldname='english_status';}else{$fieldname='status';}
	  $query="UPDATE `sub_category` SET `".$fieldname."` = 0  WHERE `parent_id` ='0'";
			$result=$GLOBALS['db']->query($query);
			
			foreach($list as $key=>$value){
			 $query="UPDATE `sub_category` SET `".$fieldname."` = 1  WHERE `flex_id` ='$value'";
			$result=$GLOBALS['db']->query($query);
			}
			 if($result){return true;} 
			 return false;
	}
	
	
	
	
	
	/* -------------------------------------------------------*/
	
	
	function selectBrandProgms($bid)
	{
	  $query="SELECT * FROM brand_programs WHERE brand_master_id ='$bid'";
	  $res = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);
	  return $res;
	}
	function selectBrandSubCat($bid)
	{	
	  $query="SELECT * FROM brand_programs WHERE brand_master_id ='$bid'";
	  $res = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);
	  $bidlist=$res['program_id'];
	  $subbidlist=$res['subcat_id'];
	 	  	  $lang	=	2;
	  if($_SESSION["brand"]	==	"parismarathon" || $_SESSION["brand"]	==	"semideparis"){
	  	$lang	=	$_SESSION["language"]["langId"];
	  }
	  $listquery="SELECT DISTINCT sc.flex_id
FROM `program_master` pm
INNER JOIN sub_category sc ON (( FIND_IN_SET( sc.flex_id, pm.`program_category_flex_id` ) )
AND sc.parent_id != '0'
AND sc.language_id = '$lang'
AND pm.program_status='4'
AND pm.program_id
IN ($bidlist)) and sc.flex_id IN ($subbidlist)";
      $result = $GLOBALS['db']->getAll($listquery, DB_FETCHMODE_ASSOC);
	  return $result;
}
	function selectBrandCat($bid)
	{
	  $query="SELECT * FROM brand_programs WHERE brand_master_id ='$bid'";
	  $res = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);
	  $bidlist=$res['program_id'];
	  $catbidlist=$res['cat_id'];
	  	 	  	  $lang	=	2;
	  if($_SESSION["brand"]	==	"parismarathon" || $_SESSION["brand"]	==	"semideparis"){
	  	$lang	=	$_SESSION["language"]["langId"];
	  }
	  $listquery="SELECT DISTINCT sc .* FROM sub_category sc CROSS JOIN program_master pm ON ( ( FIND_IN_SET( sc.flex_id, pm.`program_category_flex_id` ) )  AND sc.parent_id =0 AND sc.language_id =$lang AND pm.program_status='4' AND pm.program_id IN ($bidlist)) and sc.flex_id IN ($catbidlist) UNION SELECT DISTINCT sc. * FROM sub_category sc WHERE sc.flex_id IN ( SELECT DISTINCT sc1.parent_id FROM `program_master` pm INNER JOIN sub_category sc1 ON (( FIND_IN_SET( sc1.flex_id, pm.`program_category_flex_id` ) ) AND sc1.parent_id != '0' AND pm.program_status='4' AND pm.program_id IN ($bidlist)) ) AND sc.parent_id = '0' AND sc.language_id = '$lang' and sc.flex_id IN ($catbidlist)";

      $result = $GLOBALS['db']->getAll($listquery, DB_FETCHMODE_ASSOC);
	  return $result;
	}
	
	function checkCatSelect($cat_id,$bid)
	{
	   $query="select * from brand_programs where brand_master_id ='$bid' and (FIND_IN_SET('$cat_id',cat_id) or FIND_IN_SET('$cat_id',subcat_id))";
	   $result = mysql_query($query)or die(mysql_error());
	   $num=mysql_num_rows($result);
	   return $num;
	}
	///////////function list all brands
	public function _showPage($totalRecs,$i = 0,$no_rec = 0,$field,$type,$searchQuery){
			$fromLimit = $no_rec*($i - 1);
			$toLimit = $no_rec;
			if(trim($searchQuery)!=''){
				$query = "SELECT * FROM brand_master WHERE $searchQuery ORDER BY {$field} {$type} LIMIT {$fromLimit},{$toLimit}";
		    }
			else{
			$query = "SELECT * FROM brand_master ORDER BY {$field} {$type} LIMIT {$fromLimit},{$toLimit}";
			}
			//print $query; 
			$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);
			return $result;
		}
	//////////////function for listing brand name for select boxes
		public function getAllBrandName(){
			$query = "SELECT * FROM brand_master ORDER BY brand_name ASC";
			//print $query; 
			$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);
			return $result;
		}
	//////////////function for getting the total count	
		public function _getTotalCount($searchQuery = '',$lanId){
			$query = "SELECT count(*) as max FROM brand_master";
			if($searchQuery)
				 $query .= " where ".$searchQuery;
				//echo $query;
			$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);
			$totalRecs = $result[0]->max;
			return $totalRecs;
		}
   //////////function check brand name already exists	
	  public function _isResExists($name,&$id=''){
			$query 	= 	"SELECT * FROM brand_master WHERE brand_name ='".$name."'";
			if($id){$query.=" and brand_master_id!='".$id."'";}
			//echo $query;
			$result = mysql_query($query)or die(mysql_error());
			$totalRecs=mysql_num_rows($result);
			return $totalRecs;
		}	
		//////////////////delete brand
		  public function _deleteBrand($id)
		  {
				 $sql= "delete from brand_master where brand_master_id='$id'";
				 $sth = $GLOBALS['db']->query($sql);
				 if(!$sth)
				 {
					 return false;
				 }		
				return true;
		  }
///delete directory
   public function delete_directory($dirname) {
    //echo $dirname;
    if (is_dir($dirname))
       $dir_handle = opendir($dirname);
    if (!$dir_handle)
       return false;
    while($file = readdir($dir_handle)) {
       if ($file != "." && $file != "..") {
          if (!is_dir($dirname."/".$file))
             unlink($dirname."/".$file)or die("cudntdelete file ".$file);
          else
             delete_directory($dirname.'/'.$file);    
       }
    }
   closedir($dir_handle);
    rmdir($dirname)or die("cudntdelete dir ".$dirname);
    return true;
 }
 //////////////insert brand
	public function _insertMaster($insertArray){
			$sql = "INSERT INTO
						brand_master(
							brand_name,
							brand_description,
							brand_url,
							brand_email,  	 
							brand_status ,
							brand_password
						) VALUES (
							?,?,?,?,?,?
						)";
			$sth = $GLOBALS['db']->prepare($sql);
			if(DB::isError($sth)) {
				echo $sth->getMessage();
			}
			$objGen = new General();
			$brand_name	= $objGen->_clean_data($insertArray['brand_name']);
			$brand_description	= $objGen->_clean_data($insertArray['brand_description']);
			$brand_url	= $objGen->_clean_data($insertArray['brand_url']);
			$brand_email	= $objGen->_clean_data($insertArray['brand_email']);
			$brand_status		= $objGen->_clean_data($insertArray['brand_status']);
			$brand_password		= $objGen->_clean_data($insertArray['brand_password']); 
			$data 		= array($brand_name,$brand_description,$brand_url,$brand_email,$brand_status,$brand_password);
			//print_r($data);
			//Insering into reseller table
				$res = $GLOBALS['db']->execute($sth, $data);
			if(DB::isError($res)) {
				echo $res->getDebugInfo();
			}
			return $nextId;
		}	
		
		public function _getAllById(&$id) {

			$sql = "SELECT * from brand_master WHERE brand_master_id = {$id}";
			$res = $GLOBALS['db']->getAll($sql, DB_FETCHMODE_ASSOC);
			if(DB::isError($res)) {
				echo $res->getDebugInfo();
			}
			else{
				$data = array();
				if(!empty($res)) {
					foreach($res as $testmonial){
						$data[]	= $this->_setValues($testmonial);
					}
				}
				if($data != "")
					return $data;
				else 
					return false;
			}
		}
		
		public function _getRowById(&$id) {

			$sql = "SELECT * from brand_master WHERE brand_master_id = {$id}";
			$res = $GLOBALS['db']->getRow($sql, DB_FETCHMODE_ASSOC);
			if(count($res)){return ($res['brand_name']);}
			return false;
		}
       
	   public function _updateResMaster(&$id,$updateArray) {

			$bool = true;

						

			$sql = "UPDATE

						brand_master

					SET

						brand_name = ?,

						brand_description = ?,

						brand_url = ?,

						brand_email = ?,

						brand_status = ?

					WHERE

						brand_master_id = ? ";

			$sth = $GLOBALS['db']->prepare($sql);

			if(DB::isError($sth)) {

				echo $sth->getMessage();

				$bool = false;

			}

			

			$objGen 	= new General();

			$brand_name	= $objGen->_clean_data($updateArray['brand_name']);

			$brand_description	= $objGen->_clean_data($updateArray['brand_description']);

			$brand_url	= $objGen->_clean_data($updateArray['brand_url']);

			$brand_email		= $objGen->_clean_data($updateArray['brand_email']);

			$brand_status		= $objGen->_clean_data($updateArray['brand_status']);

			$data 		= array(

							$brand_name,$brand_description,$brand_url,$brand_email,$brand_status,$id

						  );

			

			$res = $GLOBALS['db']->execute($sth, $data);

			if(DB::isError($res)) {

				echo $res->getDebugInfo();

				$bool = false;

			}

			

			return $bool;

		}

		

	   
		/*

		Function 			: _setValues

		Usage	   			: setting the result array to a more simple format.

		Variable Passing 	: $res is passed as reference.

		*/

		function _setValues(&$res){
			$val = array(
					"brand_master_id" 			=> $res['brand_master_id'],
					"brand_name"				=> $res['brand_name'],
					"brand_description"	=> $res['brand_description'],
					"brand_url"	=> $res['brand_url'],
					"brand_email"	=> $res['brand_email'],
					"brand_status"	=> $res['brand_status'],
					"brand_password"	=> $res['brand_password']
				   );

			return $val;

		}
		function insertPrograms($bid,$proglist,$subcatlist,$catlist)
		{
		   
		   $query="INSERT INTO `brand_programs` ( `brand_master_id` , `program_id`,`cat_id`,`subcat_id` )
VALUES ( '$bid', '$proglist','$catlist','$subcatlist'
)";
		   $result=$GLOBALS['db']->query($query);
		   if($result){return true;} 
		   return false;
		}
		
		function updatePrograms($bid,$proglist,$subcatlist,$catlist)
		{
			$query="UPDATE `brand_programs` SET `program_id` = '$proglist',`cat_id`= '$catlist',`subcat_id`='$subcatlist' WHERE `brand_master_id` ='$bid'";
			$result=$GLOBALS['db']->query($query);
			 if($result){return true;} 
			 return false;
		}
		function isRowProgram($bid)
		{
			$query="SELECT * FROM `brand_programs` WHERE `brand_master_id`='$bid'";
			$querycheck=mysql_query($query);
			$dat=mysql_num_rows($querycheck);
			return($dat);
		}
		function GetBrandName($bname)
		{
			$query="SELECT brand_master_id FROM `brand_master` WHERE `brand_name`='$bname'";
			$res = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);
			$dat=$res['brand_master_id'];
			return($dat);
		}


}

?>
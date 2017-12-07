<?

/************************************************************ 

   Project Name	::> Jiwok 

   Module 	::> Class for Database Action

   Programmer	::> Vijay

   Date		::> 05-02-2007

   

   DESCRIPTION::::>>>>

   This is a Class code used to Manage the database records.



*************************************************************/

class DbAction{

	

	

	public function _insertRecord($tableName,$insertArray){



/*

print "before insert the array is";

						echo "<pre>";

print_r($insertArray);die;*/





		foreach($insertArray as $k=>$v){

				$colname.=$k.",";

				if(strcmp($v,"NOW()") == 0)

					$values.=$v.",";

				else

					$values.="'".$v."',";

			}

			$colname = "(".substr($colname,0,-1).")";

			$values = "(".substr($values,0,-1).")";

			/*print "cols            ";

			print_r($colname);

			echo "<br />";

			print "Values            ";

			print_r($values);

			die;*/

			$query = "INSERT INTO $tableName $colname VALUES $values";

			//echo $query;

			$result =  $GLOBALS['db']->query($query);

			 return $result;



	}

/***** Member Function updateTable. Used for Updating Table ******/

		

		public function _updateRecord($table_name,$elmts,$where=''){

			foreach($elmts as $k=>$v){

				$values.= $k."='".$v."',";

			}

			$values = substr($values,0,-1);

			if($where == ""){

				$query = "UPDATE $table_name SET $values";

			}else{			

				$query = "UPDATE $table_name SET $values WHERE $where";

			}

	       //echo $query."<br>";die;

			$result = $GLOBALS['db']->query($query);

			return $result;

			//return $query;

		}



/****** Member Function execQuery. Used for Executing a Query ******/

		

		public function _execQuery($query){

		

			$result = $GLOBALS['db']->query($query);

			return $result;

		}

		/* to get the array corresponding to a querry

		*/

		function _getList($sql){

		 $result = $GLOBALS['db']->getAll($sql,DB_FETCHMODE_ASSOC);

		 return $result;

		}	

        

		/* 

		

		*/

		function _isExist($sql){

	

		 $result = $GLOBALS['db']->getAll($sql,DB_FETCHMODE_ASSOC);

		 return count($result);

		

		 

		}

		

		//to find out the id

	    //for the file uploading and for the 

	    public function _getId($tableName,$feildName){

			$query = "SELECT MAX(".$feildName.") as max FROM ".$tableName;

			$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);

			$nextId = $result[0]->max+1;

			return $nextId;

		

	    }

		

		public function _showPageClient($totalRecs,$i = 0,$no_rec = 0,$table,$condition=""){

		$fromLimit = $no_rec*($i - 1);

		$toLimit = $no_rec;

		$query = "SELECT * FROM ".$table.$condition." LIMIT $fromLimit,$toLimit";

		$result = $GLOBALS['db']->query($query);

		return $result;

	    } 		

        public function _deleteData($tableName,$condition='') {

			$bool= true;

			//Delete the value from faq_manager.

			$sql = "DELETE FROM ".$tableName." WHERE ".$condition;

			$res = $GLOBALS['db']->query($sql);

			

			if(DB::isError($res)) {

				echo $res->getDebugInfo();

				$bool = false;

			}

			

			return $bool;

		}

		

		public function _insertRecord_New($tableName,$insertArray){



		foreach($insertArray as $k=>$v){

				$colname.=$k.",";

				if(strcmp($v,"NOW()") == 0)

					$values.=$v.",";

				else

					$values.="'".$v."',";

			}

			$colname = "(".substr($colname,0,-1).")";

			$values = "(".substr($values,0,-1).")";

			$query = "INSERT INTO $tableName $colname VALUES $values";

		// print $query;

		 /*$fp = fopen('abc.txt','w+');

		 fwrite($fp,$query);

		 fclose($fp);*/

			$result =  $GLOBALS['db']->query($query);

			//print mysql_insert_id();die;

			return mysql_insert_id();

	

	}

	

}

?>

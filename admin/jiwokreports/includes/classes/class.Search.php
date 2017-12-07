<?php

class Search {

  public function getProgramsFromGoal($goal_id, $goal_language) {

        //$query	    =	"SELECT  goal_programs FROM program_goals WHERE goal_id = ? AND goal_language = ? "; 

		$query			=	"SELECT  program_id FROM goal_program WHERE flex_id = '$goal_id' "; 

        //$result = $GLOBALS['db']->getOne($query, array($goal_id, $goal_language));

        $result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);





        if (PEAR::isError($result)) {



            die($result->getDebugInfo());



        }

		$temp       = array();

        ///////////// CONVERT ARRAY TO COMMA SEPARATED...

        foreach($result as $val){

		   $temp[]  = $val['program_id'];

		}

		$return     = implode(",",$temp);

        ///////////// CONVERT ARRAY TO COMMA SEPARATED...

        return $return;



    }



    public function getProgramsListing($search_fields, $program_flex_ids, $language,$bid='',$goal='',$sport='') {

	 if($language==1){$statusfield="english_status";}else{$statusfield="status";}

       //print_r($search_fields);

	    $program_flex_ids_search	= $program_flex_ids;

		if(!$bid)

		{

		$queryf="select flex_id from sub_category where ".$statusfield."=1";
		$cond_status="and sc.".$statusfield."=1";

		}

		else

		{

				$queryf="select flex_id from sub_category";
				$cond_status="";

		}		

		$res_cat = $GLOBALS['db']->getAll($queryf, DB_FETCHMODE_ASSOC);

		foreach($res_cat as $cat){

					if($cat['flex_id']!=""){

						$catarray .= $cat['flex_id'].",";

					}

		}

		$catarray= substr($catarray, 0, -1);

	

        if ($program_flex_ids != '') {

        	    $program_count = 1 + substr_count($program_flex_ids, ',');

        } else {

            	return array();

        }

		

        $GLOBALS['db']->escapeSimple($program_flex_ids_search);

        $program_flex_ids_search	= str_replace( ',', "', '", $program_flex_ids_search);

        $program_flex_ids_search	= "'".$program_flex_ids_search."'";

        $rythm_query	= "";

     $and="";

	 //  echo $program_flex_ids;

        $order_query	= " ORDER BY  ";

        if ($search_fields['user_gender'] != ''){

            $gender_query   = " AND pw.wizard_sex = ".$search_fields['user_gender'];

            $gender_query_or   = " OR pw.wizard_sex = ".$search_fields['user_gender'];

            $gender_query_1 = " pw.wizard_sex <> ".$search_fields['user_gender']." OR ";

			//$order_query	.= "wizard_sex, ";

        }

		

        if($search_fields['user_rythm'] == 1) {

            $rythm_query	= " AND pm.program_rythm <= 3 ";

            $rythm_query_or	= " OR pm.program_rythm <= 3";

            $rythm_query_1	= " pm.program_rythm > 3 OR ";

			//$order_query	.= "program_rythm, ";

        } elseif ($search_fields['user_rythm'] == 2)  {

            $rythm_query	= " AND pm.program_rythm > 3 ";

            $rythm_query_or	= " OR pm.program_rythm > 3 ";

            $rythm_query_1	= " pm.program_rythm <= 3 OR ";

			//$order_query	.= "program_rythm, ";

        }

		

        if(!empty($search_fields['user_level'])) { 

			// user_level means all levels, so no need to order by any particular level.

			$levels	= $this->getLevels($language='');

			$usrlv=$search_fields['user_level'];

			if($language==''){$lang=2;}

			else{$lang=$language;}

			

			if ($levels[$lang][$usrlv]!='') {

					//$level_order_arr	= $search_fields['user_level'];;

					$levels	= null;

					unset($levels);

					$level_query   = " AND pm.program_level_flex_id = ".$search_fields['user_level'];

					$level_query_or   = " OR pm.program_level_flex_id = ".$search_fields['user_level'];

					$level_query_1 = " pm.program_level_flex_id <> ".$search_fields['user_level']." OR ";

					//$order_query	.= " program_level_flex_id, ";

			}

        }

		

        if(!empty($search_fields['user_age'])) {

            $age_query  = " AND ".$search_fields['user_age']." >= pw.wizard_age_min AND ".$search_fields['user_age']." <= pw.wizard_age_max ";

            $age_query_1    = "( ".$search_fields['user_age']." < pw.wizard_age_min AND ".$search_fields['user_age']." > pw.wizard_age_max ) OR ";

			//$order_query	.= "ABS(wizard_age_min - ".$search_fields['user_age'].") DESC, ABS(wizard_age_max - ".$search_fields['user_age']."), ";

        }

		

        if(!empty($search_fields['user_imc'])){

            $imc_query      = " AND ".$search_fields['user_imc']." >= pw.wizard_imcmin AND ".$search_fields['user_imc']." <= pw.wizard_imcmax ";

            $imc_query_1    = "( ".$search_fields['user_imc']." < pw.wizard_imcmin AND ".$search_fields['user_imc']." > pw.wizard_imcmax ) OR ";

			//$order_query	.= "ABS(wizard_imcmin - ".$search_fields['user_imc'].") DESC, ABS(wizard_imcmax - ".$search_fields['user_imc']."), ";

        }

		

        if (!empty($search_fields['user_fcr'])) {

            $fcr_query      = " AND ".$search_fields['user_fcr']." >= pw.wizard_fcr_min AND ".$search_fields['user_fcr']." <= pw.wizard_fcr_max ";

            $fcr_query_2    = "( ".$search_fields['user_fcr']." < pw.wizard_fcr_min AND ".$search_fields['user_fcr']." > pw.wizard_fcr_max ) OR ";

			//$order_query	.= " ABS(wizard_fcr_min - ".$search_fields['user_fcr'].") DESC, ABS(wizard_fcr_max - ".$search_fields['user_fcr']."), ";

        }

		

        if (!empty($search_fields['user_fcm'])) {

            $fcm_query    = " AND ".$search_fields['user_fcm']." >= pw.wizard_fcm_min  AND ".$search_fields['user_fcm']." <= pw.wizard_fcm_max ";

            $fcm_query_2  = "( ".$search_fields['user_fcm']." < pw.wizard_fcm_min  AND ".$search_fields['user_fcm']." > pw.wizard_fcm_max ) OR ";

			//$order_query	.= " ABS(wizard_fcm_min - ".$search_fields['user_fcm'].") DESC, ABS(wizard_fcm_max - ".$search_fields['user_fcm']."), ";

        }

		

        if (!empty($search_fields['user_vma'])) {

            $vma_query  = " AND ".$search_fields['user_vma']." >= pw.wizard_vma_min AND ".$search_fields['user_vma']." <= pw.wizard_vma_max ";

            $vma_query_1    = "( ".$search_fields['user_vma']." < pw.wizard_vma_min AND ".$search_fields['user_vma']." > pw.wizard_vma_max ) OR ";

			//$order_query	.= " ABS( wizard_vma_min - ".$search_fields['user_vma'].") DESC, ABS(wizard_vma_max - ".$search_fields['user_vma']."), ";

        }

		

		if($goal!=''){

				$goal_qry= "SELECT * FROM goal_program WHERE flex_id ='".$goal."'" ;

				$res_goal =  $GLOBALS['db']->getAll($goal_qry, DB_FETCHMODE_ASSOC); 

				foreach($res_goal as $goal_det){

						if($goal_det['program_id']!=""){

								$goals .= "'".$goal_det['program_id']."',";

						}

				}

				 if($goals!=""){

					 $fgoals=substr_replace($goals ,"",-1);

					 $Goal_Sql="AND  pw.training_flex_id  IN ($fgoals)";

				}

				else{

					$Goal_Sql='';

				}

		}

		else{

				$Goal_Sql='';

		}

		

		if($sport!=''){

		

			$sport_sql="SELECT DISTINCT pm.flex_id FROM program_master pm,sub_category sc WHERE ( pm.program_sport_flex_id  LIKE '%".$sport.",%' OR pm.program_sport_flex_id LIKE '%,".$sport.",%' OR pm.program_sport_flex_id LIKE '%,".$sport."%' ) OR pm.program_sport_flex_id = '".$sport."' AND (FIND_IN_SET(sc.flex_id,pm.program_category_flex_id)) AND sc.".$statusfield."=1" ;

			$res_sport =  $GLOBALS['db']->getAll($sport_sql, DB_FETCHMODE_ASSOC); 

			foreach($res_sport as $sport_det){

				if($sport_det['flex_id']!=""){

						$sports .= "'".$sport_det['flex_id']."',";

				}	

			}

			if($sports!=""){

					 $fsports=substr_replace($sports ,"",-1);

					 $Sport_Sql="AND  pw.training_flex_id IN ($fsports)";

			}

			else{

					$Sport_Sql='';

			}

		}

		else{

					$Sport_Sql='';

		}

		

		if($bid){

					$query_brd="SELECT program_id FROM brand_programs WHERE brand_master_id ='$bid'";

					$res = $GLOBALS['db']->getRow($query_brd, DB_FETCHMODE_ASSOC);

					$bidlist=$res['program_id'];

					$brnd_qry=" AND pm.program_id IN ($bidlist)"; 

		}

		

        $query_0        = '';

        //$query_0        .= $gender_query.$age_query.$imc_query.$fcr_query.$fcm_query.$vma_query;



        $query_1        = '';

        //$query_1        .= $gender_query_1.$age_query_1.$imc_query_1.$fcr_query_1.$fcm_query_1.$vma_query_1;



        if($query_1!='') {

            $query_1    = substr($query_1, 0, -3);

            $query_1    = 'AND ('.$query_1.')';

        }

        if ($order_query == " ORDER BY  ") {

            $order_query    = '';

        } 

		else {

            $order_query    = substr($order_query, 0, -2);

        }

		 $query="(SELECT  distinct(pm.program_id) as program_master_id, pm.flex_id FROM program_wizard pw INNER JOIN program_master pm ON (pm.flex_id = pw.training_flex_id) right join sub_category sc on (FIND_IN_SET(sc.flex_id,pm.program_category_flex_id) ".$cond_status.") WHERE  pw.training_flex_id IN ($program_flex_ids_search) and pm.program_status=4   {$brnd_qry} {$Goal_Sql}{$Sport_Sql} {$rythm_query} {$level_query} {$order_query} LIMIT {$program_count}) ";

		/*if($search_fields['user_level']!='' && $search_fields['user_rythm']!=0 && $goal!=''){*/

			

			$sqlQry=substr($query, 1);  							 

			$sqlQry=substr($sqlQry,0, -2);  	

			$res_sqlQry = mysql_query($sqlQry);

			$numrow1 = mysql_num_rows($res_sqlQry);

		/*}*/

		

       /*if($query_1!='') {

	      	 $query1          =" UNION (SELECT  program_id, flex_id FROM program_wizard INNER JOIN program_master ON flex_id = training_flex_id WHERE training_flex_id IN ($program_flex_ids_search)  AND program_category_flex_id IN($queryf) {$query_1} {$brnd_qry} {$Goal_Sql} {$Sport_Sql} {$rythm_query_1} {$level_query} {$order_query} LIMIT {$program_count} )";

	   }*/

	   

	    $flag=0;

		for($i=0;$i<5;$i++){

		$numrow = mysql_num_rows($res_sqlQry);

		if($numrow == 0){

				 switch($i){

						case '0': 

						//echo "f";

						$flag=1;

						//$rythm_query=$rythm_query_or;

						$query_0        = '';

						//$query_0        .= $gender_query.$level_query.$age_query.$imc_query.$fcr_query.$fcm_query.$vma_query;

						$query	 =	"(SELECT  distinct(pm.program_id) as program_master_id, pm.flex_id FROM program_wizard pw INNER JOIN program_master pm ON (pm.flex_id = pw.training_flex_id) right join sub_category sc on(FIND_IN_SET(sc.flex_id,pm.program_category_flex_id) ".$cond_status.")  WHERE pw.training_flex_id IN ($program_flex_ids_search) and pm.program_status=4 {$brnd_qry}  {$Goal_Sql} {$Sport_Sql} {$level_query} {$order_query} LIMIT {$program_count}) ";

						$sqlQry=substr($query, 1);  							 

						$sqlQry=substr($sqlQry,0, -2);  	

						$res_sqlQry = mysql_query($sqlQry);

						//echo $query;

						break;

					

					case '1': 

						//echo "s";

						//echo $i;

						$flag=1;

						//$level_query=$level_query_or;

						$query_0        = '';

						//$query_0        .= $gender_query.$rythm_query.$age_query.$imc_query.$fcr_query.$fcm_query.$vma_query;

			

						$query	 =	"(SELECT  distinct(pm.program_id) as program_master_id, pm.flex_id FROM program_wizard pw INNER JOIN program_master pm ON (pm.flex_id = pw.training_flex_id) right join sub_category as sc on(FIND_IN_SET(sc.flex_id,pm.program_category_flex_id) ".$cond_status.") WHERE pw.training_flex_id IN ($program_flex_ids_search) and pm.program_status=4 {$brnd_qry} {$Goal_Sql} {$Sport_Sql} {$rythm_query} {$order_query} LIMIT {$program_count}) ";

						$sqlQry=substr($query, 1);  							 

						$sqlQry=substr($sqlQry,0, -2);  	

						$res_sqlQry = mysql_query($sqlQry);

						//echo $query;

						break;

				 

					case '2': 

						//echo "t";

						 //echo $i;

						$flag=1;

						//$level_query=$level_query_or;

						$query_0        = '';

						//$query_0        .= $gender_query.$rythm_query.$level_query.$age_query.$imc_query.$fcr_query.$fcm_query.$vma_query;

			

						$query	 =	"(SELECT  distinct(pm.program_id) as program_master_id, pm.flex_id FROM program_wizard pw INNER JOIN program_master pm ON (pm.flex_id = pw.training_flex_id) right join sub_category as sc on(FIND_IN_SET(sc.flex_id,pm.program_category_flex_id) ".$cond_status.") WHERE pw.training_flex_id IN ($program_flex_ids_search) and pm.program_status=4 {$brnd_qry} {$Goal_Sql} {$rythm_query} {$level_query} {$order_query} LIMIT {$program_count}) ";

						$sqlQry=substr($query, 1);  							 

						$sqlQry=substr($sqlQry,0, -2);  	

						$res_sqlQry = mysql_query($sqlQry);

						//echo $query;

						break;


					case '3': 

						//echo "fth";

						//echo $i;

						$flag=1;

						//$level_query=$level_query_or;

						$query_0        = '';

						//$query_0        .= $gender_query.$age_query.$imc_query.$fcr_query.$fcm_query.$vma_query;

			

						$query	 =	"(SELECT  distinct(pm.program_id) as program_master_id, pm.flex_id FROM program_wizard pw INNER JOIN program_master pm ON (pm.flex_id = pw.training_flex_id) right join sub_category as sc on(FIND_IN_SET(sc.flex_id,pm.program_category_flex_id) ".$cond_status.") WHERE pw.training_flex_id IN ($program_flex_ids_search) and pm.program_status=4 {$brnd_qry} {$Sport_Sql} {$rythm_query} {$level_query} {$order_query} LIMIT {$program_count}) ";							 

						$sqlQry=substr($sqlQry,0, -2);  	

						$res_sqlQry = mysql_query($sqlQry);

						//echo $query;

						break;

					/*case '4': 

						//echo "fith";

						// echo $i;

						$flag=1;

						//$level_query=$level_query_or;

						$query_0        = '';

						$query_0        .= $gender_query.$rythm_query.$age_query.$imc_query.$fcr_query.$fcm_query.$vma_query;

			

						$query	 =	"(SELECT  program_id as program_master_id, flex_id FROM program_wizard INNER JOIN program_master ON flex_id = training_flex_id WHERE training_flex_id IN ($program_flex_ids_search)  AND program_category_flex_id IN($queryf) {$query_0} {$brnd_qry} {$Sport_Sql} {$order_query} LIMIT {$program_count}) ";

						$sqlQry=substr($query, 1);  							 

						$sqlQry=substr($sqlQry,0, -2);  	

						$res_sqlQry = mysql_query($sqlQry);

						//echo $query;

						break;

					case '5': 

						//echo "sth";

						// echo $i;

						$flag=1;

						//$level_query=$level_query_or;

						$query_0        = '';

						$query_0        .= $gender_query.$level_query.$age_query.$imc_query.$fcr_query.$fcm_query.$vma_query;

			

						$query	 =	"(SELECT  program_id as program_master_id, flex_id FROM program_wizard INNER JOIN program_master ON flex_id = training_flex_id WHERE training_flex_id IN ($program_flex_ids_search)  AND program_category_flex_id IN($queryf) {$query_0} {$brnd_qry} {$Sport_Sql} {$order_query} LIMIT {$program_count}) ";

						$sqlQry=substr($query, 1);  							 

						$sqlQry=substr($sqlQry,0, -2);  	

						$res_sqlQry = mysql_query($sqlQry);

						//echo $query;

						break;

					case '6': 

						//echo "svth";

						// echo $i;

						$flag=1;

						//$level_query=$level_query_or;

						$query_0        = '';

						$query_0        .= $gender_query.$age_query.$imc_query.$fcr_query.$fcm_query.$vma_query;

			

						$query	 =	"(SELECT  program_id as program_master_id, flex_id FROM program_wizard INNER JOIN program_master ON flex_id = training_flex_id WHERE training_flex_id IN ($program_flex_ids_search)  AND program_category_flex_id IN($queryf) {$query_0} {$brnd_qry} {$Goal_Sql}{$Sport_Sql} {$order_query} LIMIT {$program_count}) ";

						$sqlQry=substr($query, 1);  							 

						$sqlQry=substr($sqlQry,0, -2);  	

						$res_sqlQry = mysql_query($sqlQry);

						//echo $query;

						break;*/

				 

				  default:	

						//echo "d";

        				//$query_0       = $gender_query.$rythm_query.$level_query.$age_query.$imc_query.$fcr_query.$fcm_query.$vma_query;              

						// avoided rythem query and level query.

						$query			=	"(SELECT  distinct(pm.program_id) as program_master_id, pm.flex_id FROM program_wizard pw INNER JOIN program_master pm ON (pm.flex_id = pw.training_flex_id) right join sub_category as sc on(FIND_IN_SET(sc.flex_id,pm.program_category_flex_id) ".$cond_status.") WHERE  pw.training_flex_id IN ($program_flex_ids_search) and pm.program_status=4 {$query_0} {$brnd_qry} {$Goal_Sql}{$Sport_Sql} {$order_query} LIMIT {$program_count}) ";

						$flag=1;

						//$query=$query. $query1;

						//echo $query;

						break;						

			}

	 }

  } 



	//echo $query;

	$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);



		/*var_dump($result);

	    exit;*/

        if (PEAR::isError($result)) {

            die($result->getDebugInfo());

        }

		

		array_push($result,$flag);

		return $result;

}





public function getAllPgmsBySports($sportflexid){


				

			$sport_sql="SELECT flex_id FROM program_master WHERE  program_master.program_sport_flex_id  LIKE '%".$sportflexid."%' " ;

			$result = $GLOBALS['db']->getAll($sport_sql, DB_FETCHMODE_ASSOC);



 if (PEAR::isError($result)) {



            die($result->getDebugInfo());



        }

		$temp       = array();

        ///////////// CONVERT ARRAY TO COMMA SEPARATED...

        foreach($result as $val){

		   $temp[]  = $val['flex_id'];

		}

			$return     = implode(",",$temp);

        ///////////// CONVERT ARRAY TO COMMA SEPARATED...

        return $return;





}





public function getWizardLevels($language_id)

{

		$brand_name = "default";

		

  		$result = $this->getWizardLevelsForBrand($brand_name,$language_id);



        return $result;



}

	

	public function getWizardLevelsForBrand($brand_name,$lanId)

{

	$brandId 			= $this->_getBrandId($brand_name);



		$select_query	= "SELECT brand_level_id FROM brand_levels WHERE brand_id ='".$brandId['brand_id']."'";



        $result = $GLOBALS['db']->getAll($select_query,  DB_FETCHMODE_ASSOC);



        if (PEAR::isError($result)) {



            die($result->getDebugInfo());



        }

		

		for($i=0;$i<count($result);$i++)

		{

			$select_query	= "SELECT flex_id,item_name FROM general WHERE flex_id = '".$result[$i]['brand_level_id']."' AND language_id =".$lanId;

			$select_query.=" AND `table_name` = 'level' ";

			$res_level = $GLOBALS['db']->getRow($select_query, DB_FETCHMODE_ASSOC);

			if($res_level)

			{

				$levels[$i]['item_name']=$res_level['item_name'];

				$levels[$i]['flex_id']=$res_level['flex_id'];

			}

		}

        return $levels;

}



public function getWizardSports($language_id)

{

		$brand_name = "default";

		

  		$result = $this->getWizardSportsForBrand($brand_name,$language_id);



        return $result;

}

	

	public function getWizardSportsForBrand($brand_name,$lanId)

{

	$brandId 			= $this->_getBrandId($brand_name);



		$select_query	= "SELECT * FROM brand_sports WHERE brand_id ='".$brandId['brand_id']."'";



        $result = $GLOBALS['db']->getAll($select_query,  DB_FETCHMODE_ASSOC);



        if (PEAR::isError($result)) {



            die($result->getDebugInfo());



        }

		

		for($i=0;$i<count($result);$i++)

		{

			$select_query	= "SELECT flex_id,item_name FROM general WHERE flex_id = '".$result[$i]['brand_sport_id']."' AND language_id =".$lanId;

			$select_query.=" AND `table_name` = 'sports' ";

			$res_sport = $GLOBALS['db']->getRow($select_query, DB_FETCHMODE_ASSOC);

			if($res_sport)

			{

				$sports[$i]['item_name']=$res_sport['item_name'];

				$sports[$i]['flex_id']=$res_sport['flex_id'];

			}

		}



        return $sports;

  

}







    public function getWizardGoals($language_id) {



        //$select_query	= "SELECT goal_id, goal_text FROM program_goals WHERE goal_language = ? ";

		//$select_query	= "SELECT flex_id, item_name FROM general WHERE table_name = 'goals' AND language_id = ? ";

       // $result = $GLOBALS['db']->getAll($select_query, array($language_id), DB_FETCHMODE_ASSOC);

      //  if (PEAR::isError($result)) {

      //      die($result->getDebugInfo());

     //   }

		$brand_name = "default";

		

  		$result = $this->getWizardGoalsForBrand($brand_name,$language_id);



        return $result;



    }

	

	

	 public function getWizardGoalsForBrand($brand_name,$lanId) {

	 

	$brandId 			= $this->_getBrandId($brand_name);



		$select_query	= "SELECT brand_goal_id FROM brand_goals WHERE brand_id ='".$brandId['brand_id']."'";



        $result = $GLOBALS['db']->getAll($select_query,  DB_FETCHMODE_ASSOC);



        if (PEAR::isError($result)) {



            die($result->getDebugInfo());



        }

		

		for($i=0;$i<count($result);$i++)

		{

			$select_query	= "SELECT flex_id,item_name FROM general WHERE flex_id = '".$result[$i]['brand_goal_id']."' AND language_id =".$lanId;

			$res_goal = $GLOBALS['db']->getRow($select_query, DB_FETCHMODE_ASSOC);

			if($res_goal)

			{

				$goals[$i]['item_name']=$res_goal['item_name'];

				$goals[$i]['flex_id']=$res_goal['flex_id'];

			}

		}



        return $goals;



    }

	

			public function _getBrandId($brand_name)

		{

			$sql = "SELECT brand_master_id FROM brand_master WHERE brand_name= '{$brand_name}'";

			$res = $GLOBALS['db']->getRow($sql, DB_FETCHMODE_ASSOC);

			

			if(DB::isError($res)) {

				echo $res->getDebugInfo();

			}

			else{

				if(!empty($res)) {

				 // print_r($res);

					$data = $this->_setBrandId($res);

				}

			}

			if($data != "")

					return $data;

			else 

					return false;

		}

		

		

		

		public function _setBrandId(&$res){

		$val = array(

					"brand_id" 			=> $res['brand_master_id']

					);

					return $val;

		}





    



    public function getAllPrograms($lanId,$bnd='') {



         if($lanId==1){$statusfield="english_status";}else{$statusfield="status";}

		if(!$bnd)

		{

			$cond	=	"  where sub_category.".$statusfield."=1";

		}

		else

		{

			$cond	= "";

		}



		$select_query	= "SELECT distinct (program_master.flex_id) FROM program_master right join sub_category on  (FIND_IN_SET(sub_category.flex_id,program_master.program_category_flex_id))  ".$cond;

		



        $result = $GLOBALS['db']->getAll($select_query, DB_FETCHMODE_ASSOC);



        if (PEAR::isError($result)) {



            die($result->getDebugInfo());



        }



        



        return $result;



    }



	



	public function getLevels ($language_id = '') {



	$levels	= array (



			1	=> array (



						2 => "jiwok_level2", 



						3 => "jiwok_level3", 



						4 => "jiwok_level4", 



						5 => "jiwok_level5", 



					  ),



			2	=> array (



						2 => "jiwok_level2", 



						3 => "jiwok_level3", 



						4 => "jiwok_level4", 



						5 => "jiwok_level5", 



					  )



		);



		/*$levels	= array (



			1	=> array (



						2 => "I've never done sports", 



						3 => "I do more sport long", 



						4 => "I do sports from time to time", 



						5 => "I do sports regularly", 



						7 => "I do sports intensely"



					  ),



			2	=> array (



						2 => "Je n'ai jamais fait de sport", 



						3 => "Je ne fais plus de sport depuis longtemps", 



						4 => "Je fais du sport de temps en temps", 



						5 => "Je fais du sport régulièrement", 



						7 => "Je fait du sport intensément"



					  )



		);*/



		



		if (isset($levels[$language_id])) {



			return $levels[$language_id];



		}



		return $levels;



	}



	



	public function getWizardRythms($language_id = '') {



		$rythms	= array(



			1=>array(



					1=>"I am available from 1 to 3 times a week",



					2=>"I am available from 4 to 7 times per week"),



			2=>array(



					1=>"Je suis disponible de 1 à 3 fois par semaine",



					2=>"Je suis disponible de 4 à 7 fois par semaine")



			);



		if (isset($rythms[$language_id])) {



			return $rythms[$language_id];



		}



		



		return $rythms;



	}



	



	public function _utf8encode (&$element) {



		$element	= utf8_encode($element);



	}

	function GetBrandName($bname)

		{

			$query="SELECT brand_master_id FROM `brand_master` WHERE `brand_name`='$bname'";

			$res = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);

			$dat=$res['brand_master_id'];

			return($dat);

		}

	/*function getAllSportSearch_list($lanId,$sportListId=''){

		$sql="SELECT * FROM `general` WHERE `table_name` = 'sports' AND `language_id` ='".$lanId."'";

		if($sportListId){$sql.=" AND  flex_id IN(".$sportListId.")";}

			$result =  $GLOBALS['db']->getAll($sql, DB_FETCHMODE_ASSOC); 

			return $result;



	}*/

	///////////

	function getAllSportSearch_list($lanId,$sportListId=''){

		$sql="SELECT * FROM `general` WHERE `table_name` = 'sports' AND `language_id` ='".$lanId."'";

		if($sportListId){$sql.=" AND  flex_id IN(".$sportListId.")";}

			$result =  $GLOBALS['db']->getAll($sql, DB_FETCHMODE_ASSOC); 

			return $result;



	}

	function GetSportLsitValues($prgmId){

		$sql="SELECT `program_sport_flex_id` FROM `program_master` WHERE `program_id` =".$prgmId;

		$result =  $GLOBALS['db']->getRow($sql, DB_FETCHMODE_ASSOC); 

		$dat = $result['program_sport_flex_id'];

		return $dat;

	}

	function GetGoalFromFlex($FlexofPrgmMaster){

		$sql="SELECT flex_id FROM `goal_program` WHERE `program_id` = '".$FlexofPrgmMaster."'";

		$result =  $GLOBALS['db']->getAll($sql, DB_FETCHMODE_ASSOC); 

		foreach($result as $resdet){

			$dat .= $resdet['flex_id'].",";

		}

		return $dat;



	}

	function getAllGoalSearch_list($lanId,$GoalListId){

		$select_query	= "SELECT flex_id, item_name FROM general WHERE table_name = 'goals' AND language_id =".$lanId." AND flex_id IN (".$GoalListId.")";

		$result =  $GLOBALS['db']->getAll($select_query, DB_FETCHMODE_ASSOC); 

		return $result;



	}

}




<?php
/**************************************************************************** 

   Project Name	::> Jiwok 

   Module 		::>  gift code Manipulation

   Programmer	::> Jasmin N

   Date			::> 9/12/2009

   DESCRIPTION::::>>>>

   This is class that can be used to manipulate the Gift Code section from the admin side and also for client section.

*****************************************************************************/

class gift

{

		  public function _insertgift($period,$amt,$gccode)

		  {

			$bool = true;

					$sql = "INSERT INTO `gift_code` ( `id` , `code` , `codetype` , `codestatus` , `codeamount` )

			VALUES (

			'', '$gccode', '$period', 'unused', '$amt'

			)";

					$sth = $GLOBALS['db']->query($sql);

					if(DB::isError($sth)) {

						echo $sth->getMessage();

						$bool = false;

		  }

		  return $bool;

		  }

		  //////////////admin insert

		   public function _insertgiftadmin($period,$amt,$gccode)

		  {

			$bool = true;

					$sql = "INSERT INTO `gift_code` ( `id` , `code` , `codetype` , `codestatus` , `codeamount` , `gen_date` )

			VALUES (

			'', '$gccode', '$period', 'unused', '$amt',CURDATE()

			)";

					$sth = $GLOBALS['db']->query($sql);

					if(DB::isError($sth)) {

						echo $sth->getMessage();

						$bool = false;

		  }

		  return $bool;

		  }

		  //////////////////for making the code status purchased(admin distributiion)

		  public function _makepurchased($code)

		  {

		  		$bool=false;

				$qry = mysql_query("SELECT * from gift_code where code='$code' and codestatus='unused'")or die(mysql_error());

				if(mysql_num_rows($qry)>0)

				{

				$query1="INSERT INTO gift_member (purchaseid,firstname,lastname,email,code,status,friendname,friendemail) VALUES ('','admin','admin','admin@jiwok.com','$code','1','','')";

		        $res1	= $GLOBALS['db']->query($query1);

				$query3="select max(purchaseid) as max from gift_member where code='$code'";

		       $purchase_row	= $GLOBALS['db']->getRow($query3,DB_FETCHMODE_ASSOC);

		       $purchase_id=$purchase_row['max'];

				$query2="UPDATE `gift_code` SET `codestatus` = 'purchased' WHERE code='$code'";

				$res2	= $GLOBALS['db']->query($query2);

				$query3="insert into gift_userdetails (id,code,purchaseid,purchasedate,purchase_currency) values('','$code','$purchase_id',CURDATE(),'Euro')";

				$res3	= $GLOBALS['db']->query($query3);

				$bool=true;

				}

				return $bool;

		  }

		  

		  public function _deletegift($code)

		  {

		        $sql= "delete  from gift_code where id='$code'";

				$sth = $GLOBALS['db']->query($sql);

		  }

         

		 ////////////////gift code check

		 public function _getchecked($code)

		{

			$query = "SELECT id from gift_code where code='$code'";

			//echo "mnmbcvjbhfubhvuhb";

			$qry = mysql_query($query)or die(mysql_error());

			$num = mysql_num_rows($qry)or die(mysql_error());

			//echo $num; 

			return $num;

		}

		

		

		 public function new1($code)

		 {

		    $qry = mysql_query("SELECT * from gift_code where code='$code'")or die(mysql_error());

			//echo "testing".mysql_num_rows($qry)."hhhu";

			return mysql_num_rows($qry);

		 }

		 

		 public function _isExistsGift($code)

		 {

		 $code=trim($code);

		 $query = "SELECT * from gift_code where code='$code' and codestatus='purchased'";

		$qry = mysql_query($query)or die(mysql_error());

		$num = mysql_num_rows($qry);

			return $num;

		 }

		 

		 public function _getdetailsgift($code)

		 {$code=trim($code);

		 	

			$query = "SELECT * from gift_code where code='$code' and codestatus='purchased'";

			$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);

			return $result;

		 }

  /////////////////////gift code payment list

  

		public function _getTotalCount($statustype,$period)

		{
            if($period=='0'){$pd=0;}
			else{
			$pd=$period." months";
			}
			
			if($statustype=='0')

			{

			  if($pd==0)

			  {

			      $condition=" where t1.codestatus!='unused'";

			  }

			  else

			  {

			  $condition=" where t1.codestatus!='unused' and t1.codetype='$pd'";

			  }

			}

			else

			{

			  if($pd==0)

			  {

			 $condition=" where t1.codestatus='$statustype'";

			 }

			 else

			 {

			 $condition=" where t1.codestatus='$statustype' and t1.codetype='$pd'";

			 }

			}

			$query="SELECT t1.code, t1.codeamount, t3.email, t2.purchasedate FROM gift_code t1

LEFT JOIN (gift_userdetails t2 LEFT JOIN gift_member t3 ON ( t3.purchaseid = t2.purchaseid )) ON ( t1.code = t2.code )";

			 $query=$query.$condition;

			//echo $query;

			$qry = mysql_query($query)or die(mysql_error());

			$totalRecs = mysql_num_rows($qry);

			return $totalRecs;

		}

		

		

		

		public function _showPage($statustype,$period,$totalRecs,$i = 0,$no_rec = 0)

		{

			

			$fromLimit = $no_rec*($i - 1);

			$toLimit = $no_rec;

			
			if($period=='0'){$pd=0;}
			else{
			$pd=$period." months";
			}
			

			$order=" order by t2.purchasedate LIMIT {$fromLimit},{$toLimit}";

			if($statustype=='0')

			{

			  if($pd==0)

			  {

			      $condition=" where t1.codestatus!='unused'";

			  }

			  else

			  {

			  $condition=" where t1.codestatus!='unused' and t1.codetype='$pd'";

			  }

			}

			else

			{

			  if($pd==0)

			  {

			 $condition=" where t1.codestatus='$statustype'";

			 }

			 else

			 {

			 $condition=" where t1.codestatus='$statustype' and t1.codetype='$pd'";

			 }

			}

			$query="SELECT t1.code, t1.codetype,t1.codestatus, t1.codeamount, t3.email, t2.purchasedate, t2.purchase_currency FROM gift_code t1

LEFT JOIN (gift_userdetails t2 LEFT JOIN gift_member t3 ON ( t3.purchaseid = t2.purchaseid )) ON ( t1.code = t2.code )";

			 $query=$query.$condition.$order;

			$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);

			return $result;

		}

		

		

		

		//////////////////////////code list

        public function _getTotalAmount($statustype,$period)
		{
			if($period=='0'){$pd=0;}
			else{
			$pd=$period." months";
			}
			
			   $query="SELECT sum(gc.codeamount) as amt,gu.purchase_currency
FROM gift_code gc
inner JOIN gift_userdetails gu ON ( gc.code = gu.code ) where gu.code not in(SELECT code from gift_member where email='admin@jiwok.com' and firstname='admin' and lastname='admin')";
               if($statustype=='0' || $statustype=='unused')
				{
	               if($pd==0){
	               $statustype='paid';
				   $qry=$query." and gc.codestatus!='unused'";
	                }
				   else
				   {
				   $statustype='paid';
				   $qry=$query." and gc.codestatus!='unused' and gc.codetype='$pd'";
				   }
				}
			   else
				{
				   if($pd==0)
				   {
				   $qry=$query." and gc.codestatus='$statustype'";
				   }
				   else
				   {
				   $qry=$query." and gc.codestatus='$statustype' and gc.codetype='$pd'";
				   }
				}
				$returnResult="Total Amount (".$statustype."): "; 
				 $qry1=$qry." GROUP BY gu.purchase_currency";
				$query1 = mysql_query($qry1)or die(mysql_error());
				while($result=mysql_fetch_array($query1))
				{
				  $returnResult.=$result['amt']."<span style='padding-right:0px;'> ".$result['purchase_currency']."</span><br>";
				}
				return $returnResult;
		}		

		
		public function _getTotalCountCodeList($statustype,$period,$searchQuery,$searchdate)
            {  
			$flag=0;
            if($period=='0'){$pd=0;}
			else{
			$pd=$period." months";
			}
			

			if($statustype=='0')

			{

			  if($pd==0)

			  {

			     $flag=1;

				  $condiiton="";

			  }

			  else

			  {

			     $condiiton=" where t1.codetype='$pd'";

			  }

			}

			else

			{

			   if($pd==0)

			   {

			    $condiiton=" where t1.codestatus='$statustype'";

			   }

			   else

			   {

			    $condiiton=" where t1.codestatus='$statustype' and t1.codetype='$pd'";

			   }

			}

			  $query="SELECT t1.code, t1.codetype, t1.codeamount, t3.email, t2.purchasedate, t2.usedate, t4.user_email FROM gift_code t1

LEFT JOIN (gift_userdetails t2 LEFT JOIN gift_member t3 ON ( t3.purchaseid = t2.purchaseid ) LEFT JOIN user_master t4 ON ( t4.user_id = t2.user_id )) ON ( t1.code = t2.code )".$condiiton;

              if($searchQuery)

			  { $flag2=1;

				if($flag==1)

				{

				 $query .= " where ".$searchQuery;

				 }

				 else

				 {

				      $query .= " and ".$searchQuery;

				 }

			  }

			  

			   if($searchdate)

			  {

				      if($flag2==1)

					  $query .= " and ".$searchdate;

					  else {if($flag==1)

							  $query .= " where ".$searchdate;

							  else

							  $query .= " and ".$searchdate;

							 }

			  }

			$qry = mysql_query($query)or die(mysql_error());

			$totalRecs = mysql_num_rows($qry);

			

			return $totalRecs;

		}



		

		public function _showPageCodeList($statustype,$period,$totalRecs,$i = 0,$no_rec = 0,$searchQuery,$searchdate)

		{

			$fromLimit = $no_rec*($i - 1);
			$toLimit = $no_rec;
			$flag=0;
            if($period=='0'){$pd=0;}
			else{
			$pd=$period." months";
			}
		
			if($statustype=='0')

			{

			  if($pd==0)

			  {

			      $condiiton="";$flag=1;

			  }

			  else

			  {

			      $condiiton=" where t1.codetype='$pd'";

			  }

			}

			else

			{

			  if($pd==0)

			  {

			$condiiton=" where t1.codestatus='$statustype'";

			 }

			 else

			 {

			  $condiiton=" where t1.codestatus='$statustype' and  t1.codetype='$pd'";

			 }

			}

			$query="SELECT t1.id,t1.code,t1.gen_date,t1.codetype,t1.codestatus, t1.codeamount, t3.email, t2.purchasedate, t2.usedate, t4.user_email FROM gift_code t1

LEFT JOIN (gift_userdetails t2 LEFT JOIN gift_member t3 ON ( t3.purchaseid = t2.purchaseid ) LEFT JOIN user_master t4 ON ( t4.user_id = t2.user_id )) ON ( t1.code = t2.code )".$condiiton;

            if($searchQuery)

			  {

				$flag2=1;

				if($flag==1)

				{

				 $query .= " where ".$searchQuery;

				 }

				 else

				 {

				      $query .= " and ".$searchQuery;

				 }

			  }

			   if($searchdate)

			  {

				      if($flag2==1)

					  $query .= " and ".$searchdate;

					 else {if($flag==1)

							  $query .= " where ".$searchdate;

							  else

							  $query .= " and ".$searchdate;

							 }

			  }

           $order=" order by t1.codetype LIMIT {$fromLimit},{$toLimit}";

			$query.=$order;

			$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);

			

			return $result;

		}

}
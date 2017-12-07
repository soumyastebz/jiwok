<?
 
    
  //checking whether the background conversion is going on
  
   if($_REQUEST['t'] == 'bG'){
   
   		exec("php convert_background.php > b.txt &");
   		header('Location:index.php?bG=1');
   }
   
   if($_REQUEST['t'] == 'vC'){
   
   		exec("php convert_to_vcal.php > v.txt &");
   		header('Location:index.php?vC=1');
   }
 
?>

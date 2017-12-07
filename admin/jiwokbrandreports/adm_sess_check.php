<?php
	session_start();
	if(!$_SESSION['brand_master_id']){
		header("location:login.php");
	}
?>
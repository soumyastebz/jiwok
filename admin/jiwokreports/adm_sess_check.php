<?php
	session_start();
	if(!$_SESSION['adm_id_report']){
		header("location:login.php");
	}
?>
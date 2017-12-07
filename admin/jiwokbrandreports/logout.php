<?php
	session_start();
	$_SESSION['adm_id_report'] = "";
	session_destroy();
	header("Location:login.php");
?>
<?php
	session_start();
	$_SESSION['adminId'] = "";
	session_destroy();
	header("Location:login.php");
?>
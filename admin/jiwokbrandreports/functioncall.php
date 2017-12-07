<?php 
if($_POST['country_select'] == '1' && $_POST['user_country']!=""){
		echo "<script language='javascript' type='text/javascript'>selectCountry(1);</script>";
}
if($_POST['language_select'] == '1' && $_POST['user_language']!=""){
		echo "<script language='javascript' type='text/javascript'>selectLanguage(1);</script>";
}
if($_POST['brand_select'] == '1'){
		echo "<script language='javascript' type='text/javascript'>selectBrand(1);</script>";
}
if($_POST['origin_select'] == '1'){
		echo "<script language='javascript' type='text/javascript'>selectOrigin(1);</script>";
}
if($_POST['type_select'] == '1'){
		echo "<script language='javascript' type='text/javascript'>selectType(1);</script>";
}
if($_POST['program_select'] == '1'){
		echo "<script language='javascript' type='text/javascript'>selectProgram(1);</script>";
}
if($_POST['cmp_select'] == '1'){
		echo "<script language='javascript' type='text/javascript'>selectCmp(1);</script>";
}
?>
<?php
function recurse_move($src,$dst) {
    $dir = opendir($src);
    @mkdir($dst,0777,true);
	chmod($dst,0777);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                recurse_move($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
} 
function delete_directory($dirname) {
  //echo $dirname;
    if (is_dir($dirname))
       $dir_handle = opendir($dirname);
    if (!$dir_handle)
       return false;
    while($file = readdir($dir_handle)) {
	   //echo $file;
       if ($file != "." && $file != "..") {
          if (!is_dir($dirname."/".$file)){
             unlink($dirname."/".$file)or die("cannot delete file: ".$file);
			}
          else
             delete_directory($dirname.'/'.$file);    
       }
    }
     closedir($dir_handle);
     rmdir($dirname)or die("cannot delete directory: ".$dirname);
    return true;
 }
// delete_directory('../templates/first');
/*
$a['val']=array('1','2','3');
$a['det']='home';
echo "<pre>";
print_r($a);
echo "</pre>";*/
?>
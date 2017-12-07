<?php
/*********************
 * Cropping Tool v1.0
 * Author: Deepak
 * Date: 06/08/2015
 *********************/
 
 
require_once 'config_category.php';
session_start();
	include_once('../../includeconfig.php');
$data = $_REQUEST;

$dataToWrite = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data['imageBlob']));
$imageName = $data['imageName'];
$dbimage = trim($data['dbimage']);

$imageWidth = $data['imageWidth'];
$imageHeight = $data['imageHeight'];
$lan_id = $data['lan_id'];
$flx_id = $data['flx_id'];
file_put_contents(UPLOAD_PATH.'temp-'.$dbimage, $dataToWrite);
rename(IMAGE_PATH.$dbimage,IMAGE_PATH.'old_'.$dbimage);


function resize_image($file, $destination, $w, $h,$imageName) {
    //Get the original image dimensions + type
    list($source_width, $source_height, $source_type) = getimagesize($file);

    //Figure out if we need to create a new JPG, PNG or GIF
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if ($ext == "jpg" || $ext == "jpeg") {
        $source_gdim=imagecreatefromjpeg($file);
    } elseif ($ext == "png") {
        $source_gdim=imagecreatefrompng($file);
    } elseif ($ext == "gif") {
        $source_gdim=imagecreatefromgif($file);
    } else {
        //Invalid file type? Return.
        return;
    }

    //If a width is supplied, but height is false, then we need to resize by width instead of cropping
    if ($w && !$h) {
        $ratio = $w / $source_width;
        $temp_width = $w;
        $temp_height = $source_height * $ratio;

        $desired_gdim = imagecreatetruecolor($temp_width, $temp_height);
        imagecopyresampled(
            $desired_gdim,
            $source_gdim,
            0, 0,
            0, 0,
            $temp_width, $temp_height,
            $source_width, $source_height
            );
    } else {
        $source_aspect_ratio = $source_width / $source_height;
        $desired_aspect_ratio = $w / $h;

        if ($source_aspect_ratio > $desired_aspect_ratio) {
            /*
             * Triggered when source image is wider
             */
            $temp_height = $h;
            $temp_width = ( int ) ($h * $source_aspect_ratio);
        } else {
            /*
             * Triggered otherwise (i.e. source image is similar or taller)
             */
            $temp_width = $w;
            $temp_height = ( int ) ($w / $source_aspect_ratio);
        }

        /*
         * Resize the image into a temporary GD image
         */

        $temp_gdim = imagecreatetruecolor($temp_width, $temp_height);
        imagecopyresampled(
            $temp_gdim,
            $source_gdim,
            0, 0,
            0, 0,
            $temp_width, $temp_height,
            $source_width, $source_height
            );

        /*
         * Copy cropped region from temporary image into the desired GD image
         */

        $x0 = ($temp_width - $w) / 2;
        $y0 = ($temp_height - $h) / 2;
        $desired_gdim = imagecreatetruecolor($w, $h);
        imagecopy(
            $desired_gdim,
            $temp_gdim,
            0, 0,
            $x0, $y0,
            $w, $h
            );
    }

    /*
     * Render the image
     * Alternatively, you can save the image in file-system or database
     */

    if ($ext == "jpg" || $ext == "jpeg") {
        ImageJpeg($desired_gdim,$destination,100);
    } elseif ($ext == "png") {
        ImagePng($desired_gdim,$destination, 9);
    } elseif ($ext == "gif") {
        ImageGif($desired_gdim,$destination);
    } else {
        return;
    }

    ImageDestroy ($desired_gdim);
    if (file_exists($file)){
	chmod($file, 0644);
    unlink($file);
    } 
}
// resize image
resize_image(UPLOAD_PATH.'temp-'.$dbimage,IMAGE_PATH.$dbimage, $imageWidth, $imageHeight, $imageName);
 
 
 
 $sql    = "update sub_category set category_image='".$dbimage."' where flex_id=$flx_id ";
 $result = $GLOBALS['db']->query($sql);
 
 
 
 
  /*$sql = "select img_new from category_image  where flex_id=$flx_id";
    $result = $GLOBALS['db']->getAll($sql, DB_FETCHMODE_ASSOC);//print_r($result);exit;
  if(count($result)>0)
  {   
	  $sql = "update category_image set img_new='".$dbimage."' where flex_id=$flx_id ";
      $result = $GLOBALS['db']->query($sql);

  }
  else
  {   
	   $query = "INSERT INTO category_image (flex_id,img_new) VALUES ($flx_id,'$dbimage')";
 	   $result =  $GLOBALS['db']->query($query);
  }*/
  
    

<?php
/*********************
 * Cropping Tool v1.0
 * Author: Deepak
 * Date: 06/08/2015
 *********************/
 error_reporting(E_ALL);
ini_set('display_errors',1);
 
require_once 'config.php';
session_start();
	include_once('../../includeconfig.php');
$data = $_REQUEST;

$dataToWrite = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data['imageBlob']));
//$dataToWrite = base64_decode(preg_replace('#^data:image/png;base64,#i', '', $data['imageBlob']));
$imageName = $data['imageName'];
$dbimage = $data['dbimage'];

$imageWidth = $data['imageWidth'];
$imageHeight = $data['imageHeight'];
$crop_user_id = $data['crop_user_id'];
// imagejpeg($dataToWrite,UPLOAD_PATH.'ss.jpg',100);
file_put_contents(UPLOAD_PATH.'temp-'.$dbimage, $dataToWrite);
//$url = 'http://beta.jiwok.com/admin/crop/assets/img/'.$dbimage;
//file_put_contents(UPLOAD_PATH.'temp-'.$dbimage, file_get_contents($url));
rename(IMAGE_PATH.$dbimage,IMAGE_PATH.'old_'.$dbimage);

function resize_image($file, $destination, $w, $h,$imageName) {
    //Get the original image dimensions + type
	
    list($source_width, $source_height, $source_type) = getimagesize($file);

    //Figure out if we need to create a new JPG, PNG or GIF
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if ($ext == "jpg" || $ext == "jpeg") {
        $source_gdim=imagecreatefromjpeg($file);
		//echo "mm".  $source_gdim."ll";exit;
    
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
    unlink(UPLOAD_PATH.'temp-'.$dbimage);
}

// resize image
resize_image(UPLOAD_PATH.'temp-'.$dbimage, IMAGE_PATH.$dbimage, $imageWidth, $imageHeight, $imageName);
  $sql = "update program_master set image_newdesign='".$dbimage."' where program_id=$crop_user_id";
 $result = $GLOBALS['db']->query($sql);

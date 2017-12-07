<?php
/*********************
 * Cropping Tool v1.0
 * Author: Deepak
 * Date: 06/08/2015
 *********************/

/*
 * Path to original image (Directory)
 * Trailing slash required
 */
define('IMAGE_PATH', 'assets/img/');

/* 
 * Upload directory for cropped images (should have 777 permission) 
 * Trailing slash required *
 */
define('UPLOAD_PATH', 'uploads_category/');

// Image resolutions for cropping
$IMAGE_RESOLUTIONS =
	array(
		array(
			'name'  => 'width  1980 X height 877  ',
			'width' => '1980',
			'height'=> '877'
			)
		);

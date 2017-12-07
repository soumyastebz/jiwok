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
define('UPLOAD_PATH', 'uploads/');

// Image resolutions for cropping
$IMAGE_RESOLUTIONS =
	array(
		array(
			'name'  => 'Portrait 320 x 480',
			'width' => '320',
			'height'=> '480'
			),
		array(
			'name'  => 'Landscape 480 x 320',
			'width' => '480',
			'height'=> '320'
			),
		array(
			'name'  => 'VGA 640 x 480',
			'width' => '640',
			'height'=> '480'
			),
		array(
			'name'  => 'custom 550 x 400',
			'width' => '550',
			'height'=> '400'
			)
		);
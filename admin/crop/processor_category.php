<?php
/*********************
 * Cropping Tool v1.0
 * Author: Deepak
 * Date: 06/08/2015
 *********************/

/**
 * All future modifications & features should be added here
 */
class DataProcessor{
    private $data;

    public function __construct(){
        $this->data = $_REQUEST;
        if ($this->data['mode'] == 1) {
            echo self::cropResolutions();
            exit;
        }
    }
    private function cropResolutions(){
        require_once 'config_category.php';
        $resolutions = $IMAGE_RESOLUTIONS;
        return json_encode($resolutions);
    }
}


// Invoke class
new DataProcessor();

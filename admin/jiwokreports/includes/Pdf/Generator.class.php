<?php
/**
 *   @copyright Copyright (c) 2007 Quality Unit s.r.o.
 *   @package PostAffiliatePro
 *   @author Milos Jancovic
 *   @since Version 1.0.0
 *
 *   Licensed under the Quality Unit, s.r.o. Standard End User License Agreement,
 *   Version 1.0 (the "License"); you may not use this file except in compliance
 *   with the License. You may obtain a copy of the License at
 *   http://www.qualityunit.com/licenses/license
 *
 */

/**
 * @package GwtPhpFramework
 */

abstract class Gpf_Pdf_Generator extends Gpf_Object {
    
    protected $title;
    protected $creator;
    protected $author;
    protected $subject;
    protected $keywords;
    protected $fontInfo;
    protected $savePath;
    
    /**
     * @var Gpf_Pdf_Tcpdf
     */
    protected $pdf;
    
    function __construct() {
    	$this->pdf = new Gpf_Pdf_Tcpdf();
    	$this->savePath = null;
    	$this->init();
    }
    
    private function init() {
        $this->setInitInfo();
        $this->setHeaderAndFooter();
        
    	$this->pdf->SetCreator($this->creator);
        $this->pdf->SetAuthor($this->author);
        $this->pdf->SetTitle($this->title);
        $this->pdf->SetSubject($this->subject);
        $this->pdf->SetKeywords($this->keywords);
        $this->pdf->SetFont($this->fontInfo["font"], $this->fontInfo["style"], $this->fontInfo["size"]);
        
        $this->pdf->AliasNbPages();
    }
    
    /**
     *  Example to use:
     * 
     * protected function setInitInfo() {
     *      $this->title = "Title";
     *      $this->creator = "Application name";
     *      $this->author = "Author";
     *      $this->subject = "Subject";
     *      $this->keywords = "Keywords";
     *  
     *      $this->fontInfo = array("font" => "freeserif",
     *                              "style" => "",
     *                              "size" => 10);
     *      $this->savePath = "SavePath";
     * }
     *
     */
    abstract protected function setInitInfo();
    
    /**
     * @service pdf read
     * @param Gpf_Rpc_Params $params
     */
    public function generatePdfFromHtml(Gpf_Rpc_Params $params) {
        if ($params->exists('html')) {
            $html = $params->get('html');
        } else {
            throw new Gpf_Exception($this->_("Html data is not defined"));
        }
        
        if ($params->exists('fileName')) {
            $fileName = $params->get('fileName');
        } else {
            throw new Gpf_Exception($this->_("File name is not defined"));
        }

        $html = htmlspecialchars_decode($html);
        
        $this->generatePDF($html);
        
        return new Gpf_Pdf_GeneratorResponse($fileName, $this->pdf);
    }
    
    /**
     * @service pdf write
     * @param Gpf_Rpc_Params $params
     */
    public function savePdfFromHtml(Gpf_Rpc_Params $params) {
        $response = new Gpf_Rpc_Action();
        $response->setInfoMessage($this->_("Pdf saved"));
        
        if ($params->exists('html')) {
            $html = $params->get('html');
        } else {
            $response->setErrorMessage($this->_("Html data is not defined"));
            $response->addError();
            return $response;
        }
        
        if ($params->exists('fileName')) {
            $fileName = $params->get('fileName');
        } else {
            $response->setErrorMessage($this->_("File name is not defined"));
            $response->addError();
            return $response;
        }
        
        htmlspecialchars_decode($html);
        $this->generatePDF($html);
        
        if ($this->savePath != null) {
            if (@is_dir($this->savePath)) {
                chdir($this->savePath);
            } else {
                throw new Gpf_Exception($this->_("Path to save file '%s' not exist", $this->savePath));
            }
        } else {
        	throw new Gpf_Exception($this->_("Path to save file %s is null", $fileName));
        }
        
        try {
            $this->pdf->Output($fileName.".pdf", "F");
        } catch (Gpf_Exception $e) {
        	$response->setErrorMessage($this->_("Unable to create file %s%s.pdf", $this->savePath, $fileName));
        }
        
        $response->addOk();
        
        return $response;
    }
    
    protected function generatePDF($html) {
    	$this->pdf->AddPage();
        $this->pdf->writeHTML($html);
        $this->pdf->lastPage(); 
    }
    
    protected function setHeaderAndFooter() {
        $this->pdf->SetHeaderData();
        $this->pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $this->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
    }
}

?>

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

class Gpf_Pdf_GeneratorResponse extends Gpf_Object implements Gpf_Rpc_Serializable  {
    
    private $fileName;
    /**
     * @var Gpf_Pdf_Tcpdf
     */
    private $pdf;
    
    function __construct($fileName, $pdf) {
        $this->fileName = $fileName;
        $this->pdf = $pdf;
    }
    
    public function toObject() {
        throw new Gpf_Exception($this->_("Unsupported"));
    }

    public function toText() {
        try {
            $this->pdf->Output($this->fileName.".pdf", "D");
        } catch (Gpf_Exception $e) {
            echo $this->_("Pdf output: %s", $e->getMessage());
        }
    }
}

<?php
require_once('./pdf/config/lang/eng.php');
require_once('./pdf/tcpdf.php');

$title = "gift code from jiwok";
$subtitle = "jiwok giftcode";
$content = "<table cellspacing='0' cellpadding='0' border='0' width='100%' style='background-color: rgb(238, 238, 238);'>
    <tbody>
        <tr>
            <td align='center' style='padding-top: 77px;'>
            <table height='430' cellspacing='0' cellpadding='0' border='0' background='./images/Jiwok_Gift_Card_bg.jpg' width='900'>
                <tbody>
                    <tr>
                        <td valign='top' align='left' style='padding: 105px 0pt 0pt 5px;'>
                        <table height='180' cellspacing='0' cellpadding='0' border='0' width='441'>
                            <tbody>
                                <tr>
                                    <td width='85' style='font-family: Arial; font-size: 24px; font-weight: bold; color: rgb(55, 79, 97); line-height: 25px; text-align: right;'>De:</td>
                                    <td width='332'>&nbsp;{NAME}</td>
                                </tr>
                                <tr>
                                    <td width='85' style='font-family: Arial; font-size: 24px; font-weight: bold; color: rgb(55, 79, 97); line-height: 25px; text-align: right;'>A:</td>
                                    <td width='332'><label> <input type='text' size='35' style='border: 1px none ; padding: 5px 0pt 0pt 5px; background: transparent none repeat scroll 0% 50%; font-size: 18px; font-weight: normal; color: rgb(85, 107, 123); line-height: 25px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial;' id='textfield1' name='textfield' /> </label></td>
                                </tr>
                                <tr>
                                    <td width='85' style='font-family: Arial; font-size: 24px; font-weight: bold; color: rgb(55, 79, 97); line-height: 25px; text-align: right;'>Dur&eacute;e:</td>
                                    <td width='332'>{GIFT_TYPE}</td>
                                </tr>
                                <tr>
                                    <td width='85' style='font-family: Arial; font-size: 24px; font-weight: bold; color: rgb(70, 103, 131); line-height: 25px; text-align: right;'>Code:</td>
                                    <td width='332'>{GIFT_CODE}</td>
                                </tr>
                            </tbody>
                        </table>
                        <table height='100' cellspacing='0' cellpadding='0' border='0' width='441' style='padding-top: 30px;'>
                            <tbody>
                                <tr>
                                    <td valign='top' align='left' style='font-family: Arial,Verdana; font-size: 14px; font-weight: bold; font-style: italic; color: rgb(43, 75, 99); line-height: 15px;'>Jiwok est un coach sportif mp3: <span style='font-family: Arial,Verdana; font-size: 13px; font-weight: bold; font-style: italic; color: rgb(53, 86, 111); line-height: 15px;'>Vous serez guid&eacute;, entrain&eacute; en musique avec la voix d'un coach afin de rester en forme, pr&eacute;parer une course ou perdre du poids ... Pour utiliser ce bon cadeau lors de votre inscription sur le site </span><span style='font-family: Arial,Verdana; font-size: 13px; font-weight: bold; color: rgb(197, 35, 48); line-height: 15px;'>www.jiwok.com </span><span style='font-family: Arial,Verdana; font-size: 13px; font-weight: bold; font-style: italic; color: rgb(53, 86, 111); line-height: 15px;'>, il vous sera demand&eacute; un code promotionnel, entrez le code ci-dessus et votre abonnement sera valid&eacute; pour la p&eacute;riode donn&eacute;e. Je vous rappelle que ce code cadeau ne peut &ecirc;tre utilis&eacute; qu&rsquo;une seule et unique fois.</span></td>
                                </tr>
                            </tbody>
                        </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            </td>
        </tr>
    </tbody>
</table>
<p>&nbsp;</p>";
$steps = 5;
$lines = "";

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
	//Page header
	public function Header() {
		// Logo
		$this->Image(K_PATH_IMAGES.'head.jpg', 10, 10, 190);
		// Set font
		$this->SetFont('helvetica', 'B', 20);
		// Move to the right
		$this->Cell(80);
		// Line break
		$this->Ln(20);
	}
	
	// Page footer
	public function Footer() {
		// Position at 1.5 cm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, 0, 'C');
	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 

//set some language-dependent strings
$pdf->setLanguageArray($l); 

// ---------------------------------------------------------

// set font


// add a page
$pdf->AddPage();

$pdf->Cell(0, 20, '', 0, 1, 'C');
$html = '<b style="font:Georgia;font-weight:bold;font-size:45pt;color:#007ac3;">'.$content.'</b><br /><br /><img src="./jiwok_ver2/admin/images/Jiwok_Gift_Card_bg.jpg">';
$pdf->writeHTML($html, true, 0, true, 0);




// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('attchmnt.pdf', 'I');

//============================================================+
// END OF FILE                                                 
//============================================================+
?>

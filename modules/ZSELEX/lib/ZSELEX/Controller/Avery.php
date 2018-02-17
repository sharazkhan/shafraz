<?php
class ZSELEX_Controller_Avery extends ZSELEX_Controller_Base_Admin {
	var $col = 0;
	var $y0;
	function printAvery_prev($SelectStmt) {
		require ('modules/ZSELEX/lib/vendor/fpdf/fpdf.php');
		$shop_id = FormUtil::getPassedValue ( 'shop_id', null, 'REQUEST' );
		$em = ServiceUtil::getService ( 'doctrine.entitymanager' );
		$repo = $em->getRepository ( 'ZSELEX_Entity_Shop' );
		$shop = $repo->get ( array (
				'entity' => 'ZSELEX_Entity_Shop',
				'where' => array (
						'a.shop_id' => $shop_id 
				) 
		) );
		// echo "<pre>"; print_r($shop); echo "</pre>"; exit;
		$siteUrl = pnGetBaseURL () . ModUtil::url ( 'ZSELEX', 'user', 'site', array (
				'shop_id' => $shop_id 
		) );
		$shopName = $shop ['shop_name'];
		$shopAddress = $shop ['address'];
		$shopPhone = $shop ['telephone'];
		$shopEmail = $shop ['email'];
		$pdf = new FPDF ( 'P', 'mm', 'A4' );
		$pdf->Open ();
		$pdf->AddPage ();
		$pdf->SetFont ( 'Arial', 'B', 7 );
		$pdf->SetMargins ( 0, 0 );
		// $pdf->SetAutoPageBreak(false);
		// $pdf->SetAutoPageBreak(TRUE, 0);
		// $pdf->SetDisplayMode('real');
		
		$x = 0;
		$y = 0;
		for($i = 1; $i <= 24; $i ++) {
			// $LabelText = sprintf("%s\n%s\n%s, %s, %s", $shopName, $shopAddress, $shopEmail, '', '');
			$LabelText = sprintf ( "%s\n%s\n%s\n%s\n%s", $siteUrl, $shopName, $shopAddress, $shopPhone, $shopEmail );
			// $LabelText = sprintf("%s\n", $siteUrl);
			$this->Avery5160 ( $x, $y, $pdf, $LabelText );
			
			$y ++; // next row
			if ($y == 8) { // end of page wrap to next column
				$x ++;
				$y = 0;
				if ($x == 3) { // end of page
					$x = 0;
					$y = 0;
					// $pdf->AddPage();
				}
			}
		}
		$pdf->Output ();
		exit ();
	}
	function Avery5160($x, // X co-ord of label (0-2)
$y, // Y co-ord of label (0-9)
&$pdf, $Data) { // String w/ line breaks to print
		$LeftMargin = 4.2;
		$TopMargin = 12.7;
		$LabelWidth = 66.6;
		// $LabelHeight = 25.45;
		$LabelHeight = 33;
		// Create Co-Ords of Upper left of the Label
		$AbsX = $LeftMargin + (($LabelWidth + 4.22) * $x);
		$AbsY = $TopMargin + ($LabelHeight * $y);
		
		// Fudge the Start 3mm inside the label to avoid alignment errors
		$pdf->SetXY ( $AbsX + 3, $AbsY + 3 );
		$pdf->MultiCell ( $LabelWidth - 8, 4.5, $Data, 0 );
		
		return;
	}
	function Avery51601($x, $y, &$pdf, $text) {
		$left = 4.826; // 0.19" in mm
		$top = 12.7; // 0.5" in mm
		$width = 76.802; // 2.63" in mm
		$height = 25.4; // 1.0" in mm
		$hgap = 3.048; // 0.12" in mm
		$vgap = 0.0;
		
		$x = $left + (($width + $hgap) * $x);
		$y = $top + (($height + $vgap) * $y);
		$pdf->SetXY ( $x, $y );
		$pdf->MultiCell ( $width, 5, $text, 1, 'C' );
	}
	
	/*
	 * Print Avery Label
	 *
	 * @uses fpdf library
	 * @return void
	 */
	function printAvery() {
		$this->throwForbiddenUnless ( SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_EDIT ), LogUtil::getErrorMsgPermission () );
		require ('modules/ZSELEX/lib/vendor/fpdf/PDF_Label.php');
		
		$shop_id = FormUtil::getPassedValue ( 'shop_id', null, 'REQUEST' );
		if (! is_numeric ( $shop_id )) {
			return LogUtil::registerError ( $this->__f ( 'Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!', ( int ) ($shop_id) ), 403 );
		}
		
		$user_id = UserUtil::getVar ( 'uid' );
		$shopPermission = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'shopPermission', array (
				'shop_id' => $shop_id,
				'user_id' => $user_id 
		) );
		
		if ($shopPermission < 1) {
			return LogUtil::registerPermissionError ();
		}
		
		$em = ServiceUtil::getService ( 'doctrine.entitymanager' );
		$repo = $em->getRepository ( 'ZSELEX_Entity_Shop' );
		$shop = $repo->get ( array (
				'entity' => 'ZSELEX_Entity_Shop',
				'where' => array (
						'a.shop_id' => $shop_id 
				) 
		) );
		// echo "<pre>"; print_r($shop); echo "</pre>"; exit;
		$siteUrl = pnGetBaseURL () . ModUtil::url ( 'ZSELEX', 'user', 'site', array (
				'shop_id' => $shop_id 
		) );
		$shopName = $shop ['shop_name'];
		$shopAddress = $shop ['address'];
		$shopPhone = $shop ['telephone'];
		$shopEmail = $shop ['email'];
		
		/*
		 * ------------------------------------------------
		 * To create the object, 2 possibilities:
		 * either pass a custom format via an array
		 * or use a built-in AVERY name
		 * ------------------------------------------------
		 */
		
		// Example of custom format
		// $pdf = new PDF_Label(array('paper-size'=>'A4', 'metric'=>'mm', 'marginLeft'=>1, 'marginTop'=>1, 'NX'=>2, 'NY'=>7, 'SpaceX'=>0, 'SpaceY'=>0, 'width'=>99, 'height'=>38, 'font-size'=>14));
		// Standard format
		// $pdf = new PDF_Label('L7163');
		
		$QR_IMAGE = $this->QR_IMAGE ( $shop_id );
		$pdf = new PDF_Label ( 'L7181' );
		
		$pdf->AddPage ();
		$pdf->shopId = $shop_id;
		$pdf->qrCodeImage = $QR_IMAGE;
		// Print labels
		for($i = 1; $i <= 24; $i ++) {
			$text = sprintf ( "  %s\n%s\n%s\n%s\n%s", $siteUrl, $shopName, $shopAddress, $shopPhone, $shopEmail );
			// two spaces before first parameter nessecery... look below!
			$text = str_replace ( "\n", "\n  ", $text );
			// to make left text margin two "spaces" larger!!! Placement option would be better like mm or pixel or units!
			$text = stripslashes ( $text );
			$text = iconv ( 'UTF-8', 'windows-1252', $text );
			$pdf->Add_Label ( $text );
			// $pdf->Image($QR_IMAGE,10,8,0,0);
			$pdf->Image ( $QR_IMAGE, $pdf->GetX () + 45, $pdf->GetY () - 15, 18, 18 );
			$pdf->Image ( $QR_IMAGE, $pdf->GetX () + 115, $pdf->GetY () - 15, 18, 18 );
			$pdf->Image ( $QR_IMAGE, $pdf->GetX () + 185, $pdf->GetY () - 15, 18, 18 );
			// X-Position IRL = X1:6mm + 47mm; X2:76mm + 117mm; X3:146mm + 187mm; Y-Position of IMG: 17mm; IMG size: 15x15mm
			// offset behind GetX and GetY is mm value last 2 parameters are size in mm almost!!!
			// Perhaps image size is influenced by position also!
			// $pdf->Image($QR_IMAGE, $pdf->GetX() + 45, $pdf->GetY() - 14, 15, 15);
			// $pdf->Image($QR_IMAGE, $pdf->GetX() + 115, $pdf->GetY() - 14, 15, 15);
			// $pdf->Image($QR_IMAGE, $pdf->GetX() + 185, $pdf->GetY() - 14, 15, 15);
			// X-Position IRL = X1:5mm + 46mm; X2:75mm + 116mm; X3:145mm + 186mm; Y-Position of IMG: 17mm; IMG size:14x14mm
		}
		
		$pdf->Output ();
		exit ();
	}
	
	/*
	 * Generate QR CODE
	 */
	function QR_IMAGE($shop_id) {
		// echo "QR_IMAGE"; exit;
		/**
		 * QR Code + Logo Generator
		 *
		 * http://labs.nticompassinc.com
		 */
		// $data = isset($_GET['data']) ? $_GET['data'] : 'http://labs.nticompassinc.com';
		$siteUrl = pnGetBaseURL () . ModUtil::url ( 'ZSELEX', 'user', 'site', array (
				'shop_id' => $shop_id 
		) );
		// echo $siteUrl; exit;
		// $data = "http://citypilot.dk/site/billunds-boghandel";
		$data = $siteUrl;
		// $size = isset($_GET['size']) ? $_GET['size'] : '200x200';
		$size = isset ( $_GET ['size'] ) ? $_GET ['size'] : '200x200';
		// $_GET['logo'] = "http://localhost/test/CP.png";
		// $logo = isset($_GET['logo']) ? $_GET['logo'] : FALSE;
		$logo = "themes/CityPilot/images/CP-clogo.jpg";
		
		header ( 'Content-type: image/png' );
		// Get QR Code image from Google Chart API
		// http://code.google.com/apis/chart/infographics/docs/qr_codes.html
		$QR = imagecreatefrompng ( 'https://chart.googleapis.com/chart?cht=qr&chld=H|1&chs=' . $size . '&chl=' . urlencode ( $data ) );
		if ($logo !== FALSE) {
			$logo = imagecreatefromstring ( file_get_contents ( $logo ) );
			
			$QR_width = imagesx ( $QR );
			$QR_height = imagesy ( $QR );
			
			$logo_width = imagesx ( $logo );
			$logo_height = imagesy ( $logo );
			
			// Scale logo to fit in the QR Code
			$logo_qr_width = $QR_width / 3;
			$scale = $logo_width / $logo_qr_width;
			$logo_qr_height = $logo_height / $scale;
			
			imagecopyresampled ( $QR, $logo, $QR_width / 3, $QR_height / 3, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height );
		}
		// imagepng($QR);
		// imagepng($QR, "myLogo.png");
		// imagepng($QR, 'modules/ZSELEX/lib/vendor/fpdf/myLogo2.png');
		$qrPath = "zselexdata/$shop_id/QR";
		if (! file_exists ( $qrPath ) && ! empty ( $qrPath )) {
			mkdir ( $qrPath, 0775, true );
			chmod ( $qrPath, 0775 );
		}
		$qrImage = $qrPath . "/" . $shop_id . "_qrCode.png";
		imagepng ( $QR, $qrImage );
		imagedestroy ( $QR );
		// exit;
		return $qrImage;
	}
}

?>
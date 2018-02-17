<?php

/**
 * Copyright ACTA-IT 2013
 *
 * ZSELEX
 * Demonstration of Zikula Module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control User interface
 */
class ZSELEX_Api_Upload extends Zikula_AbstractApi {
	public function uploadEventImage($args) {
		$filename = $args ['filename'];
		$destination = $args ['destination'];
		list ( $orig_width, $orig_height ) = getimagesize ( pnGetBaseURL () . $destination . $filename );
		
		$allowedExtensions = array (
				'png',
				'jpg',
				'gif',
				'jpeg' 
		);
		$ex = end ( explode ( ".", $filename ) );
		if (! in_array ( $ex, $allowedExtensions )) {
			// return LogUtil::registerError($this->__f('Error! Invalid file type: %1$s', $ex));
		}
		$modvariable = $this->getVars ();
		
		$fullWidth = ! empty ( $modvariable ['fullimagewidth'] ) ? $modvariable ['fullimagewidth'] : 1024;
		$fullHeight = ! empty ( $modvariable ['fullimageheight'] ) ? $modvariable ['fullimageheight'] : 768;
		
		if ($fullWidth > $orig_width) {
			$fullWidth = $orig_width;
		}
		if ($fullHeight > $orig_height) {
			$fullHeight = $orig_height;
		}
		
		$medWidth = ! empty ( $modvariable ['medimagewidth'] ) ? $modvariable ['medimagewidth'] : 800;
		$medHeight = ! empty ( $modvariable ['medimageheight'] ) ? $modvariable ['medimageheight'] : 500;
		
		if ($medWidth > $orig_width) {
			$medWidth = $orig_width;
		}
		if ($medHeight > $orig_height) {
			$medHeight = $orig_height;
		}
		
		$thumbWidth = ! empty ( $modvariable ['thumbimagewidth'] ) ? $modvariable ['thumbimagewidth'] : 298;
		$thumbHeight = ! empty ( $modvariable ['thumbimageheight'] ) ? $modvariable ['thumbimageheight'] : 133;
		
		if ($thumbWidth > $orig_width) {
			$thumbWidth = $orig_width;
		}
		if ($thumbHeight > $orig_height) {
			$thumbHeight = $orig_height;
		}
		
		// if (!in_array($ex, $allowedExtensions)) {
		require_once ('modules/ZSELEX/lib/vendor/ImageResize.php');
		$resizeObj = new ImageResize ( $destination . $filename );
		$resizeObj->resizeImage ( $fullWidth, $fullHeight );
		$resizeObj->saveImage ( $destination . 'fullsize/' . $filename, 100 );
		
		$resizeObj->resizeImage ( $medWidth, $medHeight );
		$resizeObj->saveImage ( $destination . 'medium/' . $filename, 100 );
		
		$resizeObj->resizeImage ( $thumbWidth, $thumbHeight );
		$resizeObj->saveImage ( $destination . 'thumb/' . $filename, 100 );
		if (in_array ( $ex, $allowedExtensions )) {
			unlink ( $destination . $filename );
		}
		// }
		
		return true;
	}
	public function generateEventPdfImage($args) {
		
		// echo "<pre>"; print_r($args); echo "</pre>"; exit;
		try {
			$destination = $args ['destination'];
			$pdfDirectory = $destination;
			// $thumbDirectory = "zselexdata/shoppdf/thumb/";
			$thumbDirectory = $destination . '/thumb/';
			$mediumDirectory = $destination . '/medium/';
			
			if (! file_exists ( $thumbDirectory ) && ! empty ( $thumbDirectory )) {
				mkdir ( $thumbDirectory, 0775, true );
				chmod ( $thumbDirectory, 0775 );
			}
			
			if (! file_exists ( $mediumDirectory ) && ! empty ( $mediumDirectory )) {
				mkdir ( $mediumDirectory, 0775, true );
				chmod ( $mediumDirectory, 0775 );
			}
			if (! file_exists ( $destination . '/tmp/' )) {
				mkdir ( $destination . '/tmp/', 0775, true );
				chmod ( $destination . '/tmp/', 0775 );
			}
			// print_r($file); exit;
			// echo $file['newName']; exit;
			// $name = $file['name'];
			$name = $args ['filename'];
			// exit;
			// Check file extension
			
			$allowedExtensions = array (
					'pdf' 
			);
			$ex = end ( explode ( ".", $name ) );
			if (! in_array ( $ex, $allowedExtensions )) {
				return LogUtil::registerError ( $this->__f ( 'Error! Invalid file type: %1$s', $ex ) );
			}
			// Check file size
			if ($size >= 16000000) {
				return LogUtil::registerError ( $this->__ ( 'Error! Your file is too big. The limit is 14 MB.' ) );
			}
			$newNme = $args ['filename'];
			
			// echo $newNme; exit;
			
			$thumb = basename ( $newNme, ".pdf" );
			// $thumb = preg_replace("/[^A-Za-z0-9_-]/", "", $thumb) . ".pdf";
			$thumb = preg_replace ( "/[^A-Za-z0-9_-]/", "", $thumb );
			// echo $thumb; exit;
			// $code = self::doUploadFile($file, $destination);
			// the path to the PDF file
			$pdfWithPath = $pdfDirectory . '/' . $newNme;
			// echo $pdfWithPath; exit;
			// add the desired extension to the thumbnail
			// $time = time();
			$thumb = $thumb . ".jpg";
			
			// echo $thumb; exit;
			// echo $thumbDirectory.$thumb; exit;
			
			$finalPath = $thumbDirectory . $thumb;
			$finalPath2 = $mediumDirectory . $thumb;
			// exec("convert \"{$pdfWithPath}[0]\" -colorspace RGB -geometry 120 $finalPath");
			// if ($_SERVER['SERVER_NAME'] == 'localhost') { // only for localhost
			// exec("convert -define jpeg:size=60x60 \"{$pdfWithPath}[0]\" -colorspace RGB -thumbnail 100x150 -gravity center -crop 100x150+0+0 +repage $finalPath");
			// exec("convert -define jpeg:size=60x60 \"{$pdfWithPath}[0]\" -colorspace RGB -thumbnail 500x500 -gravity center -crop 500x500+0+0 +repage $finalPath2");
			
			exec ( "convert -density 200  \"{$pdfWithPath}[0]\" -append -resize 150x150 -background white -flatten $finalPath" );
			exec ( "convert -density 200  \"{$pdfWithPath}[0]\" -append -resize 400x400 -background white -flatten $finalPath2" );
			return true;
			// }
			// KIMENEMARK BEGIN
			$pdfpage = 1;
			// $basepath = $_SERVER['DOCUMENT_ROOT'] . '/' . $destination . '/';
			$basepath = $destination . '/';
			// echo $basepath; exit;
			$pdf_name = $basepath . $newNme;
			$jpgname = $basepath . 'thumb/' . basename ( $newNme, '.pdf' ) . '.jpg';
			$gsjpgname = $basepath . 'tmp/' . basename ( $newNme, '.pdf' ) . '.jpg';
			
			$jpgname2 = $basepath . 'medium/' . basename ( $newNme, '.pdf' ) . '.jpg';
			$gsjpgname2 = $basepath . 'tmp/' . basename ( $newNme, '.pdf' ) . '.jpg';
			
			/*
			 * $gscommand = '/usr/bin/gs -sDEVICE=jpeg -sCompression=lzw -r300x300 -dNOPAUSE -dFirstPage=' . $pdfpage . ' -dLastPage=' . $pdfpage . ' -sOutputFile="' . $gsjpgname . '" ' . $pdf_name;
			 * $command = '/usr/bin/convert -define jpeg:size=60x60 ' . $gsjpgname . ' -colorspace RGB -thumbnail 100x150 -gravity center -crop 100x150+0+0 ' . $jpgname;
			 * exec($gscommand);
			 * exec($command);
			 * unlink($gsjpgname);
			 *
			 *
			 * $gscommand2 = '/usr/bin/gs -sDEVICE=jpeg -sCompression=lzw -r300x300 -dNOPAUSE -dFirstPage=' . $pdfpage . ' -dLastPage=' . $pdfpage . ' -sOutputFile="' . $gsjpgname2 . '" ' . $pdf_name;
			 * $command2 = '/usr/bin/convert -define jpeg:size=600x600 ' . $gsjpgname2 . ' -colorspace RGB -thumbnail 600x600 -gravity center -crop 600x600+0+0 ' . $jpgname2;
			 * exec($gscommand2);
			 * exec($command2);
			 * unlink($gsjpgname2);
			 */
			
			$gscommand = '/usr/bin/gs -sDEVICE=jpeg -sCompression=lzw -r300x300  -dNOPAUSE -dFirstPage=' . $pdfpage . ' -dLastPage=' . $pdfpage . ' -sOutputFile="' . $gsjpgname . '" ' . $pdf_name . ' 2>&1';
			$command = '/usr/bin/convert -define jpeg:size=60x60 ' . $gsjpgname . ' -colorspace RGB -thumbnail 100x150 -gravity center -crop 100x150+0+0 ' . $jpgname . ' 2>&1';
			exec ( $gscommand, $output, $retcode );
			exec ( $command, $output, $retcode );
			unlink ( $gsjpgname );
			
			if ($retcode != 0) {
				/*
				 * printf("Error executing command:\n");
				 * echo $output . "\n";
				 * exit;
				 */
				ZSELEX_Util::logError ( 'pdfErrors.log', '', print_R ( $output, TRUE ) );
			}
			
			$gscommand2 = '/usr/bin/gs -sDEVICE=jpeg -sCompression=lzw -r300x300  -dNOPAUSE -dFirstPage=' . $pdfpage . ' -dLastPage=' . $pdfpage . ' -sOutputFile="' . $gsjpgname2 . '" ' . $pdf_name . ' 2>&1';
			$command2 = '/usr/bin/convert -define jpeg:size=600x600 ' . $gsjpgname2 . ' -colorspace RGB -thumbnail 600x600 -gravity center -crop 600x600+0+0 ' . $jpgname2 . ' 2>&1';
			exec ( $gscommand2, $output, $retcode );
			exec ( $command2, $output, $retcode );
			unlink ( $gsjpgname2 );
			
			if ($retcode != 0) {
				ZSELEX_Util::logError ( 'pdfErrors.log', '', print_R ( $output, TRUE ) );
			}
			
			// echo "$basePath\n\n";
			// echo "$pdfDirectory\n\n";
			// echo "$gsjpgname\n\n";
			// echo "$gscommand\n\n";
			// echo "$command\n\n";
			// exit;
			// exec($gscommand);
			// exec($command);
			// unlink($gsjpgname);
			// KIMENEMARK END
		} catch ( Exception $e ) {
			// echo 'Caught exception: ', $e->getMessage(), "\n";
			ZSELEX_Util::logError ( 'pdfErrors.log', '', "CATCH ERROR: " . $e->getMessage () );
		}
		return true;
	}
	public function full_medium_thumb($args) {
		$filename = $args ['filename'];
		$destination = $args ['destination'];
		list ( $orig_width, $orig_height ) = getimagesize ( pnGetBaseURL () . $destination . $filename );
		
		$allowedExtensions = array (
				'png',
				'jpg',
				'gif',
				'jpeg' 
		);
		$ex = end ( explode ( ".", $filename ) );
		if (! in_array ( $ex, $allowedExtensions )) {
			// return LogUtil::registerError($this->__f('Error! Invalid file type: %1$s', $ex));
		}
		$modvariable = $this->getVars ();
		
		$fullWidth = ! empty ( $modvariable ['fullimagewidth'] ) ? $modvariable ['fullimagewidth'] : 1024;
		$fullHeight = ! empty ( $modvariable ['fullimageheight'] ) ? $modvariable ['fullimageheight'] : 768;
		if ($fullWidth > $orig_width) {
			$fullWidth = $orig_width;
		}
		if ($fullHeight > $orig_height) {
			$fullHeight = $orig_height;
		}
		$medWidth = ! empty ( $modvariable ['medimagewidth'] ) ? $modvariable ['medimagewidth'] : 400;
		$medHeight = ! empty ( $modvariable ['medimageheight'] ) ? $modvariable ['medimageheight'] : 400;
		if ($medWidth > $orig_width) {
			$medWidth = $orig_width;
		}
		if ($medHeight > $orig_height) {
			$medHeight = $orig_height;
		}
		
		$thumbWidth = ! empty ( $modvariable ['thumbimagewidth'] ) ? $modvariable ['thumbimagewidth'] : 150;
		$thumbHeight = ! empty ( $modvariable ['thumbimageheight'] ) ? $modvariable ['thumbimageheight'] : 150;
		
		if ($thumbWidth > $orig_width) {
			$thumbWidth = $orig_width;
		}
		if ($thumbHeight > $orig_height) {
			$thumbHeight = $orig_height;
		}
		
		// if (!in_array($ex, $allowedExtensions)) {
		require_once ('modules/ZSELEX/lib/vendor/ImageResize.php');
		$resizeObj = new ImageResize ( $destination . $filename );
		$resizeObj->resizeImage ( $fullWidth, $fullHeight );
		$resizeObj->saveImage ( $destination . 'fullsize/' . $filename, 100 );
		
		$resizeObj->resizeImage ( $medWidth, $medHeight );
		$resizeObj->saveImage ( $destination . 'medium/' . $filename, 100 );
		
		$resizeObj->resizeImage ( $thumbWidth, $thumbHeight );
		$resizeObj->saveImage ( $destination . 'thumb/' . $filename, 100 );
		
		unlink ( $destination . $filename );
		// }
		
		return true;
	}
}

// end class def
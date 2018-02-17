<?php

/*
 * $url = "http://www.example.com/images/something.gif";
 * $r = 255;
 * $g = 255;
 * $b = 255;
 * resize($url, 220, 275, "/var/www/html/images/test.jpg", $r, $g, $b);
 */
class R2resize { // image resize....
	function resize($url, $box_w, $box_h, $savePath, $r, $g, $b) {
		$background = ImageCreateTrueColor ( $box_w, $box_h );
		$color = imagecolorallocate ( $background, $r, $g, $b );
		imagefill ( $background, 0, 0, $color );
		$image = $this->open_image ( $url, $r, $g, $b );
		if ($image === false) {
			die ( 'Unable to open image' );
		}
		$w = imagesx ( $image );
		$h = imagesy ( $image );
		$ratio = $w / $h;
		$target_ratio = $box_w / $box_h;
		if ($ratio > $target_ratio) {
			$new_w = $box_w;
			$new_h = round ( $box_w / $ratio );
			$x_offset = 0;
			$y_offset = round ( ($box_h - $new_h) / 2 );
		} else {
			$new_h = $box_h;
			$new_w = round ( $box_h * $ratio );
			$x_offset = round ( ($box_w - $new_w) / 2 );
			$y_offset = 0;
		}
		$insert = ImageCreateTrueColor ( $new_w, $new_h );
		imagecopyResampled ( $insert, $image, 0, 0, 0, 0, $new_w, $new_h, $w, $h );
		imagecopymerge ( $background, $insert, $x_offset, $y_offset, 0, 0, $new_w, $new_h, 100 );
		imagejpeg ( $background, $savePath, 100 );
		imagedestroy ( $insert );
		imagedestroy ( $background );
	}
	function open_image($file, $r = '255', $g = '255', $b = '255') {
		$size = getimagesize ( $file );
		
		switch ($size ["mime"]) {
			case "image/jpeg" :
				$im = imagecreatefromjpeg ( $file ); // jpeg file
				break;
			case "image/gif" :
				$im = imagecreatefromgif ( $file ); // gif file
				imageAlphaBlending ( $im, false );
				imageSaveAlpha ( $im, true );
				$background = imagecolorallocate ( $im, 0, 0, 0 );
				imagecolortransparent ( $im, $background );
				
				$color = imagecolorallocate ( $im, $r, $g, $b );
				for($i = 0; $i < imagesy ( $im ); $i ++) {
					for($j = 0; $j < imagesx ( $im ); $j ++) {
						$rgb = imagecolorat ( $im, $j, $i );
						if ($rgb == 2) {
							imagesetpixel ( $im, $j, $i, $color );
						}
					}
				}
				
				break;
			case "image/png" :
				$im = imagecreatefrompng ( $file ); // png file
				$background = imagecolorallocate ( $im, 0, 0, 0 );
				imageAlphaBlending ( $im, false );
				imageSaveAlpha ( $im, true );
				imagecolortransparent ( $im, $background );
				$color = imagecolorallocate ( $im, $r, $g, $b );
				for($i = 0; $i < imagesy ( $im ); $i ++) {
					for($j = 0; $j < imagesx ( $im ); $j ++) {
						$rgb = imagecolorat ( $im, $j, $i );
						if ($rgb == 2) {
							imagesetpixel ( $im, $j, $i, $color );
						}
					}
				}
				
				break;
			default :
				$im = false;
				break;
		}
		return $im;
	}
}

?>

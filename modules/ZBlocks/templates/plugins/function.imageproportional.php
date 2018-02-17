<?php

function smarty_function_imageproportional($args, &$smarty) {

    $image = $args['image'];
    $path = $args['path'];
    $height = $args['height'];
    $width = $args['width'];
    $baseurl = pnGetBaseURL();

    $imagepath = $path . '/' . str_replace(" ", "%20", $image);
    // $imagepath = $path . '/' . $image;
    //echo $imagepath;
    $img_args = array(
        'imagepath' => $imagepath,
        'setheight' => $height,
        'setwidth' => $width
    );

    // $new_resize = ModUtil::apiFunc('ZSELEX', 'admin', 'imageProportional', $img_args);
    $new_resize = imageProportional($img_args);
    $dimensions = "style='width:$new_resize[new_width]px;height:$new_resize[new_height]px'";
    //$dimensions = "height='" . $new_resize['new_height'] . "'  width='" . $new_resize['new_width'] . "'";

    $smarty->assign("imagedimensions", $dimensions);



    //return $dimensions;
}

/**
 * Make the images proportional
 * Balances the hight and width of the image with clarity
 * Outputs the balanced height and width
 */
function imageProportional($args) {
    //  echo "<pre>"; print_r($args); echo "</pre>";
    $setheight = $args['setheight'];
    $setwidth = $args['setwidth'];
    $imagepath = $args['imagepath'];
    //echo $imagepath . '<br>';

    list($width, $height, $type, $attr) = @getimagesize($imagepath);

    $AW = $width; // Actual Width
    $AH = $height; // Actual Height
    // echo "Actual Width :" . $AW . " " . "Actual Height :" . $AH . '<br>';exit;


    $H = '';
    $W = '';

    if ($AH < $setheight && $AW < $setwidth) {
        $new_height = round($AH);
        $new_width = round($AW);
    } elseif ($AH > $setheight && $AW < $setwidth) {

        $H = $setheight;
        $W = $AW * (($setheight * 100) / $AH) / 100;

        $new_height = round($H);
        $new_width = round($W);
    } elseif ($AH < $setheight && $AW > $setwidth) {

        $W = $setwidth;
        $H = $AH * (($setwidth * 100) / $AW) / 100;
        $new_height = round($H);
        $new_width = round($W);
    } elseif ($AH > $setheight && $AW > $setwidth) {
        $H = $setheight;
        $W = $AW * (($setheight * 100) / $AH) / 100;
        $WTmp = $W;
        if ($W > $setwidth) {
            $W = $setwidth;
            $H = $H * (($setwidth * 100) / $WTmp) / 100;
        }
        $new_height = round($H);
        $new_width = round($W);
    } elseif ($AH >= $setheight && $AW >= $setwidth) {
        $H = $setheight;
        $W = $AW * (($setheight * 100) / $AH) / 100;
        $WTmp = $W;
        if ($W > $setwidth) {
            $W = $setwidth;
            $H = $H * (($setwidth * 100) / $WTmp) / 100;
        }
        $new_height = round($H);
        $new_width = round($W);
    }


    $output['new_height'] = $new_height;
    $output['new_width'] = $new_width;
    // echo "<pre>"; print_r($output); echo "</pre>";
    return $output;
}

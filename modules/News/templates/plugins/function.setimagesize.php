<?php

function smarty_function_setimagesize($args, &$smarty) {
    $dom = ZLanguage::getModuleDomain('ZSELEX');
    //echo "SID" . $args['sid'];
    //echo "picture : " . $args['picture'];
    //echo "path : " . $args['imagepath'];
    $domain = pnGetBaseURL();
    $pictureCount = $args['picture'];
    $imagePath = $args['imagepath'];
    $resizeHeight = '';
    $resizewidth = '';

    $imageFile = $domain . '/' . $imagePath . '/' . str_replace(" ", "%20", "pic_sid" . $args['sid'] . "-0-norm.jpg");

    $file_headers = @get_headers($imageFile);

//    if ($file_headers[0] == 'HTTP/1.0 404 Not Found') {
//        echo "The file $imageFile does not exist";
//    } else if ($file_headers[0] == 'HTTP/1.0 302 Found') {
//        echo "The file $imageFile does not exist, and I got redirected to a custom 404 page..";
//    } else {
//        echo "The file $imageFile exists";
//    }

    if ($file_headers[0] != 'HTTP/1.0 404 Not Found' || $file_headers[0] != 'HTTP/1.0 302 Found') {

        list($width, $height, $type, $attr) = @getimagesize($imageFile);
        $AW = $width;
        $AH = $height;

        $H = '';
        $W = '';


        if ($AH < 88 && $AW < 200) {
            // echo "comes here";
        }

        if ($AH > 88 && $AW < 200) {

            $H = 88;
            $W = $AW * ((88 * 100) / $AH) / 100;

            $resizeHeight = round($H);
            $resizewidth = round($W);
        }

        if ($AH < 88 && $AW > 200) {

            $W = 200;
            $H = $AH * ((200 * 100) / $AW) / 100;
            $resizeHeight = round($H);
            $resizewidth = round($W);
        }

        if ($AH > 88 && $AW > 200) { //used
            $H = 88;
            $W = $AW * ((88 * 100) / $AH) / 100;

            $WTmp = $W;
            if ($W > 200) {
                $W = 200;
                $H = $H * ((200 * 100) / $WTmp) / 100;
            }

            $resizeHeight = round($H);
            $resizewidth = round($W);
        }



        $smarty->assign("height", $resizeHeight);
        $smarty->assign("width", $resizewidth);
    }
    //return $plugincontent;
}
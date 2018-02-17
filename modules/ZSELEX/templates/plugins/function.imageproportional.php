<?php

function smarty_function_imageproportional($args, &$smarty)
{
    $image   = $args ['image'];
    $path    = $args ['path'];
    $height  = $args ['height'];
    $width   = $args ['width'];
    $baseurl = pnGetBaseURL();

    $imagepath = $path.'/'.str_replace(" ", "%20", $image);
    // $imagepath = $path . '/' . $image;
    // echo $imagepath;
    $img_args  = array(
        'imagepath' => $imagepath,
        'setheight' => $height,
        'setwidth' => $width
    );

    $new_resize    = ModUtil::apiFunc('ZSELEX', 'admin', 'imageProportional',
            $img_args);
    $dimensions    = "style='width:$new_resize[new_width]px;height:$new_resize[new_height]px'";
    $imageProperty = "height='".$new_resize['new_height']."' width='".$new_resize['new_width']."'";

    $smarty->assign("imagedimensions", $dimensions);
    $smarty->assign("newHeight", $new_resize['new_height']);
    $smarty->assign("newWidth", $new_resize['new_width']);
    $smarty->assign("imageProperty", $imageProperty);

    // return $dimensions;
}

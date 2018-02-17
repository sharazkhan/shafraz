<?php

function smarty_function_fileexist($args, &$smarty) {
    $filename = $args['path'];
    if (@getimagesize($filename)) {
        $return = true;
    } else {
        $return = false;
    }

    $smarty->assign("fileexist", $return);
}

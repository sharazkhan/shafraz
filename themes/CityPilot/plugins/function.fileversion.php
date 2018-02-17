<?php

function smarty_function_fileversion($args, &$smarty) {

    // $ver = '?v=1.0';
    $ver = ModUtil::apiFunc('ZSELEX', 'plugin', 'fileVersion');
    $smarty->assign("ver", $ver);
}

<?php

function smarty_function_submitarticle($params, &$smarty) {
   // echo "comes here";
    $perm = SecurityUtil::checkPermission('News::', '::', ACCESS_ADMIN);
    $link = $params['link'];
    $smarty->assign("perm", $perm);
    $smarty->assign("link", $link);
}

<?php

function smarty_function_searchbreadcrum($params, &$smarty) {

    // echo "<pre>"; print_r($params); echo "</pre>";

    // echo "hellooowwwww"; exit;
    //unset($smarty);
    // return ZSELEX_Controller_User::postToWall();
    return ModUtil::apiFunc('ZSELEX', 'plugin', 'searchBreadcrum', $params);
}
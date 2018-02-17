<?php

function smarty_function_shoprating($params, &$smarty) {
    //return;
    return ModUtil::apiFunc('ZSELEX', 'plugin', 'showRating', $params);
}
<?php

function smarty_function_getregionid($params, &$smarty) {
    $append = '';
    $regionName = $params['regionName'];
    $regId = $params['regId'];
    if (!empty($regId)) {
        $append .= "WHERE region_id='" . $regId . "'";
    } elseif (!empty($regionName)) {
        $append .= "WHERE region_name='" . $regionName . "' OR region_name LIKE  '%" . $regionName . "%'";
    }
    $sql = "SELECT region_id FROM zselex_region" . " " . $append;

    //echo $sql; return;
    $query = DBUtil::executeSQL($sql);
    $result = $query->fetch();
    if ($result['region_id'] > 0) {
        $region_id = $result['region_id'];
    } else {
        $region_id = '-1';
    }

    echo $region_id;
    // $smarty->assign("perm", $perm);
    // $smarty->assign("link", $link);
}

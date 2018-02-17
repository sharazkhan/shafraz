<?php

function smarty_function_shopdetails($args, &$smarty) {

    // exit;
    //echo "<pre>"; print_r($_SESSION['cart']); echo "</pre>"; exit;
    $em = ServiceUtil::getService('doctrine.entitymanager');
    $shop_id = $args['shop_id'];
    $loguser = UserUtil::getVar('uid');
    $loguser = !empty($loguser) ? $loguser : 0;
    $user_id = $loguser;
    if (empty($shop_id)) {
        return;
    }

    $getArgs = array(
        'entity' => 'ZSELEX_Entity_Shop',
        'fields' => array('a.shop_id', 'a.shop_name', 'b.city_name', 'c.aff_id', 'c.aff_image'),
        'joins' => array('LEFT JOIN a.city b', 'LEFT JOIN a.aff_id c'),
        'where' => array('a.shop_id' => $shop_id)
    );
    $result = $em->getRepository('ZSELEX_Entity_Shop')->get($getArgs);

    //  echo "<pre>"; print_r($result); echo "</pre>";
    $shop_name = $result['shop_name'];
    $city_name = $result['city_name'];

    //Permission
    //$perm = ModUtil::apiFunc('ZSELEX', 'admin', 'shopPermission', array('shop_id' => $shop_id, 'user_id' => $user_id));
    //$perm = $_REQUEST['perm'];
    //  System::queryStringSetVar('perm', $perm);
    // $perm = ModUtil::apiFunc('ZSELEX', 'admin', 'shopPermission', array('shop_id' => $shop_id, 'user_id' => $user_id));
    //$perm = $_REQUEST['perm'];
    $perm = FormUtil::getPassedValue('perm', null, 'REQUEST');

    $smarty->assign("shop_name", $shop_name);
    $smarty->assign("city_name", $city_name);
    $smarty->assign('aff_id', $result['aff_id']);
    $smarty->assign('aff_image', $result['aff_image']);
    $smarty->assign("perm", $perm);
}

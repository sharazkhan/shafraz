<?php

function smarty_function_servicecartcount($args, &$smarty) {
    $em = ServiceUtil::getService('doctrine.entitymanager');
    $repo = $em->getRepository('ZSELEX_Entity_Shop');
    $dom = ZLanguage::getModuleDomain('ZSELEX');
    //$plugincontent = __('ZSELEX plugin', $dom) . "<br />";
    $user_id = UserUtil::getVar('uid');
    $servicecart_count = 0;
    if (!$user_id) {
        return $servicecart_count;
    }
    /*
      $servicecart_count = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $args = array(
      'table' => 'zselex_basket',
      "where" => "user_id=$user_id"
      ));
     */

    $servicecart_count = $repo->getCount(null, 'ZSELEX_Entity_ServiceBasket', 'basket_id', array('a.user_id' => $user_id));
    // echo $service_count;
    //$this->view->assign('servicecart_count', $servicecart_count);
    return $servicecart_count;
}

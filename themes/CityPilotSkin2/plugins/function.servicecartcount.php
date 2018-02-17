<?php

function smarty_function_servicecartcount($args, &$smarty) {
    $dom = ZLanguage::getModuleDomain('ZSELEX');
    //$plugincontent = __('ZSELEX plugin', $dom) . "<br />";
    $user_id = UserUtil::getVar('uid');
    $servicecart_count = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $args = array(
                'table' => 'zselex_basket',
                "where" => "user_id=$user_id"
            ));
    // echo $service_count;
    //$this->view->assign('servicecart_count', $servicecart_count);
    return $servicecart_count;
}
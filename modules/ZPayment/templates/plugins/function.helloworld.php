<?php

function smarty_function_ZPayment($args, &$smarty)
{
    $dom = ZLanguage::getModuleDomain('ZPayment');
    $plugincontent = __('ZPayment plugin', $dom) . "<br />";
    return $plugincontent;
}
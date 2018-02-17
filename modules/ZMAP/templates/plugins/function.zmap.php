<?php

function smarty_function_zmap($args, &$smarty)
{
    $dom = ZLanguage::getModuleDomain('ZMAP');
    $plugincontent = __('ZMAP plugin', $dom) . "<br />";
    return $plugincontent;
}
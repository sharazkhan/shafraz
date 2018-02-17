<?php

function smarty_function_ZBlocks($args, &$smarty)
{
    $dom = ZLanguage::getModuleDomain('ZBlocks');
    $plugincontent = __('ZBlocks plugin', $dom) . "<br />";
    return $plugincontent;
}
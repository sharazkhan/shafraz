<?php
function smarty_function_exclusiveevents($args, &$smarty) {
	$dom = ZLanguage::getModuleDomain ( 'ZSELEX' );
	$plugincontent = __ ( 'ZSELEX plugin', $dom ) . "<br />";
	return $plugincontent;
}
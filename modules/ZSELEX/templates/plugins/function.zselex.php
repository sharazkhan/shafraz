<?php
function smarty_function_zselex($args, &$smarty) {
	$dom = ZLanguage::getModuleDomain ( 'ZSELEX' );
	$plugincontent = __ ( 'ZSELEX plugin', $dom ) . "<br />";
	return $plugincontent;
}
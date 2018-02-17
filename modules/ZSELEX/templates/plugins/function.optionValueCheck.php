<?php
function smarty_function_optionValueCheck($args, &$smarty) {
	// $dom = ZLanguage::getModuleDomain('ZSELEX');
	// $plugincontent = __('ZSELEX plugin', $dom) . "<br />";
	$option_id = $args ['option_id'];
	$em = ServiceUtil::getService ( 'doctrine.entitymanager' );
	$repo = $em->getRepository ( 'ZSELEX_Entity_Shop' );
	$count = $repo->getCount ( null, 'ZSELEX_Entity_ProductOptionValue', 'option_value_id', array (
			'a.option' => $option_id 
	) );
	// return $count;
	if ($count < 1) {
		echo "<br><font color=red>" . __ ( 'option values are empty' ) . "</font>";
	}
}
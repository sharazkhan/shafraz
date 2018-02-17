<?php
function smarty_function_seturl($args, &$smarty) {
	$dom = ZLanguage::getModuleDomain ( 'ZSELEX' );
	$name = '';
	
	$shop_id = $args ['shop_id'];
	$url = ModUtil::url ( 'ZSELEX', 'user', 'shop', array (
			'shop_id' => $shop_id 
	) );
	
	$url = pnGetBaseURL () . $url;
	
	$smarty->assign ( "url", $url );
}
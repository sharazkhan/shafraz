<?php
function smarty_function_ownername($args, &$smarty) {
	
	// print_r($args);
	$shop_id = $args ['shop_id'];
	
	$ownerName = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getOwner', $args = array (
			'shop_id' => $shop_id 
	) );
	
	// $titlelink = "<a href=''></a>";
	
	return $ownerName;
}
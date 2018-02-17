<?php
function smarty_function_ownerinfo($args, &$smarty) {
	
	// print_r($args);
	$shop_id = $args ['shop_id'];
	$owner = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getOwnerInfo', array (
			'shop_id' => $shop_id 
	) );
	
	// $titlelink = "<a href=''></a>";
	
	$smarty->assign ( "owner", $owner );
}
<?php
function smarty_function_fbpostonwall($params, &$smarty) {
	
	// echo "<pre>"; print_r($params); echo "</pre>";
	$shop_id = $params ['shop_id'];
	if (empty ( $shop_id )) {
		return;
	}
	$serviceExist = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'serviceExistBlock', $args = array (
			'shop_id' => $shop_id,
			'type' => 'fbwallpost' 
	) );
	
	// echo "post exit : ". $serviceExist;
	if ($serviceExist < 1) {
		return;
	}
	// echo "post here";
	unset ( $smarty );
	// return ZSELEX_Controller_User::postToWall();
	return ModUtil::apiFunc ( 'ZSELEX', 'plugin', 'postToWall', $params );
}
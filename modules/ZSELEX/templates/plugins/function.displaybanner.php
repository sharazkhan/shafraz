<?php
function smarty_function_displaybanner($args, &$smarty) {
	
	// echo "hello";
	$width = $args ['width'];
	$shop_id = $args ['shop_id'];
	if (empty ( $shop_id ) || ! ( int ) $shop_id || $_REQUEST ['module'] != 'ZSELEX') {
		return;
	}
	
	$serviceExist = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'serviceExistBlock', $args = array (
			'shop_id' => $shop_id,
			'type' => 'minisitebanner' 
	) );
	
	// echo $serviceExist;
	if ($serviceExist < 1) {
		return;
	}
	// echo "hello";
	$getBanner = ModUtil::apiFunc ( 'ZSELEX', 'user', 'get', $args = array (
			'table' => 'zselex_shop_banner',
			'where' => "shop_id=$shop_id" 
	) );
	$ownerName = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getOwner', $args = array (
			'shop_id' => $shop_id 
	) );
	
	$image = "";
	if (! empty ( $getBanner ['banner_image'] )) {
		if ($width) {
			$style = "style=width:100%";
		}
		$image = "<img src=zselexdata/$ownerName/banner/resized/$getBanner[banner_image] $style>";
	} else {
		$image = "";
	}
	return $image;
}
<?php
function smarty_function_ownerfolderquota($args, &$smarty) {
	$shop_id = $args ['shop_id'];
	$diskquota = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'checkDiskquota', $args = array (
			'shop_id' => $shop_id 
	) );
	$ownerfolderquota = ZSELEX_Controller_Admin::display_size ( $diskquota ['sizelimit'] );
	return $ownerfolderquota;
	// $smarty->assign("ownerfolderquota", $ownerfolderquota);
}
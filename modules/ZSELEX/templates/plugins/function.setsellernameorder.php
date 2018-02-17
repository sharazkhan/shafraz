<?php
function smarty_function_setsellernameorder($args, &$smarty) {
	// $dom = ZLanguage::getModuleDomain('ZSELEX');
	// $name = '';
	// echo $args['key'];
	$products = $args ['value'];
	$shop_id = $args ['shop_id'];
	// echo "<pre>"; print_r($products); echo "</pre>";
	$em = ServiceUtil::getService ( 'doctrine.entitymanager' );
	$repo = $em->getRepository ( 'ZSELEX_Entity_Shop' );
	
	if ($key != 'zselex') {
		/*
		 * $seller = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow', $args =
		 * array('table' => 'zselex_shop_owners a , users b', 'where' => array("a.user_id=b.uid", "a.shop_id=$shop_id"), 'groupby' => 'b.uid'));
		 *
		 */
		$seller = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getOwnerInfo', $args = array (
				'shop_id' => $shop_id 
		) );
		// $seller
		$name = $seller ['uname'];
		
		foreach ( $products as $v ) {
			$grandPrice [] = $v ['FINALPRICE'];
		}
		$GRANDTOTAL = array_sum ( $grandPrice );
	} else {
		foreach ( $products as $v ) {
			$grandPrice [] = $v ['FINALPRICE'];
		}
		$GRANDTOTAL = array_sum ( $grandPrice );
		$name = 'ZSELEX';
	}
	$smarty->assign ( "sellername", $name );
	$smarty->assign ( "GRANDSUM", $GRANDTOTAL );
	
	// return $plugincontent;
}
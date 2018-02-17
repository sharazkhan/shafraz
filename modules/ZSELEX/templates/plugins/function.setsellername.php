<?php
function smarty_function_setsellername($args, &$smarty) {
	// $dom = ZLanguage::getModuleDomain('ZSELEX');
	// echo "<pre>"; print_r($args); echo "</pre>";
	$em = ServiceUtil::getService ( 'doctrine.entitymanager' );
	$repo = $em->getRepository ( 'ZSELEX_Entity_Shop' );
	// $name = '';
	// echo $args['key'];
	$products = $args ['value'];
	// echo "<pre>"; print_r($products); echo "</pre>";
	$key = $args ['key'];
	
	if ($key != 'zselex') {
		/*
		 * $seller = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray', $args =
		 * array(
		 * 'table' => 'zselex_shop_owners a , users b , zselex_shop c',
		 * 'fields' => array('b.uname', 'c.shop_name'),
		 * 'where' => array("a.user_id=b.uid", "a.shop_id=$key", "c.shop_id=$key"),
		 * 'groupby' => 'b.uid')
		 * );
		 */
		$getArgs = array (
				'entity' => 'ZSELEX_Entity_Shop',
				'fields' => array (
						'a.shop_name' 
				),
				'where' => array (
						'a.shop_id' => $key 
				) 
		);
		$getShop = $repo->get ( $getArgs );
		
		$ownerName = ModUtil::apiFunc ( 'ZSELEX', 'user', 'getOwnerRealName', array (
				'shop_id' => $key 
		) );
		$shop_id = $key;
		// $name = $seller[0]['uname'];
		$name = $ownerName;
		// $shopname = $seller[0]['shop_name'];
		$shopname = $getShop ['shop_name'];
		// $options = json_decode($seller[0]['cart_content'] , true);
		// echo "<pre>"; print_r($options); echo "</pre>";
		
		foreach ( $products [$key] as $v ) {
			// echo $v['cart_content'];
			/*
			 * if ($v['cart_content'] != '') {
			 * $options = json_decode($v['cart_content'], true);
			 * //echo "<pre>"; print_r($options); echo "</pre>";
			 * if (!empty($options)) {
			 * foreach ($options as $ov) {
			 * $value_info = ModUtil::apiFunc('ZSELEX', 'user', 'get', array(
			 * 'table' => 'zselex_product_to_options_values',
			 * 'where' => "product_to_options_value_id=$ov[valueID]",
			 * 'fields' => array('product_to_options_value_id', 'price')
			 * ));
			 * $total+=$value_info['price'];
			 * }
			 * }
			 * }
			 */
			// $grandPrice[] = $v['final_price'];
			$grandPrice += $v ['final_price'];
			// $cantbuycart[] = $v['CANTBUY'];
		}
		// $cantbuy = array_sum($cantbuycart);
		// echo $cantbuy_status . '<br>';
		// $GRANDTOTAL = array_sum($grandPrice);
		$GRANDTOTAL = $grandPrice;
	} else {
		foreach ( $products [$key] as $v ) {
			$grandPrice [] = $v ['FINALPRICE'];
		}
		$GRANDTOTAL = array_sum ( $grandPrice );
		$name = 'ZSELEX';
	}
	$smarty->assign ( "sellername", $name );
	$smarty->assign ( "shopname", $shopname );
	$smarty->assign ( "shop_id", $shop_id );
	$smarty->assign ( "cantbuy", $cantbuy );
	$smarty->assign ( "GRANDSUM", $GRANDTOTAL );
	
	// return $plugincontent;
}
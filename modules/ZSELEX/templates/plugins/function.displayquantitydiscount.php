<?php

/**
 * Plugin to display quantity discount for a product
 * 
 * @param array $args
 * int product_id
 * @param Smarty Instance(default) $smarty
 * @return string
 */
function smarty_function_displayquantitydiscount($args, &$smarty) {
	// $dom = ZLanguage::getModuleDomain('ZSELEX');
	// echo "<pre>"; print_r($args); echo "</pre>";
	$em = ServiceUtil::getService ( 'doctrine.entitymanager' );
	$repo = $em->getRepository ( 'ZSELEX_Entity_Product' );
	$optionArr = array ();
	// $options = json_decode($args['options'], true);
	$product_id = $args ['product_id'];
	$page = $args ['page'];
	if (! $product_id) {
		return '';
	}
	// echo $args['key'];
	$quantity_discount = ModUtil::apiFunc ( 'ZSELEX', 'product', 'getQtyDiscounts', array (
			'product_id' => $product_id 
	) );
	if (empty ( $quantity_discount )) {
		return '';
	}
	// echo "<pre>"; print_r($quantity_discount); echo "</pre>";
	$header_text = '';
	$footer_text = '';
	$content = '';
	if ($page == 'cart') {
		// echo 'Comes here';
		$header_text = '<tr><td colspan=6 style="border-top:0px"><i><font color=red>' . $smarty->__ ( 'Please note you are entitled to the following discount(s) on the above product:' ) . '</i><br>';
	} else {
		$header_text = '<i>' . $smarty->__ ( 'Discount:' ) . '</i><br>';
	}
	$today = date ( "Y-m-d" );
	$discount_text = '';
	if ($quantity_discount) {
		foreach ( $quantity_discount as $value ) {
			if (! empty ( $value ['start_date'] ) && ! empty ( $value ['end_date'] )) {
				if ($today >= $value ['start_date'] && $today <= $value ['end_date']) {
					$discount_text .= content ( $value, $page, $smarty );
				}
			} else {
				$discount_text .= content ( $value, $page, $smarty );
			}
		}
		if ($page == 'cart') {
			$footer_text = "</font></td></tr>";
		} else {
			$footer_text = '<br>';
		}
	}
	if (strlen ( $discount_text ) > 0) {
		$content = $header_text . $discount_text . $footer_text;
	}
	
	// echo $discount_text;
	return $content;
}
function content($value, $page, $smarty) {
	if (empty ( $value ['quantity'] )) {
		return '';
	}
	$last_char = substr ( $value ['discount'], - 1 );
	if ($last_char == "%") {
		$discount = $value ['discount'];
	} else {
		$curr_args = array (
				'amount' => $value ['discount'],
				'currency_symbol' => '',
				'decimal_point' => ',',
				'thousands_sep' => '.',
				'precision' => '2' 
		);
		$price = ModUtil::apiFunc ( 'ZSELEX', 'user', 'number2currency', $curr_args );
		$discount = $price . ' DKK';
	}
	$untill = "";
	if ($value ['end_date']) {
		$date_explode = explode ( '-', $value ['end_date'] );
		$formated_date = $date_explode [2] . '-' . $date_explode [1] . '-' . $date_explode [0];
		$untill = " " . $smarty->__ ( 'until' ) . " " . $formated_date;
	}
	if ($page == 'cart') {
		$discount_text .= '<div><i>' . $smarty->__ ( 'Get' ) . " " . $discount . " " . $smarty->__ ( 'if you order' ) . " " . $value ['quantity'] . " " . $smarty->__ ( 'pcs or more' ) . $untill . "</i></div>";
	} else {
		$discount_text .= '<i>' . $discount . " " . $smarty->__ ( 'at' ) . " " . $value ['quantity'] . " " . $smarty->__ ( 'pcs' ) . $untill . "</i><br>";
	}
	
	return $discount_text;
}

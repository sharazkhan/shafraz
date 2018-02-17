<?php
function smarty_function_displayprice($args, &$smarty) {
	
	// echo "hello";
	// echo "<pre>"; print_r($args); echo "</pre>";
	$amount = $args ['amount'];
	$pref = $args ['pref'];
	
	$curr_args = array (
			'amount' => $amount,
			'currency_symbol' => '',
			'decimal_point' => ',',
			'thousands_sep' => '.',
			'precision' => '2' 
	);
	$price = ModUtil::apiFunc ( 'ZSELEX', 'user', 'number2currency', $curr_args );
	if ($pref == true) {
		$prefix = substr ( $amount, 0, 1 );
		if ($prefix == '+') {
			return $prefix . $price;
		} else {
			return $price;
		}
	}
	return $price;
}
<?php
function smarty_function_paymentmessage($args, &$smarty) {
	// $dom = ZLanguage::getModuleDomain('ZSELEX');
	$shop_id = $args ['shop_id'];
	$cart = $args ['cart'];
	$em = ServiceUtil::getService ( 'doctrine.entitymanager' );
	// $repo = $em->getRepository('ZSELEX_Entity_ProductOption');
	$payment_methods = array ();
	$netaxept = $em->getRepository ( 'ZPayment_Entity_Netaxept' )->paymentMode ( array (
			'shop_id' => $shop_id 
	) );
	// echo "<pre>"; print_r($netaxept); echo "</pre>";
	if ($netaxept ['test_mode'] == true) {
		$payment_methods [] = 'Netaxept';
	}
	$paypal = $em->getRepository ( 'ZPayment_Entity_Paypal' )->paymentMode ( array (
			'shop_id' => $shop_id 
	) );
	
	if ($paypal ['test_mode'] == true) {
		// LogUtil::registerError($smarty->__('Your Paypal payment is in test mode'));
		$payment_methods [] = 'Paypal';
	}
	
	$quickpay = $em->getRepository ( 'ZPayment_Entity_QuickPay' )->paymentMode ( array (
			'shop_id' => $shop_id 
	) );
	
	if ($quickpay ['test_mode'] == true) {
		// LogUtil::registerError($smarty->__('Your Quickpay payment is in test mode'));
		$payment_methods [] = 'Quickpay';
	}
	
	$count = count ( $payment_methods );
	if ($count > 0) {
		if ($count < 2) {
			$message = "<font color=red>" . $smarty->__ ( "Payment method " ) . implode ( ' , ', $payment_methods ) . $smarty->__ ( " is running in test mode for this shop" ) . "</font>";
		} else {
			$message = "<font color=red>" . $smarty->__ ( "Payment methods " ) . implode ( ' , ', $payment_methods ) . $smarty->__ ( " are running in test mode for this shop" ) . "</font>";
		}
	}
	
	echo $message;
}

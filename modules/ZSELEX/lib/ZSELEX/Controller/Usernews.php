<?php

// error_reporting(0);
/**
 * Copyright 2013
 *
 * ZSELEX
 * Demonstration of Zikula Module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control User interface
 */
class ZSELEX_Controller_Usernews extends News_Controller_User {
	/**
	 * These constants are applicable for News module override
	 */
	const ACTION_PREVIEW = 0;
	const ACTION_SUBMIT = 1;
	const ACTION_PUBLISH = 2;
	const ACTION_REJECT = 3;
	const ACTION_SAVEPENDING = 4;
	const ACTION_ARCHIVE = 5;
	const ACTION_SAVEDRAFT = 6;
	const ACTION_SAVEDRAFT_RETURN = 8;
	
	/**
	 * main
	 *
	 * main view function for end user
	 * 
	 * @access public
	 */
	public function main() {
		$this->redirect ( ModUtil::url ( 'ZSELEX', 'user', 'view' ) );
	}
	
	/**
	 * view items
	 * This is a standard function to provide an overview of all of the items
	 * available from the module.
	 */
	// public function view() {
	// //echo "Helloooo";
	// $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_OVERVIEW), LogUtil::getErrorMsgPermission());
	//
	// $this->view->assign('external_function', ZSELEX_Util::externalfunction());
	//
	// return $this->view->fetch('user/view.tpl');
	// }
	//
	// public function views() {
	// //echo "Helloooo";
	// $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_OVERVIEW), LogUtil::getErrorMsgPermission());
	//
	// $this->view->assign('external_function', ZSELEX_Util::externalfunction());
	//
	// return $this->view->fetch('user/viewtest.tpl');
	// }
	
	/**
	 * products detail of individual ISHOP Product
	 */
	public function productview($args) {
		
		// echo $modvars.ZConfig.sitename;
		// echo System::getVar('shorturls');
		$this->throwForbiddenUnless ( SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_OVERVIEW ), LogUtil::getErrorMsgPermission () );
		// EventUtil::registerPersistentModuleHandler('ZSELEX', 'user.gettheme', array('ZSELEX_Listener_User', 'getTheme'));
		// echo "<pre>"; print_r($_POST); echo "</pre>";
		// if ($_POST == true) {
		// $_SESSION['theme'] = $_POST['theme'];
		// }
		//
		//
		// if ($_POST == false) {
		// if (($_SESSION['theme'] != $_GET['theme'] && $_GET['theme'] != '')) {
		//
		// return LogUtil::registerError('There was an error in the page');
		//
		// }
		// }
		
		$pid = ( int ) FormUtil::getPassedValue ( 'id', isset ( $args ['id'] ) ? $args ['id'] : null, 'REQUEST' );
		$productTitle = FormUtil::getPassedValue ( 'producttitle', '', 'REQUEST' );
		if (! empty ( $pid )) {
			// echo "comes here";
			$product = DBUtil::selectObjectByID ( 'zselex_products', $pid, 'product_id' );
		} else {
			
			$tables = DBUtil::getTables ();
			$col = $tables ['zselex_products_column'];
			$where = "{$col['urltitle']} = '" . DataUtil::formatForStore ( $productTitle ) . "'";
			$product = DBUtil::selectObject ( 'zselex_products', $where, null, $permFilter = '', null, $args ['SQLcache'] );
			
			// System::queryStringSetVar('sid', $sid);
		}
		
		// $product = DBUtil::selectObjectByID('zselex_products', $pid, 'product_id');
		
		if ($product == false) {
			return LogUtil::registerError ( $this->__ ( 'Error! Type not found.' ) );
		}
		
		$Id = FormUtil::getPassedValue ( 'id', isset ( $args ['id'] ) ? $args ['id'] : null, 'REQUEST' );
		
		$product_id = FormUtil::getPassedValue ( 'products_id', '', 'REQUEST' );
		$qnty = FormUtil::getPassedValue ( 'cart_quantity', '', 'REQUEST' );
		
		$this->view->assign ( 'product', $product );
		$this->view->assign ( 'product_id', $product_id );
		$this->view->assign ( 'quantity', $qnty );
		return $this->view->fetch ( 'user/productview.tpl' );
	}
	public function usercart($args) {
		// System::setVar('Default_Theme', 'SeaBreeze');
		$this->redirect ( ModUtil::url ( 'ZSELEX', 'user', 'cart' ) );
	}
	public static function _getThemeFilterEvent($theme_name, $type) {
		// echo "hereeee";
		$event = new Zikula_Event ( 'user.gettheme', null, array (
				'type' => $type 
		), $theme_name );
		return EventUtil::notify ( $event )->getData ();
	}
	
	/**
	 * This function is for storing product in carts
	 * stores product in bothe cookies and session
	 * returns all the products in the cart
	 */
	public function cart($args) {
		// echo "<pre>"; print_r($_COOKIE['cart']['104']); echo "</pre>"; exit;
		unset ( $_SESSION ['checkoutinfo'] );
		// echo "<pre>"; print_r($_SESSION['checkoutinfo']); echo "</pre>";
		// EventUtil::registerPersistentModuleHandler('ZSELEX', 'module_dispatch.custom_classname', array('ZSELEX_Listener_User', 'customClassname'));
		// $modvars = ModUtil::getVar('News');
		// EventUtil::registerPersistentModuleHandler('ZSELEX', 'user.gettheme', array('ZSELEX_Listener_User', 'getTheme'));
		// EventUtil::registerPersistentModuleHandler('ZSELEX', 'module.users.ui.login.succeeded', array('ZSELEX_Listener_UserLogin', 'succeeded'));
		// echo "<pre>"; print_r($_SESSION['cart']); echo "</pre>";
		// echo "<pre>"; print_r($_COOKIE['cart']); echo "</pre>";
		// echo "<pre>"; print_r($_POST); echo "</pre>";
		// if (!SecurityUtil::checkPermission('ZSELEX::cart', '::', ACCESS_COMMENT)) {
		// throw new Zikula_Exception_Forbidden();
		// }
		// EventUtil::registerPersistentModuleHandler('ZSELEX', 'user.account.create', array('ZSELEX_Listener_User', 'create'));
		// EventUtil::registerPersistentModuleHandler('ZSELEX', 'user.account.delete', array('ZSELEX_Listener_User', 'delete'));
		// unset($_SESSION['cart']);
		
		if (isset ( $_SESSION ['checkoutsession'] )) {
			unset ( $_SESSION ['checkoutsession'] );
		}
		$this->throwForbiddenUnless ( SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_OVERVIEW ), LogUtil::getErrorMsgPermission () );
		$sessionId = session_id ();
		$product_id = FormUtil::getPassedValue ( 'product_id', '', 'REQUEST' );
		$productName = FormUtil::getPassedValue ( 'productName', '', 'REQUEST' );
		$quantity = FormUtil::getPassedValue ( 'cart_quantity', '', 'REQUEST' );
		$productprice = FormUtil::getPassedValue ( 'product_price', '', 'REQUEST' );
		$productdesc = FormUtil::getPassedValue ( 'productDesc', '', 'REQUEST' );
		$productimg = FormUtil::getPassedValue ( 'productImage', '', 'REQUEST' );
		$service = FormUtil::getPassedValue ( 'service', '', 'REQUEST' );
		$shop_id = FormUtil::getPassedValue ( 'shop_id', '', 'REQUEST' );
		
		$sessionCount = 0;
		$cookieCount = 0;
		
		// echo "<pre>"; print_r($_POST); echo "</pre>";
		$exist = 0;
		// /////////////////////////////if shop_id is empty//////////////////////////////////
		foreach ( $_COOKIE ['cart'] as $key => $val ) {
			
			foreach ( $_COOKIE ['cart'] [$key] as $key1 => $val1 ) {
				if ($key == 0) {
					$_COOKIE ['cart'] [$key] = array ();
					// setcookie("cart[$key]", "", time() - 6048000000);
				}
			}
		}
		foreach ( $_SESSION ['cart'] as $key => $val ) {
			if ($key == 0) {
				unset ( $_SESSION ['cart'] [$key] );
			}
		}
		// //////////////////////////////////////////////////////////////////
		// ////////////////////////setting the cart products in session from cookies .
		// ////this happends when the user opens the browser next time/////////////////
		if (empty ( $_SESSION ['cart'] )) {
			
			if (! empty ( $_COOKIE ['cart'] )) {
				// echo "come here"; exit;
				foreach ( $_COOKIE ['cart'] as $key => $val ) {
					// $finalCookie[$key] = json_decode($val, true);
					foreach ( $_COOKIE ['cart'] [$key] as $key1 => $val1 ) {
						$finalCookie [$key] [$key1] = json_decode ( $val1, true );
					}
				}
				
				foreach ( $finalCookie as $key => $val ) {
					foreach ( $finalCookie [$key] as $key1 => $val1 ) {
						$_SESSION ['cart'] [$key] [$key1] = array (
								'PRODUCTID' => $val1 ['PRODUCTID'],
								'PRODUCTNAME' => $val1 ['PRODUCTNAME'],
								'QUANTITY' => $val1 ['QUANTITY'],
								'DESCRIPTION' => $val1 ['DESCRIPTION'],
								'IMAGE' => $val1 ['IMAGE'],
								'REALPRICE' => $val1 ['REALPRICE'],
								'FINALPRICE' => $val1 ['FINALPRICE'] 
						);
					}
				}
			}
		} //
		  // //////////////////////////////////////////////////////////////////////
		  // ////////////////////////////////////////////////////////////////////////////
		  // setting products in session and cookies when a user adds products to cart//
		  // //////////////////////////////////////////////////////////////////////////////
		if ($_POST ['productName'] == true) {
			if (isset ( $_COOKIE ['cart'] [$_POST [shop_id]] )) { // checking the existing products in cart
				foreach ( $_COOKIE ['cart'] [$_POST [shop_id]] as $val ) {
					// echo "<pre>"; print_r( json_decode($val, true)); echo "</pre>";
					if (in_array ( $productName, json_decode ( $val, true ) )) {
						$exist ++;
					} else {
					}
				}
			}
			
			if ($exist < 1) { // if product not exist in cart the set it to session and cookie
				$item = ModUtil::apiFunc ( 'ZSELEX', 'user', 'getProductCart', $args = array (
						'shop_id' => $shop_id 
				) );
				
				// echo "<pre>"; print_r($item); echo "</pre>";
				// echo $item['shop_id'];
				
				$_SESSION ['cart'] [$_POST ['shop_id']] [] = array (
						'PRODUCTID' => $product_id,
						'PRODUCTNAME' => $productName,
						'SHOPID' => $item ['shop_id'],
						'QUANTITY' => $quantity,
						'DESCRIPTION' => $productdesc,
						'IMAGE' => $productimg,
						'REALPRICE' => $productprice,
						'FINALPRICE' => $productprice 
				);
				
				$array = array (
						'PRODUCTID' => $product_id,
						'PRODUCTNAME' => $productName,
						'SHOPID' => $item ['shop_id'],
						'QUANTITY' => $quantity,
						'DESCRIPTION' => $productdesc,
						'IMAGE' => $productimg,
						'REALPRICE' => $productprice,
						'FINALPRICE' => $productprice 
				);
				
				$last_keys = '';
				if (! isset ( $_COOKIE ['cart'] [$_POST [shop_id]] )) { // if cookie is not set then set the first key as zero
					$last_keys = 0;
				} else { // if cookie is available then increment the key by adding the last key of the previous cookie to it.
					$last_key = key ( array_slice ( $_COOKIE ['cart'] [$_POST [shop_id]], - 1, 1, TRUE ) );
					$last_keys = $last_key + 1;
				}
				// echo "<pre>"; print_r(json_encode($_SESSION['cart'][$last_key])); echo "</pre>";
				
				$cookieEncode = json_encode ( $array );
				setcookie ( "cart[$_POST[shop_id]][$last_keys]", $cookieEncode, time () + 604800 );
			}
		} // ///
		  // //////////////////////////////////////////////////////////////////////////////////////
		  // echo "<pre>"; print_r($_COOKIE['cart']); echo "</pre>";
		  // echo "<pre>"; print_r(json_encode($_SESSION['cart'])); echo "</pre>";
		  // echo "<pre>"; print_r($_COOKIE['cart']); echo "</pre>";
		  // echo "<pre>"; print_r($_SESSION['cart']); echo "</pre>";
		
		if (isset ( $_SESSION ['cart'] )) {
			$sessionCount = count ( $_SESSION ['cart'] );
		}
		
		if (isset ( $_COOKIE ['cart'] )) {
			$cookieCount = count ( $_COOKIE ['cart'] );
		}
		
		// echo "session count: " . $sessionCount;
		// echo "<br>";
		// echo "cookie count: " . $cookieCount;
		// if (count($_COOKIE['cart']) > 0) {
		// foreach ($_COOKIE['cart'] as $key => $val) {
		// $finalCookie[$key] = json_decode($val, true);
		// }
		// }
		
		if (count ( $_COOKIE ['cart'] ) > 0) {
			$c = 0;
			foreach ( $_COOKIE ['cart'] as $key => $val ) {
				foreach ( $_COOKIE ['cart'] [$key] as $key1 => $val1 ) {
					$finalCookie [$key] [$key1] = json_decode ( $val1, true );
					// $t[$key][$key1] = json_decode($val1, true);
					$c ++;
				}
			}
		}
		
		if (count ( $_SESSION ['cart'] ) > 0) {
			$s = 0;
			foreach ( $_SESSION ['cart'] as $key => $val ) {
				foreach ( $_SESSION ['cart'] [$key] as $key1 => $val1 ) {
					// $finalCookie[$key][$key1] = json_decode($val1, true);
					// $t[$key][$key1] = json_decode($val1, true);
					$s ++;
				}
			}
		}
		// echo "c:" . $c;
		// echo "<br>";
		// echo "s:" . $s;
		
		$sessionCount = $s;
		$cookieCount = $c;
		$count = (empty ( $cookieCount )) ? $sessionCount : $cookieCount;
		
		$GRANDTOTAL = '';
		if ($sessionCount > $cookieCount) {
			if (count ( $_SESSION ['cart'] ) > 0) {
				foreach ( $_SESSION ['cart'] as $val ) {
					$grandPrice [] = $val ['FINALPRICE'];
				}
				$GRANDTOTAL = array_sum ( $grandPrice );
			}
		} else {
			if (count ( $_COOKIE ['cart'] ) > 0) {
				foreach ( $finalCookie as $val ) {
					$grandPrice [] = $val ['FINALPRICE'];
				}
				$GRANDTOTAL = array_sum ( $grandPrice );
			}
		}
		
		$products = array ();
		// echo "<br>".$GRANDTOTAL;
		// echo "<pre>"; print_r($_COOKIE['cart']); echo "</pre>";
		// echo "<pre>"; print_r($_SESSION['cart']); echo "</pre>";
		// echo "<pre>"; print_r($finalCookie); echo "</pre>";
		
		if ($sessionCount > $cookieCount) { // if session is set first then render session products to cart page otherwise render cookie products
		                                    // $this->view->assign('products', $_SESSION['cart']);
		                                    // echo "FROM SESSION";
			$products = $_SESSION ['cart'];
		} else {
			// echo "FROM COOKIE";
			// $this->view->assign('products', $finalCookie);
			$products = $finalCookie;
		}
		
		/*
		 * foreach ($products as $k => $v) {
		 *
		 * foreach ($products[$k] as $k1 => $v1) {
		 * //echo $v1['FINALPRICE'];
		 * $grandPrice1[] = $v1['FINALPRICE'];
		 * $GRANDTOTAL1 = array_sum($grandPrice1);
		 * $seller = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray', $args =
		 * array('table' => 'zselex_shop_owners a , users b', 'where' => array("a.user_id=b.uid", "a.shop_id=$k"), 'groupby' => 'b.uid'));
		 *
		 * $products[$k]['seller'] = $seller[0]['uname'];
		 * $products[$k]['subtotal'] = $GRANDTOTAL1;
		 * }
		 *
		 * //echo $products[$k]['seller'];
		 * }
		 */
		
		// echo "<pre>"; print_r($products); echo "</pre>";
		
		$this->view->assign ( 'products', $products );
		
		$this->view->assign ( 'grandtotal', $GRANDTOTAL );
		$this->view->assign ( 'count', $count );
		$this->view->assign ( 'check', $exist );
		$this->view->assign ( 'upcounter', $counter );
		
		return $this->view->fetch ( 'user/cart.tpl' );
	}
	public function cart1($args) {
		// $_SESSION['checkoutsession'] = '0';
		// echo $_SESSION['checkoutsession'];
		unset ( $_SESSION ['checkoutsession'] );
		$this->throwForbiddenUnless ( SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_OVERVIEW ), LogUtil::getErrorMsgPermission () );
		$sessionId = session_id ();
		$product_id = FormUtil::getPassedValue ( 'product_id', '', 'REQUEST' );
		$productName = FormUtil::getPassedValue ( 'productName', '', 'REQUEST' );
		$quantity = FormUtil::getPassedValue ( 'cart_quantity', '', 'REQUEST' );
		$productprice = FormUtil::getPassedValue ( 'product_price', '', 'REQUEST' );
		$productdesc = FormUtil::getPassedValue ( 'productDesc', '', 'REQUEST' );
		$productimg = FormUtil::getPassedValue ( 'productImage', '', 'REQUEST' );
		
		// echo "<pre>"; print_r($_POST); echo "</pre>";
		$exist = 0;
		
		if ($_POST ['productName'] == true) {
			if (isset ( $_SESSION ['cart'] )) {
				foreach ( $_SESSION ['cart'] as $val ) {
					if (in_array ( $productName, $val )) {
						$exist ++;
					} else {
					}
				}
			}
			
			if ($exist < 1) {
				$_SESSION ['cart'] [] = array (
						'PRODUCTID' => $product_id,
						'PRODUCTNAME' => $productName,
						'QUANTITY' => $quantity,
						'DESCRIPTION' => $productdesc,
						'IMAGE' => $productimg,
						'REALPRICE' => $productprice,
						'FINALPRICE' => $productprice 
				);
			} else {
				// return $this->registerError($this->__('The selected log-in method is not currently available.'));
			}
		}
		
		// echo "<pre>"; print_r($_SESSION['cart']); echo "</pre>";
		// echo $exit;
		// echo "<pre>"; print_r($_SESSION['cart']); echo "</pre>";
		$count = count ( $_SESSION ['cart'] );
		
		if (count ( $_SESSION ['cart'] ) > 0) {
			foreach ( $_SESSION ['cart'] as $val ) {
				$grandPrice [] = $val ['FINALPRICE'];
			}
			$GRANDTOTAL = array_sum ( $grandPrice );
		} else {
			$GRANDTOTAL = '';
		}
		$this->view->assign ( 'products', $_SESSION ['cart'] );
		$this->view->assign ( 'grandtotal', $GRANDTOTAL );
		$this->view->assign ( 'count', $count );
		$this->view->assign ( 'check', $exist );
		$this->view->assign ( 'upcounter', $counter );
		
		return $this->view->fetch ( 'user/cart.tpl' );
	}
	
	/**
	 * Deletes a product from the cart
	 * Unsets product stored in session
	 * Destroys product stored in cookie
	 */
	public function deletecart() {
		$Id = FormUtil::getPassedValue ( 'id', '', 'REQUEST' );
		$shop_id = FormUtil::getPassedValue ( 'shop_id', '', 'REQUEST' );
		unset ( $_SESSION ['cart'] [$shop_id] [$Id] );
		if (count ( $_SESSION ['cart'] [$shop_id] < 1 )) {
			// unset($_SESSION['cart'][$shop_id]);
		}
		
		setcookie ( "cart[$shop_id][$Id]", "", time () - 604800 );
		LogUtil::registerStatus ( $this->__ ( 'Done! Item has been deleted successfully from Cart.' ) );
		return $this->redirect ( ModUtil::url ( 'ZSELEX', 'user', 'cart' ) );
	}
	
	/**
	 * Updates product detail of products in the cart
	 * updates the session and cookie
	 */
	public function updatecart() {
		
		// echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
		$shop_id = $_REQUEST ['shop_id'];
		// echo $shop_id; exit;
		$counter = 0;
		foreach ( $_POST ['quantity'] as $productKey => $quantity ) {
			// echo $productKey . " - " . $quantity . '<br>';
			$_SESSION ['cart'] [$shop_id] [$productKey] ['QUANTITY'] = $quantity;
			$_SESSION ['cart'] [$shop_id] [$productKey] ['FINALPRICE'] = $quantity * $_SESSION ['cart'] [$shop_id] [$productKey] ['REALPRICE'];
			
			// setcookie("cart[$productKey]", $cookieEncode1, time() + 604800);
			// echo $_COOKIE['cart'][$productKey] . '<br>';
		}
		
		// echo "<pre>"; print_r($_SESSION['cart']); echo "</pre>"; exit;
		// setcookie("cart", "" , time() - 604800);
		
		foreach ( $_SESSION ['cart'] [$shop_id] as $key => $val ) {
			$array = array (
					'PRODUCTID' => $val ['PRODUCTID'],
					'PRODUCTNAME' => $val ['PRODUCTNAME'],
					'QUANTITY' => $val ['QUANTITY'],
					'DESCRIPTION' => $val ['DESCRIPTION'],
					'IMAGE' => $val ['IMAGE'],
					'REALPRICE' => $val ['REALPRICE'],
					'FINALPRICE' => $val ['FINALPRICE'] 
			);
			
			$cookieEncode = json_encode ( $array );
			setcookie ( "cart[$shop_id][$key]", $cookieEncode, time () + 604800 );
		}
		
		LogUtil::registerStatus ( $this->__ ( 'Done! Cart has been updated successfully.' ) );
		
		// echo "<pre>"; print_r(json_encode($_SESSION['cart'][$last_key])); echo "</pre>";
		// echo "<pre>"; print_r($array); echo "</pre>"; exit;
		
		return $this->redirect ( ModUtil::url ( 'ZSELEX', 'user', 'cart' ) );
	}
	
	/**
	 * check out function from cart
	 */
	public function checkout() {
		
		// echo session_id();
		/*
		 * if ($_POST == true) {
		 * $shop_id = $_REQUEST['shop_id'];
		 *
		 * extract($_POST);
		 * if (!empty($_SESSION['cart'][$shop_id])) {
		 *
		 * foreach ($_SESSION['cart'][$shop_id] as $val) {
		 * $grandPrice[] = $val['FINALPRICE'];
		 * }
		 * $GRANDTOTAL = array_sum($grandPrice);
		 *
		 * $totalprice = $GRANDTOTAL;
		 *
		 * $obj = array('user_id' => $user_id,
		 * 'city' => $city,
		 * 'street' => $street,
		 * 'address' => $address,
		 * 'phone' => $phone,
		 * 'totalprice' => $totalprice,
		 * );
		 * $result = DBUtil::insertObject($obj, 'zselex_order');
		 * $InsertId = DBUtil::getInsertID('zselex_order', 'order_id');
		 * //echo "order id : " .$InsertId;
		 * // echo "<pre>"; print_r($result); echo "</pre>";
		 * // return $this->redirect(ModUtil::url('ZSELEX', 'user', 'order'));
		 * }
		 *
		 *
		 * // echo "<pre>"; print_r($_SESSION['cart'][$shop_id]); echo "</pre>";
		 * }
		 *
		 */
		
		// echo "helloooooo";
		// echo "<pre>"; print_r($_SESSION['checkoutinfo']); echo "</pre>"; exit;
		if (! UserUtil::isLoggedIn ()) {
			// echo "hellooo222";
			// SessionUtil::setVar('checkoutsession', '1');
			// session_register('checkoutsession');
			$_SESSION ['checkoutsession'] = '1';
			// EventUtil::registerPersistentModuleHandler('ZSELEX', 'module.users.ui.login.succeeded', array('ZSELEX_Listener_UserLogin', 'succeeded'));
			return LogUtil::registerPermissionError ();
		} else {
			
			// echo "Check Out Page";
			$user_id = UserUtil::getVar ( 'uid' );
			$userinfo = DBUtil::selectObjectByID ( 'users', $user_id, 'uid' );
			
			// echo "<pre>"; print_r($userinfo); echo "</pre>";
		}
		$checkoutInfo = '';
		if (! empty ( $_SESSION ['checkoutinfo'] )) {
			$checkoutInfo = $_SESSION ['checkoutinfo'];
		}
		$shop_id = $_REQUEST ['shop_id'];
		$seller = ModUtil::apiFunc ( 'ZSELEX', 'user', 'selectRow', $args = array (
				'table' => 'zselex_shop_owners a ,  users b',
				'where' => array (
						"a.user_id=b.uid",
						"a.shop_id=$shop_id" 
				),
				'groupby' => 'b.uid' 
		) );
		
		$sellerName = $seller ['uname'];
		$this->view->assign ( 'seller', $sellerName );
		
		$this->view->assign ( 'shop_id', $shop_id );
		$this->view->assign ( 'userinfo', $userinfo );
		$this->view->assign ( $checkoutInfo );
		
		return $this->view->fetch ( 'user/checkout.tpl' );
	}
	public function payButtonExist($args) {
		$shop_id = $args ['shop_id'];
		
		$payButtonCount = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getCount', $args = array (
				'table' => 'zselex_serviceshop',
				'where' => "shop_id=$shop_id AND type='paybutton'" 
		) );
		return $payButtonCount;
	}
	
	/**
	 * Order details
	 */
	public function order() {
		if (! UserUtil::isLoggedIn ()) {
			$_SESSION ['checkoutsession'] = '1';
			return LogUtil::registerPermissionError ();
		}
		// echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
		
		$shop_id = $_REQUEST ['id'];
		// echo $shop_id; exit;
		// echo "<pre>"; print_r($_SESSION['cart'][$shop_id]); echo "</pre>"; exit;
		$this->view->assign ( 'shop_id', $shop_id );
		
		// echo "<pre>"; print_r($_COOKIE['cart'][$shop_id]); echo "</pre>";
		$args = array (
				'shop_id' => $shop_id 
		);
		// echo $this->payButtonExist($args);
		
		if ($this->payButtonExist ( $args ) > 0) {
			
			$payPalEmail = ModUtil::apiFunc ( 'ZSELEX', 'user', 'getPayPalEmail', $args = array (
					'shop_id' => $shop_id 
			) );
		} else {
			$modvars = $this->getVars ();
			$payPalEmail = $modvars ['paypalzselexemail'];
		}
		$modvars = $this->getVars ();
		// echo "<pre>"; print_r($modvars); echo "</pre>";
		// echo "paypal email : " . $payPalEmail;
		$this->view->assign ( 'paypalemail', $payPalEmail );
		
		// echo "<pre>"; print_r($modvars); echo "</pre>";
		// echo "<pre>"; print_r($_SESSION['cart']); echo "</pre>";
		// echo "<pre>"; print_r($_SESSION['cart'][$shop_id]); echo "</pre>";
		// $user_id = UserUtil::getVar('uid');
		// $city = FormUtil::getPassedValue('city', '', 'POST');
		// $street = FormUtil::getPassedValue('street', '', 'POST');
		// $adress = FormUtil::getPassedValue('address', '', 'POST');
		// $phone = FormUtil::getPassedValue('phone', '', 'POST');
		//
		// if ($_POST == true) {
		// $_SESSION['checkoutinfo'] = $_POST;
		// }
		// echo "<pre>"; print_r($_SESSION['checkoutinfo']); echo "</pre>";
		
		$order_id = $_SESSION ['checkoutinfo'] ['order_id'];
		if (! empty ( $_SESSION ['cart'] [$shop_id] )) {
			foreach ( $_SESSION ['cart'] [$shop_id] as $val ) {
				$grandPrice [] = $val ['FINALPRICE'];
			}
			$GRANDTOTAL = array_sum ( $grandPrice );
			
			$totalprice = $GRANDTOTAL;
		} else {
			
			return $this->redirect ( ModUtil::url ( 'ZSELEX', 'user', 'cart' ) );
		}
		
		$_SESSION ['checkoutinfo'] ['totalprice'] = $totalprice;
		
		$paytype = $_SESSION ['checkoutinfo'] ['paytype'];
		// echo $paytype; exit;
		
		/*
		 * if (!empty($_SESSION['cart'][$shop_id])) {
		 *
		 * $obj = array('user_id' => $user_id,
		 * 'city' => $city,
		 * 'street' => $street,
		 * 'address' => $adress,
		 * 'phone' => $phone,
		 * 'totalprice' => $totalprice,
		 * );
		 * $result = DBUtil::insertObject($obj, 'zselex_order');
		 * }
		 *
		 */
		
		// if (!empty($_POST)) {
		// $obj = array('user_id' => $user_id,
		// 'city' => $city,
		// 'street' => $street,
		// 'address' => $adress,
		// 'phone' => $phone,
		// 'totalprice' => $totalprice,
		// );
		//
		// // do the insert
		// $result = DBUtil::insertObject($obj, 'zselex_order');
		// $lastInsertId = DBUtil::getInsertID('zselex_order', 'order_id');
		//
		// foreach ($_SESSION['cart'] as $val) {
		//
		// $obj2 = array('product_id' => $val['PRODUCTID'],
		// 'order_id' => $lastInsertId,
		// 'quantity' => $val['QUANTITY'],
		// 'price' => $val['FINALPRICE'],
		// );
		//
		// $result2 = DBUtil::insertObject($obj2, 'zselex_orderitems');
		// }
		//
		//
		// }
		// echo "<pre>"; print_r($_SESSION['checkoutinfo']); echo "</pre>";
		$products = $_SESSION ['cart'] [$shop_id];
		// echo "<pre>"; print_r($products); echo "</pre>";
		$this->view->assign ( $_SESSION ['checkoutinfo'] );
		$this->view->assign ( 'paytype', $paytype );
		$this->view->assign ( 'shop_id', $shop_id );
		$this->view->assign ( 'products', $products );
		
		$this->view->assign ( 'user_id', $user_id );
		$this->view->assign ( 'grandtotal', $totalprice );
		return $this->view->fetch ( 'user/order.tpl' );
	}
	public function placeOrder() {
		
		// echo "<pre>"; print_r($_SESSION['checkoutinfo']); echo "</pre>"; exit;
		$user_id = UserUtil::getVar ( 'uid' );
		$shop_id = $_REQUEST ['shop_id'];
		// echo $shop_id; exit;
		
		if (! empty ( $_SESSION ['cart'] [$shop_id] )) {
			foreach ( $_SESSION ['cart'] [$shop_id] as $val ) {
				$grandPrice [] = $val ['FINALPRICE'];
			}
			$GRANDTOTAL = array_sum ( $grandPrice );
			
			$totalprice = $GRANDTOTAL;
			$_SESSION ['checkoutinfo'] ['totalprice'] = $totalprice;
		}
		
		$paymentType = $_REQUEST ['paytype'];
		$_SESSION ['checkoutinfo'] ['shop_id'] = $shop_id;
		$_SESSION ['checkoutinfo'] ['paytype'] = $paymentType;
		
		// echo "<pre>"; print_r($_SESSION['checkoutinfo']); echo "</pre>"; exit;
		// echo $shop_id; exit;
		$obj = array (
				'user_id' => $user_id,
				'city' => $_SESSION ['checkoutinfo'] [city],
				'street' => $_SESSION ['checkoutinfo'] [street],
				'address' => $_SESSION ['checkoutinfo'] [adress],
				'phone' => $_SESSION ['checkoutinfo'] [phone],
				'totalprice' => $_SESSION ['checkoutinfo'] [totalprice],
				'status' => 'placed' 
		);
		// echo "</pre>"; print_r($obj); echo "</pre>"; exit;
		
		$result = DBUtil::insertObject ( $obj, 'zselex_order' );
		$lastInsertId = DBUtil::getInsertID ( 'zselex_order', 'id' );
		
		if ($result) {
			
			$order_id = "ZS" . $lastInsertId;
			$_SESSION ['checkoutinfo'] [order_id] = $order_id;
			$args = array (
					'table' => 'zselex_order',
					'items' => array (
							'order_id' => $order_id 
					),
					'where' => array (
							'id' => $lastInsertId 
					) 
			);
			$updateOrderId = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'updateElementWhere', $args );
			
			foreach ( $_SESSION ['cart'] [$shop_id] as $key => $val ) {
				
				$obj2 = array (
						'product_id' => $val ['PRODUCTID'],
						'shop_id' => $shop_id,
						'order_id' => $order_id,
						'quantity' => $val ['QUANTITY'],
						'price' => $val ['FINALPRICE'] 
				);
				
				$result2 = DBUtil::insertObject ( $obj2, 'zselex_orderitems' );
			}
			
			// return $this->redirect(ModUtil::url('ZSELEX', 'user', 'payment'));
			return $this->redirect ( ModUtil::url ( 'ZSELEX', 'user', 'order', array (
					'id' => $shop_id 
			) ) );
		}
	}
	public function paymentoptions() {
		$shop_id = $_REQUEST ['id'];
		
		// echo $shop_id; exit;
		
		$user_id = UserUtil::getVar ( 'uid' );
		$city = FormUtil::getPassedValue ( 'city', '', 'POST' );
		$street = FormUtil::getPassedValue ( 'street', '', 'POST' );
		$adress = FormUtil::getPassedValue ( 'address', '', 'POST' );
		$phone = FormUtil::getPassedValue ( 'phone', '', 'POST' );
		
		if ($_POST == true) {
			$_SESSION ['checkoutinfo'] = $_POST;
		}
		$this->view->assign ( 'shop_id', $shop_id );
		
		return $this->view->fetch ( 'user/paymentoptions.tpl' );
	}
	public function payment() {
		
		// echo "</pre>"; print_r($_POST); echo "</pre>"; exit;
		// echo "</pre>"; print_r($_SESSION['checkoutinfo']); echo "</pre>"; exit;
		/*
		 * $user_id = UserUtil::getVar('uid');
		 * $shop_id = $_REQUEST['shop_id'];
		 *
		 * //echo $shop_id; exit;
		 * $obj = array(
		 * 'user_id' => $user_id,
		 * 'city' => $_SESSION['checkoutinfo'][city],
		 * 'street' => $_SESSION['checkoutinfo'][street],
		 * 'address' => $_SESSION['checkoutinfo'][adress],
		 * 'phone' => $_SESSION['checkoutinfo'][phone],
		 * 'totalprice' => $_SESSION['checkoutinfo'][totalprice],
		 * 'status' => 'placed',
		 * );
		 * //echo "</pre>"; print_r($obj); echo "</pre>"; exit;
		 *
		 * $result = DBUtil::insertObject($obj, 'zselex_order');
		 * $lastInsertId = DBUtil::getInsertID('zselex_order', 'id');
		 *
		 * if ($result) {
		 *
		 * $order_id = "ZS" . $lastInsertId;
		 * $args = array('table' => 'zselex_order',
		 * 'items' => array('order_id' => $order_id),
		 * 'where' => array('id' => $lastInsertId));
		 * $updateOrderId = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElementWhere', $args);
		 *
		 *
		 * foreach ($_SESSION['cart'][$shop_id] as $key => $val) {
		 *
		 * $obj2 = array('product_id' => $val['PRODUCTID'],
		 * 'shop_id' => $shop_id,
		 * 'order_id' => $order_id,
		 * 'quantity' => $val['QUANTITY'],
		 * 'price' => $val['FINALPRICE']
		 * );
		 *
		 * $result2 = DBUtil::insertObject($obj2, 'zselex_orderitems');
		 * }
		 * }
		 *
		 */
		
		// echo "order id :" . $lastInsertId; exit;
		$shopsId = $_SESSION ['checkoutinfo'] ['shop_id'];
		
		// echo "<pre>"; print_r($_SESSION['cart'][$shopsId]); echo "</pre>"; exit;
		
		$products = $_SESSION ['cart'] [$shopsId];
		
		// echo "<pre>"; print_r($products); echo "</pre>"; exit;
		
		$this->view->assign ( 'products', $products );
		
		$this->view->assign ( $_SESSION ['checkoutinfo'] );
		return $this->view->fetch ( 'user/payment.tpl' );
	}
	public function payPalReturn() {
		
		// echo "order id : " . $_REQUEST['order_id'] . '<br>'; exit;
		// echo "<pre>" ; print_r($_REQUEST); echo "</pre>"; exit;
		$ordr = $_REQUEST ['order_id'];
		// $orderno = $_SESSION["ss_last_orderno"];
		$orderno = $ordr;
		
		// $orderno = $_SESSION['checkoutinfo'][order_id];
		$ppAcc = "seller_1357558142_biz@.com";
		$at = "D_fA7ggeD4MVD9j9jtnWJ9xBOM0z_-RuGnkCSb8O9mCRVAJhtF__cC0njmW"; // PDT Identity Token
		$url = "https://www.sandbox.paypal.com/cgi-bin/webscr"; // Test
		                                                        // $url = "https://www.paypal.com/cgi-bin/webscr"; //Live
		$tx = $_REQUEST ["tx"]; // this value is return by PayPal
		                       // $tx = '95C602744Y395553B';
		                       // echo $tx; exit;
		$cmd = "_notify-synch";
		$post = "tx=$tx&at=$at&cmd=$cmd";
		
		// Send request to PayPal server using CURL
		$ch = curl_init ( $url );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_TIMEOUT, 30 );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post );
		
		$result = curl_exec ( $ch ); // returned result is key-value pair string
		$error = curl_error ( $ch );
		
		if (curl_errno ( $ch ) != 0) // CURL error
			exit ( "ERROR: Failed updating order. PayPal PDT service failed." );
		
		$longstr = str_replace ( "\r", "", $result );
		$lines = split ( "\n", $longstr );
		// echo "<pre>"; print_r($lines); echo "</pre>"; exit;
		// parse the result string and store information to array
		if ($lines [0] == "SUCCESS") {
			// successful payment
			$ppInfo = array ();
			for($i = 1; $i < count ( $lines ); $i ++) {
				$parts = split ( "=", $lines [$i] );
				if (count ( $parts ) == 2) {
					$ppInfo [$parts [0]] = urldecode ( $parts [1] );
				}
			}
			
			$curtime = gmdate ( "d/m/Y H:i:s" );
			// capture the PayPal returned information as order remarks
			$oremarks = "##$curtime##\n" . "PayPal Transaction Informationâ€¦<br>" . "Txn Id: " . $ppInfo ["txn_id"] . "<br>" . "Txn Type: " . $ppInfo ["txn_type"] . "<br>" . "Item Number: " . $ppInfo ["item_number"] . "<br>" . "Payment Date: " . $ppInfo ["payment_date"] . "<br>" . "Payment Type: " . $ppInfo ["payment_type"] . "<br>" . "Payment Status: " . $ppInfo ["payment_status"] . "<br>" . "Currency: " . $ppInfo ["mc_currency"] . "<br>" . "Payment Gross: " . $ppInfo ["payment_gross"] . "<br>" . "Payment Fee: " . $ppInfo ["payment_fee"] . "<br>" . "Payer Email: " . $ppInfo ["payer_email"] . "<br>" . "Payer Id: " . $ppInfo ["payer_id"] . "<br>" . "Payer Name: " . $ppInfo ["first_name"] . " " . $ppInfo ["last_name"] . "<br>" . "Payer Status: " . $ppInfo ["payer_status"] . "<br>" . "Country: " . $ppInfo ["residence_country"] . "<br>" . "Business: " . $ppInfo ["business"] . "<br>" . "Receiver Email: " . $ppInfo ["receiver_email"] . "<br>" . "Receiver Id: " . $ppInfo ["receiver_id"] . "<br>";
			
			// Update database using $orderno, set status to Paid
			// Send confirmation email to buyer and notification email to merchant
			// Redirect to thankyou page
			// echo $oremarks;
			
			$args = array (
					'table' => 'zselex_order',
					'items' => array (
							'status' => "success" 
					),
					'where' => array (
							'order_id' => "'$orderno'" 
					) 
			);
			$updateOrderId = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'updateElementWhere', $args );
			$shopsId = $_REQUEST ['cm'];
			
			$orderDetails = ModUtil::apiFunc ( 'ZSELEX', 'user', 'showPurchedOrder', $args = array (
					'order_id' => $orderno 
			) );
			
			// echo "shop_id :" . $shopsId;
			// echo "<pre>"; print_r($_COOKIE['cart'][$shopsId]); echo "</pre>";
			// unset($_SESSION['cart'][$shopsId]);
			// foreach ($_COOKIE['cart'][$shopsId] as $key => $val) {
			// echo $key . "<br>";
			// setcookie("cart[$shopsId][$key]", null , time() - 6048000);
			//
			// }
			// setcookie("cart[$shopsId]", "", time() - 604800);
			// echo "<pre>"; print_r($orderDetails); echo "</pre>";
			
			$this->view->assign ( 'order_id', $orderno );
			$this->view->assign ( 'orderDetails', $orderDetails );
			$this->view->assign ( 'shop_id', $shopsId );
			$this->view->assign ( 'reciept', $oremarks );
			return $this->view->fetch ( 'user/thankyou.tpl' );
		} 		

		// Payment failed
		else {
			// echo "Failed....";
			
			$args = array (
					'table' => 'zselex_order',
					'items' => array (
							'status' => "failed" 
					),
					'where' => array (
							'order_id' => "'$orderno'" 
					) 
			);
			$updateOrderId = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'updateElementWhere', $args );
			return $this->view->fetch ( 'user/pperror.tpl' );
			// Delete order information
			// Redirect to failed page
		}
		
		// exit;
	}
	public function paypalCancel($args) {
		$order_id = $_REQUEST ['order_id'];
		
		$args = array (
				'table' => 'zselex_order',
				'items' => array (
						'status' => "cancelled" 
				),
				'where' => array (
						'order_id' => "'$order_id'" 
				) 
		);
		$updateOrderId = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'updateElementWhere', $args );
		return $this->view->fetch ( 'user/ppcancelled.tpl' );
	}
	
	/**
	 * This is a page to provide an textual overview of caching concepts
	 * 
	 * @return string
	 */
	public function cacheinfo() {
		// template needs to know where the directories are
		$this->view->assign ( 'compiledir', $this->view->getCompileDir () );
		$this->view->assign ( 'cachedir', $this->view->getCacheDir () );
		
		return $this->view->fetch ( 'user/cachedemo/info.tpl' );
	}
	
	/**
	 * This is a standard page that returns a template view
	 * It DOES respect the settings in Theme->settings->render caching
	 * (on/off and lifetime)
	 * 
	 * @return string
	 */
	public function standard() {
		$this->view->assign ( 'time', microtime ( true ) );
		return $this->view->fetch ( 'user/cachedemo/standard.tpl' );
	}
	
	/**
	 * This is a page that should never return cached information.
	 * It does not
	 * respect cache settings (on/off) in Theme. The page should always return
	 * new information regardless of all cache settings.
	 * 
	 * @return string
	 */
	public function nevercached() {
		// force caching off
		$this->view->setCaching ( Zikula_View::CACHE_DISABLED );
		
		$this->view->assign ( 'time', microtime ( true ) );
		return $this->view->fetch ( 'user/cachedemo/nevercached.tpl' );
	}
	
	/**
	 * This is a page that should return partially cached information.
	 * It does
	 * not respect cache settings(on/off or lifetime) in Theme. The page should
	 * always return some information that is always cached and some information
	 * that is never cached. (controlled in template by {nocache} block)
	 * 
	 * @return string
	 */
	public function partialcache() {
		// force caching on
		$this->view->setCaching ( Zikula_View::CACHE_ENABLED );
		
		// force local cache lifetime
		$localcachelifetime = 31;
		$this->view->setCacheLifetime ( $localcachelifetime );
		
		$this->view->assign ( 'time', microtime ( true ) );
		$this->view->assign ( 'localcachelifetime', $localcachelifetime );
		return $this->view->fetch ( 'user/cachedemo/partialcache.tpl' );
	}
	
	/**
	 * When one template is used to render multiple pages or versions of content
	 * it becomes necessary to 'salt' the cacheId with additional information
	 * in order that each unique page of content has a unique cache
	 *
	 * This page will return unique cached information per page id. In this
	 * example the only unique information on the page is the page number.
	 *
	 * It does not respect cache settings (on/off or lifetime) in Theme.
	 *
	 * Additionally, this page demonstrates the varying methods to clear cached
	 * templates using clear_cache().
	 * 
	 * @return string
	 */
	public function uniquepages() {
		$submit = ( int ) $this->request->getPost ()->get ( 'submit', 0 );
		$page = ( int ) $this->request->getPost ()->get ( 'page', 1 );
		// enfore min/max values for $page
		if ($page < 1) {
			$page = 1;
		}
		if ($page > 9) {
			$page = 9;
		}
		
		$template = 'user/cachedemo/uniquepages.tpl';
		
		// force caching on
		$this->view->setCaching ( Zikula_View::CACHE_ENABLED );
		
		// force local cache lifetime
		$localcachelifetime = 120;
		$this->view->setCacheLifetime ( $localcachelifetime );
		
		// setting the cacheid forces each page version of the template to unique
		$this->view->setCacheId ( $page );
		
		switch ($submit) {
			case - 100 : // clear this page template cache
				$this->view->clear_cache ( $template, $this->view->getCacheId () );
				LogUtil::registerStatus ( $this->__f ( "Just this version of '%s' cleared from cache.", $template ) );
				break;
			case - 200 : // clear all page uses of this template cache
				$this->view->clear_cache ( $template );
				LogUtil::registerStatus ( $this->__f ( "All versions of '%s' cleared from cache.", $template ) );
				break;
			// NOTE: calling $this->view->clear_cache(); (with no arguments) clears all cached templates for *this* module.
		}
		
		$this->view->assign ( 'cacheid', $this->view->getCacheId () );
		$this->view->assign ( 'submit', $submit );
		
		$this->view->assign ( 'page', $page );
		$this->view->assign ( 'time', microtime ( true ) );
		$this->view->assign ( 'localcachelifetime', $localcachelifetime );
		return $this->view->fetch ( $template );
	}
	
	/**
	 * This is a page to demonstrate the value of checking ->is_cached() when
	 * returning a cached template.
	 * A manufactured delay (sleep) is used to
	 * simulate doing something very resource intensive that might take place
	 * in a real module.
	 * It does not respect cache settings (on/off or lifetime) in Theme.
	 * 
	 * @return string
	 */
	public function checkiscached() {
		$template = 'user/cachedemo/checkiscached.tpl';
		
		// force caching on
		$this->view->setCaching ( Zikula_View::CACHE_ENABLED );
		
		// force local cache lifetime
		$localcachelifetime = 31;
		$this->view->setCacheLifetime ( $localcachelifetime );
		
		// check to see if the tempalte is cached, if not, get required data
		if (! $this->view->is_cached ( $template )) {
			// manufactured wait to demo DB fetch or something resource intensive
			sleep ( 5 );
			
			$this->view->assign ( 'time', microtime ( true ) );
			$this->view->assign ( 'localcachelifetime', $localcachelifetime );
		}
		return $this->view->fetch ( $template );
	}
	public function shopzzz() {
		$this->throwForbiddenUnless ( SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_OVERVIEW ), LogUtil::getErrorMsgPermission () );
		return $this->view->fetch ( 'user/shopadmin.tpl' );
	}
	public function creates($args) {
		
		// echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
		$_SESSION ['redirecttosite'] = 1;
		return News_Controller_User::create ( $args );
		
		// ZSELEX_Controller_User::redirect(ModUtil::url('ZSELEX', 'user', 'viewshoparticles', array('shop_id' => '104')));
	}
	
	/**
	 * News module user controller override class function
	 */
	public function create($args) {
		
		// echo "Works Fine event!!!"; exit;
		// Get parameters from whatever input we need
		$story = FormUtil::getPassedValue ( 'story', isset ( $args ['story'] ) ? $args ['story'] : null, 'POST' );
		$files = News_ImageUtil::reArrayFiles ( FormUtil::getPassedValue ( 'news_files', null, 'FILES' ) );
		
		$modifyid = $story ['modifyid'];
		// echo $modifyid; exit;
		
		if ($story ['shop_idnewItem'] != '') {
			$shop_id = $story ['shop_idnewItem'];
		} else {
			$shop_id = $story ['shop_id'];
		}
		$shopName = $story ['shopName'];
		
		// echo $shop_id; exit;
		// echo $shopName; exit;
		// echo "<pre>"; print_r($story); echo "</pre>"; exit;
		// Create the item array for processing
		$item = array (
				'title' => $story ['title'],
				'urltitle' => isset ( $story ['urltitle'] ) ? $story ['urltitle'] : '',
				'__CATEGORIES__' => isset ( $story ['__CATEGORIES__'] ) ? $story ['__CATEGORIES__'] : null,
				'__ATTRIBUTES__' => isset ( $story ['attributes'] ) ? News_Util::reformatAttributes ( $story ['attributes'] ) : null,
				'language' => isset ( $story ['language'] ) ? $story ['language'] : '',
				'hometext' => isset ( $story ['hometext'] ) ? $story ['hometext'] : '',
				'hometextcontenttype' => $story ['hometextcontenttype'],
				'bodytext' => isset ( $story ['bodytext'] ) ? $story ['bodytext'] : '',
				'bodytextcontenttype' => $story ['bodytextcontenttype'],
				'notes' => $story ['notes'],
				'displayonindex' => isset ( $story ['displayonindex'] ) ? $story ['displayonindex'] : 0,
				'allowcomments' => isset ( $story ['allowcomments'] ) ? $story ['allowcomments'] : 0,
				'from' => isset ( $story ['from'] ) ? $story ['from'] : null,
				'tonolimit' => isset ( $story ['tonolimit'] ) ? $story ['tonolimit'] : null,
				'to' => isset ( $story ['to'] ) ? $story ['to'] : null,
				'unlimited' => isset ( $story ['unlimited'] ) && $story ['unlimited'] ? true : false,
				'weight' => isset ( $story ['weight'] ) ? $story ['weight'] : 0,
				'action' => isset ( $story ['action'] ) ? $story ['action'] : self::ACTION_PREVIEW,
				'sid' => isset ( $story ['sid'] ) ? $story ['sid'] : null,
				'tempfiles' => isset ( $story ['tempfiles'] ) ? $story ['tempfiles'] : null,
				'del_pictures' => isset ( $story ['del_pictures'] ) ? $story ['del_pictures'] : null 
		);
		
		// echo "<pre>"; print_r($item); echo "</pre>"; exit;
		// convert user times to server times (TZ compensation) refs #181
		// can't do the below because values are YYYY-MM-DD HH:MM:SS and DateUtil value is in seconds.
		// $item['from'] = $item['from'] + DateUtil::getTimezoneUserDiff();
		// $item['to'] = $item['to'] + DateUtil::getTimezoneUserDiff();
		// Disable the non accessible fields for non editors
		if (! SecurityUtil::checkPermission ( 'News::', '::', ACCESS_ADD )) {
			$item ['notes'] = '';
			$item ['displayonindex'] = 1;
			$item ['allowcomments'] = 1;
			$item ['from'] = null;
			$item ['tonolimit'] = true;
			$item ['to'] = null;
			$item ['unlimited'] = true;
			$item ['weight'] = 0;
			if ($item ['action'] > self::ACTION_SUBMIT) {
				$item ['action'] = self::ACTION_PREVIEW;
			}
		}
		
		// Validate the input
		$validationerror = News_Util::validateArticle ( $item );
		// check hooked modules for validation
		$sid = isset ( $item ['sid'] ) ? $item ['sid'] : null;
		$hookvalidators = News_Controller_User::notifyHooks ( new Zikula_ValidationHook ( 'news.ui_hooks.articles.validate_edit', new Zikula_Hook_ValidationProviders () ) )->getValidators ();
		if ($hookvalidators->hasErrors ()) {
			$validationerror .= News_Controller_User::__ ( 'Error! Hooked content does not validate.' ) . "\n";
		}
		
		// get all module vars
		// $modvars = News_Controller_User::getVars();
		
		$modvars = ModUtil::getVar ( 'News' );
		
		// echo "<pre>"; print_r($modvars); echo "</pre>"; exit;
		
		if (isset ( $files ) && $modvars ['picupload_enabled']) {
			list ( $files, $item ) = News_ImageUtil::validateImages ( $files, $item );
		} else {
			$item ['pictures'] = 0;
		}
		
		// story was previewed with uploaded pics
		if (isset ( $item ['tempfiles'] )) {
			$tempfiles = unserialize ( $item ['tempfiles'] );
			// delete files if requested
			if (isset ( $item ['del_pictures'] )) {
				foreach ( $tempfiles as $key => $file ) {
					if (in_array ( $file ['name'], $item ['del_pictures'] )) {
						unset ( $tempfiles [$key] );
						News_ImageUtil::removePreviewImages ( array (
								$file 
						) );
					}
				}
			}
			$files = array_merge ( $files, $tempfiles );
			$item ['pictures'] += count ( $tempfiles );
		}
		
		// if the user has selected to preview the article we then route them back
		// to the new function with the arguments passed here
		if ($item ['action'] == self::ACTION_PREVIEW || $validationerror !== false) {
			// log the error found if any
			if ($validationerror !== false) {
				LogUtil::registerError ( nl2br ( $validationerror ) );
			}
			if ($item ['pictures'] > 0) {
				$tempfiles = News_ImageUtil::tempStore ( $files );
				$item ['tempfiles'] = serialize ( $tempfiles );
			}
			// back to the referer form
			SessionUtil::setVar ( 'newsitem', $item );
			$this->redirect ( ModUtil::url ( 'ZSELEX', 'user', 'newitemevent', array (
					'shop_id' => $shop_id 
			) ) );
		} else {
			// As we're not previewing the item let's remove it from the session
			SessionUtil::delVar ( 'newsitem' );
		}
		
		// Confirm authorization code.
		News_Controller_User::checkCsrfToken ();
		
		if (! isset ( $item ['sid'] ) || empty ( $item ['sid'] )) {
			// Create the news story
			$sid = ModUtil::apiFunc ( 'News', 'user', 'create', $item );
			
			// echo "Last Id: " . $sid; exit;
			if ($sid != false) {
				$shopNewsArg = array (
						'shop_id' => $shop_id,
						'news_id' => $sid,
						'cr_uid' => UserUtil::getVar ( 'uid' ) 
				);
				
				// print_r($shopNewsArg); exit;
				ModUtil::apiFunc ( 'ZSELEX', 'user', 'insertShopNews', $shopNewsArg );
				// $_SESSION['shopsid'] = '';
				// $_SESSION['shopsname'] = '';
				// $argsused = array('type' => 'createarticles', 'shop_id' => $shop_id, 'user_id' => UserUtil::getVar('uid'));
				// ModUtil::apiFunc('ZSELEX', 'user', 'updateArticleService', $argsused);
				// $user_id = UserUtil::getVar('uid');
				// $serviceupdatearg = array('user_id' => $user_id, 'type' => 'createarticle', 'shop_id' => $shop_id);
				// $serviceavailed = ModUtil::apiFunc('ZSELEX', 'admin', 'updateServiceUsed', $serviceupdatearg);
				// DBUtil::insertObject($shopNewsArg, 'zselex_shop_news' , 'shop_news_id');
				// Success
				LogUtil::registerStatus ( News_Controller_User::__ ( 'Done! Created new article.' ) );
				// Let any hooks know that we have created a new item
				News_Controller_User::notifyHooks ( new Zikula_ProcessHook ( 'news.ui_hooks.articles.process_edit', $sid, new Zikula_ModUrl ( 'News', 'User', 'display', ZLanguage::getLanguageCode (), array (
						'sid' => $sid 
				) ) ) );
				News_Controller_User::notify ( $item ); // send notification email
			} else {
				// fail! story not created
				throw new Zikula_Exception_Fatal ( News_Controller_User::__ ( 'Story not created for unknown reason (Api failure).' ) );
				return false;
			}
		} else {
			// update the draft
			$result = ModUtil::apiFunc ( 'News', 'admin', 'update', $item );
			if ($result) {
				LogUtil::registerStatus ( News_Controller_User::__ ( 'Done! Story has been updated successfully.' ) );
			} else {
				// fail! story not updated
				throw new Zikula_Exception_Fatal ( News_Controller_User::__ ( 'Story not updated for unknown reason (Api failure).' ) );
				return false;
			}
		}
		
		// clear article and view caches
		News_Controller_User::clearArticleCaches ( $item, $this );
		
		if (isset ( $files ) && $modvars ['picupload_enabled']) {
			$resized = News_ImageUtil::resizeImages ( $sid, $files ); // resize and move the uploaded pics
			if (isset ( $item ['tempfiles'] )) {
				News_ImageUtil::removePreviewImages ( $tempfiles ); // remove any preview images
			}
			LogUtil::registerStatus ( News_Controller_User::_fn ( '%1$s out of %2$s picture was uploaded and resized.', '%1$s out of %2$s pictures were uploaded and resized.', $item ['pictures'], array (
					$resized,
					$item ['pictures'] 
			) ) );
			if (($item ['action'] >= self::ACTION_SAVEDRAFT) && ($resized != $item ['pictures'])) {
				LogUtil::registerStatus ( News_Controller_User::_fn ( 'Article now has draft status, since the picture was not uploaded.', 'Article now has draft status, since not all pictures were uploaded.', $item ['pictures'], array (
						$resized,
						$item ['pictures'] 
				) ) );
			}
		}
		
		// release pagelock
		if (ModUtil::available ( 'PageLock' )) {
			ModUtil::apiFunc ( 'PageLock', 'user', 'releaseLock', array (
					'lockName' => "Newsnews{$item['sid']}" 
			) );
		}
		
		if ($item ['action'] == self::ACTION_SAVEDRAFT_RETURN) {
			SessionUtil::setVar ( 'newsitem', $item );
			ZSELEX_Controller_User::redirect ( ModUtil::url ( 'News', 'user', 'newitemevent', array (
					'shop_id' => $shop_id 
			) ) );
		}
		// News_Controller_User::redirect(ModUtil::url('News', 'user', 'view'));
		
		if (! is_numeric ( $modifyid )) {
			ZSELEX_Controller_User::redirect ( ModUtil::url ( 'ZSELEX', 'admin', 'createevent', array (
					'shop_id' => $shop_id 
			) ) );
		} elseif (! empty ( $modifyid ) || is_numeric ( $modifyid )) {
			ZSELEX_Controller_User::redirect ( ModUtil::url ( 'ZSELEX', 'admin', 'modifyevent', array (
					'shop_id' => $shop_id,
					'eventId' => $modifyid 
			) ) );
		}
	}
	
	/**
	 * shop page
	 * this function is for displaying shop info in owner's minisite
	 */
	public function shop($args) {
		
		// return false;
		// echo "helloo";
		// $group = ModUtil::apiFunc('Groups', 'user', 'getusergroups');
		// echo "<pre>"; print_r($group); echo "</pre>";
		// echo $group[0]['gid'];
		// $item = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow', $args = array('table'=>'users' , 'fields'=>array('uid') , 'orderby'=>'uid DESC' ,'limit'=>'0,1'));
		// $item = ModUtil::apiFunc('ZSELEX', 'user', 'deleteItems', $args = array('table'=>'zselex_parent' ,
		// 'where'=>array('childType'=>'SHOP' , 'childId'=>'' , 'parentId'=>'' , 'parentType'=>'SHOPADMIN')));
		// $item = ModUtil::apiFunc('ZSELEX', 'user', 'selectJoinArray', $args = array('table' => 'zselex_serviceshop a', 'fields' => array(), 'joins' => array('LEFT JOIN zselex_shop b ON a.shop_id=b.shop_id',
		// 'LEFT JOIN users c on c.uid=a.user_id')));
		// $item = ModUtil::apiFunc('ZSELEX', 'user', 'selectJoinRow', array('table' => 'zselex_parent a',
		// 'fields' => array('a.childId as shop_id', 'b.shop_name'),
		// 'joins' => array('LEFT JOIN zselex_shop b ON b.shop_id=a.childId'),
		// 'where' => array("a.parentId=29", "a.parentType='SHOPADMIN'", "a.childType='SHOP'")));
		$shop_id = FormUtil::getPassedValue ( 'id', '', 'REQUEST' );
		
		// print_r($_REQUEST);
		// $objectid = (int) FormUtil::getPassedValue('objectid', null, 'REQUEST');
		// $shopName = FormUtil::getPassedValue('shopName', '', 'REQUEST');
		
		$shoptitle = FormUtil::getPassedValue ( 'shoptitle', '', 'REQUEST' );
		
		if (empty ( $shop_id ) && empty ( $shoptitle )) {
			
			return LogUtil::registerError ( $this->__ ( 'Error! Type not found.' ) );
		}
		
		// echo "shop id :" . $shop_id;
		$shop = array ();
		if (! empty ( $shop_id )) {
			// echo "comes here";
			// $item = ModUtil::apiFunc('ZSELEX', 'user', 'getShop_Shoppage', array('shop_id' => $shop_id));
			
			$item = ModUtil::apiFunc ( 'ZSELEX', 'user', 'selectJoinRow', array (
					'table' => 'zselex_shop a',
					'fields' => array (
							'*',
							'a.shop_id as shop_id' 
					),
					'joins' => array (
							"LEFT JOIN zselex_files b ON b.shop_id=a.shop_id AND b.defaultImg='1'",
							"LEFT JOIN zselex_shop_owners ow ON ow.shop_id=a.shop_id",
							"LEFT JOIN users u ON u.uid=ow.user_id",
							"LEFT JOIN zselex_shop_gallery g ON a.shop_id=g.shop_id AND g.defaultImg='1'" 
					),
					'where' => array (
							"a.shop_id=$shop_id" 
					) 
			) );
		} else {
			// echo $shoptitle; exit;
			// $item = ModUtil::apiFunc('ZSELEX', 'user', 'getShop_Shoppage', array('shoptitle' => $shoptitle));
			$item = ModUtil::apiFunc ( 'ZSELEX', 'user', 'selectJoinRow', array (
					'table' => 'zselex_shop a',
					'fields' => array (
							'*',
							'a.shop_id as shop_id' 
					),
					'joins' => array (
							"LEFT JOIN zselex_files b ON b.shop_id=a.shop_id AND b.defaultImg='1'",
							"LEFT JOIN zselex_shop_owners ow ON ow.shop_id=a.shop_id",
							"LEFT JOIN users u ON u.uid=ow.user_id",
							"LEFT JOIN zselex_shop_gallery g ON a.shop_id=g.shop_id AND g.defaultImg='1'" 
					),
					'where' => array (
							"a.urltitle='" . $shoptitle . "'" 
					) 
			) );
			
			$shopsId = $item ['shop_id'];
			$shopsname = $item ['shop_name'];
			// echo "<pre>";print_r($item);echo "</pre>"; exit;
			
			System::queryStringSetVar ( 'shop_id', $shopsId );
			System::queryStringSetVar ( 'shopName', $shopsname );
			
			$id = $_REQUEST ['shop_id'];
		}
		$shop_id = ! empty ( $_REQUEST ['shop_id'] ) ? $_REQUEST ['shop_id'] : $_REQUEST ['id'];
		if (empty ( $shop_id )) {
			return LogUtil::registerError ( $this->__ ( 'Error! Type not found.' ) );
		}
		
		$this->view->assign ( 'shop_id', $shop_id );
		
		if (! empty ( $shop_id )) {
			
			$defaultImage = ModUtil::apiFunc ( 'ZSELEX', 'user', 'selectRow', array (
					'table' => 'zselex_files',
					'where' => array (
							"shop_id='" . $shop_id . "'",
							"defaultImg=1" 
					) 
			) );
			
			if (count ( $defaultImage ['defaultImg'] ) < 1) {
				
				$imagez = ModUtil::apiFunc ( 'ZSELEX', 'user', 'selectArray', array (
						'table' => 'zselex_files',
						'where' => array (
								"shop_id='" . $shop_id . "'" 
						),
						'orderby' => "defaultImg DESC" 
				) );
				
				$val = 1;
			} else {
				$imagez = ModUtil::apiFunc ( 'ZSELEX', 'user', 'selectArray', array (
						'table' => 'zselex_files',
						'where' => array (
								"shop_id='" . $shop_id . "'" 
						),
						'orderby' => "defaultImg DESC" 
				) );
				$val = 2;
			}
		}
		
		// echo "<pre>"; print_r($imagez); echo "</pre>";
		
		$shopImage = ! empty ( $defaultImage ['name'] ) ? $defaultImage ['name'] : $images ['name'];
		
		// echo $shop_id;
		if (! ( int ) ($shop_id)) {
			return LogUtil::registerError ( $this->__ ( 'Error! Type not found.' ) );
		}
		
		$loguser = UserUtil::getVar ( 'uid' );
		
		$shop = $item;
		$perm = '';
		if (SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_ADMIN )) {
			$perm = '1';
		}
		// if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
		// $perm = '1';
		// }
		
		$miniShopLinkStrt = '';
		$miniShopLinkEnd = '';
		
		$checkMiniShop = ModUtil::apiFunc ( 'ZSELEX', 'user', 'selectRow', $args = array (
				'table' => 'zselex_serviceshop',
				'where' => array (
						"shop_id=$shop_id",
						"type='minishop'" 
				),
				'fields' => array (
						'quantity' 
				) 
		) );
		
		if (! empty ( $checkMiniShop ['quantity'] )) {
			$miniShopLinkStrt = "<a href=" . ModUtil::url ( 'ZSELEX', 'user', 'minishop', array (
					'shop_id' => $shop_id 
			) ) . ">";
			$miniShopLinkEnd = "</a>";
		}
		// ModUtil::url('ZSELEX', 'user', 'modifytype', array('type_id' => $item['type_id']));
		// echo $id;
		// $link = ModUtil::url('ZSELEX', 'user', 'newitem', array('shop_id' => $id));
		// echo "<pre>"; print_r($shop); echo "</pre>";
		// $this->view->assign('link', $link);
		$this->view->assign ( 'perm', $perm );
		
		$this->view->assign ( 'miniShopLinkStrt', $miniShopLinkStrt );
		$this->view->assign ( 'miniShopLinkEnd', $miniShopLinkEnd );
		
		$this->view->assign ( 'imageVal', $val );
		
		$this->view->assign ( 'loguser', $loguser );
		$this->view->assign ( 'shopitem', $shop );
		$this->view->assign ( 'shopImage', $shopImage );
		
		$this->view->assign ( 'imagez', $imagez );
		
		return $this->view->fetch ( 'user/shop.tpl' );
	}
	public function shopPageProducts($args) {
		
		// echo "Shop Id: " . $_REQUEST['shop_id'];
		// echo $_SESSION['linkservice'];
		if ($_REQUEST ['func'] == 'viewShopProducts') {
			return false;
		}
		
		if (! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_ADMIN )) {
			if ($_SESSION ['linkservice'] == 'no') {
				return false;
			}
		}
		
		$shop_id = $_REQUEST ['shop_id'];
		$loguser = UserUtil::getVar ( 'uid' );
		
		$items = array ();
		$id = ( int ) FormUtil::getPassedValue ( 'id', '', 'REQUEST' ); // normal shop id
		
		if (! empty ( $id )) {
			$shop_id = $id;
		} elseif (! empty ( $_REQUEST ['shop_id'] )) {
			$shop_id = FormUtil::getPassedValue ( 'shop_id', '', 'REQUEST' ); // query string passed shop id
		} else {
			$shop_id = $_REQUEST ['shop_idnewItem']; // query string through newitem function
		}
		
		if (! empty ( $shop_id )) {
			// echo $shop_id;
			$id = $shop_id;
			$fields = array (
					'shoptype_id' 
			);
			$obj = DBUtil::selectObjectByID ( 'zselex_shop', $id, 'shop_id' );
			
			// echo "<pre>"; print_r($obj); echo "</pre>";
			// echo $obj['shoptype_id'];
			
			if ($obj ['shoptype_id'] == '2') { // ISHOP
				$sql = "SELECT p.* FROM zselex_products p , zselex_shop s where s.shop_id=$id AND s.shop_id=p.shop_id  ORDER BY RAND() limit 0,2";
				$items = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'execteQuery', $sql );
			} elseif ($obj ['shoptype_id'] == '1') { // ZENCART
				$items = ModUtil::apiFunc ( 'ZSELEX', 'user', 'getZenCartProducts', $args = $obj );
			}
			
			// echo "<pre>"; print_r($items); echo "</pre>";
			
			$this->view->assign ( 'shoptype', $obj ['shoptype_id'] );
			
			$this->view->assign ( 'products', $items );
			// $this->view->assign('obj', $obj);
			return $this->view->fetch ( 'user/shoppageproducts.tpl' );
		}
	}
	public function viewShopProducts($args) {
		if (! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_ADMIN ) && ! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_ADD ) && ! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_EDIT )) { // normal users
		                                                                 // echo "comes here";
			$serviceExist = ModUtil::apiFunc ( 'ZSELEX', 'user', 'selectArray', $args = array (
					'table' => ' zselex_serviceshop',
					'where' => array (
							"shop_id=$shop_id",
							"type='linktoshop' OR type='minishop'" 
					) 
			) );
			
			if (count ( $serviceExist ) < 1) {
				$_SESSION ['linkservice'] = 'no';
				return LogUtil::registerError ( $this->__ ( 'Error! Sorry, no page has been configured for this site.' ) );
			} else {
				$_SESSION ['linkservice'] = 'yes';
			}
		} elseif (! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_ADMIN ) && SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_ADD )) {
			// shop owners
			$serviceExist = ModUtil::apiFunc ( 'ZSELEX', 'user', 'selectRow', $args = array (
					'table' => ' zselex_shop',
					'where' => array (
							"shop_id=$shop_id",
							"user_id='" . UserUtil::getVar ( 'uid' ) . "'" 
					) 
			) );
			if ($serviceExist ['shop_id'] < 1) {
				$_SESSION ['linkservice'] = 'no';
				return LogUtil::registerError ( $this->__ ( 'Error! Sorry, no page has been configured for this site.' ) );
			} else {
				$_SESSION ['linkservice'] = 'yes';
			}
		} elseif (! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_ADMIN ) && ! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_ADD ) && SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_EDIT )) {
			// shop admins
			$serviceExist = ModUtil::apiFunc ( 'ZSELEX', 'user', 'selectRow', $args = array (
					'table' => ' zselex_shop a , zselex_parent b',
					'where' => array (
							"a.shop_id=$shop_id",
							"b.parentId='" . UserUtil::getVar ( 'uid' ) . "'",
							"b.cr_uid=a.user_id",
							"b.childType='SHOP'",
							"b.parentType='SHOPADMIN'" 
					) 
			) );
			if ($serviceExist ['shop_id'] < 1) {
				$_SESSION ['linkservice'] = 'no';
				return LogUtil::registerError ( $this->__ ( 'Error! Sorry, no page has been configured for this site.' ) );
			} else {
				$_SESSION ['linkservice'] = 'yes';
			}
		}
		// echo "comes here";
		
		$getShopType = ModUtil::apiFunc ( 'ZSELEX', 'user', 'selectRow', array (
				'table' => 'zselex_shop_types a , zselex_shop b',
				'where' => array (
						"b.shop_id=$shop_id",
						"b.shoptype_id=a.shoptype_id" 
				),
				'fields' => array (
						'a.shopTypeName' 
				) 
		) );
		
		$shopType = $getShopType ['shopTypeName'];
		
		$items = array ();
		if ($shopType == 'iSHOP') {
			$items = ModUtil::apiFunc ( 'ZSELEX', 'user', 'selectArray', array (
					'table' => 'zselex_products a , zselex_shop b',
					'where' => array (
							"a.shop_id=$shop_id",
							"a.shop_id=b.shop_id" 
					) 
			) );
		} elseif ($shopType == 'zSHOP') {
			
			// echo $shop_id;
			// echo "comes here";
			
			$obj = DBUtil::selectObjectByID ( 'zselex_shop', $shop_id, 'shop_id' );
			
			$items = ModUtil::apiFunc ( 'ZSELEX', 'user', 'getZenCartProductsMiniShop', $args = $obj );
		}
		
		// echo "<pre>"; print_r($items); echo "</pre>";
		
		$this->view->assign ( 'shoptype', $shopType );
		$this->view->assign ( 'products', $items );
		return $this->view->fetch ( 'user/shopproducts.tpl' );
	}
	public function minishop($args) {
		$shop_id = $_REQUEST ['shop_id'];
		
		/*
		 * $checkMiniShop = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow', $args = array('table' => 'zselex_serviceshop',
		 * 'where' => array("shop_id=$shop_id", "type='minishop'"), 'fields' => array('quantity')));
		 *
		 *
		 * if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
		 *
		 * if (empty($checkMiniShop['quantity'])) {
		 * $_SESSION['linkservice'] = 'no';
		 * return LogUtil::registerError($this->__('Error! No minishop here.'));
		 * } else {
		 * $_SESSION['linkservice'] = 'yes';
		 * }
		 * }
		 *
		 */
		
		$serviceExist = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getCount', $args = array (
				'table' => 'zselex_serviceshop',
				'where' => "shop_id=$shop_id AND type='minishop'" 
		) );
		
		if ($serviceExist < 1) {
			// echo "comes hereee";
			return LogUtil::registerError ( $this->__ ( 'Error! Sorry, no minishop has been configured.' ) );
		}
		
		return $this->view->fetch ( 'user/page.tpl' );
	}
	public function shopServicesMenu($args) {
		if ($_REQUEST ['func'] == 'viewShopProducts') {
			return false;
		}
		
		if (! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_ADMIN )) {
			if ($_SESSION ['linkservice'] == 'no') {
				return false;
			}
		}
		
		$shop_id = ! empty ( $_REQUEST ['shop_id'] ) ? FormUtil::getPassedValue ( 'shop_id', '', 'REQUEST' ) : $_REQUEST ['shop_idnewItem'];
		$user_id = UserUtil::getVar ( 'uid' );
		
		if (! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_ADD )) {
			return false;
		}
		$services = ModUtil::apiFunc ( 'ZSELEX', 'user', 'shopServicesMenu', $args = array (
				'user_id' => $user_id,
				'shop_id' => $shop_id 
		) );
		// echo "<pre>"; print_r($services); echo "</pre>";
		$this->view->assign ( 'services', $services );
		
		return $this->view->fetch ( 'user/servicemenu.tpl' );
	}
	public function otherShops() {
		// echo "TESTTTTT";
		$user_id = UserUtil::getVar ( 'uid' );
		if (! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_ADD )) {
			return false;
		}
		$shop_id = $_REQUEST ['shop_id'];
		$otherShops = ModUtil::apiFunc ( 'ZSELEX', 'user', 'selectArrays', $args = array (
				'table' => 'zselex_shop',
				'where' => array (
						"user_id=$user_id",
						"shop_id!=$shop_id" 
				) 
		) );
		$count = count ( $otherShops );
		if ($count > 1) {
			$this->view->assign ( 'shops', $otherShops );
			return $this->view->fetch ( 'user/othershops.tpl' );
		}
	}
	public function newitems($args) {
		$shop_id = FormUtil::getPassedValue ( 'shop_id', '', 'REQUEST' );
		$this->redirect ( ModUtil::url ( 'ZSELEX', 'user', 'newitem' ) );
	}
	public function serviceDisabled($type) {
		return ModUtil::apiFunc ( 'ZSELEX', 'admin', 'serviceDisabled', $type );
	}
	public function shopPermission($shop_id) {
		$shop_id = $shop_id;
		$user_id = UserUtil::getVar ( 'uid' );
		$perm = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'shopPermission', array (
				'shop_id' => $shop_id,
				'user_id' => $user_id 
		) );
		return $perm;
	}
	public function serviceCheck($args) {
		$shop_id = $shop_id;
		$user_id = UserUtil::getVar ( 'uid' );
		$perm = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'serviceCheck', $args );
		return $perm;
	}
	
	/**
	 * News module user controller override class function
	 * Also used directly
	 */
	public function newitem2($args) {
		
		// News_Controller_User::check();
		// News_Controller_User::preview($args);
		// echo "<pre>"; print_r($_SESSION); echo "</pre>";
		echo "New Item";
		exit ();
		// $this->newitem($args);
		return News_Controller_User::newitem ( $args );
	}
	
	/**
	 * News module user controller override class function
	 */
	public function preview($args) {
		// echo "zselex news preview";
		$title = $args ['title'];
		$hometext = $args ['hometext'];
		$hometextcontenttype = $args ['hometextcontenttype'];
		$bodytext = $args ['bodytext'];
		$bodytextcontenttype = $args ['bodytextcontenttype'];
		$notes = $args ['notes'];
		$sid = $args ['sid'];
		$pictures = $args ['pictures'];
		$temp_pictures = $args ['temp_pictures'];
		
		// format the contents if needed
		if ($hometextcontenttype == 0) {
			$hometext = nl2br ( $hometext );
		}
		if ($bodytextcontenttype == 0) {
			$bodytext = nl2br ( $bodytext );
		}
		$this->view->setCaching ( false );
		
		$this->view->assign ( 'preview', array (
				'title' => $title,
				'hometext' => $hometext,
				'bodytext' => $bodytext,
				'notes' => $notes,
				'sid' => $sid,
				'pictures' => $pictures,
				'temp_pictures' => $temp_pictures 
		) );
		
		return $this->view->fetch ( 'user/News/preview.tpl' );
	}
	public function chk() {
		echo "testing function";
		return $this->view->fetch ( 'user/viewtest.tpl' );
	}
	public function viewshoparticles($args) {
		
		// echo "Shop Articles";
		// echo $_GET['shop_id'];
		$shop_id = $_REQUEST ['shop_id'];
		
		$this->throwForbiddenUnless ( SecurityUtil::checkPermission ( 'News::', '::', ACCESS_OVERVIEW ), LogUtil::getErrorMsgPermission () );
		
		$user_id = UserUtil::getVar ( 'uid' );
		if ($this->shopPermission ( $shop_id ) < 1) {
			return LogUtil::registerPermissionError ();
		}
		// clean the session preview data
		SessionUtil::delVar ( 'newsitem' );
		
		// get all module vars for later use
		$modvars = ModUtil::getVar ( 'News' );
		
		// Get parameters from whatever input we need
		$theme = isset ( $args ['theme'] ) ? strtolower ( $args ['theme'] ) : ( string ) strtolower ( FormUtil::getPassedValue ( 'theme', 'user', 'GET' ) );
		$page = isset ( $args ['page'] ) ? $args ['page'] : ( int ) FormUtil::getPassedValue ( 'page', 1, 'GET' );
		$prop = isset ( $args ['prop'] ) ? $args ['prop'] : ( string ) FormUtil::getPassedValue ( 'prop', null, 'GET' );
		$cat = isset ( $args ['cat'] ) ? $args ['cat'] : ( string ) FormUtil::getPassedValue ( 'cat', null, 'GET' );
		$displayModule = FormUtil::getPassedValue ( 'module', 'X', 'GET' );
		$defaultItemsPerPage = ($displayModule == 'X') ? $modvars ['storyhome'] : $modvars ['itemsperpage'];
		$itemsperpage = isset ( $args ['itemsperpage'] ) ? $args ['itemsperpage'] : ( int ) FormUtil::getPassedValue ( 'itemsperpage', $defaultItemsPerPage, 'GET' );
		$displayonindex = isset ( $args ['displayonindex'] ) ? ( int ) $args ['displayonindex'] : FormUtil::getPassedValue ( 'displayonindex', null, 'GET' );
		
		$allowedThemes = array (
				'user',
				'rss',
				'atom',
				'printer' 
		);
		$theme = in_array ( $theme, $allowedThemes ) ? $theme : 'user';
		
		// work out page size from page number
		$startnum = (($page - 1) * $itemsperpage) + 1;
		
		$lang = ZLanguage::getLanguageCode ();
		
		// check if categorization is enabled
		if ($modvars ['enablecategorization']) {
			// get the categories registered for News
			$catregistry = CategoryRegistryUtil::getRegisteredModuleCategories ( 'News', 'news' );
			$properties = array_keys ( $catregistry );
			
			// validate the property
			// and build the category filter - mateo
			if (! empty ( $prop ) && in_array ( $prop, $properties ) && ! empty ( $cat )) {
				if (! is_numeric ( $cat )) {
					$rootCat = CategoryUtil::getCategoryByID ( $catregistry [$prop] );
					$cat = CategoryUtil::getCategoryByPath ( $rootCat ['path'] . '/' . $cat );
				} else {
					$cat = CategoryUtil::getCategoryByID ( $cat );
				}
				$catname = isset ( $cat ['display_name'] [$lang] ) ? $cat ['display_name'] [$lang] : $cat ['name'];
				
				if (! empty ( $cat ) && isset ( $cat ['path'] )) {
					// include all it's subcategories and build the filter
					$categories = CategoryUtil::getCategoriesByPath ( $cat ['path'], '', 'path' );
					$catstofilter = array ();
					foreach ( $categories as $category ) {
						$catstofilter [] = $category ['id'];
					}
					$catFilter = array (
							$prop => $catstofilter 
					);
				} else {
					LogUtil::registerError ( $this->__ ( 'Error! Invalid category passed.' ) );
				}
			}
		}
		
		// get matching news articles
		$items = ModUtil::apiFunc ( 'News', 'block', 'getall', array (
				'startnum' => $startnum,
				'numitems' => $itemsperpage,
				// 'status' => News_Api_User::STATUS_PUBLISHED,
				'status' => 0,
				'displayonindex' => $displayonindex,
				'filterbydate' => true,
				'category' => isset ( $catFilter ) ? $catFilter : null, // get all method doesn't appear to want a category arg
				'catregistry' => isset ( $catregistry ) ? $catregistry : null 
		) );
		
		// echo "<pre>"; print_r($items); echo "</pre>";
		
		if ($items == false) {
			if ($modvars ['enablecategorization'] && isset ( $catFilter )) {
				LogUtil::registerStatus ( $this->__f ( 'No articles currently published under the \'%s\' category.', $catname ) );
			} else {
				LogUtil::registerStatus ( $this->__ ( 'No articles currently published.' ) );
			}
		}
		
		// assign various useful template variables
		$this->view->assign ( 'startnum', $startnum );
		$this->view->assign ( 'lang', $lang );
		
		// assign the root category
		$this->view->assign ( 'category', $cat );
		$this->view->assign ( 'catname', isset ( $catname ) ? $catname : '' );
		
		$shop_id = $_REQUEST ['shop_id'];
		$serviceargs = array (
				'shop_id' => $shop_id,
				'type' => 'youtubelink' 
		);
		$serviceargs = array (
				'shop_id' => $shop_id,
				'type' => 'youtubelink' 
		);
		$serviceExist = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'serviceExist', $serviceargs );
		
		$newsitems = array ();
		// Loop through each item and display it
		foreach ( $items as $item ) {
			// ///////////////////////////finding you tube link and remove if service not bought/////////////////////////////////////
			if ($serviceExist < 1) {
				$pattern1 = "!<iframe[^>]+>(.*?)</iframe>!";
				$pattern2 = "!<object[^>]+>(.*?)</object>!";
				$pattern3 = "!<embed[^>]+>(.*?)</embed>!";
				$replacement = "";
				
				$item ['hometext'] = preg_replace ( $pattern1, $replacement, $item ['hometext'] );
				$item ['hometext'] = preg_replace ( $pattern2, $replacement, $item ['hometext'] );
				$item ['hometext'] = preg_replace ( $pattern3, $replacement, $item ['hometext'] );
				
				$item ['bodytext'] = preg_replace ( $pattern1, $replacement, $item ['bodytext'] );
				$item ['bodytext'] = preg_replace ( $pattern2, $replacement, $item ['bodytext'] );
				$item ['bodytext'] = preg_replace ( $pattern3, $replacement, $item ['bodytext'] );
			}
			// /////////////////////////////////////////////////////////////////////////////////////////////////////
			// display if it's published and the displayonindex match (if set)
			if (($item ['published_status'] == 0) && (! isset ( $displayonindex ) || $item ['displayonindex'] == $displayonindex)) {
				
				$template = 'user/index.tpl';
				if (! $this->view->is_cached ( $template, $item ['sid'] )) {
					// $info is array holding raw information.
					// Used below and also passed to the theme - jgm
					$info = ModUtil::apiFunc ( 'News', 'block', 'getArticleInfo', $item );
					
					// $links is an array holding pure URLs to
					// specific functions for this article.
					// Used below and also passed to the theme - jgm
					$links = ModUtil::apiFunc ( 'News', 'block', 'getArticleLinks', $info );
					
					// $preformat is an array holding chunks of
					// preformatted text for this article.
					// Used below and also passed to the theme - jgm
					$preformat = ModUtil::apiFunc ( 'News', 'block', 'getArticlePreformat', array (
							'info' => $info,
							'links' => $links 
					) );
					
					// echo "<pre>"; print_r($preformat); echo "</pre>";
					
					$this->view->assign ( array (
							'info' => $info,
							'links' => $links,
							'shop_id' => $_REQUEST ['shop_id'],
							'preformat' => $preformat 
					) );
				}
				
				$newsitems [] = $this->view->fetch ( $template, $item ['sid'] );
			}
		}
		
		// The items that are displayed on this overview page depend on the individual
		// user permissions. Therefor, we can not cache the whole page.
		// The single entries are cached, though.
		$this->view->setCaching ( false );
		
		// Display the entries
		$this->view->assign ( 'newsitems', $newsitems );
		
		// Assign the values for the smarty plugin to produce a pager
		$this->view->assign ( 'pager', array (
				'numitems' => ModUtil::apiFunc ( 'News', 'block', 'countitems', array (
						'status' => 0,
						'filterbydate' => true,
						'displayonindex' => $displayonindex,
						'category' => isset ( $catFilter ) ? $catFilter : null 
				) ),
				'itemsperpage' => $itemsperpage 
		) );
		
		// Return the output that has been generated by this function
		
		return $this->view->fetch ( 'user/viewshoparticles.tpl' );
	}
	public function serviceExist($args) {
		$exist = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'serviceExist', $args );
		return $exist;
	}
	public function display($args) {
		
		// exit;
		// echo "Shop Id: ".$_REQUEST['shop_id'];
		// Get parameters from whatever input we need
		$sid = ( int ) FormUtil::getPassedValue ( 'sid', null, 'REQUEST' );
		$objectid = ( int ) FormUtil::getPassedValue ( 'objectid', null, 'REQUEST' );
		$page = ( int ) FormUtil::getPassedValue ( 'page', 1, 'REQUEST' );
		$title = FormUtil::getPassedValue ( 'title', null, 'REQUEST' );
		$year = FormUtil::getPassedValue ( 'year', null, 'REQUEST' );
		$monthnum = FormUtil::getPassedValue ( 'monthnum', null, 'REQUEST' );
		$monthname = FormUtil::getPassedValue ( 'monthname', null, 'REQUEST' );
		$day = FormUtil::getPassedValue ( 'day', null, 'REQUEST' );
		
		// User functions of this type can be called by other modules
		extract ( $args );
		
		// echo "<pre>"; print_r($args); echo "</pre>";
		
		$theme = isset ( $args ['theme'] ) ? strtolower ( $args ['theme'] ) : ( string ) strtolower ( FormUtil::getPassedValue ( 'theme', 'user', 'GET' ) );
		$allowedThemes = array (
				'user',
				'printer' 
		);
		$theme = in_array ( $theme, $allowedThemes ) ? $theme : 'user';
		
		// At this stage we check to see if we have been passed $objectid, the
		// generic item identifier
		if ($objectid) {
			
			$sid = $objectid;
		}
		
		// Validate the essential parameters
		if ((empty ( $sid ) || ! is_numeric ( $sid )) && (empty ( $title ))) {
			return LogUtil::registerArgsError ();
		}
		if (! empty ( $title )) {
			
			unset ( $sid );
		}
		
		// Set the default page number
		if ($page < 1 || ! is_numeric ( $page )) {
			$page = 1;
		}
		
		// increment the read count
		if ($page == 1) {
			
			if (isset ( $sid )) {
				
				ModUtil::apiFunc ( 'News', 'user', 'incrementreadcount', array (
						'sid' => $sid 
				) );
			} else {
				
				ModUtil::apiFunc ( 'News', 'user', 'incrementreadcount', array (
						'title' => $title 
				) );
			}
		}
		
		// For caching reasons you must pass a cache ID
		if (isset ( $sid )) {
			$this->view->setCacheId ( $sid . $page );
		} else {
			$this->view->setCacheId ( $title . $page );
		}
		
		// Get the news story
		if (! SecurityUtil::checkPermission ( 'News::', "::", ACCESS_ADD )) {
			if (isset ( $sid )) {
				
				$item = ModUtil::apiFunc ( 'News', 'user', 'get', array (
						'sid' => $sid,
						'status' => 0 
				) );
			} else {
				
				$item = ModUtil::apiFunc ( 'News', 'user', 'get', array (
						'title' => $title,
						'year' => $year,
						'monthname' => $monthname,
						'monthnum' => $monthnum,
						'day' => $day,
						'status' => 0 
				) );
				$sid = $item ['sid'];
				System::queryStringSetVar ( 'sid', $sid );
			}
		} else {
			if (isset ( $sid )) {
				$item = ModUtil::apiFunc ( 'News', 'user', 'get', array (
						'sid' => $sid 
				) );
			} else {
				
				$item = ModUtil::apiFunc ( 'News', 'user', 'get', array (
						'title' => $title,
						'year' => $year,
						'monthname' => $monthname,
						'monthnum' => $monthnum,
						'day' => $day 
				) );
				$sid = $item ['sid'];
				System::queryStringSetVar ( 'sid', $sid );
			}
		}
		
		// echo "<pre>"; print_r($item); echo "</pre>";
		// ///////////////////////////finding you tube and remove/////////////////////////////////////
		
		$shop_id = $_REQUEST ['shop_id'];
		$serviceargs = array (
				'shop_id' => $shop_id,
				'type' => 'youtubelink' 
		);
		$serviceExist = $this->serviceExist ( $serviceargs );
		
		if ($serviceExist < 1) {
			
			$item ['hometext'] = preg_replace ( "!<iframe[^>]+>(.*?)</iframe>!", "", $item ['hometext'] );
			$item ['hometext'] = preg_replace ( "!<object[^>]+>(.*?)</object>!", "", $item ['hometext'] );
			$item ['hometext'] = preg_replace ( "!<embed[^>]+>(.*?)</embed>!", "", $item ['hometext'] );
			
			$item ['bodytext'] = preg_replace ( "!<iframe[^>]+>(.*?)</iframe>!", "", $item ['bodytext'] );
			$item ['bodytext'] = preg_replace ( "!<object[^>]+>(.*?)</object>!", "", $item ['bodytext'] );
			$item ['bodytext'] = preg_replace ( "!<embed[^>]+>(.*?)</embed>!", "", $item ['bodytext'] );
		}
		
		// ////////////////////////////////////////////////////////////////////////////
		// echo "<pre>"; print_r($item); echo "</pre>";
		
		if ($item === false) {
			return LogUtil::registerError ( $this->__ ( 'Error! No such article found.' ), 403 );
		}
		
		// Explode the story into an array of seperate pages
		$allpages = explode ( '<!--pagebreak-->', $item ['bodytext'] );
		
		// Set the item bodytext to be the required page
		// nb arrays start from zero, pages from one
		$item ['bodytext'] = $allpages [$page - 1];
		$numpages = count ( $allpages );
		unset ( $allpages );
		
		// If the pagecount is greater than 1 and we're not on the frontpage
		// don't show the hometext
		if ($numpages > 1 && $page > 1) {
			$item ['hometext'] = '';
		}
		
		// $info is array holding raw information.
		// Used below and also passed to the theme - jgm
		$info = ModUtil::apiFunc ( 'News', 'user', 'getArticleInfo', $item );
		
		// $links is an array holding pure URLs to specific functions for this article.
		// Used below and also passed to the theme - jgm
		$links = ModUtil::apiFunc ( 'News', 'user', 'getArticleLinks', $info );
		
		// $preformat is an array holding chunks of preformatted text for this article.
		// Used below and also passed to the theme - jgm
		$preformat = ModUtil::apiFunc ( 'News', 'user', 'getArticlePreformat', array (
				'info' => $info,
				'links' => $links 
		) );
		
		// set the page title
		if ($numpages <= 1) {
			PageUtil::setVar ( 'title', $info ['title'] );
		} else {
			PageUtil::setVar ( 'title', $info ['title'] . ' :: ' . $this->__f ( 'page %s', $page ) );
		}
		
		// Assign the story info arrays
		$this->view->assign ( array (
				'info' => $info,
				'links' => $links,
				'preformat' => $preformat,
				'page' => $page 
		) );
		
		$modvars = $this->getVars ();
		$this->view->assign ( 'lang', ZLanguage::getLanguageCode () );
		
		// get more articletitles in the categories of this article
		if ($modvars ['enablecategorization'] && $modvars ['enablemorearticlesincat']) {
			// check how many articles to display
			if ($modvars ['morearticlesincat'] > 0 && ! empty ( $info ['categories'] )) {
				$morearticlesincat = $modvars ['morearticlesincat'];
			} elseif ($modvars ['morearticlesincat'] == 0 && array_key_exists ( 'morearticlesincat', $info ['attributes'] )) {
				$morearticlesincat = $info ['attributes'] ['morearticlesincat'];
			} else {
				$morearticlesincat = 0;
			}
			if ($morearticlesincat > 0) {
				// get the categories registered for News
				$catregistry = CategoryRegistryUtil::getRegisteredModuleCategories ( 'News', 'news' );
				foreach ( array_keys ( $catregistry ) as $property ) {
					$catFilter [$property] = $info ['categories'] [$property] ['id'];
				}
				// get matching news articles
				$morearticlesincat = ModUtil::apiFunc ( 'News', 'user', 'getall', array (
						'numitems' => $morearticlesincat,
						'status' => 0,
						'filterbydate' => true,
						'category' => $catFilter,
						'catregistry' => $catregistry,
						'query' => array (
								array (
										'sid',
										'!=',
										$sid 
								) 
						) 
				) );
			}
		} else {
			$morearticlesincat = 0;
		}
		$this->view->assign ( 'morearticlesincat', $morearticlesincat );
		
		// Now lets assign the informatation to create a pager for the review
		$this->view->assign ( 'pager', array (
				'numitems' => $numpages,
				'itemsperpage' => 1 
		) );
		
		// Return the output that has been generated by this function
		return $this->view->fetch ( $theme . '/article.tpl' );
	}
	public function testMap() {
		$ip = "122.166.29.86";
		// $ip = $_SERVER['REMOTE_ADDR'];
		$json = file_get_contents ( "http://api.easyjquery.com/ips/?ip=" . $ip . "&full=true" );
		$json = json_decode ( $json, true );
		
		$this->view->assign ( 'userlats', $json ['CityLatitude'] );
		$this->view->assign ( 'userlngs', $json ['CityLongitude'] );
		return $this->view->fetch ( 'user/testmap.tpl' );
	}
	function pdfView($pdf) {
		$pdf = $_REQUEST ['pdf'];
		$shop_id = $_REQUEST ['shop_id'];
		$ownerName = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getOwner', $args = array (
				'shop_id' => $shop_id 
		) );
		// echo $pdf; exit;
		
		$file = "zselexdata/$ownerName/pdfupload/" . $pdf;
		
		if (file_exists ( $file )) {
			header ( 'Content-Description: File Transfer' );
			header ( 'Content-Type: application/pdf' );
			header ( 'Content-Disposition: attachment; filename=' . basename ( $file ) );
			header ( 'Content-Transfer-Encoding: binary' );
			header ( 'Expires: 0' );
			header ( 'Cache-Control: must-revalidate' );
			header ( 'Pragma: public' );
			header ( 'Content-Length: ' . filesize ( $file ) );
			ob_clean ();
			flush ();
			readfile ( $file );
			exit ();
		}
	}
}

// end class def
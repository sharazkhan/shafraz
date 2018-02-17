<?php
/**
 * Copyright  2014
 *
 * ZSELEX
 * Demonstration of Zikula Module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control User interface
 */
class ZSELEX_Controller_Base_User extends Zikula_AbstractController
{
    /**
     * These constants are applicable for News module override
     */
    const ACTION_PREVIEW          = 0;
    const ACTION_SUBMIT           = 1;
    const ACTION_PUBLISH          = 2;
    const ACTION_REJECT           = 3;
    const ACTION_SAVEPENDING      = 4;
    const ACTION_ARCHIVE          = 5;
    const ACTION_SAVEDRAFT        = 6;
    const ACTION_SAVEDRAFT_RETURN = 8;

    protected $current_theme;
    protected $current_theme_path;
    public $ownername;
    public $owner_id;
    public $return_shop_id;

    function postInitialize()
    {
        header("Cache-Control: max-age=300, must-revalidate");
        // echo "helooo";
        $this->current_theme      = System::getVar('Default_Theme');
        $this->current_theme_path = "Themes/".System::getVar('Default_Theme');
        $shop_id                  = $_REQUEST ['shop_id'];
        if (!empty($shop_id)) {
            $ownerInfo = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwnerInfo',
                    $args      = array(
                    'shop_id' => $shop_id
            ));
        }
        $this->ownername = $ownerInfo ['uname'];
        $this->owner_id  = $ownerInfo ['uid'];
        if ($_REQUEST ['func'] == 'payPalReturn') {
            $this->return_shop_id = $_REQUEST ['cm'];
        }
    }

    /**
     * main
     *
     * main view function for end user
     * 
     * @access public
     */
    public function main()
    {
        $this->redirect(ModUtil::url('ZSELEX', 'user', 'view'));
    }

    /**
     * Product detail page
     * 
     * @param type $args
     * @return html
     */
    public function productview($args)
    {

       
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_OVERVIEW), LogUtil::getErrorMsgPermission());
        // EventUtil::registerPersistentModuleHandler('ZSELEX', 'user.gettheme', array('ZSELEX_Listener_User', 'getTheme'));
       // EventUtil::registerPersistentModuleHandler('ZSELEX', 'frontcontroller.exception', array('ZSELEX_Listener_ErrorListner', 'systemError'));
        $_SESSION ['linkedOption']   = array();
        $_SESSION ['otherOption']    = array();
        $_SESSION ['otherOption2']   = array();
        $_SESSION ['checkboxOption'] = array();
        // $pid = (int) FormUtil::getPassedValue('id', isset($args['id']) ? $args['id'] : null, 'REQUEST');
        $productTitle                = FormUtil::getPassedValue('producttitle',
                '', 'REQUEST');
        $product_id                  = FormUtil::getPassedValue('product_id',
                '', 'REQUEST');
        if (!isset($product_id) || empty($product_id)) {

            $product_id = FormUtil::getPassedValue('id', '', 'REQUEST');
        }
        // echo "comes here"; exit;
        $pid = $product_id;
        // echo $pid; exit;
        // echo $productTitle; exit;

        $product = $this->entityManager->getRepository('ZSELEX_Entity_Product')->getProduct(array(
            'product_id' => $product_id,
            'product_title' => $productTitle
        ));
        // echo "<pre>"; print_r($product);echo "</pre>"; exit;

        if ($product == false) {
            return LogUtil::registerError($this->__('Error! Product not found.'));
        }
        // echo "ownername : " . $this->ownername;
        $shop_id   = $product ['shop_id'];
        System::queryStringSetVar('shop_id', $shop_id);
        $shop_name = $product ['shop_name'];
        System::queryStringSetVar('shop_name', $shop_name);
        System::queryStringSetVar('shopName', $shop_name);
        System::queryStringSetVar('products_id', $product ['product_id']);
        System::queryStringSetVar('product_id', $product ['product_id']);
        System::queryStringSetVar('city_name', $product ['city_name']);
        $Id        = FormUtil::getPassedValue('id',
                isset($args ['id']) ? $args ['id'] : null, 'REQUEST');

        $shop_name = FormUtil::getPassedValue('shop_name', '', 'REQUEST');

        $product_id = FormUtil::getPassedValue('products_id', '', 'REQUEST');
        $qnty       = FormUtil::getPassedValue('cart_quantity', '', 'REQUEST');

        $prod_id      = FormUtil::getPassedValue('product_id', '', 'REQUEST');
        $product_link = pnGetBaseURL().ModUtil::url('ZSELEX', 'user',
                'productview', array(
                'id' => $prod_id
        ));

        PageUtil::setVar('title', $shop_name." - ".$product ['product_name']);

        $priceArgs   = array(
            'amount' => $product ['prd_price'],
            'currency_symbol' => '',
            'decimal_point' => ',',
            'thousands_sep' => '.',
            'precision' => '2'
        );
        $price       = ModUtil::apiFunc('ZSELEX', 'user', 'number2currency',
                $priceArgs);
        $metaContent = $product ['prd_description'].' '.$price.' DKK';
        // PageUtil::setVar('description', $metaContent);
        $this->view->assign('metaContent', $metaContent);

        $loggedIn        = UserUtil::isLoggedIn();
        $em              = ServiceUtil::getService('doctrine.entitymanager');
        $shop_detail     = $em->getRepository('ZSELEX_Entity_ShopDetail')->getShopDetails(array(
            'shop_id' => $shop_id
        ));
        $no_payment      = $shop_detail ['no_payment'];
        // echo "comes here"; exit;
        // $start = microtime(true);
        // $tess = $em->getRepository('ZSELEX_Entity_ProductToOptionValue')->findAll();
        $product_options = ModUtil::apiFunc('ZSELEX', 'product',
                'getConfiguredProductOptions',
                array(
                'product_id' => $prod_id,
                'type' => 'front'
        ));

        // echo "<pre>"; print_r($product_options); echo "</pre>";
        // $end = microtime(true);
        // $diff = $end - $start;
        // echo $diff . '<br>';
        // echo "<pre>"; print_r($tess); echo "</pre>";

        setcookie("last_shop_id", $shop_id, time() + (86400 * 7), '/');

        // echo "<pre>"; print_r($product_options); echo "</pre>";

        foreach ($product_options as $key => $val) {

            if (empty($val ['values'])) {
                unset($product_options [$key]);
            }
            foreach ($val ['values'] as $key1 => $val1) {
                // echo "here2<br>";
                // echo "qty: $val1[qty]<br>";

                if ($val1 ['qty'] > 0) {
                    // echo "here3<br>";
                    // }

                    if ($val1 ['parent_option_value_id'] > 0) {
                        // echo "here3<br>";

                        $getVal = ModUtil::apiFunc('ZSELEX', 'user',
                                'selectJoinRow',
                                $args   = array(
                                'table' => 'zselex_product_options_values a',
                                'fields' => array(
                                    'a.option_value,b.option_name'
                                ),
                                'where' => array(
                                    "a.option_value_id=$val1[parent_option_value_id]"
                                ),
                                'joins' => array(
                                    "LEFT JOIN zselex_product_options b ON b.option_id=a.option_id"
                                )
                                )
                                // 'orderby' => 'a.sort_order ASC'
                        );

                        // echo "<pre>"; print_r($getVal); echo "</pre>";

                        $product_options [$key] ['values'] [$key1] ['parent_option_name']
                            = $getVal ['option_name'];
                        $product_options [$key] ['values'] [$key1] ['parent_option_value']
                            = $getVal ['option_value'];
                    }
                } else {
                    unset($product_options [$key] ['values'] [$key1]);
                }
            }
        }

        foreach ($product_options as $key => $val) {
            if (empty($val ['values'])) {
                unset($product_options [$key]);
            }
        }

        // echo "<pre>"; print_r($product_options); echo "</pre>";
        // echo "<pre>"; print_r($product); echo "</pre>";

        $user_id      = UserUtil::getVar('uid');
        $perm         = ModUtil::apiFunc('ZSELEX', 'admin', 'shopPermission',
                array(
                'shop_id' => $shop_id,
                'user_id' => $user_id
        ));
        $option_count = count($product_options);
        $this->view->assign('product_options', $product_options);
        $this->view->assign('option_count', $option_count);
        $this->view->assign('no_payment', $no_payment);
        $this->view->assign('ownername', $this->ownername);
        $this->view->assign('product', $product);
        $this->view->assign('product_id', $product_id);
        $this->view->assign('quantity', $qnty);
        $this->view->assign('product_link', $product_link);
        $this->view->assign('loggedIn', $loggedIn);
        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('perm', $perm);
        // $this->view->assign('quantity_discount', $quantity_discount);
        return $this->view->fetch('user/productview.tpl');
    }

    public function usercart($args)
    {
        // System::setVar('Default_Theme', 'SeaBreeze');
        $this->redirect(ModUtil::url('ZSELEX', 'user', 'cart'));
    }

    public static function _getThemeFilterEvent($theme_name, $type)
    {
        // echo "hereeee";
        $event = new Zikula_Event('user.gettheme', null,
            array(
            'type' => $type
            ), $theme_name);
        return EventUtil::notify($event)->getData();
    }

    public function carttotal()
    {
        // $view = Zikula_View::getInstance('ZSELEX');
        // $view->setCaching(false);
        // echo "<pre>"; print_r($_SESSION['cart']); echo "</pre>"; exit;
        // echo "comes here";
        // Zikula_View::getInstance('ZSELEX')->setCaching(false);
        $finalCookie = array();
        $total       = 0;
        if (UserUtil::isLoggedIn()) {
            // echo count($_SESSION['user_cart']);

            $user_id          = UserUtil::getVar('uid');
            $get_products     = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                    $getargs          = array(
                    'table' => 'zselex_cart',
                    'where' => "user_id=$user_id"
                    )
                    // 'fields' => array('id', 'quantity', 'availed')
            );
            $cart_unserialize = unserialize($get_products ['cart_content']);
            // echo "<pre>"; print_r($cart_unserialize); echo "</pre>";
            $cart_unserialize = ZSELEX_Controller_User::validatecart($cart_unserialize);
            if (!empty($cart_unserialize)) {
                $c = 0;
                foreach ($cart_unserialize as $k => $vl) {
                    foreach ($vl as $l => $m) {
                        $total += $m ['FINALPRICE'];
                        $qty += $m ['QUANTITY'];
                        $c ++;
                    }
                }
            }
            // echo $total;
            // echo $c;
            // return $final_total;
            // return $total;
            return array(
                'total' => $total,
                'count' => $qty
            );
        }
        if (count($_COOKIE ['cart']) > 0) {
            $c = 0;
            foreach ($_COOKIE ['cart'] as $key => $val) {
                foreach ($_COOKIE ['cart'] [$key] as $key1 => $val1) {
                    $finalCookie [$key] [$key1] = json_decode($val1, true);
                    $c ++;
                }
            }
        }

        if (count($_SESSION ['cart']) > 0) {
            $s = 0;
            foreach ($_SESSION ['cart'] as $key => $val) {
                foreach ($_SESSION ['cart'] [$key] as $key1 => $val1) {
                    // $finalCart[$key][$key1] = $val1;
                    $s ++;
                }
            }
        }

        $sessionCount = $s;
        $cookieCount  = $c;
        $count        = (empty($cookieCount)) ? $sessionCount : $cookieCount;

        $GRANDTOTAL = '';
        if ($sessionCount > $cookieCount) {
            $c1 = 0;
            if (count($_SESSION ['cart']) > 0) {
                foreach ($_SESSION ['cart'] as $val) {
                    foreach ($val as $v) {
                        $grandPrice [] = $v ['FINALPRICE'];
                        $total += $v ['FINALPRICE'];
                        $c1 ++;
                    }
                }
                $GRANDTOTAL = array_sum($grandPrice);
            }
        } else {
            $c1 = 0;
            if (count($_COOKIE ['cart']) > 0) {

                foreach ($finalCookie as $val) {
                    // echo 'hii';
                    foreach ($val as $v) {
                        $grandPrice [] = $v ['FINALPRICE'];
                        $total += $v ['FINALPRICE'];
                        $c1 ++;
                    }
                }
                $GRANDTOTAL = array_sum($grandPrice);
            }
        }
        // echo "<pre>"; print_r($finalCookie); echo "</pre>";

        $final_total = (empty($GRANDTOTAL) || in_array(0, $grandPrice)) ? 0 : $GRANDTOTAL;
        if ($_REQUEST ['func'] == 'payPalReturn') {
            $final_total = "0.0000";
        }

        // return $final_total;
        // return $total;
        return array(
            'total' => $total,
            'count' => $c1
        );
    }

    public function cart($args)
    {
        $this->view->setCaching(false);

        /*
         * if (!UserUtil::isLoggedIn()) {
         * return $this->cart_guest($args);
         * } else {
         * $args_user = ($_POST == true) ? $_POST + array('post' => 1) : array();
         * return $this->cart_user($args_user);
         * }
         */
        return $this->cart_user($args_user);
    }

    /**
     * This function is for storing product in carts
     * stores product in bothe cookies and session
     * returns all the products in the cart
     */
    public function cart_guest($args)
    {
        // exit;
        // echo $this->carttotal();
        // echo "<pre>"; print_r($_SESSION['cart']); echo "</pre>";
        // echo "<pre>"; print_r($_COOKIE['cart']); echo "</pre>"; exit;
        // echo "<pre>"; print_r($_SESSION['cart'][$shop_id]); echo "</pre>";
        // unset($_SESSION['checkoutinfo']);
        // echo "<pre>"; print_r($_SESSION['checkoutinfo']); echo "</pre>";
        // EventUtil::registerPersistentModuleHandler('ZSELEX', 'module_dispatch.custom_classname', array('ZSELEX_Listener_User', 'customClassname'));
        // $modvars = ModUtil::getVar('News');
        // EventUtil::registerPersistentModuleHandler('ZSELEX', 'user.gettheme', array('ZSELEX_Listener_User', 'getTheme'));
        // EventUtil::registerPersistentModuleHandler('ZSELEX', 'module.users.ui.login.succeeded', array('ZSELEX_Listener_UserLogin', 'succeeded'));
        // EventUtil::registerPersistentModuleHandler('ZSELEX', 'module.users.ui.logout.succeeded', array('ZSELEX_Listener_UserLogin', 'logout'));
        // EventUtil::registerPersistentModuleHandler('ZSELEX', 'module.users.ui.login.failed', array('ZSELEX_Listener_UserLogin', 'failed'));
        // EventUtil::registerPersistentEventHandlerClass($this->name, 'ZSELEX_Listener_UsersUiHandler');
        // echo "<pre>"; print_r($_SESSION['cart']); echo "</pre>";
        // echo "<pre>"; print_r($_COOKIE['cart']); echo "</pre>";
        // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
        // if (!SecurityUtil::checkPermission('ZSELEX::cart', '::', ACCESS_COMMENT)) {
        // throw new Zikula_Exception_Forbidden();
        // }
        // EventUtil::registerPersistentModuleHandler('ZSELEX', 'user.account.create', array('ZSELEX_Listener_User', 'create'));
        // EventUtil::registerPersistentModuleHandler('ZSELEX', 'user.account.delete', array('ZSELEX_Listener_User', 'delete'));
        // HookUtil::registerProviderBundles(ZSELEX_Version::getHookProviderBundles());
        // unset($_SESSION['cart']);
        // $_SESSION['mainshop'] = "201";
        // echo $_SESSION['mainshop'];
        PageUtil::setVar('title', $this->__("Shopping Cart"));
        if (isset($_SESSION ['checkoutsession'])) {
            unset($_SESSION ['checkoutsession']);
        }
        $_SESSION ['cart_menu'] = array();
        // echo "<pre>"; print_r($_SESSION['cart_menu']); echo "</pre>";
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_OVERVIEW), LogUtil::getErrorMsgPermission());
        $sessionId              = session_id();
        $product_id             = FormUtil::getPassedValue('product_id', '',
                'REQUEST');
        $productName            = FormUtil::getPassedValue('productName', '',
                'REQUEST');
        $quantity               = FormUtil::getPassedValue('cart_quantity', '',
                'REQUEST');
        $productprice           = FormUtil::getPassedValue('product_price', '',
                'REQUEST');
        $productdesc            = FormUtil::getPassedValue('productDesc', '',
                'REQUEST');
        $productimg             = FormUtil::getPassedValue('productImage', '',
                'REQUEST');
        $service                = FormUtil::getPassedValue('service', '',
                'REQUEST');
        $shop_id                = FormUtil::getPassedValue('shop_id', '',
                'REQUEST');

        $sessionCount = 0;
        $cookieCount  = 0;

        // echo "<pre>"; print_r($_POST); echo "</pre>";
        $exist = 0;

        /*
         * ///////////////////////////////if shop_id is empty//////////////////////////////////
         * if (!empty($_COOKIE['cart'])) {
         * foreach ($_COOKIE['cart'] as $key => $val) {
         *
         * foreach ($_COOKIE['cart'][$key] as $key1 => $val1) {
         * if ($key == 0) {
         * $_COOKIE['cart'][$key] = array();
         * //setcookie("cart[$key]", "", time() - 6048000000);
         * }
         * }
         * }
         * }
         * if (!empty($_SESSION['cart'])) {
         * foreach ($_SESSION['cart'] as $key => $val) {
         * if ($key == 0) {
         * unset($_SESSION['cart'][$key]);
         * }
         * }
         * }
         * ////////////////////////////////////////////////////////////////////
         *
         */

        // ////////////////////////setting the cart products in session from cookies .
        // ////this happends when the user opens the browser next time/////////////////
        if (empty($_SESSION ['cart'])) {
            if (!empty($_COOKIE ['cart'])) {
                // echo "come here"; exit;
                foreach ($_COOKIE ['cart'] as $key => $val) {
                    // $finalCookie[$key] = json_decode($val, true);
                    foreach ($_COOKIE ['cart'] [$key] as $key1 => $val1) {
                        $finalCookie [$key] [$key1] = json_decode($val1, true);
                    }
                }

                foreach ($finalCookie as $key => $val) {
                    foreach ($finalCookie [$key] as $key1 => $val1) {
                        $_SESSION ['cart'] [$key] [$key1] = array(
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
            if (isset($_COOKIE ['cart'] [$_POST [shop_id]])) { // checking the existing products in cart
                foreach ($_COOKIE ['cart'] [$_POST [shop_id]] as $val) {
                    // echo "<pre>"; print_r( json_decode($val, true)); echo "</pre>";
                    if (in_array($product_id, json_decode($val, true))) {
                        $exist ++;
                    } else {
                        
                    }
                }
            }

            if ($exist < 1) { // if product not exist in cart the set it to session and cookie
                /*
                 * $item = ModUtil::apiFunc('ZSELEX', 'user', 'getProductCart', $args = array(
                 * 'shop_id' => $shop_id
                 * ));
                 *
                 */

                // echo "<pre>"; print_r($item); echo "</pre>";exit;
                // echo $item['shop_id'];

                $_SESSION ['cart'] [$_POST ['shop_id']] [] = array(
                    'PRODUCTID' => $product_id,
                    'PRODUCTNAME' => $productName,
                    'SHOPID' => $item ['shop_id'],
                    'QUANTITY' => $quantity,
                    'DESCRIPTION' => $productdesc,
                    'IMAGE' => $productimg,
                    'REALPRICE' => $productprice,
                    'FINALPRICE' => $productprice
                );

                $array = array(
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
                if (!isset($_COOKIE ['cart'] [$_POST [shop_id]])) { // if cookie is not set then set the first key as zero
                    $last_keys = 0;
                } else { // if cookie is available then increment the key by adding the last key of the previous cookie to it.
                    $last_key  = key(array_slice($_COOKIE ['cart'] [$_POST [shop_id]],
                            - 1, 1, TRUE));
                    $last_keys = $last_key + 1;
                }
                // echo "<pre>"; print_r(json_encode($_SESSION['cart'][$last_key])); echo "</pre>";

                $cookieEncode = json_encode($array);
                setcookie("cart[$_POST[shop_id]][$last_keys]", $cookieEncode,
                    time() + 604800, '/');
            } else {
                LogUtil::registerStatus($this->__('This Product Is Already In Your Cart.'));
            }
        } // ///
        // //////////////////////////////////////////////////////////////////////////////////////
        // echo "<pre>"; print_r($_COOKIE['cart']); echo "</pre>";
        // echo "<pre>"; print_r(json_encode($_SESSION['cart'])); echo "</pre>";
        // echo "<pre>"; print_r($_COOKIE['cart']); echo "</pre>";
        // echo "<pre>"; print_r($_SESSION['cart']); echo "</pre>";

        if (isset($_SESSION ['cart'])) {
            $sessionCount = count($_SESSION ['cart']);
        }

        if (isset($_COOKIE ['cart'])) {
            $cookieCount = count($_COOKIE ['cart']);
        }

        // echo "session count: " . $sessionCount;
        // echo "<br>";
        // echo "cookie count: " . $cookieCount;
        // if (count($_COOKIE['cart']) > 0) {
        // foreach ($_COOKIE['cart'] as $key => $val) {
        // $finalCookie[$key] = json_decode($val, true);
        // }
        // }

        if (count($_COOKIE ['cart']) > 0) {
            $c = 0;
            foreach ($_COOKIE ['cart'] as $key => $val) {
                foreach ($_COOKIE ['cart'] [$key] as $key1 => $val1) {
                    $finalCookie [$key] [$key1] = json_decode($val1, true);
                    // $t[$key][$key1] = json_decode($val1, true);
                    $c ++;
                }
            }
        }

        if (count($_SESSION ['cart']) > 0) {
            $s = 0;
            foreach ($_SESSION ['cart'] as $key => $val) {
                foreach ($_SESSION ['cart'] [$key] as $key1 => $val1) {
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
        $cookieCount  = $c;
        $count        = (empty($cookieCount)) ? $sessionCount : $cookieCount;

        $GRANDTOTAL = '';
        if ($sessionCount > $cookieCount) {
            if (count($_SESSION ['cart']) > 0) {
                foreach ($_SESSION ['cart'] as $val) {
                    $grandPrice [] = $val ['FINALPRICE'];
                }
                $GRANDTOTAL = array_sum($grandPrice);
            }
        } else {
            if (count($_COOKIE ['cart']) > 0) {
                foreach ($finalCookie as $val) {
                    $grandPrice [] = $val ['FINALPRICE'];
                }
                $GRANDTOTAL = array_sum($grandPrice);
            }
        }

        // echo "Grand Total :" . $GRANDTOTAL;

        $products = array();
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

        // echo "<pre>"; print_r($products); echo "</pre>";
        $products = @array_filter($products);
        $products = $this->validatecart($products); // VALIDATE THE PRODUCTS IN CART
        // echo "<pre>"; print_r($products); echo "</pre>";
        // $cart_searialize = serialize($products);
        // $cart_unsearialize = unserialize($cart_searialize);
        // echo $cart_searialize . '<br>';
        // echo "<pre>"; print_r($cart_unsearialize); echo "</pre>";

        $this->view->assign('products', $products);
        $this->view->assign('grandtotal', $GRANDTOTAL);
        $this->view->assign('count', $count);
        $this->view->assign('check', $exist);
        $this->view->assign('upcounter', $counter);

        // echo $this->carttotal();

        return $this->view->fetch('user/cart.tpl');
    }

    /**
     * Gets the latest update of the product from database
     *
     * Sets the latest updates to product array
     * Sets the latest updates to session array
     */
    public function validatecart($products)
    {
        // return true;
        $myThis = Zikula_View::getInstance('ZSELEX');
        // $myThis->setCaching(false);
        // ////////////////////Validate the Cart//////////////////////////////
        if (!empty($products)) {
            $_SESSION ['user_cart'] = array();
            // echo "<pre>"; print_r($products); echo "</pre>"; exit;

            foreach ($products as $shopid => $items) {
                $_SESSION ['cartstatus'] [$shopid] = 0;
                // echo $shopid;
                $s                                 = 0;
                $modified                          = 0;
                foreach ($items as $k => $val) {
                    $old_products [$k] = $val;
                    // echo "<pre>"; print_r($old_products[$k]); echo "</pre>";
                    // echo $val['PRODUCTID'].'<br>';
                    $cantbuy           = 0;
                    $prdId             = $val ['PRODUCTID'];
                    // if($prdId < 1)continue;
                    $prodCount         = ModUtil::apiFunc('ZSELEX', 'admin',
                            'getCount',
                            $args              = array(
                            'table' => 'zselex_products',
                            'where' => "product_id=$val[PRODUCTID]"
                    ));
                    if ($prodCount == 0) {
                        // echo $shopid . " " . $val['PRODUCTID'] . '<br>';
                        unset($items [$k]);
                        unset($_SESSION ['cart'] [$shopid] [$k]);
                        unset($_SESSION ['user_cart'] [$shopid] [$k]);
                        // setcookie("cart[$shopid][$k]", "", time() - 604800);
                        setcookie("cart[$shopid][$k]", "", time() - 604800, '/');
                        unset($products [$shopid] [$k]);
                        // echo "<pre>"; print_r($products); echo "</pre>";
                        if (empty($products [$shopid])) {
                            // echo "empty";
                            unset($products [$shopid]);
                        }
                        if (UserUtil::isLoggedIn()) {
                            ZSELEX_Controller_User::update_cart($products);
                        }
                        LogUtil::registerStatus($myThis->__('Your cart has been modified.Some products are deleted'));
                    } else {
                        // echo $shopid . " " . $val['PRODUCTID'] . '<br>';
                        $updated_prod = ModUtil::apiFunc('ZSELEX', 'user',
                                'selectRow',
                                $args_upd     = array(
                                'table' => 'zselex_products a',
                                'where' => array(
                                    "a.product_id=$prdId"
                                )
                        ));
                        // echo "<pre>"; print_r($updated_prod); echo "</pre>";
                        if (($updated_prod ['prd_price'] < 1) || empty($updated_prod ['prd_price'])) {
                            $cantbuy ++;
                            $s ++;
                        }
                        $products [$shopid] [$k] ['PRODUCTID']   = $updated_prod ['product_id'];
                        $products [$shopid] [$k] ['PRODUCTNAME'] = $updated_prod ['product_name'];
                        $products [$shopid] [$k] ['SHOPID']      = $shopid;
                        $products [$shopid] [$k] ['DESCRIPTION'] = $updated_prod ['prd_description'];
                        $products [$shopid] [$k] ['IMAGE']       = $updated_prod ['prd_image'];
                        $products [$shopid] [$k] ['QUANTITY']    = $val ['QUANTITY'];
                        $products [$shopid] [$k] ['REALPRICE']   = $updated_prod ['prd_price'];
                        $products [$shopid] [$k] ['FINALPRICE']  = $updated_prod ['prd_price']
                            * $products [$shopid] [$k] ['QUANTITY'];
                        // $products[$shopid][$k]['CANTBUY'] = $cantbuy;

                        $new_products [$k] = $products [$shopid] [$k];
                        // echo "<pre>"; print_r($new_products[$k]); echo "</pre>";
                        if (UserUtil::isLoggedIn()) {
                            if (@array_diff($old_products [$k],
                                    $new_products [$k])) {
                                LogUtil::registerStatus($myThis->__('Your cart has been modified'));
                                $modified ++;
                            }

                            ZSELEX_Controller_User::update_cart($products);
                        } elseif (!UserUtil::isLoggedIn()) {
                            if (@array_diff($_SESSION ['cart'] [$shopid] [$k],
                                    $new_products [$k])) {
                                LogUtil::registerStatus($myThis->__('Your cart has been modified'));
                                $modified ++;
                            }
                        }

                        // echo "<pre>"; print_r($_SESSION['cart'][$shopid][$k]); echo "</pre>";
                        $_SESSION ['cart'] [$shopid] [$k] ['PRODUCTID']   = $updated_prod ['product_id'];
                        $_SESSION ['cart'] [$shopid] [$k] ['PRODUCTNAME'] = $updated_prod ['product_name'];
                        $_SESSION ['cart'] [$shopid] [$k] ['SHOPID']      = $shopid;
                        $_SESSION ['cart'] [$shopid] [$k] ['DESCRIPTION'] = $updated_prod ['prd_description'];
                        $_SESSION ['cart'] [$shopid] [$k] ['IMAGE']       = $updated_prod ['prd_image'];
                        $_SESSION ['cart'] [$shopid] [$k] ['QUANTITY']    = $val ['QUANTITY'];
                        $_SESSION ['cart'] [$shopid] [$k] ['REALPRICE']   = $updated_prod ['prd_price'];
                        $_SESSION ['cart'] [$shopid] [$k] ['FINALPRICE']  = $products [$shopid] [$k] ['FINALPRICE'];
                        // $_SESSION['cart'][$shopid][$k]['CANTBUY'] = $cantbuy;

                        $_SESSION ['user_cart'] [$shopid] [$k] ['PRODUCTID']   = $updated_prod ['product_id'];
                        $_SESSION ['user_cart'] [$shopid] [$k] ['PRODUCTNAME'] = $updated_prod ['product_name'];
                        $_SESSION ['user_cart'] [$shopid] [$k] ['DESCRIPTION'] = $updated_prod ['prd_description'];
                        $_SESSION ['user_cart'] [$shopid] [$k] ['IMAGE']       = $updated_prod ['prd_image'];
                        $_SESSION ['user_cart'] [$shopid] [$k] ['QUANTITY']    = $val ['QUANTITY'];
                        $_SESSION ['user_cart'] [$shopid] [$k] ['REALPRICE']   = $updated_prod ['prd_price'];
                        $_SESSION ['user_cart'] [$shopid] [$k] ['FINALPRICE']  = $products [$shopid] [$k] ['FINALPRICE'];
                        $_SESSION ['user_cart'] [$shopid] [$k] ['OPTIONS']     = $products [$shopid] [$k] ['OPTIONS'];
                    }
                }
                if ($s > 0) {
                    $_SESSION ['cartstatus'] [$shopid] = - 1;
                }
            }
        }
        // echo "<pre>"; print_r($products); echo "</pre>";
        // /////////////////////////////////////////////////////////////////////////////////////////////////
        return $products;
    }

    /**
     * Deletes a product from the cart
     * Unsets product stored in session
     * Destroys product stored in cookie
     */
    public function deletecart()
    {
        // $this->view->setCaching(false);
        $Id      = FormUtil::getPassedValue('id', '', 'REQUEST');
        $shop_id = FormUtil::getPassedValue('shop_id', '', 'REQUEST');
        // echo "ID :" . $Id . " Shop ID :" . $shop_id; exit;
        // echo "<pre>"; print_r($_SESSION['cart']); echo "</pre>"; exit;
        unset($_SESSION ['cart'] [$shop_id] [$Id]);
        if (empty($_SESSION ['cart'] [$shop_id])) {
            unset($_SESSION ['cart'] [$shop_id]);
        }
        // session_destroy($_SESSION['cart'][$shop_id][$Id]);
        if (count($_SESSION ['cart'] [$shop_id] < 1)) {
            // unset($_SESSION['cart'][$shop_id]);
        }

        setcookie("cart[$shop_id][$Id]", "", time() - 604800, '/');
        if (UserUtil::isLoggedIn()) {
            unset($_SESSION ['user_cart'] [$shop_id] [$Id]);
            if (empty($_SESSION ['user_cart'] [$shop_id])) {
                unset($_SESSION ['user_cart'] [$shop_id]);
            }
            $user_id          = UserUtil::getVar('uid');
            $get_products     = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                    $getargs          = array(
                    'table' => 'zselex_cart',
                    'where' => "user_id=$user_id"
                    )
                    // 'fields' => array('id', 'quantity', 'availed')
            );
            $content          = $get_products ['cart_content'];
            $cart_unserialize = unserialize($content);
            unset($cart_unserialize [$shop_id] [$Id]);
            if (empty($cart_unserialize [$shop_id])) {
                unset($cart_unserialize [$shop_id]);
            }
            $this->update_cart($cart_unserialize);
        } else {
            $this->validatecart($_SESSION ['cart']);
        }

        LogUtil::registerStatus($this->__('Done! Item has been deleted successfully from Cart.'));
        return $this->redirect(ModUtil::url('ZSELEX', 'user', 'cart'));
    }

    /**
     * Delete product from cart
     * 
     * @return type
     */
    public function deleteUserCart()
    {
        //echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
        $cart_id = FormUtil::getPassedValue('id', '', 'REQUEST');
        $shop_id = FormUtil::getPassedValue('shop_id', '', 'REQUEST');
        // echo $shop_id; exit;
        // echo $cart_id; exit;

        $user_id = UserUtil::getVar('uid');
        if (!UserUtil::isLoggedIn()) {
            $user_id = ZSELEX_Util::getTempUserId();
        }
        // if (UserUtil::isLoggedIn()) {
        if ($user_id) {
            // $delete = DBUtil::deleteWhere('zselex_cart', $where = "cart_id=$cart_id");
            $cartObj = $this->entityManager->find('ZSELEX_Entity_Cart', $cart_id);
            $this->entityManager->remove($cartObj);
            $this->entityManager->flush();

            /*
             * $cart = ModUtil::apiFunc('ZSELEX', 'user', 'get', array(
             * 'table' => 'zselex_cart',
             * 'where' => "cart_id=$cart_id",
             * // 'fields' => array('product_to_options_value_id', 'price')
             * ));
             */
            $getArgs = array(
                'entity' => 'ZSELEX_Entity_Cart',
                'fields' => array(
                    'a.cart_id'
                ),
                'where' => array(
                    'a.cart_id' => $cart_id
                )
            );
            $cart    = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->get($getArgs);

            if (empty($cart)) {
                setcookie("last_shop_id", $shop_id, time() + (86400 * 7), '/');
            }
        } else {
            unset($_SESSION ['temp_cart'] [$shop_id] [$cart_id]);
            if (empty($_SESSION ['temp_cart'] [$shop_id]) || count($_SESSION ['temp_cart'] [$shop_id])
                < 1) {
                unset($_SESSION ['temp_cart'] [$shop_id]);
            }
            $cookieEncode = json_encode($_SESSION ['temp_cart']);
            setcookie("temp_cart", $cookieEncode, time() + 604800, '/');

            if (empty($cookieEncode)) {
                setcookie("last_shop_id", $shop_id, time() + (86400 * 7), '/');
            }
        }
        LogUtil::registerStatus($this->__('Done! Item has been deleted successfully from Cart.'));
        return $this->redirect(ModUtil::url('ZSELEX', 'user', 'cart'));
    }

    /**
     * Update user cart
     * 
     * @return type
     */
    public function updateUserCart()
    {
        // echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
        // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
        $shop_id     = FormUtil::getPassedValue('shop_id', '', 'REQUEST');
        $quantities  = FormUtil::getPassedValue('quantity', '', 'REQUEST');
        $prod_answer = FormUtil::getPassedValue('prd_answer', '', 'REQUEST');

        // echo "<pre>"; print_r($_SESSION['temp_cart'][$shop_id]['prd_answer']); echo "</pre>"; exit;
        // echo "<pre>"; print_r($_SESSION['temp_cart']); echo "</pre>"; exit;
        $cartRepo = $this->entityManager->getRepository('ZSELEX_Entity_Cart');

        $user_id = UserUtil::getVar('uid');
        if (!UserUtil::isLoggedIn()) {
            $user_id = ZSELEX_Util::getTempUserId();
        }
        foreach ($prod_answer as $cart_product => $answer) {

            $expld_id       = explode('+', $cart_product);
            // echo "<pre>"; print_r($expld_id); echo "</pre>"; exit;
            $cart_id        = $expld_id [0];
            $product_id     = $expld_id [1];
            $questionStatus = $cartRepo->get(array(
                'entity' => 'ZSELEX_Entity_Product',
                'fields' => array(
                    'a.enable_question',
                    'a.validate_question'
                ),
                // 'joins' => array('JOIN a.product b'),
                'where' => array(
                    'a.product_id' => $product_id
                )
            ));




            // echo "<pre>"; print_r($questionStatus); echo "</pre>"; exit;
            // if (!empty($answer)) {
            // if (UserUtil::isLoggedIn()) {
            if ($user_id) {
                $pitem = array(
                    'prd_answer' => $answer
                );
                /*
                 * if ($questionStatus['enable_question'] && $questionStatus['validate_question']) {
                 * if (!empty($answer)) {
                 * $pitem = array(
                 * 'prd_answer' => $answer,
                 * );
                 * $cartRepo->updateEntity(null, 'ZSELEX_Entity_Cart', $pitem, array('a.cart_id' => $cart_id));
                 * }
                 * } else {
                 * $pitem = array(
                 * 'prd_answer' => $answer,
                 * );
                 * $cartRepo->updateEntity(null, 'ZSELEX_Entity_Cart', $pitem, array('a.cart_id' => $cart_id));
                 * }
                 */
                $cartRepo->updateEntity(null, 'ZSELEX_Entity_Cart', $pitem,
                    array(
                    'a.cart_id' => $cart_id
                ));
            } else {
                /*
                 * if ($questionStatus['enable_question'] && $questionStatus['validate_question']) {
                 * // echo 'helloo'; exit;
                 * if (!empty($answer)) {
                 * $_SESSION['temp_cart'][$shop_id][$cart_id]['prd_answer'] = $answer;
                 * }
                 * } else {
                 * $_SESSION['temp_cart'][$shop_id][$cart_id]['prd_answer'] = $answer;
                 * }
                 */
                $_SESSION ['temp_cart'] [$shop_id] [$cart_id] ['prd_answer'] = $answer;
            }
            // }
        }
        //echo "<pre>"; print_r($quantities); echo "</pre>"; exit;
        foreach ($quantities as $cart_id => $quantity) {
            $quantity = ($quantity < 1) ? 1 : $quantity;
            //  if (UserUtil::isLoggedIn()) {
            if ($user_id) {
                // echo "comes here";  exit;
                // $quantity = ($quantity < 1) ? 1 : $quantity;
                $get_cart       = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                        array(
                        'table' => 'zselex_cart',
                        'where' => "cart_id=$cart_id",
                        'fields' => array(
                            'cart_id',
                            'quantity',
                            'original_price',
                            'cart_content'
                        )
                ));
                // echo "<pre>"; print_r($get_cart); echo "</pre>"; exit;
                $options_to_arr = json_decode($get_cart ['cart_content'], true);
                // $qty_later = $get_cart['quantity'];

                $total = 0;
                if (!empty($options_to_arr)) {
                    foreach ($options_to_arr as $ov) {
                        $value_info = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                                array(
                                'table' => 'zselex_product_to_options_values',
                                'where' => "product_to_options_value_id=$ov[valueID]",
                                'fields' => array(
                                    'product_to_options_value_id',
                                    'price',
                                    'qty'
                                )
                        ));
                        $total += $value_info ['price'];
                    }
                    // echo count($options_to_arr); exit;
                }
                $price       = 0;
                $final_price = ($get_cart ['original_price'] + $total) * $quantity;
                // $price = $get_cart['original_price'] + $total;
                // if (!in_array($cart_id, $errCartId)) {
                $updateCart  = ModUtil::apiFunc('ZSELEX', 'user',
                        'updateObject',
                        $args        = array(
                        'table' => 'zselex_cart',
                        'where' => "cart_id=$cart_id",
                        'idName' => 'cart_id',
                        'fields' => array(
                            'quantity' => $quantity,
                            'final_price' => $final_price
                        )
                        // 'outofstock' => 0
                ));
                // }
            } else {
                $_SESSION ['temp_cart'] [$shop_id] [$cart_id] ['quantity'] = $quantity;
                $options_to_arr                                            = json_decode($_SESSION ['temp_cart'] [$shop_id] [$cart_id] ['cart_content'],
                    true);

                $total = 0;
                if (!empty($options_to_arr)) {
                    foreach ($options_to_arr as $ov) {
                        $value_info = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                                array(
                                'table' => 'zselex_product_to_options_values',
                                'where' => "product_to_options_value_id=$ov[valueID]",
                                'fields' => array(
                                    'product_to_options_value_id',
                                    'price',
                                    'qty'
                                )
                        ));
                        $total += $value_info ['price'];
                    }
                    // echo count($options_to_arr); exit;
                }

                $final_price                                                  = ($_SESSION ['temp_cart'] [$shop_id] [$cart_id] ['prd_price']
                    + $total) * $_SESSION ['temp_cart'] [$shop_id] [$cart_id] ['quantity'];
                $_SESSION ['temp_cart'] [$shop_id] [$cart_id] ['final_price'] = $final_price;
                $cookieEncode                                                 = json_encode($_SESSION ['temp_cart']);
                setcookie("temp_cart", $cookieEncode, time() + 604800, '/');
            }
        }
        LogUtil::registerStatus($this->__('Done! Cart has been updated successfully.'));

        return $this->redirect(ModUtil::url('ZSELEX', 'user', 'cart'));
    }

    /**
     * Updates product detail of products in the cart
     * updates the session and cookie
     */
    public function updatecart()
    {
        $this->view->setCaching(false);
        // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
        $shop_id = $_REQUEST ['shop_id'];
        // echo $shop_id; exit;
        $counter = 0;
        foreach ($_POST ['quantity'] as $productKey => $quantity) {
            // echo $productKey . " - " . $quantity . '<br>';
            $quantity                                                  = ($quantity
                < 1) ? 1 : $quantity;
            $_SESSION ['cart'] [$shop_id] [$productKey] ['QUANTITY']   = $quantity;
            $_SESSION ['cart'] [$shop_id] [$productKey] ['FINALPRICE'] = $quantity
                * $_SESSION ['cart'] [$shop_id] [$productKey] ['REALPRICE'];

            // setcookie("cart[$productKey]", $cookieEncode1, time() + 604800);
            // echo $_COOKIE['cart'][$productKey] . '<br>';
            if (UserUtil::isLoggedIn()) {
                $_SESSION ['user_cart'] [$shop_id] [$productKey] ['QUANTITY'] = $quantity;
                $user_id                                                      = UserUtil::getVar('uid');
                $get_products                                                 = ModUtil::apiFunc('ZSELEX',
                        'user', 'get',
                        $getargs                                                      = array(
                        'table' => 'zselex_cart',
                        'where' => "user_id=$user_id"
                        )
                        // 'fields' => array('id', 'quantity', 'availed')
                );
                $content                                                      = $get_products ['cart_content'];
                $cart_unserialize                                             = unserialize($content);
                $cart_unserialize [$shop_id] [$productKey] ['QUANTITY']       = $quantity;
                $cart_unserialize [$shop_id] [$productKey] ['FINALPRICE']     = $quantity
                    * $_SESSION ['cart'] [$shop_id] [$productKey] ['REALPRICE'];
                $this->update_cart($cart_unserialize);
            }
        }
        // echo "<pre>"; print_r($_SESSION['cart']); echo "</pre>"; exit;
        // setcookie("cart", "" , time() - 604800);

        foreach ($_SESSION ['cart'] [$shop_id] as $key => $val) {
            $array = array(
                'PRODUCTID' => $val ['PRODUCTID'],
                'PRODUCTNAME' => $val ['PRODUCTNAME'],
                'SHOPID' => $shop_id,
                'QUANTITY' => $val ['QUANTITY'],
                'DESCRIPTION' => $val ['DESCRIPTION'],
                'IMAGE' => $val ['IMAGE'],
                'REALPRICE' => $val ['REALPRICE'],
                'FINALPRICE' => $val ['FINALPRICE']
            );

            $cookieEncode = json_encode($array);
            setcookie("cart[$shop_id][$key]", $cookieEncode, time() + 604800,
                '/');
        }

        LogUtil::registerStatus($this->__('Done! Cart has been updated successfully.'));

        // echo "<pre>"; print_r(json_encode($_SESSION['cart'][$last_key])); echo "</pre>";
        // echo "<pre>"; print_r($array); echo "</pre>"; exit;

        return $this->redirect(ModUtil::url('ZSELEX', 'user', 'cart'));
    }

    public function sendOrder($args)
    {
        // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
        // $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_OVERVIEW), LogUtil::getErrorMsgPermission());
        $shop_id    = FormUtil::getPassedValue('shop_id', '', 'REQUEST');
        $ownerName  = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args       = array(
                'shop_id' => $shop_id
        ));
        // echo $ownerName; exit;
        $user_id    = UserUtil::getVar('uid');
        // $userinfo = $this->getUserInfo($user_id);
        $userinfo   = $_SESSION ['checkoutinfo'];
        $cartstatus = $userinfo ['cartstatus'];
        // echo "<pre>"; print_r($userinfo);echo "</pre>"; exit;
        // echo "<pre>"; print_r($_SESSION['checkoutinfo']); echo "</pre>"; exit;
        $obj        = array(
            'user_id' => $user_id,
            'first_name' => $userinfo ['fname'],
            'last_name' => $userinfo ['lname'],
            'city' => $userinfo ['city'],
            'address' => $userinfo ['address'],
            'phone' => $userinfo ['mobile'],
            'totalprice' => ($cartstatus == - 1) ? '' : $_SESSION ['checkoutinfo'] [totalprice],
            'status' => ($cartstatus == - 1) ? 'askinshop' : 'Placed'
        );
        if ($_POST) {
            $chng_shipping_addr = FormUtil::getPassedValue('chng_shippingaddr',
                    '', 'POST');
            if ($chng_shipping_addr) {
                $firstname = FormUtil::getPassedValue('fname', '', 'POST');
                $lastname  = FormUtil::getPassedValue('lname', '', 'POST');
                $zip       = FormUtil::getPassedValue('zip', '', 'POST');
                $city      = FormUtil::getPassedValue('city', '', 'POST');
                $address   = nl2br(FormUtil::getPassedValue('address', '',
                        'POST'));
                $phone     = FormUtil::getPassedValue('phone', '', 'POST');

                $obj = array(
                    'user_id' => $user_id,
                    'first_name' => $firstname,
                    'last_name' => $lastname,
                    'city' => $city,
                    'address' => $address,
                    'phone' => $phone,
                    'totalprice' => ($cartstatus == - 1) ? '' : $_SESSION ['checkoutinfo'] [totalprice],
                    'status' => ($cartstatus == - 1) ? 'askinshop' : 'Placed'
                );
            }
        }
        // echo "</pre>"; print_r($obj); echo "</pre>"; exit;

        $result       = DBUtil::insertObject($obj, 'zselex_order');
        $lastInsertId = DBUtil::getInsertID('zselex_order', 'id');

        if ($result) {
            $order_id                             = "ZS".$lastInsertId;
            $_SESSION ['checkoutinfo'] [order_id] = $order_id;
            $_SESSION ['order_id']                = $order_id;
            $args                                 = array(
                'table' => 'zselex_order',
                'items' => array(
                    'order_id' => $order_id
                ),
                'where' => array(
                    'id' => $lastInsertId
                )
            );
            $updateOrderId                        = ModUtil::apiFunc('ZSELEX',
                    'admin', 'updateElementWhere', $args);

            foreach ($_SESSION ['cart'] [$shop_id] as $key => $val) {
                $obj2 [] = array(
                    'product_id' => $val ['PRODUCTID'],
                    'shop_id' => $shop_id,
                    'order_id' => $order_id,
                    'quantity' => $val ['QUANTITY'],
                    'price' => $val ['FINALPRICE']
                );
            }
            // $result2 = DBUtil::insertObject($obj2, 'zselex_orderitems');
            $result2 = DBUtil::insertObjectArray($obj2, 'zselex_orderitems',
                    'item_id');
        }

        if ($result2) {
            $count = count($_SESSION ['cart'] [$shop_id]);
            unset($_SESSION ['cart'] [$shop_id]);
            for ($i = 0; $i <= $count; $i ++) {
                setcookie("cart[$shop_id][$i]", "", time() - 604800, '/');
            }
            LogUtil::registerStatus($this->__('Order Sucessfully Sent!'));
        }
        return $this->redirect(ModUtil::url('ZSELEX', 'user',
                    'orderConfirmation'));
    }

    public function orderConfirmation($args)
    {
        //echo "<pre>"; print_r($_SESSION['checkoutinfo']); echo "</pre>"; exit;
        PageUtil::setVar('title', $this->__("Order Confirmation"));
        /*  $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
          '::', ACCESS_COMMENT), LogUtil::getErrorMsgPermission()); */
        $repo       = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        $order_id   = $_SESSION ['checkoutinfo'] ['order_id'];
        $shop_id    = $_SESSION ['checkoutinfo'] ['shop_id'];
        $theme      = $_REQUEST ['theme'];
        $user_email = $_SESSION ['checkoutinfo'] ['email'];

        if (empty($order_id)) {
            // LogUtil::registerError($this->__('Sorry! Please try later!'));
            return $this->redirect(ModUtil::url('ZSELEX', 'user', 'cart'));
        }



        $orderInfo = $repo->get(array(
            'entity' => 'ZSELEX_Entity_Order',
            'fields' => array(
                'a.id',
                'a.status',
                'a.user_id',
                'a.payment_type',
                'a.vat',
                'a.shipping',
                'b.shop_id',
                'b.shop_name',
                'a.self_pickup',
                'a.totalprice'
            ),
            'joins' => array(
                'JOIN a.shop b'
            ),
            'where' => array(
                'a.order_id' => $order_id
            )
        ));
        // echo "<pre>"; print_r($orderInfo); echo "</pre>";
        $this->view->assign('orderInfo', $orderInfo);
        System::queryStringSetVar('shop_id', $shop_id);


        //  $user_id = UserUtil::getVar('uid');
        $user_id = UserUtil::getVar('uid');
        if (!UserUtil::isLoggedIn()) {
            $user_id = ZSELEX_Util::getTempUserId();
        }


        $this->update_cart($shop_id, $order_id);

        unset($_SESSION ['cart_menu']);
        $_SESSION ['cart_menu'] = array();
        $this->view->assign('order_id', $order_id);


        // echo "<pre>"; print_r($pay_type); echo "</pre>";exit;
        $payment_type = $orderInfo ['payment_type'];

        if ($payment_type == 'directpay') {
            // $checkout_info = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getSingleResult(array('field' => 'checkout_info', 'where' => "a.shop_id=$shop_id"));
            $directpay = $this->entityManager->getRepository('ZPayment_Entity_DirectpaySetting')->getDirectpay(array(
                'shop_id' => $shop_id
            ));
        }

        $this->view->assign('shop_id', $shop_id);

        $ownername = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));
        $this->view->assign('ownername', $ownername);


        $orderDetails = ModUtil::apiFunc('ZSELEX', 'user', 'getOrderDetails',
                $args         = array(
                'order_id' => $order_id
        ));

        $items = $orderDetails;
        $total = 0;
        foreach ($items as $val) {
            $grandPrice [] = $val ['total'];
            $total += $val ['total'];
        }
        // $GRANDTOTAL = array_sum($grandPrice);
        $GRANDTOTAL = $total;
        $vat        = $orderInfo ['vat'];

        // $shipping        = 0;

        $shipping = $orderInfo ['shipping'];
        if ($orderInfo ['self_pickup']) {
            $shipping = 0;
        }

        //   $grand_total_all = $GRANDTOTAL + $shipping;
        $grand_total_all = $orderInfo['totalprice'];
        // echo "<pre>"; print_r($items); echo "</pre>";exit;
        $this->view->assign('owner_name', $owner_name);
        $this->view->assign('items', $items);
        $this->view->assign('orderDetails', $items);
        $this->view->assign('GRANDTOTAL', $GRANDTOTAL);
        $this->view->assign('vat', $vat);

        $this->view->assign('grand_total_all', $grand_total_all);
        $this->view->assign('payment_type', $payment_type);
        $this->view->assign('checkout_info', $checkout_info);
        $this->view->assign('directpay', $directpay);
        $this->view->assign('shipping', $shipping);

        // echo "<pre>"; print_r($items); echo "</pre>";
        // return $this->view->fetch('user/thankyou.tpl');
        if ($theme == 'Printer') {
            return $this->view->fetch('printer/order_confirmation.tpl');
        }

        if ($_SERVER ['SERVER_NAME'] != 'localhost') {
            $this->sendMailToUser($order_id); // send mail
        }


        $cart_info = ModUtil::apiFunc('ZSELEX', 'cart', 'carttotal', array());
        $this->view->assign('cartCount', $cart_info['count']);

        return $this->view->fetch('user/order_confirmation.tpl');
    }

    public function updatecartitem($args)
    {
        // echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
        $Id      = FormUtil::getPassedValue('id', '', 'REQUEST');
        $shop_id = FormUtil::getPassedValue('shop_id', '', 'REQUEST');

        setcookie("cart[$shop_id][$Id]", "", time() - 604800);
        LogUtil::registerStatus($this->__('Done! Item has been deleted successfully from Cart.'));
        return $this->redirect(ModUtil::url('ZSELEX', 'user', 'cart'));
    }

    public function payments()
    {
        return $this->view->fetch('user/view.tpl');
    }

    /**
     * Check out function from cart
     *
     * @return html
     */
    public function checkout()
    {
        header("Cache-Control: max-age=300, must-revalidate");

        $user_id = UserUtil::getVar('uid');
        if (!UserUtil::isLoggedIn()) {
            $user_id = ZSELEX_Util::getTempUserId();
        }

        //echo $user_id; exit;
        // $shop_id = $_REQUEST['shop_id'];
        // $cart_shop_id = $_REQUEST['cart_shop_id'];
        $_SESSION ['netaxept'] = array();

        if ($_POST == true) {
            //echo "comes here"; exit;
            // echo $_REQUEST ['cart_shop_id']; exit;
            // $_SESSION ['cart_shop_id'] = $_REQUEST ['cart_shop_id'];
            SessionUtil::setVar("cart_shop_id", $_REQUEST ['cart_shop_id']);
        }
        // $cart_shop_id = $_SESSION ['cart_shop_id'];
        $cart_shop_id = SessionUtil::getVar("cart_shop_id");
        // echo $cart_shop_id; exit;
        if (empty($cart_shop_id)) {
            return $this->redirect(ModUtil::url('ZSELEX', 'user', 'cart'));
        }

        $cartError = ModUtil::apiFunc('ZSELEX', 'cart', 'validatecart',
                array('cart_shop_id' => $cart_shop_id));
        if ($cartError) {
            return $this->redirect(ModUtil::url('ZSELEX', 'user', 'cart'));
        }
        // echo "comes here";  exit;
        $shop_id                   = $cart_shop_id;
        System::queryStringSetVar('shop_id', $shop_id);
        $shop_name                 = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getSingleResult(array(
            'field' => 'shop_name',
            'where' => "a.shop_id=$shop_id"
        ));
        PageUtil::setVar('title', $this->__("Check Out - ".$shop_name));
        // $shop_name = $_REQUEST['shop_name'];
        $_SESSION ['cart_menu'] [] = 'checkout';
        // echo "<pre>"; print_r($_SESSION['cart_menu']); echo "</pre>";
        // echo "<pre>"; print_r($_COOKIE['cart']); echo "</pre>";
        // echo "<pre>"; print_r($_REQUEST); echo "</pre>";
        // echo "<pre>"; print_r($_SESSION['cart'][$shop_id]); echo "</pre>";


        $menu_items = array(
            'delivery',
            'paymentoptions'
        );
        ModUtil::apiFunc('ZSELEX', 'cart', 'unsetCartMenu', $menu_items);
        if (!UserUtil::isLoggedIn()) {
            // echo "hellooo222";
            // SessionUtil::setVar('checkoutsession', '1');
            // session_register('checkoutsession');
            $_SESSION ['checkoutsession'] = '1';
            // EventUtil::registerPersistentModuleHandler('ZSELEX', 'module.users.ui.login.succeeded', array('ZSELEX_Listener_UserLogin', 'succeeded'));
            // return LogUtil::registerPermissionError();
        } else {
            // echo "Check Out Page";
            // $user_id  = UserUtil::getVar('uid');
            $userinfo = DBUtil::selectObjectByID('users', $user_id, 'uid');
            // echo "<pre>"; print_r($userinfo); echo "</pre>";
        }
        if (empty($cart_shop_id)) {
            return $this->redirect(ModUtil::url('ZSELEX', 'user', 'cart'));
        }
        $checkoutInfo = '';
        if (!empty($_SESSION ['checkoutinfo'])) {
            $checkoutInfo = $_SESSION ['checkoutinfo'];
        }

        setcookie("last_shop_id", $shop_id, time() + (86400 * 7), '/');



        $seller = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwnerInfo',
                $args   = array(
                'shop_id' => $shop_id
        ));

        // echo "<pre>"; print_r($seller); echo "</pre>";

        $sellerName = $seller ['uname'];
        $info       = $this->getUserInfo($user_id);
        // echo "<pre>"; print_r($info); echo "</pre>";
        // echo "<pre>"; print_r($_SESSION['checkoutinfo']['shipping_info']); echo "</pre>";
        // $_SESSION['checkoutinfo']['shipping_info'] = $info;

        $user2                 = $_SESSION ['checkoutinfo'] ['shipping_info'];
        $info                  = $info + array(
            'email' => $userinfo ['email']
        );
        $_SESSION ['userInfo'] = $info;
        // echo "<pre>"; print_r($_SESSION['userInfo']); echo "</pre>";

        $userArr = array(
            'first_name' => !empty($user2 ['fname']) ? $user2 ['fname'] : $info ['first_name'],
            'last_name' => !empty($user2 ['lname']) ? $user2 ['lname'] : $info ['last_name'],
            'address' => !empty($user2 ['address']) ? $user2 ['address'] : $info ['address'],
            'zip' => !empty($user2 ['zip']) ? $user2 ['zip'] : $info ['zip'],
            'city' => !empty($user2 ['city']) ? $user2 ['city'] : $info ['city'],
            'mobile' => !empty($user2 ['phone']) ? $user2 ['phone'] : $info ['mobile'],
            'email' => !empty($user2 ['email']) ? $user2 ['email'] : $info ['email'],
            'country' => !empty($user2 ['country']) ? $user2 ['country'] : $info ['country'],
            'state' => !empty($user2 ['state']) ? $user2 ['state'] : $info ['state']
        );

        $this->view->assign('seller', $sellerName);
        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('userinfo', $userinfo);
        // $this->view->assign('info', $info);
        $this->view->assign('info', $userArr);
        $this->view->assign($checkoutInfo);
        $this->view->assign('shop_id', $shop_id);

        return $this->view->fetch('user/checkout.tpl');
    }

    public function getUserInfo($user_id)
    {
        $info = array();

        $shipping_address = ModUtil::apiFunc('ZSELEX', 'user', 'getUserInfo',
                $args             = array(
                'where' => "object_id='".$user_id."' AND object_type='users' AND (attribute_name='ship_address')"
        ));

        /*
         * $first_name = ModUtil::apiFunc('ZSELEX', 'user', 'get', $args = array(
         * 'table' => 'objectdata_attributes',
         * 'where' => "object_id=$user_id AND object_type='users' AND (attribute_name='first_name')",
         * 'fields' => array("value")
         * ));
         */

        $first_name = ModUtil::apiFunc('ZSELEX', 'user', 'getUserInfo',
                $args       = array(
                'where' => "object_id='".$user_id."' AND object_type='users' AND attribute_name='ship_first_name'"
        ));
        // echo "<pre>"; print_r($first_name); echo "</pre>";
        $last_name  = ModUtil::apiFunc('ZSELEX', 'user', 'getUserInfo',
                $args       = array(
                'where' => "object_id='".$user_id."' AND object_type='users'AND attribute_name='ship_last_name'"
        ));
        $mobile     = ModUtil::apiFunc('ZSELEX', 'user', 'getUserInfo',
                $args       = array(
                'where' => "object_id='".$user_id."' AND object_type='users' AND attribute_name='ship_phone'"
        ));

        // echo "<pre>"; print_r($mobile); echo "</pre>";

        $zip  = ModUtil::apiFunc('ZSELEX', 'user', 'getUserInfo',
                $args = array(
                'where' => "object_id='".$user_id."' AND object_type='users' AND (attribute_name='ship_zipcode')"
        ));

        $city = ModUtil::apiFunc('ZSELEX', 'user', 'getUserInfo',
                $args = array(
                'where' => "object_id='".$user_id."' AND object_type='users' AND attribute_name='ship_city'"
        ));
        // echo "<pre>"; print_r($city); echo "</pre>";

        /*
         * $country = ModUtil::apiFunc('ZSELEX', 'user', 'getUserInfo', $args =
         * array(
         * 'where' => "object_id=$user_id AND object_type='users' AND (attribute_name='user_country')",
         * ));
         */
        $info = array(
            'first_name' => $first_name ['value'],
            'last_name' => $last_name ['value'],
            'mobile' => $mobile ['value'],
            'zip' => $zip ['value'],
            'city' => $city ['value'],
            // 'state' => $state['value'],
            'country' => $city ['value'],
            'address' => $shipping_address ['value']
        );
        return $info;
    }

    public function payButtonExist($args)
    {
        $shop_id        = $args ['shop_id'];
        $payButtonCount = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount',
                $args           = array(
                'table' => 'zselex_serviceshop',
                'where' => "shop_id=$shop_id AND type='paybutton'"
        ));
        return $payButtonCount;
    }

    /**
     * Order Page
     *
     * @return html
     */
    public function order()
    {
        //echo "order page";
        PageUtil::setVar('title', $this->__("Order Summary"));
        if (!UserUtil::isLoggedIn()) {
            $_SESSION ['checkoutsession'] = '1';
            //return LogUtil::registerPermissionError();
        }

        $user_id = UserUtil::getVar('uid');
        if (!UserUtil::isLoggedIn()) {
            $user_id = ZSELEX_Util::getTempUserId();
        }

        //  $shop_id = $_SESSION ['cart_shop_id'];
        $shop_id = SessionUtil::getVar('cart_shop_id');
        if (empty($shop_id)) {
            // LogUtil::registerError($this->__('Sorry! Please try later!'));
            return $this->redirect(ModUtil::url('ZSELEX', 'user', 'cart'));
        }

        $cartError = ModUtil::apiFunc('ZSELEX', 'cart', 'validatecart',
                array('cart_shop_id' => $shop_id));
        if ($cartError) {
            //  return $this->redirect(ModUtil::url('ZSELEX', 'user', 'cart'));
        }
        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Shop');

        $getArgs   = array(
            'entity' => 'ZSELEX_Entity_Shop',
            'fields' => array(
                'a.shop_name'
            ),
            'where' => array(
                'a.shop_id' => $shop_id
            )
        );
        $getShop   = $repo->get($getArgs);
        $shop_name = $getShop ['shop_name'];
        $this->view->assign('shop_name', $shop_name);
        System::queryStringSetVar('shop_id', $shop_id);
        // echo $shop_id; exit;
        // echo "<pre>"; print_r($_SESSION['cart'][$shop_id]); echo "</pre>";
        $this->view->assign('shop_id', $shop_id);
        // echo "<pre>"; print_r($_COOKIE['cart'][$shop_id]); echo "</pre>";
        $args      = array(
            'shop_id' => $shop_id
        );
        // echo $this->payButtonExist($args);
        if ($this->payButtonExist($args) > 0) {
            // echo "comes here";
            $payPalEmail = ModUtil::apiFunc('ZSELEX', 'user', 'getPayPalEmail',
                    $args        = array(
                    'shop_id' => $shop_id
            ));
        } else {
            // echo "comes here";
            $modvars     = $this->getVars();
            $payPalEmail = $modvars ['paypalzselexemail'];
        }
        $modvars = $this->getVars();
        // echo "<pre>"; print_r($modvars); echo "</pre>";
        // echo "paypal email : " . $payPalEmail;
        $this->view->assign('paypalemail', $payPalEmail);

        $order_id  = $_SESSION ['checkoutinfo'] ['order_id'];
        $ownername = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));
        // echo "OrderId :" . $order_id;

        $paytype = $_SESSION ['checkoutinfo'] ['paytype'];
        // echo $paytype; exit;

        $pp_return_url = pnGetBaseURL().ModUtil::url('ZSELEX', 'user',
                'payPalReturn',
                array(
                'order_id' => $order_id
        ));
        $pp_cancel_url = pnGetBaseURL().ModUtil::url('ZSELEX', 'user',
                'paypalCancel',
                array(
                'order_id' => $order_id,
                'shop_id' => $shop_id
        ));

        // $products = $_SESSION['cart'][$shop_id];
        $products = ModUtil::apiFunc('ZSELEX', 'user', 'getOrderDetails',
                $args     = array(
                'order_id' => $order_id
        ));
        // echo "<pre>"; print_r($products); echo "</pre>";
        $gtotal   = 0;
        foreach ($products as $v) {
            $gtotal += $v ['total'];
        }
        $product_form  = $products;
        $product_count = count($products);
        $this->view->assign('product_count', $product_count);

        $vat         = $_SESSION ['checkoutinfo'] ['VAT'];
        $shippingVal = $_SESSION ['checkoutinfo'] ['shipping'];
        if ($_SESSION ['checkoutinfo'] ['self_pickup']) {
            //  echo "self_pickup";
            $shippingVal = 0;
        }

        $this->view->assign('vat', $vat);
        $this->view->assign('shippingVal', $shippingVal);

        $extraArr = array();
        /*
         * if ($vat > 0) {
         * $extraArr[] = array('product_name' => 'VAT', 'price' => $vat, 'quantity' => '1');
         * }
         */
        if ($shipping > 0) {
            $extraArr [] = array(
                'product_name' => 'Shipping',
                'price' => $shipping,
                'total' => $shipping,
                'quantity' => '1'
            );
        }

        $product_form = array_merge($product_form, $extraArr);
        $product_form = array_filter($product_form);

        // echo "<pre>"; print_r($product_form); echo "</pre>";
        $grand_total    = DBUtil::selectObjectSum($table          = "zselex_orderitems",
                $column         = "total", $where          = "order_id='".$order_id."'",
                $categoryFilter = null);
        // $grand_total = $gtotal;
        // echo "Grand Total :" . $grand_total; exit;

        $_SESSION ['checkoutinfo'] ['grand_total'] = $grand_total;
        // $_SESSION['checkoutinfo']['grand_total_all'] = $grand_total + $_SESSION['checkoutinfo']['shipping'];
        // echo "Shipping : ". $_SESSION['checkoutinfo']['shipping'];
        // $grand_total_all       = $_SESSION ['checkoutinfo'] ['grand_total_all'];

        $grand_total_all       = $_SESSION ['checkoutinfo'] ['final_price'];
        $thislang              = ZLanguage::getLanguageCode();
        $_SESSION ['netaxept'] = array();

        $shop_info = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->get(array(
            'entity' => 'ZSELEX_Entity_Shop',
            'fields' => array(
                'a.shop_name'
            ),
            'where' => array(
                'a.shop_id' => $shop_id
            )
        ));
        // echo "<pre>"; print_r($shop_info); echo "</pre>"; exit;
        $shop_name = $shop_info ['shop_name'];
        if ($paytype == 'netaxept') {
            if (ModUtil::available('ZPayment')) {
                $netaxept_info = $this->entityManager->getRepository('ZPayment_Entity_Netaxept')->getNetaxept(array(
                    'shop_id' => $shop_id
                ));
                $this->view->assign('netaxept_info', $netaxept_info);
                // Register Transaction with Netaxept here.
                // $netaxept = ZPayment_Controller_User::registerTransaction($_SESSION['checkoutinfo']);

                $orderExist = $this->entityManager->getRepository('ZPayment_Entity_Netaxept')->nets_orderCount(array(
                    'order_id' => $order_id
                ));

                if (!$orderExist) {
                    $netaxept = ModUtil::apiFunc('ZPayment', 'Netaxept',
                            'registerTransaction', $_SESSION ['checkoutinfo']);

                    if ($netaxept ['error'] || !$netaxept) {
                        // echo "<pre>"; print_r($netaxept); echo "</pre>"; exit;
                        LogUtil::registerError($this->__('Sorry! Error occured in Netaxept .Please try later!'));
                        return $this->redirect(ModUtil::url('ZSELEX', 'user',
                                    'cart'));
                    }

                    $_SESSION ['netaxept'] = $netaxept;

                    // echo "<pre>"; print_r($_SESSION['netaxept']); echo "</pre>"; exit;

                    $nets_entity = new ZPayment_Entity_Netaxept ();
                    $nets_entity->setZselex_order_id($_SESSION ['checkoutinfo'] ['order_id']);
                    $nets_entity->setNets_transaction_id($netaxept ['transactionId']);
                    $nets_entity->setStatus('Placed');
                    $this->entityManager->persist($nets_entity);
                    $this->entityManager->flush();
                }
            } else {
                LogUtil::registerError($this->__('Sorry! Payment module is not available currently!'));
                return $this->redirect(ModUtil::url('ZSELEX', 'user', 'cart'));
            }
        } elseif ($paytype == 'paypal') {
            $paypal_info = $this->entityManager->getRepository('ZPayment_Entity_Paypal')->getPaypal(array(
                'shop_id' => $shop_id
            ));

            $orderExist = $this->entityManager->getRepository('ZPayment_Entity_Paypal')->paypal_orderCount(array(
                'order_id' => $order_id
            ));
            if (!$orderExist) {
                $paypal_entity = new ZPayment_Entity_Paypal ();
                $paypal_entity->setOrder_id($_SESSION ['checkoutinfo'] ['order_id']);
                $paypal_entity->setStatus('Placed');
                $this->entityManager->persist($paypal_entity);
                $this->entityManager->flush();
            }
            $this->view->assign('paypal_info', $paypal_info);
            $this->view->assign('order_id', $order_id);
        } elseif ($paytype == 'quickpay') {
            // $user_id       = UserUtil::getVar('uid');
            $quickpay_info = $this->entityManager->getRepository('ZPayment_Entity_QuickPay')->getQuickPay(array(
                'shop_id' => $shop_id
            ));
            $orderExist    = $this->entityManager->getRepository('ZPayment_Entity_QuickPay')->quickpay_orderCount(array(
                'order_id' => $order_id
            ));

            $qpTransactionId = uniqid(md5(rand(1, 666)), true);

            if (!$orderExist) {
                $quickpay_entity = new ZPayment_Entity_QuickPay ();
                $quickpay_entity->setOrder_id($_SESSION ['checkoutinfo'] ['order_id']);
                $quickpay_entity->setStatus('Placed');
                $quickpay_entity->setTransaction_id($qpTransactionId);
                $this->entityManager->persist($quickpay_entity);
                $this->entityManager->flush();
            }

            /* $qp_ok_url       = pnGetBaseURL().ModUtil::url('ZSELEX', 'user',
              'QuickPayOk',
              array(
              'order_id' => $order_id, 'gateway' => 'quickpay'
              )); */

            $qp_cancel_url = pnGetBaseURL().ModUtil::url('ZSELEX', 'user',
                    'QuickPayCancel',
                    array(
                    'order_id' => $order_id
            ));
            /* $qp_callback_url = pnGetBaseURL().ModUtil::url('ZSELEX', 'user',
              'QuickPayCallback',
              array(
              'order_id' => $order_id
              )); */

            if ($quickpay_info['return_url']) {
                $qpReturnUrl = $quickpay_info['return_url'];
            } elseif (empty($quickpay_info['return_url'])) {
                $qpReturnUrl = pnGetBaseURL().ModUtil::url('ZSELEX', 'user',
                        'shop', array('shop_id' => $shop_id));
            }


            $query = parse_url($qpReturnUrl, PHP_URL_QUERY);

            if ($query) {
                $prepend = '&';
            } else {
                $prepend = '?';
            }

            $urlParams = $prepend."gateway=quickpay&order_id=".$order_id."&txn_id=".$qpTransactionId;

            $qpReturnUrl .= $urlParams;

            //echo $qpReturnUrl; exit;
            $qp_ok_url       = $qpReturnUrl.'&url_type=continue';
            $qp_callback_url = $qpReturnUrl.'&url_type=callback';

            $splitpayment = '';
            $cardtypelock = '';
            $protocol     = '7';
            $msgtype      = 'authorize';
            //  $merchant     = $quickpay_info ['quickpay_id'];
            $merchant     = $quickpay_info ['merchant_id'];
            $agreementId  = $quickpay_info ['agreement_id'];
            $language     = $thislang;
            $ordernumber  = $_SESSION ['checkoutinfo'] ['order_id'];
            $amount       = (int) ($grand_total_all * 100);
            $currency     = 'DKK';
            $continueurl  = $qp_ok_url;
            $cancelurl    = $qp_cancel_url;
            $callbackurl  = $qp_callback_url; // see http://quickpay.dk/clients/callback-quickpay.php.txt
            $autocapture  = 0;
            //$md5secret    = $quickpay_info ['md5_secret'];
            $apiKey       = $quickpay_info ['api_key'];
            $test_mode    = '';
            if ($quickpay_info ['test_mode']) {
                $test_mode = 1;
            }
            if ($quickpay_info ['pay_type'] == 'individual') {
                $splitpayment = '1';
                $cardtypelock = '';
            }

            // $epayForm['md5_hash'] = $epay_info['md5_hash'];
            $order_description = $this->__("Your order")." #$ordernumber ".$this->__("from")." ".$shop_name;

            $md5check = md5($protocol.$msgtype.$merchant.$language.$ordernumber.$amount.$currency.$continueurl.$cancelurl.$callbackurl.$autocapture.$cardtypelock.$order_description.$test_mode.$splitpayment.$md5secret);


            $params   = array(
                "version" => "v10",
                "merchant_id" => $merchant,
                "agreement_id" => $agreementId,
                "order_id" => $ordernumber,
                "amount" => $amount,
                "currency" => $currency,
                "continueurl" => $qp_ok_url,
                "cancelurl" => $cancelurl,
                "callbackurl" => $qp_callback_url,
                "variables" => array(
                    "CUSTOM_shop_id" => $shop_id,
                    "CUSTOM_user_id" => $user_id,
                )
            );
            $checksum = ModUtil::apiFunc('ZPayment', 'user', 'sign',
                    $args     = array(
                    'params' => $params,
                    'api_key' => $apiKey
            ));

            // echo $checksum; exit;
            $this->view->assign('checksum', $checksum);


            $extraArr = array(
                'protocol' => $protocol,
                'msgtype' => $msgtype,
                'language' => $language,
                'ordernumber' => $ordernumber,
                'amount' => $amount,
                'currency' => $currency,
                'continueurl' => $continueurl,
                'cancelurl' => $cancelurl,
                'autocapture' => $autocapture,
                'callbackurl' => $callbackurl,
                'splitpayment' => $splitpayment,
                'cardtypelock' => $cardtypelock,
                'description' => $order_description,
                'md5check' => $md5check
            );

            $quickpay_info = array_merge($quickpay_info, $extraArr);
            $this->view->assign('user_id', $user_id);
            $this->view->assign('quickpay_info', $quickpay_info);
            $this->view->assign('text_type', 'hidden');

            //  echo "<pre>"; print_r($quickpay_info); echo "</pre>"; exit;
        } elseif ($paytype == 'epay') {
            //$user_id    = UserUtil::getVar('uid');
            $epay_info  = $this->entityManager->getRepository('ZPayment_Entity_Epay')->getEpay(array(
                'shop_id' => $shop_id
            ));
            $orderExist = $this->entityManager->getRepository('ZPayment_Entity_Epay')->epay_orderCount(array(
                'order_id' => $order_id
            ));

            if (!$orderExist) {
                $epay_entity = new ZPayment_Entity_Epay ();
                $epay_entity->setOrder_id($_SESSION ['checkoutinfo'] ['order_id']);
                $epay_entity->setStatus('Placed');
                $this->entityManager->persist($epay_entity);
                $this->entityManager->flush();
            }

            $epay_accept_url   = pnGetBaseURL().ModUtil::url('ZSELEX', 'user',
                    'EpayAccept',
                    array(
                    'shop_id' => $shop_id, 'gateway' => 'epay'
            ));
            $epay_cancel_url   = pnGetBaseURL().ModUtil::url('ZSELEX', 'user',
                    'EpayCancel',
                    array(
                    'shop_id' => $shop_id
            ));
            $epay_callback_url = pnGetBaseURL().ModUtil::url('ZSELEX', 'user',
                    'EpayAccept',
                    array(
                    'shop_id' => $shop_id, 'gateway' => 'epay'
            ));

            $epayForm = array();
            if ($epay_info ['test_mode']) {
                $epayForm ['merchant_number'] = $epay_info ['test_merchant_number'];
            } else {
                $epayForm ['merchant_number'] = $epay_info ['merchant_number'];
            }
            // $language = $thislang;
            $epayForm ['ordernumber']     = $_SESSION ['checkoutinfo'] ['order_id'];
            $epayForm ['amount']          = (int) ($grand_total_all * 100);
            $epayForm ['currency']        = 'DKK';
            $epayForm ['accepturl']       = $epay_accept_url;
            $epayForm ['cancelurl']       = $epay_cancel_url;
            $epayForm ['callbackurl']     = $epay_callback_url; // see http://quickpay.dk/clients/callback-quickpay.php.txt
            $epayForm ['currency']        = 'DKK';
            $epayForm ['windowstate']     = 3;
            $epayForm ['instantcallback'] = 1;
            $epayForm ['ownreceipt']      = 1;
            $shop_info                    = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->get(array(
                'entity' => 'ZSELEX_Entity_Shop',
                'fields' => array(
                    'a.shop_name'
                ),
                'where' => array(
                    'a.shop_id' => $shop_id
                )
            ));
            // echo "<pre>"; print_r($shop_info); echo "</pre>"; exit;
            $shop_name                    = $shop_info ['shop_name'];
            // $epayForm['md5_hash'] = $epay_info['md5_hash'];
            $ordernumber                  = $_SESSION ['checkoutinfo'] ['order_id'];
            $epayForm ['ordertext']       = $this->__("Your order")." #$ordernumber ".$this->__("from")." ".$shop_name;
            $epayForm ['description']     = $this->__("Your order")." #$ordernumber ".$this->__("from")." ".$shop_name;
            $test_mode                    = '';
            if ($epay_info ['test_mode']) {
                $test_mode = 1;
            }

            // echo "<pre>"; print_r(array_values($epayForm)); echo "</pre>"; exit;
            // $md5check = md5($protocol . $msgtype . $merchant . $language . $ordernumber . $amount . $currency . $continueurl . $cancelurl . $callbackurl . $autocapture . $test_mode . $cardtypelock . $splitpayment . $md5secret);
            if (strlen($epay_info ['md5_hash']) > 0) {
                $hash                  = md5(implode("", array_values($epayForm)).$epay_info ['md5_hash']);
                $epayForm ['set_hash'] = 1;
                $epayForm ['hash']     = $hash;
            }

            $this->view->assign('user_id', $user_id);
            $this->view->assign('epay_info', $epay_info);
            $this->view->assign('epayForm', $epayForm);
        } elseif ($paytype == 'directpay') {
            return $this->redirect(ModUtil::url('ZSELEX', 'user',
                        'orderConfirmation'));
        } elseif ($paytype == 'printorder') {
            return $this->redirect(ModUtil::url('ZSELEX', 'user',
                        'orderConfirmation',
                        array(
                        'theme' => 'printer'
            )));
        }
        // echo "come back from nets"; exit;
        // echo "<pre>"; print_r($netaxept); echo "</pre>";
        // $nets_transaction_id = $netaxept['result']->TransactionId;
        // echo "Transaction ID : " . $nets_transaction_id; exit;
        $last_key = key(array_slice($products, - 1, 1, TRUE));
        $vat_item = array(
            'product_id' => '',
            'product_name' => 'VAT',
            'price' => $_SESSION ['VAT'],
            'total' => $_SESSION ['VAT'],
            'quantity' => 1,
            'extra' => 1
        );
        // array_push($product_form, $vat_item);
        // echo "<pre>"; print_r($product_form); echo "</pre>";
        // ZSELEX_Controller_User::clearCart();
        // echo "<pre>"; print_r($_SESSION['checkoutinfo']); echo "</pre>";

        $this->view->assign('thislang', $thislang);
        $this->view->assign($_SESSION ['checkoutinfo']);
        $this->view->assign('userinfo',
            $_SESSION ['checkoutinfo'] ['shipping_info']);
        $this->view->assign('paytype', $paytype);
        $this->view->assign('order_id', $order_id);
        // $this->view->assign('netaxept', $netaxept);
        $this->view->assign('netaxept', $_SESSION ['netaxept']);
        $this->view->assign('ownername', $ownername);
        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('products', $products);
        $this->view->assign('product_form', $product_form);

        $this->view->assign('user_id', $user_id);
        $this->view->assign('grandtotal', $totalprice);
        $this->view->assign('grand_total', $grand_total);
        $this->view->assign('grand_total_all', $grand_total_all);
        $this->view->assign('pp_return_url', $pp_return_url);
        $this->view->assign('pp_cancel_url', $pp_cancel_url);
        //
        return $this->view->fetch('user/order.tpl');
    }

    /**
     * Place an order
     * Generates uniuque OrderID
     * 
     * @return Redirect to Order confirmation page
     */
    public function placeOrder()
    {
        // echo "<pre>"; print_r($_SESSION['checkoutinfo']); echo "</pre>"; exit;
        /* $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
          '::', ACCESS_COMMENT), LogUtil::getErrorMsgPermission()); */
        //$user_id      = UserUtil::getVar('uid');
        $user_id = UserUtil::getVar('uid');
        if (!UserUtil::isLoggedIn()) {
            $user_id = ZSELEX_Util::getTempUserId();
        }
        $shop_id      = $_REQUEST ['shop_id'];
        // echo $shop_id; exit;
        // $cart_shop_id = $_SESSION ['cart_shop_id'];
        $cart_shop_id = SessionUtil::getVar('cart_shop_id');
        // echo $cart_shop_id; exit;
        // $shop_id      = $_SESSION ['cart_shop_id'];
        $shop_id      = SessionUtil::getVar('cart_shop_id');
        if (empty($cart_shop_id)) {
            return $this->redirect(ModUtil::url('ZSELEX', 'user', 'cart'));
        }


        $cartError = ModUtil::apiFunc('ZSELEX', 'cart', 'validatecart',
                array('cart_shop_id' => $cart_shop_id));
        if ($cartError) {
            return $this->redirect(ModUtil::url('ZSELEX', 'user', 'cart'));
        }


        // $GRANDTOTAL = $_SESSION ['checkoutinfo'] ['grand_total_all'];
        $GRANDTOTAL = $_SESSION ['checkoutinfo'] ['final_price'];
        // echo $GRANDTOTAL; exit;

        $paymentType = $_REQUEST ['paytype'];
        // echo "pay type :" . $paymentType; exit;

        if (empty($paymentType)) {
            LogUtil::registerError($this->__('Please choose a payment option'));
            return $this->redirect(ModUtil::url('ZSELEX', 'user',
                        'paymentoptions'));
        }
        $payButtonExist = $_REQUEST ['payButtonExist'];
        $status         = 'Placed';
        if (!$payButtonExist) {
            $status = 'directbuy';
        }
        $_SESSION ['payment_method']           = $paymentType;
        // echo $paymentType; exit;
        $_SESSION ['checkoutinfo'] ['shop_id'] = $shop_id;
        $_SESSION ['checkoutinfo'] ['paytype'] = $paymentType;

        $user_email = $_SESSION ['checkoutinfo'] ['email'];
        // echo "<pre>"; print_r($_SESSION['checkoutinfo']); echo "</pre>"; exit;
        // echo "shopId : " . $shop_id; exit;

        $shipping_address = $_SESSION ['checkoutinfo'] ['shipping_info'];

        $obj = array(
            'shop_id' => $shop_id,
            'user_id' => $user_id,
            'first_name' => $shipping_address ['fname'],
            'last_name' => $shipping_address ['lname'],
            'email' => $user_email,
            'zip' => $shipping_address ['zip'],
            'city' => $shipping_address ['city'],
            'street' => $shipping_address ['street'],
            'address' => $shipping_address ['address'],
            'phone' => $shipping_address ['phone'],
            'totalprice' => $GRANDTOTAL,
            'vat' => $_SESSION ['checkoutinfo'] ['VAT'],
            'shipping' => $_SESSION ['checkoutinfo'] ['shipping'],
            // 'freight' => $_SESSION['checkoutinfo']['freight'],
            'status' => $status,
            'payment_type' => $paymentType,
            'self_pickup' => $_SESSION ['checkoutinfo'] ['self_pickup']
        );
        // echo "</pre>"; print_r($obj); echo "</pre>"; exit;
        // $result = DBUtil::insertObject($obj, 'zselex_order');
        // $lastInsertId = DBUtil::getInsertID('zselex_order', 'id');

        $lastInsertId = $this->entityManager->getRepository('ZSELEX_Entity_Order')->createOrder($obj);
        $result       = $lastInsertId;
        // echo $result; exit;
        if ($result) {
            $rand                                   = rand(0000, 9999);
            $order_id                               = "ZS".$lastInsertId.$rand;
            $_SESSION ['checkoutinfo'] ['order_id'] = $order_id;


            $upd_args      = array(
                'fields' => array(
                    'order_id' => $order_id
                ),
                'where' => array(
                    'a.id' => $lastInsertId
                )
                )
            // 'where' => "a.id=:id"
            ;
            $updateOrderId = $this->entityManager->getRepository('ZSELEX_Entity_Order')->updateOrderId($upd_args);
            // echo "<pre>"; print_r($_SESSION['user_cart'][$shop_id]); echo "</pre>"; exit;



            $fields    = array(
                'a.cart_id',
                'a.quantity',
                'c.shop_id',
                'a.price',
                'a.final_price',
                'a.options_price',
                'a.cart_content',
                'b.product_id',
                'b.product_name',
                'b.prd_price',
                'b.prd_description',
                'b.prd_image',
                'a.prd_answer'
            );
            $setParams = array(
                'uid' => $user_id,
                'shop_id' => $shop_id
            );
            //  $where     = "a.user_id=:uid AND a.shop=:shop_id";
            $where     = "a.user_id=:uid AND a.shop_id=:shop_id";
            $get_cart  = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->getCartProducts(array(
                'user_id' => $user_id,
                'fields' => $fields,
                'where' => $where,
                'setParams' => $setParams
            ));
            //   echo "<pre>"; print_r($get_cart); echo "</pre>"; exit;
            // foreach ($_SESSION['user_cart'][$shop_id] as $key => $val) {
            foreach ($get_cart as $key => $val) {
                $obj2 = array(
                    'product_id' => $val ['product_id'],
                    'shop_id' => $shop_id,
                    'order_id' => $order_id,
                    'quantity' => $val ['quantity'],
                    'product_options' => $val ['cart_content'],
                    // 'price' => $val['prd_price'],
                    'price' => $val ['price'],
                    'options_price' => $val ['options_price'],
                    'prd_answer' => $val ['prd_answer'],
                    'total' => $val ['final_price']
                );

                // echo "<pre>"; print_r($obj2); echo "</pre><br>";
                // $result2 = DBUtil::insertObject($obj2, 'zselex_orderitems');

                $result2 = $this->entityManager->getRepository('ZSELEX_Entity_Order')->createOrderItems($obj2);
                // $result2 = ModUtil::apiFunc('ZSELEX', 'cart', 'insertOrderItems', $obj2);
            }
            // exit;
            // return $this->redirect(ModUtil::url('ZSELEX', 'user', 'payment'));
            /*
             * return $this->redirect(ModUtil::url('ZSELEX', 'user', 'order', array(
             * 'id' => $cart_shop_id
             * )));
             */
            if (!$payButtonExist) {
                // return $this->redirect(ModUtil::url('ZSELEX', 'user', 'orderConfirmation'));
            }
            return $this->redirect(ModUtil::url('ZSELEX', 'user', 'order'));
            // return $this->order();
        }
    }

    /**
     * Payment options
     * 
     * @return array
     */
    public function paymentoptions()
    {
        header("Cache-Control: max-age=300, must-revalidate");
        // $thislang = ZLanguage::getLanguageCodeLegacy();
        // echo $thislang; exit;
        /* $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
          '::', ACCESS_COMMENT), LogUtil::getErrorMsgPermission()); */
        $_SESSION ['netaxept']       = array();
        $_SESSION ['payment_method'] = '';
        $shop_id                     = $_REQUEST ['id'];
        // echo "Cart ShopId : " . $_SESSION['cart_shop_id'];
        //  $cart_shop_id                = $_SESSION ['cart_shop_id'];
        $cart_shop_id                = SessionUtil::getVar('cart_shop_id');
        if (empty($cart_shop_id)) {
            return $this->redirect(ModUtil::url('ZSELEX', 'user', 'cart'));
        }
        $cartError = ModUtil::apiFunc('ZSELEX', 'cart', 'validatecart',
                array('cart_shop_id' => $cart_shop_id));
        if ($cartError) {
            return $this->redirect(ModUtil::url('ZSELEX', 'user', 'cart'));
        }
        $shop_id                   = $cart_shop_id;
        System::queryStringSetVar('shop_id', $shop_id);
        $shop_name                 = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getSingleResult(array(
            'field' => 'shop_name',
            'where' => "a.shop_id=$shop_id"
        ));
        PageUtil::setVar('title', $this->__("Payment Options"." - ".$shop_name));
        $_SESSION ['cart_menu'] [] = 'paymentoptions';

        $payButtonExist = ModUtil::apiFunc('ZSELEX', 'admin',
                'serviceExistBlock',
                $args           = array(
                'shop_id' => $cart_shop_id,
                'type' => 'paybutton'
        ));

        if (!$payButtonExist) {
            // return $this->redirect(ModUtil::url('ZSELEX', 'user', 'placeOrder'));
        }

        $paypal_info   = '';
        $netaxept_info = '';
        if ($payButtonExist) {
            $paypal_info    = $this->entityManager->getRepository('ZPayment_Entity_Paypal')->getPaypal(array(
                'shop_id' => $cart_shop_id
            ));
            $netaxept_info  = $this->entityManager->getRepository('ZPayment_Entity_Netaxept')->getNetaxept(array(
                'shop_id' => $cart_shop_id
            ));
            $quickpay_info  = $this->entityManager->getRepository('ZPayment_Entity_QuickPay')->getQuickPay(array(
                'shop_id' => $cart_shop_id
            ));
            $directpay_info = $this->entityManager->getRepository('ZPayment_Entity_DirectpaySetting')->getDirectpay(array(
                'shop_id' => $cart_shop_id
            ));
            $epay_info      = $this->entityManager->getRepository('ZPayment_Entity_EpaySetting')->getEpay(array(
                'shop_id' => $cart_shop_id
            ));
            $this->view->assign('paypal_info', $paypal_info);
            $this->view->assign('netaxept_info', $netaxept_info);
            $this->view->assign('quickpay_info', $quickpay_info);
            $this->view->assign('directpay_info', $directpay_info);
            $this->view->assign('epay_info', $epay_info);
        }
        // echo "<pre>"; print_r($paypal_info); echo "</pre>";
        // $enable_checkout = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getSingleResult(array('field' => 'enable_checkoutinfo', 'where' => "a.shop_id=$cart_shop_id"));
        // echo "<pre>"; print_r($serviceExist); echo "</pre>";
        // echo "<pre>"; print_r($_SESSION['cart_menu']); echo "</pre>";
        // echo $shop_id; exit;

        if (!$payButtonExist && !$enable_checkout) {
            // echo "helloo world!!!";
            // $_SESSION['print_order'] = 1;
            $this->view->assign('print_order', 1);
            // return $this->redirect(ModUtil::url('ZSELEX', 'user', 'placeOrder'));
        }

        // $user_id = UserUtil::getVar('uid');
        $user_id = UserUtil::getVar('uid');
        if (!UserUtil::isLoggedIn()) {
            $user_id = ZSELEX_Util::getTempUserId();
        }
        $city   = FormUtil::getPassedValue('city', '', 'POST');
        $street = FormUtil::getPassedValue('street', '', 'POST');
        $adress = FormUtil::getPassedValue('address', '', 'POST');
        $phone  = FormUtil::getPassedValue('phone', '', 'POST');

        $return_url = pnGetBaseURL().'payPalReturn';
        $cancel_url = pnGetBaseURL().'paypalCancel';

        $self_pickup                               = FormUtil::getPassedValue('self_pickup',
                '', 'POST');
        $_SESSION ['checkoutinfo'] ['self_pickup'] = $self_pickup;
        // echo $self_pickup; exit;
        // echo $_SESSION['checkoutinfo']['self_pickup']; exit;
        if ($_SESSION ['checkoutinfo'] ['self_pickup']) {
            /* $_SESSION ['checkoutinfo'] ['grand_total_all'] = $_SESSION ['checkoutinfo'] ['grand_total_all']
              - $_SESSION ['checkoutinfo'] ['shipping'];
              $_SESSION ['checkoutinfo'] ['shipping']        = 0;
              $_SESSION ['checkoutinfo'] ['VAT']             = $_SESSION ['checkoutinfo'] ['grand_total_all']
             * 0.2; */
        }
        // echo $_SESSION['checkoutinfo']['VAT']; exit;
        if ($_POST == true) {
            $chnage_shipping_addr = FormUtil::getPassedValue('chng_shippingaddr',
                    '', 'POST');
            if ($chnage_shipping_addr) {
                $diffAdress                                  = FormUtil::getPassedValue('diffAddr',
                        '', 'POST');
                // echo "<pre>"; print_r($diffAdress); echo "</pre>";
                $_SESSION ['checkoutinfo'] ['shipping_info'] = $diffAdress;
            }
        }
        // echo "<pre>"; print_r($_SESSION['checkoutinfo']); echo "</pre>";
        $ZpaymentSettings = ModUtil::getVar('ZPayment');
        // echo "<pre>"; print_r($ZpaymentSettings); echo "</pre>";
        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('cart_shop_id', $cart_shop_id);
        $this->view->assign('return_url', $return_url);
        $this->view->assign('cancel_url', $cancel_url);
        $this->view->assign('ZpaymentSettings', $ZpaymentSettings);
        $this->view->assign('payButtonExist', $payButtonExist);
        $this->view->assign('enable_checkout', $enable_checkout);

        return $this->view->fetch('user/paymentoptions.tpl');
    }

    public function payment()
    {

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
         * 'status' => 'Placed',
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

        $this->view->assign('products', $products);

        $this->view->assign($_SESSION ['checkoutinfo']);
        return $this->view->fetch('user/payment.tpl');
    }

    /**
     * @Return URL function from paypal
     *
     * @return s order_id , and shop_id
     *         @Updates the order status to success/failed in Order table
     */
    public function payPalReturn1()
    {

        // echo "order id : " . $_REQUEST['order_id'] . '<br>'; exit;
        // echo "<pre>" ; print_r($_REQUEST); echo "</pre>"; exit;
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_COMMENT), LogUtil::getErrorMsgPermission());
        $ordr    = $_REQUEST ['order_id']; // retrieve order id from paypal.
        // $orderno = $_SESSION["ss_last_orderno"];
        $orderno = $ordr;

        // $orderno = $_SESSION['checkoutinfo'][order_id];
        // $ppAcc = "seller_1357558142_biz@.com";
        $ppAcc = "sharaz.khan-facilitator@.com";
        // $at = "D_fA7ggeD4MVD9j9jtnWJ9xBOM0z_-RuGnkCSb8O9mCRVAJhtF__cC0njmW"; //PDT Identity Token
        $at    = "0MMBakKt3Gl_9PeVYV6OA5QjgXOzav1Ffsh2tUr5FVtz7EYf2rl61yPeQbu"; // PDT Identity Token
        $url   = "https://www.sandbox.paypal.com/cgi-bin/webscr"; // Test
        // $url = "https://www.paypal.com/cgi-bin/webscr"; //Live
        $tx    = $_REQUEST ["tx"]; // this value is return by PayPal
        // $tx = '95C602744Y395553B';
        // echo $tx; exit;
        $cmd   = "_notify-synch";
        $post  = "tx=$tx&at=$at&cmd=$cmd";

        // Send request to PayPal server using CURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $result = curl_exec($ch); // returned result is key-value pair string
        $error  = curl_error($ch);

        if (curl_errno($ch) != 0) // CURL error
                exit("ERROR: Failed updating order. PayPal PDT service failed.");

        $longstr = str_replace("\r", "", $result);
        $lines   = split("\n", $longstr);
        // echo "<pre>"; print_r($lines); echo "</pre>"; exit;
        // parse the result string and store information to array
        // $lines[0] = "SUCCESS";
        if ($lines [0] == "SUCCESS") {
            // echo "comes here"; exit;
            // ZSELEX_Controller_User::clearCart();
            // echo $this->return_shop_id; exit;
            // echo "<pre>"; print_r($_SESSION['user_cart']); echo "</pre>"; exit;
            $user_id          = UserUtil::getVar('uid');
            $get_products     = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                    $getargs          = array(
                    'table' => 'zselex_cart',
                    'where' => "user_id=$user_id"
                    )
                    // 'fields' => array('id', 'quantity', 'availed')
            );
            $content          = $get_products ['cart_content'];
            $cart_unserialize = unserialize($content);
            unset($_SESSION ['user_cart'] [$this->return_shop_id]);
            unset($cart_unserialize [$this->return_shop_id]);
            $this->update_cart($cart_unserialize);
            unset($_SESSION ['cart_menu']);
            $shop_id          = $this->return_shop_id;

            // successful payment
            $ppInfo = array();
            for ($i = 1; $i < count($lines); $i ++) {
                $parts = split("=", $lines [$i]);
                if (count($parts) == 2) {
                    $ppInfo [$parts [0]] = urldecode($parts [1]);
                }
            }

            // echo "<pre>"; print_r($ppInfo); echo "</pre>"; exit;
            $payment_status = $ppInfo ['payment_status'];

            $curtime  = gmdate("d/m/Y H:i:s");
            // capture the PayPal returned information as order remarks
            $oremarks = "##$curtime##\n"."PayPal Transaction InformationÃƒÂ¢Ã¢â€šÂ¬Ã‚Â¦<br>"."Txn Id: ".$ppInfo ["txn_id"]."<br>"."Txn Type: ".$ppInfo ["txn_type"]."<br>"."Item Number: ".$ppInfo ["item_number"]."<br>"."Payment Date: ".$ppInfo ["payment_date"]."<br>"."Payment Type: ".$ppInfo ["payment_type"]."<br>"."Payment Status: ".$ppInfo ["payment_status"]."<br>"."Currency: ".$ppInfo ["mc_currency"]."<br>"."Payment Gross: ".$ppInfo ["payment_gross"]."<br>"."Payment Fee: ".$ppInfo ["payment_fee"]."<br>"."Payer Email: ".$ppInfo ["payer_email"]."<br>"."Payer Id: ".$ppInfo ["payer_id"]."<br>"."Payer Name: ".$ppInfo ["first_name"]." ".$ppInfo ["last_name"]."<br>"."Payer Status: ".$ppInfo ["payer_status"]."<br>"."Country: ".$ppInfo ["residence_country"]."<br>"."Business: ".$ppInfo ["business"]."<br>"."Receiver Email: ".$ppInfo ["receiver_email"]."<br>"."Receiver Id: ".$ppInfo ["receiver_id"]."<br>";

            // Update database using $orderno, set status to Paid
            // Send confirmation email to buyer and notification email to merchant
            // Redirect to thankyou page
            // echo $oremarks;

            $updateOrder = array(
                'table' => 'zselex_order',
                'IdValue' => $orderno,
                'fields' => array(
                    'status' => "'".$payment_status."'"
                ),
                'idName' => 'id',
                'where' => "order_id='".$orderno."'"
            );

            $updateOrderId               = ModUtil::apiFunc('ZSELEX', 'user',
                    'updateObject', $updateOrder);
            $shopsId                     = $_REQUEST ['cm'];
            $_SESSION ['shop_id_delete'] = $_REQUEST ['cm'];

            $update_paypal = $this->entityManager->getRepository('ZPayment_Entity_Paypal')->updatePaypalPayment($status
                = $payment_status, $orderno, $tx);

            $ownername = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                    $args      = array(
                    'shop_id' => $shop_id
            ));
            $this->view->assign('ownername', $ownername);

            /*
             * $orderDetails = ModUtil::apiFunc('ZSELEX', 'user', 'showPurchedOrder', $args = array(
             * 'order_id' => $orderno
             * ));
             */
            $orderDetails   = ModUtil::apiFunc('ZSELEX', 'user',
                    'getOrderDetails',
                    $args           = array(
                    'order_id' => $orderno
            ));
            $grand_total    = DBUtil::selectObjectSum($table          = "zselex_orderitems",
                    $column         = "total",
                    $where          = 'order_id="'.$orderno.'"',
                    $categoryFilter = null);
            // echo "<pre>"; print_r($orderDetails); echo "</pre>";
            $this->sendMailToUser($orderno); // send mail!

            $this->view->assign('order_id', $orderno);
            $this->view->assign('orderDetails', $orderDetails);
            $this->view->assign('grand_total', $grand_total);
            $this->view->assign('shop_id', $shopsId);
            $this->view->assign('reciept', $oremarks);
            return $this->view->fetch('user/thankyou.tpl');
        }

        // Payment failed
        else {
            // echo "Failed...."; exit;
            PageUtil::setVar('title', $this->__("Order Status - Failed"));
            $updateOrder = array(
                'table' => 'zselex_order',
                'IdValue' => $orderno,
                'fields' => array(
                    'status' => 'failed'
                ),
                'idName' => 'id',
                'where' => "order_id='".$orderno."'"
            );

            $updateOrderId = ModUtil::apiFunc('ZSELEX', 'user', 'updateObject',
                    $updateOrder);
            $update_paypal = $this->entityManager->getRepository('ZPayment_Entity_Paypal')->updatePaypalPayment($status
                = 'Failed', $orderno, $tx);
            return $this->view->fetch('user/pperror.tpl');
            // Delete order information
            // Redirect to failed page
        }

        // exit;
    }

    /**
     * Return from Paypal
     * 
     * @return html
     */
    public function payPalReturn()
    {

        // echo "order id : " . $_REQUEST['order_id'] . '<br>'; exit;
        // echo "<pre>" ; print_r($_REQUEST); echo "</pre>"; exit;
        /*  $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
          '::', ACCESS_COMMENT), LogUtil::getErrorMsgPermission()); */
        // $_SESSION ['cart_shop_id'] = '';
        SessionUtil::setVar("cart_shop_id", '');
        $ordr    = $_REQUEST ['order_id']; // retrieve order id from paypal.
        // $orderno = $_SESSION["ss_last_orderno"];
        $orderno = $ordr;
        $status  = $_REQUEST ['st'];
        $txn_id  = $_REQUEST ['tx'];
        $shop_id = $this->return_shop_id;
        $user_id = UserUtil::getVar('uid');

        $txnCount = $this->entityManager->getRepository('ZPayment_Entity_Paypal')->paypalTxnCount(array(
            'txn_id' => $txn_id
        ));

        if (!$txnCount) {
            unset($_SESSION ['user_cart'] [$this->return_shop_id]);
            // unset($cart_unserialize[$this->return_shop_id]);
            // $this->update_cart($cart_unserialize);
            unset($_SESSION ['cart_menu']);
            $shop_id        = $this->return_shop_id;
            $this->update_cart($shop_id, $orderno);
            unset($_SESSION ['checkoutinfo']);
            // echo "<pre>"; print_r($ppInfo); echo "</pre>"; exit;
            $payment_status = $status;

            setcookie("last_shop_id", $shop_id, time() + (86400 * 7), '/');

            $upd_args                    = array(
                'entity' => 'ZSELEX_Entity_Order',
                'fields' => array(
                    'status' => $payment_status,
                    'completed' => 1
                ),
                'where' => array(
                    'a.order_id' => $orderno
                )
                )
            // 'where' => "a.order_id=:order_id"
            ;
            $updateOrderId               = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->updateEntity($upd_args);
            $shopsId                     = $_REQUEST ['cm'];
            $_SESSION ['shop_id_delete'] = $_REQUEST ['cm'];
            $update_paypal               = $this->entityManager->getRepository('ZPayment_Entity_Paypal')->updatePaypalPayment($status
                = $payment_status, $orderno, $txn_id);

            $this->sendMailToUser($orderno); // send mail!
            /*
              $adminMailArgs = array('order_id' => $orderno, 'shop_id' => $shop_id,
              'gateway' => 'paypal', 'request_array' => array(), 'success' => 1);
              $notifyAdmin = ModUtil::apiFunc('ZPayment', 'QuickPay',
              'sendOrderMailToAdmin', $adminMailArgs);
             */
        }
        $ownername = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));
        $this->view->assign('ownername', $ownername);



        $orderInfoArgs = array(
            'fields' => array(
                'a.id',
                'a.status',
                'a.user_id',
                'b.shop_id',
                'a.payment_type',
                'a.vat',
                'a.shipping',
                'a.self_pickup',
                'a.totalprice'
            ),
            'where' => array(
                'a.order_id' => $orderno
            )
        );
        $orderInfo     = $this->entityManager->getRepository('ZSELEX_Entity_Order')->getOrderInfo($orderInfoArgs);
        $orderDetails  = ModUtil::apiFunc('ZSELEX', 'user', 'getOrderDetails',
                $args          = array(
                'order_id' => $orderno
        ));
        // $grand_total = DBUtil::selectObjectSum($table = "zselex_orderitems", $column = "total", $where = 'order_id="' . $orderno . '"', $categoryFilter = null);
        /* $grand_total   = $this->entityManager->getRepository('ZSELEX_Entity_OrderItem')->getOrderItemsTotal(array(
          'order_id' => $orderno
          ));
         */

        $grand_total = $orderInfo['totalprice'];

        // echo $grand_total;
        $vat      = $orderInfo ['vat'];
        $shipping = $orderInfo ['shipping'];
        if ($orderInfo ['self_pickup']) {
            $shipping = 0;
        }

        // $grand_total_all = $grand_total + $shipping;
        $grand_total_all = $grand_total;

        //echo "grand_total_all :". $grand_total_all;
        // echo "<pre>"; print_r($orderDetails); echo "</pre>";
        // echo "<pre>"; print_r($orderInfo); echo "</pre>";

        /*
          if ($_SERVER ['SERVER_NAME'] != 'localhost') {
          $this->sendMailToUser($orderno); // send mail!
          } */

        $paymentMode = $this->entityManager->getRepository('ZPayment_Entity_Paypal')->paymentMode(array(
            'shop_id' => $shop_id
        ));

        $this->view->assign('order_id', $orderno);
        $this->view->assign('orderDetails', $orderDetails);
        $this->view->assign('grand_total', $grand_total);
        $this->view->assign('grand_total_all', $grand_total_all);
        $this->view->assign('vat', $vat);

        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('shipping', $shipping);
        $this->view->assign('reciept', $oremarks);
        $this->view->assign('gateway', 'paypal');
        $this->view->assign('paymentMode', $paymentMode);
        return $this->view->fetch('user/thankyou.tpl');
    }

    /**
     * Send mail to user/owner after purchase success
     * 
     * @param type $order_id
     * @return boolean
     */
    public function sendMailToUser($order_id)
    {
        // $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_COMMENT), LogUtil::getErrorMsgPermission());

        $orderInfoArgs = array(
            'fields' => array(
                'a.id',
                'a.status',
                'a.user_id',
                'b.shop_id',
                'a.payment_type',
                'a.vat',
                'a.shipping',
                'a.email',
                'a.first_name',
                'a.last_name',
                'a.zip',
                'a.city',
                'a.street',
                'a.address',
                'a.phone',
                'a.totalprice',
                'a.status',
                'a.self_pickup'
            ),
            'where' => array(
                'a.order_id' => $order_id
            )
        );
        $orderInfo     = $this->entityManager->getRepository('ZSELEX_Entity_Order')->getOrderInfo($orderInfoArgs);
        $shop_id       = $orderInfo ['shop_id'];
        $payment_type  = $orderInfo ['payment_type'];
        $checout_info  = '';
        if ($payment_type == 'directpay') {
            // $checout_info = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getSingleResult(array('field' => 'checkout_info', 'where' => "a.shop_id=$shop_id"));
            $checout_info = $this->entityManager->getRepository('ZPayment_Entity_DirectpaySetting')->getDirectpay(array(
                'shop_id' => $shop_id
            ));
        }
        $user_email = '';
        $user_email = $orderInfo ['email'];
        switch ($payment_type) {
            case 'paypal' :
                $payment_method = $this->__("PayPal");
                $payment_mode   = $this->entityManager->getRepository('ZPayment_Entity_Paypal')->paymentMode(array(
                    'shop_id' => $shop_id
                ));
                break;
            case 'netaxept' :
                $payment_method = $this->__("Safe Payment through Netaxept");
                $payment_mode   = $this->entityManager->getRepository('ZPayment_Entity_Netaxept')->paymentMode(array(
                    'shop_id' => $shop_id
                ));
                break;
            case 'quickpay' :

                $payment_method = $this->__("QuickPay");
                $payment_mode   = $this->entityManager->getRepository('ZPayment_Entity_QuickPay')->paymentMode(array(
                    'shop_id' => $shop_id
                ));

                $payment_mode['test_mode'] = 0;
                break;
            case 'epay' :

                $payment_method = $this->__("Epay");
                $payment_mode   = $this->entityManager->getRepository('ZPayment_Entity_Epay')->paymentMode(array(
                    'shop_id' => $shop_id
                ));
                break;
            case 'directpay' :
                $payment_method = $this->__("Direct Payment");
                break;
            case 'printorder' :
                $payment_method = $this->__("Invoice");
                break;
        }

        $ownerInfo  = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwnerInfo',
                $args       = array(
                'shop_id' => $shop_id
        ));
        $owner_name = $ownerInfo ['uname'];

        $owners = $this->entityManager->getRepository('ZSELEX_Entity_ShopOwner')->getOwners(array(
            'shop_id' => $shop_id
        ));

        $owner_email    = '';
        $owner_email    = $ownerInfo ['email'];
        $orderItems     = ModUtil::apiFunc('ZSELEX', 'user', 'getOrderDetails',
                $args           = array(
                'order_id' => $order_id
        ));
        $GRANDTOTAL     = DBUtil::selectObjectSum($table          = "zselex_orderitems",
                $column         = "total", $where          = 'order_id="'.$order_id.'"',
                $categoryFilter = null);

        $vat      = $orderInfo ['vat'];
        // $vat      = $_SESSION ['checkoutinfo'] ['VAT'];
        $shipping = $orderInfo ['shipping'];
        /*
          if ($_SESSION ['checkoutinfo'] ['self_pickup']) {
          $shipping = 0;
          }
         */
        if ($orderInfo ['self_pickup']) {
            $shipping = 0;
        }


        $grand_total_all = $orderInfo['totalprice'];
        $mail_args       = array();

        $cardtype = '';
        if ($payment_type == 'netaxept') {
            $netaxept = $this->entityManager->getRepository('ZPayment_Entity_Netaxept')->getPaymentDetails(array(
                'order_id' => $order_id
            ));
            $cardtype = $netaxept ['cardtype'];
        } elseif ($payment_type == 'quickpay') {
            $quickpay = $this->entityManager->getRepository('ZPayment_Entity_QuickPay')->getPaymentDetails(array(
                'order_id' => $order_id
            ));
            $cardtype = $quickpay ['cardtype'];
        }

        $shopArgs  = array(
            'entity' => 'ZSELEX_Entity_Shop',
            'fields' => array(
                'a.shop_name',
                'a.address',
                'a.telephone',
                'a.email'
            ),
            'where' => array(
                'a.shop_id' => $shop_id
            )
        );
        $getShop   = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->get($shopArgs);
        $shop_name = $getShop ['shop_name'];

        $hasNoVatProduct = $this->entityManager->getRepository('ZSELEX_Entity_Order')->hasNoVatProduct(array(
            'order_id' => $order_id
        ));
        $renderArgs      = array(
            'order_id' => $order_id,
            'shop_id' => $shop_id,
            'shop_name' => $shop_name,
            'order_info' => $orderInfo,
            'shop_info' => $getShop,
            'items' => $orderItems,
            'checkout_info' => $checout_info,
            'payment_method' => $payment_method,
            'payment_mode' => $payment_mode,
            'cardtype' => $cardtype,
            'vat' => $vat,
            'shipping' => $shipping,
            'GRANDTOTAL' => $GRANDTOTAL,
            'grand_total_all' => $grand_total_all,
            'owner_name' => $owner_name,
            'hasNoVatProduct' => $hasNoVatProduct
            )

        ;

        if ($user_email != '' && filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            // send mail to user
            $mail_args1 = array(
                'toaddress' => $user_email,
                'fromname' => System::getVar('sitename'),
                'subject' => $this->__('Order Details').', '.$shop_name.', '.$order_id,
                'fromaddress' => $owner_email,
                'renderArgs' => $renderArgs
            );
            $sendMail1  = ModUtil::apiFunc('ZSELEX', 'mail',
                    'sendOrderConfirmation', $mail_args1);
        }


        foreach ($owners as $k => $v) {
            $renderArgsOwner = array(
                'order_id' => $order_id,
                'shop_id' => $shop_id,
                'shop_name' => $shop_name,
                'order_info' => $orderInfo,
                'items' => $orderItems,
                'checkout_info' => $checout_info,
                'payment_method' => $payment_method,
                'payment_mode' => $payment_mode,
                'GRANDTOTAL' => $GRANDTOTAL,
                'owner_name' => $v ['uname']
                )

            ;
            if ($v ['email'] != '' && filter_var($v ['email'],
                    FILTER_VALIDATE_EMAIL)) {
                // send mail to owner
                $mail_args2 = array(
                    'toaddress' => $v ['email'],
                    'fromname' => System::getVar('sitename'),
                    'subject' => $this->__('Order Received'),
                    'renderArgs' => $renderArgsOwner
                );
                $sendMail2  = ModUtil::apiFunc('ZSELEX', 'mail',
                        'sendOrderConfirmationToOwner', $mail_args2);
            }
        }
        return true;
    }

    public function paypalCancel($args)
    {
        PageUtil::setVar('title', $this->__("Order Status - Cancelled"));

        $shop_id     = $_REQUEST ['shop_id'];
        $paymentMode = $this->entityManager->getRepository('ZPayment_Entity_Paypal')->paymentMode(array(
            'shop_id' => $shop_id
        ));
        // echo "<pre>"; print_r($_REQUEST); echo "</pre>";
        // echo $this->return_shop_id;
        if ($paymentMode ['test_mode'] == true) {
            LogUtil::registerError($this->__('Paypal payment is in test mode for this shop'));
        }
        $order_id = $_REQUEST ['order_id'];

        $tx = $_REQUEST ["tx"];
        /*
         * $updateOrder = array(
         * 'table' => 'zselex_order',
         * 'IdValue' => $order_id,
         * 'fields' => array('status' => 'Cancelled'),
         * 'idName' => 'id',
         * 'where' => "order_id='" . $order_id . "'",
         * );
         * $updateOrderId = ModUtil::apiFunc('ZSELEX', 'user', 'updateObject', $updateOrder);
         */

        $upd_args      = array(
            'entity' => 'ZSELEX_Entity_Order',
            'fields' => array(
                'status' => 'Cancelled'
            ),
            'where' => array(
                'a.order_id' => $order_id
            )
        );
        $updateOrderId = $this->entityManager->getRepository('ZSELEX_Entity_Order')->updateEntity($upd_args);

        $update_paypal = $this->entityManager->getRepository('ZPayment_Entity_Paypal')->updatePaypalPayment($status
            = 'Cancelled', $order_id, $tx);
        return $this->view->fetch('user/ppcancelled.tpl');
    }

    public function paypalIPN()
    {

        // echo "comes here....."; exit;
        $sql = "update zpayment_paypal set status='Failedsssss'";
        // DBUtil::executeSQL($sql);
        define("DEBUG", 1);

        // Set to 0 once you're ready to go live
        define("USE_SANDBOX", 1);

        define("LOG_FILE", "./ipn.log");

        // Read POST data
        // reading posted data directly from $_POST causes serialization
        // issues with array data in POST. Reading raw POST data from input stream instead.
        $raw_post_data  = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost         = array();
        foreach ($raw_post_array as $keyval) {
            $keyval               = explode('=', $keyval);
            if (count($keyval) == 2)
                    $myPost [$keyval [0]] = urldecode($keyval [1]);
        }
        // read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-validate';
        if (function_exists('get_magic_quotes_gpc')) {
            $get_magic_quotes_exists = true;
        }
        foreach ($myPost as $key => $value) {
            if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
                $value = urlencode(stripslashes($value));
            } else {
                $value = urlencode($value);
            }
            $req .= "&$key=$value";
        }

        // Post IPN data back to PayPal to validate the IPN data is genuine
        // Without this step anyone can fake IPN data

        if (USE_SANDBOX == true) {
            $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
        } else {
            $paypal_url = "https://www.paypal.com/cgi-bin/webscr";
        }

        $ch = curl_init($paypal_url);
        if ($ch == FALSE) {
            return FALSE;
        }

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);

        if (DEBUG == true) {
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
        }

        // CONFIG: Optional proxy configuration
        // curl_setopt($ch, CURLOPT_PROXY, $proxy);
        // curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
        // Set TCP timeout to 30 seconds
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
            'Connection: Close'
        ));

        // CONFIG: Please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path
        // of the certificate as shown below. Ensure the file is readable by the webserver.
        // This is mandatory for some environments.
        // $cert = __DIR__ . "./cacert.pem";
        // curl_setopt($ch, CURLOPT_CAINFO, $cert);

        $res = curl_exec($ch);
        if (curl_errno($ch) != 0) { // cURL error
            if (DEBUG == true) {
                error_log(date('[Y-m-d H:i e] ')."Can't connect to PayPal to validate IPN message: ".curl_error($ch).PHP_EOL,
                    3, LOG_FILE);
            }
            curl_close($ch);
            exit();
        } else {
            // Log the entire HTTP response if debug is switched on.
            if (DEBUG == true) {
                error_log(date('[Y-m-d H:i e] ')."HTTP request of validation request:".curl_getinfo($ch,
                        CURLINFO_HEADER_OUT)." for IPN payload: $req".PHP_EOL,
                    3, LOG_FILE);
                error_log(date('[Y-m-d H:i e] ')."HTTP response of validation request: $res".PHP_EOL,
                    3, LOG_FILE);

                // Split response headers and payload
                list ( $headers, $res ) = explode("\r\n\r\n", $res, 2);
            }
            curl_close($ch);
        }

        // Inspect IPN validation result and act accordingly
        $txn_id = $_POST ['txn_id'];
        if (strcmp($res, "VERIFIED") == 0) {
            // check whether the payment_status is Completed
            // check that txn_id has not been previously processed
            // check that receiver_email is your PayPal email
            // check that payment_amount/payment_currency are correct
            // process payment and mark item as paid.
            // assign posted variables to local variables
            $item_name        = $_POST ['item_name'];
            $item_number      = $_POST ['item_number'];
            $payment_status   = $_POST ['payment_status'];
            $payment_amount   = $_POST ['mc_gross'];
            $payment_currency = $_POST ['mc_currency'];
            $txn_id           = $_POST ['txn_id'];
            $receiver_email   = $_POST ['receiver_email'];
            $payer_email      = $_POST ['payer_email'];
            $sql              = "update zpayment_paypal set status='".$payment_status."sssss' where transaction_id='".$txn_id."'";
            DBUtil::executeSQL($sql);
            if (DEBUG == true) {
                error_log(date('[Y-m-d H:i e] ')."Verified IPN: $req ".PHP_EOL,
                    3, LOG_FILE);
            }
        } else if (strcmp($res, "INVALID") == 0) {
            // log for manual investigation
            $sql = "update zpayment_paypal set status='Failedsssss' where transaction_id='".$txn_id."'";
            DBUtil::executeSQL($sql);
            // Add business logic here which deals with invalid IPN messages
            if (DEBUG == true) {
                error_log(date('[Y-m-d H:i e] ')."Invalid IPN: $req".PHP_EOL, 3,
                    LOG_FILE);
            }
        }
    }

    /**
     * This is a page to provide an textual overview of caching concepts
     * 
     * @return string
     */
    public function cacheinfo()
    {
        // template needs to know where the directories are
        $this->view->assign('compiledir', $this->view->getCompileDir());
        $this->view->assign('cachedir', $this->view->getCacheDir());

        return $this->view->fetch('user/cachedemo/info.tpl');
    }

    /**
     * This is a standard page that returns a template view
     * It DOES respect the settings in Theme->settings->render caching
     * (on/off and lifetime)
     *
     * @return string
     */
    public function standard()
    {
        $this->view->assign('time', microtime(true));
        return $this->view->fetch('user/cachedemo/standard.tpl');
    }

    /**
     * This is a page that should never return cached information.
     * It does not
     * respect cache settings (on/off) in Theme. The page should always return
     * new information regardless of all cache settings.
     * 
     * @return string
     */
    public function nevercached()
    {
        // force caching off
        $this->view->setCaching(Zikula_View::CACHE_DISABLED);

        $this->view->assign('time', microtime(true));
        return $this->view->fetch('user/cachedemo/nevercached.tpl');
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
    public function partialcache()
    {
        // force caching on
        $this->view->setCaching(Zikula_View::CACHE_ENABLED);

        // force local cache lifetime
        $localcachelifetime = 31;
        $this->view->setCacheLifetime($localcachelifetime);

        $this->view->assign('time', microtime(true));
        $this->view->assign('localcachelifetime', $localcachelifetime);
        return $this->view->fetch('user/cachedemo/partialcache.tpl');
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
    public function uniquepages()
    {
        $submit = (int) $this->request->getPost()->get('submit', 0);
        $page   = (int) $this->request->getPost()->get('page', 1);
        // enfore min/max values for $page
        if ($page < 1) {
            $page = 1;
        }
        if ($page > 9) {
            $page = 9;
        }

        $template = 'user/cachedemo/uniquepages.tpl';

        // force caching on
        $this->view->setCaching(Zikula_View::CACHE_ENABLED);

        // force local cache lifetime
        $localcachelifetime = 120;
        $this->view->setCacheLifetime($localcachelifetime);

        // setting the cacheid forces each page version of the template to unique
        $this->view->setCacheId($page);

        switch ($submit) {
            case - 100 : // clear this page template cache
                $this->view->clear_cache($template, $this->view->getCacheId());
                LogUtil::registerStatus($this->__f("Just this version of '%s' cleared from cache.",
                        $template));
                break;
            case - 200 : // clear all page uses of this template cache
                $this->view->clear_cache($template);
                LogUtil::registerStatus($this->__f("All versions of '%s' cleared from cache.",
                        $template));
                break;
            // NOTE: calling $this->view->clear_cache(); (with no arguments) clears all cached templates for *this* module.
        }

        $this->view->assign('cacheid', $this->view->getCacheId());
        $this->view->assign('submit', $submit);

        $this->view->assign('page', $page);
        $this->view->assign('time', microtime(true));
        $this->view->assign('localcachelifetime', $localcachelifetime);
        return $this->view->fetch($template);
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
    public function checkiscached()
    {
        $template = 'user/cachedemo/checkiscached.tpl';

        // force caching on
        $this->view->setCaching(Zikula_View::CACHE_ENABLED);

        // force local cache lifetime
        $localcachelifetime = 31;
        $this->view->setCacheLifetime($localcachelifetime);

        // check to see if the tempalte is cached, if not, get required data
        if (!$this->view->is_cached($template)) {
            // manufactured wait to demo DB fetch or something resource intensive
            sleep(5);

            $this->view->assign('time', microtime(true));
            $this->view->assign('localcachelifetime', $localcachelifetime);
        }
        return $this->view->fetch($template);
    }

    public function creates($args)
    {

        // echo "comes here"; exit;
        if (News_Controller_User::create($args)) {

            // echo "done here"; exit;
            ZSELEX_Controller_User::redirect(ModUtil::url('ZSELEX', 'user',
                    'viewshoparticles',
                    array(
                    'shop_id' => '104'
            )));
        }
    }

    /**
     * News module user controller override class function
     */
    public function create($args)
    {

        // echo "Works Fine!!!"; exit;
        // Get parameters from whatever input we need
        $story = FormUtil::getPassedValue('story',
                isset($args ['story']) ? $args ['story'] : null, 'POST');
        $files = News_ImageUtil::reArrayFiles(FormUtil::getPassedValue('news_files',
                    null, 'FILES'));

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
        $item = array(
            'title' => $story ['title'],
            'urltitle' => isset($story ['urltitle']) ? $story ['urltitle'] : '',
            '__CATEGORIES__' => isset($story ['__CATEGORIES__']) ? $story ['__CATEGORIES__']
                    : null,
            '__ATTRIBUTES__' => isset($story ['attributes']) ? News_Util::reformatAttributes($story ['attributes'])
                    : null,
            'language' => isset($story ['language']) ? $story ['language'] : '',
            'hometext' => isset($story ['hometext']) ? $story ['hometext'] : '',
            'hometextcontenttype' => $story ['hometextcontenttype'],
            'bodytext' => isset($story ['bodytext']) ? $story ['bodytext'] : '',
            'bodytextcontenttype' => $story ['bodytextcontenttype'],
            'notes' => $story ['notes'],
            'displayonindex' => isset($story ['displayonindex']) ? $story ['displayonindex']
                    : 0,
            'allowcomments' => isset($story ['allowcomments']) ? $story ['allowcomments']
                    : 0,
            'from' => isset($story ['from']) ? $story ['from'] : null,
            'tonolimit' => isset($story ['tonolimit']) ? $story ['tonolimit'] : null,
            'to' => isset($story ['to']) ? $story ['to'] : null,
            'unlimited' => isset($story ['unlimited']) && $story ['unlimited'] ? true
                    : false,
            'weight' => isset($story ['weight']) ? $story ['weight'] : 0,
            'action' => isset($story ['action']) ? $story ['action'] : self::ACTION_PREVIEW,
            'sid' => isset($story ['sid']) ? $story ['sid'] : null,
            'tempfiles' => isset($story ['tempfiles']) ? $story ['tempfiles'] : null,
            'del_pictures' => isset($story ['del_pictures']) ? $story ['del_pictures']
                    : null
        );

        // echo "<pre>"; print_r($item); echo "</pre>"; exit;
        // convert user times to server times (TZ compensation) refs #181
        // can't do the below because values are YYYY-MM-DD HH:MM:SS and DateUtil value is in seconds.
        // $item['from'] = $item['from'] + DateUtil::getTimezoneUserDiff();
        // $item['to'] = $item['to'] + DateUtil::getTimezoneUserDiff();
        // Disable the non accessible fields for non editors
        if (!SecurityUtil::checkPermission('News::', '::', ACCESS_ADD)) {
            $item ['notes']          = '';
            $item ['displayonindex'] = 1;
            $item ['allowcomments']  = 1;
            $item ['from']           = null;
            $item ['tonolimit']      = true;
            $item ['to']             = null;
            $item ['unlimited']      = true;
            $item ['weight']         = 0;
            if ($item ['action'] > self::ACTION_SUBMIT) {
                $item ['action'] = self::ACTION_PREVIEW;
            }
        }

        // Validate the input
        $validationerror = News_Util::validateArticle($item);
        // check hooked modules for validation
        $sid             = isset($item ['sid']) ? $item ['sid'] : null;
        $hookvalidators  = News_Controller_User::notifyHooks(new Zikula_ValidationHook('news.ui_hooks.articles.validate_edit',
                new Zikula_Hook_ValidationProviders()))->getValidators();
        if ($hookvalidators->hasErrors()) {
            $validationerror .= News_Controller_User::__('Error! Hooked content does not validate.')."\n";
        }

        // get all module vars
        // $modvars = News_Controller_User::getVars();

        $modvars = ModUtil::getVar('News');

        // echo "<pre>"; print_r($modvars); echo "</pre>"; exit;

        if (isset($files) && $modvars ['picupload_enabled']) {
            list ( $files, $item ) = News_ImageUtil::validateImages($files,
                    $item);
        } else {
            $item ['pictures'] = 0;
        }

        // story was previewed with uploaded pics
        if (isset($item ['tempfiles'])) {
            $tempfiles = unserialize($item ['tempfiles']);
            // delete files if requested
            if (isset($item ['del_pictures'])) {
                foreach ($tempfiles as $key => $file) {
                    if (in_array($file ['name'], $item ['del_pictures'])) {
                        unset($tempfiles [$key]);
                        News_ImageUtil::removePreviewImages(array(
                            $file
                        ));
                    }
                }
            }
            $files = array_merge($files, $tempfiles);
            $item ['pictures'] += count($tempfiles);
        }

        // if the user has selected to preview the article we then route them back
        // to the new function with the arguments passed here
        if ($item ['action'] == self::ACTION_PREVIEW || $validationerror !== false) {
            // log the error found if any
            if ($validationerror !== false) {
                LogUtil::registerError(nl2br($validationerror));
            }
            if ($item ['pictures'] > 0) {
                $tempfiles          = News_ImageUtil::tempStore($files);
                $item ['tempfiles'] = serialize($tempfiles);
            }
            // back to the referer form
            SessionUtil::setVar('newsitem', $item);
            $this->redirect(ModUtil::url('ZSELEX', 'user', 'newitem',
                    array(
                    'shop_id' => $shop_id
            )));
        } else {
            // As we're not previewing the item let's remove it from the session
            SessionUtil::delVar('newsitem');
        }

        // Confirm authorization code.
        News_Controller_User::checkCsrfToken();

        if (!isset($item ['sid']) || empty($item ['sid'])) {
            // Create the news story
            $sid = ModUtil::apiFunc('News', 'user', 'create', $item);

            // echo "Last Id: " . $sid; exit;
            if ($sid != false) {

                $shopNewsArg = array(
                    'shop_id' => $shop_id,
                    'news_id' => $sid,
                    'cr_uid' => UserUtil::getVar('uid')
                );

                // print_r($shopNewsArg); exit;
                ModUtil::apiFunc('ZSELEX', 'user', 'insertShopNews',
                    $shopNewsArg);
                $_SESSION ['shopsid']   = '';
                $_SESSION ['shopsname'] = '';

                // $argsused = array('type' => 'createarticles', 'shop_id' => $shop_id, 'user_id' => UserUtil::getVar('uid'));
                // ModUtil::apiFunc('ZSELEX', 'user', 'updateArticleService', $argsused);

                $user_id          = UserUtil::getVar('uid');
                $serviceupdatearg = array(
                    'user_id' => $user_id,
                    'type' => 'createarticle',
                    'shop_id' => $shop_id
                );
                $serviceavailed   = ModUtil::apiFunc('ZSELEX', 'admin',
                        'updateServiceUsed', $serviceupdatearg);

                // DBUtil::insertObject($shopNewsArg, 'zselex_shop_news' , 'shop_news_id');
                // Success
                LogUtil::registerStatus(News_Controller_User::__('Done! Article has been created successfully.'));
                // Let any hooks know that we have created a new item
                News_Controller_User::notifyHooks(new Zikula_ProcessHook('news.ui_hooks.articles.process_edit',
                    $sid,
                    new Zikula_ModUrl('News', 'User', 'display',
                    ZLanguage::getLanguageCode(),
                    array(
                    'sid' => $sid
                ))));
                News_Controller_User::notify($item); // send notification email
            } else {
                // fail! story not created
                throw new Zikula_Exception_Fatal(News_Controller_User::__('Story not created for unknown reason (Api failure).'));
                return false;
            }
        } else {
            // update the draft
            $result = ModUtil::apiFunc('News', 'admin', 'update', $item);
            if ($result) {
                LogUtil::registerStatus(News_Controller_User::__('Done! Story has been updated successfully.'));
            } else {
                // fail! story not updated
                throw new Zikula_Exception_Fatal(News_Controller_User::__('Story not updated for unknown reason (Api failure).'));
                return false;
            }
        }

        // clear article and view caches
        News_Controller_User::clearArticleCaches($item, $this);

        if (isset($files) && $modvars ['picupload_enabled']) {
            $resized = News_ImageUtil::resizeImages($sid, $files); // resize and move the uploaded pics
            if (isset($item ['tempfiles'])) {
                News_ImageUtil::removePreviewImages($tempfiles); // remove any preview images
            }
            LogUtil::registerStatus(News_Controller_User::_fn('%1$s out of %2$s picture was uploaded and resized.',
                    '%1$s out of %2$s pictures were uploaded and resized.',
                    $item ['pictures'],
                    array(
                    $resized,
                    $item ['pictures']
            )));
            if (($item ['action'] >= self::ACTION_SAVEDRAFT) && ($resized != $item ['pictures'])) {
                LogUtil::registerStatus(News_Controller_User::_fn('Article now has draft status, since the picture was not uploaded.',
                        'Article now has draft status, since not all pictures were uploaded.',
                        $item ['pictures'],
                        array(
                        $resized,
                        $item ['pictures']
                )));
            }
        }

        // release pagelock
        if (ModUtil::available('PageLock')) {
            ModUtil::apiFunc('PageLock', 'user', 'releaseLock',
                array(
                'lockName' => "Newsnews{$item['sid']}"
            ));
        }

        if ($item ['action'] == self::ACTION_SAVEDRAFT_RETURN) {
            SessionUtil::setVar('newsitem', $item);
            News_Controller_User::redirect(ModUtil::url('News', 'user',
                    'newitem'));
        }
        // News_Controller_User::redirect(ModUtil::url('News', 'user', 'view'));

        ZSELEX_Controller_User::redirect(ModUtil::url('ZSELEX', 'user',
                'viewshoparticles',
                array(
                'shop_id' => $shop_id
        )));
    }

    public function createforevent($args)
    {

        // Get parameters from whatever input we need
        $story = FormUtil::getPassedValue('story',
                isset($args ['story']) ? $args ['story'] : null, 'POST');
        $files = News_ImageUtil::reArrayFiles(FormUtil::getPassedValue('news_files',
                    null, 'FILES'));

        $shop_id = $_REQUEST ['shop_id'];
        // Create the item array for processing
        $item    = array(
            'title' => $story ['title'],
            'urltitle' => isset($story ['urltitle']) ? $story ['urltitle'] : '',
            '__CATEGORIES__' => isset($story ['__CATEGORIES__']) ? $story ['__CATEGORIES__']
                    : null,
            '__ATTRIBUTES__' => isset($story ['attributes']) ? News_Util::reformatAttributes($story ['attributes'])
                    : null,
            'language' => isset($story ['language']) ? $story ['language'] : '',
            'hometext' => isset($story ['hometext']) ? $story ['hometext'] : '',
            'hometextcontenttype' => $story ['hometextcontenttype'],
            'bodytext' => isset($story ['bodytext']) ? $story ['bodytext'] : '',
            'bodytextcontenttype' => $story ['bodytextcontenttype'],
            'notes' => $story ['notes'],
            'displayonindex' => isset($story ['displayonindex']) ? $story ['displayonindex']
                    : 0,
            'allowcomments' => isset($story ['allowcomments']) ? $story ['allowcomments']
                    : 0,
            'from' => isset($story ['from']) ? $story ['from'] : null,
            'tonolimit' => isset($story ['tonolimit']) ? $story ['tonolimit'] : null,
            'to' => isset($story ['to']) ? $story ['to'] : null,
            'unlimited' => isset($story ['unlimited']) && $story ['unlimited'] ? true
                    : false,
            'weight' => isset($story ['weight']) ? $story ['weight'] : 0,
            'action' => isset($story ['action']) ? $story ['action'] : self::ACTION_PREVIEW,
            'sid' => isset($story ['sid']) ? $story ['sid'] : null,
            'tempfiles' => isset($story ['tempfiles']) ? $story ['tempfiles'] : null,
            'del_pictures' => isset($story ['del_pictures']) ? $story ['del_pictures']
                    : null
        );

        // echo "<pre>"; print_r($item); echo "</pre>"; exit;
        // convert user times to server times (TZ compensation) refs #181
        // can't do the below because values are YYYY-MM-DD HH:MM:SS and DateUtil value is in seconds.
        // $item['from'] = $item['from'] + DateUtil::getTimezoneUserDiff();
        // $item['to'] = $item['to'] + DateUtil::getTimezoneUserDiff();
        // Disable the non accessible fields for non editors
        if (!SecurityUtil::checkPermission('News::', '::', ACCESS_ADD)) {
            $item ['notes']          = '';
            $item ['displayonindex'] = 1;
            $item ['allowcomments']  = 1;
            $item ['from']           = null;
            $item ['tonolimit']      = true;
            $item ['to']             = null;
            $item ['unlimited']      = true;
            $item ['weight']         = 0;
            if ($item ['action'] > self::ACTION_SUBMIT) {
                $item ['action'] = self::ACTION_PREVIEW;
            }
        }

        // Validate the input
        $validationerror = News_Util::validateArticle($item);
        // check hooked modules for validation
        $sid             = isset($item ['sid']) ? $item ['sid'] : null;
        $hookvalidators  = $this->notifyHooks(new Zikula_ValidationHook('news.ui_hooks.articles.validate_edit',
                new Zikula_Hook_ValidationProviders()))->getValidators();
        if ($hookvalidators->hasErrors()) {
            $validationerror .= $this->__('Error! Hooked content does not validate.')."\n";
        }

        // get all module vars
        $modvars = ModUtil::getVar('News');

        if (isset($files) && $modvars ['picupload_enabled']) {
            list ( $files, $item ) = News_ImageUtil::validateImages($files,
                    $item);
        } else {
            $item ['pictures'] = 0;
        }

        // story was previewed with uploaded pics
        if (isset($item ['tempfiles'])) {
            $tempfiles = unserialize($item ['tempfiles']);
            // delete files if requested
            if (isset($item ['del_pictures'])) {
                foreach ($tempfiles as $key => $file) {
                    if (in_array($file ['name'], $item ['del_pictures'])) {
                        unset($tempfiles [$key]);
                        News_ImageUtil::removePreviewImages(array(
                            $file
                        ));
                    }
                }
            }
            $files = array_merge($files, $tempfiles);
            $item ['pictures'] += count($tempfiles);
        }

        // if the user has selected to preview the article we then route them back
        // to the new function with the arguments passed here
        if ($item ['action'] == self::ACTION_PREVIEW || $validationerror !== false) {
            // log the error found if any
            if ($validationerror !== false) {
                LogUtil::registerError(nl2br($validationerror));
            }
            if ($item ['pictures'] > 0) {
                $tempfiles          = News_ImageUtil::tempStore($files);
                $item ['tempfiles'] = serialize($tempfiles);
            }
            // back to the referer form
            SessionUtil::setVar('newsitem', $item);
            $this->redirect(ModUtil::url('ZSELEX', 'user', 'newitemevent',
                    array(
                    'shop_id' => $shop_id
            )));
        } else {
            // As we're not previewing the item let's remove it from the session
            SessionUtil::delVar('newsitem');
        }

        // Confirm authorization code.
        // $this->checkCsrfToken();

        if (!isset($item ['sid']) || empty($item ['sid'])) {
            // Create the news story
            $sid = ModUtil::apiFunc('News', 'user', 'create', $item);
            if ($sid != false) {
                // Success
                LogUtil::registerStatus($this->__('Done! Article has been created successfully.'));
                // Let any hooks know that we have created a new item
                // $this->notifyHooks(new Zikula_ProcessHook('news.ui_hooks.articles.process_edit', $sid, new Zikula_ModUrl('News', 'User', 'display', ZLanguage::getLanguageCode(), array('sid' => $sid))));
                // $this->notify($item); // send notification email
            } else {
                // fail! story not created
                throw new Zikula_Exception_Fatal($this->__('Story not created for unknown reason (Api failure).'));
                return false;
            }
        } else {
            // update the draft
            $result = ModUtil::apiFunc('News', 'admin', 'update', $item);
            if ($result) {
                LogUtil::registerStatus($this->__('Done! Story has been updated successfully.'));
            } else {
                // fail! story not updated
                throw new Zikula_Exception_Fatal($this->__('Story not updated for unknown reason (Api failure).'));
                return false;
            }
        }

        // clear article and view caches
        // self::clearArticleCaches($item, $this);

        if (isset($files) && $modvars ['picupload_enabled']) {
            $resized = News_ImageUtil::resizeImages($sid, $files); // resize and move the uploaded pics
            if (isset($item ['tempfiles'])) {
                News_ImageUtil::removePreviewImages($tempfiles); // remove any preview images
            }
            LogUtil::registerStatus($this->_fn('%1$s out of %2$s picture was uploaded and resized.',
                    '%1$s out of %2$s pictures were uploaded and resized.',
                    $item ['pictures'],
                    array(
                    $resized,
                    $item ['pictures']
            )));
            if (($item ['action'] >= self::ACTION_SAVEDRAFT) && ($resized != $item ['pictures'])) {
                LogUtil::registerStatus($this->_fn('Article now has draft status, since the picture was not uploaded.',
                        'Article now has draft status, since not all pictures were uploaded.',
                        $item ['pictures'],
                        array(
                        $resized,
                        $item ['pictures']
                )));
            }
        }

        // release pagelock
        if (ModUtil::available('PageLock')) {
            ModUtil::apiFunc('PageLock', 'user', 'releaseLock',
                array(
                'lockName' => "Newsnews{$item['sid']}"
            ));
        }

        if ($item ['action'] == self::ACTION_SAVEDRAFT_RETURN) {
            SessionUtil::setVar('newsitem', $item);
            $this->redirect(ModUtil::url('ZSELEX', 'admin', 'createevent',
                    array(
                    'shop_id' => $shop_id
            )));
        }
        // $this->redirect(ModUtil::url('News', 'user', 'view'));

        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'createevent',
                array(
                'shop_id' => $shop_id
        )));
    }

    public function eventperdate($args)
    {
        $eventdate = $_REQUEST ['date'];
        $events    = ModUtil::apiFunc('ZSELEX', 'user', 'getEventsPerDay',
                array(
                'date' => $eventdate
        ));

        // echo "<pre>"; print_r($events); echo "</pre>"; exit;

        $dateFormat = date("j F Y", strtotime($eventdate));
        $this->view->assign('dateFormat', $dateFormat);
        $this->view->assign('events', $events);

        return $this->view->fetch('user/eventsperday.tpl');
    }

    /**
     * shop front page
     * 
     * this function is for displaying shop info in owner's minisite
     */
    public function site($args)
    {
        // echo UserUtil::getVar('email'); exit;
        // echo "Theme : " . $this->current_theme;
        // echo "<pre>"; print_r($_REQUEST); echo "</pre>";
        // echo "userId :" . UserUtil::getVar('uid');
        // header('Location:http://localhost/test.php');
        $this->view->assign('currenttheme', $this->current_theme);
        $getDate = '';
        if (isset($_GET ['date']) && !empty($_GET ['date'])) {
            $getDate = $_GET ['date'];
        } else {
            $getDate = '';
        }

        $shop_id = FormUtil::getPassedValue('shop_id', '', 'REQUEST');
        // echo "shopID: " . $shop_id; exit;
        // echo "comes here";

        $shoptitle = FormUtil::getPassedValue('shoptitle', '', 'REQUEST');
        $shoptitle = DataUtil::formatForStore($shoptitle);

        // echo "title: " . $shoptitle; exit;

        if (empty($shop_id) && empty($shoptitle)) {
            // return LogUtil::registerError($this->__('Error! Type-usercontroller_2 not found.'));
            return LogUtil::registerError($this->__('Error! Site not found.'),
                    403);
        }

        // $_COOKIE['last_shop_id'] = $shop_id;
        // setcookie("last_shop_id", $shop_id, time() + (86400 * 7), '/');
        // echo "shop id :" . $shop_id;
        $shop = array();

        // $item = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getMinisite(array('shop_id' => $shop_id));
        // $item = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getMinisite(array('shop_id' => $shop_id));
        $item = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getMinisite(array(
            'shop_id' => $shop_id,
            'title' => $shoptitle
        ));
        // echo "<pre>";print_r($item);echo "</pre>"; exit;
        if (!$item) {
            return LogUtil::registerError($this->__('Error! Site not found.'),
                    404);
        }

        $shop_id        = $item ['shop_id'];
        setcookie("last_shop_id", $shop_id, time() + (86400 * 7), '/');
        $ownerName      = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                array(
                'shop_id' => $shop_id
        ));
        // echo $ownerName; exit;
        $item ['uname'] = $ownerName;
        // echo "<pre>";print_r($item);echo "</pre>";
        $shopsId        = $item ['shop_id'];
        $shopsname      = $item ['shop_name'];
        $cityName       = $item ['city_name'];

        // echo "<pre>";print_r($item);echo "</pre>";
        System::queryStringSetVar('shop_id', $shopsId);
        System::queryStringSetVar('shopName', $shopsname);
        System::queryStringSetVar('city_name', $cityName);

        $id = $_REQUEST ['shop_id'];

        // $shop_id = !empty($_REQUEST['shop_id']) ? $_REQUEST['shop_id'] : $_REQUEST['id'];
        // echo $shop_id; exit;

        PageUtil::setVar('title', $this->__("Site")." - ".$shopsname);

        $this->view->assign('shop_id', $shop_id);

        $url = pnGetBaseURL().ModUtil::url('ZSELEX', 'user', 'site',
                array(
                'shop_id' => $shop_id
        ));
        $this->view->assign('url', $url);

        $loguser = UserUtil::getVar('uid');

        $shop    = $item;
        $perm    = '';
        /*
         * if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
         * $perm = '1';
         * }
         */
        $user_id = UserUtil::getVar('uid');
        $perm    = ModUtil::apiFunc('ZSELEX', 'admin', 'shopPermission',
                array(
                'shop_id' => $shop_id,
                'user_id' => $user_id
        ));
        System::queryStringSetVar('perm', $perm);

        $miniShopLinkStrt           = '';
        $miniShopLinkEnd            = '';
        $miniShopLinkStrtSuperAdmin = '';
        $miniShopLinkEndSuperAdmin  = '';

        $checkMiniShop = $this->entityManager->getRepository('ZSELEX_Entity_ServiceShop')->getServiceQuantity(array(
            'shop_id' => $shop_id,
            'type' => 'minishop'
        ));

        // echo "<pre>";print_r($checkMiniShop); echo "</pre>";

        $miniShopConfigured = $this->entityManager->getRepository('ZSELEX_Entity_MiniShop')->getminiShopConfigured(array(
            'shop_id' => $shop_id
        ));

        // echo "<pre>";print_r($miniShopConfigured); echo "</pre>";

        $configured = $miniShopConfigured ['configured'];

        if ($configured == 1) {
            $miniShopLinkStrtSuperAdmin = "<a href=".ModUtil::url('ZSELEX',
                    'user', 'shop',
                    array(
                    'shop_id' => $shop_id
                )).">";
            $miniShopLinkEndSuperAdmin  = "</a>";
        }

        if (!empty($checkMiniShop ['quantity'])) {
            $miniShopLinkStrt = "<a href=".ModUtil::url('ZSELEX', 'user',
                    'shop',
                    array(
                    'shop_id' => $shop_id
                )).">";
            $miniShopLinkEnd  = "</a>";
        }

        $pageServiceExist = ModUtil::apiFunc('ZSELEX', 'admin',
                'serviceExistBlock',
                array(
                'shop_id' => $shop_id,
                'type' => 'pages'
        ));
        if ($pageServiceExist) {
            $ztext_pages = $this->entityManager->getRepository('ZTEXT_Entity_Page')->getPagesInSite(array(
                'shop_id' => $shop_id
            ));
        } else {
            $ztext_pages = [];
        }
        $this->view->assign('ztext_pages', $ztext_pages);
        $page_setting = $this->entityManager->getRepository('ZTEXT_Entity_Page')->get(array(
            'entity' => 'ZTEXT_Entity_PageSetting',
            'fields' => array(
                'a.disable_frontend_image'
            ),
            'where' => array(
                'a.shop' => $shop_id
            )
        ));
        $this->view->assign('page_setting', $page_setting);

        $image_path = $this->shopProfileImage($args       = array(
            'shop_id' => $shop_id,
            'from' => $shop ['default_img_frm']
        ));
        $this->view->assign('perm', $perm);

        $this->view->assign('miniShopLinkStrt', $miniShopLinkStrt);
        $this->view->assign('miniShopLinkEnd', $miniShopLinkEnd);

        $this->view->assign('miniShopLinkStrtSuperAdmin',
            $miniShopLinkStrtSuperAdmin);
        $this->view->assign('miniShopLinkEndSuperAdmin',
            $miniShopLinkEndSuperAdmin);

        $this->view->assign('imageVal', $val);

        $this->view->assign('loguser', $loguser);
        $this->view->assign('shopitem', $shop);
        $shopImage = $image_path;
        $this->view->assign('shopImage', $shopImage);

        $this->view->assign('imagez', $imagez);
        // echo "comes here";
        $user_id = UserUtil::getVar('uid');
        $perm    = ModUtil::apiFunc('ZSELEX', 'admin', 'shopPermission',
                array(
                'shop_id' => $shop_id,
                'user_id' => $user_id
        ));
        System::queryStringSetVar('perm', $perm);
        return $this->view->fetch('user/shop.tpl');
    }

    public function shopProfileImage($args)
    {
        $ownerName  = $this->ownername;
        $shop_id    = $args ['shop_id'];
        $source     = $args ['from'];
        $image_path = '';
        if ($source == 'fromshop') {
            $get        = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                    $getargs    = array(
                    'table' => 'zselex_files',
                    'fields' => array(
                        'file_id',
                        'name'
                    ),
                    'where' => "shop_id=$shop_id AND defaultImg=1"
            ));
            //$image_path = pnGetBaseURL () . "zselexdata/$ownerName/minisiteimages/fullsize/" . $get ['name'];
            $image_path = pnGetBaseURL()."zselexdata/$shop_id/minisiteimages/fullsize/".$get ['name'];
        } elseif ($source == 'fromgallery') {
            $get        = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                    $getargs    = array(
                    'table' => 'zselex_shop_gallery',
                    'fields' => array(
                        'gallery_id',
                        'image_name'
                    ),
                    'where' => "shop_id=$shop_id AND defaultImg=1"
            ));
            //$image_path = pnGetBaseURL () . "zselexdata/$ownerName/minisitegallery/fullszie/" . $get ['image_name'];
            $image_path = pnGetBaseURL()."zselexdata/$shop_id/minisitegallery/fullszie/".$get ['image_name'];
        }
        return $image_path;
    }

    public function shopPageProducts($args)
    {

        // echo "Shop Id: " . $_REQUEST['shop_id'];
        // echo $_SESSION['linkservice'];
        if ($_REQUEST ['func'] == 'viewShopProducts') {
            return false;
        }

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            if ($_SESSION ['linkservice'] == 'no') {
                return false;
            }
        }

        $shop_id = $_REQUEST ['shop_id'];
        $loguser = UserUtil::getVar('uid');

        $items = array();
        $id    = (int) FormUtil::getPassedValue('id', '', 'REQUEST'); // normal shop id

        if (!empty($id)) {
            $shop_id = $id;
        } elseif (!empty($_REQUEST ['shop_id'])) {
            $shop_id = FormUtil::getPassedValue('shop_id', '', 'REQUEST'); // query string passed shop id
        } else {
            $shop_id = $_REQUEST ['shop_idnewItem']; // query string through newitem function
        }

        if (!empty($shop_id)) {
            // echo $shop_id;
            $id     = $shop_id;
            $fields = array(
                'shoptype_id'
            );
            $obj    = DBUtil::selectObjectByID('zselex_shop', $id, 'shop_id');

            // echo "<pre>"; print_r($obj); echo "</pre>";
            // echo $obj['shoptype_id'];

            if ($obj ['shoptype_id'] == '2') { // ISHOP
                $sql   = "SELECT p.* FROM zselex_products p , zselex_shop s where s.shop_id=$id AND s.shop_id=p.shop_id  ORDER BY RAND() limit 0,2";
                $items = ModUtil::apiFunc('ZSELEX', 'admin', 'execteQuery', $sql);
            } elseif ($obj ['shoptype_id'] == '1') { // ZENCART
                $items = ModUtil::apiFunc('ZSELEX', 'user',
                        'getZenCartProducts', $args  = $obj);
            }

            // echo "<pre>"; print_r($items); echo "</pre>";

            $this->view->assign('shoptype', $obj ['shoptype_id']);

            $this->view->assign('products', $items);
            // $this->view->assign('obj', $obj);
            return $this->view->fetch('user/shoppageproducts.tpl');
        }
    }

    public function viewShopProducts($args)
    {
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN) && !SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD) && !SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT)) { // normal users
            // echo "comes here";
            $serviceExist = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray',
                    $args         = array(
                    'table' => ' zselex_serviceshop',
                    'where' => array(
                        "shop_id=$shop_id",
                        "type='linktoshop' OR type='minishop'"
                    )
            ));

            if (count($serviceExist) < 1) {
                $_SESSION ['linkservice'] = 'no';
                return LogUtil::registerError($this->__('Error! Sorry, no page has been configured for this site.'));
            } else {
                $_SESSION ['linkservice'] = 'yes';
            }
        } elseif (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)
            && SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
            // shop owners
            $serviceExist = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow',
                    $args         = array(
                    'table' => ' zselex_shop',
                    'where' => array(
                        "shop_id=$shop_id",
                        "user_id='".UserUtil::getVar('uid')."'"
                    )
            ));
            if ($serviceExist ['shop_id'] < 1) {
                $_SESSION ['linkservice'] = 'no';
                return LogUtil::registerError($this->__('Error! Sorry, no page has been configured for this site.'));
            } else {
                $_SESSION ['linkservice'] = 'yes';
            }
        } elseif (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)
            && !SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD) && SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT)) {
            // shop admins
            $serviceExist = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow',
                    $args         = array(
                    'table' => ' zselex_shop a , zselex_parent b',
                    'where' => array(
                        "a.shop_id=$shop_id",
                        "b.parentId='".UserUtil::getVar('uid')."'",
                        "b.cr_uid=a.user_id",
                        "b.childType='SHOP'",
                        "b.parentType='SHOPADMIN'"
                    )
            ));
            if ($serviceExist ['shop_id'] < 1) {
                $_SESSION ['linkservice'] = 'no';
                return LogUtil::registerError($this->__('Error! Sorry, no page has been configured for this site.'));
            } else {
                $_SESSION ['linkservice'] = 'yes';
            }
        }
        // echo "comes here";

        $getShopType = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow',
                array(
                'table' => 'zselex_shop_types a , zselex_shop b',
                'where' => array(
                    "b.shop_id=$shop_id",
                    "b.shoptype_id=a.shoptype_id"
                ),
                'fields' => array(
                    'a.shopTypeName'
                )
        ));

        $shopType = $getShopType ['shopTypeName'];

        $items = array();
        if ($shopType == 'iSHOP') {
            $items = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray',
                    array(
                    'table' => 'zselex_products a , zselex_shop b',
                    'where' => array(
                        "a.shop_id=$shop_id",
                        "a.shop_id=b.shop_id"
                    )
            ));
        } elseif ($shopType == 'zSHOP') {

            // echo $shop_id;
            // echo "comes here";

            $obj = DBUtil::selectObjectByID('zselex_shop', $shop_id, 'shop_id');

            $items = ModUtil::apiFunc('ZSELEX', 'user',
                    'getZenCartProductsMiniShop', $args  = $obj);
        }

        // echo "<pre>"; print_r($items); echo "</pre>";

        $this->view->assign('shoptype', $shopType);
        $this->view->assign('products', $items);
        return $this->view->fetch('user/shopproducts.tpl');
    }

    function redirectToNetsReturn()
    {
        if ($_SESSION ['netaxept'] ['transactionId'] != '') {
            // echo "first"; exit;
            return $this->redirect(ModUtil::url('ZSELEX', 'user', 'netsReturn',
                        array(
                        'responseCode' => $_REQUEST ['responseCode'],
                        'transactionId' => $_REQUEST ['transactionId'],
                        'orderId' => $_REQUEST ['orderId'],
                        'cart_shop_id' => $_REQUEST ['cart_shop_id']
            )));
        }
    }

    /**
     * Minishop page
     *
     * @param string shoptitle
     * @param int shop_id
     * @return html
     */
    public function shop($args)
    {
        //header("Cache-Control: max-age=300, must-revalidate");

        $shopTitle = FormUtil::getPassedValue('shoptitle', null, 'REQUEST');
        $shopId    = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        // echo "searchWord : " . $searchWord;

        if (!$shopId && !$shopTitle) {
            // echo "comes here"; exit;
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }

        $shopArgs = array(
            'shop_id' => $shopId,
            'title' => $shopTitle
        );
        $getShop  = ModUtil::apiFunc('ZSELEX', 'shop', 'getShopDetail',
                $shopArgs);

        if (!$getShop) {
            return LogUtil::registerError($this->__('Error! Site not found.'),
                    404);
        }

        $shop_id = $getShop ['shop_id'];

        System::queryStringSetVar('shop_id', $getShop ['shop_id']);
        System::queryStringSetVar('shop_name', $getShop ['shop_name']);
        System::queryStringSetVar('shopName', $getShop ['shop_name']);
        System::queryStringSetVar('city_name', $getShop ['city_name']);
        $user_id = UserUtil::getVar('uid');
        // Permission check
        $perm    = ModUtil::apiFunc('ZSELEX', 'admin', 'shopPermission',
                array(
                'shop_id' => $shop_id,
                'user_id' => $user_id
        ));
        System::queryStringSetVar('perm', $perm);

        //Payment gateway retuen url
        if (isset($_REQUEST ['gateway']) && $_REQUEST ['gateway'] != '') {
            $requestBody = file_get_contents("php://input");
            $this->paymentGatewayReturn($_REQUEST, $requestBody);
        }

        $loguser = $user_id;
        $loguser = !empty($loguser) ? $loguser : 0;
        $user_id = $loguser;

        $serviceExist = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceExistBlock',
                $args         = array(
                'shop_id' => $shop_id,
                'type' => 'minishop'
        ));

        if ($serviceExist < 1) {
            return LogUtil::registerError($this->__('Error! Sorry, no minishop has been configured.'));
        }

        $this->view->assign('shop_id', $shop_id);
        setcookie("last_shop_id", $shop_id, time() + (86400 * 7), '/');
        $itemsperpage   = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 24,
                'GETPOST');
        $startnum       = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : 0, 'GETPOST');
        $prod_catId     = FormUtil::getPassedValue('prod_category', array(),
                'REQUEST');
        $mnfr_id        = FormUtil::getPassedValue('prod_mnfr', array(),
                'REQUEST');
        $prod_categorys = FormUtil::getPassedValue('prod_categorys', null,
                'REQUEST');
        $mnfrIds        = FormUtil::getPassedValue('mnfrIds', null, 'REQUEST');

        $catExtra = array();
        if (!empty($prod_categorys)) {
            $catExtra = explode(',', $prod_categorys);
        }

        $mnfExtra = array();
        if (!empty($mnfrIds)) {
            $mnfExtra = explode(',', $mnfrIds);
        }

        $catArr = array();

        $_SESSION ['cats'] = $prod_catId;

        $cats1 = array();

        if (!empty($_SESSION ['cats'])) {
            $cats1 = $_SESSION ['cats'];
        }

        $cats = array_merge($cats1, $catExtra);
        if (!empty($cats)) {
            $cats = array_unique($cats);
        }
        $prod_catIdsArr = $cats;
        if (!empty($prod_catIdsArr)) {
            $prod_catIds = implode(',', $prod_catIdsArr);
        }
        $_POST ['prod_categorys'] = $prod_catIds;

        $_SESSION ['manf'] = $mnfr_id;

        $manf = array();
        if (!empty($_SESSION ['manf'])) {
            $manf = $_SESSION ['manf'];
        }


        $manf = array_merge($manf, $mnfExtra);

        if (!empty($manf)) {
            $manf = array_unique($manf);
        }
        $manfIdsArr = $manf;
        if (!empty($manfIdsArr)) {
            $manfIds = implode(',', $manfIdsArr);
        }

        $_POST ['mnfrIds'] = $manfIds;
        if (isset($_POST ['submit_mnfr']) || isset($_POST ['submit_category'])) {
            $startnum = 0;
        }

        $this->view->assign('startnum', $startnum);
        $this->view->assign('itemsperpage', $itemsperpage);
        $this->view->assign('prod_catId', $prod_catId);
        $this->view->assign('mnfr_id', $mnfr_id);
        $this->view->assign('prod_catIds', $prod_catIds);
        $this->view->assign('manfIds', $manfIds);
        $this->view->assign('prod_catIdsArr', $prod_catIdsArr);

        $this->view->assign('manfIdsArr', $manfIdsArr);
        if ($startnum > 0) {
            $startnum = $startnum - 1;
        } else {
            $startnum = $startnum;
        }
        $limit = "LIMIT $startnum , $itemsperpage";

        $getArgs = array(
            'entity' => 'ZSELEX_Entity_MiniShop',
            'fields' => array(
                'b.shop_name',
                'a.shoptype'
            ),
            'joins' => array(
                'INNER JOIN a.shop b'
            ),
            'where' => array(
                'a.shop' => $shop_id
            )
        );


        $shopInfo = $this->entityManager->getRepository('ZSELEX_Entity_MiniShop')->get($getArgs);
        $shopType = $shopInfo ['shoptype'];

        PageUtil::setVar('title',
            $this->__("Minishop")." - ".$shopInfo ['shop_name']);

        $products = array();
        if ($shopType == 'iSHOP') {
            $cat_qry  = '';
            $mnfr_qry = '';

            if (!empty($prod_catIdsArr)) {
                $cat_qry = " AND p.product_id IN(SELECT product_id FROM zselex_product_to_category WHERE prd_cat_id IN($prod_catIds)) ";
            }
            if (!empty($manfIdsArr)) {
                $mnfr_qry = " AND p.manufacturer_id IN($manfIds) ";
            }

            $service = $this->entityManager->getRepository('ZSELEX_Entity_Product')->get(array(
                'entity' => 'ZSELEX_Entity_ServiceShop',
                'fields' => array(
                    'a.quantity'
                ),
                'where' => array(
                    'a.shop' => $shop_id,
                    'a.type' => 'addproducts'
                )
            ));

            $serviceQuantity = $service ['quantity'];
            $total_count     = $this->entityManager->getRepository('ZSELEX_Entity_Product')->getMinishopProductsCount(array(
                'shop_id' => $shop_id,
                'cat_qry' => $cat_qry,
                'mnfr_qry' => $mnfr_qry,
            ));
            $qtyLeft         = $serviceQuantity - $total_count;
            if ($qtyLeft < $total_count) {
                $total_count = $serviceQuantity;
            }
            if ($serviceQuantity < $itemsperpage) {
                //$itemsperpage = $serviceQuantity;
            }
            $products   = $this->entityManager->getRepository('ZSELEX_Entity_Product')->getMinishopProducts(array(
                'shop_id' => $shop_id,
                'cat_qry' => $cat_qry,
                'mnfr_qry' => $mnfr_qry,
                'startnum' => $startnum,
                'itemsperpage' => $itemsperpage
            ));
            $startnums  = $startnum + 1;
            $tillShown  = $startnums - 1;
            $itemCount  = count($products);
            $totalShown = $tillShown + $itemCount;

            if ($totalShown > $total_count) {
                $minus         = $totalShown - $total_count;
                $totalThisPage = $itemCount - $minus;
                for ($c = 0; $c < $totalThisPage; $c++) {
                    $newItems[] = $products[$c];
                }

                $products = $newItems;
            }
        } elseif ($shopType == 'zSHOP') {

            $obj = $this->entityManager->getRepository('ZSELEX_Entity_Product')->get(array(
                'entity' => 'ZSELEX_Entity_ZenShop',
                'where' => array(
                    'a.shop' => $shop_id
                )
            ));

            $this->view->assign('zShopDomain', $obj ['domain']);
            $this->view->assign('zShopImgPath',
                'http://'.$obj ['domain'].'/images');
            $products    = ModUtil::apiFunc('ZSELEX', 'admin',
                    'getZenCartProducts',
                    $args        = array(
                    'shop' => $obj,
                    'limit' => $limit
            ));
            $total_count = ModUtil::apiFunc('ZSELEX', 'admin',
                    'getZenCartProductsCount',
                    $args        = array(
                    'shop_id' => $shop_id,
                    'sql' => $sql,
                    'shop' => $obj
            ));
            if ($products == 'error') {
                return $this->redirect(ModUtil::url('ZSELEX', 'user', 'errorss'));
            }
        }

        if ((!$products) && (!$perm)) {
            // return;
            // return $products;
        }
        $this->view->assign('total_count', $total_count);

        $owner_id      = $this->owner_id;
        $itmeargs      = array(
            'table' => 'zselex_product_categories',
            'where' => "shop_id=$shop_id",
            // 'orderBy' => 'service_name ASC',
            'useJoins' => ''
        );
        $categories    = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $itmeargs);
        $manufacturers = $this->entityManager->getRepository('ZSELEX_Entity_Manufacturer')->getManufacturers(array(
            'shop_id' => $shop_id
        ));
        $shop_detail   = $this->entityManager->getRepository('ZSELEX_Entity_ShopDetail')->getShopDetails(array(
            'shop_id' => $shop_id
        ));
        $no_payment    = $shop_detail ['no_payment'];
        $this->view->assign('no_payment', $no_payment);

        $count    = count($products);
        $loggedIn = UserUtil::isLoggedIn();
        $this->view->assign('perm', $perm);
        $this->view->assign('ownerName', $ownerName);
        $this->view->assign('shoptype', $shopType);
        $this->view->assign('shopinfo', $shopInfo);
        $this->view->assign('count', $count);
        $this->view->assign('categories', $categories);
        $this->view->assign('manufacturers', $manufacturers);
        $this->view->assign('products', $products);
        $this->view->assign('allproducts', $allproducts);
        $this->view->assign('loggedIn', $loggedIn);

        return $this->view->fetch('user/minishop.tpl');
    }

    /**
     * Payment gateway return url
     *
     * @param array $args
     * @param array $requestBody
     * @return redirect
     */
    function paymentGatewayReturn($args, $requestBody)
    {
        /*
          $mailContent = json_encode($args).'<br><br>'.$requestBody;
          mail('sharazkhanz@gmail.com', 'QuickPayCallback', $mailContent);
         */

        $gateway = $args['gateway'];

        if ($gateway == 'nets') {
            return $this->redirect(ModUtil::url('ZSELEX', 'user', 'netsReturn',
                        array(
                        'responseCode' => $args ['responseCode'],
                        'transactionId' => $args ['transactionId'],
                        'orderId' => $args ['orderId'],
                        'source' => 'minishop'
            )));
        } elseif ($gateway == 'quickpay') {
            $requestArray = json_decode($requestBody, true);

            if ($args ['url_type'] == 'continue') {
                return $this->redirect(ModUtil::url('ZSELEX', 'user',
                            'QuickPayOk',
                            array(
                            'order_id' => $args ['order_id'],
                )));
            } elseif ($args ['url_type'] == 'callback') {
                $order_id = $args ['order_id'];
                $txnId    = $args ['txn_id'];

                if ($requestArray['operations'][0]['qp_status_code'] == '20000') {
                    $payment_status = "Success";
                } else {
                    $payment_status = "Failed";
                }

                $errorArgs   = array('request_array' => $requestArray, 'url_values' => $args);
                $notifyError = ModUtil::apiFunc('ZPayment', 'QuickPay',
                        'sendErrorNotification', $errorArgs);
                $upd_args    = array(
                    'entity' => 'ZSELEX_Entity_Order',
                    'fields' => array(
                        'status' => $payment_status
                    ),
                    'where' => array(
                        'a.order_id' => $order_id
                    )
                );

                $updateOrderId   = $this->entityManager->getRepository('ZSELEX_Entity_Order')->updateEntity($upd_args);
                $update_quickpay = $this->entityManager->getRepository('ZPayment_Entity_QuickPay')->updateQuickPayPayment($status
                    = $payment_status, $order_id, $txnId,
                    $info            = $requestArray['operations'][0]['qp_status_msg'],
                    $cardtype        = $requestArray['metadata']['brand']);
            }
        }
    }

    public function minishop1($args)
    {
        // return;
        // echo "<pre>"; print_r($_REQUEST); echo "</pre>";
        $shop_id = $_REQUEST ['shop_id'];
        if (!is_numeric($shop_id)) {
            // echo "comes here";
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }

        $loguser      = UserUtil::getVar('uid');
        $loguser      = !empty($loguser) ? $loguser : 0;
        $user_id      = $loguser;
        $serviceExist = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceExistBlock',
                $args         = array(
                'shop_id' => $shop_id,
                'type' => 'minishop'
        ));

        if ($serviceExist < 1) {
            // echo "comes hereee";
            return LogUtil::registerError($this->__('Error! Sorry, no minishop has been configured.'));
        }

        $this->view->assign('shop_id', $shop_id);

        setcookie("last_shop_id", $shop_id, time() + (86400 * 7), '/');

        // $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner', $args = array('shop_id' => $shop_id));
        // $perm = ModUtil::apiFunc('ZSELEX', 'admin', 'shopPermission', array('shop_id' => $shop_id, 'user_id' => $user_id));
        $perm         = $_REQUEST ['perm'];
        $itemsperpage = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 24,
                'GETPOST');
        $startnum     = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : 0, 'GETPOST');
        $prod_catId   = FormUtil::getPassedValue('prod_category',
                isset($args ['prod_category']) ? $args ['prod_category'] : 0,
                'REQUEST');
        $mnfr_id      = FormUtil::getPassedValue('prod_mnfr',
                isset($args ['prod_mnfr']) ? $args ['prod_mnfr'] : 0, 'REQUEST');
        // $prod_categorys = FormUtil::getPassedValue('prod_categorys', null, 'REQUEST');
        // unset($_SESSION['cats']);
        // echo "<pre>"; print_r($prod_catId); echo "</pre>";
        // $prod_categories = $prod_categorys;
        if (!empty($prod_catId)) {
            // foreach (@$prod_catId as $v) {
            // $_SESSION['cats'][] = $v;
            $_SESSION ['cats'] = $prod_catId;
            // }
            // $prod_catIds = implode(',', $prod_catId);
        }
        if (empty($prod_catId) && count($_SESSION ['cats']) == 1) {
            // foreach (@$prod_catId as $v) {
            // $_SESSION['cats'][] = $v;
            $_SESSION ['cats'] = array();
            // }
            // $prod_catIds = implode(',', $prod_catId);
        }

        $cats = $_SESSION ['cats'];
        if (!empty($cats)) {
            $cats = array_unique($cats);
        }
        $prod_catIdsArr = $cats;
        if (!empty($prod_catIdsArr)) {
            $prod_catIds = implode(',', $prod_catIdsArr);
        }

        if (!empty($mnfr_id)) {
            // foreach (@$prod_catId as $v) {
            // $_SESSION['cats'][] = $v;
            $_SESSION ['manf'] = $mnfr_id;
            // }
            // $prod_catIds = implode(',', $prod_catId);
        }
        if (empty($mnfr_id) && count($_SESSION ['manf']) == 1) {
            // foreach (@$prod_catId as $v) {
            // $_SESSION['cats'][] = $v;
            $_SESSION ['manf'] = array();
            // }
            // $prod_catIds = implode(',', $prod_catId);
        }

        $manf = $_SESSION ['manf'];
        if (!empty($manf)) {
            $manf = array_unique($manf);
        }
        $manfIdsArr = $manf;
        if (!empty($manfIdsArr)) {
            $manfIds = implode(',', $manfIdsArr);
        }
        // echo "<pre>"; print_r($prod_catIdsArr); echo "</pre>";
        // echo "prodID : " . $prod_catId;
        // echo "prod_mnfr : " . $mnfr_id;
        // echo "prod_categorys : " . $prod_categorys;
        // echo "prod_catId : " . $prod_catIds;
        if (isset($_POST ['submit_mnfr']) || isset($_POST ['submit_category'])) {
            // echo "manufacturer";
            $startnum = 0;
        }
        // echo "startnum : " . $startnum;
        $this->view->assign('startnum', $startnum);
        $this->view->assign('itemsperpage', $itemsperpage);
        $this->view->assign('prod_catId', $prod_catId);
        $this->view->assign('mnfr_id', $mnfr_id);
        $this->view->assign('prod_catIds', $prod_catIds);
        // $prod_catIdsArr = explode(',', $prod_catIds);
        $this->view->assign('prod_catIdsArr', $prod_catIdsArr);

        $this->view->assign('manfIdsArr', $manfIdsArr);
        /*
         * if ($startnum > 0) {
         * $startnum = $startnum - 1;
         * } else {
         * $startnum = $startnum;
         * }
         */
        $limit = "LIMIT $startnum , $itemsperpage";

        $getArgs  = array(
            'entity' => 'ZSELEX_Entity_MiniShop',
            'fields' => array(
                'b.shop_name',
                'a.shoptype'
            ),
            'joins' => array(
                'INNER JOIN a.shop b'
            ),
            'where' => array(
                'a.shop' => $shop_id
            )
        );
        $shopInfo = $this->entityManager->getRepository('ZSELEX_Entity_MiniShop')->get($getArgs);
        $shopType = $shopInfo ['shoptype'];
        // echo "<pre>"; print_r($get_prod_qty); echo "</pre>";
        // echo $shopType;

        PageUtil::setVar('title',
            $this->__("Minishop")." - ".$shopInfo ['shop_name']);
        // echo $shopType; exit;
        // echo "<pre>"; print_r($type); echo "</pre>";
        $products = array();
        if ($shopType == 'iSHOP') {
            $cat_qry  = '';
            $mnfr_qry = '';
            // if ($prod_catId > 0) {
            if (!empty($prod_catIdsArr)) {
                // $cat_qry = " AND category_id=$prod_catId ";
                $cat_qry = " AND p.product_id IN(SELECT product_id FROM zselex_product_to_category WHERE prd_cat_id IN($prod_catIds)) ";
            }
            if (!empty($manfIdsArr)) {
                $mnfr_qry = " AND p.manufacturer_id IN($manfIds) ";
            }
            /*
             * $args = array('table' => 'zselex_products',
             * "where" => array("shop_id=$shop_id", "prd_status=1"),
             * 'limit' => $limit);
             * $products = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray', $args);
             */

            /*
             * $products = ModUtil::apiFunc('ZSELEX', 'user', 'getAll', $args = array(
             * 'table' => 'zselex_products',
             * 'where' => "product_id!='' AND prd_status=1 AND shop_id=$shop_id" . $cat_qry . $mnfr_qry,
             * 'startnum' => $startnum,
             * 'itemsperpage' => $itemsperpage,
             * // 'orderby' => $orberby
             * ));
             */

            $products = $this->entityManager->getRepository('ZSELEX_Entity_Product')->getMinishopProducts(array(
                'shop_id' => $shop_id,
                'cat_qry' => $cat_qry,
                'mnfr_qry' => $mnfr_qry,
                'startnum' => $startnum,
                'itemsperpage' => $itemsperpage
            ));
            // echo "<pre>"; print_r($products); echo "</pre>";
            /*
             * foreach ($products as $key => $val) {
             * $imagepath = pnGetBaseURL() . 'zselexdata/' . $ownerName . '/products/thumb/' . str_replace(" ", "%20", $val['prd_image']);
             * $img_args = array(
             * 'imagepath' => $imagepath,
             * 'setheight' => '210',
             * 'setwidth' => '170'
             * );
             * $new_resize = ModUtil::apiFunc('ZSELEX', 'admin', 'imageProportional', $img_args);
             * $products[$key]['H'] = $new_resize['new_height'];
             * $products[$key]['W'] = $new_resize['new_width'];
             * }
             */
            // echo "<pre>"; print_r($products); echo "</pre>";

            /*
             * $where_count = "prd_status=1 AND shop_id=$shop_id" . $cat_qry . $mnfr_qry;
             * $total_count = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $args = array(
             * 'table' => 'zselex_products',
             * 'where' => $where_count));
             */

            $total_count = $this->entityManager->getRepository('ZSELEX_Entity_Product')->getMinishopProductsCount(array(
                'shop_id' => $shop_id,
                'cat_qry' => $cat_qry,
                'mnfr_qry' => $mnfr_qry
            ));
            // echo $total_count;
        } elseif ($shopType == 'zSHOP') {
            // echo "comes here";
            // $obj = DBUtil::selectObjectByID('zselex_zenshop', $shop_id, 'shop_id');
            $obj         = $this->entityManager->getRepository('ZSELEX_Entity_Product')->get(array(
                'entity' => 'ZSELEX_Entity_ZenShop',
                'where' => array(
                    'a.shop' => $shop_id
                )
            ));
            // echo "<pre>"; print_r($obj); echo "</pre>";
            $this->view->assign('zShopDomain', $obj ['domain']);
            $this->view->assign('zShopImgPath',
                'http://'.$obj ['domain'].'/images');
            $products    = ModUtil::apiFunc('ZSELEX', 'admin',
                    'getZenCartProducts',
                    $args        = array(
                    'shop' => $obj,
                    'limit' => $limit
            ));
            $total_count = ModUtil::apiFunc('ZSELEX', 'admin',
                    'getZenCartProductsCount',
                    $args        = array(
                    'shop_id' => $shop_id,
                    'sql' => $sql,
                    'shop' => $obj
            ));

            // echo "products : " . $products . '<br>'; exit;
            if ($products == 'error') {
                return $this->redirect(ModUtil::url('ZSELEX', 'user', 'errorss'));
            }
        }

        if ((!$products) && (!$perm)) {
            // return;
            // return $products;
        }
        $this->view->assign('total_count', $total_count);

        $owner_id   = $this->owner_id;
        $itmeargs   = array(
            'table' => 'zselex_product_categories',
            'where' => "shop_id=$shop_id",
            // 'orderBy' => 'service_name ASC',
            'useJoins' => ''
        );
        $categories = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $itmeargs);

        /*
         * $catArgs = array(
         * 'entity' => 'ZSELEX_Entity_ProductCategory',
         * 'fields' => array('a.prd_cat_id', 'a.prd_cat_name'),
         * 'where' => array('a.shop' => $shop_id)
         * );
         * $categories = $this->entityManager->getRepository('ZSELEX_Entity_ProductCategory')->get($catArgs);
         */
        // echo "<pre>"; print_r($categories); echo "</pre>";
        $manufacturers = $this->entityManager->getRepository('ZSELEX_Entity_Manufacturer')->getManufacturers(array(
            'shop_id' => $shop_id
        ));

        // echo "<pre>"; print_r($manufacturers); echo "</pre>";
        $shop_detail = $this->entityManager->getRepository('ZSELEX_Entity_ShopDetail')->getShopDetails(array(
            'shop_id' => $shop_id
        ));
        $no_payment  = $shop_detail ['no_payment'];
        $this->view->assign('no_payment', $no_payment);

        // echo "<pre>"; print_r($products); echo "</pre>";

        $count    = count($products);
        $loggedIn = UserUtil::isLoggedIn();
        $this->view->assign('perm', $perm);
        $this->view->assign('ownerName', $ownerName);
        $this->view->assign('shoptype', $shopType);
        $this->view->assign('shopinfo', $shopInfo);
        $this->view->assign('count', $count);
        $this->view->assign('categories', $categories);
        $this->view->assign('manufacturers', $manufacturers);
        $this->view->assign('products', $products);
        $this->view->assign('allproducts', $allproducts);
        $this->view->assign('loggedIn', $loggedIn);

        return $this->view->fetch('user/minishop.tpl');
    }

    public function shopServicesMenu($args)
    {
        if ($_REQUEST ['func'] == 'viewShopProducts') {
            return false;
        }

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            if ($_SESSION ['linkservice'] == 'no') {
                return false;
            }
        }

        $shop_id = !empty($_REQUEST ['shop_id']) ? FormUtil::getPassedValue('shop_id',
                '', 'REQUEST') : $_REQUEST ['shop_idnewItem'];
        $user_id = UserUtil::getVar('uid');

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
            return false;
        }
        $services = ModUtil::apiFunc('ZSELEX', 'user', 'shopServicesMenu',
                $args     = array(
                'user_id' => $user_id,
                'shop_id' => $shop_id
        ));
        // echo "<pre>"; print_r($services); echo "</pre>";
        $this->view->assign('services', $services);

        return $this->view->fetch('user/servicemenu.tpl');
    }

    public function otherShops()
    {
        // echo "TESTTTTT";
        $user_id = UserUtil::getVar('uid');
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
            return false;
        }
        $shop_id    = $_REQUEST ['shop_id'];
        $otherShops = ModUtil::apiFunc('ZSELEX', 'user', 'selectArrays',
                $args       = array(
                'table' => 'zselex_shop',
                'where' => array(
                    "user_id=$user_id",
                    "shop_id!=$shop_id"
                )
        ));
        $count      = count($otherShops);
        if ($count > 1) {
            $this->view->assign('shops', $otherShops);
            return $this->view->fetch('user/othershops.tpl');
        }
    }

    public function newitems($args)
    {
        $shop_id = FormUtil::getPassedValue('shop_id', '', 'REQUEST');
        $this->redirect(ModUtil::url('ZSELEX', 'user', 'newitem'));
    }

    public function serviceDisabled($type)
    {
        return ModUtil::apiFunc('ZSELEX', 'admin', 'serviceDisabled', $type);
    }

    public function shopPermission($shop_id)
    {
        $shop_id = $shop_id;
        $user_id = UserUtil::getVar('uid');
        $perm    = ModUtil::apiFunc('ZSELEX', 'admin', 'shopPermission',
                array(
                'shop_id' => $shop_id,
                'user_id' => $user_id
        ));
        return $perm;
    }

    public function serviceCheck($args)
    {
        $shop_id = $shop_id;
        $user_id = UserUtil::getVar('uid');
        $perm    = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceCheck', $args);
        return $perm;
    }

    /**
     * News module user controller override class function
     * Also used directly
     */
    public function newitem($args)
    {

        // News_Controller_User::check();
        // News_Controller_User::preview($args);
        // echo "<pre>"; print_r($_SESSION); echo "</pre>";
        // echo "New Item"; exit;
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('News::',
                '::', ACCESS_READ), LogUtil::getErrorMsgPermission());

        $shop_id = (int) FormUtil::getPassedValue('shop_id', '', 'REQUEST');

        $shpName = FormUtil::getPassedValue('shopName', '', 'REQUEST');

        // echo $_REQUEST['shop_idnewItem'];
        // echo "comes here";
        // echo "shop id :" . $shop_id;
        if ($shop_id == false && $shpName == false) {
            return LogUtil::registerError($this->__('Error! Unable to load page for this Minisite.'));
        }

        if (!empty($shop_id)) {
            $shopitem = ModUtil::apiFunc('ZSELEX', 'user', 'getSingle',
                    $arg      = array(
                    'table' => 'zselex_shop',
                    'idName' => 'shop_id',
                    'id' => $shop_id
            ));
            $shopName = $shopitem ['shop_name'];
        } else {
            $shopName = $shpName;
        }

        $user_id = UserUtil::getVar('uid');
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        /*
         *
         * if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
         *
         * //echo "shop id :" . $shop_id;
         * // $servicecheck = ModUtil::apiFunc('ZSELEX', 'user', 'createArticlesExist', $arg = array('shop_id' => $_REQUEST['shop_idnewItem'], 'user_id' => $user_id));
         * $serviceargs = array('shop_id' => $_REQUEST['shop_id'], 'user_id' => $user_id);
         * $servicePermission = $this->serviceCheck($serviceargs);
         * if ($servicePermission < 1) {
         * //return LogUtil::registerError($this->__('The service you try to use has to be purchased first.'));
         * $template = 'create_noservice.tpl';
         * }
         * }
         *
         */

        $servicecount   = 0;
        $error          = 0;
        $message        = '';
        $servicedisable = false;
        $dom            = ZLanguage::getModuleDomain('ZSELEX');

        $admin    = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN);
        $this->view->assign('admin', $admin);
        $template = 'create.tpl';

        $serviceargs       = array(
            'shop_id' => $shop_id,
            'user_id' => $user_id,
            'type' => 'createarticle'
        );
        $servicePermission = $this->serviceCheck($serviceargs);

        $serviceDisabled = $this->serviceDisabled('createarticle');

        if ($servicePermission < 1) {

            // return LogUtil::registerError($this->__('The service you try to use has to be purchased first.'));
            $message .= $this->__f('The service you try to use has to be purchased first.',
                    $this->__($msg, $dom), $dom)."\n";

            $error ++;
        }
        if ($this->serviceDisabled('createarticle') < 1) {
            // $template = 'createdisabled.tpl';
            if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $servicedisable = true;
                $error ++;
                $disable        = "disabled";
            }
            $message .= $this->__f('This service has been temporarily disabled!',
                    $this->__($msg, $dom), $dom)."\n";
        }
        LogUtil::registerError(nl2br($message));

        $this->view->assign('servicecount', $servicecount);
        $this->view->assign('servicedisable', $servicedisable);
        $this->view->assign('serviceerror', $error);
        $this->view->assign('disable', $disable);
        $this->view->assign('message', $message);

        // echo "count :". $servicecheck;
        // echo $shop_id; exit;
        // Any item set for preview will be stored in a session var
        // Once the new article is posted we'll clear the session var.
        $item      = array();
        $sess_item = SessionUtil::getVar('newsitem');

        // get the type parameter so we can decide what template to use
        $type = FormUtil::getPassedValue('type', 'user', 'REQUEST');

        // Set the default values for the form. If not previewing an item prior
        // to submission these values will be null but do need to be set
        $item ['sid']                 = isset($sess_item ['sid']) ? $sess_item ['sid']
                : '';
        $item ['__CATEGORIES__']      = isset($sess_item ['__CATEGORIES__']) ? $sess_item ['__CATEGORIES__']
                : array();
        $item ['__ATTRIBUTES__']      = isset($sess_item ['__ATTRIBUTES__']) ? $sess_item ['__ATTRIBUTES__']
                : array();
        $item ['title']               = isset($sess_item ['title']) ? $sess_item ['title']
                : '';
        $item ['urltitle']            = isset($sess_item ['urltitle']) ? $sess_item ['urltitle']
                : '';
        $item ['hometext']            = isset($sess_item ['hometext']) ? $sess_item ['hometext']
                : '';
        $item ['hometextcontenttype'] = isset($sess_item ['hometextcontenttype'])
                ? $sess_item ['hometextcontenttype'] : '';
        $item ['bodytext']            = isset($sess_item ['bodytext']) ? $sess_item ['bodytext']
                : '';
        $item ['bodytextcontenttype'] = isset($sess_item ['bodytextcontenttype'])
                ? $sess_item ['bodytextcontenttype'] : '';
        $item ['notes']               = isset($sess_item ['notes']) ? $sess_item ['notes']
                : '';
        $item ['displayonindex']      = isset($sess_item ['displayonindex']) ? $sess_item ['displayonindex']
                : 1;
        $item ['language']            = isset($sess_item ['language']) ? $sess_item ['language']
                : '';
        $item ['allowcomments']       = isset($sess_item ['allowcomments']) ? $sess_item ['allowcomments']
                : 1;
        $item ['from']                = isset($sess_item ['from']) ? $sess_item ['from']
                : DateUtil::getDatetime(null, '%Y-%m-%d %H:%M');
        $item ['to']                  = isset($sess_item ['to']) ? $sess_item ['to']
                : DateUtil::getDatetime(null, '%Y-%m-%d %H:%M');
        $item ['tonolimit']           = isset($sess_item ['tonolimit']) ? $sess_item ['tonolimit']
                : 1;
        $item ['unlimited']           = isset($sess_item ['unlimited']) ? $sess_item ['unlimited']
                : 1;
        $item ['weight']              = isset($sess_item ['weight']) ? $sess_item ['weight']
                : 0;
        $item ['pictures']            = isset($sess_item ['pictures']) ? $sess_item ['pictures']
                : 0;
        $item ['tempfiles']           = isset($sess_item ['tempfiles']) ? $sess_item ['tempfiles']
                : null;
        $item ['temp_pictures']       = isset($sess_item ['tempfiles']) ? unserialize($sess_item ['tempfiles'])
                : null;

        $preview = '';
        if (isset($sess_item ['action']) && $sess_item ['action'] == self::ACTION_PREVIEW) {
            $preview = $this->preview(array(
                'title' => $item ['title'],
                'hometext' => $item ['hometext'],
                'hometextcontenttype' => $item ['hometextcontenttype'],
                'bodytext' => $item ['bodytext'],
                'bodytextcontenttype' => $item ['bodytextcontenttype'],
                'notes' => $item ['notes'],
                'sid' => $item ['sid'],
                'pictures' => $item ['pictures'],
                'temp_pictures' => $item ['temp_pictures']
            ));
        }

        // Get the module vars
        $modvars = ModUtil::getVar('News');

        if ($modvars ['enablecategorization']) {
            $catregistry = CategoryRegistryUtil::getRegisteredModuleCategories('News',
                    'news');
            $this->view->assign('catregistry', $catregistry);

            // add article attribute if morearticles is enabled and general setting is zero
            if ($modvars ['enablemorearticlesincat'] && $modvars ['morearticlesincat']
                == 0) {
                $item ['__ATTRIBUTES__'] ['morearticlesincat'] = 0;
            }
        }

        // Assign the default languagecode
        $this->view->assign('lang', ZLanguage::getLanguageCode());

        // Assign the item to the template
        $this->view->assign('item', $item);

        // Assign the content format
        $formattedcontent = ModUtil::apiFunc('News', 'user', 'isformatted',
                array(
                'func' => 'new'
        ));
        $this->view->assign('formattedcontent', $formattedcontent);

        $this->view->assign('accessadd', 0);
        if (SecurityUtil::checkPermission('News::', '::', ACCESS_ADD)) {
            $this->view->assign('accessadd', 1);
            $this->view->assign('accesspicupload', 1);
            $this->view->assign('accesspubdetails', 1);
        } else {
            $this->view->assign('accesspicupload',
                SecurityUtil::checkPermission('News:pictureupload:', '::',
                    ACCESS_ADD));
            $this->view->assign('accesspubdetails',
                SecurityUtil::checkPermission('News:publicationdetails:', '::',
                    ACCESS_ADD));
        }

        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('shopName', $shopName);
        $this->view->assign('preview', $preview);

        // Return the output that has been generated by this function
        return $this->view->fetch('user/News/'.$template);
    }

    public function newitemevent($args)
    {

        // News_Controller_User::check();
        // News_Controller_User::preview($args);
        // echo "<pre>"; print_r($_SESSION); echo "</pre>";
        // echo "New Item"; exit;
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('News::',
                '::', ACCESS_READ), LogUtil::getErrorMsgPermission());
        $shop_id = (int) FormUtil::getPassedValue('shop_id', '', 'REQUEST');
        $shpName = FormUtil::getPassedValue('shopName', '', 'REQUEST');

        // echo $_REQUEST['shop_idnewItem'];
        // echo "comes here";
        // echo "shop id :" . $shop_id;
        if ($shop_id == false && $shpName == false) {
            return LogUtil::registerError($this->__('Error! Unable to load page for this Minisite.'));
        }

        if (!empty($shop_id)) {
            $shopitem = ModUtil::apiFunc('ZSELEX', 'user', 'getSingle',
                    $arg      = array(
                    'table' => 'zselex_shop',
                    'idName' => 'shop_id',
                    'id' => $shop_id
            ));
            $shopName = $shopitem ['shop_name'];
        } else {
            $shopName = $shpName;
        }

        $user_id = UserUtil::getVar('uid');

        $servicecount   = 0;
        $error          = 0;
        $message        = '';
        $servicedisable = false;
        $dom            = ZLanguage::getModuleDomain('ZSELEX');

        $admin    = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN);
        $this->view->assign('admin', $admin);
        $template = 'create_event.tpl';

        // echo "count :". $servicecheck;
        // echo $shop_id; exit;
        // Any item set for preview will be stored in a session var
        // Once the new article is posted we'll clear the session var.
        $item      = array();
        $sess_item = SessionUtil::getVar('newsitem');

        // get the type parameter so we can decide what template to use
        $type = FormUtil::getPassedValue('type', 'user', 'REQUEST');

        // Set the default values for the form. If not previewing an item prior
        // to submission these values will be null but do need to be set
        $item ['sid']                 = isset($sess_item ['sid']) ? $sess_item ['sid']
                : '';
        $item ['__CATEGORIES__']      = isset($sess_item ['__CATEGORIES__']) ? $sess_item ['__CATEGORIES__']
                : array();
        $item ['__ATTRIBUTES__']      = isset($sess_item ['__ATTRIBUTES__']) ? $sess_item ['__ATTRIBUTES__']
                : array();
        $item ['title']               = isset($sess_item ['title']) ? $sess_item ['title']
                : '';
        $item ['urltitle']            = isset($sess_item ['urltitle']) ? $sess_item ['urltitle']
                : '';
        $item ['hometext']            = isset($sess_item ['hometext']) ? $sess_item ['hometext']
                : '';
        $item ['hometextcontenttype'] = isset($sess_item ['hometextcontenttype'])
                ? $sess_item ['hometextcontenttype'] : '';
        $item ['bodytext']            = isset($sess_item ['bodytext']) ? $sess_item ['bodytext']
                : '';
        $item ['bodytextcontenttype'] = isset($sess_item ['bodytextcontenttype'])
                ? $sess_item ['bodytextcontenttype'] : '';
        $item ['notes']               = isset($sess_item ['notes']) ? $sess_item ['notes']
                : '';
        $item ['displayonindex']      = isset($sess_item ['displayonindex']) ? $sess_item ['displayonindex']
                : 1;
        $item ['language']            = isset($sess_item ['language']) ? $sess_item ['language']
                : '';
        $item ['allowcomments']       = isset($sess_item ['allowcomments']) ? $sess_item ['allowcomments']
                : 1;
        $item ['from']                = isset($sess_item ['from']) ? $sess_item ['from']
                : DateUtil::getDatetime(null, '%Y-%m-%d %H:%M');
        $item ['to']                  = isset($sess_item ['to']) ? $sess_item ['to']
                : DateUtil::getDatetime(null, '%Y-%m-%d %H:%M');
        $item ['tonolimit']           = isset($sess_item ['tonolimit']) ? $sess_item ['tonolimit']
                : 1;
        $item ['unlimited']           = isset($sess_item ['unlimited']) ? $sess_item ['unlimited']
                : 1;
        $item ['weight']              = isset($sess_item ['weight']) ? $sess_item ['weight']
                : 0;
        $item ['pictures']            = isset($sess_item ['pictures']) ? $sess_item ['pictures']
                : 0;
        $item ['tempfiles']           = isset($sess_item ['tempfiles']) ? $sess_item ['tempfiles']
                : null;
        $item ['temp_pictures']       = isset($sess_item ['tempfiles']) ? unserialize($sess_item ['tempfiles'])
                : null;

        $preview = '';
        if (isset($sess_item ['action']) && $sess_item ['action'] == self::ACTION_PREVIEW) {
            $preview = $this->preview(array(
                'title' => $item ['title'],
                'hometext' => $item ['hometext'],
                'hometextcontenttype' => $item ['hometextcontenttype'],
                'bodytext' => $item ['bodytext'],
                'bodytextcontenttype' => $item ['bodytextcontenttype'],
                'notes' => $item ['notes'],
                'sid' => $item ['sid'],
                'pictures' => $item ['pictures'],
                'temp_pictures' => $item ['temp_pictures']
            ));
        }

        // Get the module vars
        $modvars = ModUtil::getVar('News');

        if ($modvars ['enablecategorization']) {
            $catregistry = CategoryRegistryUtil::getRegisteredModuleCategories('News',
                    'news');
            $this->view->assign('catregistry', $catregistry);

            // add article attribute if morearticles is enabled and general setting is zero
            if ($modvars ['enablemorearticlesincat'] && $modvars ['morearticlesincat']
                == 0) {
                $item ['__ATTRIBUTES__'] ['morearticlesincat'] = 0;
            }
        }

        // Assign the default languagecode
        $this->view->assign('lang', ZLanguage::getLanguageCode());

        // Assign the item to the template
        $this->view->assign('item', $item);

        // Assign the content format
        $formattedcontent = ModUtil::apiFunc('News', 'user', 'isformatted',
                array(
                'func' => 'new'
        ));
        $this->view->assign('formattedcontent', $formattedcontent);

        $this->view->assign('accessadd', 0);
        if (SecurityUtil::checkPermission('News::', '::', ACCESS_ADD)) {
            $this->view->assign('accessadd', 1);
            $this->view->assign('accesspicupload', 1);
            $this->view->assign('accesspubdetails', 1);
        } else {
            $this->view->assign('accesspicupload',
                SecurityUtil::checkPermission('News:pictureupload:', '::',
                    ACCESS_ADD));
            $this->view->assign('accesspubdetails',
                SecurityUtil::checkPermission('News:publicationdetails:', '::',
                    ACCESS_ADD));
        }

        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('shopName', $shopName);
        $this->view->assign('preview', $preview);
        // echo $template;
        // Return the output that has been generated by this function
        return $this->view->fetch('user/News/'.$template);
    }

    /**
     * News module user controller override class function
     */
    public function preview($args)
    {
        // echo "zselex news preview";
        $title               = $args ['title'];
        $hometext            = $args ['hometext'];
        $hometextcontenttype = $args ['hometextcontenttype'];
        $bodytext            = $args ['bodytext'];
        $bodytextcontenttype = $args ['bodytextcontenttype'];
        $notes               = $args ['notes'];
        $sid                 = $args ['sid'];
        $pictures            = $args ['pictures'];
        $temp_pictures       = $args ['temp_pictures'];

        // format the contents if needed
        if ($hometextcontenttype == 0) {
            $hometext = nl2br($hometext);
        }
        if ($bodytextcontenttype == 0) {
            $bodytext = nl2br($bodytext);
        }
        $this->view->setCaching(false);

        $this->view->assign('preview',
            array(
            'title' => $title,
            'hometext' => $hometext,
            'bodytext' => $bodytext,
            'notes' => $notes,
            'sid' => $sid,
            'pictures' => $pictures,
            'temp_pictures' => $temp_pictures
        ));

        return $this->view->fetch('user/News/preview.tpl');
    }

    public function chk()
    {
        echo "testing function";
        return $this->view->fetch('user/viewtest.tpl');
    }

    public function viewshoparticles($args)
    {

        // echo "Shop Articles";
        // echo $_GET['shop_id'];
        $shop_id = $_REQUEST ['shop_id'];

        $this->throwForbiddenUnless(SecurityUtil::checkPermission('News::',
                '::', ACCESS_OVERVIEW), LogUtil::getErrorMsgPermission());

        $user_id = UserUtil::getVar('uid');
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        // clean the session preview data
        SessionUtil::delVar('newsitem');

        // get all module vars for later use
        $modvars = ModUtil::getVar('News');

        // Get parameters from whatever input we need
        $theme               = isset($args ['theme']) ? strtolower($args ['theme'])
                : (string) strtolower(FormUtil::getPassedValue('theme', 'user',
                    'GET'));
        $page                = isset($args ['page']) ? $args ['page'] : (int) FormUtil::getPassedValue('page',
                1, 'GET');
        $prop                = isset($args ['prop']) ? $args ['prop'] : (string) FormUtil::getPassedValue('prop',
                null, 'GET');
        $cat                 = isset($args ['cat']) ? $args ['cat'] : (string) FormUtil::getPassedValue('cat',
                null, 'GET');
        $displayModule       = FormUtil::getPassedValue('module', 'X', 'GET');
        $defaultItemsPerPage = ($displayModule == 'X') ? $modvars ['storyhome'] : $modvars ['itemsperpage'];
        $itemsperpage        = isset($args ['itemsperpage']) ? $args ['itemsperpage']
                : (int) FormUtil::getPassedValue('itemsperpage',
                $defaultItemsPerPage, 'GET');
        $displayonindex      = isset($args ['displayonindex']) ? (int) $args ['displayonindex']
                : FormUtil::getPassedValue('displayonindex', null, 'GET');

        $allowedThemes = array(
            'user',
            'rss',
            'atom',
            'printer'
        );
        $theme         = in_array($theme, $allowedThemes) ? $theme : 'user';

        // work out page size from page number
        $startnum = (($page - 1) * $itemsperpage) + 1;

        $lang = ZLanguage::getLanguageCode();

        // check if categorization is enabled
        if ($modvars ['enablecategorization']) {
            // get the categories registered for News
            $catregistry = CategoryRegistryUtil::getRegisteredModuleCategories('News',
                    'news');
            $properties  = array_keys($catregistry);

            // validate the property
            // and build the category filter - mateo
            if (!empty($prop) && in_array($prop, $properties) && !empty($cat)) {
                if (!is_numeric($cat)) {
                    $rootCat = CategoryUtil::getCategoryByID($catregistry [$prop]);
                    $cat     = CategoryUtil::getCategoryByPath($rootCat ['path'].'/'.$cat);
                } else {
                    $cat = CategoryUtil::getCategoryByID($cat);
                }
                $catname = isset($cat ['display_name'] [$lang]) ? $cat ['display_name'] [$lang]
                        : $cat ['name'];

                if (!empty($cat) && isset($cat ['path'])) {
                    // include all it's subcategories and build the filter
                    $categories   = CategoryUtil::getCategoriesByPath($cat ['path'],
                            '', 'path');
                    $catstofilter = array();
                    foreach ($categories as $category) {
                        $catstofilter [] = $category ['id'];
                    }
                    $catFilter = array(
                        $prop => $catstofilter
                    );
                } else {
                    LogUtil::registerError($this->__('Error! Invalid category passed.'));
                }
            }
        }

        // get matching news articles
        $items = ModUtil::apiFunc('News', 'block', 'getall',
                array(
                'startnum' => $startnum,
                'numitems' => $itemsperpage,
                // 'status' => News_Api_User::STATUS_PUBLISHED,
                'status' => 0,
                'displayonindex' => $displayonindex,
                'filterbydate' => true,
                'category' => isset($catFilter) ? $catFilter : null, // get all method doesn't appear to want a category arg
                'catregistry' => isset($catregistry) ? $catregistry : null
        ));

        // echo "<pre>"; print_r($items); echo "</pre>";

        if ($items == false) {
            if ($modvars ['enablecategorization'] && isset($catFilter)) {
                LogUtil::registerStatus($this->__f('No articles currently published under the \'%s\' category.',
                        $catname));
            } else {
                LogUtil::registerStatus($this->__('No articles currently published.'));
            }
        }

        // assign various useful template variables
        $this->view->assign('startnum', $startnum);
        $this->view->assign('lang', $lang);

        // assign the root category
        $this->view->assign('category', $cat);
        $this->view->assign('catname', isset($catname) ? $catname : '' );

        $shop_id      = $_REQUEST ['shop_id'];
        $serviceargs  = array(
            'shop_id' => $shop_id,
            'type' => 'youtubelink'
        );
        $serviceargs  = array(
            'shop_id' => $shop_id,
            'type' => 'youtubelink'
        );
        $serviceExist = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceExist',
                $serviceargs);

        $newsitems = array();
        // Loop through each item and display it
        foreach ($items as $item) {
            // ///////////////////////////finding you tube link and remove if service not bought/////////////////////////////////////
            if ($serviceExist < 1) {
                $pattern1    = "!<iframe[^>]+>(.*?)</iframe>!";
                $pattern2    = "!<object[^>]+>(.*?)</object>!";
                $pattern3    = "!<embed[^>]+>(.*?)</embed>!";
                $replacement = "";

                $item ['hometext'] = preg_replace($pattern1, $replacement,
                    $item ['hometext']);
                $item ['hometext'] = preg_replace($pattern2, $replacement,
                    $item ['hometext']);
                $item ['hometext'] = preg_replace($pattern3, $replacement,
                    $item ['hometext']);

                $item ['bodytext'] = preg_replace($pattern1, $replacement,
                    $item ['bodytext']);
                $item ['bodytext'] = preg_replace($pattern2, $replacement,
                    $item ['bodytext']);
                $item ['bodytext'] = preg_replace($pattern3, $replacement,
                    $item ['bodytext']);
            }
            // /////////////////////////////////////////////////////////////////////////////////////////////////////
            // display if it's published and the displayonindex match (if set)
            if (($item ['published_status'] == 0) && (!isset($displayonindex) || $item ['displayonindex']
                == $displayonindex)) {

                $template = 'user/index.tpl';
                if (!$this->view->is_cached($template, $item ['sid'])) {
                    // $info is array holding raw information.
                    // Used below and also passed to the theme - jgm
                    $info = ModUtil::apiFunc('News', 'block', 'getArticleInfo',
                            $item);

                    // $links is an array holding pure URLs to
                    // specific functions for this article.
                    // Used below and also passed to the theme - jgm
                    $links = ModUtil::apiFunc('News', 'block',
                            'getArticleLinks', $info);

                    // $preformat is an array holding chunks of
                    // preformatted text for this article.
                    // Used below and also passed to the theme - jgm
                    $preformat = ModUtil::apiFunc('News', 'block',
                            'getArticlePreformat',
                            array(
                            'info' => $info,
                            'links' => $links
                    ));

                    // echo "<pre>"; print_r($preformat); echo "</pre>";

                    $this->view->assign(array(
                        'info' => $info,
                        'links' => $links,
                        'shop_id' => $_REQUEST ['shop_id'],
                        'preformat' => $preformat
                    ));
                }

                $newsitems [] = $this->view->fetch($template, $item ['sid']);
            }
        }

        // The items that are displayed on this overview page depend on the individual
        // user permissions. Therefor, we can not cache the whole page.
        // The single entries are cached, though.
        $this->view->setCaching(false);

        // Display the entries
        $this->view->assign('newsitems', $newsitems);

        // Assign the values for the smarty plugin to produce a pager
        $this->view->assign('pager',
            array(
            'numitems' => ModUtil::apiFunc('News', 'block', 'countitems',
                array(
                'status' => 0,
                'filterbydate' => true,
                'displayonindex' => $displayonindex,
                'category' => isset($catFilter) ? $catFilter : null
            )),
            'itemsperpage' => $itemsperpage
        ));

        // Return the output that has been generated by this function

        return $this->view->fetch('user/viewshoparticles.tpl');
    }

    public function serviceExist($args)
    {
        $exist = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceExist', $args);
        return $exist;
    }

    public function display($args)
    {

        // exit;
        // echo "Shop Id: ".$_REQUEST['shop_id'];
        // Get parameters from whatever input we need
        $sid       = (int) FormUtil::getPassedValue('sid', null, 'REQUEST');
        $objectid  = (int) FormUtil::getPassedValue('objectid', null, 'REQUEST');
        $page      = (int) FormUtil::getPassedValue('page', 1, 'REQUEST');
        $title     = FormUtil::getPassedValue('title', null, 'REQUEST');
        $year      = FormUtil::getPassedValue('year', null, 'REQUEST');
        $monthnum  = FormUtil::getPassedValue('monthnum', null, 'REQUEST');
        $monthname = FormUtil::getPassedValue('monthname', null, 'REQUEST');
        $day       = FormUtil::getPassedValue('day', null, 'REQUEST');

        // User functions of this type can be called by other modules
        extract($args);

        // echo "<pre>"; print_r($args); echo "</pre>";

        $theme         = isset($args ['theme']) ? strtolower($args ['theme']) : (string) strtolower(FormUtil::getPassedValue('theme',
                    'user', 'GET'));
        $allowedThemes = array(
            'user',
            'printer'
        );
        $theme         = in_array($theme, $allowedThemes) ? $theme : 'user';

        // At this stage we check to see if we have been passed $objectid, the
        // generic item identifier
        if ($objectid) {

            $sid = $objectid;
        }

        // Validate the essential parameters
        if ((empty($sid) || !is_numeric($sid)) && (empty($title))) {
            return LogUtil::registerArgsError();
        }
        if (!empty($title)) {

            unset($sid);
        }

        // Set the default page number
        if ($page < 1 || !is_numeric($page)) {
            $page = 1;
        }

        // increment the read count
        if ($page == 1) {

            if (isset($sid)) {

                ModUtil::apiFunc('News', 'user', 'incrementreadcount',
                    array(
                    'sid' => $sid
                ));
            } else {

                ModUtil::apiFunc('News', 'user', 'incrementreadcount',
                    array(
                    'title' => $title
                ));
            }
        }

        // For caching reasons you must pass a cache ID
        if (isset($sid)) {
            $this->view->setCacheId($sid.$page);
        } else {
            $this->view->setCacheId($title.$page);
        }

        // Get the news story
        if (!SecurityUtil::checkPermission('News::', "::", ACCESS_ADD)) {
            if (isset($sid)) {

                $item = ModUtil::apiFunc('News', 'user', 'get',
                        array(
                        'sid' => $sid,
                        'status' => 0
                ));
            } else {

                $item = ModUtil::apiFunc('News', 'user', 'get',
                        array(
                        'title' => $title,
                        'year' => $year,
                        'monthname' => $monthname,
                        'monthnum' => $monthnum,
                        'day' => $day,
                        'status' => 0
                ));
                $sid  = $item ['sid'];
                System::queryStringSetVar('sid', $sid);
            }
        } else {
            if (isset($sid)) {
                $item = ModUtil::apiFunc('News', 'user', 'get',
                        array(
                        'sid' => $sid
                ));
            } else {

                $item = ModUtil::apiFunc('News', 'user', 'get',
                        array(
                        'title' => $title,
                        'year' => $year,
                        'monthname' => $monthname,
                        'monthnum' => $monthnum,
                        'day' => $day
                ));
                $sid  = $item ['sid'];
                System::queryStringSetVar('sid', $sid);
            }
        }

        // echo "<pre>"; print_r($item); echo "</pre>";
        // ///////////////////////////finding you tube and remove/////////////////////////////////////

        $shop_id      = $_REQUEST ['shop_id'];
        $serviceargs  = array(
            'shop_id' => $shop_id,
            'type' => 'youtubelink'
        );
        $serviceExist = $this->serviceExist($serviceargs);

        if ($serviceExist < 1) {

            $item ['hometext'] = preg_replace("!<iframe[^>]+>(.*?)</iframe>!",
                "", $item ['hometext']);
            $item ['hometext'] = preg_replace("!<object[^>]+>(.*?)</object>!",
                "", $item ['hometext']);
            $item ['hometext'] = preg_replace("!<embed[^>]+>(.*?)</embed>!", "",
                $item ['hometext']);

            $item ['bodytext'] = preg_replace("!<iframe[^>]+>(.*?)</iframe>!",
                "", $item ['bodytext']);
            $item ['bodytext'] = preg_replace("!<object[^>]+>(.*?)</object>!",
                "", $item ['bodytext']);
            $item ['bodytext'] = preg_replace("!<embed[^>]+>(.*?)</embed>!", "",
                $item ['bodytext']);
        }

        // ////////////////////////////////////////////////////////////////////////////
        // echo "<pre>"; print_r($item); echo "</pre>";

        if ($item === false) {
            return LogUtil::registerError($this->__('Error! No such article found.'),
                    403);
        }

        // Explode the story into an array of seperate pages
        $allpages = explode('<!--pagebreak-->', $item ['bodytext']);

        // Set the item bodytext to be the required page
        // nb arrays start from zero, pages from one
        $item ['bodytext'] = $allpages [$page - 1];
        $numpages          = count($allpages);
        unset($allpages);

        // If the pagecount is greater than 1 and we're not on the frontpage
        // don't show the hometext
        if ($numpages > 1 && $page > 1) {
            $item ['hometext'] = '';
        }

        // $info is array holding raw information.
        // Used below and also passed to the theme - jgm
        $info = ModUtil::apiFunc('News', 'user', 'getArticleInfo', $item);

        // $links is an array holding pure URLs to specific functions for this article.
        // Used below and also passed to the theme - jgm
        $links = ModUtil::apiFunc('News', 'user', 'getArticleLinks', $info);

        // $preformat is an array holding chunks of preformatted text for this article.
        // Used below and also passed to the theme - jgm
        $preformat = ModUtil::apiFunc('News', 'user', 'getArticlePreformat',
                array(
                'info' => $info,
                'links' => $links
        ));

        // set the page title
        if ($numpages <= 1) {
            PageUtil::setVar('title', $info ['title']);
        } else {
            PageUtil::setVar('title',
                $info ['title'].' :: '.$this->__f('page %s', $page));
        }

        // Assign the story info arrays
        $this->view->assign(array(
            'info' => $info,
            'links' => $links,
            'preformat' => $preformat,
            'page' => $page
        ));

        $modvars = $this->getVars();
        $this->view->assign('lang', ZLanguage::getLanguageCode());

        // get more articletitles in the categories of this article
        if ($modvars ['enablecategorization'] && $modvars ['enablemorearticlesincat']) {
            // check how many articles to display
            if ($modvars ['morearticlesincat'] > 0 && !empty($info ['categories'])) {
                $morearticlesincat = $modvars ['morearticlesincat'];
            } elseif ($modvars ['morearticlesincat'] == 0 && array_key_exists('morearticlesincat',
                    $info ['attributes'])) {
                $morearticlesincat = $info ['attributes'] ['morearticlesincat'];
            } else {
                $morearticlesincat = 0;
            }
            if ($morearticlesincat > 0) {
                // get the categories registered for News
                $catregistry = CategoryRegistryUtil::getRegisteredModuleCategories('News',
                        'news');
                foreach (array_keys($catregistry) as $property) {
                    $catFilter [$property] = $info ['categories'] [$property] ['id'];
                }
                // get matching news articles
                $morearticlesincat = ModUtil::apiFunc('News', 'user', 'getall',
                        array(
                        'numitems' => $morearticlesincat,
                        'status' => 0,
                        'filterbydate' => true,
                        'category' => $catFilter,
                        'catregistry' => $catregistry,
                        'query' => array(
                            array(
                                'sid',
                                '!=',
                                $sid
                            )
                        )
                ));
            }
        } else {
            $morearticlesincat = 0;
        }
        $this->view->assign('morearticlesincat', $morearticlesincat);

        // Now lets assign the informatation to create a pager for the review
        $this->view->assign('pager',
            array(
            'numitems' => $numpages,
            'itemsperpage' => 1
        ));

        // Return the output that has been generated by this function
        return $this->view->fetch($theme.'/article.tpl');
    }

    public function displayarticleevent($args)
    {

        // exit;
        // echo "Shop Id: ".$_REQUEST['shop_id'];
        // Get parameters from whatever input we need
        $article   = $args ['article'];
        $sid       = (int) FormUtil::getPassedValue('sid', null, 'REQUEST');
        $objectid  = (int) FormUtil::getPassedValue('objectid', null, 'REQUEST');
        $page      = (int) FormUtil::getPassedValue('page', 1, 'REQUEST');
        $title     = FormUtil::getPassedValue('title', null, 'REQUEST');
        $year      = FormUtil::getPassedValue('year', null, 'REQUEST');
        $monthnum  = FormUtil::getPassedValue('monthnum', null, 'REQUEST');
        $monthname = FormUtil::getPassedValue('monthname', null, 'REQUEST');
        $day       = FormUtil::getPassedValue('day', null, 'REQUEST');

        // User functions of this type can be called by other modules
        extract($args);

        // echo "<pre>"; print_r($args); echo "</pre>";

        $theme         = isset($args ['theme']) ? strtolower($args ['theme']) : (string) strtolower(FormUtil::getPassedValue('theme',
                    'user', 'GET'));
        $allowedThemes = array(
            'user',
            'printer'
        );
        $theme         = in_array($theme, $allowedThemes) ? $theme : 'user';

        // At this stage we check to see if we have been passed $objectid, the
        // generic item identifier
        if ($objectid) {

            $sid = $objectid;
        }

        // Validate the essential parameters
        if ((empty($sid) || !is_numeric($sid)) && (empty($title))) {
            return LogUtil::registerArgsError();
        }
        if (!empty($title)) {

            unset($sid);
        }

        // Set the default page number
        if ($page < 1 || !is_numeric($page)) {
            $page = 1;
        }

        // increment the read count
        if ($page == 1) {

            if (isset($sid)) {

                ModUtil::apiFunc('News', 'user', 'incrementreadcount',
                    array(
                    'sid' => $sid
                ));
            } else {

                ModUtil::apiFunc('News', 'user', 'incrementreadcount',
                    array(
                    'title' => $title
                ));
            }
        }

        // For caching reasons you must pass a cache ID
        if (isset($sid)) {
            $this->view->setCacheId($sid.$page);
        } else {
            $this->view->setCacheId($title.$page);
        }

        // Get the news story
        if (!SecurityUtil::checkPermission('News::', "::", ACCESS_ADD)) {
            if (isset($sid)) {

                $item = ModUtil::apiFunc('News', 'user', 'get',
                        array(
                        'sid' => $sid,
                        'status' => 0
                ));
            } else {

                $item = ModUtil::apiFunc('News', 'user', 'get',
                        array(
                        'title' => $title,
                        'year' => $year,
                        'monthname' => $monthname,
                        'monthnum' => $monthnum,
                        'day' => $day,
                        'status' => 0
                ));
                $sid  = $item ['sid'];
                System::queryStringSetVar('sid', $sid);
            }
        } else {
            if (isset($sid)) {
                $item = ModUtil::apiFunc('News', 'user', 'get',
                        array(
                        'sid' => $sid
                ));
            } else {

                $item = ModUtil::apiFunc('News', 'user', 'get',
                        array(
                        'title' => $title,
                        'year' => $year,
                        'monthname' => $monthname,
                        'monthnum' => $monthnum,
                        'day' => $day
                ));
                $sid  = $item ['sid'];
                System::queryStringSetVar('sid', $sid);
            }
        }

        // echo "<pre>"; print_r($item); echo "</pre>";
        // ///////////////////////////finding you tube and remove/////////////////////////////////////

        $shop_id      = $_REQUEST ['shop_id'];
        $serviceargs  = array(
            'shop_id' => $shop_id,
            'type' => 'youtubelink'
        );
        $serviceExist = $this->serviceExist($serviceargs);

        if ($serviceExist < 1) {

            $item ['hometext'] = preg_replace("!<iframe[^>]+>(.*?)</iframe>!",
                "", $item ['hometext']);
            $item ['hometext'] = preg_replace("!<object[^>]+>(.*?)</object>!",
                "", $item ['hometext']);
            $item ['hometext'] = preg_replace("!<embed[^>]+>(.*?)</embed>!", "",
                $item ['hometext']);

            $item ['bodytext'] = preg_replace("!<iframe[^>]+>(.*?)</iframe>!",
                "", $item ['bodytext']);
            $item ['bodytext'] = preg_replace("!<object[^>]+>(.*?)</object>!",
                "", $item ['bodytext']);
            $item ['bodytext'] = preg_replace("!<embed[^>]+>(.*?)</embed>!", "",
                $item ['bodytext']);
        }

        // ////////////////////////////////////////////////////////////////////////////
        // echo "<pre>"; print_r($item); echo "</pre>";

        if ($item === false) {
            return LogUtil::registerError($this->__('Error! No such article found.'),
                    403);
        }

        // Explode the story into an array of seperate pages
        $allpages = explode('<!--pagebreak-->', $item ['bodytext']);

        // Set the item bodytext to be the required page
        // nb arrays start from zero, pages from one
        $item ['bodytext'] = $allpages [$page - 1];
        $numpages          = count($allpages);
        unset($allpages);

        // If the pagecount is greater than 1 and we're not on the frontpage
        // don't show the hometext
        if ($numpages > 1 && $page > 1) {
            $item ['hometext'] = '';
        }

        // $info is array holding raw information.
        // Used below and also passed to the theme - jgm
        $info = ModUtil::apiFunc('News', 'user', 'getArticleInfo', $item);

        // $links is an array holding pure URLs to specific functions for this article.
        // Used below and also passed to the theme - jgm
        $links = ModUtil::apiFunc('News', 'user', 'getArticleLinks', $info);

        // $preformat is an array holding chunks of preformatted text for this article.
        // Used below and also passed to the theme - jgm
        $preformat = ModUtil::apiFunc('News', 'user', 'getArticlePreformat',
                array(
                'info' => $info,
                'links' => $links
        ));

        // set the page title
        if ($numpages <= 1) {
            PageUtil::setVar('title', $info ['title']);
        } else {
            PageUtil::setVar('title',
                $info ['title'].' :: '.$this->__f('page %s', $page));
        }

        // Assign the story info arrays
        $this->view->assign(array(
            'info' => $info,
            'links' => $links,
            'preformat' => $preformat,
            'page' => $page
        ));

        $modvars = $this->getVars();
        $this->view->assign('lang', ZLanguage::getLanguageCode());

        // get more articletitles in the categories of this article
        if ($modvars ['enablecategorization'] && $modvars ['enablemorearticlesincat']) {
            // check how many articles to display
            if ($modvars ['morearticlesincat'] > 0 && !empty($info ['categories'])) {
                $morearticlesincat = $modvars ['morearticlesincat'];
            } elseif ($modvars ['morearticlesincat'] == 0 && array_key_exists('morearticlesincat',
                    $info ['attributes'])) {
                $morearticlesincat = $info ['attributes'] ['morearticlesincat'];
            } else {
                $morearticlesincat = 0;
            }
            if ($morearticlesincat > 0) {
                // get the categories registered for News
                $catregistry = CategoryRegistryUtil::getRegisteredModuleCategories('News',
                        'news');
                foreach (array_keys($catregistry) as $property) {
                    $catFilter [$property] = $info ['categories'] [$property] ['id'];
                }
                // get matching news articles
                $morearticlesincat = ModUtil::apiFunc('News', 'user', 'getall',
                        array(
                        'numitems' => $morearticlesincat,
                        'status' => 0,
                        'filterbydate' => true,
                        'category' => $catFilter,
                        'catregistry' => $catregistry,
                        'query' => array(
                            array(
                                'sid',
                                '!=',
                                $sid
                            )
                        )
                ));
            }
        } else {
            $morearticlesincat = 0;
        }
        $this->view->assign('morearticlesincat', $morearticlesincat);

        // Now lets assign the informatation to create a pager for the review
        $this->view->assign('pager',
            array(
            'numitems' => $numpages,
            'itemsperpage' => 1
        ));

        // Return the output that has been generated by this function
        return $this->view->fetch($theme.'/article.tpl');
    }

    /**
     * view a single event
     *
     * @param int GET eventId
     * @param int GET shop_id
     *
     * @return event arrays
     */
    public function viewevent($args)
    {

        // echo "<pre>"; print_r($_REQUEST); echo "</pre>";
        $eventId    = FormUtil::getPassedValue('eventId', null, 'REQUEST');
        // echo $eventId;
        $eventTitle = FormUtil::getPassedValue('event_urltitle', null, 'REQUEST');
        $shop_id    = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        // echo $eventTitle; exit;

        /*
         * if (!is_numeric($shop_id)) {
         * // echo "comes here";
         * return LogUtil::registerError($this->__f('Error! The Shop Name in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!', (int) ($shop_id)), 403);
         * }
         */
        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Event');
        // $shop_name = FormUtil::getPassedValue('shop_name', null, 'REQUEST');
        // echo $this->ownername;
        // PageUtil::setVar('title', $this->__("View Event - " . $shop_name));

        $this->view->assign('ownername', $this->ownername);
        // $event = DBUtil::selectObjectByID('zselex_shop_events', $eventId, 'shop_event_id');
        $item  = $repo->getViewEvent(array(
            'shop_id' => $shop_id,
            'event_id' => $eventId,
            'event_title' => $eventTitle
        ));
        $event = $item;
        // echo "<pre>"; print_r($event); echo "</pre>";

        if ($event == false) {
            return LogUtil::registerError($this->__('Error! Event not found.'),
                    403);
        }

        $shop_id = $event ['shop_id'];
        System::queryStringSetVar('shop_id', $shop_id);

        $shop_name = $event ['shop_name'];
        System::queryStringSetVar('shop_name', $shop_name);
        System::queryStringSetVar('shopName', $shop_name);

        System::queryStringSetVar('shop_event_venue', $event['shop_event_venue']);
        System::queryStringSetVar('showfrom', $event['showfrom']);
        System::queryStringSetVar('email', $event['email']);
        System::queryStringSetVar('phone', $event['phone']);
        System::queryStringSetVar('price', $event['price']);
        System::queryStringSetVar('event_link', $event['event_link']);
        System::queryStringSetVar('open_new', $event['open_new']);
        System::queryStringSetVar('shop_event_starthour',
            $event['shop_event_starthour']);
        System::queryStringSetVar('shop_event_endhour',
            $event['shop_event_endhour']);

        PageUtil::setVar('title', $this->__("View Event - ".$shop_name));

        $shoptype = ModUtil::apiFunc('ZSELEX', 'admin', 'shopType',
                $args     = array(
                'shop_id' => $shop_id
        ));
        $shoptype = $shoptype ['shoptype'];

        // $shop_info = $repo->
        // echo "<pre>"; print_r($shop_info); echo "</pre>";
        // $total_ratings = DBUtil::selectObjectCountByID($table = 'zselex_shop_ratings', $id = $shop_id, $field = 'shop_id', $transformFunc = '');
        // $this->view->assign('total_ratings', $total_ratings);
        if ($event ['showfrom'] == 'article') {
            $news_id = $event ['news_article_id'];
            $article = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow',
                    array(
                    'table' => 'news',
                    'where' => array(
                        "sid=$news_id"
                    )
            ));
            $this->view->assign('info', $article);
        } elseif ($event ['showfrom'] == 'product') {
            $product_id = $event ['product_id'];
            // echo "<pre>"; print_r($product); echo "</pre>";
            if ($shoptype == 'iSHOP') {

                $finalproduct = $repo->get(array(
                    'entity' => 'ZSELEX_Entity_Product',
                    'where' => array(
                        'a.product_id' => $product_id
                    )
                ));

                // echo "<pre>"; print_r($finalproduct); echo "</pre>";
            } elseif ($shoptype == 'zSHOP') {

                $zencart     = $repo->get(array(
                    'entity' => 'ZSELEX_Entity_ZenShop',
                    'where' => array(
                        'a.shop' => $shop_id
                    )
                ));
                $this->view->assign('zencart', $zencart);
                $zenproducts = ModUtil::apiFunc('ZSELEX', 'admin',
                        'getZenCartProduct',
                        array(
                        'shop' => $zencart,
                        'shop_id' => $shop_id,
                        'product_id' => $product_id
                ));
                // echo "<pre>"; print_r($zenproducts); echo "</pre>";

                $finalproduct = $zenproducts;
                // $finalproduct = array(
                // 'product_id' => $zenproducts['products_id'],
                // 'product_name' => $zenproducts['products_name'],
                // );
            }
            // echo "<pre>"; print_r($finalproduct); echo "</pre>";
            $this->view->assign('product', $finalproduct);
        }

        if ($event ['event_doc'] != '') {
            $event ['pdf_image'] = basename($event ['event_doc'], '.pdf');
        }

        $eventdate                     = $event ['shop_event_startdate'];
        $timestamp                     = strtotime($eventdate);
        $date_format_start             = getdate(date($timestamp));
        $date_format_start ['weekday'] = $this->__($date_format_start ['weekday']);
        $date_format_start ['month']   = $this->__($date_format_start ['month']);
        if ($eventdate != $event ['shop_event_enddate']) {
            $eventdate                   = $event ['shop_event_enddate'];
            $timestamp                   = strtotime($eventdate);
            $date_format_end             = getdate(date($timestamp));
            $date_format_end ['weekday'] = $this->__($date_format_end ['weekday']);
            $date_format_end ['month']   = $this->__($date_format_end ['month']);
        }

        System::queryStringSetVar('start_weekday',
            $date_format_start ['weekday']);
        System::queryStringSetVar('start_mday', $date_format_start ['mday']);
        System::queryStringSetVar('start_month', $date_format_start ['month']);

        System::queryStringSetVar('end_weekday', $date_format_end ['weekday']);
        System::queryStringSetVar('end_mday', $date_format_end ['mday']);
        System::queryStringSetVar('end_month', $date_format_end ['month']);
        // echo "<pre>"; print_r($date_format_start); echo "</pre>";
        $event_doc  = urlencode($event ['event_doc']);
        $path_parts = pathinfo($event_doc);
        $extension  = $path_parts ['extension'];
        // echo "<pre>"; print_r($path_parts); echo "</pre>";
        // echo "<pre>"; print_r($event); echo "</pre>";

        $user_id = UserUtil::getVar('uid');
        $perm    = ModUtil::apiFunc('ZSELEX', 'admin', 'shopPermission',
                array(
                'shop_id' => $shop_id,
                'user_id' => $user_id
        ));
        // $perm = $_REQUEST['perm'];
        // echo "Comes here";
        // $perm = $_REQUEST['perm'];
        $user_id = UserUtil::getVar('uid');
        $perm    = ModUtil::apiFunc('ZSELEX', 'admin', 'shopPermission',
                array(
                'shop_id' => $shop_id,
                'user_id' => $user_id
        ));
        System::queryStringSetVar('perm', $perm);
        $this->view->assign('perm', $perm);
        $this->view->assign('shoptype', $shoptype);
        $this->view->assign('event', $event);
        $this->view->assign('extension', $extension);
        $this->view->assign('event_doc', $event_doc);
        $this->view->assign('date_format_start', $date_format_start);
        $this->view->assign('date_format_end', $date_format_end);
        $this->view->assign('shop_id', $shop_id);
        return $this->view->fetch('user/viewevent.tpl');
    }

    public function testMap()
    {
        $ip   = "122.166.29.86";
        // $ip = $_SERVER['REMOTE_ADDR'];
        $json = file_get_contents("http://api.easyjquery.com/ips/?ip=".$ip."&full=true");
        $json = json_decode($json, true);

        $this->view->assign('userlats', $json ['CityLatitude']);
        $this->view->assign('userlngs', $json ['CityLongitude']);
        return $this->view->fetch('user/testmap.tpl');
    }

    function pdfView($pdf)
    {
        $pdf       = $_REQUEST ['pdf'];
        $shop_id   = $_REQUEST ['shop_id'];
        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));
        // echo $pdf; exit;

        $file = "zselexdata/$ownerName/pdfupload/".$pdf;
        $this->view->assign('file', $file);

        return $this->view->fetch('user/pdfview.tpl');
        /*
         * if (file_exists($file)) {
         * header('Content-Description: File Transfer');
         * header('Content-Type: application/pdf');
         * header('Content-Disposition: attachment; filename=' . basename($file));
         * header('Content-Transfer-Encoding: binary');
         * header('Expires: 0');
         * header('Cache-Control: must-revalidate');
         * header('Pragma: public');
         * header('Content-Length: ' . filesize($file));
         * ob_clean();
         * flush();
         * readfile($file);
         * exit;
         * }
         *
         */
    }

    function pdfViewEvent($pdf)
    {
        // echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
        $pdf       = $_REQUEST ['pdf'];
        $shop_id   = $_REQUEST ['shop_id'];
        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));
        $pdf       = urldecode($pdf);
        // echo $pdf; exit;

        $path_parts = pathinfo($pdf);
        $extension  = $path_parts ['extension'];
        // echo "<pre>"; print_r($path_parts); echo "</pre>"; exit;
        // $file = "zselexdata/$ownerName/events/docs/" . $pdf;
        $file       = "zselexdata/$shop_id/events/docs/".$pdf;
        if (!file_exists($file)) {
            return LogUtil::registerError($this->__("file not found!"));
        }
        if ($extension == 'doc') {
            header('Content-disposition: inline');
            header('Content-type: application/msword'); // not sure if this is the correct MIME type
            readfile($file);
            exit();
        }
        $this->view->assign('file', $file);
        return $this->view->fetch('user/pdfview.tpl');
        /*
         * if (file_exists($file)) {
         *
         * header('Content-Description: File Transfer');
         * header('Content-Type: application/' . $extension);
         * header('Content-Disposition: attachment; filename=' . basename($file));
         * header('Content-Transfer-Encoding: binary');
         * header('Expires: 0');
         * header('Cache-Control: must-revalidate');
         * header('Pragma: public');
         * header('Content-Length: ' . filesize($file));
         * ob_clean();
         * flush();
         * readfile($file);
         * exit;
         * }
         *
         */
    }

    public function getminisiteimage($args)
    {
        $imageId = $args ['id'];
        $obj     = DBUtil::selectObjectByID('zselex_files', $imageId, 'file_id');
        $image   = $obj ['name'];
        return $image;
    }

    public function getminisitegalleryimage($args)
    {
        $imageId = $args ['id'];
        $obj     = DBUtil::selectObjectByID('zselex_shop_gallery', $imageId,
                'gallery_id');
        $image   = $obj ['image_name'];
        return $image;
    }

    function createDateRangeArray($start, $end)
    {
        $range = array();

        if (is_string($start) === true) $start = strtotime($start);
        if (is_string($end) === true) $end   = strtotime($end);

        if ($start > $end) return $this->createDateRangeArray($end, $start);

        do {
            $range [] = date('Y-m-d', $start);
            $start    = strtotime("+ 1 day", $start);
        } while ($start < $end);

        return $range;
    }

    /**
     * Array Pagination Function.
     * By Sergey Gurevich.
     *
     * Input:
     * 1 - Target Array.
     * 2 - Page Number.
     * 3 - Link prefix (example: "?page=").
     * 4 - Link suffix.
     * 5 - Results per page.
     * 6 - Number of pages displayed in the page link panel.
     *
     * Output:
     * - $output['panel'] - Link Panel HTML source.
     * - $output['offset'] - Current page number.
     * - $output['limit'] - Number of resuts per page.
     * - $output['array'] = - Array of current page results.
     *
     * Will return FALSE if no pagination was done.
     */
    function pagination_array($url, $array, $page = 1, $link_prefix = false,
                              $link_suffix = false, $limit_page = 10,
                              $limit_number = 10)
    {
        if (empty($page) or ! $limit_page) $page = 1;

        // echo $link_prefix . '<br>';
        // echo $url . '<br>';
        $num_rows = count($array);
        if (!$num_rows) {
            // return false;
            $array = array();
        }

        // if (!$num_rows or $limit_page >= $num_rows)
        // return false;
        $num_pages   = ceil($num_rows / $limit_page);
        $page_offset = ($page - 1) * $limit_page;

        // Calculating the first number to show.
        if ($limit_number) {
            $limit_number_start = $page - ceil($limit_number / 2);
            $limit_number_end   = ceil($page + $limit_number / 2) - 1;
            if ($limit_number_start < 1) $limit_number_start = 1;
            // In case if the current page is at the beginning.
            $dif                = ($limit_number_end - $limit_number_start);
            if ($dif < $limit_number)
                    $limit_number_end   = $limit_number_end + ($limit_number - ($dif
                    + 1));
            if ($limit_number_end > $num_pages) $limit_number_end   = $num_pages;
            // In case if the current page is at the ending.
            $dif                = ($limit_number_end - $limit_number_start);
            if ($limit_number_start < 1) $limit_number_start = 1;
        } else {
            $limit_number_start = 1;
            $limit_number_end   = $num_pages;
        }
        // Generating page links.
        for ($i = $limit_number_start; $i <= $limit_number_end; $i ++) {
            $page_cur = "<a href='$url$link_prefix$i$link_suffix'>$i</a>";
            if ($page == $i) $page_cur = "<b><font color=red>$i</font></b>";
            else $page_cur = "<a href='$url$link_prefix$i$link_suffix'>$i</a>";

            $panel .= " <span>$page_cur</span>";
        }

        $panel = trim($panel);
        // Navigation arrows.
        if ($limit_number_start > 1)
                $panel = "<b><a href='$url/$link_prefix".(1)."$link_suffix'>&lt;&lt;</a>  <a href='$url/$link_prefix".($page
                - 1)."$link_suffix'>&lt;</a></b> $panel";
        if ($limit_number_end < $num_pages)
                $panel = "$panel <b><a href='$url/$link_prefix".($page + 1)."$link_suffix'>&gt;</a> <a href='$url/$link_prefix$num_pages$link_suffix'>&gt;&gt;</a></b>";

        $startnum = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        if (!empty($startnum)) {
            $startnums = $startnum - 1;
        } else {
            $startnums = $startnum;
        }

        // echo $startnums;

        $output ['panel']  = $panel; // Panel HTML source.
        // $output['offset'] = $page_offset; //Current page number.
        $output ['offset'] = $startnums;
        $output ['limit']  = $limit_page; // Number of resuts per page.
        // $output['dates'] = array_slice($array, $page_offset, $limit_page, true); //Array of current page results.
        $output ['dates']  = array_slice($array, $startnums, $limit_page, true); // Array of current page results.

        return $output;
    }

    public function showEvents($args)
    {
        $setParams = array();
        $repo      = $this->entityManager->getRepository('ZSELEX_Entity_Event');

        // echo "<pre>"; print_r($_REQUEST); echo "</pre>";
        // $sess_item = SessionUtil::getVar('selectionfields');
        // $sess_item = $_SESSION['selectionfield'];
        // echo "<pre>"; print_r($sess_item); echo "</pre>"; exit;

        $level = FormUtil::getPassedValue("level");
        /*
         * $shop_id = FormUtil::getPassedValue("shop_id");
         * $country_id = FormUtil::getPassedValue("country_id");
         * $region_id = FormUtil::getPassedValue("region_id");
         * $city_id = FormUtil::getPassedValue("city_id");
         * $area_id = FormUtil::getPassedValue("area_id");
         * $branch_id = FormUtil::getPassedValue("branch_id");
         * $category_id = FormUtil::getPassedValue("category_id");
         */

        $shop_id   = isset($_REQUEST ['shop_id']) ? $_REQUEST ['shop_id'] : $_COOKIE ['shop_cookie'];
        $shop_name = '';
        if ($shop_id) {
            $shop_name       = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getSingleResult(array(
                'field' => 'shop_name',
                'where' => "a.shop_id=$shop_id"
            ));
            $shop_name_title = " - ".$shop_name;
        }
        PageUtil::setVar('title',
            $this->__("CityPilot - Events".$shop_name_title));
        $modvariable = $this->getVars();
        $country_id  = $modvariable ['default_country_id'];
        // $country_id = 61;
        // echo "<pre>"; print_r($_COOKIE); echo "</pre>";
        $region_id   = $_COOKIE ['region_cookie'];
        $city_id     = $_COOKIE ['city_cookie'];
        $area_id     = $_COOKIE ['area_cookie'];
        $branch_id   = $_COOKIE ['branch_cookie'];
        $category_id = $_COOKIE ['category_cookie'];
        $search      = $_COOKIE ["search_cookie"];
        // $search = FormUtil::getPassedValue("search");
        $eventlimit  = FormUtil::getPassedValue("eventlimit");
        $reset       = FormUtil::getPassedValue("reset");

        // echo "shop_id : " . $shop_id;

        $append = '';

        if ($country_id != '') {
            $country = "/country_id/".$country_id;
        }
        if ($region_id != '') {
            $region = "/region_id/".$region_id;
        }
        if ($city_id != '') {
            $city = "/city_id/".$city_id;
        }
        if ($area_id != '') {
            $area = "/area_id/".$area_id;
        }
        if ($shop_id != '') {
            $shop = "/shop_id/".$shop_id;
        }
        if ($category_id != '') {
            $cat = "/category_id/".$category_id;
        }
        if ($branch_id != '') {
            $branch = "/branch_id/".$branch_id;
        }
        if ($search != '') {
            $searchword = "/search/".$search;
        }

        $url = "showEvents".$country.$region.$city.$area.$shop.$cat.$branch.$searchword;

        // echo $url;
        // echo $reset; exit;
        if (!empty($eventlimit)) {
            $limit      = $eventlimit;
            $limitquery = "LIMIT 0 , $eventlimit";
        } else {
            $limit      = "2";
            $limitquery = "LIMIT 0 , 2";
        }

        $searchquery = '';

        // if (($shop_id > 0 || !empty($shop_id)) || ($region_id > 0 || !empty($region_id)) || ($city_id > 0 || !empty($city_id)) || ($country_id > 0 || !empty($country_id)) || ($area_id > 0 || !empty($area_id)) || ($search > 0 || !empty($search)) || ($category_id > 0 || !empty($category_id)) || ($branch_id > 0 || !empty($branch_id) || !empty($eventdate))) {
        // $eventdateqry = " AND shop_event_startdate>=CURDATE()";

        $output = '';
        $items  = array(
            'id' => $shop_id
        );
        // $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
        $where  = '';

        if (!empty($country_id)) { // COUNTRY
            $append .= " AND b.country_id=$country_id";
        }

        if (!empty($region_id)) { // REGION
            $append .= " AND b.region_id=$region_id";
        }

        if (!empty($city_id)) { // CITY
            $append .= " AND b.city_id=$city_id";
        }

        if (!empty($area_id)) { // AREA
            $append .= " AND b.area_id=$area_id";
        }

        if (!empty($shop_id)) { // SHOP
            $append .= " AND b.shop_id=$shop_id";
        }

        $join = "";
        if (!empty($category_id)) {
            $append .= " AND c.category_id=$category_id ";
            $join = " INNER JOIN zselex_shop_to_category c ON c.shop_id=b.shop_id ";
        }

        if (!empty($branch_id)) {
            $append .= " AND b.branch_id=$branch_id";
        }

        if (!empty($search)) {

            // $append .= " AND (a.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE '%" . DataUtil::formatForStore($search) . "%') OR a.shop_id IN (SELECT shop_id FROM zselex_shop WHERE shop_name LIKE '%" . DataUtil::formatForStore($search) . "%'))";
            $append .= " AND (b.shop_name LIKE :search OR MATCH (b.shop_name) AGAINST (:search2) OR b.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE :search OR MATCH (keyword) AGAINST (:search2)))";
            $setParams += array(
                'search' => '%'.DataUtil::formatForStore($search).'%',
                'search2' => DataUtil::formatForStore($search)
            );
        }

        $result = $repo->getAllEvents(array(
            'append' => $append,
            'join' => $join,
            'setParams' => $setParams
        ));

        // echo "<pre>"; print_r($result); echo "</pre>"; exit;
        // $result = $this->entityManager->getRepository('ZSELEX_Entity_Event')->getUpcomingEventShops(array('append' => $append, 'setParams' => $setParams, 'show_all' => true, 'upcommingEvents' => true));
        // echo "<pre>"; print_r($result); echo "</pre>";
        // $count = count($result);

        $count = $repo->getAllEventsCount(array(
            'append' => $append,
            'join' => $join,
            'setParams' => $setParams
        ));

        // echo "Count : " . $count;

        /*
         * $countNext = $repo->getAllEventsCount(
         * array('append' => $append,
         * 'join' => $join,
         * 'setParams' => $setParams,
         * 'start' => 10,
         * 'end' => 10
         * ));
         * $counts = $countNext;
         */

        // echo "Counts : " . $counts;
        $limit = 10;

        $this->view->assign('eventsCount', $eventsCount);
        $this->view->assign('count', $count);
        $this->view->assign('counts', $counts);

        $this->view->assign('totalcount', $c);

        $this->view->assign('limit', $limit);
        // $this->view->assign('events', $arrays);
        $this->view->assign('events', $result);
        // $this->view->assign('events', $pagination);
        // $this->view->assign('panel', $panel);
        // }

        return $this->view->fetch('user/allevents.tpl');
    }

    public function showEvents2($args)
    {
        $setParams = array();

        // echo "<pre>"; print_r($_REQUEST); echo "</pre>";
        // $sess_item = SessionUtil::getVar('selectionfields');
        // $sess_item = $_SESSION['selectionfield'];
        // echo "<pre>"; print_r($sess_item); echo "</pre>"; exit;

        $level = FormUtil::getPassedValue("level");
        /*
         * $shop_id = FormUtil::getPassedValue("shop_id");
         * $country_id = FormUtil::getPassedValue("country_id");
         * $region_id = FormUtil::getPassedValue("region_id");
         * $city_id = FormUtil::getPassedValue("city_id");
         * $area_id = FormUtil::getPassedValue("area_id");
         * $branch_id = FormUtil::getPassedValue("branch_id");
         * $category_id = FormUtil::getPassedValue("category_id");
         */

        $shop_id   = isset($_REQUEST ['shop_id']) ? $_REQUEST ['shop_id'] : $_COOKIE ['shop_cookie'];
        $shop_name = '';
        if ($shop_id) {
            $shop_name       = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getSingleResult(array(
                'field' => 'shop_name',
                'where' => "a.shop_id=$shop_id"
            ));
            $shop_name_title = " - ".$shop_name;
        }
        PageUtil::setVar('title',
            $this->__("CityPilot - Events".$shop_name_title));
        $modvariable = $this->getVars();
        $country_id  = $modvariable ['default_country_id'];
        // $country_id = 61;
        // echo "<pre>"; print_r($_COOKIE); echo "</pre>";
        $region_id   = $_COOKIE ['region_cookie'];
        $city_id     = $_COOKIE ['city_cookie'];
        $area_id     = $_COOKIE ['area_cookie'];
        $branch_id   = $_COOKIE ['branch_cookie'];
        $category_id = $_COOKIE ['category_cookie'];
        $search      = $_COOKIE ["search_cookie"];
        // $search = FormUtil::getPassedValue("search");
        $eventlimit  = FormUtil::getPassedValue("eventlimit");
        $reset       = FormUtil::getPassedValue("reset");

        // echo "shop_id : " . $shop_id;

        $append = '';

        if ($country_id != '') {
            $country = "/country_id/".$country_id;
        }
        if ($region_id != '') {
            $region = "/region_id/".$region_id;
        }
        if ($city_id != '') {
            $city = "/city_id/".$city_id;
        }
        if ($area_id != '') {
            $area = "/area_id/".$area_id;
        }
        if ($shop_id != '') {
            $shop = "/shop_id/".$shop_id;
        }
        if ($category_id != '') {
            $cat = "/category_id/".$category_id;
        }
        if ($branch_id != '') {
            $branch = "/branch_id/".$branch_id;
        }
        if ($search != '') {
            $searchword = "/search/".$search;
        }

        $url = "showEvents".$country.$region.$city.$area.$shop.$cat.$branch.$searchword;

        // echo $url;
        // echo $reset; exit;
        if (!empty($eventlimit)) {
            $limit      = $eventlimit;
            $limitquery = "LIMIT 0 , $eventlimit";
        } else {
            $limit      = "2";
            $limitquery = "LIMIT 0 , 2";
        }

        $searchquery = '';

        // if (($shop_id > 0 || !empty($shop_id)) || ($region_id > 0 || !empty($region_id)) || ($city_id > 0 || !empty($city_id)) || ($country_id > 0 || !empty($country_id)) || ($area_id > 0 || !empty($area_id)) || ($search > 0 || !empty($search)) || ($category_id > 0 || !empty($category_id)) || ($branch_id > 0 || !empty($branch_id) || !empty($eventdate))) {
        // $eventdateqry = " AND shop_event_startdate>=CURDATE()";

        $output = '';
        $items  = array(
            'id' => $shop_id
        );
        // $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
        $where  = '';

        if (!empty($country_id)) { // COUNTRY
            $append .= " AND a.country_id=$country_id";
        }

        if (!empty($region_id)) { // REGION
            $append .= " AND a.region_id=$region_id";
        }

        if (!empty($city_id)) { // CITY
            $append .= " AND a.city_id=$city_id";
        }

        if (!empty($area_id)) { // AREA
            $append .= " AND a.area_id=$area_id";
        }

        if (!empty($shop_id)) { // SHOP
            $append .= " AND a.shop_id=$shop_id";
        }

        if (!empty($category_id)) {
            // $append .= " AND a.cat_id=$category_id";
            $append .= " AND a.shop_id IN(SELECT shop_id FROM zselex_shop_to_category WHERE category_id=$category_id) ";
        }

        if (!empty($branch_id)) {
            $append .= " AND a.branch_id=$branch_id";
        }

        if (!empty($search)) {

            // $append .= " AND (a.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE '%" . DataUtil::formatForStore($search) . "%') OR a.shop_id IN (SELECT shop_id FROM zselex_shop WHERE shop_name LIKE '%" . DataUtil::formatForStore($search) . "%'))";
            $append .= " AND (a.shop_name LIKE :search OR MATCH (a.shop_name) AGAINST (:search2) OR a.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE :search OR MATCH (keyword) AGAINST (:search2)))";
            $setParams += array(
                'search' => '%'.DataUtil::formatForStore($search).'%',
                'search2' => DataUtil::formatForStore($search)
            );
        }

        /*
         * $sql = "SELECT shop_id FROM zselex_shop a
         * WHERE a.shop_id IS NOT NULL AND a.shop_id > 1 AND a.status='1' $append";
         * //echo $sql;
         * $query = DBUtil::executeSQL($sql);
         * $result = $query->fetchAll();
         */

        $result = $this->entityManager->getRepository('ZSELEX_Entity_Event')->getUpcomingEventShops(array(
            'append' => $append,
            'setParams' => $setParams,
            'show_all' => true,
            'upcommingEvents' => true
        ));

        // echo "<pre>"; print_r($result); echo "</pre>";
        $count = count($result);
        // /} else {
        // $count = 0;
        // }

        $shopsql = '';
        if ($count > 0) {
            foreach ($result as $shopid) {
                $shop_idarray [] = $shopid ['shop_id'];
            }

            $shop_ids = implode(",", $shop_idarray);
            // foreach ($result as $shop) {
            $shopsql  = " AND a.shop_id IN($shop_ids)";

            $shopquery1 = " AND a.shop IN($shop_ids)";
            if ($reset != 'reset') {
                $shopquery = $shopsql;
            } else {
                $shopquery = "";
            }

            // }
            $itemsperpage = FormUtil::getPassedValue('itemsperpage',
                    isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 2,
                    'GETPOST');
            // $startnum = FormUtil::getPassedValue('startnum', isset($args['startnum']) ? $args['startnum'] : 0, 'GETPOST');
            $this->view->assign('itemsperpage', $itemsperpage);
            // echo $shopquery;
            $eventdateqry = '';

            /*
             * $minmax = "SELECT MIN( shop_event_startdate ) as mindate , MAX( shop_event_enddate ) as maxdate
             * FROM zselex_shop_events a
             * WHERE a.shop_event_id IS NOT NULL AND UNIX_TIMESTAMP(a.shop_event_startdate) != 0 AND UNIX_TIMESTAMP(a.shop_event_enddate) != 0 AND a.status='1' " . " " . $shopquery . " ";
             * //echo $minmax;
             * $q = DBUtil::executeSQL($minmax);
             * $e = $q->fetch();
             */

            $e = $this->entityManager->getRepository('ZSELEX_Entity_Event')->getEventDates(array(
                'shopquery' => $shopquery1
            ));

            $mindate   = $e ['mindate'];
            $maxdate   = $e ['maxdate'];
            $mxdates   = array(
                "0" => $maxdate
            );
            $datearray = array();

            $datearray = $this->createDateRangeArray($mindate, $maxdate);
            $datearray = array_merge($datearray, $mxdates);

            // echo "<pre>"; print_r($datearray); echo "</pre>";

            $todayDate = date("Y-m-d");
            $c         = 0;
            foreach ($datearray as $a => $b) {
                if ($b < $todayDate) continue;
                // if($c < 7)continue;
                // echo $c . '<br>';

                $c ++;
            }

            // echo $c;

            $datecounts = sizeof($datearray);

            // echo "come here";
            $todayDate = date("Y-m-d");
            $i         = 1;
            $j         = 0;
            $k         = 0;
            // echo $startnum;
            $myEvents  = array();
            foreach ($datearray as $key => $d) {
                if ($d < $todayDate) continue;
                // echo $d . '<br>';
                // echo $k . '<br>';
                // if ($k == 2)
                // break;
                // $sql1 = "SELECT * FROM zselex_shop_events WHERE shop_event_enddate BETWEEN curdate() AND '" . $d . "'";

                /*
                 * $sql1 = "SELECT a.shop_event_id , a.shop_id , a.shop_event_name , a.shop_event_shortdescription , a.phone , a.email , a.price , a.shop_event_venue , c.uname
                 * FROM zselex_shop_events a
                 * LEFT JOIN zselex_shop_owners b ON a.shop_id=b.shop_id
                 * LEFT JOIN users c ON c.uid=b.user_id
                 * WHERE '" . $d . "' BETWEEN a.shop_event_startdate AND a.shop_event_enddate " . " " . $shopquery . " GROUP BY b.user_id";
                 */

                /*
                 * $sql1 = "SELECT * FROM zselex_shop_events a
                 * WHERE '" . $d . "' BETWEEN a.shop_event_startdate AND a.shop_event_enddate AND (a.activation_date<=CURDATE() OR UNIX_TIMESTAMP(a.activation_date) = 0) " . " " . $shopquery;
                 */

                /*
                 * $sql1 = "SELECT a.shop_event_id , a.shop_id , a.shop_event_name , a.shop_event_shortdescription , a.shop_event_description , a.shop_event_startdate , a.shop_event_starthour , a.shop_event_startminute , a.shop_event_enddate , a.shop_event_endhour , a.shop_event_endminute,a.price,a.email,a.phone,a.shop_event_venue,
                 * b.aff_id , c.aff_image
                 * FROM zselex_shop_events a
                 * LEFT JOIN zselex_shop b ON b.shop_id=a.shop_id
                 * LEFT JOIN zselex_shop_affiliation c ON c.aff_id=b.aff_id
                 * WHERE '" . $d . "' BETWEEN a.shop_event_startdate AND a.shop_event_enddate AND (a.activation_date<=CURDATE() OR UNIX_TIMESTAMP(a.activation_date) = 0 OR a.activation_date IS NULL) " . " " . $shopquery;
                 *
                 *
                 * // echo $sql1 . '<br>';
                 *
                 * $query1 = DBUtil::executeSQL($sql1);
                 * $events1 = $query1->fetchAll();
                 */

                $events1 = $this->entityManager->getRepository('ZSELEX_Entity_Event')->getEventBetweenDates(array(
                    'd' => $d,
                    'shopsql' => $shopquery1
                ));
                foreach ($events1 as $get => $owner) {
                    $ownerName                = ModUtil::apiFunc('ZSELEX',
                            'admin', 'getOwner',
                            $args                     = array(
                            'shop_id' => $owner ['shop_id']
                    ));
                    $events1 [$get] ['uname'] = $ownerName;
                }
                $arrays ['dates'] [$d] = $events1;
                // echo "<pre>"; print_r($events1); echo "</pre>";
                // $datearray[$key]['eventsname'] = 'hiii';
                $i ++;
                $j ++;
                $k ++;
            }
            // echo $j;
            // echo "<pre>"; print_r($arrays['dates']); echo "</pre>";
            $page = $_GET ['page'];

            // $pagination = $this->pagination_array($url, $arrays['dates'], $page, "/page/");
            // echo $pagination['panel'];
            // $panel = $pagination['panel'];
        }
        // echo "<pre>"; print_r($pagination); echo "</pre>";
        // echo "<pre>"; print_r($arrays); echo "</pre>";
        $limit  = 10;
        $counts = sizeof($arrays ['dates']);

        $eventsCount = sizeof($arrays);

        $this->view->assign('eventsCount', $eventsCount);
        $this->view->assign('count', $counts);

        $this->view->assign('totalcount', $c);

        $this->view->assign('limit', $limit);
        $this->view->assign('events', $arrays);
        // $this->view->assign('events', $pagination);
        // $this->view->assign('panel', $panel);
        // }

        return $this->view->fetch('user/allevents.tpl');
    }

    public function showEvents1($args)
    {

        // echo "<pre>"; print_r($_REQUEST); echo "</pre>";
        // $sess_item = SessionUtil::getVar('selectionfields');
        // $sess_item = $_SESSION['selectionfield'];
        // echo "<pre>"; print_r($sess_item); echo "</pre>"; exit;
        $level       = FormUtil::getPassedValue("level");
        $shop_id     = FormUtil::getPassedValue("shop_id");
        $country_id  = FormUtil::getPassedValue("country_id");
        $region_id   = FormUtil::getPassedValue("region_id");
        $city_id     = FormUtil::getPassedValue("city_id");
        $area_id     = FormUtil::getPassedValue("area_id");
        $branch_id   = FormUtil::getPassedValue("branch_id");
        $category_id = FormUtil::getPassedValue("category_id");
        $search      = FormUtil::getPassedValue("search");
        $eventlimit  = FormUtil::getPassedValue("eventlimit");
        $reset       = FormUtil::getPassedValue("reset");
        $append      = '';

        if ($country_id != '') {
            $country = "/country_id/".$country_id;
        }
        if ($region_id != '') {
            $region = "/region_id/".$region_id;
        }
        if ($city_id != '') {
            $city = "/city_id/".$city_id;
        }
        if ($area_id != '') {
            $area = "/area_id/".$area_id;
        }
        if ($shop_id != '') {
            $shop = "/shop_id/".$shop_id;
        }
        if ($category_id != '') {
            $cat = "/category_id/".$category_id;
        }
        if ($branch_id != '') {
            $branch = "/branch_id/".$branch_id;
        }
        if ($search != '') {
            $searchword = "/search/".$search;
        }

        $url = "showEvents".$country.$region.$city.$area.$shop.$cat.$branch.$searchword;

        // echo $reset; exit;
        if (!empty($eventlimit)) {

            $limit      = $eventlimit;
            $limitquery = "LIMIT 0 , $eventlimit";
        } else {
            $limit      = "2";
            $limitquery = "LIMIT 0 , 2";
        }

        $searchquery = '';

        // if (($shop_id > 0 || !empty($shop_id)) || ($region_id > 0 || !empty($region_id)) || ($city_id > 0 || !empty($city_id)) || ($country_id > 0 || !empty($country_id)) || ($area_id > 0 || !empty($area_id)) || ($search > 0 || !empty($search)) || ($category_id > 0 || !empty($category_id)) || ($branch_id > 0 || !empty($branch_id) || !empty($eventdate))) {
        // $eventdateqry = " AND shop_event_startdate>=CURDATE()";

        $output = '';
        $items  = array(
            'id' => $shop_id
        );
        // $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
        $where  = '';

        if (!empty($country_id)) { // COUNTRY
            $append .= " AND a.country_id=$country_id";
        }

        if (!empty($region_id)) { // REGION
            $append .= " AND a.region_id=$region_id";
        }

        if (!empty($city_id)) { // CITY
            $append .= " AND a.city_id=$city_id";
        }

        if (!empty($area_id)) { // AREA
            $append .= " AND a.area_id=$area_id";
        }

        if (!empty($shop_id)) { // SHOP
            $append .= " AND a.shop_id=$shop_id";
        }

        if (!empty($category_id)) {
            $append .= " AND a.cat_id=$category_id";
        }

        if (!empty($branch_id)) {
            $append .= " AND a.branch_id=$branch_id";
        }

        if (!empty($search)) {

            $append .= " AND a.shop_name LIKE '%".DataUtil::formatForStore($search)."%'
                             OR a.shop_id IN (SELECT shop_id FROM zselex_advertise WHERE keywords LIKE '%".DataUtil::formatForStore($search)."%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_files WHERE keywords LIKE '%".DataUtil::formatForStore($search)."%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_gallery WHERE keywords LIKE '%".DataUtil::formatForStore($search)."%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_pdf WHERE keywords LIKE '%".DataUtil::formatForStore($search)."%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_dotd WHERE keywords LIKE '%".DataUtil::formatForStore($search)."%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_products WHERE keywords LIKE '%".DataUtil::formatForStore($search)."%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_events WHERE shop_event_keywords 
                             LIKE '%".DataUtil::formatForStore($search)."%' OR shop_event_name LIKE '%".DataUtil::formatForStore($search)."%' OR shop_event_shortdescription LIKE '%".DataUtil::formatForStore($search)."%' OR shop_event_description LIKE '%".DataUtil::formatForStore($search)."%' OR shop_event_startdate LIKE '%".DataUtil::formatForStore($search)."%' OR shop_event_starthour LIKE '%".DataUtil::formatForStore($search)."%' OR shop_event_startminute LIKE '%".DataUtil::formatForStore($search)."%' OR shop_event_enddate LIKE '%".DataUtil::formatForStore($search)."%' OR shop_event_endhour LIKE '%".DataUtil::formatForStore($search)."%' OR shop_event_endminute LIKE '%".DataUtil::formatForStore($search)."%' OR shop_event_endminute LIKE '%".DataUtil::formatForStore($search)."%')     
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_news WHERE news_id IN 
                             (SELECT sid FROM news WHERE title LIKE '%".DataUtil::formatForStore($search)."%' OR hometext LIKE '%".DataUtil::formatForStore($search)."%' OR bodytext LIKE '%".DataUtil::formatForStore($search)."%' OR urltitle LIKE '%".DataUtil::formatForStore($search)."%'))
            ";
        }

        $sql    = "SELECT shop_id FROM zselex_shop a
                     WHERE a.shop_id IS NOT NULL AND a.status='1'  $append";
        // echo $sql;
        $query  = DBUtil::executeSQL($sql);
        $result = $query->fetchAll();

        $count = count($result);
        // /} else {
        // $count = 0;
        // }

        $shopsql = '';
        if ($count > 0) {

            foreach ($result as $shopid) {
                $shop_idarray [] = $shopid ['shop_id'];
            }

            $shop_ids = implode(",", $shop_idarray);
            // foreach ($result as $shop) {
            $shopsql  = " AND shop_id IN($shop_ids)";

            if ($reset != 'reset') {
                $shopquery = $shopsql;
            } else {
                $shopquery = "";
            }
        }
        $itemsperpage = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 2,
                'GETPOST');
        // $startnum = FormUtil::getPassedValue('startnum', isset($args['startnum']) ? $args['startnum'] : 0, 'GETPOST');
        $this->view->assign('itemsperpage', $itemsperpage);
        // echo $shopquery;
        $eventdateqry = '';

        $minmax = "SELECT MIN( shop_event_startdate ) as mindate , MAX( shop_event_enddate ) as maxdate
                   FROM zselex_shop_events WHERE shop_event_id IS NOT NULL AND status='1' "." ".$shopquery." ";
        $q      = DBUtil::executeSQL($minmax);
        $e      = $q->fetch();

        $mindate   = $e ['mindate'];
        $maxdate   = $e ['maxdate'];
        $mxdates   = array(
            "0" => $maxdate
        );
        $datearray = array();

        $datearray = $this->createDateRangeArray($mindate, $maxdate);
        $datearray = array_merge($datearray, $mxdates);

        // echo "<pre>"; print_r($datearray); echo "</pre>";

        $todayDate = date("Y-m-d");
        $c         = 0;
        foreach ($datearray as $a => $b) {
            if ($b < $todayDate) continue;
            // if($c < 7)continue;
            // echo $c . '<br>';

            $c ++;
        }

        // echo $c;

        $datecounts = sizeof($datearray);

        // echo "come here";
        $todayDate = date("Y-m-d");
        $i         = 1;
        $j         = 0;
        $k         = 0;
        // echo $startnum;

        foreach ($datearray as $key => $d) {
            if ($d < $todayDate) continue;
            // echo $d . '<br>';
            // echo $k . '<br>';
            // if ($k == 2)
            // break;
            // $sql1 = "SELECT * FROM zselex_shop_events WHERE shop_event_enddate BETWEEN curdate() AND '" . $d . "'";

            $sql1 = "SELECT shop_event_id , shop_id , shop_event_name
                     FROM zselex_shop_events 
                     WHERE '".$d."' BETWEEN shop_event_startdate AND shop_event_enddate "." ".$shopquery;
            // echo $sql1;

            $query1                = DBUtil::executeSQL($sql1);
            $events1               = $query1->fetchAll();
            $arrays ['dates'] [$d] = $events1;

            // echo "<pre>"; print_r($events1); echo "</pre>";
            // $datearray[$key]['eventsname'] = 'hiii';
            $i ++;
            $j ++;
            $k ++;
        }
        // echo $j;
        // echo "<pre>"; print_r($arrays['dates']); echo "</pre>";
        $page = $_GET ['page'];

        $pagination = $this->pagination_array($url, $arrays ['dates'], $page,
            "/page/");
        // echo $pagination['panel'];
        $panel      = $pagination ['panel'];

        // echo "<pre>"; print_r($pagination); echo "</pre>";
        // echo "<pre>"; print_r($arrays); echo "</pre>";
        $counts = sizeof($arrays ['dates']);

        $eventsCount = sizeof($arrays);

        $this->view->assign('eventsCount', $eventsCount);
        $this->view->assign('counts', $counts);

        $this->view->assign('totalcount', $c);

        // $this->view->assign('events', $arrays);
        $this->view->assign('events', $pagination);
        $this->view->assign('panel', $panel);

        // }

        return $this->view->fetch('user/allevents.tpl');
    }

    /**
     * Find Us - map
     *
     * @param init shop_id
     * @return void
     */
    function findus()
    {
        // echo "find us"; exit;
        $shopId = FormUtil::getPassedValue('shop_id', '', 'REQUEST');

        $shoptitle = FormUtil::getPassedValue('shoptitle', '', 'REQUEST');
        $shopTitle = DataUtil::formatForStore($shoptitle);

        if (isset($shopId) && !empty($shopId)) {
            $where = " a.shop_id=$shopId ";
        } else {
            $where = " a.urltitle='$shoptitle'";
        }

        // echo $where; exit;

        /*
          $shop_name = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getSingleResult(array(
          'field' => 'shop_name',
          //'where' => "a.shop_id=$shop_id AND a.status=1"
          'where' => $where
          ));
         */
        $item = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getMinisite(array(
            'shop_id' => $shopId,
            'title' => $shopTitle
        ));

        //  echo "<pre>"; print_r($item); echo "</pre>"; exit;
        //  echo $shop_name; exit;
        if (!$item) {
            //  echo "find us"; exit;
            return LogUtil::registerError($this->__('Error! Site not found.'),
                    404);
        }
        $shopId  = $item['shop_id'];
        System::queryStringSetVar('shopName', $item['shop_name']);
        System::queryStringSetVar('shop_id', $item['shop_id']);
        System::queryStringSetVar('city_name', $item['city_name']);
        PageUtil::setVar('title', $this->__("Find Us"." - ".$item['shop_name']));
        $user_id = UserUtil::getVar('uid');
        $perm    = ModUtil::apiFunc('ZSELEX', 'admin', 'shopPermission',
                array(
                'shop_id' => $shopId,
                'user_id' => $user_id
        ));
        System::queryStringSetVar('perm', $perm);
        // return "";
        return $this->view->fetch('user/findus.tpl');
    }

    function myRedirect()
    {
        // echo "comes here"; exit;
        // $facebook = ModUtil::apiFunc('FConnect', 'Facebook', 'facebook');
        // $facebook->destroySession();
        require_once 'modules/FConnect/lib/vendor/Facebook/facebook.php';
        $settings = ModUtil::getVar('FConnect');
        $facebook = new Facebook(array(
            'appId' => $settings ['appid'],
            'secret' => $settings ['secretkey']
        ));
        $facebook->destroySession();
        // echo $facebook->getAppId(); exit;
        setcookie('fbsr_'.$facebook->getAppId(), '', time() - 604800, '/',
            '.'.$_SERVER ['SERVER_NAME']);
        setcookie('fbsr_'.$facebook->getAppId(), '', time() - 604800, '/');
        session_destroy();
        $loginUrl = ModUtil::url('Users', 'user', 'main');
        $this->redirect($loginUrl);
    }
}
// end class def
?>
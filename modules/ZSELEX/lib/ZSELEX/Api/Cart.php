<?php
/**
 * ZSELEX_Api_Cart
 *
 * Api class which contains cart related methods
 * 
 */

/**
 * Class to control User interface
 */
class ZSELEX_Api_Cart extends Zikula_AbstractApi
{

    function insertOrderItems($args)
    {
        $sql       = "INSERT INTO zselex_orderitems (product_id , shop_id , order_id , quantity , price , total)
            VALUES('".$args ['product_id']."' , '".$args ['shop_id']."' , '".$args ['order_id']."' ,'".$args ['quantity']."' , '".$args ['price']."' ,  '".$args ['total']."')";
        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);
        return true;
    }

    public function carttotal()
    {
        $finalCookie = array();
        $total       = 0;
        $qty         = 0;


        $user_id = UserUtil::getVar('uid');
        if (!UserUtil::isLoggedIn()) {
            $user_id = ZSELEX_Util::getTempUserId();
        }

        $get_cart = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->getUserCartTotal(array(
            "user_id" => $user_id
        ));
        // echo "<pre>"; print_r($get_cart); echo "</pre>";

        if (!empty($get_cart)) {
            $total = $get_cart ['total'];
            $qty   = $get_cart ['qty'];
        }
        return array(
            'total' => $total,
            'count' => $qty
        );
    }

    public function carttotal1()
    {
        $finalCookie = array();
        $total       = 0;
        $qty         = 0;
        if (UserUtil::isLoggedIn()) {

            $user_id = UserUtil::getVar('uid');

            $get_cart = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->getUserCartTotal(array(
                "user_id" => $user_id
            ));
            // echo "<pre>"; print_r($get_cart); echo "</pre>";
            /*
             * if (!empty($get_products)) {
             * $c = 0;
             * foreach ($get_products as $k => $vl) {
             *
             * $total+=$vl['final_price'];
             * $qty+=$vl['quantity'];
             * $c++;
             * }
             * }
             */
            if (!empty($get_cart)) {
                $total = $get_cart ['total'];
                $qty   = $get_cart ['qty'];
            }
            return array(
                'total' => $total,
                'count' => $qty
            );
        } else {

            $s      = 0;
            $c      = 0;
            $cq     = 0;
            $ctotal = 0;
            $sq     = 0;
            $stotal = 0;
            if (count(json_decode($_COOKIE ['temp_cart'], true)) > 0) {

                $cookieCart = json_decode($_COOKIE ['temp_cart'], true);
                foreach ($cookieCart as $key => $val) {
                    foreach ($val as $key1 => $val1) {

                        $ctotal += $val1 ['final_price'];
                        $cq += $val1 ['quantity'];
                        $c ++;
                    }
                }
            }

            // $_SESSION['temp_cart'] =

            if (count($_SESSION ['temp_cart']) > 0) {

                foreach ($_SESSION ['temp_cart'] as $key => $val) {
                    foreach ($val as $key2 => $val2) {
                        $stotal += $val2 ['final_price'];
                        $sq += $val2 ['quantity'];
                        $s ++;
                    }
                }
            }

            // echo "cookie : " . $c . '<br>';
            // echo "session : " . $s . '<br>';

            $sessionCount = $sq;
            $cookieCount  = $cq;
            if (($sessionCount > $cookieCount) || ($sessionCount == $cookieCount)) {
                // echo "session";
                $total = $stotal;
                $count = $sq;
            } else {
                // echo "cokkie";
                $total = $ctotal;
                $count = $cq;
            }
            $arr = array(
                'total' => $total,
                'count' => $count
            );
            // echo "<pre>"; print_r($arr); echo "</pre>";

            return $arr;
        }

        // return $final_total;
        // return $total;
        // return array('total' => $total, 'count' => $c1);
    }

    /**
     * validate and update the user cart
     *
     * @access public
     * @param array $args
     *        	null
     * @return boolean
     */
    public function validatecart($args)
    {

        //echo "<pre>"; print_r($args); echo "</pre>";
        // return true;
        // echo "validatecart";  exit;
        $changed = false;
        $error   = false;
        //$user_id = UserUtil::getVar('uid');
        $user_id = UserUtil::getVar('uid');
        if (!UserUtil::isLoggedIn()) {
            $user_id = ZSELEX_Util::getTempUserId();
        }

        $cartShopId = $args['cart_shop_id'];

        //echo "User ID :" . $user_id;

        $fields    = array(
            'a.cart_id',
            'a.quantity',
            'b.product_id',
            'c.shop_id',
            'a.price',
            'a.final_price',
            'a.cart_content',
            'a.outofstock'
        );
        $setParams = array(
            'uid' => $user_id
        );
        $where     = "a.user_id=:uid";
        $cart      = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->getCartProducts(array(
            'user_id' => $user_id,
            'fields' => $fields,
            'where' => $where,
            'setParams' => $setParams
        ));
        if (empty($cart)) {
            return true;
        }
        // echo "comes here";  exit;
        // echo "<pre>"; print_r($cart); echo "</pre>";

        foreach ($cart as $key => $val) {
            $outofstock      = 0;
            $discountApplied = 0;
            $product         = $this->entityManager->getRepository('ZSELEX_Entity_Product')->getProduct(array(
                'product_id' => $val ['product_id']
            ));
            // echo "<pre>"; print_r($product); echo "</pre>";
            // $product['prd_price'] = $this->getDiscount(array('product_id' => $product['product_id'], 'price' => $product['prd_price'], 'discount' => $product['discount']));
            if ($product == false || empty($product)) {

                $cartObj = $this->entityManager->find('ZSELEX_Entity_Cart',
                    $val ['cart_id']);
                $this->entityManager->remove($cartObj);
                $this->entityManager->flush();
                $changed = true;
                $error   = true;
            } else {

                $getDiscountPrice = $this->getDiscount(array(
                    'product_id' => $product ['product_id'],
                    'price' => $product ['prd_price'],
                    'discount' => $product ['discount']
                ));
                /*
                  $product ['prd_price'] = $this->getDiscount(array(
                  'product_id' => $product ['product_id'],
                  'price' => $product ['prd_price'],
                  'discount' => $product ['discount']
                  ));
                 */
                if ($getDiscountPrice['applied']) {
                    $discountApplied = 1;
                }
                $product ['prd_price'] = $getDiscountPrice['price'];
                $total                 = 0;
                // if ($val['cart_content'] != '') {
                // $options = json_decode($val['cart_content'], true);
                $options               = unserialize($val ['cart_content']);
                // echo "<pre>"; print_r($options); echo "</pre>";
                $new_price             = false;
                if (!empty($options)) {
                    foreach ($options as $ov) {
                        $fields     = array(
                            'a.product_to_options_value_id',
                            'a.price',
                            'a.qty',
                            'a.price_prefix'
                        );
                        $value_info = $this->entityManager->getRepository('ZSELEX_Entity_ProductToOption')->getProductToOptionValue(array(
                            'id' => $ov ['valueID'],
                            'fields' => $fields
                        ));
                        $total += $value_info ['price'];
                        $pref       = $value_info ['price_prefix'];

                        if ($pref != '+' && $pref != '-' && $value_info ['price']
                            > 0) {
                            $new_price = true;
                        }

                        // ////////////////////////
                        if ($value_info ['qty'] < $val ['quantity']) {
                            $outofstock  = 1;
                            $priceChange = true;
                            $error       = true;
                        }
                        $stock = $value_info ['qty'];
                        // ////////////////////////
                    }
                } else {
                    if ($product ['prd_quantity'] < $val ['quantity']) {
                        $outofstock  = 1;
                        $priceChange = true;
                        $error       = true;
                    }
                    $stock = $product ['prd_quantity'];
                }
                // }
                //
	             $price = 0;
                if ($new_price == true) {
                    $final_price = $total * $val ['quantity'];
                    $price       = $total;
                } else {
                    $final_price = ($product ['prd_price'] + $total) * $val ['quantity'];
                    $price       = $product ['prd_price'] + $total;
                }

                // echo $final_price;
                // Apply quantiy discounts
                $qty_discount_exist = $this->entityManager->getRepository('ZSELEX_Entity_Product')->getCount(null,
                    'ZSELEX_Entity_QuantityDiscount', 'discount_id',
                    array(
                    'a.product' => $product ['product_id']
                ));
                if ($qty_discount_exist) {
                    $quantity_discount = $this->_applyQtyDiscount(array(
                        'product_id' => $val ['product_id'],
                        'cart_qty' => $val ['quantity'],
                        'cart_total' => $final_price
                    ));
                    // echo "quantity_discount :" .$quantity_discount; exit;
                    if ($quantity_discount > 0) {
                        $final_price     = $quantity_discount;
                        $discountApplied = 1;
                    }
                }
                $update_item = array(
                    'product' => $val ['product_id'],
                    'cart_content' => $val ['cart_content'],
                    'original_price' => $product ['original_price'],
                    'price' => $price,
                    'options_price' => $total,
                    // 'final_price' => ($product['prd_price'] + $total) * $val['quantity'],
                    'final_price' => $final_price,
                    'outofstock' => $outofstock,
                    'stock' => $stock,
                    'discount_applied' => $discountApplied
                );
                // echo "<pre>"; print_r($update_item); echo "</pre>";

                $upd_args = array(
                    'entity' => 'ZSELEX_Entity_Cart',
                    'fields' => $update_item,
                    'where' => array(
                        'a.cart_id' => $val ['cart_id']
                    )
                    )
                // 'where' => "a.cart_id=:cart_id"
                ;
                $update   = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->updateEntity($upd_args);
                // echo $update ; exit;
                if ($update == true) {
                    // $changed = true;
                }
            }
        }
        // exit;
        $errorMsg = '';
        if (!$cartShopId) {
            if ($changed) {
                // LogUtil::registerStatus($this->__('Your cart has been modified.Some products are deleted'));
                $errorMsg .= $this->__('Your cart has been modified.Some products are deleted')."\n";
            }
            if ($priceChange) {
                // LogUtil::registerStatus($this->__('Your cart has been modified.Some products are deleted'));
                $errorMsg .= $this->__('Products marked with *** are not available in the desired quantity or not in stock!')."\n";
            }
            if ($error) {
                LogUtil::registerError(nl2br($errorMsg));
            }
        }

        if ($cartShopId) {
            return $error;
        }
        return true;
    }

    public function validatecart_old($args)
    {
        // return true;
        // echo "validatecart";  exit;
        $changed = false;
        //$user_id = UserUtil::getVar('uid');
        $user_id = UserUtil::getVar('uid');
        if (!UserUtil::isLoggedIn()) {
            $user_id = ZSELEX_Util::getTempUserId();
        }

        //echo "User ID :" . $user_id;

        $fields    = array(
            'a.cart_id',
            'a.quantity',
            'b.product_id',
            'c.shop_id',
            'a.price',
            'a.final_price',
            'a.cart_content',
            'a.outofstock'
        );
        $setParams = array(
            'uid' => $user_id
        );
        $where     = "a.user_id=:uid";
        $cart      = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->getCartProducts(array(
            'user_id' => $user_id,
            'fields' => $fields,
            'where' => $where,
            'setParams' => $setParams
        ));
        if (empty($cart)) {
            return true;
        }
        // echo "comes here";  exit;
        //echo "<pre>"; print_r($cart); echo "</pre>";

        foreach ($cart as $key => $val) {
            $outofstock      = 0;
            $discountApplied = 0;
            $product         = $this->entityManager->getRepository('ZSELEX_Entity_Product')->getProduct(array(
                'product_id' => $val ['product_id']
            ));
            // echo "<pre>"; print_r($product); echo "</pre>";
            // $product['prd_price'] = $this->getDiscount(array('product_id' => $product['product_id'], 'price' => $product['prd_price'], 'discount' => $product['discount']));
            if ($product == false || empty($product)) {

                $cartObj = $this->entityManager->find('ZSELEX_Entity_Cart',
                    $val ['cart_id']);
                $this->entityManager->remove($cartObj);
                $this->entityManager->flush();
                $changed = true;
                $error   = true;
            } else {

                $getDiscountPrice = $this->getDiscount(array(
                    'product_id' => $product ['product_id'],
                    'price' => $product ['prd_price'],
                    'discount' => $product ['discount']
                ));
                /*
                  $product ['prd_price'] = $this->getDiscount(array(
                  'product_id' => $product ['product_id'],
                  'price' => $product ['prd_price'],
                  'discount' => $product ['discount']
                  ));
                 */
                if ($getDiscountPrice['applied']) {
                    $discountApplied = 1;
                }
                $product ['prd_price'] = $getDiscountPrice['price'];
                $total                 = 0;
                // if ($val['cart_content'] != '') {
                // $options = json_decode($val['cart_content'], true);
                $options               = unserialize($val ['cart_content']);
                // echo "<pre>"; print_r($options); echo "</pre>";
                $new_price             = false;
                if (!empty($options)) {
                    foreach ($options as $ov) {
                        $fields     = array(
                            'a.product_to_options_value_id',
                            'a.price',
                            'a.qty',
                            'a.price_prefix'
                        );
                        $value_info = $this->entityManager->getRepository('ZSELEX_Entity_ProductToOption')->getProductToOptionValue(array(
                            'id' => $ov ['valueID'],
                            'fields' => $fields
                        ));
                        $total += $value_info ['price'];
                        $pref       = $value_info ['price_prefix'];

                        if ($pref != '+' && $pref != '-' && $value_info ['price']
                            > 0) {
                            $new_price = true;
                        }

                        // ////////////////////////
                        if ($value_info ['qty'] < $val ['quantity']) {
                            $outofstock  = 1;
                            $priceChange = true;
                            $error       = true;
                        }
                        $stock = $value_info ['qty'];
                        // ////////////////////////
                    }
                } else {
                    if ($product ['prd_quantity'] < $val ['quantity']) {
                        $outofstock  = 1;
                        $priceChange = true;
                        $error       = true;
                    }
                    $stock = $product ['prd_quantity'];
                }
                // }
                //
	             $price = 0;
                if ($new_price == true) {
                    $final_price = $total * $val ['quantity'];
                    $price       = $total;
                } else {
                    $final_price = ($product ['prd_price'] + $total) * $val ['quantity'];
                    $price       = $product ['prd_price'] + $total;
                }

                // echo $final_price;
                // Apply quantiy discounts
                $qty_discount_exist = $this->entityManager->getRepository('ZSELEX_Entity_Product')->getCount(null,
                    'ZSELEX_Entity_QuantityDiscount', 'discount_id',
                    array(
                    'a.product' => $product ['product_id']
                ));
                if ($qty_discount_exist) {
                    $quantity_discount = $this->_applyQtyDiscount(array(
                        'product_id' => $val ['product_id'],
                        'cart_qty' => $val ['quantity'],
                        'cart_total' => $final_price
                    ));
                    // echo "quantity_discount :" .$quantity_discount; exit;
                    if ($quantity_discount > 0) {
                        $final_price     = $quantity_discount;
                        $discountApplied = 1;
                    }
                }
                $update_item = array(
                    'product' => $val ['product_id'],
                    'cart_content' => $val ['cart_content'],
                    'original_price' => $product ['original_price'],
                    'price' => $price,
                    'options_price' => $total,
                    // 'final_price' => ($product['prd_price'] + $total) * $val['quantity'],
                    'final_price' => $final_price,
                    'outofstock' => $outofstock,
                    'stock' => $stock,
                    'discount_applied' => $discountApplied
                );
                // echo "<pre>"; print_r($update_item); echo "</pre>";

                $upd_args = array(
                    'entity' => 'ZSELEX_Entity_Cart',
                    'fields' => $update_item,
                    'where' => array(
                        'a.cart_id' => $val ['cart_id']
                    )
                    )
                // 'where' => "a.cart_id=:cart_id"
                ;
                $update   = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->updateEntity($upd_args);
                // echo $update ; exit;
                if ($update == true) {
                    // $changed = true;
                }
            }
        }
        // exit;
        $errorMsg = '';
        if ($changed) {
            // LogUtil::registerStatus($this->__('Your cart has been modified.Some products are deleted'));
            $errorMsg .= $this->__('Your cart has been modified.Some products are deleted')."\n";
        }
        if ($priceChange) {
            // LogUtil::registerStatus($this->__('Your cart has been modified.Some products are deleted'));
            $errorMsg .= $this->__('Products marked with *** are not available in the desired quantity or not in stock!')."\n";
        }
        if ($error) {
            LogUtil::registerError(nl2br($errorMsg));
        }
        return true;
    }

    /**
     * validate and update guest cart in session
     *
     * Get the cart items form the session cookie
     *
     * @param array $args
     *        	null
     * @return boolean
     */
    public function validatecartGuest($args)
    {
        // return true;
        $changed = false;
        $user_id = UserUtil::getVar('uid');
        // $tempCart = $_COOKIE['temp_cart'];
        // $cart = json_decode($_COOKIE['temp_cart'], true);
        $cart    = $this->getTempCart();
        // echo "<pre>"; print_r($cart); echo "</pre><br>"; exit;

        $_SESSION ['temp_cart'] = $cart;
        if (empty($cart)) {
            return true;
        }

        foreach ($cart as $keys => $shop_id) {
            $shop_id_value = key($cart);
            foreach ($shop_id as $key => $val) {
                // echo key($cart) . '<br>';
                // echo "<pre>"; print_r($val); echo "</pre><br>";
                $outofstock = 0;
                $product    = $this->entityManager->getRepository('ZSELEX_Entity_Product')->getProduct(array(
                    'product_id' => $val ['product_id']
                ));
                // echo "<pre>"; print_r($product); echo "</pre><br>";

                if ($product == false || empty($product)) {
                    // DBUtil::deleteWhere('zselex_cart', "cart_id=$val[cart_id]");

                    unset($_SESSION ['temp_cart'] [$shop_id_value] [$key]);
                    $changed = true;
                    $error   = true;
                } else {
                    $product ['prd_price'] = $this->getDiscount(array(
                        'product_id' => $product ['product_id'],
                        'price' => $product ['original_price'],
                        'discount' => $product ['discount']
                    ));
                    $total                 = 0;
                    // if ($val['cart_content'] != '') {
                    // $options = json_decode($val['cart_content'], true);
                    $options               = unserialize($val ['cart_content']);
                    // echo "<pre>"; print_r($options); echo "</pre>"; exit;
                    $new_price             = false;
                    if (!empty($options)) {
                        foreach ($options as $ov) {
                            $fields     = array(
                                'a.product_to_options_value_id',
                                'a.price',
                                'a.qty',
                                'a.price_prefix'
                            );
                            $value_info = $this->entityManager->getRepository('ZSELEX_Entity_ProductToOption')->getProductToOptionValue(array(
                                'id' => $ov ['valueID'],
                                'fields' => $fields
                            ));
                            $total += $value_info ['price'];
                            $pref       = $value_info ['price_prefix'];
                            if ($pref != '+' && $pref != '-' && $value_info ['price']
                                > 0) {
                                $new_price = true;
                            }

                            // ////////////////////////
                            if ($value_info ['qty'] < $val ['quantity']) {
                                $outofstock  = 1;
                                $priceChange = true;
                                $error       = true;
                            }
                            // ////////////////////////
                        }
                    } else {
                        if ($product ['prd_quantity'] < $val ['quantity']) {
                            $outofstock  = 1;
                            $priceChange = true;
                            $error       = true;
                        }
                    }
                    // }
                    //
					
					// echo $_SESSION['temp_cart'][$shop_id][$key]['prd_price'];
                    // $_SESSION['temp_cart'][203][0]['prd_price'];
                    // echo $key . '<br>';
                    $price = 0;
                    if ($new_price) {
                        $final_price = $total * $val ['quantity'];
                        $price       = $total;
                    } else {
                        $final_price = ($product ['prd_price'] + $total) * $val ['quantity'];
                        $price       = $product ['prd_price'] + $total;
                    }
                    $quantity_discount = $this->_applyQtyDiscount(array(
                        'product_id' => $val ['product_id'],
                        'cart_qty' => $val ['quantity'],
                        'cart_total' => $final_price
                    ));
                    // echo "quantity_discount :" .$quantity_discount; exit;
                    if ($quantity_discount > 0) {
                        $final_price = $quantity_discount;
                    }
                    @$_SESSION ['temp_cart'] [$shop_id_value] [$key] ['prd_price']
                        = $product ['original_price'];
                    @$_SESSION ['temp_cart'] [$shop_id_value] [$key] ['price']           = $price;
                    @$_SESSION ['temp_cart'] [$shop_id_value] [$key] ['final_price']
                        = $final_price;
                    @$_SESSION ['temp_cart'] [$shop_id_value] [$key] ['outofstock']
                        = $outofstock;
                    @$_SESSION ['temp_cart'] [$shop_id_value] [$key] ['prd_question']
                        = $product ['prd_question'];
                    @$_SESSION ['temp_cart'] [$shop_id_value] [$key] ['enable_question']
                        = $product ['enable_question'];

                    if ($update == true) {
                        // $changed = true;
                    }
                }
            }
        }

        $cookieEncode = json_encode($_SESSION ['temp_cart']);
        setcookie("temp_cart", $cookieEncode, time() + 604800, '/');
        // exit;
        $errorMsg     = '';
        if ($changed) {
            // LogUtil::registerStatus($this->__('Your cart has been modified.Some products are deleted'));
            $errorMsg .= $this->__('Your cart has been modified.Some products are deleted')."\n";
        }
        if ($priceChange) {
            // LogUtil::registerStatus($this->__('Your cart has been modified.Some products are deleted'));
            $errorMsg .= $this->__('Products marked with *** are not available in the desired quantity or not in stock!')."\n";
        }
        if ($error) {
            LogUtil::registerError(nl2br($errorMsg));
        }
        return true;
    }

    public function getTempCart()
    {
        $temp_cart   = array();
        $sessionCart = array();
        $cookieCart  = array();
        $s           = 0;
        $c           = 0;
        $cq          = 0;
        $ctotal      = 0;
        $sq          = 0;
        $stotal      = 0;
        $cookieCart  = json_decode($_COOKIE ['temp_cart'], true);
        if (count($cookieCart) > 0) {
            $cookieCart = json_decode($_COOKIE ['temp_cart'], true);
            foreach ($cookieCart as $key => $val) {
                foreach ($val as $key1 => $val1) {
                    $cq += $val1 ['quantity'];
                    $c ++;
                }
            }
        }

        $sessionCart = $_SESSION ['temp_cart'];
        if (count($sessionCart) > 0) {
            foreach ($_SESSION ['temp_cart'] as $key => $val) {
                foreach ($val as $key2 => $val2) {
                    $sq += $val2 ['quantity'];
                    $s ++;
                }
            }
        }

        $sessionCount = $sq;
        $cookieCount  = $cq;
        if (($sessionCount > $cookieCount) || ($sessionCount == $cookieCount)) {
            $temp_cart = $sessionCart;
        } else {
            $temp_cart = $cookieCart;
        }
        if (!empty($temp_cart)) {
            foreach ($temp_cart as $key => $val) {
                if (empty($val)) {
                    unset($temp_cart [$key]);
                    // echo "empty";
                }
            }
        }
        return $temp_cart;
    }

    /**
     * Update product quantity after successfull purchase
     * 
     * @param array $args
     * @return boolean
     */
    public function updateProductOptions($args)
    {
        $order_id   = $args ['order_id'];
        $order_info = ModUtil::apiFunc('ZSELEX', 'user', 'getOrderDetails',
                $args       = array(
                'order_id' => $order_id
        ));

        // echo "<pre>"; print_r($order_info); echo "</pre>"; exit;
        foreach ($order_info as $val) {
            $buyed_qty       = $val ['quantity'];
            // if (!empty($val['product_options'])) {
            // $product_options = json_decode($val['product_options'], true);
            $product_options = unserialize($val ['product_options']);
            if (!empty($product_options)) {
                foreach ($product_options as $option_val) {
                    $fields         = array(
                        'a.product_to_options_value_id',
                        'a.price',
                        'a.qty'
                    );
                    $get_option_val = $this->entityManager->getRepository('ZSELEX_Entity_ProductToOption')->getProductToOptionValue(array(
                        'id' => $option_val ['valueID'],
                        'fields' => $fields
                    ));
                    $orig_qty       = $get_option_val ['qty'];
                    $update_item    = array(
                        'qty' => $orig_qty - $buyed_qty
                    );
                    // echo "<pre>"; print_r($update_item); echo "</pre>";

                    $upd_args = array(
                        'entity' => 'ZSELEX_Entity_ProductToOptionValue',
                        'fields' => $update_item,
                        'where' => array(
                            'a.product_to_options_value_id' => $option_val ['valueID']
                        )
                        )
                    // 'where' => "a.cart_id=:cart_id"
                    ;
                    $update   = $this->entityManager->getRepository('ZSELEX_Entity_Order')->updateEntity($upd_args);
                }
            } else {

                $getArgs = array(
                    'entity' => 'ZSELEX_Entity_Product',
                    'fields' => array(
                        'a.prd_quantity'
                    ),
                    'where' => array(
                        'a.product_id' => $val ['product_id']
                    )
                );
                // echo "<pre>"; print_r($getArgs); echo "</pre>"; exit;

                $get_prod_qty = $this->entityManager->getRepository('ZSELEX_Entity_Product')->get($getArgs);

                $update_item2 = array(
                    'prd_quantity' => $get_prod_qty ['prd_quantity'] - $buyed_qty
                );

                $upd_args2 = array(
                    'entity' => 'ZSELEX_Entity_Product',
                    'fields' => $update_item2,
                    'where' => array(
                        'a.product_id' => $val ['product_id']
                    )
                );

                $update2 = $this->entityManager->getRepository('ZSELEX_Entity_Product')->updateEntity($upd_args2);
            }
            // }
        }

        return true;
    }

    /**
     * UpdateFromGuestCart
     *
     * @return void
     */
    public function updateFromGuestCart()
    {

        $tempUserId = ZSELEX_Util::getTempUserId();
        $repo       = $this->entityManager->getRepository('ZSELEX_Entity_Cart');

        $tempUserIdExistInCart = $repo->getCount(null, 'ZSELEX_Entity_Cart',
            'cart_id', array(
            'a.user_id' => $tempUserId
        ));

        if ($tempUserIdExistInCart) {
            $userId = UserUtil::getVar('uid');
            $item   = array(
                'user_id' => $userId,
                'is_guest' => 0
            );
            $repo->updateEntity(null, 'ZSELEX_Entity_Cart', $item,
                array(
                'a.user_id' => $tempUserId
            ));
        }
    }

    /**
     * Update Cart
     *
     * Insert the products from the guest cart to user cart when user
     * logs in.
     *
     * @uses clearCart()
     * @return void
     */
    public function updateFromGuestCart1()
    {
        $user_id       = UserUtil::getVar('uid');
        $cart_products = $_SESSION ['temp_cart'];
        $total         = 0;
        if (!empty($cart_products)) {
            // echo "<pre>"; print_r($cart_products); echo "</pre>"; exit;
            foreach ($cart_products as $shop_id => $value) {
                foreach ($value as $k => $v) {
                    // echo "<pre>"; print_r($v); echo "</pre>";
                    $repo = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
                    $cart = $repo->get(array(
                        'entity' => 'ZSELEX_Entity_Cart',
                        'where' => array(
                            'a.user_id' => $user_id,
                            'a.shop' => $shop_id,
                            'a.product' => $v ['product_id'],
                            'a.cart_content' => $v ['options']
                        )
                    ));

                    $product = $repo->get(array(
                        'entity' => 'ZSELEX_Entity_Product',
                        'where' => array(
                            'a.product_id' => $v ['product_id']
                        )
                    ));

                    $option_array = json_decode($v ['options'], true);
                    if (!empty($option_array)) {
                        foreach ($option_array as $ov) {

                            $fields     = array(
                                'a.product_to_options_value_id',
                                'a.price',
                                'a.qty'
                            );
                            $value_info = $this->entityManager->getRepository('ZSELEX_Entity_ProductToOption')->getProductToOptionValue(array(
                                'id' => $ov ['valueID'],
                                'fields' => $fields
                            ));
                            // echo "<pre>"; print_r($value_info); echo "</pre>";

                            $total += $value_info ['price'];
                        }
                    } else {
                        $total = 0;
                    }

                    // exit;
                    if (count($cart) < 1) { // not exist
                        $cartItem = new ZSELEX_Entity_Cart ();
                        $cartItem->setUser_id($user_id);
                        $product  = $this->entityManager->find('ZSELEX_Entity_Product',
                            $v ['product_id']);
                        $cartItem->setProduct($product);
                        $shop     = $this->entityManager->find('ZSELEX_Entity_Shop',
                            $shop_id);
                        $cartItem->setShop($shop);
                        $cartItem->setQuantity($v ['quantity']);
                        $cartItem->setOriginal_price($product ['prd_price']);
                        $cartItem->setPrice($v ['price']);
                        // $cartItem->setOptions_price($item['options_price']);
                        $cartItem->setFinal_price($product ['prd_price'] + $total);
                        $cartItem->setCart_content(stripslashes($v ['options']));
                        $cartItem->setPrd_answer($v ['prd_answer']);
                        // $cartItem->setOutofstock(0);

                        $this->entityManager->persist($cartItem);
                        $this->entityManager->flush();
                    } else {
                        $obj = array(
                            'cart_content' => $v ['options'],
                            'quantity' => $cart ['quantity'] + 1,
                            'original_price' => $product ['prd_price'],
                            'price' => $v ['price'],
                            'prd_answer' => $v ['prd_answer'],
                            'final_price' => ($product ['prd_price'] + $total) * $cart ['quantity']
                        );
                        // DBUTil::updateObject($obj, 'zselex_cart', $where = "user_id=$user_id AND shop_id=$shop_id AND product_id=$v[product_id] AND cart_content='" . $v[options] . "'");
                        $repo->updateEntity(null, 'ZSELEX_Entity_Cart', $obj,
                            array(
                            'a.user_id' => $user_id,
                            'a.shop' => $shop_id,
                            'a.product' => $v ['product_id'],
                            'a.cart_content' => $v ['options']
                        ));
                    }
                }
            }
        }
        ZSELEX_Controller_User::clearCart();
        return true;
    }

    function unsetCartMenu($items)
    {
        if (isset($_SESSION ['cart_menu'])) {
            foreach ($items as $key => $value) {
                // if ($value == $item) {
                if (in_array($value, $_SESSION ['cart_menu'])) {
                    $menu_key = array_search($value, $_SESSION ['cart_menu']);
                    unset($_SESSION ['cart_menu'] [$menu_key]);
                }
            }
        }
        return true;
    }

    /**
     * Get quantity discount for a product in cart
     *
     * @param array $args
     *        	int product_id
     * @return decimal price for quantity the product
     */
    function _applyQtyDiscount($args)
    {
        $product_id     = $args ['product_id'];
        $cart_qty       = $args ['cart_qty'];
        $cart_total     = $args ['cart_total'];
        $today          = date("Y-m-d");
        $repo           = $this->entityManager->getRepository('ZSELEX_Entity_Product');
        // $qty_discount_exist = $repo->getCount(null, 'ZSELEX_Entity_QuantityDiscount', 'discount_id', array('a.product' => $product_id));
        // echo $qty_discount_exist; exit;
        // if ($qty_discount_exist) {
        $getQtyDiscount = $repo->getQtyDiscount(array(
            'product_id' => $product_id,
            'qty' => $cart_qty
        ));
        // echo "<pre>"; print_r($getQtyDiscount); echo "</pre>"; exit;
        if ($getQtyDiscount) {
            $discount_price = $this->_discountPrice(array(
                'discount' => $getQtyDiscount ['discount'],
                'price' => $cart_total
            ));
        }
        // }
        // echo $discount_price; exit;
        return $discount_price;
    }

    /**
     * Format discount price
     *
     * @param string $args['discount']
     * @param decimal $args['price']
     * @return decimal price for quantity the product
     */
    function _discountPrice($args)
    {
        $discount       = $args ['discount'];
        $price          = $args ['price'];
        // $origPrice = number_format($origPrice1, 2, '.', ',');
        // $origPrice = getAmount($origPrice1);
        // $origPrice = $origPrice1;
        $discount_price = 0;
        // echo $origPrice . '<br>';
        $last_char      = substr($discount, - 1);

        $is_discount  = false;
        $discount_val = '';
        // if (!empty($discount)) {
        if (!empty($discount)) {
            // echo "heree";
            if ($last_char == "%") {
                // echo "percentage"; exit;
                $newVal         = substr($discount, 0, - 1);
                // alert(newVal);
                $discount_price = $price - ($price * $newVal / 100);
            } else {
                // echo "normal"; exit;
                $discount_price = $price - $discount;
            }
        }
        // echo $discount_price; exit;
        return $discount_price;
    }

    /**
     * Get Discount of the product
     *
     * @param int $args['product_id']
     * @param string $args['discount']
     * @param decimal $args['price']
     * @return array 
     */
    public function getDiscount($args)
    {
        $repo       = $this->entityManager->getRepository('ZSELEX_Entity_Product');
        $product_id = $args ['product_id'];
        $discount   = $args ['discount'];
        $price      = $args ['price'];
        $applied    = 0;
        if (!empty($discount)) { // if product detail tab qunatity is not empty then give give preference to it
            $price   = $this->_discountPrice(array(
                'discount' => $discount,
                'price' => $price
            ));
            $applied = 1;
        } else { // check and apply if there is any zero qty discount in Quantitydiscount tab
            $zero_discount = $repo->getZeroQtyDiscount(array(
                'product_id' => $product_id
            ));
            if (!empty($zero_discount)) {
                $price   = $this->_discountPrice(array(
                    'discount' => $zero_discount,
                    'price' => $price
                ));
                $applied = 1;
            }
        }
        // return $price;
        return array('price' => $price, 'applied' => $applied);
    }
}
// end class def
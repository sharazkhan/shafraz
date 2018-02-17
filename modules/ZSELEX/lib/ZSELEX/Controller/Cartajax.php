<?php

/**
 * 2015
 * Class for cart functionalities
 */
class ZSELEX_Controller_Cartajax extends ZSELEX_Controller_Base_Ajax
{

    /**
     * Add a product to cart
     *
     * @param POST
     * @return ajax response
     */
    public function addToCart()
    { // /////
        // echo "Helloooooo cart"; exit;
        $pid      = FormUtil::getPassedValue('pid', 0, 'REQUEST');
        $shop_id  = FormUtil::getPassedValue('shop_id', 0, 'REQUEST');
        $options  = FormUtil::getPassedValue('options', null, 'REQUEST');
        $options2 = FormUtil::getPassedValue('options2', null, 'REQUEST');
        $ques_ans = FormUtil::getPassedValue('ques_ans', null, 'REQUEST');
        $loggedin = $_REQUEST ['loggedin'];
        //  $user_id  = UserUtil::getVar('uid');
        $isGuest  = 0;
        $user_id  = UserUtil::getVar('uid');
        if (!UserUtil::isLoggedIn()) {
            $user_id = ZSELEX_Util::getTempUserId();
            $isGuest = 1;
        }

        $optionsArr = json_decode($options, true);
        if (empty($optionsArr)) {
            $optionsArr = array();
        }
        $optionsArr2 = json_decode($options2, true);
        if (empty($optionsArr2)) {
            $optionsArr2 = array();
        }

        // echo "user_id :" . $user_id; exit;
        // echo "prodId :" . $pid; exit;
        // echo "options : " . $options; exit;
        // echo "options2 : " . $options2; exit;

        /*
         * if (!empty($options2)) {
         * $options_to_arr1 = array_merge(json_decode($options, true), json_decode($options2, true));
         * } else {
         * $options_to_arr1 = json_decode($options, true);
         * }
         */

        $options_to_arr1 = array_merge($optionsArr, $optionsArr2);

        // $options = json_encode($options_to_arr1);
        $options = serialize($options_to_arr1);

        // echo "Comes hereee"; exit;
        $error = $this->checkProductAvailabilityUser(array(
            'options' => $options,
            'pid' => $pid,
            'shop_id' => $shop_id,
            'ques_ans' => $ques_ans
        ));
        // exit;
        // echo "Comes hereee"; exit;
        // echo "Error : " . $error; exit;
        if ($error) {
            $output ['pid']        = $pid;
            $output ['outofstock'] = true;
            AjaxUtil::output($output);
        }

        //  echo "Comes hereee2"; exit;

        $product = $this->entityManager->getRepository('ZSELEX_Entity_Product')->getProduct(array(
            'product_id' => $pid
        ));

        // echo "<pre>"; print_r($product); echo "</pre>"; exit;
        $getPrice = ModUtil::apiFunc('ZSELEX', 'cart', 'getDiscount',
                array(
                'product_id' => $product ['product_id'],
                'price' => $product ['original_price'],
                'discount' => $product ['discount']
        ));


        $product ['prd_price'] = $getPrice['price'];
        //  echo $product ['prd_price']; exit;
        // echo "<pre>"; print_r($product ['prd_price']); echo "</pre>"; exit;
        // $options_to_arr = json_decode($options, true);
        $options_to_arr        = $options_to_arr1;
        // echo "<pre>"; print_r($options); echo "</pre>"; exit;
        // echo "<pre>"; print_r($options_to_arr); echo "</pre>"; exit;
        $total                 = 0;
        $new_price             = false;
        if (!empty($options_to_arr)) {
            foreach ($options_to_arr as $ov) {

                $fields     = array(
                    'a.product_to_options_value_id',
                    'a.price',
                    'a.parent_option_id',
                    'a.price_prefix'
                );
                $value_info = $this->entityManager->getRepository('ZSELEX_Entity_ProductToOption')->getProductToOptionValue(array(
                    'id' => $ov ['valueID'],
                    'fields' => $fields
                ));
                // echo "<pre>"; print_r($value_info); echo "</pre>";
                $total += $value_info ['price'];
                // $pref = substr($value_info['price'], 0, 1);
                $pref       = $value_info ['price_prefix'];
                // echo "pref :" . $pref . '<br>';
                if ($pref != '+' && $pref != '-' && $value_info ['price'] > 0) {
                    // echo "pref :" . $pref . '<br>';
                    $new_price = true;
                }
            }
        }
        // echo "newPrice :" . $new_price;
        // exit;
        // echo $total; exit;
        // echo 'Comes here'; exit;
        // echo "<pre>"; print_r($cart_info); echo "</pre>"; exit;
        $exist = 0;

        $where    = "";
        $addWhere = "";

        // $setParams = array('user_id' => $user_id, 'product_id' => $pid, 'shop_id' => $shop_id);
        $whereFields = array(
            'a.user_id' => $user_id,
            'a.product' => $pid,
            'a.shop' => $shop_id
        );
        if (!empty($options)) {
            // $setParams += array('cart_content' => $options);
            $addWhere                       = " AND a.cart_content='".$options."' ";
            $whereFields ['a.cart_content'] = $options;
        }
        // if (!empty($ques_ans)) {
        $addWhere .= " AND a.prd_answer='".DataUtil::formatForStore($ques_ans)."' ";
        $whereFields ['a.prd_answer'] = DataUtil::formatForStore($ques_ans);
        // }

        $where = "a.user_id=$user_id AND a.product=$pid AND a.shop='".$shop_id."' ".$addWhere."";
        // echo $where; exit;

        $product_count = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->getCount(null,
            'ZSELEX_Entity_Cart', 'cart_id', $whereFields);
        // echo "count : " . $product_count; exit;

        if ($product_count) {

            // echo "comes here"; exit;

            $fields   = array(
                'a.cart_id',
                'a.quantity'
            );
            $get_cart = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->getCart(array(
                'where' => $whereFields,
                'fields' => $fields
            ));

            // echo "<pre>"; print_r($get_cart); echo "</pre>"; exit;

            $exist         = 1;
            $price         = 0;
            $cart_quantity = ($get_cart ['quantity'] < 1) ? 1 : $get_cart ['quantity'];
            if ($new_price == true) {
                // $final_price = $total * $cart_quantity;
                $final_price = $total * ($cart_quantity + 1);
                $price       = $total;
            } else {
                // $final_price = ($product ['prd_price'] + $total) * $cart_quantity;
                $final_price = ($product ['prd_price'] + $total) * ($cart_quantity
                    + 1);
                $price       = $product ['prd_price'] + $total;
            }
            $update_item = array(
                // 'product_id' => $pid,
                // 'cart_content' => $options,
                // 'prd_answer' => $ques_ans,
                'quantity' => $cart_quantity + 1,
                'original_price' => $product ['original_price'],
                'price' => $price,
                'options_price' => $total,
                // 'final_price' => ($product['prd_price'] + $total) * $cart_quantity,
                'final_price' => $final_price
            );
            // echo "<pre>"; print_r($update_item); echo "</pre>"; exit;
            // $where = "WHERE user_id=$user_id AND product_id=$pid AND shop_id=$shop_id";
            // $update = DBUTil::updateObject($obj = $update_item, 'zselex_cart', $where);
            $update      = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->updateCart(array(
                'where' => $whereFields,
                'item' => $update_item
            ));
        } else {

            if ($new_price == true) {
                $final_price = $total * 1;
            } else {
                $final_price = ($product ['prd_price'] + $total) * 1;
            }

            $insert_item = array(
                'user_id' => $user_id,
                'product_id' => $pid,
                'shop_id' => $shop_id,
                'quantity' => 1,
                'original_price' => $product ['original_price'],
                'price' => $price,
                'options_price' => $total,
                // 'final_price' => ($product['prd_price'] + $total) * 1,
                'final_price' => $final_price,
                'cart_content' => $options,
                'prd_answer' => $ques_ans,
                'is_guest' => $isGuest
            );
            // echo "<pre>"; print_r($insert_item); echo "</pre>"; exit;

            $create = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->createCartItem($insert_item);
        }

        $cart_info = ModUtil::apiFunc('ZSELEX', 'cart', 'carttotal', array());

        //echo "<pre>"; print_r($cart_info); echo "</pre>"; exit;

        $curr_args             = array(
            'amount' => $cart_info ['total'],
            'currency_symbol' => '',
            'decimal_point' => ',',
            'thousands_sep' => '.',
            'precision' => '2'
        );
        $cart_total            = ModUtil::apiFunc('ZSELEX', 'user',
                'number2currency', $curr_args);
        $cart_count            = $cart_info ['count'];
        $theme_path            = "themes/".System::getVar('Default_Theme');
        $output ['exist']      = $exist;
        $output ['cart_total'] = $cart_total;
        $output ['cart_count'] = $cart_count;
        $output ['theme_path'] = $theme_path;
        $output ['pid']        = $pid;

        AjaxUtil::output($output);
    }

    public function checkProductAvailabilityUser1($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $pid          = $args ['pid'];
        $options      = $args ['options'];
        // $optionsToArr = json_decode($options, true);
        $optionsToArr = unserialize($options);
        $shop_id      = $args ['shop_id'];
        $user_id      = UserUtil::getVar('uid');
        $error        = false;

        $where     = "";
        $addWhere  = "";
        $countArgs = array();
        if (!empty($options)) {
            $addWhere = " AND cart_content='".$options."'";
        }

        $where = "user_id=$user_id AND product_id=$pid AND shop_id='".$shop_id."' ".$addWhere."";

        $whereArr = array(
            "a.user_id" => $user_id,
            "a.product" => $pid,
            "a.shop" => $shop_id
        );
        if (!empty($options)) {
            $whereArr ['a.cart_content'] = $options;
        }

        $countArgs = array(
            'entity' => 'ZSELEX_Entity_Cart',
            'field' => 'cart_id',
            'where' => $whereArr
        );

        $product_count = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->count($countArgs);

        // var_dump($product_count); exit;
        // echo $product_count; exit;
        $cart = array();
        if ($product_count) {

            $fields   = array(
                'a.cart_id',
                'a.quantity'
            );
            $get_cart = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->getCart(array(
                'where' => $whereArr,
                'fields' => $fields
            ));

            $cart [] = $get_cart;
            foreach ($cart as $key => $val) {

                $product = $this->entityManager->getRepository('ZSELEX_Entity_Product')->getProduct(array(
                    'product_id' => $val ['product_id']
                ));
                // echo "<pre>"; print_r($product); echo "</pre>"; exit;
                if (!empty($optionsToArr)) {
                    foreach ($optionsToArr as $ov) {
                        $opt_fields = array(
                            'a.product_to_options_value_id',
                            'a.price',
                            'a.qty'
                        );
                        $value_info = $this->entityManager->getRepository('ZSELEX_Entity_ProductToOption')->getProductToOptionValue(array(
                            'id' => $ov ['valueID'],
                            'fields' => $opt_fields
                        ));

                        if ($value_info ['qty'] <= $val ['quantity']) {
                            $error = true;
                        }
                    }
                } else {
                    if ($product ['prd_quantity'] <= $val ['quantity']) {
                        $error = true;
                    }
                }
            }
        }
        return $error;
    }

    /**
     * Check product quantity availability
     * 
     * @param array $args
     * @return boolean
     */
    public function checkProductAvailabilityUser($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $pid          = $args ['pid'];
        $options      = $args ['options'];
        // $optionsToArr = json_decode($options, true);
        $optionsToArr = unserialize($options);
        $shop_id      = $args ['shop_id'];
        $ques_ans     = $args ['ques_ans'];
        // $user_id      = UserUtil::getVar('uid');
        $user_id      = UserUtil::getVar('uid');
        if (!UserUtil::isLoggedIn()) {
            $user_id = ZSELEX_Util::getTempUserId();
        }
        $error = false;

        $where     = "";
        $addWhere  = "";
        $countArgs = array();
        if (!empty($optionsToArr)) {
            $addWhere = " AND cart_content='".$options."'";
        }

        $where = "user_id=$user_id AND product_id=$pid AND shop_id='".$shop_id."' ".$addWhere."";

        $whereArr = array(
            "a.user_id" => $user_id,
            "a.product" => $pid,
            "a.shop" => $shop_id
        );
        if (!empty($options)) {
            $whereArr ['a.cart_content'] = $options;
        }
        // if (!empty($ques_ans)) {
        $whereArr ['a.prd_answer'] = DataUtil::formatForStore($ques_ans);
        // }

        $countArgs = array(
            'entity' => 'ZSELEX_Entity_Cart',
            'field' => 'cart_id',
            'where' => $whereArr
        );

        $product_count = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->count($countArgs);

        // var_dump($product_count); exit;
        // echo $product_count; exit;
        $cart = array();
        if ($product_count) {

            $fields   = array(
                'a.cart_id',
                'a.quantity'
            );
            // $get_cart = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->getCart(array('where' => $whereArr, 'fields' => $fields));
            $get_cart = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->get(array(
                'entity' => 'ZSELEX_Entity_Cart',
                'fields' => array(
                    'a.cart_id',
                    'a.quantity',
                    'b.product_id'
                ),
                'joins' => array(
                    'JOIN a.product b'
                ),
                'where' => $whereArr
            ));
            // echo "Helloooo"; exit;
            // echo "<pre>"; print_r($get_cart); echo "</pre>"; exit;
            // $cart[] = $get_cart;
            $cart     = $get_cart;
            // echo "<pre>"; print_r($cart); echo "</pre>"; exit;
            // foreach ($cart as $key => $val) {

            $product = $this->entityManager->getRepository('ZSELEX_Entity_Product')->getProduct(array(
                'product_id' => $cart ['product_id']
            ));
            // echo "<pre>"; print_r($product); echo "</pre>"; exit;
            if (!empty($optionsToArr)) {
                // echo 'option exists'; exit;
                foreach ($optionsToArr as $ov) {
                    $opt_fields = array(
                        'a.product_to_options_value_id',
                        'a.price',
                        'a.qty'
                    );
                    $value_info = $this->entityManager->getRepository('ZSELEX_Entity_ProductToOption')->getProductToOptionValue(array(
                        'id' => $ov ['valueID'],
                        'fields' => $opt_fields
                    ));

                    if ($value_info ['qty'] <= $cart ['quantity']) {
                        $error = true;
                    }
                }
            } else {
                // echo 'normal'; exit;
                if ($product ['prd_quantity'] <= $cart ['quantity']) {
                    $error = true;
                }
            }
            // }
        }
        return $error;
    }

    public function checkProductAvailabilityGuest($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $pid          = $args ['pid'];
        $options      = $args ['options'];
        // $optionsToArr = json_decode($options, true);
        $optionsToArr = unserialize($options);
        $shop_id      = $args ['shop_id'];
        $ques_ans     = $args ['ques_ans'];
        $error        = false;

        $productArr   = ModUtil::apiFunc('ZSELEX', 'cart', 'getTempCart');
        // echo "<pre>"; print_r($productArr); echo "</pre>"; exit;
        $productCheck = $this->productExist($productArr, $pid, $options,
            $ques_ans);
        // echo "<pre>"; print_r($productCheck); echo "</pre>"; exit;
        if ($productCheck ['exist'] == true) {
            $key     = $productCheck ['key'];
            $cart [] = $productArr [$shop_id] [$key];
            foreach ($cart as $key => $val) {

                $product = $this->entityManager->getRepository('ZSELEX_Entity_Product')->getProduct(array(
                    'product_id' => $val ['product_id']
                ));
                if (!empty($optionsToArr)) {
                    foreach ($optionsToArr as $ov) {
                        $fields     = array(
                            'a.product_to_options_value_id',
                            'a.price',
                            'a.qty'
                        );
                        $value_info = $this->entityManager->getRepository('ZSELEX_Entity_ProductToOption')->getProductToOptionValue(array(
                            'id' => $ov ['valueID'],
                            'fields' => $fields
                        ));

                        if ($value_info ['qty'] <= $val ['quantity']) {
                            $error = true;
                        }
                    }
                } else {
                    if ($product ['prd_quantity'] <= $val ['quantity']) {
                        $error = true;
                    }
                }
            }
        }
        return $error;
    }

    public function addToCartGuest()
    {
        $pid      = $_REQUEST ['pid'];
        $shop_id  = $_REQUEST ['shop_id'];
        $options  = $_REQUEST ['options'];
        $options2 = $_REQUEST ['options2'];
        $ques_ans = $_REQUEST ['ques_ans'];
        $loggedin = $_REQUEST ['loggedin'];
        $user_id  = UserUtil::getVar('uid');

        $optionsArr = json_decode($options, true);
        if (empty($optionsArr)) {
            $optionsArr = array();
        }
        $optionsArr2 = json_decode($options2, true);
        if (empty($optionsArr2)) {
            $optionsArr2 = array();
        }

        $options_to_arr1 = array_merge($optionsArr, $optionsArr2);

        // $options = json_encode($options_to_arr1);
        $options = serialize($options_to_arr1);

        // $options_to_arr1 = array_unique($options_to_arr1);
        // echo "<pre>"; print_r($options_to_arr1); echo "</pre>"; exit;

        $product = $this->entityManager->getRepository('ZSELEX_Entity_Product')->getProduct(array(
            'product_id' => $pid
        ));

        // echo "<pre>"; print_r($product); echo "</pre>"; exit;
        $product ['prd_price'] = ModUtil::apiFunc('ZSELEX', 'cart',
                'getDiscount',
                array(
                'product_id' => $product ['product_id'],
                'price' => $product ['original_price'],
                'discount' => $product ['discount']
        ));
        // $options_to_arr = json_decode($options, true);
        $options_to_arr        = $options_to_arr1;
        // echo "<pre>"; print_r($options); echo "</pre>"; exit;

        $error = $this->checkProductAvailabilityGuest(array(
            'options' => $options,
            'pid' => $pid,
            'shop_id' => $shop_id,
            'ques_ans' => $ques_ans
        ));
        // echo "Error : " . $error; exit;
        if ($error) {
            $output ['pid']        = $pid;
            $output ['outofstock'] = true;
            AjaxUtil::output($output);
        }

        $total     = 0;
        $new_price = false;
        if (!empty($options_to_arr)) {
            foreach ($options_to_arr as $ov) {
                $fields     = array(
                    'a.product_to_options_value_id',
                    'a.price',
                    'a.price_prefix'
                );
                $value_info = $this->entityManager->getRepository('ZSELEX_Entity_ProductToOption')->getProductToOptionValue(array(
                    'id' => $ov ['valueID'],
                    'fields' => $fields
                ));
                $total += $value_info ['price'];
                $pref       = $value_info ['price_prefix'];
                if ($pref != '+' && $pref != '-' && $value_info ['price'] > 0) {
                    $new_price = true;
                }
            }
        }

        // echo $total; exit;
        $exist = 0;

        // $_SESSION['temp_cart'] = json_decode($_COOKIE['temp_cart'], true);
        $_SESSION ['temp_cart'] = ModUtil::apiFunc('ZSELEX', 'cart',
                'getTempCart');
        // if ($product_count) {
        $productCheck           = $this->productExist($_SESSION ['temp_cart'],
            $pid, $options, $ques_ans);
        // echo "<pre>"; print_r($productCheck); echo "</pre>"; exit;
        if ($productCheck ['exist'] == true) {
            $key = $productCheck ['key'];

            $upatedQty = $_SESSION ['temp_cart'] [$shop_id] [$key] ['quantity'] + 1;

            if ($new_price == true) {
                $final_price = $total * $upatedQty;
            } else {
                $final_price = ($product ['prd_price'] + $total) * $upatedQty;
            }
            $_SESSION ['temp_cart'] [$shop_id] [$key] = array(
                'cart_id' => $_SESSION ['temp_cart'] [$shop_id] [$key] ['cart_id'],
                'product_id' => $pid,
                'product_name' => $product ['product_name'],
                'options' => $options,
                'quantity' => $upatedQty,
                'cart_content' => $options,
                'prd_answer' => $ques_ans,
                'prd_price' => $product ['original_price'],
                // 'final_price' => ($product['prd_price'] + $total) * $upatedQty,
                'final_price' => $final_price,
                'prd_image' => $product ['prd_image'],
                'prd_description' => $product ['prd_description']
            );
        } else {

            // if (!isset($_SESSION['temp_cart'][$shop_id])) { // if cookie is not set then set the first key as zero
            if (count($_SESSION ['temp_cart'] [$shop_id]) < 1) {
                $last_keys = 0;
            } else { // if cookie is available then increment the key by adding the last key of the previous cookie to it.
                $last_key  = key(array_slice($_SESSION ['temp_cart'] [$shop_id],
                        - 1, 1, TRUE));
                $last_keys = $last_key + 1;
            }

            if ($new_price == true) {
                $final_price = $total;
            } else {
                $final_price = $product ['prd_price'] + $total;
            }

            $_SESSION ['temp_cart'] [$shop_id] [] = array(
                'cart_id' => $last_keys,
                'product_id' => $pid,
                'product_name' => $product ['product_name'],
                'options' => $options,
                'quantity' => 1,
                'cart_content' => $options,
                'prd_answer' => $ques_ans,
                'prd_price' => $product ['original_price'],
                // 'final_price' => $product['prd_price'] + $total,
                'final_price' => $final_price,
                'prd_image' => $product ['prd_image'],
                'prd_description' => $product ['prd_description']
            );

            // $existingCookie = $_COOKIE['temp_cart'];
            // $cookieEncode = json_encode($_SESSION['temp_cart']);
            // setcookie("temp_cart", $cookieEncode, time() + 604800, '/');
        }
        $cookieEncode = json_encode($_SESSION ['temp_cart']);
        setcookie("temp_cart", $cookieEncode, time() + 604800, '/');

        $cart_info = ModUtil::apiFunc('ZSELEX', 'cart', 'carttotal', array());

        // echo "<pre>"; print_r($cart_info); echo "</pre>"; exit;

        $curr_args             = array(
            'amount' => $cart_info ['total'],
            'currency_symbol' => '',
            'decimal_point' => ',',
            'thousands_sep' => '.',
            'precision' => '2'
        );
        $cart_total            = ModUtil::apiFunc('ZSELEX', 'user',
                'number2currency', $curr_args);
        $cart_count            = $cart_info ['count'];
        $theme_path            = "themes/".System::getVar('Default_Theme');
        $output ['exist']      = $exist;
        $output ['cart_total'] = $cart_total;
        $output ['cart_count'] = $cart_count;
        $output ['theme_path'] = $theme_path;
        $output ['pid']        = $pid;

        AjaxUtil::output($output);
    }

    function productExist($array, $pid, $options, $ques_ans)
    {
        if (!empty($array)) {
            foreach ($array as $shop_ids => $value) {
                foreach ($value as $k => $v) {
                    if ($v ['product_id'] == $pid && $v ['options'] == $options && $v ['prd_answer']
                        == $ques_ans) {
                        // return true;
                        return array(
                            'exist' => true,
                            'key' => $k
                        );
                    }
                }
            }
        }
        return false;
    }

    function productExist1($array, $pid)
    {
        if (!empty($array)) {
            foreach ($array as $shop_ids => $value) {
                foreach ($value as $k => $v) {
                    if ($v ['product_id'] == $pid) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    function getProductToOptionValueId($args)
    {
        error_reporting(0);
        $output = '';
        // AjaxUtil::output($output);
        // echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;

        $option_id           = $_REQUEST ['optionId'];
        $product_id          = $_REQUEST ['productId'];
        $optionValueId       = $_REQUEST ['optionValueId'];
        $childOptionValueId  = $_REQUEST ['childOptionValueId'];
        $parentOptionValueId = $_REQUEST ['parentOptionValueId'];
        $child               = $_REQUEST ['child'];
        $parent              = $_REQUEST ['parent'];
        $optionType          = $_REQUEST ['optionType'];
        $retArr              = array();

        $showPrice = 0;
        $where     = '';
        if (($parent == 1 || $parent == '1') && ($parentOptionValueId > 0 && $childOptionValueId
            > 0)) {
            $_SESSION ['linkedOption'] = array();
            // $where = "product_id=$product_id AND option_id=$option_id AND option_value_id=$childOptionValueId AND parent_option_value_id=$optionValueId";
            $where                     = "a.product=$product_id AND a.option=$option_id AND a.option_value_id=$childOptionValueId AND a.parent_option_value_id=$optionValueId";
            // echo "parent :" .$where; exit;

            if ($childOptionValueId > 0) {
                /*
                 * $get = ModUtil::apiFunc('ZSELEX', 'user', 'get', array(
                 * 'table' => 'zselex_product_to_options_values',
                 * 'where' => $where,
                 * 'fields' => array('product_to_options_value_id', 'product_to_options_id', 'price', 'qty')
                 * ));
                 */
                $get = $this->entityManager->getRepository('ZSELEX_Entity_ProductToOption')->getProductToOptionValuesAjax(array(
                    'where' => $where
                ));
                // echo "<pre>"; print_r($get); echo "</pre>"; exit;

                if (!$get || !count($get)) {
                    $output ['notAvailable'] = 1;
                    AjaxUtil::output($output);
                } elseif ($get ['qty'] < 1) {
                    $output ['noQuantity'] = 1;
                    AjaxUtil::output($output);
                }

                $curr_args = array(
                    'amount' => $get ['price'],
                    'currency_symbol' => '',
                    'decimal_point' => ',',
                    'thousands_sep' => '.',
                    'precision' => '2'
                );
                $price     = ModUtil::apiFunc('ZSELEX', 'user',
                        'number2currency', $curr_args).' DKK';

                $showPrice = 1;

                $_SESSION ['linkedOption'] [] = array(
                    "prdToOptionID" => $get ['product_to_options_id'],
                    "valueID" => $get ['product_to_options_value_id']
                );
            }
        } elseif (($child == 1 || $child == '1') && ($parentOptionValueId > 0 && $childOptionValueId
            > 0)) {
            // echo "child :" .$where; exit;

            $_SESSION ['linkedOption'] = array();
            $where                     = "a.product=$product_id AND a.option=$option_id AND a.option_value_id=$childOptionValueId AND a.parent_option_value_id=$parentOptionValueId";

            // echo "child :" .$where; exit;

            if ($childOptionValueId > 0) {
                /*
                 * $get = ModUtil::apiFunc('ZSELEX', 'user', 'get', array(
                 * 'table' => 'zselex_product_to_options_values',
                 * 'where' => $where,
                 * 'fields' => array('product_to_options_value_id', 'product_to_options_id', 'price', 'qty')
                 * ));
                 */
                $get = $this->entityManager->getRepository('ZSELEX_Entity_ProductToOption')->getProductToOptionValuesAjax(array(
                    'where' => $where
                ));
                // echo "<pre>"; print_r($get); echo "</pre>"; exit;

                if (!$get || !count($get)) {
                    $output ['notAvailable'] = 1;
                    AjaxUtil::output($output);
                } elseif ($get ['qty'] < 1) {
                    $output ['noQuantity'] = 1;
                    AjaxUtil::output($output);
                }

                $curr_args = array(
                    'amount' => $get ['price'],
                    'currency_symbol' => '',
                    'decimal_point' => ',',
                    'thousands_sep' => '.',
                    'precision' => '2'
                );
                $price     = ModUtil::apiFunc('ZSELEX', 'user',
                        'number2currency', $curr_args).' DKK';

                $showPrice = 1;

                $_SESSION ['linkedOption'] [] = array(
                    "prdToOptionID" => $get ['product_to_options_id'],
                    "valueID" => $get ['product_to_options_value_id']
                );
            }
        } elseif (($parent != 1 || $parent != '1') && ($child != 1 || $child != '1')) {
            // $_SESSION['otherOption'] = array();
            $where     = "a.product=$product_id AND a.option=$option_id AND a.option_value_id=$optionValueId";
            // echo "other :" .$where; exit;
            $showPrice = 0;

            /*
             * $get = ModUtil::apiFunc('ZSELEX', 'user', 'get', array(
             * 'table' => 'zselex_product_to_options_values',
             * 'where' => $where,
             * 'fields' => array('product_to_options_value_id', 'product_to_options_id', 'price', 'qty')
             * ));
             */

            $get = $this->entityManager->getRepository('ZSELEX_Entity_ProductToOption')->getProductToOptionValuesAjax(array(
                'where' => $where
            ));

            /*
             * if (!$get || !count($get)) {
             * $output['notAvailable'] = 1;
             * AjaxUtil::output($output);
             * } elseif ($get['qty'] < 1) {
             * $output['noQuantity'] = 1;
             * AjaxUtil::output($output);
             * }
             */

            $price = $get ['price'];

            if ($optionType != 'checkbox') {

                $_SESSION ['otherOption'] [$get ['product_to_options_id']]    = array();
                $_SESSION ['otherOption'] [$get ['product_to_options_id']] [] = array(
                    "prdToOptionID" => $get ['product_to_options_id'],
                    "valueID" => $get ['product_to_options_value_id']
                );
            } elseif ($optionType == 'checkbox') {
                // $_SESSION['checkboxOption'] = array();
                $_SESSION ['checkboxOption'] [] = array(
                    "prdToOptionID" => $get ['product_to_options_id'],
                    "valueID" => $get ['product_to_options_value_id']
                );
            }

            // $prdToOptionID[] = $get['product_to_options_id'];
        }

        // echo "all :" .$where; exit;
        // echo $where; exit;
        // echo "<pre>"; print_r($get); echo "</pre>"; exit;
        // echo "<pre>"; print_r($_SESSION['otherOption']); echo "</pre>"; exit;
        // echo "<pre>"; print_r($_SESSION['linkedOption']); echo "</pre>"; exit;
        // echo "<pre>"; print_r($_SESSION['checkboxOption']); echo "</pre>"; exit;
        // $retArr = array_merge(!empty($_SESSION['otherOption']) ? $_SESSION['otherOption'] : array(), !empty($_SESSION['linkedOption']) ? $_SESSION['linkedOption'] : array());

        foreach ($_SESSION ['linkedOption'] as $key => $val) {
            if (!empty($tempID)) {
                $checkExist = $val ['valueID']."-".$val ['prdToOptionID'];
                if (in_array($checkExist, $tempID)) {
                    unset($_SESSION ['linkedOption'] [$key]);
                }
            }
            $tempID [] = $val ['valueID']."-".$val ['prdToOptionID'];
        }

        // echo "<pre>"; print_r($_SESSION['linkedOption']); echo "</pre>"; exit;

        foreach ($_SESSION ['otherOption'] as $key1 => $val1) {
            // echo "<pre>"; print_r($val1); echo "</pre>"; exit;
            foreach ($val1 as $key2 => $val2) {
                $subArr [] = array(
                    "prdToOptionID" => $val2 ['prdToOptionID'],
                    "valueID" => $val2 ['valueID']
                );
            }
        }

        foreach ($_SESSION ['checkboxOption'] as $key3 => $val3) {
            if (!empty($tempID3)) {
                $checkExist = $val3 ['valueID']."-".$val3 ['prdToOptionID'];
                if (in_array($checkExist, $tempID3)) {
                    unset($_SESSION ['checkboxOption'] [$key3]);
                }
            }
            $tempID3 [] = $val3 ['valueID']."-".$val3 ['prdToOptionID'];
        }

        $retArr    = array_merge(!empty($subArr) ? $subArr : array(),
            !empty($_SESSION ['linkedOption']) ? $_SESSION ['linkedOption'] : array(),
            !empty($_SESSION ['checkboxOption']) ? $_SESSION ['checkboxOption'] : array() );
        // $retArr = array_merge($_SESSION['linkedOption'], $subArr);
        // echo "<pre>"; print_r($retArr); echo "</pre>"; exit;
        // echo $price; exit;
        // echo "<pre>"; print_r($get); echo "</pre>"; exit;
        $origPrice = $get ['price'];
        $pref      = substr($origPrice, 0, 1);

        /*
         * if ($price[0] !== '-' && $price[0] !== '+') {
         * $price = "+" . $price;
         * }
         */
        if ($pref == '+') {
            $price = "+".$price;
        }
        $output ['price']     = $price;
        $output ['showPrice'] = $showPrice;
        $output ['data']      = json_encode($retArr);
        AjaxUtil::output($output);
    }

    function getParentOptionValues()
    {
        $view                  = Zikula_View::getInstance($this->name);
        $parent_option_id      = $_REQUEST ['parentOptionId'];
        $child_option_value_id = $_REQUEST ['optionValueId'];
        $product_id            = $_REQUEST ['product_id'];

        /*
         * $parent_product_options = ModUtil::apiFunc('ZSELEX', 'user', 'selectJoinArray', $args = array(
         * 'table' => 'zselex_product_to_options_values a',
         * 'fields' => array(
         * 'a.product_id,a.price,a.option_id,a.product_to_options_id,a.option_value_id,a.parent_option_value_id,a.qty,b.option_name,b.option_type,c.option_value'
         * ),
         * 'where' => array(
         * "a.parent_option_id=$parent_option_id AND a.option_value_id=$child_option_value_id AND a.qty > 0 AND a.product_id=$product_id"
         * ),
         * 'joins' => array(
         * "INNER JOIN zselex_product_options b ON b.option_id=a.parent_option_id",
         * "INNER JOIN zselex_product_options_values c ON c.option_value_id=a.parent_option_value_id"
         * ),
         * 'orderby' => 'c.sort_order ASC',
         * 'groupby' => 'parent_option_value_id'
         * ));
         */

        $where                  = " a.parent_option_id=$parent_option_id AND a.option_value_id=$child_option_value_id AND a.qty > 0 AND a.product_id=$product_id ";
        $parent_product_options = $this->entityManager->getRepository('ZSELEX_Entity_ProductToOption')->getParentOptionValues(array(
            'where' => $where
        ));

        // echo "<pre>"; print_r($parent_product_options); echo "</pre>";

        $view->assign('enable', 1);
        $view->assign('parent_product_options', $parent_product_options);

        $output_tpl = $view->fetch('ajax/showParentOptionValues.tpl');
        $data       = '';
        $data .= new Zikula_Response_Ajax_Plain($output_tpl);

        $output ['data'] = $data;
        AjaxUtil::output($output);
    }

    /**
     * function updateShipping(){
     * $self_pick = FormUtil::getPassedValue('pid', 0, 'REQUEST');
     * }
     */
    function getTotal()
    {
        $output ['error'] = false;
        // $total = $_SESSION ['checkoutinfo'] ['grand_total_all'];
        $total            = $_SESSION ['checkoutinfo'] ['final_price'];
        $gw               = FormUtil::getPassedValue('gw', null, 'REQUEST');
        $cartShopId       = SessionUtil::getVar('cart_shop_id');
        // echo $gw; exit;
        if ($gw == 'paypal') {
            $total = number_format($total, 2);
        } else {
            $total = (int) ($total * 100);
        }

        $cartError = ModUtil::apiFunc('ZSELEX', 'cart', 'validatecart',
                array('cart_shop_id' => $cartShopId));
        if ($cartError) {
            // return $this->redirect(ModUtil::url('ZSELEX', 'user', 'cart'));
            $output ['error'] = true;
        }

        $cartUrl = ModUtil::url('ZSELEX', 'user', 'cart');

        // $total = (int) ($total * 100);

        /* elseif ($gw == 'quickpay') {
          $total = (int) ($total * 100);
          } */
        $output ['total']    = $total;
        $output ['cart_url'] = $cartUrl;
        AjaxUtil::output($output);
    }

    function applyDiscount2()
    {

        //echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
        $_SESSION ['checkoutinfo'] ['discount_price'] = '';


        $discountCode  = FormUtil::getPassedValue('code', null, 'REQUEST');
        $shopId        = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        //  $total            = $_SESSION ['checkoutinfo'] ['grand_total_all'];
        $totalPrice    = $_SESSION ['checkoutinfo'] ['totalprice'];
        $shipping      = $_SESSION ['checkoutinfo'] ['shipping'];
        $discountValue = $_SESSION ['checkoutinfo'] ['discount_value'];
        $selfPickUp    = $_SESSION ['checkoutinfo'] ['self_pickup'];
        // $userId           = UserUtil::getVar('uid');
        $userId        = UserUtil::getVar('uid');
        if (!UserUtil::isLoggedIn()) {
            $userId = ZSELEX_Util::getTempUserId();
        }
        $output ['error'] = 0;
        if ($selfPickUp) {
            $shipping = 0;
        }

        $total = $totalPrice + $shipping;
        //  echo $total; exit;
        //   print_r($total); exit;


        if (empty($discountCode)) {
            $discountValue    = 0;
            $output ['error'] = 0;


            // print_r($total); exit;

            $getVatTotal = $getVatTotal - $discountValue;
            $vat         = ($getVatTotal + $shipping) * 0.2;



            // $newPrice        = 0;
            $newPrice        = $total;
            $discountValue   = 0;
            $discount        = 0;
            $discountApplied = 0;
            $discountCode    = 0;
        } else {
            $getDiscount = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->get(array(
                'entity' => 'ZSELEX_Entity_Discount',
                'fields' => array(
                    'a.discount', 'a.discount_code',
                ),
                'where' => array('a.shop' => $shopId, 'a.discount_code' => $discountCode,
                    'a.status' => 1)
            ));
            if (!$getDiscount) {
                $output ['error'] = 1;


                $newPrice        = 0;
                $discountValue   = 0;
                $discount        = 0;
                $discountApplied = 0;
                $discountCode    = 0;
            }
            //$output ['error'] = 0;
            $discountPrice = $getDiscount['discount'];

            $_SESSION ['checkoutinfo'] ['discount_code'] = $discountCode;
            //   $_SESSION ['checkoutinfo'] ['discount_price'] = $discountPrice;

            $lastChar = substr($discountPrice, - 1);
            if ($lastChar == "%") {
                // echo "percentage";
                $newVal        = substr($discountPrice, 0, - 1);
                // alert(newVal);
                // $discountPrice1 = $total - ($total * $newVal / 100);
                $newPrice      = $total - ($total * $newVal / 100);
                $discountValue = ($total * $newVal / 100);
                $discount      = $newVal;

                $_SESSION ['checkoutinfo'] ['discount'] = $discount;
            } else {

                //$discountPrice1 = $total - $discountPrice;
                $newPrice      = $total - $discountPrice;
                $discountValue = $discountPrice;

                @$n         = $discountPrice / $total;
                $newVal    = $n * 100;
                // echo $new_val;
                $first_str = str_split($newVal);
                // echo "first :" . $str[0];
                if ($first_str [0] == '0') {
                    $discount_val = (number_format($newVal, 2) - 0);
                } else {
                    $discount_val = round($newVal);
                }
                $discount = $discount_val;

                $_SESSION ['checkoutinfo'] ['discount'] = $discount;
            }
        }

        $_SESSION ['checkoutinfo'] ['discount_applied'] = 1;
        $_SESSION ['checkoutinfo'] ['final_price']      = $newPrice;

        $currArgs = array(
            'amount' => $newPrice,
            'currency_symbol' => '',
            'decimal_point' => ',',
            'thousands_sep' => '.',
            'precision' => '2'
        );
        $newPrice = ModUtil::apiFunc('ZSELEX', 'user', 'number2currency',
                $currArgs);

        // print_r($newPrice); exit;

        $vatArgs                           = array(
            'user_id' => $userId,
            'shop_id' => $shopId
        );
        $getVatTotal                       = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->getVatTotal($vatArgs);
        $getVatTotal                       = $getVatTotal - $discountValue;
        $vat                               = ($getVatTotal + $shipping) * 0.2;
        $_SESSION ['checkoutinfo'] ['VAT'] = $vat;

        $vatCurrArgs = array(
            'amount' => $vat,
            'currency_symbol' => '',
            'decimal_point' => ',',
            'thousands_sep' => '.',
            'precision' => '2'
        );

        $vat = ModUtil::apiFunc('ZSELEX', 'user', 'number2currency',
                $vatCurrArgs);


        $_SESSION ['checkoutinfo'] ['discount_value'] = $discountValue;

        $output ['newPrice']       = $newPrice;
        $output ['discount_value'] = $discountValue;
        $output ['discount']       = $discount;
        $output ['vat']            = $vat;
        AjaxUtil::output($output);
    }

    /**
     * Apply Discount
     *
     * @param string discount_code
     * @param int shop_id
     * @return ajax response
     */
    function applyDiscount()
    {

        //echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
        $_SESSION ['checkoutinfo'] ['discount_price'] = '';


        $discountCode  = FormUtil::getPassedValue('code', null, 'REQUEST');
        $shopId        = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        //  $total            = $_SESSION ['checkoutinfo'] ['grand_total_all'];
        $totalPrice    = $_SESSION ['checkoutinfo'] ['totalprice'];
        $shipping      = $_SESSION ['checkoutinfo'] ['shipping'];
        $discountValue = $_SESSION ['checkoutinfo'] ['discount_value'];
        $selfPickUp    = $_SESSION ['checkoutinfo'] ['self_pickup'];
        // $userId           = UserUtil::getVar('uid');
        $userId        = UserUtil::getVar('uid');
        if (!UserUtil::isLoggedIn()) {
            $userId = ZSELEX_Util::getTempUserId();
        }
        $output ['error'] = 0;
        if ($selfPickUp) {
            $shipping = 0;
        }

        $total = $totalPrice + $shipping;
        //  echo $total; exit;
        //   print_r($total); exit;


        if (empty($discountCode)) {
            $discountValue    = 0;
            $output ['error'] = 0;


            // print_r($total); exit;

            $getVatTotal = $getVatTotal - $discountValue;
            $vat         = ($getVatTotal + $shipping) * 0.2;


            // $newPrice        = 0;
            $newPrice        = $total;
            $discountValue   = 0;
            $discount        = 0;
            $discountApplied = 0;
            $discountCode    = 0;
        } else {
            $getDiscount = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->get(array(
                'entity' => 'ZSELEX_Entity_Discount',
                'fields' => array(
                    'a.discount', 'a.discount_code',
                ),
                'where' => array('a.shop' => $shopId, 'a.discount_code' => $discountCode,
                    'a.status' => 1)
            ));
            if (!$getDiscount) {
                $output ['error'] = 1;


                $newPrice        = 0;
                $discountValue   = 0;
                $discount        = 0;
                $discountApplied = 0;
                $discountCode    = 0;
            }
            //$output ['error'] = 0;
            $discountPrice = $getDiscount['discount'];

            $_SESSION ['checkoutinfo'] ['discount_code'] = $discountCode;
            //   $_SESSION ['checkoutinfo'] ['discount_price'] = $discountPrice;
            $discountToReduce                            = 0;


            if ($getDiscount) {
                $discountToReduce = $this->totalAfterDiscount(array('discount' => $discountPrice));
            }
            $discountValue = $discountToReduce;
            $newPrice      = $total - $discountToReduce;

            @$n      = $discountValue / $total;
            $newVal = $n * 100;
            // echo $newVal; exit;

            $first_str = str_split($newVal);

            /*
              if ($first_str [0] == '0') {
              $discount_val = (number_format($newVal, 2) - 0);
              } else {
              $discount_val = round($newVal);
              }
             */
            $discount_val = (number_format($newVal, 2) - 0);
            $discount     = $discount_val;
        }

        // echo $discount;  exit;


        $_SESSION ['checkoutinfo'] ['discount']         = $discount;
        $_SESSION ['checkoutinfo'] ['discount_applied'] = 1;
        $_SESSION ['checkoutinfo'] ['final_price']      = $newPrice;

        $currArgs = array(
            'amount' => $newPrice,
            'currency_symbol' => '',
            'decimal_point' => ',',
            'thousands_sep' => '.',
            'precision' => '2'
        );
        $newPrice = ModUtil::apiFunc('ZSELEX', 'user', 'number2currency',
                $currArgs);

        // print_r($newPrice); exit;

        $vatArgs     = array(
            'user_id' => $userId,
            'shop_id' => $shopId
        );
        $getVatTotal = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->getVatTotal($vatArgs);
        $getVatTotal = $getVatTotal - $discountValue;
        $vat         = ($getVatTotal + $shipping) * 0.2;

        $_SESSION ['checkoutinfo'] ['VAT'] = $vat;

        $vatCurrArgs = array(
            'amount' => $vat,
            'currency_symbol' => '',
            'decimal_point' => ',',
            'thousands_sep' => '.',
            'precision' => '2'
        );

        $vat = ModUtil::apiFunc('ZSELEX', 'user', 'number2currency',
                $vatCurrArgs);


        $_SESSION ['checkoutinfo'] ['discount_value'] = $discountValue;

        $output ['newPrice']       = $newPrice;
        $output ['discount_value'] = $discountValue;
        $output ['discount']       = $discount;
        $output ['vat']            = $vat;
        AjaxUtil::output($output);
    }

    function selfPickup()
    {
        $_SESSION ['checkoutinfo'] ['self_pickup'] = 0;

        $output['error'] = 1;
        // $discountCode = FormUtil::getPassedValue('code', null, 'REQUEST');
        // $total           = $_SESSION ['checkoutinfo'] ['grand_total_all'];
        $shopId          = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $total           = $_SESSION ['checkoutinfo'] ['totalprice'];
        $shipping        = $_SESSION ['checkoutinfo'] ['shipping'];
        // $finalPrice    = $_SESSION ['checkoutinfo'] ['final_price'];
        $discountValue   = $_SESSION ['checkoutinfo'] ['discount_value'];
        $checked         = FormUtil::getPassedValue('checked', null, 'REQUEST');
        //  $userId          = UserUtil::getVar('uid');
        $userId          = UserUtil::getVar('uid');
        if (!UserUtil::isLoggedIn()) {
            $userId = ZSELEX_Util::getTempUserId();
        }
        /* if ($checked == true) {
          $finalPrice = ($total - $shipping) - $discountValue;
          } else {
          $finalPrice = ($total + $shipping) - $discountValue;
          } */



        if ($checked == 1) {
            //  echo "comes here"; exit;
            $finalPrice = $total - $discountValue;

            $_SESSION ['checkoutinfo'] ['self_pickup'] = 1;
            //  $_SESSION ['checkoutinfo'] ['shipping'] = 0;

            $shipping = 0;
        } else {
            // echo "comes here2"; exit;
            $finalPrice = ($total + $shipping) - $discountValue;
        }

        $vatArgs     = array(
            'user_id' => $userId,
            'shop_id' => $shopId
        );
        $getVatTotal = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->getVatTotal($vatArgs);
        $getVatTotal = $getVatTotal - $discountValue;
        $vat         = ($getVatTotal + $shipping) * 0.2;

        $_SESSION ['checkoutinfo'] ['VAT'] = $vat;

        $_SESSION ['checkoutinfo'] ['final_price'] = $finalPrice;

        $currArgs   = array(
            'amount' => $finalPrice,
            'currency_symbol' => '',
            'decimal_point' => ',',
            'thousands_sep' => '.',
            'precision' => '2'
        );
        $finalPrice = ModUtil::apiFunc('ZSELEX', 'user', 'number2currency',
                $currArgs);


        $shippingCurrArgs = array(
            'amount' => $shipping,
            'currency_symbol' => '',
            'decimal_point' => ',',
            'thousands_sep' => '.',
            'precision' => '2'
        );

        $shipping = ModUtil::apiFunc('ZSELEX', 'user', 'number2currency',
                $shippingCurrArgs);

        $vatCurrArgs = array(
            'amount' => $vat,
            'currency_symbol' => '',
            'decimal_point' => ',',
            'thousands_sep' => '.',
            'precision' => '2'
        );

        $vat = ModUtil::apiFunc('ZSELEX', 'user', 'number2currency',
                $vatCurrArgs);

        $output['error']       = 0;
        $output['checked']     = $checked;
        $output['final_price'] = $finalPrice;
        $output['shipping']    = $shipping;
        $output['vat']         = $vat;
        AjaxUtil::output($output);
    }

    /**
     * Send Email on iniatiating an order
     *
     * @return void
     */
    function sendEmail()
    {
        // $total = $_SESSION ['checkoutinfo'] ['grand_total_all'];
        $orderId       = $_SESSION ['checkoutinfo'] ['order_id'];
        $gw            = FormUtil::getPassedValue('gw', null, 'REQUEST');
        // echo $gw; exit;
        $orderInfoArgs = array(
            'fields' => array(
                'a.id',
                'a.status',
                'a.user_id',
                'b.shop_id',
                'a.email',
                'a.totalprice',
                'a.order_id',
                'a.vat',
                'a.shipping',
                'a.self_pickup'
            ),
            'where' => array(
                'a.order_id' => $orderId
            )
        );
        $orderInfo     = $this->entityManager->getRepository('ZSELEX_Entity_Order')->getOrderInfo($orderInfoArgs);

        $notifyError     = ModUtil::apiFunc('ZPayment', 'QuickPay',
                'sendInitialNotification', $orderInfo);
        $output['error'] = 0;
        AjaxUtil::output($output);
    }

    /**
     * Get total after discount
     * 
     * @param type $args
     * @return int
     */
    function totalAfterDiscount($args)
    {
        //  $discountCode = $args['discountCode'];
        // echo "<pre>"; print_r($args); echo "</pre>";  exit;
        $discount = $args['discount'];
        if (empty($discount)) {
            return 0;
        }
        $userId = UserUtil::getVar('uid');
        if (!UserUtil::isLoggedIn()) {
            $userId = ZSELEX_Util::getTempUserId();
        }

        //$discount = $getDiscount['discount'];
        $lastChar = substr($discount, - 1);


        $getArgs            = array(
            'entity' => 'ZSELEX_Entity_Cart',
            'where' => array('a.user_id' => $userId),
            'joins' => array('JOIN a.product b'),
            'fields' => array('a.discount_applied', 'a.final_price', 'b.max_discount')
            // 'exit'=> true
        );
        //  echo "<pre>"; print_r($getArgs); echo "</pre>";  exit;
        $cartProducts       = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->getAll($getArgs);
        //  echo "<pre>"; print_r($cartProducts); echo "</pre>";  exit;
        // echo "comes here";  exit;
        $totalAfterDiscount = 0;
        $count              = 1;
        foreach ($cartProducts as $key => $val) {

            $maxDiscount    = $val['max_discount'];
            $maxDiscountVal = $this->_maxDiscount($maxDiscount);
            if (!$val['discount_applied']) {
                if ($maxDiscountVal['status'] != 'no_discount') {
                    $finalPrice = $val['final_price'];
                    if ($lastChar == "%") {
                        $newVal = substr($discount, 0, - 1);
                        //echo $newVal; exit;
                        // $discountPrice = $finalPrice * ($newVal / 100);
                    } else {
                        //$discountPrice = $finalPrice - $discount;
                        //$discountPrice = $finalPrice * ($discount / 100);
                        $newVal = $discount;
                    }
                    if ($newVal > $maxDiscountVal['max']) {
                        $newVal = $maxDiscountVal['max'];
                    }
                    $discountPrice = $finalPrice * ($newVal / 100);
                    $totalAfterDiscount += $discountPrice;
                }
            }
            $count++;
        }
        // echo "total :" . $totalAfterDiscount; exit;
        //  echo "comes here2";  exit;

        return $totalAfterDiscount;
    }

    function _maxDiscount($maxDiscount)
    {

        if ($maxDiscount == '' || $maxDiscount == null) {
            $output['status'] = 'null';
        } elseif ($maxDiscount == 0) {
            $output['status'] = 'no_discount';
        }
        $lastChar = substr($maxDiscount, - 1);
        if ($lastChar == "%") {
            $newVal = substr($maxDiscount, 0, - 1);
        } else {
            $newVal = $maxDiscount;
        }
        $output['max'] = $maxDiscount;

        return $output;
    }
}
?>
<?php

class ZSELEX_Controller_User extends ZSELEX_Controller_Base_User
{ //

    public function remindercontentcron($args)
    {
        $modvariable      = $this->getVars();
        $reminderdays     = $modvariable ['remindercontentdays'];
        $today            = date("Y-m-d");
        // echo $reminderdays; exit;
        $minisite_updates = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $args             = array(
                'table' => 'zselex_ministe_updates',
                'where' => "",
                'orderBy' => '',
                'useJoins' => ''
        ));

        foreach ($minisite_updates as $key => $val) {
            
        }

        $check = ModUtil::apiFunc('ZSELEX', 'admin', 'dateDiff', $reminderdays,
                $today);
    }

    /**
     * List all shops
     * 
     * @return type
     */
    public function shoplists()
    {

        // echo "<pre>"; print_r($_POST); echo "</pre>";
        PageUtil::setVar('title', $this->__("CityPilot - Shops"));
        $shop_id     = FormUtil::getPassedValue("shop_id");
        // $country_id = FormUtil::getPassedValue("country_id");
        $modvariable = $this->getVars();
        $country_id  = $modvariable ['default_country_id'];
        // $country_id = "61";
        // KIMENEMARK
        /*
         * $region_id = FormUtil::getPassedValue("region_id");
         * $city_id = FormUtil::getPassedValue("city_id");
         * $area_id = FormUtil::getPassedValue("area_id");
         */

        $region_id = FormUtil::getPassedValue("region_id");
        $city_id   = FormUtil::getPassedValue("city_id");
        $area_id   = FormUtil::getPassedValue("area_id");

        if (!$region_id) {
            $region_id = $_COOKIE ['region_cookie'];
        }
        if (!$city_id) {
            $city_id = $_COOKIE ['city_cookie'];
        }
        if (!$area_id) {
            $area_id = $_COOKIE ['area_cookie'];
        }

        $hsearch = FormUtil::getPassedValue("hsearch");
        // echo $hsearch; exit;
        $hsearch = ($hsearch == $this->__('search for...') || $hsearch == $this->__('search'))
                ? '' : $hsearch;
        $search  = $hsearch;

        $category_id = FormUtil::getPassedValue("category_id");
        if (!$category_id) {
            $category_id = $_COOKIE ['category_cookie'];
        }
        $branch_id = FormUtil::getPassedValue("branch_id");
        if (!$branch_id) {
            $branch_id = $_COOKIE ['branch_cookie'];
        }

        $affId = FormUtil::getPassedValue("aff_id");
        if (!$affId) {
            $affId = $_COOKIE ['affiliate_cookie'];
        }

        if (!empty($affId)) {
            $affIdArray = explode(',', $affId);
        }
        // echo "<pre>"; print_r($affIdArray); echo "</pre>"; exit;
        $affArray = array();
        $affQuery = '';
        if (!empty($affIdArray)) {
            foreach ($affIdArray as $a) {
                $affArray [] = "a.aff_id=$a";
            }
        }
        // echo "<pre>"; print_r($affArray); echo "</pre>";
        if (!empty($affArray)) {
            $affQuery = implode(' OR ', $affArray);
        }
        // echo $affQuery;
        // echo "BranchId :" . $branch_id;

        $startval = FormUtil::getPassedValue("startval");
        $endval   = FormUtil::getPassedValue("endval");

        $itemsperpage = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 10,
                'GETPOST');
        $startnum     = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $this->view->assign('startnum', $startnum);
        $this->view->assign('itemsperpage', $itemsperpage);

        $searchquerymain = '';
        $append          = '';
        $joins           = '';

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
            // $append .= " AND a.shop_id IN(SELECT shop_id FROM zselex_shop_to_category WHERE category_id=$category_id) ";
            $append .= " AND cat.category_id=".DataUtil::formatForStore($category_id);
            $joins .= " INNER JOIN zselex_shop_to_category cat ON cat.shop_id=a.shop_id ";
        }

        if (!empty($branch_id)) {
            // $append .= " AND a.branch_id=$branch_id";

            $append .= " AND branch.branch_id=".DataUtil::formatForStore($branch_id);
            $joins .= " INNER JOIN zselex_shop_to_branch branch ON branch.shop_id=a.shop_id ";
        }

        if (!empty($affArray)) {
            // $append .= " AND a.aff_id IN($aff_ids) ";
            $append .= " AND ($affQuery)";
        }

        if (!empty($hsearch)) {
            $append .= " AND (a.shop_name LIKE '%".DataUtil::formatForStore($search)."%' OR MATCH (a.shop_name) AGAINST ('".DataUtil::formatForStore($search)."' IN BOOLEAN MODE) OR a.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE '%".DataUtil::formatForStore($search)."%' OR MATCH (keyword) AGAINST ('".DataUtil::formatForStore($search)."' IN BOOLEAN MODE)))";
        }
        // echo $append;

        $items = ModUtil::apiFunc('ZSELEX', 'user', 'getShopList',
                $args  = array(
                'start' => $startnum,
                'itemsperpage' => $itemsperpage,
                'append' => $append,
                'joins' => $joins
        ));
        $shops = $items ['items'];
        $count = $items ['count'];

        // echo "<pre>"; print_r($shops); echo "</pre>";
        foreach ($shops as $key => $val) {
            $shops [$key] ['linktoshop'] = ModUtil::apiFunc('ZSELEX', 'admin',
                    'serviceExistBlock',
                    $args                        = array(
                    'shop_id' => $val ['shop_id'],
                    'type' => 'linktoshop'
            ));
        }
        // echo "<pre>"; print_r($shops); echo "</pre>";
        $this->view->assign('total_count', $count);
        $this->view->assign('shops', $shops);
        $current_theme = System::getVar('Default_Theme');
        PageUtil::addVar('stylesheet',
            'themes/'.$current_theme.'/style/rating.css');

        return $this->view->fetch('user/shoplist.tpl');
    }

    /**
     * Delivery page
     * Discounts , shiiping , vat are calculated here
     *
     * @return html
     */
    public function delivery()
    {
        header("Cache-Control: max-age=300, must-revalidate");
        $_SESSION ['netaxept'] = array();
        // $user_id               = UserUtil::getVar('uid');

        $user_id = UserUtil::getVar('uid');
        if (!UserUtil::isLoggedIn()) {
            $user_id = ZSELEX_Util::getTempUserId();
        }


        $cart_shop_id = SessionUtil::getVar('cart_shop_id');

        if (empty($cart_shop_id)) {
            return $this->redirect(ModUtil::url('ZSELEX', 'user', 'cart'));
        }
        $cartError = ModUtil::apiFunc('ZSELEX', 'cart', 'validatecart',
                array('cart_shop_id' => $cart_shop_id));
        if ($cartError) {
            return $this->redirect(ModUtil::url('ZSELEX', 'user', 'cart'));
        }
        // $repo = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        $shop_id = $cart_shop_id;
        System::queryStringSetVar('shop_id', $shop_id);

        $cart_count = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->getCount(null,
            'ZSELEX_Entity_Cart', 'cart_id',
            array(
            'a.user_id' => $user_id,
            'a.shop' => $shop_id
        ));

        if ($cart_count < 1) {
            return $this->redirect(ModUtil::url('ZSELEX', 'user', 'cart'));
        }

        // echo "comes here"; exit;

        $shop_name     = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getSingleResult(array(
            'field' => 'shop_name',
            'where' => "a.shop_id=$shop_id"
        ));
        $delivery_time = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getSingleResult(array(
            'field' => 'delivery_time',
            'where' => "a.shop_id=$shop_id"
        ));
        PageUtil::setVar('title', $this->__("Delivery - ".$shop_name));
        if (!in_array('delivery', $_SESSION ['cart_menu'])) {
            $_SESSION ['cart_menu'] [] = 'delivery';
        }
        if ($_POST) {
            $_SESSION ['checkoutinfo']                   = $_POST;
            $_SESSION ['checkoutinfo'] ['shipping_info'] = $_POST;
            // $user_email = UserUtil::getVar('email');
            $user_email                                  = FormUtil::getPassedValue('email',
                    null, 'REQUEST');

            //  $user_id                                     = UserUtil::getVar('uid');
            if ($_POST ['subscribe'] == true) {
                $subscribe            = $this->entityManager->getRepository('ZSELEX_Entity_Newsletter')->subscribeUser(array(
                    'user_id' => $user_id,
                    'email' => $user_email,
                    'shop_id' => $shop_id
                ));
                // KIMENEMARK 17.12.2016
                $shop_mailinglist_url = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getSingleResult(array(
                    'field' => 'link_to_mailinglist',
                    'where' => "a.shop_id=$shop_id"
                ));
//$shop_mailinglist_url = "http://klubliste.keeprunning.dk/lists/lcsub.php?pwd=Dojo8291&email=";
                if ($shop_mailinglist_url) {
                    $mlisturl  = $shop_mailinglist_url.$user_email;
                    // echo "mlisturl=[" . $mlisturl . "]";
                    $cch       = curl_init();
                    curl_setopt($cch, CURLOPT_URL, $mlisturl);
                    curl_setopt($cch, CURLOPT_POST, true);
                    curl_setopt($cch, CURLOPT_RETURNTRANSFER, true);
                    $cchresult = curl_exec($cch);
                    curl_close($cch);
                }
                //
            }
        }

        // echo $user_email; exit;
        $_SESSION ['checkoutinfo'] ['shop_id'] = $shop_id;

        $menu_items = array(
            'paymentoptions'
        );
        ModUtil::apiFunc('ZSELEX', 'cart', 'unsetCartMenu', $menu_items);

        // echo "<pre>"; print_r($_SESSION['cart_menu']); echo "</pre>";

        if (empty($cart_shop_id)) {
            return $this->redirect(ModUtil::url('ZSELEX', 'user', 'cart'));
        }

        //echo "comes here2"; exit;

        $cart_status = $_SESSION ['cartstatus'] [$shop_id];

        $_SESSION ['checkoutinfo'] ['cartstatus'] = $cart_status;

        $getTotArgs      = array(
            'setParams' => array(
                'user_id' => $user_id,
                'shop_id' => $shop_id
            )
        );
        $getTotal        = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->getCartTotalShop($getTotArgs);
        $getShipping     = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->getTotalShipping($getTotArgs);
        $noDeliveryCount = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->getNoDeliveryCount($getTotArgs);
        //echo "<pre>"; print_r($getShipping); echo "</pre>";

        $origTotal = $getTotal ['grandTotal'];
        // $noDeliveryNoCount = $getShipping['no_delivery_no_count'];

        $freight = $this->entityManager->getRepository('ZPayment_Entity_FreightSetting')->get(array(
            'entity' => 'ZPayment_Entity_FreightSetting',
            'fields' => array(
                'a.std_freight_price',
                'a.zero_freight_price',
                'a.enabled'
            ),
            'where' => array(
                'a.shop_id' => $shop_id
            )
        ));

        // KIMENEMARK
        // WE HAVE TO GET THESE VALUES FROM SOMEWHERE!!!
        $DISCOUNT = 0; // %
        $SHIPPING = 0;

        // echo $origTotal;
        $SHIPPING      = $getShipping ['shippingTotal'];
        $freighApplied = 0;
        if (($origTotal < $freight ['zero_freight_price']) || ($freight ['zero_freight_price']
            == '') || ($freight ['zero_freight_price'] == 0)) {
            // echo "comes here..";
            if ($getShipping ['shippingTotal'] > 0) {
                $SHIPPING = $getShipping ['shippingTotal'];
            } elseif ($freight ['enabled']) {
                $SHIPPING      = $freight ['std_freight_price'];
                $freighApplied = 1;
            }
        }

        // echo $SHIPPING;

        $discountValue = $_SESSION ['checkoutinfo'] ['discount_value'];

        $vat_args      = array(
            'user_id' => $user_id,
            'shop_id' => $shop_id
        );
        $get_vat_total = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->getVatTotal($vat_args);
        // echo $get_vat_total . '<br>'; exit;
        $get_vat_total = $get_vat_total - $discountValue;

        $selfPickUp = $_SESSION ['checkoutinfo'] ['self_pickup'];

        $GRANDTOTAL = $getTotal ['grandTotal'];
        $GRANDTOTAL = $GRANDTOTAL - ($GRANDTOTAL * $DISCOUNT);

        $_SESSION ['checkoutinfo'] ['shipping'] = $SHIPPING;
        if ($selfPickUp) {
            $SHIPPING = 0;
        }

        $VAT = ($get_vat_total + $SHIPPING) * 0.2;
        // $_SESSION['VAT'] = $VAT;

        $_SESSION ['checkoutinfo'] ['VAT'] = $VAT;

        $GRANDTOTAL = $GRANDTOTAL;

        if ($selfPickUp) {
            $SHIPPING = 0;
        }
        $GRANDTOTAL_ALL = $GRANDTOTAL + $SHIPPING;

        $_SESSION ['checkoutinfo'] ['totalprice']      = $GRANDTOTAL;
        $_SESSION ['checkoutinfo'] ['grand_total_all'] = $GRANDTOTAL_ALL;

        $finalPrice = $GRANDTOTAL_ALL - $discountValue;

        $_SESSION ['checkoutinfo'] ['final_price'] = $finalPrice;

        $DISCOUNT = $_SESSION ['checkoutinfo'] ['discount'];



        if (empty($delivery_time)) {
            $delivery_time = $this->__('3-5 business days');
        }
        $this->view->assign('delivery_time', $delivery_time);
        $this->view->assign('userinfo',
            $_SESSION ['checkoutinfo'] ['shipping_info']);
        $this->view->assign('cartstatus', $cart_status);
        $this->view->assign('origTotal', $origTotal);
        $this->view->assign('GRANDTOTAL', $GRANDTOTAL);
        $this->view->assign('GRANDTOTAL_ALL', $GRANDTOTAL_ALL);
        $this->view->assign('DISCOUNT', $DISCOUNT);
        $this->view->assign('SHIPPING', $SHIPPING);
        $this->view->assign('VAT', $VAT);
        // $this->view->assign('freight', $freight);
        $this->view->assign('final_price', $finalPrice);

        $shop_details = $this->entityManager->getRepository('ZSELEX_Entity_ShopDetail')->getShopDetails(array(
            'shop_id' => $shop_id
        ));
        $this->view->assign('shop_details', $shop_details);
        $thislang     = ZLanguage::getLanguageCode();
        $this->view->assign('thislang', $thislang);
        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('noDeliveryCount', $noDeliveryCount);
        $this->view->assign('shippingTotal', $getShipping ['shippingTotal']);

        $showCheckbox = 0;
        if ($getShipping ['shippingTotal'] > 0) {
            $showCheckbox = 1;
            // echo "comes ghere";
        } elseif ($noDeliveryCount > 0) {
            if ($freighApplied) {
                $showCheckbox = 1;
            } else {
                $showCheckbox = 0;
            }
        }
        $this->view->assign('showCheckbox', $showCheckbox);

        return $this->view->fetch('user/delivery.tpl');
    }

    public function delivery1()
    {
        // $_SESSION['user_cart'] = array();
        // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
        // $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        //  $_SESSION['checkoutinfo'] = array();
        $_SESSION ['netaxept'] = array();
        // $user_id               = UserUtil::getVar('uid');

        $user_id = UserUtil::getVar('uid');
        if (!UserUtil::isLoggedIn()) {
            $user_id = ZSELEX_Util::getTempUserId();
        }
        // $cart_shop_id = $_SESSION ['cart_shop_id'];

        $cart_shop_id = SessionUtil::getVar('cart_shop_id');

        //  echo $cart_shop_id; exit;

        if (empty($cart_shop_id)) {
            return $this->redirect(ModUtil::url('ZSELEX', 'user', 'cart'));
        }
        // $repo = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        $shop_id = $cart_shop_id;
        System::queryStringSetVar('shop_id', $shop_id);

        $cart_count = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->getCount(null,
            'ZSELEX_Entity_Cart', 'cart_id',
            array(
            'a.user_id' => $user_id,
            'a.shop' => $shop_id
        ));

        if ($cart_count < 1) {
            return $this->redirect(ModUtil::url('ZSELEX', 'user', 'cart'));
        }

        // echo "comes here"; exit;

        $shop_name     = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getSingleResult(array(
            'field' => 'shop_name',
            'where' => "a.shop_id=$shop_id"
        ));
        $delivery_time = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getSingleResult(array(
            'field' => 'delivery_time',
            'where' => "a.shop_id=$shop_id"
        ));
        PageUtil::setVar('title', $this->__("Delivery - ".$shop_name));
        if (!in_array('delivery', $_SESSION ['cart_menu'])) {
            $_SESSION ['cart_menu'] [] = 'delivery';
        }
        if ($_POST) {
            $_SESSION ['checkoutinfo']                   = $_POST;
            $_SESSION ['checkoutinfo'] ['shipping_info'] = $_POST;
            // $user_email = UserUtil::getVar('email');
            $user_email                                  = FormUtil::getPassedValue('email',
                    null, 'REQUEST');
            //  $user_id                                     = UserUtil::getVar('uid');
            if ($_POST ['subscribe'] == true) {
                $subscribe = $this->entityManager->getRepository('ZSELEX_Entity_Newsletter')->subscribeUser(array(
                    'user_id' => $user_id,
                    'email' => $user_email,
                    'shop_id' => $shop_id
                ));
            }
        }

        // echo $user_email; exit;
        $_SESSION ['checkoutinfo'] ['shop_id'] = $shop_id;
        // echo "Cart staus : " . $_SESSION['cartstatus'][$shop_id];
        // echo "<pre>"; print_r($_SESSION['cart'][$shop_id]); echo "</pre>";
        // echo "<pre>"; print_r($_SESSION['checkoutinfo']); echo "</pre>";
        /*
         * $menu_key = array_search('paymentoptions', $_SESSION['cart_menu']);
         * if ($menu_key) {
         * unset($_SESSION['cart_menu'][$menu_key]);
         * }
         */
        $menu_items                            = array(
            'paymentoptions'
        );
        ModUtil::apiFunc('ZSELEX', 'cart', 'unsetCartMenu', $menu_items);

        // echo "<pre>"; print_r($_SESSION['cart_menu']); echo "</pre>";

        if (empty($cart_shop_id)) {
            return $this->redirect(ModUtil::url('ZSELEX', 'user', 'cart'));
        }

        //echo "comes here2"; exit;

        $cart_status                            = $_SESSION ['cartstatus'] [$shop_id];
        $_SESSION ['checkoutinfo'] [cartstatus] = $cart_status;

        $getTotArgs = array(
            'setParams' => array(
                'user_id' => $user_id,
                'shop_id' => $shop_id
            )
        );
        $getTotal   = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->getCartTotalShop($getTotArgs);
        // echo "<pre>"; print_r($getTotal); echo "</pre>";
        // echo "<pre>"; print_r($getTotal2); echo "</pre>";
        $origTotal  = $getTotal ['grandTotal'];

        $freight = $this->entityManager->getRepository('ZPayment_Entity_FreightSetting')->get(array(
            'entity' => 'ZPayment_Entity_FreightSetting',
            'fields' => array(
                'a.std_freight_price',
                'a.zero_freight_price',
                'a.enabled'
            ),
            'where' => array(
                'a.shop_id' => $shop_id
            )
        ));

        //$_SESSION ['checkoutinfo'] ['self_pickup'] = 0;
        //$_SESSION ['checkoutinfo'] ['discount_applied'] = 1;
        // echo "<pre>"; print_r($freight); echo "</pre>";
        // KIMENEMARK
        // WE HAVE TO GET THESE VALUES FROM SOMEWHERE!!!
        $DISCOUNT = 0; // %
        $SHIPPING = 0;

        // echo $origTotal;
        $SHIPPING = $getTotal ['shippingTotal'];
        if (($origTotal < $freight ['zero_freight_price']) || ($freight ['zero_freight_price']
            == '') || ($freight ['zero_freight_price'] == 0)) {
            // echo "comes here..";
            if ($getTotal ['shippingTotal'] > 0) {
                $SHIPPING = $getTotal ['shippingTotal'];
            } elseif ($freight ['enabled']) {
                $SHIPPING = $freight ['std_freight_price'];
            }
        }

        // echo $SHIPPING;

        $discountValue = $_SESSION ['checkoutinfo'] ['discount_value'];

        $vat_args      = array(
            'user_id' => $user_id,
            'shop_id' => $shop_id
        );
        $get_vat_total = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->getVatTotal($vat_args);
        // echo $get_vat_total . '<br>'; exit;
        $get_vat_total = $get_vat_total - $discountValue;
        //echo $get_vat_total . '<br>';
        // echo "<pre>"; print_r($get_vat_total); echo "</pre>";
        // echo "SHIPPING :" . $SHIPPING;
        // $GRANDTOTAL = array_sum($grandPrice);
        $selfPickUp    = $_SESSION ['checkoutinfo'] ['self_pickup'];

        $GRANDTOTAL = $getTotal ['grandTotal'];
        $GRANDTOTAL = $GRANDTOTAL - ($GRANDTOTAL * $DISCOUNT);

        // $VAT = $GRANDTOTAL * 0.2; // DKK VAT = 25%
        // $VAT = ($GRANDTOTAL + $SHIPPING) * 0.2;
        // $VAT = ($get_vat_total['total'] + $SHIPPING) * 0.2;

        $_SESSION ['checkoutinfo'] ['shipping'] = $SHIPPING;
        if ($selfPickUp) {
            $SHIPPING = 0;
        }

        $VAT = ($get_vat_total + $SHIPPING) * 0.2;
        // $_SESSION['VAT'] = $VAT;

        $_SESSION ['checkoutinfo'] ['VAT'] = $VAT;
        // $_SESSION ['checkoutinfo'] ['shipping'] = $SHIPPING; // Original here.
        // $_SESSION['checkoutinfo']['freight'] = $FREIGHT_PRICE;
        // $GRANDTOTAL = $GRANDTOTAL + $SHIPPING;
        $GRANDTOTAL                        = $GRANDTOTAL;
        // $GRANDTOTAL_ALL = $GRANDTOTAL + $_SESSION['VAT'];
        if ($selfPickUp) {
            $SHIPPING = 0;
        }
        $GRANDTOTAL_ALL                                = $GRANDTOTAL + $SHIPPING;
        // $GRANDTOTAL_ALL = $GRANDTOTAL;
        $_SESSION ['checkoutinfo'] ['totalprice']      = $GRANDTOTAL;
        $_SESSION ['checkoutinfo'] ['grand_total_all'] = $GRANDTOTAL_ALL;
        // $discountValue = $_SESSION ['checkoutinfo'] ['discount_value'];
        //echo "Discount Value:" . $discountValue;
        $finalPrice                                    = $GRANDTOTAL_ALL - $discountValue;
        $_SESSION ['checkoutinfo'] ['final_price']     = $finalPrice;
        $DISCOUNT                                      = $_SESSION ['checkoutinfo'] ['discount'];
        // echo "<br>Grand Total :" . $GRANDTOTAL;
        //  echo "Final Price :" . $finalPrice;
        // array_push($_SESSION['cart'][$shop_id] , array('PRODUCTNAME'=>'VAT' , 'price'=>0.2));
        // $_SESSION['cart'][$shop_id] = array_values($_SESSION['cart'][$shop_id]);
        // echo "<pre>"; print_r($_SESSION['cart'][$shop_id]); echo "</pre>";
        // $this->view->assign('userinfo', $_SESSION['checkoutinfo']);
        // echo "<pre>"; print_r($freight); echo "</pre>";
        // echo "<pre>";   print_r($_SESSION['checkoutinfo']);   echo "</pre>";



        if (empty($delivery_time)) {
            $delivery_time = $this->__('3-5 business days');
        }
        $this->view->assign('delivery_time', $delivery_time);
        $this->view->assign('userinfo',
            $_SESSION ['checkoutinfo'] ['shipping_info']);
        $this->view->assign('cartstatus', $cart_status);
        $this->view->assign('origTotal', $origTotal);
        $this->view->assign('GRANDTOTAL', $GRANDTOTAL);
        $this->view->assign('GRANDTOTAL_ALL', $GRANDTOTAL_ALL);
        $this->view->assign('DISCOUNT', $DISCOUNT);
        $this->view->assign('SHIPPING', $SHIPPING);
        $this->view->assign('VAT', $VAT);
        // $this->view->assign('freight', $freight);
        $this->view->assign('final_price', $finalPrice);

        $shop_details = $this->entityManager->getRepository('ZSELEX_Entity_ShopDetail')->getShopDetails(array(
            'shop_id' => $shop_id
        ));
        $this->view->assign('shop_details', $shop_details);
        $thislang     = ZLanguage::getLanguageCode();
        $this->view->assign('thislang', $thislang);
        $this->view->assign('shop_id', $shop_id);

        return $this->view->fetch('user/delivery.tpl');
    }

    public function showRating($params)
    {
        if (UserUtil::isLoggedIn()) {
            // PageUtil::addVar("javascript", "modules/ZSELEX/javascript/star_jquery.js");
            PageUtil::addVar("javascript", "modules/ZSELEX/javascript/rate.js");
        }
        // PageUtil::addVar('stylesheet', 'themes/CityPilot/style/rating.css');
        $shop_id = $params ['shop_id'];
        if (empty($shop_id) || !(int) $shop_id) {
            return;
        }
        // echo "<input type='hidden' id='shop_id' value=$shop_id>";
        $this->view->assign('shop_id', $shop_id);
        $x = '';

        $ratingCount = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount',
                $count_args  = array(
                'table' => 'zselex_shop_ratings',
                'where' => "shop_id=$shop_id"
        ));
        if ($ratingCount == 1) {
            $v = 'vote';
        } else {
            $v = 'votes';
        }

        $ratings   = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray',
                $rate_args = array(
                'table' => 'zselex_shop_ratings',
                'where' => array(
                    "shop_id=$shop_id"
                )
        ));
        $this->view->assign('ratings', $ratings);
        $this->view->assign('v', $v);

        foreach ($ratings as $key => $val) {
            $rr = $val ["rating"]; // EACH RATING FOR THE CONTENT
            $x += $rr; // ADDS THEM ALL UP
        }
        // echo "X :" . $x;
        // IF THERE ARE RATINGS...
        if ($ratingCount) {
            $rating = $x / $ratingCount; // THE AVERAGE RATING (UNROUNDED)
        } else {
            $rating = 0; // SET TO PREVENT THE ERROR OF DIVISION BY 0, WHICH WOULD BE THE NUMBER OF RATINGS HERE
        }

        $dec_rating = round($rating, 1); // ROUNDED RATING TO THE NEAREST TENTH
        // $dec_rating = round($rating);
        $stars      = '';
        $y          = '';
        $this->view->assign('isLoggedIn', UserUtil::isLoggedIn());
        $this->view->assign('rating', $rating);
        $this->view->assign('dec_rating', $dec_rating);
        $this->view->assign('ratingCount', $ratingCount);
        $this->view->assign('v', $v);
        // SHOWS THE FULL NUMBER OF STARS (Ex: 3.5 stars = 3 full stars)
        for ($i = 1; $i <= floor($rating); $i ++) {
            $stars .= '<div class="star" id="'.$i.'"></div>';
        }

        if (UserUtil::isLoggedIn()) {
            $user_id        = $params ['user_id'];
            $userRating     = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow',
                    $user_rate_args = array(
                    'table' => 'zselex_shop_ratings',
                    'where' => array(
                        "shop_id=$shop_id",
                        "user_id=$user_id"
                    )
            ));
            if ($userRating ['rating']) {
                // $user_rated = '<div>You rated this a <b>' . $userRating[rating] . '</b></div>';
                $this->view->assign('userRating', $userRating ['rating']);
            }
        }

        // THE OVERALL RATING (THE OPAQUE STARS)
        /*
         * echo '<div class="r">
         * <div class="rating">' . $stars . '</div>';
         *
         * //THE TRANSPARENT STARS (OPAQUE STARS WILL COVER AS MANY STARS AS THE RATING REPRESENTS)
         * echo '<div class="transparent">
         * <div class="star" id="1"></div>
         * <div class="star" id="2"></div>
         * <div class="star" id="3"></div>
         * <div class="star" id="4"></div>
         * <div class="star" id="5"></div>
         * <div class="votes">&nbsp;(' . $dec_rating . '/5, ' . $ratingCount . ' ' . $v . ')</div>
         * </div>
         * </div>
         * <br>';
         * echo $user_rated;
         */
        return $this->view->fetch('user/showRating.tpl');
    }

    public function errorss()
    {
        echo "helloo";
        exit();
        return LogUtil::registerError($this->__('cannot load this page'));
    }

    /**
     * @Return URL function from paypal
     *
     * @return s order_id , and shop_id
     *         @Updates the order status to success/failed in Order table
     */
    public function payPalReturnServicePaid()
    {

        // echo "order id : " . $_REQUEST['order_id'] . '<br>'; exit;
        // echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
        $ordr    = $_REQUEST ['order_id']; // retrieve order id from paypal.
        // $orderno = $_SESSION["ss_last_orderno"];
        $orderno = $ordr;

        // $orderno = $_SESSION['checkoutinfo'][order_id];
        // $ppAcc = "seller_1357558142_biz@.com";
        $ppAcc = "r2internation-facilitator@india.com";
        // $at = "D_fA7ggeD4MVD9j9jtnWJ9xBOM0z_-RuGnkCSb8O9mCRVAJhtF__cC0njmW"; //PDT Identity Token
        $at    = "FGqDnRnt53o_e7z590SMm4qRTKxvoUYAgGIFCI6uQUEZweh9T2PXI2yZ8Vu"; // PDT Identity Token
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
        if ($lines [0] == "SUCCESS") {
            // successful payment
            $ppInfo = array();
            for ($i = 1; $i < count($lines); $i ++) {
                $parts = split("=", $lines [$i]);
                if (count($parts) == 2) {
                    $ppInfo [$parts [0]] = urldecode($parts [1]);
                }
            }

            $curtime  = gmdate("d/m/Y H:i:s");
            // capture the PayPal returned information as order remarks
            $oremarks = "##$curtime##\n"."PayPal Transaction InformationÃ¢â‚¬Â¦<br>"."Txn Id: ".$ppInfo ["txn_id"]."<br>"."Txn Type: ".$ppInfo ["txn_type"]."<br>"."Item Number: ".$ppInfo ["item_number"]."<br>"."Payment Date: ".$ppInfo ["payment_date"]."<br>"."Payment Type: ".$ppInfo ["payment_type"]."<br>"."Payment Status: ".$ppInfo ["payment_status"]."<br>"."Currency: ".$ppInfo ["mc_currency"]."<br>"."Payment Gross: ".$ppInfo ["payment_gross"]."<br>"."Payment Fee: ".$ppInfo ["payment_fee"]."<br>"."Payer Email: ".$ppInfo ["payer_email"]."<br>"."Payer Id: ".$ppInfo ["payer_id"]."<br>"."Payer Name: ".$ppInfo ["first_name"]." ".$ppInfo ["last_name"]."<br>"."Payer Status: ".$ppInfo ["payer_status"]."<br>"."Country: ".$ppInfo ["residence_country"]."<br>"."Business: ".$ppInfo ["business"]."<br>"."Receiver Email: ".$ppInfo ["receiver_email"]."<br>"."Receiver Id: ".$ppInfo ["receiver_id"]."<br>";

            // Update database using $orderno, set status to Paid
            // Send confirmation email to buyer and notification email to merchant
            // Redirect to thankyou page
            // echo $oremarks;

            $args          = array(
                'table' => 'zselex_order',
                'items' => array(
                    'status' => "success"
                ),
                'where' => array(
                    'order_id' => "'$orderno'"
                )
            );
            $updateOrderId = ModUtil::apiFunc('ZSELEX', 'admin',
                    'updateElementWhere', $args);
            $shopsId       = $_REQUEST ['cm'];

            $orderDetails = ModUtil::apiFunc('ZSELEX', 'user',
                    'showPurchedOrder',
                    $args         = array(
                    'order_id' => $orderno
            ));

            // echo "<pre>"; print_r($orderDetails); echo "</pre>";

            $this->view->assign('order_id', $orderno);
            $this->view->assign('orderDetails', $orderDetails);
            $this->view->assign('shop_id', $shopsId);
            $this->view->assign('reciept', $oremarks);
            return $this->view->fetch('user/thankyou.tpl');
        }

        // Payment failed
        else {
            // echo "Failed....";

            $args          = array(
                'table' => 'zselex_order',
                'items' => array(
                    'status' => "Failed"
                ),
                'where' => array(
                    'order_id' => "'$orderno'"
                )
            );
            $updateOrderId = ModUtil::apiFunc('ZSELEX', 'admin',
                    'updateElementWhere', $args);
            return $this->view->fetch('user/pperror.tpl');
            // Delete order information
            // Redirect to failed page
        }

        // exit;
    }

    public function clearCart()
    {
        if (!empty($_COOKIE ['cart'])) {
            foreach ($_COOKIE ['cart'] as $shop_id => $val) {
                foreach ($_COOKIE ['cart'] [$shop_id] as $key1 => $val1) {
                    setcookie("cart[$shop_id][$key1]", "", time() - 604800, '/');
                }
            }
            unset($_SESSION ['cart']);
        }
        unset($_SESSION ['temp_cart']);
        setcookie("temp_cart", "", time() - 604800, '/');
    }

    public function exclusiveEvents()
    {
        return $this->view->fetch('user/exclusive_events.tpl');
    }

    public function cart_user($args)
    {

        // EventUtil::registerPersistentModuleHandler('ZSELEX', 'user.account.update', array('ZSELEX_Listener_User', 'update'));

        /*
         * $fspath = "zselexdata/jabbar";
         * $newpath = "zselexdata/khan";
         * if (file_exists($fspath)) {
         * rename($fspath, $newpath);
         * }
         */
        // unset($_SESSION['cart_menu']['checkout']);
        // echo "Temp User ID : " . ZSELEX_Util::getTempUserId();
        $menu_items = array(
            'checkout',
            'delivery',
            'paymentoptions'
        );
        ModUtil::apiFunc('ZSELEX', 'cart', 'unsetCartMenu', $menu_items);
        // ModUtil::apiFunc('ZSELEX', 'cart', 'unsetCartMenu', 'delivery');
        // echo "<pre>"; print_r($_SESSION['cart_menu']); echo "</pre>";
        //  $user_id      = UserUtil::getVar('uid');

        $user_id = UserUtil::getVar('uid');
        if (!UserUtil::isLoggedIn()) {
            $user_id = ZSELEX_Util::getTempUserId();
        }
        $last_shop_id = $_COOKIE ['last_shop_id'];
        $this->view->assign('last_shop_id', $last_shop_id);
        // $validateCart = ModUtil::apiFunc('ZSELEX', 'cart', 'validatecart');

        $productArr            = array();
        $products              = array();
        $_SESSION ['netaxept'] = array();
        //  if (UserUtil::isLoggedIn()) {
        if ($user_id) {
            $validateCart = ModUtil::apiFunc('ZSELEX', 'cart', 'validatecart');

            $fields    = array(
                'a.cart_id',
                'a.quantity',
                'c.shop_id',
                'a.price',
                'a.final_price',
                'a.cart_content',
                'a.outofstock',
                'b.product_name',
                'b.prd_price',
                'b.prd_description',
                'b.prd_image',
                'b.product_id',
                'a.prd_answer',
                'b.prd_question',
                'b.enable_question',
                'a.stock',
            );
            $setParams = array(
                'uid' => $user_id
            );
            $where     = "a.user_id=:uid";
            $products  = $this->entityManager->getRepository('ZSELEX_Entity_Cart')->getCartProducts(array(
                'user_id' => $user_id,
                'fields' => $fields,
                'where' => $where,
                'setParams' => $setParams
            ));
            // echo "<pre>"; print_r($products); echo "</pre>";
            foreach ($products as $key => $val) {
                // echo "<pre>"; print_r(unserialize($val['cart_content'])); echo "</pre>";
                $productArr [$val ['shop_id']] [] = $val;
            }
        } else {
            $validateCart = ModUtil::apiFunc('ZSELEX', 'cart',
                    'validatecartGuest');
            /*
             * if (($_SESSION['temp_cart'] > json_decode($_COOKIE['temp_cart'], true) || $_SESSION['temp_cart'] == json_decode($_COOKIE['temp_cart'], true))) {
             * $productArr = $_SESSION['temp_cart'];
             * } else {
             * $productArr = json_decode($_COOKIE['temp_cart'], true);
             * }
             */
            $productArr   = ModUtil::apiFunc('ZSELEX', 'cart', 'getTempCart');
            // $productArr = json_decode($_SESSION['temp_cart'], true);
            // $productArr = $_SESSION['temp_cart'];
        }
        // echo "<pre>"; print_r($productArr); echo "</pre>";

        $this->view->assign('products', $productArr);
        return $this->view->fetch('user/cart.tpl');
    }

    public function update_cart1($cart_products)
    {
        // Zikula_View::getInstance('ZSELEX')->setCaching(false);
        // echo "<pre>"; print_r($_SESSION['cart']);
        $user_id         = UserUtil::getVar('uid');
        $cart_searialize = '';
        $cart_searialize = serialize($cart_products);

        $count = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount',
                $args  = array(
                'table' => 'zselex_cart',
                "where" => "user_id=$user_id"
        ));
        if ($count < 1) {
            $cart_item   = array(
                'user_id' => $user_id,
                'cart_content' => $cart_searialize
            );
            $create_args = array(
                'table' => 'zselex_cart',
                'element' => $cart_item,
                'Id' => 'cart_id'
            );
            $insert      = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement',
                    $create_args);
        } else {

            $pntables             = pnDBGetTables();
            $column               = $pntables ['zselex_cart_column'];
            $obj ['cart_content'] = $cart_searialize;
            // $products = @array_filter($products);
            $where                = "WHERE $column[user_id]=$user_id";
            DBUTil::updateObject($obj, 'zselex_cart', $where);
        }
        ZSELEX_Controller_User::clearCart();
        return true;
    }

    /**
     * Update cart after successfull payment
     * 
     * @param int $shop_id
     * @param int $order_id
     * @return boolean
     */
    public function update_cart($shop_id, $order_id, $updateQuantity = true)
    {

        $repo    = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        // $user_id = UserUtil::getVar('uid');
        $user_id = UserUtil::getVar('uid');
        if (!UserUtil::isLoggedIn()) {
            $user_id = ZSELEX_Util::getTempUserId();
        }
        // $delete = DBUTil::deleteWhere('zselex_cart', "user_id=$user_id AND shop_id=$shop_id");
        $delete = $repo->deleteEntity(null, 'ZSELEX_Entity_Cart',
            array(
            'a.user_id' => $user_id,
            'a.shop' => $shop_id
        ));
        // echo "Comes here5"; exit;
        // echo $order_id; exit;
        if (isset($order_id) && !empty($order_id)) {
            if ($updateQuantity == true) {
                ModUtil::apiFunc('ZSELEX', 'cart', 'updateProductOptions',
                    array(
                    'order_id' => $order_id
                ));
            }
        }
        return true;
    }

    public function addToCart($args)
    {
        $product_id   = $args ['product_id'];
        $productName  = $args ['productName'];
        $quantity     = $args ['cart_quantity'];
        $productprice = $args ['product_price'];
        $productdesc  = $args ['productDesc'];
        $productimg   = $args ['productImage'];
        $service      = $args ['service'];
        $shop_id      = $args ['shop_id'];
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
            $_SESSION ['cart'] [$_POST ['shop_id']] [] = array(
                'PRODUCTID' => $product_id,
                'PRODUCTNAME' => $productName,
                'SHOPID' => $_POST ['shop_id'],
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
            LogUtil::registerStatus($this->__('Product added to cart successfully'));
        } else {
            LogUtil::registerStatus($this->__('This Product Is Already In Your Cart.'));
        }
    }

    /**
     * Netaxept return after payment
     *
     * @param GETPOST
     * @return Redirect
     */
    public function netsReturn()
    {

        $_SESSION ['netaxept'] = array();
        $responseCode          = FormUtil::getPassedValue('responseCode', null,
                'REQUEST');
        $transactionId         = FormUtil::getPassedValue('transactionId', null,
                'REQUEST');
        $orderId               = FormUtil::getPassedValue('orderId', null,
                'REQUEST');
        $source                = FormUtil::getPassedValue('source', null,
                'REQUEST');

        $cart_shop_id = FormUtil::getPassedValue('cart_shop_id', null, 'REQUEST');
        $shop_id      = $cart_shop_id;

        $orderInfo = ModUtil::apiFunc('ZPayment', 'Netaxept', 'getShopId',
                array(
                'transaction_id' => $transactionId
        ));
        $shop_id   = $orderInfo ['shop_id'];
        $orderId   = $orderInfo ['order_id'];

        $ownerInfo = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwnerInfo',
                $args      = array(
                'shop_id' => $shop_id
        ));

        $emails [] = 'sharazkhanz@gmail.com';
        $emails [] = $ownerInfo ['email'];
        $emails [] = 'kim@acta-it.dk';


        $pntables        = pnDBGetTables();
        $column          = $pntables ['zselex_order_column'];
        $where           = "WHERE $column[order_id]='".$orderId."'";
        $nets_where      = "WHERE zselex_order_id='".$orderId."' AND nets_transaction_id='".$transactionId."'";
        $order_status    = '';
        $netaxept_status = '';
        $info            = '';


        if ($responseCode == 'OK') {

            $order_status    = 'Success';
            $netaxept_status = 'Success';

            $query_call = ModUtil::apiFunc('ZPayment', 'Netaxept', 'query_call',
                    array(
                    'transaction_id' => $transactionId,
                    'shop_id' => $shop_id
            ));
            // echo "<pre>"; print_r($query_call); echo "</pre>"; exit;
            if ($query_call ['error'] == 1) { // Error
                // echo "error occured!!!!";
                $error_info   = $query_call ['result'] ['detail']->AuthenticationException->Message;
                $order_status = 'Failed';
                $info         = $query_call ['result']->Error->ResponseText;
                // echo "Querycall firt error : " . $info; exit;
                // $err_msg = "Nets Error Code :" . $query_call['result']->Error->ResponseCode . '\n';
                // $err_msg .= "Nets Error Message :" . $query_call['result']->Error->ResponseText . '\n\n' . var_export($query_call, true);
                // mail("kim@acta-it.dk", $_SERVER['HTTP_HOST'] . " - netaxept info QUERY error after Process", $err_msg);
                $err_msg      = $this->__("Nets Error Code")." : ".$query_call ['result']->Error->ResponseCode.'<br>';
                $err_msg .= $this->__("Nets Error Message")." : ".$query_call ['result']->Error->ResponseText." ".$this->__('Netaxept info QUERY error after Process').'<br>';
                $err_msg .= $this->__("Server")." :".$_SERVER ['SERVER_NAME'].'<br>';
                $err_msg .= $this->__("Date")." :".date('Y-m-d h:i:s a', time()).'<br>';
                $err_msg .= $this->__("Shop ID")." : ".$shop_id.'<br>';
                $err_msg .= $this->__("Module").': ZPayment<br>';
                $err_msg .= $this->__("User ID").' : '.UserUtil::getVar('uid').'<br>';
                $err_msg .= $this->__("User Name").' : '.UserUtil::getVar('uname').'<br>';
                $err_msg .= $this->__("User Email").' : '.UserUtil::getVar('email').'<br>';
                // mail("kim@acta-it.dk", $_SERVER['HTTP_HOST'] . " - netaxept info QUERY error after Process", $err_msg);
                // mail("sharazkhanz@gmail.com", $_SERVER['HTTP_HOST'] . " - netaxept info QUERY error after Process", $err_msg);

                foreach ($emails as $email) {
                    $mailer_args = array(
                        'toaddress' => $email,
                        'fromname' => 'ZSELEX',
                        'subject' => 'Netaxept Error',
                        'body' => $err_msg,
                        'html' => true
                    );

                    $sent = ModUtil::apiFunc('Mailer', 'user', 'sendMessage',
                            $mailer_args);
                }
            } else {

                // QUERY ok, we need to AUTH

                $auth_call = ModUtil::apiFunc('ZPayment', 'Netaxept',
                        'auth_call',
                        array(
                        'transaction_id' => $transactionId,
                        'shop_id' => $shop_id
                ));

                // echo $auth_call['error']; exit;
                if ($auth_call ['error'] == 1) { // Auth Error!
                    // echo "<pre>"; print_r($auth_call); echo "</pre>"; exit;
                    // echo "error occured!!!!";
                    $error_info      = "Ukendt fejlkode: ".$auth_call ['result'] ['detail']->BBSException->Result->ResponseCode.", ".$auth_call ['result'] ['faultstring'];
                    $order_status    = 'Failed';
                    $netaxept_status = 'Failed';
                    $info            = $auth_call ['result'] ['faultstring'];
                    // echo "Auth error : " . $info; exit;
                    // echo "errorMsg : " . $error_info; ; exit;
                    $err_msg         = $this->__("Nets Error Code")." :".$auth_call ['result'] ['detail']->BBSException->Result->ResponseCode.'<br>';
                    // $err_msg .= "Nets Error Message :" . $auth_call['result']['faultstring'] . '\n\n' . var_export($auth_call, true);
                    $err_msg .= $this->__("Nets Error Message")." :".$auth_call ['result'] ['faultstring'].'<br>';
                    // mail("kim@acta-it.dk", $_SERVER['HTTP_HOST'] . " - netaxept info AUTH error", $err_msg);
                    // mail("sharazkhanz@gmail.com", $_SERVER['HTTP_HOST'] . " - netaxept info AUTH error", $err_msg);
                    $err_msg .= $this->__("Server")." :".$_SERVER ['SERVER_NAME'].'<br>';
                    $err_msg .= $this->__("Date")." :".date('Y-m-d h:i:s a',
                            time()).'<br>';
                    $err_msg .= $this->__("Shop ID")." : ".$shop_id.'<br>';
                    $err_msg .= $this->__("Module").': ZPayment<br>';
                    $err_msg .= $this->__("User ID").' : '.UserUtil::getVar('uid').'<br>';
                    $err_msg .= $this->__("User Name").' : '.UserUtil::getVar('uname').'<br>';
                    $err_msg .= $this->__("User Email").' : '.UserUtil::getVar('email').'<br>';

                    foreach ($emails as $email) {
                        $mailer_args = array(
                            'toaddress' => $email,
                            'fromname' => 'ZSELEX',
                            'subject' => 'Netaxept Error',
                            'body' => $err_msg,
                            'html' => true
                        );

                        $sent = ModUtil::apiFunc('Mailer', 'user',
                                'sendMessage', $mailer_args);
                    }
                    // zen_redirect(zen_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL', true, false));
                } else { // Success
                    // $_SESSION ['cart_shop_id'] = '';
                    SessionUtil::setVar("cart_shop_id", '');
                    $user_id = UserUtil::getVar('uid');

                    unset($_SESSION ['user_cart'] [$cart_shop_id]);
                    // unset($cart_unserialize[$cart_shop_id]);
                    // $this->update_cart($cart_unserialize);
                    if (!$orderInfo ['completed'] || $orderInfo ['completed'] < 1) {
                        $this->update_cart($cart_shop_id, $orderId);
                    }
                    unset($_SESSION ['cart_menu']);
                    unset($_SESSION ['checkoutinfo']);

                    // echo "<pre>"; print_r($query_call); echo "</pre>"; exit;
                    $cardtype = $query_call ['result']->CardInformation->PaymentMethod;
                }
            } //

            $userUpdateObj = array(
                'status' => $order_status,
                'completed' => $orderInfo['completed'] + 1
            );
            DBUtil::updateObject($userUpdateObj, 'zselex_order', $where);

            $userNets        = array(
                'status' => 'Success'
            );
            // DBUtil::updateObject($userNets, 'zpayment_netaxept', $nets_where);
            $update_netaxept = $this->entityManager->getRepository('ZPayment_Entity_Netaxept')->updateNetaxeptPayment($status
                = $netaxept_status, $orderId, $transactionId, $info, $cardtype);

            if ($order_status == 'Success') {
                if (!$orderInfo ['completed'] || $orderInfo ['completed'] < 1) {
                    $this->sendMailToUser($orderId); // send mail.
                }
            }

            // DBUTil::updateObject($obj, 'zselex_shop', $where);
            // return $this->view->fetch('user/thankyou.tpl');
        } elseif ($responseCode == 'Cancel') {

            //echo "Cancel"; exit;
            $userUpdateObj   = array(
                'status' => 'Cancelled'
            );
            DBUtil::updateObject($userUpdateObj, 'zselex_order', $where);
            $userNets        = array(
                'status' => 'Cancelled'
            );
            // DBUtil::updateObject($userNets, 'zpayment_netaxept', $nets_where);
            $update_netaxept = $this->entityManager->getRepository('ZPayment_Entity_Netaxept')->updateNetaxeptPayment($status
                = 'Cancelled', $orderId, $transactionId, $info, $cardtype);
            // return $this->view->fetch('user/ppcancelled.tpl');
            //  echo "Cancel2"; exit;
        }   // Payment failed
        else {
            // echo "Failed...."; exit;

            $userUpdateObj   = array(
                'status' => 'Failed'
            );
            DBUtil::updateObject($userUpdateObj, 'zselex_order', $where);
            $userNets        = array(
                'status' => 'Failed'
            );
            // DBUtil::updateObject($userNets, 'zpayment_netaxept', $nets_where);
            $update_netaxept = $this->entityManager->getRepository('ZPayment_Entity_Netaxept')->updateNetaxeptPayment($status
                = 'Failed', $orderId, $transactionId, $info, $cardtype);
            // return $this->view->fetch('user/pperror.tpl');
            // Delete order information
            // Redirect to failed page
        }
        // }
        return $this->redirect(ModUtil::url('ZSELEX', 'user', 'paymentStatus',
                    array(
                    'order_id' => $orderId
        )));
    }

    /**
     * Show payment status to user
     * 
     * @return html
     */
    public function paymentStatus()
    {
        try {
            /* $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
              '::', ACCESS_COMMENT), LogUtil::getErrorMsgPermission()); */
            $order_id = FormUtil::getPassedValue('order_id', null, 'REQUEST');
            if (empty($order_id)) {
                return $this->redirect();
            }
            //  echo "comes here"; exit;

            $orderInfoArgs = array(
                'fields' => array(
                    'a.id',
                    'a.status',
                    'a.user_id',
                    'b.shop_id',
                    'a.payment_type',
                    'a.vat',
                    'a.shipping',
                    'a.totalprice',
                    'a.self_pickup'
                ),
                'where' => array(
                    'a.order_id' => $order_id
                )
            );
            $order         = $this->entityManager->getRepository('ZSELEX_Entity_Order')->getOrderInfo($orderInfoArgs);
            if (!$order) {
                // throw new Zikula_Exception_Fatal($this->__('Sorry could not find this order!.'));
                return LogUtil::registerError($this->__('Sorry could not find this order!.'));
            }


            $shop_id = $order ['shop_id'];
            setcookie("last_shop_id", $shop_id, time() + (86400 * 7), '/');
            $user_id = $order ['user_id'];

            $orderDetails = ModUtil::apiFunc('ZSELEX', 'user',
                    'getOrderDetails',
                    $args         = array(
                    'order_id' => $order_id
            ));

            /* $grand_total  = $this->entityManager->getRepository('ZSELEX_Entity_OrderItem')->getOrderItemsTotal(array(
              'order_id' => $order_id
              )); */

            $grand_total = $order['totalprice'];
            // echo "<pre>"; print_r($order); echo "</pre>";
            // echo "shopID : " .  ;



            $gateway  = $order ['payment_type'];
            $vat      = $order ['vat'];
            $shipping = $order ['shipping'];
            if ($order ['self_pickup']) {
                $shipping = 0;
            }
            $this->view->assign('gateway', $gateway);
            $this->view->assign('vat', $vat);
            $this->view->assign('shipping', $shipping);

            $this->view->assign('order_id', $order_id);
            $this->view->assign('shop_id', $shop_id);

            $ownername = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                    $args      = array(
                    'shop_id' => $orderDetails [0] ['shop_id']
            ));


            $this->view->assign('ownername', $ownername);

            $this->view->assign('orderDetails', $orderDetails);
            $this->view->assign('grand_total', $grand_total);

            //  $grand_total_all = $grand_total + $shipping;
            $grand_total_all = $grand_total;
            $this->view->assign('grand_total_all', $grand_total_all);
            $cardtype        = '';
            if ($order ['payment_type'] == 'netaxept') {
                $paymentMethod = $this->__('Netaxept');
                $netaxept      = $this->entityManager->getRepository('ZPayment_Entity_Netaxept')->getPaymentDetails(array(
                    'order_id' => $order_id
                ));

                $cardtype = $netaxept ['cardtype'];

                $paymentMode = $this->entityManager->getRepository('ZPayment_Entity_Netaxept')->paymentMode(array(
                    'shop_id' => $shop_id
                ));
            } elseif ($order ['payment_type'] == 'quickpay') {
                $paymentMethod = $this->__('Quickpay');
                $quickpay      = $this->entityManager->getRepository('ZPayment_Entity_QuickPay')->getPaymentDetails(array(
                    'order_id' => $order_id
                ));
                $cardtype      = $quickpay ['cardtype'];
                /*  $paymentMode   = $this->entityManager->getRepository('ZPayment_Entity_QuickPay')->paymentMode(array(
                  'shop_id' => $shop_id
                  )); */
            }
            if ($order ['status'] == 'Success') {
                //  echo "comes here3"; exit;
                // $_SESSION ['cart_shop_id'] = '';
                SessionUtil::setVar("cart_shop_id", '');


                $cart_info = ModUtil::apiFunc('ZSELEX', 'cart', 'carttotal',
                        array());
                $this->view->assign('cartCount', $cart_info['count']);
                $this->view->assign('cardtype', $cardtype);

                $get_products = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                        $getargs      = array(
                        'table' => 'zselex_cart',
                        'where' => "user_id='$user_id'"
                        )
                        // 'fields' => array('id', 'quantity', 'availed')
                );

                $this->view->assign('paymentMode', $paymentMode);
                $content          = $get_products ['cart_content'];
                $cart_unserialize = unserialize($content);
                unset($_SESSION ['user_cart'] [$shop_id]);
                unset($cart_unserialize [$shop_id]);
                // $this->update_cart($cart_unserialize);
                // $this->update_cart($shop_id, $order_id);

                unset($_SESSION ['cart_menu']);
                PageUtil::setVar('title', $this->__("Order Status - Success"));
                return $this->view->fetch('user/thankyou.tpl');
                // return $this->view->fetch('user/order_confirmation.tpl');
            } elseif ($order ['status'] == 'Cancelled') {
                PageUtil::setVar('title', $this->__("Order Status - Cancelled"));

                if ($paymentMode ['test_mode'] == true) {
                    LogUtil::registerError($this->__("$paymentMethod payment is in test mode for this shop"));
                }
                return $this->view->fetch('user/ppcancelled.tpl');
            } elseif ($order ['status'] == 'Failed') {
                PageUtil::setVar('title', $this->__("Order Status - Failed"));
                if ($paymentMode ['test_mode'] == true) {
                    LogUtil::registerError($this->__("$paymentMethod payment is in test mode for this shop"));
                }
                return $this->view->fetch('user/pperror.tpl');
            } elseif ($order ['payment_type'] == 'quickpay' && $order ['status']
                == 'Placed') {
                // echo "comes here....";
                $this->clearUserCart($user_id, $shop_id);
                return $this->view->fetch('user/quickpay_late_response.tpl');
            }
        } catch (\Exception $e) {
            // echo 'Caught exception: ', $e->getMessage(), "\n";
            // die;
            $mailContent = "Error: ".$e->getMessage()."<br><br>Dump: ".json_encode($_REQUEST);
            mail('sharazkhanz@gmail.com', 'Error', $mailContent);
            echo 'Caught exception: ', $e->getMessage(), "\n";
            die();
        }
    }

    /**
     * Clear user cart
     * 
     * @param int $user_id
     * @param int $shop_id
     * @return boolean
     */
    function clearUserCart($user_id, $shop_id)
    {
        if (!$user_id) {
            $user_id = UserUtil::getVar('uid');
        }
        // echo $user_id;
        $get_products     = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                $getargs          = array(
                'table' => 'zselex_cart',
                'where' => "user_id=$user_id"
                )
                // 'fields' => array('id', 'quantity', 'availed')
        );
        // print_r($get_products);
        $content          = $get_products ['cart_content'];
        $cart_unserialize = unserialize($content);
        // print_r($cart_unserialize);
        unset($_SESSION ['user_cart'] [$shop_id]);
        unset($cart_unserialize [$shop_id]);
        // $this->update_cart($cart_unserialize);
        // $this->update_cart($shop_id);
        unset($_SESSION ['cart_menu']);
        return true;
    }

    public function printOrder($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_COMMENT), LogUtil::getErrorMsgPermission());
        $order_id = $_SESSION ['checkoutinfo'] ['order_id'];
        $shop_id  = $_SESSION ['checkoutinfo'] ['shop_id'];

        if (empty($order_id)) {
            // LogUtil::registerError($this->__('Sorry! Please try later!'));
            return $this->redirect(ModUtil::url('ZSELEX', 'user', 'cart'));
        }

        $ownerInfo  = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwnerInfo',
                $args       = array(
                'shop_id' => $shop_id
        ));
        $owner_name = $ownerInfo ['uname'];

        $user_id                = UserUtil::getVar('uid');
        $get_products           = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                $getargs                = array(
                'table' => 'zselex_cart',
                'where' => "user_id=$user_id"
                )
                // 'fields' => array('id', 'quantity', 'availed')
        );
        $content                = $get_products ['cart_content'];
        $cart_unserialize       = unserialize($content);
        unset($_SESSION ['user_cart'] [$shop_id]);
        unset($cart_unserialize [$shop_id]);
        $this->update_cart($cart_unserialize);
        unset($_SESSION ['cart_menu']);
        $_SESSION ['cart_menu'] = array();
        $this->view->assign('order_id', $order_id);

        $pay_type     = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                $getargs      = array(
                'table' => 'zselex_order',
                'where' => "order_id='".$order_id."'",
                'fields' => array(
                    'id',
                    'payment_type'
                )
        ));
        // echo "<pre>"; print_r($pay_type); echo "</pre>";exit;
        $payment_type = $pay_type ['payment_type'];

        if ($payment_type == 'directpay') {
            $checkout_info = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getSingleResult(array(
                'field' => 'checkout_info',
                'where' => "a.shop_id=$shop_id"
            ));
        }

        $joinInfo [] = array(
            'join_table' => 'zselex_products',
            'join_field' => array(
                'product_name',
                'prd_description',
                'prd_price',
                'prd_image'
            ),
            'object_field_name' => array(
                'product_name',
                'prd_description',
                'prd_price',
                'prd_image'
            ),
            'compare_field_table' => 'product_id', // main table
            'compare_field_join' => 'product_id'
        );

        $items = ModUtil::apiFunc('ZSELEX', 'user', 'getAllByJoin',
                $args  = array(
                'table' => 'zselex_orderitems',
                'joinInfo' => $joinInfo,
                'where' => "order_id='".$order_id."'"
        ));

        foreach ($items as $val) {
            $grandPrice [] = $val ['total'];
        }
        $GRANDTOTAL = array_sum($grandPrice);
        // echo "<pre>"; print_r($items); echo "</pre>";exit;
        $this->view->assign('owner_name', $owner_name);
        $this->view->assign('items', $items);
        $this->view->assign('GRANDTOTAL', $GRANDTOTAL);
        $this->view->assign('payment_type', $payment_type);
        $this->view->assign('checkout_info', $checkout_info);

        // echo "<pre>"; print_r($items); echo "</pre>";
        // return $this->view->fetch('user/thankyou.tpl');
        return $this->view->fetch('user/order_confirmation.tpl');
    }

    /**
     * Quickpay continue url function
     * User lands here after payment processed from Quickpay
     * 
     * @return Redirect
     */
    function QuickPayOk()
    {
        $repo     = $this->entityManager->getRepository('ZSELEX_Entity_Order');
        $order_id = $_REQUEST ['order_id'];

        $orderInfoArgs = array(
            'fields' => array(
                'a.id',
                'a.status',
                'a.user_id',
                'b.shop_id',
                'a.completed'
            ),
            'where' => array(
                'a.order_id' => $order_id
            )
        );
        $orderInfo     = $repo->getOrderInfo($orderInfoArgs);

        // if ($orderInfo ['status'] == "Success") {
        if (!$orderInfo ['completed'] || $orderInfo ['completed'] < 1) {
            $this->update_cart($orderInfo ['shop_id'], $order_id, false);
            $this->sendMailToUser($order_id);
            unset($_SESSION ['cart_menu']);
            unset($_SESSION ['checkoutinfo']);
            unset($_SESSION ['user_cart'] [$orderInfo ['shop_id']]);
        }
        //  }

        $updArgs = array(
            'entity' => 'ZSELEX_Entity_Order',
            'fields' => array(
                'completed' => $orderInfo['completed'] + 1
            ),
            'where' => array(
                'a.order_id' => $order_id
            )
        );

        $updateOrder = $repo->updateEntity($updArgs);

        return $this->redirect(ModUtil::url('ZSELEX', 'user', 'paymentStatus',
                    array(
                    'order_id' => $order_id
        )));
    }

    function QuickPayOk_old()
    {
        $order_id = $_REQUEST ['order_id'];


        $orderInfoArgs = array(
            'fields' => array(
                'a.id',
                'a.status',
                'a.user_id',
                'b.shop_id'
            ),
            'where' => array(
                'a.order_id' => $order_id
            )
        );
        $orderInfo     = $this->entityManager->getRepository('ZSELEX_Entity_Order')->getOrderInfo($orderInfoArgs);

        // if ($orderInfo ['status'] == "Success") {
        $this->update_cart($orderInfo ['shop_id'], $order_id);
        $this->sendMailToUser($order_id);
        unset($_SESSION ['cart_menu']);
        unset($_SESSION ['checkoutinfo']);
        unset($_SESSION ['user_cart'] [$orderInfo ['shop_id']]);
        //  }

        return $this->redirect(ModUtil::url('ZSELEX', 'user', 'paymentStatus',
                    array(
                    'order_id' => $order_id
        )));
    }

    /**
     * QuickPay Callback
     * Called aynchronously by Quickpay at background
     * 
     * @return boolean
     */
    function QuickPayCallback()
    {

        mail('sharazkhanz@gmail.com', 'QuickPayCallback', json_encode($_REQUEST));
        $order_id       = $_REQUEST ['order_id'];
        $txnId          = $_REQUEST ['txn_id'];
        $payment_status = "Success";
        $upd_args       = array(
            'entity' => 'ZSELEX_Entity_Order',
            'fields' => array(
                'status' => $payment_status
            ),
            'where' => array(
                'a.order_id' => $order_id
            )
        );


        $updateOrderId = $this->entityManager->getRepository('ZSELEX_Entity_Order')->updateEntity($upd_args);

        $update_quickpay = $this->entityManager->getRepository('ZPayment_Entity_QuickPay')->updateQuickPayPayment($status
            = $payment_status, $order_id, $txnId, $info            = '',
            $cardtype        = '');

        return true;
    }

    function QuickPayCallback_old()
    {
        // $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_COMMENT), LogUtil::getErrorMsgPermission());
        try {
            $order_id       = $_REQUEST ['ordernumber'];
            $state          = $_REQUEST ['state'];
            $qpstatmsg      = $_REQUEST ['qpstatmsg'];
            $transaction_id = $_REQUEST ['transaction'];
            $cardtype       = $_REQUEST ['cardtype'];
            // $shop_id        = $_REQUEST ['CUSTOM_shop_id'];
            //$user_id        = $_REQUEST ['CUSTOM_user_id'];
            $variables      = $_REQUEST ['variables'];
            $shop_id        = $variables ['CUSTOM_shop_id'];
            $user_id        = $variables ['CUSTOM_user_id'];

            // $txnCount = $this->entityManager->getRepository('ZPayment_Entity_QuickPay')->quickpayTxnCount(array('txn_id' => $transaction_id));
            $msg = "order_id : ".$order_id." state :".$state." txn_id : ".$transaction_id." user_id :".$user_id." shop_id :".$shop_id;
            // echo $msg; exit;
            // mail('sharaz.khan@.com', 'testing', $message = $msg);

            mail('sharazkhanz@gmail.com', 'QuickPayCallback',
                json_encode($_REQUEST));
            mail('sharazkhanz@gmail.com', 'QuickPayCallback2', $msg);

            // if (!$txnCount) {
            if ($state || $state == 1 || $state == '1') {
                $payment_status = "Success";


                $upd_args      = array(
                    'entity' => 'ZSELEX_Entity_Order',
                    'fields' => array(
                        'status' => $payment_status
                    ),
                    'where' => array(
                        'a.order_id' => $order_id
                    )
                    )
                // 'where' => "a.cart_id=:cart_id"
                ;
                $updateOrderId = $this->entityManager->getRepository('ZSELEX_Entity_Order')->updateEntity($upd_args);

                $update_quickpay = $this->entityManager->getRepository('ZPayment_Entity_QuickPay')->updateQuickPayPayment($status
                    = $payment_status, $order_id, $transaction_id,
                    $info            = $qpstatmsg, $cardtype);
                // echo "helloooo";
                // $this->update_cart($shop_id, $order_id);
                // $this->sendMailToUser($order_id); //send mail.
                // mail('sharaz.khan@.com', 'testing', $message = $msg);
                // return $this->view->fetch('user/thankyou.tpl');
            } else {
                $payment_status = "Failed";


                $upd_args      = array(
                    'entity' => 'ZSELEX_Entity_Order',
                    'fields' => array(
                        'status' => $payment_status
                    ),
                    'where' => array(
                        'a.order_id' => $order_id
                    )
                    )
                // 'where' => "a.cart_id=:cart_id"
                ;
                $updateOrderId = $this->entityManager->getRepository('ZSELEX_Entity_Order')->updateEntity($upd_args);

                $update_quickpay = $this->entityManager->getRepository('ZPayment_Entity_QuickPay')->updateQuickPayPayment($status
                    = $payment_status, $order_id, $transaction_id,
                    $info            = $qpstatmsg, $cardtype);
                // return $this->view->fetch('user/pperror.tpl');
            }
            // }
            // mail('sharaz.khan@.com', 'testing', $message = 'works1');
            return true;
        } catch (Exception $e) {

            // $dt = date('Y-m-d H:i:s', time());
            // error_log("[$dt] QuickPay Error : " . $e->getMessage() . "\n", 3, "modules/ZSELEX/errorlog/error.log");
        }
        // return $this->redirect(ModUtil::url('ZSELEX', 'user', 'paymentStatus', array('order_id' => $order_id)));
        return true;
    }

    /**
     * Quickpay Cancel
     * 
     * @return redirect
     */
    function QuickPayCancel()
    {
        $order_id       = $_REQUEST ['order_id'];
        $payment_status = "Cancelled";

        /*
         * $updateOrder = array(
         * 'table' => 'zselex_order',
         * 'IdValue' => $order_id,
         * 'fields' => array('status' => $payment_status),
         * 'idName' => 'id',
         * 'where' => "order_id='" . $order_id . "'",
         * );
         *
         * $updateOrderId = ModUtil::apiFunc('ZSELEX', 'user', 'updateObject', $updateOrder);
         */

        $upd_args      = array(
            'entity' => 'ZSELEX_Entity_Order',
            'fields' => array(
                'status' => $payment_status
            ),
            'where' => array(
                'a.order_id' => $order_id
            )
            )
        // 'where' => "a.cart_id=:cart_id"
        ;
        $updateOrderId = $this->entityManager->getRepository('ZSELEX_Entity_Order')->updateEntity($upd_args);

        $update_quickpay = $this->entityManager->getRepository('ZPayment_Entity_QuickPay')->updateQuickPayPayment($status
            = $payment_status, $order_id, '', $info            = 'Payment Cancelled',
            $cardtype        = '');

        return $this->redirect(ModUtil::url('ZSELEX', 'user', 'paymentStatus',
                    array(
                    'order_id' => $order_id
        )));
    }

    /**
     * Returns here after payment success
     * 
     * @return type
     */
    function EpayAccept()
    {
        // echo "EpayAccept<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
        $orderId       = FormUtil::getPassedValue('orderid', null, 'REQUEST');
        $transactionId = FormUtil::getPassedValue('txnid', null, 'REQUEST');
        $card_number   = FormUtil::getPassedValue('cardno', null, 'REQUEST');

        $payment_status = 'Success';

        $txnCount      = $this->entityManager->getRepository('ZPayment_Entity_Epay')->ePayTxnCount(array(
            'txn_id' => $transactionId
        ));
        $orderInfoArgs = array(
            // 'fields' => array('a.id', 'a.status', 'a.user_id', 's.shop_id'),
            'fields' => array(
                'a.order_id',
                'b.shop_id'
            ),
            'joins' => array(
                'JOIN a.shop b'
            ),
            'where' => array(
                'a.order_id' => $orderId
            )
        );
        $orderInfo     = $this->entityManager->getRepository('ZSELEX_Entity_Order')->getOrderInfo($orderInfoArgs);
        $shop_id       = $orderInfo ['shop_id'];
        $upd_args      = array(
            'entity' => 'ZSELEX_Entity_Order',
            'fields' => array(
                'status' => $payment_status
            ),
            'where' => array(
                'a.order_id' => $orderId
            )
            )
        // 'where' => "a.cart_id=:cart_id"
        ;

        $updateOrderId = $this->entityManager->getRepository('ZSELEX_Entity_Order')->updateEntity($upd_args);

        $update_epay = $this->entityManager->getRepository('ZPayment_Entity_Epay')->updateEpayPayment($payment_status,
            $orderId, $transactionId, $card_number);

        // if ($orderInfo['status'] == "Success") {
        if (!$txnCount) {
            $this->update_cart($orderInfo ['shop_id'], $orderId);
            $this->sendMailToUser($orderId);
            unset($_SESSION ['cart_menu']);
            unset($_SESSION ['checkoutinfo']);
            unset($_SESSION ['user_cart'] [$shop_id]);
        }
        // }

        return $this->redirect(ModUtil::url('ZSELEX', 'user', 'paymentStatus',
                    array(
                    'order_id' => $orderId
        )));
    }

    function EpayCallBack()
    {
        //echo "EpayCallBack<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
        $orderId       = FormUtil::getPassedValue('orderid', null, 'REQUEST');
        $transactionId = FormUtil::getPassedValue('txnid', null, 'REQUEST');
        $card_number   = FormUtil::getPassedValue('cardno', null, 'REQUEST');

        $payment_status = 'Success';

        $txnCount = $this->entityManager->getRepository('ZPayment_Entity_Epay')->ePayTxnCount(array(
            'txn_id' => $transactionId
        ));

        $orderInfoArgs = array(
            // 'fields' => array('a.id', 'a.status', 'a.user_id', 's.shop_id'),
            'fields' => array(
                'a.order_id',
                'b.shop_id'
            ),
            'joins' => array(
                'JOIN a.shop b'
            ),
            'where' => array(
                'a.order_id' => $orderId
            )
        );
        $orderInfo     = $this->entityManager->getRepository('ZSELEX_Entity_Order')->getOrderInfo($orderInfoArgs);
        $shop_id       = $orderInfo ['shop_id'];
        $upd_args      = array(
            'entity' => 'ZSELEX_Entity_Order',
            'fields' => array(
                'status' => $payment_status
            ),
            'where' => array(
                'a.order_id' => $orderId
            )
            )
        // 'where' => "a.cart_id=:cart_id"
        ;

        $updateOrderId = $this->entityManager->getRepository('ZSELEX_Entity_Order')->updateEntity($upd_args);

        $update_epay = $this->entityManager->getRepository('ZPayment_Entity_Epay')->updateEpayPayment($payment_status,
            $orderId, $transactionId, $card_number);

        // if ($orderInfo['status'] == "Success") {
        if (!$txnCount) {
            $this->update_cart($orderInfo ['shop_id'], $orderId);
            $this->sendMailToUser($orderId);
            unset($_SESSION ['cart_menu']);
            unset($_SESSION ['checkoutinfo']);
            unset($_SESSION ['user_cart'] [$shop_id]);
        }
        // }
        return true;
        // return $this->redirect(ModUtil::url('ZSELEX', 'user', 'paymentStatus', array('order_id' => $orderId)));
    }

    /**
     * Epay Cancel
     * 
     * @return Redirect
     */
    function EpayCancel()
    {
        // echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
        $orderId = FormUtil::getPassedValue('orderid', null, 'REQUEST');

        $payment_status = 'Cancelled';

        $orderInfoArgs = array(
            // 'fields' => array('a.id', 'a.status', 'a.user_id', 's.shop_id'),
            'fields' => array(
                'a.order_id',
                'b.shop_id'
            ),
            'joins' => array(
                'JOIN a.shop b'
            ),
            'where' => array(
                'a.order_id' => $orderId
            )
        );
        $orderInfo     = $this->entityManager->getRepository('ZSELEX_Entity_Order')->getOrderInfo($orderInfoArgs);
        $shop_id       = $orderInfo ['shop_id'];
        $upd_args      = array(
            'entity' => 'ZSELEX_Entity_Order',
            'fields' => array(
                'status' => $payment_status
            ),
            'where' => array(
                'a.order_id' => $orderId
            )
            )
        // 'where' => "a.cart_id=:cart_id"
        ;

        $updateOrderId = $this->entityManager->getRepository('ZSELEX_Entity_Order')->updateEntity($upd_args);

        $update_quickpay = $this->entityManager->getRepository('ZPayment_Entity_Epay')->updateEpayPayment($status
            = $payment_status, $orderId, $transaction_id  = '',
            $card_number     = '');

        return $this->redirect(ModUtil::url('ZSELEX', 'user', 'paymentStatus',
                    array(
                    'order_id' => $orderId
        )));
    }

    /**
     * Api for getting shops categories
     */
    public function shopCategoryApi()
    {
        $shop_title  = FormUtil::getPassedValue('urltitle', '', 'REQUEST');
        // echo $shop_title; exit;
        $json_encode = '';
        if (!empty($shop_title)) {
            $getCategories = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getAll(array(
                'entity' => 'ZSELEX_Entity_Shop',
                'joins' => array(
                    'JOIN a.shop_prod_categories b'
                ),
                'fields' => array(
                    'b.prd_cat_id',
                    'b.prd_cat_name'
                ),
                'where' => array(
                    'a.urltitle' => $shop_title
                )
            ));
            // echo "<pre>"; print_r($getCategories); echo "</pre>"; exit;
            $json_encode   = json_encode($getCategories);
        }
        echo $json_encode;
        exit();
    }

    /**
     * Api for getting shops manufacturers
     */
    public function shopManufacturerApi()
    {
        $shop_title  = FormUtil::getPassedValue('urltitle', '', 'REQUEST');
        // echo $shop_title; exit;
        $json_encode = '';
        if (!empty($shop_title)) {
            $getManufacturers = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getAll(array(
                'entity' => 'ZSELEX_Entity_Shop',
                'joins' => array(
                    'JOIN a.shop_manufacturers b'
                ),
                'fields' => array(
                    'b.manufacturer_id',
                    'b.manufacturer_name'
                ),
                'where' => array(
                    'a.urltitle' => $shop_title
                )
            ));
            // echo "<pre>"; print_r($getManufacturers); echo "</pre>"; exit;
            $json_encode      = json_encode($getManufacturers);
        }
        echo $json_encode;
        exit();
    }

    /**
     * Api for getting shops manufacturers
     *
     * @param GETPOST
     * @return json response
     */
    public function getShopIdApi()
    {
        $shop_title  = FormUtil::getPassedValue('urltitle', '', 'REQUEST');
        // echo $shop_title; exit;
        $json_encode = '';
        if (!empty($shop_title)) {
            $getShopId   = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->get(array(
                'entity' => 'ZSELEX_Entity_Shop',
                'fields' => array(
                    'a.shop_id'
                ),
                'where' => array(
                    'a.urltitle' => $shop_title
                )
            ));
            // echo "<pre>"; print_r($getManufacturers); echo "</pre>"; exit;
            $json_encode = json_encode($getShopId);
        }
        echo $json_encode;
        exit();
    }

    /**
     * Api for getting shops manufacturers
     * 
     * @param GETPOST
     * @return json response
     */
    public function getShopProductsApi()
    {
        // echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
        $shop_id = FormUtil::getPassedValue('shop_id', '', 'REQUEST');
        $limit   = FormUtil::getPassedValue('limit', '', 'REQUEST');
        $filter  = FormUtil::getPassedValue('filter', '', 'REQUEST');
        // echo $shop_title; exit;
        // echo "<pre>"; print_r($filter['cat']); echo "</pre>"; exit;
        if (!empty($filter ['cat'])) {
            $catIds  = implode(',', $filter ['cat']);
            $cat_qry = " AND p.product_id IN(SELECT product_id FROM zselex_product_to_category WHERE prd_cat_id IN($catIds)) ";
        }
        // echo $catIds; exit;
        if (!empty($filter ['mnfr'])) {
            $mnfrIds  = implode(',', $filter ['mnfr']);
            $mnfr_qry = " AND p.manufacturer_id IN($mnfrIds) ";
        }

        $json_encode = '';
        if (!empty($shop_id)) {
            /*
             * $getProducts = $this->entityManager->getRepository('ZSELEX_Entity_Product')
             * ->getAll(array(
             * 'entity' => 'ZSELEX_Entity_Product',
             * //'fields' => array('a.shop_id'),
             * 'joins' => array('JOIN a.shop b'),
             * 'offset' => $limit,
             * 'where' => array('b.urltitle' => $shop_title)
             * ));
             */
            $getProducts = $this->entityManager->getRepository('ZSELEX_Entity_Product')->getMinishopProductsApi(array(
                'shop_id' => $shop_id,
                'cat_qry' => $cat_qry,
                'mnfr_qry' => $mnfr_qry,
                'startnum' => 0,
                'itemsperpage' => $limit
            ));
            // echo "<pre>"; print_r($getManufacturers); echo "</pre>"; exit;
            $json_encode = json_encode($getProducts);
        }
        echo $json_encode;
        exit();
    }

    function test7()
    {
        $msg         = '';
        $repo        = $this->entityManager->getRepository('ZSELEX_Entity_Product');
        $serialize   = 'a:1:{i:0;a:2:{s:13:"prdToOptionID";s:2:"43";s:7:"valueID";s:3:"177";}}';
        $unserialize = unserialize($serialize);
        //echo "<pre>"; print_r($unserialize); echo "</pre>"; exit;
        foreach ($unserialize as $val) {
            $getName = $repo->get(array(
                'entity' => 'ZSELEX_Entity_ProductToOption',
                'fields' => array(
                    'b.option_name',
                    'b.option_type'
                ),
                'where' => array(
                    "a.product_to_options_id" => $val['prdToOptionID']
                ),
                'joins' => array(
                    'LEFT JOIN a.option b'
                )
            ));

            $getValue = $repo->get(array(
                'entity' => 'ZSELEX_Entity_ProductToOptionValue',
                'fields' => array(
                    'b.option_value',
                    'a.price',
                    'a.parent_option_value_id',
                    'a.price_prefix',
                    'a.qty'
                ),
                'where' => array(
                    "a.product_to_options_value_id" => $val ['valueID']
                ),
                'joins' => array(
                    'LEFT JOIN a.option_value_id b'
                )
                )
                // 'groupby' => 'b.option_value_id'
            );
            // echo "<pre>"; print_r($getValue); echo "</pre>";
            $msg .= $getName['option_name'].' : '.$getValue['option_value'].'<br>';
            $msg .= 'Quantity Left'.' : '.$getValue['qty'];
        }

        echo $msg;

        exit;
    }
}
?>
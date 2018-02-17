<?php

/**
 * Copyright  2013
 *
 * ZSELEX
 * Demonstration of Zikula Module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control Block display and interface
 */
class ZBlocks_Block_Products extends Zikula_Controller_AbstractBlock {

    /**
     * initialise block
     */
    public function init() {
        SecurityUtil::registerPermissionSchema('ZBlocks:zencartproductsblock:', 'Block title::');
    }

    /**
     * get information on block
     */
    public function info() {
        return array(
            'text_type' => 'ZBlocks',
            'module' => 'ZBlocks',
            'text_type_long' => $this->__('ZBlocks Products'),
            'allow_multiple' => true,
            'form_content' => false,
            'form_refresh' => false,
            'show_preview' => true,
            'admin_tableless' => true);
    }

    /**
     * display block
     */
    public function display($blockinfo) {//
        //return false;
        if (!SecurityUtil::checkPermission('ZBlocks:zencartproductsblock:', "$blockinfo[title]::", ACCESS_OVERVIEW)) {
            return;
        }
        $this->view->setCaching(false);
        if (!ModUtil::available('ZBlocks')) {
            return;
        }
        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        // echo "<pre>";  print_r($vars);  echo "</pre>"; 
        // $products = '';
        //echo count($products);

        $shop_title = $this->getSiteTitle($vars['site_url']);
        $apiDomain = $this->getApiDomain($vars['site_url']);

        $shop_id_api = $apiDomain . '/zselex/getShopIdApi/urltitle/' . $shop_title;
        $getShop = $this->CallAPI('GET', $url = $shop_id_api);
        //echo $getShop; exit;
        $getShopObj = json_decode($getShop, false);
        $shop_id = $getShopObj->shop_id;
        $categories = $vars[$shop_id]['categories'];
        $manufacturers = $vars[$shop_id]['manufacturers'];
        $append = '?';
        if (!empty($categories)) {
            foreach ($categories as $cat) {
                $append .= "filter[cat][]=$cat&";
            }
        }
        if (!empty($manufacturers)) {
            foreach ($manufacturers as $mnfr) {
                $append .= "filter[mnfr][]=$mnfr&";
            }
        }
       // echo $append;

        // echo "<pre>";  print_r($categories);  echo "</pre>";
        $products_api = $apiDomain . '/zselex/getShopProductsApi/shop_id/' . $shop_id . '/limit/' . $vars['product_limit'] . $append;
        $getProducts = $this->CallAPI('GET', $url = $products_api);
        //echo $getProducts;
        $products = json_decode($getProducts, true);
        //echo "<pre>";  print_r($products);  echo "</pre>"; 
        $count = count($products);



        $this->view->assign('vars', $vars);
        $this->view->assign('domain', $apiDomain);
        $this->view->assign('shop_title', $shop_title);
        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('count', $count);
        $this->view->assign('products', $products);


        $blockinfo['content'] = $this->view->fetch('blocks/zproducts/zproducts.tpl');

        return BlockUtil::themeBlock($blockinfo);
    }

    /**
     * modify block settings ..
     */
    public function modify($blockinfo) {
        $this->view->setCaching(false);
        $vars = BlockUtil::varsFromContent($blockinfo['content']);


        //echo "<pre>";   print_r($vars);  echo "</pre>";

        $shop_title = $this->getSiteTitle($vars['site_url']);
        //echo $shop_title;
        //$category_api =  $_SERVER['SERVER_NAME'] . '/';
        //echo $category_api;
        //$category_api = 'http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . '/zselex/shopCategoryApi/urltitle/' . $shop_title;
        // $manufacturer_api = 'http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . '/zselex/shopManufacturerApi/urltitle/' . $shop_title;
        //echo $category_api;
        $apiDomain = $this->getApiDomain($vars['site_url']);
        $category_api = $apiDomain . '/zselex/shopCategoryApi/urltitle/' . $shop_title;
        $manufacturer_api = $apiDomain . '/zselex/shopManufacturerApi/urltitle/' . $shop_title;
        $shopCategories = $this->CallAPI('GET', $url = $category_api);
        $shopManufacturers = $this->CallAPI('GET', $url = $manufacturer_api);
        //echo "<pre>";   print_r($shopManufacturers);  echo "</pre>";

        $shop_id_api = $apiDomain . '/zselex/getShopIdApi/urltitle/' . $shop_title;
        $getShop = $this->CallAPI('GET', $url = $shop_id_api);
        //echo $getShop; exit;
        $getShopObj = json_decode($getShop, false);
        $shop_id = $getShopObj->shop_id;
        //echo "<pre>";   print_r($vars[$shop_id]['categories']);  echo "</pre>";
        $this->view->assign('vars', $vars);
        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('shopCategories', json_decode($shopCategories, true));
        $this->view->assign('shopManufacturers', json_decode($shopManufacturers, true));


        return $this->view->fetch('blocks/zproducts/zproducts_modify.tpl');
    }

    /**
     * update block settings
     */
    public function update($blockinfo) {
        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        // alter the corresponding variable
        $vars['shop_name'] = FormUtil::getPassedValue('shop_name', '', 'POST');
        $vars['site_url'] = FormUtil::getPassedValue('site_url', '', 'POST');
        $vars['product_limit'] = FormUtil::getPassedValue('product_limit', '', 'POST');
        $vars['categories'] = FormUtil::getPassedValue('categories', '', 'POST');
        $vars['manufacturers'] = FormUtil::getPassedValue('manufacturers', '', 'POST');
        //echo "<pre>";  print_r($vars['categories']);  echo "</pre>"; exit;
        $shop_title = $this->getSiteTitle($vars['site_url']);
        $apiDomain = $this->getApiDomain($vars['site_url']);
        $shop_id_api = $apiDomain . '/zselex/getShopIdApi/urltitle/' . $shop_title;
        $getShop = $this->CallAPI('GET', $url = $shop_id_api);
        //echo $getShop; exit;
        $getShopObj = json_decode($getShop, false);
        $shop_id = $getShopObj->shop_id;
        //echo $shop_id; exit;
        $vars[$shop_id]['categories'] = $vars['categories'];
        $vars[$shop_id]['manufacturers'] = $vars['manufacturers'];
        // echo "<pre>";  print_r($vars[$shop_id]);  echo "</pre>"; exit;
        // write back the new contents
        $blockinfo['content'] = BlockUtil::varsToContent($vars);

        // clear the block cache
        $this->view->clear_cache('blocks/zencartproducts.tpl');

        return $blockinfo;
    }

    private function getSiteTitle($url) {

        $site_url = $url;
//$url = "localhost/zselex/site/newishop";
        if (!empty($site_url)) {
            $explode = explode('/', $url);

//$count = count($explode);

            $result = array_filter($explode);
            $count = count($result);

//echo "<pre>"; print_r($result);  echo "</pre>";
            return $result[$count];
        }
        return '';
    }

    function CallAPI($method, $url, $data = false) {
        $curl = curl_init();

        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // Optional Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

    private function getApiDomain($url) {
        $pieces = parse_url($url);
        // echo "<pre>"; print_r($pieces);  echo "</pre>";
        $domain = '';
        if (!empty($pieces['scheme'])) {
            $domain = $pieces['scheme'] . '://' . $pieces['host'];
        } else {
            $explode = @explode('/', $pieces['path']);
            $domain = 'http://' . $explode[0];
        }
        return $domain;
    }

}

// end class def
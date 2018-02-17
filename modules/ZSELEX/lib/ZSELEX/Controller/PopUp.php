<?php

class ZSELEX_Controller_PopUp extends ZSELEX_Controller_Base_Ajax
{
    public $ownername;
    public $owner_id;
    public $view;

    function initialize()
    {
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        if (!empty($shop_id)) {
            $ownerInfo       = ModUtil::apiFunc('ZSELEX', 'admin',
                    'getOwnerInfo',
                    $args            = array(
                    'shop_id' => $shop_id
            ));
            $this->ownername = $ownerInfo ['uname'];
            $this->owner_id  = $ownerInfo ['uid'];
            // echo $getShop['shop_name'];
        }
        $this->view = Zikula_View::getInstance($this->name);
        // echo $this->ownername; exit;
    }

    /**
     * product pop up
     * 
     * @param type $args
     * @return html
     */
    public function showProductPopUp($args)
    {
        $data       = '';
        $product_id = $_REQUEST ['product_id'];
        $shop_id    = $_REQUEST ['shop_id'];

        if (empty($product_id) || !is_numeric($product_id)) {
            $output ["noproduct"] = 1;
            AjaxUtil::output($output);
        }

        $prdCount = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount',
                array(
                'table' => 'zselex_products',
                'where' => "product_id='".$product_id."'"
        ));

        if (!$prdCount) {
            $output ["noproduct"] = 1;
            AjaxUtil::output($output);
        }
        $view            = Zikula_View::getInstance($this->name);
        $error_msg       = '';
        $payment_enabled = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->paymentsEnabled($shop_id);
        if (!$payment_enabled) {
            $error_msg = $view->__("You must enable at least one payment gateway. Please go to ")." <a href='".ModUtil::url('ZSELEX',
                    'admin', 'shopsettings',
                    array(
                    'shop_id' => $shop_id
                ))."'>".$view->__("shop settings")."</a>";
        }

        $productRepo = $this->entityManager->getRepository('ZSELEX_Entity_Product');

        $product = $productRepo->get(array(
            'entity' => 'ZSELEX_Entity_Product',
            'where' => array(
                'a.product_id' => $product_id
            ),
            'joins' => array(
                'LEFT JOIN a.manufacturer b'
            ),
            'fields' => array(
                'a.product_id',
                'a.product_name',
                'a.urltitle',
                'a.prd_description',
                'b.manufacturer_id',
                'a.keywords',
                'a.original_price',
                'a.prd_price',
                'a.discount',
                'a.shipping_price',
                'a.prd_quantity',
                'a.prd_image',
                'a.prd_status',
                'a.validate_question',
                'a.prd_question',
                'a.enable_question',
                'a.no_vat',
                'a.advertise',
                'a.max_discount',
                'a.no_delivery'
            )
        ));


        $product ['categories'] = $productRepo->getAll(array(
            'entity' => 'ZSELEX_Entity_Product',
            'where' => array(
                'a.product_id' => $product_id
            ),
            'joins' => array(
                'JOIN a.product_to_category b'
            ),
            'fields' => array(
                'b.prd_cat_id category_id'
            )
            )
            // 'orderby' => 'a.prd_cat_name ASC'
        );

        // echo "<pre>"; print_r($product['categories']); echo "</pre>"; exit;

        $product ['options'] = ModUtil::apiFunc('ZSELEX', 'product',
                'getConfiguredProductOptions',
                array(
                'product_id' => $product_id
        ));

        $product ['original_price_new'] = floatval($product ['original_price']);

        $product ['prd_price_new'] = floatval($product ['prd_price']);
        $view->assign('product', $product);

        $category = $productRepo->getAll(array(
            'entity' => 'ZSELEX_Entity_ProductCategory',
            'where' => array(
                'a.shop' => $shop_id
            ),
            'fields' => array(
                'a.prd_cat_id',
                'a.prd_cat_name'
            ),
            'orderby' => 'a.prd_cat_name ASC'
        ));
        $view->assign('category', $category);
        $view->assign('error_msg', $error_msg);

        $manufacturers = $this->entityManager->getRepository('ZSELEX_Entity_Manufacturer')->getManufacturers(array(
            'shop_id' => $shop_id
        ));
        $view->assign('manufacturers', $manufacturers);

        // echo "<pre>"; print_r($manufacturers); echo "</pre><br>";

        $option_args = array(
            'shop_id' => $shop_id,
            'all' => 1
        );
        // $product_options = $this->entityManager->getRepository('ZSELEX_Entity_ProductOption')->getProductOptionList($option_args);

        $product_options = $this->entityManager->getRepository('ZSELEX_Entity_ProductOption')->getProductOptions(array(
            'shop_id' => $shop_id
        ));
        $parent_options  = $this->entityManager->getRepository('ZSELEX_Entity_ProductOption')->getParentOptions(array(
            'shop_id' => $shop_id
        ));

        $optionExistForProduct = $this->entityManager->getRepository('ZSELEX_Entity_ProductOption')->checkOptionExistForProduct(array(
            'product_id' => $product_id
        ));
        // echo "<pre>"; print_r($parent_options); echo "</pre>";
        if (!empty($parent_options)) {
            $product_options = array_merge($product_options, $parent_options);
        }

        $qty_discounts       = ModUtil::apiFunc('ZSELEX', 'product',
                'getQtyDiscounts',
                array(
                'product_id' => $product_id
        ));
        $qty_discounts_count = count($qty_discounts);
        // echo "<pre>"; print_r($qty_discounts); echo "</pre>";
        $view->assign('product_options', $product_options);
        $view->assign('optionExistForProduct', $optionExistForProduct);
        $view->assign('qty_discounts', $qty_discounts);
        $view->assign('qty_discounts_count', $qty_discounts_count);
        $output_test         = $view->fetch('ajax/productpopup.tpl');
        $data .= new Zikula_Response_Ajax_Plain($output_test);
        $output ["product"]  = $data;
        AjaxUtil::output($output);
    }

    public function productCategory($args)
    {
        $data            = '';
        $shop_id         = $_REQUEST ['shop_id'];
        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('owner_id', $this->owner_id);
        $output_file     = $this->view->fetch('ajax/product_category.tpl');
        $data .= new Zikula_Response_Ajax_Plain($output_file);
        $output ["data"] = $data;
        AjaxUtil::output($output);
    }

    public function saveCategory()
    {
        // $this->checkCsrfToken();
        $repo       = $this->entityManager->getRepository('ZSELEX_Entity_ProductOption');
        $shop_id    = $_REQUEST ['shopId'];
        $product_id = $_REQUEST ['product_id'];
        $owner_id   = $_REQUEST ['ownerId'];
        $catName    = $_REQUEST ['catName'];
        $status     = $_REQUEST ['status'];

        /*
         * $cat_count = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $args = array(
         * 'table' => 'zselex_product_categories',
         * "where" => "shop_id=$shop_id AND prd_cat_name='" . $catName . "'"
         * ));
         */

        // echo $cat_count; exit;
        $cat_count = $repo->getCount(null, 'ZSELEX_Entity_ProductCategory',
            'prd_cat_id',
            array(
            'a.shop' => $shop_id,
            'a.prd_cat_name' => $catName
        ));

        if ($cat_count > 0) {
            $output ["exist"] = 1;
            AjaxUtil::output($output);
        }

        /*
         * $item = array(
         * 'prd_cat_name' => $catName,
         * 'user_id' => $owner_id,
         * 'shop_id' => $shop_id,
         * 'status' => $status
         * );
         *
         * $args = array(
         * 'table' => 'zselex_product_categories',
         * 'element' => $item,
         * 'Id' => 'prd_cat_id'
         * );
         * // Create the zselex type
         * $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $args);
         */

        $prodCat = new ZSELEX_Entity_ProductCategory ();
        $prodCat->setPrd_cat_name($catName);
        $prodCat->setUser_id($owner_id);
        $shopObj = $this->entityManager->find('ZSELEX_Entity_Shop', $shop_id);
        $prodCat->setShop($shopObj);
        $prodCat->setStatus($status);
        $this->entityManager->persist($prodCat);
        $this->entityManager->flush();
        $result  = $prodCat->getPrd_cat_id();

        // $new_cat_id = $result['prd_cat_id'];
        $new_cat_id = $result;
        /*
         * $itmeargs = array(
         * 'table' => 'zselex_product_categories',
         * 'where' => "shop_id=$shop_id",
         * 'orderBy' => 'prd_cat_name ASC',
         * 'useJoins' => ''
         * );
         * $categories = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements', $itmeargs);
         */

        $categories = $repo->getAll(array(
            'entity' => 'ZSELEX_Entity_ProductCategory',
            'where' => array(
                'a.shop' => $shop_id
            ),
            // 'fields' => array('b.prd_cat_id category_id'),
            'orderby' => 'a.prd_cat_name ASC'
        ));
        /*
         * $itmeargs1 = array(
         * 'table' => 'zselex_product_to_category',
         * 'where' => "product_id=$product_id",
         * //'orderBy' => 'service_name ASC',
         * 'useJoins' => ''
         * );
         * $product_categories = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements', $itmeargs1);
         */

        $product_categories = $repo->getAll(array(
            'entity' => 'ZSELEX_Entity_Product',
            'where' => array(
                'a.product_id' => $product_id
            ),
            'joins' => array(
                'JOIN a.product_to_category b'
            )
            )
            // 'fields' => array('b.prd_cat_id category_id'),
            // 'orderby' => 'a.prd_cat_name ASC'
        );
        // echo "<pre>"; print_r($product_categories); echo "</pre>"; exit;
        $catlist            = '';
        $catlist .= "<label for='category'>".$this->__('Categories')."</label><select class='mcategory' id=category name=formElements[prod_cats][] multiple='multiple'>";
        // $catlist .= "<option value=''>" . $this->__('select category') . "</option>";
        foreach ($categories as $row) {
            /*
             * if ($row['prd_cat_id'] == $new_cat_id) {
             * $selected = "selected";
             * } else {
             * $selected = '';
             * }
             */

            $selected = '';

            if (ZSELEX_Util::in_assoc_array($row ['prd_cat_id'], 'category_id',
                    $product_categories) || $row ['prd_cat_id'] == $new_cat_id) {
                // echo "Product Exist";
                $selected = "selected";
            }
            $catlist .= "<option value='".$row ['prd_cat_id']."'  $selected>".$row [prd_cat_name]."</option>";
        }
        $catlist .= "</select>";
        $output ["cat"] = $catlist;
        AjaxUtil::output($output);
    }

    public function manufacturerForm($args)
    {
        $data            = '';
        $shop_id         = $_REQUEST ['shop_id'];
        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('owner_id', $this->owner_id);
        $output_file     = $this->view->fetch('ajax/manufacturer_form.tpl');
        $data .= new Zikula_Response_Ajax_Plain($output_file);
        $output ["data"] = $data;
        AjaxUtil::output($output);
    }

    public function saveManufacturer()
    {
        // $this->checkCsrfToken();
        // throw new Zikula_Exception_Forbidden(__('Security token validation failed'));
        $shop_id  = $_REQUEST ['shopId'];
        $owner_id = $_REQUEST ['ownerId'];
        $name     = $_REQUEST ['name'];
        $status   = $_REQUEST ['status'];

        /*
         * $cat_count = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $args = array(
         * 'table' => 'zselex_product_categories',
         * "where" => "shop_id=$shop_id AND prd_cat_name='" . $catName . "'"
         * ));
         */

        $manufacturer_exist = $this->entityManager->getRepository('ZSELEX_Entity_Manufacturer')->manufacturer_count(array(
            'shop_id' => $shop_id,
            'name' => $name
        ));

        if ($manufacturer_exist > 0) {
            $output ["exist"] = 1;
            AjaxUtil::output($output);
        }

        $manufacturer_entity = new ZSELEX_Entity_Manufacturer ();
        $manufacturer_entity->setManufacturer_name($name);
        $shopObj             = $this->entityManager->find('ZSELEX_Entity_Shop',
            $shop_id);
        $manufacturer_entity->setShop($shopObj);
        $manufacturer_entity->setStatus($status);

        $this->entityManager->persist($manufacturer_entity);
        $this->entityManager->flush();
        $InsertId = $manufacturer_entity->getManufacturer_id();

        $new_id        = $InsertId;
        $manufacturers = $this->entityManager->getRepository('ZSELEX_Entity_Manufacturer')->getManufacturers(array(
            'shop_id' => $shop_id
        ));
        $list          = '';
        $list .= "<label for='manufacturer'>".$this->__('Manufacturer')."</label><select id=manufacturer name=formElements[manufacturer]>";
        $list .= "<option value=''>".$this->__('select manufacturer')."</option>";
        foreach ($manufacturers as $row) {
            if ($row ['manufacturer_id'] == $new_id) {
                $selected = "selected";
            } else {
                $selected = '';
            }
            $list .= "<option value='".$row ['manufacturer_id']."'  $selected>".$row [manufacturer_name]."</option>";
        }
        $list .= "</select>";
        $output ["manufacturer"] = $list;
        AjaxUtil::output($output);
    }

    public function editPurchasedBundles()
    {
        $view = Zikula_View::getInstance($this->name);
        // $view->setCaching(Zikula_View::CACHE_ENABLED);
        $data = '';
        // $sid = $_REQUEST['sid'];
        // $shop_id = $_REQUEST['shop_id'];
        // $bundle_id = $_REQUEST['bundle_id'];
        $sid  = $_REQUEST ['sids'];
        $sid  = explode(',', $sid);
        $sids = array();
        // echo "<pre>"; print_r($sids); echo "</pre>"; exit;
        $em   = ServiceUtil::getService('doctrine.entitymanager');
        foreach ($sid as $k => $v) {
            // $sids[$k]['sid'] = $v;
            $sids [$k] ['sid']               = $v;
            // echo $v . "<br>";
            $find                            = $this->entityManager->getRepository('ZSELEX_Entity_ServiceBundle')->getPurchasedBundle(array(
                'sid' => $v
            ));
            $sids [$k] ['bundle_id']         = $find ['bundle_id'];
            $sids [$k] ['bundle_name']       = $find ['bundle_name'];
            $sids [$k] ['shop_id']           = $find ['shop_id'];
            $sids [$k] ['current_demo_days'] = $this->entityManager->getRepository('ZSELEX_Entity_Bundle')->getCurrentDemoDays(array(
                'sid' => $v
            ));
        }
        // exit;
        // echo "<pre>"; print_r($sids); echo "</pre>"; exit;
        // $view->assign('sid', $sid);
        $view->assign('sids', $sids);
        // $view->assign('shop_id', $shop_id);
        // $view->assign('bundle_id', $bundle_id);
        // $this->view->assign('owner_id', $this->owner_id);
        // $current_demo_days = $this->entityManager->getRepository('ZSELEX_Entity_Bundle')->getCurrentDemoDays(array('sid' => $sid));
        $view->assign('current_demo_days', $current_demo_days);
        $output_file     = $view->fetch('ajax/purchased_bundle.tpl');
        $data .= new Zikula_Response_Ajax_Plain($output_file);
        $output ["data"] = $data;
        AjaxUtil::output($output);
    }

    function copyProduct()
    {
        $curr_product_id = $_REQUEST ['product_id'];
        $shop_id         = $_REQUEST ['shop_id'];
        $data            = '';

        $products        = $this->entityManager->getRepository('ZSELEX_Entity_Product')->getProducts(array(
            'shop_id' => $shop_id,
            'product_id' => $curr_product_id
        ));
        // echo "<pre>"; print_r($products); echo "</pre>"; exit;
        $this->view->assign('products', $products);
        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('curr_product_id', $curr_product_id);
        // $this->view->assign('owner_id', $this->owner_id);
        $output_file     = $this->view->fetch('ajax/showProducts.tpl');
        $data .= new Zikula_Response_Ajax_Plain($output_file);
        $output ["data"] = $data;
        AjaxUtil::output($output);
    }

    function saveCopyProduct()
    {
        $new_product_id  = $_REQUEST ['product_id'];
        $shop_id         = $_REQUEST ['shop_id'];
        $curr_product_id = $_REQUEST ['curr_product_id'];

        $copy = ModUtil::apiFunc('ZSELEX', 'product', 'copyProduct',
                array(
                'shop_id' => $shop_id,
                'new_product_id' => $new_product_id,
                'curr_product_id' => $curr_product_id
        ));
        if (!$copy) {
            // return LogUtil::registerPermissionError();
            // LogUtil::registerError('herlloooo');
            // return http_response_code(403);
        }

        $output ["curr_product_id"] = $curr_product_id;
        AjaxUtil::output($output);
    }

    function updateBundlesForShop($ags)
    {
        $repo            = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $shops           = $_REQUEST ['shopIds'];
        // echo "<pre>"; print_r($shops); echo "</pre>"; exit;
        $data            = '';
        $this->view->assign('shopIds', $shops);
        $bundles         = $repo->getAll(array(
            'entity' => 'ZSELEX_Entity_Bundle'
        ));
        // echo "<pre>"; print_r($bundles); echo "</pre>"; exit;
        $this->view->assign('bundles', $bundles);
        $output_file     = $this->view->fetch('ajax/update_bundles.tpl');
        $data .= new Zikula_Response_Ajax_Plain($output_file);
        $output ["data"] = $data;
        AjaxUtil::output($output);
    }

    function reactivateDemo($args)
    {
        $repo  = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $shops = $_REQUEST ['shopIds'];

        // echo "<pre>"; print_r($shops); echo "</pre>"; die();

        $data = '';
        $this->view->assign('shopIds', $shops);

        $bundles         = $repo->getAll(array(
            'entity' => 'ZSELEX_Entity_Bundle'
        ));
        // echo "<pre>"; print_r($bundles); echo "</pre>"; exit;
        $this->view->assign('bundles', $bundles);
        $output_file     = $this->view->fetch('ajax/reactivate_demo.tpl');
        $data .= new Zikula_Response_Ajax_Plain($output_file);
        $output ["data"] = $data;
        AjaxUtil::output($output);
    }

    /**
     * Edit product settings pop up
     * 
     * @param type $ags
     */
    function editProductSetting($ags)
    {
        $repo            = $this->entityManager->getRepository('ZSELEX_Entity_Product');
        $shopId          = $_REQUEST ['shop_id'];
        // echo "<pre>"; print_r($shops); echo "</pre>"; exit;
        $data            = '';
        $this->view->assign('shop_id', $shopId);
        $settings        = $repo->get(array(
            'entity' => 'ZSELEX_Entity_Shop',
            'fields' => array('a.advertise_sel_prods'),
            'where' => array('a.shop_id' => $shopId)
            )
        );
        //echo "<pre>"; print_r($settings); echo "</pre>"; exit;
        $this->view->assign('settings', $settings);
        $output_file     = $this->view->fetch('ajax/product_settings.tpl');
        $data .= new Zikula_Response_Ajax_Plain($output_file);
        $output ["data"] = $data;
        AjaxUtil::output($output);
    }

    /**
     * Save settings
     *
     * @params POST 
     * @return boolean
     */
    function saveProductSettings()
    {

        $this->checkCsrfToken();
        $error    = 0;
        $formData = FormUtil::getPassedValue('settings', '', 'POST');
        //echo "<pre>"; print_r($formData); echo "</pre>"; exit;
        $shopId   = $formData['shop_id'];
        $repo     = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        $item     = array(
            'advertise_sel_prods' => ($formData['advertise_sel_prods']) ? 1 : 0
        );

        // echo "<pre>"; print_r($item); echo "</pre>"; exit;

        $update = $repo->updateEntity(null, 'ZSELEX_Entity_Shop', $item,
            array(
            'a.shop_id' => $shopId
        ));

        if (!$update) {
            $error++;
        }
        //  $error++;

        $output['error'] = $error;

        // echo "<pre>"; print_r($output); echo "</pre>"; exit;

        AjaxUtil::output($output);
    }
}
?>
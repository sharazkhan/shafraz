<?php

class ZSELEX_Api_Service extends ZSELEX_Api_Admin
{

    function diskQuoataCheck($args)
    {
        $shop_id    = $args ['shop_id'];
        $filesize   = $args ['filesize'];
        $msg        = '';
        $diskquota  = ModUtil::apiFunc('ZSELEX', 'admin', 'checkDiskquota',
                $args       = array(
                'shop_id' => $shop_id
        ));
        $sizeExceed = 0;
        // $filesize = $files['size'];
        $allsize    = $diskquota ['sizeused'] + $filesize;

        $error = 0;
        if ($diskquota ['error']) {
            $error = 1;
            $msg   = $diskquota ['message'];
        }
        if ($allsize >= $diskquota ['sizelimit']) {
            $error      = 1;
            $sizeExceed = 1;
            $msg        = $this->__("Your disquota is exceeded. Please upgrade");
        }

        return array(
            'error' => $error,
            'msg' => $msg
        );
    }

    function ownerFolderSize($args)
    {
        $repo     = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        $owner_id = $args ['owner_id'];
        if (!$owner_id) {
            return '0 B';
        }
        $getAllShops = $repo->getAll(array(
            'entity' => 'ZSELEX_Entity_ShopOwner',
            'fields' => array(
                'b.shop_id'
            ),
            'joins' => array(
                'JOIN a.shop b'
            ),
            'where' => array(
                'a.user_id' => $owner_id
            )
        ));

        $ownerfoldersize = 0;
        $size            = 0;
        foreach ($getAllShops as $val) {
            $fspath = $_SERVER ['DOCUMENT_ROOT']."/zselexdata/".$val ['shop_id'];
            if ($_SERVER ['SERVER_NAME'] == 'localhost') {
                $fspath = "zselexdata/".$val ['shop_id'];
            }
            $size += $this->filesize_recursive($fspath);
        }

        $ownerfoldersize = $this->display_size($size);

        return $ownerfoldersize;

        // echo "<pre>"; print_r($getAllShops); echo "</pre>";
    }

    public function deleteExtraEmployeeServices($args)
    {
        // error_reporting('E_ALL');
        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        try {

            // $arra = array();
            $shop_id = $args ['shop_id'];

            $servicecheck = $repo->get(array(
                'entity' => 'ZSELEX_Entity_ServiceShop',
                'fields' => array(
                    'a.availed',
                    'a.quantity'
                ),
                'where' => array(
                    'a.shop' => $shop_id,
                    'a.type' => 'employees'
                )
            ));

            // echo "<pre>"; print_r($servicecheck); echo "</pre>"; exit;
            if ($servicecheck ['availed'] > $servicecheck ['quantity']) {
                $service_used_extra  = $servicecheck ['availed'] - $servicecheck ['quantity'];
                $original_used_extra = $servicecheck ['availed'] - $service_used_extra;
                // echo $original_used_extra;

                $service_extra = $repo->getAll(array(
                    'entity' => 'ZSELEX_Entity_Employee',
                    'fields' => array(
                        'a.emp_image',
                        'a.emp_id'
                    ),
                    'where' => array(
                        'a.shop' => $shop_id
                    ),
                    'startlimit' => 0,
                    'offset' => $service_used_extra,
                    'orderby' => 'a.emp_id DESC'
                ));

                // echo "<pre>"; print_r($service_extra); echo "</pre>"; exit;

                foreach ($service_extra as $extra_item) {
                    unlink('zselexdata/'.$shop_id.'/employees/fullsize/'.$extra_item ['emp_image']);
                    unlink('zselexdata/'.$shop_id.'/employees/medium/'.$extra_item ['emp_image']);
                    unlink('zselexdata/'.$shop_id.'/employees/thumb/'.$extra_item ['emp_image']);
                    // $where = "emp_id=$extra_item[emp_id]";
                    // DBUtil::deleteWhere('zselex_shop_employees', $where);
                    $repo->deleteEntity(null, 'ZSELEX_Entity_Employee',
                        array(
                        'a.emp_id' => $extra_item ['emp_id']
                    ));

                    // echo $extra_item['pdf_name'] . '<br>';
                }

                $update_service = $repo->updateEntity(null,
                    'ZSELEX_Entity_ServiceShop',
                    array(
                    'availed' => $original_used_extra
                    ),
                    array(
                    'a.shop' => $shop_id,
                    'a.type' => 'employees'
                ));
            }
            return true;
        } catch (\Exception $e) {
            // echo 'Message: ' . $e->getMessage() . '<br>';
            // echo $query->getSQL();
            // exit;
            // error_log("Error : " . $e->getMessage(), 3, "/var/www/zselex/modules/ZSELEX/errors.log");
        }
    }

    public function deleteExtraImageService($args)
    {
        // error_reporting('E_ALL');
        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        try {

            // $arra = array();
            $shop_id = $args ['shop_id'];

            $servicecheck = $repo->get(array(
                'entity' => 'ZSELEX_Entity_ServiceShop',
                'fields' => array(
                    'a.availed',
                    'a.quantity'
                ),
                'where' => array(
                    'a.shop' => $shop_id,
                    'a.type' => 'minisiteimages'
                )
            ));

            // echo "<pre>"; print_r($servicecheck); echo "</pre>"; exit;
            if ($servicecheck ['availed'] > $servicecheck ['quantity']) {
                $service_used_extra  = $servicecheck ['availed'] - $servicecheck ['quantity'];
                $original_used_extra = $servicecheck ['availed'] - $service_used_extra;
                // echo $original_used_extra;

                $service_extra = $repo->getAll(array(
                    'entity' => 'ZSELEX_Entity_MinisiteImage',
                    'fields' => array(
                        'a.name',
                        'a.file_id'
                    ),
                    'where' => array(
                        'a.shop' => $shop_id
                    ),
                    'startlimit' => 0,
                    'offset' => $service_used_extra,
                    'orderby' => 'a.file_id DESC'
                ));

                // echo "<pre>"; print_r($service_extra); echo "</pre>"; exit;

                foreach ($service_extra as $extra_item) {
                    unlink('zselexdata/'.$shop_id.'/minisiteimages/fullsize/'.$extra_item ['name']);
                    unlink('zselexdata/'.$shop_id.'/minisiteimages/medium/'.$extra_item ['name']);
                    unlink('zselexdata/'.$shop_id.'/minisiteimages/thumb/'.$extra_item ['name']);
                    // $where = "emp_id=$extra_item[emp_id]";
                    // DBUtil::deleteWhere('zselex_shop_employees', $where);
                    $repo->deleteEntity(null, 'ZSELEX_Entity_MinisiteImage',
                        array(
                        'a.file_id' => $extra_item ['file_id']
                    ));

                    // echo $extra_item['pdf_name'] . '<br>';
                }

                $update_service = $repo->updateEntity(null,
                    'ZSELEX_Entity_ServiceShop',
                    array(
                    'availed' => $original_used_extra
                    ),
                    array(
                    'a.shop' => $shop_id,
                    'a.type' => 'minisiteimages'
                ));
            }
            return true;
        } catch (\Exception $e) {
            // echo 'Message: ' . $e->getMessage() . '<br>';
            // echo $query->getSQL();
            // exit;
            // error_log("Error : " . $e->getMessage(), 3, "/var/www/zselex/modules/ZSELEX/errors.log");
        }
    }

    public function deleteExtraEventService($args)
    {
        // error_reporting('E_ALL');
        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        try {

            // $arra = array();
            $shop_id = $args ['shop_id'];

            $servicecheck = $repo->get(array(
                'entity' => 'ZSELEX_Entity_ServiceShop',
                'fields' => array(
                    'a.availed',
                    'a.quantity'
                ),
                'where' => array(
                    'a.shop' => $shop_id,
                    'a.type' => 'eventservice'
                )
            ));

            // echo "<pre>"; print_r($servicecheck); echo "</pre>"; exit;
            if ($servicecheck ['availed'] > $servicecheck ['quantity']) {
                $service_used_extra  = $servicecheck ['availed'] - $servicecheck ['quantity'];
                $original_used_extra = $servicecheck ['availed'] - $service_used_extra;
                // echo $original_used_extra;

                $service_extra = $repo->getAll(array(
                    'entity' => 'ZSELEX_Entity_Event',
                    'fields' => array(
                        'a.shop_event_id',
                        'a.event_doc',
                        'a.event_image'
                    ),
                    'where' => array(
                        'a.shop' => $shop_id
                    ),
                    'startlimit' => 0,
                    'offset' => $service_used_extra,
                    'orderby' => 'a.shop_event_id DESC'
                ));

                // echo "<pre>"; print_r($service_extra); echo "</pre>"; exit;

                foreach ($service_extra as $extra_item) {
                    @unlink('zselexdata/'.$shop_id.'/events/fullsize/'.$extra_item ['event_image']);
                    @unlink('zselexdata/'.$shop_id.'/events/medium/'.$extra_item ['event_image']);
                    @unlink('zselexdata/'.$shop_id.'/events/thumb/'.$extra_item ['event_image']);
                    @unlink('zselexdata/'.$shop_id.'/events/docs/'.$extra_item ['event_doc']);
                    // $pdfImg = basename($extra_item['event_doc'], ".pdf");
                    $fileInfo = pathinfo($extra_item ['event_doc']);
                    $fileExt  = $fileInfo ['extension'];
                    $fileBase = $fileInfo ['filename'];
                    if ($fileExt == 'pdf') {
                        @unlink('zselexdata/'.$shop_id.'/events/docs/fullsize/'.$fileBase.'.jpg');
                        @unlink('zselexdata/'.$shop_id.'/events/docs/medium/'.$fileBase.'.jpg');
                        @unlink('zselexdata/'.$shop_id.'/events/docs/thumb/'.$fileBase.'.jpg');
                    }

                    $repo->deleteEntity(null, 'ZSELEX_Entity_Event',
                        array(
                        'a.shop_event_id' => $extra_item ['shop_event_id']
                    ));

                    // echo $extra_item['pdf_name'] . '<br>';
                }

                $update_service = $repo->updateEntity(null,
                    'ZSELEX_Entity_ServiceShop',
                    array(
                    'availed' => $original_used_extra
                    ),
                    array(
                    'a.shop' => $shop_id,
                    'a.type' => 'eventservice'
                ));
            }
            return true;
        } catch (\Exception $e) {
            // echo 'Message: ' . $e->getMessage() . '<br>';
            // echo $query->getSQL();
            // exit;
            // error_log("Error : " . $e->getMessage(), 3, "/var/www/zselex/modules/ZSELEX/errors.log");
        }
    }

    public function deleteExtraProductService($args)
    {
        // error_reporting('E_ALL');
        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        try {

            // $arra = array();
            $shop_id = $args ['shop_id'];

            $servicecheck = $repo->get(array(
                'entity' => 'ZSELEX_Entity_ServiceShop',
                'fields' => array(
                    'a.availed',
                    'a.quantity'
                ),
                'where' => array(
                    'a.shop' => $shop_id,
                    'a.type' => 'addproducts'
                )
            ));

            // echo "<pre>"; print_r($servicecheck); echo "</pre>"; exit;
            if ($servicecheck ['availed'] > $servicecheck ['quantity']) {
                $service_used_extra  = $servicecheck ['availed'] - $servicecheck ['quantity'];
                $original_used_extra = $servicecheck ['availed'] - $service_used_extra;
                // echo $original_used_extra;

                $service_extra = $repo->getAll(array(
                    'entity' => 'ZSELEX_Entity_Product',
                    'fields' => array(
                        'a.prd_image',
                        'a.product_id'
                    ),
                    'where' => array(
                        'a.shop' => $shop_id
                    ),
                    'startlimit' => 0,
                    'offset' => $service_used_extra,
                    'orderby' => 'a.product_id DESC'
                ));

                // echo "<pre>"; print_r($service_extra); echo "</pre>"; exit;

                foreach ($service_extra as $extra_item) {
                    @unlink('zselexdata/'.$shop_id.'/products/fullsize/'.$extra_item ['prd_image']);
                    @unlink('zselexdata/'.$shop_id.'/products/medium/'.$extra_item ['prd_image']);
                    @unlink('zselexdata/'.$shop_id.'/products/thumb/'.$extra_item ['prd_image']);

                    $repo->deleteEntity(null, 'ZSELEX_Entity_Product',
                        array(
                        'a.product_id' => $extra_item ['product_id']
                    ));

                    // echo $extra_item['pdf_name'] . '<br>';
                }

                $update_service = $repo->updateEntity(null,
                    'ZSELEX_Entity_ServiceShop',
                    array(
                    'availed' => $original_used_extra
                    ),
                    array(
                    'a.shop' => $shop_id,
                    'a.type' => 'addproducts'
                ));
            }
            return true;
        } catch (\Exception $e) {
            // echo 'Message: ' . $e->getMessage() . '<br>';
            // echo $query->getSQL();
            // exit;
            // error_log("Error : " . $e->getMessage(), 3, "/var/www/zselex/modules/ZSELEX/errors.log");
        }
    }

    public function insertBundleItems($args)
    {
        $repo           = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $bundleitems    = $args ['bundleitems'];
        $timer_days     = $args ['timer_days'];
        $shop_id        = $args ['shop_id'];
        $owner_id       = $args ['owner_id'];
        $bundle_type    = $args ['bundle_type'];
        $bundle_id      = $args ['bundle_id'];
        // $main_bundle = $args['main_bundle'];
        $shop_id        = $args ['shop_id'];
        $service_status = $args ['service_status'];
        // echo "<pre>comes here :"; print_r($args); echo "</pre>"; exit;
        // $pntables = pnDBGetTables();
        // $column = $pntables['zselex_serviceshop_column'];
        foreach ($bundleitems as $key => $val) {

            $count = $repo->getCount(null, 'ZSELEX_Entity_ServiceShop', 'id',
                array(
                'a.shop' => $shop_id,
                'a.type' => $val ['servicetype']
            ));

            // echo $count; exit;
            if ($count > 0) { // already exists then update
                // echo $val[servicetype]; exit;
                $service_plugin = $repo->get(array(
                    'entity' => 'ZSELEX_Entity_Plugin',
                    'fields' => array(
                        'a.qty_based',
                        'a.bundle_id'
                    ),
                    'where' => array(
                        'a.type' => $val ['servicetype']
                    )
                ));

                $get_qnty   = $repo->get(array(
                    'entity' => 'ZSELEX_Entity_ServiceShop',
                    'fields' => array(
                        'a.quantity'
                    ),
                    'where' => array(
                        'a.type' => $val ['servicetype'],
                        'a.shop' => $shop_id
                    )
                ));
                // echo "<pre>"; print_r($get_qnty); echo "</pre>"; exit;
                // echo "<pre>"; print_r($service_plugin); echo "</pre>"; exit;
                $qty_update = $val ['qty'];
                // $pntables = pnDBGetTables();
                // $column = $pntables['zselex_serviceshop_column'];

                if ($bundle_type == 'additional') {
                    if ($service_plugin ['qty_based'] == 1) { // quantity based then updated quantity
                        $new_qty = $get_qnty ['quantity'] + $val ['qty'];
                    } else {
                        $new_qty = 1;
                    }
                } else { // main bundle
                    $existing_quantity = $service_plugin ['bundle_id'];
                    if ($service_plugin ['qty_based'] == 1) {
                        // check if any additional bundle has already configured.and update quantity accordingly
                        $updated_qty = $this->getAdditionalBundleQty(array(
                            'qty' => $val ['qty'],
                            'shop_id' => $shop_id,
                            'type' => $val [servicetype]
                        ));
                        // $new_qty = $val['qty'];
                        $new_qty     = $updated_qty;
                    } else {
                        $new_qty = 1;
                    }
                }
                $obj = array(
                    'original_quantity' => $new_qty,
                    'quantity' => $new_qty
                    )
                // 'timer_date' => $args['timer_date'],
                // 'timer_days' => $args['timer_days'],
                ;
                // $obj['main_bundle'] = $main_bundle;
                if ($bundle_type == 'main') {
                    // change timer date and days only if its a main bundle.
                    // $obj['bundle_id'] = $bundle_id;
                    $obj ['bundle']         = $bundle_id;
                    $obj ['timer_date']     = $args ['timer_date'];
                    $obj ['timer_days']     = $args ['timer_days'];
                    $obj ['service_status'] = $service_status;
                }
                /*
                 * $where = "WHERE $column[shop_id]=$shop_id AND $column[type]='" . $val[servicetype] . "'";
                 * DBUTil::updateObject($obj, 'zselex_serviceshop', $where);
                 */
                $repo->updateEntity(null, 'ZSELEX_Entity_ServiceShop', $obj,
                    array(
                    'a.shop' => $shop_id,
                    'a.type' => $val ['servicetype']
                ));
            } else { // add new - fresh record
                // echo "comes here"; exit;
                $updated_qty = $this->getAdditionalBundleQty(array(
                    // 'qty' => $val ['qty'],
                    'qty' => ($bundle_type == 'main') ? $val ['qty'] : 0,
                    'shop_id' => $shop_id,
                    'type' => $val [servicetype]
                ));

                // echo "updated_qty :". $updated_qty; exit;

                $srvcShop  = new ZSELEX_Entity_ServiceShop ();
                $shopObj   = $this->entityManager->find('ZSELEX_Entity_Shop',
                    $shop_id);
                $srvcShop->setShop($shopObj);
                $srvcShop->setUser_id($args ['user_id']);
                $srvcShop->setOwner_id($owner_id);
                $pluginObj = $this->entityManager->find('ZSELEX_Entity_Plugin',
                    $val ['plugin_id']);
                $srvcShop->setPlugin($pluginObj);
                $srvcShop->setType($val ['servicetype']);
                $srvcShop->setOriginal_quantity($updated_qty);
                $srvcShop->setQuantity($updated_qty);
                $srvcShop->setStatus(1);
                $srvcShop->setService_status($service_status);
                $srvcShop->setQty_based($val ['qty_based']);
                $srvcShop->setIs_bundle(1);
                $bundleObj = $this->entityManager->find('ZSELEX_Entity_Bundle',
                    $bundle_id);
                $srvcShop->setBundle($bundleObj);
                $srvcShop->setBundle_type($bundle_type);
                $srvcShop->setTimer_date(date_create($args ['timer_date']));
                $srvcShop->setTimer_days($args ['timer_days']);
                $this->entityManager->persist($srvcShop);
                $this->entityManager->flush();
                $result    = $srvcShop->getId();

                // echo $result; exit;

                if ($val ['servicetype'] == 'minishop') { // configure minishop direcly
                    /*
                     * $m_arg = array('table' => 'zselex_minishop', 'where' => "shop_id=$shop_id", 'Id' => 'id');
                     * $minishopCount = ModUtil::apiFunc('ZSELEX', 'admin', 'countElements', $m_arg);
                     */
                    $minishopCount = $repo->getCount(null,
                        'ZSELEX_Entity_MiniShop', 'id',
                        array(
                        'a.shop' => $shop_id
                    ));
                    if ($minishopCount < 1) {
                        if ($result) { // configure as ishop as defauly if its a 'minishop' service
                            /*
                             * $item_minishop = array(
                             * 'shop_id' => $shop_id,
                             * 'shoptype_id' => 2,
                             * 'shoptype' => 'iSHOP',
                             * 'minishop_name' => 'My Ishop',
                             * 'description' => '',
                             * 'configured' => 1,
                             * );
                             * $args_minishop = array(
                             * 'table' => 'zselex_minishop',
                             * 'element' => $item_minishop,
                             * 'Id' => 'id'
                             * );
                             *
                             * $insert_minishop = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $args_minishop);
                             */
                            // echo "comes here..."; exit;
                            $miniShop = new ZSELEX_Entity_MiniShop ();
                            $shopObj  = $this->entityManager->find('ZSELEX_Entity_Shop',
                                $shop_id);
                            $miniShop->setShop($shopObj);
                            $miniShop->setShoptype_id(2);
                            $miniShop->setShoptype('iSHOP');
                            $miniShop->setMinishop_name('My Ishop');
                            $miniShop->setDescription(null);
                            $miniShop->setConfigured(true);
                            $this->entityManager->persist($miniShop);
                            $this->entityManager->flush();
                        }
                    }
                }
            }
        }

        /*
         * $obj_bndle = array(
         * 'bundle' => $bundle_id,
         * );
         *
         * $result = $repo->updateEntity(null, 'ZSELEX_Entity_ServiceShop', $obj_bndle, array('a.shop' => $shop_id));
         */
        return true;
    }

    public function getAdditionalBundleQty($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $repo    = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $qty     = $args ['qty'];
        $type    = $args ['type'];
        $shop_id = $args ['shop_id'];

        $additional = $repo->getAll(array(
            'entity' => 'ZSELEX_Entity_ServiceBundle',
            'where' => array(
                'a.bundle_type' => 'additional',
                'a.shop' => $shop_id
            ),
            'joins' => array(
                'JOIN a.bundle b'
            ),
            'fields' => array(
                'a.quantity',
                'b.bundle_id'
            )
        ));

        //  echo "<pre>"; print_r($additional); echo "</pre>";  exit;
        // $qty = 0;
        if ($additional) {
            foreach ($additional as $key) {

                $additional_qty = $repo->get(array(
                    'entity' => 'ZSELEX_Entity_BundleItem',
                    'where' => array(
                        'a.servicetype' => $type,
                        'a.bundle' => $key ['bundle_id']
                    ),
                    'fields' => array(
                        'a.qty',
                        'a.qty_based'
                    )
                ));
                //echo "<pre>"; print_r($additional_qty); echo "</pre>";  exit;
                // if ($additional_qty) {
                if ($additional_qty && $additional_qty ['qty_based']) {
                    $qty += $additional_qty ['qty'] * $key ['quantity'];
                }
            }
        }

        //echo "qty :".$qty; exit;

        return $qty;
    }

    public function addService($args)
    { // new add to demo function
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $repo    = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $user_id = UserUtil::getVar('uid');
        if ($args ['service_status'] != 2) { // demo
            $shop_id        = $args ["shop_id"];
            $quantity       = $args ["quantity"];
            $bundle_id      = $args ["bundle_id"];
            $top_bundle     = 1;
            $timer_days     = $args ["timer_days"];
            $service_status = 1;
            // $paidRunning = $repo->getCount(null, 'ZSELEX_Entity_ServiceBundle', 'service_bundle_id', array('a.shop' => $shop_id, 'a.bundle_type' => 'main', 'a.service_status' => 2));
        } else { // paid
            // $user_id = UserUtil::getVar('uid');
            if (!$user_id) {
                $user_id = $args ["user_id"];
            }
            $shop_id        = $args ["shop_id"];
            $quantity       = $args ["quantity"];
            $bundle_id      = $args ["bundle_id"];
            $top_bundle     = 1;
            $timer_days     = $args ["timer_days"];
            $service_status = 2;

            // $repo->deleteEntity(null, 'ZSELEX_Entity_ServiceBundle', array('a.shop' => $shop_id, 'a.service_status' => 1));
            // $repo->deleteEntity(null, 'ZSELEX_Entity_ServiceShop', array('a.shop' => $shop_id, 'a.service_status' => 1));
        }

        // echo "<pre>"; print_r($args); echo "</pre>"; exit;

        $ownerInfo = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwnerInfo',
                $args      = array(
                'shop_id' => $shop_id
        ));

        $owner_id = $ownerInfo ['user_id'];
        // $source = FormUtil::getPassedValue("source");
        // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;

        $bundle_detail = $repo->get(array(
            'entity' => 'ZSELEX_Entity_Bundle',
            'where' => array(
                'a.bundle_id' => $bundle_id
            )
        ));

        // echo "<pre>";print_r($bundle_detail); echo "</pre>"; exit;

        $servicePrice = $bundle_detail ["bundle_price"];
        $bundle_type  = $bundle_detail ["bundle_type"];
        $bundle_name  = $bundle_detail ["bundle_name"];

        // $date = date("Y-m-d");
        // $date = $args["timer_date"];
        // echo $count; exit;
        // echo "<pre>"; print_r($main_bundle); echo "</pre>"; exit;
        $main_bundle = $repo->get(array(
            'entity' => 'ZSELEX_Entity_ServiceBundle',
            'fields' => array(
                'a.service_bundle_id',
                'a.timer_date',
                'a.timer_days',
                'b.bundle_id'
            ),
            'joins' => array(
                'JOIN a.bundle b'
            ),
            'where' => array(
                'a.bundle_type' => 'main',
                'a.shop' => $shop_id
            )
        ));
        if (!$main_bundle) {
            return;
        }
        // echo "<pre>"; print_r($main_bundle); echo "</pre>"; exit;
        // if ($bundle == 1) {
        $values = array(
            'bundle' => 1,
            'service_status' => $service_status,
            'user_id' => $user_id,
            'owner_id' => $owner_id,
            'shop_id' => $shop_id,
            // 'main_bundle' => $main_bundle['bundle_id'],
            'timer_date' => $main_bundle ['timer_date']
        );

        $bundleitems = $repo->getAll(array(
            'entity' => 'ZSELEX_Entity_BundleItem',
            'fields' => array(
                'a.id',
                'b.bundle_id',
                'a.service_name',
                'a.servicetype',
                'c.plugin_id',
                'a.price',
                'a.qty',
                'a.qty_based'
            ),
            'joins' => array(
                'JOIN a.bundle b',
                'LEFT JOIN a.plugin c'
            ),
            'where' => array(
                'a.bundle' => $bundle_id
            ),
            'groupby' => 'a.id'
        ));

        // echo "<pre>"; print_r($bundleitems); echo "</pre>"; exit;
        $values ['quantity']       = $quantity;
        $values ['bundleitems']    = $bundleitems;
        $values ['timer_days']     = $main_bundle ['timer_days'];
        $values ['bundle_type']    = $bundle_type;
        $values ['bundle_id']      = $bundle_id;
        // $values['bundle_id'] = $main_bundle['bundle_id'];
        $values ['shop_id']        = $shop_id;
        $values ['service_status'] = $service_status;

        // echo "<pre>"; print_r($values);exit;
        $approvebundlesitems = $this->insertBundleItemsRepair($values);
        // }

        if ($service_status == 1) {
            LogUtil::registerStatus($this->__('Service configured for demo'));
        } else {
            LogUtil::registerStatus($this->__('Paid Service configured successfully'));
            return true;
        }
        return true;
    }

    private function getQuantity($shop_id, $bundle_type, $servicetype,
                                 $item_qty, $bundle_qty)
    {
        $repo           = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $service_plugin = $repo->get(array(
            'entity' => 'ZSELEX_Entity_Plugin',
            'fields' => array(
                'a.qty_based',
                'a.bundle_id'
            ),
            'where' => array(
                'a.type' => $servicetype
            )
        ));

        $get_qnty = $repo->get(array(
            'entity' => 'ZSELEX_Entity_ServiceShop',
            'fields' => array(
                'a.quantity'
            ),
            'where' => array(
                'a.type' => $servicetype,
                'a.shop' => $shop_id
            )
        ));
        // echo "<pre>"; print_r($get_qnty); echo "</pre>"; exit;
        // echo "<pre>"; print_r($service_plugin); echo "</pre>"; exit;
        // $qty_update = $val['qty'];
        // $pntables = pnDBGetTables();
        // $column = $pntables['zselex_serviceshop_column'];

        if ($bundle_type == 'additional') {
            if ($service_plugin ['qty_based'] == 1) { // quantity based then updated quantity
                $new_qty = $get_qnty ['quantity'] + ($item_qty * $bundle_qty);
            } else {
                $new_qty = 1;
            }
        } else { // main bundle
            $existing_quantity = $service_plugin ['bundle_id'];
            if ($service_plugin ['qty_based'] == 1) {
                // check if any additional bundle has already configured.and update quantity accordingly
                // $updated_qty = $this->getAdditionalBundleQty(array('qty' => $val['qty'], 'shop_id' => $shop_id, 'type' => $val[servicetype]));
                // $new_qty = $val['qty'];
                $new_qty = $get_qnty ['quantity'] + $item_qty;
            } else {
                $new_qty = 1;
            }
        }

        return $new_qty;
    }

    public function insertBundleItemsRepair($args)
    {
        $repo           = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $bundleitems    = $args ['bundleitems'];
        $timer_days     = $args ['timer_days'];
        $shop_id        = $args ['shop_id'];
        $owner_id       = $args ['owner_id'];
        $bundle_type    = $args ['bundle_type'];
        $bundle_id      = $args ['bundle_id'];
        // $main_bundle = $args['main_bundle'];
        $shop_id        = $args ['shop_id'];
        $service_status = $args ['service_status'];
        $quantity       = $args ['quantity'];
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        // $pntables = pnDBGetTables();
        // $column = $pntables['zselex_serviceshop_column'];
        foreach ($bundleitems as $key => $val) {
            $new_qty  = $this->getQuantity($shop_id, $bundle_type,
                $val ['servicetype'], $val ['qty'], $quantity);
            $used_qty = $this->getServiceUsedCount(array(
                'service_type' => $val ['servicetype'],
                'shop_id' => $shop_id
            ));
            $count    = $repo->getCount(null, 'ZSELEX_Entity_ServiceShop', 'id',
                array(
                'a.shop' => $shop_id,
                'a.type' => $val ['servicetype']
            ));

            // echo $count; exit;
            if ($count > 0) { // already exists then update
                // echo $val[servicetype]; exit;
                $obj                    = array(
                    'original_quantity' => $new_qty,
                    'quantity' => $new_qty,
                    'availed' => $used_qty
                    )
                // 'timer_date' => $args['timer_date'],
                // 'timer_days' => $args['timer_days'],
                ;
                // $obj['main_bundle'] = $main_bundle;
                // if ($bundle_type == 'main') {
                // change timer date and days only if its a main bundle.
                // $obj['bundle_id'] = $bundle_id;
                $obj ['bundle']         = $bundle_id;
                $obj ['timer_date']     = $args ['timer_date'];
                $obj ['timer_days']     = $args ['timer_days'];
                $obj ['service_status'] = $service_status;
                // }

                $repo->updateEntity(null, 'ZSELEX_Entity_ServiceShop', $obj,
                    array(
                    'a.shop' => $shop_id,
                    'a.type' => $val ['servicetype']
                ));
            } else { // add new - fresh record
                // $updated_qty = $this->getAdditionalBundleQty(array('qty' => $val['qty'], 'shop_id' => $shop_id, 'type' => $val[servicetype]));
                $srvcShop  = new ZSELEX_Entity_ServiceShop ();
                $shopObj   = $this->entityManager->find('ZSELEX_Entity_Shop',
                    $shop_id);
                $srvcShop->setShop($shopObj);
                $srvcShop->setUser_id($args ['user_id']);
                $srvcShop->setOwner_id($owner_id);
                $pluginObj = $this->entityManager->find('ZSELEX_Entity_Plugin',
                    $val ['plugin_id']);
                $srvcShop->setPlugin($pluginObj);
                $srvcShop->setType($val ['servicetype']);
                $srvcShop->setOriginal_quantity($new_qty);
                $srvcShop->setQuantity($new_qty);
                $srvcShop->setAvailed($used_qty);
                $srvcShop->setStatus(1);
                $srvcShop->setService_status($service_status);
                $srvcShop->setQty_based($val ['qty_based']);
                $srvcShop->setIs_bundle(1);
                $bundleObj = $this->entityManager->find('ZSELEX_Entity_Bundle',
                    $bundle_id);
                $srvcShop->setBundle($bundleObj);
                $srvcShop->setBundle_type($bundle_type);
                $srvcShop->setTimer_date(date_create($args ['timer_date']));
                $srvcShop->setTimer_days($args ['timer_days']);
                $this->entityManager->persist($srvcShop);
                $this->entityManager->flush();
                $result    = $srvcShop->getId();

                // echo $result; exit;

                if ($val ['servicetype'] == 'minishop') { // configure minishop direcly
                    $minishopCount = $repo->getCount(null,
                        'ZSELEX_Entity_MiniShop', 'id',
                        array(
                        'a.shop' => $shop_id
                    ));
                    if ($minishopCount < 1) {
                        if ($result) { // configure as ishop as defauly if its a 'minishop' service
                            // echo "comes here..."; exit;
                            $miniShop = new ZSELEX_Entity_MiniShop ();
                            $shopObj  = $this->entityManager->find('ZSELEX_Entity_Shop',
                                $shop_id);
                            $miniShop->setShop($shopObj);
                            $miniShop->setShoptype_id(2);
                            $miniShop->setShoptype('iSHOP');
                            $miniShop->setMinishop_name('My Ishop');
                            $miniShop->setDescription(null);
                            $miniShop->setConfigured(true);
                            $this->entityManager->persist($miniShop);
                            $this->entityManager->flush();
                        }
                    }
                }
            }
        }

        return true;
    }

    function getServiceUsedCount($args)
    {
        $repo         = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $service_type = $args ['service_type'];
        $shop_id      = $args ['shop_id'];
        if ($service_type == 'employees') {
            $entity   = 'ZSELEX_Entity_Employee';
            $field_id = 'emp_id';
            $count    = $repo->getCount(null, $entity, $field_id,
                array(
                "a.shop" => $shop_id
            ));
        } elseif ($service_type == 'addproducts') {
            $entity   = 'ZSELEX_Entity_Product';
            $field_id = 'product_id';
            $count    = $repo->getCount(null, $entity, $field_id,
                array(
                "a.shop" => $shop_id
            ));
        } elseif ($service_type == 'eventservice') {
            $entity   = 'ZSELEX_Entity_Event';
            $field_id = 'shop_event_id';
            $count    = $repo->getCount(null, $entity, $field_id,
                array(
                "a.shop" => $shop_id
            ));
        } elseif ($service_type == 'minisiteimages') {
            $entity   = 'ZSELEX_Entity_MinisiteImage';
            $field_id = 'file_id';
            $count    = $repo->getCount(null, $entity, $field_id,
                array(
                "a.shop" => $shop_id
            ));
        } elseif ($service_type == 'createad') {
            $entity   = 'ZSELEX_Entity_Advertise';
            $field_id = 'advertise_id';
            $count    = $repo->getCount(null, $entity, $field_id,
                array(
                "a.shop" => $shop_id
            ));
        } elseif ($service_type == 'minisitebanner') {
            $entity   = 'ZSELEX_Entity_Banner';
            $field_id = 'shop_banner_id';
            $count    = $repo->getCount(null, $entity, $field_id,
                array(
                "a.shop" => $shop_id
            ));
        } elseif ($service_type == 'minisitebanner') {
            $entity   = 'ZSELEX_Entity_Banner';
            $field_id = 'shop_banner_id';
            $count    = $repo->getCount(null, $entity, $field_id,
                array(
                "a.shop" => $shop_id
            ));
        } elseif ($service_type == 'minisiteannouncement') {
            $entity   = 'ZSELEX_Entity_Announcement';
            $field_id = 'ann_id';
            $count    = $repo->getCount(null, $entity, $field_id,
                array(
                "a.shop" => $shop_id
            ));
        } elseif ($service_type == 'exclusiveevent') {
            $entity   = 'ZSELEX_Entity_Event';
            $field_id = 'shop_event_id';
            $count    = $repo->getCount(null, $entity, $field_id,
                array(
                "a.shop" => $shop_id,
                "a.exclusive" => 1
            ));
        } elseif ($service_type == 'pages') {
            $entity   = 'ZTEXT_Entity_Page';
            $field_id = 'text_id';
            $count    = $repo->getCount(null, $entity, $field_id,
                array(
                "a.shop" => $shop_id
            ));
            // echo $count; exit;
        } elseif ($service_type == 'minisite' || $service_type == 'minishop') {
            $count = 1;
        } elseif ($service_type == 'diskquota') {

            $count = 0;
        } else {
            $count = 1;
        }
        // echo "<pre>"; print_r($products); echo "</pre>";
        return $count;
    }

    public function canBuyStatusAdditionalBundle($args)
    { // api for checking the buy staus based on dependency for bundle services
        // error_reporting(0);
        // exit;
        $repo              = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $bundleId          = $args ['bundle_id'];
        $shop_id           = $args ['shop_id'];
        $downgradeBundleId = $args ['d_bundle_id'];
        $shoptype          = ModUtil::apiFunc('ZSELEX', 'admin', 'shopType',
                $typeargs          = array(
                'shop_id' => $shop_id
        ));
        $shoptype          = $shoptype ['shoptype'];

        $result = $repo->getAll(array(
            'entity' => 'ZSELEX_Entity_BundleItem',
            'fields' => array(
                'b.plugin_id',
                'a.servicetype',
                'a.service_name',
                'b.service_depended',
                'b.depended_services',
                'b.shop_depended'
            ),
            'joins' => array(
                'LEFT JOIN a.plugin b'
            ),
            'where' => array(
                'a.bundle' => $bundleId,
                ' b.status' => 1
            )
        ));
        // echo "<pre>"; print_r($result); echo "</pre>";

        $cantbuy         = 0;
        $shopdepended    = 0;
        $finalservices   = array();
        $depend_services = array();
        $typeonly        = array();
        $b               = 0;

        foreach ($result as $key => $val) { // loop through bundle item services
            // $typeonly= array();
            $servicetype1 = $val ['servicetype'];
            // echo $servicetype1 . '<br>';
            $typeonly []  = $servicetype1;
            // echo "<pre>"; print_r($typeonly); echo "</pre>";
            // echo $servicetype . "-". $val['shop_depended'] .'<br>';
            if ($val ['service_depended'] == 1) { // check for depended services
                // echo $val['depended_services'] . '<br><br><br>';
                // echo $servicetype . "-". $val['shop_depended'] .'<br>';
                $depend_services = unserialize($val ['depended_services']); // convert to array
                $depend_services = (array) $depend_services;
                // echo "<pre>"; print_r($depend_services); echo "</pre>";
                // echo "<pre>"; print_r($newArr); echo "</pre>";

                foreach ($depend_services as $key1 => $val1) { // loop through depended services of each bundle item services
                    // echo $val1['type'] . '<br>';
                    // echo $key1 . '<br>';
                    $serviceType = $val1 ['type'];

                    $countItemInBundle = $repo->getCount(null,
                        'ZSELEX_Entity_BundleItem', 'id',
                        array(
                        'a.bundle' => $bundleId,
                        'a.servicetype' => $serviceType
                    ));
                    // echo $countItemInBundle . "<br>";
                    if ($countItemInBundle == 0) {

                        $itemExist = $repo->getCount(null,
                            'ZSELEX_Entity_BundleItem', 'id',
                            array(
                            'a.bundle' => $downgradeBundleId,
                            'a.servicetype' => $serviceType
                        ));


                        if ($itemExist < 1) {
                            $cantbuy ++;
                        }
                    }
                    if ($val ['shop_depended'] == 1) { // check for shop depended for depended services
                        $shop_depend_count = $repo->getCount2(array(
                            'entity' => 'ZSELEX_Entity_Plugin',
                            'field' => 'plugin_id',
                            'where' => "a.type=:servicetype AND (a.depended_shoptypes LIKE :shoptype OR a.depended_shoptypes LIKE '')",
                            'setParams' => array(
                                'servicetype' => $serviceType,
                                'shoptype' => "%".DataUtil::formatForStore($shoptype)."%"
                            )
                        ));

                        // echo $shop_depend_count . "<br>";
                        // echo $serviceType ."-". $ishopcount . '<br>';
                        if ($shop_depend_count < 1) {
                            $cantbuy ++; // shop based
                            $shopdepended ++;
                        }
                    }



                    // echo $depend_services[$key]['canbuystatus'] . '<br>';
                    $b ++;
                }

                // echo "<br>";
            }
        }



        return $cantbuy;
    }
}
?>
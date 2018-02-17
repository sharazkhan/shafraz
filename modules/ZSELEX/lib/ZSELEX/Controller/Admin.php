<?php

// ini_set("display_errors", '1');
class ZSELEX_Controller_Admin extends ZSELEX_Controller_Base_Admin
{

    public function viewserviceidentifiers($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        $repo    = $this->entityManager->getRepository('ZSELEX_Entity_ServiceIdentifier');
        // initialize sort array - used to display sort classes and urls
        SessionUtil::delVar('identifieritem');
        $user_id = UserUtil::getVar('uid');

        $admin = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN);
        $this->view->assign('admin', $admin);

        $sort   = array();
        $fields = array(
            'id',
            'identifier'
        ); // possible sort fields

        $itemsperpage  = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 25,
                'GETPOST');
        $startnum      = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $status        = FormUtil::getPassedValue('status',
                isset($args ['status']) ? $args ['status'] : null, 'GETPOST');
        $order         = FormUtil::getPassedValue('order',
                isset($args ['order']) ? $args ['order'] : 'id', 'GETPOST');
        $original_sdir = FormUtil::getPassedValue('sdir',
                isset($args ['sdir']) ? $args ['sdir'] : 0, 'GETPOST');
        $searchtext    = FormUtil::getPassedValue('searchtext',
                isset($args ['searchtext']) ? $args ['searchtext'] : null,
                'GETPOST');
        $this->view->assign('searchtext', $searchtext);
        $this->view->assign('startnum', $startnum);
        $this->view->assign('itemsperpage', $itemsperpage);
        $this->view->assign('order', $order);
        $this->view->assign('sdir', $original_sdir);

        $aStatus = array(
            'InActive',
            'Active'
        );
        $this->view->assign('aStatus', $aStatus);

        $sdir = $original_sdir ? 0 : 1; // if true change to false, if false change to true
        // change class for selected 'orderby' field to asc/desc
        if ($sdir == 0) {
            $sort ['class'] [$order] = 'z-order-desc';
            $orderdir                = 'DESC';
        }
        if ($sdir == 1) {
            $sort ['class'] [$order] = 'z-order-asc';
            $orderdir                = 'ASC';
        }
        // complete initialization of sort array, adding urls
        foreach ($fields as $field) {
            $sort ['url'] [$field] = ModUtil::url('ZSELEX', 'admin',
                    'viewserviceidentifiers',
                    array(
                    'status' => $status,
                    'filtercats_serialized' => serialize($filtercats),
                    'order' => $field,
                    'sdir' => $sdir
            ));
        }
        // echo "<pre>"; print_r($sort); echo "</pre>";
        $this->view->assign('sort', $sort);

        $this->view->assign('filter_active', $status);

        // $sql = " SELECT a.* FROM zselex_type AS a
        // WHERE a.type_id IS NOT NULL ";

        $sql = "";
        if (isset($status) && $status != "") {
            // $sql .= " AND status = " . $status;
            $identArgs ['where'] ['a.status'] = $status;
        }
        if (isset($searchtext) && $searchtext != "") {
            // $sql .= " AND identifier LIKE '%" . DataUtil::formatForStore($searchtext) . "%'";
            $identArgs ['like'] ['a.identifier'] = "%".DataUtil::formatForStore($searchtext)."%";
        }
        if (isset($order) && $order != "") {
            // $sql .= " ORDER BY " . $order . " " . $orderdir;
            $identArgs ['orderby'] = "a.".$order." ".$orderdir;
        }

        // Get all zselex stories
        $getArgs = array(
            'sql' => $sql,
            'startnum' => $startnum,
            'numitems' => $itemsperpage
        );
        // $items = ModUtil::apiFunc('ZSELEX', 'admin', 'getAll', $getArgs);

        $items = array();


        $identArgs ['entity']     = 'ZSELEX_Entity_ServiceIdentifier';
        $identArgs ['paginate']   = true;
        $identArgs ['startlimit'] = $startnum;
        $identArgs ['offset']     = $itemsperpage;
        $identifiers              = $repo->getAll($identArgs);
        // echo "<pre>"; print_r($itemarray); echo "</pre>";
        $items                    = $identifiers ['result'];
        $count                    = $identifiers ['count'];
        // echo "<pre>"; print_r($items); echo "</pre>";

        $total_count = $count;

        // Set the possible status for later use
        $itemstatus = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );

        $identifieritems = array();
        foreach ($items as $item) {
            $options = array();
            if (SecurityUtil::checkPermission('ZSELEX::',
                    "{$item['cr_uid']}::{$item['id']}", ACCESS_EDIT)) {
                $options [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'modifyidentifier',
                        array(
                        'id' => $item ['id']
                    )),
                    'image' => 'xedit.png',
                    'title' => $this->__('Edit')
                );

                if ((SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['id']}", ACCESS_DELETE)) || SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['type_id']}", ACCESS_ADD)) {
                    $options [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin',
                            'deleteidentifier',
                            array(
                            'id' => $item ['id']
                        )),
                        'image' => '14_layer_deletelayer.png',
                        'title' => $this->__('Delete')
                    );
                }
            }
            $item ['options']   = $options;
            $item ['infuture']  = DateUtil::getDatetimeDiff_AsField($item ['cr_date'],
                    DateUtil::getDatetime(), 6) < 0;
            $identifieritems [] = $item;
        }

        // Assign the items to the template
        $this->view->assign('identifieritems', $identifieritems);
        $this->view->assign('total_count', $total_count);

        // Assign the current status filter and the possible ones
        $this->view->assign('status', $status);
        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('order', $order);
        // Return the output that has been generated by this function
        return $this->view->fetch('admin/viewserviceidentifiers.tpl');
    }

    public function createidentifier($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $formElements = FormUtil::getPassedValue('formElements',
                isset($args ['formElements']) ? $args ['formElements'] : null,
                'POST');
        $languages    = ZLanguage::getInstalledLanguages();
        $this->view->assign('languages', $languages);
        $func         = "createidentifier";
        $this->view->assign('func', 'createidentifier');

        if ($_POST) {
            $item = array(
                'name' => $formElements ['name'],
                'identifier' => $formElements ['identifier'],
                'description' => isset($formElements ['description']) ? $formElements ['description']
                        : '',
                'status' => isset($formElements ['status']) ? $formElements ['status']
                        : 0
            );

            // $validationerror = ZSELEX_Util::validateServiceIdentifier($item);

            $validation_rules = array(
                'name' => array(
                    'required' => true,
                    'value' => $formElements ['name'],
                    'label' => 'Identifier Name',
                    'exist' => true,
                    'table' => 'zselex_service_identifiers',
                    'field_name' => 'name'
                ),
                'identifier' => array(
                    'required' => true,
                    'value' => $formElements ['identifier'],
                    'label' => 'Identifier',
                    'exist' => true,
                    'table' => 'zselex_service_identifiers',
                    'field_name' => 'identifier'
                )
            );

            $validationerror = ZSELEX_Util::validate($validation_rules);

            if ($validationerror !== false) {
                // log the error found if any
                if ($validationerror !== false) {
                    LogUtil::registerError(nl2br($validationerror));
                }
                SessionUtil::setVar('identifieritem', $item);

                $this->redirect(ModUtil::url('ZSELEX', 'admin',
                        'createidentifier'));
            } else {
                // As we're not previewing the item let's remove it from the session
                SessionUtil::delVar('identifieritem');
            }

            // echo "<pre>"; print_r($item); exit;
            $args   = array(
                'table' => 'zselex_service_identifiers',
                'element' => $item,
                'Id' => 'id'
            );
            // Create the zselex type
            $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $args);
            if ($result) {
                LogUtil::registerStatus($this->__('Done! Identifier has been created successfully.'));
                $this->redirect(ModUtil::url('ZSELEX', 'admin',
                        'viewserviceidentifiers'));
            }
        }

        $sess_item = SessionUtil::getVar('identifieritem');
        $this->view->assign('item', $sess_item);
        return $this->view->fetch('admin/createidentifier.tpl');
    }

    public function modifyidentifier($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        $repo = $this->entityManager->getRepository('ZSELEX_Entity_ServiceIdentifier');
        if ($_POST) {
            $formElements       = FormUtil::getPassedValue('formElements',
                    isset($args ['formElements']) ? $args ['formElements'] : null,
                    'POST');
            $InsertId           = $formElements ['elemId'];
            $selectedidentifier = array(
                'selected' => $formElements ['selectedidentifier']
            );
            $validateItems      = array();
            $item               = array(
                'id' => $InsertId,
                'name' => $formElements ['name'],
                'identifier' => $formElements ['identifier'],
                'description' => isset($formElements ['description']) ? $formElements ['description']
                        : '',
                'status' => isset($formElements ['status']) ? $formElements ['status']
                        : 0
            );
            $validateItems      = $item;
            $validateItems      = array_merge($validateItems,
                $selectedidentifier);
            // echo "<pre>"; print_r($validateItems); exit;
            // $validationerror = ZSELEX_Util::validateServiceIdentifier($validateItems);
            $validation_rules   = array(
                'name' => array(
                    'required' => true,
                    'value' => $formElements ['name'],
                    'label' => 'Identifier Name',
                    'exist' => true,
                    'edit' => true,
                    'edit_id' => $InsertId,
                    'idName' => 'id',
                    'table' => 'zselex_service_identifiers',
                    'field_name' => 'name'
                ),
                'identifier' => array(
                    'required' => true,
                    'value' => $formElements ['identifier'],
                    'label' => 'Identifier',
                    'exist' => true,
                    'edit' => true,
                    'edit_id' => $InsertId,
                    'idName' => 'id',
                    'table' => 'zselex_service_identifiers',
                    'field_name' => 'identifier'
                )
            );
            $validationerror    = ZSELEX_Util::validate($validation_rules);

            if ($validationerror !== false) {
                // log the error found if any
                if ($validationerror !== false) {
                    LogUtil::registerError(nl2br($validationerror));
                }
                SessionUtil::setVar('identifieritem', $item);

                $this->redirect(ModUtil::url('ZSELEX', 'admin',
                        'modifyidentifier',
                        array(
                        'id' => $InsertId
                )));
            } else {
                // As we're not previewing the item let's remove it from the session
                SessionUtil::delVar('identifieritem');
            }


            $result = $repo->updateEntity(null,
                'ZSELEX_Entity_ServiceIdentifier', $item,
                array(
                'a.id' => $InsertId
            ));
            if ($result) {
                LogUtil::registerStatus($this->__('Done! Identifier has been updated successfully.'));
                $this->redirect(ModUtil::url('ZSELEX', 'admin',
                        'viewserviceidentifiers'));
            }
        }
        // echo "modifycity";
        $id        = FormUtil::getPassedValue('id',
                isset($args ['id']) ? $args ['id'] : null, 'GETPOST');
        $args      = array(
            'table' => 'zselex_service_identifiers',
            'IdValue' => $id,
            'IdName' => 'id'
        );
        $finalitem = array();
        $sess_item = SessionUtil::getVar('identifieritem');
        // echo "<pre>"; print_r($sess_item); echo "</pre>";
        // Get the news type in the db
        // $item = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $args);
        $item      = $repo->get(array(
            'entity' => 'ZSELEX_Entity_ServiceIdentifier',
            'where' => array(
                'a.id' => $id
            )
        ));
        $languages = ZLanguage::getInstalledLanguages();

        $this->view->assign('languages', $languages);
        if (!empty($sess_item)) {
            $finalitem = $sess_item;
        } else {
            $finalitem = $item;
        }
        // ZSELEX_Util::printarray($finalitem);
        $this->view->assign('itemdb', $item);
        $this->view->assign('item', $finalitem);
        return $this->view->fetch('admin/createidentifier.tpl');
    }

    public function deleteidentifier($args)
    {
        $Id = FormUtil::getPassedValue('id',
                isset($args ['id']) ? $args ['id'] : null, 'REQUEST');

        $user_id = UserUtil::getVar('uid');
        // Validate the essential parameters
        if (empty($Id)) {
            return LogUtil::registerArgsError();
        }

        $repo         = $this->entityManager->getRepository('ZSELEX_Entity_ServiceIdentifier');
        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
        if (empty($confirmation)) {
            // Add ZSELEX type ID
            $this->view->assign('IdValue', $Id);
            $this->view->assign('confirm_title',
                $this->__f('Delete %s item', $this->__('Service Identifier')));
            $this->view->assign('confirm_msg',
                $this->__f('Do you want to delete this %s item',
                    $this->__('Service Identifier')));
            $this->view->assign('shop_id', $shop_id);
            $this->view->assign('IdName', 'id'); // edit id param name
            $this->view->assign('submitFunc', 'deleteidentifier');
            $this->view->assign('cancelFunc', 'viewserviceidentifiers');
            $emptyvar = $this->__('Confirmation prompt'); // just to get the translation out with poedit!!!
            // Return the output that has been generated by this function
            return $this->view->fetch('admin/deletecommon1.tpl');
        }

        $args   = array(
            'table' => 'zselex_service_identifiers',
            'IdValue' => $Id,
            'IdName' => 'id'
        );
        $delete = $repo->deleteEntity(null, 'ZSELEX_Entity_ServiceIdentifier',
            array(
            'a.id' => $Id
        ));
        // if (ModUtil::apiFunc('ZSELEX', 'admin', 'deleteItem', $args)) {
        if ($delete) {
            // Success
            LogUtil::registerStatus($this->__('Done! Service Identifier has been deleted successfully.'));

            // Let any hooks know that we have deleted an item
            $this->notifyHooks(new Zikula_ProcessHook('type.ui_hooks.types.process_delete',
                $Id));
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                    'viewserviceidentifiers'));
    }

    /**
     * Create a new bundle
     * 
     * @param type $args
     * @return HTML
     */
    public function createservicebundle($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $bundleRepo = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $languages  = ZLanguage::getInstalledLanguages();
        $this->view->assign('languages', $languages);
        $func       = "createservicebundle";
        $this->view->assign('func', $func);
        // unset($_SESSION['bundles']);
        // echo "<pre>"; print_r($_SESSION['bundles']); echo "</pre>";
        // echo "<pre>"; print_r($_SESSION['bundlesretain']); echo "</pre>";
        // echo "<pre>"; print_r($languages); echo "</pre>";

        if ($_POST) {
            $formElements = FormUtil::getPassedValue('formElements',
                    isset($args ['formElements']) ? $args ['formElements'] : null,
                    'POST');
            if ($_POST ['action'] == '1') {
                unset($_SESSION ['bundles']);

                // $formElements = FormUtil::getPassedValue('formElements', isset($args['formElements']) ? $args['formElements'] : null, 'POST');
                // echo "<pre>"; print_r($_POST); exit;
                // echo "<pre>"; print_r($formElements); exit;
                // exit;

                $item = array(
                    'bundle_name' => $formElements ['name']
                );

                // $validationerror = ZSELEX_Util::validateServiceIdentifier($item);
                // echo "<pre>"; print_r($item); exit;
                $args = array(
                    'table' => 'zselex_service_bundles',
                    'element' => $item,
                    'Id' => 'bundle_id'
                );
                // Create the zselex type
                // $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $args);
                // $InsertId = DBUtil::getInsertID($args['table'], $args['Id']);
                // if ($result) {
                // LogUtil::registerStatus($this->__('Done! Bundle has been created successfully.'));

                foreach ($formElements ['services'] as $key => $val) {
                    // echo "Service : " . $val . " " . "Qty : " . $formElements['qty'][$key] . "<br>";
                    $bundleitem                  = array(
                        'servicetype' => $val,
                        'servicename' => $formElements ['servicename'] [$key],
                        'plugin_id' => $formElements ['plugin_id'] [$key],
                        'qty' => $formElements ['qty'] [$key],
                        'price' => $formElements ['price'] [$key],
                        'qty_based' => $formElements ['qty_based'] [$key]
                    );
                    $bundleitemargs              = array(
                        'table' => 'zselex_service_bundle_items',
                        'element' => $bundleitem,
                        'Id' => 'id'
                    );
                    $_SESSION ['bundles'] [$val] = $bundleitem;
                    // Create the zselex type
                    // $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $bundleitemargs);
                }

                $this->redirect(ModUtil::url('ZSELEX', 'admin',
                        'createservicebundle#shwbndle'));
                // }
            } elseif ($_POST ['action'] == '2') {
                // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;

                if (!empty($formElements ['type'])) {
                    $type = strtolower($formElements ['type']);
                    $type = str_replace(" ", '-', $type);
                } else {
                    $type = strtolower($formElements ['name']);
                    $type = str_replace(" ", '-', $type);
                }

                $items    = array(
                    'bundle_name' => $formElements ['name'],
                    'type' => $type,
                    'bundle_price' => $formElements ['bundleprice'],
                    'calculated_price' => $formElements ['calcprice'],
                    'bundle_description' => $formElements ['description'],
                    'bundle_type' => isset($formElements ['bundle_type']) ? $formElements ['bundle_type']
                            : '',
                    'demo' => isset($formElements ['demo']) ? $formElements ['demo']
                            : 0,
                    'demoperiod' => isset($formElements ['demoperiod']) ? $formElements ['demoperiod']
                            : 0,
                    'status' => $formElements ['status'],
                    'is_free' => $formElements ['is_free']
                );
                // echo "<pre>"; print_r($items); echo "</pre>"; exit;
                $args     = array(
                    'table' => 'zselex_service_bundles',
                    'element' => $items,
                    'Id' => 'bundle_id'
                );
                // Create the zselex type
                // $results = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $args);
                $results  = $bundleRepo->createBundle($items);
                // $InsertId = DBUtil::getInsertID($args['table'], $args['Id']);
                $InsertId = $results;
                if ($results) {
                    LogUtil::registerStatus($this->__('Done! Bundle has been created successfully.'));

                    foreach ($_SESSION ['bundles'] as $key => $val) {
                        // echo "Service : " . $val . " " . "Qty : " . $formElements['qty'][$key] . "<br>";
                        $bundleitem     = array(
                            'bundle_id' => $InsertId,
                            'servicetype' => $val ['servicetype'],
                            'plugin_id' => $val ['plugin_id'],
                            'service_name' => $val ['servicename'],
                            'price' => $val ['price'],
                            'qty' => $val ['qty'],
                            'qty_based' => $val ['qty_based']
                        );
                        $bundleitemargs = array(
                            'table' => 'zselex_service_bundle_items',
                            'element' => $bundleitem,
                            'Id' => 'id'
                        );
                        // Create the zselex type
                        // $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $bundleitemargs);
                        $result         = $bundleRepo->createBundleItems($bundleitem);
                    }

                    $this->redirect(ModUtil::url('ZSELEX', 'admin',
                            'viewservicebundles'));
                }
            }
        }

        if (!empty($_SESSION ['bundles'])) {
            foreach ($_SESSION ['bundles'] as $val) {
                $quantities [] = $val ['qty'] * $val ['price'];
            }
            $sum = array_sum($quantities);

            $this->view->assign('bundlecount', count($_SESSION ['bundles']));
            $this->view->assign('sum', $sum);
            $this->view->assign('bundlesin', $_SESSION ['bundles']);
        }

        /*
         * $plugins = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements', $args = array(
         * 'table' => 'zselex_plugin',
         * // 'where' => "bundle_id=0 AND type!=''",
         * 'where' => "type!=''",
         * 'orderBy' => 'plugin_name ASC',
         * 'useJoins' => ''
         * ));
         */

        $plugins = $bundleRepo->fetchAll(array(
            'entity' => 'ZSELEX_Entity_Plugin',
            'where' => "a.type!=''",
            'orderby' => "a.plugin_name ASC"
        ));

        $pluginitems = array();
        foreach ($plugins as $key => $val) {
            $pluginitems [$val ['type']] = $val;
        }

        // echo "<pre>"; print_r($pluginitems); echo "</pre>";

        $this->view->assign('plugins', $pluginitems);

        $sess_item = SessionUtil::getVar('identifieritem');
        $this->view->assign('item', $sess_item);
        return $this->view->fetch('admin/createservicebundle.tpl');
    }

    public function viewservicebundles($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        // initialize sort array - used to display sort classes and urls

        unset($_SESSION ['bundles']);
        unset($_SESSION ['bundleinfo']);
        SessionUtil::delVar('bundleinfo');
        unset($_SESSION ['bundlesitems']);

        if ($_POST) {
            if ($_POST ['submit'] == 'SORT') {
                $sort_values = FormUtil::getPassedValue('sortorder', null,
                        'POST');
                // echo "<pre>"; print_r($sort_values); echo "</pre>"; exit;
                $sort_values = array_filter($sort_values);
                foreach ($sort_values as $id => $value) {
                    $item       = array(
                        'bundle_id' => $id,
                        'sort_order' => $value
                    );
                    $updateargs = array(
                        'table' => 'zselex_service_bundles',
                        'IdValue' => $id,
                        'IdName' => 'bundle_id',
                        'element' => $item
                    );

                    $result = ModUtil::apiFunc('ZSELEX', 'admin',
                            'updateElement', $updateargs);
                }
            }
        }

        $user_id = UserUtil::getVar('uid');

        $admin = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN);
        $this->view->assign('admin', $admin);

        $sort   = array();
        $fields = array(
            'bundle_id',
            'view'
        ); // possible sort fields

        $getAllArgs    = array();
        $itemsperpage  = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 25,
                'GETPOST');
        $startnum      = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $status        = FormUtil::getPassedValue('status',
                isset($args ['status']) ? $args ['status'] : null, 'GETPOST');
        $order         = FormUtil::getPassedValue('order',
                isset($args ['order']) ? $args ['order'] : 'bundle_id',
                'GETPOST');
        $original_sdir = FormUtil::getPassedValue('sdir',
                isset($args ['sdir']) ? $args ['sdir'] : 0, 'GETPOST');
        $searchtext    = FormUtil::getPassedValue('searchtext',
                isset($args ['searchtext']) ? $args ['searchtext'] : null,
                'GETPOST');
        $this->view->assign('searchtext', $searchtext);
        $this->view->assign('startnum', $startnum);
        $this->view->assign('itemsperpage', $itemsperpage);
        $this->view->assign('order', $order);
        $this->view->assign('sdir', $original_sdir);

        $aStatus = array(
            'InActive',
            'Active'
        );
        $this->view->assign('aStatus', $aStatus);

        $sdir = $original_sdir ? 0 : 1; // if true change to false, if false change to true
        // change class for selected 'orderby' field to asc/desc
        if ($sdir == 0) {
            $sort ['class'] [$order] = 'z-order-desc';
            $orderdir                = 'DESC';
        }
        if ($sdir == 1) {
            $sort ['class'] [$order] = 'z-order-asc';
            $orderdir                = 'ASC';
        }
        // complete initialization of sort array, adding urls
        foreach ($fields as $field) {
            $sort ['url'] [$field] = ModUtil::url('ZSELEX', 'admin',
                    'viewservicebundles',
                    array(
                    'status' => $status,
                    // 'filtercats_serialized' => serialize($filtercats),
                    'order' => $field,
                    'sdir' => $sdir
            ));
        }
        // echo "<pre>"; print_r($sort); echo "</pre>";
        $this->view->assign('sort', $sort);

        $this->view->assign('filter_active', $status);

        // $sql = " SELECT a.* FROM zselex_type AS a
        // WHERE a.type_id IS NOT NULL ";

        $sql = "";
        if (isset($status) && $status != "") {
            $sql .= " AND status = ".$status;
            $getAllArgs ['where'] ['a.status'] = $status;
        }
        if (isset($searchtext) && $searchtext != "") {
            // $sql .= " AND bundle_name LIKE '%" . DataUtil::formatForStore($searchtext) . "%'";
            // $getAllArgs['like'][] = "a.bundle_name LIKE " . '%' . DataUtil::formatForStore($searchtext) . '%';
            $getAllArgs ['like'] ['a.bundle_name'] = '%'.DataUtil::formatForStore($searchtext).'%';
        }
        if (isset($order) && $order != "") {
            // $sql .= " ORDER BY " . $order . " " . $orderdir;
            if ($order == 'view') {
                $getAllArgs ['orderby'] = "a.sort_order $orderdir";
            } else {
                $getAllArgs ['orderby'] = "a.".$order." ".$orderdir;
            }
        } else {
            // $sql .= " ORDER BY IF(sort_order = 0, 999999999, sort_order) ASC";
            // $getAllArgs['orderby'] = "IF(a.sort_order = 0, 999999999, a.sort_order) ASC";
            $getAllArgs ['orderby'] = "a.sort_order ASC";
        }

        $getAllArgs ['startlimit'] = $startnum;
        $getAllArgs ['offset']     = $itemsperpage;

        // Get all zselex stories
        $getArgs = array(
            'sql' => $sql,
            'startnum' => $startnum,
            'numitems' => $itemsperpage
        );
        // $items = ModUtil::apiFunc('ZSELEX', 'admin', 'getAll', $getArgs);

        $bitem = $this->entityManager->getRepository('ZSELEX_Entity_Bundle')->getServiceBundles($getAllArgs);

        // echo "<pre>"; print_r($bitem); echo "</pre>";
        $items = array();
        /*
         * $itemarray = ModUtil::apiFunc('ZSELEX', 'admin', 'getServiceBundles', $args = array(
         * 'start' => $startnum,
         * 'itemsperpage' => $itemsperpage,
         * 'sql' => $sql
         * ));
         */
        // echo "<pre>"; print_r($itemarray); echo "</pre>";
        // $items = $itemarray['items'];
        // $count = $itemarray['count'];
        // echo "<pre>"; print_r($items); echo "</pre>";
        $items = $bitem ['result'];

        $total_count = $bitem ['count'];

        // Set the possible status for later use
        $itemstatus = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );

        $bundles = array();
        foreach ($items as $item) {
            $item ['bundleitems'] = ModUtil::apiFunc('ZSELEX', 'user',
                    'selectArray',
                    $args                 = array(
                    'table' => 'zselex_service_bundle_items',
                    'where' => array(
                        "bundle_id=".$item ['bundle_id']
                    )
            ));

            $options = array();
            if (SecurityUtil::checkPermission('ZSELEX::',
                    "{$item['cr_uid']}::{$item['bundle_id']}", ACCESS_EDIT)) {
                $options [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'modifybundle',
                        array(
                        'id' => $item ['bundle_id']
                    )),
                    'image' => 'xedit.png',
                    'title' => $this->__('Edit')
                );

                if ((SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['id']}", ACCESS_DELETE)) || SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['type_id']}", ACCESS_ADD)) {
                    $options [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin', 'deletebundle',
                            array(
                            'id' => $item ['bundle_id']
                        )),
                        'image' => '14_layer_deletelayer.png',
                        'title' => $this->__('Delete')
                    );
                }
            }
            $item ['options']  = $options;
            $item ['infuture'] = DateUtil::getDatetimeDiff_AsField($item ['cr_date'],
                    DateUtil::getDatetime(), 6) < 0;
            $bundles []        = $item;
        }
        // echo "<pre>"; print_r($bundles); echo "</pre>";
        // Assign the items to the template
        $this->view->assign('bundles', $bundles);
        $this->view->assign('total_count', $total_count);

        // Assign the current status filter and the possible ones
        $this->view->assign('status', $status);
        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('order', $order);
        // Return the output that has been generated by this function
        return $this->view->fetch('admin/viewservicebundles.tpl');
    }

    /**
     * Modify a bundle
     * 
     * @param array $args
     * @return html
     */
    public function modifybundle($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $bundleRepo = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $id         = FormUtil::getPassedValue('id',
                isset($args ['id']) ? $args ['id'] : null, 'GETPOST');
        // echo $id; exit;
        $func       = "modifybundle";
        $this->view->assign('func', $func);
        $this->view->assign('id', $id);
        unset($_SESSION ['bundles']);

        $languages = ZLanguage::getInstalledLanguages();
        $this->view->assign('languages', $languages);
        if ($_POST) {
            unset($_SESSION ['bundleinfo']);
            $formElements      = FormUtil::getPassedValue('formElements',
                    isset($args ['formElements']) ? $args ['formElements'] : null,
                    'POST');
            $content           = FormUtil::getPassedValue('content',
                    isset($args ['content']) ? $args ['content'] : null, 'POST');
            $content_serialize = serialize($content);
            // echo "<pre>"; print_r($formElements); exit;
            // echo "<pre>"; print_r($content_serialize); echo "</pre>"; exit;
            $newitem           = array(
                'bundle_name' => $formElements ['name'],
                'type' => $formElements ['type'],
                'calculated_price' => $formElements ['calcprice'],
                'bundle_price' => $formElements ['bundleprice'],
                'bundle_description' => $formElements ['description'],
                'content' => $content_serialize,
                'demo' => $formElements ['demo'],
                'demoperiod' => $formElements ['demoperiod'],
                'status' => $formElements ['status']
            );
            SessionUtil::setVar('bundleinfo', $newitem);
            if ($_POST ['action'] == '1') {
                unset($_SESSION ['bundlesitems']);
                // unset($_SESSION['bundlesretain']);
                // $formElements = FormUtil::getPassedValue('formElements', isset($args['formElements']) ? $args['formElements'] : null, 'POST');
                // echo "<pre>"; print_r($_POST); exit;
                // echo "<pre>"; print_r($formElements); exit;
                // exit;
                // $_SESSION['bundlesretain'][] = $formElements;
                // $this->view->assign('bundlesretain', $_SESSION['bundlesretain']);

                $item = array(
                    'bundle_name' => $formElements ['name']
                );

                // $validationerror = ZSELEX_Util::validateServiceIdentifier($item);
                // echo "<pre>"; print_r($item); exit;
                $args = array(
                    'table' => 'zselex_service_bundles',
                    'element' => $item,
                    'Id' => 'bundle_id'
                );
                // Create the zselex type
                // $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $args);
                // $InsertId = DBUtil::getInsertID($args['table'], $args['Id']);
                // if ($result) {
                // LogUtil::registerStatus($this->__('Done! Bundle has been created successfully.'));

                foreach ($formElements ['services'] as $key => $val) {
                    // echo "Service : " . $val . " " . "Qty : " . $formElements['qty'][$key] . "<br>";
                    $bundleitem = array(
                        'servicetype' => $val,
                        'servicename' => $formElements ['servicename'] [$key],
                        'plugin_id' => $formElements ['plugin_id'] [$key],
                        'qty' => $formElements ['qty'] [$key],
                        'qty_based' => $formElements ['qty_based'] [$key],
                        'price' => $formElements ['price'] [$key]
                    );

                    $_SESSION ['bundlesitems'] [$val] = $bundleitem;
                    // Create the zselex type
                    // $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $bundleitemargs);
                }

                $redirecturl = ModUtil::url('ZSELEX', 'admin', 'modifybundle',
                        array(
                        'id' => $id
                ));
                $redirecturl = $redirecturl."#shwbndle";
                $this->redirect($redirecturl);
                // }
            } elseif ($_POST ['action'] == '2') {
                // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;

                if (!empty($formElements ['type'])) {
                    $type = strtolower($formElements ['type']);
                    $type = str_replace(" ", '-', $type);
                } else {
                    $type = strtolower($formElements ['name']);
                    $type = str_replace(" ", '-', $type);
                }

                $pntables = pnDBGetTables();
                $column   = $pntables ['zselex_plugin_column'];

                $items = array(
                    'bundle_id' => $id,
                    'bundle_name' => $formElements ['name'],
                    'type' => $type,
                    'bundle_price' => $formElements ['bundleprice'],
                    'calculated_price' => $formElements ['calcprice'],
                    'bundle_description' => $formElements ['description'],
                    'content' => $content_serialize,
                    'bundle_type' => isset($formElements ['bundle_type']) ? $formElements ['bundle_type']
                            : '',
                    'demo' => isset($formElements ['demo']) ? $formElements ['demo']
                            : 0,
                    'demoperiod' => isset($formElements ['demoperiod']) ? $formElements ['demoperiod']
                            : 0,
                    'status' => $formElements ['status'],
                    'is_free' => $formElements ['is_free']
                );
                // echo "<pre>"; print_r($items); echo "</pre>"; exit;

                $result   = $bundleRepo->updateEntity(array(
                    'entity' => 'ZSELEX_Entity_Bundle',
                    'fields' => $items,
                    'where' => array(
                        'a.bundle_id' => $id
                    )
                ));
                $InsertId = $id;
                // $results = true;
                if ($result) {
                    LogUtil::registerStatus($this->__('Done! Bundle has been saved successfully.'));

                    $bundleRepo->deleteEntity(array(
                        'entity' => "ZSELEX_Entity_BundleItem",
                        'where' => array(
                            'a.bundle' => $id
                        )
                    ));
                    // foreach ($_SESSION['bundles'] as $key => $val) {
                    foreach ($formElements ['services'] as $key => $val) {
                        // echo "Service : " . $val . " " . "Qty : " . $formElements['qty'][$key] . "<br>";

                        $bundleitem = array(
                            'bundle_id' => $InsertId,
                            'servicetype' => $val,
                            'plugin_id' => $formElements ['plugin_id'] [$key],
                            'service_name' => $formElements ['servicename'] [$key],
                            'price' => $formElements ['price'] [$key],
                            'qty' => $formElements ['qty'] [$key],
                            'qty_based' => $formElements ['qty_based'] [$key]
                        );

                        $result = $bundleRepo->createBundleItems($bundleitem);
                    }
                    // exit;
                    $this->redirect(ModUtil::url('ZSELEX', 'admin',
                            'viewservicebundles'));
                }
            }
        } // //////////////////////////////////////////////////////////////////////////////////
        // echo "modifycity";
        // $id = FormUtil::getPassedValue('id', isset($args['id']) ? $args['id'] : null, 'GETPOST');
        $args             = array(
            'table' => 'zselex_service_bundles',
            'IdValue' => $id,
            'IdName' => 'bundle_id'
        );
        $finalitem        = array();
        $bundleservices   = array();
        $finalbundleitems = array();
        // $sess_item = SessionUtil::getVar('identifieritem');

        $sess_item        = SessionUtil::getVar('bundleinfo');
        $sess_bundleitems = $_SESSION ['bundlesitems'];
        // echo "<pre>"; print_r($sess_item); echo "</pre>";
        // Get the news type in the db
        // $item = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $args);
        $item             = $bundleRepo->get(array(
            'entity' => 'ZSELEX_Entity_Bundle',
            'where' => array(
                'a.bundle_id' => $id
            )
        ));
        // echo "<pre>"; print_r($item); echo "</pre>";
        $bundeleid        = $item ['bundle_id'];
        $this->view->assign('bundeleid', $bundeleid);
        $sum              = $item ['calculated_price'];

        $bundleitems = $bundleRepo->getAll(array(
            'entity' => 'ZSELEX_Entity_BundleItem',
            'where' => array(
                'a.bundle' => $id
            ),
            'orderby' => "a.service_name ASC"
        ));
        // echo "<pre>"; print_r($bundleitems); echo "</pre>";
        foreach ($bundleitems as $key => $val) {
            $bundleitem                            = array(
                'servicetype' => $val ['servicetype'],
                'servicename' => $val ['service_name'],
                'qty' => $val ['qty'],
                'price' => $val ['price']
            );
            $bundleservices [$val ['servicetype']] = $bundleitem;
        }

        // echo "<pre>"; print_r($item); echo "</pre>";
        // echo "<pre>"; print_r($bundleitems); echo "</pre>";
        // echo "<pre>"; print_r($_SESSION['bundlesitems']); echo "</pre>";

        if (!empty($sess_item)) {
            $finalitem = $sess_item;
        } else {
            $finalitem = $item;
        }
        $content = unserialize($finalitem ['content']);
        $this->view->assign('content', $content);

        if (!empty($sess_bundleitems)) {
            $finalbundleitems = $sess_bundleitems;
            foreach ($_SESSION ['bundlesitems'] as $val) {
                $quantities [] = $val ['qty'] * $val ['price'];
            }
            $sum = array_sum($quantities);
        } else {
            $finalbundleitems = $bundleservices;
        }

        $finalsum = $sum;

        $this->view->assign('sum', $finalsum);
        $this->view->assign('bundlecount', count($finalbundleitems));
        $this->view->assign('bundlesin', $finalbundleitems);

        // echo "<pre>"; print_r($finalbundleitems); echo "</pre>";

        $plugins = $bundleRepo->fetchAll(array(
            'entity' => 'ZSELEX_Entity_Plugin',
            'where' => "a.type!=''",
            'orderby' => "a.plugin_name ASC"
        ));

        $pluginitems = array();
        foreach ($plugins as $key => $val) {
            $pluginitems [$val ['type']] = $val;
        }

        // echo "<pre>"; print_r($pluginitems); exit;

        $this->view->assign('plugins', $pluginitems);
        // ZSELEX_Util::printarray($finalitem);
        $this->view->assign('itemdb', $item);
        $this->view->assign('item', $finalitem);
        return $this->view->fetch('admin/createservicebundle.tpl');
    }

    /**
     * Delete a bundle
     * 
     * @param type $args
     * @return Redirect
     */
    public function deletebundle($args)
    {
        $Id         = FormUtil::getPassedValue('id',
                isset($args ['id']) ? $args ['id'] : null, 'REQUEST');
        $bundleRepo = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $user_id    = UserUtil::getVar('uid');
        // Validate the essential parameters
        if (empty($Id)) {
            return LogUtil::registerArgsError();
        }

        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
        if (empty($confirmation)) {
            // Add ZSELEX type ID
            $this->view->assign('IdValue', $Id);
            $this->view->assign('confirm_title',
                $this->__f('Delete %s item', $this->__('Service Bundle')));
            $this->view->assign('confirm_msg',
                $this->__f('Do you want to delete this %s item',
                    $this->__('Service Bundle')));
            // $this->view->assign('shop_id', $shop_id);
            $this->view->assign('IdName', 'id'); // edit id param name
            $this->view->assign('submitFunc', 'deletebundle');
            $this->view->assign('cancelFunc', 'viewservicebundles');
            $emptyvar = $this->__('Confirmation prompt'); // just to get the translation out with poedit!!!
            // Return the output that has been generated by this function
            return $this->view->fetch('admin/deletecommon1.tpl');
        }

        $args         = array(
            'table' => 'zselex_service_bundles',
            'IdValue' => $Id,
            'IdName' => 'bundle_id'
        );
        $deleteBundle = $bundleRepo->deleteEntity(array(
            'entity' => 'ZSELEX_Entity_Bundle',
            'where' => array(
                'a.bundle_id' => $Id
            )
        ));
        // if (ModUtil::apiFunc('ZSELEX', 'admin', 'deleteItem', $args)) {
        if ($deleteBundle) {
            /*
             * $itemargs = array(
             * 'table' => 'zselex_service_bundle_items',
             * 'IdValue' => $Id,
             * 'IdName' => 'bundle_id'
             * );
             */
            // ModUtil::apiFunc('ZSELEX', 'admin', 'deleteItem', $itemargs);
            $deleteBundleItem = $bundleRepo->deleteEntity(array(
                'entity' => 'ZSELEX_Entity_BundleItem',
                'where' => array(
                    'a.bundle' => $Id
                )
            ));
            /*
             * $pluginargs = array(
             * 'table' => 'zselex_plugin',
             * 'IdValue' => $Id,
             * 'IdName' => 'bundle_id'
             * );
             * $deleteplugin = ModUtil::apiFunc('ZSELEX', 'admin', 'deleteItem', $pluginargs);
             */
            $deleteplugin     = $bundleRepo->deleteEntity(array(
                'entity' => 'ZSELEX_Entity_Plugin',
                'where' => array(
                    'a.bundle_id' => $Id
                )
            ));
            // Success
            LogUtil::registerStatus($this->__('Done! Service Bundle has been deleted successfully.'));

            // Let any hooks know that we have deleted an item
            $this->notifyHooks(new Zikula_ProcessHook('type.ui_hooks.types.process_delete',
                $Id));
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                    'viewservicebundles'));
    }

    public function shopsummary($args)
    {

        /*
         * OLD shopsummary:
         * $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
         * PageUtil::setVar('title', $this->__('Welcome Back!'));
         * $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
         *
         * if (!(int) ($shop_id)) {
         * return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!', (int) ($shop_id)));
         * }
         *
         *
         * if ($this->shopPermission($shop_id) < 1) {
         * return LogUtil::registerPermissionError();
         * }
         *
         * $joinInfoCity[] = array('join_table' => 'zselex_city',
         * 'join_field' => array('city_name'),
         * 'object_field_name' => array('city_name'),
         * 'compare_field_table' => 'city_id', // main table
         * 'compare_field_join' => 'city_id');
         *
         * $shop_info = ModUtil::apiFunc('ZSELEX', 'user', 'getByJoin', $args = array(
         * 'table' => 'zselex_shop',
         * 'joinInfo' => $joinInfoCity,
         * 'where' => "shop_id=$shop_id",
         * 'fields' => array(
         * 'shop_id', 'shop_name'
         * )
         * ));
         * //$services = ZSELEX_Controller_Admin::purchaseservices($args);
         * //echo "<pre>"; print_r($services); echo "</pre>";
         * //echo "<pre>"; print_r($shop_info); echo "</pre>";
         * $this->view->assign('shop_id', $shop_id)
         * ->assign('shop_info', $shop_info);
         * return $this->view->fetch('admin/shop_summary.tpl');
         */
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        $fullusername = UserUtil::getVar('uname');
        PageUtil::setVar('title', $this->__('Welcome').' '.$fullusername);
        $shop_id      = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $this->reminderNotifications($shop_id);
        /*
         * $joinInfoCity[] = array('join_table' => 'zselex_city',
         * 'join_field' => array('city_name'),
         * 'object_field_name' => array('city_name'),
         * 'compare_field_table' => 'city_id', // main table
         * 'compare_field_join' => 'city_id');
         *
         * $shop_info = ModUtil::apiFunc('ZSELEX', 'user', 'getByJoin', $args = array(
         * 'table' => 'zselex_shop',
         * 'joinInfo' => $joinInfoCity,
         * 'where' => "shop_id=$shop_id",
         * 'fields' => array(
         * 'shop_id', 'shop_name'
         * )
         * ));
         */
        // $services = ZSELEX_Controller_Admin::purchaseservices($args);
        // echo "<pre>"; print_r($services); echo "</pre>";
        // echo "<pre>"; print_r($shop_info); echo "</pre>";
        $current_theme = System::getVar('Default_Theme');
        $this->view->assign('shop_id', $shop_id)->assign('fullusername',
            $fullusername)->assign('current_theme', $current_theme)->assign('shop_info',
            $shop_info);
        return $this->view->fetch('admin/shop_summary.tpl');
    }

    public function shopsites($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        PageUtil::setVar('title', $this->__('My Sites'));
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        if (!(int) ($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)));
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $joinInfoCity [] = array(
            'join_table' => 'zselex_city',
            'join_field' => array(
                'city_name'
            ),
            'object_field_name' => array(
                'city_name'
            ),
            'compare_field_table' => 'city_id', // main table
            'compare_field_join' => 'city_id'
        );

        $shop_info = ModUtil::apiFunc('ZSELEX', 'user', 'getByJoin',
                $args      = array(
                'table' => 'zselex_shop',
                'joinInfo' => $joinInfoCity,
                'where' => "shop_id=$shop_id",
                'fields' => array(
                    'shop_id',
                    'shop_name'
                )
        ));
        // $services = ZSELEX_Controller_Admin::purchaseservices($args);
        // echo "<pre>"; print_r($services); echo "</pre>";
        // echo "<pre>"; print_r($shop_info); echo "</pre>";
        $this->view->assign('shop_id', $shop_id)->assign('shop_info', $shop_info);
        return $this->view->fetch('admin/shop_sites.tpl');
    }

    public function shopservices_backup($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        PageUtil::setVar('title', $this->__('My Services'));
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        if (!(int) ($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)));
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $bundles            = array();
        $joinInfo_bundle [] = array(
            'join_table' => 'zselex_service_bundles',
            'join_field' => array(
                'bundle_id',
                'bundle_name',
                'bundle_name',
                'bundle_type'
            ),
            'object_field_name' => array(
                'bundle_id',
                'bundle_name',
                'bundle_name',
                'bundle_type'
            ),
            'compare_field_table' => 'bundle_id', // main table
            'compare_field_join' => 'bundle_id'
        );

        $getBundles = ModUtil::apiFunc('ZSELEX', 'user', 'getAllByJoin',
                $args       = array(
                'table' => 'zselex_plugin',
                'joinInfo' => $joinInfo_bundle,
                'where' => "tbl.status=1 AND tbl.bundle=1",
                // 'startnum' => $startnum,
                // 'itemsperpage' => $itemsperpage,
                'fields' => $fields,
                'orderby' => 'ORDER BY IF(sort_order = 0, 999999999, sort_order) ASC'
        ));

        $main_bundle_service_exist = ModUtil::apiFunc('ZSELEX', 'admin',
                'mainBundleExist',
                $args                      = array(
                'shop_id' => $shop_id
        ));
        $this->view->assign('main_bundle_service_exist',
            $main_bundle_service_exist);

        // echo "<pre>"; print_r($getBundles); echo "</pre>";

        $joinInfo_bundleItems [] = array(
            'join_table' => 'zselex_plugin',
            'join_field' => array(
                'plugin_name',
                'price',
                'description',
                'is_editable',
                'func_name',
                'content',
                'service_depended',
                'depended_services',
                'shop_depended',
                'depended_shoptypes',
                'sort_order'
            ),
            'object_field_name' => array(
                'service_name',
                'price',
                'description',
                'is_editable',
                'func_name',
                'content',
                'service_depended',
                'depended_services',
                'shop_depended',
                'depended_shoptypes',
                'sort_order'
            ),
            'compare_field_table' => 'plugin_id', // main table
            'compare_field_join' => 'plugin_id'
        );

        $bundle_exist = 0;
        foreach ($getBundles as $key => $val) {

            // echo $val['bundle_id'] . '<br>';

            /*
             * $getBundles[$key]['bundleitems'] = ModUtil::apiFunc('ZSELEX', 'user', 'getAllByJoin', $args = array(
             * 'table' => 'zselex_service_bundle_items',
             * 'joinInfo' => $joinInfo_bundleItems,
             * 'where' => "tbl.bundle_id=$val[bundle_id]",
             * 'orderby' => 'ORDER BY IF(a.sort_order = 0, 999999999, a.sort_order) ASC'
             * )
             * );
             */
            $service_record_exist                = DBUtil::selectObjectCount('zselex_serviceshop',
                    "shop_id=$shop_id AND type='".$val [type]."'");
            $getBundles [$key] ['service_exist'] = $service_record_exist;

            // $bundle_record_exist = DBUtil::selectObjectCount('zselex_serviceshop', "shop_id=$shop_id AND top_bundle=1");
            // $getBundles[$key]['bundle_exist'] = $bundle_record_exist;
            if ($service_record_exist) {
                $bundle_exist ++;
            }

            $getBundles [$key] ['bundlebuy'] = ModUtil::apiFunc('ZSELEX',
                    'admin', 'canBuyStatusBundle',
                    $args                            = array(
                    'shop_id' => $shop_id,
                    'bundleId' => $val ['bundle_id']
            ));
            // echo "<pre>"; print_r($plugins[$key]['bundlebuy']); echo "</pre>";
            $getBundles [$key] ['cantbuy']   = $getBundles [$key] ['bundlebuy'] ['cantbuy'];
            $getBundles [$key] ['msg']       = $getBundles [$key] ['bundlebuy'] ['msg'];

            $sort_order     = $val ['sort_order'] - 1; // for checking the previous bundle
            // echo $sort_order . '<br>';
            $prev_key       = $key - 1;
            $prev_bundle_id = $getBundles [$prev_key] ['bundle_id'];
            // echo $prev_bundle_id . '<br>';

            $button_show_status                = ModUtil::apiFunc('ZSELEX',
                    'admin', 'buttonShowCheck',
                    $args                              = array(
                    'shop_id' => $shop_id,
                    "type" => $val ['type'],
                    "sort_order" => $val ['sort_order']
            ));
            // echo $button_show_status . '<br>';
            $getBundles [$key] ['button_show'] = $button_show_status;

            if ($val ['demo']) {
                $democount = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount',
                        $args      = array(
                        'table' => 'zselex_service_demo',
                        "where" => "shop_id=$shop_id AND type='".$val [type]."'"
                ));
                if ($democount > 0) {
                    // if ($val['qty_based'] == 1) { // if quantity based then check demo validation.
                    $demodays = ModUtil::apiFunc('ZSELEX', 'admin',
                            'demoDateCheck',
                            $args     = array(
                            'type' => $val [type],
                            'plugin_id' => $val [plugin_id],
                            'user_id' => $user_id,
                            'shop_id' => $shop_id,
                            'demo' => 1
                    ));
                    // echo "$serviceType<br>";
                    // echo "<pre>"; print_r($demodays); echo "</pre>";
                    if ($demodays ['demo'] == 0) { // out of demo
                        $getBundles [$key] ['demo_status'] = 0;
                    } else { // running as demo
                        $getBundles [$key] ['demo_status']  = 1;
                        $getBundles [$key] ['demo_running'] = 1;
                    }
                    // } else { // if not quantity based then once used as demo then its demo session is over.
                    // $getBundles[$key]['demo_status'] = 0;
                    // }
                } else { // never used as demo
                    $getBundles [$key] ['demo_status'] = 1;
                }
            } else { // never used as demo
                $getBundles [$key] ['demo_status'] = 0;
            }
        }
        // echo "<pre>"; print_r($getBundles); echo "</pre>";

        $bundles         = array_chunk($getBundles, 3);
        $thislang        = ZLanguage::getLanguageCode();
        $existing_bundle = array();

        if ($bundle_exist) {
            // echo "helooooooo";
            $get     = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                    $getargs = array(
                    'table' => 'zselex_serviceshop',
                    'where' => "shop_id=$shop_id AND top_bundle=1"
            ));

            $existing_bundle = ModUtil::apiFunc('ZSELEX', 'admin',
                    'bundleExpiryCheck',
                    $args            = array(
                    'shop_id' => $shop_id,
                    "type" => $get ['type'],
                    "service_status" => $get ['service_status']
            ));
        }

        // echo "<pre>"; print_r($existing_bundle); echo "</pre>";
        // echo "<pre>"; print_r($get); echo "</pre>";
        // echo "<pre>"; print_r($bundles); echo "</pre>";
        // echo "<pre>"; print_r($shop_info); echo "</pre>";
        $this->view->assign('shop_id', $shop_id)->assign('thislang', $thislang)->assign('bundle_exist',
            $bundle_exist)->assign('existing_bundle', $existing_bundle)->assign('bundles',
            $bundles);
        return $this->view->fetch('admin/shop_services.tpl');
    }

    /**
     * List available services
     *
     * @return array
     */
    public function shopservices($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        PageUtil::setVar('title', $this->__('My Services'));
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $this->reminderNotifications($shop_id);
        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');

        // $Repo = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $bundles = array();

        $getBundles = ModUtil::apiFunc('ZSELEX', 'admin', 'getBundles');
        // echo "<pre>"; print_r($getBundles); echo "</pre>";

        $main_bundle_service_exist = ModUtil::apiFunc('ZSELEX', 'admin',
                'mainBundleExist',
                $args                      = array(
                'shop_id' => $shop_id
        ));
        // echo $main_bundle_service_exist . '<br>';
        $this->view->assign('main_bundle_service_exist',
            $main_bundle_service_exist);

        // echo "<pre>"; print_r($getBundles); echo "</pre>";

        $bundle_exist       = 0;
        // $bundle_exist = DBUtil::selectObjectCount('zselex_serviceshop_bundles', "shop_id=$shop_id AND bundle_type='main'");
        // $bundle_exist = DBUtil::selectObjectCount('zselex_service_demo', "shop_id=$shop_id AND top_bundle=1 AND bundle_type='main'");
        $demoExist          = $repo->getCount(null, 'ZSELEX_Entity_ServiceDemo',
            'demo_id',
            array(
            'a.shop' => $shop_id,
            'a.bundle' => $val ['bundle_id'],
            'a.bundle_type' => 'main'
        ));
        $bundle_exist       = $demoExist;
        $existing_bundle    = array();
        $serviceBundleExist = 0;
        if ($demoExist) {
            $getExistingDemo               = ModUtil::apiFunc('ZSELEX', 'admin',
                    'getExistingBundle',
                    $getargs                       = array(
                    'shop_id' => $shop_id
            ));
            // echo "<pre>"; print_r($get); echo "</pre>";
            $existing_bundle               = ModUtil::apiFunc('ZSELEX', 'admin',
                    'bundleExpiryCheck',
                    $args                          = array(
                    'shop_id' => $shop_id,
                    "bundle_id" => $getExistingDemo ['bundle_id'],
                    "service_status" => $getExistingDemo ['service_status']
            ));
            $existing_bundle ['bundle_id'] = $getExistingDemo ['bundle_id'];
        }
        $serviceBundleExist = $repo->getCount(null,
            'ZSELEX_Entity_ServiceBundle', 'service_bundle_id',
            array(
            'a.shop' => $shop_id,
            'a.bundle_type' => 'main'
        ));
        // echo "serviceBundleExist: ". $serviceBundleExist;
        // echo "<pre>"; print_r($existing_bundle); echo "</pre>";
        if ($existing_bundle ['running'] < 1) {
            $getServiceBundle              = $repo->get(array(
                'entity' => 'ZSELEX_Entity_ServiceBundle',
                'fields' => array(
                    'b.bundle_id'
                ),
                'joins' => array(
                    'JOIN a.bundle b'
                ),
                'where' => array(
                    'a.shop' => $shop_id,
                    'a.bundle_type' => 'main'
                )
            ));
            // echo "<pre>"; print_r($getServiceBundle); echo "</pre>";
            $existing_bundle ['bundle_id'] = $getServiceBundle ['bundle_id'];
        }
        foreach ($getBundles as $key => $val) { //
            $getBundles [$key] ['bundlebuy'] = ModUtil::apiFunc('ZSELEX',
                    'admin', 'canBuyStatusBundle',
                    $args                            = array(
                    'shop_id' => $shop_id,
                    'bundleId' => $val ['bundle_id']
            ));
            // echo "<pre>"; print_r($plugins[$key]['bundlebuy']); echo "</pre>";
            $getBundles [$key] ['cantbuy']   = $getBundles [$key] ['bundlebuy'] ['cantbuy'];
            $getBundles [$key] ['msg']       = $getBundles [$key] ['bundlebuy'] ['msg'];

            $service_record_exist                = $repo->getCount(null,
                'ZSELEX_Entity_ServiceBundle', 'service_bundle_id',
                array(
                'a.shop' => $shop_id,
                'a.bundle' => $val ['bundle_id']
            ));
            // echo $service_record_exist . '<br>';
            $getBundles [$key] ['service_exist'] = $service_record_exist;

            if ($service_record_exist) {
                // $bundle_exist++;

                $existing_service = $repo->get(array(
                    'entity' => 'ZSELEX_Entity_ServiceBundle',
                    'where' => array(
                        'a.shop' => $shop_id,
                        'a.bundle' => $val ['bundle_id']
                    )
                ));
                // echo "<pre>"; print_r($existing_service); echo "</pre>";

                $getBundles [$key] ['service_exist_details'] = $existing_service;
            }

            $sort_order = $val ['sort_order'] - 1; // for checking the previous bundle
            // echo $sort_order . '<br>';
            // $prev_key = $key - 1;
            // $prev_bundle_id = $getBundles[$prev_key]['bundle_id'];
            // echo $prev_bundle_id . '<br>';

            if ($existing_bundle ['running']) { // Demo
                // echo "demo comes here";
                $button_show_status = ModUtil::apiFunc('ZSELEX', 'admin',
                        'buttonShowCheckDemo',
                        $args               = array(
                        'shop_id' => $shop_id,
                        "bundle_id" => $val ['bundle_id'],
                        "sort_order" => $val ['sort_order']
                ));
            } else {
                // echo "upgrade comes here";
                $button_show_status = ModUtil::apiFunc('ZSELEX', 'admin',
                        'buttonShowCheck',
                        $args               = array(
                        'shop_id' => $shop_id,
                        "bundle_id" => $val ['bundle_id'],
                        "sort_order" => $val ['sort_order']
                ));
            }
            // echo $button_show_status . '<br>';
            $getBundles [$key] ['button_show'] = $button_show_status;
            if ($val ['bundle_type'] == 'additional') {
                $getBundles [$key] ['button_show'] = 1;
            }

            if ($val ['demo']) {
                $getBundles [$key] ['demo_status'] = 1;
            } else { // never used as demo
                $getBundles [$key] ['demo_status'] = 0;
            }
        }
        // echo "<pre>"; print_r($getBundles); echo "</pre>";

        $bundles  = array_chunk($getBundles, 3);
        $thislang = ZLanguage::getLanguageCode();

        // echo "<pre>"; print_r($existing_bundle); echo "</pre>";
        // echo "<pre>"; print_r($get); echo "</pre>";
        // echo "<pre>"; print_r($bundles); echo "</pre>";
        // echo "<pre>"; print_r($shop_info); echo "</pre>";
        $this->view->assign('shop_id', $shop_id)->assign('thislang', $thislang)->assign('bundle_exist',
            $bundle_exist)->assign('existing_bundle', $existing_bundle)->assign('serviceBundleExist',
            $serviceBundleExist)->assign('bundles', $bundles);
        return $this->view->fetch('admin/shop_services.tpl');
    }

    public function shopservices1($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        PageUtil::setVar('title', $this->__('My Services'));
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $this->reminderNotifications($shop_id);
        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');

        // $Repo = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $bundles            = array();
        $joinInfo_bundle [] = array(
            'join_table' => 'zselex_service_bundles',
            'join_field' => array(
                'bundle_id',
                'bundle_name',
                'bundle_name',
                'bundle_type'
            ),
            'object_field_name' => array(
                'bundle_id',
                'bundle_name',
                'bundle_name',
                'bundle_type'
            ),
            'compare_field_table' => 'bundle_id', // main table
            'compare_field_join' => 'bundle_id'
        );

        $getBundles = ModUtil::apiFunc('ZSELEX', 'admin', 'getBundles');
        // echo "<pre>"; print_r($getBundles); echo "</pre>";

        $main_bundle_service_exist = ModUtil::apiFunc('ZSELEX', 'admin',
                'mainBundleExist',
                $args                      = array(
                'shop_id' => $shop_id
        ));
        // echo $main_bundle_service_exist . '<br>';
        $this->view->assign('main_bundle_service_exist',
            $main_bundle_service_exist);

        // echo "<pre>"; print_r($getBundles); echo "</pre>";

        $joinInfo_bundleItems [] = array(
            'join_table' => 'zselex_plugin',
            'join_field' => array(
                'plugin_name',
                'price',
                'description',
                'is_editable',
                'func_name',
                'content',
                'service_depended',
                'depended_services',
                'shop_depended',
                'depended_shoptypes',
                'sort_order'
            ),
            'object_field_name' => array(
                'service_name',
                'price',
                'description',
                'is_editable',
                'func_name',
                'content',
                'service_depended',
                'depended_services',
                'shop_depended',
                'depended_shoptypes',
                'sort_order'
            ),
            'compare_field_table' => 'plugin_id', // main table
            'compare_field_join' => 'plugin_id'
        );

        $bundle_exist = 0;
        // $bundle_exist = DBUtil::selectObjectCount('zselex_serviceshop_bundles', "shop_id=$shop_id AND bundle_type='main'");
        $bundle_exist = DBUtil::selectObjectCount('zselex_service_demo',
                "shop_id=$shop_id AND top_bundle=1 AND bundle_type='main'");
        foreach ($getBundles as $key => $val) {
            $getBundles [$key] ['bundlebuy'] = ModUtil::apiFunc('ZSELEX',
                    'admin', 'canBuyStatusBundle',
                    $args                            = array(
                    'shop_id' => $shop_id,
                    'bundleId' => $val ['bundle_id']
            ));
            // echo "<pre>"; print_r($plugins[$key]['bundlebuy']); echo "</pre>";
            $getBundles [$key] ['cantbuy']   = $getBundles [$key] ['bundlebuy'] ['cantbuy'];
            $getBundles [$key] ['msg']       = $getBundles [$key] ['bundlebuy'] ['msg'];

            // $service_record_exist = DBUtil::selectObjectCount('zselex_serviceshop_bundles', "shop_id=$shop_id AND bundle_id='" . $val[bundle_id] . "'");
            $service_record_exist                = $repo->getCount(null,
                'ZSELEX_Entity_ServiceBundle', 'service_bundle_id',
                array(
                'a.shop' => $shop_id,
                'a.bundle' => $val ['bundle_id']
            ));
            // echo $service_record_exist . '<br>';
            $getBundles [$key] ['service_exist'] = $service_record_exist;

            if ($service_record_exist) {
                // $bundle_exist++;
                /*
                 * $existing_service = ModUtil::apiFunc('ZSELEX', 'user', 'get', $getargs = array('table' => 'zselex_serviceshop_bundles',
                 * 'where' => "shop_id=$shop_id AND bundle_id='" . $val[bundle_id] . "'"));
                 */
                $existing_service = $repo->get(array(
                    'entity' => 'ZSELEX_Entity_ServiceBundle',
                    'where' => array(
                        'a.shop' => $shop_id,
                        'a.bundle' => $val ['bundle_id']
                    )
                ));
                // echo "<pre>"; print_r($existing_service); echo "</pre>";

                $getBundles [$key] ['service_exist_details'] = $existing_service;
            }

            $sort_order = $val ['sort_order'] - 1; // for checking the previous bundle
            // echo $sort_order . '<br>';
            // $prev_key = $key - 1;
            // $prev_bundle_id = $getBundles[$prev_key]['bundle_id'];
            // echo $prev_bundle_id . '<br>';

            $button_show_status                = ModUtil::apiFunc('ZSELEX',
                    'admin', 'buttonShowCheck',
                    $args                              = array(
                    'shop_id' => $shop_id,
                    "bundle_id" => $val ['bundle_id'],
                    "sort_order" => $val ['sort_order']
            ));
            // echo $button_show_status . '<br>';
            $getBundles [$key] ['button_show'] = $button_show_status;
            if ($val ['bundle_type'] == 'additional') {
                $getBundles [$key] ['button_show'] = 1;
            }

            if ($val ['demo']) {
                $getBundles [$key] ['demo_status'] = 1;
            } else { // never used as demo
                $getBundles [$key] ['demo_status'] = 0;
            }
        }
        // echo "<pre>"; print_r($getBundles); echo "</pre>";

        $bundles         = array_chunk($getBundles, 3);
        $thislang        = ZLanguage::getLanguageCode();
        $existing_bundle = array();

        if ($bundle_exist) {
            // echo "helooooooo";
            /*
             * $get = ModUtil::apiFunc('ZSELEX', 'user', 'get', $getargs =
             * array('table' => 'zselex_serviceshop',
             * 'where' => "shop_id=$shop_id AND top_bundle=1"));
             */

            $get     = ModUtil::apiFunc('ZSELEX', 'admin', 'getExistingBundle',
                    $getargs = array(
                    'shop_id' => $shop_id
            ));

            // echo "<pre>"; print_r($get); echo "</pre>";
            $existing_bundle               = ModUtil::apiFunc('ZSELEX', 'admin',
                    'bundleExpiryCheck',
                    $args                          = array(
                    'shop_id' => $shop_id,
                    "bundle_id" => $get ['bundle_id'],
                    "service_status" => $get ['service_status']
            ));
            $existing_bundle ['bundle_id'] = $get ['bundle_id'];

            // echo "<pre>"; print_r($existing_bundle); echo "</pre>";
        }

        // echo "<pre>"; print_r($existing_bundle); echo "</pre>";
        // echo "<pre>"; print_r($get); echo "</pre>";
        // echo "<pre>"; print_r($bundles); echo "</pre>";
        // echo "<pre>"; print_r($shop_info); echo "</pre>";
        $this->view->assign('shop_id', $shop_id)->assign('thislang', $thislang)->assign('bundle_exist',
            $bundle_exist)->assign('existing_bundle', $existing_bundle)->assign('bundles',
            $bundles);
        return $this->view->fetch('admin/shop_services.tpl');
    }

    public function quickbuy($args)
    {
        error_reporting(E_ALL);
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        PageUtil::setVar('title', $this->__('Buy Services!'));
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $user_id      = UserUtil::getVar('uid');
        $all_ser      = FormUtil::getPassedValue('all_ser', null, 'REQUEST');
        $itemsperpage = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 4,
                'GETPOST');
        $startnum     = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $thislang     = ZLanguage::getLanguageCode();
        $this->view->assign('thislang', $thislang);
        // echo "Language : " . $thislang;
        $this->view->assign('itemsperpage', $itemsperpage);

        $joinInfo [] = array(
            'join_table' => 'zselex_city',
            'join_field' => array(
                'city_name'
            ),
            'object_field_name' => array(
                'city_name'
            ),
            'compare_field_table' => 'city_id', // main table
            'compare_field_join' => 'city_id'
        );

        $shop_info = ModUtil::apiFunc('ZSELEX', 'user', 'getByJoin',
                $args      = array(
                'table' => 'zselex_shop',
                'joinInfo' => $joinInfo,
                'where' => "shop_id=$shop_id",
                'fields' => array(
                    'shop_id',
                    'shop_name'
                )
        ));
        $this->view->assign('shop_info', $shop_info);
        // echo "<pre>"; print_r($shop_info); echo "</pre>";
        $where     = " status=1";
        $and       = " AND bundle=1";
        if ($all_ser) {
            $and = '';
        }
        $where .= $and;
        // $fields = array('product_id' , 'product_name' , 'prd_description');
        // $fields = array('plugin_id' , 'plugin_name' , 'identifier' , 'type' , 'qty_based' , 'description' , 'content' , 'price' , 'bundle' , 'bundle_id' , 'top_bundle' , 'service_depended' , 'depended_services' , 'shop_depended');
        $getBundles = ModUtil::apiFunc('ZSELEX', 'user', 'getAll',
                $args       = array(
                'table' => 'zselex_plugin',
                'where' => "status=1 AND bundle=1",
                // 'startnum' => $startnum,
                // 'itemsperpage' => $itemsperpage,
                'fields' => $fields,
                'orderby' => 'top_bundle DESC'
        ));

        $joinInfo_bundleItems [] = array(
            'join_table' => 'zselex_plugin',
            'join_field' => array(
                'plugin_name',
                'price',
                'description',
                'is_editable',
                'func_name',
                'content',
                'service_depended',
                'depended_services',
                'shop_depended',
                'depended_shoptypes',
                'sort_order'
            ),
            'object_field_name' => array(
                'service_name',
                'price',
                'description',
                'is_editable',
                'func_name',
                'content',
                'service_depended',
                'depended_services',
                'shop_depended',
                'depended_shoptypes',
                'sort_order'
            ),
            'compare_field_table' => 'plugin_id', // main table
            'compare_field_join' => 'plugin_id'
        );
        foreach ($getBundles as $key => $val) {
            $getBundles [$key] ['bundleitems'] = ModUtil::apiFunc('ZSELEX',
                    'user', 'getAllByJoin',
                    $args                              = array(
                    'table' => 'zselex_service_bundle_items',
                    'joinInfo' => $joinInfo_bundleItems,
                    'where' => "tbl.bundle_id=$val[bundle_id]",
                    'orderby' => 'ORDER BY IF(a.sort_order = 0, 999999999, a.sort_order) ASC'
            ));
        }

        // echo "<pre>"; print_r($getBundles); echo "</pre>";
        $bundles     = array_chunk($getBundles, 3);
        // echo "<pre>"; print_r($bundles); echo "</pre>";
        // $service_fields = array('plugin_id', 'plugin_name', 'price', 'content', 'service_depended', 'depended_services');
        $getServices = ModUtil::apiFunc('ZSELEX', 'user', 'getAll',
                $args        = array(
                'table' => 'zselex_plugin',
                'where' => "status=1 AND bundle!=1",
                // 'startnum' => $startnum,
                // 'itemsperpage' => $itemsperpage,
                'fields' => $service_fields,
                'orderby' => 'ORDER BY IF(sort_order = 0, 999999999, sort_order) ASC'
        ));
        $services    = $services;

        // echo "<pre>"; print_r($getServices); echo "</pre>";
        // var_dump (debug_backtrace()); exit;

        $count = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount',
                $args  = array(
                'table' => 'zselex_plugin',
                'where' => $where
        ));
        $this->view->assign('count', $count);

        // echo "<pre>"; print_r(array_chunk($getServices,2)); echo "</pre>";
        foreach ($getServices as $key => $val) {
            if ($val ['bundle'] == '1') {
                $getServices [$key] ['bundleitems'] = ModUtil::apiFunc('ZSELEX',
                        'user', 'getAll',
                        $args                               = array(
                        'table' => 'zselex_service_bundle_items',
                        'where' => "bundle_id=$val[bundle_id]",
                        'orderby' => 'bundle_id'
                ));
            }

            // //////////////////////DEPENDECY CHECK//////////////////////////////////
            if ($val ['top_bundle'] == 1) { // for bundles followed by its items
                $getServices [$key] ['bundlebuy'] = ModUtil::apiFunc('ZSELEX',
                        'admin', 'canBuyStatusBundle',
                        $args                             = array(
                        'shop_id' => $shop_id,
                        'bundleId' => $val ['bundle_id']
                ));
                // echo "<pre>"; print_r($plugins[$key]['bundlebuy']); echo "</pre>";
                $getServices [$key] ['cantbuy']   = $getServices [$key] ['bundlebuy'] ['cantbuy'];
                $getServices [$key] ['msg']       = $getServices [$key] ['bundlebuy'] ['msg'];
            } else { // for normal services
                if ($val ['service_depended'] == 1 || $val ['shop_depended'] == 1) {
                    $getServices [$key] ['buy']     = ModUtil::apiFunc('ZSELEX',
                            'admin', 'canBuyStatus',
                            $args                           = array(
                            'depended_services' => $val ['depended_services'],
                            'type' => $val ['type'],
                            'shop_id' => $shop_id,
                            'shop_depended' => $val ['shop_depended'],
                            'service_depended' => $val ['service_depended'],
                            'owner_id' => $owner_id
                    ));
                    $getServices [$key] ['cantbuy'] = $getServices [$key] ['buy'] ['cantbuy'];
                    $getServices [$key] ['msg']     = $getServices [$key] ['buy'] ['msg'];
                } else {
                    $getServices [$key] ['cantbuy'] = '0';
                }
            }
            // //////////////////////////DEPENDECY CHECK ENDS////////////////////////////
            $serviceType = $val ['type'];

            if ($val ['demo']) {
                // check if its used as demo ever
                $democount = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount',
                        $args      = array(
                        'table' => 'zselex_service_demo',
                        "where" => "shop_id=$shop_id AND type='".$serviceType."'"
                ));
                // echo "$serviceType :" . $democount . '<br>';
                if ($democount > 0) {
                    if ($val ['qty_based'] == 1) { // if quantity based then check demo validation.
                        $demodays = ModUtil::apiFunc('ZSELEX', 'admin',
                                'demoDateCheck',
                                $args     = array(
                                'type' => $val [type],
                                'plugin_id' => $val [plugin_id],
                                'user_id' => $user_id,
                                'shop_id' => $shop_id,
                                'demo' => self::DEMO
                        ));
                        // echo "$serviceType<br>";
                        // echo "<pre>"; print_r($demodays); echo "</pre>";
                        if ($demodays ['demo'] == 0) { // out of demo
                            $getServices [$key] ['demo_status'] = 0;
                        } else { // running as demo
                            $getServices [$key] ['demo_status'] = 1;
                        }
                    } else { // if not quantity based then once used as demo then its demo session is over.
                        $getServices [$key] ['demo_status'] = 0;
                    }
                } else { // never used as demo
                    $getServices [$key] ['demo_status'] = 1;
                }
            }
        }
        // $getServices = array_chunk($getServices, 2);
        // echo "<pre>"; print_r($getServices); echo "</pre>";

        $this->view->assign('shop_id', $shop_id)->assign('count', $count)->assign('all_ser',
            $all_ser)->assign('bundles', $bundles)->assign('services',
            $getServices);

        return $this->view->fetch('admin/quick_buy.tpl');
    }

    public function editservices($args)
    {
        PageUtil::setVar('title', 'Edit Services - Administartion');
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        $admin    = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN);
        $this->view->assign('admin', $admin);
        $shop_id  = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $this->view->assign('shop_id', $shop_id);
        $thislang = ZLanguage::getLanguageCode();
        $this->view->assign('thislang', $thislang);

        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $this->reminderNotifications($shop_id);

        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');

        // echo "<pre>"; print_r($shop_info); echo "</pre>";

        $shop_info = $repo->get(array(
            'entity' => 'ZSELEX_Entity_Shop',
            'joins' => array(
                'join a.city b'
            ),
            'fields' => array(
                'a.shop_id',
                'a.shop_name',
                'b.city_name'
            ),
            'where' => array(
                'a.shop_id' => $shop_id
            )
        ));
        // echo "<pre>"; print_r($shop_info1); echo "</pre>";
        $this->view->assign('shop_info', $shop_info);

        /*
         * $joinInfo[] = array('join_table' => 'zselex_plugin',
         * 'join_field' => array('plugin_name', 'price', 'description', 'is_editable', 'func_name', 'content', 'service_depended', 'depended_services', 'shop_depended', 'depended_shoptypes', 'sort_order'),
         * 'object_field_name' => array('service_name', 'price', 'description', 'is_editable', 'func_name', 'content', 'service_depended', 'depended_services', 'shop_depended', 'depended_shoptypes', 'sort_order'),
         * 'compare_field_table' => 'plugin_id', // main table
         * 'compare_field_join' => 'plugin_id');
         *
         * $servicePurchased = ModUtil::apiFunc('ZSELEX', 'user', 'getAllByJoin', $args = array(
         * 'table' => 'zselex_serviceshop',
         * 'where' => "shop_id=$shop_id AND tbl.top_bundle=0",
         * 'joinInfo' => $joinInfo,
         * 'orderby' => 'ORDER BY IF(a.sort_order = 0, 999999999, a.sort_order) ASC'
         * ));
         */

        /*
         * $servicePurchased = ModUtil::apiFunc('ZSELEX', 'user', 'selectJoinArray', array(
         * 'table' => 'zselex_serviceshop a',
         * 'joins' => array(
         * "LEFT JOIN zselex_plugin b ON a.plugin_id=b.plugin_id"
         * ),
         * 'where' => array(
         * "a.shop_id=$shop_id", "a.top_bundle=0"
         * ),
         * 'orderby' => "IF(b.sort_order = 0, 999999999, b.sort_order) ASC"
         * ));
         */

        $service_repo     = $this->entityManager->getRepository('ZSELEX_Entity_ServiceShop');
        $servicePurchased = $service_repo->getAll(array(
            'entity' => 'ZSELEX_Entity_ServiceShop',
            'joins' => array(
                'LEFT JOIN a.plugin b'
            ),
            'fields' => array(
                'a.original_quantity',
                'a.quantity',
                'a.availed',
                'a.type',
                'b.plugin_id',
                'b.plugin_name',
                'b.description',
                'b.is_editable',
                'b.func_name'
            ),
            'where' => array(
                'a.shop' => $shop_id
            ),
            'orderby' => 'b.sort_order ASC'
        ));
        // echo "<pre>"; print_r($servicePurchased); echo "</pre>";
        // echo "<pre>"; print_r($servicePurchased1); echo "</pre>";
        /*
         * foreach ($servicePurchased as $key => $val) {
         *
         * if ($val['service_depended'] == 1 || $val['shop_depended'] == 1) {
         * $servicePurchased[$key]['buy'] = ModUtil::apiFunc('ZSELEX', 'admin', 'canBuyStatus', $args = array('depended_services' => $val['depended_services'], 'type' => $val['type'], 'shop_id' => $shop_id, 'shop_depended' => $val['shop_depended'], 'owner_id' => $owner_id));
         * $servicePurchased[$key]['cantbuy'] = $servicePurchased[$key]['buy']['cantbuy'];
         * $servicePurchased[$key]['msg'] = $servicePurchased[$key]['buy']['msg'];
         * } else {
         * $servicePurchased[$key]['cantbuy'] = '0';
         * }
         *
         * ////////////////////////////DEPENDECY CHECK ENDS////////////////////////////
         * }
         */

        // $servicePurchased = array_chunk($servicePurchased, 2);
        // echo "<pre>"; print_r($servicePurchased); echo "</pre>";

        $this->view->assign('servicesPurchased', $servicePurchased);
        return $this->view->fetch('admin/edit_services.tpl');
    }

    public function editservices1($args)
    {
        PageUtil::setVar('title', 'Edit Services - Administartion');
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        $admin    = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN);
        $this->view->assign('admin', $admin);
        $shop_id  = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $this->view->assign('shop_id', $shop_id);
        $thislang = ZLanguage::getLanguageCode();
        $this->view->assign('thislang', $thislang);

        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $joinInfoCity [] = array(
            'join_table' => 'zselex_city',
            'join_field' => array(
                'city_name'
            ),
            'object_field_name' => array(
                'city_name'
            ),
            'compare_field_table' => 'city_id', // main table
            'compare_field_join' => 'city_id'
        );

        $shop_info = ModUtil::apiFunc('ZSELEX', 'user', 'getByJoin',
                $args      = array(
                'table' => 'zselex_shop',
                'joinInfo' => $joinInfoCity,
                'where' => "shop_id=$shop_id",
                'fields' => array(
                    'shop_id',
                    'shop_name'
                )
        ));
        $this->view->assign('shop_info', $shop_info);

        /*
         * $joinInfo[] = array('join_table' => 'zselex_plugin',
         * 'join_field' => array('plugin_name', 'price', 'description', 'is_editable', 'func_name', 'content', 'service_depended', 'depended_services', 'shop_depended', 'depended_shoptypes', 'sort_order'),
         * 'object_field_name' => array('service_name', 'price', 'description', 'is_editable', 'func_name', 'content', 'service_depended', 'depended_services', 'shop_depended', 'depended_shoptypes', 'sort_order'),
         * 'compare_field_table' => 'plugin_id', // main table
         * 'compare_field_join' => 'plugin_id');
         *
         * $servicePurchased = ModUtil::apiFunc('ZSELEX', 'user', 'getAllByJoin', $args = array(
         * 'table' => 'zselex_serviceshop',
         * 'where' => "shop_id=$shop_id AND tbl.top_bundle=0",
         * 'joinInfo' => $joinInfo,
         * 'orderby' => 'ORDER BY IF(a.sort_order = 0, 999999999, a.sort_order) ASC'
         * ));
         */

        $servicePurchased = ModUtil::apiFunc('ZSELEX', 'user',
                'selectJoinArray',
                array(
                'table' => 'zselex_serviceshop a',
                'joins' => array(
                    "LEFT JOIN zselex_plugin b ON a.plugin_id=b.plugin_id"
                ),
                'where' => array(
                    "a.shop_id=$shop_id",
                    "a.top_bundle=0"
                ),
                'orderby' => "IF(b.sort_order = 0, 999999999, b.sort_order) ASC"
        ));
        // echo "<pre>"; print_r($servicePurchased); echo "</pre>";

        foreach ($servicePurchased as $key => $val) {
            if ($val ['service_depended'] == 1 || $val ['shop_depended'] == 1) {
                $servicePurchased [$key] ['buy']     = ModUtil::apiFunc('ZSELEX',
                        'admin', 'canBuyStatus',
                        $args                                = array(
                        'depended_services' => $val ['depended_services'],
                        'type' => $val ['type'],
                        'shop_id' => $shop_id,
                        'shop_depended' => $val ['shop_depended'],
                        'owner_id' => $owner_id
                ));
                $servicePurchased [$key] ['cantbuy'] = $servicePurchased [$key] ['buy'] ['cantbuy'];
                $servicePurchased [$key] ['msg']     = $servicePurchased [$key] ['buy'] ['msg'];
            } else {
                $servicePurchased [$key] ['cantbuy'] = '0';
            }

            // //////////////////////////DEPENDECY CHECK ENDS////////////////////////////
        }

        // $servicePurchased = array_chunk($servicePurchased, 2);
        // echo "<pre>"; print_r($servicePurchased); echo "</pre>";

        $this->view->assign('servicesPurchased', $servicePurchased);
        return $this->view->fetch('admin/edit_services.tpl');
    }

    public function reduceService($args)
    {
        // echo "comes here ssss"; exit;
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        $reduce_qty  = FormUtil::getPassedValue('reduce_qty',
                isset($args ['reduce_qty']) ? $args ['reduce_qty'] : null,
                'GETPOST');
        $servicetype = FormUtil::getPassedValue('servicetype',
                isset($args ['servicetype']) ? $args ['servicetype'] : null,
                'GETPOST');
        $shop_id     = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : null, 'GETPOST');
        // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
        $get         = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                $getargs     = array(
                'table' => 'zselex_serviceshop',
                'where' => "shop_id=$shop_id AND type='".$servicetype."'"
        ));
        // echo "<pre>"; print_r($get); echo "</pre>"; exit;
        $current_qty = $get ['quantity'];
        // echo $current_qty; exit;
        if ($reduce_qty > $current_qty) {
            $reduce_qty = $current_qty;
        }
        $new_qty = $current_qty - $reduce_qty;

        $item     = array(
            'quantity' => $new_qty
        );
        $upd_args = array(
            'table' => 'zselex_serviceshop',
            'items' => $item,
            'where' => array(
                'shop_id' => $shop_id,
                'type' => $servicetype
            )
        );
        // echo "<pre>"; print_r($clear_args); echo "</pre>"; exit;
        $update   = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElementWhere',
                $upd_args);
        LogUtil::registerStatus($this->__("Done! quantity reduced for $servicetype."));

        return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                    'configuredServices',
                    array(
                    'shop_id' => $shop_id
        )));
    }

    public function reduceServiceSimpleView($args)
    {
        // echo "comes here ssss"; exit;
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        $reduce_qty = FormUtil::getPassedValue('reduce_qty',
                isset($args ['reduce_qty']) ? $args ['reduce_qty'] : 1,
                'GETPOST');
        // $servicetype = FormUtil::getPassedValue('servicetype', isset($args['servicetype']) ? $args['servicetype'] : null, 'GETPOST');
        $shop_id    = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : null, 'GETPOST');
        $sid        = FormUtil::getPassedValue('sid',
                isset($args ['sid']) ? $args ['sid'] : null, 'GETPOST');
        // echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;

        $joinInfo []  = array(
            'join_table' => 'zselex_plugin',
            'join_field' => array(
                'plugin_id',
                'plugin_name',
                'description'
            ),
            'object_field_name' => array(
                'plugin_id',
                'service_name',
                'description'
            ),
            'compare_field_table' => 'plugin_id', // main table id
            'compare_field_join' => 'plugin_id'
        );
        $get          = ModUtil::apiFunc('ZSELEX', 'user', 'getByJoin',
                $getargs      = array(
                'table' => 'zselex_serviceshop',
                'where' => "shop_id=$shop_id AND id=$sid",
                'joinInfo' => $joinInfo
        ));
        // echo "<pre>"; print_r($get); echo "</pre>"; exit;
        $current_qty  = $get ['quantity'];
        $service_name = $get ['service_name'];
        $servicetype  = $get ['type'];
        // echo $current_qty; exit;
        if ($reduce_qty > $current_qty) {
            $reduce_qty = $current_qty;
        }
        $new_qty = $current_qty - $reduce_qty;

        $item     = array(
            'quantity' => $new_qty
        );
        $upd_args = array(
            'table' => 'zselex_serviceshop',
            'items' => $item,
            'where' => array(
                'shop_id' => $shop_id,
                'type' => $servicetype
            )
        );
        // echo "<pre>"; print_r($clear_args); echo "</pre>"; exit;
        $update   = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElementWhere',
                $upd_args);
        LogUtil::registerStatus($this->__("Done! quantity reduced for $service_name."));

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'editservices',
                    array(
                    'shop_id' => $shop_id
        )));
    }

    public function cancelReduced($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        $sid      = FormUtil::getPassedValue('sid',
                isset($args ['sid']) ? $args ['sid'] : null, 'GETPOST');
        $shop_id  = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : null, 'GETPOST');
        $src      = FormUtil::getPassedValue('src',
                isset($args ['src']) ? $args ['src'] : null, 'GETPOST');
        // echo $src; exit;
        $get      = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                $getargs  = array(
                'table' => 'zselex_serviceshop',
                'where' => "shop_id=$shop_id AND id='".$sid."'"
        ));
        $orig_qty = $get ['original_quantity'];

        $item     = array(
            'quantity' => $orig_qty
        );
        $upd_args = array(
            'table' => 'zselex_serviceshop',
            'items' => $item,
            'where' => array(
                'shop_id' => $shop_id,
                'id' => $sid
            )
        );
        // echo "<pre>"; print_r($clear_args); echo "</pre>"; exit;
        $update   = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElementWhere',
                $upd_args);
        LogUtil::registerStatus($this->__("Done! original quantity retained"));

        if ($src == 'simple') {
            return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                        'editservices',
                        array(
                        'shop_id' => $shop_id
            )));
        } else {
            return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                        'configuredServices',
                        array(
                        'shop_id' => $shop_id
            )));
        }
    }

    public function deleteConfiguredService($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $shop_id = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : null, 'GETPOST');
        $sid     = FormUtil::getPassedValue('sid',
                isset($args ['sid']) ? $args ['sid'] : null, 'GETPOST');
        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }
        if (!(int) ($sid)) {
            return LogUtil::registerError($this->__f('Error! The ID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($sid)));
        }
        $get     = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                $getargs = array(
                'table' => 'zselex_serviceshop',
                'where' => "shop_id=$shop_id AND id='".$sid."'"
        ));

        // echo "<pre>"; print_r($get); echo "</pre>"; exit;
        // service_status
        if ($get ['service_status'] == 1) {
            DBUtil::deleteWhere('zselex_service_demo',
                "shop_id=$shop_id AND plugin_id=$get[plugin_id] AND type='".$get [type]."'");
        }
        if (DBUtil::deleteWhere('zselex_serviceshop', "id=$sid")) {
            LogUtil::registerStatus($this->__('Done! Service Deleted Successfully.'));
        }
        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'editservices',
                    array(
                    'shop_id' => $shop_id
        )));
    }

    public function buybundle()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        PageUtil::setVar('title', 'Buy Bundle - Administration');
        $bundle_id = FormUtil::getPassedValue('bundle_id',
                isset($args ['bundle_id']) ? $args ['bundle_id'] : null,
                'GETPOST');
        $shop_id   = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : null, 'GETPOST');

        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $repo        = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $user_id     = UserUtil::getVar('uid');
        $demo_status = 1;
        $thislang    = ZLanguage::getLanguageCode();
        $this->view->assign('thislang', $thislang);

        if (!(int) ($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)));
        }
        if (!(int) ($bundle_id)) {
            return LogUtil::registerError($this->__f('Error! The BundleID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($bundle_id)));
        }


        $bundle = $repo->get(array(
            'entity' => 'ZSELEX_Entity_Bundle',
            'field' => array(
                'a.bundle_id',
                'a.bundle_name',
                'a.type',
                'a.bundle_price',
                'a.calculated_price',
                'a.bundle_description',
                'a.content',
                'a.bundle_type',
                'a.demo',
                'a.demoperiod',
                'a.sort_order',
                'a.status',
                'a.plugin_id',
                'a.service_name',
                'a.description'
            ),
            'where' => array(
                'a.bundle_id' => $bundle_id
            )
        ));
        // echo "<pre>"; print_r($bundle); echo "</pre>";



        $bundle_items = $repo->getAll(array(
            'entity' => 'ZSELEX_Entity_BundleItem',
            'fields' => array(
                'a.id',
                'b.bundle_id',
                'a.service_name',
                'a.servicetype',
                'c.plugin_id',
                'a.price',
                'a.qty',
                'a.qty_based',
                'c.content'
            ),
            'joins' => array(
                'JOIN a.bundle b',
                'LEFT JOIN a.plugin c'
            ),
            'where' => array(
                'a.bundle' => $bundle_id
            ),
            'orderby' => "b.sort_order ASC",
            'groupby' => 'a.id'
        ));

        // foreach ($getServices as $key => $val) {
        // //////////////////////DEPENDECY CHECK//////////////////////////////////
        $bundle ['bundlebuy'] = ModUtil::apiFunc('ZSELEX', 'admin',
                'canBuyStatusBundle',
                $args                 = array(
                'shop_id' => $shop_id,
                'bundleId' => $bundle ['bundle_id']
        ));
        // echo "<pre>"; print_r($bundle['bundlebuy']); echo "</pre>";
        $bundle ['cantbuy']   = $bundle ['bundlebuy'] ['cantbuy'];
        $bundle ['msg']       = $bundle ['bundlebuy'] ['msg'];

        // //////////////////////////DEPENDECY CHECK ENDS////////////////////////////
        // $bundle['bundle_exist'] = DBUtil::selectObjectCount('zselex_service_demo', "shop_id=$shop_id AND top_bundle=1 AND bundle_type='main'");
        $bundle ['bundle_exist'] = $repo->getCount(null,
            'ZSELEX_Entity_ServiceDemo', 'demo_id',
            array(
            'a.shop' => $shop_id,
            'a.top_bundle' => 1,
            'a.bundle_type' => 'main'
        ));
        $serviceType             = $bundle ['type'];

        // $service_record_exist = DBUtil::selectObjectCount('zselex_serviceshop_bundles', "shop_id=$shop_id AND bundle_id='" . $bundle_id . "'");
        $service_record_exist     = $repo->getCount(null,
            'ZSELEX_Entity_ServiceBundle', 'service_bundle_id',
            array(
            'a.shop' => $shop_id,
            'a.bundle' => $bundle_id
        ));
        // echo $service_record_exist;
        $bundle ['service_exist'] = $service_record_exist;

        // $bundle_record_exist = DBUtil::selectObjectCount('zselex_serviceshop', "shop_id=$shop_id AND top_bundle=1");
        // $getBundles[$key]['bundle_exist'] = $bundle_record_exist;
        if ($service_record_exist) {
            // $bundle_exist++;
            /*
             * $existing_service = ModUtil::apiFunc('ZSELEX', 'user', 'get', $getargs = array('table' => 'zselex_serviceshop_bundles',
             * 'where' => "shop_id=$shop_id AND bundle_id='" . $bundle_id . "'"));
             */
            $existing_service                 = $repo->get(array(
                'entity' => 'ZSELEX_Entity_ServiceBundle',
                'where' => array(
                    'a.bundle' => $bundle_id,
                    'a.shop' => $shop_id
                )
            ));
            // echo "<pre>"; print_r($existing_service); echo "</pre>";
            $bundle ['service_exist_details'] = $existing_service;
        }

        $button_show_status     = ModUtil::apiFunc('ZSELEX', 'admin',
                'buttonShowCheck',
                $args                   = array(
                'shop_id' => $shop_id,
                "bundle_id" => $bundle_id,
                "sort_order" => $bundle ['sort_order']
        ));
        $bundle ['button_show'] = $button_show_status;
        if ($bundle ['bundle_type'] == 'additional') {
            $bundle ['button_show'] = 1;
        }

        if ($bundle ['bundle_exist']) {
            $get     = ModUtil::apiFunc('ZSELEX', 'admin', 'getExistingBundle',
                    $getargs = array(
                    'shop_id' => $shop_id
            ));

            // echo "<pre>"; print_r($get); echo "</pre>";
            $existing_bundle = ModUtil::apiFunc('ZSELEX', 'admin',
                    'bundleExpiryCheck',
                    $args            = array(
                    'shop_id' => $shop_id,
                    "bundle_id" => $get ['bundle_id'],
                    "service_status" => $get ['service_status']
            ));
        }

        $bundle ['existing_bundle'] = $existing_bundle;

        // check if its used as demo ever
        /*
         * $democount = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $args = array(
         * 'table' => 'zselex_service_demo',
         * "where" => "shop_id=$shop_id AND type='" . $serviceType . "'"
         * ));
         * if ($democount > 0) {
         * $bundle['demo_status'] = 0;
         * } else { // never used as demo
         * $bundle['demo_status'] = 1;
         * }
         */
        // }
        // echo "<pre>"; print_r($bundle); echo "</pre>";
        //echo "<pre>"; print_r($bundle_items); echo "</pre>";

        $bundle ['demo_status'] = $demo_status;
        $this->view->assign('bundle', $bundle);
        $this->view->assign('bundle_items', $bundle_items);
        return $this->view->fetch('admin/buybundle.tpl');
    }

    public function deleteExtraBanner($args)
    {
        error_reporting('E_ALL');
        $arra         = array();
        $shop_id      = $args ['shop_id'];
        $ownername    = $args ['ownername'];
        $servicecheck = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow',
                $args         = array(
                'table' => 'zselex_serviceshop',
                'where' => array(
                    "shop_id=$shop_id",
                    "type='minisitebanner'"
                )
        ));
        if ($servicecheck ['availed'] > $servicecheck ['quantity']) {
            $service_used_extra  = $servicecheck ['availed'] - $servicecheck ['quantity'];
            $original_used_extra = $servicecheck ['availed'] - $service_used_extra;
            // echo $original_used_extra;
            $service_extra       = ModUtil::apiFunc('ZSELEX', 'user',
                    'selectArray',
                    $args                = array(
                    'table' => 'zselex_shop_banner',
                    'where' => array(
                        "shop_id=$shop_id"
                    ),
                    'orderby' => 'shop_banner_id DESC',
                    'limit' => "LIMIT 0 , $service_used_extra"
            ));

            // echo "<pre>"; print_r($service_extra); echo "</pre>";

            foreach ($service_extra as $extra_item) {
                unlink('zselexdata/'.$ownername.'/banner/'.$extra_item [banner_image]);
                unlink('zselexdata/'.$ownername.'/banner/resized/'.$extra_item [banner_image]);

                $where = "shop_banner_id=$extra_item[shop_banner_id]";
                DBUtil::deleteWhere('zselex_shop_banner', $where);

                // echo $extra_item['pdf_name'] . '<br>';
            }
            $upd_ser_args   = array(
                'table' => 'zselex_serviceshop',
                'items' => array(
                    'availed' => $original_used_extra
                ),
                'where' => array(
                    'shop_id' => $shop_id,
                    'type' => 'minisitebanner'
                )
            );
            $update_service = ModUtil::apiFunc('ZSELEX', 'admin',
                    'updateElementWhere', $upd_ser_args);
        }
        return true;
    }

    public function viewminisitebanner($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        // initialize sort array - used to display sort classes and urls
        // SessionUtil::delVar('identifieritem');
        $user_id = UserUtil::getVar('uid');
        $shop_id = FormUtil::getPassedValue('shop_id', '', 'REQUEST');

        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        // $args_del_extra_service = array('ownername' => $this->ownername, 'shop_id' => $shop_id);
        // $check = $this->deleteExtraBanner($args_del_extra_service);

        $serviceargs       = array(
            'shop_id' => $shop_id,
            'user_id' => $user_id,
            'type' => 'minisitebanner'
        );
        $servicePermission = $this->servicePermission($serviceargs);
        // echo $servicecount += $servicePermission['perm'];

        if ($servicePermission ['perm'] < 1) {
            $message = $servicePermission ['message'];
            $error ++;
            LogUtil::registerError(nl2br($message));
        }

        if ($this->serviceDisabled('minisitebanner') < 1) {
            $serviceDisabled = $this->serviceDisabled('minisitebanner');
            if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $servicedisable = true;
                $error ++;
                $disable        = "disabled";
            }
            $message = $this->__("This Service is currently disabled");
            LogUtil::registerError(nl2br($message));
        }
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            $expired = $servicePermission ['expired'];
        }

        $ownerName  = $this->ownername;
        // $uploadpath = $_SERVER['DOCUMENT_ROOT'] . "/zselexdata/" . $ownerName . "/";
        $uploadpath = "zselexdata/".$ownerName."/";
        if ($_SERVER ['SERVER_NAME'] == 'localhost') {
            $uploadpath = "zselexdata/".$ownerName."/";
        }
        $this->view->assign('uploadpath', $uploadpath);

        $this->view->assign('servicecount', $servicePermission ['perm']);
        $this->view->assign('servicedisable', $servicedisable);
        $this->view->assign('expired', $expired);
        $this->view->assign('disable', $disable);
        $this->view->assign('serviceerror', $error);
        $this->view->assign('message', $message);

        $admin = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN);
        $this->view->assign('admin', $admin);

        $bannerCount = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount',
                $args        = array(
                'table' => 'zselex_shop_banner',
                'where' => "shop_id=$shop_id"
        ));

        $this->view->assign('bannerExist', $bannerCount);

        $aStatus = array(
            'InActive',
            'Active'
        );
        $this->view->assign('aStatus', $aStatus);

        // complete initialization of sort array, adding urls
        // echo "<pre>"; print_r($sort); echo "</pre>";
        $this->view->assign('sort', $sort);
        $this->view->assign('ownername', $this->ownername);

        // $sql = " SELECT a.* FROM zselex_type AS a
        // WHERE a.type_id IS NOT NULL ";
        // $items = ModUtil::apiFunc('ZSELEX', 'admin', 'getAll', $getArgs);

        $items     = array();
        $itemarray = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                $args      = array(
                'table' => 'zselex_shop_banner',
                'where' => "shop_id=$shop_id"
        ));
        // echo "<pre>"; print_r($itemarray); echo "</pre>";
        $item      = $itemarray;
        $count     = $bannerCount;

        // Set the possible status for later use
        $itemstatus = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );

        $identifieritems = array();
        // foreach ($items as $item) {
        $options         = array();
        if (SecurityUtil::checkPermission('ZSELEX::',
                "{$item['cr_uid']}::{$item['id']}", ACCESS_EDIT)) {
            /*
             * $options[] = array(
             * 'url' => ModUtil::url('ZSELEX', 'admin', 'modifybanner', array(
             * 'id' => $item['shop_banner_id']
             * )),
             * 'image' => 'xedit.png',
             * 'title' => $this->__('Edit')
             * );
             */

            if ((SecurityUtil::checkPermission('ZSELEX::',
                    "{$item['cr_uid']}::{$item['id']}", ACCESS_DELETE)) || SecurityUtil::checkPermission('ZSELEX::',
                    "{$item['cr_uid']}::{$item['type_id']}", ACCESS_ADD)) {
                $options [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'deletebanner',
                        array(
                        'id' => $item ['shop_banner_id'],
                        'shop_id' => $shop_id
                    )),
                    'image' => '14_layer_deletelayer.png',
                    'title' => $this->__('Delete')
                );
            }
        }
        $item ['options']  = $options;
        // echo "<pre>"; print_r($item); echo "</pre>";
        $item ['infuture'] = DateUtil::getDatetimeDiff_AsField($item ['cr_date'],
                DateUtil::getDatetime(), 6) < 0;
        $banner            = $item;

        // }
        // Assign the items to the template
        $this->view->assign('count', $count);
        $this->view->assign('banner', $banner);

        // Assign the current status filter and the possible ones
        $this->view->assign('status', $status);
        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('order', $order);
        // Return the output that has been generated by this function
        return $this->view->fetch('admin/view_shop_banner.tpl');
    }

    public function uploadImages3($filename, $destination)
    {

        // echo $width; exit;
        $allowedExtensions = array(
            'png',
            'jpg',
            'gif',
            'jpeg'
        );
        $ex                = end(explode(".", $name));
        if (!in_array($ex, $allowedExtensions)) {
            // return LogUtil::registerError($this->__f('Error! Invalid file type: %1$s', $ex));
        }
        $modvariable = $this->getVars();

        $fullWidth  = !empty($modvariable ['fullimagewidth']) ? $modvariable ['fullimagewidth']
                : 1024;
        $fullHeight = !empty($modvariable ['fullimageheight']) ? $modvariable ['fullimageheight']
                : 768;

        $medWidth  = !empty($modvariable ['medimagewidth']) ? $modvariable ['medimagewidth']
                : 800;
        $medHeight = !empty($modvariable ['medimageheight']) ? $modvariable ['medimageheight']
                : 500;

        $thumbWidth  = !empty($modvariable ['thumbimagewidth']) ? $modvariable ['thumbimagewidth']
                : 298;
        $thumbHeight = !empty($modvariable ['thumbimageheight']) ? $modvariable ['thumbimageheight']
                : 133;

        require_once('modules/ZSELEX/lib/vendor/ImageResize.php');
        $resizeObj = new ImageResize($destination.$filename);
        $resizeObj->resizeImage($fullWidth, $fullHeight);
        $resizeObj->saveImage($destination.'fullsize/'.$filename, 100);

        $resizeObj->resizeImage($medWidth, $medHeight);
        $resizeObj->saveImage($destination.'medium/'.$filename, 100);

        $resizeObj->resizeImage($thumbWidth, $thumbHeight);
        $resizeObj->saveImage($destination.'thumb/'.$filename, 100);

        unlink($destination.$filename);

        return true;
    }

    public function uploadEmployeeImages($filename, $destination)
    {

        // echo $width; exit;
        $allowedExtensions = array(
            'png',
            'jpg',
            'gif',
            'jpeg'
        );
        $ex                = end(explode(".", $name));
        if (!in_array($ex, $allowedExtensions)) {
            // return LogUtil::registerError($this->__f('Error! Invalid file type: %1$s', $ex));
        }
        $modvariable = $this->getVars();

        $fullWidth  = !empty($modvariable ['fullimagewidth']) ? $modvariable ['fullimagewidth']
                : 1024;
        $fullHeight = !empty($modvariable ['fullimageheight']) ? $modvariable ['fullimageheight']
                : 768;

        $medWidth  = !empty($modvariable ['medimagewidth']) ? $modvariable ['medimagewidth']
                : 800;
        $medHeight = !empty($modvariable ['medimageheight']) ? $modvariable ['medimageheight']
                : 500;

        $thumbWidth  = !empty($modvariable ['thumbimagewidth']) ? $modvariable ['thumbimagewidth']
                : 298;
        $thumbHeight = !empty($modvariable ['thumbimageheight']) ? $modvariable ['thumbimageheight']
                : 133;

        require_once('modules/ZSELEX/lib/vendor/ImageResize.php');
        $resizeObj = new ImageResize($destination.$filename);
        $resizeObj->resizeImage($fullWidth, $fullHeight);
        $resizeObj->saveImage($destination.'fullsize/'.$filename, 100);

        $resizeObj->resizeImage($medWidth, $medHeight);
        $resizeObj->saveImage($destination.'medium/'.$filename, 100);

        $resizeObj->resizeImage($thumbWidth, $thumbHeight);
        $resizeObj->saveImage($destination.'thumb/'.$filename, 100);

        unlink($destination.$filename);

        return true;
    }

    public function deleteExtraEmployeeServices($args)
    {
        // error_reporting('E_ALL');
        $arra         = array();
        $shop_id      = $args ['shop_id'];
        $ownername    = $args ['ownername'];
        $servicecheck = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow',
                $args         = array(
                'table' => 'zselex_serviceshop',
                'where' => array(
                    "shop_id=$shop_id",
                    "type='employees'"
                )
        ));
        if ($servicecheck ['availed'] > $servicecheck ['quantity']) {
            $service_used_extra  = $servicecheck ['availed'] - $servicecheck ['quantity'];
            $original_used_extra = $servicecheck ['availed'] - $service_used_extra;
            // echo $original_used_extra;
            $service_extra       = ModUtil::apiFunc('ZSELEX', 'user',
                    'selectArray',
                    $args                = array(
                    'table' => 'zselex_shop_employees',
                    'where' => array(
                        "shop_id=$shop_id"
                    ),
                    'orderby' => 'emp_id DESC',
                    'limit' => "LIMIT 0 , $service_used_extra"
            ));

            // echo "<pre>"; print_r($service_extra); echo "</pre>";

            foreach ($service_extra as $extra_item) {
                unlink('zselexdata/'.$ownername.'/employees/fullsize/'.$extra_item [emp_image]);
                unlink('zselexdata/'.$ownername.'/employees/medium/'.$extra_item [emp_image]);
                unlink('zselexdata/'.$ownername.'/employees/thumb/'.$extra_item [emp_image]);
                $where = "emp_id=$extra_item[emp_id]";
                DBUtil::deleteWhere('zselex_shop_employees', $where);

                // echo $extra_item['pdf_name'] . '<br>';
            }
            $upd_ser_args   = array(
                'table' => 'zselex_serviceshop',
                'items' => array(
                    'availed' => $original_used_extra
                ),
                'where' => array(
                    'shop_id' => $shop_id,
                    'type' => 'employees'
                )
            );
            $update_service = ModUtil::apiFunc('ZSELEX', 'admin',
                    'updateElementWhere', $upd_ser_args);
        }
        return true;
    }

    public function uploadBannerTest($file, $destination)
    {
        $ownername = $this->ownername;
        require_once('modules/ZSELEX/lib/vendor/R2resize.php');
        // echo $ownername; exit;
        $name      = $file ['name'];
        $temp_name = $file ['tmp_name'];
        // echo $name; exit;
        // Check file extension
        list($width, $height, $type, $attr) = getimagesize($temp_name);
        // echo $width; exit;
        // echo $width; exit;

        $allowedExtensions = array(
            'png',
            'jpg',
            'gif',
            'jpeg',
            'JPEG',
            'JPG'
        );
        $ex                = end(explode(".", $name));
        if (!in_array($ex, $allowedExtensions)) {
            return LogUtil::registerError($this->__f('Error! Invalid file type: %1$s',
                        $ex));
        }

        // Check file size
        if ($size >= 16000000) {
            return LogUtil::registerError($this->__('Error! Your file is too big. The limit is 14 MB.'));
        }

        $newNme    = $file ['newName'];
        $code      = self::doUploadFile($file, $destination);
        // LogUtil::registerError(FileUtil::uploadErrorMsg($code));
        // *** 1) Initialise / load image
        $resizeObj = new R2resize();
        // *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
        // $resizeObj->resizeImage(1350, 320, 'crop');
        $resizeObj->resize($destination.'/'.$newNme, 1350, 320,
            $destination.'/resized/'.$newNme, $r, $g, $b);
        // $resizeObj->resizeImage($width, 320, 'crop');
        // *** 3) Save image

        unlink('zselexdata/'.$ownername.'/banner/'.$newNme);

        return true;
    }

    public function uploadBanner1($file, $destination)
    {
        $ownername = $this->ownername;
        require_once('modules/ZSELEX/lib/vendor/ImageResize.php');
        // echo $destination; exit;
        $name      = $file ['name'];
        $temp_name = $file ['tmp_name'];
        // echo $name; exit;
        // Check file extension
        list($width, $height, $type, $attr) = getimagesize($temp_name);
        // echo $width; exit;
        // echo $width; exit;

        $allowedExtensions = array(
            'png',
            'jpg',
            'gif',
            'jpeg',
            'JPEG',
            'JPG'
        );
        $ex                = end(explode(".", $name));
        if (!in_array($ex, $allowedExtensions)) {
            return LogUtil::registerError($this->__f('Error! Invalid file type: %1$s',
                        $ex));
        }

        // Check file size
        if ($size >= 16000000) {
            return LogUtil::registerError($this->__('Error! Your file is too big. The limit is 14 MB.'));
        }

        $newNme    = $file ['newName'];
        $code      = self::doUploadFile($file, $destination);
        // LogUtil::registerError(FileUtil::uploadErrorMsg($code));
        // *** 1) Initialise / load image
        $resizeObj = new ImageResize($destination.'/'.$newNme);

        // *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
        $resizeObj->resizeImage(1350, 320, 'crop');
        // $resizeObj->resizeImage($width, 320, 'crop');
        // *** 3) Save image
        $resizeObj->saveImage($destination.'/resized/'.$newNme, 100);
        unlink('zselexdata/'.$ownername.'/banner/'.$newNme);

        return true;
    }

    public function uploadBanner($file, $destination)
    {
        $ownername = $this->ownername;
        require_once('modules/ZSELEX/lib/vendor/ImageResize.php');
        // echo $destination; exit;
        $name      = $file ['name'];
        $temp_name = $file ['tmp_name'];
        // echo $name; exit;
        // Check file extension
        list($width, $height, $type, $attr) = getimagesize($temp_name);
        // echo $width; exit;
        // echo $width; exit;

        $allowedExtensions = array(
            'png',
            'jpg',
            'gif',
            'jpeg',
            'JPEG',
            'JPG'
        );
        $ex                = end(explode(".", $name));
        if (!in_array($ex, $allowedExtensions)) {
            return LogUtil::registerError($this->__f('Error! Invalid file type: %1$s',
                        $ex));
        }

        // Check file size
        if ($size >= 16000000) {
            return LogUtil::registerError($this->__('Error! Your file is too big. The limit is 14 MB.'));
        }

        $newNme       = $file ['newName'];
        $code         = self::doUploadFile($file, $destination);
        $resizeBanner = ModUtil::apiFunc('ZSELEX', 'admin', 'bannerResize',
                $args         = array(
                'filename' => $newNme,
                'destination' => $destination.'/'
        ));
        unlink('zselexdata/'.$ownername.'/banner/'.$newNme);

        return true;
    }

    public function createbanner($args)
    {
        $shop_id = FormUtil::getPassedValue('shop_id', '', 'REQUEST');
        $admin   = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN);
        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $ownername = $this->ownername;

        $serviceargs       = array(
            'shop_id' => $shop_id,
            'user_id' => $user_id,
            'type' => 'minisitebanner'
        );
        $servicePermission = $this->servicePermission($serviceargs);

        if ($servicePermission ['perm'] < 1) {
            return LogUtil::registerError($this->__($servicePermission ['message']));
        }

        if ($this->serviceDisabled('minisitebanner') < 1) {
            if (!$admin) {
                return LogUtil::registerError($this->__('This Service has been temporarily disabled!'));
            }
        }

        $diskquota = ModUtil::apiFunc('ZSELEX', 'admin', 'checkDiskquota',
                $args      = array(
                'shop_id' => $shop_id
        ));
        // echo "<pre>"; print_r($diskquota); echo "</pre>";

        if ($diskquota ['count'] < 1) {
            return LogUtil::registerError($diskquota ['message']);
        } elseif ($diskquota ['limitover'] < 1) {
            return LogUtil::registerError($diskquota ['message']);
        }

        if ($_POST == true) {
            $this->checkCsrfToken();
            $formElements = FormUtil::getPassedValue('formElements',
                    isset($args ['formElements']) ? $args ['formElements'] : null,
                    'POST');
            $files        = FormUtil::getPassedValue('bannerfile', '', 'FILES');

            // echo "<pre>"; print_r($files); echo "</pre>"; exit;
            $filesize = $files ['size'];
            $allsize  = $diskquota ['sizeused'] + $filesize;
            if ($allsize >= $diskquota ['sizelimit']) {
                return LogUtil::registerError($this->__("File was not uploaded. Your disquota is exceeded for this shop. Please upgrade."));
            }

            // make directories if not exist.
            if (!is_dir('zselexdata/'.$ownername)) {
                mkdir('zselexdata/'.$ownername, 0775);
                chmod('zselexdata/'.$ownername, 0775);
            }

            if (!is_dir('zselexdata/'.$ownername.'/banner')) {
                mkdir('zselexdata/'.$ownername.'/banner', 0775);
                chmod('zselexdata/'.$ownername.'/banner', 0775);
            }

            if (!is_dir('zselexdata/'.$ownername.'/banner/resized')) {
                mkdir('zselexdata/'.$ownername.'/banner/resized', 0775);
                chmod('zselexdata/'.$ownername.'/banner/resized', 0775);
            }
            // echo "<pre>"; print_r($files); echo "</pre>"; exit;

            if (!empty($files)) {
                $random_digit  = rand(0000, 9999);
                $new_file_name = time().'_'.$files ['name'];

                $newNme      = array(
                    'newName' => $new_file_name
                );
                $file        = array();
                $file        = $files + $newNme;
                $destination = 'zselexdata/'.$ownername.'/banner';
                $upload      = $this->uploadBanner($file, $destination);

                if ($upload == true) {
                    $item = array(
                        'shop_id' => $formElements ['shop_id'],
                        'banner_image' => $new_file_name,
                        'status' => isset($formElements ['status']) ? $formElements ['status']
                                : 0
                    );

                    $args   = array(
                        'table' => 'zselex_shop_banner',
                        'element' => $item,
                        'Id' => 'shop_banner_id'
                    );
                    $result = ModUtil::apiFunc('ZSELEX', 'admin',
                            'createElement', $args);
                    LogUtil::registerStatus($this->__('Done! Banner Image Uploaded Successfuly!.'));
                } else {
                    LogUtil::registerError($this->__f('Error! Could not upload banner image'));
                }
            }
            $this->redirect(ModUtil::url('ZSELEX', 'admin',
                    'viewminisitebanner',
                    array(
                    'shop_id' => $formElements ['shop_id']
            )));
        }

        $bannerCount = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount',
                $args        = array(
                'table' => 'zselex_shop_banner',
                'where' => "shop_id=$shop_id"
        ));

        $this->view->assign('bannerExist', $bannerCount);

        return $this->view->fetch('admin/createbanner.tpl');
    }

    public function deletebanner()
    {
        $id      = FormUtil::getPassedValue('id',
                isset($args ['id']) ? $args ['id'] : 0, 'REQUEST');
        $shop_id = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : 0, 'REQUEST');
        // echo $shop_id; exit;
        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $user_id   = UserUtil::getVar('uid');
        $ownername = $this->ownername;

        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
        if (empty($confirmation)) {
            // Add ZSELEX type ID
            $this->view->assign('IdValue', $id);
            $this->view->assign('confirm_title',
                $this->__f('Delete %s', $this->__('Minisite Image')));
            $this->view->assign('confirm_msg',
                $this->__f('Do you want to delete this %s',
                    $this->__('Minisite Banner')));
            $this->view->assign('shop_id', $shop_id);
            $this->view->assign('IdName', 'id'); // edit id param name
            $this->view->assign('submitFunc', 'deletebanner');
            $this->view->assign('cancelFunc', 'viewminisitebanner');
            // Return the output that has been generated by this function
            return $this->view->fetch('admin/deletecommon.tpl');
        }

        // echo $ownername; exit;
        $argsitem = array(
            'table' => 'zselex_shop_banner',
            'IdValue' => $id,
            'IdName' => 'shop_banner_id'
        );
        // Get the news type in the db
        $item     = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $argsitem);
        $shop_id  = $item ['shop_id'];

        // echo "<pre>"; print_r($item); echo "</pre>"; exit;

        if (file_exists('zselexdata/'.$ownername.'/banner/resized/'.$item ['banner_image'])) {
            unlink('zselexdata/'.$ownername.'/banner/resized/'.$item ['banner_image']);
        }

        $where = "shop_banner_id=$id";
        if (DBUtil::deleteWhere('zselex_shop_banner', $where)) {
            LogUtil::registerStatus($this->__('Done! Banner Deleted Successfully.'));
            $args_del      = array(
                'shop_id' => $shop_id,
                'servicetype' => 'minisitebanner'
            );
            $deleteservice = ModUtil::apiFunc('ZSELEX', 'admin',
                    'deleteService', $args_del);
        }

        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewminisitebanner',
                array(
                'shop_id' => $shop_id
        )));
    }

    public function announcement($args)
    {
        $shop_id = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : 0, 'REQUEST');
        // echo $shop_id; exit;
        // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;

        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $serviceargs       = array(
            'shop_id' => $shop_id,
            'type' => 'minisiteannouncement'
        );
        $servicePermission = $this->servicePermission($serviceargs);
        if ($servicePermission ['perm'] < 1) {
            $message = $servicePermission ['message'];
            $error ++;
            LogUtil::registerError(nl2br($message));
        }

        if ($this->serviceDisabled('minisiteannouncement') < 1) {
            if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $servicedisable = true;
                $error ++;
                $disable        = "disabled";
            }
            $message = $this->__("This Service Is Currently Disabled");
            LogUtil::registerError(nl2br($message));
        }
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            $expired = $servicePermission ['expired'];
        }

        $this->view->assign('disabled', $servicedisable);
        $this->view->assign('error', $error);
        $this->view->assign('expired', $expired);

        $formElements = FormUtil::getPassedValue('formElements',
                isset($args ['formElements']) ? $args ['formElements'] : null,
                'POST');
        $formElements = $this->purifyHtml($formElements);
        if ($_POST) {
            $this->checkCsrfToken();
            $source = $_REQUEST ['source'];
            $item   = array(
                'shop_id' => $shop_id,
                'text' => $formElements ['text'],
                'start_date' => $formElements ['start_date'],
                'end_date' => $formElements ['end_date'],
                'status' => $formElements ['status']
            );
            $count  = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount',
                    $args   = array(
                    'table' => 'zselex_shop_announcement',
                    'where' => "shop_id='".$shop_id."'"
            ));
            if ($count < 1) {
                $create_args = array(
                    'table' => 'zselex_shop_announcement',
                    'element' => $item,
                    'Id' => 'ann_id'
                );
                $insert      = ModUtil::apiFunc('ZSELEX', 'admin',
                        'createElement', $create_args);
                if ($insert) {
                    LogUtil::registerStatus($this->__('Done! Created Announcement Successsfully.'));
                }
            } else {
                // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;
                // echo $shop_id; exit;
                $updateargs = array(
                    'table' => 'zselex_shop_announcement',
                    'items' => $item,
                    'where' => array(
                        'shop_id' => $shop_id
                    )
                );
                // echo "<pre>"; print_r($clear_args); echo "</pre>"; exit;
                $update     = ModUtil::apiFunc('ZSELEX', 'admin',
                        'updateElementWhere', $updateargs);
                if ($update) {
                    LogUtil::registerStatus($this->__('Done! Updated Announcement Successsfully.'));
                    if ($source == 'shopsetting') {
                        $this->redirect(ModUtil::url('ZSELEX', 'admin',
                                'shopsettings',
                                array(
                                'shop_id' => $shop_id
                        )));
                    }
                }
            }
        }

        $get     = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                $getargs = array(
                'table' => 'zselex_shop_announcement',
                'where' => "shop_id=$shop_id"
        ));
        $this->view->assign('item', $get);
        return $this->view->fetch('admin/announcement/announcement.tpl');
    }

    public function createemployee()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $shop_id = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : null, 'REQUEST');
        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $serviceargs       = array(
            'shop_id' => $shop_id,
            'type' => 'employees'
        );
        $servicePermission = $this->servicePermission($serviceargs);

        if ($servicePermission ['perm'] < 1) {
            return LogUtil::registerError($servicePermission ['message']);
        }

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) { // disabled check
            if ($this->serviceDisabled('employees') < 1) {
                return LogUtil::registerError($this->__('This service has been temporarily disabled!'));
            }
        }

        if ($_POST) {
            $this->checkCsrfToken();
            // echo $this->ownername; exit;
            $ownername    = $this->ownername;
            $formElements = FormUtil::getPassedValue('formElements',
                    isset($args ['formElements']) ? $args ['formElements'] : null,
                    'POST');
            // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;
            $file         = FormUtil::getPassedValue('empimage',
                    isset($args ['empimage']) ? $args ['empimage'] : null,
                    'FILES');
            // echo "<pre>"; print_r($file); echo "</pre>"; exit;
            $item         = array(
                'name' => $formElements ['name'],
                'shop_id' => isset($formElements ['shop_id']) ? $formElements ['shop_id']
                        : '0',
                'name' => isset($formElements ['name']) ? $formElements ['name']
                        : '',
                'phone' => isset($formElements ['phone']) ? $formElements ['phone']
                        : '',
                'cell' => isset($formElements ['cell']) ? $formElements ['cell']
                        : '',
                'email' => isset($formElements ['email']) ? $formElements ['email']
                        : '',
                'job' => isset($formElements ['job']) ? $formElements ['job'] : '',
                'status' => isset($formElements ['status']) ? $formElements ['status']
                        : 0
            );
            $args         = array(
                'table' => 'zselex_shop_employees',
                'element' => $item,
                'Id' => 'emp_id '
            );
            // Create
            $result       = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement',
                    $args);
            // $result = 1;
            if ($result) {
                $lastInsertId = DBUtil::getInsertID('zselex_shop_employees',
                        'emp_id');
                LogUtil::registerStatus($this->__('Done! Successfully created employee.'));
                if ($file ['error'] < 1) {
                    if (!is_dir('zselexdata/'.$ownername)) {
                        mkdir('zselexdata/'.$ownername, 0775);
                        chmod('zselexdata/'.$ownername, 0775);
                    }

                    if (!is_dir('zselexdata/'.$ownername.'/employees')) {
                        mkdir('zselexdata/'.$ownername.'/employees', 0775);
                        chmod('zselexdata/'.$ownername.'/employees', 0775);
                    }
                    if (!is_dir('zselexdata/'.$ownername.'/employees/fullsize')) {
                        mkdir('zselexdata/'.$ownername.'/employees/fullsize',
                            0775);
                        chmod('zselexdata/'.$ownername.'/employees/fullsize',
                            0775);
                    }
                    if (!is_dir('zselexdata/'.$ownername.'/employees/medium')) {
                        mkdir('zselexdata/'.$ownername.'/employees/medium', 0775);
                        chmod('zselexdata/'.$ownername.'/employees/medium', 0775);
                    }
                    if (!is_dir('zselexdata/'.$ownername.'/employees/thumb')) {
                        mkdir('zselexdata/'.$ownername.'/employees/thumb', 0775);
                        chmod('zselexdata/'.$ownername.'/employees/thumb', 0775);
                    }

                    $image_upload = "zselexdata/".$ownername."/employees/fullsize";
                    // $file_new_name = $this->checkFilename($image_upload, $maxFileSize, $allowExt, $file['name'], $size, $newName = '');

                    $file_new_name = $lastInsertId.$file ['name'];
                    $newNme        = array(
                        'newName' => $file_new_name
                    );

                    $file        = $file + $newNme;
                    // echo $file_new_name; exit;
                    // echo "<pre>"; print_r($file); echo "</pre>"; exit;
                    $upload_file = $this->uploadImage($file,
                        $destination = "zselexdata/".$ownername."/employees");
                    if ($upload_file) {
                        $where = "WHERE emp_id=$lastInsertId";
                        $obj   = array(
                            'emp_image' => $file_new_name
                        );
                        DBUTil::updateObject($obj, 'zselex_shop_employees',
                            $where);
                    }
                }

                $user             = UserUtil::getVar('uid');
                $serviceupdatearg = array(
                    'user_id' => $user,
                    'type' => 'employees',
                    'shop_id' => $shop_id
                );
                $serviceavailed   = ModUtil::apiFunc('ZSELEX', 'admin',
                        'updateServiceUsed', $serviceupdatearg);
                ModUtil::apiFunc('ZSELEX', 'admin', 'updateMinisite',
                    array(
                    'shop_id' => $shop_id
                ));
                $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewemployees',
                        array(
                        'shop_id' => $shop_id
                )));
            }
        }

        return $this->view->fetch('admin/employees/create_employee.tpl');
    }

    public function modifyemployee($args)
    {
        $shop_id     = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : '', 'GETPOST');
        $emp_id      = FormUtil::getPassedValue('emp_id',
                isset($args ['emp_id']) ? $args ['emp_id'] : '', 'GETPOST');
        $getEmployee = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                $getargs     = array(
                'table' => 'zselex_shop_employees',
                'where' => "emp_id=$emp_id"
        ));
        $this->view->assign('item', $getEmployee);
        $ownername   = $this->ownername;
        $this->view->assign('ownername', $ownername);

        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) { // disabled check
            if ($this->serviceDisabled('employees') < 1) {
                return LogUtil::registerError($this->__('This service has been temporarily disabled!'));
            }
        }

        if ($_POST) {
            $formElements  = FormUtil::getPassedValue('formElements',
                    isset($args ['formElements']) ? $args ['formElements'] : null,
                    'POST');
            $file          = FormUtil::getPassedValue('empimage',
                    isset($args ['empimage']) ? $args ['empimage'] : null,
                    'FILES');
            $existingImage = $formElements ['existingImage'];
            // echo $existingImage; exit;
            $items         = array(
                'emp_id' => $emp_id,
                'name' => $formElements ['name'],
                'shop_id' => isset($formElements ['shop_id']) ? $formElements ['shop_id']
                        : '0',
                'name' => isset($formElements ['name']) ? $formElements ['name']
                        : '',
                'phone' => isset($formElements ['phone']) ? $formElements ['phone']
                        : '',
                'cell' => isset($formElements ['cell']) ? $formElements ['cell']
                        : '',
                'email' => isset($formElements ['email']) ? $formElements ['email']
                        : '',
                'job' => isset($formElements ['job']) ? $formElements ['job'] : '',
                'status' => isset($formElements ['status']) ? $formElements ['status']
                        : 0
            );

            $updateargs = array(
                'table' => 'zselex_shop_employees',
                'IdValue' => $emp_id,
                'IdName' => 'emp_id',
                'element' => $items
            );

            $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement',
                    $updateargs);
            if ($result) {
                if ($file ['error'] < 1) {
                    $file_new_name = $emp_id.$file ['name'];
                    $newNme        = array(
                        'newName' => $file_new_name
                    );

                    $file        = $file + $newNme;
                    // echo $file_new_name; exit;
                    // echo "<pre>"; print_r($file); echo "</pre>"; exit;
                    $upload_file = $this->uploadImage($file,
                        $destination = "zselexdata/".$ownername."/employees");
                    if ($upload_file) {
                        $where = "WHERE emp_id=$emp_id";
                        $obj   = array(
                            'emp_image' => $file_new_name
                        );
                        DBUTil::updateObject($obj, 'zselex_shop_employees',
                            $where);
                        if (file_exists('zselexdata/'.$ownername.'/employees/'.$existingImage)) {
                            unlink('zselexdata/'.$ownername.'/employees/'.$existingImage);
                        }
                        if (file_exists('zselexdata/'.$ownername.'/employees/fullsize/'.$existingImage)) {
                            unlink('zselexdata/'.$ownername.'/employees/fullsize/'.$existingImage);
                        }

                        if (file_exists('zselexdata/'.$ownername.'/employees/medium/'.$existingImage)) {
                            unlink('zselexdata/'.$ownername.'/employees/medium/'.$existingImage);
                        }

                        if (file_exists('zselexdata/'.$ownername.'/employees/thumb/'.$existingImage)) {
                            unlink('zselexdata/'.$ownername.'/employees/thumb/'.$existingImage);
                        }
                    }
                }

                LogUtil::registerStatus($this->__('Done! Successfully updated employee.'));
                $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewemployees',
                        array(
                        'shop_id' => $shop_id
                )));
            }
        }

        // echo "<pre>"; print_r($getEmployee); echo "</pre>";

        return $this->view->fetch('admin/employees/create_employee.tpl');
    }

    public function deleteemployee($args)
    {
        $id      = FormUtil::getPassedValue('emp_id',
                isset($args ['emp_id']) ? $args ['emp_id'] : 0, 'REQUEST');
        $shop_id = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : 0, 'REQUEST');
        $user_id = UserUtil::getVar('uid');

        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
        if (empty($confirmation)) {
            // Add ZSELEX type ID
            $this->view->assign('IdValue', $id);
            $this->view->assign('confirm_title',
                $this->__f('Delete %s', $this->__('Employee')));
            $this->view->assign('confirm_msg',
                $this->__f('Do you want to delete this %s',
                    $this->__('Employee')));
            $this->view->assign('shop_id', $shop_id);
            $this->view->assign('IdName', 'emp_id'); // edit id param name
            $this->view->assign('submitFunc', 'deleteemployee');
            $this->view->assign('cancelFunc', 'viewemployees');
            // Return the output that has been generated by this function
            return $this->view->fetch('admin/deletecommon.tpl');
        }

        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));

        $argsitem = array(
            'table' => 'zselex_shop_employees',
            'IdValue' => $id,
            'IdName' => 'emp_id'
        );
        // Get the news type in the db
        $item     = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $argsitem);

        // echo "<pre>"; print_r($item); echo "</pre>"; exit;
        // echo $ownerName; exit;

        if (file_exists('zselexdata/'.$shop_id.'/employees/'.$item ['emp_image'])) {
            unlink('zselexdata/'.$shop_id.'/employees/'.$item ['emp_image']);
        }
        if (file_exists('zselexdata/'.$shop_id.'/employees/fullsize/'.$item ['emp_image'])) {
            unlink('zselexdata/'.$shop_id.'/employees/fullsize/'.$item ['emp_image']);
        }
        if (file_exists('zselexdata/'.$shop_id.'/employees/medium/'.$item ['emp_image'])) {
            unlink('zselexdata/'.$shop_id.'/employees/medium/'.$item ['emp_image']);
        }
        if (file_exists('zselexdata/'.$shop_id.'/employees/thumb/'.$item ['emp_image'])) {
            unlink('zselexdata/'.$shop_id.'/employees/thumb/'.$item ['emp_image']);
        }

        $where = "emp_id=$id";
        if (DBUtil::deleteWhere('zselex_shop_employees', $where)) {
            $servicetype   = "employees";
            $user_id       = UserUtil::getVar('uid');
            $args          = array(
                'shop_id' => $shop_id,
                'servicetype' => $servicetype,
                'user_id' => $user_id
            );
            $deleteservice = ModUtil::apiFunc('ZSELEX', 'admin',
                    'deleteService', $args);
            LogUtil::registerStatus($this->__('Done! Deleted successfully.'));
            ModUtil::apiFunc('ZSELEX', 'admin', 'updateMinisite',
                array(
                'shop_id' => $shop_id
            ));
            return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                        'viewemployees',
                        array(
                        'shop_id' => $shop_id
            )));
        } else {
            LogUtil::registerError($this->__('Error! Delete was NOT performed.'));
            return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                        'viewemployees',
                        array(
                        'shop_id' => $shop_id
            )));
        }
    }

    public function viewemployees($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        // initialize sort array - used to display sort classes and urls
        $sort   = array();
        $fields = array(
            'emp_id',
            'name',
            'cr_date',
            'status',
            'lu_date'
        ); // possible sort fields
        // echo "<pre>"; print_r($_POST); echo "</pre>";

        $shop_id = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : '', 'GETPOST');

        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $error             = 0;
        $servicedisable    = false;
        $serviceargs       = array(
            'shop_id' => $shop_id,
            'type' => 'employees'
        );
        $servicePermission = $this->servicePermission($serviceargs);

        if ($servicePermission ['perm'] < 1) {
            $message = $servicePermission ['message'];
            $this->errormsg .= $message."\n";
            $error ++;
            LogUtil::registerError(nl2br($message));
        }
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            if ($this->serviceDisabled('employees') < 1) {
                $error ++;
                $servicedisable = true;

                $message = $this->__("This service is currently disabled");
                $this->errormsg .= $message."\n";
                LogUtil::registerError(nl2br($message));
            }
            $expired = $servicePermission ['expired'];
        }

        // echo $error;

        $this->view->assign('serviceerror', $error);
        $this->view->assign('servicedisable', $servicedisable);
        $this->view->assign('expired', $expired);

        $itemsperpage  = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 40,
                'GETPOST');
        $startnum      = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $status        = FormUtil::getPassedValue('status',
                isset($args ['status']) ? $args ['status'] : null, 'GETPOST');
        $order         = FormUtil::getPassedValue('order',
                isset($args ['order']) ? $args ['order'] : 'cr_date', 'GETPOST');
        // $order = FormUtil::getPassedValue('order', isset($args['order']) ? $args['order'] : '', 'GETPOST');
        $original_sdir = FormUtil::getPassedValue('sdir',
                isset($args ['sdir']) ? $args ['sdir'] : 1, 'GETPOST');
        $searchtext    = FormUtil::getPassedValue('searchtext',
                isset($args ['searchtext']) ? $args ['searchtext'] : null,
                'GETPOST');

        $this->view->assign('startnum', $startnum);
        $this->view->assign('itemsperpage', $itemsperpage);
        $this->view->assign('order', $order);
        $this->view->assign('sdir', $original_sdir);
        $this->view->assign('searchtext', $searchtext);

        $sdir = $original_sdir ? 0 : 1; // if true change to false, if false change to true
        // change class for selected 'orderby' field to asc/desc
        if ($sdir == 0) {
            $sort ['class'] [$order] = 'z-order-desc';
            $orderdir                = 'DESC';
        }
        if ($sdir == 1) {
            $sort ['class'] [$order] = 'z-order-asc';
            $orderdir                = 'ASC';
        }

        // complete initialization of sort array, adding urls
        foreach ($fields as $field) {
            $sort ['url'] [$field] = ModUtil::url('ZSELEX', 'admin',
                    'viewemployees',
                    array(
                    'shop_id' => $shop_id,
                    'status' => $status,
                    'filtercats_serialized' => serialize($filtercats),
                    'order' => $field,
                    'sdir' => $sdir
            ));
        }
        $this->view->assign('sort', $sort);
        $this->view->assign('filter_active',
            (!isset($status) && empty($filtercats)) ? false : true);

        // $sql .= " AND bundle=0";
        if (isset($status) && $status != "") {
            $where .= " AND tbl.status = ".$status;
        }
        if (isset($searchtext) && $searchtext != "") {
            $where .= " AND tbl.name LIKE '%".DataUtil::formatForStore($searchtext)."%'";
        }
        if (isset($order) && $order != "") {
            $orberby .= $order." ".$orderdir;
        }

        $joinInfo [] = array(
            'join_table' => 'zselex_shop',
            'join_field' => array(
                'shop_id',
                'shop_name'
            ),
            'object_field_name' => array(
                'shop_id',
                'shop_name'
            ),
            'compare_field_table' => 'shop_id', // main table
            'compare_field_join' => 'shop_id'
        );

        $items = ModUtil::apiFunc('ZSELEX', 'user', 'getAllByJoin',
                $args  = array(
                'table' => 'zselex_shop_employees',
                'where' => "tbl.shop_id=$shop_id".$where,
                'joinInfo' => $joinInfo,
                'start' => $startnum,
                'itemsperpage' => $itemsperpage,
                'orderby' => $orberby
        ));

        $getCountArgs = array(
            'table' => 'zselex_shop_employees',
            'where' => "shop_id=$shop_id".$where,
            'Id' => 'emp_id',
            'status' => $status
        );

        $total_count = ModUtil::apiFunc('ZSELEX', 'admin', 'countElements',
                $getCountArgs);

        // Set the possible status for later use
        $itemstatus = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );

        $aStatus = array(
            'InActive',
            'Active'
        );
        $this->view->assign('aStatus', $aStatus);
        for ($i = 0; $i < count($items); $i ++) {
            // Get the Username for create and update
            $createargs                 = array(
                'table' => 'users',
                'IdValue' => $items [$i] ['cr_uid'],
                'IdName' => 'uid'
            );
            $users                      = ModUtil::apiFunc('ZSELEX', 'admin',
                    'getElement', $createargs);
            $items [$i] ['createduser'] = $users ['uname'];

            $updateargs                 = array(
                'table' => 'users',
                'IdValue' => $items [$i] ['lu_uid'],
                'IdName' => 'uid'
            );
            $users                      = ModUtil::apiFunc('ZSELEX', 'admin',
                    'getElement', $updateargs);
            $items [$i] ['updateduser'] = $users ['uname'];
        }

        $employees = array();
        foreach ($items as $item) {
            $options = array();

            if (SecurityUtil::checkPermission('ZSELEX::',
                    "{$item['cr_uid']}::{$item['plugin_id']}", ACCESS_EDIT)) {
                $options [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'modifyemployee',
                        array(
                        'emp_id' => $item ['emp_id'],
                        'shop_id' => $shop_id
                    )),
                    'image' => 'xedit.png',
                    'title' => $this->__('Edit')
                );

                if ((SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['plugin_id']}", /* ACCESS_DELETE */
                        ACCESS_ADD)) || SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['plugin_id']}", ACCESS_ADMIN)) {
                    $options [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin',
                            'deleteemployee',
                            array(
                            'emp_id' => $item ['emp_id'],
                            'shop_id' => $shop_id
                        )),
                        'image' => '14_layer_deletelayer.png',
                        'title' => $this->__('Delete')
                    );
                }
            }
            $item ['options'] = $options;

            $item ['infuture'] = DateUtil::getDatetimeDiff_AsField($item ['cr_date'],
                    DateUtil::getDatetime(), 6) < 0;
            $employees []      = $item;
        }

        // Assign the items to the template
        $this->view->assign('employees', $employees);
        $this->view->assign('total_count', $total_count);

        $servicecheck = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow',
                $args         = array(
                'table' => 'zselex_serviceshop',
                'where' => array(
                    "shop_id=$shop_id",
                    "type='employees'"
                )
        ));

        $servicelimit = $servicecheck ['quantity'] - $servicecheck ['availed'];

        $this->view->assign('servicecount', $servicecount);
        $this->view->assign('quantity', $servicecheck ['quantity']);
        $this->view->assign('servicelimit', $servicelimit);

        // Assign the current status filter and the possible ones
        $this->view->assign('status', $status);
        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('order', $order);
        $ownername = $this->ownername;
        $this->view->assign('ownername', $ownername);

        // $uploadpath = $_SERVER['DOCUMENT_ROOT'] . "/zselexdata/" . $ownername . "/";
        $uploadpath = "zselexdata/".$ownername."/";
        if ($_SERVER ['SERVER_NAME'] == 'localhost') {
            $uploadpath = "zselexdata/".$ownername."/";
        }
        $this->view->assign('uploadpath', $uploadpath);
        // Return the output that has been generated by this function
        return $this->view->fetch('admin/employees/viewemployees.tpl');
    }
    protected $totalImages;

    /**
     * Shop settings
     * shop information , payment settings and other shop setiings
     * are done here.
     *
     * @return HTML
     */
    public function shopsettings()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        $this->view->setCaching(0);
        $shop_id = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : '', 'GETPOST');
        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }

        if ($this->shopPermission($shop_id) < 1) {
            // return LogUtil::registerPermissionError();
            return LogUtil::registerPermissionError();
        }
        $this->reminderNotifications($shop_id);

        $this->view->assign('shop_id', $shop_id);

        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Shop');

        $ownerName  = $this->ownername;
        $this->view->assign('ownerName', $ownerName);
        $uploadpath = "zselexdata/".$shop_id."/";
        // $this->testpage();
        // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
        if ($_POST) {
            $this->checkCsrfToken();
            // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
            if ($_POST ['action'] == 'savedefaults') {
                // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
                // echo $this->owner_id; exit;

                $getCurrentShop = $repo->get(array(
                    'entity' => 'ZSELEX_Entity_Shop',
                    'fields' => array(
                        'a.urltitle'
                    ),
                    'where' => array(
                        'a.shop_id' => $shop_id
                    )
                ));
                // echo "<pre>"; print_r($getCurrentShop); echo "</pre>"; exit;

                $shop_name = FormUtil::getPassedValue('shop_name', null, 'POST');

                if (isset($_POST ['urltitle'])) {
                    $urltitle = FormUtil::getPassedValue('urltitle', null,
                            'POST');
                    if (!empty($urltitle)) {
                        $urltitle = strtolower($urltitle);
                        $urltitle = str_replace(" ", '-', $urltitle);
                    } else {
                        $urltitle = strtolower($shop_name);
                        $urltitle = str_replace(" ", '-', $urltitle);
                    }
                }

                $urlCount = $repo->getCount2(array(
                    'entity' => 'ZSELEX_Entity_Shop',
                    'field' => 'shop_id',
                    'where' => "a.urltitle=:urltitle AND a.shop_id!=''",
                    'setParams' => array(
                        'urltitle' => $urltitle
                    )
                ));

                // echo $urlCount; exit;

                if ($urlCount > 0) {
                    // $final_urltitle = $this->increment_url($title = $urltitle, $table = 'zselex_shop', $field = 'urltitle');
                    $args_url       = array(
                        'table' => 'zselex_shop',
                        'title' => $urltitle,
                        'field' => 'urltitle'
                    );
                    $final_urltitle = $this->increment_url($args_url);
                } else {
                    $final_urltitle = $urltitle;
                }

                $shop_address            = FormUtil::getPassedValue('address',
                        null, 'POST');
                $opening_hours           = FormUtil::getPassedValue('opening_hours',
                        null, 'POST');
                // echo "<pre>"; print_r($opening_hours); echo "</pre>"; exit;
                // echo $opening_hours['comment']; exit;
                // $opening_hours['comment'] = nl2br($opening_hours['comment']);
                $opening_hours_serialize = serialize($opening_hours);
                $shop_info               = FormUtil::getPassedValue('shop_info',
                        null, 'POST');
                $shop_info               = strip_tags($shop_info);
                $link_to_homepage        = FormUtil::getPassedValue('link_to_homepage',
                        null, 'POST');
                $default_img_frm         = FormUtil::getPassedValue('default_img_frm',
                        null, 'POST');
                $mainShop                = FormUtil::getPassedValue('mainshop',
                        null, 'REQUEST');

                $test = array();
                /*
                 * $obj = array(
                 * 'shop_info' => $shop_info,
                 * 'opening_hours' => $opening_hours,
                 * 'default_img_frm' => $default_img_frm
                 * );
                 */

                $obj ['shop_info']        = $shop_info;
                $obj ['link_to_homepage'] = $link_to_homepage;
                $obj ['address']          = $shop_address;
                $obj ['shop_name']        = $shop_name;
                if (isset($_POST ['urltitle'])) {
                    $obj ['urltitle'] = $final_urltitle;
                }
                $obj ['opening_hours']       = $opening_hours_serialize;
                $obj ['telephone']           = FormUtil::getPassedValue('telephone',
                        null, 'REQUEST');
                $obj ['vat_number']          = FormUtil::getPassedValue('vat_number',
                        null, 'REQUEST');
                $obj ['fax']                 = FormUtil::getPassedValue('fax',
                        null, 'REQUEST');
                $obj ['email']               = FormUtil::getPassedValue('email',
                        null, 'REQUEST');
                $obj ['advertise_sel_prods'] = FormUtil::getPassedValue('adv_sel_prod',
                        0, 'REQUEST');
                // $obj['enable_checkoutinfo'] = $enable_checkout_info;
                // $obj['checkout_info'] = $checkout_info;
                if (isset($default_img_frm)) {
                    $obj ['default_img_frm']        = $default_img_frm;
                    $setting_ob ['default_img_frm'] = $default_img_frm;
                }

                // $test = array_merge(array('opening_hours' => $opening_hours) , $test) ;

                $pntables = pnDBGetTables();
                $column   = $pntables ['zselex_shop_column'];
                $where    = "WHERE $column[shop_id]=$shop_id";

                // DBUTil::updateObject($obj, 'zselex_shop', $where);
                $repo->updateEntity(null, 'ZSELEX_Entity_Shop', $obj,
                    array(
                    'a.shop_id' => $shop_id
                ));

                // Old url support
                $urlArr    = array(
                    'shop_id' => $shop_id,
                    'title' => $getCurrentShop ['urltitle']
                );
                $updateUrl = $this->entityManager->getRepository('ZSELEX_Entity_Url')->updateShopUrl($urlArr);

                $setting_ob ['link_to_homepage'] = $link_to_homepage;
                $setting_ob ['opening_hours']    = $opening_hours_serialize;

                /*
                 * $setting_args = array(
                 * 'fields' => $setting_ob,
                 * 'where' => "a.shop=$shop_id"
                 * );
                 * $setting = $this->entityManager->getRepository('ZSELEX_Entity_ShopSetting')->updateShopSetting($setting_args);
                 */

                if ($mainShop == 1) { // setting as main shop
                    // echo "comes here"; exit;
                    $item_main1 = array(
                        // 'shop_id' => $shop_id,
                        'main' => 1
                    );

                    $update_main = array(
                        'table' => 'zselex_shop',
                        'IdValue' => $shop_id,
                        'IdName' => 'shop_id',
                        'where' => "shop_id='".$shop_id."'",
                        'element' => $item_main1
                    );

                    // ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement', $update_main);

                    $repo->updateEntity(null, 'ZSELEX_Entity_Shop', $item_main1,
                        array(
                        'a.shop_id' => $shop_id
                    ));

                    $item_main2 = array(
                        'shop_id' => $shop_id,
                        'main' => 0
                    );

                    $update_main2 = array(
                        'table' => 'zselex_shop',
                        'IdValue' => $shop_id,
                        'IdName' => 'shop_id',
                        'where' => "shop_id!='".$shop_id."'",
                        'element' => $item_main2
                    );

                    // ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement', $update_main2);
                    // $repo->updateEntity(null, 'ZSELEX_Entity_Shop', $item_main2, array('a.shop_id' => $shop_id));
                    $repo->updateMainShop(array(
                        'shop_id' => $shop_id,
                        'owner_id' => $this->owner_id
                    ));
                } else {
                    $item_main1 = array(
                        // 'shop_id' => $shop_id,
                        'main' => 0
                    );

                    $update_main = array(
                        'table' => 'zselex_shop',
                        'IdValue' => $shop_id,
                        'IdName' => 'shop_id',
                        'where' => "shop_id='".$shop_id."'",
                        'element' => $item_main1
                    );

                    // ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement', $update_main);
                    $repo->updateEntity(null, 'ZSELEX_Entity_Shop', $item_main1,
                        array(
                        'a.shop_id' => $shop_id
                    ));
                }
                LogUtil::registerStatus($this->__('Done! shop information updated successfully.'));
            } elseif ($_POST ['action'] == 'savepayments') {
                $formElement = FormUtil::getPassedValue('formElement', null,
                        'REQUEST');
                return $this->updatePayments($formElement);
            } elseif ($_POST ['action'] == 'termsconditions') {
                $formElement = FormUtil::getPassedValue('formElement', null,
                        'REQUEST');
                return $this->updateTermsCondition($formElement);
            }
        }

        /*
         * $shop_info = ModUtil::apiFunc('ZSELEX', 'user', 'get', $getargs = array(
         * 'table' => 'zselex_shop',
         * 'where' => "shop_id=$shop_id",
         * //'fields' => array('id', 'quantity', 'availed')
         * ));
         */

        $shop_info = $repo->get(array(
            'entity' => 'ZSELEX_Entity_Shop',
            'where' => array(
                'a.shop_id' => $shop_id
            )
        ));

        if (!$shop_info) {
            return LogUtil::registerError($this->__f('Error! Site not found!',
                        (int) ($shop_id)), 404);
        }

        // echo "<pre>"; print_r($shop_info); echo "</pre>"; exit;
        // $shop_info = $this->entityManager->getRepository('ZSELEX_Entity_ShopSetting')->getShopAndSettings(array('shop_id' => $shop_id));

        $shop_info ['opening_hour_array'] = unserialize($shop_info ['opening_hours']);
        $this->view->assign('shop_info', $shop_info);

        // echo "<pre>"; print_r($shop_infoss); echo "</pre>";
        // echo "<pre>"; print_r($shop_info); echo "</pre>"; exit;
        // echo "<pre>"; print_r($diskquota); echo "</pre>";

        $service_minisite = $repo->get(array(
            'entity' => 'ZSELEX_Entity_ServiceShop',
            'where' => array(
                'a.shop' => $shop_id,
                'a.type' => 'minisiteimages'
            ),
            'fields' => array(
                'a.id',
                'a.quantity',
                'a.availed'
            )
        ));

        // echo "<pre>"; print_r($service_minisite); echo "</pre>";

        $imageService = $service_minisite;
        $totalImages  = $repo->getCount(null, 'ZSELEX_Entity_MinisiteImage',
            'file_id', array(
            'a.shop' => $shop_id
        ));



        // echo "total images :" . $totalImages;

        $imagelimit = $service_minisite ['quantity'] - $totalImages;
        //echo $imagelimit;
        /*
          $limit = 0;
          if ($imagelimit < $totalImages) {
          $limit = $service_minisite ['quantity'];
          }
         */

        //echo $limit;
        /*
         * $minisite_images = $repo->getAll(array(
         * 'entity' => 'ZSELEX_Entity_MinisiteImage',
         * 'where' => array('a.shop' => $shop_id),
         * 'offset'=>$limit
         * ));
         */

        // echo "<pre>"; print_r($minisite_images); echo "</pre>";

        $minisite_banner = array();

        $diskquota_check = ModUtil::apiFunc('ZSELEX', 'admin', 'checkDiskquota',
                $args            = array(
                'shop_id' => $shop_id
        ));

        //  echo "<pre>"; print_r($diskquota_check); echo "</pre>";
        $image_args = array(
            'shop_id' => $shop_id,
            'type' => 'minisiteimages',
            'disablecheck' => true
        );

        $image_check = $this->servicePermission($image_args);
        $image_perm  = $image_check ['perm'];
        if ($diskquota_check ['error']) {
            $image_perm = 0;
        }
        $this->view->assign('image_perm', $image_perm);
        //echo "image_perm : ." . $image_perm;
        //echo "<pre>"; print_r($image_check); echo "</pre>";

        $gallery_args = array(
            'shop_id' => $shop_id,
            'type' => 'minisitegallery',
            'disablecheck' => true
        );

        $gallery_check = $this->servicePermission($gallery_args);
        $gallery_perm  = $gallery_check ['perm'];
        if ($diskquota_check ['error']) {
            $gallery_perm = 0;
        }
        $this->view->assign('gallery_perm', $gallery_perm);

        $service_employee = $repo->get(array(
            'entity' => 'ZSELEX_Entity_ServiceShop',
            'where' => array(
                'a.shop' => $shop_id,
                'a.type' => 'employees'
            ),
            'fields' => array(
                'a.id',
                'a.quantity',
                'a.availed'
            )
        ));
        $totalEmployees   = $repo->getCount(null, 'ZSELEX_Entity_Employee',
            'emp_id', array(
            'a.shop' => $shop_id
        ));

        $emp_args = array(
            'shop_id' => $shop_id,
            'type' => 'employees',
            'disablecheck' => true
        );

        $employee_check = $this->servicePermission($emp_args);
        $emp_perm       = $employee_check ['perm'];
        if ($diskquota_check ['error']) {
            $emp_perm = 0;
        }
        $this->view->assign('emp_perm', $emp_perm);

        $banner_args = array(
            'shop_id' => $shop_id,
            'type' => 'minisitebanner',
            'disablecheck' => true
        );

        $banner_check = $this->servicePermission($banner_args);
        $banner_perm  = $banner_check ['perm'];

        $bannerCount     = 0;
        //  if ($banner_perm) {
        $minisite_banner = $repo->get(array(
            'entity' => 'ZSELEX_Entity_Banner',
            'where' => array(
                'a.shop' => $shop_id
            )
        ));

        $bannerCount = $repo->getCount(null, 'ZSELEX_Entity_Banner',
            'shop_banner_id', array(
            'a.shop' => $shop_id
        ));
        //  }

        $this->view->assign('bannerExist', $bannerCount);

        if ($diskquota_check ['error']) {
            $banner_perm = 0;
        }
        $this->view->assign('banner_perm', $banner_perm);

        $announcement_args = array(
            'shop_id' => $shop_id,
            'type' => 'minisiteannouncement',
            'disablecheck' => true
        );

        $announcement_check = $this->servicePermission($announcement_args);
        $announcement_perm  = $announcement_check ['perm'];
        if ($diskquota_check ['error']) {
            $announcement_perm = 0;
        }
        $this->view->assign('announcement_perm', $announcement_perm);

        $article_args = array(
            'shop_id' => $shop_id,
            'type' => 'createarticle',
            'disablecheck' => true
        );

        $article_check = $this->servicePermission($article_args);
        $article_perm  = $article_check ['perm'];
        $this->view->assign('article_perm', $article_perm);


        if (!$image_perm) {
            $imagelimit = 0;
        }
        $employee_limit = $service_employee ['quantity'] - $totalEmployees;
        // echo "Emp Limit : " . $totalEmployees;
        if (!$emp_perm) {
            $employee_limit = 0;
        }

        // $banner_limit = 2;
        $banner_limit = 1;
        if (!$banner_perm) {
            $banner_limit = 0;
        }
        $current_theme = System::getVar('Default_Theme');
        $this->view->assign('current_theme', $current_theme);

        $payment_args      = array(
            'shop_id' => $shop_id,
            'type' => 'paybutton',
            'disablecheck' => true
        );
        $paymentPermission = $this->servicePermission($payment_args);
        // echo "<pre>"; print_r($paymentPermission); echo "</pre>";
        $payment_perm      = $paymentPermission ['perm']; // ///

        $netaxept  = $this->entityManager->getRepository('ZPayment_Entity_Netaxept')->getNetaxept(array(
            'shop_id' => $shop_id
        ));
        $paypal    = $this->entityManager->getRepository('ZPayment_Entity_PaypalSetting')->getPaypal(array(
            'shop_id' => $shop_id
        ));
        $quickpay  = $this->entityManager->getRepository('ZPayment_Entity_QuickPaySetting')->getQuickPay(array(
            'shop_id' => $shop_id
        ));
        $directpay = $this->entityManager->getRepository('ZPayment_Entity_DirectpaySetting')->getDirectpay(array(
            'shop_id' => $shop_id
        ));
        $freight   = $this->entityManager->getRepository('ZPayment_Entity_FreightSetting')->get(array(
            'entity' => 'ZPayment_Entity_FreightSetting',
            'fields' => array(
                'a.freight_id',
                'a.enabled',
                'a.std_freight_price',
                'a.zero_freight_price'
            ),
            'where' => array(
                'a.shop_id' => $shop_id
            )
        ));
        $epay      = $this->entityManager->getRepository('ZPayment_Entity_EpaySetting')->getEpay(array(
            'shop_id' => $shop_id
        ));

        // echo "<pre>"; print_r($netaxept); echo "</pre>";
        // echo "<pre>"; print_r($quickpay); echo "</pre>";
        // echo "<pre>"; print_r($freight); echo "</pre>"; exit;

        $CardsAccepted = $this->entityManager->getRepository('ZPayment_Entity_CardsAccepted')->get_Cards_Accepted(array(
            'shop_id' => $shop_id
        ));

        $CardsAccepted = unserialize($CardsAccepted ['cards']);
        // $CardsAccepted = array();
        $this->view->assign('CardsAccepted', $CardsAccepted);

        // echo "<pre>"; print_r($CardsAccepted); echo "</pre>";

        $this->view->assign('payment_perm', $payment_perm);
        $this->view->assign('netaxept', $netaxept);
        $this->view->assign('paypal', $paypal);
        $this->view->assign('quickpay', $quickpay);
        $this->view->assign('directpay', $directpay);
        $this->view->assign('freight', $freight);
        $this->view->assign('epay', $epay);

        $shop_details = $this->entityManager->getRepository('ZSELEX_Entity_ShopSetting')->getShopDetails(array(
            'shop_id' => $shop_id
        ));
        $this->view->assign('shop_details', $shop_details);

        $languages = ZLanguage::getInstalledLanguages();
        $this->view->assign('languages', $languages);

        $image_mode = ModUtil::apiFunc('ZSELEX', 'shopsetting',
                'getBannerImageMode',
                array(
                'shop_id' => $shop_id
        ));

        $this->view->assign('minisite_images', $minisite_images);
        $this->view->assign('minisite_banner', $minisite_banner);
        $this->view->assign('quantity', $service_minisite ['quantity']);
        $this->view->assign('imagelimit', $imagelimit);
        $this->view->assign('employee_limit', $employee_limit);
        $this->view->assign('banner_limit', $banner_limit);
        $this->view->assign('uploadpath', $uploadpath);
        $this->view->assign('diskquota_check', $diskquota_check);
        $this->view->assign('image_mode', $image_mode);
        $this->view->clear_cache('ajax/shopsettings.tpl');
        return $this->view->fetch('admin/shopsettings.tpl');
    }

    public function updatePayments($formElement)
    {
        // $formElement = FormUtil::getPassedValue('formElement', null, 'REQUEST');
        // echo "<pre>"; print_r($formElement); echo "</pre>"; exit;
        $CardsAccepted = FormUtil::getPassedValue('CardsAccepted', null,
                'REQUEST');
        // echo "<pre>"; print_r($CardsAccepted); echo "</pre>"; exit;

        $saveCardsAccepted = $this->entityManager->getRepository('ZPayment_Entity_QuickPay')->Cards_Accepted(array(
            'shop_id' => $formElement ['shop_id'],
            'cards' => $CardsAccepted
        ));

        $serviceargs       = array(
            'shop_id' => $formElement ['shop_id'],
            'type' => 'paybutton',
            'disablecheck' => true
        );
        $servicePermission = ZSELEX_Controller_Admin::servicePermission($serviceargs);
        // echo "<pre>"; print_r($servicePermission); echo "</pre>";
        $perm              = $servicePermission ['perm'];
        if ($perm) {
            // LogUtil::registerError($this->__($servicePermission['message']));
            // $this->redirect(ModUtil::url('ZSELEX', 'admin', 'shopsettings', array('shop_id' => $formElement['shop_id'])));
            // }

            $count = $this->entityManager->getRepository('ZPayment_Entity_Netaxept')->counts(array(
                'shop_id' => $formElement ['shop_id']
            ));
            // echo "count :" . $count; exit;

            if ($count < 1) { // INSERT
                $nets_entity = new ZPayment_Entity_NetaxeptSetting();
                $nets_entity->setShop_id($formElement ['shop_id']);
                $nets_entity->setEnabled($formElement ['Netaxept_enabled']);
                $nets_entity->setTest_mode($formElement ['Netaxept_testmode']);
                $nets_entity->setMerchant_id($formElement ['Netaxept_merchant_id']);
                $nets_entity->setToken($formElement ['Netaxept_token']);
                $nets_entity->setTest_merchant_id($formElement ['Netaxept_testmerchant_id']);
                $nets_entity->setTest_token($formElement ['Netaxept_testtoken']);
                $nets_entity->setReturn_url($formElement ['Netaxept_returl']);
                $this->entityManager->persist($nets_entity);
                $this->entityManager->flush();
                $InsertId    = $nets_entity->getId();
                if ($InsertId > 0) {
                    LogUtil::registerStatus($this->__('Done! Created Netaxept configuration.'));
                }
            } else {
                $update = $this->entityManager->getRepository('ZPayment_Entity_Netaxept')->updateNetaxeptSettings($formElement);
                if ($update) {
                    LogUtil::registerStatus($this->__('Done! Updated Netaxept configuration.'));
                }
            }

            $paypal_count = $this->entityManager->getRepository('ZPayment_Entity_Paypal')->paypal_count(array(
                'shop_id' => $formElement ['shop_id']
            ));
            if ($paypal_count < 1) { // INSERT
                $paypal_entity = new ZPayment_Entity_PaypalSetting();
                $paypal_entity->setShop_id($formElement ['shop_id']);
                $paypal_entity->setEnabled($formElement ['Paypal_enabled']);
                $paypal_entity->setTest_mode($formElement ['Paypal_testmode']);
                $paypal_entity->setBusiness_email($formElement ['Paypal_business_email']);

                $this->entityManager->persist($paypal_entity);
                $this->entityManager->flush();
                $InsertId = $paypal_entity->getId();
                if ($InsertId > 0) {
                    LogUtil::registerStatus($this->__('Done! Created Paypal configuration.'));
                }
            } else {
                $update = $this->entityManager->getRepository('ZPayment_Entity_Paypal')->updatePaypalSettings($formElement);
                if ($update) {
                    LogUtil::registerStatus($this->__('Done! Updated the Paypal configuration.'));
                }
            }

            $quickpay_count = $this->entityManager->getRepository('ZPayment_Entity_QuickPay')->quickpay_count(array(
                'shop_id' => $formElement ['shop_id']
            ));
            if ($quickpay_count < 1) { // INSERT
                $quickpay_entity = new ZPayment_Entity_QuickPaySetting();
                $quickpay_entity->setShop_id($formElement ['shop_id']);
                $quickpay_entity->setEnabled($formElement ['QuickPay_enabled']);
                // $quickpay_entity->setTest_mode($formElement ['QuickPay_testmode']);
                // $quickpay_entity->setQuickpay_id($formElement ['QuickPay_ID']);
                $quickpay_entity->setMerchant_id($formElement ['quickpay_merchant_id']);
                // $quickpay_entity->setMd5_secret($formElement ['QuickPay_md5']);
                //  $quickpay_entity->setPay_type($formElement ['QuickPay_paytype']);
                $quickpay_entity->setAgreement_id($formElement ['quickpay_agreement_id']);
                $quickpay_entity->setApi_key($formElement ['quickpay_api_key']);
                $quickpay_entity->setReturn_url($formElement ['quickpay_return_url']);

                $this->entityManager->persist($quickpay_entity);
                $this->entityManager->flush();
                $InsertId = $quickpay_entity->getId();
                if ($InsertId > 0) {
                    LogUtil::registerStatus($this->__('Done! Created QuickPay configuration.'));
                }
            } else {
                $qp_update = $this->entityManager->getRepository('ZPayment_Entity_QuickPay')->updateQuickPaySettings($formElement);
                if ($qp_update) {
                    LogUtil::registerStatus($this->__('Done! Updated the QuickPay configuration.'));
                }
            }

            $epay_count = $this->entityManager->getRepository('ZPayment_Entity_Epay')->epay_count(array(
                'shop_id' => $formElement ['shop_id']
            ));
            if ($epay_count < 1) { // INSERT
                $epay_entity = new ZPayment_Entity_EpaySetting();
                $epay_entity->setShop_id($formElement ['shop_id']);
                $epay_entity->setEnabled($formElement ['Epay_enabled']);
                $epay_entity->setTest_mode($formElement ['Epay_testmode']);
                $epay_entity->setTest_merchant_number($formElement ['Epay_testmode']);
                $epay_entity->setMerchant_number($formElement ['Epay_merchant_number']);
                $epay_entity->setMd5_hash($formElement ['Epay_md5']);

                $this->entityManager->persist($epay_entity);
                $this->entityManager->flush();
                $InsertId = $epay_entity->getId();
                if ($InsertId > 0) {
                    LogUtil::registerStatus($this->__('Done! Created Epay configuration.'));
                }
            } else {
                $epay_update = $this->entityManager->getRepository('ZPayment_Entity_Epay')->updateEpaySettings($formElement);
                if ($epay_update) {
                    LogUtil::registerStatus($this->__('Done! Updated the Epay configuration.'));
                }
            }
            $dp_count = $this->entityManager->getRepository('ZPayment_Entity_DirectpaySetting')->directpay_count(array(
                'shop_id' => $formElement ['shop_id']
            ));
            if ($dp_count < 1) { // INSERT
                $directpay_entity = new ZPayment_Entity_DirectpaySetting();
                $directpay_entity->setShop_id($formElement ['shop_id']);
                $directpay_entity->setEnabled($formElement ['Directpay_enabled']);
                $directpay_entity->setInfo($formElement ['Directpay_info']);

                $this->entityManager->persist($directpay_entity);
                $this->entityManager->flush();
                $InsertId = $directpay_entity->getId();
                if ($InsertId > 0) {
                    LogUtil::registerStatus($this->__('Done! Created Directpay configuration.'));
                }
            } else {
                $update = $this->entityManager->getRepository('ZPayment_Entity_DirectpaySetting')->updateDirectpaySettings($formElement);
                if ($update) {
                    LogUtil::registerStatus($this->__('Done! Updated Directpay configuration.'));
                }
            }

            $freight_count = $this->entityManager->getRepository('ZPayment_Entity_FreightSetting')->getCount(null,
                'ZPayment_Entity_FreightSetting', 'freight_id',
                array(
                'a.shop_id' => $formElement ['shop_id']
            ));
            if ($freight_count < 1) { // INSERT
                $freight_entity = new ZPayment_Entity_FreightSetting();
                $freight_entity->setShop_id($formElement ['shop_id']);
                $freight_entity->setEnabled($formElement ['freight_enabled']);
                $freight_entity->setStd_freight_price($formElement ['std_freight_price']);
                $freight_entity->setZero_freight_price($formElement ['zero_freight_price']);

                $this->entityManager->persist($freight_entity);
                $this->entityManager->flush();
                $InsertId = $freight_entity->getFreight_id();
                if ($InsertId > 0) {
                    LogUtil::registerStatus($this->__('Done! Created Freight configuration.'));
                }
            } else {
                $updFreightObj = array(
                    'enabled' => $formElement ['freight_enabled'],
                    'std_freight_price' => $formElement ['std_freight_price'],
                    'zero_freight_price' => $formElement ['zero_freight_price']
                );
                $updateFreight = $this->entityManager->getRepository('ZPayment_Entity_FreightSetting')->updateEntity(null,
                    'ZPayment_Entity_FreightSetting', $updFreightObj,
                    array(
                    'a.shop_id' => $formElement ['shop_id']
                ));
                if ($updateFreight) {
                    LogUtil::registerStatus($this->__('Done! Updated Freight configuration.'));
                }
            }
        }
        $shop_exist = $this->entityManager->getRepository('ZSELEX_Entity_ShopSetting')->shop_exist(array(
            'shop_id' => $formElement ['shop_id']
        ));

        if (!$shop_exist) {
            $shop_entity = new ZSELEX_Entity_ShopSetting();
            $shop_entity->setShop($formElement ['shop_id']);
            $shop_entity->setNo_payment($formElement ['no_payment']);

            $this->entityManager->persist($shop_entity);
            $this->entityManager->flush();
            // $no_payment = $shop_entity->getShop_id();
            $no_payment = $shop_entity->getId();
        } else {
            // $no_payment = $this->entityManager->getRepository('ZSELEX_Entity_ShopDetail')->updateNopayment($formElement);
            $n_args     = array(
                'fields' => array(
                    'no_payment' => $formElement ['no_payment']
                ),
                'where' => "a.shop=$formElement[shop_id]"
            );
            $no_payment = $this->entityManager->getRepository('ZSELEX_Entity_ShopSetting')->updateShopSetting($n_args);
        }
        if ($no_payment > 0) {
            LogUtil::registerStatus($this->__('Done! Updated payment settings.'));
        }

        $updShopObj = array(
            'delivery_time' => $formElement ['delivery_time']
        );
        $updateShop = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->updateEntity(null,
            'ZSELEX_Entity_Shop', $updShopObj,
            array(
            'a.shop_id' => $formElement ['shop_id']
        ));
        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'shopsettings',
                array(
                'shop_id' => $formElement ['shop_id']
        )));
    }

    public function updateNoPayButton()
    {
        
    }

    public function updateTermsCondition($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        // echo "<pre>"; print_r($args['terms_conditions']); echo "</pre>"; exit;
        // error_reporting(E_ALL);
        $shop_exist = $this->entityManager->getRepository('ZSELEX_Entity_ShopSetting')->shop_exist(array(
            'shop_id' => $args ['shop_id']
        ));
        // echo $shop_exist; exit;

        $this->entityManager->getRepository('ZSELEX_Entity_Shop')->updateEntity(null,
            'ZSELEX_Entity_Shop',
            array(
            'terms_conditions' => serialize($args ['terms_conditions'])
            ), array(
            'a.shop_id' => $args ['shop_id']
        ));
        /*
         * if (!$shop_exist) {
         * $shop_entity = new ZSELEX_Entity_ShopSetting();
         * $shop_entity->setShop($args['shop_id']);
         * $shop_entity->setTerms_conditions(serialize($args['terms_conditions']));
         *
         * $this->entityManager->persist($shop_entity);
         * $this->entityManager->flush();
         * $InsertId = $shop_entity->getShop_id();
         * if ($InsertId > 0) {
         * LogUtil::registerStatus($this->__('Done! create terms and conditions.'));
         * }
         * } else {
         * $update_terms = $this->entityManager->getRepository('ZSELEX_Entity_ShopSetting')->updateTermsCondition($args);
         * if ($update_terms) {
         * LogUtil::registerStatus($this->__('Done! updated terms and conditions.'));
         * }
         * }
         */
        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'shopsettings',
                array(
                'shop_id' => $args ['shop_id']
        )));
    }

    public function saveImage($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $this->checkCsrfToken();
        // echo $shop_id; exit;
        $imageRepo      = $this->entityManager->getRepository('ZSELEX_Entity_MinisiteImage');
        $image_id       = FormUtil::getPassedValue('image_id', null, 'POST');
        $image_name     = FormUtil::getPassedValue('image_name', null, 'POST');
        $image_dispname = FormUtil::getPassedValue('image_dispname', null,
                'POST');
        $image_desc     = FormUtil::getPassedValue('image_desc', null, 'POST');
        $status         = FormUtil::getPassedValue('status', null, 'POST');
        $sort_order     = FormUtil::getPassedValue('sort_order', null, 'POST');
        $default_image  = FormUtil::getPassedValue('default_image', null, 'POST');
        $default_image  = (isset($default_image)) ? 1 : 0;
        $ownerName      = $this->ownername;

        // echo $default_image; exit;
        // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
        if ($_POST ['action'] == 'saveimage') {
            $obj      = array(
                'name' => $image_name,
                'dispname' => $image_dispname,
                'filedescription' => $image_desc,
                'status' => $status,
                'sort_order' => $sort_order
            );
            $pntables = pnDBGetTables();
            $column   = $pntables ['zselex_files_column'];
            $where    = "WHERE $column[file_id]=$image_id";

            // DBUTil::updateObject($obj, 'zselex_files', $where);
            $imageRepo->updateEntity(null, 'ZSELEX_Entity_MinisiteImage', $obj,
                array(
                'a.file_id' => $image_id
            ));

            if ($default_image == 1) {
                $item = array(
                    'defaultImg' => 1
                );
                /*
                 * $updateargs = array(
                 * 'table' => 'zselex_files',
                 * 'IdValue' => $image_id,
                 * 'IdName' => 'file_id',
                 * 'where' => "file_id='" . $image_id . "' AND shop_id=$shop_id",
                 * 'element' => $item
                 * );
                 */

                // $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement', $updateargs);

                $result = $imageRepo->updateEntity(null,
                    'ZSELEX_Entity_MinisiteImage', $item,
                    array(
                    'a.file_id' => $image_id,
                    'a.shop' => $shop_id
                ));

                $item = array(
                    'defaultImg' => 0
                );
                /*
                 * $updateargs = array(
                 * 'table' => 'zselex_files',
                 * 'IdValue' => $image_id,
                 * 'IdName' => 'file_id',
                 * 'where' => "file_id!='" . $image_id . "' AND shop_id=$shop_id",
                 * 'element' => $item
                 * );
                 */

                // $result_default_image = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement', $updateargs);
                $result_default_image = $imageRepo->updateEntity(null,
                    'ZSELEX_Entity_MinisiteImage', $item,
                    array(
                    'a.shop' => $shop_id
                    ),
                    array(
                    'a.file_id' => $image_id
                ));
            } else {
                $item = array(
                    'defaultImg' => 0
                );
                /*
                 * $updateargs = array(
                 * 'table' => 'zselex_files',
                 * 'IdValue' => $image_id,
                 * 'IdName' => 'file_id',
                 * 'where' => "file_id='" . $image_id . "' AND shop_id=$shop_id",
                 * 'element' => $item
                 * );
                 */

                // $result_default_image = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement', $updateargs);
                $result_default_image = $imageRepo->updateEntity(null,
                    'ZSELEX_Entity_MinisiteImage', $item,
                    array(
                    'a.file_id' => $image_id,
                    'a.shop' => $shop_id
                ));
            }

            LogUtil::registerStatus($this->__('Done! image updated successfully.'));
        } elseif ($_POST ['action'] == 'deleteimage') {

            /*
             * $item = ModUtil::apiFunc('ZSELEX', 'user', 'get', $getargs = array(
             * 'table' => 'zselex_files',
             * 'where' => "file_id=$image_id",
             * //'fields' => array('id', 'quantity', 'availed')
             * ));
             */

            $item = $imageRepo->get(array(
                'entity' => 'ZSELEX_Entity_MinisiteImage',
                'where' => array(
                    'a.file_id' => $image_id
                )
            ));

            // echo "<pre>"; print_r($item); echo "</pre>"; exit;
            $delete = $imageRepo->deleteEntity(null,
                'ZSELEX_Entity_MinisiteImage',
                array(
                'a.file_id' => $image_id
            ));
            // if (DBUtil::deleteWhere('zselex_files', $where = "file_id=$image_id")) {
            if ($delete) {
                if (file_exists('zselexdata/'.$shop_id.'/minisiteimages/'.$item ['name'])) {
                    unlink('zselexdata/'.$shop_id.'/minisiteimages/'.$item ['name']);
                }
                if (file_exists('zselexdata/'.$shop_id.'/minisiteimages/fullsize/'.$item ['name'])) {
                    unlink('zselexdata/'.$shop_id.'/minisiteimages/fullsize/'.$item ['name']);
                }
                if (file_exists('zselexdata/'.$shop_id.'/minisiteimages/medium/'.$item ['name'])) {
                    unlink('zselexdata/'.$shop_id.'/minisiteimages/medium/'.$item ['name']);
                }
                if (file_exists('zselexdata/'.$shop_id.'/minisiteimages/thumb/'.$item ['name'])) {
                    unlink('zselexdata/'.$shop_id.'/minisiteimages/thumb/'.$item ['name']);
                }

                $servicetype   = "minisiteimages";
                $user_id       = UserUtil::getVar('uid');
                $args          = array(
                    'shop_id' => $shop_id,
                    'servicetype' => $servicetype,
                    'user_id' => $user_id
                );
                $deleteservice = ModUtil::apiFunc('ZSELEX', 'admin',
                        'deleteService', $args);
                LogUtil::registerStatus($this->__('Done! deleted image successfully.'));
                ModUtil::apiFunc('ZSELEX', 'admin', 'updateMinisite',
                    array(
                    'shop_id' => $shop_id
                ));
                return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                            'shopsettings',
                            array(
                            'shop_id' => $shop_id
                )));
            } else {
                LogUtil::registerError($this->__('Error! Delete was NOT performed.'));
                return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                            'shopsettings',
                            array(
                            'shop_id' => $shop_id
                )));
            }
        }

        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'shopsettings',
                array(
                'shop_id' => $shop_id
        )));
    }

    public function loadMiniSiteImages($args)
    {
        $shop_id   = $_REQUEST ['shop_id'];
        $ajax      = $_REQUEST ['ajax'];
        $ownerName = $this->ownername;
        $this->view->assign('ownerName', $ownerName);

        $repo             = $this->entityManager->getRepository('ZSELEX_Entity_MinisiteImage');
        $service_minisite = $repo->get(array(
            'entity' => 'ZSELEX_Entity_ServiceShop',
            'where' => array(
                'a.shop' => $shop_id,
                'a.type' => 'minisiteimages'
            ),
            'fields' => array(
                'a.id',
                'a.quantity',
                'a.availed'
            )
        ));
        $imageService     = $service_minisite;


        $minisite_images = array();
        if ($imageService) {
            $totalImages = $repo->getCount(null, 'ZSELEX_Entity_MinisiteImage',
                'file_id',
                array(
                'a.shop' => $shop_id
            ));

            $imagelimit = $service_minisite ['quantity'] - $totalImages;
            $limit      = 0;
            if ($imagelimit < $totalImages) {
                $limit = $service_minisite ['quantity'];
            }
            $imageArgs = array(
                'entity' => 'ZSELEX_Entity_MinisiteImage',
                'where' => array(
                    'a.shop' => $shop_id
                ),
                'orderby' => 'a.defaultImg DESC , a.sort_order ASC'
            );
            // 'offset' => $limit

            if ($limit > 0) {
                $imageArgs ['offset'] = $limit;
            }
            $minisite_images = $repo->getAll($imageArgs);
        }
        $this->view->assign('minisite_images', $minisite_images);
        if ($ajax) {
            $data            = '';
            $view            = Zikula_View::getInstance($this->name);
            $view->assign('ownerName', $ownerName);
            $view->assign('shop_id', $shop_id);
            $view->assign('minisite_images', $minisite_images);
            $output_test     = $view->fetch('ajax/loadimages.tpl');
            $data .= new Zikula_Response_Ajax_Plain($output_test);
            $output ["data"] = $data;
            AjaxUtil::output($output);
            return;
        } else {
            return $this->view->fetch('ajax/loadimages.tpl');
        }
    }

    public function loadEmployees($args)
    {
        $shop_id   = $_REQUEST ['shop_id'];
        $ajax      = $_REQUEST ['ajax'];
        $ownerName = $this->ownername;
        $this->view->assign('ownerName', $ownerName);

        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Employee');

        $serviceEmployee = $repo->get(array(
            'entity' => 'ZSELEX_Entity_ServiceShop',
            'where' => array(
                'a.shop' => $shop_id,
                'a.type' => 'employees'
            ),
            'fields' => array(
                'a.id',
                'a.quantity',
                'a.availed'
            )
        ));

        $employees = array();

        if ($serviceEmployee) {
            $totalEmployees = $repo->getCount(null, 'ZSELEX_Entity_Employee',
                'emp_id',
                array(
                'a.shop' => $shop_id
            ));
            $employeeLimit  = $serviceEmployee ['quantity'] - $totalEmployees;
            $limit          = 0;
            if ($employeeLimit < $totalEmployees) {
                $limit = $serviceEmployee ['quantity'];
            }

            $empArgs = array(
                'entity' => 'ZSELEX_Entity_Employee',
                'where' => array(
                    'a.shop' => $shop_id
                ),
                'orderby' => 'a.sort_order ASC'
            );
            if ($limit > 0) {
                $empArgs ['offset'] = $limit;
            }
            $employees = $repo->getAll($empArgs);
        }
        $this->view->assign('employees', $employees);
        if ($ajax) {
            $data            = '';
            $view            = Zikula_View::getInstance($this->name);
            $view->assign('ownerName', $ownerName);
            $view->assign('shop_id', $shop_id);
            $view->assign('employees', $employees);
            $output_test     = $view->fetch('ajax/loademployees.tpl');
            $data .= new Zikula_Response_Ajax_Plain($output_test);
            $output ["data"] = $data;
            AjaxUtil::output($output);
            return;
        } else {
            return $this->view->fetch('ajax/loademployees.tpl');
        }
    }

    public function loadBanner($args)
    {
        // exit;
        // $this->view->clear_cache('ajax/shopsettings.tpl');
        $repo            = $this->entityManager->getRepository('ZSELEX_Entity_Employee');
        $shop_id         = $_REQUEST ['shop_id'];
        $ajax            = $_REQUEST ['ajax'];
        // $ownerName = $this->ownername;
        // $this->view->assign('ownerName', $ownerName);
        $this->view->assign('shop_id', $shop_id);
        $minisite_banner = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                $getargs         = array(
                'table' => 'zselex_shop_banner',
                'where' => "shop_id=$shop_id"
        ));
        $this->view->assign('minisite_banner', $minisite_banner);
        if ($ajax) {
            $data            = '';
            $view            = Zikula_View::getInstance($this->name);
            // $view->assign('ownerName', $ownerName);
            $view->assign('shop_id', $shop_id);
            $view->assign('minisite_banner', $minisite_banner);
            $output_test     = $view->fetch('ajax/loadbanner.tpl');
            $data .= new Zikula_Response_Ajax_Plain($output_test);
            $output ["data"] = $data;
            AjaxUtil::output($output);
            return;
        } else {
            return $this->view->fetch('ajax/loadbanner.tpl');
        }
    }

    public function saveEmployee($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        $formElements = FormUtil::getPassedValue('formElements',
                isset($args ['formElements']) ? $args ['formElements'] : null,
                'REQUEST');
        $formElements = $this->purifyHtml($formElements);
        // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;
        // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
        $ownerName    = $this->ownername;
        $shop_id      = $formElements ['shop_id'];
        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $this->checkCsrfToken();

        $empRepo = $this->entityManager->getRepository('ZSELEX_Entity_Employee');
        if ($_POST ['action'] == 'saveimageemployee') {
            $item = array(
                'emp_id' => $formElements ['emp_id'],
                'name' => $formElements ['name'],
                'phone' => $formElements ['phone'],
                'cell' => $formElements ['cell'],
                'email' => $formElements ['email'],
                'job' => $formElements ['job'],
                'sort_order' => $formElements ['sort_order']
            );

            // echo "<pre>"; print_r($item); echo "</pre>"; exit;

            /*
             * $update_args = array(
             * 'table' => 'zselex_shop_employees',
             * 'IdValue' => $formElements['emp_id'],
             * 'IdName' => 'emp_id',
             * 'where' => "emp_id='" . $formElements['emp_id'] . "'",
             * 'element' => $item
             * );
             *
             * $update = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement', $update_args);
             */
            $update = $empRepo->updateEntity(null, 'ZSELEX_Entity_Employee',
                $item,
                array(
                'a.emp_id' => $formElements ['emp_id']
            ));

            if ($update) {
                LogUtil::registerStatus($this->__('Done! Employee details saved successfully.'));
                $this->redirect(ModUtil::url('ZSELEX', 'admin', 'shopsettings',
                        array(
                        'shop_id' => $formElements ['shop_id']
                )));
            } else {
                throw new Zikula_Exception_Fatal($this->__('Story not created for unknown reason (Api failure).'));
                return false;
            }
        } elseif ($_POST ['action'] == 'deleteemployee') {

            /*
             * $item = ModUtil::apiFunc('ZSELEX', 'user', 'get', $getargs = array(
             * 'table' => 'zselex_shop_employees',
             * 'where' => "emp_id=$formElements[emp_id]",
             * //'fields' => array('id', 'quantity', 'availed')
             * ));
             */

            $item   = $empRepo->get(array(
                'entity' => 'ZSELEX_Entity_Employee',
                'where' => array(
                    'a.emp_id' => $formElements ['emp_id']
                )
            ));
            // echo "<pre>"; print_r($item); echo "</pre>"; exit;
            $delete = $empRepo->deleteEntity(null, 'ZSELEX_Entity_Employee',
                array(
                'a.emp_id' => $formElements ['emp_id']
            ));
            if ($delete) {
                if (file_exists('zselexdata/'.$shop_id.'/employees/'.$item ['emp_image'])) {
                    unlink('zselexdata/'.$shop_id.'/employees/'.$item ['emp_image']);
                }
                if (file_exists('zselexdata/'.$shop_id.'/employees/fullsize/'.$item ['emp_image'])) {
                    unlink('zselexdata/'.$shop_id.'/employees/fullsize/'.$item ['emp_image']);
                }
                if (file_exists('zselexdata/'.$shop_id.'/employees/medium/'.$item ['emp_image'])) {
                    unlink('zselexdata/'.$shop_id.'/employees/medium/'.$item ['emp_image']);
                }
                if (file_exists('zselexdata/'.$shop_id.'/employees/thumb/'.$item ['emp_image'])) {
                    unlink('zselexdata/'.$shop_id.'/employees/thumb/'.$item ['emp_image']);
                }

                $servicetype   = "employees";
                $user_id       = UserUtil::getVar('uid');
                $args          = array(
                    'shop_id' => $shop_id,
                    'servicetype' => $servicetype,
                    'user_id' => $user_id
                );
                $deleteservice = ModUtil::apiFunc('ZSELEX', 'admin',
                        'deleteService', $args);
                LogUtil::registerStatus($this->__('Done! deleted employee successfully.'));
                ModUtil::apiFunc('ZSELEX', 'admin', 'updateMinisite',
                    array(
                    'shop_id' => $shop_id
                ));
                return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                            'shopsettings',
                            array(
                            'shop_id' => $shop_id
                )));
            } else {
                LogUtil::registerError($this->__('Error! Delete was NOT performed.'));
                return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                            'shopsettings',
                            array(
                            'shop_id' => $shop_id
                )));
            }
        }
    }

    public function saveAnnouncement($args)
    {
        $formElements = FormUtil::getPassedValue('formElements',
                isset($args ['formElements']) ? $args ['formElements'] : null,
                'REQUEST');
        $shop_id      = $formElements ['shop_id'];
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $this->checkCsrfToken();
        // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;
        // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Banner');
        if ($_POST ['action'] == 'saveannouncement') {

            // $this->checkCsrfToken();
            $item  = array(
                'shop' => $formElements ['shop_id'],
                'text' => $formElements ['text'],
                'start_date' => $formElements ['start_date'],
                'end_date' => $formElements ['end_date'],
                'status' => $formElements ['status']
            );
            $count = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount',
                    $args  = array(
                    'table' => 'zselex_shop_announcement',
                    'where' => "shop_id='".$formElements ['shop_id']."'"
            ));
            if ($count < 1) {
                /*
                 * $create_args = array(
                 * 'table' => 'zselex_shop_announcement',
                 * 'element' => $item,
                 * 'Id' => 'ann_id'
                 * );
                 * $insert = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $create_args);
                 * $result = $insert;
                 */
                $ann  = new ZSELEX_Entity_Announcement();
                $shop = $this->entityManager->find('ZSELEX_Entity_Shop',
                    $formElements ['shop_id']);
                $ann->setShop($shop);
                $ann->setText($item ['text']);
                $ann->setStart_date(date_create($item ['start_date']));
                $ann->setEnd_date(date_create($item ['end_date']));
                $ann->setStatus($item ['status']);

                $this->entityManager->persist($ann);
                $this->entityManager->flush();

                $result = $ann->getAnn_id();
                $insert = $result;

                if ($insert) {
                    LogUtil::registerStatus($this->__('Done! Created Announcement Successsfully.'));
                }
            } else {
                // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;
                // echo $shop_id; exit;
                $updateargs = array(
                    'table' => 'zselex_shop_announcement',
                    'items' => $item,
                    'where' => array(
                        'shop_id' => $formElements ['shop_id']
                    )
                );
                // echo "<pre>"; print_r($clear_args); echo "</pre>"; exit;
                // $update = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElementWhere', $updateargs);
                $update     = $repo->updateEntity(null,
                    'ZSELEX_Entity_Announcement', $item,
                    array(
                    'a.shop' => $formElements ['shop_id']
                ));
                $result     = $update;
                if ($update) {
                    LogUtil::registerStatus($this->__('Done! Updated Announcement Successsfully.'));
                }
            }
            if ($result) {
                return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                            'shopsettings',
                            array(
                            'shop_id' => $formElements ['shop_id']
                )));
            }
        } elseif ($_POST ['action'] == 'deletebanner') {
            // echo $ownername; exit;

            /*
             * $item = ModUtil::apiFunc('ZSELEX', 'user', 'get', $getargs = array(
             * 'table' => 'zselex_shop_banner',
             * 'where' => "shop_id=$formElements[shop_id]",
             * //'fields' => array('id', 'quantity', 'availed')
             * ));
             */

            $item = $repo->get(array(
                'entity' => 'ZSELEX_Entity_Banner',
                'where' => array(
                    'a.shop' => $formElements ['shop_id']
                )
            ));

            // echo "<pre>"; print_r($item); echo "</pre>"; exit;
            if (file_exists('zselexdata/'.$shop_id.'/banner/resized/'.$item ['banner_image'])) {
                unlink('zselexdata/'.$shop_id.'/banner/resized/'.$item ['banner_image']);
            }
            $deleteBanner = $repo->deleteEntity(null, 'ZSELEX_Entity_Banner',
                array(
                'a.shop' => $formElements ['shop_id']
            ));
            // $where = "shop_id=$formElements[shop_id]";
            $deleteAnn    = $repo->deleteEntity(null,
                'ZSELEX_Entity_Announcement',
                array(
                'a.shop' => $formElements ['shop_id']
            ));
            if ($deleteAnn) {
                LogUtil::registerStatus($this->__('Done! banner deleted successfully.'));
                $args_del      = array(
                    'shop_id' => $formElements ['shop_id'],
                    'servicetype' => 'minisitebanner'
                );
                $deleteservice = ModUtil::apiFunc('ZSELEX', 'admin',
                        'deleteService', $args_del);
                return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                            'shopsettings',
                            array(
                            'shop_id' => $formElements ['shop_id']
                )));
            }
        }
    }

    public function createEventAuto($args)
    {
        $quantity = $args ['quantity'];
        $shop_id  = $args ['shop_id'];
        $user_id  = UserUtil::getVar('uid');
        for ($i = 1; $i <= $quantity; $i ++) {
            $item = array(
                'shop_id' => $shop_id
            );

            $args_evnt        = array(
                'table' => 'zselex_shop_events',
                'element' => $item,
                'Id' => 'shop_event_id'
            );
            $result           = ModUtil::apiFunc('ZSELEX', 'admin',
                    'createElement', $args_evnt);
            $serviceupdatearg = array(
                'user_id' => $user_id,
                'type' => 'eventservice',
                'shop_id' => $shop_id
            );
            $serviceavailed   = ModUtil::apiFunc('ZSELEX', 'admin',
                    'updateServiceUsed', $serviceupdatearg);
        }
        return true;
    }

    public function events($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());

        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $repo       = $this->entityManager->getRepository('ZSELEX_Entity_Event');
        $this->reminderNotifications($shop_id);
        $this->view->assign('shop_id', $shop_id);
        $ownerName  = $this->ownername;
        $this->view->assign('ownerName', $ownerName);
        // $uploadpath = $_SERVER['DOCUMENT_ROOT'] . "/zselexdata/" . $ownerName;
        /*
         * if ($_SERVER['SERVER_NAME'] == 'localhost') {
         * $uploadpath = "zselexdata/" . $ownerName . "/";
         * }
         */
        $uploadpath = "zselexdata/".$shop_id."/";

        $this->view->assign('uploadpath', $uploadpath);

        $before = microtime(true);


        $events            = array();
        $events_left       = 0;
        $serviceargs       = array(
            'shop_id' => $shop_id,
            'type' => 'eventservice',
            'disablecheck' => true
        );
        $servicePermission = $this->servicePermission($serviceargs);
        //echo "<pre>"; print_r($servicePermission); echo "</pre>"; 
        $this->view->assign('service', $servicePermission);

        if ($servicePermission ['perm'] < 1) {
            $message = $servicePermission ['message'];
            // $error++;
            LogUtil::registerError(nl2br($message));
        }
        $event_perm = $servicePermission ['perm'];


        if ($event_perm) {
            $totalEvents = $repo->getCount(null, 'ZSELEX_Entity_Event',
                'shop_event_id',
                array(
                'a.shop' => $shop_id
            ));

            $events_left = $servicePermission['quantity'] - $totalEvents;


            if ($events_left < 1) {
                // $template = 'viewproducts_noservice.tpl';
                $message = ($servicePermission['service_status'] == 2) ? $this->__("Your service limit is over for this service")
                        : $this->__("Your service limit is over for this demo service");
                $error ++;
                LogUtil::registerError(nl2br($message));
            }


            $limit = 0;
            if ($events_left < $totalEvents) {
                $limit = $servicePermission ['quantity'];
            }

            $evntArgs = array(
                'entity' => 'ZSELEX_Entity_Event',
                'fields' => array(
                    'a.shop_event_id',
                    'a.shop_event_name',
                    'a.shop_event_shortdescription',
                    'a.shop_event_description',
                    'a.shop_event_startdate',
                    'a.shop_event_enddate',
                    'a.event_image',
                    'a.image_height',
                    'a.image_width',
                    'a.event_doc',
                    'a.product_id',
                    'a.showfrom',
                    'a.price',
                    'a.email',
                    'a.event_link',
                    'a.open_new',
                    'a.call_link_directly'
                ),
                'where' => 'a.shop=:shop_id',
                'setParams' => array(
                    'shop_id' => $shop_id
                ),
                'orderby' => 'a.shop_event_id DESC'
            );

            if ($limit > 0) {
                $evntArgs ['offset'] = $limit;
            }


            $events = $this->entityManager->getRepository('ZSELEX_Entity_Event')->fetchAll($evntArgs);
        }

        // echo "<pre>"; print_r($events); echo "</pre>"; exit;
        $after = microtime(true);
        $diff  = $after - $before;
        // echo "Time : " . $diff;

        foreach ($events as $key => $val) {
            if (!empty($val ['event_doc'])) {
                $path_parts                 = pathinfo($val ['event_doc']);
                $doc_ext                    = $path_parts ['extension'];
                $events [$key] ['docExt']   = $path_parts ['extension'];
                $events [$key] ['fileName'] = $path_parts ['filename'];
            }

            if ($val ['showfrom'] == 'product') {
                $shoptype                   = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getShopType(array(
                    'shop_id' => $shop_id
                ));
                $shoptype                   = $shoptype ['shoptype'];
                $events [$key] ['shoptype'] = $shoptype;
                if ($shoptype == 'iSHOP') {
                    $finalproduct                   = $this->entityManager->getRepository('ZSELEX_Entity_Event')->get(array(
                        'entity' => 'ZSELEX_Entity_Product',
                        'fields' => array(
                            'a.prd_image'
                        ),
                        'where' => array(
                            'a.product_id' => $val ['product_id']
                        )
                    ));
                    $events [$key] ['productImage'] = $finalproduct ['prd_image'];
                } elseif ($shoptype == 'zSHOP') {
                    $obj                            = $this->entityManager->getRepository('ZSELEX_Entity_Product')->get(array(
                        'entity' => 'ZSELEX_Entity_ZenShop',
                        'where' => array(
                            'a.shop' => $shop_id
                        )
                    ));
                    $zencart                        = $obj;
                    $zenproduct                     = ModUtil::apiFunc('ZSELEX',
                            'admin', 'getZenCartProduct',
                            array(
                            'shop' => $zencart,
                            'shop_id' => $shop_id,
                            'product_id' => $val ['product_id']
                    ));
                    // echo "<pre>"; print_r($zenproduct); echo "</pre>";
                    $events [$key] ['productImage'] = $zenproduct ['products_image'];
                    $events [$key] ['zencart']      = $zencart;
                }
            }
        }


        $this->view->assign('events_left', $events_left);

        $event_left = $servicePermission['qty_left'];
        $expired    = $servicePermission ['expired'];
        $this->view->assign('expired', $expired);
        $this->view->assign('event_perm', $event_perm);

        $this->view->assign('events', $events);

        // echo "<pre>"; print_r($events); echo "</pre>"; exit;




        $image_args        = array(
            'shop_id' => $shop_id,
            'type' => 'minisiteimages',
            'disablecheck' => true
        );
        $image_service     = $this->servicePermission($image_args);
        $this->view->assign('image_service', $image_service);
        // echo "<pre>"; print_r($image_service); echo "</pre>";
        $diskquota_service = ModUtil::apiFunc('ZSELEX', 'admin',
                'checkDiskquota',
                $args              = array(
                'shop_id' => $shop_id
        ));
        // echo "<pre>"; print_r($diskquota_service); echo "</pre>"; exit;
        $this->view->assign('diskquota_service', $diskquota_service);
        $event_limit       = 1;
        if ($diskquota_service ['error']) {
            $event_limit = 0;
        }
        $current_theme = System::getVar('Default_Theme');
        $this->view->assign('current_theme', $current_theme);
        $this->view->assign('event_limit', $event_limit);
        // echo "comes here";
        return $this->view->fetch('admin/event/eventpage.tpl');
    }

    /**
     * Save events
     *
     * @param type $args
     * @return Redirect if success
     */
    public function saveEvents($args)
    {
        $formElements = FormUtil::getPassedValue('formElements',
                isset($args ['formElements']) ? $args ['formElements'] : null,
                'REQUEST');
        $formElements = $this->purifyHtml($formElements);
        // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;
        // echo "<pre>"; print_r($_POST); echo "</pre>";
        $ownerName    = $this->ownername;
        $user_id      = UserUtil::getVar('uid');
        $repo         = $this->entityManager->getRepository('ZSELEX_Entity_Event');

        // echo $ownerName; exit;
        $eventId = $formElements ['elemId'];
        if ($eventId != 'new') {

            // echo "<pre>"; print_r($event_item); echo "</pre>"; exit;
            $event_item = array();
            if ($eventId > 0) {
                $getArgs    = array(
                    'entity' => 'ZSELEX_Entity_Event',
                    'fields' => array(
                        'a.shop_event_id',
                        'a.shop_event_name',
                        'a.shop_event_shortdescription',
                        'a.event_urltitle',
                        'a.shop_event_description',
                        'a.shop_event_startdate',
                        'a.shop_event_enddate',
                        'a.activation_date',
                        'a.shop_event_starthour',
                        'a.event_image',
                        'a.image_height',
                        'a.image_width',
                        'a.event_doc',
                        'a.product_id',
                        'a.showfrom',
                        'a.price',
                        'a.email',
                        'a.shop_event_endhour',
                        'a.shop_event_startminute',
                        'a.shop_event_endminute',
                        'a.exclusive',
                        'a.phone'
                    ),
                    'where' => array(
                        'a.shop_event_id' => $eventId
                    )
                );
                $event_item = $repo->get($getArgs);
            }
            // echo "<pre>"; print_r($event_item); echo "</pre>"; exit;
        } else {
            $event_item = array();
        }

        // echo "<pre>"; print_r($event_item); echo "</pre>"; exit;

        if ($_POST ['action'] == 'saveevents') {
            // $this->checkCsrfToken();
            // echo "comes here"; exit;
            // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;
            // $this->checkCsrfToken();
            // echo "<pre>"; print_r($event_item); echo "</pre>"; exit;
            $src         = FormUtil::getPassedValue('src', null, 'REQUEST');
            // echo $src; exit;
            $event_price = ModUtil::apiFunc('ZSELEX', 'user', 'getAmount',
                    array(
                    'money' => isset($formElements ['price']) ? $formElements ['price']
                            : 0
            ));

            $urltitle = strtolower($formElements ['eventname']);
            $urltitle = ZSELEX_Util::cleanTitle($urltitle);
            $urlCount = $repo->getCount2(array(
                'entity' => 'ZSELEX_Entity_Event',
                'field' => 'shop_event_id',
                //   'where' => "a.event_urltitle=:urltitle AND a.shop_event_id!=".$formElements ['elemId'],
                'where' => "a.event_urltitle=:urltitle AND a.shop_event_id!=:event_id",
                'setParams' => array(
                    'urltitle' => $urltitle,
                    'event_id' => $eventId
                )
            ));
            //echo $urlCount; exit;
            if ($urlCount > 0) {
                // $args_url = array('table' => 'zselex_shop_events', 'title' => $urltitle, 'field' => 'event_urltitle');
                // $final_urltitle = $this->increment_url($args_url);
                $args_url       = array(
                    'title' => $urltitle,
                    'event_id' => $formElements ['elemId']
                );
                $final_urltitle = $repo->increment_event_url($args_url);
                // echo $final_urltitle; exit;
                // echo "comes1"; exit;
            } else {


                $final_urltitle = $urltitle;
            }

            // echo $final_urltitle; exit;

            $item = array(
                'shop_event_id' => $formElements ['elemId'],
                'shop' => $formElements ['shop_id'],
                'shop_event_name' => $formElements ['eventname'],
                'event_urltitle' => $final_urltitle,
                'shop_event_shortdescription' => $formElements ['eventshortinto'],
                'shop_event_description' => $formElements ['eventdetail'],
                'news_article_id' => $formElements ['article'],
                'shop_event_keywords' => $formElements ['keywords'],
                'shop_event_startdate' => $formElements ['startdate'],
                'shop_event_starthour' => $formElements ['starthour'],
                'shop_event_startminute' => $formElements ['startminute'],
                'shop_event_enddate' => $formElements ['enddate'],
                'shop_event_endhour' => $formElements ['endhour'],
                'shop_event_endminute' => $formElements ['endminute'],
                'activation_date' => $formElements ['activation_date'],
                'product_id' => $formElements ['product'],
                // 'showfrom' => $formElements['showfrom'],
                'price' => $event_price,
                'event_link' => $formElements ['event_link'],
                'open_new' => $formElements ['open_new'],
                'call_link_directly' => $formElements ['call_link_directly'],
                'email' => $formElements ['email'],
                'phone' => $formElements ['phone'],
                'shop_event_venue' => $formElements ['venue'],
                'status' => $formElements ['status'],
                'contact_name' => $formElements ['contact_name'],
            );

            $serviceargs_exl = array(
                'shop_id' => $formElements ['shop_id'],
                'type' => 'exclusiveevent',
                'disablecheck' => true
            );
            $exclusive_event = $this->servicePermission($serviceargs_exl);
            if (isset($formElements ['showfrom'])) {
                $item ['showfrom'] = $formElements ['showfrom'];
            }
            if ($exclusive_event ['perm']) {
                $item ['exclusive'] = $formElements ['exclusive'];
            }

            // echo "<pre>"; print_r($item); echo "</pre>"; exit;

            if ($eventId != 'new') {
                $insert_id = $formElements ['elemId'];
                // echo "old"; exit;
                // $item = array_filter($item);
                $upd_args  = array(
                    'entity' => 'ZSELEX_Entity_Event',
                    'fields' => $item,
                    'where' => array(
                        'a.shop_event_id' => $formElements ['elemId']
                    )
                );
                // 'where' => "a.cart_id=:cart_id"
                // echo "<pre>"; print_r($item); echo "</pre>"; exit;
                $result    = $repo->updateEntity($upd_args);
                if ($result) {
                    $urlArr    = array(
                        'event_id' => $insert_id,
                        'event_title' => $event_item ['event_urltitle'],
                        'shop_id' => $formElements ['shop_id']
                    );
                    $updateUrl = $this->entityManager->getRepository('ZSELEX_Entity_Url')->updateEventUrl($urlArr);


                    $baseUrl = pnGetBaseURL();
                    if ($_SERVER ['SERVER_NAME'] == 'localhost') {
                        $path = $_SERVER ['DOCUMENT_ROOT']."/zselex/scripts/event_sync.php shop_id=$formElements[shop_id] event_id=$formElements[elemId] from=$formElements[startdate] end=$formElements[enddate] base_url=$baseUrl";
                        // $cmd  = "php ".$path;
                    } else {
                        $path = $_SERVER ['DOCUMENT_ROOT']."/scripts/event_sync.php shop_id=$formElements[shop_id] event_id=$formElements[elemId] from=$formElements[startdate] end=$formElements[enddate] base_url=$baseUrl";
                        //  $cmd  = "/usr/bin/php -c php.ini ".$path;
                    }
                    $cmd = "php ".$path;
                    // exec("/usr/bin/php -c php.ini ".$path." > /dev/null &");
                    // echo $cmd; exit;
                    ZSELEX_Util::execInBackground($cmd);



                    if ($event_item ['exclusive'] && !$formElements ['exclusive']) {
                        $args_exclusive          = array(
                            'shop_id' => $formElements ['shop_id'],
                            'servicetype' => 'exclusiveevent',
                            'user_id' => $user_id
                        );
                        $deleteservice_exclusive = ModUtil::apiFunc('ZSELEX',
                                'admin', 'deleteService', $args_exclusive);
                    } elseif (!$event_item ['exclusive'] && $formElements ['exclusive']) {
                        $serviceupdatearg = array(
                            'user_id' => $user_id,
                            'type' => 'exclusiveevent',
                            'shop_id' => $formElements ['shop_id']
                        );
                        $serviceavailed   = ModUtil::apiFunc('ZSELEX', 'admin',
                                'updateServiceUsed', $serviceupdatearg);
                    }

                    if ($formElements ['showfrom'] == 'product') {
                        @unlink('zselexdata/'.$formElements ['shop_id'].'/events/fullsize/'.$event_item ['event_image']);
                        @unlink('zselexdata/'.$formElements ['shop_id'].'/events/medium/'.$event_item ['event_image']);
                        @unlink('zselexdata/'.$formElements ['shop_id'].'/events/thumb/'.$event_item ['event_image']);
                        @unlink('zselexdata/'.$formElements ['shop_id'].'/events/docs/'.$event_item ['event_doc']);

                        @$fileInfo = pathinfo($event_item ['event_doc']);
                        // $fileExt = $fileInfo['extension'];
                        @$fileBase = $fileInfo ['filename'];
                        @unlink('zselexdata/'.$formElements ['shop_id'].'/events/docs/fullsize/'.$fileBase.'.jpg');
                        @unlink('zselexdata/'.$formElements ['shop_id'].'/events/docs/medium/'.$fileBase.'.jpg');
                        @unlink('zselexdata/'.$formElements ['shop_id'].'/events/docs/thumb/'.$fileBase.'.jpg');
                    }
                }
            } else { // new
                // echo "new"; exit;
                // echo "<pre>"; print_r($item); echo "</pre>"; exit;
                $create_args = array(
                    'table' => 'zselex_shop_events',
                    'element' => $item,
                    'Id' => 'shop_event_id'
                );

                // Create the event
                // $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $create_args);
                $result    = $this->entityManager->getRepository('ZSELEX_Entity_Event')->createEvent($item);
                $insert_id = $result;
                if ($result) {
                    $dateRange    = ZSELEX_Util::createDateRangeArray($formElements ['startdate'],
                            $formElements ['enddate']);
                    // echo "<pre>"; print_r($dateRange); echo "</pre>"; exit;
                    $setEventTemp = $repo->updateEventTemp(array(
                        'shop_id' => $formElements ['shop_id'],
                        'event_id' => $result,
                        'dates' => $dateRange
                    ));

                    if (isset($formElements ['exclusive'])) {
                        $serviceupdatearg = array(
                            'user_id' => $user_id,
                            'type' => 'exclusiveevent',
                            'shop_id' => $formElements ['shop_id']
                        );
                        $serviceavailed   = ModUtil::apiFunc('ZSELEX', 'admin',
                                'updateServiceUsed', $serviceupdatearg);
                    }
                }
            }

            if ($result) {
                $keyRepo = $this->entityManager->getRepository('ZSELEX_Entity_Keyword');
                $keyRepo->updateEventKeywords(array(
                    'event_id' => $insert_id,
                    'keywords' => $formElements ['keywords'],
                    'shop_id' => $formElements ['shop_id']
                ));
                LogUtil::registerStatus($this->__('Done! Event saved succesfully!'));
                if ($src == 'view') {
                    return $this->redirect(ModUtil::url('ZSELEX', 'user',
                                'viewevent',
                                array(
                                'shop_id' => $formElements ['shop_id'],
                                'eventId' => $formElements ['elemId']
                    )));
                }
                return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'events',
                            array(
                            'shop_id' => $formElements ['shop_id']
                )));
            }
        } elseif ($_POST ['action'] == 'deleteevent') {
            // echo $ownername; exit;
            // echo 'deleteevent'; exit;
            $src     = FormUtil::getPassedValue('src', null, 'REQUEST');
            $eventId = $formElements ['elemId'];
            $shop_id = $formElements ['shop_id'];

            $item_clear = array(
                'shop_event_id' => $formElements ['elemId'],
                'shop_id' => $formElements ['shop_id'],
                'shop_event_name' => '',
                'shop_event_shortdescription' => '',
                'shop_event_description' => '',
                'news_article_id' => '',
                'shop_event_keywords' => '',
                'shop_event_startdate' => '',
                'shop_event_starthour' => '',
                'shop_event_startminute' => '',
                'shop_event_enddate' => '',
                'shop_event_endhour' => '',
                'shop_event_endminute' => '',
                'event_image' => '',
                'event_doc' => '',
                'product_id' => '',
                'showfrom' => '',
                'price' => '',
                'email' => '',
                'phone' => '',
                'shop_event_venue' => '',
                'status' => 0
            );

            /*
             * $del_args = array(
             * 'table' => 'zselex_shop_events',
             * 'IdValue' => $eventId,
             * 'IdName' => 'shop_event_id'
             * );
             */

            $del_args    = array(
                'entity' => 'ZSELEX_Entity_Event',
                'where' => array(
                    'a.shop_event_id' => $eventId
                )
            );
            $deleteEvent = $this->entityManager->getRepository('ZSELEX_Entity_Event')->deleteEntity($del_args);

            // echo $event_item['exclusive']; exit;
            // if (ModUtil::apiFunc('ZSELEX', 'admin', 'deleteItem', $del_args)) {
            if ($deleteEvent) {
                $this->entityManager->getRepository('ZSELEX_Entity_Event')->deleteEntity(null,
                    'ZSELEX_Entity_EventTemp',
                    array(
                    'a.event' => $eventId
                ));
                /*
                 * $where_keywords = "WHERE type='eventservice' AND type_id=$eventId";
                 * $delete_keywords = DBUtil::deleteWhere('zselex_keywords', $where_keywords);
                 */

                $keyw_args       = array(
                    'entity' => 'ZSELEX_Entity_Keyword',
                    'where' => array(
                        'a.type_id' => $eventId,
                        'a.type' => 'eventservice'
                    )
                );
                $delete_keywords = $this->entityManager->getRepository('ZSELEX_Entity_Keyword')->deleteEntity($keyw_args);

                unlink('zselexdata/'.$shop_id.'/events/'.$event_item ['event_image']);
                unlink('zselexdata/'.$shop_id.'/events/fullsize/'.$event_item ['event_image']);
                unlink('zselexdata/'.$shop_id.'/events/medium/'.$event_item ['event_image']);
                unlink('zselexdata/'.$shop_id.'/events/thumb/'.$event_item ['event_image']);
                unlink('zselexdata/'.$shop_id.'/events/docs/'.$event_item ['event_doc']);
                $docName = basename($event_item ['event_doc'], '.pdf');
                // echo $docName; exit;
                @unlink('zselexdata/'.$shop_id.'/events/docs/thumb/'.$docName.'.jpg');
                @unlink('zselexdata/'.$shop_id.'/events/docs/medium/'.$docName.'.jpg');

                $args          = array(
                    'shop_id' => $shop_id,
                    'servicetype' => 'eventservice'
                );
                $deleteservice = ModUtil::apiFunc('ZSELEX', 'admin',
                        'deleteService', $args);

                if ($event_item ['exclusive']) {
                    $args_exclusive          = array(
                        'shop_id' => $shop_id,
                        'servicetype' => 'exclusiveevent'
                    );
                    $deleteservice_exclusive = ModUtil::apiFunc('ZSELEX',
                            'admin', 'deleteService', $args_exclusive);
                }
                // Success
                LogUtil::registerStatus($this->__('Done! Deleted Event.'));
                if ($src == 'view') {
                    return $this->redirect(ModUtil::url('ZSELEX', 'user',
                                'site',
                                array(
                                'shop_id' => $formElements ['shop_id']
                    )));
                }
                return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'events',
                            array(
                            'shop_id' => $formElements ['shop_id']
                )));
            }
        }
    }

    public function products($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());

        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $this->reminderNotifications($shop_id);
        $productRepo     = $this->entityManager->getRepository('ZSELEX_Entity_Product');
        $payment_enabled = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->paymentsEnabled($shop_id);
        if (!$payment_enabled) {
            LogUtil::registerError($this->__("You must enable at least one payment gateway. Please go to ")." <a href='".ModUtil::url('ZSELEX',
                    'admin', 'shopsettings',
                    array(
                    'shop_id' => $shop_id
                ))."'>".$this->__("shop settings")."</a>");
        }

        // echo "<pre>"; print_r($_REQUEST); echo "</pre>";
        $bulkaction = (int) FormUtil::getPassedValue('news_bulkaction_select',
                0, 'POST');
        $pids       = FormUtil::getPassedValue('prod_ids', array(), 'POST');
        if ($pids) {
            // echo $shop_id; exit;
            // echo "<pre>"; print_r($pids); echo "</pre>";
            foreach ($pids as $pid) {
                // $this->delete_product_popup(array('product_id' => $pid, 'shop_id' => $shop_id));
                shell_exec("/usr/bin/php"." ".$this->delete_product_popup(array(
                        'product_id' => $pid,
                        'shop_id' => $shop_id
                )));
            }
        }

        $sort           = array();
        $fields         = array(
            'product_id',
            'product_name',
            'prd_status',
            'cr_date',
            'lu_date'
        ); // possible sort fields
        $user_id        = UserUtil::getVar('uid');
        $disabled       = "no";
        $disable        = '';
        $message        = '';
        $servicecount   = 0;
        $error          = 0;
        $servicedisable = false;
        $minishop       = ModUtil::apiFunc('ZSELEX', 'admin', 'minishopExist',
                $args           = array(
                'shop_id' => $shop_id
        ));
        $ownerName      = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args           = array(
                'shop_id' => $shop_id
        ));
        $this->view->assign('ownerName', $ownerName);

        // $args_del_extra_service = array('ownername' => $ownerName, 'shop_id' => $shop_id);
        // $check = $this->deleteExtraProductServices($args_del_extra_service);
        // $uploadpath = $_SERVER['DOCUMENT_ROOT'] . "/zselexdata/" . $ownerName . "/";
        // $uploadpath = "zselexdata/" . $ownerName . "/";
        $uploadpath = "zselexdata/".$shop_id."/";
        if ($_SERVER ['SERVER_NAME'] == 'localhost') {
            $uploadpath = "zselexdata/".$shop_id."/";
        }
        $this->view->assign('uploadpath', $uploadpath);

        $admin = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN);
        $this->view->assign('admin', $admin);

        $disabled = "no";
        $template = 'productspage.tpl';

        $serviceargs       = array(
            'shop_id' => $shop_id,
            'user_id' => $user_id,
            'type' => 'addproducts'
        );
        $servicePermission = $this->servicePermission($serviceargs);
        $this->view->assign('service', $servicePermission);
        // echo "<pre>"; print_r($servicePermission); echo "</pre>";
        $servicecount += $servicePermission ['perm'];

        if ($servicePermission ['perm'] < 1) {
            // $template = 'viewproducts_noservice.tpl';
            //echo "comes here";
            $message = $servicePermission ['message'];
            $error ++;
            LogUtil::registerError(nl2br($message));
        }

        if ($this->serviceDisabled('addproducts') < 1) {
            if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $servicedisable = true;
                $error ++;
                $disable        = "disabled";
            }
            $message = $this->__("This service is currently disabled");
            LogUtil::registerError(nl2br($message));
        }
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            $expired = $servicePermission ['expired'];
        }

        $serviceQuantity = $servicePermission ['quantity'];
        $serviceStatus   = $servicePermission ['service_status'];

        $servicecheck = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow',
                $args         = array(
                'table' => 'zselex_serviceshop',
                'where' => array(
                    "shop_id=$shop_id",
                    "type='addproducts'"
                )
        ));

        // $servicelimit = $servicecheck['quantity'] - $servicecheck['availed'];

        $current_theme = System::getVar('Default_Theme');
        $this->view->assign('current_theme', $current_theme);
        $this->view->assign('servicecount', $servicecount);
        $this->view->assign('quantity', $servicecheck ['quantity']);
        // $this->view->assign('servicelimit', $servicelimit);
        $this->view->assign('servicedisable', $servicedisable);
        $this->view->assign('expired', $expired);
        $this->view->assign('serviceerror', $error);
        $this->view->assign('disable', $disable);
        $this->view->assign('message', $message);

        $itemsperpage            = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 20,
                'GETPOST');
        $startnum                = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $prodArgs ['startlimit'] = $startnum;
        $prodArgs ['offset']     = $itemsperpage;
        // $startnum = 7;
        $status                  = FormUtil::getPassedValue('status',
                isset($args ['status']) ? $args ['status'] : null, 'GETPOST');
        $order                   = FormUtil::getPassedValue('order',
                isset($args ['order']) ? $args ['order'] : 'cr_date', 'GETPOST');
        $original_sdir           = FormUtil::getPassedValue('sdir',
                isset($args ['sdir']) ? $args ['sdir'] : 1, 'GETPOST');

        $searchtext = FormUtil::getPassedValue('searchtext',
                isset($args ['searchtext']) ? $args ['searchtext'] : null,
                'GETPOST');
        $this->view->assign('searchtext', $searchtext);
        $JOINS      = '';
        $category   = FormUtil::getPassedValue('category', null, 'GETPOST');
        if ($category > 0) {
            $JOINS .= " INNER JOIN zselex_product_to_category pc ON pc.product_id=s.product_id AND pc.category_id=$category ";
            $prodArgs ['joins'] []               = 'INNER JOIN a.product_to_category c';
            $prodArgs ['where'] ['c.prd_cat_id'] = $category;
        }
        $manufacturer = FormUtil::getPassedValue('manufacturer', null, 'GETPOST');

        $this->view->assign('startnum', $startnum);
        $this->view->assign('itemsperpage', $itemsperpage);
        $this->view->assign('order', $order);
        $this->view->assign('sdir', $original_sdir);
        $this->view->assign('category', $category);
        $this->view->assign('manufacturer', $manufacturer);

        $aStatus = array(
            'InActive',
            'Active'
        );
        $this->view->assign('aStatus', $aStatus);

        $sdir = $original_sdir ? 0 : 1; // if true change to false, if false change to true
        // change class for selected 'orderby' field to asc/desc
        if ($sdir == 0) {
            $sort ['class'] [$order] = 'z-order-desc';
            $orderdir                = 'DESC';
        }
        if ($sdir == 1) {
            $sort ['class'] [$order] = 'z-order-asc';
            $orderdir                = 'ASC';
        }

        // complete initialization of sort array, adding urls
        foreach ($fields as $field) {
            $sort ['url'] [$field] = ModUtil::url('ZSELEX', 'admin', 'products',
                    array(
                    'status' => $status,
                    'filtercats_serialized' => serialize($filtercats),
                    'order' => $field,
                    'shop_id' => $shop_id,
                    'sdir' => $sdir
            ));
        }
        $this->view->assign('sort', $sort);

        $this->view->assign('filter_active', $status);

        // if (UserUtil::getVar('uid') != '3') {
        if (!empty($shop_id)) {
            // $sql .= " AND s.shop_id='" . $shop_id . "'";
            $prodArgs ['where'] ['a.shop'] = $shop_id;
        }

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {

            // $sql .= " AND s.cr_uid='" . UserUtil::getVar('uid') . "'";
        }
        if (isset($status) && $status != "") {
            // $sql .= " AND s.prd_status = " . $status;
            $prodArgs ['where'] ['a.prd_status'] = $status;
        }
        if (isset($searchtext) && $searchtext != "") {
            // $sql .= " AND s.product_name LIKE '%" . DataUtil::formatForStore($searchtext) . "%'";
            $prodArgs ['like'] ['a.product_name'] = "%".DataUtil::formatForStore($searchtext)."%";
        }

        if (isset($category) && $category != "") {
            // $sql .= " AND s.category_id = " . $category;
        }
        if (isset($manufacturer) && $manufacturer != "") {
            // $sql .= " AND s.manufacturer_id = " . $manufacturer;
            $prodArgs ['where'] ['a.manufacturer'] = $manufacturer;
        }

        // $sql.= " LIMIT $startnum , $itemsperpage";

        if (isset($order) && $order != "") {
            // $sql .= " ORDER BY " . $order . " " . $orderdir;
            $prodArgs ['orderby'] = "a.".$order." ".$orderdir;
        }
        // $sql.= " LIMIT $startnum , $itemsperpage";
        // echo $sql;
        // Get all zselex stories
        $total_products = 0;
        $items          = array();
        $tillShown      = $startnum - 1;
        if ($servicePermission ['perm']) {

            $totalCreatedProducts = $productRepo->getCount(null,
                'ZSELEX_Entity_Product', 'product_id',
                array(
                'a.shop' => $shop_id,
            ));

            $qtyLeft      = $serviceQuantity - $totalCreatedProducts;
            $servicelimit = $qtyLeft;
            $this->view->assign('servicelimit', $servicelimit);
            // echo "Total : " .$total_products;
            // echo "qtyLeft : " .$qtyLeft;

            $prodArgs ['entity']   = 'ZSELEX_Entity_Product';
            $prodArgs ['fields']   = array(
                'a.product_id',
                'a.product_name',
                'a.prd_description',
                'a.prd_quantity',
                'a.prd_price',
                'a.discount',
                'a.prd_status',
                'a.prd_image',
                'b.shop_name',
                'b.shop_id'
            );
            // $prodArgs['joins'][] = 'LEFT JOIN a.shop b';
            $prodArgs ['joins'] [] = 'JOIN a.shop b';
            $prodArgs ['groupby']  = 'a.product_id';
            $prodArgs ['paginate'] = true;
            //$prodArgs['print'] = true;
            //  $prodArgs ['offset'] = 3;
            $prodItems             = $productRepo->getAll($prodArgs);
            $items                 = $prodItems ['result'];
            // echo "<pre>"; print_r($items); echo "</pre>";

            $total_products = $prodItems ['count'];

            $itemCount  = count($items);
            // echo "itemCount: ".$itemCount;
            $totalShown = $tillShown + $itemCount;
            // echo "totalShown: ".$totalShown;
            if ($qtyLeft < $totalCreatedProducts) {
                $total_products = $serviceQuantity;
                //  echo "total: ".$total_products;
            }

            if ($totalShown > $total_products) {
                //echo "pulled more";
                $minus         = $totalShown - $total_products;
                $totalThisPage = $itemCount - $minus;
                //echo "totalThisPage: ".$totalThisPage;
                for ($c = 0; $c < $totalThisPage; $c++) {
                    $newItems[] = $items[$c];
                }

                $items = $newItems;
            }

            //echo "<pre>"; print_r($newItems); echo "</pre>";



            if ($qtyLeft < 1) {
                // $template = 'viewproducts_noservice.tpl';
                $message = ($serviceStatus == 2) ? $this->__("Your service limit is over for this service")
                        : $this->__("Your service limit is over for this demo service");
                $error ++;
                LogUtil::registerError(nl2br($message));
            }
        }
        $this->view->assign('qtyLeft', $qtyLeft);
        // Set the possible status for later use
        $itemstatus = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );

        $productItems = array();
        $count        = 1;
        foreach ($items as $item) {
            $options = array();
            if (SecurityUtil::checkPermission('ZSELEX::',
                    "{$item['cr_uid']}::{$item['product_id']}", ACCESS_EDIT)) {
                $options [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'modifyproduct',
                        array(
                        'id' => $item ['product_id'],
                        'shop_id' => $shop_id
                    )),
                    'image' => 'xedit.png',
                    'title' => $this->__('Edit')
                );

                if ((SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['product_id']}",
                        ACCESS_DELETE)) || SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['product_id']}", ACCESS_ADD)) {
                    $options [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin',
                            'deleteproduct',
                            array(
                            'product_id' => $item ['product_id'],
                            'shop_id' => $shop_id
                        )),
                        'image' => '14_layer_deletelayer.png',
                        'title' => $this->__('Delete')
                    );
                }
            }
            $item ['options']  = $options;
            $item ['infuture'] = DateUtil::getDatetimeDiff_AsField($item ['cr_date'],
                    DateUtil::getDatetime(), 6) < 0;
            $productItems []   = $item;
            $count++;
        }

        $shop_id  = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $minishop = DBUtil::selectObjectByID('zselex_minishop', $shop_id,
                'shop_id');

        // echo "<pre>"; print_r($minishop); echo "</pre>";
        // echo "<pre>"; print_r($shopsitems); echo "</pre>";
        // echo "<pre>"; print_r($productItems); echo "</pre>";
        // Assign the items to the template
        $this->view->assign('minId', $minishop ['id']);

        $this->view->assign('productItems', $productItems);
        $this->view->assign('total_products', $total_products);

        // Assign the current status filter and the possible ones
        $this->view->assign('status', $status);
        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('order', $order);

        $categories = $productRepo->getAll(array(
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
        $this->view->assign('categories', $categories);
        // echo "<pre>"; print_r($categories); echo "</pre>";

        $manufacturers = $this->entityManager->getRepository('ZSELEX_Entity_Manufacturer')->getManufacturers(array(
            'shop_id' => $shop_id
        ));
        $this->view->assign('manufacturers', $manufacturers);
        $this->view->assign('admins',
            SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN));

        // echo "<pre>"; print_r($product_options); echo "</pre>";
        // echo $template;
        // Return the output that has been generated by this function
        $this->view->clear_cache('admin/ishopproducts/'.$template);
        $src = FormUtil::getPassedValue('src', null, 'REQUEST');
        if (isset($src)) {
            $source = $src;
        } else {
            $source = '';
        }
        $this->view->assign('source', $source);
        return $this->view->fetch('admin/ishopproducts/'.$template);
    }

    /**
     * Save product from pop up
     * 
     * @param array $args
     * @return redirect
     */
    public function saveProducts($args)
    { // edited today
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        // $this->checkCsrfToken();
        $formElements    = FormUtil::getPassedValue('formElements',
                isset($args ['formElements']) ? $args ['formElements'] : null,
                'POST');
        $formElements    = $this->purifyHtml($formElements);
        // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;
        $product_options = $formElements ['option'];
        // echo "<pre>"; print_r($product_options); echo "</pre>"; exit;

        $productRepo = $this->entityManager->getRepository('ZSELEX_Entity_Product');
        if ($_POST ['action'] == 'saveproduct') {
            $this->checkCsrfToken();

            $getCurrentProduct = $productRepo->get(array(
                'entity' => 'ZSELEX_Entity_Product',
                'fields' => array(
                    'a.urltitle'
                ),
                'where' => array(
                    'a.product_id' => $formElements ['elemId']
                )
            ));
            // echo "<pre>"; print_r($getCurrentItem); echo "</pre>"; exit;

            $oldName     = $formElements ['oldname'];
            $oldUrlTitle = $formElements ['oldurltitle'];
            $src         = FormUtil::getPassedValue('src', null, 'REQUEST');
            // echo $src; exit;
            // $urltitle = strtolower($formElements['urltitle']);
            if (trim($oldName) == trim($formElements ['name'])) {
                $urltitle = strtolower($formElements ['urltitle']);
            } else {
                $urltitle = strtolower($formElements ['name']);
                // $urltitle = str_replace(" ", '-', $urltitle);
                $urltitle = ZSELEX_Util::cleanTitle($urltitle);
            }
            // $final_urltitle = $this->increment_url($title = $urltitle, $table = 'zselex_products', $field = 'urltitle');
            // echo $urltitle; exit;

            $urlCount = $productRepo->getCount2(array(
                'entity' => 'ZSELEX_Entity_Product',
                'field' => 'product_id',
                'where' => "a.urltitle=:urltitle AND a.product_id!=".$formElements ['product_id'],
                'setParams' => array(
                    'urltitle' => $urltitle
                )
            ));

            // echo $urlCount; exit;
            // echo $urltitle; exit;

            if ($urlCount > 0) {
                // echo "title1"; exit;
                // $final_urltitle = $this->increment_url($title = $urltitle, $table = 'zselex_products', $field = 'urltitle');
                $args_url       = array(
                    'table' => 'zselex_products',
                    'title' => $urltitle,
                    'field' => 'urltitle'
                );
                $final_urltitle = $this->increment_url($args_url);
            } else {
                // echo "title2"; exit;
                $final_urltitle = $urltitle;
            }

            // echo $final_urltitle; exit;
            // $original_price = ModUtil::apiFunc('ZSELEX', 'user', 'getAmount', array('money' => isset($formElements['original_price']) ? $formElements['original_price'] : 0));
            // $prd_price = ModUtil::apiFunc('ZSELEX', 'user', 'getAmount', array('money' => isset($formElements['prd_price']) ? $formElements['prd_price'] : 0));
            // echo $formElements['discount']; exit;
            $startnum = $formElements ['startnum'];
            $item     = array(
                'product_id' => $formElements ['elemId'],
                'shop_id' => $formElements ['shop_id'],
                'product_name' => !empty($formElements ['name']) ? $formElements ['name']
                        : '',
                'urltitle' => $final_urltitle,
                'prd_description' => $formElements ['description'],
                'keywords' => $formElements ['keywords'],
                'category_id' => isset($formElements ['category']) ? $formElements ['category']
                        : 0,
                'manufacturer_id' => isset($formElements ['manufacturer']) ? $formElements ['manufacturer']
                        : 0,
                'original_price' => $formElements ['original_price'],
                // 'prd_price' => $formElements['prd_price'],
                'prd_price' => $formElements ['original_price'],
                'discount' => isset($formElements ['discount']) ? $formElements ['discount']
                        : 0,
                // 'prd_quantity' => isset($formElements['quantity']) ? $formElements['quantity'] : 0,
                'enable_question' => isset($formElements ['enable_question']) ? $formElements ['enable_question']
                        : 0,
                'validate_question' => isset($formElements ['validate_question'])
                        ? $formElements ['validate_question'] : 0,
                'prd_question' => isset($formElements ['prd_question']) ? $formElements ['prd_question']
                        : 0,
                'shipping_price' => isset($formElements ['shipping']) ? $formElements ['shipping']
                        : 0,
                'prd_status' => isset($formElements ['status']) ? $formElements ['status']
                        : 0,
                'advertise' => isset($formElements ['advertise']) ? $formElements ['advertise']
                        : 0,
                'no_vat' => isset($formElements ['no_vat']) ? $formElements ['no_vat']
                        : 0,
                'max_discount' => $formElements ['max_discount'],
                'no_delivery' => isset($formElements ['no_delivery']) ? $formElements ['no_delivery']
                        : 0
            );
            if (isset($formElements ['quantity']) && empty($product_options)) {
                $item ['prd_quantity'] = isset($formElements ['quantity']) ? $formElements ['quantity']
                        : 0;
            }


            // echo "<pre>"; print_r($item); echo "</pre>"; exit;
            $prod_cats = $formElements ['prod_cats'];
            // echo "<pre>"; print_r($prod_cats); echo "</pre>"; exit;

            $saveCategories = ModUtil::apiFunc('ZSELEX', 'product',
                    'saveProductCategories',
                    array(
                    'product_id' => $formElements ['elemId'],
                    'categories' => $prod_cats
            ));

            $keywords    = $formElements ['keywords'];
            $productName = $formElements ['name'];
            // echo "<pre>"; print_r($item); echo "</pre>"; exit;
            $InsertId    = $formElements ['elemId'];

            $update_product = $this->entityManager->getRepository('ZSELEX_Entity_Product')->updateProduct($item);
            $result         = $update_product;

            if ($result == true) {
                $urlArr    = array(
                    'product_id' => $InsertId,
                    'product_title' => $getCurrentProduct ['urltitle'],
                    'shop_id' => $formElements ['shop_id']
                );
                $updateUrl = $this->entityManager->getRepository('ZSELEX_Entity_Url')->updateProductUrl($urlArr);

                $saveOptions = ModUtil::apiFunc('ZSELEX', 'product',
                        'saveProductOptions',
                        array(
                        'product_id' => $formElements ['elemId'],
                        'options' => $product_options
                ));

                $saveQtyDiscounts = $productRepo->setQuantityDiscount(array(
                    'product_id' => $formElements ['elemId'],
                    'qty_discounts' => $formElements ['qtydisount']
                ));
                // /////////////////KEYWORDS//////////////////////
                $where            = "WHERE type='addproducts' AND type_id=$InsertId";

                $delKeywords       = $productRepo->deleteEntity(null,
                    'ZSELEX_Entity_Keyword',
                    array(
                    'a.type' => 'product',
                    'a.type_id' => $InsertId
                ));
                // if (DBUtil::deleteWhere('zselex_keywords', $where)) {
                $productKeywords[] = $productName;
                if (!empty($keywords)) {
                    $keywords            = trim(preg_replace('/\s+/', ' ',
                            $keywords));
                    $keywords_for_search = str_replace(",", " ", $keywords);
                    $keywords_for_search = explode(" ", $keywords_for_search);
                    $productKeywords     = array_merge($productKeywords,
                        $keywords_for_search);
                }
                // echo "<pre>"; print_r($productKeywords); echo "</pre>";  exit;
                if ($delKeywords) {

                    // echo "comes here..."; exit;

                    if (!empty($productKeywords)) {

                        foreach ($productKeywords as $keyword) {

                            if (!empty($keyword)) {
                                $keywordExist = $productRepo->getCount(null,
                                    'ZSELEX_Entity_Keyword', 'keyword_id',
                                    array(
                                    'a.keyword' => $keyword
                                ));

                                if ($keywordExist < 1) {
                                    // echo "comes here..."; exit;

                                    $keywordEntity = new ZSELEX_Entity_Keyword();
                                    $keywordEntity->setKeyword($keyword);
                                    $keywordEntity->setType('product');
                                    $keywordEntity->setType_id($InsertId);
                                    $shopObj       = $this->entityManager->find('ZSELEX_Entity_Shop',
                                        $formElements ['shop_id']);
                                    $keywordEntity->setShop($shopObj);
                                    $this->entityManager->persist($keywordEntity);
                                    $this->entityManager->flush();
                                }
                            }
                        }
                    }
                }

                LogUtil::registerStatus($this->__('Done! Product has been updated successfully.'));
            } // /
            if ($src == 'detail') {
                return $this->redirect(ModUtil::url('ZSELEX', 'user',
                            'productview',
                            array(
                            'id' => $formElements ['elemId']
                )));
            }
            return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'products',
                        array(
                        'shop_id' => $formElements ['shop_id'],
                        'startnum' => $startnum
            )));
        } elseif ($_POST ['action'] === 'deleteproduct') {
            $src = FormUtil::getPassedValue('src', null, 'REQUEST');
            // echo $src; exit;

            if ($this->delete_product_popup(array(
                    'product_id' => $formElements ['product_id'],
                    'shop_id' => $formElements ['shop_id']
                )) == true) {
                LogUtil::registerStatus($this->__('Done! Product has been deleted successfully.'));

                if ($src == 'detail') {
                    return $this->redirect(ModUtil::url('ZSELEX', 'user',
                                'shop',
                                array(
                                'shop_id' => $formElements ['shop_id']
                    )));
                }

                return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                            'products',
                            array(
                            'shop_id' => $formElements ['shop_id']
                )));
            }
        }
    }

    public function delete_product_popup($args)
    {
        $productRepo = $this->entityManager->getRepository('ZSELEX_Entity_Product');
        $ownerName   = $this->ownername;
        // echo $ownerName; exit;
        $product_id  = $args ['product_id'];
        $shop_id     = $args ['shop_id'];
        $del_args    = array(
            'table' => 'zselex_products',
            'IdValue' => $product_id,
            'IdName' => 'product_id'
        );

        // $obj = DBUtil::selectObjectByID('zselex_products', $product_id, 'product_id');
        // $obj = DBUtil::selectObjectByID('zselex_product_categories', $product_id, 'prd_cat_id');
        $obj = $productRepo->get(array(
            'entity' => 'ZSELEX_Entity_Product',
            'where' => array(
                'a.product_id' => $product_id
            )
        ));

        // echo "<pre>"; print_r($obj); echo "</pre>"; exit;

        $productRepo->deleteProductCategories($product_id);
        $del_prod = $productRepo->delete('ZSELEX_Entity_Product',
            array(
            'a.product_id' => $product_id
        ));
        // if (ModUtil::apiFunc('ZSELEX', 'admin', 'deleteItem', $del_args)) {
        if ($del_prod) {
            // DBUtil::deleteObjectById('zselex_product_to_category', $product_id, 'product_id');
            // $where_keywords = "WHERE type='addproducts' AND type_id=$product_id";
            // $delete_keywords = DBUtil::deleteWhere('zselex_keywords', $where_keywords);
            $delete_keywords = $productRepo->deleteEntity(null,
                'ZSELEX_Entity_Keyword',
                array(
                'a.type' => 'addproducts',
                'a.type_id' => $product_id
            ));
            @unlink('zselexdata/'.$shop_id.'/products/'.$obj ['prd_image']);
            @unlink('zselexdata/'.$shop_id.'/products/fullsize/'.$obj ['prd_image']);
            @unlink('zselexdata/'.$shop_id.'/products/medium/'.$obj ['prd_image']);
            @unlink('zselexdata/'.$shop_id.'/products/thumb/'.$obj ['prd_image']);

            $user_id       = UserUtil::getVar('uid');
            $args_ser      = array(
                'shop_id' => $shop_id,
                'servicetype' => 'addproducts',
                'user_id' => $user_id
            );
            $deleteservice = ModUtil::apiFunc('ZSELEX', 'admin',
                    'deleteService', $args_ser);
            // DBUtil::deleteWhere('zselex_product_to_category', "product_id=$product_id");
            // DBUtil::deleteWhere('zselex_product_to_options', "product_id=$product_id");
            $productRepo->delete('ZSELEX_Entity_ProductToOption',
                array(
                'a.product' => $product_id
            ));
            // DBUtil::deleteWhere('zselex_product_to_options_values', "product_id=$product_id");
            $productRepo->delete('ZSELEX_Entity_ProductToOptionValue',
                array(
                'a.product' => $product_id
            ));
            // Success

            ModUtil::apiFunc('ZSELEX', 'admin', 'updateMinisite',
                array(
                'shop_id' => $shop_id
            ));
            // Let any hooks know that we have deleted an item
            // $this->notifyHooks(new Zikula_ProcessHook('type.ui_hooks.types.process_delete', $product_id));
            return true;
        }
    }

    public function productAd()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        $shop_id = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : '', 'GETPOST');

        // echo "<pre>"; print_r($get_ad_values); echo "</pre>"; exit;
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        // echo $shop_id; exit;
        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $this->reminderNotifications($shop_id);
        $adRepo       = $this->entityManager->getRepository('ZSELEX_Entity_Advertise');
        $modvariable  = $this->getVars();
        $country_id   = !empty($modvariable ['default_country_id']) ? $modvariable ['default_country_id']
                : 0;
        $country_name = $modvariable ['default_country_name'];
        $this->view->assign('country_id', $country_id);
        $this->view->assign('country_name', $country_name);
        if ($_POST ['submit_ad'] == 1) {
            $level         = "COUNTRY";
            $region_id     = FormUtil::getPassedValue('region_id', null, 'POST');
            $city_id       = FormUtil::getPassedValue('city_id', null, 'POST');
            $ad_level      = FormUtil::getPassedValue('ad_level', null, 'POST');
            $get_ad_values = $this->getAdCost($args          = array(
                'ad_level' => $ad_level
            ));
            if (!empty($region_id)) {
                $level = "REGION";
            }
            if (!empty($city_id)) {
                $level = "CITY";
            }
            // echo $ad_level; exit;
            // echo "<pre>"; print_r($get_ad_values); echo "</pre>"; exit;
            // echo $city_id; exit;
            $this->checkCsrfToken();
            // echo "<pre>"; print_r($_POST); echo "</pre>";
            $ad_name = FormUtil::getPassedValue('ad_name', null, 'POST');

            $item = array(
                'name' => !empty($ad_name) ? $ad_name : 'COUNTRY-DENMARK',
                'adprice_id' => $get_ad_values ['price_id'],
                'advertise_type' => 'productAd',
                'level' => $level,
                'shop_id' => $shop_id,
                'country_id' => $country_id,
                'region_id' => FormUtil::getPassedValue('region_id', null,
                    'POST'),
                'city_id' => FormUtil::getPassedValue('city_id', null, 'POST'),
                // 'area_id' => ($formElements['level'] == 'AREA') ? $area_id : 0,
                // 'maxviews' => !empty($formElements['maxviews']) ? $formElements['maxviews'] : 0,
                // 'maxclicks' => !empty($formElements['maxclicks']) ? $formElements['maxclicks'] : 0,
                // 'startdate' => !empty($formElements['startdate']) ? $formElements['startdate'] : 0,
                // 'enddate' => !empty($formElements['enddate']) ? $formElements['enddate'] : 0,
                // 'description' => isset($formElements['elemtDesc']) ? $formElements['elemtDesc'] : '',
                // 'keywords' => isset($formElements['keywords']) ? $formElements['keywords'] : '',
                'status' => 1
            );

            $item ['ad_level'] = $ad_level;

            $validationerror = ZSELEX_Util::validateProductAd($item);
            //
            if ($validationerror !== false) {
                // log the error found if any
                if ($validationerror !== false) {
                    LogUtil::registerError(nl2br($validationerror));
                }
                // SessionUtil::setVar('item', $item);

                $this->redirect(ModUtil::url('ZSELEX', 'admin', 'productAd',
                        array(
                        'shop_id' => $shop_id
                )));
            } else {
                // As we're not previewing the item let's remove it from the session
                // SessionUtil::delVar('item');
            }

            // echo "<pre>"; print_r($item); echo "</pre>"; exit;
            /*
             * $args = array(
             * 'table' => 'zselex_advertise',
             * 'element' => $item,
             * 'Id' => 'advertise_id'
             * );
             */
            // $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $args);
            $result = $adRepo->createAd($item);
            if ($result) {
                $user             = UserUtil::getVar('uid');
                $serviceupdatearg = array(
                    'used' => $get_ad_values ['price'],
                    'shop_id' => $shop_id
                );
                $serviceavailed   = ModUtil::apiFunc('ZSELEX', 'admin',
                        'updateAdUsed', $serviceupdatearg);
                LogUtil::registerStatus($this->__('Done! Created Ad successfully.'));
            }
        } elseif ($_POST ['reset_ad'] == 1) {
            SessionUtil::delVar('item');
        }
        if (!(int) ($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)));
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $cities       = $adRepo->getAll(array(
            'entity' => 'ZSELEX_Entity_City',
            'fields' => array(
                "a.city_id",
                "a.city_name"
            ),
            'orderby' => 'a.city_name ASC'
        ));
        $itemsperpage = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 40,
                'GETPOST');
        $startnum     = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $this->view->assign('startnum', $startnum);
        $this->view->assign('itemsperpage', $itemsperpage);

        $adArgs ['entity']     = 'ZSELEX_Entity_Advertise';
        $adArgs ['where']      = array(
            'a.shop' => $shop_id
        );
        $adArgs ['fields']     = array(
            'a.advertise_id',
            'a.name',
            'g.identifier price_name',
            'g.price',
            'c.country_name',
            'd.region_name',
            'e.city_name',
            'f.area_name'
        );
        $adArgs ['startlimit'] = $startnum;
        $adArgs ['offset']     = $itemsperpage;
        $adArgs ['joins']      = array(
            'JOIN a.shop b',
            'LEFT JOIN a.country c',
            'LEFT JOIN a.region d',
            'LEFT JOIN a.city e',
            'LEFT JOIN a.area f',
            'JOIN a.adprice g'
        );
        $adArgs ['paginate']   = true;
        $adItems               = $adRepo->getAll($adArgs);
        $product_ads           = $adItems ['result'];
        // echo "<pre>"; print_r($adItems); echo "</pre>";
        // echo "<pre>"; print_r($product_ads); echo "</pre>";
        // echo "<pre>"; print_r($product_ads); echo "</pre>";

        $total_count = $adItems ['count'];
        // echo "<pre>"; print_r($items); echo "</pre>";

        $error             = 0;
        $servicecount      = 0;
        $serviceargs       = array(
            'shop_id' => $shop_id,
            'user_id' => $user_id,
            'type' => 'createad'
        );
        $servicePermission = $this->servicePermission($serviceargs);
        $servicecount += $servicePermission ['perm'];

        if ($servicePermission ['perm'] < 1) {
            // echo "comes here";
            // $template = 'viewadvertise_noservice.tpl';
            $message = $servicePermission ['message'];
            $error ++;
            LogUtil::registerError(nl2br($message));
        }

        if ($this->serviceDisabled('createad') < 1) {
            if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $servicedisable = true;
                $error ++;
                $disable        = "disabled";
            }
            $message = $this->__("This service is currently disabled");
            LogUtil::registerError(nl2br($message));
        }
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            $expired = $servicePermission ['expired'];
        }
        $this->view->assign('servicecount', $servicecount);
        $this->view->assign('servicedisable', $servicedisable);
        $this->view->assign('expired', $expired);

        // echo "<pre>"; print_r($servicePermission); echo "</pre>";

        $servicecheck = $adRepo->get(array(
            'entity' => 'ZSELEX_Entity_ServiceShop',
            'where' => array(
                "a.shop" => $shop_id,
                "a.type" => 'createad'
            )
        ));

        // echo "<pre>"; print_r($servicecheck); echo "</pre>";
        // echo "<pre>"; print_r($servicecount); echo "</pre>";

        $servicelimit = $servicecheck ['quantity'] - $servicecheck ['availed'];

        $this->view->assign('cities', $cities);
        $this->view->assign('product_ads', $product_ads);
        $this->view->assign('total_count', $total_count);
        $this->view->assign('servicelimit', $servicelimit);
        $sess_item = SessionUtil::getVar('item');
        $this->view->assign('item', $sess_item);
        return $this->view->fetch('admin/advertise/product_ad.tpl');
    }

    public function getAdCost($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $ad_level     = $args ['ad_level'];
        $return_array = array();
        $adRepo       = $this->entityManager->getRepository('ZSELEX_Entity_Advertise');
        /*
         * $get = ModUtil::apiFunc('ZSELEX', 'user', 'get', $getargs = array('table' => 'zselex_advertise_price',
         * 'where' => "identifier='" . $ad_level . "'"));
         */
        $get          = $adRepo->get(array(
            'entity' => 'ZSELEX_Entity_AdvertisePrice',
            'fields' => array(
                'a.adprice_id',
                'a.price'
            ),
            'where' => array(
                'a.identifier' => $ad_level
            )
        ));

        $return_array = array(
            'price_id' => $get ['adprice_id'],
            'price' => $get ['price']
        );
        return $return_array;
    }

    public function deleteAd($args)
    {
        $advertise_id = FormUtil::getPassedValue('advertise_id',
                isset($args ['advertise_id']) ? $args ['advertise_id'] : null,
                'REQUEST');
        $shop_id      = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : null, 'REQUEST');
        $user_id      = UserUtil::getVar('uid');
        $adRepo       = $this->entityManager->getRepository('ZSELEX_Entity_Advertise');

        // echo "ads"; exit;
        // $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');

        /*
         * if (empty($confirmation)) {
         * $this->view->assign('IdValue', $advertise_id);
         * $this->view->assign('confirm_title', $this->__f('Delete %s', $this->__('Product AD')));
         * $this->view->assign('confirm_msg', $this->__f('Do you want to delete this %s', $this->__('Product AD')));
         * $this->view->assign('IdName', 'advertise_id');
         * $this->view->assign('shop_id', $shop_id);
         * $this->view->assign('submitFunc', 'deleteAd');
         * $this->view->assign('cancelFunc', 'productAd');
         * // Return the output that has been generated by this function
         * return $this->view->fetch('admin/deletecommon_noheader.tpl');
         * }
         * $this->checkCsrfToken();
         */

        /*
         * $adPrice = ModUtil::apiFunc('ZSELEX', 'user', 'get', $getargs = array('table' => 'zselex_advertise',
         * 'where' => "advertise_id=$advertise_id"));
         */

        $adPrice = $adRepo->get(array(
            'entity' => 'ZSELEX_Entity_Advertise',
            'fields' => array(
                'b.adprice_id'
            ),
            'joins' => array(
                'JOIN a.adprice b'
            ),
            'where' => array(
                'a.advertise_id' => $advertise_id
            )
        ));

        // echo "<pre>"; print_r($adPrice); echo "</pre>"; exit;

        /*
         * $adPriceCost = ModUtil::apiFunc('ZSELEX', 'user', 'get', $getargs = array('table' => 'zselex_advertise_price',
         * 'where' => "adprice_id=$adPrice[adprice_id]"));
         */

        $adPriceCost = $adRepo->get(array(
            'entity' => 'ZSELEX_Entity_AdvertisePrice',
            'where' => array(
                'a.adprice_id' => $adPrice ['adprice_id']
            )
        ));

        // echo $adPriceCost['price']; exit;

        /*
         * if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
         * $item = array(
         * 'advertise_id' => $advertise_id,
         * 'status' => 0
         * );
         * $updateargs = array(
         * 'table' => 'zselex_advertise',
         * 'IdValue' => $advertise_id,
         * 'IdName' => 'advertise_id',
         * 'element' => $item
         * );
         *
         * //ModUtil::apiFunc('ZSELEX', 'admin', 'updateMultipleItem', $args = array('table' => 'zselex_serviceshop', 'values' => array('availed' => 'availed-1'), 'where' => array('user_id' => UserUtil::getVar('uid'), 'shop_id' => $shop_id, 'type' => 'createad'))); // update the service table
         * //$result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement', $updateargs);
         * } else {
         *
         * $result = DBUtil::deleteObjectById('zselex_advertise', $advertise_id, 'advertise_id');
         * }
         */
        // $result = DBUtil::deleteObjectById('zselex_advertise', $advertise_id, 'advertise_id');
        $result = $adRepo->deleteEntity(array(
            'entity' => 'ZSELEX_Entity_Advertise',
            'where' => array(
                'a.advertise_id' => $advertise_id
            )
        ));
        // Delete
        if ($result) {
            $args          = array(
                'shop_id' => $shop_id,
                'cost' => $adPriceCost ['price'],
                'user_id' => $user_id
            );
            $deleteservice = ModUtil::apiFunc('ZSELEX', 'admin',
                    'deleteAdService', $args);
            // Success
            LogUtil::registerStatus($this->__('Done! Ad has been deleted successfully.'));
            ModUtil::apiFunc('ZSELEX', 'admin', 'updateMinisite',
                array(
                'shop_id' => $shop_id
            ));
            // Let any hooks know that we have deleted an item
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'productAd',
                    array(
                    'shop_id' => $shop_id
        )));
    }

    public function payPalReturnServicePaid()
    {
        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');

        // echo "order id : " . $_REQUEST['order_id'] . '<br>'; exit;
        // echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;

        $ordr    = $_REQUEST ['order_id']; // retrieve order id from paypal.
        // $orderno = $_SESSION["ss_last_orderno"];
        $orderno = $ordr;
        $tx      = $_REQUEST ["tx"];

        // $orderno = $_SESSION['checkoutinfo'][order_id];
        // $ppAcc = "r2internation-facilitator@india.com";
        $modvars               = ModUtil::getVar('ZPayment');
        // echo "<pre>"; print_r($modvars); echo "</pre>"; exit;
        $paypal_business_email = $modvars ['Paypal_business_email'];
        $ppAcc                 = $paypal_business_email;
        $test_mode             = $modvars ['Paypal_testmode'];
        $pdt                   = $modvars ['Paypal_pdt'];

        // echo "pdt :"; $pdt; exit;
        // $at = "FGqDnRnt53o_e7z590SMm4qRTKxvoUYAgGIFCI6uQUEZweh9T2PXI2yZ8Vu"; //PDT Identity Token
        // $at = "0MMBakKt3Gl_9PeVYV6OA5QjgXOzav1Ffsh2tUr5FVtz7EYf2rl61yPeQbu"; //PDT Identity Token
        /*
         * $at = $pdt;
         * if ($test_mode) {
         * $url = "https://www.sandbox.paypal.com/cgi-bin/webscr"; //Test
         * } else {
         * $url = "https://www.paypal.com/cgi-bin/webscr"; //Live
         * }
         * $tx = $_REQUEST["tx"]; //this value is return by PayPal
         * //$tx = '95C602744Y395553B';
         * //echo $tx; exit;
         * $cmd = "_notify-synch";
         * $post = "tx=$tx&at=$at&cmd=$cmd";
         *
         * //Send request to PayPal server using CURL
         * $ch = curl_init($url);
         * curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         * curl_setopt($ch, CURLOPT_HEADER, 0);
         * curl_setopt($ch, CURLOPT_TIMEOUT, 30);
         * curl_setopt($ch, CURLOPT_POST, 1);
         * curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
         * curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
         *
         * $result = curl_exec($ch); //returned result is key-value pair string
         * $error = curl_error($ch);
         *
         * if (curl_errno($ch) != 0) //CURL error
         * exit("ERROR: Failed updating order. PayPal PDT service failed.");
         *
         * $longstr = str_replace("\r", "", $result);
         * $lines = split("\n", $longstr);
         * //echo "<pre>"; print_r($lines); echo "</pre>"; exit;
         * //parse the result string and store information to array
         * // if ($lines[0] == "SUCCESS") {
         * //echo "<pre>"; print_r($lines); echo "</pre>"; exit;
         * //successful payment
         * $ppInfo = array();
         * for ($i = 1; $i < count($lines); $i++) {
         * $parts = split("=", $lines[$i]);
         * if (count($parts) == 2) {
         * $ppInfo[$parts[0]] = urldecode($parts[1]);
         * }
         * }
         */
        // echo "<pre>"; print_r($ppInfo); echo "</pre>"; exit;
        // $payment_status = $ppInfo['payment_status'];
        $payment_status = $_REQUEST ['st'];
        $curtime        = gmdate("d/m/Y H:i:s");
        // capture the PayPal returned information as order remarks
        $oremarks       = "##$curtime##\n"."PayPal Transaction InformationÃ¢â‚¬Â¦<br>"."Txn Id: ".$ppInfo ["txn_id"]."<br>"."Txn Type: ".$ppInfo ["txn_type"]."<br>"."Item Number: ".$ppInfo ["item_number"]."<br>"."Payment Date: ".$ppInfo ["payment_date"]."<br>"."Payment Type: ".$ppInfo ["payment_type"]."<br>"."Payment Status: ".$ppInfo ["payment_status"]."<br>"."Currency: ".$ppInfo ["mc_currency"]."<br>"."Payment Gross: ".$ppInfo ["payment_gross"]."<br>"."Payment Fee: ".$ppInfo ["payment_fee"]."<br>"."Payer Email: ".$ppInfo ["payer_email"]."<br>"."Payer Id: ".$ppInfo ["payer_id"]."<br>"."Payer Name: ".$ppInfo ["first_name"]." ".$ppInfo ["last_name"]."<br>"."Payer Status: ".$ppInfo ["payer_status"]."<br>"."Country: ".$ppInfo ["residence_country"]."<br>"."Business: ".$ppInfo ["business"]."<br>"."Receiver Email: ".$ppInfo ["receiver_email"]."<br>"."Receiver Id: ".$ppInfo ["receiver_id"]."<br>";

        // Update database using $orderno, set status to Paid
        // Send confirmation email to buyer and notification email to merchant
        // Redirect to thankyou page
        // echo $oremarks;

        /*
         * $updateOrder = array(
         * 'table' => 'zselex_service_order',
         * 'IdValue' => $order_id,
         * 'fields' => array('status' => "'" . $payment_status . "'"),
         * 'idName' => 'id',
         * 'where' => "order_id='" . $orderno . "'",
         * );
         *
         * $updateOrderId = ModUtil::apiFunc('ZSELEX', 'user', 'updateObject', $updateOrder);
         */
        $updItem       = array(
            'status' => $payment_status,
            'transaction_id' => $tx
        );
        $updateOrderId = $repo->updateEntity(null, 'ZSELEX_Entity_ServiceOrder',
            $updItem, array(
            'a.order_id' => $orderno
        ));
        $shopsId       = $_REQUEST ['cm'];

        // echo "comes here"; exit;
        /*
         * $getBundles = ModUtil::apiFunc('ZSELEX', 'user', 'getAll', $getargs = array(
         * 'table' => 'zselex_service_orderitems',
         * 'fields' => array('order_item_id', 'bundle_id', 'shop_id', 'quantity', 'timer_days'),
         * 'where' => "order_id='" . $orderno . "'"));
         */

        /*
         * $getBundles = $repo->getAll(array('entity' => 'ZSELEX_Entity_ServiceOrderItem',
         * 'fields' => array('a.order_item_id', 'a.bundle_id', 'a.shop_id', 'a.quantity',
         * 'a.timer_days'),
         * 'where' => array('a.order_id' => $orderno),
         * ));
         *
         *
         * $remaining_days = date('t') - date('j');
         * //echo "remaining days :" . $remaining_days; exit;
         * $date = date("Y-m-d");
         * foreach ($getBundles as $key => $val) {
         * $args['bundle_id'] = $val['bundle_id'];
         * $args['quantity'] = $val['quantity'];
         * $args['top_bundle'] = 1;
         * // $args['timer_days'] = $remaining_days;
         * $args['timer_days'] = $val['timer_days'];
         * $args['timer_date'] = $date;
         * $args['shop_id'] = $val['shop_id'];
         * $args['service_status'] = 2;
         * //$configure = ZSELEX_Controller_Base_Admin::configurePaidService($args);
         * //$configure = $this->configureService($args);
         * $configure = $this->addService($args);
         * // DBUtil::deleteWhere('zselex_basket', $where = "shop_id='" . $val['shop_id'] . "' AND bundle_id='" . $val['bundle_id'] . "'");
         * $repo->deleteEntity(null, 'ZSELEX_Entity_ServiceBasket', array('a.shop' => $shop_id, 'a.bundle' => $val['bundle_id']));
         * }
         */
        $txnCount = $this->entityManager->getRepository('ZSELEX_Entity_ServiceOrder')->serviceOrderTxnIdCount(array(
            'txn_id' => $tx,
            'order_id' => $orderno
        ));
        if (!$txnCount) {
            $configureServices = $this->setServices(array(
                'order_id' => $orderno
            ));
        }
        if ($configureServices) {
            LogUtil::registerStatus($this->__('Done! Service(s) configured successfully.'));
        } else {
            LogUtil::registerError($this->__('Sorry! Some error occured.Please contact administrator'));
        }
        $ownerShop = ModUtil::apiFunc('ZSELEX', 'admin', 'ownerShopDetails',
                array());

        if (!$ownerShop ['shop_id']) {
            return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshop'));
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'shopservices',
                    array(
                    'shop_id' => $ownerShop ['shop_id']
        )));

        $this->view->assign('order_id', $orderno);
        $this->view->assign('orderDetails', $orderDetails);
        $this->view->assign('shop_id', $shopsId);
        $this->view->assign('reciept', $oremarks);
        return $this->view->fetch('admin/paypal/thankyou.tpl');
        // }
    }

    public function setServices($args)
    {
        $repo       = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $order_id   = $args ['order_id'];
        $getBundles = $repo->getAll(array(
            'entity' => 'ZSELEX_Entity_ServiceOrderItem',
            'fields' => array(
                'a.order_item_id',
                'a.bundle_id',
                'a.shop_id',
                'a.quantity',
                'a.timer_days'
            ),
            'where' => array(
                'a.order_id' => $order_id
            )
        ));

        $date = date("Y-m-d");
        foreach ($getBundles as $key => $val) {
            $args ['bundle_id']      = $val ['bundle_id'];
            $args ['quantity']       = $val ['quantity'];
            $args ['top_bundle']     = 1;
            $args ['timer_days']     = $val ['timer_days'];
            $args ['timer_date']     = $date;
            $args ['shop_id']        = $val ['shop_id'];
            $args ['service_status'] = 2;

            // $configure = $this->addToDemo($args);
            $configure = $this->addService($args);
            // DBUtil::deleteWhere('zselex_basket', $where = "shop_id='" . $val['shop_id'] . "' AND bundle_id='" . $val['bundle_id'] . "'");
            $repo->deleteEntity(null, 'ZSELEX_Entity_ServiceBasket',
                array(
                'a.shop' => $val ['shop_id'],
                'a.bundle' => $val ['bundle_id']
            ));
        }

        return true;
    }

    /**
     * @Return URL function from paypal
     *
     * @return s order_id , and shop_id
     *         @Updates the order status to success/failed in Order table
     */
    public function payPalReturnServicePaid1()
    {

        // echo "order id : " . $_REQUEST['order_id'] . '<br>'; exit;
        // echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
        $ordr    = $_REQUEST ['order_id']; // retrieve order id from paypal.
        // $orderno = $_SESSION["ss_last_orderno"];
        $orderno = $ordr;

        // $orderno = $_SESSION['checkoutinfo'][order_id];
        // $ppAcc = "r2internation-facilitator@india.com";
        $modvars               = ModUtil::getVar('ZPayment');
        // echo "<pre>"; print_r($modvars); echo "</pre>"; exit;
        $paypal_business_email = $modvars ['Paypal_business_email'];
        $ppAcc                 = $paypal_business_email;
        $test_mode             = $modvars ['Paypal_testmode'];
        $pdt                   = $modvars ['Paypal_pdt'];

        // echo "pdt :"; $pdt; exit;
        // $at = "FGqDnRnt53o_e7z590SMm4qRTKxvoUYAgGIFCI6uQUEZweh9T2PXI2yZ8Vu"; //PDT Identity Token
        // $at = "0MMBakKt3Gl_9PeVYV6OA5QjgXOzav1Ffsh2tUr5FVtz7EYf2rl61yPeQbu"; //PDT Identity Token
        $at = $pdt;
        if ($test_mode) {
            $url = "https://www.sandbox.paypal.com/cgi-bin/webscr"; // Test
        } else {
            $url = "https://www.paypal.com/cgi-bin/webscr"; // Live
        }
        $tx   = $_REQUEST ["tx"]; // this value is return by PayPal
        // $tx = '95C602744Y395553B';
        // echo $tx; exit;
        $cmd  = "_notify-synch";
        $post = "tx=$tx&at=$at&cmd=$cmd";

        // Send request to PayPal server using CURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $result = curl_exec($ch); // returned result is key-value pair string
        $error  = curl_error($ch);

        if (curl_errno($ch) != 0) { // CURL error
            exit("ERROR: Failed updating order. PayPal PDT service failed.");
        }

        $longstr = str_replace("\r", "", $result);
        $lines   = split("\n", $longstr);
        // echo "<pre>"; print_r($lines); echo "</pre>"; exit;
        // parse the result string and store information to array
        if ($lines [0] == "SUCCESS") {
            // echo "<pre>"; print_r($lines); echo "</pre>"; exit;
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
            $curtime        = gmdate("d/m/Y H:i:s");
            // capture the PayPal returned information as order remarks
            $oremarks       = "##$curtime##\n"."PayPal Transaction InformationÃ¢â‚¬Â¦<br>"."Txn Id: ".$ppInfo ["txn_id"]."<br>"."Txn Type: ".$ppInfo ["txn_type"]."<br>"."Item Number: ".$ppInfo ["item_number"]."<br>"."Payment Date: ".$ppInfo ["payment_date"]."<br>"."Payment Type: ".$ppInfo ["payment_type"]."<br>"."Payment Status: ".$ppInfo ["payment_status"]."<br>"."Currency: ".$ppInfo ["mc_currency"]."<br>"."Payment Gross: ".$ppInfo ["payment_gross"]."<br>"."Payment Fee: ".$ppInfo ["payment_fee"]."<br>"."Payer Email: ".$ppInfo ["payer_email"]."<br>"."Payer Id: ".$ppInfo ["payer_id"]."<br>"."Payer Name: ".$ppInfo ["first_name"]." ".$ppInfo ["last_name"]."<br>"."Payer Status: ".$ppInfo ["payer_status"]."<br>"."Country: ".$ppInfo ["residence_country"]."<br>"."Business: ".$ppInfo ["business"]."<br>"."Receiver Email: ".$ppInfo ["receiver_email"]."<br>"."Receiver Id: ".$ppInfo ["receiver_id"]."<br>";

            // Update database using $orderno, set status to Paid
            // Send confirmation email to buyer and notification email to merchant
            // Redirect to thankyou page
            // echo $oremarks;

            $updateOrder = array(
                'table' => 'zselex_service_order',
                'IdValue' => $order_id,
                'fields' => array(
                    'status' => "'".$payment_status."'"
                ),
                'idName' => 'id',
                'where' => "order_id='".$orderno."'"
            );

            $updateOrderId = ModUtil::apiFunc('ZSELEX', 'user', 'updateObject',
                    $updateOrder);
            $shopsId       = $_REQUEST ['cm'];

            // echo "comes here"; exit;
            $getBundles = ModUtil::apiFunc('ZSELEX', 'user', 'getAll',
                    $getargs    = array(
                    'table' => 'zselex_service_orderitems',
                    'fields' => array(
                        'order_item_id',
                        'bundle_id',
                        'shop_id',
                        'quantity',
                        'timer_days'
                    ),
                    'where' => "order_id='".$orderno."'"
            ));

            // echo "comes here"; exit;
            // echo "<pre>"; print_r($getBundles); echo "</pre>"; exit;

            $remaining_days = date('t') - date('j');
            // echo "remaining days :" . $remaining_days; exit;
            $date           = date("Y-m-d");
            foreach ($getBundles as $key => $val) {
                $args ['bundle_id']      = $val ['bundle_id'];
                $args ['quantity']       = $val ['quantity'];
                $args ['top_bundle']     = 1;
                // $args['timer_days'] = $remaining_days;
                $args ['timer_days']     = $val ['timer_days'];
                $args ['timer_date']     = $date;
                $args ['shop_id']        = $val ['shop_id'];
                $args ['service_status'] = 2;
                // $configure = ZSELEX_Controller_Base_Admin::configurePaidService($args);
                // $configure = $this->configureService($args);
                $configure               = $this->addService($args);
                DBUtil::deleteWhere('zselex_basket',
                    $where                   = "shop_id='".$val ['shop_id']."' AND bundle_id='".$val ['bundle_id']."'");
            }

            LogUtil::registerStatus($this->__('Done! Service(s) configured successfully.'));
            $ownerShop = ModUtil::apiFunc('ZSELEX', 'admin', 'ownerShopDetails',
                    array());

            if (!$ownerShop ['shop_id']) {
                return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                            'viewshop'));
            }

            return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                        'shopservices',
                        array(
                        'shop_id' => $ownerShop ['shop_id']
            )));

            $this->view->assign('order_id', $orderno);
            $this->view->assign('orderDetails', $orderDetails);
            $this->view->assign('shop_id', $shopsId);
            $this->view->assign('reciept', $oremarks);
            return $this->view->fetch('admin/paypal/thankyou.tpl');
        }

        // Payment failed
        else {
            // echo "Failed...."; exit;

            $updateOrder = array(
                'table' => 'zselex_service_order',
                // 'IdValue' => $order_id,
                'fields' => array(
                    'status' => 'Failed'
                ),
                'idName' => 'id',
                'where' => "order_id='".$orderno."'"
            );

            $updateOrderId = ModUtil::apiFunc('ZSELEX', 'user', 'updateObject',
                    $updateOrder);
            return $this->view->fetch('admin/paypal/pperror.tpl');
            // Delete order information
            // Redirect to failed page
        }

        // exit;
    }

    public function netaxeptReturn()
    {
        // echo "<pre>"; print_r($_REQUEST); echo "</pre>";
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_COMMENT), LogUtil::getErrorMsgPermission());
        $responseCode  = FormUtil::getPassedValue('responseCode', null,
                'REQUEST');
        $transactionId = FormUtil::getPassedValue('transactionId', null,
                'REQUEST');
        $orderId       = FormUtil::getPassedValue('orderId', null, 'REQUEST');
        $cart_shop_id  = FormUtil::getPassedValue('cart_shop_id', null,
                'REQUEST');
        // echo "nets return url"; exit;
        // echo "OrderID : " . $orderId;

        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');

        $pntables        = pnDBGetTables();
        $column          = $pntables ['zselex_order_column'];
        $where           = "WHERE $column[order_id]='".$orderId."'";
        $nets_where      = "WHERE zselex_order_id='".$orderId."' AND nets_transaction_id='".$transactionId."'";
        $order_status    = '';
        $netaxept_status = '';
        $info            = '';
        // echo $where; exit;

        if ($responseCode == 'OK') {
            // echo $where; exit;
            $order_status    = 'Success';
            $netaxept_status = 'Success';

            $query_call = ModUtil::apiFunc('ZPayment', 'Netaxept', 'query_call',
                    array(
                    'transaction_id' => $transactionId,
                    'type' => 'service'
            ));
            if ($query_call ['error'] == 1) { // Error
                // echo "query_call error occured!!!!"; exit;
                $error_info    = $query_call ['result'] ['detail']->AuthenticationException->Message;
                // echo $error_info . '<br>';
                $order_status  = 'Failed';
                $info          = $query_call ['result']->Error->ResponseText;
                // echo $info . '<br>'; exit;
                /*
                 * $updateOrder = array(
                 * 'table' => 'zselex_service_order',
                 * 'IdValue' => $orderId,
                 * 'fields' => array('status' => $order_status),
                 * 'idName' => 'id',
                 * 'where' => "order_id='" . $orderId . "'",
                 * );
                 *
                 * $updateOrderId = ModUtil::apiFunc('ZSELEX', 'user', 'updateObject', $updateOrder);
                 */
                $updItem       = array(
                    'status' => $order_status
                );
                $updateOrderId = $repo->updateEntity(null,
                    'ZSELEX_Entity_ServiceOrder', $updItem,
                    array(
                    'a.order_id' => $orderId
                ));
                return $this->view->fetch('admin/paypal/pperror.tpl');
            } else {
                if (!empty($query_call ['result']->Error)) { // Error
                    // echo "query_call2 occured!!!!"; exit;
                    $order_status    = 'Failed';
                    $netaxept_status = 'Failed';
                    $info            = $query_call ['result']->Error->ResponseText;
                    /*
                     * $updateOrder = array(
                     * 'table' => 'zselex_service_order',
                     * 'IdValue' => $orderId,
                     * 'fields' => array('status' => $order_status),
                     * 'idName' => 'id',
                     * 'where' => "order_id='" . $orderId . "'",
                     * );
                     *
                     * $updateOrderId = ModUtil::apiFunc('ZSELEX', 'user', 'updateObject', $updateOrder);
                     */
                    $updItem         = array(
                        'status' => $order_status
                    );
                    $updateOrderId   = $repo->updateEntity(null,
                        'ZSELEX_Entity_ServiceOrder', $updItem,
                        array(
                        'a.order_id' => $orderId
                    ));
                    return $this->view->fetch('admin/paypal/pperror.tpl');
                    // echo "Querycall second error : " . $info; exit;
                } else {
                    // QUERY ok, we need to AUTH

                    $auth_call = ModUtil::apiFunc('ZPayment', 'Netaxept',
                            'auth_call',
                            array(
                            'transaction_id' => $transactionId,
                            'type' => 'service'
                    ));

                    // echo $auth_call['error']; exit;
                    if ($auth_call ['error'] == 1) { // Auth Error!
                        // echo "<pre>"; print_r($auth_call); echo "</pre>"; exit;
                        // echo "auth_call error occured!!!!"; exit;
                        $error_info      = "Ukendt fejlkode: ".$auth_call ['result'] ['detail']->BBSException->Result->ResponseCode.", ".$auth_call ['result'] ['faultstring'];
                        $order_status    = 'Failed';
                        $netaxept_status = 'Failed';
                        $info            = $auth_call ['result'] ['faultstring'];
                        /*
                         * $updateOrder = array(
                         * 'table' => 'zselex_service_order',
                         * 'IdValue' => $orderId,
                         * 'fields' => array('status' => $order_status),
                         * 'idName' => 'id',
                         * 'where' => "order_id='" . $orderId . "'",
                         * );
                         *
                         * $updateOrderId = ModUtil::apiFunc('ZSELEX', 'user', 'updateObject', $updateOrder);
                         */
                        $updItem         = array(
                            'status' => $order_status
                        );
                        $updateOrderId   = $repo->updateEntity(null,
                            'ZSELEX_Entity_ServiceOrder', $updItem,
                            array(
                            'a.order_id' => $orderId
                        ));
                        return $this->view->fetch('admin/paypal/pperror.tpl');
                    } else { // Success
                        $order_status      = 'Success';
                        /*
                         * $updateOrder = array(
                         * 'table' => 'zselex_service_order',
                         * 'IdValue' => $orderId,
                         * 'fields' => array('status' => $order_status),
                         * 'idName' => 'id',
                         * 'where' => "order_id='" . $orderId . "'",
                         * );
                         *
                         * $updateOrderId = ModUtil::apiFunc('ZSELEX', 'user', 'updateObject', $updateOrder);
                         */
                        $updItem           = array(
                            'status' => $order_status,
                            'transaction_id' => $transactionId
                        );
                        $updateOrderId     = $repo->updateEntity(null,
                            'ZSELEX_Entity_ServiceOrder', $updItem,
                            array(
                            'a.order_id' => $orderId
                        ));
                        $configureServices = $this->setServices(array(
                            'order_id' => $orderId
                        ));
                        // exit;
                        // return $this->view->fetch('admin/paypal/thankyou.tpl');
                        if ($configureServices) {
                            LogUtil::registerStatus($this->__('Done! Service(s) configured successfully.'));
                        }
                        $ownerShop = ModUtil::apiFunc('ZSELEX', 'admin',
                                'ownerShopDetails', array());
                        // echo "<br><pre>"; print_r($getBundles); echo "</pre>"; exit;
                        if (!$ownerShop ['shop_id']) {
                            return $this->redirect(ModUtil::url('ZSELEX',
                                        'admin', 'viewshop'));
                        }
                        return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                                    'shopservices',
                                    array(
                                    'shop_id' => $ownerShop ['shop_id']
                        )));
                    } // // succes ends
                }
            } //
            // DBUTil::updateObject($obj, 'zselex_shop', $where);
            // return $this->view->fetch('user/thankyou.tpl');
        } elseif ($responseCode == 'Cancel') {
            $order_status  = "Cancelled";
            /*
             * $updateOrder = array(
             * 'table' => 'zselex_service_order',
             * 'IdValue' => $order_id,
             * 'fields' => array('status' => $order_status),
             * 'idName' => 'id',
             * 'where' => "order_id='" . $order_id . "'",
             * );
             *
             * $updateOrderId = ModUtil::apiFunc('ZSELEX', 'user', 'updateObject', $updateOrder);
             *
             */
            $updItem       = array(
                'status' => $order_status
            );
            $updateOrderId = $repo->updateEntity(null,
                'ZSELEX_Entity_ServiceOrder', $updItem,
                array(
                'a.order_id' => $orderId
            ));

            LogUtil::registerError($this->__('Sorry your order has been cancelled!'));
            $ownerShop = ModUtil::apiFunc('ZSELEX', 'admin', 'ownerShopDetails',
                    array());
            // echo "<br><pre>"; print_r($getBundles); echo "</pre>"; exit;
            if (!$ownerShop ['shop_id']) {
                return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                            'viewshop'));
            }
            return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                        'shopservices',
                        array(
                        'shop_id' => $ownerShop ['shop_id']
            )));
            return $this->view->fetch('admin/paypal/ppcancelled.tpl');
        }  // Payment failed
        else {
            // echo "Failed....";exit

            $order_status  = "Failed";
            // $order_status = "cancelled";
            /*
             * $updateOrder = array(
             * 'table' => 'zselex_service_order',
             * 'IdValue' => $order_id,
             * 'fields' => array('status' => $order_status),
             * 'idName' => 'id',
             * 'where' => "order_id='" . $order_id . "'",
             * );
             *
             * $updateOrderId = ModUtil::apiFunc('ZSELEX', 'user', 'updateObject', $updateOrder);
             */
            $updItem       = array(
                'status' => $order_status
            );
            $updateOrderId = $repo->updateEntity(null,
                'ZSELEX_Entity_ServiceOrder', $updItem,
                array(
                'a.order_id' => $orderId
            ));

            LogUtil::registerError($this->__('Sorry your order has been failed!'));
            $ownerShop = ModUtil::apiFunc('ZSELEX', 'admin', 'ownerShopDetails',
                    array());
            // echo "<br><pre>"; print_r($getBundles); echo "</pre>"; exit;
            if (!$ownerShop ['shop_id']) {
                return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                            'viewshop'));
            }
            return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                        'shopservices',
                        array(
                        'shop_id' => $ownerShop ['shop_id']
            )));
            return $this->view->fetch('admin/paypal/pperror.tpl');
        }
        return $this->redirect(ModUtil::url('ZSELEX', 'user', 'paymentStatus',
                    array(
                    'order_id' => $orderId
        )));
    }

    public function QuickPayCallbackService()
    {
        $order_id       = $_REQUEST ['ordernumber'];
        $state          = $_REQUEST ['state'];
        $qpstatmsg      = $_REQUEST ['qpstatmsg'];
        $transaction_id = $_REQUEST ['transaction'];
        if ($state || $state == 1 || $state == '1') {
            $order_status = 'Success';
            $updateOrder  = array(
                'table' => 'zselex_service_order',
                'IdValue' => $order_id,
                'fields' => array(
                    'status' => $order_status
                ),
                'idName' => 'id',
                'where' => "order_id='".$order_id."'"
            );

            $updateOrderId = ModUtil::apiFunc('ZSELEX', 'user', 'updateObject',
                    $updateOrder);

            return true;
            // exit;
            // return $this->view->fetch('admin/paypal/thankyou.tpl');
            LogUtil::registerStatus($this->__('Done! Service(s) configured successfully.'));
            $ownerShop = ModUtil::apiFunc('ZSELEX', 'admin', 'ownerShopDetails',
                    array());
            // echo "<br><pre>"; print_r($getBundles); echo "</pre>"; exit;
            if (!$ownerShop ['shop_id']) {
                return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                            'viewshop'));
            }
            return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                        'shopservices',
                        array(
                        'shop_id' => $ownerShop ['shop_id']
            )));
        }
    }

    public function QuickPayOkService()
    {
        $order_id = $_REQUEST ['order_id'];
        return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                    'QuickPayRedirect',
                    array(
                    'order_id' => $order_id
        )));
    }

    public function QuickPayRedirect()
    {
        $order_id  = $_REQUEST ['order_id'];
        $order     = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                $getargs   = array(
                'table' => 'zselex_service_order',
                'where' => "order_id='".$order_id."'"
        ));
        $ownerShop = ModUtil::apiFunc('ZSELEX', 'admin', 'ownerShopDetails',
                array());
        // echo "<br><pre>"; print_r($ownerShop); echo "</pre>"; exit;
        if ($order ['status'] == 'Success') {
            $configureServices = $this->setServices(array(
                'order_id' => $order_id
            ));
            if ($configureServices) {
                LogUtil::registerStatus($this->__('Done! Service(s) configured successfully.'));
            }
        } elseif ($order ['status'] == 'Failed') {
            LogUtil::registerError($this->__('Sorry your order has been failed!'));
        } elseif ($order ['status'] == 'Cancelled') {
            LogUtil::registerError($this->__('Sorry your order has been Cancelled!'));
        } else {
            LogUtil::registerStatus($this->__('Your Services will be configured shortly'));
        }
        if (!$ownerShop ['shop_id']) {
            return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshop'));
        }
        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'shopservices',
                    array(
                    'shop_id' => $ownerShop ['shop_id']
        )));
    }

    public function EpayAcceptService()
    {

        // echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');

        // echo "order id : " . $_REQUEST['order_id'] . '<br>'; exit;
        // echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;

        $order_id       = $_REQUEST ['orderid']; // retrieve order id from paypal.
        $shop_id        = $_REQUEST ['shop_id'];
        $transaction_id = $_REQUEST ['txnid'];

        $modvars = ModUtil::getVar('ZPayment');
        // echo "<pre>"; print_r($modvars); echo "</pre>"; exit;

        $payment_status = 'Success';

        $updItem       = array(
            'status' => $payment_status,
            'transaction_id' => $transaction_id
        );
        $updateOrderId = $repo->updateEntity(null, 'ZSELEX_Entity_ServiceOrder',
            $updItem, array(
            'a.order_id' => $order_id
        ));
        // $shopsId = $_REQUEST['cm'];

        $configureServices = $this->setServices(array(
            'order_id' => $order_id
        ));
        if ($configureServices) {
            LogUtil::registerStatus($this->__('Done! Service(s) configured successfully.'));
        } else {
            LogUtil::registerError($this->__('Sorry! Some error occured.Please contact administrator'));
        }
        $ownerShop = ModUtil::apiFunc('ZSELEX', 'admin', 'ownerShopDetails',
                array());

        if (!$ownerShop ['shop_id']) {
            return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshop'));
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'shopservices',
                    array(
                    'shop_id' => $ownerShop ['shop_id']
        )));
    }

    public function EpayCancelService()
    {
        // echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
        LogUtil::registerError($this->__('Sorry your order has been cancelled!'));
        $ownerShop = ModUtil::apiFunc('ZSELEX', 'admin', 'ownerShopDetails',
                array());

        if (!$ownerShop ['shop_id']) {
            return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshop'));
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'shopservices',
                    array(
                    'shop_id' => $ownerShop ['shop_id']
        )));
    }

    public function configureService($args)
    {
        $user_id    = UserUtil::getVar('uid');
        // $serviceId = FormUtil::getPassedValue("serviceId");
        // $serviceType = FormUtil::getPassedValue("servicetype");
        $shop_id    = $args ["shop_id"];
        // $qtybased = FormUtil::getPassedValue("qty_based");
        $quantity   = $args ["quantity"];
        // $servicePrice = FormUtil::getPassedValue("serviceprice");
        // $bundle = FormUtil::getPassedValue("bundle");
        $bundle_id  = $args ["bundle_id"];
        $top_bundle = $args ["top_bundle"];
        // $bundle_type = FormUtil::getPassedValue("bundle_type");
        $timer_days = $args ["timer_days"];
        // $service_status = FormUtil::getPassedValue("service_status");
        $owner      = DBUtil::selectObjectByID('zselex_shop_owners', $shop_id,
                'shop_id');
        $owner_id   = $owner ['user_id'];
        $source     = $args ["source"];

        // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;

        $bundle_detail = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                $getargs       = array(
                'table' => 'zselex_service_bundles',
                'where' => "bundle_id=$bundle_id"
        ));

        // echo "<pre>"; print_r($bundle_detail); echo "</pre>"; exit;

        $servicePrice   = $bundle_detail ["bundle_price"];
        $bundle_type    = $bundle_detail ["bundle_type"];
        $bundle_name    = $bundle_detail ["bundle_name"];
        // $timer_days = $bundle_detail["demoperiod"];
        $service_status = 2;

        // echo "<pre>"; print_r($bundle_detail); echo "</pre>"; exit;

        $date = date("Y-m-d");

        // echo $count; exit;
        if ($bundle_type != 'additional') {
            // echo "comes here!"; exit;
            DBUtil::deleteWhere('zselex_serviceshop_bundles',
                $where = "shop_id=$shop_id AND bundle_type='main'");
            // }
            // DBUtil::deleteWhere('zselex_service_demo', $where = "shop_id=$shop_id AND top_bundle=1");
            // DBUtil::deleteWhere('zselex_service_config', $where = "shop_id=$shop_id AND top_bundle=1");
        }

        // exit;

        $count = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount',
                $args  = array(
                'table' => 'zselex_serviceshop_bundles',
                "where" => "shop_id=$shop_id AND bundle_id='".$bundle_id."'"
        ));

        if ($count < 1) { // main doesnt exist . only additional bundle could be more than 1
            $item   = array(
                'bundle_id' => $bundle_id,
                'bundle_name' => $bundle_name,
                'shop_id' => $shop_id,
                'original_quantity' => $quantity,
                'quantity' => $quantity,
                'service_status' => $service_status,
                'bundle_type' => $bundle_type,
                'timer_date' => $date,
                'timer_days' => $timer_days
            );
            // echo "<pre>"; print_r($item); echo "</pre>"; exit;
            $args   = array(
                'table' => 'zselex_serviceshop_bundles',
                'element' => $item,
                'Id' => 'service_bundle_id'
            );
            $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $args);
        } else {
            // echo "comes here"; exit;
            $get     = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                    $getargs = array(
                    'table' => 'zselex_serviceshop_bundles',
                    'where' => "shop_id=$shop_id AND bundle_id=$bundle_id"
            ));
            // echo "<pre>"; print_r($get); echo "</pre>"; exit;

            $pntables = pnDBGetTables();
            $column   = $pntables ['zselex_serviceshop_bundles_column'];

            $obj    = array(
                'bundle_id' => $bundle_id,
                'original_quantity' => $get [quantity] + 1,
                'quantity' => $get [quantity] + $quantity,
                'timer_date' => $date,
                'timer_days' => $timer_days,
                'service_status' => $service_status
            );
            $where  = "WHERE $column[shop_id]=$shop_id AND $column[bundle_id]=$bundle_id";
            $result = DBUTil::updateObject($obj, 'zselex_serviceshop_bundles',
                    $where);
        }

        if ($result) {
            $main_bundle = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                    array(
                    'table' => 'zselex_serviceshop_bundles',
                    'where' => "shop_id=$shop_id AND bundle_type='main'",
                    'fields' => array(
                        'service_bundle_id',
                        'bundle_id'
                    )
            ));
            if ($count < 1) { // doesnt exists.
                // Insert to demo table. (23-01-14) edited.
            }
            // if ($bundle == 1) {
            $values                 = array(
                'bundle' => $bundle,
                'service_status' => $service_status,
                'user_id' => $user_id,
                'owner_id' => $owner_id,
                'shop_id' => $shop_id,
                'timer_date' => $date
            );
            $bundleitems            = ModUtil::apiFunc('ZSELEX', 'user',
                    'selectArray',
                    $argsbundleitems        = array(
                    'table' => 'zselex_service_bundle_items',
                    'where' => array(
                        "bundle_id=".$bundle_id
                    )
            ));
            $values ['bundleitems'] = $bundleitems;
            $values ['timer_days']  = $timer_days;
            $values ['bundle_type'] = $bundle_type;
            $values ['bundle_id']   = $main_bundle ['bundle_id'];
            $values ['shop_id']     = $shop_id;
            // echo "<pre>"; print_r($values);exit;
            $approvebundlesitems    = $this->insertBundleItemsPaid($values);

            // }
        }
        return true;
    }

    public function paypalServiceOrderCancel($args)
    {
        $order_id = $_REQUEST ['order_id'];

        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        // echo "comes here"; exit;
        $item = array(
            'status' => 'Cancelled'
        );
        /*
         * $updateOrder = array(
         * 'table' => 'zselex_service_order',
         * 'IdValue' => $order_id,
         * 'fields' => array('status' => 'Cancelled'),
         * 'idName' => 'id',
         * 'where' => "order_id='" . $order_id . "'",
         * );
         *
         * ModUtil::apiFunc('ZSELEX', 'user', 'updateObject', $updateOrder);
         */

        $repo->updateEntity(null, 'ZSELEX_Entity_ServiceOrder', $item,
            array(
            'a.order_id' => $order_id
        ));
        return $this->view->fetch('admin/paypal/ppcancelled.tpl');
    }

    public function viewaffiliate($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        if ($_POST) {
            // echo "<pre>"; print_r($product_ads); echo "</pre>";
            $repo        = $this->entityManager->getRepository('ZSELEX_Entity_ShopAffiliation');
            $sort_orders = FormUtil::getPassedValue('sortorder', null, 'GETPOST');
            // echo "<pre>"; print_r($sort_orders); echo "</pre>";

            foreach ($sort_orders as $aff_id => $sort_order) {
                $item = array(
                    'sort_order' => $sort_order
                );

                $result = $repo->updateEntity(null,
                    'ZSELEX_Entity_ShopAffiliation', $item,
                    array(
                    'a.aff_id' => $aff_id
                ));
            }
        }

        $itemsperpage = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 25,
                'GETPOST');
        $startnum     = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $this->view->assign('startnum', $startnum);
        $this->view->assign('itemsperpage', $itemsperpage);

        /*
         * $affiliates = ModUtil::apiFunc('ZSELEX', 'user', 'getAll', $args = array(
         * 'table' => 'zselex_shop_affiliation',
         * //'where' => "shop_id=$shop_id" . $where,
         * 'startnum' => $startnum,
         * 'itemsperpage' => $itemsperpage,
         * 'orderby' => $orberby
         * ));
         */

        $getArgs = array(
            'entity' => 'ZSELEX_Entity_ShopAffiliation',
            'fields' => array(
                'a.aff_id',
                'a.aff_name',
                'a.aff_image',
                'a.cr_date',
                'a.cr_uid',
                'a.lu_date',
                'a.lu_uid',
                'a.sort_order'
            ),
            'startlimit' => $startnum,
            'offset' => $itemsperpage,
            'paginate' => true
        );
        // 'where' => array('a.product_id' => $val['product_id'])

        $items      = $this->entityManager->getRepository('ZSELEX_Entity_Product')->getAll($getArgs);
        $affiliates = $items ['result'];
        // echo "<pre>"; print_r($product_ads); echo "</pre>";
        // echo "<pre>"; print_r($product_ads); echo "</pre>";

        /*
         * $getCountArgs = array(
         * 'table' => 'zselex_shop_affiliation',
         * //'where' => "shop_id=$shop_id" . $where,
         * 'Id' => 'aff_id',
         * 'status' => $status
         * );
         *
         * $total_count = ModUtil::apiFunc('ZSELEX', 'admin', 'countElements', $getCountArgs);
         */
        $total_count = $items ['count'];
        // echo "<pre>"; print_r($items); echo "</pre>";

        $this->view->assign('cities', $cities);
        $this->view->assign('affiliates', $affiliates);
        $this->view->assign('total_count', $total_count);
        $this->view->assign('servicelimit', $servicelimit);
        $sess_item = SessionUtil::getVar('item');
        $this->view->assign('item', $sess_item);
        return $this->view->fetch('admin/affiliate/view_affiliate.tpl');
    }

    public function createaffiliate()
    {
        if ($_POST) {
            $formElements = FormUtil::getPassedValue('formElements',
                    isset($args ['formElements']) ? $args ['formElements'] : null,
                    'POST');
            $formElements = ZSELEX_Util::purifyHtml($formElements);
            /*
             * $item = array(
             * 'aff_name' => $formElements['aff_name'],
             * 'aff_image' => $formElements['aff_image'],
             * );
             *
             * $args = array(
             * 'table' => 'zselex_shop_affiliation',
             * 'element' => $item,
             * 'Id' => 'aff_id'
             * );
             * // Create the zselex type
             * $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $args);
             */
            $affiliate    = new ZSELEX_Entity_ShopAffiliation();
            $affiliate->setAff_name($formElements ['aff_name']);
            $affiliate->setAff_image($formElements ['aff_image']);
            $this->entityManager->persist($affiliate);
            $this->entityManager->flush();
            $result       = $affiliate->getAff_id();
            if ($result != false) {
                LogUtil::registerStatus($this->__('Done! Affiliate has been created successfully.'));
            } else {
                // fail! type not created
                throw new Zikula_Exception_Fatal($this->__('Story not created for unknown reason (Api failure).'));
                return false;
            }
            $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewaffiliate'));
        }
        return $this->view->fetch('admin/affiliate/create_affiliate.tpl');
    }

    public function modifyaffiliate($ags)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        $repo = $this->entityManager->getRepository('ZSELEX_Entity_ShopAffiliation');
        if ($_POST) {
            $formElements = FormUtil::getPassedValue('formElements',
                    isset($args ['formElements']) ? $args ['formElements'] : null,
                    'POST');
            // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;
            $InsertId     = $formElements ['aff_id'];
            // update the type

            $item   = array(
                'aff_id' => $InsertId,
                'aff_name' => $formElements ['aff_name'],
                'aff_image' => $formElements ['aff_image']
            );
            /*
             * $updateargs = array(
             * 'table' => 'zselex_shop_affiliation',
             * 'IdValue' => $InsertId,
             * 'IdName' => 'aff_id',
             * 'element' => $item
             * );
             *
             * $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement', $updateargs);
             */
            $result = $repo->updateEntity(null, 'ZSELEX_Entity_ShopAffiliation',
                $item,
                array(
                'a.aff_id' => $InsertId
            ));
            if ($result != false) {
                LogUtil::registerStatus($this->__('Done! Affiliate has been saved successfully.'));
            } else {
                // fail! type not created
                throw new Zikula_Exception_Fatal($this->__('Story not created for unknown reason (Api failure).'));
                return false;
            }
            $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewaffiliate'));
        }
        // echo "modifycity";
        $aff_id = FormUtil::getPassedValue('aff_id',
                isset($args ['aff_id']) ? $args ['aff_id'] : null, 'GETPOST');

        /*
         * $args = array(
         * 'table' => 'zselex_shop_affiliation',
         * 'IdValue' => $aff_id,
         * 'IdName' => 'aff_id'
         * );
         * // Get the news type in the db
         * $item = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $args);
         */
        $item = $repo->get(array(
            'entity' => 'ZSELEX_Entity_ShopAffiliation',
            'where' => array(
                'a.aff_id' => $aff_id
            )
        ));
        // echo "<pre>"; print_r($item); echo "</pre>";
        $this->view->assign('item', $item);

        return $this->view->fetch('admin/affiliate/create_affiliate.tpl');
    }

    public function deleteaffiliate($args)
    {
        $Id = FormUtil::getPassedValue('aff_id',
                isset($args ['aff_id']) ? $args ['aff_id'] : null, 'REQUEST');

        $user_id = UserUtil::getVar('uid');
        // Validate the essential parameters
        if (empty($Id)) {
            return LogUtil::registerArgsError();
        }

        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
        if (empty($confirmation)) {
            // Add ZSELEX type ID
            $this->view->assign('IdValue', $Id);
            $this->view->assign('confirm_title',
                $this->__f('Delete %s item', $this->__('Affiliate')));
            $this->view->assign('confirm_msg',
                $this->__f('Do you want to delete this %s item',
                    $this->__('Affiliate')));

            $this->view->assign('IdName', 'aff_id'); // edit id param name
            $this->view->assign('submitFunc', 'deleteaffiliate');
            $this->view->assign('cancelFunc', 'viewaffiliate');
            $emptyvar = $this->__('Confirmation prompt'); // just to get the translation out with poedit!!!
            // Return the output that has been generated by this function
            return $this->view->fetch('admin/deletecommon1.tpl');
        }

        $args = array(
            'table' => 'zselex_shop_affiliation',
            'IdValue' => $Id,
            'IdName' => 'aff_id'
        );

        $repo   = $this->entityManager->getRepository('ZSELEX_Entity_ShopAffiliation');
        $delete = $repo->deleteEntity(null, 'ZSELEX_Entity_ShopAffiliation',
            array(
            'a.aff_id' => $Id
        ));
        if ($delete) {
            $user_id = UserUtil::getVar('uid');

            // Success
            LogUtil::registerStatus($this->__('Done! Affiliate has been deleted successfully.'));

            // Let any hooks know that we have deleted an item
            $this->notifyHooks(new Zikula_ProcessHook('type.ui_hooks.types.process_delete',
                $Id));
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewaffiliate'));
    }

    public function saveCategory($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        $formElements = FormUtil::getPassedValue('formElements',
                isset($args ['formElements']) ? $args ['formElements'] : null,
                'POST');
        $owner_id     = $this->owner_id;
        // echo $owner_id; exit;
        $shop_id      = $_REQUEST ['shop_id'];

        $item = array(
            'prd_cat_name' => $formElements ['elemtName'],
            'user_id' => $owner_id,
            'status' => $formElements ['status']
        );

        $args     = array(
            'table' => 'zselex_product_categories',
            'element' => $item,
            'Id' => 'prd_cat_id'
        );
        // Create the zselex type
        $result   = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $args);
        $InsertId = DBUtil::getInsertID($args ['table'], $args ['Id']);

        if ($result == true) {
            // Success
            if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) {
                LogUtil::registerStatus($this->__('Done! Category has been created successfully.'));
            } else {
                LogUtil::registerStatus($this->__('Done! Category details has been updated successfully.'));
            }
            // $this->redirect(ModUtil::url('ZSELEX', 'admin', 'productCategories', array('shop_id' => $shop_id)));
        } else {
            // fail! type not created
            throw new Zikula_Exception_Fatal($this->__('Story not created for unknown reason (Api failure).'));
            return false;
        }

        if ($formElements ['source'] == 'product') {
            $this->redirect(ModUtil::url('ZSELEX', 'admin', 'products',
                    array(
                    'shop_id' => $shop_id
            )));
        } else {
            $this->redirect(ModUtil::url('ZSELEX', 'admin', 'productCategories',
                    array(
                    'shop_id' => $shop_id
            )));
        }
    }

    public function createProductCategory($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        // $aSelectArray = ModUtil::apiFunc('ZSELEX', 'admin', 'getElementsSelectArray');
        // echo "<pre>"; print_r($cities); echo "</pre>";
        if ($_POST) {
            $formElements = FormUtil::getPassedValue('formElements',
                    isset($args ['formElements']) ? $args ['formElements'] : null,
                    'POST');
            // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;
            $owner_id     = $this->owner_id;
            $shop_id      = $_REQUEST ['shop_id'];
            if ($formElements ['elemtName']) { // PLUGIN
                $item = array(
                    'prd_cat_name' => $formElements ['elemtName'],
                    'shop_id' => $shop_id,
                    'user_id' => $owner_id,
                    'status' => $formElements ['status']
                );

                $args     = array(
                    'table' => 'zselex_product_categories',
                    'element' => $item,
                    'Id' => 'prd_cat_id'
                );
                // Create the zselex type
                $result   = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement',
                        $args);
                $InsertId = DBUtil::getInsertID($args ['table'], $args ['Id']);

                if ($result == true) {
                    // Success
                    if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) {
                        LogUtil::registerStatus($this->__('Done! Category has been created successfully.'));
                    } else {
                        LogUtil::registerStatus($this->__('Done! Category details has been updated successfully.'));
                    }
                    $this->redirect(ModUtil::url('ZSELEX', 'admin',
                            'productCategories',
                            array(
                            'shop_id' => $shop_id
                    )));
                } else {
                    // fail! type not created
                    throw new Zikula_Exception_Fatal($this->__('Story not created for unknown reason (Api failure).'));
                    return false;
                }
            }
        }

        return $this->view->fetch('admin/ishopproducts/create_prod_cat.tpl');
    }

    public function createProdCategory()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());

        // echo "modifycity";
        // $cat_id = FormUtil::getPassedValue('category_id', isset($args['category_id']) ? $args['category_id'] : null, 'GETPOST');
        $shop_id = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : null, 'GETPOST');

        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        if ($_POST) {
            $repo         = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
            $formElements = FormUtil::getPassedValue('formElements',
                    isset($args ['formElements']) ? $args ['formElements'] : null,
                    'POST');
            // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;
            $InsertId     = $formElements ['elemId'];
            $shop_id      = $formElements ['shop_id'];
            $cat_exist    = $repo->getCount(null,
                'ZSELEX_Entity_ProductCategory', 'prd_cat_id',
                array(
                'a.prd_cat_name' => $formElements ['elemtName'],
                'a.shop' => $shop_id
            ));
            if ($cat_exist) {
                LogUtil::registerError($this->__('Error! Category already exists'));
                $this->redirect(ModUtil::url('ZSELEX', 'admin',
                        'createProdCategory',
                        array(
                        'shop_id' => $shop_id
                )));
            }

            $user_id = UserUtil::getVar('uid');

            $create_cat = new ZSELEX_Entity_ProductCategory();
            $create_cat->setPrd_cat_name($formElements ['elemtName']);
            $shop       = $this->entityManager->find('ZSELEX_Entity_Shop',
                $shop_id);
            $create_cat->setShop($shop);
            $create_cat->setUser_id($user_id);
            $create_cat->setStatus($formElements ['status']);
            $this->entityManager->persist($create_cat);
            $this->entityManager->flush();
            $result     = $create_cat->getPrd_cat_id();
            // $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement', $updateargs);
            if ($result == true) {
                // Success
                LogUtil::registerStatus($this->__('Done! Category details has been created successfully.'));
            } else {
                // fail! type not created
                throw new Zikula_Exception_Fatal($this->__('Story not created for unknown reason (Api failure).'));
                return false;
            }
            $this->redirect(ModUtil::url('ZSELEX', 'admin', 'productCategories',
                    array(
                    'shop_id' => $shop_id
            )));
        }

        $this->view->assign('shop_id', $shop_id);
        return $this->view->fetch('admin/ishopproducts/create_prod_cat.tpl');
    }

    public function modifyProductCategory()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());

        // echo "modifycity";
        $cat_id  = FormUtil::getPassedValue('category_id',
                isset($args ['category_id']) ? $args ['category_id'] : null,
                'GETPOST');
        $shop_id = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : null, 'GETPOST');

        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        if ($_POST) {
            $formElements = FormUtil::getPassedValue('formElements',
                    isset($args ['formElements']) ? $args ['formElements'] : null,
                    'POST');
            // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;
            $InsertId     = $formElements ['elemId'];
            $shop_id      = $formElements ['shop_id'];
            // update the type
            $item         = array(
                'prd_cat_id' => $formElements ['elemId'],
                'prd_cat_name' => $formElements ['elemtName'],
                'shop_id' => $shop_id,
                'status' => isset($formElements ['status']) ? $formElements ['status']
                        : 0
            );
            $updateargs   = array(
                'table' => 'zselex_product_categories',
                'IdValue' => $InsertId,
                'IdName' => 'prd_cat_id',
                'element' => $item
            );

            $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement',
                    $updateargs);
            if ($result == true) {
                // Success
                LogUtil::registerStatus($this->__('Done! Category details has been updated successfully.'));
            } else {
                // fail! type not created
                throw new Zikula_Exception_Fatal($this->__('Story not created for unknown reason (Api failure).'));
                return false;
            }
            $this->redirect(ModUtil::url('ZSELEX', 'admin', 'productCategories',
                    array(
                    'shop_id' => $shop_id
            )));
        }
        $args = array(
            'table' => 'zselex_product_categories',
            'IdValue' => $cat_id,
            'IdName' => 'prd_cat_id'
        );
        // Get the news type in the db
        $item = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $args);

        $this->view->assign('item', $item);
        $this->view->assign('shop_id', $shop_id);
        return $this->view->fetch('admin/ishopproducts/create_prod_cat.tpl');
    }

    public function deleteProductCategory($args)
    {
        $cat_id  = FormUtil::getPassedValue('category_id',
                isset($args ['category_id']) ? $args ['category_id'] : null,
                'REQUEST');
        $shop_id = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : null, 'REQUEST');
        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Product');

        // echo $shop_id; exit;

        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');

        if (empty($confirmation)) {
            $this->view->assign('IdValue', $cat_id);
            $this->view->assign('confirm_title',
                $this->__f('Delete %s', $this->__('Product Category')));
            $this->view->assign('confirm_msg',
                $this->__f('Do you want to delete this %s',
                    $this->__('Product Category')));
            $this->view->assign('IdName', 'category_id');
            $this->view->assign('shop_id', $shop_id);
            $this->view->assign('submitFunc', 'deleteProductCategory');
            $this->view->assign('cancelFunc', 'productCategories');
            // Return the output that has been generated by this function
            return $this->view->fetch('admin/deletecommon2.tpl');
        }
        $this->checkCsrfToken();

        // $result = DBUtil::deleteObjectById('zselex_product_categories', $cat_id, 'prd_cat_id');
        // $result = $repo->deleteEntity(null, 'ZSELEX_Entity_ProductCategory', array('a.prd_cat_id' => $cat_id));

        $result = true;

        // Delete
        if ($result) {
            // DBUtil::deleteObjectById('zselex_product_to_category', $cat_id, 'category_id');
            $product = $repo->get(array(
                'entity' => 'ZSELEX_Entity_Product',
                'fields' => array(
                    'a.product_id'
                ),
                'joins' => array(
                    'JOIN a.product_to_category b'
                ),
                'where' => array(
                    'b.prd_cat_id' => $cat_id
                )
            ));
            if ($product) {
                $product_id = $product ['product_id'];
                // echo $product_id; exit;
                // echo $product_id; exit;
                $productObj = $this->entityManager->getRepository('ZSELEX_Entity_Product')->find($product_id);
                $catObj     = $this->entityManager->getRepository('ZSELEX_Entity_ProductCategory')->find($cat_id);
                // echo "hiiiii"; exit;
                // echo "<pre>"; print_r($getCat); echo "</pre>"; exit;

                $productObj->removeCategory($catObj);

                // $this->entityManager->persist($productObj);
                $this->entityManager->flush();
            }

            $repo->deleteEntity(null, 'ZSELEX_Entity_ProductCategory',
                array(
                'a.prd_cat_id' => $cat_id
            ));
            // Success
            LogUtil::registerStatus($this->__('Done! Category has been deleted successfully.'));
            // Let any hooks know that we have deleted an item
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                    'productCategories',
                    array(
                    'shop_id' => $shop_id
        )));
    }

    public function productCategories($args)
    {
        // echo "comes heree";
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        // initialize sort array - used to display sort classes and urls
        $shop_id = $_REQUEST ['shop_id'];

        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $productRepo = $this->entityManager->getRepository('ZSELEX_Entity_Product');
        $sort        = array();
        $fields      = array(
            'prd_cat_id',
            'prd_cat_name'
        ); // possible sort fields

        $itemsperpage  = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 10,
                'GETPOST');
        $startnum      = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $status        = FormUtil::getPassedValue('status',
                isset($args ['status']) ? $args ['status'] : null, 'GETPOST');
        $order         = FormUtil::getPassedValue('order',
                isset($args ['order']) ? $args ['order'] : 'prd_cat_id',
                'GETPOST');
        $original_sdir = FormUtil::getPassedValue('sdir',
                isset($args ['sdir']) ? $args ['sdir'] : 1, 'GETPOST');
        $searchtext    = FormUtil::getPassedValue('searchtext',
                isset($args ['searchtext']) ? $args ['searchtext'] : null,
                'GETPOST');
        $this->view->assign('searchtext', $searchtext);

        $this->view->assign('startnum', $startnum);
        $this->view->assign('itemsperpage', $itemsperpage);
        $this->view->assign('order', $order);
        $this->view->assign('sdir', $original_sdir);

        $aStatus = array(
            'InActive',
            'Active'
        );
        $this->view->assign('aStatus', $aStatus);

        $sdir = $original_sdir ? 0 : 1; // if true change to false, if false change to true
        // change class for selected 'orderby' field to asc/desc
        if ($sdir == 0) {
            $sort ['class'] [$order] = 'z-order-desc';
            $orderdir                = 'DESC';
        }
        if ($sdir == 1) {
            $sort ['class'] [$order] = 'z-order-asc';
            $orderdir                = 'ASC';
        }

        // complete initialization of sort array, adding urls
        foreach ($fields as $field) {
            $sort ['url'] [$field] = ModUtil::url('ZSELEX', 'admin',
                    'productCategories',
                    array(
                    'status' => $status,
                    'filtercats_serialized' => serialize($filtercats),
                    'order' => $field,
                    'sdir' => $sdir,
                    'shop_id' => $shop_id
            ));
        }
        $this->view->assign('sort', $sort);

        $this->view->assign('filter_active', $status);

        $owner_id                         = $this->owner_id;
        /*
         * $sql = "SELECT a.* FROM zselex_product_categories AS a
         * WHERE a.prd_cat_id IS NOT NULL AND a.shop_id=$shop_id";
         */
        $prodCatArgs ['where'] ['a.shop'] = $shop_id;
        if (isset($status) && $status != "") {
            // $sql .= " AND a.status = " . $status;
            $prodCatArgs ['where'] ['a.status'] = $status;
        }
        if (isset($searchtext) && $searchtext != "") {
            // $sql .= " AND a.prd_cat_name LIKE '%" . DataUtil::formatForStore($searchtext) . "%'";
            $prodCatArgs ['like'] ['a.prd_cat_name'] = '%'.DataUtil::formatForStore($searchtext).'%';
        }
        if (isset($order) && $order != "") {
            // $sql .= " ORDER BY a." . $order . " " . $orderdir;
            $prodCatArgs ['orderby'] = "a.".$order." ".$orderdir;
        }

        // Get all zselex stories
        /*
         * $getArgs = array(
         * 'sql' => $sql,
         * 'startnum' => $startnum,
         * 'numitems' => $itemsperpage
         * );
         * $items = ModUtil::apiFunc('ZSELEX', 'admin', 'getAll', $getArgs);
         */

        $prodCatArgs ['entity']     = 'ZSELEX_Entity_ProductCategory';
        $prodCatArgs ['startlimit'] = $startnum;
        $prodCatArgs ['offset']     = $itemsperpage;
        $prodCatArgs ['paginate']   = true;
        $prodCats                   = $productRepo->getAll($prodCatArgs);

        $items = $prodCats ['result'];
        // echo "<pre>"; print_r($items); echo "</pre>";
        // echo "<pre>"; print_r($prodCats); echo "</pre>";

        /*
         * $where = " prd_cat_id IS NOT NULL AND shop_id=$shop_id";
         * if (isset($status) && $status != '') {
         * $where .= " AND status = " . $status;
         * }
         * if (isset($searchtext) && $searchtext != "") {
         * $where .= " AND prd_cat_name LIKE '%" . DataUtil::formatForStore($searchtext) . "%'";
         * }
         * $getCountArgs = array(
         * 'table' => 'zselex_product_categories',
         * 'where' => $where,
         * 'Id' => 'prd_cat_id',
         * 'status' => $status
         * );
         *
         * $total_categorys = ModUtil::apiFunc('ZSELEX', 'admin', 'countElements', $getCountArgs);
         */
        $total_categorys = $prodCats ['count'];

        // Set the possible status for later use
        $itemstatus = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );

        $categorysitems = array();
        foreach ($items as $item) {
            $options = array();

            // $options[] = array('url' => ModUtil::url('ZSELEX', 'admin', 'displaycategory', array('category_id' => $item['category_id'])),
            // 'image' => '14_layer_visible.png',
            // 'title' => $this->__('View'));

            if (SecurityUtil::checkPermission('ZSELEX::',
                    "{$item['cr_uid']}::{$item['category_id']}", ACCESS_EDIT)) {
                $options [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin',
                        'modifyProductCategory',
                        array(
                        'category_id' => $item ['prd_cat_id'],
                        'shop_id' => $shop_id
                    )),
                    'image' => 'xedit.png',
                    'title' => $this->__('Edit')
                );

                if ((SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['category_id']}",
                        ACCESS_DELETE)) || SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['category_id']}", ACCESS_EDIT)) {
                    $options [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin',
                            'deleteProductCategory',
                            array(
                            'category_id' => $item ['prd_cat_id'],
                            'shop_id' => $shop_id
                        )),
                        'image' => '14_layer_deletelayer.png',
                        'title' => $this->__('Delete')
                    );
                }
            }
            $item ['options'] = $options;

            $item ['infuture'] = DateUtil::getDatetimeDiff_AsField($item ['cr_date'],
                    DateUtil::getDatetime(), 6) < 0;
            $categorysitems [] = $item;
        }

        // Assign the items to the template
        $this->view->assign('categorysitems', $categorysitems);

        $this->view->assign('total_categorys', $total_categorys);

        // Assign the current status filter and the possible ones
        $this->view->assign('status', $status);
        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('order', $order);
        return $this->view->fetch('admin/ishopproducts/product_categories.tpl');
    }

    /**
     * list events as requested/filtered
     * send list to template
     *
     * @return string showlist template
     */
    public function configuredBundles(array $args)
    {
        // exit;
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        $listtype = isset($args ['listtype']) ? $args ['listtype'] : $this->request->query->get('listtype',
                $this->request->request->get('listtype', CalendarEvent::APPROVED));

        switch ($listtype) {
            case CalendarEvent::ALLSTATUS :
                $functionname = "all";
                break;
            case CalendarEvent::HIDDEN :
                $functionname = "hidden";
                break;
            case CalendarEvent::QUEUED :
                $functionname = "queued";
                break;
            case CalendarEvent::APPROVED :
            default :
                $functionname = "approved";
        }

        $sortcolclasses = array(
            'title' => 'z-order-unsorted',
            'time' => 'z-order-unsorted',
            'eventStart' => 'z-order-unsorted'
        );

        $offset        = $this->request->query->get('offset',
            $this->request->request->get('offset', 0));
        $sort          = $this->request->query->get('sort',
            $this->request->request->get('sort', 'time'));
        $original_sdir = $this->request->query->get('sdir',
            $this->request->request->get('sdir', 1));
        $this->view->assign('offset', $offset);
        $this->view->assign('sort', $sort);
        $this->view->assign('sdir', $original_sdir);
        $original_sort = $sort;
        $sdir          = $original_sdir ? 0 : 1; // if true change to false, if false change to true

        if ($sdir == 0) {
            $sortcolclasses [$original_sort] = 'z-order-desc';
            $sort                            = "a.$sort DESC";
        }
        if ($sdir == 1) {
            $sortcolclasses [$original_sort] = 'z-order-asc';
            $sort                            = "a.$sort ASC";
        }
        $this->view->assign('sortcolclasses', $sortcolclasses);

        $filtercats            = $this->request->query->get('pc_categories',
            $this->request->request->get('pc_categories', null));
        $filtercats_serialized = $this->request->query->get('filtercats_serialized',
            false);
        $filtercats            = $filtercats_serialized ? unserialize($filtercats_serialized)
                : $filtercats;
        $selectedCategories    = PostCalendar_Api_Event::formatCategoryFilter($filtercats);
        // echo "maxResults : " . $this->getVar('pcListHowManyEvents');
        $events                = $this->entityManager->getRepository('PostCalendar_Entity_CalendarEvent')->getEventlist($listtype,
            $sort, $offset - 1, $this->getVar('pcListHowManyEvents'),
            $selectedCategories);
        // echo "<pre>"; print_r($events); echo "</pre>";
        $events                = $this->_appendObjectActions($events, $listtype);

        // $finds = $this->entityManager->getRepository('PostCalendar_Entity_CalendarEvent')->find('2');
        // echo "<pre>"; print_r($finds); echo "</pre>";

        $total_events = $this->entityManager->getRepository('PostCalendar_Entity_CalendarEvent')->getEventCount($listtype,
            $selectedCategories);
        $this->view->assign('total_events', $total_events);

        $this->view->assign('filter_active',
            (($listtype == CalendarEvent::ALLSTATUS) && empty($selectedCategories))
                    ? false : true);

        $this->view->assign('functionname', $functionname);
        $this->view->assign('events', $events);
        $sorturls = array(
            'title',
            'time',
            'eventStart'
        );
        foreach ($sorturls as $sorturl) {
            $this->view->assign($sorturl.'_sort_url',
                ModUtil::url('PostCalendar', 'admin', 'listevents',
                    array(
                    'listtype' => $listtype,
                    'filtercats_serialized' => serialize($selectedCategories),
                    'sort' => $sorturl,
                    'sdir' => $sdir
            )));
        }
        $this->view->assign('formactions',
            array(
            '-1' => $this->__('With selected:'),
            self::ACTION_VIEW => $this->__('View'),
            self::ACTION_APPROVE => $this->__('Approve'),
            self::ACTION_HIDE => $this->__('Hide'),
            self::ACTION_DELETE => $this->__('Delete')
        ));
        $this->view->assign('actionselected', '-1');
        $this->view->assign('listtypes',
            array(
            CalendarEvent::ALLSTATUS => $this->__('All Events'),
            CalendarEvent::APPROVED => $this->__('Approved Events'),
            CalendarEvent::HIDDEN => $this->__('Hidden Events'),
            CalendarEvent::QUEUED => $this->__('Queued Events')
        ));
        $this->view->assign('listtypeselected', $listtype);

        $this->view->assign('catregistry',
            CategoryRegistryUtil::getRegisteredModuleCategories('PostCalendar',
                'CalendarEvent'));
        $this->view->assign('selectedcategories', $selectedCategories);

        return $this->view->fetch('admin/showlist.tpl');
    }

    public function paymentgatewaysettings()
    {
        /*
         * $payments = array();
         * $payments[] = array('method' => "Netaxept", 'edit_link' => ModUtil::url('ZPayment', 'admin', 'editNetaxept'));
         *
         * //echo "<pre>"; print_r($payments); echo "</pre>";
         * $this->view->assign('payments', $payments);
         *
         *
         *
         * return $this->view->fetch('admin/payments.tpl');
         */
        return ZPayment_Controller_Admin::payments();
    }

    public function paymentgateway()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        return ZPayment_Controller_Admin::payments1($shop_id);
    }

    public function denmarkMap()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $modvars = $this->getVars();

        if ($_POST) {
            // $country_id = $_REQUEST['map_denmark_id'];
            $region1 = FormUtil::getPassedValue('da_region1', 0);
            $region2 = FormUtil::getPassedValue('da_region2', 0);
            $region3 = FormUtil::getPassedValue('da_region3', 0);
            $region4 = FormUtil::getPassedValue('da_region4', 0);
            $region5 = FormUtil::getPassedValue('da_region5', 0);
            $region6 = FormUtil::getPassedValue('da_region6', 0);
            $region7 = FormUtil::getPassedValue('da_region7', 0);

            $modvars ['da_region1'] = $region1;
            $modvars ['da_region2'] = $region2;
            $modvars ['da_region3'] = $region3;
            $modvars ['da_region4'] = $region4;
            $modvars ['da_region5'] = $region5;
            $modvars ['da_region6'] = $region6;
            $modvars ['da_region7'] = $region7;
            $this->setVars($modvars);
        }

        $country_id = $modvars ['default_country_id'];

        $da_region1 = $modvars ['da_region1'];
        $this->view->assign('da_region1', $da_region1);
        $da_region2 = $modvars ['da_region2'];
        $this->view->assign('da_region2', $da_region2);
        $da_region3 = $modvars ['da_region3'];
        $this->view->assign('da_region3', $da_region3);
        $da_region4 = $modvars ['da_region4'];
        $this->view->assign('da_region4', $da_region4);
        $da_region5 = $modvars ['da_region5'];
        $this->view->assign('da_region5', $da_region5);
        $da_region6 = $modvars ['da_region6'];
        $this->view->assign('da_region6', $da_region6);
        $da_region7 = $modvars ['da_region7'];
        $this->view->assign('da_region7', $da_region7);

        /*
         * $countries = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements', $args = array(
         * 'table' => 'zselex_country',
         * 'where' => '',
         * 'orderBy' => 'country_name ASC',
         * 'useJoins' => ''
         * ));
         */

        // if ($country_id) {

        $regions = $this->entityManager->getRepository('ZSELEX_Entity_Region')->getAll(array(
            'entity' => 'ZSELEX_Entity_Region',
            'where' => array(
                'a.country' => $country_id
            ),
            'orderby' => 'a.region_name ASC'
        ));
        // }
        // echo "<pre>"; print_r($regions); echo "</pre>";
        // echo "<pre>"; print_r($modvars); echo "</pre>";
        // $this->view->assign('countries', $countries);
        $this->view->assign('regions', $regions);
        $this->view->assign('country_id', $country_id);
        return $this->view->fetch('admin/denmark_map.tpl');
    }

    public function manufacturers()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        // initialize sort array - used to display sort classes and urls
        $shop_id = $_REQUEST ['shop_id'];
        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $this->view->assign('shop_id', $shop_id);
        $manufacturerRepo = $this->entityManager->getRepository('ZSELEX_Entity_Manufacturer');
        $sort             = array();
        $fields           = array(
            'manufacturer_id',
            'manufacturer_name'
        ); // possible sort fields

        $itemsperpage  = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 10,
                'GETPOST');
        $startnum      = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $startnum      = $startnum - 1;
        $status        = FormUtil::getPassedValue('status',
                isset($args ['status']) ? $args ['status'] : null, 'GETPOST');
        $order         = FormUtil::getPassedValue('order',
                isset($args ['order']) ? $args ['order'] : 'manufacturer_id',
                'GETPOST');
        $original_sdir = FormUtil::getPassedValue('sdir',
                isset($args ['sdir']) ? $args ['sdir'] : 1, 'GETPOST');
        $searchtext    = FormUtil::getPassedValue('searchtext',
                isset($args ['searchtext']) ? $args ['searchtext'] : null,
                'GETPOST');
        $this->view->assign('searchtext', $searchtext);

        $this->view->assign('startnum', $startnum);
        $this->view->assign('itemsperpage', $itemsperpage);
        $this->view->assign('order', $order);
        $this->view->assign('sdir', $original_sdir);

        $aStatus = array(
            'InActive',
            'Active'
        );
        $this->view->assign('aStatus', $aStatus);

        $sdir = $original_sdir ? 0 : 1; // if true change to false, if false change to true
        // change class for selected 'orderby' field to asc/desc
        if ($sdir == 0) {
            $sort ['class'] [$order] = 'z-order-desc';
            $orderdir                = 'DESC';
        }
        if ($sdir == 1) {
            $sort ['class'] [$order] = 'z-order-asc';
            $orderdir                = 'ASC';
        }

        // complete initialization of sort array, adding urls
        foreach ($fields as $field) {
            $sort ['url'] [$field] = ModUtil::url('ZSELEX', 'admin',
                    'manufacturers',
                    array(
                    'status' => $status,
                    'filtercats_serialized' => serialize($filtercats),
                    'order' => $field,
                    'sdir' => $sdir,
                    'shop_id' => $shop_id
            ));
        }
        $this->view->assign('sort', $sort);

        $this->view->assign('filter_active', $status);

        $owner_id = $this->owner_id;

        $main_args     = array(
            'shop_id' => $shop_id,
            'status' => $status,
            'searchtext' => $searchtext,
            'order' => $order,
            'orderdir' => $orderdir,
            'offset' => $startnum,
            'maxResults' => $itemsperpage
        );
        $manufacturers = $this->entityManager->getRepository('ZSELEX_Entity_Manufacturer')->getManufacturers($main_args);

        $total = $this->entityManager->getRepository('ZSELEX_Entity_Manufacturer')->getManufacturerCount($main_args);

        // Set the possible status for later use
        $itemstatus = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );

        // Assign the items to the template
        $this->view->assign('manufacturers', $manufacturers);

        $this->view->assign('total', $total);

        // Assign the current status filter and the possible ones
        $this->view->assign('status', $status);
        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('order', $order);
        return $this->view->fetch('admin/ishopproducts/manufacturers.tpl');
    }

    public function createManufacturer()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());

        // echo "modifycity";
        // $mnfr_id = FormUtil::getPassedValue('mnfr_id', isset($args['mnfr_id']) ? $args['mnfr_id'] : null, 'GETPOST');
        $shop_id = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : null, 'GETPOST');
        if (!is_numeric($shop_id)) {
            // return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!', (int) ($shop_id)), 403);
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        // echo "Comes here...";
        if ($_POST) {
            $repo         = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
            // echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
            $formElements = FormUtil::getPassedValue('formElements',
                    isset($args ['formElements']) ? $args ['formElements'] : null,
                    'POST');
            extract($formElements);
            // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;
            $InsertId     = $formElements ['elemId'];
            $shop_id      = $shop_id;
            $mnfr_exist   = $repo->getCount(null, 'ZSELEX_Entity_Manufacturer',
                'manufacturer_id',
                array(
                'a.manufacturer_name' => $elemtName,
                'a.shop' => $shop_id
            ));
            if ($mnfr_exist) {
                LogUtil::registerError($this->__('Error! Manufacturer already exists'));
                $this->redirect(ModUtil::url('ZSELEX', 'admin',
                        'createManufacturer',
                        array(
                        'shop_id' => $shop_id
                )));
            }

            // echo $shop_id; exit;
            // $result = $this->entityManager->getRepository('ZSELEX_Entity_Manufacturer')->updateManufacturer($formElements);
            $create_mnfr = new ZSELEX_Entity_Manufacturer();
            $create_mnfr->setManufacturer_name($elemtName);
            $shop        = $this->entityManager->find('ZSELEX_Entity_Shop',
                $shop_id);
            $create_mnfr->setShop($shop);
            $create_mnfr->setStatus($status);
            $this->entityManager->persist($create_mnfr);
            $this->entityManager->flush();
            $result      = $create_mnfr->getManufacturer_id();
            if ($result == true) {
                // Success
                LogUtil::registerStatus($this->__('Done! Manufacturer has been created successfully.'));
            } else {
                // fail! type not created
                // throw new Zikula_Exception_Fatal($this->__('Story not created for unknown reason (Api failure).'));
                // return false;
            }
            $this->redirect(ModUtil::url('ZSELEX', 'admin', 'manufacturers',
                    array(
                    'shop_id' => $shop_id
            )));
        }

        // $manufacturer = $this->entityManager->getRepository('ZSELEX_Entity_Manufacturer')->getManufacturerById(array('mnfr_id' => $mnfr_id));
        // echo "<pre>"; print_r($manufacturer); echo "</pre>";
        // $this->view->assign('item', $manufacturer);
        $this->view->assign('shop_id', $shop_id);
        return $this->view->fetch('admin/ishopproducts/create_manufacturer.tpl');
    }

    public function editManufacturer()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());

        // echo "modifycity";
        $mnfr_id = FormUtil::getPassedValue('mnfr_id',
                isset($args ['mnfr_id']) ? $args ['mnfr_id'] : null, 'GETPOST');
        $shop_id = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : null, 'GETPOST');
        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        // echo "Comes here...";
        if ($_POST) {
            $formElements = FormUtil::getPassedValue('formElements',
                    isset($args ['formElements']) ? $args ['formElements'] : null,
                    'POST');
            // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;
            $InsertId     = $formElements ['elemId'];
            $shop_id      = $formElements ['shop_id'];
            // update the type

            $result = $this->entityManager->getRepository('ZSELEX_Entity_Manufacturer')->updateManufacturer($formElements);
            if ($result == true) {
                // Success
                LogUtil::registerStatus($this->__('Done! Manufacturer has been updated successfully.'));
            } else {
                // fail! type not created
                // throw new Zikula_Exception_Fatal($this->__('Story not created for unknown reason (Api failure).'));
                // return false;
            }
            $this->redirect(ModUtil::url('ZSELEX', 'admin', 'manufacturers',
                    array(
                    'shop_id' => $shop_id
            )));
        }

        $manufacturer = $this->entityManager->getRepository('ZSELEX_Entity_Manufacturer')->getManufacturerById(array(
            'mnfr_id' => $mnfr_id
        ));
        // echo "<pre>"; print_r($manufacturer); echo "</pre>";
        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('item', $manufacturer);
        return $this->view->fetch('admin/ishopproducts/create_manufacturer.tpl');
    }

    public function deleteManufacturer($args)
    {
        $mnfr_id = FormUtil::getPassedValue('mnfr_id',
                isset($args ['mnfr_id']) ? $args ['mnfr_id'] : null, 'REQUEST');
        $shop_id = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : null, 'REQUEST');
        // echo $shop_id; exit;

        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');

        if (empty($confirmation)) {
            $this->view->assign('IdValue', $mnfr_id);
            $this->view->assign('confirm_title',
                $this->__f('Delete %s', $this->__('Manufacturer')));
            $this->view->assign('confirm_msg',
                $this->__f('Do you want to delete this %s',
                    $this->__('Manufacturer')));
            $this->view->assign('IdName', 'mnfr_id');
            $this->view->assign('shop_id', $shop_id);
            $this->view->assign('submitFunc', 'deleteManufacturer');
            $this->view->assign('cancelFunc', 'manufacturers');
            // Return the output that has been generated by this function
            return $this->view->fetch('admin/deletecommon2.tpl');
        }
        $this->checkCsrfToken();

        $result = $this->entityManager->getRepository('ZSELEX_Entity_Manufacturer')->deleteManufacturer(array(
            'manufacturer_id' => $mnfr_id
        ));

        // Delete
        if ($result) {

            // Success
            LogUtil::registerStatus($this->__('Done! Manufacturer has been deleted successfully.'));
            // Let any hooks know that we have deleted an item
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'manufacturers',
                    array(
                    'shop_id' => $shop_id
        )));
    }

    public function mailtext()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $modvars = $this->getVars();

        // echo "<pre>"; print_r($modvars); echo "</pre>"; exit;

        if ($_POST) {
            // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
            $this->checkCsrfToken();

            $modvars ['additional_mail_text'] = FormUtil::getPassedValue('additional_mail_text');
            $modvars ['enabled_mail_text']    = FormUtil::getPassedValue('enabled_mail_text');
            $this->setVars($modvars);
            $this->view->clear_cache();
            LogUtil::registerStatus($this->__('Done! Mail text has been saved.'));
        }

        $languages = ZLanguage::getInstalledLanguages();
        $this->view->assign('languages', $languages);
        $thislang  = ZLanguage::getLanguageCode();
        $this->view->assign('thislang', $thislang);

        return $this->view->fetch('admin/mailtext.tpl');
    }

    public function productOption($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        // initialize sort array - used to display sort classes and urls
        $shop_id = $_REQUEST ['shop_id'];
        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $productOptionRepo = $this->entityManager->getRepository('ZSELEX_Entity_ProductOption');
        if ($_POST) {
            // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
            // if ($_POST['saveType'] == 'links') {
            $Links = array();
            $Links = FormUtil::getPassedValue('linkOptionIds', array(),
                    'GETPOST');
            // echo "<pre>"; print_r($Links); echo "</pre>"; exit;
            if (count($Links) > 2) {
                LogUtil::registerError($this->__("You cannot link more than 2 options"));
            } // echo "<pre>"; print_r($Links); echo "</pre>";
            // elseif (count($Links) < 2) {
            elseif (count($Links) == 1) {
                LogUtil::registerError($this->__("You need to link minimum of 2 options"));
            } else {
                // array_push($Links, array('shop_id'=>$shop_id));

                $Links        = $Links + array(
                    'shop_id' => $shop_id
                );
                // echo "<pre>"; print_r($Links); echo "</pre>"; exit;
                $updateParent = ModUtil::apiFunc('ZSELEX', 'product',
                        'setParentOptions', $Links);
                if ($updateParent) {
                    LogUtil::registerStatus($this->__("Done! saved linked options"));
                    // $successMsg .= $this->__("Done! saved linked options") . '<br>';
                }
            }
            // }
            // elseif ($_POST['saveType'] == 'sortOrder') {
            $SortOrders = FormUtil::getPassedValue('optionSortOrder', null,
                    'GETPOST');
            // echo "<pre>"; print_r($SortOrders); echo "</pre>"; exit;
            foreach ($SortOrders as $option_id => $sort_order) {
                $updateargss = array(
                    'table' => 'zselex_product_options',
                    'IdValue' => $option_id,
                    'IdName' => 'option_id',
                    'element' => array(
                        'option_id' => $option_id,
                        'sort_order' => $sort_order
                    )
                );

                // $resultz = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement', $updateargss);
                $resultz = $productOptionRepo->updateEntity(null,
                    'ZSELEX_Entity_ProductOption',
                    array(
                    'option_id' => $option_id,
                    'sort_order' => $sort_order
                    ),
                    array(
                    'a.option_id' => $option_id
                ));
                if ($resultz) {
                    
                }
            }
            LogUtil::registerStatus($this->__("Done! saved sort order"));
            // }
        }

        $sort   = array();
        $fields = array(
            'option_id',
            'option_name'
        ); // possible sort fields

        $itemsperpage  = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 10,
                'GETPOST');
        $startnum      = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $startnum      = $startnum - 1;
        $status        = FormUtil::getPassedValue('status',
                isset($args ['status']) ? $args ['status'] : null, 'GETPOST');
        $order         = FormUtil::getPassedValue('order',
                isset($args ['order']) ? $args ['order'] : 'option_id',
                'GETPOST');
        $original_sdir = FormUtil::getPassedValue('sdir',
                isset($args ['sdir']) ? $args ['sdir'] : 1, 'GETPOST');
        $searchtext    = FormUtil::getPassedValue('searchtext',
                isset($args ['searchtext']) ? $args ['searchtext'] : null,
                'GETPOST');
        $this->view->assign('searchtext', $searchtext);

        $this->view->assign('startnum', $startnum);
        $this->view->assign('itemsperpage', $itemsperpage);
        $this->view->assign('order', $order);
        $this->view->assign('sdir', $original_sdir);

        $aStatus = array(
            'InActive',
            'Active'
        );
        $this->view->assign('aStatus', $aStatus);

        $sdir = $original_sdir ? 0 : 1; // if true change to false, if false change to true
        // change class for selected 'orderby' field to asc/desc
        if ($sdir == 0) {
            $sort ['class'] [$order] = 'z-order-desc';
            $orderdir                = 'DESC';
        }
        if ($sdir == 1) {
            $sort ['class'] [$order] = 'z-order-asc';
            $orderdir                = 'ASC';
        }

        // complete initialization of sort array, adding urls
        foreach ($fields as $field) {
            $sort ['url'] [$field] = ModUtil::url('ZSELEX', 'admin',
                    'productOption',
                    array(
                    'status' => $status,
                    'filtercats_serialized' => serialize($filtercats),
                    'order' => $field,
                    'sdir' => $sdir,
                    'shop_id' => $shop_id
            ));
        }
        $this->view->assign('sort', $sort);

        $this->view->assign('filter_active', $status);

        $owner_id = $this->owner_id;

        $main_args       = array(
            'shop_id' => $shop_id,
            'status' => $status,
            'searchtext' => $searchtext,
            'order' => $order,
            'orderdir' => $orderdir,
            'offset' => $startnum,
            'maxResults' => $itemsperpage
        );
        $product_options = $this->entityManager->getRepository('ZSELEX_Entity_ProductOption')->getProductOptionList($main_args);

        // echo "<pre>"; print_r($product_options); echo "</pre>";
        $total      = $this->entityManager->getRepository('ZSELEX_Entity_ProductOption')->getProductOptionCount($main_args);
        // Set the possible status for later use
        $itemstatus = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );

        // Assign the items to the template
        $this->view->assign('product_options', $product_options);

        $this->view->assign('total', $total);

        // Assign the current status filter and the possible ones
        $this->view->assign('status', $status);
        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('order', $order);
        return $this->view->fetch('admin/ishopproducts/viewproduct_option.tpl');
    }

    public function createOption($args)
    {
        $shop_id = FormUtil::getPassedValue('shop_id', 0, 'GETPOST');
        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        if ($_POST) {

            // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
            $this->checkCsrfToken();
            $formElements   = FormUtil::getPassedValue('formElements',
                    isset($args ['formElements']) ? $args ['formElements'] : null,
                    'GETPOST');
            // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;
            // echo "<pre>"; print_r($formElements['optionValues']); echo "</pre>"; exit;
            $product_option = new ZSELEX_Entity_ProductOption();
            $shop           = $this->entityManager->find('ZSELEX_Entity_Shop',
                $formElements ['shop_id']);
            $product_option->setShop($shop);
            $product_option->setOption_name($formElements ['name']);
            $product_option->setOption_type($formElements ['type']);
            $product_option->setParent_option_id(0);
            $product_option->setSort_order(0);

            $this->entityManager->persist($product_option);
            $this->entityManager->flush();
            $InsertId = $product_option->getOption_id();
            if ($InsertId > 0) {
                foreach ($formElements ['optionValues'] ['val'] as $key => $val) {
                    /*
                     * $option_item = array(
                     * 'option_id' => $InsertId,
                     * 'shop_id' => $formElements['shop_id'],
                     * 'option_value' => $val,
                     * 'sort_order' => $formElements['optionValues']['sort_order'][$key],
                     * );
                     * $create_args = array(
                     * 'table' => 'zselex_product_options_values',
                     * 'element' => $option_item,
                     * 'Id' => 'option_value_id'
                     * );
                     * $insert = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $create_args);
                     */

                    $prodOptVal = new ZSELEX_Entity_ProductOptionValue();
                    $optionObj  = $this->entityManager->find('ZSELEX_Entity_ProductOption',
                        $InsertId);
                    $prodOptVal->setOption($optionObj);
                    $shopObj    = $this->entityManager->find('ZSELEX_Entity_Shop',
                        $formElements ['shop_id']);
                    $prodOptVal->setShop($shopObj);
                    $prodOptVal->setOption_value($val);
                    $prodOptVal->setSort_order($formElements ['optionValues'] ['sort_order'] [$key]);
                    $this->entityManager->persist($prodOptVal);
                    $this->entityManager->flush();
                }
                return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                            'productOption',
                            array(
                            'shop_id' => $formElements ['shop_id']
                )));
                LogUtil::registerStatus($this->__('Done! Created product option'));
            }
        }

        return $this->view->fetch('admin/ishopproducts/create_product_option.tpl');
    }

    public function editOption($args)
    {
        $shop_id   = FormUtil::getPassedValue('shop_id', 0, 'GETPOST');
        $option_id = FormUtil::getPassedValue('option_id', 0, 'GETPOST');

        if (!is_numeric($shop_id) || empty($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }
        if (!is_numeric($option_id) || empty($option_id)) {
            return LogUtil::registerError($this->__f('Error! The ID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($option_id)), 403);
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $this->view->assign('option_id', $option_id);

        if ($_POST) {
            $this->checkCsrfToken();
            $formElements = FormUtil::getPassedValue('formElements',
                    isset($args ['formElements']) ? $args ['formElements'] : null,
                    'GETPOST');
            // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;
            /*
             * $upd_args = array(
             * 'entity' => 'ZSELEX_Entity_ProductOption',
             * 'fields' => array(
             * 'shop_id' => $formElements['shop_id'],
             * 'option_name' => $formElements['name'],
             * 'option_type' => $formElements['type'],
             * 'option_value' => serialize($formElements['optionValues'])
             * ),
             * 'where' => array("option_id=$formElements[elemId]")
             * );
             * // echo "<pre>"; print_r($upd_args); echo "</pre>"; exit;
             * $upd_country = $this->entityManager->getRepository('ZSELEX_Entity_ProductOption')->updateEntity($upd_args);
             */
            $insert       = ModUtil::apiFunc('ZSELEX', 'product',
                    'saveOptionValues', $formElements);
            // $result = $this->entityManager->getRepository('ZSELEX_Entity_Product')->createProduct($item=array());
            $result       = ModUtil::apiFunc('ZSELEX', 'product',
                    'createProduct', $item);
            LogUtil::registerStatus($this->__('Done! Updated product option'));
            return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                        'productOption',
                        array(
                        'shop_id' => $formElements ['shop_id']
            )));
        }

        $product_options = $this->entityManager->getRepository('ZSELEX_Entity_ProductOption')->getProductOption(array(
            'shop_id' => $shop_id,
            'option_id' => $option_id
        ));
        // echo "<pre>"; print_r($product_options); echo "</pre>";
        $option_value    = unserialize($product_options ['option_value']);
        // echo "<pre>"; print_r($option_value); echo "</pre>";

        $product_options ['values'] = ModUtil::apiFunc('ZSELEX', 'user',
                'getAll',
                $args                       = array(
                'table' => 'zselex_product_options_values',
                'where' => "option_id=$option_id"
        ));

        // echo "<pre>"; print_r($product_options); echo "</pre>";
        $this->view->assign('product_options', $product_options);
        $this->view->assign('option_value', $option_value);
        return $this->view->fetch('admin/ishopproducts/create_product_option.tpl');
    }

    public function deleteProductOption()
    {
        $option_id = FormUtil::getPassedValue('option_id',
                isset($args ['option_id']) ? $args ['option_id'] : null,
                'REQUEST');
        $shop_id   = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : null, 'REQUEST');
        // echo $shop_id; exit;

        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');

        if (empty($confirmation)) {
            $this->view->assign('IdValue', $option_id);
            $this->view->assign('confirm_title',
                $this->__f('Delete %s', $this->__('Product Option')));
            $this->view->assign('confirm_msg',
                $this->__f('Do you want to delete this %s',
                    $this->__('Product Option')));
            $this->view->assign('IdName', 'option_id');
            $this->view->assign('shop_id', $shop_id);
            $this->view->assign('submitFunc', 'deleteProductOption');
            $this->view->assign('cancelFunc', 'productOption');
            // Return the output that has been generated by this function
            return $this->view->fetch('admin/deletecommon2.tpl');
        }
        $this->checkCsrfToken();

        $result = $this->entityManager->getRepository('ZSELEX_Entity_ProductOption')->deleteProductOption(array(
            'option_id' => $option_id
        ));

        // Delete
        if ($result) {
            // Success
            // DBUtil::deleteWhere('zselex_product_options_values', "option_id=$option_id");
            $this->entityManager->getRepository('ZSELEX_Entity_ProductOption')->deleteEntity(null,
                'ZSELEX_Entity_ProductOptionValue',
                array(
                'a.option' => $option_id
            ));
            LogUtil::registerStatus($this->__('Done! Product option has been deleted successfully.'));
            // Let any hooks know that we have deleted an item
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'productOption',
                    array(
                    'shop_id' => $shop_id
        )));
    }

    public function updateShopBundles($args)
    {
        set_time_limit(0);
        $shops       = FormUtil::getPassedValue('shopIds', null, 'REQUEST');
        $shopIdArray = explode(',', $shops);
        $shopIdsJson = json_encode($shopIdArray);

        $path = $_SERVER ['DOCUMENT_ROOT'].'/scripts/update_bundles.php';
        if ($_SERVER ['SERVER_NAME'] == 'localhost') {
            $path = $_SERVER ['DOCUMENT_ROOT'].'/zselex/scripts/update_bundles.php';
        }
        $baseUrl = pnGetBaseURL();

        $cmd = "/usr/bin/php -c php.ini ".$path." ".$shopIdsJson." ".$baseUrl;
        // echo $cmd; exit;
        ZSELEX_Util::execInBackground($cmd);

        LogUtil::registerStatus($this->__('Update Bundles script has started at background'));

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshop'));
    }

    public function updateShopBundles_19_02_16($args)
    {
        set_time_limit(0);
        $shops  = FormUtil::getPassedValue('shopIds', null, 'REQUEST');
        $repo   = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $update = $repo->updateShopBundlesWithLatest(array(
            'shops' => $shops
        ));
        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshop'));
    }

    public function updateShopBundles1($args)
    {
        // echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
        $shops   = FormUtil::getPassedValue('shopIds', null, 'REQUEST');
        // echo $shops; exit;
        $shopIds = explode(',', $shops);
        $repo    = $this->entityManager->getRepository('ZSELEX_Entity_Shop');

        $bundles = $repo->getAll(array(
            'entity' => 'ZSELEX_Entity_ServiceBundle',
            'fields' => array(
                'b.shop_id',
                'c.bundle_id',
                'c.bundle_type',
                'a.service_status',
                'a.timer_date',
                'a.timer_days'
            ),
            'joins' => array(
                'JOIN a.shop b',
                'JOIN a.bundle c'
            ),
            'subquery' => array(
                "a.shop IN($shops)"
            ),
            'groupby' => 'a.service_bundle_id'
        ));

        // echo "<pre>"; print_r($bundles); echo "</pre>"; exit;
        $user_id = UserUtil::getVar('uid');
        foreach ($bundles as $val) {
            // echo $shop_id . '<br>';
            // echo "<pre>"; print_r($val); echo "</pre>";
            $shop_id   = $val ['shop_id'];
            $bundle_id = $val ['bundle_id'];
            $ownerInfo = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwnerInfo',
                    $args      = array(
                    'shop_id' => $shop_id
            ));

            $owner_id    = $ownerInfo ['user_id'];
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
            $newBitems = array();
            foreach ($bundleitems as $bitem) {
                $newBitems [] = $bitem ['servicetype'];
                $count        = $repo->getCount(null,
                    'ZSELEX_Entity_ServiceShop', 'id',
                    array(
                    'a.shop' => $shop_id,
                    'a.type' => $bitem ['servicetype']
                ));
                if (!$count) {
                    $srvcShop  = new ZSELEX_Entity_ServiceShop();
                    $shopObj   = $this->entityManager->find('ZSELEX_Entity_Shop',
                        $shop_id);
                    $srvcShop->setShop($shopObj);
                    $srvcShop->setUser_id($user_id);
                    $srvcShop->setOwner_id($owner_id);
                    $pluginObj = $this->entityManager->find('ZSELEX_Entity_Plugin',
                        $bitem ['plugin_id']);
                    $srvcShop->setPlugin($pluginObj);
                    $srvcShop->setType($bitem ['servicetype']);
                    $srvcShop->setOriginal_quantity($bitem ['qty']);
                    $srvcShop->setQuantity($bitem ['qty']);
                    $srvcShop->setStatus(1);
                    $srvcShop->setService_status($val ['service_status']);
                    $srvcShop->setQty_based($bitem ['qty_based']);
                    $srvcShop->setIs_bundle(1);
                    $bundleObj = $this->entityManager->find('ZSELEX_Entity_Bundle',
                        $bundle_id);
                    $srvcShop->setBundle($bundleObj);
                    $srvcShop->setBundle_type($val ['bundle_type']);
                    $srvcShop->setTimer_date(date_create($val ['timer_date']));
                    $srvcShop->setTimer_days($val ['timer_days']);
                    $this->entityManager->persist($srvcShop);
                    $this->entityManager->flush();
                    $result    = $srvcShop->getId();
                } else {
                    $updFields = array(
                        'original_quantity' => $bitem ['qty'],
                        'quantity' => $bitem ['qty']
                    );
                    $repo->updateEntity(null, 'ZSELEX_Entity_ServiceShop',
                        $updFields,
                        array(
                        'a.shop' => $shop_id,
                        'a.type' => $bitem ['servicetype']
                    ));
                }
            }
            $newBitemsString = "'".implode("','", $newBitems)."'";
            $delWhere        = "a.shop=$shop_id AND a.bundle=$bundle_id AND a.type NOT IN($newBitemsString)";
            $repo->deleteWhere(null, 'ZSELEX_Entity_ServiceShop', $delWhere);
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshop'));
    }

    public function updateAdditionalBundles($args)
    {
        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        $repo->updateAdditionalBundles();
    }

    public function viewsociallinks($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        $repo    = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        // initialize sort array - used to display sort classes and urls
        SessionUtil::delVar('identifieritem');
        $user_id = UserUtil::getVar('uid');

        $admin = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN);
        $this->view->assign('admin', $admin);

        $sort   = array();
        $fields = array(
            'socl_link_id',
            'socl_link_name'
        ); // possible sort fields

        $itemsperpage  = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 25,
                'GETPOST');
        $startnum      = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $status        = FormUtil::getPassedValue('status',
                isset($args ['status']) ? $args ['status'] : null, 'GETPOST');
        $order         = FormUtil::getPassedValue('order',
                isset($args ['order']) ? $args ['order'] : 'socl_link_id',
                'GETPOST');
        $original_sdir = FormUtil::getPassedValue('sdir',
                isset($args ['sdir']) ? $args ['sdir'] : 0, 'GETPOST');
        $searchtext    = FormUtil::getPassedValue('searchtext',
                isset($args ['searchtext']) ? $args ['searchtext'] : null,
                'GETPOST');
        $this->view->assign('searchtext', $searchtext);
        $this->view->assign('startnum', $startnum);
        $this->view->assign('itemsperpage', $itemsperpage);
        $this->view->assign('order', $order);
        $this->view->assign('sdir', $original_sdir);

        $aStatus = array(
            'InActive',
            'Active'
        );
        $this->view->assign('aStatus', $aStatus);

        $sdir = $original_sdir ? 0 : 1; // if true change to false, if false change to true
        // change class for selected 'orderby' field to asc/desc
        if ($sdir == 0) {
            $sort ['class'] [$order] = 'z-order-desc';
            $orderdir                = 'DESC';
        }
        if ($sdir == 1) {
            $sort ['class'] [$order] = 'z-order-asc';
            $orderdir                = 'ASC';
        }
        // complete initialization of sort array, adding urls
        foreach ($fields as $field) {
            $sort ['url'] [$field] = ModUtil::url('ZSELEX', 'admin',
                    'viewsociallinks',
                    array(
                    'status' => $status,
                    'filtercats_serialized' => serialize($filtercats),
                    'order' => $field,
                    'sdir' => $sdir
            ));
        }
        // echo "<pre>"; print_r($sort); echo "</pre>";
        $this->view->assign('sort', $sort);

        $this->view->assign('filter_active', $status);

        // $sql = " SELECT a.* FROM zselex_type AS a
        // WHERE a.type_id IS NOT NULL ";

        $sql = "";
        if (isset($status) && $status != "") {
            // $sql .= " AND status = " . $status;
            $soc_args ['where'] ['a.status'] = $status;
        }
        if (isset($searchtext) && $searchtext != "") {
            // $sql .= " AND identifier LIKE '%" . DataUtil::formatForStore($searchtext) . "%'";
            $soc_args ['like'] ['a.socl_link_name'] = "%".DataUtil::formatForStore($searchtext)."%";
        }
        if (isset($order) && $order != "") {
            // $sql .= " ORDER BY " . $order . " " . $orderdir;
            $soc_args ['orderby'] = "a.".$order." ".$orderdir;
        }

        // Get all zselex stories
        $getArgs = array(
            'sql' => $sql,
            'startnum' => $startnum,
            'numitems' => $itemsperpage
        );
        // $items = ModUtil::apiFunc('ZSELEX', 'admin', 'getAll', $getArgs);

        $items = array();

        $soc_args ['entity']     = 'ZSELEX_Entity_SocialLink';
        $soc_args ['paginate']   = true;
        $soc_args ['fields']     = array(
            'a.socl_link_id',
            'a.socl_link_name',
            'a.socl_image',
            'a.status'
        );
        $soc_args ['startlimit'] = $startnum;
        $soc_args ['offset']     = $itemsperpage;
        $identifiers             = $repo->getAll($soc_args);
        // echo "<pre>"; print_r($identifiers); echo "</pre>"; exit;
        $items                   = $identifiers ['result'];
        $count                   = $identifiers ['count'];
        // echo "<pre>"; print_r($items); echo "</pre>";

        $total_count = $count;

        // Set the possible status for later use
        $itemstatus = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );

        $identifieritems = array();
        foreach ($items as $item) {
            $options = array();
            if (SecurityUtil::checkPermission('ZSELEX::',
                    "{$item['cr_uid']}::{$item['id']}", ACCESS_EDIT)) {
                $options [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'modifysociallink',
                        array(
                        'link_id' => $item ['socl_link_id']
                    )),
                    'image' => 'xedit.png',
                    'title' => $this->__('Edit')
                );

                if ((SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['id']}", ACCESS_DELETE)) || SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['type_id']}", ACCESS_ADD)) {
                    $options [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin',
                            'deletesociallink',
                            array(
                            'link_id' => $item ['socl_link_id']
                        )),
                        'image' => '14_layer_deletelayer.png',
                        'title' => $this->__('Delete')
                    );
                }
            }
            $item ['options']   = $options;
            $item ['infuture']  = DateUtil::getDatetimeDiff_AsField($item ['cr_date'],
                    DateUtil::getDatetime(), 6) < 0;
            $identifieritems [] = $item;
        }

        // Assign the items to the template
        $this->view->assign('identifieritems', $identifieritems);
        $this->view->assign('total_count', $total_count);

        // Assign the current status filter and the possible ones
        $this->view->assign('status', $status);
        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('order', $order);
        // Return the output that has been generated by this function
        return $this->view->fetch('admin/sociallink/viewsociallinks.tpl');
    }

    public function modifysociallink($ags)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        if ($_POST) {
            $formElements = FormUtil::getPassedValue('formElements',
                    isset($args ['formElements']) ? $args ['formElements'] : null,
                    'POST');
            // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;
            $InsertId     = $formElements ['socl_link_id'];
            // update the type

            $item = array(
                // 'aff_id' => $InsertId,
                'socl_link_name' => $formElements ['socl_link_name'],
                'socl_image' => $formElements ['socl_image'],
                'status' => $formElements ['status']
            );

            $result = $repo->updateEntity(null, 'ZSELEX_Entity_SocialLink',
                $item,
                array(
                'a.socl_link_id' => $InsertId
            ));
            if ($result != false) {
                LogUtil::registerStatus($this->__('Done! Social Link has been saved successfully.'));
            } else {
                // fail! type not created
                throw new Zikula_Exception_Fatal($this->__('Story not created for unknown reason (Api failure).'));
                return false;
            }
            $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewsociallinks'));
        }
        // echo "modifycity";
        $link_id = FormUtil::getPassedValue('link_id',
                isset($args ['link_id']) ? $args ['link_id'] : null, 'GETPOST');

        $item = $repo->get(array(
            'entity' => 'ZSELEX_Entity_SocialLink',
            'where' => array(
                'a.socl_link_id' => $link_id
            )
        ));
        // echo "<pre>"; print_r($item); echo "</pre>";
        $this->view->assign('item', $item);

        return $this->view->fetch('admin/sociallink/create_sociallink.tpl');
    }

    public function createsociallink()
    {
        if ($_POST) {
            $formElements = FormUtil::getPassedValue('formElements',
                    isset($args ['formElements']) ? $args ['formElements'] : null,
                    'POST');
            $formElements = ZSELEX_Util::purifyHtml($formElements);

            $soc_link = new ZSELEX_Entity_SocialLink();
            $soc_link->setSocl_link_name($formElements ['socl_link_name']);
            $soc_link->setSocl_image($formElements ['socl_image']);
            $this->entityManager->persist($soc_link);
            $this->entityManager->flush();
            $result   = $soc_link->getSocl_link_id();
            if ($result != false) {
                LogUtil::registerStatus($this->__('Done! Social Link has been created successfully.'));
            } else {
                // fail! type not created
                throw new Zikula_Exception_Fatal($this->__('Story not created for unknown reason (Api failure).'));
                return false;
            }
            $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewsociallinks'));
        }
        return $this->view->fetch('admin/sociallink/create_sociallink.tpl');
    }

    public function deletesociallink($args)
    {
        $link_id = FormUtil::getPassedValue('link_id',
                isset($args ['link_id']) ? $args ['link_id'] : null, 'REQUEST');

        $user_id = UserUtil::getVar('uid');
        // Validate the essential parameters
        if (empty($link_id)) {
            return LogUtil::registerArgsError();
        }

        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
        if (empty($confirmation)) {
            // Add ZSELEX type ID
            $this->view->assign('IdValue', $link_id);
            $this->view->assign('confirm_title',
                $this->__f('Delete %s item', $this->__('Social Link')));
            $this->view->assign('confirm_msg',
                $this->__f('Do you want to delete this %s item',
                    $this->__('Social Link')));

            $this->view->assign('IdName', 'link_id'); // edit id param name
            $this->view->assign('submitFunc', 'deletesociallink');
            $this->view->assign('cancelFunc', 'viewsociallinks');
            $emptyvar = $this->__('Confirmation prompt'); // just to get the translation out with poedit!!!
            // Return the output that has been generated by this function
            return $this->view->fetch('admin/deletecommon1.tpl');
        }

        $repo   = $this->entityManager->getRepository('ZSELEX_Entity_SocialLink');
        $delete = $repo->deleteEntity(null, 'ZSELEX_Entity_SocialLink',
            array(
            'a.socl_link_id' => $link_id
        ));
        if ($delete) {
            // $user_id = UserUtil::getVar('uid');
            // Success
            LogUtil::registerStatus($this->__('Done! Social Link has been deleted successfully.'));

            // Let any hooks know that we have deleted an item
            // $this->notifyHooks(new Zikula_ProcessHook('type.ui_hooks.types.process_delete', $Id));
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewsociallinks'));
    }
    /*
     * Display social links
     */

    public function sociallinks($args)
    {
        $repo    = $this->entityManager->getRepository('ZSELEX_Entity_SocialLink');
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $this->view->assign('shop_id', $shop_id);

        $serviceargs       = array(
            'shop_id' => $shop_id,
            'type' => 'sociallinks',
            'disablecheck' => true
        );
        $servicePermission = $this->servicePermission($serviceargs);
        // echo "<pre>"; print_r($servicePermission); echo "</pre>";
        $this->view->assign('socialllink_perm', $servicePermission);
        if ($servicePermission ['perm'] < 1) {
            $message = $servicePermission ['message'];
            // $error++;
            LogUtil::registerError(nl2br($message));
        }

        if ($_POST) {
            // echo "<pre>"; print_r($_POST); echo "</pre>";
            $form_elements = FormUtil::getPassedValue('formElements', null,
                    'POST');
            // echo "<pre>"; print_r($form_elements); echo "</pre>"; exit;
            $shop_id       = $form_elements ['shop_id'];
            // echo $shop_id; exit;

            $setting_exist = $repo->getCount(null,
                'ZSELEX_Entity_SocialLinkShopSetting', 'id',
                array(
                'a.shop' => $shop_id
            ));
            // echo $setting_exist; exit;
            if ($setting_exist) {
                $update_setting_item = array(
                    'icon_size' => $form_elements ['iconsize']
                );
                $update_setting      = $repo->updateEntity(null,
                    'ZSELEX_Entity_SocialLinkShopSetting', $update_setting_item,
                    array(
                    'a.shop' => $shop_id
                ));
            } else {
                $soc_entity_setting = new ZSELEX_Entity_SocialLinkShopSetting();
                $shop               = $this->entityManager->find('ZSELEX_Entity_Shop',
                    $shop_id);
                $soc_entity_setting->setShop($shop);
                $soc_entity_setting->setIcon_size($form_elements ['iconsize']);
                $this->entityManager->persist($soc_entity_setting);
                $this->entityManager->flush();
            }

            $success = 0;
            // echo "<pre>"; print_r($form_elements['url']); echo "</pre>"; exit;
            foreach ($form_elements ['url'] as $id => $url) {
                $record_exist = $repo->getCount(null,
                    'ZSELEX_Entity_SocialLinkShop', 'id',
                    array(
                    'a.shop' => $shop_id,
                    'a.socl_links' => $id
                ));
                if ($record_exist) {
                    $update_item = array(
                        'link_url' => $url
                    );
                    $update      = $repo->updateEntity(null,
                        'ZSELEX_Entity_SocialLinkShop', $update_item,
                        array(
                        'a.socl_links' => $id,
                        'a.shop' => $shop_id
                    ));
                    if ($update) {
                        $success ++;
                    }
                } else {
                    $soc_entity  = new ZSELEX_Entity_SocialLinkShop();
                    $shop        = $this->entityManager->find('ZSELEX_Entity_Shop',
                        $shop_id);
                    $soc_entity->setShop($shop);
                    $soc_link_id = $this->entityManager->find('ZSELEX_Entity_SocialLink',
                        $id);
                    $soc_entity->setSocl_links($soc_link_id);
                    $soc_entity->setLink_url($url);

                    $this->entityManager->persist($soc_entity);
                    $this->entityManager->flush();
                    // $no_payment = $shop_entity->getShop_id();
                    $insert_id = $soc_entity->getId();
                    if ($insert_id) {
                        $success ++;
                    }
                }
            }
            if ($success) {
                LogUtil::registerStatus($this->__('Done! Social link urls updates successfully.'));
            }
        }

        // get the icon image here
        $setting_args = array(
            'entity' => 'ZSELEX_Entity_SocialLinkShopSetting',
            'fields' => array(
                'a.icon_size'
            ),
            'where' => array(
                'a.shop' => $shop_id
            )
        );
        $soc_settings = $repo->get($setting_args);
        // echo "<pre>"; print_r($soc_settings); echo "</pre>";
        $this->view->assign('soc_settings', $soc_settings);

        $soc_args     = array(
            'entity' => 'ZSELEX_Entity_SocialLink'
        );
        $social_links = $repo->getAll($soc_args);
        // echo "<pre>"; print_r($social_links); echo "</pre>";
        $this->view->assign('social_links', $social_links);
        return $this->view->fetch('admin/sociallink/sociallinks.tpl');
    }

    /**
     * Discount listing
     *
     * @return html
     */
    public function discount($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        // initialize sort array - used to display sort classes and urls
        $shop_id = $_REQUEST ['shop_id'];
        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Discount');

        $sort   = array();
        $fields = array(
            'discount_code',
            'discount'
        ); // possible sort fields

        $itemsperpage  = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 20,
                'GETPOST');
        $startnum      = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        //$startnum      = $startnum - 1;
        $status        = FormUtil::getPassedValue('status',
                isset($args ['status']) ? $args ['status'] : null, 'GETPOST');
        $order         = FormUtil::getPassedValue('order',
                isset($args ['order']) ? $args ['order'] : 'discount_id',
                'GETPOST');
        $original_sdir = FormUtil::getPassedValue('sdir',
                isset($args ['sdir']) ? $args ['sdir'] : 1, 'GETPOST');
        $searchtext    = FormUtil::getPassedValue('searchtext',
                isset($args ['searchtext']) ? $args ['searchtext'] : null,
                'GETPOST');
        $this->view->assign('searchtext', $searchtext);

        $this->view->assign('startnum', $startnum);
        $this->view->assign('itemsperpage', $itemsperpage);
        $this->view->assign('order', $order);
        $this->view->assign('sdir', $original_sdir);
        //  echo 'order:' . $order . '<br>';
        //echo 'sdir:' . $original_sdir . '<br>';

        $aStatus = array(
            'InActive',
            'Active'
        );
        $this->view->assign('aStatus', $aStatus);

        $sdir = $original_sdir ? 0 : 1; // if true change to false, if false change to true
        // change class for selected 'orderby' field to asc/desc
        if ($sdir == 0) {
            $sort ['class'] [$order] = 'z-order-desc';
            $orderdir                = 'DESC';
        }
        if ($sdir == 1) {
            $sort ['class'] [$order] = 'z-order-asc';
            $orderdir                = 'ASC';
        }

        // complete initialization of sort array, adding urls
        foreach ($fields as $field) {
            $sort ['url'] [$field] = ModUtil::url('ZSELEX', 'admin', 'discount',
                    array(
                    'status' => $status,
                    'filtercats_serialized' => serialize($filtercats),
                    'order' => $field,
                    'sdir' => $sdir,
                    'shop_id' => $shop_id
            ));
        }
        $this->view->assign('sort', $sort);

        $this->view->assign('filter_active', $status);

        $owner_id = $this->owner_id;



        $disArgs ['fields'] = array(
            'a.discount_id',
            'a.discount_code',
            'a.discount',
            'a.status'
        );

        // Get all zselex stories

        $disArgs ['entity']   = 'ZSELEX_Entity_Discount';
        $disArgs ['paginate'] = true;
        $orderby              = '';
        if (isset($order) && $order != '') {
            // $sql .= " ORDER BY $alias" . $order . " " . $orderdir;
            $orderby             = " a.".$order.' '.$orderdir.' ';
            // echo $orderby;
            $disArgs ['orderby'] = $orderby;
            // echo "comes here";
        }

        $disArgs ['startlimit'] = $startnum;
        $disArgs ['offset']     = $itemsperpage;
        //   $disArgs ['print']  = true;
        $disArgs ['where']      = array(
            'a.shop' => $shop_id
        );

        $discounts = $repo->getAll($disArgs);
        //echo "<pre>"; print_r($discounts); echo "</pre>";

        $discountItems = $discounts['result'];
        $discountCount = $discounts['count'];
        // echo "<pre>"; print_r($discountItems); echo "</pre>";
        //$product_options = $this->entityManager->getRepository('ZSELEX_Entity_ProductOption')->getProductOptionList($main_args);
        // echo "<pre>"; print_r($product_options); echo "</pre>";
        $total         = $discountCount;
        // Set the possible status for later use
        $aStatus       = array(
            'InActive',
            'Active'
        );
        $this->view->assign('aStatus', $aStatus);

        // Assign the items to the template
        // $this->view->assign('product_options', $product_options);
        $this->view->assign('discountItems', $discountItems);
        $this->view->assign('total', $total);

        // Assign the current status filter and the possible ones
        $this->view->assign('status', $status);
        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('order', $order);
        return $this->view->fetch('admin/ishopproducts/view_discount.tpl');
    }

    public function createDiscount($args)
    {
        $shop_id = FormUtil::getPassedValue('shop_id', 0, 'GETPOST');
        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        if ($_POST) {

            //echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
            $this->checkCsrfToken();
            $formElements = FormUtil::getPassedValue('formElements',
                    isset($args ['formElements']) ? $args ['formElements'] : null,
                    'GETPOST');
            //  echo "<pre>"; print_r($formElements); echo "</pre>"; exit;

            $discount = new ZSELEX_Entity_Discount();
            $shop     = $this->entityManager->find('ZSELEX_Entity_Shop',
                $formElements ['shop_id']);
            $discount->setShop($shop);
            $discount->setDiscount_code($formElements['code']);
            $discount->setDiscount($formElements['value']);
            $discount->setStatus($formElements['status']);


            $this->entityManager->persist($discount);
            $this->entityManager->flush();
            $InsertId = $discount->getDiscount_id();
            if ($InsertId > 0) {

                return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                            'discount',
                            array(
                            'shop_id' => $formElements ['shop_id']
                )));
                LogUtil::registerStatus($this->__('Done! Created discount'));
            }
        }

        return $this->view->fetch('admin/ishopproducts/create_discount.tpl');
    }

    public function editDiscount($args)
    {
        $shop_id    = FormUtil::getPassedValue('shop_id', 0, 'GETPOST');
        $discountId = FormUtil::getPassedValue('discount_id', 0, 'GETPOST');
        //  echo $discountId; exit;

        if (!is_numeric($shop_id) || empty($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }
        if (!is_numeric($discountId) || empty($discountId)) {
            return LogUtil::registerError($this->__f('Error! The ID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($discountId)), 403);
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $this->view->assign('discount_id', $discountId);

        if ($_POST) {
            $this->checkCsrfToken();
            $formElements = FormUtil::getPassedValue('formElements',
                    isset($args ['formElements']) ? $args ['formElements'] : null,
                    'GETPOST');
            //   echo "<pre>"; print_r($formElements); echo "</pre>"; exit;

            $updateObj      = array(
                'discount' => $formElements ['value'],
                'discount_code' => $formElements ['code'],
                'status' => $formElements ['status']
            );
            $updateDiscount = $this->entityManager->getRepository('ZSELEX_Entity_Discount')->updateEntity(null,
                'ZSELEX_Entity_Discount', $updateObj,
                array(
                'a.discount_id' => $discountId
            ));
            LogUtil::registerStatus($this->__('Done! Updated Discount'));
            return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'discount',
                        array(
                        'shop_id' => $formElements ['shop_id']
            )));
        }

        $discount = $this->entityManager->getRepository('ZSELEX_Entity_Discount')
            ->get(array(
            'entity' => 'ZSELEX_Entity_Discount',
            'where' => array('a.discount_id' => $discountId),
            // 'exit'=>true
        ));

        //  echo "<pre>"; print_r($discount); echo "</pre>";
        $this->view->assign('discount', $discount);

        return $this->view->fetch('admin/ishopproducts/create_discount.tpl');
    }

    public function deleteDiscount($args)
    {
        $discountId = FormUtil::getPassedValue('discount_id',
                isset($args ['discount_id']) ? $args ['discount_id'] : null,
                'REQUEST');
        $shopId     = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : null, 'REQUEST');
        // echo $shopId; exit;

        if (!is_numeric($shopId)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shopId)), 403);
        }
        if ($this->shopPermission($shopId) < 1) {
            return LogUtil::registerPermissionError();
        }

        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');

        if (empty($confirmation)) {
            $this->view->assign('IdValue', $discountId);
            $this->view->assign('confirm_title',
                $this->__f('Delete %s', $this->__('Product Option')));
            $this->view->assign('confirm_msg',
                $this->__f('Do you want to delete this %s',
                    $this->__('Discount')));
            $this->view->assign('IdName', 'discount_id');
            $this->view->assign('shop_id', $shopId);
            $this->view->assign('submitFunc', 'deleteDiscount');
            $this->view->assign('cancelFunc', 'discount');
            // Return the output that has been generated by this function
            return $this->view->fetch('admin/deletecommon2.tpl');
        }
        $this->checkCsrfToken();

        $result = $this->entityManager->getRepository('ZSELEX_Entity_Discount')->deleteEntity(null,
            'ZSELEX_Entity_Discount',
            array(
            'a.discount_id' => $discountId
        ));

        // Delete
        if ($result) {
            // Success

            LogUtil::registerStatus($this->__('Done! Discount has been deleted successfully.'));
            // Let any hooks know that we have deleted an item
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'discount',
                    array(
                    'shop_id' => $shopId
        )));
    }

    public function testpage()
    {

        // echo "Comes here....";
        return $this->view->fetch('test/test.tpl');
    }
}
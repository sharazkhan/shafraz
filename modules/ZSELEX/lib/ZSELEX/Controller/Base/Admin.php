<?php
// ini_set("display_errors", "1");
/**
 * Copyright 2013.
 *
 * ZSELEX
 * Demonstration of Zikula Module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control Admin interface.
 */
class ZSELEX_Controller_Base_Admin extends Zikula_AbstractController
{
    const ACTION_SUBMIT   = 1;
    const ACTION_UPDATE   = 2;
    const ACTION_REJECT   = 3;
    const ACTION_RETURN   = 4;
    const STATUS_ACTIVE   = 1;
    const STATUS_INACTIVE = 0;
    const DEMO            = 1;
    const PAID            = 2;

    public $errormsg;
    public $statusmsg;
    public $ownername;
    public $owner_id;
    public $shopname;

    // KIMENEMARK BEGIN
    public function filesize_recursive($path)
    {
        error_reporting(0);
        if (!file_exists($path)) {
            return 0;
        }
        if (is_file($path)) {
            return filesize($path);
        }
        $ret = 0;
        foreach (glob($path.'/*') as $fn) {
            $ret += $this->filesize_recursive($fn);
        }

        return $ret;
    }

    public function display_size($size)
    {
        $sizes = array(
            'B',
            'kB',
            'MB',
            'GB',
            'TB',
            'PB',
            'EB',
            'ZB',
            'YB'
        );
        if ($retstring === null) {
            $retstring = '%01.2f %s';
        }
        $lastsizestring = end($sizes);
        foreach ($sizes as $sizestring) {
            if ($size < 1024) {
                break;
            }
            if ($sizestring != $lastsizestring) {
                $size /= 1024;
            }
        }
        if ($sizestring == $sizes [0]) {
            $retstring = '%01d %s';
        } // Bytes aren't normally fractional
        return sprintf($retstring, $size, $sizestring);
    }
    // KIMENEMARK END

    /**
     * the main administration function
     * This function is the default function, and is called whenever the
     * module is initiated without defining arguments.
     */
    public function main()
    {
        // $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshop'));
    }

    /**
     * set caching to false for all admin functions
     */
    public function postInitialize()
    {
        PageUtil::addVar('jsgettext', 'module_zselex_js:ZSELEX');
        // $this->view->setCaching(false);
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        if (!empty($shop_id)) {
            $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                    $args      = array(
                    'shop_id' => $shop_id
            ));

            $owner    = DBUtil::selectObjectByID('zselex_shop_owners', $shop_id,
                    'shop_id');
            $owner_id = $owner ['user_id'];

            $getShop = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                    $args    = array(
                    'table' => 'zselex_shop',
                    'fields' => array(
                        'shop_id',
                        'shop_name'
                    )
            ));
            // echo $getShop['shop_name'];
        }
        $this->owner_id  = $owner_id;
        $this->ownername = $ownerName;
        $this->shopname  = $getShop ['shop_name'];
        /*
         * if (!$_POST) {
         * $this->payMentAlert($shop_id);
         * }
         */
    }

    public function shopheader()
    {
        // return;
        // echo "shopheader";
        // echo "cookie :" . $_COOKIE['shop_menu'];
        // echo "Owner :" . $this->ownername;
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        if (!(int) ($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)));
        }

        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Shop');

        if (!empty($shop_id)) {


            $shop = $repo->get(array(
                'entity' => 'ZSELEX_Entity_Shop',
                'fields' => array(
                    'a.shop_name',
                    'b.name image',
                    'c.image_name image_name',
                    'a.default_img_frm',
                    'a.shop_id'
                ),
                'joins' => array(
                    'LEFT JOIN a.shop_images b WITH b.defaultImg=1',
                    'LEFT JOIN a.shop_gallery c WITH c.defaultImg=1',
                    'LEFT JOIN a.shop_owners d'
                ),
                'where' => array(
                    'a.shop_id' => $shop_id
                )
                // 'b.defaultImg' => 1,
                // 'c.defaultImg' => 1
                ,
                'groupby' => 'a.shop_id'
            ));
            if ($shop) {
                $shop ['uname'] = $this->ownername;
            }
        }

        // echo "<pre>"; print_r($shop); echo "</pre>";

        if (!empty($_SESSION ['admincart'])) {
            $sessionCount = count($_SESSION ['admincart']);
        }

        if (!empty($_COOKIE ['admincart'])) {
            $cookieCount = count($_COOKIE ['admincart']);
        }
        $count            = (empty($cookieCount)) ? $sessionCount : $cookieCount;
        $count            = (!empty($count)) ? $count : 0;
        $this->view->assign('count', $count);
        $shop_menu_cookie = $_COOKIE ['shop_menu'];
        if ($shop_menu_cookie == '1') {
            $displayval = 'block';
        } else {
            $displayval = 'none';
        }
        $this->view->assign('shop_menu_cookie', $shop_menu_cookie);
        $this->view->assign('displayval', $displayval);
        $func = FormUtil::getPassedValue('func', null, 'REQUEST');
        $this->view->assign('func', $func);
        $this->view->assign('shop_id', $shop_id);

        $user_id = UserUtil::getVar('uid');


        $service_count = $repo->getCount(null, 'ZSELEX_Entity_ServiceBasket',
            'basket_id', array(
            'a.user_id' => $user_id
        ));

        $this->view->assign('count', $count);

        $this->view->assign('service_count', $service_count);

        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('shop', $shop);
        $this->reminderNotifications($shop_id);

        return $this->view->fetch('admin/shop_header.tpl');
    }

    /**
     * present administrator options to change module configuration
     *
     * @return config template
     */
    public function modifyconfig()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $modvars = $this->getVars();



        // echo "<pre>"; print_r($modvars); echo "</pre>"; exit;
        $groups = ModUtil::apiFunc('Groups', 'user', 'getAll',
                array(
                'numitems' => 10000
        ));

        $this->view->assign('groups', $groups);

        $languages = ZLanguage::getInstalledLanguages();
        $this->view->assign('languages', $languages);
        $thislang  = ZLanguage::getLanguageCode();
        $this->view->assign('thislang', $thislang);

        return $this->view->fetch('admin/modifyconfig.tpl');
    }

    /**
     * sets module variables as requested by admin
     *
     * @return status/error ->back to modify config page
     */
    public function updateconfig()
    {
        // pnModCallHooks('ZSELEX_CONFIG', 'update', '123' , array ('module' => 'ZSELEX'));
        $this->checkCsrfToken();

        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        $modvars = array();

        $modvars ['shoporderby']    = FormUtil::getPassedValue('shoporderby', 0);
        $modvars ['shopfrontlimit'] = FormUtil::getPassedValue('shopfrontlimit',
                0);

        $modvars ['shopAdminGroup'] = FormUtil::getPassedValue('shopAdminGroup',
                0);
        $modvars ['shopOwnerGroup'] = FormUtil::getPassedValue('shopOwnerGroup',
                0);

        $modvars ['themeprice']           = FormUtil::getPassedValue('themeprice');
        $modvars ['default_country_id']   = FormUtil::getPassedValue('default_country_id');
        $modvars ['default_country_name'] = FormUtil::getPassedValue('default_country_name');

        $modvars ['fullimagewidth']  = FormUtil::getPassedValue('fullimagewidth');
        $modvars ['fullimageheight'] = FormUtil::getPassedValue('fullimageheight');

        $modvars ['medimagewidth']  = FormUtil::getPassedValue('medimagewidth');
        $modvars ['medimageheight'] = FormUtil::getPassedValue('medimageheight');

        $modvars ['thumbimagewidth']  = FormUtil::getPassedValue('thumbimagewidth');
        $modvars ['thumbimageheight'] = FormUtil::getPassedValue('thumbimageheight');

        $modvars ['serviceexpiryday'] = FormUtil::getPassedValue('serviceexpiryday');

        $modvars ['diskquotaitem'] = FormUtil::getPassedValue('diskquotaitem');

        $modvars ['invoiceday'] = FormUtil::getPassedValue('invoiceday');
        $modvars ['invoiceday'] = FormUtil::getPassedValue('invoiceday');

        $termsConditionInfo             = serialize(FormUtil::getPassedValue('termsConditionInfo'));
        $modvars ['termsConditionInfo'] = $termsConditionInfo;

        $modvariable = $this->getVars();
        $themPrice   = $modvariable ['themeprice'];

        $this->setVars($modvars);
        $modvars = $this->getVars();

        // clear the cache
        $this->view->clear_cache();

        LogUtil::registerStatus($this->__('Done! ZSELEX configuration has been updated successfully.'));
        // return $this->modifyconfig();
        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'modifyconfig'));
    }

    public function updateShopKeywords()
    {
        error_reporting(E_ALL);

        $em    = ServiceUtil::getService('doctrine.entitymanager');
        $start = microtime(true);
        try {
            if ($_SERVER ['SERVER_NAME'] == 'localhost') {
                $path = $_SERVER ['DOCUMENT_ROOT'].'/zselex/scripts/keyword_update.php';
                chmod($path, 0777); //
                // exec('/usr/bin/php -c php.ini '.$path.' > /dev/null &');
            } else { // SERVER
                // echo "comes here!!!"; exit;
                $path = $_SERVER ['DOCUMENT_ROOT'].'/scripts/keyword_update.php';
                chmod($path, 0777);
                // exec('/usr/bin/php -c php.ini '.$path.' > /dev/null &');
            }
            $cmd  = 'php '.$path." ".pnGetBaseURL();
            // echo $cmd; exit;
            ZSELEX_Util::execInBackground($cmd);
            $end  = microtime(true);
            $diff = $end - $start;

            LogUtil::registerStatus($this->__('Keyword updation started at background'));

            // $this->entityManager->getRepository('ZSELEX_Entity_Shop')->insertKeyword();
        } catch (\Exception $e) {
            echo 'Message: '.$e->getMessage().'<br>';
            // echo $query->getSQL();
            exit();
        }

        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'modifyconfig'));
        // echo $exec;
        // exit;
    }

    public function updateEventTemp()
    {
        // echo "comes here"; exit;
        LogUtil::registerStatus($this->__('Event temperory table updation started at background'));
        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Event');

        if ($_SERVER ['SERVER_NAME'] == 'localhost') {
            $path = $_SERVER ['DOCUMENT_ROOT'].'/zselex/scripts/event_sync_all.php';
            chmod($path, 0777);
        } else {
            $path = $_SERVER ['DOCUMENT_ROOT'].'/scripts/event_sync_all.php';
            chmod($path, 0777);
        }
        exec('/usr/bin/php -c php.ini '.$path.' > /dev/null &'); // run at bg

        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'modifyconfig'));
    }

    public function createDateRangeArrays($start, $end)
    {
        $todayDate = date('Y-m-d');
        if ($end > $todayDate) {
            // $start = $todayDate;
        }
        $range = array();

        if (is_string($start) === true) {
            $start = strtotime($start);
        }
        if (is_string($end) === true) {
            $end = strtotime($end);
        }

        if ($start > $end) {
            return $this->createDateRangeArray($end, $start);
        }

        do {
            $range [] = date('Y-m-d', $start);
            $start    = strtotime('+ 1 day', $start);
        } while ($start <= $end);

        return $range;
    }

    public function paymentgatewaysettings1()
    {
        if ($_POST) {
            $this->checkCsrfToken();
            $item = array(
                'ppemail' => FormUtil::getPassedValue('paypalzselexemail', 0)
            );

            $modvars ['paypalzselexemail'] = FormUtil::getPassedValue('paypalzselexemail',
                    0);
            $this->setVars($modvars);
            $validationerror               = ZSELEX_Util::validatePayPal($item);

            if ($validationerror !== false) {
                // log the error found if any
                if ($validationerror !== false) {
                    LogUtil::registerError(nl2br($validationerror));
                }
                SessionUtil::setVar('ppitem', $item);

                $this->redirect(ModUtil::url('ZSELEX', 'admin',
                        'paymentgatewaysettings'));
            } else {
                // As we're not previewing the item let's remove it from the session
                SessionUtil::delVar('ppitem');
            }

            $modvars = array();

            $modvars = $this->getVars();
            $this->view->clear_cache();
            LogUtil::registerStatus($this->__('Done! Payment settings has been updated successfully.'));
        }

        return $this->view->fetch('admin/paymentgatewaysettings.tpl');
    }

    public function shopconfig($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        $loguser = UserUtil::getVar('uid');
        $loguser = !empty($loguser) ? $loguser : 0;
        if (!(int) ($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)));
        }

        $servicePerm = 2;

        $modvars = $this->getVars();

        $fields = array(
            'default_img_frm'
        );


        $fields      = array(
            'shop_id',
            'shop_name',
            'theme',
            'default_img_frm',
            'opening_hours'
        );
        $joinInfo [] = array(
            'join_table' => 'zselex_shop_owners',
            'join_field' => array(
                'main'
            ),
            'object_field_name' => array(
                'main'
            ),
            'compare_field_table' => 'shop_id', // main table
            'compare_field_join' => 'shop_id'
        );

        $obj  = ModUtil::apiFunc('ZSELEX', 'user', 'getByJoin',
                $args = array(
                'table' => 'zselex_shop',
                'where' => "tbl.shop_id=$shop_id",
                'joinInfo' => $joinInfo
        ));

        $where = " shop_id='".$shop_id."' AND user_id!=''";

        // echo "<pre>"; print_r($obj); echo "</pre>"; exit;
        $getCountArgs = array(
            'table' => 'zselex_shop_owners',
            'where' => $where,
            'Id' => 'id'
        );

        $ownerIsThere = ModUtil::apiFunc('ZSELEX', 'admin', 'countElements',
                $getCountArgs);

        if ($ownerIsThere > 0) {
            $this->view->assign('ownerExist', $ownerIsThere);
            $owner = DBUtil::selectObjectByID('zselex_shop_owners', $shop_id,
                    'shop_id');
            $this->view->assign('owner', $owner ['user_id']);
        }

        $articleServiceExist = ModUtil::apiFunc('ZSELEX', 'admin',
                'serviceExistBlock',
                $args                = array(
                'shop_id' => $shop_id,
                'type' => 'createarticle'
        ));

        $articles = ModUtil::apiFunc('ZSELEX', 'admin', 'getShopArticleEvents',
                $args     = array(
                'shop_id' => $shop_id
        ));

        // echo "<pre>"; print_r($articles); echo "</pre>"; exit;

        $this->view->assign('servicePerm', $servicePerm);
        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('obj', $obj);
        $this->view->assign('articles', $articles);
        $this->view->assign('articleServiceExist', $articleServiceExist);

        return $this->view->fetch('admin/shopconfig.tpl');
    }

    public function updateshopconfig($args)
    {
        $shop_id         = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $default_img_frm = FormUtil::getPassedValue('default_img_frm', null,
                'POST');
        $user_id         = FormUtil::getPassedValue('user_id', null, 'POST');
        $owner_id        = FormUtil::getPassedValue('owner_id', null, 'POST');
        $shopDesign      = FormUtil::getPassedValue('shopdesign', null, 'POST');
        $opening_hours   = FormUtil::getPassedValue('opening_hours', null,
                'POST');
        $shop_info       = FormUtil::getPassedValue('shop_info', null, 'POST');
        $shop_info       = strip_tags($shop_info);

        // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
        // echo $shop_info; exit;
        $obj      = array(
            'shop_info' => $shop_info,
            'default_img_frm' => $default_img_frm,
            'opening_hours' => $opening_hours
        );
        $pntables = pnDBGetTables();
        $column   = $pntables ['zselex_shop_column'];
        $where    = "WHERE $column[shop_id]=$shop_id";

        DBUTil::updateObject($obj, 'zselex_shop', $where);

        $mainShop = FormUtil::getPassedValue('mainshop', null, 'REQUEST');

        if ($mainShop == 1) { // setting as main shop
            $where1 = "WHERE shop_id=$shop_id";
            $obj1   = array(
                'main' => 1
            );
            if (DBUTil::updateObject($obj1, 'zselex_shop', $where1)) {
                // $sql = "UPDATE zselex_shop set main='0' WHERE user_id!='' AND user_id='" . $user_id . "' AND shop_id NOT IN($shop_id)";
                // echo $sql; exit;
                // DBUtil::executeSQL($sql);
                $obj2   = array(
                    'main' => 0
                );
                $where2 = "user_id!='' AND user_id='".$user_id."' AND shop_id NOT IN($shop_id)";
                DBUTil::updateObject($obj2, 'zselex_shop', $where2);
            }

            $u    = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElementWhere',
                    $args = array(
                    'table' => 'zselex_shop_owners',
                    'items' => array(
                        'main' => '1'
                    ),
                    'where' => array(
                        'shop_id' => $shop_id
                    )
            ));

            DBUtil::updateObject($obj   = array(
                'main' => '0'
                ), 'zselex_shop_owners',
                $where = "WHERE user_id='".$owner_id."'
                AND shop_id NOT IN($shop_id)");
        }

        if (!empty($shopDesign)) {
            $item        = array(
                'theme' => $shopDesign
            );
            $updateTheme = array(
                'table' => 'zselex_shop',
                'IdValue' => $shop_id,
                'IdName' => 'shop_id',
                'element' => $item
            );
            // echo "<pre>"; print_r($updateTheme); echo "</pre>"; exit;
            $result      = ModUtil::apiFunc('ZSELEX', 'admin',
                    'updateSingleItem', $updateTheme);
        }

        $this->view->clear_cache();

        LogUtil::registerStatus($this->__('Done! Site configuration has been updated successfully.'));

        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'shopconfig',
                array(
                'shop_id' => $shop_id
        )));
        // return $this->shopconfig();
    }

    public function createtype($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        // Any item set for preview will be stored in a session var
        // Once the new type is posted we'll clear the session var.
        $item      = array();
        $sess_item = SessionUtil::getVar('createtype');
        // get the type parameter so we can decide what template to use
        $typetable = FormUtil::getPassedValue('typetable', 'admin', 'REQUEST');

        // Set the default values for the form. If not previewing an item prior
        // to submission these values will be null but do need to be set
        $item ['type_id']     = isset($sess_item ['type_id']) ? $sess_item ['type_id']
                : '';
        $item ['type_name']   = isset($sess_item ['type_name']) ? $sess_item ['type_name']
                : '';
        $item ['description'] = isset($sess_item ['description']) ? $sess_item ['description']
                : '';
        $item ['created']     = isset($sess_item ['created']) ? $sess_item ['created']
                : DateUtil::getDatetime(null, '%Y-%m-%d %H:%M');
        $item ['updated']     = isset($sess_item ['updated']) ? $sess_item ['updated']
                : DateUtil::getDatetime(null, '%Y-%m-%d %H:%M');

        // Get the module vars
        $modvars = $this->getVars();

        $this->view->assign('accessadd', 0);
        // Assign the item to the template
        $this->view->assign('item', $item);

        return $this->view->fetch('admin/create_type.tpl');
    }

    public function updatetype($args)
    {

        // Get parameters cr_date whatever input we need
        $typetable = FormUtil::getPassedValue('typetable',
                isset($args ['typetable']) ? $args ['typetable'] : null, 'POST');

        // echo "<pre>"; print_r($typetable); echo "</pre>"; exit;
        // Create the item array for processing
        $item = array(
            'type_name' => $typetable ['type_name'],
            'description' => isset($typetable ['description']) ? $typetable ['description']
                    : '',
            'status' => isset($typetable ['status']) ? $typetable ['status'] : 0
        );

        $itemValidate = array(
            'type_name|Type Name' => $typetable ['type_name'],
            'description|Description' => isset($typetable ['description']) ? $typetable ['description']
                    : ''
        );

        // $url = ModUtil::url('ZSELEX', 'admin', 'createtype');
        //
		// return LogUtil::registerError($this->__('Fields cannot be empty.'), null, $url);
        // Validate the input
        // ******* Validation happens here ******//
        $validationerror = ZSELEX_Util::validateItems($itemValidate);

        if ($validationerror !== false) {
            // log the error found if any
            if ($validationerror !== false) {
                LogUtil::registerError(nl2br($validationerror));
            }
            SessionUtil::setVar('createtype', $item);

            $this->redirect(ModUtil::url('ZSELEX', 'admin', 'createtype'));
        } else {
            // As we're not previewing the item let's remove it from the session
            SessionUtil::delVar('createtype');
        }
        // ******* Validation ends ******//

        $type_id = isset($typetable ['type_id']) ? $typetable ['type_id'] : null;

        // get all module vars
        $modvars = $this->getVars();

        if (!isset($typetable ['type_id']) || empty($typetable ['type_id'])) {
            // Create the zselex type
            $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement',
                    array(
                    'table' => 'zselex_type',
                    'element' => $item,
                    'Id' => 'type_id'
            ));
            if ($result != false) {
                // Success
                LogUtil::registerStatus($this->__('Done! Type has been created successfully'));
            } else {
                // fail! type not created
                throw new Zikula_Exception_Fatal($this->__('Story not created for unknown reason (Api failure).'));

                return false;
            }
        } else {
            // update the type
            $args   = array(
                'table' => 'zselex_type',
                'IdValue' => $type_id,
                'IdName' => 'type_id',
                'element' => $typetable
            );
            $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement', $args);
            if ($result) {
                LogUtil::registerStatus($this->__('Done! Type has been updated successfully.'));
            } else {
                // fail! type not updated
                throw new Zikula_Exception_Fatal($this->__('Story not updated for unknown reason (Api failure).'));

                return false;
            }
        }

        if ($item ['action'] == self::ACTION_RETURN) {
            SessionUtil::setVar('createtype', $item);
            $this->redirect(ModUtil::url('ZSELEX', 'admin', 'createtype'));
        }

        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewtype'));
    }

    public function viewtype($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        // initialize sort array - used to display sort classes and urls
        $sort   = array();
        $fields = array(
            'type_id',
            'type_name',
            'status',
            'cr_date',
            'lu_date'
        ); // possible sort fields

        $itemsperpage  = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 15,
                'GETPOST');
        $startnum      = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $status        = FormUtil::getPassedValue('status',
                isset($args ['status']) ? $args ['status'] : null, 'GETPOST');
        $order         = FormUtil::getPassedValue('order',
                isset($args ['order']) ? $args ['order'] : 'cr_date', 'GETPOST');
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
            $sort ['url'] [$field] = ModUtil::url('ZSELEX', 'admin', 'viewtype',
                    array(
                    'status' => $status,
                    'filtercats_serialized' => serialize($filtercats),
                    'order' => $field,
                    'sdir' => $sdir
            ));
        }
        $this->view->assign('sort', $sort);

        $this->view->assign('filter_active', $status);

        $sql = ' SELECT a.* FROM zselex_type AS a
                  WHERE a.type_id IS NOT NULL ';
        if (isset($status) && $status != '') {
            $sql .= ' AND a.status = '.$status;
        }
        if (isset($searchtext) && $searchtext != '') {
            $sql .= " AND a.type_name LIKE '%".DataUtil::formatForStore($searchtext)."%'";
        }
        if (isset($order) && $order != '') {
            $sql .= ' ORDER BY a.'.$order.' '.$orderdir;
        }

        // Get all zselex stories
        $getArgs = array(
            'sql' => $sql,
            'startnum' => $startnum,
            'numitems' => $itemsperpage
        );
        $items   = ModUtil::apiFunc('ZSELEX', 'admin', 'getAll', $getArgs);
        $where   = ' type_id IS NOT NULL';
        if (isset($status) && $status != '') {
            $where .= ' AND status = '.$status;
        }
        if (isset($searchtext) && $searchtext != '') {
            $where .= " AND type_name LIKE '%".DataUtil::formatForStore($searchtext)."%'";
        }
        $getCountArgs = array(
            'table' => 'zselex_type',
            'where' => $where,
            'Id' => 'type_id',
            'status' => $status
        );

        $total_types = ModUtil::apiFunc('ZSELEX', 'admin', 'countElements',
                $getCountArgs);

        // Set the possible status for later use
        $itemstatus = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );

        $typesitems = array();
        foreach ($items as $item) {
            $options = array();

            // $options[] = array('url' => ModUtil::url('ZSELEX', 'admin', 'displaytype', array('type_id' => $item['type_id'])),
            // 'image' => '14_layer_visible.png',
            // 'title' => $this->__('View'));

            if (SecurityUtil::checkPermission('ZSELEX::',
                    "{$item['cr_uid']}::{$item['type_id']}", ACCESS_EDIT)) {
                $options [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'modifytype',
                        array(
                        'type_id' => $item ['type_id']
                    )),
                    'image' => 'xedit.png',
                    'title' => $this->__('Edit')
                );

                if ((SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['type_id']}", ACCESS_DELETE))
                    || SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['type_id']}", ACCESS_ADMIN)) {
                    $options [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin', 'deletetype',
                            array(
                            'type_id' => $item ['type_id']
                        )),
                        'image' => '14_layer_deletelayer.png',
                        'title' => $this->__('Delete')
                    );
                }
            }
            $item ['options'] = $options;

            $item ['infuture'] = DateUtil::getDatetimeDiff_AsField($item ['cr_date'],
                    DateUtil::getDatetime(), 6) < 0;
            $typesitems []     = $item;
        }

        // Assign the items to the template
        $this->view->assign('typesitems', $typesitems);

        $this->view->assign('total_types', $total_types);

        // Assign the current status filter and the possible ones
        $this->view->assign('status', $status);
        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('order', $order);
        // Return the output that has been generated by this function
        return $this->view->fetch('admin/viewtype.tpl');
    }

    public function createshoptype($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        // Any item set for preview will be stored in a session var
        // Once the new type is posted we'll clear the session var.
        $item      = array();
        $sess_item = SessionUtil::getVar('createshoptype');
        // get the type parameter so we can decide what template to use
        $typetable = FormUtil::getPassedValue('typetable', 'admin', 'REQUEST');

        // Set the default values for the form. If not previewing an item prior
        // to submission these values will be null but do need to be set
        $item ['type_id']     = isset($sess_item ['type_id']) ? $sess_item ['type_id']
                : '';
        $item ['type_name']   = isset($sess_item ['type_name']) ? $sess_item ['type_name']
                : '';
        $item ['description'] = isset($sess_item ['description']) ? $sess_item ['description']
                : '';
        $item ['created']     = isset($sess_item ['created']) ? $sess_item ['created']
                : DateUtil::getDatetime(null, '%Y-%m-%d %H:%M');
        $item ['updated']     = isset($sess_item ['updated']) ? $sess_item ['updated']
                : DateUtil::getDatetime(null, '%Y-%m-%d %H:%M');

        // Get the module vars
        $modvars = $this->getVars();

        $this->view->assign('accessadd', 0);
        // Assign the item to the template
        $this->view->assign('item', $item);

        return $this->view->fetch('admin/createshoptype.tpl');
    }

    public function modifyshoptype($args)
    {
        $id       = FormUtil::getPassedValue('id',
                isset($args ['id']) ? $args ['id'] : null, 'GETPOST');
        $objectid = FormUtil::getPassedValue('objectid',
                isset($args ['objectid']) ? $args ['objectid'] : null, 'GET');
        // At this stage we check to see if we have been passed $objectid
        if (!empty($objectid)) {
            $id = $objectid;
        }

        // Check if we're redirected to preview
        $inpreview = false;
        $item      = SessionUtil::getVar('createshoptype');
        if (!empty($item)) {
            $id = $item ['shoptype_id'];
        }

        // Validate the essential parameters
        if (empty($id)) {
            return LogUtil::registerArgsError();
        }
        $args = array(
            'table' => 'zselex_shop_types',
            'IdValue' => $id,
            'IdName' => 'shoptype_id'
        );
        // Get the news type in the db
        $item = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $args);

        if ($item === false) {
            return LogUtil::registerError($this->__('Error! Item not found.'),
                    403);
        }

        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                $dbitem ['cr_uid'].'::'.$id, ACCESS_EDIT),
            LogUtil::getErrorMsgPermission());

        // Get the module configuration vars
        $modvars = $this->getVars();

        $this->view->assign('accessadd', 0);
        if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
            $this->view->assign('accessadd', 1);
        }

        // Assign the item to the template
        $this->view->assign('item', $item);

        // lock the page so others cannot edit it
        if (ModUtil::available('PageLock')) {
            $returnUrl = ModUtil::url('ZSELEX', 'admin', 'viewtype');
            ModUtil::apiFunc('PageLock', 'admin', 'pageLock',
                array(
                'lockName' => "ZSELEXtype{$item['id']}",
                'returnUrl' => $returnUrl
            ));
        }
        $this->view->assign('page', 0); // this var is used in the ajax version, but this is here to prevent E_NOTICE errors
        // Return the output that has been generated by this function
        return $this->view->fetch('admin/createshoptype.tpl');
    }

    public function submitshoptype($args)
    {

        // Get parameters cr_date whatever input we need
        $shoptype = FormUtil::getPassedValue('shoptype',
                isset($args ['shoptype']) ? $args ['shoptype'] : null, 'POST');

        // echo "<pre>"; print_r($typetable); echo "</pre>"; exit;
        // Create the item array for processing
        $item = array(
            'shoptype' => $shoptype ['name'],
            'description' => isset($shoptype ['description']) ? $shoptype ['description']
                    : '',
            'status' => isset($shoptype ['status']) ? $shoptype ['status'] : 0
        );

        $itemValidate = array(
            'type_name|Type Name' => $shoptype ['name'],
            'description|Description' => isset($shoptype ['description']) ? $shoptype ['description']
                    : ''
        );

        // $url = ModUtil::url('ZSELEX', 'admin', 'createtype');
        //
		// return LogUtil::registerError($this->__('Fields cannot be empty.'), null, $url);
        // Validate the input
        // ******* Validation happens here ******//
        $validationerror = ZSELEX_Util::validateItems($itemValidate);

        if ($validationerror !== false) {
            // log the error found if any
            if ($validationerror !== false) {
                LogUtil::registerError(nl2br($validationerror));
            }
            SessionUtil::setVar('createshoptype', $item);

            $this->redirect(ModUtil::url('ZSELEX', 'admin', 'createshoptype'));
        } else {
            // As we're not previewing the item let's remove it from the session
            SessionUtil::delVar('createshoptype');
        }
        // ******* Validation ends ******//

        $shoptype_id = isset($shoptype ['shoptypeid']) ? $shoptype ['shoptypeid']
                : null;

        // get all module vars
        $modvars = $this->getVars();

        if (!isset($shoptype ['shoptypeid']) || empty($shoptype ['shoptypeid'])) {
            // Create the zselex type
            $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement',
                    array(
                    'table' => 'zselex_shop_types',
                    'element' => $item,
                    'Id' => 'shoptype_id'
            ));
            if ($result != false) {
                // Success
                LogUtil::registerStatus($this->__('Done! Shop Type has been created successfully'));
            } else {
                // fail! type not created
                throw new Zikula_Exception_Fatal($this->__('Story not created for unknown reason (Api failure).'));

                return false;
            }
        } else {
            // update the type
            $args   = array(
                'table' => 'zselex_shop_types',
                'IdValue' => $shoptype_id,
                'IdName' => 'shoptype_id',
                'element' => $shoptype
            );
            $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement', $args);
            if ($result) {
                LogUtil::registerStatus($this->__('Done! Shop Type has been updated successfully.'));
            } else {
                // fail! type not updated
                throw new Zikula_Exception_Fatal($this->__('Story not updated for unknown reason (Api failure).'));

                return false;
            }
        }

        if ($item ['action'] == self::ACTION_RETURN) {
            SessionUtil::setVar('createshoptype', $item);
            $this->redirect(ModUtil::url('ZSELEX', 'admin', 'createshoptype'));
        }

        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshoptypes'));
    }

    public function viewshoptypes($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
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
                isset($args ['order']) ? $args ['order'] : 'shoptype_id',
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
                    'viewshoptypes',
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

        $where = " shoptype_id!=''";
        if (isset($status) && $status != '') {
            $where .= ' AND status = '.$status;
        }
        if (isset($searchtext) && $searchtext != '') {
            $where .= " AND shoptype LIKE '%".DataUtil::formatForStore($searchtext)."%'";
        }
        if (isset($order) && $order != '') {
            $where .= ' ORDER BY '.$order.' '.$orderdir;
        }

        // $items = ModUtil::apiFunc('ZSELEX', 'admin', 'getAll', $getArgs);

        $items = array();

        $items = ModUtil::apiFunc('ZSELEX', 'admin', 'getAllElements',
                $args  = array(
                'table' => 'zselex_shop_types',
                'where' => $where,
                'startnum' => $startnum,
                'numitems' => $itemsperpage,
                'orderBy' => '',
                'useJoins' => ''
        ));
        // echo "<pre>"; print_r($items); echo "</pre>";

        $countargs = array(
            'table' => 'zselex_shop_types',
            'where' => $where,
            'Id' => 'shoptype_id',
            'status' => $status
        );
        $count     = ModUtil::apiFunc('ZSELEX', 'admin', 'countElements',
                $countargs);
        // echo "<pre>"; print_r($items); echo "</pre>";

        $total_count = $count;

        // Set the possible status for later use
        $itemstatus = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );

        $shoptypes = array();
        foreach ($items as $item) {
            $options = array();
            if (SecurityUtil::checkPermission('ZSELEX::',
                    "{$item['cr_uid']}::{$item['id']}", ACCESS_EDIT)) {
                $options [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'modifyshoptype',
                        array(
                        'id' => $item ['shoptype_id']
                    )),
                    'image' => 'xedit.png',
                    'title' => $this->__('Edit')
                );

                if ((SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['id']}", ACCESS_DELETE)) || SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['type_id']}", ACCESS_ADD)) {
                    $options [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin',
                            'deleteshoptype',
                            array(
                            'id' => $item ['shoptype_id']
                        )),
                        'image' => '14_layer_deletelayer.png',
                        'title' => $this->__('Delete')
                    );
                }
            }
            $item ['options'] = $options;

            $item ['infuture'] = DateUtil::getDatetimeDiff_AsField($item ['cr_date'],
                    DateUtil::getDatetime(), 6) < 0;
            $shoptypes []      = $item;
        }

        // Assign the items to the template
        $this->view->assign('shoptypes', $shoptypes);

        $this->view->assign('total_count', $total_count);

        // Assign the current status filter and the possible ones
        $this->view->assign('status', $status);
        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('order', $order);
        // Return the output that has been generated by this function
        return $this->view->fetch('admin/viewshoptypes.tpl');
    }

    /**
     * display item.
     *
     * @author
     *
     * @param
     *        	'type_id' The type ID
     * @param
     *        	'objectid' generic object id maps to type_id if present
     *        	
     * @return string HTML string
     */
    public function displaytype($args)
    {
        // Get parameters from whatever input we need
        $type_id = (int) FormUtil::getPassedValue('type_id', null, 'REQUEST');
        // Validate the essential parameters
        if ((empty($type_id) || !is_numeric($type_id))) {
            return LogUtil::registerArgsError();
        }

        $args = array(
            'table' => 'zselex_type',
            'IdValue' => $type_id,
            'IdName' => 'type_id'
        );
        // Get the type
        if (isset($type_id)) {
            $item = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $args);
        }

        if ($item === false) {
            return LogUtil::registerError($this->__('Error! Item not found.'),
                    403);
        }

        $options = array();

        // $options[] = array('url' => ModUtil::url('ZSELEX', 'admin', 'displaytype', array('type_id' => $item['type_id'])),
        // 'image' => '14_layer_visible.png',
        // 'title' => $this->__('View'));

        if (SecurityUtil::checkPermission('ZSELEX::',
                "{$item['cr_uid']}::{$item['type_id']}", ACCESS_EDIT)) {
            $options [] = array(
                'url' => ModUtil::url('ZSELEX', 'admin', 'modifytype',
                    array(
                    'type_id' => $item ['type_id']
                )),
                'image' => 'xedit.png',
                'title' => $this->__('Edit')
            );

            if ((SecurityUtil::checkPermission('ZSELEX::',
                    "{$item['cr_uid']}::{$item['type_id']}", ACCESS_DELETE)) || SecurityUtil::checkPermission('ZSELEX::',
                    "{$item['cr_uid']}::{$item['type_id']}", ACCESS_ADMIN)) {
                $options [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'deletetype',
                        array(
                        'type_id' => $item ['type_id']
                    )),
                    'image' => '14_layer_deletelayer.png',
                    'title' => $this->__('Delete')
                );
            }
        }
        $item ['options'] = $options;

        $this->view->assign('item', $item);
        // Return the output that has been generated by this function
        return $this->view->fetch('admin/display_type.tpl');
    }

    /**
     * modify a news type.
     *
     * @param
     *        	int 'type_id' the id of the item to be modified
     * @param
     *        	int 'objectid' generic object id maps to type_id if present
     *
     * @author
     *
     * @return string HTML string
     */
    public function modifytype($args)
    {
        $type_id  = FormUtil::getPassedValue('type_id',
                isset($args ['type_id']) ? $args ['type_id'] : null, 'GETPOST');
        $objectid = FormUtil::getPassedValue('objectid',
                isset($args ['objectid']) ? $args ['objectid'] : null, 'GET');
        // At this stage we check to see if we have been passed $objectid
        if (!empty($objectid)) {
            $type_id = $objectid;
        }

        // Check if we're redirected to preview
        $inpreview = false;
        $item      = SessionUtil::getVar('typesitem');
        if (!empty($item)) {
            $type_id = $item ['type_id'];
        }

        // Validate the essential parameters
        if (empty($type_id)) {
            return LogUtil::registerArgsError();
        }
        $args = array(
            'table' => 'zselex_type',
            'IdValue' => $type_id,
            'IdName' => 'type_id'
        );
        // Get the news type in the db
        $item = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $args);

        if ($item === false) {
            return LogUtil::registerError($this->__('Error! Item not found.'),
                    403);
        }

        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                $dbitem ['cr_uid'].'::'.$type_id, ACCESS_EDIT),
            LogUtil::getErrorMsgPermission());

        // Get the module configuration vars
        $modvars = $this->getVars();

        $this->view->assign('accessadd', 0);
        if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
            $this->view->assign('accessadd', 1);
        }

        // Assign the item to the template
        $this->view->assign('item', $item);

        // lock the page so others cannot edit it
        if (ModUtil::available('PageLock')) {
            $returnUrl = ModUtil::url('ZSELEX', 'admin', 'viewtype');
            ModUtil::apiFunc('PageLock', 'admin', 'pageLock',
                array(
                'lockName' => "ZSELEXtype{$item['type_id']}",
                'returnUrl' => $returnUrl
            ));
        }
        $this->view->assign('page', 0); // this var is used in the ajax version, but this is here to prevent E_NOTICE errors
        // Return the output that has been generated by this function
        return $this->view->fetch('admin/modfiy_type.tpl');
    }

    /**
     * delete item.
     *
     * @param
     *        	int 'type_id' the id of the type item to be deleted
     * @param
     *        	int 'objectid' generic object id maps to type_id if present
     * @param
     *        	int 'confirmation' confirmation that this type item can be deleted
     *
     * @author
     *
     * @return mixed HTML string if no confirmation, true if delete successful, false otherwise
     */
    public function deletetype($args)
    {
        $type_id      = FormUtil::getPassedValue('type_id',
                isset($args ['type_id']) ? $args ['type_id'] : null, 'REQUEST');
        $objectid     = FormUtil::getPassedValue('objectid',
                isset($args ['objectid']) ? $args ['objectid'] : null, 'REQUEST');
        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
        if (!empty($objectid)) {
            $type_id = $objectid;
        }

        // Validate the essential parameters
        if (empty($type_id)) {
            return LogUtil::registerArgsError();
        }
        $args = array(
            'table' => 'zselex_type',
            'IdValue' => $type_id,
            'IdName' => 'type_id'
        );

        // Get the type type
        $item = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $args);

        if ($item == false) {
            return LogUtil::registerError($this->__('Error! Item not found.'),
                    403);
        }

        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                $item ['cr_uid'].'::'.$item ['type_id'], ACCESS_DELETE),
            LogUtil::getErrorMsgPermission());

        // Check for confirmation.
        if (empty($confirmation)) {
            // Add ZSELEX type ID
            $this->view->assign('IdValue', $type_id);
            $this->view->assign('confirm_title', $this->__('Delete Type'));
            $this->view->assign('confirm_msg',
                $this->__('Do you want to delete this Type'));
            $this->view->assign('IdName', 'type_id');
            // $this->view->assign('shop_id', $shop_id); is it already assigned?
            $this->view->assign('submitFunc', 'deletetype');
            $this->view->assign('cancelFunc', 'viewtype');

            // Return the output that has been generated by this function
            return $this->view->fetch('admin/deletecommon.tpl');
        }

        // If we get here it means that the admin has confirmed the action
        // Confirm authorisation code
        $this->checkCsrfToken();

        // Delete
        if (ModUtil::apiFunc('ZSELEX', 'admin', 'deleteElement', $args)) {
            // Success
            LogUtil::registerStatus($this->__('Done! Type has been deleted successfully.'));

            // Let any hooks know that we have deleted an item
            $this->notifyHooks(new Zikula_ProcessHook('type.ui_hooks.types.process_delete',
                $type_id));
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewtype'));
    }

    /**
     * create country.
     *
     * @author
     *
     */
    public function createcountry($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        return $this->view->fetch('admin/createcountry.tpl');
    }

    /**
     * create region.
     *
     * @author
     *
     */
    public function createregion($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        $countries = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $args      = array(
                'table' => 'zselex_country',
                'where' => '',
                'orderBy' => 'country_name ASC',
                'useJoins' => ''
        ));
        $this->view->assign('countries', $countries);

        return $this->view->fetch('admin/createregion.tpl');
    }

    /**
     * create branch.
     *
     * @author
     *
     */
    public function createbranch($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        return $this->view->fetch('admin/createbranch.tpl');
    }

    /**
     * create city.
     *
     * @author
     *
     */
    public function createcity($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $countries = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $args      = array(
                'table' => 'zselex_country',
                'where' => '',
                'orderBy' => 'country_name ASC',
                'useJoins' => ''
        ));
        $regions   = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $args      = array(
                'table' => 'zselex_region',
                'where' => '',
                'orderBy' => 'region_name ASC',
                'useJoins' => ''
        ));
        $this->view->assign('regions', $regions);
        $this->view->assign('countries', $countries);

        return $this->view->fetch('admin/createcity.tpl');
    }

    /**
     * create area.
     *
     * @author
     *
     */
    public function createarea($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        $countryArgs = array(
            'entity' => 'ZSELEX_Entity_Country',
            'fields' => array(
                'a.country_id',
                'a.country_name'
            ),
            'orderby' => 'a.country_name ASC'
        );
        $countries   = $this->entityManager->getRepository('ZSELEX_Entity_Country')->getAll($countryArgs);

        $regionsArgs = array(
            'entity' => 'ZSELEX_Entity_Region',
            'fields' => array(
                'a.region_id',
                'a.region_name'
            ),
            'orderby' => 'a.region_name ASC'
        );
        $regions     = $this->entityManager->getRepository('ZSELEX_Entity_Country')->getAll($regionsArgs);

        $cityArgs = array(
            'entity' => 'ZSELEX_Entity_City',
            'fields' => array(
                'a.city_id',
                'a.city_name'
            ),
            'orderby' => 'a.city_name ASC'
        );
        $cities   = $this->entityManager->getRepository('ZSELEX_Entity_Country')->getAll($cityArgs);

        $this->view->assign('regions', $regions);
        $this->view->assign('countries', $countries);
        $this->view->assign('cities', $cities);

        return $this->view->fetch('admin/createarea.tpl');
    }

    /**
     * create shop.
     *
     * @author
     *
     */
    public function createshop($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        $sess_item = SessionUtil::getVar('createshop');

        $item = array(
            'shop_id' => $sess_item ['elemId'],
            'user_id' => !empty($sess_item ['parentuser']) ? $sess_item ['parentuser']
                    : 0,
            'urltitle' => $urltitle,
            'theme' => isset($sess_item ['shopdesign']) ? $sess_item ['shopdesign']
                    : '',
            'shop_name' => $sess_item ['elemtName'],
            'description' => isset($sess_item ['elemtDesc']) ? $sess_item ['elemtDesc']
                    : '',
            'address' => isset($sess_item ['elemtAddrs']) ? $sess_item ['elemtAddrs']
                    : '',
            'telephone' => isset($sess_item ['elemtTele']) ? $sess_item ['elemtTele']
                    : '',
            'fax' => isset($sess_item ['elemtFax']) ? $sess_item ['elemtFax'] : '',
            'email' => isset($sess_item ['elemtEmail']) ? $sess_item ['elemtEmail']
                    : '',
            'country_id' => isset($sess_item ['country-combo']) ? $sess_item ['country-combo']
                    : '',
            'region_id' => isset($sess_item ['region-combo']) ? $sess_item ['region-combo']
                    : '',
            'city_id' => isset($sess_item ['city-combo']) ? $sess_item ['city-combo']
                    : '',
            'area_id' => isset($sess_item ['area-combo']) ? $sess_item ['area-combo']
                    : '',
            'category_id' => isset($sess_item ['category-combo']) ? $sess_item ['category-combo']
                    : '',
            'branch_id' => isset($sess_item ['branch']) ? $sess_item ['branch'] : '',
            'opening_hours' => isset($sess_item ['opening_hours']) ? $sess_item ['opening_hours']
                    : '',
            'pictures' => isset($file ['name']) ? $file ['name'] : '',
            'shoptype_id' => isset($sess_item ['ecommerce']) ? $sess_item ['ecommerce']
                    : '',
            'domain' => isset($sess_item ['ecomDomain']) ? $sess_item ['ecomDomain']
                    : '',
            'hostname' => isset($sess_item ['ecomHost']) ? $sess_item ['ecomHost']
                    : '',
            'dbname' => isset($sess_item ['ecomDb']) ? $sess_item ['ecomDb'] : '',
            'username' => isset($sess_item ['ecomUser']) ? $sess_item ['ecomUser']
                    : '',
            'password' => isset($sess_item ['ecomPswrd']) ? $sess_item ['ecomPswrd']
                    : '',
            'table_prefix' => isset($sess_item ['table_prefix']) ? $sess_item ['table_prefix']
                    : '',
            'status' => isset($sess_item ['status']) ? $sess_item ['status'] : 0
        );

        // echo "<pre>"; print_r($sess_item); echo "</pre>";
        // echo "<pre>"; print_r($item); echo "</pre>";
        $branchargs = array(
            'table' => 'zselex_branch',
            'where' => '',
            'orderBy' => '',
            'useJoins' => ''
        );
        $aBranch    = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $branchargs); // get all branches

        $ecommerceargs = array(
            'table' => 'zselex_shop_types',
            'where' => '',
            'orderBy' => '',
            'useJoins' => ''
        );

        $aEcommerce = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $ecommerceargs);

        $usereargs = array(
            'table' => 'users',
            'where' => '',
            'orderBy' => '',
            'useJoins' => ''
        );
        $users     = ModUtil::apiFunc('ZSELEX', 'admin', 'getUser', $usereargs);

        $pluginargs = array(
            'table' => 'zselex_plugin',
            'where' => '',
            'orderBy' => '',
            'useJoins' => ''
        );
        $plugins    = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $pluginargs);
        // echo "<pre>"; print_r($plugins); echo "</pre>";

        $countries = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $args      = array(
                'table' => 'zselex_country',
                'where' => '',
                'orderBy' => 'country_name ASC',
                'useJoins' => ''
        ));
        $regions   = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $args      = array(
                'table' => 'zselex_region',
                'where' => '',
                'orderBy' => 'region_name ASC',
                'useJoins' => ''
        ));
        $cities    = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $args      = array(
                'table' => 'zselex_city',
                'where' => '',
                'orderBy' => 'city_name ASC',
                'useJoins' => ''
        ));
        $areas     = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $args      = array(
                'table' => 'zselex_area',
                'where' => '',
                'orderBy' => 'area_name ASC',
                'useJoins' => ''
        ));

        $catArgs    = array(
            'entity' => 'ZSELEX_Entity_Category',
            'fields' => array(
                'a.category_id',
                'a.category_name'
            ),
            'orderby' => 'a.category_name ASC'
        );
        $categories = $this->entityManager->getRepository('ZSELEX_Entity_Country')->getAll($catArgs);

        $affiliates = $this->entityManager->getRepository('ZSELEX_Entity_Country')->getAll(array(
            'entity' => 'ZSELEX_Entity_ShopAffiliation',
            'fields' => array(
                'a.aff_id',
                'a.aff_name'
            ),
            'orderby' => 'a.aff_name ASC'
        ));

        $loguser = UserUtil::getVar('uid');
        $usr     = array();

        // echo "<pre>"; print_r($regions); echo "</pre>";

        $this->view->assign('regions', $regions);
        $this->view->assign('countries', $countries);
        $this->view->assign('cities', $cities);
        $this->view->assign('areas', $areas);
        $this->view->assign('categories', $categories);
        $this->view->assign('item', $item);
        $this->view->assign('zecommerce', $aEcommerce);
        $this->view->assign('users', $users);
        $this->view->assign('loguser', $loguser);

        $this->view->assign('zbranch', $aBranch);
        $this->view->assign('affiliates', $affiliates);
        $this->view->assign('puser', $usr);
        $this->view->assign('plugin', $plugins);
        // echo "<pre>"; print_r($aSelectArray['category']); echo "</pre>";

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            return $this->view->fetch('admin/createshop_user.tpl');
        } else {
            return $this->view->fetch('admin/createshop.tpl');
        }
    }

    /**
     * create product ad.
     *
     * @author
     *
     */
    public function createadvertise($args)
    {

        // echo "comes here..."; exit;
        // echo $this->shopname;
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        try {
            $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
            $loguser = UserUtil::getVar('uid');
            $loguser = !empty($loguser) ? $loguser : 0;

            if ($this->shopPermission($shop_id) < 1) { // restrict user if he is not allowed to view this shop
                return LogUtil::registerPermissionError();
            }

            $serviceargs       = array(
                'shop_id' => FormUtil::getPassedValue('shop_id', null, 'REQUEST'),
                'user_id' => $user_id,
                'type' => 'createad'
            );
            // $servicePermission = $this->serviceCheck($serviceargs);
            $servicePermission = $this->servicePermission($serviceargs);

            if ($servicePermission ['perm'] < 1) {
                return LogUtil::registerError($this->__($servicePermission ['message']));
            }

            if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) { // disabled check
                if ($this->serviceDisabled('createad') < 1) {
                    return LogUtil::registerError($this->__('This service has been temporarily disabled!'));
                }
            }

            $adpriceargs = array(
                'table' => 'zselex_advertise_price',
                'where' => '',
                'orderBy' => '',
                'useJoins' => ''
            );

            $aAdPriceArray = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                    $adpriceargs);

            $shop_id_fromlist = $_GET ['shop_id'];

            $shopName_fromlist = $_GET ['shopName'];

            $countryargs = array(
                'table' => 'zselex_country',
                'fields' => array(
                    'country_id , country_name'
                ),
                'orderby' => 'country_name ASC'
            );
            $countries   = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray',
                    $countryargs);
            $regionargs  = array(
                'table' => 'zselex_region',
                'fields' => array(
                    'region_id , region_name'
                ),
                'orderby' => 'region_name ASC'
            );
            $regions     = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray',
                    $regionargs);
            $cityargs    = array(
                'table' => 'zselex_city',
                'fields' => array(
                    'city_id , city_name'
                ),
                'orderby' => 'city_name ASC'
            );
            $cities      = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray',
                    $cityargs);
            $areaargs    = array(
                'table' => 'zselex_area',
                'fields' => array(
                    'area_id , area_name'
                ),
                'orderby' => 'area_name ASC'
            );
            $areas       = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray',
                    $areaargs);

            $this->view->assign('countries', $countries);
            $this->view->assign('regions', $regions);
            $this->view->assign('cities', $cities);
            $this->view->assign('areas', $areas);
            $this->view->assign('shop_id', $shop_id);
            $this->view->assign('shop_name', $this->shopname);
            $this->view->assign('zadprice', $aAdPriceArray);
            $this->view->assign('shop_id_fromlist', $shop_id_fromlist);
            $this->view->assign('shopName_fromlist', $shopName_fromlist);

            // return $this->view->fetch('admin/createadvertise.tpl'); exit;
            /*
             * if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
             * return $this->view->fetch('admin/advertise/createadvertise.tpl');
             * } else {
             * return $this->view->fetch('admin/advertise/createadvertise_user.tpl');
             * }
             */
            return $this->view->fetch('admin/advertise/createadvertise.tpl');
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
            // die;
        }
    }

    /**
     * create ad price.
     *
     * @author
     *
     */
    public function createadprice($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        return $this->view->fetch('admin/createadprice.tpl');
    }

    /**
     * submit city.
     *
     * @author
     *
     */
    public function submitcity($args)
    {

        // Get parameters cr_date whatever input we need
        $formElements = FormUtil::getPassedValue('formElements',
                isset($args ['formElements']) ? $args ['formElements'] : null,
                'POST');
        $formElements = $this->purifyHtml($formElements);

        if ($formElements ['elemtName']) { // CITY
            $item = array(
                'city_name' => $formElements ['elemtName'],
                'region_id' => $formElements ['region-combo'],
                'country_id' => $formElements ['country-combo'],
                'description' => isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                        : '',
                'status' => isset($formElements ['status']) ? $formElements ['status']
                        : 0
            );

            if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) {
                $args    = array(
                    'table' => 'zselex_city',
                    'element' => $item,
                    'Id' => 'city_id'
                );
                // Create the zselex type
                // $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $args);
                // $InsertId = DBUtil::getInsertID($args['table'], $args['Id']);
                // echo $formElements['region-combo']; exit;
                $city    = new ZSELEX_Entity_City ();
                $city->setCity_name($formElements ['elemtName']);
                $region  = $this->entityManager->find('ZSELEX_Entity_Region',
                    $formElements ['region-combo']);
                $city->setRegion($region);
                $country = $this->entityManager->find('ZSELEX_Entity_Country',
                    $formElements ['country-combo']);
                $city->setCountry($country);
                $city->setDescription($formElements ['elemtDesc']);
                $city->setStatus($formElements ['status']);
                $this->entityManager->persist($city);
                $this->entityManager->flush();

                $InsertId = $city->getCity_id();
                $result   = $InsertId;
            } else {

                // echo "hello"; exit;

                $InsertId = $formElements ['elemId'];
                // update the type
                $item     = array(
                    'city_id' => $formElements ['elemId'],
                    'city_name' => $formElements ['elemtName'],
                    'region' => $formElements ['region-combo'],
                    'country' => $formElements ['country-combo'],
                    'description' => isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                            : '',
                    'status' => isset($formElements ['status']) ? $formElements ['status']
                            : 0
                );
                /*
                 * $updateargs = array(
                 * 'table' => 'zselex_city',
                 * 'IdValue' => $InsertId,
                 * 'IdName' => 'city_id',
                 * 'element' => $item
                 * );
                 *
                 * $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement', $updateargs);
                 */

                $result = $this->entityManager->getRepository('ZSELEX_Entity_City')->updateEntity(null,
                    'ZSELEX_Entity_City', $item,
                    array(
                    'a.city_id' => $InsertId
                ));

                // $delParentQuery = "DELETE FROM zselex_parent WHERE childType = '" . $formElements['childType'] . "' AND childId = " . $InsertId . "";
                // DBUtil::executeSQL($delParentQuery);
                // $delresult = ModUtil::apiFunc('ZSELEX', 'admin', 'execteQuery', $delParentQuery);
            }

            if ($result != false) {
                // echo "Success";
                if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) {
                    LogUtil::registerStatus($this->__('Done! City has been created successfully.'));
                } else {
                    LogUtil::registerStatus($this->__('Done! City details has been updated successfully.'));
                }

                $childType = $formElements ['childType'];
                if ($formElements ['parentCity'] && !empty($formElements ['parentcity_list'])) {
                    $parentId   = $formElements ['parentCity'];
                    $parentType = 'CITY';

                    $parentItem = array(
                        'childType' => $childType,
                        'childId' => $InsertId,
                        'parentId' => $parentId,
                        'parentType' => $parentType
                    );
                    $args       = array(
                        'table' => 'zselex_parent',
                        'element' => $parentItem,
                        'Id' => 'tableId'
                    );
                    // Create the zselex type
                    $result     = ModUtil::apiFunc('ZSELEX', 'admin',
                            'createElement', $args);
                } else {
                    $parentId   = '0';
                    $parentType = 'CITY';

                    $parentItem = array(
                        'childType' => $childType,
                        'childId' => $InsertId,
                        'parentId' => $parentId,
                        'parentType' => $parentType
                    );
                    $args       = array(
                        'table' => 'zselex_parent',
                        'element' => $parentItem,
                        'Id' => 'tableId'
                    );
                    // Create the zselex type
                    $result     = ModUtil::apiFunc('ZSELEX', 'admin',
                            'createElement', $args);
                }

                if ($formElements ['parentCountry'] && !empty($formElements ['parentcountry_list'])) {
                    $parentId   = $formElements ['parentCountry'];
                    $parentType = 'COUNTRY';
                    $parentItem = array(
                        'childType' => $childType,
                        'childId' => $InsertId,
                        'parentId' => $parentId,
                        'parentType' => $parentType
                    );
                    $args       = array(
                        'table' => 'zselex_parent',
                        'element' => $parentItem,
                        'Id' => 'tableId'
                    );
                    // Create the zselex type
                    $result     = ModUtil::apiFunc('ZSELEX', 'admin',
                            'createElement', $args);
                }
                if ($formElements ['parentRegion'] && !empty($formElements ['parentregion_list'])) {
                    $parentId   = $formElements ['parentRegion'];
                    $parentType = 'REGION';

                    $parentItem = array(
                        'childType' => $childType,
                        'childId' => $InsertId,
                        'parentId' => $parentId,
                        'parentType' => $parentType
                    );
                    $args       = array(
                        'table' => 'zselex_parent',
                        'element' => $parentItem,
                        'Id' => 'tableId'
                    );
                    // Create the zselex type
                    $result     = ModUtil::apiFunc('ZSELEX', 'admin',
                            'createElement', $args);
                }
            } else {
                // fail! type not created
                throw new Zikula_Exception_Fatal($this->__('Story not created for unknown reason (Api failure).'));

                return false;
            }
        }

        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewcity'));
    }

    /**
     * view all cities.
     *
     * @author
     *
     */
    public function viewcity($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $repo   = $this->entityManager->getRepository('ZSELEX_Entity_City');
        // initialize sort array - used to display sort classes and urls
        $sort   = array();
        $fields = array(
            'city_id',
            'city_name',
            'status',
            'cr_date',
            'lu_date'
        ); // possible sort fields

        $itemsperpage  = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 15,
                'GETPOST');
        $startnum      = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $status        = FormUtil::getPassedValue('status',
                isset($args ['status']) ? $args ['status'] : null, 'GETPOST');
        $order         = FormUtil::getPassedValue('order',
                isset($args ['order']) ? $args ['order'] : 'cr_date', 'GETPOST');
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
            $sort ['url'] [$field] = ModUtil::url('ZSELEX', 'admin', 'viewcity',
                    array(
                    'status' => $status,
                    'filtercats_serialized' => serialize($filtercats),
                    'order' => $field,
                    'sdir' => $sdir
            ));
        }
        $this->view->assign('sort', $sort);

        $this->view->assign('filter_active', $status);

        if (isset($status) && $status != '') {
            $cityArgs ['where'] ['a.status'] = $status;
        }
        if (isset($searchtext) && $searchtext != '') {
            $cityArgs ['like'] ['a.city_name'] = '%'.DataUtil::formatForStore($searchtext).'%';
        }
        if (isset($order) && $order != '') {
            $cityArgs ['orderby'] = 'a.'.$order.' '.$orderdir;
        }

        $cityArgs ['groupby'] = 'a.city_id';

        $cityArgs ['joins'] = array(
            'left join a.country b',
            'left join a.region c'
        );

        $cityArgs ['fields'] = array(
            'a.city_id',
            'a.city_name',
            'b.country_id',
            'c.region_id',
            'b.country_name',
            'c.region_name',
            'a.description',
            'a.status',
            'a.cr_date',
            'a.cr_uid',
            'a.lu_date',
            'a.lu_uid'
        );

        // Get all zselex stories

        $cityArgs ['entity']   = 'ZSELEX_Entity_City';
        $cityArgs ['paginate'] = true;
        $cityItems             = $repo->getAll($cityArgs);

        $items = $cityItems ['result'];

        // echo "<pre>"; print_r($cityItems); echo "</pre>";
        // echo "<pre>"; print_r($items); echo "</pre>";

        for ($i = 0; $i < count($items); ++$i) {

            // Get the Username for create and update

            if ($items [$i] ['lu_uid'] > 0) {
                $createdUsers               = $repo->getUserInfo(array(
                    'entity' => 'ZSELEX_Entity_City',
                    'table' => 'zselex_city',
                    'field' => 'cr_uid',
                    'user_id' => $items [$i] ['cr_uid']
                ));
                $items [$i] ['createduser'] = $createdUsers ['uname'];
            }

            if ($items [$i] ['lu_uid'] > 0) {
                $updateUsers                = $repo->getUserInfo(array(
                    'entity' => 'ZSELEX_Entity_City',
                    'table' => 'zselex_city',
                    'field' => 'lu_uid',
                    'user_id' => $items [$i] ['lu_uid']
                ));
                $items [$i] ['updateduser'] = $updateUsers ['uname'];
            }
        }

        // $total_cities = ModUtil::apiFunc('ZSELEX', 'admin', 'countElements', $getCountArgs);
        $total_cities = $cityItems ['count'];

        // Set the possible status for later use
        $itemstatus = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );

        $citiesitems = array();
        foreach ($items as $item) {
            $options = array();

            // $options[] = array('url' => ModUtil::url('ZSELEX', 'admin', 'displaycity', array('city_id' => $item['city_id'])),
            // 'image' => '14_layer_visible.png',
            // 'title' => $this->__('View'));

            if (SecurityUtil::checkPermission('ZSELEX::',
                    "{$item['cr_uid']}::{$item['city_id']}", ACCESS_EDIT)) {
                $options [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'modifycity',
                        array(
                        'id' => $item ['city_id']
                    )),
                    'image' => 'xedit.png',
                    'title' => $this->__('Edit')
                );

                if ((SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['city_id']}", ACCESS_DELETE))
                    || SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['city_id']}", ACCESS_ADMIN)) {
                    $options [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin', 'deletecity',
                            array(
                            'city_id' => $item ['city_id']
                        )),
                        'image' => '14_layer_deletelayer.png',
                        'title' => $this->__('Delete')
                    );
                }
            }
            $item ['options'] = $options;

            $item ['infuture'] = DateUtil::getDatetimeDiff_AsField($item ['cr_date'],
                    DateUtil::getDatetime(), 6) < 0;
            $citiesitems []    = $item;
        }

        // Assign the items to the template
        $this->view->assign('citiesitems', $citiesitems);

        $this->view->assign('total_cities', $total_cities);

        // Assign the current status filter and the possible ones
        $this->view->assign('status', $status);
        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('order', $order);
        // Return the output that has been generated by this function
        return $this->view->fetch('admin/viewcity.tpl');
    }

    public function submitarea($args)
    {

        // Get parameters cr_date whatever input we need
        $formElements = FormUtil::getPassedValue('formElements',
                isset($args ['formElements']) ? $args ['formElements'] : null,
                'POST');
        $formElements = $this->purifyHtml($formElements);

        // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;

        if ($formElements ['elemtName']) {
            $item = array(
                'area_name' => $formElements ['elemtName'],
                'city_id' => $formElements ['city-combo'],
                'region_id' => $formElements ['region-combo'],
                'country_id' => $formElements ['country-combo']
                )
            // 'description' => isset($formElements['elemtDesc']) ? $formElements['elemtDesc'] : '',
            // 'status' => isset($formElements['status']) ? $formElements['status'] : 0,
            ;

            if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) {
                $area     = new ZSELEX_Entity_Area ();
                $area->setArea_name($item ['area_name']);
                $city     = $this->entityManager->find('ZSELEX_Entity_City',
                    $item ['city_id']);
                $area->setCity($city);
                $region   = $this->entityManager->find('ZSELEX_Entity_Region',
                    $item ['region_id']);
                $area->setRegion($region);
                $country  = $this->entityManager->find('ZSELEX_Entity_Country',
                    $item ['country_id']);
                $area->setCountry($country);
                // $area->setStatus($value['status']);
                $this->entityManager->persist($area);
                $this->entityManager->flush();
                $InsertId = $area->getArea_id();
                $result   = $InsertId;
            } else {
                $InsertId = $formElements ['elemId'];
                // update the type
                $item     = array(
                    'area_id' => $formElements ['elemId'],
                    'area_name' => $formElements ['elemtName'],
                    'city' => $formElements ['city-combo'],
                    'region' => $formElements ['region-combo'],
                    'country' => $formElements ['country-combo']
                );
                /*
                 * $updateargs = array(
                 * 'table' => 'zselex_area',
                 * 'IdValue' => $InsertId,
                 * 'IdName' => 'area_id',
                 * 'element' => $item
                 * );
                 */

                // $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement', $updateargs);
                $result = $this->entityManager->getRepository('ZSELEX_Entity_Country')->updateEntity(null,
                    'ZSELEX_Entity_Area', $item,
                    array(
                    'a.area_id' => $InsertId
                ));

                $delParentQuery = "DELETE FROM zselex_parent WHERE childType = '".$formElements ['childType']."' AND childId = ".$InsertId.'';
                DBUtil::executeSQL($delParentQuery);

                // $delresult = ModUtil::apiFunc('ZSELEX', 'admin', 'execteQuery', $delParentQuery);
            }

            if ($result != false) {
                // echo "Success";
                if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) {
                    LogUtil::registerStatus($this->__('Done! Area has been created successfully.'));
                } else {
                    LogUtil::registerStatus($this->__('Done! Area has been updated successfully.'));
                }

                $childType = $formElements ['childType'];

                if (!empty($formElements ['country-combo'])) {
                    $parentId   = $formElements ['country-combo'];
                    $parentType = 'COUNTRY';

                    $parentItem = array(
                        'childType' => $childType,
                        'childId' => $InsertId,
                        'parentId' => $parentId,
                        'parentType' => $parentType
                    );
                    $args       = array(
                        'table' => 'zselex_parent',
                        'element' => $parentItem,
                        'Id' => 'tableId'
                    );
                    // Create the zselex type
                    $result     = ModUtil::apiFunc('ZSELEX', 'admin',
                            'createElement', $args);
                }

                if (!empty($formElements ['region-combo'])) {
                    $parentId   = $formElements ['region-combo'];
                    $parentType = 'REGION';

                    $parentItem = array(
                        'childType' => $childType,
                        'childId' => $InsertId,
                        'parentId' => $parentId,
                        'parentType' => $parentType
                    );
                    $args       = array(
                        'table' => 'zselex_parent',
                        'element' => $parentItem,
                        'Id' => 'tableId'
                    );
                    // Create the zselex type
                    $result     = ModUtil::apiFunc('ZSELEX', 'admin',
                            'createElement', $args);
                }

                if (!empty($formElements ['city-combo'])) {
                    $parentId   = $formElements ['city-combo'];
                    $parentType = 'CITY';

                    $parentItem = array(
                        'childType' => $childType,
                        'childId' => $InsertId,
                        'parentId' => $parentId,
                        'parentType' => $parentType
                    );
                    $args       = array(
                        'table' => 'zselex_parent',
                        'element' => $parentItem,
                        'Id' => 'tableId'
                    );
                    // Create the zselex type
                    $result     = ModUtil::apiFunc('ZSELEX', 'admin',
                            'createElement', $args);
                }
            } else {
                // fail! type not created
                throw new Zikula_Exception_Fatal($this->__('Story not created for unknown reason (Api failure).'));

                return false;
            }
        }

        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewarea'));
    }

    /**
     * view all araeas.
     *
     * @author
     *
     */
    public function viewarea($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $areaRepo = $this->entityManager->getRepository('ZSELEX_Entity_Area');
        $areaArgs = array();
        // initialize sort array - used to display sort classes and urls
        $sort     = array();
        $fields   = array(
            'area_id',
            'area_name'
        ); // possible sort fields

        $itemsperpage  = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 15,
                'GETPOST');
        $startnum      = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $status        = FormUtil::getPassedValue('status',
                isset($args ['status']) ? $args ['status'] : null, 'GETPOST');
        $order         = FormUtil::getPassedValue('order',
                isset($args ['order']) ? $args ['order'] : 'cr_date', 'GETPOST');
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
            $sort ['url'] [$field] = ModUtil::url('ZSELEX', 'admin', 'viewarea',
                    array(
                    'status' => $status,
                    'filtercats_serialized' => serialize($filtercats),
                    'order' => $field,
                    'sdir' => $sdir
            ));
        }
        $this->view->assign('sort', $sort);

        $this->view->assign('filter_active', $status);

        if (isset($status) && $status != '') {
            $areaArgs ['where'] ['a.status'] = $status;
        }
        if (isset($searchtext) && $searchtext != '') {
            $areaArgs ['like'] ['a.area_name'] = '%'.DataUtil::formatForStore($searchtext).'%';
        }
        if (isset($order) && $order != '') {
            $areaArgs ['order'] = 'a.'.$order.' '.$orderdir;
        }

        // Get all zselex stories
        $areaArgs ['startlimit'] = $startnum;
        $areaArgs ['offset']     = $itemsperpage;

        $areaArgs += array(
            'entity' => 'ZSELEX_Entity_Area'
        );
        $areaArgs ['fields']   = array(
            'a.area_id',
            'a.area_name',
            'b.country_name',
            'c.region_name',
            'd.city_name'
        );
        $areaArgs ['paginate'] = true;
        $areaArgs ['joins']    = array(
            'LEFT JOIN a.country b',
            'LEFT JOIN a.region c',
            'LEFT JOIN a.city d'
        );

        // echo "<pre>"; print_r($areaArgs); echo "</pre>";
        $areaItems = $areaRepo->getAll($areaArgs);
        $items     = $areaItems ['result'];

        for ($i = 0; $i < count($items); ++$i) {
            
        }

        // echo "<pre>"; print_r($items); echo "</pre>";

        $total_cities = $areaItems ['count'];

        // Set the possible status for later use
        $itemstatus = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );

        $citiesitems = array();
        foreach ($items as $item) {
            $options = array();

            // $options[] = array('url' => ModUtil::url('ZSELEX', 'admin', 'displaycity', array('city_id' => $item['city_id'])),
            // 'image' => '14_layer_visible.png',
            // 'title' => $this->__('View'));

            if (SecurityUtil::checkPermission('ZSELEX::',
                    "{$item['cr_uid']}::{$item['area_id']}", ACCESS_EDIT)) {
                $options [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'modifyarea',
                        array(
                        'id' => $item ['area_id']
                    )),
                    'image' => 'xedit.png',
                    'title' => $this->__('Edit')
                );

                if ((SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['area_id']}", ACCESS_DELETE))
                    || SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['area_id']}", ACCESS_ADMIN)) {
                    $options [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin', 'deletearea',
                            array(
                            'area_id' => $item ['area_id']
                        )),
                        'image' => '14_layer_deletelayer.png',
                        'title' => $this->__('Delete')
                    );
                }
            }
            $item ['options'] = $options;

            $item ['infuture'] = DateUtil::getDatetimeDiff_AsField($item ['cr_date'],
                    DateUtil::getDatetime(), 6) < 0;
            $citiesitems []    = $item;
        }

        // Assign the items to the template
        $this->view->assign('citiesitems', $citiesitems);

        $this->view->assign('total_cities', $total_cities);

        // Assign the current status filter and the possible ones
        $this->view->assign('status', $status);
        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('order', $order);
        // Return the output that has been generated by this function
        return $this->view->fetch('admin/viewarea.tpl');
    }

    public function modifyarea()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        // echo "modifycity";
        $area_id = FormUtil::getPassedValue('id',
                isset($args ['id']) ? $args ['id'] : null, 'GETPOST');

        $args = array(
            'table' => 'zselex_area',
            'IdValue' => $area_id,
            'IdName' => 'area_id'
        );
        // Get the news type in the db
        $item = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $args);

        $parentArgs                 = array(
            'childType' => 'AREA',
            'childId' => $item ['area_id']
        );
        $parentArgs ['parentTable'] = 'zselex_country';
        $parentArgs ['parentId']    = 'country_id';
        $parentArgs ['parentType']  = 'COUNTRY';
        $parentcountry              = ModUtil::apiFunc('ZSELEX', 'admin',
                'getParents', $parentArgs);
        $item ['parentcountry']     = $parentcountry [0] ['country_name'];
        $item ['parentcountry_id']  = $parentcountry [0] ['country_id'];

        $parentArgs ['parentTable'] = 'zselex_region';
        $parentArgs ['parentId']    = 'region_id';
        $parentArgs ['parentType']  = 'REGION';
        $parentregion               = ModUtil::apiFunc('ZSELEX', 'admin',
                'getParents', $parentArgs);
        $item ['parentregion']      = $parentregion [0] ['region_name'];
        $item ['parentregion_id']   = $parentregion [0] ['region_id'];

        $parentArgs ['parentTable'] = 'zselex_city';
        $parentArgs ['parentId']    = 'city_id';
        $parentArgs ['parentType']  = 'CITY';
        $parentcity                 = ModUtil::apiFunc('ZSELEX', 'admin',
                'getParents', $parentArgs);
        $item ['parentcity']        = $parentcity [0] ['city_name'];
        $item ['parentcity_id']     = $parentcity [0] ['city_id'];

        // echo "<pre>"; print_r($item); echo "</pre>";

        $countries = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $args      = array(
                'table' => 'zselex_country',
                'where' => '',
                'orderBy' => 'country_name ASC',
                'useJoins' => ''
        ));
        $regions   = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $args      = array(
                'table' => 'zselex_region',
                'where' => '',
                'orderBy' => 'region_name ASC',
                'useJoins' => ''
        ));
        $cities    = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $args      = array(
                'table' => 'zselex_city',
                'where' => '',
                'orderBy' => 'city_name ASC',
                'useJoins' => ''
        ));

        $this->view->assign('regions', $regions);
        $this->view->assign('countries', $countries);
        $this->view->assign('cities', $cities);
        $this->view->assign('item', $item);

        return $this->view->fetch('admin/createarea.tpl');
    }

    public function deletearea($args)
    {
        $area_id = FormUtil::getPassedValue('area_id',
                isset($args ['area_id']) ? $args ['area_id'] : null, 'REQUEST');
        $args    = array(
            'table' => 'zselex_area',
            'IdValue' => $area_id,
            'IdName' => 'area_id'
        );

        // Delete
        $delete = $this->entityManager->getRepository('ZSELEX_Entity_Country')->deleteEntity(null,
            'ZSELEX_Entity_Area',
            array(
            'a.area_id' => $area_id
        ));
        if ($delete) {
            // Success
            LogUtil::registerStatus($this->__('Done! Type has been deleted successfully.'));
            // Let any hooks know that we have deleted an item
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewarea'));
    }
    /**
     * delete item.
     *
     * @param
     *        	int 'city_id' the id of the type item to be deleted
     * @param
     *        	int 'objectid' generic object id maps to city_id if present
     * @param
     *        	int 'confirmation' confirmation that this type item can be deleted
     *        	
     * @author
     *
     * @return mixed HTML string if no confirmation, true if delete successful, false otherwise
     */
    // public function deletecity($args) {
    // $city_id = FormUtil::getPassedValue('city_id', isset($args['city_id']) ? $args['city_id'] : null, 'REQUEST');
    //
	// $objectid = FormUtil::getPassedValue('objectid', isset($args['objectid']) ? $args['objectid'] : null, 'REQUEST');
    // $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
    // if (!empty($objectid)) {
    // $city_id = $objectid;
    // }
    //
	// // Validate the essential parameters
    // if (empty($city_id)) {
    // return LogUtil::registerArgsError();
    // }
    // $args = array('table' => 'zselex_city', 'IdValue' => $city_id, 'IdName' => 'city_id');
    //
	// // Get the type type
    // $item = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $args);
    //
	//
	// if ($item == false) {
    // return LogUtil::registerError($this->__('Error! Item not found.'), 403);
    // }
    //
	// $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::', $item['cr_uid'] . '::' . $item['city_id'], ACCESS_DELETE), LogUtil::getErrorMsgPermission());
    //
	// // Check for confirmation.
    // if (empty($confirmation)) {
    // // Add ZSELEX type ID
    // $this->view->assign('IdValue', $city_id);
    // $this->view->assign('IdName', 'city_id');
    // $this->view->assign('submitFunc', 'deletecity');
    // $this->view->assign('cancelFunc', 'viewcity');
    //
	// // Return the output that has been generated by this function
    // return $this->view->fetch('admin/delete.tpl');
    // }
    //
	// // If we get here it means that the admin has confirmed the action
    // // Confirm authorisation code
    // $this->checkCsrfToken();
    //
	// // Delete
    // if (ModUtil::apiFunc('ZSELEX', 'admin', 'deleteElement', $args)) {
    // // Success
    // LogUtil::registerStatus($this->__('Done! Type has been deleted successfully.'));
    //
	// // Let any hooks know that we have deleted an item
    // $this->notifyHooks(new Zikula_ProcessHook('type.ui_hooks.types.process_delete', $city_id));
    // }
    //
	// return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewcity'));
    // }

    /**
     * delete a city.
     *
     * @author
     *
     */
    public function deletecity($args)
    {
        $city_id = FormUtil::getPassedValue('city_id',
                isset($args ['city_id']) ? $args ['city_id'] : null, 'REQUEST');
        $item    = array(
            'city_id' => $city_id,
            'status' => 0
        );
        /*
         * $updateargs = array(
         * 'table' => 'zselex_city',
         * 'IdValue' => $city_id,
         * 'IdName' => 'city_id',
         * 'element' => $item
         * );
         *
         * // $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement', $updateargs);
         * $result = DBUtil::deleteWhere('zselex_city', $where = "city_id=$city_id");
         */
        $result  = $this->entityManager->getRepository('ZSELEX_Entity_City')->deleteEntity(array(
            'entity' => 'ZSELEX_Entity_City',
            'where' => array(
                'a.city_id' => $city_id
            )
        ));
        // Delete
        if ($result) {
            // Success
            LogUtil::registerStatus($this->__('Done! Type has been deleted successfully.'));

            // Let any hooks know that we have deleted an item
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewcity'));
    }

    public function modifycity()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        $repo    = $this->entityManager->getRepository('ZSELEX_Entity_City');
        // echo "modifycity";
        $city_id = FormUtil::getPassedValue('id',
                isset($args ['id']) ? $args ['id'] : null, 'GETPOST');

        /*
         * $args = array(
         * 'table' => 'zselex_city',
         * 'IdValue' => $city_id,
         * 'IdName' => 'city_id'
         * );
         * // Get the news type in the db
         * $item = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $args);
         */
        $item = $repo->get(array(
            'entity' => 'ZSELEX_Entity_City',
            'fields' => array(
                'a.city_id',
                'a.city_name',
                'b.country_id',
                'c.region_id',
                'b.country_name',
                'c.region_name',
                'a.description',
                'a.status',
                'a.cr_date',
                'a.cr_uid',
                'a.lu_date',
                'a.lu_uid'
            ),
            'where' => array(
                'a.city_id' => $city_id
            ),
            'joins' => array(
                'LEFT JOIN a.country b',
                'LEFT JOIN a.region c'
            )
        ));

        $countries = $repo->getAll(array(
            'entity' => 'ZSELEX_Entity_Country',
            'fields' => array(
                'a.country_id',
                'a.country_name'
            ),
            'orderby' => 'a.country_name ASC'
        ));

        $regions = $repo->getAll(array(
            'entity' => 'ZSELEX_Entity_Region',
            'fields' => array(
                'a.region_id',
                'a.region_name'
            ),
            'orderby' => 'a.region_name ASC'
        ));

        $this->view->assign('regions', $regions);
        $this->view->assign('countries', $countries);
        $this->view->assign('item', $item);

        return $this->view->fetch('admin/createcity.tpl');
    }

    public function uploadSingleFile($file, $destination)
    {
        $name              = $file ['name'];
        // echo $name; exit;
        // exit;
        // Check file extension
        $allowedExtensions = array(
            'png',
            'jpg',
            'gif',
            'jpeg',
            'JPEG',
            'JPG'
        );
        $ex                = end(explode('.', $name));
        if (!in_array($ex, $allowedExtensions)) {
            return LogUtil::registerError($this->__f('Error! Invalid file type: %1$s',
                        $ex));
        }

        // Check file size
        if ($size >= 16000000) {
            return LogUtil::registerError($this->__('Error! Your file is too big. The limit is 14 MB.'));
        }

        $destination = 'zselexdata/shops';
        // echo $destination; exit;
        $code        = FileUtil::uploadFile('files', $destination);
        LogUtil::registerError(FileUtil::uploadErrorMsg($code));

        // create thumbnail
        $imagine = new Imagine\Gd\Imagine ();
        $size    = new Imagine\Image\Box(120, 120);
        $mode    = Imagine\Image\ImageInterface::THUMBNAIL_INSET;
        $imagine->open($destination.'/'.$name)->thumbnail($size, $mode)->save($destination.'/thumbs/'.$name);
    }

    public static function doUploadFile($file, $destination, $newName = '',
                                        $absolute = false)
    {
        // print_r($file); exit;
        if (!$file) {
            return z_exit($this->__f('%s: called with invalid %s.',
                    array(
                    'FileUtil::uploadFile',
                    'key'
            )));
        }

        if (!$destination) {
            return z_exit($this->__f('%s: called with invalid %s.',
                    array(
                    'FileUtil::uploadFile',
                    'destination'
            )));
        }

        $msg = '';
        if (!is_dir($destination) || !is_writable($destination)) {
            if (SecurityUtil::checkPermission('::', '::', ACCESS_ADMIN)) {
                $msg = $this->__f('The destination path [%s] does not exist or is not writable',
                    $destination);
            } else {
                $msg = $this->__('The destination path does not exist or is not writable');
            }
        } elseif (isset($file ['name'])) {
            $uploadfile = $file ['tmp_name'];
            // $origfile = $file['name'];

            $origfile = $file ['newName'];

            if ($newName) {
                $uploaddest = DataUtil::formatForOS("$destination/$newName",
                        $absolute);
            } else {
                $uploaddest = DataUtil::formatForOS("$destination/$origfile",
                        $absolute);
            }

            $rc = move_uploaded_file($uploadfile, $uploaddest);

            if ($rc) {
                return true;
            } else {
                $msg = FileUtil::uploadErrorMsg($file ['error']);
            }
        }

        return $msg;
    }

    public function uploadMultipleFile($file, $destination)
    {

        // print_r($file); exit;
        // echo $file['newName']; exit;
        $name              = $file ['name'];
        $temp_name         = $file ['tmp_name'];
        list ( $width, $height, $type, $attr ) = getimagesize($temp_name);
        // exit;
        // echo "Width :" . $width;
        // echo "<br>";
        // echo "Height :" . $height;
        // exit;
        // Check file extension
        $allowedExtensions = array(
            'png',
            'jpg',
            'gif',
            'jpeg'
        );
        $ex                = end(explode('.', $name));
        if (!in_array($ex, $allowedExtensions)) {
            return LogUtil::registerError($this->__f('Error! Invalid file type: %1$s',
                        $ex));
        }
        $modvariable = $this->getVars();

        $fullWidth  = $modvariable ['fullimagewidth'];
        $fullHeight = $modvariable ['fullimageheight'];

        $medWidth  = $modvariable ['medimagewidth'];
        $medHeight = $modvariable ['medimageheight'];

        $thumbWidth  = $modvariable ['thumbimagewidth'];
        $thumbHeight = $modvariable ['thumbimageheight'];

        // Check file size

        if ($size >= 16000000) {
            return LogUtil::registerError($this->__('Error! Your file is too big. The limit is 14 MB.'));
        }

        $newNme = $file ['newName'];

        $code    = self::doUploadFile($file, $destination);
        // LogUtil::registerError(FileUtil::uploadErrorMsg($code));
        // // create full size
        // $imagine = new Imagine\Gd\Imagine();
        // $size = new Imagine\Image\Box($fullWidth, $fullHeight);
        // $mode = Imagine\Image\ImageInterface::THUMBNAIL_INSET;
        // $imagine->open($file['tmp_name'])
        // ->thumbnail($size, $mode)
        // ->save($destination . '/' . $newNme);
        // create medium size
        $imagine = new Imagine\Gd\Imagine ();
        $size    = new Imagine\Image\Box($medWidth, $medHeight);
        $mode    = Imagine\Image\ImageInterface::THUMBNAIL_INSET;
        $imagine->open($destination.'/'.$newNme)->thumbnail($size, $mode)->save($destination.'/medium/'.$newNme);

        // create thumbnail
        $imagine = new Imagine\Gd\Imagine ();
        $size    = new Imagine\Image\Box($thumbWidth, $thumbHeight);
        $mode    = Imagine\Image\ImageInterface::THUMBNAIL_INSET;
        $imagine->open($destination.'/'.$newNme)->thumbnail($size, $mode)->save($destination.'/thumbs/'.$newNme);

        return true;
    }

    public function uploadImage($file, $destination)
    {

        // print_r($file); exit;
        // echo $file['size']; exit;
        // echo $file['newName']; exit;
        $name      = $file ['name'];
        $temp_name = $file ['tmp_name'];

        // $path_parts = pathinfo($name);
        // print_r($path_parts); exit;
        // echo $temp_name; exit;
        // exit;
        // Check file extension
        list ( $width, $height, $type, $attr ) = getimagesize($temp_name);

        // echo $width; exit;

        $allowedExtensions = array(
            'png',
            'jpg',
            'gif',
            'jpeg'
        );
        $ex                = end(explode('.', $name));
        if (!in_array($ex, $allowedExtensions)) {
            return LogUtil::registerError($this->__f('Error! Invalid file type: %1$s',
                        $ex));
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

        if ($height > $fullHeight) { // FULL HEIGHT
            $finalHeight = $fullHeight;
        } else {
            $finalHeight = $height;
        }

        if ($width > $fullWidth) { // FULL WIDTH
            $finalFullWidth = $fullWidth;
        } else {
            $finalFullWidth = $width;
        }

        if ($height > $medHeight) { // MEDIUM HEIGHT
            $finalMedHeight = $medHeight;
        } else {
            $finalMedHeight = $height;
        }

        if ($width > $medWidth) { // MEDIUM WIDTH
            $finalMedWidth = $medWidth;
        } else {
            $finalMedWidth = $width;
        }

        if ($height > $thumbHeight) { // THUMB HEIGHT
            $finalThumbHeight = $thumbHeight;
        } else {
            $finalThumbHeight = $height;
        }

        if ($width > $thumbWidth) { // THUMB WIDTH
            $finalThumbWeight = $thumbWidth;
        } else {
            $finalThumbWeight = $width;
        }

        // Check file size
        if ($size >= 16000000) {
            return LogUtil::registerError($this->__('Error! Your file is too big. The limit is 14 MB.'));
        }

        $newNme = $file ['newName'];

        $code    = self::doUploadFile($file, $destination);
        // LogUtil::registerError(FileUtil::uploadErrorMsg($code));
        // $fullWidth = 940;
        // $fullHeight = 303;
        // create full size
        $imagine = new Imagine\Gd\Imagine ();
        $size    = new Imagine\Image\Box($fullWidth, $fullHeight);
        $mode    = Imagine\Image\ImageInterface::THUMBNAIL_INSET;
        $imagine->open($destination.'/'.$newNme)->thumbnail($size, $mode)->save($destination.'/fullsize/'.$newNme);

        // create medium size
        $imagine = new Imagine\Gd\Imagine ();
        $size    = new Imagine\Image\Box($medWidth, $medHeight);
        $mode    = Imagine\Image\ImageInterface::THUMBNAIL_INSET;
        $imagine->open($destination.'/'.$newNme)->thumbnail($size, $mode)->save($destination.'/medium/'.$newNme);

        // create thumbnail
        $imagine = new Imagine\Gd\Imagine ();
        $size    = new Imagine\Image\Box($thumbWidth, $thumbHeight);
        $mode    = Imagine\Image\ImageInterface::THUMBNAIL_INSET;
        $imagine->open($destination.'/'.$newNme)->thumbnail($size, $mode)->save($destination.'/thumb/'.$newNme);

        unlink($destination.'/'.$newNme);

        return true;
    }

    public function submitshopuser($args)
    {

        // echo "helloooo"; exit;
        // Get parameters cr_date whatever input we need
        $formElements = FormUtil::getPassedValue('formElements',
                isset($args ['formElements']) ? $args ['formElements'] : null,
                'POST');
        // $file = FormUtil::getPassedValue('files', isset($args['files']) ? $args['files'] : null, 'FILES');
        // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;

        $files = FormUtil::getPassedValue('files',
                isset($args ['files']) ? $args ['files'] : null, 'FILES');

        // echo $formElements['defaultimage']; exit;
        $modvariable = $this->getVars();
        // if(!empty($files)) {
        // echo "<pre>"; print_r($files); echo "</pre>";
        // }
        // exit;

        if ($formElements ['elemtName']) {
            $urltitle = strtolower($formElements ['elemtName']);
            $urltitle = str_replace(' ', '-', $urltitle);

            // Shop
            if (!empty($file ['name'])) {
                $item = array(
                    'shop_id' => $formElements ['elemId'],
                    // 'user_id' => !empty($formElements['parentuser']) ? $formElements['parentuser'] : 0,
                    'shop_name' => $formElements ['elemtName'],
                    'urltitle' => $urltitle,
                    'description' => isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                            : '',
                    'theme' => isset($formElements ['shopdesign']) ? $formElements ['shopdesign']
                            : ''
                    )
                // 'address' => isset($formElements['elemtAddrs']) ? $formElements['elemtAddrs'] : '',
                // 'telephone' => isset($formElements['elemtTele']) ? $formElements['elemtTele'] : '',
                // 'fax' => isset($formElements['elemtFax']) ? $formElements['elemtFax'] : '',
                // 'email' => isset($formElements['elemtEmail']) ? $formElements['elemtEmail'] : '',
                // 'pictures' => isset($file['name']) ? $file['name'] : '',
                // 'shoptype_id' => isset($formElements['ecommerce']) ? $formElements['ecommerce'] : '',
                // 'domain' => isset($formElements['ecomDomain']) ? $formElements['ecomDomain'] : '',
                // 'hostname' => isset($formElements['ecomHost']) ? $formElements['ecomHost'] : '',
                // 'dbname' => isset($formElements['ecomDb']) ? $formElements['ecomDb'] : '',
                // 'username' => isset($formElements['ecomUser']) ? $formElements['ecomUser'] : '',
                // 'password' => isset($formElements['ecomPswrd']) ? $formElements['ecomPswrd'] : '',
                // 'table_prefix' => isset($formElements['table_prefix']) ? $formElements['table_prefix'] : '',
                // 'status' => isset($formElements['status']) ? $formElements['status'] : 0,
                ;
            } else {
                $item = array(
                    'shop_id' => $formElements ['elemId'],
                    // 'user_id' => !empty($formElements['parentuser']) ? $formElements['parentuser'] : 0,
                    'shop_name' => $formElements ['elemtName'],
                    'urltitle' => $urltitle,
                    'description' => isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                            : '',
                    'theme' => isset($formElements ['shopdesign']) ? $formElements ['shopdesign']
                            : ''
                    )
                // 'address' => isset($formElements['elemtAddrs']) ? $formElements['elemtAddrs'] : '',
                // 'telephone' => isset($formElements['elemtTele']) ? $formElements['elemtTele'] : '',
                // 'fax' => isset($formElements['elemtFax']) ? $formElements['elemtFax'] : '',
                // 'email' => isset($formElements['elemtEmail']) ? $formElements['elemtEmail'] : '',
                // 'shoptype_id' => isset($formElements['ecommerce']) ? $formElements['ecommerce'] : '',
                // 'domain' => isset($formElements['ecomDomain']) ? $formElements['ecomDomain'] : '',
                // 'hostname' => isset($formElements['ecomHost']) ? $formElements['ecomHost'] : '',
                // 'dbname' => isset($formElements['ecomDb']) ? $formElements['ecomDb'] : '',
                // 'username' => isset($formElements['ecomUser']) ? $formElements['ecomUser'] : '',
                // 'password' => isset($formElements['ecomPswrd']) ? $formElements['ecomPswrd'] : '',
                // 'table_prefix' => isset($formElements['table_prefix']) ? $formElements['table_prefix'] : '',
                // 'status' => isset($formElements['status']) ? $formElements['status'] : 0,
                ;
            }

            $args = array(
                'table' => 'zselex_shop',
                'element' => $item,
                'Id' => 'shop_id'
            );

            if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) {
                $args   = array(
                    'table' => 'zselex_shop',
                    'element' => $item,
                    'Id' => 'shop_id'
                );
                // Create the zselex type
                $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement',
                        $args);
                if (!empty($file ['name'])) {
                    $this->uploadSingleFile($file, $destination = '');
                }
                $InsertId = DBUtil::getInsertID($args ['table'], $args ['Id']);
            } else { // update shop
                $InsertId    = $formElements ['elemId'];
                // echo "hiii"; exit;
                $destination = pnGetBaseURL().'zselexdata/shops';

                $loguser = UserUtil::getVar('uid');

                // if (!empty($file['name'])) {
                // unlink('zselexdata/shops/' . $formElements['hiddenpicture']);
                // unlink('zselexdata/shops/thumbs/' . $formElements['hiddenpicture']);
                // $this->uploadSingleFile($file, $destination = '');
                // $pictures = 'pictures' . '=>' . $file['name'];
                // }
                //
				$modvariable = $this->getVars();
                if (!empty($files)) {
                    // unlink('zselexdata/shops/' . $formElements['hiddenpicture']);
                    // unlink('zselexdata/shops/thumbs/' . $formElements['hiddenpicture']);
                    // $this->uploadSingleFile($file, $destination = '');

                    $files = $this->reArrayFiles($files);
                    // $file = array();
                    foreach ($files as $file) {
                        // LogUtil::registerError($this->__('Error! Invalid category passed.'));
                        // $tmpName = $_FILES['userfile']['tmp_name'];
                        $error = 0;

                        list ( $width, $height, $type, $attr ) = getimagesize($file ['tmp_name']);

                        // $modvars['fullimagewidth'];
                        // echo "width :" . $width;
                        // echo "<br>";
                        // echo "height :" . $height;
                        // exit;

                        if ($width > $modvariable ['fullimagewidth']) {
                            LogUtil::registerError($this->__f('Error! Image %s is too wide.',
                                    $file [name]));
                            ++$error;
                        } else {
                            --$error;
                        }
                        if ($height > $modvariable ['fullimageheight']) {
                            LogUtil::registerError($this->__f('Error! Image %s is too high.',
                                    $file [name]));
                            ++$error;
                        } else {
                            --$error;
                        }

                        if ($error < 1) {
                            // echo "width :" . $width;
                            // echo "<br>";
                            // echo "height :" . $height;
                            // exit;

                            $random_digit  = rand(0000, 9999);
                            $new_file_name = time().'_'.$file ['name'];
                            $newNme        = array(
                                'newName' => $new_file_name
                            );
                            $file          = $file + $newNme;
                            $destination   = 'zselexdata/shops';
                            $this->uploadMultipleFile($file, $destination);

                            $chkForDefltImge = ModUtil::apiFunc('ZSELEX',
                                    'user', 'selectRow',
                                    array(
                                    'table' => 'zselex_files',
                                    'where' => array(
                                        "shop_id='".$InsertId."'",
                                        'defaultImg=1'
                                    )
                            ));

                            if (count($chkForDefltImge < 1)) {
                                $addDefault = 1;
                            } else {
                                $addDefault = 0;
                            }

                            $insertImgeId = "INSERT INTO zselex_files (name,shop_id,user_id,defaultImg)VALUES('".$new_file_name."' , '".$InsertId."' , '".$loguser."' , '".$addDefault."')";
                            $statement    = Doctrine_Manager::getInstance()->connection();
                            $results      = $statement->execute($insertImgeId);

                            // if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {

                            $serviceupdatearg = array(
                                'user_id' => $loguser,
                                'type' => 'createimage',
                                'shop_id' => $InsertId
                            );
                            $serviceavailed   = ModUtil::apiFunc('ZSELEX',
                                    'admin', 'updateServiceAvailed',
                                    $serviceupdatearg);
                            // }
                        }
                    }
                }

                // echo $formElements['defaultimage']; exit;

                if (isset($formElements ['defaultimage'])) {
                    $sql       = "UPDATE zselex_files SET defaultImg='1' WHERE file_id='".$formElements ['defaultimage']."' AND
                              shop_id = '".$InsertId."'";
                    $statement = Doctrine_Manager::getInstance()->connection();
                    $results   = $statement->execute($sql);

                    $sql       = "update zselex_files set defaultImg=0 where file_id!='".$formElements ['defaultimage']."' and
                             shop_id = '".$InsertId."'";
                    $statement = Doctrine_Manager::getInstance()->connection();
                    $results   = $statement->execute($sql);
                }

                // ModUtil::apiFunc('ZSELEX', 'admin', 'uploadFile', $args = array('fname' => $_FILES['formElements']['name']['shopimage'], 'destination' => $dest));
                // update the type
                if (!empty($file ['name'])) {
                    $item = array(
                        'shop_id' => $formElements ['elemId'],
                        'shop_name' => $formElements ['elemtName'],
                        'urltitle' => $urltitle,
                        'description' => isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                                : '',
                        'theme' => isset($formElements ['shopdesign']) ? $formElements ['shopdesign']
                                : ''
                        )
                    // 'address' => isset($formElements['elemtAddrs']) ? $formElements['elemtAddrs'] : '',
                    // 'telephone' => isset($formElements['elemtTele']) ? $formElements['elemtTele'] : '',
                    // 'fax' => isset($formElements['elemtFax']) ? $formElements['elemtFax'] : '',
                    // 'email' => isset($formElements['elemtEmail']) ? $formElements['elemtEmail'] : '',
                    // 'pictures' => isset($file['name']) ? $file['name'] : '',
                    // 'shoptype_id' => isset($formElements['ecommerce']) ? $formElements['ecommerce'] : '',
                    // 'domain' => isset($formElements['ecomDomain']) ? $formElements['ecomDomain'] : '',
                    // 'hostname' => isset($formElements['ecomHost']) ? $formElements['ecomHost'] : '',
                    // 'dbname' => isset($formElements['ecomDb']) ? $formElements['ecomDb'] : '',
                    // 'username' => isset($formElements['ecomUser']) ? $formElements['ecomUser'] : '',
                    // 'password' => isset($formElements['ecomPswrd']) ? $formElements['ecomPswrd'] : '',
                    // 'table_prefix' => isset($formElements['table_prefix']) ? $formElements['table_prefix'] : '',
                    // 'status' => isset($formElements['status']) ? $formElements['status'] : 0,
                    ;
                } else {
                    $item = array(
                        'shop_id' => $formElements ['elemId'],
                        'shop_name' => $formElements ['elemtName'],
                        'urltitle' => $urltitle,
                        'description' => isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                                : '',
                        'theme' => isset($formElements ['shopdesign']) ? $formElements ['shopdesign']
                                : ''
                        )
                    // 'address' => isset($formElements['elemtAddrs']) ? $formElements['elemtAddrs'] : '',
                    // 'telephone' => isset($formElements['elemtTele']) ? $formElements['elemtTele'] : '',
                    // 'fax' => isset($formElements['elemtFax']) ? $formElements['elemtFax'] : '',
                    // 'email' => isset($formElements['elemtEmail']) ? $formElements['elemtEmail'] : '',
                    // 'shoptype_id' => isset($formElements['ecommerce']) ? $formElements['ecommerce'] : '',
                    // 'domain' => isset($formElements['ecomDomain']) ? $formElements['ecomDomain'] : '',
                    // 'hostname' => isset($formElements['ecomHost']) ? $formElements['ecomHost'] : '',
                    // 'dbname' => isset($formElements['ecomDb']) ? $formElements['ecomDb'] : '',
                    // 'username' => isset($formElements['ecomUser']) ? $formElements['ecomUser'] : '',
                    // 'password' => isset($formElements['ecomPswrd']) ? $formElements['ecomPswrd'] : '',
                    // 'table_prefix' => isset($formElements['table_prefix']) ? $formElements['table_prefix'] : '',
                    // 'status' => isset($formElements['status']) ? $formElements['status'] : 0,
                    ;
                }

                $updateargs = array(
                    'table' => 'zselex_shop',
                    'IdValue' => $InsertId,
                    'IdName' => 'shop_id',
                    'element' => $item
                );

                $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement',
                        $updateargs);
                if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
                    $delParentQuery = "DELETE FROM zselex_parent WHERE childType = '".$formElements ['childType']."' AND childId = '".$InsertId."' AND parentType='SHOPADMIN'";
                    DBUtil::executeSQL($delParentQuery);
                }
                // $delresult = ModUtil::apiFunc('ZSELEX', 'admin', 'execteQuery', $delParentQuery);
            }

            if ($result == true) {
                if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) {
                    LogUtil::registerStatus($this->__('Done! City has been created successfully.'));
                } else {
                    LogUtil::registerStatus($this->__('Done! Shop details has been updated successfully.'));
                }
                // Success
                if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
                    $childType = $formElements ['childType'];

                    if (!empty($formElements ['shopadmins'])) {
                        foreach ($formElements ['shopadmins'] as $shopadmin) {
                            $items = array(
                                'childId' => $InsertId,
                                'childType' => 'SHOP',
                                'parentId' => $shopadmin,
                                'parentType' => 'SHOPADMIN'
                            );

                            $args            = array(
                                'table' => 'zselex_parent',
                                'element' => $items,
                                'Id' => 'tableId'
                            );
                            $resultshopadmin = ModUtil::apiFunc('ZSELEX',
                                    'admin', 'createElement', $args);
                        }
                    }
                }
            } else {
                // fail! type not created
                throw new Zikula_Exception_Fatal($this->__('Error! Site was not created (probably API failure).'));

                return false;
            }
        }

        // print_r($item);
        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshop'));
    }

    public function purifyHtml($formElements)
    {
        $formElements = ModUtil::apiFunc('ZSELEX', 'admin', 'purifyHtml',
                $formElements);

        return $formElements;
    }

    public function submitshop($args)
    { // update
        // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
        // echo "<pre>"; print_r($_POST['formElements']); echo "</pre>"; exit;
        // Get parameters cr_date whatever input we need
        $this->view->setCaching(false);
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());

        $shopRepo     = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        $formElements = FormUtil::getPassedValue('formElements',
                isset($args ['formElements']) ? $args ['formElements'] : null,
                'POST');
        $formElements = $this->purifyHtml($formElements);
        // $file = FormUtil::getPassedValue('files', isset($args['files']) ? $args['files'] : null, 'FILES');
        $files        = FormUtil::getPassedValue('files',
                isset($args ['files']) ? $args ['files'] : null, 'FILES');
        $shop_id      = $formElements ['elemId'];
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $this->checkCsrfToken();
        $InsertId = $formElements ['elemId'];

        $getCurrentShop = $shopRepo->get(array(
            'entity' => 'ZSELEX_Entity_Shop',
            'fields' => array(
                'a.urltitle'
            ),
            'where' => array(
                'a.shop_id' => $InsertId
            )
        ));
        // echo $shop_id; exit;
        // extract($_FILES['file']);
        // echo "<pre>"; print_r($getCurrentShop); echo "</pre>"; exit;
        $filespershop   = FormUtil::getPassedValue('filespershop',
                isset($args ['filespershop']) ? $args ['filespershop'] : 1,
                'GETPOST');

        $country_id = $_POST ['country-combo__sexyComboHidden'];
        // echo "<pre>"; print_r($formElements['plugins']); echo "</pre>"; exit;

        $modvariable = $this->getVars();
        // echo "<pre>"; print_r($files); echo "</pre>"; exit;
        // ******* Validation happens here ******//

        $validation_rules = array(
            'shop_name' => array(
                'required' => true,
                'value' => $formElements ['elemtName'],
                'label' => 'Name'
            )
        );
        // echo "<pre>"; print_r($validation_rules); echo "</pre>"; exit;
        $validationerror  = ZSELEX_Util::validate($validation_rules);

        if ($validationerror !== false) {
            // log the error found if any
            if ($validationerror !== false) {
                LogUtil::registerError(nl2br($validationerror));
            }
            SessionUtil::setVar('createshop', $formElements);

            $this->redirect(ModUtil::url('ZSELEX', 'admin', 'modifyshop',
                    array(
                    'id' => $formElements ['elemId']
            )));
        } else {
            // As we're not previewing the item let's remove it from the session
            SessionUtil::delVar('createshop');
        }
        // ******* Validation ends ******//

        if ($formElements ['elemtName']) {
            if (!empty($formElements ['urltitle'])) {
                $urltitle       = $formElements ['urltitle'];
                // $urltitle = str_replace(" ", '-', $urltitle);
                $urltitle       = ZSELEX_Util::cleanTitle($urltitle);
                $args_url       = array(
                    'table' => 'zselex_shop',
                    'title' => $urltitle,
                    'field' => 'urltitle'
                );
                $final_urltitle = $this->increment_url($args_url);
            } else {
                $urltitle       = strtolower($formElements ['elemtName']);
                // $urltitle = str_replace(" ", '-', $urltitle);
                $urltitle       = ZSELEX_Util::cleanTitle($urltitle);
                $args_url       = array(
                    'table' => 'zselex_shop',
                    'title' => $urltitle,
                    'field' => 'urltitle'
                );
                $final_urltitle = $this->increment_url($args_url);
            }

            // echo $urltitle; exit;

            $urlCount = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount',
                    $args     = array(
                    'table' => 'zselex_shop',
                    'where' => "urltitle='".$urltitle."' AND shop_id!=$shop_id"
            ));

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

            // Shop
            // update the shop
            $super_admin = SecurityUtil::checkPermission('ZSELEX::', '::',
                    ACCESS_ADMIN);

            $item = array(
                'shop_id' => $formElements ['elemId'],
                'user_id' => !empty($formElements ['parentuser']) ? $formElements ['parentuser']
                        : 0,
                'title' => $formElements ['urltitle'],
                'urltitle' => $final_urltitle,
                'country' => (!empty($formElements ['changeparents']) && !empty($formElements ['country-combo']))
                        ? $formElements ['country-combo'] : $formElements ['parentCountry'],
                'region' => (!empty($formElements ['changeparents']) && !empty($formElements ['region-combo']))
                        ? $formElements ['region-combo'] : $formElements ['parentRegion'],
                'city' => (!empty($formElements ['changeparents']) && !empty($formElements ['city-combo']))
                        ? $formElements ['city-combo'] : $formElements ['parentCity'],
                'area' => (!empty($formElements ['changeparents']) && !empty($formElements ['area-combo']))
                        ? $formElements ['area-combo'] : $formElements ['parentArea'],
                // 'cat_id' => isset($formElements['category-combo']) ? $formElements['category-combo'] : '',
                'branch' => isset($formElements ['branch']) ? $formElements ['branch']
                        : '',
                'theme' => isset($formElements ['shopdesign']) ? $formElements ['shopdesign']
                        : '',
                'shop_name' => $formElements ['elemtName'],
                'description' => isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                        : '',
                // 'address' => isset($formElements['elemtAddrs']) ? $formElements['elemtAddrs'] : '',
                // 'telephone' => isset($formElements['elemtTele']) ? $formElements['elemtTele'] : '',
                // 'fax' => isset($formElements['elemtFax']) ? $formElements['elemtFax'] : '',
                // 'email' => isset($formElements['elemtEmail']) ? $formElements['elemtEmail'] : '',
                // 'opening_hours' => isset($formElements['opening_hours']) ? $formElements['opening_hours'] : '',
                'shoptype_id' => isset($formElements ['ecommerce']) ? $formElements ['ecommerce']
                        : '',
                // 'domain' => isset($formElements['ecomDomain']) ? $formElements['ecomDomain'] : '',
                // 'hostname' => isset($formElements['ecomHost']) ? $formElements['ecomHost'] : '',
                // 'dbname' => isset($formElements['ecomDb']) ? $formElements['ecomDb'] : '',
                // 'username' => isset($formElements['ecomUser']) ? $formElements['ecomUser'] : '',
                // 'password' => isset($formElements['ecomPswrd']) ? $formElements['ecomPswrd'] : '',
                // 'table_prefix' => isset($formElements['table_prefix']) ? $formElements['table_prefix'] : '',
                'status' => isset($formElements ['status']) ? $formElements ['status']
                        : 0
            );

            if ($super_admin) {
                $item ['aff_id'] = isset($formElements ['affiliate']) ? $formElements ['affiliate']
                        : 0;
            }

            // echo "<pre>"; print_r($item); echo "</pre>"; exit;

            $updArgs = array(
                'entity' => 'ZSELEX_Entity_Shop',
                'fields' => $item,
                'where' => array(
                    'a.shop_id' => $InsertId
                )
            );
            $result  = $shopRepo->updateEntity($updArgs);

            // $delresult = ModUtil::apiFunc('ZSELEX', 'admin', 'execteQuery', $delParentQuery);
            // UPDATE ENDS

            if ($result == true) { // SAVING RELATIONS
                if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) {
                    LogUtil::registerStatus($this->__('Done! Shop has been created successfully.'));
                } else {
                    LogUtil::registerStatus($this->__('Done! Shop details has been updated successfully.'));
                }
                // Success
                // Old url support
                $urlArr    = array(
                    'shop_id' => $InsertId,
                    'title' => $getCurrentShop ['urltitle']
                );
                $updateUrl = $this->entityManager->getRepository('ZSELEX_Entity_Url')->updateShopUrl($urlArr);

                $categories     = $formElements ['shop_cats'];
                // echo "<pre>"; print_r($categories); echo "</pre>"; exit;
                $saveCategories = ModUtil::apiFunc('ZSELEX', 'admin',
                        'saveShopCategories',
                        array(
                        'shop_id' => $InsertId,
                        'categories' => $categories
                ));

                $branches      = $formElements ['branches'];
                $save_branches = ModUtil::apiFunc('ZSELEX', 'admin',
                        'saveShopBranches',
                        array(
                        'shop_id' => $InsertId,
                        'branches' => $branches
                ));

                $keyRepo     = $this->entityManager->getRepository('ZSELEX_Entity_Keyword');
                $delKeywords = $keyRepo->deleteEntity(null,
                    'ZSELEX_Entity_Keyword',
                    array(
                    'a.type_id' => $InsertId
                ));
                // $where_keyword = "WHERE type='shop' AND type_id=$InsertId";
                if ($delKeywords) {
                    if (!empty($formElements ['elemtName'])) {
                        $keyword = $formElements ['elemtName'];
                        // $keywords_for_search = str_replace(",", " ", $formElements['elemtName']);
                        // $keywords_for_search = explode(" ", $keywords_for_search);
                        // foreach ($keywords_for_search as $keyword) {

                        $keywordExist = $keyRepo->getCount(null,
                            'ZSELEX_Entity_Keyword', 'keyword_id',
                            array(
                            'a.keyword' => $keyword
                        ));

                        if ($keywordExist < 1) {
                            if (!empty($keyword)) {
                                $keyword_item   = array(
                                    'keyword' => $keyword,
                                    'type' => 'shop',
                                    'type_id' => $InsertId,
                                    'shop_id' => $formElements ['elemId']
                                );
                                $keyword_args   = array(
                                    'table' => 'zselex_keywords',
                                    'element' => $keyword_item,
                                    'Id' => 'keyword_id'
                                );
                                // $result_keyword = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $keyword_args);
                                $result_keyword = $keyRepo->createKeyword($keyword_item);
                            }
                        }
                        // }
                    }
                }

                ModUtil::apiFunc('ZSELEX', 'admin', 'updateMinisite',
                    array(
                    'shop_id' => $InsertId
                ));
            } else { //
                // fail! type not created
                throw new Zikula_Exception_Fatal($this->__('Error! Site was not created (probably API failure).'));

                return false;
            }
        }

        // print_r($item);
        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshop'));
    }

    public function increment_url($args)
    {
        $title    = $args ['title'];
        $table    = $args ['table'];
        $field    = $args ['field'];
        $sql      = "SELECT COALESCE( CONCAT( '".$title."', SUBSTRING( MAX( $field ) , CHAR_LENGTH( '".$title."' ) +1 ) *1 +1 ) , '".$title."' ) $field
                    FROM $table
                    WHERE $field REGEXP '$title([0-9]+)?$'";
        $query    = DBUtil::executeSQL($sql);
        $result   = $query->fetch();
        $urltitle = $result [$field];

        return $urltitle;
    }

    public function submitshopInsert($args)
    { // insert
        // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
        // echo "<pre>"; print_r($_POST['formElements']); echo "</pre>"; exit;
        // Get parameters cr_date whatever input we need
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        $this->checkCsrfToken();
        $shopRepo     = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        $formElements = FormUtil::getPassedValue('formElements',
                isset($args ['formElements']) ? $args ['formElements'] : null,
                'POST');
        $formElements = $this->purifyHtml($formElements);
        // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;
        // $file = FormUtil::getPassedValue('files', isset($args['files']) ? $args['files'] : null, 'FILES');
        $files        = FormUtil::getPassedValue('files',
                isset($args ['files']) ? $args ['files'] : null, 'FILES');
        // extract($_FILES['file']);
        // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;
        $filespershop = FormUtil::getPassedValue('filespershop',
                isset($args ['filespershop']) ? $args ['filespershop'] : 1,
                'GETPOST');

        $country_id = $_POST ['country-combo__sexyComboHidden'];
        // echo "<pre>"; print_r($formElements['plugins']); echo "</pre>"; exit;

        $modvariable = $this->getVars();
        // echo "<pre>"; print_r($files); echo "</pre>"; exit;

        $itemValidate = array(
            'shop_name|Shop Name' => $formElements ['elemtName'],
            // 'description|Description' => isset($formElements['elemtDesc']) ? $formElements['elemtDesc'] : '',
            'email|Email' => isset($formElements ['elemtEmail']) ? $formElements ['elemtEmail']
                    : ''
        );

        $validation_rules = array(
            'shop_name' => array(
                'required' => true,
                'value' => $formElements ['elemtName'],
                'label' => 'Name'
            )
        );

        // ******* Validation happens here ******//
        // $validationerror = ZSELEX_Util::validateItems($itemValidate);
        $validationerror = ZSELEX_Util::validate($validation_rules);

        if ($validationerror !== false) {
            // log the error found if any
            if ($validationerror !== false) {
                LogUtil::registerError(nl2br($validationerror));
            }
            SessionUtil::setVar('createshop', $formElements);
            $sess_item = SessionUtil::getVar('createshop');

            $this->redirect(ModUtil::url('ZSELEX', 'admin', 'createshop'));
        } else {
            // As we're not previewing the item let's remove it from the session
            SessionUtil::delVar('createshop');
        }
        // ******* Validation ends ******//

        if ($formElements ['elemtName']) {
            if (!empty($formElements ['urltitle'])) {
                $urltitle       = $formElements ['urltitle'];
                // $urltitle = str_replace(" ", '-', $urltitle);
                $urltitle       = ZSELEX_Util::cleanTitle($urltitle);
                $args_url       = array(
                    'table' => 'zselex_shop',
                    'title' => $urltitle,
                    'field' => 'urltitle'
                );
                $final_urltitle = $this->increment_url($args_url);
            } else {
                $urltitle       = strtolower($formElements ['elemtName']);
                // $urltitle = str_replace(" ", '-', $urltitle);
                $urltitle       = ZSELEX_Util::cleanTitle($urltitle);
                $args_url       = array(
                    'table' => 'zselex_shop',
                    'title' => $urltitle,
                    'field' => 'urltitle'
                );
                $final_urltitle = $this->increment_url($args_url);
            }

            // echo $urltitle; exit;

            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
                // Shop

                $item = array(
                    'shop_id' => $formElements ['elemId'],
                    'user_id' => !empty($formElements ['parentuser']) ? $formElements ['parentuser']
                            : 0,
                    'title' => $formElements ['urltitle'],
                    'urltitle' => $final_urltitle,
                    'country_id' => isset($formElements ['country-combo']) ? $formElements ['country-combo']
                            : '',
                    'region_id' => isset($formElements ['region-combo']) ? $formElements ['region-combo']
                            : '',
                    'city_id' => isset($formElements ['city-combo']) ? $formElements ['city-combo']
                            : '',
                    'area_id' => isset($formElements ['area-combo']) ? $formElements ['area-combo']
                            : '',
                    // 'cat_id' => isset($formElements['category-combo']) ? $formElements['category-combo'] : '',
                    'branch_id' => isset($formElements ['branch']) ? $formElements ['branch']
                            : '',
                    'aff_id' => isset($formElements ['affiliate']) ? $formElements ['affiliate']
                            : '',
                    'theme' => isset($formElements ['shopdesign']) ? $formElements ['shopdesign']
                            : '',
                    'shop_name' => $formElements ['elemtName'],
                    'description' => isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                            : '',
                    // 'address' => isset($formElements['elemtAddrs']) ? $formElements['elemtAddrs'] : '',
                    // 'telephone' => isset($formElements['elemtTele']) ? $formElements['elemtTele'] : '',
                    // 'fax' => isset($formElements['elemtFax']) ? $formElements['elemtFax'] : '',
                    // 'email' => isset($formElements['elemtEmail']) ? $formElements['elemtEmail'] : '',
                    // 'opening_hours' => isset($formElements['opening_hours']) ? $formElements['opening_hours'] : '',
                    'default_img_frm' => 'fromshop',
                    'shoptype_id' => isset($formElements ['ecommerce']) ? $formElements ['ecommerce']
                            : '',
                    'domain' => isset($formElements ['ecomDomain']) ? $formElements ['ecomDomain']
                            : '',
                    'hostname' => isset($formElements ['ecomHost']) ? $formElements ['ecomHost']
                            : '',
                    'dbname' => isset($formElements ['ecomDb']) ? $formElements ['ecomDb']
                            : '',
                    'username' => isset($formElements ['ecomUser']) ? $formElements ['ecomUser']
                            : '',
                    'password' => isset($formElements ['ecomPswrd']) ? $formElements ['ecomPswrd']
                            : '',
                    'table_prefix' => isset($formElements ['table_prefix']) ? $formElements ['table_prefix']
                            : '',
                    'status' => isset($formElements ['status']) ? $formElements ['status']
                            : 0
                );

                // echo "<pre>"; print_r($item); echo "</pre>"; exit;

                $args = array(
                    'table' => 'zselex_shop',
                    'element' => $item,
                    'Id' => 'shop_id'
                );

                if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) { // INSERT
                    $args = array(
                        'table' => 'zselex_shop',
                        'element' => $item,
                        'Id' => 'shop_id'
                    );
                    // Create the shop
                    // $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $args);
                    // $InsertId = DBUtil::getInsertID($args['table'], $args['Id']);

                    $result   = $shopRepo->createShop($item);
                    $InsertId = $result;
                }
                if ($result == true) { // SAVING RELATIONS
                    if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) {
                        LogUtil::registerStatus($this->__('Done! Shop has been created successfully.'));
                    } else {
                        LogUtil::registerStatus($this->__('Done! Shop details has been updated successfully.'));
                    }
                    // Success
                    // $shop = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->find($InsertId);
                    // echo "shop:" . $shop; exit;t
                    // echo $InsertId; exit;
                    /*
                     * $settings_entity = new ZSELEX_Entity_ShopSetting();
                     * $settings_entity->setShop($InsertId);
                     * $settings_entity->setMain('0');
                     * $this->entityManager->persist($settings_entity);
                     * // $this->entityManager->merge($settings_entity);
                     * $this->entityManager->flush();
                     */

                    $categories = $formElements ['shop_cats'];
                    /*
                     * $saveCategories = ModUtil::apiFunc('ZSELEX', 'admin', 'saveShopCategories', array(
                     * 'shop_id' => $InsertId,
                     * 'categories' => $categories
                     * ));
                     */

                    $addCategories = $shopRepo->addShopCategories(array(
                        'categories' => $categories,
                        'shop_id' => $InsertId
                    ));

                    $branches     = $formElements ['branches'];
                    $add_branches = $shopRepo->addShopBranches(array(
                        'branches' => $branches,
                        'shop_id' => $InsertId
                    ));
                    if (!empty($formElements ['elemtName'])) {
                        $item_setting = array(
                            'shop_id' => $InsertId
                        );

                        $args_setting   = array(
                            'table' => 'zselex_shop_a_settings',
                            'element' => $item_setting,
                            'Id' => 'id'
                        );
                        // Create the shop
                        $result_setting = ModUtil::apiFunc('ZSELEX', 'admin',
                                'createElement', $args_setting);

                        $keyword = $formElements ['elemtName'];
                        // $keywords_for_search = str_replace(",", " ", $formElements['elemtName']);
                        // $keywords_for_search = explode(" ", $keywords_for_search);
                        // foreach ($keywords_for_search as $keyword) {
                        $keyRepo = $this->entityManager->getRepository('ZSELEX_Entity_Keyword');
                        /*
                         * $keywordExist = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $args = array(
                         * 'table' => 'zselex_keywords',
                         * 'where' => "keyword='" . $keyword . "'"
                         * ));
                         */

                        $keywordExist = $keyRepo->getCount(null,
                            'ZSELEX_Entity_Keyword', 'keyword_id',
                            array(
                            'a.keyword' => $keyword
                        ));

                        if ($keywordExist < 1) {
                            if (!empty($keyword)) {
                                $keyword_item   = array(
                                    'keyword' => $keyword,
                                    'type' => 'shop',
                                    'type_id' => $InsertId,
                                    'shop_id' => $InsertId
                                );
                                /*
                                 * $keyword_args = array(
                                 * 'table' => 'zselex_keywords',
                                 * 'element' => $keyword_item,
                                 * 'Id' => 'keyword_id'
                                 * );
                                 * $result_keyword = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $keyword_args);
                                 *
                                 */
                                $result_keyword = $keyRepo->createKeyword($keyword_item);
                            }
                        }
                        // }
                    }
                } else { //
                    // fail! type not created
                    throw new Zikula_Exception_Fatal($this->__('Error! Site was not created (probably API failure).'));

                    return false;
                }
            } // lement sublit ends
        }

        // print_r($item);
        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshop'));
    }

    public static function reArrayFiles($file_post)
    {
        if (!isset($file_post) || !is_array($file_post)) {
            return;
        }
        $file_ary   = array();
        $file_count = count($file_post ['name']);
        $file_keys  = array_keys($file_post);

        for ($i = 0; $i < $file_count; ++$i) {
            foreach ($file_keys as $key) {
                $file_ary [$i] [$key] = $file_post [$key] [$i];
            }
        }
        // filter out empty values
        foreach ($file_ary as $k => $f) {
            if (empty($f ['name'])) {
                unset($file_ary [$k]);
            }
        }

        return $file_ary;
    }

    public function modifyshop()
    {

        // echo pnGetBaseURL().'modules/zselex/images/cart_close_btn.jpg';
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        $this->view->setCaching(false);

        $shop_id = FormUtil::getPassedValue('id',
                isset($args ['id']) ? $args ['id'] : null, 'GETPOST');

        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $modvariable = $this->getVars();

        // echo "<pre>"; print_r($modvariable); echo "</pre>";
        $shopargs = array(
            'table' => 'zselex_shop a',
            'where' => array(
                "a.shop_id=$shop_id"
            ),
            'fields' => array(
                '*',
                'a.country_id as country_id',
                'a.region_id as region_id',
                'a.city_id as city_id',
                'a.area_id as area_id',
                'a.description as description',
                'a.status as status'
            ),
            'joins' => array(
                'LEFT JOIN zselex_country b ON a.country_id=b.country_id',
                'LEFT JOIN zselex_region c ON a.region_id=c.region_id',
                'LEFT JOIN zselex_city d ON a.city_id=d.city_id',
                'LEFT JOIN zselex_area e ON a.area_id=e.area_id'
            )
        );
        $item     = ModUtil::apiFunc('ZSELEX', 'user', 'selectJoinRow',
                $shopargs);

        $item ['categories'] = ModUtil::apiFunc('ZSELEX', 'user', 'getAll',
                array(
                'table' => 'zselex_shop_to_category',
                'where' => "shop_id=$shop_id",
                'fields' => array(
                    'category_id'
                )
        ));

        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Shop');

        $item ['branches'] = $repo->getAll(array(
            'entity' => 'ZSELEX_Entity_Shop',
            'fields' => array(
                'b.branch_id'
            ),
            'joins' => array(
                'JOIN a.shop_to_branch b'
            ),
            'where' => array(
                "a.shop_id" => $shop_id
            )
        ));

        // echo "<pre>"; print_r($item['branches']); echo "</pre>";
        // echo "<pre>"; print_r($item); echo "</pre>";
        // $args = array('table' => 'zselex_shop', 'IdValue' => $shop_id, 'IdName' => 'shop_id');
        // Get the news type in the db
        // $item = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $args);

        if ($item ['user_id'] > 0) {
            $shopuserargs           = array(
                'table' => 'users',
                'IdValue' => $item ['user_id'],
                'IdName' => 'uid'
            );
            // Get the news type in the db
            $parentuser             = ModUtil::apiFunc('ZSELEX', 'admin',
                    'getElement', $shopuserargs);
            $item ['parentuser']    = $parentuser ['uname'];
            $item ['parentuser_id'] = $parentuser ['uid'];
        }

        $pluginargs = array(
            'table' => 'zselex_plugin',
            'where' => '',
            'orderBy' => '',
            'useJoins' => ''
        );
        $plugins    = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $pluginargs);

        $loguser               = UserUtil::getVar('uid');
        $servicePurchased      = ModUtil::apiFunc('ZSELEX', 'admin',
                'getServicePurchased',
                $args                  = array(
                'user_id' => $loguser,
                'shop_id' => $shop_id
        ));
        // echo "<pre>"; print_r($servicePurchased); echo "</pre>";
        $servicePurchasedCount = ModUtil::apiFunc('ZSELEX', 'admin',
                'getServicePurchasedCount',
                $args                  = array(
                'user_id' => $loguser,
                'shop_id' => $shop_id
        ));
        $serviceImageQnty      = ModUtil::apiFunc('ZSELEX', 'admin',
                'getServiceImage',
                $args                  = array(
                'user_id' => $loguser,
                'shop_id' => $shop_id,
                'type' => 'createimage'
        ));
        $imgqty                = $serviceImageQnty ['quantity'] - $serviceImageQnty ['availed'];

        $shopImages = ModUtil::apiFunc('ZSELEX', 'admin', 'getShopImages',
                $args       = array(
                'user_id' => $loguser,
                'shop_id' => $shop_id
        ));

        $argss = array(
            'table' => 'zselex_shop',
            'fields' => array(
                'shop_id',
                'shop_name',
                'description'
            ),
            'where' => array(
                'user_id' => '4',
                'email' => 'test@test.com'
            )
        );
        $s     = ModUtil::apiFunc('ZSELEX', 'admin', 'selectItems', $argss);
        // echo "<pre>"; print_r($shopImages); echo "</pre>";

        $loguser = UserUtil::getVar('uid');

        $modvars = $this->getVars();
        // echo "<pre>"; print_r($modvars); echo "</pre>";
        // $designs = $modvars['ZSELEXthemes'];

        /*
         * $argsthemes = array(
         * 'table' => 'zselex_plugin',
         * 'fields' => array(),
         * 'where' => array(
         * 'parent' => 'theme'
         * )
         * );
         * $designs = ModUtil::apiFunc('ZSELEX', 'admin', 'selectItems', $argsthemes);
         */

        // echo "<pre>"; print_r($design); echo "</pre>";
        // echo "<pre>"; print_r($item); echo "</pre>";
        $countryArgs = array(
            'entity' => 'ZSELEX_Entity_Country',
            'fields' => array(
                'a.country_id',
                'a.country_name'
            ),
            'orderby' => 'a.country_name ASC'
        );
        $countries   = $this->entityManager->getRepository('ZSELEX_Entity_Country')->getAll($countryArgs);

        $regionArgs = array(
            'entity' => 'ZSELEX_Entity_Region',
            'fields' => array(
                'a.region_id',
                'a.region_name'
            ),
            'orderby' => 'a.region_name ASC'
        );
        $regions    = $this->entityManager->getRepository('ZSELEX_Entity_Country')->getAll($regionArgs);
        $cityArgs   = array(
            'entity' => 'ZSELEX_Entity_City',
            'fields' => array(
                'a.city_id',
                'a.city_name'
            ),
            'orderby' => 'a.city_name ASC'
        );
        $cities     = $this->entityManager->getRepository('ZSELEX_Entity_Country')->getAll($cityArgs);
        $areaArgs   = array(
            'entity' => 'ZSELEX_Entity_Area',
            'fields' => array(
                'a.area_id',
                'a.area_name'
            ),
            'orderby' => 'a.area_name ASC'
        );
        $areas      = $this->entityManager->getRepository('ZSELEX_Entity_Country')->getAll($areaArgs);

        $catArgs    = array(
            'entity' => 'ZSELEX_Entity_Category',
            'fields' => array(
                'a.category_id',
                'a.category_name'
            ),
            'orderby' => 'a.category_name ASC'
        );
        $categories = $this->entityManager->getRepository('ZSELEX_Entity_Country')->getAll($catArgs);

        $branch_Args = array(
            'entity' => 'ZSELEX_Entity_Branch',
            'fields' => array(
                'a.branch_id',
                'a.branch_name'
            ),
            'orderby' => 'a.branch_name ASC'
        );
        $branchs     = $this->entityManager->getRepository('ZSELEX_Entity_Country')->getAll($branch_Args);

        $affiliates = $this->entityManager->getRepository('ZSELEX_Entity_Country')->getAll(array(
            'entity' => 'ZSELEX_Entity_ShopAffiliation',
            'fields' => array(
                'a.aff_id',
                'a.aff_name'
            ),
            'orderby' => 'a.aff_name ASC'
        ));

        $this->view->assign('countries', $countries);
        $this->view->assign('regions', $regions);
        $this->view->assign('cities', $cities);
        $this->view->assign('areas', $areas);
        $this->view->assign('categories', $categories);
        $this->view->assign('affiliates', $affiliates);
        $shopDesign = $item ['theme'];
        $this->view->assign('zecommerce', $aSelectArray ['ecommerce']);
        $this->view->assign('users', $users);
        $this->view->assign('loguser', $loguser);
        $this->view->assign('zbranch', $branchs);
        $this->view->assign('item', $item);
        $this->view->assign('shopuser', $shopusers);
        $this->view->assign('puser', $usr);
        $this->view->assign('plugin', $plugins);

        $this->view->assign('shop', $shop_id);

        $this->view->assign('servicesPurchased', $servicePurchased);
        $this->view->assign('serviceCount', $servicePurchasedCount);

        $this->view->assign('imageQuantity', $imgqty);

        $this->view->assign('shpImgs', $shopImages);

        $this->view->assign('designs', $designs);

        $this->view->assign('shopDesign', $shopDesign);

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            // return $this->view->fetch('admin/createshop_user.tpl');
            return $this->view->fetch('admin/modifyshop.tpl');
        } else {
            // return $this->view->fetch('admin/createshop.tpl');

            return $this->view->fetch('admin/modifyshop.tpl');
        }
    }

    /**
     * display all services to buy or for demo.
     *
     * @author
     *
     * @param
     *        	'shop_id' - each service would be added to cart from here for a particular shop
     *        	checks whether to display 'demo' and 'add to cart' links based on the configurations of each services with shop
     * @return s array
     */
    public function purchaseservices1($args)
    {

        // echo "<pre>"; print_r($_COOKIE['admincart']); echo "</pre>";
        // echo "<pre>"; print_r($_SESSION['admincart']); echo "</pre>";
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());

        $shop_id  = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $owner    = DBUtil::selectObjectByID('zselex_shop_owners', $shop_id,
                'shop_id');
        $owner_id = $owner ['user_id'];

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $user_id      = UserUtil::getVar('uid');
        $itemsperpage = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 25,
                'GETPOST');
        $startnum     = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $this->view->assign('startnum', $startnum);
        $this->view->assign('itemsperpage', $itemsperpage);

        $items = ModUtil::apiFunc('ZSELEX', 'admin', 'getServiceList',
                $args  = array(
                'shop_id' => $shop_id,
                'start' => $startnum,
                'itemsperpage' => $itemsperpage
        ));

        $plugins = $items ['items'];
        $count   = $items ['count'];
        $this->view->assign('total_count', $count);

        // $pluginargs = array('table' => 'zselex_plugin', 'where' => "status=1", 'orderBy' => '', 'useJoins' => '');
        // $plugins = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements', $pluginargs);

        foreach ($plugins as $key => $val) {
            if ($val ['top_bundle'] == 1) {
                $plugins [$key] ['bundlebuy'] = ModUtil::apiFunc('ZSELEX',
                        'admin', 'canBuyStatusBundle',
                        $args                         = array(
                        'shop_id' => $shop_id,
                        'bundleId' => $val ['bundle_id']
                ));
                // echo "<pre>"; print_r($plugins[$key]['bundlebuy']); echo "</pre>";
                $plugins [$key] ['cantbuy']   = $plugins [$key] ['bundlebuy'] ['cantbuy'];
                $plugins [$key] ['msg']       = $plugins [$key] ['bundlebuy'] ['msg'];
            } else {
                if ($val ['service_depended'] == 1 || $val ['shop_depended'] == 1) {
                    $plugins [$key] ['buy']     = ModUtil::apiFunc('ZSELEX',
                            'admin', 'canBuyStatus',
                            $args                       = array(
                            'depended_services' => $val ['depended_services'],
                            'type' => $val ['type'],
                            'shop_id' => $shop_id,
                            'shop_depended' => $val ['shop_depended'],
                            'owner_id' => $owner_id
                    ));
                    $plugins [$key] ['cantbuy'] = $plugins [$key] ['buy'] ['cantbuy'];
                    $plugins [$key] ['msg']     = $plugins [$key] ['buy'] ['msg'];
                    // echo $plugins[$key]['cantbuy'] . '<br>';
                    // echo "<pre>"; print_r($plugins[$key]['buy']); echo "</pre>";
                } else {
                    $plugins [$key] ['cantbuy'] = '0';
                }
            }
            // echo $val['top_bundle'] . '<br>';
            // echo $val['bundle_id'] . '<br>';

            if ($val ['bundle'] == 1) {
                $plugins [$key] ['bundleitems'] = ModUtil::apiFunc('ZSELEX',
                        'user', 'selectArray',
                        $args                           = array(
                        'table' => 'zselex_service_bundle_items',
                        'where' => array(
                            'bundle_id='.$val ['bundle_id']
                        )
                ));
            }
            // echo $val['content'] . '<br>';
            $plugins [$key] ['contents'] = unserialize($val ['content']);
            $serviceType                 = $val ['type'];
            $democount                   = ModUtil::apiFunc('ZSELEX', 'admin',
                    'getCount',
                    $args                        = array(
                    'table' => 'zselex_service_demo',
                    'where' => "user_id=$user_id AND shop_id=$shop_id AND type='".$serviceType."'"
            ));
            if ($democount > 0) { // already used/in demo
                $demodays = ModUtil::apiFunc('ZSELEX', 'admin', 'demoDateCheck',
                        $args     = array(
                        'type' => $val [type],
                        'plugin_id' => $val [plugin_id],
                        'user_id' => $user_id,
                        'shop_id' => $shop_id,
                        'demo' => self::DEMO
                ));

                if ($demodays ['demo'] == 0) {
                    $plugins [$key] ['demo_status'] = 0;
                } else {
                    $plugins [$key] ['demo_status'] = 1;
                }
            } else {
                $plugins [$key] ['demo_status'] = 1;
            }

            if ($val ['qty_based'] == 0) {
                $qty_based_exist                    = ModUtil::apiFunc('ZSELEX',
                        'admin', 'getCount',
                        $args                               = array(
                        'table' => 'zselex_serviceshop',
                        'where' => "user_id=$user_id AND shop_id=$shop_id AND type='".$serviceType."' AND service_status='2'"
                ));
                $plugins [$key] ['qty_based_exist'] = $qty_based_exist;
                if ($plugins [$key] ['qty_based_exist'] > 0) {
                    // unset($plugins[$key]);
                    $plugins [$key] ['disabled'] = 1;
                } else {
                    $plugins [$key] ['disabled'] = 0;
                }
            } else {
                $qty_not_based_exist = ModUtil::apiFunc('ZSELEX', 'admin',
                        'getCount',
                        $args                = array(
                        'table' => 'zselex_serviceshop',
                        'where' => "user_id=$user_id AND shop_id=$shop_id AND type='".$serviceType."' AND service_status='2' AND quantity>availed"
                ));

                $plugins [$key] ['qty_not_based_exist'] = $qty_not_based_exist;
                if ($plugins [$key] ['qty_not_based_exist'] > 0) {
                    $plugins [$key] ['disabledemo'] = 1;
                } else {
                    $plugins [$key] ['disabledemo'] = 0;
                }
                $plugins [$key] ['disabled'] = 0;
            }

            // echo $plugins[$key]['demo_status'] .'<b>';

            $plugins [$key] ['democount'] = $democount;

            $plugins [$key] ['servicePurchasedCount'] = ModUtil::apiFunc('ZSELEX',
                    'admin', 'getServicePurchasedQuantity',
                    $args                                     = array(
                    'shop_id' => $shop_id,
                    'type' => $serviceType
            ));
        }

        $thislang = ZLanguage::getLanguageCode();

        // echo "<pre>"; print_r($plugins); echo "</pre>";

        $this->view->assign('thislang', $thislang);
        $this->view->assign('plugin', $plugins);

        return $this->view->fetch('admin/purchaseservices.tpl');
    }

    public function purchaseservices($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        $shop_id  = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $owner    = DBUtil::selectObjectByID('zselex_shop_owners', $shop_id,
                'shop_id');
        $owner_id = $owner ['user_id'];

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $user_id      = UserUtil::getVar('uid');
        $itemsperpage = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 25,
                'GETPOST');
        $startnum     = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $this->view->assign('startnum', $startnum);
        $this->view->assign('itemsperpage', $itemsperpage);

        $items = ModUtil::apiFunc('ZSELEX', 'admin', 'getServiceList',
                $args  = array(
                'shop_id' => $shop_id,
                'start' => $startnum,
                'itemsperpage' => $itemsperpage
        ));

        $plugins = $items ['items'];
        $count   = $items ['count'];
        $this->view->assign('total_count', $count);

        // $pluginargs = array('table' => 'zselex_plugin', 'where' => "status=1", 'orderBy' => '', 'useJoins' => '');
        // $plugins = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements', $pluginargs);

        foreach ($plugins as $key => $val) {
            // //////////////////////DEPENDECY CHECK//////////////////////////////////
            if ($val ['top_bundle'] == 1) { // for bundles followed by its items
                $plugins [$key] ['bundlebuy'] = ModUtil::apiFunc('ZSELEX',
                        'admin', 'canBuyStatusBundle',
                        $args                         = array(
                        'shop_id' => $shop_id,
                        'bundleId' => $val ['bundle_id']
                ));
                // echo "<pre>"; print_r($plugins[$key]['bundlebuy']); echo "</pre>";
                $plugins [$key] ['cantbuy']   = $plugins [$key] ['bundlebuy'] ['cantbuy'];
                $plugins [$key] ['msg']       = $plugins [$key] ['bundlebuy'] ['msg'];
            } else { // for normal services
                if ($val ['service_depended'] == 1 || $val ['shop_depended'] == 1) {
                    $plugins [$key] ['buy']     = ModUtil::apiFunc('ZSELEX',
                            'admin', 'canBuyStatus',
                            $args                       = array(
                            'depended_services' => $val ['depended_services'],
                            'type' => $val ['type'],
                            'shop_id' => $shop_id,
                            'shop_depended' => $val ['shop_depended'],
                            'service_depended' => $val ['service_depended'],
                            'owner_id' => $owner_id
                    ));
                    $plugins [$key] ['cantbuy'] = $plugins [$key] ['buy'] ['cantbuy'];
                    $plugins [$key] ['msg']     = $plugins [$key] ['buy'] ['msg'];
                    // echo $plugins[$key]['cantbuy'] . '<br>';
                    // echo "<pre>"; print_r($plugins[$key]['buy']); echo "</pre>";
                } else {
                    $plugins [$key] ['cantbuy'] = '0';
                }
            }
            // echo "<pre>"; print_r($plugins); echo "</pre>";
            // //////////////////////////DEPENDECY CHECK ENDS////////////////////////////

            if ($val ['bundle'] == 1) {
                $plugins [$key] ['bundleitems'] = ModUtil::apiFunc('ZSELEX',
                        'user', 'selectArray',
                        $args                           = array(
                        'table' => 'zselex_service_bundle_items',
                        'where' => array(
                            'bundle_id='.$val ['bundle_id']
                        )
                ));
            }
            $serviceType = $val ['type'];

            // check if its used as demo ever
            $democount = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount',
                    $args      = array(
                    'table' => 'zselex_service_demo',
                    'where' => "shop_id=$shop_id AND type='".$serviceType."'"
            ));
            // echo $val[type] . '<br>';
            // echo "<pre>"; print_r($demodays); echo "</pre>";
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
                    // echo "<pre>"; print_r($demodays); echo "</pre>";
                    if ($demodays ['demo'] == 0) { // out of demo
                        $plugins [$key] ['demo_status'] = 0;
                    } else { // running as demo
                        $plugins [$key] ['demo_status'] = 1;
                    }
                } else { // if not quantity based then once used as demo then its demo session is over.
                    $plugins [$key] ['demo_status'] = 0;
                }
            } else { // never used as demo
                $plugins [$key] ['demo_status'] = 1;
            }

            // service purchased count//
            $plugins [$key] ['servicePurchasedCount'] = ModUtil::apiFunc('ZSELEX',
                    'admin', 'getServicePurchasedQuantity',
                    $args                                     = array(
                    'shop_id' => $shop_id,
                    'type' => $serviceType
            ));
        }

        $thislang = ZLanguage::getLanguageCode();

        // echo "<pre>"; print_r($plugins); echo "</pre>";

        $this->view->assign('thislang', $thislang);
        $this->view->assign('plugin', $plugins);

        return $this->view->fetch('admin/purchaseservices.tpl');
    }

    /**
     * display configured services for a shop.
     *
     * @author
     *
     */
    public function configuredServices_old($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        $shop_id    = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $bulkaction = (int) FormUtil::getPassedValue('news_bulkaction_select',
                0, 'POST');

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $sids = FormUtil::getPassedValue('news_selected_articles', array(),
                'POST');

        if ($bulkaction >= 1 && $bulkaction <= 5) {
            // echo "exit;"; e xit;
            // echo "<pre>";print_r($sids); echo "</pre>"; exit;
            $actionmap    = array(
                // these indices are not constants, just unrelated integers
                1 => __('Delete'),
                2 => __('Reactivate Demo'),
                3 => __('Publish'),
                4 => __('Reject'),
                5 => __('Change categories')
            );
            $updateresult = array(
                'successful' => array(),
                'failed' => array()
            );

            switch ($bulkaction) {

                case 1 : // delete
                    // echo "comes hereee";
                    foreach ($sids as $sid) {
                        if (DBUtil::deleteObjectByID('zselex_serviceshop', $sid,
                                'id')) {
                            // assume max pictures. if less, errors are supressed by @
                            $updateresult ['successful'] [] = $sid;
                        } else {
                            $updateresult ['failed'] [] = $sid;
                        }
                    }
                    break;

                case 2 : // REACTIVATE DEMO
                    // echo "comes hereee";
                    $current_date = date('Y-m-d');
                    foreach ($sids as $sid) {
                        // echo $sid . '<br>';
                        $joinInfo []        = array(
                            'join_table' => 'zselex_plugin',
                            'join_field' => array(
                                'plugin_id',
                                'demo',
                                'demoperiod',
                                'qty_based'
                            ),
                            'object_field_name' => array(
                                'plugin_id',
                                'demo',
                                'demoperiod',
                                'qty_based_new'
                            ),
                            'compare_field_table' => 'plugin_id', // main table id
                            'compare_field_join' => 'plugin_id'
                        );
                        $getExistingService = ModUtil::apiFunc('ZSELEX', 'user',
                                'getByJoin',
                                $getargs            = array(
                                'table' => 'zselex_serviceshop',
                                'joinInfo' => $joinInfo,
                                'where' => "id=$sid"
                        ));
                        // echo "<pre>";print_r($getExistingService); echo "</pre>";

                        $obj        = array(
                            'timer_date' => $current_date,
                            'timer_days' => $getExistingService ['demoperiod'],
                            'qty_based' => $getExistingService ['qty_based_new'],
                            'service_status' => 1
                        );
                        $where      = "WHERE id=$sid";
                        DBUTil::updateObject($obj, 'zselex_serviceshop', $where);
                        $obj_demo   = array(
                            'start_date' => $current_date,
                            'timer_days' => $getExistingService ['demoperiod']
                        );
                        $where_demo = "WHERE shop_id=$getExistingService[shop_id] AND type='$getExistingService[type]'";
                        DBUTil::updateObject($obj_demo, 'zselex_service_demo',
                            $where_demo);
                    }
                    break;
            }
            $updateresult ['successful'] ['list'] = implode(', ',
                $updateresult ['successful']);
            $updateresult ['failed'] ['list']     = implode(', ',
                $updateresult ['failed']);

            LogUtil::registerStatus($this->__f('Processed Bulk Action. Action: %s.',
                    $actionmap [$bulkaction]));
        }

        // echo "<pre>"; print_r($_POST); echo "</pre>";

        $servicePurchased = ModUtil::apiFunc('ZSELEX', 'admin',
                'getServicePurchased',
                $args             = array(
                'user_id' => $loguser,
                'shop_id' => $shop_id,
                'status' => '1'
        ));

        foreach ($servicePurchased as $key => $val) {
            if ($val ['bundle'] == 1 && $val ['top_bundle'] == 1) { // avoid bundle items
                unset($servicePurchased [$key]);
            }
        }

        $servicePurchasedCount = ModUtil::apiFunc('ZSELEX', 'admin',
                'getServicePurchasedCount',
                $args                  = array(
                'user_id' => $loguser,
                'shop_id' => $shop_id
        ));

        $this->view->assign('servicesPurchased', $servicePurchased);
        // $this->view->assign('servicesPurchased', $servicePurchased);
        return $this->view->fetch('admin/configuredservices.tpl');
    }

    /**
     * display configured services for a shop.
     *
     * @author
     *
     */
    public function configuredServices($args)
    {
        // error_reporting(E_ALL);
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        $shop_id         = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $bulkaction      = (int) FormUtil::getPassedValue('news_bulkaction_select',
                0, 'POST');
        $reactivate_demo = FormUtil::getPassedValue('reactivate_demo', 0, 'POST');
        // echo "<pre>"; print_r($_POST); echo "</pre>";

        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)));
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        // $this->payMentAlert($shop_id);
        $sids = FormUtil::getPassedValue('news_selected_articles', array(),
                'POST');

        if ($bulkaction >= 1 && $bulkaction <= 5) {
            // echo "exit;"; e xit;
            // echo "<pre>"; print_r($sids); echo "</pre>";
            $actionmap    = array(
                // these indices are not constants, just unrelated integers
                1 => __('Delete'),
                2 => __('Reactivate Demo'),
                3 => __('Publish'),
                4 => __('Reject'),
                5 => __('Change categories')
            );
            $updateresult = array(
                'successful' => array(),
                'failed' => array()
            );

            switch ($bulkaction) {

                case 1 : // delete
                    // echo "comes hereee";
                    foreach ($sids as $sid) {
                        if (DBUtil::deleteObjectByID('zselex_serviceshop_bundles',
                                $sid, 'service_bundle_id')) {
                            // assume max pictures. if less, errors are supressed by @
                            $updateresult ['successful'] [] = $sid;
                        } else {
                            $updateresult ['failed'] [] = $sid;
                        }
                    }
                    break;

                case 2 : // REACTIVATE DEMO
                    // echo "comes hereee";
                    $current_date = date('Y-m-d');
                    foreach ($sids as $sid) {
                        // echo $sid . '<br>';
                    }
                    break;
            }
            $updateresult ['successful'] ['list'] = implode(', ',
                $updateresult ['successful']);
            $updateresult ['failed'] ['list']     = implode(', ',
                $updateresult ['failed']);

            LogUtil::registerStatus($this->__f('Processed Bulk Action. Action: %s.',
                    $actionmap [$bulkaction]));
        }

        if ($reactivate_demo) {
            // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
            $indexes     = FormUtil::getPassedValue('index', array(), 'POST');
            $demo_period = FormUtil::getPassedValue('demo_period', array(),
                    'POST');
            // echo "<pre>"; print_r($demo_period); echo "</pre>"; exit;
            $sid         = FormUtil::getPassedValue('sid', array(), 'POST');
            // echo "<pre>"; print_r($sid); echo "</pre>"; exit;
            $bundle_id   = FormUtil::getPassedValue('bundle_id', array(), 'POST');
            $shop_id     = FormUtil::getPassedValue('shop_id', 0, 'REQUEST');
            // echo "<pre>"; print_r($indexes); echo "</pre>"; exit;
            /*
             * foreach ($indexes as $k => $v) {
             * $update_demo = $this->entityManager->getRepository('ZSELEX_Entity_Bundle')->updateDemo(array('sid' => $sid[$v], 'demo_period' => $demo_period[$v], 'bundle_id' => $bundle_id[$v], 'shop_id' => $shop_id));
             * }
             */
            $update_demo = $this->entityManager->getRepository('ZSELEX_Entity_Bundle')->reactivateDemoFromShopListing(array(
                'shop_id' => $shop_id,
                'days' => $demo_period [0]
            ));
            if ($update_demo) {
                LogUtil::registerStatus($this->__('Successfully reactivated demo'));
            }
        }

        // $article = $this->entityManager->getRepository('ZSELEX_Entity_ServiceBundle')->findOneBy(array('service_bundle_id' => 22))->getOldArray();
        /*
         * try {
         *
         * //$article = $this->entityManager->getRepository('ZSELEX_Entity_ServiceBundle')->findOneBy(array('service_bundle_id' => 22));
         * $article = $this->entityManager->getRepository('ZSELEX_Entity_ServiceBundle')->findOneBy(array('bundle_id' => 22));
         *
         * echo "<pre>"; print_r($article); echo "</pre>";
         * } catch (Exception $e) {
         * echo "<pre>"; print_r($e); echo "</pre>"; exit;
         * }
         */
        // echo "<pre>"; print_r($_POST); echo "</pre>";
        // $article = $this->entityManager->getRepository('ZSELEX_Entity_ServiceBundle')->findBy(array('service_bundle_id'=>20));
        // echo "<pre>"; print_r($article); echo "</pre>";
        $repository       = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $bundlesPurchased = $repository->bundlesPurchased(array(
            'shop_id' => $shop_id
        )); // ///
        // $test = (array) $this->entityManager->getRepository('ZSELEX_Entity_Bundle')->find('2');

        /*
         * $servicePurchased = ModUtil::apiFunc('ZSELEX', 'admin', 'getServicePurchased', $args = array(
         * 'user_id' => $loguser,
         * 'shop_id' => $shop_id,
         * 'status' => '1'
         * ));
         */
        $servicePurchased      = $bundlesPurchased;
        $servicePurchasedCount = ModUtil::apiFunc('ZSELEX', 'admin',
                'getServicePurchasedCount',
                $args                  = array(
                'user_id' => $loguser,
                'shop_id' => $shop_id
        ));

        $this->view->assign('servicesPurchased', $servicePurchased);
        // $this->view->assign('servicesPurchased', $servicePurchased);
        return $this->view->fetch('admin/configuredservices.tpl');
    }

    public function deleteShopService1($args)
    {
        $id           = FormUtil::getPassedValue('sid',
                isset($args ['sid']) ? $args ['sid'] : 0, 'REQUEST');
        $shop_id      = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : 0, 'REQUEST');
        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
        if (empty($confirmation)) {
            // Add ZSELEX type ID
            $this->view->assign('IdValue', $id);
            $this->view->assign('confirm_title',
                $this->__f('Delete %s', $this->__('Minisite Service')));
            $this->view->assign('confirm_msg',
                $this->__f('Do you want to delete this %s',
                    $this->__('Minisite Service')));
            $this->view->assign('shop_id', $shop_id);
            $this->view->assign('IdName', 'sid'); // edit id param name
            $this->view->assign('submitFunc', 'deleteShopService');
            $this->view->assign('cancelFunc', 'configuredServices');

            // Return the output that has been generated by this function
            return $this->view->fetch('admin/deletecommon.tpl');
        }
        $where = "id=$id AND shop_id=$shop_id";
        // echo $where; exit;
        if (DBUtil::deleteWhere('zselex_serviceshop', $where)) {
            LogUtil::registerStatus($this->__('Done! Deleted successfully.'));

            return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                        'configuredServices',
                        array(
                        'shop_id' => $shop_id
            )));
        }
    }

    public function deleteShopService($args)
    {
        $id           = FormUtil::getPassedValue('sid',
                isset($args ['sid']) ? $args ['sid'] : 0, 'REQUEST');
        $shop_id      = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : 0, 'REQUEST');
        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
        $servicecheck = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceOverCheck',
                $args         = array(
                'id' => $id
        ));
        if (empty($confirmation)) {
            // Add ZSELEX type ID

            $this->view->assign('info', $servicecheck);
            $this->view->assign('IdValue', $id);
            $this->view->assign('confirm_title',
                __f('Delete %s', __('Minisite Service')));
            $this->view->assign('confirm_msg',
                __f('Do you want to delete this %s', __('Minisite Service')));
            $this->view->assign('shop_id', $shop_id);
            $this->view->assign('IdName', 'sid'); // edit id param name
            $this->view->assign('submitFunc', 'deleteShopService');
            $this->view->assign('cancelFunc', 'configuredServices');

            // Return the output that has been generated by this function
            return $this->view->fetch('admin/confirm_delete_configured_service.tpl');
        }
        // echo $servicecheck['action']; exit;
        // echo "<pre>"; print_r($servicecheck); echo "</pre>"; exit;
        if ($servicecheck ['action'] == 'delete') {
            // ////////////////////////////////////////////////////////////////////////////////////
            $where = "id=$id AND shop_id=$shop_id";
            // echo $where; exit;
            if (DBUtil::deleteWhere('zselex_serviceshop', $where)) {
                if ($servicecheck ['top_bundle'] == 1) {
                    $where_bundle_items = "bundle_id=$servicecheck[bundle_id] AND shop_id=$shop_id";
                    DBUtil::deleteWhere('zselex_serviceshop',
                        $where_bundle_items);
                }
                LogUtil::registerStatus($this->__('Done! Deleted successfully.'));

                return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                            'configuredServices',
                            array(
                            'shop_id' => $shop_id
                )));
            }

            // ///////////////////////////////////////////////////////////////////////////////////////
        } elseif ($servicecheck ['action'] == 'inactive') {
            $obj      = array(
                'status' => '0'
            );
            $pntables = pnDBGetTables();
            $column   = $pntables ['zselex_serviceshop_column'];
            $where    = "WHERE $column[id]=$id";
            DBUTil::updateObject($obj, 'zselex_serviceshop', $where);
            if ($servicecheck ['top_bundle'] == 1) {
                $where_bundle_items = "bundle_id=$servicecheck[bundle_id] AND shop_id=$shop_id";
                DBUTil::updateObject($obj, 'zselex_serviceshop',
                    $where_bundle_items);
            }

            return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                        'configuredServices',
                        array(
                        'shop_id' => $shop_id
            )));
        }
    }

    /**
     * List all shops in the system
     *
     * @param array $args - search criterias
     * @return array of shops
     */
    public function viewshop($args)
    { // List all shops with search criteries
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());

        $shopRepo = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        $sort     = array();
        $fields   = array(
            'shop_id',
            'shop_name',
            'telephone',
            'address',
            'email',
            'uname',
            'country_name',
            'region_name',
            'cityName',
            'area_name',
            'categoryName',
            'branch_name',
            'status',
            'cr_date',
            'lu_date'
        ); // possible sort fields

        $shopfields = array(
            'shop_id',
            'shop_name',
            'telephone',
            'address',
            'email',
            'status',
            'cr_date',
            'lu_date'
        ); // possible so

        $clear = FormUtil::getPassedValue('clear', null, 'REQUEST');

        if ($clear) {
            setcookie('status', '', time() - 604800);
            setcookie('searchtext', '', time() - 604800);
            setcookie('telephone', '', time() - 604800);
            setcookie('email', '', time() - 604800);
            setcookie('owner', '', time() - 604800);
            setcookie('address', '', time() - 604800);
            setcookie('order', '', time() - 604800);
            setcookie('original_sdir', '', time() - 604800);
            setcookie('country', '', time() - 604800);
            setcookie('region', '', time() - 604800);
            setcookie('city', '', time() - 604800);
            setcookie('area', '', time() - 604800);
            setcookie('category', '', time() - 604800);
            setcookie('branch', '', time() - 604800);
            setcookie('affiliate', '', time() - 604800);
            setcookie('itemsperpage', '', time() - 604800);
            setcookie('bundle', '', time() - 604800);
            $_COOKIE ['status']        = '';
            $_COOKIE ['searchtext']    = '';
            $_COOKIE ['telephone']     = '';
            $_COOKIE ['email']         = '';
            $_COOKIE ['owner']         = '';
            $_COOKIE ['address']       = '';
            $_COOKIE ['order']         = '';
            $_COOKIE ['original_sdir'] = '';
            $_COOKIE ['country']       = '';
            $_COOKIE ['region']        = '';
            $_COOKIE ['city']          = '';
            $_COOKIE ['area']          = '';
            $_COOKIE ['category']      = '';
            $_COOKIE ['branch']        = '';
            $_COOKIE ['affiliate']     = '';
            $_COOKIE ['itemsperpage']  = '';
            $_COOKIE ['bundle']        = '';
            // $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshop'));
            // echo "Comes here...";
            // echo "search cookie : " . $_COOKIE['searchtext'];
        }

        $shop_ids = FormUtil::getPassedValue('shop_ids', null, 'POST');
        // echo "<pre>"; print_r($shop_ids); echo "</pre>";exit;
        if (isset($shop_ids)) {
            // echo "<pre>"; print_r($shop_ids); echo "</pre>";
            // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
            $chg_cat     = FormUtil::getPassedValue('chg_cat', null, 'POST');
            $chg_aff     = FormUtil::getPassedValue('chg_aff', null, 'POST');
            $chg_stat    = FormUtil::getPassedValue('chg_stat', null, 'POST');
            $chg_del     = FormUtil::getPassedValue('chg_del', null, 'POST');
            $rdemo       = FormUtil::getPassedValue('chg_demo', null, 'POST');
            $chg_brnch   = FormUtil::getPassedValue('chg_brnch', null, 'POST');
            $chgGroup    = FormUtil::getPassedValue('chg_group', null, 'POST');
            $select_type = FormUtil::getPassedValue('select_type', null, 'POST');

            //echo $select_type; exit;
            // echo "<pre>"; print_r($chg_cat); echo "</pre>"; exit;
            //echo $chgGroup; exit;
            if (isset($chg_cat) || isset($chg_aff) || isset($chg_stat) || isset($chg_brnch)
                || isset($chgGroup)) {

                $shopIdsJson = json_encode($shop_ids);
                //echo $shopIdsJson; exit;
                $baseUrl     = pnGetBaseURL();
                $params      = "shopJson=$shopIdsJson chg_cat=$chg_cat chg_aff=$chg_aff chg_stat=$chg_stat chg_brnch=$chg_brnch select_type=$select_type baseUrl=$baseUrl chg_group=$chgGroup";
                if ($_SERVER ['SERVER_NAME'] == 'localhost') {
                    $path = $_SERVER ['DOCUMENT_ROOT']."/zselex/scripts/update_shop_selection.php ".$params;
                } else {
                    $path = $_SERVER ['DOCUMENT_ROOT']."/scripts/update_shop_selection.php ".$params;
                }
                $cmd = "/usr/bin/php -c php.ini ".$path;
                // echo $cmd; exit;
                ZSELEX_Util::execInBackground($cmd);

                if (!$err) {
                    LogUtil::registerStatus($this->__("Update script has started at background"));
                }
            } elseif ($chg_del) {
                foreach ($shop_ids as $sid) {
                    // echo $sid . '<br>';
                    if (!$this->deleteshops(array(
                            'shop_id' => $sid
                        ))) {
                        $delerr = 1;
                    }
                }
                if (!$delerr) {
                    LogUtil::registerStatus($this->__('Done! shop(s) deleted!'));
                }
            } elseif ($rdemo) {
                // echo "helloooo"; exit;
                // echo "<pre>"; print_r($shop_ids); echo "</pre>";exit;
                foreach ($shop_ids as $sid) {
                    $this->entityManager->getRepository('ZSELEX_Entity_Bundle')->reactivateDemoFromShopListing(array(
                        'shop_id' => $sid
                    ));
                }
            }
        }

        //echo "comes here"; exit;
        $JOINS        = '';
        $itemsperpage = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : null,
                'GETPOST');
        $startnum     = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : 0, 'GETPOST');
        $status       = FormUtil::getPassedValue('status',
                isset($args ['status']) ? $args ['status'] : null, 'GETPOST');

        if (isset($itemsperpage)) {
            // echo "Comes here..";
            setcookie('itemsperpage', $itemsperpage, time() + 604800);
        }
        $itemsperpage = (isset($itemsperpage)) ? $itemsperpage : $_COOKIE ['itemsperpage'];
        // echo "itemsperpage :" . $itemsperpage;
        if (!$itemsperpage) {
            $itemsperpage = 20;
        }

        if (isset($status)) {
            setcookie('status', $status, time() + 604800);
        }
        $status = (isset($status)) ? $status : $_COOKIE ['status'];
        // $order = FormUtil::getPassedValue('order', isset($args['order']) ? $args['order'] : 'shop_id', 'GETPOST');
        $order  = FormUtil::getPassedValue('order',
                isset($args ['order']) ? $args ['order'] : null, 'GETPOST');
        if (isset($order)) {
            setcookie('order', $order, time() + 604800);
        }
        $order = (isset($order)) ? $order : $_COOKIE ['order'];

        // $original_sdir = FormUtil::getPassedValue('sdir', isset($args['sdir']) ? $args['sdir'] : 1, 'GETPOST');
        $original_sdir = FormUtil::getPassedValue('sdir',
                isset($args ['sdir']) ? $args ['sdir'] : null, 'GETPOST');
        if (isset($original_sdir)) {
            setcookie('original_sdir', $original_sdir, time() + 604800);
        }
        $original_sdir = (isset($original_sdir)) ? $original_sdir : $_COOKIE ['original_sdir'];
        $searchtext    = '';
        $searchtext    = FormUtil::getPassedValue('searchtext',
                isset($args ['searchtext']) ? $args ['searchtext'] : null,
                'GETPOST');

        if ($searchtext != '') {
            setcookie('searchtext', $searchtext, time() + 604800);
            // echo "not null <br>";
        }
        // $searchtext = (isset($_COOKIE['searchtext'])) ? $_COOKIE['searchtext'] : $searchtext;
        $searchtext = (isset($searchtext)) ? $searchtext : $_COOKIE ['searchtext'];

        // echo "search : " . $searchtext;
        $telephone = FormUtil::getPassedValue('telephone',
                isset($args ['telephone']) ? $args ['telephone'] : null,
                'GETPOST');

        if ($telephone != '') {
            setcookie('telephone', $telephone, time() + 604800);
            // echo "not null <br>";
        }
        $telephone = (isset($telephone)) ? $telephone : $_COOKIE ['telephone'];

        $email = FormUtil::getPassedValue('email',
                isset($args ['email']) ? $args ['email'] : null, 'GETPOST');

        if ($email != '') {
            setcookie('email', $email, time() + 604800);
        }
        $email = (isset($email)) ? $email : $_COOKIE ['email'];

        $owner = FormUtil::getPassedValue('owner',
                isset($args ['owner']) ? $args ['owner'] : null, 'GETPOST');

        if ($owner != '') {
            setcookie('owner', $owner, time() + 604800);
        }
        $owner = (isset($owner)) ? $owner : $_COOKIE ['owner'];

        $adminnames = 'N/A';
        $address    = FormUtil::getPassedValue('address',
                isset($args ['address']) ? $args ['address'] : null, 'GETPOST');

        if ($address != '') {
            setcookie('address', $address, time() + 604800);
        }
        $address = (isset($address)) ? $address : $_COOKIE ['address'];
        $country = FormUtil::getPassedValue('country',
                isset($args ['country']) ? $args ['country'] : null, 'GETPOST');

        if ($country != '') {
            setcookie('country', $country, time() + 604800);
        }
        $country = (isset($country)) ? $country : $_COOKIE ['country'];
        $region  = FormUtil::getPassedValue('region',
                isset($args ['region']) ? $args ['region'] : null, 'GETPOST');

        if (isset($region)) {
            setcookie('region', $region, time() + 604800);
        }
        $region = (isset($region)) ? $region : $_COOKIE ['region'];
        $city   = FormUtil::getPassedValue('city',
                isset($args ['city']) ? $args ['city'] : null, 'GETPOST');
        if (isset($city)) {
            setcookie('city', $city, time() + 604800);
        }
        $city = (isset($city)) ? $city : $_COOKIE ['city'];
        $area = FormUtil::getPassedValue('area',
                isset($args ['area']) ? $args ['area'] : null, 'GETPOST');

        if (isset($area)) {
            setcookie('area', $area, time() + 604800);
        }
        $area     = (isset($area)) ? $area : $_COOKIE ['area'];
        $category = FormUtil::getPassedValue('category',
                isset($args ['category']) ? $args ['category'] : null, 'GETPOST');

        if (isset($category)) {
            setcookie('category', $category, time() + 604800);
        }
        $category = (isset($category)) ? $category : $_COOKIE ['category'];

        if ($category > 0) {
            $JOINS .= " INNER JOIN zselex_shop_to_category sc ON sc.shop_id=s.shop_id AND sc.category_id=$category
                       LEFT JOIN zselex_category cat ON cat.category_id=sc.category_id ";
            // $join_fields = array('cat.category_name');
            $join_fields [] = 'cat.category_name';
        }

        $branch = FormUtil::getPassedValue('branch',
                isset($args ['branch']) ? $args ['branch'] : null, 'GETPOST');

        if (isset($branch)) {
            setcookie('branch', $branch, time() + 604800);
        }
        $branch = (isset($branch)) ? $branch : $_COOKIE ['branch'];

        if ($branch > 0) {
            $JOINS .= " INNER JOIN zselex_shop_to_branch sb ON sb.shop_id=s.shop_id AND sb.branch_id=$branch
                        INNER JOIN zselex_branch brnch ON brnch.branch_id=sb.branch_id ";
            $join_fields [] = 'brnch.branch_name';
        }

        $affiliate = FormUtil::getPassedValue('affiliate',
                isset($args ['affiliate']) ? $args ['affiliate'] : null,
                'GETPOST');

        if (isset($affiliate)) {
            setcookie('affiliate', $affiliate, time() + 604800);
        }
        $affiliate = (isset($affiliate)) ? $affiliate : $_COOKIE ['affiliate'];


        $bundle = FormUtil::getPassedValue('bundle',
                isset($args ['bundle']) ? $args ['bundle'] : null, 'GETPOST');

        if (isset($bundle)) {
            setcookie('bundle', $bundle, time() + 604800);
        }
        $bundle = (isset($bundle)) ? $bundle : $_COOKIE ['bundle'];

        // echo "bundle :".$bundle;

        $admin = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN);
        $this->view->assign('admin', $admin);

        // echo $superAdmin;
        $this->view->assign('searchtext', $searchtext);
        $this->view->assign('telephone', $telephone);
        $this->view->assign('email', $email);
        $this->view->assign('owner', $owner);
        $this->view->assign('adminnames', $adminnames);
        $this->view->assign('address', $address);
        $this->view->assign('countryname', $country);
        $this->view->assign('regionname', $region);
        $this->view->assign('city_name', $city);
        $this->view->assign('areaname', $area);
        $this->view->assign('category_name', $category);
        $this->view->assign('branchname', $branch);
        $this->view->assign('shop_affiliate', $affiliate);
        $this->view->assign('shop_bundle', $bundle);

        $this->view->assign('startnum', $startnum);
        $this->view->assign('itemsperpage', $itemsperpage);
        $this->view->assign('order', $order);
        $this->view->assign('sdir', $original_sdir);

        $aStatus = array(
            'InActive',
            'Active'
        );
        $this->view->assign('aStatus', $aStatus);

        $sdir     = $original_sdir ? 0 : 1; // if true change to false, if false change to true
        // change class for selected 'orderby' field to asc/desc
        $orderdir = 'ASC';
        if ($sdir == 0) {
            $sort ['class'] [$order] = 'z-order-desc';
            // $orderdir = 'DESC';

            $orderdir = 'ASC';
        }
        if ($sdir == 1) {
            $sort ['class'] [$order] = 'z-order-asc';
            // $orderdir = 'ASC';

            $orderdir = 'DESC';
        }

        // complete initialization of sort array, adding urls
        foreach ($fields as $field) {
            $sort ['url'] [$field] = ModUtil::url('ZSELEX', 'admin', 'viewshop',
                    array(
                    'status' => $status,
                    'filtercats_serialized' => serialize($filtercats),
                    'order' => $field,
                    'sdir' => $sdir
            ));
        }
        // echo "<pre>"; print_r($sort); echo "</pre>"; exit;
        $this->view->assign('sort', $sort);

        $this->view->assign('filter_active', $status);

        $countryArgs = array(
            'entity' => 'ZSELEX_Entity_Country',
            'fields' => array(
                'a.country_id',
                'a.country_name'
            ),
            'orderby' => 'a.country_name ASC'
        );
        $countries   = $this->entityManager->getRepository('ZSELEX_Entity_Country')->getAll($countryArgs);

        //   echo "<pre>"; print_r($countries); echo "</pre>"; exit;
        $regionArgs = array(
            'entity' => 'ZSELEX_Entity_Region',
            'fields' => array(
                'a.region_id',
                'a.region_name'
            ),
            'orderby' => 'a.region_name ASC'
        );
        $regions    = $this->entityManager->getRepository('ZSELEX_Entity_Country')->getAll($regionArgs);
        $cityArgs   = array(
            'entity' => 'ZSELEX_Entity_City',
            'fields' => array(
                'a.city_id',
                'a.city_name'
            ),
            'orderby' => 'a.city_name ASC'
        );
        $cities     = $this->entityManager->getRepository('ZSELEX_Entity_Country')->getAll($cityArgs);
        $areaArgs   = array(
            'entity' => 'ZSELEX_Entity_Area',
            'fields' => array(
                'a.area_id',
                'a.area_name'
            ),
            'orderby' => 'a.area_name ASC'
        );
        $areas      = $this->entityManager->getRepository('ZSELEX_Entity_Country')->getAll($areaArgs);

        $catArgs    = array(
            'entity' => 'ZSELEX_Entity_Category',
            'fields' => array(
                'a.category_id',
                'a.category_name'
            ),
            'orderby' => 'a.category_name ASC'
        );
        $categories = $this->entityManager->getRepository('ZSELEX_Entity_Country')->getAll($catArgs);

        $branch_Args = array(
            'entity' => 'ZSELEX_Entity_Branch',
            'fields' => array(
                'a.branch_id',
                'a.branch_name'
            ),
            'orderby' => 'a.branch_name ASC'
        );
        $branches    = $this->entityManager->getRepository('ZSELEX_Entity_Country')->getAll($branch_Args);

        $affiliates = $this->entityManager->getRepository('ZSELEX_Entity_Country')->getAll(array(
            'entity' => 'ZSELEX_Entity_ShopAffiliation',
            'fields' => array(
                'a.aff_id',
                'a.aff_name'
            ),
            'orderby' => 'a.aff_name ASC'
        ));

        $bundles = $shopRepo->getAll(array(
            'entity' => 'ZSELEX_Entity_Bundle',
            'fields' => array(
                'a.bundle_id',
                'a.bundle_name',
                'a.bundle_type',
            ),
            'where' => array('a.bundle_type' => 'main'),
            'orderby' => 'a.sort_order ASC'
        ));
        // echo "<pre>"; print_r($bundles); echo "</pre>";

        $this->view->assign('countries', $countries);
        $this->view->assign('regions', $regions);
        $this->view->assign('cities', $cities);
        $this->view->assign('areas', $areas);
        $this->view->assign('categories', $categories);
        $this->view->assign('branches', $branches);
        $this->view->assign('affiliates', $affiliates);
        $this->view->assign('bundles', $bundles);

        // echo "<pre>"; print_r($countries); echo "</pre>";
        if (!empty($join_fields)) {
            // $join_fields = " , " . implode(',', $join_fields);
        }

        // if (UserUtil::getVar('uid') != '3') {

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            $sql .= " AND s.status=1 AND (s.shop_id IN  (SELECT shop_id FROM zselex_shop_owners WHERE user_id='".UserUtil::getVar('uid')."')
                OR s.shop_id IN (SELECT shop_id FROM zselex_shop_admins WHERE user_id='".UserUtil::getVar('uid')."'))";
        }
        if (isset($status) && $status != '') {
            $sql .= ' AND s.status = '.$status;
        }
        if (isset($searchtext) && $searchtext != '') {
            $sql .= " AND s.shop_name LIKE '%".DataUtil::formatForStore($searchtext)."%'";
        }

        if (isset($telephone) && $telephone != '') {
            $sql .= " AND s.telephone LIKE '%".DataUtil::formatForStore($telephone)."%'";
        }

        if (isset($email) && $email != '') {
            $sql .= " AND s.email LIKE '%".DataUtil::formatForStore($email)."%'";
        }

        if (isset($owner) && $owner != '' && $admin) { // only for super admin
            $sql .= " AND u.uname LIKE '%".DataUtil::formatForStore($owner)."%'";
        }

        if (isset($address) && $address != '') {
            $sql .= " AND s.address LIKE '%".DataUtil::formatForStore($address)."%'";
        }

        if (isset($country) && $country != '') {
            $sql .= " AND country.country_name LIKE '%".DataUtil::formatForStore($country)."%'";
        }
        if (isset($region) && $region != '') {
            $sql .= " AND region.region_name LIKE '%".DataUtil::formatForStore($region)."%'";
        }
        if (isset($city) && $city != '') {
            $sql .= " AND city.city_name LIKE '%".DataUtil::formatForStore($city)."%'";
        }
        if (isset($area) && $area != '') {
            $sql .= " AND area.area_name LIKE '%".DataUtil::formatForStore($area)."%'";
        }
        if (isset($category) && $category != '') {
            // echo "category"; exit;
            // $sql .= " AND cat.category_name LIKE '%" . DataUtil::formatForStore($category) . "%'";
        }
        if (isset($branch) && $branch != '') {
            // $sql .= " AND branch.branch_name LIKE '%" . DataUtil::formatForStore($branch) . "%'";
        }

        if (isset($affiliate) && $affiliate > 0) {
            $sql .= " AND s.aff_id=$affiliate ";
        }

        if (isset($bundle) && $bundle > 0) {
            $sql .= " AND sb.bundle_id ='".$bundle."' ";
        }

        $alias = '';
        if (in_array($order, $shopfields)) {
            $alias = 's.';
        }

        // $sql .= " GROUP BY s.shop_id ";
        $orderby = '';
        if (isset($order) && $order != '') {
            // $sql .= " ORDER BY $alias" . $order . " " . $orderdir;
            $orderby .= " ORDER BY $alias".$order.' '.$orderdir.' ';
        }
        // echo $sql;
        // $sql .= $sql1;
        // echo $sql;
        $this->view->assign('sql', $sql);
        // Get all zselex stories
        $getArgs = array(
            'sql' => $sql,
            'startnum' => $startnum,
            'numitems' => $itemsperpage
        );
        // $items = ModUtil::apiFunc('ZSELEX', 'admin', 'getAll', $getArgs);

        $shop_args = array(
            'startlimit' => $startnum,
            'itemsperpage' => $itemsperpage,
            'join_fields' => $join_fields,
            'joins' => $JOINS,
            'orderby' => $orderby,
            'sql' => $sql
        );
        $shop_item = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getShopListing($shop_args);

        //   echo "<pre>"; print_r($shop_item); echo "</pre>"; exit;
        // exit;
        $items = $shop_item;

        // echo "<pre>"; print_r($items); echo "</pre>";

        $zselex = ZSELEX_Util::MODULE_INFO('ZSELEX');

        // echo "<pre>"; print_r($zselex); echo "</pre>";

        for ($i = 0; $i < count($items); ++$i) {
            if ($zselex ['version'] > '1.0.5') { // temperory check
                $sc_args                        = array(
                    'shop_id' => $items [$i] ['shop_id']
                );
                $shop_categories                = ModUtil::apiFunc('ZSELEX',
                        'admin', 'getShopCategories', $sc_args);
                $items [$i] ['shop_categories'] = $shop_categories;

                $sb_args                      = array(
                    'shop_id' => $items [$i] ['shop_id']
                );
                $shop_branches                = ModUtil::apiFunc('ZSELEX',
                        'admin', 'getShopBranches', $sb_args);
                $items [$i] ['shop_branches'] = $shop_branches;
            }
        }

        // echo "<pre>"; print_r($items); echo "</pre>";

        $total_shops = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getShopListingCount($shop_args);
        // echo "<pre>"; print_r($total_shops); echo "</pre>";
        // $total_shops = 5;
        // echo "<pre>"; print_r($total_shops); echo "</pre>";
        // echo $total_shops; exit;
        // Set the possible status for later use
        $itemstatus  = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );

        $shopsitems = array();

        foreach ($items as $item) {
            $options = array();

            // $options[] = array('url' => ModUtil::url('ZSELEX', 'admin', 'displayshop', array('shop_id' => $item['shop_id'])),
            // 'image' => '14_layer_visible.png',
            // 'title' => $this->__('View'));

            if (SecurityUtil::checkPermission('ZSELEX::',
                    "{$item['cr_uid']}::{$item['shop_id']}", ACCESS_EDIT)) {
                $options [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'modifyshop',
                        array(
                        'id' => $item ['shop_id']
                    )),
                    'image' => 'xedit.png',
                    'title' => $this->__('Edit')
                );

                if ((SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['shop_id']}", ACCESS_DELETE))
                    || SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['shop_id']}", ACCESS_ADD)) {
                    $options [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin', 'deleteshop',
                            array(
                            'shop_id' => $item ['shop_id']
                        )),
                        'image' => '14_layer_deletelayer.png',
                        'title' => $this->__('Delete')
                    );
                }
            }
            $item ['options'] = $options;

            $item ['infuture'] = DateUtil::getDatetimeDiff_AsField($item ['cr_date'],
                    DateUtil::getDatetime(), 6) < 0;

            $item ['adminnames'] = ModUtil::apiFunc('ZSELEX', 'admin',
                    'getAdmins',
                    $args                = array(
                    'shop_id' => $item ['shop_id']
            ));

            $shopsitems [] = $item;
        }
        // echo "<pre>"; print_r($shopsitems); echo "</pre>";
        // Assign the items to the template
        $this->view->assign('shopsitems', $shopsitems);

        $this->view->assign('total_shops', $total_shops);

        // Assign the current status filter and the possible ones
        $this->view->assign('status', $status);
        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('order', $order);

        $this->view->assign('admins',
            SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN));
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            // KIMENEMARK BEGIN
            $ownername = UserUtil::getVar('uname');
            if ($ownername != '') {
                $fspath = $_SERVER ['DOCUMENT_ROOT'].'/zselexdata/'.$ownername;
                if ($_SERVER ['SERVER_NAME'] == 'localhost') {
                    $fspath = 'zselexdata/'.$ownername;
                }
                $ownerfoldersize = $this->display_size($this->filesize_recursive($fspath));
            } else {
                $ownerfoldersize = '0';
            }
            $diskquota = ModUtil::apiFunc('ZSELEX', 'admin', 'checkDiskquota',
                    $args      = array(
                    'ownername' => $ownername
            ));

            // if ($diskquota['count'] < 1) {
            // LogUtil::registerError($diskquota['message']);
            // } else if ($diskquota['limitover'] < 1) {
            // LogUtil::registerError($diskquota['message']);
            // }
            if ($diskquota ['error'] > 0) {
                LogUtil::registerError($diskquota ['message']);
            }

            // echo "<pre>"; print_r($diskquota); echo "</pre>";
            // $ownerfolderquota = '50MB'; // Sharaz: We have to be able to assign diskquota in ZSELEX for each owner and be able to restrict in the right places.
            $ownerfolderquota = $this->display_size($diskquota ['sizelimit']);

            $this->view->assign('ownerfoldersize', $ownerfoldersize);
            $this->view->assign('ownerfolderquota', $ownerfolderquota);
            // KIMENEMARK END
        }

        $groups = ModUtil::apiFunc('Groups', 'user', 'getAll',
                array(
                'numitems' => 10000
        ));

        $this->view->assign('groups', $groups);

        //echo "<pre>"; print_r($groups); echo "</pre>";
        // Return the output that has been generated by this function
        return $this->view->fetch('admin/viewshop.tpl');
    }

    public function viewshop_bck($args)
    { // List all shops with search criteries
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());

        $shopRepo = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        $sort     = array();
        $fields   = array(
            'shop_id',
            'shop_name',
            'telephone',
            'address',
            'email',
            'uname',
            'country_name',
            'region_name',
            'cityName',
            'area_name',
            'categoryName',
            'branch_name',
            'status',
            'cr_date',
            'lu_date'
        ); // possible sort fields

        $shopfields = array(
            'shop_id',
            'shop_name',
            'telephone',
            'address',
            'email',
            'status',
            'cr_date',
            'lu_date'
        ); // possible so

        $clear = FormUtil::getPassedValue('clear', null, 'REQUEST');

        if ($clear) {
            setcookie('status', '', time() - 604800);
            setcookie('searchtext', '', time() - 604800);
            setcookie('telephone', '', time() - 604800);
            setcookie('email', '', time() - 604800);
            setcookie('owner', '', time() - 604800);
            setcookie('address', '', time() - 604800);
            setcookie('order', '', time() - 604800);
            setcookie('original_sdir', '', time() - 604800);
            setcookie('country', '', time() - 604800);
            setcookie('region', '', time() - 604800);
            setcookie('city', '', time() - 604800);
            setcookie('area', '', time() - 604800);
            setcookie('category', '', time() - 604800);
            setcookie('branch', '', time() - 604800);
            setcookie('affiliate', '', time() - 604800);
            setcookie('itemsperpage', '', time() - 604800);
            $_COOKIE ['status']        = '';
            $_COOKIE ['searchtext']    = '';
            $_COOKIE ['telephone']     = '';
            $_COOKIE ['email']         = '';
            $_COOKIE ['owner']         = '';
            $_COOKIE ['address']       = '';
            $_COOKIE ['order']         = '';
            $_COOKIE ['original_sdir'] = '';
            $_COOKIE ['country']       = '';
            $_COOKIE ['region']        = '';
            $_COOKIE ['city']          = '';
            $_COOKIE ['area']          = '';
            $_COOKIE ['category']      = '';
            $_COOKIE ['branch']        = '';
            $_COOKIE ['affiliate']     = '';
            $_COOKIE ['itemsperpage']  = '';
            // $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshop'));
            // echo "Comes here...";
            // echo "search cookie : " . $_COOKIE['searchtext'];
        }

        $shop_ids = FormUtil::getPassedValue('shop_ids', null, 'POST');
        // echo "<pre>"; print_r($shop_ids); echo "</pre>";exit;
        if (isset($shop_ids)) {
            // echo "<pre>"; print_r($shop_ids); echo "</pre>";
            // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
            $chg_cat     = FormUtil::getPassedValue('chg_cat', null, 'POST');
            $chg_aff     = FormUtil::getPassedValue('chg_aff', null, 'POST');
            $chg_stat    = FormUtil::getPassedValue('chg_stat', null, 'POST');
            $chg_del     = FormUtil::getPassedValue('chg_del', null, 'POST');
            $rdemo       = FormUtil::getPassedValue('chg_demo', null, 'POST');
            $chg_brnch   = FormUtil::getPassedValue('chg_brnch', null, 'POST');
            $select_type = FormUtil::getPassedValue('select_type', null, 'POST');
            if ($chg_cat) {
                $cats []     = $chg_cat;
                $objs        = array(
                    'cat_id' => $chg_cat
                );
                $typ         = 'Category';
                $category_id = $chg_cat;
                $catObj      = $this->entityManager->getRepository('ZSELEX_Entity_Category')->find($category_id);
            } elseif ($chg_aff) {
                $objs = array(
                    'aff_id' => $chg_aff
                );
                $typ  = 'Affiliate';
            } elseif (isset($chg_stat)) {
                $objs = array(
                    'status' => $chg_stat
                );
                $typ  = 'Status';
            } elseif ($chg_brnch) {
                $branches [] = $chg_brnch;
                $objs        = array(
                    'branch' => $chg_brnch
                );
                $typ         = 'Branch';
                $branch_id   = $chg_brnch;
                $branchObj   = $this->entityManager->getRepository('ZSELEX_Entity_Branch')->find($branch_id);
            }
            // echo $chg_brnch; exit;
            if (isset($chg_cat) || isset($chg_aff) || isset($chg_stat) || isset($chg_brnch)) {
                foreach ($shop_ids as $sid) {
                    // echo $sid . '<br>';
                    // $where = "WHERE shop_id='" . $sid . "'";

                    if (isset($chg_cat)) {
                        if ($select_type == 'rm_cat') {
                            $shopObj = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->find($sid);
                            $shopObj->removeCategory($catObj);
                            $this->entityManager->persist($shopObj);
                        } else {
                            $saveCategories = ModUtil::apiFunc('ZSELEX',
                                    'admin', 'saveShopCategories',
                                    array(
                                    'shop_id' => $sid,
                                    'categories' => $cats
                            ));
                        }
                        $update = $saveCategories;
                    } elseif (isset($chg_brnch)) {
                        if ($select_type == 'rm_brnch') {
                            // echo "rm_brnch"; exit;
                            $shopObj = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->find($sid);
                            $shopObj->removeBranch($branchObj);
                            $this->entityManager->persist($shopObj);
                            $update  = true;
                        } else {
                            $saveBranches = ModUtil::apiFunc('ZSELEX', 'admin',
                                    'saveShopBranches',
                                    array(
                                    'shop_id' => $sid,
                                    'branches' => $branches
                            ));
                            $update       = $saveBranches;
                        }
                    } else {
                        $upd_args = array(
                            'entity' => 'ZSELEX_Entity_Shop',
                            'fields' => $objs,
                            'where' => array(
                                'a.shop_id' => $sid
                            )
                        );
                        $update   = $shopRepo->updateEntity($upd_args);
                    }

                    // if (!DBUTil::updateObject($objs, 'zselex_shop', $where)) {
                    if (!$update) {
                        $err = 1;
                    }
                }
                if ($select_type == 'rm_brnch' || $select_type == 'rm_cat') {
                    $this->entityManager->flush();
                }

                if (!$err) {
                    LogUtil::registerStatus($this->__("Done! $typ saved"));
                }
            } elseif ($chg_del) {
                foreach ($shop_ids as $sid) {
                    // echo $sid . '<br>';
                    if (!$this->deleteshops(array(
                            'shop_id' => $sid
                        ))) {
                        $delerr = 1;
                    }
                }
                if (!$delerr) {
                    LogUtil::registerStatus($this->__('Done! shop(s) deleted!'));
                }
            } elseif ($rdemo) {
                // echo "helloooo"; exit;
                // echo "<pre>"; print_r($shop_ids); echo "</pre>";exit;
                foreach ($shop_ids as $sid) {
                    $this->entityManager->getRepository('ZSELEX_Entity_Bundle')->reactivateDemoFromShopListing(array(
                        'shop_id' => $sid
                    ));
                }
            }
        }

        $JOINS        = '';
        $itemsperpage = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : null,
                'GETPOST');
        $startnum     = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : 0, 'GETPOST');
        $status       = FormUtil::getPassedValue('status',
                isset($args ['status']) ? $args ['status'] : null, 'GETPOST');

        if (isset($itemsperpage)) {
            // echo "Comes here..";
            setcookie('itemsperpage', $itemsperpage, time() + 604800);
        }
        $itemsperpage = (isset($itemsperpage)) ? $itemsperpage : $_COOKIE ['itemsperpage'];
        // echo "itemsperpage :" . $itemsperpage;
        if (!$itemsperpage) {
            $itemsperpage = 20;
        }

        if (isset($status)) {
            setcookie('status', $status, time() + 604800);
        }
        $status = (isset($status)) ? $status : $_COOKIE ['status'];
        // $order = FormUtil::getPassedValue('order', isset($args['order']) ? $args['order'] : 'shop_id', 'GETPOST');
        $order  = FormUtil::getPassedValue('order',
                isset($args ['order']) ? $args ['order'] : null, 'GETPOST');
        if (isset($order)) {
            setcookie('order', $order, time() + 604800);
        }
        $order = (isset($order)) ? $order : $_COOKIE ['order'];

        // $original_sdir = FormUtil::getPassedValue('sdir', isset($args['sdir']) ? $args['sdir'] : 1, 'GETPOST');
        $original_sdir = FormUtil::getPassedValue('sdir',
                isset($args ['sdir']) ? $args ['sdir'] : null, 'GETPOST');
        if (isset($original_sdir)) {
            setcookie('original_sdir', $original_sdir, time() + 604800);
        }
        $original_sdir = (isset($original_sdir)) ? $original_sdir : $_COOKIE ['original_sdir'];
        $searchtext    = '';
        $searchtext    = FormUtil::getPassedValue('searchtext',
                isset($args ['searchtext']) ? $args ['searchtext'] : null,
                'GETPOST');

        if ($searchtext != '') {
            setcookie('searchtext', $searchtext, time() + 604800);
            // echo "not null <br>";
        }
        // $searchtext = (isset($_COOKIE['searchtext'])) ? $_COOKIE['searchtext'] : $searchtext;
        $searchtext = (isset($searchtext)) ? $searchtext : $_COOKIE ['searchtext'];

        // echo "search : " . $searchtext;
        $telephone = FormUtil::getPassedValue('telephone',
                isset($args ['telephone']) ? $args ['telephone'] : null,
                'GETPOST');

        if ($telephone != '') {
            setcookie('telephone', $telephone, time() + 604800);
            // echo "not null <br>";
        }
        $telephone = (isset($telephone)) ? $telephone : $_COOKIE ['telephone'];

        $email = FormUtil::getPassedValue('email',
                isset($args ['email']) ? $args ['email'] : null, 'GETPOST');

        if ($email != '') {
            setcookie('email', $email, time() + 604800);
        }
        $email = (isset($email)) ? $email : $_COOKIE ['email'];

        $owner = FormUtil::getPassedValue('owner',
                isset($args ['owner']) ? $args ['owner'] : null, 'GETPOST');

        if ($owner != '') {
            setcookie('owner', $owner, time() + 604800);
        }
        $owner = (isset($owner)) ? $owner : $_COOKIE ['owner'];

        $adminnames = 'N/A';
        $address    = FormUtil::getPassedValue('address',
                isset($args ['address']) ? $args ['address'] : null, 'GETPOST');

        if ($address != '') {
            setcookie('address', $address, time() + 604800);
        }
        $address = (isset($address)) ? $address : $_COOKIE ['address'];
        $country = FormUtil::getPassedValue('country',
                isset($args ['country']) ? $args ['country'] : null, 'GETPOST');

        if ($country != '') {
            setcookie('country', $country, time() + 604800);
        }
        $country = (isset($country)) ? $country : $_COOKIE ['country'];
        $region  = FormUtil::getPassedValue('region',
                isset($args ['region']) ? $args ['region'] : null, 'GETPOST');

        if (isset($region)) {
            setcookie('region', $region, time() + 604800);
        }
        $region = (isset($region)) ? $region : $_COOKIE ['region'];
        $city   = FormUtil::getPassedValue('city',
                isset($args ['city']) ? $args ['city'] : null, 'GETPOST');
        if (isset($city)) {
            setcookie('city', $city, time() + 604800);
        }
        $city = (isset($city)) ? $city : $_COOKIE ['city'];
        $area = FormUtil::getPassedValue('area',
                isset($args ['area']) ? $args ['area'] : null, 'GETPOST');

        if (isset($area)) {
            setcookie('area', $area, time() + 604800);
        }
        $area     = (isset($area)) ? $area : $_COOKIE ['area'];
        $category = FormUtil::getPassedValue('category',
                isset($args ['category']) ? $args ['category'] : null, 'GETPOST');

        if (isset($category)) {
            setcookie('category', $category, time() + 604800);
        }
        $category = (isset($category)) ? $category : $_COOKIE ['category'];

        if ($category > 0) {
            $JOINS .= " INNER JOIN zselex_shop_to_category sc ON sc.shop_id=s.shop_id AND sc.category_id=$category
                       LEFT JOIN zselex_category cat ON cat.category_id=sc.category_id ";
            // $join_fields = array('cat.category_name');
            $join_fields [] = 'cat.category_name';
        }

        $branch = FormUtil::getPassedValue('branch',
                isset($args ['branch']) ? $args ['branch'] : null, 'GETPOST');

        if (isset($branch)) {
            setcookie('branch', $branch, time() + 604800);
        }
        $branch = (isset($branch)) ? $branch : $_COOKIE ['branch'];

        if ($branch > 0) {
            $JOINS .= " INNER JOIN zselex_shop_to_branch sb ON sb.shop_id=s.shop_id AND sb.branch_id=$branch
                        INNER JOIN zselex_branch brnch ON brnch.branch_id=sb.branch_id ";
            $join_fields [] = 'brnch.branch_name';
        }

        $affiliate = FormUtil::getPassedValue('affiliate',
                isset($args ['affiliate']) ? $args ['affiliate'] : null,
                'GETPOST');

        if (isset($affiliate)) {
            setcookie('affiliate', $affiliate, time() + 604800);
        }
        $affiliate = (isset($affiliate)) ? $affiliate : $_COOKIE ['affiliate'];

        $admin = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN);
        $this->view->assign('admin', $admin);

        // echo $superAdmin;
        $this->view->assign('searchtext', $searchtext);
        $this->view->assign('telephone', $telephone);
        $this->view->assign('email', $email);
        $this->view->assign('owner', $owner);
        $this->view->assign('adminnames', $adminnames);
        $this->view->assign('address', $address);
        $this->view->assign('countryname', $country);
        $this->view->assign('regionname', $region);
        $this->view->assign('city_name', $city);
        $this->view->assign('areaname', $area);
        $this->view->assign('category_name', $category);
        $this->view->assign('branchname', $branch);
        $this->view->assign('shop_affiliate', $affiliate);

        $this->view->assign('startnum', $startnum);
        $this->view->assign('itemsperpage', $itemsperpage);
        $this->view->assign('order', $order);
        $this->view->assign('sdir', $original_sdir);

        $aStatus = array(
            'InActive',
            'Active'
        );
        $this->view->assign('aStatus', $aStatus);

        $sdir     = $original_sdir ? 0 : 1; // if true change to false, if false change to true
        // change class for selected 'orderby' field to asc/desc
        $orderdir = 'ASC';
        if ($sdir == 0) {
            $sort ['class'] [$order] = 'z-order-desc';
            // $orderdir = 'DESC';

            $orderdir = 'ASC';
        }
        if ($sdir == 1) {
            $sort ['class'] [$order] = 'z-order-asc';
            // $orderdir = 'ASC';

            $orderdir = 'DESC';
        }

        // complete initialization of sort array, adding urls
        foreach ($fields as $field) {
            $sort ['url'] [$field] = ModUtil::url('ZSELEX', 'admin', 'viewshop',
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

        $countryArgs = array(
            'entity' => 'ZSELEX_Entity_Country',
            'fields' => array(
                'a.country_id',
                'a.country_name'
            ),
            'orderby' => 'a.country_name ASC'
        );
        $countries   = $this->entityManager->getRepository('ZSELEX_Entity_Country')->getAll($countryArgs);

        $regionArgs = array(
            'entity' => 'ZSELEX_Entity_Region',
            'fields' => array(
                'a.region_id',
                'a.region_name'
            ),
            'orderby' => 'a.region_name ASC'
        );
        $regions    = $this->entityManager->getRepository('ZSELEX_Entity_Country')->getAll($regionArgs);
        $cityArgs   = array(
            'entity' => 'ZSELEX_Entity_City',
            'fields' => array(
                'a.city_id',
                'a.city_name'
            ),
            'orderby' => 'a.city_name ASC'
        );
        $cities     = $this->entityManager->getRepository('ZSELEX_Entity_Country')->getAll($cityArgs);
        $areaArgs   = array(
            'entity' => 'ZSELEX_Entity_Area',
            'fields' => array(
                'a.area_id',
                'a.area_name'
            ),
            'orderby' => 'a.area_name ASC'
        );
        $areas      = $this->entityManager->getRepository('ZSELEX_Entity_Country')->getAll($areaArgs);

        $catArgs    = array(
            'entity' => 'ZSELEX_Entity_Category',
            'fields' => array(
                'a.category_id',
                'a.category_name'
            ),
            'orderby' => 'a.category_name ASC'
        );
        $categories = $this->entityManager->getRepository('ZSELEX_Entity_Country')->getAll($catArgs);

        $branch_Args = array(
            'entity' => 'ZSELEX_Entity_Branch',
            'fields' => array(
                'a.branch_id',
                'a.branch_name'
            ),
            'orderby' => 'a.branch_name ASC'
        );
        $branches    = $this->entityManager->getRepository('ZSELEX_Entity_Country')->getAll($branch_Args);

        $affiliates = $this->entityManager->getRepository('ZSELEX_Entity_Country')->getAll(array(
            'entity' => 'ZSELEX_Entity_ShopAffiliation',
            'fields' => array(
                'a.aff_id',
                'a.aff_name'
            ),
            'orderby' => 'a.aff_name ASC'
        ));

        $this->view->assign('countries', $countries);
        $this->view->assign('regions', $regions);
        $this->view->assign('cities', $cities);
        $this->view->assign('areas', $areas);
        $this->view->assign('categories', $categories);
        $this->view->assign('branches', $branches);
        $this->view->assign('affiliates', $affiliates);

        // echo "<pre>"; print_r($countries); echo "</pre>";
        if (!empty($join_fields)) {
            // $join_fields = " , " . implode(',', $join_fields);
        }

        // if (UserUtil::getVar('uid') != '3') {

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            $sql .= " AND s.status=1 AND (s.shop_id IN  (SELECT shop_id FROM zselex_shop_owners WHERE user_id='".UserUtil::getVar('uid')."')
                OR s.shop_id IN (SELECT shop_id FROM zselex_shop_admins WHERE user_id='".UserUtil::getVar('uid')."'))";
        }
        if (isset($status) && $status != '') {
            $sql .= ' AND s.status = '.$status;
        }
        if (isset($searchtext) && $searchtext != '') {
            $sql .= " AND s.shop_name LIKE '%".DataUtil::formatForStore($searchtext)."%'";
        }

        if (isset($telephone) && $telephone != '') {
            $sql .= " AND s.telephone LIKE '%".DataUtil::formatForStore($telephone)."%'";
        }

        if (isset($email) && $email != '') {
            $sql .= " AND s.email LIKE '%".DataUtil::formatForStore($email)."%'";
        }

        if (isset($owner) && $owner != '' && $admin) { // only for super admin
            $sql .= " AND u.uname LIKE '%".DataUtil::formatForStore($owner)."%'";
        }

        if (isset($address) && $address != '') {
            $sql .= " AND s.address LIKE '%".DataUtil::formatForStore($address)."%'";
        }

        if (isset($country) && $country != '') {
            $sql .= " AND country.country_name LIKE '%".DataUtil::formatForStore($country)."%'";
        }
        if (isset($region) && $region != '') {
            $sql .= " AND region.region_name LIKE '%".DataUtil::formatForStore($region)."%'";
        }
        if (isset($city) && $city != '') {
            $sql .= " AND city.city_name LIKE '%".DataUtil::formatForStore($city)."%'";
        }
        if (isset($area) && $area != '') {
            $sql .= " AND area.area_name LIKE '%".DataUtil::formatForStore($area)."%'";
        }
        if (isset($category) && $category != '') {
            // echo "category"; exit;
            // $sql .= " AND cat.category_name LIKE '%" . DataUtil::formatForStore($category) . "%'";
        }
        if (isset($branch) && $branch != '') {
            // $sql .= " AND branch.branch_name LIKE '%" . DataUtil::formatForStore($branch) . "%'";
        }

        if (isset($affiliate) && $affiliate > 0) {
            $sql .= " AND s.aff_id=$affiliate ";
        }

        $alias = '';
        if (in_array($order, $shopfields)) {
            $alias = 's.';
        }

        // $sql .= " GROUP BY s.shop_id ";
        $orderby = '';
        if (isset($order) && $order != '') {
            // $sql .= " ORDER BY $alias" . $order . " " . $orderdir;
            $orderby .= " ORDER BY $alias".$order.' '.$orderdir.' ';
        }
        // echo $sql;
        // $sql .= $sql1;
        // echo $sql;
        $this->view->assign('sql', $sql);
        // Get all zselex stories
        $getArgs = array(
            'sql' => $sql,
            'startnum' => $startnum,
            'numitems' => $itemsperpage
        );
        // $items = ModUtil::apiFunc('ZSELEX', 'admin', 'getAll', $getArgs);

        $shop_args = array(
            'startlimit' => $startnum,
            'itemsperpage' => $itemsperpage,
            'join_fields' => $join_fields,
            'joins' => $JOINS,
            'orderby' => $orderby,
            'sql' => $sql
        );
        $shop_item = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getShopListing($shop_args);

        // echo "<pre>"; print_r($shop_item); echo "</pre>";
        // exit;
        $items = $shop_item;

        // echo "<pre>"; print_r($items); echo "</pre>";

        $zselex = ZSELEX_Util::MODULE_INFO('ZSELEX');

        // echo "<pre>"; print_r($zselex); echo "</pre>";

        for ($i = 0; $i < count($items); ++$i) {
            if ($zselex ['version'] > '1.0.5') { // temperory check
                $sc_args                        = array(
                    'shop_id' => $items [$i] ['shop_id']
                );
                $shop_categories                = ModUtil::apiFunc('ZSELEX',
                        'admin', 'getShopCategories', $sc_args);
                $items [$i] ['shop_categories'] = $shop_categories;

                $sb_args                      = array(
                    'shop_id' => $items [$i] ['shop_id']
                );
                $shop_branches                = ModUtil::apiFunc('ZSELEX',
                        'admin', 'getShopBranches', $sb_args);
                $items [$i] ['shop_branches'] = $shop_branches;
            }
        }

        // echo "<pre>"; print_r($items); echo "</pre>";

        $total_shops = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getShopListingCount($shop_args);
        // echo "<pre>"; print_r($total_shops); echo "</pre>";
        // $total_shops = 5;
        // echo "<pre>"; print_r($total_shops); echo "</pre>";
        // echo $total_shops; exit;
        // Set the possible status for later use
        $itemstatus  = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );

        $shopsitems = array();

        foreach ($items as $item) {
            $options = array();

            // $options[] = array('url' => ModUtil::url('ZSELEX', 'admin', 'displayshop', array('shop_id' => $item['shop_id'])),
            // 'image' => '14_layer_visible.png',
            // 'title' => $this->__('View'));

            if (SecurityUtil::checkPermission('ZSELEX::',
                    "{$item['cr_uid']}::{$item['shop_id']}", ACCESS_EDIT)) {
                $options [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'modifyshop',
                        array(
                        'id' => $item ['shop_id']
                    )),
                    'image' => 'xedit.png',
                    'title' => $this->__('Edit')
                );

                if ((SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['shop_id']}", ACCESS_DELETE))
                    || SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['shop_id']}", ACCESS_ADD)) {
                    $options [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin', 'deleteshop',
                            array(
                            'shop_id' => $item ['shop_id']
                        )),
                        'image' => '14_layer_deletelayer.png',
                        'title' => $this->__('Delete')
                    );
                }
            }
            $item ['options'] = $options;

            $item ['infuture'] = DateUtil::getDatetimeDiff_AsField($item ['cr_date'],
                    DateUtil::getDatetime(), 6) < 0;

            $item ['adminnames'] = ModUtil::apiFunc('ZSELEX', 'admin',
                    'getAdmins',
                    $args                = array(
                    'shop_id' => $item ['shop_id']
            ));

            $shopsitems [] = $item;
        }
        // echo "<pre>"; print_r($shopsitems); echo "</pre>";
        // Assign the items to the template
        $this->view->assign('shopsitems', $shopsitems);

        $this->view->assign('total_shops', $total_shops);

        // Assign the current status filter and the possible ones
        $this->view->assign('status', $status);
        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('order', $order);

        $this->view->assign('admins',
            SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN));
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            // KIMENEMARK BEGIN
            $ownername = UserUtil::getVar('uname');
            if ($ownername != '') {
                $fspath = $_SERVER ['DOCUMENT_ROOT'].'/zselexdata/'.$ownername;
                if ($_SERVER ['SERVER_NAME'] == 'localhost') {
                    $fspath = 'zselexdata/'.$ownername;
                }
                $ownerfoldersize = $this->display_size($this->filesize_recursive($fspath));
            } else {
                $ownerfoldersize = '0';
            }
            $diskquota = ModUtil::apiFunc('ZSELEX', 'admin', 'checkDiskquota',
                    $args      = array(
                    'ownername' => $ownername
            ));

            // if ($diskquota['count'] < 1) {
            // LogUtil::registerError($diskquota['message']);
            // } else if ($diskquota['limitover'] < 1) {
            // LogUtil::registerError($diskquota['message']);
            // }
            if ($diskquota ['error'] > 0) {
                LogUtil::registerError($diskquota ['message']);
            }

            // echo "<pre>"; print_r($diskquota); echo "</pre>";
            // $ownerfolderquota = '50MB'; // Sharaz: We have to be able to assign diskquota in ZSELEX for each owner and be able to restrict in the right places.
            $ownerfolderquota = $this->display_size($diskquota ['sizelimit']);

            $this->view->assign('ownerfoldersize', $ownerfoldersize);
            $this->view->assign('ownerfolderquota', $ownerfolderquota);
            // KIMENEMARK END
        }
        // Return the output that has been generated by this function
        return $this->view->fetch('admin/viewshop.tpl');
    }

    public function shop()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        // echo UserUtil::getVar('uid');
        $servicebaskets = ModUtil::apiFunc('ZSELEX', 'admin',
                'getserviceBasket',
                $args           = array(
                'user_id' => UserUtil::getVar('uid')
        ));

        $serviceamount = ModUtil::apiFunc('ZSELEX', 'admin', 'getServiceAmount',
                $args          = array(
                'user_id' => UserUtil::getVar('uid')
        ));
        // echo "<pre>"; print_r($servicebaskets); echo "</pre>";
        $this->view->assign('servicebasket', $servicebaskets);
        $this->view->assign('total', $serviceamount);

        return $this->view->fetch('admin/shopsview.tpl');
    }

    /**
     * create DOTD.
     *
     * @author
     *
     */
    public function dotd($args)
    { // Deal of the day
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        $shop_id   = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));
        $this->view->assign('ownerName', $ownerName);
        if (!(int) ($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)));
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $loguser  = UserUtil::getVar('uid');
        $loguser  = !empty($loguser) ? $loguser : 0;
        $user_id  = $loguser;
        $minishop = ModUtil::apiFunc('ZSELEX', 'admin', 'minishopExist',
                $args     = array(
                'shop_id' => $shop_id
        ));

        $checkdotdexist = ModUtil::apiFunc('ZSELEX', 'admin', 'checkDotdExist',
                $args           = array(
                'shop_id' => $shop_id
        ));
        if ($checkdotdexist == true) {
            return LogUtil::registerError($this->__('Error! Deal Of The Day (DOTD) already exits.'));
        }

        $serviceargs       = array(
            'shop_id' => $shop_id,
            'user_id' => $user_id,
            'type' => 'dealoftheday'
        );
        $servicePermission = $this->serviceCheck($serviceargs);

        if ($servicePermission < 1) {
            return LogUtil::registerError($this->__('The service you try to use has to be purchased first.'));
        }

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) { // disabled check
            if ($this->serviceDisabled('dealoftheday') < 1) {
                return LogUtil::registerError($this->__('This service has been temporarily disabled!'));
            }
        }

        $shopType = ModUtil::apiFunc('ZSELEX', 'admin', 'getShopType1',
                $args     = array(
                'shop_id' => $shop_id
        ));

        $allproducts = '';

        if ($shopType == 'iSHOP') {
            $func                    = 'chooseIshopDodtProduct';
            $_SESSION ['zenproduct'] = '';
            $ishopProducts           = ModUtil::apiFunc('ZSELEX', 'user',
                    'getAll',
                    $getargs                 = array(
                    'table' => 'zselex_products',
                    'where' => "shop_id='".$shop_id."'"
            ));
            $allproducts             = $ishopProducts;
        } elseif ($shopType == 'zSHOP') {
            $func                  = 'chooseZshopDodtProduct';
            $_SESSION ['iproduct'] = '';
            $obj                   = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                    $args                  = array(
                    'table' => 'zselex_zenshop',
                    'where' => "shop_id=$shop_id"
            ));
            $zShopProducts         = ModUtil::apiFunc('ZSELEX', 'admin',
                    'getZenCartProducts',
                    $args                  = array(
                    'shop_id' => $shop_id,
                    'shop' => $obj
            ));
            $allproducts           = $zShopProducts;
        }
        $this->view->assign('ishopProducts', $ishopProducts);
        $this->view->assign('zShopProducts', $zShopProducts);

        // echo "<pre>"; print_r($ishopProducts); echo "</pre>";
        // echo "<pre>"; print_r($zShopProducts); echo "</pre>";
        // echo $func;
        $iproduct      = !empty($_SESSION ['iproduct']) ? $_SESSION ['iproduct']
                : '';
        $zenproduct_id = !empty($_SESSION ['zenproduct']) ? $_SESSION ['zenproduct']
                : '';

        if (!empty($iproduct)) {
            $iproductItem = DBUtil::selectObjectByID('zselex_products',
                    $iproduct, 'product_id');
        }
        if (!empty($zenproduct_id)) {
            $obj  = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                    $args = array(
                    'table' => 'zselex_zenshop',
                    'where' => "shop_id=$shop_id"
            ));

            $zenproductItem = ModUtil::apiFunc('ZSELEX', 'admin',
                    'getZenCartDOTDProducts',
                    $args           = array(
                    'product_id' => $zenproduct_id,
                    'shop' => $obj
            ));
        }
        // echo "<pre>"; print_r($zenproductItem); echo "</pre>";
        // echo "<pre>"; print_r($iproductItem); echo "</pre>";

        $this->view->assign('redirect', 'create');
        $this->view->assign('iproductItem', $iproductItem);
        $this->view->assign('zenproductItem', $zenproductItem);

        $this->view->assign('iproduct', $iproduct);
        $this->view->assign('zenproductid', $zenproduct_id);

        $sess_item = SessionUtil::getVar('createdotd');

        // $_SESSION['DOTD']['test1']='value1';
        // $_SESSION['DOTD']['test2']='value2';
        // echo "<pre>"; print_r($_SESSION['/']); echo "</pre>";
        // echo "<pre>"; print_r($sess_item); echo "</pre>";

        $iShopProducts = ModUtil::apiFunc('ZSELEX', 'admin',
                'getIshopProductsAutocomplete',
                $args          = array(
                'shop_id' => $shop_id
        ));

        // echo "<pre>"; print_r($iShopProducts); echo "</pre>";

        $item = array(
            'dotd_name' => !empty($sess_item ['elemtName']) ? $sess_item ['elemtName']
                    : '',
            'column_name' => !empty($sess_item ['column_name']) ? $sess_item ['column_name']
                    : '',
            'value' => !empty($sess_item ['ishopProductId']) ? $sess_item ['ishopProductId']
                    : $sess_item ['zshopProductId'],
            'dotd_date' => !empty($sess_item ['dotddate']) ? $sess_item ['dotddate']
                    : '',
            'keywords' => !empty($sess_item ['keywords']) ? $sess_item ['keywords']
                    : ''
        );
        // echo "<pre>"; print_r($item); echo "</pre>";
        // echo $func;
        $this->view->assign('func', $func);
        $this->view->assign('prodfunc', $func);
        $this->view->assign('item', $item);
        $this->view->assign('shoptype', $shopType);
        $this->view->assign('products', $iShopProducts);

        return $this->view->fetch('admin/dotd.tpl');
    }

    /**
     * delete DOTD.
     *
     * @author
     *
     */
    public function deleteDotd($args)
    {
        $Id      = FormUtil::getPassedValue('dotdId',
                isset($args ['dotdId']) ? $args ['dotdId'] : null, 'REQUEST');
        $shop_id = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : null, 'REQUEST');
        $user_id = UserUtil::getVar('uid');
        // Validate the essential parameters
        if (empty($Id)) {
            return LogUtil::registerArgsError();
        }

        $serviceEdit = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceCheckEdit',
                $args        = array(
                'shop_id' => $shop_id,
                'item_idValue' => $Id,
                'user_id' => $user_id,
                'servicetable' => 'zselex_dotd',
                'item_id' => 'dotdId',
                'type' => 'dealoftheday'
        ));

        if ($serviceEdit < 1) {
            // return LogUtil::registerError($this->__f('Error! Unable to delete this %s.', $this->__('Deal of The Day')));
        }

        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
        if (empty($confirmation)) {
            // Add ZSELEX type ID
            $this->view->assign('IdValue', $Id);
            $this->view->assign('confirm_title',
                $this->__f('Delete %s item', $this->__('Deal Of The Day')));
            $this->view->assign('confirm_msg',
                $this->__f('Do you want to delete this %s item',
                    $this->__('Deal Of The Day')));
            $this->view->assign('shop_id', $shop_id);
            $this->view->assign('IdName', 'dotdId'); // edit id param name
            $this->view->assign('submitFunc', 'deleteDotd');
            $this->view->assign('cancelFunc', 'viewdotd');
            $emptyvar = $this->__('Confirmation prompt'); // just to get the translation out with poedit!!!
            // Return the output that has been generated by this function
            return $this->view->fetch('admin/deletecommon.tpl');
        }

        $args = array(
            'table' => 'zselex_dotd',
            'IdValue' => $Id,
            'IdName' => 'dotdId'
        );
        if (ModUtil::apiFunc('ZSELEX', 'admin', 'deleteItem', $args)) {
            $user_id       = UserUtil::getVar('uid');
            $args          = array(
                'shop_id' => $shop_id,
                'servicetype' => 'dealoftheday',
                'user_id' => $user_id
            );
            $deleteservice = ModUtil::apiFunc('ZSELEX', 'admin',
                    'deleteService', $args);
            ModUtil::apiFunc('ZSELEX', 'admin', 'updateMinisite',
                array(
                'shop_id' => $shop_id
            ));
            // Success
            LogUtil::registerStatus($this->__('Done! Deal Of The Day (DOTD) has been deleted successfully.'));

            // Let any hooks know that we have deleted an item
            $this->notifyHooks(new Zikula_ProcessHook('type.ui_hooks.types.process_delete',
                $Id));
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewdotd',
                    array(
                    'shop_id' => $shop_id
        )));
    }

    /**
     * submit DOTD.
     *
     * @author
     *
     */
    public function submitdotd($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        if (!(int) ($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)));
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $this->checkCsrfToken();
        $user = UserUtil::getVar('uid');

        $serviceargs       = array(
            'shop_id' => $shop_id,
            'user_id' => $user_id,
            'type' => 'dealoftheday'
        );
        $servicePermission = $this->servicePermission($serviceargs);

        if ($servicePermission ['perm'] < 1) {
            return LogUtil::registerError($this->__($servicePermission ['message']));
        }

        $formElements = FormUtil::getPassedValue('formElements',
                isset($args ['formElements']) ? $args ['formElements'] : null,
                'POST');
        $formElements = $this->purifyHtml($formElements);
        $shopType     = $formElements ['shoptype'];
        // echo $shopType; exit;
        // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;
        if ($formElements ['shoptype'] == 'iSHOP') {
            $product_id = $formElements ['ishopProductId'];
            $item       = array(
                'dotd_name' => $formElements ['elemtName'],
                'shop_id' => $formElements ['shop_id'],
                'user_id' => $user,
                'dotd_date' => isset($formElements ['dotddate']) ? $formElements ['dotddate']
                        : '',
                'keywords' => isset($formElements ['keywords']) ? $formElements ['keywords']
                        : '',
                // 'column_name' => isset($formElements['column_name']) ? $formElements['column_name'] : '',
                'column_name' => 'product_id',
                // 'value' => isset($formElements['columnValue']) ? $formElements['columnValue'] : 0,
                // 'value' => $formElements['ishopProduct'],
                'value' => $product_id,
                'status' => isset($formElements ['status']) ? $formElements ['status']
                        : 0
            );
        } elseif ($formElements ['shoptype'] == 'zSHOP') {
            $_SESSION ['iproduct'] = '';
            $product_id            = $formElements ['zshopProductId'];
            $item                  = array(
                'dotd_name' => $formElements ['elemtName'],
                'shop_id' => $formElements ['shop_id'],
                'user_id' => $user,
                'dotd_date' => isset($formElements ['dotddate']) ? $formElements ['dotddate']
                        : '',
                'keywords' => isset($formElements ['keywords']) ? $formElements ['keywords']
                        : '',
                'column_name' => 'products_id',
                'value' => $product_id,
                'status' => isset($formElements ['status']) ? $formElements ['status']
                        : 0
            );
        }

        if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) { // insert dotd
            // ******* Validation happens here ******//
            $itemValidate = array(
                'dotd_name|DOTD Name' => $formElements ['elemtName'],
                // 'value|Product' => $formElements['columnValue'],
                // 'column_name|Column Name' => isset($formElements['column_name']) ? $formElements['column_name'] : '',
                // 'value|Column Value' => isset($formElements['columnValue']) ? $formElements['columnValue'] : '',
                'dotd_date|DOTD Date' => isset($formElements ['dotddate']) ? $formElements ['dotddate']
                        : ''
            );
            $argsExit     = array(
                'checkColum' => 'dotd_date',
                'checkValue' => isset($formElements ['dotddate']) ? $formElements ['dotddate']
                        : '',
                'product_id' => $product_id
            );

            $validationerror = ZSELEX_Util::validateDotd($argsExit,
                    $itemValidate);

            if ($validationerror !== false) {
                // log the error found if any
                if ($validationerror !== false) {
                    LogUtil::registerError(nl2br($validationerror));
                }
                SessionUtil::setVar('createdotd', $formElements);
                // SessionUtil::setVar('createdotd', $item);
                // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;

                $this->redirect(ModUtil::url('ZSELEX', 'admin', 'dotd',
                        array(
                        'shop_id' => $formElements ['shop_id']
                )));
            } else {
                // As we're not previewing the item let's remove it from the session
                SessionUtil::delVar('createdotd');
                $_SESSION ['zenproduct'] = '';
                $_SESSION ['iproduct']   = '';
            }
            // ******* Validation ends ******//

            $args     = array(
                'table' => 'zselex_dotd',
                'element' => $item,
                'Id' => 'dotdId'
            );
            // Create the zselex type
            $result   = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement',
                    $args);
            $InsertId = DBUtil::getInsertID($args ['table'], $args ['Id']);

            $user = UserUtil::getVar('uid');
            if ($result) {
                $serviceupdatearg = array(
                    'user_id' => $user,
                    'type' => 'dealoftheday',
                    'shop_id' => $formElements ['shop_id']
                );
                $serviceavailed   = ModUtil::apiFunc('ZSELEX', 'admin',
                        'updateServiceUsed', $serviceupdatearg);
                $keyword_crt_args = array(
                    'keywords' => $formElements ['keywords'],
                    'type' => 'dealoftheday',
                    'type_id' => $InsertId,
                    'shop_id' => $formElements ['shop_id']
                );
                $this->createkeywords($keyword_crt_args);
            }
        } else { // update dotd
            $InsertId = $formElements ['elemId'];

            // echo $formElements['selecteddotd'];
            // ******* Validation happens here ******//
            $itemValidate = array(
                'dotd_name|DOTD Name' => $formElements ['elemtName'],
                // 'value|Product' => $formElements['columnValue'],
                // 'column_name|Column Name' => isset($formElements['column_name']) ? $formElements['column_name'] : '',
                // 'value|Column Value' => isset($formElements['columnValue']) ? $formElements['columnValue'] : '',
                'dotd_date|DOTD Date' => isset($formElements ['dotddate']) ? $formElements ['dotddate']
                        : ''
            );
            $argsExit     = array(
                'checkColum' => 'dotd_date',
                'checkValue' => isset($formElements ['dotddate']) ? $formElements ['dotddate']
                        : '',
                'product_id' => $product_id,
                'selectedDate' => $formElements ['selecteddotd']
            );

            $validationerror = ZSELEX_Util::validateDotdModify($argsExit,
                    $itemValidate);

            if ($validationerror !== false) {
                // log the error found if any
                if ($validationerror !== false) {
                    LogUtil::registerError(nl2br($validationerror));
                }
                // SessionUtil::setVar('createdotd', $formElements);

                $this->redirect(ModUtil::url('ZSELEX', 'admin', 'modifydotd',
                        array(
                        'dotdId' => $InsertId,
                        'shop_id' => $formElements ['shop_id']
                )));
            } else {
                // As we're not previewing the item let's remove it from the session
                // SessionUtil::delVar('createdotd');
                // $_SESSION['zenproduct'] = '';
                // $_SESSION['iproduct'] = '';
            }
            // ******* Validation ends ******//

            if ($formElements ['shoptype'] == 'iSHOP') {
                $item = array(
                    'dotdId' => $formElements ['elemId'],
                    'dotd_name' => $formElements ['elemtName'],
                    'dotd_date' => isset($formElements ['dotddate']) ? $formElements ['dotddate']
                            : '',
                    'keywords' => isset($formElements ['keywords']) ? $formElements ['keywords']
                            : '',
                    // 'column_name' => isset($formElements['column_name']) ? $formElements['column_name'] : '',
                    'column_name' => 'product_id',
                    // 'value' => isset($formElements['columnValue']) ? $formElements['columnValue'] : 0,
                    'value' => $formElements ['ishopProductId'],
                    'status' => isset($formElements ['status']) ? $formElements ['status']
                            : 0
                );
            } elseif ($formElements ['shoptype'] == 'zSHOP') {
                $_SESSION ['iproduct'] = '';

                $item = array(
                    'dotdId' => $formElements ['elemId'],
                    'dotd_name' => $formElements ['elemtName'],
                    'dotd_date' => isset($formElements ['dotddate']) ? $formElements ['dotddate']
                            : '',
                    'keywords' => isset($formElements ['keywords']) ? $formElements ['keywords']
                            : '',
                    'column_name' => 'products_id',
                    'value' => $formElements ['zshopProductId'],
                    'status' => isset($formElements ['status']) ? $formElements ['status']
                            : 0
                );
            }

            $updateargs = array(
                'table' => 'zselex_dotd',
                'IdValue' => $InsertId,
                'IdName' => 'dotdId',
                'element' => $item
            );
            // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;
            $result     = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement',
                    $updateargs);
            if ($result) {
                $keyword_upd_args = array(
                    'keywords' => $formElements ['keywords'],
                    'type' => 'dealoftheday',
                    'type_id' => $InsertId,
                    'shop_id' => $formElements ['shop_id']
                );
                $this->updatekeywords($args             = $keyword_upd_args);
            }
        }

        if ($result == true) {
            // Success
            if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) {
                LogUtil::registerStatus($this->__('Done! Deal Of The Day (DOTD) has been created successfully.'));
            } else {
                LogUtil::registerStatus($this->__('Done! Deal Of The Day (DOTD) has been updated successfully.'));
            }
            $_SESSION ['zenproduct'] = '';
            $_SESSION ['iproduct']   = '';
            ModUtil::apiFunc('ZSELEX', 'admin', 'updateMinisite',
                array(
                'shop_id' => $InsertId
            ));
        } else {
            // fail! type not created
            throw new Zikula_Exception_Fatal($this->__('Story not created for unknown reason (Api failure).'));

            return false;
        }

        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewdotd',
                array(
                'shop_id' => !empty($formElements ['shop_id']) ? $formElements ['shop_id']
                        : $formElements ['shop_id']
        )));
    }

    public function viewdotd($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());

        // echo UserUtil::getVar('uid');
        $_SESSION ['zenproduct'] = '';
        $_SESSION ['iproduct']   = '';
        SessionUtil::delVar('createdotd');

        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $user_id = UserUtil::getVar('uid');
        if ($this->shopPermission($shop_id) < 1) {
            // return LogUtil::registerPermissionError();
            return LogUtil::registerPermissionError();
        }

        $error          = 0;
        $servicecount   = 0;
        $message        = '';
        $servicedisable = false;
        $admin          = SecurityUtil::checkPermission('ZSELEX::', '::',
                ACCESS_ADMIN);
        $this->view->assign('admin', $admin);
        $template       = 'viewdotd.tpl';

        $checkdotdexist = ModUtil::apiFunc('ZSELEX', 'admin', 'checkDotdExist',
                $args           = array(
                'shop_id' => $shop_id
        ));
        if ($checkdotdexist == true) {

            // return LogUtil::registerError($this->__('Error! Deal Of The Day (DOTD) already exits.'));
            ++$error;
        }

        $serviceargs       = array(
            'shop_id' => FormUtil::getPassedValue('shop_id', null, 'REQUEST'),
            'user_id' => $user_id,
            'type' => 'dealoftheday'
        );
        $servicePermission = $this->servicePermission($serviceargs);
        $servicecount += $servicePermission ['perm'];

        if ($servicePermission ['perm'] < 1) {

            // $template = 'viewdotd_noservice.tpl';
            $message = $servicePermission ['message'];
            LogUtil::registerError(nl2br($message));
            ++$error;
        }

        if ($this->serviceDisabled('dealoftheday') < 1) {
            $serviceDisabled = $this->serviceDisabled('dealoftheday');
            if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $servicedisable = true;
                $disable        = 'disabled';
                ++$error;
            }
            $message = $this->__('This service is currently disabled');
            LogUtil::registerError(nl2br($message));
        }
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            $expired = $servicePermission ['expired'];
        }

        // echo "Error :" . $error;

        $this->view->assign('servicecount', $servicecount);
        $this->view->assign('serviceerror', $error);
        $this->view->assign('servicedisable', $servicedisable);
        $this->view->assign('expired', $expired);
        $this->view->assign('disable', $disable);
        $this->view->assign('message', $message);

        $shopType = ModUtil::apiFunc('ZSELEX', 'admin', 'getShopType',
                $args     = array(
                'shop_id' => $shop_id
        ));

        $user = UserUtil::getVar('uid');
        // if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
        // $dotd = ModUtil::apiFunc('ZSELEX', 'user', 'selectJoinArray', $args = array('table' => 'zselex_dotd a', 'joins' => array("INNER JOIN zselex_shop s ON s.shop_id=a.shop_id"),
        // 'where' => array("a.shop_id=$shop_id")));
        // } else {
        // $dotd = ModUtil::apiFunc('ZSELEX', 'user', 'selectJoinArray', $args = array('table' => 'zselex_dotd a', 'joins' => array("INNER JOIN zselex_shop s ON s.shop_id=a.shop_id"),
        // 'where' => array("a.cr_uid=$user", "a.shop_id=$shop_id")));
        // }

        $dotd = ModUtil::apiFunc('ZSELEX', 'user', 'selectJoinArray',
                $args = array(
                'table' => 'zselex_dotd a',
                'joins' => array(
                    'INNER JOIN zselex_shop s ON s.shop_id=a.shop_id'
                ),
                'where' => array(
                    "a.shop_id=$shop_id"
                )
        ));

        $perm = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD);
        $this->view->assign('perm', $perm);
        // echo "<pre>"; print_r($servicebaskets); echo "</pre>";
        $this->view->assign('dotd', $dotd);
        $this->view->assign('total', $serviceamount);

        return $this->view->fetch('admin/dotd/'.$template);
    }

    public function modifydotd($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        // echo "modifycity";
        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));
        $this->view->assign('ownerName', $ownerName);
        $dotdId    = FormUtil::getPassedValue('dotdId',
                isset($args ['dotdId']) ? $args ['dotdId'] : null, 'GETPOST');
        $user_id   = UserUtil::getVar('uid');

        $serviceEdit = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceCheckEdit',
                $args        = array(
                'shop_id' => $shop_id,
                'item_idValue' => $dotdId,
                'user_id' => $user_id,
                'servicetable' => 'zselex_dotd',
                'item_id' => 'dotdId',
                'type' => 'dealoftheday'
        ));

        if ($serviceEdit < 1) {
            // return LogUtil::registerError($this->__f('Error! Unable to edit this %s.', $this->__('Deal of The Day')));
        }

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) { // disabled check
            if ($this->serviceDisabled('dealoftheday') < 1) {
                return LogUtil::registerError($this->__('This service has been temporarily disabled!'));
            }
        }

        $args = array(
            'table' => 'zselex_dotd a',
            'where' => array(
                "a.dotdId=$dotdId"
            ),
            'joins' => array(
                'LEFT JOIN zselex_shop s ON s.shop_id=a.shop_id'
            )
        );
        // Get the news type in the db
        $item = ModUtil::apiFunc('ZSELEX', 'user', 'selectJoinRow', $args);
        // echo "<pre>"; print_r($item); echo "</pre>"; exit;

        $shopType = ModUtil::apiFunc('ZSELEX', 'admin', 'getShopType1',
                $args     = array(
                'shop_id' => $item ['shop_id']
        ));

        // echo $shopType; exit;
        $iproductItem = array();
        if ($shopType == 'iSHOP') {
            $func            = 'chooseIshopDodtProduct';
            $ishopproduct_id = !empty($_SESSION ['iproduct']) ? $_SESSION ['iproduct']
                    : $item ['value'];
            // echo $ishopproduct_id; exit;
            if (!empty($ishopproduct_id)) {
                $iproductItem = DBUtil::selectObjectByID('zselex_products',
                        $ishopproduct_id, 'product_id');
            }
            $ishopProducts = ModUtil::apiFunc('ZSELEX', 'user', 'getAll',
                    $getargs       = array(
                    'table' => 'zselex_products',
                    'where' => "shop_id='".$shop_id."'"
            ));
            // $_SESSION['iproduct'] = $item['value'];
            // echo "<pre>"; print_r($iproductItem); echo "</pre>"; exit;
        } elseif ($shopType == 'zSHOP') {
            $func          = 'chooseZshopDodtProduct';
            $zenproduct_id = !empty($_SESSION ['zenproduct']) ? $_SESSION ['zenproduct']
                    : $item ['value'];
            // $obj = DBUtil::selectObjectByID('zselex_shop', $item['shop_id'], 'shop_id');
            $obj           = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                    $args          = array(
                    'table' => 'zselex_zenshop',
                    'where' => "shop_id=$shop_id"
            ));
            /*
             * $zenproductItem = ModUtil::apiFunc('ZSELEX', 'admin', 'getZenCartDOTDProducts', $args = array(
             * 'product_id' => $zenproduct_id,
             * 'shop' => $obj
             * ));
             */
            $zShopProducts = ModUtil::apiFunc('ZSELEX', 'admin',
                    'getZenCartProducts',
                    $args          = array(
                    'shop_id' => $shop_id,
                    'shop' => $obj
            ));
            // $_SESSION['zenproduct'] = $item['value'];
        }

        $this->view->assign('ishopProducts', $ishopProducts);
        $this->view->assign('zShopProducts', $zShopProducts);

        $formElements = array(
            'dotdId' => $item ['dotdId'],
            'dotd_name' => !empty($_SESSION ['/'] ['createdotd'] ['elemtName']) ? $_SESSION ['/'] ['createdotd'] ['elemtName']
                    : $item ['dotd_name'],
            'dotd_date' => !empty($_SESSION ['/'] ['createdotd'] ['dotddate']) ? $_SESSION ['/'] ['createdotd'] ['dotddate']
                    : $item ['dotd_date'],
            'keywords' => !empty($_SESSION ['/'] ['createdotd'] ['keywords']) ? $_SESSION ['/'] ['createdotd'] ['keywords']
                    : $item ['keywords'],
            'value' => !empty($_SESSION ['/'] ['createdotd'] ['value']) ? $_SESSION ['/'] ['createdotd'] ['value']
                    : $item ['value']
        );

        SessionUtil::setVar('createdotd', $formElements);
        $sess_item = SessionUtil::getVar('createdotd');

        // echo "<pre>"; print_r($sess_item); echo "</pre>";
        // echo $_SESSION['/']['createdotd']['elemtName'];
        // echo $_SESSION['/']['createdotd']['dotddate'];

        $iproduct      = $ishopproduct_id;
        $zenproduct_id = $zenproduct_id;

        $this->view->assign('redirect', "modify&dotdId=$dotdId");
        $this->view->assign('func', $func);

        $this->view->assign('shoptype', $shopType);

        $this->view->assign('iproduct', $iproduct);
        $this->view->assign('zenproductid', $zenproduct_id);

        $this->view->assign('iproductItem', $iproductItem);
        $this->view->assign('zenproductItem', $zenproductItem);

        $this->view->assign('item', $sess_item);

        // echo "<pre>"; print_r($sess_item); echo "</pre>";

        return $this->view->fetch('admin/dotd.tpl');
    }

    public function shopinnerview($args)
    {
        // error_reporting(0);
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        $shopRepo = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        $shop_id  = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        // $this->payMentAlert($shop_id);

        $count = $shopRepo->getCount(array(
            'entity' => 'ZSELEX_Entity_MinisiteUpdate',
            'field' => 'shop_id',
            'where' => array(
                'a.shop_id' => $shop_id
            )
        ));

        // echo $count; exit;

        if ($count < 1) {
            ModUtil::apiFunc('ZSELEX', 'admin', 'updateMinisite',
                array(
                'shop_id' => $shop_id,
                'is_new' => 1
            ));
        }

        if ($_REQUEST ['op'] == 'cl') {
            $item = array(
                'is_updated_recent' => 1
            );

            $clear = $shopRepo->updateEntity(array(
                'entity' => 'ZSELEX_Entity_MinisiteUpdate',
                'fields' => $item,
                'where' => array(
                    'a.shop_id' => $shop_id
                )
            ));
        }

        $notupdated_recent = $shopRepo->getCount(array(
            'entity' => 'ZSELEX_Entity_MinisiteUpdate',
            'field' => 'shop_id',
            'where' => array(
                'a.shop_id' => $shop_id,
                'a.is_updated_recent' => 0
            )
        ));

        // echo $notupdated_recent;

        $this->view->assign('notupdate_recent', $notupdated_recent);
        /*
         * $item = ModUtil::apiFunc('ZSELEX', 'user', 'selectJoinRow', array(
         * 'table' => 'zselex_shop a',
         * 'where' => array(
         * "a.shop_id=$shop_id"
         * )
         * ));
         */

        $getArgs = array(
            'entity' => 'ZSELEX_Entity_Shop',
            // 'fields' => array('a.prd_quantity'),
            'where' => array(
                'a.shop_id' => $shop_id
            )
        );
        $item    = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->get($getArgs);

        // echo "<pre>"; print_r($item); echo "</pre>";
        $this->view->assign('item', $item);

        // KIMENEMARK BEGIN

        $ownername = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));
        if ($ownername != '') {
            $fspath = $_SERVER ['DOCUMENT_ROOT'].'/zselexdata/'.$ownername;
            if ($_SERVER ['SERVER_NAME'] == 'localhost') {
                $fspath = 'zselexdata/'.$ownername;
            }
            // echo $this->filesize_recursive($fspath);
            $ownerfoldersize = $this->display_size($this->filesize_recursive($fspath));
        } else {
            $ownerfoldersize = '0';
        }

        // echo $this->owner_id; exit;
        // $ownerfoldersize = round($ownerfoldersize);
        $ownerfoldersize = ModUtil::apiFunc('ZSELEX', 'service',
                'ownerFolderSize',
                $args            = array(
                'owner_id' => $this->owner_id
        ));

        // echo "hellooo2"; exit;

        $diskquota     = ModUtil::apiFunc('ZSELEX', 'admin', 'checkDiskquota',
                $args          = array(
                'shop_id' => $shop_id
        ));
        // echo "hellooo2"; exit;
        // if ($diskquota['count'] < 1) {
        // LogUtil::registerError($diskquota['message']);
        // } else if ($diskquota['limitover'] < 1) {
        // LogUtil::registerError($diskquota['message']);
        // }
        // $where = "shop_id=$shop_id";
        // $service_exist = DBUtil::selectObjectCount('zselex_serviceshop', $where);
        $service_exist = $shopRepo->getCount(array(
            'entity' => 'ZSELEX_Entity_ServiceShop',
            'field' => 'id',
            'where' => array(
                'a.shop' => $shop_id
            )
        ));

        if (!$service_exist) {
            // echo "Comes here";
            LogUtil::registerStatus($this->__('Congratulations on your new online representation.<br />We welcome you at CityPilot.<br />Please try one of our service packages in a free demo period.<br />Sincerely Your CityPilot team.'));
        } elseif ($diskquota ['error'] > 0) {
            LogUtil::registerError($diskquota ['message']);
        }

        // echo "<pre>"; print_r($diskquota); echo "</pre>";
        // $ownerfolderquota = '50MB'; // Sharaz: We have to be able to assign diskquota in ZSELEX for each owner and be able to restrict in the right places.

        $ownerfolderquota = $this->display_size($diskquota ['sizelimit']);
        $this->view->assign('ownerfoldersize', $ownerfoldersize);
        $this->view->assign('ownerfolderquota', $ownerfolderquota);
        // KIMENEMARK END

        return $this->view->fetch('admin/shop_innerview.tpl');
    }

    public function viewbasket($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        // echo UserUtil::getVar('uid');
        // $servicebaskets = ModUtil::apiFunc('ZSELEX', 'admin', 'getserviceBasket', $args = array('user_id' => UserUtil::getVar('uid')));
        $servicebasket = ModUtil::apiFunc('ZSELEX', 'admin', 'getBasket',
                $args          = array(
                'user_id' => UserUtil::getVar('uid')
        ));

        $serviceamount = ModUtil::apiFunc('ZSELEX', 'admin', 'getServiceAmount',
                $args          = array(
                'user_id' => UserUtil::getVar('uid')
        ));
        // echo "<pre>"; print_r($servicebaskets); echo "</pre>";
        // echo "<pre>"; print_r($servicebasket); echo "</pre>";
        // $gtotal = array_sum($servicebasket['price']);

        if (!empty($servicebasket)) {
            foreach ($servicebasket as $val) {
                $price [] = $val ['price'];
            }
            $granTotal = array_sum($price);
        } else {
            $granTotal = '';
        }

        // echo "<pre>"; print_r($price); echo "</pre>";

        $this->view->assign('servicebasket', $servicebasket);
        $this->view->assign('granTotal', $granTotal);
        $this->view->assign('total', $serviceamount);

        return $this->view->fetch('admin/viewbasket.tpl');
    }

    public function submitservices($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        // echo "<pre>"; print_r($_SESSION['admincart']); echo "</pre>";
        $shop_id      = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');

        $ownerInfo = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow',
                array(
                'table' => 'zselex_shop_owners a , users b',
                'where' => array(
                    "a.shop_id='".$shop_id."'",
                    'a.user_id=b.uid'
                )
        ));
        // echo "<pre>"; print_r($ownerInfo); echo "</pre>";
        $owner_id  = $ownerInfo ['uid'];
        // Check for confirmation.
        if (empty($confirmation)) {
            // Add ZSELEX type ID
            // Return the output that has been generated by this function
            return $this->view->fetch('admin/basket_confirmation.tpl');
        }

        // If we get here it means that the admin has confirmed the action
        // Confirm authorisation code
        $this->checkCsrfToken();

        // echo UserUtil::getVar('uid');
        $servicepurchased = ModUtil::apiFunc('ZSELEX', 'admin', 'getBasket',
                $args             = array(
                'user_id' => UserUtil::getVar('uid')
        ));
        // exit;
        // echo "<pre>"; print_r($servicepurchased); echo "</pre>"; exit;
        $serviceshop      = ModUtil::apiFunc('ZSELEX', 'admin',
                'insertServicesShopApprovals',
                $args             = array(
                'user_id' => UserUtil::getVar('uid'),
                'data' => $servicepurchased,
                'owner_id' => $owner_id
        ));
        // echo "<pre>"; print_r($servicebaskets); echo "</pre>";

        LogUtil::registerStatus($this->__('Done! Service(s) has been configured to your minisite/-shop successfully.'));
        // return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewbasket'));
        if ($shop_id) {
            return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'services',
                        array(
                        'shop_id' => $shop_id
            )));
        } else {
            return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewbasket'));
        }
    }

    public function viewserviceapproval($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        // initialize sort array - used to display sort classes and urls
        $sort   = array();
        $fields = array(
            'id',
            'name',
            'status',
            'cr_date',
            'lu_date'
        ); // possible sort fields

        $itemsperpage  = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 15,
                'GETPOST');
        $startnum      = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $status        = FormUtil::getPassedValue('status',
                isset($args ['status']) ? $args ['status'] : null, 'GETPOST');
        $order         = FormUtil::getPassedValue('order',
                isset($args ['order']) ? $args ['order'] : 'cr_date', 'GETPOST');
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
                    'viewadvertise',
                    array(
                    'status' => $status,
                    'filtercats_serialized' => serialize($filtercats),
                    'order' => $field,
                    'sdir' => $sdir
            ));
        }
        $this->view->assign('sort', $sort);

        $this->view->assign('filter_active', $status);

        $sql = ' SELECT * FROM zselex_serviceapproval a LEFT JOIN zselex_shop s ON s.shop_id=a.shop_id
                 LEFT JOIN users u ON u.uid=a.user_id
                 LEFT JOIN zselex_plugin p ON p.plugin_id=a.plugin_id
                 WHERE a.id IS NOT NULL AND a.status=0';

        // echo $sql;
        // Get all zselex stories
        $getArgs = array(
            'sql' => $sql,
            'startnum' => $startnum,
            'numitems' => $itemsperpage
        );
        $items   = ModUtil::apiFunc('ZSELEX', 'admin', 'getAll', $getArgs);

        $where = ' id IS NOT NULL  AND status=0';

        if (isset($status) && $status != '') {
            $where .= ' AND status = '.$status;
        }
        if (isset($searchtext) && $searchtext != '') {
            $where .= " AND name LIKE '%".DataUtil::formatForStore($searchtext)."%'";
        }
        $getCountArgs = array(
            'table' => 'zselex_serviceapproval',
            'where' => $where,
            'Id' => 'id',
            'status' => $status
        );

        $total_advertises = ModUtil::apiFunc('ZSELEX', 'admin', 'countElements',
                $getCountArgs);

        // Set the possible status for later use
        $itemstatus = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );

        $advertisesitems = array();
        foreach ($items as $item) {
            $options = array();

            if (SecurityUtil::checkPermission('ZSELEX::',
                    "{$item['user_id']}::{$item['id']}", ACCESS_ADMIN)) {
                $options [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'approveService',
                        array(
                        'id' => $item ['id']
                    )),
                    'image' => 'up.png',
                    'title' => $this->__('Approve')
                );

                if ((SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['user_id']}::{$item['id']}", ACCESS_DELETE)) || SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['user_id']}::{$item['id']}", ACCESS_ADMIN)) {
                    $options [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin',
                            'deleteService',
                            array(
                            'id' => $item ['id']
                        )),
                        'image' => '14_layer_deletelayer.png',
                        'title' => $this->__('Delete')
                    );
                }
            }
            $item ['options'] = $options;

            $item ['infuture']  = DateUtil::getDatetimeDiff_AsField($item ['cr_date'],
                    DateUtil::getDatetime(), 6) < 0;
            $advertisesitems [] = $item;
        }

        // Assign the items to the template
        $this->view->assign('aprovalitems', $advertisesitems);

        $this->view->assign('total_advertises', $total_advertises);

        // Assign the current status filter and the possible ones
        $this->view->assign('status', $status);
        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('order', $order);

        // Return the output that has been generated by this function
        return $this->view->fetch('admin/viewserviceapproval.tpl');
    }

    public function approveService()
    {
        // echo "comes here"; exit;
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $Id = FormUtil::getPassedValue('id',
                isset($args ['id']) ? $args ['id'] : null, 'REQUEST');

        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');

        if (empty($confirmation)) {
            // Add ZSELEX type ID
            $this->view->assign('IdValue', $Id);
            $this->view->assign('IdName', 'id');
            $this->view->assign('submitFunc', 'approveService');
            $this->view->assign('cancelFunc', 'viewserviceapproval');

            return $this->view->fetch('admin/service_confirmation.tpl');
        }

        // If we get here it means that the admin has confirmed the action
        // Confirm authorisation code
        $this->checkCsrfToken();
        // exit;

        $serviceshop = ModUtil::apiFunc('ZSELEX', 'admin', 'approveServices',
                $args        = array(
                'id' => $Id
        ));
        // echo "<pre>"; print_r($servicebaskets); echo "</pre>";

        LogUtil::registerStatus($this->__('Done! Service approved and configured successfully.'));

        return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                    'viewserviceapproval'));
    }

    public function deleteService($args)
    {
        $Id           = FormUtil::getPassedValue('id',
                isset($args ['id']) ? $args ['id'] : null, 'REQUEST');
        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
        // Validate the essential parameters
        if (empty($Id)) {
            return LogUtil::registerArgsError();
        }
        $args = array(
            'table' => 'zselex_serviceapproval',
            'IdValue' => $Id,
            'IdName' => 'id'
        );

        // Check for confirmation.
        if (empty($confirmation)) {
            // Add ZSELEX type ID
            $this->view->assign('IdValue', $Id);
            $this->view->assign('confirm_title', $this->__('Delete Service'));
            $this->view->assign('confirm_msg',
                $this->__('Do you want to delete this Service'));
            $this->view->assign('IdName', 'id');
            // $this->view->assign('shop_id', $shop_id); is it already assigned?
            $this->view->assign('submitFunc', 'deleteService');
            $this->view->assign('cancelFunc', 'viewserviceapproval');

            // Return the output that has been generated by this function
            return $this->view->fetch('admin/deletecommon1.tpl');
        }

        // If we get here it means that the admin has confirmed the action
        // Confirm authorisation code
        $this->checkCsrfToken();

        // Delete
        if (ModUtil::apiFunc('ZSELEX', 'admin', 'deleteItem', $args)) {
            // Success
            LogUtil::registerStatus($this->__('Done! Service has been deleted successfully.'));

            // Let any hooks know that we have deleted an item
            $this->notifyHooks(new Zikula_ProcessHook('type.ui_hooks.types.process_delete',
                $Id));
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                    'viewserviceapproval'));
    }

    public function deleteBasket()
    {

        // echo "hiiiii"; exit;
        $Id = FormUtil::getPassedValue('id',
                isset($args ['id']) ? $args ['id'] : null, 'REQUEST');

        // Validate the essential parameters
        if (empty($Id)) {
            return LogUtil::registerArgsError();
        }
        $args = array(
            'table' => 'zselex_basket',
            'IdValue' => $Id,
            'IdName' => 'basket_id'
        );
        if (ModUtil::apiFunc('ZSELEX', 'admin', 'deleteItem', $args)) {
            // Success
            LogUtil::registerStatus($this->__('Done! Cart has been deleted successfully.'));

            // Let any hooks know that we have deleted an item
            $this->notifyHooks(new Zikula_ProcessHook('type.ui_hooks.types.process_delete',
                $Id));
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewbasket'));
    }

    public function deleteBasket1()
    {

        // echo "hiiiii"; exit;
        $Id = FormUtil::getPassedValue('id',
                isset($args ['id']) ? $args ['id'] : null, 'REQUEST');

        unset($_SESSION ['admincart'] [$Id]);

        setcookie("admincart[$Id]", '', time() - 604800);
        // LogUtil::registerStatus($this->__('Done! Deleted Item.'));
        // AjaxUtil::ajaxOutput("deleted");

        LogUtil::registerStatus($this->__('Done! Deleted Basket.'));

        // Let any hooks know that we have deleted an item
        $this->notifyHooks(new Zikula_ProcessHook('type.ui_hooks.types.process_delete',
            $Id));

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewbasket'));
    }

    public function deleteExtraProductServices($args)
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
                    "type='addproducts'"
                )
        ));
        if ($servicecheck ['availed'] > $servicecheck ['quantity']) {
            $service_used_extra  = $servicecheck ['availed'] - $servicecheck ['quantity'];
            $original_used_extra = $servicecheck ['availed'] - $service_used_extra;
            // echo $original_used_extra;
            $service_extra       = ModUtil::apiFunc('ZSELEX', 'user',
                    'selectArray',
                    $args                = array(
                    'table' => 'zselex_products',
                    'where' => array(
                        "shop_id=$shop_id"
                    ),
                    'orderby' => 'product_id DESC',
                    'limit' => "LIMIT 0 , $service_used_extra"
            ));

            // echo "<pre>"; print_r($service_extra); echo "</pre>";

            foreach ($service_extra as $extra_item) {
                unlink('zselexdata/'.$ownername.'/products/fullsize/'.$extra_item [prd_image]);
                unlink('zselexdata/'.$ownername.'/products/medium/'.$extra_item [prd_image]);
                unlink('zselexdata/'.$ownername.'/products/thumb/'.$extra_item [prd_image]);
                $where = "product_id=$extra_item[product_id]";
                DBUtil::deleteWhere('zselex_products', $where);

                // echo $extra_item['pdf_name'] . '<br>';
            }
            $upd_ser_args   = array(
                'table' => 'zselex_serviceshop',
                'items' => array(
                    'availed' => $original_used_extra
                ),
                'where' => array(
                    'shop_id' => $shop_id,
                    'type' => 'addproducts'
                )
            );
            $update_service = ModUtil::apiFunc('ZSELEX', 'admin',
                    'updateElementWhere', $upd_ser_args);
        }

        return true;
    }

    public function viewproducts()
    {

        // return false;
        // echo "testingg";
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());

        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        if (!(int) ($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)));
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $bulkaction = (int) FormUtil::getPassedValue('news_bulkaction_select',
                0, 'POST');
        $sids       = FormUtil::getPassedValue('news_selected_articles',
                array(), 'POST');
        if ($bulkaction >= 1 && $bulkaction <= 5) {
            // echo "exit;"; e xit;
            // echo "<pre>";print_r($sids); echo "</pre>"; exit;
            $actionmap    = array(
                // these indices are not constants, just unrelated integers
                1 => __('Delete'),
                2 => __('Archive'),
                3 => __('Publish'),
                4 => __('Reject'),
                5 => __('Change categories')
            );
            $updateresult = array(
                'successful' => array(),
                'failed' => array()
            );

            switch ($bulkaction) {

                case 1 : // delete
                    // echo "comes hereee"; exit;
                    foreach ($sids as $sid) {
                        $get     = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                                $getargs = array(
                                'table' => 'zselex_products',
                                'where' => "product_id='".$sid."'"
                        ));
                        if (file_exists('zselexdata/'.$this->ownername.'/products/'.$get ['prd_image'])) {
                            @unlink('zselexdata/'.$this->ownername.'/products/'.$get ['prd_image']);
                        }
                        if (file_exists('zselexdata/'.$this->ownername.'/products/fullsize/'.$get ['prd_image'])) {
                            @unlink('zselexdata/'.$this->ownername.'/products/fullsize/'.$get ['prd_image']);
                        }
                        if (file_exists('zselexdata/'.$this->ownername.'/products/medium/'.$get ['prd_image'])) {
                            @unlink('zselexdata/'.$this->ownername.'/products/medium/'.$get ['prd_image']);
                        }
                        if (file_exists('zselexdata/'.$this->ownername.'/products/thumb/'.$get ['prd_image'])) {
                            @unlink('zselexdata/'.$this->ownername.'/products/thumb/'.$get ['prd_image']);
                        }

                        if (DBUtil::deleteObjectByID('zselex_products', $sid,
                                'product_id')) {
                            // assume max pictures. if less, errors are supressed by @
                            $updateresult ['successful'] [] = $sid;
                            $args                           = array(
                                'shop_id' => $shop_id,
                                'servicetype' => 'addproducts',
                                'user_id' => $user_id
                            );
                            $deleteservice                  = ModUtil::apiFunc('ZSELEX',
                                    'admin', 'deleteService', $args);
                        } else {
                            $updateresult ['failed'] [] = $sid;
                        }
                    }
                    if (sizeof($updateresult ['successful']) > 0) {
                        LogUtil::registerStatus($this->__('Done! Selected Items Deleted Successsfully.'));
                    }
                    break;
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
        $disabled       = 'no';
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

        $args_del_extra_service = array(
            'ownername' => $ownerName,
            'shop_id' => $shop_id
        );
        $check                  = $this->deleteExtraProductServices($args_del_extra_service);

        // $uploadpath = $_SERVER['DOCUMENT_ROOT'] . "/zselexdata/" . $ownerName . "/";
        $uploadpath = 'zselexdata/'.$ownerName.'/';
        if ($_SERVER ['SERVER_NAME'] == 'localhost') {
            $uploadpath = 'zselexdata/'.$ownerName.'/';
        }
        $this->view->assign('uploadpath', $uploadpath);

        $admin = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN);
        $this->view->assign('admin', $admin);

        $disabled = 'no';
        $template = 'viewproducts.tpl';

        $serviceargs       = array(
            'shop_id' => $shop_id,
            'user_id' => $user_id,
            'type' => 'addproducts'
        );
        $servicePermission = $this->servicePermission($serviceargs);
        $servicecount += $servicePermission ['perm'];

        if ($servicePermission ['perm'] < 1) {
            // $template = 'viewproducts_noservice.tpl';
            $message = $servicePermission ['message'];
            ++$error;
            LogUtil::registerError(nl2br($message));
        }

        if ($this->serviceDisabled('addproducts') < 1) {
            if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $servicedisable = true;
                ++$error;
                $disable        = 'disabled';
            }
            $message = $this->__('This service is currently disabled');
            LogUtil::registerError(nl2br($message));
        }
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            $expired = $servicePermission ['expired'];
        }

        /*
         * $diskquota = ModUtil::apiFunc('ZSELEX', 'admin', 'checkDiskquota', $args = array(
         * 'shop_id' => $shop_id
         * ));
         *
         * if ($diskquota['error'] > 0) {
         * $error++;
         * LogUtil::registerError($diskquota['message']);
         * }
         */

        $servicecheck = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow',
                $args         = array(
                'table' => 'zselex_serviceshop',
                'where' => array(
                    "shop_id=$shop_id",
                    "type='addproducts'"
                )
        ));

        $servicelimit = $servicecheck ['quantity'] - $servicecheck ['availed'];

        $this->view->assign('servicecount', $servicecount);
        $this->view->assign('quantity', $servicecheck ['quantity']);
        $this->view->assign('servicelimit', $servicelimit);
        $this->view->assign('servicedisable', $servicedisable);
        $this->view->assign('expired', $expired);
        $this->view->assign('serviceerror', $error);
        $this->view->assign('disable', $disable);
        $this->view->assign('message', $message);

        $itemsperpage  = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 15,
                'GETPOST');
        $startnum      = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $status        = FormUtil::getPassedValue('status',
                isset($args ['status']) ? $args ['status'] : null, 'GETPOST');
        $order         = FormUtil::getPassedValue('order',
                isset($args ['order']) ? $args ['order'] : 'cr_date', 'GETPOST');
        $original_sdir = FormUtil::getPassedValue('sdir',
                isset($args ['sdir']) ? $args ['sdir'] : 1, 'GETPOST');

        $searchtext = FormUtil::getPassedValue('searchtext',
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
                    'viewproducts',
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

        $sql = ' SELECT s.* , sh.shop_name FROM zselex_products AS s
                 LEFT JOIN zselex_shop AS sh ON s.shop_id=sh.shop_id
                 WHERE s.product_id IS NOT NULL ';

        // if (UserUtil::getVar('uid') != '3') {
        if (!empty($shop_id)) {
            $sql .= " AND s.shop_id='".$shop_id."'";
        }

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {

            // $sql .= " AND s.cr_uid='" . UserUtil::getVar('uid') . "'";
        }
        if (isset($status) && $status != '') {
            $sql .= ' AND s.prd_status = '.$status;
        }
        if (isset($searchtext) && $searchtext != '') {
            $sql .= " AND s.product_name LIKE '%".DataUtil::formatForStore($searchtext)."%'";
        }

        if (isset($order) && $order != '') {
            $sql .= ' ORDER BY '.$order.' '.$orderdir;
        }
        // echo $sql;
        // Get all zselex stories
        $getArgs = array(
            'sql' => $sql,
            'startnum' => $startnum,
            'numitems' => $itemsperpage
        );
        $items   = ModUtil::apiFunc('ZSELEX', 'admin', 'getAll', $getArgs);

//        for ($i = 0; $i < count($items); ++$i) {
//
//       }
        if (!empty($shop_id)) {
            $append = " AND shop_id='".$shop_id."'";
        }

        $where = " product_id IS NOT NULL $append";
        // if (UserUtil::getVar('uid') != '3') {

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            $where .= " AND cr_uid='".UserUtil::getVar('uid')."'";
        }
        if (isset($status) && $status != '') {
            $where .= ' AND prd_status = '.$status;
        }
        if (isset($searchtext) && $searchtext != '') {
            $where .= " AND product_name LIKE '%".DataUtil::formatForStore($searchtext)."%'";
        }
        $getCountArgs   = array(
            'table' => 'zselex_products',
            'where' => $where,
            'Id' => 'product_id',
            'status' => $status
        );
        $total_products = ModUtil::apiFunc('ZSELEX', 'admin', 'countElements',
                $getCountArgs);
        // Set the possible status for later use
        $itemstatus     = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );

        $productItems = array();
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
        }

        $shop_id  = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $minishop = DBUtil::selectObjectByID('zselex_minishop', $shop_id,
                'shop_id');

        // echo "<pre>"; print_r($minishop); echo "</pre>";
        // echo "<pre>"; print_r($shopsitems); echo "</pre>";
        // Assign the items to the template
        $this->view->assign('minId', $minishop ['id']);

        $this->view->assign('productItems', $productItems);
        $this->view->assign('total_products', $total_products);

        // Assign the current status filter and the possible ones
        $this->view->assign('status', $status);
        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('order', $order);

        $this->view->assign('admins',
            SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN));
        // Return the output that has been generated by this function
        return $this->view->fetch('admin/ishopproducts/'.$template);
    }

    public function deleteproduct()
    {
        $Id        = FormUtil::getPassedValue('product_id',
                isset($args ['product_id']) ? $args ['product_id'] : null,
                'REQUEST');
        $shop_id   = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $source    = FormUtil::getPassedValue('source', null, 'REQUEST');
        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));
        $user_id   = UserUtil::getVar('uid');

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            $serviceEdit = ModUtil::apiFunc('ZSELEX', 'admin',
                    'serviceCheckEdit',
                    $args        = array(
                    'shop_id' => $shop_id,
                    'item_idValue' => $Id,
                    'user_id' => $user_id,
                    'servicetable' => 'zselex_products',
                    'item_id' => 'product_id',
                    'type' => 'addproducts'
            ));

            if ($serviceEdit < 1) {
                return LogUtil::registerError($this->__f('Error! Unable to delete this %s.',
                            $this->__('Minishop Product')));
            }
        }

        $fields       = array(
            'prd_image'
        );
        $obj          = DBUtil::selectObjectByID('zselex_products', $Id,
                'product_id');
        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
        // Validate the essential parameters
        if (empty($Id)) {
            return LogUtil::registerArgsError();
        }
        $args = array(
            'table' => 'zselex_products',
            'IdValue' => $Id,
            'IdName' => 'product_id'
        );
        // Check for confirmation.
        if (empty($confirmation)) {
            // Add ZSELEX type ID
            $this->view->assign('IdValue', $Id);
            $this->view->assign('confirm_title',
                $this->__f('Delete %s', $this->__('Minishop Product')));
            $this->view->assign('confirm_msg',
                $this->__f('Do you want to delete this %s',
                    $this->__('Minishop Product')));
            $this->view->assign('shop_id', $shop_id);
            $this->view->assign('IdName', 'product_id');
            $this->view->assign('submitFunc', 'deleteproduct');
            $this->view->assign('cancelFunc', 'viewproducts');
            $this->view->assign('source', $source);

            // Return the output that has been generated by this function
            return $this->view->fetch('admin/deletecommon.tpl');
        }

        // If we get here it means that the admin has confirmed the action
        // Confirm authorisation code
        $this->checkCsrfToken();

        // Delete
        if (ModUtil::apiFunc('ZSELEX', 'admin', 'deleteItem', $args)) {
            // unlink('zselexdata/products/' . $obj['prd_image']);
            // unlink('zselexdata/products/medium/' . $obj['prd_image']);
            // unlink('zselexdata/products/thumbs/' . $obj['prd_image']);

            $where_keywords  = "WHERE type='addproducts' AND type_id=$Id";
            $delete_keywords = DBUtil::deleteWhere('zselex_keywords',
                    $where_keywords);
            unlink('zselexdata/'.$ownerName.'/products/'.$obj ['prd_image']);
            unlink('zselexdata/'.$ownerName.'/products/fullsize/'.$obj ['prd_image']);
            unlink('zselexdata/'.$ownerName.'/products/medium/'.$obj ['prd_image']);
            unlink('zselexdata/'.$ownerName.'/products/thumb/'.$obj ['prd_image']);

            $user_id       = UserUtil::getVar('uid');
            $args          = array(
                'shop_id' => $shop_id,
                'servicetype' => 'addproducts',
                'user_id' => $user_id
            );
            $deleteservice = ModUtil::apiFunc('ZSELEX', 'admin',
                    'deleteService', $args);
            // Success
            LogUtil::registerStatus($this->__('Done! Product has been deleted successfully.'));
            ModUtil::apiFunc('ZSELEX', 'admin', 'updateMinisite',
                array(
                'shop_id' => $shop_id
            ));
            // Let any hooks know that we have deleted an item
            $this->notifyHooks(new Zikula_ProcessHook('type.ui_hooks.types.process_delete',
                $Id));
        }

        if ($source == 'pop') {
            
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewproducts',
                    array(
                    'shop_id' => $shop_id
        )));
    }

    public function addproducts()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        $shop_id = FormUtil::getPassedValue('shop_id', '', 'REQUEST');
        $loguser = UserUtil::getVar('uid');
        $loguser = !empty($loguser) ? $loguser : 0;
        $user_id = $loguser;

        if (!(int) ($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)));
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $product = SessionUtil::getVar('createproduct');
        $this->view->assign('product', $product);

        // echo "<pre>"; print_r($product); echo "</pre>";

        $serviceargs       = array(
            'shop_id' => $shop_id,
            'user_id' => $user_id,
            'type' => 'addproducts'
        );
        $servicePermission = $this->serviceCheck($serviceargs);

        if ($servicePermission < 1) {
            return LogUtil::registerError($this->__('The service you try to use has to be purchased first.'));
        }

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) { // disabled check
            if ($this->serviceDisabled('addproducts') < 1) {
                return LogUtil::registerError($this->__('This service has been temporarily disabled!'));
            }
        }

        $loguser = UserUtil::getVar('uid');

        $counts = ModUtil::apiFunc('ZSELEX', 'admin',
                'getServicePurchasedCounts',
                $args   = array(
                'user_id' => $loguser,
                'shop_id' => $shop_id,
                'type' => 'addproducts'
        ));

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            $ishopargs = array(
                'table' => 'zselex_shop',
                'fields' => '',
                'where' => array(
                    'shoptype_id' => '2',
                    'shop_id' => $shop_id
                )
            );
        } else {
            $ishopargs = array(
                'table' => 'zselex_shop',
                'fields' => '',
                'where' => array(
                    'shoptype_id' => '2'
                )
            );
        }

        $ishops = ModUtil::apiFunc('ZSELEX', 'admin', 'selectItems', $ishopargs);

        // echo "<pre>"; print_r($ishops); echo "</pre>";

        $catargs  = array(
            'table' => 'zselex_category',
            'fields' => '',
            'where' => ''
        );
        $category = ModUtil::apiFunc('ZSELEX', 'admin', 'selectItems', $catargs);

        // echo "<pre>"; print_r($category); echo "</pre>";
        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('servicecount', $counts);
        $this->view->assign('ishop', $ishops);
        $this->view->assign('category', $category);
        if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            return $this->view->fetch('admin/ishopproducts/addproduct.tpl');
        } else {
            return $this->view->fetch('admin/ishopproducts/addproductuser.tpl');
        }
    }

    public function modifyproduct()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        $prdId   = FormUtil::getPassedValue('id',
                isset($args ['id']) ? $args ['id'] : null, 'GETPOST');
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $loguser = UserUtil::getVar('uid');

        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));
        $this->view->assign('ownerName', $ownerName);

        $serviceEdit = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceCheckEdit',
                $args        = array(
                'shop_id' => $shop_id,
                'item_idValue' => $prdId,
                'user_id' => $loguser,
                'servicetable' => 'zselex_products',
                'item_id' => 'product_id',
                'type' => 'addproducts'
        ));

        if ($serviceEdit < 1) {
            // return LogUtil::registerError($this->__f('Error! Unable to edit this %s.', $this->__('Minishop Product')));
        }

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) { // disabled check
            if ($this->serviceDisabled('addproducts') < 1) {
                return LogUtil::registerError($this->__('This service has been temporarily disabled!'));
            }
        }

        $args = array(
            'table' => 'zselex_products',
            'IdValue' => $prdId,
            'IdName' => 'product_id'
        );
        // Get the news type in the db
        // $item = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $args);

        $sql = "SELECT p.* , s.shop_name FROM zselex_products p  LEFT JOIN zselex_shop s ON p.shop_id=s.shop_id  WHERE p.product_id=$prdId";

        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);
        $item      = $results->fetch();

        $ishopargs = array(
            'table' => 'zselex_shop',
            'fields' => '',
            'where' => array(
                'shoptype_id' => '2'
            )
        );
        $ishops    = ModUtil::apiFunc('ZSELEX', 'admin', 'selectItems',
                $ishopargs);

        $catargs  = array(
            'table' => 'zselex_category',
            'fields' => '',
            'where' => ''
        );
        $category = ModUtil::apiFunc('ZSELEX', 'admin', 'selectItems', $catargs);

        // echo "<pre>"; print_r($item); echo "</pre>"; exit;

        $loguser = UserUtil::getVar('uid');

        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('product', $item);
        $this->view->assign('ishop', $ishops);
        $this->view->assign('category', $category);
        $this->view->assign('shop', $shop_id);

        return $this->view->fetch('admin/ishopproducts/addproduct.tpl');
    }

    public function submitproduct()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        $formElements = FormUtil::getPassedValue('formElements',
                isset($args ['formElements']) ? $args ['formElements'] : null,
                'POST');
        $formElements = $this->purifyHtml($formElements);
        // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;
        $shop_id      = $formElements ['shop_id'];
        // $source = $formElements['source'];

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $this->checkCsrfToken();

        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));
        $files     = FormUtil::getPassedValue('files', '', 'FILES');

        if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) {
            $serviceargs       = array(
                'shop_id' => $shop_id,
                'user_id' => $user_id,
                'type' => 'addproducts'
            );
            $servicePermission = $this->servicePermission($serviceargs);
            if ($servicePermission ['perm'] < 1) {
                return LogUtil::registerError($servicePermission ['message']);
            }

            // make directories if not exist.
            if (!is_dir('zselexdata/'.$ownerName)) {
                mkdir('zselexdata/'.$ownerName, 0775);
                chmod('zselexdata/'.$ownerName, 0775);
            }

            if (!is_dir('zselexdata/'.$ownerName.'/products')) {
                mkdir('zselexdata/'.$ownerName.'/products', 0775);
                chmod('zselexdata/'.$ownerName.'/products', 0775);
            }
            if (!is_dir('zselexdata/'.$ownerName.'/products/fullsize')) {
                mkdir('zselexdata/'.$ownerName.'/products/fullsize', 0775);
                chmod('zselexdata/'.$ownerName.'/products/fullsize', 0775);
            }
            if (!is_dir('zselexdata/'.$ownerName.'/products/medium')) {
                mkdir('zselexdata/'.$ownerName.'/products/medium', 0775);
                chmod('zselexdata/'.$ownerName.'/products/medium', 0775);
            }
            if (!is_dir('zselexdata/'.$ownerName.'/products/thumb')) {
                mkdir('zselexdata/'.$ownerName.'/products/thumb', 0775);
                chmod('zselexdata/'.$ownerName.'/products/thumb', 0775);
            }

            $validation_rules = array(
                'product_name' => array(
                    'required' => true,
                    'value' => $formElements ['name'],
                    'label' => 'Product Name'
                )
            );

            $validationerror = ZSELEX_Util::validate($validation_rules);

            if ($validationerror !== false) {
                // log the error found if any
                if ($validationerror !== false) {
                    LogUtil::registerError(nl2br($validationerror));
                }
                SessionUtil::setVar('createproduct', $formElements);

                $this->redirect(ModUtil::url('ZSELEX', 'admin', 'addproducts',
                        array(
                        'shop_id' => $shop_id
                )));
            } else {
                // As we're not previewing the item let's remove it from the session
                SessionUtil::delVar('createproduct');
            }

            if ($files ['error'] < 1) {
                $random_digit  = rand(0000, 9999);
                $new_file_name = time().'_'.$files ['name'];
                // echo $new_file_name; exit;
                $newNme        = array(
                    'newName' => $new_file_name
                );
                $file          = array();
                $file          = $files + $newNme;
            }

            $urltitle = strtolower($formElements ['name']);
            $urltitle = str_replace(' ', '-', $urltitle);
            // $final_urltitle = $this->increment_url($title = $urltitle, $table = 'zselex_products', $field = 'urltitle');

            $args_url       = array(
                'table' => 'zselex_products',
                'title' => $urltitle,
                'field' => 'urltitle'
            );
            $final_urltitle = $this->increment_url($args_url);

            // echo "<pre>"; print_r($files); echo "</pre>"; exit;
            $item = array(
                'shop_id' => $formElements ['ishop'],
                'product_name' => !empty($formElements ['name']) ? $formElements ['name']
                        : '',
                'urltitle' => $final_urltitle,
                'prd_description' => $formElements ['description'],
                'keywords' => $formElements ['keywords'],
                'category_id' => isset($formElements ['category']) ? $formElements ['category']
                        : 0,
                'prd_price' => isset($formElements ['price']) ? $formElements ['price']
                        : 0,
                'prd_quantity' => isset($formElements ['quantity']) ? $formElements ['quantity']
                        : 0,
                'prd_image' => $new_file_name,
                'prd_status' => isset($formElements ['status']) ? $formElements ['status']
                        : 0
            );

            $keywords = $formElements ['keywords'];
            $args     = array(
                'table' => 'zselex_products',
                'element' => $item,
                'Id' => 'product_id'
            );
            $result   = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement',
                    $args);
            $InsertId = DBUtil::getInsertID($args ['table'], $args ['Id']);
            if ($result == true) {
                // /////////////////KEYWORDS//////////////////////
                if (!empty($keywords)) {
                    $keywords_for_search = str_replace(',', ' ', $keywords);
                    $keywords_for_search = explode(' ', $keywords_for_search);

                    foreach ($keywords_for_search as $keyword) {
                        $keywordExist = ModUtil::apiFunc('ZSELEX', 'admin',
                                'getCount',
                                $args         = array(
                                'table' => 'zselex_keywords',
                                'where' => "keyword='".$keyword."'"
                        ));

                        if ($keywordExist < 1) {
                            if (!empty($keyword)) {
                                $keyword_item   = array(
                                    'keyword' => $keyword,
                                    'type' => 'addproducts',
                                    'type_id' => $InsertId,
                                    'shop_id' => $formElements ['ishop']
                                );
                                $keyword_args   = array(
                                    'table' => 'zselex_keywords',
                                    'element' => $keyword_item,
                                    'Id' => 'keyword_id'
                                );
                                $result_keyword = ModUtil::apiFunc('ZSELEX',
                                        'admin', 'createElement', $keyword_args);
                            }
                        }
                    }
                }

                // ///////////////////////////////////////////////

                if ($files ['error'] < 1) {
                    $size      = $files ['size'];
                    $diskquota = ModUtil::apiFunc('ZSELEX', 'admin',
                            'checkDiskquota',
                            $args      = array(
                            'shop_id' => $formElements ['ishop']
                    ));
                    $allsize   = $diskquota ['sizeused'] + $size;
                    // $destination = 'zselexdata/products';
                    if ($diskquota ['count'] < 1) {
                        LogUtil::registerStatus($this->__('Image was not uploaded.').'. '.$diskquota ['message']);
                    } elseif ($diskquota ['limitover'] < 1) {
                        LogUtil::registerStatus($this->__('Image was not uploaded.').'. '.$diskquota ['message']);
                    } elseif ($allsize >= $diskquota ['sizelimit']) {
                        LogUtil::registerStatus($this->__('Image was not uploaded.').'. '.$this->__('Your disquota doesnt have sufficient space'));
                    } else {
                        $destination = 'zselexdata/'.$ownerName.'/products';
                        $this->uploadImage($file, $destination);
                    }
                }

                $user_id          = UserUtil::getVar('uid');
                $serviceupdatearg = array(
                    'user_id' => $user_id,
                    'type' => 'addproducts',
                    'shop_id' => $formElements ['ishop']
                );
                $serviceavailed   = ModUtil::apiFunc('ZSELEX', 'admin',
                        'updateServiceUsed', $serviceupdatearg);
            } // /

            LogUtil::registerStatus($this->__('Done! Product has been created successfully.'));
        } else { // Update
            $validation_rules = array(
                'product_name' => array(
                    'required' => true,
                    'value' => $formElements ['name'],
                    'label' => 'Product Name'
                )
            );

            $validationerror = ZSELEX_Util::validate($validation_rules);

            if ($validationerror !== false) {
                // log the error found if any
                if ($validationerror !== false) {
                    LogUtil::registerError(nl2br($validationerror));
                }
                SessionUtil::setVar('createproduct', $formElements);

                $this->redirect(ModUtil::url('ZSELEX', 'admin', 'addproducts',
                        array(
                        'shop_id' => $shop_id
                )));
            } else {
                // As we're not previewing the item let's remove it from the session
                SessionUtil::delVar('createproduct');
            }
            // make directories if not exist.
            if (!is_dir('zselexdata/'.$ownerName)) {
                mkdir('zselexdata/'.$ownerName, 0775);
                chmod('zselexdata/'.$ownerName, 0775);
            }

            if (!is_dir('zselexdata/'.$ownerName.'/products')) {
                mkdir('zselexdata/'.$ownerName.'/products', 0775);
                chmod('zselexdata/'.$ownerName.'/products', 0775);
            }
            if (!is_dir('zselexdata/'.$ownerName.'/products/fullsize')) {
                mkdir('zselexdata/'.$ownerName.'/products/fullsize', 0775);
                chmod('zselexdata/'.$ownerName.'/products/fullsize', 0775);
            }
            if (!is_dir('zselexdata/'.$ownerName.'/products/medium')) {
                mkdir('zselexdata/'.$ownerName.'/products/medium', 0775);
                chmod('zselexdata/'.$ownerName.'/products/medium', 0775);
            }
            if (!is_dir('zselexdata/'.$ownerName.'/products/thumb')) {
                mkdir('zselexdata/'.$ownerName.'/products/thumb', 0775);
                chmod('zselexdata/'.$ownerName.'/products/thumb', 0775);
            }

            $files = FormUtil::getPassedValue('files', '', 'FILES');

            if ($files ['error'] < 1) {
                // echo "<pre>"; print_r($files); echo "</pre>"; exit;
                $random_digit  = rand(0000, 9999);
                $new_file_name = time().'_'.$files ['name'];
                // echo $new_file_name; exit;
                $newNme        = array(
                    'newName' => $new_file_name
                );
                $file          = array();
                $file          = $files + $newNme;
            }

            $urltitle = strtolower($formElements ['name']);
            $urltitle = str_replace(' ', '-', $urltitle);
            // $final_urltitle = $this->increment_url($title = $urltitle, $table = 'zselex_products', $field = 'urltitle');

            $urlCount = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount',
                    $args     = array(
                    'table' => 'zselex_products',
                    'where' => "urltitle='".$urltitle."' AND product_id!=$shop_id"
            ));

            if ($urlCount > 0) {
                // $final_urltitle = $this->increment_url($title = $urltitle, $table = 'zselex_products', $field = 'urltitle');
                $args_url       = array(
                    'table' => 'zselex_products',
                    'title' => $urltitle,
                    'field' => 'urltitle'
                );
                $final_urltitle = $this->increment_url($args_url);
            } else {
                $final_urltitle = $urltitle;
            }

            // echo $formElements['hiddenImage']; exit;
            $item       = array(
                'product_id' => $formElements ['elemId'],
                'shop_id' => $formElements ['ishop'],
                'product_name' => !empty($formElements ['name']) ? $formElements ['name']
                        : '',
                'urltitle' => $final_urltitle,
                'prd_description' => $formElements ['description'],
                'keywords' => $formElements ['keywords'],
                'category_id' => isset($formElements ['category']) ? $formElements ['category']
                        : 0,
                'prd_price' => isset($formElements ['price']) ? $formElements ['price']
                        : 0,
                'prd_quantity' => isset($formElements ['quantity']) ? $formElements ['quantity']
                        : 0,
                'prd_image' => empty($files ['error']) ? $new_file_name : $formElements ['hiddenImage'],
                'prd_status' => isset($formElements ['status']) ? $formElements ['status']
                        : 0
            );
            $keywords   = $formElements ['keywords'];
            // echo "<pre>"; print_r($item); echo "</pre>"; exit;
            $InsertId   = $formElements ['elemId'];
            $updateargs = array(
                'table' => 'zselex_products',
                'IdValue' => $InsertId,
                'IdName' => 'product_id',
                'element' => $item
            );

            $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement',
                    $updateargs);

            if ($result == true) {
                $where = "WHERE type='addproducts' AND type_id=$InsertId";
                if (DBUtil::deleteWhere('zselex_keywords', $where)) {
                    if (!empty($keywords)) {
                        $keywords_for_search = str_replace(',', ' ', $keywords);
                        $keywords_for_search = explode(' ', $keywords_for_search);
                        foreach ($keywords_for_search as $keyword) {
                            $keywordExist = ModUtil::apiFunc('ZSELEX', 'admin',
                                    'getCount',
                                    $args         = array(
                                    'table' => 'zselex_keywords',
                                    'where' => "keyword='".$keyword."'"
                            ));

                            if ($keywordExist < 1) {
                                $keyword_item   = array(
                                    'keyword' => $keyword,
                                    'type' => 'addproducts',
                                    'type_id' => $InsertId,
                                    'shop_id' => $formElements ['ishop']
                                );
                                $keyword_args   = array(
                                    'table' => 'zselex_keywords',
                                    'element' => $keyword_item,
                                    'Id' => 'keyword_id'
                                );
                                $result_keyword = ModUtil::apiFunc('ZSELEX',
                                        'admin', 'createElement', $keyword_args);
                            }
                        }
                    }
                }

                if ($files ['error'] < 1) {
                    $size          = $files ['size'];
                    $existingImage = $formElements ['hiddenImage'];
                    if (!empty($existingImage)) {
                        $existingFileSize = filesize('zselexdata/'.$ownerName.'/minisiteimages/'.$existingImage);
                    } else {
                        $existingFileSize = 0;
                    }
                    $diskquota = ModUtil::apiFunc('ZSELEX', 'admin',
                            'checkDiskquota',
                            $args      = array(
                            'shop_id' => $formElements ['ishop']
                    ));

                    $allsize  = $diskquota ['sizeused'] + $size;
                    $allsize1 = $diskquota ['sizeused'] - $existingFileSize;
                    $allsizes = $allsize1 + $size;

                    if ($allsizes <= $diskquota ['sizelimit']) {
                        // return LogUtil::registerError($this->__("File was not uploaded. You need more disquoata to upload this file. Please upgrade."));
                        // LogUtil::registerStatus($this->__('File was not uploaded. You need more disquoata to upload this file. Please upgrade.'));

                        if (file_exists('zselexdata/'.$ownerName.'/products/'.$formElements ['hiddenImage'])) {
                            unlink('zselexdata/'.$ownerName.'/products/'.$formElements ['hiddenImage']);
                        }
                        if (file_exists('zselexdata/'.$ownerName.'/products/fullsize/'.$formElements ['hiddenImage'])) {
                            unlink('zselexdata/'.$ownerName.'/products/fullsize/'.$formElements ['hiddenImage']);
                        }
                        if (file_exists('zselexdata/'.$ownerName.'/products/medium/'.$formElements ['hiddenImage'])) {
                            unlink('zselexdata/'.$ownerName.'/products/medium/'.$formElements ['hiddenImage']);
                        }
                        if (file_exists('zselexdata/'.$ownerName.'/products/thumb/'.$formElements ['hiddenImage'])) {
                            unlink('zselexdata/'.$ownerName.'/products/thumb/'.$formElements ['hiddenImage']);
                        }
                        // unlink('zselexdata/products/thumbs/' . $formElements['hiddenImage']);
                        // $destination = 'zselexdata/products';
                        // $this->uploadMultipleFile($file, $destination);
                        $destination = 'zselexdata/'.$ownerName.'/products';
                        $this->uploadImage($file, $destination);
                    } else {
                        LogUtil::registerStatus($this->__('File was not uploaded. You need more disquoata to upload this file. Please upgrade.'));
                    }
                }

                LogUtil::registerStatus($this->__('Done! Product has been updated successfully.'));
            } // //
        }

        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewproducts',
                array(
                'shop_id' => $formElements ['ishop']
        )));
    }

    public function createfolder($args)
    {
        $ownerName = $args ['ownerName'];
        $itemName  = $args ['itemName'];
        // make directories if not exist.
        if (!is_dir('zselexdata/'.$ownerName)) {
            mkdir('zselexdata/'.$ownerName, 0775);
            chmod('zselexdata/'.$ownerName, 0775);
        }

        if (!is_dir('zselexdata/'.$ownerName.'/'.$itemName)) {
            mkdir('zselexdata/'.$ownerName.'/'.$itemName, 0775);
            chmod('zselexdata/'.$ownerName.'/'.$itemName, 0775);
        }
        if (!is_dir('zselexdata/'.$ownerName.'/'.$itemName.'/fullsize')) {
            mkdir('zselexdata/'.$ownerName.'/products/fullsize', 0775);
            chmod('zselexdata/'.$ownerName.'/products/fullsize', 0775);
        }
        if (!is_dir('zselexdata/'.$ownerName.'/products/medium')) {
            mkdir('zselexdata/'.$ownerName.'/products/medium', 0775);
            chmod('zselexdata/'.$ownerName.'/products/medium', 0775);
        }
        if (!is_dir('zselexdata/'.$ownerName.'/products/thumb')) {
            mkdir('zselexdata/'.$ownerName.'/products/thumb', 0775);
            chmod('zselexdata/'.$ownerName.'/products/thumb', 0775);
        }

        return true;
    }

    public function deleteshop($args)
    {
        $shop_id   = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : null, 'REQUEST');
        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));

        if ((empty($shop_id) || !is_numeric($shop_id))) {
            return LogUtil::registerArgsError();
        }

        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
        if (empty($confirmation)) {
            $this->view->assign('IdValue', $shop_id);
            $this->view->assign('confirm_title',
                $this->__f('Delete %s', $this->__('Minisite')));
            $this->view->assign('confirm_msg',
                $this->__f('Do you want to delete this %s',
                    $this->__('Minisite')));
            $this->view->assign('IdName', 'shop_id');
            $this->view->assign('submitFunc', 'deleteshop');
            $this->view->assign('cancelFunc', 'viewshop');
            // Return the output that has been generated by this function
            // return $this->view->fetch('admin/deleteshop.tpl');
            return $this->view->fetch('admin/deletecommon.tpl');
        }
        $this->checkCsrfToken();

        $result = $this->_deleteShopAndItems(array(
            'shop_id' => $shop_id
        ));
        // Delete
        if ($result) {
            // Success
            LogUtil::registerStatus($this->__('Done! Minisite has been deleted successfully.'));
            // Let any hooks know that we have deleted an item
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshop'));
    }

    public function deleteshops($args)
    {
        $shop_id = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : null, 'REQUEST');

        if (!isset($shop_id) || empty($shop_id)) {
            $shop_id = $args ['shop_id'];
        }
        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));

        if ((empty($shop_id) || !is_numeric($shop_id))) {
            return LogUtil::registerArgsError();
        }

        $result = $this->_deleteShopAndItems(array(
            'shop_id' => $shop_id
        ));

        // echo "<pre>"; print_r($getCategories); echo "</pre>"; exit;

        if ($result) {
            return true;
        }
    }

    public function _deleteShopAndItems($args)
    {
        $shop_id  = $args ['shop_id'];
        $shopRepo = $this->entityManager->getRepository('ZSELEX_Entity_Shop');

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            $item = array(
                'shop_id' => $shop_id,
                'status' => 0
            );

            $result = $shopRepo->updateEntity(array(
                'entity' => 'ZSELEX_Entity_Shop',
                'fields' => $item,
                'where' => array(
                    'a.shop_id' => $shop_id
                )
            ));

            return true;
        }

        $shopArgs = array(
            'entity' => 'ZSELEX_Entity_Shop',
            'joins' => array(
                'LEFT JOIN a.minishops m',
                'LEFT JOIN a.shop_images imgs',
                'LEFT JOIN a.shop_products prod',
                'LEFT JOIN a.shop_banners bnr',
                'LEFT JOIN a.shop_announcements ann',
                'LEFT JOIN a.shop_events evnt',
                'LEFT JOIN a.shop_pdfs pdf',
                'LEFT JOIN a.shop_employees emp',
                'LEFT JOIN a.shop_gallery gal'
            ),
            'fields' => array(
                'm.id as minId',
                'm.shoptype as mshoptype',
                'prod.product_id',
                'bnr.shop_banner_id',
                'imgs.file_id',
                'emp.emp_id',
                'pdf.pdf_id',
                'gal.gallery_id',
                'evnt.shop_event_id'
            ),
            'where' => array(
                'a.shop_id' => $shop_id
            ),
            'groupby' => 'a.shop_id'
        );

        $shopDetails = $shopRepo->get($shopArgs);

        if ($shopDetails ['minId'] != '') {
            if ($shopDetails ['mshoptype'] == 'iSHOP') {
                $product = $this->entityManager->getRepository('ZSELEX_Entity_Product')->getAll(array(
                    'entity' => 'ZSELEX_Entity_Product',
                    'fields' => array(
                        'a.prd_image'
                    ),
                    'where' => array(
                        'a.shop' => $shop_id
                    )
                ));

                // echo "<pre>"; print_r($product); echo "</pre>"; exit;

                foreach ($product as $item) {
                    if (file_exists('zselexdata/'.$ownerName.'/products/'.$item ['prd_image'])) {
                        @unlink('zselexdata/'.$ownerName.'/products/'.$item ['prd_image']);
                    }
                    if (file_exists('zselexdata/'.$ownerName.'/products/fullsize/'.$item ['prd_image'])) {
                        @unlink('zselexdata/'.$ownerName.'/products/fullsize/'.$item ['prd_image']);
                    }
                    if (file_exists('zselexdata/'.$ownerName.'/products/medium/'.$item ['prd_image'])) {
                        @unlink('zselexdata/'.$ownerName.'/products/medium/'.$item ['prd_image']);
                    }
                    if (file_exists('zselexdata/'.$ownerName.'/products/thumb/'.$item ['prd_image'])) {
                        @unlink('zselexdata/'.$ownerName.'/products/thumb/'.$item ['prd_image']);
                    }
                }

                $delprod_args  = array(
                    'entity' => 'ZSELEX_Entity_Product',
                    'where' => array(
                        'a.shop' => $shop_id
                    )
                );
                $deleteProduct = $shopRepo->deleteEntity($delprod_args);
            } elseif ($shopDetails ['mshoptype'] == 'zSHOP') {

                // DBUtil::deleteObjectById('zselex_zenshop', $shop_id, 'shop_id');
                $delzen_args   = array(
                    'entity' => 'ZSELEX_Entity_ZenShop',
                    'where' => array(
                        'a.shop' => $shop_id
                    )
                );
                $deleteZenCart = $shopRepo->deleteEntity($delzen_args);
            }

            $delMiniShop_args = array(
                'entity' => 'ZSELEX_Entity_MiniShop',
                'where' => array(
                    'a.shop' => $shop_id
                )
            );
            $delMiniShop      = $shopRepo->deleteEntity($delMiniShop_args);
        }

        $delMiniShop_args = array(
            'entity' => 'ZSELEX_Entity_MiniShop',
            'where' => array(
                'a.shop' => $shop_id
            )
        );
        $delMiniShop      = $shopRepo->deleteEntity($delMiniShop_args);

        $delOwner_args = array(
            'entity' => 'ZSELEX_Entity_ShopOwner',
            'where' => array(
                'a.shop' => $shop_id
            )
        );
        $delOwner      = $shopRepo->deleteEntity($delOwner_args);

        $delAdmin_args = array(
            'entity' => 'ZSELEX_Entity_ShopAdmin',
            'where' => array(
                'a.shop' => $shop_id
            )
        );
        $delAdmin      = $shopRepo->deleteEntity($delAdmin_args);

        /* Delete Images */
        if ($shopDetails ['file_id']) {
            $s = $this->entityManager->getRepository('ZSELEX_Entity_MinisiteImage')->getAll(array(
                'entity' => 'ZSELEX_Entity_MinisiteImage',
                'fields' => array(
                    'a.name'
                ),
                'where' => array(
                    'a.shop' => $shop_id
                )
            ));

            foreach ($s as $data) { // delete all shop images
                if (file_exists('zselexdata/'.$ownerName.'/minisiteimages/'.$data ['name'])) {
                    @unlink('zselexdata/'.$ownerName.'/minisiteimages/'.$data ['name']);
                }
                if (file_exists('zselexdata/'.$ownerName.'/minisiteimages/fullsize/'.$data ['name'])) {
                    @unlink('zselexdata/'.$ownerName.'/minisiteimages/fullsize/'.$data ['name']);
                }
                if (file_exists('zselexdata/'.$ownerName.'/minisiteimages/medium/'.$data ['name'])) {
                    @unlink('zselexdata/'.$ownerName.'/minisiteimages/medium/'.$data ['name']);
                }
                if (file_exists('zselexdata/'.$ownerName.'/minisiteimages/thumb/'.$data ['name'])) {
                    @unlink('zselexdata/'.$ownerName.'/minisiteimages/thumb/'.$data ['name']);
                }
            }

            $delImages_args = array(
                'entity' => 'ZSELEX_Entity_MinisiteImage',
                'where' => array(
                    'a.shop' => $shop_id
                )
            );
            $delImages      = $shopRepo->deleteEntity($delImages_args);
        }

        /* Delete Gallery */
        if ($shopDetails ['gallery_id']) {
            $galleyImages = $this->entityManager->getRepository('ZSELEX_Entity_Gallery')->getAll(array(
                'entity' => 'ZSELEX_Entity_Gallery',
                'fields' => array(
                    'a.image_name'
                ),
                'where' => array(
                    'a.shop' => $shop_id
                )
            ));

            foreach ($galleyImages as $data) { // delete all shop images
                @unlink('zselexdata/'.$ownerName.'/minisitegallery/'.$data ['image_name']);
                @unlink('zselexdata/'.$ownerName.'/minisitegallery/fullsize/'.$data ['image_name']);
                @unlink('zselexdata/'.$ownerName.'/minisitegallery/medium/'.$data ['image_name']);
                @unlink('zselexdata/'.$ownerName.'/minisitegallery/thumb/'.$data ['image_name']);
            }

            $delGallery_args = array(
                'entity' => 'ZSELEX_Entity_Gallery',
                'where' => array(
                    'a.shop' => $shop_id
                )
            );
            $delGallery      = $shopRepo->deleteEntity($delGallery_args);
        }

        if ($shopDetails ['shop_event_id']) {
            /* Delete Events */
            $events = $this->entityManager->getRepository('ZSELEX_Entity_Event')->getAll(array(
                'entity' => 'ZSELEX_Entity_Event',
                'fields' => array(
                    'a.event_doc',
                    'a.event_image'
                ),
                'where' => array(
                    'a.shop' => $shop_id
                )
            ));
            foreach ($events as $event) { // delete all shop images
                if ($event ['event_image'] != '') {
                    @unlink('zselexdata/'.$ownerName.'/events/'.$event ['event_image']);
                    @unlink('zselexdata/'.$ownerName.'/events/fullsize/'.$event ['event_image']);
                    @unlink('zselexdata/'.$ownerName.'/events/medium/'.$event ['event_image']);
                    @unlink('zselexdata/'.$ownerName.'/events/thumb/'.$event ['event_image']);
                }
                if ($event ['event_doc'] != '') {
                    @unlink('zselexdata/'.$ownerName.'/events/docs/'.$event ['event_doc']);
                }
            }

            $delEvent_args = array(
                'entity' => 'ZSELEX_Entity_Event',
                'where' => array(
                    'a.shop' => $shop_id
                )
            );
            $delEvent      = $shopRepo->deleteEntity($delEvent_args);
        }

        /* Delete Pdf */
        if ($shopDetails ['pdf_id']) {
            $pdfs = $this->entityManager->getRepository('ZSELEX_Entity_Pdf')->getAll(array(
                'entity' => 'ZSELEX_Entity_Pdf',
                'fields' => array(
                    'a.pdf_image',
                    'a.pdf_name'
                ),
                'where' => array(
                    'a.shop' => $shop_id
                )
            ));
            foreach ($pdfs as $pdf) { // delete all shop images
                @unlink('zselexdata/'.$ownerName.'/pdfupload/'.$pdf ['pdf_name']);
                @unlink('zselexdata/'.$ownerName.'/pdfupload/thumb/'.$pdf ['pdf_image'].'.jpg');
            }

            $delPdf_args = array(
                'entity' => 'ZSELEX_Entity_Pdf',
                'where' => array(
                    'a.shop' => $shop_id
                )
            );
            $delPdf      = $shopRepo->deleteEntity($delPdf_args);
        }

        /* Delete Employee */
        if ($shopDetails ['emp_id']) {
            $employees = $this->entityManager->getRepository('ZSELEX_Entity_Employee')->getAll(array(
                'entity' => 'ZSELEX_Entity_Employee',
                'fields' => array(
                    'a.emp_image'
                ),
                'where' => array(
                    'a.shop' => $shop_id
                )
            ));
            foreach ($employees as $employee) { // delete all shop images
                @unlink('zselexdata/'.$ownerName.'/employees/'.$employee ['emp_image']);
                @unlink('zselexdata/'.$ownerName.'/employees/fullsize/'.$employee ['emp_image']);
                @unlink('zselexdata/'.$ownerName.'/employees/medium/'.$employee ['emp_image']);
                @unlink('zselexdata/'.$ownerName.'/employees/thumb/'.$employee ['emp_image']);
            }

            $delEmp_args = array(
                'entity' => 'ZSELEX_Entity_Employee',
                'where' => array(
                    'a.shop' => $shop_id
                )
            );
            $delEmp      = $shopRepo->deleteEntity($delEmp_args);
        }

        if ($shopDetails ['shop_banner_id']) {
            $Banner = $this->entityManager->getRepository('ZSELEX_Entity_Banner')->get(array(
                'entity' => 'ZSELEX_Entity_Banner',
                'fields' => array(
                    'a.banner_image'
                ),
                'where' => array(
                    'a.shop' => $shop_id
                )
            ));
            @unlink('zselexdata/'.$ownerName.'/banner/resized/'.$Banner ['banner_image']);

            $delBanner_args = array(
                'entity' => 'ZSELEX_Entity_Banner',
                'where' => array(
                    'a.shop' => $shop_id
                )
            );
            $delBanner      = $shopRepo->deleteEntity($delBanner_args);
        }

        $delSerShop_args = array(
            'entity' => 'ZSELEX_Entity_ServiceShop',
            'where' => array(
                'a.shop' => $shop_id
            )
        );
        $delSerShop      = $shopRepo->deleteEntity($delSerShop_args);

        $delServiceDemo_args = array(
            'entity' => 'ZSELEX_Entity_ServiceDemo',
            'where' => array(
                'a.shop' => $shop_id
            )
        );
        $delSerShop          = $shopRepo->deleteEntity($delSerShop_args);

        $delSerShopBundle = $shopRepo->deleteEntity(array(
            'entity' => 'ZSELEX_Entity_ServiceBundle',
            'where' => array(
                'a.shop' => $shop_id
            )
        ));

        $delKeywrd = $shopRepo->deleteEntity(array(
            'entity' => 'ZSELEX_Entity_Keyword',
            'where' => array(
                'a.shop' => $shop_id
            )
        ));

        $delAd = $shopRepo->deleteEntity(array(
            'entity' => 'ZSELEX_Entity_Advertise',
            'where' => array(
                'a.shop' => $shop_id
            )
        ));

        $delShopCategories = $shopRepo->deleteShopCategories(array(
            // 'categories' => $getCategories,
            'shop_id' => $shop_id
        ));

        $delShopBranches = $shopRepo->deleteShopBranches(array(
            // 'categories' => $getCategories,
            'shop_id' => $shop_id
        ));

        $delDirectPay = $this->entityManager->getRepository('ZPayment_Entity_DirectpaySetting')->deleteEntity(array(
            'entity' => 'ZPayment_Entity_DirectpaySetting',
            'where' => array(
                'a.shop_id' => $shop_id
            )
        ));

        $delNetaxept = $this->entityManager->getRepository('ZPayment_Entity_Netaxept')->deleteEntity(array(
            'entity' => 'ZPayment_Entity_NetaxeptSetting',
            'where' => array(
                'a.shop_id' => $shop_id
            )
        ));

        $delPaypal = $this->entityManager->getRepository('ZPayment_Entity_PaypalSetting')->deleteEntity(array(
            'entity' => 'ZPayment_Entity_PaypalSetting',
            'where' => array(
                'a.shop_id' => $shop_id
            )
        ));

        $delQuickPay = $this->entityManager->getRepository('ZPayment_Entity_QuickPaySetting')->deleteEntity(array(
            'entity' => 'ZPayment_Entity_QuickPaySetting',
            'where' => array(
                'a.shop_id' => $shop_id
            )
        ));

        $delShopSetting = $this->entityManager->getRepository('ZSELEX_Entity_ShopSetting')->deleteEntity(array(
            'entity' => 'ZSELEX_Entity_ShopSetting',
            'where' => array(
                'a.shop' => $shop_id
            )
        ));

        $result = $shopRepo->deleteEntity(array(
            'entity' => 'ZSELEX_Entity_Shop',
            'where' => array(
                'a.shop_id' => $shop_id
            )
        ));

        return true;
    }

    public function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != '.' && $object != '..') {
                    if (filetype($dir.'/'.$object) == 'dir') {
                        rrmdir($dir.'/'.$object);
                    } else {
                        unlink($dir.'/'.$object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    // /**
    // * delete item
    // *
    // * @param int 'shop_id' the id of the type item to be deleted
    // * @param int 'objectid' generic object id maps to shop_id if present
    // * @param int 'confirmation' confirmation that this type item can be deleted
    // * @author
    // * @return mixed HTML string if no confirmation, true if delete successful, false otherwise
    // */
    // public function deleteshop($args) {
    // $shop_id = FormUtil::getPassedValue('shop_id', isset($args['shop_id']) ? $args['shop_id'] : null, 'REQUEST');
    // $objectid = FormUtil::getPassedValue('objectid', isset($args['objectid']) ? $args['objectid'] : null, 'REQUEST');
    // $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
    // if (!empty($objectid)) {
    // $shop_id = $objectid;
    // }
    //
	// // Validate the essential parameters
    // if (empty($shop_id)) {
    // return LogUtil::registerArgsError();
    // }
    // $args = array('table' => 'zselex_shop', 'IdValue' => $shop_id, 'IdName' => 'shop_id');
    //
	// // Get the type type
    // $item = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $args);
    //
	//
	// if ($item == false) {
    // return LogUtil::registerError($this->__('Error! Item not found.'), 403);
    // }
    //
	// $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::', $item['cr_uid'] . '::' . $item['shop_id'], ACCESS_DELETE), LogUtil::getErrorMsgPermission());
    //
	// // Check for confirmation.
    // if (empty($confirmation)) {
    // // Add ZSELEX type ID
    // $this->view->assign('IdValue', $shop_id);
    // $this->view->assign('IdName', 'shop_id');
    // $this->view->assign('submitFunc', 'deleteshop');
    // $this->view->assign('cancelFunc', 'viewshop');
    //
	// // Return the output that has been generated by this function
    // return $this->view->fetch('admin/delete.tpl');
    // }
    //
	// // If we get here it means that the admin has confirmed the action
    // // Confirm authorisation code
    // $this->checkCsrfToken();
    //
	// // Delete
    // if (ModUtil::apiFunc('ZSELEX', 'admin', 'deleteElement', $args)) {
    //
	//
	// $sql = "DELETE FROM zselex_parent WHERE childType = 'SHOP' AND childId='" . $args['IdValue'] . "'";
    // $statement = Doctrine_Manager::getInstance()->connection();
    // $results = $statement->execute($sql);
    // // Success
    // LogUtil::registerStatus($this->__('Done! Minisite has been deleted successfully.'));
    //
	// // Let any hooks know that we have deleted an item
    // $this->notifyHooks(new Zikula_ProcessHook('type.ui_hooks.types.process_delete', $shop_id));
    // }
    //
	// return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshop'));
    // }
    public function submitadvertiseuseradmin($args)
    {
        // Get parameters cr_date whatever input we need
        // echo "hellllooo"; exit;
        $formElements = FormUtil::getPassedValue('formElements',
                isset($args ['formElements']) ? $args ['formElements'] : null,
                'POST');

        // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;
        // echo "hellooooozz"; exit;
        $item = array(
            'name' => $formElements ['elemtName'],
            'description' => isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                    : '',
            'keywords' => isset($formElements ['keywords']) ? $formElements ['keywords']
                    : ''
        );

        $InsertId = $formElements ['elemId'];
        // update the type
        // echo "<pre>"; print_r($item); echo "</pre>"; exit;

        $updateargs = array(
            'table' => 'zselex_advertise',
            'IdValue' => $InsertId,
            'IdName' => 'advertise_id',
            'element' => $item
        );
        // echo "<pre>"; print_r($updateargs); echo "</pre>"; exit;
        $result     = ModUtil::apiFunc('ZSELEX', 'admin', 'updateSingleItem',
                $updateargs);

        LogUtil::registerStatus($this->__('Done! Advertise details has been updated successfully.'));

        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewadvertise'));
    }

    public function submitadvertiseuser($args)
    {
        // Get parameters cr_date whatever input we need
        $formElements = FormUtil::getPassedValue('formElements',
                isset($args ['formElements']) ? $args ['formElements'] : null,
                'POST');

        // session_register('svals');
        // $_SESSION['svals'] = $formElements;
        // echo $formElements['adprice_id']; exit;
        $adPriceExplode = explode('+', $formElements ['adprice_id']);

        $adId        = $adPriceExplode [0];
        $adTypePrice = $adPriceExplode [1];

        // echo "<pre>"; print_r($adPriceExplode); echo "</pre>"; exit;
        // echo "<pre>"; print_r($_SESSION['svals']); echo "</pre>"; exit;

        if ($formElements ['elemtName']) { // AD
            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
                $item = array(
                    'name' => $formElements ['elemtName'],
                    'adprice_id' => $adId,
                    'advertise_type' => $formElements ['advertise_type'],
                    'level' => $formElements ['level'],
                    'shop_id' => !empty($formElements ['shop_id']) ? $formElements ['shop_id']
                            : 0,
                    'country_id' => !empty($formElements ['parentcountry_list'])
                            ? $formElements ['parentCountry'] : 0,
                    'region_id' => !empty($formElements ['parentregion_list']) ? $formElements ['parentRegion']
                            : 0,
                    'city_id' => !empty($formElements ['parentcity_list']) ? $formElements ['parentCity']
                            : 0,
                    'maxviews' => !empty($formElements ['maxviews']) ? $formElements ['maxviews']
                            : 0,
                    'maxclicks' => !empty($formElements ['maxclicks']) ? $formElements ['maxclicks']
                            : 0,
                    'startdate' => !empty($formElements ['startdate']) ? $formElements ['startdate']
                            : 0,
                    'enddate' => !empty($formElements ['enddate']) ? $formElements ['enddate']
                            : 0,
                    'description' => isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                            : '',
                    'keywords' => isset($formElements ['keywords']) ? $formElements ['keywords']
                            : '',
                    'status' => isset($formElements ['status']) ? $formElements ['status']
                            : 0
                );
            } else {
                $item = array(
                    'name' => $formElements ['elemtName'],
                    'description' => isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                            : '',
                    'keywords' => isset($formElements ['keywords']) ? $formElements ['keywords']
                            : ''
                );
            }

            if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) {
                $args     = array(
                    'table' => 'zselex_advertise',
                    'element' => $item,
                    'Id' => 'advertise_id'
                );
                // Create the zselex type
                $result   = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement',
                        $args);
                $InsertId = DBUtil::getInsertID($args ['table'], $args ['Id']);

                // echo $formElements['parentshop']; exit;
                // if (UserUtil::getVar('uid') != 2) {
                $loguser          = UserUtil::getVar('uid');
                $serviceupdatearg = array(
                    'user_id' => $loguser,
                    'type' => 'createad',
                    'shop_id' => $formElements ['parentshop']
                );
                $serviceavailed   = ModUtil::apiFunc('ZSELEX', 'admin',
                        'updateServiceAvailed', $serviceupdatearg);
                // }
            } else {
                $InsertId = $formElements ['elemId'];
                // update the type

                if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
                    $item = array(
                        'advertise_id' => $formElements ['elemId'],
                        'name' => $formElements ['elemtName'],
                        'adprice_id' => $formElements ['adprice_id'],
                        'advertise_type' => $formElements ['advertise_type'],
                        'level' => $formElements ['level'],
                        'shop_id' => !empty($formElements ['shop_id']) ? $formElements ['shop_id']
                                : 0,
                        'country_id' => !empty($formElements ['parentcountry_list'])
                                ? $formElements ['parentCountry'] : 0,
                        'region_id' => !empty($formElements ['parentregion_list'])
                                ? $formElements ['parentRegion'] : 0,
                        'city_id' => !empty($formElements ['parentcity_list']) ? $formElements ['parentCity']
                                : 0,
                        'maxviews' => !empty($formElements ['maxviews']) ? $formElements ['maxviews']
                                : 0,
                        'maxclicks' => !empty($formElements ['maxclicks']) ? $formElements ['maxclicks']
                                : 0,
                        'startdate' => !empty($formElements ['startdate']) ? $formElements ['startdate']
                                : 0,
                        'enddate' => !empty($formElements ['enddate']) ? $formElements ['enddate']
                                : 0,
                        'description' => isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                                : '',
                        'keywords' => isset($formElements ['keywords']) ? $formElements ['keywords']
                                : '',
                        'status' => isset($formElements ['status']) ? $formElements ['status']
                                : 0
                    );
                } else {
                    $item = array(
                        'name' => $formElements ['elemtName'],
                        'description' => isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                                : ''
                    );
                }

                // echo "<pre>"; print_r($item); echo "</pre>"; exit;

                $updateargs = array(
                    'table' => 'zselex_advertise',
                    'IdValue' => $InsertId,
                    'IdName' => 'advertise_id',
                    'element' => $item
                );
                // echo "<pre>"; print_r($updateargs); echo "</pre>"; exit;
                $result     = ModUtil::apiFunc('ZSELEX', 'admin',
                        'updateElement', $updateargs);

                // $delParentQuery = "DELETE FROM zselex_parent WHERE childType = '" . $formElements['childType'] . "' AND childId = " . $InsertId . "";
                // DBUtil::executeSQL($delParentQuery);
            }
        }
        if ($result != false) {
            if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) {
                LogUtil::registerStatus($this->__('Done! Advertise has been created successfully.'));
            } else {
                LogUtil::registerStatus($this->__('Done! Advertise details has been updated successfully.'));
            }

            // Success
        } else {
            // fail! type not created
            throw new Zikula_Exception_Fatal($this->__('Story not created for unknown reason (Api failure).'));

            return false;
        }

        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewadvertise',
                array(
                'shop_id' => $formElements ['shop_id']
        )));
    }

    public function createkeywords($args)
    {
        $keywords            = $args ['keywords'];
        $type                = $args ['type'];
        $type_id             = $args ['type_id'];
        $shop_id             = $args ['shop_id'];
        $keywords_for_search = str_replace(',', ' ', $keywords);
        $keywords_for_search = explode(' ', $keywords_for_search);
        foreach ($keywords_for_search as $keyword) {
            $keywordExist = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount',
                    $args         = array(
                    'table' => 'zselex_keywords',
                    'where' => "keyword='".$keyword."'"
            ));

            if ($keywordExist < 1) {
                $keyword_item   = array(
                    'keyword' => $keyword,
                    'type' => $type,
                    'type_id' => $type_id,
                    'shop_id' => $shop_id
                );
                $keyword_args   = array(
                    'table' => 'zselex_keywords',
                    'element' => $keyword_item,
                    'Id' => 'keyword_id'
                );
                $result_keyword = ModUtil::apiFunc('ZSELEX', 'admin',
                        'createElement', $keyword_args);
            }
        }

        return true;
    }

    public function updatekeywords($args)
    {
        $keywords      = $args ['keywords'];
        $type          = $args ['type'];
        $type_id       = $args ['type_id'];
        $shop_id       = $args ['shop_id'];
        $where_keyword = "WHERE type='".$type."' AND type_id=$type_id";
        if (DBUtil::deleteWhere('zselex_keywords', $where_keyword)) {
            if (!empty($keywords)) {
                $keywords_for_search = str_replace(',', ' ', $keywords);
                $keywords_for_search = explode(' ', $keywords_for_search);
                foreach ($keywords_for_search as $keyword) {
                    $keywordExist = ModUtil::apiFunc('ZSELEX', 'admin',
                            'getCount',
                            $args         = array(
                            'table' => 'zselex_keywords',
                            'where' => "keyword='".$keyword."'"
                    ));

                    if ($keywordExist < 1) {
                        if (!empty($keyword)) {
                            $keyword_item   = array(
                                'keyword' => $keyword,
                                'type' => $type,
                                'type_id' => $type_id,
                                'shop_id' => $shop_id
                            );
                            $keyword_args   = array(
                                'table' => 'zselex_keywords',
                                'element' => $keyword_item,
                                'Id' => 'keyword_id'
                            );
                            $result_keyword = ModUtil::apiFunc('ZSELEX',
                                    'admin', 'createElement', $keyword_args);
                        }
                    }
                }
            }
        }

        return true;
    }

    public function submitadvertise($args)
    {
        // Get parameters cr_date whatever input we need
        $formElements = FormUtil::getPassedValue('formElements',
                isset($args ['formElements']) ? $args ['formElements'] : null,
                'POST');
        $formElements = $this->purifyHtml($formElements);
        $shop_id      = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        // echo "<pre>"; print_r($formElements); exit;
        // echo "<pre>"; print_r(pathinfo($formElements['country-combo'])); exit;
        $this->checkCsrfToken();
        if ($formElements ['elemtName']) { // AD
            // echo "comes here"; exit;
            $country    = pathinfo($formElements ['country-combo']);
            $country_id = $country ['filename'];
            $region     = pathinfo($formElements ['region-combo']);
            $region_id  = $region ['filename'];
            $city       = pathinfo($formElements ['city-combo']);
            $city_id    = $city ['filename'];
            $area       = pathinfo($formElements ['area-combo']);
            $area_id    = $area ['filename'];

            $item     = array(
                'name' => $formElements ['elemtName'],
                'adprice_id' => $formElements ['adprice_id'],
                'advertise_type' => $formElements ['advertise_type'],
                'level' => $formElements ['level'],
                'shop_id' => !empty($formElements ['shop_id']) ? $formElements ['shop_id']
                        : 0,
                'country_id' => ($formElements ['level'] == 'COUNTRY') ? $country_id
                        : 0,
                'region_id' => ($formElements ['level'] == 'REGION') ? $region_id
                        : 0,
                'city_id' => ($formElements ['level'] == 'CITY') ? $city_id : 0,
                'area_id' => ($formElements ['level'] == 'AREA') ? $area_id : 0,
                'maxviews' => !empty($formElements ['maxviews']) ? $formElements ['maxviews']
                        : 0,
                'maxclicks' => !empty($formElements ['maxclicks']) ? $formElements ['maxclicks']
                        : 0,
                'startdate' => !empty($formElements ['startdate']) ? $formElements ['startdate']
                        : 0,
                'enddate' => !empty($formElements ['enddate']) ? $formElements ['enddate']
                        : 0,
                'description' => isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                        : '',
                'keywords' => isset($formElements ['keywords']) ? $formElements ['keywords']
                        : '',
                'status' => isset($formElements ['status']) ? $formElements ['status']
                        : 0
            );
            $keywords = $formElements ['keywords'];

            // echo "<pre>"; print_r($item); exit;

            if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) { // create
                $args     = array(
                    'table' => 'zselex_advertise',
                    'element' => $item,
                    'Id' => 'advertise_id'
                );
                // Create the zselex type
                $result   = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement',
                        $args);
                $InsertId = DBUtil::getInsertID($args ['table'], $args ['Id']);
                // if (UserUtil::getVar('uid') != 2) {
                // if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                // $loguser = UserUtil::getVar('uid');
                // $serviceupdatearg = array('user_id' => $loguser, 'type' => 'createad', 'shop_id' => $formElements['parentshop']);
                // $serviceavailed = ModUtil::apiFunc('ZSELEX', 'admin', 'updateServiceAvailed', $serviceupdatearg);

                $user             = UserUtil::getVar('uid');
                $serviceupdatearg = array(
                    'user_id' => $user,
                    'type' => 'createad',
                    'shop_id' => $formElements ['parentshop']
                );
                $serviceavailed   = ModUtil::apiFunc('ZSELEX', 'admin',
                        'updateServiceUsed', $serviceupdatearg);

                if ($result) {
                    $this->createkeywords($args = array(
                        'keywords' => $keywords,
                        'type' => 'productAd',
                        'type_id' => $InsertId,
                        'shop_id' => $formElements ['shop_id']
                    ));
                }
                // }
            } else {
                $InsertId = $formElements ['elemId'];
                // echo $InsertId; exit;
                // update the type
                // echo "<pre>"; print_r($formElements); exit;
                $item     = array(
                    'advertise_id' => $formElements ['elemId'],
                    'name' => $formElements ['elemtName'],
                    'adprice_id' => $formElements ['adprice_id'],
                    'advertise_type' => $formElements ['advertise_type'],
                    'level' => $formElements ['level'],
                    'shop_id' => !empty($formElements ['shop_id']) ? $formElements ['shop_id']
                            : 0,
                    'country_id' => ($formElements ['level'] == 'COUNTRY') ? $formElements ['country-combo']
                            : 0,
                    'region_id' => ($formElements ['level'] == 'REGION') ? $formElements ['region-combo']
                            : 0,
                    'city_id' => ($formElements ['level'] == 'CITY') ? $formElements ['city-combo']
                            : 0,
                    'area_id' => ($formElements ['level'] == 'AREA') ? $formElements ['area-combo']
                            : 0,
                    'maxviews' => !empty($formElements ['maxviews']) ? $formElements ['maxviews']
                            : 0,
                    'maxclicks' => !empty($formElements ['maxclicks']) ? $formElements ['maxclicks']
                            : 0,
                    'startdate' => !empty($formElements ['startdate']) ? $formElements ['startdate']
                            : 0,
                    'enddate' => !empty($formElements ['enddate']) ? $formElements ['enddate']
                            : 0,
                    'description' => isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                            : '',
                    'keywords' => isset($formElements ['keywords']) ? $formElements ['keywords']
                            : '',
                    'status' => isset($formElements ['status']) ? $formElements ['status']
                            : 0
                );
                $keywords = $formElements ['keywords'];

                // echo "<pre>"; print_r($item); echo "</pre>"; exit;

                $updateargs = array(
                    'table' => 'zselex_advertise',
                    'IdValue' => $InsertId,
                    'IdName' => 'advertise_id',
                    'element' => $item
                );

                $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement',
                        $updateargs);

                if ($result) {
                    $keyword_upd_args = array(
                        'keywords' => $keywords,
                        'type' => 'productAd',
                        'type_id' => $InsertId,
                        'shop_id' => $formElements ['shop_id']
                    );
                    // echo "<pre>"; print_r($keyword_upd_args); echo "</pre>"; exit;
                    $this->updatekeywords($args             = $keyword_upd_args);
                }

                // $delParentQuery = "DELETE FROM zselex_parent WHERE childType = '" . $formElements['childType'] . "' AND childId = " . $InsertId . "";
                // DBUtil::executeSQL($delParentQuery);
                // $delresult = ModUtil::apiFunc('ZSELEX', 'admin', 'execteQuery', $delParentQuery);
            }
        }
        if ($result != false) {
            ModUtil::apiFunc('ZSELEX', 'admin', 'updateMinisite',
                array(
                'shop_id' => $formElements ['shop_id']
            ));
            if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) {
                LogUtil::registerStatus($this->__('Done! Advertise has been created successfully.'));
            } else {
                LogUtil::registerStatus($this->__('Done! Advertise details has been updated successfully.'));
            }
        } else {
            // fail! type not created
            throw new Zikula_Exception_Fatal($this->__('Story not created for unknown reason (Api failure).'));

            return false;
        }

        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewadvertise',
                array(
                'shop_id' => $formElements ['shop_id']
        )));
    }

    public function viewadvertise($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>";
        // echo $_SESSION['svals']['parentregion_list'];
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());

        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        if (!(int) ($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)));
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $user_id        = UserUtil::getVar('uid');
        $admin          = SecurityUtil::checkPermission('ZSELEX::', '::',
                ACCESS_ADMIN);
        $this->view->assign('admin', $admin);
        $template       = 'viewadvertise.tpl';
        $disable        = '';
        $servicecount   = 0;
        $error          = 0;
        $servicedisable = false;

        $minishop = ModUtil::apiFunc('ZSELEX', 'admin', 'minishopExist',
                $args     = array(
                'shop_id' => $shop_id
        ));

        $serviceargs       = array(
            'shop_id' => FormUtil::getPassedValue('shop_id', null, 'REQUEST'),
            'user_id' => $user_id,
            'type' => 'createad'
        );
        $servicePermission = $this->servicePermission($serviceargs);
        $servicecount += $servicePermission ['perm'];

        // echo "<pre>"; print_r($servicePermission); echo "</pre>";

        if ($servicePermission ['perm'] < 1) {
            // echo "comes here";
            // $template = 'viewadvertise_noservice.tpl';
            $message = $servicePermission ['message'];
            ++$error;
            LogUtil::registerError(nl2br($message));
        }

        if ($this->serviceDisabled('createad') < 1) {
            if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $servicedisable = true;
                ++$error;
                $disable        = 'disabled';
            }
            $message = $this->__('This service is currently disabled');
            LogUtil::registerError(nl2br($message));
        }
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            $expired = $servicePermission ['expired'];
        }

        $this->view->assign('servicecount', $servicecount);
        $this->view->assign('servicedisable', $servicedisable);
        $this->view->assign('disable', $disable);
        $this->view->assign('serviceerror', $error);
        $this->view->assign('expired', $expired);
        $this->view->assign('message', $message);

        // initialize sort array - used to display sort classes and urls
        $sort   = array();
        $fields = array(
            'advertise_id',
            'name',
            'status',
            'cr_date',
            'lu_date'
        ); // possible sort fields

        $itemsperpage  = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 15,
                'GETPOST');
        $startnum      = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $status        = FormUtil::getPassedValue('status',
                isset($args ['status']) ? $args ['status'] : null, 'GETPOST');
        $order         = FormUtil::getPassedValue('order',
                isset($args ['order']) ? $args ['order'] : 'cr_date', 'GETPOST');
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
                    'viewadvertise',
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
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            // $wher = " AND a.status=1 AND (a.cr_uid='" . UserUtil::getVar('uid') . "' OR a.shop_id In (SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='SHOPADMIN'
            // AND parentId='" . UserUtil::getVar('uid') . "'))";
        }

        $sql = " SELECT a.*,p.name AS pricename,p.adprice_id,s.shop_name,s.shop_id,c.country_name,
                   c.country_id,r.region_name,r.region_id,ci.city_name,ci.city_id, area.area_id ,  area.area_name
                   FROM zselex_advertise AS a 
                   LEFT JOIN zselex_advertise_price AS p ON (a.adprice_id = p.adprice_id)
                   LEFT JOIN zselex_shop AS s ON (a.shop_id = s.shop_id)
                   LEFT JOIN zselex_country AS c ON (a.country_id = c.country_id)
                   LEFT JOIN zselex_region AS r ON (a.region_id = r.region_id)
                   LEFT JOIN zselex_city AS ci ON (a.city_id = ci.city_id)
                   LEFT JOIN zselex_area AS area ON (a.area_id = area.area_id)
                   WHERE a.advertise_id IS NOT NULL AND a.shop_id='".$shop_id."' $wher";

        if (isset($status) && $status != '') {
            $sql .= ' AND a.status = '.$status;
        }
        if (isset($searchtext) && $searchtext != '') {
            $sql .= " AND a.name LIKE '%".DataUtil::formatForStore($searchtext)."%'";
        }
        if (isset($order) && $order != '') {
            $sql .= ' ORDER BY a.'.$order.' '.$orderdir;
        }
        // echo $sql;
        // Get all zselex stories
        $getArgs = array(
            'sql' => $sql,
            'startnum' => $startnum,
            'numitems' => $itemsperpage
        );
        $items   = ModUtil::apiFunc('ZSELEX', 'admin', 'getAll', $getArgs);

        for ($i = 0; $i < count($items); ++$i) {

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
                'IdValue' => !empty($items [$i] ['lu_uid']) ? $items [$i] ['lu_uid']
                        : 2,
                'IdName' => 'uid'
            );
            // echo "<pre>"; print_r($updateargs); echo "</pre>";
            $users                      = ModUtil::apiFunc('ZSELEX', 'admin',
                    'getElement', $updateargs);
            $items [$i] ['updateduser'] = $users ['uname'];
        }

        $where = " advertise_id IS NOT NULL AND shop_id='".$shop_id."'";
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            // $where .= " AND cr_uid='" . UserUtil::getVar('uid') . "'";

            $where .= " AND status=1 AND (cr_uid='".UserUtil::getVar('uid')."' OR shop_id In (SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='SHOPADMIN'
                  AND parentId='".UserUtil::getVar('uid')."'))";
        }
        if (isset($status) && $status != '') {
            $where .= ' AND status = '.$status;
        }
        if (isset($searchtext) && $searchtext != '') {
            $where .= " AND name LIKE '%".DataUtil::formatForStore($searchtext)."%'";
        }
        $getCountArgs = array(
            'table' => 'zselex_advertise',
            'where' => $where,
            'Id' => 'advertise_id',
            'status' => $status
        );

        $total_advertises = ModUtil::apiFunc('ZSELEX', 'admin', 'countElements',
                $getCountArgs);
        if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            // $servicecount += $total_advertises;
            // $this->view->assign('servicecount', $total_advertises);
        }

        // Set the possible status for later use
        $itemstatus = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );

        $advertisesitems = array();
        foreach ($items as $item) {
            $options = array();

            // $options[] = array('url' => ModUtil::url('ZSELEX', 'admin', 'displayadvertise', array('advertise_id' => $item['advertise_id'])),
            // 'image' => '14_layer_visible.png',
            // 'title' => $this->__('View'));

            if (SecurityUtil::checkPermission('ZSELEX::',
                    "{$item['cr_uid']}::{$item['advertise_id']}", ACCESS_EDIT)) {
                $options [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'modifyadvertise',
                        array(
                        'id' => $item ['advertise_id'],
                        'shop_id' => $shop_id
                    )),
                    'image' => 'xedit.png',
                    'title' => $this->__('Edit')
                );

                if ((SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['advertise_id']}",
                        ACCESS_DELETE)) || SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['advertise_id']}", ACCESS_ADD)) {
                    $options [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin',
                            'deleteadvertise',
                            array(
                            'advertise_id' => $item ['advertise_id'],
                            'shop_id' => $item ['shop_id']
                        )),
                        'image' => '14_layer_deletelayer.png',
                        'title' => $this->__('Delete'),
                        'val' => 'delete'
                    );
                }
            }
            $item ['options'] = $options;

            $item ['infuture']  = DateUtil::getDatetimeDiff_AsField($item ['cr_date'],
                    DateUtil::getDatetime(), 6) < 0;
            $advertisesitems [] = $item;
        }

        $loguser     = UserUtil::getVar('uid');
        $servicearg  = array(
            'user_id' => $loguser,
            'type' => 'createad'
        );
        $serviceperm = ModUtil::apiFunc('ZSELEX', 'admin',
                'getServicePermission', $servicearg);

        $perm = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD);
        $this->view->assign('perm', $perm);

        $serviceAdCount = count($serviceperm);
        // Assign the items to the template

        $this->view->assign('perm', $perm);
        $this->view->assign('advertisesitems', $advertisesitems);

        $this->view->assign('total_advertises', $total_advertises);

        // Assign the current status filter and the possible ones

        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('status', $status);
        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('order', $order);

        $this->view->assign('serviceAdCount', $serviceAdCount);
        // Return the output that has been generated by this function
        return $this->view->fetch('admin/advertise/'.$template);
    }

    public function deleteadvertise($args)
    {
        $advertise_id = FormUtil::getPassedValue('advertise_id',
                isset($args ['advertise_id']) ? $args ['advertise_id'] : null,
                'REQUEST');
        $shop_id      = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : null, 'REQUEST');
        $user_id      = UserUtil::getVar('uid');

        $serviceEdit = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceCheckEdit',
                $args        = array(
                'shop_id' => $shop_id,
                'item_idValue' => $advertise_id,
                'user_id' => $user_id,
                'servicetable' => 'zselex_advertise',
                'item_id' => 'advertise_id',
                'type' => 'createad'
        ));

        if ($serviceEdit < 1) {
            // return LogUtil::registerError($this->__f('Error! Unable to delete this %s.', $this->__('Product AD')));
        }
        // echo $shop_id; exit;

        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');

        if (empty($confirmation)) {
            $this->view->assign('IdValue', $advertise_id);
            $this->view->assign('confirm_title',
                $this->__f('Delete %s', $this->__('Product AD')));
            $this->view->assign('confirm_msg',
                $this->__f('Do you want to delete this %s',
                    $this->__('Product AD')));
            $this->view->assign('IdName', 'advertise_id');
            $this->view->assign('shop_id', $shop_id);
            $this->view->assign('submitFunc', 'deleteadvertise');
            $this->view->assign('cancelFunc', 'viewadvertise');
            // Return the output that has been generated by this function
            return $this->view->fetch('admin/deletecommon.tpl');
        }
        $this->checkCsrfToken();

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            $item       = array(
                'advertise_id' => $advertise_id,
                'status' => 0
            );
            $updateargs = array(
                'table' => 'zselex_advertise',
                'IdValue' => $advertise_id,
                'IdName' => 'advertise_id',
                'element' => $item
            );

            // ModUtil::apiFunc('ZSELEX', 'admin', 'updateMultipleItem', $args = array('table' => 'zselex_serviceshop', 'values' => array('availed' => 'availed-1'), 'where' => array('user_id' => UserUtil::getVar('uid'), 'shop_id' => $shop_id, 'type' => 'createad'))); // update the service table
            // $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement', $updateargs);
        } else {
            $result = DBUtil::deleteObjectById('zselex_advertise',
                    $advertise_id, 'advertise_id');
        }
        // Delete
        if ($result) {
            $args          = array(
                'shop_id' => $shop_id,
                'servicetype' => 'createad',
                'user_id' => $user_id
            );
            $deleteservice = ModUtil::apiFunc('ZSELEX', 'admin',
                    'deleteService', $args);
            // Success
            LogUtil::registerStatus($this->__('Done! Type has been deleted successfully.'));
            ModUtil::apiFunc('ZSELEX', 'admin', 'updateMinisite',
                array(
                'shop_id' => $shop_id
            ));
            // Let any hooks know that we have deleted an item
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewadvertise',
                    array(
                    'shop_id' => $shop_id
        )));
    }

    public function modifyadvertise()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());

        // echo "modifycity";
        $shop_id      = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $advertise_id = FormUtil::getPassedValue('id',
                isset($args ['id']) ? $args ['id'] : null, 'GETPOST');
        $user_id      = UserUtil::getVar('uid');

        if (!(int) ($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)));
        }

        if (!(int) ($advertise_id)) {
            return LogUtil::registerError($this->__f('Error! The AdvertiseID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($advertise_id)));
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $serviceEdit = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceCheckEdit',
                $args        = array(
                'shop_id' => $shop_id,
                'item_idValue' => $advertise_id,
                'user_id' => $user_id,
                'servicetable' => 'zselex_advertise',
                'item_id' => 'advertise_id',
                'type' => 'createad'
        ));

        if ($serviceEdit < 1) {
            // return LogUtil::registerError($this->__f('Error! Unable to edit this %s.', $this->__('Product AD')));
        }

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) { // disabled check
            if ($this->serviceDisabled('createad') < 1) {
                return LogUtil::registerError($this->__('This service has been temporarily disabled!'));
            }
        }

        $sql = ' SELECT a.*,p.name AS pricename,p.adprice_id,s.shop_name,s.shop_id,c.country_name,
                   c.country_id,r.region_name,r.region_id,ci.city_name,ci.city_id
                   FROM zselex_advertise AS a 
                   LEFT JOIN zselex_advertise_price AS p ON (a.adprice_id = p.adprice_id)
                   LEFT JOIN zselex_shop AS s ON (a.shop_id = s.shop_id)
                   LEFT JOIN zselex_country AS c ON (a.country_id = c.country_id)
                   LEFT JOIN zselex_region AS r ON (a.region_id = r.region_id)
                   LEFT JOIN zselex_city AS ci ON (a.city_id = ci.city_id)
                   WHERE a.advertise_id ='.$advertise_id;

        $args = array(
            'table' => 'zselex_advertise',
            'IdValue' => $advertise_id,
            'IdName' => 'advertise_id'
        );
        // Get the news type in the db
        $item = ModUtil::apiFunc('ZSELEX', 'admin', 'execteQuery', $sql);

        // print_r($item);

        $adpriceargs = array(
            'table' => 'zselex_advertise_price',
            'where' => '',
            'orderBy' => '',
            'useJoins' => ''
        );

        $aAdPriceArray = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $adpriceargs);

        $this->view->assign('zadprice', $aAdPriceArray);
        $this->view->assign('item', $item [0]);
        $this->view->assign('admins',
            SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN));

        $func = '';
        if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            $func = 'submitadvertise';
        } else {
            $func = 'submitadvertiseuser';
        }

        $func = 'submitadvertise';

        $countryargs = array(
            'table' => 'zselex_country',
            'fields' => array(
                'country_id , country_name'
            ),
            'orderby' => 'country_name ASC'
        );
        $countries   = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray',
                $countryargs);
        $regionargs  = array(
            'table' => 'zselex_region',
            'fields' => array(
                'region_id , region_name'
            ),
            'orderby' => 'region_name ASC'
        );
        $regions     = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray',
                $regionargs);
        $cityargs    = array(
            'table' => 'zselex_city',
            'fields' => array(
                'city_id , city_name'
            ),
            'orderby' => 'city_name ASC'
        );
        $cities      = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray',
                $cityargs);
        $areaargs    = array(
            'table' => 'zselex_area',
            'fields' => array(
                'area_id , area_name'
            ),
            'orderby' => 'area_name ASC'
        );
        $areas       = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray',
                $areaargs);

        $this->view->assign('countries', $countries);
        $this->view->assign('regions', $regions);
        $this->view->assign('cities', $cities);
        $this->view->assign('areas', $areas);
        $this->view->assign('shop_name', $this->shopname);
        $this->view->assign('funcs', $func);

        /*
         * if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
         * return $this->view->fetch('admin/advertise/createadvertise.tpl');
         * } elseif (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
         * return $this->view->fetch('admin/advertise/createadvertise_user.tpl');
         * } else {
         * return $this->view->fetch('admin/advertise/createadvertise_user_admin.tpl');
         * }
         */
        return $this->view->fetch('admin/advertise/createadvertise.tpl');
    }

    public function submitadprice($args)
    {
        // Get parameters cr_date whatever input we need
        $formElements = FormUtil::getPassedValue('formElements',
                isset($args ['formElements']) ? $args ['formElements'] : null,
                'POST');
        $formElements = $this->purifyHtml($formElements);
        $adRepo       = $this->entityManager->getRepository('ZSELEX_Entity_AdvertisePrice');
        if ($formElements ['elemtName']) { // AD
            $item = array(
                'name' => $formElements ['elemtName'],
                'identifier' => $formElements ['identifier'],
                'pricetype' => isset($formElements ['pricetype']) ? $formElements ['pricetype']
                        : '',
                'price' => isset($formElements ['price']) ? $formElements ['price']
                        : '',
                'description' => isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                        : '',
                'status' => isset($formElements ['status']) ? $formElements ['status']
                        : 0
            );

            if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) {

                /*
                 * $args = array(
                 * 'table' => 'zselex_advertise_price',
                 * 'element' => $item,
                 * 'Id' => 'adprice_id'
                 * );
                 * // Create the zselex type
                 * $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $args);
                 */
                $result   = $adRepo->createAdvertisePrice($item);
                // $InsertId = DBUtil::getInsertID($args['table'], $args['Id']);
                $InsertId = $result;
            } else {
                $InsertId = $formElements ['elemId'];
                // update the type

                $item = array(
                    'adprice_id' => $formElements ['elemId'],
                    'name' => $formElements ['elemtName'],
                    'identifier' => $formElements ['identifier'],
                    'pricetype' => isset($formElements ['pricetype']) ? $formElements ['pricetype']
                            : '',
                    'price' => isset($formElements ['price']) ? $formElements ['price']
                            : '',
                    'description' => isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                            : '',
                    'status' => isset($formElements ['status']) ? $formElements ['status']
                            : 0
                );
                /*
                 * $updateargs = array(
                 * 'table' => 'zselex_advertise_price',
                 * 'IdValue' => $InsertId,
                 * 'IdName' => 'adprice_id',
                 * 'element' => $item
                 * );
                 *
                 * $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement', $updateargs);
                 */

                $result = $adRepo->updateEntity(array(
                    'entity' => 'ZSELEX_Entity_AdvertisePrice',
                    'fields' => $item,
                    'where' => array(
                        'a.adprice_id' => $InsertId
                    )
                ));

                // $delParentQuery = "DELETE FROM zselex_parent WHERE childType = '" . $formElements['childType'] . "' AND childId = " . $InsertId . "";
                // DBUtil::executeSQL($delParentQuery);
                // $delresult = ModUtil::apiFunc('ZSELEX', 'admin', 'execteQuery', $delParentQuery);
            }
        }
        if ($result != false) {
            // Success
            if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) {
                LogUtil::registerStatus($this->__('Done! AD price has been created successfully.'));
            } else {
                LogUtil::registerStatus($this->__('Done! AD price details has been updated successfully.'));
            }
        } else {
            // fail! type not created
            throw new Zikula_Exception_Fatal($this->__('Story not created for unknown reason (Api failure).'));

            return false;
        }

        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewadprice'));
    }

    public function viewadprice($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        error_reporting(0);
        $adRepo = $this->entityManager->getRepository('ZSELEX_Entity_Advertise');
        // initialize sort array - used to display sort classes and urls
        $sort   = array();
        $fields = array(
            'adprice_id',
            'name',
            'status',
            'cr_date',
            'lu_date'
        ); // possible sort fields

        $itemsperpage  = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 15,
                'GETPOST');
        $startnum      = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $status        = FormUtil::getPassedValue('status',
                isset($args ['status']) ? $args ['status'] : null, 'GETPOST');
        $order         = FormUtil::getPassedValue('order',
                isset($args ['order']) ? $args ['order'] : 'cr_date', 'GETPOST');
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
                    'viewadprice',
                    array(
                    'status' => $status,
                    'filtercats_serialized' => serialize($filtercats),
                    'order' => $field,
                    'sdir' => $sdir
            ));
        }
        $this->view->assign('sort', $sort);

        $this->view->assign('filter_active', $status);

        /*
         * $sql = " SELECT a.* FROM zselex_advertise_price AS a
         * WHERE a.adprice_id IS NOT NULL ";
         */
        if (isset($status) && $status != '') {
            // $sql .= " AND a.status = " . $status;
            $adArgs ['where'] ['a.status'] = $status;
        }

        if (isset($searchtext) && $searchtext != '') {
            // $sql .= " AND a.name LIKE '%" . DataUtil::formatForStore($searchtext) . "%'";
            $adArgs ['like'] ['a.name'] = '%'.DataUtil::formatForStore($searchtext).'%';
        }
        if (isset($order) && $order != '') {
            // $sql .= " ORDER BY a." . $order . " " . $orderdir;
            $adArgs ['orderby'] = 'a.'.$order.' '.$orderdir;
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
        $adArgs ['entity']   = 'ZSELEX_Entity_AdvertisePrice';
        $adArgs ['paginate'] = true;
        $adItems             = $adRepo->getAll($adArgs);

        $items = $adItems ['result'];
        // echo "<pre>"; print_r($adItems); echo "</pre>";
        // echo "<pre>"; print_r($items); echo "</pre>";

        $total_adprices = $adItems ['count'];

        // Set the possible status for later use
        $itemstatus = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );

        $adpricesitems = array();
        foreach ($items as $item) {
            $options = array();

            // $options[] = array('url' => ModUtil::url('ZSELEX', 'admin', 'displayadprice', array('adprice_id' => $item['adprice_id'])),
            // 'image' => '14_layer_visible.png',
            // 'title' => $this->__('View'));

            if (SecurityUtil::checkPermission('ZSELEX::',
                    "{$item['cr_uid']}::{$item['adprice_id']}", ACCESS_EDIT)) {
                $options [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'modifyadprice',
                        array(
                        'id' => $item ['adprice_id']
                    )),
                    'image' => 'xedit.png',
                    'title' => $this->__('Edit')
                );

                if ((SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['adprice_id']}",
                        ACCESS_DELETE)) || SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['adprice_id']}", ACCESS_ADMIN)) {
                    $options [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin',
                            'deleteadprice',
                            array(
                            'adprice_id' => $item ['adprice_id']
                        )),
                        'image' => '14_layer_deletelayer.png',
                        'title' => $this->__('Delete')
                    );
                }
            }
            $item ['options'] = $options;

            // Get the Username for create and update
            if ($item ['cr_uid'] > 0) {
                $createdUsers         = $adRepo->getUserInfo(array(
                    'entity' => 'ZSELEX_Entity_AdvertisePrice',
                    'table' => 'zselex_advertise_price',
                    'field' => 'cr_uid',
                    'user_id' => $item ['cr_uid']
                ));
                $item ['createduser'] = $createdUsers ['uname'];
            }

            if ($item ['lu_uid'] > 0) {
                $updatedUsers         = $adRepo->getUserInfo(array(
                    'entity' => 'ZSELEX_Entity_AdvertisePrice',
                    'table' => 'zselex_advertise_price',
                    'field' => 'lu_uid',
                    'user_id' => $item ['lu_uid']
                ));
                $item ['updateduser'] = $updatedUsers ['uname'];
            }

            $item ['infuture'] = DateUtil::getDatetimeDiff_AsField($item ['cr_date'],
                    DateUtil::getDatetime(), 6) < 0;
            $adpricesitems []  = $item;
        }

        // echo "<pre>"; print_r($adpricesitems); echo "</pre>";
        // Assign the items to the template
        $this->view->assign('adpricesitems', $adpricesitems);

        $this->view->assign('total_adprices', $total_adprices);

        // Assign the current status filter and the possible ones
        $this->view->assign('status', $status);
        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('order', $order);
        // Return the output that has been generated by this function
        return $this->view->fetch('admin/viewadprice.tpl');
    }

    public function deleteadprice($args)
    {
        $adprice_id = FormUtil::getPassedValue('adprice_id',
                isset($args ['adprice_id']) ? $args ['adprice_id'] : null,
                'REQUEST');
        $adRepo     = $this->entityManager->getRepository('ZSELEX_Entity_Advertise');
        $item       = array(
            'adprice_id' => $adprice_id,
            'status' => 0
        );
        /*
         * $updateargs = array(
         * 'table' => 'zselex_advertise_price',
         * 'IdValue' => $adprice_id,
         * 'IdName' => 'adprice_id',
         * 'element' => $item
         * );
         *
         * $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement', $updateargs);
         */
        $result     = $adRepo->updateEntity(array(
            'entity' => 'ZSELEX_Entity_AdvertisePrice',
            'fields' => $item,
            'where' => array(
                'a.adprice_id' => $adprice_id
            )
        ));
        // Delete
        if ($result) {
            // Success
            LogUtil::registerStatus($this->__('Done! Type has been deleted successfully.'));

            // Let any hooks know that we have deleted an item
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewadprice'));
    }

    public function modifyadprice()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        // echo "modifycity";
        $adprice_id = FormUtil::getPassedValue('id',
                isset($args ['id']) ? $args ['id'] : null, 'GETPOST');

        $args = array(
            'table' => 'zselex_advertise_price',
            'IdValue' => $adprice_id,
            'IdName' => 'adprice_id'
        );
        // Get the news type in the db
        $item = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $args);

        $parentArgs = array(
            'childType' => 'AD',
            'childId' => $item ['adprice_id']
        );

        $parentArgs ['parentTable'] = 'zselex_advertise_price';
        $parentArgs ['parentId']    = 'adprice_id';
        $parentArgs ['parentType']  = 'AD';
        $parentad                   = ModUtil::apiFunc('ZSELEX', 'admin',
                'getParents', $parentArgs);
        $item ['parentad']          = $parentad [0] ['name'];
        $item ['parentadId']        = $parentad [0] ['adprice_id'];
        $this->view->assign('item', $item);

        return $this->view->fetch('admin/createadprice.tpl');
    }

    public function viewplugin($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        $repo   = $this->entityManager->getRepository('ZSELEX_Entity_Plugin');
        // initialize sort array - used to display sort classes and urls
        $sort   = array();
        $fields = array(
            'plugin_id',
            'plugin_name',
            'sort_order',
            'status',
            'cr_date',
            'lu_date'
        ); // possible sort fields
        // echo "<pre>"; print_r($_POST); echo "</pre>";
        if ($_POST) {
            if ($_POST ['submit'] == 'SORT') {
                $sort_values = FormUtil::getPassedValue('sortorder', null,
                        'POST');
                foreach ($sort_values as $id => $value) {
                    $item   = array(
                        'plugin_id' => $id,
                        'sort_order' => $value
                    );
                    /*
                     * $updateargs = array(
                     * 'table' => 'zselex_plugin',
                     * 'IdValue' => $id,
                     * 'IdName' => 'plugin_id',
                     * 'element' => $item
                     * );
                     *
                     * $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement', $updateargs);
                     */
                    $result = $repo->updateEntity(null, 'ZSELEX_Entity_Plugin',
                        $item,
                        array(
                        'a.plugin_id' => $id
                    ));
                }
            }
        }

        $itemsperpage  = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 40,
                'GETPOST');
        $startnum      = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $status        = FormUtil::getPassedValue('status',
                isset($args ['status']) ? $args ['status'] : null, 'GETPOST');
        // $order = FormUtil::getPassedValue('order', isset($args['order']) ? $args['order'] : 'cr_date', 'GETPOST');
        $order         = FormUtil::getPassedValue('order',
                isset($args ['order']) ? $args ['order'] : '', 'GETPOST');
        $original_sdir = FormUtil::getPassedValue('sdir',
                isset($args ['sdir']) ? $args ['sdir'] : 1, 'GETPOST');
        $searchtext    = FormUtil::getPassedValue('searchtext',
                isset($args ['searchtext']) ? $args ['searchtext'] : null,
                'GETPOST');

        $this->view->assign('startnum', $startnum);
        $this->view->assign('itemsperpage', $itemsperpage);
        $this->view->assign('order', $order);
        $this->view->assign('sdir', $original_sdir);
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
                    'viewplugin',
                    array(
                    'status' => $status,
                    'filtercats_serialized' => serialize($filtercats),
                    'order' => $field,
                    'sdir' => $sdir
            ));
        }
        $this->view->assign('sort', $sort);

        $this->view->assign('filter_active',
            (!isset($status) && empty($filtercats)) ? false : true );

        /*
         * $sql = " SELECT a.* FROM zselex_plugin AS a
         * WHERE a.plugin_id IS NOT NULL ";
         * // $sql .= " AND bundle=0";
         */

        $pluginArgs ['fields'] = array(
            'a.plugin_id',
            'a.plugin_name',
            'a.sort_order',
            'a.description',
            'a.identifier',
            'a.price',
            'a.status',
            'a.cr_date',
            'a.cr_uid',
            'a.lu_date',
            'a.lu_uid'
        );
        if (isset($status) && $status != '') {
            // $sql .= " AND a.status = " . $status;
            $pluginArgs ['where'] ['a.status'] = $status;
        }
        if (isset($searchtext) && $searchtext != '') {
            // $sql .= " AND a.plugin_name LIKE '%" . DataUtil::formatForStore($searchtext) . "%'";
            $pluginArgs ['like'] ['a.plugin_name'] = '%'.DataUtil::formatForStore($searchtext).'%';
        }
        if (isset($order) && $order != '') {
            // $sql .= " ORDER BY a." . $order . " " . $orderdir;
            $pluginArgs ['orderby'] = 'a.'.$order.' '.$orderdir;
        } else {
            // $sql .= " ORDER BY IF(sort_order = 0, 999999999, sort_order) ASC";
            $pluginArgs ['orderby'] = 'a.sort_order ASC';
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

        $pluginArgs ['entity']     = 'ZSELEX_Entity_Plugin';
        $pluginArgs ['paginate']   = true;
        $pluginArgs ['startlimit'] = $startnum;
        $pluginArgs ['offset']     = $itemsperpage;
        $plugins                   = $repo->getAll($pluginArgs);
        $items                     = $plugins ['result'];

        $total_plugins = $plugins ['count'];

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
        for ($i = 0; $i < count($items); ++$i) {
            // Get the Username for create and update

            if ($items [$i] ['cr_uid'] > 0) {
                $createdUsers               = $repo->getUserInfo(array(
                    'entity' => 'ZSELEX_Entity_Plugin',
                    'table' => 'zselex_plugin',
                    'field' => 'cr_uid',
                    'user_id' => $items [$i] ['cr_uid']
                ));
                $items [$i] ['createduser'] = $createdUsers ['uname'];
            }

            if ($items [$i] ['lu_uid'] > 0) {
                $updatedUsers               = $repo->getUserInfo(array(
                    'entity' => 'ZSELEX_Entity_Plugin',
                    'table' => 'zselex_plugin',
                    'field' => 'lu_uid',
                    'user_id' => $items [$i] ['lu_uid']
                ));
                $items [$i] ['updateduser'] = $updatedUsers ['uname'];
            }
        }

        $pluginsitems = array();
        foreach ($items as $item) {
            $options = array();

            // $options[] = array('url' => ModUtil::url('ZSELEX', 'admin', 'displayplugin', array('plugin_id' => $item['plugin_id'])),
            // 'image' => '14_layer_visible.png',
            // 'title' => $this->__('View'));

            if (SecurityUtil::checkPermission('ZSELEX::',
                    "{$item['cr_uid']}::{$item['plugin_id']}", ACCESS_EDIT)) {
                $options [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'modifyplugin',
                        array(
                        'plugin_id' => $item ['plugin_id']
                    )),
                    'image' => 'xedit.png',
                    'title' => $this->__('Edit')
                );

                if ((SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['plugin_id']}", ACCESS_DELETE))
                    || SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['plugin_id']}", ACCESS_ADMIN)) {
                    $options [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin', 'deleteplugin',
                            array(
                            'plugin_id' => $item ['plugin_id']
                        )),
                        'image' => '14_layer_deletelayer.png',
                        'title' => $this->__('Delete')
                    );
                }
            }
            $item ['options'] = $options;

            $item ['infuture'] = DateUtil::getDatetimeDiff_AsField($item ['cr_date'],
                    DateUtil::getDatetime(), 6) < 0;
            $pluginsitems []   = $item;
        }

        // Assign the items to the template
        $this->view->assign('pluginsitems', $pluginsitems);

        $this->view->assign('active_plugins', $active_plugins);
        $this->view->assign('total_plugins', $total_plugins);

        // Assign the current status filter and the possible ones
        $this->view->assign('status', $status);
        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('order', $order);
        // Return the output that has been generated by this function
        return $this->view->fetch('admin/viewplugin.tpl');
    }

    public function submitplugin($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        // Get parameters cr_date whatever input we need
        $formElements       = FormUtil::getPassedValue('formElements',
                isset($args ['formElements']) ? $args ['formElements'] : null,
                'POST');
        $formElements       = $this->purifyHtml($formElements);
        $blockinfo          = FormUtil::getPassedValue('blockinfo',
                isset($args ['blockinfo']) ? $args ['blockinfo'] : null, 'POST');
        // echo "<pre>"; print_r($blockinfo); echo "</pre>"; exit;
        // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;
        $blockInfoSerialize = serialize($blockinfo);

        // echo "<pre>"; print_r($blockInfoSerialize); echo "</pre>"; exit;
        // Create the item array for processing

        if ($formElements ['elemtName']) { // PLUGIN
            $typ        = str_replace(' ', '',
                strtolower($formElements ['elemtName']));
            $dps        = array();
            $isDepended = $formElements ['service_depended'];
            // if ($isDepended == true) {
            $dps        = $formElements ['depend_services'];
            // echo "<pre>"; print_r($dps); echo "</pre>"; exit;
            // $dps = implode(',', $dps);
            if (count($dps) > 0) {
                foreach ($dps as $plugin_id => $type) {
                    $dpsArr [] = array(
                        'plugin_id' => $plugin_id,
                        'type' => $type,
                        'name' => $formElements ['servicename'] [$plugin_id]
                    );
                }
                $dps_serialized = serialize($dpsArr);
            } else {
                $dps_serialized = '';
            }
            // }

            $shopDepended = $formElements ['shop_depended'];
            $shoptypes    = $formElements ['depend_shoptype'];
            foreach ($shoptypes as $shoptype_id => $shoptype) {
                $shoptypesArr [] = $shoptype;
            }
            // echo "<pre>"; print_r($shoptypesArr); echo "</pre>"; exit;
            // $shoptypes_serialized = serialize($shoptypesArr);
            $shoptypes_implode = implode(',', $shoptypesArr);
            // echo $shoptypes_implode; exit;
            // echo "<pre>"; print_r($dpsArr); echo "</pre>"; exit;

            $item = array(
                'plugin_name' => $formElements ['elemtName'],
                // 'type' => $typ,
                'type' => $formElements ['identifier'],
                'identifier' => $formElements ['identifier'],
                'qty_based' => $formElements ['qty_based'],
                'description' => isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                        : '',
                'content' => $blockInfoSerialize,
                'price' => isset($formElements ['price']) ? $formElements ['price']
                        : '',
                'status' => isset($formElements ['status']) ? $formElements ['status']
                        : 0,
                'service_depended' => isset($formElements ['service_depended']) ? $formElements ['service_depended']
                        : 0,
                'depended_services' => $dps_serialized,
                'shop_depended' => isset($formElements ['shop_depended']) ? $formElements ['shop_depended']
                        : 0,
                'depended_shoptypes' => $shoptypes_implode,
                'is_editable' => isset($formElements ['is_editable']) ? 1 : '',
                'func_name' => isset($formElements ['func_name']) ? $formElements ['func_name']
                        : '',
                'demo' => isset($formElements ['demo']) ? $formElements ['demo']
                        : 0,
                'demoperiod' => isset($formElements ['demoperiod']) ? $formElements ['demoperiod']
                        : 0
            );

            if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) { // insert
                $args     = array(
                    'table' => 'zselex_plugin',
                    'element' => $item,
                    'Id' => 'plugin_id'
                );
                // Create the zselex type
                $result   = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement',
                        $args);
                $InsertId = DBUtil::getInsertID($args ['table'], $args ['Id']);
                pnModCallHooks('plugin', 'create', $InsertId,
                    array(
                    'module' => 'ZSELEX'
                ));
            } else { // update
                $InsertId = $formElements ['elemId'];
                // update the type

                if ($formElements ['type'] != 'theme') {
                    $typ = str_replace(' ', '',
                        strtolower($formElements ['elemtName']));
                } else {
                    $typ = 'theme';
                }

                $item       = array(
                    'plugin_id' => $formElements ['elemId'],
                    'plugin_name' => $formElements ['elemtName'],
                    'type' => $formElements ['identifier'],
                    'identifier' => $formElements ['identifier'],
                    'qty_based' => $formElements ['qty_based'],
                    'description' => isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                            : '',
                    'content' => $blockInfoSerialize,
                    'price' => isset($formElements ['price']) ? $formElements ['price']
                            : '',
                    'status' => isset($formElements ['status']) ? $formElements ['status']
                            : 0,
                    'service_depended' => isset($formElements ['service_depended'])
                            ? $formElements ['service_depended'] : 0,
                    'depended_services' => $dps_serialized,
                    'shop_depended' => isset($formElements ['shop_depended']) ? $formElements ['shop_depended']
                            : 0,
                    'depended_shoptypes' => $shoptypes_implode,
                    'is_editable' => isset($formElements ['is_editable']) ? 1 : '',
                    'func_name' => isset($formElements ['func_name']) ? $formElements ['func_name']
                            : '',
                    'demo' => isset($formElements ['demo']) ? $formElements ['demo']
                            : 0,
                    'demoperiod' => isset($formElements ['demoperiod']) ? $formElements ['demoperiod']
                            : 0
                );
                $updateargs = array(
                    'table' => 'zselex_plugin',
                    'IdValue' => $InsertId,
                    'IdName' => 'plugin_id',
                    'element' => $item
                );

                $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement',
                        $updateargs);

                // $delParentQuery = "DELETE FROM zselex_parent WHERE childType = '" . $formElements['childType'] . "' AND childId = " . $InsertId . "";
                // DBUtil::executeSQL($delParentQuery);
                // $delresult = ModUtil::apiFunc('ZSELEX', 'admin', 'execteQuery', $delParentQuery);
            }

            if ($result == true) {
                // Success
                if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) {
                    LogUtil::registerStatus($this->__('Done! Plugin has been created successfully.'));
                } else {
                    LogUtil::registerStatus($this->__('Done! Plugin details has been updated successfully.'));
                }

                $childType = $formElements ['childType'];
                if ($formElements ['parentplugin'] && !empty($formElements ['parentplugin_list'])) {
                    $parentId   = $formElements ['parentplugin'];
                    $parentType = 'PLUGIN';

                    $parentItem = array(
                        'childType' => $childType,
                        'childId' => $InsertId,
                        'parentId' => $parentId,
                        'parentType' => $parentType
                    );
                    $args       = array(
                        'table' => 'zselex_parent',
                        'element' => $parentItem,
                        'Id' => 'tableId'
                    );
                    // Create the zselex type
                    $result     = ModUtil::apiFunc('ZSELEX', 'admin',
                            'createElement', $args);
                }
            } else {
                // fail! type not created
                throw new Zikula_Exception_Fatal($this->__('Story not created for unknown reason (Api failure).'));

                return false;
            }
        }

        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewplugin'));
    }

    public function createplugin($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $languages          = ZLanguage::getInstalledLanguages();
        $this->view->assign('languages', $languages);
        $serviceidentifiers = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $args               = array(
                'table' => 'zselex_service_identifiers',
                'where' => '',
                'orderBy' => 'identifier ASC',
                'useJoins' => ''
        ));
        $this->view->assign('identifiers', $serviceidentifiers);

        $plugins   = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $args      = array(
                'table' => 'zselex_plugin',
                'where' => "bundle_id=0 AND type!=''",
                'orderBy' => 'plugin_name ASC',
                'useJoins' => ''
        ));
        $this->view->assign('plugins', $plugins);
        $shoptypes = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $args      = array(
                'table' => 'zselex_shop_types',
                'where' => '',
                'orderBy' => 'shoptype ASC',
                'useJoins' => ''
        ));
        $this->view->assign('shoptypes', $shoptypes);

        return $this->view->fetch('admin/createplugin.tpl');
    }

    public function modifyplugin()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        // echo "modifycity";
        $plugin_id = FormUtil::getPassedValue('plugin_id',
                isset($args ['plugin_id']) ? $args ['plugin_id'] : null,
                'GETPOST');

        $args             = array(
            'table' => 'zselex_plugin',
            'IdValue' => $plugin_id,
            'IdName' => 'plugin_id'
        );
        // Get the news type in the db
        $item             = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement',
                $args);
        $depend_services  = unserialize($item ['depended_services']);
        $depend_shoptypes = explode(',', $item ['depended_shoptypes']);
        // echo "<pre>"; print_r($depend_services); echo "</pre>";
        // echo "<pre>"; print_r($depend_shoptypes); echo "</pre>";

        $languages = ZLanguage::getInstalledLanguages();
        // echo "<pre>"; print_r($item); echo "</pre>";

        $content = unserialize($item ['content']);

        // echo "<pre>"; print_r($content); echo "</pre>";
        $serviceidentifiers = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $args               = array(
                'table' => 'zselex_service_identifiers',
                'where' => '',
                'orderBy' => 'identifier ASC',
                'useJoins' => ''
        ));
        $this->view->assign('identifiers', $serviceidentifiers);

        $plugins = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $args    = array(
                'table' => 'zselex_plugin',
                'where' => "bundle_id=0 AND type!=''",
                'orderBy' => 'plugin_name ASC',
                'useJoins' => ''
        ));
        $this->view->assign('plugins', $plugins);

        $shoptypes = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $args      = array(
                'table' => 'zselex_shop_types',
                'where' => '',
                'orderBy' => 'shoptype ASC',
                'useJoins' => ''
        ));
        $this->view->assign('shoptypes', $shoptypes);

        $this->view->assign('content', $content);
        $this->view->assign('languages', $languages);
        $this->view->assign('item', $item);
        $this->view->assign('depend_services', $depend_services);
        $this->view->assign('depend_shoptypes', $depend_shoptypes);
        // echo "<pre>"; print_r($depend_services); echo "</pre>";
        return $this->view->fetch('admin/createplugin.tpl');
    }

    public function deleteplugin($args)
    {
        $plugin_id = FormUtil::getPassedValue('plugin_id',
                isset($args ['plugin_id']) ? $args ['plugin_id'] : null,
                'REQUEST');

        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');

        if (empty($confirmation)) {
            $this->view->assign('IdValue', $plugin_id);
            $this->view->assign('confirm_title',
                $this->__f('Delete %s', $this->__('Service')));
            $this->view->assign('confirm_msg',
                $this->__f('Do you want to delete this %s', $this->__('Service')));
            $this->view->assign('IdName', 'plugin_id');
            $this->view->assign('submitFunc', 'deleteplugin');
            $this->view->assign('cancelFunc', 'viewplugin');
            // Return the output that has been generated by this function
            // return $this->view->fetch('admin/deleteshop.tpl');
            return $this->view->fetch('admin/deletecommon1.tpl');
        }

        $item = array(
            'plugin_id' => $plugin_id,
            'status' => 0
        );
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            $updateargs = array(
                'table' => 'zselex_plugin',
                'IdValue' => $plugin_id,
                'IdName' => 'plugin_id',
                'element' => $item
            );

            $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement',
                    $updateargs);
        } elseif (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            // /
            $result = DBUtil::deleteObjectById('zselex_plugin', $plugin_id,
                    'plugin_id');
            DBUtil::deleteWhere('zselex_service_bundle_items',
                "WHERE plugin_id=$plugin_id");
            DBUtil::deleteWhere('zselex_serviceshop',
                "WHERE plugin_id=$plugin_id");
        }
        // Delete
        if ($result) {
            // Success
            LogUtil::registerStatus($this->__('Done! Type has been deleted successfully.'));

            // Let any hooks know that we have deleted an item
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewplugin'));
    }

    public function submitcountry($args)
    {
        $formElements = FormUtil::getPassedValue('formElements',
                isset($args ['formElements']) ? $args ['formElements'] : null,
                'POST');
        $formElements = $this->purifyHtml($formElements);
        if ($formElements ['elemtName']) { // PLUGIN
            $item = array(
                'country_name' => $formElements ['elemtName'],
                'description' => isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                        : '',
                'status' => isset($formElements ['status']) ? $formElements ['status']
                        : 0
            );

            if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) {

                /*
                 * $args = array(
                 * 'table' => 'zselex_country',
                 * 'element' => $item,
                 * 'Id' => 'country_id'
                 * );
                 * // Create the zselex type
                 * $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $args);
                 * $InsertId = DBUtil::getInsertID($args['table'], $args['Id']);
                 */

                $country  = new ZSELEX_Entity_Country ();
                $country->setCountry_name($item ['country_name']);
                $country->setDescription($item ['description']);
                $country->setStatus($item ['status']);
                $this->entityManager->persist($country);
                $this->entityManager->flush();
                $InsertId = $country->getCountry_id();
                $result   = $InsertId;
            } else {
                $InsertId   = $formElements ['elemId'];
                // update the type
                $item       = array(
                    'country_id' => $formElements ['elemId'],
                    'country_name' => $formElements ['elemtName'],
                    'description' => isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                            : '',
                    'status' => isset($formElements ['status']) ? $formElements ['status']
                            : 0
                );
                $updateargs = array(
                    'table' => 'zselex_country',
                    'IdValue' => $InsertId,
                    'IdName' => 'country_id',
                    'element' => $item
                );

                $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement',
                        $updateargs);

                $result = $this->entityManager->getRepository('ZSELEX_Entity_Country')->updateEntity(null,
                    'ZSELEX_Entity_Country', $item,
                    array(
                    'a.country_id' => $InsertId
                ));
                // $delresult = ModUtil::apiFunc('ZSELEX', 'admin', 'execteQuery', $delParentQuery);
            }
        }

        if ($result != false) {
            // Success
            if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) {
                LogUtil::registerStatus($this->__('Done! Country has been created successfully.'));
            } else {
                LogUtil::registerStatus($this->__('Done! Country details has been updated.'));
            }
            $childType = $formElements ['childType'];
            if ($formElements ['parentCountry'] && !empty($formElements ['parentcountry_list'])) {
                $parentId   = $formElements ['parentCountry'];
                $parentType = 'COUNTRY';
                $parentItem = array(
                    'childType' => $childType,
                    'childId' => $InsertId,
                    'parentId' => $parentId,
                    'parentType' => $parentType
                );
                $args       = array(
                    'table' => 'zselex_parent',
                    'element' => $parentItem,
                    'Id' => 'tableId'
                );
                // Create the zselex type
                $result     = ModUtil::apiFunc('ZSELEX', 'admin',
                        'createElement', $args);
            } else {
                $parentId   = '0';
                $parentType = 'COUNTRY';

                $parentItem = array(
                    'childType' => $childType,
                    'childId' => $InsertId,
                    'parentId' => $parentId,
                    'parentType' => $parentType
                );
                $args       = array(
                    'table' => 'zselex_parent',
                    'element' => $parentItem,
                    'Id' => 'tableId'
                );
                // Create the zselex type
                $result     = ModUtil::apiFunc('ZSELEX', 'admin',
                        'createElement', $args);
            }
        } else {
            // fail! type not created
            throw new Zikula_Exception_Fatal($this->__('Story not created for unknown reason (Api failure).'));

            return false;
        }

        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewcountry'));
    }

    public function modifycountry()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        // echo "modifycity";
        $country_id = FormUtil::getPassedValue('id',
                isset($args ['id']) ? $args ['id'] : null, 'GETPOST');

        $args = array(
            'table' => 'zselex_country',
            'IdValue' => $country_id,
            'IdName' => 'country_id'
        );
        // Get the news type in the db
        $item = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $args);

        $parentArgs                 = array(
            'childType' => 'COUNTRY',
            'childId' => $item ['country_id']
        );
        $parentArgs ['parentTable'] = 'zselex_country';
        $parentArgs ['parentId']    = 'country_id';
        $parentArgs ['parentType']  = 'COUNTRY';
        $parentcountry              = ModUtil::apiFunc('ZSELEX', 'admin',
                'getParents', $parentArgs);
        $item ['parentcountry']     = $parentcountry [0] ['country_name'];
        $item ['parentcountry_id']  = $parentcountry [0] ['country_id'];

        $this->view->assign('item', $item);

        return $this->view->fetch('admin/createcountry.tpl');
    }

    public function viewcountry($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        // initialize sort array - used to display sort classes and urls
        $sort   = array();
        $fields = array(
            'country_id',
            'countryname',
            'status',
            'cr_date',
            'lu_date'
        ); // possible sort fields

        $itemsperpage  = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 25,
                'GETPOST');
        $startnum      = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $status        = FormUtil::getPassedValue('status',
                isset($args ['status']) ? $args ['status'] : null, 'GETPOST');
        $order         = FormUtil::getPassedValue('order',
                isset($args ['order']) ? $args ['order'] : 'cr_date', 'GETPOST');
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
                    'viewcountry',
                    array(
                    'status' => $status,
                    'filtercats_serialized' => serialize($filtercats),
                    'order' => $field,
                    'sdir' => $sdir
            ));
        }
        $this->view->assign('sort', $sort);

        $this->view->assign('filter_active', $status);

        $sql = 'SELECT c.* FROM zselex_country AS c
                  WHERE c.country_id IS NOT NULL ';
        if (isset($status) && $status != '') {
            $sql .= ' AND c.status = '.$status;
            $cntryArgs ['where'] ['a.status'] = $status;
        }
        if (isset($searchtext) && $searchtext != '') {
            $sql .= " AND c.country_name LIKE '%".DataUtil::formatForStore($searchtext)."%'";
            $cntryArgs ['like'] ['a.country_name'] = '%'.DataUtil::formatForStore($searchtext).'%';
        }
        if (isset($order) && $order != '') {
            $sql .= ' ORDER BY c.'.$order.' '.$orderdir;
            $cntryArgs ['orderby'] = 'a.'.$order.' '.$orderdir;
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

        $repository = $this->entityManager->getRepository('ZSELEX_Entity_Country');

        $cntryArgs ['entity']     = 'ZSELEX_Entity_Country';
        $cntryArgs ['startlimit'] = $startnum;
        $cntryArgs ['offset']     = $itemsperpage;
        $cntryArgs ['paginate']   = true;
        $cntryArgs ['fields']     = array(
            'a.country_id',
            'a.country_name',
            'a.description',
            'a.status',
            'a.cr_date',
            'a.cr_uid',
            'a.lu_date',
            'a.lu_uid'
        );
        $countries                = $repository->getAll($cntryArgs);

        $items = $countries ['result'];
        // $test = $repository->getCountry();
        // echo "<pre>"; print_r($items); echo "</pre>";

        for ($i = 0; $i < count($items); ++$i) {
            $parentArgs                   = array(
                'childType' => 'COUNTRY',
                'childId' => $items [$i] ['country_id']
            );
            $parentArgs ['parentTable']   = 'zselex_country';
            $parentArgs ['parentId']      = 'country_id';
            $parentArgs ['parentType']    = 'COUNTRY';
            $items [$i] ['parentcountry'] = ModUtil::apiFunc('ZSELEX', 'admin',
                    'getParents', $parentArgs);

            $parentArgs ['parentTable']  = 'zselex_region';
            $parentArgs ['parentId']     = 'region_id';
            $parentArgs ['parentType']   = 'REGION';
            $items [$i] ['parentregion'] = ModUtil::apiFunc('ZSELEX', 'admin',
                    'getParents', $parentArgs);

            $parentArgs ['parentTable'] = 'zselex_city';
            $parentArgs ['parentId']    = 'city_id';
            $parentArgs ['parentType']  = 'CITY';
            $items [$i] ['parentcity']  = ModUtil::apiFunc('ZSELEX', 'admin',
                    'getParents', $parentArgs);

            $parentArgs ['parentTable'] = 'zselex_shop';
            $parentArgs ['parentId']    = 'shop_id';
            $parentArgs ['parentType']  = 'SHOP';
            $items [$i] ['parentshop']  = ModUtil::apiFunc('ZSELEX', 'admin',
                    'getParents', $parentArgs);

            $parentArgs ['parentTable'] = 'zselex_advertise';
            $parentArgs ['parentId']    = 'advertise_id';
            $parentArgs ['parentType']  = 'AD';
            $items [$i] ['parentad']    = ModUtil::apiFunc('ZSELEX', 'admin',
                    'getParents', $parentArgs);

            $parentArgs ['parentTable'] = 'zselex_plugin';
            $parentArgs ['parentId']    = 'plugin_id';
            $parentArgs ['parentType']  = 'PLUGIN';

            $items [$i] ['parentplugin'] = ModUtil::apiFunc('ZSELEX', 'admin',
                    'getParents', $parentArgs);

            // Get the Username for create and update
            /*
             * $createargs = array('table' => 'users', 'IdValue' => $items[$i]['cr_uid'], 'IdName' => 'uid');
             * $users = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $createargs);
             * $items[$i]['createduser'] = $users['uname'];
             *
             * $updateargs = array('table' => 'users', 'IdValue' => $items[$i]['lu_uid'], 'IdName' => 'uid');
             * $users = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $updateargs);
             * $items[$i]['updateduser'] = $users['uname'];
             *
             */
        }

        /*
         * $where = " country_id IS NOT NULL";
         * if (isset($status) && $status != '') {
         * $where .= " AND status = " . $status;
         * }
         * if (isset($searchtext) && $searchtext != "") {
         * $where .= " AND country_name LIKE '%" . DataUtil::formatForStore($searchtext) . "%'";
         * }
         * $getCountArgs = array(
         * 'table' => 'zselex_country',
         * 'where' => $where,
         * 'Id' => 'country_id',
         * 'status' => $status
         * );
         *
         * $total_countrys = ModUtil::apiFunc('ZSELEX', 'admin', 'countElements', $getCountArgs);
         */

        $total_countrys = $countries ['count'];

        // Set the possible status for later use
        $itemstatus = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );

        $countrysitems = array();
        foreach ($items as $item) {
            $options = array();

            // $options[] = array('url' => ModUtil::url('ZSELEX', 'admin', 'displaycountry', array('country_id' => $item['country_id'])),
            // 'image' => '14_layer_visible.png',
            // 'title' => $this->__('View'));

            if (SecurityUtil::checkPermission('ZSELEX::',
                    "{$item['cr_uid']}::{$item['country_id']}", ACCESS_EDIT)) {
                $options [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'modifycountry',
                        array(
                        'id' => $item ['country_id']
                    )),
                    'image' => 'xedit.png',
                    'title' => $this->__('Edit')
                );

                if ((SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['country_id']}",
                        ACCESS_DELETE)) || SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['country_id']}", ACCESS_ADMIN)) {
                    $options [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin',
                            'deletecountry',
                            array(
                            'country_id' => $item ['country_id']
                        )),
                        'image' => '14_layer_deletelayer.png',
                        'title' => $this->__('Delete')
                    );
                }
            }
            $item ['options'] = $options;

            $item ['infuture'] = DateUtil::getDatetimeDiff_AsField($item ['cr_date'],
                    DateUtil::getDatetime(), 6) < 0;
            $countrysitems []  = $item;
        }

        // Assign the items to the template
        $this->view->assign('countrysitems', $countrysitems);

        $this->view->assign('total_countrys', $total_countrys);

        // Assign the current status filter and the possible ones
        $this->view->assign('status', $status);
        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('order', $order);
        // Return the output that has been generated by this function
        return $this->view->fetch('admin/viewcountry.tpl');
    }

    public function deletecountry($args)
    {
        $country_id = FormUtil::getPassedValue('country_id',
                isset($args ['country_id']) ? $args ['country_id'] : null,
                'REQUEST');
        $item       = array(
            'country_id' => $country_id,
            'status' => 0
        );
        $updateargs = array(
            'table' => 'zselex_country',
            'IdValue' => $country_id,
            'IdName' => 'country_id',
            'element' => $item
        );

        // $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement', $updateargs);

        $result = $this->entityManager->getRepository('ZSELEX_Entity_Country')->updateEntity(null,
            'ZSELEX_Entity_Country', $item,
            array(
            'a.country_id' => $country_id
        ));
        // Delete
        if ($result) {
            // Success
            LogUtil::registerStatus($this->__('Done! Type has been deleted successfully.'));

            // Let any hooks know that we have deleted an item
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewcountry'));
    }

    public function submitbranch($args)
    {
        $formElements = FormUtil::getPassedValue('formElements',
                isset($args ['formElements']) ? $args ['formElements'] : null,
                'POST');
        $formElements = $this->purifyHtml($formElements);
        if ($formElements ['elemtName']) { // BRANCH
            $item = array(
                'branch_name' => $formElements ['elemtName'],
                'description' => isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                        : '',
                'status' => isset($formElements ['status']) ? $formElements ['status']
                        : 0
            );

            if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) {
                $branch = new ZSELEX_Entity_Branch ();
                $branch->setBranch_name($formElements ['elemtName']);
                $branch->setDescription(isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                            : '' );
                $branch->setStatus(isset($formElements ['status']) ? $formElements ['status']
                            : 0 );
                $this->entityManager->persist($branch);
                $this->entityManager->flush();

                $InsertId = $branch->getBranch_id();
                $result   = $InsertId;
            } else {
                $InsertId = $formElements ['elemId'];
                // update the type
                $item     = array(
                    'branch_id' => $formElements ['elemId'],
                    'branch_name' => $formElements ['elemtName'],
                    'description' => isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                            : '',
                    'status' => isset($formElements ['status']) ? $formElements ['status']
                            : 0
                );

                $result = $this->entityManager->getRepository('ZSELEX_Entity_Branch')->updateEntity(array(
                    'entity' => 'ZSELEX_Entity_Branch',
                    'fields' => $item,
                    'where' => array(
                        'a.branch_id' => $InsertId
                    )
                ));
            }
        }
        if ($result != false) {
            // Success
            if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) {
                LogUtil::registerStatus($this->__('Done! Branch has been created successfully.'));
            } else {
                LogUtil::registerStatus($this->__('Done! Branch details has been updated successfully.'));
            }
        } else {
            // fail! type not created
            throw new Zikula_Exception_Fatal($this->__('Story not created for unknown reason (Api failure).'));

            return false;
        }

        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewbranch'));
    }

    public function modifybranch()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $branchRepo = $this->entityManager->getRepository('ZSELEX_Entity_Category');
        // echo "modifycity";
        $branch_id  = FormUtil::getPassedValue('id',
                isset($args ['id']) ? $args ['id'] : null, 'GETPOST');

        $args = array(
            'table' => 'zselex_branch',
            'IdValue' => $branch_id,
            'IdName' => 'branch_id'
        );
        // Get the news type in the db
        $item = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $args);
        $item = $branchRepo->get(array(
            'entity' => 'ZSELEX_Entity_Branch',
            // 'fields'=>
            'where' => array(
                'a.branch_id' => $branch_id
            )
        ));

        // echo "<pre>"; print_r($cities); echo "</pre>";

        $this->view->assign('ztypes', $aSelectArray ['types']);
        $this->view->assign('zcities', $aSelectArray ['cities']);
        $this->view->assign('zshops', $aSelectArray ['shops']);
        $this->view->assign('zplugins', $aSelectArray ['plugins']);
        $this->view->assign('zecommerce', $aSelectArray ['ecommerce']);
        $this->view->assign('zcountry', $aSelectArray ['country']);
        $this->view->assign('zregion', $aSelectArray ['region']);
        $this->view->assign('zbranch', $aSelectArray ['branch']);
        $this->view->assign('zad', $aSelectArray ['ad']);
        $this->view->assign('item', $item);

        return $this->view->fetch('admin/createbranch.tpl');
    }

    public function viewbranch($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $branchRepo = $this->entityManager->getRepository('ZSELEX_Entity_Category');
        // initialize sort array - used to display sort classes and urls
        $sort       = array();
        $fields     = array(
            'branch_id',
            'branchname',
            'status',
            'cr_date',
            'lu_date'
        ); // possible sort fields

        $itemsperpage  = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 15,
                'GETPOST');
        $startnum      = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $status        = FormUtil::getPassedValue('status',
                isset($args ['status']) ? $args ['status'] : null, 'GETPOST');
        $order         = FormUtil::getPassedValue('order',
                isset($args ['order']) ? $args ['order'] : 'cr_date', 'GETPOST');
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
                    'viewbranch',
                    array(
                    'status' => $status,
                    'filtercats_serialized' => serialize($filtercats),
                    'order' => $field,
                    'sdir' => $sdir
            ));
        }
        $this->view->assign('sort', $sort);

        $this->view->assign('filter_active', $status);

        if (isset($status) && $status != '') {
            $brnchArgs ['where'] ['a.status'] = $status;
        }
        if (isset($searchtext) && $searchtext != '') {
            $brnchArgs ['like'] ['a.branch_name '] = '%'.DataUtil::formatForStore($searchtext).'%';
        }
        if (isset($order) && $order != '') {
            $brnchArgs ['orderby'] = 'a.'.$order.' '.$orderdir;
        }

        // Get all zselex stories
        $brnchArgs ['startlimit'] = $startnum;
        $brnchArgs ['offset']     = $itemsperpage;

        $brnchArgs ['entity']   = 'ZSELEX_Entity_Branch';
        $brnchArgs ['paginate'] = true;
        $brnchArgs ['fields']   = array(
            'a.branch_id',
            'a.branch_name',
            'a.description',
            'a.status',
            'a.cr_date',
            'a.cr_uid',
            'a.lu_date',
            'a.lu_uid'
        );
        $branchItems            = $branchRepo->getAll($brnchArgs);
        $items                  = $branchItems ['result'];

        // echo "<pre>"; print_r($items); echo "</pre>";

        for ($i = 0; $i < count($items); ++$i) {

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

        $total_branchs = $branchItems ['count'];

        // Set the possible status for later use
        $itemstatus = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );

        $branchsitems = array();
        foreach ($items as $item) {
            $options = array();

            if (SecurityUtil::checkPermission('ZSELEX::',
                    "{$item['cr_uid']}::{$item['branch_id']}", ACCESS_EDIT)) {
                $options [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'modifybranch',
                        array(
                        'id' => $item ['branch_id']
                    )),
                    'image' => 'xedit.png',
                    'title' => $this->__('Edit')
                );

                if ((SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['branch_id']}", ACCESS_DELETE))
                    || SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['branch_id']}", ACCESS_ADMIN)) {
                    $options [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin', 'deletebranch',
                            array(
                            'branch_id' => $item ['branch_id']
                        )),
                        'image' => '14_layer_deletelayer.png',
                        'title' => $this->__('Delete')
                    );
                }
            }
            $item ['options'] = $options;

            $item ['infuture'] = DateUtil::getDatetimeDiff_AsField($item ['cr_date'],
                    DateUtil::getDatetime(), 6) < 0;
            $branchsitems []   = $item;
        }

        // Assign the items to the template
        $this->view->assign('branchsitems', $branchsitems);

        $this->view->assign('total_branchs', $total_branchs);

        // Assign the current status filter and the possible ones
        $this->view->assign('status', $status);
        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('order', $order);
        // Return the output that has been generated by this function
        return $this->view->fetch('admin/viewbranch.tpl');
    }

    public function deletebranch($args)
    {
        $branch_id = FormUtil::getPassedValue('branch_id',
                isset($args ['branch_id']) ? $args ['branch_id'] : null,
                'REQUEST');
        $item      = array(
            'branch_id' => $branch_id,
            'status' => 0
        );
        /*
         * $updateargs = array(
         * 'table' => 'zselex_branch',
         * 'IdValue' => $branch_id,
         * 'IdName' => 'branch_id',
         * 'element' => $item
         * );
         *
         * $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement', $updateargs);
         */
        $upd_args  = array(
            'entity' => 'ZSELEX_Entity_Branch',
            'fields' => $item,
            'where' => array(
                'a.branch_id' => $branch_id
            )
        );
        $result    = $this->entityManager->getRepository('ZSELEX_Entity_Branch')->updateEntity($upd_args);
        // Delete
        if ($result) {
            // Success
            LogUtil::registerStatus($this->__('Done! Type has been deleted successfully.'));

            // Let any hooks know that we have deleted an item
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewbranch'));
    }

    public function submitregion($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $formElements = FormUtil::getPassedValue('formElements',
                isset($args ['formElements']) ? $args ['formElements'] : null,
                'POST');
        $formElements = $this->purifyHtml($formElements);
        if ($formElements ['elemtName']) { // REGION
            $item = array(
                'region_name' => $formElements ['elemtName'],
                'country_id' => $formElements ['country-combo'],
                'description' => isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                        : '',
                'status' => isset($formElements ['status']) ? $formElements ['status']
                        : 0
            );

            if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) {
                $args = array(
                    'table' => 'zselex_region',
                    'element' => $item,
                    'Id' => 'region_id'
                );
                // Create the zselex type
                // $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $args);
                // $InsertId = DBUtil::getInsertID($args['table'], $args['Id']);

                $country_id = $formElements ['country-combo'];
                $country    = $this->entityManager->find('ZSELEX_Entity_Country',
                    $formElements ['country-combo']);
                // echo $article; exit;
                // echo $country; exit;
                // echo "<pre>"; print_r($country); echo "</pre>"; exit;
                $region     = new ZSELEX_Entity_Region ();
                $region->setRegion_name($formElements ['elemtName']);
                $region->setCountry($country);
                $region->setDescription($formElements ['elemtDesc']);
                $region->setStatus($formElements ['status']);
                $this->entityManager->persist($region);
                $this->entityManager->flush();
                $InsertId   = $region->getRegion_id();
                $result     = $InsertId;

                // $upd_country = $this->entityManager->getRepository('ZSELEX_Entity_Region')->updateEntity($upd_args);
            } else {

                // echo "helloo"; exit;
                $InsertId = $formElements ['elemId'];
                // update the type
                $item     = array(
                    // 'region_id' => $formElements['elemId'],
                    'region_name' => $formElements ['elemtName'],
                    'country' => $formElements ['country-combo'],
                    'description' => isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                            : '',
                    'status' => isset($formElements ['status']) ? $formElements ['status']
                            : 0
                );

                // echo $InsertId; exit;
                /*
                 * $updateargs = array(
                 * 'table' => 'zselex_region',
                 * 'IdValue' => $InsertId,
                 * 'IdName' => 'region_id',
                 * 'element' => $item
                 * );
                 *
                 * $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement', $updateargs);
                 */

                $upd_args = array(
                    'entity' => 'ZSELEX_Entity_Region',
                    'fields' => $item,
                    'where' => array(
                        'a.region_id' => $InsertId
                    )
                );
                $result   = $this->entityManager->getRepository('ZSELEX_Entity_Region')->updateEntity($upd_args);

                // $delParentQuery = "DELETE FROM zselex_parent WHERE childType = '" . $formElements['childType'] . "' AND childId = " . $InsertId . "";
                // DBUtil::executeSQL($delParentQuery);
                // $delresult = ModUtil::apiFunc('ZSELEX', 'admin', 'execteQuery', $delParentQuery);
            }
        }
        if ($result != false) {
            // Success
            if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) {
                LogUtil::registerStatus($this->__('Done! Region has been created succesfully.'));
            } else {
                LogUtil::registerStatus($this->__('Done! Region details has been updated successfully.'));
            }

            $childType = $formElements ['childType'];

            if ($formElements ['parentCountry'] && !empty($formElements ['parentcountry_list'])) {
                $parentId   = $formElements ['parentCountry'];
                $parentType = 'COUNTRY';

                $parentItem = array(
                    'childType' => $childType,
                    'childId' => $InsertId,
                    'parentId' => $parentId,
                    'parentType' => $parentType
                );
                $args       = array(
                    'table' => 'zselex_parent',
                    'element' => $parentItem,
                    'Id' => 'tableId'
                );
                // Create the zselex type
                $result     = ModUtil::apiFunc('ZSELEX', 'admin',
                        'createElement', $args);
            }
            if ($formElements ['parentRegion'] && !empty($formElements ['parentregion_list'])) {
                $parentId   = $formElements ['parentRegion'];
                $parentType = 'REGION';

                $parentItem = array(
                    'childType' => $childType,
                    'childId' => $InsertId,
                    'parentId' => $parentId,
                    'parentType' => $parentType
                );
                $args       = array(
                    'table' => 'zselex_parent',
                    'element' => $parentItem,
                    'Id' => 'tableId'
                );
                // Create the zselex type
                $result     = ModUtil::apiFunc('ZSELEX', 'admin',
                        'createElement', $args);
            } else {
                $parentId   = '0';
                $parentType = 'REGION';

                $parentItem = array(
                    'childType' => $childType,
                    'childId' => $InsertId,
                    'parentId' => $parentId,
                    'parentType' => $parentType
                );
                $args       = array(
                    'table' => 'zselex_parent',
                    'element' => $parentItem,
                    'Id' => 'tableId'
                );
                // Create the zselex type
                $result     = ModUtil::apiFunc('ZSELEX', 'admin',
                        'createElement', $args);
            }
        } else {
            // fail! type not created
            throw new Zikula_Exception_Fatal($this->__('Story not created for unknown reason (Api failure).'));

            return false;
        }

        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewregion'));
    }

    public function modifyregion()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        // echo "modifycity";
        $region_id = FormUtil::getPassedValue('id',
                isset($args ['id']) ? $args ['id'] : null, 'GETPOST');

        $item = $this->entityManager->getRepository('ZSELEX_Entity_Country')->get(array(
            'entity' => 'ZSELEX_Entity_Region',
            'fields' => array(
                'a.region_id',
                'a.region_name',
                'a.description',
                'b.country_id',
                'a.status',
                'a.cr_date',
                'a.cr_uid',
                'a.lu_date',
                'a.lu_uid',
                'b.country_name'
            ),
            'joins' => array(
                'LEFT JOIN a.country b'
            ),
            'where' => array(
                'a.region_id' => $region_id
            )
        ));

        $countryArgs = array(
            'entity' => 'ZSELEX_Entity_Country',
            'fields' => array(
                'a.country_id',
                'a.country_name'
            ),
            'orderby' => 'a.country_name ASC'
        );
        $countries   = $this->entityManager->getRepository('ZSELEX_Entity_Country')->getAll($countryArgs);
        $this->view->assign('countries', $countries);
        $this->view->assign('item', $item);

        return $this->view->fetch('admin/createregion.tpl');
    }

    public function viewregion($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        // initialize sort array - used to display sort classes and urls
        $sort   = array();
        $fields = array(
            'region_id',
            'regionname',
            'status',
            'cr_date',
            'lu_date'
        ); // possible sort fields

        $itemsperpage  = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 15,
                'GETPOST');
        $startnum      = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $status        = FormUtil::getPassedValue('status',
                isset($args ['status']) ? $args ['status'] : null, 'GETPOST');
        $order         = FormUtil::getPassedValue('order',
                isset($args ['order']) ? $args ['order'] : 'cr_date', 'GETPOST');
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
                    'viewregion',
                    array(
                    'status' => $status,
                    'filtercats_serialized' => serialize($filtercats),
                    'order' => $field,
                    'sdir' => $sdir
            ));
        }
        $this->view->assign('sort', $sort);

        $this->view->assign('filter_active', $status);

        if (isset($status) && $status != '') {
            $regArgs ['where'] ['a.status'] = $status;
        }
        if (isset($searchtext) && $searchtext != '') {
            $regArgs ['like'] ['a.region_name'] = '%'.DataUtil::formatForStore($searchtext).'%';
        }
        if (isset($order) && $order != '') {
            $regArgs ['orderby'] = 'a.'.$order.' '.$orderdir;
        }

        $repo                   = $this->entityManager->getRepository('ZSELEX_Entity_Country');
        $regArgs ['fields']     = array(
            'a.region_id',
            'a.region_name',
            'a.description',
            'b.country_id',
            'a.status',
            'a.cr_date',
            'a.cr_uid',
            'a.lu_date',
            'a.lu_uid',
            'b.country_name'
        );
        $regArgs ['entity']     = 'ZSELEX_Entity_Region';
        $regArgs ['paginate']   = true;
        $regArgs ['startlimit'] = $startnum;
        $regArgs ['offset']     = $itemsperpage;
        $regArgs ['joins']      = array(
            'LEFT JOIN a.country b'
        );
        $regions                = $repo->getAll($regArgs);

        $items = $regions ['result'];

        for ($i = 0; $i < count($items); ++$i) {

            // Get the Username for create and update

            if ($items [$i] ['cr_uid'] > 0) {
                $createdUsers               = $repo->getUserInfo(array(
                    'entity' => 'ZSELEX_Entity_Region',
                    'table' => 'zselex_region',
                    'field' => 'cr_uid',
                    'user_id' => $items [$i] ['cr_uid']
                ));
                $items [$i] ['createduser'] = $createdUsers ['uname'];
            }

            if ($items [$i] ['lu_uid'] > 0) {
                $updateduser                = $repo->getUserInfo(array(
                    'entity' => 'ZSELEX_Entity_Region',
                    'table' => 'zselex_region',
                    'field' => 'lu_uid',
                    'user_id' => $items [$i] ['lu_uid']
                ));
                $items [$i] ['updateduser'] = $updateduser ['uname'];
            }
        }

        $total_regions = $regions ['count'];

        // Set the possible status for later use
        $itemstatus = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );

        $regionsitems = array();
        foreach ($items as $item) {
            $options = array();

            // $options[] = array('url' => ModUtil::url('ZSELEX', 'admin', 'displayregion', array('region_id' => $item['region_id'])),
            // 'image' => '14_layer_visible.png',
            // 'title' => $this->__('View'));

            if (SecurityUtil::checkPermission('ZSELEX::',
                    "{$item['cr_uid']}::{$item['region_id']}", ACCESS_EDIT)) {
                $options [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'modifyregion',
                        array(
                        'id' => $item ['region_id']
                    )),
                    'image' => 'xedit.png',
                    'title' => $this->__('Edit')
                );

                if ((SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['region_id']}", ACCESS_DELETE))
                    || SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['region_id']}", ACCESS_ADMIN)) {
                    $options [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin', 'deleteregion',
                            array(
                            'region_id' => $item ['region_id']
                        )),
                        'image' => '14_layer_deletelayer.png',
                        'title' => $this->__('Delete')
                    );
                }
            }
            $item ['options'] = $options;

            $item ['infuture'] = DateUtil::getDatetimeDiff_AsField($item ['cr_date'],
                    DateUtil::getDatetime(), 6) < 0;
            $regionsitems []   = $item;
        }

        // Assign the items to the template
        $this->view->assign('regionsitems', $regionsitems);

        $this->view->assign('total_regions', $total_regions);

        // Assign the current status filter and the possible ones
        $this->view->assign('status', $status);
        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('order', $order);
        // Return the output that has been generated by this function
        return $this->view->fetch('admin/viewregion.tpl');
    }

    public function deleteregion($args)
    {
        $region_id  = FormUtil::getPassedValue('region_id',
                isset($args ['region_id']) ? $args ['region_id'] : null,
                'REQUEST');
        $item       = array(
            'region_id' => $region_id,
            'status' => 0
        );
        $updateargs = array(
            'table' => 'zselex_region',
            'IdValue' => $region_id,
            'IdName' => 'region_id',
            'element' => $item
        );

        // $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement', $updateargs);
        // $result = DBUtil::deleteObjectById('zselex_region', $region_id, 'region_id');
        $result = $this->entityManager->getRepository('ZSELEX_Entity_Region')->deleteEntity(null,
            'ZSELEX_Entity_Region',
            array(
            'a.region_id' => $region_id
        ));
        // Delete
        if ($result) {
            // Success
            LogUtil::registerStatus($this->__('Done! Type has been deleted successfully.'));

            // Let any hooks know that we have deleted an item
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewregion'));
    }

    public function createcategory($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        // $aSelectArray = ModUtil::apiFunc('ZSELEX', 'admin', 'getElementsSelectArray');
        // echo "<pre>"; print_r($cities); echo "</pre>";

        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Category');

        /*
         * $categories = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements', $args = array(
         * 'table' => 'zselex_category',
         * 'where' => '',
         * 'orderBy' => 'category_name ASC',
         * 'useJoins' => ''
         * ));
         */
        $categories = $repo->getAll(array(
            'entity' => 'ZSELEX_Entity_Category'
        ));
        $this->view->assign('categories', $categories);

        return $this->view->fetch('admin/createcategory.tpl');
    }

    public function submitcategory($args)
    {

        // Get parameters cr_date whatever input we need
        $formElements = FormUtil::getPassedValue('formElements',
                isset($args ['formElements']) ? $args ['formElements'] : null,
                'POST');
        $formElements = $this->purifyHtml($formElements);
        // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;
        // Create the item array for processing

        if ($formElements ['elemtName']) { // PLUGIN
            $item = array(
                'category_name' => $formElements ['elemtName'],
                'parent_cat_id' => $formElements ['category-combo'],
                'description' => isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                        : '',
                'status' => isset($formElements ['status']) ? $formElements ['status']
                        : 0
            );

            if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) {
                $args = array(
                    'table' => 'zselex_category',
                    'element' => $item,
                    'Id' => 'category_id'
                );
                // Create the zselex type
                // $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $args);
                // $InsertId = DBUtil::getInsertID($args['table'], $args['Id']);

                $category = new ZSELEX_Entity_Category ();
                $category->setCategory_name($formElements ['elemtName']);
                $category->setDescription($formElements ['elemtDesc']);
                $category->setStatus($formElements ['status']);
                $this->entityManager->persist($category);
                $this->entityManager->flush();
                // $result = $category->getCategory_id();
                $InsertId = $category->getCategory_id();
                // echo $InsertId; exit;
                $result   = 1;
            } else {
                $InsertId = $formElements ['elemId'];

                // $finds = $this->entityManager->getRepository('ZSELEX_Entity_Category')->findOneBy(array('category_id' => $InsertId))->getOldArray();
                // $finds = $this->entityManager->getRepository('ZSELEX_Entity_Category')->findOneBy(array('category_id' => $InsertId))->getOldArray();
                $updateCategory = $this->entityManager->getRepository('ZSELEX_Entity_Category')->updateCategory($formElements);
                $result         = $updateCategory;

                $result = 1;
            }

            if ($result == true) {
                // Success
                if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) {
                    LogUtil::registerStatus($this->__('Done! Category has been created successfully.'));
                } else {
                    LogUtil::registerStatus($this->__('Done! Category details has been updated successfully.'));
                }
            } else {
                // fail! type not created
                throw new Zikula_Exception_Fatal($this->__('Story not created for unknown reason (Api failure).'));

                return false;
            }
        }

        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewcategory'));
    }

    public function viewcategory($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $repo   = $this->entityManager->getRepository('ZSELEX_Entity_Category');
        error_reporting(null);
        // initialize sort array - used to display sort classes and urls
        $sort   = array();
        $fields = array(
            'category_id',
            'category_name',
            'status',
            'cr_date',
            'lu_date'
        ); // possible sort fields

        $itemsperpage  = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 15,
                'GETPOST');
        $startnum      = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $status        = FormUtil::getPassedValue('status',
                isset($args ['status']) ? $args ['status'] : null, 'GETPOST');
        $order         = FormUtil::getPassedValue('order',
                isset($args ['order']) ? $args ['order'] : 'cr_date', 'GETPOST');
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
                    'viewcategory',
                    array(
                    'status' => $status,
                    'filtercats_serialized' => serialize($filtercats),
                    'order' => $field,
                    'sdir' => $sdir
            ));
        }
        $this->view->assign('sort', $sort);

        $this->view->assign('filter_active', $status);

        /*
         * $sql = "SELECT a.* FROM zselex_category AS a
         * WHERE a.category_id IS NOT NULL ";
         */
        if (isset($status) && $status != '') {
            // $sql .= " AND a.status = " . $status;
            $catArgs ['where'] ['a.status'] = $status;
        }
        if (isset($searchtext) && $searchtext != '') {
            // $sql .= " AND a.category_name LIKE '%" . DataUtil::formatForStore($searchtext) . "%'";
            $catArgs ['like'] ['a.category_name'] = '%'.DataUtil::formatForStore($searchtext).'%';
        }
        if (isset($order) && $order != '') {
            // $sql .= " ORDER BY a." . $order . " " . $orderdir;
            $catArgs ['orderby'] = 'a.'.$order.' '.$orderdir;
        }

        // Get all zselex stories
        $getArgs = array(
            'sql' => $sql,
            'startnum' => $startnum,
            'numitems' => $itemsperpage
        );
        // $items = ModUtil::apiFunc('ZSELEX', 'admin', 'getAll', $getArgs);

        $items                  = array();
        $catArgs ['startlimit'] = $startnum;
        $catArgs ['offset']     = $itemsperpage;
        $catArgs ['entity']     = 'ZSELEX_Entity_Category';
        $catArgs ['fields']     = array(
            'a.category_id',
            'a.category_name',
            'a.description',
            'a.status',
            'a.cr_date',
            'a.cr_uid',
            'a.lu_date',
            'a.lu_uid'
        );
        $catArgs ['paginate']   = true;
        $categoryItems          = $repo->getAll($catArgs);
        $items                  = $categoryItems ['result'];

        // echo "<pre>"; print_r($categoryItems); echo "</pre>";

        for ($i = 0; $i < count($items); ++$i) {

            // echo $items[$i]['parent_cat_id'] . '<br>';
            /*
             * if (!empty($items[$i]['parent_cat_id'])) {
             * $catargs = array(
             * 'table' => 'zselex_category',
             * 'IdValue' => $items[$i]['parent_cat_id'],
             * 'IdName' => 'category_id'
             * );
             * $parentcat = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $catargs);
             * $items[$i]['parentCat'] = $parentcat['category_name'];
             * } else {
             * $items[$i]['parentCat'] = '';
             * }
             */

            // Get the Username for create and update
            /*
             * $createargs = array(
             * 'table' => 'users',
             * 'IdValue' => $items[$i]['cr_uid'],
             * 'IdName' => 'uid'
             * );
             * $users = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $createargs);
             */
            if ($items [$i] ['cr_uid'] > 0) {
                $users                      = $repo->getUserInfo(array(
                    'entity' => 'ZSELEX_Entity_Category',
                    'table' => 'zselex_category',
                    'field' => 'cr_uid',
                    'user_id' => $items [$i] ['cr_uid']
                ));
                $items [$i] ['createduser'] = $users ['uname'];
            }
            /*
             * $updateargs = array(
             * 'table' => 'users',
             * 'IdValue' => $items[$i]['lu_uid'],
             * 'IdName' => 'uid'
             * );
             * $users = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $updateargs);
             */
            if ($items [$i] ['lu_uid'] > 0) {
                $updateUsers                = $repo->getUserInfo(array(
                    'entity' => 'ZSELEX_Entity_Category',
                    'table' => 'zselex_category',
                    'field' => 'cr_uid',
                    'user_id' => $items [$i] ['lu_uid']
                ));
                $items [$i] ['updateduser'] = $updateUsers ['uname'];
            }
        }

        // echo "<pre>"; print_r($items); echo "</pre>";
        // $total_categorys = ModUtil::apiFunc('ZSELEX', 'admin', 'countElements', $getCountArgs);
        $total_categorys = $categoryItems ['count'];

        // Set the possible status for later use
        $itemstatus = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );

        $categorysitems = array();
        foreach ($items as $item) {
            // echo "Date : " . $item['cr_date']->date . '<br>';
            $options = array();

            // $options[] = array('url' => ModUtil::url('ZSELEX', 'admin', 'displaycategory', array('category_id' => $item['category_id'])),
            // 'image' => '14_layer_visible.png',
            // 'title' => $this->__('View'));

            if (SecurityUtil::checkPermission('ZSELEX::',
                    "{$item['cr_uid']}::{$item['category_id']}", ACCESS_EDIT)) {
                $options [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'modifycategory',
                        array(
                        'category_id' => $item ['category_id']
                    )),
                    'image' => 'xedit.png',
                    'title' => $this->__('Edit')
                );

                if ((SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['category_id']}",
                        ACCESS_DELETE)) || SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['category_id']}",
                        ACCESS_ADMIN)) {
                    $options [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin',
                            'deletecategory',
                            array(
                            'category_id' => $item ['category_id']
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
        // Return the output that has been generated by this function
        return $this->view->fetch('admin/viewcategory.tpl');
    }

    public function modifycategory()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $repo        = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        // echo "modifycity";
        $category_id = FormUtil::getPassedValue('category_id',
                isset($args ['category_id']) ? $args ['category_id'] : null,
                'GETPOST');

        $args = array(
            'table' => 'zselex_category',
            'IdValue' => $category_id,
            'IdName' => 'category_id'
        );
        // Get the news type in the db
        // $item = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $args);
        $item = $repo->get(array(
            'entity' => 'ZSELEX_Entity_Category',
            'where' => array(
                'a.category_id' => $category_id
            )
        ));

        // echo "<pre>"; print_r($item); echo "</pre>"; exit;
        /*
         * $parentArgs = array(
         * 'childType' => 'CATEGORY',
         * 'childId' => $item['category_id']
         * );
         * $parentArgs['parentTable'] = 'zselex_category';
         * $parentArgs['parentId'] = 'category_id';
         * $parentArgs['parentType'] = 'CATEGORY';
         *
         * $parentcategory = ModUtil::apiFunc('ZSELEX', 'admin', 'getParents', $parentArgs);
         * $item['parentcategory'] = $parentcategory[0]['category_name'];
         * $item['parentcategory_id'] = $parentcategory[0]['category_id'];
         */
        /*
         * $categories = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements', $args = array(
         * 'table' => 'zselex_category',
         * 'where' => '',
         * 'orderBy' => 'category_name ASC',
         * 'useJoins' => ''
         * ));
         */
        $categories = $repo->getAll(array(
            'entity' => 'ZSELEX_Entity_Category',
            // 'where' => array('a.category_id' => $category_id),
            'orderby' => 'a.category_name ASC'
        ));
        $this->view->assign('categories', $categories);

        $this->view->assign('item', $item);

        return $this->view->fetch('admin/createcategory.tpl');
    }

    public function deletecategory($args)
    {
        $category_id = FormUtil::getPassedValue('category_id',
                isset($args ['category_id']) ? $args ['category_id'] : null,
                'REQUEST');
        $item        = array(
            'category_id' => $category_id,
            'status' => 0
        );
        $updateargs  = array(
            'table' => 'zselex_category',
            'IdValue' => $category_id,
            'IdName' => 'category_id',
            'element' => $item
        );

        // echo "comes here"; exit;
        // $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement', $updateargs);
        $result = DBUtil::deleteObjectById('zselex_category', $category_id,
                'category_id');
        // Delete
        if ($result) {
            DBUtil::deleteObjectById('zselex_shop_to_category', $category_id,
                'category_id');
            // Success
            LogUtil::registerStatus($this->__('Done! Category has been deleted successfully.'));

            // Let any hooks know that we have deleted an item
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewcategory'));
    }

    public function make_path($pathname, $is_filename = false)
    {
        if ($is_filename) {
            $pathname = substr($pathname, 0, strrpos($pathname, '/'));
        }

        // Check if directory already exists

        if (is_dir($pathname) || empty($pathname)) {
            return true;
        }

        // Ensure a file does not already exist with the same name

        $pathname = str_replace(array(
            '/',
            '\\'
            ), DIRECTORY_SEPARATOR, $pathname);

        if (is_file($pathname)) {
            trigger_error('mkdirr() File exists', E_USER_WARNING);

            return false;
        }

        // Crawl up the directory tree

        $next_pathname = substr($pathname, 0,
            strrpos($pathname, DIRECTORY_SEPARATOR));

        if (make_path($next_pathname, $mode)) {
            if (!file_exists($pathname)) {
                return mkdir($pathname, $mode);
            }
        }

        return false;
    }

    public function createShopImage($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
        if (!(int) ($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)));
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $modvariable   = $this->getVars();
        $diskquotasize = $modvariable ['diskquotaitem'];

        $loguser        = UserUtil::getVar('uid');
        $loguser        = !empty($loguser) ? $loguser : 0;
        $user_id        = $loguser;
        $ownerName      = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args           = array(
                'shop_id' => $shop_id
        ));
        $message        = '';
        $disabled       = '';
        $servicedisable = false;

        $serviceargs       = array(
            'shop_id' => FormUtil::getPassedValue('shop_id', null, 'REQUEST'),
            'user_id' => $user_id,
            'type' => 'minisiteimages'
        );
        // $servicePermission = $this->serviceCheck($serviceargs);
        $servicePermission = $this->servicePermission($serviceargs);
        $this->view->assign('servicePermission', $servicePermission ['perm']);
        // echo "<pre>"; print_r($servicePermission); exit;
        if ($servicePermission ['perm'] < 1) {
            return LogUtil::registerError($this->__($servicePermission ['message']));
            // $message = "The service you try to use has to be purchased first.";
            $message  = $servicePermission ['message'];
            $disabled = 'disabled=disabled';
            // LogUtil::registerError(nl2br($message));
        }

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) { // disabled check
            if ($this->serviceDisabled('minisiteimages') < 1) {
                return LogUtil::registerError($this->__('This service has been temporarily disabled!'));
                $message        = 'This service has been temporarily disabled!';
                $disabled       = 'disabled=disabled';
                $servicedisable = true;
                // LogUtil::registerError(nl2br($message));
            }
        }

        $diskquota = ModUtil::apiFunc('ZSELEX', 'admin', 'checkDiskquota',
                $args      = array(
                'shop_id' => $shop_id
        ));

        if ($diskquota ['count'] < 1) {
            return LogUtil::registerError($diskquota ['message']);
        } elseif ($diskquota ['limitover'] < 1) {
            return LogUtil::registerError($diskquota ['message']);
        }

        // echo "<pre>"; print_r($diskquota); echo "</pre>"; exit;

        $this->view->assign('disabled', $disabled);
        $this->view->assign('servicedisable', $servicedisable);

        $func = 'createShopImage';
        if ($_POST) {
            $this->checkCsrfToken();
            $files = FormUtil::getPassedValue('simage',
                    isset($args ['simage']) ? $args ['simage'] : null, 'FILES');

            // echo "<pre>"; print_r($files); echo "</pre>"; exit;

            $filesize = $files ['size'];
            $allsize  = $diskquota ['sizeused'] + $filesize;
            if ($allsize >= $diskquota ['sizelimit']) {
                return LogUtil::registerError($this->__('File was not uploaded. Your disquota is exceeded for this shop. Please upgrade.'));
            }

            // make directories if not exist.
            if (!is_dir('zselexdata/'.$ownerName)) {
                mkdir('zselexdata/'.$ownerName, 0775);
                chmod('zselexdata/'.$ownerName, 0775);
            }

            if (!is_dir('zselexdata/'.$ownerName.'/minisiteimages')) {
                mkdir('zselexdata/'.$ownerName.'/minisiteimages', 0775);
                chmod('zselexdata/'.$ownerName.'/minisiteimages', 0775);
            }
            if (!is_dir('zselexdata/'.$ownerName.'/minisiteimages/fullsize')) {
                mkdir('zselexdata/'.$ownerName.'/minisiteimages/fullsize', 0775);
                chmod('zselexdata/'.$ownerName.'/minisiteimages/fullsize', 0775);
            }
            if (!is_dir('zselexdata/'.$ownerName.'/minisiteimages/medium')) {
                mkdir('zselexdata/'.$ownerName.'/minisiteimages/medium', 0775);
                chmod('zselexdata/'.$ownerName.'/minisiteimages/medium', 0775);
            }
            if (!is_dir('zselexdata/'.$ownerName.'/minisiteimages/thumb')) {
                mkdir('zselexdata/'.$ownerName.'/minisiteimages/thumb', 0775);
                chmod('zselexdata/'.$ownerName.'/minisiteimages/thumb', 0775);
            }

            $shop_id     = FormUtil::getPassedValue('shop_id', null, 'POST');
            $description = FormUtil::getPassedValue('description', null, 'POST');
            $keywords    = FormUtil::getPassedValue('keywords', null, 'POST');

            // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
            // echo "<pre>"; print_r($_FILES); echo "</pre>"; exit;

            if ($files ['error'] < 1) {
                // echo "<pre>"; print_r($files); echo "</pre>"; exit;
                $random_digit  = rand(0000, 9999);
                $new_file_name = time().'_'.$files ['name'];
                // echo $new_file_name; exit;
                $newNme        = array(
                    'newName' => $new_file_name
                );
                $file          = array();
                $file          = $files + $newNme;

                // $destination = 'zselexdata/shops';

                $destination = 'zselexdata/'.$ownerName.'/minisiteimages';

                // $checkImageSize = $this->validateImageSize($files);
                // echo $checkImageSize; exit;
                // if ($checkImageSize == true) {
                if ($this->uploadImage($file, $destination) == true) {
                    $where        = " shop_id='".$shop_id."' AND defaultImg='1'";
                    $getCountArgs = array(
                        'table' => 'zselex_files',
                        'where' => $where,
                        'Id' => 'file_id'
                    );

                    $dfltImgcount_count = ModUtil::apiFunc('ZSELEX', 'admin',
                            'countElements', $getCountArgs);
                    // echo $dfltImgcount_count; exit;

                    if ($dfltImgcount_count < 1) {
                        $addDefault = 1;
                    } else {
                        $addDefault = 0;
                    }

                    // $insertImgeId = "INSERT INTO zselex_files (name,shop_id,user_id,filedescription,keywords,defaultImg)
                    // VALUES('" . $new_file_name . "' , '" . $shop_id . "' , '" . $loguser . "' , '" . $description . "' , '" . $keywords . "' ,'" . $addDefault . "')";
                    // $statement = Doctrine_Manager::getInstance()->connection();
                    // $results = $statement->execute($insertImgeId);

                    $item     = array(
                        'name' => $new_file_name,
                        'shop_id' => $shop_id,
                        'user_id' => $loguser,
                        'image_description' => $description,
                        'keywords' => $keywords,
                        'defaultImg' => $addDefault
                    );
                    $args     = array(
                        'table' => 'zselex_files',
                        'element' => $item,
                        'Id' => 'file_id'
                    );
                    $result   = ModUtil::apiFunc('ZSELEX', 'admin',
                            'createElement', $args);
                    $InsertId = DBUtil::getInsertID($args ['table'],
                            $args ['Id']);
                    if ($result) {
                        if (!empty($keywords)) {
                            $keywords_for_search = str_replace(',', ' ',
                                $keywords);
                            $keywords_for_search = explode(' ',
                                $keywords_for_search);
                            foreach ($keywords_for_search as $keyword) {
                                $keywordExist = ModUtil::apiFunc('ZSELEX',
                                        'admin', 'getCount',
                                        $args         = array(
                                        'table' => 'zselex_keywords',
                                        'where' => "keyword='".$keyword."'"
                                ));

                                if ($keywordExist < 1) {
                                    $keyword_item   = array(
                                        'keyword' => $keyword,
                                        'type' => 'minisiteimages',
                                        'type_id' => $InsertId,
                                        'shop_id' => $shop_id
                                    );
                                    $keyword_args   = array(
                                        'table' => 'zselex_keywords',
                                        'element' => $keyword_item,
                                        'Id' => 'keyword_id'
                                    );
                                    $result_keyword = ModUtil::apiFunc('ZSELEX',
                                            'admin', 'createElement',
                                            $keyword_args);
                                }
                            }
                        }
                        $user             = UserUtil::getVar('uid');
                        $serviceupdatearg = array(
                            'user_id' => $user,
                            'type' => 'minisiteimages',
                            'shop_id' => $shop_id
                        );
                        $serviceavailed   = ModUtil::apiFunc('ZSELEX', 'admin',
                                'updateServiceUsed', $serviceupdatearg);
                        ModUtil::apiFunc('ZSELEX', 'admin', 'updateMinisite',
                            array(
                            'shop_id' => $shop_id
                        ));
                    }
                }
                // }
            } else {
                LogUtil::registerError($this->__f('Error! Please select a file.'));
                $this->redirect(ModUtil::url('ZSELEX', 'admin',
                        'createShopImage',
                        array(
                        'shop_id' => $shop_id
                )));
            }
            $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshopimages',
                    array(
                    'shop_id' => $shop_id
            )));
        }
        $this->view->assign('func', $func);
        $this->view->assign('shop_id', $shop_id);

        return $this->view->fetch('admin/minisiteimages/createshopimage.tpl');
    }

    public function validateImageSize($file)
    {
        $error = 0;

        list ( $width, $height, $type, $attr ) = getimagesize($file ['tmp_name']);

        $modvariable = $this->getVars();
        if ($width > $modvariable ['fullimagewidth']) {
            LogUtil::registerError($this->__f('Error! Image %s is too wide.',
                    $file [name]));
            ++$error;
        } else {
            --$error;
        }
        if ($height > $modvariable ['fullimageheight']) {
            LogUtil::registerError($this->__f('Error! Image %s is too high.',
                    $file [name]));
            ++$error;
        } else {
            --$error;
        }

        if ($error < 0 && $error != 0) {
            return true;
        } else {
            return false;
        }

        // return $error;
    }

    public function modifyshopimages($args)
    {
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $id      = FormUtil::getPassedValue('id', null, 'REQUEST');
        $func    = 'modifyshopimages';
        $loguser = UserUtil::getVar('uid');
        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));
        $this->view->assign('ownerName', $ownerName);

        // if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) { // disabled check
        $serviceEdit       = ModUtil::apiFunc('ZSELEX', 'admin',
                'serviceCheckEdit',
                $args              = array(
                'shop_id' => $shop_id,
                'item_idValue' => $id,
                'user_id' => $loguser,
                'servicetable' => 'zselex_files',
                'item_id' => 'file_id',
                'type' => 'minisiteimages'
        ));
        $servicePermission = $serviceEdit;
        $this->view->assign('servicePermission', $servicePermission);
        if ($serviceEdit < 1) {
            // return LogUtil::registerError($this->__f('Error! Unable to edit this %s.', $this->__('Minisite Image')));
        }

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            if ($this->serviceDisabled('minisiteimages') < 1) {
                return LogUtil::registerError($this->__('This service has been temporarily disabled!'));
            }
        }
        // }
        $diskquota = ModUtil::apiFunc('ZSELEX', 'admin', 'checkDiskquota',
                $args      = array(
                'shop_id' => $shop_id
        ));

        if ($diskquota ['error']) {
            // return LogUtil::registerError($diskquota['message']);
        }

        if ($_POST) {
            $files = FormUtil::getPassedValue('simage',
                    isset($args ['simage']) ? $args ['simage'] : null, 'FILES');

            $shop_id       = FormUtil::getPassedValue('shop_id', null, 'POST');
            $file_id       = FormUtil::getPassedValue('file_id', null, 'POST');
            $description   = FormUtil::getPassedValue('description', null,
                    'POST');
            $keywords      = FormUtil::getPassedValue('keywords', null, 'POST');
            $existingImage = FormUtil::getPassedValue('existingImage', null,
                    'POST');

            // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
            // echo "<pre>"; print_r($_FILES); echo "</pre>"; exit;
            $obj = array(
                'filedescription' => $description,
                'keywords' => $keywords
            );
            if ($files ['error'] < 1) {
                if (!empty($existingImage)) {
                    $existingFileSize = filesize('zselexdata/'.$ownerName.'/minisiteimages/'.$existingImage);
                } else {
                    $existingFileSize = 0;
                }

                // echo $existingFileSize; exit;

                $diskquota = ModUtil::apiFunc('ZSELEX', 'admin',
                        'checkDiskquota',
                        $args      = array(
                        'shop_id' => $shop_id
                ));
                // echo "<pre>"; print_r($diskquota); echo "</pre>"; exit;
                $allsize   = $diskquota ['sizeused'] - $existingFileSize;
                // echo $allsize; exit;
                $filesize  = $files ['size'];
                // echo $filesize; exit;
                $allsizes  = $allsize + $filesize;
                if ($allsizes >= $diskquota ['sizelimit']) {
                    return LogUtil::registerError($this->__('File was not uploaded. You need more disquoata to upload this file. Please upgrade.'));
                }

                // $checkImageSize = $this->validateImageSize($files);
                // if ($checkImageSize == true) {
                // echo "<pre>"; print_r($files); echo "</pre>"; exit;
                if (file_exists('zselexdata/'.$ownerName.'/minisiteimages/'.$existingImage)) {
                    unlink('zselexdata/'.$ownerName.'/minisiteimages/'.$existingImage);
                }
                if (file_exists('zselexdata/'.$ownerName.'/minisiteimages/fullsize/'.$existingImage)) {
                    unlink('zselexdata/'.$ownerName.'/minisiteimages/fullsize/'.$existingImage);
                }

                if (file_exists('zselexdata/'.$ownerName.'/minisiteimages/medium/'.$existingImage)) {
                    unlink('zselexdata/'.$ownerName.'/minisiteimages/medium/'.$existingImage);
                }

                if (file_exists('zselexdata/'.$ownerName.'/minisiteimages/thumb/'.$existingImage)) {
                    unlink('zselexdata/'.$ownerName.'/minisiteimages/thumb/'.$existingImage);
                }

                // make directories if not exist.
                if (!is_dir('zselexdata/'.$ownerName)) {
                    mkdir('zselexdata/'.$ownerName, 0775);
                    chmod('zselexdata/'.$ownerName, 0775);
                }

                if (!is_dir('zselexdata/'.$ownerName.'/minisiteimages')) {
                    mkdir('zselexdata/'.$ownerName.'/minisiteimages', 0775);
                    chmod('zselexdata/'.$ownerName.'/minisiteimages', 0775);
                }
                if (!is_dir('zselexdata/'.$ownerName.'/minisiteimages/fullsize')) {
                    mkdir('zselexdata/'.$ownerName.'/minisiteimages/fullsize',
                        0775);
                    chmod('zselexdata/'.$ownerName.'/minisiteimages/fullsize',
                        0775);
                }
                if (!is_dir('zselexdata/'.$ownerName.'/minisiteimages/medium')) {
                    mkdir('zselexdata/'.$ownerName.'/minisiteimages/medium',
                        0775);
                    chmod('zselexdata/'.$ownerName.'/minisiteimages/medium',
                        0775);
                }
                if (!is_dir('zselexdata/'.$ownerName.'/minisiteimages/thumb')) {
                    mkdir('zselexdata/'.$ownerName.'/minisiteimages/thumb', 0775);
                    chmod('zselexdata/'.$ownerName.'/minisiteimages/thumb', 0775);
                }

                $random_digit  = rand(0000, 9999);
                $new_file_name = time().'_'.$files ['name'];
                // echo $new_file_name; exit;
                $newNme        = array(
                    'newName' => $new_file_name
                );
                $file          = array();
                $file          = $files + $newNme;

                // $destination = 'zselexdata/shops';
                $destination  = 'zselexdata/'.$ownerName.'/minisiteimages';
                $this->uploadImage($file, $destination);
                $where        = " shop_id='".$shop_id."' AND defaultImg='1'";
                $getCountArgs = array(
                    'table' => 'zselex_files',
                    'where' => $where,
                    'Id' => 'file_id'
                );

                $dfltImgcount_count = ModUtil::apiFunc('ZSELEX', 'admin',
                        'countElements', $getCountArgs);
                // echo $dfltImgcount_count; exit;

                if ($dfltImgcount_count < 1) {
                    $addDefault = 1;
                } else {
                    $addDefault = 0;
                }
                $obj = array(
                    'filedescription' => $description,
                    'keywords' => $keywords,
                    'name' => $new_file_name
                );
                // }
            }

            $pntables     = pnDBGetTables();
            $column       = $pntables ['zselex_files_column'];
            $where        = "WHERE $column[file_id]=$file_id";
            $update_image = DBUTil::updateObject($obj, 'zselex_files', $where);
            ModUtil::apiFunc('ZSELEX', 'admin', 'updateMinisite',
                array(
                'shop_id' => $shop_id
            ));
            if ($update_image) {
                $where_keyword = "WHERE type='minisiteimages' AND type_id=$file_id";
                if (DBUtil::deleteWhere('zselex_keywords', $where_keyword)) {
                    if (!empty($keywords)) {
                        $keywords_for_search = str_replace(',', ' ', $keywords);
                        $keywords_for_search = explode(' ', $keywords_for_search);
                        foreach ($keywords_for_search as $keyword) {
                            $keywordExist = ModUtil::apiFunc('ZSELEX', 'admin',
                                    'getCount',
                                    $args         = array(
                                    'table' => 'zselex_keywords',
                                    'where' => "keyword='".$keyword."'"
                            ));

                            if ($keywordExist < 1) {
                                if (!empty($keyword)) {
                                    $keyword_item   = array(
                                        'keyword' => $keyword,
                                        'type' => 'minisiteimages',
                                        'type_id' => $file_id,
                                        'shop_id' => $shop_id
                                    );
                                    $keyword_args   = array(
                                        'table' => 'zselex_keywords',
                                        'element' => $keyword_item,
                                        'Id' => 'keyword_id'
                                    );
                                    $result_keyword = ModUtil::apiFunc('ZSELEX',
                                            'admin', 'createElement',
                                            $keyword_args);
                                }
                            }
                        }
                    }
                }
            }

            $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshopimages',
                    array(
                    'shop_id' => $shop_id
            )));
        }

        $item = DBUtil::selectObjectByID('zselex_files', $id, 'file_id');

        $this->view->assign('func', $func);
        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('id', $id);
        $this->view->assign('item', $item);

        return $this->view->fetch('admin/minisiteimages/createshopimage.tpl');
    }

    public function createShopGalleryImage($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        if (!(int) ($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)));
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $loguser           = UserUtil::getVar('uid');
        $user_id           = $loguser;
        $func              = 'createShopGalleryImage';
        $ownerName         = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args              = array(
                'shop_id' => $shop_id
        ));
        $serviceargs       = array(
            'shop_id' => FormUtil::getPassedValue('shop_id', null, 'REQUEST'),
            'user_id' => $user_id,
            'type' => 'minisitegallery'
        );
        // $servicePermission = $this->serviceCheck($serviceargs);
        $servicePermission = $this->servicePermission($serviceargs);

        if ($servicePermission ['perm'] < 1) {
            // return LogUtil::registerError($this->__('The service you try to use has to be purchased first.'));
            return LogUtil::registerError($this->__($servicePermission ['message']));
        }

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) { // disabled check
            if ($this->serviceDisabled('minisitegallery') < 1) {
                return LogUtil::registerError($this->__('This service has been temporarily disabled!'));
            }
        }

        $diskquota = ModUtil::apiFunc('ZSELEX', 'admin', 'checkDiskquota',
                $args      = array(
                'shop_id' => $shop_id
        ));

        if ($diskquota ['count'] < 1) {
            return LogUtil::registerError($diskquota ['message']);
        } elseif ($diskquota ['limitover'] < 1) {
            return LogUtil::registerError($diskquota ['message']);
        }

        if ($_POST) {
            $this->checkCsrfToken();
            $files = FormUtil::getPassedValue('simage',
                    isset($args ['simage']) ? $args ['simage'] : null, 'FILES');
            // make directories if not exist.
            if (!is_dir('zselexdata/'.$ownerName)) {
                mkdir('zselexdata/'.$ownerName, 0775);
                chmod('zselexdata/'.$ownerName, 0775);
            }

            if (!is_dir('zselexdata/'.$ownerName.'/minisitegallery')) {
                mkdir('zselexdata/'.$ownerName.'/minisitegallery', 0775);
                chmod('zselexdata/'.$ownerName.'/minisitegallery', 0775);
            }
            if (!is_dir('zselexdata/'.$ownerName.'/minisitegallery/fullsize')) {
                mkdir('zselexdata/'.$ownerName.'/minisitegallery/fullsize', 0775);
                chmod('zselexdata/'.$ownerName.'/minisitegallery/fullsize', 0775);
            }
            if (!is_dir('zselexdata/'.$ownerName.'/minisitegallery/medium')) {
                mkdir('zselexdata/'.$ownerName.'/minisitegallery/medium', 0775);
                chmod('zselexdata/'.$ownerName.'/minisitegallery/medium', 0775);
            }
            if (!is_dir('zselexdata/'.$ownerName.'/minisitegallery/thumb')) {
                mkdir('zselexdata/'.$ownerName.'/minisitegallery/thumb', 0775);
                chmod('zselexdata/'.$ownerName.'/minisitegallery/thumb', 0775);
            }

            $shop_id     = FormUtil::getPassedValue('shop_id', null, 'POST');
            $description = FormUtil::getPassedValue('description', null, 'POST');
            $keywords    = FormUtil::getPassedValue('keywords', null, 'POST');

            if (!empty($keywords)) {
                $keywords_for_search = str_replace(',', ' ', $keywords);
                $keywords_for_search = explode(' ', $keywords_for_search);
            }

            // echo "<pre>"; print_r($keywords_for_search); echo "</pre>"; exit;
            // echo "<pre>"; print_r($_POST); echo "</pre>";
            // echo "<pre>"; print_r($_FILES); echo "</pre>"; exit;

            if ($files ['error'] < 1) {
                // $checkImageSize = $this->validateImageSize($files);
                // if ($checkImageSize == true) {
                // echo "<pre>"; print_r($files); echo "</pre>"; exit;
                $random_digit  = rand(0000, 9999);
                $new_file_name = time().'_'.$files ['name'];
                // echo $new_file_name; exit;
                $newNme        = array(
                    'newName' => $new_file_name
                );
                $file          = array();
                $file          = $files + $newNme;

                // $destination = 'zselexdata/shopgallery';
                $destination = 'zselexdata/'.$ownerName.'/minisitegallery';
                if ($this->uploadImage($file, $destination) == true) {
                    $where        = " shop_id='".$shop_id."' AND defaultImg='1'";
                    $getCountArgs = array(
                        'table' => 'zselex_shop_gallery',
                        'where' => $where,
                        'Id' => 'gallery_id'
                    );

                    $dfltImgcount_count = ModUtil::apiFunc('ZSELEX', 'admin',
                            'countElements', $getCountArgs);
                    // echo $dfltImgcount_count; exit;

                    if ($dfltImgcount_count < 1) {
                        $addDefault = 1;
                    } else {
                        $addDefault = 0;
                    }

                    // $insertImgeId = "INSERT INTO zselex_shop_gallery (image_name,shop_id,user_id,image_description,keywords,defaultImg)
                    // VALUES('" . $new_file_name . "' , '" . $shop_id . "' , '" . $loguser . "' , '" . $description . "' , '" . $keywords . "' ,'" . $addDefault . "')";
                    // $statement = Doctrine_Manager::getInstance()->connection();
                    // $results = $statement->execute($insertImgeId);
                    $item     = array(
                        'image_name' => $new_file_name,
                        'shop_id' => $shop_id,
                        'user_id' => $loguser,
                        'image_description' => $description,
                        'keywords' => $keywords,
                        'defaultImg' => $addDefault
                    );
                    $args     = array(
                        'table' => 'zselex_shop_gallery',
                        'element' => $item,
                        'Id' => 'gallery_id'
                    );
                    $result   = ModUtil::apiFunc('ZSELEX', 'admin',
                            'createElement', $args);
                    $InsertId = DBUtil::getInsertID($args ['table'],
                            $args ['Id']);

                    if ($result) {
                        if (!empty($keywords)) {
                            foreach ($keywords_for_search as $keyword) {
                                $keywordExist = ModUtil::apiFunc('ZSELEX',
                                        'admin', 'getCount',
                                        $args         = array(
                                        'table' => 'zselex_keywords',
                                        'where' => "keyword='".$keyword."'"
                                ));

                                if ($keywordExist < 1) {
                                    $keyword_item   = array(
                                        'keyword' => $keyword,
                                        'type' => 'minisitegallery',
                                        'type_id' => $InsertId,
                                        'shop_id' => $shop_id
                                    );
                                    $keyword_args   = array(
                                        'table' => 'zselex_keywords',
                                        'element' => $keyword_item,
                                        'Id' => 'keyword_id'
                                    );
                                    $result_keyword = ModUtil::apiFunc('ZSELEX',
                                            'admin', 'createElement',
                                            $keyword_args);
                                }
                            }
                        }

                        $user_id          = UserUtil::getVar('uid');
                        $serviceupdatearg = array(
                            'user_id' => $user_id,
                            'type' => 'minisitegallery',
                            'shop_id' => $shop_id
                        );
                        $serviceavailed   = ModUtil::apiFunc('ZSELEX', 'admin',
                                'updateServiceUsed', $serviceupdatearg);
                        ModUtil::apiFunc('ZSELEX', 'admin', 'updateMinisite',
                            array(
                            'shop_id' => $shop_id
                        ));
                    }
                }
                // }
            } else {
                LogUtil::registerError($this->__f('Error! Please select a valid file.'));
                $this->redirect(ModUtil::url('ZSELEX', 'admin',
                        'createShopGalleryImage',
                        array(
                        'shop_id' => $shop_id
                )));
            }
            $this->redirect(ModUtil::url('ZSELEX', 'admin',
                    'viewshopgalleryimages',
                    array(
                    'shop_id' => $shop_id
            )));
        }
        $this->view->assign('func', $func);
        $this->view->assign('shop_id', $shop_id);

        return $this->view->fetch('admin/minisitegallery/createshopgalleryimage.tpl');
    }

    public function modifyshopgalleryimages($args)
    {
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $id      = FormUtil::getPassedValue('id', null, 'REQUEST');
        $func    = 'modifyshopgalleryimages';
        $loguser = UserUtil::getVar('uid');
        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));
        $this->view->assign('ownerName', $ownerName);

        // if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) { // disabled check
        $serviceEdit = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceCheckEdit',
                $args        = array(
                'shop_id' => $shop_id,
                'item_idValue' => $id,
                'user_id' => $loguser,
                'servicetable' => 'zselex_shop_gallery',
                'item_id' => 'gallery_id',
                'type' => 'minisitegallery'
        ));

        if ($serviceEdit < 1) {
            // return LogUtil::registerError($this->__f('Error! Unable to edit this %s.', $this->__('Minisite Gallery Image')));
        }

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            if ($this->serviceDisabled('minisitegallery') < 1) {
                return LogUtil::registerError($this->__('This service has been temporarily disabled!'));
            }
        }
        // }

        $diskquota = ModUtil::apiFunc('ZSELEX', 'admin', 'checkDiskquota',
                $args      = array(
                'shop_id' => $shop_id
        ));

        if ($diskquota ['error']) {
            // return LogUtil::registerError($diskquota['message']);
        }

        if ($_POST) {
            $files = FormUtil::getPassedValue('simage',
                    isset($args ['simage']) ? $args ['simage'] : null, 'FILES');

            $shop_id       = FormUtil::getPassedValue('shop_id', null, 'POST');
            $file_id       = FormUtil::getPassedValue('gallery_id', null, 'POST');
            $description   = FormUtil::getPassedValue('description', null,
                    'POST');
            $keywords      = FormUtil::getPassedValue('keywords', null, 'POST');
            $existingImage = FormUtil::getPassedValue('existingImage', null,
                    'POST');

            // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
            // echo "<pre>"; print_r($_FILES); echo "</pre>"; exit;
            $obj = array(
                'image_description' => $description,
                'keywords' => $keywords
            );
            if ($files ['error'] < 1) {
                if (!empty($existingImage)) {
                    $existingFileSize = filesize('zselexdata/'.$ownerName.'/minisitegallery/'.$existingImage);
                } else {
                    $existingFileSize = 0;
                }
                echo 'File Size :'.$existingFileSize;
                exit();

                $diskquota = ModUtil::apiFunc('ZSELEX', 'admin',
                        'checkDiskquota',
                        $args      = array(
                        'shop_id' => $shop_id
                ));
                // echo "<pre>"; print_r($diskquota); echo "</pre>"; exit;
                $allsize   = $diskquota ['sizeused'] - $existingFileSize;
                // echo $allsize; exit;
                $filesize  = $files ['size'];
                // echo $filesize; exit;
                $allsizes  = $allsize + $filesize;
                if ($allsizes >= $diskquota ['sizelimit']) {
                    return LogUtil::registerError($this->__('File was not uploaded. You need more disquoata to upload this file. Please upgrade.'));
                }

                // $checkImageSize = $this->validateImageSize($files);
                // if ($checkImageSize == true) {
                // echo "<pre>"; print_r($files); echo "</pre>"; exit;
                // make directories if not exist.
                if (!is_dir('zselexdata/'.$ownerName)) {
                    mkdir('zselexdata/'.$ownerName, 0775);
                    chmod('zselexdata/'.$ownerName, 0775);
                }

                if (!is_dir('zselexdata/'.$ownerName.'/minisitegallery')) {
                    mkdir('zselexdata/'.$ownerName.'/minisitegallery', 0775);
                    chmod('zselexdata/'.$ownerName.'/minisitegallery', 0775);
                }
                if (!is_dir('zselexdata/'.$ownerName.'/minisitegallery/fullsize')) {
                    mkdir('zselexdata/'.$ownerName.'/minisitegallery/fullsize',
                        0775);
                    chmod('zselexdata/'.$ownerName.'/minisitegallery/fullsize',
                        0775);
                }
                if (!is_dir('zselexdata/'.$ownerName.'/minisitegallery/medium')) {
                    mkdir('zselexdata/'.$ownerName.'/minisitegallery/medium',
                        0775);
                    chmod('zselexdata/'.$ownerName.'/minisitegallery/medium',
                        0775);
                }
                if (!is_dir('zselexdata/'.$ownerName.'/minisitegallery/thumb')) {
                    mkdir('zselexdata/'.$ownerName.'/minisitegallery/thumb',
                        0775);
                    chmod('zselexdata/'.$ownerName.'/minisitegallery/thumb',
                        0775);
                }

                if (file_exists('zselexdata/'.$ownerName.'/minisitegallery/'.$existingImage)) {
                    unlink('zselexdata/'.$ownerName.'/minisitegallery/'.$existingImage);
                }
                if (file_exists('zselexdata/'.$ownerName.'/minisitegallery/fullsize/'.$existingImage)) {
                    unlink('zselexdata/'.$ownerName.'/minisitegallery/fullsize/'.$existingImage);
                }
                if (file_exists('zselexdata/'.$ownerName.'/minisitegallery/medium/'.$existingImage)) {
                    unlink('zselexdata/'.$ownerName.'/minisitegallery/medium/'.$existingImage);
                }
                if (file_exists('zselexdata/'.$ownerName.'/minisitegallery/thumb/'.$existingImage)) {
                    unlink('zselexdata/'.$ownerName.'/minisitegallery/thumb/'.$existingImage);
                }

                // unlink('zselexdata/shopgallery/' . $existingImage);
                // unlink('zselexdata/shopgallery/medium' . $existingImage);
                // unlink('zselexdata/shopgallery/thumbs' . $existingImage);
                $random_digit  = rand(0000, 9999);
                $new_file_name = time().'_'.$files ['name'];
                // echo $new_file_name; exit;
                $newNme        = array(
                    'newName' => $new_file_name
                );
                $file          = array();
                $file          = $files + $newNme;

                // $destination = 'zselexdata/shopgallery';
                $destination  = 'zselexdata/'.$ownerName.'/minisitegallery';
                $this->uploadImage($file, $destination);
                $where        = " shop_id='".$shop_id."' AND defaultImg='1'";
                $getCountArgs = array(
                    'table' => 'zselex_shop_gallery',
                    'where' => $where,
                    'Id' => 'gallery_id'
                );

                $dfltImgcount_count = ModUtil::apiFunc('ZSELEX', 'admin',
                        'countElements', $getCountArgs);
                // echo $dfltImgcount_count; exit;

                if ($dfltImgcount_count < 1) {
                    $addDefault = 1;
                } else {
                    $addDefault = 0;
                }
                $obj = array(
                    'image_description' => $description,
                    'keywords' => $keywords,
                    'image_name' => $new_file_name
                );
                // }
            }
            $pntables      = pnDBGetTables();
            $column        = $pntables ['zselex_shop_gallery_column'];
            $where         = "WHERE $column[gallery_id]=$file_id";
            $updategallery = DBUTil::updateObject($obj, 'zselex_shop_gallery',
                    $where);

            $where = "WHERE type='minisitegallery' AND type_id=$file_id";
            ModUtil::apiFunc('ZSELEX', 'admin', 'updateMinisite',
                array(
                'shop_id' => $shop_id
            ));
            if (DBUtil::deleteWhere('zselex_keywords', $where)) {
                if (!empty($keywords)) {
                    $keywords_for_search = str_replace(',', ' ', $keywords);
                    $keywords_for_search = explode(' ', $keywords_for_search);
                    foreach ($keywords_for_search as $keyword) {
                        $keywordExist = ModUtil::apiFunc('ZSELEX', 'admin',
                                'getCount',
                                $args         = array(
                                'table' => 'zselex_keywords',
                                'where' => "keyword='".$keyword."'"
                        ));

                        if ($keywordExist < 1) {
                            if (!empty($keyword)) {
                                $keyword_item   = array(
                                    'keyword' => $keyword,
                                    'type' => 'minisitegallery',
                                    'type_id' => $file_id,
                                    'shop_id' => $shop_id
                                );
                                $keyword_args   = array(
                                    'table' => 'zselex_keywords',
                                    'element' => $keyword_item,
                                    'Id' => 'keyword_id'
                                );
                                $result_keyword = ModUtil::apiFunc('ZSELEX',
                                        'admin', 'createElement', $keyword_args);
                            }
                        }
                    }
                }
            }

            $this->redirect(ModUtil::url('ZSELEX', 'admin',
                    'viewshopgalleryimages',
                    array(
                    'shop_id' => $shop_id
            )));
        }

        $item = DBUtil::selectObjectByID('zselex_shop_gallery', $id,
                'gallery_id');

        $this->view->assign('func', $func);
        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('id', $id);
        $this->view->assign('item', $item);

        return $this->view->fetch('admin/minisitegallery/createshopgalleryimage.tpl');
    }

    public function deleteExtraImageServices($args)
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
                    "type='minisiteimages'"
                )
        ));
        if ($servicecheck ['availed'] > $servicecheck ['quantity']) {
            $service_used_extra  = $servicecheck ['availed'] - $servicecheck ['quantity'];
            $original_used_extra = $servicecheck ['availed'] - $service_used_extra;
            // echo $original_used_extra;
            $service_extra       = ModUtil::apiFunc('ZSELEX', 'user',
                    'selectArray',
                    $args                = array(
                    'table' => 'zselex_files',
                    'where' => array(
                        "shop_id=$shop_id"
                    ),
                    'orderby' => 'file_id DESC',
                    'limit' => "LIMIT 0 , $service_used_extra"
            ));

            // echo "<pre>"; print_r($service_extra); echo "</pre>";

            foreach ($service_extra as $extra_item) {
                unlink('zselexdata/'.$ownername.'/minisiteimages/fullsize/'.$extra_item [name]);
                unlink('zselexdata/'.$ownername.'/minisiteimages/medium/'.$extra_item [name]);
                unlink('zselexdata/'.$ownername.'/minisiteimages/thumb/'.$extra_item [name]);
                $where = "file_id=$extra_item[file_id]";
                DBUtil::deleteWhere('zselex_files', $where);

                // echo $extra_item['pdf_name'] . '<br>';
            }
            $upd_ser_args   = array(
                'table' => 'zselex_serviceshop',
                'items' => array(
                    'availed' => $original_used_extra
                ),
                'where' => array(
                    'shop_id' => $shop_id,
                    'type' => 'minisiteimages'
                )
            );
            $update_service = ModUtil::apiFunc('ZSELEX', 'admin',
                    'updateElementWhere', $upd_ser_args);
        }

        return true;
    }

    public function viewshopimages($args)
    {
        // PageUtil::addVar('jsgettext', 'module_zselex:ZSELEX');
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $user_id = UserUtil::getVar('uid');
        if (!(int) ($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)));
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $bulkaction = (int) FormUtil::getPassedValue('news_bulkaction_select',
                0, 'POST');
        $sids       = FormUtil::getPassedValue('news_selected_articles',
                array(), 'POST');
        if ($bulkaction >= 1 && $bulkaction <= 5) {
            // echo "exit;"; e xit;
            // echo "<pre>";print_r($sids); echo "</pre>"; exit;
            $actionmap    = array(
                // these indices are not constants, just unrelated integers
                1 => __('Delete'),
                2 => __('Archive'),
                3 => __('Publish'),
                4 => __('Reject'),
                5 => __('Change categories')
            );
            $updateresult = array(
                'successful' => array(),
                'failed' => array()
            );

            switch ($bulkaction) {

                case 1 : // delete
                    // echo "comes hereee"; exit;
                    foreach ($sids as $sid) {
                        $get     = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                                $getargs = array(
                                'table' => 'zselex_files',
                                'where' => "file_id=$sid"
                        ));
                        if (file_exists('zselexdata/'.$this->ownername.'/minisiteimages/'.$get ['name'])) {
                            @unlink('zselexdata/'.$this->ownername.'/minisiteimages/'.$get ['name']);
                        }
                        if (file_exists('zselexdata/'.$this->ownername.'/minisiteimages/fullsize/'.$get ['name'])) {
                            @unlink('zselexdata/'.$this->ownername.'/minisiteimages/fullsize/'.$get ['name']);
                        }
                        if (file_exists('zselexdata/'.$this->ownername.'/minisiteimages/medium/'.$get ['name'])) {
                            @unlink('zselexdata/'.$this->ownername.'/minisiteimages/medium/'.$get ['name']);
                        }
                        if (file_exists('zselexdata/'.$this->ownername.'/minisiteimages/thumb/'.$get ['name'])) {
                            @unlink('zselexdata/'.$this->ownername.'/minisiteimages/thumb/'.$get ['name']);
                        }

                        // if (DBUtil::deleteObjectByID('zselex_files', $sid, 'file_id')) {
                        $del_args = array(
                            'table' => 'zselex_files',
                            'IdValue' => $sid,
                            'IdName' => 'file_id'
                        );
                        if (ModUtil::apiFunc('ZSELEX', 'admin', 'deleteElement',
                                $del_args)) {
                            // assume max pictures. if less, errors are supressed by @
                            $updateresult ['successful'] [] = $sid;
                            $args                           = array(
                                'shop_id' => $shop_id,
                                'servicetype' => 'minisiteimages'
                            );
                            $deleteservice                  = ModUtil::apiFunc('ZSELEX',
                                    'admin', 'deleteService', $args);
                        } else {
                            $updateresult ['failed'] [] = $sid;
                        }
                    }
                    if (sizeof($updateresult ['successful']) > 0) {
                        LogUtil::registerStatus($this->__('Done! Slected Items Deleted Successsfully.'));
                    }
                    break;
            }
        }

        $admin     = SecurityUtil::checkPermission('ZSELEX::', '::',
                ACCESS_ADMIN);
        $this->view->assign('admin', $admin);
        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));
        $this->view->assign('ownerName', $ownerName);

        $args_del_extra_service = array(
            'ownername' => $ownerName,
            'shop_id' => $shop_id
        );
        $check                  = $this->deleteExtraImageServices($args_del_extra_service);

        // $var = shell_exec("E:/xampp/php/php.exe" . " " . $this->deleteExtraImageServices($args_del_extra_service));
        // $uploadpath = $_SERVER['DOCUMENT_ROOT'] . "/zselexdata/" . $ownerName . "/";
        if ($_SERVER ['SERVER_NAME'] == 'localhost') {
            $uploadpath = 'zselexdata/'.$ownerName.'/';
        }
        $uploadpath = 'zselexdata/'.$ownerName.'/';
        $this->view->assign('uploadpath', $uploadpath);

        // echo "<pre>"; print_r($diskquota); echo "</pre>";

        $disabled       = 'no';
        $disable        = '';
        $error          = 0;
        $nodisquota     = 0;
        $template       = 'viewshopimages.tpl';
        $servicecount   = 0;
        $message        = '';
        $servicedisable = false;

        $serviceargs       = array(
            'shop_id' => FormUtil::getPassedValue('shop_id', null, 'REQUEST'),
            'user_id' => $user_id,
            'type' => 'minisiteimages'
        );
        $servicePermission = $this->servicePermission($serviceargs);
        $serviceDisabled   = $this->serviceDisabled('minisiteimages');
        $servicecount += $servicePermission ['perm'];

        $this->view->assign('servicePermission', $servicePermission);

        if ($servicePermission ['perm'] < 1) {
            // $template = 'viewshopimages_noservice.tpl';
            $message = $servicePermission ['message'];
            ++$error;
            LogUtil::registerError(nl2br($message));
        }
        if ($this->serviceDisabled('minisiteimages') < 1) {
            if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $servicedisable = true;
                ++$error;
                $disable        = 'disabled';
            }
            $message = $this->__('This service is currently disabled');
            LogUtil::registerError(nl2br($message));
        }
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            $expired = $servicePermission ['expired'];
        }
        $this->view->assign('servicecount', $servicecount);
        $this->view->assign('servicedisable', $servicedisable);
        $this->view->assign('expired', $expired);
        $this->view->assign('serviceerror', $error);
        $this->view->assign('nodisquota', $nodisquota);
        $this->view->assign('disable', $disable);
        $this->view->assign('message', $message);

        if ($_POST ['action'] == 'savedefaults') {
            // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
            if (isset($_POST ['defaultimage'])) {
                $item       = array(
                    'defaultImg' => 1
                );
                $updateargs = array(
                    'table' => 'zselex_files',
                    'IdValue' => $_POST ['defaultimage'],
                    'IdName' => 'file_id',
                    'where' => "file_id='".$_POST ['defaultimage']."' AND  shop_id = '".$shop_id."'",
                    'element' => $item
                );

                $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement',
                        $updateargs);

                $item       = array(
                    'defaultImg' => 0
                );
                $updateargs = array(
                    'table' => 'zselex_files',
                    'IdValue' => $_POST ['defaultimage'],
                    'IdName' => 'file_id',
                    'where' => "file_id!='".$_POST ['defaultimage']."' AND  shop_id = '".$shop_id."'",
                    'element' => $item
                );

                $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement',
                        $updateargs);
            }
            if (!empty($_POST ['display'])) {
                // echo "<pre>"; print_r($_POST['display']); echo "</pre>";
                foreach ($_POST ['display'] as $val) {
                    $item       = array(
                        'display' => 0
                    );
                    $updateargs = array(
                        'table' => 'zselex_files',
                        'IdValue' => $val,
                        'IdName' => 'file_id',
                        'where' => "file_id!='".$val."' AND  shop_id='".$shop_id."'",
                        'element' => $item
                    );

                    $result = ModUtil::apiFunc('ZSELEX', 'admin',
                            'updateElement', $updateargs);
                }
                foreach ($_POST ['display'] as $val) {
                    // echo $val . '<br>';
                    $item       = array(
                        'display' => 1
                    );
                    $updateargs = array(
                        'table' => 'zselex_files',
                        'IdValue' => $val,
                        'IdName' => 'file_id',
                        'where' => "file_id='".$val."' AND  shop_id = '".$shop_id."'",
                        'element' => $item
                    );

                    $result = ModUtil::apiFunc('ZSELEX', 'admin',
                            'updateElement', $updateargs);
                }
            }
        }

        $item    = '';
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        $itemsperpage = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 20,
                'GETPOST');
        $startnum     = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : 0, 'GETPOST');

        $result = ModUtil::apiFunc('ZSELEX', 'admin', 'getShopImage',
                $args   = array(
                'start' => $startnum,
                'itemsperpage' => $itemsperpage,
                'shop_id' => $shop_id
        ));

        $items = $result;

        $where        = " shop_id='".$shop_id."'";
        $getCountArgs = array(
            'table' => 'zselex_files',
            'where' => $where,
            'Id' => 'file_id'
        );

        $total_count = ModUtil::apiFunc('ZSELEX', 'admin', 'countElements',
                $getCountArgs);

        $servicecheck = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow',
                $args         = array(
                'table' => 'zselex_serviceshop',
                'where' => array(
                    "shop_id=$shop_id",
                    "type='minisiteimages'"
                )
        ));

        // echo "<pre>"; print_r($servicecount); echo "</pre>";

        $servicelimit = $servicecheck ['quantity'] - $servicecheck ['availed'];

        $this->view->assign('quantity', $servicecheck ['quantity']);
        $this->view->assign('servicelimit', $servicelimit);
        // echo "<pre>"; print_r($item); echo "</pre>";
        $this->view->assign('startnum', $startnum);
        $this->view->assign('itemsperpage', $itemsperpage);
        $this->view->assign('total_count', $total_count);
        $this->view->assign('items', $items);

        return $this->view->fetch('admin/minisiteimages/'.$template);
    }

    public function deleteMinisiteImage($args)
    {
        $id      = FormUtil::getPassedValue('id',
                isset($args ['id']) ? $args ['id'] : 0, 'REQUEST');
        $shop_id = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : 0, 'REQUEST');
        $user_id = UserUtil::getVar('uid');

        $serviceEdit = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceCheckEdit',
                $args        = array(
                'shop_id' => $shop_id,
                'item_idValue' => $id,
                'user_id' => $user_id,
                'servicetable' => 'zselex_files',
                'item_id' => 'file_id',
                'type' => 'minisiteimages'
        ));

        if ($serviceEdit < 1) {
            // return LogUtil::registerError($this->__f('Error! Unable to delete this %s.', $this->__('Minisite Image')));
        }

        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
        if (empty($confirmation)) {
            // Add ZSELEX type ID
            $this->view->assign('IdValue', $id);
            $this->view->assign('confirm_title',
                $this->__f('Delete %s', $this->__('Minisite Image')));
            $this->view->assign('confirm_msg',
                $this->__f('Do you want to delete this %s',
                    $this->__('Minisite Image')));
            $this->view->assign('shop_id', $shop_id);
            $this->view->assign('IdName', 'id'); // edit id param name
            $this->view->assign('submitFunc', 'deleteMinisiteImage');
            $this->view->assign('cancelFunc', 'viewshopimages');
            // Return the output that has been generated by this function
            return $this->view->fetch('admin/deletecommon.tpl');
        }

        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));

        $argsitem = array(
            'table' => 'zselex_files',
            'IdValue' => $id,
            'IdName' => 'file_id'
        );
        // Get the news type in the db
        $item     = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $argsitem);

        // echo "<pre>"; print_r($item); echo "</pre>"; exit;

        if (file_exists('zselexdata/'.$ownerName.'/minisiteimages/'.$item ['name'])) {
            unlink('zselexdata/'.$ownerName.'/minisiteimages/'.$item ['name']);
        }
        if (file_exists('zselexdata/'.$ownerName.'/minisiteimages/fullsize/'.$item ['name'])) {
            unlink('zselexdata/'.$ownerName.'/minisiteimages/fullsize/'.$item ['name']);
        }
        if (file_exists('zselexdata/'.$ownerName.'/minisiteimages/medium/'.$item ['name'])) {
            unlink('zselexdata/'.$ownerName.'/minisiteimages/medium/'.$item ['name']);
        }
        if (file_exists('zselexdata/'.$ownerName.'/minisiteimages/thumb/'.$item ['name'])) {
            unlink('zselexdata/'.$ownerName.'/minisiteimages/thumb/'.$item ['name']);
        }

        $where = "file_id=$id AND shop_id=$shop_id";
        if (DBUtil::deleteWhere('zselex_files', $where)) {
            $servicetype   = 'minisiteimages';
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
                        'viewshopimages',
                        array(
                        'shop_id' => $shop_id
            )));
        } else {
            LogUtil::registerError($this->__('Error! Delete was NOT performed.'));

            return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                        'viewshopimages',
                        array(
                        'shop_id' => $shop_id
            )));
        }
    }

    public function serviceDisabled($type)
    {
        return ModUtil::apiFunc('ZSELEX', 'admin', 'serviceDisabled', $type);
    }

    public function deleteExtraGalleryServices($args)
    {
        $arra         = array();
        $shop_id      = $args ['shop_id'];
        $ownername    = $args ['ownername'];
        $servicecheck = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow',
                $args         = array(
                'table' => 'zselex_serviceshop',
                'where' => array(
                    "shop_id=$shop_id",
                    "type='minisitegallery'"
                )
        ));
        if ($servicecheck ['availed'] > $servicecheck ['quantity']) {
            $service_used_extra  = $servicecheck ['availed'] - $servicecheck ['quantity'];
            $original_used_extra = $servicecheck ['availed'] - $service_used_extra;
            // echo $original_used_extra;
            $service_extra       = ModUtil::apiFunc('ZSELEX', 'user',
                    'selectArray',
                    $args                = array(
                    'table' => 'zselex_shop_gallery',
                    'where' => array(
                        "shop_id=$shop_id"
                    ),
                    'orderby' => 'gallery_id DESC',
                    'limit' => "LIMIT 0 , $service_used_extra"
            ));

            // echo "<pre>"; print_r($service_extra); echo "</pre>";

            foreach ($service_extra as $extra_item) {
                unlink('zselexdata/'.$ownername.'/minisitegallery/fullsize/'.$extra_item [image_name]);
                unlink('zselexdata/'.$ownername.'/minisitegallery/medium/'.$extra_item [image_name]);
                unlink('zselexdata/'.$ownername.'/minisitegallery/thumb/'.$extra_item [image_name]);
                $where = "gallery_id=$extra_item[gallery_id]";
                DBUtil::deleteWhere('zselex_shop_gallery', $where);

                // echo $extra_item['pdf_name'] . '<br>';
            }
            $upd_ser_args   = array(
                'table' => 'zselex_serviceshop',
                'items' => array(
                    'availed' => $original_used_extra
                ),
                'where' => array(
                    'shop_id' => $shop_id,
                    'type' => 'minisitegallery'
                )
            );
            $update_service = ModUtil::apiFunc('ZSELEX', 'admin',
                    'updateElementWhere', $upd_ser_args);
        }

        return true;
    }

    public function viewshopgalleryimages($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $user_id = UserUtil::getVar('uid');
        if (!(int) ($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)));
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $bulkaction = (int) FormUtil::getPassedValue('news_bulkaction_select',
                0, 'POST');
        $sids       = FormUtil::getPassedValue('news_selected_articles',
                array(), 'POST');
        if ($bulkaction >= 1 && $bulkaction <= 5) {
            // echo "exit;"; e xit;
            // echo "<pre>";print_r($sids); echo "</pre>"; exit;
            $actionmap    = array(
                // these indices are not constants, just unrelated integers
                1 => __('Delete'),
                2 => __('Archive'),
                3 => __('Publish'),
                4 => __('Reject'),
                5 => __('Change categories')
            );
            $updateresult = array(
                'successful' => array(),
                'failed' => array()
            );

            switch ($bulkaction) {

                case 1 : // delete
                    // echo "comes hereee"; exit;
                    foreach ($sids as $sid) {
                        $get     = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                                $getargs = array(
                                'table' => 'zselex_shop_gallery',
                                'where' => "gallery_id='".$sid."'"
                        ));
                        if (file_exists('zselexdata/'.$this->ownername.'/minisitegallery/'.$get ['image_name'])) {
                            @unlink('zselexdata/'.$this->ownername.'/minisitegallery/'.$get ['image_name']);
                        }
                        if (file_exists('zselexdata/'.$this->ownername.'/minisitegallery/fullsize/'.$get ['image_name'])) {
                            @unlink('zselexdata/'.$this->ownername.'/minisitegallery/fullsize/'.$get ['image_name']);
                        }
                        if (file_exists('zselexdata/'.$this->ownername.'/minisitegallery/medium/'.$get ['image_name'])) {
                            @unlink('zselexdata/'.$this->ownername.'/minisitegallery/medium/'.$get ['image_name']);
                        }
                        if (file_exists('zselexdata/'.$this->ownername.'/minisitegallery/thumb/'.$get ['image_name'])) {
                            @unlink('zselexdata/'.$this->ownername.'/minisitegallery/thumb/'.$get ['image_name']);
                        }

                        if (DBUtil::deleteObjectByID('zselex_shop_gallery',
                                $sid, 'gallery_id')) {
                            // assume max pictures. if less, errors are supressed by @
                            $updateresult ['successful'] [] = $sid;
                            $args                           = array(
                                'shop_id' => $shop_id,
                                'servicetype' => 'minisitegallery',
                                'user_id' => $user_id
                            );
                            $deleteservice                  = ModUtil::apiFunc('ZSELEX',
                                    'admin', 'deleteService', $args);
                        } else {
                            $updateresult ['failed'] [] = $sid;
                        }
                    }
                    if (sizeof($updateresult ['successful']) > 0) {
                        LogUtil::registerStatus($this->__('Done! Slected Items Deleted Successsfully.'));
                    }
                    break;
            }
        }

        $admin     = SecurityUtil::checkPermission('ZSELEX::', '::',
                ACCESS_ADMIN);
        $this->view->assign('admin', $admin);
        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));
        $this->view->assign('ownerName', $ownerName);

        $args_del_extra_service = array(
            'ownername' => $ownerName,
            'shop_id' => $shop_id
        );
        $check                  = $this->deleteExtraGalleryServices($args_del_extra_service);

        // $uploadpath = $_SERVER['DOCUMENT_ROOT'] . "/zselexdata/" . $ownerName . "/";
        $uploadpath = 'zselexdata/'.$ownerName.'/';
        if ($_SERVER ['SERVER_NAME'] == 'localhost') {
            $uploadpath = 'zselexdata/'.$ownerName.'/';
        }

        $this->view->assign('uploadpath', $uploadpath);

        $disabled       = 'no';
        $disable        = '';
        $error          = 0;
        $nodisquota     = 0;
        $message        = '';
        $servicedisable = false;
        $template       = 'viewshopgalleryimages.tpl';

        $serviceargs       = array(
            'shop_id' => FormUtil::getPassedValue('shop_id', null, 'REQUEST'),
            'user_id' => $user_id,
            'type' => 'minisitegallery'
        );
        $servicePermission = $this->servicePermission($serviceargs);
        // echo "<pre>"; print_r($servicePermission); echo "</pre>";
        $servicecount += $servicePermission ['perm'];
        $serviceDisabled   = $this->serviceDisabled('minisitegallery');

        if ($servicePermission ['perm'] < 1) {
            // $template = 'viewshopgalleryimages_noservice.tpl';
            $message = $servicePermission ['message'];
            ++$error;
            LogUtil::registerError(nl2br($message));
        }

        // $template = 'viewshopgalleryimagesdisabled.tpl';

        if ($this->serviceDisabled('minisitegallery') < 1) {
            if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $servicedisable = true;
                $disable        = 'disabled';
                ++$error;
            }
            $message = $this->__('This service is currently disabled');
            LogUtil::registerError(nl2br($message));
        }
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            $expired = $servicePermission ['expired'];
        }

        $this->view->assign('servicecount', $servicecount);
        $this->view->assign('nodisquota', $nodisquota);
        $this->view->assign('servicedisable', $servicedisable);
        $this->view->assign('expired', $expired);
        $this->view->assign('disable', $disable);
        $this->view->assign('serviceerror', $error);
        $this->view->assign('message', $message);

        if ($_POST ['action'] == 'savedefaults') {
            if (isset($_POST ['defaultimage'])) {
                $item       = array(
                    'defaultImg' => 1
                );
                $updateargs = array(
                    'table' => 'zselex_shop_gallery',
                    'IdValue' => $_POST ['defaultimage'],
                    'IdName' => 'gallery_id',
                    'where' => "gallery_id='".$_POST ['defaultimage']."' AND  shop_id = '".$shop_id."'",
                    'element' => $item
                );

                $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement',
                        $updateargs);

                $item       = array(
                    'defaultImg' => 0
                );
                $updateargs = array(
                    'table' => 'zselex_shop_gallery',
                    'IdValue' => $_POST ['defaultimage'],
                    'IdName' => 'gallery_id',
                    'where' => "gallery_id!='".$_POST ['defaultimage']."' AND  shop_id = '".$shop_id."'",
                    'element' => $item
                );

                $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement',
                        $updateargs);
            }
        }

        $item    = '';
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        // $pntable = pnDBGetTables();
        // $customercolumn = $pntable['zselex_files_column'];
        // $where = "WHERE $customercolumn[shop_id] = '" . pnVarPrepForStore($shop_id) . "' AND user_id='4'";
        // $orderBy = "";
        // $objArray = DBUtil::selectObjectArray('zselex_files', $where, $orderBy);

        $itemsperpage = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 20,
                'GETPOST');
        $startnum     = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : 0, 'GETPOST');

        $result = ModUtil::apiFunc('ZSELEX', 'admin', 'getShopGalleryImage',
                $args   = array(
                'start' => $startnum,
                'itemsperpage' => $itemsperpage,
                'shop_id' => $shop_id
        ));

        $items = $result;

        $where        = " shop_id='".$shop_id."'";
        $getCountArgs = array(
            'table' => 'zselex_shop_gallery',
            'where' => $where,
            'Id' => 'gallery_id'
        );

        $total_count = ModUtil::apiFunc('ZSELEX', 'admin', 'countElements',
                $getCountArgs);

        $servicecheck = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow',
                $args         = array(
                'table' => 'zselex_serviceshop',
                'where' => array(
                    "shop_id=$shop_id",
                    "type='minisitegallery'"
                )
        ));

        // echo "<pre>"; print_r($servicecount); echo "</pre>";

        $servicelimit = $servicecheck ['quantity'] - $servicecheck ['availed'];

        // echo "<pre>"; print_r($item); echo "</pre>";
        $this->view->assign('quantity', $servicecheck ['quantity']);
        $this->view->assign('servicelimit', $servicelimit);
        $this->view->assign('startnum', $startnum);
        $this->view->assign('itemsperpage', $itemsperpage);
        $this->view->assign('total_count', $total_count);
        $this->view->assign('items', $items);

        return $this->view->fetch('admin/minisitegallery/'.$template);
    }

    public function deleteMinisiteGalleryImage($args)
    {
        $id        = FormUtil::getPassedValue('id',
                isset($args ['id']) ? $args ['id'] : 0, 'REQUEST');
        $shop_id   = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : 0, 'REQUEST');
        $user_id   = UserUtil::getVar('uid');
        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));

        $serviceEdit = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceCheckEdit',
                $args        = array(
                'shop_id' => $shop_id,
                'item_idValue' => $id,
                'user_id' => $user_id,
                'servicetable' => 'zselex_shop_gallery',
                'item_id' => 'gallery_id',
                'type' => 'minisitegallery'
        ));

        if ($serviceEdit < 1) {
            return LogUtil::registerError($this->__f('Error! Unable to delete this %s.',
                        $this->__('Minisite Gallery Image')));
        }

        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
        if (empty($confirmation)) {
            // Add ZSELEX type ID
            $this->view->assign('IdValue', $id);
            $this->view->assign('confirm_title',
                $this->__f('Delete %s', $this->__('Minisite Gallery Image')));
            $this->view->assign('confirm_msg',
                $this->__f('Do you want to delete this %s',
                    $this->__('Minisite Gallery Image')));
            $this->view->assign('shop_id', $shop_id);
            $this->view->assign('IdName', 'id'); // edit id param name
            $this->view->assign('submitFunc', 'deleteMinisiteGalleryImage');
            $this->view->assign('cancelFunc', 'viewshopgalleryimages');

            // Return the output that has been generated by this function
            return $this->view->fetch('admin/deletecommon.tpl');
        }

        $argsitem = array(
            'table' => 'zselex_shop_gallery',
            'IdValue' => $id,
            'IdName' => 'gallery_id'
        );
        // Get the news type in the db
        $item     = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $argsitem);

        if (file_exists('zselexdata/'.$ownerName.'/minisitegallery/'.$item ['image_name'])) {
            unlink('zselexdata/'.$ownerName.'/minisitegallery/'.$item ['image_name']);
        }
        if (file_exists('zselexdata/'.$ownerName.'/minisitegallery/fullsize/'.$item ['image_name'])) {
            unlink('zselexdata/'.$ownerName.'/minisitegallery/fullsize/'.$item ['image_name']);
        }
        if (file_exists('zselexdata/'.$ownerName.'/minisitegallery/medium/'.$item ['image_name'])) {
            unlink('zselexdata/'.$ownerName.'/minisitegallery/medium/'.$item ['image_name']);
        }
        if (file_exists('zselexdata/'.$ownerName.'/minisitegallery/thumb/'.$item ['image_name'])) {
            unlink('zselexdata/'.$ownerName.'/minisitegallery/thumb/'.$item ['image_name']);
        }

        // echo "<pre>"; print_r($item); echo "</pre>"; exit;

        $where = "gallery_id=$id AND shop_id=$shop_id";
        if (DBUtil::deleteWhere('zselex_shop_gallery', $where)) {
            $where_keywords  = "WHERE type='minisitegallery' AND type_id=$id";
            $delete_keywords = DBUtil::deleteWhere('zselex_keywords',
                    $where_keywords);

            $servicetype   = 'minisitegallery';
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
                        'viewshopgalleryimages',
                        array(
                        'shop_id' => $shop_id
            )));
        } else {
            LogUtil::registerError($this->__('Error! Delete was NOT performed.'));

            return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                        'viewshopgalleryimages',
                        array(
                        'shop_id' => $shop_id
            )));
        }
    }

    public function createshoppdf($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());

        // error_reporting(E_ALL);
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        if (!(int) ($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)));
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $loguser   = UserUtil::getVar('uid');
        $user_id   = $loguser;
        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));
        $func      = 'createshoppdf';

        $serviceargs       = array(
            'shop_id' => $shop_id,
            'user_id' => $user_id,
            'type' => 'pdfupload'
        );
        $servicePermission = $this->servicePermission($serviceargs);

        if ($servicePermission ['perm'] < 1) {
            return LogUtil::registerError($servicePermission ['message']);
        }

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) { // disabled check
            if ($this->serviceDisabled('pdfupload') < 1) {
                return LogUtil::registerError($this->__('This service has been temporarily disabled!'));
            }
        }

        $diskquota = ModUtil::apiFunc('ZSELEX', 'admin', 'checkDiskquota',
                $args      = array(
                'shop_id' => $shop_id
        ));

        if ($diskquota ['count'] < 1) {
            return LogUtil::registerError($diskquota ['message']);
        } elseif ($diskquota ['limitover'] < 1) {
            return LogUtil::registerError($diskquota ['message']);
        }

        if ($_POST) {
            $files = FormUtil::getPassedValue('pdf_image',
                    isset($args ['pdf_image']) ? $args ['pdf_image'] : null,
                    'FILES');

            $filesize = $files ['size'];
            $allsize  = $diskquota ['sizeused'] + $filesize;
            if ($allsize >= $diskquota ['sizelimit']) {
                return LogUtil::registerError($this->__('File was not uploaded. Your disquota is exceeded for this shop. Please upgrade.'));
            }

            // make directories if not exist.
            if (!is_dir('zselexdata/'.$ownerName)) {
                mkdir('zselexdata/'.$ownerName, 0775);
                chmod('zselexdata/'.$ownerName, 0775);
            }

            if (!is_dir('zselexdata/'.$ownerName.'/pdfupload')) {
                mkdir('zselexdata/'.$ownerName.'/pdfupload', 0775);
                chmod('zselexdata/'.$ownerName.'/pdfupload', 0775);
            }

            if (!is_dir('zselexdata/'.$ownerName.'/pdfupload/thumb')) {
                mkdir('zselexdata/'.$ownerName.'/pdfupload/thumb', 0775);
                chmod('zselexdata/'.$ownerName.'/pdfupload/thumb', 0775);
            }

            if (!is_dir('zselexdata/'.$ownerName.'/pdfupload/tmp')) {
                mkdir('zselexdata/'.$ownerName.'/pdfupload/tmp', 0775);
                chmod('zselexdata/'.$ownerName.'/pdfupload/tmp', 0775);
            }

            $shop_id     = FormUtil::getPassedValue('shop_id', null, 'POST');
            $description = FormUtil::getPassedValue('description', null, 'POST');
            $keywords    = FormUtil::getPassedValue('keywords', null, 'POST');

            // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
            // echo "<pre>"; print_r($_FILES); echo "</pre>"; exit;

            if ($files ['error'] < 1) {
                // $checkImageSize = $this->validateImageSize($files);
                // if ($checkImageSize == true) {
                // echo "<pre>"; print_r($files); echo "</pre>"; exit;
                $random_digit = rand(0000, 9999);

                $basename   = basename($files ['name'], '.pdf');
                $image_name = preg_replace('/[^A-Za-z0-9_-]/', '', $basename).'-'.time();
                $alterName  = preg_replace('/[^A-Za-z0-9_-]/', '', $basename).'-'.time().'.pdf';
                $n          = $alterName;

                // $new_file_name = time() . '_' . $files['name'];
                $new_file_name = $n;
                // echo $new_file_name; exit;
                $newNme        = array(
                    'newName' => $new_file_name
                );
                $file          = array();
                $file          = $files + $newNme;

                // $destination = 'zselexdata/shoppdf';
                $destination = 'zselexdata/'.$ownerName.'/pdfupload';
                // echo $file; exit;
                if ($this->createPdfThumb($file, $destination) == true) {
                    $item = array(
                        'pdf_name' => $new_file_name,
                        'pdf_image' => $image_name,
                        'shop_id' => $shop_id,
                        'user_id' => $loguser,
                        'pdf_description' => $description,
                        'keywords' => $keywords
                    );

                    $args     = array(
                        'table' => 'zselex_shop_pdf',
                        'element' => $item,
                        'Id' => 'pdf_id'
                    );
                    $result   = ModUtil::apiFunc('ZSELEX', 'admin',
                            'createElement', $args);
                    $InsertId = DBUtil::getInsertID($args ['table'],
                            $args ['Id']);

                    if ($result) {

                        // ///////INSERT KEYWORDS//////
                        if (!empty($keywords)) {
                            $keywords_for_search = str_replace(',', ' ',
                                $keywords);
                            $keywords_for_search = explode(' ',
                                $keywords_for_search);
                            foreach ($keywords_for_search as $keyword) {
                                $keywordExist = ModUtil::apiFunc('ZSELEX',
                                        'admin', 'getCount',
                                        $args         = array(
                                        'table' => 'zselex_keywords',
                                        'where' => "keyword='".$keyword."'"
                                ));

                                if ($keywordExist < 1) {
                                    if (!empty($keyword)) {
                                        $keyword_item   = array(
                                            'keyword' => $keyword,
                                            'type' => 'pdfupload',
                                            'type_id' => $InsertId
                                        );
                                        $keyword_args   = array(
                                            'table' => 'zselex_keywords',
                                            'element' => $keyword_item,
                                            'Id' => 'keyword_id'
                                        );
                                        $result_keyword = ModUtil::apiFunc('ZSELEX',
                                                'admin', 'createElement',
                                                $keyword_args);
                                    }
                                }
                            }
                        }
                        // ///////////////////////
                        // if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                        $argsused = array(
                            'type' => 'pdfupload',
                            'shop_id' => $shop_id,
                            'user_id' => UserUtil::getVar('uid')
                        );
                        ModUtil::apiFunc('ZSELEX', 'user',
                            'updateArticleService', $argsused);
                        ModUtil::apiFunc('ZSELEX', 'admin', 'updateMinisite',
                            array(
                            'shop_id' => $shop_id
                        ));
                    }
                    // }
                }
                // }
            } else {
                LogUtil::registerError($this->__f('Error! Please select a valid file.'));
                $this->redirect(ModUtil::url('ZSELEX', 'admin', 'createshoppdf',
                        array(
                        'shop_id' => $shop_id
                )));
            }
            $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshoppdf',
                    array(
                    'shop_id' => $shop_id
            )));
        }
        $this->view->assign('func', $func);
        $this->view->assign('shop_id', $shop_id);

        return $this->view->fetch('admin/pdfupload/pdf.tpl');
    }

    public function shopPermission($shop_id)
    {
        // $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $shop_id = $shop_id;

        $user_id = UserUtil::getVar('uid');
        $perm    = ModUtil::apiFunc('ZSELEX', 'admin', 'shopPermission',
                array(
                'shop_id' => $shop_id,
                'user_id' => $user_id
        ));

        return $perm;
    }

    public function servicePermission($args)
    {
        return $perm = ModUtil::apiFunc('ZSELEX', 'admin', 'servicePermission',
                $args = $args);
    }

    public function deleteExtraPdfServices($args)
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
                    "type='pdfupload'"
                )
        ));
        if ($servicecheck ['availed'] > $servicecheck ['quantity']) {
            $service_used_extra  = $servicecheck ['availed'] - $servicecheck ['quantity'];
            $original_used_extra = $servicecheck ['availed'] - $service_used_extra;
            // echo $original_used_extra;
            $service_extra       = ModUtil::apiFunc('ZSELEX', 'user',
                    'selectArray',
                    $args                = array(
                    'table' => 'zselex_shop_pdf',
                    'where' => array(
                        "shop_id=$shop_id"
                    ),
                    'orderby' => 'pdf_id DESC',
                    'limit' => "LIMIT 0 , $service_used_extra"
            ));

            // echo "<pre>"; print_r($service_extra); echo "</pre>";

            foreach ($service_extra as $extra_item) {
                // sleep(2);
                // return LogUtil::registerError($this->__('File') . ': ' . $extra_item['pdf_name'] . ' ' . $this->__('could not be deleted!'));break;
                unlink('zselexdata/'.$ownername.'/pdfupload/'.$extra_item [pdf_name]);
                unlink('zselexdata/'.$ownername.'/pdfupload/thumb/'.$extra_item [pdf_image].'.jpg');
                $where = "pdf_id=$extra_item[pdf_id]";
                DBUtil::deleteWhere('zselex_shop_pdf', $where);

                // echo $extra_item['pdf_name'] . '<br>';
            }
            $upd_ser_args   = array(
                'table' => 'zselex_serviceshop',
                'items' => array(
                    'availed' => $original_used_extra
                ),
                'where' => array(
                    'shop_id' => $shop_id
                )
            );
            $update_service = ModUtil::apiFunc('ZSELEX', 'admin',
                    'updateElementWhere', $upd_ser_args);
        }

        return true;
    }

    public function viewshoppdf($args)
    {
        // exec('/usr/bin/convert -version', $output);
        // var_dump($output);
        // echo exec('echo $GHOSTSCRIPT');
        // echo "PATH :" . $GHOSTSCRIPT;
        // echo exec('echo $GHOSTSCRIPT');
        // echo "<pre>"; var_dump($_POST); echo "</pre>";
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $user_id = UserUtil::getVar('uid');

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $ownername = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));
        $this->view->assign('ownername', $ownername);

        $bulkaction = (int) FormUtil::getPassedValue('news_bulkaction_select',
                0, 'POST');
        $sids       = FormUtil::getPassedValue('news_selected_articles',
                array(), 'POST');
        if ($bulkaction >= 1 && $bulkaction <= 5) {
            // echo "exit;"; e xit;
            // echo "<pre>";print_r($sids); echo "</pre>"; exit;
            $actionmap    = array(
                // these indices are not constants, just unrelated integers
                1 => __('Delete'),
                2 => __('Archive'),
                3 => __('Publish'),
                4 => __('Reject'),
                5 => __('Change categories')
            );
            $updateresult = array(
                'successful' => array(),
                'failed' => array()
            );

            switch ($bulkaction) {

                case 1 : // delete
                    // echo "comes hereee"; exit;
                    foreach ($sids as $sid) {
                        $get     = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                                $getargs = array(
                                'table' => 'zselex_shop_pdf',
                                'where' => "pdf_id='".$sid."'"
                        ));
                        if (file_exists('zselexdata/'.$this->ownername.'/pdfupload/'.$get ['pdf_name'])) {
                            @unlink('zselexdata/'.$this->ownername.'/pdfupload/'.$get ['pdf_name']);
                        }
                        if (file_exists('zselexdata/'.$this->ownername.'/pdfupload/thumb/'.$get ['pdf_image'].'.jpg')) {
                            @unlink('zselexdata/'.$this->ownername.'/pdfupload/thumb/'.$get ['pdf_image'].'.jpg');
                        }

                        if (DBUtil::deleteObjectByID('zselex_shop_pdf', $sid,
                                'pdf_id')) {
                            // assume max pictures. if less, errors are supressed by @
                            $updateresult ['successful'] [] = $sid;
                            $args                           = array(
                                'shop_id' => $shop_id,
                                'servicetype' => 'pdfupload',
                                'user_id' => $user_id
                            );
                            $deleteservice                  = ModUtil::apiFunc('ZSELEX',
                                    'admin', 'deleteService', $args);
                        } else {
                            $updateresult ['failed'] [] = $sid;
                        }
                    }
                    if (sizeof($updateresult ['successful']) > 0) {
                        LogUtil::registerStatus($this->__('Done! Slected Items Deleted Successsfully.'));
                    }
                    break;
            }
        }

        /*
         * $folder = 'zselexdata/' . $ownername . '/pdfupload/';
         * if ($handle = opendir($folder)) {
         * while (false !== ($entry = readdir($handle))) {
         * if ($entry != "." && $entry != ".." && !is_dir($folder . $entry . '/')) {
         * echo "$entry<br>";
         * $files_in_folder[] = $entry;
         * }
         * }
         * closedir($handle);
         * }
         */

        $args_del_extra_service = array(
            'ownername' => $ownername,
            'shop_id' => $shop_id
        );
        $check                  = $this->deleteExtraPdfServices($args_del_extra_service);

        // $uploadpath = $_SERVER['DOCUMENT_ROOT'] . "/zselexdata/" . $ownername . "/";
        $uploadpath = 'zselexdata/'.$ownername.'/';
        if ($_SERVER ['SERVER_NAME'] == 'localhost') {
            $uploadpath = 'zselexdata/'.$ownername.'/';
        }
        $this->view->assign('uploadpath', $uploadpath);
        $admin = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN);
        $this->view->assign('admin', $admin);

        $disabled       = 'no';
        $servicecount   = 0;
        $error          = 0;
        $message        = '';
        $servicedisable = false;
        $template       = 'viewshoppdfimages.tpl';
        $ownerName      = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args           = array(
                'shop_id' => $shop_id
        ));
        $this->view->assign('ownerName', $ownerName);

        $minishop          = ModUtil::apiFunc('ZSELEX', 'admin',
                'minishopExist',
                $args              = array(
                'shop_id' => $shop_id
        ));
        $serviceargs       = array(
            'shop_id' => FormUtil::getPassedValue('shop_id', null, 'REQUEST'),
            'user_id' => $user_id,
            'type' => 'pdfupload'
        );
        // $servicePermission = $this->serviceCheck($serviceargs);
        $servicePermission = $this->servicePermission($serviceargs);
        // $test = $this->serviceCheckAll($serviceargs);
        // echo $test['perm'];
        // echo "<pre>"; print_r($servicePermission); echo "</pre>";
        // echo "Service Perm : " . $this->serviceCheckAll($serviceargs);
        // $servicecount += $servicePermission;
        $servicecount += $servicePermission ['perm'];
        if ($minishop < 1) {

            // $template = 'viewshopimages_noservice.tpl';
            $message = $this->__('Minishop has not been configured yet for this shop');
            ++$error;
            LogUtil::registerError(nl2br($message));
        }

        if ($servicePermission ['perm'] < 1) {

            // $template = 'viewshoppdf_images_noservice.tpl';
            // $message = $this->__("The service you try to use has to be purchased first");
            $message = $servicePermission ['message'];
            ++$error;
            LogUtil::registerError(nl2br($message));
        }

        if ($this->serviceDisabled('pdfupload') < 1) {
            if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $servicedisable = true;
                ++$error;
                $disable        = 'disabled';
            }
            $message = $this->__('This service is currently disabled');
            LogUtil::registerError(nl2br($message));
        }
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            $expired = $servicePermission ['expired'];
        }

        // echo $error;
        $this->view->assign('servicecount', $servicecount);
        $this->view->assign('servicedisable', $servicedisable);
        $this->view->assign('expired', $expired);
        $this->view->assign('disable', $disable);
        $this->view->assign('serviceerror', $error);
        $this->view->assign('message', $message);

        $item    = '';
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        $itemsperpage = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 20,
                'GETPOST');
        $startnum     = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : 0, 'GETPOST');

        $result = ModUtil::apiFunc('ZSELEX', 'admin', 'getShopPdfImages',
                $args   = array(
                'start' => $startnum,
                'itemsperpage' => $itemsperpage,
                'shop_id' => $shop_id
        ));

        $items = $result;

        $where        = " shop_id='".$shop_id."'";
        $getCountArgs = array(
            'table' => 'zselex_shop_pdf',
            'where' => $where,
            'Id' => 'pdf_id'
        );

        $total_count = ModUtil::apiFunc('ZSELEX', 'admin', 'countElements',
                $getCountArgs);

        $servicecheck = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow',
                $args         = array(
                'table' => 'zselex_serviceshop',
                'where' => array(
                    "shop_id=$shop_id",
                    "type='pdfupload'"
                )
        ));

        // echo "<pre>"; print_r($servicecheck); echo "</pre>";

        $servicelimit = $servicecheck ['quantity'] - $servicecheck ['availed'];

        $this->view->assign('quantity', $servicecheck ['quantity']);
        $this->view->assign('servicelimit', $servicelimit);

        // echo "<pre>"; print_r($item); echo "</pre>";
        $this->view->assign('startnum', $startnum);
        $this->view->assign('itemsperpage', $itemsperpage);
        $this->view->assign('total_count', $total_count);
        $this->view->assign('items', $items);
        // echo $template;
        return $this->view->fetch('admin/pdfupload/'.$template);
    }

    public function deleteshoppdf_image($args)
    {
        $Id        = FormUtil::getPassedValue('id',
                isset($args ['id']) ? $args ['id'] : null, 'REQUEST');
        $shop_id   = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));

        $obj = DBUtil::selectObjectByID('zselex_shop_pdf', $Id, 'pdf_id');

        // echo "<pre>"; print_r($obj); echo "</pre>"; exit;

        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
        // Validate the essential parameters
        if (empty($Id)) {
            return LogUtil::registerArgsError();
        }
        $args = array(
            'table' => 'zselex_shop_pdf',
            'IdValue' => $Id,
            'IdName' => 'pdf_id'
        );
        // Check for confirmation.
        if (empty($confirmation)) {
            // Add ZSELEX type ID
            $this->view->assign('IdValue', $Id);
            $this->view->assign('confirm_title',
                $this->__f('Delete %s', $this->__('Minisite PDF')));
            $this->view->assign('confirm_msg',
                $this->__f('Do you want to delete this %s',
                    $this->__('Minisite PDF')));
            $this->view->assign('shop_id', $shop_id);
            $this->view->assign('IdName', 'id');
            $this->view->assign('submitFunc', 'deleteshoppdf_image');
            $this->view->assign('cancelFunc', 'viewshoppdf');

            // Return the output that has been generated by this function
            return $this->view->fetch('admin/deletecommon.tpl');
        }

        // If we get here it means that the admin has confirmed the action
        // Confirm authorisation code
        $this->checkCsrfToken();

        // Delete
        if (ModUtil::apiFunc('ZSELEX', 'admin', 'deleteItem', $args)) {
            $where_keywords  = "WHERE type='pdfupload' AND type_id=$Id";
            $delete_keywords = DBUtil::deleteWhere('zselex_keywords',
                    $where_keywords);

            unlink('zselexdata/'.$ownerName.'/pdfupload/'.$obj ['pdf_name']);
            unlink('zselexdata/'.$ownerName.'/pdfupload/thumb/'.$obj ['pdf_image'].'.jpg');

            if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                // $loguser = UserUtil::getVar('uid');
                // $serviceupdatearg = array('user_id' => $loguser, 'type' => 'addproducts', 'shop_id' => '');
                // $serviceavailed = ModUtil::apiFunc('ZSELEX', 'admin', 'lessServiceAvailed', $serviceupdatearg);
            }

            $servicetype   = 'pdfupload';
            $user_id       = UserUtil::getVar('uid');
            $args          = array(
                'shop_id' => $shop_id,
                'servicetype' => $servicetype,
                'user_id' => $user_id
            );
            $deleteservice = ModUtil::apiFunc('ZSELEX', 'admin',
                    'deleteService', $args);
            // Success
            LogUtil::registerStatus($this->__('Done! PDF has been deleted successfully.'));

            // Let any hooks know that we have deleted an item
            $this->notifyHooks(new Zikula_ProcessHook('type.ui_hooks.types.process_delete',
                $Id));
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshoppdf',
                    array(
                    'shop_id' => $shop_id
        )));
    }

    public function pdfView($arg)
    {
        $pdf = FormUtil::getPassedValue('pdf', null, 'REQUEST');

        // echo $pdf; exit;

        $file = 'zselexdata/shoppdf/'.$pdf;

        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename='.basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: '.filesize($file));
            ob_clean();
            flush();
            readfile($file);
            exit();
        }
    }

    public function modifyshoppdf_images($args)
    {
        // echo $_SERVER['SERVER_NAME'];
        // error_reporting(E_ALL);
        $shop_id   = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $id        = FormUtil::getPassedValue('id', null, 'REQUEST');
        $func      = 'modifyshoppdf_images';
        $loguser   = UserUtil::getVar('uid');
        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));
        $this->view->assign('ownerName', $ownerName);

        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $serviceEdit = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceCheckEdit',
                $args        = array(
                'shop_id' => $shop_id,
                'item_idValue' => $id,
                'user_id' => $loguser,
                'servicetable' => 'zselex_shop_pdf',
                'item_id' => 'pdf_id',
                'type' => 'pdfupload'
        ));
        if ($serviceEdit < 1) {
            // return LogUtil::registerError($this->__f('Error! Unable to edit this %s.', $this->__('Minisite PDF')));
        }

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            if ($this->serviceDisabled('pdfupload') < 1) {
                return LogUtil::registerError($this->__('This service has been temporarily disabled!'));
            }
        }

        if ($_POST) {
            $files = FormUtil::getPassedValue('pdf_image',
                    isset($args ['pdf_image']) ? $args ['pdf_image'] : null,
                    'FILES');

            $shop_id       = FormUtil::getPassedValue('shop_id', null, 'POST');
            $file_id       = FormUtil::getPassedValue('pdf_id', null, 'POST');
            $description   = FormUtil::getPassedValue('description', null,
                    'POST');
            $keywords      = FormUtil::getPassedValue('keywords', null, 'POST');
            $exitingPdf    = FormUtil::getPassedValue('exitingPdf', null, 'POST');
            $existingImage = FormUtil::getPassedValue('existingImage', null,
                    'POST');

            // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
            // echo "<pre>"; print_r($_FILES); echo "</pre>"; exit;
            $obj = array(
                'pdf_description' => $description,
                'keywords' => $keywords
            );
            if ($files ['error'] < 1) {
                if (!empty($exitingPdf)) {
                    $existingFileSize = filesize('zselexdata/'.$ownerName.'/minisiteimages/'.$existingImage);
                } else {
                    $existingFileSize = 0;
                }

                $diskquota = ModUtil::apiFunc('ZSELEX', 'admin',
                        'checkDiskquota',
                        $args      = array(
                        'shop_id' => $shop_id
                ));
                // echo "<pre>"; print_r($diskquota); echo "</pre>"; exit;
                $allsize   = $diskquota ['sizeused'] - $existingFileSize;
                // echo $allsize; exit;
                $filesize  = $files ['size'];
                // echo $filesize; exit;
                $allsizes  = $allsize + $filesize;
                if ($allsizes >= $diskquota ['sizelimit']) {
                    return LogUtil::registerError($this->__('File was not uploaded. You need more disquoata to upload this file. Please upgrade.'));
                }

                if (file_exists('zselexdata/'.$ownerName.'/pdfupload/'.$exitingPdf)) {
                    unlink('zselexdata/'.$ownerName.'/pdfupload/'.$exitingPdf);
                }
                if (file_exists('zselexdata/'.$ownerName.'/pdfupload/thumb/'.$existingImage.'.jpg')) {
                    unlink('zselexdata/'.$ownerName.'/pdfupload/thumb/'.$existingImage.'.jpg');
                }

                // make directories if not exist.
                if (!is_dir('zselexdata/'.$ownerName)) {
                    mkdir('zselexdata/'.$ownerName, 0775);
                    chmod('zselexdata/'.$ownerName, 0775);
                }

                if (!is_dir('zselexdata/'.$ownerName.'/pdfupload')) {
                    mkdir('zselexdata/'.$ownerName.'/pdfupload', 0775);
                    chmod('zselexdata/'.$ownerName.'/pdfupload', 0775);
                }

                if (!is_dir('zselexdata/'.$ownerName.'/pdfupload/thumb')) {
                    mkdir('zselexdata/'.$ownerName.'/pdfupload/thumb', 0775);
                    chmod('zselexdata/'.$ownerName.'/pdfupload/thumb', 0775);
                }

                $basename      = basename($files ['name'], '.pdf');
                $image_name    = preg_replace('/[^A-Za-z0-9_-]/', '', $basename).'-'.time();
                $alterName     = preg_replace('/[^A-Za-z0-9_-]/', '', $basename).'-'.time().'.pdf';
                $n             = $alterName;
                $new_file_name = $n;
                $newNme        = array(
                    'newName' => $new_file_name
                );
                $file          = array();
                $file          = $files + $newNme;

                // $destination = 'zselexdata/shoppdf';
                $destination  = 'zselexdata/'.$ownerName.'/pdfupload';
                $this->createPdfThumb($file, $destination); // generate pdf thumbnail and save
                $where        = " shop_id='".$shop_id."' AND defaultImg='1'";
                $getCountArgs = array(
                    'table' => 'zselex_shop_pdf',
                    'where' => $where,
                    'Id' => 'pdf_id'
                );

                $dfltImgcount_count = ModUtil::apiFunc('ZSELEX', 'admin',
                        'countElements', $getCountArgs);
                // echo $dfltImgcount_count; exit;

                if ($dfltImgcount_count < 1) {
                    $addDefault = 1;
                } else {
                    $addDefault = 0;
                }
                $obj = array(
                    'pdf_description' => $description,
                    'keywords' => $keywords,
                    'pdf_name' => $new_file_name,
                    'pdf_image' => $image_name
                );
                // }
            }
            $pntables  = pnDBGetTables();
            $column    = $pntables ['zselex_shop_pdf_column'];
            $where     = "WHERE $column[pdf_id]=$file_id";
            $updatepdf = DBUTil::updateObject($obj, 'zselex_shop_pdf', $where);

            ModUtil::apiFunc('ZSELEX', 'admin', 'updateMinisite',
                array(
                'shop_id' => $shop_id
            ));
            $where_keyword = "WHERE type='pdfupload' AND type_id=$file_id";
            if (DBUtil::deleteWhere('zselex_keywords', $where_keyword)) {
                if (!empty($keywords)) {
                    $keywords_for_search = str_replace(',', ' ', $keywords);
                    $keywords_for_search = explode(' ', $keywords_for_search);
                    foreach ($keywords_for_search as $keyword) {
                        $keywordExist = ModUtil::apiFunc('ZSELEX', 'admin',
                                'getCount',
                                $args         = array(
                                'table' => 'zselex_keywords',
                                'where' => "keyword='".$keyword."'"
                        ));

                        if ($keywordExist < 1) {
                            if (!empty($keyword)) {
                                $keyword_item   = array(
                                    'keyword' => $keyword,
                                    'type' => 'pdfupload',
                                    'type_id' => $file_id,
                                    'shop_id' => $shop_id
                                );
                                $keyword_args   = array(
                                    'table' => 'zselex_keywords',
                                    'element' => $keyword_item,
                                    'Id' => 'keyword_id'
                                );
                                $result_keyword = ModUtil::apiFunc('ZSELEX',
                                        'admin', 'createElement', $keyword_args);
                            }
                        }
                    }
                }
            }

            $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshoppdf',
                    array(
                    'shop_id' => $shop_id
            )));
        }

        $item = DBUtil::selectObjectByID('zselex_shop_pdf', $id, 'pdf_id');
        $this->view->assign('func', $func);
        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('id', $id);
        $this->view->assign('item', $item);

        return $this->view->fetch('admin/pdf.tpl');
    }

    public function createPdfThumb($file, $destination)
    {
        // echo "comes here.."; exit;
        // ini_set('display_errors', '1');
        // error_reporting(E_ALL);
        try {
            // $destination = 'zselexdata/shoppdf/';
            $pdfDirectory   = $destination;
            // $thumbDirectory = "zselexdata/shoppdf/thumb/";
            $thumbDirectory = $destination.'/thumb/';
            // print_r($file); exit;
            // echo $file['newName']; exit;
            $name           = $file ['name'];
            // exit;
            // Check file extension

            $allowedExtensions = array(
                'pdf'
            );
            $ex                = end(explode('.', $name));
            if (!in_array($ex, $allowedExtensions)) {
                return LogUtil::registerError($this->__f('Error! Invalid file type: %1$s',
                            $ex));
            }
            // Check file size
            if ($size >= 16000000) {
                return LogUtil::registerError($this->__('Error! Your file is too big. The limit is 14 MB.'));
            }
            $newNme = $file ['newName'];

            // echo $newNme; exit;

            $thumb = basename($newNme, '.pdf');
            // $thumb = preg_replace("/[^A-Za-z0-9_-]/", "", $thumb) . ".pdf";
            $thumb = preg_replace('/[^A-Za-z0-9_-]/', '', $thumb);
            // echo $thumb; exit;

            $code        = self::doUploadFile($file, $destination);
            // the path to the PDF file
            $pdfWithPath = $pdfDirectory.'/'.$newNme;
            // echo $pdfWithPath; exit;
            // add the desired extension to the thumbnail
            // $time = time();
            $thumb       = $thumb.'.jpg';

            // echo $thumb; exit;
            // echo $thumbDirectory.$thumb; exit;

            $finalPath = $thumbDirectory.$thumb;
            // exec("convert \"{$pdfWithPath}[0]\" -colorspace RGB -geometry 120 $finalPath");
            // if ($_SERVER['SERVER_NAME'] == 'localhost') { // only for localhost
            exec("convert -define jpeg:size=60x60 \"{$pdfWithPath}[0]\" -colorspace RGB -thumbnail 100x150 -gravity center -crop 100x150+0+0 +repage $finalPath");
            return true;
            // }
            // KIMENEMARK BEGIN
            $pdfpage   = 1;
            // $basepath = $_SERVER['DOCUMENT_ROOT'] . '/' . $destination . '/';
            $basepath  = $destination.'/';
            // echo $basepath; exit;
            $pdf_name  = $basepath.$newNme;
            $jpgname   = $basepath.'thumb/'.basename($newNme, '.pdf').'.jpg';
            $gsjpgname = $basepath.'tmp/'.basename($newNme, '.pdf').'.jpg';

            $gscommand = '/usr/bin/gs -sDEVICE=jpeg -sCompression=lzw -r300x300  -dNOPAUSE -dFirstPage='.$pdfpage.' -dLastPage='.$pdfpage.' -sOutputFile="'.$gsjpgname.'" '.$pdf_name;
            $command   = '/usr/bin/convert -define jpeg:size=60x60 '.$gsjpgname.' -colorspace RGB -thumbnail 100x150 -gravity center -crop 100x150+0+0 '.$jpgname;
            exec($gscommand);
            exec($command);
            unlink($gsjpgname);
            // echo "$basePath\n\n";
            // echo "$pdfDirectory\n\n";
            // echo "$gsjpgname\n\n";
            // echo "$gscommand\n\n";
            // echo "$command\n\n";
            // exit;
            exec($gscommand);
            exec($command);
            unlink($gsjpgname);
            // KIMENEMARK END
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }

        return true;
    }

    public function chooseIshopDodtProduct($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));
        $this->view->assign('ownerName', $ownerName);
        $domain    = pnGetBaseURL();

        $sql = '';
        if ($_POST ['saveproduct'] == 1) {
            $redirect = FormUtil::getPassedValue('redirect', null, 'REQUEST');
            $dotdId   = FormUtil::getPassedValue('dotdId', null, 'REQUEST');

            $dotd_name    = FormUtil::getPassedValue('dotd_name', null,
                    'REQUEST');
            $dotd_date    = FormUtil::getPassedValue('dotd_date', null,
                    'REQUEST');
            $dotdKeywords = FormUtil::getPassedValue('keywords', null, 'REQUEST');

            // echo $dotd_name; exit;
            // echo "product: " . $_POST['iproduct'];
            // echo $redirect; exit;

            $_SESSION ['iproduct'] = FormUtil::getPassedValue('iproduct', null,
                    'POST');

            if ($redirect == 'modify') {
                $_SESSION ['/'] ['createdotd'] ['elemtName'] = $dotd_name;
                $_SESSION ['/'] ['createdotd'] ['dotddate']  = $dotd_date;
                $_SESSION ['/'] ['createdotd'] ['keywords']  = $dotdKeywords;
                echo "<script>
                   window.close();
                   window.opener.location='".$domain."index.php?module=ZSELEX&type=admin&func=modifydotd&dotdId=$dotdId&shop_id=$shop_id';
                   
                 </script>";
            } else {
                $_SESSION ['/'] ['createdotd'] ['elemtName'] = $dotd_name;
                $_SESSION ['/'] ['createdotd'] ['dotddate']  = $dotd_date;
                $_SESSION ['/'] ['createdotd'] ['keywords']  = $dotdKeywords;
                echo "<script>
                   window.close();
                   window.opener.location='".$domain."index.php?module=ZSELEX&type=admin&func=dotd&shop_id=$shop_id';
                
                 </script>";
            }
        }

        $searchproduct = FormUtil::getPassedValue('searchproduct',
                isset($args ['searchproduct']) ? $args ['searchproduct'] : null,
                'GETPOST');
        $this->view->assign('searchproduct', $searchproduct);

        $item    = '';
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        if (isset($searchproduct) && $searchproduct != '') {
            $sql .= " AND product_name LIKE '%".DataUtil::formatForStore($searchproduct)."%'";
        }

        $itemsperpage = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 25,
                'GETPOST');
        $startnum     = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : 0, 'GETPOST');

        $result = ModUtil::apiFunc('ZSELEX', 'admin', 'chooseIshopProducts',
                $args   = array(
                'start' => $startnum,
                'itemsperpage' => $itemsperpage,
                'shop_id' => $shop_id,
                'sql' => $sql
        ));

        $items = $result;

        $where        = " shop_id='".$shop_id."' $sql";
        $getCountArgs = array(
            'table' => 'zselex_products',
            'where' => $where,
            'Id' => 'product_id'
        );

        $total_count = ModUtil::apiFunc('ZSELEX', 'admin', 'countElements',
                $getCountArgs);

        // echo "<pre>"; print_r($item); echo "</pre>";
        $this->view->assign('startnum', $startnum);
        $this->view->assign('itemsperpage', $itemsperpage);
        $this->view->assign('total_count', $total_count);
        $this->view->assign('items', $items);

        return $this->view->fetch('admin/choosedotdproduct.tpl');
    }

    public function chooseZshopDodtProduct($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $minishop = ModUtil::apiFunc('ZSELEX', 'admin', 'minishopExist',
                $args     = array(
                'shop_id' => $shop_id
        ));
        // echo $minishop; exit;
        $domain   = pnGetBaseURL();

        $sql = '';
        if ($_POST ['saveproduct'] == 1) {
            $redirect = FormUtil::getPassedValue('redirect', null, 'REQUEST');
            $dotdId   = FormUtil::getPassedValue('dotdId', null, 'REQUEST');

            $dotd_name = FormUtil::getPassedValue('dotd_name', null, 'REQUEST');
            $dotd_date = FormUtil::getPassedValue('dotd_date', null, 'REQUEST');

            // echo "product: " . $_POST['zenproduct']; exit;

            $_SESSION ['zenproduct'] = FormUtil::getPassedValue('zenproduct',
                    null, 'REQUEST');

            if ($redirect == 'modify') {
                $_SESSION ['/'] ['createdotd'] ['elemtName'] = $dotd_name;
                $_SESSION ['/'] ['createdotd'] ['dotddate']  = $dotd_date;
                echo "<script>
                   window.close();
                   window.opener.location='".$domain."index.php?module=ZSELEX&type=admin&func=modifydotd&dotdId=$dotdId&shop_id=$shop_id';
                 </script>";
            } else {
                $_SESSION ['/'] ['createdotd'] ['elemtName'] = $dotd_name;
                $_SESSION ['/'] ['createdotd'] ['dotddate']  = $dotd_date;
                echo "<script>
                   window.close();
                   window.opener.location='".$domain."index.php?module=ZSELEX&type=admin&func=dotd&shop_id=$shop_id';
                 </script>";
            }
        }
        // $obj = DBUtil::selectObjectByID('zselex_shop', $shop_id, 'shop_id');
        // $obj = DBUtil::selectObjectByID('zselex_shop', $shop_id, 'shop_id');

        $obj  = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                $args = array(
                'table' => 'zselex_zenshop',
                'where' => "shop_id=$shop_id"
        ));

        // echo "<pre>"; print_r($obj); echo "</pre>"; exit;

        $searchproduct = FormUtil::getPassedValue('searchproduct',
                isset($args ['searchproduct']) ? $args ['searchproduct'] : null,
                'GETPOST');
        $this->view->assign('searchproduct', $searchproduct);

        $item = '';

        if (isset($searchproduct) && $searchproduct != '') {
            $sql .= " AND b.products_name LIKE '%".DataUtil::formatForStore($searchproduct)."%'";
        }

        $itemsperpage = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 25,
                'GETPOST');
        $startnum     = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : 0, 'GETPOST');

        if ($startnum > 0) {
            $startnum = $startnum - 1;
        } else {
            $startnum = $startnum;
        }

        $limit = "LIMIT $startnum , $itemsperpage";

        $result = ModUtil::apiFunc('ZSELEX', 'admin', 'getZenCartProducts',
                $args   = array(
                'shop_id' => $shop_id,
                'sql' => $sql,
                'shop' => $obj,
                'limit' => $limit
        ));

        // print_r($result); exit;

        $items = $result;

        // $where = " shop_id='" . $shop_id . "' $sql";
        // $getCountArgs = array('table' => 'zselex_products',
        // 'where' => $where,
        // 'Id' => 'product_id',
        // );
        // $total_count = ModUtil::apiFunc('ZSELEX', 'admin', 'countElements', $getCountArgs);
        $total_count = ModUtil::apiFunc('ZSELEX', 'admin',
                'getZenCartProductsCount',
                $args        = array(
                'shop_id' => $shop_id,
                'sql' => $sql,
                'shop' => $obj
        ));

        // echo "COUNT :" . $total_count;
        // echo "<pre>"; print_r($item); echo "</pre>";
        $this->view->assign('startnum', $startnum);
        $this->view->assign('itemsperpage', $itemsperpage);
        $this->view->assign('total_count', $total_count);
        $this->view->assign('items', $items);

        return $this->view->fetch('admin/choosezencartdotdproduct.tpl');
    }

    public function minishop($args)
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
        // $this->payMentAlert($shop_id);
        $repo     = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        $loguser  = UserUtil::getVar('uid');
        $loguser  = !empty($loguser) ? $loguser : 0;
        $user_id  = $loguser;
        $admin    = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN);
        $error    = 0;
        $disabled = 0;

        $serviceargs       = array(
            'shop_id' => $shop_id,
            'user_id' => $user_id,
            'type' => 'minishop'
        );
        $servicePermission = $this->servicePermission($serviceargs);

        if ($servicePermission ['perm'] < 1) {
            LogUtil::registerError($this->__('The service you try to use has to be purchased first.'));
            ++$error;
        }

        if ($this->serviceDisabled('minishop') < 1) {
            LogUtil::registerError($this->__('This service has been temporarily disabled!'));
            ++$error;
            if (!$admin) {
                $servicedisable = true;
                ++$disabled;
            }
        }
        if (!$admin) {
            $expired = $servicePermission ['expired'];
        }

        $this->view->assign('disabled', $servicedisable);
        $this->view->assign('error', $error);
        $this->view->assign('expired', $expired);

        if ($_POST ['action'] == 1) { // submit
            $formElements = FormUtil::getPassedValue('formElements',
                    isset($args ['formElements']) ? $args ['formElements'] : null,
                    'POST');
            // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;
            $shopType     = $formElements ['shoptype'];
            $miniShopItem = array(
                'shop' => $shop_id,
                'shoptype' => $shopType,
                'minishop_name' => $formElements ['elemtName'],
                'description' => $formElements ['elemtDesc'],
                'configured' => '1'
            );
            if (!isset($formElements ['minishop_id']) || empty($formElements ['minishop_id'])) {

                // echo "comes1";  exit;
                $mshop                 = new ZSELEX_Entity_MiniShop ();
                $shopObj               = $this->entityManager->find('ZSELEX_Entity_Shop',
                    $shop_id);
                $mshop->setShop($shopObj);
                $mshop->setShoptype($shopType);
                $mshop->setMinishop_name($formElements ['elemtName']);
                $mshop->setDescription($formElements ['elemtDesc']);
                $mshop->setConfigured(1);
                $this->entityManager->persist($mshop);
                $this->entityManager->flush();
                $InsertId              = $mshop->getId();
                $result                = $InsertId;
                $resultMiniShop ['id'] = $InsertId;
            } else {
                // echo "comes2";  exit;
                // echo "<pre>"; print_r($miniShopItem); echo "</pre>"; exit;
                $updateminishop        = $repo->updateEntity(null,
                    'ZSELEX_Entity_MiniShop', $miniShopItem,
                    array(
                    'a.shop' => $formElements ['shop_id']
                ));
                $resultMiniShop ['id'] = $formElements ['minishop_id'];
            }

            // echo "<pre>"; print_r($resultMiniShop); echo "</pre>"; exit;

            if ($shopType == 'zSHOP') {
                //  echo "comes here"; exit;
                $item = array(
                    'shop' => $shop_id,
                    'minishop_id' => $resultMiniShop ['id'],
                    'domain' => isset($formElements ['domain']) ? $formElements ['domain']
                            : '',
                    'hostname' => isset($formElements ['host']) ? $formElements ['host']
                            : '',
                    'dbname' => isset($formElements ['database']) ? $formElements ['database']
                            : '',
                    'username' => isset($formElements ['username']) ? $formElements ['username']
                            : '',
                    'password' => isset($formElements ['password']) ? $formElements ['password']
                            : '',
                    'table_prefix' => isset($formElements ['tableprefix']) ? $formElements ['tableprefix']
                            : ''
                    )
                // 'status' => isset($formElements['status']) ? $formElements['status'] : 0
                ;
                /*
                 *
                 * $zShopExist = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $args = array(
                 * 'table' => 'zselex_zenshop',
                 * 'where' => "shop_id='" . $formElements['shop_id'] . "'"
                 * ));
                 */

                $zShopExist = $repo->getCount(null, 'ZSELEX_Entity_ZenShop',
                    'zen_id',
                    array(
                    'a.shop' => $shop_id
                ));

                if ($zShopExist < 1) {
                    /*
                     * $args = array(
                     * 'table' => 'zselex_zenshop',
                     * 'element' => $item,
                     * 'Id' => 'zen_id'
                     * );
                     * $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $args);
                     */
                    $zen      = new ZSELEX_Entity_ZenShop ();
                    $shopObj  = $this->entityManager->find('ZSELEX_Entity_Shop',
                        $shop_id);
                    $zen->setShop($shopObj);
                    $zen->setMinishop_id($resultMiniShop ['id']);
                    $zen->setDomain(isset($formElements ['domain']) ? $formElements ['domain']
                                : '' );
                    $zen->setHostname(isset($formElements ['host']) ? $formElements ['host']
                                : '' );
                    $zen->setDbname(isset($formElements ['database']) ? $formElements ['database']
                                : '' );
                    $zen->setUsername(isset($formElements ['username']) ? $formElements ['username']
                                : '' );
                    $zen->setPassword(isset($formElements ['password']) ? $formElements ['password']
                                : '' );
                    $zen->setTable_prefix(isset($formElements ['tableprefix']) ? $formElements ['tableprefix']
                                : '' );
                    $this->entityManager->persist($zen);
                    $this->entityManager->flush();
                    $InsertId = $zen->getZen_id();
                    $result   = $InsertId;
                    // $InsertId = DBUtil::getInsertID($args['table'], $args['Id']);
                } else {
                    /*
                     * $updateargs = array(
                     * 'table' => 'zselex_zenshop',
                     * 'items' => $item,
                     * 'where' => array(
                     * 'shop_id' => $formElements['shop_id']
                     * )
                     * );
                     * $update = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElementWhere', $updateargs);
                     */
                    $update = $repo->updateEntity(null, 'ZSELEX_Entity_ZenShop',
                        $item,
                        array(
                        'a.shop' => $formElements ['shop_id']
                    ));
                }

                LogUtil::registerStatus($this->__('Done! Shop settings for ZenCart shop has been configured successfully.'));
                $this->redirect(ModUtil::url('ZSELEX', 'admin', 'minishop',
                        array(
                        'shop_id' => $formElements ['shop_id']
                )));
                // $this->view->assign('type', 'zSHOP');
                // $this->view->assign('shop_id', $shop_id);
                // return $this->view->fetch('admin/minishop/redirecting.tpl');
            } elseif ($shopType == 'iSHOP') {
                LogUtil::registerStatus($this->__('Done! Shop settings for internal shop has been configured successfully.'));
                // $this->view->assign('type', 'iSHOP');
                // $this->view->assign('shop_id', $shop_id);
                // return $this->view->fetch('admin/minishop/redirecting.tpl');

                $this->redirect(ModUtil::url('ZSELEX', 'admin', 'minishop',
                        array(
                        'shop_id' => $formElements ['shop_id']
                )));
            }
            // echo "<pre>"; print_r($formElements); echo "</pre>";
        }

        // $minishopExist = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $args = array('table'=>'zselex_minishop' , 'where'=>"shop_id=$shop_id"));
        // $minishopExist = ModUtil::apiFunc('ZSELEX', 'admin', 'selectWhere', $args = array('table' => 'zselex_minishop', 'where' => array("shop_id" => $shop_id)));
        // $minishopExist = DBUtil::selectObjectByID('zselex_minishop', $shop_id, 'shop_id');
        $minishopExist = $repo->get(array(
            'entity' => 'ZSELEX_Entity_MiniShop',
            'where' => array(
                'a.shop' => $shop_id
            )
        ));
        $count         = count($minishopExist);

        /*
         * if ($count > 1) {
         *
         * if ($minishopExist['shoptype'] == 'zSHOP') {
         * //$this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewZenShop', array('shop_id' => $shop_id)));
         * //header("Refresh: 5;url='" . ModUtil::url('ZSELEX', 'admin', 'viewZenShop', array('shop_id' => $shop_id) . "'"));
         * $this->view->assign('type', 'zSHOP');
         * $this->view->assign('shop_id', $shop_id);
         * return $this->view->fetch('admin/minishop/redirecting.tpl');
         * } elseif ($minishopExist['shoptype'] == 'iSHOP') {
         * $this->view->assign('type', 'iSHOP');
         * $this->view->assign('shop_id', $shop_id);
         * return $this->view->fetch('admin/minishop/redirecting.tpl');
         * }
         * }
         *
         */
        // else {
        /*
         * $shoptypes = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements', $args = array(
         * 'table' => 'zselex_shop_types',
         * 'where' => '',
         * 'orderBy' => 'shoptype ASC',
         * 'useJoins' => ''
         * ));
         */

        $shoptypes               = $repo->getAll(array(
            'entity' => 'ZSELEX_Entity_ShopType',
            'orderby' => 'a.shoptype ASC'
        ));
        // echo "<pre>"; print_r($shoptypes); echo "</pre>";
        // $shopTypes = DBUtil::selectObjectArray('zselex_shop_types'); //get all shop types
        $this->view->assign('shoptypes', $shoptypes);
        $shoptype                = ModUtil::apiFunc('ZSELEX', 'admin',
                'shopType',
                $args                    = array(
                'shop_id' => $shop_id
        ));
        // if ($shoptype['shoptype'] == 'zSHOP') {
        // $minishopExist['zshop'] = DBUtil::selectObjectByID('zselex_zenshop', $shop_id, 'shop_id');
        $minishopExist ['zshop'] = $repo->get(array(
            'entity' => 'ZSELEX_Entity_ZenShop',
            'where' => array(
                'a.shop' => $shop_id
            )
        ));
        // }
        // echo "<pre>"; print_r($minishopExist); echo "</pre>";
        $this->view->assign('error', $error);
        $this->view->assign('disabled', $disabled);
        $this->view->assign('minishop', $minishopExist);

        return $this->view->fetch('admin/minishop/createminishop.tpl');
        // }
    }

    public function viewZenShop($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        if ($_POST ['action'] == 1) {

            // LogUtil::registerStatus($this->__('Done! ZenCart shop details has been updated successfully.'));
        }

        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $zenShop = DBUtil::selectObjectByID('zselex_zenshop', $shop_id,
                'shop_id');

        $this->view->assign($zenShop);

        return $this->view->fetch('admin/minishop/viewzenshop.tpl');
    }

    public function updateZenShop($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        $formElements = FormUtil::getPassedValue('formElements',
                isset($args ['formElements']) ? $args ['formElements'] : null,
                'POST');

        // echo "<pre>"; print_r($formElements); echo "</pre>";

        $item       = array(
            'zen_id' => $formElements ['zen_id'],
            'shop_id' => $formElements ['shop_id'],
            'domain' => isset($formElements ['domain']) ? $formElements ['domain']
                    : '',
            'hostname' => isset($formElements ['host']) ? $formElements ['host']
                    : '',
            'dbname' => isset($formElements ['database']) ? $formElements ['database']
                    : '',
            'username' => isset($formElements ['username']) ? $formElements ['username']
                    : '',
            'password' => isset($formElements ['password']) ? $formElements ['password']
                    : '',
            'table_prefix' => isset($formElements ['tableprefix']) ? $formElements ['tableprefix']
                    : ''
            )
        // 'status' => isset($formElements['status']) ? $formElements['status'] : 0,
        ;
        $updateargs = array(
            'table' => 'zselex_zenshop',
            'IdValue' => $formElements ['zen_id'],
            'IdName' => 'zen_id',
            'element' => $item
        );

        $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement',
                $updateargs);
        LogUtil::registerStatus($this->__('Done! ZenCart shop details has been updated successfully.'));

        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewZenShop',
                array(
                'shop_id' => $formElements ['shop_id']
        )));

        // exit;
    }

    public function deleteZenShop($args)
    {
        $zen_id  = FormUtil::getPassedValue('id', null, 'REQUEST');
        $shop_id = FormUtil::getPassedValue('sid', null, 'REQUEST');
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $minishop_id = FormUtil::getPassedValue('mid', null, 'REQUEST');
        if (!empty($zen_id)) {
            DBUtil::deleteObjectById('zselex_zenshop', $zen_id, 'zen_id');
        }
        if (!empty($minishop_id)) {
            DBUtil::deleteObjectById('zselex_minishop', $minishop_id, 'id');
        }
        LogUtil::registerStatus($this->__('Done! You have unsubscribed successfully.'));
        ModUtil::apiFunc('ZSELEX', 'admin', 'updateMinisite',
            array(
            'shop_id' => $shop_id
        ));
        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'minishop',
                array(
                'shop_id' => $shop_id
        )));
    }

    public function deleteIshop($args)
    {
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $minishop_id = FormUtil::getPassedValue('mid', null, 'REQUEST');
        DBUtil::deleteObjectById('zselex_minishop', $minishop_id, 'id');

        $products = ModUtil::apiFunc('ZSELEX', 'admin', 'selectWhere',
                $args     = array(
                'table' => 'zselex_products',
                'where' => array(
                    'shop_id' => $shop_id
                )
        ));

        // echo "<pre>"; print_r($product); echo "</pre>"; exit;

        foreach ($products as $item) {
            unlink('zselexdata/products/'.$item ['prd_image']);
            unlink('zselexdata/products/thumbs/'.$item ['prd_image']);
            unlink('zselexdata/products/medium/'.$item ['prd_image']);
        }

        DBUtil::deleteObjectById('zselex_products', $shop_id, 'shop_id');
        ModUtil::apiFunc('ZSELEX', 'admin', 'updateMinisite',
            array(
            'shop_id' => $shop_id
        ));
        LogUtil::registerStatus($this->__('Done! You have unsubscribed successfully.'));
        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'minishop',
                array(
                'shop_id' => $shop_id
        )));
    }

    public function existingOwner($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        if (!(int) ($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)));
        }

        $repo = $this->entityManager->getRepository('ZSELEX_Entity_ShopOwner');

        /*
         * $ownerExist = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $args = array(
         * 'table' => 'zselex_shop_owners',
         * 'where' => "shop_id=$shop_id"
         * ));
         */

        $ownerExist = $repo->getCount(null, 'ZSELEX_Entity_ShopOwner', 'id',
            array(
            'a.shop' => $shop_id
        ));
        // echo $ownerExist;

        if ($ownerExist > 0) {
            LogUtil::registerError($this->__('Error! An owner already exists for this site!<br>'));
            $this->redirect(ModUtil::url('ZSELEX', 'adminusers', 'viewOwner',
                    array(
                    'shop_id' => $shop_id
            )));
        }

        // $search

        if ($_POST) {
            // echo "<pre>"; print_r($_POST); echo "</pre>";
            if ($_POST ['submit'] == 'Filter') {
                
            } else {
                $selectedOwner = FormUtil::getPassedValue('shopowners', null,
                        'POST');
                // echo "<pre>"; print_r($selectedOwners); echo "</pre>"; exit;
                if (!empty($selectedOwner)) {
                    $owner = $selectedOwner;
                    // foreach ($selectedOwners as $key => $owner) {
                    // echo $shop_id;
                    /*
                     * $count = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $args = array(
                     * 'table' => 'zselex_shop_owners',
                     * 'where' => "shop_id=$shop_id AND user_id=$owner"
                     * ));
                     */
                    $count = $repo->getCount(null, 'ZSELEX_Entity_ShopOwner',
                        'id',
                        array(
                        'a.shop' => $shop_id,
                        'a.user_id' => $owner
                    ));
                    // echo $count; exit;
                    if ($count < 1) {
                        $item        = array(
                            'user_id' => $owner,
                            'shop_id' => $shop_id
                        );
                        $args        = array(
                            'table' => 'zselex_shop_owners',
                            'element' => $item,
                            'Id' => 'id'
                        );
                        // $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $args);
                        $createOwner = new ZSELEX_Entity_ShopOwner ();
                        $createOwner->setUser_id($owner);
                        $createOwner->setShop($this->entityManager->find('ZSELEX_Entity_Shop',
                                $shop_id));
                        $createOwner->setMain(1);
                        $this->entityManager->persist($createOwner);
                        $this->entityManager->flush();
                        LogUtil::registerStatus($this->__('Done! Owner has been assigned successfully.'));
                    } else {
                        LogUtil::registerError($this->__f('Error! %s already exists as owner for this shop!<br>',
                                $key));
                    }
                    // }
                }

                $this->redirect(ModUtil::url('ZSELEX', 'adminusers',
                        'viewOwner',
                        array(
                        'shop_id' => $shop_id
                )));
            }
        }

        $this->view->assign('shop_id', $shop_id);

        $modvariable = $this->getVars();
        $ownerGroup  = $modvariable ['shopOwnerGroup'];

        // exit;
        // echo "<pre>"; print_r($owners); echo "</pre>"; exit;

        /*
         * $owners = ModUtil::apiFunc('ZSELEX', 'user', 'selectJoinArray', $args = array(
         * 'table' => 'group_membership a',
         * 'where' => array(
         * "a.gid=$ownerGroup"
         * ),
         * 'joins' => array(
         * "INNER JOIN users b ON a.uid=b.uid",
         * "LEFT JOIN zselex_shop_owners c ON c.user_id=b.uid",
         * "LEFT JOIN zselex_shop d ON d.shop_id=c.shop_id"
         * ),
         * 'groupby' => 'b.uid'
         * ));
         */

        $searchQuery = '';
        $startnum    = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : 0, 'GETPOST');
        if ($startnum > 0) {
            $startnum = $startnum - 1;
        }
        if ($startnum == '') {
            $startnum = 0;
        }
        // echo "start : " . $startnum . '<br>';
        $itemsperpage = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 25,
                'GETPOST');
        $this->view->assign('itemsperpage', $itemsperpage);
        $searchtext   = FormUtil::getPassedValue('searchtext',
                isset($args ['searchtext']) ? $args ['searchtext'] : null,
                'GETPOST');
        $this->view->assign('searchtext', $searchtext);
        if (!empty($searchtext)) {
            $searchQuery = " AND b.uname LIKE '%".DataUtil::formatForStore($searchtext)."%' ";
        }
        // echo $searchQuery;
        $owners = $repo->getExistingOwners(array(
            'group_id' => $ownerGroup,
            'startnum' => $startnum,
            'itemsperpage' => $itemsperpage,
            'append' => $searchQuery
        ));
        // echo "<pre>"; print_r($owners1); echo "</pre>";
        // echo "<pre>"; print_r($owners); echo "</pre>";
        /*
         * $owners = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray', $args = array(
         * 'table' => 'zselex_shop_owners a , users b',
         * 'where' => array(
         * "a.user_id=b.uid"
         * ),
         * 'groupby' => 'b.uid'
         * ));
         */

        $total_count = $repo->getExistingOwnersCount(array(
            'group_id' => $ownerGroup,
            'append' => $searchQuery
        ));

        // $total_count = 2;

        foreach ($owners as $key => $val) {
            $uid = $val ['uid'];
            $sid = $val ['shop_id'];
            /*
             * $shops = ModUtil::apiFunc('ZSELEX', 'user', 'selectJoinArray', $args = array(
             * 'table' => 'zselex_shop s',
             * 'fields' => array(
             * 's.shop_name',
             * 'm.shoptype',
             * 'c.city_name'
             * ),
             * 'joins' => array(
             * "INNER JOIN zselex_shop_owners owr ON s.shop_id=owr.shop_id AND owr.user_id=$uid",
             * "LEFT JOIN zselex_minishop m ON m.shop_id=s.shop_id",
             * "LEFT JOIN zselex_city c ON c.city_id=s.city_id"
             * )
             * ));
             */

            $shops = $repo->getAll(array(
                'entity' => 'ZSELEX_Entity_Shop',
                'joins' => array(
                    'JOIN a.shop_owners owr',
                    'LEFT JOIN a.minishops m',
                    'LEFT JOIN a.city c'
                ),
                'fields' => array(
                    'a.shop_name',
                    'm.shoptype',
                    'c.city_name'
                ),
                'where' => array(
                    'owr.user_id' => $uid
                ),
                'groupby' => 'a.shop_id'
            ));

            // echo "<pre>"; print_r($shops); echo "</pre>";
            $owners [$key] ['shops'] = $shops;
        }

        // echo "<pre>"; print_r($owners); echo "</pre>";

        $this->view->assign('owners', $owners);
        $this->view->assign('total_count', $total_count);

        return $this->view->fetch('admin/Users/existingowner.tpl');
    }

    public function assignCoOwner($args)
    {
        $admins  = $args ['admins'];
        $shop_id = $args ['shop_id'];
        $repo    = $this->entityManager->getRepository('ZSELEX_Entity_ShopOwner');

        $modvars = ModUtil::getVar('ZSELEX');

        // echo "<pre>"; print_r($admins); echo "</pre>"; exit;
        // echo "<pre>"; print_r($modvars); echo "</pre>"; exit;
        $ownerGroupId = $modvars ['shopOwnerGroup'];

        $insertIntoGroup = $repo->insertAdminToOwnerGroup(array(
            'group_id' => $ownerGroupId,
            'user_id' => $admin
        ));

        foreach ($admins as $key => $admin) {
            $insertIntoGroup = $repo->insertAdminToOwnerGroup(array(
                'group_id' => $ownerGroupId,
                'user_id' => $admin
            ));
            $count           = $repo->getCount(null, 'ZSELEX_Entity_ShopOwner',
                'id',
                array(
                'a.shop' => $shop_id,
                'a.user_id' => $admin
            ));
            // echo $count; exit;
            if ($count < 1) {
                $item          = array(
                    'user_id' => $admin,
                    'shop_id' => $shop_id
                );
                $args          = array(
                    'table' => 'zselex_shop_owners',
                    'element' => $item,
                    'Id' => 'id'
                );
                // $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $args);
                $createCoOwner = new ZSELEX_Entity_ShopOwner ();
                $createCoOwner->setUser_id($admin);
                $createCoOwner->setShop($this->entityManager->find('ZSELEX_Entity_Shop',
                        $shop_id));
                $createCoOwner->setMain(0);
                $createCoOwner->setCo_owner(1);
                $this->entityManager->persist($createCoOwner);
                $this->entityManager->flush();
                LogUtil::registerStatus($this->__('Done! Owner has been assigned successfully.'));
            } else {
                LogUtil::registerError($this->__f('Error! %s already exists as owner for this shop!<br>',
                        $key));
            }
        }

        $this->redirect(ModUtil::url('ZSELEX', 'adminusers', 'view',
                array(
                'shop_id' => $shop_id
        )));
    }

    public function existingAdmin($args)
    {
        // echo "helloooo";
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        if (!(int) ($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)));
        }
        $loguser = UserUtil::getVar('uid');
        $repo    = $this->entityManager->getRepository('ZSELEX_Entity_ShopAdmin');

        if ($_POST) {
            // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;

            if ($_POST ['submit'] == 'Filter') {

            } else {
                $assign         = FormUtil::getPassedValue('assign', null,
                        'REQUEST');
                // echo $assign; exit;
                $selectedAdmins = FormUtil::getPassedValue('shopadmins', null,
                        'POST');
                // echo "<pre>"; print_r($selectedAdmins); echo "</pre>"; exit;
                if (!empty($selectedAdmins)) {
                    if ($assign == 'coowner') {
                        return $this->assignCoOwner(array(
                                'admins' => $selectedAdmins,
                                'shop_id' => $shop_id
                        ));
                    }
                    foreach ($selectedAdmins as $key => $admin) {
                        // echo $key . $admin; exit;
                        $count = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount',
                                $args  = array(
                                'table' => 'zselex_shop_admins',
                                'where' => "shop_id=$shop_id AND user_id=$admin"
                        ));

                        // echo $count; exit;

                        if ($count < 1) {
                            $item   = array(
                                'user_id' => $admin,
                                'shop_id' => $shop_id,
                                'owner_id' => $loguser
                            );
                            $args   = array(
                                'table' => 'zselex_shop_admins',
                                'element' => $item,
                                'Id' => 'admin_id'
                            );
                            $result = ModUtil::apiFunc('ZSELEX', 'admin',
                                    'createElement', $args);
                            LogUtil::registerStatus($this->__('Done! Admin has been assigned successfully.'));
                        } else {
                            LogUtil::registerError($this->__f('Error! %s already exists as admin for this shop!<br>',
                                    $key));
                        }
                    }
                }

                $this->redirect(ModUtil::url('ZSELEX', 'adminusers', 'view',
                        array(
                        'shop_id' => $shop_id
                )));
            }
        }

        $this->view->assign('shop_id', $shop_id);

        // echo $shop_id; exit;
        // $ownerAdmin = '';
        $append = '';
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN) && SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD)) {
            $ownerAdmin = "a.owner_id=$loguser";
            $append .= " AND a.owner_id=$loguser";
        }
        $admins = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray',
                $args   = array(
                'table' => 'zselex_shop_admins a ,  users b',
                'where' => array(
                    'a.user_id=b.uid',
                    $ownerAdmin
                ),
                'groupby' => 'b.uid'
        ));

        $searchQuery = '';
        $startnum    = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : 0, 'GETPOST');
        if ($startnum > 0) {
            $startnum = $startnum - 1;
        }
        if ($startnum == '') {
            $startnum = 0;
        }
        // echo "start : " . $startnum . '<br>';
        $itemsperpage = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 25,
                'GETPOST');
        $this->view->assign('itemsperpage', $itemsperpage);
        $searchtext   = FormUtil::getPassedValue('searchtext',
                isset($args ['searchtext']) ? $args ['searchtext'] : null,
                'GETPOST');
        $this->view->assign('searchtext', $searchtext);
        if (!empty($searchtext)) {
            $append .= " AND b.uname LIKE '%".DataUtil::formatForStore($searchtext)."%' ";
        }

        $admins = $repo->getExistingAdmins(array(
            'startnum' => $startnum,
            'itemsperpage' => $itemsperpage,
            'searchtext' => $searchtext
        ));

        // echo "<pre>"; print_r($admins); echo "</pre>"; exit;

        foreach ($admins as $key => $val) {
            $uid = $val ['user_id'];
            $sid = $val ['shop_id'];

            /*
             * $shops = ModUtil::apiFunc('ZSELEX', 'user', 'selectJoinArray', $args = array(
             * 'table' => 'zselex_shop s',
             * 'fields' => array(
             * 's.shop_name',
             * 'm.shoptype'
             * ),
             * 'joins' => array(
             * "INNER JOIN zselex_shop_admins adm ON s.shop_id=adm.shop_id AND adm.user_id=$uid",
             * "LEFT JOIN zselex_minishop m ON m.shop_id=s.shop_id"
             * )
             * ));
             */

            $shops                   = $repo->getAll(array(
                'entity' => 'ZSELEX_Entity_Shop',
                'joins' => array(
                    'JOIN a.shop_admins adm',
                    'LEFT JOIN a.minishops m',
                    'LEFT JOIN a.city c'
                ),
                'fields' => array(
                    'a.shop_name',
                    'm.shoptype'
                ),
                'where' => array(
                    'adm.user_id' => $uid
                ),
                'groupby' => 'a.shop_id'
            ));
            $admins [$key] ['shops'] = $shops;
        }

        // echo "<pre>"; print_r($admins); echo "</pre>"; exit;

        $total_count = $repo->getExistingAdminsCount(array(
            'startnum' => $startnum,
            'itemsperpage' => $itemsperpage,
            'searchtext' => $searchtext
        ));

        // echo $total_count; exit;

        $this->view->assign('admins', $admins);
        $this->view->assign('total_count', $total_count);

        return $this->view->fetch('admin/Users/existingadmin.tpl');
    }

    public function existingAdminAll($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $loguser = UserUtil::getVar('uid');
        $shop_id = FormUtil::getPassedValue('shop_id', 0, 'REQUEST');

        $repo = $this->entityManager->getRepository('ZSELEX_Entity_ShopAdmin');
        if ($_POST) {
            // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
            if ($_POST ['submit'] == 'Filter') {
                
            } else {
                $selectedAdmins = FormUtil::getPassedValue('shopadmins', null,
                        'POST');
                // echo "<pre>"; print_r($selectedAdmins); echo "</pre>"; exit;
                if (!empty($selectedAdmins)) {
                    foreach ($selectedAdmins as $key => $admin) {
                        // echo $key . $admin; exit;
                        $count = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount',
                                $args  = array(
                                'table' => 'zselex_shop_admins',
                                'where' => "shop_id=$shop_id AND user_id=$admin"
                        ));

                        // echo $count; exit;

                        if ($count < 1) {
                            $item   = array(
                                'user_id' => $admin,
                                'shop_id' => $shop_id,
                                'owner_id' => $loguser
                            );
                            $args   = array(
                                'table' => 'zselex_shop_admins',
                                'element' => $item,
                                'Id' => 'admin_id'
                            );
                            $result = ModUtil::apiFunc('ZSELEX', 'admin',
                                    'createElement', $args);
                            LogUtil::registerStatus($this->__f('Done! Admin %s has been assigned successfully.',
                                    $key));
                        } else {
                            LogUtil::registerError($this->__f('Error! %s already exists as admin for this shop!<br>',
                                    $key));
                        }
                    }
                }

                $this->redirect(ModUtil::url('ZSELEX', 'adminusers', 'view',
                        array(
                        'shop_id' => $shop_id
                )));
            }
        }

        $this->view->assign('shop_id', $shop_id);

        // echo $shop_id;
        // $ownerAdmin = '';
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN) && SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD)) {
            $ownerAdmin = "a.owner_id=$loguser";
        }
        $modvars = ModUtil::getVar('ZSELEX');
        /*
         * $admins = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray', $args = array(
         * 'table' => 'group_membership g, users b',
         * 'where' => array(
         * "g.uid=b.uid",
         * "g.gid=" . $modvars['shopAdminGroup'],
         * $ownerAdmin
         * ),
         * 'groupby' => 'b.uid'
         * ));
         */

        $searchQuery = '';
        $startnum    = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : 0, 'GETPOST');
        if ($startnum > 0) {
            $startnum = $startnum - 1;
        }
        if ($startnum == '') {
            $startnum = 0;
        }
        // echo "start : " . $startnum . '<br>';
        $itemsperpage = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 25,
                'GETPOST');
        $this->view->assign('itemsperpage', $itemsperpage);
        $searchtext   = FormUtil::getPassedValue('searchtext',
                isset($args ['searchtext']) ? $args ['searchtext'] : null,
                'GETPOST');
        $this->view->assign('searchtext', $searchtext);
        if (!empty($searchtext)) {
            $append .= " AND b.uname LIKE '%".DataUtil::formatForStore($searchtext)."%' ";
        }

        $admins = $repo->getExistingAllAdmins(array(
            'adminGroup' => $modvars ['shopAdminGroup'],
            'startnum' => $startnum,
            'itemsperpage' => $itemsperpage,
            'append' => $append
        ));

        // echo "<pre>"; print_r($admins); echo "</pre>";

        foreach ($admins as $key => $val) {
            $uid = $val ['uid'];

            $shops = array();
            // echo "<pre>uid="; echo $uid; echo "</pre>";
            /*
             * $inanyshop = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray', $args = array(
             * 'table' => 'zselex_shop_admins adm',
             * 'where' => array(
             * "adm.user_id=" . $uid
             * )
             * ));
             */

            $inanyshop = $repo->getCount(null, 'ZSELEX_Entity_ShopAdmin',
                'admin_id',
                array(
                'a.user_id' => $uid
            ));
            if (count($inanyshop) > 0) {
                /*
                 * $shops = ModUtil::apiFunc('ZSELEX', 'user', 'selectJoinArray', $args = array(
                 * 'table' => 'zselex_shop s',
                 * 'fields' => array(
                 * 's.shop_name',
                 * 'm.shoptype'
                 * ),
                 * 'joins' => array(
                 * "INNER JOIN zselex_shop_admins adm ON s.shop_id=adm.shop_id AND adm.user_id=$uid",
                 * "LEFT JOIN zselex_minishop m ON m.shop_id=s.shop_id"
                 * )
                 * ));
                 */

                $shops = $repo->getAll(array(
                    'entity' => 'ZSELEX_Entity_Shop',
                    'joins' => array(
                        'JOIN a.shop_admins adm',
                        'LEFT JOIN a.minishops m',
                        'LEFT JOIN a.city c'
                    ),
                    'fields' => array(
                        'a.shop_name',
                        'm.shoptype'
                    ),
                    'where' => array(
                        'adm.user_id' => $uid
                    ),
                    'groupby' => 'a.shop_id'
                ));
            }
            $admins [$key] ['shops'] = $shops;
        }

        $total_count = $repo->getExistingAllAdminsCount(array(
            'adminGroup' => $modvars ['shopAdminGroup'],
            'append' => $append
        ));

        $this->view->assign('admins', $admins);
        $this->view->assign('total_count', $total_count);

        return $this->view->fetch('admin/Users/existingadmin.tpl');
    }

    public function ownertheme($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());

        $loguser = UserUtil::getVar('uid');

        if ($_POST) {

            // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
            $shopsowner = FormUtil::getPassedValue('shopsowner', null, 'POST');
            // echo "<pre>"; print_r($selectedAdmins); echo "</pre>"; exit;
            if (!empty($shopsowner)) {
                foreach ($shopsowner as $key => $val) {
                    if (!empty($val)) {
                        $explode = explode('+', $key);

                        $theme_id   = $explode [0];
                        $theme_name = $explode [1];

                        $explodeuser = explode('+', $val);

                        // echo $explode[0] . '<br>';
                        $user_id  = $explodeuser [0];
                        $userName = $explodeuser [1];
                        // echo $user_id . '<br>';
                        // echo $key . $admin; exit;

                        $count = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount',
                                $args  = array(
                                'table' => 'zselex_shop_owners_theme',
                                'where' => "theme_id=$theme_id AND user_id=$user_id"
                        ));

                        // echo $count;

                        if ($count < 1) {
                            $item   = array(
                                'user_id' => $user_id,
                                'theme_id' => $theme_id,
                                'theme_name' => $theme_name
                            );
                            $args   = array(
                                'table' => 'zselex_shop_owners_theme',
                                'element' => $item,
                                'Id' => 'id'
                            );
                            $result = ModUtil::apiFunc('ZSELEX', 'admin',
                                    'createElement', $args);
                            LogUtil::registerStatus($this->__f('Done! The Theme %1$s was assigned successfully to %2$s!',
                                    array(
                                    $theme_name,
                                    $userName
                            )));
                        } else {
                            LogUtil::registerError($this->__f('Error! The Theme %1$s already exists for %2$s!<br>',
                                    array(
                                    $theme_name,
                                    $userName
                            )));
                        }
                    }
                }
            }

            $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewownertheme'));
        }

        $this->view->assign('shop_id', $shop_id);

        // echo $shop_id;
        // $ownerAdmin = '';
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN) && SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD)) {
            $ownerAdmin = "a.owner_id=$loguser";
        }

        $themes = DBUtil::selectObjectArray('themes');

        $shopOwners = ModUtil::apiFunc('ZSELEX', 'user', 'selectJoinArray',
                $args       = array(
                'table' => 'zselex_shop_owners s',
                'fields' => array(
                    'u.uname',
                    's.user_id'
                ),
                'joins' => array(
                    'INNER JOIN  users u ON u.uid=s.user_id'
                ),
                'groupby' => 's.user_id'
        ));

        // echo "<pre>"; print_r($shopOwners); echo "</pre>"; exit;
        /*
         * foreach ($themes as $key => $val) {
         * $uid = $val['user_id'];
         * $sid = $val['shop_id'];
         * $shops = ModUtil::apiFunc('ZSELEX', 'user', 'selectJoinArray', $args =
         * array('table' => 'zselex_shop s',
         * 'fields' => array('s.shop_name', 'm.shoptype'),
         * 'joins' => array("INNER JOIN zselex_shop_admins adm ON s.shop_id=adm.shop_id AND adm.user_id=$uid", "LEFT JOIN zselex_minishop m ON m.shop_id=s.shop_id")));
         * $admins[$key]['shops'] = $shops;
         * }
         */
        $this->view->assign('themes', $themes);
        $this->view->assign('shopowners', $shopOwners);

        return $this->view->fetch('admin/ownertheme.tpl');
    }

    public function viewownertheme()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        // initialize sort array - used to display sort classes and urls
        $itemsperpage = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 15,
                'GETPOST');
        $startnum     = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');

        $sql     = ' SELECT * FROM zselex_shop_owners_theme a ,  users b where a.user_id=b.uid';
        // Get all zselex stories
        $getArgs = array(
            'sql' => $sql,
            'startnum' => $startnum,
            'numitems' => $itemsperpage
        );

        $items = ModUtil::apiFunc('ZSELEX', 'admin', 'getAll', $getArgs);

        // echo "<pre>"; print_r($items); echo "</pre>"; exit;

        $this->view->assign('items', $items);

        return $this->view->fetch('admin/viewownertheme.tpl');
    }

    public function removeOwnerTheme($args)
    {
        $id = $this->request->getGet()->get('id', '');
        // echo $id; exit;

        $where = "WHERE id=$id";
        if (DBUtil::deleteWhere('zselex_shop_owners_theme', $where)) {
            LogUtil::registerStatus($this->__('Done! Admin has been removed successfully.'));
            $this->redirect(ModUtil::url($this->name, 'admin', 'viewownertheme'));
        }
    }

    public function paymentgateway1()
    {
        $shop_id  = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $loguser  = UserUtil::getVar('uid');
        $user_id  = $loguser;
        $template = 'paymentgateway.tpl';
        $error    = 0;
        $disable  = 0;

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $_SESSION ['pperror'] = '0';

        $admin = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN);
        $this->view->assign('admin', $admin);

        $disabled = 'no';

        $serviceargs       = array(
            'shop_id' => $shop_id,
            'user_id' => $user_id,
            'type' => 'paybutton'
        );
        $servicePermission = $this->servicePermission($serviceargs);

        if ($servicePermission ['perm'] < 1) {
            LogUtil::registerError($this->__('The service you try to use has to be purchased first.'));
            ++$error;
        }
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            if ($this->serviceDisabled('paybutton') < 1) {
                // $template = 'paymentgatewaydisabled.tpl';
                ++$error;
                ++$disable;
                $servicedisable = true;
                $message        = $this->__('This service is currently disabled');
                LogUtil::registerError(nl2br($message));
            }
            $expired = $servicePermission ['expired'];
        }
        $this->view->assign('disable', $disable);
        $this->view->assign('error', $error);
        $this->view->assign('servicedisable', $servicedisable);
        $this->view->assign('expired', $expired);

        return $this->view->fetch('admin/paymentgateway/'.$template);
    }

    public function paypalConfig()
    {
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $user_id = UserUtil::getVar('uid');

        // echo $user_id; exit;

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $serviceargs       = array(
            'shop_id' => $shop_id,
            'user_id' => $user_id,
            'type' => 'paybutton'
        );
        $servicePermission = $this->servicePermission($serviceargs);

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            if ($servicePermission ['perm'] < 1) {
                return LogUtil::registerError($servicePermission ['message']);
            }
        }

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            if ($this->serviceDisabled('paybutton') < 1) {
                return LogUtil::registerError($this->__('This service has been temporarily disabled!'));
            }
        }
        // echo $_SESSION['pperror'];
        // echo $shop_id; exit;
        $sess_item = SessionUtil::getVar('ppitem');

        $this->view->assign('pperror', $_SESSION ['pperror']);
        $this->view->assign($sess_item);
        // echo "</pre>"; print_r($sess_item); echo "</pre>";

        $args = array(
            'table' => 'zselex_paypal',
            'IdValue' => $shop_id,
            'IdName' => 'shop_id'
        );
        // Get the news type in the db
        $item = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $args);

        // echo "</pre>"; print_r($item); echo "</pre>"; exit;

        if ($_POST) {
            $paypalInfo = FormUtil::getPassedValue('paypalinfo',
                    isset($args ['paypalinfo']) ? $args ['paypalinfo'] : null,
                    'POST');
            // echo "<pre>"; print_r($paypalInfo); echo "</pre>"; exit;

            $item = array(
                'ppemail' => $paypalInfo ['pemail']
            );

            $validationerror = ZSELEX_Util::validatePayPalOwner($item);

            if ($validationerror !== false) {
                // log the error found if any
                if ($validationerror !== false) {
                    LogUtil::registerError(nl2br($validationerror));
                }
                SessionUtil::setVar('ppitem', $item);
                $_SESSION ['pperror'] = '1';
                $this->view->assign($_SESSION ['pperror']);

                $this->redirect(ModUtil::url('ZSELEX', 'admin', 'paypalConfig',
                        array(
                        'shop_id' => $shop_id
                )));
            } else {
                // As we're not previewing the item let's remove it from the session
                SessionUtil::delVar('ppitem');
                $_SESSION ['pperror'] = '0';
            }

            if (empty($paypalInfo ['paypalId'])) { // INSERT
                $obj = array(
                    'paypal_email' => $paypalInfo ['pemail'],
                    'user_id' => $user_id,
                    'shop_id' => $shop_id
                );
                // echo "</pre>"; print_r($obj); echo "</pre>"; exit;

                $result = DBUtil::insertObject($obj, 'zselex_paypal');
            } else {
                $args   = array(
                    'table' => 'zselex_paypal',
                    'items' => array(
                        'paypal_email' => $paypalInfo ['pemail']
                    ),
                    'where' => array(
                        'id' => $paypalInfo ['paypalId']
                    )
                );
                $result = ModUtil::apiFunc('ZSELEX', 'admin',
                        'updateElementWhere', $args);
            }

            if ($result) {
                $this->redirect(ModUtil::url('ZSELEX', 'admin',
                        'paymentgateway',
                        array(
                        'shop_id' => $shop_id
                )));
            }
        }

        $this->view->assign('item', $item);

        return $this->view->fetch('admin/paypalconfig.tpl');
    }

    public function viewzselextheme($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        // initialize sort array - used to display sort classes and urls
        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Category');

        $themIds = array();
        /*
         * if ($_POST['submit'] == 'configure') {
         *
         * if (count($_POST['zselextheme']) > 0) {
         * // echo "<pre>"; print_r($_POST['zselextheme']); echo "</pre>"; exit;
         *
         * foreach ($_POST['zselextheme'] as $theme_id => $theme_name) {
         * $sql = "DELETE FROM zselex_themes WHERE theme_id!=$theme_id";
         * $statement = Doctrine_Manager::getInstance()->connection();
         * $results = $statement->execute($sql);
         * }
         *
         *
         * foreach ($_POST['zselextheme'] as $theme_id => $theme_name) {
         * $sql = "INSERT INTO zselex_themes(theme_id , theme_name)VALUES('" . $theme_id . "' , '" . $theme_name . "')";
         * $statement = Doctrine_Manager::getInstance()->connection();
         * $results = $statement->execute($sql);
         * }
         * } else {
         *
         * $sql = "DELETE FROM zselex_themes";
         * $statement = Doctrine_Manager::getInstance()->connection();
         * $results = $statement->execute($sql);
         * }
         * }
         */

        $sort   = array();
        $fields = array(
            'id',
            'name'
        ); // possible sort fields

        $itemsperpage  = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 25,
                'GETPOST');
        $startnum      = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $status        = FormUtil::getPassedValue('status',
                isset($args ['status']) ? $args ['status'] : null, 'GETPOST');
        $order         = FormUtil::getPassedValue('order',
                isset($args ['order']) ? $args ['order'] : 'zt_id', 'GETPOST');
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
                    'viewzselextheme',
                    array(
                    'status' => $status,
                    'filtercats_serialized' => serialize($filtercats),
                    'order' => $field,
                    'sdir' => $sdir
            ));
        }
        $this->view->assign('sort', $sort);

        $this->view->assign('filter_active', $status);

        // $sql = " SELECT a.* FROM zselex_type AS a
        // WHERE a.type_id IS NOT NULL ";

        $sql = '';
        if (isset($status) && $status != '') {
            $sql .= ' AND a.status = '.$status;
        }
        if (isset($searchtext) && $searchtext != '') {
            $sql .= " AND a.theme_name LIKE '%".DataUtil::formatForStore($searchtext)."%'";
        }
        if (isset($order) && $order != '') {
            $sql .= ' ORDER BY a.'.$order.' '.$orderdir;
        }

        // Get all zselex stories
        $getArgs = array(
            'sql' => $sql,
            'startnum' => $startnum,
            'numitems' => $itemsperpage
        );
        // $items = ModUtil::apiFunc('ZSELEX', 'admin', 'getAll', $getArgs);

        $themes = ModUtil::apiFunc('ZSELEX', 'admin', 'getZselexThemes',
                $args   = array(
                'start' => $startnum,
                'itemsperpage' => $itemsperpage,
                'sql' => $sql
        ));

        // echo "<pre>"; print_r($result); echo "</pre>";

        $items = $themes ['result'];

        // echo "<pre>"; print_r($items); echo "</pre>";

        /*
         * $total_types = ModUtil::apiFunc('ZSELEX', 'admin', 'getZselexThemesCount', $args = array(
         * 'sql' => $sql
         * ));
         */

        $total_types = $themes ['count'];
        // echo $total_types;
        // Set the possible status for later use
        $itemstatus  = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );

        $typesitems = array();
        foreach ($items as $item) {
            $options = array();

            // $options[] = array('url' => ModUtil::url('ZSELEX', 'admin', 'displaytype', array('type_id' => $item['type_id'])),
            // 'image' => '14_layer_visible.png',
            // 'title' => $this->__('View'));

            if (SecurityUtil::checkPermission('ZSELEX::',
                    "{$item['cr_uid']}::{$item['type_id']}", ACCESS_EDIT)) {
                $options [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'modifytype',
                        array(
                        'type_id' => $item ['type_id']
                    )),
                    'image' => 'xedit.png',
                    'title' => $this->__('Edit')
                );

                if ((SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['type_id']}", ACCESS_DELETE))
                    || SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['type_id']}", ACCESS_ADMIN)) {
                    $options [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin', 'deletetype',
                            array(
                            'type_id' => $item ['type_id']
                        )),
                        'image' => '14_layer_deletelayer.png',
                        'title' => $this->__('Delete')
                    );
                }
            }
            $item ['options'] = $options;

            $item ['infuture'] = DateUtil::getDatetimeDiff_AsField($item ['cr_date'],
                    DateUtil::getDatetime(), 6) < 0;
            $typesitems []     = $item;
        }
        $zThemes = DBUtil::selectObjectArray('zselex_themes');

        foreach ($zThemes as $theme) {
            $zselexTheme [] = $theme ['theme_id'];
        }

        // echo "<pre>"; print_r($typesitems); echo "</pre>";
        // echo "<pre>"; print_r($ownerTheme); echo "</pre>";
        // Assign the items to the template
        $this->view->assign('typesitems', $typesitems);
        $this->view->assign('zselexTheme', $zselexTheme);

        $this->view->assign('total_types', $total_types);

        // Assign the current status filter and the possible ones
        $this->view->assign('status', $status);
        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('order', $order);
        // Return the output that has been generated by this function
        return $this->view->fetch('admin/viewzselextheme.tpl');
    }

    public function configurethemezselex($args)
    {

        // echo "comes here"; exit;
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        // initialize sort array - used to display sort classes and urls

        $themIds = array();
        if ($_POST ['submit'] == 'configure') {

            /*
             * $t = $_POST['zselextheme'];
             * $xp = explode('|', $t);
             * //echo "<pre>"; print_r($xp); echo "</pre>";
             * $theme_id = $xp['1'];
             * $theme_name = $xp['0'];
             *
             * $item = array('theme_id' => $theme_id, 'theme_name' => $theme_name);
             * $args = array('table' => 'zselex_themes', 'element' => $item, 'Id' => 'zt_id');
             * // Create the zselex type
             * $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $args);
             *
             */
            // echo "<pre>"; print_r($_POST['zselextheme']); echo "</pre>"; exit;
            $zselextheme = FormUtil::getPassedValue('zselextheme', null, 'POST');
            if (count($zselextheme) > 0) {
                foreach ($zselextheme as $theme_id => $theme_name) {
                    /*
                     * $item = array(
                     * 'theme_id' => $theme_id,
                     * 'theme_name' => $theme_name
                     * );
                     * $args = array(
                     * 'table' => 'zselex_themes',
                     * 'element' => $item,
                     * 'Id' => 'zt_id'
                     * );
                     * $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $args);
                     */
                    $theme = new ZSELEX_Entity_ZselexTheme ();
                    $theme->setTheme_id($theme_id);
                    $theme->setTheme_name($theme_name);
                    $this->entityManager->persist($theme);
                }
                $this->entityManager->flush();
            }
        }

        $sort   = array();
        $fields = array(
            'id',
            'name'
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
                    'configurethemezselex',
                    array(
                    'status' => $status,
                    'filtercats_serialized' => serialize($filtercats),
                    'order' => $field,
                    'sdir' => $sdir
            ));
        }
        $this->view->assign('sort', $sort);

        $this->view->assign('filter_active', $status);

        // $sql = " SELECT a.* FROM zselex_type AS a
        // WHERE a.type_id IS NOT NULL ";

        $sql = '';
        if (isset($status) && $status != '') {
            $sql .= ' AND t.status = '.$status;
        }
        if (isset($searchtext) && $searchtext != '') {
            $sql .= " AND t.name LIKE '%".DataUtil::formatForStore($searchtext)."%'";
        }
        if (isset($order) && $order != '') {
            $sql .= ' ORDER BY t.'.$order.' '.$orderdir;
        }

        // Get all zselex stories
        $getArgs = array(
            'sql' => $sql,
            'startnum' => $startnum,
            'numitems' => $itemsperpage
        );
        // $items = ModUtil::apiFunc('ZSELEX', 'admin', 'getAll', $getArgs);

        $items = ModUtil::apiFunc('ZSELEX', 'admin',
                'getThemesToConfigureToZselex',
                $args  = array(
                'start' => $startnum,
                'itemsperpage' => $itemsperpage,
                'sql' => $sql
        ));

        $total_types = ModUtil::apiFunc('ZSELEX', 'admin',
                'getThemesToConfigureToZselexCount',
                $args        = array(
                'sql' => $sql
        ));

        // Set the possible status for later use
        $itemstatus = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );

        $typesitems = array();
        foreach ($items as $item) {
            $options = array();

            // $options[] = array('url' => ModUtil::url('ZSELEX', 'admin', 'displaytype', array('type_id' => $item['type_id'])),
            // 'image' => '14_layer_visible.png',
            // 'title' => $this->__('View'));

            if (SecurityUtil::checkPermission('ZSELEX::',
                    "{$item['cr_uid']}::{$item['type_id']}", ACCESS_EDIT)) {
                $options [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'modifytype',
                        array(
                        'type_id' => $item ['type_id']
                    )),
                    'image' => 'xedit.png',
                    'title' => $this->__('Edit')
                );

                if ((SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['type_id']}", ACCESS_DELETE))
                    || SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['type_id']}", ACCESS_ADMIN)) {
                    $options [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin', 'deletetype',
                            array(
                            'type_id' => $item ['type_id']
                        )),
                        'image' => '14_layer_deletelayer.png',
                        'title' => $this->__('Delete')
                    );
                }
            }
            $item ['options'] = $options;

            $item ['infuture'] = DateUtil::getDatetimeDiff_AsField($item ['cr_date'],
                    DateUtil::getDatetime(), 6) < 0;
            $typesitems []     = $item;
        }
        $zThemes = DBUtil::selectObjectArray('zselex_themes');

        foreach ($zThemes as $theme) {
            $zselexTheme [] = $theme ['theme_id'];
        }

        // echo "<pre>"; print_r($typesitems); echo "</pre>";
        // echo "<pre>"; print_r($ownerTheme); echo "</pre>";
        // Assign the items to the template
        $this->view->assign('typesitems', $typesitems);
        $this->view->assign('zselexTheme', $zselexTheme);

        $this->view->assign('total_types', $total_types);

        // Assign the current status filter and the possible ones
        $this->view->assign('status', $status);
        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('order', $order);
        // Return the output that has been generated by this function
        return $this->view->fetch('admin/viewthemesforzselex.tpl');
    }

    public function removeZselexTheme($args)
    {
        $id     = FormUtil::getPassedValue('id',
                isset($args ['id']) ? $args ['id'] : 0, 'REQUEST');
        $repo   = $this->entityManager->getRepository('ZSELEX_Entity_ZselexTheme');
        // $where = "zt_id=$id";
        $delete = $repo->deleteEntity(null, 'ZSELEX_Entity_ZselexTheme',
            array(
            'a.zt_id' => $id
        ));
        if ($delete) {
            LogUtil::registerStatus($this->__('Done! Successfully removed.'));

            return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                        'viewzselextheme'));
        }
    }

    public function serviceCheck($args)
    {
        $perm = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceCheck', $args);

        return $perm;
    }

    public function createarticlead($args)
    {
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $user_id           = UserUtil::getVar('uid');
        $serviceargs       = array(
            'shop_id' => $shop_id,
            'user_id' => $user_id,
            'type' => 'createarticlead'
        );
        // $servicePermission = $this->serviceCheck($serviceargs);
        $servicePermission = $this->servicePermission($serviceargs);

        if ($servicePermission ['perm'] < 1) {
            return LogUtil::registerError($servicePermission ['message']);
        }

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            if ($this->serviceDisabled('createarticlead') < 1) {
                return LogUtil::registerError($this->__('This service has been temporarily disabled!'));
            }
        }

        $countries = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $args      = array(
                'table' => 'zselex_country',
                'where' => '',
                'orderBy' => 'country_name ASC',
                'useJoins' => ''
        ));
        $regions   = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $args      = array(
                'table' => 'zselex_region',
                'where' => '',
                'orderBy' => 'region_name ASC',
                'useJoins' => ''
        ));
        $cities    = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $args      = array(
                'table' => 'zselex_city',
                'where' => '',
                'orderBy' => 'city_name ASC',
                'useJoins' => ''
        ));
        $areas     = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $args      = array(
                'table' => 'zselex_area',
                'where' => '',
                'orderBy' => 'area_name ASC',
                'useJoins' => ''
        ));

        $adpriceargs = array(
            'table' => 'zselex_advertise_price',
            'where' => '',
            'orderBy' => '',
            'useJoins' => ''
        );

        $aAdPriceArray = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $adpriceargs);
        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('zadprice', $aAdPriceArray);
        $this->view->assign('regions', $regions);
        $this->view->assign('countries', $countries);
        $this->view->assign('cities', $cities);
        $this->view->assign('areas', $areas);

        return $this->view->fetch('admin/articlead/createarticlead.tpl');
    }

    public function submitarticlead($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        $this->checkCsrfToken();
        // Get parameters cr_date whatever input we need
        $formElements = FormUtil::getPassedValue('formElements',
                isset($args ['formElements']) ? $args ['formElements'] : null,
                'POST');
        $formElements = $this->purifyHtml($formElements);
        // $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;

        $shop_id = $formElements ['shop_id'];
        $user_id = UserUtil::getVar('uid');

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $serviceargs       = array(
            'shop_id' => $shop_id,
            'type' => 'createarticlead'
        );
        $servicePermission = $this->servicePermission($serviceargs);

        if ($servicePermission ['perm'] < 1) {
            LogUtil::registerError($servicePermission ['message']);
        }

        if ($formElements ['elemtName']) { // AD
            $item     = array(
                'name' => $formElements ['elemtName'],
                'level' => $formElements ['level'],
                'shop_id' => !empty($formElements ['shop_id']) ? $formElements ['shop_id']
                        : 0,
                'country_id' => !empty($formElements ['country-combo']) ? $formElements ['country-combo']
                        : 0,
                'region_id' => !empty($formElements ['region-combo']) ? $formElements ['region-combo']
                        : 0,
                'city_id' => !empty($formElements ['city-combo']) ? $formElements ['city-combo']
                        : 0,
                'start_date' => !empty($formElements ['startdate']) ? $formElements ['startdate']
                        : 0,
                'end_date' => !empty($formElements ['enddate']) ? $formElements ['enddate']
                        : 0,
                'description' => isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                        : '',
                'keywords' => isset($formElements ['keywords']) ? $formElements ['keywords']
                        : '',
                'status' => isset($formElements ['status']) ? $formElements ['status']
                        : 0
            );
            $keywords = $formElements ['keywords'];

            if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) {
                $args             = array(
                    'table' => 'zselex_article_ads',
                    'element' => $item,
                    'Id' => 'articlead_id'
                );
                // Create the zselex type
                $result           = ModUtil::apiFunc('ZSELEX', 'admin',
                        'createElement', $args);
                $InsertId         = DBUtil::getInsertID($args ['table'],
                        $args ['Id']);
                // if (UserUtil::getVar('uid') != 2) {
                // if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $user             = UserUtil::getVar('uid');
                $serviceupdatearg = array(
                    'user_id' => $user,
                    'type' => 'createarticlead',
                    'shop_id' => $formElements ['shop_id']
                );
                $serviceavailed   = ModUtil::apiFunc('ZSELEX', 'admin',
                        'updateServiceUsed', $serviceupdatearg);
                if ($result) {
                    $keyword_crt_args = array(
                        'keywords' => $keywords,
                        'type' => 'createarticlead',
                        'type_id' => $InsertId,
                        'shop_id' => $formElements ['shop_id']
                    );
                    $this->createkeywords($args             = $keyword_upd_args);
                }
                // }
            } else {
                $InsertId = $formElements ['elemId'];
                // update the type

                $changelevel = $formElements ['changelevels'];
                if ($changelevel == 1) {
                    $item = array(
                        'articlead_id' => $formElements ['elemId'],
                        'name' => $formElements ['elemtName'],
                        'level' => !empty($formElements ['level']) ? $formElements ['level']
                                : $formElements ['levelset'],
                        'shop_id' => !empty($formElements ['shop_id']) ? $formElements ['shop_id']
                                : 0,
                        'country_id' => !empty($formElements ['country-combo']) ? $formElements ['country-combo']
                                : 0,
                        'region_id' => !empty($formElements ['region-combo']) ? $formElements ['region-combo']
                                : 0,
                        'city_id' => !empty($formElements ['city-combo']) ? $formElements ['city-combo']
                                : 0,
                        'start_date' => !empty($formElements ['startdate']) ? $formElements ['startdate']
                                : 0,
                        'end_date' => !empty($formElements ['enddate']) ? $formElements ['enddate']
                                : 0,
                        'description' => isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                                : '',
                        'keywords' => isset($formElements ['keywords']) ? $formElements ['keywords']
                                : '',
                        'status' => isset($formElements ['status']) ? $formElements ['status']
                                : 0
                    );
                } else {
                    $item = array(
                        'articlead_id' => $formElements ['elemId'],
                        'name' => $formElements ['elemtName'],
                        'level' => !empty($formElements ['level']) ? $formElements ['level']
                                : $formElements ['levelset'],
                        'shop_id' => !empty($formElements ['shop_id']) ? $formElements ['shop_id']
                                : 0,
                        'country_id' => !empty($formElements ['country-combo']) ? $formElements ['country-combo']
                                : $formElements ['parentCountry'],
                        'region_id' => !empty($formElements ['region-combo']) ? $formElements ['region-combo']
                                : $formElements ['parentRegion'],
                        'city_id' => !empty($formElements ['city-combo']) ? $formElements ['city-combo']
                                : $formElements ['parentCity'],
                        'start_date' => !empty($formElements ['startdate']) ? $formElements ['startdate']
                                : 0,
                        'end_date' => !empty($formElements ['enddate']) ? $formElements ['enddate']
                                : 0,
                        'description' => isset($formElements ['elemtDesc']) ? $formElements ['elemtDesc']
                                : '',
                        'keywords' => isset($formElements ['keywords']) ? $formElements ['keywords']
                                : '',
                        'status' => isset($formElements ['status']) ? $formElements ['status']
                                : 0
                    );
                }

                // echo "<pre>"; print_r($item); echo "</pre>"; exit;

                $updateargs = array(
                    'table' => 'zselex_article_ads',
                    'IdValue' => $InsertId,
                    'IdName' => 'articlead_id',
                    'element' => $item
                );

                $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement',
                        $updateargs);
                if ($result) {
                    $keyword_upd_args = array(
                        'keywords' => $formElements ['keywords'],
                        'type' => 'createarticlead',
                        'type_id' => $InsertId,
                        'shop_id' => $formElements ['shop_id']
                    );
                    $this->updatekeywords($args             = $keyword_upd_args);
                }
            }
        }
        if ($result != false) {
            if (!isset($formElements ['elemId']) || empty($formElements ['elemId'])) {
                LogUtil::registerStatus($this->__('Done! Article AD has been created successfully.'));
            } else {
                LogUtil::registerStatus($this->__('Done! Article AD has been updated successfully.'));
            }
        } else {
            // fail! type not created
            throw new Zikula_Exception_Fatal($this->__('Story not created for unknown reason (Api failure).'));

            return false;
        }

        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewarticleads',
                array(
                'shop_id' => $formElements ['shop_id']
        )));
    }

    public function deleteArticleAd($args)
    {
        $adid    = FormUtil::getPassedValue('adid',
                isset($args ['adid']) ? $args ['adid'] : null, 'REQUEST');
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $user_id = UserUtil::getVar('uid');

        $serviceEdit = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceCheckEdit',
                $args        = array(
                'shop_id' => $shop_id,
                'item_idValue' => $adid,
                'user_id' => $user_id,
                'servicetable' => 'zselex_article_ads',
                'item_id' => 'articlead_id',
                'type' => 'createarticlead'
        ));

        if ($serviceEdit < 1) {
            return LogUtil::registerError($this->__f('Error! Unable to delete this %s.',
                        $this->__('Article AD')));
        }

        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
        if (empty($confirmation)) {
            // Add ZSELEX type ID
            $this->view->assign('IdValue', $adid);
            $this->view->assign('confirm_title',
                $this->__f('Delete %s', $this->__('Article AD')));
            $this->view->assign('confirm_msg',
                $this->__f('Do you want to delete this %s',
                    $this->__('Article AD')));
            $this->view->assign('shop_id', $shop_id);
            $this->view->assign('IdName', 'adid'); // edit id param name
            $this->view->assign('submitFunc', 'deleteArticleAd');
            $this->view->assign('cancelFunc', 'viewarticleads');

            // Return the output that has been generated by this function
            return $this->view->fetch('admin/deletecommon.tpl');
        }

        $where = "WHERE articlead_id=$adid";
        if (DBUtil::deleteWhere('zselex_article_ads', $where)) {
            $user_id       = UserUtil::getVar('uid');
            $args          = array(
                'shop_id' => $shop_id,
                'servicetype' => 'createarticlead',
                'user_id' => $user_id
            );
            $deleteservice = ModUtil::apiFunc('ZSELEX', 'admin',
                    'deleteService', $args);
            LogUtil::registerStatus($this->__('Done! Article AD has been deleted successfully.'));
            $this->redirect(ModUtil::url($this->name, 'admin', 'viewarticleads',
                    array(
                    'shop_id' => $shop_id
            )));
        }
    }

    public function viewarticleads($args)
    {

        // $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        // initialize sort array - used to display sort classes and urls
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $user_id = UserUtil::getVar('uid');

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $admin          = SecurityUtil::checkPermission('ZSELEX::', '::',
                ACCESS_ADMIN);
        $servicecount   = 0;
        $message        = '';
        $error          = 0;
        $servicedisable = false;
        $this->view->assign('admin', $admin);
        $template       = 'viewarticleads.tpl';
        $minishop       = ModUtil::apiFunc('ZSELEX', 'admin', 'minishopExist',
                $args           = array(
                'shop_id' => $shop_id
        ));

        $serviceargs       = array(
            'shop_id' => FormUtil::getPassedValue('shop_id', null, 'REQUEST'),
            'user_id' => $user_id,
            'type' => 'createarticlead'
        );
        $servicePermission = $this->servicePermission($serviceargs);
        $servicecount += $servicePermission ['perm'];
        // echo "<pre>"; print_r($servicePermission); echo "</pre>";

        if ($servicePermission ['perm'] < 1) {

            // $template = 'viewarticleads_noservice.tpl';
            $message = $servicePermission ['message'];
            ++$error;
            LogUtil::registerError(nl2br($message));
        }

        if ($this->serviceDisabled('createarticlead') < 1) {
            $serviceDisabled = $this->serviceDisabled('createarticlead');
            if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $servicedisable = true;
                ++$error;
                $disable        = 'disabled';
            }

            $message = $this->__('This service is currently disabled');
            LogUtil::registerError(nl2br($message));
        }
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            $expired = $servicePermission ['expired'];
        }

        $this->view->assign('servicecount', $servicecount);
        $this->view->assign('servicedisable', $servicedisable);
        $this->view->assign('expired', $expired);
        $this->view->assign('disable', $disable);
        $this->view->assign('serviceerror', $error);
        $this->view->assign('message', $message);

        // echo $servicecount;

        $sort   = array();
        $fields = array(
            'id',
            'name'
        ); // possible sort fields

        $itemsperpage  = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 15,
                'GETPOST');
        $startnum      = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $status        = FormUtil::getPassedValue('status',
                isset($args ['status']) ? $args ['status'] : null, 'GETPOST');
        $order         = FormUtil::getPassedValue('order',
                isset($args ['order']) ? $args ['order'] : 'articlead_id',
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
                    'viewarticleads',
                    array(
                    'status' => $status,
                    'shop_id' => $shop_id,
                    'filtercats_serialized' => serialize($filtercats),
                    'order' => $field,
                    'sdir' => $sdir
            ));
        }
        $this->view->assign('sort', $sort);

        $this->view->assign('filter_active', $status);

        // $sql = " SELECT a.* FROM zselex_type AS a
        // WHERE a.type_id IS NOT NULL ";

        $sql = '';

        $user_id = UserUtil::getVar('uid');
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN) && SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD)) { // owner
            $sql .= " AND ad.shop_id IN (SELECT shop_id FROM zselex_shop_owners WHERE user_id=$user_id)";
        } elseif (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)
            && !SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD) && SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT)) {
            $sql .= " AND ad.shop_id IN (SELECT shop_id FROM zselex_shop_admins WHERE user_id=$user_id)";
        }

        if (isset($status) && $status != '') {
            $sql .= ' AND ad.status = '.$status;
        }
        if (isset($searchtext) && $searchtext != '') {
            $sql .= " AND ad.name LIKE '%".DataUtil::formatForStore($searchtext)."%'";
        }
        if (isset($order) && $order != '') {
            $sql .= ' ORDER BY ad.'.$order.' '.$orderdir;
        }

        // Get all zselex stories
        $getArgs = array(
            'sql' => $sql,
            'startnum' => $startnum,
            'numitems' => $itemsperpage
        );
        // $items = ModUtil::apiFunc('ZSELEX', 'admin', 'getAll', $getArgs);

        $items = ModUtil::apiFunc('ZSELEX', 'admin', 'getArticleAds',
                $args  = array(
                'start' => $startnum,
                'itemsperpage' => $itemsperpage,
                'sql' => $sql,
                'shop_id' => $shop_id
        ));

        $total_articleads = ModUtil::apiFunc('ZSELEX', 'admin',
                'getArticleAdsCount',
                $args             = array(
                'sql' => $sql,
                'shop_id' => $shop_id
        ));

        // Set the possible status for later use
        $itemstatus = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );

        $advertisesitems = array();
        foreach ($items as $item) {
            $options = array();

            // $options[] = array('url' => ModUtil::url('ZSELEX', 'admin', 'displaytype', array('type_id' => $item['type_id'])),
            // 'image' => '14_layer_visible.png',
            // 'title' => $this->__('View'));

            if (SecurityUtil::checkPermission('ZSELEX::',
                    "{$item['cr_uid']}::{$item['type_id']}", ACCESS_EDIT)) {
                $options [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'modifyarticlead',
                        array(
                        'adid' => $item ['articlead_id'],
                        'shop_id' => $shop_id
                    )),
                    'image' => 'xedit.png',
                    'title' => $this->__('Edit')
                );

                if ((SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['type_id']}", ACCESS_DELETE))
                    || SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['type_id']}", ACCESS_ADMIN)) {
                    $options [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin',
                            'deleteArticleAd',
                            array(
                            'adid' => $item ['articlead_id'],
                            'shop_id' => $shop_id
                        )),
                        'image' => '14_layer_deletelayer.png',
                        'title' => $this->__('Delete')
                    );
                }
            }
            $item ['options'] = $options;

            $item ['infuture']  = DateUtil::getDatetimeDiff_AsField($item ['cr_date'],
                    DateUtil::getDatetime(), 6) < 0;
            $advertisesitems [] = $item;
        }

        // echo "<pre>"; print_r($typesitems); echo "</pre>";
        // echo "<pre>"; print_r($ownerTheme); echo "</pre>";
        // Assign the items to the template

        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('advertisesitems', $advertisesitems);
        $this->view->assign('total_articleads', $total_articleads);

        // Assign the current status filter and the possible ones
        $this->view->assign('status', $status);
        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('order', $order);
        // Return the output that has been generated by this function
        return $this->view->fetch('admin/articlead/'.$template);
    }

    public function modifyarticlead($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());

        // echo "modifycity";
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $user_id = UserUtil::getVar('uid');
        $adId    = FormUtil::getPassedValue('adid',
                isset($args ['adid']) ? $args ['adid'] : null, 'GETPOST');

        if (!(int) ($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)));
        }

        if (!(int) ($adId)) {
            return LogUtil::registerError($this->__f('Error! The AdID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($adId)));
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $serviceEdit = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceCheckEdit',
                $args        = array(
                'shop_id' => $shop_id,
                'item_idValue' => $adId,
                'user_id' => $user_id,
                'servicetable' => 'zselex_article_ads',
                'item_id' => 'articlead_id',
                'type' => 'createarticlead'
        ));

        if ($serviceEdit < 1) {
            // return LogUtil::registerError($this->__f('Error! Unable to edit this %s.', $this->__('Article AD')));
        }

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) { // disabled check
            if ($this->serviceDisabled('createarticlead') < 1) {
                return LogUtil::registerError($this->__('This service has been temporarily disabled!'));
            }
        }

        $countries = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $args      = array(
                'table' => 'zselex_country',
                'where' => '',
                'orderBy' => 'country_name ASC',
                'useJoins' => ''
        ));
        $regions   = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $args      = array(
                'table' => 'zselex_region',
                'where' => '',
                'orderBy' => 'region_name ASC',
                'useJoins' => ''
        ));
        $cities    = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $args      = array(
                'table' => 'zselex_city',
                'where' => '',
                'orderBy' => 'city_name ASC',
                'useJoins' => ''
        ));
        $areas     = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                $args      = array(
                'table' => 'zselex_area',
                'where' => '',
                'orderBy' => 'area_name ASC',
                'useJoins' => ''
        ));

        $item = ModUtil::apiFunc('ZSELEX', 'admin', 'getArticleAdsEdit',
                $args = array(
                'adId' => $adId
        ));

        // echo "<pre>"; print_r($item); echo "</pre>";
        // $parentArgs = array('childType' => 'AD', 'childId' => $item['advertise_id']);
        //
		// $parentArgs['parentTable'] = 'zselex_country';
        // $parentArgs['parentId'] = 'country_id';
        // $parentArgs['parentType'] = 'COUNTRY';
        // $parentcountry = ModUtil::apiFunc('ZSELEX', 'admin', 'getParents', $parentArgs);
        // $item['parentcountry'] = $parentcountry[0]['country_name'];
        // $item['parentcountry_id'] = $parentcountry[0]['country_id'];
        //
		// $parentArgs['parentTable'] = 'zselex_region';
        // $parentArgs['parentId'] = 'region_id';
        // $parentArgs['parentType'] = 'REGION';
        // $parentregion = ModUtil::apiFunc('ZSELEX', 'admin', 'getParents', $parentArgs);
        // $item['parentregion'] = $parentregion[0]['region_name'];
        // $item['parentregion_id'] = $parentregion[0]['region_id'];
        //
		// $parentArgs['parentTable'] = 'zselex_city';
        // $parentArgs['parentId'] = 'city_id';
        // $parentArgs['parentType'] = 'CITY';
        // $parentcity = ModUtil::apiFunc('ZSELEX', 'admin', 'getParents', $parentArgs);
        // $item['parentcity'] = $parentcity[0]['city_name'];
        // $item['parentcity_id'] = $parentcity[0]['city_id'];
        //
		//
		// $parentArgs['parentTable'] = 'zselex_advertise';
        // $parentArgs['parentId'] = 'advertise_id';
        // $parentArgs['parentType'] = 'AD';
        // $parentad = ModUtil::apiFunc('ZSELEX', 'admin', 'getParents', $parentArgs);
        // $item['parentad'] = $parentad[0]['name'];
        // $item['parentadId'] = $parentad[0]['advertise_id'];
        //
		// $parentArgs['parentTable'] = 'zselex_shop';
        // $parentArgs['parentId'] = 'shop_id';
        // $parentArgs['parentType'] = 'SHOP';
        // $parentshop = ModUtil::apiFunc('ZSELEX', 'admin', 'getParents', $parentArgs);
        // $item['parentshop'] = $parentshop[0]['shop_name'];
        // $item['parentshop_id'] = $parentshop[0]['shop_id'];

        $this->view->assign('regions', $regions);
        $this->view->assign('countries', $countries);
        $this->view->assign('cities', $cities);
        $this->view->assign('areas', $areas);

        $this->view->assign('zadprice', $aAdPriceArray);
        $this->view->assign('item', $item);
        $this->view->assign('admins',
            SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN));

        return $this->view->fetch('admin/articlead/modifyarticlead.tpl');
    }

    public function configureshoptheme1($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        // initialize sort array - used to display sort classes and urls

        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        $admin    = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN);
        $this->view->assign('admin', $admin);
        $template = 'viewzthemesforshop.tpl';

        $sort   = array();
        $fields = array(
            'id',
            'name'
        ); // possible sort fields

        $itemsperpage  = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 15,
                'GETPOST');
        $startnum      = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $status        = FormUtil::getPassedValue('status',
                isset($args ['status']) ? $args ['status'] : null, 'GETPOST');
        $order         = FormUtil::getPassedValue('order',
                isset($args ['order']) ? $args ['order'] : 'zt_id', 'GETPOST');
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
                    'viewarticleads',
                    array(
                    'status' => $status,
                    'shop_id' => $shop_id,
                    'filtercats_serialized' => serialize($filtercats),
                    'order' => $field,
                    'sdir' => $sdir
            ));
        }
        $this->view->assign('sort', $sort);

        $this->view->assign('filter_active', $status);

        $sql = '';

        $user_id = UserUtil::getVar('uid');

        if (isset($status) && $status != '') {
            $sql .= ' AND ad.status = '.$status;
        }
        if (isset($searchtext) && $searchtext != '') {
            $sql .= " AND ad.name LIKE '%".DataUtil::formatForStore($searchtext)."%'";
        }
        if (isset($order) && $order != '') {
            $sql .= ' ORDER BY '.$order.' '.$orderdir;
        }

        // Get all zselex stories
        $getArgs = array(
            'sql' => $sql,
            'startnum' => $startnum,
            'numitems' => $itemsperpage
        );
        // $items = ModUtil::apiFunc('ZSELEX', 'admin', 'getAll', $getArgs);

        $items = ModUtil::apiFunc('ZSELEX', 'admin', 'getShopThemes',
                $args  = array(
                'start' => $startnum,
                'itemsperpage' => $itemsperpage,
                'sql' => $sql,
                'shop_id' => $shop_id
        ));

        $total_count = ModUtil::apiFunc('ZSELEX', 'admin', 'getShopThemesCount',
                $args        = array(
                'sql' => $sql,
                'shop_id' => $shop_id
        ));

        // Set the possible status for later use
        $itemstatus = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );

        $advertisesitems = array();
        foreach ($items as $item) {
            $options = array();

            // $options[] = array('url' => ModUtil::url('ZSELEX', 'admin', 'displaytype', array('type_id' => $item['type_id'])),
            // 'image' => '14_layer_visible.png',
            // 'title' => $this->__('View'));

            if (SecurityUtil::checkPermission('ZSELEX::',
                    "{$item['cr_uid']}::{$item['type_id']}", ACCESS_EDIT)) {
                $options [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'modifyarticlead',
                        array(
                        'adid' => $item ['articlead_id'],
                        'shop_id' => $shop_id
                    )),
                    'image' => 'xedit.png',
                    'title' => $this->__('Edit')
                );

                if ((SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['type_id']}", ACCESS_DELETE))
                    || SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['type_id']}", ACCESS_ADMIN)) {
                    $options [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin',
                            'deleteArticleAd',
                            array(
                            'adid' => $item ['articlead_id'],
                            'shop_id' => $shop_id
                        )),
                        'image' => '14_layer_deletelayer.png',
                        'title' => $this->__('Delete')
                    );
                }
            }
            $item ['options'] = $options;

            $item ['infuture']  = DateUtil::getDatetimeDiff_AsField($item ['cr_date'],
                    DateUtil::getDatetime(), 6) < 0;
            $advertisesitems [] = $item;
        }

        // echo "<pre>"; print_r($typesitems); echo "</pre>";
        // echo "<pre>"; print_r($ownerTheme); echo "</pre>";
        // Assign the items to the template

        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('advertisesitems', $advertisesitems);
        $this->view->assign('total_count', $total_count);

        // Assign the current status filter and the possible ones
        $this->view->assign('status', $status);
        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('order', $order);
        // Return the output that has been generated by this function
        return $this->view->fetch('admin/'.$template);
    }

    public function configureshoptheme($args = array())
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        // $this->payMentAlert($shop_id);
        // echo "helloo";
        $repo           = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        $user_id        = UserUtil::getVar('uid');
        $disable        = '';
        $servicecount   = 0;
        $error          = 0;
        $message        = '';
        $servicedisable = false;
        // $minishop = ModUtil::apiFunc('ZSELEX', 'admin', 'minishopExist', $args = array('shop_id' => $shop_id));

        $no_of_pages = FormUtil::getPassedValue('no_of_pages', null, 'REQUEST');
        $this->view->assign('no_of_pages', $no_of_pages);
        $admin       = SecurityUtil::checkPermission('ZSELEX::', '::',
                ACCESS_ADMIN);
        $this->view->assign('admin', $admin);

        $serviceargs       = array(
            'shop_id' => FormUtil::getPassedValue('shop_id', null, 'REQUEST'),
            'user_id' => $user_id,
            'type' => 'stdtheme',
            'quantitybased' => 'no'
        );
        // $servicePermission = $this->serviceCheck($serviceargs);
        $servicePermission = $this->servicePermission($serviceargs);
        // echo "<pre>"; print_r($servicePermission); echo "</pre>";
        $serviceDisabled   = $this->serviceDisabled('stdtheme');
        $servicecount += $servicePermission ['perm'];

        if ($servicePermission ['perm'] < 1) {

            // $template = 'viewshopimages_noservice.tpl';
            // $message = "The service you try to use has to be purchased first.";
            $message = $servicePermission ['message'];
            ++$error;
            LogUtil::registerError(nl2br($message));
        }

        if ($this->serviceDisabled('stdtheme') < 1) {

            // $this->view->assign('disabled', 'yes');
            if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $servicedisable = true;
                $disable        = 'disabled';
            }
            $message = $this->__('This service is currently disabled');
            ++$error;
            LogUtil::registerError(nl2br($message));
        }
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            $expired = $servicePermission ['expired'];
        }

        // echo "error : " . $error;

        $this->view->assign('servicecount', $servicecount);
        $this->view->assign('servicedisable', $servicedisable);
        $this->view->assign('disable', $disable);
        $this->view->assign('expired', $expired);
        $this->view->assign('serviceerror', $error);
        $this->view->assign('message', $message);

        if (isset($this->serviceManager ['multisites.enabled']) && $this->serviceManager ['multisites.enabled']
            == 1) {
            // only the main site can regenerate the themes list
            if ($this->serviceManager ['multisites.mainsiteurl'] == FormUtil::getPassedValue('sitedns',
                    null, 'GET')) {
                // return true but any action has been made
                ModUtil::apiFunc('Theme', 'admin', 'regenerate');
            }
        } else {
            ModUtil::apiFunc('Theme', 'admin', 'regenerate');
        }

        // get our input
        $startnum = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : 1, 'GET');
        $startlet = FormUtil::getPassedValue('startlet',
                isset($args ['startlet']) ? $args ['startlet'] : null, 'GET');

        $this->view->assign('startlet', $startlet);
        // we need this value multiple times, so we keep it
        // $itemsperpage = $this->getVar('itemsperpage');
        if (!empty($no_of_pages)) {
            $itemsperpage = $no_of_pages;
        } else {
            $itemsperpage = '25';
        }

        $allthemes = array();
        // call the API to get a list of all themes in the themes dir
        // $allthemes = ThemeUtil::getAllThemes(ThemeUtil::FILTER_ALL, ThemeUtil::STATE_ALL);

        /*
         * if ($servicePermission > 0 && $error < 1) {
         * $allthemes = ModUtil::apiFunc('ZSELEX', 'admin', 'getShopThemes1', $args = array(
         * 'start' => $startnum,
         * 'itemsperpage' => $itemsperpage,
         * 'sql' => $sql,
         * 'shop_id' => $shop_id
         * ));
         * } else {
         *
         * $allthemes = array();
         * }
         */
        $allthemes   = ModUtil::apiFunc('ZSELEX', 'admin', 'getShopThemes1',
                $args        = array(
                'start' => $startnum,
                'itemsperpage' => $itemsperpage,
                'sql' => $sql,
                'shop_id' => $shop_id
        ));
        // $allthemes = $repo->get(array('entity'=>'ZSELEX_Entity_Shop'));
        // echo "<pre>"; print_r($allthemes); echo "</pre>";
        $ownerThemes = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwnerThemes',
                $args        = array(
                'user_id' => $user_id,
                'shop_id' => $shop_id
        ));

        // echo "<pre>"; print_r($ownerThemes); echo "</pre>";

        $allthemes  = array_merge($allthemes, $ownerThemes);
        // echo "<pre>"; print_r($allthemes); echo "</pre>";
        $allthemess = array();
        foreach ($allthemes as $key => $value) {
            $name               = $value ['name'];
            // echo $name . '<br>';
            $allthemess [$name] = $value;
        }
        // filter by letter if required
        if (isset($startlet) && !empty($startlet)) {
            $allthemess = $this->_filterbyletter($allthemess, $startlet);
        }
        // echo "<pre>"; print_r($allthemes); echo "</pre>";

        $themes = array_slice($allthemess, $startnum - 1, $itemsperpage);

        // echo "<pre>"; print_r($themes); echo "</pre>";

        $this->view->assign('themes', $themes);

        // assign default theme
        $currenttheme = ModUtil::apiFunc('ZSELEX', 'admin',
                'getDefaultShopTheme',
                $args         = array(
                'shop_id' => $shop_id
        ));
        // echo "currenttheme :" . $currenttheme;
        // $this->view->assign('currenttheme', System::getVar('Default_Theme'));
        $this->view->assign('currenttheme', $currenttheme);

        $total_count = sizeof($allthemess);
        $this->view->assign('total_count', $total_count);
        // echo sizeof($allthemes);
        // assign the values for the pager plugin
        $this->view->assign('pager',
            array(
            'numitems' => sizeof($allthemess),
            'itemsperpage' => $itemsperpage
        ));

        // return the output that has been generated to the template
        return $this->view->fetch('admin/theme_admin_view.tpl');
    }

    /**
     * set theme as default for site.
     */
    public function setasdefaultshoptheme($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        // get our input
        $themename         = FormUtil::getPassedValue('themename',
                isset($args ['themename']) ? $args ['themename'] : null,
                'REQUEST');
        // echo $themename; exit;
        $confirmation      = (int) FormUtil::getPassedValue('confirmation',
                false, 'REQUEST');
        $resetuserselected = FormUtil::getPassedValue('resetuserselected',
                isset($args ['resetuserselected']) ? $args ['resetuserselected']
                        : null, 'POST');
        $shop_id           = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : null, 'REQUEST');

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        // check our input
        if (!isset($themename) || empty($themename)) {
            return LogUtil::registerArgsError(ModUtil::url('ZSELEX', 'admin',
                        'configureshoptheme',
                        array(
                        'shop_id' => $shop_id
            )));
        }

        // Security check
        if (!SecurityUtil::checkPermission('Theme::', '::', ACCESS_ADMIN)) {
            // return LogUtil::registerPermissionError();
        }

        // Check for confirmation.
        if (empty($confirmation)) {
            // No confirmation yet
            // Add a hidden field for the item ID to the output
            $this->view->assign('themename', $themename);
            $this->view->assign('shop_id', $shop_id);

            // assign the var defining if users can change themes
            $this->view->assign('theme_change', System::getVar('theme_change'));

            // Return the output that has been generated by this function
            return $this->view->fetch('admin/theme_admin_setasdefault.tpl');
        }

        // echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
        // If we get here it means that the user has confirmed the action
        $this->checkCsrfToken();

        // Set the default theme
        if (ModUtil::apiFunc('ZSELEX', 'admin', 'setasdefaultshoptheme',
                array(
                'themename' => $themename,
                'resetuserselected' => $resetuserselected,
                'shop_id' => $shop_id
            ))) {
            // Success
            LogUtil::registerStatus($this->__('Done! Default theme has been changed successfully.'));
        }

        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'configureshoptheme',
                array(
                'shop_id' => $shop_id
        )));
    }

    /**
     * filter theme array by letter.
     */
    private function _filterbyletter($allthemes, $startlet)
    {
        // echo "comes here";
        // echo "<pre>";print_r($allthemes);echo "</pre>";
        $themes = array();

        $startlet = strtolower($startlet);

        foreach ($allthemes as $key => $theme) {
            // echo $key[0] . '<br>';
            if (strtolower($key [0]) == $startlet) {
                $themes [$key] = $theme;
            }
        }

        return $themes;
    }

    public function createevent($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        $shop_id = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : null, 'REQUEST');
        // $classnames = get_class_methods('ZSELEX_Controller_Admin');
        // echo "<pre>"; print_r($classnames); echo "</pre>";
        if (!(int) ($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)));
        }
        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $loguser = UserUtil::getVar('uid');
        $user_id = $loguser;

        $serviceargs       = array(
            'shop_id' => $shop_id,
            'user_id' => $user_id,
            'type' => 'eventservice'
        );
        $servicePermission = $this->servicePermission($serviceargs);

        if ($servicePermission ['perm'] < 1) {
            return LogUtil::registerError($servicePermission ['message']);
        }

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) { // disabled check
            if ($this->serviceDisabled('eventservice') < 1) {
                return LogUtil::registerError($this->__('This service has been temporarily disabled!'));
            }
        }

        $shoptype = ModUtil::apiFunc('ZSELEX', 'admin', 'shopType',
                $args     = array(
                'shop_id' => $shop_id
        ));
        $shoptype = $shoptype ['shoptype'];

        if ($shoptype == 'iSHOP') {
            $finalproduct = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                    $args         = array(
                    'table' => 'zselex_products',
                    'where' => "shop_id=$shop_id AND prd_status=1",
                    'orderBy' => 'product_name ASC',
                    'useJoins' => ''
            ));
        } elseif ($shoptype == 'zSHOP') {
            $zenargs     = array(
                'table' => 'zselex_zenshop',
                'fields' => array(
                    '*'
                ),
                'where' => array(
                    "shop_id=$shop_id"
                )
            );
            $zencart     = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow',
                    $zenargs);
            $zenproducts = ModUtil::apiFunc('ZSELEX', 'admin',
                    'getZenCartProducts',
                    array(
                    'shop' => $zencart,
                    'shop_id' => $shop_id
            ));
            // echo "<pre>"; print_r($zenproducts); echo "</pre>";
            foreach ($zenproducts as $key => $val) {
                $finalproduct [] = array(
                    'product_id' => $val ['products_id'],
                    'product_name' => $val ['products_name']
                );
            }
        }
        // echo "<pre>"; print_r($finalproduct); echo "</pre>";
        $this->view->assign('products', $finalproduct);
        // echo "<pre>"; print_r($products); echo "</pre>";

        $createarticleargs = array(
            'shop_id' => $shop_id,
            'user_id' => $user_id,
            'disablecheck' => true,
            'type' => 'createarticle'
        );
        $articlePermission = $this->servicePermission($createarticleargs);
        $articlePerm       = $articlePermission ['perm'];
        // echo $articleerror;

        if ($articlePerm < 1) {
            $disable_article = 'disabled';
        } else {
            $disable_article = '';
        }
        // echo $disable_article;
        $this->view->assign('disable_article', $disable_article);
        $disable_image        = '';
        $check_images_args    = array(
            'shop_id' => $shop_id,
            'disablecheck' => true,
            'type' => 'minisiteimages'
        );
        $imagePermissionCheck = $this->servicePermission($check_images_args);
        $imagePermission      = $imagePermissionCheck ['perm'];

        $disable_addproduct        = '';
        $check_addproduct_args     = array(
            'shop_id' => $shop_id,
            'disablecheck' => true,
            'type' => 'addproducts'
        );
        $addproductPermissionCheck = $this->servicePermission($check_addproduct_args);
        $addproductPermission      = $addproductPermissionCheck ['perm'];
        // echo "<pre>"; print_r($addproductPermissionCheck); echo "</pre>";

        if ($addproductPermission < 1) {
            $disable_addproduct = 'disabled';
        }
        $this->view->assign('disable_addproduct', $disable_addproduct);

        $diskquota = ModUtil::apiFunc('ZSELEX', 'admin', 'checkDiskquota',
                $args      = array(
                'shop_id' => $shop_id
        ));
        // echo "<pre>"; print_r($diskquota); echo "</pre>";

        $disquotaerror = $diskquota ['error'];
        if ($disquotaerror) {
            $disabled_status = 'disabled';
        } else {
            $disabled_status = '';
        }

        $this->view->assign('disabled_status', $disabled_status);

        if ($imagePermission < 1) {
            $disable_image = 'disabled';
        } elseif ($disquotaerror) {
            $disable_image = 'disabled';
        }

        $this->view->assign('disable_image', $disable_image);

        $function  = 'createevent';
        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('function', $function);
        $articles  = ModUtil::apiFunc('ZSELEX', 'admin', 'getShopArticleEvents',
                $args      = array(
                'shop_id' => $shop_id
        ));
        // echo "<pre>"; print_r($articles); echo "</pre>";
        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));
        $sess_item = SessionUtil::getVar('createevent');
        $this->view->assign('item', $sess_item);
        // echo "<pre>"; print_r($sess_item); echo "</pre>";
        // if ($this->request->isPost()) {
        if ($_POST) { // submit event.
            $formElements = FormUtil::getPassedValue('formElements',
                    isset($args ['formElements']) ? $args ['formElements'] : null,
                    'POST');
            // $files = News_ImageUtil::reArrayFiles(FormUtil::getPassedValue('news_files', null, 'FILES'));
            // $docs = News_ImageUtil::reArrayFiles(FormUtil::getPassedValue('event_docs', null, 'FILES'));
            $file         = FormUtil::getPassedValue('news_files', null, 'FILES');
            $doc          = FormUtil::getPassedValue('event_docs', null, 'FILES');
            // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;
            $upload_check = 0;
            if ($disable_article != '' || $disabled_status != '') {
                if (empty($doc ['error']) || empty($file ['error']) || !empty($formElements ['article'])) { // true condition
                    $upload_check = 1;
                }
            }
            // echo $upload_check; exit;
            $item = array(
                'shop_id' => $formElements ['shop_id'],
                'shop_event_name' => $formElements ['eventname'],
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
                'product_id' => $formElements ['product'],
                'showfrom' => $formElements ['showfrom'],
                'price' => $formElements ['price'],
                'email' => $formElements ['email'],
                'phone' => $formElements ['phone'],
                'shop_event_venue' => $formElements ['shop_event_venue'],
                'status' => $formElements ['status']
            );

            $items = array_merge($item,
                array(
                'upload_check' => $upload_check
            ));
            // echo "<pre>"; print_r($items); echo "</pre>"; exit;
            // ******* Validation happens here ******//

            $validationerror = ZSELEX_Util::validateShopEvent($items);

            if ($validationerror !== false) {
                // log the error found if any
                if ($validationerror !== false) {
                    LogUtil::registerError(nl2br($validationerror));
                }

                SessionUtil::setVar('createevent', $item);

                $this->redirect(ModUtil::url('ZSELEX', 'admin', 'createevent',
                        array(
                        'shop_id' => $shop_id
                )));
            } else {
                // As we're not previewing the item let's remove it from the session
                SessionUtil::delVar('createevent');
            }
            // ******* Validation ends ******//
            $keywords = $formElements ['keywords'];
            // echo "<pre>"; print_r($item); echo "</pre>"; exit;

            $args     = array(
                'table' => 'zselex_shop_events',
                'element' => $item,
                'Id' => 'shop_event_id'
            );
            // Create the event
            $result   = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement',
                    $args);
            $InsertId = DBUtil::getInsertID($args ['table'], $args ['Id']);

            $diskquota = ModUtil::apiFunc('ZSELEX', 'admin', 'checkDiskquota',
                    $args      = array(
                    'shop_id' => $formElements ['shop_id']
            ));

            // echo "<pre>"; print_r($diskquota); echo "</pre>"; exit;

            if ($result) {
                LogUtil::registerStatus($this->__('Done! Event created successfully'));
                if (!empty($keywords)) {
                    $keywords_for_search = str_replace(',', ' ', $keywords);
                    $keywords_for_search = explode(' ', $keywords_for_search);
                    foreach ($keywords_for_search as $keyword) {
                        $keywordExist = ModUtil::apiFunc('ZSELEX', 'admin',
                                'getCount',
                                $args         = array(
                                'table' => 'zselex_keywords',
                                'where' => "keyword='".$keyword."'"
                        ));

                        if ($keywordExist < 1) {
                            $keyword_item   = array(
                                'keyword' => $keyword,
                                'type' => 'eventservice',
                                'type_id' => $InsertId,
                                'shop_id' => $shop_id
                            );
                            $keyword_args   = array(
                                'table' => 'zselex_keywords',
                                'element' => $keyword_item,
                                'Id' => 'keyword_id'
                            );
                            $result_keyword = ModUtil::apiFunc('ZSELEX',
                                    'admin', 'createElement', $keyword_args);
                        }
                    }
                }

                if ($file ['error'] < 1) {
                    // make directories if not exist.
                    if (!is_dir('zselexdata/'.$ownerName)) {
                        mkdir('zselexdata/'.$ownerName, 0775);
                        chmod('zselexdata/'.$ownerName, 0775);
                    }

                    if (!is_dir('zselexdata/'.$ownerName.'/events')) {
                        mkdir('zselexdata/'.$ownerName.'/events', 0775);
                        chmod('zselexdata/'.$ownerName.'/events', 0775);
                    }

                    if (!is_dir('zselexdata/'.$ownerName.'/events/fullsize')) {
                        mkdir('zselexdata/'.$ownerName.'/events/fullsize', 0775);
                        chmod('zselexdata/'.$ownerName.'/events/fullsize', 0775);
                    }
                    if (!is_dir('zselexdata/'.$ownerName.'/events/medium')) {
                        mkdir('zselexdata/'.$ownerName.'/events/medium', 0775);
                        chmod('zselexdata/'.$ownerName.'/events/medium', 0775);
                    }
                    if (!is_dir('zselexdata/'.$ownerName.'/events/thumb')) {
                        mkdir('zselexdata/'.$ownerName.'/events/thumb', 0775);
                        chmod('zselexdata/'.$ownerName.'/events/medium', 0775);
                    }

                    $destination = 'zselexdata/'.$ownerName.'/events/';

                    $new_file_name = time().'_'.$file ['name'];
                    // echo $new_file_name; exit;
                    $newNme        = array(
                        'newName' => $new_file_name
                    );

                    $file    = $file + $newNme;
                    $size    = $file ['size'];
                    $allsize = $diskquota ['sizeused'] + $size;

                    if ($diskquota ['count'] < 1) {
                        LogUtil::registerStatus($this->__('Image was not uploaded.').'. '.$diskquota ['message']);
                    } elseif ($diskquota ['limitover'] < 1) {
                        LogUtil::registerStatus($this->__('Image was not uploaded.').'. '.$diskquota ['message']);
                    } elseif ($allsize >= $diskquota ['sizelimit']) {
                        LogUtil::registerStatus($this->__('Image was not uploaded.').'. '.'your dont have enough disquoata to upload this file. Please upgrade it');
                    } else {
                        if ($this->uploadImage($file, $destination) == true) {
                            $itemimage  = array(
                                'shop_event_id' => $InsertId,
                                'event_image' => $file ['newName']
                            );
                            $updateargs = array(
                                'table' => 'zselex_shop_events',
                                'IdValue' => $InsertId,
                                'IdName' => 'shop_event_id',
                                'element' => $itemimage
                            );

                            // echo "<pre>"; print_r($updateargs); echo "</pre>"; exit;
                            $result = ModUtil::apiFunc('ZSELEX', 'admin',
                                    'updateElement', $updateargs);
                        }
                    }
                }
                if ($doc ['error'] < 1) {
                    // make directories if not exist.
                    if (!is_dir('zselexdata/'.$ownerName)) {
                        mkdir('zselexdata/'.$ownerName, 0775);
                        chmod('zselexdata/'.$ownerName, 0775);
                    }

                    if (!is_dir('zselexdata/'.$ownerName.'/events')) {
                        mkdir('zselexdata/'.$ownerName.'/events', 0775);
                        chmod('zselexdata/'.$ownerName.'/events', 0775);
                    }

                    if (!is_dir('zselexdata/'.$ownerName.'/events/docs')) {
                        mkdir('zselexdata/'.$ownerName.'/events/docs', 0775);
                        chmod('zselexdata/'.$ownerName.'/events/docs', 0775);
                    }

                    $destination = 'zselexdata/'.$ownerName.'/events/docs';

                    $new_file_name = time().'_'.$doc ['name'];
                    // echo $new_file_name; exit;
                    $newNme        = array(
                        'newName' => $new_file_name
                    );

                    $doc     = $doc + $newNme;
                    $size    = $doc ['size'];
                    $allsize = $diskquota ['sizeused'] + $size;

                    if ($diskquota ['count'] < 1) {
                        LogUtil::registerStatus($this->__('Doc was not uploaded.').'. '.$diskquota ['message']);
                    } elseif ($diskquota ['limitover'] < 1) {
                        LogUtil::registerStatus($this->__('Doc was not uploaded.').'. '.$diskquota ['message']);
                    } elseif ($allsize >= $diskquota ['sizelimit']) {
                        LogUtil::registerStatus($this->__('Image was not uploaded.').'. '.'your dont have enough disquoata to upload this file. Please upgrade it');
                    } else {
                        if ($this->uploadEventDocs($doc, $destination) == true) {
                            $itemdoc    = array(
                                'shop_event_id' => $InsertId,
                                'event_doc' => $doc ['newName']
                            );
                            $updateargs = array(
                                'table' => 'zselex_shop_events',
                                'IdValue' => $InsertId,
                                'IdName' => 'shop_event_id',
                                'element' => $itemdoc
                            );

                            // echo "<pre>"; print_r($updateargs); echo "</pre>"; exit;
                            $result = ModUtil::apiFunc('ZSELEX', 'admin',
                                    'updateElement', $updateargs);
                        }
                    }
                }
                $user_id          = UserUtil::getVar('uid');
                $serviceupdatearg = array(
                    'user_id' => $user_id,
                    'type' => 'eventservice',
                    'shop_id' => $shop_id
                );
                $serviceavailed   = ModUtil::apiFunc('ZSELEX', 'admin',
                        'updateServiceUsed', $serviceupdatearg);
                ModUtil::apiFunc('ZSELEX', 'admin', 'updateMinisite',
                    array(
                    'shop_id' => $shop_id
                ));
                $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshopevent',
                        array(
                        'shop_id' => $shop_id
                )));
            }
        }

        $shop_info = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                $args      = array(
                'table' => 'zselex_shop',
                'where' => "shop_id=$shop_id",
                'fields' => array(
                    'shop_id',
                    'email',
                    'address',
                    'telephone'
                )
        ));

        $shop_item = array(
            'shop_email' => $shop_info ['email'],
            'shop_address' => $shop_info ['address'],
            'shop_phone' => $shop_info ['telephone']
        );
        // echo "<pre>"; print_r($shop_info); echo "</pre>";
        $this->view->assign('shop_item', $shop_item);
        $start     = strtotime('12:00am');
        $end       = strtotime('12:00pm');

        $starttime         = '';
        $selectedstarttime = '';
        $starttime .= '<select name="formElements[starttime]">';
        $starttime .= '<option value="">select</option>';
        for ($i = $start; $i <= $end; $i += 900) {
            if ($sess_item ['shop_event_starttime'] == date('g:i a', $i)) {
                $selectedstarttime = 'selected=selected';
            } else {
                $selectedstarttime = '';
            }
            $starttime .= '<option '.$selectedstarttime.'>'.date('g:i a', $i).'</option>';
        }
        $starttime .= '</select>';

        $endtime         = '';
        $selectedendtime = '';
        $endtime .= '<select name="formElements[endtime]">';
        $endtime .= '<option value="">select</option>';
        for ($i = $start; $i <= $end; $i += 900) {
            if ($sess_item ['shop_event_endtime'] == date('g:i a', $i)) {
                $selectedendtime = 'selected=selected';
            } else {
                $selectedendtime = '';
            }
            $endtime .= '<option  '.$selectedendtime.'>'.date('g:i a', $i).'</option>';
        }
        $endtime .= '</select>';

        $modvars       = ModUtil::getVar('News');
        $newsimagepath = $modvars ['picupload_uploaddir'];

        // $newsimagepath = 'news_picupload';
        $this->view->assign('starttime', $starttime);
        $this->view->assign('endtime', $endtime);
        $this->view->assign('articles', $articles);
        $this->view->assign('newsimagepath', $newsimagepath);

        return $this->view->fetch('admin/event/createevent.tpl');
    }

    public function modifyevent($args)
    {
        $shop_id = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : null, 'REQUEST');
        $eventId = FormUtil::getPassedValue('eventId',
                isset($args ['eventId']) ? $args ['eventId'] : null, 'REQUEST');

        if (!is_numeric($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)), 403);
        }

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) { // disabled check
            if ($this->serviceDisabled('dealoftheday') < 1) {
                return LogUtil::registerError($this->__('This service has been temporarily disabled!'));
            }
        }

        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));
        // echo $ownerName; exit;
        $function  = 'modifyevent';
        $this->view->assign('ownerName', $ownerName);
        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('function', $function);

        $shoptype = ModUtil::apiFunc('ZSELEX', 'admin', 'shopType',
                $args     = array(
                'shop_id' => $shop_id
        ));
        $shoptype = $shoptype ['shoptype'];

        if ($shoptype == 'iSHOP') {
            $finalproduct = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                    $args         = array(
                    'table' => 'zselex_products',
                    'where' => "shop_id=$shop_id AND prd_status=1",
                    'orderBy' => 'product_name ASC',
                    'useJoins' => ''
            ));
        } elseif ($shoptype == 'zSHOP') {
            $zenargs     = array(
                'table' => 'zselex_zenshop',
                'fields' => array(
                    '*'
                ),
                'where' => array(
                    "shop_id=$shop_id"
                )
            );
            $zencart     = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow',
                    $zenargs);
            $zenproducts = ModUtil::apiFunc('ZSELEX', 'admin',
                    'getZenCartProducts',
                    array(
                    'shop' => $zencart,
                    'shop_id' => $shop_id
            ));
            // echo "<pre>"; print_r($zenproducts); echo "</pre>";
            foreach ($zenproducts as $key => $val) {
                $finalproduct [] = array(
                    'product_id' => $val ['products_id'],
                    'product_name' => $val ['products_name']
                );
            }
        }
        $this->view->assign('products', $finalproduct);

        if ($_POST) {
            $formElements = FormUtil::getPassedValue('formElements',
                    isset($args ['formElements']) ? $args ['formElements'] : null,
                    'POST');
            $file         = FormUtil::getPassedValue('news_files', null, 'FILES');
            $doc          = FormUtil::getPassedValue('event_docs', null, 'FILES');
            // echo "<pre>"; print_r($formElements); echo "</pre>"; exit;
            // echo "<pre>"; print_r($doc); echo "</pre>"; exit;
            $item         = array(
                'shop_event_id' => $formElements ['elemId'],
                'shop_id' => $formElements ['shop_id'],
                'shop_event_name' => $formElements ['eventname'],
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
                'product_id' => $formElements ['product'],
                'showfrom' => $formElements ['showfrom'],
                'price' => $formElements ['price'],
                'email' => $formElements ['email'],
                'phone' => $formElements ['phone'],
                'shop_event_venue' => $formElements ['venue'],
                'status' => $formElements ['status']
            );
            // $itemvalidate = $item;
            $itemvalidate = array_merge(array(
                'type' => 'modify'
                ), $item);
            $eventId      = $formElements ['elemId'];

            // echo "<pre>"; print_r($itemvalidate); echo "</pre>"; exit;
            // ******* Validation happens here ******//

            $validationerror = ZSELEX_Util::validateShopEvent($itemvalidate);

            if ($validationerror !== false) {
                // log the error found if any
                if ($validationerror !== false) {
                    LogUtil::registerError(nl2br($validationerror));
                }
                SessionUtil::setVar('createevent', $itemvalidate);

                $this->redirect(ModUtil::url('ZSELEX', 'admin', 'modifyevent',
                        array(
                        'shop_id' => $shop_id,
                        'eventId' => $eventId
                )));
            } else {
                // As we're not previewing the item let's remove it from the session
                SessionUtil::delVar('createevent');
            }
            // ******* Validation ends ******//
            $keywords = $formElements ['keywords'];

            $updateargs = array(
                'table' => 'zselex_shop_events',
                'IdValue' => $eventId,
                'IdName' => 'shop_event_id',
                'element' => $item
            );

            // echo "<pre>"; print_r($updateargs); echo "</pre>"; exit;
            $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement',
                    $updateargs);

            if ($result) {
                ModUtil::apiFunc('ZSELEX', 'admin', 'updateMinisite',
                    array(
                    'shop_id' => $formElements ['shop_id']
                ));
                LogUtil::registerStatus($this->__('Done! Event saved successfully'));
                $where_keyword = "WHERE type='eventservice' AND type_id=$eventId";
                if (DBUtil::deleteWhere('zselex_keywords', $where_keyword)) {
                    if (!empty($keywords)) {
                        $keywords_for_search = str_replace(',', ' ', $keywords);
                        $keywords_for_search = explode(' ', $keywords_for_search);
                        foreach ($keywords_for_search as $keyword) {
                            $keywordExist = ModUtil::apiFunc('ZSELEX', 'admin',
                                    'getCount',
                                    $args         = array(
                                    'table' => 'zselex_keywords',
                                    'where' => "keyword='".$keyword."'"
                            ));

                            if ($keywordExist < 1) {
                                $keyword_item   = array(
                                    'keyword' => $keyword,
                                    'type' => 'eventservice',
                                    'type_id' => $eventId
                                );
                                $keyword_args   = array(
                                    'table' => 'zselex_keywords',
                                    'element' => $keyword_item,
                                    'Id' => 'keyword_id'
                                );
                                $result_keyword = ModUtil::apiFunc('ZSELEX',
                                        'admin', 'createElement', $keyword_args);
                            }
                        }
                    }
                }

                if ($file ['error'] < 1) {
                    // make directories if not exist.
                    if (!is_dir('zselexdata/'.$ownerName)) {
                        mkdir('zselexdata/'.$ownerName, 0775);
                        chmod('zselexdata/'.$ownerName, 0775);
                    }

                    if (!is_dir('zselexdata/'.$ownerName.'/events')) {
                        mkdir('zselexdata/'.$ownerName.'/events', 0775);
                        chmod('zselexdata/'.$ownerName.'/events', 0775);
                    }

                    if (!is_dir('zselexdata/'.$ownerName.'/events/fullsize')) {
                        mkdir('zselexdata/'.$ownerName.'/events/fullsize', 0775);
                        chmod('zselexdata/'.$ownerName.'/events/fullsize', 0775);
                    }
                    if (!is_dir('zselexdata/'.$ownerName.'/events/medium')) {
                        mkdir('zselexdata/'.$ownerName.'/events/medium', 0775);
                        chmod('zselexdata/'.$ownerName.'/events/medium', 0775);
                    }
                    if (!is_dir('zselexdata/'.$ownerName.'/events/thumb')) {
                        mkdir('zselexdata/'.$ownerName.'/events/thumb', 0775);
                        chmod('zselexdata/'.$ownerName.'/events/medium', 0775);
                    }
                    $destination   = 'zselexdata/'.$ownerName.'/events/';
                    $new_file_name = time().'_'.$file ['name'];
                    $newNme        = array(
                        'newName' => $new_file_name
                    );

                    $file = $file + $newNme;

                    $existingImage = $formElements ['selectedimage'];

                    if (!empty($existingImage)) {
                        $existingFileSize = filesize('zselexdata/'.$ownerName.'/events/'.$existingImage);
                    } else {
                        $existingFileSize = 0;
                    }
                    $size = $file ['size'];

                    $diskquota = ModUtil::apiFunc('ZSELEX', 'admin',
                            'checkDiskquota',
                            $args      = array(
                            'shop_id' => $formElements ['shop_id']
                    ));
                    $allsize   = $diskquota ['sizeused'] + $size;
                    $allsize1  = $diskquota ['sizeused'] - $existingFileSize;
                    $allsizes  = $allsize1 + $size;

                    if ($diskquota ['count'] < 1) {
                        LogUtil::registerStatus($this->__('Image was not uploaded.').'. '.$diskquota ['message']);
                    } elseif ($diskquota ['limitover'] < 1) {
                        LogUtil::registerStatus($this->__('Image was not uploaded.').'. '.$diskquota ['message']);
                    } elseif ($allsizes >= $diskquota ['sizelimit']) {
                        LogUtil::registerStatus($this->__('Image was not uploaded.').'. '.$this->__('Your diskquota doesnt has sufficient space'));
                    } else {
                        if ($this->uploadImage($file, $destination) == true) {
                            $itemimage  = array(
                                'shop_event_id' => $eventId,
                                'event_image' => $file ['newName']
                            );
                            $updateargs = array(
                                'table' => 'zselex_shop_events',
                                'IdValue' => $eventId,
                                'IdName' => 'shop_event_id',
                                'element' => $itemimage
                            );
                            unlink('zselexdata/'.$ownerName.'/events/'.$formElements ['selectedimage']);
                            unlink('zselexdata/'.$ownerName.'/events/fullsize/'.$formElements ['selectedimage']);
                            unlink('zselexdata/'.$ownerName.'/events/medium/'.$formElements ['selectedimage']);
                            unlink('zselexdata/'.$ownerName.'/events/thumb/'.$formElements ['selectedimage']);
                            // echo "<pre>"; print_r($updateargs); echo "</pre>"; exit;
                            $result     = ModUtil::apiFunc('ZSELEX', 'admin',
                                    'updateElement', $updateargs);
                        }
                    }
                }

                if ($doc ['error'] < 1) {
                    if (!is_dir('zselexdata/'.$ownerName)) {
                        mkdir('zselexdata/'.$ownerName, 0775);
                        chmod('zselexdata/'.$ownerName, 0775);
                    }

                    if (!is_dir('zselexdata/'.$ownerName.'/events')) {
                        mkdir('zselexdata/'.$ownerName.'/events', 0775);
                        chmod('zselexdata/'.$ownerName.'/events', 0775);
                    }
                    if (!is_dir('zselexdata/'.$ownerName.'/events/docs')) {
                        mkdir('zselexdata/'.$ownerName.'/events/docs', 0775);
                        chmod('zselexdata/'.$ownerName.'/events/docs', 0775);
                    }
                    $destination   = 'zselexdata/'.$ownerName.'/events/docs';
                    $new_file_name = time().'_'.$doc ['name'];
                    $newNme        = array(
                        'newName' => $new_file_name
                    );

                    $doc = $doc + $newNme;

                    $size = $doc ['size'];

                    $diskquota = ModUtil::apiFunc('ZSELEX', 'admin',
                            'checkDiskquota',
                            $args      = array(
                            'shop_id' => $formElements ['shop_id']
                    ));
                    $allsize   = $diskquota ['sizeused'] + $size;
                    $allsize1  = $diskquota ['sizeused'] - $existingFileSize;
                    $allsizes  = $allsize1 + $size;
                    // echo "<pre>";print_r($diskquota); echo "</pre>"; exit;

                    /*
                     * if ($diskquota['count'] < 1) {
                     * LogUtil::registerStatus($this->__('Doc was not uploaded.') . ". " . $diskquota['message']);
                     * } else if ($diskquota['limitover'] < 1) {
                     * LogUtil::registerStatus($this->__('Doc was not uploaded.') . ". " . $diskquota['message']);
                     * } else if ($allsizes >= $diskquota['sizelimit']) {
                     * LogUtil::registerStatus($this->__('Doc was not uploaded.') . ". " . $this->__("Your diskquota doesnt has sufficient space"));
                     * }
                     */

                    if ($diskquota ['error'] > 0) {
                        $message = $this->__('Doc was not uploaded.').'. '.$diskquota ['message'];
                        $this->statusmsg .= $message."\n";
                        LogUtil::registerStatus($this->statusmsg);
                        // exit;
                    } elseif ($allsizes >= $diskquota ['sizelimit']) {
                        LogUtil::registerStatus($this->__('Doc was not uploaded.').'. '.$this->__('Your diskquota doesnt has sufficient space'));
                    } else {
                        // echo "comes here"; exit;

                        if ($this->uploadEventDocs($doc, $destination) == true) {
                            $itemdoc    = array(
                                'shop_event_id' => $eventId,
                                'event_doc' => $doc ['newName']
                            );
                            $updateargs = array(
                                'table' => 'zselex_shop_events',
                                'IdValue' => $eventId,
                                'IdName' => 'shop_event_id',
                                'element' => $itemdoc
                            );

                            // echo "<pre>"; print_r($updateargs); echo "</pre>"; exit;
                            $result = ModUtil::apiFunc('ZSELEX', 'admin',
                                    'updateElement', $updateargs);
                            unlink('zselexdata/'.$ownerName.'/events/docs/'.$formElements ['selecteddoc']);
                        }
                    }
                }

                $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshopevent',
                        array(
                        'shop_id' => $shop_id
                )));
            } //
        }

        $item = array();

        $articles = ModUtil::apiFunc('ZSELEX', 'admin', 'getShopArticleEvents',
                $args     = array(
                'shop_id' => $shop_id
        ));
        // $obj = DBUtil::selectObjectByID('zselex_shop_events', $eventId, 'shop_event_id');

        $joinInfo [] = array(
            'join_table' => 'zselex_shop',
            'join_field' => array(
                'email',
                'address',
                'telephone'
            ),
            'object_field_name' => array(
                'shop_email',
                'shop_address',
                'shop_phone'
            ),
            'compare_field_table' => 'shop_id', // main table
            'compare_field_join' => 'shop_id'
        );

        $getEvent = ModUtil::apiFunc('ZSELEX', 'user', 'getByJoin',
                $args     = array(
                'table' => 'zselex_shop_events',
                'where' => "shop_event_id=$eventId",
                'joinInfo' => $joinInfo
        ));

        // echo "<pre>"; print_r($getEvent); echo "</pre>";

        $start = strtotime('12:00am');
        $end   = strtotime('12:00pm');

        $starttime         = '';
        $selectedstarttime = '';

        $endtime         = '';
        $selectedendtime = '';

        $sess_item = SessionUtil::getVar('createevent');
        // echo "<pre>"; print_r($sess_item); echo "</pre>";

        if (!empty($sess_item)) {
            $item = $sess_item;
        } else {
            $item = $getEvent;
        }

        // echo "<pre>"; print_r($item); echo "</pre>";

        $starttime .= '<select name="formElements[starttime]">';
        $starttime .= '<option value="">select</option>';
        for ($i = $start; $i <= $end; $i += 900) {
            if ($item ['shop_event_starttime'] == date('g:i a', $i)) {
                $selectedstarttime = 'selected=selected';
            } else {
                $selectedstarttime = '';
            }
            $starttime .= '<option '.$selectedstarttime.'>'.date('g:i a', $i).'</option>';
        }
        $starttime .= '</select>';

        $endtime .= '<select name="formElements[endtime]">';
        $endtime .= '<option value="">select</option>';
        for ($i = $start; $i <= $end; $i += 900) {
            if ($item ['shop_event_endtime'] == date('g:i a', $i)) {
                $selectedendtime = 'selected=selected';
            } else {
                $selectedendtime = '';
            }
            $endtime .= '<option  '.$selectedendtime.'>'.date('g:i a', $i).'</option>';
        }
        $endtime .= '</select>';

        // $sess_item = SessionUtil::getVar('createevent');
        // $this->view->assign('item', $sess_item);
        $this->view->assign('starttime', $starttime);
        $this->view->assign('endtime', $endtime);
        $this->view->assign('articles', $articles);
        $this->view->assign('item', $item);

        return $this->view->fetch('admin/event/createevent.tpl');
    }

    public function deleteevent($args)
    {
        $eventId   = FormUtil::getPassedValue('eventId',
                isset($args ['eventId']) ? $args ['eventId'] : null, 'REQUEST');
        $shop_id   = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : null, 'REQUEST');
        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));

        $obj = DBUtil::selectObjectByID('zselex_shop_events', $eventId,
                'shop_event_id');

        // echo "<pre>"; print_r($obj); echo "</pre>"; exit;

        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
        // Validate the essential parameters
        if (empty($eventId)) {
            return LogUtil::registerArgsError();
        }
        $args = array(
            'table' => 'zselex_shop_events',
            'IdValue' => $eventId,
            'IdName' => 'shop_event_id'
        );
        // Check for confirmation.
        if (empty($confirmation)) {
            // Add ZSELEX type ID
            $this->view->assign('IdValue', $eventId);
            $this->view->assign('cofirm_title', 'Delete This Event');
            $this->view->assign('confirm_msg',
                'Do you Want To Delete This Event');
            $this->view->assign('shop_id', $shop_id);
            $this->view->assign('IdName', 'eventId');
            $this->view->assign('submitFunc', 'deleteevent');
            $this->view->assign('cancelFunc', 'viewshopevent');

            // Return the output that has been generated by this function
            return $this->view->fetch('admin/deletecommon.tpl');
        }

        // If we get here it means that the admin has confirmed the action
        // Confirm authorisation code
        $this->checkCsrfToken();

        // Delete
        if (ModUtil::apiFunc('ZSELEX', 'admin', 'deleteItem', $args)) {
            $where_keywords  = "WHERE type='eventservice' AND type_id=$eventId";
            $delete_keywords = DBUtil::deleteWhere('zselex_keywords',
                    $where_keywords);

            unlink('zselexdata/'.$ownerName.'/events/'.$obj ['event_image']);
            unlink('zselexdata/'.$ownerName.'/events/fullsize/'.$obj ['event_image']);
            unlink('zselexdata/'.$ownerName.'/events/medium/'.$obj ['event_image']);
            unlink('zselexdata/'.$ownerName.'/events/thumb/'.$obj ['event_image']);
            unlink('zselexdata/'.$ownerName.'/events/docs/'.$obj ['event_doc']);

            $args          = array(
                'shop_id' => $shop_id,
                'servicetype' => 'eventservice'
            );
            $deleteservice = ModUtil::apiFunc('ZSELEX', 'admin',
                    'deleteService', $args);
            // Success
            LogUtil::registerStatus($this->__('Done! Deleted Event.'));
            ModUtil::apiFunc('ZSELEX', 'admin', 'updateMinisite',
                array(
                'shop_id' => $shop_id
            ));
            // Let any hooks know that we have deleted an item
            $this->notifyHooks(new Zikula_ProcessHook('type.ui_hooks.types.process_delete',
                $Id));
        }

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshopevent',
                    array(
                    'shop_id' => $shop_id
        )));
    }

    public function uploadEventDocs($file, $destination)
    {
        $name      = $file ['name'];
        $temp_name = $file ['tmp_name'];

        // Check file extension
        list ( $width, $height, $type, $attr ) = getimagesize($temp_name);

        // echo $width; exit;

        $allowedExtensions = array(
            'png',
            'jpg',
            'gif',
            'jpeg'
        );
        $ex                = end(explode('.', $name));
        // if (!in_array($ex, $allowedExtensions)) {
        // return LogUtil::registerError($this->__f('Error! Invalid file type: %1$s', $ex));
        // }

        $code = self::doUploadFile($file, $destination);
        // LogUtil::registerError(FileUtil::uploadErrorMsg($code));

        return true;
    }

    public function viewshopevent($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        // initialize sort array - used to display sort classes and urls
        // echo $themepath; exit;
        SessionUtil::delVar('createevent');
        $shop_id = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : 0, 'REQUEST');

        if ($this->shopPermission($shop_id) < 1) {
            return LogUtil::registerPermissionError();
        }
        $user_id = UserUtil::getVar('uid');

        $admin = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN);
        $this->view->assign('admin', $admin);

        $disabled       = 'no';
        $servicecount   = 0;
        $error          = 0;
        $message        = '';
        $expired        = 0;
        $servicedisable = false;

        $serviceargs       = array(
            'shop_id' => FormUtil::getPassedValue('shop_id', null, 'REQUEST'),
            'user_id' => $user_id,
            'type' => 'eventservice'
        );
        $servicePermission = $this->servicePermission($serviceargs);

        // echo "<pre>"; print_r($servicePermission); echo "</pre>";
        $servicecount += $servicePermission ['perm'];

        if ($servicePermission ['perm'] < 1) {
            // $template = 'viewshoppdf_images_noservice.tpl';
            // $message = $this->__("The service you try to use has to be purchased first");
            $message = $servicePermission ['message'];
            $this->errormsg .= $message."\n";
            ++$error;
            LogUtil::registerError(nl2br($this->errormsg));
        }

        if ($this->serviceDisabled('eventservice') < 1) {
            if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $servicedisable = true;
                ++$error;
            }
            $disable = 'disabled';
            $message = $this->__('This service is currently disabled');
            $this->errormsg .= $message."\n";
            LogUtil::registerError(nl2br($message));
        }
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            $expired = $servicePermission ['expired'];
        }

        $this->view->assign('servicecount', $servicecount);
        $this->view->assign('servicedisable', $servicedisable);
        $this->view->assign('disable', $disable);
        $this->view->assign('serviceerror', $error);
        $this->view->assign('expired', $expired);
        $this->view->assign('message', $message);

        $sort   = array();
        $fields = array(
            'shop_event_id',
            'shop_event_name'
        ); // possible sort fields

        $itemsperpage  = FormUtil::getPassedValue('itemsperpage',
                isset($args ['itemsperpage']) ? $args ['itemsperpage'] : 25,
                'GETPOST');
        $startnum      = FormUtil::getPassedValue('startnum',
                isset($args ['startnum']) ? $args ['startnum'] : null, 'GETPOST');
        $status        = FormUtil::getPassedValue('status',
                isset($args ['status']) ? $args ['status'] : null, 'GETPOST');
        $order         = FormUtil::getPassedValue('order',
                isset($args ['order']) ? $args ['order'] : 'shop_event_id',
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
                    'viewshopevent',
                    array(
                    'status' => $status,
                    'filtercats_serialized' => serialize($filtercats),
                    'order' => $field,
                    'shop_id' => $shop_id,
                    'sdir' => $sdir
            ));
        }
        // echo "<pre>"; print_r($sort); echo "</pre>";
        $this->view->assign('sort', $sort);

        $this->view->assign('filter_active', $status);

        // $sql = " SELECT a.* FROM zselex_type AS a
        // WHERE a.type_id IS NOT NULL ";

        $sql = '';
        if (isset($status) && $status != '') {
            $sql .= ' AND status = '.$status;
        }
        if (isset($searchtext) && $searchtext != '') {
            $sql .= " AND shop_event_name LIKE '%".DataUtil::formatForStore($searchtext)."%'";
        }
        if (isset($order) && $order != '') {
            $sql .= ' ORDER BY '.$order.' '.$orderdir;
        }

        // Get all zselex stories
        $getArgs = array(
            'sql' => $sql,
            'startnum' => $startnum,
            'numitems' => $itemsperpage
        );
        // $items = ModUtil::apiFunc('ZSELEX', 'admin', 'getAll', $getArgs);

        $items     = array();
        $itemarray = ModUtil::apiFunc('ZSELEX', 'admin', 'getShopEvents',
                $args      = array(
                'start' => $startnum,
                'itemsperpage' => $itemsperpage,
                'shop_id' => $shop_id,
                'sql' => $sql
        ));
        // echo "<pre>"; print_r($itemarray); echo "</pre>";
        $items     = $itemarray ['items'];
        $count     = $itemarray ['count'];
        // echo "<pre>"; print_r($items); echo "</pre>";

        $total_types = $count;

        // Set the possible status for later use
        $itemstatus = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );

        $typesitems = array();
        foreach ($items as $item) {
            $options = array();

            if (SecurityUtil::checkPermission('ZSELEX::',
                    "{$item['cr_uid']}::{$item['type_id']}", ACCESS_EDIT)) {
                $options [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'modifyevent',
                        array(
                        'eventId' => $item ['shop_event_id'],
                        'shop_id' => $shop_id
                    )),
                    'image' => 'xedit.png',
                    'title' => $this->__('Edit')
                );

                if ((SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['type_id']}", ACCESS_DELETE))
                    || SecurityUtil::checkPermission('ZSELEX::',
                        "{$item['cr_uid']}::{$item['type_id']}", ACCESS_ADD)) {
                    $options [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin', 'deleteevent',
                            array(
                            'eventId' => $item ['shop_event_id'],
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
            $typesitems []     = $item;
        }
        $zThemes = DBUtil::selectObjectArray('zselex_themes');

        foreach ($zThemes as $theme) {
            $zselexTheme [] = $theme ['theme_id'];
        }
        $ownerName  = $this->ownername;
        $uploadpath = 'zselexdata/'.$ownerName.'/';
        if ($_SERVER ['SERVER_NAME'] == 'localhost') {
            $uploadpath = 'zselexdata/'.$ownerName.'/';
        }
        $this->view->assign('uploadpath', $uploadpath);

        // echo "<pre>"; print_r($typesitems); echo "</pre>";
        // echo "<pre>"; print_r($ownerTheme); echo "</pre>";
        // Assign the items to the template
        $this->view->assign('typesitems', $typesitems);
        $this->view->assign('zselexTheme', $zselexTheme);

        $this->view->assign('total_types', $total_types);

        // Assign the current status filter and the possible ones
        $this->view->assign('status', $status);
        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('order', $order);
        // Return the output that has been generated by this function
        return $this->view->fetch('admin/event/viewshopevent.tpl');
    }

    public function dateDiff($start, $end)
    { // returns number of days between two dates
        $start_ts = strtotime($start);

        $end_ts = strtotime($end);

        $diff = $end_ts - $start_ts;

        return round($diff / 86400);
    }

    public function deleteServiceCart($args)
    {
        $basket_id = FormUtil::getPassedValue('basket_id');
        // DBUtil::deleteObjectById('zselex_basket', $basket_id, 'basket_id');
        $this->entityManager->getRepository('ZSELEX_Entity_Bundle')->deleteEntity(null,
            'ZSELEX_Entity_ServiceBasket',
            array(
            'a.basket_id' => $basket_id
        ));
        LogUtil::registerStatus($this->__('Cart item deleted'));
        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'serviceCart'));
    }

    public function proceedToCheckout($args)
    {
        // echo "heloooooo"; exit;
        $user_id  = UserUtil::getVar('uid');
        $user_id  = UserUtil::getVar('uid');
        $userinfo = DBUtil::selectObjectByID('users', $user_id, 'uid');
        $this->view->assign('userinfo', $userinfo);

        return $this->view->fetch('admin/checkout.tpl');
        // echo "<pre>"; print_r($cart); echo "</pre>"; exit;
    }

    public function insertBundleItemsDemo($args)
    {
        $bundleitems = $args ['bundleitems'];
        $timer_days  = $args ['timer_days'];
        $shop_id     = $args ['shop_id'];
        $owner_id    = $args ['owner_id'];
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        foreach ($bundleitems as $key => $val) {
            $count      = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount',
                    $count_args = array(
                    'table' => 'zselex_serviceshop',
                    'where' => "shop_id=$shop_id AND type='".$val [servicetype]."'"
            ));

            // echo $count; exit;
            if ($count > 0) {
                $get_qnty   = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow',
                        array(
                        'table' => 'zselex_serviceshop',
                        'fields' => array(
                            'quantity'
                        ),
                        'where' => array(
                            "shop_id='".$shop_id."'",
                            "type='".$val [servicetype]."'"
                        )
                ));
                // echo "<pre>"; print_r($get_qnty); echo "</pre>"; exit;
                $qty_update = $get_qnty ['quantity'] + $val ['qty'];
                $pntables   = pnDBGetTables();
                $column     = $pntables ['zselex_serviceshop_column'];
                $obj        = array(
                    'original_quantity' => $qty_update,
                    'quantity' => $qty_update
                );
                $where      = "WHERE $column[shop_id]=$shop_id  AND $column[type]='".$val [servicetype]."'";
                DBUTil::updateObject($obj, 'zselex_serviceshop', $where);
            } else {
                $item      = array(
                    'shop_id' => $shop_id,
                    'user_id' => $args ['user_id'],
                    'owner_id' => $owner_id,
                    'plugin_id' => $val ['plugin_id'],
                    'type' => $val ['servicetype'],
                    'original_quantity' => $val ['qty'],
                    'quantity' => $val ['qty'],
                    'status' => '1',
                    'service_status' => '1',
                    'qty_based' => $val ['qty_based'],
                    'bundle' => '1',
                    'bundle_id' => $val ['bundle_id'],
                    'timer_date' => $args ['timer_date'],
                    'timer_days' => $timer_days
                );
                $argsitems = array(
                    'table' => 'zselex_serviceshop',
                    'element' => $item,
                    'Id' => 'id'
                );
                // Create the zselex type
                $result    = ModUtil::apiFunc('ZSELEX', 'admin',
                        'createElement', $argsitems);

                if ($val ['servicetype'] == 'minishop') {
                    $m_arg         = array(
                        'table' => 'zselex_minishop',
                        'where' => "shop_id=$shop_id",
                        'Id' => 'id'
                    );
                    $minishopCount = ModUtil::apiFunc('ZSELEX', 'admin',
                            'countElements', $m_arg);
                    if ($minishopCount < 1) {
                        if ($result) { // configure as ishop as defauly if its a 'minishop' service
                            $item_minishop = array(
                                'shop_id' => $shop_id,
                                'shoptype_id' => 2,
                                'shoptype' => 'iSHOP',
                                'minishop_name' => 'My Ishop',
                                'description' => '',
                                'configured' => 1
                            );
                            $args_minishop = array(
                                'table' => 'zselex_minishop',
                                'element' => $item_minishop,
                                'Id' => 'id'
                            );

                            $insert_minishop = ModUtil::apiFunc('ZSELEX',
                                    'admin', 'createElement', $args_minishop);
                        }
                    }
                }

                $itemdemo   = array(
                    'shop_id' => $args ['shop_id'],
                    'plugin_id' => $val ['plugin_id'],
                    'type' => $val ['servicetype'],
                    'user_id' => $args ['user_id'],
                    'owner_id' => $args ['owner_id'],
                    'quantity' => $val ['qty'],
                    'qty_based' => $val ['qty_based'],
                    'bundle' => '1',
                    'bundle_id' => $val ['bundle_id'],
                    'status' => self::DEMO,
                    'start_date' => $args ['timer_date'],
                    'timer_days' => $timer_days
                );
                $argsdemo   = array(
                    'table' => 'zselex_service_demo',
                    'element' => $itemdemo,
                    'Id' => 'demo_id'
                );
                $resultdemo = ModUtil::apiFunc('ZSELEX', 'admin',
                        'createElement', $argsdemo);
            }
        }

        return true;
    }

    public function updateBundleItemsDemo($args)
    {
        // echo "items comes here"; exit;
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $bundleitems = $args ['bundleitems'];
        $timer_days  = $args ['timer_days'];
        foreach ($bundleitems as $key => $val) {
            $get      = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                    $getargs  = array(
                    'table' => 'zselex_serviceshop',
                    'fields' => array(
                        'id',
                        'original_quantity',
                        'quantity'
                    ),
                    'where' => "shop_id=$args[shop_id] AND type='".$val [servicetype]."'"
            ));
            $pntables = pnDBGetTables();
            $column   = $pntables ['zselex_serviceshop_column'];
            $obj      = array(
                // 'timer_days' => $timer_days,
                'original_quantity' => ($val ['qty_based'] == 1) ? $get ['original_quantity']
                    + $val ['qty'] : 1,
                'quantity' => ($val ['qty_based'] == 1) ? $get ['quantity'] + $val ['qty']
                        : 1
            );
            $where    = "WHERE $column[shop_id]=$args[shop_id] AND $column[type] ='".$val [servicetype]."' AND $column[service_status]='".self::DEMO."'";
            DBUTil::updateObject($obj, 'zselex_serviceshop', $where);

            $columndemo = $pntables ['zselex_service_demo_column'];
            $obj        = array(
                // 'timer_days' => $timer_days,
                'original_quantity' => ($val ['qty_based'] == 1) ? $get ['original_quantity']
                    + $val ['qty'] : 1,
                'quantity' => ($val ['qty_based'] == 1) ? $get ['quantity'] + $val ['qty']
                        : 1
            );
            $where      = "WHERE $columndemo[shop_id]=$args[shop_id] AND $columndemo[type] = '".$val [servicetype]."' AND $columndemo[status]='".self::DEMO."'";
            DBUTil::updateObject($obj, 'zselex_service_demo', $where);
        }
    }

    public function demoCart($args)
    {

        // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
        $user_id      = UserUtil::getVar('uid');
        $serviceId    = FormUtil::getPassedValue('serviceId');
        $serviceType  = FormUtil::getPassedValue('servicetype');
        $shop_id      = FormUtil::getPassedValue('shop_id');
        $qtybased     = FormUtil::getPassedValue('qty_based');
        $quantity     = FormUtil::getPassedValue('cart_quantity');
        $servicePrice = FormUtil::getPassedValue('serviceprice');
        $bundle       = FormUtil::getPassedValue('bundle');
        $bundle_id    = FormUtil::getPassedValue('bundle_id');
        $top_bundle   = FormUtil::getPassedValue('top_bundle');
        $timer_days   = FormUtil::getPassedValue('demoperiod');
        $owner        = DBUtil::selectObjectByID('zselex_shop_owners', $shop_id,
                'shop_id');
        $owner_id     = $owner ['user_id'];
        $source       = FormUtil::getPassedValue('source');

        // echo "<pre>"; print_r($xpl); exit;
        // $count = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $args = array('table' => 'zselex_basket', "where" => "user_id=$user_id AND shop_id=$shop_id AND type='" . $serviceType . "'"));
        // $count = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $args = array('table' => 'zselex_serviceshop',
        // "where" => "shop_id=$shop_id AND type='" . $serviceType . "' AND service_status='" . self::DEMO . "'"));
        // $count = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $args = array('table' => 'zselex_service_demo',
        // "where" => "shop_id=$shop_id AND type='" . $serviceType . "' AND plugin_id=$serviceId AND status='" . self::DEMO . "'"));
        // ////////check if the service is already paid//////////////////////

        if ($top_bundle) {
            // echo "hellooooo"; exit;
            $bundle_details = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                    $getargs        = array(
                    'table' => 'zselex_service_bundles',
                    'where' => "bundle_id=$bundle_id"
            ));
            // echo "<pre>"; print_r($bundle_details); exit;
            if ($bundle_details ['bundle_type'] != 'additional') {
                DBUtil::deleteWhere('zselex_serviceshop',
                    $where = "shop_id=$shop_id AND top_bundle=1");
                DBUtil::deleteWhere('zselex_service_demo',
                    $where = "shop_id=$shop_id AND top_bundle=1");
                DBUtil::deleteWhere('zselex_service_config',
                    $where = "shop_id=$shop_id AND top_bundle=1");
            }
        }

        $count = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount',
                $args  = array(
                'table' => 'zselex_serviceshop',
                'where' => "shop_id=$shop_id AND type='".$serviceType."' AND service_status='".self::PAID."'"
        ));

        if ($count > 0) {
            LogUtil::registerStatus($this->__('This Service is currently running as paid'));
            $this->redirect(ModUtil::url('ZSELEX', 'admin', 'purchaseservices',
                    array(
                    'shop_id' => $shop_id
            )));
        }
        // ////////////////////////////////////////////////////////////////////
        $demodays = ModUtil::apiFunc('ZSELEX', 'admin', 'demoDateCheck',
                $args     = array(
                'type' => $serviceType,
                'plugin_id' => $serviceId,
                'user_id' => $user_id,
                'shop_id' => $shop_id,
                'demo' => self::DEMO
        ));

        // echo "<pre>"; print_r($demodays); exit;
        // echo $demodays['demo']; exit;
        // if ($count > 0) {
        $approvebundlesitems = array();

        if ($demodays ['demo'] == 1) { // In Demo
            $count = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount',
                    $args  = array(
                    'table' => 'zselex_serviceshop',
                    'where' => "shop_id=$shop_id AND type='".$serviceType."' AND plugin_id=$serviceId AND service_status='".self::DEMO."'"
            ));

            // echo $count; exit;
            if ($count < 1) {
                // quantity+$value[quantity]
                if (($top_bundle == 1 && $bundle_details ['bundle_type'] != 'additional')
                    || $top_bundle != 1) {
                    $item    = array(
                        'shop_id' => $shop_id,
                        'user_id' => $user_id,
                        'owner_id' => $owner_id,
                        'plugin_id' => $serviceId,
                        'type' => $serviceType,
                        'original_quantity' => $quantity,
                        'quantity' => $quantity,
                        'status' => '1',
                        'service_status' => self::DEMO,
                        'qty_based' => $qtybased,
                        'bundle' => $bundle,
                        'bundle_id' => $bundle_id,
                        'top_bundle' => $top_bundle,
                        'timer_date' => $demodays ['demodate'],
                        'timer_days' => $timer_days
                    );
                    // echo "<pre>"; print_r($item); echo "</pre>"; exit;
                    $args    = array(
                        'table' => 'zselex_serviceshop',
                        'element' => $item,
                        'Id' => 'id'
                    );
                    $result1 = ModUtil::apiFunc('ZSELEX', 'admin',
                            'createElement', $args);
                }
                // ///////insert bundle items if its a bundle service//////////////////////
                if ($bundle == 1) {
                    $values                 = array(
                        'bundle' => $bundle,
                        'service_status' => self::DEMO,
                        'user_id' => $user_id,
                        'owner_id' => $owner_id,
                        'shop_id' => $shop_id,
                        'timer_date' => $demodays ['demodate']
                    );
                    $bundleitems            = ModUtil::apiFunc('ZSELEX', 'user',
                            'selectArray',
                            $argsbundleitems        = array(
                            'table' => 'zselex_service_bundle_items',
                            'where' => array(
                                'bundle_id='.$bundle_id
                            )
                    ));
                    $values ['bundleitems'] = $bundleitems;
                    $values ['timer_days']  = $timer_days;
                    // echo "<pre>"; print_r($values);exit;
                    $approvebundlesitems    = $this->insertBundleItemsDemo($values);
                }

                // ////////////////////////////////////////////////////////////////////////
            } else { // update....
                // echo "comes here"; exit;
                // echo "<pre>"; print_r($demodays); exit;
                if (($top_bundle == 1 && $bundle_details ['bundle_type'] != 'additional')
                    || $top_bundle != 1) {
                    $pntables = pnDBGetTables();
                    $column   = $pntables ['zselex_serviceshop_column'];

                    $obj   = array(
                        // 'timer_days' => $timer_days,
                        'original_quantity' => ($qtybased == 0) ? 1 : $demodays [quantity]
                            + $quantity,
                        'quantity' => ($qtybased == 0) ? 1 : $demodays [quantity]
                            + $quantity
                    );
                    $where = "WHERE $column[shop_id]=$shop_id AND $column[plugin_id]=$serviceId AND $column[service_status]='".self::DEMO."'";
                    DBUTil::updateObject($obj, 'zselex_serviceshop', $where);
                }

                $columndemo = $pntables ['zselex_service_demo_column'];
                $obj        = array(
                    // 'timer_days' => $timer_days,
                    'original_quantity' => ($qtybased == 0) ? $quantity : $demodays [quantity]
                        + $quantity,
                    'quantity' => ($qtybased == 0) ? $quantity : $demodays [quantity]
                        + $quantity
                );
                $where      = "WHERE $columndemo[shop_id]=$shop_id AND $columndemo[plugin_id]=$serviceId AND $columndemo[status]='".self::DEMO."'";
                DBUTil::updateObject($obj, 'zselex_service_demo', $where);

                if ($bundle == 1) {
                    $values                 = array(
                        'bundle' => $bundle,
                        'service_status' => self::DEMO,
                        'user_id' => $user_id,
                        'owner_id' => $owner_id,
                        'shop_id' => $shop_id,
                        'timer_date' => $demodays ['demodate'],
                        'quantity' => $demodays [quantity]
                    );
                    $bundleitems            = ModUtil::apiFunc('ZSELEX', 'user',
                            'selectArray',
                            $argsbundleitems        = array(
                            'table' => 'zselex_service_bundle_items',
                            'where' => array(
                                'bundle_id='.$bundle_id
                            )
                    ));
                    $values ['bundleitems'] = $bundleitems;
                    $values ['timer_days']  = $timer_days;
                    // echo "<pre>"; print_r($values);exit;
                    $approvebundlesitems    = $this->updateBundleItemsDemo($values);
                }
            }

            LogUtil::registerStatus($this->__('Service configured and updated for demo'));
            // LogUtil::registerError(nl2br('This service is already used as demo'));
            if ($source == 'quickbuy') {
                $this->redirect(ModUtil::url('ZSELEX', 'admin', 'quickbuy',
                        array(
                        'shop_id' => $shop_id
                )));
            } elseif ($source == 'myservice') {
                $this->redirect(ModUtil::url('ZSELEX', 'admin', 'shopservices',
                        array(
                        'shop_id' => $shop_id
                )));
            } else {
                $this->redirect(ModUtil::url('ZSELEX', 'admin',
                        'purchaseservices',
                        array(
                        'shop_id' => $shop_id
                )));
            }
        } else { // Insert Fresh Demo. nothing in service table!
            // echo "comes here"; exit;
            // echo "<pre>"; print_r($bundle_details); echo "</pre>"; exit;
            // exit;
            $owner     = DBUtil::selectObjectByID('zselex_shop_owners',
                    $shop_id, 'shop_id');
            $owner_id  = $owner ['user_id'];
            $date      = date('Y-m-d');
            $demoExist = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount',
                    $args      = array(
                    'table' => 'zselex_serviceshop',
                    'where' => "shop_id=$shop_id AND type='".$serviceType."'  AND service_status='".self::DEMO."'"
            ));

            $item         = array(
                'shop_id' => $shop_id,
                'plugin_id' => $serviceId,
                'type' => $serviceType,
                'user_id' => $user_id,
                'owner_id' => $owner_id,
                'original_quantity' => $quantity,
                'quantity' => $quantity,
                'qty_based' => $qtybased,
                'bundle' => $bundle,
                'bundle_id' => $bundle_id,
                'top_bundle' => $top_bundle,
                'status' => self::DEMO,
                'start_date' => $date,
                'timer_days' => $timer_days
            );
            // echo "<pre>"; print_r($item); exit;
            $args         = array(
                'table' => 'zselex_service_demo',
                'element' => $item,
                'Id' => 'demo_id'
            );
            $result       = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement',
                    $args); // Insert To Demo Table
            $objconfig    = array(
                'shop_id' => $shop_id,
                'plugin_id' => $serviceId,
                'type' => $serviceType,
                'user_id' => $user_id,
                'owner_id' => $owner_id,
                'service_status' => self::DEMO,
                'qty_based' => $qtybased,
                'bundle' => $bundle,
                'bundle_id' => $bundle_id,
                'top_bundle' => $top_bundle,
                'start_date' => $date
            );
            $insertConfig = DBUtil::insertObject($objconfig,
                    'zselex_service_config'); // Insert To Config Table
            if (($top_bundle == 1 && $bundle_details ['bundle_type'] != 'additional')
                || $top_bundle != 1) {
                $item1 = array(
                    'shop_id' => $shop_id,
                    'user_id' => $user_id,
                    'owner_id' => $owner_id,
                    'plugin_id' => $serviceId,
                    'type' => $serviceType,
                    'original_quantity' => $quantity,
                    'quantity' => $quantity,
                    'status' => '1',
                    'service_status' => self::DEMO,
                    'qty_based' => $qtybased,
                    'bundle' => $bundle,
                    'bundle_id' => $bundle_id,
                    'top_bundle' => $top_bundle,
                    'timer_date' => $date,
                    'timer_days' => $timer_days
                );
                $args1 = array(
                    'table' => 'zselex_serviceshop',
                    'element' => $item1,
                    'Id' => 'id'
                );

                $result1 = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement',
                        $args1);

                if ($serviceType == 'minishop') {
                    $m_arg         = array(
                        'table' => 'zselex_minishop',
                        'where' => "shop_id=$shop_id",
                        'Id' => 'id'
                    );
                    $minishopCount = ModUtil::apiFunc('ZSELEX', 'admin',
                            'countElements', $m_arg);

                    // echo $minishopCount; exit;
                    if ($minishopCount < 1) {
                        if ($result1) { // configure as ishop as defauly if its a 'minishop' service
                            $item_minishop = array(
                                'shop_id' => $shop_id,
                                'shoptype_id' => 2,
                                'shoptype' => 'iSHOP',
                                'minishop_name' => 'My Ishop',
                                'description' => '',
                                'configured' => 1
                            );
                            $args_minishop = array(
                                'table' => 'zselex_minishop',
                                'element' => $item_minishop,
                                'Id' => 'id'
                            );

                            $insert_minishop = ModUtil::apiFunc('ZSELEX',
                                    'admin', 'createElement', $args_minishop);
                        }
                    }
                } elseif ($serviceType == 'eventservice') {

                    // $ev_arg = array('shop_id' => $shop_id, 'quantity' => $quantity);
                    // $this->createEventAuto($ev_arg);
                }
            }

            if ($result) {
                // ///////insert bundle items if its a bundle service//////////////////////
                if ($bundle == 1) {
                    $values                 = array(
                        'bundle' => $bundle,
                        'service_status' => self::DEMO,
                        'user_id' => $user_id,
                        'owner_id' => $owner_id,
                        'shop_id' => $shop_id,
                        'timer_date' => $date
                    );
                    // echo "<pre>"; print_r($values); exit;
                    $bundleitems            = ModUtil::apiFunc('ZSELEX', 'user',
                            'selectArray',
                            $argsbundleitems        = array(
                            'table' => 'zselex_service_bundle_items',
                            'where' => array(
                                'bundle_id='.$bundle_id
                            )
                    ));
                    $values ['bundleitems'] = $bundleitems;
                    $values ['timer_days']  = $timer_days;
                    // echo "<pre>"; print_r($values); exit;
                    $approvebundlesitems    = $this->insertBundleItemsDemo($values);
                }

                // ////////////////////////////////////////////////////////////////////////
            }

            LogUtil::registerStatus($this->__('Service configured for demo'));
            // echo $source; exit;
            if ($source == 'quickbuy') {
                $this->redirect(ModUtil::url('ZSELEX', 'admin', 'quickbuy',
                        array(
                        'shop_id' => $shop_id
                )));
            } elseif ($source == 'myservice') {
                $this->redirect(ModUtil::url('ZSELEX', 'admin', 'shopservices',
                        array(
                        'shop_id' => $shop_id
                )));
            } else {
                $this->redirect(ModUtil::url('ZSELEX', 'admin',
                        'purchaseservices',
                        array(
                        'shop_id' => $shop_id
                )));
            }
        }
    }

    public function addService($args)
    { // new add to demo function
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
        $bundleId      = FormUtil::getPassedValue('bundle_id');
        $repo          = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $getBundleArgs = array('entity' => 'ZSELEX_Entity_Bundle', 'where' => array(
                'a.bundle_id' => $bundleId));
        $getBundle     = $repo->get($getBundleArgs);
        // echo "<pre>"; print_r($getBundle); echo "</pre>"; exit;
        $user_id       = UserUtil::getVar('uid');
        if ($args ['service_status'] != 2) {
            // demo
            // echo "Demo!"; exit;
            // $serviceId = FormUtil::getPassedValue("serviceId");
            // $serviceType = FormUtil::getPassedValue("servicetype");
            $shop_id        = FormUtil::getPassedValue('shop_id');
            // $qtybased = FormUtil::getPassedValue("qty_based");
            $quantity       = FormUtil::getPassedValue('cart_quantity');
            // $servicePrice = FormUtil::getPassedValue("serviceprice");
            // $bundle = FormUtil::getPassedValue("bundle");
            $bundle_id      = FormUtil::getPassedValue('bundle_id');
            $top_bundle     = FormUtil::getPassedValue('top_bundle');
            // $bundle_type = FormUtil::getPassedValue("bundle_type");
            $timer_days     = FormUtil::getPassedValue('demoperiod');
            $service_status = FormUtil::getPassedValue('service_status');
            /*
             * $paidRunning = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', array(
             * 'table' => 'zselex_serviceshop_bundles',
             * "where" => "shop_id=$shop_id AND bundle_type='main' AND service_status=2"
             * ));
             */

            $paidRunning = $repo->getCount(null, 'ZSELEX_Entity_ServiceBundle',
                'service_bundle_id',
                array(
                'a.shop' => $shop_id,
                'a.bundle_type' => 'main',
                'a.service_status' => 2
            ));

            // echo $paidRunning; exit;
            if ($paidRunning) {
                LogUtil::registerError($this->__('You already have a paid service'));
                $this->redirect(ModUtil::url('ZSELEX', 'admin', 'shopservices',
                        array(
                        'shop_id' => $shop_id
                )));
            }
        } else {
            // paid
            // $user_id = UserUtil::getVar('uid');
            if (!$user_id) {
                $user_id = $args ['user_id'];
            }
            $shop_id        = $args ['shop_id'];
            $quantity       = $args ['quantity'];
            $bundle_id      = $args ['bundle_id'];
            $top_bundle     = 1;
            $timer_days     = $args ['timer_days'];
            $service_status = 2;

            // DBUtil::deleteWhere('zselex_serviceshop_bundles', $where = "shop_id=$shop_id AND service_status='1'");
            // DBUtil::deleteWhere('zselex_serviceshop', $where = "shop_id=$shop_id AND service_status='1'");
            $repo->deleteEntity(null, 'ZSELEX_Entity_ServiceBundle',
                array(
                'a.shop' => $shop_id,
                'a.service_status' => 1
            ));
            $repo->deleteEntity(null, 'ZSELEX_Entity_ServiceShop',
                array(
                'a.shop' => $shop_id,
                'a.service_status' => 1
            ));
        }

        //  echo "<pre>"; print_r($args); echo "</pre>"; exit;

        $ownerInfo = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwnerInfo',
                $args      = array(
                'shop_id' => $shop_id
        ));
        // echo "<pre>"; print_r($ownerInfo); echo "</pre>"; exit;
        // $owner = DBUtil::selectObjectByID('zselex_shop_owners', $shop_id, 'shop_id');
        // $owner_id = $owner['user_id'];
        $owner_id  = $ownerInfo ['user_id'];
        $source    = FormUtil::getPassedValue('source');

        // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;

        /*
         * $bundle_detail = ModUtil::apiFunc('ZSELEX', 'user', 'get', $getargs =
         * array('table' => 'zselex_service_bundles',
         * 'where' => "bundle_id=$bundle_id"));
         */

        $bundle_detail = $repo->get(array(
            'entity' => 'ZSELEX_Entity_Bundle',
            'where' => array(
                'a.bundle_id' => $bundle_id
            )
        ));

        // echo "<pre>"; print_r($bundle_detail); echo "</pre>"; exit;

        $servicePrice = $bundle_detail ['bundle_price'];
        $bundle_type  = $bundle_detail ['bundle_type'];
        $bundle_name  = $bundle_detail ['bundle_name'];
        // $timer_days = $bundle_detail["demoperiod"];
        // $service_status = 1;
        // echo "<pre>"; print_r($bundle_detail); echo "</pre>"; exit;

        $date = date('Y-m-d');

        // echo $count; exit;
        if ($bundle_type != 'additional') { // if its a main bundlle then delete the existing main bundle
            // echo "comes here!"; exit;
            // DBUtil::deleteWhere('zselex_serviceshop_bundles', $where = "shop_id=$shop_id AND bundle_type='main'");
            $repo->deleteEntity(null, 'ZSELEX_Entity_ServiceBundle',
                array(
                'a.shop' => $shop_id,
                'a.bundle_type' => 'main'
            ));
            // }
            // DBUtil::deleteWhere('zselex_service_demo', $where = "shop_id=$shop_id AND top_bundle=1");
            // DBUtil::deleteWhere('zselex_service_config', $where = "shop_id=$shop_id AND top_bundle=1");
        } else {
            $main_bundle = ModUtil::apiFunc('ZSELEX', 'admin',
                    'bundlePaidExpiryCheck',
                    $args        = array(
                    'shop_id' => $shop_id
            ));

            // echo "<pre>"; print_r($main_bundle); echo "</pre>"; exit;
            if (!$main_bundle ['running']) {
                // echo "no main bundle"; exit;
                LogUtil::registerError($this->__('You dont have a valid main bundle.'));
                $this->redirect(ModUtil::url('ZSELEX', 'admin', 'shopservices',
                        array(
                        'shop_id' => $shop_id
                )));
            }
        }

        // exit;

        $count = $repo->getCount(null, 'ZSELEX_Entity_ServiceBundle',
            'service_bundle_id',
            array(
            'a.shop' => $shop_id,
            'a.bundle' => $bundle_id
        ));

        // echo $count; exit;

        if ($count < 1) { // main doesnt exist . only additional bundles could be more than 1
            $servcBndle = new ZSELEX_Entity_ServiceBundle ();
            $bundleObj  = $this->entityManager->find('ZSELEX_Entity_Bundle',
                $bundle_id);
            $servcBndle->setBundle($bundleObj);
            $servcBndle->setBundle_name($bundle_name);
            $shopObj    = $this->entityManager->find('ZSELEX_Entity_Shop',
                $shop_id);
            $servcBndle->setShop($shopObj);
            $servcBndle->setOriginal_quantity($quantity);
            $servcBndle->setQuantity($quantity);
            $servcBndle->setService_status($service_status);
            $servcBndle->setBundle_type($bundle_type);
            $servcBndle->setTimer_date(date_create($date));
            $servcBndle->setTimer_days($timer_days);
            $this->entityManager->persist($servcBndle);
            $this->entityManager->flush();
            $result     = $servcBndle->getService_bundle_id();
        } else {
            // echo "comes here"; exit;

            $get = $repo->get(array(
                'entity' => 'ZSELEX_Entity_ServiceBundle',
                'where' => array(
                    'a.bundle' => $bundle_id,
                    'a.shop' => $shop_id
                )
            ));
            // echo "<pre>"; print_r($get); echo "</pre>"; exit;
            // $pntables = pnDBGetTables();
            // $column = $pntables['zselex_serviceshop_bundles_column'];

            $obj = array(
                'original_quantity' => $get ['quantity'] + 1,
                'quantity' => $get ['quantity'] + $quantity,
                'timer_date' => $date,
                'timer_days' => $timer_days
            );
            /*
             * $where = "WHERE $column[shop_id]=$shop_id AND $column[bundle_id]=$bundle_id";
             * $result = DBUTil::updateObject($obj, 'zselex_serviceshop_bundles', $where);
             */

            $result = $repo->updateEntity(null, 'ZSELEX_Entity_ServiceBundle',
                $obj,
                array(
                'a.shop' => $shop_id,
                'a.bundle' => $bundle_id
            ));
        }

        // exit;

        if ($result) {

            /*
             * $main_bundle = ModUtil::apiFunc('ZSELEX', 'user', 'get', array('table' => 'zselex_serviceshop_bundles',
             * 'where' => "shop_id=$shop_id AND bundle_type='main'",
             * 'fields' => array('service_bundle_id', 'bundle_id')
             * ));
             */

            // echo "<pre>"; print_r($main_bundle); echo "</pre>"; exit;
            $main_bundle = $repo->get(array(
                'entity' => 'ZSELEX_Entity_ServiceBundle',
                'fields' => array(
                    'a.service_bundle_id',
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
            // echo "<pre>"; print_r($main_bundle); echo "</pre>"; exit;
            if ($service_status == 1) { // only for demo
                if ($count < 1) { // doesnt exists.
                    // Insert to demo table. (23-01-14) edited.
                    // $demoCount = $repo->getCount(null, 'ZSELEX_Entity_ServiceDemo', 'demo_id', array('a.shop' => $shop_id, 'a.bundle' => $bundle_id));
                    if ($bundle_type == 'main') {
                        $demoCount = $repo->getCount(null,
                            'ZSELEX_Entity_ServiceDemo', 'demo_id',
                            array(
                            'a.shop' => $shop_id,
                            'a.bundle_type' => 'main'
                        ));
                        // echo "demoCount: ". $demoCount; exit;
                        if (!$demoCount) {
                            $servcDemo = new ZSELEX_Entity_ServiceDemo ();
                            $shopObj   = $this->entityManager->find('ZSELEX_Entity_Shop',
                                $shop_id);
                            $servcDemo->setShop($shopObj);
                            $servcDemo->setPlugin_id($serviceId);
                            $servcDemo->setType($serviceType);
                            $servcDemo->setUser_id($user_id);
                            $servcDemo->setOwner_id($owner_id);
                            $servcDemo->setQuantity($quantity);
                            $servcDemo->setQty_based($quantity);
                            $servcDemo->setIs_bundle($bundle);
                            $bundleObj = $this->entityManager->find('ZSELEX_Entity_Bundle',
                                $bundle_id);
                            $servcDemo->setBundle($bundleObj);
                            $servcDemo->setTop_bundle($top_bundle);
                            $servcDemo->setBundle_type($bundle_type);
                            $servcDemo->setStatus($service_status);
                            $servcDemo->setStart_date(date_create($date));
                            $servcDemo->setTimer_days($timer_days);
                            $this->entityManager->persist($servcDemo);
                            $this->entityManager->flush();
                            $result    = $servcDemo->getDemo_id();
                        } else {
                            $updateDemoItem = array(
                                'bundle' => $bundle_id
                            );
                            $updateDemo     = $repo->updateEntity(null,
                                'ZSELEX_Entity_ServiceDemo', $updateDemoItem,
                                array(
                                'a.shop' => $shop_id,
                                'a.bundle_type' => 'main'
                            ));
                        }
                    }
                }
            }
            // if ($bundle == 1) {
            $values = array(
                'bundle' => $bundle,
                'service_status' => $service_status,
                'user_id' => $user_id,
                'owner_id' => $owner_id,
                'shop_id' => $shop_id,
                // 'main_bundle' => $main_bundle['bundle_id'],
                'timer_date' => $date
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
            $values ['bundleitems']    = $bundleitems;
            $values ['timer_days']     = $timer_days;
            $values ['bundle_type']    = $bundle_type;
            $values ['bundle_id']      = $bundle_id;
            // $values['bundle_id'] = $main_bundle['bundle_id'];
            $values ['shop_id']        = $shop_id;
            $values ['service_status'] = $service_status;

            // echo "<pre>"; print_r($values);exit;
            $approvebundlesitems = $this->insertBundleItems($values);
            // }
        }

        if ($service_status == 1) {
            LogUtil::registerStatus($this->__('Service configured for demo'));
        } else {
            // LogUtil::registerStatus($this->__('Service configured successfully'));
            return true;
        }
        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'shopservices',
                array(
                'shop_id' => $shop_id
        )));
    }

    public function bundleValidate($args)
    {
        $bundle_id = $args ['bundle_id'];
    }

    public function insertBundleItems($args)
    {
        $insert = ModUtil::apiFunc('ZSELEX', 'service', 'insertBundleItems',
                $args);

        return $insert;
    }

    public function getAdditionalBundleQty($args)
    {
        /*
         * $repo = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
         * $qty = $args['qty'];
         * $type = $args['type'];
         * $shop_id = $args['shop_id'];
         *
         *
         * $additional = $repo->getAll(
         * array(
         * 'entity' => 'ZSELEX_Entity_ServiceBundle',
         * 'where' => array('a.bundle_type' => 'additional', 'a.shop' => $shop_id),
         * 'joins' => array('JOIN a.bundle b'),
         * 'fields' => array('a.quantity', 'b.bundle_id')
         * ));
         * if ($additional) {
         * foreach ($additional as $key) {
         *
         * $additional_qty = $repo->get(array(
         * 'entity' => 'ZSELEX_Entity_BundleItem',
         * 'where' => array('a.servicetype' => $type, 'a.bundle' => $key['bundle_id']),
         * 'fields' => array('a.qty')
         * ));
         * if ($additional_qty) {
         * $qty+=$additional_qty['qty'] * $key['quantity'];
         * }
         * }
         * }
         *
         * return $qty;
         */
        $qty = ModUtil::apiFunc('ZSELEX', 'service', 'getAdditionalBundleQty',
                $args);

        return $qty;
    }

    public function configurePaidService($args)
    { // new add to demo function
        // echo "here"; exit;
        $user_id    = UserUtil::getVar('uid');
        // $serviceId = FormUtil::getPassedValue("serviceId");
        // $serviceType = FormUtil::getPassedValue("servicetype");
        $shop_id    = $args ['shop_id'];
        // $qtybased = FormUtil::getPassedValue("qty_based");
        $quantity   = $args ['quantity'];
        // $servicePrice = FormUtil::getPassedValue("serviceprice");
        // $bundle = FormUtil::getPassedValue("bundle");
        $bundle_id  = $args ['bundle_id'];
        $top_bundle = $args ['top_bundle'];
        // $bundle_type = FormUtil::getPassedValue("bundle_type");
        $timer_days = $args ['timer_days'];
        // $service_status = FormUtil::getPassedValue("service_status");
        $owner      = DBUtil::selectObjectByID('zselex_shop_owners', $shop_id,
                'shop_id');
        $owner_id   = $owner ['user_id'];
        $source     = $args ['source'];

        // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;

        $bundle_detail = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                $getargs       = array(
                'table' => 'zselex_service_bundles',
                'where' => "bundle_id=$bundle_id"
        ));

        // echo "<pre>"; print_r($bundle_detail); echo "</pre>"; exit;

        $servicePrice   = $bundle_detail ['bundle_price'];
        $bundle_type    = $bundle_detail ['bundle_type'];
        $bundle_name    = $bundle_detail ['bundle_name'];
        // $timer_days = $bundle_detail["demoperiod"];
        $service_status = 2;

        // echo "<pre>"; print_r($bundle_detail); echo "</pre>"; exit;

        $date = date('Y-m-d');

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
                'where' => "shop_id=$shop_id AND bundle_id='".$bundle_id."'"
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
                        'bundle_id='.$bundle_id
                    )
            ));
            $values ['bundleitems'] = $bundleitems;
            $values ['timer_days']  = $timer_days;
            $values ['bundle_type'] = $bundle_type;
            $values ['bundle_id']   = $bundle_id;
            $values ['shop_id']     = $shop_id;
            // echo "<pre>"; print_r($values);exit;
            $approvebundlesitems    = $this->insertBundleItemsPaid($values);
            // }
        }

        LogUtil::registerStatus($this->__('Service configured for paid!'));
        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'shopservices',
                array(
                'shop_id' => $shop_id
        )));
    }

    public function insertBundleItemsPaid($args)
    {
        $bundleitems = $args ['bundleitems'];
        $timer_days  = $args ['timer_days'];
        $shop_id     = $args ['shop_id'];
        $owner_id    = $args ['owner_id'];
        $bundle_type = $args ['bundle_type'];
        $bundle_id   = $args ['bundle_id'];
        $shop_id     = $args ['shop_id'];
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        foreach ($bundleitems as $key => $val) {
            $count      = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount',
                    $count_args = array(
                    'table' => 'zselex_serviceshop',
                    'where' => "shop_id=$shop_id AND type='".$val [servicetype]."'"
            ));

            // echo $count; exit;
            if ($count > 0) { // already exists then update
                // echo $val[servicetype]; exit;
                $service_plugin = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                        $getargs        = array(
                        'table' => 'zselex_plugin',
                        'where' => "type='".$val [servicetype]."'"
                ));

                $get_qnty   = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow',
                        array(
                        'table' => 'zselex_serviceshop',
                        'fields' => array(
                            'quantity'
                        ),
                        'where' => array(
                            "shop_id='".$shop_id."'",
                            "type='".$val [servicetype]."'"
                        )
                ));
                // echo "<pre>"; print_r($get_qnty); echo "</pre>";
                // echo "<pre>"; print_r($service_plugin); echo "</pre>"; exit;
                $qty_update = $val ['qty'];
                $pntables   = pnDBGetTables();
                $column     = $pntables ['zselex_serviceshop_column'];

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
                $obj                    = array(
                    'original_quantity' => $new_qty,
                    'quantity' => $new_qty
                    )
                // 'timer_date' => $args['timer_date'],
                // 'timer_days' => $args['timer_days'],
                ;
                // if ($bundle_type == 'main') {
                // chnage timer date and days only if its a main bundle.
                $obj ['bundle_id']      = $bundle_id;
                $obj ['timer_date']     = $args ['timer_date'];
                $obj ['timer_days']     = $args ['timer_days'];
                $obj ['service_status'] = 2;
                // }
                $where                  = "WHERE $column[shop_id]=$shop_id  AND $column[type]='".$val [servicetype]."'";
                DBUTil::updateObject($obj, 'zselex_serviceshop', $where);
            } else { // add new - fresh record
                $updated_qty = $this->getAdditionalBundleQty(array(
                    'qty' => $val ['qty'],
                    'shop_id' => $shop_id,
                    'type' => $val [servicetype]
                ));
                $item        = array(
                    'shop_id' => $shop_id,
                    'user_id' => $args ['user_id'],
                    'owner_id' => $owner_id,
                    'plugin_id' => $val ['plugin_id'],
                    'type' => $val ['servicetype'],
                    'original_quantity' => $updated_qty,
                    'quantity' => $updated_qty,
                    'status' => '1',
                    'service_status' => '2',
                    'qty_based' => $val ['qty_based'],
                    'bundle' => '1',
                    'bundle_id' => $val ['bundle_id'],
                    'timer_date' => $args ['timer_date'],
                    'timer_days' => $args ['timer_days']
                );
                $argsitems   = array(
                    'table' => 'zselex_serviceshop',
                    'element' => $item,
                    'Id' => 'id'
                );
                // Create the zselex type
                $result      = ModUtil::apiFunc('ZSELEX', 'admin',
                        'createElement', $argsitems);

                if ($val ['servicetype'] == 'minishop') { // configure minishop direcly
                    $m_arg         = array(
                        'table' => 'zselex_minishop',
                        'where' => "shop_id=$shop_id",
                        'Id' => 'id'
                    );
                    $minishopCount = ModUtil::apiFunc('ZSELEX', 'admin',
                            'countElements', $m_arg);
                    if ($minishopCount < 1) {
                        if ($result) { // configure as ishop as defauly if its a 'minishop' service
                            $item_minishop = array(
                                'shop_id' => $shop_id,
                                'shoptype_id' => 2,
                                'shoptype' => 'iSHOP',
                                'minishop_name' => 'My Ishop',
                                'description' => '',
                                'configured' => 1
                            );
                            $args_minishop = array(
                                'table' => 'zselex_minishop',
                                'element' => $item_minishop,
                                'Id' => 'id'
                            );

                            $insert_minishop = ModUtil::apiFunc('ZSELEX',
                                    'admin', 'createElement', $args_minishop);
                        }
                    }
                }
            }
        }

        return true;
    }

    public function serviceTimer($running = 1)
    {
        $modvars     = $this->getVars();
        $invoice_day = ($modvars ['invoiceday'] == 0) ? 25 : $modvars ['invoiceday'];
        // $invoice_day = 15;
        $current_day = date('j');
        // echo "current day :" . $current_day; exit;
        $timer_days  = 0;
        // if (!$running) { // Expired / Never Bought
        // echo "timer_daye :" . $timer_days_for_billing; exit;
        if ($invoice_day > $current_day) {
            $timer_days = $invoice_day - $current_day;
        } else { // invoice day less than current day
            // echo "comes here"; exit;
            $remaining_days = date('t') - date('j'); // remaining days in the current month
            // echo "remianing days :" . $remaining_days; exit;
            $timer_days     = $remaining_days + $invoice_day;
        }
        // }
        // echo $timer_days; exit;
        return $timer_days;
    }

    public function serviceCart($args)
    {
        // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
        // echo UserUtil::getVar('email');
        // echo "comes here"; exit;
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        $repo                        = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $user_id                     = UserUtil::getVar('uid');
        $_SESSION ['order_id']       = '';
        $_SESSION ['payment_method'] = '';
        $_SESSION ['payment_option'] = '';
        unset($_SESSION ['order_id']);
        unset($_SESSION ['payment_method']);
        if ($_POST) {

            // // $serviceId = FormUtil::getPassedValue("serviceId");
            $serviceType = FormUtil::getPassedValue('servicetype');
            $shop_id     = FormUtil::getPassedValue('shop_id');
            $qtybased    = FormUtil::getPassedValue('qty_based');
            $quantity    = FormUtil::getPassedValue('cart_quantity');
            // echo $quantity; exit;
            // $servicePrice = FormUtil::getPassedValue("serviceprice");
            $price       = FormUtil::getPassedValue('serviceprice');
            $bundle      = FormUtil::getPassedValue('bundle');
            $bundle_id   = FormUtil::getPassedValue('bundle_id');

            /*
             * $bundle_detail = ModUtil::apiFunc('ZSELEX', 'user', 'get', $getargs =
             * array('table' => 'zselex_service_bundles',
             * 'where' => "bundle_id=$bundle_id"));
             */

            $bundle_detail = $repo->get(array(
                'entity' => 'ZSELEX_Entity_Bundle',
                'fields' => array(
                    'a.bundle_price',
                    'a.bundle_type',
                    'a.bundle_name'
                ),
                'where' => array(
                    'a.bundle_id' => $bundle_id
                )
            ));

            // echo "<pre>"; print_r($bundle_detail); echo "</pre>"; exit;

            $servicePrice = $bundle_detail ['bundle_price'];
            $bundle_type  = $bundle_detail ['bundle_type'];
            $bundle_name  = $bundle_detail ['bundle_name'];

            $top_bundle       = FormUtil::getPassedValue('top_bundle');
            $service_depended = FormUtil::getPassedValue('isDepended');

            if ($bundle_type == 'main') {
                // echo "main bundle"; exit;
                /*
                 * $main_bundle_exist = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $args = array(
                 * 'table' => 'zselex_basket',
                 * // "where" => "user_id=$user_id AND bundle_type='main'"
                 * "where" => "shop_id=$shop_id AND bundle_type='main'"
                 * ));
                 */

                $main_bundle_exist = $repo->getCount(null,
                    'ZSELEX_Entity_ServiceBasket', 'basket_id',
                    array(
                    'a.shop' => $shop_id,
                    'a.bundle_type' => 'main'
                ));

                // $main_bundle_exist = $repo->getCount(null , '');
                // echo $main_bundle_exist; exit;

                if ($main_bundle_exist) {
                    // $where = "user_id=$user_id AND bundle_type='main'";
                    /*
                     * $where = "shop_id=$shop_id AND bundle_type='main'";
                     * $deleteBasket = ModUtil::apiFunc('ZSELEX', 'admin', 'deleteWhere', $args = array(
                     * 'table' => 'zselex_basket',
                     * 'where' => $where
                     * ));
                     */
                    $deleteBasket = $repo->deleteEntity(null,
                        'ZSELEX_Entity_ServiceBasket',
                        array(
                        'a.shop' => $shop_id,
                        'a.bundle_type' => 'main'
                    ));
                }
            } elseif ($bundle_type == 'additional') {
                // echo "additional bundle"; exit;
                $main_bundle = ModUtil::apiFunc('ZSELEX', 'admin',
                        'bundlePaidExpiryCheck',
                        $args        = array(
                        'shop_id' => $shop_id
                ));

                // echo "<pre>"; print_r($main_bundle); echo "</pre>"; exit;
                if (!$main_bundle ['running']) {
                    // echo "no main bundle"; exit;
                    /*
                     * $where = "shop_id=$shop_id AND bundle_type='main'";
                     * $deleteBasket = ModUtil::apiFunc('ZSELEX', 'admin', 'deleteWhere', $args = array(
                     * 'table' => 'zselex_basket',
                     * 'where' => $where
                     * ));
                     */
                    $deleteBasket = $repo->deleteEntity(null,
                        'ZSELEX_Entity_ServiceBasket',
                        array(
                        'a.shop' => $shop_id,
                        'a.bundle_type' => 'main'
                    ));
                    LogUtil::registerError($this->__('You dont have a valid main bundle.'));
                    $this->redirect(ModUtil::url('ZSELEX', 'admin',
                            'shopservices',
                            array(
                            'shop_id' => $shop_id
                    )));
                }
            }

            // echo "comes here"; exit;

            if ($top_bundle == 1) { // bundle service
                $buystatus = ModUtil::apiFunc('ZSELEX', 'admin',
                        'canBuyStatusBundle',
                        $args      = array(
                        'shop_id' => $shop_id,
                        'bundleId' => $val ['bundle_id']
                ));
                // echo "<pre>"; print_r($plugins[$key]['bundlebuy']); echo "</pre>";
                $cantbuy   = $buystatus ['cantbuy'];
            } else { // normal service
                if ($service_depended == 1) {
                    $buystatus = ModUtil::apiFunc('ZSELEX', 'admin',
                            'canBuyStatus',
                            $args      = array(
                            'depended_services' => $val ['depended_services'],
                            'shop_id' => $shop_id,
                            'owner_id' => $owner_id
                    ));
                    $cantbuy   = $buystatus ['cantbuy'];
                    // echo $plugins[$key]['cantbuy'] . '<br>';
                    // echo "<pre>"; print_r($plugins[$key]['buy']); echo "</pre>";
                } else {
                    $cantbuy = '0';
                }
            }

            if ($cantbuy > 0) {
                LogUtil::registerStatus($this->__('Sorry! Cannot buy this service now'));
                $this->redirect(ModUtil::url('ZSELEX', 'admin', 'shopservices',
                        array(
                        'shop_id' => $shop_id
                )));
            }

            $serviceargs            = array(
                'shop_id' => $shop_id,
                'bundle_id' => $bundle_id
            );
            $serviceCheck           = ModUtil::apiFunc('ZSELEX', 'admin',
                    'serviceCheckCartBundle', $serviceargs);
            // echo "<pre>"; print_r($serviceCheck); echo "</pre>"; exit;
            $todayDate              = date('Y-m-d');
            $remaining_days         = date('t') - date('j'); // remaining days in the current month
            $modvars                = $this->getVars();
            $invoice_day            = ($modvars ['invoiceday'] == 0) ? 25 : $modvars ['invoiceday'];
            // echo $invoice_day; exit;
            $timer_days             = $this->serviceTimer($serviceCheck ['running']);
            // echo $t_day;
            $timer_days_for_billing = $timer_days;
            // echo $timer_days_for_billing; exit;

            $price_per_day    = $servicePrice / 25;
            $calculated_price = $price_per_day * $timer_days_for_billing;

            // echo "Calculated Price :" . $calculated_price; exit;
            // $servicePrice = $final_price;
            if ($calculated_price < $servicePrice) {
                $final_price = $calculated_price;
            } else {
                $final_price = $servicePrice;
            }

            // echo "Price :" . $servicePrice; exit;

            /*
             * $count = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $args = array(
             * 'table' => 'zselex_basket',
             * "where" => "user_id=$user_id AND shop_id=$shop_id AND type='" . $serviceType . "'"
             * ));
             */
            $count = $repo->getCount(null, 'ZSELEX_Entity_ServiceBasket',
                'basket_id',
                array(
                'a.user_id' => $user_id,
                'a.shop' => $shop_id,
                'a.type' => $serviceType
            ));

            if ($count > 0) { // if already exist in basket
                $subtotal = $final_price * $quantity;
                // $pntables = pnDBGetTables();
                // $column = $pntables['zselex_basket_column'];
                $obj      = array(
                    'price' => $final_price,
                    'service_status' => $service_status,
                    'subtotal' => $subtotal
                );
                /*
                 * $where = "WHERE $column[shop_id]=$shop_id AND $column[user_id]=$user_id AND $column[type]='" . $serviceType . "'";
                 * DBUTil::updateObject($obj, 'zselex_basket', $where);
                 */
                $repo->updateEntity(null, 'ZSELEX_Entity_ServiceBasket', $obj,
                    array(
                    'a.shop' => $shop_id,
                    'a.user_id' => $user_id,
                    'a.type' => $serviceType
                ));
                LogUtil::registerStatus($this->__('This service is already in your cart'));
            } else {
                // echo "comes here"; exit;

                $subtotal  = $final_price * $quantity;
                // echo $subtotal; exit;
                // $obj = DBUtil::selectObjectByID('zselex_shop_owners', $shop_id, 'shop_id');
                $ownerInfo = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwnerInfo',
                        $args      = array(
                        'shop_id' => $shop_id
                ));
                // $owner_id = $obj['user_id'];
                $owner_id  = $ownerInfo ['user_id'];

                // echo "qty :" .$quantity; exit;
                /*
                 * $item = array(
                 * 'plugin_id' => $serviceId,
                 * 'type' => $serviceType,
                 * 'shop_id' => $shop_id,
                 * 'user_id' => $user_id,
                 * 'owner_id' => $owner_id,
                 * 'quantity' => $quantity,
                 * 'qty_based' => $qtybased,
                 * 'bundle' => $bundle,
                 * 'bundle_id' => $bundle_id,
                 * 'top_bundle' => $top_bundle,
                 * 'bundle_type' => $bundle_type,
                 * 'original_price' => $servicePrice,
                 * 'price' => $subtotal,
                 * 'service_status' => 2,
                 * 'subtotal' => $subtotal,
                 * 'timer_days' => $timer_days
                 * );
                 *
                 * $args = array(
                 * 'table' => 'zselex_basket',
                 * 'element' => $item,
                 * 'Id' => 'basket_id'
                 * );
                 * $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $args);
                 */
                $basket    = new ZSELEX_Entity_ServiceBasket ();
                $pluginObj = $this->entityManager->find('ZSELEX_Entity_Plugin',
                    $serviceId);
                $basket->setPlugin($pluginObj);
                $basket->setType($serviceType);
                $shopObj   = $this->entityManager->find('ZSELEX_Entity_Shop',
                    $shop_id);
                $basket->setShop($shopObj);
                $basket->setUser_id($user_id);
                $basket->setOwner_id($owner_id);
                $basket->setQuantity($quantity);
                $basket->setQty_based($qtybased);
                $basket->setIs_bundle($bundle);
                $bundleObj = $this->entityManager->find('ZSELEX_Entity_Bundle',
                    $bundle_id);
                $basket->setBundle($bundleObj);
                $basket->setTop_bundle($top_bundle);
                $basket->setBundle_type($bundle_type);
                $basket->setOriginal_price($servicePrice);
                $basket->setPrice($subtotal);
                $basket->setService_status(2);
                $basket->setSubtotal($subtotal);
                $basket->setTimer_days($timer_days);
                $this->entityManager->persist($basket);
                $this->entityManager->flush();
                $result    = $basket->geBasket_id();

                LogUtil::registerStatus($this->__('Service added to cart'));
            }
        } // post ends here
        // $this->validateServiceCart();
        $serviceCart = ModUtil::apiFunc('ZSELEX', 'admin', 'getServiceCart',
                $args        = array(
                'user_id' => $user_id
        ));
        $subtotals   = array();
        foreach ($serviceCart as $key => $val) {
            if ($val ['service_status'] == '1') {
                $serviceCart [$key] ['finalprice'] = '0';
            } else {
                $serviceCart [$key] ['finalprice'] = $val ['price'];
            }
            // validate cart//

            $subtotals [] = $val ['subtotal'];
        }

        // echo "<pre>"; print_r($serviceCart); echo "</pre>";

        $this->validateServiceCart();
        $granTotal = array_sum($subtotals);
        $count     = sizeof($serviceCart);
        $this->view->assign('count', $count);
        $this->view->assign('serviceCart', $serviceCart);
        $this->view->assign('granTotal', $granTotal);

        return $this->view->fetch('admin/servicecart.tpl');
    }

    public function validateServiceCart()
    {
        $repo        = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $user_id     = UserUtil::getVar('uid');
        $serviceCart = ModUtil::apiFunc('ZSELEX', 'admin', 'getServiceCart',
                $args        = array(
                'user_id' => $user_id
        ));
        // echo "<pre>"; print_r($serviceCart); echo "</pre>"; exit;
        foreach ($serviceCart as $key => $val) {
            /*
             * $plugininfo = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', array(
             * 'table' => 'zselex_service_bundles',
             * 'IdValue' => $val[bundle_id],
             * 'IdName' => 'bundle_id'
             * ));
             */

            $plugininfo    = $repo->get(array(
                'entity' => 'ZSELEX_Entity_Bundle',
                'fields' => array(
                    'a.bundle_price'
                ),
                // 'joins' => array('JOIN a.bundle b', 'LEFT JOIN a.shop c', 'LEFT JOIN a.plugin d'),
                'where' => array(
                    'a.bundle_id' => $val ['bundle_id']
                )
            ));
            // echo "<pre>"; print_r($plugininfo); echo "</pre>"; exit;
            $shop_id       = $val ['shop_id'];
            $bundle_id     = $val ['bundle_id'];
            $serviceType   = $val ['type'];
            $serviceId     = $val ['plugin_id'];
            $originalPrice = $plugininfo ['bundle_price'];
            $basket_id     = $val ['basket_id'];
            $quantity      = $val ['quantity'];

            $serviceargs  = array(
                'shop_id' => $shop_id,
                'bundle_id' => $bundle_id,
                'type' => $serviceType
            );
            // $serviceCheck = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceCheckCart', $serviceargs);
            $serviceCheck = ModUtil::apiFunc('ZSELEX', 'admin',
                    'serviceCheckCartBundle', $serviceargs);
            // echo "<pre>"; print_r($serviceCheck); echo "</pre>"; exit;

            $todayDate      = date('Y-m-d');
            $remaining_days = date('t') - date('j');

            $timer_days = $this->serviceTimer($serviceCheck ['running']);
            // echo $t_day;

            $timer_days_for_billing = $timer_days;
            $price_per_day          = $originalPrice / 25;
            $calculated_price       = $price_per_day * $timer_days_for_billing;

            // echo "Calculated Price :" . $calculated_price; exit;
            // $servicePrice = $final_price;
            if ($calculated_price < $originalPrice) {
                $final_price = $calculated_price;
            } else {
                $final_price = $originalPrice;
            }

            // echo "Final Price :" . $final_price * $quantity; exit;

            $subtotal   = $final_price * $quantity;
            $item       = array(
                'basket_id' => $basket_id,
                'quantity' => $quantity,
                'original_price' => $originalPrice,
                'price' => $final_price,
                'subtotal' => $subtotal,
                'timer_days' => $timer_days
            );
            // echo "<pre>"; print_r($item); echo "</pre>"; exit;
            $updateargs = array(
                'table' => 'zselex_basket',
                'IdValue' => $basket_id,
                'IdName' => 'basket_id',
                'element' => $item
            );
            // echo "helloo34"; exit;
            // $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement', $updateargs);
            $result     = $repo->updateEntity(null,
                'ZSELEX_Entity_ServiceBasket', $item,
                array(
                'a.basket_id' => $basket_id
            ));
        }

        return true;
    }

    public function updateServiceCart($args)
    {
        $repo       = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
        // $quantities = $serviceId = FormUtil::getPassedValue("quantity");
        $quantities = FormUtil::getPassedValue('quantity');
        // echo "<pre>"; print_r($quantities); echo "</pre>"; exit;
        foreach ($quantities as $key => $quantity) {
            $xplode    = explode('+', $key);
            $basket_id = $xplode [0];
            $price     = $xplode [1];
            // echo $price . '<br>'; exit;

            $subtotal = $price * $quantity;
            $item     = array(
                'basket_id' => $basket_id,
                'quantity' => $quantity,
                'subtotal' => $subtotal
            );

            $updateargs = array(
                'table' => 'zselex_basket',
                'IdValue' => $basket_id,
                'IdName' => 'basket_id',
                'element' => $item
            );

            // $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement', $updateargs);

            $result = $repo->updateEntity(null, 'ZSELEX_Entity_ServiceBasket',
                $item,
                array(
                'a.basket_id' => $basket_id
            ));
        }

        $this->validateServiceCart($args); // VAIDATE CART!

        LogUtil::registerStatus($this->__('Cart Updated'));
        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'serviceCart'));
    }

    public function serviceOrder($args)
    { // Show order + Validate the cart items
        // unset($_SESSION['serviceorder']);
        // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
        $repo                        = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $_SESSION ['grandtotal'];
        $_SESSION ['order_id'];
        $_SESSION ['payment_option'] = 1;
        // echo "heloooooo"; exit;
        if ($_POST) {
            $_SESSION ['payment_method'] = $_POST ['paytype'];
        }
        // echo $_SESSION['payment_method'];
        $payment_method = $_SESSION ['payment_method'];
        $date           = date('Y-m-d');
        $user_id        = UserUtil::getVar('uid');
        $this->view->assign('user_id', $user_id);
        $this->validateServiceCart(); // VAIDATE CART!
        /*
         * $cart = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements', $args = array(
         * 'table' => 'zselex_basket',
         * 'where' => "user_id=$user_id",
         * 'orderBy' => 'basket_id ASC',
         * 'useJoins' => ''
         * ));
         */
        $cart           = $repo->getAll(array(
            'entity' => 'ZSELEX_Entity_ServiceBasket',
            'fields' => array(
                'a.basket_id',
                'b.bundle_id',
                'c.shop_id',
                'a.service_status',
                'a.subtotal',
                'a.price',
                'a.original_price',
                'a.quantity',
                'a.timer_days'
            ),
            'where' => array(
                'a.user_id' => $user_id
            ),
            'joins' => array(
                'JOIN a.bundle b',
                'JOIN a.shop c'
            ),
            'orderby' => 'a.basket_id ASC'
        ));
        // echo "<pre>"; print_r($cart); echo "</pre>"; exit;
        foreach ($cart as $key => $value) { // validate the cart
            /*
             * $bundleinfo = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $args = array(
             * 'table' => 'zselex_service_bundles',
             * 'IdValue' => $value['bundle_id'],
             * 'IdName' => 'bundle_id'
             * ));
             */
            $bundleinfo                        = $repo->get(array(
                'entity' => 'ZSELEX_Entity_Bundle',
                'where' => array(
                    'a.bundle_id' => $value ['bundle_id']
                )
            ));
            /*
             * $shopinfo = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $args = array(
             * 'table' => 'zselex_shop',
             * 'IdValue' => $value['shop_id'],
             * 'IdName' => 'shop_id'
             * ));
             */
            $shopinfo                          = $repo->get(array(
                'entity' => 'ZSELEX_Entity_Shop',
                'where' => array(
                    'a.shop_id' => $value ['shop_id']
                )
            ));
            $cart [$key] ['shop_name']         = $shopinfo ['shop_name'];
            $cart [$key] ['bundle_name']       = $bundleinfo ['bundle_name'];
            // $cart[$key]['demoperiod'] = $plugininfo['demoperiod'];
            // $cart[$key]['demoperiod'] = $plugininfo['demoperiod'];
            $cart [$key] ['service_status']    = $value ['service_status'];
            $cart [$key] ['subtotal_upgraded'] = $value ['subtotal'];
            $subtotal_total []                 = $value ['subtotal'];
        }

        $_SESSION ['serviceorder'] = $cart;

        // $order_id = $this->createServiceOrder($order_args);
        // $order_id = $this->createServiceOrder();
        // echo "Order ID : " . $order_id;
        // echo "<pre>"; print_r($cart); echo "</pre>"; exit;

        if (count($subtotal_total) > 0) {
            $granTotal = array_sum($subtotal_total);
        } else {
            $granTotal = 0;
        }
        $_SESSION ['grandtotal'] = $granTotal;
        $order_id                = $this->createServiceOrder();

        /*
         * $orderinfo = ModUtil::apiFunc('ZSELEX', 'user', 'get', $getargs = array(
         * 'table' => 'zselex_service_order',
         * 'where' => "order_id='" . $order_id . "'",
         * //'fields' => array('id', 'quantity', 'availed')
         * ));
         */

        $orderinfo = $repo->get(array(
            'entity' => 'ZSELEX_Entity_ServiceOrder',
            'where' => array(
                'a.order_id' => $order_id
            )
        ));
        // echo "<pre>"; print_r($orderinfo); echo "</pre>"; exit;
        // echo "<pre>"; print_r($cart); echo "</pre>"; exit;
        // array_unshift($_SESSION['serviceorder'] , $order_id);
        // echo "<pre>"; print_r($_SESSION['serviceorder']); echo "</pre>";
        // echo $granTotal; exit;
        // echo $payment_method; exit;
        $modvars   = ModUtil::getVar('ZPayment');
        if ($payment_method == 'netaxept') {
            if (ModUtil::available('ZPayment')) {

                // Register Transaction with Netaxept here.
                // $netaxept = ZPayment_Controller_User::registerTransaction($_SESSION['checkoutinfo']);
                $netaxept = ModUtil::apiFunc('ZPayment', 'Netaxept',
                        'registerServiceTransaction',
                        array(
                        'order_info' => $orderinfo
                ));
                if ($netaxept ['error'] || !$netaxept) {
                    LogUtil::registerError($this->__('Sorry! Error occured in Netaxept .Please try later!'));

                    return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                                'serviceCart'));
                }
            } else {
                LogUtil::registerError($this->__('Sorry! Payment module is not available currently!'));

                return $this->redirect(ModUtil::url('ZSELEX', 'admin',
                            'serviceCart'));
            }
        } elseif ($payment_method == 'paypal') {
            // $modvars = ModUtil::getVar('ZPayment');
            // echo "<pre>"; print_r($modvars); echo "</pre>"; exit;
            $paypal_business_email = $modvars ['Paypal_business_email'];
            $test_mode             = $modvars ['Paypal_testmode'];
            $this->view->assign('paypal_business_email', $paypal_business_email);
            $this->view->assign('test_mode', $test_mode);
        } elseif ($payment_method == 'quickpay') {
            // $modvars = ModUtil::getVar('ZPayment');
            // echo "<pre>"; print_r($modvars); echo "</pre>";

            $qp_ok_url       = pnGetBaseURL().ModUtil::url('ZSELEX', 'admin',
                    'QuickPayOkService',
                    array(
                    'order_id' => $order_id
            ));
            $qp_cancel_url   = pnGetBaseURL().ModUtil::url('ZSELEX', 'admin',
                    'QuickPayCancelService',
                    array(
                    'order_id' => $order_id
            ));
            $qp_callback_url = pnGetBaseURL().ModUtil::url('ZSELEX', 'admin',
                    'QuickPayCallbackService',
                    array(
                    'order_id' => $order_id
            ));

            $protocol    = '7';
            $msgtype     = 'authorize';
            //   $merchant    = $modvars ['QuickPay_ID'];
            $merchant    = $modvars ['QuickPay_Merchant_ID'];
            $agreementId = $modvars ['QuickPay_Agreement_ID'];
            $language    = 'en';
            $ordernumber = $order_id;
            $amount      = (int) ($orderinfo ['grand_total'] * 100);
            $currency    = 'DKK';
            $continueurl = $qp_ok_url;
            $cancelurl   = $qp_cancel_url;
            $callbackurl = $qp_callback_url;
            $autocapture = 1;
            //  $md5secret   = $modvars ['QuickPay_md5'];
            $apiKey      = $modvars ['QuickPay_Api_Key'];
            $test_mode   = '';
            if ($modvars ['QuickPay_testmode']) {
                $test_mode = 1;
            }
            $md5check = md5($protocol.$msgtype.$merchant.$language.$ordernumber.$amount.$currency.$continueurl.$cancelurl.$callbackurl.$autocapture.$test_mode.$md5secret);

            /* $quickpay_info = array(
              'merchant' => $merchant,
              'test_mode' => $test_mode,
              'protocol' => $protocol,
              'msgtype' => $msgtype,
              'language' => $language,
              'ordernumber' => $ordernumber,
              'amount' => $amount,
              'currency' => $currency,
              'continueurl' => $continueurl,
              'cancelurl' => $cancelurl,
              'callbackurl' => $callbackurl,
              'autocapture' => $autocapture,
              'md5check' => $md5check
              ); */
            // echo "<pre>"; print_r($quickpay_info); echo "</pre>";
            // $this->view->assign('quickpay_info', $quickpay_info);


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
                    //  "CUSTOM_shop_id" => $shop_id,
                    "CUSTOM_user_id" => $user_id
                )
            );
            $checksum = ModUtil::apiFunc('ZPayment', 'user', 'sign',
                    $args     = array(
                    'params' => $params,
                    'api_key' => $apiKey
            ));

            $this->view->assign('checksum', $checksum);
            $this->view->assign('quickpay_info', $params);
            $this->view->assign('text_type', 'hidden');
        } elseif ($payment_method == 'epay') {
            // $user_id = UserUtil::getVar('uid');

            $epay_accept_url = pnGetBaseURL().ModUtil::url('ZSELEX', 'admin',
                    'EpayAcceptService',
                    array(
                    'shop_id' => $shop_id
            ));
            $epay_cancel_url = pnGetBaseURL().ModUtil::url('ZSELEX', 'admin',
                    'EpayCancelService',
                    array(
                    'shop_id' => $shop_id
            ));
            // $epay_callback_url = pnGetBaseURL() . ModUtil::url('ZSELEX', 'user', 'QuickPayCallback', array('order_id' => $order_id));

            $epayForm = array();
            if ($modvars ['Epay_testmode']) {
                $epayForm ['merchant_number'] = $modvars ['Epay_test_merchant_number'];
            } else {
                $epayForm ['merchant_number'] = $modvars ['Epay_merchant_number'];
            }
            // $language = $thislang;
            $epayForm ['ordernumber']     = $order_id;
            $epayForm ['amount']          = (int) ($orderinfo ['grand_total'] * 100);
            $epayForm ['currency']        = 'DKK';
            $epayForm ['accepturl']       = $epay_accept_url;
            $epayForm ['cancelurl']       = $epay_cancel_url;
            // $epayForm['callbackurl'] = $qp_callback_url; //see http://quickpay.dk/clients/callback-quickpay.php.txt
            $epayForm ['currency']        = 'DKK';
            $epayForm ['windowstate']     = 3;
            $epayForm ['instantcallback'] = 1;
            $epayForm ['ownreceipt']      = 1;
            // $epayForm['md5_hash'] = $modvars['Epay_md5_hash'];
            // $md5check = md5($protocol . $msgtype . $merchant . $language . $ordernumber . $amount . $currency . $continueurl . $cancelurl . $callbackurl . $autocapture . $test_mode . $cardtypelock . $splitpayment . $md5secret);

            if (strlen($modvars ['Epay_md5_hash']) > 0) {
                $hash                  = md5(implode('', array_values($epayForm)).$modvars ['Epay_md5_hash']);
                $epayForm ['set_hash'] = 1;
                $epayForm ['hash']     = $hash;
            }

            // $this->view->assign('user_id', $user_id);
            // $this->view->assign('epay_info', $epay_info);
            $this->view->assign('epayForm', $epayForm);
        }

        $user_email = UserUtil::getVar('email');

        $user_info = ZSELEX_Controller_Base_User::getUserInfo($user_id);
        $user_info = $user_info + array(
            'email' => $user_email
        );
        // echo "<pre>"; print_r($user_info); echo "</pre>";
        $this->view->assign('user_info', $user_info);
        $this->view->assign('netaxept', $netaxept);
        $this->view->assign('cart', $cart);
        $this->view->assign('order_id', $order_id);
        $this->view->assign('granTotal', $granTotal);
        $this->view->assign('payment_method', $payment_method);

        return $this->view->fetch('admin/serviceorder.tpl');
    }

    public function paymentOption($args)
    { // payment options
        // unset($_SESSION['serviceorder']);
        $_SESSION ['order_id']       = '';
        $_SESSION ['payment_method'] = '';
        $_SESSION ['payment_option'] = 1;
        unset($_SESSION ['order_id']);
        unset($_SESSION ['payment_method']);

        return $this->view->fetch('admin/paymentoptions.tpl');
    }

    public function createServiceOrder()
    {
        // echo "<pre>"; print_r($_SESSION['serviceorder']); echo "</pre>"; exit;
        // echo $_SESSION['grandtotal']; exit;
        if (!empty($_SESSION ['order_id'])) {
            return $_SESSION ['order_id'];
            if ($_SESSION ['order_id_changed'] == $_SESSION ['order_id']) {
                // return $_SESSION['order_id'];
            }
        }

        $repo    = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $user_id = UserUtil::getVar('uid');
        /*
         * $obj = array(
         * 'user_id' => $user_id,
         * 'status' => 'Placed',
         * 'payment_method' => $_SESSION['payment_method'],
         * 'grand_total' => $_SESSION['grandtotal']
         * );
         * $result = DBUtil::insertObject($obj, 'zselex_service_order');
         * $lastInsertId = DBUtil::getInsertID('zselex_service_order', 'id');
         */

        $order        = new ZSELEX_Entity_ServiceOrder ();
        $order->setUser_id($user_id);
        $order->setStatus('Placed');
        $order->setPayment_method($_SESSION ['payment_method']);
        $order->setGrand_total($_SESSION ['grandtotal']);
        $this->entityManager->persist($order);
        $this->entityManager->flush();
        $result       = $order->getId();
        $lastInsertId = $order->getId();
        if ($result) {
            $rand_num                      = rand(000000, 999999);
            $order_id                      = 'CP'.$lastInsertId.$rand_num; // make it unique
            $_SESSION ['order_id']         = $order_id;
            $_SESSION ['order_id_changed'] = $order_id;
            /*
             * $args = array(
             * 'table' => 'zselex_service_order',
             * 'items' => array(
             * 'order_id' => $order_id
             * ),
             * 'where' => array(
             * 'id' => $lastInsertId
             * )
             * );
             * $updateOrderId = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElementWhere', $args); // update order id
             */

            $item          = array(
                'order_id' => $order_id
            );
            $updateOrderId = $repo->updateEntity(null,
                'ZSELEX_Entity_ServiceOrder', $item,
                array(
                'a.id' => $lastInsertId
            ));
            foreach ($_SESSION ['serviceorder'] as $key => $val) {
                $obj2 = array(
                    'order_id' => $_SESSION ['order_id'],
                    'plugin_id' => $val ['plugin_id'],
                    'type' => $val ['type'],
                    'shop_id' => $val ['shop_id'],
                    'user_id' => $user_id,
                    'owner_id' => $val ['owner_id'],
                    'quantity' => $val ['quantity'],
                    'price' => $val ['price'],
                    'service_status' => $val ['service_status'],
                    'timer_days' => $val ['timer_days'],
                    'qty_based' => $val ['qty_based'],
                    'bundle' => $val ['bundle'],
                    'bundle_id' => $val ['bundle_id'],
                    'subtotal' => $val ['subtotal_upgraded']
                );

                $result2 = DBUtil::insertObject($obj2,
                        'zselex_service_orderitems');

                /*
                 * $where = "user_id=$user_id";
                 * $deleteBasket = ModUtil::apiFunc('ZSELEX', 'admin', 'deleteWhere', $args = array(
                 * 'table' => 'zselex_basket',
                 * 'where' => $where
                 * ));
                 */
            }
        }

        return $order_id;
    }

    public function createServiceOrder1($args)
    {
        // echo "<pre>"; print_r($_SESSION['serviceorder']); echo "</pre>";
        // echo $_SESSION['grandtotal'];
        if (!empty($_SESSION ['order_id'])) {
            return $_SESSION ['order_id'];
            if ($_SESSION ['order_id_changed'] == $_SESSION ['order_id']) {
                // return $_SESSION['order_id'];
            }
        }
        $user_id      = UserUtil::getVar('uid');
        $obj          = array(
            'user_id' => $user_id,
            'status' => 'Placed',
            'grand_total' => $_SESSION ['grandtotal']
        );
        $result       = DBUtil::insertObject($obj, 'zselex_service_order');
        $lastInsertId = DBUtil::getInsertID('zselex_service_order', 'id');
        if ($result) {
            $rand_num                      = rand(000000, 999999);
            $order_id                      = 'CP'.$lastInsertId.$rand_num; // make it unique
            $_SESSION ['order_id']         = $order_id;
            $_SESSION ['order_id_changed'] = $order_id;
            $args                          = array(
                'table' => 'zselex_service_order',
                'items' => array(
                    'order_id' => $order_id
                ),
                'where' => array(
                    'id' => $lastInsertId
                )
            );
            $updateOrderId                 = ModUtil::apiFunc('ZSELEX', 'admin',
                    'updateElementWhere', $args); // update order id

            foreach ($_SESSION ['serviceorder'] as $key => $val) {
                $obj2 = array(
                    'order_id' => $_SESSION ['order_id'],
                    'plugin_id' => $val ['plugin_id'],
                    'type' => $val ['type'],
                    'shop_id' => $val ['shop_id'],
                    'user_id' => $user_id,
                    'owner_id' => $val ['owner_id'],
                    'quantity' => $val ['quantity'],
                    'price' => $val ['price'],
                    'service_status' => $val ['service_status'],
                    'qty_based' => $val ['qty_based'],
                    'bundle' => $val ['bundle'],
                    'bundle_id' => $val ['bundle_id'],
                    'subtotal' => $val ['subtotal_upgraded']
                );

                $result2 = DBUtil::insertObject($obj2,
                        'zselex_service_orderitems');

                /*
                 * $where = "user_id=$user_id";
                 * $deleteBasket = ModUtil::apiFunc('ZSELEX', 'admin', 'deleteWhere', $args = array(
                 * 'table' => 'zselex_basket',
                 * 'where' => $where
                 * ));
                 */
            }
        }

        return $order_id;
    }

    public function confirmServiceOrder($args)
    {
        // echo "<pre>"; print_r($_SESSION['serviceorder']); echo "</pre>";exit;
        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
        $orderitems   = array();
        $user_id      = UserUtil::getVar('uid');
        if (empty($confirmation)) {
            return $this->view->fetch('admin/cart_confirmation.tpl');
        }
        // $orderitems = $_SESSION['serviceorder'];

        $obj          = array(
            'user_id' => $user_id,
            'status' => 'Placed',
            'grand_total' => $_SESSION ['grandtotal']
        );
        $result       = DBUtil::insertObject($obj, 'zselex_service_order');
        $lastInsertId = DBUtil::getInsertID('zselex_service_order', 'id');
        if ($result) {
            $rand_num              = rand(0000, 9999);
            $order_id              = 'ZS'.$lastInsertId.$rand_num; // make it unique
            $_SESSION ['order_id'] = $order_id;
            $args                  = array(
                'table' => 'zselex_service_order',
                'items' => array(
                    'order_id' => $order_id
                ),
                'where' => array(
                    'id' => $lastInsertId
                )
            );
            $updateOrderId         = ModUtil::apiFunc('ZSELEX', 'admin',
                    'updateElementWhere', $args); // update order id
            // echo "<pre>"; print_r($_SESSION['serviceorder']); echo "</pre>"; exit;
            foreach ($_SESSION ['serviceorder'] as $key => $val) {
                $obj2 = array(
                    'order_id' => $_SESSION ['order_id'],
                    'plugin_id' => $val ['plugin_id'],
                    'type' => $val ['type'],
                    'shop_id' => $val ['shop_id'],
                    'user_id' => $user_id,
                    'owner_id' => $val ['owner_id'],
                    'quantity' => $val ['quantity'],
                    'price' => $val ['price'],
                    'service_status' => $val ['service_status'],
                    'qty_based' => $val ['qty_based'],
                    'bundle' => $val ['bundle'],
                    'bundle_id' => $val ['bundle_id'],
                    'subtotal' => $val ['subtotal_upgraded']
                );

                $result2 = DBUtil::insertObject($obj2,
                        'zselex_service_orderitems');
            }

            $where        = "user_id=$user_id";
            $deleteBasket = ModUtil::apiFunc('ZSELEX', 'admin', 'deleteWhere',
                    $args         = array(
                    'table' => 'zselex_basket',
                    'where' => $where
            ));

            $serviceToApprove = ModUtil::apiFunc('ZSELEX', 'admin',
                    'submitServiceToApprove',
                    $args             = array(
                    'user_id' => UserUtil::getVar('uid'),
                    'data' => $_SESSION ['serviceorder']
            ));
            // $user_id = UserUtil::getVar('uid');
            // $userinfo = DBUtil::selectObjectByID('users', $user_id, 'uid');
            $message          = 'This is a test email';
            $sendmail         = array(
                'from' => 'Zselex Admin',
                'rpemail' => 'noreply@zselex.com',
                'subject' => 'Order Details',
                'message' => $message
            );
            $this->sendMailToUser($sendmail, $_SESSION ['order_id'],
                $_SESSION ['grandtotal'], $_SESSION ['serviceorder']);
            LogUtil::registerStatus($this->__('Order Submitted Successfully'));

            return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshop'));
        }
    }

    public function sendMailToUser($sendmail, $order_id, $grandTotal, $orderInfo)
    {
        $msg = '';
        $msg .= '<div><h3>Order Id : '.$order_id.'</h3></div>';
        $msg .= '<table width="60%" cellspacing="1" cellpadding="1" bgcolor="brown">
                 <tr bgcolor="white">
                 <td><b>Service</b></td>
                 <td><b>Shop</b></td>
                 <td><b>Quantity</b></td>
                 <td><b>Price</b></td>
                 <td><b>Subtotal</b></td>
                </tr>';

        $orderitems = ModUtil::apiFunc('ZSELEX', 'user', 'selectJoinArray',
                array(
                'table' => 'zselex_service_orderitems a',
                'fields' => array(
                    'a.*',
                    'b.shop_name',
                    'c.plugin_name'
                ),
                'joins' => array(
                    'LEFT JOIN zselex_shop b ON b.shop_id=a.shop_id',
                    'LEFT JOIN zselex_plugin c ON c.plugin_id=a.plugin_id'
                ),
                'where' => array(
                    "a.order_id='".$order_id."'"
                )
        ));
        // echo "<pre>"; print_r($orderitems); echo "</pre>";

        foreach ($orderitems as $key => $value) {
            $msg .= "<tr bgcolor='white'>
                     <td>$value[plugin_name]</td>
                     <td>$value[shop_name]</td>
                     <td>$value[quantity]</td>
                     <td>$value[price]</td>
                     <td>$value[subtotal]</td>
                    </tr>";
            $sub [] = $value ['subtotal'];
        }
        $grand = array_sum($sub);
        $msg .= "<tr  bgcolor='white'>
                  <td colspan='5' align='right'><b>Grand Total : $grand</b></td>
                  </tr>";

        $msg .= '</table>';

        /*
         * $sendMessageArgs = array(
         * 'fromname' => $sendmail['from'],
         * 'fromaddress' => $sendmail['rpemail'],
         * 'toname' => UserUtil::getVar('uname'),
         * 'toaddress' => UserUtil::getVar('email'),
         * 'replytoname' => UserUtil::getVar('uname'),
         * 'replytoaddress' => $sendmail['rpemail'],
         * 'subject' => $sendmail['subject'],
         * 'body' => $msg,
         * 'bcc' => false,
         * );
         * if (ModUtil::apiFunc('Mailer', 'user', 'sendMessage', $sendMessageArgs)) {
         * $recipientscount += count($bcclist);
         * } else {
         * $this->registerError($this->__('Error! Could not send the e-mail message.'));
         *
         * return false;
         * }
         *
         */

        $to      = UserUtil::getVar('email');
        $subject = 'Order Details';
        $message = $msg;
        $headers = 'MIME-Version: 1.0'."\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";

        // Additional headers
        $headers .= 'To: '.UserUtil::getVar('uname').' <'.UserUtil::getVar('email').'>'."\r\n";
        $headers .= 'From: ZSELEX ADMIN <admin@zselex.com>'."\r\n";
        // $headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
        // $headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";
        // Mail it
        mail($to, $subject, $message, $headers);
    }

    public function mailTest()
    {

        // echo "hello"; exit;
        $deleteBasket = ModUtil::apiFunc('ZSELEX', 'admin',
                'servicExpiryReminder', $args         = array());

        exit();
    }

    public function test123()
    {
        $sql    = 'select * from zselex_country';
        $query  = DBUtil::executeSQL($sql);
        $result = $query->fetchAll();
        $array  = array();
        foreach ($result as $row) {
            $array [] = array(
                'value' => $row ['country_id'],
                'text' => $row ['country_name']
            );
        }

        return json_encode($array);
    }

    public function upgrade()
    {
        // echo "comes heree"; exit;

        /*
         * $entities = array(
         * 'ZSELEX_Entity_ProductOption',
         * 'ZSELEX_Entity_ProductOptionValue',
         * 'ZSELEX_Entity_ProductToOption',
         * 'ZSELEX_Entity_ProductToOptionValue',
         * 'ZSELEX_Entity_Order',
         * 'ZSELEX_Entity_OrderItem',
         * 'ZSELEX_Entity_Rating',
         * 'ZSELEX_Entity_ShopSetting',
         * 'ZSELEX_Entity_Event',
         * 'ZSELEX_Entity_Shop',
         * 'ZSELEX_Entity_Category',
         * 'ZSELEX_Entity_Bundle',
         * 'ZSELEX_Entity_BundleItem',
         * 'ZSELEX_Entity_Plugin',
         * 'ZSELEX_Entity_Area',
         * 'ZSELEX_Entity_Product',
         * 'ZSELEX_Entity_Advertise',
         * 'ZSELEX_Entity_Product',
         * 'ZSELEX_Entity_ProductCategory',
         * 'ZSELEX_Entity_Manufacturer',
         * 'ZSELEX_Entity_MinisiteImage',
         * 'ZSELEX_Entity_Employee',
         * 'ZSELEX_Entity_Announcement',
         * 'ZSELEX_Entity_Banner',
         * 'ZSELEX_Entity_Shop',
         * 'ZSELEX_Entity_Category',
         * 'ZSELEX_Entity_Area',
         * 'ZSELEX_Entity_ServiceDemo',
         * 'ZSELEX_Entity_ServiceShop',
         * 'ZSELEX_Entity_MiniShop',
         * 'ZSELEX_Entity_ServiceBasket',
         * 'ZSELEX_Entity_ServiceOrder',
         * 'ZSELEX_Entity_ServiceOrderItem',
         * 'ZSELEX_Entity_ServiceBundle'
         * );
         */
        $entities = array(
            'ZSELEX_Entity_Url'
        );

        foreach ($entities as $table) {
            try {
                $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                        array(
                        $table
                ));
            } catch (\Exception $e) {
                echo 'Message: '.$e->getMessage().'<br>';
                echo 'Error! Could not update Entity - '.$table." ".$e->getMessage().'<br>';
                exit();
            }
        }

        // exit;

        LogUtil::registerStatus($this->__('Upgraded Successfully'));

        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshop'));
    }

    public function renameFolder()
    {

        // echo "comes heree"; exit;
        // return ModUtil::apiFunc('ZSELEX', 'admin', 'renameFolders');
        // shell_exec("/usr/bin/php" . " " . ModUtil::apiFunc('ZSELEX', 'admin', 'renameFolder'));
        // ModUtil::apiFunc('ZSELEX', 'folderrename', 'renameFolder');
        $update_folders = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->renameFolder();
        // return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshop'));

        return true;
    }

    public function updateProfileFields()
    {
        $statement = Doctrine_Manager::getInstance()->connection();
        $sql       = "SELECT COUNT(*) as count FROM user_property WHERE attributename='ship_city'";
        $query     = $statement->execute($sql);
        $result    = $query->fetch();

        return $result;
    }

    public function payMentAlert($shop_id)
    {
        $netaxept = $this->entityManager->getRepository('ZPayment_Entity_Netaxept')->paymentMode(array(
            'shop_id' => $shop_id
        ));
        // echo "<pre>"; print_r($netaxept); echo "</pre>"; exit;
        if ($netaxept ['test_mode'] == true) {
            LogUtil::registerError($this->__('Your Netaxept payment is in test mode'));
        }
        $paypal = $this->entityManager->getRepository('ZPayment_Entity_Paypal')->paymentMode(array(
            'shop_id' => $shop_id
        ));

        if ($paypal ['test_mode'] == true) {
            LogUtil::registerError($this->__('Your Paypal payment is in test mode'));
        }

        /*
          $quickpay = $this->entityManager->getRepository('ZPayment_Entity_QuickPay')->paymentMode(array(
          'shop_id' => $shop_id
          ));

          if ($quickpay ['test_mode'] == true) {
          LogUtil::registerError($this->__('Your Quickpay payment is in test mode'));
          }
         */

        $epay = $this->entityManager->getRepository('ZPayment_Entity_Epay')->paymentMode(array(
            'shop_id' => $shop_id
        ));

        if ($epay ['test_mode'] == true) {
            LogUtil::registerError($this->__('Your Epay payment is in test mode'));
        }

        return true;
    }

    public function reminderNotifications($shop_id)
    {
        $this->payMentAlert($shop_id);
        $repo          = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        $default_image = $repo->getCount(null, 'ZSELEX_Entity_MinisiteImage',
            'file_id',
            array(
            'a.shop' => $shop_id,
            'a.defaultImg' => 1
        ));
        if (!$default_image) {
            LogUtil::registerError($this->__('You have not selected a default image for shop'));
        }
    }

    public function updateProductKeywords()
    {
        set_time_limit(0);
        // echo "comes here...";  exit;
        $products = $this->entityManager->getRepository('ZSELEX_Entity_Product')->updateProductKeywordsByDelete();

        die("End of script");
    }

    public function testing()
    {
        // echo "comes here...";  exit;
        $products = $this->entityManager->getRepository('ZSELEX_Entity_Product')->updateProductKeywordsByDelete();

        return $this->view->fetch('admin/test.tpl');
    }
}
// end class def


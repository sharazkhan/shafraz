<?php

/**
 * Event handler implementation class for user-related events.
 */
class ZSELEX_Listener_User {
    /**
     * Listener for the `user.gettheme` event.
     *
     * Called during UserUtil::getTheme() and is used to filter the results.
     * Receives arg['type'] with the type of result to be filtered
     * and the $theme_name in the $event->data which can be modified.
     * Must $event->stop() if handler performs filter.
     */
    /*
      public static function getTheme1(Zikula_Event $event) {
      // return;
      // error_reporting(E_ALL);
      // echo "HELLO TESTTT"; exit;
      try {
      if ($_REQUEST ['func'] == 'productview' && !isset($_REQUEST ['theme'])) {

      $product_id = (int) FormUtil::getPassedValue('id', isset($args ['id']) ? $args ['id'] : null, 'REQUEST');
      $productTitle = FormUtil::getPassedValue('producttitle', '', 'REQUEST');

      if (!empty($product_id)) {

      $shopTheme = ModUtil::apiFunc('ZSELEX', 'user', 'changeTheme', $args = array(
      'product_id' => $product_id
      ));
      } else {

      $shopTheme = ModUtil::apiFunc('ZSELEX', 'user', 'changeTheme', $args = array(
      'producttitle' => $productTitle
      ));
      }
      if (!empty($shopTheme)) {
      $event->setData($shopTheme);
      $event->stop();
      } else {

      }
      } elseif ($_REQUEST ['func'] == 'shop' && $_REQUEST ['type'] == 'user' && !isset($_REQUEST ['theme'])) {

      $shop_id = $_REQUEST ['shop_id'];
      $shop_id = !empty($_REQUEST ['shop_id']) ? $_REQUEST ['shop_id'] : $_REQUEST ['shop_idnewItem'];
      // echo $shop_id;
      // $shop_id = FormUtil::getPassedValue('id', isset($args['id']) ? $args['id'] : null, 'REQUEST');
      // $obj = DBUtil::selectObjectByID('zselex_shop', $shop_id, 'shop_id');
      // $shopTheme = $obj['theme'];
      // if (!empty($shopTheme)) {
      // $event->setData($shopTheme);
      // $event->stop();
      // } else {
      //
      // }

      $sql = "SELECT theme from zselex_shop WHERE shop_id='" . $shop_id . "'";
      $query = DBUtil::executeSQL($sql);
      $result = $query->fetch();
      $shopTheme = $result ['theme'];

      // echo "theme: " . $shopTheme;
      if (!empty($shopTheme)) {
      $event->setData($shopTheme);
      $event->stop();
      }
      } elseif (($_REQUEST ['func'] == 'site' || ($_REQUEST ['func'] == 'newitem' && $_REQUEST ['module'] == 'ZSELEX') || $_REQUEST ['func'] == 'display' || $_REQUEST ['func'] == 'viewshoparticles' && $_REQUEST ['module'] == 'ZSELEX') && !isset($_REQUEST ['theme'])) {
      $shop_id = $_REQUEST ['shop_id'];
      $shop_id = !empty($_REQUEST ['shop_id']) ? $_REQUEST ['shop_id'] : $_REQUEST ['shop_idnewItem'];
      // echo "shop id: " . $shop_id;
      $shopName = $_REQUEST ['shopName'];
      // echo $_REQUEST['shop_idnewItem'];
      $sql = "SELECT theme from zselex_shop WHERE shop_id='" . $shop_id . "'";
      $query = DBUtil::executeSQL($sql);
      $result = $query->fetch();
      $shopTheme = $result ['theme'];

      // echo "theme: " . $shopTheme;
      if (!empty($shopTheme)) {
      $event->setData($shopTheme);
      $event->stop();
      }
      } elseif ($_REQUEST ['func'] == 'viewShopProducts' && !isset($_REQUEST ['theme'])) {
      // $event->setData("SeaBreeze");
      // $event->stop();
      } else {

      }

      // if ($_REQUEST['module'] == 'news' && $_REQUEST['type'] == 'user' && $_REQUEST['func'] == 'create') {
      //
      // $user = UserUtil::getVar('uid');
      // $shop_id = $_POST['SHOP'];
      //
      // if (!SecurityUtil::checkPermission('News::', '::', ACCESS_ADMIN)) {
      // ModUtil::apiFunc('ZSELEX', 'user', 'updateArticleService', $args = array('user_id' => $user, 'shop_id' => $shop_id, 'type' => 'createarticles'));
      // }
      //
      //
      // }
      } catch (Exception $e) {
      echo 'Caught exception: ', $e->getMessage(), "\n";
      // die;
      }
      }
     */

    /**
     * Listener for the `user.gettheme` event.
     *
     * Called during UserUtil::getTheme() and is used to filter the results.
     * Receives arg['type'] with the type of result to be filtered
     * and the $theme_name in the $event->data which can be modified.
     * Must $event->stop() if handler performs filter.
     */
    public static function getTheme(Zikula_Event $event) {
        //error_reporting(0);
        $allowedFunctions = array(
            'productview',
            'site',
            'findus',
            'shop',
            'viewevent',
            'pages',
            'page'
        );
        $currentFunction = $_REQUEST ['func'];
        if (in_array($currentFunction, $allowedFunctions) && !isset($_REQUEST ['theme'])) {

            $shopTitle = $_REQUEST['shoptitle'];
            $em = ServiceUtil::getService('doctrine.entitymanager');

            $item = $em->getRepository('ZSELEX_Entity_Shop')->get(array(
                'entity' => 'ZSELEX_Entity_Shop',
                'fields' => array(
                    'a.shop_id', 'a.theme'
                ),
                'where' => array(
                    'a.urltitle' => $shopTitle
                )
            ));

            //print_r($item); exit;
            $shopTheme = $item['theme'];
            $shopId = $item['shop_id'];

            $serviceExist = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceExistBlock', $args = array(
                        'shop_id' => $shopId,
                        'type' => 'stdtheme'
            ));

            if ($serviceExist < 1) {
                return;
            }
//echo $shopTheme; exit;
            if (!empty($shopTheme)) {
                $themeCount = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $args = array(
                            'table' => 'themes',
                            'where' => "name='" . $shopTheme . "'"
                ));
                //echo "Theme Count :" . $themeCount; exit;
                if ($themeCount) {
                    $event->setData($shopTheme);
                    $event->stop();
                }
            }
        }
    }

    /*
      public static function getTheme2(Zikula_Event $event) {
      // return;
      // error_reporting(E_ALL);
      // echo "HELLO TESTTT";
      // echo $_REQUEST['theme'];
      $allowedFunctions = array(
      'productview',
      'site',
      'findus',
      'shop',
      'viewevent'
      );
      $currentFunction = $_REQUEST ['func'];
      $shop_id = $_REQUEST ['shop_id'];
      if (empty($shop_id) || !is_numeric($shop_id)) {
      return;
      }
      if (!empty($shopTheme)) {
      // return;
      }

      if (in_array($currentFunction, $allowedFunctions) && !isset($_REQUEST ['theme'])) {
      $serviceExist = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceExistBlock', $args = array(
      'shop_id' => $shop_id,
      'type' => 'stdtheme'
      ));

      if ($serviceExist < 1) {
      return;
      }
      $sql = "SELECT theme from zselex_shop WHERE shop_id='" . $shop_id . "'";
      $query = DBUtil::executeSQL($sql);
      $result = $query->fetch();
      $shopTheme = $result ['theme'];

      if (!empty($shopTheme)) {
      $themeCount = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $args = array(
      'table' => 'themes',
      'where' => "name='" . $shopTheme . "'"
      ));

      if ($themeCount) {
      $event->setData($shopTheme);
      $event->stop();
      }
      }
      }
      }
     */

    /**
     * Listener for the `module_dispatch.custom_classname` event.
     *
     * Called during ModUtil::getTheme() and is used to filter the results.
     * Receives arg['type'] with the type of result to be filtered
     * and the $theme_name in the $event->data which can be modified.
     * Must $event->stop() if handler performs filter.
     */
    public function customClassname(Zikula_Event $event) {

        // echo $event->getData() . '<br>';
        // if (!SecurityUtil::checkPermission('News::', '::', ACCESS_ADMIN)) {
        if ($event->getData() == "News_Controller_User" && $_REQUEST ['func'] == 'newitem') {
            if (!SecurityUtil::checkPermission('News::', '::', ACCESS_ADMIN)) {
                // echo "event handler works";
                $event->setData("ZSELEX_Controller_User");
                // echo "hellooo here";
                $event->stop();
            }
        } elseif ($event->getData() == "News_Controller_User" && $_REQUEST ['func'] == 'create') {

            // echo $_POST['story']['shop_id']; exit;
            if (($_POST ['story'] ['shop_id'] != '' || $_POST ['story'] ['shopName'] != '') && ($_POST ['story'] ['purpose'] != 'event')) {
                $event->setData("ZSELEX_Controller_User");
                $event->stop();
            } elseif (($_POST ['story'] ['shop_id'] != '' || $_POST ['story'] ['shopName'] != '') && ($_POST ['story'] ['purpose'] == 'event')) {
                $event->setData("ZSELEX_Controller_Usernews");
                $event->stop();
            }
        } elseif ($event->getData() == "News_Api_User" && $_REQUEST ['func'] == 'create' && ($_POST ['story'] ['shop_id'] != '' || $_POST ['story'] ['shopName'])) {
            // echo "event handler works"; exit;
            $event->setData("ZSELEX_Api_User");
            $event->stop();
        } elseif ($event->getData() == "News_Api_Admin" && ($_REQUEST ['func'] == 'view' || $_REQUEST ['func'] == 'modify')) {
            // echo "workssss";
            if (!SecurityUtil::checkPermission('News::', '::', ACCESS_ADMIN)) {
                $event->setData("ZSELEX_Api_Admin");
                $event->stop();
            }
        } elseif (!SecurityUtil::checkPermission('News::', '::', ACCESS_ADMIN)) {
            if ($event->getData() == "News_Api_User" && $_GET ['func'] == 'view') {
                // echo "hello buddyyy";
                // $event->setData("ZSELEX_Api_User");
                // $event->stop();
            }
        }

        // }
        // if ($event->getData() == "News_Api_User" && $_REQUEST['func'] == 'shop') {
        // //$event->setData("ZSELEX_Api_User");
        // //$event->stop();
        // }
        // else{
        //
		// }
    }

    public function create(Zikula_Event $event) {

        // echo "Shop Id: " . $shop_id;

        /*
         * if ($_REQUEST['type'] == 'adminusers' && $_REQUEST['func'] == 'newUser') {
         * $loguser = UserUtil::getVar('uid');
         * $userRecord = $event->getSubject();
         * $shop_id = $_REQUEST['shop_id'];
         *
         *
         * $newUserId = $userRecord['uid'];
         *
         *
         * $shopAdminGroupId = $_REQUEST['shopAdminGroup'];
         *
         *
         *
         * $itemsAdmin = array(
         * 'shop_id' => $shop_id,
         * 'user_id' => $newUserId,
         * 'owner_id' => $loguser,
         * );
         *
         * $argsAdmin = array('table' => 'zselex_shop_admins', 'element' => $itemsAdmin, 'Id' => 'admin_id');
         * $resultAdmin = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $argsAdmin);
         *
         * $update = "UPDATE group_membership SET gid='" . $shopAdminGroupId . "' WHERE gid='1' AND uid='" . $newUserId . "'";
         * DBUtil::executeSQL($update);
         * }
         */
        $userRecord = $event->getSubject();
        // echo "<pre>"; print_r($userRecord); "</pre>"; exit;
    }

    public function delete(Zikula_Event $event) {
        // echo "<pre>"; print_r($_REQUEST); "</pre>"; exit;
        /*
         * if ($_REQUEST['type'] == 'adminusers' && $_REQUEST['func'] == 'deleteUsers') {
         * $loguser = UserUtil::getVar('uid');
         * $userRecord = $event->getSubject();
         * $newUserId = $userRecord['uid'];
         * $shop_id = $_REQUEST['shop_id'];
         *
         *
         * $deleteShopAdmins = ModUtil::apiFunc('ZSELEX', 'user', 'deleteItems', $args = array('table' => 'zselex_shop_admins',
         * 'where' => array("user_id=$newUserId", "shop_id=$shop_id")));
         * }
         *
         */
        $userRecord = $event->getSubject();
        // echo "<pre>"; print_r($userRecord); "</pre>"; exit;
        $userSid = $userRecord ['uid'];
        // $shop_id = $_REQUEST['shop_id'];
        $objOwner = array(
            'user_id' => 0
        );
        $whereOwner = "WHERE user_id='" . $userSid . "'";
        // DBUTil::updateObject($objOwner, 'zselex_shop', $whereOwner);

        $deleteShopOwner = ModUtil::apiFunc('ZSELEX', 'user', 'deleteItems', $args = array(
                    'table' => 'zselex_shop_owners',
                    'where' => array(
                        "user_id=$userSid"
                    )
        ));

        $deleteShopAdmin = ModUtil::apiFunc('ZSELEX', 'user', 'deleteItems', $args = array(
                    'table' => 'zselex_shop_admins',
                    'where' => array(
                        "user_id=$userSid"
                    )
        ));

        $deleteFB = ModUtil::apiFunc('ZSELEX', 'user', 'deleteItems', $args = array(
                    'table' => 'fconnect',
                    'where' => array(
                        "user_id=$userSid"
                    )
        ));

        $deleteGOOGLE = ModUtil::apiFunc('ZSELEX', 'user', 'deleteItems', $args = array(
                    'table' => 'google',
                    'where' => array(
                        "user_id=$userSid"
                    )
        ));

        $deleteTWITER = ModUtil::apiFunc('ZSELEX', 'user', 'deleteItems', $args = array(
                    'table' => 'twitter_login',
                    'where' => array(
                        "user_id=$userSid"
                    )
        ));
        return true;
    }

    public function update(Zikula_Event $event) {
        $userRecord = $event->getSubject();
        // echo "<pre>"; print_r($userRecord); "</pre>"; exit;
    }

}

?>
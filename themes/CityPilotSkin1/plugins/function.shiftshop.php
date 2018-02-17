<?php

function smarty_function_shiftshop($args, Zikula_View $view) {
    $shop_id = $args['shop_id'];
    if (empty($shop_id) || !(int) ($shop_id)) {
        return;
    }

   
    $user_id = UserUtil::getVar('uid');
    $perm = ModUtil::apiFunc('ZSELEX', 'admin', 'shopPermission', array(
                'shop_id' => $shop_id,
                'user_id' => $user_id
            ));
    if($perm < 1){
        return;
    }

    echo "<script>
      function shiftShop(val){
          var display;
                if(val == 'show'){
                      display = 'block';
                }
                else{
                      display = 'none';
                }
                document.getElementById('PopUpBG').style.display=display;
                document.getElementById('PopUpCenter').style.display=display;
            }
          </script>";



    $joinInfo[] = array('join_table' => 'zselex_city',
        'join_field' => array('city_id', 'city_name'),
        'object_field_name' => array('city_id', 'city_name'),
        'compare_field_table' => 'city_id', // main table
        'compare_field_join' => 'city_id');
    $getShop = ModUtil::apiFunc('ZSELEX', 'user', 'getByJoin', $args = array(
                'table' => 'zselex_shop',
                'joinInfo' => $joinInfo,
                'where' => "shop_id=$shop_id"
            ));
    $shopName = $getShop['shop_name'];
    $cityName = $getShop['city_name'];
    //echo "hellooo";
    //return  $view->fetch('templates/shiftshop.tpl');
    // echo "";

    $user_id = UserUtil::getVar('uid');

    if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
        $getOwner = ModUtil::apiFunc('ZSELEX', 'user', 'get', $args = array(
                    'table' => 'zselex_shop_owners',
                    'fields' => array('id', 'user_id'),
                    'where' => "shop_id=$shop_id"
                ));
        $final_user = $getOwner;
    } elseif (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD) && SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT)) {
        $getAdmin = ModUtil::apiFunc('ZSELEX', 'user', 'get', $args = array(
                    'table' => 'zselex_shop_admins',
                    'fields' => array('admin_id', 'user_id'),
                    'where' => "shop_id=$shop_id"
                ));
        $final_user = $getAdmin;
    }


    $current_owner_id = $final_user['user_id'];
    //echo "<pre>";   print_r($getOwner); echo "</pre>";
    if (empty($current_owner_id) || !(int) ($current_owner_id)) {
        return;
    }
    $joinInfo_shop[] = array('join_table' => 'zselex_shop',
        'join_method' => 'INNER JOIN',
        'join_field' => array('shop_id', 'shop_name', 'city_id'),
        'object_field_name' => array('shop_id', 'shop_name', 'city_id'),
        'compare_field_table' => 'shop_id', // main table
        'compare_field_join' => 'shop_id');

    $joinInfo_shop[] = array('join_table' => 'zselex_city',
        'join_field' => array('city_id', 'city_name'),
        'object_field_name' => array('city_id', 'city_name'),
        'compare_field_table' => 'a.city_id', // main table
        'compare_field_join' => 'city_id');
    if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
        $getAllShop = ModUtil::apiFunc('ZSELEX', 'user', 'getAllByJoin', $args = array(
                    'table' => 'zselex_shop_owners',
                    'joinInfo' => $joinInfo_shop,
                    'where' => "tbl.user_id=$current_owner_id"
                ));
    } elseif (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD) && SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT)) {
        $getAllShop = ModUtil::apiFunc('ZSELEX', 'user', 'getAllByJoin', $args = array(
                    'table' => 'zselex_shop_admins',
                    'joinInfo' => $joinInfo_shop,
                    'where' => "tbl.user_id=$current_owner_id"
                ));
    }

    //echo "Count :" . count($getAllShop);
    $count = count($getAllShop);
    //$shiftshop = ($count > 0) ? "<li onClick=shiftShop('show')><a href='#'>" . $view->__('Change Shop') . " >></a></li>" : '';
    $shiftshop = ($count > 1) ? "<li onClick=shiftShop('show')><a href='#'>" . $view->__('Change Shop') . " >></a></li>" : '';

    echo "<ul class='navlist'>
              <li>$shopName&nbsp;$cityName</li>
              $shiftshop
          </ul>";



    // echo "<pre>";   print_r($getAllShop); echo "</pre>";

    $output = '';

    $output .= "<div id='PopUpBG' class='PopUpBG' style='display:none'>";
    $output .= "</div>";
    $output .= "<div id='PopUpCenter' class='PopUpCenter' style='display:none'>";
    $output .= "<h3 class='PopUpBGH3'>" . $view->__('Change Shop') . "<span class='right ClosePopeup'><img onClick=shiftShop('hide') src='themes/CityPilot/images/Close_cart.jpg'  height='10'/></span></h3>";
    $output .= "<ul class='ShopSection'>";
    foreach ($getAllShop as $key => $value) {
        $output .= "<li><a href='" . ModUtil::url('ZSELEX', 'admin', 'shopsummary', array('shop_id' => $value[shop_id])) . "'>$value[shop_name] $value[city_name]</a></li>";
    }
    $output .= "</div>";
    $output .= "</ul>";

    echo $output;
}
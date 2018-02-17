<?php

function smarty_function_shiftshop($args, Zikula_View $view)
{
    //echo "comes here";  exit;
    $shop_id = $args['shop_id'];
    if (empty($shop_id) || !(int) ($shop_id)) {
        return;
    }

    $em      = ServiceUtil::getService('doctrine.entitymanager');
    $repo    = $em->getRepository('ZSELEX_Entity_Shop');
    $user_id = UserUtil::getVar('uid');

    $perm = ZSELEX_Util::shopPermission($shop_id);
    // echo "perm : " . $perm;
    if ($perm < 1) {
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


    $shopArg  = array(
        'entity' => 'ZSELEX_Entity_Shop',
        'fields' => array('b.city_id', 'b.city_name', 'a.shop_name'),
        'joins' => array('LEFT JOIN a.city b'),
        'where' => array('a.shop_id' => $shop_id),
        'groupby' => 'a.shop_id'
    );
    $getShop  = $repo->get($shopArg);
    //echo "<pre>";   print_r($getShop); echo "</pre>";
    $shopName = $getShop['shop_name'];
    $shopName = strip_tags($shopName);
    $cityName = $getShop['city_name'];
    $cityName = strip_tags($cityName);
    //echo "hellooo";
    //return  $view->fetch('templates/shiftshop.tpl');
    // echo "";

    $user_id = UserUtil::getVar('uid');


    if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
        //echo "ADD";

        $ownrArg    = array(
            'entity' => 'ZSELEX_Entity_ShopOwner',
            'fields' => array('a.id', 'a.user_id'),
            'where' => "a.shop=$shop_id AND (a.co_owner!=1 OR a.main=1)"
        );
        $final_user = $repo->fetch($ownrArg);
    } elseif (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD) && SecurityUtil::checkPermission('ZSELEX::',
            '::', ACCESS_EDIT)) {
        // echo "EDIT";

        $adminArg   = array(
            'entity' => 'ZSELEX_Entity_ShopAdmin',
            'fields' => array('a.admin_id', 'a.user_id'),
            'where' => array('a.shop' => $shop_id, 'a.user_id' => $user_id)
        );
        $final_user = $repo->get($adminArg);
    }



    // echo "<pre>";  print_r($final_user);  echo "</pre>";
    $current_owner_id = $final_user['user_id'];
    // echo $current_owner_id;
    // $current_owner_id = $user_id;
    //echo "<pre>";   print_r($getOwner); echo "</pre>";
    if (empty($current_owner_id) || !(int) ($current_owner_id)) {
        return;
    }

    // echo "comes here2";  exit;
    //  echo $current_owner_id;  exit;

    /*
      if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {


      $allShopArg = array(
      'entity' => 'ZSELEX_Entity_ShopOwner',
      'fields' => array('c.city_id', 'c.city_name', 'b.shop_name', 'b.shop_id'),
      'joins' => array('INNER JOIN a.shop b', 'LEFT JOIN b.city c',),
      'where' => array('a.user_id' => $current_owner_id),
      'groupby' => 'b.shop_id'
      );
      $getAllShop = $repo->getAll($allShopArg);
      } elseif (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD) && SecurityUtil::checkPermission('ZSELEX::',
      '::', ACCESS_EDIT)) {


      $allShopArg = array(
      'entity' => 'ZSELEX_Entity_ShopAdmin',
      'fields' => array('c.city_id', 'c.city_name', 'b.shop_name', 'b.shop_id'),
      'joins' => array('INNER JOIN a.shop b', 'LEFT JOIN b.city c',),
      'where' => array('a.user_id' => $current_owner_id),
      'groupby' => 'b.shop_id'
      );
      $getAllShop = $repo->getAll($allShopArg);
      }
     */

    $getAllShop1 = [];
    $getAllShop2 = [];


    /* $allShopArg1 = array(
      'entity' => 'ZSELEX_Entity_ShopOwner',
      'fields' => array('c.city_id', 'c.city_name', 'b.shop_name', 'b.shop_id'),
      'joins' => array('INNER JOIN a.shop b', 'LEFT JOIN b.city c',),
      'where' => array('a.user_id' => $current_owner_id),
      'groupby' => 'b.shop_id'
      );
      $getAllShop1 = $repo->getAll($allShopArg1); */

    $where     = "a.user_id=:current_owner_id";
    $setParams = array(
        'current_owner_id' => $user_id
    );

    //if super admin
    if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
        $where     = "(a.user_id=:current_owner_id OR a.user_id=:user_id)";
        $setParams = array(
            'current_owner_id' => $current_owner_id,
            'user_id' => $user_id,
        );
    }

    // echo $where;

    $allShopArg1 = array(
        'entity' => 'ZSELEX_Entity_ShopOwner',
        'fields' => array('c.city_id', 'c.city_name', 'b.shop_name', 'b.shop_id'),
        'joins' => array('INNER JOIN a.shop b', 'LEFT JOIN b.city c',),
        'where' => $where,
        'setParams' => $setParams,
        // 'groupby' => 'b.shop_id'
    );
    $getAllShop1 = $repo->fetchAll($allShopArg1);

    // echo "<pre>";  print_r($getAllShop1);  echo "</pre>";


    $allShopArg2 = array(
        'entity' => 'ZSELEX_Entity_ShopAdmin',
        'fields' => array('c.city_id', 'c.city_name', 'b.shop_name', 'b.shop_id'),
        'joins' => array('INNER JOIN a.shop b', 'LEFT JOIN b.city c',),
        'where' => $where,
        'setParams' => $setParams,
        // 'groupby' => 'b.shop_id'
    );
    $getAllShop2 = $repo->fetchAll($allShopArg2);


    $getAllShop = array_merge($getAllShop1, $getAllShop2);

    $temp = [];
    foreach ($getAllShop as $k => $v) {

        if (in_array($v['shop_id'], $temp)) {
            unset($getAllShop[$k]);
        }
        $temp[] = $v['shop_id'];
    }

    // echo "<pre>";   print_r($getAllShop); echo "</pre>";
    //echo "Count :" . count($getAllShop);
    $count     = count($getAllShop);
    //$shiftshop = ($count > 0) ? "<li onClick=shiftShop('show')><a href='#'>" . $view->__('Change Shop') . " >></a></li>" : '';
    $shiftshop = ($count > 1) ? "<li onClick=shiftShop('show')><a href='#'>".$view->__('Change Shop')." >></a></li>"
            : '';

    echo "<ul class='navlist'>
              <li>".stripslashes($shopName)."&nbsp;$cityName</li>
              $shiftshop
          </ul>";



    // echo "<pre>";   print_r($getAllShop); echo "</pre>";

    $output = '';

    $output .= "<div id='PopUpBG' class='PopUpBG' style='display:none'>";
    $output .= "</div>";
    $output .= "<div id='PopUpCenter' class='PopUpCenter' style='display:none'>";
    $output .= "<h3 class='PopUpBGH3'>".$view->__('Change Shop')."<span class='right ClosePopeup'><img onClick=shiftShop('hide') src='themes/CityPilot/images/Close_cart.jpg'  height='10'/></span></h3>";
    $output .= "<ul class='ShopSection'>";
    foreach ($getAllShop as $key => $value) {
        $output .= "<li><a href='".ModUtil::url('ZSELEX', 'admin',
                'shopsummary', array('shop_id' => $value[shop_id]))."'>$value[shop_name] $value[city_name]</a></li>";
    }
    $output .= "</div>";
    $output .= "</ul>";

    echo $output;
}

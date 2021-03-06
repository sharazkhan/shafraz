<?php

function alteraccountmenu_icons($icon, &$accountlink) {
	switch ($icon) {
		case 'locale.png': $accountlink['icon'] = 'CPchangelanguage.png'; break;
		case 'password.png': $accountlink['icon'] = 'CPchangepassword.png'; break;
		case 'message.png': $accountlink['icon'] = 'CPemailaddressmanager.png'; break;
		case 'exit.png': $accountlink['icon'] = 'CPlogout.png'; break;
		case 'news_add.gif': $accountlink['icon'] = 'CPsendarticle.png'; break;
		case 'admin.png': $accountlink['icon'] = 'CPadministrationpanel.png'; break;
	}
}
	
function smarty_function_alteraccountmenu($params, &$smarty) {

    $new_accountlinks = $params['accountlinks'];
    $admin = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN);
    $add = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD);
    $edit = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT);

    if ((!$admin && $add) || (!$admin && !$add && $edit)) {
        foreach ($new_accountlinks as $key => $link) {
            //echo $link['url'] . '<br>';
            if ($link['url'] == "index.php?module=adminpanel&type=admin&func=adminpanel") {
                $new_accountlinks[$key]['url'] = ModUtil::url('ZSELEX', 'admin', 'shopsummary', array('shop_id' => $_SESSION['mainshop']));
				$new_accountlinks[$key]['icon'] = 'CPadmin.png';
            } elseif ($link['url'] == "news/newitem/" || $link['url'] == "index.php?module=news&type=user&func=newitem") {
                unset($new_accountlinks[$key]);
            } else
				alteraccountmenu_icons($link['icon'], $new_accountlinks[$key]);
        }

        $new_array = array(
            'url' => ModUtil::url('ZSELEX', 'user', 'site', array('shop_id' => $_SESSION['mainshop'])),
            'module' => 'ZSELEX',
            'title' => __('My Site'),
            'icon' => 'CPmysite.png'
        );
        $new_accountlinks['ZSELEX01'] = $new_array;
    } elseif ($admin) {
        foreach ($new_accountlinks as $key => $link) {
			alteraccountmenu_icons($link['icon'], $new_accountlinks[$key]);
        }

        $new_array = array(
            'url' => ModUtil::url('ZSELEX', 'admin', 'viewshop'),
            'module' => 'ZSELEX',
            'title' => __('CityPilot administration'),
            'icon' => 'CPadmin.png'
        );
        $new_accountlinks['ZSELEX01'] = $new_array;
	}

    //array_push();
    // echo "<pre>";print_r($new_accountlinks);echo "</pre>";

    $smarty->assign("new_accountlinks", $new_accountlinks);
    //$smarty->assign("link", $link);
}

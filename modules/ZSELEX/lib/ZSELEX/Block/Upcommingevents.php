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
class ZSELEX_Block_Upcommingevents extends Zikula_Controller_AbstractBlock
{

    /**
     * initialise block
     */
    public function init()
    {
        // SecurityUtil::registerPermissionSchema('ZSELEX:upcommingevents:', 'Block title::');
    }

    /**
     * get information on block
     */
    public function info()
    {
        return array(
            'text_type' => 'selection',
            'module' => 'ZSELEX',
            'text_type_long' => $this->__('Display Upcomming Events'),
            'allow_multiple' => true,
            'form_content' => false,
            'form_refresh' => false,
            'show_preview' => true,
            'admin_tableless' => true
        );
    }

    /**
     * display block
     */
    public function display($blockinfo)
    {

        // print_r($_REQUEST);
        // return false;
        // echo "<pre>"; print_r($blockinfo); echo "</pre>";
        if (!SecurityUtil::checkPermission('ZSELEX:upcommingevents:',
                "$blockinfo[title]::", ACCESS_OVERVIEW)) {
            // return;
        }
        if (!ModUtil::available('ZSELEX')) {
            return;
        }
        // $shop_id = !empty($_REQUEST['shop_id']) ? $_REQUEST['shop_id'] : $_REQUEST['shop_idnewItem'];
        $loguser = UserUtil::getVar('uid');
        $loguser = !empty($loguser) ? $loguser : 0;
        $admin   = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN);
        $edit    = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT);

        $vars     = BlockUtil::varsFromContent($blockinfo ['content']);
        $thislang = ZLanguage::getLanguageCode();
        if (!array_key_exists($thislang, $vars ['blockinfo'])) {
            $thislang = 'en';
        }
        $modvariable = $this->getVars();

        $country_id    = $modvariable ['default_country_id'];
        $region_id     = $_COOKIE ['region_cookie'];
        $city_id       = $_COOKIE ['city_cookie'];
        $area_id       = $_COOKIE ['area_cookie'];
        $shop_id       = $_COOKIE ['shop_cookie'];
        $branch_id     = $_COOKIE ['branch_cookie'];
        $branch_id_url = FormUtil::getPassedValue("branch_id");
        if ($branch_id_url > 0) {
            $branch_id = $branch_id_url;
        }
        $aff_id     = $_COOKIE ['affiliate_cookie'];
        $aff_id_url = FormUtil::getPassedValue("aff_id");
        if ($aff_id_url) {
            $aff_id = $aff_id_url;
        }
        $category_id = $_COOKIE ['category_cookie'];
        $search      = stripslashes(htmlspecialchars($_COOKIE ['search_cookie']));

        // echo "<pre>"; print_r($vars); echo "</pre>";
        $eventlimit = $vars ['upcommingeventlimit'];

        // echo $aff_id;

        $event_args = array(
            'country_id' => $country_id,
            'region_id' => $region_id,
            'city_id' => $city_id,
            'area_id' => $area_id,
            'shop_id' => $shop_id,
            'category_id' => $category_id,
            'branch_id' => $branch_id,
            'search' => $search,
            'aff_id' => $aff_id,
            'eventlimit' => $eventlimit
        );

        $upcommingevents = ModUtil::apiFunc('ZSELEX', 'user', 'upcommingEvents',
                $event_args);

        // echo "<pre>"; print_r($upcommingevents); echo "</pre>";
        // $upcommingevents = $this->getupcommingEvents();
        // $count = count($upcommingevents);
        // echo $totalcount = ModUtil::apiFunc('ZSELEX', 'user', 'upcommingEventsCount', $vars);
        // echo "count :" . $totalcount;
        // echo "<pre>"; print_r($upcommingevents); echo "</pre>";
        // echo $blockinfo['bid'];
        // ZSELEX_Controller_User::display($args);
        // echo "<pre>"; print_r($upcommingevents['events2']); echo "</pre>";
        $totalcount = $upcommingevents ['count'];

        $info ['title'] = $vars ['blockinfo'] [$thislang] ['infotitle'];

        $this->view->assign('bid', $blockinfo ['bid']);
        $this->view->assign('info', $info);
        $this->view->assign('vars', $vars);
        $this->view->assign('eventlimit', $eventlimit);
        $this->view->assign('totalcount', $totalcount);
        // $this->view->assign('count', $count);
        $this->view->assign('admin', $admin);
        // $this->view->assign('add', $add);
        $this->view->assign('events', $upcommingevents ['events']);

        // $this->view->assign('shopconfig', $shopconfig);

        $blockinfo ['content'] = $this->view->fetch('blocks/upcommingevents/upcommingevents.tpl');

        return BlockUtil::themeBlock($blockinfo);
    }

    /**
     * modify block settings .
     * .
     */
    public function modify($blockinfo)
    {
        $vars = BlockUtil::varsFromContent($blockinfo ['content']);
        if (empty($vars ['showAdminZSELEXinBlock'])) {
            $vars ['showAdminZSELEXinBlock'] = 0;
        }

        // echo "<pre>"; print_r($vars); echo "</pre>";

        $blockinfo = $vars ['blockinfo'];
        if (!empty($blockinfo)) {

            $exist = true;
        } else {

            $exist = false;
        }

        // echo $exist;

        $languages = ZLanguage::getInstalledLanguages();

        // echo "<pre>"; print_r($languages); echo "</pre>";

        $this->view->assign('languages', $languages);
        $this->view->assign('vars', $vars);
        $this->view->assign('exist', $exist);
        $this->view->assign('blockinfo', $blockinfo);

        return $this->view->fetch('blocks/upcommingevents/upcommingevents_modify.tpl');
    }

    /**
     * update block settings
     */
    public function update($blockinfo)
    {
        $vars = BlockUtil::varsFromContent($blockinfo ['content']);

        $vars ['displayinfo']         = FormUtil::getPassedValue('displayinfo',
                '', 'POST');
        $vars ['upcommingeventlimit'] = FormUtil::getPassedValue('upcommingeventlimit',
                '', 'POST');
        $vars ['blockinfo']           = FormUtil::getPassedValue('blockinfo',
                '', 'POST');

        // write back the new contents
        $blockinfo ['content'] = BlockUtil::varsToContent($vars);

        // clear the block cache
        $this->view->clear_cache('blocks/upcommingevents/upcommingevents_modify.tpl');

        return $blockinfo;
    }
}
// end class def
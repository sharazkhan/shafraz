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
class ZSELEX_Block_Googlemap extends Zikula_Controller_AbstractBlock
{
    public $amount;

    /**
     * initialise block
     */
    public function init()
    {
        error_reporting(0);
        SecurityUtil::registerPermissionSchema('ZSELEX:googlemap:',
            'Block title::');
    }

    /**
     * get information on block
     */
    public function info()
    {
        return array(
            'text_type' => 'selection',
            'module' => 'ZSELEX',
            'text_type_long' => $this->__('Google Map'),
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
        // return;
        // exit;
        // echo "Comes here..........";
        if (!SecurityUtil::checkPermission('ZSELEX:googlemap:',
                "$blockinfo[title]::", ACCESS_OVERVIEW)) {
            return;
        }
        if (!ModUtil::available('ZSELEX')) {
            return;
        }
        $loguser = UserUtil::getVar('uid');
        $loguser = !empty($loguser) ? $loguser : 0;
        $vars    = BlockUtil::varsFromContent($blockinfo ['content']);
        $shop_id = !empty($_REQUEST ['shop_id']) ? $_REQUEST ['shop_id'] : $_REQUEST ['shop_idnewItem'];

        $admin = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN);
        $edit  = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT);

        if ($admin) {
            $perm = $admin;
        } else if ($edit) {
            $perm = $edit;
        } else {
            $perm = '';
        }

        // /////////////////check service exists//////////////////////////
        // $serviceExist = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $args = array('table' => 'zselex_serviceshop',
        // 'where' => "shop_id=$shop_id AND type='minisitegooglemap'"));
        $serviceExist = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceExistBlock',
                $args         = array(
                'shop_id' => $shop_id,
                'type' => 'minisitegooglemap'
        ));

        if ($serviceExist < 1) {
            return;
        }

        // ////////////////////////////////////////////////////////
        // //////////////////////service permission/////////////////////////////////////
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            $servicePermCount = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount',
                    $args             = array(
                    'table' => 'zselex_serviceshop',
                    'where' => "shop_id=$shop_id AND type='minisitegooglemap'
                        AND(owner_id=$loguser OR owner_id 
                            IN(SELECT owner_id FROM zselex_shop_admins WHERE user_id=$loguser))"
            ));

            if ($servicePermCount > 0) {
                $servicePerm = 1;
            } else {
                $servicePerm = 0;
            }
        } elseif (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            $servicePerm      = 2;
            $servicePermCount = 2;
        }
        // /////////////////////////////////////////////////////////////////

        $joinInfo = array(
            array(
                'join_table' => 'zselex_country',
                'join_field' => array(
                    'country_id',
                    'country_name'
                ),
                'object_field_name' => array(
                    'country_id',
                    'country_name'
                ),
                'compare_field_table' => 'country_id', // main table
                'compare_field_join' => 'country_id'
            ),
            array(
                'join_table' => 'zselex_region',
                'join_field' => array(
                    'region_id',
                    'region_name'
                ),
                'object_field_name' => array(
                    'region_id',
                    'region_name'
                ),
                'compare_field_table' => 'region_id', // main table
                'compare_field_join' => 'region_id'
            ),
            array(
                'join_table' => 'zselex_city',
                'join_field' => array(
                    'city_id',
                    'city_name'
                ),
                'object_field_name' => array(
                    'city_id',
                    'city_name'
                ),
                'compare_field_table' => 'city_id', // main table
                'compare_field_join' => 'city_id'
            ),
            array(
                'join_table' => 'zselex_area',
                'join_field' => array(
                    'area_id',
                    'area_name'
                ),
                'object_field_name' => array(
                    'area_id',
                    'area_name'
                ),
                'compare_field_table' => 'area_id', // main table
                'compare_field_join' => 'area_id'
            )
        );

        $get  = ModUtil::apiFunc('ZSELEX', 'user', 'getByJoin',
                $args = array(
                'table' => 'zselex_shop',
                'where' => "tbl.shop_id=$shop_id",
                'joinInfo' => $joinInfo
        ));

        // echo "country : " . $country . " , region : " . $region . " , city : " . $city . " , area : " . $area;

        $country    = $get ['country_name'];
        $region     = $get ['region_name'];
        $city       = $get ['city_name'];
        $area       = $get ['area_name'];
        $shop_name  = $get ['shop_name'];
        $address    = str_replace(" ", "%20", $get ['address']);
        $mapaddress = nl2br($get ['address']);
        // echo $mapaddress;
        // $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $address . ',+' . $city . ',+' . $region . ',+' . $country . '&sensor=false');
        $geocode    = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$address.'&sensor=false');

        $output = json_decode($geocode); // Store values in variable
        // echo "<pre>"; print_r($output); echo "</pre>";
        $lat    = $output->results [0]->geometry->location->lat;
        $long   = $output->results [0]->geometry->location->lng;

        // echo "lat :" . $lat . " lng : " . $long;

        if ($vars ['map_height'] != '') {
            $height = $vars ['map_height'];
        } else {
            $height = 300;
        }

        if ($vars ['map_width'] != '') {
            $width = $vars ['map_width'];
        } else {
            $width = 500;
        }

        if (!empty($vars ['map_zoom'])) {
            $zoom = $vars ['map_zoom'];
        } else {
            $zoom = 16;
        }
        $currentTheme = System::getVar('Default_Theme');

        $displaymap = "<div id='map' style='width: ".$width."px; height: ".$height."px'></div>";
        if ($currentTheme == 'CityPilotResponsive') {
            $width      = '100';
            $height     = '400';
            $displaymap = "<div id='map' style='width: ".$width."%; height: ".$height."px'></div>";
        }

        if (empty($shop_id)) {
            return false;
        }
        $thislang = ZLanguage::getLanguageCode();

        $info ['title'] = $vars ['blockinfo'] [$thislang] ['infotitle'];

        $this->view->assign('zoom', $zoom);
        $this->view->assign('perm', $perm);
        $this->view->assign('servicePerm', $servicePermCount);
        $this->view->assign('lat', $lat);
        $this->view->assign('long', $long);
        $this->view->assign('shop_name', $shop_name);
        $this->view->assign('mapaddress', $mapaddress);
        $this->view->assign('bid', $blockinfo ['bid']);
        $this->view->assign('info', $info);
        $this->view->assign('vars', $vars);
        $this->view->assign('displaymap', $displaymap);

        $blockinfo ['content'] = $this->view->fetch('blocks/googlemap/gmap.tpl');

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

        $languages = ZLanguage::getInstalledLanguages();

        // echo "<pre>"; print_r($vars['blockinfo']); echo "</pre>";
        // echo count($vars['blockinfo']);

        $this->view->assign('vars', $vars);
        $this->view->assign('languages', $languages);

        return $this->view->fetch('blocks/googlemap/gmap_modify.tpl');
    }

    /**
     * update block settings
     */
    public function update($blockinfo)
    {
        $vars = BlockUtil::varsFromContent($blockinfo ['content']);

        // alter the corresponding variable
        $vars ['showAdminZSELEXinBlock'] = FormUtil::getPassedValue('showAdminZSELEXinBlock',
                '', 'POST');

        $vars ['displayinfo'] = FormUtil::getPassedValue('displayinfo', '',
                'POST');
        $vars ['map_height']  = FormUtil::getPassedValue('map_height', '',
                'POST');
        $vars ['map_width']   = FormUtil::getPassedValue('map_width', '', 'POST');
        $vars ['map_zoom']    = FormUtil::getPassedValue('map_zoom', '', 'POST');

        $vars ['blockinfo'] = FormUtil::getPassedValue('blockinfo', '', 'POST');

        // write back the new contents
        $blockinfo ['content'] = BlockUtil::varsToContent($vars);

        // clear the block cache
        $this->view->clear_cache('blocks/googlemap/gmap_modify.tpl');

        return $blockinfo;
    }
}
// end class def
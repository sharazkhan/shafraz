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
class ZSELEX_Block_Selection extends Zikula_Controller_AbstractBlock
{

    /**
     * initialise block
     */
    public function init()
    {
        // SecurityUtil::registerPermissionSchema('ZSELEX:selectionblock:', 'Block title::');
        if ($_REQUEST ['func'] == '') {
            setcookie("last_shop_id", "", time() + (86400 * 7), '/');
        }
    }

    /**
     * get information on block
     */
    public function info()
    {
        return array(
            'text_type' => 'selection',
            'module' => 'ZSELEX',
            'text_type_long' => $this->__('Selection drop down box'),
            'allow_multiple' => true,
            'form_content' => false,
            'form_refresh' => false,
            'show_preview' => true,
            'admin_tableless' => true
        );
    }

    /**
     * display block
     *
     * @return HTML
     */
    public function display($blockinfo)
    {

        // echo "selection box";
        if (!SecurityUtil::checkPermission('ZSELEX:selectionblock:',
                "$blockinfo[title]::", ACCESS_OVERVIEW)) {
            // return;
        }
        if (!ModUtil::available('ZSELEX')) {
            return;
        }
        $current_theme = System::getVar('Default_Theme');

        $thislang = ZLanguage::getLanguageCode();
        // echo "Lang :" . $thislang;
        $vars     = BlockUtil::varsFromContent($blockinfo ['content']);

        $input = FormUtil::getPassedValue('region', '', 'POST');

        $modvariable  = $this->getVars();
        $country_id   = $modvariable ['default_country_id'];
        $country_name = $modvariable ['default_country_name'];
        // $country_cookie = 61;
        if (isset($_COOKIE ['country_cookie']) && $_COOKIE ['country_cookie'] > 0) {
            $country_cookie = $_COOKIE ['country_cookie'];
        } else {
            $country_cookie = $country_id;
        }

        $area_cookie      = $_COOKIE ['area_cookie'];
        $areaname_cookie  = $_COOKIE ['areaname_cookie'];
        $shop_cookie      = $_COOKIE ['shop_cookie'];
        $branch_cookie    = $_COOKIE ['branch_cookie'];
        $affiliate_cookie = $_COOKIE ['affiliate_cookie'];

        $branch_id_url = FormUtil::getPassedValue("branch_id", null, 'GET');
        $lock          = FormUtil::getPassedValue("lock", null, 'GET');
        $unlock        = FormUtil::getPassedValue("unlock", null, 'GET');

        $cookieTime = time() + (86400 * 7);
        if ($lock) {
            $cookieTime = 0;
            setcookie("lock_cookie", 1, 0, '/');
        }

        if ($unlock) {
            // echo "unlock";
            setcookie("lock_cookie", 0, time() - (86400 * 7), '/');
        }

        // echo "lock :" . $_COOKIE['lock_cookie'];
        $branchSelect = 0;
        if (isset($branch_id_url) && !empty($branch_id_url)) {
            $branch_id_url = $branch_id_url;
            $branchSelect ++;
        } else {
            $branch_id_url = $branch_cookie;
        }

        // echo "branch_id_url : ". $branch_id_url;

        if ($branch_id_url) {
            $branchItem = $this->entityManager->getRepository('ZSELEX_Entity_Branch')->get(array(
                'entity' => 'ZSELEX_Entity_Branch',
                'fields' => array(
                    'a.branch_name'
                ),
                'where' => array(
                    'a.branch_id' => $branch_id_url
                )
            ));
        }
        if ($branchItem) {
            $branch_cookie = $branch_id_url;
            if ($branchSelect) {
                setcookie("branch_cookie", $branch_id_url, $cookieTime, '/');
                setcookie("branch_name_cookie", $branchItem ['branch_name'],
                    $cookieTime, '/');
            }
        } else {
            setcookie("branch_cookie", $branch_id_url, time() - (86400 * 7), '/');
            setcookie("branch_name_cookie", $branchItem ['branch_name'],
                time() - (86400 * 7), '/');
            $branch_cookie = '';
        }
        $this->view->assign('branchNameCookie', $branchItem ['branch_name']);

        // echo "branch :" . $branch_cookie;
        $affSelect  = 0;
        $aff_id_url = FormUtil::getPassedValue("aff_id", null, 'GET');
        if (isset($aff_id_url) && !empty($aff_id_url)) {
            $affiliate_cookie = $aff_id_url;
            $affSelect ++;
        } /*
         * else {
         * //$aff_id_url = $_COOKIE['affiliate_cookie'];
         * }
         */

        // echo $aff_id_url;

        if (!empty($affiliate_cookie)) {
            $affIds  = explode(',', $affiliate_cookie);
            // echo "<pre>"; print_r($affIds); echo "</pre>";
            $affItem = $this->entityManager->getRepository('ZSELEX_Entity_ShopAffiliation')->get(array(
                'entity' => 'ZSELEX_Entity_ShopAffiliation',
                'fields' => array(
                    'a.aff_name'
                ),
                'where' => array(
                    'a.aff_id' => $affIds [0]
                )
            ));
            // echo "<pre>"; print_r($affItem); echo "</pre>";
            if ($affItem) { //
                if ($affSelect) {
                    setcookie("affiliate_cookie", $affiliate_cookie,
                        $cookieTime, '/');
                    setcookie("aff_name_cookie", $affItem ['aff_name'],
                        $cookieTime, '/');
                }
                $this->view->assign('affNameCookie', $affItem ['aff_name']);
            } else {
                setcookie("affiliate_cookie", $affiliate_cookie,
                    time() - (86400 * 7), '/');
                setcookie("aff_name_cookie", $affItem ['aff_name'],
                    time() - (86400 * 7), '/');
            }
        }
        $category_cookie     = $_COOKIE ['category_cookie'];
        $categoryName_cookie = $_COOKIE ['categoryName_cookie'];
        // echo $categoryName_cookie;
        // echo "AreaId: " . $area_cookie;
        // echo "Area Name: " . $areaname_cookie;

        $region_cookie = $_COOKIE ['region_cookie'];
        $city_cookie   = $_COOKIE ['city_cookie'];

        $search_cookie = stripslashes(htmlspecialchars($_COOKIE ['search_cookie']));
        // echo $search_cookie;

        $area_where   = " AND a.area_id!=''";
        // $region_where['a.region_id'] = "''";
        $region_where = array();
        $city_where   = array();
        $shop_where   = 'status=1';

        if (!empty($country_cookie)) { // COUNTRY
            $area_where .= " AND a.country=$country_cookie";
            $region_where ['a.country'] = $country_cookie;
            $city_where ['a.country']   = $country_cookie;
            // $shop_where .= " AND country_id=$country_cookie";
        }

        if (!empty($region_cookie)) { // REGION
            $area_where .= " AND a.region=$region_cookie";
            $city_where ['a.region'] = $region_cookie;
            // $shop_where .= " AND region_id=$region_cookie";
        }

        if (!empty($city_cookie)) { // CITY
            $area_where .= " AND a.city=$city_cookie";
            $shop_where .= " AND city_id=$city_cookie";
        }

        if (!empty($area_cookie)) { // AREA
            $shop_where .= " AND a.area_id=$area_cookie";
        }

        // echo $shop_where . '<br>';

        $regions = $this->entityManager->getRepository('ZSELEX_Entity_Region')->getAll(array(
            'entity' => 'ZSELEX_Entity_Region',
            'fields' => array(
                'a.region_id',
                'a.region_name'
            ),
            'where' => $region_where
        ));
        // echo "<pre>"; print_r($regions); echo "</pre>";

        $cities = $this->entityManager->getRepository('ZSELEX_Entity_City')->getAll(array(
            'entity' => 'ZSELEX_Entity_City',
            'fields' => array(
                'a.city_id',
                'a.city_name'
            ),
            'where' => $city_where
        ));

        // echo "<pre>"; print_r($cities); echo "</pre>";

        $areaArgs = array(
            'sql' => $area_where
        );
        $areas    = $this->entityManager->getRepository('ZSELEX_Entity_Area')->getAreas($areaArgs);

        // echo "<pre>"; print_r($areas2); echo "</pre>";
        /*
         * $branchargs = array('table' => 'zselex_branch', 'where' => '', 'orderBy' => '', 'useJoins' => '');
         * $branchs = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements', $branchargs);
         */

        /*
         * $catargs = array('table' => 'zselex_category', 'where' => '', 'orderBy' => 'category_name', 'useJoins' => '');
         * $category = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements', $catargs);
         */
        $catArgs  = array();
        $category = $this->entityManager->getRepository('ZSELEX_Entity_Category')->getCategories($catArgs);
        // sort($category);
        // echo "<pre>"; print_r($categorys); echo "</pre>";
        // echo "<pre>"; print_r($branchs); echo "</pre>"; exit;
        // echo count($products);
        // $shopconfig = ModUtil::apiFunc('ZSELEX', 'admin', 'getShopConfig', $vars);
        // echo "<pre>"; print_r($shopconfig); echo "</pre>";
        // echo $shopconfig['dbname'];

        $countrysrch       = $_GET ['country'];
        $regionsrch        = $_GET ['region'];
        $citysrch          = $_GET ['city'];
        $shopsrch          = $_GET ['shop'];
        $info ['title']    = $vars ['blockinfo'] [$thislang] ['infotitle'];
        $info ['message']  = $vars ['blockinfo'] [$thislang] ['infomessage'];
        $currentFunc       = FormUtil::getPassedValue('func', null, 'REQUEST');
        $searchPlaceHolder = $this->__('search for...');
        

        $this->view->assign("current_theme", $current_theme);
        $this->view->assign('country_id', $country_id);
        $this->view->assign('country_name', $country_name);
        $this->view->assign('area_cookie', $area_cookie);
        $this->view->assign('shop_cookie', $shop_cookie);
        $this->view->assign('region_cookie', $region_cookie);
        $this->view->assign('city_cookie', $city_cookie);
        $this->view->assign('branch_cookie', $branch_cookie);
        $this->view->assign('affiliate_cookie', $affiliate_cookie);
        $this->view->assign('category_cookie', $category_cookie);
        $this->view->assign('search_cookie', $search_cookie);
        $this->view->assign('categoryName_cookie', $categoryName_cookie);

        // echo "Country :" . $country_cookie . " Region :" . $region_cookie . " City :" . $city_cookie . " Area : " . $area_cookie . " Shop :" . $shop_cookie . " Branch :" . $branch_cookie . " Category :" . $category_cookie;

        $this->view->assign('bid', $blockinfo ['bid']);
        $this->view->assign('vars', $vars);
        $this->view->assign('info', $info);
        $this->view->assign('countryCount', $countryCount);
        $this->view->assign('countries', $countries);
        $this->view->assign('regions', $regions);
        $this->view->assign('cities', $cities);
        $this->view->assign('areas', $areas);
        $this->view->assign('shops', $shops);
        $this->view->assign('shopconfig', $shopconfig);
        $this->view->assign('zshops', $shops);
        $this->view->assign('category', $category);
        $this->view->assign('branchs', $branchs);
        $this->view->assign('countrysrch', $countrysrch);
        $this->view->assign('regsrch', $regionsrch);
        $this->view->assign('citysrch', $citysrch);
        $this->view->assign('shopsrch', $shopsrch);
        $this->view->assign('thislang', $thislang);
        $this->view->assign("search_place_holder", $searchPlaceHolder);

        $blockinfo ['content'] = $this->view->fetch('blocks/selection.tpl');

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
        $shops     = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
        // echo "<pre>"; print_r($shops); echo "</pre>";
        $this->view->assign('vars', $vars);
        $this->view->assign('languages', $languages);
        $this->view->assign('zshops', $shops);

        return $this->view->fetch('blocks/selection_modify.tpl');
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
        $vars ['shop']                   = FormUtil::getPassedValue('shop', '',
                'POST');
        $vars ['amount']                 = FormUtil::getPassedValue('amount',
                '', 'POST');
        $vars ['orderby']                = FormUtil::getPassedValue('orderby',
                '', 'POST');

        $vars ['displayinfo'] = FormUtil::getPassedValue('displayinfo', '',
                'POST');

        $vars ['blockinfo'] = FormUtil::getPassedValue('blockinfo', '', 'POST');

        // write back the new contents
        $blockinfo ['content'] = BlockUtil::varsToContent($vars);

        // clear the block cache
        $this->view->clear_cache('blocks/selection_modify.tpl');

        return $blockinfo;
    }
}
// end class def
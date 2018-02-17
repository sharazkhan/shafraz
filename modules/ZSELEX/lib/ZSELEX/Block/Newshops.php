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
class ZSELEX_Block_Newshops extends Zikula_Controller_AbstractBlock
{

    /**
     * initialise block
     */
    public function init()
    {
        // SecurityUtil::registerPermissionSchema('ZSELEX:Newshops:', 'Block title::');
    }

    /**
     * get information on block
     */
    public function info()
    {
        return array(
            'text_type' => 'hello',
            'module' => 'ZSELEX',
            'text_type_long' => $this->__('New Shops in front end'),
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
        if (!SecurityUtil::checkPermission('ZSELEX:Newshops:',
                "$blockinfo[title]::", ACCESS_OVERVIEW)) {
            // return;
        }
        if (!ModUtil::available('ZSELEX')) {
            return;
        }
        $current_theme = System::getVar('Default_Theme');
        // $current_theme = "CityPilot";
        PageUtil::addVar('stylesheet',
            'themes/'.$current_theme.'/style/rating.css');
        $modvariable   = $this->getVars();
        $current_theme = System::getVar('Default_Theme');

        $vars = BlockUtil::varsFromContent($blockinfo ['content']);

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
        $category_id = $_COOKIE ['category_cookie'];
        $search      = stripslashes(htmlspecialchars($_COOKIE ['search_cookie']));

        $shopfrontorder = $modvariable ["shoporderby"];
        $shopfrontlimit = $modvariable ["shopfrontlimit"];

        $aff_id     = $_COOKIE ['affiliate_cookie'];
        $aff_id_url = FormUtil::getPassedValue("aff_id");
        if ($aff_id_url) {
            $aff_id = $aff_id_url;
        }
        // echo "<pre>"; print_r($aff_id); echo "</pre>";

        $shop_args = array(
            'country_id' => $country_id,
            'region_id' => $region_id,
            'city_id' => $city_id,
            'area_id' => $area_id,
            'shop_id' => $shop_id,
            'category_id' => $category_id,
            'branch_id' => $branch_id,
            'aff_id' => $aff_id,
            'search' => $search,
            'eventlimit' => $eventlimit,
            'shopfrontorder' => $shopfrontorder,
            'shopfrontlimit' => $shopfrontlimit
        );

        $newshops = ModUtil::apiFunc('ZSELEX', 'user', 'getNewShops', $shop_args);

        // echo "<pre>"; print_r($new_shops); echo "</pre>";
        // $newshops = $this->getNewShops($args);
        // echo "<pre>"; print_r($products); echo "</pre>"; ;

        $this->view->assign('vars', $vars);
        $this->view->assign('count', count($newshops));
        $this->view->assign('newshops', $newshops);

        $blockinfo ['content'] = $this->view->fetch('blocks/Newshops/newshops.tpl');

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
        // $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);

        $this->view->assign('vars', $vars);

        return $this->view->fetch('blocks/Newshops/newshops_modify.tpl');
    }

    /**
     * update block settings
     */
    public function update($blockinfo)
    {
        $vars = BlockUtil::varsFromContent($blockinfo ['content']);
        // alter the corresponding variable

        $vars ['shop']    = FormUtil::getPassedValue('shop', '', 'POST');
        $vars ['amount']  = FormUtil::getPassedValue('amount', '', 'POST');
        $vars ['orderby'] = FormUtil::getPassedValue('orderby', '', 'POST');

        // write back the new contents
        $blockinfo ['content'] = BlockUtil::varsToContent($vars);

        // clear the block cache
        $this->view->clear_cache('blocks/Newshops/newshops.tpl');

        return $blockinfo;
    }
}
// end class def
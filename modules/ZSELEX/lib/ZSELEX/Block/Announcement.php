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
class ZSELEX_Block_Announcement extends Zikula_Controller_AbstractBlock
{

    /**
     * initialise block
     */
    public function init()
    {
        SecurityUtil::registerPermissionSchema('ZSELEX:Announcement:',
            'Block title::');
    }

    /**
     * get information on block
     */
    public function info()
    {
        return array(
            'text_type' => 'Announcement',
            'module' => 'ZSELEX',
            'text_type_long' => $this->__('Ministe Announcement'),
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
        if (!SecurityUtil::checkPermission('ZSELEX:Announcement:',
                "$blockinfo[title]::", ACCESS_OVERVIEW)) {
            return;
        }
        if (!ModUtil::available('ZSELEX')) {
            return;
        }
        // PageUtil::addVar('stylesheet', 'themes/CityPilot/style/announcement.css');
        $vars = BlockUtil::varsFromContent($blockinfo ['content']);

        $shop_id = $_REQUEST ['shop_id'];
        if (empty($shop_id)) {
            return;
        }

        $serviceExist = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceExistBlock',
                $args         = array(
                'shop_id' => $shop_id,
                'type' => 'minisiteannouncement'
            ));

        // echo $serviceExist;
        if ($serviceExist < 1) {
            return;
        }
        $announcement = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                $args         = array(
                'table' => 'zselex_shop_announcement',
                'where' => "shop_id=$shop_id AND status='1'"
            ));
        // echo $announcement;
        if (!$announcement) {
            return;
        }
        $start_date = $announcement ['start_date'];
        $end_date   = $announcement ['end_date'];

        $todayDate = date("Y-m-d");

        $text = $announcement ['text'];
        // $text = wordwrap($text, "35", "<br>", 1);
        if (empty($text)) {
            return;
        }
        $count = strlen($text);

        if (!empty($start_date)) {
            if ($todayDate < $start_date) {
                return;
            }
        }
        if (!empty($start_date)) {
            if ($end_date < $todayDate) {
                return;
            }
        }

        $array   = array();
        $explode = explode("\n", $text);
        // $explode = array_filter($explode);

        foreach ($explode as $key => $val) {
            // if(ctype_space($val)) {
            if (trim($val) == '') {
                unset($explode [$key]);
            }
        }
        $array         = $explode;
        // echo "<pre>"; print_r($array); echo "</pre>";
        // echo $shopconfig['dbname'];
        $this->view->assign('vars', $vars);
        $this->view->assign('ownerName', $ownerName);
        // echo $text; exit;
        $this->view->assign('text', $text);
        $current_theme = System::getVar('Default_Theme');
        $this->view->assign('current_theme', $current_theme);

        $blockinfo ['content'] = $this->view->fetch('blocks/announcement/announcement.tpl');

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

        $shops = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray',
                $args  = array(
                'table' => 'zselex_shop s , zselex_minishop m',
                'where' => array(
                    "s.shop_id=m.shop_id",
                    "m.shoptype='zSHOP'"
                )
            ));
        // echo "<pre>"; print_r($shops); echo "</pre>";
        $this->view->assign('vars', $vars);
        $this->view->assign('zshops', $shops);

        return $this->view->fetch('blocks/announcement/announcement_modify.tpl');
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

        // write back the new contents
        $blockinfo ['content'] = BlockUtil::varsToContent($vars);

        // clear the block cache
        $this->view->clear_cache('blocks/announcement/announcement_modify.tpl');

        return $blockinfo;
    }
}
// end class def
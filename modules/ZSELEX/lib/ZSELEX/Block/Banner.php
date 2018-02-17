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
class ZSELEX_Block_Banner extends Zikula_Controller_AbstractBlock
{

    /**
     * initialise block
     */
    public function init()
    {
        SecurityUtil::registerPermissionSchema('ZSELEX:Banner:', 'Block title::');
    }

    /**
     * get information on block
     */
    public function info()
    {
        return array(
            'text_type' => 'Banner',
            'module' => 'ZSELEX',
            'text_type_long' => $this->__('Ministe Banner'),
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
        // return;
        if (!SecurityUtil::checkPermission('ZSELEX:Banner:',
                "$blockinfo[title]::", ACCESS_OVERVIEW)) {
            return;
        }
        if (!ModUtil::available('ZSELEX')) {
            return;
        }

        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Shop');

        $loguser = UserUtil::getVar('uid');
        $loguser = !empty($loguser) ? $loguser : 0;
        $user_id = $loguser;
        $vars    = BlockUtil::varsFromContent($blockinfo ['content']);

        $shop_id = $_REQUEST ['shop_id'];
        if (empty($shop_id)) {
            return;
        }

        $serviceExist = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceExistBlock',
                $args         = array(
                'shop_id' => $shop_id,
                'type' => 'minisitebanner'
        ));

        // echo $serviceExist;
        if ($serviceExist < 1) {
            return;
        }

        $announcement = $repo->get(array(
            'entity' => 'ZSELEX_Entity_Announcement',
            'fields' => array(
                'a.status'
            ),
            'where' => array(
                'a.shop' => $shop_id
            )
        ));

        // echo "<pre>"; print_r($announcement); echo "</pre>";
        if ($announcement) {
            if ($announcement['status'] < 1) {
                return;
            }
        }


        // $perm = ModUtil::apiFunc('ZSELEX', 'admin', 'shopPermission', array('shop_id' => $shop_id, 'user_id' => $user_id));
        $perm = $_REQUEST ['perm'];
        // echo "perm: " . $perm;


        $getBanner = $repo->get(array(
            'entity' => 'ZSELEX_Entity_Banner',
            'fields' => array(
                'a.banner_image'
            ),
            'where' => array(
                'a.shop' => $shop_id
            )
        ));
        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));

        $image = "";
        if (!empty($getBanner ['banner_image'])) {
            // list($width, $height, $type, $attr) = getimagesize("zselexdata/$ownerName/banner/resized/$getBanner[banner_image]");
            // echo "width: " . $width;
            // echo "height: " . $height;
            if ($width) {
                $style = "style=width:100%";
            }
            $image = "<img src=zselexdata/$ownerName/banner/resized/$getBanner[banner_image] $style>";
        }

        // echo "<pre>"; print_r($products); echo "</pre>";
        // echo $shopconfig['dbname'];
        $this->view->assign('vars', $vars);
        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('perm', $perm);
        $this->view->assign('ownerName', $ownerName);
        $this->view->assign('getBanner', $getBanner);
        $current_theme = System::getVar('Default_Theme');
        $this->view->assign('current_theme', $current_theme);

        $blockinfo ['content'] = $this->view->fetch('blocks/banner/banner1.tpl');

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

        return $this->view->fetch('blocks/banner/banner_modify.tpl');
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
        $this->view->clear_cache('blocks/hello.tpl');

        return $blockinfo;
    }
}
// end class def
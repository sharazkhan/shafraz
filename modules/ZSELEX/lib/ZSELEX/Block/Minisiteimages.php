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
class ZSELEX_Block_Minisiteimages extends Zikula_Controller_AbstractBlock
{
    public $amount;

    /**
     * initialise block
     */
    public function init()
    {
        SecurityUtil::registerPermissionSchema('ZSELEX:minisiteimages :',
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
            'text_type_long' => $this->__('Minisite Images Block'),
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

        // echo "Images Comes Here"; return;
        // print_r($_REQUEST);
        // return false;
        // echo "<pre>"; print_r($blockinfo); echo "</pre>";
        if (!SecurityUtil::checkPermission('ZSELEX:minisiteimages:',
                "$blockinfo[title]::", ACCESS_OVERVIEW)) {
            return;
        }
        if (!ModUtil::available('ZSELEX')) {
            return;
        }
        $shop_id = !empty($_REQUEST ['shop_id']) ? $_REQUEST ['shop_id'] : $_REQUEST ['shop_idnewItem'];
        $loguser = UserUtil::getVar('uid');
        $loguser = !empty($loguser) ? $loguser : 0;
        $user_id = $loguser;
        $admin   = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN);
        $edit    = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT);

        if ($admin) {
            $perm = $admin;
        } else if ($edit) {
            $perm = $edit;
        } else {
            $perm = '';
        }

        // /////////////////check service exists//////////////////////////

        $serviceExist = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceExistBlock',
                $args         = array(
                'shop_id' => $shop_id,
                'type' => 'minisiteimages'
            ));

        // echo "<pre>"; print_r($serviceExist); echo "</pre>";

        if ($serviceExist < 1) {
            return false;
        }

        // ////////////////////////////////////////////////////////
        // $servicePerm = 1;
        // echo "count minisiteimage :" . $servicePermCount;

        $vars     = BlockUtil::varsFromContent($blockinfo ['content']);
        $thislang = ZLanguage::getLanguageCode();
        if (!array_key_exists($thislang, $vars ['blockinfo'])) {
            $thislang = 'en';
        }

        $shop_id = !empty($_REQUEST ['shop_id']) ? $_REQUEST ['shop_id'] : $_REQUEST ['shop_idnewItem'];

        if (empty($shop_id)) {
            return false;
        }

        $perm = FormUtil::getPassedValue('perm', '', 'REQUEST');

        $service         = $this->entityManager->getRepository('ZSELEX_Entity_Product')->get(array(
            'entity' => 'ZSELEX_Entity_ServiceShop',
            'fields' => array(
                'a.quantity'
            ),
            'where' => array(
                'a.shop' => $shop_id,
                'a.type' => 'minisiteimages'
            )
            ));
        $serviceQuantity = $service ['quantity'];

        $totalImages = $this->entityManager->getRepository('ZSELEX_Entity_MinisiteImage')->getCount(null,
            'ZSELEX_Entity_MinisiteImage', 'file_id',
            array(
            'a.shop' => $shop_id
            ));

        $limit = 0;

        $qtyLeft = $serviceQuantity - $totalImages;
        if ($qtyLeft < $totalImages) {
            $limit = $serviceQuantity;
        }
        // echo $limit;

        $images = $this->entityManager->getRepository('ZSELEX_Entity_MinisiteImage')->getImages(array(
            'shop_id' => $shop_id,
            'limit' => $limit
            ));

        // echo "<pre>"; print_r($images); echo "</pre>";
        // echo "<pre>"; print_r($e); echo "</pre>";
        // echo "count :" . count($images);
        $count = count($images);
        // echo "count :" . $count;
        if ((count($images) < 1) && (!$perm)) {
            return;
        }

        $info ['title'] = $vars ['blockinfo'] [$thislang] ['infotitle'];
        // $info['message'] = $vars['blockinfo'][$thislang]['infomessage'];
        // echo "<pre>"; print_r($info); echo "</pre>";
        $this->view->assign('perm', $perm);
        $this->view->assign('count', $count);
        $this->view->assign('servicePerm', $servicePerm);
        $this->view->assign('bid', $blockinfo ['bid']);
        $this->view->assign('info', $info);
        $this->view->assign('vars', $vars);
        $this->view->assign('admin', $admin);
        $this->view->assign('add', $add);
        $this->view->assign('shoptype', $shopType);
        $this->view->assign('ownerName', $ownerName);
        $this->view->assign('images', $images);
        $this->view->assign('shop_id', $shop_id);
        // $this->view->assign('shopconfig', $s
        // $this->view->assign('shopconfig', $shopconfig);

        $blockinfo ['content'] = $this->view->fetch('blocks/minisiteimagesblock/minisiteimages.tpl');

        return BlockUtil::themeBlock($blockinfo);
    }

    public function getInfo($blockinfo)
    {
        
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

        $ishopargs = array(
            'table' => 'zselex_shop',
            'fields' => '',
            'where' => array(
                'shoptype_id' => '2'
            )
        );
        $ishops    = ModUtil::apiFunc('ZSELEX', 'admin', 'selectItems',
                $ishopargs);

        // echo "<pre>"; print_r($adtypes); echo "</pre>";

        $languages = ZLanguage::getInstalledLanguages();

        // echo "<pre>"; print_r($languages); echo "</pre>";

        $this->view->assign('languages', $languages);
        $this->view->assign('vars', $vars);
        $this->view->assign('ishops', $ishops);

        return $this->view->fetch('blocks/minisiteimagesblock/minisiteimages_modify.tpl');
    }

    /**
     * update block settings
     */
    public function update($blockinfo)
    {
        $vars = BlockUtil::varsFromContent($blockinfo ['content']);

        // echo "<pre>"; print_r(FormUtil::getPassedValue('blockinfo', '', 'POST')); echo "</pre>"; exit;
        // alter the corresponding variable
        $vars ['showAdminZSELEXinBlock'] = FormUtil::getPassedValue('showAdminZSELEXinBlock',
                '', 'POST');

        $vars ['amount']      = FormUtil::getPassedValue('amount', '', 'POST');
        $vars ['shop_id']     = FormUtil::getPassedValue('shop_id', '', 'POST');
        $vars ['orderby']     = FormUtil::getPassedValue('orderby', '', 'POST');
        $vars ['displayinfo'] = FormUtil::getPassedValue('displayinfo', '',
                'POST');
        // $vars['infotitle'] = FormUtil::getPassedValue('infotitle', '', 'POST');
        // $vars['infomessage'] = FormUtil::getPassedValue('infomessage', '', 'POST');

        $vars ['blockinfo'] = FormUtil::getPassedValue('blockinfo', '', 'POST');

        // write back the new contents
        $blockinfo ['content'] = BlockUtil::varsToContent($vars);

        // clear the block cache
        $this->view->clear_cache('blocks/dealoftheday_modify.tpl');

        return $blockinfo;
    }
}
// end class def
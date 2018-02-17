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
class ZSELEX_Block_Minisiteevent extends Zikula_Controller_AbstractBlock
{
    public $shoptype;
    public $ownername;

    /**
     * initialise block // similar to constructor
     */
    public function init()
    {
        // echo "helloo";
        SecurityUtil::registerPermissionSchema('ZSELEX:minisiteevent:',
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
            'text_type_long' => $this->__('Display Minisite Event'),
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
        // return;
        // echo "<pre>"; print_r($blockinfo); echo "</pre>";
        // echo $this->ownername;
        if (!SecurityUtil::checkPermission('ZSELEX:minisiteevent:',
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
        $shoptype = ModUtil::apiFunc('ZSELEX', 'admin', 'shopType',
                $args     = array(
                'shop_id' => $shop_id
        ));
        $shoptype = $shoptype ['shoptype'];
        $this->view->assign('shoptype', $shoptype);

        // echo "<pre>"; print_r($get); echo "</pre>";
        $servicePerm = ModUtil::apiFunc('ZSELEX', 'admin', 'shopPermission',
                array(
                'shop_id' => $shop_id,
                'user_id' => $user_id
        ));
        // $event = $this->getEvent($args = array('shop_id' => $shop_id));

        $events     = $this->entityManager->getRepository('ZSELEX_Entity_Event')->getMinisiteEvent(array(
            'shop_id' => $shop_id
        )); //
        // echo "<pre>"; print_r($events2); echo "</pre>";
        // echo "<pre>"; print_r($events); echo "</pre>";
        $event_doc  = urlencode($event ['event_doc']);
        $path_parts = pathinfo($event_doc);
        $extension  = $path_parts ['extension'];
        // echo "<pre>"; print_r($events); echo "</pre>";
        // echo "count :" . count($event);
        // if (count($event) < 1) {
        // return;
        // }

        if (empty($event ['shop_event_name'])) {
            // return;
        }

        // $event = DBUtil::selectObjectByID('zselex_shop_events', $shop_id, 'shop_id');
        if (!empty($shop_id)) {
            $ownername = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                    $args      = array(
                    'shop_id' => $shop_id
            ));
        }
        foreach ($events as $key => $event) {
            $event_doc                   = urlencode($event ['event_doc']);
            $path_parts                  = pathinfo($event_doc);
            $extension                   = $path_parts ['extension'];
            $events [$key] ['extension'] = $extension;
            // $events[$key]['extension'] = $extension;

            if ($event ['showfrom'] == 'article') {
                $news_id = $event ['news_article_id'];

                $article                   = $this->entityManager->getRepository('ZSELEX_Entity_Event')->getNewsArticle(array(
                    'news_id' => $news_id
                ));
                $events [$key] ['article'] = $article;
                // $this->view->assign('info', $article);
            } elseif ($event ['showfrom'] == 'product') {
                $product_id = $event ['product_id'];
                // echo "<pre>"; print_r($product); echo "</pre>";

                if ($shoptype == 'iSHOP') {
                    $finalproduct                   = $this->entityManager->getRepository('ZSELEX_Entity_Event')->getProduct(array(
                        'product_id' => $product_id
                    ));
                    $events [$key] ['ishopProduct'] = $finalproduct;
                    // echo "<pre>"; print_r($finalproduct); echo "</pre>";
                } elseif ($shoptype == 'zSHOP') {
                    $zencart     = $this->entityManager->getRepository('ZSELEX_Entity_ZenShop')->getZenCart(array(
                        'shop_id' => $shop_id
                    ));
                    // echo "<pre>"; print_r($zencart); echo "</pre>";
                    $this->view->assign('zencart', $zencart);
                    $zenproducts = ModUtil::apiFunc('ZSELEX', 'admin',
                            'getZenCartProduct',
                            array(
                            'shop' => $zencart,
                            'shop_id' => $shop_id,
                            'product_id' => $product_id
                    ));
                    // echo "<pre>"; print_r($zenproducts); echo "</pre>";

                    $finalproduct                   = $zenproducts;
                    $events [$key] ['zshopProduct'] = $finalproduct;
                }
                // echo "<pre>"; print_r($finalproduct); echo "</pre>";
                // $this->view->assign('product', $finalproduct);
            }

            if ($event ['event_doc'] != '') {
                $events [$key] ['pdf_image'] = basename($event ['event_doc'],
                    '.pdf');
            }
        }

        // echo "<pre>"; print_r($finalproduct); echo "</pre>";
        // echo "<pre>"; print_r($events); echo "</pre>";
        // echo $blockinfo['bid'];
        // ZSELEX_Controller_User::display($args);
        // $perm = ModUtil::apiFunc('ZSELEX', 'admin', 'shopPermission', array('shop_id' => $shop_id, 'user_id' => $user_id));

        $perm = FormUtil::getPassedValue('perm', '', 'REQUEST');

        $count = sizeof($events);
        if (($count < 1) && (!$perm)) {
            return;
        }
        $info ['title'] = $vars ['blockinfo'] [$thislang] ['infotitle'];
        // $info['message'] = $vars['blockinfo'][$thislang]['infomessage'];
        // echo "<pre>"; print_r($info); echo "</pre>";
        $this->view->assign('count', $count);
        $this->view->assign('perm', $perm);
        $this->view->assign('servicePerm', $servicePerm);
        $this->view->assign('bid', $blockinfo ['bid']);
        $this->view->assign('info', $info);
        $this->view->assign('vars', $vars);
        $this->view->assign('admin', $admin);
        $this->view->assign('add', $add);
        $this->view->assign('ownername', $ownername);
        $this->view->assign('events', $events);
        $this->view->assign('extension', $extension);
        $this->view->assign('event_doc', $event_doc);
        $this->view->assign('shop_id', $shop_id);

        // $this->view->assign('shopconfig', $shopconfig);

        $blockinfo ['content'] = $this->view->fetch('blocks/minisiteevent/minisiteevent.tpl');

        return BlockUtil::themeBlock($blockinfo);
    }

    public function getEvent($args)
    {
        $sql   = "SELECT * FROM zselex_shop_events WHERE shop_event_startdate <=CURDATE() AND shop_event_enddate >=CURDATE()
                AND shop_id=$args[shop_id] ORDER BY RAND()";
        $query = DBUtil::executeSQL($sql);
        $event = $query->fetch();
        return $event;
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

        return $this->view->fetch('blocks/minisiteevent/minisiteevent_modify.tpl');
    }

    /**
     * update block settings
     */
    public function update($blockinfo)
    {
        $vars = BlockUtil::varsFromContent($blockinfo ['content']);

        $vars ['displayinfo'] = FormUtil::getPassedValue('displayinfo', '',
                'POST');

        $vars ['blockinfo'] = FormUtil::getPassedValue('blockinfo', '', 'POST');

        // write back the new contents
        $blockinfo ['content'] = BlockUtil::varsToContent($vars);

        // clear the block cache
        $this->view->clear_cache('blocks/minisiteevent/minisiteevent_modify.tpl');

        return $blockinfo;
    }
}
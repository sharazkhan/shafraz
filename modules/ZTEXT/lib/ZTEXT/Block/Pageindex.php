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
class ZTEXT_Block_Pageindex extends Zikula_Controller_AbstractBlock {

    /**
     * initialise block
     */
    public function init() {
        SecurityUtil::registerPermissionSchema('ZTEXT:Pageindex:', 'Block title::');
    }

    /**
     * get information on block
     */
    public function info() {
        return array(
            'text_type' => 'ZTEXT',
            'module' => 'ZTEXT',
            'text_type_long' => $this->__('ZTEXT Page Index'),
            'allow_multiple' => true,
            'form_content' => false,
            'form_refresh' => false,
            'show_preview' => true,
            'admin_tableless' => true);
    }

    /**
     * display block
     */
    public function display($blockinfo) {//
        //return false;
        if (!SecurityUtil::checkPermission('ZTEXT:Pageindex:', "$blockinfo[title]::", ACCESS_OVERVIEW)) {
            return;
        }
        $this->view->setCaching(false);
        if (!ModUtil::available('ZTEXT')) {
            return;
        }
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $serviceExist = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceExistBlock', $args = array('shop_id' => $shop_id,
                    'type' => 'pages'));

       // echo "<pre>"; print_r($serviceExist);  echo "</pre>";

        if ($serviceExist < 1) {
            return false;
        }

        $repo = $this->entityManager->getRepository('ZTEXT_Entity_Page');
        $page_setting = $repo->get(array('entity' => 'ZTEXT_Entity_PageSetting',
            'fields'=> array('a.disable_page_index'),
            'where' => array('a.shop' => $shop_id)));
        
        $disable_page_index = 0;
         if ($page_setting['disable_page_index']) {
            //return false;
             $disable_page_index = 1;
        }
        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        // echo "<pre>";  print_r($vars);  echo "</pre>"; 
        $page_arags = array(
            'entity' => 'ZTEXT_Entity_Page',
            'fields' => array('a.text_id', 'a.headertext'),
            'where' => 'a.shop=:shop_id AND a.active=1',
            'setParams' => array('shop_id' => $shop_id),
                // 'orderby' => 'a.shop_event_id DESC'
        );
        $pages = $repo->fetchAll($page_arags);
        //echo count($products);
        //echo "<pre>";  print_r($pages);  echo "</pre>"; 

        $this->view->assign('disable_page_index', $disable_page_index);
        $this->view->assign('vars', $vars);
        $this->view->assign('pages', $pages);
        $user_id = UserUtil::getVar('uid');
        $perm = ModUtil::apiFunc('ZSELEX', 'admin', 'shopPermission', array('shop_id' => $shop_id, 'user_id' => $user_id));
        $this->view->assign('perm', $perm);


        $blockinfo['content'] = $this->view->fetch('blocks/pageindex.tpl');

        return BlockUtil::themeBlock($blockinfo);
    }

}

// end class def
<?php

/**
 * Copyright acta-it.dk
 *
 * Zvelo

 */

/**
 * Class to control Block display and interface
 */
class Zvelo_Block_RightBlock extends Zikula_Controller_AbstractBlock {

    /**
     * initialise block
     */
    public function init() {
        SecurityUtil::registerPermissionSchema('Zvelo:RightBlock:', 'Block title::');
    }

    /**
     * get information on block
     */
    public function info() {
        return array(
            'text_type' => 'RightBlock',
            'module' => 'Zvelo',
            'text_type_long' => $this->__('Right Block'),
            'allow_multiple' => true,
            'form_content' => false,
            'form_refresh' => false,
            'show_preview' => true,
            'admin_tableless' => true);
    }

    /**
     * display block
     */
    public function display($blockinfo) {
        if (!SecurityUtil::checkPermission('Zvelo:RightBlock:', "$blockinfo[title]::", ACCESS_OVERVIEW)) {
            return;
        }
        if (!ModUtil::available('Zvelo')) {
            return;
        }
        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        //echo "<pre>";  print_r($products);  echo "</pre>"; 
        // echo $shopconfig['dbname'];
        $this->view->assign('vars', $vars);
        $customer_id = $_SESSION['current_customer_id'];
        $this->view->assign('customer_id', $customer_id);
        $this->view->assign('shopconfig', $shopconfig);

        $blockinfo['content'] = $this->view->fetch('blocks/rightblock.tpl');

        return BlockUtil::themeBlock($blockinfo);
    }

    /**
     * modify block settings ..
     */
    /* public function modify($blockinfo) {
      $vars = BlockUtil::varsFromContent($blockinfo['content']);
      if (empty($vars['showAdminZSELEXinBlock'])) {
      $vars['showAdminZSELEXinBlock'] = 0;
      }

      $this->view->assign('vars', $vars);
      $this->view->assign('zshops', $shops);

      return $this->view->fetch('blocks/leftblock_modify.tpl');
      } */

    /**
     * update block settings
     */
    public function update($blockinfo) {
        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        // alter the corresponding variable
        $vars['showAdminZSELEXinBlock'] = FormUtil::getPassedValue('showAdminZSELEXinBlock', '', 'POST');
        $vars['shop'] = FormUtil::getPassedValue('shop', '', 'POST');
        $vars['amount'] = FormUtil::getPassedValue('amount', '', 'POST');
        $vars['orderby'] = FormUtil::getPassedValue('orderby', '', 'POST');

        // write back the new contents
        $blockinfo['content'] = BlockUtil::varsToContent($vars);

        // clear the block cache
        $this->view->clear_cache('blocks/rightblock.tpl');

        return $blockinfo;
    }

}

// end class def
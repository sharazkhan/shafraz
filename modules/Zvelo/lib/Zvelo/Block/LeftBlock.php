<?php

/**
 * Copyright acta-it.dk
 *
 * Zvelo

 */

/**
 * Class to control Block display and interface
 */
class Zvelo_Block_LeftBlock extends Zikula_Controller_AbstractBlock {

    /**
     * initialise block
     */
    public function init() {
        SecurityUtil::registerPermissionSchema('Zvelo:LeftBlock:', 'Block title::');
    }

    /**
     * get information on block
     */
    public function info() {
        return array(
            'text_type' => 'LeftBlock',
            'module' => 'Zvelo',
            'text_type_long' => $this->__('Left Block'),
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
        if (!SecurityUtil::checkPermission('Zvelo:LeftBlock:', "$blockinfo[title]::", ACCESS_OVERVIEW)) {
            return;
        }
        if (!ModUtil::available('Zvelo')) {
            return;
        }
        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        //echo "<pre>";  print_r($products);  echo "</pre>"; 
        // echo $shopconfig['dbname'];
        $this->view->assign('vars', $vars);
        $customerInfo = array();
        if (!empty($_SESSION['current_customer_id'])) {
            $customerInfo = $this->entityManager->getRepository('Zvelo_Entity_Customer')->getCustmerInfo(array('customer_id' => $_SESSION['current_customer_id']));
            $wish = $this->entityManager->getRepository('Zvelo_Entity_CustomerWish')->getWish(array('customer_id' => $_SESSION['current_customer_id']));
            $bicycleBlock = $this->entityManager->getRepository('Zvelo_Entity_Bicycle')->getBicycleDetailByCustomerId(array('customer_id' => $_SESSION['current_customer_id']));
        }
        // echo "<pre>";  print_r($customerInfo);  echo "</pre>"; 
        // echo "<pre>";  print_r($wish);  echo "</pre>"; 
        //echo "<pre>";  print_r($bicycleBlock);  echo "</pre>"; exit;
        $this->view->assign('customerInfo', $customerInfo);
        $this->view->assign('wish', $wish);


        $blockinfo['content'] = $this->view->fetch('blocks/leftblock.tpl');

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
        $this->view->clear_cache('blocks/leftblock.tpl');

        return $blockinfo;
    }

}

// end class def
<?php

/**
 * Copyright ACTA-IT 2014 - ZBlocks
 *
 * ZBlocks
 * Payment Gateway Module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control Admin interface
 */
class ZBlocks_Controller_Admin extends Zikula_AbstractController {

    /**
     * the main administration function
     * This function is the default function, and is called whenever the
     * module is initiated without defining arguments.
     */
    public function main() {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZBlocks::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        //$this->redirect(ModUtil::url('ZBlocks', 'admin', 'payments'));
         return $this->view->fetch('admin/info.tpl');
    }

    /**
     * @desc set caching to false for all admin functions
     * @return      null
     */
    public function postInitialize() {
        $this->view->setCaching(false);
    }

    /**
     * @desc present administrator options to change module configuration
     * @return      config template
     */
    public function modifyconfig() {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZBlocks::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $modvars = ModUtil::getVar('ZBlocks');
        // echo "<pre>"; print_r($modvars);  echo "</pre>";
        $CardsAccepted = unserialize($modvars['CardsAccepted']);
        //echo "<pre>"; print_r($CardsAccepted);  echo "</pre>";
        $this->view->assign('CardsAccepted', $CardsAccepted);
        return $this->view->fetch('admin/modifyconfig.tpl');
    }

    /**
     * @desc sets module variables as requested by admin
     * @return      status/error ->back to modify config page
     */
    public function updateconfig() {
        $this->checkCsrfToken();

        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZBlocks::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        // echo "<pre>"; print_r($_POST);  echo "</pre>"; exit;
        $modvars = array();
        //$modvars['showAdminZBlocks'] = FormUtil::getPassedValue('showAdminZBlocks', 0);
        $modvars['Netaxept_enabled_general'] = FormUtil::getPassedValue('Netaxept_enabled_general', 0);
        $modvars['Paypal_enabled_general'] = FormUtil::getPassedValue('Paypal_enabled_general', 0);
        $modvars['Directpay_enabled_general'] = FormUtil::getPassedValue('Directpay_enabled_general', 0);
        $modvars['QuickPay_enabled_general'] = FormUtil::getPassedValue('QuickPay_enabled_general', 0);
        $modvars['Epay_enabled_general'] = FormUtil::getPassedValue('Epay_enabled_general', 0);

        $CardsAccepted = FormUtil::getPassedValue('CardsAccepted', null, 'REQUEST');
        //echo "<pre>"; print_r($CreditCarsAccepted);  echo "</pre>"; exit;
        $modvars['CardsAccepted'] = serialize($CardsAccepted);

        // set the new variables
        $this->setVars($modvars);

        // clear the cache
        $this->view->clear_cache();

        LogUtil::registerStatus($this->__('Done! Updated the ZBlocks configuration.'));
        return $this->modifyconfig();
    }

    /**
     * @desc present administrator information
     * @return      template
     */
    public function info() {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZBlocks::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        return $this->view->fetch('admin/info.tpl');
    }

}

// end class def
<?php
/**
 * Copyright ACTA-IT 2013 - ZMAP
 *
 * ZMAP
 * Demonstration of Zikula Module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control Admin interface
 */
class ZMAP_Controller_Admin extends Zikula_AbstractController
{
    /**
     * the main administration function
     * This function is the default function, and is called whenever the
     * module is initiated without defining arguments.
     */
    public function main()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZMAP::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
		$this->redirect(ModUtil::url('ZMAP', 'admin', 'modifyconfig'));
    }
    /**
     * @desc present administrator options to change module configuration
     * @return      config template
     */
    public function modifyconfig()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZMAP::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        return $this->view->fetch('admin/modifyconfig.tpl');
    }
    /**
     * @desc sets module variables as requested by admin
     * @return      status/error ->back to modify config page
     */
    public function updateconfig()
    {
        $this->checkCsrfToken();
        
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZMAP::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        $modvars = array();
        $modvars['showAdminZMAP'] = FormUtil::getPassedValue('showAdminZMAP', 0);

        // set the new variables
        $this->setVars($modvars);
    
        // clear the cache
        $this->view->clear_cache();
    
        LogUtil::registerStatus($this->__('Done! ZMAP configuration was updated successfully.'));
        return $this->modifyconfig();
    }
    /**
     * @desc present administrator information
     * @return      template
     */
    public function info()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZMAP::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        return $this->view->fetch('admin/info.tpl');
    }
    /**
     * @desc set caching to false for all admin functions
     * @return      null
     */
    public function postInitialize()
    {
        $this->view->setCaching(false);
    }
} // end class def
<?php

/**
 * Copyright ACTA-IT 2015 - ZBlocks
 *
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control Installer interface
 */
class ZBlocks_Installer extends Zikula_AbstractInstaller {

    /**
     * Initializes a new install
     *
     * This function will initialize a new installation.
     * It is accessed via the Zikula Admin interface and should
     * not be called directly.
     *
     * @return  boolean    true/false
     */
    public function install() {
        // create table

        return true;
    }

    /**
     * Upgrades an old install
     *
     * This function is used to upgrade an old version
     * of the module.  It is accessed via the Zikula
     * Admin interface and should not be called directly.
     *
     * @param   string    $oldversion Version we're upgrading
     * @return  boolean   true/false
     */
    public function upgrade($oldversion) {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZBlocks::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        switch ($oldversion) {

            case '0.0.1':
            //future development
        }

        return true;
    }

    /**
     * removes an install
     *
     * This function removes the module from your
     * Zikula install and should be accessed via
     * the Zikula Admin interface
     *
     * @return  boolean    true/false
     */
    public function uninstall() {

        return true;
    }

}

// end class def
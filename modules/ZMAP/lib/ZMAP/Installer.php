<?php

/**
 * Copyright R2International 2013 - Zmap
 *
 * Zmap
 * Demonstration of Zikula Module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control Installer interface
 */
class Zmap_Installer extends Zikula_AbstractInstaller {

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
        if (!DBUtil::createTable('zmap')) {
            return LogUtil::registerError($this->__('Error! Could not create the table.'));
        }
        if (!DBUtil::createTable('zmap_projects')) {
            return LogUtil::registerError($this->__('Error! Could not create the table zmap_projects.'));
        }
        if (!DBUtil::createTable('zmap_roadmap')) {
            return LogUtil::registerError($this->__('Error! Could not create the table zmap_roadmap.'));
        }
        if (!DBUtil::createTable('zmap_roadmap_temp')) {
            return LogUtil::registerError($this->__('Error! Could not create the table zmap_roadmap_temp.'));
        }

        // Set up config variables
        $this->setVar('showAdminHelloWorld', 0);

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
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Zmap::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        switch ($oldversion) {
            case '1.0.0':
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
        $result = DBUtil::dropTable('zmap');
        $result = $result && $this->delVars();
        DBUtil::dropTable('zmap_projects');
        DBUtil::dropTable('zmap_roadmap');
        DBUtil::dropTable('zmap_roadmap_temp');

        return $result;
    }

}

// end class def
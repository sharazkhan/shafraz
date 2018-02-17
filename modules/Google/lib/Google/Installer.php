<?php

/**
 * FConnect
 */
Class Google_Installer extends Zikula_AbstractInstaller {

    private $_entities = array(
        'Google_Entity_Connections'
    );

    /**
     *  Initialize a new install of the FConnect module
     *
     *  This function will initialize a new installation of Dizkus.
     *  It is accessed via the Zikula Admin interface and should
     *  not be called directly.
     */
    public function install() { // from kerala

        try {
            DoctrineHelper::createSchema($this->entityManager, $this->_entities);
        } catch (Exception $e) {
            return LogUtil::registerError($e->getMessage());
        }

        EventUtil::registerPersistentModuleHandler('Google', 'user.account.delete', array('Google_Listener_User', 'delete'));

        // Initialisation successful
        return true;
    }

    /**
     *  Deletes an install of the FConnect module
     *
     *  This function removes Dizkus from your
     *  Zikula install and should be accessed via
     *  the Zikula Admin interface
     */
    public function uninstall() {
//        try {
//            DoctrineHelper::dropSchema($this->entityManager, $this->_entities);
//        } catch (Exception $e) {
//            return LogUtil::registerError($e->getMessage());
//        }


        $sql = "DROP TABLE google";
        $query = DBUtil::executeSQL($sql);
        // remove module vars
        $this->delVars();
        EventUtil::unregisterPersistentModuleHandlers('Google');
        // Deletion successful
        return true;
    }

    public function upgrade($oldversion) {
        
    }

}

<?php

/**
 * TwitterLogin
 */
Class TwitterLogin_Installer extends Zikula_AbstractInstaller {

    private $_entities = array(
        'TwitterLogin_Entity_Connections'
    );

    /**
     *  Initialize a new install of the FConnect module
     *
     *  This function will initialize a new installation of Dizkus.
     *  It is accessed via the Zikula Admin interface and should
     *  not be called directly.
     */
    public function install() {

        try {
            DoctrineHelper::createSchema($this->entityManager, $this->_entities);
        } catch (Exception $e) {
            return LogUtil::registerError($e->getMessage());
        }

        EventUtil::registerPersistentModuleHandler('TwitterLogin', 'user.account.delete', array('TwitterLogin_Listener_User', 'delete'));

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


        $sql = "DROP TABLE twitter_login";
        $query = DBUtil::executeSQL($sql);
        // remove module vars
        $this->delVars();
        EventUtil::unregisterPersistentModuleHandlers('TwitterLogin');
        // Deletion successful
        return true;
    }

    public function upgrade($oldversion) {
        
    }

}

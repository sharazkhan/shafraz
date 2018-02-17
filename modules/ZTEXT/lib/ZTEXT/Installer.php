<?php

/**
 * Class to control Installer interface
 */
class ZTEXT_Installer extends Zikula_AbstractInstaller {

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

        try {
            DoctrineHelper::createSchema($this->entityManager, array(
                'ZTEXT_Entity_Page',
                'ZTEXT_Entity_PageSetting',
            ));
        } catch (Exception $e) {
            LogUtil::registerError($this->__f('Error! Could not create table (%s).', $e->getMessage()));
            return false;
        }


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
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZTEXT::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        switch ($oldversion) {

            case '0.0.1':
                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager, array(
                                'ZTEXT_Entity_PageSetting',
                    ));
                } catch (Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).', $e->getMessage()));
                    return '0.0.1';
                }
            case '0.0.2':
                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager, array(
                                'ZTEXT_Entity_PageSetting',
                    ));
                } catch (Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).', $e->getMessage()));
                    return '0.0.2';
                }
            case '0.0.3':
                try {
                    if (!$this->colunmExist('ztext_pages', 'doc')) {
                        $this->executeQuery("ALTER TABLE `ztext_pages` ADD `doc` VARCHAR(250) NULL AFTER `image`");
                    }
                } catch (Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).', $e->getMessage()));
                    return '0.0.3';
                }
            case '0.0.4':
                try {
                    if (!$this->colunmExist('ztext_pages', 'extension')) {
                        $this->executeQuery("ALTER TABLE `ztext_pages` ADD `extension` VARCHAR(250) NULL AFTER `image`");
                    }
                } catch (Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).', $e->getMessage()));
                    return '0.0.4';
                }
            case '0.0.5':
        }

        return true;
    }

    public function executeQuery($sql) {
        $statement = Doctrine_Manager::getInstance()->connection();

        if (!empty($sql)) {
            $query = $statement->execute($sql);
            return $query;
        }
    }

    public function colunmExist($table, $field) {
        $statement = Doctrine_Manager::getInstance()->connection();
        $sql = "SHOW columns from $table where field='$field'";
        $query = $statement->execute($sql);
        $result = $query->fetch();
        return $result;
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
        /*
          DoctrineHelper::dropSchema($this->entityManager, array(
          'ZTEXT_Entity_Page',
          ));
         */
        return true;
    }

}

// end class def
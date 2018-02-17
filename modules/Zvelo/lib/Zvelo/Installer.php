<?php

/**
 * Zvelo
 */

/**
 * Installer.
 */
class Zvelo_Installer extends Zikula_AbstractInstaller {

    /**
     * Install the module.
     *
     * @return boolean True on success, false otherwise.
     */
    public function install() {
        // create the table
        try {
            // DoctrineHelper::createSchema($this->entityManager, array('Tag_Entity_Tag', 'Tag_Entity_Object'));
            $upgrade = DoctrineHelper::createSchema($this->entityManager, array(
                        'Zvelo_Entity_Customer',
                        'Zvelo_Entity_CustomerWish',
                        'Zvelo_Entity_Bicycle',
                        'Zvelo_Entity_CustomerErgonomicValue',
                        'Zvelo_Entity_CustomerMeasurement'
            ));
            //change to InnoDB engine
            $statement = Doctrine_Manager::getInstance()->connection();
            $statement->execute('ALTER TABLE zvelo_customer ENGINE = innodb');
            $statement->execute('ALTER TABLE zvelo_bicycle ENGINE = innodb');
            $statement->execute('ALTER TABLE zvelo_customer_ergonomic_value ENGINE = innodb');
            $statement->execute('ALTER TABLE zvelo_customer_measurement ENGINE = innodb');
            $statement->execute('ALTER TABLE zvelo_customer_wish ENGINE = innodb');

            DoctrineHelper::updateSchema($this->entityManager, array(
                'Zvelo_Entity_Customer',
                'Zvelo_Entity_CustomerWish',
                'Zvelo_Entity_Bicycle',
                'Zvelo_Entity_CustomerErgonomicValue',
                'Zvelo_Entity_CustomerMeasurement'
            ));
        } catch (Exception $e) {
            LogUtil::registerError($e->getMessage());
            return false;
        }


        EventUtil::registerPersistentModuleHandler('Zvelo', 'user.gettheme', array('Zvelo_Listener_User', 'getTheme'));
        $this->defaultdata();


        // Initialisation successful
        return true;
    }

    /**
     * Upgrade the module from an old version.
     *
     * This function can be called multiple times.
     *
     * @param integer $oldversion Version to upgrade from.
     *
     * @return boolean True on success, false otherwise.
     */
    public function upgrade($oldversion) {
        switch ($oldversion) {
            case '0.0.1':
                try {
                    if (!$this->colunmExist('zvelo_bicycle', 'imagename2')) {
                        $statement = Doctrine_Manager::getInstance()->connection();
                        $statement->execute("ALTER TABLE `zvelo_bicycle` ADD `imagename2` VARCHAR(250) NULL AFTER `imagename`");
                    }
                } catch (Exception $e) {
                    LogUtil::registerError($e->getMessage());
                    return '0.0.1';
                }
            case '0.0.2':
        }

        // Update successful
        return true;
    }

    public function colunmExist($table, $field) {

        $statement = Doctrine_Manager::getInstance()->connection();
        $sql = "SHOW columns from $table where field='$field'";
        $query = $statement->execute($sql);
        $result = $query->fetch();
        return $result;
    }

    /**
     * Uninstall the module.
     *
     * This function is only ever called once during the lifetime of a particular
     * module instance.
     *
     * @return bool True on success, false otherwise.
     */
    public function uninstall() {
        // drop tables
        // DoctrineHelper::dropSchema($this->entityManager, array('Tag_Entity_Tag', 'Tag_Entity_Object'));
        DoctrineHelper::dropSchema($this->entityManager, array(
            'Zvelo_Entity_Customer',
            'Zvelo_Entity_CustomerWish',
            'Zvelo_Entity_Bicycle',
            'Zvelo_Entity_CustomerErgonomicValue',
            'Zvelo_Entity_CustomerMeasurement'
        ));

        // unregister handlers
        EventUtil::unregisterPersistentModuleHandlers('Zvelo');
        // EventUtil::unregisterPersistentModuleHandlers('ZSELEX');
        HookUtil::unregisterProviderBundles($this->version->getHookProviderBundles());

        // remove all module vars
        $this->delVars();

        // Deletion successful
        return true;
    }

    /**
     * Provide default data.
     *
     * @return void
     */
    protected function defaultdata() {
        
    }

}
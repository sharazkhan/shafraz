<?php
/**
 * Copyright ACTA-IT 2014 - ZPayment
 *
 * ZPayment
 * Payment Gateway Module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control Installer interface
 */
class ZPayment_Installer extends Zikula_AbstractInstaller
{

    /**
     * Initializes a new install
     *
     * This function will initialize a new installation.
     * It is accessed via the Zikula Admin interface and should
     * not be called directly.
     *
     * @return  boolean    true/false
     */
    public function install()
    {
        // create table


        try {
            DoctrineHelper::createSchema($this->entityManager,
                array(
                'ZPayment_Entity_Netaxept',
                'ZPayment_Entity_NetaxeptSetting',
                'ZPayment_Entity_Paypal',
                'ZPayment_Entity_PaypalSetting',
                'ZPayment_Entity_DirectpaySetting'
            ));
        } catch (Exception $e) {
            LogUtil::registerError($this->__f('Error! Could not create tables (%s).',
                    $e->getMessage()));
            return false;
        }

        // Set up config variables
        $this->setVar('Netaxept_enabled', 1);
        $this->setVar('Netaxept_testmode', 1);
        $this->setVar('Netaxept_merchant_id', 11001393);
        $this->setVar('Netaxept_token', '8Sr-Bd/6');

        $this->setVar('Paypal_enabled', 1);
        $this->setVar('Paypal_testmode', 1);

        $this->setVar('Directpay_enabled', 1);

        $this->setVar('Netaxept_enabled_general', 1);
        $this->setVar('Paypal_enabled_general', 1);
        $this->setVar('Directpay_enabled_general', 1);

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
    public function upgrade($oldversion)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZPayment::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        switch ($oldversion) {

            // case '1.0.0':
            //future development
            case '0.9.0':
                try {
                    DoctrineHelper::createSchema($this->entityManager,
                        array(
                        'ZPayment_Entity_QuickPay',
                        'ZPayment_Entity_QuickPaySetting'
                    ));
                } catch (Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not create table (%s).',
                            $e->getMessage()));
                    return '0.9.0';
                }
            case '0.9.1':
                $query  = DBUtil::executeSQL("SHOW columns from zpayment_quickpay_settings where field='pay_type'");
                $result = $query->fetch();
                if (!$result) {
                    DBUtil::executeSQL("ALTER TABLE `zpayment_quickpay_settings` ADD `pay_type` VARCHAR(250) NOT NULL AFTER `md5_secret`");
                }
                $query  = DBUtil::executeSQL("SHOW columns from zpayment_quickpay where field='cardtype'");
                $result = $query->fetch();
                if (!$result) {
                    DBUtil::executeSQL("ALTER TABLE `zpayment_quickpay` ADD `cardtype` VARCHAR(250) NOT NULL AFTER `info`");
                }
            case '0.9.2':
                $query  = DBUtil::executeSQL("SHOW columns from zpayment_netaxept where field='cardtype'");
                $result = $query->fetch();
                if (!$result) {
                    DBUtil::executeSQL("ALTER TABLE `zpayment_netaxept` ADD `cardtype` VARCHAR(250) NOT NULL AFTER `info`");
                }
            case '0.9.3':
                try {
                    DoctrineHelper::createSchema($this->entityManager,
                        array(
                        'ZPayment_Entity_CardsAccepted'
                    ));
                } catch (Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not create table (%s).',
                            $e->getMessage()));
                    return '0.9.3';
                }
            case '0.9.4':
                try {
                    DoctrineHelper::createSchema($this->entityManager,
                        array(
                        'ZPayment_Entity_FreightSetting'
                    ));
                } catch (Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not create table (%s).',
                            $e->getMessage()));
                    return '0.9.4';
                }

            case '0.9.5':
                try {
                    DoctrineHelper::updateSchema($this->entityManager,
                        array(
                        'ZPayment_Entity_NetaxeptSetting'
                    ));
                } catch (Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not create table (%s).',
                            $e->getMessage()));
                    return '0.9.5';
                }
            case '0.9.6':
                try {
                    DoctrineHelper::updateSchema($this->entityManager,
                        array(
                        'ZPayment_Entity_NetaxeptSetting'
                    ));
                } catch (Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not create table (%s).',
                            $e->getMessage()));
                    return '0.9.6';
                }

            case '0.9.7':
                try {
                    DoctrineHelper::updateSchema($this->entityManager,
                        array(
                        'ZPayment_Entity_NetaxeptSetting'
                    ));
                } catch (Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not create table (%s).',
                            $e->getMessage()));
                    return '0.9.7';
                }
            case '0.9.8':
                try {
                    DoctrineHelper::createSchema($this->entityManager,
                        array(
                        'ZPayment_Entity_EpaySetting',
                        'ZPayment_Entity_Epay'
                    ));
                } catch (Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not create table (%s).',
                            $e->getMessage()));
                    return '0.9.8';
                }
            case '0.9.9':
                try {
                    DoctrineHelper::updateSchema($this->entityManager,
                        array(
                        'ZPayment_Entity_EpaySetting',
                        'ZPayment_Entity_Epay'
                    ));
                } catch (Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not create table (%s).',
                            $e->getMessage()));
                    return '0.9.9';
                }
            case '1.0.0':
                try {
                    if (!$this->colunmExist('zpayment_quickpay_settings',
                            'test_mode')) {
                        $this->executeQuery("ALTER TABLE `zpayment_quickpay_settings` CHANGE `test_mode` `test_mode` TINYINT( 1 ) NOT NULL DEFAULT '0'");
                    }
                    if (!$this->colunmExist('zpayment_quickpay_settings',
                            'merchant_id')) {
                        $this->executeQuery("ALTER TABLE `zpayment_quickpay_settings` ADD `merchant_id` VARCHAR( 250 ) NOT NULL AFTER `quickpay_id`");
                    }
                    if (!$this->colunmExist('zpayment_quickpay_settings',
                            'api_key')) {
                        $this->executeQuery("ALTER TABLE `zpayment_quickpay_settings` ADD `api_key` TEXT NOT NULL AFTER `md5_secret`");
                    }
                    if (!$this->colunmExist('zpayment_quickpay_settings',
                            'agreement_id')) {
                        $this->executeQuery("ALTER TABLE `zpayment_quickpay_settings` ADD `agreement_id` VARCHAR( 250 ) NOT NULL AFTER `merchant_id`");
                    }
                } catch (Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not create table (%s).',
                            $e->getMessage()));
                    return '1.0.0';
                }
            case '1.0.1':

                try {
                    if (!$this->colunmExist('zpayment_quickpay_settings',
                            'return_url')) {
                        $this->executeQuery("ALTER TABLE `zpayment_quickpay_settings` ADD `return_url` TEXT NULL AFTER `pay_type`");
                    }
                } catch (Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not create table (%s).',
                            $e->getMessage()));
                    return '1.0.0';
                }

            case '1.0.2':
        }

        return true;
    }

    public function executeQuery($sql)
    {
        $statement = Doctrine_Manager::getInstance()->connection();

        if (!empty($sql)) {
            $query = $statement->execute($sql);
            return $query;
        }
    }

    public function colunmExist($table, $field)
    {

        $statement = Doctrine_Manager::getInstance()->connection();
        $sql       = "SHOW columns from $table where field='$field'";
        $query     = $statement->execute($sql);
        $result    = $query->fetch();
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
    public function uninstall()
    {
        // $result = DBUtil::dropTable('ZPayment');
        $result = $this->delVars();
        DoctrineHelper::dropSchema($this->entityManager,
            array(
            'ZPayment_Entity_Netaxept',
            'ZPayment_Entity_NetaxeptSetting',
            'ZPayment_Entity_Paypal',
            'ZPayment_Entity_PaypalSetting',
            'ZPayment_Entity_DirectpaySetting'
        ));

        return $result;
    }
}
// end class def
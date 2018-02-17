<?php
/**
 * Copyright  2013
 *
 * ZSELEX
 * Demonstration of Zikula Module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control Installer interface
 */
class ZSELEX_Installer extends Zikula_AbstractInstaller
{

    /**
     * Initializes a new install
     *
     * This function will initialize a new installation.
     * It is accessed via the Zikula Admin interface and should
     * not be called directly.
     *
     * @return boolean true/false
     */
    public function install()
    {
        // create tables
        $tablefunc = 'zselex_tables';
        $data      = call_user_func($tablefunc);
        /*
         * foreach ($data as $key => $val) {
         * if ($key === $val) {
         * if (!DBUtil::createTable($val)) {
         * return LogUtil::registerError($this->__("Error! Could not create the table $val."));
         * }
         * }
         * }
         */

        /*
         * try {
         * DoctrineHelper::createSchema($this->entityManager, array('ZSELEX_Entity_Country',
         * 'ZSELEX_Entity_Region',
         * 'ZSELEX_Entity_City',
         * 'ZSELEX_Entity_Area',
         * 'ZSELEX_Entity_Shop',
         * 'ZSELEX_Entity_Category',
         * 'ZSELEX_Entity_Branch',
         * 'ZSELEX_Entity_Products',
         * 'ZSELEX_Entity_ProductCategory'
         * ));
         * } catch (Exception $e) {
         * LogUtil::registerError($this->__f('Error! Could not create tables (%s).', $e->getMessage()));
         * return false;
         * }
         */

        // exit;
        // Set up config variables
        $this->setVar('showAdminZSELEX', 0);
        $this->setVar('itemsperpage', 15);
        EventUtil::registerPersistentModuleHandler('ZSELEX',
            'module_dispatch.custom_classname',
            array(
            'ZSELEX_Listener_User',
            'customClassname'
        ));
        EventUtil::registerPersistentModuleHandler('ZSELEX', 'user.gettheme',
            array(
            'ZSELEX_Listener_User',
            'getTheme'
        ));
        EventUtil::registerPersistentModuleHandler('ZSELEX',
            'module.users.ui.login.succeeded',
            array(
            'ZSELEX_Listener_UserLogin',
            'succeeded'
        ));
        EventUtil::registerPersistentModuleHandler('ZSELEX',
            'module.users.ui.logout.succeeded',
            array(
            'ZSELEX_Listener_UserLogin',
            'logout'
        ));
        EventUtil::registerPersistentModuleHandler('ZSELEX',
            'user.account.create',
            array(
            'ZSELEX_Listener_User',
            'create'
        ));
        EventUtil::registerPersistentModuleHandler('ZSELEX',
            'user.account.delete',
            array(
            'ZSELEX_Listener_User',
            'delete'
        ));
        return true;
    }

    /**
     * Upgrades an old install
     *
     * This function is used to upgrade an old version
     * of the module. It is accessed via the Zikula
     * Admin interface and should not be called directly.
     *
     * @param string $oldversion
     *        	Version we're upgrading
     * @return boolean true/false
     */
    public function upgrade($oldversion)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        // echo "comes here"; exit;
        switch ($oldversion) {
            // case '0.9.0':
            case '0.9.0' :
                try {
                    DoctrineHelper::createSchema($this->entityManager,
                        array(
                        'ZSELEX_Entity_ShopDetail'
                    ));
                } catch (Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not create table (%s).',
                            $e->getMessage()));
                    return '0.9.0';
                }

            case '0.9.1' :
                EventUtil::registerPersistentModuleHandler('ZSELEX',
                    'user.account.create',
                    array(
                    'ZSELEX_Listener_User',
                    'create'
                ));
                EventUtil::registerPersistentModuleHandler('ZSELEX',
                    'user.account.delete',
                    array(
                    'ZSELEX_Listener_User',
                    'delete'
                ));
            case '0.9.2' :
                $this->entityManager->getRepository('ZSELEX_Entity_ShopDetail')->setShopIdInShopDetails();
                try {
                    DoctrineHelper::createSchema($this->entityManager,
                        array(
                        'ZSELEX_Entity_Newsletter'
                    ));
                } catch (Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not create table (%s).',
                            $e->getMessage()));
                    return '0.9.2';
                }
            case '0.9.3' :
                DBUtil::executeSQL("ALTER TABLE `zselex_shop` ADD FULLTEXT(`shop_name`)");
                DBUtil::executeSQL("ALTER TABLE `zselex_keywords` ADD FULLTEXT(`keyword`)");
            case '0.9.4' :
                DBUtil::executeSQL("ALTER TABLE `zselex_service_orderitems` ADD `timer_days` INT NOT NULL AFTER `service_status`");
            case '0.9.5' :
                DBUtil::executeSQL("ALTER TABLE `zselex_basket` CHANGE `price` `price` DECIMAL(10,2) NULL DEFAULT NULL");
                DBUtil::executeSQL("ALTER TABLE `zselex_basket` CHANGE `subtotal` `subtotal` DECIMAL(10,2) NULL DEFAULT NULL");
                DBUtil::executeSQL("ALTER TABLE `zselex_basket` CHANGE `original_price` `original_price` DECIMAL(10,2) NULL DEFAULT NULL");
            case '0.9.6' :
                $query  = DBUtil::executeSQL("SHOW columns from zselex_order where field='vat'");
                $result = $query->fetch();
                if (!$result) {
                    DBUtil::executeSQL("ALTER TABLE `zselex_order` ADD `vat` DECIMAL(10,2) NOT NULL AFTER `totalprice`, ADD `shipping` DECIMAL(10,2) NOT NULL AFTER `vat`");
                }
            case '0.9.7' :
                $sqls    = array();
                $sqls [] = "ALTER TABLE `zselex_order` CHANGE `totalprice` `totalprice` DECIMAL(15,4) NOT NULL DEFAULT '0.0000'";
                $sqls [] = "ALTER TABLE `zselex_order` CHANGE `vat` `vat` DECIMAL(15,4) NOT NULL DEFAULT '0.0000'";
                $sqls [] = "ALTER TABLE `zselex_order` CHANGE `shipping` `shipping` DECIMAL(15,4) NOT NULL DEFAULT '0.0000'";
                $sqls [] = "ALTER TABLE `zselex_products` CHANGE `original_price` `original_price` DECIMAL(15,4) NOT NULL DEFAULT '0.0000'";
                $sqls [] = "ALTER TABLE `zselex_products` CHANGE `prd_price` `prd_price` DECIMAL(15,4) NOT NULL DEFAULT '0.0000'";
                $sqls [] = "ALTER TABLE `zselex_products` CHANGE `discount` `discount` DECIMAL(15,4) NOT NULL DEFAULT '0.0000';";
                $sqls [] = "ALTER TABLE `zselex_basket` CHANGE `original_price` `original_price` DECIMAL(15,4) NOT NULL DEFAULT '0.0000'";
                $sqls [] = "ALTER TABLE `zselex_basket` CHANGE `price` `price` DECIMAL(15,4) NOT NULL DEFAULT '0.0000'";
                $sqls [] = "ALTER TABLE `zselex_basket` CHANGE `subtotal` `subtotal` DECIMAL(15,4) NOT NULL DEFAULT '0.0000'";
                $sqls [] = "ALTER TABLE `zselex_advertise_price` CHANGE `price` `price` DECIMAL(15,4) NOT NULL DEFAULT '0.0000'";
                $sqls [] = "ALTER TABLE `zselex_order` CHANGE `phone` `phone` INT(20) NULL DEFAULT NULL";
                $sqls [] = "ALTER TABLE `zselex_order` CHANGE `status` `status` VARCHAR(250) NULL DEFAULT NULL";
                $sqls [] = "ALTER TABLE `zselex_orderitems` CHANGE `price` `price` DECIMAL(15,4) NOT NULL DEFAULT '0.0000'";
                $sqls [] = "ALTER TABLE `zselex_orderitems` CHANGE `total` `total` DECIMAL(15,4) NOT NULL DEFAULT '0.0000'";
                $sqls [] = "ALTER TABLE `zselex_plugin` CHANGE `price` `price` DECIMAL(15,4) NOT NULL DEFAULT '0.0000'";
                $sqls [] = "ALTER TABLE `zselex_service_bundles` CHANGE `bundle_price` `bundle_price` DECIMAL(15,4) NOT NULL DEFAULT '0.0000'";
                $sqls [] = "ALTER TABLE `zselex_service_bundles` CHANGE `calculated_price` `calculated_price` DECIMAL(15,4) NOT NULL DEFAULT '0.0000'";
                $sqls [] = "ALTER TABLE `zselex_service_bundle_items` CHANGE `price` `price` DECIMAL(15,4) NOT NULL DEFAULT '0.0000'";
                $sqls [] = "ALTER TABLE `zselex_service_order` CHANGE `grand_total` `grand_total` DECIMAL(15,4) NOT NULL DEFAULT '0.0000'";
                $sqls [] = "ALTER TABLE `zselex_service_orderitems` CHANGE `price` `price` DECIMAL(15,4) NOT NULL DEFAULT '0.0000'";
                $sqls [] = "ALTER TABLE `zselex_service_orderitems` CHANGE `subtotal` `subtotal` DECIMAL(15,4) NOT NULL DEFAULT '0.0000'";
                $sqls [] = "ALTER TABLE `zselex_cart` CHANGE `cart_total` `cart_total` DECIMAL(15,4) NOT NULL DEFAULT '0.0000'";
                $sqls [] = "ALTER TABLE `zselex_shop_events` CHANGE `price` `price` DECIMAL(15,4) NOT NULL DEFAULT '0.0000'";
                foreach ($sqls as $sql) {
                    if (!DBUtil::executeSQL($sql)) {
                        LogUtil::registerError($this->__('Error! Could not update table.'));
                        return '0.9.7';
                    }
                }
            case '0.9.8' :
                $sqls    = array();
                $sqls [] = "ALTER TABLE `zselex_serviceshop` CHANGE `timer_date` `timer_date` DATE NULL DEFAULT NULL";
                $sqls [] = "ALTER TABLE `zselex_serviceshop_bundles` CHANGE `timer_date` `timer_date` DATE NULL DEFAULT NULL";
                $sqls [] = "ALTER TABLE `zselex_service_demo` CHANGE `start_date` `start_date` DATE NULL DEFAULT NULL";
                $sqls [] = "ALTER TABLE `zselex_serviceshop` CHANGE `timer_date` `timer_date` DATE NULL DEFAULT NULL";
                $sqls [] = "ALTER TABLE `zselex_shop_events` CHANGE `shop_event_startdate` `shop_event_startdate` DATE NULL DEFAULT NULL";
                $sqls [] = "ALTER TABLE `zselex_shop_events` CHANGE `shop_event_enddate` `shop_event_enddate` DATE NULL DEFAULT NULL";
                $sqls [] = "ALTER TABLE `zselex_shop_events` CHANGE `activation_date` `activation_date` DATE NULL DEFAULT NULL";
                $sqls [] = "ALTER TABLE `zselex_order` ADD `cr_date` DATETIME NOT NULL AFTER `payment_type`, ADD `cr_uid` INT NOT NULL AFTER `cr_date`, ADD `lu_date` DATETIME NOT NULL AFTER `cr_uid`, ADD `lu_uid` INT NOT NULL AFTER `lu_date`";
                foreach ($sqls as $sql) {
                    if (!DBUtil::executeSQL($sql)) {
                        LogUtil::registerError($this->__('Error! Could not update table.'));
                        return '0.9.8';
                    }
                }
            case '0.9.9' :
                DBUtil::executeSQL("ALTER TABLE `zselex_products` CHANGE `discount` `discount` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0'");
            case '1.0.0' :
                $query  = DBUtil::executeSQL("SHOW columns from zselex_shop where field='link_to_homepage'");
                $result = $query->fetch();
                if (!$result) {
                    DBUtil::executeSQL("ALTER TABLE `zselex_shop` ADD `link_to_homepage` TEXT NOT NULL AFTER `shoptype_id`");
                }
            case '1.0.1' :
                try {
                    DoctrineHelper::createSchema($this->entityManager,
                        array(
                        'ZSELEX_Entity_ShopSetting'
                    ));
                    $this->entityManager->getRepository('ZSELEX_Entity_Shop')->setShopSettings();
                } catch (Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not create table (%s).',
                            $e->getMessage()));
                    return '1.0.1';
                }

                $sqls    = array();
                $sqls [] = "RENAME TABLE zselex_shopowners TO zselex_shop_owners";
                $sqls [] = "RENAME TABLE zselex_shopadmins TO zselex_shop_admins";
                $sqls [] = "RENAME TABLE zselex_shopnews TO zselex_shop_news";
                $sqls [] = "RENAME TABLE zselex_shoptypes TO zselex_shop_types";
                $sqls [] = "RENAME TABLE zselex_shopowners_theme TO zselex_shop_owners_theme";

                foreach ($sqls as $sql) {
                    if (!DBUtil::executeSQL($sql)) {
                        LogUtil::registerError($this->__('Error! Could not update table.'));
                        '1.0.1';
                    }
                } // //

            case '1.0.2' :
                try {
                    // echo "comes here"; exit;

                    DoctrineHelper::createSchema($this->entityManager,
                        array(
                        'ZSELEX_Entity_Manufacturer'
                    ));
                } catch (Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not create table (%s).',
                            $e->getMessage()));
                    return '1.0.2';
                }
                DBUtil::executeSQL("ALTER TABLE `zselex_manufacturer` ADD INDEX(`shop_id`)");
                DBUtil::executeSQL("ALTER TABLE `zselex_products` ADD `manufacturer_id` BIGINT NOT NULL AFTER `prd_description`");
            case '1.0.3' :
                if (!$this->colunmExist('zselex_shop', 'vat_number')) {
                    DBUtil::executeSQL("ALTER TABLE `zselex_shop` ADD `vat_number` VARCHAR(300) NULL AFTER `aff_id`");
                }
            case '1.0.4' :
                $sql = "CREATE TABLE `zselex_product_to_category` (
                        `product_id` BIGINT NOT NULL ,
                        `category_id` INT NOT NULL ,
                        PRIMARY KEY ( `product_id` , `category_id` )
                        ) ENGINE = MYISAM ;
                        ";
                DBUtil::executeSQL($sql);
            case '1.0.5' :
                try {
                    $query  = DBUtil::executeSQL("SELECT shop_id , cat_id FROM zselex_shop WHERE cat_id!=0");
                    $result = $query->fetchAll();
                    DoctrineHelper::updateSchema($this->entityManager,
                        array(
                        'ZSELEX_Entity_Shop',
                        'ZSELEX_Entity_Product'
                    ));
                    foreach ($result as $key => $val) {
                        $query = DBUtil::executeSQL("INSERT INTO zselex_shop_to_category(shop_id,category_id)VALUES('".$val ['shop_id']."','".$val ['cat_id']."')");
                    }
                } catch (Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.0.5';
                }
            case '1.0.6' :
                try {
                    // echo "comes here"; exit;

                    DoctrineHelper::createSchema($this->entityManager,
                        array(
                        'ZSELEX_Entity_ProductOption'
                    ));
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Region'
                    ));
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Shop'
                    ));
                    // DBUtil::executeSQL("ALTER TABLE `zselex_shop` ADD FULLTEXT(`shop_name`)");
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Event'
                    ));
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_ServiceShop'
                    ));
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_ServiceDemo'
                    ));
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Rating'
                    ));
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Product'
                    ));
                    $sql     = "CREATE TABLE `zselex_product_to_options` (
                        `product_id` BIGINT NOT NULL ,
                        `option_id` INT NOT NULL ,
                        `option_values` TEXT NOT NULL,
                        PRIMARY KEY ( `product_id` , `option_id` )
                        ) ENGINE = MYISAM ;
                        ";
                    DBUtil::executeSQL($sql);
                } catch (Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not create table (%s).',
                            $e->getMessage()));
                    return '1.0.6';
                }
            case '1.0.7' :

                DBUtil::executeSQL("DROP TABLE IF EXISTS zselex_product_to_options");
                $sql = "CREATE TABLE IF NOT EXISTS `zselex_product_to_options` (
                        `product_to_options_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                        `product_id` INT NOT NULL ,
                        `option_id` INT NOT NULL ,
                        `option_values` VARCHAR( 250 ) NOT NULL
                        ) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";
                DBUtil::executeSQL($sql);

                if ($this->colunmExist('zselex_cart', 'cart_total')) {
                    DBUtil::executeSQL('ALTER TABLE `zselex_cart` DROP `cart_total`');
                }

                if (!$this->colunmExist('zselex_cart', 'original_price')) {
                    DBUtil::executeSQL("ALTER TABLE `zselex_cart` ADD `original_price` DECIMAL(15,4) NOT NULL AFTER `cart_content`");
                }
                if (!$this->colunmExist('zselex_cart', 'final_price')) {
                    DBUtil::executeSQL("ALTER TABLE `zselex_cart` ADD `final_price` DECIMAL(15,4) NOT NULL AFTER `original_price`");
                }
                if (!$this->colunmExist('zselex_cart', 'product_id')) {
                    DBUtil::executeSQL("ALTER TABLE `zselex_cart` ADD `product_id` BIGINT NOT NULL AFTER `user_id`");
                }
                if (!$this->colunmExist('zselex_cart', 'quantity')) {
                    DBUtil::executeSQL("ALTER TABLE `zselex_cart` ADD `quantity` INT NOT NULL AFTER `product_id`");
                }
                if (!$this->colunmExist('zselex_cart', 'shop_id')) {
                    DBUtil::executeSQL("ALTER TABLE `zselex_cart` ADD `shop_id` BIGINT NOT NULL AFTER `quantity`");
                }
                if (!$this->colunmExist('zselex_orderitems', 'product_options')) {
                    DBUtil::executeSQL("ALTER TABLE `zselex_orderitems` ADD `product_options` TEXT NOT NULL AFTER `quantity`");
                }
                DBUtil::executeSQL("ALTER TABLE `zselex_cart` CHANGE `original_price` `original_price` DECIMAL(15,4) NOT NULL DEFAULT '0.0000'");
                DBUtil::executeSQL("ALTER TABLE `zselex_cart` CHANGE `final_price` `final_price` DECIMAL(15,4) NOT NULL DEFAULT '0.0000'");

                $sql = "CREATE TABLE IF NOT EXISTS `zselex_product_to_options_values` (
                        `product_to_options_value_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                        `product_to_options_id` INT NOT NULL ,
                        `product_id` BIGINT NOT NULL ,
                        `option_id` INT NOT NULL ,
                        `option_value_id` INT NOT NULL ,
                        `option_value` VARCHAR( 250 ) NOT NULL ,
                        `price` DECIMAL( 15, 4 ) NOT NULL
                        ) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";
                DBUtil::executeSQL($sql);
                $sql = "CREATE TABLE IF NOT EXISTS `zselex_product_options_values` (
                        `option_value_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                        `option_id` INT NOT NULL ,
                        `shop_id` BIGINT NOT NULL ,
                        `option_value` VARCHAR( 250 ) NOT NULL
                        ) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";
                DBUtil::executeSQL($sql);

            case '1.0.8' :
                if (!$this->colunmExist('zselex_cart', 'options_price')) {
                    DBUtil::executeSQL("ALTER TABLE `zselex_cart` ADD `options_price` DECIMAL(15,4) NOT NULL DEFAULT '0.0000' AFTER `original_price`");
                }
                if (!$this->colunmExist('zselex_orderitems', 'options_price')) {
                    DBUtil::executeSQL("ALTER TABLE `zselex_orderitems` ADD `options_price` DECIMAL(15,4) NOT NULL DEFAULT '0.0000' AFTER `price`");
                }
            case '1.0.9' :
                if (!$this->colunmExist('zselex_product_to_options_values',
                        'qty')) {
                    DBUtil::executeSQL("ALTER TABLE `zselex_product_to_options_values` ADD `qty` INT NOT NULL AFTER `price`");
                }
                if (!$this->colunmExist('zselex_cart', 'outofstock')) {
                    DBUtil::executeSQL("ALTER TABLE `zselex_cart` ADD `outofstock` SMALLINT(1) NOT NULL AFTER `final_price`");
                }
            case '1.1.0' :

                if (!$this->colunmExist('zselex_files', 'sort_order')) {
                    DBUtil::executeSQL("ALTER TABLE `zselex_files` ADD `sort_order` INT NOT NULL AFTER `status`");
                }
                if (!$this->colunmExist('zselex_product_to_options_values',
                        'sort_order')) {
                    DBUtil::executeSQL("ALTER TABLE `zselex_product_to_options_values` ADD `sort_order` INT NOT NULL AFTER `qty`");
                }
            case '1.1.1' :

                if (!$this->colunmExist('zselex_product_options_values',
                        'sort_order')) {
                    DBUtil::executeSQL("ALTER TABLE `zselex_product_options_values` ADD `sort_order` INT NOT NULL AFTER `option_value`");
                }

                if ($this->colunmExist('zselex_product_to_options_values',
                        'sort_order')) {
                    DBUtil::executeSQL("ALTER TABLE `zselex_product_to_options_values` DROP `sort_order`");
                }

            case '1.1.2' :

                if (!$this->colunmExist('zselex_product_options',
                        'parent_option_id')) {
                    DBUtil::executeSQL("ALTER TABLE `zselex_product_options` ADD `parent_option_id` INT NULL AFTER `option_value`");
                }
                if (!$this->colunmExist('zselex_product_options', 'sort_order')) {
                    DBUtil::executeSQL("ALTER TABLE `zselex_product_options` ADD `sort_order` INT NULL AFTER `parent_option_id`");
                }

                if (!$this->colunmExist('zselex_product_options_values',
                        'sort_order')) {
                    DBUtil::executeSQL("ALTER TABLE `zselex_product_options_values` ADD `sort_order` INT NULL AFTER `option_value`");
                }

                if (!$this->colunmExist('zselex_product_to_options',
                        'parent_option_id')) {
                    DBUtil::executeSQL("ALTER TABLE `zselex_product_to_options` ADD `parent_option_id` INT NULL AFTER `option_id`");
                }

                if (!$this->colunmExist('zselex_product_to_options_values',
                        'parent_option_id')) {
                    DBUtil::executeSQL("ALTER TABLE `zselex_product_to_options_values` ADD `parent_option_id` INT NULL AFTER `option_id`");
                }

                if (!$this->colunmExist('zselex_product_to_options_values',
                        'parent_option_value_id')) {
                    DBUtil::executeSQL("ALTER TABLE `zselex_product_to_options_values` ADD `parent_option_value_id` INT NOT NULL AFTER `option_value_id`");
                }

                if (!$this->colunmExist('zselex_product_to_options_values',
                        'price_prefix')) {
                    DBUtil::executeSQL("ALTER TABLE `zselex_product_to_options_values` ADD `price_prefix` VARCHAR(10) NOT NULL AFTER `price`");
                }
                if (!$this->indexExist('zselex_shop', 'shop_name')) {
                    DBUtil::executeSQL("ALTER TABLE `zselex_shop` ADD FULLTEXT(`shop_name`)");
                }

            case '1.1.3' :
                if ($this->colunmExist('zselex_product_to_options_values',
                        'price_prefix')) {
                    DBUtil::executeSQL("ALTER TABLE `zselex_product_to_options_values` DROP `price_prefix`");
                }

            case '1.1.4' :
                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_ProductOption',
                            'ZSELEX_Entity_ProductOptionValue',
                            'ZSELEX_Entity_ProductToOption',
                            'ZSELEX_Entity_ProductToOptionValue'
                    ));
                } catch (Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.1.4';
                }
            case '1.1.5' :
                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_ProductOption',
                            'ZSELEX_Entity_ProductOptionValue',
                            'ZSELEX_Entity_ProductToOption',
                            'ZSELEX_Entity_ProductToOptionValue',
                            'ZSELEX_Entity_Order',
                            'ZSELEX_Entity_OrderItem'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.1.5';
                }
            case '1.1.6' :
                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Rating'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.1.6';
                }

            case '1.1.7' :

                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_ProductOption',
                            'ZSELEX_Entity_ProductOptionValue',
                            'ZSELEX_Entity_ProductToOption',
                            'ZSELEX_Entity_ProductToOptionValue',
                            'ZSELEX_Entity_Order',
                            'ZSELEX_Entity_OrderItem',
                            'ZSELEX_Entity_Rating',
                            'ZSELEX_Entity_ShopSetting',
                            'ZSELEX_Entity_Event',
                            'ZSELEX_Entity_Shop',
                            'ZSELEX_Entity_Category',
                            'ZSELEX_Entity_Bundle',
                            'ZSELEX_Entity_BundleItem',
                            'ZSELEX_Entity_Plugin'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.1.7';
                }
            case '1.1.8' :

                try {

                    $areas = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getAll(array(
                        'entity' => 'ZSELEX_Entity_Area',
                        'fields' => array(
                            'a.area_id',
                            'a.area_name',
                            'c.city_id',
                            'b.country_id',
                            'd.region_id',
                            'a.status'
                        ),
                        'joins' => array(
                            'LEFT JOIN a.country b',
                            'LEFT JOIN a.city c',
                            'LEFT JOIN a.region d'
                        ),
                        'groupby' => 'a.area_id'
                    ));

                    // echo "<pre>"; print_r($areas); echo "</pre>"; exit;
                    DoctrineHelper::dropSchema($this->entityManager,
                        array(
                        'ZSELEX_Entity_Area'
                    ));
                    // DoctrineHelper::createSchema($this->entityManager, array('ZSELEX_Entity_Area'));

                    $entityArray = array(
                        'ZSELEX_Entity_Country',
                        'ZSELEX_Entity_Region',
                        'ZSELEX_Entity_City',
                        'ZSELEX_Entity_ProductOption',
                        'ZSELEX_Entity_ProductOptionValue',
                        'ZSELEX_Entity_ProductToOption',
                        'ZSELEX_Entity_ProductToOptionValue',
                        'ZSELEX_Entity_Order',
                        'ZSELEX_Entity_OrderItem',
                        'ZSELEX_Entity_Rating',
                        // 'ZSELEX_Entity_ShopSetting',
                        'ZSELEX_Entity_Event',
                        'ZSELEX_Entity_Shop',
                        'ZSELEX_Entity_Category',
                        'ZSELEX_Entity_Bundle',
                        'ZSELEX_Entity_BundleItem',
                        'ZSELEX_Entity_Plugin',
                        'ZSELEX_Entity_Area',
                        'ZSELEX_Entity_Product',
                        'ZSELEX_Entity_Advertise',
                        'ZSELEX_Entity_Product',
                        'ZSELEX_Entity_ProductCategory',
                        'ZSELEX_Entity_Manufacturer',
                        'ZSELEX_Entity_MinisiteImage',
                        'ZSELEX_Entity_Employee',
                        'ZSELEX_Entity_Announcement',
                        'ZSELEX_Entity_Banner'
                    );
                    $upgrade     = DoctrineHelper::updateSchema($this->entityManager,
                            $entityArray);

                    foreach ($areas as $value) {
                        $area    = new ZSELEX_Entity_Area ();
                        $area->setArea_name($value ['area_name']);
                        $city    = $this->entityManager->find('ZSELEX_Entity_City',
                            $value ['city_id']);
                        $area->setCity($city);
                        $region  = $this->entityManager->find('ZSELEX_Entity_Region',
                            $value ['region_id']);
                        $area->setRegion($region);
                        $country = $this->entityManager->find('ZSELEX_Entity_Country',
                            $value ['country_id']);
                        $area->setCountry($country);
                        $area->setStatus($value ['status']);
                        $this->entityManager->persist($area);
                    }
                    $this->entityManager->flush();

                    /*
                     * foreach ($entityArray as $table) {
                     * try {
                     * $upgrade = DoctrineHelper::updateSchema($this->entityManager, array($table));
                     * } catch (\Exception $e) {
                     * LogUtil::registerError($this->__f('Error! Could not update Entity -' . $table, $e->getMessage()));
                     * //return '1.1.8';
                     * }
                     * }
                     */

                    if (!$this->indexExist('zselex_shop', 'shop_name')) {
                        DBUtil::executeSQL("ALTER TABLE `zselex_shop` ADD FULLTEXT(`shop_name`)");
                    }
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.1.8';
                }

            case '1.1.9' :

                try {

                    $minishops = $this->entityManager->getRepository('ZSELEX_Entity_MiniShop')->getAll(array(
                        'entity' => 'ZSELEX_Entity_MiniShop',
                        'fields' => array(
                            'b.shop_id',
                            'a.shoptype_id',
                            'a.shoptype',
                            'a.minishop_name',
                            'a.description',
                            'a.configured'
                        ),
                        'joins' => array(
                            'JOIN a.shop b'
                        )
                        ))
                    // 'groupby' => 'a.area_id'
                    ;

                    // echo "<pre>"; print_r($areas); echo "</pre>"; exit;
                    DoctrineHelper::dropSchema($this->entityManager,
                        array(
                        'ZSELEX_Entity_MiniShop'
                    ));

                    $entityArray = array(
                        'ZSELEX_Entity_ProductOption',
                        'ZSELEX_Entity_ProductOptionValue',
                        'ZSELEX_Entity_ProductToOption',
                        'ZSELEX_Entity_ProductToOptionValue',
                        'ZSELEX_Entity_Order',
                        'ZSELEX_Entity_OrderItem',
                        'ZSELEX_Entity_Rating',
                        'ZSELEX_Entity_ShopSetting',
                        'ZSELEX_Entity_Event',
                        'ZSELEX_Entity_Shop',
                        'ZSELEX_Entity_Cart',
                        'ZSELEX_Entity_Category',
                        'ZSELEX_Entity_Bundle',
                        'ZSELEX_Entity_BundleItem',
                        'ZSELEX_Entity_Plugin',
                        'ZSELEX_Entity_Area',
                        'ZSELEX_Entity_Product',
                        'ZSELEX_Entity_Advertise',
                        'ZSELEX_Entity_Keyword',
                        'ZSELEX_Entity_Product',
                        'ZSELEX_Entity_ProductCategory',
                        'ZSELEX_Entity_Manufacturer',
                        'ZSELEX_Entity_MinisiteImage',
                        'ZSELEX_Entity_Employee',
                        'ZSELEX_Entity_Announcement',
                        'ZSELEX_Entity_Banner',
                        'ZSELEX_Entity_Shop',
                        'ZSELEX_Entity_Category',
                        'ZSELEX_Entity_Area',
                        'ZSELEX_Entity_ServiceDemo',
                        'ZSELEX_Entity_ServiceShop',
                        'ZSELEX_Entity_MiniShop',
                        'ZSELEX_Entity_ServiceBasket',
                        'ZSELEX_Entity_ServiceOrder',
                        'ZSELEX_Entity_ServiceOrderItem',
                        'ZSELEX_Entity_ServiceBundle'
                    );
                    // $upgrade = DoctrineHelper::updateSchema($this->entityManager, $entityArray);

                    foreach ($entityArray as $table) {
                        try {
                            $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                                    array(
                                    $table
                            ));
                        } catch (\Exception $e) {
                            LogUtil::registerError($this->__f('Error! Could not update Entity - '.$table.' ',
                                    $e->getMessage()));
                            return '1.1.9';
                        }
                    }

                    foreach ($minishops as $value) {
                        $minishopCr = new ZSELEX_Entity_MiniShop ();
                        $shop       = $this->entityManager->find('ZSELEX_Entity_Shop',
                            $value ['shop_id']);
                        $minishopCr->setShop($shop);
                        $minishopCr->setShoptype_id($value ['shoptype_id']);
                        $minishopCr->setShoptype($value ['shoptype']);
                        $minishopCr->setMinishop_name($value ['minishop_name']);
                        $minishopCr->setDescription($value ['description']);
                        $minishopCr->setConfigured($value ['configured']);
                        $this->entityManager->persist($minishopCr);
                    }
                    $this->entityManager->flush();

                    if (!$this->indexExist('zselex_shop', 'shop_name')) {
                        $statement = Doctrine_Manager::getInstance()->connection();
                        $sql       = "ALTER TABLE `zselex_shop` ADD FULLTEXT(`shop_name`)";
                        $query     = $statement->execute($sql);
                    }
                    if (!$this->indexExist('zselex_keywords', 'keyword')) {
                        $statement = Doctrine_Manager::getInstance()->connection();
                        $sql       = "ALTER TABLE `zselex_keywords` ADD FULLTEXT(`keyword`)";
                        $query     = $statement->execute($sql);
                    }
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.1.9';
                }

            case '1.2.0' :
                EventUtil::registerPersistentModuleHandler('ZSELEX',
                    'user.account.update',
                    array(
                    'ZSELEX_Listener_User',
                    'update'
                ));
            // shell_exec("php" . " " . ModUtil::apiFunc('ZSELEX', 'admin', 'renameFolders'));
            // ZSELEX_Controller_Base_Admin::renameFolders()
            // $api = pnGetBaseURL() . ModUtil::url('ZSELEX', 'admin', 'renameFolders');
            // echo $api; exit;
            // exec("/usr/bin/php" . " " . ModUtil::apiFunc('ZSELEX', 'admin', 'renameFolders'));
            case '1.2.1' :

                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Employee'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.2.1';
                }

            case '1.2.2' :
                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_ServiceDemo',
                            'ZSELEX_Entity_ServiceShop',
                            'ZSELEX_Entity_ServiceBundle'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.2.2';
                }

            case '1.2.3' :
                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Advertise',
                            'ZSELEX_Entity_AdvertisePrice'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.2.3';
                }
            case '1.2.4' :
                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Shop',
                            'ZSELEX_Entity_Keyword'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.2.4';
                }
            case '1.2.5' :
                try {

                    if (!$this->indexExist('zselex_shop', 'shop_name')) {
                        $statement = Doctrine_Manager::getInstance()->connection();
                        $sql       = "ALTER TABLE `zselex_shop` ADD FULLTEXT(`shop_name`)";
                        $query     = $statement->execute($sql);
                    }
                    if (!$this->indexExist('zselex_keywords', 'keyword')) {
                        $statement = Doctrine_Manager::getInstance()->connection();
                        $sql       = "ALTER TABLE `zselex_keywords` ADD FULLTEXT(`keyword`)";
                        $query     = $statement->execute($sql);
                    }
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.2.5';
                }
            case '1.2.6' :
                try {
                    // $url = pnGetBaseURL() . ModUtil::url('ZSELEX', 'admin', 'renameFolder');
                    // echo $url; exit;
                    // exec("/usr/bin/php -f " . $this->curlExec($url) . " > /dev/null &");
                    // $this->curlExec($url);
                    $folders = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->renameFolder();
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.2.6';
                }
            case '1.2.7' :
                try {
                    // $url = pnGetBaseURL() . ModUtil::url('ZSELEX', 'admin', 'renameFolder');
                    // echo $url; exit;
                    // exec("/usr/bin/php -f " . $this->curlExec($url) . " > /dev/null &");
                    // $this->curlExec($url);
                    $folders = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->renameFolder();
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.2.7';
                }
            case '1.2.8' :

                try {

                    $folders = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->insertKeyword();
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.2.8';
                }
            case '1.2.9' :
                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Cart',
                            'ZSELEX_Entity_ProductToOptionValue'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.2.9';
                }
            case '1.3.0' :
                try {
                    $this->entityManager->getRepository('ZSELEX_Entity_Shop')->updateAdditionalBundles();
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.3.0';
                }
            case '1.3.1' :
                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Order'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.3.1';
                }
            case '1.3.2' :
                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Order'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.3.2';
                }
            case '1.3.3' :
                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Product',
                            'ZSELEX_Entity_Cart',
                            'ZSELEX_Entity_OrderItem'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.3.3';
                }

            case '1.3.4' :
                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Cart'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.3.4';
                }
            case '1.3.5' :

                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_ShopAffiliation'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.3.5';
                }

            case '1.3.6' :

                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_ShopAffiliation'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.3.6';
                }

            case '1.3.7' :

                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Shop'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.3.7';
                }

            case '1.3.8' :

                if (!$this->colunmExist('zselex_shop_owners', 'co_owner')) {
                    DBUtil::executeSQL("ALTER TABLE `zselex_shop_owners` ADD `co_owner` INT NOT NULL AFTER `main`");
                }
            case '1.3.9' :

                $this->setFullTextIndex();

            case '1.4.0' :
                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Event',
                            'ZSELEX_Entity_EventTemp'
                    ));

                    $this->setFullTextIndex();
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.4.0';
                }
            case '1.4.1' :

                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Event',
                            'ZSELEX_Entity_EventTemp'
                    ));
                    // ModUtil::apiFunc('ZSELEX', 'update', 'updateEventUrls');
                    $this->entityManager->getRepository('ZSELEX_Entity_Event')->updateEventUrls();
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.4.1';
                }
            case '1.4.2' :

                $this->createFullTextIndex('zselex_country', 'country_name');
                $this->createFullTextIndex('zselex_region', 'region_name');
                $this->createFullTextIndex('zselex_city', 'city_name');
                $this->createFullTextIndex('zselex_area', 'area_name');
                $this->createFullTextIndex('zselex_shop', 'address');

            case '1.4.3' :
                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_EventTemp'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.4.3';
                }
            case '1.4.4' :
                $this->analyzeTables();
            case '1.4.5' :
                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Order'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.4.5';
                }
            case '1.4.6' :
                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Order'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.4.6';
                }
            case '1.4.7' :
                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Shop'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.4.7';
                }
            case '1.4.8' :
                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_ServiceOrder'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.4.8';
                }
            case '1.4.9' :
                $this->setFullTextIndex();
            case '1.5.0' :
                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Event'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.0';
                }
            case '1.5.1' :
                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Event'
                    ));
                    $this->setFullTextIndex();
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.1';
                }
            case '1.5.2' :
                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_QuantityDiscount'
                    ));
                    $this->setFullTextIndex();
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.2';
                }
            case '1.5.3' :
                try {
                    if ($this->colunmExist('zselex_shop_events',
                            'event_urltitle')) {
                        $this->executeQuery("ALTER TABLE zselex_shop_events CHANGE COLUMN event_urltitle event_urltitle VARCHAR(255) AFTER shop_event_name");
                    }
                    if ($this->colunmExist('zselex_shop_events', 'event_link')) {
                        $this->executeQuery("ALTER TABLE zselex_shop_events CHANGE COLUMN event_link event_link VARCHAR(255) AFTER exclusive");
                    }
                    if ($this->colunmExist('zselex_shop_events', 'open_new')) {
                        $this->executeQuery("ALTER TABLE zselex_shop_events CHANGE COLUMN open_new open_new tinyint(1) AFTER event_link");
                    }
                    if (!$this->colunmExist('zselex_shop_events',
                            'call_link_directly')) {
                        $this->executeQuery("ALTER TABLE `zselex_shop_events` ADD `call_link_directly` TINYINT(1) NULL DEFAULT NULL AFTER `open_new`");
                    }
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Event'
                    ));
                    $this->setFullTextIndex();
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.3';
                }
            case '1.5.4' :

                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Product'
                    ));
                    $this->setFullTextIndex();
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.4';
                }
            case '1.5.5' :
                try {
                    if (!$this->colunmExist('zselex_shop_employees',
                            'sort_order')) {
                        $this->executeQuery("ALTER TABLE `zselex_shop_employees` ADD `sort_order` INT NULL AFTER `status`");
                    }
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.5';
                }
            case '1.5.6' :
                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_SocialLinkShop',
                            'ZSELEX_Entity_SocialLinkShopSetting',
                            'ZSELEX_Entity_SocialLink'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.6';
                }
            case '1.5.7' :
                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Shop'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.7';
                }
                $this->entityManager->getRepository('ZSELEX_Entity_Category')->migrateShopBranches();
            case '1.5.8' :
                $this->setFullTextIndex();
            case '1.5.9' :
                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_BannerSetting'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.9';
                }
            case '1.5.10' :

                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Banner'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.10';
                }
            case '1.5.11' :

                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Shop',
                            'ZSELEX_Entity_Event'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.11';
                }
            case '1.5.12' :

                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Event'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.12';
                }
            case '1.5.13' :
                try {
                    if (!$this->colunmExist('zselex_shop',
                            'purchase_collect_stat')) {
                        $this->executeQuery("ALTER TABLE `zselex_shop` ADD `purchase_collect_stat` TINYINT NULL AFTER `delivery_time`");
                    }
                    if (!$this->colunmExist('zselex_shop',
                            'email_purchase_tries')) {
                        $this->executeQuery("ALTER TABLE `zselex_shop` ADD `email_purchase_tries` TINYINT NULL AFTER `purchase_collect_stat`");
                    }
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.13';
                }
            case '1.5.14' :
                try {
                    $this->setFullTextIndex();
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.14';
                }
            case '1.5.15' :
                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Url'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.15';
                }
            case '1.5.16' :
                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Event'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.16';
                }

            case '1.5.17' :
                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Discount'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.17';
                }

            case '1.5.18' :
                try {

                    if (!$this->colunmExist('zselex_cart', 'is_guest')) {
                        $this->executeQuery("ALTER TABLE `zselex_cart` ADD `is_guest` TINYINT( 4 ) NULL DEFAULT '0' AFTER `user_id` ;");
                    }
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Cart',
                            'ZSELEX_Entity_Order'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.18';
                }

            case '1.5.19' :
                try {


                    $this->executeQuery("ALTER TABLE `zselex_products` CHANGE `no_vat` `no_vat` TINYINT( 1 ) NULL DEFAULT '0';");
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.19';
                }
            case '1.5.20' :
                try {


                    $this->executeQuery("UPDATE `zselex_products` SET no_vat=0 WHERE no_vat IS NULL");
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.20';
                }

            case '1.5.21' :

                try {

                    if (!$this->colunmExist('zselex_cart', 'stock')) {
                        $this->executeQuery("ALTER TABLE `zselex_cart` ADD `stock` INT NULL DEFAULT '0' AFTER `outofstock`");
                    }
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.21';
                }
            case '1.5.22' :
                try {

                    if (!$this->colunmExist('zselex_order', 'self_pickup')) {
                        $this->executeQuery("ALTER TABLE `zselex_order` ADD `self_pickup` SMALLINT NOT NULL DEFAULT '0' AFTER `payment_type`");
                    }
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.22';
                }
            case '1.5.23' :

                try {
                    $this->executeQuery("ALTER TABLE `zselex_serviceshop` CHANGE `user_id` `user_id` INT( 11 ) NULL DEFAULT '0'");
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.23';
                }
            case '1.5.24' :
                try {
                    if (!$this->colunmExist('zselex_shop_events', 'contact_name')) {
                        $this->executeQuery("ALTER TABLE  `zselex_shop_events` ADD  `contact_name` VARCHAR( 250 ) NOT NULL AFTER  `price`");
                    }
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.24';
                }
            case '1.5.25' :

                try {
                    $upgrade = DoctrineHelper::updateSchema($this->entityManager,
                            array(
                            'ZSELEX_Entity_Cart'
                    ));
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.25';
                }
            case '1.5.26' :
                try {
                    if (!$this->colunmExist('zselex_shop_events', 'contact_name')) {
                        $this->executeQuery("ALTER TABLE `zselex_shop_events` CHANGE `contact_name` `contact_name` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL");
                    }
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.26';
                }
            case '1.5.27' :
                try {
                    if ($this->colunmExist('zselex_shop_events', 'contact_name')) {
                        $this->executeQuery("ALTER TABLE `zselex_shop_events` CHANGE `contact_name` `contact_name` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL");
                    }
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.27';
                }
            case '1.5.28' :

                try {

                    if (!$this->colunmExist('zselex_products', 'advertise')) {
                        $this->executeQuery("ALTER TABLE `zselex_products` ADD `advertise` BOOLEAN NULL DEFAULT FALSE AFTER `prd_status`");
                    }

                    if (!$this->colunmExist('zselex_shop', 'advertise_sel_prods')) {
                        $this->executeQuery("ALTER TABLE `zselex_shop` ADD `advertise_sel_prods` BOOLEAN NULL DEFAULT FALSE AFTER `vat_number`");
                    }
                    if (!$this->indexExist('zselex_shop_owners', 'shop_id')) {
                        $this->executeQuery("ALTER TABLE `zselex_shop_owners` ADD INDEX ( `shop_id` )");
                    }

                    if (!$this->indexExist('zselex_shop_admins', 'shop_id')) {
                        $this->executeQuery("ALTER TABLE `zselex_shop_admins` ADD INDEX ( `shop_id` )");
                    }

                    if (!$this->indexExist('zselex_products', 'prd_status')) {
                        $this->executeQuery("ALTER TABLE `zselex_products` ADD INDEX `prd_status` ( `prd_status` , `shop_id` , `advertise`)");
                    }

                    if (!$this->indexExist('zselex_advertise', 'level')) {
                        $this->executeQuery("ALTER TABLE `zselex_advertise` ADD INDEX ( `level` )");
                    }
                    if ($this->indexExist('zselex_shop', 'shop_id')) {
                        $this->executeQuery("ALTER TABLE zselex_shop DROP INDEX shop_id");
                    }
                    if (!$this->indexExist('zselex_advertise_price',
                            'identifier')) {
                        $this->executeQuery("ALTER TABLE `zselex_advertise_price` ADD INDEX ( `identifier`)");
                    }
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.27';
                }

            case '1.5.29' :
                try {
                    if (!$this->colunmExist('zselex_service_bundles', 'is_free')) {
                        $this->executeQuery("ALTER TABLE `zselex_service_bundles` ADD `is_free` TINYINT NULL DEFAULT '0' AFTER `demoperiod`");
                    }
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.29';
                }
            case '1.5.30' :
                try {
                    if (!$this->colunmExist('zselex_products', 'image_height') && !$this->colunmExist('zselex_products',
                            'image_width')) {
                        $this->executeQuery("ALTER TABLE `zselex_products` ADD `image_height` VARCHAR(100) NULL AFTER `prd_image`, ADD `image_width` VARCHAR(100) NULL AFTER `image_height`");
                    }
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.30';
                }

            case '1.5.31' :
                try {
                    if (!$this->colunmExist('zselex_files', 'image_height') && !$this->colunmExist('zselex_files',
                            'image_width')) {
                        $this->executeQuery("ALTER TABLE `zselex_files` ADD `image_height` VARCHAR(100) NULL AFTER `name`, ADD `image_width` VARCHAR(100) NULL AFTER `image_height`");
                    }
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.31';
                }

            case '1.5.32' :

                try {
                    if (!$this->colunmExist('zselex_order', 'completed')) {
                        $this->executeQuery("ALTER TABLE `zselex_order` ADD `completed` SMALLINT NOT NULL DEFAULT '0' AFTER `self_pickup`");
                    }
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.32';
                }

            case '1.5.33' :

                try {
                    if (!$this->colunmExist('zpayment_quickpay', 'callback')) {
                        $this->executeQuery("ALTER TABLE `zpayment_quickpay` ADD `callback` TINYINT NOT NULL DEFAULT '0' AFTER `cardtype`");
                    }
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.33';
                }

            case '1.5.34' :
                try {

                    $this->entityManager->getRepository('ZSELEX_Entity_Product')->updateProductKeywords();
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not execute updateProductKeywords api',
                            $e->getMessage()));
                    return '1.5.34';
                }
            case '1.5.35' :
//                try {
//
//                    $this->entityManager->getRepository('ZSELEX_Entity_Product')->updateProductKeywordsByDelete();
//                } catch (\Exception $e) {
//                    LogUtil::registerError($this->__f('Error! Could not execute updateProductKeywordsByDelete api',
//                            $e->getMessage()));
//                    return '1.5.35';
//                }

            case '1.5.36' :

                if ($_SERVER ['SERVER_NAME'] == 'localhost') {
                    $path = $_SERVER ['DOCUMENT_ROOT'].'/zselex/scripts/product_keyword_update.php';
                    chmod($path, 0777); //
                } else { // SERVER
                    // echo "comes here!!!"; exit;
                    $path = $_SERVER ['DOCUMENT_ROOT'].'/scripts/product_keyword_update.php';
                    chmod($path, 0777);
                }
                $cmd = 'php '.$path." ".pnGetBaseURL();

                ZSELEX_Util::execInBackground($cmd);

            case '1.5.37' :

                if ($_SERVER ['SERVER_NAME'] == 'localhost') {
                    $path = $_SERVER ['DOCUMENT_ROOT'].'/zselex/scripts/product_keyword_update.php';
                    chmod($path, 0777); //
                } else { // SERVER
                    // echo "comes here!!!"; exit;
                    $path = $_SERVER ['DOCUMENT_ROOT'].'/scripts/product_keyword_update.php';
                    chmod($path, 0777);
                }
                $cmd = 'php '.$path." ".pnGetBaseURL();

                ZSELEX_Util::execInBackground($cmd);

            case '1.5.38' :
                try {
                    if (!$this->colunmExist('zselex_shop', 'link_to_mailinglist')) {
                        $this->executeQuery("ALTER TABLE `zselex_shop` ADD `link_to_mailinglist` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `link_to_homepage`;");
                    }
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.38';
                }
            case '1.5.39' :
                try {
                    $this->executeQuery("ALTER TABLE `zselex_products` CHANGE `shipping_price` `shipping_price` DECIMAL(15,4) NULL DEFAULT NULL AFTER `advertise`");
                    $this->executeQuery("ALTER TABLE `zselex_products` CHANGE `enable_question` `enable_question` TINYINT(1) NULL DEFAULT NULL AFTER `shipping_price`");
                    $this->executeQuery("ALTER TABLE `zselex_products` CHANGE `validate_question` `validate_question` TINYINT(1) NULL DEFAULT NULL AFTER `enable_question`");
                    $this->executeQuery("ALTER TABLE `zselex_products` CHANGE `prd_question` `prd_question` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `validate_question`");
                    $this->executeQuery("ALTER TABLE `zselex_products` CHANGE `no_vat` `no_vat` TINYINT(1) NULL DEFAULT '0' AFTER `prd_question`");
                    if (!$this->colunmExist('zselex_products', 'max_discount')) {
                        $this->executeQuery("ALTER TABLE `zselex_products` ADD `max_discount` CHAR(10) NULL AFTER `no_vat`");
                    }
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.39';
                }
            case '1.5.40' :
                try {

                    if (!$this->colunmExist('zselex_products', 'no_delivery')) {
                        $this->executeQuery("ALTER TABLE `zselex_products` ADD `no_delivery` SMALLINT(1) NULL DEFAULT '0' AFTER `max_discount`");
                    }
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.40';
                }

            case '1.5.41' :
                EventUtil::registerPersistentModuleHandler('ZSELEX',
                    'frontcontroller.exception',
                    array('ZSELEX_Listener_ErrorListner', 'frontcontrollerError'));
                EventUtil::registerPersistentModuleHandler('ZSELEX',
                    'systemerror',
                    array('ZSELEX_Listener_ErrorListner', 'systemError'));
            case '1.5.42' :
                try {
                    $this->executeQuery("ALTER TABLE `zselex_region` CHANGE `cr_date` `cr_date` DATETIME NOT NULL");
                    $this->executeQuery("ALTER TABLE `zselex_region` CHANGE `lu_date` `lu_date` DATETIME NOT NULL");
                } catch (\Exception $e) {
                    LogUtil::registerError($this->__f('Error! Could not update table (%s).',
                            $e->getMessage()));
                    return '1.5.42';
                }
            case '1.5.43' :
        }

        return true;
    }

    public function colunmExist($table, $field)
    {
        /*
         * $query = DBUtil::executeSQL("SHOW columns from $table where field='$field'");
         * $result = $query->fetch();
         * return $result;
         */
        $statement = Doctrine_Manager::getInstance()->connection();
        $sql       = "SHOW columns from $table where field='$field'";
        $query     = $statement->execute($sql);
        $result    = $query->fetch();
        return $result;
    }

    public function setFullTextIndex()
    {
        $statement = Doctrine_Manager::getInstance()->connection();
        if (!$this->indexExist('zselex_shop', 'shop_name')) {
            $sql   = "ALTER TABLE `zselex_shop` ADD FULLTEXT(`shop_name`)";
            $query = $statement->execute($sql);
        }

        if (!$this->indexExist('zselex_keywords', 'keyword')) {
            $sql   = "ALTER TABLE `zselex_keywords` ADD FULLTEXT(`keyword`)";
            $query = $statement->execute($sql);
        }

        if (!$this->indexExist('zselex_shop', 'address')) {
            $sql   = "ALTER TABLE `zselex_shop` ADD FULLTEXT(`address`)";
            $query = $statement->execute($sql);
        }

        if (!$this->indexExist('zselex_country', 'address')) {
            $sql   = "ALTER TABLE `zselex_country` ADD FULLTEXT(`country_name`)";
            $query = $statement->execute($sql);
        }
        if (!$this->indexExist('zselex_region', 'region_name')) {
            $sql   = "ALTER TABLE `zselex_region` ADD FULLTEXT(`region_name`)";
            $query = $statement->execute($sql);
        }
        if (!$this->indexExist('zselex_city', 'city_name')) {
            $sql   = "ALTER TABLE `zselex_city` ADD FULLTEXT(`city_name`)";
            $query = $statement->execute($sql);
        }
        if (!$this->indexExist('zselex_area', 'area_name')) {
            $sql   = "ALTER TABLE `zselex_area` ADD FULLTEXT(`area_name`)";
            $query = $statement->execute($sql);
        }

        return true;
    }

    public function createFullTextIndex($table, $field)
    {
        $statement = Doctrine_Manager::getInstance()->connection();
        if (!$this->indexExist($table, $field)) {

            $sql   = "ALTER TABLE ".$table." ADD FULLTEXT(`$field`)";
            $query = $statement->execute($sql);
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

    public function indexExist($table, $field)
    {
        /*
         * $query = DBUtil::executeSQL("SHOW INDEX FROM $table WHERE Key_name = '$field' ");
         * $result = $query->fetch();
         * return $result;
         */
        $statement = Doctrine_Manager::getInstance()->connection();
        $sql       = "SHOW INDEX FROM $table WHERE Key_name = '$field'";
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
     * @return boolean true/false
     */
    public function uninstall()
    {
        // $result = DBUtil::dropTable('zselex');
        $result = true;
        $result = $result && $this->delVars();

        $tablefunc = 'zselex_tables';
        $data      = call_user_func($tablefunc);
        foreach ($data as $key => $val) {
            if ($key === $val) {
                // echo "'".$val."'" . '<br>';
                // DBUtil::dropTable($val);
            }
        }

        EventUtil::unregisterPersistentModuleHandlers('ZSELEX');

        // return $result;
        return true;
    }

    function curlExec($url)
    {
        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_URL, "http://localhost/zselex/index.php?module=zselex&type=admin&func=renameFolder");
        // return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);
    }

    function analyzeTables()
    {
        // $sqls[] = "ANALYZE TABLE zselex_advertise";
        // $sqls[] = "ANALYZE TABLE zselex_advertise";
        // echo "Analyze tables;"; exit;
        $statement = Doctrine_Manager::getInstance()->connection();
        $sql       = "SHOW TABLES";
        $query     = $statement->execute($sql);
        $result    = $query->fetchAll();
        // echo "<pre>"; print_r($result); echo "</pre>"; exit;
        foreach ($result as $key => $value) {
            // echo $value[0] . '<br>';
            $table = $value [0];
            $sql   = "ANALYZE TABLE $table";
            $query = $statement->execute($sql);
        }
        // exit;
        return true;
    }
}
// end class def
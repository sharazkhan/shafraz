<?php

/**
 * Copyright  2015.
 *
 * ZSELEX
 * Shopping Module
 *
 * @license ACTA-IT.
 */

/**
 * @function    zselex_tables
 * @params
 *
 * @return array table defs
 */
function zselex_tables() { //2016 started
    // Initialise table array
    $table = array();

    $table['zselex'] = DBUtil::getLimitedTablename('zselex');

    $table['zselex_column'] = array(
        'id' => 'bid',
        'text' => 'cid',);

    $table['zselex_column_def'] = array(
        'id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'text' => 'C(150) DEFAULT \'\'', // varchar(150) default ''
    );
    $table['zselex_db_extra_enable_categorization'] = true;
    $table['zselex_primary_key_column'] = 'id';

    // add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition($table['zselex_column'], '');
    ObjectUtil::addStandardFieldsToTableDataDefinition($table['zselex_column_def']);

    $table['zselex_type'] = DBUtil::getLimitedTablename('zselex_type');

    $table['zselex_type_column'] = array(
        'type_id' => 'type_id',
        'type_name' => 'type_name',
        'description' => 'description',
        'status' => 'status',
    );

    $table['zselex_type_column_def'] = array(
        'type_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'type_name' => 'C(250) DEFAULT NULL', // varchar(150) default ''
        'description' => 'XL NOTNULL', // text default ''
        'status' => 'I1 DEFAULT 0',
    );

    $table['zselex_type_db_extra_enable_categorization'] = true;
    $table['zselex_type_primary_key_column'] = 'type_id';

    // add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition($table['zselex_type_column'], '');
    ObjectUtil::addStandardFieldsToTableDataDefinition($table['zselex_type_column_def']);

    $table['zselex_country'] = DBUtil::getLimitedTablename('zselex_country');
    $table['zselex_country_column'] = array(
        'country_id' => 'country_id',
        'country_name' => 'country_name',
        'description' => 'description',
        'status' => 'status',
    );

    $table['zselex_country_column_def'] = array(
        'country_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'country_name' => 'C(50) DEFAULT NULL', // varchar(150) default ''
        'description' => 'XL NOTNULL', // text default ''
        'status' => 'I1 DEFAULT 0',
    );

    $table['zselex_country_db_extra_enable_categorization'] = true;
    $table['zselex_country_primary_key_column'] = 'country_id';

    // add standard data fields
    ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_country_column'], '');
    ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_country_column_def']);

    $table['zselex_branch'] = DBUtil::getLimitedTablename('zselex_branch');
    $table['zselex_branch_column'] = array(
        'branch_id' => 'branch_id',
        'branch_name' => 'branch_name',
        'description' => 'description',
        'status' => 'status',
    );
    $table['zselex_branch_column_def'] = array(
        'branch_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'branch_name' => 'C(50) DEFAULT NULL', // varchar(150) default ''
        'description' => 'XL NOTNULL', // text default ''
        'status' => 'I1 DEFAULT 0',
    );
    $table['zselex_branch_db_extra_enable_categorization'] = true;
    $table['zselex_branch_primary_key_column'] = 'branch_id';
    // add standard data fields
    ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_branch_column'], '');
    ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_branch_column_def']);

    $table['zselex_branch_shop'] = DBUtil::getLimitedTablename('zselex_branch_shop');
    $table['zselex_branch_shop_column'] = array(
        'id' => 'id',
        'branch_id' => 'branch_id',
        'shop_id' => 'shop_id',
    );
    $table['zselex_branch_shop_column_def'] = array(
        'id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'branch_id' => 'I DEFAULT 0',
        'shop_id' => 'I DEFAULT 0',
    );
    $table['zselex_branch_shop_db_extra_enable_categorization'] = true;
    $table['zselex_branch_shop_primary_key_column'] = 'id';
    // add standard data fields
    //ObjectUtil::addStandardFieldsToTableDefinition($table['zselex_branch_shop_column'], '');
    //ObjectUtil::addStandardFieldsToTableDataDefinition($table['zselex_branch_shop_column_def']);


    $table['zselex_region'] = DBUtil::getLimitedTablename('zselex_region');
    $table['zselex_region_column'] = array(
        'region_id' => 'region_id',
        'region_name' => 'region_name',
        'country_id' => 'country_id',
        'description' => 'description',
        'status' => 'status',
    );
    $table['zselex_region_column_def'] = array(
        'region_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'region_name' => 'C(50) DEFAULT NULL', // varchar(150) default ''
        'country_id' => 'I DEFAULT 0',
        'description' => 'XL NOTNULL', // text default ''
        'status' => 'I1 DEFAULT 0',
    );
    $table['zselex_region_db_extra_enable_categorization'] = true;
    $table['zselex_region_primary_key_column'] = 'region_id';
    // add standard data fields
    ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_region_column'], '');
    ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_region_column_def']);

    $table['zselex_advertise'] = DBUtil::getLimitedTablename('zselex_advertise');
    $table['zselex_advertise_column'] = array(
        'advertise_id' => 'advertise_id',
        'name' => 'name',
        'adprice_id' => 'adprice_id',
        'advertise_type' => 'advertise_type',
        'level' => 'level',
        'shop_id' => 'shop_id',
        'country_id' => 'country_id',
        'region_id' => 'region_id',
        'city_id' => 'city_id',
        'area_id' => 'area_id',
        'description' => 'description',
        'keywords' => 'keywords',
        'maxviews' => 'maxviews',
        'totalviews' => 'totalviews',
        'viewstoday' => 'viewstoday',
        'maxclicks' => 'maxclicks',
        'totalclicks' => 'totalclicks',
        'clickstoday' => 'clickstoday',
        'startdate' => 'startdate',
        'enddate' => 'enddate',
        'status' => 'status',
    );
    $table['zselex_advertise_column_def'] = array(
        'advertise_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'name' => 'C(250) DEFAULT NULL', // varchar(150) default ''
        'adprice_id' => 'I DEFAULT 0',
        'advertise_type' => 'C(100) DEFAULT NULL', // varchar(150) default ''
        'level' => 'C(100) DEFAULT NULL', // varchar(150) default ''
        'shop_id' => 'I DEFAULT 0',
        'country_id' => 'I DEFAULT 0',
        'region_id' => 'I DEFAULT 0',
        'city_id' => 'I DEFAULT 0',
        'area_id' => 'I DEFAULT 0',
        'description' => 'XL NOTNULL', // text default ''
        'keywords' => 'XL NOTNULL', // text default ''
        'maxviews' => 'I DEFAULT -1',
        'totalviews' => 'I DEFAULT 0',
        'viewstoday' => 'I DEFAULT 0',
        'maxclicks' => 'I DEFAULT -1',
        'totalclicks' => 'I DEFAULT 0',
        'clickstoday' => 'I DEFAULT 0',
        'startdate' => 'T DEFAULT NULL',
        'enddate' => 'T DEFAULT NULL',
        'status' => 'I1 NOTNULL DEFAULT 0',
    );
    $table['zselex_advertise_db_extra_enable_categorization'] = true;
    $table['zselex_advertise_primary_key_column'] = 'advertise_id';
    // add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition($table['zselex_advertise_column'], '');
    ObjectUtil::addStandardFieldsToTableDataDefinition($table['zselex_advertise_column_def']);

    $table['zselex_advertise_price'] = DBUtil::getLimitedTablename('zselex_advertise_price');
    $table['zselex_advertise_price_column'] = array(
        'adprice_id' => 'adprice_id',
        'name' => 'name',
        'identifier' => 'identifier',
        'pricetype' => 'pricetype',
        'price' => 'price',
        'description' => 'description',
        'status' => 'status',
    );
    $table['zselex_advertise_price_column_def'] = array(
        'adprice_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'name' => 'C(500) DEFAULT NULL',
        'identifier' => 'C(10) DEFAULT NULL',
        'pricetype' => 'C(250) DEFAULT NULL',
        'price' => 'I DEFAULT 0', // varchar(150) default ''
        'description' => 'XL NOTNULL', // text default ''
        'status' => 'I1 NOTNULL DEFAULT 0',
    );
    $table['zselex_advertise_price_db_extra_enable_categorization'] = true;
    $table['zselex_advertise_price_primary_key_column'] = 'adprice_id';
    // add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition($table['zselex_advertise_price_column'], '');
    ObjectUtil::addStandardFieldsToTableDataDefinition($table['zselex_advertise_price_column_def']);

    $table['zselex_city'] = DBUtil::getLimitedTablename('zselex_city');
    $table['zselex_city_column'] = array(
        'city_id' => 'city_id',
        'city_name' => 'city_name',
        'region_id' => 'region_id',
        'country_id' => 'country_id',
        'description' => 'description',
        'status' => 'status',
    );
    $table['zselex_city_column_def'] = array(
        'city_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'city_name' => 'C(250) DEFAULT NULL',
        'region_id' => 'I DEFAULT 0',
        'country_id' => 'I DEFAULT 0',
        'description' => 'XL NOTNULL', // text default ''
        'status' => 'I1 NOTNULL DEFAULT 0',
    );
    $table['zselex_city_db_extra_enable_categorization'] = true;
    $table['zselex_city_primary_key_column'] = 'city_id';
    // add standard data fields
    ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_city_column'], '');
    ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_city_column_def']);

    $table['zselex_category'] = DBUtil::getLimitedTablename('zselex_category');
    $table['zselex_category_column'] = array(
        'category_id' => 'category_id',
        'category_name' => 'category_name',
        // 'parent_cat_id' => 'parent_cat_id',
        'description' => 'description',
        'status' => 'status',
    );
    $table['zselex_category_column_def'] = array(
        'category_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'category_name' => 'C(250) DEFAULT NULL',
        //'parent_cat_id' => 'I DEFAULT 0',
        'description' => 'XL NOTNULL', // text default ''
        'status' => 'I1 NOTNULL DEFAULT 0',
    );
    $table['zselex_category_db_extra_enable_categorization'] = true;
    $table['zselex_category_primary_key_column'] = 'category_id';
    // add standard data fields
    ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_category_column'], '');
    ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_category_column_def']);

    $table['zselex_category_shop'] = DBUtil::getLimitedTablename('zselex_category_shop');
    $table['zselex_category_shop_column'] = array(
        'id' => 'id',
        'cat_id' => 'cat_id',
        'shop_id' => 'shop_id',
    );
    $table['zselex_category_shop_column_def'] = array(
        'id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'cat_id' => 'I DEFAULT 0',
        'shop_id' => 'I DEFAULT 0',
    );
    $table['zselex_category_shop_db_extra_enable_categorization'] = true;
    $table['zselex_category_shop_primary_key_column'] = 'id';
    // add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition($table['zselex_category_column'], '');
    ObjectUtil::addStandardFieldsToTableDataDefinition($table['zselex_category_column_def']);

    $table['zselex_parent'] = DBUtil::getLimitedTablename('zselex_parent');
    $table['zselex_parent_column'] = array(
        'tableId' => 'tableId',
        'childType' => 'childType',
        'childId' => 'childId',
        'parentId' => 'parentId',
        'parentType' => 'parentType',
        'status' => 'status',
    );
    $table['zselex_parent_column_def'] = array(
        'tableId' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'childType' => 'C(100) DEFAULT NULL',
        'childId' => 'I DEFAULT 0',
        'parentId' => 'I DEFAULT 0',
        'parentType' => 'C(250) DEFAULT NULL', // varchar(150) default ''
        'status' => 'I1 NOTNULL DEFAULT 0',
    );
    $table['zselex_parent_db_extra_enable_categorization'] = true;
    $table['zselex_parent_primary_key_column'] = 'tableId';
    // add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition($table['zselex_parent_column'], '');
    ObjectUtil::addStandardFieldsToTableDataDefinition($table['zselex_parent_column_def']);

    $table['zselex_plugin'] = DBUtil::getLimitedTablename('zselex_plugin');
    $table['zselex_plugin_column'] = array(
        'plugin_id' => 'plugin_id',
        'plugin_name' => 'plugin_name',
        'identifier' => 'identifier',
        // 'parent' => 'parent',
        'type' => 'type',
        'qty_based' => 'qty_based',
        'description' => 'description',
        'content' => 'content',
        'price' => 'price',
        'bundle' => 'bundle',
        'bundle_id' => 'bundle_id',
        'top_bundle' => 'top_bundle',
        'service_depended' => 'service_depended',
        'depended_services' => 'depended_services',
        'shop_depended' => 'shop_depended',
        'depended_shoptypes' => 'depended_shoptypes',
        'is_editable' => 'is_editable',
        'func_name' => 'func_name',
        'status' => 'status',
        'demo' => 'demo',
        'demoperiod' => 'demoperiod',
        'sort_order' => 'sort_order',
    );
    $table['zselex_plugin_column_def'] = array(
        'plugin_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'plugin_name' => 'C(250) DEFAULT NULL',
        'identifier' => 'C(250) DEFAULT NULL',
        // 'parent' => 'C(250) DEFAULT NULL',
        'type' => 'C(250) DEFAULT NULL',
        'qty_based' => 'I1 NOTNULL DEFAULT 0',
        'description' => 'XL NOTNULL',
        'content' => 'XL NOTNULL',
        'price' => 'C(250) DEFAULT NULL',
        'bundle' => 'I1 NOTNULL DEFAULT 0',
        'bundle_id' => 'I DEFAULT 0',
        'top_bundle' => 'I1 NOTNULL DEFAULT 0',
        'service_depended' => 'I1 NOTNULL DEFAULT 0',
        'depended_services' => 'XL NOTNULL',
        'shop_depended' => 'I1 NOTNULL DEFAULT 0',
        'depended_shoptypes' => 'XL NOTNULL',
        'is_editable' => 'I1 NOTNULL DEFAULT 0',
        'func_name' => 'C(100) DEFAULT NULL',
        'status' => 'I DEFAULT 0',
        'demo' => 'I1 NOTNULL DEFAULT 0',
        'demoperiod' => 'I DEFAULT 0',
        'sort_order' => 'I DEFAULT 0',
    );
    $table['zselex_plugin_db_extra_enable_categorization'] = true;
    $table['zselex_plugin_primary_key_column'] = 'plugin_id';
    // add standard data fields
    ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_plugin_column'], '');
    ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_plugin_column_def']);

    $table['zselex_shop'] = DBUtil::getLimitedTablename('zselex_shop');
    $table['zselex_shop_column'] = array(
        'shop_id' => 'shop_id',
        'title' => 'title',
        'urltitle' => 'urltitle',
        'user_id' => 'user_id',
        'country_id' => 'country_id',
        'region_id' => 'region_id',
        'city_id' => 'city_id',
        'area_id' => 'area_id',
        //'cat_id' => 'cat_id',
        'branch_id' => 'branch_id',
        'theme' => 'theme',
        'shop_name' => 'shop_name',
        'description' => 'description',
        'shop_info' => 'shop_info',
        'address' => 'address',
        'telephone' => 'telephone',
        'fax' => 'fax',
        'email' => 'email',
        'opening_hours' => 'opening_hours',
        'pictures' => 'pictures',
        'default_img_frm' => 'default_img_frm',
        'main' => 'main',
        'meta_tag' => 'meta_tag',
        'meta_description' => 'meta_description',
        'shoptype_id' => 'shoptype_id',
        'link_to_homepage' => 'link_to_homepage',
        'link_to_mailinglist' => 'link_to_mailinglist',
        'status' => 'status',
        'aff_id' => 'aff_id',
        'vat_number' => 'vat_number',
    );
    $table['zselex_shop_column_def'] = array(
        'shop_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'title' => 'C(250) DEFAULT NULL',
        'urltitle' => 'C(250) DEFAULT NULL',
        'user_id' => 'I DEFAULT 0',
        'country_id' => 'I DEFAULT 0',
        'region_id' => 'I DEFAULT 0',
        'city_id' => 'I DEFAULT 0',
        'area_id' => 'I DEFAULT 0',
        // 'cat_id' => 'I DEFAULT 0',
        'branch_id' => 'I DEFAULT 0',
        'theme' => 'C(250) DEFAULT NULL',
        'shop_name' => 'C(250) DEFAULT NULL',
        'description' => 'XL NOTNULL',
        'shop_info' => 'XL NOTNULL',
        'address' => 'XL NOTNULL',
        'telephone' => 'C(25) DEFAULT NULL',
        'fax' => 'C(150) DEFAULT NULL',
        'email' => 'C(150) DEFAULT NULL',
        'opening_hours' => 'XL NULL',
        'pictures' => 'C(150) DEFAULT NULL',
        'default_img_frm' => 'C(150) DEFAULT NULL',
        'main' => 'I1 NOTNULL DEFAULT 0',
        'meta_tag' => 'XL NULL',
        'meta_description' => 'XL NULL',
        'shoptype_id' => 'I DEFAULT 0',
        'link_to_homepage' => 'XL NULL',
        'link_to_mailinglist' => 'XL NULL',
        'status' => 'I1 NOTNULL DEFAULT 0',
        'aff_id' => 'I DEFAULT 0',
        'vat_number' => 'C(300) DEFAULT NULL',
    );

    $table['zselex_shop_db_extra_enable_categorization'] = true;
    $table['zselex_shop_primary_key_column'] = 'shop_id';
    // add standard data fields
    ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_shop_column'], '');
    ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_shop_column_def']);

    $table['zselex_shop_config'] = DBUtil::getLimitedTablename('zselex_shop_config');
    $table['zselex_shop_config_column'] = array(
        'shopconfigId' => 'shopconfigId',
        'shop_id' => 'shop_id',
        'shoptype_id' => 'shoptype_id',
        'domain' => 'domain',
        'hostname' => 'hostname',
        'dbname' => 'dbname',
        'username' => 'username',
        'password' => 'password',
        'table_prefix' => 'table_prefix',
        'status' => 'status',
    );
    $table['zselex_shop_config_column_def'] = array(
        'shopconfigId' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'shop_id' => 'I DEFAULT 0',
        'shoptype_id' => 'I DEFAULT 0',
        'domain' => 'C(250) DEFAULT NULL',
        'hostname' => 'C(250) DEFAULT NULL',
        'dbname' => 'C(250) DEFAULT NULL',
        'username' => 'C(250) DEFAULT NULL',
        'password' => 'C(250) DEFAULT NULL',
        'table_prefix' => 'C(250) DEFAULT NULL',
        'status' => 'I1 NOTNULL DEFAULT 0',
    );
    $table['zselex_shop_config_db_extra_enable_categorization'] = true;
    $table['zselex_shop_config_primary_key_column'] = 'shopconfigId';
    // add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition($table['zselex_shop_config_column'], '');
    ObjectUtil::addStandardFieldsToTableDataDefinition($table['zselex_shop_config_column_def']);

    $table['zselex_shop_types'] = DBUtil::getLimitedTablename('zselex_shop_types');
    $table['zselex_shop_types_column'] = array(
        'shoptype_id' => 'shoptype_id',
        'shoptype' => 'shoptype',
        'description' => 'description',
        'status' => 'status',
    );
    $table['zselex_shop_types_column_def'] = array(
        'shoptype_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'shoptype' => 'C(250) DEFAULT NULL',
        'description' => 'XL NOTNULL',
        'status' => 'I1 NOTNULL DEFAULT 0',
    );
    $table['zselex_shop_types_db_extra_enable_categorization'] = true;
    $table['zselex_shop_types_primary_key_column'] = 'shoptype_id';
    // add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition($table['zselex_shop_types_column'], '');
    ObjectUtil::addStandardFieldsToTableDataDefinition($table['zselex_shop_types_column_def']);

/////////////////////////////////////////////Service Basket(Cart)////////////////////////////////////////////
    $table['zselex_basket'] = DBUtil::getLimitedTablename('zselex_basket');
    $table['zselex_basket_column'] = array(
        'basket_id' => 'basket_id',
        'plugin_id' => 'plugin_id',
        'type' => 'type',
        'shop_id' => 'shop_id',
        'user_id' => 'user_id',
        'owner_id' => 'owner_id',
        'quantity' => 'quantity',
        'qty_based' => 'qty_based',
        'bundle' => 'bundle',
        'bundle_id' => 'bundle_id',
        'top_bundle' => 'top_bundle',
        'bundle_type' => 'bundle_type',
        'original_price' => 'original_price',
        'price' => 'price',
        'status' => 'status',
        'service_status' => 'service_status',
        'subtotal' => 'subtotal',
        'timer_days' => 'timer_days',
    );

    $table['zselex_basket_column_def'] = array(
        'basket_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'plugin_id' => 'I DEFAULT 0',
        'type' => 'C(250) DEFAULT NULL',
        'shop_id' => 'I DEFAULT 0',
        'user_id' => 'I DEFAULT 0',
        'owner_id' => 'I DEFAULT 0',
        'quantity' => 'I DEFAULT 0',
        'qty_based' => 'I1 NOTNULL DEFAULT 0',
        'bundle' => 'I1 NOTNULL DEFAULT 0',
        'bundle_id' => 'I DEFAULT 0',
        'top_bundle' => 'I1 NOTNULL DEFAULT 0',
        'bundle_type' => 'C(15) DEFAULT NULL',
        'original_price' => 'C(250) DEFAULT NULL',
        'price' => 'C(250) DEFAULT NULL',
        'status' => 'I1 NOTNULL DEFAULT 0',
        'service_status' => 'I1 NOTNULL DEFAULT 0',
        'subtotal' => 'I DEFAULT 0',
        'timer_days' => 'I DEFAULT 0',
    );

    $table['zselex_basket_db_extra_enable_categorization'] = true;
    $table['zselex_basket_primary_key_column'] = 'basket_id';
    // add standard data fields
    ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_basket_column'], '');
    ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_basket_column_def']);

/////////////////////////////////////////zselex_serviceshop ////////////////////////
    $table['zselex_serviceshop'] = DBUtil::getLimitedTablename('zselex_serviceshop');
    $table['zselex_serviceshop_column'] = array(
        'id' => 'id',
        'shop_id' => 'shop_id',
        'user_id' => 'user_id',
        'owner_id' => 'owner_id',
        'plugin_id' => 'plugin_id',
        'type' => 'type',
        'original_quantity' => 'original_quantity',
        'quantity' => 'quantity',
        'availed' => 'availed',
        'extra' => 'extra',
        'status' => 'status',
        'service_status' => 'service_status',
        'qty_based' => 'qty_based',
        'bundle' => 'bundle',
        'bundle_id' => 'bundle_id',
        //'main_bundle' => 'main_bundle',
        'top_bundle' => 'top_bundle',
        'bundle_type' => 'bundle_type',
        'timer_date' => 'timer_date',
        'timer_days' => 'timer_days',
    );
    $table['zselex_serviceshop_column_def'] = array(
        'id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'shop_id' => 'I DEFAULT 0',
        'user_id' => 'I DEFAULT 0',
        'owner_id' => 'I DEFAULT 0',
        'plugin_id' => 'I DEFAULT 0',
        'type' => 'C(250) DEFAULT NULL',
        'original_quantity' => 'I DEFAULT 0',
        'quantity' => 'I DEFAULT 0',
        'availed' => 'I DEFAULT 0',
        'extra' => 'C(100) DEFAULT NULL',
        'status' => 'I1 NOTNULL DEFAULT 0',
        'service_status' => 'I1 NOTNULL DEFAULT 0',
        'qty_based' => 'I1 NOTNULL DEFAULT 0',
        'bundle' => 'I1 NOTNULL DEFAULT 0',
        'bundle_id' => 'I DEFAULT 0',
        //'main_bundle' => 'I DEFAULT 0',
        'top_bundle' => 'I1 NOTNULL DEFAULT 0',
        'bundle_type' => 'C(100) DEFAULT NULL',
        'timer_date' => 'C(100) DEFAULT NULL',
        'timer_days' => 'I DEFAULT 0',
    );
    $table['zselex_serviceshop_db_extra_enable_categorization'] = true;
    $table['zselex_serviceshop_primary_key_column'] = 'id';
    // add standard data fields
    ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_serviceshop_column'], '');
    ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_serviceshop_column_def']);
/////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////zselex_servicesconfigured_status////////////////////////
    $table['zselex_service_demo'] = DBUtil::getLimitedTablename('zselex_service_demo');
    $table['zselex_service_demo_column'] = array(
        'demo_id' => 'demo_id',
        'shop_id' => 'shop_id',
        'plugin_id' => 'plugin_id',
        'type' => 'type',
        'user_id' => 'user_id',
        'owner_id' => 'owner_id',
        'quantity' => 'quantity',
        'availed' => 'availed',
        'qty_based' => 'qty_based',
        'bundle' => 'bundle',
        'bundle_id' => 'bundle_id',
        'top_bundle' => 'top_bundle',
        'bundle_type' => 'bundle_type',
        'status' => 'status',
        'start_date' => 'start_date',
        'timer_days' => 'timer_days',
    );
    $table['zselex_service_demo_column_def'] = array(
        'demo_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'shop_id' => 'I DEFAULT 0',
        'plugin_id' => 'I DEFAULT 0',
        'type' => 'C(250) DEFAULT NULL',
        'user_id' => 'I DEFAULT 0',
        'owner_id' => 'C(250) DEFAULT NULL',
        'quantity' => 'I DEFAULT 0',
        'availed' => 'I DEFAULT 0',
        'qty_based' => 'I1 NOTNULL DEFAULT 0',
        'bundle' => 'I1 NOTNULL DEFAULT 0',
        'bundle_id' => 'I DEFAULT 0',
        'top_bundle' => 'I1 NOTNULL DEFAULT 0',
        'bundle_type' => 'C(100) DEFAULT NULL',
        'status' => 'I1 NOTNULL DEFAULT 0',
        'start_date' => 'C(250) DEFAULT NULL',
        'timer_days' => 'I DEFAULT 0',
    );
    $table['zselex_service_demo_db_extra_enable_categorization'] = true;
    $table['zselex_service_demo_primary_key_column'] = 'demo_id';
    // add standard data fields
    //  ObjectUtil::addStandardFieldsToTableDefinition($table['zselex_serviceconfigured_status_column'], '');
    // ObjectUtil::addStandardFieldsToTableDataDefinition($table['zselex_serviceconfigured_status_column_def']);
/////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////zselex_servicesconfigured_status////////////////////////
    $table['zselex_service_config'] = DBUtil::getLimitedTablename('zselex_service_config');
    $table['zselex_service_config_column'] = array(
        'id' => 'id',
        'shop_id' => 'shop_id',
        'plugin_id' => 'plugin_id',
        'type' => 'type',
        'user_id' => 'user_id',
        'owner_id' => 'owner_id',
        'quantity' => 'quantity',
        'availed' => 'availed',
        'service_status' => 'service_status',
        'qty_based' => 'qty_based',
        'bundle' => 'bundle',
        'bundle_id' => 'bundle_id',
        'top_bundle' => 'top_bundle',
        'start_date' => 'start_date',
    );
    $table['zselex_service_config_column_def'] = array(
        'id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'shop_id' => 'I DEFAULT 0',
        'plugin_id' => 'I DEFAULT 0',
        'type' => 'C(250) DEFAULT NULL',
        'user_id' => 'I DEFAULT 0',
        'owner_id' => 'C(250) DEFAULT NULL',
        'quantity' => 'I DEFAULT 0',
        'availed' => 'I DEFAULT 0',
        'service_status' => 'I1 NOTNULL DEFAULT 0',
        'qty_based' => 'I1 NOTNULL DEFAULT 0',
        'bundle' => 'I1 NOTNULL DEFAULT 0',
        'bundle_id' => 'I DEFAULT 0',
        'top_bundle' => 'I1 NOTNULL DEFAULT 0',
        'start_date' => 'C(250) DEFAULT NULL',
    );
    $table['zselex_service_config_db_extra_enable_categorization'] = true;
    $table['zselex_service_config_primary_key_column'] = 'id';
    // add standard data fields
    //  ObjectUtil::addStandardFieldsToTableDefinition($table['zselex_serviceconfigured_status_column'], '');
    // ObjectUtil::addStandardFieldsToTableDataDefinition($table['zselex_serviceconfigured_status_column_def']);
/////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////zselex_service_order////////////////////////////////////////////
    $table['zselex_service_order'] = DBUtil::getLimitedTablename('zselex_service_order');
    $table['zselex_service_order_column'] = array(
        'id' => 'id',
        'order_id' => 'order_id',
        'user_id' => 'user_id',
        'status' => 'status',
        'grand_total' => 'grand_total',
        'payment_method' => 'payment_method',
    );
    $table['zselex_service_order_column_def'] = array(
        'id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'order_id' => 'C(250) DEFAULT NULL',
        'user_id' => 'I DEFAULT 0',
        'status' => 'C(50) DEFAULT NULL',
        'grand_total' => 'I DEFAULT 0',
        'payment_method' => 'C(250) DEFAULT NULL',
    );
    $table['zselex_service_order_db_extra_enable_categorization'] = true;
    $table['zselex_service_order_primary_key_column'] = 'id';
    // add standard data fields
    //ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_service_order_column'], '');
    //ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_service_order_column_def']);
///////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////zselex_service_orderitems////////////////////////////////////////////
    $table['zselex_service_orderitems'] = DBUtil::getLimitedTablename('zselex_service_orderitems');
    $table['zselex_service_orderitems_column'] = array(
        'order_item_id' => 'order_item_id',
        'order_id' => 'order_id',
        'plugin_id' => 'plugin_id',
        'type' => 'type',
        'shop_id' => 'shop_id',
        'user_id' => 'user_id',
        'owner_id' => 'owner_id',
        'quantity' => 'quantity',
        'price' => 'price',
        'status' => 'status',
        'service_status' => 'service_status',
        'timer_days' => 'timer_days',
        'qty_based' => 'qty_based',
        'bundle' => 'bundle',
        'bundle_id' => 'bundle_id',
        'subtotal' => 'subtotal',
    );
    $table['zselex_service_orderitems_column_def'] = array(
        'order_item_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'order_id' => 'C(50) DEFAULT NULL',
        'plugin_id' => 'I DEFAULT 0',
        'type' => 'C(250) DEFAULT NULL',
        'shop_id' => 'I DEFAULT 0',
        'user_id' => 'I DEFAULT 0',
        'owner_id' => 'I DEFAULT 0',
        'quantity' => 'I DEFAULT 0',
        'price' => 'C(250) DEFAULT NULL',
        'status' => 'I1 NOTNULL DEFAULT 0',
        'service_status' => 'I1 NOTNULL DEFAULT 0',
        'timer_days' => 'I DEFAULT 0',
        'qty_based' => 'I1 NOTNULL DEFAULT 0',
        'bundle' => 'I1 NOTNULL DEFAULT 0',
        'bundle_id' => 'I DEFAULT 0',
        'subtotal' => 'I DEFAULT 0',
    );
    $table['zselex_service_orderitems_db_extra_enable_categorization'] = true;
    $table['zselex_service_orderitems_primary_key_column'] = 'order_item_id';
    // add standard data fields
    ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_service_orderitems_column'], '');
    ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_service_orderitems_column_def']);

//////////////////////zselex_serviceapproval//////////////////////////////////////////////
    $table['zselex_serviceapproval'] = DBUtil::getLimitedTablename('zselex_serviceapproval');

    $table['zselex_serviceapproval_column'] = array(
        'id' => 'id',
        'shop_id' => 'shop_id',
        'user_id' => 'user_id',
        'plugin_id' => 'plugin_id',
        'type' => 'type',
        'bundle' => 'bundle',
        'bundle_id' => 'bundle_id',
        'top_bundle' => 'top_bundle',
        'quantity' => 'quantity',
        'status' => 'status',
        'service_status' => 'service_status',
        'qty_based' => 'qty_based',
        'timer_days' => 'timer_days',
    );

    $table['zselex_serviceapproval_column_def'] = array(
        'id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'shop_id' => 'I DEFAULT 0',
        'user_id' => 'I DEFAULT 0',
        'plugin_id' => 'I DEFAULT 0',
        'type' => 'C(250) DEFAULT NULL',
        'bundle' => 'I1 NOTNULL DEFAULT 0',
        'bundle_id' => 'I DEFAULT 0',
        'top_bundle' => 'I1 NOTNULL DEFAULT 0',
        'quantity' => 'I DEFAULT 0',
        'status' => 'I1 NOTNULL DEFAULT 0',
        'service_status' => 'I1 NOTNULL DEFAULT 0',
        'qty_based' => 'I1 NOTNULL DEFAULT 0',
        'timer_days' => 'I DEFAULT 0',
    );

    $table['zselex_serviceapproval_db_extra_enable_categorization'] = true;
    $table['zselex_serviceapproval_primary_key_column'] = 'id';

    // add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition($table['zselex_serviceapproval_column'], '');
    ObjectUtil::addStandardFieldsToTableDataDefinition($table['zselex_serviceapproval_column_def']);
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////zselex_files//////////////////////////////////////////////
    $table['zselex_files'] = DBUtil::getLimitedTablename('zselex_files');

    $table['zselex_files_column'] = array(
        'file_id' => 'file_id',
        'name' => 'name',
        'dispname' => 'dispname',
        'shop_id' => 'shop_id',
        'user_id' => 'user_id',
        'filedescription' => 'filedescription',
        'keywords' => 'keywords',
        'defaultImg' => 'defaultImg',
        'status' => 'status',
        'sort_order' => 'sort_order',
    );

    $table['zselex_files_column_def'] = array(
        'file_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'name' => 'C(250) DEFAULT NULL',
        'dispname' => 'C(250) DEFAULT NULL',
        'shop_id' => 'I DEFAULT 0',
        'user_id' => 'I DEFAULT 0',
        'filedescription' => 'XL NOTNULL',
        'keywords' => 'XL NOTNULL',
        'defaultImg' => 'I1 NOTNULL DEFAULT 0',
        'status' => 'I1 NOTNULL DEFAULT 0',
        'sort_order' => 'I DEFAULT 0',
    );

    $table['zselex_files_db_extra_enable_categorization'] = true;
    $table['zselex_files_primary_key_column'] = 'file_id';

    // add standard data fields
    //ObjectUtil::addStandardFieldsToTableDefinition($table['zselex_files_column'], '');
    //ObjectUtil::addStandardFieldsToTableDataDefinition($table['zselex_files_column_def']);
///////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////zselex_shop_gallery//////////////////////////////////////////////
    $table['zselex_shop_gallery'] = DBUtil::getLimitedTablename('zselex_shop_gallery');
    $table['zselex_shop_gallery_column'] = array(
        'gallery_id' => 'gallery_id',
        'image_name' => 'image_name',
        'shop_id' => 'shop_id',
        'user_id' => 'user_id',
        'image_description' => 'image_description',
        'keywords' => 'keywords',
        'defaultImg' => 'defaultImg',
    );

    $table['zselex_shop_gallery_column_def'] = array(
        'gallery_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'image_name' => 'C(250) DEFAULT NULL',
        'shop_id' => 'I DEFAULT 0',
        'user_id' => 'I DEFAULT 0',
        'image_description' => 'XL NOTNULL',
        'keywords' => 'XL NOTNULL',
        'defaultImg' => 'I DEFAULT 0',
    );

    $table['zselex_shop_gallery_db_extra_enable_categorization'] = true;
    $table['zselex_shop_gallery_primary_key_column'] = 'gallery_id';

    // add standard data fields
    //ObjectUtil::addStandardFieldsToTableDefinition($table['zselex_shop_gallery_column'], '');
    //ObjectUtil::addStandardFieldsToTableDataDefinition($table['zselex_shop_gallery_column_def']);
///////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////zselex_products//////////////////////////////////////////////
    $table['zselex_products'] = DBUtil::getLimitedTablename('zselex_products');

    $table['zselex_products_column'] = array(
        'product_id' => 'product_id',
        'shop_id' => 'shop_id',
        //'category_id' => 'category_id',
        'product_name' => 'product_name',
        'urltitle' => 'urltitle',
        'prd_description' => 'prd_description',
        'manufacturer_id' => 'manufacturer_id',
        'keywords' => 'keywords',
        'original_price' => 'original_price',
        'prd_price' => 'prd_price',
        'discount' => 'discount',
        'prd_quantity' => 'prd_quantity',
        'prd_image' => 'prd_image',
        'prd_status' => 'prd_status',
    );

    $table['zselex_products_column_def'] = array(
        'product_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'shop_id' => 'I DEFAULT 0',
        // 'category_id' => 'I DEFAULT 0',
        'product_name' => 'C(250) DEFAULT NULL',
        'urltitle' => 'C(250) DEFAULT NULL',
        'prd_description' => 'XL NOTNULL',
        'manufacturer_id' => 'I DEFAULT 0',
        'keywords' => 'XL NOTNULL',
        'original_price' => 'C(250) DEFAULT NULL',
        'prd_price' => 'C(250) DEFAULT NULL',
        'discount' => 'C(250) DEFAULT NULL',
        'prd_quantity' => 'I DEFAULT 0',
        'prd_image' => 'C(250) DEFAULT NULL',
        'prd_status' => 'I1 NOTNULL DEFAULT 0',
    );

    $table['zselex_products_db_extra_enable_categorization'] = true;
    $table['zselex_products_primary_key_column'] = 'product_id';

    // add standard data fields
    ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_products_column'], '');
    ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_products_column_def']);
///////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////zselex_order//////////////////////////////////////////////
    $table['zselex_order'] = DBUtil::getLimitedTablename('zselex_order');

    $table['zselex_order_column'] = array(
        'id' => 'id',
        'order_id' => 'order_id',
        'shop_id' => 'shop_id',
        'user_id' => 'user_id',
        'first_name' => 'first_name',
        'last_name' => 'last_name',
        'email' => 'email',
        'city' => 'city',
        'street' => 'street',
        'address' => 'address',
        'phone' => 'phone',
        'totalprice' => 'totalprice',
        'vat' => 'vat',
        'shipping' => 'shipping',
        'status' => 'status',
        'payment_type' => 'payment_type',
    );

    $table['zselex_order_column_def'] = array(
        'id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'order_id' => 'C(250) DEFAULT NULL',
        'shop_id' => 'I DEFAULT 0',
        'user_id' => 'I DEFAULT 0',
        'first_name' => 'C(250) DEFAULT NULL',
        'last_name' => 'C(250) DEFAULT NULL',
        'email' => 'C(250) DEFAULT NULL',
        'city' => 'C(250) DEFAULT NULL',
        'street' => 'C(250) DEFAULT NULL',
        'address' => 'C(250) DEFAULT NULL',
        'phone' => 'I(100) UNSIGNED',
        'totalprice' => 'DECIMAL(15,2)  NOT NULL',
        'vat' => 'DECIMAL(15,2)  NOT NULL',
        'shipping' => 'DECIMAL(15,2)  NOT NULL',
        'status' => 'C(250) DEFAULT NULL',
        'payment_type' => 'C(250) DEFAULT NULL',
    );

    $table['zselex_order_db_extra_enable_categorization'] = true;
    $table['zselex_order_primary_key_column'] = 'id';

    // add standard data fields
    ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_order_column'], '');
    ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_order_column_def']);
///////////////////////////////////////////////////////////////////////////////////////////////


    $table['zselex_orderitems'] = DBUtil::getLimitedTablename('zselex_orderitems');
    $table['zselex_orderitems_column'] = array(
        'item_id' => 'item_id',
        'product_id' => 'product_id',
        'shop_id' => 'shop_id',
        'order_id' => 'order_id',
        'quantity' => 'quantity',
        'product_options' => 'product_options',
        'price' => 'price',
        'options_price' => 'options_price',
        'total' => 'total',
    );

    $table['zselex_orderitems_column_def'] = array(
        'item_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'product_id' => 'I DEFAULT 0',
        'shop_id' => 'I DEFAULT 0',
        'order_id' => 'C(250) DEFAULT NULL',
        'quantity' => 'I DEFAULT 0',
        'product_options' => 'XL NOTNULL',
        'price' => 'DECIMAL(15,4)  NOT NULL',
        'options_price' => 'DECIMAL(15,4)  NOT NULL',
        'total' => 'DECIMAL(15,4)  NOT NULL',
    );

    $table['zselex_orderitems_db_extra_enable_categorization'] = true;
    $table['zselex_orderitems_primary_key_column'] = 'item_id';

    // add standard data fields
    // ObjectUtil::addStandardFieldsToTableDefinition($table['zselex_order_column'], '');
    // ObjectUtil::addStandardFieldsToTableDataDefinition($table['zselex_order_column_def']);


    $table['zselex_shop_news'] = DBUtil::getLimitedTablename('zselex_shop_news');
    $table['zselex_shop_news_column'] = array(
        'shop_news_id' => 'shop_news_id',
        'shop_id' => 'shop_id',
        'news_id' => 'news_id',
        'cr_uid' => 'cr_uid',
    );

    $table['zselex_shop_news_column_def'] = array(
        'shop_news_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'shop_id' => 'I DEFAULT 0',
        'news_id' => 'I DEFAULT 0',
        'cr_uid' => 'I DEFAULT 0',
    );

    $table['zselex_shop_news_db_extra_enable_categorization'] = true;
    $table['zselex_shop_news_primary_key_column'] = 'shop_news_id';

    //dotd table
    $table['zselex_dotd'] = DBUtil::getLimitedTablename('zselex_dotd');
    $table['zselex_dotd_column'] = array(
        'dotdId' => 'dotdId',
        'dotd_name' => 'dotd_name',
        'shop_id' => 'shop_id',
        'user_id' => 'user_id',
        'dotd_date' => 'dotd_date',
        'keywords' => 'keywords',
        'column_name' => 'column_name',
        'value' => 'value',
        'status' => 'status',
    );

    $table['zselex_dotd_column_def'] = array(
        'dotdId' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'dotd_name' => 'C(250) DEFAULT NULL',
        'shop_id' => 'I DEFAULT 0',
        'user_id' => 'I DEFAULT 0',
        'dotd_date' => 'C(250) DEFAULT NULL',
        'keywords' => 'XL NOTNULL',
        'column_name' => 'C(250) DEFAULT NULL',
        'value' => 'C(250) DEFAULT NULL',
        'status' => 'I1 NOTNULL DEFAULT 0',
    );

    $table['zselex_dotd_db_extra_enable_categorization'] = true;
    $table['zselex_dotd_primary_key_column'] = 'dotdId';
    // add standard data fields
    ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_dotd_column'], '');
    ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_dotd_column_def']);

    /////////////////////zselex_shop_owners////////////////////////////////////////////////
    $table['zselex_shop_owners'] = DBUtil::getLimitedTablename('zselex_shop_owners');
    $table['zselex_shop_owners_column'] = array(
        'id' => 'id',
        'user_id' => 'user_id',
        'shop_id' => 'shop_id',
        'main' => 'main',
    );

    $table['zselex_shop_owners_column_def'] = array(
        'id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'shop_id' => 'I DEFAULT 0',
        'user_id' => 'I DEFAULT 0',
        'main' => 'I1 NOTNULL DEFAULT 0',
    );

    $table['zselex_shop_owners_db_extra_enable_categorization'] = true;
    $table['zselex_shop_owners_primary_key_column'] = 'id';
    /*     * ***************************************************************************************** */

/////////////////////zselex_shop_admins////////////////////////////////////////////////
    $table['zselex_shop_admins'] = DBUtil::getLimitedTablename('zselex_shop_admins');
    $table['zselex_shop_admins_column'] = array(
        'admin_id' => 'admin_id',
        'shop_id' => 'shop_id',
        'user_id' => 'user_id',
        'owner_id' => 'owner_id',
    );

    $table['zselex_shop_admins_column_def'] = array(
        'admin_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'shop_id' => 'I DEFAULT 0',
        'user_id' => 'I DEFAULT 0',
        'owner_id' => 'I DEFAULT 0',
    );

    $table['zselex_shop_admins_db_extra_enable_categorization'] = true;
    $table['zselex_shop_admins_primary_key_column'] = 'admin_id';
    // add standard data fields
    //ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_dotd_column'], '');
    //ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_dotd_column_def']);
    /*     * ***************************************************************************************** */

/////////////////////zselex_zenshop////////////////////////////////////////////////
    $table['zselex_zenshop'] = DBUtil::getLimitedTablename('zselex_zenshop');
    $table['zselex_zenshop_column'] = array(
        'zen_id' => 'zen_id',
        'minishop_id' => 'minishop_id',
        'shop_id' => 'shop_id',
        'domain' => 'domain',
        'hostname' => 'hostname',
        'dbname' => 'dbname',
        'username' => 'username',
        'password' => 'password',
        'table_prefix' => 'table_prefix',
    );

    $table['zselex_zenshop_column_def'] = array(
        'zen_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'minishop_id' => 'I DEFAULT 0',
        'shop_id' => 'I DEFAULT 0',
        'domain' => 'C(50) DEFAULT NULL',
        'hostname' => 'C(50) DEFAULT NULL',
        'dbname' => 'C(50) DEFAULT NULL',
        'username' => 'C(50) DEFAULT NULL',
        'password' => 'C(50) DEFAULT NULL',
        'table_prefix' => 'C(50) DEFAULT NULL',
    );

    $table['zselex_zenshop_db_extra_enable_categorization'] = true;
    $table['zselex_zenshop_primary_key_column'] = 'zen_id';
    //add standard data fields
    //ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_zenshop_column'], '');
    //ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_zenshop_column_def']);
    /*     * ***************************************************************************************** */

/////////////////////zselex_minishop////////////////////////////////////////////////
    $table['zselex_minishop'] = DBUtil::getLimitedTablename('zselex_minishop');
    $table['zselex_minishop_column'] = array(
        'id' => 'id',
        'shop_id' => 'shop_id',
        'shoptype_id' => 'shoptype_id',
        'shoptype' => 'shoptype',
        'minishop_name' => 'minishop_name',
        'description' => 'description',
        'configured' => 'configured',
    );

    $table['zselex_minishop_column_def'] = array(
        'id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'shop_id' => 'I DEFAULT 0',
        'shoptype_id' => 'I DEFAULT 0',
        'shoptype' => 'C(50) DEFAULT NULL',
        'minishop_name' => 'C(50) DEFAULT NULL',
        'description' => 'XL NOTNULL',
        'configured' => 'I1 NOTNULL DEFAULT 0',
    );

    $table['zselex_minishop_db_extra_enable_categorization'] = true;
    $table['zselex_minishop_primary_key_column'] = 'id';
    //add standard data fields
    //ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_zenshop_column'], '');
    //ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_zenshop_column_def']);
    /*     * ***************************************************************************************** */

    /////////////////////zselex_shop_settings////////////////////////////////////////////////
    $table['zselex_shop_settings'] = DBUtil::getLimitedTablename('zselex_shop_settings');
    $table['zselex_shop_settings_column'] = array(
        'idSettings' => 'idSettings',
        'shop_id' => 'shop_id',
        'default_img_frm' => 'default_img_frm',
        'main' => 'main',
    );

    $table['zselex_shop_settings_column_def'] = array(
        'idSettings' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'shop_id' => 'I DEFAULT 0',
        'default_img_frm' => 'C(50) DEFAULT NULL',
        'main' => 'I1 NOTNULL DEFAULT 0',
    );

    $table['zselex_shop_settings_db_extra_enable_categorization'] = true;
    $table['zselex_shop_settings_primary_key_column'] = 'idSettings';
    //add standard data fields
    //ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_zenshop_column'], '');
    //ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_zenshop_column_def']);
    /*     * ***************************************************************************************** */

    /////////////////////zselex_area////////////////////////////////////////////////
    $table['zselex_area'] = DBUtil::getLimitedTablename('zselex_area');
    $table['zselex_area_column'] = array(
        'area_id' => 'area_id',
        'area_name' => 'area_name',
        'city_id' => 'city_id',
        'region_id' => 'region_id',
        'country_id' => 'country_id',
    );

    $table['zselex_area_column_def'] = array(
        'area_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'area_name' => 'C(100) DEFAULT NULL',
        'city_id' => 'I DEFAULT 0',
        'region_id' => 'I DEFAULT 0',
        'country_id' => 'I DEFAULT 0',
    );

    $table['zselex_area_db_extra_enable_categorization'] = true;
    $table['zselex_area_primary_key_column'] = 'area_id';
    //add standard data fields
    //ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_zenshop_column'], '');
    //ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_zenshop_column_def']);
    /*     * ***************************************************************************************** */

    /////////////////////zselex_shop_owners_theme////////////////////////////////////////////////
    $table['zselex_shop_owners_theme'] = DBUtil::getLimitedTablename('zselex_shop_owners_theme');
    $table['zselex_shop_owners_theme_column'] = array(
        'id' => 'id',
        'theme_id' => 'theme_id',
        'theme_name' => 'theme_name',
        'user_id' => 'user_id',
        'shop_id' => 'shop_id',
    );

    $table['zselex_shop_owners_theme_column_def'] = array(
        'id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'theme_id' => 'I DEFAULT 0',
        'theme_name' => 'C(100) DEFAULT NULL',
        'user_id' => 'I DEFAULT 0',
        'shop_id' => 'I DEFAULT 0',
    );

    $table['zselex_shop_owners_theme_db_extra_enable_categorization'] = true;
    $table['zselex_shop_owners_theme_primary_key_column'] = 'id';
    //add standard data fields
    //ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_zenshop_column'], '');
    //ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_zenshop_column_def']);
    /*     * ***************************************************************************************** */

    /////////////////////zselex_shop_pdf////////////////////////////////////////////////
    $table['zselex_shop_pdf'] = DBUtil::getLimitedTablename('zselex_shop_pdf');
    $table['zselex_shop_pdf_column'] = array(
        'pdf_id' => 'pdf_id',
        'pdf_name' => 'pdf_name',
        'pdf_image' => 'pdf_image',
        'shop_id' => 'shop_id',
        'user_id' => 'user_id',
        'pdf_description' => 'pdf_description',
        'keywords' => 'keywords',
        'defaultImg' => 'defaultImg',
    );

    $table['zselex_shop_pdf_column_def'] = array(
        'pdf_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'pdf_name' => 'C(100) DEFAULT NULL',
        'pdf_image' => 'C(100) DEFAULT NULL',
        'shop_id' => 'I DEFAULT 0',
        'user_id' => 'I DEFAULT 0',
        'pdf_description' => 'C(100) DEFAULT NULL',
        'keywords' => 'C(100) DEFAULT NULL',
        'defaultImg' => 'I1 NOTNULL DEFAULT 0',
    );

    $table['zselex_shop_pdf_db_extra_enable_categorization'] = true;
    $table['zselex_shop_pdf_primary_key_column'] = 'pdf_id';
    //add standard data fields
    ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_shop_pdf_column'], '');
    ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_shop_pdf_column_def']);
    /*     * ***************************************************************************************** */

    /////////////////////zselex_paypal////////////////////////////////////////////////
    $table['zselex_paypal'] = DBUtil::getLimitedTablename('zselex_paypal');
    $table['zselex_paypal_column'] = array(
        'id' => 'id',
        'paypal_email' => 'paypal_email',
        'user_id' => 'user_id',
        'shop_id' => 'shop_id',
    );

    $table['zselex_paypal_column_def'] = array(
        'id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'paypal_email' => 'C(100) DEFAULT NULL',
        'user_id' => 'I DEFAULT 0',
        'shop_id' => 'I DEFAULT 0',
    );

    $table['zselex_paypal_db_extra_enable_categorization'] = true;
    $table['zselex_paypal_primary_key_column'] = 'id';
    //add standard data fields
    //ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_zenshop_column'], '');
    //ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_zenshop_column_def']);
    /*     * ***************************************************************************************** */

    /////////////////////zselex_themes////////////////////////////////////////////////
    $table['zselex_themes'] = DBUtil::getLimitedTablename('zselex_themes');
    $table['zselex_themes_column'] = array(
        'zt_id' => 'zt_id',
        'theme_id' => 'theme_id',
        'theme_name' => 'theme_name',
    );

    $table['zselex_themes_column_def'] = array(
        'zt_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'theme_id' => 'I DEFAULT 0',
        'theme_name' => 'C(100) DEFAULT NULL',
    );

    $table['zselex_themes_db_extra_enable_categorization'] = true;
    $table['zselex_themes_primary_key_column'] = 'zt_id';
    //add standard data fields
    //ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_zenshop_column'], '');
    //ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_zenshop_column_def']);
    /*     * ***************************************************************************************** */

    /////////////////////zselex_themes////////////////////////////////////////////////
    $table['zselex_article_ads'] = DBUtil::getLimitedTablename('zselex_article_ads');
    $table['zselex_article_ads_column'] = array(
        'articlead_id' => 'articlead_id',
        'name' => 'name',
        'shop_id' => 'shop_id',
        'level' => 'level',
        'country_id' => 'country_id',
        'region_id' => 'region_id',
        'city_id' => 'city_id',
        'area_id' => 'area_id',
        'keywords' => 'keywords',
        'start_date' => 'start_date',
        'end_date' => 'end_date',
        'status' => 'status',
    );

    $table['zselex_article_ads_column_def'] = array(
        'articlead_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'name' => 'C(100) DEFAULT NULL',
        'shop_id' => 'I DEFAULT 0',
        'level' => 'C(100) DEFAULT NULL',
        'country_id' => 'I DEFAULT 0',
        'region_id' => 'I DEFAULT 0',
        'city_id' => 'I DEFAULT 0',
        'area_id' => 'I DEFAULT 0',
        'keywords' => 'XL NOTNULL',
        'start_date' => 'T DEFAULT NULL',
        'end_date' => 'T DEFAULT NULL',
        'status' => 'I1 NOTNULL DEFAULT 0',
    );

    $table['zselex_article_ads_db_extra_enable_categorization'] = true;
    $table['zselex_article_ads_primary_key_column'] = 'articlead_id';
    //add standard data fields
    ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_article_ads_column'], '');
    ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_article_ads_column_def']);
    /*     * ***************************************************************************************** */

    /////////////////////zselex_shop_events////////////////////////////////////////////////
    $table['zselex_shop_events'] = DBUtil::getLimitedTablename('zselex_shop_events');
    $table['zselex_shop_events_column'] = array(
        'shop_event_id' => 'shop_event_id',
        'shop_id' => 'shop_id',
        'shop_event_name' => 'shop_event_name',
        'shop_event_shortdescription' => 'shop_event_shortdescription',
        'shop_event_description' => 'shop_event_description',
        'shop_event_keywords' => 'shop_event_keywords',
        'news_article_id' => 'news_article_id',
        'shop_event_startdate' => 'shop_event_startdate',
        'shop_event_starthour' => 'shop_event_starthour',
        'shop_event_startminute' => 'shop_event_startminute',
        'shop_event_enddate' => 'shop_event_enddate',
        'shop_event_endhour' => 'shop_event_endhour',
        'shop_event_endminute' => 'shop_event_endminute',
        'activation_date' => 'activation_date',
        'event_image' => 'event_image',
        'image_height' => 'image_height',
        'image_width' => 'image_width',
        'event_doc' => 'event_doc',
        'product_id' => 'product_id',
        'showfrom' => 'showfrom',
        'price' => 'price',
        'email' => 'email',
        'phone' => 'phone',
        'shop_event_venue' => 'shop_event_venue',
        'exclusive' => 'exclusive',
        'status' => 'status',
    );

    $table['zselex_shop_events_column_def'] = array(
        'shop_event_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'shop_id' => 'I DEFAULT 0',
        'shop_event_name' => 'C(100) DEFAULT NULL',
        'shop_event_shortdescription' => 'XL NOTNULL',
        'shop_event_description' => 'XL NOTNULL',
        'shop_event_keywords' => 'XL NOTNULL',
        'news_article_id' => 'I DEFAULT 0',
        'shop_event_startdate' => 'T DEFAULT NULL',
        'shop_event_starthour' => 'C(100) DEFAULT NULL',
        'shop_event_startminute' => 'C(100) DEFAULT NULL',
        'shop_event_enddate' => 'T DEFAULT NULL',
        'shop_event_endhour' => 'C(100) DEFAULT NULL',
        'shop_event_endminute' => 'C(100) DEFAULT NULL',
        'activation_date' => 'T DEFAULT NULL',
        'event_image' => 'XL NOTNULL',
        'image_height' => 'C(50) DEFAULT NULL',
        'image_width' => 'C(50) DEFAULT NULL',
        'event_doc' => 'C(100) DEFAULT NULL',
        'product_id' => 'I DEFAULT 0',
        'showfrom' => 'C(100) DEFAULT NULL',
        'price' => 'C(100) DEFAULT NULL',
        'email' => 'C(100) DEFAULT NULL',
        'phone' => 'I DEFAULT 0',
        'shop_event_venue' => 'XL NOTNULL',
        'exclusive' => 'I1 NOTNULL DEFAULT 0',
        'status' => 'I1 NOTNULL DEFAULT 0',
    );

    $table['zselex_shop_events_db_extra_enable_categorization'] = true;
    $table['zselex_shop_events_primary_key_column'] = 'shop_event_id';
    //add standard data fields
    ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_shop_events_column'], '');
    ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_shop_events_column_def']);
    /*     * ***************************************************************************************** */

    /////////////////////zselex_shop_events_files////////////////////////////////////////////////
    $table['zselex_shop_event_files'] = DBUtil::getLimitedTablename('zselex_shop_event_files');
    $table['zselex_shop_event_files_column'] = array(
        'id' => 'id',
        'shop_event_id' => 'shop_event_id',
        'file_name' => 'file_name',
        'type' => 'type',
    );

    $table['zselex_shop_event_files_column_def'] = array(
        'id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'shop_event_id' => 'I DEFAULT 0',
        'file_name' => 'C(100) DEFAULT NULL',
        'type' => 'C(100) DEFAULT NULL',
    );

    $table['zselex_shop_event_files_db_extra_enable_categorization'] = true;
    $table['zselex_shop_event_files_primary_key_column'] = 'id';
    //add standard data fields
    //ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_shop_event_files_column'], '');
    // ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_shop_event_files_column_def']);
    /*     * ***************************************************************************************** */

    /////////////////////zselex_keywords////////////////////////////////////////////////
    $table['zselex_keywords'] = DBUtil::getLimitedTablename('zselex_keywords');
    $table['zselex_keywords_column'] = array(
        'keyword_id' => 'keyword_id',
        'keyword' => 'keyword',
        'type' => 'type',
        'type_id' => 'type_id',
        'shop_id' => 'shop_id',
    );

    $table['zselex_keywords_column_def'] = array(
        'keyword_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'keyword' => 'XL NOTNULL',
        'type' => 'C(100) DEFAULT NULL',
        'type_id' => 'I DEFAULT 0',
        'shop_id' => 'I DEFAULT 0',
    );

    $table['zselex_keywords_db_extra_enable_categorization'] = true;
    $table['zselex_keywords_primary_key_column'] = 'keyword_id';
    //add standard data fields
    //ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_shop_event_files_column'], '');
    // ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_shop_event_files_column_def']);
    /*     * ***************************************************************************************** */

    /////////////////////zselex_service_identifiers////////////////////////////////////////////////
    $table['zselex_service_identifiers'] = DBUtil::getLimitedTablename('zselex_service_identifiers');
    $table['zselex_service_identifiers_column'] = array(
        'id' => 'id',
        'name' => 'name',
        'identifier' => 'identifier',
        'description' => 'description',
        'status' => 'status',
    );

    $table['zselex_service_identifiers_column_def'] = array(
        'id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'name' => 'C(100) DEFAULT NULL',
        'identifier' => 'C(100) DEFAULT NULL',
        'description' => 'XL NOTNULL',
        'status' => 'I1 NOTNULL DEFAULT 0',
    );

    $table['zselex_service_identifiers_db_extra_enable_categorization'] = true;
    $table['zselex_service_identifiers_primary_key_column'] = 'id';
    //add standard data fields
    //ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_service_identifiers_column'], '');
    // ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_service_identifiers_column_def']);
    /*     * ***************************************************************************************** */

    /////////////////////zselex_service_bundles////////////////////////////////////////////////
    $table['zselex_service_bundles'] = DBUtil::getLimitedTablename('zselex_service_bundles');
    $table['zselex_service_bundles_column'] = array(
        'bundle_id' => 'bundle_id',
        'bundle_name' => 'bundle_name',
        'type' => 'type',
        'bundle_price' => 'bundle_price',
        'calculated_price' => 'calculated_price',
        'bundle_description' => 'bundle_description',
        'content' => 'content',
        'bundle_type' => 'bundle_type',
        'demo' => 'demo',
        'demoperiod' => 'demoperiod',
        'sort_order' => 'sort_order',
        'status' => 'status',
    );

    $table['zselex_service_bundles_column_def'] = array(
        'bundle_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'bundle_name' => 'C(100) DEFAULT NULL',
        'type' => 'C(100) DEFAULT NULL',
        'bundle_price' => 'C(100) DEFAULT NULL',
        'calculated_price' => 'C(100) DEFAULT NULL',
        'bundle_description' => 'XL NOTNULL',
        'content' => 'XL NOTNULL',
        'bundle_type' => 'C(50) DEFAULT NULL',
        'demo' => 'I1 NOTNULL DEFAULT 0',
        'demoperiod' => 'I DEFAULT 0',
        'sort_order' => 'I DEFAULT 0',
        'status' => 'I1 NOTNULL DEFAULT 0',
    );

    $table['zselex_service_bundles_db_extra_enable_categorization'] = true;
    $table['zselex_service_bundles_primary_key_column'] = 'bundle_id';
    //add standard data fields
    //ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_service_bundles_column'], '');
    // ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_service_bundles_column_def']);
    /*     * ***************************************************************************************** */

    /////////////////////zselex_service_bundle_items////////////////////////////////////////////////
    $table['zselex_service_bundle_items'] = DBUtil::getLimitedTablename('zselex_service_bundle_items');
    $table['zselex_service_bundle_items_column'] = array(
        'id' => 'id',
        'bundle_id' => 'bundle_id',
        'service_name' => 'service_name',
        'servicetype' => 'servicetype',
        'plugin_id' => 'plugin_id',
        'price' => 'price',
        'qty' => 'qty',
        'qty_based' => 'qty_based',
    );

    $table['zselex_service_bundle_items_column_def'] = array(
        'id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'bundle_id' => 'I DEFAULT 0',
        'service_name' => 'C(100) DEFAULT NULL',
        'servicetype' => 'C(100) DEFAULT NULL',
        'plugin_id' => 'I DEFAULT 0',
        'price' => 'C(100) DEFAULT NULL',
        'qty' => 'I DEFAULT 0',
        'qty_based' => 'I1 NOTNULL DEFAULT 0',
    );

    $table['zselex_service_bundle_items_db_extra_enable_categorization'] = true;
    $table['zzselex_service_bundle_items_primary_key_column'] = 'id';
    //add standard data fields
    //ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_service_bundle_items_column'], '');
    // ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_service_bundle_items_column_def']);
    /*     * ***************************************************************************************** */

    /////////////////////zselex_service_dependies////////////////////////////////////////////////
    $table['zselex_service_dependies'] = DBUtil::getLimitedTablename('zselex_service_dependies');
    $table['zselex_service_dependies_column'] = array(
        'depend_id' => 'depend_id',
        'service_id' => 'service_id',
        'depend_service_id' => 'depend_service_id',
        'service_type' => 'service_type',
    );

    $table['zselex_service_dependies_column_def'] = array(
        'depend_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'service_id' => 'I DEFAULT 0',
        'depend_service_id' => 'I DEFAULT 0',
        'service_type' => 'C(100) DEFAULT NULL',
    );

    $table['zselex_service_dependies_db_extra_enable_categorization'] = true;
    $table['zselex_service_dependies_primary_key_column'] = 'depend_id';
    //add standard data fields
    //ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_service_bundles_column'], '');
    // ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_service_bundles_column_def']);
    /*     * ***************************************************************************************** */

    /////////////////////zselex_ministe_updates////////////////////////////////////////////////
    $table['zselex_ministe_updates'] = DBUtil::getLimitedTablename('zselex_ministe_updates');
    $table['zselex_ministe_updates_column'] = array(
        'id' => 'id',
        'shop_id' => 'shop_id',
        'owner_id' => 'owner_id',
        'update_date' => 'update_date',
        'is_updated_recent' => 'is_updated_recent',
    );

    $table['zselex_ministe_updates_column_def'] = array(
        'id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'shop_id' => 'I DEFAULT 0',
        'owner_id' => 'I DEFAULT 0',
        'update_date' => 'C(100) DEFAULT NULL',
        'is_updated_recent' => 'I1 NOTNULL DEFAULT 0',
    );

    $table['zselex_ministe_updates_db_extra_enable_categorization'] = true;
    $table['zselex_ministe_updates_primary_key_column'] = 'id';
    //add standard data fields
    //ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_service_bundles_column'], '');
    // ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_service_bundles_column_def']);
    /*     * ***************************************************************************************** */

    /////////////////////zselex_shop_ratings////////////////////////////////////////////////
    $table['zselex_shop_ratings'] = DBUtil::getLimitedTablename('zselex_shop_ratings');
    $table['zselex_shop_ratings_column'] = array(
        'rating_id' => 'rating_id',
        'shop_id' => 'shop_id',
        'rating' => 'rating',
        'user_id' => 'user_id',
        'dateposted' => 'dateposted',
        'timestamp' => 'timestamp',
    );
    $table['zselex_shop_ratings_column_def'] = array(
        'rating_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'shop_id' => 'I DEFAULT 0',
        'rating' => 'I DEFAULT 0',
        'user_id' => 'I DEFAULT 0',
        'dateposted' => 'C(100) DEFAULT NULL',
        'timestamp' => 'I DEFAULT 0',
    );
    $table['zselex_shop_ratings_db_extra_enable_categorization'] = true;
    $table['zselex_shop_ratings_primary_key_column'] = 'rating_id';
    //add standard data fields
    //ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_service_bundles_column'], '');
    //ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_service_bundles_column_def']);
    /*     * ***************************************************************************************** */

    /////////////////////zselex_shop_banner////////////////////////////////////////////////
    $table['zselex_shop_banner'] = DBUtil::getLimitedTablename('zselex_shop_banner');
    $table['zselex_shop_banner_column'] = array(
        'shop_banner_id' => 'shop_banner_id',
        'shop_id' => 'shop_id',
        'banner_image' => 'banner_image',
        'status' => 'status',
    );
    $table['zselex_shop_banner_column_def'] = array(
        'shop_banner_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'shop_id' => 'I DEFAULT 0',
        'banner_image' => 'C(100) DEFAULT NULL',
        'status' => 'I DEFAULT 0',
    );
    $table['zselex_shop_banner_db_extra_enable_categorization'] = true;
    $table['zselex_shop_banner_primary_key_column'] = 'shop_banner_id';
    //add standard data fields
    ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_shop_banner_column'], '');
    ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_shop_banner_column_def']);
    /*     * ***************************************************************************************** */

    /////////////////////zselex_shop_announcement////////////////////////////////////////////////
    $table['zselex_shop_announcement'] = DBUtil::getLimitedTablename('zselex_shop_announcement');
    $table['zselex_shop_announcement_column'] = array(
        'ann_id' => 'ann_id',
        'shop_id' => 'shop_id',
        'text' => 'text',
        'start_date' => 'start_date',
        'end_date' => 'end_date',
        'status' => 'status',
    );
    $table['zselex_shop_announcement_column_def'] = array(
        'ann_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'shop_id' => 'I DEFAULT 0',
        'text' => 'C(100) DEFAULT NULL',
        'start_date' => 'T DEFAULT NULL',
        'end_date' => 'T DEFAULT NULL',
        'status' => 'I DEFAULT 0',
    );
    $table['zselex_shop_announcement_db_extra_enable_categorization'] = true;
    $table['zselex_shop_announcement_primary_key_column'] = 'ann_id';
    //add standard data fields
    //ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_shop_announcement_column'], '');
    //ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_shop_announcement_column_def']);
    /*     * ***************************************************************************************** */

    /////////////////////zselex_shop_employees////////////////////////////////////////////////
    $table['zselex_shop_employees'] = DBUtil::getLimitedTablename('zselex_shop_employees');
    $table['zselex_shop_employees_column'] = array(
        'emp_id' => 'emp_id',
        'shop_id' => 'shop_id',
        'name' => 'name',
        'phone' => 'phone',
        'cell' => 'cell',
        'email' => 'email',
        'job' => 'job',
        'emp_image' => 'emp_image',
        'status' => 'status',
    );
    $table['zselex_shop_employees_column_def'] = array(
        'emp_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'shop_id' => 'I DEFAULT 0',
        'name' => 'C(100) DEFAULT NULL',
        'phone' => 'I DEFAULT 0',
        'cell' => 'I DEFAULT 0',
        'email' => 'C(100) DEFAULT NULL',
        'job' => 'XL NOTNULL',
        'emp_image' => 'C(250) DEFAULT NULL',
        'status' => 'I1 NOTNULL DEFAULT 0',
    );
    //$table['zselex_shop_employees_db_extra_enable_categorization'] = true;
    $table['zselex_shop_employees_primary_key_column'] = 'emp_id';
    //add standard data fields
    ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_shop_employees_column'], '');
    ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_shop_employees_column_def']);
    /*     * ***************************************************************************************** */

    /////////////////////zselex_serviceshop_bundles////////////////////////////////////////////////
    $table['zselex_serviceshop_bundles'] = DBUtil::getLimitedTablename('zselex_serviceshop_bundles');
    $table['zselex_serviceshop_bundles_column'] = array(
        'service_bundle_id' => 'service_bundle_id',
        'bundle_id' => 'bundle_id',
        'bundle_name' => 'bundle_name',
        'shop_id' => 'shop_id',
        'original_quantity' => 'original_quantity',
        'quantity' => 'quantity',
        'service_status' => 'service_status',
        'bundle_type' => 'bundle_type',
        'timer_date' => 'timer_date',
        'timer_days' => 'timer_days',
    );
    $table['zselex_serviceshop_bundles_column_def'] = array(
        'service_bundle_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'bundle_id' => 'I DEFAULT 0',
        'bundle_name' => 'C(250) DEFAULT NULL',
        'shop_id' => 'I DEFAULT 0',
        'original_quantity' => 'I DEFAULT 0',
        'quantity' => 'I DEFAULT 0',
        'service_status' => 'I1 NOTNULL DEFAULT 0',
        'bundle_type' => 'C(100) DEFAULT NULL',
        'timer_date' => 'T DEFAULT NULL',
        'timer_days' => 'I DEFAULT 0',
    );
    $table['zselex_serviceshop_bundles_db_extra_enable_categorization'] = true;
    $table['zselex_serviceshop_bundles_primary_key_column'] = 'service_bundle_id';
    //add standard data fields
    ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_serviceshop_bundles_column'], '');
    ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_serviceshop_bundles_column_def']);
    /*     * ***************************************************************************************** */

    /////////////////////zselex_serviceshop_bundles////////////////////////////////////////////////
    $table['zselex_shop_affiliation'] = DBUtil::getLimitedTablename('zselex_shop_affiliation');
    $table['zselex_shop_affiliation_column'] = array(
        'aff_id' => 'aff_id',
        'aff_name' => 'aff_name',
        'aff_image' => 'aff_image',
    );
    $table['zselex_shop_affiliation_column_def'] = array(
        'aff_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'aff_name' => 'C(50) DEFAULT NULL',
        'aff_image' => 'C(100) DEFAULT NULL',
    );
    $table['zselex_shop_affiliation_db_extra_enable_categorization'] = true;
    $table['zselex_shop_affiliation_primary_key_column'] = 'aff_id';
    //add standard data fields
    ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_shop_affiliation_column'], '');
    ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_shop_affiliation_column_def']);
    /*     * ***************************************************************************************** */

    /////////////////////zselex_cart////////////////////////////////////////////////
    $table['zselex_cart'] = DBUtil::getLimitedTablename('zselex_cart');
    $table['zselex_cart_column'] = array(
        'cart_id' => 'cart_id',
        'user_id' => 'user_id',
        'product_id' => 'product_id',
        'quantity' => 'quantity',
        'shop_id' => 'shop_id',
        'original_price' => 'original_price',
        'options_price' => 'options_price',
        'final_price' => 'final_price',
        'cart_content' => 'cart_content',
        'outofstock' => 'outofstock',
            //'cart_total' => 'cart_total',
    );
    $table['zselex_cart_column_def'] = array(
        'cart_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'user_id' => 'I DEFAULT 0',
        'product_id' => 'I DEFAULT 0',
        'quantity' => 'I DEFAULT 0',
        'shop_id' => 'I DEFAULT 0',
        'original_price' => 'DECIMAL(15,4)  NOT NULL',
        'options_price' => 'DECIMAL(15,4)  NOT NULL',
        'final_price' => 'DECIMAL(15,4)  NOT NULL',
        'cart_content' => 'XL NOTNULL',
        'outofstock' => 'I DEFAULT 0',
            // 'cart_total' => 'I DEFAULT 0',
    );
    //$table['zselex_cart_db_extra_enable_categorization'] = true;
    $table['zselex_cart_primary_key_column'] = 'cart_id';
    //add standard data fields
    // ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_cart_column'], '');
    // ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_cart_column_def']);
    /*     * ***************************************************************************************** */

    /////////////////////zselex_product_categories////////////////////////////////////////////////
    $table['zselex_product_categories'] = DBUtil::getLimitedTablename('zselex_product_categories');
    $table['zselex_product_categories_column'] = array(
        'prd_cat_id' => 'prd_cat_id',
        'prd_cat_name' => 'prd_cat_name',
        'shop_id' => 'shop_id',
        'user_id' => 'user_id',
        'status' => 'status',
    );
    $table['zselex_product_categories_column_def'] = array(
        'prd_cat_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'prd_cat_name' => 'C(100) DEFAULT NULL',
        'shop_id' => 'I DEFAULT 0',
        'user_id' => 'I DEFAULT 0',
        'status' => 'I DEFAULT 0',
    );
    //$table['zselex_cart_db_extra_enable_categorization'] = true;
    $table['zselex_product_categories_primary_key_column'] = 'prd_cat_id';
    //add standard data fields
    // ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_cart_column'], '');
    // ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_cart_column_def']);
    /*     * ***************************************************************************************** */

    $table['zselex_shop_a_settings'] = DBUtil::getLimitedTablename('zselex_shop_a_settings');
    $table['zselex_shop_a_settings_column'] = array(
        'id' => 'id',
        'shop_id' => 'shop_id',
        'default_img_frm' => 'default_img_frm',
        'main' => 'main',
        'theme' => 'theme',
        'opening_hours' => 'opening_hours',
        'no_payment' => 'no_payment',
        'link_to_homepage' => 'link_to_homepage',
        'terms_conditions' => 'terms_conditions',
    );
    $table['zselex_shop_a_settings_column_def'] = array(
        'id' => 'I NOTNULL AUTO PRIMARY',
        'shop_id' => 'I DEFAULT 0', // int(11) unsigned NOT NULL auto_increment
        'default_img_frm' => 'C(100) DEFAULT NULL',
        'main' => 'I DEFAULT 0',
        'theme' => 'C(100) DEFAULT NULL',
        'opening_hours' => 'XL NOTNULL',
        'no_payment' => 'I DEFAULT 0',
        'link_to_homepage' => 'C(100) DEFAULT NULL',
        'terms_conditions' => 'XL NOTNULL',
    );
    //$table['zselex_cart_db_extra_enable_categorization'] = true;
    $table['zselex_shop_a_settings_primary_key_column'] = 'shop_id';

    /////////////////////zselex_manufacturer////////////////////////////////////////////////
    $table['zselex_manufacturer'] = DBUtil::getLimitedTablename('zselex_manufacturer');
    $table['zselex_manufacturer_column'] = array(
        'manufacturer_id' => 'manufacturer_id',
        'manufacturer_name' => 'manufacturer_name',
        'shop_id' => 'shop_id',
        'description' => 'XL NOTNULL',
        'status' => 'status',
    );
    $table['zselex_manufacturer_column_def'] = array(
        'manufacturer_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'manufacturer_name' => 'C(100) DEFAULT NULL',
        'shop_id' => 'I DEFAULT 0',
        'description' => 'description',
        'status' => 'I DEFAULT 0',
    );
    //$table['zselex_cart_db_extra_enable_categorization'] = true;
    $table['zselex_manufacturer_primary_key_column'] = 'manufacturer_id';
    //add standard data fields
    ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_manufacturer_column'], '');
    ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_manufacturer_column_def']);
    /*     * ***************************************************************************************** */

    /////////////////////zselex_product_to_category////////////////////////////////////////////////
    $table['zselex_product_to_category'] = DBUtil::getLimitedTablename('zselex_product_to_category');
    $table['zselex_product_to_category_column'] = array(
        'product_id' => 'product_id',
        'category_id' => 'category_id',
    );
    $table['zselex_product_to_category_column_def'] = array(
        'product_id' => 'I DEFAULT 0 PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'category_id' => 'I DEFAULT 0 PRIMARY',
    );
    //$table['zselex_cart_db_extra_enable_categorization'] = true;
    //$table['zselex_product_to_category_primary_key_column'] = 'product_id';
/////////////////////zselex_shop_to_category////////////////////////////////////////////////
    $table['zselex_shop_to_category'] = DBUtil::getLimitedTablename('zselex_shop_to_category');
    $table['zselex_shop_to_category_column'] = array(
        'shop_id' => 'shop_id',
        'category_id' => 'category_id',
    );
    $table['zselex_shop_to_category_column_def'] = array(
        'shop_id' => 'I DEFAULT 0 PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'category_id' => 'I DEFAULT 0 PRIMARY',
    );
    //$table['zselex_cart_db_extra_enable_categorization'] = true;
    //$table['zselex_product_to_category_primary_key_column'] = 'product_id';
    /////////////////////zselex_product_options////////////////////////////////////////////////
    $table['zselex_product_options'] = DBUtil::getLimitedTablename('zselex_product_options');
    $table['zselex_product_options_column'] = array(
        'option_id' => 'option_id',
        'shop_id' => 'shop_id',
        'option_name' => 'option_name',
        'option_type' => 'option_type',
        'option_value' => 'option_value',
        'parent_option_id' => 'parent_option_id',
        'sort_order' => 'sort_order',
    );
    $table['zselex_product_options_column_def'] = array(
        'option_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'shop_id' => 'I DEFAULT 0',
        'option_name' => 'C(250) DEFAULT NULL',
        'option_type' => 'C(250) DEFAULT NULL',
        'option_value' => 'XL NOTNULL',
        'parent_option_id' => 'I DEFAULT 0',
        'sort_order' => 'I DEFAULT 0',
    );
    //$table['zselex_cart_db_extra_enable_categorization'] = true;
    $table['zselex_product_options_primary_key_column'] = 'option_id';
    //add standard data fields
    // ZSELEX_Util::addStandardFieldsToTableDefinition($table['zselex_manufacturer_column'], '');
    // ZSELEX_Util::addStandardFieldsToTableDataDefinition($table['zselex_manufacturer_column_def']);
    /*     * ***************************************************************************************** */

    /////////////////////zselex_product_options_values////////////////////////////////////////////////
    $table['zselex_product_options_values'] = DBUtil::getLimitedTablename('zselex_product_options_values');
    $table['zselex_product_options_values_column'] = array(
        'option_value_id' => 'option_value_id',
        'option_id' => 'option_id',
        'shop_id' => 'shop_id',
        'option_value' => 'option_value',
        'sort_order' => 'sort_order',
    );
    $table['zselex_product_options_values_column_def'] = array(
        'option_value_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'option_id' => 'I DEFAULT 0', // int(11) unsigned NOT NULL auto_increment
        'shop_id' => 'I DEFAULT 0',
        'option_value' => 'C(250) DEFAULT NULL',
        'sort_order' => 'I DEFAULT 0',
    );
    $table['zselex_product_options_values_column_key_column'] = 'option_value_id';
    ////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////zselex_product_to_options////////////////////////////////////////////////
    $table['zselex_product_to_options'] = DBUtil::getLimitedTablename('zselex_product_to_options');
    $table['zselex_product_to_options_column'] = array(
        'product_to_options_id' => 'product_to_options_id',
        'product_id' => 'product_id',
        'option_id' => 'option_id',
        'parent_option_id' => 'parent_option_id',
        'option_values' => 'option_values',
    );
    $table['zselex_product_to_options_column_def'] = array(
        'product_to_options_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'product_id' => 'I DEFAULT 0', // int(11) unsigned NOT NULL auto_increment
        'option_id' => 'I DEFAULT 0',
        'parent_option_id' => 'I DEFAULT 0',
        'option_values' => 'XL NOTNULL',
    );
    $table['zselex_product_to_options_column_key_column'] = 'product_to_options_id';
    ////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////zselex_product_to_options_values////////////////////////////////////////////////
    $table['zselex_product_to_options_values'] = DBUtil::getLimitedTablename('zselex_product_to_options_values');
    $table['zselex_product_to_options_values_column'] = array(
        'product_to_options_value_id' => 'product_to_options_value_id',
        'product_to_options_id' => 'product_to_options_id',
        'product_id' => 'product_id',
        'option_id' => 'option_id',
        'parent_option_id' => 'parent_option_id',
        'option_value_id' => 'option_value_id',
        'parent_option_value_id' => 'parent_option_value_id',
        // 'option_value' => 'option_value',
        'price' => 'price',
        'qty' => 'qty',
            //  'sort_order' => 'sort_order'
    );
    $table['zselex_product_to_options_values_column_def'] = array(
        'product_to_options_value_id' => 'I NOTNULL AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'product_to_options_id' => 'I DEFAULT 0',
        'product_id' => 'I DEFAULT 0',
        'option_id' => 'I DEFAULT 0',
        'parent_option_id' => 'I DEFAULT 0',
        'option_value_id' => 'I DEFAULT 0',
        'parent_option_value_id' => 'I DEFAULT 0',
        // 'option_value' => 'C(250) DEFAULT NULL',
        'price' => 'DECIMAL(10,2)  NOT NULL',
        'qty' => 'I DEFAULT 0',
            //'sort_order' => 'I DEFAULT 0'
    );
    $table['zselex_product_to_options_values_key_column'] = 'product_to_options_value_id';
    ////////////////////////////////////////////////////////////////////////////////////////////
    return $table;


  /// dev test. again..
}

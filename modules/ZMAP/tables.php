<?php

/**
 * Copyright ACTA-IT 2013 - ZMAP
 *
 * ZMAP
 * Zikula Module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * @function    zmap_tables
 * @params
 * @return      array table defs
 */
function zmap_tables() {
    // Initialise table array
    $table = array();

    $table['zmap'] = DBUtil::getLimitedTablename('zmap');

    $table['zmap_column'] = array(
        'bid' => 'bid',
        'cid' => 'cid');

    $table['zmap_column_def'] = array(
        'bid' => 'I(11) UNSIGNED AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'cid' => 'C(150) DEFAULT \'\'', // varchar(150) default ''
    );
    $table['zmap_db_extra_enable_categorization'] = true;
    $table['zmap_primary_key_column'] = 'bid';

    // add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition($table['zmap_column'], '');
    ObjectUtil::addStandardFieldsToTableDataDefinition($table['zmap_column_def']);



    $table['zmap_projects'] = DBUtil::getLimitedTablename('zmap_projects');

    $table['zmap_projects_column'] = array(
        'pid' => 'pid',
        'cid' => 'cid',
        'name' => 'name',
        'description' => 'description',
    );

    $table['zmap_projects_column_def'] = array(
        'pid' => 'I(11) UNSIGNED AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'cid' => 'I(11) UNSIGNED',
        'name' => 'C(150) DEFAULT \'\'', // varchar(150) default ''
        'description' => 'C(150) DEFAULT \'\'', // varchar(150) default ''
    );
    $table['zmap_projects_db_extra_enable_categorization'] = true;
    $table['zmap_projects_primary_key_column'] = 'pid';

    // add standard data fields
    // ZMAP_Util::addStandardFieldsToTableDefinition($table['zmap_projects_projects_column'], '');
    // ZMAP_Util::addStandardFieldsToTableDataDefinition($table['zmap_projects_projects_column_def']);





    $table['zmap_roadmap'] = DBUtil::getLimitedTablename('zmap_roadmap');

    $table['zmap_roadmap_column'] = array(
        'rid' => 'rid',
        'name' => 'name',
        'description' => 'description',
        'start' => 'start',
        'startdescription' => 'startdescription',
        'end' => 'end',
        'enddescription' => 'enddescription',
        'start_lattitude' => 'start_lattitude',
        'start_longitude' => 'start_longitude',
        'end_lattitude' => 'end_lattitude',
        'end_longitude' => 'end_longitude',
        'waypoints' => 'waypoints',
        'length' => 'length',
        'width' => 'width',
        'area' => 'area',
        'cid' => 'cid',
        'pid' => 'pid',
    );

    $table['zmap_roadmap_column_def'] = array(
        'pid' => 'I(11) UNSIGNED AUTO PRIMARY', // int(11) unsigned NOT NULL auto_increment
        'name' => 'C(50) DEFAULT NULL',
        'description' => 'XL NOTNULL',
        'start' => 'C(50) DEFAULT NULL',
        'startdescription' => 'XL NOTNULL',
        'end' => 'C(50) DEFAULT NULL',
        'enddescription' => 'XL NOTNULL',
        'start_lattitude' => 'C(100) DEFAULT NULL',
        'start_longitude' => 'C(100) DEFAULT NULL',
        'end_lattitude' => 'C(100) DEFAULT NULL',
        'end_longitude' => 'C(100) DEFAULT NULL',
        'waypoints' => 'XL NOTNULL',
        'length' => 'C(50) DEFAULT NULL',
        'width' => 'C(50) DEFAULT NULL',
        'area' => 'C(50) DEFAULT NULL',
        'cid' => 'I(11) UNSIGNED',
        'pid' => 'I(11) UNSIGNED',
    );
    $table['zmap_roadmap_db_extra_enable_categorization'] = true;
    $table['zmap_roadmap_primary_key_column'] = 'rid';

    // add standard data fields
    // ZMAP_Util::addStandardFieldsToTableDefinition($table['zmap_projects_projects_column'], '');
    // ZMAP_Util::addStandardFieldsToTableDataDefinition($table['zmap_projects_projects_column_def']);

    return $table;
}

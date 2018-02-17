<?php

/**
 * Copyright ACTA-IT 2013 - ZMAP
 *
 * ZMAP
 * Demonstration of Zikula Module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * External Util class for example
 */
class ZMAP_Util {

    public static function externalfunction() {
        return true;
    }

    public static function addStandardFieldsToTableDataDefinition(&$columns) {
        //$columns['obj_status'] = "C(1) NOTNULL DEFAULT 'A'";
        // $columns['cr_date'] = "T NOTNULL DEFAULT '1970-01-01 00:00:00'";
        // $columns['cr_uid'] = "I NOTNULL DEFAULT '0'";
        //  $columns['lu_date'] = "T NOTNULL DEFAULT '1970-01-01 00:00:00'";
        //  $columns['lu_uid'] = "I NOTNULL DEFAULT '0'";

        return;
    }

}

// end class def
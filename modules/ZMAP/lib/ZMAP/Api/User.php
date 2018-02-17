<?php

/**
 * Copyright R2International 2013 - ZMAP
 *
 * ZMAP
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control User interface
 */
class ZMAP_Api_User extends Zikula_AbstractApi {

    /**
     * Get available user links
     *
     * @return array array of admin links
     */
    public function getlinks() {
        // Define an empty array to hold the list of admin links
        $links = array();

        // Check the users permissions to each avaiable action within the admin panel
        // and populate the links array if the user has permission
        if (SecurityUtil::checkPermission('ZMAP::', '::', ACCESS_ADMIN)) {
            $links[] = array(
                'url' => ModUtil::url('ZMAP', 'admin', 'modifyconfig'),
                'text' => $this->__('Admin'),
                'class' => 'z-icon-es-config');
        }

        $sublinks = array(
            array('url' => ModUtil::url('ZMAP', 'user', 'standard'),
                'text' => $this->__('Standard caching')),
            array('url' => ModUtil::url('ZMAP', 'user', 'nevercached'),
                'text' => $this->__('Never cached')),
            array('url' => ModUtil::url('ZMAP', 'user', 'partialcache'),
                'text' => $this->__('Partial cache')),
            array('url' => ModUtil::url('ZMAP', 'user', 'uniquepages'),
                'text' => $this->__('Multiple page caching')),
            array('url' => ModUtil::url('ZMAP', 'user', 'checkiscached'),
                'text' => $this->__('is_cached demo')),
            array('url' => ModUtil::url('ZMAP', 'user', 'cacheinfo'),
                'text' => $this->__('Caching explained'))
        );

        $links[] = array(
            'url' => ModUtil::url('ZMAP', 'user', 'cacheinfo'),
            'text' => $this->__('Cache demo'),
            'class' => 'z-icon-es-view',
            'links' => $sublinks);

        // Return the links array back to the calling function
        return $links;
    }

    /**
     * api function to insert the items held by this module
     *
     * @author R2international
     * @return insert id  held by this module
     */
    public function createElement($args) {

        // evaluates the input action
        $args['element']['action'] = isset($args['element']['action']) ? $args['element']['action'] : null;

        // Security check
        if (!SecurityUtil::checkPermission('ZMAP::', '::', ACCESS_COMMENT)) {
            return LogUtil::registerPermissionError();
        }
        //echo "comes here"; exit;
        //echo "<pre>"; print_r($args); echo "</pre>"; exit;
        if (!($obj = DBUtil::insertObject($args['element'], $args['table'], $args['Id']))) {
            return LogUtil::registerError($this->__('Error! Could not create new element.'));
        }

        return $obj;
    }

    /**
     * api function to getElements of the items held by this module
     *
     * @author r2international
     * @return insert id  held by this module
     */
    public function getElements($args) {

        // Optional arguments.
        $where = isset($args['where']) ? $args['where'] : '';
        $orderBy = isset($args['orderBy']) ? $args['orderBy'] : '';
        $useJoins = isset($args['useJoins']) ? ((bool) $args['useJoins']) : true;
        // create a empty result set
        $items = array();
        // Security check
        if (!SecurityUtil::checkPermission('ZMAP::', '::', ACCESS_OVERVIEW)) {
            return $items;
        }

        $objArray = DBUtil::selectObjectArray($args['table'], $where, $orderBy, $limitOffset, $limitNumRows, '', NULL);

        // $objArray = DBUtil::selectObjectArray($table, $where, $orderby, $limitOffset, $limitNumRows, $assocKey, $permissionFilter);
        // DBUtil::selectObjectArrayFilter($table, $where, $orderby, $limitOffset, $limitNumRows, $assocKey, $filterCallback, $categoryFilter, $columnArray);
        // Check for an error with the database code, and if so set an appropriate
        // error message and return
        if ($objArray === false) {
            return LogUtil::registerError($this->__('Error! Could not load any results.'));
        }

        // Return the items
        return $objArray;
    }
    
    
     /**
     * get a specific item
     * @author r2international
     * @param $args['typeId'] id of ZSELEX item to get
     * @return mixed item array, or false on failure
     */
    public function getElement($args) {

        //echo "hiii"; exit;
        //echo "<pre>";  print_r($args);  echo "</pre>"; exit;
        // optional arguments
        if (isset($args['objectid'])) {
            $args['typeId'] = $args['objectid'];
        }

        // Argument check
        if ((!isset($args['IdValue']) || !is_numeric($args['IdValue']))) {
            return LogUtil::registerArgsError();
        }

        // Check for caching of the DBUtil calls (needed for AJAX editing)
        if (!isset($args['SQLcache'])) {
            $args['SQLcache'] = true;
        }

        $permFilter = array();
        $permFilter[] = array('realm' => 0,
            'component_left' => 'ZMAP',
            'component_middle' => '',
            'component_right' => '',
            'instance_left' => 'cr_uid',
            'instance_middle' => '',
            'instance_right' => $args['IdName'],
            'level' => ACCESS_READ);

        if (isset($args['IdValue']) && is_numeric($args['IdValue'])) {
            $item = DBUtil::selectObjectByID($args['table'], $args['IdValue'], $args['IdName'], null, $permFilter, null, $args['SQLcache']);
        }
        if (empty($item))
            return false;

        return $item;
    }

    public function updateElement($args) {

        // print_r($args); exit;
        // Argument check
        if (!isset($args['IdValue'])) {
            return LogUtil::registerArgsError();
        }
        // Get the news item
        $item = ModUtil::apiFunc('ZMAP', 'user', 'getElement', $args);

        // print_r($item); exit;

        if ($item == false) {
            return LogUtil::registerError($this->__('Error! No such type found.'));
        }

        $this->throwForbiddenUnless($this->_isSubmittor($item) || SecurityUtil::checkPermission('ZMAP::', $item['cr_uid'] . '::' . $args['IdValue'], ACCESS_COMMENT), LogUtil::getErrorMsgPermission());

        if (!DBUtil::updateObject($args['element'], $args['table'], '', $args['IdName'])) {
            return LogUtil::registerError($this->__('Error! Could not save your changes.'));
        }
        //echo "hiiiii";   exit;
        // Let the calling process know that we have finished successfully
        return true;
    }

}

// end class def
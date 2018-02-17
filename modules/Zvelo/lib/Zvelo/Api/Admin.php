<?php

/**
 * Tag - a content-tagging module for the Zikukla Application Framework
 * 
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

/**
 * Class to control Admin Api
 */
class Zvelo_Api_Admin extends Zikula_AbstractApi {

    /**
     * Get available admin panel links
     *
     * @return array array of admin links
     */
    public function getlinks() {
        // Define an empty array to hold the list of admin links
        $links = array();

        if (SecurityUtil::checkPermission('Tag::', '::', ACCESS_ADMIN)) {
            $links[] = array(
                'url' => ModUtil::url('Zvelo', 'admin', 'modifyconfig'),
                'text' => $this->__('Settings'),
                'class' => 'z-icon-es-config');
        }

        if (SecurityUtil::checkPermission('Tag::', '::', ACCESS_ADMIN)) {
            $links[] = array(
                'url' => ModUtil::url('Zvelo', 'admin', 'view'),
                'text' => $this->__('Zvelo List'),
                'class' => 'z-icon-es-view');
        }



        return $links;
    }

}
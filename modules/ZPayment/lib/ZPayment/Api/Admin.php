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
 * Class to control Admin interface
 */
class ZPayment_Api_Admin extends Zikula_AbstractApi {

    /**
     * Get available admin panel links
     *
     * @return array array of admin links
     */
    public function getlinks() {
        // Define an empty array to hold the list of admin links
        $links = array();

        // Check the users permissions to each avaiable action within the admin panel
        // and populate the links array if the user has permission
        if (SecurityUtil::checkPermission('ZPayment::', '::', ACCESS_ADMIN)) {
            $links[] = array(
                'url' => ModUtil::url('ZPayment', 'admin', 'modifyconfig'),
                'text' => $this->__('Settings'),
                'class' => 'z-icon-es-config');
        }
        if (SecurityUtil::checkPermission('ZPayment::', '::', ACCESS_ADMIN)) {
            $links[] = array(
                'url' => ModUtil::url('ZPayment', 'admin', 'info'),
                'text' => $this->__('Module Information'),
                'class' => 'z-icon-es-info');
        }

        // Return the links array back to the calling function
        return $links;
    }

}

// end class def
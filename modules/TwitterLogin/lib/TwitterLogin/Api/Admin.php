<?php
/**
 * TwitterLogin
 */

/**
 * Administrative API functions.
 */
class TwitterLogin_Api_Admin extends Zikula_AbstractApi
{

    /**
     * Get available admin panel links.
     *
     * @return array Array of adminpanel links.
     */
    public function getLinks()
    {
        $links = array();

        if (SecurityUtil::checkPermission('TwitterLogin::', '::', ACCESS_ADMIN)) {
            $links[] = array('url' => ModUtil::url($this->name, 'admin', 'modifyConfig'), 'text' => $this->__('Settings'), 'class' => 'z-icon-es-config');
        }

        return $links;
    }
}
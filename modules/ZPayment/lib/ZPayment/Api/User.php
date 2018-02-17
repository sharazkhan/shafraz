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
 * Class to control User interface
 */
class ZPayment_Api_User extends Zikula_AbstractApi
{

    /**
     * Get available user links
     *
     * @return array array of admin links
     */
    public function getlinks()
    {////
        // Define an empty array to hold the list of admin links
        $links = array();

        // Check the users permissions to each avaiable action within the admin panel
        // and populate the links array if the user has permission
        if (SecurityUtil::checkPermission('ZPayment::', '::', ACCESS_ADMIN)) {
            $links[] = array(
                'url' => ModUtil::url('ZPayment', 'admin', 'modifyconfig'),
                'text' => $this->__('Admin'),
                'class' => 'z-icon-es-config');
        }

        $sublinks = array(
            array('url' => ModUtil::url('ZPayment', 'user', 'standard'),
                'text' => $this->__('Standard caching')),
            array('url' => ModUtil::url('ZPayment', 'user', 'nevercached'),
                'text' => $this->__('Never cached')),
            array('url' => ModUtil::url('ZPayment', 'user', 'partialcache'),
                'text' => $this->__('Partial cache')),
            array('url' => ModUtil::url('ZPayment', 'user', 'uniquepages'),
                'text' => $this->__('Multiple page caching')),
            array('url' => ModUtil::url('ZPayment', 'user', 'checkiscached'),
                'text' => $this->__('is_cached demo')),
            array('url' => ModUtil::url('ZPayment', 'user', 'cacheinfo'),
                'text' => $this->__('Caching explained'))
        );

        $links[] = array(
            'url' => ModUtil::url('ZPayment', 'user', 'cacheinfo'),
            'text' => $this->__('Cache demo'),
            'class' => 'z-icon-es-view',
            'links' => $sublinks);

        // Return the links array back to the calling function
        return $links;
    }

    function sign($args)
    {

        //  echo "comes here"; exit;
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $params           = $args['params'];
        $api_key          = $args['api_key'];
        $flattened_params = $this->flatten_params($params);
        ksort($flattened_params);
        $base             = implode(" ", $flattened_params);

        return hash_hmac("sha256", $base, $api_key);
    }

    function flatten_params($obj, $result = array(), $path = array())
    {
        //echo "<pre>"; print_r($obj); echo "</pre>"; exit;
        if (is_array($obj)) {
            foreach ($obj as $k => $v) {
                $result = array_merge($result,
                    $this->flatten_params($v, $result,
                        array_merge($path, array($k))));
            }
        } else {
            $result[implode("",
                    array_map(function($p) {
                        return "[{$p}]";
                    }, $path))] = $obj;
        }

        return $result;
    }
}
// end class def
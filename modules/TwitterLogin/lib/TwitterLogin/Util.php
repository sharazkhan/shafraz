<?php

/**
 * Copyright R2INTERNATIONAL 2013
 *
 * TwitterLogin
 * Demonstration of Zikula Module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * External Util class for example
 */
class TwitterLogin_Util {

    public static function validateEmail($email) {

        $dom = ZLanguage::getModuleDomain('ZSELEX');
        $validationerror = false;
        if (empty($email)) {
            $validationerror .= __f('Error! You did not enter a %s.', __('Email', $dom), $dom) . "\n";
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $valid = 1; //valid email
        } else {
            $valid = 0; //not valid
        }

        if ($valid < 1) {
            $validationerror .= __f('Error! Invalid %s.', __('Email', $dom), $dom) . "\n";
        }
//        if (empty($type['description'])) {
//            $validationerror .= __f('Error! You did not enter a %s.', __('Type description', $dom), $dom) . "\n";
//        }
        return $validationerror;
    }

}

// end class def
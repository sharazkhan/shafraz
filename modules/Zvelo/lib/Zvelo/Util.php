<?php

/**
 * Copyright R2INTERNATIONAL 2013
 *
 * ZSELEX
 * Demonstration of Zikula Module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * External Util class for example
 */
class Zvelo_Util {

    public static function externalfunction() {


        return true;
    }

    public static function ajaxOutput($data, $code = '200 OK') {
        //exit;
        if (!System::isLegacyMode()) {
            $response = new Zikula_Response_Ajax($data);
            echo $response;
            System::shutDown();
        }
        // Below for reference - to be deleted.
        // check if an error message is set
        $msgs = LogUtil::getErrorMessagesText('<br />');

        if ($msgs != false && !empty($msgs)) {
            self::error($msgs);
        }

        echo $data;
        System::shutdown();
    }

}

// end class def
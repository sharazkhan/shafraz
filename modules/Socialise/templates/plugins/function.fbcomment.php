<?php

/**
 * Copyright Socialise Team 2011
 *
 * @license GNU/GPLv3 (or at your option, any later version).
 * @package Socialise
 * @link https://github.com/phaidon/Socialise
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

/**
 * Smarty plugin to display a sexybookmarks buttons.
 *
 * Available parameters
 *
 *   url: The URL of the item to submit to the social bookmarking site(s)
 *   see the rest on the fblike Plugin Api function
 *
 * Examples
 *   For the News module: {fblike url=$links.permalink}
 *   For a Clip publication: {fblike url=$returnurl}
 *
 * @param array  $params  All attributes passed to this function from the template.
 * @param object &$smarty Reference to the Smarty object.
 *
 * @return string
 */
function smarty_function_fbcomment($params, &$smarty) {

   // echo "<pre>"; print_r($params);
    $shop_id = $params['shop_id'];
    $serviceExist = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceExistBlock', $args = array('shop_id' => $shop_id,
                'type' => 'fbcomment'));


    if ($serviceExist < 1) {
        return false;
    }
    
    unset($smarty);
    return ModUtil::apiFunc('Socialise', 'plugin', 'fbcomment', $params);
}

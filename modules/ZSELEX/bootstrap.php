<?php

// ini_set('allow_url_include', "1");
/**
 *
 * ZSELEX
 * Demonstration of Zikula Module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */
/**
 * This file loads stuff and runs on every page of your module
 * before anythig else runs
 */
// echo "Comes Here";// testing???? ------!!sharaz testing for git!!!!
// Load Doctrine 2 Extension here
$helper = ServiceUtil::getService ( 'doctrine_extensions' );
$helper->getListener ( 'timestampable' );
$helper->getListener ( 'standardfields' );

?>
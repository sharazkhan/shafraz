<?php

/**
 * Copyright Zikula Foundation 2009 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package Zikula
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */
//ZSELEX_Util::disable_magic_quotes();
//echo "Comes here!"; exit;
include 'lib/bootstrap.php';
$core->init();

//System::redirect(ModUtil::url('Users', 'user', 'main'));
//ZSELEX_Util::disable_magic_quotes();
$em = ServiceUtil::getService('doctrine.entitymanager');
$em->getRepository('ZSELEX_Entity_Event')->updateEventTempTable();
//echo "<pre>"; print_r($result); echo "</pre>"; exit;

//echo "hello world!"; exit;

<?php

/**
 * Copyright ACTA-IT 2015 - ZTEXT
 *
 * ZTEXT
 * Block
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */
/**
 * This file loads stuff and runs on every page of your module
 * before anythig else runs
 * */
$helper = ServiceUtil::getService('doctrine_extensions');
$helper->getListener('timestampable');
$helper->getListener('standardfields');
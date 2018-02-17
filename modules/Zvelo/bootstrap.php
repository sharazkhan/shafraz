<?php

/**
 * Zvelo
 */
$helper = ServiceUtil::getService('doctrine_extensions');
$helper->getListener('sluggable');
$helper->getListener('timestampable');
$helper->getListener('standardfields');
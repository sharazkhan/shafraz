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
//include 'lib/bootstrap.php';
//$core->init();
//echo "hellooo"; exit;
//System::redirect(ModUtil::url('Users', 'user', 'main'));
//ZSELEX_Util::disable_magic_quotes();
if (isset($argv)) {
    parse_str(implode('&', array_slice($argv, 1)), $_GET);
}
//$em       = ServiceUtil::getService('doctrine.entitymanager');
$shop_id  = $_GET['shop_id'];
$event_id = $_GET['event_id'];
$baseUrl  = $_GET['base_url'];

$start = $_GET['from'];
$end   = $_GET['end'];
if ((!empty($start) && !empty($end)) && ($end >= $start)) {
    /* $dateRange = ZSELEX_Util::createDateRangeArray($start, $end);
      $em->getRepository('ZSELEX_Entity_Event')->updateEventTemp(
      array(
      'shop_id' => $shop_id,
      'event_id' => $event_id,
      'dates' => $dateRange
      )); */
    $url    = $baseUrl."index.php?module=zselex&type=shop&func=updateEventTemp";
//echo $url; exit;
    $fields = array(
        'shop_id' => urlencode($shop_id),
        'event_id' => urlencode($event_id),
        'from' => urlencode($start),
        'end' => urlencode($end),
    );

    $fields = array_filter($fields);

    $fields_string = '';
    foreach ($fields as $key => $value) {
        $fields_string .= $key.'='.$value.'&';
    }
    rtrim($fields_string, '&');

//open connection
    $ch = curl_init();

//set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, count($fields));
//curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//execute post
    $result = curl_exec($ch);

//close connection
    curl_close($ch);
}



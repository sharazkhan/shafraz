<?php
/*
  include 'lib/bootstrap.php';
  $core->init();

  $em = ServiceUtil::getService('doctrine.entitymanager');
  $em->getRepository('ZSELEX_Entity_Shop')->insertKeyword();
 */

error_reporting(0);
set_time_limit(0);

$baseUrl = $argv[1];

//echo $baseUrl; exit;


$url = $baseUrl."index.php?module=zselex&type=admin&func=updateProductKeywords";
//echo $url; exit;
/* $fields = array(
  'shopIds' => urlencode($shopIdsJson)
  ); */

/*
  foreach ($fields as $key => $value) {
  $fields_string .= $key.'='.$value.'&';
  }
  rtrim($fields_string, '&');
 */

//echo $fields_string . PHP_EOL; exit;
//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL, $url);
//curl_setopt($ch, CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_POST, true);
//curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//execute post
$result = curl_exec($ch);

//close connection
curl_close($ch);
die("End of script");
?>


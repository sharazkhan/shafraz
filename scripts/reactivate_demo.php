<?php
error_reporting(0);
set_time_limit(0);
//echo "Comes here" . PHP_EOL;  exit;
$shopIdsJson = $argv[1];
$days        = $argv[2];
$baseUrl     = $argv[3];

//echo $baseUrl; exit;

/*
  echo "Shop ID's : ";
  //echo "===========" . PHP_EOL;
  echo $shopIdsJson . PHP_EOL;

  echo "Days :" .$days. PHP_EOL;

  echo "BaseUrl :" .$baseUrl. PHP_EOL;

  exit;
 */

$url    = $baseUrl."index.php?module=zselex&type=shop&func=reactivateDemoBg";
//echo $url; exit;
$fields = array(
    'shop_ids' => urlencode($shopIdsJson),
    'days' => urlencode($days),
    // 'shop_ids' => $shopIdsJson,
    // 'days' => $days,
);

//echo "<pre>"; print_r($fields);  echo "</pre>";  exit;
//url-ify the data for the POST
foreach ($fields as $key => $value) {
    $fields_string .= $key.'='.$value.'&';
}
rtrim($fields_string, '&');

//echo $fields_string . PHP_EOL; exit;
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
?>

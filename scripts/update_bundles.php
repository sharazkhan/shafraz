<?php
error_reporting(0);
set_time_limit(0);
//echo "Comes here" . PHP_EOL;  exit;
$shopIdsJson = $argv[1];
$baseUrl     = $argv[2];

//echo $shopIdsJson . PHP_EOL; exit;
//echo $baseUrl; exit;


$url    = $baseUrl."index.php?module=zselex&type=shop&func=updateShopBundlesBg";
//echo $url; exit;
$fields = array(
    'shopIds' => urlencode($shopIdsJson)
);

//echo "<pre>"; print_r($fields);  echo "</pre>" . PHP_EOL;  exit;
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

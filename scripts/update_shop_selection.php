<?php
if (isset($argv)) {
    // parse_str(implode('&', array_slice($argv, 1)), $_GET);
    parse_str(implode('&', array_slice($argv, 1)), $_POST);
}

$chg_cat     = $_POST['chg_cat'];
$chg_aff     = $_POST['chg_aff'];
$chg_stat    = $_POST['chg_stat'];
$chg_brnch   = $_POST['chg_brnch'];
$select_type = $_POST['select_type'];
$select_type = $_POST['select_type'];
$shopJson    = $_POST['shopJson'];
$baseUrl     = $_POST['baseUrl'];
$chgGroup    = $_POST['chg_group'];

//echo $chg_stat . PHP_EOL; exit;
if ($select_type == 'stat') {
    if ($chg_stat) $chg_stat = 'active';
    else $chg_stat = 'inactive';
}


$func = "updateShopSelection";
if ($select_type == 'group') {
    $func = "assignToGroup";
}

$url = $baseUrl."index.php?module=zselex&type=shop&func=$func";

//echo $url; exit;
$fields = array(
    'chg_cat' => urlencode($chg_cat),
    'chg_aff' => urlencode($chg_aff),
    'chg_stat' => urlencode($chg_stat),
    'chg_brnch' => urlencode($chg_brnch),
    'select_type' => urlencode($select_type),
    'shopJson' => urlencode($shopJson),
    'baseUrl' => urlencode($baseUrl),
    'chg_group' => urlencode($chgGroup)
);

$fields = array_filter($fields);

//echo "<pre>"; print_r($fields); echo "</pre>".PHP_EOL; exit;
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

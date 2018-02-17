<?php

$protocol = '7';
$msgtype = 'authorize';
$merchant = '15370300';
$language = 'en';
$ordernumber = time();
$amount = '147';
$currency = 'DKK';
$continueurl = "http://" . $_SERVER['SERVER_NAME'] . "/quickpay/continue.php";
$cancelurl = "http://" . $_SERVER['SERVER_NAME'] . "/quickpay/cancel.php";
$callbackurl = "http://" . $_SERVER['SERVER_NAME'] . "/quickpay/callback.php"; //see http://quickpay.dk/clients/callback-quickpay.php.txt
$autocapture = '0';
$cardtypelock = '';
$splitpayment = '1';
$md5secret = 'a2aa3b62eacbe78baec92162ca99d93171b0115295fe87c65cebe91d3d5a1685';
//$md5secret = 'klasfkskfwe43453klsjfkjskldjf34j5r4lk3jlkjlkjslkjsadlkfjlkasflk';

$md5check = md5($protocol . $msgtype . $merchant . $language . $ordernumber . $amount . $currency . $continueurl . $cancelurl . $callbackurl . $autocapture . $cardtypelock . $splitpayment . $md5secret);
?>

<form action="https://secure.quickpay.dk/form/" method="post">
    <input type="hidden" name="protocol" value="<?php echo $protocol ?>" />
    <input type="hidden" name="msgtype" value="<?php echo $msgtype ?>" />
    <input type="hidden" name="merchant" value="<?php echo $merchant ?>" />
    <input type="hidden" name="language" value="<?php echo $language ?>" />
    <input type="hidden" name="ordernumber" value="<?php echo $ordernumber ?>" />
    <input type="hidden" name="amount" value="<?php echo $amount ?>" />
    <input type="hidden" name="currency" value="<?php echo $currency ?>" />
    <input type="hidden" name="continueurl" value="<?php echo $continueurl ?>" />
    <input type="hidden" name="cancelurl" value="<?php echo $cancelurl ?>" />
    <input type="hidden" name="callbackurl" value="<?php echo $callbackurl ?>" />
    <input type="hidden" name="autocapture" value="<?php echo $autocapture ?>" />
    <input type="hidden" name="cardtypelock" value="<?php echo $cardtypelock ?>" />
    <input type="hidden" name="splitpayment" value="<?php echo $splitpayment ?>" />
    <input type="hidden" name="md5check" value="<?php echo $md5check ?>" />
    <input type="submit" value="Pay" />
</form>

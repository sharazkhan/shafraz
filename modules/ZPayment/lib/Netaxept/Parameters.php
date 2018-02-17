<?php

// Please change the value of the following lines according to your environment
// MerchantId provided by ZPayment
//$merchant_Id = "11001393";
// Token provided by ZPayment
//$token = "8Sr-Bd/6";
// WSDL location found in documentation
$wsdl = "https://epayment-test.bbs.no/Netaxept.svc?wsdl";

// Redirect from ZPayment to your site
$path_parts = pathinfo($_SERVER["PHP_SELF"]);
// this is an example is showing how to add one (or several additional parameters on the terminal)
//$redirect_url = "http://" . $_SERVER["HTTP_HOST"] . $path_parts['dirname'] . "/Process.php?webshopParameter=1234567";
//$redirect_url = "http://" . $_SERVER["HTTP_HOST"] . $path_parts['dirname'] . "/Process.php?webshopParameter=123/456/789";
$redirect_url = "http://demo.keeprunning.dk/index.php?main_page=checkout_process";
//$redirect_url = "http://" . $_SERVER["HTTP_HOST"] . $path_parts['dirname'] . "/Process.php";
// ZPayment Terminal Location
$terminal = "https://epayment-test.bbs.no/terminal/default.aspx";
?>

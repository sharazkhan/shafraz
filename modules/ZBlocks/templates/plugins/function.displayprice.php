<?php

function smarty_function_displayprice($args, &$smarty) {

    //echo "hello";
    // echo "<pre>"; print_r($args);  echo "</pre>";
    $amount = $args['amount'];
    $pref = $args['pref'];

    $curr_args = array('amount' => $amount, 'currency_symbol' => '', 'decimal_point' => ',', 'thousands_sep' => '.', 'precision' => '2');
    $price = number2currency($curr_args);
    if ($pref == true) {
        $prefix = substr($amount, 0, 1);
        if ($prefix == '+') {
            return $prefix . $price;
        } else {
            return $price;
        }
    }
    return $price;
}

function number2currency($args) {
    setlocale(LC_MONETARY, "da_DK");
    $locale = localeconv();
    $amount = $args['amount'];
    if (!isset($amount) || $amount == '') {
        $amount = 0;
    }
    $locale['currency_symbol'] = $args['currency_symbol'];
    $locale['decimal_point'] = $args['decimal_point'];
    $locale['thousands_sep'] = $args['thousands_sep'];
    $precision = $args['precision'];
    return $locale['currency_symbol'] . "" . number_format($amount, $precision, $locale['decimal_point'], $locale['thousands_sep']);

    // return $curency_money;
}

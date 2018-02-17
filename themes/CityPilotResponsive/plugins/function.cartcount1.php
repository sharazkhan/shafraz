<?php

function smarty_function_cartcount($args, &$smarty) {

    //echo "<pre>"; print_r($_SESSION['cart']); echo "</pre>"; exit;
    $item = ZSELEX_Controller_User::carttotal();
    $current_theme = System::getVar('Default_Theme');
    $curr_args = array('amount' => $item['total'], 'currency_symbol' => '', 'decimal_point' => ',', 'thousands_sep' => '.', 'precision' => '2');
    $GRANDTOTAL_FINAL = ModUtil::apiFunc('ZSELEX', 'user', 'number2currency', $curr_args);
    $counts = "&nbsp;";
    if (!empty($item['count'])) {
        $counts = $item['count'];
    }
    // $str = "<span style='float:none; padding:0px; display:inline-block'>".$GRANDTOTAL_FINAL . " DKK</span>" . "<span style='float:none; padding:0px; display:inline-block'><img src=themes/" . $current_theme . "/images/checkout.png /></span>" . "<span style='display:inline-block; margin-left:-15px; margin-top:6px; float:none;padding:0px; color:white; position:absolute;'>" .$counts . "</span>";
    $str = "<span id='carts_total' style='float:none; padding:0px; display:inline-block'>" . $GRANDTOTAL_FINAL . " DKK</span>" . "<span id='carts_count' style='padding:0px;color:white;display:inline-block;float:none;width:20px;height:18px;background:url(".pnGetBaseURL()."themes/$current_theme/images/checkout.png) no-repeat center center; padding-top:9px; text-align:center'>" . $counts . "</span>";
    return $str;

    // DKK <img src="{$themepath}/images/checkout.png" />
}
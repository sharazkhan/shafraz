<?php

function smarty_function_sample($args, &$smarty) {

    $discount = $args['value'];
    $origPrice1 = $args['orig_price'];
    $origPrice = number_format($origPrice1, 2, '.', ',');
    //echo $origPrice;
    $last_char = substr($discount, -1);

    $is_discount = false;
    $discount_val = '';
    if (!empty($discount)) {
        // echo "heree";
        if ($last_char == "%") {
            // echo "percentage";
            // $newVal = substr($discount, 0, -1);
            // alert(newVal);
            // $disPrice = $origPrice - ($origPrice * $newVal / 100);
            $discount_val = $discount;
        } else {
            //echo "normal";
            $new_val = $discount / $origPrice * 100;
            $discount_val = round($new_val) . "%";
        }
        $is_discount = true;
    }
    //echo $discount_val;
    //echo $is_discount;


    $smarty->assign("is_discount", $is_discount);
    $smarty->assign("dicount_vals", $discount_val);
}
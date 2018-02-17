<?php

function smarty_function_setdiscount($args, &$smarty) {

    //echo "<pre>"; print_r($args); echo "</pre>"; 
    $discount = $args['value'];
    $origPrice1 = $args['orig_price'];
    //$origPrice = number_format($origPrice1, 2, '.', ',');
    //$origPrice = getAmount($origPrice1);
    $origPrice = $origPrice1;
    //echo $origPrice . '<br>';
    $last_char = substr($discount, -1);

    $is_discount = false;
    $discount_val = '';
   // if (!empty($discount)) {
       if (!empty($discount) && $discount > 0) {
        // echo "heree";
        if ($last_char == "%") {
            // echo "percentage";
            // $newVal = substr($discount, 0, -1);
            // alert(newVal);
            // $disPrice = $origPrice - ($origPrice * $newVal / 100);
            $discount_val = $discount;
        } else {
            // echo "normal";
            // echo $discount;
            //echo $origPrice;
            // echo $origPrice - $discount;
            //$new_val = (($origPrice - $discount) / $origPrice) * 100;
            $n = $discount / $origPrice;
            $new_val = $n * 100;
          //  echo $new_val;
            $first_str = str_split($new_val);
            // echo "first :" . $str[0];
            if ($first_str[0] == '0') {
                $discount_val = number_format($new_val, 2) - 0 . '%';
            } else {
                $discount_val = round($new_val) . "%";
            }
            //$discount_val = abs($new_val) . "%";
            //$discount_val = round($new_val) . "%";
        }
        $is_discount = true;
    }
    //echo $discount_val;
    //echo $is_discount;


    $smarty->assign("is_discount", $is_discount);
    $smarty->assign("dicount_value", $discount_val);
}


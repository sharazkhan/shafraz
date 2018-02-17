<?php

function smarty_function_cartcount1($args, &$smarty) {

    //echo "<pre>"; print_r($_SESSION['cart']); echo "</pre>"; exit;
    $GRANDTOTAL = ZSELEX_Controller_User::carttotal();
   
   // $GRANDTOTAL = "12345678918";
  //  $GRANDTOTAL =  wordwrap(23423432454342535345345353245345353245234, 4);
    //$GRANDTOTAL =  wordwrap("23423432454342535345345353245345353245234", 10 , '<br>' , 1);


    return $GRANDTOTAL;
}
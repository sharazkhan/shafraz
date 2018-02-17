<?php

function smarty_function_dayname($params, &$smarty) {
    $append = '';
    $date = $params['date'];
    $dayname = date_format(date_create($date), 'l'); 
  
    echo $dayname;
    // $smarty->assign("perm", $perm);
    // $smarty->assign("link", $link);
}

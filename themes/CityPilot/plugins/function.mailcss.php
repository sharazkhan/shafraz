<?php

function smarty_function_mailcss($args, &$smarty) {

    // $table = "style='font-size:14px;color:#666666'";
    $headerTable = " style='font-size:14px;color:#666666;width:100%;background: #E45620'";
    $header1stTd = " style='height:78px;color: #FFF;padding-left: 50px;vertical-align: middle'";
    $header2ndTd = " style='text-align: left;color:white;vertical-align: middle'";
    $headerH3 = " style='padding-top:25px;padding-right:150px;display:inline-block;margin-left: 85p;vertical-align:baseline;'";
    $contentTable = " style='font-size:14px;color:#666666;margin-top: 20px;margin-bottom: 20px'";
    $contentTd = " style='padding-left: 50px;padding-right: 50px'";
    $footerTable = " style='width:100%;border-top: 7px solid #2B2B2B;background-color: #545153'";
    $footerTd = " style='padding-top:10px;padding-bottom: 10px;  padding-left: 50px'";
    $footerLogo = " style='margin-top: 28px;vertical-align:top;'";

   // $smarty->assign("table", $table);
    $smarty->assign("headerTable", $headerTable);
    $smarty->assign("header1stTd", $header1stTd);
    $smarty->assign("header2ndTd", $header2ndTd);
    $smarty->assign("headerH3", $headerH3);
    $smarty->assign("contentTable", $contentTable);
    $smarty->assign("contentTd", $contentTd);
    $smarty->assign("footerTable", $footerTable);
    $smarty->assign("footerTd", $footerTd);
    $smarty->assign("footerLogo", $footerLogo);
}
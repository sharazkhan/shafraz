<?php

function smarty_function_shorttext($args, &$smarty) {

    //echo "hello";
    $text = trim($args['text']);
    $length = $args['len'];
    $actual_length = strlen($text);
    if ($actual_length > $length) {
        $dots = "..";
    }
    $final_text = substr($text, 0, $length);

    return $final_text . $dots;
}
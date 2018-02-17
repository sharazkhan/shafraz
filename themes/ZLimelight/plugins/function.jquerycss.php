<?php

function smarty_function_jquerycss($params, &$smarty) {
    $jcss = '<script type="text/javascript">
                 var $curr = jQuery(".edits");
                    $curr = $curr.prev();
                    $curr.css("width", "auto");
                    $curr.css("float", "left");
                    $curr.css("padding-top", "2px");
                    $curr.css("background-position", "1px 2px");
                </script>';
    return $jcss;
}

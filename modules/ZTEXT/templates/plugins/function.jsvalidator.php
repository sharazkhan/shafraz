<?php

function smarty_function_jsvalidator($args, &$smarty) {
    // js files
    PageUtil::addVar('javascript', 'modules/ZSELEX/javascript/validation/fabtabulous.js');
    PageUtil::addVar('javascript', 'modules/ZSELEX/javascript/validation/validation.js');
    // css files
    PageUtil::addVar('stylesheet', 'modules/ZSELEX/javascript/validation/style.css');
}
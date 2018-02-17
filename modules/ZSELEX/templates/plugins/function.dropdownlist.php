<?php
function smarty_function_dropdownlist($args, &$smarty) {
	// js files
	PageUtil::addVar ( 'javascript', 'modules/ZSELEX/javascript/dropdownlist/jquery-1.6.1.min.js' );
	PageUtil::addVar ( 'javascript', 'modules/ZSELEX/javascript/dropdownlist/jquery-ui-1.8.13.custom.min.js' );
	PageUtil::addVar ( 'javascript', 'modules/ZSELEX/javascript/dropdownlist/ui.dropdownchecklist.js' );
	
	// css files
	PageUtil::addVar ( 'stylesheet', 'modules/ZSELEX/javascript/dropdownlist/jquery-ui-1.8.13.custom.css' );
	PageUtil::addVar ( 'stylesheet', 'modules/ZSELEX/javascript/dropdownlist/ui.dropdownchecklist.themeroller.css' );
}
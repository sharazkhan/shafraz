<?php
function smarty_function_jcrop($args, &$smarty) {
	// js files
	PageUtil::addVar ( 'javascript', 'modules/ZSELEX/javascript/jcrop/jquery.min.js' );
	PageUtil::addVar ( 'javascript', 'modules/ZSELEX/javascript/jcrop/jquery.Jcrop.js' );
	// css files
	
	// PageUtil::addVar('stylesheet', 'modules/ZSELEX/style/jcrop/main.css');
	// PageUtil::addVar('stylesheet', 'modules/ZSELEX/style/jcrop/demos.css');
	PageUtil::addVar ( 'stylesheet', 'modules/ZSELEX/style/jcrop/jquery.Jcrop.css' );
}

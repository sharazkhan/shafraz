<?php
function smarty_function_timepickerplugin($args, &$smarty) {
	// js files
	PageUtil::addVar ( 'javascript', 'modules/ZSELEX/javascript/timepicker/jquery.mousewheel.js' );
	PageUtil::addVar ( 'javascript', 'modules/ZSELEX/javascript/timepicker/globalize.js' );
	PageUtil::addVar ( 'javascript', 'modules/ZSELEX/javascript/timepicker/globalize.culture.de-DE.js' );
	PageUtil::addVar ( 'javascript', 'modules/ZSELEX/javascript/timepicker/globalize.culture.ja-JP.js.js' );
	PageUtil::addVar ( 'javascript', 'modules/ZSELEX/javascript/timepicker/jquery.ui.core.js' );
	PageUtil::addVar ( 'javascript', 'modules/ZSELEX/javascript/timepicker/jquery.ui.widget.js' );
	PageUtil::addVar ( 'javascript', 'modules/ZSELEX/javascript/timepicker/jquery.ui.button.js' );
	PageUtil::addVar ( 'javascript', 'modules/ZSELEX/javascript/timepicker/jquery.ui.spinner.js' );
	PageUtil::addVar ( 'javascript', 'modules/ZSELEX/javascript/timepicker/jquery.ui.mask.js' );
	PageUtil::addVar ( 'javascript', 'modules/ZSELEX/javascript/timepicker/jquery.ui.timepicker.js' );
	
	// css files
	PageUtil::addVar ( 'stylesheet', 'modules/ZSELEX/javascript/timepicker/visual.css' );
	PageUtil::addVar ( 'stylesheet', 'modules/ZSELEX/javascript/timepicker/jquery.ui.all.css.css' );
}
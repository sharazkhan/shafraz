<?php
function smarty_function_jscroll($args, &$smarty) {
	$fileName = $args ['file_name'];
	// js files
	$device = ZSELEX_Util::getDevice ();
	if ($device == 'desktop') {
		PageUtil::addVar ( 'javascript', $fileName );
	}
}

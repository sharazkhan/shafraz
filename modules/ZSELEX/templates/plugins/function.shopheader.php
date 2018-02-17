<?php
function smarty_function_shopheader($args, &$smarty) {
	// exit;
	return ModUtil::func ( 'ZSELEX', 'admin', 'shopheader' );
}
<?php
function smarty_function_lingualtext($args, &$smarty) {
	$thislang = ZLanguage::getLanguageCode ();
	$content = $args ['content'];
	$content_unserial = unserialize ( $content );
	$description = $content_unserial [$thislang] ['infomessage'];
	
	return $description;
}
<?php
function smarty_modifier_cleantext($string) {
	$new_text = stripslashes ( $string );
	// $new_text = htmlspecialchars($new_text);
	// $new_text = htmlentities($new_text);
	return $new_text;
}

/* vim: set expandtab: */
?>

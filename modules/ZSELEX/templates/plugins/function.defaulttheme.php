<?php
function smarty_function_defaulttheme($args, &$smarty) {
	
	// $current_theme_path = pnGetBaseURL() . "themes/" . System::getVar('Default_Theme') . "/templates/includes/header.tpl";
	$current_theme_path = "../../../CityPilot/templates/includes/header.tpl";
	// return include($current_theme_path);
	$smarty->assign ( "defaultthemepath", $current_theme_path );
	// return $current_theme;
}
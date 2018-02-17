<?php

/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty plugin
 *
 * Type: modifier<br>
 * Name: nl2table<br>
 * Date: Feb 26, 2003
 * Purpose: convert \r\n, \r or \n to <<br>>
 * Input:<br>
 * - contents = contents to replace
 * - preceed_test = if true, includes preceeding break tags
 * in replacement
 * Example: {$text|nl2br}
 * 
 * @link http://smarty.php.net/manual/en/language.modifier.nl2br.php
 *       nl2br (Smarty online manual)
 * @version 1.0
 * @author Monte Ohrt <monte at ohrt dot com>
 * @param
 *        	string
 * @return string
 */
function smarty_modifier_nl2table($string) {
	/*
	 * $fromnewline = array(" ", "\r\n", "\n", "\r");
	 * $totable = array("</td><td>", "</td></tr><tr><td>", "</td></tr><tr><td>", "</td></tr><tr><td>");
	 * $newtextarea = "<table><tr><td>" . str_replace($fromnewline, $totable, $string) . "</td></tr></table>";
	 */
	$fromnewline = array (
			"  ",
			" -",
			"- ",
			" ",
			"\r\n",
			"\n",
			"\r" 
	);
	$totable = array (
			" ",
			"-",
			"-",
			"</td><td>",
			"</td></tr><tr><td>",
			"</td></tr><tr><td>",
			"</td></tr><tr><td>" 
	);
	$newtextarea = "<table><tr><td>" . str_replace ( $fromnewline, $totable, $string ) . "</td></tr></table>";
	$newtextarea = str_replace ( "-", " - ", $newtextarea );
	return $newtextarea;
}

/* vim: set expandtab: */
?>

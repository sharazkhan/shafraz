<?php

echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<title>Untitled Document</title>
</head><body>";

$oldtextarea = "Mandag  10.00 - 17.00\nTirsdag 10.00 - 17.00\nOnsdag 10.00 - 17.00\nTorsdag 10.00 - 17.00\nFredag 10.00 - 17.00\nLørdag 10.00 - 13.00\nSøndag Lukket";
$newtextarea = "";
$newnltextarea = "";
$fromnewline = array("  ", " -", "- ", " ", "\r\n", "\n", "\r");
$totable = array(" ", "-", "-", "</td><td>", "</td></tr><tr><td>", "</td></tr><tr><td>", "</td></tr><tr><td>");
$newtextarea = "<table><tr><td>".str_replace($fromnewline, $totable, $oldtextarea)."</td></tr></table>";
$newtextarea = str_replace("-", " - ", $newtextarea);


$newnltextarea = nl2br($oldtextarea);

echo "<br />OLD: ".$oldtextarea;
echo "<br /><br />NL: ".$newnltextarea;
echo "<br /><br />NEW: ".$newtextarea;

echo "</body>
</html>";

?>
<?php

$path = $_SERVER['DOCUMENT_ROOT'] . "/modules/ZSELEX/images/kim"; // <-- Edit: Add your folder name here!

function filesize_recursive($path){ // Function 1
	if(!file_exists($path))
		return 0;
	if(is_file($path))
		return filesize($path);
	$ret = 0;
	foreach(glob($path."/*") as $fn)
		$ret += filesize_recursive($fn);
	return $ret;
}
 
function display_size($size) { // Function 2
	$sizes = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
	if ($retstring === null) {
		$retstring = '%01.2f %s';
	}
	$lastsizestring = end($sizes);
	foreach ($sizes as $sizestring) {
		if ($size < 1024) {
			break;
		}
		if ($sizestring != $lastsizestring) {
			$size /= 1024;
		}
	}
	if ($sizestring == $sizes[0]) {
		$retstring = '%01d %s';
	} // Bytes aren't normally fractional
	return sprintf($retstring, $size, $sizestring);
}

   $mtime = microtime(); 
   $mtime = explode(" ",$mtime); 
   $mtime = $mtime[1] + $mtime[0]; 
   $starttime = $mtime; 

echo "Folder {$path} size: ".display_size(filesize_recursive($path))."";

   $mtime = microtime(); 
   $mtime = explode(" ",$mtime); 
   $mtime = $mtime[1] + $mtime[0]; 
   $endtime = $mtime; 
   $totaltime = ($endtime - $starttime); 
   echo "<br>The process took ".$totaltime." seconds<br><br>"; 

?>
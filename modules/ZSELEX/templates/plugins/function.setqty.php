<?php
function smarty_function_setqty($args, &$smarty) {
	$array = $args ['sessionarr'];
	$origtype = $args ['origtype'];
	$value = 1;
	if (! empty ( $array )) {
		foreach ( $array as $key => $val ) {
			// echo $val['servicetype'].'<br>';
			if ($origtype == $val ['servicetype']) {
				$value = $val ['qty'];
				break;
			}
		}
	}
	
	// echo "<pre>"; print_r($array); echo "</pre>";
	$smarty->assign ( "qtys", $value );
}
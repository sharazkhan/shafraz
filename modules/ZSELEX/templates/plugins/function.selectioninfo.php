<?php

/*
 * Affiliate & Branch Selection Info Plugin
 *
 * @return string
 */
function smarty_function_selectioninfo($params, &$smarty) {
	// echo "helloo";
	$baseurl = pnGetBaseURL ();
	$count = 0;
	$title = '';
	$affiliate = 0;
	$branch = 0;
	if (isset ( $_REQUEST ['aff_id'] ) && count ( $_REQUEST ['aff_id'] ) > 0) {
		++ $affiliate;
		++ $count;
	}
	if (isset ( $_REQUEST ['branch_id'] ) && $_REQUEST ['branch_id'] > 0) {
		++ $branch;
		++ $count;
	}
	if ((isset ( $_REQUEST ['aff_id'] ) && count ( $_REQUEST ['aff_id'] ) > 0) && (isset ( $_REQUEST ['branch_id'] ) && $_REQUEST ['branch_id'] > 0)) {
		++ $affiliate;
		++ $branch;
		++ $count;
	}
	
	// echo 'Affiliate :' . $affiliate;
	
	if ($count) {
		if ($affiliate && ! $branch) {
			$title = $smarty->__ ( 'Affiliate selection active!' );
		} elseif (! $affiliate && $branch) {
			$title = $smarty->__ ( 'Branch selection active!' );
		} elseif ($affiliate && $branch) {
			$title = $smarty->__ ( 'Affiliates and Branch selections are active!' );
		}
		echo '<img src=' . $baseurl . "images/icons/extrasmall/info.png title='$title'>";
	}
}

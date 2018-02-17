<?php
function smarty_function_announcement($args, &$smarty) {
	$shop_id = $args ['shop_id'];
	
	if (empty ( $shop_id ) || ! ( int ) $shop_id || $_REQUEST ['module'] != 'ZSELEX') {
		return;
	}
	
	$serviceExist = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'serviceExistBlock', $args = array (
			'shop_id' => $shop_id,
			'type' => 'minisiteannouncement' 
	) );
	
	if ($serviceExist < 1) {
		return "";
	}
	$output = "";
	$announcement = ModUtil::apiFunc ( 'ZSELEX', 'user', 'get', $args = array (
			'table' => 'zselex_shop_announcement',
			'where' => "shop_id=$shop_id AND status='1'" 
	) );
	$start_date = $announcement ['start_date'];
	$end_date = $announcement ['end_date'];
	
	$todayDate = date ( "Y-m-d" );
	
	$text = $announcement ['text'];
	// $text = wordwrap($text, "35", "<br>", 1);
	$count = strlen ( $text );
	
	if (! empty ( $start_date )) {
		if ($todayDate < $start_date) {
			return;
		}
	}
	if (! empty ( $start_date )) {
		if ($end_date < $todayDate) {
			return;
		}
	}
	
	if (! empty ( $text )) {
		// echo "comes here";
		PageUtil::addVar ( 'stylesheet', 'themes/CityPilot/style/announcement.css' );
		
		$output = '<div class="SlopedText">
                    <div class="InnerText">
                   ' . nl2br ( $text ) . '
                    </div>
                  </div>';
	}
	return $output;
}
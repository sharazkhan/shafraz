<?php
function smarty_function_slidereventimages($args, &$smarty) {
	
	// print_r($args);
	$events = $args ['events'];
	
	$imageArray = '';
	
	foreach ( $events as $key => $val ) {
		$shop_id = $val ['shop_id'];
		$ownerName = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getOwner', $args = array (
				'shop_id' => $shop_id 
		) );
		$url = ModUtil::url ( 'ZSELEX', 'user', 'viewevent', array (
				'shop_id' => $val ['shop_id'],
				'eventId' => $val ['shop_event_id'] 
		) );
		$imageArray .= "['zselexdata/$ownerName/events/fullsize/$val[event_image]' , '" . $url . "' , '_new'],";
	}
	return $imageArray;
}
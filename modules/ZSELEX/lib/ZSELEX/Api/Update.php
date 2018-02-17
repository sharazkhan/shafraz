<?php
class ZSELEX_Api_Update extends ZSELEX_Api_Base_User {
	function updateEventUrls() {
		$repo = $this->entityManager->getRepository ( 'ZSELEX_Entity_Event' );
		
		$allEvents = $repo->getAll ( array (
				'entity' => 'ZSELEX_Entity_Event',
				'fields' => array (
						'a.shop_event_id',
						'a.shop_event_name' 
				) 
		)
		// 'joins' => array('JOIN a.shop b')
		 );
		foreach ( $allEvents as $event ) {
			$urltitle = strtolower ( $event ['shop_event_name'] );
			$urltitle = ZSELEX_Util::cleanTitle ( $urltitle );
			
			$args_url = array (
					'table' => 'zselex_shop_events',
					'title' => $urltitle,
					'field' => 'event_urltitle' 
			);
			$final_urltitle = ZSELEX_Controller_Admin::increment_url ( $args_url );
			$item = array (
					'event_urltitle' => $final_urltitle 
			);
			$updateUrls = $repo->updateEntity ( null, 'ZSELEX_Entity_Event', $item, array (
					'a.shop_event_id' => $event ['shop_event_id'] 
			) );
		}
		return true;
	}
	function analyzeTables() {
		// $sqls[] = "ANALYZE TABLE zselex_advertise";
		// $sqls[] = "ANALYZE TABLE zselex_advertise";
		echo "Analyze tables;";
		exit ();
		$statement = Doctrine_Manager::getInstance ()->connection ();
		$sql = "SHOW TABLES";
		$query = $statement->execute ( $sql );
		$result = $query->fetchAll ();
		// echo "<pre>"; print_r($result); echo "</pre>"; exit;
		foreach ( $result as $key => $value ) {
			// echo $value[0] . '<br>';
			$table = $value [0];
			$sql = "ANALYZE TABLE $table";
			$query = $statement->execute ( $sql );
		}
		// exit;
		return true;
	}
}

?>
<?php
class ZSELEX_Api_User extends ZSELEX_Api_Base_User {
	
	/**
	 * Get the shoplist
	 *
	 * @param type $args        	
	 * @return array of shoplist
	 */
	public function getShopList($args) {
		$append = $args ['append'];
		$joins = $args ['joins'];
		
		$sql = "SELECT a.shop_id as shop_id , a.shop_name ,a.urltitle , a.shop_info AS shop_desc , 
                 a.country_id, a.region_id,a.city_id,a.area_id,a.default_img_frm,
                 b.name , ms.configured as minishop_configured, g.image_name, a.shop_id as SID ,
                 round((sum(rating.rating)/COUNT(rating.rating)),1) as rating, COUNT(rating.rating) as votes,
                 reg.region_name,city.city_name,u.uname
                 FROM zselex_shop a 
                 $joins
	         LEFT JOIN zselex_minishop ms ON ms.shop_id=a.shop_id
                 LEFT JOIN zselex_shop_owners ow ON ow.shop_id=a.shop_id
                 LEFT JOIN users u ON u.uid=ow.user_id
                 LEFT JOIN zselex_shop_gallery g ON a.shop_id=g.shop_id AND g.defaultImg=1
		 LEFT JOIN zselex_files b ON a.shop_id=b.shop_id AND b.defaultImg=1
                 LEFT JOIN zselex_region reg ON a.region_id=reg.region_id
                 LEFT JOIN zselex_city city ON a.city_id=city.city_id
                 LEFT JOIN zselex_shop_ratings rating ON rating.shop_id=a.shop_id
                 LEFT JOIN zselex_shop_affiliation aff ON aff.aff_id=a.aff_id
                 WHERE a.shop_id IS NOT NULL AND a.status='1'" . " " . $append . " 
                 GROUP BY a.shop_id 
                 ORDER BY sum(rating.rating) DESC , city.city_name ASC , a.shop_name ASC";
		
		// echo $sql;
		$res = DBUtil::executeSQL ( $sql, $args ['start'] - 1, $args ['itemsperpage'] );
		$result = DBUtil::marshallObjects ( $res );
		
		// get the total count
		$rescount = DBUtil::executeSQL ( $sql );
		$count = $rescount->rowCount ();
		
		$returnarray = array (
				'items' => $result,
				'count' => $count 
		);
		
		return $returnarray;
	}
	
	/*
	 * Reference
	 *
	 * $joinInfo[] = array('join_table' => 'zselex_city',
	 * 'join_field' => array('city_id', 'city_name'),
	 * 'object_field_name' => array('city_ids', 'city_names'),
	 * 'compare_field_table' => 'city_id', // main table
	 * 'compare_field_join' => 'city_id');
	 *
	 * $joinInfo[] = array('join_table' => 'zselex_region',
	 * 'join_field' => array('region_id', 'region_name'),
	 * 'object_field_name' => array('region_ids', 'region_names'),
	 * 'compare_field_table' => 'region_id', // main table
	 * 'compare_field_join' => 'region_id');
	 *
	 *
	 */
	public function get($args) { // get single row.
	                             // echo "<pre>"; print_r($args); echo "</pre>"; exit;
		$items = array ();
		// Security check
		if (! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_OVERVIEW )) {
			return $items;
		}
		$table = $args ['table'];
		$tables = DBUtil::getTables ();
		$col = $tables [$table . '_column'];
		$where = $args ['where'];
		$fields = $args ['fields'];
		$item = DBUtil::selectObject ( $table, $where, $columnArray = $fields, $permFilter, null, $args ['SQLcache'] );
		return $item;
	}
	public function getById($args) { // get single row based on ID.
		$item = array ();
		// Security check
		if (! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_OVERVIEW )) {
			return $item;
		}
		$table = $args ['table'];
		$tables = DBUtil::getTables ();
		$id = $args ['idValue'];
		$idName = $args ['idName'];
		$col = $tables [$table . '_column'];
		$where = $args ['where'];
		$fields = $args ['fields'];
		$item = DBUtil::selectObjectByID ( $table, $id, $field = $idName, $columnArray = $fields, $permissionFilter = null, $categoryFilter = null, $cacheObject = true, $transformFunc = null );
		return $item;
	}
	public function getAll($args) { // get all result in array.
		$items = array ();
		// Security check
		if (! SecurityUtil::checkPermission ( 'Blocks::', '::', ACCESS_OVERVIEW )) {
			return $items;
		}
		$tablename = $args ['table'];
		$where = $args ['where'];
		$start = $args ['startnum'];
		$itemsperpage = $args ['itemsperpage'];
		$orderby = $args ['orderby'];
		$fields = $args ['fields'];
		$obj = DBUtil::selectObjectArray ( $tablename, $where, $orderby, $limitOffset = $start - 1, $limitNumRows = $itemsperpage, $assocKey = '', $permissionFilter = null, $categoryFilter = null, $columnArray = $fields, $distinct );
		$items = $obj;
		return $items;
	}
	public function getAllByJoin($args) { // get all result in array with joins
		$items = array ();
		// Security check
		if (! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_OVERVIEW )) {
			return $items;
		}
		// echo "comes here"; exit;
		$tablename = $args ['table'];
		$joinInfo = $args ['joinInfo'];
		$where = $args ['where'];
		$start = $args ['start'];
		$itemsperpage = $args ['itemsperpage'];
		$fields = $args ['fields'];
		$orderby = $args ['orderby'];
		$obj = DBUtil::selectExpandedObjectArray ( $tablename, $joinInfo, $where, $orderby, $limitOffset = $start - 1, $limitNumRows = $itemsperpage, $assocKey = '', $permissionFilter = null, $categoryFilter = null, $columnArray = $fields, $distinct );
		
		$items = $obj;
		return $items;
	}
	public function getByJoin($args) { // get single row with join
		$item = array ();
		// Security check
		if (! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_OVERVIEW )) {
			return $item;
		}
		$tablename = $args ['table'];
		$joinInfo = $args ['joinInfo'];
		$where = $args ['where'];
		$fields = $args ['fields'];
		$obj = DBUtil::selectExpandedObject ( $tablename, $joinInfo, $where, $columnArray = $fields, $permissionFilter = null, $categoryFilter = null );
		$item = $obj;
		return $item;
	}
	public function getByIdByJoin($args) { // get a row based on id with join
		$item = array ();
		// Security check
		if (! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_OVERVIEW )) {
			return $item;
		}
		$tablename = $args ['table'];
		$idValue = $args ['idValue'];
		$idName = $args ['idName'];
		$joinInfo = $args ['joinInfo'];
		$where = $args ['where'];
		$fields = $args ['fields'];
		$obj = DBUtil::selectExpandedObjectByID ( $tablename, $joinInfo, $id = $idValue, $field = $idName, $columnArray = $fields, $permissionFilter = null, $categoryFilter = null, $transformFunc = null );
		$item = $obj;
		return $item;
	}
	public function getCountByJoin($args) { // get count from table using join
		$item = array ();
		// Security check
		if (! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_OVERVIEW )) {
			return $item;
		}
		$tablename = $args ['table'];
		$joinInfo = $args ['joinInfo'];
		$idName = $args ['idName'];
		$where = $args ['where'];
		// $obj = DBUtil::selectExpandedObjectCount($tablename, $joinInfo, $where = '', $column = $idName, $distinct = false);
		$obj = DBUtil::selectExpandedObjectCount ( $tablename, $joinInfo, $where, $distinct = false, $categoryFilter = null );
		$item = $obj;
		return $item;
	}
	public function updateObject($args) {
		$item = array ();
		// Security check
		if (! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_OVERVIEW )) {
			return $item;
		}
		$table = $args ['table'];
		$idName = $args ['idName'];
		$where = $args ['where'];
		$columnArray = $args ['fields'];
		$obj = DBUtil::updateObject ( $columnArray, $table, $where, $idfield = $idName, $force, $updateid );
		$item = $obj;
		return $item;
	}
	function number2currency($args) {
		setlocale ( LC_MONETARY, "da_DK" );
		$locale = localeconv ();
		$amount = $args ['amount'];
		if (! isset ( $amount ) || $amount == '') {
			$amount = 0;
		}
		$locale ['currency_symbol'] = $args ['currency_symbol'];
		$locale ['decimal_point'] = $args ['decimal_point'];
		$locale ['thousands_sep'] = $args ['thousands_sep'];
		$precision = $args ['precision'];
		return $locale ['currency_symbol'] . "" . number_format ( $amount, $precision, $locale ['decimal_point'], $locale ['thousands_sep'] );
		
		// return $curency_money;
	}
	function getAmount($args) {
		$money = $args ['money'];
		$cleanString = preg_replace ( '/([^0-9\.,])/i', '', $money );
		$onlyNumbersString = preg_replace ( '/([^0-9])/i', '', $money );
		
		$separatorsCountToBeErased = strlen ( $cleanString ) - strlen ( $onlyNumbersString ) - 1;
		
		$stringWithCommaOrDot = preg_replace ( '/([,\.])/', '', $cleanString, $separatorsCountToBeErased );
		$removedThousendSeparator = preg_replace ( '/(\.|,)(?=[0-9]{3,}$)/', '', $stringWithCommaOrDot );
		
		return ( float ) str_replace ( ',', '.', $removedThousendSeparator );
	}
	public function getOrderDetails($args) {
		$order_id = $args ['order_id'];
		
		/*
		 * $sql = "SELECT * FROM zselex_orderitems a
		 * LEFT JOIN zselex_products b ON b.product_id=a.product_id
		 * WHERE a.order_id='" . $order_id . "'";
		 * $query = DBUtil::executeSQL($sql);
		 * $result = $query->fetchAll();
		 */
		
		$result = $this->entityManager->getRepository ( 'ZSELEX_Entity_OrderItem' )->getOrderDetails ( array (
				'order_id' => $order_id 
		) );
		return $result;
	}
	public function getExclusiveEvents() {
		$sql = "SELECT * FROM zselex_shop_events " . "WHERE exclusive=1";
		$query = DBUtil::executeSQL ( $sql );
		$result = $query->fetchAll ();
		$events = array ();
		foreach ( $result as $key => $val ) {
			$shop_id = $val ['shop_id'];
			$serviceExist = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'serviceExistBlock', $args = array (
					'shop_id' => $shop_id,
					'type' => 'exclusiveevent' 
			) );
			if ($serviceExist) {
				$events = $result;
			}
		}
		// echo "<pre>"; print_r($events); echo "</pre>";
		return $events;
	}
	public function getExclusiveEvent() {
		$area_cookie = $_COOKIE ['area_cookie'];
		$shop_cookie = $_COOKIE ['shop_cookie'];
		$branch_cookie = $_COOKIE ['branch_cookie'];
		$category_cookie = $_COOKIE ['category_cookie'];
		$region_cookie = $_COOKIE ['region_cookie'];
		$city_cookie = $_COOKIE ['city_cookie'];
		$shop_cookie = $_COOKIE ['shop_cookie'];
		$search_cookie = $_COOKIE ['search_cookie'];
		
		$modvariable = $this->getVars ();
		$country_id = $modvariable ['default_country_id'];
		// $country_id = 61;
		$region_id = $region_cookie;
		$city_id = $city_cookie;
		$area_id = $area_cookie;
		$shop_id = $shop_cookie;
		$category_id = $category_cookie;
		$branch_id = $branch_cookie;
		
		$search = $search_cookie;
		$search = ($search == $this->__ ( 'search for...' ) || $search == $this->__ ( 'search' )) ? '' : $search;
		
		$old_event_id = FormUtil::getPassedValue ( 'old_event_id', null, 'REQUEST' );
		$event_count = FormUtil::getPassedValue ( 'event_count', null, 'REQUEST' );
		
		if (! empty ( $country_id )) { // COUNTRY
			$append .= " AND a.country_id=$country_id";
		}
		
		if (! empty ( $region_id )) { // REGION
			$append .= " AND a.region_id=$region_id";
		}
		
		if (! empty ( $city_id )) { // CITY
			$append .= " AND a.city_id=$city_id";
		}
		
		if (! empty ( $area_id )) { // AREA
			$append .= " AND a.area_id=$area_id";
		}
		
		if (! empty ( $shop_id )) { // SHOP
			$append .= " AND a.shop_id=$shop_id";
		}
		if (! empty ( $category_id )) {
			// $append .= " AND a.cat_id=$category_id";
			$append .= " AND a.shop_id IN(SELECT shop_id FROM zselex_shop_to_category WHERE category_id=$category_id) ";
		}
		
		if (! empty ( $branch_id )) {
			$append .= " AND a.branch_id=$branch_id";
		}
		
		if (! empty ( $search )) {
			$append .= " AND (b.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE '%" . DataUtil::formatForStore ( $search ) . "%') OR b.shop_id IN (SELECT shop_id FROM zselex_shop WHERE shop_name LIKE '%" . DataUtil::formatForStore ( $search ) . "%'))";
		}
		
		$sql = "SELECT a.shop_id
                        FROM zselex_shop a , zselex_shop_events b
                        WHERE a.shop_id IS NOT NULL
                        AND a.shop_id = b.shop_id
                        AND a.status='1' $append";
		// echo $sql; exit;
		$query = DBUtil::executeSQL ( $sql );
		// $shop_count = $query->rowCount();
		$all_shops = $query->fetchAll ();
		
		foreach ( $all_shops as $key => $val ) {
			$shop_id = $val ['shop_id'];
			$serviceExist = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'serviceExistBlock', $args = array (
					'shop_id' => $shop_id,
					'type' => 'exclusiveevent' 
			) );
			if ($serviceExist) {
				$shopIdArr [] = $val ['shop_id'];
			}
		}
		$shop_count = sizeof ( $shopIdArr );
		if ($shop_count < 1) {
			return;
			$output ['noresult'] = 1;
			// AjaxUtil::output($output);
		}
		$shopIdArr = array_unique ( $shopIdArr );
		$shopIds = implode ( ',', $shopIdArr );
		
		// echo "<pre>"; print_r($shopIdArr); echo "</pre>";
		// echo $shopIds;
		$data = '';
		// $output_test = "helloo world";
		
		$extra = '';
		if (! empty ( $old_event_id ) && $event_count > 1) {
			$extra = " AND shop_event_id!='" . $old_event_id . "'";
		}
		
		$sql = "SELECT event_image , shop_id , shop_event_id
          FROM zselex_shop_events
          WHERE shop_id IN($shopIds) AND (shop_event_startdate >=CURDATE() OR shop_event_startdate <=CURDATE() ) AND shop_event_enddate >=CURDATE() AND status=1 AND exclusive=1
          $extra ORDER BY RAND()";
		
		// echo $sql;
		$output ['sql'] = $sql;
		$query = DBUtil::executeSQL ( $sql );
		$count = $query->rowCount ();
		if ($count < 1) {
			$output ['noresult'] = 1;
			return;
			// AjaxUtil::output($output);
		}
		$res = $query->fetch ();
		$output ['image'] = $res ['event_image'];
		$ownerName = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getOwner', $args = array (
				'shop_id' => $res [shop_id] 
		) );
		$_SESSION ['old_event_id'] = $res ['shop_event_id'];
		$output ['old_event_id'] = $res ['shop_event_id'];
		$output ['event_count'] = $count;
		$event_url = ModUtil::url ( 'ZSELEX', 'user', 'viewevent', array (
				'shop_id' => $res ['shop_id'],
				'eventId' => $res ['shop_event_id'] 
		) );
		$output ['show'] = "<a href=$event_url><img src='zselexdata/$ownerName/events/fullsize/$res[event_image]'></a>";
		$output ['image'] = "<img class='exclEvntImg' src='zselexdata/$ownerName/events/fullsize/$res[event_image]' class='active' onClick=window.location.href='$event_url'>";
		return $output;
	}
	public function getUserInfo($args) {
		
		// echo "<pre>"; print_r($args); echo "</pre>";
		// return true;
		$where = $args ['where'];
		$query = "SELECT value             
                   FROM objectdata_attributes  
                   WHERE " . $where;
		
		// echo $query . '<br>';
		$statement = Doctrine_Manager::getInstance ()->connection ();
		$results = $statement->execute ( $query );
		$values = $results->fetch ();
		return $values;
	}
	public function getOwnerRealName($args) {
		// echo "<pre>"; print_r($args); echo "</pre>"; exit;
		// return true;
		$user = $this->entityManager->getRepository ( 'ZSELEX_Entity_ShopOwner' )->getOwnerInfo ( $args );
		// echo "<pre>"; print_r($user); echo "</pre>";
		$user_id = $user ['uid'];
		if ($user_id) {
			$fn = ModUtil::apiFunc ( 'ZSELEX', 'user', 'getUserInfo', $args = array (
					'where' => "object_id=$user_id AND object_type='users' AND (attribute_name='first_name')" 
			) );
			// echo "<pre>"; print_r($fn); echo "</pre>";
			$ln = ModUtil::apiFunc ( 'ZSELEX', 'user', 'getUserInfo', $args = array (
					'where' => "object_id=$user_id AND object_type='users'AND (attribute_name='last_name')" 
			) );
			
			// if (!$fn || empty($fn)) {
			if (! $fn) {
				return $user ['uname'];
			}
			
			return $fn ['value'] . ' ' . $ln ['value'];
		}
	}
}

?>
<?php

/**
 * Copyright  2012 - ZSELEX
 *
 * ZSELEX
 * Shopping Exchange Module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control User interface
 */
class ZSELEX_Api_Block extends Zikula_AbstractApi {
	public function getGalleryBlockImages($args) {
		$sql = "SELECT * FROM  zselex_shop_gallery a
                LEFT JOIN zselex_shop b ON a.shop_id=b.shop_id
                WHERE a.shop_id='" . $args [shop_id] . "' ORDER BY a.defaultImg DESC";
		$res = DBUtil::executeSQL ( $sql );
		$result = DBUtil::marshallObjects ( $res );
		
		return $result;
	}
}

// end class def
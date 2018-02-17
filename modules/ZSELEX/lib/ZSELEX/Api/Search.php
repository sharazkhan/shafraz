<?php

/**
 * Zikula Application Framework
 *
 * @copyright  (c) Zikula Development Team
 * @link       http://www.zikula.org
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @author     Mark West <mark@zikula.org>
 * @category   Zikula_3rdParty_Modules
 * @package    Content_Management
 * @subpackage News
 */
class ZSELEX_Api_Search extends Zikula_AbstractApi {
	
	/**
	 * Search plugin info
	 */
	/*
	 * public function info() {
	 * // echo "helloo";
	 * $array = array();
	 * $array = array('title' => 'ZSELEX',
	 * 'functions' => array('0' => 'search',
	 * '1' => 'searchproducts',
	 * '2' => 'searchMiniSiteImages',
	 * '3' => 'searchGalleryImages',
	 * '4' => 'searchzencartproducts'));
	 * // return array('title' => 'ZSELEX',
	 * // 'functions' => array('ZSELEX' => 'search' , 'ZSELEX' => 'searchproducts'));
	 * //echo "<pre>"; print_r($array); echo "</pre>";
	 * return $array;
	 * }
	 *
	 */
	public function info() {
		$array = array ();
		$array = array (
				'title' => 'ZSELEX',
				'functions' => array (
						'0' => 'search',
						'1' => 'searchproducts',
						'2' => 'searchMiniSiteImages',
						'3' => 'searchGalleryImages',
						'4' => 'searchzencartproducts',
						'5' => 'searchShopPdf',
						'6' => 'searchProductAds',
						'7' => 'searchDotd',
						'8' => 'searchEvents' 
				) 
		);
		
		return $array;
	}
	
	/**
	 * Search form component
	 */
	public function options($args) {
		if (SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_READ )) {
			// Create output object - this object will store all of our output so that
			// we can return it easily when required
			$render = Zikula_View::getInstance ( 'ZSELEX' );
			$render->assign ( 'active', (isset ( $args ['active'] ) && isset ( $args ['active'] ['ZSELEX'] )) || (! isset ( $args ['active'] )) );
			return $render->fetch ( 'search/options.tpl' );
		}
		
		return '';
	}
	
	/**
	 * Search plugin main function
	 */
	public function search($args) {
		// echo "<pre>"; print_r($args); echo "</pre>";
		if (! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_READ )) {
			return true;
		}
		
		ModUtil::dbInfoLoad ( 'Search' );
		$tables = DBUtil::getTables ();
		
		$kewrd = $args ['q'];
		// echo "<pre>"; print_r($tables); echo "</pre>";
		$newsColumn = $tables ['zselex_shop_column'];
		
		$where = Search_Api_User::construct_where ( $args, array (
				$newsColumn ['shop_name'],
				$newsColumn ['urltitle'],
				$newsColumn ['description'],
				$newsColumn ['address'] 
		) );
		// Only search in published articles that are currently visible
		// echo "<pre>"; print_r($where); echo "</pre>";
		// $where .= " AND ({$newsColumn['published_status']} = '0')";
		$date = DateUtil::getDatetime ();
		// $where .= " AND ('$date' >= {$newsColumn['from']} AND ({$newsColumn['to']} IS NULL OR '$date' <= {$newsColumn['to']}))";
		// $where .= " OR (shop_id IN (SELECT shop_id FROM zselex_files WHERE filedescription LIKE '$kewrd'))";
		// $where .= " OR (shop_id IN (SELECT shop_id FROM zselex_shop_gallery WHERE image_description LIKE '$kewrd'))";
		
		$sessionId = session_id ();
		
		ModUtil::loadApi ( 'ZSELEX', 'user' );
		
		$permChecker = new News_ResultChecker ( $this->getVar ( 'enablecategorization' ), $this->getVar ( 'enablecategorybasedpermissions' ) );
		$shops = DBUtil::selectObjectArrayFilter ( 'zselex_shop', $where, null, null, null, '', $permChecker, null );
		
		// $shops = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray', array('table' => 'zselex_shop a , zselex_files f , zselex_shop_gallery g',
		// 'where' => array("a.urltitle LIKE '%$kewrd%' OR a.description LIKE '%$kewrd%' OR a.address LIKE '%$kewrd%' OR f.filedescription LIKE '%$kewrd%'
		// OR g.image_description LIKE '%$kewrd%'")
		// ));
		
		foreach ( $shops as $shop ) {
			$item = array (
					'title' => $shop ['shop_name'],
					'text' => $shop ['description'] . " " . $shop ['filedescription'] . "" . $shop ['image_description'],
					'extra' => $shop ['shop_id'] . "," . 'shop',
					'created' => $shop ['cr_date'],
					'module' => 'ZSELEX',
					'session' => $sessionId 
			);
			
			$insertResult = DBUtil::insertObject ( $item, 'search_result' );
			if (! $insertResult) {
				return LogUtil::registerError ( $this->__ ( 'Error! Could not load any articles.' ) );
			}
		}
		
		return true;
	}
	
	/**
	 * Search plugin main function
	 */
	public function searchproducts($args) {
		// echo "<pre>"; print_r($args); echo "</pre>";
		if (! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_READ )) {
			return true;
		}
		
		ModUtil::dbInfoLoad ( 'Search' );
		$tables = DBUtil::getTables ();
		
		$kewrd = $args ['q'];
		// echo "<pre>"; print_r($tables); echo "</pre>";
		$newsColumn = $tables ['zselex_products_column'];
		
		$where = Search_Api_User::construct_where ( $args, array (
				$newsColumn ['product_name'],
				$newsColumn ['urltitle'],
				$newsColumn ['prd_description'],
				$newsColumn ['prd_price'] 
		) );
		// Only search in published articles that are currently visible
		// echo "<pre>"; print_r($where); echo "</pre>";
		// $where .= " AND ({$newsColumn['published_status']} = '0')";
		$date = DateUtil::getDatetime ();
		// $where .= " AND ('$date' >= {$newsColumn['from']} AND ({$newsColumn['to']} IS NULL OR '$date' <= {$newsColumn['to']}))";
		
		$sessionId = session_id ();
		
		ModUtil::loadApi ( 'ZSELEX', 'user' );
		
		$permChecker = new News_ResultChecker ( $this->getVar ( 'enablecategorization' ), $this->getVar ( 'enablecategorybasedpermissions' ) );
		$iproducts = DBUtil::selectObjectArrayFilter ( 'zselex_products', $where, null, null, null, '', $permChecker, null );
		
		// echo "<pre>"; print_r($iproducts); echo "</pre>";
		foreach ( $iproducts as $iproduct ) {
			$item = array (
					'title' => $iproduct ['product_name'],
					'text' => $iproduct ['prd_description'],
					'extra' => $iproduct ['product_id'] . "," . 'product',
					'created' => $iproduct ['cr_date'],
					'module' => 'ZSELEX',
					'session' => $sessionId 
			);
			
			$insertResult = DBUtil::insertObject ( $item, 'search_result' );
			if (! $insertResult) {
				return LogUtil::registerError ( $this->__ ( 'Error! Could not load any articles.' ) );
			}
		}
		
		return true;
	}
	
	/**
	 * Search plugin main function
	 */
	public function searchMiniSiteImages($args) {
		// echo "<pre>"; print_r($args); echo "</pre>";
		if (! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_READ )) {
			return true;
		}
		
		ModUtil::dbInfoLoad ( 'Search' );
		$tables = DBUtil::getTables ();
		
		$kewrd = $args ['q'];
		// echo "<pre>"; print_r($tables); echo "</pre>";
		$newsColumn = $tables ['zselex_files_column'];
		
		$where = Search_Api_User::construct_where ( $args, array (
				$newsColumn ['filedescription'],
				$newsColumn ['keywords'] 
		) );
		// Only search in published articles that are currently visible
		// echo "<pre>"; print_r($where); echo "</pre>";
		// $where .= " AND ({$newsColumn['published_status']} = '0')";
		$date = DateUtil::getDatetime ();
		// $where .= " AND ('$date' >= {$newsColumn['from']} AND ({$newsColumn['to']} IS NULL OR '$date' <= {$newsColumn['to']}))";
		
		$sessionId = session_id ();
		
		ModUtil::loadApi ( 'ZSELEX', 'user' );
		
		$permChecker = new News_ResultChecker ( $this->getVar ( 'enablecategorization' ), $this->getVar ( 'enablecategorybasedpermissions' ) );
		$simages = DBUtil::selectObjectArrayFilter ( 'zselex_files', $where, null, null, null, '', $permChecker, null );
		
		// echo "<pre>"; print_r($simages); echo "</pre>"; exit;
		foreach ( $simages as $image ) {
			$item = array (
					'title' => $image ['filedescription'],
					'text' => $image ['filedescription'],
					'extra' => $image ['shop_id'] . "," . 'minisiteimage',
					'created' => '',
					'module' => 'ZSELEX',
					'session' => $sessionId 
			);
			
			$insertResult = DBUtil::insertObject ( $item, 'search_result' );
			if (! $insertResult) {
				return LogUtil::registerError ( $this->__ ( 'Error! Could not load any articles.' ) );
			}
		}
		
		return true;
	}
	public function searchGalleryImages($args) { // search shop gallery images
	                                             // echo "<pre>"; print_r($args); echo "</pre>";
		if (! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_READ )) {
			return true;
		}
		
		ModUtil::dbInfoLoad ( 'Search' );
		$tables = DBUtil::getTables ();
		
		$kewrd = $args ['q'];
		// echo "<pre>"; print_r($tables); echo "</pre>";
		$newsColumn = $tables ['zselex_shop_gallery_column'];
		
		$where = Search_Api_User::construct_where ( $args, array (
				$newsColumn ['image_description'],
				$newsColumn ['keywords'] 
		) );
		// Only search in published articles that are currently visible
		// echo "<pre>"; print_r($where); echo "</pre>";
		// $where .= " AND ({$newsColumn['published_status']} = '0')";
		$date = DateUtil::getDatetime ();
		// $where .= " AND ('$date' >= {$newsColumn['from']} AND ({$newsColumn['to']} IS NULL OR '$date' <= {$newsColumn['to']}))";
		
		$sessionId = session_id ();
		
		ModUtil::loadApi ( 'ZSELEX', 'user' );
		
		$permChecker = new News_ResultChecker ( $this->getVar ( 'enablecategorization' ), $this->getVar ( 'enablecategorybasedpermissions' ) );
		$gimages = DBUtil::selectObjectArrayFilter ( 'zselex_shop_gallery', $where, null, null, null, '', $permChecker, null );
		
		// echo "<pre>"; print_r($gimages); echo "</pre>"; exit;
		foreach ( $gimages as $gimage ) {
			$item = array (
					'title' => $gimage ['image_description'],
					'text' => $gimage ['image_description'],
					'extra' => $gimage ['shop_id'] . "," . 'galleryimage',
					'created' => '',
					'module' => 'ZSELEX',
					'session' => $sessionId 
			);
			
			$insertResult = DBUtil::insertObject ( $item, 'search_result' );
			if (! $insertResult) {
				return LogUtil::registerError ( $this->__ ( 'Error! Could not load any articles.' ) );
			}
		}
		
		return true;
	}
	public function searchShopPdf($args) {
		if (! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_READ )) {
			return true;
		}
		
		ModUtil::dbInfoLoad ( 'Search' );
		$tables = DBUtil::getTables ();
		
		$kewrd = $args ['q'];
		// echo "<pre>"; print_r($tables); echo "</pre>";
		$pdfColumn = $tables ['zselex_shop_pdf_column'];
		
		$where = Search_Api_User::construct_where ( $args, array (
				$pdfColumn ['pdf_description'],
				$pdfColumn ['keywords'] 
		) );
		// Only search in published articles that are currently visible
		// echo "<pre>"; print_r($where); echo "</pre>";
		// $where .= " AND ({$newsColumn['published_status']} = '0')";
		$date = DateUtil::getDatetime ();
		// $where .= " AND ('$date' >= {$newsColumn['from']} AND ({$newsColumn['to']} IS NULL OR '$date' <= {$newsColumn['to']}))";
		
		$sessionId = session_id ();
		
		ModUtil::loadApi ( 'ZSELEX', 'user' );
		
		$permChecker = new News_ResultChecker ( $this->getVar ( 'enablecategorization' ), $this->getVar ( 'enablecategorybasedpermissions' ) );
		$pdf_images = DBUtil::selectObjectArrayFilter ( 'zselex_shop_pdf', $where, null, null, null, '', $permChecker, null );
		
		// echo "<pre>"; print_r($gimages); echo "</pre>"; exit;
		foreach ( $pdf_images as $pdf_image ) {
			$item = array (
					'title' => $pdf_image ['pdf_description'],
					'text' => $pdf_image ['pdf_description'],
					'extra' => $pdf_image ['shop_id'] . "," . 'shoppdf',
					'created' => '',
					'module' => 'ZSELEX',
					'session' => $sessionId 
			);
			
			$insertResult = DBUtil::insertObject ( $item, 'search_result' );
			if (! $insertResult) {
				return LogUtil::registerError ( $this->__ ( 'Error! Could not load any articles.' ) );
			}
		}
		
		return true;
	}
	public function searchShopPdf_check($args) {
		// echo "<pre>"; print_r($args); echo "</pre>"; exit;
		// echo "come here products.....";
		// $datarow['url'] = '';
		$datarow = & $args ['datarow'];
		// echo "<pre>"; print_r($datarow); echo "</pre>";
		$shop_id = $datarow ['extra'];
		// $datarow['type'] = 'product';
		// echo $product_id . '<br>';
		// echo $datarow['url'];
		$datarow ['url'] = ModUtil::url ( 'ZSELEX', 'user', 'shop', array (
				'id' => $shop_id 
		) );
		$datarow ['shoppdfurl'] = ModUtil::url ( 'ZSELEX', 'user', 'shop', array (
				'id' => $shop_id 
		) );
		
		// echo "<pre>"; print_r($datarow); echo "</pre>"; exit;
		// echo $datarow['url']; exit;
		
		return true;
	}
	public function searchProductAds($args) {
		if (! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_READ )) {
			return true;
		}
		
		ModUtil::dbInfoLoad ( 'Search' );
		$tables = DBUtil::getTables ();
		
		$kewrd = $args ['q'];
		// echo "<pre>"; print_r($tables); echo "</pre>";
		$prodAdColumn = $tables ['zselex_advertise_column'];
		
		$where = Search_Api_User::construct_where ( $args, array (
				$prodAdColumn ['name'],
				$prodAdColumn ['description'],
				$prodAdColumn ['keywords'] 
		) );
		// Only search in published articles that are currently visible
		// echo "<pre>"; print_r($where); echo "</pre>";
		// $where .= " AND ({$newsColumn['published_status']} = '0')";
		$date = DateUtil::getDatetime ();
		// $where .= " AND ('$date' >= {$newsColumn['from']} AND ({$newsColumn['to']} IS NULL OR '$date' <= {$newsColumn['to']}))";
		
		$sessionId = session_id ();
		
		ModUtil::loadApi ( 'ZSELEX', 'user' );
		
		$permChecker = new News_ResultChecker ( $this->getVar ( 'enablecategorization' ), $this->getVar ( 'enablecategorybasedpermissions' ) );
		$prodAds = DBUtil::selectObjectArrayFilter ( 'zselex_advertise', $where, null, null, null, '', $permChecker, null );
		
		// echo "<pre>"; print_r($prodAds); echo "</pre>"; exit;
		foreach ( $prodAds as $prodAd ) {
			$item = array (
					'title' => $prodAd ['name'],
					'text' => $prodAd ['description'],
					'extra' => $prodAd ['shop_id'] . "," . 'productad',
					'created' => '',
					'module' => 'ZSELEX',
					'session' => $sessionId 
			);
			
			$insertResult = DBUtil::insertObject ( $item, 'search_result' );
			if (! $insertResult) {
				return LogUtil::registerError ( $this->__ ( 'Error! Could not load any articles.' ) );
			}
		}
		
		return true;
	}
	public function searchProductAds_check($args) {
		// echo "<pre>"; print_r($args); echo "</pre>"; exit;
		// echo "come here products.....";
		// $datarow['url'] = '';
		$datarow = & $args ['datarow'];
		// echo "<pre>"; print_r($datarow); echo "</pre>";
		$shop_id = $datarow ['extra'];
		// $datarow['type'] = 'product';
		// echo $product_id . '<br>';
		// echo $datarow['url'];
		$datarow ['url'] = ModUtil::url ( 'ZSELEX', 'user', 'shop', array (
				'id' => $shop_id 
		) );
		$datarow ['productadurl'] = ModUtil::url ( 'ZSELEX', 'user', 'shop', array (
				'id' => $shop_id 
		) );
		
		// echo "<pre>"; print_r($datarow); echo "</pre>"; exit;
		// echo $datarow['url']; exit;
		
		return true;
	}
	public function searchDotd($args) {
		if (! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_READ )) {
			return true;
		}
		
		ModUtil::dbInfoLoad ( 'Search' );
		$tables = DBUtil::getTables ();
		
		$kewrd = $args ['q'];
		// echo "<pre>"; print_r($tables); echo "</pre>";
		$dotdColumn = $tables ['zselex_dotd_column'];
		
		$where = Search_Api_User::construct_where ( $args, array (
				$dotdColumn ['dotd_name'],
				$dotdColumn ['keywords'] 
		) );
		// Only search in published articles that are currently visible
		// echo "<pre>"; print_r($where); echo "</pre>";
		// $where .= " AND ({$newsColumn['published_status']} = '0')";
		$date = DateUtil::getDatetime ();
		// $where .= " AND ('$date' >= {$newsColumn['from']} AND ({$newsColumn['to']} IS NULL OR '$date' <= {$newsColumn['to']}))";
		
		$sessionId = session_id ();
		
		ModUtil::loadApi ( 'ZSELEX', 'user' );
		
		$permChecker = new News_ResultChecker ( $this->getVar ( 'enablecategorization' ), $this->getVar ( 'enablecategorybasedpermissions' ) );
		$dotds = DBUtil::selectObjectArrayFilter ( 'zselex_dotd', $where, null, null, null, '', $permChecker, null );
		
		// echo "<pre>"; print_r($prodAds); echo "</pre>"; exit;
		foreach ( $dotds as $dotd ) {
			$item = array (
					'title' => $dotd ['dotd_name'],
					'text' => $dotd ['keywords'],
					'extra' => $prodAd ['shop_id'] . "," . 'dotd',
					'created' => '',
					'module' => 'ZSELEX',
					'session' => $sessionId 
			);
			
			$insertResult = DBUtil::insertObject ( $item, 'search_result' );
			if (! $insertResult) {
				return LogUtil::registerError ( $this->__ ( 'Error! Could not load any articles.' ) );
			}
		}
		
		return true;
	}
	public function searchDotd_check($args) {
		// echo "<pre>"; print_r($args); echo "</pre>"; exit;
		// echo "come here products.....";
		// $datarow['url'] = '';
		$datarow = & $args ['datarow'];
		// echo "<pre>"; print_r($datarow); echo "</pre>";
		$shop_id = $datarow ['extra'];
		// $datarow['type'] = 'product';
		// echo $product_id . '<br>';
		// echo $datarow['url'];
		$datarow ['url'] = ModUtil::url ( 'ZSELEX', 'user', 'shop', array (
				'id' => $shop_id 
		) );
		$datarow ['dotdurl'] = ModUtil::url ( 'ZSELEX', 'user', 'shop', array (
				'id' => $shop_id 
		) );
		
		// echo "<pre>"; print_r($datarow); echo "</pre>"; exit;
		// echo $datarow['url']; exit;
		
		return true;
	}
	
	/**
	 * Search plugin main function
	 */
	public function searchzencartproducts($args) {
		// echo "<pre>"; print_r($args); echo "</pre>"; exit;
		if (! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_READ )) {
			return true;
		}
		
		ModUtil::dbInfoLoad ( 'Search' );
		// $tables = DBUtil::getTables();
		
		$kewrd = $args ['q'];
		// echo "<pre>"; print_r($tables); echo "</pre>";
		
		$shops = ModUtil::apiFunc ( 'ZSELEX', 'user', 'selectArray', array (
				'table' => 'zselex_shop s , zselex_minishop m , zselex_zenshop z',
				'where' => array (
						"s.shop_id=m.shop_id",
						"m.shoptype='zSHOP'",
						"m.configured='1'",
						"m.shop_id=z.shop_id",
						"s.status=1" 
				) 
		) );
		
		$count = count ( $shops );
		// echo "count : " . $count; exit;
		// Only search in published articles that are currently visible
		// echo "<pre>"; print_r($shops); echo "</pre>"; exit;
		// $where .= " AND ({$newsColumn['published_status']} = '0')";
		$date = DateUtil::getDatetime ();
		// $where .= " AND ('$date' >= {$newsColumn['from']} AND ({$newsColumn['to']} IS NULL OR '$date' <= {$newsColumn['to']}))";
		
		$sessionId = session_id ();
		
		ModUtil::loadApi ( 'ZSELEX', 'user' );
		// $allValues = array();
		
		if ($count > 0) {
			foreach ( $shops as $shop ) {
				// echo $shop['dbname'] . '<br>';
				
				$dnName = (! empty ( $shop ['dbname'] ) ? $shop ['dbname'] : '');
				$dnUser = (! empty ( $shop ['username'] ) ? $shop ['username'] : 'root');
				$dbPswrd = (! empty ( $shop ['password'] ) ? $shop ['password'] : '');
				$dbHost = (! empty ( $shop ['hostname'] ) ? $shop ['hostname'] : 'localhost');
				
				$dsn = "mysql:dbname=$dnName;host=$dbHost";
				$user = $dnUser;
				$password = $dbPswrd;
				$tableprefix = (! empty ( $shop ['table_prefix'] ) ? $shop ['table_prefix'] : '');
				/*
				 * $prdquery = "SELECT a.products_id , a.products_date_added, a.products_image , a.products_price , LEFT(b.products_name, 50) AS products_name , LEFT(b.products_description, 50) AS products_description,
				 * mn.manufacturers_name
				 * FROM " . $tableprefix . "products a
				 * LEFT JOIN " . $tableprefix . "products_description b ON b.products_id=a.products_id
				 * LEFT JOIN " . $tableprefix . "manufacturers mn ON mn.manufacturers_id=a.manufacturers_id
				 * WHERE a.products_status=1 AND b.products_name!='' AND a.products_image!='' AND a.manufacturers_id!='' AND b.products_name LIKE '%$kewrd%'
				 * OR b.products_description LIKE '%$kewrd%' OR mn.manufacturers_name LIKE '%$kewrd%'
				 * group by a.products_id order by b.products_name
				 * ";
				 */
				
				// $prdquery = "SELECT DISTINCT LEFT(pd.products_name, 20) AS products_name,LEFT(pd.products_description, 20) AS products_description , p.products_image, p.products_quantity , m.manufacturers_id, p.products_id, pd.products_name,
				// p.products_price, p.products_tax_class_id, p.products_price_sorter, p.products_qty_box_status,
				// p.master_categories_id
				// FROM (" . $tableprefix . "products p LEFT JOIN " . $tableprefix . "manufacturers m USING(manufacturers_id), " . $tableprefix . "products_description pd, " . $tableprefix . "categories c, " . $tableprefix . "products_to_categories p2c )
				// LEFT JOIN " . $tableprefix . "meta_tags_products_description mtpd ON mtpd.products_id= p2c.products_id AND mtpd.language_id = 1
				// WHERE (p.products_status = 1 AND p.products_id = pd.products_id AND pd.language_id = 1 AND p.products_id = p2c.products_id AND p2c.categories_id = c.categories_id AND
				// ((pd.products_name LIKE '%$kewrd%' OR p.products_model LIKE '%$kewrd%' OR m.manufacturers_name LIKE '%$kewrd%' OR (mtpd.metatags_keywords LIKE '%$kewrd%' AND mtpd.metatags_keywords !='')
				// OR (mtpd.metatags_description LIKE '%$kewrd%' AND mtpd.metatags_description !='') OR pd.products_description LIKE '%$kewrd%') ))
				// order by p.products_sort_order, pd.products_name";
				// $prdquery = "SELECT DISTINCT LEFT(pd.products_name, 20) AS products_name,LEFT(pd.products_description, 20) AS products_description, p.products_image, p.products_quantity , m.manufacturers_id, p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_price_sorter,
				// p.products_qty_box_status, p.master_categories_id FROM (" . $tableprefix . "products p
				// LEFT JOIN " . $tableprefix . "manufacturers m USING(manufacturers_id), " . $tableprefix . "products_description pd, " . $tableprefix . "categories c, " . $tableprefix . "products_to_categories p2c )
				// LEFT JOIN " . $tableprefix . "meta_tags_products_description mtpd ON mtpd.products_id= p2c.products_id AND mtpd.language_id = 1
				// WHERE (p.products_status = 1 AND p.products_id = pd.products_id AND pd.language_id = 1 AND p.products_id = p2c.products_id AND p2c.categories_id = c.categories_id
				// AND ((pd.products_name LIKE '%$kewrd%' OR p.products_model LIKE '%$kewrd%' OR m.manufacturers_name LIKE '%$kewrd%' OR
				// (mtpd.metatags_keywords LIKE '%$kewrd%' AND mtpd.metatags_keywords !='')
				// OR (mtpd.metatags_description LIKE '%$kewrd%' AND mtpd.metatags_description !='')
				// OR pd.products_description LIKE '%$kewrd%') ))
				// order by p.products_sort_order, pd.products_name";
				
				$prdquery = "SELECT DISTINCT LEFT(pd.products_name, 20) AS products_name,
                                LEFT(pd.products_description, 60) AS products_description,
                                p.products_image,
                                m.manufacturers_name,
                                p.products_quantity ,
                                m.manufacturers_id,
                                p.products_id,
                                pd.products_name,
                                p.products_price,
                                p.products_tax_class_id,
                                p.products_price_sorter,
                                p.products_qty_box_status,
                                p.master_categories_id
                FROM (" . $tableprefix . "products p
                      LEFT JOIN " . $tableprefix . "manufacturers m USING(manufacturers_id),
                                                                  " . $tableprefix . "products_description pd,
                                                                                                           " . $tableprefix . "categories c,
                                                                                                                                          " . $tableprefix . "products_to_categories p2c)
                LEFT JOIN " . $tableprefix . "meta_tags_products_description mtpd ON mtpd.products_id= p2c.products_id
                AND mtpd.language_id = 2
                WHERE (p.products_status = 1
                       AND p.products_id = pd.products_id
                       AND pd.language_id = 2
                       AND p.products_id = p2c.products_id
                       AND p2c.categories_id = c.categories_id
                       AND ((pd.products_name LIKE '%$kewrd%'
                             OR p.products_model LIKE '%$kewrd%'
                             OR m.manufacturers_name LIKE '%$kewrd%'
                             OR (mtpd.metatags_keywords LIKE '%$kewrd%'
                                 AND mtpd.metatags_keywords !='')
                             OR (mtpd.metatags_description LIKE '%$kewrd%'
                                 AND mtpd.metatags_description !='')
                             OR pd.products_description LIKE '%$kewrd%')))
                ORDER BY p.products_sort_order,
                         pd.products_name";
				
				$dbh = new PDO ( $dsn, $user, $password );
				$statement1 = Doctrine_Manager::getInstance ()->connection ( $dbh );
				$results = $statement1->execute ( $prdquery );
				$sValues = $results->fetchAll ();
				// $allValues[] = $sValues;
				
				for($i = 0; $i < count ( $sValues ); $i ++) {
					
					$sValues [$i] ['domain'] = $shop ['domain'];
				}
				$allValues [] = $sValues;
			}
			
			$statement1 = Doctrine_Manager::getInstance ()->closeConnection ( $statement1 );
			
			// echo "<pre>"; print_r($allValues); echo "</pre>";
			// echo count($allValues);
			
			foreach ( $allValues as $zproduct ) {
				
				foreach ( $zproduct as $val ) {
					// echo $val[products_id] . '<br>';
					
					$item = array (
							'title' => $val ['products_name'] . "  " . $val ['manufacturers_name'],
							'text' => $val ['products_description'],
							'extra' => $val ['products_id'] . "," . 'zproduct' . "," . $val ['domain'],
							'created' => $val ['products_date_added'],
							'module' => 'ZSELEX',
							'session' => $sessionId 
					);
					
					$insertResult = DBUtil::insertObject ( $item, 'search_result' );
					
					if (! $insertResult) {
						return LogUtil::registerError ( $this->__ ( 'Error! Could not load any articles.' ) );
					}
				}
			}
			// exit;
		}
		
		return true;
	}
	
	/**
	 * Do last minute access checking and assign URL to items
	 */
	public function search_check($args) {
		// echo "<pre>"; print_r($args); echo "</pre>";
		// echo "come here shops.....";
		// echo $datarow['url'];
		// $datarow['url'] = '';
		$datarow = & $args ['datarow'];
		$shop_id = $datarow ['extra'];
		// echo $shop_id . '<br>';
		// $datarow['type'] = 'shop';
		$datarow ['url'] = ModUtil::url ( 'ZSELEX', 'user', 'site', array (
				'id' => $shop_id 
		) );
		$datarow ['shopurl'] = ModUtil::url ( 'ZSELEX', 'user', 'site', array (
				'id' => $shop_id 
		) );
		// echo $datarow['url']; exit;
		return true;
	}
	public function searchproducts_check($args) {
		// echo "<pre>"; print_r($args); echo "</pre>";
		// echo "come here products.....";
		// $datarow['url'] = '';
		$datarow = & $args ['datarow'];
		// echo "<pre>"; print_r($datarow); echo "</pre>";
		$product_id = $datarow ['extra'];
		// $datarow['type'] = 'product';
		// echo $product_id . '<br>';
		// echo $datarow['url'];
		$datarow ['url'] = ModUtil::url ( 'ZSELEX', 'user', 'productview', array (
				'id' => $product_id 
		) );
		$datarow ['producturl'] = ModUtil::url ( 'ZSELEX', 'user', 'productview', array (
				'id' => $product_id 
		) );
		
		// echo $datarow['url']; exit;
		
		return true;
	}
	public function searchMiniSiteImages_check($args) {
		// echo "<pre>"; print_r($args); echo "</pre>"; exit;
		// echo "come here products.....";
		// $datarow['url'] = '';
		$datarow = & $args ['datarow'];
		// echo "<pre>"; print_r($datarow); echo "</pre>";
		$shop_id = $datarow ['extra'];
		// $datarow['type'] = 'product';
		// echo $product_id . '<br>';
		// echo $datarow['url'];
		$datarow ['url'] = ModUtil::url ( 'ZSELEX', 'user', 'site', array (
				'id' => $shop_id 
		) );
		$datarow ['minisiteimageurl'] = ModUtil::url ( 'ZSELEX', 'user', 'site', array (
				'id' => $shop_id 
		) );
		
		// echo "<pre>"; print_r($datarow); echo "</pre>"; exit;
		// echo $datarow['url']; exit;
		
		return true;
	}
	public function searchGalleryImages_check($args) {
		// echo "<pre>"; print_r($args); echo "</pre>"; exit;
		// echo "come here products.....";
		// $datarow['url'] = '';
		$datarow = & $args ['datarow'];
		// echo "<pre>"; print_r($datarow); echo "</pre>";
		$shop_id = $datarow ['extra'];
		// $datarow['type'] = 'product';
		// echo $product_id . '<br>';
		// echo $datarow['url'];
		$datarow ['url'] = ModUtil::url ( 'ZSELEX', 'user', 'shop', array (
				'id' => $shop_id 
		) );
		$datarow ['galleryimageurl'] = ModUtil::url ( 'ZSELEX', 'user', 'shop', array (
				'id' => $shop_id 
		) );
		
		// echo "<pre>"; print_r($datarow); echo "</pre>"; exit;
		// echo $datarow['url']; exit;
		
		return true;
	}
	public function searchzencartproducts_check($args) {
		$datarow = & $args ['datarow'];
		// echo "<pre>"; print_r($datarow); echo "</pre>";
		$extra = $datarow ['extra'];
		$explode = explode ( ',', $extra );
		// echo "<pre>"; print_r($explode); echo "</pre>"; exit;
		
		$product_id = $explode [0];
		$domain = $explode [2];
		// $datarow['type'] = 'product';
		// echo $product_id . '<br>';
		// echo $datarow['url'];
		// $datarow['url'] = '';
		$datarow ['zproducturl'] = "http://$domain/index.php?main_page=product_info&products_id=$product_id";
		return true;
	}
	public function searchEvents($args) {
		if (! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_READ )) {
			return true;
		}
		
		ModUtil::dbInfoLoad ( 'Search' );
		$tables = DBUtil::getTables ();
		
		$kewrd = $args ['q'];
		// echo "<pre>"; print_r($tables); echo "</pre>";
		$eventsColumn = $tables ['zselex_shop_events_column'];
		
		$where = Search_Api_User::construct_where ( $args, array (
				$eventsColumn ['shop_event_name'],
				$eventsColumn ['shop_event_shortdescription'],
				$eventsColumn ['shop_event_description'],
				$eventsColumn ['shop_event_keywords'],
				$eventsColumn ['shop_event_startdate'],
				$eventsColumn ['shop_event_starthour'],
				$eventsColumn ['shop_event_startminute'],
				$eventsColumn ['shop_event_enddate'],
				$eventsColumn ['shop_event_endhour'],
				$eventsColumn ['shop_event_endminute'] 
		) );
		// Only search in published articles that are currently visible
		// echo "<pre>"; print_r($where); echo "</pre>";
		
		$where .= " AND ({$eventsColumn['shop_event_startdate']} <=CURDATE() AND {$eventsColumn['shop_event_enddate']}  >=CURDATE())";
		$date = DateUtil::getDatetime ();
		// $where .= " AND ('$date' >= {$newsColumn['from']} AND ({$newsColumn['to']} IS NULL OR '$date' <= {$newsColumn['to']}))";
		
		$sessionId = session_id ();
		
		ModUtil::loadApi ( 'ZSELEX', 'user' );
		
		$permChecker = new News_ResultChecker ( $this->getVar ( 'enablecategorization' ), $this->getVar ( 'enablecategorybasedpermissions' ) );
		$events = DBUtil::selectObjectArrayFilter ( 'zselex_shop_events', $where, null, null, null, '', $permChecker, null );
		
		// echo "<pre>"; print_r($prodAds); echo "</pre>"; exit;
		foreach ( $events as $event ) {
			$item = array (
					'title' => $event ['shop_event_name'],
					'text' => $event ['shop_event_shortdescription'],
					'extra' => $event ['shop_id'] . "," . 'events',
					'created' => '',
					'module' => 'ZSELEX',
					'session' => $sessionId 
			);
			
			$insertResult = DBUtil::insertObject ( $item, 'search_result' );
			if (! $insertResult) {
				return LogUtil::registerError ( $this->__ ( 'Error! Could not load any articles.' ) );
			}
		}
		
		return true;
	}
	public function searchEvents_check($args) {
		// echo "<pre>"; print_r($args); echo "</pre>"; exit;
		// echo "come here products.....";
		// $datarow['url'] = '';
		$datarow = & $args ['datarow'];
		// echo "<pre>"; print_r($datarow); echo "</pre>";
		$shop_id = $datarow ['extra'];
		// $datarow['type'] = 'product';
		// echo $product_id . '<br>';
		// echo $datarow['url'];
		$datarow ['url'] = ModUtil::url ( 'ZSELEX', 'user', 'shop', array (
				'id' => $shop_id 
		) );
		$datarow ['eventsurl'] = ModUtil::url ( 'ZSELEX', 'user', 'shop', array (
				'id' => $shop_id 
		) );
		
		// echo "<pre>"; print_r($datarow); echo "</pre>"; exit;
		// echo $datarow['url']; exit;
		
		return true;
	}
}
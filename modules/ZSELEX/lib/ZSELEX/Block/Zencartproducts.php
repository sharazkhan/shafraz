<?php

/**
 * Copyright  2013
 *
 * ZSELEX
 * Demonstration of Zikula Module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control Block display and interface
 */
class ZSELEX_Block_Zencartproducts extends Zikula_Controller_AbstractBlock {
	
	/**
	 * initialise block
	 */
	public function init() {
		SecurityUtil::registerPermissionSchema ( 'ZSELEX:zencartproductsblock:', 'Block title::' );
	}
	
	/**
	 * get information on block
	 */
	public function info() {
		return array (
				'text_type' => 'zencart',
				'module' => 'ZSELEX',
				'text_type_long' => $this->__ ( 'ZenCart Products' ),
				'allow_multiple' => true,
				'form_content' => false,
				'form_refresh' => false,
				'show_preview' => true,
				'admin_tableless' => true 
		);
	}
	
	/**
	 * display block
	 */
	public function display($blockinfo) {
		// return false;
		if (! SecurityUtil::checkPermission ( 'ZSELEX:zencartproductsblock:', "$blockinfo[title]::", ACCESS_OVERVIEW )) {
			return;
		}
		if (! ModUtil::available ( 'ZSELEX' )) {
			return;
		}
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		
		// echo "<pre>"; print_r($vars); echo "</pre>";
		
		$products = $this->getProducts ( $vars );
		// echo "<pre>"; print_r($products); echo "</pre>";
		// echo count($products);
		
		$this->view->assign ( 'vars', $vars );
		$this->view->assign ( 'products', $products );
		
		$blockinfo ['content'] = $this->view->fetch ( 'blocks/zencartproducts.tpl' );
		
		return BlockUtil::themeBlock ( $blockinfo );
	}
	
	/**
	 * modify block settings .
	 * .
	 */
	public function modify($blockinfo) {
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		if (empty ( $vars ['showAdminZSELEXinBlock'] )) {
			$vars ['showAdminZSELEXinBlock'] = 0;
		}
		
		// echo "<pre>"; print_r($vars); echo "</pre>";
		
		$this->view->assign ( 'vars', $vars );
		
		return $this->view->fetch ( 'blocks/zencartproducts_modify.tpl' );
	}
	
	/**
	 * update block settings
	 */
	public function update($blockinfo) {
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		
		// alter the corresponding variable
		$vars ['showAdminZSELEXinBlock'] = FormUtil::getPassedValue ( 'showAdminZSELEXinBlock', '', 'POST' );
		$vars ['shop'] = FormUtil::getPassedValue ( 'shop', '', 'POST' );
		$vars ['amount'] = FormUtil::getPassedValue ( 'amount', '', 'POST' );
		$vars ['domain'] = FormUtil::getPassedValue ( 'domain', '', 'POST' );
		$vars ['host'] = FormUtil::getPassedValue ( 'host', '', 'POST' );
		$vars ['database'] = FormUtil::getPassedValue ( 'database', '', 'POST' );
		$vars ['username'] = FormUtil::getPassedValue ( 'username', '', 'POST' );
		$vars ['password'] = FormUtil::getPassedValue ( 'password', '', 'POST' );
		$vars ['tableprefix'] = FormUtil::getPassedValue ( 'tableprefix', '', 'POST' );
		
		$vars ['orderby'] = FormUtil::getPassedValue ( 'orderby', '', 'POST' );
		
		// write back the new contents
		$blockinfo ['content'] = BlockUtil::varsToContent ( $vars );
		
		// clear the block cache
		$this->view->clear_cache ( 'blocks/zencartproducts.tpl' );
		
		return $blockinfo;
	}
	public function getProducts($vars) {
		try {
			// echo "<pre>"; print_r($vars); echo "</pre>"; exit;
			
			if ($vars ['amount'] != '') {
				$limit = $vars ['amount'];
			} else {
				$limit = '2';
			}
			
			// echo $limit;
			
			$dnName = (! empty ( $vars ['database'] ) ? $vars ['database'] : 'nodb');
			$dnUser = (! empty ( $vars ['username'] ) ? $vars ['username'] : 'root');
			$dbPswrd = (! empty ( $vars ['password'] ) ? $vars ['password'] : '');
			$dbHost = (! empty ( $vars ['host'] ) ? $vars ['host'] : 'localhost');
			
			$dsn = "mysql:dbname='" . $dnName . "';host='" . $dbHost . "'";
			// echo $dsn; exit;
			
			$dsn = "mysql:dbname=$dnName;host=$dbHost";
			$user = $dnUser;
			$password = $dbPswrd;
			$tableprefix = (! empty ( $vars ['tableprefix'] ) ? $vars ['tableprefix'] : '');
			// $sValues = '';
			// $statement = Doctrine_Manager::getInstance()->closeConnection($statement);
			// echo $vars['amount'];
			
			$tableQuery = "SELECT COUNT(*) AS count  FROM information_schema.tables  
                WHERE table_schema = '" . $dnName . "'   AND table_name = '" . $tableprefix . "products'";
			
			// echo $prdquery; exit;
			$dbh = new PDO ( $dsn, $user, $password );
			$statement1 = Doctrine_Manager::getInstance ()->connection ( $dbh );
			
			$resultTable = $statement1->execute ( $tableQuery );
			$tableExist = $resultTable->fetch ();
			$tableCount = $tableExist ['count'];
			
			if ($tableCount > 0) {
				$prdwhere = "b.products_name!='' AND a.products_image!='' AND a.manufacturers_id!=''";
				$prdwhere = "a.products_status=1";
				$prdquery = "SELECT a.products_id , a.products_image , a.products_price , LEFT(b.products_name, 20) AS products_name  , LEFT(b.products_description, 20) AS products_description,
                         mn.manufacturers_name
                         FROM  " . $tableprefix . "products a 
                         LEFT JOIN " . $tableprefix . "products_description b ON b.products_id=a.products_id
                         LEFT JOIN " . $tableprefix . "manufacturers mn ON mn.manufacturers_id=a.manufacturers_id
                         WHERE " . $prdwhere . "
                                                ORDER BY RAND()  LIMIT  0,$limit";
				$results = $statement1->execute ( $prdquery );
				$sValues = $results->fetchAll ();
				
				// echo $config['domain'];
				// $imagearr = array('imageval'=>'lower');
				$list = array ();
				for($i = 0; $i < count ( $sValues ); $i ++) {
					
					// echo number_format($sValues[$i]['products_price'], 2) . '<br>';
					$priceexplode = explode ( '.', $sValues [$i] ['products_price'] );
					
					// echo $priceexplode[1] . '<br>';
					
					if (strlen ( $priceexplode [0] ) >= 4) {
						
						$p1 = substr_replace ( $priceexplode [0], ".", 1, 0 );
						$p2 = substr_replace ( $priceexplode [1], ",", 2 );
						$p2 = substr ( $p2, 0, - 1 );
						
						$sValues [$i] ['PRICE'] = $p1 . ',' . $p2;
					} else {
						
						// echo $priceexplode[1] . '<br>';
						$newstring = substr_replace ( $priceexplode [1], '', '2' );
						
						// echo $newstring . '<br>';
						// echo $priceexplode[0] . ',' . $newstring . '<br>';
						
						$sValues [$i] ['PRICE'] = $priceexplode [0] . ',' . $newstring;
					}
					
					// echo $sValues[$i]['PRICE'] . '<br>';
					
					if ($sValues [$i] ['products_image'] != '') {
						
						list ( $width, $height, $type, $attr ) = getimagesize ( 'http://' . $vars ['domain'] . '/images/' . str_replace ( " ", "%20", $sValues [$i] ['products_image'] ) );
						$AW = $width;
						$AH = $height;
						
						$H = '';
						$W = '';
						
						if ($AH < 210 && $AW < 170) {
						}
						
						if ($AH > 210 && $AW < 170) {
							
							$H = 210;
							$W = $AW * ((210 * 100) / $AH) / 100;
							
							$sValues [$i] ['H'] = round ( $H );
							$sValues [$i] ['W'] = round ( $W );
						}
						
						if ($AH < 210 && $AW > 170) {
							
							$W = 170;
							$H = $AH * ((170 * 100) / $AW) / 100;
							$sValues [$i] ['H'] = round ( $H );
							$sValues [$i] ['W'] = round ( $W );
						}
						
						if ($AH > 210 && $AW > 170) {
							
							$H = 210;
							$W = $AW * ((210 * 100) / $AH) / 100;
							
							$WTmp = $W;
							if ($W > 170) {
								$W = 170;
								$H = $H * ((170 * 100) / $WTmp) / 100;
							}
							
							$sValues [$i] ['H'] = round ( $H );
							$sValues [$i] ['W'] = round ( $W );
						}
					}
				}
			} else {
				$error = "Table Doesnt Exists";
				$this->view->assign ( 'error', $error );
			}
			// return $sValues;
			// echo "<pre>"; print_r($sValues); echo "</pre>"; exit;
			$statement1 = Doctrine_Manager::getInstance ()->closeConnection ( $statement1 );
		} catch ( PDOException $e ) {
			// echo 'Caught exception: ', $e->getMessage(), "\n";
			$error = $e->getMessage () . "\n";
			$this->view->assign ( 'error', $error );
			// die;
		}
		
		return $sValues;
	}
}

// end class def
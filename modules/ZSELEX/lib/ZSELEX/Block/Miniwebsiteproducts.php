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
class ZSELEX_Block_Miniwebsiteproducts extends Zikula_Controller_AbstractBlock {
	public $amount;
	
	/**
	 * initialise block
	 */
	public function init() {
		SecurityUtil::registerPermissionSchema ( 'ZSELEX:miniwebsiteproducts:', 'Block title::' );
	}
	
	/**
	 * get information on block
	 */
	public function info() {
		return array (
				'text_type' => 'selection',
				'module' => 'ZSELEX',
				'text_type_long' => $this->__ ( 'Display Products In Mini Website' ),
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
		// echo "Comes Here";
		// if (!SecurityUtil::checkPermission('ZSELEX::', "::", ACCESS_EDIT)) {
		// return;
		// }
		// return;
		if (! SecurityUtil::checkPermission ( 'ZSELEX:miniwebsiteproducts:', "$blockinfo[title]::", ACCESS_OVERVIEW )) {
			return;
		}
		if (! ModUtil::available ( 'ZSELEX' )) {
			return;
		}
		// echo "<pre>"; print_r( $_SESSION['user_cart']); echo "</pre>";
		$admin = SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_ADMIN );
		$edit = SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_EDIT );
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		$thislang = ZLanguage::getLanguageCode ();
		$loguser = UserUtil::getVar ( 'uid' );
		$loguser = ! empty ( $loguser ) ? $loguser : 0;
		$user_id = $loguser;
		// echo "<pre>"; print_r($vars); echo "</pre>";
		
		if ($admin) {
			$perm = $admin;
		} else if ($edit) {
			$perm = $edit;
		} else {
			$perm = '';
		}
		
		$orderby = '';
		
		$limit = ' LIMIT 0 , 4';
		if ($vars ['amount'] != '') {
			$limit = " LIMIT 0 , $vars[amount]";
		}
		// echo $limit;
		// echo "<pre>"; print_r($vars); echo "</pre>";
		
		$shop_id = ! empty ( $_REQUEST ['shop_id'] ) ? FormUtil::getPassedValue ( 'shop_id', '', 'REQUEST' ) : $_REQUEST ['shop_idnewItem'];
		
		if (empty ( $shop_id )) {
			return;
		}
		$ownerName = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getOwner', $args = array (
				'shop_id' => $shop_id 
		) );
		$this->view->assign ( 'ownerName', $ownerName );
		
		// /////////////////check service exists//////////////////////////
		
		$serviceExist = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'serviceExistBlock', $args = array (
				'shop_id' => $shop_id,
				'type' => 'minisiteproductblock' 
		) );
		
		if ($serviceExist < 1) {
			return;
		}
		
		$linkToShop = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'serviceExistBlock', $args = array (
				'shop_id' => $shop_id,
				'type' => 'linktoshop' 
		) );
		$this->view->assign ( 'linkToShop', $linkToShop );
		
		// echo "comes here";
		$em = ServiceUtil::getService ( 'doctrine.entitymanager' );
		$shop_detail = $em->getRepository ( 'ZSELEX_Entity_ShopSetting' )->getShopDetails ( array (
				'shop_id' => $shop_id 
		) );
		// echo "<pre>"; print_r($shop_detail); echo "</pre>";
		$no_payment = $shop_detail ['no_payment'];
		// $no_payment = 0;
		// echo "nopay : " . $no_payment;
		$this->view->assign ( 'no_payment', $no_payment );
		// echo "comes here22";
		
		$args = array (
				'table' => 'zselex_shop a , zselex_minishop b',
				'where' => array (
						"a.shop_id=b.shop_id",
						"a.shop_id=$shop_id" 
				) 
		);
		$type = ModUtil::apiFunc ( 'ZSELEX', 'user', 'selectRow', $args );
		$shopType = $type ['shoptype'];
		$shopName = $type ['shop_name'];
		// echo "<pre>"; print_r($type); echo "</pre>";
		$products = array ();
		
		if ($shopType == 'iSHOP') {
			if ($vars ['orderby'] != '') {
				if ($vars ['orderby'] == 'new') {
					$orderby = 'p.product_id DESC';
				} else if ($vars ['orderby'] == 'random') {
					$orderby = 'RAND()';
				}
			}
			
			$service = $this->entityManager->getRepository ( 'ZSELEX_Entity_Product' )->get ( array (
					'entity' => 'ZSELEX_Entity_ServiceShop',
					'fields' => array (
							'a.quantity' 
					),
					'where' => array (
							'a.shop' => $shop_id,
							'a.type' => 'addproducts' 
					) 
			) );
			$serviceQuantity = $service ['quantity'];
			// $args = array('table' => 'zselex_products', 'where' => array("shop_id=$shop_id", "prd_status=1"), 'limit' => 'LIMIT 0 , 4', 'orderby' => $orderby);
			// echo "<pre>"; print_r($args); echo "</pre>";
			// $products = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray', $args);
			$limit = 4;
			if ($serviceQuantity < 4) {
				$limit = $serviceQuantity;
			}
			$products = $this->entityManager->getRepository ( 'ZSELEX_Entity_Product' )->getMinisiteProducts ( array (
					'shop_id' => $shop_id,
					'orderby' => $orderby,
					'limit' => $limit 
			) );
			// echo "<pre>"; print_r($products); echo "</pre>";
			// echo "<pre>"; print_r($e); echo "</pre>";
		} elseif ($shopType == 'zSHOP') {
			
			if ($vars ['orderby'] != '') {
				if ($vars ['orderby'] == 'new') {
					$orderby = 'a.products_id DESC';
				} else if ($vars ['orderby'] == 'random') {
					$orderby = 'RAND()';
				}
			}
			
			$obj = DBUtil::selectObjectByID ( 'zselex_zenshop', $shop_id, 'shop_id' );
			// echo "<pre>"; print_r($obj); echo "</pre>";
			$this->view->assign ( 'zShopDomain', $obj ['domain'] );
			$this->view->assign ( 'zShopImgPath', 'http://' . $obj ['domain'] . '/images' );
			$products = ModUtil::apiFunc ( 'ZSELEX', 'user', 'getZenCartProducts', $args = array (
					'shop' => $obj,
					'limit' => $limit,
					'orderby' => $orderby 
			) );
		}
		
		// $perm = ModUtil::apiFunc('ZSELEX', 'admin', 'shopPermission', array('shop_id' => $shop_id, 'user_id' => $user_id));
		
		$perm = $_REQUEST ['perm'];
		if ((count ( $products ) < 0 || empty ( $products )) && (! $perm)) {
			return;
		}
		if (empty ( $products )) {
			return;
		}
		
		$info ['title'] = $vars ['blockinfo'] [$thislang] ['infotitle'];
		$info ['message'] = $vars ['blockinfo'] [$thislang] ['infomessage'];
		
		// echo "<pre>"; print_r($products); echo "</pre>";
		$loggedIn = UserUtil::isLoggedIn ();
		$productCount = sizeof ( $products );
		$this->view->assign ( 'productCount', $productCount );
		$this->view->assign ( 'perm', $perm );
		$this->view->assign ( 'servicePerm', $servicePermCount );
		$this->view->assign ( 'admin', $admin );
		$this->view->assign ( 'add', $add );
		$this->view->assign ( 'bid', $blockinfo ['bid'] );
		$this->view->assign ( 'info', $info );
		$this->view->assign ( 'vars', $vars );
		$this->view->assign ( 'shoptype', $shopType );
		$this->view->assign ( 'shopName', $shopName );
		$this->view->assign ( 'products', $products );
		$this->view->assign ( 'loggedIn', $loggedIn );
		$this->view->assign ( 'shop_id', $shop_id );
		// $this->view->assign('shopconfig', $shopconfig);
		
		$blockinfo ['content'] = $this->view->fetch ( 'blocks/miniwebsiteproducts.tpl' );
		
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
		
		// echo "<pre>"; print_r($shops); echo "</pre>";
		$languages = ZLanguage::getInstalledLanguages ();
		$this->view->assign ( 'languages', $languages );
		$this->view->assign ( 'vars', $vars );
		$this->view->assign ( 'shops', $shops );
		
		return $this->view->fetch ( 'blocks/miniwebsiteproducts_modify.tpl' );
	}
	
	/**
	 * update block settings
	 */
	public function update($blockinfo) {
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		
		// alter the corresponding variable
		$vars ['showAdminZSELEXinBlock'] = FormUtil::getPassedValue ( 'showAdminZSELEXinBlock', '', 'POST' );
		
		$vars ['amount'] = FormUtil::getPassedValue ( 'amount', '', 'POST' );
		$vars ['orderby'] = FormUtil::getPassedValue ( 'orderby', '', 'POST' );
		$vars ['displayinfo'] = FormUtil::getPassedValue ( 'displayinfo', '', 'POST' );
		$vars ['blockinfo'] = FormUtil::getPassedValue ( 'blockinfo', '', 'POST' );
		
		// write back the new contents
		$blockinfo ['content'] = BlockUtil::varsToContent ( $vars );
		
		// clear the block cache
		$this->view->clear_cache ( 'blocks/miniwebsiteproducts_modify.tpl' );
		
		return $blockinfo;
	}
}

// end class def
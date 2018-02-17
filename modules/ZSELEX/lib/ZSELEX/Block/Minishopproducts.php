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
class ZSELEX_Block_Minishopproducts extends Zikula_Controller_AbstractBlock {
	
	/**
	 * initialise block
	 */
	public function init() {
		SecurityUtil::registerPermissionSchema ( 'ZSELEX:minishopproducts :', 'Block title::' );
	}
	
	/**
	 * get information on block
	 */
	public function info() {
		return array (
				'text_type' => 'selection',
				'module' => 'ZSELEX',
				'text_type_long' => $this->__ ( 'Display Products In Mini Shop' ),
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
		
		// echo $_SESSION['linkservice'];
		// return;
		// echo "<pre>"; print_r($_POST); echo "</pre>";
		if (! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_ADMIN )) {
			if ($_SESSION ['linkservice'] == 'no') {
				// return false;
			}
		}
		
		if (! SecurityUtil::checkPermission ( 'ZSELEX::', "::", ACCESS_OVERVIEW )) {
			return;
		}
		
		if (! SecurityUtil::checkPermission ( 'ZSELEX:minishopproducts:', "$blockinfo[title]::", ACCESS_OVERVIEW )) {
			return;
		}
		if (! ModUtil::available ( 'ZSELEX' )) {
			return;
		}
		$shop_id = $_REQUEST ['shop_id'];
		$loguser = UserUtil::getVar ( 'uid' );
		$loguser = ! empty ( $loguser ) ? $loguser : 0;
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		$ownerName = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getOwner', $args = array (
				'shop_id' => $shop_id 
		) );
		$this->view->assign ( 'ownerName', $ownerName );
		
		$thislang = ZLanguage::getLanguageCode ();
		$admin = SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_ADMIN );
		$edit = SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_EDIT );
		
		// /////////////////check service exists///////////////////
		$serviceExist = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'serviceExistBlock', $args = array (
				'shop_id' => $shop_id,
				'type' => 'minishop' 
		) );
		
		if ($serviceExist < 1) {
			// echo "comes hereee";
			// return LogUtil::registerError($this->__('Error! No minishop configured here.'));
			return;
		}
		// ////////////////////////////////////////////////////////
		
		if (! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_ADMIN )) {
			$servicePermCount = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getCount', $args = array (
					'table' => 'zselex_serviceshop',
					'where' => "shop_id=$shop_id AND type='minisiteimages' 
                        AND(owner_id=$loguser OR owner_id 
                            IN(SELECT owner_id FROM zselex_shop_admins WHERE user_id=$loguser))" 
			) );
			
			if ($servicePermCount > 0) {
				$servicePerm = 1;
			} else {
				$servicePerm = 0;
			}
		} elseif (SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_ADMIN )) {
			$servicePerm = 2;
			$servicePermCount = 2;
		}
		
		if ($admin) {
			$perm = $admin;
		} else if ($edit) {
			$perm = $edit;
		} else {
			$perm = '';
		}
		$orderby = '';
		
		if ($vars ['orderby'] != '') {
			if ($vars ['orderby'] == 'new') {
				$orderby = ' a.products_id DESC';
			} else if ($vars ['orderby'] == 'random') {
				$orderby = ' RAND()';
			}
		}
		
		$limit = '';
		if ($vars ['amount'] != '') {
			$limit = " LIMIT 0 , $vars[amount]";
		}
		
		// echo "<pre>"; print_r($vars); echo "</pre>";
		
		$shop_id = ! empty ( $_REQUEST ['shop_id'] ) ? FormUtil::getPassedValue ( 'shop_id', '', 'REQUEST' ) : $_REQUEST ['shop_idnewItem'];
		
		if (empty ( $shop_id )) {
			return;
		}
		$template = "minishopproducts.tpl";
		
		$ownerInfo = ModUtil::apiFunc ( 'ZSELEX', 'user', 'selectRow', $args = array (
				'table' => 'zselex_shop_owners a , users b',
				'where' => array (
						"a.shop_id=$shop_id",
						"a.user_id=b.uid" 
				) 
		) );
		// echo "<pre>"; print_r($ownerInfo); echo "</pre>";
		$owner_id = $ownerInfo ['uid'];
		$productLinker = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'checkProductLinkerExist', $args = array (
				'shop_id' => $shop_id,
				'owner_id' => $owner_id,
				'type' => 'productlinker' 
		) );
		// echo $productLinker;
		// echo "<pre>"; print_r($_REQUEST); echo "</pre>";
		$itemsperpage = FormUtil::getPassedValue ( 'itemsperpage', isset ( $args ['itemsperpage'] ) ? $args ['itemsperpage'] : 24, 'GETPOST' );
		$startnum = FormUtil::getPassedValue ( 'startnum', isset ( $args ['startnum'] ) ? $args ['startnum'] : 0, 'GETPOST' );
		$prod_catId = FormUtil::getPassedValue ( 'prod_category', isset ( $args ['prod_category'] ) ? $args ['prod_category'] : 0, 'GETPOST' );
		$this->view->assign ( 'startnum', $startnum );
		$this->view->assign ( 'itemsperpage', $itemsperpage );
		$this->view->assign ( 'prod_catId', $prod_catId );
		if ($startnum > 0) {
			$startnum = $startnum - 1;
		} else {
			$startnum = $startnum;
		}
		$limit = "LIMIT $startnum , $itemsperpage";
		if ($productLinker < 1) { // no product linker
		                          // echo "come here";
			$args = array (
					'table' => 'zselex_shop a , zselex_minishop b',
					'where' => array (
							"a.shop_id=b.shop_id",
							"a.shop_id=$shop_id" 
					),
					'fields' => array (
							'b.shoptype',
							'a.shop_name' 
					) 
			);
			$shopInfo = ModUtil::apiFunc ( 'ZSELEX', 'user', 'selectRow', $args );
			$shopType = $shopInfo ['shoptype'];
			
			// echo "<pre>"; print_r($type); echo "</pre>";
			$products = array ();
			if ($shopType == 'iSHOP') {
				$args = array (
						'table' => 'zselex_products',
						"where" => array (
								"shop_id=$shop_id",
								"prd_status=1" 
						),
						'limit' => $limit 
				);
				$products = ModUtil::apiFunc ( 'ZSELEX', 'user', 'selectArray', $args );
				foreach ( $products as $key => $val ) {
					$imagepath = pnGetBaseURL () . 'zselexdata/' . $ownerName . '/products/thumb/' . str_replace ( " ", "%20", $val ['prd_image'] );
					$img_args = array (
							'imagepath' => $imagepath,
							'setheight' => '210',
							'setwidth' => '170' 
					);
					$new_resize = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'imageProportional', $img_args );
					$products [$key] ['H'] = $new_resize ['new_height'];
					$products [$key] ['W'] = $new_resize ['new_width'];
				}
				// echo "<pre>"; print_r($products); echo "</pre>";
				$total_count = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getCount', $args = array (
						'table' => 'zselex_products',
						'where' => "shop_id=$shop_id" 
				) );
			} elseif ($shopType == 'zSHOP') {
				// echo "comes here";
				$obj = DBUtil::selectObjectByID ( 'zselex_zenshop', $shop_id, 'shop_id' );
				$products = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getZenCartProducts', $args = array (
						'shop' => $obj,
						'limit' => $limit 
				) );
				$total_count = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getZenCartProductsCount', $args = array (
						'shop_id' => $shop_id,
						'sql' => $sql,
						'shop' => $obj 
				) );
				
				// echo "products : " . $products . '<br>'; exit;
				if ($products == 'error') {
					return $this->redirect ( ModUtil::url ( 'ZSELEX', 'user', 'errorss' ) );
				}
			}
			
			if ((! $products) && (! $perm)) {
				return;
			}
		} else {
			$template = "minishopproducts_productlinker.tpl";
			$allproducts = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getMiniShopBlockProductsProductLinker', $args = array (
					'shop_id' => $shop_id,
					'type' => 'productlinker' 
			) );
			shuffle ( $allproducts );
		}
		$this->view->assign ( 'total_count', $total_count );
		
		$args_cat = array (
				'table' => 'zselex_product_categories',
				'IdValue' => $cat_id,
				'IdName' => 'prd_cat_id' 
		);
		// Get the news type in the db
		$categories = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getElements', $args_cat );
		// echo "<pre>"; print_r($categories); echo "</pre>";
		// echo "<pre>"; print_r($allproducts); echo "</pre>";
		$info ['title'] = $vars ['blockinfo'] [$thislang] ['infotitle'];
		$info ['message'] = $vars ['blockinfo'] [$thislang] ['infomessage'];
		$count = count ( $products );
		$this->view->assign ( 'perm', $perm );
		$this->view->assign ( 'servicePerm', $servicePermCount );
		$this->view->assign ( 'bid', $blockinfo ['bid'] );
		$this->view->assign ( 'info', $info );
		$this->view->assign ( 'vars', $vars );
		$this->view->assign ( 'shoptype', $shopType );
		$this->view->assign ( 'shopinfo', $shopInfo );
		$this->view->assign ( 'count', $count );
		$this->view->assign ( 'categories', $categories );
		$this->view->assign ( 'products', $products );
		$this->view->assign ( 'allproducts', $allproducts );
		// $this->view->assign('shopconfig', $shopconfig);
		
		$blockinfo ['content'] = $this->view->fetch ( 'blocks/' . $template );
		
		return BlockUtil::themeBlock ( $blockinfo );
	}
	public function isMiniShopExist($args) {
	}
	public function imageProportional($setheight, $setwidth, $imagepath) {
		list ( $width, $height, $type, $attr ) = getimagesize ( $imagepath );
		$AW = $width;
		$AH = $height;
		
		$H = '';
		$W = '';
		
		if ($AH < $setheight && $AW < $setwidth) {
		}
		
		if ($AH > $setheight && $AW < $setwidth) {
			$H = $setheight;
			$W = $AW * ((210 * 100) / $AH) / 100;
			$sValues [$i] ['H'] = round ( $H );
			$sValues [$i] ['W'] = round ( $W );
		}
		
		if ($AH < $setheight && $AW > $setwidth) {
			$W = $setwidth;
			$H = $AH * (($setwidth * 100) / $AW) / 100;
			$sValues [$i] ['H'] = round ( $H );
			$sValues [$i] ['W'] = round ( $W );
		}
		
		if ($AH > $setheight && $AW > $setwidth) {
			$H = $setheight;
			$W = $AW * (($setheight * 100) / $AH) / 100;
			
			$WTmp = $W;
			if ($W > $setwidth) {
				$W = $setwidth;
				$H = $H * (($setwidth * 100) / $WTmp) / 100;
			}
			
			$new_height = round ( $H );
			$new_width = round ( $W );
		}
		$output ['height'] = $new_height;
		$output ['weight'] = $new_width;
		return $output;
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
		
		$languages = ZLanguage::getInstalledLanguages ();
		
		// echo "<pre>"; print_r($shops); echo "</pre>";
		$this->view->assign ( 'languages', $languages );
		$this->view->assign ( 'vars', $vars );
		$this->view->assign ( 'shops', $shops );
		
		return $this->view->fetch ( 'blocks/minishopproducts_modify.tpl' );
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
		$this->view->clear_cache ( 'blocks/minishopproducts_modify.tpl' );
		
		return $blockinfo;
	}
}

// end class def
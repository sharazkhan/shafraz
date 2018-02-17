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
class ZSELEX_Block_Dealoftheday extends Zikula_Controller_AbstractBlock {
	public $amount;
	
	/**
	 * initialise block
	 */
	public function init() {
		SecurityUtil::registerPermissionSchema ( 'ZSELEX:dealoftheday :', 'Block title::' );
	}
	
	/**
	 * get information on block
	 */
	public function info() {
		return array (
				'text_type' => 'selection',
				'module' => 'ZSELEX',
				'text_type_long' => $this->__ ( 'Deal Of The Day (DOTD)' ),
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
		if (! SecurityUtil::checkPermission ( 'ZSELEX:dealoftheday:', "$blockinfo[title]::", ACCESS_OVERVIEW )) {
			return;
		}
		if (! ModUtil::available ( 'ZSELEX' )) {
			return;
		}
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		$thislang = ZLanguage::getLanguageCode ();
		$admin = SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_ADMIN );
		$edit = SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_EDIT );
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
				$orderby = ' ORDER BY product_id desc';
			} else if ($vars ['orderby'] == 'random') {
				$orderby = ' ORDER BY RAND()';
			}
		}
		
		$limit = '';
		if ($vars ['amount'] != '') {
			$limit = " LIMIT 0 , $vars[amount]";
		}
		
		$date = date ( 'Y-m-d' );
		$args = array (
				'table' => 'zselex_dotd',
				'where' => array (
						"dotd_date='" . $date . "'" 
				) 
		);
		$dod = ModUtil::apiFunc ( 'ZSELEX', 'user', 'selectRow', $args );
		
		// echo "<pre>"; print_r($dod); echo "</pre>"; exit;
		
		if (empty ( $dod )) {
			return;
		}
		
		$shop_id = $dod ['shop_id'];
		$column_name = $dod ['column_name'];
		$columnValue = $dod ['value'];
		
		// $getShopType = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow', $args = array('table' => 'zselex_shop a ,zselex_shop_types b',
		// 'where' => array("a.shop_id=$shop_id", "a.shoptype_id=b.shoptype_id"), 'fields' => array('b.shopTypeName')));
		
		$shopType = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getShopType1', $args = array (
				'shop_id' => $shop_id 
		) );
		$ownerName = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getOwner', $args = array (
				'shop_id' => $shop_id 
		) );
		// $shopType = $getShopType['shopTypeName'];
		// $shopType = 'iSHOP';
		$item = array ();
		if ($shopType == 'iSHOP') {
			
			$item = ModUtil::apiFunc ( 'ZSELEX', 'user', 'getIshopDOTDProduct', $args = array (
					'shop_id' => $shop_id,
					'column_name' => $column_name,
					'columnValue' => $columnValue 
			) );
		} elseif ($shopType == 'zSHOP') {
			// echo "comes here";
			$obj = DBUtil::selectObjectByID ( 'zselex_shop', $shop_id, 'shop_id' );
			$item = ModUtil::apiFunc ( 'ZSELEX', 'user', 'getZenCartDOTDProducts', $args = array (
					'shop_id' => $shop_id,
					'shop' => $obj,
					'column_name' => $column_name,
					'columnValue' => $columnValue 
			) );
		}
		
		// echo "<pre>"; print_r($item); echo "</pre>";
		if (empty ( $item )) {
			return;
		}
		$info ['title'] = $vars ['blockinfo'] [$thislang] ['infotitle'];
		$info ['message'] = $vars ['blockinfo'] [$thislang] ['infomessage'];
		
		$this->view->assign ( 'ownerName', $ownerName );
		$this->view->assign ( 'bid', $blockinfo ['bid'] );
		$this->view->assign ( 'info', $info );
		$this->view->assign ( 'vars', $vars );
		$this->view->assign ( 'shoptype', $shopType );
		$this->view->assign ( 'item', $item );
		// $this->view->assign('shopconfig', $shopconfig);
		
		$blockinfo ['content'] = $this->view->fetch ( 'blocks/dealoftheday.tpl' );
		
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
		
		$ishopargs = array (
				'table' => 'zselex_shop',
				'fields' => '',
				'where' => array (
						'shoptype_id' => '2' 
				) 
		);
		$ishops = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'selectItems', $ishopargs );
		
		$languages = ZLanguage::getInstalledLanguages ();
		
		// echo "<pre>"; print_r($adtypes); echo "</pre>";
		$this->view->assign ( 'languages', $languages );
		$this->view->assign ( 'vars', $vars );
		$this->view->assign ( 'ishops', $ishops );
		
		return $this->view->fetch ( 'blocks/dealoftheday_modify.tpl' );
	}
	
	/**
	 * update block settings
	 */
	public function update($blockinfo) {
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		
		// alter the corresponding variable
		$vars ['showAdminZSELEXinBlock'] = FormUtil::getPassedValue ( 'showAdminZSELEXinBlock', '', 'POST' );
		
		$vars ['amount'] = FormUtil::getPassedValue ( 'amount', '', 'POST' );
		$vars ['shop_id'] = FormUtil::getPassedValue ( 'shop_id', '', 'POST' );
		$vars ['orderby'] = FormUtil::getPassedValue ( 'orderby', '', 'POST' );
		$vars ['displayinfo'] = FormUtil::getPassedValue ( 'displayinfo', '', 'POST' );
		$vars ['blockinfo'] = FormUtil::getPassedValue ( 'blockinfo', '', 'POST' );
		
		// write back the new contents
		$blockinfo ['content'] = BlockUtil::varsToContent ( $vars );
		
		// clear the block cache
		$this->view->clear_cache ( 'blocks/dealoftheday_modify.tpl' );
		
		return $blockinfo;
	}
}

// end class def
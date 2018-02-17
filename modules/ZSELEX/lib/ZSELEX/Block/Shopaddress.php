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
class ZSELEX_Block_Shopaddress extends Zikula_Controller_AbstractBlock {
	
	/**
	 * initialise block
	 */
	public function init() { // / testing....
		SecurityUtil::registerPermissionSchema ( 'ZSELEX:shopaddress:', 'Block title::' );
	}
	
	/**
	 * get information on block//
	 */
	public function info() {
		return array (
				'text_type' => 'hello',
				'module' => 'ZSELEX',
				'text_type_long' => $this->__ ( 'Shop Address Block' ),
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
		// return;
		if (! SecurityUtil::checkPermission ( 'ZSELEX:shopaddress:', "$blockinfo[title]::", ACCESS_OVERVIEW )) {
			return;
		}
		if (! ModUtil::available ( 'ZSELEX' )) {
			return;
		}
		$loguser = UserUtil::getVar ( 'uid' );
		$loguser = ! empty ( $loguser ) ? $loguser : 0;
		$user_id = $loguser;
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		$this->view->assign ( 'vars', $vars );
		
		$shopId = FormUtil::getPassedValue ( 'shop_id', '', 'REQUEST' );
		$shop_id = $shopId;
		$shopinfo = $this->getShopInfo ( $shopId );
		if ($shopinfo == false) {
			return;
		}
		// echo "<pre>"; print_r($shopinfo); echo "</pre>";
		// $perm = ModUtil::apiFunc('ZSELEX', 'admin', 'shopPermission', array('shop_id' => $shop_id, 'user_id' => $user_id));
		
		$perm = FormUtil::getPassedValue ( 'perm', '', 'REQUEST' );
		
		$this->view->assign ( 'shopinfo', $shopinfo );
		$perm = FormUtil::getPassedValue ( 'perm', null, 'REQUEST' );
		$this->view->assign ( 'perm', $perm );
		
		$current_theme = System::getVar ( 'Default_Theme' );
		$this->view->assign ( 'current_theme', $current_theme );
		
		$blockinfo ['content'] = $this->view->fetch ( 'blocks/shopaddress/shopaddress.tpl' );
		
		return BlockUtil::themeBlock ( $blockinfo );
	}
	public function getShopInfo($shopId) {
		$repo = $this->entityManager->getRepository ( 'ZSELEX_Entity_Shop' );
		/*
		 * $sql = 'SELECT address , telephone , fax , email , opening_hours , vat_number
		 * FROM zselex_shop
		 * WHERE shop_id="' . $shopId . '"';
		 * $query = DBUtil::executeSQL($sql);
		 * $result = $query->fetch();
		 */
		$result = $repo->get ( array (
				'entity' => 'ZSELEX_Entity_Shop',
				'fields' => array (
						'a.address',
						'a.telephone',
						'a.fax',
						'a.email',
						'a.opening_hours',
						'a.vat_number' 
				),
				'where' => array (
						'a.shop_id' => $shopId 
				) 
		) );
		return $result;
	}
	
	/**
	 * modify block settings .
	 * .
	 */
	public function modify($blockinfo) {
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		
		$this->view->assign ( 'vars', $vars );
		
		return $this->view->fetch ( 'blocks/shopaddress/shopaddress_modify.tpl' );
	}
	
	/**
	 * update block settings
	 */
	public function update($blockinfo) {
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		// write back the new contents
		$blockinfo ['content'] = BlockUtil::varsToContent ( $vars );
		// clear the block cache
		$this->view->clear_cache ( 'blocks/shopaddress/shopaddress_modify.tpl' );
		return $blockinfo;
	}
}

// end class def
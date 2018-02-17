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
class ZSELEX_Block_Shopdisplay extends Zikula_Controller_AbstractBlock {
	public $amount;
	
	/**
	 * initialise block
	 */
	public function init() {
		SecurityUtil::registerPermissionSchema ( 'ZSELEX:shopdisplayblock:', 'Block title::' );
	}
	
	/**
	 * get information on block
	 */
	public function info() {
		return array (
				'text_type' => 'selection',
				'module' => 'ZSELEX',
				'text_type_long' => $this->__ ( 'Shopdisplay Block' ),
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
		
		// echo $blockinfo['title'];
		// echo "comes here";
		// return;
		if (! SecurityUtil::checkPermission ( 'ZSELEX:shopdisplayblock:', "$blockinfo[title]::", ACCESS_OVERVIEW )) {
			return;
		}
		if (! ModUtil::available ( 'ZSELEX' )) {
			return;
		}
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		$this->amount = $vars ['amount'];
		
		// echo $_GET['page'];
		$countrysrch = $_GET ['country'];
		$regionsrch = $_GET ['region'];
		$citysrch = $_GET ['city'];
		$shopsrch = $_GET ['shop'];
		
		$shopCountSql = "SELECT * FROM zselex_shop WHERE shop_id IS NOT NULL AND status='1'";
		$queryCount = DBUtil::executeSQL ( $shopCountSql );
		$resultCount = $queryCount->fetchAll ();
		$allCount = count ( $resultCount );
		// echo $allCount;
		$shopargs = array (
				'table' => 'zselex_shop',
				'where' => '',
				'orderBy' => '',
				'useJoins' => '' 
		);
		$shops = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getShoplistFrontEnd', $vars );
		
		// echo $vars['amount'];
		// echo "<pre>"; print_r($shops); echo "</pre>";
		
		$shopCount = count ( $shops );
		
		// echo "<pre>"; print_r($vars); echo "</pre>";
		// $cities = ModUtil::apiFunc('ZSELEX', 'admin', 'get_cat_selectlist', $vars);
		// echo "<pre>"; print_r($products); echo "</pre>";
		// echo count($products);
		// $shopconfig = ModUtil::apiFunc('ZSELEX', 'admin', 'getShopConfig', $vars);
		// echo "<pre>"; print_r($shopconfig); echo "</pre>";
		// echo $shopconfig['dbname'];
		if (SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_ADMIN )) {
			$admin = 1;
		} else {
			$admin = 0;
		}
		
		// $admin = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN);
		$this->view->assign ( 'amount', $vars ['amount'] );
		$this->view->assign ( 'allCount', $allCount );
		$this->view->assign ( 'admin', $admin );
		$this->view->assign ( 'vars', $vars );
		$this->view->assign ( 'zcities', $cities );
		$this->view->assign ( 'shopconfig', $shopconfig );
		$this->view->assign ( 'count', $shopCount );
		$this->view->assign ( 'zshop', $shops );
		// echo "comes hereee2";
		// echo "<pre>"; print_r($shops); echo "</pre>";
		$blockinfo ['content'] = $this->view->fetch ( 'blocks/shopblock.tpl' );
		
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
		
		$shops = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getShop', $items );
		// echo "<pre>"; print_r($shops); echo "</pre>";
		$this->view->assign ( 'vars', $vars );
		
		return $this->view->fetch ( 'blocks/shopblock_modify.tpl' );
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
		
		// write back the new contents
		$blockinfo ['content'] = BlockUtil::varsToContent ( $vars );
		
		// clear the block cache
		$this->view->clear_cache ( 'blocks/shopblock_modify.tpl' );
		
		return $blockinfo;
	}
}

// end class def
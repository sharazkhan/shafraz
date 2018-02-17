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
class ZSELEX_Block_Ishopproducts extends Zikula_Controller_AbstractBlock {
	public $amount;
	
	/**
	 * initialise block
	 */
	public function init() {
		SecurityUtil::registerPermissionSchema ( 'ZSELEX:ishopproducts:', 'Block title::' );
	}
	
	/**
	 * get information on block
	 */
	public function info() {
		return array (
				'text_type' => 'selection',
				'module' => 'ZSELEX',
				'text_type_long' => $this->__ ( 'Display Ishop Products' ),
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
		if (! SecurityUtil::checkPermission ( 'ZSELEX:ishopproducts:', "$blockinfo[title]::", ACCESS_OVERVIEW )) {
			return;
		}
		if (! ModUtil::available ( 'ZSELEX' )) {
			return;
		}
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		
		if (empty ( $vars ['shop_id'] )) {
			return;
		}
		
		// echo "comes here";
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
		
		// print_r($vars);
		// $pntable = pnDBGetTables();
		// $customercolumn = $pntable['zelex_shop__column'];
		// $where = "WHERE $customercolumn[country] = '" . pnVarPrepForStore($country) . "'";
		// $orderBy = "product_id";
		// $objArray = DBUtil::selectObjectArray ('zelex_products', $where = '', $orderBy);
		// print_r($vars);
		
		$sql = "SELECT * , s.shop_id as shop_id FROM zselex_products p , zselex_shop s 
                LEFT JOIN zselex_shop_owners ow ON ow.shop_id=s.shop_id
                LEFT JOIN users u ON u.uid = ow.user_id
                LEFT JOIN zselex_serviceshop sv ON sv.shop_id = s.shop_id AND sv.type='paybutton'
                where s.shop_id=$vars[shop_id] AND s.shop_id=p.shop_id  $orderby $limit";
		$items = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'execteQuery', $sql );
		// echo $sql;
		// echo "<pre>"; print_r($items); echo "</pre>";
		
		$this->view->assign ( 'vars', $vars );
		$this->view->assign ( 'ishops', $items );
		// $this->view->assign('shopconfig', $shopconfig);
		
		$blockinfo ['content'] = $this->view->fetch ( 'blocks/ishopproducts.tpl' );
		
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
		
		// $ishopargs = array('table' => 'zselex_shop', 'fields' => '', 'where' => array('shoptype_id' => '2'));
		// $ishops = ModUtil::apiFunc('ZSELEX', 'admin', 'selectItems', $ishopargs);
		
		$ishopargs = array (
				'table' => 'zselex_shop a , zselex_minishop b',
				'fields' => '',
				'where' => array (
						"a.shop_id=b.shop_id",
						"b.shoptype='iSHOP'" 
				) 
		);
		$ishops = ModUtil::apiFunc ( 'ZSELEX', 'user', 'selectArray', $ishopargs );
		// echo "<pre>"; print_r($adtypes); echo "</pre>";
		
		$this->view->assign ( 'vars', $vars );
		$this->view->assign ( 'ishops', $ishops );
		
		return $this->view->fetch ( 'blocks/ishopproducts_modify.tpl' );
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
		
		// write back the new contents
		$blockinfo ['content'] = BlockUtil::varsToContent ( $vars );
		
		// clear the block cache
		$this->view->clear_cache ( 'blocks/ishopproducts_modify.tpl' );
		
		return $blockinfo;
	}
}

// end class def
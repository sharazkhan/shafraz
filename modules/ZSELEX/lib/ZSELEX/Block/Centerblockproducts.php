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
class ZSELEX_Block_Centerblockproducts extends Zikula_Controller_AbstractBlock {
	
	/**
	 * initialise block
	 */
	public function init() {
		SecurityUtil::registerPermissionSchema ( 'ZSELEX:centerblockproductsblock:', 'Block title::' );
	}
	
	/**
	 * get information on block
	 */
	public function info() {
		return array (
				'text_type' => 'centerblockproducts',
				'module' => 'ZSELEX',
				'text_type_long' => $this->__ ( 'Center Product display' ),
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
		if (! SecurityUtil::checkPermission ( 'ZSELEX:centerblockproductsblock:', "$blockinfo[title]::", ACCESS_OVERVIEW )) {
			return;
		}
		if (! ModUtil::available ( 'ZSELEX' )) {
			return;
		}
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		
		$blockinfo ['content'] = $this->view->fetch ( 'blocks/centerblock.tpl' );
		
		return BlockUtil::themeBlock ( $blockinfo );
	}

/**
 * modify block settings .
 * .
 */
	// public function modify($blockinfo) {
	// $vars = BlockUtil::varsFromContent($blockinfo['content']);
	// if (empty($vars['showAdminZSELEXinBlock'])) {
	// $vars['showAdminZSELEXinBlock'] = 0;
	// }
	//
	// $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
	// //echo "<pre>"; print_r($shops); echo "</pre>";
	// $this->view->assign('vars', $vars);
	// $this->view->assign('zshops', $shops);
	//
	// return $this->view->fetch('blocks/selection_modify.tpl');
	// }

/**
 * update block settings
 */
	// public function update($blockinfo) {
	// $vars = BlockUtil::varsFromContent($blockinfo['content']);
	//
	// // alter the corresponding variable
	// $vars['showAdminZSELEXinBlock'] = FormUtil::getPassedValue('showAdminZSELEXinBlock', '', 'POST');
	// $vars['shop'] = FormUtil::getPassedValue('shop', '', 'POST');
	// $vars['amount'] = FormUtil::getPassedValue('amount', '', 'POST');
	// $vars['orderby'] = FormUtil::getPassedValue('orderby', '', 'POST');
	//
	// // write back the new contents
	// $blockinfo['content'] = BlockUtil::varsToContent($vars);
	//
	// // clear the block cache
	// $this->view->clear_cache('blocks/hello.tpl');
	//
	// return $blockinfo;
	// }
}

// end class def
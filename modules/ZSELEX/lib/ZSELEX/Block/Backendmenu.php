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
class ZSELEX_Block_Backendmenu extends Zikula_Controller_AbstractBlock {
	
	/**
	 * initialise block
	 */
	public function init() {
		SecurityUtil::registerPermissionSchema ( 'ZSELEX:Backendmenu :', 'Block title::' );
	}
	
	/**
	 * get information on block
	 */
	public function info() {
		return array (
				'text_type' => 'hello',
				'module' => 'ZSELEX',
				'text_type_long' => $this->__ ( 'Backendmenu' ),
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
		if (! SecurityUtil::checkPermission ( 'ZSELEX:Backendmenu:', "$blockinfo[title]::", ACCESS_OVERVIEW )) {
			return;
		}
		if (! ModUtil::available ( 'ZSELEX' )) {
			return;
		}
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		
		// echo "<pre>"; print_r($vars); echo "</pre>";
		// echo "<pre>"; print_r($products); echo "</pre>";
		// echo $shopconfig['dbname'];
		$this->view->assign ( 'vars', $vars );
		
		$blockinfo ['content'] = $this->view->fetch ( 'blocks/backendmenu/backendmenu.tpl' );
		
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
		
		// $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
		
		$this->view->assign ( 'vars', $vars );
		
		return $this->view->fetch ( 'blocks/backendmenu/backendmenu_modify.tpl' );
	}
	
	/**
	 * update block settings
	 */
	public function update($blockinfo) {
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		
		// alter the corresponding variable
		
		// write back the new contents
		$blockinfo ['content'] = BlockUtil::varsToContent ( $vars );
		
		// clear the block cache
		$this->view->clear_cache ( 'blocks/backendmenu/backendmenu.tpl' );
		
		return $blockinfo;
	}
}

// end class def
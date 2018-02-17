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
class ZSELEX_Block_Servicemenu extends Zikula_Controller_AbstractBlock {
	public $amount;
	
	/**
	 * initialise block
	 */
	public function init() {
		SecurityUtil::registerPermissionSchema ( 'ZSELEX:servicemenu:', 'Block title::' );
	}
	
	/**
	 * get information on block
	 */
	public function info() {
		return array (
				'text_type' => 'selection',
				'module' => 'ZSELEX',
				'text_type_long' => $this->__ ( 'Shop Services Menu' ),
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
		if (! SecurityUtil::checkPermission ( 'ZSELEX::', "::", ACCESS_EDIT )) {
			return;
		}
		
		if (! SecurityUtil::checkPermission ( 'ZSELEX:servicemenu:', "$blockinfo[title]::", ACCESS_OVERVIEW )) {
			return;
		}
		if (! ModUtil::available ( 'ZSELEX' )) {
			return;
		}
		$admin = SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_ADMIN );
		$edit = SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_EDIT );
		
		if ($admin) {
			$perm = $admin;
		} else if ($edit) {
			$perm = $edit;
		} else {
			$perm = '';
		}
		
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		
		$thislang = ZLanguage::getLanguageCode ();
		
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
		
		// echo "<pre>"; print_r($vars); echo "</pre>";
		
		$shop_id = ! empty ( $_REQUEST ['shop_id'] ) ? FormUtil::getPassedValue ( 'shop_id', '', 'REQUEST' ) : $_REQUEST ['shop_idnewItem'];
		
		if (empty ( $shop_id )) {
			
			return;
		}
		
		$user_id = UserUtil::getVar ( 'uid' );
		$services = ModUtil::apiFunc ( 'ZSELEX', 'user', 'shopServicesMenu', $args = array (
				'user_id' => $user_id,
				'shop_id' => $shop_id 
		) );
		
		if (count ( $services ) < 1) {
			return false;
		}
		$this->view->assign ( 'perm', $perm );
		$info ['title'] = $vars ['blockinfo'] [$thislang] ['infotitle'];
		// $info['message'] = $vars['blockinfo'][$thislang]['infomessage'];
		// echo "<pre>"; print_r($type); echo "</pre>";
		$this->view->assign ( 'bid', $blockinfo ['bid'] );
		$this->view->assign ( 'info', $info );
		$this->view->assign ( 'vars', $vars );
		$this->view->assign ( 'services', $services );
		
		$blockinfo ['content'] = $this->view->fetch ( 'blocks/servicemenu.tpl' );
		
		return BlockUtil::themeBlock ( $blockinfo );
	}
	
	/**
	 * modify block settings .
	 * .
	 */
	public function modify($blockinfo) {
		
		// echo "hellloooooooooo";
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		if (empty ( $vars ['showAdminZSELEXinBlock'] )) {
			$vars ['showAdminZSELEXinBlock'] = 0;
		}
		$languages = ZLanguage::getInstalledLanguages ();
		$this->view->assign ( 'languages', $languages );
		$this->view->assign ( 'vars', $vars );
		
		return $this->view->fetch ( 'blocks/servicemenu_modify.tpl' );
	}
	
	/**
	 * update block settings
	 */
	public function update($blockinfo) {
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		
		$vars ['displayinfo'] = FormUtil::getPassedValue ( 'displayinfo', '', 'POST' );
		$vars ['blockinfo'] = FormUtil::getPassedValue ( 'blockinfo', '', 'POST' );
		
		$blockinfo ['content'] = BlockUtil::varsToContent ( $vars );
		
		// clear the block cache
		$this->view->clear_cache ( 'blocks/servicemenu_modify.tpl' );
		
		return $blockinfo;
	}
}

// end class def
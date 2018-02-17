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
class ZSELEX_Block_Gallery extends Zikula_Controller_AbstractBlock {
	public $amount;
	
	/**
	 * initialise block
	 */
	public function init() {
		SecurityUtil::registerPermissionSchema ( 'ZSELEX:gallery:', 'Block title::' );
	}
	
	/**
	 * get information on block
	 */
	public function info() {
		return array (
				'text_type' => 'selection',
				'module' => 'ZSELEX',
				'text_type_long' => $this->__ ( 'Gallery Block' ),
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
		
		// print_r($_REQUEST);
		// return false;
		if (! SecurityUtil::checkPermission ( 'ZSELEX:gallery:', "$blockinfo[title]::", ACCESS_OVERVIEW )) {
			return;
		}
		if (! ModUtil::available ( 'ZSELEX' )) {
			// return;
		}
		$loguser = UserUtil::getVar ( 'uid' );
		$loguser = ! empty ( $loguser ) ? $loguser : 0;
		$user_id = $loguser;
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		$shop_id = ! empty ( $_REQUEST ['shop_id'] ) ? $_REQUEST ['shop_id'] : $_REQUEST ['shop_idnewItem'];
		
		// /////////////////check service exists//////////////////////////
		
		$serviceExist = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'serviceExistBlock', $args = array (
				'shop_id' => $shop_id,
				'type' => 'minisitegallery' 
		) );
		
		if ($serviceExist < 1) {
			return;
		}
		
		$servicePerm = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'shopPermission', array (
				'shop_id' => $shop_id,
				'user_id' => $user_id 
		) );
		// echo "owner/admin : " . $servicePerm;
		// echo "count gallery :" . $servicePermCount;
		
		$admin = SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_ADMIN );
		$edit = SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_EDIT );
		
		if ($admin) {
			$perm = $admin;
		} else if ($edit) {
			$perm = $edit;
		} else {
			$perm = '';
		}
		
		if (empty ( $shop_id )) {
			return;
		}
		$thislang = ZLanguage::getLanguageCode ();
		
		// $images = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray', $args = array('table' => 'zselex_files',
		// 'where' => array("shop_id=$shop_id")
		// ));
		
		$galleryImages = ModUtil::apiFunc ( 'ZSELEX', 'block', 'getGalleryBlockImages', $args = array (
				'shop_id' => $shop_id 
		) );
		
		if (count ( $galleryImages ) < 1) {
			return;
		}
		
		// echo "<pre>"; print_r($galleryImages); echo "</pre>";
		// if(count($images) < 1){
		// return;
		// }
		// echo "<pre>"; print_r($vars); echo "</pre>";
		$ownerName = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getOwner', $args = array (
				'shop_id' => $shop_id 
		) );
		
		// $image_split = array_chunk($galleryImages , 2);
		// echo "<pre>"; print_r($image_split); echo "</pre>";
		$info ['title'] = $vars ['blockinfo'] [$thislang] ['infotitle'];
		$info ['message'] = $vars ['blockinfo'] [$thislang] ['infomessage'];
		
		$this->view->assign ( 'check', "comes here galleryyyyyy" );
		$this->view->assign ( 'admin', $admin );
		
		$this->view->assign ( 'perm', $perm );
		$this->view->assign ( 'servicePerm', $servicePerm );
		$this->view->assign ( 'add', $add );
		$this->view->assign ( 'bid', $blockinfo ['bid'] );
		$this->view->assign ( 'info', $info );
		$this->view->assign ( 'vars', $vars );
		$this->view->assign ( 'shoptype', $shopType );
		$this->view->assign ( 'ownerName', $ownerName );
		$this->view->assign ( 'images', $galleryImages );
		// $this->view->assign('image_split', $image_split);
		// $this->view->assign('shopconfig', $shopconfig);
		
		$blockinfo ['content'] = $this->view->fetch ( 'blocks/gallery/gallery.tpl' );
		
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
		
		return $this->view->fetch ( 'blocks/gallery/gallery_modify.tpl' );
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
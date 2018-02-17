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
class ZSELEX_Block_Pdfimagedisplay extends Zikula_Controller_AbstractBlock {
	
	/**
	 * initialise block
	 */
	public function init() {
		SecurityUtil::registerPermissionSchema ( 'ZSELEX:pdf_imagedisplay :', 'Block title::' );
	}
	
	/**
	 * get information on block
	 */
	public function info() {
		return array (
				'text_type' => 'selection',
				'module' => 'ZSELEX',
				'text_type_long' => $this->__ ( 'PDF Images Block' ),
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
		// echo "<pre>"; print_r($blockinfo); echo "</pre>";
		if (! SecurityUtil::checkPermission ( 'ZSELEX:pdf_imagedisplay:', "$blockinfo[title]::", ACCESS_OVERVIEW )) {
			return;
		}
		if (! ModUtil::available ( 'ZSELEX' )) {
			return;
		}
		$shop_id = ! empty ( $_REQUEST ['shop_id'] ) ? $_REQUEST ['shop_id'] : $_REQUEST ['shop_idnewItem'];
		
		if (empty ( $shop_id )) {
			return;
		}
		
		// echo "shop id : " . $shop_id; exit;
		$loguser = UserUtil::getVar ( 'uid' );
		$loguser = ! empty ( $loguser ) ? $loguser : 0;
		$user_id = $loguser;
		$admin = SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_ADMIN );
		$edit = SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_EDIT );
		
		if ($admin) {
			$perm = $admin;
		} else if ($edit) {
			$perm = $edit;
		} else {
			$perm = '';
		}
		
		// /////////////////check service exists//////////////////////////
		
		/*
		 * $serviceExist = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $args = array('table' => 'zselex_serviceshop',
		 * 'where' => "shop_id=$shop_id AND type='pdfupload'"));
		 *
		 */
		$serviceExist = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'serviceExistBlock', $args = array (
				'shop_id' => $shop_id,
				'type' => 'pdfupload' 
		) );
		
		// echo $serviceExist;
		if ($serviceExist < 1) {
			return false;
		}
		
		// ////////////////////////////////////////////////////////
		// $servicePerm = 1;
		// //////////////////////service permission/////////////////////////////////////
		/*
		 * if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
		 * $servicePermCount = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $args = array('table' => 'zselex_serviceshop',
		 * 'where' => "shop_id=$shop_id AND type='pdfupload'
		 * AND(owner_id=$loguser OR owner_id
		 * IN(SELECT owner_id FROM zselex_shop_admins WHERE user_id=$loguser))"));
		 *
		 * if ($servicePermCount > 0) {
		 * $servicePerm = 1;
		 * } else {
		 * $servicePerm = 0;
		 * }
		 * } elseif (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
		 * $servicePerm = 2;
		 * $servicePermCount = 2;
		 * }
		 */
		// /////////////////////////////////////////////////////////////////
		// echo "count minisiteimage :" . $servicePermCount;
		
		$servicePerm = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'shopPermission', array (
				'shop_id' => $shop_id,
				'user_id' => $user_id 
		) );
		
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		$thislang = ZLanguage::getLanguageCode ();
		if (! array_key_exists ( $thislang, $vars ['blockinfo'] )) {
			$thislang = 'en';
		}
		
		$shop_id = ! empty ( $_REQUEST ['shop_id'] ) ? $_REQUEST ['shop_id'] : $_REQUEST ['shop_idnewItem'];
		
		if (empty ( $shop_id )) {
			return false;
		}
		// echo "<pre>"; print_r(); echo "</pre>";
		// echo $vars['limit'];
		if (is_numeric ( $vars ['limit'] ) && $vars ['limit'] != '') {
			$limit = "LIMIT 0,$vars[limit]";
		} else {
			
			$limit = '';
		}
		
		$images = ModUtil::apiFunc ( 'ZSELEX', 'user', 'selectArray', $args = array (
				'table' => 'zselex_shop_pdf',
				'where' => array (
						"shop_id=$shop_id" 
				),
				'orderby' => 'RAND()',
				'limit' => $limit 
		) );
		$ownerName = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getOwner', $args = array (
				'shop_id' => $shop_id 
		) );
		
		if (count ( $images ) < 1) {
			return;
		}
		
		// echo "<pre>"; print_r($images); echo "</pre>";
		// echo $blockinfo['bid'];
		
		$info ['title'] = $vars ['blockinfo'] [$thislang] ['infotitle'];
		// $info['message'] = $vars['blockinfo'][$thislang]['infomessage'];
		// echo "<pre>"; print_r($info); echo "</pre>";
		$this->view->assign ( 'perm', $perm );
		$this->view->assign ( 'servicePerm', $servicePerm );
		$this->view->assign ( 'bid', $blockinfo ['bid'] );
		$this->view->assign ( 'info', $info );
		$this->view->assign ( 'vars', $vars );
		$this->view->assign ( 'admin', $admin );
		$this->view->assign ( 'add', $add );
		$this->view->assign ( 'shoptype', $shopType );
		$this->view->assign ( 'ownerName', $ownerName );
		$this->view->assign ( 'images', $images );
		// $this->view->assign('shopconfig', $shopconfig);
		
		$blockinfo ['content'] = $this->view->fetch ( 'blocks/pdfimagesblock/pdfimages.tpl' );
		
		return BlockUtil::themeBlock ( $blockinfo );
	}
	public function getInfo($blockinfo) {
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
		
		// echo "<pre>"; print_r($adtypes); echo "</pre>";
		
		$languages = ZLanguage::getInstalledLanguages ();
		
		// echo "<pre>"; print_r($languages); echo "</pre>";
		
		$this->view->assign ( 'languages', $languages );
		$this->view->assign ( 'vars', $vars );
		$this->view->assign ( 'ishops', $ishops );
		
		return $this->view->fetch ( 'blocks/pdfimagesblock/pdfimages_modify.tpl' );
	}
	
	/**
	 * update block settings
	 */
	public function update($blockinfo) {
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		
		// echo "<pre>"; print_r(FormUtil::getPassedValue('blockinfo', '', 'POST')); echo "</pre>"; exit;
		// alter the corresponding variable
		$vars ['showAdminZSELEXinBlock'] = FormUtil::getPassedValue ( 'showAdminZSELEXinBlock', '', 'POST' );
		
		$vars ['limit'] = FormUtil::getPassedValue ( 'limit', '', 'POST' );
		
		$vars ['displayinfo'] = FormUtil::getPassedValue ( 'displayinfo', '', 'POST' );
		// $vars['infotitle'] = FormUtil::getPassedValue('infotitle', '', 'POST');
		// $vars['infomessage'] = FormUtil::getPassedValue('infomessage', '', 'POST');
		
		$vars ['blockinfo'] = FormUtil::getPassedValue ( 'blockinfo', '', 'POST' );
		
		// write back the new contents
		$blockinfo ['content'] = BlockUtil::varsToContent ( $vars );
		
		// clear the block cache
		$this->view->clear_cache ( 'blocks/pdfimagesblock/pdfimages_modify.tpl' );
		
		return $blockinfo;
	}
}

// end class def
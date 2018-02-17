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
class ZSELEX_Block_Sociallinks extends Zikula_Controller_AbstractBlock {
	
	/**
	 * initialise block // similar to constructor
	 */
	public function init() {
		// echo "helloo";
		SecurityUtil::registerPermissionSchema ( 'ZSELEX:sociallinks:', 'Block title::' );
	}
	
	/**
	 * get information on block
	 */
	public function info() {
		return array (
				'text_type' => 'selection',
				'module' => 'ZSELEX',
				'text_type_long' => $this->__ ( 'Social Links Block' ),
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
		// return;
		// echo "<pre>"; print_r($blockinfo); echo "</pre>";
		// echo $this->ownername;
		if (! SecurityUtil::checkPermission ( 'ZSELEX:sociallinks:', "$blockinfo[title]::", ACCESS_OVERVIEW )) {
			return;
		}
		if (! ModUtil::available ( 'ZSELEX' )) {
			return;
		}
		$shop_id = ! empty ( $_REQUEST ['shop_id'] ) ? $_REQUEST ['shop_id'] : $_REQUEST ['shop_idnewItem'];
		$loguser = UserUtil::getVar ( 'uid' );
		$loguser = ! empty ( $loguser ) ? $loguser : 0;
		$user_id = $loguser;
		
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		$thislang = ZLanguage::getLanguageCode ();
		
		$shop_id = ! empty ( $_REQUEST ['shop_id'] ) ? $_REQUEST ['shop_id'] : $_REQUEST ['shop_idnewItem'];
		
		if (empty ( $shop_id )) {
			return false;
		}
		
		$serviceExist = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'serviceExistBlock', $args = array (
				'shop_id' => $shop_id,
				'type' => 'sociallinks' 
		) );
		if ($serviceExist < 1) {
			return;
		}
		
		// $servicePerm = ModUtil::apiFunc('ZSELEX', 'admin', 'shopPermission', array('shop_id' => $shop_id, 'user_id' => $user_id));
		// $event = $this->getEvent($args = array('shop_id' => $shop_id));
		
		$repo = $this->entityManager->getRepository ( 'ZSELEX_Entity_Shop' );
		$soc_args = array (
				'entity' => 'ZSELEX_Entity_SocialLinkShop',
				'where' => array (
						'a.shop' => $shop_id 
				),
				'joins' => array (
						'JOIN a.socl_links b' 
				),
				'fields' => array (
						'b.socl_image',
						'b.socl_link_name',
						'b.status',
						'a.link_url' 
				),
				'orderby' => 'b.socl_link_id ASC' 
		);
		
		// $soc_args['fields'] = array('a.socl_link_id', 'a.socl_link_name', 'a.socl_image', 'a.status');
		
		$social_links = $repo->getAll ( $soc_args );
		// echo "<pre>"; print_r($social_links); echo "</pre>";
		
		$soc_setting_args = array (
				'entity' => 'ZSELEX_Entity_SocialLinkShopSetting',
				'where' => array (
						'a.shop' => $shop_id 
				),
				'fields' => array (
						'a.icon_size' 
				) 
		);
		
		// $soc_args['fields'] = array('a.socl_link_id', 'a.socl_link_name', 'a.socl_image', 'a.status');
		$icon_size = "small";
		$social_links_setting = $repo->get ( $soc_setting_args );
		if (! empty ( $social_links_setting ['icon_size'] )) {
			$icon_size = $social_links_setting ['icon_size'];
		}
		
		$perm = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'shopPermission', array (
				'shop_id' => $shop_id,
				'user_id' => $user_id 
		) );
		
		$this->view->assign ( 'count', $count );
		$this->view->assign ( 'perm', $perm );
		$this->view->assign ( 'servicePerm', $servicePerm );
		$this->view->assign ( 'bid', $blockinfo ['bid'] );
		$this->view->assign ( 'info', $info );
		$this->view->assign ( 'vars', $vars );
		$this->view->assign ( 'admin', $admin );
		$this->view->assign ( 'social_links', $social_links );
		$this->view->assign ( 'icon_size', $icon_size );
		
		$this->view->assign ( 'shop_id', $shop_id );
		
		// $this->view->assign('shopconfig', $shopconfig);
		
		$blockinfo ['content'] = $this->view->fetch ( 'blocks/sociallinks/sociallinks.tpl' );
		
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
		
		// echo "<pre>"; print_r($vars); echo "</pre>";
		
		$blockinfo = $vars ['blockinfo'];
		if (! empty ( $blockinfo )) {
			
			$exist = true;
		} else {
			
			$exist = false;
		}
		
		// echo $exist;
		
		$languages = ZLanguage::getInstalledLanguages ();
		
		// echo "<pre>"; print_r($languages); echo "</pre>";
		
		$this->view->assign ( 'languages', $languages );
		$this->view->assign ( 'vars', $vars );
		$this->view->assign ( 'exist', $exist );
		$this->view->assign ( 'blockinfo', $blockinfo );
		
		return $this->view->fetch ( 'blocks/sociallinks/sociallinks_modify.tpl' );
	}
	
	/**
	 * update block settings
	 */
	public function update($blockinfo) {
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		
		$vars ['displayinfo'] = FormUtil::getPassedValue ( 'displayinfo', '', 'POST' );
		
		$vars ['blockinfo'] = FormUtil::getPassedValue ( 'blockinfo', '', 'POST' );
		
		// write back the new contents
		$blockinfo ['content'] = BlockUtil::varsToContent ( $vars );
		
		// clear the block cache
		$this->view->clear_cache ( 'blocks/minisiteevent/minisiteevent_modify.tpl' );
		
		return $blockinfo;
	}
}

// end class def
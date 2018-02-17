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
class ZSELEX_Block_Eventcalender extends Zikula_Controller_AbstractBlock {
	
	/**
	 * initialise block
	 */
	public function init() {
		SecurityUtil::registerPermissionSchema ( 'ZSELEX:eventcalendar :', 'Block title::' );
	}
	
	/**
	 * get information on block
	 */
	public function info() {
		return array (
				'text_type' => 'selection',
				'module' => 'ZSELEX',
				'text_type_long' => $this->__ ( 'Event Calendar' ),
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
		if (! SecurityUtil::checkPermission ( 'ZSELEX:eventcalendar:', "$blockinfo[title]::", ACCESS_OVERVIEW )) {
			return;
		}
		if (! ModUtil::available ( 'ZSELEX' )) {
			return;
		}
		
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		$thislang = ZLanguage::getLanguageCode ();
		if (! array_key_exists ( $thislang, $vars ['blockinfo'] )) {
			$thislang = 'en';
		}
		
		// echo "<pre>"; print_r($events); echo "</pre>";
		// echo $blockinfo['bid'];
		// ZSELEX_Controller_User::display($args);
		$info ['title'] = $vars ['blockinfo'] [$thislang] ['infotitle'];
		// $info['message'] = $vars['blockinfo'][$thislang]['infomessage'];
		// echo "<pre>"; print_r($info); echo "</pre>";
		$this->view->assign ( 'perm', $perm );
		$this->view->assign ( 'servicePerm', $servicePermCount );
		$this->view->assign ( 'bid', $blockinfo ['bid'] );
		$this->view->assign ( 'info', $info );
		$this->view->assign ( 'vars', $vars );
		$this->view->assign ( 'admin', $admin );
		$this->view->assign ( 'add', $add );
		$this->view->assign ( 'ownerName', $ownerName );
		// $this->view->assign('events', $events);
		// $this->view->assign('shopconfig', $shopconfig);
		
		$blockinfo ['content'] = $this->view->fetch ( 'blocks/eventcalendar/eventcalendar.tpl' );
		
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
		
		return $this->view->fetch ( 'blocks/eventcalendar/eventcalendar_modify.tpl' );
	}
	
	/**
	 * update block settings
	 */
	public function update($blockinfo) {
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		
		$vars ['displayinfo'] = FormUtil::getPassedValue ( 'displayinfo', '', 'POST' );
		$vars ['eventlimit'] = FormUtil::getPassedValue ( 'eventlimit', '', 'POST' );
		$vars ['blockinfo'] = FormUtil::getPassedValue ( 'blockinfo', '', 'POST' );
		
		// write back the new contents
		$blockinfo ['content'] = BlockUtil::varsToContent ( $vars );
		
		// clear the block cache
		$this->view->clear_cache ( 'blocks/minisiteevent/minisiteevent_modify.tpl' );
		
		return $blockinfo;
	}
}

// end class def
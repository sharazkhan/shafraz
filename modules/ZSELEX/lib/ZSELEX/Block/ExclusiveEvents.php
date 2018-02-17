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
class ZSELEX_Block_ExclusiveEvents extends Zikula_Controller_AbstractBlock {
	
	/**
	 * initialise block
	 */
	public function init() {
		SecurityUtil::registerPermissionSchema ( 'ZSELEX:ExclusiveEvents:', 'Block title::' );
	}
	
	/**
	 * get information on block
	 */
	public function info() {
		return array (
				'text_type' => 'Exclusive Events',
				'module' => 'ZSELEX',
				'text_type_long' => $this->__ ( 'Exclusive Events' ),
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
		// echo "helloooooo";
		if (! SecurityUtil::checkPermission ( 'ZSELEX:ExclusiveEvents:', "$blockinfo[title]::", ACCESS_OVERVIEW )) {
			// return;
		}
		if (! ModUtil::available ( 'ZSELEX' )) {
			return;
		}
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		
		// $shop_id = FormUtil::getPassedValue('shop_id', isset($args['shop_id']) ? $args['shop_id'] : null, 'REQUEST');
		// $shop_id = 26;
		if (empty ( $shop_id )) {
			// return;
		}
		
		/*
		 * $serviceExist = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceExistBlock', $args = array('shop_id' => $shop_id,
		 * 'type' => 'exclusiveevent'));
		 *
		 * // echo $serviceExist;
		 * if ($serviceExist < 1) {
		 * // return;
		 * }
		 */
		/*
		 * $getEvents = ModUtil::apiFunc('ZSELEX', 'user', 'getAll', $args = array(
		 * 'table' => 'zselex_shop_events',
		 * 'where' => "shop_id=$shop_id",
		 * 'itemsperpage' => 4
		 * ));
		 */
		// $getEvents = ModUtil::apiFunc('ZSELEX', 'user', 'getExclusiveEvent');
		
		$loguser = UserUtil::getVar ( 'uid' );
		$loguser = ! empty ( $loguser ) ? $loguser : 0;
		$user_id = $loguser;
		// $perm = ModUtil::apiFunc('ZSELEX', 'admin', 'shopPermission', array('shop_id' => $shop_id, 'user_id' => $user_id));
		// $getEmployeesFinal = array_chunk($getEmployees, 2);
		// echo "<pre>"; print_r($getEvents); echo "</pre>";
		// echo $shopconfig['dbname'];
		$this->view->assign ( 'vars', $vars );
		$this->view->assign ( 'perm', $perm );
		$this->view->assign ( 'events', $getEvents );
		
		$blockinfo ['content'] = $this->view->fetch ( 'blocks/exclusiveevents/exclusive_events.tpl' );
		
		return BlockUtil::themeBlock ( $blockinfo );
	}
	
	/**
	 * modify block settings .
	 * .
	 */
	public function modify($blockinfo) {
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		
		// $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
		
		$shops = ModUtil::apiFunc ( 'ZSELEX', 'user', 'selectArray', $args = array (
				'table' => 'zselex_shop s , zselex_minishop m',
				'where' => array (
						"s.shop_id=m.shop_id",
						"m.shoptype='zSHOP'" 
				) 
		) );
		// echo "<pre>"; print_r($shops); echo "</pre>";
		$this->view->assign ( 'vars', $vars );
		$this->view->assign ( 'zshops', $shops );
		
		return $this->view->fetch ( 'blocks/exclusiveevents/exclusive_events_modify.tpl' );
	}
	
	/**
	 * update block settings
	 */
	public function update($blockinfo) {
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		
		// alter the corresponding variable
		
		$vars ['shop'] = FormUtil::getPassedValue ( 'shop', '', 'POST' );
		$vars ['amount'] = FormUtil::getPassedValue ( 'amount', '', 'POST' );
		$vars ['orderby'] = FormUtil::getPassedValue ( 'orderby', '', 'POST' );
		
		// write back the new contents
		$blockinfo ['content'] = BlockUtil::varsToContent ( $vars );
		
		// clear the block cache
		$this->view->clear_cache ( 'blocks/exclusiveevents/exclusive_events.tpl' );
		
		return $blockinfo;
	}
}

// end class def
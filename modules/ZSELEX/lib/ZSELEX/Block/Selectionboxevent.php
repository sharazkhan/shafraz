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
class ZSELEX_Block_Selectionboxevent extends Zikula_Controller_AbstractBlock {
	
	/**
	 * initialise block
	 */
	public function init() {
		SecurityUtil::registerPermissionSchema ( 'ZSELEX:selectionblockboxevent:', 'Block title::' );
	}
	
	/**
	 * get information on block
	 */
	public function info() {
		return array (
				'text_type' => 'selection',
				'module' => 'ZSELEX',
				'text_type_long' => $this->__ ( 'Selection drop down box for events' ),
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
		if (! SecurityUtil::checkPermission ( 'ZSELEX:selectionblockboxevent:', "$blockinfo[title]::", ACCESS_OVERVIEW )) {
			return;
		}
		if (! ModUtil::available ( 'ZSELEX' )) {
			return;
		}
		$thislang = ZLanguage::getLanguageCode ();
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		
		$input = FormUtil::getPassedValue ( 'region', '', 'POST' );
		
		// echo "Region: " . $input;
		// echo "<pre>"; print_r($vars); echo "</pre>";
		
		$countryargs = array (
				'table' => 'zselex_country',
				'fields' => array (
						"country_id , country_name" 
				),
				'orderby' => 'country_name ASC' 
		);
		$countries = ModUtil::apiFunc ( 'ZSELEX', 'user', 'selectArray', $countryargs );
		$countryCount = count ( $country );
		
		// echo "<pre>"; print_r($country); echo "</pre>";
		
		$regionargs = array (
				'table' => 'zselex_region',
				'fields' => array (
						"region_id , region_name" 
				),
				'orderby' => 'region_name ASC' 
		);
		$regions = ModUtil::apiFunc ( 'ZSELEX', 'user', 'selectArray', $regionargs );
		
		// echo "<pre>"; print_r($regions); echo "</pre>";
		$cityargs = array (
				'table' => 'zselex_city',
				'fields' => array (
						"city_id , city_name" 
				),
				'orderby' => 'city_name ASC' 
		);
		$cities = ModUtil::apiFunc ( 'ZSELEX', 'user', 'selectArray', $cityargs );
		
		$shopargs = array (
				'table' => 'zselex_shop',
				'fields' => array (
						"shop_id , shop_name" 
				),
				'orderby' => 'shop_name ASC' 
		);
		$shops = ModUtil::apiFunc ( 'ZSELEX', 'user', 'selectArray', $shopargs );
		
		$areaargs = array (
				'table' => 'zselex_area',
				'fields' => array (
						"area_id , area_name" 
				),
				'orderby' => 'area_name ASC' 
		);
		$areas = ModUtil::apiFunc ( 'ZSELEX', 'user', 'selectArray', $areaargs );
		
		$branchargs = array (
				'table' => 'zselex_branch',
				'where' => '',
				'orderBy' => '',
				'useJoins' => '' 
		);
		$branchs = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getElements', $branchargs );
		
		$catargs = array (
				'table' => 'zselex_category',
				'where' => '',
				'orderBy' => '',
				'useJoins' => '' 
		);
		$category = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getElements', $catargs );
		// echo "<pre>"; print_r($branchs); echo "</pre>"; exit;
		// echo count($products);
		// $shopconfig = ModUtil::apiFunc('ZSELEX', 'admin', 'getShopConfig', $vars);
		// echo "<pre>"; print_r($shopconfig); echo "</pre>";
		// echo $shopconfig['dbname'];
		
		$countrysrch = $_GET ['country'];
		$regionsrch = $_GET ['region'];
		$citysrch = $_GET ['city'];
		$shopsrch = $_GET ['shop'];
		$info ['title'] = $vars ['blockinfo'] [$thislang] ['infotitle'];
		$info ['message'] = $vars ['blockinfo'] [$thislang] ['infomessage'];
		
		$this->view->assign ( 'bid', $blockinfo ['bid'] );
		$this->view->assign ( 'vars', $vars );
		$this->view->assign ( 'info', $info );
		$this->view->assign ( 'countryCount', $countryCount );
		$this->view->assign ( 'countries', $countries );
		$this->view->assign ( 'regions', $regions );
		$this->view->assign ( 'cities', $cities );
		$this->view->assign ( 'areas', $areas );
		$this->view->assign ( 'shops', $shops );
		$this->view->assign ( 'shopconfig', $shopconfig );
		$this->view->assign ( 'zshops', $shops );
		$this->view->assign ( 'category', $category );
		$this->view->assign ( 'branchs', $branchs );
		$this->view->assign ( 'countrysrch', $countrysrch );
		$this->view->assign ( 'regsrch', $regionsrch );
		$this->view->assign ( 'citysrch', $citysrch );
		$this->view->assign ( 'shopsrch', $shopsrch );
		
		$blockinfo ['content'] = $this->view->fetch ( 'blocks/selection.tpl' );
		
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
		$languages = ZLanguage::getInstalledLanguages ();
		$shops = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getShop', $items );
		// echo "<pre>"; print_r($shops); echo "</pre>";
		$this->view->assign ( 'vars', $vars );
		$this->view->assign ( 'languages', $languages );
		$this->view->assign ( 'zshops', $shops );
		
		return $this->view->fetch ( 'blocks/selection_modify.tpl' );
	}
	
	/**
	 * update block settings
	 */
	public function update($blockinfo) {
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		
		// alter the corresponding variable
		$vars ['showAdminZSELEXinBlock'] = FormUtil::getPassedValue ( 'showAdminZSELEXinBlock', '', 'POST' );
		$vars ['shop'] = FormUtil::getPassedValue ( 'shop', '', 'POST' );
		$vars ['amount'] = FormUtil::getPassedValue ( 'amount', '', 'POST' );
		$vars ['orderby'] = FormUtil::getPassedValue ( 'orderby', '', 'POST' );
		
		$vars ['displayinfo'] = FormUtil::getPassedValue ( 'displayinfo', '', 'POST' );
		
		$vars ['blockinfo'] = FormUtil::getPassedValue ( 'blockinfo', '', 'POST' );
		
		// write back the new contents
		$blockinfo ['content'] = BlockUtil::varsToContent ( $vars );
		
		// clear the block cache
		$this->view->clear_cache ( 'blocks/selection_modify.tpl' );
		
		return $blockinfo;
	}
}

// end class def
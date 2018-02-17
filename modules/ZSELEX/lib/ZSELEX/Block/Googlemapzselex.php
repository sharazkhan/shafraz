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
class ZSELEX_Block_Googlemapzselex extends Zikula_Controller_AbstractBlock {
	public $amount;
	
	/**
	 * initialise block
	 */
	public function init() {
		SecurityUtil::registerPermissionSchema ( 'ZSELEX:googlemapzselex:', 'Block title::' );
	}
	
	/**
	 * get information on block
	 */
	public function info() {
		return array (
				'text_type' => 'selection',
				'module' => 'ZSELEX',
				'text_type_long' => $this->__ ( 'Google Map For ZSELEX' ),
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
		if (! SecurityUtil::checkPermission ( 'ZSELEX:googlemapzselex:', "$blockinfo[title]::", ACCESS_OVERVIEW )) {
			return;
		}
		if (! ModUtil::available ( 'ZSELEX' )) {
			return;
		}
		
		// $ip = $_SERVER['REMOTE_ADDR'];
		$ip = "122.166.29.86";
		
		// $json = file_get_contents("http://api.easyjquery.com/ips/?ip=" . $ip . "&full=true");
		// $json = json_decode($json, true);
		// echo "<pre>"; print_r($json); echo "</pre>";
		
		$userCountry = $json ['CountryName'];
		
		// $state = $json['RegionName'];
		// $city = $json['CityName'];
		
		$loguser = UserUtil::getVar ( 'uid' );
		$loguser = ! empty ( $loguser ) ? $loguser : 0;
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		
		/*
		 * $shop_id = !empty($_REQUEST['shop_id']) ? $_REQUEST['shop_id'] : $_REQUEST['shop_idnewItem'];
		 *
		 * $cntry = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow', array('table' => 'zselex_country c , zselex_parent p', 'fields' => array('c.country_name'),
		 * 'where' => array("p.childId='" . $shop_id . "'", "p.childType='SHOP'", "p.parentType='COUNTRY'", "p.parentId=c.country_id")
		 * ));
		 * $country = $cntry['country_name'];
		 *
		 *
		 * $cit = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow', array('table' => 'zselex_city c , zselex_parent p', 'fields' => array('c.city_name'),
		 * 'where' => array("p.childId='" . $shop_id . "'", "p.childType='SHOP'", "p.parentType='CITY'", "p.parentId=c.city_id")
		 * ));
		 * $city = $cit['city_name'];
		 *
		 * $a = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow', array('table' => 'zselex_shop', 'fields' => array('address'),
		 * 'where' => array("shop_id='" . $shop_id . "'")
		 * ));
		 *
		 */
		
		if ($vars ['centerfunc'] == true) {
			$add = str_replace ( " ", "%20", $a ['address'] );
			$city = $city;
			$state = '';
			$country = $userCountry;
			$zip = '';
			
			$geocode = file_get_contents ( 'http://maps.google.com/maps/api/geocode/json?address=' . $add . ',+' . $city . ',+' . $state . ',+' . $country . '&sensor=false' );
			
			$output = json_decode ( $geocode ); // Store values in variable
			$lat = $output->results [0]->geometry->location->lat;
			$long = $output->results [0]->geometry->location->lng;
		} else {
			$lat = '';
			$long = '';
		}
		
		if (empty ( $shop_id )) {
			// return false;
		}
		$thislang = ZLanguage::getLanguageCode ();
		
		// $images = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray', $args = array('table' => 'zselex_files',
		// 'where' => array("shop_id=$shop_id")
		// ));
		// echo "lat :" . $lat . "" . "lng :" .$long;
		// echo "<pre>"; print_r($vars); echo "</pre>";
		
		$this->view->assign ( 'centerfunc', $vars ['centerfunc'] );
		$this->view->assign ( 'userCountry', $userCountry );
		
		$this->view->assign ( 'lat', $lat );
		$this->view->assign ( 'long', $long );
		$this->view->assign ( 'bid', $blockinfo ['bid'] );
		$this->view->assign ( 'info', $info );
		$this->view->assign ( 'vars', $vars );
		
		$blockinfo ['content'] = $this->view->fetch ( 'blocks/googlemapzselex/gmapzselex.tpl' );
		
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
		
		// echo "<pre>"; print_r($vars); echo "</pre>";
		
		$this->view->assign ( 'vars', $vars );
		$this->view->assign ( 'languages', $languages );
		
		return $this->view->fetch ( 'blocks/googlemapzselex/gmapzselex_modify.tpl' );
	}
	
	/**
	 * update block settings
	 */
	public function update($blockinfo) {
		$vars = BlockUtil::varsFromContent ( $blockinfo ['content'] );
		
		// alter the corresponding variable
		$vars ['showAdminZSELEXinBlock'] = FormUtil::getPassedValue ( 'showAdminZSELEXinBlock', '', 'POST' );
		
		$vars ['centerfunc'] = FormUtil::getPassedValue ( 'centerfunc', '', 'POST' );
		$vars ['displayinfo'] = FormUtil::getPassedValue ( 'displayinfo', '', 'POST' );
		
		$vars ['blockinfo'] = FormUtil::getPassedValue ( 'blockinfo', '', 'POST' );
		
		// write back the new contents
		$blockinfo ['content'] = BlockUtil::varsToContent ( $vars );
		
		// clear the block cache
		$this->view->clear_cache ( 'blocks/googlemapzselex/gmapzselex_modify.tpl' );
		
		return $blockinfo;
	}
}

// end class def
<?php
function smarty_function_serviceusedcount($args, &$smarty) {
	// $dom = ZLanguage::getModuleDomain('ZSELEX');
	// $name = '';
	// echo $args['key'];
	$shop_id = $args ['shop_id'];
	$service_type = $args ['type'];
	
	$em = ServiceUtil::getService ( 'doctrine.entitymanager' );
	$repo = $em->getRepository ( 'ZSELEX_Entity_Shop' );
	
	if ($service_type == 'employees') {
		$entity = 'ZSELEX_Entity_Employee';
		$field_id = 'emp_id';
		$count = $repo->getCount ( null, $entity, $field_id, array (
				"a.shop" => $shop_id 
		) );
	} elseif ($service_type == 'addproducts') {
		$entity = 'ZSELEX_Entity_Product';
		$field_id = 'product_id';
		$count = $repo->getCount ( null, $entity, $field_id, array (
				"a.shop" => $shop_id 
		) );
	} elseif ($service_type == 'eventservice') {
		$entity = 'ZSELEX_Entity_Event';
		$field_id = 'shop_event_id';
		$count = $repo->getCount ( null, $entity, $field_id, array (
				"a.shop" => $shop_id 
		) );
	} elseif ($service_type == 'minisiteimages') {
		$entity = 'ZSELEX_Entity_MinisiteImage';
		$field_id = 'file_id';
		$count = $repo->getCount ( null, $entity, $field_id, array (
				"a.shop" => $shop_id 
		) );
	} elseif ($service_type == 'createad') {
		$entity = 'ZSELEX_Entity_Advertise';
		$field_id = 'advertise_id';
		$count = $repo->getCount ( null, $entity, $field_id, array (
				"a.shop" => $shop_id 
		) );
	} elseif ($service_type == 'minisitebanner') {
		$entity = 'ZSELEX_Entity_Banner';
		$field_id = 'shop_banner_id';
		$count = $repo->getCount ( null, $entity, $field_id, array (
				"a.shop" => $shop_id 
		) );
	} elseif ($service_type == 'minisitebanner') {
		$entity = 'ZSELEX_Entity_Banner';
		$field_id = 'shop_banner_id';
		$count = $repo->getCount ( null, $entity, $field_id, array (
				"a.shop" => $shop_id 
		) );
	} elseif ($service_type == 'minisiteannouncement') {
		$entity = 'ZSELEX_Entity_Announcement';
		$field_id = 'ann_id';
		$count = $repo->getCount ( null, $entity, $field_id, array (
				"a.shop" => $shop_id 
		) );
	} elseif ($service_type == 'exclusiveevent') {
		$entity = 'ZSELEX_Entity_Event';
		$field_id = 'shop_event_id';
		$count = $repo->getCount ( null, $entity, $field_id, array (
				"a.shop" => $shop_id,
				"a.exclusive" => 1 
		) );
	} elseif ($service_type == 'pages') {
		$entity = 'ZTEXT_Entity_Page';
		$field_id = 'text_id';
		$count = $repo->getCount ( null, $entity, $field_id, array (
				"a.shop" => $shop_id 
		) );
		// echo $count; exit;
	} elseif ($service_type == 'minisite' || $service_type == 'minishop') {
		$count = 1;
	} elseif ($service_type == 'diskquota') {
		$ownerInfo = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getOwnerInfo', $args = array (
				'shop_id' => $shop_id 
		) );
		$owner_id = $ownerInfo ['user_id'];
		$ownerfoldersize = ModUtil::apiFunc ( 'ZSELEX', 'service', 'ownerFolderSize', $args = array (
				'owner_id' => $owner_id 
		) );
		$count = $ownerfoldersize;
	} else {
		$count = 1;
	}
	// echo "<pre>"; print_r($products); echo "</pre>";
	return $count;
}

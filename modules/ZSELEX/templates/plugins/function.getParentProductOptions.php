<?php
function smarty_function_getParentProductOptions($args, &$smarty) {
	$em = ServiceUtil::getService ( 'doctrine.entitymanager' );
	$repo = $em->getRepository ( 'ZSELEX_Entity_ProductOption' );
	
	// print_r($args);
	$option_id = $args ['option_id'];
	/*
	 * $parent_product_options = ModUtil::apiFunc('ZSELEX', 'user', 'selectJoinArray', $args = array(
	 * 'table' => 'zselex_product_options_values a',
	 * 'fields' => array(
	 * 'a.*,b.option_name,b.option_type'
	 * ),
	 * 'where' => array(
	 * "a.option_id=$option_id"
	 * ),
	 * 'joins' => array(
	 * "LEFT JOIN zselex_product_options b ON b.option_id=a.option_id"
	 * ),
	 * 'orderby' => 'a.sort_order ASC'
	 * ));
	 */
	
	$parent_product_options = $repo->getAll ( array (
			'entity' => 'ZSELEX_Entity_ProductOptionValue',
			'fields' => array (
					'b.option_name',
					'b.option_type',
					'a.option_value_id',
					'b.option_id',
					'a.option_value',
					'a.sort_order' 
			),
			'where' => array (
					"a.option" => $option_id 
			),
			'joins' => array (
					'LEFT JOIN a.option b' 
			),
			'orderby' => 'a.sort_order ASC' 
	) );
	
	// echo "<pre>"; print_r($parent_product_options); echo "</pre>";
	
	$smarty->assign ( "parent_product_options", $parent_product_options );
}
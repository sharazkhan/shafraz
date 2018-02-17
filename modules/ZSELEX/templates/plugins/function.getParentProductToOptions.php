<?php
function smarty_function_getParentProductToOptions($args, &$smarty) {
	
	// print_r($args);
	$option_id = $args ['parent_option_id'];
	$product_id = $args ['product_id'];
	
	/*
	 * $parent_product_options = ModUtil::apiFunc('ZSELEX', 'user', 'selectJoinArray', $args = array(
	 * 'table' => 'zselex_product_to_options_values a',
	 * 'fields' => array(
	 * 'a.product_id,a.price,a.option_id,a.product_to_options_id,a.option_value_id,a.parent_option_value_id,a.qty,b.option_name,b.option_type,c.option_value'
	 * ),
	 * 'where' => array(
	 * "a.parent_option_id=$option_id AND a.product_id=$product_id AND a.qty > 0"
	 * ),
	 * 'joins' => array(
	 * "INNER JOIN zselex_product_options b ON b.option_id=a.parent_option_id",
	 * "INNER JOIN zselex_product_options_values c ON c.option_value_id=a.parent_option_value_id"
	 * ),
	 * 'groupby' => 'parent_option_value_id'
	 * ));
	 */
	
	$em = ServiceUtil::getService ( 'doctrine.entitymanager' );
	$where = " a.parent_option_id=$option_id AND a.product_id=$product_id AND a.qty > 0 ";
	$parent_product_options = $em->getRepository ( 'ZSELEX_Entity_ProductToOption' )->getParentOptionValues ( array (
			'where' => $where 
	) );
	// echo "<pre>"; print_r($parent_product_options); echo "</pre>";
	// echo "<pre>"; print_r($parent_product_options1); echo "</pre>";
	
	$smarty->assign ( "parent_product_options", $parent_product_options );
}
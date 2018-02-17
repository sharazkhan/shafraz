<?php
function smarty_function_sociallinkshopurl($args, &$smarty) {
	
	// echo "<pre>"; print_r($args); echo "</pre>";
	$shop_id = $args ['shop_id'];
	$soc_link_id = $args ['soc_link_id'];
	$em = ServiceUtil::getService ( 'doctrine.entitymanager' );
	$repo = $em->getRepository ( 'ZSELEX_Entity_Shop' );
	$soc_args = array (
			'entity' => 'ZSELEX_Entity_SocialLinkShop',
			'fields' => array (
					'a.link_url' 
			),
			'where' => array (
					'a.shop' => $shop_id,
					'a.socl_links' => $soc_link_id 
			),
			'exit' => false 
	);
	$get = $repo->get ( $soc_args );
	// echo "<pre>"; print_r($get); echo "</pre>";
	echo $get ['link_url'];
	// $smarty->assign("url", $url);
}
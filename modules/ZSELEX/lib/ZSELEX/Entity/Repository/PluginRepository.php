<?php

/**
 * Tag - a content-tagging module for the Zikukla Application Framework
 * 
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Repository class for DQL calls
 */
class ZSELEX_Entity_Repository_PluginRepository extends ZSELEX_Entity_Repository_General {
	public function shopDependedCount($args) {
		$type = $args ['type'];
		$shoptype = $args ['shoptype'];
		$dql = "SELECT COUNT(a.plugin_id) FROM ZSELEX_Entity_Plugin a 
                WHERE a.type=:type AND (a.depended_shoptypes LIKE :shoptype OR a.depended_shoptypes LIKE '')";
		$query = $this->_em->createQuery ( $dql );
		// $setParams['type'] = $type;
		// $setParams['shoptype'] = "'%" . DataUtil::formatForStore($shoptype) . "%'";
		$query->setParameters ( array (
				'type' => $type,
				'shoptype' => '%' . DataUtil::formatForStore ( $shoptype ) . '%' 
		) );
		$count = $query->getSingleScalarResult ();
		return $count;
	}
}
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
class ZSELEX_Entity_Repository_MiniShopRepository extends ZSELEX_Entity_Repository_General {
	public function getminiShopConfigured($args) {
		$shop_id = $args ['shop_id'];
		try {
			
			$dql = "SELECT a.configured 
                FROM ZSELEX_Entity_MiniShop a
                WHERE a.shop=:shop_id";
			$query = $this->_em->createQuery ( $dql );
			$query->useResultCache ( true );
			$query->setParameter ( 'shop_id', $shop_id );
			$result = $query->getOneOrNullResult ();
			return $result;
		} catch ( \Exception $e ) {
			// echo 'Message: ' . $e->getMessage();
			// exit;
		}
	}
}
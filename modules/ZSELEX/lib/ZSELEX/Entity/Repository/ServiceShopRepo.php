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
class ZSELEX_Entity_Repository_ServiceShopRepo extends ZSELEX_Entity_Repository_General {
	public function serviceExistBlock($args) {
		// return;
		$shop_id = $args ['shop_id'];
		$servicetype = $args ['type'];
		$today = date ( "Y-m-d" );
		$dql = "SELECT date_diff('$today' , a.timer_date) as days , a.id , a.timer_days, a.service_status , a.qty_based , a.quantity , a.availed ,
                b.status as active_status , b.service_depended , b.shop_depended , b.depended_services , c.is_free
                FROM  ZSELEX_Entity_ServiceShop a
                LEFT JOIN a.plugin b
                JOIN a.bundle c
                WHERE a.type=:type AND a.shop=:shop_id";
		// echo $dql;
		
		$query = $this->_em->createQuery ( $dql );
		$query->setParameter ( 'shop_id', $shop_id );
		$query->setParameter ( 'type', $servicetype );
		$result = $query->getOneOrNullResult ();
		// echo "<pre>"; print_r($result); echo "</pre>";
		
		return $result; // hydrate result to array
	}
	public function getService($args) {
		$shop_id = $args ['shop_id'];
		$servicetype = $args ['type'];
		$today = date ( "Y-m-d" );
		$dql = "SELECT a.id , date_diff('$today' , a.timer_date) as days ,  a.service_status , a.qty_based , a.quantity , a.availed , a.timer_date , a.timer_days
                  FROM  ZSELEX_Entity_ServiceShop a
                  WHERE a.type=:type AND a.shop=:shop_id";
		// echo $dql;
		
		$query = $this->_em->createQuery ( $dql );
		$query->setParameter ( 'shop_id', $shop_id );
		$query->setParameter ( 'type', $servicetype );
		$result = $query->getOneOrNullResult ();
		// echo "<pre>"; print_r($result); echo "</pre>";
		
		return $result; // hydrate result to array
	}
	public function getCount($args) {
		$type = $args ['type'];
		$shop_id = $args ['shop_id'];
		$dql = "SELECT COUNT(a.id) FROM ZSELEX_Entity_ServiceShop a 
                WHERE a.type=:type AND a.shop=:shop_id";
		$query = $this->_em->createQuery ( $dql );
		
		$query->setParameters ( array (
				'type' => $type,
				'shop_id' => $shop_id 
		) );
		$count = $query->getSingleScalarResult ();
		return $count;
	}
	public function getServiceQuantity($args) {
		try {
			
			$shop_id = $args ['shop_id'];
			$type = $args ['type'];
			$dql = "SELECT a.quantity 
                FROM ZSELEX_Entity_ServiceShop a
                WHERE a.shop=:shop_id AND a.type=:type";
			$query = $this->_em->createQuery ( $dql );
			// $query->useResultCache(true);
			$query->setParameter ( 'shop_id', $shop_id );
			$query->setParameter ( 'type', $type );
			$result = $query->getOneOrNullResult ( 2 );
			return $result;
		} catch ( \Exception $e ) {
			// echo 'Message: ' . $e->getMessage();
			// exit;
		}
	}
	function mainBundleExist($args) {
		$shop_id = $args ['shop_id'];
		$dql = "SELECT COUNT(a.id) 
            FROM ZSELEX_Entity_ServiceShop a
            LEFT JOIN a.bundle b 
            WHERE b.bundle_type='main' AND a.shop=$shop_id";
		$query = $this->_em->createQuery ( $dql );
		$count = $query->getSingleScalarResult ();
		
		return $count;
	}
}
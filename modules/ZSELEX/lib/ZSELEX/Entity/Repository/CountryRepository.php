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
use Doctrine\ORM\QueryBuilder;
use DoctrineExtensions\Paginate\Paginate;
use Doctrine\ORM;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Repository class for DQL calls
 */
class ZSELEX_Entity_Repository_CountryRepository extends ZSELEX_Entity_Repository_General {
	
	/**
	 * get filtered collection of events for admin view
	 * This collection is not filtered by Permissions
	 *
	 * @param integer $eventStatus        	
	 * @param string $sortDir        	
	 * @param integer $offset        	
	 * @param integer $maxResults        	
	 * @param array $categoryFilter        	
	 *
	 * @return Object Collection
	 */
	public function getCountryList($args) {
		// echo "comes here api!!!"; exit;
		$dql = "SELECT a FROM ZSELEX_Entity_Country a ";
		$where = array ();
		$where [] = "a.country_id != '' ";
		if ($eventStatus != CalendarEvent::ALLSTATUS) {
			$where [] = "a.eventstatus = :status ";
		}
		if (isset ( $categoryFilter ) && ! empty ( $categoryFilter )) {
			// reformat array
			$categories = array_values ( $categoryFilter );
			// add to dql
			$where [] = "c.category IN (:categories) ";
		}
		if (! empty ( $where )) {
			$dql .= "WHERE " . implode ( ' AND ', $where );
		}
		$dql .= "ORDER BY $sortDir ";
		// generate query
		$query = $this->_em->createQuery ( $dql );
		if ($eventStatus != CalendarEvent::ALLSTATUS) {
			$query->setParameter ( 'status', $eventStatus );
		}
		if (isset ( $categories )) {
			$query->setParameter ( 'categories', $categories );
		}
		if ($offset > 0) {
			$query->setFirstResult ( $offset );
		}
		$query->setMaxResults ( $maxResults );
		try {
			$result = $query->getResult ();
		} catch ( Exception $e ) {
			echo "<pre>";
			var_dump ( $e->getMessage () );
			var_dump ( $query->getDQL () );
			var_dump ( $query->getParameters () );
			var_dump ( $query->getSQL () );
			die ();
		}
		return $result;
	}
	public function getCountry() {
		$dql = "SELECT a FROM ZSELEX_Entity_Country a ";
		$query = $this->_em->createQuery ( $dql );
		$result = $query->getResult ();
		
		return $result;
	}
	public function getAppCountry($args) {
		try {
			$country = $args ['country'];
			
			$rsm = new ORM\Query\ResultSetMapping ();
			$rsm->addEntityResult ( 'ZSELEX_Entity_Country', 'a' );
			
			$rsm->addFieldResult ( 'a', 'country_id', 'country_id' );
			$rsm->addFieldResult ( 'a', 'country_name', 'country_name' );
			
			$dql = "SELECT a.country_id , a.country_name
                FROM zselex_country a
                WHERE MATCH(a.country_name) AGAINST('" . DataUtil::formatForStore ( $country ) . "')";
			$query = $this->_em->createNativeQuery ( $dql, $rsm );
			$result = $query->getSingleResult ( 2 );
			
			// echo "<pre>"; print_r($result); echo "</pre>";
		} catch ( \Exception $e ) {
			// echo 'Message: ' . $e->getMessage();
			// exit;
		}
		
		return $result;
	}
	public function getAppRegion($args) {
		
		// echo "<pre>"; print_r($args); echo "</pre>"; exit;
		try {
			$regionQry = $args ['regionQry'];
			$rsm = new ORM\Query\ResultSetMapping ();
			$rsm->addEntityResult ( 'ZSELEX_Entity_Region', 'a' );
			$rsm->addFieldResult ( 'a', 'region_id', 'region_id' );
			$rsm->addFieldResult ( 'a', 'region_name', 'region_name' );
			$dql = "SELECT a.region_id , a.region_name
                FROM zselex_region a
                WHERE $regionQry";
			// echo $dql; exit;
			$query = $this->_em->createNativeQuery ( $dql, $rsm );
			$result = $query->getSingleResult ( 2 );
			// echo "<pre>"; print_r($result); echo "</pre>";
		} catch ( \Exception $e ) {
			// echo 'Message: ' . $e->getMessage();
			// exit;
		}
		
		return $result;
	}
	public function getAppCity($args) {
		try {
			$cityQry = $args ['cityQry'];
			$rsm = new ORM\Query\ResultSetMapping ();
			$rsm->addEntityResult ( 'ZSELEX_Entity_City', 'a' );
			$rsm->addFieldResult ( 'a', 'city_id', 'city_id' );
			$rsm->addFieldResult ( 'a', 'city_name', 'city_name' );
			$rsm->addMetaResult ( 'a', 'region_id', 'region_id' );
			$rsm->addMetaResult ( 'a', 'region_name', 'region_name' );
			$rsm->addMetaResult ( 'a', 'country_id', 'country_id' );
			$rsm->addMetaResult ( 'a', 'country_name', 'country_name' );
			$dql = "SELECT a.city_id , a.city_name , a.region_id , b.region_name , c.country_id , c.country_name
                FROM zselex_city a
                LEFT JOIN zselex_region b on b.region_id=a.region_id
                 LEFT JOIN zselex_country c on c.country_id=a.country_id
                WHERE $cityQry";
			// echo $dql; exit;
			$query = $this->_em->createNativeQuery ( $dql, $rsm );
			$result = $query->getSingleResult ( 2 );
			// echo "<pre>"; print_r($result); echo "</pre>";
		} catch ( \Exception $e ) {
			// echo 'Message: ' . $e->getMessage();
			// exit;
		}
		
		return $result;
	}
	public function getAppArea($args) {
		try {
			$areaQry = $args ['areaQry'];
			$rsm = new ORM\Query\ResultSetMapping ();
			$rsm->addEntityResult ( 'ZSELEX_Entity_Area', 'a' );
			$rsm->addFieldResult ( 'a', 'area_id', 'area_id' );
			$rsm->addFieldResult ( 'a', 'area_name', 'area_name' );
			$rsm->addMetaResult ( 'a', 'region_id', 'region_id' );
			$rsm->addMetaResult ( 'a', 'region_name', 'region_name' );
			$rsm->addMetaResult ( 'a', 'city_id', 'city_id' );
			$rsm->addMetaResult ( 'a', 'city_name', 'city_name' );
			$rsm->addMetaResult ( 'a', 'country_id', 'country_id' );
			$rsm->addMetaResult ( 'a', 'country_name', 'country_name' );
			$dql = "SELECT a.area_id , a.area_name , 
                b.region_id , b.region_name , c.city_id , c.city_name ,  d.country_id , d.country_name
                FROM zselex_area a
                LEFT JOIN zselex_region b ON b.region_id=a.region_id
                LEFT JOIN zselex_city c ON c.city_id=a.city_id
                LEFT JOIN zselex_country d ON d.country_id=a.country_id
                WHERE $areaQry";
			$query = $this->_em->createNativeQuery ( $dql, $rsm );
			$result = $query->getSingleResult ( 2 );
			// echo "<pre>"; print_r($result); echo "</pre>";
		} catch ( \Exception $e ) {
			// echo 'Message: ' . $e->getMessage();
			// exit;
			$result = array ();
		}
		
		return $result;
	}
	public function getAppShopAddress($args) {
		try {
			$addressQry = $args ['addressQry'];
			$rsm = new ORM\Query\ResultSetMapping ();
			$rsm->addEntityResult ( 'ZSELEX_Entity_Shop', 'a' );
			$rsm->addFieldResult ( 'a', 'shop_id', 'shop_id' );
			$rsm->addMetaResult ( 'a', 'country_id', 'country_id' );
			$rsm->addMetaResult ( 'a', 'region_id', 'region_id' );
			$rsm->addMetaResult ( 'a', 'city_id', 'city_id' );
			$rsm->addMetaResult ( 'a', 'area_id', 'area_id' );
			$dql = "SELECT a.shop_id , a.country_id , a.region_id , a.city_id , a.area_id
                FROM zselex_shop a
                WHERE $addressQry";
			// echo $dql . '<br>';
			$query = $this->_em->createNativeQuery ( $dql, $rsm );
			// $result = $query->getSingleResult(2);
			$result = $query->getArrayResult ( 2 );
			// echo "<pre>"; print_r($result); echo "</pre>"; exit;
		} catch ( \Exception $e ) {
			// echo 'Message: ' . $e->getMessage(); exit;
			//
			$result = array ();
		}
		
		return $result;
	}
}
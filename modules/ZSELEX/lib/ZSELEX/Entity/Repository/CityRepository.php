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
class ZSELEX_Entity_Repository_CityRepository extends ZSELEX_Entity_Repository_General {
	public function getCities($args) {
		$dql = "SELECT a.shop_id
                FROM ZSELEX_Entity_Shop a
                WHERE a.shop_id IS NOT NULL AND a.status=1";
		$query = $this->_em->createQuery ( $dql );
		$query->setFirstResult ( 0 );
		$query->setMaxResults ( $limit );
		$result = $query->getArrayResult ();
		return $result;
	}
	public function getCityTest1() { // working
		$qb = $this->_em->createQueryBuilder ();
		$users = 61;
		$qb->select ( 'a.city_name', 'u.country_name' )->from ( 'ZSELEX_Entity_City', 'a' )->leftJoin ( 'a.country', 'u' );
		// ->where('u = :user')
		// ->setParameter('user', $users);
		
		return $qb->getQuery ()->getResult ();
		
		// leftJoin($join, $alias, $conditionType = null, $condition = null, $indexBy = null)
	}
}
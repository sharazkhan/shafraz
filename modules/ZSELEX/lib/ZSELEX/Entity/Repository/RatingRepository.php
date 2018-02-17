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
class ZSELEX_Entity_Repository_RatingRepository extends ZSELEX_Entity_Repository_General {
	public function getRatings($args) {
		$shop_id = $args ['shop_id'];
		
		$dql = "SELECT a.rating 
                 FROM ZSELEX_Entity_Rating a
                 WHERE a.shop=:shop_id";
		$query = $this->_em->createQuery ( $dql );
		$query->setParameter ( 'shop_id', $shop_id );
		$result = $query->getResult ( 2 );
		// echo "<pre>"; print_r($result); echo "</pre>"; exit;
		return $result;
	}
	public function getUserRating($args) {
		$shop_id = $args ['shop_id'];
		$user_id = $args ['user_id'];
		
		$dql = "SELECT a.rating 
                 FROM ZSELEX_Entity_Rating a
                 WHERE a.shop=:shop_id AND a.user_id=:user_id";
		$query = $this->_em->createQuery ( $dql );
		$query->setParameter ( 'shop_id', $shop_id );
		$query->setParameter ( 'user_id', $user_id );
		$result = $query->getOneOrNullResult ( 2 );
		// echo "<pre>"; print_r($result); echo "</pre>"; exit;
		return $result;
	}
	public function createRating($args) {
		// echo "<pre>"; print_r($args); echo "</pre>"; exit;
		try {
			$item = ZSELEX_Util::purifyHtml ( $args );
			// echo "<pre>"; print_r($item); echo "</pre>"; exit;
			$shop = $this->_em->find ( 'ZSELEX_Entity_Shop', $item ['shop_id'] );
			// echo $shop; exit;
			$rating = new ZSELEX_Entity_Rating ();
			$rating->setShop ( $shop );
			$rating->setRating ( $item ['rating'] );
			$rating->setUser_id ( $item ['user_id'] );
			$rating->setDateposted ( $item ['dateposted'] );
			$rating->setTimestamp ( $item ['timestamp'] );
			
			$this->_em->persist ( $rating );
			$this->_em->flush ();
			
			$InsertId = $rating->getRating_id ();
			$result = $InsertId;
			return $result;
		} catch ( \Exception $e ) {
			echo 'Message: ' . $e->getMessage () . '<br>';
			// echo $query->getSQL();
			exit ();
		}
	}
}
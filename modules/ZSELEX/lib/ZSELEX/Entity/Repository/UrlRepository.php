<?php
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Repository class for DQL calls
 */
class ZSELEX_Entity_Repository_UrlRepository extends ZSELEX_Entity_Repository_General {
	/*
	 * Update shop url
	 *
	 * @param int $args['shop_id']
	 * @param string $args['title']
	 * @return boolean
	 */
	public function updateShopUrl($args) {
		// return array();
		$shop_id = $args ['shop_id'];
		$title = $args ['title'];
		$count = $this->getCount ( null, 'ZSELEX_Entity_Url', 'url_id', array (
				'a.type' => 'shop',
				'a.url' => $title 
		) );
		if (! $count) {
			$url = new ZSELEX_Entity_Url ();
			$url->setType ( 'shop' );
			$url->setType_id ( $shop_id );
			$url->setUrl ( $title );
			$this->_em->persist ( $url );
			$this->_em->flush ();
		}
		return true;
	}
	
	/*
	 * Update product url
	 *
	 * @param int $args['product_id']
	 * @param string $args['product_title']
	 * @param int $args['shop_id']
	 * @return boolean
	 */
	public function updateProductUrl($args) {
		// return array();
		$product_id = $args ['product_id'];
		$title = $args ['product_title'];
		$shop_id = $args ['shop_id'];
		$count = $this->getCount ( null, 'ZSELEX_Entity_Url', 'url_id', array (
				'a.type' => 'product',
				'a.url' => $title 
		) );
		if (! $count) {
			$url = new ZSELEX_Entity_Url ();
			$url->setType ( 'product' );
			$url->setType_id ( $product_id );
			$url->setUrl ( $title );
			$url->setShop_id ( $shop_id );
			$this->_em->persist ( $url );
			$this->_em->flush ();
		}
		return true;
	}
	
	/*
	 * Update event url
	 *
	 * @param int $args['event_id']
	 * @param string $args['event_title']
	 * @param int $args['shop_id']
	 * @return boolean
	 */
	public function updateEventUrl($args) {
		$event_id = $args ['event_id'];
		$title = $args ['event_title'];
		$shop_id = $args ['shop_id'];
		$count = $this->getCount ( null, 'ZSELEX_Entity_Url', 'url_id', array (
				'a.type' => 'event',
				'a.url' => $title 
		) );
		if (! $count) {
			$url = new ZSELEX_Entity_Url ();
			$url->setType ( 'event' );
			$url->setType_id ( $event_id );
			$url->setUrl ( $title );
			$url->setShop_id ( $shop_id );
			$this->_em->persist ( $url );
			$this->_em->flush ();
		}
		return true;
	}
}

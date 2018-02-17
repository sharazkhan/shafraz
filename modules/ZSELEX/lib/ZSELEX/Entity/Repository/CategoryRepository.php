<?php

/*
 * use Doctrine\ORM\EntityRepository;
 * use Doctrine\ORM\Query;
 * use Doctrine\ORM;
 * use Doctrine\ORM\Query\ResultSetMapping;
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
class ZSELEX_Entity_Repository_CategoryRepository extends ZSELEX_Entity_Repository_General {
	function updateCategory($args) {
		$qb = $this->_em->createQueryBuilder ();
		$q = $qb->update ( 'ZSELEX_Entity_Category', 'u' )->set ( 'u.category_name', '?1' )->set ( 'u.description', '?2' )->set ( 'u.status', '?3' )->where ( 'u.category_id = ?4' )->setParameter ( 1, $args ['elemtName'] )->setParameter ( 2, $args ['elemtDesc'] )->setParameter ( 3, $args ['status'] )->setParameter ( 4, $args ['elemId'] )->getQuery ();
		$p = $q->execute ();
		return $p;
	}
	public function getCategories($args) {
		$dql = "SELECT a.category_id , a.category_name
                FROM ZSELEX_Entity_Category a
                WHERE a.category_id IS NOT NULL ORDER BY a.category_name ASC";
		// echo $dql;
		$query = $this->_em->createQuery ( $dql );
		
		$result = $query->getArrayResult ();
		return $result;
	}
	function migrateShopBranches() {
		$shop_args = array (
				'entity' => 'ZSELEX_Entity_Shop',
				'fields' => array (
						'a.shop_id',
						'b.branch_id' 
				),
				'joins' => array (
						'JOIN a.branch b' 
				) 
		);
		$all_shops = $this->getAll ( $shop_args );
		// echo "<pre>"; print_r($all_shops); echo "</pre>"; exit;
		$shop_obj = $this->_em->getRepository ( 'ZSELEX_Entity_Shop' );
		foreach ( $all_shops as $key => $val ) {
			$branches = array ();
			$branches [] = $val ['branch_id'];
			
			$add_branches = $shop_obj->addShopBranches ( array (
					'branches' => $branches,
					'shop_id' => $val ['shop_id'] 
			) );
		}
		
		return true;
	}
}

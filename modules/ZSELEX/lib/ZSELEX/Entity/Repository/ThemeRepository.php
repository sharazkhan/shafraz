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
class ZSELEX_Entity_Repository_ThemeRepository extends ZSELEX_Entity_Repository_General {
	public function getZselexThemes($args) { // gallery images listing
		try {
			$rsm = new ORM\Query\ResultSetMapping ();
			$rsm->addEntityResult ( 'ZSELEX_Entity_ZselexTheme', 'a' );
			
			$rsm->addFieldResult ( 'a', 'zt_id', 'zt_id' );
			$rsm->addFieldResult ( 'a', 'theme_name', 'theme_name' );
			$rsm->addMetaResult ( 'a', 'id', 'id' );
			$rsm->addMetaResult ( 'a', 'name', 'name' );
			$rsm->addMetaResult ( 'a', 'displayname', 'displayname' );
			$rsm->addMetaResult ( 'a', 'description', 'description' );
			
			$startlimit = $args ['start'] < 1 ? 0 : $args ['start'];
			if ($startlimit > 0) {
				$startlimit = $startlimit - 1;
			}
			$offset = $args ['itemsperpage'];
			$sql = $args ['sql'];
			
			$dql = "SELECT c.id , a.zt_id , a.theme_name , c.name , c.displayname , c.description
                FROM zselex_themes a
                LEFT JOIN  zselex_shop_owners_theme b ON b.theme_id=a.theme_id
                LEFT JOIN  themes c ON c.id=a.theme_id 
                WHERE a.zt_id IS NOT NULL " . $sql . " " . " LIMIT $startlimit , $offset";
			// echo $dql;
			$query = $this->_em->createNativeQuery ( $dql, $rsm );
			$result = $query->getResult ( 2 );
			
			$rsm2 = new ORM\Query\ResultSetMapping ();
			$rsm2->addEntityResult ( 'ZSELEX_Entity_ZselexTheme', 'a' );
			// $rsm2->addMetaResult('a', 'COUNT(a.zt_id)', 'tot');
			$rsm2->addScalarResult ( 'COUNT(a.zt_id)', 'total' );
			$dql2 = "SELECT COUNT(a.zt_id)
                FROM zselex_themes a
                LEFT JOIN  zselex_shop_owners_theme b ON b.theme_id=a.theme_id
                LEFT JOIN  themes c ON c.id=a.theme_id 
                WHERE a.zt_id IS NOT NULL " . $sql;
			// echo $dql2;
			$query2 = $this->_em->createNativeQuery ( $dql2, $rsm2 );
			$result2 = $query2->getOneOrNullResult ( 2 );
			
			// echo "<pre>"; print_r($result2); echo "</pre>";
			return array (
					'result' => $result,
					'count' => $result2 ['total'] 
			);
		} catch ( \Exception $e ) {
			echo 'Message: ' . $e->getMessage ();
			exit ();
		}
	}
	public function getThemesToConfigureToZselex($args) {
		$rsm = new ORM\Query\ResultSetMapping ();
		$rsm->addEntityResult ( 'ZSELEX_Entity_ShopOwnerTheme', 'a' );
		
		$rsm->addFieldResult ( 'a', 'theme_name', 'theme_name' );
		$rsm->addMetaResult ( 'a', 'id', 'id' );
		$rsm->addMetaResult ( 'a', 'name', 'name' );
		$rsm->addMetaResult ( 'a', 'displayname', 'displayname' );
		$rsm->addMetaResult ( 'a', 'description', 'description' );
		
		$extra = $args ['sql'];
		
		/*
		 * $dql = "SELECT t.id , a.theme_name , t.name , t.displayname , t.description
		 * FROM themes t
		 * LEFT JOIN zselex_shop_owners_theme a ON a.theme_id=t.id
		 * WHERE t.id NOT IN(SELECT theme_id FROM zselex_themes)" . $extra;
		 */
		
		$dql = "SELECT t.id  , a.theme_name , t.name , t.displayname , t.description
          FROM zselex_shop_owners_theme a
          RIGHT JOIN themes t ON a.theme_id=t.id
          WHERE t.id NOT IN(SELECT theme_id FROM zselex_themes)" . $extra;
		echo $dql;
		
		$query = $this->_em->createNativeQuery ( $dql, $rsm );
		$result = $query->getResult ( 2 );
		
		// echo "<pre>"; print_r($result); echo "</pre>";
		return $result;
	}
}
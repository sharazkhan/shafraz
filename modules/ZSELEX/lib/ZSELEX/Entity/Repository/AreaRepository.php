<?php
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Repository class for DQL calls
 */
class ZSELEX_Entity_Repository_AreaRepository extends ZSELEX_Entity_Repository_General {
	public function getAreas($args) {
		$sql = $args ['sql'];
		// echo $sql;
		$dql = "SELECT a.area_id , a.area_name
                FROM ZSELEX_Entity_Area a
                WHERE a.area_id IS NOT NULL " . $sql . " ORDER BY a.area_name ASC";
		// echo $dql;
		$query = $this->_em->createQuery ( $dql );
		
		$result = $query->getArrayResult ();
		return $result;
	}
}
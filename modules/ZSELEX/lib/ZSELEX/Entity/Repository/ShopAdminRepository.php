<?php
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Repository class for DQL calls
 */
class ZSELEX_Entity_Repository_ShopAdminRepository extends ZSELEX_Entity_Repository_General {
	public function getPermission($args) {
		$shop_id = $args ['shop_id'];
		$user_id = $args ['user_id'];
		
		$dql = "SELECT COUNT(a.admin_id) FROM ZSELEX_Entity_ShopAdmin a
          INNER JOIN a.shop b
          WHERE a.shop=b.shop_id AND a.shop=$shop_id AND b.shop_id=$shop_id AND a.user_id=$user_id";
		
		$query = $this->_em->createQuery ( $dql );
		// $query->setParameter('shop_id', $shop_id);
		$count = $query->getSingleScalarResult ();
		return $count;
	}
	public function getAdmins($args) {
		// return;
		// echo "<pre>"; print_r($args); echo "</pre>"; exit;
		try {
			$shop_id = $args ['shop_id'];
			
			$rsm = new ORM\Query\ResultSetMapping ();
			
			$rsm->addEntityResult ( 'ZSELEX_Entity_ShopAdmin', 'u' );
			$rsm->addFieldResult ( 'u', 'user_id', 'user_id' );
			$rsm->addMetaResult ( 'u', 'uname', 'uname' );
			
			$dql = "SELECT  u.user_id ,  a.uname 
                FROM zselex_shop_admins u
                INNER JOIN zselex_shop b ON b.shop_id=u.shop_id
                INNER JOIN users a ON a.uid=u.user_id
                WHERE u.shop_id=b.shop_id AND b.shop_id=$shop_id";
			$query = $this->_em->createNativeQuery ( $dql, $rsm );
			$result = $query->getArrayResult ();
			// echo "<pre>"; print_r($result); echo "</pre>";
		} catch ( \Exception $e ) {
			// echo 'Message: ' . $e->getMessage();
			// exit;
		}
		
		return $result;
	}
	public function getShopAdmins($args) {
		$shop_id = $args ['shop_id'];
		
		try {
			// echo "<pre>"; print_r($setParams); echo "</pre>"; exit;
			$rsm = new ORM\Query\ResultSetMapping ();
			$rsm->addEntityResult ( 'ZSELEX_Entity_ShopAdmin', 'u' );
			$rsm->addFieldResult ( 'u', 'user_id', 'user_id' );
			$rsm->addMetaResult ( 'u', 'uname', 'uname' );
			$rsm->addMetaResult ( 'u', 'user_regdate', 'user_regdate' );
			$rsm->addMetaResult ( 'u', 'lastlogin', 'lastlogin' );
			$rsm->addMetaResult ( 'u', 'passreminder', 'passreminder' );
			$rsm->addMetaResult ( 'u', 'pass', 'pass' );
			$rsm->addMetaResult ( 'u', 'approved_date', 'approved_date' );
			$rsm->addMetaResult ( 'u', 'approved_by', 'approved_by' );
			$rsm->addMetaResult ( 'u', 'theme', 'theme' );
			$rsm->addMetaResult ( 'u', 'ublockon', 'ublockon' );
			$rsm->addMetaResult ( 'u', 'ublock', 'ublock' );
			$rsm->addMetaResult ( 'u', 'tz', 'tz' );
			$rsm->addMetaResult ( 'u', 'locale', 'locale' );
			$rsm->addMetaResult ( 'u', 'uid', 'uid' );
			$rsm->addMetaResult ( 'u', 'activated', 'activated' );
			
			$dql = "SELECT u.user_id  , a.uid  , a.uname , a.user_regdate , a.lastlogin , a.passreminder ,
                a.pass , a.approved_date , a.approved_by , a.theme , a.ublockon , a.ublock , a.tz , a.locale , a.activated
                FROM zselex_shop_admins u
                INNER JOIN users a ON a.uid=u.user_id
                WHERE u.shop_id = $shop_id
                GROUP BY a.uid";
			// echo $dql;
			$query = $this->_em->createNativeQuery ( $dql, $rsm );
			$result = $query->getArrayResult ();
			// echo "<pre>"; print_r($result); echo "</pre>";
			
			return $result;
			// echo "<pre>"; print_r($result); echo "</pre>";
		} catch ( \Exception $e ) {
			// echo 'Message: ' . $e->getMessage();
			// exit;
		}
	}
	public function getExistingAllAdmins($args) {
		$adminGroup = $args ['adminGroup'];
		$append = $args ['append'];
		$start = $args ['startnum'];
		$limit = $args ['itemsperpage'];
		
		try {
			$statement = Doctrine_Manager::getInstance ()->connection ();
			
			$dql = "SELECT a.gid  , b.uid , b.uname 
                FROM group_membership a
                INNER JOIN users b ON a.uid=b.uid
                WHERE a.gid = $adminGroup  
                    " . $append . "
                GROUP BY b.uid
                LIMIT $start , $limit";
			// echo $dql;
			$query = $statement->execute ( $dql );
			$result = $query->fetchAll ();
			return $result;
			// echo "<pre>"; print_r($result); echo "</pre>";
		} catch ( \Exception $e ) {
			// echo 'Message: ' . $e->getMessage();
			// exit;
		}
	}
	public function getExistingAllAdminsCount($args) {
		$adminGroup = $args ['adminGroup'];
		$append = $args ['append'];
		
		try {
			$statement = Doctrine_Manager::getInstance ()->connection ();
			
			$dql = "SELECT a.gid  , b.uid , b.uname 
                FROM group_membership a
                INNER JOIN users b ON a.uid=b.uid
                WHERE a.gid = $adminGroup  
                    " . $append . "
                GROUP BY b.uid
                ";
			
			// echo $dql;
			$query = $statement->execute ( $dql );
			$result = $query->rowCount ();
			return $result;
			// echo "<pre>"; print_r($result); echo "</pre>";
		} catch ( \Exception $e ) {
			// echo 'Message: ' . $e->getMessage();
			// exit;
		}
	}
	public function getExistingAdmins($args) {
		$loguser = UserUtil::getVar ( 'uid' );
		$start = $args ['startnum'];
		$limit = $args ['itemsperpage'];
		$searchtext = $args ['searchtext'];
		
		try {
			// echo "<pre>"; print_r($setParams); echo "</pre>"; exit;
			$rsm = new ORM\Query\ResultSetMapping ();
			$rsm->addEntityResult ( 'ZSELEX_Entity_ShopAdmin', 'a' );
			$rsm->addFieldResult ( 'a', 'user_id', 'user_id' );
			$rsm->addMetaResult ( 'a', 'uname', 'uname' );
			$rsm->addMetaResult ( 'a', 'user_regdate', 'user_regdate' );
			$rsm->addMetaResult ( 'a', 'lastlogin', 'lastlogin' );
			$rsm->addMetaResult ( 'a', 'passreminder', 'passreminder' );
			$rsm->addMetaResult ( 'a', 'pass', 'pass' );
			$rsm->addMetaResult ( 'a', 'approved_date', 'approved_date' );
			$rsm->addMetaResult ( 'a', 'approved_by', 'approved_by' );
			$rsm->addMetaResult ( 'a', 'theme', 'theme' );
			$rsm->addMetaResult ( 'a', 'ublockon', 'ublockon' );
			$rsm->addMetaResult ( 'a', 'ublock', 'ublock' );
			$rsm->addMetaResult ( 'a', 'tz', 'tz' );
			$rsm->addMetaResult ( 'a', 'locale', 'locale' );
			$rsm->addMetaResult ( 'a', 'uid', 'uid' );
			$rsm->addMetaResult ( 'a', 'activated', 'activated' );
			
			$append = '';
			if (! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_ADMIN ) && SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_ADD )) {
				$append .= " AND a.owner_id=$loguser ";
			}
			
			if (! empty ( $searchtext ) || $searchtext != '') {
				$append .= " AND b.uname LIKE '%" . DataUtil::formatForStore ( $searchtext ) . "%' ";
			}
			
			$dql = "SELECT a.user_id  , b.uid  , b.uname , b.user_regdate , b.lastlogin , b.passreminder ,
                b.pass , b.approved_date , b.approved_by , b.theme , b.ublockon , b.ublock , b.tz , b.locale , b.activated
                FROM zselex_shop_admins a
                INNER JOIN users b ON b.uid=a.user_id
                " . $append . "
                GROUP BY b.uid LIMIT $start , $limit";
			// echo $dql . '<br>'; exit;
			$query = $this->_em->createNativeQuery ( $dql, $rsm );
			$result = $query->getArrayResult ();
			// echo "<pre>"; print_r($result); echo "</pre>";
			
			return $result;
			// echo "<pre>"; print_r($result); echo "</pre>";
		} catch ( \Exception $e ) {
			// echo 'Message: ' . $e->getMessage();
			// exit;
		}
	}
	public function getExistingAdminsCount($args) {
		$loguser = UserUtil::getVar ( 'uid' );
		$start = $args ['startnum'];
		$limit = $args ['itemsperpage'];
		$searchtext = $args ['searchtext'];
		
		try {
			// echo "<pre>"; print_r($setParams); echo "</pre>"; exit;
			$rsm = new ORM\Query\ResultSetMapping ();
			$rsm->addEntityResult ( 'ZSELEX_Entity_ShopAdmin', 'a' );
			$rsm->addScalarResult ( 'COUNT(a.admin_id)', 'count' );
			
			$append = '';
			if (! SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_ADMIN ) && SecurityUtil::checkPermission ( 'ZSELEX::', '::', ACCESS_ADD )) {
				$append .= " AND a.owner_id=$loguser ";
			}
			
			if (! empty ( $searchtext ) || $searchtext != '') {
				$append .= " AND b.uname LIKE '%" . DataUtil::formatForStore ( $searchtext ) . "%' ";
			}
			
			$dql = "SELECT COUNT(a.admin_id)
                FROM zselex_shop_admins a
                INNER JOIN users b ON b.uid=a.user_id
                " . $append . "
                ";
			// echo $dql . '<br>';
			$query = $this->_em->createNativeQuery ( $dql, $rsm );
			$result = $query->getSingleScalarResult ();
			// echo "<pre>"; print_r($result); echo "</pre>";
			
			return $result;
			// echo "<pre>"; print_r($result); echo "</pre>";
		} catch ( \Exception $e ) {
			// echo 'Message: ' . $e->getMessage();
			// exit;
		}
	}
}
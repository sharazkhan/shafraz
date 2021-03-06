<?php

/**
 * ZSELEX.
 *
 * @copyright 
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package ShopProducts
 * @author  <>.
 * @link http://modulestudio.de/
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.5.4 (http://modulestudio.de) at Tue Feb 07 21:56:43 IST 2012.
 */
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use DoctrineExtensions\Paginate\Paginate;
use Doctrine\ORM;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Repository class used to implement own convenience methods for performing certain DQL queries.
 *
 * This is the base repository class for shop entities.
 */
class ZSELEX_Entity_Repository_ZenShopRepository extends EntityRepository {
	public function getZenCart($args) {
		try {
			$shop_id = $args ['shop_id'];
			$dql = "SELECT a.zen_id , a.domain , a.hostname , a.dbname , a.username , a.password , a.table_prefix
                FROM ZSELEX_Entity_ZenShop a
                WHERE a.shop = :shop_id";
			$query = $this->_em->createQuery ( $dql );
			$query->setParameter ( 'shop_id', $shop_id );
			$result = $query->getOneOrNullResult ( 2 );
			// echo "<pre>"; print_r($result); echo "</pre>";
			return $result;
		} catch ( \Exception $e ) {
			// echo 'Message: ' . $e->getMessage() . '<br>';
			// echo $query->getSQL();
			// exit;
		}
	}
}

<?php

/**
 * ZSELEX.
 *
 * @copyright R2International
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package ShopProducts
 * @author R2International <R2International>.
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
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Repository class used to implement own convenience methods for performing certain DQL queries.
 *
 * This is the base repository class for shop entities.
 */
class ZPayment_Entity_Repository_Netaxept extends ZSELEX_Entity_Repository_General {

    public function updateNetaxeptPayment($status, $orderId, $transactionId, $info = '', $cardtype) {

        // $date = date("Y-m-d");
        $qb = $this->_em->createQueryBuilder();
        $q = $qb->update('ZPayment_Entity_Netaxept', 'u')
                ->set('u.status', '?1')
                ->set('u.info', '?4')
                ->set('u.cardtype', '?5')
                ->where('u.zselex_order_id = ?2 AND u.nets_transaction_id = ?3')
                ->setParameter(1, $status)
                ->setParameter(2, $orderId)
                ->setParameter(3, $transactionId)
                ->setParameter(4, $info)
                ->setParameter(5, $cardtype)
                ->getQuery();
        $p = $q->execute();
        return $p;
    }

    public function getNetaxept($args) {
        $shop_id = $args['shop_id'];
        $dql = "SELECT a.enabled , a.test_mode , a.merchant_id , a.token , a.return_url , a.test_merchant_id , a.test_token
                  FROM ZPayment_Entity_NetaxeptSetting a 
                  WHERE a.shop_id = :shop_id";

        //echo $dql;

        $query = $this->_em->createQuery($dql);
        $query->setParameter('shop_id', $shop_id);
        $result = $query->getOneOrNullResult();
        //echo "<pre>";  print_r($result);   echo "</pre>";

        return $result; // hydrate result to array
    }

    public function getPaymentDetails($args) {
        $order_id = $args['order_id'];
        $dql = "SELECT a.nets_transaction_id , a.status , a.info , a.cardtype 
                  FROM ZPayment_Entity_Netaxept a 
                  WHERE a.zselex_order_id = :order_id";

        $query = $this->_em->createQuery($dql);
        $query->setParameter('order_id', $order_id);
        $result = $query->getOneOrNullResult();
        //echo "<pre>";  print_r($result);   echo "</pre>";

        return $result; // hydrate result to array
    }

    public function counts($args) {
        $shop_id = $args['shop_id'];
        $query = $this->_em->createQuery('SELECT COUNT(u.id) FROM ZPayment_Entity_NetaxeptSetting u WHERE u.shop_id=:shop_id');
        $query->setParameter('shop_id', $shop_id);
        $count = $query->getSingleScalarResult();
        return $count;
    }

    public function netsTxnCount($args) {
        $txn_id = $args['txn_id'];
        $query = $this->_em->createQuery("SELECT COUNT(u.nets_transaction_id) FROM ZPayment_Entity_Netaxept u 
                                           WHERE u.nets_transaction_id=:txn_id AND (u.status!='' OR u.status!='Placed')");
        $query->setParameter('txn_id', $txn_id);
        //$query->setParameter('status', "");
        //$query->setParameter('status', "");
        $count = $query->getSingleScalarResult();

        return $count;
    }

    public function nets_orderCount($args) {
        $order_id = $args['order_id'];
        $query = $this->_em->createQuery('SELECT COUNT(u.zselex_order_id) FROM ZPayment_Entity_Netaxept u '
                . 'WHERE u.zselex_order_id=:order_id');
        $query->setParameter('order_id', $order_id);
        $count = $query->getSingleScalarResult();
        return $count;
    }

    public function updateNetaxeptSettings($args) {
        // echo "<pre>"; print_r($args); echo "</pre>";  exit;

        $qb = $this->_em->createQueryBuilder();
        $q = $qb->update('ZPayment_Entity_NetaxeptSetting', 'u')
                ->set('u.enabled', ':enabled')
                ->set('u.test_mode', ':test_mode')
                ->set('u.merchant_id', ':merchant_id')
                ->set('u.token', ':token')
                ->set('u.test_merchant_id', ':test_merchant_id')
                ->set('u.test_token', ':test_token')
                ->set('u.return_url', ':return_url')
                ->where('u.shop_id = :shop_id')
                ->setParameter('shop_id', $args['shop_id'])
                ->setParameter('enabled', $args['Netaxept_enabled'])
                ->setParameter('test_mode', $args['Netaxept_testmode'])
                ->setParameter('merchant_id', $args['Netaxept_merchant_id'])
                ->setParameter('token', $args['Netaxept_token'])
                ->setParameter('test_merchant_id', $args['Netaxept_testmerchant_id'])
                ->setParameter('test_token', $args['Netaxept_testtoken'])
                ->setParameter('return_url', $args['Netaxept_returl'])
                ->getQuery();
        $p = $q->execute();
        if ($p) {
            return $p;
        }
    }

    public function paymentMode($args) {
        $shop_id = $args['shop_id'];
        $dql = "SELECT a.enabled , a.test_mode 
                  FROM ZPayment_Entity_NetaxeptSetting a 
                  WHERE a.shop_id = :shop_id";

        //echo $dql;

        $query = $this->_em->createQuery($dql);
        $query->setParameter('shop_id', $shop_id);
        $result = $query->getOneOrNullResult();
        //echo "<pre>";  print_r($result);   echo "</pre>";

        return $result; // hydrate result to array
    }

}

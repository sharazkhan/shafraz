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

/**
 * Repository class for DQL calls
 *
 */
class Zvelo_Entity_Repository_BicycleRepo extends EntityRepository {

    function getBicycles() {
        $dql = "SELECT a.bicycle_id , a.name , a.nos , a.iconname , a.imagename , a.imagename2
                FROM Zvelo_Entity_Bicycle a";
        $query = $this->_em->createQuery($dql);

        $result = $query->getResult();
        return $result;
    }

    function getBicycleDetail($args) {
        $bicycle_id = $args['bicycle_id'];
        // echo "ID2 :" .  $bicyle_id;
        $dql = "SELECT a.bicycle_id , a.name , a.nos , a.iconname , a.imagename , a.imagename2 , a.description
                FROM Zvelo_Entity_Bicycle a
                WHERE a.bicycle_id = :bicycle_id";
        $query = $this->_em->createQuery($dql);
        $query->setParameter('bicycle_id', $bicycle_id);

        $result = $query->getOneOrNullResult();
        // echo "<pre>";   print_r($result);   echo "</pre>";  exit;
        return $result;
    }

    function getBicycleDetailByCustomerId($args) {
        $customer_id = $args['customer_id'];
        // echo "ID2 :" .  $bicyle_id;
        $dql = "SELECT a.bicycle_id , a.name , a.nos , a.iconname , a.imagename , a.imagename2 , a.description
                FROM Zvelo_Entity_CustomerWish b
                JOIN b.bicycle a
                WHERE b.customer = :customer_id";
        $query = $this->_em->createQuery($dql);
        $query->setParameter('customer_id', $customer_id);

        $result = $query->getOneOrNullResult();
        // echo "<pre>";   print_r($result);   echo "</pre>";  exit;
        return $result;
    }

    function updateBicycle($args, $customer_id) {
        //echo "comes here"; exit;
        //echo "<pre>";  print_r($args);   echo "</pre>"; exit;
        try {
            $bicycle_id = $args['bicycle_id'];

            if (!empty($bicycle_id)) {
                $qb = $this->_em->createQueryBuilder();
                $q = $qb->update('Zvelo_Entity_CustomerWish', 'u')
                        ->set('u.bicycle', '?1')
                        ->where('u.customer = ?2')
                        ->setParameter(1, $bicycle_id)
                        ->setParameter(2, $customer_id)
                        ->getQuery();
                $p = $q->execute();

                return $p;
            } else {
                return true;
            }
        } catch (\Exception $e) {
            echo 'Message: ' . $e->getMessage();
            die();
        }
    }

    function createBicycleInWish($args, $customerId) {

        // echo $customerId;  exit;
        // echo $customer; exit;
        //echo "<pre>";     print_r($customer);    echo "</pre>";  exit;
        try {
            $bicycle_id = $args['bicycle_id'];
            if (!empty($bicycle_id)) {
                $entity = new Zvelo_Entity_CustomerWish();
                $customer = $this->_em->find('Zvelo_Entity_Customer', $customerId);
                $entity->setCustomer($customer);
                $bicycle = $this->_em->find('Zvelo_Entity_Bicycle', $bicycle_id);
                $entity->setBicycle($bicycle);

                $this->_em->persist($entity);
                $this->_em->flush();
                $wish_id = $entity->getWish_id();


                if (!$wish_id) {
                    LogUtil::registerError($this->__('Error! Could not create a bicycle wish'));
                    return $this->redirect(ModUtil::url('Zvelo', 'user', 'main'));
                }
                return $msrmrntId;
            } else {
                return true;
            }
        } catch (\Exception $e) {
            echo 'Message: ' . $e->getMessage();
            die();
        }
    }

}
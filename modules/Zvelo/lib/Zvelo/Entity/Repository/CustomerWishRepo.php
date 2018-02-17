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
class Zvelo_Entity_Repository_CustomerWishRepo extends EntityRepository {

    public function count($customer_id) {
        try {
            // $customer_id = $args['customer_id'];
            $query = $this->_em->createQuery('SELECT COUNT(a.wish_id) 
                       FROM Zvelo_Entity_CustomerWish a '
                    . 'WHERE a.customer=:customer_id');
            $query->setParameter('customer_id', $customer_id);
            $count = $query->getSingleScalarResult();
            return $count;
        } catch (\Exception $e) {
            echo 'Message: ' . $e->getMessage();
            die();
        }
        return false;
    }

    function createWish($formElement, $customerId) {

        // echo $customerId;  exit;
        // echo $customer; exit;
        //echo "<pre>";     print_r($customer);    echo "</pre>";  exit;
        try {
            $entity = new Zvelo_Entity_CustomerWish();
            $customer = $this->_em->find('Zvelo_Entity_Customer', $customerId);
            $entity->setCustomer($customer);
            if (!empty($bicycleId)) {
                $bicycle = $this->_em->find('Zvelo_Entity_Bicycle', $bicycleId);
                $entity->setBicycle($bicycle);
            }
            $usage = serialize($formElement['usage']);
            $ageclass = $formElement['ageclass'];
            $kmonthly = $formElement['kmmonthly'];
            $framematerial = serialize($formElement['framematerial']);
            $frametype = serialize($formElement['frametype']);
            $suspension = serialize($formElement['suspension']);
            $gears = serialize($formElement['gears']);
            $brakes = serialize($formElement['brakes']);
            $accessories = serialize($formElement['accessories']);

            //$entity->setSeatposition(!empty($formElement['value1']) ? $formElement['value1'] : '');
            $entity->setUsages(!empty($usage) ? $usage : '');
            $entity->setAgeclass(!empty($ageclass) ? $ageclass : '');
            $entity->setKmmonthly(!empty($kmonthly) ? $kmonthly : '');
            $entity->setFramematerial(!empty($framematerial) ? $framematerial : '');
            $entity->setFrametype(!empty($frametype) ? $frametype : '');
            $entity->setSuspension(!empty($suspension) ? $suspension : '');
            $entity->setGears(!empty($gears) ? $gears : '');
            $entity->setBrakes(!empty($brakes) ? $brakes : '');
            $entity->setAccessories(!empty($accessories) ? $accessories : '');
            // $entity->setGears(!empty($formElement['value4']) ? $formElement['value4'] : '');

            $this->_em->persist($entity);
            $this->_em->flush();
            $wish_id = $entity->getWish_id();


            if (!$wish_id) {
                LogUtil::registerError($this->__('Error! Could not create customer wish'));
                return $this->redirect(ModUtil::url('Zvelo', 'user', 'main'));
            }
            return $msrmrntId;
        } catch (\Exception $e) {
            echo 'Message: ' . $e->getMessage();
            die();
        }
    }

    function updateWish($args, $customer_id) {
        //echo "comes here"; exit;
        //echo "<pre>";  print_r($args);   echo "</pre>"; exit;
        try {
            $usage = serialize($args['usage']);
            $ageclass = $args['ageclass'];
            $kmonthly = $args['kmmonthly'];
            $framematerial = serialize($args['framematerial']);
            $frametype = serialize($args['frametype']);
            $suspension = serialize($args['suspension']);
            $gears = serialize($args['gears']);
            $brakes = serialize($args['brakes']);
            $accessories = serialize($args['accessories']);

            $qb = $this->_em->createQueryBuilder();
            $q = $qb->update('Zvelo_Entity_CustomerWish', 'u')
                    //->set('u.bicycle', '?1')
                    //->set('u.seatposition', '?2')
                    ->set('u.usages', '?2')
                    ->set('u.ageclass', '?3')
                    ->set('u.kmmonthly', '?4')
                    ->set('u.framematerial', '?5')
                    ->set('u.frametype', '?6')
                    ->set('u.suspension', '?7')
                    ->set('u.gears', '?8')
                    ->set('u.brakes', '?9')
                    ->set('u.accessories', '?10')
                    ->where('u.customer = ?11')
                    //->setParameter(1, $args['value1'])
                    ->setParameter(2, $usage)
                    ->setParameter(3, $ageclass)
                    ->setParameter(4, $kmonthly)
                    ->setParameter(5, $framematerial)
                    ->setParameter(6, $frametype)
                    ->setParameter(7, $suspension)
                    ->setParameter(8, $gears)
                    ->setParameter(9, $brakes)
                    ->setParameter(10, $accessories)
                    ->setParameter(11, $customer_id)
                    ->getQuery();
            $p = $q->execute();

            return $p;
        } catch (\Exception $e) {
            echo 'Message: ' . $e->getMessage();
            die();
        }
    }

    function createSeatPosition($formElement, $customerId) {

        // echo $customerId;  exit;
        // echo $customer; exit;
        //echo "<pre>";     print_r($customer);    echo "</pre>";  exit;
        try {
            $entity = new Zvelo_Entity_CustomerWish();
            $customer = $this->_em->find('Zvelo_Entity_Customer', $customerId);
            $entity->setCustomer($customer);
            if (!empty($bicycleId)) {
                $bicycle = $this->_em->find('Zvelo_Entity_Bicycle', $bicycleId);
                $entity->setBicycle($bicycle);
            }

            $entity->setSeatposition(!empty($formElement['seatposition']) ? $formElement['seatposition'] : '');

            $this->_em->persist($entity);
            $this->_em->flush();
            $wish_id = $entity->getWish_id();


            if (!$wish_id) {
                LogUtil::registerError($this->__('Error! Could not create seat position'));
                return $this->redirect(ModUtil::url('Zvelo', 'user', 'main'));
            }
            return $msrmrntId;
        } catch (\Exception $e) {
            echo 'Message: ' . $e->getMessage();
            die();
        }
    }

    function updateSeatPostion($args, $customer_id) {
        //echo "comes here"; exit;
        //echo "<pre>";  print_r($args);   echo "</pre>"; exit;
        try {
            $seatposition = $args['seatposition'];


            $qb = $this->_em->createQueryBuilder();
            $q = $qb->update('Zvelo_Entity_CustomerWish', 'u')
                    //->set('u.bicycle', '?1')
                    //->set('u.seatposition', '?2')
                    ->set('u.seatposition', '?1')
                    ->where('u.customer = ?2')
                    //->setParameter(1, $args['value1'])
                    ->setParameter(1, $seatposition)
                    ->setParameter(2, $customer_id)
                    ->getQuery();
            $p = $q->execute();

            return $p;
        } catch (\Exception $e) {
            echo 'Message: ' . $e->getMessage();
            die();
        }
    }

    function getWish($args) {
        //echo "<pre>";  print_r($args);   echo "</pre>"; exit;
        try {
            $customer_id = $args['customer_id'];
            $dql = "SELECT a.wish_id,  a.seatposition , a.usages , a.ageclass ,
                 a.kmmonthly , a.framematerial , a.frametype , a.suspension , a.gears ,
                 a.brakes , a.accessories , a.comments
                 FROM Zvelo_Entity_CustomerWish a 
                 WHERE a.customer = :customer_id";

            //echo $dql; exit;
            $query = $this->_em->createQuery($dql);
            $query->setParameter('customer_id', $customer_id);
            $result = $query->getOneOrNullResult();
            // echo "<pre>";    print_r($result);   echo "</pre>";



            return $result;
        } catch (\Exception $e) {
            echo 'Message: ' . $e->getMessage();
            die();
        }
    }

    function getSeatPosition($args) {
        //echo "<pre>";  print_r($args);   echo "</pre>"; exit;
        try {
            $customer_id = $args['customer_id'];
            $dql = "SELECT a.seatposition 
                 FROM Zvelo_Entity_CustomerWish a 
                 WHERE a.customer = :customer_id";

            //echo $dql; exit;
            $query = $this->_em->createQuery($dql);
            $query->setParameter('customer_id', $customer_id);
            $result = $query->getOneOrNullResult();
            // echo "<pre>";    print_r($result);   echo "</pre>";



            return $result;
        } catch (\Exception $e) {
            echo 'Message: ' . $e->getMessage();
            die();
        }
    }

}
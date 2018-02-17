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
class Zvelo_Entity_Repository_CustomerErgonomicValueRepo extends EntityRepository {

    public function count($customer_id) {
        try {
            // $customer_id = $args['customer_id'];
            $query = $this->_em->createQuery('SELECT COUNT(a.ergonomic_value_id) 
                       FROM Zvelo_Entity_CustomerErgonomicValue a '
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

    function createErgonomicValues($formElement, $customerId) {

        // echo $customerId;  exit;
        // echo $customer; exit;
        //echo "<pre>";     print_r($customer);    echo "</pre>";  exit;
        try {
            $ergn_entity = new Zvelo_Entity_CustomerErgonomicValue();
            $customer = $this->_em->find('Zvelo_Entity_Customer', $customerId);
            $ergn_entity->setCustomer($customer);

            $getBicycle = $this->_em->getRepository('Zvelo_Entity_Bicycle')->getBicycleDetailByCustomerId(array('customer_id' => $customerId));
            $bicycleId = $getBicycle['bicycle_id'];

            if (!empty($bicycleId)) {
                $bicycle = $this->_em->find('Zvelo_Entity_Bicycle', $bicycleId);
                $ergn_entity->setBicycle($bicycle);
            }

            $ergn_entity->setValue1(!empty($formElement['value1']) ? $formElement['value1'] : '');
            $ergn_entity->setValue2(!empty($formElement['value2']) ? $formElement['value2'] : '');
            $ergn_entity->setValue3(!empty($formElement['value3']) ? $formElement['value3'] : '');
            $ergn_entity->setValue4(!empty($formElement['value4']) ? $formElement['value4'] : '');

            $this->_em->persist($ergn_entity);
            $this->_em->flush();
            $ergn_id = $ergn_entity->getErgonomic_value_id();


            if (!$ergn_id) {
                LogUtil::registerError($this->__('Error! Could not create values'));
                return $this->redirect(ModUtil::url('Zvelo', 'user', 'main'));
            }
            return $msrmrntId;
        } catch (\Exception $e) {
            echo 'Message: ' . $e->getMessage();
            die();
        }
    }

    function updateErgonomicValues($args, $customer_id) {
        //echo "comes here"; exit;
        //echo "<pre>";  print_r($args);   echo "</pre>"; exit;
        try {
            $getBicycle = $this->_em->getRepository('Zvelo_Entity_Bicycle')->getBicycleDetailByCustomerId(array('customer_id' => $customer_id));
            $bicycleId = $getBicycle['bicycle_id'];
            // echo $bicycleId; exit;
            $qb = $this->_em->createQueryBuilder();
            $q = $qb->update('Zvelo_Entity_CustomerErgonomicValue', 'u')
                    ->set('u.value1', '?1')
                    ->set('u.value2', '?2')
                    ->set('u.value3', '?3')
                    ->set('u.value4', '?4')
                    ->set('u.bicycle', '?6')
                    ->where('u.customer = ?5')
                    ->setParameter(1, $args['value1'])
                    ->setParameter(2, $args['value2'])
                    ->setParameter(3, $args['value3'])
                    ->setParameter(4, $args['value4'])
                    ->setParameter(5, $customer_id)
                    ->setParameter(6, $bicycleId)
                    ->getQuery();
            $p = $q->execute();

            return $p;
        } catch (\Exception $e) {
            echo 'Message: ' . $e->getMessage();
            die();
        }
    }

    function getErgonomicValues($args) {
        //echo "<pre>";  print_r($args);   echo "</pre>"; exit;
        try {
            $customer_id = $args['customer_id'];
            $dql = "SELECT a.ergonomic_value_id,  a.value1 , a.value2 , a.value3 ,
                 a.value4
                 FROM Zvelo_Entity_CustomerErgonomicValue a 
                 WHERE a.customer = :customer_id";

            //echo $dql; exit;
            $query = $this->_em->createQuery($dql);
            $query->setParameter('customer_id', $customer_id);
            $result = $query->getOneOrNullResult();
            // echo "<pre>";  print_r($result);   echo "</pre>"; exit;

            return $result;
        } catch (\Exception $e) {
            echo 'Message: ' . $e->getMessage();
            die();
        }
    }

}
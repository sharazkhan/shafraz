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
class Zvelo_Entity_Repository_CustomerMeasurementRepo extends EntityRepository {

    function createCustomerMeasurement($formElement, $customerId) {

        // echo $customerId;  exit;
        // echo $customer; exit;
        //echo "<pre>";     print_r($customer);    echo "</pre>";  exit;
        $msrmnt_entity = new Zvelo_Entity_CustomerMeasurement();
        $customer = $this->_em->find('Zvelo_Entity_Customer', $customerId);
        $msrmnt_entity->setCustomer($customer);
        $msrmnt_entity->setFunctionalheight(!empty($formElement['height']) ? $formElement['height'] : '');
        $msrmnt_entity->setShoulderheight(!empty($formElement['shoulderheight']) ? $formElement['shoulderheight'] : '');
        $msrmnt_entity->setShoulderwidth(!empty($formElement['shoulderwidth']) ? $formElement['shoulderwidth'] : '');
        $msrmnt_entity->setFistheight(!empty($formElement['fistheight']) ? $formElement['fistheight'] : '');
        $msrmnt_entity->setPelvicboneheight(!empty($formElement['pelvicboneheight']) ? $formElement['pelvicboneheight'] : '');
        $msrmnt_entity->setWeight(!empty($formElement['weight']) ? $formElement['weight'] : '');
        $this->_em->persist($msrmnt_entity);
        $this->_em->flush();
        $msrmrntId = $msrmnt_entity->getMeasurement_id();


        if (!$msrmrntId) {
            LogUtil::registerError($this->__('Error! Could not create measuremnt'));
            return $this->redirect(ModUtil::url('Zvelo', 'user', 'main'));
        }
        return $msrmrntId;
    }

    function updateMeasurement($args, $customer_id) {
        //echo "comes here"; exit;
        //echo "<pre>";  print_r($args);   echo "</pre>"; exit;
        $qb = $this->_em->createQueryBuilder();
        $q = $qb->update('Zvelo_Entity_CustomerMeasurement', 'u')
                ->set('u.functionalheight', '?1')
                ->set('u.shoulderheight', '?2')
                ->set('u.shoulderwidth', '?3')
                ->set('u.fistheight', '?4')
                ->set('u.pelvicboneheight', '?5')
                ->set('u.weight', '?6')
                ->where('u.customer = ?7')
                ->setParameter(1, $args['height'])
                ->setParameter(2, $args['shoulderheight'])
                ->setParameter(3, $args['shoulderwidth'])
                ->setParameter(4, $args['fistheight'])
                ->setParameter(5, $args['pelvicboneheight'])
                ->setParameter(6, $args['weight'])
                ->setParameter(7, $customer_id)
                ->getQuery();
        $p = $q->execute();

        $qb2 = $this->_em->createQueryBuilder();
        $q2 = $qb2->update('Zvelo_Entity_Customer', 'u')
                ->set('u.gender', '?8')
                ->where('u.customer_id = ?9')
                ->setParameter(8, $args['gender'])
                ->setParameter(9, $customer_id)
                ->getQuery();
        $p = $q2->execute();
        return $p;
    }

    function getMeasurementInfo($args) {
        //echo "<pre>";  print_r($args);   echo "</pre>"; exit;
        $customer_id = $args['customer_id'];
        $dql = "SELECT b.gender,a.measurement_id, a.functionalheight, a.shoulderheight , a.shoulderwidth , a.fistheight , a.pelvicboneheight ,
                 a.weight,a.comments
                 FROM Zvelo_Entity_CustomerMeasurement a 
                 JOIN a.customer b
                 WHERE a.customer = :customer_id";

        //echo $dql; exit;
        $query = $this->_em->createQuery($dql);
        $query->setParameter('customer_id', $customer_id);
        $result = $query->getOneOrNullResult();
        // echo "<pre>";  print_r($result);   echo "</pre>"; exit;

        return $result;
    }

}
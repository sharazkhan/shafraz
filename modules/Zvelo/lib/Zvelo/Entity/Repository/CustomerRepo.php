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
class Zvelo_Entity_Repository_CustomerRepo extends EntityRepository {

    function createCustomer($formElement) {
        //echo "helloo"; exit;
        try {
            $client_entity = new Zvelo_Entity_Customer();
            $client_entity->setGender($formElement['gender']);
            $this->_em->persist($client_entity);
            $this->_em->flush();
            $customerId = $client_entity->getCustomer_id();
            //echo "helloo2"; exit;
            // } catch (\Exception $e) {

            if (!$customerId) {
                LogUtil::registerError($this->__('Error! Could not create record'));
                return $this->redirect(ModUtil::url('Zvelo', 'user', 'main'));
            }

            return $customerId;
        } catch (\Exception $e) {
            echo 'Message: ' . $e->getMessage();
            exit;
        }
        // }
    }

    function getCustmerInfo($args) {
        $customer_id = $args['customer_id'];
        $dql = "SELECT a.customer_id, a.gender , a.first_name , a.last_name , a.address ,
                 a.address2,a.zipcode,a.city,a.phone,a.email,a.comments,a.cr_date
                 FROM Zvelo_Entity_Customer a 
                 WHERE a.customer_id = :customer_id";

        //echo $dql;
        $query = $this->_em->createQuery($dql);
        $query->setParameter('customer_id', $customer_id);
        $result = $query->getOneOrNullResult();
        // echo "<pre>";  print_r($result);   echo "</pre>"; exit;

        return $result;
    }

    function searchUsers($value) {
        // $value = $args['value'];
        $dql = "SELECT CONCAT(CONCAT(a.first_name, ' '), a.last_name) as value  , a.customer_id as data
                  FROM Zvelo_Entity_Customer a 
                  WHERE a.first_name LIKE '" . DataUtil::formatForStore($value) . "%'";

        //echo $dql;
        $query = $this->_em->createQuery($dql);
        $query->setFirstResult(0);
        $query->setMaxResults(10);
        $result = $query->getResult();
        // echo "<pre>";  print_r($result);   echo "</pre>"; exit;

        return $result;
    }

    function updateCustomerInfo($args, $customer_id) {
        //echo "comes here"; exit;
        //echo "<pre>";    print_r($args);  echo "</pre>";   exit;
        $qb = $this->_em->createQueryBuilder();
        $q = $qb->update('Zvelo_Entity_Customer', 'u')
                ->set('u.first_name', '?1')
                ->set('u.last_name', '?2')
                ->set('u.address', '?3')
                ->set('u.address2', '?4')
                ->set('u.zipcode', '?5')
                ->set('u.city', '?6')
                ->set('u.phone', '?7')
                ->set('u.comments', '?8')
                ->set('u.email', '?9')
                ->where('u.customer_id = ?10')
                ->setParameter(1, $args['first_name'])
                ->setParameter(2, $args['last_name'])
                ->setParameter(3, $args['address'])
                ->setParameter(4, $args['address2'])
                ->setParameter(5, $args['zip'])
                ->setParameter(6, $args['city'])
                ->setParameter(7, $args['phone'])
                ->setParameter(8, $args['comment'])
                ->setParameter(9, $args['email'])
                ->setParameter(10, $customer_id)
                ->getQuery();
        $p = $q->execute();
        return true;
    }

    function getAllUsers() {
        $dql = "SELECT a.customer_id , a.first_name , a.last_name
                FROM Zvelo_Entity_Customer a";
        $query = $this->_em->createQuery($dql);

        $result = $query->getResult();
        return $result;
    }

    function deleteUser($args) {
        $customer_id = $args['customer_id'];
        //echo $customer_id; exit;
        $dql = "DELETE FROM Zvelo_Entity_Customer a WHERE a.customer_id = :customer_id";
        $query = $this->_em->createQuery($dql);
        $query->setParameter('customer_id', $customer_id);
        $numDeleted = $query->execute();
        return $numDeleted;
    }

}
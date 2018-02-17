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
use Doctrine\ORM\Query;

/**
 * Repository class for DQL calls
 */
class ZSELEX_Entity_Repository_OrderRepository extends ZSELEX_Entity_Repository_General
{

    public function createOrder($args)
    {
        try {
            $item = ZSELEX_Util::purifyHtml($args);

            $shop = $this->_em->find('ZSELEX_Entity_Shop', $item ['shop_id']);
            // echo "shop_id : " . $shop; exit;

            $order = new ZSELEX_Entity_Order ();
            $order->setShop($shop);
            $order->setUser_id($item ['user_id']);
            $order->setFirst_name($item ['first_name']);
            $order->setLast_name($item ['last_name']);
            $order->setEmail($item ['email']);
            $order->setZip($item ['zip']);
            $order->setCity($item ['city']);
            $order->setStreet($item ['street']);
            $order->setAddress($item ['address']);
            $order->setPhone($item ['phone']);
            $order->setTotalprice($item ['totalprice']);
            $order->setVat($item ['vat']);
            $order->setShipping($item ['shipping']);

            $order->setStatus($item ['status']);
            $order->setPayment_type($item ['payment_type']);
            $order->setSelf_pickup($item ['self_pickup']);
            $this->_em->persist($order);
            $this->_em->flush();

            $InsertId = $order->getId();
            $result   = $InsertId;
            return $result;
        } catch (\Exception $e) {
            echo 'createOrder Message: '.$e->getMessage().'<br>';
            // echo $query->getSQL();
            exit();
        }
    }

    public function updateOrderId($args)
    {
        try {
            $upd_args = array(
                'entity' => 'ZSELEX_Entity_Order',
                'fields' => $args ['fields'],
                // 'setParams' => $args['setParams'],
                'where' => $args ['where']
            );
            $update   = $this->updateEntity($upd_args);
            return $update;
        } catch (\Exception $e) {
            // echo 'Message: ' . $e->getMessage() . '<br>';
            // // echo $query->getSQL();
            // exit;
        }
    }

    public function createOrderItems($args)
    {
        try {
            $item = ZSELEX_Util::purifyHtml($args);

            // $order = $this->_em->find('ZSELEX_Entity_Order', $item['order_id']);
            $shop    = $this->_em->find('ZSELEX_Entity_Shop', $item ['shop_id']);
            $product = $this->_em->find('ZSELEX_Entity_Product',
                $item ['product_id']);

            $orderItem = new ZSELEX_Entity_OrderItem ();
            $orderItem->setProduct($product);
            $orderItem->setShop($shop);
            $orderItem->setOrder_id($item ['order_id']);
            $orderItem->setQuantity($item ['quantity']);
            $orderItem->setProduct_options(stripslashes($item ['product_options']));
            $orderItem->setPrice($item ['price']);
            $orderItem->setOptions_price($item ['options_price']);
            $orderItem->setPrd_answer($item ['prd_answer']);
            $orderItem->setTotal($item ['total']);

            $this->_em->persist($orderItem);
            $this->_em->flush();

            $InsertId = $orderItem->getItem_id();
            $result   = $InsertId;
            return $result;
        } catch (\Exception $e) {
            echo 'Message: '.$e->getMessage().'<br>';
            // echo $query->getSQL();
            exit();
        }
    }

    /**
     * Get order details
     * 
     * @param type $args
     * @return type
     */
    public function getOrderDetails($args)
    {
        $order_id = $args ['order_id'];
        $dql      = "SELECT a.item_id , a.quantity,a.product_options,a.price,a.options_price,a.total,
                b.prd_image , b.prd_description , b.prd_price , b.product_name , b.product_id , a.prd_answer , b.prd_question , b.no_vat ,
                b.prd_quantity
                FROM ZSELEX_Entity_OrderItem a
                JOIN a.product b 
                WHERE a.order_id=:order_id";
        $query    = $this->_em->createQuery($dql);
        $query->setParameter('order_id', $order_id);
        $result   = $query->getArrayResult();
        // echo "<pre>"; print_r($result); echo "</pre>";
        return $result;
    }

    public function getOrderItemsTotal($args)
    {
        $order_id = $args ['order_id'];
        $dql      = "SELECT SUM(a.total) as grandtotal
                FROM ZSELEX_Entity_OrderItem a
                WHERE a.order_id=:order_id";
        $query    = $this->_em->createQuery($dql);
        $query->setParameter('order_id', $order_id);
        $result   = $query->getOneOrNullResult(2);
        // echo "<pre>"; print_r($result); echo "</pre>";
        return $result ['grandtotal'];
    }

    /**
     * Get Order Info
     * 
     * @param array $args
     * @return array
     */
    public function getOrderInfo($args)
    {
        if (isset($args ['fields']) && !empty($args ['fields'])) {
            $fields = $this->generateFields($args ['fields']);
        } else {
            $fields = "a";
        }
        $where  = $this->generateWhere($args ['where']);
        $dql    = "SELECT $fields
                FROM ZSELEX_Entity_Order a
                JOIN a.shop b
                WHERE ".$where ['where'];
        // echo $dql;
        $query  = $this->_em->createQuery($dql);
        $query->setParameters($where ['setParams']);
        $result = $query->getOneOrNullResult(2);
        // echo "<pre>"; print_r($result); echo "</pre>";
        return $result;
    }

    public function serviceOrderTxnIdCount($args)
    {
        $order_id = $args ['order_id'];
        $txn_id   = $args ['txn_id'];
        $query    = $this->_em->createQuery('SELECT COUNT(a.transaction_id) FROM ZSELEX_Entity_ServiceOrder a '.'WHERE a.order_id=:order_id AND a.transaction_id=:txn_id');
        $query->setParameter('order_id', $order_id);
        $query->setParameter('txn_id', $txn_id);
        $count    = $query->getSingleScalarResult();
        return $count;
    }

    public function hasNoVatProduct($args)
    {
        $order_id = $args ['order_id'];

        // echo "<pre>"; print_r($setParams); echo "</pre>"; exit;
        $dql    = "SELECT COUNT(a.item_id)
                 FROM ZSELEX_Entity_OrderItem a
                 JOIN a.product b
                 WHERE a.order_id=:order_id AND b.no_vat=1";
        // echo $dql;
        $query  = $this->_em->createQuery($dql);
        $query->setParameter('order_id', $order_id);
        $result = $query->getSingleScalarResult();
        return $result;
    }
}
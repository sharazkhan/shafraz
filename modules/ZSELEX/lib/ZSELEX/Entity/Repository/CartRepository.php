<?php

/**
 * Tag - a content-tagging module for the Zikukla Application Framework.
 * 
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Repository class for DQL calls.
 */
class ZSELEX_Entity_Repository_CartRepository extends ZSELEX_Entity_Repository_General
{

    public function createCartItem($args)
    {
        try {
            $item = ZSELEX_Util::purifyHtml($args);

            $shop    = $this->_em->find('ZSELEX_Entity_Shop', $item ['shop_id']);
            $product = $this->_em->find('ZSELEX_Entity_Product',
                $item ['product_id']);

            $cartItem = new ZSELEX_Entity_Cart ();
            $cartItem->setUser_id($item ['user_id']);
            $cartItem->setProduct($product);
            $cartItem->setShop($shop);
            $cartItem->setQuantity($item ['quantity']);
            $cartItem->setOriginal_price($item ['original_price']);
            $cartItem->setPrice($item ['price']);
            $cartItem->setOptions_price($item ['options_price']);
            $cartItem->setFinal_price($item ['final_price']);
            $cartItem->setCart_content(stripslashes($item ['cart_content']));
            $cartItem->setPrd_answer($item ['prd_answer']);
            $cartItem->setOutofstock(0);
            $cartItem->setIs_guest($item['is_guest']);

            $this->_em->persist($cartItem);
            $this->_em->flush();

            $InsertId = $cartItem->getCart_id();
            $result   = $InsertId;

            return $result;
        } catch (\Exception $e) {
            // echo 'Message: ' . $e->getMessage() . '<br>';
            echo 'Message: '.'Unexpected Error Occured';
            // echo $query->getSQL();
            exit();
        }
    }

    public function getCartCount($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $setParams = array();
        $where     = $args ['where'];
        $setParams = $args ['setParams'];

        try {
            $dql = 'SELECT COUNT(a.cart_id) FROM ZSELEX_Entity_Cart a
               WHERE '.$where;

            $query = $this->_em->createQuery($dql);
            // $query->setParameters($setParams);
            $count = $query->getSingleScalarResult();

            return $count;
        } catch (\Exception $e) {
            echo 'Message: '.$e->getMessage();
            exit();
        }
    }

    public function getCart($args)
    {
        // $shop_id = $args['shop_id'];
        $where    = $args ['where'];
        $fields   = $this->generateFields($args ['fields']);
        $whereRes = $this->generateWhere($where);

        // echo "<pre>"; print_r($whereRes); echo "</pre>"; exit;

        $dql    = "SELECT $fields
                 FROM ZSELEX_Entity_Cart a
                 WHERE ".$whereRes ['where'];
        // echo $dql; exit;
        $query  = $this->_em->createQuery($dql);
        $query->setParameters($whereRes ['setParams']);
        $result = $query->getOneOrNullResult(2);

        return $result;
    }

    public function updateCart($args)
    {
        $where      = $args ['where'];
        $item       = $args ['item'];
        /*
         * $upd_dql = "UPDATE ZSELEX_Entity_Cart a SET a.product='" . $item['product_id'] . "' , a.cart_content='" . $item['cart_content'] . "' ,
         * a.prd_answer='" . $item['prd_answer'] . "',
         * a.quantity='" . $item['quantity'] . "' , a.original_price='" . $item['original_price'] . "' , a.price='" . $item['price'] . "' , a.options_price='" . $item['options_price'] . "' , a.final_price='" . $item['final_price'] . "'
         * WHERE $where";
         * // echo $upd_dql; exit;
         * $query = $this->_em->createQuery($upd_dql);
         * $numUpdated = $query->execute();
         */
        $numUpdated = $this->updateEntity(null, 'ZSELEX_Entity_Cart', $item,
            $where);

        return $numUpdated;
    }

    /**
     * Get products in cart for user
     *
     * @api
     *
     * @param string $args['fields']
     * @param string $args['where']
     * @param array $args['setParams']
     *
     * @return array of cart products
     */
    public function getCartProducts($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>";
        try {

            // $setParams = array();
            // $setParams = $args ['setParams'];
            $fields    = $args ['fields'];
            $where     = $args ['where'];
            $setParams = $args ['setParams'];
            $fields    = $this->generateFields($fields);

            // echo "<pre>"; print_r($args ['fields']); echo "</pre>"; exit;


            $rsm = new ORM\Query\ResultSetMapping ();



            $rsm->addEntityResult('ZSELEX_Entity_Cart', 'u');
            foreach ($args ['fields'] as $k => $v) {
                $fieldSplit = explode('.', $v);
                /*  $rsm->addFieldResult('u', 'cart_id', 'cart_id');
                  $rsm->addFieldResult('u', 'price', 'price');
                  $rsm->addFieldResult('u', 'final_price', 'final_price');
                  $rsm->addFieldResult('u', 'cart_content', 'cart_content');
                  $rsm->addFieldResult('u', 'outofstock', 'outofstock');
                  $rsm->addFieldResult('u', 'quantity', 'quantity');

                  $rsm->addMetaResult('u', 'product_id', 'product_id');
                  $rsm->addMetaResult('u', 'shop_id', 'shop_id'); */
                if ($fieldSplit[0] == 'a') {
                    $rsm->addFieldResult('u', $fieldSplit[1], $fieldSplit[1]);
                } else {
                    $rsm->addMetaResult('u', $fieldSplit[1], $fieldSplit[1]);
                }
            }


            $dql = "SELECT $fields
                 FROM zselex_cart a
                 LEFT JOIN zselex_products b ON b.product_id=a.product_id
                 LEFT JOIN zselex_shop c ON c.shop_id=a.shop_id
                 WHERE ".$where.' '.' GROUP BY a.cart_id';

            // echo $dql; exit;


            $query = $this->_em->createNativeQuery($dql, $rsm);
            // $query->useResultCache(true);
            //$setParams ['status'] = 1;
            if (isset($setParams) && !empty($setParams)) {
                $query->setParameters($setParams);
            }

            // echo $query->getSQL() . '<br><br>';
            // $result = $query->getResult(2);
            $result = $query->getArrayResult();
            //  echo "<pre>"; print_r($result); echo "</pre>"; exit;

            return $result;
        } catch (\Exception $e) {
            echo 'Message: '.$e->getMessage();
            exit;
        }
    }

    public function getCartProducts_1($args)
    {
        // $shop_id = $args['shop_id'];
        // echo "<pre>"; print_r($args); echo "</pre>";
        $fields    = $args ['fields'];
        $where     = $args ['where'];
        $setParams = $args ['setParams'];
        $fields    = $this->generateFields($fields);

        $dql    = "SELECT $fields
                 FROM ZSELEX_Entity_Cart a
                 JOIN a.product b
                 JOIN a.shop c
                 WHERE ".$where.' '.' GROUP BY a.cart_id';
        // . " " . " GROUP BY b.product_id"
        // LEFT JOIN a.product b
        // echo $dql;
        $query  = $this->_em->createQuery($dql);
        $query->setParameters($setParams);
        $result = $query->getResult(2);
        // echo "<pre>"; print_r($result); echo "</pre>"; exit;
        return $result;
    }

    public function getCartTotalShop($args)
    {
        $setParams = $args ['setParams'];

        // echo "<pre>"; print_r($setParams); echo "</pre>"; exit;
        $dql    = 'SELECT sum(a.final_price) as grandTotal 
                 FROM ZSELEX_Entity_Cart a
                 JOIN a.product b
                 WHERE a.user_id=:user_id AND a.shop=:shop_id';
        //, sum(b.shipping_price) as shippingTotal
        $query  = $this->_em->createQuery($dql);
        $query->setParameters($setParams);
        $result = $query->getOneOrNullResult(2);

        return $result;
    }

    /**
     * Get total shipping charges fo products in cart
     * Skip shiping charges for the product which has no_delivery checked
     *
     * @param type $args
     * @return array
     */
    public function getTotalShipping($args)
    {
        $setParams = $args ['setParams'];

        // echo "<pre>"; print_r($setParams); echo "</pre>"; exit;
        $dql    = 'SELECT sum(b.shipping_price) as shippingTotal 
                 FROM ZSELEX_Entity_Cart a
                 JOIN a.product b
                 WHERE a.user_id=:user_id AND a.shop=:shop_id and b.no_delivery < 1';
        $query  = $this->_em->createQuery($dql);
        $query->setParameters($setParams);
        $result = $query->getOneOrNullResult(2);

        return $result;
    }

    public function getNoDeliveryCount($args)
    {
        $setParams = $args ['setParams'];

        // echo "<pre>"; print_r($setParams); echo "</pre>"; exit;
        $dql    = 'SELECT sum(b.no_delivery) as noDeliveryCount
                 FROM ZSELEX_Entity_Cart a
                 JOIN a.product b
                 WHERE a.user_id=:user_id AND a.shop=:shop_id';
        //, sum(b.shipping_price) as shippingTotal
        $query  = $this->_em->createQuery($dql);
        $query->setParameters($setParams);
        $result = $query->getOneOrNullResult(2);

        return $result['noDeliveryCount'];
    }

    public function count($args)
    {
        $countArgs = array(
            'entity' => $args ['entity'],
            'field' => $args ['field'],
            'where' => $args ['where']
        );
        $count     = $this->getCount($countArgs);

        return $count;
    }

    public function getUserCartTotal($args)
    {
        $user_id = $args ['user_id'];

        // echo "userID :" . $user_id . '<br>';

        $dql    = 'SELECT sum(a.final_price) as total , sum(a.quantity) as qty
                 FROM ZSELEX_Entity_Cart a
                 JOIN a.product b
                 WHERE a.user_id=:user_id';
        // echo $dql;
        $query  = $this->_em->createQuery($dql);
        $query->setParameter('user_id', $user_id);
        $result = $query->getOneOrNullResult(2);
        // echo "<pre>"; print_r($result); echo "</pre>";
        return $result;
    }

    public function getVatTotal1($args)
    {
        $user_id         = $args ['user_id'];
        $shop_id         = $args ['shop_id'];
        $is_freight      = $args ['is_freight'];
        $freigt_shipping = $args ['freigt_shipping'];

        // echo "<pre>"; print_r($setParams); echo "</pre>"; exit;
        /*
         * $dql = "SELECT sum(a.final_price) as total
         * FROM ZSELEX_Entity_Cart a
         * JOIN a.product b
         * WHERE a.user_id=:user_id AND a.shop=:shop_id AND (b.no_vat=0 OR b.no_vat='')";
         */
        if ($is_freight) {
            $dql    = 'SELECT sum(a.final_price) as total1
                 FROM ZSELEX_Entity_Cart a
                 JOIN a.product b
                 WHERE a.user_id=:user_id AND a.shop=:shop_id AND b.no_vat < 1';
            // echo $dql;
            $query  = $this->_em->createQuery($dql);
            $query->setParameter('user_id', $user_id);
            $query->setParameter('shop_id', $shop_id);
            $result = $query->getOneOrNullResult(2);
            if ($result ['total1']) {
                return $result ['total1'] + $freigt_shipping;
            } else {
                return 0;
            }
        } else {
            $dql    = 'SELECT sum(a.final_price) as total1 , sum(b.shipping_price) as total2
                 FROM ZSELEX_Entity_Cart a
                 JOIN a.product b
                 WHERE a.user_id=:user_id AND a.shop=:shop_id AND b.no_vat < 1';
            // echo $dql;
            $query  = $this->_em->createQuery($dql);
            $query->setParameter('user_id', $user_id);
            $query->setParameter('shop_id', $shop_id);
            $result = $query->getOneOrNullResult(2);

            return $result ['total1'] + $result ['total2'];
        }
    }

    /**
     * Get Total without no VAT products
     * 
     * @param type $args
     * @return int
     */
    public function getVatTotal($args)
    {
        $user_id         = $args ['user_id'];
        $shop_id         = $args ['shop_id'];
        $is_freight      = $args ['is_freight'];
        $freigt_shipping = $args ['freigt_shipping'];

        // echo "<pre>"; print_r($setParams); echo "</pre>"; exit;
        $dql = 'SELECT sum(a.final_price) as total
          FROM ZSELEX_Entity_Cart a
          JOIN a.product b
          WHERE a.user_id=:user_id AND a.shop=:shop_id AND b.no_vat < 1';

        $query  = $this->_em->createQuery($dql);
        $query->setParameter('user_id', $user_id);
        $query->setParameter('shop_id', $shop_id);
        $result = $query->getOneOrNullResult(2);

        return $result ['total'];
    }
    // public function generateWhere
}
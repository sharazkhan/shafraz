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
use Doctrine\ORM;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Repository class for DQL calls
 */
class ZSELEX_Entity_Repository_BundleRepo extends ZSELEX_Entity_Repository_General
{

    public function bundlesPurchased($args)
    {
        $shop_id = $args ['shop_id'];
        $dql     = "SELECT a.service_bundle_id , a.original_quantity , a.quantity , a.service_status , a.bundle_type ,
                 a.timer_date,a.timer_days,b.bundle_id,b.bundle_name
                 FROM ZSELEX_Entity_ServiceBundle a 
                 JOIN a.bundle b
                 WHERE a.shop = :shop_id
                 ORDER BY b.type ASC";
        // echo $dql;

        $query  = $this->_em->createQuery($dql);
        $query->setParameter('shop_id', $shop_id);
        $result = $query->getResult();
        // echo "<pre>"; print_r($result); echo "</pre>";

        return $result; // hydrate result to array
    }

    public function getCurrentDemoDays($args)
    {
        $sid    = $args ['sid'];
        $dql    = "SELECT a.timer_days FROM ZSELEX_Entity_ServiceBundle a
            WHERE a.service_bundle_id=?1";
        $query  = $this->_em->createQuery($dql);
        $query->setParameter(1, $sid);
        $result = $query->getOneOrNullResult();
        return $result ['timer_days'];
    }

    function updateDemo($args)
    {
        // $date = date("Y-m-d");
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $qb = $this->_em->createQueryBuilder();
        $q  = $qb->update('ZSELEX_Entity_ServiceBundle', 'u')->set('u.timer_days',
                '?1')->set('u.timer_date', '?2')->set('u.service_status', '?3')->where('u.service_bundle_id = ?4')->setParameter(1,
                $args ['demo_period'])->setParameter(2, date("Y-m-d"))->setParameter(3,
                1)->setParameter(4, $args ['sid'])->getQuery();
        $p  = $q->execute();

        $demoItem   = array(
            'start_date' => date("Y-m-d"),
            'timer_days' => $args ['demo_period']
        );
        $updateDemo = $this->updateEntity(null, 'ZSELEX_Entity_ServiceDemo',
            $demoItem,
            array(
            'a.shop' => $args ['shop_id'],
            'a.bundle' => $args ['bundle_id']
        ));
        // if ($p) {
        $this->updateBundleItemsDemo($args ['bundle_id'], $args ['shop_id'],
            $args ['demo_period'], date("Y-m-d"), $args ['bundle_type'], 1,
            $args ['quantity']);
        // return $p;
        return true;
        // }
    }

    function updateBundleItemsDemo($bundle_id, $shop_id, $demo_period, $date,
                                   $bundle_type, $service_status = 1,
                                   $bundle_qty)
    {
        // $date = date("Y-m-d");
        // echo $bundle_id; exit;
        /*
         * $qb = $this->_em->createQueryBuilder();
         * $q = $qb->update('ZSELEX_Entity_ServiceShop', 'u')
         * ->set('u.timer_days', '?1')
         * ->set('u.timer_date', '?2')
         * ->set('u.service_status', '?3')
         * ->where('u.shop = ?5 AND u.bundle = ?4')
         * ->setParameter(1, $demo_period)
         * ->setParameter(2, date("Y-m-d"))
         * ->setParameter(3, 1)
         * ->setParameter(4, $bundle_id)
         * ->setParameter(5, $shop_id)
         * ->getQuery();
         * $p = $q->execute();
         * return $p;
         */
        $repo        = $this->_em->getRepository('ZSELEX_Entity_Bundle');
        $bundleitems = $repo->getAll(array(
            'entity' => 'ZSELEX_Entity_BundleItem',
            'fields' => array(
                'a.id',
                'b.bundle_id',
                'a.service_name',
                'a.servicetype',
                'c.plugin_id',
                'a.price',
                'a.qty',
                'a.qty_based',
                'b.bundle_type'
            ),
            'joins' => array(
                'JOIN a.bundle b',
                'LEFT JOIN a.plugin c'
            ),
            'where' => array(
                'a.bundle' => $bundle_id
            ),
            'groupby' => 'a.id'
        ));

        // echo "<pre>"; print_r($bundleitems); echo "</pre>"; exit;
        $ownerInfo = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwnerInfo',
                $args      = array(
                'shop_id' => $shop_id
        ));
        $values    = array(
            'bundle' => 1,
            'service_status' => $service_status,
            'user_id' => UserUtil::getVar('uid'),
            'owner_id' => $ownerInfo ['user_id'],
            'shop_id' => $shop_id,
            // 'main_bundle' => $main_bundle['bundle_id'],
            'timer_date' => $date,
            'quantity' => $bundle_qty,
            'bundleitems' => $bundleitems,
            'timer_days' => $demo_period,
            'bundle_type' => $bundle_type,
            'bundle_id' => $bundle_id
        );
        // $add = ModUtil::apiFunc('ZSELEX', 'service', 'insertBundleItems', $values);
        $add       = ModUtil::apiFunc('ZSELEX', 'service',
                'insertBundleItemsRepair', $values);
        return true;
    }

    public function getPurchasedBundle($args)
    {
        $sid = $args ['sid'];
        $dql = "SELECT a.service_bundle_id , b.bundle_id , b.bundle_name , c.shop_id
                FROM ZSELEX_Entity_ServiceBundle a
                JOIN a.bundle b
                JOIN a.shop c
                WHERE a.service_bundle_id = ?1";
        // echo $dql;

        $query  = $this->_em->createQuery($dql);
        $query->setParameter(1, $sid);
        $result = $query->getOneOrNullResult();
        // echo "<pre>"; print_r($result); echo "</pre>";

        return $result; // hydrate result to array
    }

    public function reactivateDemoFromShopListing($args)
    {
        error_reporting(0);
        $repo    = $this->_em->getRepository('ZSELEX_Entity_Shop');
        $shop_id = $args ['shop_id'];
        $days    = $args ['days'];
        $repo->deleteEntity(null, 'ZSELEX_Entity_ServiceShop',
            array(
            'a.shop' => $shop_id
        ));
        $query   = $this->_em->createQuery('SELECT COUNT(a.service_bundle_id) FROM ZSELEX_Entity_ServiceBundle a '.'WHERE a.shop=:shop_id');
        $query->setParameter('shop_id', $shop_id);
        $count   = $query->getSingleScalarResult();

        // echo "count : " . $count; exit;

        if ($count) {
            // $this->updateDemo($args=array(''));
            $get = $repo->get(array(
                'entity' => 'ZSELEX_Entity_ServiceBundle',
                'fields' => array(
                    'a.service_bundle_id',
                    'b.bundle_id',
                    'b.demoperiod'
                ),
                'joins' => array(
                    'LEFT JOIN a.bundle b'
                ),
                'where' => array(
                    'a.shop' => $shop_id,
                    'b.demo' => 1,
                    'a.bundle_type' => 'main'
                )
            ));
            // echo "<pre>"; print_r($get); echo "</pre>"; exit;
            if (isset($days) && $days > 0) {
                $demo_period = $days;
            } else {
                $demo_period = $get ['demoperiod'];
            }

            $getAll  = $repo->getAll(array(
                'entity' => 'ZSELEX_Entity_ServiceBundle',
                'fields' => array(
                    'a.service_bundle_id',
                    'a.quantity',
                    'b.bundle_id',
                    'b.demoperiod',
                    'b.bundle_type'
                ),
                'joins' => array(
                    'LEFT JOIN a.bundle b'
                ),
                'where' => array(
                    'a.shop' => $shop_id,
                    'b.demo' => 1
                )
            ));
            $bundles = $getAll;
            // echo "<pre>"; print_r($bundles); echo "</pre>"; exit;
            // $demo_period = $get['demoperiod'];
            // echo $demo_period; exit;
            foreach ($bundles as $val) {
                $this->updateDemo(array(
                    'shop_id' => $shop_id,
                    'sid' => $val ['service_bundle_id'],
                    'bundle_id' => $val ['bundle_id'],
                    'demo_period' => $demo_period,
                    'bundle_type' => $val ['bundle_type'],
                    'quantity' => $val ['quantity']
                ));
            }
            return true;
        }

        return false;
    }

    public function getServiceBundles($bundle_args)
    {
        // echo "<pre>"; print_r($args1); echo "</pre>";
        $args ['entity']   = "ZSELEX_Entity_Bundle";
        $args ['fields']   = array(
            'a.bundle_id',
            'a.bundle_name',
            'a.bundle_type',
            'a.type',
            'a.bundle_price',
            'a.calculated_price',
            'a.bundle_description',
            'a.content',
            'a.sort_order'
        );
        $args ['orderby']  = $bundle_args ['orderby'];
        $args ['like']     = $bundle_args ['like'];
        $args ['paginate'] = true;
        // $args['print'] = true;
        // echo "<pre>"; print_r($args); echo "</pre>";
        $items             = $this->getAll($args);
        return $items;
    }

    /**
     * Create a new bundle
     * 
     * @param array $args
     * @return boolean
     */
    public function createBundle($args)
    {
        try {
            $item = ZSELEX_Util::purifyHtml($args);

            $bundle = new ZSELEX_Entity_Bundle ();
            $bundle->setBundle_name($item ['bundle_name']);
            $bundle->setType($item ['type']);
            $bundle->setBundle_price($item ['bundle_price']);
            $bundle->setCalculated_price($item ['calculated_price']);
            $bundle->setBundle_description(stripslashes($item ['bundle_description']));
            $bundle->setBundle_type($item ['bundle_type']);
            $bundle->setDemo($item ['demo']);
            $bundle->setDemoperiod($item ['demoperiod']);
            $bundle->setStatus($item ['status']);
            $bundle->setIs_free($item ['is_free']);

            $this->_em->persist($bundle);
            $this->_em->flush();

            $InsertId = $bundle->getBundle_id();
            $result   = $InsertId;
            return $result;
        } catch (\Exception $e) {
            echo 'Message: '.$e->getMessage().'<br>';
            // echo $query->getSQL();
            exit();
        }
    }

    public function createBundleItems($args)
    {
        try {
            $item = ZSELEX_Util::purifyHtml($args);

            $bundle = $this->_em->find('ZSELEX_Entity_Bundle',
                $item ['bundle_id']);
            $plugin = $this->_em->find('ZSELEX_Entity_Plugin',
                $item ['plugin_id']);

            $bundleItem = new ZSELEX_Entity_BundleItem ();
            $bundleItem->setBundle($bundle);
            $bundleItem->setServicetype($item ['servicetype']);
            $bundleItem->setPlugin($plugin);
            $bundleItem->setService_name($item ['service_name']);
            $bundleItem->setPrice($item ['price']);
            $bundleItem->setQty($item ['qty']);
            $bundleItem->setQty_based($item ['qty_based']);

            $this->_em->persist($bundleItem);
            $this->_em->flush();

            $InsertId = $bundleItem->getId();
            $result   = $InsertId;
            return $result;
        } catch (\Exception $e) {
            echo 'Message Error : '.$e->getMessage().'<br>';
            echo $query->getSQL().'<br>';
            exit();
        }
    }

    public function getExistingBundle($args)
    {

        // echo "<pre>"; print_r($args); echo "</pre>";
        $shop_id = $args ['shop_id'];
        $dql     = "SELECT a.demo_id , a.bundle_type
            FROM ZSELEX_Entity_ServiceDemo a
            WHERE a.top_bundle=1 AND a.shop=$shop_id AND a.bundle_type='main'";
        // echo $dql;
        $query   = $this->_em->createQuery($dql);
        // $query->setParameter(1, 1);
        // $query->setParameter(2, $args['shop_id']);
        // $query->setParameter(3, 'main');
        // $result = $query->getOneOrNullResult(2);
        $result  = $query->getArrayResult();
        // echo "<pre>"; print_r($result); echo "</pre>";

        return $result;
    }

    public function updateShopBundlesWithLatest($args)
    {
        set_time_limit(0);
        $shops = explode(',', $args ['shops']);
        // echo "<pre>"; print_r($shops); echo "</pre>"; exit;
        // echo $shops; exit;
        // $shopIds = explode(',', $shops);
        $repo  = $this->_em->getRepository('ZSELEX_Entity_Bundle');
        $total = 0;

        foreach ($shops as $sid) {
            $shop_id = $sid;
            // $days = $args['days'];
            $repo->deleteEntity(null, 'ZSELEX_Entity_ServiceShop',
                array(
                'a.shop' => $shop_id
            ));
            $query   = $this->_em->createQuery('SELECT COUNT(a.service_bundle_id) FROM ZSELEX_Entity_ServiceBundle a '.'WHERE a.shop=:shop_id');
            $query->setParameter('shop_id', $shop_id);
            $count   = $query->getSingleScalarResult();

            // echo "count : " . $count; exit;

            if ($count) {
                // $this->updateDemo($args=array(''));
                $get = $repo->get(array(
                    'entity' => 'ZSELEX_Entity_ServiceBundle',
                    'fields' => array(
                        'a.service_bundle_id',
                        'b.bundle_id',
                        'a.timer_days',
                        'a.timer_date',
                        'a.service_status'
                    ),
                    'joins' => array(
                        'LEFT JOIN a.bundle b'
                    ),
                    'where' => array(
                        'a.shop' => $shop_id,
                        // 'b.demo' => 1,
                        'a.bundle_type' => 'main'
                    )
                ));
                // echo "<pre>"; print_r($get); echo "</pre>"; exit;

                $timer_days = $get ['timer_days'];
                $timer_date = $get ['timer_date'];

                $getAll  = $repo->getAll(array(
                    'entity' => 'ZSELEX_Entity_ServiceBundle',
                    'fields' => array(
                        'a.service_bundle_id',
                        'a.quantity',
                        'b.bundle_id',
                        'b.bundle_type',
                        'a.timer_date',
                        'a.service_status',
                        'a.quantity'
                    ),
                    'joins' => array(
                        'LEFT JOIN a.bundle b'
                    ),
                    'where' => array(
                        'a.shop' => $shop_id
                    )
                ));
                $bundles = $getAll;
                // echo "<pre>"; print_r($bundles); echo "</pre>"; exit;
                // $demo_period = $get['demoperiod'];
                // echo $demo_period; exit;
                foreach ($bundles as $val) {
                    $this->updateBundleItemsDemo($val ['bundle_id'], $shop_id,
                        $timer_days, $timer_date, $val ['bundle_type'],
                        $val ['service_status'], $val ['quantity']);
                }
                $total ++;
            }
        }

        //return;
        return $total;
    }
}
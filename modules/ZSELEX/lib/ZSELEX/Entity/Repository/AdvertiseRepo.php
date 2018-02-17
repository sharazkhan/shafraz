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
class ZSELEX_Entity_Repository_AdvertiseRepo extends ZSELEX_Entity_Repository_General
{

    public function getAdShops($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>";
        try {

            // $setParams = array();
            $setParams = $args ['setParams'];
            // echo "<pre>"; print_r($setParams); echo "</pre>"; exit;
            $append    = $args ['append'];
            // echo $append . '<br><br>';
            $offset    = $args ['offset'];

            $branch_id = $args ['branch_id'];
            if ($branch_id) {
                $joins                   = " INNER JOIN zselex_shop_to_branch brach ON brach.shop_id=u.shop_id ";
                $append .= " AND brach.branch_id=:branch_id";
                $setParams ['branch_id'] = DataUtil::formatForStore($branch_id);
            }

            $rsm = new ORM\Query\ResultSetMapping ();

            $rsm->addEntityResult('ZSELEX_Entity_Advertise', 'u');
            $rsm->addFieldResult('u', 'advertise_id', 'advertise_id');
            $rsm->addFieldResult('u', 'advertise_type', 'advertise_type');
            $rsm->addFieldResult('u', 'level', 'level');

            $rsm->addMetaResult('u', 'shop_id', 'shop_id');
            $rsm->addMetaResult('u', 'city_id', 'city_id');
            $rsm->addMetaResult('u', 'area_id', 'area_id');
            $rsm->addMetaResult('u', 'shop_name', 'shop_name');
            $rsm->addMetaResult('u', 'adprice_id', 'adprice_id');
            $rsm->addMetaResult('u', 'configured', 'configured');
            $rsm->addMetaResult('u', 'shoptype', 'shoptype');
            $rsm->addMetaResult('u', 'advertise_sel_prods',
                'advertise_sel_prods');
            $today = date("Y-m-d");

            $adCount = $this->getAdCount($args);
            if (!$adCount) {
                return array();
            }
            // $adCount = 0;
            // echo $adCount . '<br>';
            // $randLim = rand(1, $adCount);
            // echo $randLim . '<br>';
            $limits = $this->getRandLimit($count  = $adCount, $end    = $offset);
            // echo "<pre>"; print_r($append); echo "</pre>";
            $dql    = "SELECT DISTINCT a.shop_name , a.advertise_sel_prods, u.advertise_id ,  u.advertise_type , b.adprice_id , u.shop_id , ms.configured , ms.shoptype
             FROM zselex_advertise u 
             INNER JOIN zselex_minishop ms ON ms.shop_id=u.shop_id
             INNER JOIN zselex_serviceshop sv ON sv.shop_id=u.shop_id
             INNER JOIN zselex_advertise_price b ON b.adprice_id=u.adprice_id
             INNER JOIN zselex_shop a ON a.shop_id=u.shop_id
             $joins
             WHERE 
             a.status=:status 
                 ".$append."
              AND exists (SELECT 1
                 FROM zselex_products p
                WHERE p.shop_id = u.shop_id AND  p.prd_status=1)
             AND sv.type='createad'
             AND ((DATEDIFF('$today' , sv.timer_date) <= sv.timer_days) OR sv.service_status=3)

             AND (u.startdate<=CURDATE() OR u.startdate =0 OR UNIX_TIMESTAMP(u.startdate)=0 OR u.startdate IS NULL)
             AND (u.enddate>=CURDATE() OR u.enddate =0 OR UNIX_TIMESTAMP(u.enddate)=0 OR u.enddate IS NULL)
             AND u.advertise_type='productAd' 
         
             LIMIT $limits[start] , $limits[end]";

            // FROM zselex_advertise u FORCE INDEX(shop_id)

            /*
             * $user_id = UserUtil::getVar('uid');
             * if ($user_id == 122) {
             * echo "shops : " . $dql . '<br><br>';
             * }
             */

            // echo $dql.'<br><br>';
            // echo $dql . '<br><br>';
            // LIMIT 0 , $offset
            // INNER JOIN zselex_products d ON d.shop_id=u.shop_id
            // ORDER BY RAND()
            // GROUP BY a.shop_id
            // HAVING COUNT(d.product_id) > 0
            // AND (u.maxviews > u.totalviews OR u.maxviews = -1) AND (u.maxclicks > u.totalclicks OR u.maxclicks = -1)

            $query                = $this->_em->createNativeQuery($dql, $rsm);
            // $query->useResultCache(true);
            $setParams ['status'] = 1;
            if (isset($setParams) && !empty($setParams)) {
                $query->setParameters($setParams);
            }

            // echo $query->getSQL() . '<br><br>';
            // $result = $query->getResult(2);
            $result = $query->getArrayResult();
            // echo "<pre>"; print_r($result); echo "</pre>";

            return $result;
        } catch (\Exception $e) {
            // echo 'Message: ' . $e->getMessage();
            // exit;
        }
    }

    public function getAdShops1($args)
    {
        $shops   = array();
        $offset  = $args ['offset'];
        $adCount = $this->getAdCount($args);
        // echo $adCount . '<br>';

        if ($adCount) {
            $limits   = $this->randNumbers($min      = 0, $max      = $adCount - 1,
                $quantity = $offset);
            // echo "<pre>"; print_r($limits); echo "</pre><br>";
            // exit;

            foreach ($limits as $num) {
                $shops [] = $this->getEachAdShop($args, $num);
            }
        }

        // echo "<pre>"; print_r($shops); echo "</pre>"; exit;
        return $shops;
    }

    function getRandLimit($count, $end)
    {
        // $count = 1;
        $rand = mt_rand(0, $count);
        // $end = 2;

        $next = $rand + $end;
        // echo $next . '<br>';
        if ($next > $count) {
            $rand = $rand - $end;
            if ($rand < 0) {
                $rand = 0;
            }
        }

        return array(
            'start' => $rand,
            'end' => $end
        );
    }

    public function getEachAdShop($args, $num)
    {
        // echo "<pre>"; print_r($args); echo "</pre>";
        try {

            // $setParams = array();
            $setParams = $args ['setParams'];
            // echo "<pre>"; print_r($setParams); echo "</pre>"; exit;
            $append    = $args ['append'];
            // echo $append . '<br><br>';
            $offset    = $args ['offset'];
            $rsm       = new ORM\Query\ResultSetMapping ();

            $rsm->addEntityResult('ZSELEX_Entity_Shop', 'a');
            $rsm->addFieldResult('a', 'shop_id', 'shop_id');
            $rsm->addMetaResult('a', 'advertise_id', 'advertise_id');
            $rsm->addMetaResult('a', 'advertise_type', 'advertise_type');
            $rsm->addMetaResult('a', 'level', 'level');

            $rsm->addMetaResult('a', 'city_id', 'city_id');
            $rsm->addMetaResult('a', 'area_id', 'area_id');
            $rsm->addMetaResult('a', 'shop_name', 'shop_name');
            $rsm->addMetaResult('a', 'adprice_id', 'adprice_id');
            $rsm->addMetaResult('a', 'configured', 'configured');
            $rsm->addMetaResult('a', 'shoptype', 'shoptype');
            $today = date("Y-m-d");

            // $adCount = $this->getAdCount($args);
            // echo $adCount . '<br>';
            // $randLim = mt_rand(0, $adCount - 1);
            // echo $randLim . '<br>';

            $dql = "SELECT DISTINCT a.shop_name , u.advertise_id ,  u.advertise_type , b.adprice_id , u.shop_id , ms.configured , ms.shoptype
            FROM zselex_advertise u FORCE INDEX(shop_id)
             INNER JOIN zselex_minishop ms ON ms.shop_id=u.shop_id
             INNER JOIN zselex_serviceshop sv ON sv.shop_id=u.shop_id
             INNER JOIN zselex_advertise_price b ON b.adprice_id=u.adprice_id
             INNER JOIN zselex_shop a ON a.shop_id=u.shop_id
             WHERE a.status=:status 
              ".$append."
            AND sv.type='createad'
            AND ((DATEDIFF('$today' , sv.timer_date) <= sv.timer_days) OR sv.service_status=3)
           
            AND (u.maxviews > u.totalviews OR u.maxviews = -1)
            AND (u.maxclicks > u.totalclicks OR u.maxclicks = -1)
            AND (u.startdate<=CURDATE() OR u.startdate =0 OR UNIX_TIMESTAMP(u.startdate)=0 OR u.startdate IS NULL)
            AND (u.enddate>=CURDATE() OR u.enddate =0 OR UNIX_TIMESTAMP(u.enddate)=0 OR u.enddate IS NULL)
            AND u.advertise_type='productAd' 
            LIMIT $num , 1
            
            ";
            // echo $dql . '<br><br>';

            $query                = $this->_em->createNativeQuery($dql, $rsm);
            $query->useResultCache(true);
            $setParams ['status'] = 1;
            if (isset($setParams) && !empty($setParams)) {
                $query->setParameters($setParams);
            }

            // echo $query->getSQL() . '<br><br>';
            $result = $query->getOneOrNullResult(2);
            // $result = $query->getArrayResult();
            // echo "<pre>"; print_r($result); echo "</pre>";

            return $result;
        } catch (\Exception $e) {
            echo 'Message: '.$e->getMessage();
            // exit;
        }
    }

    function randNumbers($min, $max, $quantity)
    {
        $numbers = range($min, $max);
        shuffle($numbers);
        return array_slice($numbers, 0, $quantity);
    }

    public function getAdCount($args)
    {
        try {

            // $setParams = array();
            $setParams = $args ['setParams'];
            // echo "<pre>"; print_r($args); echo "</pre>";
            $append    = $args ['append'];
            $offset    = $args ['offset'];
            $branch_id = $args ['branch_id'];
            if ($branch_id) {
                $joins                   = " INNER JOIN zselex_shop_to_branch brach ON brach.shop_id=u.shop_id ";
                $append .= " AND brach.branch_id=:branch_id";
                $setParams ['branch_id'] = DataUtil::formatForStore($branch_id);
            }

            $rsm = new ORM\Query\ResultSetMapping ();

            $rsm->addEntityResult('ZSELEX_Entity_Advertise', 'u');
            $rsm->addScalarResult('COUNT(*)', 'count');
            $today = date("Y-m-d");

            $dql = "SELECT COUNT(*)
            FROM zselex_advertise u
            INNER JOIN zselex_serviceshop sv ON sv.shop_id=u.shop_id
            INNER JOIN zselex_shop a ON a.shop_id=u.shop_id
            INNER JOIN zselex_minishop ms ON ms.shop_id=u.shop_id
            INNER JOIN zselex_advertise_price b ON b.adprice_id=u.adprice_id
            ".$joins."
            WHERE 
              a.status=:status 
               ".$append."
            AND exists (SELECT 1
                 FROM zselex_products p
                WHERE p.shop_id = u.shop_id AND  p.prd_status=1)
            AND sv.type='createad'
            AND ((DATEDIFF('$today' , sv.timer_date) <= sv.timer_days) OR sv.service_status=3)
           
            AND (u.maxviews > u.totalviews OR u.maxviews = -1)
            AND (u.maxclicks > u.totalclicks OR u.maxclicks = -1)
            AND (u.startdate<=CURDATE() OR u.startdate =0 OR UNIX_TIMESTAMP(u.startdate)=0 OR u.startdate IS NULL)
            AND (u.enddate>=CURDATE() OR u.enddate =0 OR UNIX_TIMESTAMP(u.enddate)=0 OR u.enddate IS NULL)
            AND u.advertise_type='productAd' 
           
            ";

            /*
             * $user_id = UserUtil::getVar('uid');
             * if ($user_id == 122) {
             * echo "shopCount : " . $dql . '<br><br>';
             * }
             */

            $query                = $this->_em->createNativeQuery($dql, $rsm);
            $query->useResultCache(true);
            $setParams ['status'] = 1;
            if (isset($setParams) && !empty($setParams)) {
                $query->setParameters($setParams);
            }

            $result = $query->getOneOrNullResult(2);

            return $result ['count'];
        } catch (\Exception $e) {
            echo 'Message: '.$e->getMessage();
            // exit;
        }
    }

    public function createAdvertisePrice($args)
    {
        try {
            $item = ZSELEX_Util::purifyHtml($args);

            $adPrice = new ZSELEX_Entity_AdvertisePrice ();
            $adPrice->setName($item ['name']);
            $adPrice->setIdentifier($item ['identifier']);
            $adPrice->setPricetype($item ['pricetype']);
            $adPrice->setPrice($item ['price']);
            $adPrice->setDescription($item ['description']);
            $adPrice->setStatus($item ['status']);

            $this->_em->persist($adPrice);
            $this->_em->flush();

            $InsertId = $adPrice->getAdprice_id();
            $result   = $InsertId;
            return $result;
        } catch (\Exception $e) {
            echo 'Message: '.$e->getMessage().'<br>';
            // echo $query->getSQL();
            exit();
        }
    }

    public function createAd($args)
    {
        try {
            unset($args ['ad_level']);
            $item    = ZSELEX_Util::purifyHtml($args);
            // echo "<pre>"; print_r($item); echo "</pre>"; exit;
            $adPrice = $this->_em->find('ZSELEX_Entity_AdvertisePrice',
                $item ['adprice_id']);
            $shop    = $this->_em->find('ZSELEX_Entity_Shop', $item ['shop_id']);

            $ad = new ZSELEX_Entity_Advertise ();
            $ad->setName($item ['name']);
            $ad->setAdprice($adPrice);
            $ad->setAdvertise_type($item ['advertise_type']);
            $ad->setLevel($item ['level']);
            $ad->setShop($shop);

            if ($item ['country_id'] > 0) {
                $country = $this->_em->find('ZSELEX_Entity_Country',
                    $item ['country_id']);
            } else {
                $country = null;
            }
            $ad->setCountry($country);

            // $item['region_id'] = 8;
            if ($item ['region_id'] > 0) {
                $region = $this->_em->find('ZSELEX_Entity_Region',
                    $item ['region_id']);
            } else {
                $region = null;
            }
            $ad->setRegion($region);

            // $item['city_id'] = 23;
            if ($item ['city_id'] > 0) {
                $city = $this->_em->find('ZSELEX_Entity_City', $item ['city_id']);
            } else {
                $city = null;
            }
            $ad->setCity($city);

            $ad->setStartdate(null);
            $ad->setEnddate(null);
            $this->_em->persist($ad);
            $this->_em->flush();

            $InsertId = $ad->getAdvertise_id();
            $result   = $InsertId;
            return $result;
        } catch (\Exception $e) {
            echo 'Error Message: '.$e->getMessage().'<br>';
            // echo $query->getSQL();
            exit();
        }
    }
}
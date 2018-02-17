<?php

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Repository class for DQL calls
 */
class ZSELEX_Entity_Repository_EventRepository extends ZSELEX_Entity_Repository_General
{

    public function getSpecialBlockEvents($args)
    {
        $result = array();
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        // $setParams = array();
        try {
            $setParams = $args ['setParams'];
            // echo "<pre>"; print_r($setParams); echo "</pre>"; exit;
            $append    = $args ['append'];
            $offset    = $args ['offset'];
            $joins     = $args ['joins'];
            $rsm       = new ORM\Query\ResultSetMapping();

            $rsm->addEntityResult('ZSELEX_Entity_Event', 'u');
            $rsm->addFieldResult('u', 'shop_event_id', 'shop_event_id');
            $rsm->addFieldResult('u', 'shop_event_name', 'shop_event_name');
            $rsm->addFieldResult('u', 'shop_event_shortdescription',
                'shop_event_shortdescription');
            $rsm->addFieldResult('u', 'shop_event_description',
                'shop_event_description');
            $rsm->addFieldResult('u', 'shop_event_keywords',
                'shop_event_keywords');
            $rsm->addFieldResult('u', 'news_article_id', 'news_article_id');
            $rsm->addFieldResult('u', 'event_image', 'event_image');
            $rsm->addFieldResult('u', 'event_doc', 'event_doc');
            $rsm->addFieldResult('u', 'product_id', 'product_id');
            $rsm->addFieldResult('u', 'showfrom', 'showfrom');
            $rsm->addFieldResult('u', 'price', 'price');
            $rsm->addFieldResult('u', 'event_link', 'event_link');
            $rsm->addFieldResult('u', 'open_new', 'open_new');
            $rsm->addFieldResult('u', 'call_link_directly', 'call_link_directly');

            $rsm->addMetaResult('u', 'shop_id', 'shop_id');
            $rsm->addMetaResult('u', 'shoptype', 'shoptype');
            $today = date("Y-m-d");
            // $rsm->addMetaResult('u', 'product_id', 'product_id');

            $count = $this->getSpecialBlockEventsCount($args);
            if (!$count) {
                return array();
            }
            $limits = ZSELEX_Util::getRandLimit($count, $end    = $offset);
            $dql    = "SELECT a.shop_id , u.shop_event_id , c.shoptype , u.event_link, u.open_new,u.call_link_directly,
                        u.shop_event_name , u.shop_event_shortdescription , u.shop_event_description , u.shop_event_keywords , u.news_article_id , u.event_image , u.event_doc , u.product_id , u.showfrom , u.price
                        FROM zselex_shop_events u
                        INNER JOIN zselex_shop a ON a.shop_id=u.shop_id
                        INNER JOIN zselex_serviceshop sv ON sv.shop_id=u.shop_id
                        $joins
                        LEFT JOIN zselex_minishop c ON c.shop_id=u.shop_id
                        WHERE a.shop_id IS NOT NULL
                        AND a.status=:status
                        AND u.status=:status
                        AND sv.type='eventservice'
                        AND ((DATEDIFF('$today' , sv.timer_date) <= sv.timer_days) OR sv.service_status=3)
                        $append
                        AND a.shop_id = u.shop_id
                        AND (u.shop_event_startdate >=CURDATE() OR u.shop_event_startdate <=CURDATE()) AND u.shop_event_enddate >=CURDATE() 
                        AND (u.activation_date<=CURDATE() OR UNIX_TIMESTAMP(u.activation_date) = 0 OR u.activation_date IS NULL) 
                       
                        LIMIT $limits[start] , $limits[end]";
            // ORDER BY RAND()
            // LIMIT 0 , $offset
            // echo $dql;

            $query                = $this->_em->createNativeQuery($dql, $rsm);
            $query->useResultCache(true);
            $setParams ['status'] = 1;
            $query->setParameters($setParams);
            // echo $query->getSQL();
            // $result = $query->getResult(2);

            $result = $query->getArrayResult();
            // echo "<pre>"; print_r($result); echo "</pre>";
        } catch (\Exception $e) {
            echo 'Message: '.$e->getMessage().'<br>';
            echo $query->getSQL();
            exit();
        }

        // echo "<pre>"; print_r($result); echo "</pre>";

        return $result;
    }

    public function getSpecialBlockEventsCount($args)
    {
        try {

            // $setParams = array();
            $setParams = $args ['setParams'];
            // echo "<pre>"; print_r($setParams); echo "</pre>"; exit;
            $append    = $args ['append'];
            $offset    = $args ['offset'];
            $joins     = $args ['joins'];
            $rsm       = new ORM\Query\ResultSetMapping();

            $rsm->addEntityResult('ZSELEX_Entity_Advertise', 'u');
            $rsm->addScalarResult('COUNT(*)', 'count');
            $today = date("Y-m-d");

            $dql = "SELECT COUNT(*)
                        FROM zselex_shop_events u
                        INNER JOIN zselex_shop a ON a.shop_id=u.shop_id
                        INNER JOIN zselex_serviceshop sv ON sv.shop_id=u.shop_id
                        $joins
                        LEFT JOIN zselex_minishop c ON c.shop_id=u.shop_id
                        WHERE a.shop_id IS NOT NULL
                        AND a.status=:status
                        AND u.status=:status
                        AND sv.type='eventservice'
                        AND ((DATEDIFF('$today' , sv.timer_date) <= sv.timer_days) OR sv.service_status=3)
                        $append
                        AND a.shop_id = u.shop_id
                        AND (u.shop_event_startdate >=CURDATE() OR u.shop_event_startdate <=CURDATE()) AND u.shop_event_enddate >=CURDATE() 
                        AND (u.activation_date<=CURDATE() OR UNIX_TIMESTAMP(u.activation_date) = 0 OR u.activation_date IS NULL) 
                        ";

            // echo $dql . '<br><br>';

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

    public function getUpcomingEventShops($args)
    {
        try {
            $append          = $args ['append'];
            $limit           = $args ['limit'];
            $showAll         = $args ['show_all'];
            $setParams       = $args ['setParams'];
            $upcommingEvents = $args ['upcommingEvents'];
            $rsm             = new ORM\Query\ResultSetMapping();
            $rsm->addEntityResult('ZSELEX_Entity_Shop', 'a');
            $rsm->addFieldResult('a', 'shop_id', 'shop_id');
            $limitQuery      = '';
            if ($limit > 0) {
                $limitQuery = " LIMIT 0 , $limit ";
            }
            if ($showAll) {
                $orderRand = "";
            } else {
                $orderRand = " ORDER BY RAND() ";
            }

            $shopId1Qry = '';
            if ($upcommingEvents == true) {
                $shopId1Qry = " AND a.shop_id > 1 ";
            }

            /*
             * $dql = "SELECT a.shop_id
             * FROM zselex_shop a
             * INNER JOIN zselex_shop_events b ON b.shop_id=a.shop_id
             * WHERE a.shop_id IS NOT NULL AND b.status=1 AND a.shop_id > 1 AND a.status=1 " . $append . " $orderRand $limitQuery";
             */
            $dql   = "SELECT a.shop_id
              FROM zselex_shop a
              INNER JOIN zselex_shop_events b ON b.shop_id=a.shop_id
              AND (b.shop_event_startdate >=CURDATE() OR b.shop_event_startdate <=CURDATE()) AND b.shop_event_enddate >=CURDATE() 
              AND (b.activation_date<=CURDATE() OR UNIX_TIMESTAMP(b.activation_date) = 0 OR b.activation_date IS NULL) 
              WHERE a.shop_id IS NOT NULL AND b.status=1 $shopId1Qry AND a.status=1 
              ".$append." $orderRand $limitQuery";
            // echo $dql;
            $query = $this->_em->createNativeQuery($dql, $rsm);
            if (isset($setParams) && !empty($setParams)) {
                $query->setParameters($setParams);
            }
            // $query->useResultCache(true);
            $result = $query->getArrayResult();
            // echo "<pre>"; print_r($result); echo "</pre>";
            return $result;
        } catch (\Exception $e) {
            echo 'Message: '.$e->getMessage().'<br>';
            echo $query->getSQL();
            // exit;
        }
    }

    public function getEventDates($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>";
        $result = array();
        try {
            $shopquery = $args ['shopquery'];
            $limit     = $args ['limit'];
            $dql       = "SELECT MIN( a.shop_event_startdate ) as mindate , MAX( a.shop_event_enddate ) as maxdate
                       FROM ZSELEX_Entity_Event a
                       WHERE a.shop_event_id IS NOT NULL AND (a.shop_event_startdate != '' OR a.shop_event_startdate IS NOT NULL) AND (a.shop_event_enddate != '' OR a.shop_event_enddate IS NOT NULL)  AND a.status='1' "." ".$shopquery;
            // echo $dql;
            $query     = $this->_em->createQuery($dql);
            if (isset($limit) && $limit > 0) {
                $query->setFirstResult(0);
                $query->setMaxResults($limit);
            }
            $query->useResultCache(true);
            // echo $query->getSQL(); exit;
            $result = $query->getOneOrNullResult(2);
            // echo "<pre>"; print_r($result); echo "</pre>";
            return $result;
        } catch (\Exception $e) {
            // echo 'Message: ' . $e->getMessage() . '<br>';
            // echo $query->getSQL();
            // exit;
        }
    }

    public function getEventBetweenDates($args)
    {
        $result = array();
        try {
            $d       = $args ['d'];
            $shopsql = $args ['shopsql'];
            $dql     = "SELECT a.shop_event_id , b.shop_id , a.shop_event_name , a.shop_event_shortdescription , a.shop_event_description , a.shop_event_startdate , a.shop_event_starthour , a.shop_event_startminute , a.shop_event_enddate , a.shop_event_endhour , a.shop_event_endminute,a.price,a.email,a.phone,a.shop_event_venue,
                         c.aff_id , c.aff_image
                         FROM ZSELEX_Entity_Event a 
                         LEFT JOIN a.shop b
                         LEFT JOIN b.aff_id c
                         WHERE '".$d."' >= a.shop_event_startdate AND '".$d."'  <= a.shop_event_enddate  AND (a.activation_date<=CURRENT_DATE() OR a.activation_date IS NULL OR a.activation_date='') "." ".$shopsql;
            // echo $dql . '<br>';
            $query   = $this->_em->createQuery($dql);
            $query->useResultCache(true);
            $result  = $query->getArrayResult();
            // echo "<pre>"; print_r($result); echo "</pre>";
            // return $result;
        } catch (\Exception $e) {
            // echo 'Message: ' . $e->getMessage() . '<br>';
            // echo $query->getSQL();
            // exit;
        }
        return $result;
    }

    public function getMinisiteEvent($args)
    {

        // echo "<pre>"; print_r($args); echo "</pre>";
        $shop_id = $args ['shop_id'];
        try {
            $rsm = new ORM\Query\ResultSetMapping();
            $rsm->addEntityResult('ZSELEX_Entity_Event', 'a');
            $rsm->addFieldResult('a', 'shop_event_id', 'shop_event_id');
            $rsm->addFieldResult('a', 'shop_event_name', 'shop_event_name');
            $rsm->addFieldResult('a', 'shop_event_shortdescription',
                'shop_event_shortdescription');
            $rsm->addFieldResult('a', 'shop_event_description',
                'shop_event_description');
            $rsm->addFieldResult('a', 'news_article_id', 'news_article_id');
            $rsm->addFieldResult('a', 'event_image', 'event_image');
            $rsm->addFieldResult('a', 'event_doc', 'event_doc');
            $rsm->addFieldResult('a', 'product_id', 'product_id');
            $rsm->addFieldResult('a', 'showfrom', 'showfrom');
            $rsm->addFieldResult('a', 'event_link', 'event_link');
            $rsm->addFieldResult('a', 'open_new', 'open_new');
            $rsm->addMetaResult('a', 'call_link_directly', 'call_link_directly');

            $today = date("Y-m-d");


            $dql    = "SELECT a.shop_event_id , a.shop_event_name , a.shop_event_shortdescription , a.shop_event_description , a.news_article_id ,
                         a.event_image , a.event_doc , a.product_id , a.showfrom , a.event_link , a.open_new,a.call_link_directly
                        FROM zselex_shop_events a
                        INNER JOIN zselex_serviceshop sv ON sv.shop_id=a.shop_id
                        WHERE a.shop_id=:shop_id
                        AND a.status=1
                        AND sv.type='eventservice'
                        AND ((DATEDIFF('$today' , sv.timer_date) <= sv.timer_days) OR sv.service_status=3)
                        AND (a.shop_event_startdate >=CURDATE() OR a.shop_event_startdate <=CURDATE()) AND a.shop_event_enddate >=CURDATE() 
                        AND (a.activation_date<=CURDATE() OR UNIX_TIMESTAMP(a.activation_date) = 0 OR a.activation_date IS NULL) 
                        ORDER BY RAND() LIMIT 0 , 2";
            // echo $dql;
            $query  = $this->_em->createNativeQuery($dql, $rsm);
            $query->setParameter('shop_id', $shop_id);
            $query->useResultCache(true);
            $result = $query->getArrayResult();
            // echo "<pre>"; print_r($result); echo "</pre>";

            return $result;
        } catch (\Exception $e) {
            echo 'Message: '.$e->getMessage().'<br>';
            // echo $query->getSQL();
            // exit;
        }
    }

    /**
     * Get single event in event detail view page
     *
     * @param array $args
     *        	int shop_id
     *        	int product_id
     * @return array of event
     */
    public function getViewEvent($args)
    {
        $shop_id     = $args ['shop_id'];
        $event_id    = $args ['event_id'];
        $event_title = $args ['event_title'];
        if (isset($event_id) && !empty($event_id)) {
            $where = " a.shop_event_id=:event ";
        } else {
            $where = " a.event_urltitle=:urltitle ";
        }
        try {
            $rsm = new ORM\Query\ResultSetMapping();
            $rsm->addEntityResult('ZSELEX_Entity_Event', 'a');
            $rsm->addFieldResult('a', 'shop_event_id', 'shop_event_id');
            $rsm->addFieldResult('a', 'event_urltitle', 'event_urltitle');
            $rsm->addFieldResult('a', 'shop_event_name', 'shop_event_name');
            $rsm->addFieldResult('a', 'shop_event_shortdescription',
                'shop_event_shortdescription');
            $rsm->addFieldResult('a', 'shop_event_description',
                'shop_event_description');
            $rsm->addFieldResult('a', 'news_article_id', 'news_article_id');
            $rsm->addFieldResult('a', 'event_image', 'event_image');
            $rsm->addFieldResult('a', 'event_doc', 'event_doc');
            $rsm->addFieldResult('a', 'product_id', 'product_id');
            $rsm->addFieldResult('a', 'showfrom', 'showfrom');
            $rsm->addFieldResult('a', 'shop_event_starthour',
                'shop_event_starthour');
            $rsm->addFieldResult('a', 'shop_event_startminute',
                'shop_event_startminute');
            $rsm->addFieldResult('a', 'shop_event_endhour', 'shop_event_endhour');
            $rsm->addFieldResult('a', 'shop_event_endminute',
                'shop_event_endminute');
            $rsm->addFieldResult('a', 'price', 'price');
            $rsm->addFieldResult('a', 'phone', 'phone');
            $rsm->addFieldResult('a', 'email', 'email');
            $rsm->addFieldResult('a', 'shop_event_venue', 'shop_event_venue');
            $rsm->addFieldResult('a', 'event_link', 'event_link');
            $rsm->addFieldResult('a', 'open_new', 'open_new');
            $rsm->addFieldResult('a', 'call_link_directly', 'call_link_directly');

            $rsm->addMetaResult('a', 'shop_event_startdate',
                'shop_event_startdate');
            $rsm->addMetaResult('a', 'shop_event_enddate', 'shop_event_enddate');
            $rsm->addMetaResult('a', 'shop_id', 'shop_id');
            $rsm->addMetaResult('a', 'shop_name', 'shop_name');

            $dql   = "SELECT a.shop_id ,a.shop_event_id , a.event_urltitle , a.shop_event_name , a.shop_event_shortdescription , a.shop_event_description , a.news_article_id ,
                         a.event_image , a.event_doc , a.product_id , a.showfrom , a.shop_event_startdate,
                         a.shop_event_enddate,a.shop_event_starthour,a.shop_event_startminute,
                         a.shop_event_endhour,a.shop_event_endminute,a.price,a.phone,a.email,a.shop_event_venue,a.event_link,a.open_new,a.call_link_directly,
                         b.shop_name
                        FROM zselex_shop_events a
                        INNER JOIN zselex_shop b ON a.shop_id=b.shop_id
                        WHERE $where
                        AND a.status=1
                        AND (a.shop_event_startdate >=CURDATE() OR a.shop_event_startdate <=CURDATE()) AND a.shop_event_enddate >=CURDATE() 
                        AND (a.activation_date<=CURDATE() OR UNIX_TIMESTAMP(a.activation_date) = 0 OR a.activation_date IS NULL) 
                        ";
            // echo $dql; exit;
            $query = $this->_em->createNativeQuery($dql, $rsm);
            // $query->setParameter('shop', $shop_id);
            // $query->setParameter('event', $event_id);
            if (isset($event_id) && !empty($event_id)) {
                $query->setParameter('event', $event_id);
            } else {
                $query->setParameter('urltitle', $event_title);
            }
            // $query->useResultCache(true);
            $result = $query->getOneOrNullResult(2);
            // echo "<pre>"; print_r($result); echo "</pre>"; exit;
            if (!$result) {
                $oldUrlArr = array(
                    'entity' => 'ZSELEX_Entity_Url',
                    'where' => array(
                        'a.type' => 'event',
                        'a.url' => $event_title
                    )
                );
                $getOldUrl = $this->get($oldUrlArr);
                // echo "<pre>"; print_r($getOldUrl); echo "</pre>"; exit;
                if ($getOldUrl) {
                    $url = pnGetBaseURL().ModUtil::url('ZSELEX', 'user',
                            'viewevent',
                            array(
                            'shop_id' => $getOldUrl ['shop_id'],
                            'eventId' => $getOldUrl ['type_id']
                    ));
                    // echo $url; exit;
                    // $this->redirect($url);
                    System::redirect($url);
                    die();
                }
            }

            return $result;
        } catch (\Exception $e) {
            echo 'Message: '.$e->getMessage().'<br>';
            // echo $query->getSQL();
            // exit;
        }
    }

    public function getViewEvent1($args)
    {
        $shop_id  = $args ['shop_id'];
        $event_id = $args ['event_id'];
        try {
            $rsm = new ORM\Query\ResultSetMapping();
            $rsm->addEntityResult('ZSELEX_Entity_Event', 'a');
            $rsm->addFieldResult('a', 'shop_event_id', 'shop_event_id');
            $rsm->addFieldResult('a', 'shop_event_name', 'shop_event_name');
            $rsm->addFieldResult('a', 'shop_event_shortdescription',
                'shop_event_shortdescription');
            $rsm->addFieldResult('a', 'shop_event_description',
                'shop_event_description');
            $rsm->addFieldResult('a', 'news_article_id', 'news_article_id');
            $rsm->addFieldResult('a', 'event_image', 'event_image');
            $rsm->addFieldResult('a', 'event_doc', 'event_doc');
            $rsm->addFieldResult('a', 'product_id', 'product_id');
            $rsm->addFieldResult('a', 'showfrom', 'showfrom');
            $rsm->addFieldResult('a', 'shop_event_starthour',
                'shop_event_starthour');
            $rsm->addFieldResult('a', 'shop_event_startminute',
                'shop_event_startminute');
            $rsm->addFieldResult('a', 'shop_event_endhour', 'shop_event_endhour');
            $rsm->addFieldResult('a', 'shop_event_endminute',
                'shop_event_endminute');
            $rsm->addFieldResult('a', 'price', 'price');
            $rsm->addFieldResult('a', 'phone', 'phone');
            $rsm->addFieldResult('a', 'email', 'email');
            $rsm->addFieldResult('a', 'shop_event_venue', 'shop_event_venue');
            $rsm->addFieldResult('a', 'event_link', 'event_link');
            $rsm->addFieldResult('a', 'open_new', 'open_new');
            $rsm->addFieldResult('a', 'call_link_directly', 'call_link_directly');

            $rsm->addMetaResult('a', 'shop_event_startdate',
                'shop_event_startdate');
            $rsm->addMetaResult('a', 'shop_event_enddate', 'shop_event_enddate');
            $rsm->addMetaResult('a', 'shop_id', 'shop_id');

            $dql    = "SELECT a.shop_id ,a.shop_event_id , a.shop_event_name , a.shop_event_shortdescription , a.shop_event_description , a.news_article_id ,
                         a.event_image , a.event_doc , a.product_id , a.showfrom , a.shop_event_startdate,
                         a.shop_event_enddate,a.shop_event_starthour,a.shop_event_startminute,
                         a.shop_event_endhour,a.shop_event_endminute,a.price,a.phone,a.email,a.shop_event_venue,a.event_link,a.open_new,a.call_link_directly
                        FROM zselex_shop_events a
                        WHERE a.shop_id=:shop
                        AND a.status=1
                        AND (a.shop_event_startdate >=CURDATE() OR a.shop_event_startdate <=CURDATE()) AND a.shop_event_enddate >=CURDATE() 
                        AND (a.activation_date<=CURDATE() OR UNIX_TIMESTAMP(a.activation_date) = 0 OR a.activation_date IS NULL) 
                        AND a.shop_event_id=:event";
            // echo $dql;
            $query  = $this->_em->createNativeQuery($dql, $rsm);
            $query->setParameter('shop', $shop_id);
            $query->setParameter('event', $event_id);
            // $query->useResultCache(true);
            $result = $query->getOneOrNullResult(2);
            // echo "<pre>"; print_r($result); echo "</pre>";

            return $result;
        } catch (\Exception $e) {
            echo 'Message: '.$e->getMessage().'<br>';
            // echo $query->getSQL();
            // exit;
        }
    }

    public function getNewsArticle($args)
    {
        try {
            $news_id = $args ['news_id'];

            $rsm = new ORM\Query\ResultSetMapping();
            $rsm->addEntityResult('ZSELEX_Entity_Event', 'a');
            $rsm->addFieldResult('a', 'shop_event_id', 'shop_event_id');
            $rsm->addMetaResult('a', 'sid', 'sid');

            $dql    = "SELECT a.shop_event_id , b.sid
                 FROM zselex_shop_events a
                 INNER JOIN news b ON b.sid=a.news_article_id
                 WHERE b.sid=$news_id
                 ";
            $query  = $this->_em->createNativeQuery($dql, $rsm);
            $result = $query->getOneOrNullResult(2);
            return $result;
        } catch (\Exception $e) {
            // echo 'Message: ' . $e->getMessage() . '<br>';
            // echo $query->getSQL();
            // exit;
        }
    }

    public function getProduct($args)
    {
        try {
            $product_id = $args ['product_id'];

            $dql    = "SELECT a.product_id , a.product_name , a.prd_image
                FROM ZSELEX_Entity_Product a
                WHERE a.prd_status=1 AND  a.product_id = :product_id";
            $query  = $this->_em->createQuery($dql);
            $query->setParameter('product_id', $product_id);
            $result = $query->getOneOrNullResult(2);
            // echo "<pre>"; print_r($result); echo "</pre>";
            return $result;
        } catch (\Exception $e) {
            // echo 'Message: ' . $e->getMessage() . '<br>';
            // echo $query->getSQL();
            // exit;
        }
    }

    /**
     * Create a new event
     * 
     * @param type $args - from values
     * @return int - last inserted id
     */
    public function createEvent($args)
    {
        $item      = ZSELEX_Util::purifyHtml($args);
        // echo "<pre>"; print_r($item); echo "</pre>"; exit;
        $shop      = $this->_em->find('ZSELEX_Entity_Shop', $item ['shop']);
        // echo "shop_id : " . $shop; exit;
        // echo new \DateTime($item['shop_event_startdate']); exit;
        $startDate = !empty($item ['shop_event_startdate']) ? date_create($item ['shop_event_startdate'])
                : null;
        $endDate   = !empty($item ['shop_event_enddate']) ? date_create($item ['shop_event_enddate'])
                : null;
        $actDate   = !empty($item ['activation_date']) ? date_create($item ['activation_date'])
                : null;

        $event = new ZSELEX_Entity_Event();
        $event->setShop($shop);
        $event->setShop_event_name($item ['shop_event_name']);
        $event->setEvent_urltitle($item ['event_urltitle']);
        $event->setShop_event_shortdescription($item ['shop_event_shortdescription']);
        $event->setShop_event_description($item ['shop_event_description']);
        $event->setNews_article_id($item ['news_article_id']);
        $event->setShop_event_keywords($item ['shop_event_keywords']);
        $event->setShop_event_startdate($startDate);
        $event->setShop_event_starthour($item ['shop_event_starthour']);
        $event->setShop_event_startminute($item ['shop_event_startminute']);
        $event->setShop_event_enddate($endDate);
        $event->setShop_event_endhour($item ['shop_event_endhour']);
        $event->setShop_event_endminute($item ['shop_event_endminute']);
        $event->setActivation_date($actDate);
        $event->setProduct_id($item ['product_id']);
        $event->setPrice($item ['price']);
        $event->setEvent_link($item ['event_link']);
        $event->setOpen_new($item ['open_new']);
        $event->setCall_link_directly($item ['call_link_directly']);
        $event->setEmail($item ['email']);
        $event->setPhone($item ['phone']);
        $event->setShop_event_venue($item ['shop_event_venue']);
        $event->setStatus($item ['status']);
        $this->_em->persist($event);
        $this->_em->flush();

        $InsertId = $event->getShop_event_id();
        $result   = $InsertId;
        return $result;
    }

    public function createEventDnd($args)
    {
        $item = ZSELEX_Util::purifyHtml($args);
        // echo "<pre>"; print_r($item); echo "</pre>"; exit;
        $shop = $this->_em->find('ZSELEX_Entity_Shop', $item ['shop_id']);

        $event = new ZSELEX_Entity_Event();
        $event->setShop($shop);
        $event->setShop_event_name($item ['shop_event_name']);
        $event->setEvent_urltitle($item ['event_urltitle']);
        $event->setEvent_image($item ['event_image']);
        $event->setImage_height($item ['image_height']);
        $event->setImage_width($item ['image_width']);
        $event->setEvent_doc($item ['event_doc']);
        $event->setShowfrom($item ['showfrom']);
        $event->setStatus($item ['status']);
        $event->setShop_event_venue($shop->getAddress());
        $event->setEmail($shop->getEmail());
        $event->setPhone($shop->getTelephone());
        $this->_em->persist($event);
        $this->_em->flush();

        $InsertId = $event->getShop_event_id();
        $result   = $InsertId;
        return $result;
    }

    public function updateEventDnd($args)
    {
        $fields      = $args ['items'];
        $event_id    = $args ['event_id'];
        $updateEvent = $this->updateEntity(null, 'ZSELEX_Entity_Event', $fields,
            $where       = array(
            'a.shop_event_id' => $event_id
        ));
        return true;
    }

    public function updateEventTemp($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        set_time_limit(0);
        $event_id    = $args ['event_id'];
        $shop_id     = $args ['shop_id'];
        $dates       = $args ['dates'];
        $single      = $args ['single'];
        $batchSize   = 20;
        $i           = 0;
        $todayDate   = date("Y-m-d");
        $deleteEvent = $this->deleteEntity(null, 'ZSELEX_Entity_EventTemp',
            array(
            'a.event' => $event_id
        ));
        foreach ($dates as $date) {
            if ($date >= $todayDate) {
                $eventTmp = new ZSELEX_Entity_EventTemp();
                $event    = $this->_em->find('ZSELEX_Entity_Event', $event_id);
                $eventTmp->setEvent($event);
                $shop     = $this->_em->find('ZSELEX_Entity_Shop', $shop_id);
                $eventTmp->setShop($shop);
                $eventTmp->setEvent_date(date_create($date));
                $this->_em->persist($eventTmp);
                // if (($i % $batchSize) === 0) {
                $this->_em->flush();
                $this->_em->clear();
                // }
                $i ++;
            }
        }
        return true;
    }

    public function updateEventTempTable()
    {
        set_time_limit(0);
        $server  = $_SERVER ['SERVER_NAME'];
        $message = 'Event update script task is completed at the background.Pleas check the events<br><br>';
        // $message .= $this->__('Server') . ' : ' . $server . '<br>';
        // $message .= $this->__('Start Date') . ' : ' . date('Y-m-d h:i:s a', time()) . '<br>';
        $message .= 'Server : '.$server.'<br>';
        $message .= 'Start Date : '.date('Y-m-d h:i:s a', time()).'<br>';

        $getAllEvents = $this->_em->getRepository('ZSELEX_Entity_Event')->getAll(array(
            'entity' => 'ZSELEX_Entity_Event',
            'fields' => array(
                'a.shop_event_id',
                'b.shop_id',
                'a.shop_event_startdate',
                'a.shop_event_enddate',
                'a.status'
            ),
            'joins' => array(
                'JOIN a.shop b'
            )
        ));
        // return $getAllEvents;
        // echo "<pre>"; print_r($getAllEvents); echo "</pre>"; exit;
        foreach ($getAllEvents as $event) {
            /*
             * $deleteEventTmp = $this->_em->getRepository('ZSELEX_Entity_Event')->
             * deleteEntity(null, 'ZSELEX_Entity_EventTemp', array('a.event' => $event['shop_event_id']));
             */
            // if ($event['status']) {
            if ((!empty($event ['shop_event_startdate']) && !empty($event ['shop_event_enddate']))
                && ($event ['shop_event_enddate'] >= $event ['shop_event_startdate'])) {
                $dateRange    = ZSELEX_Util::createDateRangeArray($event ['shop_event_startdate'],
                        $event ['shop_event_enddate']);
                // echo "<pre>"; print_r($dateRange); echo "</pre>"; exit;
                $setEventTemp = $this->_em->getRepository('ZSELEX_Entity_Event')->updateEventTemp(array(
                    'shop_id' => $event ['shop_id'],
                    'event_id' => $event ['shop_event_id'],
                    'dates' => $dateRange,
                    'all' => true
                ));
                // }
            }
        }

        // $message .= $this->__('End Date') . ' : ' . date('Y-m-d h:i:s a', time()) . '<br>';
        $message .= 'End Date : '.date('Y-m-d h:i:s a', time()).'<br>';
        /*
         * $mailer_args = array(
         * 'toaddress' => 'sharazkhanz@gmail.com',
         * 'fromname' => 'ZSELEX',
         * 'subject' => 'Event Update Script',
         * 'body' => $message,
         * 'html' => true
         * );
         * // echo "<pre>"; print_r($mailer_args); echo "</pre>"; exit;
         * $sent = ModUtil::apiFunc('Mailer', 'user', 'sendMessage', $mailer_args);
         */
        $mailer_args = array(
            'toaddress' => 'kim@acta-it.dk',
            'fromname' => 'ZSELEX',
            'subject' => 'Event Update Script',
            'body' => $message,
            'html' => true
        );
        $sent        = ModUtil::apiFunc('Mailer', 'user', 'sendMessage',
                $mailer_args);
        // LogUtil::registerStatus($this->__('Event temperory table updated'));
        // $this->redirect(ModUtil::url('ZSELEX', 'admin', 'modifyconfig'));
        return true;
    }

    /**
     * Get events (upcomming)
     * 
     * @param type $args
     * @return array
     */
    public function getAllEvents($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $result          = array();
        $append          = $args ['append'];
        $setParams       = $args ['setParams'];
        $join            = $args ['join'];
        $start           = $args ['start'];
        $end             = $args ['end'];
        $upcommingEvents = $args ['upcommingEvents'];
        $sql             = '';
        // if ($upcommingEvents) {
        // $sql = " AND b.shop_id > 1 ";
        $sql             = " AND b.shop_id!=1 ";
        // }
        if (!isset($start)) {
            $start = 0;
        }
        if (!isset($end)) {
            $end = 10;
        }

        // $end2 = $start + 10;
        // $date1 = date('Y-m-d', strtotime("+$start days"));
        // $date2 = date('Y-m-d', strtotime("+$end2 days"));

        try {
            $rsm = new ORM\Query\ResultSetMapping();
            $rsm->addEntityResult('ZSELEX_Entity_EventTemp', 'u');
            $rsm->addFieldResult('u', 'id', 'id');
            $rsm->addMetaResult('u', 'event_date', 'event_date');

            $rsm->addMetaResult('u', 'shop_event_id', 'shop_event_id');
            $rsm->addMetaResult('u', 'shop_event_name', 'shop_event_name');
            $rsm->addMetaResult('u', 'shop_event_shortdescription',
                'shop_event_shortdescription');
            $rsm->addMetaResult('u', 'shop_event_description',
                'shop_event_description');
            $rsm->addMetaResult('u', 'news_article_id', 'news_article_id');
            $rsm->addMetaResult('u', 'event_image', 'event_image');
            $rsm->addMetaResult('u', 'event_doc', 'event_doc');
            $rsm->addMetaResult('u', 'product_id', 'product_id');
            $rsm->addMetaResult('u', 'showfrom', 'showfrom');
            $rsm->addMetaResult('u', 'shop_event_starthour',
                'shop_event_starthour');
            $rsm->addMetaResult('u', 'shop_event_startminute',
                'shop_event_startminute');
            $rsm->addMetaResult('u', 'shop_event_endhour', 'shop_event_endhour');
            $rsm->addMetaResult('u', 'shop_event_endminute',
                'shop_event_endminute');
            $rsm->addMetaResult('u', 'price', 'price');
            $rsm->addMetaResult('u', 'phone', 'phone');
            $rsm->addMetaResult('u', 'email', 'email');
            $rsm->addMetaResult('u', 'shop_event_venue', 'shop_event_venue');
            $rsm->addMetaResult('u', 'shop_event_startdate',
                'shop_event_startdate');
            $rsm->addMetaResult('u', 'shop_event_enddate', 'shop_event_enddate');
            $rsm->addMetaResult('u', 'aff_id', 'aff_id');
            $rsm->addMetaResult('u', 'aff_image', 'aff_image');
            $rsm->addMetaResult('u', 'uname', 'uname');
            $rsm->addMetaResult('u', 'shop_id', 'shop_id');
            $rsm->addMetaResult('u', 'event_link', 'event_link');
            $rsm->addMetaResult('u', 'open_new', 'open_new');
            $rsm->addMetaResult('u', 'call_link_directly', 'call_link_directly');
            $rsm->addMetaResult('u', 'contact_name', 'contact_name');
            $rsm->addMetaResult('u', 'shop_name', 'shop_name');
            $today = date("Y-m-d");

            $dql = "SELECT u.id , b.shop_id ,  u.event_date , a.shop_event_id , a.shop_event_name , a.shop_event_shortdescription , a.shop_event_description ,
               a.shop_event_startdate,a.event_link,a.open_new,a.call_link_directly,
              a.shop_event_enddate,a.shop_event_starthour,a.shop_event_startminute,
              a.shop_event_endhour,a.shop_event_endminute,a.price,a.phone,a.email,a.shop_event_venue,a.contact_name,b.shop_name,
              d.aff_id , d.aff_image , f.uname
              FROM zselex_shop_event_temp u FORCE INDEX (event_date)
              INNER JOIN zselex_shop_events a ON a.shop_event_id=u.event_id
              INNER JOIN zselex_shop b ON b.shop_id=a.shop_id
              INNER JOIN zselex_serviceshop sv ON sv.shop_id=u.shop_id
              ".$join."
              LEFT JOIN zselex_shop_affiliation d ON d.aff_id=b.aff_id
              LEFT JOIN zselex_shop_owners e ON e.shop_id=b.shop_id AND e.co_owner=0
              LEFT JOIN users f ON f.uid=e.user_id
              WHERE u.event_date >=CURDATE()
              ".$append."
              AND a.status=1
              AND b.status=1
              AND sv.type='eventservice'
              AND ((DATEDIFF('$today' , sv.timer_date) <= sv.timer_days) OR sv.service_status=3)
              ".$sql."

              AND (a.activation_date<=CURDATE() OR UNIX_TIMESTAMP(a.activation_date) = 0 OR a.activation_date IS NULL)
              
              ORDER BY u.event_date ASC
              LIMIT $start , $end";

            //
            // GROUP BY u.id
            // OR UNIX_TIMESTAMP(a.activation_date) = 0
            // AND (a.shop_event_startdate >=CURDATE() OR a.shop_event_startdate <=CURDATE()) AND a.shop_event_enddate >=CURDATE()
            // echo $dql . '<br>';

            $query = $this->_em->createNativeQuery($dql, $rsm);
            if (isset($setParams) && !empty($setParams)) {
                $query->setParameters($setParams);
            }
            $query->useResultCache(true);
            $result = $query->getArrayResult();
            // echo "<pre>"; print_r($result); echo "</pre>";
            //$result = array();
            return $result;
        } catch (\Exception $e) {
            echo 'Message: '.$e->getMessage().'<br>';
            // echo $query->getSQL();
            // exit;
        }
        return $result;
    }

    /**
     * Get events count (upcomming)
     *
     * @param type $args
     * @return int - count
     */
    public function getAllEventsCount($args)
    {
        try {
            $append    = $args ['append'];
            $setParams = $args ['setParams'];
            $join      = $args ['join'];

            $start    = $args ['start'];
            $end      = $args ['end'];
            $limitQry = '';
            if (isset($start)) {
                // $limitQry = " LIMIT $start , $end";
            }

            $upcommingEvents = $args ['upcommingEvents'];
            $sql             = '';
            // if ($upcommingEvents) {
            $sql             = " AND b.shop_id > 1 ";
            // }

            $rsm = new ORM\Query\ResultSetMapping();
            $rsm->addEntityResult('ZSELEX_Entity_EventTemp', 'u');
            // $rsm->addFieldResult('s', 'shop_id', 'shop_id');
            $rsm->addScalarResult('COUNT(u.id)', 'count');

            $today = date("Y-m-d");
            $dql   = "SELECT COUNT(u.id)
                         FROM zselex_shop_event_temp u
                         INNER JOIN zselex_shop_events a ON a.shop_event_id=u.event_id
                         INNER JOIN zselex_shop b ON b.shop_id=a.shop_id
                         INNER JOIN zselex_serviceshop sv ON sv.shop_id=u.shop_id
                          ".$join."
                          WHERE u.event_date >=CURDATE()
                          AND sv.type='eventservice'
                          AND ((DATEDIFF('$today' , sv.timer_date) <= sv.timer_days) OR sv.service_status=3)
                         ".$append."
                         AND a.status=1
                         ".$sql."
                        
                         AND (a.activation_date<=CURDATE()  OR a.activation_date IS NULL)
                         
                         ".$limitQry;
            // echo $dql;
            // OR UNIX_TIMESTAMP(a.activation_date) = 0
            // AND (a.shop_event_startdate >=CURDATE() OR a.shop_event_startdate <=CURDATE()) AND a.shop_event_enddate >=CURDATE()
            $query = $this->_em->createNativeQuery($dql, $rsm);
            if (isset($setParams) && !empty($setParams)) {
                $query->setParameters($setParams);
            }
            $result = $query->getOneOrNullResult(2);
            return $result ['count'];
        } catch (\Exception $e) {
            echo 'Message: '.$e->getMessage();
            exit();
        }
    }

    public function updateEventUrls()
    {
        $repo = $this->_em->getRepository('ZSELEX_Entity_Event');

        $allEvents = $repo->getAll(array(
            'entity' => 'ZSELEX_Entity_Event',
            'fields' => array(
                'a.shop_event_id',
                'a.shop_event_name'
            )
            )
            // 'joins' => array('JOIN a.shop b')
        );
        foreach ($allEvents as $event) {
            $event_id = $event ['shop_event_id'];
            $urltitle = strtolower($event ['shop_event_name']);
            $urltitle = ZSELEX_Util::cleanTitle($urltitle);

            // $args_url = array('table' => 'zselex_shop_events', 'title' => $urltitle, 'field' => 'event_urltitle');
            // $final_urltitle = ZSELEX_Controller_Admin::increment_url($args_url);

            $urlCount = $repo->getCount2(array(
                'entity' => 'ZSELEX_Entity_Event',
                'field' => 'shop_event_id',
                'where' => "a.event_urltitle=:urltitle AND a.shop_event_id!=".$event_id,
                'setParams' => array(
                    'urltitle' => $urltitle
                )
            ));

            if ($urlCount > 0) {
                $args_url       = array(
                    'title' => $urltitle,
                    'event_id' => $event_id
                );
                $final_urltitle = $repo->increment_event_url($args_url);
            } else {
                $final_urltitle = $urltitle;
            }
            $item       = array(
                'event_urltitle' => $final_urltitle
            );
            $updateUrls = $repo->updateEntity(null, 'ZSELEX_Entity_Event',
                $item,
                array(
                'a.shop_event_id' => $event ['shop_event_id']
            ));
        }
        return true;
    }

    public function increment_event_url($args)
    {
        $title     = $args ['title'];
        $event_id  = $args ['event_id'];
        $statement = Doctrine_Manager::getInstance()->connection();
        $sql       = "SELECT COALESCE( CONCAT( '".$title."', SUBSTRING( MAX( event_urltitle ) , CHAR_LENGTH( '".$title."' ) +1 ) *1 +1 ) , '".$title."' ) event_urltitle
                    FROM zselex_shop_events
                    WHERE event_urltitle REGEXP '$title([0-10001]+)?$' AND shop_event_id!='$event_id'";
        $query     = $statement->execute($sql);
        $result    = $query->fetch();
        $urltitle  = $result ['event_urltitle'];
        return $urltitle;
    }

    /**
     * Gety exclusive events for front end event banser sliders
     *
     * @param array $args
     * @return array
     */
    public function getExclusiveEvents($args)
    {
        $result = array();
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        // $setParams = array();
        try {
            $setParams = $args ['setParams'];
            // echo "<pre>"; print_r($setParams); echo "</pre>"; exit;
            $append    = $args ['append'];
            $offset    = $args ['offset'];
            $joins     = $args ['joins'];
            $rsm       = new ORM\Query\ResultSetMapping();

            $rsm->addEntityResult('ZSELEX_Entity_Event', 'u');
            $rsm->addFieldResult('u', 'shop_event_id', 'shop_event_id');
            $rsm->addFieldResult('u', 'event_image', 'event_image');
            $rsm->addFieldResult('u', 'event_link', 'event_link');
            $rsm->addFieldResult('u', 'open_new', 'open_new');
            $rsm->addFieldResult('u', 'call_link_directly', 'call_link_directly');

            $rsm->addMetaResult('u', 'shop_id', 'shop_id');

            $eventCount = $this->getExclusiveEventsCount($args);
            // echo "count :" . $eventCount;
            // $eventCount = 30;
            if (!$eventCount) {
                return array();
            }

            $limits = ZSELEX_Util::getRandLimit($count  = $eventCount, $end    = 20);
            // echo "<pre>"; print_r($limits); echo "</pre>";
            $today  = date("Y-m-d");
            $dql    = "SELECT DISTINCT a.shop_id ,u.shop_event_id ,u.event_image,u.event_link,u.open_new,u.call_link_directly
                        FROM zselex_shop_events u FORCE INDEX(shop_id)
                        INNER JOIN zselex_serviceshop b ON b.shop_id=u.shop_id 
                        INNER JOIN zselex_shop a ON a.shop_id=u.shop_id
                        $joins
                        WHERE ((DATEDIFF('$today' , b.timer_date) <= b.timer_days) OR b.service_status=3)
                        AND b.type='exclusiveevent'
                        AND a.shop_id IS NOT NULL
                        AND a.status=:status
                        AND u.status=:status
                       ".$append."
                        AND u.exclusive=1 AND u.image_height>=300 AND u.image_width>=900 AND (u.shop_event_startdate >=CURDATE() OR u.shop_event_startdate <=CURDATE()) AND u.shop_event_enddate >=CURDATE() 
                        AND (u.activation_date<=CURDATE() OR UNIX_TIMESTAMP(u.activation_date) = 0 OR u.activation_date IS NULL) 
                       
                        LIMIT $limits[start] , $limits[end]";

            // AND RAND()<=20
            // ORDER BY RAND()
            // LIMIT 0 , 20
            // echo $dql . '<br>';

            $query                = $this->_em->createNativeQuery($dql, $rsm);
            $query->useResultCache(true);
            $setParams ['status'] = 1;
            $query->setParameters($setParams);
            // echo $query->getSQL();
            // $result = $query->getResult(2);

            $result = $query->getArrayResult();
            // echo "<pre>"; print_r($result); echo "</pre>";
        } catch (\Exception $e) {
            // echo 'Message: ' . $e->getMessage() . '<br>'; exit;
            // echo $query->getSQL();
            // exit;
        }

        // echo "<pre>"; print_r($result); echo "</pre>";

        return $result;
    }

    /**
     * Gety exclusive events count for front end event banser sliders
     *
     * @param array $args
     * @return int - count
     */
    public function getExclusiveEventsCount($args)
    {
        try {

            // $setParams = array();
            $setParams = $args ['setParams'];
            // echo "<pre>"; print_r($setParams); echo "</pre>"; exit;
            $append    = $args ['append'];
            // $offset = $args['offset'];
            $joins     = $args ['joins'];
            $rsm       = new ORM\Query\ResultSetMapping();

            $rsm->addEntityResult('ZSELEX_Entity_Advertise', 'u');
            $rsm->addScalarResult('COUNT(*)', 'count');
            $today = date("Y-m-d");

            $dql = "SELECT COUNT(*)
            FROM zselex_shop_events u FORCE INDEX(shop_id)
                        INNER JOIN zselex_serviceshop b ON b.shop_id=u.shop_id 
                        INNER JOIN zselex_shop a ON a.shop_id=u.shop_id
                        $joins
                        WHERE ((DATEDIFF('$today' , b.timer_date) <= b.timer_days) OR b.service_status=3)
                        AND b.type='exclusiveevent'
                        AND a.shop_id IS NOT NULL
                        AND a.status=:status
                        AND u.status=:status
                       ".$append."
                        AND u.exclusive=1 AND (u.shop_event_startdate >=CURDATE() OR u.shop_event_startdate <=CURDATE()) AND u.shop_event_enddate >=CURDATE() 
                        AND (u.activation_date<=CURDATE() OR UNIX_TIMESTAMP(u.activation_date) = 0 OR u.activation_date IS NULL) 
                       ";

            // echo $dql . '<br><br>';

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

    public function backgroundAd()
    {
        // set_time_limit(0);
        $getArgs     = array(
            'entity' => 'ZSELEX_Entity_Shop',
            'fields' => array(
                'a.shop_name',
                'a.shop_id'
            )
        );
        $getAllShops = $this->getAll($getArgs);

        $lim = 1;

        // echo "<pre>"; print_r($getAllShops); echo "</pre>"; exit;
        $i = 0;
        foreach ($getAllShops as $vals) {
            $shop_id = $vals ['shop_id'];
            $shop    = $this->_em->find('ZSELEX_Entity_Shop', $shop_id);
            $country = $this->_em->find('ZSELEX_Entity_Country', 61);

            $ad1     = new ZSELEX_Entity_Advertise();
            $ad1->setName('COUNTRY-DENMARK'.$i);
            $adPrice = $this->_em->find('ZSELEX_Entity_AdvertisePrice', 1);
            $ad1->setAdprice($adPrice);
            $ad1->setAdvertise_type('productAd');
            $ad1->setShop($shop);
            $ad1->setCountry($country);
            $ad1->setLevel('COUNTRY');
            $this->_em->persist($ad1);
            $this->_em->flush();

            /*
             * $ad2 = new ZSELEX_Entity_Advertise();
             * $ad2->setName('COUNTRY-DENMARK' . $i);
             * $adPrice = $this->_em->find('ZSELEX_Entity_AdvertisePrice', 2);
             * $ad2->setAdprice($adPrice);
             * $ad2->setAdvertise_type('productAd');
             * $ad2->setShop($shop);
             * $ad2->setCountry($country);
             * $ad2->setLevel('COUNTRY');
             * $this->_em->persist($ad2);
             * $this->_em->flush();
             *
             * $ad3 = new ZSELEX_Entity_Advertise();
             * $ad3->setName('COUNTRY-DENMARK' . $i);
             * $adPrice = $this->_em->find('ZSELEX_Entity_AdvertisePrice', 3);
             * $ad3->setAdprice($adPrice);
             * $ad3->setAdvertise_type('productAd');
             *
             * $ad3->setShop($shop);
             *
             * $ad3->setCountry($country);
             * $ad3->setLevel('COUNTRY');
             *
             * $this->_em->persist($ad3);
             * $this->_em->flush();
             *
             */
        }

        return true;
    }

    /**
     * Get all events
     * 
     * @param int $args ['limit']
     * @param int $args ['start']
     * @return type
     */
    public function getAllEventsApi($args)
    {

        // echo "<pre>"; print_r($args); echo "</pre>";
        $limit = !empty($args ['limit']) ? $args ['limit'] : 2;
        $start = !empty($args ['start']) ? $args ['start'] : 0;

        try {
            $rsm = new ORM\Query\ResultSetMapping();
            $rsm->addEntityResult('ZSELEX_Entity_Event', 'a');
            $rsm->addFieldResult('a', 'shop_event_id', 'shop_event_id');
            $rsm->addFieldResult('a', 'shop_event_name', 'shop_event_name');
            $rsm->addFieldResult('a', 'shop_event_shortdescription',
                'shop_event_shortdescription');
            $rsm->addFieldResult('a', 'shop_event_description',
                'shop_event_description');
            $rsm->addFieldResult('a', 'news_article_id', 'news_article_id');
            $rsm->addFieldResult('a', 'event_image', 'event_image');
            $rsm->addFieldResult('a', 'event_doc', 'event_doc');
            $rsm->addFieldResult('a', 'product_id', 'product_id');
            $rsm->addFieldResult('a', 'showfrom', 'showfrom');
            $rsm->addFieldResult('a', 'event_link', 'event_link');
            $rsm->addFieldResult('a', 'open_new', 'open_new');
            $rsm->addFieldResult('a', 'event_urltitle', 'event_urltitle');
            $rsm->addMetaResult('a', 'shop_id', 'shop_id');
            $rsm->addMetaResult('a', 'shop_event_startdate',
                'shop_event_startdate');
            $rsm->addMetaResult('a', 'shop_event_enddate', 'shop_event_enddate');
            $rsm->addMetaResult('a', 'call_link_directly', 'call_link_directly');
            $rsm->addMetaResult('a', 'urltitle', 'urltitle');

            $today  = date("Y-m-d");
            $shopId = 79;
// KIMENEMARK: shopId = 79 <=> Fredericia Shopping!!!

            $dql    = "SELECT a.shop_event_id , a.shop_id, a.shop_event_name , a.shop_event_shortdescription , a.shop_event_description , a.news_article_id ,
                         a.event_image , a.event_doc , a.product_id , a.showfrom , a.event_link , a.open_new,a.call_link_directly,
                         a.shop_event_startdate , a.shop_event_enddate , a.event_urltitle , shop.urltitle
                        FROM zselex_shop_events a
                        INNER JOIN zselex_serviceshop sv ON sv.shop_id=a.shop_id
                        INNER JOIN zselex_shop shop ON shop.shop_id=a.shop_id
                        WHERE 
                        shop.shop_id=$shopId
                        AND a.status=1
                        AND shop.status=1
                        AND sv.type='eventservice'
                        AND ((DATEDIFF('$today' , sv.timer_date) <= sv.timer_days) OR sv.service_status=3)
                        AND (a.shop_event_startdate >=CURDATE() OR a.shop_event_startdate <=CURDATE()) AND a.shop_event_enddate >=CURDATE()
                        AND (a.activation_date<=CURDATE() OR UNIX_TIMESTAMP(a.activation_date) = 0 OR a.activation_date IS NULL)
                        AND shop.shop_id!=1
                        ORDER BY a.shop_event_startdate ASC, a.shop_event_id ASC
                        LIMIT $start , $limit";
// KIMENEMARK: Here we should sort on user sort order in stead of shop_event_id !!!						
// KIMENEMARK: shop_id!=1 <=> 1=CityPilot!!!
            // echo $dql; exit;
            $query  = $this->_em->createNativeQuery($dql, $rsm);
            // $query->setParameter('shop_id', $shop_id);
            $query->useResultCache(true);
            $result = $query->getArrayResult();
            // echo "<pre>"; print_r($result); echo "</pre>";

            return $result;
        } catch (\Exception $e) {
            echo 'Message: '.$e->getMessage().'<br>';
            // echo $query->getSQL();
            // exit;
        }
    }

    /**
     * Get Event total count
     *
     * @return int count
     */
    public function getAllEventsApiCount()
    {

        // echo "<pre>"; print_r($args); echo "</pre>";


        try {
            $rsm = new ORM\Query\ResultSetMapping();
            $rsm->addEntityResult('ZSELEX_Entity_Event', 'a');
            $rsm->addScalarResult('COUNT(a.shop_event_id)', 'count');

            $today  = date("Y-m-d");
            $shopId = 79;


            $dql    = "SELECT COUNT(a.shop_event_id)
                        FROM zselex_shop_events a
                        INNER JOIN zselex_serviceshop sv ON sv.shop_id=a.shop_id
                        INNER JOIN zselex_shop shop ON shop.shop_id=a.shop_id
                        WHERE shop.shop_id=$shopId
                        AND a.status=1
                        AND shop.status=1
                        AND sv.type='eventservice'
                        AND ((DATEDIFF('$today' , sv.timer_date) <= sv.timer_days) OR sv.service_status=3)
                        AND (a.shop_event_startdate >=CURDATE() OR a.shop_event_startdate <=CURDATE()) AND a.shop_event_enddate >=CURDATE()
                        AND (a.activation_date<=CURDATE() OR UNIX_TIMESTAMP(a.activation_date) = 0 OR a.activation_date IS NULL)
                        AND shop.shop_id!=1
                        ";
            // echo $dql;
            $query  = $this->_em->createNativeQuery($dql, $rsm);
            // $query->useResultCache(true);
            $result = $query->getSingleScalarResult();

            return $result;
        } catch (\Exception $e) {
            echo 'Message: '.$e->getMessage().'<br>';
            // echo $query->getSQL();
            // exit;
        }
    }
}
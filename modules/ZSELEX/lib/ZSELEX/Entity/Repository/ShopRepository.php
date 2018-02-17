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
use Doctrine\ORM\QueryBuilder;
use DoctrineExtensions\Paginate\Paginate;
use Doctrine\ORM;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Repository class for DQL calls
 */
class ZSELEX_Entity_Repository_ShopRepository extends ZSELEX_Entity_Repository_General
{

    public function createFullTextIndex()
    {
        $statement = Doctrine_Manager::getInstance()->connection();
        $sql       = "ALTER TABLE `zselex_shop` ADD FULLTEXT(`shop_name`)";
        $query     = $statement->execute($sql);
        return true;
    }

    public function createShop($args)
    {
        try {
            // $item = ZSELEX_Util::purifyHtml($args);
            $item      = $args;
            // echo "<pre>"; print_r($item); echo "</pre>"; exit;
            $shop      = new ZSELEX_Entity_Shop ();
            $shop->setTitle($item ['title']);
            $shop->setUrltitle($item ['urltitle']);
            $country   = $this->_em->find('ZSELEX_Entity_Country',
                $item ['country_id']);
            $shop->setCountry($country);
            $region    = $this->_em->find('ZSELEX_Entity_Region',
                $item ['region_id']);
            $shop->setRegion($region);
            $city      = $this->_em->find('ZSELEX_Entity_City',
                $item ['city_id']);
            $shop->setCity($city);
            $area      = $this->_em->find('ZSELEX_Entity_Area',
                $item ['area_id']);
            $shop->setArea($area);
            $branch    = $this->_em->find('ZSELEX_Entity_Branch',
                $item ['branch_id']);
            // echo $branch; exit;
            $shop->setBranch($branch);
            $affiliate = $this->_em->find('ZSELEX_Entity_ShopAffiliation',
                $item ['aff_id']);
            // echo $affiliate; exit;
            $shop->setAff_id($affiliate);
            $shop->setStatus($item ['status']);
            $shop->setShop_name($item ['shop_name']);
            $shop->setDescription($item ['description']);
            $shop->setDefault_img_frm($item ['default_img_frm']);
            $shop->setStatus($item ['status']);

            $this->_em->persist($shop);
            $this->_em->flush();

            $InsertId = $shop->getShop_id();
            $result   = $InsertId;
            return $result;
        } catch (\Exception $e) {
            echo 'Message: '.$e->getMessage().'<br>';
            // echo $query->getSQL();
            exit();
        }
    }

    public function getSingleResult($args)
    {
        $field  = $args ['field'];
        $where  = $args ['where'];
        $dql    = "SELECT a.$field FROM ZSELEX_Entity_Shop a
            WHERE $where";
        // echo $dql;
        $query  = $this->_em->createQuery($dql);
        // $query->setParameter(1, $sid);
        $result = $query->getOneOrNullResult();
        return $result [$field];
    }

    public function updateItem($args)
    {
        $id    = $args ['id'];
        $idVal = $args ['idVal'];
    }

    public function shop_exist($args)
    {
        $shop_id = $args ['shop_id'];
        $query   = $this->_em->createQuery('SELECT COUNT(a.shop) FROM ZSELEX_Entity_ShopSetting a '.'WHERE a.shop=:shop_id');
        $query->setParameter('shop_id', $shop_id);
        $count   = $query->getSingleScalarResult();
        return $count;
    }

    public function shop_count($args)
    {
        $shop_id = $args ['shop_id'];
        $query   = $this->_em->createQuery('SELECT COUNT(a.shop_id) FROM ZSELEX_Entity_Shop a '.'WHERE a.shop_id=:shop_id');
        $query->setParameter('shop_id', $shop_id);
        $count   = $query->getSingleScalarResult();
        return $count;
    }

    function updateTermsCondition($args)
    {
        // $date = date("Y-m-d");
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $qb = $this->_em->createQueryBuilder();
        $q  = $qb->update('ZSELEX_Entity_ShopSetting', 'u')->set('u.terms_conditions',
                ':terms_conditions')->where('u.shop = :shop_id')->setParameter('shop_id',
                $args ['shop_id'])->setParameter('terms_conditions',
                serialize($args ['terms_conditions']))->getQuery();
        $p  = $q->execute();
        if ($p) {

            return $p;
        }
    }

    public function getShopDetails($args)
    {
        $shop_id = $args ['shop_id'];
        $dql     = "SELECT a.terms_conditions , a.no_payment , a.opening_hours FROM ZSELEX_Entity_ShopSetting a
            WHERE a.shop=:shop_id";
        // echo $dql;
        $query   = $this->_em->createQuery($dql);
        $query->setParameter('shop_id', $shop_id);
        $result  = $query->getOneOrNullResult();
        return $result;
    }

    function updateNopayment($args)
    {
        // $date = date("Y-m-d");
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $qb = $this->_em->createQueryBuilder();
        $q  = $qb->update('ZSELEX_Entity_ShopDetail', 'u')->set('u.no_payment',
                ':no_payment')->where('u.shop_id = :shop_id')->setParameter('shop_id',
                $args ['shop_id'])->setParameter('no_payment',
                $args ['no_payment'])->getQuery();
        $p  = $q->execute();
        if ($p) {

            return $p;
        }
    }

    function setShopIdInShopDetails()
    {
        $dql    = "SELECT a.shop_id FROM ZSELEX_Entity_Shop a WHERE a.shop_id NOT IN(SELECT b.shop_id FROM ZSELEX_Entity_ShopDetail b)";
        $query  = $this->_em->createQuery($dql);
        $result = $query->getResult();

        // echo "<pre>"; print_r($result); echo "</pre>"; exit;
        foreach ($result as $value) {
            $shop_id = $value ['shop_id'];
            $str .= "(".$shop_id.') , ';
        }
        $str   = substr($str, 0, - 2);
        $sql   = "INSERT INTO zselex_shop_details(shop_id)values $str";
        // echo $sql; exit;
        $query = DBUtil::executeSQL($sql);

        return true;
    }

    function subscribeUser($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $shop_id = $args ['shop_id'];
        $user_id = $args ['user_id'];
        $email   = $args ['email'];
        $query   = $this->_em->createQuery('SELECT COUNT(a.user_id) FROM ZSELEX_Entity_Newsletter a '.'WHERE a.shop_id=:shop_id AND a.user_email=:email');
        $query->setParameter('shop_id', $shop_id);
        $query->setParameter('email', $email);
        $count   = $query->getSingleScalarResult();
        if (!$count) {
            $newsletter_entity = new ZSELEX_Entity_Newsletter ();
            $newsletter_entity->setUser_id($user_id);
            $newsletter_entity->setShop_id($shop_id);
            $newsletter_entity->setUser_email($email);
            $this->_em->persist($newsletter_entity);
            $this->_em->flush();
        }
        return true;
    }

    public function getNopayment($args)
    {
        $shop_id = $args ['shop_id'];
        $dql     = "SELECT a.no_payment
                  FROM ZSELEX_Entity_ShopSetting a 
                  WHERE a.shop = :shop_id";

        // echo $dql;

        $query  = $this->_em->createQuery($dql);
        $query->setParameter('shop_id', $shop_id);
        $result = $query->getOneOrNullResult();
        // echo "<pre>"; print_r($result); echo "</pre>";

        return $result; // hydrate result to array
    }

    public function paymentsEnabled($shop_id)
    {
        $em        = ServiceUtil::getService('doctrine.entitymanager');
        $netaxept  = $em->getRepository('ZPayment_Entity_Netaxept')->getNetaxept(array(
            'shop_id' => $shop_id
        ));
        $paypal    = $em->getRepository('ZPayment_Entity_PaypalSetting')->getPaypal(array(
            'shop_id' => $shop_id
        ));
        $quickpay  = $em->getRepository('ZPayment_Entity_QuickPaySetting')->getQuickPay(array(
            'shop_id' => $shop_id
        ));
        $directpay = $em->getRepository('ZPayment_Entity_DirectpaySetting')->getDirectpay(array(
            'shop_id' => $shop_id
        ));
        $nopayment = $em->getRepository('ZSELEX_Entity_ShopSetting')->getNopayment(array(
            'shop_id' => $shop_id
        ));

        $enabled = $netaxept ['enabled'] + $paypal ['enabled'] + $quickpay ['enabled']
            + $directpay ['enabled'] + $nopayment ['no_payment'];
        return $enabled;
        // return true;
    }

    function setShopSettings()
    {
        $dql    = "SELECT * FROM zselex_shop";
        $query  = DBUtil::executeSQL($dql);
        $result = $query->fetchAll();

        // echo "<pre>"; print_r($result); echo "</pre>"; exit;
        foreach ($result as $value) {

            $str .= "(".$value ['shop_id'].' , '."'".$value ['main']."'".' , '."'".$value ['opening_hours']."'".","."'".$value ['link_to_homepage']."'".') , ';
        }
        $str   = substr($str, 0, - 2);
        $sql   = "INSERT INTO zselex_shop_a_settings(shop_id,main,opening_hours,link_to_homepage)values $str";
        // echo $sql; exit;
        $query = DBUtil::executeSQL($sql);

        $dql    = "SELECT * FROM zselex_shop_details";
        $query  = DBUtil::executeSQL($dql);
        $result = $query->fetchAll();

        // echo "<pre>"; print_r($result); echo "</pre>"; exit;
        foreach ($result as $value) {

            $dql   = "update zselex_shop_a_settings set terms_conditions='".$value ['terms_conditions']."' , no_payment='".$value ['no_payment']."' where shop_id='".$value ['shop_id']."'";
            $query = DBUtil::executeSQL($dql);
        }

        return true;
    }

    function updateShopSetting($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $fields = $args ['fields'];
        $items  = '';
        foreach ($fields as $fkey => $fval) {
            $items .= "a.".$fkey."="."'$fval'".',';
        }
        $items = substr($items, 0, - 1);
        $where = $args ['where'];

        $query      = "UPDATE ZSELEX_Entity_ShopSetting a SET $items WHERE $where";
        // echo $query; exit;
        $q          = $this->_em->createQuery($query);
        $numUpdated = $q->execute();
        return $numUpdated;
    }

    public function getShopAndSettings($args)
    {
        $shop_id = $args ['shop_id'];
        $dql     = "SELECT a.shop_id ,  a.shop_name ,a.urltitle , a.description , a.shop_info , a.address ,
                 a.telephone,a.fax,a.email,b.default_img_frm,b.main,b.opening_hours,b.link_to_homepage,a.link_to_mailinglist
                 FROM ZSELEX_Entity_ShopSetting b 
                 JOIN b.shop a
                 WHERE b.shop = :shop_id
                 ";
        // echo $dql;

        $query  = $this->_em->createQuery($dql);
        $query->setParameter('shop_id', $shop_id);
        $result = $query->getOneOrNullResult();
        // echo "<pre>"; print_r($result); echo "</pre>";

        return $result; // hydrate result to array
    }

    function createShopSetting($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $shop_id = $args ['shop_id'];
        $items   = '';

        $query      = "INSERT INTO ZSELEX_Entity_ShopSetting a (shop , default_img_frm , main , theme , opening_hours , no_payment , link_to_homepage,  terms_conditions) values('".$shop_id."' , '', '0', '', '', '0', '', '')";
        // echo $query; exit;
        $q          = $this->_em->createQuery($query);
        $numUpdated = $q->execute();
        return $numUpdated;
    }

    public function getShopType($args)
    {
        try {
            $shop_id = $args ['shop_id'];
            $dql     = "SELECT a.shoptype_id , a.shoptype FROM ZSELEX_Entity_MiniShop a
            WHERE a.shop=:shop_id";
            // echo $dql;
            $query   = $this->_em->createQuery($dql);
            $query->setParameter('shop_id', $shop_id);
            $result  = $query->getOneOrNullResult();
            return $result;
        } catch (\Exception $e) {
            echo 'Message: '.$e->getMessage().'<br>';
            exit;
            // echo $query->getSQL();
        }
    }

    public function getShopIds($args)
    {

        // echo "<pre>"; print_r($args); echo "</pre>";
        $append    = $args ['append'];
        $limit     = $args ['limit'];
        $setParams = $args ['setParams'];
        $rsm       = new ORM\Query\ResultSetMapping ();
        $rsm->addEntityResult('ZSELEX_Entity_Shop', 'a');
        $rsm->addFieldResult('a', 'shop_id', 'shop_id');

        $dql   = "SELECT a.shop_id
                FROM zselex_shop a
                WHERE a.shop_id IS NOT NULL AND a.status=1 ".$append." LIMIT 0 , $limit";
        // echo $dql;
        $query = $this->_em->createNativeQuery($dql, $rsm);
        if (isset($setParams) && !empty($setParams)) {
            $query->setParameters($setParams);
        }
        $result = $query->getArrayResult();
        // echo "<pre>"; print_r($result); echo "</pre>";
        return $result;
    }

    public function getNewShops($args)
    {

        // echo "<pre>"; print_r($args); echo "</pre>";
        try {
            $limit     = $args ['limit'];
            $orderby   = $args ['orderby'];
            $append    = $args ['append'];
            $joins     = $args ['joins'];
            $setParams = $args ['setParams'];
            $rsm       = new ORM\Query\ResultSetMapping ();

            $rsm->addEntityResult('ZSELEX_Entity_Shop', 'a');
            $rsm->addMetaResult('a', 'shop_id', 'shopid');
            $rsm->addFieldResult('a', 'shop_name', 'shop_name');
            $rsm->addFieldResult('a', 'default_img_frm', 'default_img_frm');
            $rsm->addMetaResult('a', 'aff_id', 'aff_id');

            $rsm->addMetaResult('a', 'LEFT(a.description, 40)', 'description');
            $rsm->addMetaResult('a', 'round((sum(c.rating)/COUNT(c.rating)),1)',
                'rating');
            $rsm->addMetaResult('a', 'COUNT(c.rating)', 'votes');

            $rsm->addMetaResult('a', 'city_name', 'city_name');
            $rsm->addMetaResult('a', 'rating', 'rating');
            $rsm->addMetaResult('a', 'uname', 'uname');
            $rsm->addMetaResult('a', 'image_name', 'image_name');
            $rsm->addMetaResult('a', 'name', 'name');
            $rsm->addMetaResult('a', 'type', 'minishop');

            $today = date("Y-m-d");
            $dql   = "SELECT a.shop_id , a.shop_name , LEFT(a.description, 40) , a.default_img_frm , a.aff_id ,
              b.city_name , round((sum(c.rating)/COUNT(c.rating)),1) , COUNT(c.rating) ,
              e.uname , f.image_name , g.name , sv2.type 
              FROM zselex_shop a
              INNER JOIN zselex_serviceshop sv ON sv.shop_id=a.shop_id
              $joins
              LEFT JOIN zselex_city b ON a.city_id=b.city_id
              LEFT JOIN zselex_shop_ratings c ON c.shop_id=a.shop_id
              LEFT JOIN zselex_shop_owners d ON d.shop_id=a.shop_id
              LEFT JOIN users e ON e.uid=d.user_id
              LEFT JOIN zselex_shop_gallery f ON a.shop_id=f.shop_id AND f.defaultImg=1
              LEFT JOIN zselex_files g ON a.shop_id=g.shop_id AND g.defaultImg=1
              LEFT JOIN zselex_serviceshop sv2 ON sv2.shop_id=a.shop_id AND ((DATEDIFF('$today' , sv2.timer_date) <= sv2.timer_days) OR sv2.service_status=3) AND sv2.type='minishop'
              WHERE a.status=1
              AND ((DATEDIFF('$today' , sv.timer_date) <= sv.timer_days) OR sv.service_status=3)
              AND sv.type='minisite'
              ".$append." 
              GROUP BY a.shop_id
              $orderby
              LIMIT 0,$limit";
            // echo $dql . '<br>';
            $query = $this->_em->createNativeQuery($dql, $rsm);
            if (isset($setParams) && !empty($setParams)) {
                $query->setParameters($setParams);
            }
            $query->useResultCache(true);
            $result = $query->getArrayResult();
            // echo "<pre>"; print_r($result); echo "</pre>";
            return $result;
        } catch (\Exception $e) {
            // echo 'Message: ' . $e->getMessage();
            // exit;
        }
    }

    public function getAffiliate($args)
    {
        try {

            $aff_id = $args ['aff_id'];
            $dql    = "SELECT a.aff_id , a.aff_image
                FROM ZSELEX_Entity_ShopAffiliation a
                WHERE  a.aff_id=:aff_id";
            $query  = $this->_em->createQuery($dql);
            $query->useResultCache(true);
            $query->setParameter('aff_id', $aff_id);
            $result = $query->getOneOrNullResult();
            return $result;
        } catch (\Exception $e) {
            echo 'Message: '.$e->getMessage();
            // exit;
        }
    }
    /*
     * Back Up
     */

    public function getMinisite1($args)
    {
        $shop_id = $args ['shop_id'];
        // echo $shop_id;

        try {
            $dql    = "SELECT a.shop_id , a.shop_name , a.urltitle , a.address , a.theme , a.description , a.shop_info , a.default_img_frm ,
                 a.link_to_homepage,d.gallery_id , d.image_name , e.file_id , e.name
                 FROM ZSELEX_Entity_Shop a
                 LEFT JOIN a.aff_id b
                 LEFT JOIN a.shop_owners c
                 LEFT JOIN a.shop_gallery d
                 LEFT JOIN a.shop_images e 
                 WHERE a.shop_id=:shop_id
                 GROUP BY a.shop_id
                 ";
            $query  = $this->_em->createQuery($dql);
            // $query->useResultCache(true);
            $query->setParameter('shop_id', $shop_id);
            $result = $query->getOneOrNullResult(2);
            // $result = $query->getResult();
            // echo "<pre>"; print_r($result); echo "</pre>";
            return $result;
        } catch (\Exception $e) {
            echo 'Message: '.$e->getMessage();
            // exit;
        }

        // echo "<pre>"; print_r($result); echo "</pre>";
    }

    /**
     * Get shop details in minisite
     *
     * @param int $args['shop_id']
     * @param int $args['title']
     * @return array
     */
    public function getMinisite($args)
    {
        $shop_id = $args ['shop_id'];
        $title   = $args ['title'];
        // echo $shop_id;
        if (isset($shop_id) && !empty($shop_id)) {
            $where = " a.shop_id=:shop_id ";
        } else {
            $where = " a.urltitle=:urltitle ";
        }
        // echo $where; exit;
        try {
            $dql   = "SELECT a.shop_id , a.shop_name , a.urltitle , a.address , a.theme , a.description , a.shop_info , a.default_img_frm ,
                 a.link_to_homepage , a.link_to_mailinglist , a.status , b.city_name
                 FROM ZSELEX_Entity_Shop a
                 LEFT JOIN a.city b
                 WHERE $where
                   ";
            // echo $dql; exit;
            $query = $this->_em->createQuery($dql);
            // $query->useResultCache(true);
            // $query->setParameter('shop_id', $shop_id);
            if (isset($shop_id) && !empty($shop_id)) {
                $query->setParameter('shop_id', $shop_id);
            } else {
                $query->setParameter('urltitle', $title);
            }
            $result = $query->getOneOrNullResult(2);
            // echo "<pre>"; print_r($result); echo "</pre>"; exit;
            if (!$result) {
                // echo "not found"; exit;
                $oldUrlArr = array(
                    'entity' => 'ZSELEX_Entity_Url',
                    'where' => array(
                        'a.type' => 'shop',
                        'a.url' => $title
                    )
                );
                $getOldUrl = $this->get($oldUrlArr);
                // echo "<pre>"; print_r($getOldUrl); echo "</pre>"; exit;
                if ($getOldUrl) {
                    $url = pnGetBaseURL().ModUtil::url('ZSELEX', 'user', 'site',
                            array(
                            'shop_id' => $getOldUrl ['type_id']
                    ));
                    // echo $url; exit;
                    // $this->redirect($url);
                    System::redirect($url);
                    die();
                }
            } elseif (!$result['status']) {
                // echo "comes here";  exit;
                if (!SecurityUtil::checkPermission('ZSELEX::cart', '::',
                        ACCESS_ADMIN)) {
                    return array();
                }
            }
            // echo "<pre>"; print_r($result); echo "</pre>";
            return $result;
        } catch (\Exception $e) {
            echo 'Message (getMinisite): '.$e->getMessage();
            // exit;
        }

        // echo "<pre>"; print_r($result); echo "</pre>";
    }

    /**
     * Get all shops at admin panel
     * 
     * @param type $args
     * @return array
     */
    public function getShopListing($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        try {
            $startlimit = $args ['startlimit'];
            if ($startlimit > 0) {
                $startlimit = $startlimit - 1;
            }
            $itemsperpage = $args ['itemsperpage'];
            $sql          = $args ['sql'];
            $orderby      = $args ['orderby'];

            if (!empty($args ['join_fields'])) {
                $join_fields = " , ".implode(',', $args ['join_fields']);
            }
            // echo $join_fields;
            $JOINS = $args ['joins'];

            $rsm = new ORM\Query\ResultSetMapping ();

            $rsm->addEntityResult('ZSELEX_Entity_Shop', 's');
            $rsm->addFieldResult('s', 'shop_id', 'shop_id');
            $rsm->addFieldResult('s', 'shop_name', 'shop_name');
            $rsm->addFieldResult('s', 'title', 'title');
            $rsm->addFieldResult('s', 'urltitle', 'urltitle');
            $rsm->addFieldResult('s', 'address', 'address');
            $rsm->addFieldResult('s', 'telephone', 'telephone');
            $rsm->addFieldResult('s', 'fax', 'fax');
            $rsm->addFieldResult('s', 'email', 'email');
            $rsm->addFieldResult('s', 'status', 'status');
            $rsm->addMetaResult('s', 'cr_date', 'cr_date');
            $rsm->addMetaResult('s', 'lu_date', 'lu_date');

            if (!empty($args ['join_fields'])) {
                $rsm->addMetaResult('s', 'category_name', 'category_name');
                $rsm->addMetaResult('s', 'branch_name', 'branch_name');
            }

            $rsm->addMetaResult('s', 'owner', 'owner');
            $rsm->addMetaResult('s', 'country_name', 'country_name');
            $rsm->addMetaResult('s', 'region_name', 'region_name');
            $rsm->addMetaResult('s', 'city_name', 'city_name');
            $rsm->addMetaResult('s', 'area_name', 'area_name');
            // $rsm->addMetaResult('s', 'branch_name', 'branch_name');
            $rsm->addMetaResult('s', 'shoptype', 'shoptype');
            $rsm->addMetaResult('s', 'aff_name', 'aff_name');
            $rsm->addMetaResult('s', 'bundle_name', 'bundle_name');

            $limitQuery = "LIMIT $startlimit , $itemsperpage";
            $dql        = " SELECT DISTINCT s.shop_id , s.shop_name , s.title , s.urltitle , s.address , s.telephone , s.fax,
                s.email , s.status , s.cr_date , s.lu_date ,
                m.shoptype , u.uname as owner , country.country_name , region.region_name , city.city_name , area.area_name  , aff.aff_name , bl.bundle_name
                $join_fields
                FROM zselex_shop as s 
                LEFT JOIN zselex_minishop as m ON m.shop_id=s.shop_id
                LEFT JOIN zselex_shop_owners ow ON ow.shop_id=s.shop_id
                LEFT JOIN users u ON ow.user_id=u.uid
                LEFT JOIN zselex_country country ON country.country_id=s.country_id 
                LEFT JOIN zselex_region region ON region.region_id=s.region_id  
                LEFT JOIN zselex_city city ON city.city_id=s.city_id 
                LEFT JOIN zselex_area area ON area.area_id=s.area_id
               
                LEFT JOIN zselex_shop_affiliation aff ON aff.aff_id=s.aff_id
                LEFT JOIN zselex_serviceshop_bundles as sb ON s.shop_id=sb.shop_id AND sb.bundle_type='main'
                LEFT JOIN zselex_service_bundles as bl ON bl.bundle_id=sb.bundle_id
                $JOINS
                WHERE s.shop_id IS NOT NULL ".$sql."
              
                    ".$orderby."
                $limitQuery";
            //GROUP BY s.shop_id
            //echo $dql.'<br>';
            $query      = $this->_em->createNativeQuery($dql, $rsm);

            $result = $query->getArrayResult();
            // $result = $query->getOneOrNullResult(2);
            return $result;
        } catch (\Exception $e) {
            echo 'Message: '.$e->getMessage();
            exit();
        }
    }

    public function getShopListingCount($args)
    {
        try {
            $startlimit = $args ['startlimit'];
            if ($startlimit > 0) {
                $startlimit = $startlimit - 1;
            }
            $itemsperpage = $args ['itemsperpage'];
            $sql          = $args ['sql'];

            if (!empty($args ['joins_fields'])) {
                $join_fields = " , ".implode(',', $join_fields);
            }
            $JOINS = $args ['joins'];

            $rsm = new ORM\Query\ResultSetMapping ();

            $rsm->addEntityResult('ZSELEX_Entity_Shop', 's');
            // $rsm->addFieldResult('s', 'shop_id', 'shop_id');

            $rsm->addScalarResult('COUNT(s.shop_id)', 'count');

            $dql    = " SELECT COUNT(s.shop_id)
                FROM zselex_shop as s 
                LEFT JOIN zselex_minishop as m ON m.shop_id=s.shop_id
                LEFT JOIN zselex_shop_owners ow ON ow.shop_id=s.shop_id
                LEFT JOIN users u ON ow.user_id=u.uid
                LEFT JOIN zselex_country country ON country.country_id=s.country_id 
                LEFT JOIN zselex_region region ON region.region_id=s.region_id  
                LEFT JOIN zselex_city city ON city.city_id=s.city_id 
                LEFT JOIN zselex_area area ON area.area_id=s.area_id
                LEFT JOIN zselex_branch branch ON branch.branch_id=s.branch_id
                LEFT JOIN zselex_shop_affiliation aff ON aff.aff_id=s.aff_id
                LEFT JOIN zselex_serviceshop_bundles as sb ON s.shop_id=sb.shop_id
                LEFT JOIN zselex_service_bundles as bl ON bl.bundle_id=sb.bundle_id
                $JOINS
                WHERE s.shop_id IS NOT NULL ".$sql."
                ";
            // echo $dql; exit;
            $query  = $this->_em->createNativeQuery($dql, $rsm);
            // $result = $query->getArrayResult();
            $result = $query->getOneOrNullResult(2);
            return $result ['count'];
        } catch (\Exception $e) {
            echo 'Message: '.$e->getMessage();
            exit();
        }
    }

    public function addShopCategories($args)
    {
        $categories = $args ['categories'];
        $shop_id    = $args ['shop_id'];

        $shopObj = $this->_em->getRepository('ZSELEX_Entity_Shop')->find($shop_id);
        foreach ($categories as $cat_id) {
            // echo $cat_id . '<br>';
            $cat = $this->_em->getRepository('ZSELEX_Entity_Category')->find($cat_id);
            // $shopObj = new ZSELEX_Entity_Shop();
            // $shopObj = $this->_em->getRepository('ZSELEX_Entity_Shop')->find($shop_id);
            // $shopObj->getShop_to_category()->add($cat);
            $shopObj->addCategory($cat);
            $this->_em->persist($shopObj);
        }
        $this->_em->flush();
        return true;
    }

    public function deleteShopCategories($args)
    {
        // $categories = $args['categories'];
        $shopRepo = $this->_em->getRepository('ZSELEX_Entity_Shop');
        $shop_id  = $args ['shop_id'];

        $getCategories = $shopRepo->getAll(array(
            'entity' => 'ZSELEX_Entity_Shop',
            'where' => array(
                'a.shop_id' => $shop_id
            ),
            'joins' => array(
                'JOIN a.shop_to_category b'
            ),
            'fields' => array(
                'b.category_id'
            )
        ));

        $categories = $getCategories;

        $shopObj = $this->_em->getRepository('ZSELEX_Entity_Shop')->find($shop_id);
        foreach ($categories as $val) {
            $catObj = $this->_em->getRepository('ZSELEX_Entity_Category')->find($val ['category_id']);
            // $shopObj = $this->_em->getRepository('ZSELEX_Entity_Shop')->find($shop_id);
            $shopObj->removeCategory($catObj);
            $this->_em->persist($shopObj);
            // $this->_em->flush();
        }
        $this->_em->flush();
        return true;
    }

    public function addShopBranches($args)
    {
        $branches = $args ['branches'];
        $shop_id  = $args ['shop_id'];

        // echo "shopID:" . $shop_id;
        // echo "<pre>"; print_r($branches); echo "</pre>"; exit;
        $shopObj = $this->_em->getRepository('ZSELEX_Entity_Shop')->find($shop_id);
        foreach ($branches as $branch_id) {
            // echo $cat_id . '<br>';
            $branch = $this->_em->getRepository('ZSELEX_Entity_Branch')->find($branch_id);
            // $shopObj = new ZSELEX_Entity_Shop();
            // $shopObj = $this->_em->getRepository('ZSELEX_Entity_Shop')->find($shop_id);
            // $shopObj->getShop_to_category()->add($cat);
            $shopObj->addBranch($branch);
            $this->_em->persist($shopObj);
        }
        $this->_em->flush();
        return true;
    }

    public function deleteShopBranches($args)
    {
        // $categories = $args['categories'];
        $shopRepo = $this->_em->getRepository('ZSELEX_Entity_Shop');
        $shop_id  = $args ['shop_id'];

        $getBranches = $shopRepo->getAll(array(
            'entity' => 'ZSELEX_Entity_Shop',
            'where' => array(
                'a.shop_id' => $shop_id
            ),
            'joins' => array(
                'JOIN a.shop_to_branch b'
            ),
            'fields' => array(
                'b.branch_id'
            )
        ));

        $branches = $getBranches;

        $shopObj = $this->_em->getRepository('ZSELEX_Entity_Shop')->find($shop_id);
        foreach ($branches as $val) {
            $branchObj = $this->_em->getRepository('ZSELEX_Entity_Branch')->find($val ['branch_id']);
            // $shopObj = $this->_em->getRepository('ZSELEX_Entity_Shop')->find($shop_id);
            $shopObj->removeBranch($branchObj);
            $this->_em->persist($shopObj);
            // $this->_em->flush();
        }
        $this->_em->flush();
        return true;
    }

    public function createMinisiteUpdate($args)
    {
        try {
            $item = ZSELEX_Util::purifyHtml($args);

            $MinisiteUpdate = new ZSELEX_Entity_MinisiteUpdate ();
            $MinisiteUpdate->setShop_id($item ['shop_id']);
            $MinisiteUpdate->setOwner_id($item ['owner_id']);
            $MinisiteUpdate->setUpdate_date($item ['update_date']);
            $MinisiteUpdate->setIs_updated_recent($item ['is_updated_recent']);

            $this->_em->persist($MinisiteUpdate);
            $this->_em->flush();

            $InsertId = $MinisiteUpdate->getId();
            $result   = $InsertId;
            return $result;
        } catch (\Exception $e) {
            echo 'Message: '.$e->getMessage().'<br>';
            // echo $query->getSQL();
            exit();
        }
    }

    public function createEmployeeDnd($args)
    {
        try {
            $item = ZSELEX_Util::purifyHtml($args);

            $shop = $this->_em->find('ZSELEX_Entity_Shop', $item ['shop_id']);

            $image = new ZSELEX_Entity_Employee ();
            $image->setName($item ['name']);
            $image->setShop($shop);
            $image->setEmp_image($item ['emp_image']);
            $image->setStatus($item ['status']);

            $this->_em->persist($image);
            $this->_em->flush();

            $InsertId = $image->getEmp_id();
            $result   = $InsertId;
            return $result;
        } catch (\Exception $e) {
            // echo 'Message: ' . $e->getMessage() . '<br>';
            // echo $query->getSQL();
            // exit;
        }
    }

    public function createBannerDnd($args)
    {
        try {
            $item = ZSELEX_Util::purifyHtml($args);
            $shop = $this->_em->find('ZSELEX_Entity_Shop', $item ['shop_id']);

            $image = new ZSELEX_Entity_Banner ();
            $image->setShop($shop);
            $image->setBanner_image($item ['banner_image']);
            $image->setHeight($item ['height']);
            $image->setWidth($item ['width']);

            $this->_em->persist($image);
            $this->_em->flush();

            $InsertId = $image->getShop_banner_id();
            $result   = $InsertId;
            return $result;
        } catch (\Exception $e) {
            echo 'Message: '.$e->getMessage().'<br>';
            // echo $query->getSQL();
            exit();
        }
    }

    public function getShopThemes()
    {
        try {

            $rsm = new ORM\Query\ResultSetMapping ();
            $rsm->addEntityResult('ZSELEX_Entity_ZselexTheme', 'u');

            $rsm->addFieldResult('u', 'zt_id', 'zt_id');
            $rsm->addMetaResult('u', 'id', 'id');
            $rsm->addMetaResult('u', 'name', 'name');
            $rsm->addMetaResult('u', 'displayname', 'displayname');
            $rsm->addMetaResult('u', 'description', 'description');
            $rsm->addMetaResult('u', 'type', 'type');
            $rsm->addMetaResult('u', 'type', 'type');
            $rsm->addMetaResult('u', 'version', 'version');
            $rsm->addMetaResult('u', 'admin', 'admin');
            $rsm->addMetaResult('u', 'user', 'user');
            $rsm->addMetaResult('u', 'system', 'system');
            $rsm->addMetaResult('u', 'state', 'state');
            $rsm->addMetaResult('u', 'xhtml', 'xhtml');

            $dql    = "SELECT u.zt_id , t.id, t.name , t.displayname , t.description , t.type , t.type ,
                t.version , t.admin , t.user , t.system , t.state , t.xhtml
                FROM zselex_themes u , themes t 
                WHERE t.id=u.theme_id";
            $query  = $this->_em->createNativeQuery($dql, $rsm);
            $result = $query->getResult(2);

            // echo "<pre>"; print_r($result); echo "</pre>";
        } catch (\Exception $e) {
            // echo 'Message: ' . $e->getMessage();
            // exit;
        }

        return $result;
    }

    function updateMainShop($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $getShops = $this->fetchAll(array(
            'entity' => 'ZSELEX_Entity_ShopOwner',
            'fields' => array(
                'b.shop_id'
            ),
            'joins' => array(
                'JOIN a.shop b'
            ),
            'where' => 'a.shop!='.$args ['shop_id'].' AND a.user_id='.$args ['owner_id']
        ));

        if (!empty($getShops)) {
            foreach ($getShops as $val) {
                $shopIdArr [] = $val ['shop_id'];
            }

            $shop_ids = implode(',', $shopIdArr);

            // echo "<pre>"; print_r($getShops); echo "</pre>"; exit;

            $query = "UPDATE ZSELEX_Entity_Shop a
             SET a.main=0 
             WHERE a.shop_id IN($shop_ids)";

            // echo $query; exit;
            $query      = $this->_em->createQuery($query);
            // $query->setParameter('shop', $args['shop_id']);
            // $query->setParameter('owner', $args['owner_id']);
            $numUpdated = $query->execute();
        }
        return true;
    }

    function renameFolder()
    {
        // echo "hellooo8o"; exit;
        // echo "start :" . microtime(true) . '<br>';
        // $repo = $this->_em->getRepository('ZSELEX_Entity_Shop');
        $getArgs  = array(
            'entity' => 'ZSELEX_Entity_Shop',
            'fields' => array(
                'a.shop_id'
            )
        );
        $getShops = $this->getAll($getArgs);
        foreach ($getShops as $val) {
            $shop_id     = $val ['shop_id'];
            // $owner = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwnerInfo', array('shop_id' => $shop_id));
            $owner       = $this->_em->getRepository('ZSELEX_Entity_ShopOwner')->getOwnerInfo(array(
                'shop_id' => $shop_id
            ));
            $ownerName   = $owner ['uname'];
            /*
             * $shopfolder = $_SERVER['DOCUMENT_ROOT'] . "/zselexdata/" . $val['shop_id'];
             * $ownerfolder = $_SERVER['DOCUMENT_ROOT'] . "/zselexdata/" . $ownerName;
             * if ($_SERVER['SERVER_NAME'] == 'localhost') {
             * $shopfolder = "zselexdata/" . $val['shop_id'];
             * $ownerfolder = "zselexdata/" . $ownerName;
             * }
             */
            $shopfolder  = "zselexdata/".$val ['shop_id'];
            $ownerfolder = "zselexdata/".$ownerName;
            if (!file_exists($shopfolder)) {
                mkdir($shopfolder, 0775, true);
                chmod($shopfolder, 0775);
            }

            // products
            if (!file_exists($shopfolder.'/products')) {
                mkdir($shopfolder.'/products', 0775, true);
                chmod($shopfolder.'/products', 0775);
            }
            if (!file_exists($shopfolder.'/minisiteimages')) {
                mkdir($shopfolder.'/minisiteimages', 0775, true);
                chmod($shopfolder.'/minisiteimages', 0775);
            }

            if (!file_exists($shopfolder.'/products/fullsize')) {
                mkdir($shopfolder.'/products/fullsize', 0775, true);
                chmod($shopfolder.'/products/fullsize', 0775);
            }
            if (!file_exists($shopfolder.'/products/medium')) {
                mkdir($shopfolder.'/products/medium', 0775, true);
                chmod($shopfolder.'/products/medium', 0775);
            }

            if (!file_exists($shopfolder.'/products/thumb')) {
                mkdir($shopfolder.'/products/thumb', 0775, true);
                chmod($shopfolder.'/products/thumb', 0775);
            }

            // events
            if (!file_exists($shopfolder.'/events')) {
                mkdir($shopfolder.'/events', 0775, true);
                chmod($shopfolder.'/events', 0775);
            }
            if (!file_exists($shopfolder.'/events/docs')) {
                mkdir($shopfolder.'/events/docs', 0775, true);
                chmod($shopfolder.'/events/docs', 0775);
            }
            if (!file_exists($shopfolder.'/events/fullsize')) {
                mkdir($shopfolder.'/events/fullsize', 0775, true);
                chmod($shopfolder.'/events/fullsize', 0775);
            }
            if (!file_exists($shopfolder.'/events/medium')) {
                mkdir($shopfolder.'/events/medium', 0775, true);
                chmod($shopfolder.'/events/medium', 0775);
            }
            if (!file_exists($shopfolder.'/events/thumb')) {
                mkdir($shopfolder.'/events/thumb', 0775, true);
                chmod($shopfolder.'/events/thumb', 0775);
            }

            // images
            if (!file_exists($shopfolder.'/minisiteimages')) {
                mkdir($shopfolder.'/minisiteimages', 0775, true);
                chmod($shopfolder.'/minisiteimages', 0775);
            }

            if (!file_exists($shopfolder.'/minisiteimages/fullsize')) {
                mkdir($shopfolder.'/minisiteimages/fullsize', 0775, true);
                chmod($shopfolder.'/minisiteimages/fullsize', 0775);
            }
            if (!file_exists($shopfolder.'/minisiteimages/medium')) {
                mkdir($shopfolder.'/minisiteimages/medium', 0775, true);
                chmod($shopfolder.'/minisiteimages/medium', 0775);
            }
            if (!file_exists($shopfolder.'/minisiteimages/thumb')) {
                mkdir($shopfolder.'/minisiteimages/thumb', 0775, true);
                chmod($shopfolder.'/minisiteimages/thumb', 0775);
            }

            // employees
            if (!file_exists($shopfolder.'/employees')) {
                mkdir($shopfolder.'/employees', 0775, true);
                chmod($shopfolder.'/employees', 0775);
            }

            if (!file_exists($shopfolder.'/employees/fullsize')) {
                mkdir($shopfolder.'/employees/fullsize', 0775, true);
                chmod($shopfolder.'/employees/fullsize', 0775);
            }
            if (!file_exists($shopfolder.'/employees/medium')) {
                mkdir($shopfolder.'/employees/medium', 0775, true);
                chmod($shopfolder.'/employees/medium', 0775);
            }
            if (!file_exists($shopfolder.'/employees/thumb')) {
                mkdir($shopfolder.'/employees/thumb', 0775, true);
                chmod($shopfolder.'/employees/thumb', 0775);
            }

            // banner
            if (!file_exists($shopfolder.'/banner')) {
                mkdir($shopfolder.'/banner', 0775, true);
                chmod($shopfolder.'/banner', 0775);
            }

            if (!file_exists($shopfolder.'/banner/resized')) {
                mkdir($shopfolder.'/banner/resized', 0775, true);
                chmod($shopfolder.'/banner/resized', 0775);
            }

            $this->productFolder($ownerfolder, $shopfolder, $shop_id);
            $this->imageFolder($ownerfolder, $shopfolder, $shop_id);
            $this->employeeFolder($ownerfolder, $shopfolder, $shop_id);
            $this->bannerFolder($ownerfolder, $shopfolder, $shop_id);
        }

        // echo "start :" . microtime(true) . '<br>';
        // exit;

        return true;
    }

    function productFolder($ownerfolder, $shopfolder, $shop_id)
    {
        $product = $this->getAll(array(
            'entity' => 'ZSELEX_Entity_Product',
            'fields' => array(
                'a.prd_image'
            ),
            'where' => array(
                'a.shop' => $shop_id
            )
        ));

        // echo "<pre>"; print_r($product); echo "</pre><br>"; exit;
        foreach ($product as $item) {
            if (file_exists($ownerfolder.'/products/fullsize/'.$item ['prd_image'])) {
                if (!file_exists($shopfolder.'/products/fullsize/'.$item ['prd_image'])) {
                    copy($ownerfolder.'/products/fullsize/'.$item ['prd_image'],
                        $shopfolder.'/products/fullsize/'.$item ['prd_image']);
                }
            }
            if (file_exists($ownerfolder.'/products/medium/'.$item ['prd_image'])) {
                if (!file_exists($shopfolder.'/products/medium/'.$item ['prd_image'])) {
                    copy($ownerfolder.'/products/medium/'.$item ['prd_image'],
                        $shopfolder.'/products/medium/'.$item ['prd_image']);
                }
            }
            if (file_exists($ownerfolder.'/products/thumb/'.$item ['prd_image'])) {
                if (!file_exists($shopfolder.'/products/thumb/'.$item ['prd_image'])) {
                    copy($ownerfolder.'/products/thumb/'.$item ['prd_image'],
                        $shopfolder.'/products/thumb/'.$item ['prd_image']);
                }
            }
        }
        return true;
    }

    function imageFolder($ownerfolder, $shopfolder, $shop_id)
    {
        $images = $this->getAll(array(
            'entity' => 'ZSELEX_Entity_MinisiteImage',
            'fields' => array(
                'a.name'
            ),
            'where' => array(
                'a.shop' => $shop_id
            )
        ));

        foreach ($images as $item) {
            if (file_exists($ownerfolder.'/minisiteimages/fullsize/'.$item ['name'])) {
                if (!file_exists($shopfolder.'/minisiteimages/fullsize/'.$item ['name'])) {
                    copy($ownerfolder.'/minisiteimages/fullsize/'.$item ['name'],
                        $shopfolder.'/minisiteimages/fullsize/'.$item ['name']);
                }
            }
            if (file_exists($ownerfolder.'/minisiteimages/medium/'.$item ['name'])) {
                if (!file_exists($shopfolder.'/minisiteimages/medium/'.$item ['name'])) {
                    copy($ownerfolder.'/minisiteimages/medium/'.$item ['name'],
                        $shopfolder.'/minisiteimages/medium/'.$item ['name']);
                }
            }
            if (file_exists($ownerfolder.'/minisiteimages/thumb/'.$item ['name'])) {
                if (!file_exists($shopfolder.'/minisiteimages/thumb/'.$item ['name'])) {
                    copy($ownerfolder.'/minisiteimages/thumb/'.$item ['name'],
                        $shopfolder.'/minisiteimages/thumb/'.$item ['name']);
                }
            }
        }
        return true;
    }

    function employeeFolder($ownerfolder, $shopfolder, $shop_id)
    {
        $employees = $this->getAll(array(
            'entity' => 'ZSELEX_Entity_Employee',
            'fields' => array(
                'a.emp_image'
            ),
            'where' => array(
                'a.shop' => $shop_id
            )
        ));

        foreach ($employees as $item) {
            if (file_exists($ownerfolder.'/employees/fullsize/'.$item ['emp_image'])) {
                if (!file_exists($shopfolder.'/employees/fullsize/'.$item ['emp_image'])) {
                    copy($ownerfolder.'/employees/fullsize/'.$item ['emp_image'],
                        $shopfolder.'/employees/fullsize/'.$item ['emp_image']);
                }
            }
            if (file_exists($ownerfolder.'/employees/medium/'.$item ['name'])) {
                if (!file_exists($shopfolder.'/employees/medium/'.$item ['name'])) {
                    copy($ownerfolder.'/employees/medium/'.$item ['name'],
                        $shopfolder.'/employees/medium/'.$item ['emp_image']);
                }
            }
            if (file_exists($ownerfolder.'/employees/thumb/'.$item ['name'])) {
                if (!file_exists($shopfolder.'/employees/thumb/'.$item ['name'])) {
                    copy($ownerfolder.'/employees/thumb/'.$item ['name'],
                        $shopfolder.'/employees/thumb/'.$item ['emp_image']);
                }
            }
        }
        return true;
    }

    function bannerFolder($ownerfolder, $shopfolder, $shop_id)
    {
        $item = $this->get(array(
            'entity' => 'ZSELEX_Entity_Banner',
            'fields' => array(
                'a.banner_image'
            ),
            'where' => array(
                'a.shop' => $shop_id
            )
        ));

        if (file_exists($ownerfolder.'/banner/resized/'.$item ['banner_image'])) {
            if (!file_exists($shopfolder.'/banner/resized/'.$item ['banner_image'])) {
                copy($ownerfolder.'/banner/resized/'.$item ['banner_image'],
                    $shopfolder.'/banner/resized/'.$item ['banner_image']);
            }
        }

        return true;
    }

    function insertKeyword1()
    { //
        $statement = Doctrine_Manager::getInstance()->connection();
        $sql       = "INSERT INTO zselex_keywords(keyword , type)VALUES('testkk' , 'testkk')";
        $query     = $statement->execute($sql);
        return true;
    }

    /**
     * Insert/Update the keyword table.triggeres in background process
     * fetches all shops from shop table and inserts/update the keywords
     *
     * @return boolean
     */
    function insertKeyword()
    {
        set_time_limit(0);
        error_reporting(0);
        $server  = $_SERVER ['SERVER_NAME'];
        $message = 'Keyword update script task is completed at the background.Please check the keywords<br><br>';

        $message .= 'Server : '.$server.'<br>';
        $message .= 'Start Date : '.date('Y-m-d h:i:s a', time()).'<br>';
        $getArgs   = array(
            'entity' => 'ZSELEX_Entity_Shop',
            'fields' => array(
                'a.shop_name',
                'a.shop_id'
            ),
            'where' => array('a.status' => 1)
        );
        $getShops  = $this->getAll($getArgs);
        $keyRepo   = $this->_em->getRepository('ZSELEX_Entity_Keyword');
        $batchSize = 20;
        $i         = 0;
        foreach ($getShops as $val) {
            $keyword = $val ['shop_name'];
            $shop_id = $val ['shop_id'];
            $type    = 'shop';
            $type_id = $val ['shop_id'];
            $this->deleteEntity(null, 'ZSELEX_Entity_Keyword',
                array(
                'a.type_id' => $val ['shop_id']
            ));
            /*
             * $keywordExist = $this->getCount(null, 'ZSELEX_Entity_Keyword', 'keyword_id'
             * , array('a.keyword' => $keyword));
             */

            $keywordExist = $this->getCount(null, 'ZSELEX_Entity_Keyword',
                'keyword_id',
                array(
                'a.type_id' => $val ['shop_id']
            ));

            if ($keywordExist < 1) {
                if (!empty($keyword)) {
                    /*
                     * $keyword_item = array(
                     * 'keyword' => $keyword,
                     * 'type' => 'shop',
                     * 'type_id' => $shop_id,
                     * 'shop_id' => $shop_id
                     * );
                     *
                     * $result_keyword = $keyRepo->createKeyword($keyword_item);
                     */
                    $keywordEntity = new ZSELEX_Entity_Keyword ();
                    $keywordEntity->setKeyword($keyword);
                    $keywordEntity->setType($type);
                    $keywordEntity->setType_id($type_id);
                    $shop          = $this->_em->find('ZSELEX_Entity_Shop',
                        $shop_id);
                    $keywordEntity->setShop($shop);
                    $this->_em->persist($keywordEntity);
                    // if (($i % $batchSize) === 0) {
                    $this->_em->flush();
                    $this->_em->clear();
                    // }
                }
            } else {
                $updItem       = array(
                    'keyword' => $keyword,
                    'type' => $type,
                    'type_id' => $type_id,
                    'shop' => $shop_id
                );
                $keywordUpdate = $this->updateEntity(null,
                    'ZSELEX_Entity_Keyword', $updItem,
                    array(
                    'a.keyword' => $keyword
                ));
            }
            $i ++;
        }
        $this->_em->flush();
        $this->_em->clear();

        $emails [] = 'sharazkhanz@gmail.com';
        $emails [] = 'kim@acta-it.dk';

        $message .= 'End Date : '.date('Y-m-d h:i:s a', time()).'<br>';
        foreach ($emails as $email) {
            $mailer_args = array(
                'toaddress' => $email,
                'fromname' => 'ZSELEX',
                'subject' => 'Keyword Update Script',
                'body' => $message,
                'html' => true
            );

            $sent = ModUtil::apiFunc('Mailer', 'user', 'sendMessage',
                    $mailer_args);
        }
        return true;
    }

    function updateAdditionalBundles()
    {
        $repo = $this->_em->getRepository('ZSELEX_Entity_Shop');

        $bundles = $repo->getAll(array(
            'entity' => 'ZSELEX_Entity_ServiceBundle',
            'fields' => array(
                'b.shop_id',
                'c.bundle_id',
                'c.bundle_type',
                'a.service_status',
                'a.timer_date',
                'a.timer_days'
            ),
            'joins' => array(
                'JOIN a.shop b',
                'JOIN a.bundle c'
            ),
            // 'where' => array("a.bundle_type" => 'additional'),
            'groupby' => 'a.service_bundle_id'
        ));
        // echo "<pre>"; print_r($bundles); echo "</pre>"; exit;
        foreach ($bundles as $value) {
            $bundle_id = $value ['bundle_id'];
            $shop_id   = $value ['shop_id'];
            if ($value ['bundle_type'] == 'additional') {
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
                        'a.qty_based'
                    ),
                    'joins' => array(
                        'JOIN a.bundle b',
                        'LEFT JOIN a.plugin c'
                    ),
                    'where' => array(
                        'a.bundle' => $bundle_id,
                        "b.bundle_type" => 'additional'
                    ),
                    'groupby' => 'a.id'
                ));
                // echo "<pre>"; print_r($bundleitems); echo "</pre>"; exit;
                foreach ($bundleitems as $bitem) {
                    $updObj = array(
                        'bundle' => $bundle_id,
                        'bundle_type' => 'additional'
                    );
                    $where  = array(
                        'a.shop' => $shop_id,
                        'a.type' => $bitem ['servicetype']
                    );
                    $update = $repo->updateEntity(null,
                        'ZSELEX_Entity_ServiceShop', $updObj, $where);
                }
            } else {
                $updObj2 = array(
                    'bundle_type' => 'main'
                );
                $where2  = array(
                    'a.shop' => $shop_id,
                    'a.bundle' => $bundle_id
                );
                $update2 = $repo->updateEntity(null,
                    'ZSELEX_Entity_ServiceShop', $updObj2, $where2);
            }
        }
        return true;
    }

    public function getFrontShopListing($args)
    {
        try {
            $limit     = $args ['limit'];
            $orderby   = $args ['orderby'];
            $append    = $args ['append'];
            $setParams = $args ['setParams'];
            $rsm       = new ORM\Query\ResultSetMapping ();

            $rsm->addEntityResult('ZSELEX_Entity_Shop', 'a');
            $rsm->addMetaResult('a', 'shop_id', 'shopid');
            $rsm->addFieldResult('a', 'shop_name', 'shop_name');
            $rsm->addFieldResult('a', 'default_img_frm', 'default_img_frm');
            $rsm->addMetaResult('a', 'aff_id', 'aff_id');

            $rsm->addMetaResult('a', 'LEFT(a.description, 40)', 'description');
            $rsm->addMetaResult('a', 'round((sum(c.rating)/COUNT(c.rating)),1)',
                'rating');
            $rsm->addMetaResult('a', 'COUNT(c.rating)', 'votes');

            $rsm->addMetaResult('a', 'city_name', 'city_name');
            $rsm->addMetaResult('a', 'rating', 'rating');
            $rsm->addMetaResult('a', 'uname', 'uname');
            $rsm->addMetaResult('a', 'image_name', 'image_name');
            $rsm->addMetaResult('a', 'name', 'name');

            $dql    = "SELECT a.shop_id , a.shop_name , LEFT(a.description, 40) , a.default_img_frm , a.aff_id ,
                b.city_name , round((sum(c.rating)/COUNT(c.rating)),1) , COUNT(c.rating) ,
                e.uname , f.image_name , g.name
                FROM zselex_shop a
                LEFT JOIN zselex_city b ON a.city_id=b.city_id
                LEFT JOIN zselex_shop_ratings c ON c.shop_id=a.shop_id
                LEFT JOIN zselex_shop_owners d ON d.shop_id=a.shop_id
                LEFT JOIN users e ON e.uid=d.user_id
                LEFT JOIN zselex_shop_gallery f ON a.shop_id=f.shop_id AND f.defaultImg=1
                LEFT JOIN zselex_files g ON a.shop_id=g.shop_id AND g.defaultImg=1
                WHERE a.shop_id IS NOT NULL 
                AND a.shop_id IN (".ModUtil::apiFunc('ZSELEX', 'admin',
                    'shopids',
                    array(
                    'append' => $append,
                    'type' => 'minisite',
                    'limit' => $limit,
                    'setParams' => $setParams
                )).")
                GROUP BY a.shop_id
                $orderby 
                LIMIT 0,$limit";
            // echo $dql; exit;
            $query  = $this->_em->createNativeQuery($dql, $rsm);
            $query->useResultCache(true);
            $result = $query->getArrayResult();
            return $result;
        } catch (\Exception $e) {
            echo 'Message: '.$e->getMessage();
            // exit;
        }
    }

    function backgroundShops()
    {
        set_time_limit(0);
        $statement = Doctrine_Manager::getInstance()->connection();

        for ($i = 1; $i <= 100000; $i ++) {
            $query = "INSERT INTO zselex_shop(title , urltitle , country_id , shop_name ,  status)VALUES('test$i' , 'test$i' , 61 , 'test$i' , 1)";
            $query = $statement->execute($query);
        }
        return true;
    }

    function repairServiceShopTable1()
    {
        $getArgs     = array(
            'entity' => 'ZSELEX_Entity_ServiceShop',
            'fields' => array(
                'a.type',
                'b.shop_id',
                'c.qty_based'
            ),
            'joins' => array(
                'JOIN a.shop b',
                'JOIN a.plugin c'
            )
        );
        $getServices = $this->getAll($getArgs);

        // echo "<pre>"; print_r($getServices); echo "</pre>"; exit;
        foreach ($getServices as $key => $val) {
            if ($val ['type'] == 'employees') {
                $employeeCount = $this->getCount(null, 'ZSELEX_Entity_Employee',
                    'emp_id',
                    array(
                    "a.shop" => $val ['shop_id']
                ));
                echo 'shop :'.$val ['shop_id'].'  Employee : '.$employeeCount.'<br>';
                // $updateEmployeeUsage = $this->updateEntity(null, 'ZSELEX_Entity_ServiceShop', array("availed" => $employeeCount), array('a.shop' => $shop_id , "a.type"=>"employees"));
            } elseif ($val ['type'] == 'addproducts') {
                $productCount = $this->getCount(null, 'ZSELEX_Entity_Product',
                    'product_id',
                    array(
                    "a.shop" => $val ['shop_id']
                ));
                echo 'shop :'.$val ['shop_id'].'  Product : '.$eventCount.'<br>';
                // $productUsage = $this->updateEntity(null, 'ZSELEX_Entity_ServiceShop', array("availed" => $productCount), array('a.shop' => $shop_id ,"a.type"=>"addproducts"));
            } elseif ($val ['type'] == 'eventservice') {
                $eventCount = $this->getCount(null, 'ZSELEX_Entity_Event',
                    'shop_event_id',
                    array(
                    "a.shop" => $val ['shop_id']
                ));
                echo 'shop :'.$val ['shop_id'].'  Event : '.$eventCount.'<br>';
                // $eventUsage = $this->updateEntity(null, 'ZSELEX_Entity_ServiceShop', array("availed" => $eventCount), array('a.shop' => $shop_id ,"a.type"=>"eventservice"));
            } elseif ($val ['type'] == 'minisiteimages') {
                $imageCount = $this->getCount(null,
                    'ZSELEX_Entity_MinisiteImage', 'file_id',
                    array(
                    "a.shop" => $val ['shop_id']
                ));
                echo 'shop :'.$val ['shop_id'].'  Image : '.$eventCount.'<br>';
                // $imageUsage = $this->updateEntity(null, 'ZSELEX_Entity_ServiceShop', array("availed" => $imageCount), array('a.shop' => $shop_id ,"a.type"=>"minisiteimages"));
            } elseif ($val ['type'] == 'createad') {
                $adCount = $this->getCount(null, 'ZSELEX_Entity_Advertise',
                    'advertise_id',
                    array(
                    "a.shop" => $val ['shop_id']
                ));
                echo 'shop :'.$val ['shop_id'].'  Image : '.$adCount.'<br>';
                $adUsage = $this->updateEntity(null,
                    'ZSELEX_Entity_ServiceShop',
                    array(
                    "availed" => $adCount
                    ),
                    array(
                    'a.shop' => $shop_id,
                    "a.type" => "createad"
                ));
            }
        }
    }

    function repairServiceShopTable()
    {
        $repo = $this->_em->getRepository('ZSELEX_Entity_Shop');

        $getShopArgs = array(
            'entity' => 'ZSELEX_Entity_ServiceBundle',
            'fields' => array(
                'b.shop_id'
            ),
            'joins' => array(
                'JOIN a.shop b'
            ),
            'groupby' => 'a.shop'
        );
        $getShops    = $this->getAll($getShopArgs);
        // echo "<pre>"; print_r($getShops); echo "</pre>"; exit;
        foreach ($getShops as $shop) {
            $repo->deleteEntity(null, 'ZSELEX_Entity_ServiceShop',
                array(
                'a.shop' => $shop ['shop_id']
            ));
            $getArgs     = array(
                'entity' => 'ZSELEX_Entity_ServiceBundle',
                'fields' => array(
                    'a.quantity',
                    'a.service_status',
                    'a.timer_date',
                    'a.timer_days',
                    'b.bundle_id',
                    'a.bundle_type',
                    'c.shop_id'
                ),
                'joins' => array(
                    'JOIN a.bundle b',
                    'JOIN a.shop c'
                ),
                'where' => array(
                    'a.shop' => $shop ['shop_id']
                )
            );
            $getServices = $this->getAll($getArgs);
            // echo "<pre>"; print_r($getServices); echo "</pre>"; exit;
            foreach ($getServices as $key => $val) {
                // $shop_id = $val['shop_id'];

                $args ['bundle_id']      = $val ['bundle_id'];
                $args ['quantity']       = $val ['quantity'];
                $args ['top_bundle']     = 1;
                $args ['timer_days']     = $val ['timer_days'];
                $args ['timer_date']     = $val ['timer_date'];
                $args ['shop_id']        = $val ['shop_id'];
                $args ['service_status'] = $val ['service_status'];

                // $configure = $this->addToDemo($args);
                $configure = ModUtil::apiFunc('ZSELEX', 'service', 'addService',
                        $args);
            }
        }

        return true;
    }

    public function getShopList($args)
    {

        // echo "<pre>"; print_r($args); echo "</pre>";
        try {
            $limit     = $args ['limit'];
            $orderby   = $args ['orderby'];
            $append    = $args ['append'];
            $joins     = $args ['joins'];
            $setParams = $args ['setParams'];
            $rsm       = new ORM\Query\ResultSetMapping ();

            $rsm->addEntityResult('ZSELEX_Entity_Shop', 'a');
            $rsm->addMetaResult('a', 'shop_id', 'shopid');
            $rsm->addFieldResult('a', 'shop_name', 'shop_name');
            $rsm->addFieldResult('a', 'default_img_frm', 'default_img_frm');
            $rsm->addMetaResult('a', 'aff_id', 'aff_id');

            $rsm->addMetaResult('a', 'LEFT(a.description, 40)', 'description');
            $rsm->addMetaResult('a', 'round((sum(c.rating)/COUNT(c.rating)),1)',
                'rating');
            $rsm->addMetaResult('a', 'COUNT(c.rating)', 'votes');

            $rsm->addMetaResult('a', 'city_name', 'city_name');
            $rsm->addMetaResult('a', 'rating', 'rating');
            $rsm->addMetaResult('a', 'uname', 'uname');
            $rsm->addMetaResult('a', 'image_name', 'image_name');
            $rsm->addMetaResult('a', 'name', 'name');
            $rsm->addMetaResult('a', 'type', 'minishop');

            $today = date("Y-m-d");
            $dql   = "SELECT a.shop_id , a.shop_name , LEFT(a.description, 40) , a.default_img_frm , a.aff_id ,
              b.city_name , round((sum(c.rating)/COUNT(c.rating)),1) , COUNT(c.rating) ,
              e.uname , f.image_name , g.name , sv2.type 
              FROM zselex_shop a
              INNER JOIN zselex_serviceshop sv ON sv.shop_id=a.shop_id
              $joins
              LEFT JOIN zselex_city b ON a.city_id=b.city_id
              LEFT JOIN zselex_shop_ratings c ON c.shop_id=a.shop_id
              LEFT JOIN zselex_shop_owners d ON d.shop_id=a.shop_id
              LEFT JOIN users e ON e.uid=d.user_id
              LEFT JOIN zselex_shop_gallery f ON a.shop_id=f.shop_id AND f.defaultImg=1
              LEFT JOIN zselex_files g ON a.shop_id=g.shop_id AND g.defaultImg=1
              LEFT JOIN zselex_serviceshop sv2 ON sv2.shop_id=a.shop_id AND (DATEDIFF('$today' , sv2.timer_date) <= sv2.timer_days) AND sv2.type='minishop'
              WHERE a.shop_id IS NOT NULL
              AND ((DATEDIFF('$today' , sv.timer_date) <= sv.timer_days) OR sv.service_status=3)
              AND sv.type='minisite'
              ".$append." 
              GROUP BY a.shop_id
              $orderby
              LIMIT 0,$limit";
            // echo $dql . '<br>';
            $query = $this->_em->createNativeQuery($dql, $rsm);
            if (isset($setParams) && !empty($setParams)) {
                $query->setParameters($setParams);
            }
            $query->useResultCache(true);
            $result = $query->getArrayResult();
            // echo "<pre>"; print_r($result); echo "</pre>";
            return $result;
        } catch (\Exception $e) {
            // echo 'Message: ' . $e->getMessage();
            // exit;
        }
    }
}
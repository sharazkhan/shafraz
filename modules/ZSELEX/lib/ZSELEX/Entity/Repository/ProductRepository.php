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
class ZSELEX_Entity_Repository_ProductRepository extends ZSELEX_Entity_Repository_General
{

    /**
     * Update a product
     * 
     * @param type $args
     * @return boolean
     */
    function updateProduct($args)
    {
        // $date = date("Y-m-d");
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $qb = $this->_em->createQueryBuilder();
        $q  = $qb->update('ZSELEX_Entity_Product', 'u')
            ->set('u.product_id', '?1')
            ->set('u.shop', '?2')
            ->set('u.product_name', '?3')
            ->set('u.urltitle', '?4')
            ->set('u.prd_description', '?5')
            ->set('u.keywords', '?6')

            // ->set('u.category', '?7')
            ->set('u.original_price', '?8')
            ->set('u.prd_price', '?9')
            ->set('u.discount', '?10')
            ->set('u.prd_quantity', '?11')
            ->set('u.prd_status', '?12')
            ->set('u.manufacturer', '?13')
            ->set('u.shipping_price', '?14')
            ->set('u.enable_question', '?15')
            ->set('u.prd_question', '?16')
            ->set('u.validate_question', '?17')
            ->set('u.no_vat', '?18')
            ->set('u.advertise', '?19')
            ->set('u.max_discount', '?20')
            ->set('u.no_delivery', '?21')
            ->where('u.product_id = ?1')
            ->setParameter(1, $args ['product_id'])
            ->setParameter(2, $args ['shop_id'])
            ->setParameter(3, $args ['product_name'])
            ->setParameter(4, $args ['urltitle'])
            ->setParameter(5, $args ['prd_description'])
            ->setParameter(6, $args ['keywords'])

            // ->setParameter(7, $args['category_id'])
            ->setParameter(8, $args ['original_price'])
            ->setParameter(9, $args ['prd_price'])
            ->setParameter(10, $args ['discount'])
            ->setParameter(11, $args ['prd_quantity'])
            ->setParameter(12, $args ['prd_status'])
            ->setParameter(13, $args ['manufacturer_id'])
            ->setParameter(14, $args ['shipping_price'])
            ->setParameter(15, $args ['enable_question'])
            ->setParameter(16, $args ['prd_question'])
            ->setParameter(17, $args ['validate_question'])
            ->setParameter(18, $args ['no_vat'])
            ->setParameter(19, $args ['advertise'])
            ->setParameter(20, $args ['max_discount'])
            ->setParameter(21, $args ['no_delivery'])
            ->getQuery();
        $p = $q->execute();

        // return $p;
        return true;
    }

    public function getManufacturers($args)
    {
        $shop_id = $args ['shop_id'];
        $fetch   = $args ['fetch'];
        $dql     = "SELECT a.manufacturer_id , a.manufacturer_name
            FROM ZSELEX_Entity_Manufacturer a 
            WHERE a.status=1 AND a.shop = :shop_id";
        $query   = $this->_em->createQuery($dql);
        $query->setParameter('shop_id', $shop_id);
        if ($fetch == 'row') {
            $result = $query->getOneOrNullResult();
        } else {
            $result = $query->getResult();
        }

        return $result;
    }

    public function getManufacturerById($args)
    {
        $mnfr_id = $args ['mnfr_id'];

        $dql   = "SELECT a.manufacturer_id , a.manufacturer_name , a.status , b.shop_id
                FROM ZSELEX_Entity_Manufacturer a 
                JOIN a.shop b
                WHERE a.manufacturer_id = :mnfr_id";
        $query = $this->_em->createQuery($dql);
        $query->setParameter('mnfr_id', $mnfr_id);

        $result = $query->getOneOrNullResult();

        return $result;
    }

    public function manufacturer_count($args)
    {
        $shop_id = $args ['shop_id'];
        $name    = $args ['name'];
        $query   = $this->_em->createQuery('SELECT COUNT(a.manufacturer_id) FROM ZSELEX_Entity_Manufacturer a '.'WHERE a.shop=:shop_id AND a.manufacturer_name=:name');
        $query->setParameter('shop_id', $shop_id);
        $query->setParameter('name', $name);
        $count   = $query->getSingleScalarResult();
        return $count;
    }

    public function getManufacturerList($args)
    {

        // echo "<pre>"; print_r($args); echo "</pre>";
        // echo "comes here api!!!";
        $shop_id    = $args ['shop_id'];
        $order      = " a.".$args ['order'];
        $searchtext = $args ['searchtext'];
        // $searchtext = addslashes($searchtext);
        $status     = $args ['status'];
        $orderdir   = $args ['orderdir'];
        $offset     = $args ['offset'];
        $maxResults = $args ['maxResults'];
        $sql        = '';
        if (!empty($searchtext)) {

            $sql .= " AND a.manufacturer_name LIKE :searchtext";
        }
        if (isset($status) && $status != "") {
            $sql .= " AND a.status=:status";
        }
        $dql   = "SELECT a.manufacturer_id , a.manufacturer_name , a.shop_id , a.status FROM ZSELEX_Entity_Manufacturer a
            WHERE a.shop_id = :shop_id 
            $sql
            ORDER BY $order $orderdir";
        // echo $dql;
        $query = $this->_em->createQuery($dql);
        $query->setParameter('shop_id', $shop_id);
        if (isset($status) && $status != "") {
            $query->setParameter('status', $status);
        }
        if (!empty($searchtext)) {
            $searchword = "%".$searchtext."%";
            $query->setParameter('searchtext', $searchword);
        }

        if ($offset > 0) {
            $query->setFirstResult($offset);
        }
        $query->setMaxResults($maxResults);
        // echo $query->getSQL();
        $result = $query->getResult(2);
        // echo "<pre>"; print_r($result); echo "</pre>";
        return $result;
    }

    public function getManufacturerCount($args)
    {
        $shop_id    = $args ['shop_id'];
        $searchtext = $args ['searchtext'];
        $status     = $args ['status'];

        $sql = '';
        if (!empty($searchtext)) {
            $sql .= " AND a.manufacturer_name LIKE :searchtext";
        }
        if (isset($status) && $status != "") {
            $sql .= " AND a.status=:status";
        }
        $dql = "SELECT COUNT(a.manufacturer_id) FROM ZSELEX_Entity_Manufacturer a WHERE a.shop=:shop_id ".$sql;

        $query = $this->_em->createQuery($dql);
        $query->setParameter('shop_id', $shop_id);
        if (isset($status) && $status != "") {
            $query->setParameter('status', $status);
        }
        if (!empty($searchtext)) {
            $searchword = "%".$searchtext."%";
            $query->setParameter('searchtext', $searchword);
        }

        $count = $query->getSingleScalarResult();
        return $count;
    }

    function updateManufacturer($args)
    {
        // $date = date("Y-m-d");
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $qb = $this->_em->createQueryBuilder();
        $q  = $qb->update('ZSELEX_Entity_Manufacturer', 'u')->set('u.manufacturer_name',
                '?2')->set('u.status', '?3')->where('u.manufacturer_id = ?1')->setParameter(1,
                $args ['elemId'])->setParameter(2, $args ['elemtName'])->setParameter(3,
                $args ['status'])->getQuery();
        $p  = $q->execute();

        return $p;
    }

    public function deleteManufacturer($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $manufacturer_id = $args ['manufacturer_id'];
        $query           = $this->_em->createQuery('delete from ZSELEX_Entity_Manufacturer m where m.manufacturer_id=:manufacturer_id');
        $query->setParameter('manufacturer_id', $manufacturer_id);
        $numDeleted      = $query->execute();
        return $numDeleted;
    }

    public function getProducts($args)
    {
        $shop_id    = $args ['shop_id'];
        $product_id = $args ['product_id'];

        $dql    = "SELECT a.product_id , a.product_name , a.prd_description , b.shop_id , c.manufacturer_id,
                a.keywords , a.original_price , a.prd_price , a.discount , a.prd_quantity
                FROM ZSELEX_Entity_Product a
                LEFT JOIN a.shop b
                LEFT JOIN a.manufacturer c
                WHERE a.prd_status=1 AND a.shop = :shop_id AND a.product_id! = :product_id";
        $query  = $this->_em->createQuery($dql);
        $query->setParameter('shop_id', $shop_id);
        $query->setParameter('product_id', $product_id);
        $result = $query->getResult();

        return $result;
    }

    /**
     * Get a single product
     *
     * @param int $args['product_id']
     * @return array
     */
    public function getProduct($args)
    {

        //echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $product_id    = $args ['product_id'];
        $product_title = $args ['product_title'];
        if (isset($product_id) && !empty($product_id)) {
            $where = " a.product_id=:product_id ";
        } else {
            $where = " a.urltitle=:urltitle ";
        }

        // echo $where; exit;
        $dql   = "SELECT a.product_id , a.product_name , a.prd_description , a.prd_image ,b.shop_id , b.shop_name, c.manufacturer_id,
                a.keywords , a.original_price , a.prd_price , a.discount , a.prd_quantity , a.validate_question , a.prd_question , a.enable_question,
                d.city_name
                FROM ZSELEX_Entity_Product a
                LEFT JOIN a.shop b
                LEFT JOIN a.manufacturer c
                LEFT JOIN b.city d
                WHERE a.prd_status=1 AND $where";
        // echo $dql; exit;
        $query = $this->_em->createQuery($dql);
        // $query->setParameter('shop_id', $shop_id);
        if (isset($product_id) && !empty($product_id)) {
            $query->setParameter('product_id', $product_id);
        } else {
            $query->setParameter('urltitle', $product_title);
        }
        $result = $query->getOneOrNullResult();
        if (!$result) {
            // echo "not found"; exit;
            $oldUrlArr = array(
                'entity' => 'ZSELEX_Entity_Url',
                'where' => array(
                    'a.type' => 'product',
                    'a.url' => $product_title
                )
            );
            $getOldUrl = $this->get($oldUrlArr);
            // echo "<pre>"; print_r($getOldUrl); echo "</pre>"; exit;
            //
			if ($getOldUrl) {
                $url = pnGetBaseURL().ModUtil::url('ZSELEX', 'user',
                        'productview',
                        array(
                        'id' => $getOldUrl ['type_id'],
                        'shop_id' => $getOldUrl ['shop_id']
                ));
                // echo $url; exit;
                // $this->redirect($url);
                System::redirect($url);
                die();
            }
        }

        return $result;
    }

    /**
     * Get products for Ad
     *
     * @return array of products
     */
    public function getAdProducts($args)
    {
        $shop_id     = $args ['shop_id'];
        $appnd_ishop = $args ['append'];
        $advSelected = $args ['adv_selected'];
        $rsm         = new ORM\Query\ResultSetMapping ();
        $rsm->addEntityResult('ZSELEX_Entity_Product', 'p');
        $rsm->addFieldResult('p', 'urltitle', 'urltitle');
        $rsm->addFieldResult('p', 'product_id', 'product_id');
        $rsm->addFieldResult('p', 'prd_price', 'prd_price');
        $rsm->addFieldResult('p', 'original_price', 'original_price');
        $rsm->addFieldResult('p', 'prd_image', 'prd_image');
        $rsm->addFieldResult('p', 'discount', 'discount');

        $rsm->addMetaResult('p', 'LEFT(p.product_name, 50)', 'product_name');
        $rsm->addMetaResult('p', 'LEFT(p.prd_description, 20)',
            'prd_description');
        $rsm->addMetaResult('p', 'theme', 'shopTheme');
        $rsm->addMetaResult('p', 'uname', 'uname');

        $result = array();

        $sql = '';
        if ($advSelected) {
            $sql .= " AND p.advertise=1 ";
        }
        $count = $this->getAdProductsCount($args);
        // echo $count . '<br>';
        if ($count) {
            $randNum = mt_rand(0, $count - 1);
            /*
             * $dql = "SELECT DISTINCT p.product_id , p.prd_price , p.urltitle , p.prd_image , LEFT(p.product_name, 50) , LEFT(p.prd_description, 20) , s.theme , u.uname
             * FROM zselex_products p , zselex_shop s
             * LEFT JOIN zselex_shop_owners ow ON ow.shop_id=s.shop_id
             * LEFT JOIN users u ON u.uid = ow.user_id
             * LEFT JOIN zselex_serviceshop sv ON sv.shop_id = s.shop_id AND sv.type='paybutton'
             * WHERE p.prd_status='1' " . $appnd_ishop . " AND p.shop_id='" . $shop_id . "' AND p.shop_id=s.shop_id
             * LIMIT $randNum , 1";
             */
            $dql     = "SELECT DISTINCT p.product_id , p.prd_price , p.original_price , p.discount ,p.urltitle , p.prd_image , LEFT(p.product_name, 50) , LEFT(p.prd_description, 20) , u.uname
                        FROM zselex_products p 
                        LEFT JOIN zselex_shop_owners ow ON ow.shop_id=p.shop_id and ow.co_owner=0
                        LEFT JOIN users u ON u.uid = ow.user_id
                        WHERE p.prd_status='1' ".$appnd_ishop." AND p.shop_id='".$shop_id."' $sql
                        LIMIT $randNum , 1";

            /*
             * $user_id = UserUtil::getVar('uid');
             * if($user_id==122){
             * echo "products : " . $dql . '<br><br>';
             * }
             */
            // echo $dql . '<br><br>';

            $query  = $this->_em->createNativeQuery($dql, $rsm);
            // $query->useResultCache(true);
            $result = $query->getOneOrNullResult(2);
        }
        return $result;
    }
    /*
     * Get count of the Ad products
     *
     * @return int - count
     */

    public function getAdProductsCount($args)
    {
        $shop_id     = $args ['shop_id'];
        $appnd_ishop = $args ['append'];
        $advSelected = $args ['adv_selected'];
        $sql         = '';
        if ($advSelected) {
            $sql .= " AND p.advertise=1 ";
        }
        $rsm = new ORM\Query\ResultSetMapping ();
        $rsm->addEntityResult('ZSELEX_Entity_Product', 'p');
        $rsm->addScalarResult('COUNT(*)', 'count');

        /*
         * $dql = "SELECT COUNT(*)
         * FROM zselex_products p , zselex_shop s
         * WHERE p.prd_status='1'
         * " . $appnd_ishop . "
         * AND p.shop_id='" . $shop_id . "'
         * AND p.shop_id=s.shop_id
         * ";
         */
        $dql = "SELECT COUNT(*)
                        FROM zselex_products p 
                        WHERE p.prd_status='1' 
                        ".$appnd_ishop." 
                        AND p.shop_id='".$shop_id."' $sql
                       
                         ";
        // echo $dql . '<br><br>';

        $query  = $this->_em->createNativeQuery($dql, $rsm);
        // $query->useResultCache(true);
        $result = $query->getSingleScalarResult();

        // echo "<pre>"; print_r($result); echo "</pre>";
        return $result;
    }

    public function getMinisiteProducts($args)
    {
        $shop_id = $args ['shop_id'];
        $orderby = $args ['orderby'];
        $limit   = $args ['limit'];
        $rsm     = new ORM\Query\ResultSetMapping ();
        $rsm->addEntityResult('ZSELEX_Entity_Product', 'p');
        $rsm->addFieldResult('p', 'urltitle', 'urltitle');
        $rsm->addFieldResult('p', 'product_id', 'product_id');
        $rsm->addFieldResult('p', 'prd_price', 'prd_price');
        $rsm->addFieldResult('p', 'prd_image', 'prd_image');
        $rsm->addFieldResult('p', 'prd_quantity', 'prd_quantity');
        $rsm->addFieldResult('p', 'original_price', 'original_price');
        $rsm->addFieldResult('p', 'discount', 'discount');

        $rsm->addMetaResult('p', 'LEFT(p.product_name, 50)', 'product_name');
        $rsm->addMetaResult('p', 'LEFT(p.prd_description, 20)',
            'prd_description');

        $dql = "SELECT p.product_id , p.prd_price , p.urltitle  , p.prd_image , LEFT(p.product_name, 50) , LEFT(p.prd_description, 20) ,
                        p.prd_quantity ,  p.original_price ,  p.discount 
                        FROM zselex_products p 
                        WHERE p.prd_status=1 AND p.shop_id='".$shop_id."' ORDER BY $orderby  LIMIT 0 , $limit";

        $query  = $this->_em->createNativeQuery($dql, $rsm);
        $query->useResultCache(true);
        $result = $query->getArrayResult();
        return $result;
    }

    public function getMinishopProducts($args)
    {
        $shop_id      = $args ['shop_id'];
        $cat_qry      = $args ['cat_qry'];
        $mnfr_qry     = $args ['mnfr_qry'];
        $searchWord   = $args ['search_word'];
        $startnum     = $args ['startnum'];
        $itemsperpage = $args ['itemsperpage'];
        $rsm          = new ORM\Query\ResultSetMapping ();
        $rsm->addEntityResult('ZSELEX_Entity_Product', 'p');
        $rsm->addFieldResult('p', 'urltitle', 'urltitle');
        $rsm->addFieldResult('p', 'product_id', 'product_id');
        $rsm->addFieldResult('p', 'prd_price', 'prd_price');
        $rsm->addFieldResult('p', 'prd_image', 'prd_image');
        $rsm->addFieldResult('p', 'prd_quantity', 'prd_quantity');
        $rsm->addFieldResult('p', 'enable_question', 'enable_question');
        $rsm->addFieldResult('p', 'original_price', 'original_price');
        $rsm->addFieldResult('p', 'discount', 'discount');
        $rsm->addFieldResult('p', 'prd_question', 'prd_question');

        $rsm->addMetaResult('p', 'LEFT(p.product_name, 50)', 'product_name');
        $rsm->addMetaResult('p', 'LEFT(p.prd_description, 20)',
            'prd_description');
        // $rsm->addMetaResult('p', 'COUNT(p.product_id)', 'count');
        $searchQuery = '';
//        if ($searchWord) {
//            $searchQuery = " AND p.product_name LIKE '%".DataUtil::formatForStore($searchWord)."%' ";
//        }
        //  echo $searchQuery;

        $dql = "SELECT  p.product_id , p.prd_price , p.prd_quantity , p.urltitle  , p.prd_image , LEFT(p.product_name, 50) , LEFT(p.prd_description, 20) ,
                        p.enable_question , p.original_price , p.discount , p.prd_question
                        FROM zselex_products p 
                        WHERE p.prd_status=1 AND p.shop_id='".$shop_id."' ".$cat_qry." ".$mnfr_qry." ".$searchQuery."
                        LIMIT $startnum , $itemsperpage";

        // echo $dql;
        $query  = $this->_em->createNativeQuery($dql, $rsm);
        // $query->useResultCache(true);
        $result = $query->getArrayResult();

        return $result;
    }

    public function getMinishopProductsCount($args)
    {
        $shop_id      = $args ['shop_id'];
        $cat_qry      = $args ['cat_qry'];
        $mnfr_qry     = $args ['mnfr_qry'];
        $searchWord   = $args ['search_word'];
        $startnum     = $args ['startnum'];
        $itemsperpage = $args ['itemsperpage'];
        $rsm          = new ORM\Query\ResultSetMapping ();
        $rsm->addEntityResult('ZSELEX_Entity_Product', 'p');
        $rsm->addScalarResult('COUNT(p.product_id)', 'count');

        $searchQuery = '';
//        if ($searchWord) {
//            $searchQuery = " AND p.product_name LIKE '%".DataUtil::formatForStore($searchWord)."%' ";
//        }

        $dql = "SELECT COUNT(p.product_id)
                        FROM zselex_products p 
                        WHERE p.prd_status=1 AND p.shop_id='".$shop_id."' ".$cat_qry." ".$mnfr_qry." ".$searchQuery;

        $query  = $this->_em->createNativeQuery($dql, $rsm);
        // $query->useResultCache(true);
        $result = $query->getSingleScalarResult();

        // echo "<pre>"; print_r($result); echo "</pre>";
        return $result;
    }
    /*
     * Add product categories
     *
     * @param int $product_id
     * @param array $categories
     * @return true if success
     */

    public function addProductCategories($product_id, $categories)
    {
        try {
            $categories = $categories;
            $product_id = $product_id;
            // echo $product_id; exit;

            $prodObj = $this->_em->getRepository('ZSELEX_Entity_Product')->find($product_id);
            foreach ($categories as $cat_id) {
                // echo "catId : " . $cat_id . '<br>';
                $catObj = $this->_em->getRepository('ZSELEX_Entity_ProductCategory')->find($cat_id);

                /*
                 * if ($catObj) {
                 * echo "Cat object found"; exit;
                 * }
                 */

                $prodObj->addCategory($catObj);
                // $this->_em->persist($prodObj);
            }
            // exit;
            $this->_em->flush();
            return true;
        } catch (\Exception $e) {
            echo 'Message addProductCategories: '.$e->getMessage();
            exit();
        }
    }
    /*
     * Delete product categories
     *
     * @param int $product_id
     * @return void
     */

    public function deleteProductCategories($product_id)
    {
        // $categories = $args['categories'];
        try {
            $productRepo = $this->_em->getRepository('ZSELEX_Entity_Product');
            $product_id  = $product_id;

            $getCategories = $productRepo->getAll(array(
                'entity' => 'ZSELEX_Entity_Product',
                'where' => array(
                    'a.product_id' => $product_id
                ),
                'joins' => array(
                    'JOIN a.product_to_category b'
                ),
                'fields' => array(
                    'b.prd_cat_id'
                )
            ));

            $categories = $getCategories;

            // echo "<pre>"; print_r($categories); echo "</pre>"; exit;
            $productObj = $this->_em->getRepository('ZSELEX_Entity_Product')->find($product_id);
            foreach ($categories as $val) {
                $catObj = $this->_em->getRepository('ZSELEX_Entity_ProductCategory')->find($val ['prd_cat_id']);
                // $productObj = $this->_em->getRepository('ZSELEX_Entity_Product')->find($product_id);
                $productObj->removeCategory($catObj);
                // $this->_em->persist($productObj);
            }
            $this->_em->flush();
            return true;
        } catch (\Exception $e) {
            echo 'Message deleteProductCategories: '.$e->getMessage();
            exit();
        }
    }
    /*
     * Create a product
     *
     * @return int product_id is new record ceated
     */

    public function createProduct($item = array())
    {

        // error_log(print_r($item), 3, "/var/www/test/logs.log");
        // error_log('producttest', 3, "/var/www/test/logs.log");
        // return true;
        try {
            $item       = ZSELEX_Util::purifyHtml($item);
            $prodEntity = new ZSELEX_Entity_Product ();
            /*
             * $item['shop_id'] = 203;
             * $item['product_name'] = 'testKhan';
             * $item['prd_image'] = 'sdfdsfas';
             * $item['urltitle'] = 'testtittle';
             * $item['original_price'] = 0;
             * $item['prd_price'] = 0;
             * $item['prd_status'] = 1;
             */

            $shopObj = $this->_em->find('ZSELEX_Entity_Shop', $item ['shop_id']);
            $prodEntity->setShop($shopObj);
            $prodEntity->setProduct_name($item ['product_name']);
            $prodEntity->setUrltitle($item ['urltitle']);
            $prodEntity->setPrd_image($item ['prd_image']);
            $prodEntity->setOriginal_price($item ['original_price']);
            $prodEntity->setPrd_price($item ['prd_price']);
            $prodEntity->setPrd_status($item ['prd_status']);
            if ($item ['manufacturer_id'] > 0) {
                $manufacturerObj = $this->_em->find('ZSELEX_Entity_Manufacturer',
                    $item ['manufacturer_id']);
                $prodEntity->setManufacturer($manufacturerObj);
            } /*
             * else {
             * $prodEntity->setManufacturer(null);
             * }
             */

            $this->_em->persist($prodEntity);
            $this->_em->flush();
            $result = $prodEntity->getProduct_id();

            return $result;
        } catch (\Exception $e) {
            error_log("Error :".$e->getMessage(), 3, "/var/www/test/logs.log");
            // echo 'Message deleteProductCategories: ' . $e->getMessage();
            // exit;
        }
        return $result;
    }

    public function getMinishopProductsApi($args)
    {
        $shop_id      = $args ['shop_id'];
        $cat_qry      = $args ['cat_qry'];
        $mnfr_qry     = $args ['mnfr_qry'];
        $startnum     = $args ['startnum'];
        $itemsperpage = $args ['itemsperpage'];
        $rsm          = new ORM\Query\ResultSetMapping ();
        $rsm->addEntityResult('ZSELEX_Entity_Product', 'p');
        $rsm->addFieldResult('p', 'urltitle', 'urltitle');
        $rsm->addFieldResult('p', 'product_id', 'product_id');
        $rsm->addFieldResult('p', 'prd_price', 'prd_price');
        $rsm->addFieldResult('p', 'prd_image', 'prd_image');
        $rsm->addFieldResult('p', 'prd_quantity', 'prd_quantity');
        $rsm->addFieldResult('p', 'enable_question', 'enable_question');
        $rsm->addFieldResult('p', 'original_price', 'original_price');
        $rsm->addFieldResult('p', 'discount', 'discount');
        $rsm->addFieldResult('p', 'prd_question', 'prd_question');

        $rsm->addMetaResult('p', 'LEFT(p.product_name, 50)', 'product_name');
        $rsm->addMetaResult('p', 'LEFT(p.prd_description, 20)',
            'prd_description');
        // $rsm->addMetaResult('p', 'COUNT(p.product_id)', 'count');
        $prodCount = $this->getMinishopProductsApiCount($args);
        if (!$prodCount) {
            return array();
        }
        $limits = $this->getRandLimit($count  = $prodCount, $end    = $itemsperpage);
        $dql    = "SELECT  p.product_id , p.prd_price , p.prd_quantity , p.urltitle  , p.prd_image , LEFT(p.product_name, 50) , LEFT(p.prd_description, 20) ,
                        p.enable_question , p.original_price , p.discount , p.prd_question
                        FROM zselex_products p 
                        WHERE p.prd_status=1 AND p.shop_id='".$shop_id."' ".$cat_qry." ".$mnfr_qry."  LIMIT $limits[start] , $limits[end]";

        // echo $dql; exit;
        $query  = $this->_em->createNativeQuery($dql, $rsm);
        // $query->useResultCache(true);
        $result = $query->getArrayResult();

        return $result;
    }

    public function getMinishopProductsApiCount($args)
    {
        $shop_id      = $args ['shop_id'];
        $cat_qry      = $args ['cat_qry'];
        $mnfr_qry     = $args ['mnfr_qry'];
        $startnum     = $args ['startnum'];
        $itemsperpage = $args ['itemsperpage'];
        $rsm          = new ORM\Query\ResultSetMapping ();
        $rsm->addEntityResult('ZSELEX_Entity_Product', 'p');
        $rsm->addScalarResult('COUNT(p.product_id)', 'count');

        $dql    = "SELECT COUNT(p.product_id)
                        FROM zselex_products p 
                        WHERE p.prd_status=1 AND p.shop_id='".$shop_id."' ".$cat_qry." ".$mnfr_qry;
        // echo $dql; exit;
        $query  = $this->_em->createNativeQuery($dql, $rsm);
        // $query->useResultCache(true);
        $result = $query->getSingleScalarResult();

        // echo "<pre>"; print_r($result); echo "</pre>";
        return $result;
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

    /**
     * Set quantity discounts for a product
     *
     * @acess public
     * 
     * @param array $args
     *        	int product_id
     *        	array qty_discounts - array consist of qty , discount , startdate , enddate
     * @return true - saves to table
     */
    public function setQuantityDiscount($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $product_id      = $args ['product_id'];
        $qty_discounts   = $args ['qty_discounts'];
        $delete_keywords = $this->deleteEntity(null,
            'ZSELEX_Entity_QuantityDiscount',
            array(
            'a.product' => $product_id
        ));
        if (!empty($qty_discounts)) {
            $no_qty = 0;
            foreach ($qty_discounts ['qty'] as $key => $qty) {

                if ($qty > 0) {
                    $entity  = new ZSELEX_Entity_QuantityDiscount ();
                    $prodObj = $this->_em->find('ZSELEX_Entity_Product',
                        $product_id);
                    $entity->setProduct($prodObj);
                    $entity->setQuantity($qty);
                    $entity->setDiscount($qty_discounts ['discount'] [$key]);
                    if (!empty($qty_discounts ['startdate'] [$key])) {
                        $entity->setStart_date(date_create($qty_discounts ['startdate'] [$key]));
                    }
                    if (!empty($qty_discounts ['enddate'] [$key])) {
                        $entity->setEnd_date(date_create($qty_discounts ['enddate'] [$key]));
                    }
                    $this->_em->persist($entity);
                    $this->_em->flush();
                    $result = $entity->getDiscount_id();
                } else {
                    if ($no_qty < 1) {
                        $entity  = new ZSELEX_Entity_QuantityDiscount ();
                        $prodObj = $this->_em->find('ZSELEX_Entity_Product',
                            $product_id);
                        $entity->setProduct($prodObj);
                        $entity->setQuantity($qty);
                        $entity->setDiscount($qty_discounts ['discount'] [$key]);
                        if (!empty($qty_discounts ['startdate'] [$key])) {
                            $entity->setStart_date(date_create($qty_discounts ['startdate'] [$key]));
                        }
                        if (!empty($qty_discounts ['enddate'] [$key])) {
                            $entity->setEnd_date(date_create($qty_discounts ['enddate'] [$key]));
                        }
                        $this->_em->persist($entity);
                        $this->_em->flush();
                        $result = $entity->getDiscount_id();
                    }

                    $no_qty ++;
                }
            }
        }

        return true;
    }

    /**
     * Get quantity discount for a product
     *
     * @acess public
     *
     * @param int $args['product_id']
     * @param int $args['qty']
     * @return array fo quantity discounts of the product
     */
    public function getQtyDiscount($args)
    {
        $qty        = $args ['qty'];
        $product_id = $args ['product_id'];
        $today      = date("Y-m-d");
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $dql        = "SELECT a.discount , a.start_date , a.end_date
                FROM ZSELEX_Entity_QuantityDiscount a
                WHERE a.product=:product_id AND a.quantity > 0 AND a.quantity<=:quantity 
                ORDER BY a.quantity DESC";
        $query      = $this->_em->createQuery($dql);
        $query->setParameter('quantity', $qty);
        $query->setParameter('product_id', $product_id);
        $result     = $query->getArrayResult();
        // echo "<pre>"; print_r($result); echo "</pre>"; exit;
        foreach ($result as $value) {
            if (!empty($value ['start_date']) && !empty($value ['end_date'])) {
                if ($today >= $value ['start_date'] && $today <= $value ['end_date']) {
                    return $value;
                }
            } else {
                return $value;
            }
        }
        return array();
        // echo "<pre>"; print_r($result); echo "</pre>"; exit;
        // return $result;
    }

    /**
     * Get the zero quanity discount
     *
     * Get the discount of the product which has zero in quantity in
     * QuantityDiscount table
     *
     * @param int $args['product_id']
     * @return string Discount false if no discount found
     */
    function getZeroQtyDiscount($args)
    {
        $product_id = $args ['product_id'];
        $today      = date("Y-m-d");
        $dql        = "SELECT a.discount , a.start_date , a.end_date
                FROM ZSELEX_Entity_QuantityDiscount a
                WHERE a.product=:product_id AND a.quantity=0
                ORDER BY a.discount_id ASC";
        // echo $dql;
        $query      = $this->_em->createQuery($dql);
        $query->setParameter('product_id', $product_id);
        $result     = $query->getOneOrNullResult(2);

        if ($result) {
            if (!empty($result ['start_date']) && !empty($result ['end_date'])) {
                if ($today >= $result ['start_date'] && $today <= $result ['end_date']) {
                    return $result ['discount'];
                }
            } else {
                return $result ['discount'];
            }
        } else {
            return 0;
        }
    }

    /**
     * Update product keywords
     *
     * @return void
     */
    function updateProductKeywords()
    {
        // echo "updateProductKeywords";  exit;
        $allProducts = $this->findAll();
        //echo "<pre>"; print_r($allProducts); echo "</pre>"; exit;
        foreach ($allProducts as $product) {
            // echo sprintf("-%s\n", $product->getName());
            $keyword = $product->getProduct_name();
            // echo $productName . '<br>';
            if (!empty($keyword)) {
                $keywordExist = $this->getCount(null, 'ZSELEX_Entity_Keyword',
                    'keyword_id',
                    array(
                    'a.keyword' => $keyword
                ));

                if ($keywordExist < 1) {
                    //echo $keyword." Not Exist <br>";
                    $keywordEntity = new ZSELEX_Entity_Keyword();
                    $keywordEntity->setKeyword($keyword);
                    $keywordEntity->setType('product');
                    $keywordEntity->setType_id($product->getProduct_id());
//                    $shopObj       = $this->_em->find('ZSELEX_Entity_Shop',
//                        $formElements ['shop_id']);
                    $shopObj       = $product->getShop();
                    $keywordEntity->setShop($shopObj);
                    $this->_em->persist($keywordEntity);
                    $this->_em->flush();
                }
            }
        }
    }

    /**
     * update product keywords
     *
     * @return boolean
     */
    function updateProductKeywordsByDelete()
    {

        $message = 'Product Keyword Update is completed at the background.'.'<br><br>';
        $message .= 'Server'.' : '.$_SERVER ['SERVER_NAME'].'<br>';
        $message .= 'Start Date'.' : '.date('Y-m-d h:i:s a', time()).'<br>';
        try {
            set_time_limit(0);

            $deleteKeywords = $this->deleteEntity(null, 'ZSELEX_Entity_Keyword',
                array(
                'a.type' => 'product'
            ));

            // return true;
            //  echo "updateProductKeywordsByDelete";  exit;
            $allProducts = $this->findAll();
            // echo "<pre>"; print_r($allProducts); echo "</pre>"; exit;

            $productKeyWords = [];
            foreach ($allProducts as $product) {
                $productName = $product->getProduct_name();
                if (!empty($productName)) {
                    $productKeyWords[] = $productName;
                }
                // echo "<pre>"; print_r($productKeyWords);  echo "</pre>";
                $keywords = $product->getKeywords();
                if (!empty($keywords)) {
                    $keywords          = trim(preg_replace('/\s+/', ' ',
                            $keywords));
                    $keywordsForSearch = str_replace(",", " ", $keywords);
                    $keywordsForSearch = explode(" ", $keywordsForSearch);
//                $productKeywords     = array_merge($productKeywords,
//                    $keywords_for_search);
                    $productKeyWords   = array_merge($productKeyWords,
                        $keywordsForSearch);
                }


                foreach ($productKeyWords as $keyword) {

                    if (!empty($keyword)) {
                        $keywordExist = $this->getCount(null,
                            'ZSELEX_Entity_Keyword', 'keyword_id',
                            array(
                            'a.keyword' => $keyword
                        ));

                        if ($keywordExist < 1) {

                            $keywordEntity = new ZSELEX_Entity_Keyword();
                            $keywordEntity->setKeyword($keyword);
                            $keywordEntity->setType('product');
                            $keywordEntity->setType_id($product->getProduct_id());
                            $shopObj       = $product->getShop();
                            $keywordEntity->setShop($shopObj);
                            $this->_em->persist($keywordEntity);
                            $this->_em->flush();
                        }
                    }
                }
            }

            // return true;
        } catch (\Exception $e) {
            $message .= "Error Occured: ".$e->getMessage();
        }

        $message .= 'End Date'.' : '.date('Y-m-d h:i:s a', time()).'<br>';

        $emails [] = 'sharazkhanz@gmail.com';
        $emails [] = 'kim@acta-it.dk';
        foreach ($emails as $email) {
            $mailerArgs = array(
                'toaddress' => $email,
                'fromname' => 'ZSELEX',
                'subject' => "Product Keyword Update",
                'body' => $message,
                'html' => true
            );

            $sent = @ModUtil::apiFunc('Mailer', 'user', 'sendMessage',
                    $mailerArgs);
        }

        return true;
        // echo "<pre>"; print_r($productKeyWords);  echo "</pre>"; exit;
    }
}
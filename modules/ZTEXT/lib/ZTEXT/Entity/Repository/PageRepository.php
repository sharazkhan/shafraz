<?php

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Repository class for DQL calls
 *
 */
class ZTEXT_Entity_Repository_PageRepository extends ZSELEX_Entity_Repository_General {

    /**
     * Get all pages at admin end
     * 
     * @param array $args
     * int shop_id
     * @return array of pages
     */
    function getPages($args) {
        $page_args = array();
        $getPages = array();
        $shop_id = $args['shop_id'];
        $page_args = array(
            'entity' => 'ZTEXT_Entity_Page',
            'where' => array('a.shop' => $shop_id),
            'startlimit' => $args['startlimit'],
            'offset' => $args['offset'],
            'orderby' => "a." . $args['order'] . " " . $args['orderdir'],
            'paginate' => true
        );
        if (isset($args['status']) && $args['status'] != "") {
            $page_args['where']['a.active'] = $args['status'];
        }
        if (!empty($args['searchtext'])) {
            $page_args['like']['a.headertext'] = "%" . DataUtil::formatForStore($args['searchtext']) . "%";
        }
        // echo "<pre>"; print_r($page_args);  echo "</pre>";
        $getPages = $this->getAll($page_args);
        return $getPages;
    }

    /**
     * Get pages at minisite
     * 
     * @param array $args
     * int shop_id
     * int startlimit
     * int itemsperpage
     * @return array of pages
     */
    public function getPagesForSite($args) {
        $shop_id = $args['shop_id'];
        $startlimit = $args['startlimit'];
        if ($startlimit > 0) {
            $startlimit = $startlimit - 1;
        }
        $maxResults = $args['itemsperpage'];
        $dql = "SELECT a
                 FROM ZTEXT_Entity_Page a 
                 WHERE a.shop = :shop_id AND a.active=1
                 ORDER BY a.text_id ASC";
        //echo $dql;

        $query = $this->_em->createQuery($dql);
        $query->setParameter('shop_id', $shop_id);
        $query->setFirstResult($startlimit);
        $query->setMaxResults($maxResults);
        $result = $query->getResult(2);
        //echo "<pre>";  print_r($result);   echo "</pre>";

        return $result; // hydrate result to array
    }

    /**
     * Get count for pages at ministe
     * 
     * @param array $args
     * int shop_id
     * @return total count of pages
     */
    public function getPagesForSiteCount($args) {
        $shop_id = $args['shop_id'];

        $dql = "SELECT COUNT(a.text_id)
                 FROM ZTEXT_Entity_Page a 
                 WHERE a.shop = :shop_id AND a.active=1
                 ORDER BY a.text_id ASC";
        //echo $dql;

        $query = $this->_em->createQuery($dql);
        $query->setParameter('shop_id', $shop_id);
        $result = $query->getSingleScalarResult();
        //echo "<pre>";  print_r($result);   echo "</pre>";

        return $result; // hydrate result to array
    }

    /**
     * Get pages at minisite
     * 
     * @param array $args
     * int shop_id
     * int startlimit
     * int itemsperpage
     * @return array of pages
     */
    public function getPagesInSite($args) {
        $shop_id = $args['shop_id'];
        $dql = "SELECT a
                 FROM ZTEXT_Entity_Page a 
                 WHERE a.shop = :shop_id  AND a.active=1 AND a.displayonfront=1
                 ORDER BY a.text_id ASC";
        //echo $dql;

        $query = $this->_em->createQuery($dql);
        $query->setParameter('shop_id', $shop_id);

        $result = $query->getResult(2);
        //echo "<pre>";  print_r($result);   echo "</pre>";

        return $result; // hydrate result to array
    }

    function createPageDnd($args) {
        // return true;
        $urltitle = strtolower($args['headertext']);
        $urltitle = ZSELEX_Util::cleanTitle($urltitle);
        $url_args = array(
            'table' => 'ztext_pages',
            'title' => $urltitle,
            'field' => 'urltitle'
        );
        $utltitle = ZSELEX_Util::increment_url($url_args);

        $path_parts = pathinfo($args['image']);
        if ($path_parts['extension'] == 'pdf') {
            $image_name = $args['pdf_image'];
            $doc = $args['image'];
        } else {
            $image_name = $args['image'];
            $doc = '';
        }

        $page = new ZTEXT_Entity_Page();
        $page->setHeadertext($args['headertext']);
        $page->setUrltitle($utltitle);
        $shop = $this->_em->find('ZSELEX_Entity_Shop', $args['shop_id']);
        $page->setShop($shop);
        $page->setImage($image_name);
        $page->setExtension($args['extension']);
        $page->setDoc($doc);
        $page->setActive(1);
        $this->_em->persist($page);
        $this->_em->flush();
        $result = $page->getText_id();
        return $result;
    }

}

<?php

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Repository class for DQL calls
 */
class ZSELEX_Entity_Repository_KeywordRepository extends ZSELEX_Entity_Repository_General
{

    /**
     * Get keywords from keyword table
     *
     * @param array $args
     *        	term - searchword
     * @param array $search_args
     *        	- search relations
     * @return array of keywords
     */
    public function getSearchFromKeyword($args, $search_args)
    {
        // return array();
        $term     = $args ['term'];
       
        // echo $sql;
        if (empty($term) || $term == '') {
            return array();
        }

        $sql  = '';
        // if (!isset($search_args) && !empty($search_args)) {
        $join = " JOIN a.shop b ";
        // }
        if ($search_args ['category_id'] > 0) {
            $join .= " JOIN b.shop_to_category c ";
        }

        if ($search_args ['country_id'] > 0) {
            $sql .= " AND b.country=".$search_args ['country_id'];
        }
        if ($search_args ['region_id'] > 0) {
            $sql .= " AND b.region=".$search_args ['region_id'];
        }
        if ($search_args ['city_id'] > 0) {
            $sql .= " AND b.city=".$search_args ['city_id'];
        }
        if ($search_args ['area_id'] > 0) {
            $sql .= " AND b.area=".$search_args ['area_id'];
        }
        if ($search_args ['category_id'] > 0) {
            $sql .= " AND c.category_id=".$search_args ['category_id'];
        }
       

        $dql = "SELECT DISTINCT(a.keyword) as keyword
                FROM ZSELEX_Entity_Keyword a
                ".$join."
                WHERE a.keyword!='' 
                ".$sql."
                AND a.keyword LIKE :search  
                ORDER BY a.keyword ASC";
        // echo $dql; exit;
        // error_log($dql, 3, "/var/www/test/logs.log");
        // error_log($dql, 3, "/var/www/zselex/modules/ZSELEX/errors.log");
        // error_log($dql, 3, "C:/xampp/htdocs/zselex/modules/ZSELEX/errors.log");

        $query = $this->_em->createQuery($dql);
        // $query->setParameter("search", DataUtil::formatForStore($term) . '%');
        $query->setParameter("search", '%'.DataUtil::formatForStore($term).'%');
       
        $query->setFirstResult(0);
        $query->setMaxResults(10);

        $result = $query->getArrayResult();
        return $result;
        // return [];
    }

    public function getSearchFromShop($args)
    {
        // return array();
        $term  = $args ['term'];
        // echo $sql;
        $dql   = "SELECT DISTINCT(a.shop_name) shop_name
                 FROM ZSELEX_Entity_Shop a
                 WHERE a.shop_name LIKE :search  
                 ORDER BY a.shop_name ASC";
        // echo $dql;
        $query = $this->_em->createQuery($dql);
        $query->setParameter("search", DataUtil::formatForStore($term).'%');
        $query->setFirstResult(0);
        $query->setMaxResults(10);

        $result = $query->getArrayResult();
        return $result;
    }

    public function createKeyword($args)
    {
        try {
            // $item = ZSELEX_Util::purifyHtml($args);
            $item    = $args;
            // echo "<pre>"; print_r($item); echo "</pre>"; exit;
            $keyword = new ZSELEX_Entity_Keyword ();
            $keyword->setKeyword($item ['keyword']);
            $keyword->setType($item ['type']);
            $keyword->setType_id($item ['type_id']);
            $shop    = $this->_em->find('ZSELEX_Entity_Shop', $item ['shop_id']);
            $keyword->setShop($shop);
            $this->_em->persist($keyword);
            $this->_em->flush();

            $InsertId = $keyword->getKeyword_id();
            $result   = $InsertId;
            return $result;
        } catch (\Exception $e) {
            echo 'Message: '.$e->getMessage().'<br>';
            exit();
        }
    }

    /**
     * Update event keywords in keyword table
     *
     * @param array $args
     *        	event_id , shop_id , keywords
     * @return boolean
     */
    function updateEventKeywords($args)
    {
        $event_id            = $args ['event_id'];
        $shop_id             = $args ['shop_id'];
        $keywords            = $args ['keywords'];
        $this->deleteEntity(null, 'ZSELEX_Entity_Keyword',
            array(
            'a.type_id' => $event_id,
            'a.type' => 'event'
        ));
        $keywords_for_search = str_replace(",", " ", $keywords);
        $keywords_for_search = explode(" ", $keywords_for_search);
        foreach ($keywords_for_search as $keyword) {
            if (!empty($keyword)) {

                $keywordExist = $this->getCount(null, 'ZSELEX_Entity_Keyword',
                    'keyword_id',
                    array(
                    'a.keyword' => $keyword
                ));

                if ($keywordExist < 1) {

                    $keyword_item = array(
                        'keyword' => $keyword,
                        'type' => 'event',
                        'type_id' => $event_id,
                        'shop_id' => $shop_id
                    );

                    $result_keyword = $this->createKeyword($keyword_item);
                }
            }
        }
        return true;
    }
}
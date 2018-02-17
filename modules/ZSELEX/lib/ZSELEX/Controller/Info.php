<?php

class ZSELEX_Controller_Info extends Zikula_AbstractController
{

    public function testPage()
    {
        $this->view->setCaching(false);
        return $this->view->fetch('admin/info.tpl');
    }

    public function testPage1()
    {
        $this->view->setCaching(false);
        return $this->view->fetch('admin/ajaxpage1.tpl');
    }

    function myTest()
    {
        $this->view->setCaching(false);
        $message = "hello workd!!!!";
        $this->view->assign('message', $message);
        return $this->view->fetch('test/test.tpl');
    }

    /**
     * Pop up links in footer
     * 
     * @param array $args
     * @return html
     */
    function footerLink($args)
    {
        error_reporting(0);
        //echo "<pre>"; print_r($args); echo "</pre>"; exit;
        // exit;
        $this->view->setCaching(false);

        $key     = FormUtil::getPassedValue('key', null, 'REQUEST');
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');


        $titles = ['rma' => $this->__('RMA'), 'deliveryprices' => $this->__('Delivery prices'),
            'deliverytime' => $this->__('Delivery time'),
            'termsoftrade' => $this->__('Terms of trade'), 'privacy' => $this->__('Privacy'),
            'securepayment' => $this->__('Secure payment')];
        //echo "<pre>"; print_r($titles); echo "</pre>"; exit;
        if ($shop_id > 0) {
            // echo $shop_id; exit;
            $shop_name = FormUtil::getPassedValue('shop_name', null, 'REQUEST');
            $this->view->assign('shop_id', $shop_id);
            $this->view->assign('shop_name', $shop_name);
            $this->view->assign('count',
                strlen(str_replace(" ", "", $shop_name)));

            // $shop_details = $this->entityManager->getRepository('ZSELEX_Entity_ShopDetail')->getShopDetails(array('shop_id' => $shop_id));
            $shop_details = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->get(array(
                'entity' => 'ZSELEX_Entity_Shop',
                'fields' => array(
                    'a.terms_conditions'
                ),
                'where' => array(
                    'a.shop_id' => $shop_id
                )
            ));

            // echo "<pre>"; print_r($shop_details); echo "</pre>"; exit;
            $this->view->assign('shop_details', $shop_details);
        }

        $thislang = ZLanguage::getLanguageCode();

        // echo $titles[$key]; exit;

        $this->view->assign('key', $key);
        $this->view->assign('title', $titles[$key]);
        $this->view->assign('thislang', $thislang);
        return $this->view->fetch('info/footer_link.tpl');
    }

    public function displayInfo($args)
    {

        // $info = $_REQUEST['info'];
        $bid      = $_REQUEST ['bid'];
        $type     = $_REQUEST ['servicetype'];
        $fields   = array(
            'content'
        );
        $item     = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow',
                array(
                'table' => 'blocks',
                'fields' => array(
                    'content'
                ),
                'where' => array(
                    "bid='".$bid."'"
                )
        ));
        $thislang = ZLanguage::getLanguageCode();

        $content = unserialize($item ['content']);

        // echo "<pre>"; print_r($content); echo "</pre>";

        if (!empty($content ['blockinfo'] [$thislang] ['infomessage'])) {

            $message = $content ['blockinfo'] [$thislang] ['infomessage'];
        } else {
            $contents = ModUtil::apiFunc('ZSELEX', 'admin', 'getSingleItem',
                    $args     = array(
                    'table' => 'zselex_plugin',
                    'where' => "type='".$type."'",
                    'itemname' => 'content'
            ));

            $contents = unserialize($contents);
            // echo "<pre>"; print_r($contents); echo "</pre>";

            $message = $contents [$thislang] ['infomessage'];
        }

        // $message = $content['blockinfo'][$thislang]['infomessage'];
        // echo $message;

        $this->view->assign('bid', $bid);
        $this->view->assign('message', $message);

        $this->view->setCaching(false);

        return $this->view->fetch('user/infomessage.tpl');
    }

    public function displayServiceInfo($args)
    {

        // $info = $_REQUEST['info'];
        $serviceId = $_REQUEST ['id'];
        $fields    = array(
            'content'
        );
        $item      = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow',
                array(
                'table' => 'zselex_plugin',
                'fields' => array(
                    'content'
                ),
                'where' => array(
                    "plugin_id='".$serviceId."'"
                )
        ));
        $thislang  = ZLanguage::getLanguageCode();

        $content = unserialize($item ['content']);

        // echo "<pre>"; print_r($content); echo "</pre>";

        $message = $content [$thislang] ['infomessage'];

        // $this->view->assign('bid', $bid);
        $this->view->assign('message', $message);

        $this->view->setCaching(false);

        return $this->view->fetch('user/infomessage.tpl');
    }

    public function showEvents($args)
    {

        // echo "<pre>"; print_r($_REQUEST); echo "</pre>";
        // $sess_item = SessionUtil::getVar('selectionfields');
        $sess_item = $_SESSION ['selectionfield'];

        // echo "<pre>"; print_r($sess_item); echo "</pre>"; exit;

        /*
         * $level = FormUtil::getPassedValue("level");
         * $shop_id = FormUtil::getPassedValue("shop_id");
         * $country_id = FormUtil::getPassedValue("country_id");
         * $region_id = FormUtil::getPassedValue("region_id");
         * $city_id = FormUtil::getPassedValue("city_id");
         * $area_id = FormUtil::getPassedValue("area_id");
         * $branch_id = FormUtil::getPassedValue("branch_id");
         * $category_id = FormUtil::getPassedValue("category_id");
         * $search = FormUtil::getPassedValue("hsearch");
         * $eventlimit = FormUtil::getPassedValue("eventlimit");
         * $reset = FormUtil::getPassedValue("reset");
         *
         */
        $shop_id     = $sess_item ["shop_id"];
        $country_id  = $sess_item ["country_id"];
        $region_id   = $sess_item ["region_id"];
        $city_id     = $sess_item ["city_id"];
        $area_id     = $sess_item ["area_id"];
        $branch_id   = $sess_item ["branch_id"];
        $category_id = $sess_item ["category_id"];
        $search      = $sess_item ["hsearch"];
        // echo $reset; exit;
        if (!empty($eventlimit)) {

            $limit      = $eventlimit;
            $limitquery = "LIMIT 0 , $eventlimit";
        } else {
            $limit      = "2";
            $limitquery = "LIMIT 0 , 2";
        }

        $searchquery = '';
        if (($shop_id > 0 || !empty($shop_id)) || ($region_id > 0 || !empty($region_id))
            || ($city_id > 0 || !empty($city_id)) || ($country_id > 0 || !empty($country_id))
            || ($area_id > 0 || !empty($area_id)) || ($search > 0 || !empty($search))
            || ($category_id > 0 || !empty($category_id)) || ($branch_id > 0 || !empty($branch_id)
            || !empty($eventdate))) {
            if (!empty($search)) {

                // $searchquery = " AND a.shop_name LIKE '%" . DataUtil::formatForStore($hsearch) . "%'";
                $searchquery = " AND a.shop_name LIKE '%".DataUtil::formatForStore($search)."%'
                             OR a.shop_id IN (SELECT shop_id FROM zselex_advertise WHERE keywords LIKE '%".DataUtil::formatForStore($search)."%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_files WHERE keywords LIKE '%".DataUtil::formatForStore($search)."%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_gallery WHERE keywords LIKE '%".DataUtil::formatForStore($search)."%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_pdf WHERE keywords LIKE '%".DataUtil::formatForStore($search)."%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_dotd WHERE keywords LIKE '%".DataUtil::formatForStore($search)."%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_products WHERE keywords LIKE '%".DataUtil::formatForStore($search)."%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_news WHERE news_id IN 
                             (SELECT sid FROM news WHERE title LIKE '%".DataUtil::formatForStore($search)."%' OR hometext LIKE '%".DataUtil::formatForStore($search)."%' OR bodytext LIKE '%".DataUtil::formatForStore($search)."%' OR urltitle LIKE '%".DataUtil::formatForStore($search)."%'))
            ";
            }

            // $eventdateqry = " AND shop_event_startdate>=CURDATE()";

            $catquery = '';
            if ($category_id != '') {
                $catquery = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='CATEGORY'
                AND parentId=$category_id)";
            }

            if ($category_id != '' && $country_id <= 0 && $region_id <= 0 && $city_id
                <= 0 && $area_id <= 0 && $shop_id <= 0) {
                $catshop = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='CATEGORY'
                AND parentId=$category_id)";
            }

            $branchquery = '';
            $branchshop  = '';
            if ($branch_id != '') {
                $branchquery = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='BRANCH'
                AND parentId=$branch_id)";
            }

            if ($branch_id != '' && $country_id <= 0 && $region_id <= 0 && $city_id
                <= 0 && $area_id <= 0 && $shop_id <= 0) {
                $branchshop = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='BRANCH'
                AND parentId=$branch_id)";
            }

            $searchquerymain = '';
            if (!empty($search) && $country_id <= 0 && $region_id <= 0 && $city_id
                <= 0 && $area_id <= 0 && $shop_id <= 0) { // when only search is typed
                // $searchquerymain = " AND a.shop_name LIKE '%" . DataUtil::formatForStore($hsearch) . "%'";
                $searchquerymain = " AND a.shop_name LIKE '%".DataUtil::formatForStore($search)."%'
                             OR a.shop_id IN (SELECT shop_id FROM zselex_advertise WHERE keywords LIKE '%".DataUtil::formatForStore($search)."%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_files WHERE keywords LIKE '%".DataUtil::formatForStore($search)."%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_gallery WHERE keywords LIKE '%".DataUtil::formatForStore($search)."%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_pdf WHERE keywords LIKE '%".DataUtil::formatForStore($search)."%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_dotd WHERE keywords LIKE '%".DataUtil::formatForStore($search)."%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_products WHERE keywords LIKE '%".DataUtil::formatForStore($search)."%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_news WHERE news_id IN 
                             (SELECT sid FROM news WHERE title LIKE '%".DataUtil::formatForStore($search)."%' OR hometext LIKE '%".DataUtil::formatForStore($search)."%' OR bodytext LIKE '%".DataUtil::formatForStore($search)."%' OR urltitle LIKE '%".DataUtil::formatForStore($search)."%'))
            ";
            }

            $output = '';
            $items  = array(
                'id' => $shop_id
            );
            // $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
            $where  = '';

            if (($country_id > 0) or ( $country_id > 0 && $region_id < 0 && $city_id
                < 0 && $shop_id < 0)) { // COUNTRY
                $where = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP'
        AND parentType='CITY' AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' 
        AND parentType='COUNTRY' AND parentId=$country_id)) $catquery $branchquery $searchquery
        OR 
        a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' 
        AND parentType='CITY' AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' 
        AND parentType='REGION' AND parentId IN(SELECT
        childId FROM zselex_parent WHERE childType='REGION' AND parentType='COUNTRY' AND parentId=$country_id))) $catquery $branchquery $searchquery
        OR
        a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='COUNTRY' AND parentId=$country_id) $catquery $branchquery $searchquery

      ";
            }

            if (($region_id > 0) or ( $country_id > 0 && $region_id > 0 && $city_id
                < 0 && $shop_id < 0)) { // REGION
                $where = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP'
                AND parentType='CITY' AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='REGION' 
                AND parentId=$region_id)) $catquery $branchquery $searchquery
                OR
                a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='REGION' AND parentId=$region_id) $catquery $branchquery $searchquery
            ";
            }

            if (($city_id > 0) or ( $region_id > 0 && $city_id > 0 && $country_id
                > 0 && $shop_id < 0)) { // CITY
                $where = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='CITY' AND parentId=$city_id) $catquery $branchquery $searchquery";
            }

            if (($area_id > 0) or ( $region_id > 0 && $area_id > 0 && $city_id > 0
                && $country_id > 0 && $shop_id < 0)) { // AREA
                $where = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='AREA' AND parentId=$area_id) $catquery $branchquery $searchquery";
            }

            if (($shop_id > 0) or ( $shop_id > 0 && $region_id > 0 && $city_id > 0
                && $country_id > 0)) { // SHOP
                $where = " AND a.shop_id=$shop_id  $catquery $branchquery $searchquery";
            }

            $sql    = "SELECT shop_id FROM zselex_shop a
                     WHERE a.shop_id IS NOT NULL AND a.status='1'  $where $catshop $branchshop  $searchquerymain";
            // echo $sql;
            $query  = DBUtil::executeSQL($sql);
            $result = $query->fetchAll();

            $count = count($result);
        } else {

            $count = 0;
        }
        $shopsql = '';
        if ($count > 0) {

            foreach ($result as $shopid) {
                $shop_idarray [] = $shopid ['shop_id'];
            }

            $shop_ids = implode(",", $shop_idarray);
            // foreach ($result as $shop) {
            $shopsql  = " AND shop_id IN($shop_ids)";

            if ($reset != 'reset') {
                $shopquery = $shopsql;
            } else {
                $shopquery = "";
            }
        }
        // echo $shopquery;
        $eventdateqry = '';

        $sqlevents  = "SELECT *, shop_event_id , shop_id , shop_event_startdate , LEFT(shop_event_name, 20) AS event_name  ,   LEFT(shop_event_description, 20) AS event_description  ,   LEFT(shop_event_shortdescription, 20) AS event_shortdescription , DAYNAME(shop_event_startdate) as dateformated
                     FROM zselex_shop_events 
                     WHERE shop_id IS NOT NULL AND shop_event_id IS NOT NULL AND status='1' $shopquery"." ".$eventdateqry." AND shop_event_enddate>=CURDATE() ORDER BY shop_event_startdate ASC";
        // echo $sqlevents;
        $query1     = DBUtil::executeSQL($sqlevents);
        $events     = $query1->fetchAll();
        $eventcount = count($events);
        // shuffle($events);

        $totalcountsql   = "SELECT * FROM zselex_shop_events
                          WHERE shop_id IS NOT NULL AND shop_event_id IS NOT NULL AND shop_event_enddate>=CURDATE() AND status='1' $shopquery "." ".$eventdateqry;
        $totalcountquery = DBUtil::executeSQL($totalcountsql);
        $totalCount      = $totalcountquery->rowCount();

        $todayDate = date("Y-m-d");

        foreach ($events as $key => $event) {
            // echo $event['shop_event_startdate'] . '<br>';
            // if($event['shop_event_startdate'] > )
            $date                           = strtotime(date("Y-m-d",
                    strtotime($todayDate))." +$key day");
            // $dis = date('l dS \o\f F Y', $date) . "<br>";
            // echo $dis . "-" . $event['shop_event_name'];
            $headlinedate                   = date('l dS \o\f F Y', $date);
            // echo $headlinedate . "-" . $events[$i]['shop_event_name'] . '<br>';
            $events [$key] ['headlinedate'] = $headlinedate;
        }

        $this->view->assign('events', $events);

        // }

        return $this->view->fetch('user/allevents.tpl');
    }
}
// end class def
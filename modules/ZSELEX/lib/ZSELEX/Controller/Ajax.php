<?php

class ZSELEX_Controller_Ajax extends ZSELEX_Controller_Base_Ajax
{

    public function searchres()
    {
        // $term = $_REQUEST['term'];
        // echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
        $term        = FormUtil::getPassedValue('term', '', 'REQUEST');
        // $region_id = FormUtil::getPassedValue('region_id', '', 'REQUEST');
        // $category_id = FormUtil::getPassedValue('category_id', '', 'REQUEST');
        $currFunc    = FormUtil::getPassedValue('curr_func', '', 'REQUEST');
        $search_args = array(
            'country_id' => FormUtil::getPassedValue('country_id', '', 'REQUEST'),
            'region_id' => FormUtil::getPassedValue('region_id', '', 'REQUEST'),
            'city_id' => FormUtil::getPassedValue('city_id', '', 'REQUEST'),
            'area_id' => FormUtil::getPassedValue('area_id', '', 'REQUEST'),
            'shop_id' => FormUtil::getPassedValue('shop_id', '', 'REQUEST'),
            'category_id' => FormUtil::getPassedValue('category_id', '',
                'REQUEST'),
            'branch_id' => FormUtil::getPassedValue('branch_id', '', 'REQUEST'),
            'curr_func' => $currFunc
        );

        // echo "<pre>"; print_r($search_args); echo "</pre>"; exit;
        // $msg = "term :" . $term;
        // $msg = "term :" . $term;
        // $msg = "Region :" . $region_id . " " . "Cat :" . $category_id . '\n';
        // error_log($msg, 3, "/var/www/zselex/modules/ZSELEX/errors.log");

        $result = array();
        /*
         * $sql = "SELECT DISTINCT(keyword) keyword FROM zselex_keywords
         * WHERE keyword like '" . DataUtil::formatForStore($term) . "%'
         * ORDER BY keyword ASC LIMIT 0,10";
         * $query = DBUtil::executeSQL($sql);
         * $result = $query->fetchAll();
         */
        $result = $this->entityManager->getRepository('ZSELEX_Entity_Keyword')->getSearchFromKeyword(array(
            'term' => $term
            ), $search_args);

        $data = array();
        foreach ($result as $key => $val) {
            $data [] = array(
                'label' => stripslashes($val ['keyword']),
                'value' => stripslashes($val ['keyword'])
            );
        }

        // echo "<pre>"; print_r($data); echo "</pre>"; exit;

        /*
         * $sql2 = "SELECT DISTINCT(shop_name) shop_name FROM zselex_shop
         * WHERE shop_name like '" . DataUtil::formatForStore($term) . "%'
         * ORDER BY shop_name ASC LIMIT 0,10";
         * $query2 = DBUtil::executeSQL($sql2);
         * $result2 = $query2->fetchAll();
         */
        $data2 = array();
        /*
         * $result2 = $this->entityManager->getRepository('ZSELEX_Entity_Keyword')->getSearchFromShop(array('term' => $term));
         *
         *
         * foreach ($result2 as $key2 => $val2) {
         * $data2[] = array(
         * 'label' => stripslashes($val2['shop_name']),
         * 'value' => stripslashes($val2['shop_name'])
         * );
         * }
         */

        $unique = array();

        // $finalresult = array_merge($data, $data2);
        // exit;
        $finalresult = $data;
        if (!empty($finalresult)) {
            // $unique = array_map('unserialize', array_unique(array_map('serialize', $finalresult)));
            $unique = $finalresult;
        }

        // echo "<pre>"; print_r($unique); echo "</pre>"; exit;

        ZSELEX_Util::ajaxOutput(json_encode($unique));
        // flush();
    }

    public function searchres2()
    {
        $term = $_REQUEST ['term'];

        $sql    = "SELECT DISTINCT(country_name) keyword FROM zselex_country
                WHERE country_name like '".$term."%'  
                ORDER BY country_name ASC LIMIT 0,10";
        $query  = DBUtil::executeSQL($sql);
        $result = $query->fetchAll();

        $data = array();
        foreach ($result as $key => $val) {
            $data [] = array(
                'label' => $val ['keyword'],
                'value' => $val ['keyword']
            );
        }
        ZSELEX_Util::ajaxOutput(json_encode($data));
    }

    public function getSpecialBlockArticleAd($args)
    {

        // $data = "test";
        $level       = FormUtil::getPassedValue('level', null, 'REQUEST');
        $shop_id     = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $country_id  = FormUtil::getPassedValue('country_id', null, 'REQUEST');
        $region_id   = FormUtil::getPassedValue('region_id', null, 'REQUEST');
        $city_id     = FormUtil::getPassedValue('city_id', null, 'REQUEST');
        $area_id     = FormUtil::getPassedValue('area_id', null, 'REQUEST');
        $category_id = FormUtil::getPassedValue('category_id', null, 'REQUEST');
        $branch_id   = FormUtil::getPassedValue('branch_id', null, 'REQUEST');
        $search      = FormUtil::getPassedValue('hsearch', null, 'REQUEST');
        $search      = ($search == $this->__('search for...') || $search == $this->__('search'))
                ? '' : $search;

        $append      = '';
        $shop_append = '';
        /*
         * if (!empty($shop_id)) {
         * $shop_append = " AND ad.shop_id=$shop_id";
         * }
         * if ($level == 'country') {
         * $append .= " AND ad.level='COUNTRY' AND ad.country_id='" . $country_id . "'";
         * } elseif ($level == 'region') {
         * $append .= " AND ad.level='REGION' AND ad.region_id='" . $region_id . "'";
         * } elseif ($level == 'city') {
         * $append .= " AND ad.level='CITY' AND ad.city_id='" . $city_id . "'";
         * } elseif ($level == 'area') {
         * $append .= " AND ad.level='AREA' AND ad.area_id='" . $area_id . "'";
         * } elseif ($level == 'shop') {
         * $append .= " AND ad.level='AREA' AND ad.shop_id='" . $shop_id . "'";
         * }
         */

        if (($shop_id > 0 || !empty($shop_id)) || ($region_id > 0 || !empty($region_id))
            || ($city_id > 0 || !empty($city_id)) || ($country_id > 0 || !empty($country_id))
            || ($area_id > 0 || !empty($area_id)) || (!empty($search)) || ($category_id
            > 0 || !empty($category_id)) || ($branch_id > 0 || !empty($branch_id))) {
            $catquery = '';
            $catshop  = '';

            if ($category_id != '' && $country_id <= 0 && $region_id <= 0 && $city_id
                <= 0 && $area_id <= 0 && $shop_id <= 0) { // Only category is selected
                $catshop = " AND b.cat_id=$category_id";
            }

            $branchquery = '';
            $branchshop  = '';

            if ($branch_id != '' && $country_id <= 0 && $region_id <= 0 && $city_id
                <= 0 && $area_id <= 0 && $shop_id <= 0) { // Only branch is selected
                $branchshop = " AND b.branch_id=$branch_id";
            }

            $searchquery = '';

            $searchquerymain = '';
            if (!empty($search) && $country_id <= 0 && $region_id <= 0 && $city_id
                <= 0 && $area_id <= 0 && $shop_id <= 0) { // Only search is typed
                $searchquerymain = " AND a.keywords LIKE '%".DataUtil::formatForStore($search)."%'";
            }

            if (!empty($category_id)) {
                // $append .= " AND b.cat_id=$category_id";
                $append .= " AND b.shop_id IN(SELECT shop_id FROM zselex_shop_to_category WHERE category_id=$category_id) ";
            }

            if (!empty($branch_id)) {
                $append .= " AND b.branch_id=$branch_id";
            }

            if (!empty($search)) {
                $append .= " AND (a.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE '%".DataUtil::formatForStore($search)."%') OR a.shop_id IN (SELECT shop_id FROM zselex_shop WHERE shop_name LIKE '%".DataUtil::formatForStore($search)."%'))";
            }

            $adquery = '';
            // if ($adtype != '') {
            // $append .= " AND b.adprice_id='1'";
            // }
            // $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
            $where   = '';

            if (($country_id > 0) or ( $country_id > 0 && $region_id < 0 && $city_id
                < 0 && $shop_id < 0)) { // COUNTRY
                $where = " AND a.country_id=$country_id AND a.level='COUNTRY' AND b.shop_id!=''";
            }

            if (($region_id > 0) or ( $country_id > 0 && $region_id > 0 && $city_id
                < 0 && $shop_id < 0)) { // REGION
                $where = " AND a.region_id=$region_id AND a.level='REGION' AND b.shop_id!=''";
            }

            if (($city_id > 0) or ( $region_id > 0 && $city_id > 0 && $country_id
                > 0 && $shop_id < 0)) { // CITY
                $where = " AND a.city_id=$city_id AND a.level='CITY' AND b.shop_id!=''";
            }

            if (($area_id > 0) or ( $region_id > 0 && $area_id > 0 && $city_id > 0
                && $country_id > 0 && $shop_id < 0)) { // AREA
                $where = " AND a.area_id=$area_id AND a.level='AREA' AND b.shop_id!=''";
            }

            if (($shop_id > 0) or ( $shop_id > 0 && $region_id > 0 && $city_id > 0
                && $country_id > 0)) { // SHOP
                $where = " AND a.shop_id=$shop_id AND a.level='SHOP' AND b.shop_id!=''";
            }

            if (($shop_id < 0 || empty($shop_id)) && ($region_id < 0 || empty($region_id))
                && ($city_id < 0 || empty($city_id)) && ($country_id < 0 || empty($country_id))
                && ($area_id < 0 || empty($area_id))) {
                // $where = " WHERE a.shop_id!='' $adquery $catquery $branchquery $searchquery";
            }

            $append .= $where;

            $sql = "SELECT a.articlead_id , a.shop_id FROM zselex_article_ads a , zselex_shop b
                    WHERE a.status='1'
                    AND a.shop_id=b.shop_id
                   ".$append."
                    AND a.start_date<=CURDATE() AND a.end_date>=CURDATE() 
                    ORDER BY RAND() LIMIT 0 , 4";
            // echo $sql; exit;

            $statement = Doctrine_Manager::getInstance()->connection();
            $results   = $statement->execute($sql);
            $configs   = $results->fetchAll();
            // echo "<pre>"; print_r($configs); echo "</pre>";
            $counts    = $results->rowCount();
            // echo "<pre>"; print_r($configs[shop_id]); echo "</pre>";
            // echo $config['dbname'];
        } else {
            // echo "comes here"; exit;
            $counts = 0;
        }

        if ($counts > 0) {
            foreach ($configs as $shopid) {
                $shop_idarray [] = $shopid ['shop_id'];
            }

            $shop_ids = implode(",", $shop_idarray);
            // foreach ($result as $shop) {
            $shopsql  = " AND b.shop_id IN($shop_ids)";

            $shopquery = $shopsql;

            // echo $shopquery;

            $newsArray = array();
            foreach ($configs as $shopid) {

                $sql = "SELECT a.sid , a.title , a.hometext , b.shop_id , b.news_id
                        FROM news a , zselex_shop_news b 
                        WHERE a.sid=b.news_id AND b.shop_id=$shopid[shop_id] ORDER BY RAND() LIMIT 0 , 1";

                // echo $sql; exit;

                $query     = DBUtil::executeSQL($sql);
                $count     = $query->rowCount();
                $result [] = $query->fetch();
                $newsArray = $result;
                // $newsArray = array_merge($newsArray, $result);
            }

            // echo "<pre>"; print_r($newsArray); echo "</pre>"; exit;
            $data = '';

            $modvars             = ModUtil::getVar('News');
            $picupload_uploaddir = $modvars ['picupload_uploaddir'];
            $i                   = 1;
            $count               = sizeof($newsArray);
            foreach ($newsArray as $key => $news) {
                if ($i == $count) {
                    $noborder = " noborder";
                }
                $sid      = $news ['sid'];
                $title    = $news ['title'];
                $hometext = $news ['hometext'];
                $data .= "<div class='Sec1 $noborder'>";
                if ($news ['pictures'] > 0) {
                    $data .= "<a href='".ModUtil::url('ZSELEX', 'user',
                            'display',
                            array(
                            'sid' => $sid,
                            'shop_id' => $news [shop_id]
                        ))."'>";
                    $data .= "<p style='text-align:center; margin:0px; padding:0px'><img src=".pnGetBaseURL().$picupload_uploaddir."/pic_sid".$sid."-0-thumb.jpg ></p>";
                    $data .= "</a>";
                } else { // no image
                    // $data .= "<a href='" . ModUtil::url('ZSELEX', 'user', 'display', array(
                    // 'sid' => $sid,
                    // 'shop_id' => $news[shop_id]
                    // )) . "'>";
                    $data .= "<img src=".pnGetBaseURL()."/images/imageBlank.png >";
                    // $data .= "</a>";
                }

                $data .= "<p class='sec1H'>".$title."</p>";
                // $data .= "<span class='sec1T'>$hometext</span>";
                $data .= "<p class='sec1L'> <a href='".ModUtil::url('ZSELEX',
                        'user', 'display',
                        array(
                        'sid' => $sid,
                        'shop_id' => $news [shop_id]
                    ))."'> ".$this->__('Read more')."</a> </p>";
                $data .= "</div>";
                $i ++;
            }
        } else {
            $data .= '';
        }

        $output ["articlecount"] = $count;
        $output ["articles"]     = $data;

        // return $output;
        AjaxUtil::output($output);
    }

    public function getSpecialBlockProductAd1($args)
    { // For Special Deal Products High Advertisement Block
        // Zikula_View::getInstance('ZSELEX' , false)->setCaching(false);
        // $this->checkAjaxToken();
        $view    = Zikula_View::getInstance($this->name);
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        $country_id  = FormUtil::getPassedValue('country_id', null, 'REQUEST');
        $region_id   = FormUtil::getPassedValue('region_id', null, 'REQUEST');
        $city_id     = FormUtil::getPassedValue('city_id', null, 'REQUEST');
        $area_id     = FormUtil::getPassedValue('area_id', null, 'REQUEST');
        $category_id = FormUtil::getPassedValue('category_id', null, 'REQUEST');
        $branch_id   = FormUtil::getPassedValue('branch_id', null, 'REQUEST');
        $adtype      = FormUtil::getPassedValue('adtype', null, 'REQUEST');
        $amount      = FormUtil::getPassedValue('amount', null, 'REQUEST');
        $limit       = FormUtil::getPassedValue('limit', null, 'REQUEST');
        $search      = FormUtil::getPassedValue('hsearch', null, 'REQUEST');
        $search      = ($search == $this->__('search for...') || $search == $this->__('search'))
                ? '' : $search;

        $highad_args = array(
            'ad_type' => 'high',
            'country_id' => $country_id,
            'region_id' => $region_id,
            'city_id' => $city_id,
            'area_id' => $area_id,
            'shop_id' => $shop_id,
            'category_id' => $category_id,
            'branch_id' => $branch_id,
            'search' => $search,
            'limit' => $limit
        );

        $highad = ModUtil::apiFunc('ZSELEX', 'user', 'getSpecialDealAd',
                $highad_args);

        $productcount     = count($highad);
        $data             = '';
        $view->assign('highad', $highad);
        $output_tpl       = $view->fetch('ajax/productadhigh.tpl');
        $data .= new Zikula_Response_Ajax_Plain($output_tpl);
        $output ["count"] = $productcount;
        $output ["data"]  = $data;
        AjaxUtil::output($output);
    }

    public function getSpecialBlockProductAd($args)
    { // For Special Deal Products High Advertisement Block
        // Zikula_View::getInstance('ZSELEX' , false)->setCaching(false);
        // $this->checkAjaxToken();
        $view    = Zikula_View::getInstance($this->name);
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        $country_id  = FormUtil::getPassedValue('country_id', null, 'REQUEST');
        $region_id   = FormUtil::getPassedValue('region_id', null, 'REQUEST');
        $city_id     = FormUtil::getPassedValue('city_id', null, 'REQUEST');
        $area_id     = FormUtil::getPassedValue('area_id', null, 'REQUEST');
        $category_id = FormUtil::getPassedValue('category_id', null, 'REQUEST');
        $branch_id   = FormUtil::getPassedValue('branch_id', null, 'REQUEST');
        $aff_id      = FormUtil::getPassedValue('aff_id', null, 'REQUEST');
        $adtype      = FormUtil::getPassedValue('adtype', null, 'REQUEST');
        $amount      = FormUtil::getPassedValue('amount', null, 'REQUEST');
        $limit       = FormUtil::getPassedValue('limit', null, 'REQUEST');
        $search      = FormUtil::getPassedValue('hsearch', null, 'REQUEST');
        $search      = ($search == $this->__('search for...') || $search == $this->__('search'))
                ? '' : $search;

        $highad_args = array(
            'ad_type' => 'high',
            'country_id' => $country_id,
            'region_id' => $region_id,
            'city_id' => $city_id,
            'area_id' => $area_id,
            'shop_id' => $shop_id,
            'category_id' => $category_id,
            'branch_id' => $branch_id,
            'aff_id' => $aff_id,
            'search' => $search,
            'limit' => $limit
        );

        $highad = ModUtil::apiFunc('ZSELEX', 'user', 'getSpecialDealAd',
                $highad_args);

        $productcount     = count($highad);
        $data             = '';
        $view->assign('highad', $highad);
        $output_tpl       = $view->fetch('ajax/productadhigh.tpl');
        $data .= new Zikula_Response_Ajax_Plain($output_tpl);
        // return $data;
        $output ["count"] = $productcount;
        $output ["data"]  = $data;
        AjaxUtil::output($output);
    }

    public function getSpecialBlockEventImage($shop_id, $id, $from)
    {
        $imagepath = '';
        // echo $shop_id; exit;

        if ($from == 'product') {
            $sql       = "SELECT prd_image FROM zselex_products WHERE product_id=$id";
            $query     = DBUtil::executeSQL($sql);
            $res       = $query->fetch();
            $shopId    = $shop_id;
            $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                    $args      = array(
                    'shop_id' => $shop_id
            ));
            $image     = $res ['prd_image'];
            $imagepath = pnGetBaseURL()."zselexdata/$ownerName/products/thumb/$image";
            if (!empty($image) || file_exists($imagepath)) {
                $imagepath = pnGetBaseURL()."zselexdata/$ownerName/products/thumb/$image";
            } else {
                // $imagepath = "<img src=" . pnGetBaseURL() . "images/imageBlank.png>";
                $imagepath = pnGetBaseURL()."images/imageBlank.png";
            }
        } elseif ($from == 'article') {
            $modvars             = ModUtil::getVar('News');
            $picupload_uploaddir = $modvars ['picupload_uploaddir'];
            $sid                 = $id;
            $imagepath           = pnGetBaseURL().$picupload_uploaddir."/pic_sid".$sid."-0-thumb.jpg";
            if (file_exists($imagepath)) {
                $imagepath = pnGetBaseURL().$picupload_uploaddir."/pic_sid".$sid."-0-thumb.jpg";
            } else {
                // $imagepath = "<img src=" . pnGetBaseURL() . "images/imageBlank.png>";
                $imagepath = pnGetBaseURL()."images/imageBlank.png";
            }
        }
        // echo $imagepath; exit;
        return $imagepath;
    }

    public function getSpecialBlockEvents1($args)
    {
        // exit;
        // echo $this->thempath;
        // echo "<pre>"; echo "</pre>";
        $view        = Zikula_View::getInstance($this->name);
        $level       = $args ["level"];
        $shop_id     = $args ["shop_id"];
        $country_id  = $args ["country_id"];
        $region_id   = $args ["region_id"];
        $city_id     = $args ["city_id"];
        $area_id     = $args ["area_id"];
        $branch_id   = $args ["branch_id"];
        $category_id = $args ["category_id"];
        $search      = $args ["search"];
        $search      = ($search == $this->__('search for...') || $search == $this->__('search'))
                ? '' : $search;
        $eventlimit  = 4;
        $limit       = $_REQUEST ["limit"];
        $offset      = "4";
        if (!empty($limit)) {
            $offset = $limit;
        }
        // echo $shop_id; exit;
        // echo $reset; exit;
        if (!empty($eventlimit)) {
            $limit      = $eventlimit;
            $limitquery = "LIMIT 0 , $eventlimit";
        } else {
            $limit      = "2";
            $limitquery = "LIMIT 0 , $limit";
        }
        // $limit = 2;
        $searchquery = '';
        if (($shop_id > 0 || !empty($shop_id)) || ($region_id > 0 || !empty($region_id))
            || ($city_id > 0 || !empty($city_id)) || ($country_id > 0 || !empty($country_id))
            || ($area_id > 0 || !empty($area_id)) || ($search > 0 || !empty($search))
            || ($category_id > 0 || !empty($category_id)) || ($branch_id > 0 || !empty($branch_id)
            || !empty($eventdate))) {

            // $eventdateqry = " AND shop_event_startdate>=CURDATE()";
            $eventdateqry = '';
            $output       = '';

            // $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
            $where  = '';
            $append = '';

            if (!empty($country_id)) { // COUNTRY
                $append .= " AND a.country_id=$country_id";
            }

            if (!empty($region_id)) { // REGION
                $append .= " AND a.region_id=$region_id";
            }

            if (!empty($city_id)) { // CITY
                $append .= " AND a.city_id=$city_id";
            }

            if (!empty($area_id)) { // AREA
                $append .= " AND a.area_id=$area_id";
            }

            if (!empty($shop_id)) { // SHOP
                $append .= " AND a.shop_id=$shop_id";
            }
            if (!empty($category_id)) {
                $append .= " AND a.cat_id=$category_id";
            }

            if (!empty($branch_id)) {
                $append .= " AND a.branch_id=$branch_id";
            }

            if (!empty($search)) {
                // $append .= " AND (b.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE '%" . DataUtil::formatForStore($search) . "%') OR b.shop_id IN (SELECT shop_id FROM zselex_shop WHERE shop_name LIKE '%" . DataUtil::formatForStore($search) . "%'))";
                $append .= " AND (a.shop_name LIKE '%".DataUtil::formatForStore($search)."%' OR MATCH (a.shop_name) AGAINST ('".DataUtil::formatForStore($search)."' IN BOOLEAN MODE) OR a.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE '%".DataUtil::formatForStore($search)."%' OR MATCH (keyword) AGAINST ('".DataUtil::formatForStore($search)."' IN BOOLEAN MODE)))";
            }

            // echo $append; exit;
            $sql              = "SELECT a.shop_id , b.shop_event_id ,
                        b.shop_event_name , b.shop_event_shortdescription , b.shop_event_description , b.shop_event_keywords , b.news_article_id , b.event_image , b.event_doc , b.product_id , b.showfrom , b.price
                        FROM zselex_shop a , zselex_shop_events b
                        WHERE a.shop_id IS NOT NULL
                        AND a.status=1
                        AND b.status=1
                        AND a.shop_id = b.shop_id
                        AND (b.shop_event_startdate >=CURDATE() OR b.shop_event_startdate <=CURDATE()) AND b.shop_event_enddate >=CURDATE() 
                        AND (b.activation_date<=CURDATE() OR UNIX_TIMESTAMP(b.activation_date) = 0 OR b.activation_date IS NULL) 
                        $append ORDER BY RAND() LIMIT 0 , $offset";
            // echo $sql; exit;
            $query            = DBUtil::executeSQL($sql);
            $all_shops_events = $query->fetchAll();
            // echo "<pre>"; print_r($all_shops); echo "</pre>"; exit;
            $count            = count($all_shops_events);
        } else {

            $count = 0;
        }
        $shopsql = '';
        if ($count > 0) {

            $eventArray = array();
            $results    = array();
            // $existing_event = array();
            // echo "<pre>"; print_r($results); echo "</pre>"; exit;
            // echo "<pre>"; print_r($reating_events); echo "</pre>"; exit;

            $eventArray = $all_shops_events;
            // $eventArray = array_merge($eventArray, $results);
            // echo "<pre>"; print_r($eventArray); echo "</pre>"; exit;
            $eventcount = count($eventArray);

            // echo "count :" . $eventcount; exit;

            if ($eventcount > 0) {
                $todayDate     = date("Y-m-d");
                $current_theme = System::getVar('Default_Theme');
                // exit;

                if ($current_theme == 'CityPilot') {
                    // echo "hellooo";
                    $k = 1;
                    foreach ($eventArray as $key => $d2) {

                        if ($k == $eventcount) {
                            $noborder = " noborder";
                        }
                        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin',
                                'getOwner',
                                $args      = array(
                                'shop_id' => $d2 [shop_id]
                        ));
                        if (!empty($d2 ['price']) && is_numeric($d2 ['price'])) {
                            $price = $d2 ['price'];
                        } else {
                            $price = $this->__("FREE");
                        }

                        // $output .= "<div class='Sec1 $noborder'>";
                        if ($d2 [showfrom] == 'product') {
                            // echo "productId :" . $d2['product_id'];
                            // $output .= $this->getSpecialBlockEventImage($shop_id, $product_id = $d2['product_id'], $from = 'product');
                            $imagepath     = $this->getSpecialBlockEventImage($d2 [shop_id],
                                $d2 ['product_id'], $from          = 'product');
                            $img_args      = array(
                                'imagepath' => $imagepath,
                                'setheight' => '90',
                                'setwidth' => '125'
                            );
                            $new_resize    = ModUtil::apiFunc('ZSELEX', 'admin',
                                    'imageProportional', $args          = $img_args);
                            $H             = $new_resize ['new_height'];
                            $W             = $new_resize ['new_width'];
                            $display_image = "<img style='width:".$W."px;height:".$H."px' src='".$this->getSpecialBlockEventImage($d2 [shop_id],
                                    $d2 ['product_id'], $from          = 'product')."'>";
                            // $output .= "<img src='" . $this->getSpecialBlockEventImage($d2[shop_id], $d2['product_id'], $from = 'product') . "'>";
                        } elseif ($d2 [showfrom] == 'article') {
                            $display_image = "<a  href='".ModUtil::url('ZSELEX',
                                    'user', 'viewevent',
                                    array(
                                    'shop_id' => $d2 [shop_id],
                                    'eventId' => $d2 [shop_event_id]
                                ))."'><img style='width:".$W."px;height:".$H."px' src='".$this->getSpecialBlockEventImage($d2 [shop_id],
                                    $d2 ['news_article_id'], $from          = 'article')."'></a>";
                            // $output .= "<a href='" . ModUtil::url('ZSELEX', 'user', 'viewevent', array('shop_id' => $d2[shop_id], 'eventId' => $d2[shop_event_id])) . "'><img src='" . $this->getSpecialBlockEventImage($d2[shop_id], $d2['news_article_id'], $from = 'article') . "'></a>";
                        } elseif ($d2 [showfrom] == 'image') {
                            // $output .= $this->getSpecialBlockEventImage($shop_id, $event_id = $d2['shop_event_id'], $from = 'image');
                            if (file_exists("zselexdata/".$ownerName."/events/thumb/$d2[event_image]")) {
                                $imagepath     = pnGetBaseURL()."zselexdata/$ownerName/events/thumb/$d2[event_image]";
                                $img_args      = array(
                                    'imagepath' => $imagepath,
                                    'setheight' => '90',
                                    'setwidth' => '125'
                                );
                                $new_resize    = ModUtil::apiFunc('ZSELEX',
                                        'admin', 'imageProportional',
                                        $args          = $img_args);
                                $H             = $new_resize ['new_height'];
                                $W             = $new_resize ['new_width'];
                                $display_image = "<a  href='".ModUtil::url('ZSELEX',
                                        'user', 'viewevent',
                                        array(
                                        'shop_id' => $d2 [shop_id],
                                        'eventId' => $d2 [shop_event_id]
                                    ))."'><img style='width:".$W."px;height:".$H."px' src='zselexdata/".$ownerName."/events/thumb/".$d2 [event_image]."'></a>";
                                // $output .= "<a href='" . ModUtil::url('ZSELEX', 'user', 'viewevent', array('shop_id' => $d2[shop_id], 'eventId' => $d2[shop_event_id])) . "'><img src='zselexdata/" . $ownerName . "/events/thumb/" . $d2[event_image] . "'></a>";
                            } else {
                                $display_image = "<img src=".pnGetBaseURL()."/images/imageBlank.png >";
                                // $output .= "<img src=" . pnGetBaseURL() . "/images/imageBlank.png >";
                            }
                        } elseif ($d2 [showfrom] == 'doc') {
                            // $output .= $this->getSpecialBlockEventImage($shop_id, $event_id = $d2['shop_event_id'], $from = 'doc');
                            $display_image = "<a  href='".ModUtil::url('ZSELEX',
                                    'user', 'viewevent',
                                    array(
                                    'shop_id' => $d2 [shop_id],
                                    'eventId' => $d2 [shop_event_id]
                                ))."'><img src='images/pdf.png'></a>";
                            // $output .= "<a href='" . ModUtil::url('ZSELEX', 'user', 'viewevent', array('shop_id' => $d2[shop_id], 'eventId' => $d2[shop_event_id])) . "'><img src='images/pdf.png'></a>";
                        } else {
                            $display_image = "<img src=".pnGetBaseURL()."/images/imageBlank.png >";
                            // $output .= "<img src=" . pnGetBaseURL() . "/images/imageBlank.png >";
                        }

                        $view->assign('noborder', $noborder);
                        $view->assign('event', $d2);
                        $view->assign('price', $price);
                        $view->assign('display_image', $display_image);
                        $output_event = $view->fetch('ajax/specialdealevents.tpl');
                        $output .= new Zikula_Response_Ajax_Plain($output_event);

                        /*
                         * $output .= '
                         *
                         * <p class="sec1H">
                         * <a href="' . ModUtil::url('ZSELEX', 'user', 'viewevent', array('shop_id' => $d2[shop_id], 'eventId' => $d2[shop_event_id])) . '">' . $d2[shop_event_name] . '</a>
                         * </p>
                         *
                         *
                         * ';
                         *
                         * $output .= "<p class='sec1L'> $price</p>";
                         * $output .= "</div>";
                         */
                        $l ++;
                        // }

                        $k ++;
                    }
                    // echo "J :" . $j;
                } else {
                    // echo "hellooo";
                    foreach ($arrays ['dates'] as $key => $d1) {
                        if ($k == $limit) {
                            break;
                        }

                        $dayname = date_format(date_create($key), 'l');
                        $output .= "<div>
                               <b>$key $dayname</b> </br>";
                        foreach ($arrays ['dates'] [$key] as $key2 => $d2) {
                            $output .= "&nbsp;&nbsp;<a href='".ModUtil::url('ZSELEX',
                                    'user', 'viewevent',
                                    array(
                                    'shop_id' => $d2 ['shop_id'],
                                    'eventId' => $d2 ['shop_event_id']
                                ))."'>$d2[shop_event_name]</a><br>
                                 <div>";
                        }
                        $output .= "</br>";
                        $k ++;
                    }
                }

                // if ($totalCount > $eventcount) {
                // $output .= '<div style="cursor:pointer" align=right onclick=getMoreEvents()> <font color="blue">more events... </font> </div>';
                // $output .= "<div style='cursor:pointer' onclick=check(document.getElementById('hcountry').value,document.getElementById('hregion').value,document.getElementById('hcity').value,document.getElementById('harea').value,document.getElementById('hshop').value,document.getElementById('hcategory').value,document.getElementById('hbranch').value,document.getElementById('hsearch').value)> <font color='blue'>" . $this->__('All Events...') . " </font> </div>";
                // $output .= "<div style='cursor:pointer'><a class='infoclass' id='allevents' href='" . ModUtil::url('ZSELEX', 'info', 'showEvents') . "'>All Events...</a></div>";
                // }
            } else {
                // $output .= '<dt> &nbsp;&nbsp;&nbsp;&nbsp; ' . $this->__('No Events Found') . ' </dt>';
                $output = '';
            }
            // }
        } else {

            $output .= '';
        }

        $output .= '</dl>';

        $eventoutput ['eventscount'] = $eventcount;
        $eventoutput ['events']      = $output;
        return $eventoutput;
        // AjaxUtil::output($output);
        // ZSELEX_Util::ajaxOutput($eventoutput);
    }

    public function getSpecialBlockEvents($args)
    {
        // exit;
        // echo $this->thempath;
        // echo "<pre>"; print_r($args); echo "</pre>";
        $view        = Zikula_View::getInstance($this->name);
        $level       = $args ["level"];
        $shop_id     = $args ["shop_id"];
        $country_id  = $args ["country_id"];
        $region_id   = $args ["region_id"];
        $city_id     = $args ["city_id"];
        $area_id     = $args ["area_id"];
        $branch_id   = $args ["branch_id"];
        $category_id = $args ["category_id"];
        $search      = $args ["search"];
        $search      = ($search == $this->__('search for...') || $search == $this->__('search'))
                ? '' : $search;
        $eventlimit  = 4;
        $limit       = $args ["limit"];
        $aff_id      = $args ["aff_id"];
        $event_args  = array(
            'country_id' => $country_id,
            'region_id' => $region_id,
            'city_id' => $city_id,
            'area_id' => $area_id,
            'shop_id' => $shop_id,
            'category_id' => $category_id,
            'branch_id' => $branch_id,
            'search' => $search,
            'aff_id' => $aff_id,
            'limit' => $limit
        );

        $events      = ModUtil::apiFunc('ZSELEX', 'user',
                'getSpecialBlockEvents', $event_args);
        $eventcount  = count($events);
        $eventcounts = count($events);
        // $eventcount = 3;

        $view->assign('events', $events);
        $view->assign('eventcount', $eventcount);
        $output_tpl                  = $view->fetch('ajax/specialdealevents.tpl');
        $data                        = '';
        $data .= new Zikula_Response_Ajax_Plain($output_tpl);
        $eventoutput ['eventscount'] = $eventcounts;
        $eventoutput ['events']      = $data;
        return $eventoutput;
        // AjaxUtil::output($output);
        // ZSELEX_Util::ajaxOutput($eventoutput);
    }

    public function getSpecialBlockEvents2($args)
    {
        // exit;
        // echo $this->thempath;
        // echo "<pre>"; echo "</pre>";
        $view        = Zikula_View::getInstance($this->name);
        $level       = $args ["level"];
        $shop_id     = $args ["shop_id"];
        $country_id  = $args ["country_id"];
        $region_id   = $args ["region_id"];
        $city_id     = $args ["city_id"];
        $area_id     = $args ["area_id"];
        $branch_id   = $args ["branch_id"];
        $category_id = $args ["category_id"];
        $search      = $args ["search"];
        $search      = ($search == $this->__('search for...') || $search == $this->__('search'))
                ? '' : $search;
        $eventlimit  = 4;
        $limit       = $_REQUEST ["limit"];
        $offset      = "4";
        if (!empty($limit)) {
            $offset = $limit;
        }
        // echo $shop_id; exit;
        // echo $reset; exit;
        if (!empty($eventlimit)) {
            $limit      = $eventlimit;
            $limitquery = "LIMIT 0 , $eventlimit";
        } else {
            $limit      = "2";
            $limitquery = "LIMIT 0 , $limit";
        }
        // $limit = 2;
        $searchquery = '';
        if (($shop_id > 0 || !empty($shop_id)) || ($region_id > 0 || !empty($region_id))
            || ($city_id > 0 || !empty($city_id)) || ($country_id > 0 || !empty($country_id))
            || ($area_id > 0 || !empty($area_id)) || ($search > 0 || !empty($search))
            || ($category_id > 0 || !empty($category_id)) || ($branch_id > 0 || !empty($branch_id)
            || !empty($eventdate))) {

            // $eventdateqry = " AND shop_event_startdate>=CURDATE()";
            $eventdateqry = '';
            $output       = '';

            // $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
            $where  = '';
            $append = '';

            if (!empty($country_id)) { // COUNTRY
                $append .= " AND a.country_id=$country_id";
            }

            if (!empty($region_id)) { // REGION
                $append .= " AND a.region_id=$region_id";
            }

            if (!empty($city_id)) { // CITY
                $append .= " AND a.city_id=$city_id";
            }

            if (!empty($area_id)) { // AREA
                $append .= " AND a.area_id=$area_id";
            }

            if (!empty($shop_id)) { // SHOP
                $append .= " AND a.shop_id=$shop_id";
            }
            if (!empty($category_id)) {
                $append .= " AND a.cat_id=$category_id";
            }

            if (!empty($branch_id)) {
                $append .= " AND a.branch_id=$branch_id";
            }

            if (!empty($search)) {

                $append .= " AND (b.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE '%".DataUtil::formatForStore($search)."%') OR b.shop_id IN (SELECT shop_id FROM zselex_shop WHERE shop_name LIKE '%".DataUtil::formatForStore($search)."%'))";
            }

            $sql       = "SELECT a.shop_id , b.shop_event_id
                        FROM zselex_shop a , zselex_shop_events b
                        WHERE a.shop_id IS NOT NULL
                        AND a.shop_id = b.shop_id
                        AND a.status='1' $append ORDER BY RAND() LIMIT 0 , $offset";
            // echo $sql; exit;
            $query     = DBUtil::executeSQL($sql);
            $all_shops = $query->fetchAll();
            // echo "<pre>"; print_r($all_shops); echo "</pre>"; exit;
            $count     = count($all_shops);
        } else {

            $count = 0;
        }
        $shopsql = '';
        if ($count > 0) {

            $eventArray = array();
            $results    = array();
            // $existing_event = array();
            foreach ($all_shops as $shopid) {
                if (!empty($existing_event)) {
                    $reating_events = implode(',', $existing_event);
                    $appnd          = " AND shop_event_id NOT IN($reating_events) ";
                }
                $sql = "SELECT shop_event_id , shop_id , shop_event_name , shop_event_shortdescription , shop_event_description , shop_event_keywords , news_article_id , event_image , event_doc , product_id , showfrom , price
                        FROM zselex_shop_events
                        WHERE shop_id=$shopid[shop_id] AND (shop_event_startdate >=CURDATE() OR shop_event_startdate <=CURDATE() ) AND shop_event_enddate >=CURDATE() AND status=1
                        $appnd ORDER BY RAND()";

                // echo $sql; exit;

                $query = DBUtil::executeSQL($sql);
                $count = $query->rowCount();
                $res   = $query->fetch();

                // $newsArray = $result;
                if ($count > 0) {
                    // $results[] = $event_s;
                    $results []        = $res;
                    $existing_event [] = $res [shop_event_id];
                }
            }
            // echo "<pre>"; print_r($results); echo "</pre>"; exit;
            // echo "<pre>"; print_r($reating_events); echo "</pre>"; exit;

            $eventArray = $results;
            // $eventArray = array_merge($eventArray, $results);
            // echo "<pre>"; print_r($eventArray); echo "</pre>"; exit;
            $eventcount = count($eventArray);

            // echo "count :" . $eventcount; exit;

            if ($eventcount > 0) {
                $todayDate     = date("Y-m-d");
                $current_theme = System::getVar('Default_Theme');
                // exit;

                if ($current_theme == 'CityPilot') {
                    // echo "hellooo";
                    $k = 1;
                    foreach ($eventArray as $key => $d2) {

                        if ($k == $eventcount) {
                            $noborder = " noborder";
                        }
                        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin',
                                'getOwner',
                                $args      = array(
                                'shop_id' => $d2 [shop_id]
                        ));
                        if (!empty($d2 ['price']) && is_numeric($d2 ['price'])) {
                            $price = $d2 ['price'];
                        } else {
                            $price = $this->__("FREE");
                        }

                        // $output .= "<div class='Sec1 $noborder'>";
                        if ($d2 [showfrom] == 'product') {
                            // echo "productId :" . $d2['product_id'];
                            // $output .= $this->getSpecialBlockEventImage($shop_id, $product_id = $d2['product_id'], $from = 'product');
                            $imagepath     = $this->getSpecialBlockEventImage($d2 [shop_id],
                                $d2 ['product_id'], $from          = 'product');
                            $img_args      = array(
                                'imagepath' => $imagepath,
                                'setheight' => '90',
                                'setwidth' => '125'
                            );
                            $new_resize    = ModUtil::apiFunc('ZSELEX', 'admin',
                                    'imageProportional', $args          = $img_args);
                            $H             = $new_resize ['new_height'];
                            $W             = $new_resize ['new_width'];
                            $display_image = "<img style='width:".$W."px;height:".$H."px' src='".$this->getSpecialBlockEventImage($d2 [shop_id],
                                    $d2 ['product_id'], $from          = 'product')."'>";
                            // $output .= "<img src='" . $this->getSpecialBlockEventImage($d2[shop_id], $d2['product_id'], $from = 'product') . "'>";
                        } elseif ($d2 [showfrom] == 'article') {
                            $display_image = "<a  href='".ModUtil::url('ZSELEX',
                                    'user', 'viewevent',
                                    array(
                                    'shop_id' => $d2 [shop_id],
                                    'eventId' => $d2 [shop_event_id]
                                ))."'><img style='width:".$W."px;height:".$H."px' src='".$this->getSpecialBlockEventImage($d2 [shop_id],
                                    $d2 ['news_article_id'], $from          = 'article')."'></a>";
                            // $output .= "<a href='" . ModUtil::url('ZSELEX', 'user', 'viewevent', array('shop_id' => $d2[shop_id], 'eventId' => $d2[shop_event_id])) . "'><img src='" . $this->getSpecialBlockEventImage($d2[shop_id], $d2['news_article_id'], $from = 'article') . "'></a>";
                        } elseif ($d2 [showfrom] == 'image') {
                            // $output .= $this->getSpecialBlockEventImage($shop_id, $event_id = $d2['shop_event_id'], $from = 'image');
                            if (file_exists("zselexdata/".$ownerName."/events/thumb/$d2[event_image]")) {
                                $imagepath     = pnGetBaseURL()."zselexdata/$ownerName/events/thumb/$d2[event_image]";
                                $img_args      = array(
                                    'imagepath' => $imagepath,
                                    'setheight' => '90',
                                    'setwidth' => '125'
                                );
                                $new_resize    = ModUtil::apiFunc('ZSELEX',
                                        'admin', 'imageProportional',
                                        $args          = $img_args);
                                $H             = $new_resize ['new_height'];
                                $W             = $new_resize ['new_width'];
                                $display_image = "<a  href='".ModUtil::url('ZSELEX',
                                        'user', 'viewevent',
                                        array(
                                        'shop_id' => $d2 [shop_id],
                                        'eventId' => $d2 [shop_event_id]
                                    ))."'><img style='width:".$W."px;height:".$H."px' src='zselexdata/".$ownerName."/events/thumb/".$d2 [event_image]."'></a>";
                                // $output .= "<a href='" . ModUtil::url('ZSELEX', 'user', 'viewevent', array('shop_id' => $d2[shop_id], 'eventId' => $d2[shop_event_id])) . "'><img src='zselexdata/" . $ownerName . "/events/thumb/" . $d2[event_image] . "'></a>";
                            } else {
                                $display_image = "<img src=".pnGetBaseURL()."/images/imageBlank.png >";
                                // $output .= "<img src=" . pnGetBaseURL() . "/images/imageBlank.png >";
                            }
                        } elseif ($d2 [showfrom] == 'doc') {
                            // $output .= $this->getSpecialBlockEventImage($shop_id, $event_id = $d2['shop_event_id'], $from = 'doc');
                            $display_image = "<a  href='".ModUtil::url('ZSELEX',
                                    'user', 'viewevent',
                                    array(
                                    'shop_id' => $d2 [shop_id],
                                    'eventId' => $d2 [shop_event_id]
                                ))."'><img src='images/pdf.png'></a>";
                            // $output .= "<a href='" . ModUtil::url('ZSELEX', 'user', 'viewevent', array('shop_id' => $d2[shop_id], 'eventId' => $d2[shop_event_id])) . "'><img src='images/pdf.png'></a>";
                        } else {
                            $display_image = "<img src=".pnGetBaseURL()."/images/imageBlank.png >";
                            // $output .= "<img src=" . pnGetBaseURL() . "/images/imageBlank.png >";
                        }

                        $view->assign('noborder', $noborder);
                        $view->assign('event', $d2);
                        $view->assign('price', $price);
                        $view->assign('display_image', $display_image);
                        $output_event = $view->fetch('ajax/specialdealevents.tpl');
                        $output .= new Zikula_Response_Ajax_Plain($output_event);

                        /*
                         * $output .= '
                         *
                         * <p class="sec1H">
                         * <a href="' . ModUtil::url('ZSELEX', 'user', 'viewevent', array('shop_id' => $d2[shop_id], 'eventId' => $d2[shop_event_id])) . '">' . $d2[shop_event_name] . '</a>
                         * </p>
                         *
                         *
                         * ';
                         *
                         * $output .= "<p class='sec1L'> $price</p>";
                         * $output .= "</div>";
                         */
                        $l ++;
                        // }

                        $k ++;
                    }
                    // echo "J :" . $j;
                } else {
                    // echo "hellooo";
                    foreach ($arrays ['dates'] as $key => $d1) {
                        if ($k == $limit) {
                            break;
                        }

                        $dayname = date_format(date_create($key), 'l');
                        $output .= "<div>
                               <b>$key $dayname</b> </br>";
                        foreach ($arrays ['dates'] [$key] as $key2 => $d2) {
                            $output .= "&nbsp;&nbsp;<a href='".ModUtil::url('ZSELEX',
                                    'user', 'viewevent',
                                    array(
                                    'shop_id' => $d2 ['shop_id'],
                                    'eventId' => $d2 ['shop_event_id']
                                ))."'>$d2[shop_event_name]</a><br>
                                 <div>";
                        }
                        $output .= "</br>";
                        $k ++;
                    }
                }

                // if ($totalCount > $eventcount) {
                // $output .= '<div style="cursor:pointer" align=right onclick=getMoreEvents()> <font color="blue">more events... </font> </div>';
                // $output .= "<div style='cursor:pointer' onclick=check(document.getElementById('hcountry').value,document.getElementById('hregion').value,document.getElementById('hcity').value,document.getElementById('harea').value,document.getElementById('hshop').value,document.getElementById('hcategory').value,document.getElementById('hbranch').value,document.getElementById('hsearch').value)> <font color='blue'>" . $this->__('All Events...') . " </font> </div>";
                // $output .= "<div style='cursor:pointer'><a class='infoclass' id='allevents' href='" . ModUtil::url('ZSELEX', 'info', 'showEvents') . "'>All Events...</a></div>";
                // }
            } else {
                // $output .= '<dt> &nbsp;&nbsp;&nbsp;&nbsp; ' . $this->__('No Events Found') . ' </dt>';
                $output = '';
            }
            // }
        } else {

            $output .= '';
        }

        $output .= '</dl>';

        $eventoutput ['eventscount'] = $eventcount;
        $eventoutput ['events']      = $output;
        return $eventoutput;
        // AjaxUtil::output($output);
        // ZSELEX_Util::ajaxOutput($eventoutput);
    }

    public function getSpecialBlockProductMidAd($args)
    { // For Special Deal Products Advertisement Block
        $view    = Zikula_View::getInstance($this->name);
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        $country_id  = FormUtil::getPassedValue('country_id', null, 'REQUEST');
        $region_id   = FormUtil::getPassedValue('region_id', null, 'REQUEST');
        $city_id     = FormUtil::getPassedValue('city_id', null, 'REQUEST');
        $area_id     = FormUtil::getPassedValue('area_id', null, 'REQUEST');
        $category_id = FormUtil::getPassedValue('category_id', null, 'REQUEST');
        $branch_id   = FormUtil::getPassedValue('branch_id', null, 'REQUEST');
        $adtype      = FormUtil::getPassedValue('adtype', null, 'REQUEST');
        $amount      = FormUtil::getPassedValue('amount', null, 'REQUEST');
        $limit       = FormUtil::getPassedValue('limit', null, 'REQUEST');
        $search      = FormUtil::getPassedValue('hsearch', null, 'REQUEST');
        $search      = ($search == $this->__('search for...') || $search == $this->__('search'))
                ? '' : $search;
        $aff_id      = FormUtil::getPassedValue('aff_id', null, 'REQUEST');

        $midad_args = array(
            'ad_type' => 'mid',
            'country_id' => $country_id,
            'region_id' => $region_id,
            'city_id' => $city_id,
            'area_id' => $area_id,
            'shop_id' => $shop_id,
            'category_id' => $category_id,
            'branch_id' => $branch_id,
            'aff_id' => $aff_id,
            'search' => $search,
            'limit' => $limit
        );

        $midad = ModUtil::apiFunc('ZSELEX', 'user', 'getSpecialDealAd',
                $midad_args);

        $productcount     = count($midad);
        $midadCount       = count($midad);
        $data             = '';
        $view->assign('midad', $midad);
        $view->assign('midadCount', $midadCount);
        $output_tpl       = $view->fetch('ajax/productadmid.tpl');
        $data .= new Zikula_Response_Ajax_Plain($output_tpl);
        // return $data;
        $output ["count"] = $productcount;
        $output ["data"]  = $data;
        AjaxUtil::output($output);
    }

    public function getSpecialBlockProductLowAd($args)
    { // For Special Deal Low Products Advertisement Block
        $view    = Zikula_View::getInstance($this->name);
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        $country_id  = FormUtil::getPassedValue('country_id', null, 'REQUEST');
        $region_id   = FormUtil::getPassedValue('region_id', null, 'REQUEST');
        $city_id     = FormUtil::getPassedValue('city_id', null, 'REQUEST');
        $area_id     = FormUtil::getPassedValue('area_id', null, 'REQUEST');
        $category_id = FormUtil::getPassedValue('category_id', null, 'REQUEST');
        $branch_id   = FormUtil::getPassedValue('branch_id', null, 'REQUEST');
        $adtype      = FormUtil::getPassedValue('adtype', null, 'REQUEST');
        $amount      = FormUtil::getPassedValue('amount', null, 'REQUEST');
        $limit       = FormUtil::getPassedValue('limit', null, 'REQUEST');
        $search      = FormUtil::getPassedValue('hsearch', null, 'REQUEST');
        $search      = ($search == $this->__('search for...') || $search == $this->__('search'))
                ? '' : $search;
        $aff_id      = FormUtil::getPassedValue('aff_id', null, 'REQUEST');

        $lowad_args = array(
            'ad_type' => 'low',
            'country_id' => $country_id,
            'region_id' => $region_id,
            'city_id' => $city_id,
            'area_id' => $area_id,
            'shop_id' => $shop_id,
            'category_id' => $category_id,
            'branch_id' => $branch_id,
            'aff_id' => $aff_id,
            'search' => $search,
            'limit' => $limit
        );

        $lowad = ModUtil::apiFunc('ZSELEX', 'user', 'getSpecialDealAd',
                $lowad_args);

        $productcount     = count($lowad);
        $lowadCount       = count($lowad);
        $data             = '';
        $view->assign('lowad', $lowad);
        $view->assign('lowadCount', $lowadCount);
        $output_tpl       = $view->fetch('ajax/productadlow.tpl');
        $data .= new Zikula_Response_Ajax_Plain($output_tpl);
        // return $data;
        $output ["count"] = $productcount;
        $output ["data"]  = $data;
        AjaxUtil::output($output);
    }

    public function special_deal_event_block($args)
    {
        // exit;
        $level       = FormUtil::getPassedValue("level");
        $shop_id     = FormUtil::getPassedValue("shop_id");
        $country_id  = FormUtil::getPassedValue("country_id");
        $region_id   = FormUtil::getPassedValue("region_id");
        $city_id     = FormUtil::getPassedValue("city_id");
        $area_id     = FormUtil::getPassedValue("area_id");
        $category_id = FormUtil::getPassedValue("category_id");
        $branch_id   = FormUtil::getPassedValue("branch_id");
        $adtype      = FormUtil::getPassedValue("adtype");
        $amount      = FormUtil::getPassedValue("amount");
        $search      = FormUtil::getPassedValue("hsearch");
        $search      = ($search == $this->__('search for...') || $search == $this->__('search'))
                ? '' : $search;

        $aff_id = FormUtil::getPassedValue('aff_id');
        // echo "<pre>"; print_r($aff_id); echo "</pre>";

        $args = array(
            'level' => $level,
            'shop_id' => $shop_id,
            'country_id' => $country_id,
            'region_id' => $region_id,
            'city_id' => $city_id,
            'area_id' => $area_id,
            'category_id' => $category_id,
            'branch_id' => $branch_id,
            'adtype' => $adtype,
            'amount' => $amount,
            'limit' => $amount,
            'search' => $search,
            'aff_id' => $aff_id
        );

        // $products = $this->getSpecialBlockProductAd($args);
        // $products_mid = $this->getSpecialBlockProductMidAd($args);
        // $articles = $this->getSpecialBlockArticleAd($args);
        $events            = $this->getSpecialBlockEvents($args);
        // $output["products"] = $products;
        // $output["productsMid"] = $products_mid;
        // $output["articles"] = $articles;
        $output ["events"] = $events;
        AjaxUtil::output($output);
    }

    public function showEvents($args)
    {

        // echo "<pre>"; print_r($_REQUEST); echo "</pre>";
        $view        = Zikula_View::getInstance($this->name);
        $repo        = $this->entityManager->getRepository('ZSELEX_Entity_Event');
        $setParams   = array();
        $level       = FormUtil::getPassedValue("level");
        $shop_id     = FormUtil::getPassedValue("shop_id");
        $country_id  = FormUtil::getPassedValue("country_id");
        $region_id   = FormUtil::getPassedValue("region_id");
        $city_id     = FormUtil::getPassedValue("city_id");
        $area_id     = FormUtil::getPassedValue("area_id");
        $branch_id   = FormUtil::getPassedValue("branch_id");
        $category_id = FormUtil::getPassedValue("category_id");
        $search      = FormUtil::getPassedValue("hsearch");
        $search      = ($search == $this->__('search for...') || $search == $this->__('search'))
                ? '' : $search;
        // $eventlimit = FormUtil::getPassedValue("eventlimit");
        $startlimit  = FormUtil::getPassedValue("startlimit");
        $firstLoad   = FormUtil::getPassedValue("firstLoad");
        // $startlimit = 0;
        // echo $startlimit;
        $eventlimit  = FormUtil::getPassedValue("eventlimit");

        // $start = $startlimit + $eventlimit;
        $start = $startlimit;
        if ($firstLoad) {
            // $start = 0;
        }
        // $limit = $start;
        $limit = $start + 10;
        $end   = $eventlimit;
        // echo $limitquery;

        $searchquery = '';
        if (($shop_id > 0 || !empty($shop_id)) || ($region_id > 0 || !empty($region_id))
            || ($city_id > 0 || !empty($city_id)) || ($country_id > 0 || !empty($country_id))
            || ($area_id > 0 || !empty($area_id)) || ($search > 0 || !empty($search))
            || ($category_id > 0 || !empty($category_id)) || ($branch_id > 0 || !empty($branch_id)
            || !empty($eventdate))) {

            // $eventdateqry = " AND shop_event_startdate>=CURDATE()";
            $eventdateqry = '';
            $output       = '';
            $items        = array(
                'id' => $shop_id
            );

            $where  = '';
            $append = '';

            if (!empty($country_id)) { // COUNTRY
                $append .= " AND b.country_id=$country_id";
            }

            if (!empty($region_id)) { // REGION
                $append .= " AND b.region_id=$region_id";
            }

            if (!empty($city_id)) { // CITY
                $append .= " AND b.city_id=$city_id";
            }

            if (!empty($area_id)) { // AREA
                $append .= " AND b.area_id=$area_id";
            }

            if (!empty($shop_id)) { // SHOP
                $append .= " AND b.shop_id=$shop_id";
            }
            $join = "";
            if (!empty($category_id)) {
                $append .= " AND c.category_id=$category_id ";
                $join = " INNER JOIN zselex_shop_to_category c ON c.shop_id=b.shop_id ";
            }

            if (!empty($branch_id)) {
                $append .= " AND b.branch_id=$branch_id";
            }

            if (!empty($search)) {
                // $append .= " AND (a.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE '%" . DataUtil::formatForStore($search) . "%') OR a.shop_id IN (SELECT shop_id FROM zselex_shop WHERE shop_name LIKE '%" . DataUtil::formatForStore($search) . "%'))";
                $append .= " AND (b.shop_name LIKE :search OR MATCH (b.shop_name) AGAINST (:search2) OR b.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE :search OR MATCH (keyword) AGAINST (:search2)))";
                $setParams += array(
                    'search' => '%'.DataUtil::formatForStore($search).'%',
                    'search2' => DataUtil::formatForStore($search)
                );
            }

            $event_args = array(
                'append' => $append,
                'join' => $join,
                'setParams' => $setParams,
                'start' => $start,
                'end' => $end
            );

            // echo "<pre>"; print_r($event_args); echo "</pre>";
            $result = $repo->getAllEvents($event_args);

            $count = $repo->getAllEventsCount(array(
                'append' => $append,
                'join' => $join,
                'setParams' => $setParams
            ));

            /*
             * $countNext = $repo->getAllEventsCount(
             * array('append' => $append,
             * 'join' => $join,
             * 'setParams' => $setParams,
             * 'start' => $start + 10,
             * 'end' => $end
             * ));
             */
        } else {

            $count = 0;
        }

        $counts = $countNext;

        $data       = '';
        $view->assign('limit', $limit);
        $view->assign('events', $result);
        $view->assign('count', $count);
        $view->assign('counts', $counts);
        $output_tpl = $view->fetch('ajax/show_all_events.tpl');
        $data .= new Zikula_Response_Ajax_Plain($output_tpl);
        // $data["data"] = $output;
        AjaxUtil::output($data);
    }

    public function showEvents2($args)
    {

        // echo "<pre>"; print_r($_REQUEST); echo "</pre>";
        $setParams   = array();
        $level       = FormUtil::getPassedValue("level");
        $shop_id     = FormUtil::getPassedValue("shop_id");
        $country_id  = FormUtil::getPassedValue("country_id");
        $region_id   = FormUtil::getPassedValue("region_id");
        $city_id     = FormUtil::getPassedValue("city_id");
        $area_id     = FormUtil::getPassedValue("area_id");
        $branch_id   = FormUtil::getPassedValue("branch_id");
        $category_id = FormUtil::getPassedValue("category_id");
        $search      = FormUtil::getPassedValue("hsearch");
        $search      = ($search == $this->__('search for...') || $search == $this->__('search'))
                ? '' : $search;
        // $eventlimit = FormUtil::getPassedValue("eventlimit");
        $startlimit  = FormUtil::getPassedValue("startlimit");
        // $startlimit = 0;
        // echo $startlimit;
        $eventlimit  = FormUtil::getPassedValue("eventlimit");
        if (isset($startlimit) && !empty($startlimit)) {
            $pageLimit = $startlimit;
        } else {
            $pageLimit = '0';
        }
        $loadCount = $pageLimit + $eventlimit;
        // echo $loadCount;
        $reset     = FormUtil::getPassedValue("reset");
        // echo $reset; exit;
        if (!empty($eventlimit)) {
            $limit      = $eventlimit;
            $limitquery = "LIMIT 0 , $eventlimit";
        } else {
            $limit      = "2";
            $limitquery = "LIMIT 0 , $limit";
        }

        // echo $limitquery;

        $searchquery = '';
        if (($shop_id > 0 || !empty($shop_id)) || ($region_id > 0 || !empty($region_id))
            || ($city_id > 0 || !empty($city_id)) || ($country_id > 0 || !empty($country_id))
            || ($area_id > 0 || !empty($area_id)) || ($search > 0 || !empty($search))
            || ($category_id > 0 || !empty($category_id)) || ($branch_id > 0 || !empty($branch_id)
            || !empty($eventdate))) {

            // $eventdateqry = " AND shop_event_startdate>=CURDATE()";
            $eventdateqry = '';
            $output       = '';
            $items        = array(
                'id' => $shop_id
            );
            // $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
            $where        = '';
            $append       = '';

            if (!empty($country_id)) { // COUNTRY
                $append .= " AND a.country_id=$country_id";
            }

            if (!empty($region_id)) { // REGION
                $append .= " AND a.region_id=$region_id";
            }

            if (!empty($city_id)) { // CITY
                $append .= " AND a.city_id=$city_id";
            }

            if (!empty($area_id)) { // AREA
                $append .= " AND a.area_id=$area_id";
            }

            if (!empty($shop_id)) { // SHOP
                $append .= " AND a.shop_id=$shop_id";
            }
            if (!empty($category_id)) {
                // $append .= " AND a.cat_id=$category_id";
                $append .= " AND a.shop_id IN(SELECT shop_id FROM zselex_shop_to_category WHERE category_id=$category_id) ";
            }

            if (!empty($branch_id)) {
                $append .= " AND a.branch_id=$branch_id";
            }

            if (!empty($search)) {
                // $append .= " AND (a.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE '%" . DataUtil::formatForStore($search) . "%') OR a.shop_id IN (SELECT shop_id FROM zselex_shop WHERE shop_name LIKE '%" . DataUtil::formatForStore($search) . "%'))";
                $append .= " AND (a.shop_name LIKE :search OR MATCH (a.shop_name) AGAINST (:search2) OR a.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE :search OR MATCH (keyword) AGAINST (:search2)))";
                $setParams += array(
                    'search' => '%'.DataUtil::formatForStore($search).'%',
                    'search2' => DataUtil::formatForStore($search)
                );
            }

            $result = $this->entityManager->getRepository('ZSELEX_Entity_Event')->getUpcomingEventShops(array(
                'append' => $append,
                'setParams' => $setParams,
                'show_all' => true,
                'upcommingEvents' => true
            ));

            $count = count($result);
        } else {

            $count = 0;
        }
        $shopsql = '';
        if ($count > 0) {

            foreach ($result as $shopid) {
                $shop_idarray [] = $shopid ['shop_id'];
            }

            $shop_ids   = implode(",", $shop_idarray);
            // foreach ($result as $shop) {
            $shopsql    = " AND a.shop_id IN($shop_ids)";
            $shopquery1 = " AND a.shop IN($shop_ids)";

            if ($reset != 'reset') {
                $shopquery = $shopsql;
            } else {
                $shopquery = "";
            }

            // echo $shopquery;

            /*
             * $minmax = "SELECT MIN( shop_event_startdate ) as mindate , MAX( shop_event_enddate ) as maxdate
             * FROM zselex_shop_events a
             * WHERE a.shop_event_id IS NOT NULL AND UNIX_TIMESTAMP(a.shop_event_startdate) != 0 AND UNIX_TIMESTAMP(a.shop_event_enddate) != 0 AND a.status='1' " . " " . $shopquery . " " . $limitquery;
             * //echo $minmax;
             * $q = DBUtil::executeSQL($minmax);
             * $e = $q->fetch();
             */
            $e = $this->entityManager->getRepository('ZSELEX_Entity_Event')->getEventDates(array(
                'shopquery' => $shopquery1
            ));

            $mindate   = $e ['mindate'];
            $maxdate   = $e ['maxdate'];
            $mxdates   = array(
                "0" => $maxdate
            );
            $datearray = array();
            // echo "mindate : " . $mindate;
            // echo "maxdate : " . $maxdate;

            $datearray = $this->createDateRangeArray($mindate, $maxdate);
            $datearray = array_merge($datearray, $mxdates);

            $dateCount = count($datearray);

            $todayDate = date("Y-m-d");
            $arrays    = array();

            $i = 0;
            $j = 0;
            foreach ($datearray as $key => $d) {
                if ($d < $todayDate) continue;
                // echo $d . '<br>';
                if ($i == $limit) {
                    // break;
                }

                /*
                 * $sql1 = "SELECT a.shop_event_id , a.shop_id , a.shop_event_name , a.shop_event_shortdescription , a.shop_event_description , a.shop_event_startdate , a.shop_event_starthour , a.shop_event_startminute , a.shop_event_enddate , a.shop_event_endhour , a.shop_event_endminute,a.price,a.email,a.phone,a.shop_event_venue,
                 * b.aff_id , c.aff_image
                 * FROM zselex_shop_events a
                 * LEFT JOIN zselex_shop b ON b.shop_id=a.shop_id
                 * LEFT JOIN zselex_shop_affiliation c ON c.aff_id=b.aff_id
                 * WHERE '" . $d . "' BETWEEN a.shop_event_startdate AND a.shop_event_enddate AND (a.activation_date<=CURDATE() OR UNIX_TIMESTAMP(a.activation_date) = 0) " . " " . $shopquery;
                 * //echo $sql1;
                 *
                 * $query1 = DBUtil::executeSQL($sql1);
                 * $events1 = $query1->fetchAll();
                 */
                $events1 = $this->entityManager->getRepository('ZSELEX_Entity_Event')->getEventBetweenDates(array(
                    'd' => $d,
                    'shopsql' => $shopquery1
                ));
                foreach ($events1 as $get => $owner) {
                    $ownerName                = ModUtil::apiFunc('ZSELEX',
                            'admin', 'getOwner',
                            $args                     = array(
                            'shop_id' => $owner ['shop_id']
                    ));
                    $events1 [$get] ['uname'] = $ownerName;
                }
                // echo $events1['shop_event_name'] . '<br>';
                $dates                 = strtotime(date("Y-m-d", strtotime($d))." +$j day");
                $headlinedates         = date('l dS \o\f F Y', $dates);
                // echo $headlinedates . '<br>';
                $arrays ['dates'] [$d] = $events1;

                // echo "<pre>"; print_r($events1); echo "</pre>";
                // $datearray[$key]['eventsname'] = 'hiii';
                $i ++;
                $j ++;
            }

            $l          = 0;
            $eventcount = count($arrays ['dates']);
            // echo $eventcount;
            if ($eventcount > 0) {
                $todayDate     = date("Y-m-d");
                $current_theme = System::getVar('Default_Theme');
                // exit;
                if ($current_theme == 'CityPilot') {
                    // echo "hellooo";
                    $output .= '<div class="DateBlock">';
                    $k = 0;
                    $s = 0;
                    foreach ($arrays ['dates'] as $key => $d1) {
                        // echo "hii";
                        if ($k == $limit) {
                            // break;
                        }
                        $eventsdate  = $key;
                        $dateexplode = explode('-', $eventsdate);
                        $dayname     = date_format(date_create($eventsdate), 'l');
                        $dayname     = $this->__($dayname);

                        // echo $startlimit . '<br>';
                        foreach ($arrays ['dates'] [$key] as $key2 => $d2) {
                            // echo $s . '<br>';
                            // echo $l . '<br>';
                            if ($s >= $startlimit) {
                                $newArray [] = $d2;
                                if ($l == $limit) {
                                    break;
                                }

                                if ($d2 [price] > 0) {
                                    $curr_args = array(
                                        'amount' => $d2 [price],
                                        'currency_symbol' => '',
                                        'decimal_point' => ',',
                                        'thousands_sep' => '.',
                                        'precision' => '2'
                                    );
                                    $price     = ModUtil::apiFunc('ZSELEX',
                                            'user', 'number2currency',
                                            $curr_args);
                                } else {
                                    $price = $this->__("FREE");
                                }

                                if ($d2 ['aff_id'] > 0) {
                                    $aff_image      = pnGetBaseURL()."modules/ZSELEX/images/affiliates/".$d2 ['aff_image'];
                                    $aff_image_link = "<img src='".$aff_image."' style='width: 50px;'>";
                                    $aff_image_div  = "<div style='width: 50px; height: 50px; position: relative;z-index:999;display:inline-block; float:right; margin-top:-45px; margin-right: 30px;'>$aff_image_link</div>";
                                }
                                $evntUrl = pnGetBaseURL().ModUtil::url('ZSELEX',
                                        'user', 'viewevent',
                                        array(
                                        'shop_id' => $d2 [shop_id],
                                        'eventId' => $d2 [shop_event_id]
                                ));
                                // $evntUrl = "www.gmail.com";
                                $output .= '<a href="'.$evntUrl.'" class="HoverEffet">
                                            <div class="DateBorder">
                                                <div class="Date">
                                                <span class="DateSpan">'.$dateexplode [2].'/'.$dateexplode [1].'</span><br /><span  class="YearSpan">'.$dateexplode [0].'</span><br /><span class="WeekDay">'.$dayname.'</span>
                                                </div>

                                                <div class="DateHead">
                                                    <h5>'.stripslashes(wordwrap($d2 [shop_event_name],
                                            19, "<br>\n", TRUE)).'</h5>
                                                        '.$aff_image_div.'
                                                    <h6>'.stripslashes($d2 [shop_event_shortdescription]).'</h6>
                                                 </div>
                                                <div class="DatePrice">
                                                <p>'.$this->__('Price').' : '.$price.'</p>
                                                    '.$this->__('Time').' : '.$d2 [shop_event_startdate].', '.$d2 [shop_event_starthour].' - '.($d2 [shop_event_enddate]
                                    != $d2 [shop_event_startdate] ? $d2 [shop_event_enddate].', '
                                            : '').$d2 [shop_event_endhour].'<br>
                                                    '.$this->__('Location').' : '.stripslashes($d2 [shop_event_venue]).'
                                                </div>
                                                  <div class="Sect3">
                                                   '.$this->__('Contact').' : '.$d2 [phone].'<br>
                                                   '.$this->__('Organizer').' : '.$d2 [uname].'<br>
                                                      <span class="Sect3Link">
                                                      '.$this->__('See More').'...
                                                      </span>
                                                  </div>
                                            </div>
                                      </a>';
                                // echo $l;
                                $l ++;
                            }
                            $s ++;
                        }

                        $k ++;
                    }
                    $newArrayCount = sizeof($newArray);
                    $output .= "</div>";
                } else {
                    // echo "hellooo";
                    foreach ($arrays ['dates'] as $key => $d1) {
                        if ($k == $limit) {
                            break;
                        }

                        $dayname = date_format(date_create($key), 'l');
                        $output .= "<div>
                               <b>$key $dayname</b> </br>";
                        foreach ($arrays ['dates'] [$key] as $key2 => $d2) {
                            $output .= "&nbsp;&nbsp;<a href='".ModUtil::url('ZSELEX',
                                    'user', 'viewevent',
                                    array(
                                    'shop_id' => $d2 ['shop_id'],
                                    'eventId' => $d2 ['shop_event_id']
                                ))."'>$d2[shop_event_name]</a><br>
                                 <div>";
                        }
                        $output .= "</br>";
                        $k ++;
                    }
                }
                // exit;
                // echo $totalCount;
                // $loadCount = 3;
                // echo "eventcount : " . $eventcount;
                // if ($eventcount >= $limit) {
                if ($newArrayCount > $limit) {
                    // $output .= '<div style="cursor:pointer" align=right onclick=getMoreEvents()> <font color="blue">more events... </font> </div>';
                    // $output .= "<div class='allevent' style='cursor:pointer' onclick=check(document.getElementById('hcountry').value,document.getElementById('hregion').value,document.getElementById('hcity').value,document.getElementById('harea').value,document.getElementById('hshop').value,document.getElementById('hcategory').value,document.getElementById('hbranch').value,document.getElementById('hsearch').value)> <font color='blue'>" . $this->__('All Events...') . " </font> </div>";
                    // $output .= "<div style='cursor:pointer'><a class='infoclass' id='allevents' href='" . ModUtil::url('ZSELEX', 'info', 'showEvents') . "'>All Events...</a></div>";
                    // $output .= "<br><a href style='cursor:pointer' onclick=check(document.getElementById('hcountry').value,document.getElementById('hregion').value,document.getElementById('hcity').value,document.getElementById('harea').value,document.getElementById('hshop').value,document.getElementById('hcategory').value,document.getElementById('hbranch').value,document.getElementById('hsearch').value)> " . $this->__('All Events...') . "</a>";
                    $output .= '<div class="load_more_link" align="right">';
                    $output .= '<input type="button" class="eventmorebutton" value="'.$this->__('Load More Events').'"
       onclick=showEvents("'.$loadCount.'")>';
                    $output .= '<span class="flash"></span></div>';
                }
            } else {
                $output .= '<dt> &nbsp;&nbsp;&nbsp;&nbsp;  '.$this->__('No Events Found').'  </dt>';
            }
            // }
        } else {

            $output .= '<dt> &nbsp;&nbsp;&nbsp;&nbsp;  '.$this->__('No Events Found').'  </dt>';
        }

        $output .= '</dl>';

        $data ["data"] = $output;
        AjaxUtil::output($data);
    }

    public function showEvents1($args)
    {

        // echo "<pre>"; print_r($_REQUEST); echo "</pre>";
        $setParams   = array();
        $level       = FormUtil::getPassedValue("level");
        $shop_id     = FormUtil::getPassedValue("shop_id");
        $country_id  = FormUtil::getPassedValue("country_id");
        $region_id   = FormUtil::getPassedValue("region_id");
        $city_id     = FormUtil::getPassedValue("city_id");
        $area_id     = FormUtil::getPassedValue("area_id");
        $branch_id   = FormUtil::getPassedValue("branch_id");
        $category_id = FormUtil::getPassedValue("category_id");
        $search      = FormUtil::getPassedValue("hsearch");
        $search      = ($search == $this->__('search for...') || $search == $this->__('search'))
                ? '' : $search;
        // $eventlimit = FormUtil::getPassedValue("eventlimit");
        $startlimit  = FormUtil::getPassedValue("startlimit");
        // $startlimit = 0;
        // echo $startlimit;
        $eventlimit  = FormUtil::getPassedValue("eventlimit");
        if (isset($startlimit) && !empty($startlimit)) {
            $pageLimit = $startlimit;
        } else {
            $pageLimit = '0';
        }
        $loadCount = $pageLimit + $eventlimit;
        // echo $loadCount;
        $reset     = FormUtil::getPassedValue("reset");
        // echo $reset; exit;
        if (!empty($eventlimit)) {
            $limit      = $eventlimit;
            $limitquery = "LIMIT 0 , $eventlimit";
        } else {
            $limit      = "2";
            $limitquery = "LIMIT 0 , $limit";
        }

        // echo $limitquery;

        $searchquery = '';
        if (($shop_id > 0 || !empty($shop_id)) || ($region_id > 0 || !empty($region_id))
            || ($city_id > 0 || !empty($city_id)) || ($country_id > 0 || !empty($country_id))
            || ($area_id > 0 || !empty($area_id)) || ($search > 0 || !empty($search))
            || ($category_id > 0 || !empty($category_id)) || ($branch_id > 0 || !empty($branch_id)
            || !empty($eventdate))) {

            // $eventdateqry = " AND shop_event_startdate>=CURDATE()";
            $eventdateqry = '';
            $output       = '';
            $items        = array(
                'id' => $shop_id
            );
            // $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
            $where        = '';
            $append       = '';

            if (!empty($country_id)) { // COUNTRY
                $append .= " AND a.country_id=$country_id";
            }

            if (!empty($region_id)) { // REGION
                $append .= " AND a.region_id=$region_id";
            }

            if (!empty($city_id)) { // CITY
                $append .= " AND a.city_id=$city_id";
            }

            if (!empty($area_id)) { // AREA
                $append .= " AND a.area_id=$area_id";
            }

            if (!empty($shop_id)) { // SHOP
                $append .= " AND a.shop_id=$shop_id";
            }
            if (!empty($category_id)) {
                // $append .= " AND a.cat_id=$category_id";
                $append .= " AND a.shop_id IN(SELECT shop_id FROM zselex_shop_to_category WHERE category_id=$category_id) ";
            }

            if (!empty($branch_id)) {
                $append .= " AND a.branch_id=$branch_id";
            }

            if (!empty($search)) {
                // $append .= " AND (a.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE '%" . DataUtil::formatForStore($search) . "%') OR a.shop_id IN (SELECT shop_id FROM zselex_shop WHERE shop_name LIKE '%" . DataUtil::formatForStore($search) . "%'))";
                $append .= " AND (a.shop_name LIKE :search OR MATCH (a.shop_name) AGAINST (:search2) OR a.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE :search OR MATCH (keyword) AGAINST (:search2)))";
                $setParams += array(
                    'search' => '%'.DataUtil::formatForStore($search).'%',
                    'search2' => DataUtil::formatForStore($search)
                );
            }

            /*
             * $sql = "SELECT a.shop_id
             * FROM zselex_shop a
             * WHERE a.shop_id IS NOT NULL
             * AND a.status='1' $append";
             * //echo $sql;
             * $query = DBUtil::executeSQL($sql);
             * $result = $query->fetchAll();
             */

            $result = $this->entityManager->getRepository('ZSELEX_Entity_Event')->getUpcomingEventShops(array(
                'append' => $append,
                'setParams' => $setParams,
                'show_all' => true,
                'upcommingEvents' => true
            ));

            $count = count($result);
        } else {

            $count = 0;
        }
        $shopsql = '';
        if ($count > 0) {

            foreach ($result as $shopid) {
                $shop_idarray [] = $shopid ['shop_id'];
            }

            $shop_ids   = implode(",", $shop_idarray);
            // foreach ($result as $shop) {
            $shopsql    = " AND a.shop_id IN($shop_ids)";
            $shopquery1 = " AND a.shop IN($shop_ids)";

            if ($reset != 'reset') {
                $shopquery = $shopsql;
            } else {
                $shopquery = "";
            }

            // echo $shopquery;

            /*
             * $minmax = "SELECT MIN( shop_event_startdate ) as mindate , MAX( shop_event_enddate ) as maxdate
             * FROM zselex_shop_events a
             * WHERE a.shop_event_id IS NOT NULL AND UNIX_TIMESTAMP(a.shop_event_startdate) != 0 AND UNIX_TIMESTAMP(a.shop_event_enddate) != 0 AND a.status='1' " . " " . $shopquery . " " . $limitquery;
             * //echo $minmax;
             * $q = DBUtil::executeSQL($minmax);
             * $e = $q->fetch();
             */
            $e = $this->entityManager->getRepository('ZSELEX_Entity_Event')->getEventDates(array(
                'shopquery' => $shopquery1
            ));

            $mindate   = $e ['mindate'];
            $maxdate   = $e ['maxdate'];
            $mxdates   = array(
                "0" => $maxdate
            );
            $datearray = array();
            // echo "mindate : " . $mindate;
            // echo "maxdate : " . $maxdate;

            $datearray = $this->createDateRangeArray($mindate, $maxdate);
            $datearray = array_merge($datearray, $mxdates);

            $dateCount = count($datearray);

            $todayDate = date("Y-m-d");
            $arrays    = array();

            $i = 0;
            $j = 0;
            foreach ($datearray as $key => $d) {
                if ($d < $todayDate) continue;
                // echo $d . '<br>';
                if ($i == $limit) {
                    // break;
                }

                /*
                 * $sql1 = "SELECT a.shop_event_id , a.shop_id , a.shop_event_name , a.shop_event_shortdescription , a.shop_event_description , a.shop_event_startdate , a.shop_event_starthour , a.shop_event_startminute , a.shop_event_enddate , a.shop_event_endhour , a.shop_event_endminute,a.price,a.email,a.phone,a.shop_event_venue,
                 * b.aff_id , c.aff_image
                 * FROM zselex_shop_events a
                 * LEFT JOIN zselex_shop b ON b.shop_id=a.shop_id
                 * LEFT JOIN zselex_shop_affiliation c ON c.aff_id=b.aff_id
                 * WHERE '" . $d . "' BETWEEN a.shop_event_startdate AND a.shop_event_enddate AND (a.activation_date<=CURDATE() OR UNIX_TIMESTAMP(a.activation_date) = 0) " . " " . $shopquery;
                 * //echo $sql1;
                 *
                 * $query1 = DBUtil::executeSQL($sql1);
                 * $events1 = $query1->fetchAll();
                 */
                $events1 = $this->entityManager->getRepository('ZSELEX_Entity_Event')->getEventBetweenDates(array(
                    'd' => $d,
                    'shopsql' => $shopquery1
                ));
                foreach ($events1 as $get => $owner) {
                    $ownerName                = ModUtil::apiFunc('ZSELEX',
                            'admin', 'getOwner',
                            $args                     = array(
                            'shop_id' => $owner ['shop_id']
                    ));
                    $events1 [$get] ['uname'] = $ownerName;
                }
                // echo $events1['shop_event_name'] . '<br>';
                $dates                 = strtotime(date("Y-m-d", strtotime($d))." +$j day");
                $headlinedates         = date('l dS \o\f F Y', $dates);
                // echo $headlinedates . '<br>';
                $arrays ['dates'] [$d] = $events1;

                // echo "<pre>"; print_r($events1); echo "</pre>";
                // $datearray[$key]['eventsname'] = 'hiii';
                $i ++;
                $j ++;
            }

            $l          = 0;
            $eventcount = count($arrays ['dates']);
            // echo $eventcount;
            if ($eventcount > 0) {
                $todayDate     = date("Y-m-d");
                $current_theme = System::getVar('Default_Theme');
                // exit;
                if ($current_theme == 'CityPilot') {
                    // echo "hellooo";
                    $output .= '<div class="DateBlock">';
                    $k = 0;
                    $s = 0;
                    foreach ($arrays ['dates'] as $key => $d1) {
                        // echo "hii";
                        if ($k == $limit) {
                            // break;
                        }
                        $eventsdate  = $key;
                        $dateexplode = explode('-', $eventsdate);
                        $dayname     = date_format(date_create($eventsdate), 'l');
                        $dayname     = $this->__($dayname);

                        // echo $startlimit . '<br>';
                        foreach ($arrays ['dates'] [$key] as $key2 => $d2) {
                            // echo $s . '<br>';
                            // echo $l . '<br>';
                            if ($s >= $startlimit) {
                                $newArray [] = $d2;
                                if ($l == $limit) {
                                    break;
                                }

                                if ($d2 [price] > 0) {
                                    $curr_args = array(
                                        'amount' => $d2 [price],
                                        'currency_symbol' => '',
                                        'decimal_point' => ',',
                                        'thousands_sep' => '.',
                                        'precision' => '2'
                                    );
                                    $price     = ModUtil::apiFunc('ZSELEX',
                                            'user', 'number2currency',
                                            $curr_args);
                                } else {
                                    $price = $this->__("FREE");
                                }

                                if ($d2 ['aff_id'] > 0) {
                                    $aff_image      = pnGetBaseURL()."modules/ZSELEX/images/affiliates/".$d2 ['aff_image'];
                                    $aff_image_link = "<img src='".$aff_image."' style='width: 50px;'>";
                                    $aff_image_div  = "<div style='width: 50px; height: 50px; position: relative;z-index:999;display:inline-block; float:right; margin-top:-45px; margin-right: 30px;'>$aff_image_link</div>";
                                }
                                $evntUrl = pnGetBaseURL().ModUtil::url('ZSELEX',
                                        'user', 'viewevent',
                                        array(
                                        'shop_id' => $d2 [shop_id],
                                        'eventId' => $d2 [shop_event_id]
                                ));
                                // $evntUrl = "www.gmail.com";
                                $output .= '<a href="'.$evntUrl.'" class="HoverEffet">
                                            <div class="DateBorder">
                                                <div class="Date">
                                                <span class="DateSpan">'.$dateexplode [2].'/'.$dateexplode [1].'</span><br /><span  class="YearSpan">'.$dateexplode [0].'</span><br /><span class="WeekDay">'.$dayname.'</span>
                                                </div>

                                                <div class="DateHead">
                                                    <h5>'.stripslashes(wordwrap($d2 [shop_event_name],
                                            19, "<br>\n", TRUE)).'</h5>
                                                        '.$aff_image_div.'
                                                    <h6>'.stripslashes($d2 [shop_event_shortdescription]).'</h6>
                                                 </div>
                                                <div class="DatePrice">
                                                <p>'.$this->__('Price').' : '.$price.'</p>
                                                    '.$this->__('Time').' : '.$d2 [shop_event_startdate].', '.$d2 [shop_event_starthour].' - '.($d2 [shop_event_enddate]
                                    != $d2 [shop_event_startdate] ? $d2 [shop_event_enddate].', '
                                            : '').$d2 [shop_event_endhour].'<br>
                                                    '.$this->__('Location').' : '.stripslashes($d2 [shop_event_venue]).'
                                                </div>
                                                  <div class="Sect3">
                                                   '.$this->__('Contact').' : '.$d2 [phone].'<br>
                                                   '.$this->__('Organizer').' : '.$d2 [uname].'<br>
                                                      <span class="Sect3Link">
                                                      '.$this->__('See More').'...
                                                      </span>
                                                  </div>
                                            </div>
                                      </a>';
                                // echo $l;
                                $l ++;
                            }
                            $s ++;
                        }

                        $k ++;
                    }
                    $newArrayCount = sizeof($newArray);
                    $output .= "</div>";
                } else {
                    // echo "hellooo";
                    foreach ($arrays ['dates'] as $key => $d1) {
                        if ($k == $limit) {
                            break;
                        }

                        $dayname = date_format(date_create($key), 'l');
                        $output .= "<div>
                               <b>$key $dayname</b> </br>";
                        foreach ($arrays ['dates'] [$key] as $key2 => $d2) {
                            $output .= "&nbsp;&nbsp;<a href='".ModUtil::url('ZSELEX',
                                    'user', 'viewevent',
                                    array(
                                    'shop_id' => $d2 ['shop_id'],
                                    'eventId' => $d2 ['shop_event_id']
                                ))."'>$d2[shop_event_name]</a><br>
                                 <div>";
                        }
                        $output .= "</br>";
                        $k ++;
                    }
                }
                // exit;
                // echo $totalCount;
                // $loadCount = 3;
                // echo "eventcount : " . $eventcount;
                // if ($eventcount >= $limit) {
                if ($newArrayCount > $limit) {
                    // $output .= '<div style="cursor:pointer" align=right onclick=getMoreEvents()> <font color="blue">more events... </font> </div>';
                    // $output .= "<div class='allevent' style='cursor:pointer' onclick=check(document.getElementById('hcountry').value,document.getElementById('hregion').value,document.getElementById('hcity').value,document.getElementById('harea').value,document.getElementById('hshop').value,document.getElementById('hcategory').value,document.getElementById('hbranch').value,document.getElementById('hsearch').value)> <font color='blue'>" . $this->__('All Events...') . " </font> </div>";
                    // $output .= "<div style='cursor:pointer'><a class='infoclass' id='allevents' href='" . ModUtil::url('ZSELEX', 'info', 'showEvents') . "'>All Events...</a></div>";
                    // $output .= "<br><a href style='cursor:pointer' onclick=check(document.getElementById('hcountry').value,document.getElementById('hregion').value,document.getElementById('hcity').value,document.getElementById('harea').value,document.getElementById('hshop').value,document.getElementById('hcategory').value,document.getElementById('hbranch').value,document.getElementById('hsearch').value)> " . $this->__('All Events...') . "</a>";
                    $output .= '<div class="load_more_link" align="right">';
                    $output .= '<input type="button" class="eventmorebutton" value="'.$this->__('Load More Events').'"
       onclick=showEvents("'.$loadCount.'")>';
                    $output .= '<span class="flash"></span></div>';
                }
            } else {
                $output .= '<dt> &nbsp;&nbsp;&nbsp;&nbsp;  '.$this->__('No Events Found').'  </dt>';
            }
            // }
        } else {

            $output .= '<dt> &nbsp;&nbsp;&nbsp;&nbsp;  '.$this->__('No Events Found').'  </dt>';
        }

        $output .= '</dl>';

        // ZSELEX_Util::ajaxOutput($output);
        $data ["data"] = $output;
        AjaxUtil::output($data);
    }

    public function rate($args)
    {
        $output    = '';
        $shop_id   = FormUtil::getPassedValue('shop_id', null, 'POST'); // ID OF THE CONTENT
        $rating    = FormUtil::getPassedValue('rating', null, 'POST'); // RATING THAT THE USER HAS SUBMITTED
        // $ip = $_SERVER["REMOTE_ADDR"]; //IP ADDRESS OF THE USER
        $user_id   = UserUtil::getVar('uid');
        $date      = date("l, F j, Y")." at ".date("h:i:s A"); // DATE & TIME
        $timestamp = time(); // TIMESTAMP

        /*
         * $ratingCount = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $count_args = array(
         * 'table' => 'zselex_shop_ratings',
         * 'where' => "shop_id=$shop_id AND user_id=$user_id"));
         */

        $countArgs = array(
            'entity' => 'ZSELEX_Entity_Rating',
            'field' => 'rating_id',
            'where' => array(
                'a.shop' => $shop_id,
                'a.user_id' => $user_id
            )
        );

        $ratingCount = $this->entityManager->getRepository('ZSELEX_Entity_Rating')->getCount($countArgs);

        // echo $ratingCount; exit;

        /*
         * $userRating = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow', $user_rate_args = array(
         * 'table' => 'zselex_shop_ratings',
         * 'where' => array(
         * "shop_id=$shop_id", "user_id=$user_id"
         * )
         * ));
         */

        $userRating = $this->entityManager->getRepository('ZSELEX_Entity_Rating')->getUserRating(array(
            'shop_id' => $shop_id,
            'user_id' => $user_id
        ));

        if ($ratingCount) {
            // CHECKS TO SEE IF THE RATING IS THE SAME AS BEFORE
            if ($rating != $userRating ["rating"]) {
                // IF SO, THE RATING WILL BE UPDATED
                // mysql_query("UPDATE $ratings SET rating='$rating', dateposted='$date', timestamp='$timestamp' WHERE id='$id' AND ip='$ip'");
                $updateobj = array(
                    'rating' => $rating,
                    'dateposted' => $date,
                    'timestamp' => $timestamp
                );

                /*
                 * $upd_args = array(
                 * 'table' => 'zselex_shop_ratings',
                 * 'items' => $updateobj,
                 * 'where' => array(
                 * 'shop_id' => $shop_id,
                 * 'user_id' => $user_id,
                 * )
                 * );
                 */
                // echo "<pre>"; print_r($clear_args); echo "</pre>"; exit;
                /*
                 * $updateRating = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElementWhere', $upd_args);
                 */
                $upd_args     = array(
                    'entity' => 'ZSELEX_Entity_Rating',
                    'fields' => $updateobj,
                    'where' => array(
                        'a.shop' => $shop_id,
                        'a.user_id' => $user_id
                    )
                    )
                // 'where' => "a.shop=:shop_id AND a.user_id=:user_id"
                ;
                $updateRating = $this->entityManager->getRepository('ZSELEX_Entity_Rating')->updateEntity($upd_args);
            }
        } else {
            // ADDS THE NEW RATING IF USER HAS NOT ALREADY RATED
            // mysql_query("INSERT INTO $ratings VALUES('', '$id', '$rating', '$ip', '$date', '$timestamp')");
            $rate_item = array(
                'shop_id' => $shop_id,
                'rating' => $rating,
                'user_id' => $user_id,
                'dateposted' => $date,
                'timestamp' => $timestamp
            );
            /*
             * $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', array(
             * 'table' => 'zselex_shop_ratings',
             * 'element' => $rate_item,
             * 'Id' => 'rating_id'
             * ));
             */

            $result = $this->entityManager->getRepository('ZSELEX_Entity_Rating')->createRating($rate_item);
        }
        // $output = ZSELEX_Controller_User::showRating($args = array('shop_id' => $shop_id, 'user_id' => $user_id));
        $args   = array(
            'shop_id' => $shop_id,
            'user_id' => $user_id
        );
        $output = $this->showRatings($args);
        ZSELEX_Util::ajaxOutput($output);
    }

    public function showRatings($args)
    {
        $view    = Zikula_View::getInstance($this->name);
        $shop_id = $args ['shop_id'];
        $user_id = $args ['user_id'];

        if (empty($shop_id) || !(int) $shop_id) {
            return;
        }
        $view->assign('shop_id', $shop_id);
        // echo "<input type='hidden' id='shop_id' value=$shop_id>";
        $x = '';

        $ratingCount = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount',
                $count_args  = array(
                'table' => 'zselex_shop_ratings',
                'where' => "shop_id=$shop_id"
        ));
        if ($ratingCount == 1) {
            $v = 'vote';
        } else {
            $v = 'votes';
        }

        $ratings   = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray',
                $rate_args = array(
                'table' => 'zselex_shop_ratings',
                'where' => array(
                    "shop_id=$shop_id"
                )
        ));
        $view->assign('ratings', $ratings);
        $view->assign('v', $v);

        foreach ($ratings as $key => $val) {
            $rr = $val ["rating"]; // EACH RATING FOR THE CONTENT
            $x += $rr; // ADDS THEM ALL UP
        }
        // echo "X :" . $x;
        // IF THERE ARE RATINGS...
        if ($ratingCount) {
            $rating = $x / $ratingCount; // THE AVERAGE RATING (UNROUNDED)
        } else {
            $rating = 0; // SET TO PREVENT THE ERROR OF DIVISION BY 0, WHICH WOULD BE THE NUMBER OF RATINGS HERE
        }

        $dec_rating = round($rating, 1); // ROUNDED RATING TO THE NEAREST TENTH
        // $dec_rating = round($rating);
        $stars      = '';
        $y          = '';
        $view->assign('isLoggedIn', UserUtil::isLoggedIn());
        $view->assign('rating', $rating);
        $view->assign('v', $v);
        $view->assign('dec_rating', $dec_rating);
        $view->assign('ratingCount', $ratingCount);

        if (UserUtil::isLoggedIn()) {

            $userRating     = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow',
                    $user_rate_args = array(
                    'table' => 'zselex_shop_ratings',
                    'where' => array(
                        "shop_id=$shop_id",
                        "user_id=$user_id"
                    )
            ));
            if ($userRating ['rating']) {
                // $user_rated = '<div>You rated this a <b>' . $userRating[rating] . '</b></div>';
            }
            $view->assign('userRating', $userRating ['rating']);
        }

        $output_rating = $view->fetch('user/showRating.tpl');
        $data          = new Zikula_Response_Ajax_Plain($output_rating);
        return $data;
    }

    public function getNewShops1($args)
    {
        // exit;
        $shop_id        = FormUtil::getPassedValue("shop_id");
        $country_id     = FormUtil::getPassedValue("country_id");
        $region_id      = FormUtil::getPassedValue("region_id");
        $city_id        = FormUtil::getPassedValue("city_id");
        $area_id        = FormUtil::getPassedValue("area_id");
        $hsearch        = DataUtil::formatForStore(FormUtil::getPassedValue("hsearch"));
        // echo "searchVal1 : " . $hsearch; exit;
        $hsearch        = ($hsearch == $this->__('search for...') || $hsearch == $this->__('search'))
                ? '' : $hsearch;
        $search         = $hsearch;
        // $hsearch = ($hsearch == $this->__('search')) ? '' : $hsearch;
        // echo "searchVals : " . $hsearch; exit;
        $shopfrontorder = FormUtil::getPassedValue("shopfrontorder");
        $shopfrontlimit = FormUtil::getPassedValue("shopfrontlimit");
        if ($shopfrontlimit > 0) {
            $limit = $shopfrontlimit;
        } else {
            $limit = 20;
        }

        switch ($shopfrontorder) {
            case "new" :
                $orderby = " ORDER BY a.shop_id DESC ";
                break;
            case "rank" :
                $orderby = " ORDER BY sum(c.rating) DESC ";
                break;
            default :
                $orderby = " ORDER BY a.shop_id DESC ";
        }

        $category_id = FormUtil::getPassedValue("category_id");
        $branch_id   = FormUtil::getPassedValue("branch_id");

        $affiliate_image = $this->affiliate_image;
        // echo $affiliate_image; exit;

        $append = '';
        if (!empty($country_id)) { // COUNTRY
            $append .= " AND a.country_id=$country_id";
        }

        if (!empty($region_id)) { // REGION
            $append .= " AND a.region_id=$region_id";
        }

        if (!empty($city_id)) { // CITY
            $append .= " AND a.city_id=$city_id";
        }

        if (!empty($area_id)) { // AREA
            $append .= " AND a.area_id=$area_id";
        }

        if (!empty($shop_id)) { // SHOP
            $append .= " AND a.shop_id=$shop_id";
        }

        if (!empty($category_id)) {
            $append .= " AND a.cat_id=$category_id";
        }

        if (!empty($branch_id)) {
            $append .= " AND a.branch_id=$branch_id";
        }

        if (!empty($search)) {

            // $append .= " AND a.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE '%" . DataUtil::formatForStore($hsearch) . "%')";
            // $append .= " AND (a.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE '%" . DataUtil::formatForStore($hsearch) . "%') OR a.shop_id IN (SELECT shop_id FROM zselex_shop WHERE shop_name LIKE '%" . DataUtil::formatForStore($hsearch) . "%'))";
            $append .= " AND (a.shop_name LIKE '%".DataUtil::formatForStore($search)."%' OR MATCH (a.shop_name) AGAINST ('".DataUtil::formatForStore($search)."') OR a.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE '%".DataUtil::formatForStore($search)."%' OR MATCH (keyword) AGAINST ('".DataUtil::formatForStore($search)."')))";
        }

        $sql           = "SELECT a.shop_id shopid , a.shop_name , LEFT(a.description, 40) description  , a.default_img_frm ,a.aff_id, b.city_name ,
                round((sum(c.rating)/COUNT(c.rating)),1) as rating, COUNT(c.rating) as votes,
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
                'limit' => $limit
            )).")
                GROUP BY a.shop_id
                $orderby 
                LIMIT 0,$limit";
        // echo $sql; exit;
        $query         = DBUtil::executeSQL($sql);
        $result        = $query->fetchAll();
        shuffle($result);
        $result        = array_slice($result, 0, 3);
        // $count = sizeof($result);
        // return $result;
        // echo "<pre>"; print_r($result); echo "</pre>";
        $data          = '';
        $rating        = 0;
        $stars         = '';
        $rating_sec    = '';
        $city          = '';
        $see_ful_store = "";
        $view          = Zikula_View::getInstance($this->name);

        $count = sizeof($result);
        if ($count > 0) {
            // $data .= '<div class="image_list newshop">';
            $image = '';

            foreach ($result as $key => $item) {

                $no_image      = 0;
                $minishopExist = ModUtil::apiFunc('ZSELEX', 'admin',
                        'serviceExistBlock',
                        $args          = array(
                        'shop_id' => $item [shopid],
                        'type' => 'linktoshop'
                ));
                if ($minishopExist > 0) {
                    $see_ful_store = "<a href='".ModUtil::url('ZSELEX', 'user',
                            'shop',
                            array(
                            'shop_id' => $item [shopid]
                        ))."'</a>".$this->__('See full store here')."</a>";
                } else {
                    $see_ful_store = "";
                }

                if ($item [default_img_frm] == "fromgallery") {
                    $imagepath = "zselexdata/$item[uname]/minisitegallery/medium/$item[image_name]";

                    if (file_exists($imagepath) && $item [image_name] != '') {
                        // $image = "<a href='" . ModUtil::url('ZSELEX', 'user', 'site', array('shop_id' => $item[shopid])) . "'</a><img src='zselexdata/$item[uname]/minisitegallery/medium/$item[image_name]' height='164' width='299'></a>";
                        $image = "<img style='height:144px' src='zselexdata/$item[uname]/minisitegallery/medium/".str_replace(" ",
                                "%20", $item [image_name])."'>";
                        // $imagepath_final = pnGetBaseURL() . "zselexdata/$item[uname]/minisitegallery/medium/" . str_replace(" ", "%20", $item[image_name]);
                    } else {
                        // $image = "<a href='" . ModUtil::url('ZSELEX', 'user', 'site', array('shop_id' => $item[shopid])) . "'</a><img src='zselexdata/nopreview.jpg' width='290' height='160'/></a>";
                        $image           = "<img src='zselexdata/nopreview.jpg' >";
                        $no_image        = 1;
                        $imagepath_final = pnGetBaseURL()."zselexdata/nopreview.jpg";
                    }
                } elseif ($item [default_img_frm] == "fromshop") {
                    $imagepath = "zselexdata/$item[uname]/minisiteimages/medium/$item[name]";

                    if (file_exists($imagepath) && $item [name] != '') {
                        // $image = "<a href='" . ModUtil::url('ZSELEX', 'user', 'site', array('shop_id' => $item[shopid])) . "'</a><img src='zselexdata/$item[uname]/minisiteimages/medium/$item[name]' ></a>";
                        // $imagepath_final = pnGetBaseURL() . "zselexdata/$item[uname]/minisiteimages/medium/" . str_replace(" ", "%20", $item[name]);
                        $image = "<img style='height:144px' src='zselexdata/$item[uname]/minisiteimages/medium/".str_replace(" ",
                                "%20", $item [name])."'>";
                    } else {
                        // $image = "<a href='" . ModUtil::url('ZSELEX', 'user', 'site', array('shop_id' => $item[shopid])) . "'</a><img src='zselexdata/nopreview.jpg' width='290' height='160'/></a>";
                        $imagepath_final = pnGetBaseURL()."zselexdata/nopreview.jpg";
                        $image           = "<img src='zselexdata/nopreview.jpg' >";
                        $no_image        = 1;
                    }
                }
                $result [$key] ['image']         = $image;
                $result [$key] ['no_image']      = $no_image;
                $result [$key] ['image_final']   = $imagepath_final;
                $result [$key] ['see_ful_store'] = $see_ful_store;
                if (!empty($item [city_name])) {
                    $city = " , <span>$item[city_name]</span>";
                }

                $affiliate                         = ModUtil::apiFunc('ZSELEX',
                        'user', 'get',
                        $args                              = array(
                        'table' => 'zselex_shop_affiliation',
                        'where' => "aff_id=$item[aff_id]",
                        'fields' => array(
                            'aff_id',
                            'aff_image'
                        )
                ));
                $result [$key] ['affiliate_image'] = $affiliate ['aff_image'];
            }
            // echo "<pre>"; print_r($result); echo "</pre>";
            $view->assign('newshops', $result);
            $output_shops = $view->fetch('ajax/newshops.tpl');
            $data .= new Zikula_Response_Ajax_Plain($output_shops);

            // $data .= '</div>';
        } else {
            $data .= "<dt> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$this->__("No Shops Found")."</dt>";
        }
        // $output = $data;
        // ZSELEX_Util::ajaxOutput($data);
        $output ["count"] = $count;
        $output ["data"]  = $data;
        AjaxUtil::output($output);
    }

    public function getNewShops($args)
    {
        // exit;
        $view           = Zikula_View::getInstance($this->name);
        $shop_id        = FormUtil::getPassedValue("shop_id");
        $country_id     = FormUtil::getPassedValue("country_id");
        $region_id      = FormUtil::getPassedValue("region_id");
        $city_id        = FormUtil::getPassedValue("city_id");
        $area_id        = FormUtil::getPassedValue("area_id");
        $category_id    = FormUtil::getPassedValue("category_id");
        $branch_id      = FormUtil::getPassedValue("branch_id");
        $aff_id         = FormUtil::getPassedValue("aff_id");
        $hsearch        = DataUtil::formatForStore(FormUtil::getPassedValue("search"));
        // echo "searchVal1 : " . $hsearch; exit;
        $hsearch        = ($hsearch == $this->__('search for...') || $hsearch == $this->__('search'))
                ? '' : $hsearch;
        $search         = $hsearch;
        // $hsearch = ($hsearch == $this->__('search')) ? '' : $hsearch;
        // echo "searchVals : " . $hsearch; exit;
        $shopfrontorder = FormUtil::getPassedValue("shopfrontorder");
        $shopfrontlimit = FormUtil::getPassedValue("shopfrontlimit");

        $shop_args = array(
            'country_id' => $country_id,
            'region_id' => $region_id,
            'city_id' => $city_id,
            'area_id' => $area_id,
            'shop_id' => $shop_id,
            'category_id' => $category_id,
            'branch_id' => $branch_id,
            'aff_id' => $aff_id,
            'search' => $search,
            'eventlimit' => $eventlimit,
            'shopfrontorder' => $shopfrontorder,
            'shopfrontlimit' => $shopfrontlimit
        );
        // echo "<pre>"; print_r($shop_args); echo "</pre>";
        $count     = 0;
        $newshops  = ModUtil::apiFunc('ZSELEX', 'user', 'getNewShops',
                $shop_args);
        $count     = count($newshops);

        $view->assign('newshops', $newshops);
        $view->assign('count', $count);

        $output_shops = '';
        $output_shops = $view->fetch('ajax/newshops.tpl');
        $data         = '';
        $data .= new Zikula_Response_Ajax_Plain($output_shops);

        // return $data;

        $output ["count"] = $count;
        $output ["data"]  = $data;
        AjaxUtil::output($output);
    }

    function center_image($args)
    {
        $AH            = $args ['height'];
        $AW            = $args ['width'];
        $target_height = $args ['target_height'];
        $target_width  = $args ['target_height'];
        if ($AH > $target_height) {
            $top = ($AH - $target_height) / 2 * - 1;
        }
        if ($AW > $target_width) {
            $left = ($AW - $target_width) / 2 * - 1;
        }

        $output = array(
            'top' => $top,
            'left' => $left
        );

        return $output;
    }

    function checkFilename1($fileName, $size, $newName = '')
    {
        global $allowExt, $uploadPath, $maxFileSize;

        // ------------------max file size check from js
        $maxsize_regex = preg_match("/^(?'size'[\\d]+)(?'rang'[a-z]{0,1})$/i",
            $maxFileSize, $match);
        $maxSize       = 4 * 1024 * 1024; // default 4 M
        if ($maxsize_regex && is_numeric($match ['size'])) {
            switch (strtoupper($match ['rang'])) { // 1024 or 1000??
                case 'K' :
                    $maxSize = $match [1] * 1024;
                    break;
                case 'M' :
                    $maxSize = $match [1] * 1024 * 1024;
                    break;
                case 'G' :
                    $maxSize = $match [1] * 1024 * 1024 * 1024;
                    break;
                case 'T' :
                    $maxSize = $match [1] * 1024 * 1024 * 1024 * 1024;
                    break;
                default :
                    $maxSize = $match [1]; // default 4 M
            }
        }

        if (!empty($maxFileSize) && $size > $maxSize) {
            echo json_encode(array(
                'name' => $fileName,
                'size' => $size,
                'status' => 'error',
                'info' => 'File size not allowed.'
            ));
            return false;
        }
        // -----------------End max file size check
        // comment if not using windows web server
        $windowsReserved = array(
            'CON',
            'PRN',
            'AUX',
            'NUL',
            'COM1',
            'COM2',
            'COM3',
            'COM4',
            'COM5',
            'COM6',
            'COM7',
            'COM8',
            'COM9',
            'LPT1',
            'LPT2',
            'LPT3',
            'LPT4',
            'LPT5',
            'LPT6',
            'LPT7',
            'LPT8',
            'LPT9'
        );
        $badWinChars     = array_merge(array_map('chr', range(0, 31)),
            array(
            "<",
            ">",
            ":",
            '"',
            "/",
            "\\",
            "|",
            "?",
            "*"
        ));

        $fileName = str_replace($badWinChars, '', $fileName);
        $fileInfo = pathinfo($fileName);
        $fileExt  = $fileInfo ['extension'];
        $fileBase = $fileInfo ['filename'];

        // check if legal windows file name
        if (in_array($fileName, $windowsReserved)) {
            echo json_encode(array(
                'name' => $fileName,
                'size' => 0,
                'status' => 'error',
                'info' => 'File name not allowed. Windows reserverd.'
            ));
            return false;
        }

        // check if is allowed extension
        if (!in_array($fileExt, $allowExt) && count($allowExt)) {
            echo json_encode(array(
                'name' => $fileName,
                'size' => 0,
                'status' => 'error',
                'info' => "Extension [$fileExt] not allowed."
            ));
            return false;
        }

        $fullPath = $uploadPath.$fileName;
        $c        = 0;
        while (file_exists($fullPath)) {
            $c ++;
            $fileName = $fileBase."($c).".$fileExt;
            $fullPath = $uploadPath.$fileName;
        }
        return $fullPath;
    }

    public function deleteExtraBanner1($args)
    {
        error_reporting('E_ALL');
        $arra         = array();
        $shop_id      = $args ['shop_id'];
        $ownername    = $args ['ownername'];
        $servicecheck = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow',
                $args         = array(
                'table' => 'zselex_serviceshop',
                'where' => array(
                    "shop_id=$shop_id",
                    "type='minisitebanner'"
                )
        ));
        if ($servicecheck ['availed'] > $servicecheck ['quantity']) {
            $service_used_extra  = $servicecheck ['availed'] - $servicecheck ['quantity'];
            $original_used_extra = $servicecheck ['availed'] - $service_used_extra;
            // echo $original_used_extra;
            $service_extra       = ModUtil::apiFunc('ZSELEX', 'user',
                    'selectArray',
                    $args                = array(
                    'table' => 'zselex_shop_banner',
                    'where' => array(
                        "shop_id=$shop_id"
                    ),
                    'orderby' => 'shop_banner_id DESC',
                    'limit' => "LIMIT 0 , $service_used_extra"
            ));

            // echo "<pre>"; print_r($service_extra); echo "</pre>";

            foreach ($service_extra as $extra_item) {
                unlink('zselexdata/'.$ownername.'/banner/'.$extra_item [banner_image]);
                unlink('zselexdata/'.$ownername.'/banner/resized/'.$extra_item [banner_image]);

                $where = "shop_banner_id=$extra_item[shop_banner_id]";
                DBUtil::deleteWhere('zselex_shop_banner', $where);

                // echo $extra_item['pdf_name'] . '<br>';
            }
            $upd_ser_args   = array(
                'table' => 'zselex_serviceshop',
                'items' => array(
                    'availed' => $original_used_extra
                ),
                'where' => array(
                    'shop_id' => $shop_id,
                    'type' => 'minisitebanner'
                )
            );
            $update_service = ModUtil::apiFunc('ZSELEX', 'admin',
                    'updateElementWhere', $upd_ser_args);
        }
        return true;
    }

    public function deleteExtraBanner($args)
    {
        // error_reporting('E_ALL');
        $arra      = array();
        $shop_id   = $args ['shop_id'];
        $ownername = $args ['ownername'];
        // $bannerCount = DBUtil::selectObjectCount('zselex_shop_banner', $where = "shop_id=$shop_id");
        // if ($bannerCount > 1) {

        $service_extra = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray',
                $args          = array(
                'table' => 'zselex_shop_banner',
                'where' => array(
                    "shop_id=$shop_id"
                ),
                'orderby' => 'shop_banner_id ASC',
                'limit' => "LIMIT 0 , 1"
        ));

        // echo "<pre>"; print_r($service_extra); echo "</pre>";

        foreach ($service_extra as $extra_item) { //
            // unlink('zselexdata/' . $ownername . '/banner/' . $extra_item[banner_image]);
            unlink('zselexdata/'.$shop_id.'/banner/resized/'.$extra_item [banner_image]);
            $where = "shop_banner_id=$extra_item[shop_banner_id]";
            DBUtil::deleteWhere('zselex_shop_banner', $where);
            // Error: unlink function was called for image exits - solved
        }
        // }
        return true;
    }

    function uploadBanner1($fileName, $destination)
    {
        require_once ('modules/ZSELEX/lib/vendor/ImageResize.php');

        // *** 1) Initialise / load image
        $resizeObj = new ImageResize($destination.$fileName);

        // *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
        $resizeObj->resizeImage(1350, 320, 'crop');

        // *** 3) Save image
        $resizeObj->saveImage($destination.'resized/'.$fileName, 100);
        unlink($destination.$fileName);

        return true;
    }
    /*
     * upload banner
     *
     * @return boolean
     */

    function uploadBanner($fileName, $destination, $shop_id)
    {
        $image_mode = ModUtil::apiFunc('ZSELEX', 'shopsetting',
                'getBannerImageMode',
                array(
                'shop_id' => $shop_id
        ));

        if ($image_mode ['image_mode'] < 1) {
            $func = 'bannerResize';
        } elseif ($image_mode ['image_mode'] == 1) {
            $func = 'bannerStretch';
        } elseif ($image_mode ['image_mode'] == 2) {
            $func = 'bannerStretch';
        }

        $resizeBanner = ModUtil::apiFunc('ZSELEX', 'admin', $func,
                $args         = array(
                'filename' => $fileName,
                'destination' => $destination
        ));

        unlink($destination.$fileName);

        return true;
    }

    public function upload_minisite_banner()
    {
        $exceed       = 0;
        $shop_id      = $_REQUEST ['shop_id'];
        $uploadPath   = $_REQUEST ['ax-file-path'];
        $fileName     = $_REQUEST ['ax-file-name'];
        $originalName = $_REQUEST ['ax-file-name'];
        $currByte     = $_REQUEST ['ax-start-byte'];
        $maxFileSize  = $_REQUEST ['ax-maxFileSize'];
        $html5fsize   = $_REQUEST ['ax-fileSize'];
        $isLast       = $_REQUEST ['isLast'];

        // if set generates thumbs only on images type files
        $thumbHeight  = $_REQUEST ['ax-thumbHeight'];
        $thumbWidth   = $_REQUEST ['ax-thumbWidth'];
        $thumbPostfix = $_REQUEST ['ax-thumbPostfix'];
        $thumbPath    = $_REQUEST ['ax-thumbPath'];
        // echo $thumbPath; exit;
        $thumbFormat  = $_REQUEST ['ax-thumbFormat'];
        $user_id      = UserUtil::getVar('uid');

        /*
         * if ($currByte == 0) {
         * $path_parts = pathinfo($fileName);
         * $extension = $path_parts['extension'];
         * $basename = basename($fileName, ".$extension");
         * $fileName = preg_replace("/[^A-Za-z0-9_-]/", "", $basename) . "-" . time() . ".$extension";
         * }
         */

        // $basename = basename($fileName, ".pdf");
        // $image_name = preg_replace("/[^A-Za-z0-9_-]/", "", $basename) . "-" . time();
        // $image_name = preg_replace("/[^A-Za-z0-9_-]/", "", $basename);
        // $fileName = preg_replace("/[^A-Za-z0-9_-]/", "", $basename) . "-" . time() . ".pdf";

        $path_parts = pathinfo($fileName);
        $files_name = $path_parts ['filename'];
        $extension  = $path_parts ['extension'];

        $fileName = preg_replace("/[^A-Za-z0-9_-]/", "", $files_name).".$extension";

        $allowExt = (empty($_REQUEST ['ax-allow-ext'])) ? array() : explode('|',
                $_REQUEST ['ax-allow-ext']);
        $uploadPath .= (!in_array(substr($uploadPath, - 1),
                array(
                '\\',
                '/'
            ))) ? DIRECTORY_SEPARATOR : ''; // normalize path

        if (!file_exists($uploadPath) && !empty($uploadPath)) {
            mkdir($uploadPath, 0775, true);
            chmod($uploadPath, 0775);
        }

        $image_upload = $uploadPath."banner/";
        if (!file_exists($image_upload) && !empty($image_upload)) {
            mkdir($image_upload, 0775, true);
            chmod($image_upload, 0775);
        }

        $fullsize = $image_upload."resized/";
        if (!file_exists($fullsize) && !empty($fullsize)) {
            mkdir($fullsize, 0775, true);
            chmod($fullsize, 0775);
        }

        if (isset($_FILES ['ax-files'])) {
            // exit;
            // for eahc theorically runs only 1 time, since i upload i file per time
            foreach ($_FILES ['ax-files'] ['error'] as $key => $error) {
                if ($error == UPLOAD_ERR_OK) {
                    $newName  = !empty($fileName) ? $fileName : $_FILES ['ax-files'] ['name'] [$key];
                    $fullPath = $this->checkFilename($image_upload,
                        $maxFileSize, $allowExt, $newName,
                        $_FILES ['ax-files'] ['size'] [$key]);

                    if ($fullPath) {
                        move_uploaded_file($_FILES ['ax-files'] ['tmp_name'] [$key],
                            $fullPath);
                        if (!empty($thumbWidth) || !empty($thumbHeight))
                                $this->createThumbGD($pdfupload, $thumbPath,
                                $thumbPostfix, $thumbWidth, $thumbHeight,
                                $thumbFormat);
                        ZSELEX_Util::ajaxOutput(json_encode(array(
                            'name' => basename($fullPath),
                            'size' => filesize($fullPath),
                            'status' => 'uploaded',
                            'info' => 'File uploaded'
                        )));
                        // ZSELEX_Util::ajaxOutput($output);
                    }
                } else {
                    ZSELEX_Util::ajaxOutput(json_encode(array(
                        'name' => basename($_FILES ['ax-files'] ['name'] [$key]),
                        'size' => $_FILES ['ax-files'] ['size'] [$key],
                        'status' => 'error',
                        'info' => $error
                    )));

                    // ZSELEX_Util::ajaxOutput($output);
                }
            }
        } elseif (isset($_REQUEST ['ax-file-name'])) {
            // echo "comes here"; exit;
            // echo "uploadPath : " . $uploadPath; exit;
            // check only the first peice
            $fullPath = ($currByte != 0) ? $image_upload.$fileName : $this->checkFilename($image_upload,
                    $maxFileSize, $allowExt, $fileName, $html5fsize);

            if ($fullPath) {

                $explod       = explode("/", $fullPath);
                $nums         = (count($explod) - 1);
                $new_filename = $explod [$nums];

                // $basename = basename($new_filename, ".jpg");
                // $image_name = preg_replace("/[^A-Za-z0-9_-]/", "", $basename);

                $path_parts = pathinfo($new_filename);
                $image_name = $path_parts ['filename'];

                $flag          = ($currByte == 0) ? 0 : FILE_APPEND;
                $receivedBytes = file_get_contents('php://input');
                // strange bug on very fast connections like localhost, some times cant write on file
                // TODO future version save parts on different files and then make join of parts
                while (@file_put_contents($fullPath, $receivedBytes, $flag) === false) {
                    usleep(50);
                }

                if ($isLast == 'true') {
                    // $this->createThumbGD($fullPath, $thumbPath, $thumbPostfix, $thumbWidth, $thumbHeight, $thumbFormat);
                }

                $servicecount = 0;
                // usleep(50);
                $destination  = $image_upload;
                if ($isLast == 'true') {
                    $uploadBanner = $this->uploadBanner($new_filename,
                        $destination);
                    if ($uploadBanner) {
                        $item = array(
                            'shop_id' => $shop_id,
                            'banner_image' => $new_filename
                        );

                        $args   = array(
                            'table' => 'zselex_shop_banner',
                            'element' => $item,
                            'Id' => 'shop_banner_id'
                        );
                        $result = ModUtil::apiFunc('ZSELEX', 'admin',
                                'createElement', $args);
                        if ($result) {
                            $serviceupdatearg      = array(
                                'type' => 'minisitebanner',
                                'shop_id' => $shop_id
                            );
                            $ownerName             = ModUtil::apiFunc('ZSELEX',
                                    'admin', 'getOwner',
                                    $args                  = array(
                                    'shop_id' => $shop_id
                            ));
                            $serviceavailed        = ModUtil::apiFunc('ZSELEX',
                                    'admin', 'updateServiceUsed',
                                    $serviceupdatearg);
                            $args_del_extra_banner = array(
                                'ownername' => $ownerName,
                                'shop_id' => $shop_id
                            );
                            $this->deleteExtraBanner($args_del_extra_banner);
                        }
                    }
                    // }
                    // }
                }

                // echo "fullpath :" . $fullPath; exit;
                // ZSELEX_Util::ajaxOutput(json_encode(array('name' => basename($fullPath), 'size' => $currByte, 'status' => 'uploaded', 'servicecount' => $servicecount, 'msg' => ($servicecount < 1) ? $this->__("Your service limit is over for this service") : '', 'info' => 'File/chunk uploaded')));
                ZSELEX_Util::ajaxOutput(json_encode(array(
                    'name' => $new_filename,
                    'size' => $currByte,
                    'status' => 'uploaded',
                    'servicecount' => $exceed,
                    'msg' => ($servicecount < 1) ? $this->__("Your service limit is over for this service")
                            : '',
                    'info' => 'File/chunk uploaded'
                )));
                // ZSELEX_Util::ajaxOutput($output);
            }
        }
    }

    public function upload_minisite_images()
    {
        $exceed       = 0;
        $shop_id      = $_REQUEST ['shop_id'];
        $uploadPath   = $_REQUEST ['ax-file-path'];
        $fileName     = $_REQUEST ['ax-file-name'];
        $originalName = $_REQUEST ['ax-file-name'];
        $currByte     = $_REQUEST ['ax-start-byte'];
        $maxFileSize  = $_REQUEST ['ax-maxFileSize'];
        $html5fsize   = $_REQUEST ['ax-fileSize'];
        $isLast       = $_REQUEST ['isLast'];

        // if set generates thumbs only on images type files
        $thumbHeight  = $_REQUEST ['ax-thumbHeight'];
        $thumbWidth   = $_REQUEST ['ax-thumbWidth'];
        $thumbPostfix = $_REQUEST ['ax-thumbPostfix'];
        $thumbPath    = $_REQUEST ['ax-thumbPath'];
        // echo $thumbPath; exit;
        $thumbFormat  = $_REQUEST ['ax-thumbFormat'];
        $user_id      = UserUtil::getVar('uid');

        /*
         * if ($currByte == 0) {
         * $path_parts = pathinfo($fileName);
         * $extension = $path_parts['extension'];
         * $basename = basename($fileName, ".$extension");
         * $fileName = preg_replace("/[^A-Za-z0-9_-]/", "", $basename) . "-" . time() . ".$extension";
         * }
         */

        // $basename = basename($fileName, ".pdf");
        // $image_name = preg_replace("/[^A-Za-z0-9_-]/", "", $basename) . "-" . time();
        // $image_name = preg_replace("/[^A-Za-z0-9_-]/", "", $basename);
        // $fileName = preg_replace("/[^A-Za-z0-9_-]/", "", $basename) . "-" . time() . ".pdf";
        $allowExt = (empty($_REQUEST ['ax-allow-ext'])) ? array() : explode('|',
                $_REQUEST ['ax-allow-ext']);
        $uploadPath .= (!in_array(substr($uploadPath, - 1),
                array(
                '\\',
                '/'
            ))) ? DIRECTORY_SEPARATOR : ''; // normalize path

        if (!file_exists($uploadPath) && !empty($uploadPath)) {
            mkdir($uploadPath, 0775, true);
            chmod($uploadPath, 0775);
        }

        $image_upload = $uploadPath."minisiteimages/";
        if (!file_exists($image_upload) && !empty($image_upload)) {
            mkdir($image_upload, 0775, true);
            chmod($image_upload, 0775);
        }

        $fullsize = $image_upload."fullsize/";
        if (!file_exists($fullsize) && !empty($fullsize)) {
            mkdir($fullsize, 0775, true);
            chmod($fullsize, 0775);
        }

        $medium = $image_upload."medium/";
        if (!file_exists($medium) && !empty($medium)) {
            mkdir($medium, 0775, true);
            chmod($medium, 0775);
        }

        $thumb = $image_upload."thumb/";
        if (!file_exists($thumb) && !empty($thumb)) {
            mkdir($thumb, 0775, true);
            chmod($thumb, 0775);
        }

        if (isset($_FILES ['ax-files'])) {
            // exit;
            // for eahc theorically runs only 1 time, since i upload i file per time
            foreach ($_FILES ['ax-files'] ['error'] as $key => $error) {
                if ($error == UPLOAD_ERR_OK) {
                    $newName  = !empty($fileName) ? $fileName : $_FILES ['ax-files'] ['name'] [$key];
                    $fullPath = $this->checkFilename($image_upload,
                        $maxFileSize, $allowExt, $newName,
                        $_FILES ['ax-files'] ['size'] [$key]);

                    if ($fullPath) {
                        move_uploaded_file($_FILES ['ax-files'] ['tmp_name'] [$key],
                            $fullPath);
                        if (!empty($thumbWidth) || !empty($thumbHeight))
                                $this->createThumbGD($pdfupload, $thumbPath,
                                $thumbPostfix, $thumbWidth, $thumbHeight,
                                $thumbFormat);
                        ZSELEX_Util::ajaxOutput(json_encode(array(
                            'name' => basename($fullPath),
                            'size' => filesize($fullPath),
                            'status' => 'uploaded',
                            'info' => 'File uploaded'
                        )));
                        // ZSELEX_Util::ajaxOutput($output);
                    }
                } else {
                    ZSELEX_Util::ajaxOutput(json_encode(array(
                        'name' => basename($_FILES ['ax-files'] ['name'] [$key]),
                        'size' => $_FILES ['ax-files'] ['size'] [$key],
                        'status' => 'error',
                        'info' => $error
                    )));

                    // ZSELEX_Util::ajaxOutput($output);
                }
            }
        } elseif (isset($_REQUEST ['ax-file-name'])) {
            // echo "comes here"; exit;
            // echo "uploadPath : " . $uploadPath; exit;
            // check only the first peice
            $fullPath = ($currByte != 0) ? $image_upload.$fileName : $this->checkFilename($image_upload,
                    $maxFileSize, $allowExt, $fileName, $html5fsize);

            if ($fullPath) {

                $explod       = explode("/", $fullPath);
                $nums         = (count($explod) - 1);
                $new_filename = $explod [$nums];

                // $basename = basename($new_filename, ".jpg");
                // $image_name = preg_replace("/[^A-Za-z0-9_-]/", "", $basename);

                $flag          = ($currByte == 0) ? 0 : FILE_APPEND;
                $receivedBytes = file_get_contents('php://input');
                // strange bug on very fast connections like localhost, some times cant write on file
                // TODO future version save parts on different files and then make join of parts
                while (@file_put_contents($fullPath, $receivedBytes, $flag) === false) {
                    usleep(50);
                }

                if ($isLast == 'true') {
                    // $this->createThumbGD($fullPath, $thumbPath, $thumbPostfix, $thumbWidth, $thumbHeight, $thumbFormat);
                }

                $servicecount = 0;
                // usleep(50);
                $des          = $image_upload;
                if ($isLast == 'true') {
                    ZSELEX_Controller_Admin::uploadImages3($new_filename, $des);
                    /*
                     * require_once ('modules/ZSELEX/lib/vendor/ImageResize.php');
                     * $resizeObj = new ImageResize($image_upload . '/' . $new_filename);
                     * $resizeObj->resizeImage(1024, 768);
                     * $resizeObj->saveImage($image_upload . '/fullsize/' . $new_filename, 100);
                     *
                     * $resizeObj->resizeImage(400, 300);
                     * $resizeObj->saveImage($image_upload . '/medium/' . $new_filename, 100);
                     *
                     * $resizeObj->resizeImage(170, 200);
                     * $resizeObj->saveImage($image_upload . '/thumb/' . $new_filename, 100);
                     * unlink($image_upload . '/' . $new_filename);
                     */

                    $item = array(
                        'name' => $new_filename,
                        'shop_id' => $shop_id,
                        'user_id' => $user_id,
                        'display' => 1
                    );

                    $args   = array(
                        'table' => 'zselex_files',
                        'element' => $item,
                        'Id' => 'pdf_id'
                    );
                    $result = ModUtil::apiFunc('ZSELEX', 'admin',
                            'createElement', $args);
                    if ($result) {
                        $serviceupdatearg = array(
                            'user_id' => $user_id,
                            'type' => 'minisiteimages',
                            'shop_id' => $shop_id
                        );
                        $serviceavailed   = ModUtil::apiFunc('ZSELEX', 'admin',
                                'updateServiceUsed', $serviceupdatearg);
                    }

                    // }
                    // }
                }

                // echo "fullpath :" . $fullPath; exit;
                // ZSELEX_Util::ajaxOutput(json_encode(array('name' => basename($fullPath), 'size' => $currByte, 'status' => 'uploaded', 'servicecount' => $servicecount, 'msg' => ($servicecount < 1) ? $this->__("Your service limit is over for this service") : '', 'info' => 'File/chunk uploaded')));
                ZSELEX_Util::ajaxOutput(json_encode(array(
                    'name' => $new_filename,
                    'size' => $currByte,
                    'status' => 'uploaded',
                    'servicecount' => $exceed,
                    'msg' => ($servicecount < 1) ? $this->__("Your service limit is over for this service")
                            : '',
                    'info' => 'File/chunk uploaded'
                )));
                // ZSELEX_Util::ajaxOutput($output);
            }
        }
    }

    public function upload_gallery_images()
    {
        $exceed       = 0;
        $shop_id      = $_REQUEST ['shop_id'];
        $uploadPath   = $_REQUEST ['ax-file-path'];
        $fileName     = $_REQUEST ['ax-file-name'];
        $originalName = $_REQUEST ['ax-file-name'];
        $currByte     = $_REQUEST ['ax-start-byte'];
        $maxFileSize  = $_REQUEST ['ax-maxFileSize'];
        $html5fsize   = $_REQUEST ['ax-fileSize'];
        $isLast       = $_REQUEST ['isLast'];

        // if set generates thumbs only on images type files
        $thumbHeight  = $_REQUEST ['ax-thumbHeight'];
        $thumbWidth   = $_REQUEST ['ax-thumbWidth'];
        $thumbPostfix = $_REQUEST ['ax-thumbPostfix'];
        $thumbPath    = $_REQUEST ['ax-thumbPath'];
        // echo $thumbPath; exit;
        $thumbFormat  = $_REQUEST ['ax-thumbFormat'];
        $user_id      = UserUtil::getVar('uid');

        // $basename = basename($fileName, ".pdf");
        // $image_name = preg_replace("/[^A-Za-z0-9_-]/", "", $basename) . "-" . time();
        $image_name = preg_replace("/[^A-Za-z0-9_-]/", "", $basename);
        // $fileName = preg_replace("/[^A-Za-z0-9_-]/", "", $basename) . "-" . time() . ".pdf";
        $allowExt   = (empty($_REQUEST ['ax-allow-ext'])) ? array() : explode('|',
                $_REQUEST ['ax-allow-ext']);
        $uploadPath .= (!in_array(substr($uploadPath, - 1),
                array(
                '\\',
                '/'
            ))) ? DIRECTORY_SEPARATOR : ''; // normalize path

        if (!file_exists($uploadPath) && !empty($uploadPath)) {
            mkdir($uploadPath, 0775, true);
            chmod($uploadPath, 0775);
        }

        $image_upload = $uploadPath."minisitegallery/";
        if (!file_exists($image_upload) && !empty($image_upload)) {
            mkdir($image_upload, 0775, true);
            chmod($image_upload, 0775);
        }

        $fullsize = $image_upload."fullsize/";
        if (!file_exists($fullsize) && !empty($fullsize)) {
            mkdir($fullsize, 0775, true);
            chmod($fullsize, 0775);
        }

        $medium = $image_upload."medium/";
        if (!file_exists($medium) && !empty($medium)) {
            mkdir($medium, 0775, true);
            chmod($medium, 0775);
        }

        $thumb = $image_upload."thumb/";
        if (!file_exists($thumb) && !empty($thumb)) {
            mkdir($thumb, 0775, true);
            chmod($thumb, 0775);
        }

        if (isset($_FILES ['ax-files'])) {
            // exit;
            // for eahc theorically runs only 1 time, since i upload i file per time
            foreach ($_FILES ['ax-files'] ['error'] as $key => $error) {
                if ($error == UPLOAD_ERR_OK) {
                    $newName  = !empty($fileName) ? $fileName : $_FILES ['ax-files'] ['name'] [$key];
                    $fullPath = $this->checkFilename($image_upload,
                        $maxFileSize, $allowExt, $newName,
                        $_FILES ['ax-files'] ['size'] [$key]);

                    if ($fullPath) {
                        move_uploaded_file($_FILES ['ax-files'] ['tmp_name'] [$key],
                            $fullPath);
                        if (!empty($thumbWidth) || !empty($thumbHeight))
                                $this->createThumbGD($pdfupload, $thumbPath,
                                $thumbPostfix, $thumbWidth, $thumbHeight,
                                $thumbFormat);
                        ZSELEX_Util::ajaxOutput(json_encode(array(
                            'name' => basename($fullPath),
                            'size' => filesize($fullPath),
                            'status' => 'uploaded',
                            'info' => 'File uploaded'
                        )));
                        // ZSELEX_Util::ajaxOutput($output);
                    }
                } else {
                    ZSELEX_Util::ajaxOutput(json_encode(array(
                        'name' => basename($_FILES ['ax-files'] ['name'] [$key]),
                        'size' => $_FILES ['ax-files'] ['size'] [$key],
                        'status' => 'error',
                        'info' => $error
                    )));

                    // ZSELEX_Util::ajaxOutput($output);
                }
            }
        } elseif (isset($_REQUEST ['ax-file-name'])) {
            // echo "comes here"; exit;
            // echo "uploadPath : " . $uploadPath; exit;
            // check only the first peice
            $fullPath = ($currByte != 0) ? $image_upload.$fileName : $this->checkFilename($image_upload,
                    $maxFileSize, $allowExt, $fileName, $html5fsize);

            if ($fullPath) {

                $explod       = explode("/", $fullPath);
                $nums         = (count($explod) - 1);
                $new_filename = $explod [$nums];

                $basename   = basename($new_filename, ".jpg");
                $image_name = preg_replace("/[^A-Za-z0-9_-]/", "", $basename);

                $flag          = ($currByte == 0) ? 0 : FILE_APPEND;
                $receivedBytes = file_get_contents('php://input');
                // strange bug on very fast connections like localhost, some times cant write on file
                // TODO future version save parts on different files and then make join of parts
                while (@file_put_contents($fullPath, $receivedBytes, $flag) === false) {
                    usleep(50);
                }

                if ($isLast == 'true') {
                    // $this->createThumbGD($fullPath, $thumbPath, $thumbPostfix, $thumbWidth, $thumbHeight, $thumbFormat);
                }

                $servicecount = 0;
                // usleep(50);
                $des          = $image_upload;
                if ($isLast == 'true') {
                    ZSELEX_Controller_Admin::uploadImages3($new_filename, $des);
                    /*
                     * require_once ('modules/ZSELEX/lib/vendor/ImageResize.php');
                     * $resizeObj = new ImageResize($image_upload . '/' . $new_filename);
                     * $resizeObj->resizeImage(1024, 768);
                     * $resizeObj->saveImage($image_upload . '/fullsize/' . $new_filename, 100);
                     *
                     * $resizeObj->resizeImage(400, 300);
                     * $resizeObj->saveImage($image_upload . '/medium/' . $new_filename, 100);
                     *
                     * $resizeObj->resizeImage(170, 200);
                     * $resizeObj->saveImage($image_upload . '/thumb/' . $new_filename, 100);
                     */
                    // unlink($image_upload . '/' . $new_filename);

                    $item = array(
                        'image_name' => $new_filename,
                        'shop_id' => $shop_id,
                        'user_id' => $user_id
                    );

                    $args   = array(
                        'table' => 'zselex_shop_gallery',
                        'element' => $item,
                        'Id' => 'gallery_id'
                    );
                    $result = ModUtil::apiFunc('ZSELEX', 'admin',
                            'createElement', $args);
                    if ($result) {
                        $serviceupdatearg = array(
                            'user_id' => $user_id,
                            'type' => 'minisitegallery',
                            'shop_id' => $shop_id
                        );
                        $serviceavailed   = ModUtil::apiFunc('ZSELEX', 'admin',
                                'updateServiceUsed', $serviceupdatearg);
                    }

                    // }
                    // }
                }

                // echo "fullpath :" . $fullPath; exit;
                // ZSELEX_Util::ajaxOutput(json_encode(array('name' => basename($fullPath), 'size' => $currByte, 'status' => 'uploaded', 'servicecount' => $servicecount, 'msg' => ($servicecount < 1) ? $this->__("Your service limit is over for this service") : '', 'info' => 'File/chunk uploaded')));
                ZSELEX_Util::ajaxOutput(json_encode(array(
                    'name' => $new_filename,
                    'size' => $currByte,
                    'status' => 'uploaded',
                    'servicecount' => $exceed,
                    'msg' => ($servicecount < 1) ? $this->__("Your service limit is over for this service")
                            : '',
                    'info' => 'File/chunk uploaded'
                )));
                // ZSELEX_Util::ajaxOutput($output);
            }
        }
    }

    public function upload_product_images()
    {
        $exceed       = 0;
        $shop_id      = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $uploadPath   = FormUtil::getPassedValue('ax-file-path', null, 'REQUEST');
        $fileName     = FormUtil::getPassedValue('ax-file-name', null, 'REQUEST');
        $originalName = FormUtil::getPassedValue('ax-file-name', null, 'REQUEST');
        $currByte     = FormUtil::getPassedValue('ax-start-byte', null,
                'REQUEST');
        $maxFileSize  = FormUtil::getPassedValue('ax-maxFileSize', null,
                'REQUEST');
        $html5fsize   = FormUtil::getPassedValue('ax-fileSize', null, 'REQUEST');
        $isLast       = FormUtil::getPassedValue('isLast', null, 'REQUEST');

        // if set generates thumbs only on images type files
        $thumbHeight  = FormUtil::getPassedValue('ax-thumbHeight', null,
                'REQUEST');
        $thumbWidth   = FormUtil::getPassedValue('ax-thumbWidth', null,
                'REQUEST');
        $thumbPostfix = FormUtil::getPassedValue('ax-thumbPostfix', null,
                'REQUEST');
        $thumbPath    = FormUtil::getPassedValue('ax-thumbPath', null, 'REQUEST');
        // echo $thumbPath; exit;
        $thumbFormat  = FormUtil::getPassedValue('ax-thumbFormat', null,
                'REQUEST');
        $user_id      = UserUtil::getVar('uid');

        // $basename = basename($fileName, ".pdf");
        // $image_name = preg_replace("/[^A-Za-z0-9_-]/", "", $basename) . "-" . time();
        $image_name = preg_replace("/[^A-Za-z0-9_-]/", "", $basename);
        // $fileName = preg_replace("/[^A-Za-z0-9_-]/", "", $basename) . "-" . time() . ".pdf";
        $allowExt   = (empty($_REQUEST ['ax-allow-ext'])) ? array() : explode('|',
                $_REQUEST ['ax-allow-ext']);
        $uploadPath .= (!in_array(substr($uploadPath, - 1),
                array(
                '\\',
                '/'
            ))) ? DIRECTORY_SEPARATOR : ''; // normalize path

        if (!file_exists($uploadPath) && !empty($uploadPath)) {
            mkdir($uploadPath, 0775, true);
            chmod($uploadPath, 0775);
        }

        $image_upload = $uploadPath."products/";
        if (!file_exists($image_upload) && !empty($image_upload)) {
            mkdir($image_upload, 0775, true);
            chmod($image_upload, 0775);
        }

        $fullsize = $image_upload."fullsize/";
        if (!file_exists($fullsize) && !empty($fullsize)) {
            mkdir($fullsize, 0775, true);
            chmod($fullsize, 0775);
        }

        $medium = $image_upload."medium/";
        if (!file_exists($medium) && !empty($medium)) {
            mkdir($medium, 0775, true);
            chmod($medium, 0775);
        }

        $thumb = $image_upload."thumb/";
        if (!file_exists($thumb) && !empty($thumb)) {
            mkdir($thumb, 0775, true);
            chmod($thumb, 0775);
        }

        if (isset($_FILES ['ax-files'])) {
            // exit;
            // for eahc theorically runs only 1 time, since i upload i file per time
            foreach ($_FILES ['ax-files'] ['error'] as $key => $error) {
                if ($error == UPLOAD_ERR_OK) {
                    $newName  = !empty($fileName) ? $fileName : $_FILES ['ax-files'] ['name'] [$key];
                    $fullPath = $this->checkFilename($image_upload,
                        $maxFileSize, $allowExt, $newName,
                        $_FILES ['ax-files'] ['size'] [$key]);

                    if ($fullPath) {
                        move_uploaded_file($_FILES ['ax-files'] ['tmp_name'] [$key],
                            $fullPath);
                        if (!empty($thumbWidth) || !empty($thumbHeight))
                                $this->createThumbGD($pdfupload, $thumbPath,
                                $thumbPostfix, $thumbWidth, $thumbHeight,
                                $thumbFormat);
                        ZSELEX_Util::ajaxOutput(json_encode(array(
                            'name' => basename($fullPath),
                            'size' => filesize($fullPath),
                            'status' => 'uploaded',
                            'info' => 'File uploaded'
                        )));
                        // ZSELEX_Util::ajaxOutput($output);
                    }
                } else {
                    ZSELEX_Util::ajaxOutput(json_encode(array(
                        'name' => basename($_FILES ['ax-files'] ['name'] [$key]),
                        'size' => $_FILES ['ax-files'] ['size'] [$key],
                        'status' => 'error',
                        'info' => $error
                    )));

                    // ZSELEX_Util::ajaxOutput($output);
                }
            }
        } elseif (isset($_REQUEST ['ax-file-name'])) {
            // echo "comes here"; exit;
            // echo "uploadPath : " . $uploadPath; exit;
            // check only the first peice
            $fullPath = ($currByte != 0) ? $image_upload.$fileName : $this->checkFilename($image_upload,
                    $maxFileSize, $allowExt, $fileName, $html5fsize);

            if ($fullPath) {

                $explod       = explode("/", $fullPath);
                $nums         = (count($explod) - 1);
                $new_filename = $explod [$nums];

                $basename   = basename($new_filename, ".jpg");
                $image_name = preg_replace("/[^A-Za-z0-9_-]/", "", $basename);

                $flag          = ($currByte == 0) ? 0 : FILE_APPEND;
                $receivedBytes = file_get_contents('php://input');
                // strange bug on very fast connections like localhost, some times cant write on file
                // TODO future version save parts on different files and then make join of parts
                while (@file_put_contents($fullPath, $receivedBytes, $flag) === false) {
                    usleep(50);
                }

                $servicecount = 0;
                // usleep(50);
                $des          = $image_upload;
                if ($isLast == 'true') {

                    ZSELEX_Controller_Admin::uploadImages3($new_filename, $des);

                    $urltitle       = strtolower($image_name);
                    $urltitle       = str_replace(" ", '-', $urltitle);
                    $args_url       = array(
                        'table' => 'zselex_products',
                        'title' => $urltitle,
                        'field' => 'urltitle'
                    );
                    $final_urltitle = ZSELEX_Controller_Admin::increment_url($args_url);

                    $item = array(
                        'shop_id' => $shop_id,
                        'user_id' => $user_id,
                        'product_name' => $image_name,
                        'urltitle' => $final_urltitle,
                        'prd_image' => $new_filename,
                        'prd_status' => 1
                    );

                    $args   = array(
                        'table' => 'zselex_products',
                        'element' => $item,
                        'Id' => 'product_id'
                    );
                    $result = ModUtil::apiFunc('ZSELEX', 'admin',
                            'createElement', $args);
                    if ($result) {
                        $serviceupdatearg = array(
                            'user_id' => $user_id,
                            'type' => 'addproducts',
                            'shop_id' => $shop_id
                        );
                        $serviceavailed   = ModUtil::apiFunc('ZSELEX', 'admin',
                                'updateServiceUsed', $serviceupdatearg);
                    }

                    // }
                    // }
                }

                // echo "fullpath :" . $fullPath; exit;
                // ZSELEX_Util::ajaxOutput(json_encode(array('name' => basename($fullPath), 'size' => $currByte, 'status' => 'uploaded', 'servicecount' => $servicecount, 'msg' => ($servicecount < 1) ? $this->__("Your service limit is over for this service") : '', 'info' => 'File/chunk uploaded')));
                ZSELEX_Util::ajaxOutput(json_encode(array(
                    'name' => $new_filename,
                    'size' => $currByte,
                    'status' => 'uploaded',
                    'servicecount' => $exceed,
                    'msg' => ($servicecount < 1) ? $this->__("Your service limit is over for this service")
                            : '',
                    'info' => 'File/chunk uploaded'
                )));
                // ZSELEX_Util::ajaxOutput($output);
            }
        }
    }

    public function upload_products($args)
    {
        $uploadPath = FormUtil::getPassedValue('ax-file-path', null, 'REQUEST');
        if (!file_exists($uploadPath) && !empty($uploadPath)) {
            mkdir($uploadPath, 0775, true);
            chmod($uploadPath, 0775);
        }

        $image_upload = $uploadPath;
        if (!file_exists($image_upload) && !empty($image_upload)) {
            mkdir($image_upload, 0775, true);
            chmod($image_upload, 0775);
        }

        $fullsize = $image_upload."/fullsize/";
        if (!file_exists($fullsize) && !empty($fullsize)) {
            mkdir($fullsize, 0775, true);
            chmod($fullsize, 0775);
        }

        $medium = $image_upload."/medium/";
        if (!file_exists($medium) && !empty($medium)) {
            mkdir($medium, 0775, true);
            chmod($medium, 0775);
        }

        $thumb = $image_upload."/thumb/";
        if (!file_exists($thumb) && !empty($thumb)) {
            mkdir($thumb, 0775, true);
            chmod($thumb, 0775);
        }

        require_once ('modules/ZSELEX/lib/vendor/DND/upload.php');
        $DENY_EXT        = array(
            'php',
            'php3',
            'php4',
            'php5',
            'phtml',
            'exe',
            'pl',
            'cgi',
            'html',
            'htm',
            'js',
            'asp',
            'aspx',
            'bat',
            'sh',
            'cmd'
        );
        $uploader        = new RealAjaxUploader($DENY_EXT); // create uploader object
        // register a callback function on file complete
        $FINISH_FUNCTION = "product_success";
        // $UPLOAD_PATH = "zselex";
        if (isset($FINISH_FUNCTION) && $FINISH_FUNCTION)
                $uploader->onFinish($FINISH_FUNCTION); // set name of external function to be called on finish upload

        if (isset($_REQUEST ['ax-check-file'])) {
            $uploader->header();
            echo $uploader->_checkFileExists() ? 'yes' : 'no';
        } elseif (isset($_REQUEST ['ax-delete-file']) && $ALLOW_DELETE) {
            $uploader->header();
            echo $uploader->deleteFile();
        } else {
            $uploader->uploadFile();
        }
    }

    public function upload_employee_images()
    {
        $exceed       = 0;
        $shop_id      = $_REQUEST ['shop_id'];
        $uploadPath   = $_REQUEST ['ax-file-path'];
        $fileName     = $_REQUEST ['ax-file-name'];
        $originalName = $_REQUEST ['ax-file-name'];
        $currByte     = $_REQUEST ['ax-start-byte'];
        $maxFileSize  = $_REQUEST ['ax-maxFileSize'];
        $html5fsize   = $_REQUEST ['ax-fileSize'];
        $isLast       = $_REQUEST ['isLast'];

        // if set generates thumbs only on images type files
        $thumbHeight  = $_REQUEST ['ax-thumbHeight'];
        $thumbWidth   = $_REQUEST ['ax-thumbWidth'];
        $thumbPostfix = $_REQUEST ['ax-thumbPostfix'];
        $thumbPath    = $_REQUEST ['ax-thumbPath'];
        // echo $thumbPath; exit;
        $thumbFormat  = $_REQUEST ['ax-thumbFormat'];
        $user_id      = UserUtil::getVar('uid');

        /*
         * if ($currByte == 0) {
         * $path_parts = pathinfo($fileName);
         * $extension = $path_parts['extension'];
         * $basename = basename($fileName, ".$extension");
         * $fileName = preg_replace("/[^A-Za-z0-9_-]/", "", $basename) . "-" . time() . ".$extension";
         * }
         */

        // $basename = basename($fileName, ".pdf");
        // $image_name = preg_replace("/[^A-Za-z0-9_-]/", "", $basename) . "-" . time();
        // $image_name = preg_replace("/[^A-Za-z0-9_-]/", "", $basename);
        // $fileName = preg_replace("/[^A-Za-z0-9_-]/", "", $basename) . "-" . time() . ".pdf";

        $path_parts = pathinfo($fileName);
        $files_name = $path_parts ['filename'];
        $extension  = $path_parts ['extension'];
        // if ($isLast != 'true') {
        // $fileName = preg_replace("/[^A-Za-z0-9_-]/", "", $files_name) . "-" . time() . ".$extension";
        // }
        $time       = '';
        if ($isLast == 'true') {
            // $time = "-" . time();
        }

        $fileName = preg_replace("/[^A-Za-z0-9_-]/", "", $files_name).".$extension";

        $allowExt = (empty($_REQUEST ['ax-allow-ext'])) ? array() : explode('|',
                $_REQUEST ['ax-allow-ext']);
        $uploadPath .= (!in_array(substr($uploadPath, - 1),
                array(
                '\\',
                '/'
            ))) ? DIRECTORY_SEPARATOR : ''; // normalize path

        if (!file_exists($uploadPath) && !empty($uploadPath)) {
            mkdir($uploadPath, 0775, true);
            chmod($uploadPath, 0775);
        }

        $image_upload = $uploadPath."employees/";
        if (!file_exists($image_upload) && !empty($image_upload)) {
            mkdir($image_upload, 0775, true);
            chmod($image_upload, 0775);
        }

        $fullsize = $image_upload."fullsize/";
        if (!file_exists($fullsize) && !empty($fullsize)) {
            mkdir($fullsize, 0775, true);
            chmod($fullsize, 0775);
        }

        $medium = $image_upload."medium/";
        if (!file_exists($medium) && !empty($medium)) {
            mkdir($medium, 0775, true);
            chmod($medium, 0775);
        }

        $thumb = $image_upload."thumb/";
        if (!file_exists($thumb) && !empty($thumb)) {
            mkdir($thumb, 0775, true);
            chmod($thumb, 0775);
        }

        if (isset($_FILES ['ax-files'])) {
            // exit;
            // for eahc theorically runs only 1 time, since i upload i file per time
            foreach ($_FILES ['ax-files'] ['error'] as $key => $error) {
                if ($error == UPLOAD_ERR_OK) {
                    $newName  = !empty($fileName) ? $fileName : $_FILES ['ax-files'] ['name'] [$key];
                    $fullPath = $this->checkFilename($image_upload,
                        $maxFileSize, $allowExt, $newName,
                        $_FILES ['ax-files'] ['size'] [$key]);

                    if ($fullPath) {
                        move_uploaded_file($_FILES ['ax-files'] ['tmp_name'] [$key],
                            $fullPath);
                        if (!empty($thumbWidth) || !empty($thumbHeight))
                                $this->createThumbGD($pdfupload, $thumbPath,
                                $thumbPostfix, $thumbWidth, $thumbHeight,
                                $thumbFormat);
                        ZSELEX_Util::ajaxOutput(json_encode(array(
                            'name' => basename($fullPath),
                            'size' => filesize($fullPath),
                            'status' => 'uploaded',
                            'info' => 'File uploaded'
                        )));
                        // ZSELEX_Util::ajaxOutput($output);
                    }
                } else {
                    ZSELEX_Util::ajaxOutput(json_encode(array(
                        'name' => basename($_FILES ['ax-files'] ['name'] [$key]),
                        'size' => $_FILES ['ax-files'] ['size'] [$key],
                        'status' => 'error',
                        'info' => $error
                    )));

                    // ZSELEX_Util::ajaxOutput($output);
                }
            }
        } elseif (isset($_REQUEST ['ax-file-name'])) {
            // echo "comes here"; exit;
            // echo "uploadPath : " . $uploadPath; exit;
            // check only the first peice
            $path     = $image_upload;
            $fullPath = ($currByte != 0) ? $path.$fileName : $this->checkFilename($path,
                    $maxFileSize, $allowExt, $fileName, $html5fsize);

            if ($fullPath) {

                $explod       = explode("/", $fullPath);
                $nums         = (count($explod) - 1);
                $new_filename = $explod [$nums];

                $path_parts = pathinfo($new_filename);
                $image_name = $path_parts ['filename'];

                // $basename = basename($new_filename, ".jpg");
                $image_name = preg_replace("/[^A-Za-z0-9_-]/", "", $image_name);

                $flag          = ($currByte == 0) ? 0 : FILE_APPEND;
                $receivedBytes = file_get_contents('php://input');
                // strange bug on very fast connections like localhost, some times cant write on file
                // TODO future version save parts on different files and then make join of parts
                while (@file_put_contents($fullPath, $receivedBytes, $flag) === false) {
                    usleep(50);
                }

                if ($isLast == 'true') {
                    // $this->createThumbGD($fullPath, $thumbPath, $thumbPostfix, $thumbWidth, $thumbHeight, $thumbFormat);
                }

                $servicecount = 0;
                // usleep(50);
                $des          = $image_upload;
                if ($isLast == 'true') {
                    ZSELEX_Controller_Admin::uploadEmployeeImages($new_filename,
                        $des);

                    $item = array(
                        'shop_id' => $shop_id,
                        'name' => $image_name,
                        'emp_image' => $new_filename,
                        'status' => 1
                    );

                    $args   = array(
                        'table' => 'zselex_shop_employees',
                        'element' => $item,
                        'Id' => 'emp_id'
                    );
                    $result = ModUtil::apiFunc('ZSELEX', 'admin',
                            'createElement', $args);
                    if ($result) {
                        $serviceupdatearg        = array(
                            'user_id' => $user_id,
                            'type' => 'employees',
                            'shop_id' => $shop_id
                        );
                        $serviceavailed          = ModUtil::apiFunc('ZSELEX',
                                'admin', 'updateServiceUsed', $serviceupdatearg);
                        $ownerName               = ModUtil::apiFunc('ZSELEX',
                                'admin', 'getOwner',
                                $args                    = array(
                                'shop_id' => $shop_id
                        ));
                        $args_del_extra_employee = array(
                            'ownername' => $ownerName,
                            'shop_id' => $shop_id
                        );
                        ZSELEX_Controller_Admin::deleteExtraEmployeeServices($args_del_extra_employee);
                    }

                    // }
                    // }
                }

                // echo "fullpath :" . $fullPath; exit;
                // ZSELEX_Util::ajaxOutput(json_encode(array('name' => basename($fullPath), 'size' => $currByte, 'status' => 'uploaded', 'servicecount' => $servicecount, 'msg' => ($servicecount < 1) ? $this->__("Your service limit is over for this service") : '', 'info' => 'File/chunk uploaded')));
                ZSELEX_Util::ajaxOutput(json_encode(array(
                    'name' => $new_filename,
                    'size' => $currByte,
                    'status' => 'uploaded',
                    'servicecount' => $exceed,
                    'msg' => ($servicecount < 1) ? $this->__("Your service limit is over for this service")
                            : '',
                    'info' => 'File/chunk uploaded'
                )));
                // ZSELEX_Util::ajaxOutput($output);
            }
        }
    }

    public function upload_minisite_event()
    {
        $exceed       = 0;
        $shop_id      = $_REQUEST ['shop_id'];
        $event_id     = $_REQUEST ['data'];
        $uploadPath   = $_REQUEST ['ax-file-path'];
        $fileName     = $_REQUEST ['ax-file-name'];
        $originalName = $_REQUEST ['ax-file-name'];
        $currByte     = $_REQUEST ['ax-start-byte'];
        $maxFileSize  = $_REQUEST ['ax-maxFileSize'];
        $html5fsize   = $_REQUEST ['ax-fileSize'];
        $isLast       = $_REQUEST ['isLast'];

        // if set generates thumbs only on images type files
        $thumbHeight  = $_REQUEST ['ax-thumbHeight'];
        $thumbWidth   = $_REQUEST ['ax-thumbWidth'];
        $thumbPostfix = $_REQUEST ['ax-thumbPostfix'];
        $thumbPath    = $_REQUEST ['ax-thumbPath'];
        // echo $thumbPath; exit;
        $thumbFormat  = $_REQUEST ['ax-thumbFormat'];
        $user_id      = UserUtil::getVar('uid');

        $path_parts = pathinfo($fileName);
        $files_name = $path_parts ['filename'];
        $extension  = $path_parts ['extension'];
        // if ($isLast != 'true') {
        // $fileName = preg_replace("/[^A-Za-z0-9_-]/", "", $files_name) . "-" . time() . ".$extension";
        // }
        $time       = '';
        if ($isLast == 'true') {
            // $time = "-" . time();
        }

        $fileName = preg_replace("/[^A-Za-z0-9_-]/", "", $files_name).".$extension";

        $allowExt = (empty($_REQUEST ['ax-allow-ext'])) ? array() : explode('|',
                $_REQUEST ['ax-allow-ext']);
        $uploadPath .= (!in_array(substr($uploadPath, - 1),
                array(
                '\\',
                '/'
            ))) ? DIRECTORY_SEPARATOR : ''; // normalize path

        if (!file_exists($uploadPath) && !empty($uploadPath)) {
            mkdir($uploadPath, 0775, true);
            chmod($uploadPath, 0775);
        }

        $image_upload = $uploadPath."events/";
        if (!file_exists($image_upload) && !empty($image_upload)) {
            mkdir($image_upload, 0775, true);
            chmod($image_upload, 0775);
        }

        $docs = $image_upload."docs/";
        if (!file_exists($docs) && !empty($docs)) {
            mkdir($docs, 0775, true);
            chmod($docs, 0775);
        }

        $fullsize = $image_upload."fullsize/";
        if (!file_exists($fullsize) && !empty($fullsize)) {
            mkdir($fullsize, 0775, true);
            chmod($fullsize, 0775);
        }

        $medium = $image_upload."medium/";
        if (!file_exists($medium) && !empty($medium)) {
            mkdir($medium, 0775, true);
            chmod($medium, 0775);
        }

        $thumb = $image_upload."thumb/";
        if (!file_exists($thumb) && !empty($thumb)) {
            mkdir($thumb, 0775, true);
            chmod($thumb, 0775);
        }

        if (isset($_FILES ['ax-files'])) {
            // exit;
            // for eahc theorically runs only 1 time, since i upload i file per time
            foreach ($_FILES ['ax-files'] ['error'] as $key => $error) {
                if ($error == UPLOAD_ERR_OK) {
                    $newName  = !empty($fileName) ? $fileName : $_FILES ['ax-files'] ['name'] [$key];
                    $fullPath = $this->checkFilename($image_upload,
                        $maxFileSize, $allowExt, $newName,
                        $_FILES ['ax-files'] ['size'] [$key]);

                    if ($fullPath) {
                        move_uploaded_file($_FILES ['ax-files'] ['tmp_name'] [$key],
                            $fullPath);
                        if (!empty($thumbWidth) || !empty($thumbHeight))
                                $this->createThumbGD($pdfupload, $thumbPath,
                                $thumbPostfix, $thumbWidth, $thumbHeight,
                                $thumbFormat);
                        ZSELEX_Util::ajaxOutput(json_encode(array(
                            'name' => basename($fullPath),
                            'size' => filesize($fullPath),
                            'status' => 'uploaded',
                            'info' => 'File uploaded'
                        )));
                        // ZSELEX_Util::ajaxOutput($output);
                    }
                } else {
                    ZSELEX_Util::ajaxOutput(json_encode(array(
                        'name' => basename($_FILES ['ax-files'] ['name'] [$key]),
                        'size' => $_FILES ['ax-files'] ['size'] [$key],
                        'status' => 'error',
                        'info' => $error
                    )));

                    // ZSELEX_Util::ajaxOutput($output);
                }
            }
        } elseif (isset($_REQUEST ['ax-file-name'])) {
            // echo "comes here"; exit;
            // echo "uploadPath : " . $uploadPath; exit;
            // check only the first peice
            $ex                = end(explode(".", $fileName));
            $allowedExtensions = array(
                'png',
                'jpg',
                'gif',
                'jpeg'
            );
            if (in_array($ex, $allowedExtensions)) {
                $path = $image_upload;
            } else {
                $path = $docs;
            }
            $fullPath = ($currByte != 0) ? $path.$fileName : $this->checkFilename($path,
                    $maxFileSize, $allowExt, $fileName, $html5fsize);

            if ($fullPath) {

                $explod       = explode("/", $fullPath);
                $nums         = (count($explod) - 1);
                $new_filename = $explod [$nums];

                $path_parts = pathinfo($new_filename);
                $image_name = $path_parts ['filename'];

                // $basename = basename($new_filename, ".jpg");
                $image_name = preg_replace("/[^A-Za-z0-9_-]/", "", $image_name);

                $flag          = ($currByte == 0) ? 0 : FILE_APPEND;
                $receivedBytes = file_get_contents('php://input');
                // strange bug on very fast connections like localhost, some times cant write on file
                // TODO future version save parts on different files and then make join of parts
                while (@file_put_contents($fullPath, $receivedBytes, $flag) === false) {
                    usleep(50);
                }

                if ($isLast == 'true') {
                    // $this->createThumbGD($fullPath, $thumbPath, $thumbPostfix, $thumbWidth, $thumbHeight, $thumbFormat);
                }

                $servicecount = 0;
                // usleep(50);
                $des          = $image_upload;
                if ($isLast == 'true') {

                    // ZSELEX_Controller_Admin::uploadEmployeeImages($new_filename, $des);
                    if (in_array($ex, $allowedExtensions)) {
                        $resizeImages = ModUtil::apiFunc('ZSELEX', 'upload',
                                'uploadEventImage',
                                $args         = array(
                                'filename' => $new_filename,
                                'destination' => $des
                        ));
                    }

                    if (in_array($ex, $allowedExtensions)) {
                        $showfrom = 'image';
                    } elseif (!in_array($ex, $allowedExtensions)) {
                        $showfrom = 'doc';
                    }

                    if ($event_id == 'new') {

                        $item = array(
                            'shop_id' => $shop_id,
                            'shop_event_name' => $image_name,
                            'event_image' => in_array($ex, $allowedExtensions) ? $new_filename
                                    : '',
                            'event_doc' => !in_array($ex, $allowedExtensions) ? $new_filename
                                    : '',
                            'showfrom' => $showfrom,
                            'status' => 1
                        );

                        $create_args = array(
                            'table' => 'zselex_shop_events',
                            'element' => $item,
                            'Id' => 'shop_event_id'
                        );
                        // Create the event
                        $result      = ModUtil::apiFunc('ZSELEX', 'admin',
                                'createElement', $create_args);
                    } else {
                        $event_item = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                                $getargs    = array(
                                'table' => 'zselex_shop_events',
                                'where' => "shop_event_id=$event_id"
                        ));
                        $item       = array(
                            'shop_event_id' => $event_id,
                            'shop_id' => $shop_id,
                            'shop_event_name' => $image_name,
                            'event_image' => in_array($ex, $allowedExtensions) ? $new_filename
                                    : '',
                            'event_doc' => !in_array($ex, $allowedExtensions) ? $new_filename
                                    : '',
                            'showfrom' => $showfrom,
                            'status' => 1
                        );

                        $updateargs = array(
                            'table' => 'zselex_shop_events',
                            'IdValue' => $event_id,
                            'IdName' => 'shop_event_id',
                            'element' => $item
                        );

                        $result = ModUtil::apiFunc('ZSELEX', 'admin',
                                'updateElement', $updateargs);
                    }

                    if ($result) {
                        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin',
                                'getOwner',
                                $args      = array(
                                'shop_id' => $shop_id
                        ));
                        if ($event_id == 'new') {
                            $serviceupdatearg = array(
                                'user_id' => $user_id,
                                'type' => 'eventservice',
                                'shop_id' => $shop_id
                            );
                            $serviceavailed   = ModUtil::apiFunc('ZSELEX',
                                    'admin', 'updateServiceUsed',
                                    $serviceupdatearg);
                        } else {

                            if (is_file('zselexdata/'.$ownerName.'/events/fullsize/'.$event_item ['event_image'])) {
                                unlink('zselexdata/'.$ownerName.'/events/fullsize/'.$event_item ['event_image']);
                            }
                            if (is_file('zselexdata/'.$ownerName.'/events/medium/'.$event_item ['event_image'])) {
                                unlink('zselexdata/'.$ownerName.'/events/medium/'.$event_item ['event_image']);
                            }
                            if (is_file('zselexdata/'.$ownerName.'/events/thumb/'.$event_item ['event_image'])) {
                                unlink('zselexdata/'.$ownerName.'/events/thumb/'.$event_item ['event_image']);
                            }

                            if (is_file('zselexdata/'.$ownerName.'/events/thumb/'.$event_item ['event_doc'])) {
                                unlink('zselexdata/'.$ownerName.'/events/thumb/'.$event_item ['event_doc']);
                            }
                        }

                        $args_del_extra_employee = array(
                            'ownername' => $ownerName,
                            'shop_id' => $shop_id
                        );
                        // ZSELEX_Controller_Admin::deleteExtraEmployeeServices($args_del_extra_employee);
                    }

                    // }
                    // }
                }

                // echo "fullpath :" . $fullPath; exit;
                // ZSELEX_Util::ajaxOutput(json_encode(array('name' => basename($fullPath), 'size' => $currByte, 'status' => 'uploaded', 'servicecount' => $servicecount, 'msg' => ($servicecount < 1) ? $this->__("Your service limit is over for this service") : '', 'info' => 'File/chunk uploaded')));
                ZSELEX_Util::ajaxOutput(json_encode(array(
                    'name' => $new_filename,
                    'size' => $currByte,
                    'status' => 'uploaded',
                    'servicecount' => $exceed,
                    'msg' => ($servicecount < 1) ? $this->__("Your service limit is over for this service")
                            : '',
                    'info' => 'File/chunk uploaded'
                )));
                // ZSELEX_Util::ajaxOutput($output);
            }
        }
    }

    public function showImagePopUp($args)
    {
        $data            = '';
        $image_id        = $_REQUEST ['image_id'];
        $view            = Zikula_View::getInstance($this->name);
        $minisite_images = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                $getargs         = array(
                'table' => 'zselex_files',
                'where' => "file_id=$image_id"
        ));
        $view->assign('image', $minisite_images);
        $output_test     = $view->fetch('ajax/imagepopup.tpl');
        $data .= new Zikula_Response_Ajax_Plain($output_test);
        $output ["data"] = $data;
        AjaxUtil::output($output);
    }

    public function showEmployeePopUp($args)
    {
        $repo   = $this->entityManager->getRepository('ZSELEX_Entity_Employee');
        $data   = '';
        $emp_id = $_REQUEST ['emp_id'];
        $view   = Zikula_View::getInstance($this->name);
        /*
         * $employee = ModUtil::apiFunc('ZSELEX', 'user', 'get', $getargs = array(
         * 'table' => 'zselex_shop_employees',
         * 'where' => "emp_id=$emp_id",
         * ));
         */

        $employee        = $repo->get(array(
            'entity' => 'ZSELEX_Entity_Employee',
            'where' => array(
                'a.emp_id' => $emp_id
            )
        ));
        $view->assign('employee', $employee);
        $output_test     = $view->fetch('ajax/employeepopup.tpl');
        $data .= new Zikula_Response_Ajax_Plain($output_test);
        $output ["data"] = $data;
        AjaxUtil::output($output);
    }

    public function showAnouncementPopUp($args)
    {
        $data            = '';
        $shop_id         = $_REQUEST ['shop_id'];
        $view            = Zikula_View::getInstance($this->name);
        $anouncement     = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                $getargs         = array(
                'table' => 'zselex_shop_announcement',
                'where' => "shop_id=$shop_id"
        ));
        $view->assign('item', $anouncement);
        $output_test     = $view->fetch('ajax/anouncementpopup.tpl');
        $data .= new Zikula_Response_Ajax_Plain($output_test);
        $output ["data"] = $data;
        AjaxUtil::output($output);
    }

    public function loadDenMap($args)
    {
        $data = '';

        $view = Zikula_View::getInstance($this->name);

        $output_test     = $view->fetch('ajax/map.tpl');
        $data .= new Zikula_Response_Ajax_Plain($output_test);
        $output ["data"] = $data;
        AjaxUtil::output($output);
    }

    public function showEventPopUp($args)
    {
        $view     = Zikula_View::getInstance($this->name);
        $data     = '';
        $shop_id  = $_REQUEST ['shop_id'];
        $event_id = $_REQUEST ['event_id'];
        $view->assign('event_id', $event_id);



        // echo "<pre>"; print_r($shoptype); echo "</pre>";
        $shoptype = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getShopType(array(
            'shop_id' => $shop_id
        ));
        $shoptype = $shoptype ['shoptype'];

        $shopinfoArgs = array(
            'entity' => 'ZSELEX_Entity_Shop',
            'fields' => array(
                'a.shop_id',
                'a.email',
                'a.address',
                'a.telephone'
            ),
            'where' => array(
                'a.shop_id' => $shop_id
            )
        );
        $shop_info    = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->get($shopinfoArgs);

        $shop_item = array(
            'shop_email' => $shop_info ['email'],
            'shop_address' => $shop_info ['address'],
            'shop_phone' => $shop_info ['telephone']
        );
        //echo "<pre>"; print_r($shop_info); echo "</pre>"; exit;
        $view->assign('shop_item', $shop_item);
        if ($event_id != 'new') {

            $eventArgs = array(
                'entity' => 'ZSELEX_Entity_Event',
                'fields' => array(
                    'a.shop_event_id',
                    'a.shop_event_name',
                    'a.shop_event_shortdescription',
                    'a.shop_event_description',
                    'a.shop_event_keywords',
                    'a.shop_event_startdate',
                    'a.shop_event_enddate',
                    'a.activation_date',
                    'a.shop_event_starthour',
                    'a.event_image',
                    'a.image_height',
                    'a.image_width',
                    'a.event_doc',
                    'a.product_id',
                    'a.showfrom',
                    'a.price',
                    'a.email',
                    'a.shop_event_endhour',
                    'a.shop_event_startminute',
                    'a.shop_event_endminute',
                    'a.exclusive',
                    'a.phone',
                    'a.event_link',
                    'a.open_new',
                    'a.call_link_directly',
                    'a.shop_event_venue',
                    'a.status',
                    'a.contact_name'
                ),
                'where' => array(
                    'a.shop_event_id' => $event_id
                )
            );
            $event     = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->get($eventArgs);
        } else {
            $event = array();
        }
        //  echo "<pre>"; print_r($event); echo "</pre>"; exit;
        $view->assign('item', $event);
        $article_args    = array(
            'shop_id' => $shop_id,
            'type' => 'createarticle',
            'disablecheck' => true
        );
        $article_service = $this->servicePermission($article_args);
        $view->assign('article_service', $article_service);

        $product_args    = array(
            'shop_id' => $shop_id,
            'type' => 'addproducts',
            'disablecheck' => true
        );
        $product_service = $this->servicePermission($product_args);
        $view->assign('product_service', $product_service);

        $image_args    = array(
            'shop_id' => $shop_id,
            'type' => 'minisiteimages',
            'disablecheck' => true
        );
        $image_service = $this->servicePermission($image_args);
        $view->assign('image_service', $image_service);

        $articles = ModUtil::apiFunc('ZSELEX', 'admin', 'getShopArticleEvents',
                $args     = array(
                'shop_id' => $shop_id
        ));
        $view->assign('articles', $articles);

        //  echo "comes here";  exit;
        //  echo "shop type :" . $shoptype; exit;
        if ($shoptype == 'iSHOP') {

            // echo "iSHOP";  exit;
            $finalproduct = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements',
                    $args         = array(
                    'table' => 'zselex_products',
                    'where' => "shop_id=$shop_id AND prd_status=1",
                    'orderBy' => 'product_name ASC',
                    'useJoins' => ''
            ));

            // echo "<pre>"; print_r($finalproduct); echo "</pre>";
        } elseif ($shoptype == 'zSHOP') {
            $zenargs = array(
                'table' => 'zselex_zenshop',
                'fields' => array(
                    "*"
                ),
                'where' => array(
                    "shop_id=$shop_id"
                )
            );
            $zencart = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow', $zenargs);

            // echo "<pre>"; print_r($zencart); echo "</pre>"; exit;
            $zenproducts = ModUtil::apiFunc('ZSELEX', 'admin',
                    'getZenCartProducts',
                    array(
                    'shop' => $zencart,
                    'shop_id' => $shop_id
            ));
            // echo "<pre>"; print_r($zenproducts); echo "</pre>"; exit;
            foreach ($zenproducts as $key => $val) {
                $finalproduct [] = array(
                    'product_id' => $val ['products_id'],
                    'product_name' => $val ['products_name']
                );
            }
        }

        //   echo "<pre>"; print_r($zenproducts); echo "</pre>"; exit;

        $serviceargs     = array(
            'shop_id' => $shop_id,
            'type' => 'exclusiveevent',
            'disablecheck' => true
        );
        $exclusive_event = $this->servicePermission($serviceargs);
        // $exclusive_event = $servicePermission['perm'];

        $view->assign('products', $finalproduct);
        $view->assign('exclusive_event', $exclusive_event);

        $output_test         = $view->fetch('ajax/eventpopup.tpl');
        $data .= new Zikula_Response_Ajax_Plain($output_test);
        $output ["data"]     = $data;
        $output ["event_id"] = $event_id;
        AjaxUtil::output($output);
    }

    public function setProductStaus($args)
    {
        $product_id = FormUtil::getPassedValue('product_id', null, 'REQUEST');
        $status     = FormUtil::getPassedValue('status', null, 'REQUEST');

        $item       = array(
            'product_id' => $product_id,
            'prd_status' => $status
        );
        $updateargs = array(
            'table' => 'zselex_products',
            'IdValue' => $product_id,
            'IdName' => 'product_id',
            'element' => $item
        );
        // echo "<pre>"; print_r($updateargs); echo "</pre>"; exit;
        $result     = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement',
                $updateargs);
    }

    function getCityForProductAd()
    {
        // exit;
        $country_id = FormUtil::getPassedValue("country_id");
        $region_id  = FormUtil::getPassedValue("region_id");
        $append     = '';

        if (!empty($country_id)) {
            $append .= " AND country_id=$country_id";
        }
        if (!empty($region_id)) {
            $append .= " AND region_id=$region_id";
        }

        $sql = "SELECT city_id,city_name FROM zselex_city WHERE city_id!=''".$append;

        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);
        $sValues   = $results->fetchAll();
        $count     = count($sValues);
        $output    = '';

        $output .= "<select id=city-combo name='city_id'>";
        if ($count != 0) {
            $output .= "<option value='0'>".$this->__('search city')."</option>";
            foreach ($sValues as $row) {
                $output .= "<option value='".$row ['city_id']."'>".strtoupper($row [city_name])."</option>";
            }
        } else {
            $output .= "<option value=''>".$this->__('search city')."</option>";
        }
        $output .= "</select>";

        $data ["cities"] = $output;
        AjaxUtil::output($data);

        // ZSELEX_Util::ajaxOutput($output);
    }

    public function showAdCost($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $ad_level     = $_REQUEST ['level'];
        $repo         = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        $return_array = array();
        /*
         * $get = ModUtil::apiFunc('ZSELEX', 'user', 'get', $getargs =
         * array('table' => 'zselex_advertise_price',
         * 'where' => "identifier='" . $ad_level . "'"));
         */

        $get             = $repo->get(array(
            'entity' => 'ZSELEX_Entity_AdvertisePrice',
            'fields' => array(
                'a.price'
            ),
            'where' => array(
                'a.identifier' => $ad_level
            )
        ));
        // return $return_array;
        $data ["adcost"] = round($get ['price'])." ".$this->__('points');
        AjaxUtil::output($data);
    }

    public function sliderImage1()
    {
        // exit;
        $country_id  = FormUtil::getPassedValue('country_id', null, 'REQUEST');
        $region_id   = FormUtil::getPassedValue('region_id', null, 'REQUEST');
        $city_id     = FormUtil::getPassedValue('city_id', null, 'REQUEST');
        $area_id     = FormUtil::getPassedValue('area_id', null, 'REQUEST');
        $category_id = FormUtil::getPassedValue('category_id', null, 'REQUEST');
        $branch_id   = FormUtil::getPassedValue('branch_id', null, 'REQUEST');
        $adtype      = FormUtil::getPassedValue('adtype', null, 'REQUEST');
        $amount      = FormUtil::getPassedValue('amount', null, 'REQUEST');
        $limit       = FormUtil::getPassedValue('limit', null, 'REQUEST');
        $search      = FormUtil::getPassedValue('hsearch', null, 'REQUEST');
        $search      = ($search == $this->__('search for...') || $search == $this->__('search'))
                ? '' : $search;

        $old_event_id = FormUtil::getPassedValue('old_event_id', null, 'REQUEST');
        $event_count  = FormUtil::getPassedValue('event_count', null, 'REQUEST');

        if (!empty($country_id)) { // COUNTRY
            $append .= " AND a.country_id=$country_id";
        }

        if (!empty($region_id)) { // REGION
            $append .= " AND a.region_id=$region_id";
        }

        if (!empty($city_id)) { // CITY
            $append .= " AND a.city_id=$city_id";
        }

        if (!empty($area_id)) { // AREA
            $append .= " AND a.area_id=$area_id";
        }

        if (!empty($shop_id)) { // SHOP
            $append .= " AND a.shop_id=$shop_id";
        }
        if (!empty($category_id)) {
            // $append .= " AND a.cat_id=$category_id";
            $append .= " AND a.shop_id IN(SELECT shop_id FROM zselex_shop_to_category WHERE category_id=$category_id) ";
        }

        if (!empty($branch_id)) {
            $append .= " AND a.branch_id=$branch_id";
        }

        if (!empty($search)) {
            $append .= " AND (b.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE '%".DataUtil::formatForStore($search)."%') OR b.shop_id IN (SELECT shop_id FROM zselex_shop WHERE shop_name LIKE '%".DataUtil::formatForStore($search)."%'))";
        }

        $sql       = "SELECT a.shop_id
                        FROM zselex_shop a , zselex_shop_events b
                        WHERE a.shop_id IS NOT NULL
                        AND a.shop_id = b.shop_id
                        AND a.status='1' ".$append." GROUP BY b.shop_id";
        // echo $sql; exit;
        $query     = DBUtil::executeSQL($sql);
        // $shop_count = $query->rowCount();
        $all_shops = $query->fetchAll();

        foreach ($all_shops as $key => $val) {
            $shop_id      = $val ['shop_id'];
            $serviceExist = ModUtil::apiFunc('ZSELEX', 'admin',
                    'serviceExistBlock',
                    array(
                    'shop_id' => $shop_id,
                    'type' => 'exclusiveevent'
            ));
            // echo $shop_id . "-" . $serviceExist . '<br>';
            if ($serviceExist) {
                $shopIdArr [] = $val ['shop_id'];
            }
        }
        // exit;
        $shop_count = sizeof($shopIdArr);
        if ($shop_count < 1) {
            $output ['noresult'] = 1;
            AjaxUtil::output($output);
        }
        $shopIdArr = array_unique($shopIdArr);
        $shopIds   = implode(',', $shopIdArr);

        // echo "<pre>"; print_r($shopIdArr); echo "</pre>";
        // echo $shopIds;
        $data = '';
        // $output_test = "helloo world";

        $extra = '';
        if (!empty($old_event_id) && $event_count > 1) {
            // $extra = " AND shop_event_id!='" . $old_event_id . "'";
        }

        $sql = "SELECT event_image , shop_id , shop_event_id
          FROM zselex_shop_events
          WHERE shop_id IN($shopIds) AND (shop_event_startdate >=CURDATE() OR shop_event_startdate <=CURDATE() ) AND shop_event_enddate >=CURDATE() AND status=1 AND exclusive=1
          AND (activation_date<=CURDATE() OR UNIX_TIMESTAMP(activation_date) = 0 OR activation_date IS NULL) AND image_height >=300 AND image_width >=900
          $extra ORDER BY RAND() LIMIT 0 , 20";

        // echo $sql;
        $output ['sql'] = $sql;
        $query          = DBUtil::executeSQL($sql);
        $count          = $query->rowCount();
        if ($count < 1) {
            $output ['noresult'] = 1;
            AjaxUtil::output($output);
        }
        $res              = $query->fetchAll();
        $output ['image'] = '';
        $i                = 1;
        $class            = '';
        foreach ($res as $k => $v) {
            if ($i == 1) {
                $class = "active";
            } else {
                $class = '';
            }
            $ownerName                 = ModUtil::apiFunc('ZSELEX', 'admin',
                    'getOwner',
                    $args                      = array(
                    'shop_id' => $v [shop_id]
            ));
            $_SESSION ['old_event_id'] = $v ['shop_event_id'];
            $output ['old_event_id']   = $v ['shop_event_id'];
            $output ['event_count']    = $count;
            $baseUrl                   = pnGetBaseURL();
            $event_url                 = $baseUrl.ModUtil::url('ZSELEX', 'user',
                    'viewevent',
                    array(
                    'shop_id' => $v ['shop_id'],
                    'eventId' => $v ['shop_event_id']
            ));
            $output ['show']           = "<a href=$event_url><img src='".$baseUrl."zselexdata/$ownerName/events/fullsize/$res[event_image]'></a>";
            $output ['image'] .= "<img style='display:block' class='exclEvntImg $class' src='".$baseUrl."zselexdata/$ownerName/events/fullsize/$v[event_image]' onClick=window.location.href='$event_url' alt=''>";
            $i ++;
        }
        // echo "<pre>"; print_r($output['image']); echo "</pre>";
        AjaxUtil::output($output);
    }

    public function sliderImage2()
    {
        // exit;
        // echo "<pre>"; print_r($_REQUEST); echo "</pre>";
        $repo        = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        $country_id  = FormUtil::getPassedValue('country_id', null, 'REQUEST');
        $region_id   = FormUtil::getPassedValue('region_id', null, 'REQUEST');
        $city_id     = FormUtil::getPassedValue('city_id', null, 'REQUEST');
        $area_id     = FormUtil::getPassedValue('area_id', null, 'REQUEST');
        $category_id = FormUtil::getPassedValue('category_id', null, 'REQUEST');
        $branch_id   = FormUtil::getPassedValue('branch_id', null, 'REQUEST');
        $adtype      = FormUtil::getPassedValue('adtype', null, 'REQUEST');
        $amount      = FormUtil::getPassedValue('amount', null, 'REQUEST');
        $limit       = FormUtil::getPassedValue('limit', null, 'REQUEST');
        $search      = FormUtil::getPassedValue('hsearch', null, 'REQUEST');
        $search      = ($search == $this->__('search for...') || $search == $this->__('search'))
                ? '' : $search;

        $old_event_id = FormUtil::getPassedValue('old_event_id', null, 'REQUEST');
        $event_count  = FormUtil::getPassedValue('event_count', null, 'REQUEST');

        if (!empty($country_id)) { // COUNTRY
            $append .= " AND a.country_id=$country_id";
            $evntArgs ['where'] ['b.country'] = $country_id;
        }

        if (!empty($region_id)) { // REGION
            $append .= " AND a.region_id=$region_id";
            $evntArgs ['where'] ['b.region'] = $region_id;
        }

        if (!empty($city_id)) { // CITY
            $append .= " AND a.city_id=$city_id";
            $evntArgs ['where'] ['b.city'] = $city_id;
        }

        if (!empty($area_id)) { // AREA
            $append .= " AND a.area_id=$area_id";
            $evntArgs ['where'] ['b.area'] = $area_id;
        }

        if (!empty($shop_id)) { // SHOP
            $append .= " AND a.shop_id=$shop_id";
            $evntArgs ['where'] ['a.shop'] = $shop_id;
        }
        /*
         * if (!empty($category_id)) {
         * //$append .= " AND a.cat_id=$category_id";
         * //$append .= " AND a.shop_id IN(SELECT shop_id FROM zselex_shop_to_category WHERE category_id=$category_id) ";
         * // $evntArgs['subquery'][] = " AND a.shop IN()";
         * $evntArgs['joins'][] = 'INNER JOIN b.shop_to_category c';
         * $evntArgs['where']['c.category_id'] = $category_id;
         * }
         */

        if (!empty($branch_id)) {
            $append .= " AND a.branch_id=$branch_id";
            $evntArgs ['where'] ['b.branch_id'] = $branch_id;
        }

        if (!empty($search)) {
            // $append .= " AND (b.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE '%" . DataUtil::formatForStore($search) . "%') OR b.shop_id IN (SELECT shop_id FROM zselex_shop WHERE shop_name LIKE '%" . DataUtil::formatForStore($search) . "%'))";
            /*
             * $evntArgs['subquery'][] = "b.shop_id IN
             * (SELECT d.shop_id FROM ZSELEX_Entity_Keyword c
             * JOIN c.shop d
             * WHERE c.keyword LIKE :srchwrd)
             * OR b.shop_id IN
             * (SELECT e.shop_id FROM ZSELEX_Entity_Shop e WHERE e.shop_name LIKE :srchwrd)";
             */
            $evntArgs ['subquery'] [] = "b.shop_id IN
               (SELECT d.shop_id FROM ZSELEX_Entity_Keyword c 
               JOIN c.shop d
               WHERE c.keyword LIKE :srchwrd OR MATCH (c.keyword) AGAINST (:srchwrd))
               OR b.shop_id IN 
              (SELECT e.shop_id FROM ZSELEX_Entity_Shop e WHERE e.shop_name LIKE :srchwrd)";

            $evntArgs ['setParams'] ['srchwrd'] = '%'.DataUtil::formatForStore($search).'%';
            // $evntArgs['joins'][] = 'INNER JOIN b.shop_keywords d';
            // $evntArgs['like']['d.keyword'] = "%" . DataUtil::formatForStore($search) . "%";
            // $evntArgs['like']['b.keyword'] = "%" . DataUtil::formatForStore($search) . "%";
        }

        /*
         * $sql = "SELECT a.shop_id
         * FROM zselex_shop a , zselex_shop_events b
         * WHERE a.shop_id IS NOT NULL
         * AND a.shop_id = b.shop_id
         * AND a.status='1' " . $append . " GROUP BY b.shop_id";
         * // echo $sql; exit;
         * $query = DBUtil::executeSQL($sql);
         * // $shop_count = $query->rowCount();
         * $all_shops = $query->fetchAll();
         */

        $evntArgs ['entity']   = 'ZSELEX_Entity_Event';
        // $evntArgs['joins'] = array('JOIN a.shop b');
        $evntArgs ['joins'] [] = 'JOIN a.shop b';
        if (!empty($category_id)) {
            $evntArgs ['joins'] []                = 'INNER JOIN b.shop_to_category f';
            $evntArgs ['where'] ['f.category_id'] = $category_id;
        }
        $evntArgs ['fields']  = array(
            'b.shop_id'
        );
        $evntArgs ['groupby'] = 'b.shop_id';
        $getEvnts             = $repo->getAll($evntArgs);
        $all_shops            = $getEvnts;

        foreach ($all_shops as $key => $val) {
            $shop_id      = $val ['shop_id'];
            $serviceExist = ModUtil::apiFunc('ZSELEX', 'admin',
                    'serviceExistBlock',
                    array(
                    'shop_id' => $shop_id,
                    'type' => 'exclusiveevent'
            ));
            // echo $shop_id . "-" . $serviceExist . '<br>';
            if ($serviceExist) {
                $shopIdArr [] = $val ['shop_id'];
            }
        }
        // exit;
        $shop_count = sizeof($shopIdArr);
        if ($shop_count < 1) {
            $output ['noresult'] = 1;
            AjaxUtil::output($output);
        }
        $shopIdArr = array_unique($shopIdArr);
        $shopIds   = implode(',', $shopIdArr);

        // echo "<pre>"; print_r($shopIdArr); echo "</pre>";
        // echo $shopIds;
        $data = '';
        // $output_test = "helloo world";

        $extra = '';
        if (!empty($old_event_id) && $event_count > 1) {
            // $extra = " AND shop_event_id!='" . $old_event_id . "'";
        }

        /*
         * $sql = "SELECT event_image , shop_id , shop_event_id
         * FROM zselex_shop_events
         * WHERE shop_id IN($shopIds) AND (shop_event_startdate >=CURDATE() OR shop_event_startdate <=CURDATE() ) AND shop_event_enddate >=CURDATE() AND status=1 AND exclusive=1
         * AND (activation_date<=CURDATE() OR UNIX_TIMESTAMP(activation_date) = 0 OR activation_date IS NULL) AND image_height >=300 AND image_width >=900
         * $extra ORDER BY RAND() LIMIT 0 , 20";
         */

        // echo $sql;
        $res = $repo->fetchAll(array(
            'entity' => 'ZSELEX_Entity_Event',
            'fields' => array(
                'a.event_image',
                'b.shop_id',
                'a.shop_event_id'
            ),
            'joins' => array(
                'JOIN a.shop b'
            ),
            'startlimit' => 0,
            'offset' => 20,
            'where' => "a.shop IN($shopIds) AND  (a.shop_event_startdate >=CURRENT_DATE() OR a.shop_event_startdate <=CURRENT_DATE() ) AND a.shop_event_enddate >=CURRENT_DATE() AND a.status=1 AND a.exclusive=1
                     AND (a.activation_date<=CURRENT_DATE() OR a.activation_date = '' OR a.activation_date IS NULL) AND a.image_height >=300 AND a.image_width >=900"
        ));

        // echo "<pre>"; print_r($res); echo "</pre>";
        // $output['sql'] = $sql;
        // $query = DBUtil::executeSQL($sql);
        // $count = $query->rowCount();
        $count = count($res);
        if ($count < 1) {
            $output ['noresult'] = 1;
            AjaxUtil::output($output);
        }
        // $res = $query->fetchAll();
        $output ['image'] = '';
        $i                = 1;
        $class            = '';
        foreach ($res as $k => $v) {
            if ($i == 1) {
                $class = "active";
            } else {
                $class = '';
            }
            /*
             * $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner', $args = array(
             * 'shop_id' => $v[shop_id]
             * ));
             */
            $_SESSION ['old_event_id'] = $v ['shop_event_id'];
            $output ['old_event_id']   = $v ['shop_event_id'];
            $output ['event_count']    = $count;
            $baseUrl                   = pnGetBaseURL();
            $event_url                 = $baseUrl.ModUtil::url('ZSELEX', 'user',
                    'viewevent',
                    array(
                    'shop_id' => $v ['shop_id'],
                    'eventId' => $v ['shop_event_id']
            ));
            $output ['show']           = "<a href=$event_url><img src='".$baseUrl."zselexdata/$v[shop_id]/events/fullsize/$res[event_image]'></a>";
            $image                     = $baseUrl."zselexdata/$v[shop_id]/events/fullsize/$v[event_image]";
            if (file_exists("zselexdata/$v[shop_id]/events/fullsize/$v[event_image]")) {
                $output ['image'] .= "<img style='display:block' class='exclEvntImg $class' src='".$image."' onClick=window.location.href='$event_url' alt=''>";
            }
            $i ++;
        }
        // echo "<pre>"; print_r($output['image']); echo "</pre>";
        AjaxUtil::output($output);
    }

    public function sliderImage3()
    {
        // exit;
        // echo "<pre>"; print_r($_REQUEST); echo "</pre>";
        $repo        = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        $country_id  = FormUtil::getPassedValue('country_id', null, 'REQUEST');
        $region_id   = FormUtil::getPassedValue('region_id', null, 'REQUEST');
        $city_id     = FormUtil::getPassedValue('city_id', null, 'REQUEST');
        $area_id     = FormUtil::getPassedValue('area_id', null, 'REQUEST');
        $category_id = FormUtil::getPassedValue('category_id', null, 'REQUEST');
        $branch_id   = FormUtil::getPassedValue('branch_id', null, 'REQUEST');
        $adtype      = FormUtil::getPassedValue('adtype', null, 'REQUEST');
        $amount      = FormUtil::getPassedValue('amount', null, 'REQUEST');
        $limit       = FormUtil::getPassedValue('limit', null, 'REQUEST');
        $search      = FormUtil::getPassedValue('hsearch', null, 'REQUEST');
        $search      = ($search == $this->__('search for...') || $search == $this->__('search'))
                ? '' : $search;

        $old_event_id = FormUtil::getPassedValue('old_event_id', null, 'REQUEST');
        $event_count  = FormUtil::getPassedValue('event_count', null, 'REQUEST');

        $setParams = array();

        if (!empty($country_id)) { // COUNTRY
            // $append .= " AND a.country_id=$country_id";
            $append .= " AND a.country_id=$country_id";
        }

        if (!empty($region_id)) { // REGION
            $append .= " AND a.region_id=$region_id";
        }

        if (!empty($city_id)) { // CITY
            $append .= " AND a.city_id=$city_id";
        }

        if (!empty($area_id)) { // AREA
            $append .= " AND a.area_id=$area_id";
        }

        if (!empty($shop_id)) { // SHOP
            $append .= " AND a.shop_id=$shop_id";
        }
        if (!empty($category_id)) {
            // $append .= " AND a.cat_id=$category_id";
            $append .= " AND a.shop_id IN(SELECT shop_id FROM zselex_shop_to_category WHERE category_id=$category_id) ";
        }

        if (!empty($branch_id)) {
            $append .= " AND a.branch_id=$branch_id";
        }

        if (!empty($search)) {
            // $append .= " AND (a.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE '%" . DataUtil::formatForStore($search) . "%') OR a.shop_id IN (SELECT shop_id FROM zselex_shop WHERE shop_name LIKE '%" . DataUtil::formatForStore($search) . "%'))";
            $append .= " AND (a.shop_name LIKE :search OR MATCH (a.shop_name) AGAINST (:search2) OR a.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE :search OR MATCH (keyword) AGAINST (:search2)))";
            $setParams += array(
                'search' => '%'.DataUtil::formatForStore($search).'%',
                'search2' => DataUtil::formatForStore($search)
            );
        }

        $event_shops = $this->entityManager->getRepository('ZSELEX_Entity_Event')->getUpcomingEventShops(array(
            'append' => $append,
            'limit' => 20,
            'setParams' => $setParams
        ));

        $all_shops = $event_shops;

        foreach ($all_shops as $key => $val) {
            $shop_id      = $val ['shop_id'];
            $serviceExist = ModUtil::apiFunc('ZSELEX', 'admin',
                    'serviceExistBlock',
                    array(
                    'shop_id' => $shop_id,
                    'type' => 'exclusiveevent'
            ));
            // echo $shop_id . "-" . $serviceExist . '<br>';
            if ($serviceExist) {
                $shopIdArr [] = $val ['shop_id'];
            }
        }
        // exit;
        $shop_count = sizeof($shopIdArr);
        if ($shop_count < 1) {
            $output ['noresult'] = 1;
            AjaxUtil::output($output);
        }
        $shopIdArr = array_unique($shopIdArr);
        $shopIds   = implode(',', $shopIdArr);

        // echo "<pre>"; print_r($shopIdArr); echo "</pre>";
        // echo $shopIds;
        $data = '';
        // $output_test = "helloo world";

        $extra = '';
        if (!empty($old_event_id) && $event_count > 1) {
            // $extra = " AND shop_event_id!='" . $old_event_id . "'";
        }

        // echo $sql;
        $res = $repo->fetchAll(array(
            'entity' => 'ZSELEX_Entity_Event',
            'fields' => array(
                'a.event_image',
                'b.shop_id',
                'a.shop_event_id'
            ),
            'joins' => array(
                'JOIN a.shop b'
            ),
            'startlimit' => 0,
            'offset' => 20,
            'where' => "a.shop IN($shopIds) AND  (a.shop_event_startdate >=CURRENT_DATE() OR a.shop_event_startdate <=CURRENT_DATE() ) AND a.shop_event_enddate >=CURRENT_DATE() AND a.status=1 AND a.exclusive=1
                     AND (a.activation_date<=CURRENT_DATE() OR a.activation_date = '' OR a.activation_date IS NULL) AND a.image_height >=300 AND a.image_width >=900"
        ));

        // echo "<pre>"; print_r($res); echo "</pre>";
        // $output['sql'] = $sql;
        // $query = DBUtil::executeSQL($sql);
        // $count = $query->rowCount();

        $count = count($res);
        // echo "Count :" . $count;
        if ($count < 1) {
            $output ['noresult'] = 1;
            AjaxUtil::output($output);
        }
        // $res = $query->fetchAll();
        $output ['image'] = '';
        $i                = 1;
        $class            = '';
        foreach ($res as $k => $v) {
            if ($i == 1) {
                $class = "active";
            } else {
                $class = '';
            }
            /*
             * $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner', $args = array(
             * 'shop_id' => $v[shop_id]
             * ));
             */
            $_SESSION ['old_event_id'] = $v ['shop_event_id'];
            $output ['old_event_id']   = $v ['shop_event_id'];
            $output ['event_count']    = $count;
            $baseUrl                   = pnGetBaseURL();
            $event_url                 = $baseUrl.ModUtil::url('ZSELEX', 'user',
                    'viewevent',
                    array(
                    'shop_id' => $v ['shop_id'],
                    'eventId' => $v ['shop_event_id']
            ));
            $output ['show']           = "<a href=$event_url><img src='".$baseUrl."zselexdata/$v[shop_id]/events/fullsize/$res[event_image]'></a>";
            $image                     = $baseUrl."zselexdata/$v[shop_id]/events/fullsize/$v[event_image]";
            if (file_exists("zselexdata/$v[shop_id]/events/fullsize/$v[event_image]")) {
                $output ['image'] .= "<img style='display:block' class='exclEvntImg $class' src='".$image."' onClick=window.location.href='$event_url' alt=''>";
            }
            $i ++;
        }
        // echo "<pre>"; print_r($output['image']); echo "</pre>";
        AjaxUtil::output($output);
    }

    /**
     * Exclusive event slider
     *
     * @param GET
     * @return Ajax Response
     */
    public function sliderImage()
    {
        // exit;
        // echo "<pre>"; print_r($_REQUEST); echo "</pre>";
        $repo        = $this->entityManager->getRepository('ZSELEX_Entity_Event');
        $country_id  = FormUtil::getPassedValue('country_id', null, 'REQUEST');
        $region_id   = FormUtil::getPassedValue('region_id', null, 'REQUEST');
        $city_id     = FormUtil::getPassedValue('city_id', null, 'REQUEST');
        $area_id     = FormUtil::getPassedValue('area_id', null, 'REQUEST');
        $category_id = FormUtil::getPassedValue('category_id', null, 'REQUEST');
        $branch_id   = FormUtil::getPassedValue('branch_id', null, 'REQUEST');
        $adtype      = FormUtil::getPassedValue('adtype', null, 'REQUEST');
        $amount      = FormUtil::getPassedValue('amount', null, 'REQUEST');
        $limit       = FormUtil::getPassedValue('limit', null, 'REQUEST');
        $search      = FormUtil::getPassedValue('hsearch', null, 'REQUEST');
        $search      = ($search == $this->__('search for...') || $search == $this->__('search'))
                ? '' : $search;

        $old_event_id = FormUtil::getPassedValue('old_event_id', null, 'REQUEST');
        $event_count  = FormUtil::getPassedValue('event_count', null, 'REQUEST');

        $aff_id = $_REQUEST ['aff_id'];
        // echo "<pre>"; print_r($aff_id); echo "</pre>"; exit;

        $setParams = array();
        $append1   = '';
        $joins     = '';
        if (!empty($country_id)) { // COUNTRY
            // $append .= " AND a.country_id=$country_id";
            $append .= " AND a.country_id=$country_id";
        }

        if (!empty($region_id)) { // REGION
            $append .= " AND a.region_id=$region_id";
        }

        if (!empty($city_id)) { // CITY
            $append .= " AND a.city_id=$city_id";
        }

        if (!empty($area_id)) { // AREA
            $append .= " AND a.area_id=$area_id";
        }

        if (!empty($shop_id)) { // SHOP
            $append .= " AND a.shop_id=$shop_id";
        }
        if (!empty($category_id)) {
            // $append .= " AND a.cat_id=$category_id";
            // $append .= " AND a.shop_id IN(SELECT shop_id FROM zselex_shop_to_category WHERE category_id=$category_id) ";

            $append .= " AND cat.category_id=:category ";
            $joins .= " INNER JOIN zselex_shop_to_category cat ON cat.shop_id=a.shop_id ";
            $setParams += array(
                'category' => $branch_id
            );
        }

        if (!empty($branch_id)) {
            // $append .= " AND a.branch_id=$branch_id";
            // $append1 .= " AND a.branch=$branch_id";
            $append .= " AND branch.branch_id=:branch_id ";
            $joins .= " INNER JOIN zselex_shop_to_branch branch ON branch.shop_id=a.shop_id ";
            $setParams += array(
                'branch_id' => $branch_id
            );
        }

        if (!empty($aff_id)) {
            $affQuery = ZSELEX_Api_Base_User::_affiliateQuery($aff_id, 'a');

            // $append .= " AND a.aff_id IN (:aff_ids)";
            // $append .= " AND (" . $affQuery . ")";
            $append .= " AND (".$affQuery ['query'].")";
            $setParams += $affQuery ['setParams'];
        }

        if (!empty($search)) {
            // $append .= " AND (a.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE '%" . DataUtil::formatForStore($search) . "%') OR a.shop_id IN (SELECT shop_id FROM zselex_shop WHERE shop_name LIKE '%" . DataUtil::formatForStore($search) . "%'))";
            $append .= " AND (a.shop_name LIKE :search OR MATCH (a.shop_name) AGAINST (:search2) OR a.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE :search OR MATCH (keyword) AGAINST (:search2)))";
            $setParams += array(
                'search' => '%'.DataUtil::formatForStore($search).'%',
                'search2' => DataUtil::formatForStore($search)
            );
        }

        // echo "<pre>"; print_r($shopIdArr); echo "</pre>";
        // echo $shopIds;
        $data = '';
        // $output_test = "helloo world";

        $extra = '';
        /*
         * if (!empty($old_event_id) && $event_count > 1) {
         * // $extra = " AND shop_event_id!='" . $old_event_id . "'";
         * }
         */

        // echo $sql;
        $today = date("Y-m-d");

        $event_args = array(
            'append' => $append,
            'setParams' => $setParams,
            'joins' => $joins
        );
        $res        = $repo->getExclusiveEvents($event_args);

        // echo "<pre>"; print_r($res); echo "</pre>";
        // $output['sql'] = $sql;
        // $query = DBUtil::executeSQL($sql);
        // $count = $query->rowCount();

        $count = count($res);
        // echo "Count :" . $count;
        if ($count < 1) {
            $output ['noresult'] = 1;
            AjaxUtil::output($output);
        }
        // $res = $query->fetchAll();
        $output ['image']  = '';
        $output ['image2'] = '';
        $i                 = 1;
        $class             = '';
        foreach ($res as $k => $v) {
            if ($i == 1) {
                $class = "active";
            } else {
                $class = '';
            }
            /*
             * $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner', $args = array(
             * 'shop_id' => $v[shop_id]
             * ));
             */
            $_SESSION ['old_event_id'] = $v ['shop_event_id'];
            $output ['old_event_id']   = $v ['shop_event_id'];
            $output ['event_count']    = $count;
            $baseUrl                   = pnGetBaseURL();
            $target                    = '';
            if (trim($v ['event_link']) != '' && $v ['call_link_directly'] == true) {
                $event_url = $v ['event_link'];
                if ($v ['open_new'] == true) {
                    $target = 'target=_blank';
                }
            } else {
                $event_url = $baseUrl.ModUtil::url('ZSELEX', 'user',
                        'viewevent',
                        array(
                        'shop_id' => $v ['shop_id'],
                        'eventId' => $v ['shop_event_id']
                ));
            }
            $currentTheme    = System::getVar('Default_Theme');
            // echo $currentTheme; exit;
            // $target = 'target=_blank';
            $output ['show'] = "<a href=$event_url><img src='".$baseUrl."zselexdata/$v[shop_id]/events/fullsize/$res[event_image]'></a>";
            $image           = $baseUrl."zselexdata/$v[shop_id]/events/fullsize/$v[event_image]";
            if (file_exists("zselexdata/$v[shop_id]/events/fullsize/$v[event_image]")) {
                // $output['image'] .= "<img style='display:block' class='exclEvntImg $class' src='" . $image . "' onClick=window.location.href='$event_url' alt=''>";
                $output ['image'] .= "<a $target href='$event_url'><img style='display:block' class='exclEvntImg $class' src='".$image."'  alt=''></a>";
                $output ['image2'] .= "<li><a $target href='$event_url'><img  src='".$image."'  alt=''></a></li>";
            }
            $i ++;
        }
        // echo "<pre>"; print_r($output['image']); echo "</pre>";
        AjaxUtil::output($output);
    }

    function _affiliateQuery($aff_id, $alias)
    {
        $affQuery   = '';
        $affIdArray = explode(',', $aff_id);
        if (!empty($affIdArray)) {
            foreach ($affIdArray as $k => $a) {
                if (is_numeric($a)) {
                    $affArray [] = "$alias.aff_id=$a";
                }
            }
        }
        if (!empty($affArray)) {
            $affQuery = implode(' OR ', $affArray);
        }

        return $affQuery;
    }

    public function addToCart()
    {
        $pid              = $_REQUEST ['pid'];
        $shop_id          = $_REQUEST ['shop_id'];
        $options          = $_REQUEST ['shop_id'];
        $product          = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                $getargs          = array(
                'table' => 'zselex_products',
                'where' => "product_id=$pid"
                )
                // 'fields' => array('id', 'quantity', 'availed')
        );
        $user_id          = UserUtil::getVar('uid');
        $get_products     = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                $getargs          = array(
                'table' => 'zselex_cart',
                'where' => "user_id=$user_id"
                )
                // 'fields' => array('id', 'quantity', 'availed')
        );
        // echo "<pre>"; print_r($get_products); echo "</pre>"; exit;
        $content          = $get_products ['cart_content'];
        $cart_unserialize = unserialize($content);
        if (@array_key_exists($shop_id, $cart_unserialize)) {
            // echo "key exist";
            foreach ($cart_unserialize [$shop_id] as $key => $value) {
                $existing_products [] = $value ['PRODUCTID'];
            }
            if (in_array($pid, $existing_products)) {
                // $exist++;
                // LogUtil::registerStatus($this->__('This Product Is Already In Your Cart.'));
                $output ['pid']   = $pid;
                $output ['exist'] = 1;
                AjaxUtil::output($output);
            } else {
                $cart_unserialize [$shop_id] []       = array(
                    'PRODUCTID' => $product ['product_id'],
                    'PRODUCTNAME' => $product ['product_name'],
                    'SHOPID' => $shop_id,
                    'QUANTITY' => 1,
                    'DESCRIPTION' => $product ['prd_description'],
                    'IMAGE' => $product ['prd_image'],
                    'REALPRICE' => $product ['prd_price'],
                    'FINALPRICE' => $product ['prd_price']
                );
                $_SESSION ['user_cart'] [$shop_id] [] = array(
                    'PRODUCTID' => $product ['product_id'],
                    'PRODUCTNAME' => $product ['product_name'],
                    'SHOPID' => $shop_id,
                    'QUANTITY' => 1,
                    'DESCRIPTION' => $product ['prd_description'],
                    'IMAGE' => $product ['prd_image'],
                    'REALPRICE' => $product ['prd_price'],
                    'FINALPRICE' => $product ['prd_price']
                );
            }
            // $this->update_cart($cart_unserialize);
            // ZSELEX_Controller_User::update_cart($cart_unserialize);
        } else {
            $cart_unserialize [$shop_id] []       = array(
                'PRODUCTID' => $product ['product_id'],
                'PRODUCTNAME' => $product ['product_name'],
                'SHOPID' => $shop_id,
                'QUANTITY' => 1,
                'DESCRIPTION' => $product ['prd_description'],
                'IMAGE' => $product ['prd_image'],
                'REALPRICE' => $product ['prd_price'],
                'FINALPRICE' => $product ['prd_price']
            );
            $_SESSION ['user_cart'] [$shop_id] [] = array(
                'PRODUCTID' => $product ['product_id'],
                'PRODUCTNAME' => $product ['product_name'],
                'SHOPID' => $shop_id,
                'QUANTITY' => 1,
                'DESCRIPTION' => $product ['prd_description'],
                'IMAGE' => $product ['prd_image'],
                'REALPRICE' => $product ['prd_price'],
                'FINALPRICE' => $product ['prd_price']
            );
        }

        ZSELEX_Controller_User::update_cart($cart_unserialize);
        if (!empty($cart_unserialize)) {
            ZSELEX_Controller_User::validatecart($cart_unserialize);
        }

        $theme_path            = "themes/".System::getVar('Default_Theme');
        $cart_info             = ZSELEX_Controller_User::carttotal();
        $curr_args             = array(
            'amount' => $cart_info ['total'],
            'currency_symbol' => '',
            'decimal_point' => ',',
            'thousands_sep' => '.',
            'precision' => '2'
        );
        $cart_total            = ModUtil::apiFunc('ZSELEX', 'user',
                'number2currency', $curr_args);
        $cart_count            = $cart_info ['count'];
        $output ['pid']        = $pid;
        $output ['cart_total'] = $cart_total;
        $output ['cart_count'] = $cart_count;
        $output ['theme_path'] = $theme_path;
        AjaxUtil::output($output);
    }

    public function addToCartGuest()
    {
        $pid     = $_REQUEST ['pid'];
        $shop_id = $_REQUEST ['shop_id'];
        $product = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                $getargs = array(
                'table' => 'zselex_products',
                'where' => "product_id=$pid"
                )
                // 'fields' => array('id', 'quantity', 'availed')
        );
        if ($pid) {
            if (isset($_COOKIE ['cart'] [$shop_id])) { // checking the existing products in cart
                foreach ($_COOKIE ['cart'] [$shop_id] as $val) {
                    // echo "<pre>"; print_r( json_decode($val, true)); echo "</pre>";
                    if (in_array($pid, json_decode($val, true))) {
                        $exist ++;
                    } else {
                        
                    }
                }
            }

            if ($exist < 1) { // if product not exist in cart the set it to session and cookie
                $_SESSION ['cart'] [$shop_id] [] = array(
                    'PRODUCTID' => $product ['product_id'],
                    'PRODUCTNAME' => $product ['product_name'],
                    'SHOPID' => $shop_id,
                    'QUANTITY' => 1,
                    'DESCRIPTION' => $product ['prd_description'],
                    'IMAGE' => $product ['prd_image'],
                    'REALPRICE' => $product ['prd_price'],
                    'FINALPRICE' => $product ['prd_price']
                );

                $array = array(
                    'PRODUCTID' => $product ['product_id'],
                    'PRODUCTNAME' => $product ['product_name'],
                    'SHOPID' => $shop_id,
                    'QUANTITY' => 1,
                    'DESCRIPTION' => $product ['prd_description'],
                    'IMAGE' => $product ['prd_image'],
                    'REALPRICE' => $product ['prd_price'],
                    'FINALPRICE' => $product ['prd_price']
                );

                // echo "<pre>"; print_r($array); echo "</pre>"; exit;
                // echo "<pre>"; print_r($_SESSION['cart']); echo "</pre>"; exit;

                $last_keys = '';
                if (!isset($_COOKIE ['cart'] [$shop_id])) { // if cookie is not set then set the first key as zero
                    $last_keys = 0;
                } else { // if cookie is available then increment the key by adding the last key of the previous cookie to it.
                    $last_key  = key(array_slice($_COOKIE ['cart'] [$shop_id],
                            - 1, 1, TRUE));
                    $last_keys = $last_key + 1;
                }
                // echo "<pre>"; print_r(json_encode($_SESSION['cart'][$last_key])); echo "</pre>";

                $cookieEncode = json_encode($array);
                setcookie("cart[$shop_id][$last_keys]", $cookieEncode,
                    time() + 604800, '/');
            } else { // product already exist.
                $output ['pid']   = $pid;
                $output ['exist'] = 1;
                AjaxUtil::output($output);
            }
        }
        $sessionCount = 0;
        $cookieCount  = 0;
        if (count($_COOKIE ['cart']) > 0) {
            $c = 0;
            foreach ($_COOKIE ['cart'] as $key => $val) {
                foreach ($_COOKIE ['cart'] [$key] as $key1 => $val1) {
                    $finalCookie [$key] [$key1] = json_decode($val1, true);

                    $c ++;
                }
            }
        }

        if (count($_SESSION ['cart']) > 0) {
            $s = 0;
            foreach ($_SESSION ['cart'] as $key => $val) {
                foreach ($_SESSION ['cart'] [$key] as $key1 => $val1) {

                    $s ++;
                }
            }
        }

        $sessionCount = $s;
        $cookieCount  = $c;
        $products     = array();

        if ($sessionCount > $cookieCount) { // if session is set first then render session products to cart page otherwise render cookie products
            $products = $_SESSION ['cart'];
        } else {
            $products = $finalCookie;
        }

        // echo "<pre>"; print_r($products); echo "</pre>";
        $products              = @array_filter($products);
        ZSELEX_Controller_User::validatecart($products); // VALIDATE THE PRODUCTS IN CART
        $theme_path            = "themes/".System::getVar('Default_Theme');
        $cart_info             = ZSELEX_Controller_User::carttotal();
        $curr_args             = array(
            'amount' => $cart_info ['total'],
            'currency_symbol' => '',
            'decimal_point' => ',',
            'thousands_sep' => '.',
            'precision' => '2'
        );
        $cart_total            = ModUtil::apiFunc('ZSELEX', 'user',
                'number2currency', $curr_args);
        $cart_count            = $cart_info ['count'];
        $output ['pid']        = $pid;
        $output ['cart_total'] = $cart_total;
        $output ['cart_count'] = $cart_count;
        $output ['theme_path'] = $theme_path;
        AjaxUtil::output($output);
    }

    function getProductOption1($ags)
    {
        $option_id                  = $_REQUEST ['option_id'];
        $keys                       = $_REQUEST ['key'] + 1;
        $product_options            = $this->entityManager->getRepository('ZSELEX_Entity_ProductOption')->getProductOption(array(
            'option_id' => $option_id
        ));
        // $option_value = unserialize($product_options['option_value']);
        $product_options ['values'] = ModUtil::apiFunc('ZSELEX', 'user',
                'getAll',
                $args                       = array(
                'table' => 'zselex_product_options_values',
                'where' => "option_id=$option_id",
                'orderby' => "sort_order ASC"
        ));

        $list = '<div class="innerCsub">
           <div><b>'.$product_options ['option_name'].'</b></div>
               <table  bgcolor=black cellspacing=1 cellpadding=1>
               <th bgcolor=white>#</th>
               <th bgcolor=white>'.$this->__('values').'</th>
               <th bgcolor=white>'.$this->__('price').'</th>  
               <th bgcolor=white>'.$this->__('qty').'</th>
              ';
        // $list .= "<label for='option_values'></label><select class='moption_values' id=option_values name=formElements[prod_cats][] multiple='multiple'>";

        foreach ($product_options ['values'] as $key => $option_val) {

            /*
             * $list .= "<tr bgcolor=white>
             * <td><input type='checkbox' name='formElements[option][$option_id][val][]' value=$option_val[option_value_id]></td>
             * <td>$option_val[option_value]</td>
             * <td><input type='text' name='formElements[option][$option_id][price][$option_val[option_value_id]]' size='3' autocomplete='off'></td>
             * </tr>";
             */
            $list .= "<tr bgcolor=white>
                    <td>
                    <input type='hidden' name='formElements[option][$keys][type]' value='new'>
                    <input type='hidden' name='formElements[option][$keys][option_id]' value=$option_id>
                    <input type='checkbox' name='formElements[option][$keys][val][]' value=$option_val[option_value_id]>
                    </td>
                    <td>$option_val[option_value]</td>
                    <td><input type='text' name='formElements[option][$keys][price][$option_val[option_value_id]]' size='3' autocomplete='off'></td>
                    <td><input type='text' name='formElements[option][$keys][qty][$option_val[option_value_id]]' size='3' autocomplete='off'></td>
                    
                    </tr>";
        }
        $list .= '</table><span><a class="remove_option" href="#">  <b>'.$this->__('remove').'</b></a></span><br></div>';

        $output ['data'] = $list;
        AjaxUtil::output($output);
    }

    function getProductOption($ags)
    {
        $option_id  = $_REQUEST ['option_id'];
        $product_id = $_REQUEST ['product_id'];

        $count      = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount',
                $count_args = array(
                'table' => 'zselex_product_to_options',
                'where' => "option_id=$option_id AND product_id=$product_id"
        ));
        if ($count) {
            // $output['exist'] = 1;
            // AjaxUtil::output($output);
        }
        $keys            = $_REQUEST ['key'] + 1;
        // echo "here"; exit;
        $product_options = $this->entityManager->getRepository('ZSELEX_Entity_ProductOption')->getProductOption(array(
            'option_id' => $option_id
        ));

        // $option_value = unserialize($product_options['option_value']);
        $product_options ['values'] = ModUtil::apiFunc('ZSELEX', 'user',
                'getAll',
                $args                       = array(
                'table' => 'zselex_product_options_values',
                'where' => "option_id=$option_id",
                'orderby' => "sort_order ASC"
        ));

        if ($product_options ['parent_option_id'] < 1) {

            $list = '<div class="innerCsub">
           
           <div><b>'.$product_options ['option_name'].'</b></div>
               <table  class="optionTable" cellspacing=1 cellpadding=1>
              
               <th bgcolor=white>'.$this->__('values').'</th>
               <th bgcolor=white>'.$this->__('price').'</th>  
               <th bgcolor=white>'.$this->__('qty').'</th>
              ';
            // $list .= "<label for='option_values'></label><select class='moption_values' id=option_values name=formElements[prod_cats][] multiple='multiple'>";

            foreach ($product_options ['values'] as $key => $option_val) {

                /*
                 * $list .= "<tr bgcolor=white>
                 * <td><input type='checkbox' name='formElements[option][$option_id][val][]' value=$option_val[option_value_id]></td>
                 * <td>$option_val[option_value]</td>
                 * <td><input type='text' name='formElements[option][$option_id][price][$option_val[option_value_id]]' size='3' autocomplete='off'></td>
                 * </tr>";
                 */
                $list .= "<tr bgcolor=white>
                    <input type='hidden' name='formElements[option][$keys][type]' value='new'>
                    <input id='selOptionId' class='selOptionId' type='hidden' name='formElements[option][$keys][option_id]' value=$option_id>
                    <input type='hidden' name='formElements[option][$keys][hval][]' value=$option_val[option_value_id]>
                    <td>$option_val[option_value]</td>
                    <td>
                    <input type='text' name='formElements[option][$keys][price][$option_val[option_value_id]]' size='3' autocomplete='off'>
                    </td>
                    <td><input type='text' name='formElements[option][$keys][qty][$option_val[option_value_id]]' size='3' autocomplete='off'></td>
                    
                    </tr>";
            }
            $list .= '</table><span><a class="remove_option" href="#">  <b>'.$this->__('remove').'</b></a></span><br></div>';
        } else { // linked options
            /*
             * $parent_product_options = ModUtil::apiFunc('ZSELEX', 'user', 'getAll', $args = array(
             * 'table' => 'zselex_product_options_values',
             * 'where' => "option_id=$product_options[parent_option_id]",
             * 'orderby' => "sort_order ASC"
             * ));
             */

            $parent_product_options = ModUtil::apiFunc('ZSELEX', 'user',
                    'selectJoinArray',
                    $args                   = array(
                    'table' => 'zselex_product_options_values a',
                    'fields' => array(
                        'a.*,b.option_name'
                    ),
                    'where' => array(
                        "a.option_id=$product_options[parent_option_id]"
                    ),
                    'joins' => array(
                        "LEFT JOIN zselex_product_options b ON b.option_id=a.option_id"
                    ),
                    'orderby' => 'a.sort_order ASC'
            ));

            // echo "<pre>"; print_r($parent_product_options); echo "</pre>"; exit;

            $list = '<div class="innerCsub" style="max-width:600px; overflow:auto">
           
           <div><b>'.$product_options ['option_name'].'</b></div>
               <table  class="optionTable" >
               
               <th bgcolor=white>'.$this->__('values').'</th>
               
              ';

            foreach ($parent_product_options as $pKey => $pOption_val) {
                $list .= "<th bgcolor=white>
                        $pOption_val[option_name]:
                            <br>
                        $pOption_val[option_value]
                            </th>";
            }

            foreach ($product_options ['values'] as $key => $option_val) {
                $list .= "<tr bgcolor=white>
                    <input type='hidden' name='formElements[option][$keys][linked]' value='1'>
                    <input type='hidden' name='formElements[option][$keys][type]' value='new'>
                    <input id='selOptionId' class='selOptionId' type='hidden' name='formElements[option][$keys][option_id]' value=$option_id>
                    <input type='hidden' name='formElements[option][$keys][parent_option_id]' value=$product_options[parent_option_id]>
                    <input type='hidden' name='formElements[option][$keys][hval][]' value=$option_val[option_value_id]>
                   
                    <td align='center'>$option_val[option_value]</td>";
                foreach ($parent_product_options as $pKey => $pOption_val) {
                    $list .= "<td>
                            <table>
                            <tr>
                      
                         <td>
                        ".$this->__('price')." : <input type='text' name='formElements[option][$keys][price][$option_val[option_value_id]][$pOption_val[option_value_id]]' size='3' autocomplete='off'>
                           </td>
                        <td> 
                        ".$this->__('qty')."  : <input type='text' name='formElements[option][$keys][qty][$option_val[option_value_id]][$pOption_val[option_value_id]]' size='3' autocomplete='off'>
                        </td>
                             </tr>
                             </table>
                              </td>
                             ";
                }
                $list .= "</tr>";
            }
            $list .= '</table><span><a class="remove_option" href="#">  <b>'.$this->__('remove').'</b></a></span><br></div>';
        }

        $output ['data']      = $list;
        $output ['option_id'] = $option_id;
        AjaxUtil::output($output);

        // End of file ajax..
    }
}
?>
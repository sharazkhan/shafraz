<?php

/*
 * Ajax Controller
 */

class ZSELEX_Controller_Base_Ajax extends Zikula_Controller_AbstractAjax {

    protected $current_theme;
    protected $current_theme_path;
    public $shop_ids = array();

    function initialize() {
        $this->current_theme = System::getVar('Default_Theme');
        $this->current_theme_path = "Themes/" . System::getVar('Default_Theme');
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        if (!empty($shop_id)) {
            $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner', $args = array(
                        'shop_id' => $shop_id
            ));

            $getShop = ModUtil::apiFunc('ZSELEX', 'user', 'get', $args = array(
                        'table' => 'zselex_shop',
                        'fields' => array(
                            'shop_id',
                            'shop_name'
                        )
            ));
            // echo $getShop['shop_name'];
        }
        $area_id = FormUtil::getPassedValue("area_id");
        $city_id = FormUtil::getPassedValue("city_id");
        $region_id = FormUtil::getPassedValue("region_id");
        $country_id = FormUtil::getPassedValue("country_id");
        $category_id = FormUtil::getPassedValue('category_id', null, 'REQUEST');
        $branch_id = FormUtil::getPassedValue('branch_id', null, 'REQUEST');

        // echo "comes here"; exit;
        /*
         * echo "country :" . $country_id;
         * echo "region :" . $region_id;
         * echo "city :" . $city_id;
         * echo "area :" . $area_id;
         *
         */
        // exit;
    }

    function getShopsListAll() { // CALLED DROP DOWN
        $shop_id = FormUtil::getPassedValue("shop_id");
        $area_id = FormUtil::getPassedValue("area_id");
        $city_id = FormUtil::getPassedValue("city_id");
        $region_id = FormUtil::getPassedValue("region_id");
        $country_id = FormUtil::getPassedValue("country_id");
        $shop_cookie = $_COOKIE ['shop_cookie'];
        // echo $shop_cookie;
        // ZSELEX_Util::ajaxOutput($area_id);

        $qry = '';
        if (!empty($country_id)) { // COUNTRY
            $qry .= " AND country_id=$country_id";
        }

        if (!empty($region_id)) { // REGION
            $qry .= " AND region_id=$region_id";
        }

        if (!empty($city_id)) { // CITY
            $qry .= " AND city_id=$city_id";
        }

        if (!empty($area_id)) { // AREA
            $qry .= " AND area_id=$area_id";
        }

        if (!empty($shop_id)) { // SHOP
            $qry .= " AND shop_id=$shop_id";
        }

        $sql = "SELECT shop_id,shop_name FROM zselex_shop
                WHERE status=1 AND shop_id !='' " . $qry;

        // ZSELEX_Util::ajaxOutput($sql);

        $query = DBUtil::executeSQL($sql);
        $sValues = $query->fetchAll();

        $count = count($sValues);
        $output = '';

        $output .= "<select id=shop-combo name=shop>";
        if ($count != 0) {
            $selected = "";

            $output .= "<option value='0'>" . $this->__('search shop') . "</option>";
            foreach ($sValues as $row) {
                if ($shop_cookie == $row ['shop_id']) {
                    $selected = "selected";
                }
                ;
                $output .= "<option value='" . $row ['shop_id'] . "' $selected>" . $row [shop_name] . "</option>";
            }
        } else {
            $output .= "<option value=''>" . $this->__('search shop') . "</option>";
        }

        $output .= "</select>";

        ZSELEX_Util::ajaxOutput($output);
    }

    function getRegionList() {

        // exit;
        $country_id = FormUtil::getPassedValue("country_id");

        $sql = "SELECT * FROM zselex_region a , zselex_parent b
                WHERE a.region_id=b.childId AND b.childType='REGION' AND  b.parentType='COUNTRY' 
                AND b.parentId=$country_id";

        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
        $sValues = $results->fetchAll();

        $count = count($sValues);
        $output = '';

        $output .= "<select id=region name=region onChange=getCityList(this.value),getRegionShopList(this.value),getShopDetails(document.getElementById('hshop').value)>";
        if ($count != 0) {
            $output .= "<option value='0'>" . $this->__('-select region-') . "</option>";
            foreach ($sValues as $row) {
                $output .= "<option value='" . $row ['region_id'] . "'>$row[region_name]</option>";
            }
        } else {
            $output .= "<option value=''>" . $this->__('-no regions-') . "</option>";
        }

        $output .= "</select>";

        ZSELEX_Util::ajaxOutput($output);
    }

    function getCityList() {
        $region_id = FormUtil::getPassedValue("region_id");
        $country_id = FormUtil::getPassedValue("country_id");

        if ($region_id > 0) {
            $sql = "SELECT * FROM zselex_city a , zselex_parent b
                WHERE a.city_id=b.childId AND b.childType='CITY' AND  b.parentType='REGION' 
                AND b.parentId=$region_id";
        } elseif ($region_id < 0 && $country_id > 0) {

            $sql = "SELECT * FROM zselex_city WHERE city_id IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='REGION'
                 AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='REGION' AND parentType='COUNTRY' AND parentId=$country_id))
                OR
                   city_id IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='COUNTRY' AND parentId=$country_id)
                ";
        } else {

            $sql = "SELECT * FROM zselex_city";
        }

        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
        $sValues = $results->fetchAll();

        $count = count($sValues);
        $output = '';

        $output .= "<select id=city name=city onChange=getShopFrntend(this.value),getShopDetails(document.getElementById('hshop').value)>";
        if ($count != 0) {
            $output .= "<option value='0'>" . $this->__('-select city-') . "</option>";
            foreach ($sValues as $row) {

                $output .= "<option value='" . $row ['city_id'] . "'>$row[city_name]</option>";
            }
        } else {
            $output .= "<option value=''>" . $this->__('-no cities-') . "</option>";
        }
        $output .= "</select>";

        ZSELEX_Util::ajaxOutput($output);
    }

    public function getEvents() {
        // exit;
        $level = FormUtil::getPassedValue("level");
        $shop_id = FormUtil::getPassedValue("shop_id");
        $country_id = FormUtil::getPassedValue("country_id");
        $region_id = FormUtil::getPassedValue("region_id");
        $city_id = FormUtil::getPassedValue("city_id");
        $area_id = FormUtil::getPassedValue("area_id");
        $branch_id = FormUtil::getPassedValue("branch_id");
        $category_id = FormUtil::getPassedValue("category_id");
        $search = FormUtil::getPassedValue("hsearch");
        $search = ($search == $this->__('search for...') || $search == $this->__('search')) ? '' : $search;
        $eventlimit = FormUtil::getPassedValue("eventlimit");

        $append = '';
        if (!empty($eventlimit)) {

            $limit = $eventlimit;
            $limitquery = "LIMIT 0 , $eventlimit";
        } else {
            $limit = "2";
            $limitquery = "LIMIT 0 , 2";
        }

        $searchquery = '';
        if (($shop_id > 0 || !empty($shop_id)) || ($region_id > 0 || !empty($region_id)) || ($city_id > 0 || !empty($city_id)) || ($country_id > 0 || !empty($country_id)) || ($area_id > 0 || !empty($area_id)) || ($search > 0 || !empty($search)) || ($category_id > 0 || !empty($category_id)) || ($branch_id > 0 || !empty($branch_id) || !empty($eventdate))) {
            $eventdateqry = '';
            if (!empty($eventdate)) {
                $eventdateqry = " AND shop_event_startdate='" . $eventdate . "'";
            }

            $output = '';

            $items = array(
                'id' => $shop_id
            );
            // $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
            $where = '';

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

                $append .= " AND a.shop_name LIKE '%" . DataUtil::formatForStore($search) . "%'
                             OR a.shop_id IN (SELECT shop_id FROM zselex_advertise WHERE keywords LIKE '%" . DataUtil::formatForStore($search) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_files WHERE keywords LIKE '%" . DataUtil::formatForStore($search) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_gallery WHERE keywords LIKE '%" . DataUtil::formatForStore($search) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_pdf WHERE keywords LIKE '%" . DataUtil::formatForStore($search) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_dotd WHERE keywords LIKE '%" . DataUtil::formatForStore($search) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_products WHERE keywords LIKE '%" . DataUtil::formatForStore($search) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_news WHERE news_id IN 
                             (SELECT sid FROM news WHERE title LIKE '%" . DataUtil::formatForStore($search) . "%' OR hometext LIKE '%" . DataUtil::formatForStore($search) . "%' OR bodytext LIKE '%" . DataUtil::formatForStore($search) . "%' OR urltitle LIKE '%" . DataUtil::formatForStore($search) . "%'))
            ";
            }

            $sql = "SELECT a.shop_id FROM zselex_shop a
                     WHERE a.shop_id IS NOT NULL AND a.status='1'  $append";
            // echo $sql;
            $query = DBUtil::executeSQL($sql);
            $result = $query->fetchAll();

            $count = count($result);
        } else {

            $count = 0;
        }
        if ($count > 0) {
            foreach ($result as $shopid) {
                $shop_idarray [] = $shopid ['shop_id'];
            }

            $shop_ids = implode(",", $shop_idarray);
            // foreach ($result as $shop) {
            $sqlevents = "SELECT *, shop_event_id , shop_id , shop_event_startdate , LEFT(shop_event_name, 20) AS event_name  ,   LEFT(shop_event_description, 20) AS event_description  ,   LEFT(shop_event_shortdescription, 20) AS event_shortdescription , DAYNAME(shop_event_startdate) as dateformated FROM zselex_shop_events
                     WHERE shop_id IS NOT NULL AND shop_event_id IS NOT NULL AND status='1' AND shop_id IN($shop_ids) " . " " . $eventdateqry . " ORDER BY RAND()" . " " . $limitquery;
            // echo $sql1;
            $query1 = DBUtil::executeSQL($sqlevents);
            $events = $query1->fetchAll();
            $eventcount = count($events);
            shuffle($events);

            $totalcountsql = "SELECT * FROM zselex_shop_events
                     WHERE shop_id IS NOT NULL AND shop_event_id IS NOT NULL AND status='1' AND shop_id IN($shop_ids) " . " " . $eventdateqry;
            $totalcountquery = DBUtil::executeSQL($totalcountsql);
            $totalCount = $totalcountquery->rowCount();

            $i = 1;

            if ($eventcount > 0) {
                foreach ($events as $event) {
                    if ($i == $limit) {
                        // break;
                    }
                    ;
                    $output .= "<div style='border:solid 1px #CCC; padding-top:15px; padding-bottom:5px; padding-left:15px; '>
                          
                      <div><b>" . $this->__('Event Name') . "</b>: " . $event ['event_name'] . "</div>
                          
                      <div><b>" . $this->__(/* Event */ 'Start Date') . "</b>:" . $event ['shop_event_startdate'] . "</div>
                          
                      <div><b>" . $this->__(/* Event */ 'Start Time') . "</b>: " . $event ['shop_event_starthour'] . ":" . $event ['shop_event_startminute'] . "</div>
                       
                      <div><b>" . $this->__(/* Event */ 'End Date') . "</b>: " . $event ['shop_event_enddate'] . "</div>
                          
                      <div><b>" . $this->__(/* Event */ 'End Time') . "</b>: " . $event ['shop_event_endhour'] . ":" . $event ['shop_event_endminute'] . "</div>
                       <div>
                       <a href='" . ModUtil::url('ZSELEX', 'user', 'viewevent', array(
                                'shop_id' => $event ['shop_id'],
                                'eventId' => $event ['shop_event_id']
                            )) . "'><font color=blue>" . $this->__('view details...') . "</font></a>
                      </div>
                   </div>
                  ";

                    // $output .= "<div>
                    // <h3>$event[dateformated] $event[shop_event_startdate]</h3>
                    // &nbsp;&nbsp;$event[event_name]<br>
                    // &nbsp;&nbsp;$event[event_shortdescription]...<br>
                    // &nbsp;&nbsp;$event[event_description]...<br>
                    // &nbsp;&nbsp;<a href='" . ModUtil::url('ZSELEX', 'user', 'viewevent', array('shop_id' => $event['shop_id'], 'eventId' => $event['shop_event_id'])) . "'><font color=blue>view details...</font></a>
                    //
					// <div><br>";
                    $i ++;
                }
                if ($totalCount > $eventcount) {
                    // $output .= '<div style="cursor:pointer" align=right onclick=getMoreEvents()> <font color="blue">more events... </font> </div>';
                }
            } else {
                $output .= "<dt> &nbsp;&nbsp;&nbsp;&nbsp;  " . $this->__('No Events Found') . "  </dt>";
            }
            // }
        } else {

            $output .= "<dt> &nbsp;&nbsp;&nbsp;&nbsp;  " . $this->__('No Events Found') . "  </dt>";
        }

        $output .= '</dl>';
        ZSELEX_Util::ajaxOutput($output);
    }

    public function setSelectionSessions() {
        $shop_id = FormUtil::getPassedValue("shop_id");
        $country_id = FormUtil::getPassedValue("country_id");
        $region_id = FormUtil::getPassedValue("region_id");
        $city_id = FormUtil::getPassedValue("city_id");
        $area_id = FormUtil::getPassedValue("area_id");
        $branch_id = FormUtil::getPassedValue("branch_id");
        $category_id = FormUtil::getPassedValue("category_id");
        $search = FormUtil::getPassedValue("hsearch");

        $_SESSION ['selectionfield'] ['shop_id'] = $shop_id;
        $_SESSION ['selectionfield'] ['country_id'] = $country_id;
        $_SESSION ['selectionfield'] ['region_id'] = $region_id;
        $_SESSION ['selectionfield'] ['city_id'] = $city_id;
        $_SESSION ['selectionfield'] ['area_id'] = $area_id;
        $_SESSION ['selectionfield'] ['category_id'] = $category_id;
        $_SESSION ['selectionfield'] ['branch_id'] = $branch_id;

        $items = array(
            'shop_id' => $shop_id,
            'country_id' => $country_id,
            'region_id' => $region_id,
            'city_id' => $city_id,
            'area_id' => $area_id,
            'branch_id' => $branch_id,
            'category_id' => $category_id,
            'search' => $search
        );

        SessionUtil::setVar('selectionfields', $items);
    }

    public function createDateRangeArray($start, $end) {
        $range = array();

        if (is_string($start) === true)
            $start = strtotime($start);
        if (is_string($end) === true)
            $end = strtotime($end);

        if ($start > $end)
            return $this->createDateRangeArray($end, $start);

        do {
            $range [] = date('Y-m-d', $start);
            $start = strtotime("+ 1 day", $start);
        } while ($start < $end);

        return $range;
    }

    public function getupcommingEvents1() {
        // echo $this->thempath;
        // exit;
        // echo $this->name; exit;
        $view = Zikula_View::getInstance($this->name);
        $level = FormUtil::getPassedValue("level");
        $shop_id = FormUtil::getPassedValue("shop_id");
        $country_id = FormUtil::getPassedValue("country_id");
        // $country_id = '456';
        $region_id = FormUtil::getPassedValue("region_id");
        $city_id = FormUtil::getPassedValue("city_id");
        $area_id = FormUtil::getPassedValue("area_id");
        $branch_id = FormUtil::getPassedValue("branch_id");
        $category_id = FormUtil::getPassedValue("category_id");
        $search = FormUtil::getPassedValue("hsearch");
        $search = ($search == $this->__('search for...') || $search == $this->__('search')) ? '' : $search;
        $eventlimit = FormUtil::getPassedValue("eventlimit");
        $reset = FormUtil::getPassedValue("reset");
        $output = '';
        // echo $reset; exit;
        if (!empty($eventlimit)) {
            $limit = $eventlimit;
            $limitquery = "LIMIT 0 , $eventlimit";
        } else {
            $limit = "2";
            $limitquery = "LIMIT 0 , $limit";
        }
        // echo $limitquery; exit;

        $searchquery = '';
        if (($shop_id > 0 || !empty($shop_id)) || ($region_id > 0 || !empty($region_id)) || ($city_id > 0 || !empty($city_id)) || ($country_id > 0 || !empty($country_id)) || ($area_id > 0 || !empty($area_id)) || ($search > 0 || !empty($search)) || ($category_id > 0 || !empty($category_id)) || ($branch_id > 0 || !empty($branch_id) || !empty($eventdate))) {

            // $eventdateqry = " AND shop_event_startdate>=CURDATE()";
            $eventdateqry = '';
            $output = '';
            $items = array(
                'id' => $shop_id
            );
            // $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
            $where = '';
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
                // $append .= " AND a.cat_id=$category_id";
                $append .= " AND a.shop_id IN(SELECT shop_id FROM zselex_shop_to_category WHERE category_id=$category_id) ";
            }

            if (!empty($branch_id)) {
                $append .= " AND a.branch_id=$branch_id";
            }

            if (!empty($search)) {

                // $append .= " AND (a.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE '%" . DataUtil::formatForStore($search) . "%') OR a.shop_id IN (SELECT shop_id FROM zselex_shop WHERE shop_name LIKE '%" . DataUtil::formatForStore($search) . "%'))";
                $append .= " AND (a.shop_name LIKE '%" . DataUtil::formatForStore($search) . "%' OR MATCH (a.shop_name) AGAINST ('" . DataUtil::formatForStore($search) . "') OR a.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE '%" . DataUtil::formatForStore($search) . "%' OR MATCH (keyword) AGAINST ('" . DataUtil::formatForStore($search) . "')))";
            }

            $sql = "SELECT a.shop_id
                        FROM zselex_shop a
                        WHERE a.shop_id IS NOT NULL
                        AND a.shop_id > 1
                        AND a.status='1' $append";
            // echo $sql; exit;
            $query = DBUtil::executeSQL($sql);
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
            $shopsql = " AND shop_id IN($shop_ids)";
            $shopsql1 = " AND a.shop_id IN($shop_ids)";

            /*
             * if ($reset != 'reset') {
             * $shopquery = $shopsql;
             * } else {
             * $shopquery = "";
             * }
             */

            $shopquery = $shopsql;
            $shopquery1 = $shopsql1;
            // echo $shopquery;

            $minmax = "SELECT MIN( shop_event_startdate ) as mindate , MAX( shop_event_enddate ) as maxdate
                       FROM zselex_shop_events 
                       WHERE shop_event_id IS NOT NULL AND UNIX_TIMESTAMP(shop_event_startdate) != 0 AND UNIX_TIMESTAMP(shop_event_enddate) != 0  AND status='1' " . " " . $shopquery . " " . $limitquery;
            // echo $minmax;
            $q = DBUtil::executeSQL($minmax);
            $e = $q->fetch();

            $mindate = $e ['mindate'];
            $maxdate = $e ['maxdate'];
            $mxdates = array(
                "0" => $maxdate
            );
            $datearray = array();
            // echo "mindate : " . $mindate;
            // echo "maxdate : " . $maxdate;

            $datearray = $this->createDateRangeArray($mindate, $maxdate);
            $datearray = array_merge($datearray, $mxdates);

            $dateCount = count($datearray);

            $todayDate = date("Y-m-d");
            $arrays = array();

            $i = 0;
            $j = 0;
            $countArray = array();
            $eventCount = 0;
            foreach ($datearray as $key => $d) {
                if ($d < $todayDate)
                    continue;
                // echo $d . '<br>';
                if ($i == $limit) {
                    // break;
                }
                // $sql1 = "SELECT * FROM zselex_shop_events WHERE shop_event_enddate BETWEEN curdate() AND '" . $d . "'";
                /*
                 * $sql1 = "SELECT a.shop_event_id , a.shop_id , a.shop_event_name , a.shop_event_shortdescription , a.shop_event_description , a.shop_event_startdate , a.shop_event_starthour , a.shop_event_startminute , a.shop_event_enddate , a.shop_event_endhour , a.shop_event_endminute,a.price,a.email,a.phone,a.shop_event_venue,
                 * b.aff_id , c.aff_image
                 * FROM zselex_shop_events a
                 * LEFT JOIN zselex_shop b ON b.shop_id=a.shop_id
                 * LEFT JOIN zselex_shop_affiliation c ON c.aff_id=b.aff_id
                 * WHERE '" . $d . "' BETWEEN a.shop_event_startdate AND a.shop_event_enddate AND (a.activation_date<=CURDATE() OR UNIX_TIMESTAMP(a.activation_date) = 0 OR a.activation_date IS NULL) AND UNIX_TIMESTAMP(a.shop_event_startdate) != 0 AND UNIX_TIMESTAMP(a.shop_event_enddate) != 0 " . " " . $shopquery1;
                 */

                $sql1 = "SELECT a.shop_event_id , a.shop_id , a.shop_event_name , a.shop_event_shortdescription , a.shop_event_description , a.shop_event_startdate , a.shop_event_starthour , a.shop_event_startminute , a.shop_event_enddate , a.shop_event_endhour , a.shop_event_endminute,a.price,a.email,a.phone,a.shop_event_venue,
                         b.aff_id , c.aff_image
                         FROM zselex_shop_events a 
                         LEFT JOIN zselex_shop b ON b.shop_id=a.shop_id
                         LEFT JOIN zselex_shop_affiliation c ON c.aff_id=b.aff_id
                         WHERE '" . $d . "' BETWEEN a.shop_event_startdate AND a.shop_event_enddate  AND (a.activation_date<=CURDATE() OR UNIX_TIMESTAMP(a.activation_date) = 0) " . " " . $shopquery1;
                // echo $sql1 . '<br>'; exit;
                if (UserUtil::getVar('uid') == '122') {
                    // echo $sql1 . '<br>'; exit;
                }

                $query1 = DBUtil::executeSQL($sql1);
                $events1 = $query1->fetchAll();
                // echo $events1['shop_event_name'] . '<br>';
                $dates = strtotime(date("Y-m-d", strtotime($d)) . " +$j day");
                $headlinedates = date('l dS \o\f F Y', $dates);
                // echo $headlinedates . '<br>';
                $arrays ['dates'] [$d] = $events1;
                if (count($events1) > 0) {
                    // $countArray[] = $events1;
                    $eventCount ++;
                }

                // echo "<pre>"; print_r($events1); echo "</pre>";
                // $datearray[$key]['eventsname'] = 'hiii';
                $i ++;
                $j ++;
            }

            $k = 0;
            $l = 0;
            // $eventcount = count($arrays['dates']);
            // $eventcount = count($countArray);
            $eventcount = $eventCount;
            $view->assign('eventcount', $eventcount);
            $view->assign('limit', $limit);
            // echo "count:".$eventcount;
            if ($eventcount > 0) {
                $todayDate = date("Y-m-d");

                $current_theme = System::getVar('Default_Theme');
                // exit;
                if ($current_theme == 'CityPilot') {
                    // exit;
                    // echo "hellooo";
                    $view = Zikula_View::getInstance($this->name);
                    // $view->assign('product', $product);
                    // $output .= '<div class="DateBlock">';
                    foreach ($arrays ['dates'] as $key => $d1) {
                        if ($k == $limit) {
                            // break; // removed it (i was putting limit for dates here!)
                        }
                        $eventsdate = $key;
                        $dateexplode = explode('-', $eventsdate);
                        $dayname = date_format(date_create($eventsdate), 'l');
                        $dayname = $this->__($dayname);
                        $day = $dateexplode [2];
                        $month = $dateexplode [1];
                        $year = $dateexplode [0];
                        $view->assign('dayname', $dayname);
                        $view->assign('day', $day);
                        $view->assign('month', $month);
                        $view->assign('year', $year);

                        foreach ($arrays ['dates'] [$key] as $key2 => $d2) {
                            if ($l == $limit) {
                                break;
                            }
                            // if (!empty($d2[price])) {
                            if ($d2 [price] > 0) {
                                $price = $d2 [price];
                            } else {
                                $price = $this->__("FREE");
                            }
                            $view->assign('price', $price);
                            $view->assign('event', $d2);
                            $output_event = $view->fetch('ajax/upcommingevents.tpl');
                            $output .= new Zikula_Response_Ajax_Plain($output_event);

                            // echo $l;
                            $l ++;
                        }

                        $k ++;
                    }
                    // $output .= "</div>";
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
                            $output .= "&nbsp;&nbsp;<a href='" . ModUtil::url('ZSELEX', 'user', 'viewevent', array(
                                        'shop_id' => $d2 ['shop_id'],
                                        'eventId' => $d2 ['shop_event_id']
                                    )) . "'>$d2[shop_event_name]</a><br>
                                 <div>";
                        }
                        $output .= "</br>";
                        $k ++;
                    }
                }
                // exit;
                // echo $totalCount;
                if ($eventcount > $limit) {
                    // $output .= '<div style="cursor:pointer" align=right onclick=getMoreEvents()> <font color="blue">more events... </font> </div>';
                    // $output .= "<div class='allevent' style='cursor:pointer' onclick=check(document.getElementById('hcountry').value,document.getElementById('hregion').value,document.getElementById('hcity').value,document.getElementById('harea').value,document.getElementById('hshop').value,document.getElementById('hcategory').value,document.getElementById('hbranch').value,document.getElementById('hsearch').value)> <font color='blue'>" . $this->__('All Events...') . " </font> </div>";
                    // $output .= "<div style='cursor:pointer'><a class='infoclass' id='allevents' href='" . ModUtil::url('ZSELEX', 'info', 'showEvents') . "'>All Events...</a></div>";
                    // $output .= "<br><a href style='cursor:pointer' onclick=check(document.getElementById('hcountry').value,document.getElementById('hregion').value,document.getElementById('hcity').value,document.getElementById('harea').value,document.getElementById('hshop').value,document.getElementById('hcategory').value,document.getElementById('hbranch').value,document.getElementById('hsearch').value)> " . $this->__('All Events...') . "</a>";
                    $evntUrl = pnGetBaseURL() . ModUtil::url('ZSELEX', 'user', 'showEvents');
                    $output .= "<div><a href='javascript:document.evenform.submit()' style='cursor:pointer' >" . $this->__('All Events') . "</a></div>";
                    $output .= "<form id='evenform' name='evenform' action='" . $evntUrl . "' method='post'>
                                  <input type='hidden' id='e_country_id' name='country_id' value=''>
                                  <input type='hidden' id='e_region_id' name='region_id' value=''>
                                  <input type='hidden' id='e_city_id' name='city_id' value=''>
                                  <input type='hidden' id='e_area_id' name='area_id' value=''>
                                  <input type='hidden' id='e_shop_id' name='shop_id' value=''>
                                  <input type='hidden' id='e_category_id' name='category_id' value=''>
                                  <input type='hidden' id='e_branch_id' name='branch_id' value=''>
                                 </form>";
                }
            } else {
                $output .= '<dt> &nbsp;&nbsp;&nbsp;&nbsp;  ' . $this->__('No Events Found') . '  </dt>';
            }
            // }
        } else {
            $output .= '<dt> &nbsp;&nbsp;&nbsp;&nbsp;  ' . $this->__('No Events Found') . '  </dt>';
        }

        $output .= '</dl>';
        // $view->assign('tags', $output);
        // $output_test = $view->fetch('test/test.tpl');
        // return new Zikula_Response_Ajax_Plain($output_test);

        $outputData ["data"] = $output;
        // echo "<pre>"; print_r($countArray); echo "</pre>"; exit;

        AjaxUtil::output($outputData);
    }

    /**
     * Get upcomming events block
     *
     * @return ajax response
     */
    public function getupcommingEvents() {
        // echo $this->thempath; exit;
        // exit;
        // echo $this->name; exit;
        // echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
        $view = Zikula_View::getInstance($this->name);
        $level = FormUtil::getPassedValue("level");
        $shop_id = FormUtil::getPassedValue("shop_id");
        $country_id = FormUtil::getPassedValue("country_id");
        // $country_id = '456';
        $region_id = FormUtil::getPassedValue("region_id");
        $city_id = FormUtil::getPassedValue("city_id");
        $area_id = FormUtil::getPassedValue("area_id");
        $branch_id = FormUtil::getPassedValue("branch_id");
        $category_id = FormUtil::getPassedValue("category_id");
        $search = FormUtil::getPassedValue("hsearch");
        $search = ($search == $this->__('search for...') || $search == $this->__('search')) ? '' : $search;
        $eventlimit = FormUtil::getPassedValue("eventlimit");
        $reset = FormUtil::getPassedValue("reset");
        // $aff_id = $_REQUEST['aff_id'];
        $aff_id = FormUtil::getPassedValue("aff_id");
        /*
         * if (!empty($aff_id)) {
         * $aff_ids = implode(',', $aff_id);
         * }
         */

        $output = '';
        // echo $reset; exit;
        if (!empty($eventlimit)) {
            $limit = $eventlimit;
            $limitquery = "LIMIT 0 , $eventlimit";
        } else {
            $limit = "2";
            $limitquery = "LIMIT 0 , $limit";
        }
        $event_args = array(
            'country_id' => $country_id,
            'region_id' => $region_id,
            'city_id' => $city_id,
            'area_id' => $area_id,
            'shop_id' => $shop_id,
            'category_id' => $category_id,
            'branch_id' => $branch_id,
            'search' => $search,
            'aff_id' => $aff_id,
            'eventlimit' => $eventlimit
        );

        $upcommingevents = ModUtil::apiFunc('ZSELEX', 'user', 'upcommingEvents', $event_args);

        // echo "<pre>"; print_r($upcommingevents); echo "</pre>";

        $totalcount = $upcommingevents ['count'];

        $view->assign('eventlimit', $eventlimit);
        $view->assign('totalcount', $totalcount);
        // $this->view->assign('count', $count);
        $view->assign('admin', $admin);
        // $this->view->assign('add', $add);
        $view->assign('events', $upcommingevents ['events']);
        $output = '';
        $output_event = '';
        $output_event = $view->fetch('ajax/upcommingevents.tpl');
        $output .= new Zikula_Response_Ajax_Plain($output_event);
        // return $output;
        $outputData ["data"] = $output;
        AjaxUtil::output($outputData);
    }

    public function getupcommingEvents2() {
        // echo $this->thempath; exit;
        // exit;
        // echo $this->name; exit;
        // echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
        $view = Zikula_View::getInstance($this->name);
        $level = FormUtil::getPassedValue("level");
        $shop_id = FormUtil::getPassedValue("shop_id");
        $country_id = FormUtil::getPassedValue("country_id");
        // $country_id = '456';
        $region_id = FormUtil::getPassedValue("region_id");
        $city_id = FormUtil::getPassedValue("city_id");
        $area_id = FormUtil::getPassedValue("area_id");
        $branch_id = FormUtil::getPassedValue("branch_id");
        $category_id = FormUtil::getPassedValue("category_id");
        $search = FormUtil::getPassedValue("hsearch");
        $search = ($search == $this->__('search for...') || $search == $this->__('search')) ? '' : $search;
        $eventlimit = FormUtil::getPassedValue("eventlimit");
        $reset = FormUtil::getPassedValue("reset");
        $output = '';
        // echo $reset; exit;
        if (!empty($eventlimit)) {
            $limit = $eventlimit;
            $limitquery = "LIMIT 0 , $eventlimit";
        } else {
            $limit = "2";
            $limitquery = "LIMIT 0 , $limit";
        }
        $event_args = array(
            'country_id' => $country_id,
            'region_id' => $region_id,
            'city_id' => $city_id,
            'area_id' => $area_id,
            'shop_id' => $shop_id,
            'category_id' => $category_id,
            'branch_id' => $branch_id,
            'search' => $search,
            'eventlimit' => $eventlimit
        );

        $upcommingevents = ModUtil::apiFunc('ZSELEX', 'user', 'upcommingEvents', $event_args);

        $totalcount = $upcommingevents ['count'];

        $view->assign('eventlimit', $eventlimit);
        $view->assign('totalcount', $totalcount);
        // $this->view->assign('count', $count);
        $view->assign('admin', $admin);
        // $this->view->assign('add', $add);
        $view->assign('events', $upcommingevents ['events']);
        $output = '';
        $output_event = '';
        $output_event = $view->fetch('ajax/upcommingevents.tpl');
        $output .= new Zikula_Response_Ajax_Plain($output_event);
        // return $output;
        $outputData ["data"] = $output;
        AjaxUtil::output($outputData);
    }

    public function getupcommingEventsReset() {
        $level = FormUtil::getPassedValue("level");
        $shop_id = FormUtil::getPassedValue("shop_id");
        $country_id = FormUtil::getPassedValue("country_id");
        $region_id = FormUtil::getPassedValue("region_id");
        $city_id = FormUtil::getPassedValue("city_id");
        $area_id = FormUtil::getPassedValue("area_id");
        $branch_id = FormUtil::getPassedValue("branch_id");
        $category_id = FormUtil::getPassedValue("category_id");
        $search = FormUtil::getPassedValue("hsearch");
        $search = ($search == $this->__('search for...') || $search == $this->__('search')) ? '' : $search;
        $eventlimit = FormUtil::getPassedValue("eventlimit");
        $reset = FormUtil::getPassedValue("reset");
        // echo $reset; exit;
        if (!empty($eventlimit)) {

            $limit = $eventlimit;
            $limitquery = " LIMIT 0 , $eventlimit";
        } else {
            $limit = "2";
            $limitquery = " LIMIT 0 , 2";
        }

        $searchquery = '';
        $shopquery = '';
        $limitquery = '';
        $shopsql = '';

        /*
         * $sqlevents = "SELECT *, shop_event_id , shop_id , shop_event_startdate , LEFT(shop_event_name, 20) AS event_name , LEFT(shop_event_description, 20) AS event_description , LEFT(shop_event_shortdescription, 20) AS event_shortdescription , DAYNAME(shop_event_startdate) as dateformated
         * FROM zselex_shop_events
         * WHERE shop_event_enddate>=CURDATE() ORDER BY shop_event_startdate ASC " . $limitquery;
         * // echo $sqlevents;
         * $query = DBUtil::executeSQL($sqlevents);
         * $events = $query->fetchAll();
         * $eventcount = count($events);
         *
         *
         * $totalcountsql = "SELECT *, shop_event_id , shop_id , shop_event_startdate , LEFT(shop_event_name, 20) AS event_name , LEFT(shop_event_description, 20) AS event_description , LEFT(shop_event_shortdescription, 20) AS event_shortdescription , DAYNAME(shop_event_startdate) as dateformated
         * FROM zselex_shop_events
         * WHERE shop_event_enddate>=CURDATE()";
         * $totalcountquery = DBUtil::executeSQL($totalcountsql);
         * $totalCount = $totalcountquery->rowCount();
         *
         */

        $minmax = "SELECT MIN( shop_event_startdate ) as mindate , MAX( shop_event_enddate ) as maxdate
                   FROM zselex_shop_events WHERE shop_event_id IS NOT NULL AND status='1' " . " " . $shopquery . " " . $limitquery;

        // echo $minmax; exit;
        $q = DBUtil::executeSQL($minmax);
        $e = $q->fetch();
        $counts = count($e);

        $mindate = $e ['mindate'];
        $maxdate = $e ['maxdate'];
        $datearray = array();
        $mxdates = array(
            "0" => $maxdate
        );

        $datearray = $this->createDateRangeArray($mindate, $maxdate);
        $datearray = array_merge($datearray, $mxdates);

        $todayDate = date("Y-m-d");
        $arrays = array();

        $i = 1;

        if ($counts > 0) {

            $todayDate = date("Y-m-d");

            $i = 1;
            $j = 0;
            foreach ($datearray as $key => $d) {
                if ($d < $todayDate)
                    continue;
                // echo $d . '<br>';
                if ($i == 5)
                    break;

                $sql1 = "SELECT * FROM zselex_shop_events WHERE '" . $d . "' BETWEEN shop_event_startdate AND shop_event_enddate " . " " . $shopquery;
                // echo $sql1;

                $query1 = DBUtil::executeSQL($sql1);
                $events1 = $query1->fetchAll();
                // echo $events1['shop_event_name'] . '<br>';
                $dates = strtotime(date("Y-m-d", strtotime($d)) . " +$j day");
                $headlinedates = date('l dS \o\f F Y', $dates);
                // echo $headlinedates . '<br>';

                $arrays ['dates'] [$d] = $events1;

                // echo "<pre>"; print_r($events1); echo "</pre>";
                // $datearray[$key]['eventsname'] = 'hiii';
                $i ++;
                $j ++;
            }

            $dateExist = count($arrays ['dates']);
            $current_theme = System::getVar('Default_Theme');
            // echo $dateExist; exit;
            // echo "<pre>"; print_r($arrays['dates']); echo "</pre>"; exit;
            if ($dateExist > 0) {

                if ($current_theme == 'CityPilot') {

                    foreach ($arrays ['dates'] as $key => $d1) {
                        if ($i == $limit) {
                            // break;
                        }
                        $eventsdate = $key;
                        $dateexplode = explode('-', $eventsdate);
                        $dayname = date_format(date_create($eventsdate), 'l');
                        $output .= '<div class="DateBlock">';
                        foreach ($arrays ['dates'] [$key] as $key2 => $d2) {
                            $output .= '<a href="#" class="HoverEffet">
                                            <div class="DateBorder">
                                                <div class="Date">
                                                <span class="DateSpan">' . $dateexplode [2] . '/' . $dateexplode [1] . '</span><br /><span  class="YearSpan">' . $dateexplode [0] . '</span><br /><span class="WeekDay">' . $dayname . '</span>
                                                </div>

                                                <div class="DateHead">
                                                    <h5>' . $d2 [shop_event_name] . '</h5>
                                                    <h6>' . $d2 [shop_event_shortdescription] . '</h6>
                                                 </div>
                                                <div class="DatePrice"><p>Pris 160,-</p></div>
                                            </div>
                                      </a>';
                        }

                        $i ++;
                        $output .= "</div>";
                    }
                } else {
                    foreach ($arrays ['dates'] as $key => $d1) {
                        if ($i == $limit) {
                            // break;
                        }
                        ;

                        $output .= "<div>
                               <b>$key</b> </br>";
                        foreach ($arrays ['dates'] [$key] as $key2 => $d2) {
                            $output .= "&nbsp;&nbsp;<a href='" . ModUtil::url('ZSELEX', 'user', 'viewevent', array(
                                        'shop_id' => $d2 ['shop_id'],
                                        'eventId' => $d2 ['shop_event_id']
                                    )) . "'>$d2[shop_event_name]</a><br>
                                 <div>";
                        }
                        $output .= "</br>";
                        $i ++;
                    }
                }
            } else {
                $output .= '<dt> &nbsp;&nbsp;&nbsp;&nbsp;  ' . $this->__('No Events Found') . '  </dt>';
            }
            // if ($totalCount > $eventcount) {
            // $output .= '<div style="cursor:pointer" align=right onclick=getMoreEvents()> <font color="blue">more events... </font> </div>';
            // $output .= "<div style='cursor:pointer' onclick=check(document.getElementById('hcountry').value,document.getElementById('hregion').value,document.getElementById('hcity').value,document.getElementById('harea').value,document.getElementById('hshop').value)> <font color='blue'>more events... </font> </div>";
            // $output .= "<div style='cursor:pointer'><a href='" . ModUtil::url('ZSELEX', 'user', 'showEvents') . "'>All Events...</a></div>";
            if ($dateExist > 0) {
                // $output .= "<div style='cursor:pointer' onclick=check(document.getElementById('hcountry').value,document.getElementById('hregion').value,document.getElementById('hcity').value,document.getElementById('harea').value,document.getElementById('hshop').value)> <font color='blue'>" . $this->__('All Events...') . " </font> </div>";
            }
            // }
        } else {
            $output .= '<dt> &nbsp;&nbsp;&nbsp;&nbsp;  ' . $this->__('No Events Found') . '  </dt>';
        }
        // }

        $output .= '</dl>';
        ZSELEX_Util::ajaxOutput($output);
    }

    public function getAllEvents() {

        // exit;
        $level = FormUtil::getPassedValue("level");
        $shop_id = FormUtil::getPassedValue("shop_id");
        $country_id = FormUtil::getPassedValue("country_id");
        $region_id = FormUtil::getPassedValue("region_id");
        $city_id = FormUtil::getPassedValue("city_id");
        $area_id = FormUtil::getPassedValue("area_id");
        $branch_id = FormUtil::getPassedValue("branch_id");
        $category_id = FormUtil::getPassedValue("category_id");
        $search = FormUtil::getPassedValue("hsearch");
        $search = ($search == $this->__('search for...') || $search == $this->__('search')) ? '' : $search;
        $eventlimit = FormUtil::getPassedValue("eventlimit");
        $reset = FormUtil::getPassedValue("reset");
        // echo $country_id; exit;
        if (!empty($eventlimit)) {

            $limit = $eventlimit;
            $limitquery = "LIMIT 0 , $eventlimit";
        } else {
            $limit = "2";
            $limitquery = "LIMIT 0 , 2";
        }

        $searchquery = '';
        if (($shop_id > 0 || !empty($shop_id)) || ($region_id > 0 || !empty($region_id)) || ($city_id > 0 || !empty($city_id)) || ($country_id > 0 || !empty($country_id)) || ($area_id > 0 || !empty($area_id)) || ($search > 0 || !empty($search)) || ($category_id > 0 || !empty($category_id)) || ($branch_id > 0 || !empty($branch_id) || !empty($eventdate))) {

            // $eventdateqry = " AND shop_event_startdate>=CURDATE()";

            $eventdateqry = '';

            $output = '';
            $items = array(
                'id' => $shop_id
            );
            // $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
            $where = '';
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
                // $append .= " AND a.cat_id=$category_id";
                $append .= " AND a.shop_id IN(SELECT shop_id FROM zselex_shop_to_category WHERE category_id=$category_id) ";
            }

            if (!empty($branch_id)) {
                $append .= " AND a.branch_id=$branch_id";
            }

            if (!empty($hsearch)) {

                $append .= " AND a.shop_name LIKE '%" . DataUtil::formatForStore($search) . "%'
                             OR a.shop_id IN (SELECT shop_id FROM zselex_advertise WHERE keywords LIKE '%" . DataUtil::formatForStore($search) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_files WHERE keywords LIKE '%" . DataUtil::formatForStore($search) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_gallery WHERE keywords LIKE '%" . DataUtil::formatForStore($search) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_pdf WHERE keywords LIKE '%" . DataUtil::formatForStore($search) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_dotd WHERE keywords LIKE '%" . DataUtil::formatForStore($search) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_products WHERE keywords LIKE '%" . DataUtil::formatForStore($search) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_news WHERE news_id IN 
                             (SELECT sid FROM news WHERE title LIKE '%" . DataUtil::formatForStore($search) . "%' OR hometext LIKE '%" . DataUtil::formatForStore($search) . "%' OR bodytext LIKE '%" . DataUtil::formatForStore($search) . "%' OR urltitle LIKE '%" . DataUtil::formatForStore($search) . "%'))
            ";
            }

            $sql = "SELECT a.shop_id FROM zselex_shop a
                     WHERE a.shop_id IS NOT NULL AND a.status='1'  $append";
            // echo $sql; exit;

            $query = DBUtil::executeSQL($sql);
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
            $shopsql = " AND shop_id IN($shop_ids)";

            if ($reset != 'reset') {
                $shopquery = $shopsql;
            } else {
                $shopquery = "";
            }
            // echo $shopquery;

            $sqlevents = "SELECT *, shop_event_id , shop_id , shop_event_startdate , LEFT(shop_event_name, 20) AS event_name  ,   LEFT(shop_event_description, 20) AS event_description  ,   LEFT(shop_event_shortdescription, 20) AS event_shortdescription , DAYNAME(shop_event_startdate) as dateformated
                          FROM zselex_shop_events 
                          WHERE shop_id IS NOT NULL AND shop_event_id IS NOT NULL AND status='1' $shopquery" . " " . $eventdateqry . " AND shop_event_enddate>=CURDATE() ORDER BY shop_event_startdate ASC" . " ";
            // echo $sqlevents;
            $query1 = DBUtil::executeSQL($sqlevents);
            $events = $query1->fetchAll();
            $eventcount = count($events);
            // shuffle($events);

            $totalcountsql = "SELECT * FROM zselex_shop_events
                              WHERE shop_id IS NOT NULL AND shop_event_id IS NOT NULL AND shop_event_enddate>=CURDATE() AND status='1' $shopquery " . " " . $eventdateqry;
            $totalcountquery = DBUtil::executeSQL($totalcountsql);
            $totalCount = $totalcountquery->rowCount();

            $i = 1;

            if ($eventcount > 0) {
                $todayDate = date("Y-m-d");

                foreach ($events as $key => $event) {

                    $date = strtotime(date("Y-m-d", strtotime($todayDate)) . " +$key day");

                    $headlinedate = date('l dS \o\f F Y', $date);

                    $events [$key] ['headlinedate'] = $headlinedate;
                }

                foreach ($events as $event) {
                    if ($i == $limit) {
                        // break;
                    }
                    ;

                    $output .= "<div>
                               <b>$event[headlinedate]</b> </br> 
                                    &nbsp;&nbsp;<a href='" . ModUtil::url('ZSELEX', 'user', 'viewevent', array(
                                'shop_id' => $event ['shop_id'],
                                'eventId' => $event ['shop_event_id']
                            )) . "'>$event[event_name]</a><br>
                                    &nbsp;&nbsp;$event[event_shortdescription]...<br>
                                    &nbsp;&nbsp;$event[event_description]...<br>
                                    
                                 
                               <div><br>";
                    $i ++;
                }
                if ($totalCount > $eventcount) {
                    // $output .= '<div style="cursor:pointer" align=right onclick=getMoreEvents()> <font color="blue">more events... </font> </div>';
                    // $output .= "<div style='cursor:pointer' onclick=check(document.getElementById('hcountry').value,document.getElementById('hregion').value,document.getElementById('hcity').value,document.getElementById('harea').value,document.getElementById('hshop').value)> <font color='blue'>".$this->__('more events...')." </font> </div>";
                }
            } else {
                $output .= '<dt> &nbsp;&nbsp;&nbsp;&nbsp;  ' . $this->__('No Events Found') . '  </dt>';
            }
            // }
        } else {

            $output .= '<dt> &nbsp;&nbsp;&nbsp;&nbsp;  ' . $this->__('No Events Found') . '  </dt>';
        }

        $output .= '</dl>';
        ZSELEX_Util::ajaxOutput($output);
    }

    public function getAllEventsReset() {
        $level = FormUtil::getPassedValue("level");
        $shop_id = FormUtil::getPassedValue("shop_id");
        $country_id = FormUtil::getPassedValue("country_id");
        $region_id = FormUtil::getPassedValue("region_id");
        $city_id = FormUtil::getPassedValue("city_id");
        $area_id = FormUtil::getPassedValue("area_id");
        $branch_id = FormUtil::getPassedValue("branch_id");
        $category_id = FormUtil::getPassedValue("category_id");
        $search = FormUtil::getPassedValue("hsearch");
        $search = ($search == $this->__('search for...') || $search == $this->__('search')) ? '' : $search;
        $eventlimit = FormUtil::getPassedValue("eventlimit");
        $reset = FormUtil::getPassedValue("reset");
        // echo $reset; exit;
        if (!empty($eventlimit)) {

            $limit = $eventlimit;
            $limitquery = " LIMIT 0 , $eventlimit";
        } else {
            $limit = "2";
            $limitquery = " LIMIT 0 , 2";
        }

        $searchquery = '';

        $shopsql = '';

        $sqlevents = "SELECT *, shop_event_id , shop_id , shop_event_startdate , LEFT(shop_event_name, 20) AS event_name  ,  LEFT(shop_event_description, 20) AS event_description  ,   LEFT(shop_event_shortdescription, 20) AS event_shortdescription , DAYNAME(shop_event_startdate) as dateformated
                FROM zselex_shop_events 
                WHERE shop_event_enddate>=CURDATE() ORDER BY shop_event_startdate ASC ";
        // echo $sqlevents;
        $query = DBUtil::executeSQL($sqlevents);
        $events = $query->fetchAll();
        $eventcount = count($events);

        $totalcountsql = "SELECT *, shop_event_id , shop_id , shop_event_startdate , LEFT(shop_event_name, 20) AS event_name  ,  LEFT(shop_event_description, 20) AS event_description  ,   LEFT(shop_event_shortdescription, 20) AS event_shortdescription , DAYNAME(shop_event_startdate) as dateformated
                FROM zselex_shop_events 
                WHERE shop_event_enddate>=CURDATE()";
        $totalcountquery = DBUtil::executeSQL($totalcountsql);
        $totalCount = $totalcountquery->rowCount();

        $i = 1;

        if ($eventcount > 0) {

            $todayDate = date("Y-m-d");

            foreach ($events as $key => $event) {

                $date = strtotime(date("Y-m-d", strtotime($todayDate)) . " +$key day");

                $headlinedate = date('l dS \o\f F Y', $date);

                $events [$key] ['headlinedate'] = $headlinedate;
            }

            foreach ($events as $event) {
                if ($i == $limit) {
                    // break;
                }
                ;

                $output .= "<div>
                               <b>$event[headlinedate]</b> </br> 
                                    &nbsp;&nbsp;<a href='" . ModUtil::url('ZSELEX', 'user', 'viewevent', array(
                            'shop_id' => $event ['shop_id'],
                            'eventId' => $event ['shop_event_id']
                        )) . "'>$event[event_name]</a><br>
                                    &nbsp;&nbsp;$event[event_shortdescription]...<br>
                                    &nbsp;&nbsp;$event[event_description]...<br>
                                    
                                 
                               <div><br>";
                $i ++;
            }
            if ($totalCount > $eventcount) {
                // $output .= '<div style="cursor:pointer" align=right onclick=getMoreEvents()> <font color="blue">more events... </font> </div>';
                $output .= "<div style='cursor:pointer'   onclick=check(document.getElementById('hcountry').value,document.getElementById('hregion').value,document.getElementById('hcity').value,document.getElementById('harea').value,document.getElementById('hshop').value)> <font color='blue'>" . $this->__('more events...') . " </font> </div>";
            }
        } else {
            $output .= '<dt> &nbsp;&nbsp;&nbsp;&nbsp;  ' . $this->__('No Events Found') . '  </dt>';
        }
        // }

        $output .= '</dl>';
        ZSELEX_Util::ajaxOutput($output);
    }

    public function setShopIdOnSelections($country_id, $region_id, $city_id, $area_id, $shop_id, $searchText) {
        // SessionUtil::setVar('shopsarray', $shopres = array());
        // $_SESSION['shoparray'] = array();
        // unset($_SESSION['shoparray']);
        $shop_id = !empty($shop_id) ? $shop_id : 0;
        $country_id = !empty($country_id) ? $country_id : 0;
        $region_id = !empty($region_id) ? $region_id : 0;
        $city_id = !empty($city_id) ? $city_id : 0;
        $area_id = !empty($area_id) ? $area_id : 0;
        $hsearch = !empty($searchText) ? $searchText : '';

        $category_id = FormUtil::getPassedValue("category_id");
        $branch_id = FormUtil::getPassedValue("branch_id");

        $startval = FormUtil::getPassedValue("startval");
        $endval = FormUtil::getPassedValue("endval");

        $searchquery = '';

        if (!empty($hsearch)) {

            // $searchquery = " AND a.shop_name LIKE '%" . DataUtil::formatForStore($hsearch) . "%'";
            $searchquery = " AND a.shop_name LIKE '%" . DataUtil::formatForStore($hsearch) . "%'
                             OR a.shop_id IN (SELECT shop_id FROM zselex_advertise WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_files WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_gallery WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_pdf WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_dotd WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_products WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_news WHERE news_id IN 
                             (SELECT sid FROM news WHERE title LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR hometext LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR bodytext LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR urltitle LIKE '%" . DataUtil::formatForStore($hsearch) . "%'))
            ";
        }

        $catquery = '';
        if ($category_id != '') {
            $catquery = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='CATEGORY'
                AND parentId=$category_id)";
        }

        if ($category_id != '' && $country_id <= 0 && $region_id <= 0 && $city_id <= 0 && $area_id <= 0 && $shop_id <= 0) {
            $catshop = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='CATEGORY'
                AND parentId=$category_id)";
        }

        $branchquery = '';
        $branchshop = '';
        if ($branch_id != '') {
            $branchquery = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='BRANCH'
                AND parentId=$branch_id)";
        }

        if ($branch_id != '' && $country_id <= 0 && $region_id <= 0 && $city_id <= 0 && $area_id <= 0 && $shop_id <= 0) {
            $branchshop = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='BRANCH'
                AND parentId=$branch_id)";
        }

        $searchquerymain = '';
        if (!empty($hsearch) && $country_id <= 0 && $region_id <= 0 && $city_id <= 0 && $area_id <= 0 && $shop_id <= 0) { // when only search is typed
            // $searchquerymain = " AND a.shop_name LIKE '%" . DataUtil::formatForStore($hsearch) . "%'";
            $searchquerymain = " AND a.shop_name LIKE '%" . DataUtil::formatForStore($hsearch) . "%'
                             OR a.shop_id IN (SELECT shop_id FROM zselex_advertise WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_files WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_gallery WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_pdf WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_dotd WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_products WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_news WHERE news_id IN 
                             (SELECT sid FROM news WHERE title LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR hometext LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR bodytext LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR urltitle LIKE '%" . DataUtil::formatForStore($hsearch) . "%'))
            ";
        }

        $output = '';

        $items = array(
            'id' => $shop_id
        );
        // $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
        $where = '';

        if (($country_id > 0) or ( $country_id > 0 && $region_id < 0 && $city_id < 0 && $shop_id < 0)) { // COUNTRY
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

        if (($region_id > 0) or ( $country_id > 0 && $region_id > 0 && $city_id < 0 && $shop_id < 0)) { // REGION
            $where = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP'
                AND parentType='CITY' AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='REGION' 
                AND parentId=$region_id)) $catquery $branchquery $searchquery
                OR
                a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='REGION' AND parentId=$region_id) $catquery $branchquery $searchquery
            ";
        }

        if (($city_id > 0) or ( $region_id > 0 && $city_id > 0 && $country_id > 0 && $shop_id < 0)) { // CITY
            $where = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='CITY' AND parentId=$city_id) $catquery $branchquery $searchquery";
        }

        if (($area_id > 0) or ( $region_id > 0 && $area_id > 0 && $city_id > 0 && $country_id > 0 && $shop_id < 0)) { // AREA
            $where = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='AREA' AND parentId=$area_id) $catquery $branchquery $searchquery";
        }

        if (($shop_id > 0) or ( $shop_id > 0 && $region_id > 0 && $city_id > 0 && $country_id > 0)) { // SHOP
            $where = " AND a.shop_id=$shop_id  $catquery $branchquery $searchquery";
        }

        // echo $where;

        $sql = "SELECT a.shop_id FROM zselex_shop a
                WHERE a.shop_id IS NOT NULL 
                $where $catshop $branchshop $searchquerymain";

        // echo $sql;
        $query = DBUtil::executeSQL($sql);
        $shopres = $query->fetchAll();
        // echo "<pre>"; print_r($shopres); echo "</pre>";
        // $this->view->clear_cache();
        $_SESSION ['shoparray'] = $shopres;
        // $_SESSION['shoparray'] = array('name'=>'sharaz', 'age'=>'20');;
        // echo "<pre>"; print_r($_SESSION['shoparray']); echo "</pre>";
        // SessionUtil::setVar('shopsarray', $shopres);
    }

    public function getShopDetails() { // used for getting shop list on front page based on selection
        // echo "comes here";
        $shop_id = FormUtil::getPassedValue("shop_id");
        $country_id = FormUtil::getPassedValue("country_id");
        $region_id = FormUtil::getPassedValue("region_id");
        $city_id = FormUtil::getPassedValue("city_id");
        $area_id = FormUtil::getPassedValue("area_id");
        $hsearch = FormUtil::getPassedValue("hsearch");
        $hsearch = ($hsearch == $this->__('search for...') || $hsearch == $this->__('search')) ? '' : $hsearch;

        $category_id = FormUtil::getPassedValue("category_id");
        $branch_id = FormUtil::getPassedValue("branch_id");

        $startval = FormUtil::getPassedValue("startval");
        $endval = FormUtil::getPassedValue("endval");
        // $this->setShopIdOnSelections($country_id, $region_id, $city_id, $area_id, $shop_id, $hsearch);

        $searchquerymain = '';
        if (!empty($hsearch) && $country_id <= 0 && $region_id <= 0 && $city_id <= 0 && $area_id <= 0 && $shop_id <= 0) { // when only search is typed
            // $searchquerymain = " AND a.shop_name LIKE '%" . DataUtil::formatForStore($hsearch) . "%'";
            $searchquerymain = " AND a.shop_name LIKE '%" . DataUtil::formatForStore($hsearch) . "%'
                             OR a.shop_id IN (SELECT shop_id FROM zselex_advertise WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_files WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_gallery WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_pdf WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_dotd WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_products WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_events WHERE shop_event_keywords 
                             LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR shop_event_name LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR shop_event_shortdescription LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR shop_event_description LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR shop_event_startdate LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR shop_event_starthour LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR shop_event_startminute LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR shop_event_enddate LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR shop_event_endhour LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR shop_event_endminute LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR shop_event_endminute LIKE '%" . DataUtil::formatForStore($hsearch) . "%')     
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_news WHERE news_id IN 
                             (SELECT sid FROM news WHERE title LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR hometext LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR bodytext LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR urltitle LIKE '%" . DataUtil::formatForStore($hsearch) . "%'))
            ";
        }

        $output = '';

        $items = array(
            'id' => $shop_id
        );
        // $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
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
            // $append .= " AND a.cat_id=$category_id";
            $append .= " AND a.shop_id IN(SELECT shop_id FROM zselex_shop_to_category WHERE category_id=$category_id) ";
        }

        if (!empty($branch_id)) {
            $append .= " AND a.branch_id=$branch_id";
        }

        if (!empty($hsearch)) {

            $search_append = " AND a.shop_name LIKE '%" . DataUtil::formatForStore($hsearch) . "%'
                             OR a.shop_id IN (SELECT shop_id FROM zselex_advertise WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_files WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_gallery WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_pdf WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_dotd WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_products WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_events WHERE shop_event_keywords 
                             LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR shop_event_name LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR shop_event_shortdescription LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR shop_event_description LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR shop_event_startdate LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR shop_event_starthour LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR shop_event_startminute LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR shop_event_enddate LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR shop_event_endhour LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR shop_event_endminute LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR shop_event_endminute LIKE '%" . DataUtil::formatForStore($hsearch) . "%')     
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_news WHERE news_id IN 
                             (SELECT sid FROM news WHERE title LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR hometext LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR bodytext LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR urltitle LIKE '%" . DataUtil::formatForStore($hsearch) . "%'))
            ";
            // $search_append = " AND a.shop_name LIKE '%" . DataUtil::formatForStore($hsearch) . "%'";
            $append .= $search_append;
        }

        // echo $where;

        $sql = "SELECT * ,ms.configured as minishop_configured, g.image_name, a.shop_id as SID FROM zselex_shop a
		 LEFT JOIN zselex_serviceshop sv ON sv.shop_id=a.shop_id AND (sv.type='linktoshop')
                 LEFT JOIN zselex_minishop ms ON ms.shop_id=a.shop_id
                 LEFT JOIN zselex_shop_owners ow ON ow.shop_id=a.shop_id
                 LEFT JOIN users u ON u.uid=ow.user_id
                 LEFT JOIN zselex_shop_gallery g ON a.shop_id=g.shop_id AND g.defaultImg=1
		 LEFT JOIN zselex_files b ON a.shop_id=b.shop_id AND b.defaultImg=1
                 WHERE a.shop_id IS NOT NULL AND a.status='1'
                 $append  LIMIT $startval , $endval";

        // echo $sql;

        $query = DBUtil::executeSQL($sql);
        $sValues = $query->fetchAll();

        $count = count($sValues);

        $totalsql = "SELECT * FROM zselex_shop a
                     WHERE a.shop_id IS NOT NULL AND a.status='1'  $append";
        $statement = Doctrine_Manager::getInstance()->connection();
        $resultcount = $statement->execute($totalsql);
        $totalCount = $resultcount->rowCount();

        $outputstart = '';
        $outputend = '';

        // $startvals = $startval + 5;

        $startvals = $startval + $endval;
        $outputTotalshops = '';
        if ($totalCount > 0) {
            $outputTotalshops = "<span style='cursor:pointer; float:left;  margin-left:90px;  color:#605d59;'>" . $this->__('Total Shops :') . " $totalCount</span>";
        }
        if ($startval > 0) {
            // $outputstart = "<span onClick=paginatePrev() style='cursor:pointer; float:left; color:blue'>Prev</span>";
            $outputstart = "<span onClick=paginatePrev() style='cursor:pointer; float:left; color:#605d59; font-weight:bold'> <img src='" . pnGetBaseURL() . "images/LtArrow.jpg' style='vertical-align:top; margin-top:1px'>&nbsp; " . $this->__('Prev') . "</span>";
        }

        if ($totalCount > $startvals) {
            // $outputend = "<span onClick=paginateNext() style='cursor:pointer;float:right; color:blue'>Next</span>";
            $outputend = "<span  onClick=paginateNext() style='cursor:pointer;float:right; color:#605d59; font-weight:bold'>" . $this->__('Next') . " &nbsp;<img src='" . pnGetBaseURL() . "images/RtArrow.jpg' style='vertical-align:top; margin-top:1px'></span>";
        }

        $output .= "<div>
           
            $outputstart
            $outputTotalshops
            $outputend
                   </div><br>";

        if ($count > 0) {
            $linkShopStrt = '';
            $linkShopEnd = '';
            $div = '';
            $linkStrt = '';
            $linkEnd = '';
            foreach ($sValues as $shops) {
                $ID = $shops ['SID'];

                $linkSiteStrt = "<a href='" . ModUtil::url('ZSELEX', 'user', 'site', array(
                            'id' => $ID
                        )) . "' target='_blank'>";
                $linkSiteEnd = "</a>";

                if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                    if ($shops ['quantity'] > 0 && $shops ['minishop_configured'] > 0) {
                        $linkShopStrt = "<a style='color:green' href='" . ModUtil::url('ZSELEX', 'user', 'shop', array(
                                    'shop_id' => $ID
                                )) . "' target='_blank'>" . $this->__('Go To Shop') . "";

                        $linkShopEnd = "</a>";
                    } else {
                        $linkShopStrt = '';
                        $linkShopEnd = '';
                    }
                } else {

                    if ($shops ['minishop_configured'] > 0) {
                        $linkShopStrt = "<a style='color:green' href='" . ModUtil::url('ZSELEX', 'user', 'shop', array(
                                    'shop_id' => $ID
                                )) . "' target='_blank'>" . $this->__('Go To Shop') . "";

                        $linkShopEnd = "</a>";
                    } else {
                        $linkShopStrt = '';
                        $linkShopEnd = '';
                    }
                }

                $div = '';
                if ($shops ['default_img_frm'] == 'fromshop') { // minisite shop images
                    if (!empty($shops ['name'])) {
                        $div = "<div>$linkSiteStrt<img src='zselexdata/$shops[uname]/minisiteimages/thumb/$shops[name]'>$linkSiteEnd</div>";
                    } else {
                        $div = '';
                    }
                } elseif ($shops ['default_img_frm'] == 'fromgallery') { // minisite gallery images
                    if (!empty($shops ['image_name'])) {
                        $div = "<div>$linkSiteStrt<img src='zselexdata/$shops[uname]/minisitegallery/thumb/$shops[image_name]'>$linkSiteEnd</div>";
                    } else {
                        $div = '';
                    }
                }

                $argsfb = array(
                    'url' => pnGetBaseURL() . ModUtil::url('ZSELEX', 'user', 'site', array(
                        'shop_id' => $ID
                    )),
                    'layout' => 'horizontal'
                );
                $argstwitter = array(
                    'url' => pnGetBaseURL() . ModUtil::url('ZSELEX', 'user', 'site', array(
                        'shop_id' => $ID
                    )),
                    'title' => $shops [shop_name]
                );
                $argsgplus = array(
                    'title' => $shops [shop_name],
                    'description' => $shops [address]
                );
                $argsshare = array(
                    'id' => $ID,
                    'url' => pnGetBaseURL() . ModUtil::url('ZSELEX', 'user', 'site', array(
                        'shop_id' => $ID
                    )),
                    'title' => $shops [shop_name]
                );

                $output .= "<div style='border:solid 1px #CCC; padding-top:15px; padding-bottom:5px; padding-left:15px; '>
                             $div
                      <div><b>" . $this->__('Shop Name') . "</b>:$linkSiteStrt" . $shops ['shop_name'] . "$linkSiteEnd</div>
                          
                      <div><b>" . $this->__('Address') . "</b>: " . $shops ['address'] . "</div>
                          
                      <div><b>" . $this->__('Telephone') . "</b>: " . $shops ['telephone'] . "</div>
                       
                      <div><b>" . $this->__('Fax') . "</b>: " . $shops ['fax'] . "</div>
                          
                      <div><b>" . $this->__('Email') . "</b>: " . $shops ['email'] . "</div>
                            
                      <div>" . $linkShopStrt . $linkShopEnd . "</div>
                     
                    </div>
                  ";
            }
            $output .= "<div>
            $outputstart
            
            $outputend
                   </div><br>";
        } else {

            $output = "<dl> &nbsp;&nbsp;&nbsp;&nbsp;  " . $this->__('No Shops Found') . "</dl>";
        }
        // "region:" . $region_id . "country:" . $country_id
        // ZSELEX_Util::ajaxOutput("region: " . $region_id . "country: " . $country_id . "city_id: " .$city_id. "shop_id: " .$shop_id);
        ZSELEX_Util::ajaxOutput($output);
    }

    function fblike($args) {
        // echo "<pre>"; print_r($args);
        return ModUtil::apiFunc('Socialise', 'plugin', 'fblike', $args);
    }

    function twitter($args) {
        // echo "<pre>"; print_r($args);
        return ModUtil::apiFunc('Socialise', 'plugin', 'twitter', $args);
    }

    function gplus($args) {
        return ModUtil::apiFunc('Socialise', 'plugin', 'googleplus', $args);
    }

    function shareit($args) {
        return ModUtil::apiFunc('Socialise', 'plugin', 'sharethis', $args);
    }

    /**
     * view shop list on front end.
     *
     * this function is rendered when the user selection happens in fronnt end
     */
    /*
     * public function getShopDetails() { // used for getting shop list on front page based on selection
     * $shop_id = FormUtil::getPassedValue("shop_id");
     * $country_id = FormUtil::getPassedValue("country_id");
     * $region_id = FormUtil::getPassedValue("region_id");
     * $city_id = FormUtil::getPassedValue("city_id");
     * $area_id = FormUtil::getPassedValue("area_id");
     * $hsearch = FormUtil::getPassedValue("hsearch");
     *
     * $category_id = FormUtil::getPassedValue("category_id");
     * $branch_id = FormUtil::getPassedValue("branch_id");
     *
     * $startval = FormUtil::getPassedValue("startval");
     * $endval = FormUtil::getPassedValue("endval");
     * //$this->setShopIdOnSelections($country_id, $region_id, $city_id, $area_id, $shop_id, $hsearch);
     *
     * $searchquery = '';
     *
     * if (!empty($hsearch)) {
     *
     * //$searchquery = " AND a.shop_name LIKE '%" . DataUtil::formatForStore($hsearch) . "%'";
     * $searchquery = " AND a.shop_name LIKE '%" . DataUtil::formatForStore($hsearch) . "%'
     * OR a.shop_id IN (SELECT shop_id FROM zselex_advertise WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
     * OR a.shop_id IN (SELECT shop_id FROM zselex_files WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
     * OR a.shop_id IN (SELECT shop_id FROM zselex_shop_gallery WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
     * OR a.shop_id IN (SELECT shop_id FROM zselex_shop_pdf WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
     * OR a.shop_id IN (SELECT shop_id FROM zselex_dotd WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
     * OR a.shop_id IN (SELECT shop_id FROM zselex_products WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
     * OR a.shop_id IN (SELECT shop_id FROM zselex_shop_news WHERE news_id IN
     * (SELECT sid FROM news WHERE title LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR hometext LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR bodytext LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR urltitle LIKE '%" . DataUtil::formatForStore($hsearch) . "%'))
     * ";
     * }
     *
     *
     *
     * $catquery = '';
     * if ($category_id != '') {
     * $catquery = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='CATEGORY'
     * AND parentId=$category_id)";
     * }
     *
     * if ($category_id != '' && $country_id <= 0 && $region_id <= 0 && $city_id <= 0 && $area_id <= 0 && $shop_id <= 0) {
     * $catshop = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='CATEGORY'
     * AND parentId=$category_id)";
     * }
     *
     * $branchquery = '';
     * $branchshop = '';
     * if ($branch_id != '') {
     * $branchquery = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='BRANCH'
     * AND parentId=$branch_id)";
     * }
     *
     * if ($branch_id != '' && $country_id <= 0 && $region_id <= 0 && $city_id <= 0 && $area_id <= 0 && $shop_id <= 0) {
     * $branchshop = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='BRANCH'
     * AND parentId=$branch_id)";
     * }
     *
     *
     * $searchquerymain = '';
     * if (!empty($hsearch) && $country_id <= 0 && $region_id <= 0 && $city_id <= 0 && $area_id <= 0 && $shop_id <= 0) { // when only search is typed
     * //$searchquerymain = " AND a.shop_name LIKE '%" . DataUtil::formatForStore($hsearch) . "%'";
     * $searchquerymain = " AND a.shop_name LIKE '%" . DataUtil::formatForStore($hsearch) . "%'
     * OR a.shop_id IN (SELECT shop_id FROM zselex_advertise WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
     * OR a.shop_id IN (SELECT shop_id FROM zselex_files WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
     * OR a.shop_id IN (SELECT shop_id FROM zselex_shop_gallery WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
     * OR a.shop_id IN (SELECT shop_id FROM zselex_shop_pdf WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
     * OR a.shop_id IN (SELECT shop_id FROM zselex_dotd WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
     * OR a.shop_id IN (SELECT shop_id FROM zselex_products WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
     * OR a.shop_id IN (SELECT shop_id FROM zselex_shop_news WHERE news_id IN
     * (SELECT sid FROM news WHERE title LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR hometext LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR bodytext LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR urltitle LIKE '%" . DataUtil::formatForStore($hsearch) . "%'))
     * ";
     * }
     *
     *
     *
     * $output = '';
     *
     *
     * $items = array('id' => $shop_id);
     * //$shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
     * $where = '';
     *
     * if (($country_id > 0) OR ($country_id > 0 && $region_id < 0 && $city_id < 0 && $shop_id < 0)) { //COUNTRY
     * $where = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP'
     * AND parentType='CITY' AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY'
     * AND parentType='COUNTRY' AND parentId=$country_id)) $catquery $branchquery $searchquery
     * OR
     * a.shop_id IN(select childId from zselex_parent where childType='SHOP' and parentType='REGION' and parentId IN(select childId FROM zselex_parent WHERE childType='REGION' AND parentType='COUNTRY' AND parentId=$country_id)) $catquery $branchquery $searchquery
     * OR
     * a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP'
     * AND parentType='CITY' AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY'
     * AND parentType='REGION' AND parentId IN(SELECT
     * childId FROM zselex_parent WHERE childType='REGION' AND parentType='COUNTRY' AND parentId=$country_id))) $catquery $branchquery $searchquery
     * OR
     * a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='COUNTRY' AND parentId=$country_id) $catquery $branchquery $searchquery
     *
     * ";
     * }
     *
     *
     * if (($region_id > 0) OR ($country_id > 0 && $region_id > 0 && $city_id < 0 && $shop_id < 0 )) { // REGION
     * $where = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP'
     * AND parentType='CITY' AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='REGION'
     * AND parentId=$region_id)) $catquery $branchquery $searchquery
     * OR
     * a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='REGION' AND parentId=$region_id) $catquery $branchquery $searchquery
     * ";
     * }
     *
     * if (($city_id > 0) OR ($region_id > 0 && $city_id > 0 && $country_id > 0 && $shop_id < 0)) { //CITY
     * $where = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='CITY' AND parentId=$city_id) $catquery $branchquery $searchquery";
     * }
     *
     * if (($area_id > 0) OR ($region_id > 0 && $area_id > 0 && $city_id > 0 && $country_id > 0 && $shop_id < 0)) { //AREA
     * $where = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='AREA' AND parentId=$area_id) $catquery $branchquery $searchquery";
     * }
     *
     * if (($shop_id > 0) OR ($shop_id > 0 && $region_id > 0 && $city_id > 0 && $country_id > 0)) { //SHOP
     * $where = " AND a.shop_id=$shop_id $catquery $branchquery $searchquery";
     * }
     *
     * //echo $where;
     *
     * $sql = "SELECT * ,ms.configured as minishop_configured, g.image_name, a.shop_id as SID FROM zselex_shop a
     * LEFT JOIN zselex_serviceshop sv ON sv.shop_id=a.shop_id AND (sv.type='linktoshop')
     * LEFT JOIN zselex_minishop ms ON ms.shop_id=a.shop_id
     * LEFT JOIN zselex_shop_owners ow ON ow.shop_id=a.shop_id
     * LEFT JOIN users u ON u.uid=ow.user_id
     * LEFT JOIN zselex_shop_gallery g ON a.shop_id=g.shop_id AND g.defaultImg=1
     * LEFT JOIN zselex_files b ON a.shop_id=b.shop_id AND b.defaultImg=1
     * WHERE a.shop_id IS NOT NULL AND a.status='1'
     * $where $catshop $branchshop $searchquerymain LIMIT $startval , $endval";
     *
     * //echo $sql;
     *
     *
     * $query = DBUtil::executeSQL($sql);
     * $sValues = $query->fetchAll();
     *
     *
     * $count = count($sValues);
     *
     *
     * $totalsql = "SELECT * FROM zselex_shop a
     * WHERE a.shop_id IS NOT NULL AND a.status='1' $where $catshop $branchshop $searchquerymain";
     * $statement = Doctrine_Manager::getInstance()->connection();
     * $resultcount = $statement->execute($totalsql);
     * $totalCount = $resultcount->rowCount();
     *
     * $outputstart = '';
     * $outputend = '';
     *
     * // $startvals = $startval + 5;
     *
     * $startvals = $startval + $endval;
     * $outputTotalshops = '';
     * if ($totalCount > 0) {
     * $outputTotalshops = "<span style='cursor:pointer; float:left; margin-left:90px; color:#605d59;'>" . $this->__('Total Shops :') . " $totalCount</span>";
     * }
     * if ($startval > 0) {
     * //$outputstart = "<span onClick=paginatePrev() style='cursor:pointer; float:left; color:blue'>Prev</span>";
     * $outputstart = "<span onClick=paginatePrev() style='cursor:pointer; float:left; color:#605d59; font-weight:bold'> <img src='" . pnGetBaseURL() . "images/LtArrow.jpg' style='vertical-align:top; margin-top:1px'>&nbsp; " . $this->__('Prev') . "</span>";
     * }
     *
     *
     * if ($totalCount > $startvals) {
     * //$outputend = "<span onClick=paginateNext() style='cursor:pointer;float:right; color:blue'>Next</span>";
     * $outputend = "<span onClick=paginateNext() style='cursor:pointer;float:right; color:#605d59; font-weight:bold'>" . $this->__('Next') . " &nbsp;<img src='" . pnGetBaseURL() . "images/RtArrow.jpg' style='vertical-align:top; margin-top:1px'></span>";
     * }
     *
     * $output .= "<div>
     *
     * $outputstart
     * $outputTotalshops
     * $outputend
     * </div><br>";
     *
     * if ($count > 0) {
     * $linkShopStrt = '';
     * $linkShopEnd = '';
     * $div = '';
     * $linkStrt = '';
     * $linkEnd = '';
     * foreach ($sValues as $shops) {
     * $ID = $shops['SID'];
     *
     * $linkSiteStrt = "<a href='" . ModUtil::url('ZSELEX', 'user', 'shop', array('id' => $ID)) . "' target='_blank'>";
     * $linkSiteEnd = "</a>";
     *
     * if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
     * if ($shops['quantity'] > 0 && $shops['minishop_configured'] > 0) {
     * $linkShopStrt = "<a style='color:green' href='" . ModUtil::url('ZSELEX', 'user', 'shop', array('shop_id' => $ID)) . "' target='_blank'>" . $this->__('Go To Shop') . "";
     *
     * $linkShopEnd = "</a>";
     * } else {
     * $linkShopStrt = '';
     * $linkShopEnd = '';
     * }
     * } else {
     *
     * if ($shops['minishop_configured'] > 0) {
     * $linkShopStrt = "<a style='color:green' href='" . ModUtil::url('ZSELEX', 'user', 'shop', array('shop_id' => $ID)) . "' target='_blank'>" . $this->__('Go To Shop') . "";
     *
     * $linkShopEnd = "</a>";
     * } else {
     * $linkShopStrt = '';
     * $linkShopEnd = '';
     * }
     * }
     *
     * $div = '';
     * if ($shops['default_img_frm'] == 'fromshop') { // minisite shop images
     * if (!empty($shops['name'])) {
     * $div = "<div>$linkSiteStrt<img src='zselexdata/$shops[uname]/minisiteimages/thumb/$shops[name]'>$linkSiteEnd</div>";
     * } else {
     * $div = '';
     * }
     * } elseif ($shops['default_img_frm'] == 'fromgallery') { // minisite gallery images
     * if (!empty($shops['image_name'])) {
     * $div = "<div>$linkSiteStrt<img src='zselexdata/$shops[uname]/minisitegallery/thumb/$shops[image_name]'>$linkSiteEnd</div>";
     * } else {
     * $div = '';
     * }
     * }
     *
     *
     *
     * $output .= "<div style='border:solid 1px #CCC; padding-top:15px; padding-bottom:5px; padding-left:15px; '>
     * $div
     * <div><b>" . $this->__('Shop Name') . "</b>:$linkSiteStrt" . $shops['shop_name'] . "$linkSiteEnd</div>
     *
     * <div><b>" . $this->__('Address') . "</b>: " . $shops['address'] . "</div>
     *
     * <div><b>" . $this->__('Telephone') . "</b>: " . $shops['telephone'] . "</div>
     *
     * <div><b>" . $this->__('Fax') . "</b>: " . $shops['fax'] . "</div>
     *
     * <div><b>" . $this->__('Email') . "</b>: " . $shops['email'] . "</div>
     *
     * <div>" . $linkShopStrt . $linkShopEnd . "</div>
     *
     *
     * </div>
     * ";
     * }
     * $output .= "<div>
     * $outputstart
     *
     * $outputend
     * </div><br>";
     * } else {
     *
     *
     * $output .= "<div align=center> &nbsp;&nbsp;&nbsp;&nbsp; " . $this->__('No Shops Found') . " </div>";
     * }
     * //"region:" . $region_id . "country:" . $country_id
     * //ZSELEX_Util::ajaxOutput("region: " . $region_id . "country: " . $country_id . "city_id: " .$city_id. "shop_id: " .$shop_id);
     * ZSELEX_Util::ajaxOutput($output);
     * }
     *
     */
    public function getShopDetails1() { // used for getting shop list on front page based on selection
        $shop_id = FormUtil::getPassedValue("shop_id");
        $country_id = FormUtil::getPassedValue("country_id");
        $region_id = FormUtil::getPassedValue("region_id");
        $city_id = FormUtil::getPassedValue("city_id");
        $area_id = FormUtil::getPassedValue("area_id");
        $hsearch = FormUtil::getPassedValue("hsearch");
        $hsearch = ($hsearch == 'search for...') ? '' : $hsearch;
        // echo $hsearch; exit;

        $category_id = FormUtil::getPassedValue("category_id");
        $branch_id = FormUtil::getPassedValue("branch_id");

        $startval = FormUtil::getPassedValue("startval");
        $endval = FormUtil::getPassedValue("endval");
        // $this->setShopIdOnSelections($country_id, $region_id, $city_id, $area_id, $shop_id, $hsearch);

        $searchquery = '';

        if (!empty($hsearch)) {

            // $searchquery = " AND a.shop_name LIKE '%" . DataUtil::formatForStore($hsearch) . "%'";
            $searchquery = " AND a.shop_name LIKE '%" . DataUtil::formatForStore($hsearch) . "%'
                             OR a.shop_id IN (SELECT shop_id FROM zselex_advertise WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_files WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_gallery WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_pdf WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_dotd WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_products WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_news WHERE news_id IN 
                             (SELECT sid FROM news WHERE title LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR hometext LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR bodytext LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR urltitle LIKE '%" . DataUtil::formatForStore($hsearch) . "%'))
            ";
        }

        $catquery = '';
        if ($category_id != '') {
            $catquery = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='CATEGORY'
                AND parentId=$category_id)";
        }

        if ($category_id != '' && $country_id <= 0 && $region_id <= 0 && $city_id <= 0 && $area_id <= 0 && $shop_id <= 0) {
            $catshop = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='CATEGORY'
                AND parentId=$category_id)";
        }

        $branchquery = '';
        $branchshop = '';
        if ($branch_id != '') {
            $branchquery = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='BRANCH'
                AND parentId=$branch_id)";
        }

        if ($branch_id != '' && $country_id <= 0 && $region_id <= 0 && $city_id <= 0 && $area_id <= 0 && $shop_id <= 0) {
            $branchshop = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='BRANCH'
                AND parentId=$branch_id)";
        }

        $searchquerymain = '';
        if (!empty($hsearch) && $country_id <= 0 && $region_id <= 0 && $city_id <= 0 && $area_id <= 0 && $shop_id <= 0) { // when only search is typed
            // $searchquerymain = " AND a.shop_name LIKE '%" . DataUtil::formatForStore($hsearch) . "%'";
            $searchquerymain = " AND a.shop_name LIKE '%" . DataUtil::formatForStore($hsearch) . "%'
                             OR a.shop_id IN (SELECT shop_id FROM zselex_advertise WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_files WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_gallery WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_pdf WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_dotd WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_products WHERE keywords LIKE '%" . DataUtil::formatForStore($hsearch) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_news WHERE news_id IN 
                             (SELECT sid FROM news WHERE title LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR hometext LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR bodytext LIKE '%" . DataUtil::formatForStore($hsearch) . "%' OR urltitle LIKE '%" . DataUtil::formatForStore($hsearch) . "%'))
            ";
        }

        $output = '';

        $items = array(
            'id' => $shop_id
        );
        // $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
        $where = '';

        if (($country_id > 0) or ( $country_id > 0 && $region_id < 0 && $city_id < 0 && $shop_id < 0)) { // COUNTRY
            $where = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP'
        AND parentType='CITY' AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' 
        AND parentType='COUNTRY' AND parentId=$country_id)) $catquery $branchquery $searchquery
        OR 
        a.shop_id IN(select childId from zselex_parent where childType='SHOP' and parentType='REGION' and parentId IN(select childId FROM zselex_parent WHERE childType='REGION' AND parentType='COUNTRY' AND parentId=$country_id)) $catquery $branchquery $searchquery
        OR
        a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' 
        AND parentType='CITY' AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' 
        AND parentType='REGION' AND parentId IN(SELECT
        childId FROM zselex_parent WHERE childType='REGION' AND parentType='COUNTRY' AND parentId=$country_id))) $catquery $branchquery $searchquery
        OR
        a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='COUNTRY' AND parentId=$country_id) $catquery $branchquery $searchquery

      ";
        }

        if (($region_id > 0) or ( $country_id > 0 && $region_id > 0 && $city_id < 0 && $shop_id < 0)) { // REGION
            $where = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP'
                AND parentType='CITY' AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='REGION' 
                AND parentId=$region_id)) $catquery $branchquery $searchquery
                OR
                a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='REGION' AND parentId=$region_id) $catquery $branchquery $searchquery
            ";
        }

        if (($city_id > 0) or ( $region_id > 0 && $city_id > 0 && $country_id > 0 && $shop_id < 0)) { // CITY
            $where = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='CITY' AND parentId=$city_id) $catquery $branchquery $searchquery";
        }

        if (($area_id > 0) or ( $region_id > 0 && $area_id > 0 && $city_id > 0 && $country_id > 0 && $shop_id < 0)) { // AREA
            $where = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='AREA' AND parentId=$area_id) $catquery $branchquery $searchquery";
        }

        if (($shop_id > 0) or ( $shop_id > 0 && $region_id > 0 && $city_id > 0 && $country_id > 0)) { // SHOP
            $where = " AND a.shop_id=$shop_id  $catquery $branchquery $searchquery";
        }

        // echo $where;

        $sql = "SELECT * ,ms.configured as minishop_configured, g.image_name, a.shop_id as SID FROM zselex_shop a
		 LEFT JOIN zselex_serviceshop sv ON sv.shop_id=a.shop_id AND (sv.type='linktoshop')
                 LEFT JOIN zselex_minishop ms ON ms.shop_id=a.shop_id
                 LEFT JOIN zselex_shop_owners ow ON ow.shop_id=a.shop_id
                 LEFT JOIN users u ON u.uid=ow.user_id
                 LEFT JOIN zselex_shop_gallery g ON a.shop_id=g.shop_id AND g.defaultImg=1
		 LEFT JOIN zselex_files b ON a.shop_id=b.shop_id AND b.defaultImg=1
                 WHERE a.shop_id IS NOT NULL AND a.status='1'
                 $where $catshop $branchshop $searchquerymain LIMIT $startval , $endval";

        // echo $sql;

        $query = DBUtil::executeSQL($sql);
        $sValues = $query->fetchAll();

        $count = count($sValues);

        $totalsql = "SELECT * FROM zselex_shop a
                     WHERE a.shop_id IS NOT NULL AND a.status='1'  $where $catshop $branchshop  $searchquerymain";
        $statement = Doctrine_Manager::getInstance()->connection();
        $resultcount = $statement->execute($totalsql);
        $totalCount = $resultcount->rowCount();

        $outputstart = '';
        $outputend = '';

        // $startvals = $startval + 5;

        $startvals = $startval + $endval;
        $outputTotalshops = '';
        if ($totalCount > 0) {
            $outputTotalshops = "<span style='cursor:pointer; float:left;  margin-left:90px;  color:#605d59;'>" . $this->__('Total Shops :') . " $totalCount</span>";
        }
        if ($startval > 0) {
            // $outputstart = "<span onClick=paginatePrev() style='cursor:pointer; float:left; color:blue'>Prev</span>";
            $outputstart = "<span onClick=paginatePrev() style='cursor:pointer; float:left; color:#605d59; font-weight:bold'> <img src='" . pnGetBaseURL() . "images/LtArrow.jpg' style='vertical-align:top; margin-top:1px'>&nbsp; " . $this->__('Prev') . "</span>";
        }

        if ($totalCount > $startvals) {
            // $outputend = "<span onClick=paginateNext() style='cursor:pointer;float:right; color:blue'>Next</span>";
            $outputend = "<span  onClick=paginateNext() style='cursor:pointer;float:right; color:#605d59; font-weight:bold'>" . $this->__('Next') . " &nbsp;<img src='" . pnGetBaseURL() . "images/RtArrow.jpg' style='vertical-align:top; margin-top:1px'></span>";
        }

        $output .= "<div>
           
            $outputstart
            $outputTotalshops
            $outputend
                   </div><br>";

        if ($count > 0) {
            $linkShopStrt = '';
            $linkShopEnd = '';
            $div = '';
            $linkStrt = '';
            $linkEnd = '';
            foreach ($sValues as $shops) {
                $ID = $shops ['SID'];

                $linkSiteStrt = "<a href='" . ModUtil::url('ZSELEX', 'user', 'site', array(
                            'id' => $ID
                        )) . "' target='_blank'>";
                $linkSiteEnd = "</a>";

                if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                    if ($shops ['quantity'] > 0 && $shops ['minishop_configured'] > 0) {
                        $linkShopStrt = "<a style='color:green' href='" . ModUtil::url('ZSELEX', 'user', 'shop', array(
                                    'shop_id' => $ID
                                )) . "' target='_blank'>" . $this->__('Go To Shop') . "";

                        $linkShopEnd = "</a>";
                    } else {
                        $linkShopStrt = '';
                        $linkShopEnd = '';
                    }
                } else {

                    if ($shops ['minishop_configured'] > 0) {
                        $linkShopStrt = "<a style='color:green' href='" . ModUtil::url('ZSELEX', 'user', 'shop', array(
                                    'shop_id' => $ID
                                )) . "' target='_blank'>" . $this->__('Go To Shop') . "";

                        $linkShopEnd = "</a>";
                    } else {
                        $linkShopStrt = '';
                        $linkShopEnd = '';
                    }
                }

                $div = '';
                if ($shops ['default_img_frm'] == 'fromshop') { // minisite shop images
                    if (!empty($shops ['name'])) {
                        $div = "<div>$linkSiteStrt<img src='zselexdata/$shops[uname]/minisiteimages/thumb/$shops[name]'>$linkSiteEnd</div>";
                    } else {
                        $div = '';
                    }
                } elseif ($shops ['default_img_frm'] == 'fromgallery') { // minisite gallery images
                    if (!empty($shops ['image_name'])) {
                        $div = "<div>$linkSiteStrt<img src='zselexdata/$shops[uname]/minisitegallery/thumb/$shops[image_name]'>$linkSiteEnd</div>";
                    } else {
                        $div = '';
                    }
                }

                $output .= "<div style='border:solid 1px #CCC; padding-top:15px; padding-bottom:5px; padding-left:15px; '>
                             $div
                      <div><b>" . $this->__('Shop Name') . "</b>:$linkSiteStrt" . $shops ['shop_name'] . "$linkSiteEnd</div>
                          
                      <div><b>" . $this->__('Address') . "</b>: " . $shops ['address'] . "</div>
                          
                      <div><b>" . $this->__('Telephone') . "</b>: " . $shops ['telephone'] . "</div>
                       
                      <div><b>" . $this->__('Fax') . "</b>: " . $shops ['fax'] . "</div>
                          
                      <div><b>" . $this->__('Email') . "</b>: " . $shops ['email'] . "</div>
                            
                      <div>" . $linkShopStrt . $linkShopEnd . "</div>
           
                
                   </div>
                  ";
            }
            $output .= "<div>
            $outputstart
            
            $outputend
                   </div><br>";
        } else {

            $output .= "<div align=center> &nbsp;&nbsp;&nbsp;&nbsp;  " . $this->__('No Shops Found') . "  </div>";
        }
        // "region:" . $region_id . "country:" . $country_id
        // ZSELEX_Util::ajaxOutput("region: " . $region_id . "country: " . $country_id . "city_id: " .$city_id. "shop_id: " .$shop_id);
        ZSELEX_Util::ajaxOutput($output);
    }

    function getCountryCityList() {
        $country_id = FormUtil::getPassedValue("country_id");
        $sql = "SELECT * FROM zselex_city WHERE city_id IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='REGION'
                 AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='REGION' AND parentType='COUNTRY' AND parentId=$country_id))
                OR
                   city_id IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='COUNTRY' AND parentId=$country_id)
                ";

        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
        $sValues = $results->fetchAll();
        $count = count($sValues);
        $output = '';

        $output .= "<select id=city name=city onChange=getShopFrntend(this.value),getShopDetails(document.getElementById('hshop').value)>";
        if ($count != 0) {
            $output .= "<option value='0'>" . $this->__('select city') . "</option>";
            foreach ($sValues as $row) {
                $output .= "<option value='" . $row ['city_id'] . "'>$row[city_name]</option>";
            }
        } else {
            $output .= "<option value=''>" . $this->__('no cities') . "</option>";
        }
        $output .= "</select>";

        ZSELEX_Util::ajaxOutput($output);
    }

    public function getCountryShopList() {
        $country_id = FormUtil::getPassedValue("country_id");

        if (!empty($country_id)) {
            $sql = "SELECT * FROM zselex_shop WHERE shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP'
        AND parentType='CITY' AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' 
        AND parentType='COUNTRY' AND parentId=$country_id))
        OR 
        shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' 
        AND parentType='CITY' AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' 
        AND parentType='REGION' AND parentId IN(SELECT
        childId FROM zselex_parent WHERE childType='REGION' AND parentType='COUNTRY' AND parentId=$country_id)))
        OR
        shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='COUNTRY' AND parentId=$country_id)";
        } else {

            $sql = "SELECT * FROM zselex_shop";
        }

        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
        $sValues = $results->fetchAll();

        $count = count($sValues);
        $output = '';

        $output .= "<select id=shop-combo name=shop>";
        if ($count != 0) {

            $output .= "<option value='0'>" . $this->__('search shop') . "</option>";
            foreach ($sValues as $row) {

                $output .= "<option value='" . $row ['shop_id'] . "'>" . $row [shop_name] . "</option>";
            }
        } else {
            $output .= "<option value=''>" . $this->__('search shop') . "</option>";
        }

        $output .= "</select>";

        ZSELEX_Util::ajaxOutput($output);
    }

    function getRegionShopList() {
        $region_id = FormUtil::getPassedValue("region_id");

        $country_id = FormUtil::getPassedValue("country_id");

        if ($region_id > 0) {
            $sql = "SELECT * FROM zselex_shop WHERE shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP'
                AND parentType='CITY' AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='REGION' 
                AND parentId=$region_id))
                OR
                shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='REGION' AND parentId=$region_id)
            ";
        } elseif ($region_id < 0 && $country_id > 0) {

            $sql = "SELECT * FROM zselex_shop WHERE shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP'
        AND parentType='CITY' AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' 
        AND parentType='COUNTRY' AND parentId=$country_id))
        OR 
        shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' 
        AND parentType='CITY' AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' 
        AND parentType='REGION' AND parentId IN(SELECT
        childId FROM zselex_parent WHERE childType='REGION' AND parentType='COUNTRY' AND parentId=$country_id)))
        OR
        shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='COUNTRY' AND parentId=$country_id)";
        } else {
            $sql = "SELECT * FROM zselex_shop";
        }
        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
        $sValues = $results->fetchAll();

        $count = count($sValues);
        $output = '';

        $output .= "<select id=shop-combo name=shop>";
        if ($count != 0) {

            $output .= "<option value='0'>" . $this->__('search shop') . "</option>";
            foreach ($sValues as $row) {

                $output .= "<option value='" . $row ['shop_id'] . "'>" . $row [shop_name] . "</option>";
            }
        } else {
            $output .= "<option value=''>" . $this->__('search shop') . "</option>";
        }

        $output .= "</select>";

        ZSELEX_Util::ajaxOutput($output);
    }

    public function getAdDetails() {

        // echo "hiii"; exit;
        $shop_id = FormUtil::getPassedValue("shop_id");
        $country_id = FormUtil::getPassedValue("country_id");
        $region_id = FormUtil::getPassedValue("region_id");
        $city_id = FormUtil::getPassedValue("city_id");
        $category_id = FormUtil::getPassedValue("category_id");
        $adtype = FormUtil::getPassedValue("adtype");

        $catquery = '';
        $catshop = '';
        if ($category_id != '') {

            $catquery = " AND shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='CATEGORY'
                AND parentId=$category_id)";
        }

        if ($category_id != '' && $country_id <= 0 && $region_id <= 0 && $city_id <= 0 && $shop_id <= 0) {

            $catshop = " AND shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='CATEGORY'
                AND parentId=$category_id)";
        }

        $adquery = '';

        if ($adtype == 'Low') {
            $adquery = " AND (shop_id IN (SELECT childId FROM zselex_parent a, zselex_advertise b,zselex_advertise_price c
                WHERE a.childType='SHOP' AND a.parentType='AD' AND a.parentId=b.advertise_id AND b.adprice_id=c.adprice_id 
                AND c.adprice_id=2)
                
                OR

                shop_id IN (SELECT parentId FROM zselex_parent a, zselex_advertise b,zselex_advertise_price c
                WHERE a.childType='AD' AND a.parentType='SHOP' AND a.childId=b.advertise_id AND b.adprice_id=c.adprice_id 
                AND c.adprice_id=2))
                

                ";
        }

        $items = array(
            'id' => $shop_id
        );
        // $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
        $where = '';

        if (($country_id > 0) or ( $country_id > 0 && $region_id < 0 && $city_id < 0 && $shop_id < 0)) { // COUNTRY
            $where = " WHERE shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP'
        AND parentType='CITY' AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' 
        AND parentType='COUNTRY' AND parentId=$country_id)) AND shoptype_id=1 $catquery $adquery
        OR 
        shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' 
        AND parentType='CITY' AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' 
        AND parentType='REGION' AND parentId IN(SELECT
        childId FROM zselex_parent WHERE childType='REGION' AND parentType='COUNTRY' AND parentId=$country_id))) AND shoptype_id=1 $catquery $adquery
        OR
        shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='COUNTRY' AND parentId=$country_id) AND shoptype_id=1 $catquery $adquery

      ";
        }

        if (($region_id > 0) or ( $country_id > 0 && $region_id > 0 && $city_id < 0 && $shop_id < 0)) { // REGION
            $where = " WHERE shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP'
                AND parentType='CITY' AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='REGION' 
                AND parentId=$region_id)) AND shoptype_id=1 $catquery $adquery
                OR
                shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='REGION' AND parentId=$region_id)  AND shoptype_id=1  $catquery $adquery
            ";
        }

        if (($city_id > 0) or ( $region_id > 0 && $city_id > 0 && $country_id > 0 && $shop_id < 0)) { // CITY
            $where = " WHERE shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='CITY' AND parentId=$city_id)  AND shoptype_id=1  $catquery $adquery";
        }

        if (($shop_id > 0) or ( $shop_id > 0 && $region_id > 0 && $city_id > 0 && $country_id > 0)) { // SHOP
            $where = " WHERE shop_id=$shop_id   AND shoptype_id=1  $catquery $adquery";
        }

        if (($shop_id < 0 || empty($shop_id)) && ($region_id < 0 || empty($region_id)) && ($city_id < 0 || empty($city_id)) && ($country_id < 0 || empty($country_id))) {
            $where = " WHERE shoptype_id=1 $adquery";
        }

        $sql = "SELECT * FROM zselex_shop $where $catshop";

        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
        $configs = $results->fetchAll();

        $counts = $results->rowCount();
        // echo "<pre>"; print_r($values); echo "</pre>";
        // echo $config['dbname'];

        $allValues = array();
        if ($counts > 0) {
            $limit = 2;

            foreach ($configs as $config) {
                $dnName = (!empty($config ['dbname']) ? $config ['dbname'] : 'nodb');
                $dnUser = (!empty($config ['username']) ? $config ['username'] : 'root');
                $dbPswrd = (!empty($config ['password']) ? $config ['password'] : '');
                $dbHost = (!empty($config ['hostname']) ? $config ['hostname'] : 'localhost');

                // $dsn = "mysql:dbname='" . $dnName . "';host='" . $dbHost . "'";
                // echo $dsn; exit;

                $dsn = "mysql:dbname=$dnName;host=$dbHost";
                $user = $dnUser;
                $password = $dbPswrd;
                $tableprefix = (!empty($config ['table_prefix']) ? $config ['table_prefix'] : '');

                $prdwhere = "b.products_name!='' AND a.products_image!='' AND a.manufacturers_id!=''";
                $prdwhere = "a.products_status=1";
                $prdquery = "SELECT a.products_id , a.products_image , a.products_price , LEFT(b.products_name, 20) AS products_name  , LEFT(b.products_description, 20) AS products_description,
                         mn.manufacturers_name
                         FROM  " . $tableprefix . "products a 
                         LEFT JOIN " . $tableprefix . "products_description b ON b.products_id=a.products_id
                         LEFT JOIN " . $tableprefix . "manufacturers mn ON mn.manufacturers_id=a.manufacturers_id
                         WHERE " . $prdwhere . "
                         ORDER BY RAND()  LIMIT  0,$limit";

                $dbh = new PDO($dsn, $user, $password);
                $statement1 = Doctrine_Manager::getInstance()->connection($dbh);
                $results = $statement1->execute($prdquery);
                $sValues = $results->fetchAll();
                // echo "<pre>"; print_r($sValues); echo "</pre>";
                // $imagearr = array('imageval'=>'lower');
                $list = array();
                for ($i = 0; $i < count($sValues); $i ++) {
                    $sValues [$i] ['domainname'] = $config ['domain'];

                    $priceexplode = explode('.', $sValues [$i] ['products_price']);

                    if (strlen($priceexplode [0]) >= 4) {
                        $p1 = substr_replace($priceexplode [0], ".", 1, 0);
                        $p2 = substr_replace($priceexplode [1], ",", 2);
                        $p2 = substr($p2, 0, - 1);

                        $sValues [$i] ['PRICE'] = $p1 . ',' . $p2;
                    } else {
                        $newstring = substr_replace($priceexplode [1], '', '2');
                        $sValues [$i] ['PRICE'] = $priceexplode [0] . ',' . $newstring;
                    }

                    // echo $sValues[$i]['PRICE'] . '<br>';

                    if ($sValues [$i] ['products_image'] != '') {
                        list ( $width, $height, $type, $attr ) = getimagesize('http://' . $config ['domain'] . '/images/' . str_replace(" ", "%20", $sValues [$i] ['products_image']));
                        $AW = $width;
                        $AH = $height;

                        $H = '';
                        $W = '';

                        if ($AH < 210 && $AW < 170) {
                            
                        }

                        if ($AH > 210 && $AW < 170) {
                            $H = 210;
                            $W = $AW * ((210 * 100) / $AH) / 100;
                            $sValues [$i] ['H'] = round($H);
                            $sValues [$i] ['W'] = round($W);
                        }

                        if ($AH < 210 && $AW > 170) {
                            $W = 170;
                            $H = $AH * ((170 * 100) / $AW) / 100;
                            $sValues [$i] ['H'] = round($H);
                            $sValues [$i] ['W'] = round($W);
                        }
                        if ($AH > 210 && $AW > 170) {
                            $H = 210;
                            $W = $AW * ((210 * 100) / $AH) / 100;
                            $WTmp = $W;
                            if ($W > 170) {
                                $W = 170;
                                $H = $H * ((170 * 100) / $WTmp) / 100;
                            }
                            $sValues [$i] ['H'] = round($H);
                            $sValues [$i] ['W'] = round($W);
                        }
                    }
                }

                $allValues [] = $sValues;
                // return $sValues;
                // echo "<pre>"; print_r($sValues); echo "</pre>"; exit;
                $statement1 = Doctrine_Manager::getInstance()->closeConnection($statement1);
            } // /

            $aItem = array();
            foreach ($allValues as $productz) {
                foreach ($productz as $item) {
                    $aItem [] = $item;
                }
            }
            $prodval = $this->array_random_assoc($aItem, $num = 1);
            // return $allValues;
            $output = '';
            $output .= "<dl>";

            foreach ($prodval as $product) {
                $imageprop = "width=170px";
                if ($product ['H'] != '') {

                    $imageprop = "height='" . $product ['H'] . "'  width='" . $product ['W'] . "'";
                }
                $domain = $config ['domain'];

                $output .= "<div style='float:left; width:170px; margin-left:15px; display:table-cell;'>
                                <dt class='P1'>
                                   <a href='http://$product[domainname]/index.php?main_page=product_info&products_id=$product[products_id]' target='_blank'> <img src='http://$product[domainname]/images/$product[products_image]' $imageprop   /></a>
                                </dt>
                                <div class='P1T'>
                                 <span class='phd'>$product[manufacturers_name]</span>
                                  <br>
                                <span class='phd1'>$product[products_name]...</span>
                                </div>
                                <div class='P1TBox'>
                                <span class='amount'><b>" . $this->__('DKK') . "</b> $product[PRICE]</span>
                                <div class='add'>
                                 <a href='javascript:document.cart_quantity$product[products_id].submit()'  class='addbutton'><img src='" . $themepath . "/images/add.jpg' />
                                  <span><img src='" . $themepath . "/images/mouseoverAdd1.png'><p>" . $this->__('Add to Cart') . "</p></span>
                                 </a>
                                  <form name='cart_quantity$product[products_id]' action='http://$product[domainname]/index.php?main_page=product_info&action=add_product&products_id=$product[products_id]' method='post' enctype='multipart/form-data' target='_blank'><input type='hidden' name='cart_quantity' value='1' /><input type='hidden' name='products_id' value='$product[products_id]' /></form>
                               </div>
                               </div>
                           </div>";
            }
        } else {

            $output .= '<dt>' . $this->__('No Records Found') . '</dt>';
        }

        $output .= '</dl>';
        ZSELEX_Util::ajaxOutput($output);
    }

    function array_random_assoc($arr, $num) {
        $keys = array_keys($arr);
        shuffle($keys);

        $r = array();
        $j = 1;
        for ($i = 0; $i <= $num; $i ++) {
            $r [$keys [$i]] = $arr [$keys [$i]];
            if ($j == $num) {
                break;
            }
            $j ++;
        }
        return $r;
    }

    function array_random_assoc1($arr, $num) {
        $keys = array_keys($arr);
        shuffle($keys);

        $r = array();
        $i = 0;
        foreach ($arr as $values) {
            $r [$keys [$i]] = $values;
            $i ++;
        }
        return $r;
    }

    public function getAdDetails1() { // For Products Advertisement Block
        // exit;
        $shop_id = FormUtil::getPassedValue("shop_id");
        $country_id = FormUtil::getPassedValue("country_id");
        $region_id = FormUtil::getPassedValue("region_id");
        $city_id = FormUtil::getPassedValue("city_id");
        $area_id = FormUtil::getPassedValue("area_id");
        $category_id = FormUtil::getPassedValue("category_id");
        $branch_id = FormUtil::getPassedValue("branch_id");
        $adtype = FormUtil::getPassedValue("adtype");
        $amount = FormUtil::getPassedValue("amount");
        $search = FormUtil::getPassedValue("hsearch");
        $search = ($search == $this->__('search for...') || $search == $this->__('search')) ? '' : $search;
        // $themepath = $this->current_theme;

        $append = '';

        // echo "<pre>"; print_r($_SESSION['shoparray']); echo "</pre>"; exit;
        // AjaxUtil::output($amount); exit;
        $counts = '';
        if (($shop_id > 0 || !empty($shop_id)) || ($region_id > 0 || !empty($region_id)) || ($city_id > 0 || !empty($city_id)) || ($country_id > 0 || !empty($country_id)) || ($area_id > 0 || !empty($area_id)) || ($search > 0 || !empty($search)) || ($category_id > 0 || !empty($category_id)) || ($branch_id > 0 || !empty($branch_id))) {
            $catquery = '';
            $catshop = '';

            if ($category_id != '' && $country_id <= 0 && $region_id <= 0 && $city_id <= 0 && $area_id <= 0 && $shop_id <= 0) { // Only category is selected
                $catshop = " AND a.cat_id=$category_id";
            }

            $branchquery = '';
            $branchshop = '';

            if ($branch_id != '' && $country_id <= 0 && $region_id <= 0 && $city_id <= 0 && $area_id <= 0 && $shop_id <= 0) { // Only branch is selected
                $branchshop = " AND a.branch_id=$branch_id";
            }

            $searchquery = '';

            $searchquerymain = '';
            if (!empty($search) && $country_id <= 0 && $region_id <= 0 && $city_id <= 0 && $area_id <= 0 && $shop_id <= 0) { // Only search is typed
                $searchquerymain = " AND b.keywords LIKE '%" . DataUtil::formatForStore($search) . "%'";
            }

            if (!empty($category_id)) {
                $append .= " AND a.cat_id=$category_id";
            }

            if (!empty($branch_id)) {
                $append .= " AND a.branch_id=$branch_id";
            }

            if (!empty($search)) { // for search with others
                $append .= " AND b.keywords LIKE '%" . DataUtil::formatForStore($search) . "%'";
            }

            $adquery = '';
            if ($adtype != '') {
                // $adquery = " AND
                //
				// shop_id IN (SELECT a.parentId FROM zselex_parent a, zselex_advertise b,zselex_advertise_price c
                // WHERE a.childType='AD' AND a.parentType='SHOP' AND a.childId=b.advertise_id AND b.adprice_id=c.adprice_id
                // AND c.adprice_id=$adtype)
                //
				//
				// ";
                // $adquery = " AND
                // shop_id IN (SELECT shop_id FROM zselex_advertise a , zselex_advertise_price b WHERE
                // a.adprice_id = b.adprice_id AND a.adprice_id=$adtype
                // )
                // ";
                // $adquery = " AND b.adprice_id=$adtype";
                $append .= " AND b.adprice_id=$adtype";
            }

            $items = array(
                'id' => $shop_id
            );
            // $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
            $where = '';

            if (($country_id > 0) or ( $country_id > 0 && $region_id < 0 && $city_id < 0 && $shop_id < 0)) { // COUNTRY
                $where = " AND b.country_id=$country_id AND b.level='COUNTRY' AND a.shop_id!=''  $append";
            }

            if (($region_id > 0) or ( $country_id > 0 && $region_id > 0 && $city_id < 0 && $shop_id < 0)) { // REGION
                $where = " AND b.region_id=$region_id AND b.level='REGION' AND a.shop_id!=''  $append";
            }

            if (($city_id > 0) or ( $region_id > 0 && $city_id > 0 && $country_id > 0 && $shop_id < 0)) { // CITY
                $where = " AND b.city_id=$city_id AND b.level='CITY' AND a.shop_id!=''  $append";
            }

            if (($area_id > 0) or ( $region_id > 0 && $area_id > 0 && $city_id > 0 && $country_id > 0 && $shop_id < 0)) { // AREA
                $where = " AND b.area_id=$area_id AND b.level='AREA' AND a.shop_id!=''  $append";
            }

            if (($shop_id > 0) or ( $shop_id > 0 && $region_id > 0 && $city_id > 0 && $country_id > 0)) { // SHOP
                $where = " AND b.shop_id=$shop_id AND b.level='SHOP' AND a.shop_id!=''  $append";
            }

            if (($shop_id < 0 || empty($shop_id)) && ($region_id < 0 || empty($region_id)) && ($city_id < 0 || empty($city_id)) && ($country_id < 0 || empty($country_id)) && ($area_id < 0 || empty($area_id))) {
                // $where = " WHERE a.shop_id!='' $adquery $catquery $branchquery $searchquery";
            }

            $sql = "SELECT a.* , b.* , m.* FROM zselex_shop a , zselex_advertise b , zselex_minishop m
                    WHERE a.shop_id=b.shop_id
                    $where $catshop $branchshop $searchquerymain
                    AND a.shop_id=m.shop_id
                    AND b.maxviews > b.totalviews AND  b.maxclicks > b.totalclicks AND b.startdate<=CURDATE() AND b.enddate>=CURDATE() 
                    AND b.advertise_type='productAd'";

            // echo $sql;
            // $output["data"] = $sql;
            // AjaxUtil::output($output);

            $statement = Doctrine_Manager::getInstance()->connection();
            $results = $statement->execute($sql);
            $configs = $results->fetchAll();
            // echo "<pre>"; print_r($configs); echo "</pre>";
            $counts = $results->rowCount();
            // echo "<pre>"; print_r($configs[shop_id]); echo "</pre>";
            // echo $config['dbname'];
        } else {
            $counts = 0;
        }

        $allValues = array();
        $imageShow = '';
        $limit = '';
        if ($counts > 0) {
            // $limit = 2;
            if (!empty($amount)) {
                // $limit = $amount + 1;
                $limit = $amount;
            } else {
                $limit = "2";
            }

            foreach ($configs as $config) {

                // $shopType = $config['shoptype_id'];
                $shopType = $config ['shoptype'];
                $shopsId = $config ['shop_id'];

                if ($shopType == 'zSHOP') { // ZEN-CART
                    $zShop = DBUtil::selectObjectByID('zselex_zenshop', $shopsId, 'shop_id');

                    $dnName = (!empty($zShop ['dbname']) ? $zShop ['dbname'] : '');
                    $dnUser = (!empty($zShop ['username']) ? $zShop ['username'] : 'root');
                    $dbPswrd = (!empty($zShop ['password']) ? $zShop ['password'] : '');
                    $dbHost = (!empty($zShop ['hostname']) ? $zShop ['hostname'] : 'localhost');

                    // $dsn = "mysql:dbname='" . $dnName . "';host='" . $dbHost . "'";
                    // echo $dsn; exit;

                    $dsn = "mysql:dbname=$dnName;host=$dbHost";
                    $user = $dnUser;
                    $password = $dbPswrd;
                    $tableprefix = (!empty($zShop ['table_prefix']) ? $zShop ['table_prefix'] : '');

                    $prdwhere = "b.products_name!='' AND a.products_image!='' AND a.manufacturers_id!=''";
                    $prdwhere = "a.products_status=1";
                    $prdquery = "SELECT a.products_id , a.products_image , a.products_price , LEFT(b.products_name, 20) AS products_name  , LEFT(b.products_description, 20) AS products_description,
                                    mn.manufacturers_name
                                    FROM  " . $tableprefix . "products a 
                                    LEFT JOIN " . $tableprefix . "products_description b ON b.products_id=a.products_id
                                    LEFT JOIN " . $tableprefix . "manufacturers mn ON mn.manufacturers_id=a.manufacturers_id
                                    WHERE " . $prdwhere . "
                                    group by a.products_id 
                                    ORDER BY RAND()  LIMIT  0,$limit";

                    $dbh = new PDO($dsn, $user, $password);
                    $statement1 = Doctrine_Manager::getInstance()->connection($dbh);
                    $results = $statement1->execute($prdquery);
                    $sValues = $results->fetchAll();
                    // echo "<pre>"; print_r($sValues); echo "</pre>";
                    // $imagearr = array('imageval'=>'lower');
                    $list = array();
                    for ($i = 0; $i < count($sValues); $i ++) {
                        $sValues [$i] ['domainname'] = $zShop ['domain'];
                        $sValues [$i] ['adId'] = $config ['advertise_id'];
                        $sValues [$i] ['maxviews'] = $config ['maxviews'];
                        $sValues [$i] ['totalviews'] = $config ['totalviews'];
                        $sValues [$i] ['maxclicks'] = $config ['totalclicks'];
                        $sValues [$i] ['SHOPTYPE'] = $shopType;

                        $priceexplode = explode('.', $sValues [$i] ['products_price']);
                        if (strlen($priceexplode [0]) >= 4) { // more than 1000
                            $p1 = substr_replace($priceexplode [0], ".", 1, 0);
                            $p2 = substr_replace($priceexplode [1], ",", 2);
                            $p2 = substr($p2, 0, - 1);

                            $sValues [$i] ['PRICE'] = $p1 . ',' . $p2;
                        } else {
                            $newstring = substr_replace($priceexplode [1], '', '2');
                            $sValues [$i] ['PRICE'] = $priceexplode [0] . ',' . $newstring;
                        }

                        // echo $sValues[$i]['PRICE'] . '<br>';

                        if ($sValues [$i] ['products_image'] != '') {
                            list ( $width, $height, $type, $attr ) = @getimagesize('http://' . $zShop ['domain'] . '/images/' . str_replace(" ", "%20", $sValues [$i] ['products_image']));
                            $AW = $width;
                            $AH = $height;
                            $H = '';
                            $W = '';

                            if ($AH < 210 && $AW < 170) {
                                
                            }
                            if ($AH > 210 && $AW < 170) {

                                $H = 210;
                                $W = $AW * ((210 * 100) / $AH) / 100;

                                $sValues [$i] ['H'] = round($H);
                                $sValues [$i] ['W'] = round($W);
                            }
                            if ($AH < 210 && $AW > 170) {

                                $W = 170;
                                $H = $AH * ((170 * 100) / $AW) / 100;
                                $sValues [$i] ['H'] = round($H);
                                $sValues [$i] ['W'] = round($W);
                            }
                            if ($AH > 210 && $AW > 170) {

                                $H = 210;
                                $W = $AW * ((210 * 100) / $AH) / 100;

                                $WTmp = $W;
                                if ($W > 170) {
                                    $W = 170;
                                    $H = $H * ((170 * 100) / $WTmp) / 100;
                                }

                                $sValues [$i] ['H'] = round($H);
                                $sValues [$i] ['W'] = round($W);
                            }
                        }
                    }

                    $allValues [] = $sValues;
                    // return $sValues;
                    // echo "<pre>"; print_r($sValues); echo "</pre>"; exit;
                    $statement1 = Doctrine_Manager::getInstance()->closeConnection($statement1);
                }  // /
                else if ($shopType == 'iSHOP') { // INTERNAL-SHOP
                    $iprdctQry = "SELECT p.* , LEFT(p.prd_description, 20) AS prd_description , s.theme AS shopTheme , u.uname
                        FROM zselex_products p , zselex_shop s 
                        LEFT JOIN zselex_shop_owners ow ON ow.shop_id=s.shop_id
                        LEFT JOIN users u ON u.uid = ow.user_id
                        LEFT JOIN zselex_serviceshop sv ON sv.shop_id = s.shop_id AND sv.type='paybutton'
                        WHERE p.shop_id='" . $shopsId . "' AND p.shop_id=s.shop_id ORDER BY RAND()  LIMIT 0 , $limit";
                    $statement = Doctrine_Manager::getInstance()->connection();
                    $results = $statement->execute($iprdctQry);
                    $iproducts = $results->fetchAll();

                    // $output["data"] = $iprdctQry;
                    // AjaxUtil::output($output);

                    for ($i = 0; $i < count($iproducts); $i ++) {
                        $iproducts [$i] ['adId'] = $config ['advertise_id'];
                        $iproducts [$i] ['products_name'] = $iproducts [$i] ['product_name'];
                        $iproducts [$i] ['products_id'] = $iproducts [$i] ['product_id'];
                        $iproducts [$i] ['products_image'] = $iproducts [$i] ['prd_image'];
                        $iproducts [$i] ['PRICE'] = $iproducts [$i] ['prd_price'];
                        $iproducts [$i] ['SHOPTYPE'] = $shopType;
                        $iproducts [$i] ['SHOPID'] = $shopsId;
                        $iproducts [$i] ['THEME'] = $iproducts [$i] ['shopTheme'];

                        if ($iproducts [$i] ['products_image'] != '') {
                            // echo "helloo";
                            // ZSELEX_Util::ajaxOutput(pnGetBaseURL()); exit;
                            list ( $width, $height, $type, $attr ) = @getimagesize(pnGetBaseURL() . 'zselexdata/' . $iproducts [$i] ['uname'] . '/products/' . str_replace(" ", "%20", $iproducts [$i] ['products_image']));
                            $AW = $width;
                            $AH = $height;
                            $H = '';
                            $W = '';
                            if ($AH < 210 && $AW < 170) {
                                
                            }
                            if ($AH > 210 && $AW < 170) {
                                $H = 210;
                                $W = $AW * ((210 * 100) / $AH) / 100;
                                $iproducts [$i] ['H'] = round($H);
                                $iproducts [$i] ['W'] = round($W);
                            }
                            if ($AH < 210 && $AW > 170) {
                                $W = 170;
                                $H = $AH * ((170 * 100) / $AW) / 100;
                                $iproducts [$i] ['H'] = round($H);
                                $iproducts [$i] ['W'] = round($W);
                            }
                            if ($AH > 210 && $AW > 170) {
                                $H = 210;
                                $W = $AW * ((210 * 100) / $AH) / 100;
                                $WTmp = $W;
                                if ($W > 170) {
                                    $W = 170;
                                    $H = $H * ((170 * 100) / $WTmp) / 100;
                                }
                                $iproducts [$i] ['H'] = round($H);
                                $iproducts [$i] ['W'] = round($W);
                            }
                        }
                    }
                    $allValues [] = $iproducts;
                }
                // echo "<pre>"; print_r($allValues); echo "</pre>";
            } // /////

            $aItem = array();
            foreach ($allValues as $productz) {
                foreach ($productz as $item) {
                    $aItem [] = $item;
                }
            }

            // AjaxUtil::output("amount: " . $amount); exit;
            // AjaxUtil::output("count: " . count($aItem)); exit;
            $number = '';
            if (count($aItem) <= $amount) {
                $number = count($aItem);
            } else {
                $number = $amount;
            }
            // AjaxUtil::output("number :" . $number); exit;

            $prodval = $this->array_random_assoc($aItem, $num = $number);
            // echo "<pre>"; print_r($prodval); echo "</pre>";
            $prodval = array_filter($prodval);
            // return $allValues;
            $output = '';
            $data .= "<dl>";
            $test = '';
            // $test .= $prodval[];
            $counter = 0;
            $var = '';
            $adss = '';
            // $imageShow = '';
            foreach ($prodval as $product) {

                $ads = $product ['adId'];
                if ($var != $ads || $var == '') {
                    $adss = $product ['adId'];
                    // $test .= $adss;
                    $sqlad = "select totalviews from zselex_advertise where advertise_id=$adss";
                    $statement = Doctrine_Manager::getInstance()->connection();
                    $results = $statement->execute($sqlad);
                    $advalue = $results->fetch();
                    $adview = $advalue ['totalviews'] + 1;

                    $sqladupdate = "update zselex_advertise set totalviews=$adview where advertise_id=$adss";
                    $statement = Doctrine_Manager::getInstance()->connection();
                    $results = $statement->execute($sqladupdate);
                    // $test .= $adview;
                }
                $var = $ads;
                $imageprop = "width=170px";
                if ($product ['H'] != '') {
                    $imageprop = "height='" . $product ['H'] . "'  width='" . $product ['W'] . "'";
                }
                $domain = $config ['domain'];
                // echo $themepath; exit;
                if ($product ['SHOPTYPE'] == 'zSHOP') { // ZEN-CART
                    $imageShow = "<a href='http://$product[domainname]/index.php?main_page=product_info&products_id=$product[products_id]' target='_blank'> <img src='http://$product[domainname]/images/$product[products_image]' $imageprop onClick='insertAdClick($product[adId])'/></a>";
                } else if ($product ['SHOPTYPE'] == 'iSHOP') { // INTERNAL-SHOP
                    // $imageShow = "<a href='" . pnGetBaseURL() . "index.php?theme=$product[THEME]&module=zselex&type=user&func=productview&id=$product[products_id]' target='_blank'><img src=" . pnGetBaseURL() . "zselexdata/products/thumbs/$product[products_image] /></a>";
                    $imageShow = "<a href=" . ModUtil::url('ZSELEX', 'user', 'productview', array(
                                'id' => $product [products_id]
                            )) . " target='_blank'><img src=" . pnGetBaseURL() . "zselexdata/" . $product [uname] . "/products/thumb/$product[products_image] $imageprop onClick='insertAdClick($product[adId])' /></a>";
                }
                // echo $this->current_theme; exit;
                // if ($this->current_theme != 'CityPilot') {
                if ($product ['SHOPTYPE'] == 'zSHOP') { // ZEN-CART
                    $data .= "<div class='productitem'>
        <dt class='P1'>
        $imageShow
        </dt>
        <div class='P1T'>
            <span class='phd'>$product[manufacturers_name]</span>
            <br>
            <span class='phd1'>$product[products_name]...</span>
        </div>
        <div class='P1TBox'>
            <span class='amount'><b>" . $this->__('DKK') . "</b> $product[PRICE]</span>
            <div class='add'>
                <a href='javascript:document.cart_quantity$product[products_id].submit()'  class='addbutton'><img src='" . $themepath . "/images/add.jpg' />
                    <span><img src='" . $themepath . "/images/mouseoverAdd1.png'><p>" . $this->__('Add to Cart') . "</p></span>
                </a>
                <form name='cart_quantity$product[products_id]' action='http://$product[domainname]/index.php?main_page=product_info&action=add_product&products_id=$product[products_id]' method='post' enctype='multipart/form-data' target='_blank'><input type='hidden' name='cart_quantity' value='1' /><input type='hidden' name='products_id' value='$product[products_id]' /></form>
            </div>
        </div>
    </div>";
                } else if ($product ['SHOPTYPE'] == 'iSHOP') { // INTERNAL-SHOP
                    $data .= "<div class='productitem'>
        <dt class='P1'>
        $imageShow
        </dt>
        <div class='P1T'>
            <span class='phd'>$product[products_name]</span>
            <br>
            <span class='phd1'>$product[prd_description]...</span>
        </div>
        <div class='P1TBox'>
            <span class='amount'><b>" . $this->__('DKK') . "</b> $product[PRICE]</span>
            <div class='add'>
                <a href='javascript:document.cartform$product[products_id].submit()'  class='addbutton'><img src='" . $themepath . "/images/add.jpg' />
                    <span><img src='" . $themepath . "/images/mouseoverAdd1.png'><p>" . $this->__('Add to Cart') . "</p></span>
                </a>
                <form name='cartform$product[products_id]' action='" . ModUtil::url('ZSELEX', 'user', 'cart') . "' method='post' enctype='multipart/form-data' target='_blank'>
                    <input type='hidden' name='product_id' value='$product[products_id]' />
                    <input type='hidden' name='productName' value='$product[products_name]' />
                    <input type='hidden' name='cart_quantity' value='1' />
                    <input type='hidden' name='product_price' value='$product[PRICE]' />
                    <input type='hidden' name='productImage' value='$product[prd_image]'>
                    <input type='hidden' name='productDesc' value='$product[prd_description]' >
                    <input type='hidden' name='shop_id' value='$product[SHOPID]' >
                    <input type='hidden' name='shopUser' value='$product[uname]' >
                    <input type='hidden' name='service' value='$product[type]' >
                 </form>
            </div>
        </div>
    </div>";
                }
                // }

                $counter ++;
            }
        } else {

            $data .= '<dt> &nbsp;&nbsp;&nbsp;&nbsp;  No Ads Found  </dt>';
        }
        $data .= '</dl>';
        // ZSELEX_Util::ajaxOutput($output);
        $output ["data"] = $data;
        AjaxUtil::output($output);
    }

    public function getSpecialDealEvents() {
        // exit;
        $level = FormUtil::getPassedValue("level");
        $shop_id = FormUtil::getPassedValue("shop_id");
        $country_id = FormUtil::getPassedValue("country_id");
        $region_id = FormUtil::getPassedValue("region_id");
        $city_id = FormUtil::getPassedValue("city_id");
        $area_id = FormUtil::getPassedValue("area_id");
        $branch_id = FormUtil::getPassedValue("branch_id");
        $category_id = FormUtil::getPassedValue("category_id");
        $search = FormUtil::getPassedValue("hsearch");
        $search = ($search == $this->__('search for...') || $search == $this->__('search')) ? '' : $search;
        $eventlimit = FormUtil::getPassedValue("eventlimit");

        $append = '';
        if (!empty($eventlimit)) {
            $limit = $eventlimit;
            $limitquery = "LIMIT 0 , $eventlimit";
        } else {
            $limit = "2";
            $limitquery = "LIMIT 0 , 2";
        }

        $searchquery = '';
        if (($shop_id > 0 || !empty($shop_id)) || ($region_id > 0 || !empty($region_id)) || ($city_id > 0 || !empty($city_id)) || ($country_id > 0 || !empty($country_id)) || ($area_id > 0 || !empty($area_id)) || ($search > 0 || !empty($search)) || ($category_id > 0 || !empty($category_id)) || ($branch_id > 0 || !empty($branch_id) || !empty($eventdate))) {
            $eventdateqry = '';
            if (!empty($eventdate)) {
                $eventdateqry = " AND shop_event_startdate='" . $eventdate . "'";
            }

            $output = '';

            $items = array(
                'id' => $shop_id
            );
            // $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
            $where = '';

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

                $append .= " AND a.shop_name LIKE '%" . DataUtil::formatForStore($search) . "%'
                             OR a.shop_id IN (SELECT shop_id FROM zselex_advertise WHERE keywords LIKE '%" . DataUtil::formatForStore($search) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_files WHERE keywords LIKE '%" . DataUtil::formatForStore($search) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_gallery WHERE keywords LIKE '%" . DataUtil::formatForStore($search) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_pdf WHERE keywords LIKE '%" . DataUtil::formatForStore($search) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_dotd WHERE keywords LIKE '%" . DataUtil::formatForStore($search) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_products WHERE keywords LIKE '%" . DataUtil::formatForStore($search) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_news WHERE news_id IN 
                             (SELECT sid FROM news WHERE title LIKE '%" . DataUtil::formatForStore($search) . "%' OR hometext LIKE '%" . DataUtil::formatForStore($search) . "%' OR bodytext LIKE '%" . DataUtil::formatForStore($search) . "%' OR urltitle LIKE '%" . DataUtil::formatForStore($search) . "%'))
            ";
            }

            $sql = "SELECT a.shop_id FROM zselex_shop a
                     WHERE a.shop_id IS NOT NULL AND a.status='1'  $append";
            // echo $sql;
            $query = DBUtil::executeSQL($sql);
            $result = $query->fetchAll();

            $count = count($result);
        } else {

            $count = 0;
        }
        if ($count > 0) {
            foreach ($result as $shopid) {
                $shop_idarray [] = $shopid ['shop_id'];
            }

            $shop_ids = implode(",", $shop_idarray);
            // foreach ($result as $shop) {
            $sqlevents = "SELECT *, shop_event_id , shop_id , shop_event_startdate , LEFT(shop_event_name, 20) AS event_name  ,   LEFT(shop_event_description, 20) AS event_description  ,   LEFT(shop_event_shortdescription, 20) AS event_shortdescription , DAYNAME(shop_event_startdate) as dateformated FROM zselex_shop_events
                     WHERE shop_id IS NOT NULL AND shop_event_id IS NOT NULL AND status='1' AND shop_id IN($shop_ids) " . " " . $eventdateqry . " ORDER BY RAND()" . " " . $limitquery;
            // echo $sql1;
            $query1 = DBUtil::executeSQL($sqlevents);
            $events = $query1->fetchAll();
            $eventcount = count($events);
            shuffle($events);

            $totalcountsql = "SELECT * FROM zselex_shop_events
                     WHERE shop_id IS NOT NULL AND shop_event_id IS NOT NULL AND status='1' AND shop_id IN($shop_ids) " . " " . $eventdateqry;
            $totalcountquery = DBUtil::executeSQL($totalcountsql);
            $totalCount = $totalcountquery->rowCount();

            $i = 1;

            if ($eventcount > 0) {
                foreach ($events as $event) {
                    if ($i == $limit) {
                        // break;
                    }
                    ;
                    $output .= "<div style='border:solid 1px #CCC; padding-top:15px; padding-bottom:5px; padding-left:15px; '>
                          
                      <div><b>" . $this->__('Event Name') . "</b>: " . $event ['event_name'] . "</div>
                          
                      <div><b>" . $this->__(/* Event */ 'Start Date') . "</b>:" . $event ['shop_event_startdate'] . "</div>
                          
                      <div><b>" . $this->__(/* Event */ 'Start Time') . "</b>: " . $event ['shop_event_starthour'] . ":" . $event ['shop_event_startminute'] . "</div>
                       
                      <div><b>" . $this->__(/* Event */ 'End Date') . "</b>: " . $event ['shop_event_enddate'] . "</div>
                          
                      <div><b>" . $this->__(/* Event */ 'End Time') . "</b>: " . $event ['shop_event_endhour'] . ":" . $event ['shop_event_endminute'] . "</div>
                       <div>
                       <a href='" . ModUtil::url('ZSELEX', 'user', 'viewevent', array(
                                'shop_id' => $event ['shop_id'],
                                'eventId' => $event ['shop_event_id']
                            )) . "'><font color=blue>" . $this->__('view details...') . "</font></a>
                      </div>
                   </div>
                  ";

                    // $output .= "<div>
                    // <h3>$event[dateformated] $event[shop_event_startdate]</h3>
                    // &nbsp;&nbsp;$event[event_name]<br>
                    // &nbsp;&nbsp;$event[event_shortdescription]...<br>
                    // &nbsp;&nbsp;$event[event_description]...<br>
                    // &nbsp;&nbsp;<a href='" . ModUtil::url('ZSELEX', 'user', 'viewevent', array('shop_id' => $event['shop_id'], 'eventId' => $event['shop_event_id'])) . "'><font color=blue>view details...</font></a>
                    //
					// <div><br>";
                    $i ++;
                }
                if ($totalCount > $eventcount) {
                    // $output .= '<div style="cursor:pointer" align=right onclick=getMoreEvents()> <font color="blue">more events... </font> </div>';
                }
            } else {
                $output .= "<dt> &nbsp;&nbsp;&nbsp;&nbsp;  " . $this->__('No Events Found') . "  </dt>";
            }
            // }
        } else {

            $output .= "<dt> &nbsp;&nbsp;&nbsp;&nbsp;  " . $this->__('No Events Found') . "  </dt>";
        }

        $output .= '</dl>';
        ZSELEX_Util::ajaxOutput($output);
    }

    public function getArticleAd($args) {

        // $data = "test";
        $level = FormUtil::getPassedValue("level");
        $shop_id = FormUtil::getPassedValue("shop_id");
        $country_id = FormUtil::getPassedValue("country_id");
        $region_id = FormUtil::getPassedValue("region_id");
        $city_id = FormUtil::getPassedValue("city_id");
        $area_id = FormUtil::getPassedValue("area_id");
        $category_id = FormUtil::getPassedValue("category_id");
        $search = FormUtil::getPassedValue("hsearch");

        $append = '';
        if ($level == 'country') {
            $append .= " AND ad.level='COUNTRY' AND ad.country_id='" . $country_id . "'";
        } elseif ($level == 'region') {
            $append .= " AND ad.level='REGION' AND ad.region_id='" . $region_id . "'";
        } elseif ($level == 'city') {
            $append .= " AND ad.level='CITY' AND ad.city_id='" . $city_id . "'";
        } elseif ($level == 'area') {
            $append .= " AND ad.level='AREA' AND ad.area_id='" . $area_id . "'";
        }

        $sql = "SELECT n.* , ad.shop_id FROM  zselex_article_ads ad
                LEFT JOIN zselex_shop shop ON shop.shop_id=ad.shop_id
                LEFT JOIN  zselex_country country ON country.country_id=ad.country_id
                LEFT JOIN  zselex_region region  ON region.region_id=ad.region_id
                LEFT JOIN  zselex_city city  ON city.city_id=ad.city_id
                LEFT JOIN  zselex_area area  ON area.area_id=ad.area_id
                INNER JOIN zselex_shop_news sn ON sn.shop_id=ad.shop_id
                INNER JOIN news n ON n.sid=sn.news_id
                WHERE ad.articlead_id IS NOT NULL 
                AND ad.status=1
                AND ad.start_date<=CURDATE() AND ad.end_date>=CURDATE()
                AND n.sid=sn.news_id" . $append;

        $query = DBUtil::executeSQL($sql);
        $count = $query->rowCount();
        $result = $query->fetchALL();

        $data = '';

        $modvars = ModUtil::getVar('News');
        $picupload_uploaddir = $modvars ['picupload_uploaddir'];
        foreach ($result as $news) {

            $sid = $news ['sid'];
            $title = $news ['title'];
            $hometext = $news ['hometext'];
            $data .= "<div class='Sec1'>";
            $data .= "<a href='" . ModUtil::url('ZSELEX', 'user', 'display', array(
                        'sid' => $sid,
                        'shop_id' => $news [shop_id]
                    )) . "'>";
            $data .= "<img src=" . pnGetBaseURL() . $picupload_uploaddir . "/pic_sid" . $sid . "-0-thumb.jpg>";
            $data .= "</a>";

            $data .= "<p class='sec1H'>" . $title . "</p>";
            $data .= "<span class='sec1T'>$hometext </span>";
            $data .= "<p class='sec1L'> <a href='" . ModUtil::url('ZSELEX', 'user', 'display', array(
                        'sid' => $sid,
                        'shop_id' => $news [shop_id]
                    )) . "'> " . $this->__('Read this full article') . "</a> </p>";
            $data .= "</div>";
        }

        ModUtil::url('ZSELEX', 'admin', 'viewbasket');

        // ZSELEX_Util::ajaxOutput($data);
        $output ["count"] = $count;
        $output ["data"] = $data;

        AjaxUtil::output($output);
    }

    public function insertAdClick() {
        $adId = FormUtil::getPassedValue("adId");
        $sql = "SELECT totalclicks FROM zselex_advertise WHERE advertise_id=$adId";
        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
        $row = $results->fetch();

        $adClicks = $row ['totalclicks'] + 1;
        $insrtClck = "UPDATE zselex_advertise SET totalclicks=$adClicks WHERE advertise_id=$adId";
        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($insrtClck);
    }

    public function getShopAd() {
        $shop_id = FormUtil::getPassedValue("shop_id");
        $country_id = FormUtil::getPassedValue("country_id");
        $region_id = FormUtil::getPassedValue("region_id");
        $city_id = FormUtil::getPassedValue("city_id");
        $area_id = FormUtil::getPassedValue("area_id");
        $category_id = FormUtil::getPassedValue("category_id");
        $search = FormUtil::getPassedValue("hsearch");
        $search = ($search == $this->__('search for...') || $search == $this->__('search')) ? '' : $search;

        $branch_id = FormUtil::getPassedValue("branch_id");
        $limitshops = FormUtil::getPassedValue("limitshops");
        $adtype = FormUtil::getPassedValue("adtype");
        $append = '';

        // $sQuery = "SELECT s.* FROM zselex_shop AS s WHERE s.shop_id IS NOT NULL";
        // if ($shop_id > 0 || !empty($shop_id)) {
        // $sQuery .= " AND s.shop_id = " . $shop_id;
        // } else if ($city_id > 0 || !empty($city_id)) {
        // $sQuery .= " AND s.shop_id IN( SELECT childId FROM zselex_parent WHERE childType = 'SHOP' AND parentType = 'CITY' AND parentId = " . $city_id . " )";
        // } else if ($region_id > 0 || !empty($region_id)) {
        // $sQuery .= " AND s.shop_id IN( SELECT childId FROM zselex_parent WHERE childType = 'SHOP' AND parentType = 'REGION' AND parentId = " . $region_id . " )";
        // } else if ($country_id > 0 || !empty($country_id)) {
        // $sQuery .= " AND s.shop_id IN( SELECT childId FROM zselex_parent WHERE childType = 'SHOP' AND parentType = 'COUNTRY' AND parentId = " . $country_id . " )";
        // }
        // if ($limitshops > 0 || !empty($limitshops)) {
        // $sQuery .= " LIMIT 0 , " . $limitshops;
        // }
        //
		
		if (($shop_id > 0 || !empty($shop_id)) || ($region_id > 0 || !empty($region_id)) || ($city_id > 0 || !empty($city_id)) || ($country_id > 0 || !empty($country_id)) || ($area_id > 0 || !empty($area_id)) || ($search > 0 || !empty($search)) || ($category_id > 0 || !empty($category_id)) || ($branch_id > 0 || !empty($branch_id))) {
            $catquery = '';
            $catshop = '';
            if ($category_id != '') {
                $catquery = " AND a.cat_id=$category_id";
            }
            if ($category_id != '' && $country_id <= 0 && $region_id <= 0 && $city_id <= 0 && $shop_id <= 0) {
                $catshop = " AND a.cat_id=$category_id";
            }

            $branchquery = '';
            $branchshop = '';
            if ($branch_id != '') {
                $branchquery = " AND a.branch_id=$branch_id";
            }
            if ($branch_id != '' && $country_id <= 0 && $region_id <= 0 && $city_id <= 0 && $shop_id <= 0) {
                $branchshop = " AND a.branch_id=$branch_id";
            }

            $searchquery = '';
            if (!empty($search)) {
                $searchquery = " AND b.keywords LIKE '%" . DataUtil::formatForStore($search) . "%'";
            }

            $searchquerymain = '';
            if (!empty($search) && $country_id <= 0 && $region_id <= 0 && $city_id <= 0 && $area_id <= 0 && $shop_id <= 0) { // Only search is selected
                $searchquerymain = " AND b.keywords LIKE '%" . DataUtil::formatForStore($search) . "%'";
            }

            if (!empty($category_id)) {
                $append .= " AND a.cat_id=$category_id";
            }

            if (!empty($branch_id)) {
                $append .= " AND a.branch_id=$branch_id";
            }

            $adquery = '';
            if ($adtype != '') {
                // $adquery = " AND b.adprice_id=$adtype";
                $append .= " AND b.adprice_id=$adtype";
            }
            $where = '';

            if (($country_id > 0) or ( $country_id > 0 && $region_id < 0 && $city_id < 0 && $shop_id <= 0)) { // COUNTRY
                $where = "  AND b.country_id=$country_id AND b.level='COUNTRY' $append $searchquery";
            }

            if (($region_id > 0) or ( $country_id > 0 && $region_id > 0 && $city_id < 0 && $shop_id <= 0)) { // REGION
                $where = "  AND b.region_id=$region_id AND b.level='REGION'  $append $searchquery";
            }

            if (($city_id > 0) or ( $region_id > 0 && $city_id > 0 && $country_id > 0 && $area_id < 0 && $shop_id <= 0)) { // CITY
                $where = "  AND b.city_id=$city_id AND b.level='CITY'   $append $searchquery";
            }

            if (($area_id > 0) or ( $region_id > 0 && $area_id > 0 && $city_id > 0 && $country_id > 0 && $shop_id <= 0)) { // AREA
                $where = " AND b.area_id=$area_id AND b.level='AREA'   $append $searchquery";
            }

            // if (($area_id > 0) OR ($region_id > 0 && $area_id > 0 && $city_id > 0 && $country_id > 0 && $shop_id <= 0)) { //AREA
            // $where = " comes here";
            // }

            if (($shop_id > 0) or ( $shop_id > 0 && $region_id > 0 && $city_id > 0 && $country_id > 0)) { // SHOP
                $where = " AND b.shop_id=$shop_id AND b.level='SHOP'  $append $searchquery";
            }

            if (($shop_id <= 0 || empty($shop_id)) && ($region_id <= 0 || empty($region_id)) && ($city_id <= 0 || empty($city_id)) && ($country_id <= 0 || empty($country_id)) || ($area_id <= 0 || empty($area_id))) {
                // $where = " WHERE $adquery $searchquery";
            }

            // echo "where : " . $where; exit;

            $sQuery = "SELECT a.* , b.* , f.name as shpImg FROM zselex_shop a , zselex_advertise b
                LEFT JOIN zselex_files f ON f.shop_id=b.shop_id AND f.defaultImg=1
                WHERE b.advertise_id IS NOT NULL
                AND a.shop_id=b.shop_id
                $where $catshop $branchshop $searchquerymain
                AND b.maxviews > b.totalviews AND  b.maxclicks > b.totalclicks AND b.startdate<=CURDATE() AND b.enddate>=CURDATE() 
                AND b.advertise_type='shopAd' AND b.status=1";
            // echo $sQuery;

            $sResult = ModUtil::apiFunc('ZSELEX', 'admin', 'execteQuery', $sQuery);

            // return $allValues;
            $output = '';
            $output .= "<dl>";

            $Count = count($sResult);
        } else {

            $Count = 0;
        }
        if ($Count > 0) {

            $var = '';
            $adss = '';
            $div = '';
            foreach ($sResult as $shop) {
                $ads = $shop ['advertise_id'];
                if ($var != $ads || $var == '') {
                    $adss = $shop ['advertise_id'];
                    $sqlad = "select totalviews from zselex_advertise where advertise_id=$adss";
                    $statement = Doctrine_Manager::getInstance()->connection();
                    $results = $statement->execute($sqlad);
                    $advalue = $results->fetch();
                    $adview = $advalue ['totalviews'] + 1;
                    $sqladupdate = "update zselex_advertise set totalviews=$adview where advertise_id=$adss";
                    $statement = Doctrine_Manager::getInstance()->connection();
                    $results = $statement->execute($sqladupdate);
                    // $test .= $adview;
                }
                $var = $ads;

                if (!empty($shop ['pictures'])) {
                    $div = "<div><img src='zselexdata/shops/thumbs/$shop[shpImg]'></div>";
                } else {
                    $div = '';
                }

                $output .= "<div style='border:solid 1px #CCC; padding-top:15px; padding-bottom:5px; padding-left:15px; '>
                            $div
                      <div><b>" . $this->__('Shop Name') . "</b>: " . $shop ['shop_name'] . "</div>
                          
                      <div><b>" . $this->__('Address') . "</b>: " . $shop ['address'] . "</div>
                          
                      <div><b>" . $this->__('Telephone') . "</b>: " . $shop ['telephone'] . "</div>
                       
                      <div><b>" . $this->__('Fax') . "</b>: " . $shop ['fax'] . "</div>
                          
                      <div><b>" . $this->__('Email') . "</b>: " . $shop ['email'] . "</div>

                   </div>
                  ";
            }
        } else {

            $output = '<dt> &nbsp;&nbsp;&nbsp;&nbsp;  ' . $this->__('No Shops Found') . '  </dt>';
        }

        $output .= '</dl>';

        ZSELEX_Util::ajaxOutput($output);
    }

    public function addToBasket() {

        // exit;
        $plugin_id = FormUtil::getPassedValue("plugin_id");
        $price = FormUtil::getPassedValue("price");
        $qty1 = FormUtil::getPassedValue("qty");
        $shop_id = FormUtil::getPassedValue("shop_id");
        $type = FormUtil::getPassedValue("typ");

        $output = 'false';
        $loguser = UserUtil::getVar('uid');
        $user = array();

        $sql = "SELECT * FROM zselex_basket a WHERE a.user_id=$loguser AND a.plugin_id=$plugin_id AND a.shop_id=$shop_id AND a.type='" . $type . "' AND a.status=0";

        // ZSELEX_Util::ajaxOutput($sql); exit;

        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
        $values = $results->fetchAll();
        $count = count($values);

        if ($count < 1) {

            $item = array(
                'plugin_id' => $plugin_id,
                'type' => $type,
                'shop_id' => $shop_id,
                'user_id' => $loguser,
                'quantity' => $qty1,
                'price' => $price * $qty1,
                'status' => 0,
                'originalPrice' => $price
            );

            $args = array(
                'table' => 'zselex_basket',
                'element' => $item,
                'Id' => 'basket_id'
            );
            $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $args);
            if ($result == true) {

                $output = 'true';
            }
        } else {

            // $sql = "SELECT a.quantity FROM zselex_basket a
            // WHERE a.user_id=$loguser AND a.plugin_id=$plugin_id";
            // $statement = Doctrine_Manager::getInstance()->connection();
            // $results = $statement->execute($sql);
            // $values = $results->fetch();
            //
			// $qty = $values['quantity'] + 1;

            $prices = $price * $qty1;

            $updt = "UPDATE zselex_basket SET quantity=$qty1 , price=$prices  WHERE plugin_id=$plugin_id AND user_id=$loguser AND shop_id=$shop_id AND type='" . $type . "' AND status=0";
            $statement = Doctrine_Manager::getInstance()->connection();
            $results = $statement->execute($updt);
            if ($results == true) {

                $output = 'updated';
            }
        }

        ZSELEX_Util::ajaxOutput($output);
    }

    public function addToBasket1() {

        // exit;
        $output = '';
        $plugin_id = FormUtil::getPassedValue("plugin_id");
        $plugin_name = FormUtil::getPassedValue("plugin_name");
        $price = FormUtil::getPassedValue("price");
        $qty1 = FormUtil::getPassedValue("qty");
        $qtyBased = FormUtil::getPassedValue("qty_based");
        $shop_id = FormUtil::getPassedValue("shop_id");
        $type = FormUtil::getPassedValue("typ");

        if (empty($_SESSION ['admincart'])) {
            if (!empty($_COOKIE ['admincart'])) {
                foreach ($_COOKIE ['admincart'] as $key => $val) {
                    $finalCookie [$key] = json_decode($val, true);
                }

                foreach ($finalCookie as $key => $val) {
                    $_SESSION ['admincart'] [$key] = array(
                        'plugin_id' => $val ['plugin_id'],
                        'plugin_name' => $val ['plugin_name'],
                        'type' => $val ['type'],
                        'shop_id' => $val ['shop_id'],
                        'user_id' => $val ['user_id'],
                        'quantity' => $val ['quantity'],
                        'qty_based' => $val ['qty_based'],
                        'price' => $val ['price'],
                        'originalPrice' => $val ['originalPrice']
                    );
                }
            }
        }

        if ($plugin_id != '') {
            if (isset($_COOKIE ['admincart'])) { // checking the existing products in cart
                foreach ($_COOKIE ['admincart'] as $val) {
                    // echo "<pre>"; print_r( json_decode($val, true)); echo "</pre>";
                    if (in_array($plugin_id, json_decode($val, true))) {
                        $exist ++;
                    } else {
                        
                    }
                }
            }

            $message = '';
            if ($exist < 1) { // if product not exist in cart the set it to session and cookie
                $_SESSION ['admincart'] [] = array(
                    'plugin_id' => $plugin_id,
                    'plugin_name' => $plugin_name,
                    'type' => $type,
                    'shop_id' => $shop_id,
                    'user_id' => $loguser,
                    'quantity' => $qty1,
                    'qty_based' => $qtyBased,
                    'price' => $price * $qty1,
                    'status' => 0,
                    'originalPrice' => $price
                );

                $array = array(
                    'plugin_id' => $plugin_id,
                    'plugin_name' => $plugin_name,
                    'type' => $type,
                    'shop_id' => $shop_id,
                    'user_id' => $loguser,
                    'quantity' => $qty1,
                    'qty_based' => $qtyBased,
                    'price' => $price * $qty1,
                    'status' => 0,
                    'originalPrice' => $price
                );

                $last_keys = '';
                if (!isset($_COOKIE ['admincart'])) { // if cookie is not set then set the first key as zero
                    $last_keys = 0;
                } else { // if cookie is available then increment the key by adding the last key of the previous cookie to it.
                    $last_key = key(array_slice($_COOKIE ['admincart'], - 1, 1, TRUE));
                    $last_keys = $last_key + 1;
                }
                // echo "<pre>"; print_r(json_encode($_SESSION['cart'][$last_key])); echo "</pre>";

                $cookieEncode = json_encode($array);
                setcookie("admincart[$last_keys]", $cookieEncode, time() + 604800);
                $message = "<font color=green><b>" . $this->__('Service added succesdfully') . "</b></font>";
            } else {
                $message = "<font color=green><b>" . $this->__('Service already added in Cart') . "</b></font>";
            }
        } // ///
        /*
         * if (isset($_SESSION['admincart'])) {
         * $sessionCount = count($_SESSION['admincart']);
         * }
         *
         * if (isset($_COOKIE['admincart'])) {
         * $cookieCount = count($_COOKIE['admincart']);
         * }
         * if (count($_COOKIE['admincart']) > 0) {
         * foreach ($_COOKIE['admincart'] as $key => $val) {
         * $finalCookie[$key] = json_decode($val, true);
         * }
         * }
         *
         *
         * $count = (empty($cookieCount)) ? $sessionCount : $cookieCount;
         *
         * $GRANDTOTAL = '';
         * if ($sessionCount > $cookieCount) {
         * if (count($_SESSION['admincart']) > 0) {
         * foreach ($_SESSION['admincart'] as $val) {
         * $grandPrice[] = $val['price'];
         * }
         * $GRANDTOTAL = array_sum($grandPrice);
         * $output['grandtotal'] = $GRANDTOTAL;
         * }
         * } else {
         * if (count($_COOKIE['admincart']) > 0) {
         * foreach ($finalCookie as $val) {
         * $grandPrice[] = $val['price'];
         * }
         * $GRANDTOTAL = array_sum($grandPrice);
         * $output['grandtotal'] = $GRANDTOTAL;
         * }
         * }
         *
         *
         * if ($sessionCount > $cookieCount) { // if session is set first then render session products to cart page otherwise render cookie products
         * $output['items'] = $_SESSION['admincart'];
         * } else {
         *
         * $output['items'] = $finalCookie;
         * }
         *
         * AjaxUtil::output($output);
         */

        if (!empty($_SESSION ['admincart'])) {
            $sessionCount = count($_SESSION ['admincart']);
        }

        if (!empty($_COOKIE ['admincart'])) {
            $cookieCount = count($_COOKIE ['admincart']);
        }
        $count = !empty($sessionCount) ? $sessionCount : 0;
        $output = '';

        $output ['result'] = $this->getBasket($message, $shop_id);
        $output ['count'] = $count;
        AjaxUtil::output($output);
    }

    public function getServicesBasketList1($msge) {
        // $valMsg = $msge;
        $valMsg = FormUtil::getPassedValue('val', null, 'REQUEST');
        // ZSELEX_Util::ajaxOutput($val);

        $output = '';
        $disabled = '';
        $loguser = UserUtil::getVar('uid');

        if (!empty($_SESSION ['admincart'])) {
            $sessionCount = count($_SESSION ['admincart']);
        }

        if (!empty($_COOKIE ['admincart'])) {
            $cookieCount = count($_COOKIE ['admincart']);
        }

        if (count($_COOKIE ['admincart']) > 0) {
            foreach ($_COOKIE ['admincart'] as $key => $val) {
                $finalCookie [$key] = json_decode($val, true);
            }
        }

        $count = (empty($cookieCount)) ? $sessionCount : $cookieCount;

        $GRANDTOTAL = '';
        if ($sessionCount > $cookieCount) {
            if (count($_SESSION ['admincart']) > 0) {
                foreach ($_SESSION ['admincart'] as $val) {
                    $grandPrice [] = $val ['price'];
                }
                $GRANDTOTAL = array_sum($grandPrice);
                $grandtotal = $GRANDTOTAL;
            }
        } else {
            if (count($_COOKIE ['admincart']) > 0) {
                foreach ($finalCookie as $val) {
                    $grandPrice [] = $val ['price'];
                }
                $GRANDTOTAL = array_sum($grandPrice);
                $grandtotal = $GRANDTOTAL;
            }
        }

        if ($sessionCount > $cookieCount) { // if session is set first then render session products to cart page otherwise render cookie products
            $values = $_SESSION ['admincart'];
        } else {

            $values = $finalCookie;
        }

        $output .= "<div align='center' id='supdate'>" . $valMsg . "</div>";

        $output .= "<div align='right' style='cursor:pointer' onClick='closeWindow()'><img src='" . pnGetBaseURL() . "images/cart_close_btn.jpg' /></div>";
        $output .= "<table bgcolor=black cellspacing=1 cellpadding=1 width='100%'>
        <tr bgcolor=white>
        <td  align=center><b>" . $this->__('Service') . "</b></td>
        <td  align=center><b>" . $this->__('ShopName') . "</b></td>
        <td  align=center><b>" . $this->__('Quantity') . "</b></td>
        <td  align=center><b>" . $this->__('Price') . "</b></td>
        <td></td>
        </tr>";
        $v = array();

        if (count($values) > 0) {
            foreach ($values as $key => $val) {
                // $v[] = $val['newprice'];

                if ($val ['qty_based'] == 0) {
                    $disabled = "disabled='disabled'";
                } else {
                    $disabled = '';
                }

                $output .= "<tr  bgcolor=white height='40px'>
                  <td  align=center>" . $val ['plugin_name'] . "</td>
                  <td  align=center>" . $val ['shop_name'] . "</td>
                  <td  align=center>";
                $output .= "<select  id='select' $disabled onChange='updatecart(this.value , $key , $val[originalPrice])'>";
                for ($i = 1; $i <= 100; $i ++) {
                    if ($i == $val [quantity]) {
                        $output .= "<option value=$i selected=selected>$i</option>";
                    } else {
                        $output .= "<option value=$i>$i</option>";
                    }
                }
                $output .= "</select>";
                $output .= "</td>
                  <td  align=center>" . $val [price] . "</td>
                  <td align=center><img src='" . pnGetBaseURL() . "images/canel_btn.jpg' style='cursor:pointer' onclick='deleteService1($key)' /></td>
                </tr>";
            }
        } else {

            $output .= "<tr bgcolor=white><td align=center colspan=5><b>" . $this->__('No Service in your Cart') . "</b></td></tr>";
        }
        $continue = '';
        if ($count > 0) {
            $continue = "<br><a href='index.php?module=ZSELEX&type=admin&func=submitservices'>Continue</a>";
        }
        $output .= "<tr bgcolor=white><td align=right colspan=5><b>" . $this->__f('Total: %s per month', $grandtotal) . "</b>$continue</td></tr>";
        $output .= "</table>";

        // return $output;
        ZSELEX_Util::ajaxOutput($output);
    }

    public function getBasket($msge, $shop_id) {
        // header("Cache-Control: no-cache, must-revalidate");
        $valMsg = !empty($msge) ? $msge : '';
        // ZSELEX_Util::ajaxOutput($val);

        $output = '';
        $disabled = '';
        // $loguser = UserUtil::getVar('uid');

        if (!empty($_SESSION ['admincart'])) {
            $sessionCount = count($_SESSION ['admincart']);
        }

        if (!empty($_COOKIE ['admincart'])) {
            $cookieCount = count($_COOKIE ['admincart']);
        }

        if (count($_COOKIE ['admincart']) > 0) {
            foreach ($_COOKIE ['admincart'] as $key => $val) {
                $finalCookie [$key] = json_decode($val, true);
            }
        }

        $count = (empty($cookieCount)) ? $sessionCount : $cookieCount;

        $GRANDTOTAL = '';
        if ($sessionCount > $cookieCount) {
            if (count($_SESSION ['admincart']) > 0) {
                foreach ($_SESSION ['admincart'] as $val) {
                    $grandPrice [] = $val ['price'];
                }
                $GRANDTOTAL = array_sum($grandPrice);
                $grandtotal = $GRANDTOTAL;
            }
        } else {
            if (count($_COOKIE ['admincart']) > 0) {
                foreach ($finalCookie as $val) {
                    $grandPrice [] = $val ['price'];
                }
                $GRANDTOTAL = array_sum($grandPrice);
                $grandtotal = $GRANDTOTAL;
            }
        }

        /*
         * if ($sessionCount > $cookieCount) { // if session is set first then render session products to cart page otherwise render cookie products
         * $values = $_SESSION['admincart'];
         * } elseif (($sessionCount < $cookieCount) && ($cookieCount - $sessionCount == 1)) {
         * $values = $_SESSION['admincart'];
         * } elseif ($sessionCount == $cookieCount) {
         * $values = $_SESSION['admincart'];
         * } else {
         *
         * $values = $finalCookie;
         * }
         */

        if ($sessionCount > $cookieCount) { // if session is set first then render session products to cart page otherwise render cookie products
            $values = $_SESSION ['admincart'];
        } elseif ($sessionCount == $cookieCount) {
            $values = $_SESSION ['admincart'];
        } elseif (($sessionCount < $cookieCount) && ($cookieCount - $sessionCount == 1)) {
            $values = $_SESSION ['admincart'];
        } else {
            $values = $finalCookie;
        }

        // $test = "session :" . $sessionCount . " " . "cookie : " . $cookieCount;
        // return $test;
        $output .= "<div align='center' id='supdate'> " . $valMsg . "</div>";

        $output .= "<div align='right' style='cursor:pointer' onClick='closeWindow()'><img src='" . pnGetBaseURL() . "images/cart_close_btn.jpg' /></div>";
        $output .= "<table  bgcolor=black cellspacing=1 cellpadding=1 width='100%'>
        <tr bgcolor=white>
        <td  align=center><b>" . $this->__('Service') . "</b></td>
        <td  align=center><b>" . $this->__('ShopName') . "</b></td>
        <td  align=center><b>" . $this->__('Quantity') . "</b></td>
        <td  align=center><b>" . $this->__('Price') . "</b></td>
        <td></td>
        </tr>";
        $v = array();
        if (count($values) > 0) {
            foreach ($values as $key => $val) {
                $v [] = $val ['price'];

                if ($val ['qty_based'] == 0) {
                    $disabled = "disabled='disabled'";
                } else {
                    $disabled = '';
                }

                $output .= "<tr  bgcolor=white height='40px'>
                  <td  align=center>" . $val ['plugin_name'] . "</td>
                  <td  align=center>" . $val ['shop_name'] . "</td>
                  <td  align=center>";
                $output .= "<select  id='select' $disabled onChange='updatecart(this.value , $key , $val[originalPrice] , $shop_id)'>";
                for ($i = 1; $i <= 100; $i ++) {
                    if ($i == $val [quantity]) {
                        $output .= "<option value=$i selected=selected>$i</option>";
                    } else {
                        $output .= "<option value=$i>$i</option>";
                    }
                }
                $output .= "</select>";
                $output .= "</td>
                  <td  align=center>" . $val [price] . "</td>
                  <td align=center><img src='" . pnGetBaseURL() . "images/canel_btn.jpg' style='cursor:pointer' onclick='deleteService1($key , $shop_id)' /></td>
                </tr>";
            }
        } else {

            $output .= "<tr bgcolor=white><td align=center colspan=5><b>" . $this->__('No Service in your Cart') . "</b></td></tr>";
        }
        $continue = '';
        if ($count > 0 && !empty($_SESSION ['admincart'])) {
            $continue = "<br><a href='index.php?module=ZSELEX&type=admin&func=submitservices&shop_id=$shop_id'>Continue</a>";
        }
        $output .= "<tr bgcolor=white><td align=right colspan=5><b>" . $this->__f('Total: %s per month', array_sum($v)) . "</b>$continue</td></tr>";
        $output .= "</table>";

        return $output;
        // ZSELEX_Util::ajaxOutput($output);
    }

    public function displayBasket($msge) {
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $valMsg = !empty($msge) ? $msge : '';
        // ZSELEX_Util::ajaxOutput($val);
        if (empty($_SESSION ['admincart'])) {
            if (!empty($_COOKIE ['admincart'])) {
                foreach ($_COOKIE ['admincart'] as $key => $val) {
                    $finalCookie [$key] = json_decode($val, true);
                }

                foreach ($finalCookie as $key => $val) {
                    $_SESSION ['admincart'] [$key] = array(
                        'plugin_id' => $val ['plugin_id'],
                        'plugin_name' => $val ['plugin_name'],
                        'type' => $val ['type'],
                        'shop_id' => $val ['shop_id'],
                        'user_id' => $val ['user_id'],
                        'quantity' => $val ['quantity'],
                        'qty_based' => $val ['qty_based'],
                        'price' => $val ['price'],
                        'originalPrice' => $val ['originalPrice']
                    );
                }
            }
        }

        $output = '';
        $disabled = '';
        // $loguser = UserUtil::getVar('uid');
        // if (!empty($_SESSION['admincart'])) {
        // $sessionCount = count($_SESSION['admincart']);
        $sessionCount = !empty($_SESSION ['admincart']) ? count($_SESSION ['admincart']) : 0;
        // }
        // if (!empty($_COOKIE['admincart'])) {
        // $cookieCount = count($_COOKIE['admincart']);
        // }

        $cookieCount = !empty($_COOKIE ['admincart']) ? count($_COOKIE ['admincart']) : 0;

        if (count($_COOKIE ['admincart']) > 0) {
            foreach ($_COOKIE ['admincart'] as $key => $val) {
                $finalCookie [$key] = json_decode($val, true);
            }
        }

        $count = (empty($cookieCount)) ? $sessionCount : $cookieCount;

        $GRANDTOTAL = '';
        if ($sessionCount > $cookieCount) {
            if (count($_SESSION ['admincart']) > 0) {
                foreach ($_SESSION ['admincart'] as $val) {
                    $grandPrice [] = $val ['price'];
                }
                $GRANDTOTAL = array_sum($grandPrice);
                $grandtotal = $GRANDTOTAL;
            }
        } else {
            if (count($_COOKIE ['admincart']) > 0) {
                foreach ($finalCookie as $val) {
                    $grandPrice [] = $val ['price'];
                }
                $GRANDTOTAL = array_sum($grandPrice);
                $grandtotal = $GRANDTOTAL;
            }
        }

        /*
         * if ($sessionCount > $cookieCount) { // if session is set first then render session products to cart page otherwise render cookie products
         * $values = $_SESSION['admincart'];
         * } elseif (($sessionCount < $cookieCount) && ($cookieCount - $sessionCount == 1)) {
         * $values = $_SESSION['admincart'];
         * } elseif ($sessionCount == $cookieCount) {
         * $values = $_SESSION['admincart'];
         * } else {
         *
         * $values = $finalCookie;
         * }
         */

        if ($sessionCount > $cookieCount) { // if session is set first then render session products to cart page otherwise render cookie products
            $values = $_SESSION ['admincart'];
        } elseif ($sessionCount == $cookieCount) {

            $values = $_SESSION ['admincart'];
        } elseif (($sessionCount < $cookieCount) && ($cookieCount - $sessionCount == 1)) {

            $values = $_SESSION ['admincart'];
        } else {
            // $test1 = "comes here";
            $values = $finalCookie;
        }

        // $test = "session :" . $sessionCount . " " . "cookie : " . $cookieCount;
        // return $test;
        $output .= "<div align='center' id='supdate'> " . $valMsg . "</div>";

        $output .= "<div align='right' style='cursor:pointer' onClick='closeWindow()'><img src='" . pnGetBaseURL() . "images/cart_close_btn.jpg' /></div>";
        $output .= "<table align='center' bgcolor=black cellspacing=1 cellpadding=1 width='100%'>
        <tr bgcolor=white>
        <td  align=center><b>" . $this->__('Service') . "</b></td>
        <td  align=center><b>" . $this->__('ShopName') . "</b></td>
        <td  align=center><b>" . $this->__('Quantity') . "</b></td>
        <td  align=center><b>" . $this->__('Price') . "</b></td>
        <td></td>
        </tr>";
        $v = array();
        if (count($values) > 0) {
            foreach ($values as $key => $val) {
                $v [] = $val ['price'];

                if ($val ['qty_based'] == 0) {
                    $disabled = "disabled='disabled'";
                } else {
                    $disabled = '';
                }

                $output .= "<tr  bgcolor=white height='40px'>
                  <td  align=center>" . $val ['plugin_name'] . "</td>
                  <td  align=center>" . $val ['shop_name'] . "</td>
                  <td  align=center>";
                $output .= "<select  id='select' $disabled onChange='updatecart(this.value , $key , $val[originalPrice])'>";
                for ($i = 1; $i <= 100; $i ++) {
                    if ($i == $val [quantity]) {
                        $output .= "<option value=$i selected=selected>$i</option>";
                    } else {
                        $output .= "<option value=$i>$i</option>";
                    }
                }
                $output .= "</select>";
                $output .= "</td>
                  <td  align=center>" . $val [price] . "</td>
                  <td align=center><img src='" . pnGetBaseURL() . "images/canel_btn.jpg' style='cursor:pointer' onclick='deleteService1($key)' /></td>
                </tr>";
            }
        } else {

            $output .= "<tr bgcolor=white><td align=center colspan=5><b>No Service In Your Basket</b></td></tr>";
        }
        $continue = '';
        if ($count > 0 && !empty($_SESSION ['admincart'])) {
            $continue = "<br><a href='index.php?module=ZSELEX&type=admin&func=submitservices&shop_id=$shop_id'>Continue</a>";
        }
        $output .= "<tr bgcolor=white><td align=right colspan=5><b>" . $this->__f('Total: %s per month', array_sum($v)) . "</b>$continue</td></tr>";
        $output .= "</table>";

        // return $output;
        ZSELEX_Util::ajaxOutput($output);
    }

    public function deletecart() {
        $Id = FormUtil::getPassedValue('id', '', 'REQUEST');
        $shop_id = FormUtil::getPassedValue('shop_id', '', 'REQUEST');
        unset($_SESSION ['admincart'] [$Id]);

        setcookie("admincart[$Id]", "", time() - 604800);
        // LogUtil::registerStatus($this->$this->__('Done! Deleted Item.'));
        // ZSELEX_Util::ajaxOutput("deleted");

        $output = '';
        $message = "<font color=red><b>" . $this->__('Service deleted successfully') . "</b></font>";
        if (!empty($_SESSION ['admincart'])) {
            $sessionCount = count($_SESSION ['admincart']);
        }

        $count = !empty($sessionCount) ? $sessionCount : 0;
        $output ['result'] = $this->getBasket($message, $shop_id);
        $output ['count'] = $count;
        AjaxUtil::output($output);
    }

    public function updatecart() {
        $shop_id = FormUtil::getPassedValue("shop_id");
        $Id = FormUtil::getPassedValue("id");
        $quantity = FormUtil::getPassedValue("qty");
        $orgprice = FormUtil::getPassedValue("orgprice");
        $price = $orgprice * $quantity;

        $_SESSION ['admincart'] [$Id] ['quantity'] = $quantity;
        $_SESSION ['admincart'] [$Id] ['price'] = $quantity * $_SESSION ['admincart'] [$Id] ['originalPrice'];

        foreach ($_SESSION ['admincart'] as $key => $val) {
            $array = array(
                'plugin_id' => $val ['plugin_id'],
                'plugin_name' => $val ['plugin_name'],
                'type' => $val ['type'],
                'shop_id' => $val ['shop_id'],
                'user_id' => $val ['user_id'],
                'quantity' => $val ['quantity'],
                'qty_based' => $val ['qty_based'],
                'price' => $val ['price'],
                'status' => 0,
                'originalPrice' => $val ['originalPrice']
            );

            $cookieEncode = json_encode($array);
            setcookie("admincart[$key]", $cookieEncode, time() + 604800);
        }

        $output = '';
        $message = "<font color=green><b>" . $this->__('Service updated successfully') . "</b></font>";
        $output ['result'] = $this->getBasket($message, $shop_id);
        AjaxUtil::output($output);
    }

    public function getServicesBasketList() {
        $val = FormUtil::getPassedValue("val");
        $output = '';
        $disabled = '';
        $loguser = UserUtil::getVar('uid');
        $sql = "SELECT * , b.qty_based as qty_based  , a.price as newprice FROM zselex_basket a LEFT JOIN zselex_plugin b ON a.plugin_id=b.plugin_id
                LEFT JOIN zselex_shop s ON a.shop_id=s.shop_id WHERE a.status=0
                    ";

        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
        $values = $results->fetchAll($values);
        $count = count($values);
        $msg = '';
        if ($val == 'delete') {
            $msg = '<font color=red><b>' . $this->__('Item has been deleted') . '</b></font>';
        } elseif ($val == 'update') {
            $msg = '<font color=green><b>' . $this->__('Your basket has been updated') . '</b></font>';
        } elseif ($val == 'add') {
            $msg = '<font color=green><b>' . $this->__('Service added to your basket!') . '</b></font>';
        }

        $output .= "<div align='center' id='supdate'>$msg</div>";

        $output .= "<div align='right' style='cursor:pointer' onClick='closeWindow()'><img src='" . pnGetBaseURL() . "images/cart_close_btn.jpg' /></div>";
        $output .= "<table bgcolor=black cellspacing=1 cellpadding=1 width='100%'>
        <tr bgcolor=white>
        <td  align=center><b>" . $this->__('Service') . "</b></td>
        <td  align=center><b>" . $this->__('ShopName') . "</b></td>
        <td  align=center><b>" . $this->__('Quantity') . "</b></td>
        <td  align=center><b>" . $this->__('Price') . "</b></td>
        <td></td>
        </tr>";
        $v = array();

        if (count($values) > 0) {
            foreach ($values as $val) {
                $v [] = $val ['newprice'];

                if ($val ['qty_based'] == 0) {
                    $disabled = "disabled='disabled'";
                } else {
                    $disabled = '';
                }

                $output .= "<tr  bgcolor=white height='40px'>
                  <td  align=center>" . $val ['plugin_name'] . "</td>
                  <td  align=center>" . $val ['shop_name'] . "</td>
                  <td  align=center>";
                $output .= "<select  id='select' $disabled onChange='updateService(this.value , $val[basket_id] , $val[originalPrice])'>";
                for ($i = 1; $i <= 100; $i ++) {
                    if ($i == $val [quantity]) {
                        $output .= "<option value=$i selected=selected>$i</option>";
                    } else {
                        $output .= "<option value=$i>$i</option>";
                    }
                }
                $output .= "</select>";
                $output .= "</td>
                  <td  align=center>" . $val [newprice] . "</td>
                  <td align=center><img src='" . pnGetBaseURL() . "images/canel_btn.jpg' style='cursor:pointer' onclick='deleteService($val[basket_id])' /></td>
                </tr>";
            }
        } else {

            $output .= "<tr bgcolor=white><td align=center colspan=5><b>" . $this->__('No Service in your Cart') . "</b></td></tr>";
        }
        $continue = '';
        if ($count > 0) {
            $continue = "<br><a href='index.php?module=ZSELEX&type=admin&func=submitservices'>Continue</a>";
        }
        $output .= "<tr bgcolor=white><td align=right colspan=5><b>" . $this->__f('Total: %s per month', array_sum($v)) . "</b>$continue</td></tr>";
        $output .= "</table>";

        ZSELEX_Util::ajaxOutput($output);
    }

    public function deleteService() {
        $Id = FormUtil::getPassedValue("id");

        $sql = "DELETE FROM zselex_basket WHERE basket_id=$Id";
        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
        ZSELEX_Util::ajaxOutput("deleted");
    }

    public function updateService() {
        $Id = FormUtil::getPassedValue("id");
        $qty = FormUtil::getPassedValue("qty");
        $orgprice = FormUtil::getPassedValue("orgprice");
        $price = $orgprice * $qty;

        $sql = "UPDATE zselex_basket SET quantity=$qty , price=$price  WHERE basket_id=$Id";
        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
    }

    public function checkDotdExist() {
        $date = FormUtil::getPassedValue("dateVal");
        $hasDate = FormUtil::getPassedValue("hasDate");

        $sql = "SELECT COUNT(*) as count FROM zselex_dotd WHERE dotd_date='" . $date . "' AND dotd_date!='" . $hasDate . "'";
        $query = DBUtil::executeSQL($sql);
        $result = $query->fetch();

        $old_date = 0;
        $currdate = date("Y-m-d");

        if (($date < $currdate) && $hasDate != $date) {

            $old_date = 1;
        }

        $count = $result ['count'];

        $output = '';
        // ZSELEX_Util::ajaxOutput($count);

        $output ["count"] = $count;
        $output ["olddate"] = $old_date;
        AjaxUtil::output($output);
    }

    public function checkConnection() {
        $error1 = '';
        try {
            $domain = FormUtil::getPassedValue("domain");
            $host = FormUtil::getPassedValue("host");
            $dbname = FormUtil::getPassedValue("dbname");
            $username = FormUtil::getPassedValue("username");
            $dbpassword = FormUtil::getPassedValue("password");
            $table_prefix = FormUtil::getPassedValue("tableprefix");
            $output = '';
            $error = '';

            $dbName = $dbname;
            $dbUser = (!empty($username) ? $username : 'root');
            $dbPswrd = (!empty($dbpassword) ? $dbpassword : '');
            $dbHost = $host;

            $dsn = "mysql:dbname=$dbName;host=$dbHost";
            $user = $dbUser;
            $password = $dbPswrd;
            $tableprefix = (!empty($table_prefix) ? $table_prefix : '');

            $prdquery = "SELECT COUNT(*) AS count  FROM information_schema.tables
                WHERE table_schema = '" . $dbName . "'   AND table_name = '" . $tableprefix . "products'";

            // $output["count"] = $prdquery;
            // AjaxUtil::output($output);

            $dbh = new PDO($dsn, $user, $password);
            $statement1 = Doctrine_Manager::getInstance()->connection($dbh);
            $results = $statement1->execute($prdquery);
            $sValues = $results->fetch();
            $tableCount = $sValues ['count'];

            $statement1 = Doctrine_Manager::getInstance()->closeConnection($statement1);
        } catch (Exception $e) {
            $errCount = 1;
            $error1 = $e->getMessage() . "\n";
            $output ["error"] = $error1;
            // ZSELEX_Util::ajaxOutput($output);
            AjaxUtil::output($output);
            // echo 'Caught exception: ', $e->getMessage(), "\n";
            // die;
        }
        $error2 = '';
        $error3 = '';
        if (empty($domain) || (!checkdnsrr($domain, 'A'))) { // or use ANY or for other see above link
            $errCount = 2;
            $error2 = $this->__('Domain does not exist.') . '<br>';
            // $output["error"] = $error2;
            // AjaxUtil::output($output);
        }

        if ($tableCount < 1) {
            $errCount = 3;
            $error3 = $this->__('Table does not exist. Problem with table prefix') . '<br>';
            // $output["error"] = "Table Doesnt Exists.Problem with table prefix \n";
            // AjaxUtil::output($output);
        }

        if ($errCount > 0) {
            $output ["error"] = $error1 . " " . $error2 . " " . $error3;
            AjaxUtil::output($output);
        } else {
            $output ["success"] = '1';
        }
        // $output = $count;
        // ZSELEX_Util::ajaxOutput($output);
        AjaxUtil::output($output);

        // $output["test"] = $host . " " . $dbname . " " . $username . " " . $password;
    }

    public function getIshopProductsAutocomplete($args) {
        $sql = "SELECT product_id , product_name FROM  zselex_products";

        $query = DBUtil::executeSQL($sql);
        $output = '';
        $result = $query->fetchAll();

        $array = array();

        foreach ($result as $item) {
            $array [] = array(
                'value' => $item ['product_id'],
                'text' => $item ['product_name']
            );
        }
        AjaxUtil::output($array);

        $json = json_encode($array);

        // ZSELEX_Util::ajaxOutput($json);
        $output = $array;

        // return $json;
    }

    public function setSessionForDotd($args) {
        $name = $_REQUEST ['name'];
        $value = $_REQUEST ['value'];

        // $items = array($name => $value);
        // $_SESSION['DOTD'][$name]=$value;
        // $_SESSION['DOTD'][$name]=$value;
        // $_SESSION['DOTD']['test1'] = 'value1';
        // $_SESSION['DOTD']['test2'] = 'value2';

        $_SESSION ['DOTD'] ['elemtName'] = $value;
        $_SESSION ['/'] ['createdotd'] ['elemtName'] = $value;

        // $val = $_SESSION['DOTD'][$name];
        // SessionUtil::setVar('createdotd',$items);
        // ZSELEX_Util::ajaxOutput($_SESSION['DOTD']['elemtName']);
    }

    public function setSessionForDotdDate($args) {
        $name = $_REQUEST ['name'];
        $value = $_REQUEST ['value'];

        $_SESSION ['/'] ['createdotd'] ['dotddate'] = $value;

        $_SESSION ['DOTD'] ['dotddate'] = $value;
        ZSELEX_Util::ajaxOutput($_SESSION ['DOTD'] ['dotddate']);
    }

    public function getCountry1() {
        $input = FormUtil::getPassedValue("input");

        if (!empty($input)) {
            $sql = "SELECT * FROM  zselex_country WHERE country_name like '%" . $input . "%'";
        } else {
            $sql = "SELECT * FROM  zselex_country ORDER BY country_name ASC LIMIT 0 , 30";
        }

        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
        $sValues = $results->fetchAll();

        $count = count($sValues);
        $output ['values'] = '';

        $output ['values'] .= "<select id=countrylist name=formElements[countrylist] onChange='getVal(this.value , this.options[this.selectedIndex].text)' style='width:175px;height:22px'>";
        if ($count != 0) {

            $output ['values'] .= "<option value='0'>" . $this->__('select') . "</option>";
            foreach ($sValues as $row) {

                $output ['values'] .= "<option value='" . $row ['country_id'] . "'>$row[country_name]</option>";
            }
        } else {
            $output ['values'] .= "<option value=''>" . $this->__('no countries') . "</option>";
        }

        $output ['values'] .= "</select>";

        AjaxUtil::output($output);
    }

    public function getCountry() {
        $input = FormUtil::getPassedValue("input");

        if (!empty($input)) {
            $sql = "SELECT * FROM  zselex_country WHERE country_name like '%" . $input . "%'";
        } else {
            $sql = "SELECT * FROM  zselex_country ORDER BY country_name ASC LIMIT 0 , 30";
        }

        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
        $sValues = $results->fetchAll();

        $count = count($sValues);
        $output ['values'] = '';

        // $output['values'] .= "<ul id='menu_selected'>";
        if ($count != 0) {

            $output ['values'] .= "<div class='close' onclick='out(); resetCountryDropdown();' align='right'><font color=blue>" . $this->__('close') . "</font></div>";
            foreach ($sValues as $row) {
                $val = $row ['country_name'];
                $output ['values'] .= "<div onmouseover='chagecolor(this.id)'  onmouseout='hidecolor(this.id)' id='" . $row [country_id] . "' onclick=getlp(this.id)>" . $row [country_name] . "</div>";
            }
        } else {
            $output ['values'] .= "<div class='close' onclick='out(); resetCountryDropdown();' align='right'><font color=blue>close</font></div>";
            $output ['values'] .= "<div>" . $this->__('no countries') . "</div>";
        }

        $output ['select'] .= "<select onclick='outSelect()' id=countrylist name=formElements[countrylist] onChange='getVal(this.value , this.options[this.selectedIndex].text)' style='width:175px;height:22px'>";
        if ($count != 0) {

            $output ['select'] .= "<option value='0'>" . $this->__('select') . "</option>";
            foreach ($sValues as $row) {

                $output ['select'] .= "<option value='" . $row ['country_id'] . "'>$row[country_name]</option>";
            }
        } else {
            $output ['select'] .= "<option value=''>" . $this->__('no countries') . "</option>";
        }

        $output ['select'] .= "</select>";

        // $output['values'] .= "</ul>";

        AjaxUtil::output($output);
    }

    public function resetCountryDropdown() {
        $input = FormUtil::getPassedValue("input");

        if (!empty($input)) {
            $sql = "SELECT * FROM  zselex_country WHERE country_name like '%" . $input . "%'";
        } else {
            $sql = "SELECT * FROM  zselex_country ORDER BY country_name ASC LIMIT 0 , 30";
        }

        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
        $sValues = $results->fetchAll();

        $count = count($sValues);
        $output ['values'] = '';

        // $output['values'] .= "<ul id='menu_selected'>";

        $output ['select'] .= "<select onclick='outSelect()' id=countrylist name=formElements[countrylist] onChange='getVal(this.value , this.options[this.selectedIndex].text)' style='width:175px;height:22px'>";
        if ($count != 0) {

            $output ['select'] .= "<option value='0'>" . $this->__('select') . "</option>";
            foreach ($sValues as $row) {

                $output ['select'] .= "<option value='" . $row ['country_id'] . "'>$row[country_name]</option>";
            }
        } else {
            $output ['select'] .= "<option value=''>" . $this->__('no countries') . "</option>";
        }

        $output ['select'] .= "</select>";

        AjaxUtil::output($output);
    }

    public function test123() {
        $sql = "select * from zselex_country";

        $query = DBUtil::executeSQL($sql);
        $result = $query->fetchAll();
        $array = array();
        foreach ($result as $row) {

            $array [] = array(
                'value' => $row ['country_id'],
                'text' => $row ['country_name']
            );
        }
        // return json_encode($array);

        ZSELEX_Util::ajaxOutput(json_encode($array));
    }

    public function testRegion() {
        $id = $_REQUEST ['id'];

        $sql = "select * from zselex_region where region_id='" . $id . "'";

        $query = DBUtil::executeSQL($sql);
        $result = $query->fetchAll();
        $array = array();
        foreach ($result as $row) {

            $array [] = array(
                'value' => $row ['region_id'],
                'text' => $row ['region_name']
            );
        }
        // return json_encode($array);

        ZSELEX_Util::ajaxOutput(json_encode($array));
    }

    public function getRegionss() {
        $country_id = FormUtil::getPassedValue("id");
        $output ['select'] = '';
        // AjaxUtil::output($output);
        // $sql = "SELECT * FROM zselex_region WHERE region_id='" . $id . "'";
        if (!empty($country_id)) {
            /*
             * $sql = "SELECT * FROM zselex_region a , zselex_parent b
             * WHERE a.region_id=b.childId AND b.childType='REGION' AND b.parentType='COUNTRY'
             * AND b.parentId=$country_id";
             *
             */
            $sql = "SELECT * FROM zselex_region WHERE country_id=$country_id";
        } else {

            $sql = "SELECT * FROM zselex_region";
        }
        // echo $sql;
        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
        $sValues = $results->fetchAll();

        $count = count($sValues);

        /*
         * $output['select'] = '<script type="text/javascript">
         * alert("hellooo"); exit();
         * jQuery(function () {
         * jQuery("#region-combo").sexyCombo({
         * emptyText: "Choose a region..."
         *
         * });
         *
         * });
         * </script>';
         */

        // $output['select'] .= "<input type='hidden' id=test123 value='1234ajax'>";
        $output ['select'] .= "<select id='region-combo' name='formElements[region-combo]'>";

        if ($count > 0) {

            $output ['select'] .= "<option value='0'>" . $this->__('search region') . "</option>";
            foreach ($sValues as $row) {

                $output ['select'] .= "<option value='" . $row ['region_id'] . "'>" . $row [region_name] . "</option>";
            }
        } else {
            $output ['select'] .= "<option value=''>" . $this->__('search region') . "</option>";
        }

        $output ['select'] .= "</select>";

        AjaxUtil::output($output);
    }

    public function getRegionsAll() {
        // exit;
        $country_id = FormUtil::getPassedValue("id");
        $post_region_id = FormUtil::getPassedValue("post_region_id");
        $edit = FormUtil::getPassedValue("edit");
        $output ['select'] = '';
        if ($edit == 'front') {
            $region_id_selected = $_COOKIE ['region_cookie'];
        } else {
            $region_id_selected = $post_region_id;
        }
        // AjaxUtil::output($output);
        // $sql = "SELECT * FROM zselex_region WHERE region_id='" . $id . "'";
        if (!empty($country_id)) {

            $sql = "SELECT region_id , region_name FROM zselex_region WHERE country_id=$country_id";
        } else {

            $sql = "SELECT region_id , region_name FROM zselex_region";
        }
        // echo $sql;

        $query = DBUtil::executeSQL($sql);
        $sValues = $query->fetchAll();

        $count = count($sValues);
        $output ['select'] .= "<select id='region-combo' name='formElements[region-combo]' class='chosen-select-search form-control'>";
        // $selected = '';
        if ($count > 0) {
            $output ['select'] .= "<option value=''>" . $this->__('search region') . "</option>";
            foreach ($sValues as $row) {
                if ($region_id_selected == $row ['region_id']) {
                    $selected = "selected='selected'";
                } else {
                    $selected = '';
                }
                $output ['select'] .= "<option value='" . $row ['region_id'] . "'    $selected>" . $row [region_name] . "</option>";
            }
        } else {
            $output ['select'] .= "<option value=''>" . $this->__('search region') . "</option>";
        }

        $output ['select'] .= "</select>";

        AjaxUtil::output($output);
    }

    public function getRegionss1() {
        $country_id = FormUtil::getPassedValue("id");
        $parentReg = FormUtil::getPassedValue("parentReg");
        if ($country_id < 1) {
            $parentReg = '';
        }
        $output ['select'] = '';
        // AjaxUtil::output($output);
        // $sql = "SELECT * FROM zselex_region WHERE region_id='" . $id . "'";
        if (!empty($country_id)) {
            $sql = "SELECT * FROM zselex_region a , zselex_parent b
                WHERE a.region_id=b.childId AND b.childType='REGION' AND  b.parentType='COUNTRY' 
                AND b.parentId=$country_id";
        } else {

            $sql = "SELECT * FROM zselex_region";
        }
        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
        $sValues = $results->fetchAll();

        $count = count($sValues);

        /*
         * $output['select'] = '<script type="text/javascript">
         * alert("hellooo"); exit();
         * jQuery(function () {
         * jQuery("#region-combo").sexyCombo({
         * emptyText: "Choose a region..."
         *
         * });
         *
         * });
         * </script>';
         */

        // $output['select'] .= "<input type='hidden' id=test123 value='1234ajax'>";
        $output ['select'] .= "<select id='region-combo' name='formElements[region-combo]' size='1'>";

        $selected = '';
        if ($count > 0) {

            $output ['select'] .= "<option value='0'>" . $this->__('search region') . "</option>";
            foreach ($sValues as $row) {
                if ($row ['region_id'] == $parentReg) {
                    $selected = "selected='selected'";
                }

                $output ['select'] .= "<option value='" . $row ['region_id'] . "' $selected>" . $row [region_name] . "</option>";
            }
        } else {
            $output ['select'] .= "<option value=''>" . $this->__('search region') . "</option>";
        }

        $output ['select'] .= "</select>";

        AjaxUtil::output($output);
    }

    public function getCitiess() {
        $region_id = FormUtil::getPassedValue("region_id");
        $country_id = FormUtil::getPassedValue("country_id");

        if ($region_id > 0) {
            $sql = "SELECT * FROM zselex_city a , zselex_parent b
                WHERE a.city_id=b.childId AND b.childType='CITY' AND  b.parentType='REGION' 
                AND b.parentId=$region_id";
        } elseif ($region_id < 1 && $country_id > 0) {

            $sql = "SELECT * FROM zselex_city WHERE city_id IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='REGION'
                 AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='REGION' AND parentType='COUNTRY' AND parentId=$country_id))
                OR
                   city_id IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='COUNTRY' AND parentId=$country_id)
                ";
        } else {

            $sql = "SELECT * FROM zselex_city";
        }

        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
        $sValues = $results->fetchAll();

        $count = count($sValues);
        $output ['cities'] = '';

        $output ['cities'] .= "<select id=city-combo name='formElements[city-combo]'>";
        if ($count != 0) {
            $output ['cities'] .= "<option value='0'>" . $this->__('search city') . "</option>";
            foreach ($sValues as $row) {

                $output ['cities'] .= "<option value='" . $row ['city_id'] . "'>$row[city_name]</option>";
            }
        } else {
            $output ['cities'] .= "<option value=''>" . $this->__('search city') . "</option>";
        }
        $output ['cities'] .= "</select>";

        AjaxUtil::output($output);
    }

    public function getCitiess1() { // for modify shops
        $region_id = FormUtil::getPassedValue("region_id");
        $country_id = FormUtil::getPassedValue("country_id");
        $parentCity = FormUtil::getPassedValue("parentCity");
        if ($region_id < 1) {
            $parentCity = '';
        }

        if ($region_id > 0) {
            $sql = "SELECT * FROM zselex_city a , zselex_parent b
                WHERE a.city_id=b.childId AND b.childType='CITY' AND  b.parentType='REGION' 
                AND b.parentId=$region_id";
        } elseif ($region_id < 1 && $country_id > 0) {

            $sql = "SELECT * FROM zselex_city WHERE city_id IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='REGION'
                 AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='REGION' AND parentType='COUNTRY' AND parentId=$country_id))
                OR
                   city_id IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='COUNTRY' AND parentId=$country_id)
                ";
        } else {

            $sql = "SELECT * FROM zselex_city";
        }

        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
        $sValues = $results->fetchAll();

        $count = count($sValues);
        $output ['cities'] = '';
        $selected = '';

        $output ['cities'] .= "<select id=city-combo name='formElements[city-combo]'>";
        if ($count != 0) {
            $output ['cities'] .= "<option value='0'>" . $this->__('search city') . "</option>";
            foreach ($sValues as $row) {

                if ($row ['city_id'] == $parentCity) {
                    $selected = "selected='selected'";
                } else {
                    $selected = '';
                }
                $output ['cities'] .= "<option value='" . $row ['city_id'] . "' $selected>" . $row [city_name] . "</option>";
            }
        } else {
            $output ['cities'] .= "<option value=''>" . $this->__('search city') . "</option>";
        }
        $output ['cities'] .= "</select>";

        AjaxUtil::output($output);
    }

    function getCountryCityList1() {
        $country_id = FormUtil::getPassedValue("country_id");

        if (!empty($country_id)) {
            /*
             * $sql = "SELECT * FROM zselex_city WHERE city_id IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='REGION'
             * AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='REGION' AND parentType='COUNTRY' AND parentId=$country_id))
             * OR
             * city_id IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='COUNTRY' AND parentId=$country_id)
             * ";
             *
             */
            $sql = "SELECT * FROM zselex_city WHERE country_id=$country_id";
        } else {
            $sql = "SELECT * FROM zselex_city";
        }

        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
        $sValues = $results->fetchAll();
        $count = count($sValues);
        $output = '';

        $output .= "<select id=city-combo name='formElements[city-combo]'>";
        if ($count != 0) {
            $output .= "<option value='0'>" . $this->__('search city') . "</option>";
            foreach ($sValues as $row) {
                $output .= "<option value='" . $row ['city_id'] . "'>" . $row [city_name] . "</option>";
            }
        } else {
            $output .= "<option value=''>" . $this->__('search city') . "</option>";
        }
        $output .= "</select>";

        ZSELEX_Util::ajaxOutput($output);
    }

    function getCitiesMap($args) {
        // exit;
        $country_id = FormUtil::getPassedValue("country_id");
        $region_id = FormUtil::getPassedValue("region_id");
        $region_name = FormUtil::getPassedValue("region_name");
        $append = '';

        $append .= " AND region_id='" . $region_id . "'";

        $sql = "SELECT city_id,city_name
                FROM zselex_city 
                WHERE city_id!=''" . $append;
        // echo $sql;

        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
        $sValues = $results->fetchAll();
        $count = count($sValues);
        $output = '';

        if ($count > 0) {
            foreach ($sValues as $key => $val) {
                $cityId = $val ['city_id'];
                $cityName = $val ['city_name'];
                $output .= "<span><a id=$cityId class='cityselect'  href='' onClick=return(citySelect('" . $cityId . "','" . $cityName . "'))>" . $cityName . "</a></span>" . '<br>';
            }
            $output .= "<span><a class='cityselect'  href='' onClick=return(closeMap())>" . $this->__('Select All cities') . "</a></span>" . '<br>';
        } else {
            $output = 'no cities';
        }
        $finaloutput ['data'] = $output;
        AjaxUtil::output($finaloutput);
    }

    function getCityListAll() {
        // exit;
        $country_id = FormUtil::getPassedValue("country_id");
        $region_id = FormUtil::getPassedValue("region_id");
        $post_city_id = FormUtil::getPassedValue("post_city_id");
        $append = '';

        if (!empty($country_id)) {
            $append .= " AND country_id=$country_id";
        }
        if (!empty($region_id)) {
            $append .= " AND region_id=$region_id";
        }

        $sql = "SELECT city_id,city_name FROM zselex_city WHERE city_id!=''" . $append;

        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
        $sValues = $results->fetchAll();
        $count = count($sValues);
        $output = '';

        $output .= "<select id=city-combo name='formElements[city-combo]'>";
        if ($count != 0) {
            $output .= "<option value='0'>" . $this->__('search city') . "</option>";
            foreach ($sValues as $row) {
                if ($row ['city_id'] == $post_city_id) {
                    $selected = "selected='selected'";
                } else {
                    $selected = '';
                }
                $output .= "<option value='" . $row ['city_id'] . "'  $selected>" . $row [city_name] . "</option>";
            }
        } else {
            $output .= "<option value=''>" . $this->__('search city') . "</option>";
        }
        $output .= "</select>";

        ZSELEX_Util::ajaxOutput($output);
    }

    /**
     * Get all cities in drop down
     *
     * @returns array
     */
    function getCityListAllFront() {
        //echo "getCityListAllFront"; exit;
        $country_id = FormUtil::getPassedValue("country_id");
        $region_id = FormUtil::getPassedValue("region_id");
        $post_city_id = $_COOKIE ['city_cookie'];
        $append = '';

        if (!empty($country_id)) {
            $append .= " AND country_id=$country_id";
        }
        if (!empty($region_id)) {
            $append .= " AND region_id=$region_id";
        }

        $sql = "SELECT city_id,city_name FROM zselex_city WHERE city_id!=''" . $append;
        //echo $sql; exit;

        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
        $sValues = $results->fetchAll();
        $count = count($sValues);
        $output = '';

        $output .= "<select id='city-combo1' name='city_id' size='1' class='chosen-select-search form-control'>";
        if ($count > 0) {
            $output .= "<option value=''>" . $this->__('search city') . "</option>";
            // if ($region_id > 0) {
            foreach ($sValues as $row) {
                if ($row ['city_id'] == $post_city_id) {
                    $selected = "selected='selected'";
                } else {
                    $selected = '';
                }
                $output .= "<option value='" . $row ['city_id'] . "'  $selected>" . $row [city_name] . "</option>";
            }
            // }
        } else {
            $output .= "<option value=''>" . $this->__('search city') . "</option>";
        }
        $output .= "</select>";

        ZSELEX_Util::ajaxOutput($output);
    }

    function getCountryCityList2() {
        $country_id = FormUtil::getPassedValue("country_id");
        $parentCity = FormUtil::getPassedValue("parentCity");
        if ($country_id < 1) {
            $parentCity = '';
        }

        if (!empty($country_id)) {
            $sql = "SELECT * FROM zselex_city WHERE city_id IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='REGION'
                 AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='REGION' AND parentType='COUNTRY' AND parentId=$country_id))
                OR
                   city_id IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='COUNTRY' AND parentId=$country_id)
                ";
        } else {
            $sql = "SELECT * FROM zselex_city";
        }

        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
        $sValues = $results->fetchAll();
        $count = count($sValues);
        $output = '';

        $selected = '';
        $output .= "<select id=city-combo name='formElements[city-combo]'>";
        if ($count != 0) {
            $output .= "<option value='0'>" . $this->__('search city') . "</option>";
            foreach ($sValues as $row) {
                if ($row ['city_id'] == $parentCity) {
                    $selected = "selected='selected'";
                } else {
                    $selected = '';
                }
                $output .= "<option value='" . $row ['city_id'] . "' $selected>" . $row [city_name] . "</option>";
            }
        } else {
            $output .= "<option value=''>" . $this->__('search city') . "</option>";
        }
        $output .= "</select>";

        ZSELEX_Util::ajaxOutput($output);
    }

    function getCountryAreaList() {
        $country_id = FormUtil::getPassedValue("country_id");

        if (!empty($country_id)) {
            $sql = "SELECT * FROM zselex_area WHERE
                    area_id IN(SELECT childId FROM zselex_parent WHERE childType='AREA' AND parentType='CITY' 
                    AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='REGION' AND parentType='COUNTRY' AND parentId=$country_id))
                    OR
                    area_id IN(SELECT childId FROM zselex_parent WHERE childType='AREA' AND parentType='REGION' AND parentId 
                    IN (SELECT childId FROM zselex_parent WHERE childType='REGION' AND parentType='COUNTRY' AND parentId=$country_id))
                    OR
                    area_id IN(SELECT childId FROM zselex_parent WHERE childType='AREA' AND parentType='COUNTRY' AND parentId=$country_id)
                ";
        } else {

            $sql = "SELECT * FROM zselex_area";
        }

        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
        $sValues = $results->fetchAll();
        $count = count($sValues);
        $output = '';

        $output .= "<select id=area-combo name=area>";
        if ($count != 0) {
            $output .= "<option value='0'>" . $this->__('search area') . "</option>";
            foreach ($sValues as $row) {
                $output .= "<option value='" . $row ['area_id'] . "'>" . $row [area_name] . "</option>";
            }
        } else {
            $output .= "<option value=''>" . $this->__('search area') . "</option>";
        }
        $output .= "</select>";

        ZSELEX_Util::ajaxOutput($output);
    }

    function getRegionAreaList() {
        $region_id = FormUtil::getPassedValue("region_id");

        if (!empty($region_id)) {
            $sql = "SELECT * FROM zselex_area WHERE
                    area_id IN(SELECT childId FROM zselex_parent WHERE childType='AREA' AND parentType='CITY' 
                    AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='REGION' AND parentId=$region_id))
                    OR
                    area_id IN(SELECT childId FROM zselex_parent WHERE childType='AREA' AND parentType='REGION' AND parentId=$region_id)
                ";
        } else {

            $sql = "SELECT * FROM zselex_area";
        }

        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
        $sValues = $results->fetchAll();
        $count = count($sValues);
        $output = '';

        $output .= "<select id=area-combo name='formElements[area-combo]'>";
        if ($count != 0) {
            $output .= "<option value='0'>" . $this->__('search area') . "</option>";
            foreach ($sValues as $row) {
                $output .= "<option value='" . $row ['area_id'] . "'>" . $row [area_name] . "</option>";
            }
        } else {
            $output .= "<option value=''>" . $this->__('search area') . "</option>";
        }
        $output .= "</select>";

        ZSELEX_Util::ajaxOutput($output);
    }

    function getRegionAreaList1() {
        $region_id = FormUtil::getPassedValue("region_id");
        $parentArea = FormUtil::getPassedValue("parentArea");
        $currentId = FormUtil::getPassedValue("currentId");
        if ($currentId < 1) {
            $parentArea = '';
        }

        $selected = '';
        if (!empty($region_id)) {
            $sql = "SELECT * FROM zselex_area WHERE
                    area_id IN(SELECT childId FROM zselex_parent WHERE childType='AREA' AND parentType='CITY' 
                    AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='REGION' AND parentId=$region_id))
                    OR
                    area_id IN(SELECT childId FROM zselex_parent WHERE childType='AREA' AND parentType='REGION' AND parentId=$region_id)
                ";
        } else {

            $sql = "SELECT * FROM zselex_area";
        }

        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
        $sValues = $results->fetchAll();
        $count = count($sValues);
        $output = '';

        $output .= "<select id=area-combo name='formElements[area-combo]'>";
        if ($count != 0) {
            $output .= "<option value='0'>" . $this->__('search area') . "</option>";
            foreach ($sValues as $row) {
                if ($row ['area_id'] == $parentArea) {
                    $selected = "selected='selected'";
                } else {
                    $selected = '';
                }
                $output .= "<option value='" . $row ['area_id'] . "' $selected>" . $row [area_name] . "</option>";
            }
        } else {
            $output .= "<option value=''>" . $this->__('search area') . "</option>";
        }
        $output .= "</select>";

        ZSELEX_Util::ajaxOutput($output);
    }

    function getAreaList() {
        $city_id = FormUtil::getPassedValue("city_id");
        $region_id = FormUtil::getPassedValue("region_id");
        $country_id = FormUtil::getPassedValue("country_id");

        if (($country_id > 0) or ( $country_id > 0 && $region_id < 1 && $city_id < 1)) { // COUNTRY
            $qry = " AND area_id IN(SELECT childId FROM zselex_parent WHERE childType='AREA' AND parentType='CITY'
                    AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='REGION' AND parentType='COUNTRY' AND parentId=$country_id))
                    OR
                    area_id IN(SELECT childId FROM zselex_parent WHERE childType='AREA' AND parentType='REGION' AND parentId 
                    IN (SELECT childId FROM zselex_parent WHERE childType='REGION' AND parentType='COUNTRY' AND parentId=$country_id))
                    OR
                    area_id IN(SELECT childId FROM zselex_parent WHERE childType='AREA' AND parentType='COUNTRY' AND parentId=$country_id)";
            // ZSELEX_Util::ajaxOutput($qry);
        }

        if (($region_id > 0) or ( $country_id > 0 && $region_id > 0 && $city_id < 0)) { // REGION
            $qry = " AND area_id IN(SELECT childId FROM zselex_parent WHERE childType='AREA' AND parentType='CITY'
                    AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='REGION' AND parentId=$region_id))
                    OR
                    area_id IN(SELECT childId FROM zselex_parent WHERE childType='AREA' AND parentType='REGION' AND parentId=$region_id)";
        }

        if (($city_id > 0) or ( $region_id > 0 && $city_id > 0 && $country_id > 0)) { // CITY
            $qry = " AND area_id IN(SELECT childId FROM zselex_parent WHERE childType='AREA' AND parentType='CITY'
                    AND parentId=$city_id)";
        }

        $sql = "SELECT * FROM zselex_area WHERE area_id!='' " . $qry;

        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
        $sValues = $results->fetchAll();
        $count = count($sValues);
        $output = '';

        $output .= "<select id=area-combo name='formElements[area-combo]'>";
        if ($count != 0) {
            $output .= "<option value='0'>" . $this->__('search area') . "</option>";
            foreach ($sValues as $row) {
                $output .= "<option value='" . $row ['area_id'] . "'>" . $row [area_name] . "</option>";
            }
        } else {
            $output .= "<option value=''>" . $this->__('search area') . "</option>";
        }
        $output .= "</select>";

        ZSELEX_Util::ajaxOutput($output);
    }

    function getAreaListAll() {
        $city_id = FormUtil::getPassedValue("city_id");
        $region_id = FormUtil::getPassedValue("region_id");
        $country_id = FormUtil::getPassedValue("country_id");
        $post_area_id = FormUtil::getPassedValue("post_area_id");
        $qry = '';

        if (!empty($country_id)) { // COUNTRY
            $qry .= " AND a.country=$country_id";
            // ZSELEX_Util::ajaxOutput($qry);
        }
        if (!empty($region_id)) { // REGION
            $qry .= " AND a.region=$region_id";
        }
        if (!empty($city_id)) { // CITY
            $qry .= " AND a.city=$city_id";
        }

        /*
         * $sql = "SELECT area_id , area_name
         * FROM zselex_area
         * WHERE area_id!='' " . $qry;
         * $query = DBUtil::executeSQL($sql);
         * $sValues = $query->fetchAll();
         */
        $areaArgs = array(
            'sql' => $qry
        );
        $sValues = $this->entityManager->getRepository('ZSELEX_Entity_Area')->getAreas($areaArgs);
        $count = count($sValues);
        $output = '';

        $output .= "<select id=area-combo name='formElements[area-combo]'>";
        if ($count != 0) {
            $output .= "<option value='0'>" . $this->__('search area') . "</option>";
            foreach ($sValues as $row) {
                if ($row ['area_id'] == $post_area_id) {
                    $selected = "selected='selected'";
                } else {
                    $selected = '';
                }
                $output .= "<option value='" . $row ['area_id'] . "'  $selected>" . $row [area_name] . "</option>";
            }
        } else {
            $output .= "<option value=''>" . $this->__('search area') . "</option>";
        }
        $output .= "</select>";

        ZSELEX_Util::ajaxOutput($output);
    }

    function getAreaList1() {
        $city_id = FormUtil::getPassedValue("city_id");
        $region_id = FormUtil::getPassedValue("region_id");
        $country_id = FormUtil::getPassedValue("country_id");
        $currentId = FormUtil::getPassedValue("currentId");
        $parentArea = FormUtil::getPassedValue("parentArea");
        if ($currentId < 1) {
            $parentArea = '';
        }

        $selected = '';

        if (($country_id > 0) or ( $country_id > 0 && $region_id < 1 && $city_id < 1)) { // COUNTRY
            $qry = " AND area_id IN(SELECT childId FROM zselex_parent WHERE childType='AREA' AND parentType='CITY'
                    AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='REGION' AND parentType='COUNTRY' AND parentId=$country_id))
                    OR
                    area_id IN(SELECT childId FROM zselex_parent WHERE childType='AREA' AND parentType='REGION' AND parentId 
                    IN (SELECT childId FROM zselex_parent WHERE childType='REGION' AND parentType='COUNTRY' AND parentId=$country_id))
                    OR
                    area_id IN(SELECT childId FROM zselex_parent WHERE childType='AREA' AND parentType='COUNTRY' AND parentId=$country_id)";
            // ZSELEX_Util::ajaxOutput($qry);
        }

        if (($region_id > 0) or ( $country_id > 0 && $region_id > 0 && $city_id < 0)) { // REGION
            $qry = " AND area_id IN(SELECT childId FROM zselex_parent WHERE childType='AREA' AND parentType='CITY'
                    AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='REGION' AND parentId=$region_id))
                    OR
                    area_id IN(SELECT childId FROM zselex_parent WHERE childType='AREA' AND parentType='REGION' AND parentId=$region_id)";
        }

        if (($city_id > 0) or ( $region_id > 0 && $city_id > 0 && $country_id > 0)) { // CITY
            $qry = " AND area_id IN(SELECT childId FROM zselex_parent WHERE childType='AREA' AND parentType='CITY'
                    AND parentId=$city_id)";
        }

        $sql = "SELECT * FROM zselex_area WHERE area_id!='' " . $qry;

        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
        $sValues = $results->fetchAll();
        $count = count($sValues);
        $output = '';

        $output .= "<select id=area-combo name='formElements[area-combo]'>";
        if ($count != 0) {
            $output .= "<option value='0'>" . $this->__('search area') . "</option>";
            foreach ($sValues as $row) {
                if ($row ['area_id'] == $parentArea) {
                    $selected = "selected='selected'";
                } else {
                    $selected = '';
                }
                $output .= "<option value='" . $row ['area_id'] . "' $selected>" . $row [area_name] . "</option>";
            }
        } else {
            $output .= "<option value=''>" . $this->__('search area') . "</option>";
        }
        $output .= "</select>";
        // ZSELEX_Util::ajaxOutput($sql);

        ZSELEX_Util::ajaxOutput($output);
    }

    function getAllCountry() {
        $sql = "SELECT country_id,country_name FROM zselex_country";
        $query = DBUtil::executeSQL($sql);
        $sValues = $query->fetchAll();
        $count = count($sValues);
        $output = '';

        $output .= "<select id=country-combo name=country >";
        if ($count != 0) {
            $output .= "<option value='0'>" . $this->__('search country') . "</option>";
            foreach ($sValues as $row) {
                $output .= "<option value='" . $row ['country_id'] . "'>$row[country_name]</option>";
            }
        } else {
            $output .= "<option value=''>" . $this->__('search country') . "</option>";
        }
        $output .= "</select>";

        ZSELEX_Util::ajaxOutput($output);
    }

    /*
     * function getAllCat() {
     *
     * $sql = "SELECT * FROM zselex_category";
     *
     *
     * $statement = Doctrine_Manager::getInstance()->connection();
     * $results = $statement->execute($sql);
     * $sValues = $results->fetchAll();
     * $count = count($sValues);
     * $output = '';
     *
     * $output .= "<select id=category name=category onChange=cat(this.value)>";
     * if ($count != 0) {
     * $output .= "<option value=''>-select category-</option>";
     * foreach ($sValues as $row) {
     * $output .= "<option value='" . $row['category_id'] . "'>$row[category_name]</option>";
     * }
     * } else {
     * $output .= "<option value=''>-select category-</option>";
     * }
     * $output .= "</select>";
     *
     * ZSELEX_Util::ajaxOutput($output);
     * }
     */

    /**
     * Get all categories
     *
     * @return Ajax Response
     */
    function getAllCat() {

        /*
         * $sql = "SELECT category_id , category_name FROM zselex_category";
         * $query = DBUtil::executeSQL($sql);
         * $sValues = $query->fetchAll();
         */
        $sValues = $this->entityManager->getRepository('ZSELEX_Entity_Category')->getCategories(array());
        $count = count($sValues);
        $output = '';

        $output .= "<select id=cat-combo name=category class='chosen-select-search form-control'>";
        if ($count != 0) {
            $output .= "<option value=''>" . $this->__('search category') . "</option>";
            foreach ($sValues as $row) {
                $output .= "<option value='" . $row ['category_id'] . "'>" . $row [category_name] . "</option>";
            }
        } else {
            $output .= "<option value=''>" . $this->__('search category') . "</option>";
        }
        $output .= "</select>";

        ZSELEX_Util::ajaxOutput($output);
    }

    function getAllBranch() {
        $sql = "SELECT branch_id , branch_name FROM zselex_branch";
        $query = DBUtil::executeSQL($sql);
        $sValues = $query->fetchAll();
        $count = count($sValues);
        $output = '';

        $output .= "<select id=branch-combo name=branch>";
        if ($count != 0) {
            $output .= "<option value=''>" . $this->__('search branch') . "</option>";
            foreach ($sValues as $row) {
                $output .= "<option value='" . $row ['branch_id'] . "'>" . $row [branch_name] . "</option>";
            }
        } else {
            $output .= "<option value=''>" . $this->__('search branch') . "</option>";
        }
        $output .= "</select>";

        ZSELEX_Util::ajaxOutput($output);
    }

    function test4all() {
        // exit;
        $output = '';
        $countrys = $_REQUEST ['country'];
        $type = $_REQUEST ['types'];

        $country = !empty($countrys) ? str_replace(" ", "%20", $_REQUEST ['country']) : '';
        $state = str_replace(" ", "%20", $_REQUEST ['region']);
        $city = str_replace(" ", "%20", $_REQUEST ['city']);
        $area = str_replace(" ", "%20", $_REQUEST ['area']);
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $zip = '';
        $add = '';

        if ($type == 'shop') {
            $a = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow', array(
                        'table' => 'zselex_shop',
                        'where' => array(
                            "shop_id='" . $shop_id . "'"
                        )
            ));
            $add = $a ['address'];
            $add = str_replace(" ", "%20", $add);
        }

        $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $add . ',+' . $area . ',+' . $city . ',+' . $state . ',+' . $country . '&sensor=false');
        $outputmap = json_decode($geocode); // Store values in variable
        if ($outputmap->status == 'OK') {
            $lat = $outputmap->results [0]->geometry->location->lat;
            $long = $outputmap->results [0]->geometry->location->lng;
        }

        $output ['cntry'] = $country;
        $output ['region'] = FormUtil::getPassedValue('region', null, 'REQUEST');
        $output ['city'] = FormUtil::getPassedValue('city', null, 'REQUEST');
        $output ['area'] = FormUtil::getPassedValue('area', null, 'REQUEST');
        $output ['shop'] = FormUtil::getPassedValue('shop', null, 'REQUEST');
        $output ['lat'] = $lat;
        $output ['lng'] = $long;
        $output ['type'] = $type;
        AjaxUtil::output($output);
    }

    function resetResponse() {
        $output = '';
        ZSELEX_Util::ajaxOutput($output);
    }

    function test4allCat() {
        // exit;
        $countrys = FormUtil::getPassedValue('country', null, 'REQUEST');
        $type = $_REQUEST ['types'];
        $country = str_replace(" ", "%20", $_REQUEST ['country']);
        $state = str_replace(" ", "%20", $_REQUEST ['region']);
        $city = str_replace(" ", "%20", $_REQUEST ['city']);
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $zip = '';
        $add = '';

        if ($type == 'shop') {
            $a = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow', array(
                        'table' => 'zselex_shop',
                        'where' => array(
                            "shop_id='" . $shop_id . "'"
                        )
            ));
            $add = $a ['address'];
            $add = str_replace(" ", "%20", $add);
        }

        $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $add . ',+' . $city . ',+' . $state . ',+' . $country . '&sensor=false');

        $output = json_decode($geocode); // Store values in variable
        if ($output->status == 'OK') {
            $lat = $output->results [0]->geometry->location->lat;
            $long = $output->results [0]->geometry->location->lng;
            $output = '';
        }

        $output ['country'] = $country;
        $output ['region'] = FormUtil::getPassedValue('region', null, 'REQUEST');
        $output ['city'] = FormUtil::getPassedValue('city', null, 'REQUEST');
        $output ['shop'] = FormUtil::getPassedValue('shop', null, 'REQUEST');
        $output ['lat'] = $lat;
        $output ['lng'] = $long;
        $output ['type'] = $type;
        AjaxUtil::output($output);
    }

    public function getShopMapLocation() {
        // Start XML file, create parent node
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $dom = new DOMDocument("1.0");
        $node = $dom->createElement("markers");
        $parnode = $dom->appendChild($node);

        // Select all the rows in the markers table

        $joinInfo = array(
            array(
                'join_table' => 'zselex_country',
                'join_field' => array(
                    'country_id',
                    'country_name'
                ),
                'object_field_name' => array(
                    'country_id',
                    'country_name'
                ),
                'compare_field_table' => 'country_id', // main table
                'compare_field_join' => 'country_id'
            ),
            array(
                'join_table' => 'zselex_region',
                'join_field' => array(
                    'region_id',
                    'region_name'
                ),
                'object_field_name' => array(
                    'region_id',
                    'region_name'
                ),
                'compare_field_table' => 'region_id', // main table
                'compare_field_join' => 'region_id'
            ),
            array(
                'join_table' => 'zselex_city',
                'join_field' => array(
                    'city_id',
                    'city_name'
                ),
                'object_field_name' => array(
                    'city_id',
                    'city_name'
                ),
                'compare_field_table' => 'city_id', // main table
                'compare_field_join' => 'city_id'
            ),
            array(
                'join_table' => 'zselex_area',
                'join_field' => array(
                    'area_id',
                    'area_name'
                ),
                'object_field_name' => array(
                    'area_id',
                    'area_name'
                ),
                'compare_field_table' => 'area_id', // main table
                'compare_field_join' => 'area_id'
            )
        );

        $get = ModUtil::apiFunc('ZSELEX', 'user', 'getByJoin', $args = array(
                    'table' => 'zselex_shop',
                    'where' => "tbl.shop_id=$shop_id",
                    'joinInfo' => $joinInfo
        ));

        // echo "<pre>"; print_r($get); echo "</pre>"; exit;

        $country = $get ['country_name'];
        $region = $get ['region_name'];
        $city = $get ['city_name'];
        $area = $get ['area_name'];
        $address = str_replace(" ", "%20", $get ['address']);

        // $geocode = @file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $address . ',+' . $city . ',+' . $region . ',+' . $country . '&sensor=false');
        $geocode = @file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $address . '&sensor=false');
        $output = json_decode($geocode); // Store values in variable
        if ($output->status == 'OK') {
            $lat = $output->results [0]->geometry->location->lat;
            $long = $output->results [0]->geometry->location->lng;
            // Select all the rows in the markers table
        }

        $array [] = array(
            'lat' => $lat,
            'lng' => $long,
            'name' => $get ['shop_name'],
            'address' => nl2br($get ['address']),
            'city' => $city,
            'area' => $area,
            'region' => $region,
            'country' => $country,
            'type' => 'restaurant'
        );

        // $sql = "SELECT * FROM markers_test";
        // $query = DBUtil::executeSQL($sql);
        // $result = $query->fetchAll();

        header("Content-type: text/xml");

        // Iterate through the rows, adding XML nodes for each
        // while ($row = @mysql_fetch_assoc($result)) {
        foreach ($array as $key => $row) {
            // ADD TO XML DOCUMENT NODE
            $node = $dom->createElement("marker");
            $newnode = $parnode->appendChild($node);
            $newnode->setAttribute("name", $row ['name']);
            $newnode->setAttribute("address", $row ['address']);
            $newnode->setAttribute("city", $city);
            $newnode->setAttribute("country", $country);
            $newnode->setAttribute("region", $region);
            $newnode->setAttribute("area", $area);
            $newnode->setAttribute("lat", $row ['lat']);
            $newnode->setAttribute("lng", $row ['lng']);
            $newnode->setAttribute("type", $row ['type']);
        }

        $output = $dom->saveXML();
        // return new Zikula_Response_Ajax_Plain($output);
        ZSELEX_Util::ajaxOutput($output);
    }

    function getShopMapLocation1() {
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $dom = new DOMDocument("1.0");
        $node = $dom->createElement("markers");
        $parnode = $dom->appendChild($node);

        $cntry = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow', array(
                    'table' => 'zselex_country a , zselex_shop b',
                    'fields' => array(
                        'c.country_name'
                    ),
                    'where' => array(
                        "b.shop_id='" . $shop_id . "'",
                        "a.country_id=b.country_id"
                    )
        ));
        $country = $cntry ['country_name'];

        $cit = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow', array(
                    'table' => 'zselex_city a , zselex_shop b',
                    'fields' => array(
                        'c.city_name'
                    ),
                    'where' => array(
                        "b.shop_id='" . $shop_id . "'",
                        "a.city_id=b.city_id"
                    )
        ));
        $city = $cit ['city_name'];

        $a = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow', array(
                    'table' => 'zselex_shop',
                    'fields' => array(
                        'address'
                    ),
                    'where' => array(
                        "shop_id='" . $shop_id . "'"
                    )
        ));

        // echo "<pre>"; print_r($a); echo "</pre>"; exit;

        $add = str_replace(" ", "%20", $a ['address']);
        $city = $city;
        // $state = urlencode($_POST['state']);
        $country = $country;
        $zip = '';

        $geocode = @file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $add . ',+' . $city . ',+' . $state . ',+' . $country . '&sensor=false');

        $output = json_decode($geocode); // Store values in variable
        if ($output->status == 'OK') {
            $lat = $output->results [0]->geometry->location->lat;
            $long = $output->results [0]->geometry->location->lng;
            // Select all the rows in the markers table
        }

        /*
         * $statement = Doctrine_Manager::getInstance()->connection();
         * $query = "SELECT * FROM markers WHERE 1";
         * $result = $statement->execute($query);
         * $sValues = $result->fetchAll();
         * //$result = mysql_query($query);
         * if (!$result) {
         * die('Invalid query: ' . mysql_error());
         * }
         */
        header("Content-type: text/xml");

        $array [] = array(
            'lat' => $lat,
            'lng' => $long,
            'address' => $a ['address'],
            'type' => 'restaurant'
        );

        // Iterate through the rows, adding XML nodes for each
        // while ($row = @mysql_fetch_assoc($result)) {
        foreach ($array as $row) {
            // ADD TO XML DOCUMENT NODE
            $node = $dom->createElement("marker");
            $newnode = $parnode->appendChild($node);
            // $newnode->setAttribute("name", $row['name']);
            $newnode->setAttribute("address", $row ['address']);
            $newnode->setAttribute("lat", $row ['lat']);
            $newnode->setAttribute("lng", $row ['lng']);
            $newnode->setAttribute("type", $row ['type']);
        }

        echo "helloo world";
        exit();
        $output = '';
        $output = $dom->saveXML();
        // echo $output; exit;

        ZSELEX_Util::ajaxOutput($output);
    }

    function getShopMapZselexLocation() {
        $country_name = FormUtil::getPassedValue('country', null, 'REQUEST');
        $country_id = FormUtil::getPassedValue('country_id', null, 'REQUEST');
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $dom = new DOMDocument("1.0");
        $node = $dom->createElement("markers");
        $parnode = $dom->appendChild($node);

        $country = $country_name;

        // $add = str_replace(" ", "%20", $a['address']);
        // $city = $city;
        // $state = urlencode($_POST['state']);
        // $country = $country;
        // $zip = '';

        $category_id = FormUtil::getPassedValue('category_id', null, 'REQUEST');
        $catquery = '';

        if ($category_id != '') {
            $catquery = " AND s.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='CATEGORY'
                AND parentId=$category_id)";
        }

        $branch_id = FormUtil::getPassedValue('branch_id', null, 'REQUEST');
        $branchquery = '';
        if ($branch_id != '') {
            $branchquery = " AND s.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='BRANCH'
                AND parentId=$branch_id)";
        }

        $statement = Doctrine_Manager::getInstance()->connection();
        $cshp = "select s.address from  zselex_shop s , zselex_parent p where s.shop_id=p.childId
                 and p.childType='SHOP' and p.parentId=$country_id and p.parentType='COUNTRY'" . $catquery . $branchquery;
        $res = $statement->execute($cshp);
        $sValues = $res->fetchAll();

        foreach ($sValues as $val) {
            $add = str_replace(" ", "%20", $val ['address']);
            $geocode = @file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $add . ',+' . $city . ',+' . $state . ',+' . $country . '&sensor=false');
            $output = json_decode($geocode); // Store values in variable
            if ($output->status == 'OK') {
                $lat = $output->results [0]->geometry->location->lat;
                $long = $output->results [0]->geometry->location->lng;
                // Select all the rows in the markers table
                $array [] = array(
                    'lat' => $lat,
                    'lng' => $long,
                    'address' => $val ['address'],
                    'type' => 'restaurant'
                );
            }
        }

        header("Content-type: text/xml");
        // Iterate through the rows, adding XML nodes for each
        // while ($row = @mysql_fetch_assoc($result)) {
        foreach ($array as $row) {
            // ADD TO XML DOCUMENT NODE
            $node = $dom->createElement("marker");
            $newnode = $parnode->appendChild($node);
            $newnode->setAttribute("name", $row ['name']);
            $newnode->setAttribute("address", $row ['address']);
            $newnode->setAttribute("lat", $row ['lat']);
            $newnode->setAttribute("lng", $row ['lng']);
            $newnode->setAttribute("type", $row ['type']);
        }
        $output = '';
        $output = $dom->saveXML();
        ZSELEX_Util::ajaxOutput($output);
    }

    function getShopMapZselexLocationRegion() {
        $region_name = $_REQUEST ['region'];
        $region_id = $_REQUEST ['region_id'];
        // $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $dom = new DOMDocument("1.0");
        $node = $dom->createElement("markers");
        $parnode = $dom->appendChild($node);
        $country = '';
        $state = $region_name;

        // $country = $country_name;
        // $add = str_replace(" ", "%20", $a['address']);
        // $city = $city;
        // $state = urlencode($_POST['state']);
        // $country = $country;
        // $zip = '';
        $category_id = $_REQUEST ['category_id'];
        $catquery = '';
        if ($category_id != '') {
            $catquery = " AND s.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='CATEGORY'
                AND parentId=$category_id)";
        }

        $branch_id = $_REQUEST ['branch_id'];
        $branchquery = '';
        if ($branch_id != '') {
            $branchquery = " AND s.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='BRANCH'
                AND parentId=$branch_id)";
        }

        $statement = Doctrine_Manager::getInstance()->connection();

        $getCountry = "SELECT country_id  , country_name FROM  zselex_country WHERE country_id IN
            (SELECT parentId FROM zselex_parent WHERE parentType='COUNTRY' AND childId= '" . $region_id . "' AND childType='REGION')";
        $resCountry = $statement->execute($getCountry);
        $countryVal = $resCountry->fetch();
        $country = $countryVal ['country_name'];
        $country_id = $countryVal ['country_id'];

        // $cshp = "select s.address from zselex_shop s , zselex_parent p where s.shop_id=p.childId
        // and p.childType='SHOP' and p.parentId=$region_id and p.parentType='REGION'" . $catquery . $branchquery;

        $cshp = "SELECT address FROM zselex_shop WHERE shop_id IN
            (SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='REGION' AND parentId=$region_id) $catquery $branchquery";

        $res = $statement->execute($cshp);
        $sValues = $res->fetchAll();

        foreach ($sValues as $val) {
            $add = str_replace(" ", "%20", $val ['address']);
            $geocode = @file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $add . ',+' . $city . ',+' . $state . ',+' . $country . '&sensor=false');

            $output = json_decode($geocode); // Store values in variable
            if ($output->status == 'OK') {
                $lat = $output->results [0]->geometry->location->lat;
                $long = $output->results [0]->geometry->location->lng;
                // Select all the rows in the markers table

                $array [] = array(
                    'lat' => $lat,
                    'lng' => $long,
                    'address' => $val ['address'],
                    'type' => 'restaurant'
                );
            }
        }

        /*
         * $statement = Doctrine_Manager::getInstance()->connection();
         * $query = "SELECT * FROM markers WHERE 1";
         * $result = $statement->execute($query);
         * $sValues = $result->fetchAll();
         * //$result = mysql_query($query);
         * if (!$result) {
         * die('Invalid query: ' . mysql_error());
         * }
         */
        header("Content-type: text/xml");

        // Iterate through the rows, adding XML nodes for each
        // while ($row = @mysql_fetch_assoc($result)) {
        foreach ($array as $row) {
            // ADD TO XML DOCUMENT NODE
            $node = $dom->createElement("marker");
            $newnode = $parnode->appendChild($node);
            $newnode->setAttribute("name", $row ['name']);
            $newnode->setAttribute("address", $row ['address']);
            $newnode->setAttribute("lat", $row ['lat']);
            $newnode->setAttribute("lng", $row ['lng']);
            $newnode->setAttribute("type", $row ['type']);
        }

        $output = '';
        $output = $dom->saveXML();

        ZSELEX_Util::ajaxOutput($output);
    }

    function getShopMapZselexLocationCity() {
        $cityName = $_REQUEST ['city'];
        $city_id = $_REQUEST ['city_id'];
        // $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $dom = new DOMDocument("1.0");
        $node = $dom->createElement("markers");
        $parnode = $dom->appendChild($node);
        $city = $cityName;
        $state = '';
        $country = '';
        // $country = $country_name;
        // $add = str_replace(" ", "%20", $a['address']);
        // $city = $city;
        // $state = urlencode($_POST['state']);
        // $country = $country;
        // $zip = '';
        $category_id = $_REQUEST ['category_id'];
        $catquery = '';
        if ($category_id != '') {
            $catquery = " AND s.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='CATEGORY'
                AND parentId=$category_id)";
        }

        $branch_id = $_REQUEST ['branch_id'];
        $branchquery = '';
        if ($branch_id != '') {
            $branchquery = " AND s.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='BRANCH'
                AND parentId=$branch_id)";
        }

        $statement = Doctrine_Manager::getInstance()->connection();

        $getReg = "SELECT region_id , region_name FROM   zselex_region WHERE region_id IN
            (SELECT parentId FROM zselex_parent WHERE parentType='REGION' AND childId= '" . $city_id . "' AND childType='CITY')";
        $resReg = $statement->execute($getReg);
        $regVal = $resReg->fetch();
        $state = $regVal ['region_name'];
        $region_id = $regVal ['region_id'];

        $getCountry = "SELECT country_id  , country_name FROM  zselex_country WHERE country_id IN
            (SELECT parentId FROM zselex_parent WHERE parentType='COUNTRY' AND childId= '" . $region_id . "' AND childType='REGION')";
        $resCountry = $statement->execute($getCountry);
        $countryVal = $resCountry->fetch();
        $country = $countryVal ['country_name'];
        $country_id = $countryVal ['country_id'];

        $cshp = "select s.address from  zselex_shop s , zselex_parent p where s.shop_id=p.childId
                 and p.childType='SHOP' and p.parentId=$city_id and p.parentType='CITY'" . $catquery . $branchquery;
        $res = $statement->execute($cshp);
        $sValues = $res->fetchAll();

        foreach ($sValues as $val) {
            $add = str_replace(" ", "%20", $val ['address']);

            $geocode = @file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $add . ',+' . $city . ',+' . $state . ',+' . $country . '&sensor=false');

            $output = json_decode($geocode); // Store values in variable
            if ($output->status == 'OK') {
                $lat = $output->results [0]->geometry->location->lat;
                $long = $output->results [0]->geometry->location->lng;
                // Select all the rows in the markers table

                $array [] = array(
                    'lat' => $lat,
                    'lng' => $long,
                    'address' => $val ['address'],
                    'type' => 'restaurant'
                );
            }
        }

        /*
         * $statement = Doctrine_Manager::getInstance()->connection();
         * $query = "SELECT * FROM markers WHERE 1";
         * $result = $statement->execute($query);
         * $sValues = $result->fetchAll();
         * //$result = mysql_query($query);
         * if (!$result) {
         * die('Invalid query: ' . mysql_error());
         * }
         */
        header("Content-type: text/xml");

        // Iterate through the rows, adding XML nodes for each
        // while ($row = @mysql_fetch_assoc($result)) {
        foreach ($array as $row) {
            // ADD TO XML DOCUMENT NODE
            $node = $dom->createElement("marker");
            $newnode = $parnode->appendChild($node);
            $newnode->setAttribute("name", $row ['name']);
            $newnode->setAttribute("address", $row ['address']);
            $newnode->setAttribute("lat", $row ['lat']);
            $newnode->setAttribute("lng", $row ['lng']);
            $newnode->setAttribute("type", $row ['type']);
        }

        $output = '';
        $output = $dom->saveXML();

        ZSELEX_Util::ajaxOutput($output);
    }

    function getShopMapZselexLocationArea() {
        $area_name = $_REQUEST ['area'];
        $area_id = $_REQUEST ['area_id'];
        // $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $dom = new DOMDocument("1.0");
        $node = $dom->createElement("markers");
        $parnode = $dom->appendChild($node);
        $state = '';
        $city = '';
        $country = '';
        // $country = $country_name;
        // $add = str_replace(" ", "%20", $a['address']);
        // $city = $city;
        // $state = urlencode($_POST['state']);
        // $country = $country;
        // $zip = '';
        $category_id = $_REQUEST ['category_id'];
        $catquery = '';
        if ($category_id != '') {
            $catquery = " AND s.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='CATEGORY'
                AND parentId=$category_id)";
        }

        $branch_id = $_REQUEST ['branch_id'];
        $branchquery = '';
        if ($branch_id != '') {
            $branchquery = " AND s.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='BRANCH'
                AND parentId=$branch_id)";
        }

        $statement = Doctrine_Manager::getInstance()->connection();

        $getCity = "SELECT city_id , city_name FROM zselex_city WHERE city_id IN
            (SELECT parentId FROM zselex_parent WHERE parentType='CITY' AND childId= '" . $area_id . "' AND childType='AREA')";
        $resCity = $statement->execute($getCity);
        $cityVal = $resCity->fetch();
        $city = $cityVal ['city_name'];
        $city_id = $cityVal ['city_id'];

        $getReg = "SELECT region_id , region_name FROM zselex_region WHERE region_id IN
            (SELECT parentId FROM zselex_parent WHERE parentType='REGION' AND childId= '" . $city_id . "' AND childType='CITY')";
        $resReg = $statement->execute($getReg);
        $regVal = $resReg->fetch();
        $state = $regVal ['region_name'];
        $region_id = $regVal ['region_id'];

        $getCountry = "SELECT country_id  , country_name FROM  zselex_country WHERE country_id IN
            (SELECT parentId FROM zselex_parent WHERE parentType='COUNTRY' AND childId= '" . $region_id . "' AND childType='REGION')";
        $resCountry = $statement->execute($getCountry);
        $countryVal = $resCountry->fetch();
        $country = $countryVal ['country_name'];
        $country_id = $countryVal ['country_id'];

        $cshp = "select s.address from  zselex_shop s , zselex_parent p where s.shop_id=p.childId
                 and p.childType='SHOP' and p.parentId=$area_id and p.parentType='AREA'" . $catquery . $branchquery;
        $res = $statement->execute($cshp);
        $sValues = $res->fetchAll();

        // ZSELEX_Util::ajaxOutput($city . ',+' . $state . ',+' . $country);

        foreach ($sValues as $val) {
            $add = str_replace(" ", "%20", $val ['address']);

            $geocode = @file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $add . ',+' . $city . ',+' . $state . ',+' . $country . '&sensor=false');

            $output = json_decode($geocode); // Store values in variable
            if ($output->status == 'OK') {
                $lat = $output->results [0]->geometry->location->lat;
                $long = $output->results [0]->geometry->location->lng;
                // Select all the rows in the markers table

                $array [] = array(
                    'lat' => $lat,
                    'lng' => $long,
                    'address' => $val ['address'],
                    'type' => 'restaurant'
                );
            }
        }

        /*
         * $statement = Doctrine_Manager::getInstance()->connection();
         * $query = "SELECT * FROM markers WHERE 1";
         * $result = $statement->execute($query);
         * $sValues = $result->fetchAll();
         * //$result = mysql_query($query);
         * if (!$result) {
         * die('Invalid query: ' . mysql_error());
         * }
         */
        header("Content-type: text/xml");

        // Iterate through the rows, adding XML nodes for each
        // while ($row = @mysql_fetch_assoc($result)) {
        foreach ($array as $row) {
            // ADD TO XML DOCUMENT NODE
            $node = $dom->createElement("marker");
            $newnode = $parnode->appendChild($node);
            $newnode->setAttribute("name", $row ['name']);
            $newnode->setAttribute("address", $row ['address']);
            $newnode->setAttribute("lat", $row ['lat']);
            $newnode->setAttribute("lng", $row ['lng']);
            $newnode->setAttribute("type", $row ['type']);
        }

        $output = '';
        $output = $dom->saveXML();

        ZSELEX_Util::ajaxOutput($output);
    }

    public function getShopDetailsMap() { // used for getting shops in zselex map
        // exit;
        $dom = new DOMDocument("1.0");
        $node = $dom->createElement("markers");
        $parnode = $dom->appendChild($node);

        $city = '';
        $region = '';
        $country = '';

        $shop_id = FormUtil::getPassedValue("shop_id");
        $country_id = FormUtil::getPassedValue("country_id");
        $region_id = FormUtil::getPassedValue("region_id");
        $city_id = FormUtil::getPassedValue("city_id");
        $area_id = FormUtil::getPassedValue("area_id");
        $search = FormUtil::getPassedValue("hsearch");
        $search = ($search == $this->__('search for...') || $search == $this->__('search')) ? '' : $search;
        $countryname = FormUtil::getPassedValue("countryname");
        $regionname = FormUtil::getPassedValue("regionname");
        $city_name = FormUtil::getPassedValue("city_name");
        $areaname = FormUtil::getPassedValue("areaname");

        $category_id = FormUtil::getPassedValue("category_id");
        $branch_id = FormUtil::getPassedValue("branch_id");

        $append = '';
        if (!empty($country_id) && $country_id > 0) { // COUNTRY
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
            $append .= " AND a.shop_name LIKE '%" . DataUtil::formatForStore($search) . "%'
                             OR a.shop_id IN (SELECT shop_id FROM zselex_advertise WHERE keywords LIKE '%" . DataUtil::formatForStore($search) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_files WHERE keywords LIKE '%" . DataUtil::formatForStore($search) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_gallery WHERE keywords LIKE '%" . DataUtil::formatForStore($search) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_pdf WHERE keywords LIKE '%" . DataUtil::formatForStore($search) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_dotd WHERE keywords LIKE '%" . DataUtil::formatForStore($search) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_products WHERE keywords LIKE '%" . DataUtil::formatForStore($search) . "%')
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_events WHERE shop_event_keywords 
                             LIKE '%" . DataUtil::formatForStore($search) . "%' OR shop_event_name LIKE '%" . DataUtil::formatForStore($search) . "%' OR shop_event_shortdescription LIKE '%" . DataUtil::formatForStore($search) . "%' OR shop_event_description LIKE '%" . DataUtil::formatForStore($search) . "%' OR shop_event_startdate LIKE '%" . DataUtil::formatForStore($search) . "%' OR shop_event_starthour LIKE '%" . DataUtil::formatForStore($search) . "%' OR shop_event_startminute LIKE '%" . DataUtil::formatForStore($search) . "%' OR shop_event_enddate LIKE '%" . DataUtil::formatForStore($search) . "%' OR shop_event_endhour LIKE '%" . DataUtil::formatForStore($search) . "%' OR shop_event_endminute LIKE '%" . DataUtil::formatForStore($search) . "%' OR shop_event_endminute LIKE '%" . DataUtil::formatForStore($search) . "%')          
                             OR a.shop_id IN (SELECT shop_id FROM zselex_shop_news WHERE news_id IN 
                             (SELECT sid FROM news WHERE title LIKE '%" . DataUtil::formatForStore($search) . "%' OR hometext LIKE '%" . DataUtil::formatForStore($search) . "%' OR bodytext LIKE '%" . DataUtil::formatForStore($search) . "%' OR urltitle LIKE '%" . DataUtil::formatForStore($search) . "%'))
            ";
        }

        $sql = "SELECT a.shop_id as shop_id , a.shop_id, a.urltitle,  a.address , area.area_name, city.city_name ,region.region_name , coutry.country_name
                FROM zselex_shop a
                LEFT JOIN zselex_country coutry ON coutry.country_id=a.country_id
                LEFT JOIN zselex_region region ON region.region_id=a.region_id
                LEFT JOIN zselex_city city  ON city.city_id=a.city_id
                LEFT JOIN zselex_area area ON area.area_id=a.area_id
                WHERE a.shop_id IS NOT NULL 
                " . " " . $append;

        // echo $sql; exit;
        // echo "<pre>"; print_r($sql); echo "</pre>";

        $results = DBUtil::executeSQL($sql);
        $sValues = $results->fetchAll();
        $count = count($sValues);

        if ($count > 0) {

            foreach ($sValues as $val) {
                // $add = str_replace(" ", "%20", $val['address']);
                $shop_id = $val ['shop_id'];
                $urltitle = $val ['urltitle'];
                $add = urlencode($val ['address']);
                $city = urlencode($city_name);
                $state = urlencode($regionname);
                $country = urlencode($countryname);
                $address = $add . ',' . $city . ',' . $state . ',' . $country;

                // $geocode = @file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $add . ',+' . $city . ',+' . $state . ',+' . $country . '&sensor=false');

                $geocode = @file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $address . '&sensor=false');
                $output = json_decode($geocode); // Store values in variable

                $test = $add . ',+' . $city . ',+' . $state . ',+' . $country;
                if ($output->status == 'OK') {
                    $lat = $output->results [0]->geometry->location->lat;
                    $long = $output->results [0]->geometry->location->lng;
                    // Select all the rows in the markers table

                    $array [] = array(
                        'lat' => $lat,
                        'lng' => $long,
                        'address' => $val ['address'],
                        'sql' => $sql,
                        'test' => $test,
                        'type' => 'restaurant',
                        'shop_id' => $shop_id,
                        'urltitle' => $urltitle,
                        'error' => 0
                    );
                }
            } // ///

            header("Content-type: text/xml");
            foreach ($array as $row) {
                // ADD TO XML DOCUMENT NODE
                $node = $dom->createElement("marker");
                $newnode = $parnode->appendChild($node);
                $newnode->setAttribute("name", $row ['name']);
                $newnode->setAttribute("address", $row ['address']);
                $newnode->setAttribute("lat", $row ['lat']);
                $newnode->setAttribute("lng", $row ['lng']);
                $newnode->setAttribute("type", $row ['type']);
                $newnode->setAttribute("shop_id", $row ['shop_id']);
                $newnode->setAttribute("urltitle", $row ['urltitle']);
                $newnode->setAttribute("sql", $sql);
                $newnode->setAttribute("test", $test);
                $newnode->setAttribute("error", $row ['error']);
            }

            $output = '';
            $output = $dom->saveXML();
        } else {

            // $output .= "<div align=center> &nbsp;&nbsp;&nbsp;&nbsp; No Shops Found </div>";

            $add = '';
            $city = urlencode($city_name);
            $state = urlencode($regionname);
            $country = urlencode($countryname);
            $address = $add . ',' . $city . ',' . $state . ',' . $country;

            // $geocode = @file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $add . ',+' . $city . ',+' . $state . ',+' . $country . '&sensor=false');

            $geocode = @file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $address . '&sensor=false');
            $output = json_decode($geocode); // Store values in variable

            $test = $add . ',+' . $city . ',+' . $state . ',+' . $country;
            if ($output->status == 'OK') {
                $lat = $output->results [0]->geometry->location->lat;
                $long = $output->results [0]->geometry->location->lng;
                // Select all the rows in the markers table

                $array [] = array(
                    'lat' => $lat,
                    'lng' => $long,
                    'address' => $val ['address'],
                    'sql' => $sql,
                    'test' => $test,
                    'type' => 'restaurant',
                    'error' => 1
                );
            }

            header("Content-type: text/xml");
            foreach ($array as $row) {
                // ADD TO XML DOCUMENT NODE
                $node = $dom->createElement("marker");
                $newnode = $parnode->appendChild($node);
                $newnode->setAttribute("name", $row ['name']);
                $newnode->setAttribute("address", $row ['address']);
                $newnode->setAttribute("lat", $row ['lat']);
                $newnode->setAttribute("lng", $row ['lng']);
                $newnode->setAttribute("type", $row ['type']);
                $newnode->setAttribute("sql", $sql);
                $newnode->setAttribute("test", $test);
                $newnode->setAttribute("error", $row ['error']);
            }

            $output = '';
            $output = $dom->saveXML();
        }
        // "region:" . $region_id . "country:" . $country_id
        // ZSELEX_Util::ajaxOutput("region: " . $region_id . "country: " . $country_id . "city_id: " .$city_id. "shop_id: " .$shop_id);
        ZSELEX_Util::ajaxOutput($output);
    }

    public function getShopDetailsMapFirst() { // used for getting shops in zselex map
        $dom = new DOMDocument("1.0");
        $node = $dom->createElement("markers");
        $parnode = $dom->appendChild($node);

        $city = '';
        $region = '';
        $country = '';

        $shop_id = FormUtil::getPassedValue("shop_id");
        $country_id = FormUtil::getPassedValue("country_id");
        $region_id = FormUtil::getPassedValue("region_id");
        $city_id = FormUtil::getPassedValue("city_id");
        $area_id = FormUtil::getPassedValue("area_id");

        $category_id = FormUtil::getPassedValue("category_id");
        $branch_id = FormUtil::getPassedValue("branch_id");

        $catquery = '';
        if ($category_id != '') {
            $catquery = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='CATEGORY'
                AND parentId=$category_id)";
        }

        if ($category_id != '' && $country_id <= 0 && $region_id <= 0 && $city_id <= 0 && $area_id <= 0 && $shop_id <= 0) {
            $catshop = " WHERE a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='CATEGORY'
                AND parentId=$category_id)";
        }

        $branchquery = '';
        $branchshop = '';
        if ($branch_id != '') {
            $branchquery = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='BRANCH'
                AND parentId=$branch_id)";
        }

        if ($branch_id != '' && $country_id <= 0 && $region_id <= 0 && $city_id <= 0 && $area_id <= 0 && $shop_id <= 0) {
            $branchshop = " WHERE a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='BRANCH'
                AND parentId=$branch_id)";
        }

        $output = '';

        $where = '';

        if (($country_id > 0) or ( $country_id > 0 && $region_id < 0 && $city_id < 0 && $area_id < 0 && $shop_id < 0)) { // COUNTRY
            $where = " WHERE a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP'
        AND parentType='CITY' AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' 
        AND parentType='COUNTRY' AND parentId=$country_id)) $catquery $branchquery
        OR 
        a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' 
        AND parentType='CITY' AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' 
        AND parentType='REGION' AND parentId IN(SELECT
        childId FROM zselex_parent WHERE childType='REGION' AND parentType='COUNTRY' AND parentId=$country_id))) $catquery $branchquery
        OR
        a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='COUNTRY' AND parentId=$country_id) $catquery $branchquery

      ";
        }

        if (($region_id > 0) or ( $country_id > 0 && $region_id > 0 && $city_id < 0 && $area_id < 0 && $shop_id < 0)) { // REGION
            $where = " WHERE a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP'
                AND parentType='CITY' AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='REGION' 
                AND parentId=$region_id)) $catquery $branchquery
                OR
                a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='REGION' AND parentId=$region_id) $catquery $branchquery
            ";
            /*
             * $statement = Doctrine_Manager::getInstance()->connection();
             * $getCountry = "SELECT country_id , country_name FROM zselex_country WHERE country_id IN
             * (SELECT parentId FROM zselex_parent WHERE parentType='COUNTRY' AND childId= '" . $region_id . "' AND childType='REGION')";
             * $resCountry = $statement->execute($getCountry);
             * $countryVal = $resCountry->fetch();
             * $country = $countryVal['country_name'];
             * $country_id = $countryVal['country_id'];
             *
             */
        }

        if (($city_id > 0) or ( $region_id > 0 && $city_id > 0 && $country_id > 0 && $area_id < 0 && $shop_id < 0)) { // CITY
            $where = " WHERE a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='CITY' AND parentId=$city_id) $catquery $branchquery";

            /*
             * $statement = Doctrine_Manager::getInstance()->connection();
             * $getReg = "SELECT region_id , region_name FROM zselex_region WHERE region_id IN
             * (SELECT parentId FROM zselex_parent WHERE parentType='REGION' AND childId= '" . $city_id . "' AND childType='CITY')";
             * $resReg = $statement->execute($getReg);
             * $regVal = $resReg->fetch();
             * $state = $regVal['region_name'];
             * $region_id = $regVal['region_id'];
             *
             * $statement = Doctrine_Manager::getInstance()->connection();
             * $getCountry = "SELECT country_id , country_name FROM zselex_country WHERE country_id IN
             * (SELECT parentId FROM zselex_parent WHERE parentType='COUNTRY' AND childId= '" . $region_id . "' AND childType='REGION')";
             * $resCountry = $statement->execute($getCountry);
             * $countryVal = $resCountry->fetch();
             * $country = $countryVal['country_name'];
             * $country_id = $countryVal['country_id'];
             *
             */
        }

        if (($area_id > 0) or ( $region_id > 0 && $area_id > 0 && $city_id > 0 && $country_id > 0 && $shop_id < 0)) { // AREA
            $where = " WHERE a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='AREA' AND parentId=$area_id) $catquery $branchquery";

            /*
             * $statement = Doctrine_Manager::getInstance()->connection();
             * $getCity = "SELECT city_id , city_name FROM zselex_city WHERE city_id IN
             * (SELECT parentId FROM zselex_parent WHERE parentType='CITY' AND childId= '" . $area_id . "' AND childType='AREA')";
             * $resCity = $statement->execute($getCity);
             * $cityVal = $resCity->fetch();
             * $city = $cityVal['city_name'];
             * $city_id = $cityVal['city_id'];
             *
             * $statement = Doctrine_Manager::getInstance()->connection();
             * $getReg = "SELECT region_id , region_name FROM zselex_region WHERE region_id IN
             * (SELECT parentId FROM zselex_parent WHERE parentType='REGION' AND childId= '" . $city_id . "' AND childType='CITY')";
             * $resReg = $statement->execute($getReg);
             * $regVal = $resReg->fetch();
             * $state = $regVal['region_name'];
             * $region_id = $regVal['region_id'];
             *
             * $statement = Doctrine_Manager::getInstance()->connection();
             * $getCountry = "SELECT country_id , country_name FROM zselex_country WHERE country_id IN
             * (SELECT parentId FROM zselex_parent WHERE parentType='COUNTRY' AND childId= '" . $region_id . "' AND childType='REGION')";
             * $resCountry = $statement->execute($getCountry);
             * $countryVal = $resCountry->fetch();
             * $country = $countryVal['country_name'];
             * $country_id = $countryVal['country_id'];
             *
             */
        }

        if (($shop_id > 0) or ( $shop_id > 0 && $area_id > 0 && $region_id > 0 && $city_id > 0 && $country_id > 0)) { // SHOP
            $where = " WHERE a.shop_id=$shop_id $catquery $branchquery";
        }

        $sql = "SELECT address FROM zselex_shop a  $where $catshop $branchshop";

        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
        $sValues = $results->fetchAll();

        $count = count($sValues);

        if ($count > 0) {

            foreach ($sValues as $val) {
                $add = str_replace(" ", "%20", $val ['address']);

                $geocode = @file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $add . ',+' . $city . ',+' . $state . ',+' . $country . '&sensor=false');

                $output = json_decode($geocode); // Store values in variable
                if ($output->status == 'OK') {
                    $lat = $output->results [0]->geometry->location->lat;
                    $long = $output->results [0]->geometry->location->lng;
                    // Select all the rows in the markers table

                    $array [] = array(
                        'lat' => $lat,
                        'lng' => $long,
                        'address' => $val ['address'],
                        'sql' => $sql,
                        'type' => 'restaurant'
                    );
                }
            } // ///

            header("Content-type: text/xml");
            foreach ($array as $row) {
                // ADD TO XML DOCUMENT NODE
                $node = $dom->createElement("marker");
                $newnode = $parnode->appendChild($node);
                $newnode->setAttribute("name", $row ['name']);
                $newnode->setAttribute("address", $row ['address']);
                $newnode->setAttribute("lat", $row ['lat']);
                $newnode->setAttribute("lng", $row ['lng']);
                $newnode->setAttribute("type", $row ['type']);
                $newnode->setAttribute("sql", $sql);
            }

            $output = '';
            $output = $dom->saveXML();
        } else {

            // $output .= "<div align=center> &nbsp;&nbsp;&nbsp;&nbsp; No Shops Found </div>";
        }
        // "region:" . $region_id . "country:" . $country_id
        // ZSELEX_Util::ajaxOutput("region: " . $region_id . "country: " . $country_id . "city_id: " .$city_id. "shop_id: " .$shop_id);
        ZSELEX_Util::ajaxOutput($output);
    }

    function getShopMapZselexLocationShop() {
        $shopName = $_REQUEST ['shop'];
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        // $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $dom = new DOMDocument("1.0");
        $node = $dom->createElement("markers");
        $parnode = $dom->appendChild($node);

        // $country = $country_name;
        // $add = str_replace(" ", "%20", $a['address']);
        // $city = $city;
        // $state = urlencode($_POST['state']);
        // $country = $country;
        // $zip = '';
        $category_id = $_REQUEST ['category_id'];
        $catquery = '';
        if ($category_id != '') {
            $catquery = " AND shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='CATEGORY'
                AND parentId=$category_id)";
        }
        $branch_id = $_REQUEST ['branch_id'];
        $branchquery = '';
        if ($branch_id != '') {
            $branchquery = " AND s.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='BRANCH'
                AND parentId=$branch_id)";
        }

        $statement = Doctrine_Manager::getInstance()->connection();
        $cshp = "select address from zselex_shop where shop_id=$shop_id" . $catquery . $branchquery;
        $res = $statement->execute($cshp);
        $sValues = $res->fetchAll();

        foreach ($sValues as $val) {
            $add = str_replace(" ", "%20", $val ['address']);
            $geocode = @file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $add . ',+' . $city . ',+' . $state . ',+' . $country . '&sensor=false');

            $output = json_decode($geocode); // Store values in variable
            if ($output->status == 'OK') {
                $lat = $output->results [0]->geometry->location->lat;
                $long = $output->results [0]->geometry->location->lng;
                // Select all the rows in the markers table

                $array [] = array(
                    'lat' => $lat,
                    'lng' => $long,
                    'address' => $val ['address'],
                    'type' => 'restaurant'
                );
            }
        }

        /*
         * $statement = Doctrine_Manager::getInstance()->connection();
         * $query = "SELECT * FROM markers WHERE 1";
         * $result = $statement->execute($query);
         * $sValues = $result->fetchAll();
         * //$result = mysql_query($query);
         * if (!$result) {
         * die('Invalid query: ' . mysql_error());
         * }
         */
        header("Content-type: text/xml");

        // Iterate through the rows, adding XML nodes for each
        // while ($row = @mysql_fetch_assoc($result)) {
        foreach ($array as $row) {
            // ADD TO XML DOCUMENT NODE
            $node = $dom->createElement("marker");
            $newnode = $parnode->appendChild($node);
            $newnode->setAttribute("name", $row ['name']);
            $newnode->setAttribute("address", $row ['address']);
            $newnode->setAttribute("lat", $row ['lat']);
            $newnode->setAttribute("lng", $row ['lng']);
            $newnode->setAttribute("type", $row ['type']);
        }

        $output = '';
        $output = $dom->saveXML();

        ZSELEX_Util::ajaxOutput($output);
    }

    function getShopMapZselexLocationAll() {

        // $this->view->setCaching(false);
        $dom = new DOMDocument("1.0");
        $node = $dom->createElement("markers");
        $parnode = $dom->appendChild($node);

        // $country = $country_name;
        // $add = str_replace(" ", "%20", $a['address']);
        // $city = $city;
        // $state = urlencode($_POST['state']);
        // $country = $country;
        // $zip = '';
        $category_id = FormUtil::getPassedValue('category_id', null, 'REQUEST');
        $branch_id = FormUtil::getPassedValue('branch_id', null, 'REQUEST');
        $catquery = '';
        if ($category_id != '') {
            $catquery = " AND shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='CATEGORY'
                AND parentId=$category_id)";
        }

        if ($branch_id != '') {
            $branchquery = " AND shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='BRANCH'
                AND parentId=$branch_id)";
        }

        $statement = Doctrine_Manager::getInstance()->connection();
        $cshp = "select address from zselex_shop where shop_id!=''" . $catquery . $branchquery;
        $res = $statement->execute($cshp);
        $sValues = $res->fetchAll();

        foreach ($sValues as $val) {
            $add = str_replace(" ", "%20", $val ['address']);
            $geocode = @file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $add . ',+' . $city . ',+' . $state . ',+' . $country . '&sensor=false');

            $output = json_decode($geocode); // Store values in variable
            if ($output->status == 'OK') {
                $lat = $output->results [0]->geometry->location->lat;
                $long = $output->results [0]->geometry->location->lng;
                // Select all the rows in the markers table

                $array [] = array(
                    'lat' => $lat,
                    'lng' => $long,
                    'address' => $val ['address'],
                    'type' => 'restaurant'
                );
            }
        }

        /*
         * $statement = Doctrine_Manager::getInstance()->connection();
         * $query = "SELECT * FROM markers WHERE 1";
         * $result = $statement->execute($query);
         * $sValues = $result->fetchAll();
         * //$result = mysql_query($query);
         * if (!$result) {
         * die('Invalid query: ' . mysql_error());
         * }
         */
        header("Content-type: text/xml");

        // Iterate through the rows, adding XML nodes for each
        // while ($row = @mysql_fetch_assoc($result)) {
        foreach ($array as $row) {
            // ADD TO XML DOCUMENT NODE
            $node = $dom->createElement("marker");
            $newnode = $parnode->appendChild($node);
            $newnode->setAttribute("name", $row ['name']);
            $newnode->setAttribute("address", $row ['address']);
            $newnode->setAttribute("lat", $row ['lat']);
            $newnode->setAttribute("lng", $row ['lng']);
            $newnode->setAttribute("type", $row ['type']);
        }

        $output = '';
        $output = $dom->saveXML();

        ZSELEX_Util::ajaxOutput($output);
    }

    function getShopMapZselexLocationAllDeafult() {
        $dom = new DOMDocument("1.0");
        $node = $dom->createElement("markers");
        $parnode = $dom->appendChild($node);

        // $country = $country_name;
        // $add = str_replace(" ", "%20", $a['address']);
        // $city = $city;
        // $state = urlencode($_POST['state']);
        // $country = $country;
        // $zip = '';
        $country_name = $_REQUEST ['ctrNme'];

        $statement = Doctrine_Manager::getInstance()->connection();
        $cshp = "select address from zselex_shop where shop_id!='' and shop_id in
            (select childId from zselex_parent where childType='SHOP' and parentType='COUNTRY' and parentId in
            (select country_id from  zselex_country where country_name like '%" . $country_name . "%'))";

        $res = $statement->execute($cshp);
        $sValues = $res->fetchAll();

        foreach ($sValues as $val) {
            $add = str_replace(" ", "%20", $val ['address']);
            $geocode = @file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $add . ',+' . $city . ',+' . $state . ',+' . $country . '&sensor=false');

            $output = json_decode($geocode); // Store values in variable
            if ($output->status == 'OK') {
                $lat = $output->results [0]->geometry->location->lat;
                $long = $output->results [0]->geometry->location->lng;
                // Select all the rows in the markers table

                $array [] = array(
                    'lat' => $lat,
                    'lng' => $long,
                    'address' => $val ['address'],
                    'type' => 'restaurant'
                );
            }
        }

        /*
         * $statement = Doctrine_Manager::getInstance()->connection();
         * $query = "SELECT * FROM markers WHERE 1";
         * $result = $statement->execute($query);
         * $sValues = $result->fetchAll();
         * //$result = mysql_query($query);
         * if (!$result) {
         * die('Invalid query: ' . mysql_error());
         * }
         */
        header("Content-type: text/xml");

        // Iterate through the rows, adding XML nodes for each
        // while ($row = @mysql_fetch_assoc($result)) {
        foreach ($array as $row) {
            // ADD TO XML DOCUMENT NODE
            $node = $dom->createElement("marker");
            $newnode = $parnode->appendChild($node);
            $newnode->setAttribute("name", $row ['name']);
            $newnode->setAttribute("address", $row ['address']);
            $newnode->setAttribute("lat", $row ['lat']);
            $newnode->setAttribute("lng", $row ['lng']);
            $newnode->setAttribute("type", $row ['type']);
        }

        $output = '';
        $output = $dom->saveXML();

        ZSELEX_Util::ajaxOutput($output);
    }

    public function getCatMap() {
        $shop_id = FormUtil::getPassedValue("shop_id");
        $country_id = FormUtil::getPassedValue("country_id");
        $region_id = FormUtil::getPassedValue("region_id");
        $city_id = FormUtil::getPassedValue("city_id");

        $country = $_REQUEST ['country_name'];
        $region = $_REQUEST ['region_name'];
        $city = $_REQUEST ['cityName'];

        $category_id = FormUtil::getPassedValue("category_id");

        $startval = FormUtil::getPassedValue("startval");
        $endval = FormUtil::getPassedValue("endval");

        $state = $region;

        $catquery = '';
        if ($category_id != '') {
            $catquery = " AND a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='CATEGORY'
                AND parentId=$category_id)";
        }

        if ($category_id != '' && $country_id <= 0 && $region_id <= 0 && $city_id <= 0 && $shop_id <= 0) {
            $catshop = " WHERE a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='CATEGORY'
                AND parentId=$category_id)";
        }

        $output = '';
        $items = array(
            'id' => $shop_id
        );
        // $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
        $where = '';

        if (($country_id > 0) or ( $country_id > 0 && $region_id < 0 && $city_id < 0 && $shop_id < 0)) { // COUNTRY
            $where = " WHERE a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP'
        AND parentType='CITY' AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' 
        AND parentType='COUNTRY' AND parentId=$country_id)) $catquery
        OR 
        a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' 
        AND parentType='CITY' AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' 
        AND parentType='REGION' AND parentId IN(SELECT
        childId FROM zselex_parent WHERE childType='REGION' AND parentType='COUNTRY' AND parentId=$country_id))) $catquery
        OR
        a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='COUNTRY' AND parentId=$country_id) $catquery

      ";

            $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $add . ',+' . $city . ',+' . $state . ',+' . $country . '&sensor=false');
            $output = json_decode($geocode); // Store values in variable
            $lat = $output->results [0]->geometry->location->lat;
            $long = $output->results [0]->geometry->location->lng;
            $type = 'country';
        }

        if (($region_id > 0) or ( $country_id > 0 && $region_id > 0 && $city_id < 0 && $shop_id < 0)) { // REGION
            $where = " WHERE a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP'
                AND parentType='CITY' AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='REGION' 
                AND parentId=$region_id)) $catquery
                OR
                a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='REGION' AND parentId=$region_id) $catquery
            ";

            $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $add . ',+' . $city . ',+' . $state . ',+' . $country . '&sensor=false');
            $output = json_decode($geocode); // Store values in variable
            $lat = $output->results [0]->geometry->location->lat;
            $long = $output->results [0]->geometry->location->lng;
            $type = 'region';
        }

        if (($city_id > 0) or ( $region_id > 0 && $city_id > 0 && $country_id > 0 && $shop_id < 0)) { // CITY
            $where = " WHERE a.shop_id IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='CITY' AND parentId=$city_id) $catquery";

            $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $add . ',+' . $city . ',+' . $state . ',+' . $country . '&sensor=false');
            $output = json_decode($geocode); // Store values in variable
            $lat = $output->results [0]->geometry->location->lat;
            $long = $output->results [0]->geometry->location->lng;
            $type = 'city';
        }

        if (($shop_id > 0) or ( $shop_id > 0 && $region_id > 0 && $city_id > 0 && $country_id > 0)) { // SHOP
            $where = " WHERE a.shop_id=$shop_id $catquery";
            $a = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow', array(
                        'table' => 'zselex_shop',
                        'where' => array(
                            "shop_id='" . $shop_id . "'"
                        )
            ));
            $add = $a ['address'];
            $add = str_replace(" ", "%20", $add);
            $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $add . ',+' . $city . ',+' . $state . ',+' . $country . '&sensor=false');
            $output = json_decode($geocode); // Store values in variable
            $lat = $output->results [0]->geometry->location->lat;
            $long = $output->results [0]->geometry->location->lng;
            $type = 'shop';
        }

        $sql = "SELECT a.address FROM zselex_shop a
		 LEFT JOIN zselex_serviceshop sv ON sv.shop_id=a.shop_id AND type='minishop'
                 $where $catshop LIMIT $startval , $endval";

        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute($sql);
        $sValues = $results->fetchAll();
    }

    public function testMap() {
        $dom = new DOMDocument("1.0");
        $node = $dom->createElement("markers");
        $parnode = $dom->appendChild($node);

        $statement = Doctrine_Manager::getInstance()->connection();
        $cshp = "select * from markers";

        $res = $statement->execute($cshp);
        $sValues = $res->fetchAll();

        foreach ($sValues as $val) {

            $array [] = array(
                'lat' => $val ['lat'],
                'lng' => $val ['lng'],
                'name' => $val ['name'],
                'address' => $val ['address'],
                'type' => 'restaurant'
            );
        }

        header("Content-type: text/xml");

        // Iterate through the rows, adding XML nodes for each
        // while ($row = @mysql_fetch_assoc($result)) {
        foreach ($array as $row) {
            // ADD TO XML DOCUMENT NODE
            $node = $dom->createElement("marker");
            $newnode = $parnode->appendChild($node);
            $newnode->setAttribute("name", $row ['name']);
            $newnode->setAttribute("address", $row ['address']);
            $newnode->setAttribute("lat", $row ['lat']);
            $newnode->setAttribute("lng", $row ['lng']);
            $newnode->setAttribute("type", $row ['type']);
        }

        $output = '';
        $output = $dom->saveXML();

        ZSELEX_Util::ajaxOutput($output);
    }

    public function testMapSend() {

        // ZSELEX_Util::ajaxOutput($output);exit;
        $markerId = addslashes(FormUtil::getPassedValue("markerId"));
        $markerName = addslashes(FormUtil::getPassedValue("name"));
        $markerDesc = addslashes(FormUtil::getPassedValue("desc"));
        $markerPosition = addslashes(FormUtil::getPassedValue("position"));
        $markerLat = addslashes(FormUtil::getPassedValue("lat"));
        $markerLng = addslashes(FormUtil::getPassedValue("lng"));

        $count = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $args = array(
                    'table' => 'markers',
                    'where' => "lat=$markerLat AND lng=$markerLng"
        ));

        if ($count < 1) {
            $sql = "INSERT INTO markers (name , address , lat , lng) VALUES ('" . $markerName . "' , '" . $markerDesc . "' , '" . $markerLat . "' , '" . $markerLng . "')";
            $statement = Doctrine_Manager::getInstance()->connection();
            $results = $statement->execute($sql);
        }
        $output = 'test';
        ZSELEX_Util::ajaxOutput($output);
    }

    public function getRoadMap() {
        $dom = new DOMDocument("1.0");
        $node = $dom->createElement("markers");
        $parnode = $dom->appendChild($node);

        $output = '';

        $sql = "SELECT * FROM markers";

        $results = DBUtil::executeSQL($sql);
        $sValues = $results->fetchAll();

        foreach ($sValues as $val) {
            $array [] = array(
                'lat' => $val ['lat'],
                'lng' => $val ['lng'],
                'lat2' => $val ['lat2'],
                'lng2' => $val ['lng2'],
                'address' => $val ['address'],
                'address2' => $val ['address2'],
                'type' => 'restaurant'
            );
        }

        // echo "<pre>"; print_r($sValues); echo "</pre>";

        $count = count($sValues);

        header("Content-type: text/xml");
        foreach ($sValues as $row) {
            // ADD TO XML DOCUMENT NODE
            $node = $dom->createElement("marker");
            $newnode = $parnode->appendChild($node);
            $newnode->setAttribute("name", $row ['name']);
            $newnode->setAttribute("address", $row ['address']);
            $newnode->setAttribute("address2", $row ['address2']);
            $newnode->setAttribute("lat", $row ['lat']);
            $newnode->setAttribute("lng", $row ['lng']);
            $newnode->setAttribute("lat2", $row ['lat2']);
            $newnode->setAttribute("lng2", $row ['lng2']);
            $newnode->setAttribute("type", $row ['type']);
            // $newnode->setAttribute("sql", $test);
        }

        $output = '';
        $output = $dom->saveXML();

        // "region:" . $region_id . "country:" . $country_id
        // ZSELEX_Util::ajaxOutput("region: " . $region_id . "country: " . $country_id . "city_id: " .$city_id. "shop_id: " .$shop_id);
        ZSELEX_Util::ajaxOutput($output);
    }

    public function createProductWithImage() {
        $output = '';
        $randomName = '';
        // $destination = 'zselexdata/' . $ownerName . '/minisiteimages';
        // The posted data, for reference
        $file = FormUtil::getPassedValue('value', null, 'POST');
        $name = FormUtil::getPassedValue('name', null, 'POST');
        $size = FormUtil::getPassedValue('filesize', null, 'POST');
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'POST');

        $servicecheck = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow', $args = array(
                    'table' => 'zselex_serviceshop',
                    'where' => array(
                        "shop_id=$shop_id",
                        "type='addproducts'"
                    )
        ));
        $servicecount = $servicecheck ['quantity'] - $servicecheck ['availed'];

        if ($servicecount < 1) {
            $output = $randomName . ":limitover:" . $this->__("Your service limit is over");
            ZSELEX_Util::ajaxOutput($output);
            return;
        }

        $diskquota = ModUtil::apiFunc('ZSELEX', 'admin', 'checkDiskquota', $args = array(
                    'shop_id' => $shop_id
        ));
        $allsize = $diskquota ['sizeused'] + $size;
        if ($diskquota ['count'] < 1) {
            $output = $randomName . ":limitover:" . $this->__("You need to buy Diskquota service to upload images");
            ZSELEX_Util::ajaxOutput($output);
            return;
        } else if ($diskquota ['limitover'] < 1) {
            $output = $randomName . ":limitover:" . $this->__("File was not uploaded. Your disquota is exceeded for this shop. Please upgrade.");
            ZSELEX_Util::ajaxOutput($output);
            return;
        } else if ($allsize >= $diskquota ['sizelimit']) {
            $output = $randomName . ":limitover:" . $this->__("File was not uploaded. You need more disquoata to upload this file. Please upgrade.");
            ZSELEX_Util::ajaxOutput($output);
            return;
        }

        $path_parts = pathinfo($name);
        $fileName = $path_parts ['filename'];

        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner', $args = array(
                    'shop_id' => $shop_id
        ));

        $basepath = $_SERVER ['DOCUMENT_ROOT'];
        // $uploaddir = $basepath . "/zselexdata/$ownerName/minisiteimages/";
        $uploaddir = "zselexdata/$ownerName/products/";
        $destination = "zselexdata/$ownerName/products";

        // Get the mime
        $getMime = explode('.', $name);
        $mime = end($getMime);

        // Separate out the data
        $data = explode(',', $file);

        // Encode it correctly
        $encodedData = str_replace(' ', '+', $data [1]);
        $decodedData = base64_decode($encodedData);

        // You can use the name given, or create a random name.
        // We will create a random name!
        $randomName = substr_replace(sha1(microtime(true)), '', 12) . "_$fileName" . '.' . $mime;

        // $randomName = $mime;

        $loguser = UserUtil::getVar('uid');
        $loguser = !empty($loguser) ? $loguser : 0;

        // make directories if not exist.
        $this->createfolder($args = array(
            'ownerName' => $ownerName,
            'itemName' => 'products'
        ));

        if (@file_put_contents($uploaddir . $randomName, $decodedData)) {

            // if ($this->doUploadFile($randomName, $destination)) {
            // echo $randomName . ":uploaded successfully";

            $this->resizeImages($destination, $randomName);

            $items = array(
                'shop_id' => $shop_id,
                'product_name' => $fileName,
                'urltitle' => $fileName,
                'prd_image' => $randomName,
                'prd_status' => '0'
            );

            $args = array(
                'table' => 'zselex_products',
                'element' => $items,
                'Id' => 'product_id'
            );
            $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $args);

            if ($result == true) {
                $user_id = UserUtil::getVar('uid');
                $serviceupdatearg = array(
                    'user_id' => $user_id,
                    'type' => 'addproducts',
                    'shop_id' => $shop_id
                );
                $serviceavailed = ModUtil::apiFunc('ZSELEX', 'admin', 'updateServiceUsed', $serviceupdatearg);
            }

            $servicecheck = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow', $args = array(
                        'table' => 'zselex_serviceshop',
                        'where' => array(
                            "shop_id=$shop_id",
                            "type='addproducts'"
                        )
            ));
            $servicecount = $servicecheck ['quantity'] - $servicecheck ['availed'];

            $output = $randomName . ":uploadedsuccessfully:$servicecount";
            ZSELEX_Util::ajaxOutput($output);
            // return true;
        } else {
            // Show an error message should something go wrong.
            // echo "Something went wrong. Check that the file isn't corrupted";
            $output = "Something went wrong. Check that the file isn't corrupted";
            ZSELEX_Util::ajaxOutput($output);
        }
    }

    public function creategalleryImage() {
        $output = '';
        $randomName = '';
        // $destination = 'zselexdata/' . $ownerName . '/minisiteimages';
        // The posted data, for reference
        $file = $_POST ['value'];
        $name = $_POST ['name'];
        $size = $_POST ['filesize'];
        $shop_id = $_POST ['shop_id'];

        $servicecheck = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow', $args = array(
                    'table' => 'zselex_serviceshop',
                    'where' => array(
                        "shop_id=$shop_id",
                        "type='minisitegallery'"
                    )
        ));
        $servicecount = $servicecheck ['quantity'] - $servicecheck ['availed'];

        if ($servicecount < 1) {
            $output = $randomName . ":limitover:" . $this->__("Your service limit is over");
            ZSELEX_Util::ajaxOutput($output);
            return;
        }

        $diskquota = ModUtil::apiFunc('ZSELEX', 'admin', 'checkDiskquota', $args = array(
                    'shop_id' => $shop_id
        ));
        $allsize = $diskquota ['sizeused'] + $size;
        if ($diskquota ['count'] < 1) {
            $output = $randomName . ":limitover:" . $this->__("You need to buy Diskquota service to upload images");
            ZSELEX_Util::ajaxOutput($output);
            return;
        } else if ($diskquota ['limitover'] < 1) {
            $output = $randomName . ":limitover:" . $this->__("File was not uploaded. Your disquota is exceeded for this shop. Please upgrade.");
            ZSELEX_Util::ajaxOutput($output);
            return;
        } else if ($allsize >= $diskquota ['sizelimit']) {
            $output = $randomName . ":limitover:" . $this->__("File was not uploaded. You need more disquoata to upload this file. Please upgrade.");
            ZSELEX_Util::ajaxOutput($output);
            return;
        }

        $path_parts = pathinfo($name);
        $fileName = $path_parts ['filename'];

        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner', $args = array(
                    'shop_id' => $shop_id
        ));

        $basepath = $_SERVER ['DOCUMENT_ROOT'];
        // $uploaddir = $basepath . "/zselexdata/$ownerName/minisiteimages/";
        $uploaddir = "zselexdata/$ownerName/minisitegallery/";
        $destination = "zselexdata/$ownerName/minisitegallery";

        // Get the mime
        $getMime = explode('.', $name);
        $mime = end($getMime);

        // Separate out the data
        $data = explode(',', $file);

        // Encode it correctly
        $encodedData = str_replace(' ', '+', $data [1]);
        $decodedData = base64_decode($encodedData);

        // You can use the name given, or create a random name.
        // We will create a random name!
        $randomName = substr_replace(sha1(microtime(true)), '', 12) . "_$fileName" . '.' . $mime;

        // $randomName = $mime;

        $loguser = UserUtil::getVar('uid');
        $loguser = !empty($loguser) ? $loguser : 0;

        // make directories if not exist.
        $this->createfolder($args = array(
            'ownerName' => $ownerName,
            'itemName' => 'minisitegallery'
        ));

        if (@file_put_contents($uploaddir . $randomName, $decodedData)) {

            // if ($this->doUploadFile($randomName, $destination)) {
            // echo $randomName . ":uploaded successfully";

            $this->resizeImages($destination, $randomName);

            $where = " shop_id='" . $shop_id . "' AND defaultImg='1'";
            $getCountArgs = array(
                'table' => 'zselex_shop_gallery',
                'where' => $where,
                'Id' => 'gallery_id'
            );

            $dfltImgcount_count = ModUtil::apiFunc('ZSELEX', 'admin', 'countElements', $getCountArgs);
            // echo $dfltImgcount_count; exit;

            if ($dfltImgcount_count < 1) {
                $addDefault = 1;
            } else {
                $addDefault = 0;
            }

            $insertImgeId = "INSERT INTO  zselex_shop_gallery (image_name,shop_id,user_id,defaultImg)VALUES('" . $randomName . "','" . $shop_id . "' , '" . $loguser . "' ,'" . $addDefault . "')";
            $statement = Doctrine_Manager::getInstance()->connection();
            $results = $statement->execute($insertImgeId);

            $user_id = UserUtil::getVar('uid');
            $serviceupdatearg = array(
                'user_id' => $user_id,
                'type' => 'minisitegallery',
                'shop_id' => $shop_id
            );
            $serviceavailed = ModUtil::apiFunc('ZSELEX', 'admin', 'updateServiceUsed', $serviceupdatearg);

            $servicecheck = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow', $args = array(
                        'table' => 'zselex_serviceshop',
                        'where' => array(
                            "shop_id=$shop_id",
                            "type='minisitegallery'"
                        )
            ));
            $servicecount = $servicecheck ['quantity'] - $servicecheck ['availed'];

            $output = $randomName . ":uploadedsuccessfully:$servicecount";
            ZSELEX_Util::ajaxOutput($output);
            // return true;
        } else {
            // Show an error message should something go wrong.
            // echo "Something went wrong. Check that the file isn't corrupted";
            $output = "Something went wrong. Check that the file isn't corrupted";
            ZSELEX_Util::ajaxOutput($output);
        }
    }

    public function updategalleryImage() {
        $output = '';

        // $destination = 'zselexdata/' . $ownerName . '/minisiteimages';
        // The posted data, for reference
        $file = $_POST ['value'];
        $name = $_POST ['name'];
        $size = $_POST ['filesize'];
        $shop_id = $_POST ['shop_id'];
        $item_id = $_POST ['item_id'];

        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner', $args = array(
                    'shop_id' => $shop_id
        ));

        $existingImage = $_POST ['existingImage'];

        if (!empty($existingImage)) {
            $existingFileSize = filesize('zselexdata/' . $ownerName . '/minisitegallery/' . $existingImage);
        } else {
            $existingFileSize = 0;
        }

        $diskquota = ModUtil::apiFunc('ZSELEX', 'admin', 'checkDiskquota', $args = array(
                    'shop_id' => $shop_id
        ));
        $allsize = $diskquota ['sizeused'] + $size;
        $allsize1 = $diskquota ['sizeused'] - $existingFileSize;
        $allsizes = $allsize1 + $size;

        if ($diskquota ['count'] < 1) {
            $output = $randomName . ":limitover:" . $this->__("You need to buy Diskquota service to upload images");
            ZSELEX_Util::ajaxOutput($output);
            return;
        } else if ($allsizes >= $diskquota ['sizelimit']) {
            $output = $randomName . ":limitover:" . $this->__("File was not uploaded. You need more disquoata to upload this file. Please upgrade.");
            ZSELEX_Util::ajaxOutput($output);
            return;
        }

        $path_parts = pathinfo($name);
        $fileName = $path_parts ['filename'];

        $uploaddir = "zselexdata/$ownerName/minisitegallery/";
        $destination = "zselexdata/$ownerName/minisitegallery";

        // Get the mime
        $getMime = explode('.', $name);
        $mime = end($getMime);

        // Separate out the data
        $data = explode(',', $file);

        // Encode it correctly
        $encodedData = str_replace(' ', '+', $data [1]);
        $decodedData = base64_decode($encodedData);

        // You can use the name given, or create a random name.
        // We will create a random name!
        $randomName = substr_replace(sha1(microtime(true)), '', 12) . "_$fileName" . '.' . $mime;

        // $randomName = $mime;

        $loguser = UserUtil::getVar('uid');
        $loguser = !empty($loguser) ? $loguser : 0;

        // make directories if not exist.
        $this->createfolder($args = array(
            'ownerName' => $ownerName,
            'itemName' => 'minisitegallery'
        ));

        if (file_put_contents($uploaddir . $randomName, $decodedData)) {

            if (file_exists('zselexdata/' . $ownerName . '/minisitegallery/' . $existingImage)) {
                unlink('zselexdata/' . $ownerName . '/minisitegallery/' . $existingImage);
            }
            if (file_exists('zselexdata/' . $ownerName . '/minisitegallery/fullsize/' . $existingImage)) {
                unlink('zselexdata/' . $ownerName . '/minisitegallery/fullsize/' . $existingImage);
            }

            if (file_exists('zselexdata/' . $ownerName . '/minisitegallery/medium/' . $existingImage)) {
                unlink('zselexdata/' . $ownerName . '/minisitegallery/medium/' . $existingImage);
            }

            if (file_exists('zselexdata/' . $ownerName . '/minisitegallery/thumb/' . $existingImage)) {
                unlink('zselexdata/' . $ownerName . '/minisitegallery/thumb/' . $existingImage);
            }

            // echo $randomName . ":uploaded successfully";
            $this->resizeImages($destination, $randomName);

            $where = " shop_id='" . $shop_id . "' AND defaultImg='1'";
            $getCountArgs = array(
                'table' => 'zselex_shop_gallery',
                'where' => $where,
                'Id' => 'gallery_id'
            );

            $dfltImgcount_count = ModUtil::apiFunc('ZSELEX', 'admin', 'countElements', $getCountArgs);
            // echo $dfltImgcount_count; exit;

            if ($dfltImgcount_count < 1) {
                $addDefault = 1;
            } else {
                $addDefault = 0;
            }
            $obj = array(
                'image_name' => $randomName
            );
            // }

            $pntables = pnDBGetTables();
            $column = $pntables ['zselex_shop_gallery_column'];
            $where = "WHERE $column[gallery_id]=$item_id";
            DBUTil::updateObject($obj, 'zselex_shop_gallery', $where);

            $output = $randomName . ":uploadedsuccessfully";
            ZSELEX_Util::ajaxOutput($output);
            // return true;
        } else {
            // Show an error message should something go wrong.
            // echo "Something went wrong. Check that the file isn't corrupted";
            $output = "Something went wrong. Check that the file isn't corrupted";
            ZSELEX_Util::ajaxOutput($output);
        }
    }

    public function createImage() {
        $output = '';
        $randomName = '';
        // $destination = 'zselexdata/' . $ownerName . '/minisiteimages';
        // The posted data, for reference
        $file = $_POST ['value'];
        $name = $_POST ['name'];
        $size = $_POST ['filesize'];
        $shop_id = $_POST ['shop_id'];

        $servicecheck = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow', $args = array(
                    'table' => 'zselex_serviceshop',
                    'where' => array(
                        "shop_id=$shop_id",
                        "type='minisiteimages'"
                    )
        ));
        $servicecount = $servicecheck ['quantity'] - $servicecheck ['availed'];

        if ($servicecount < 1) {
            $output = $randomName . ":limitover:" . $this->__("Your service limit is over");
            ZSELEX_Util::ajaxOutput($output);
            return;
        }

        $diskquota = ModUtil::apiFunc('ZSELEX', 'admin', 'checkDiskquota', $args = array(
                    'shop_id' => $shop_id
        ));
        $allsize = $diskquota ['sizeused'] + $size;
        if ($diskquota ['count'] < 1) {
            $output = $randomName . ":limitover:" . $this->__("You need to buy Diskquota service to upload images");
            ZSELEX_Util::ajaxOutput($output);
            return;
        } else if ($diskquota ['limitover'] < 1) {
            $output = $randomName . ":limitover:" . $this->__("File was not uploaded. Your disquota is exceeded for this shop. Please upgrade.");
            ZSELEX_Util::ajaxOutput($output);
            return;
        } else if ($allsize >= $diskquota ['sizelimit']) {
            $output = $randomName . ":limitover:" . $this->__("File was not uploaded. You need more disquoata to upload this file. Please upgrade.");
            ZSELEX_Util::ajaxOutput($output);
            return;
        }

        $path_parts = pathinfo($name);
        $fileName = $path_parts ['filename'];

        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner', $args = array(
                    'shop_id' => $shop_id
        ));

        $basepath = $_SERVER ['DOCUMENT_ROOT'];
        // $uploaddir = $basepath . "/zselexdata/$ownerName/minisiteimages/";
        $uploaddir = "zselexdata/$ownerName/minisiteimages/";
        $destination = "zselexdata/$ownerName/minisiteimages";

        // Get the mime
        $getMime = explode('.', $name);
        $mime = end($getMime);

        // Separate out the data
        $data = explode(',', $file);

        // Encode it correctly
        $encodedData = str_replace(' ', '+', $data [1]);
        $decodedData = base64_decode($encodedData);

        // You can use the name given, or create a random name.
        // We will create a random name!
        $randomName = substr_replace(sha1(microtime(true)), '', 12) . "_$fileName" . '.' . $mime;

        // $randomName = $mime;

        $loguser = UserUtil::getVar('uid');
        $loguser = !empty($loguser) ? $loguser : 0;

        // make directories if not exist.
        $this->createfolder($args = array(
            'ownerName' => $ownerName,
            'itemName' => 'minisiteimages'
        ));

        if (@file_put_contents($uploaddir . $randomName, $decodedData)) {

            // if ($this->doUploadFile($randomName, $destination)) {
            // echo $randomName . ":uploaded successfully";

            $this->resizeImages($destination, $randomName);

            $where = " shop_id='" . $shop_id . "' AND defaultImg='1'";
            $getCountArgs = array(
                'table' => 'zselex_files',
                'where' => $where,
                'Id' => 'file_id'
            );

            $dfltImgcount_count = ModUtil::apiFunc('ZSELEX', 'admin', 'countElements', $getCountArgs);
            // echo $dfltImgcount_count; exit;

            if ($dfltImgcount_count < 1) {
                $addDefault = 1;
            } else {
                $addDefault = 0;
            }

            $insertImgeId = "INSERT INTO zselex_files (name,shop_id,user_id,defaultImg)VALUES('" . $randomName . "' , '" . $shop_id . "' , '" . $loguser . "' ,'" . $addDefault . "')";
            $statement = Doctrine_Manager::getInstance()->connection();
            $results = $statement->execute($insertImgeId);

            $user = UserUtil::getVar('uid');
            $serviceupdatearg = array(
                'user_id' => $user,
                'type' => 'minisiteimages',
                'shop_id' => $shop_id
            );
            $serviceavailed = ModUtil::apiFunc('ZSELEX', 'admin', 'updateServiceUsed', $serviceupdatearg);

            $servicecheck = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow', $args = array(
                        'table' => 'zselex_serviceshop',
                        'where' => array(
                            "shop_id=$shop_id",
                            "type='minisiteimages'"
                        )
            ));
            $servicecount = $servicecheck ['quantity'] - $servicecheck ['availed'];

            $output = $randomName . ":uploadedsuccessfully:$servicecount";
            ZSELEX_Util::ajaxOutput($output);
            // return true;
        } else {
            // Show an error message should something go wrong.
            // echo "Something went wrong. Check that the file isn't corrupted";
            $output = "Something went wrong. Check that the file isn't corrupted";
            ZSELEX_Util::ajaxOutput($output);
        }
    }

    public function updateImage() {
        $output = '';

        // $destination = 'zselexdata/' . $ownerName . '/minisiteimages';
        // The posted data, for reference
        $file = $_POST ['value'];
        $name = $_POST ['name'];
        $size = $_POST ['filesize'];
        $shop_id = $_POST ['shop_id'];
        $item_id = $_POST ['item_id'];

        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner', $args = array(
                    'shop_id' => $shop_id
        ));

        $existingImage = $_POST ['existingImage'];

        if (!empty($existingImage)) {
            $existingFileSize = filesize('zselexdata/' . $ownerName . '/minisiteimages/' . $existingImage);
        } else {
            $existingFileSize = 0;
        }

        $diskquota = ModUtil::apiFunc('ZSELEX', 'admin', 'checkDiskquota', $args = array(
                    'shop_id' => $shop_id
        ));
        $allsize = $diskquota ['sizeused'] + $size;
        $allsize1 = $diskquota ['sizeused'] - $existingFileSize;
        $allsizes = $allsize1 + $size;

        if ($diskquota ['count'] < 1) {
            $output = $randomName . ":limitover:" . $this->__("You need to buy Diskquota service to upload images");
            ZSELEX_Util::ajaxOutput($output);
            return;
        } else if ($allsizes >= $diskquota ['sizelimit']) {
            $output = $randomName . ":limitover:" . $this->__("File was not uploaded. You need more disquoata to upload this file. Please upgrade.");
            ZSELEX_Util::ajaxOutput($output);
            return;
        }

        $path_parts = pathinfo($name);
        $fileName = $path_parts ['filename'];

        $uploaddir = "zselexdata/$ownerName/minisiteimages/";
        $destination = "zselexdata/$ownerName/minisiteimages";

        // Get the mime
        $getMime = explode('.', $name);
        $mime = end($getMime);

        // Separate out the data
        $data = explode(',', $file);

        // Encode it correctly
        $encodedData = str_replace(' ', '+', $data [1]);
        $decodedData = base64_decode($encodedData);

        // You can use the name given, or create a random name.
        // We will create a random name!
        $randomName = substr_replace(sha1(microtime(true)), '', 12) . "_$fileName" . '.' . $mime;

        // $randomName = $mime;

        $loguser = UserUtil::getVar('uid');
        $loguser = !empty($loguser) ? $loguser : 0;

        // make directories if not exist.
        $this->createfolder($args = array(
            'ownerName' => $ownerName,
            'itemName' => 'minisiteimages'
        ));

        if (file_put_contents($uploaddir . $randomName, $decodedData)) {

            if (file_exists('zselexdata/' . $ownerName . '/minisiteimages/' . $existingImage)) {
                unlink('zselexdata/' . $ownerName . '/minisiteimages/' . $existingImage);
            }
            if (file_exists('zselexdata/' . $ownerName . '/minisiteimages/fullsize/' . $existingImage)) {
                unlink('zselexdata/' . $ownerName . '/minisiteimages/fullsize/' . $existingImage);
            }

            if (file_exists('zselexdata/' . $ownerName . '/minisiteimages/medium/' . $existingImage)) {
                unlink('zselexdata/' . $ownerName . '/minisiteimages/medium/' . $existingImage);
            }

            if (file_exists('zselexdata/' . $ownerName . '/minisiteimages/thumb/' . $existingImage)) {
                unlink('zselexdata/' . $ownerName . '/minisiteimages/thumb/' . $existingImage);
            }

            // echo $randomName . ":uploaded successfully";
            $this->resizeImages($destination, $randomName);

            $where = " shop_id='" . $shop_id . "' AND defaultImg='1'";
            $getCountArgs = array(
                'table' => 'zselex_files',
                'where' => $where,
                'Id' => 'file_id'
            );

            $dfltImgcount_count = ModUtil::apiFunc('ZSELEX', 'admin', 'countElements', $getCountArgs);
            // echo $dfltImgcount_count; exit;

            if ($dfltImgcount_count < 1) {
                $addDefault = 1;
            } else {
                $addDefault = 0;
            }
            $obj = array(
                'name' => $randomName
            );

            $pntables = pnDBGetTables();
            $column = $pntables ['zselex_files_column'];
            $where = "WHERE $column[file_id]=$item_id";
            DBUTil::updateObject($obj, 'zselex_files', $where);

            $output = $randomName . ":uploadedsuccessfully";
            ZSELEX_Util::ajaxOutput($output);
            // return true;
        } else {
            // Show an error message should something go wrong.
            // echo "Something went wrong. Check that the file isn't corrupted";
            $output = "Something went wrong. Check that the file isn't corrupted";
            ZSELEX_Util::ajaxOutput($output);
        }
    }

    public function createfolder($args) {
        $ownerName = $args ['ownerName'];
        $itemName = $args ['itemName'];
        // make directories if not exist.
        if (!is_dir('zselexdata/' . $ownerName)) {
            mkdir('zselexdata/' . $ownerName, 0775);
            chmod('zselexdata/' . $ownerName, 0775);
        }

        if (!is_dir('zselexdata/' . $ownerName . '/' . $itemName)) {
            mkdir('zselexdata/' . $ownerName . '/' . $itemName, 0775);
            chmod('zselexdata/' . $ownerName . '/' . $itemName, 0775);
        }
        if (!is_dir('zselexdata/' . $ownerName . '/' . $itemName . '/fullsize')) {
            mkdir('zselexdata/' . $ownerName . '/' . $itemName . '/fullsize', 0775);
            chmod('zselexdata/' . $ownerName . '/' . $itemName . '/fullsize', 0775);
        }
        if (!is_dir('zselexdata/' . $ownerName . '/' . $itemName . '/medium')) {
            mkdir('zselexdata/' . $ownerName . '/' . $itemName . '/medium', 0775);
            chmod('zselexdata/' . $ownerName . '/' . $itemName . '/medium', 0775);
        }
        if (!is_dir('zselexdata/' . $ownerName . '/' . $itemName . '/thumb')) {
            mkdir('zselexdata/' . $ownerName . '/' . $itemName . '/thumb', 0775);
            chmod('zselexdata/' . $ownerName . '/' . $itemName . '/thumb', 0775);
        }

        return true;
    }

    public function resizeImages($destination, $randomName) {
        $modvariable = $this->getVars();

        $fullWidth = !empty($modvariable ['fullimagewidth']) ? $modvariable ['fullimagewidth'] : 1024;
        $fullHeight = !empty($modvariable ['fullimageheight']) ? $modvariable ['fullimageheight'] : 768;

        $medWidth = !empty($modvariable ['medimagewidth']) ? $modvariable ['medimagewidth'] : 800;
        $medHeight = !empty($modvariable ['medimageheight']) ? $modvariable ['medimageheight'] : 500;

        $thumbWidth = !empty($modvariable ['thumbimagewidth']) ? $modvariable ['thumbimagewidth'] : 298;
        $thumbHeight = !empty($modvariable ['thumbimageheight']) ? $modvariable ['thumbimageheight'] : 133;

        $loguser = UserUtil::getVar('uid');
        $loguser = !empty($loguser) ? $loguser : 0;
        if ($height > $fullHeight) { // FULL HEIGHT
            $finalHeight = $fullHeight;
        } else {
            $finalHeight = $height;
        }

        if ($width > $fullWidth) { // FULL WIDTH
            $finalFullWidth = $fullWidth;
        } else {
            $finalFullWidth = $width;
        }

        if ($height > $medHeight) { // MEDIUM HEIGHT
            $finalMedHeight = $medHeight;
        } else {
            $finalMedHeight = $height;
        }

        if ($width > $medWidth) { // MEDIUM WIDTH
            $finalMedWidth = $medWidth;
        } else {
            $finalMedWidth = $width;
        }

        if ($height > $thumbHeight) { // THUMB HEIGHT
            $finalThumbHeight = $thumbHeight;
        } else {
            $finalThumbHeight = $height;
        }

        if ($width > $thumbWidth) { // THUMB WIDTH
            $finalThumbWeight = $thumbWidth;
        } else {
            $finalThumbWeight = $width;
        }

        // create full size
        $imagine = new Imagine\Gd\Imagine ();
        $size = new Imagine\Image\Box($fullWidth, $fullHeight);
        $mode = Imagine\Image\ImageInterface::THUMBNAIL_INSET;
        $imagine->open($destination . '/' . $randomName)->thumbnail($size, $mode)->save($destination . '/fullsize/' . $randomName);

        // create medium size
        $imagine = new Imagine\Gd\Imagine ();
        $size = new Imagine\Image\Box($medWidth, $medHeight);
        $mode = Imagine\Image\ImageInterface::THUMBNAIL_INSET;
        $imagine->open($destination . '/' . $randomName)->thumbnail($size, $mode)->save($destination . '/medium/' . $randomName);

        // create thumbnail
        $imagine = new Imagine\Gd\Imagine ();
        $size = new Imagine\Image\Box($thumbWidth, $thumbHeight);
        $mode = Imagine\Image\ImageInterface::THUMBNAIL_INSET;
        $imagine->open($destination . '/' . $randomName)->thumbnail($size, $mode)->save($destination . '/thumb/' . $randomName);
        return true;
    }

    public function servicePermission($args) {
        return $perm = ModUtil::apiFunc('ZSELEX', 'admin', 'servicePermission', $args = $args);
    }

    public function createPdfThumb($fileName, $destination) {
        // return true;
        // ini_set('display_errors', '1');
        // error_reporting(E_ALL);
        // try {
        // $destination = 'zselexdata/shoppdf/';
        $pdfDirectory = $destination;
        // $thumbDirectory = "zselexdata/shoppdf/thumb/";
        $thumbDirectory = $destination . 'thumb/';
        // print_r($file); exit;
        // echo $file['newName']; exit;
        $name = $fileName;
        // exit;
        // Check file extension

        $allowedExtensions = array(
            'pdf'
        );
        $ex = end(explode(".", $name));
        if (!in_array($ex, $allowedExtensions)) {
            // return LogUtil::registerError($this->__f('Error! Invalid file type: %1$s', $ex));
        }
        // Check file size
        if ($size >= 16000000) {
            // return LogUtil::registerError($this->__('Error! Your file is too big. The limit is 14 MB.'));
        }
        // $newNme = $file['newName'];

        $newNme = $name;

        // echo $newNme; exit;

        $thumb = basename($newNme, ".pdf");
        // $thumb = preg_replace("/[^A-Za-z0-9_-]/", "", $thumb) . ".pdf";
        $thumb = preg_replace("/[^A-Za-z0-9_-]/", "", $thumb);
        // echo $thumb; exit;
        // $code = self::doUploadFile($file, $destination);
        // the path to the PDF file
        $pdfWithPath = $pdfDirectory . $newNme;
        // echo $pdfWithPath; exit;
        // add the desired extension to the thumbnail
        // $time = time();
        $thumb = $thumb . ".jpg";

        // echo $thumb; exit;
        // echo $thumbDirectory.$thumb; exit;

        $finalPath = $thumbDirectory . $thumb;
        // exec("convert \"{$pdfWithPath}[0]\" -colorspace RGB -geometry 120 $finalPath");
        // if ($_SERVER['SERVER_NAME'] == 'localhost') { // only for localhost
        exec("convert -define jpeg:size=60x60 \"{$pdfWithPath}[0]\" -colorspace RGB -thumbnail 100x150 -gravity center -crop 100x150+0+0 +repage $finalPath");
        return true;
        // }
        // KIMENEMARK BEGIN
        $pdfpage = 1;
        // $basepath = $_SERVER['DOCUMENT_ROOT'] . '/' . $destination;
        $basepath = $destination;
        // echo $basepath; exit;

        $name_purify = basename($newNme, ".pdf");
        $name_purified = preg_replace("/[^A-Za-z0-9_-]/", "", $name_purify);

        $pdf_name = $basepath . $newNme;
        // $jpgname = $basepath . 'thumb/' . basename($newNme, '.pdf') . '.jpg';
        // $gsjpgname = $basepath . 'tmp/' . basename($newNme, '.pdf') . '.jpg';

        $jpgname = $basepath . 'thumb/' . $name_purified . '.jpg';
        $gsjpgname = $basepath . 'tmp/' . $name_purified . '.jpg';

        $gscommand = '/usr/bin/gs -sDEVICE=jpeg -sCompression=lzw -r300x300  -dNOPAUSE -dFirstPage=' . $pdfpage . ' -dLastPage=' . $pdfpage . ' -sOutputFile="' . $gsjpgname . '" ' . $pdf_name;
        $command = '/usr/bin/convert -define jpeg:size=60x60 ' . $gsjpgname . ' -colorspace RGB -thumbnail 100x150 -gravity center -crop 100x150+0+0 ' . $jpgname;
        exec($gscommand);
        exec($command);
        unlink($gsjpgname);
        // echo "$basePath\n\n";
        // echo "$pdfDirectory\n\n";
        // echo "$gsjpgname\n\n";
        // echo "$gscommand\n\n";
        // echo "$command\n\n";
        // exit;
        exec($gscommand);
        exec($command);
        unlink($gsjpgname);
        // KIMENEMARK END
        return true;
        // } catch (Exception $e) {
        // echo 'Caught exception: ', $e->getMessage(), "\n";
        // }
    }

    public function uploadpdfim() {

        // echo "hellooooooooooo"; exit;
        // $enc = json_encode($_FILES);
        // $enc = 'h';
        // ZSELEX_Util::ajaxOutput(json_encode(array('pst' => $enc))); exit;
        $exceed = 0;
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $uploadPath = $_REQUEST ['ax-file-path'];
        $fileName = $_REQUEST ['ax-file-name'];
        $originalName = $_REQUEST ['ax-file-name'];
        $currByte = $_REQUEST ['ax-start-byte'];
        $maxFileSize = $_REQUEST ['ax-maxFileSize'];
        $html5fsize = $_REQUEST ['ax-fileSize'];
        $isLast = $_REQUEST ['isLast'];

        // if set generates thumbs only on images type files
        $thumbHeight = $_REQUEST ['ax-thumbHeight'];
        $thumbWidth = $_REQUEST ['ax-thumbWidth'];
        $thumbPostfix = $_REQUEST ['ax-thumbPostfix'];
        $thumbPath = $_REQUEST ['ax-thumbPath'];
        // echo $thumbPath; exit;
        $thumbFormat = $_REQUEST ['ax-thumbFormat'];
        $user_id = UserUtil::getVar('uid');

        /*
         * $servicecheck = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow', $args =
         * array('table' => 'zselex_serviceshop', 'where' => array("shop_id=$shop_id", "type='pdfupload'")));
         * $servicecount = $servicecheck['quantity'] - $servicecheck['availed'];
         *
         * if ($servicecount < 1) {
         * ZSELEX_Util::ajaxOutput(json_encode(array(
         * 'servicecount' => '0',
         * 'size' => 0,
         * 'status' => 'error',
         * 'info' => 'Limit Exceeded',
         * 'msg' => $servicePermission['message']
         * )));
         * //return;
         * }
         */

        $serviceargs = array(
            'shop_id' => $shop_id,
            'user_id' => $user_id,
            'type' => 'pdfupload'
        );
        $servicePermission = $this->servicePermission($serviceargs);

        // $servicePermission = 0;
        if ($servicePermission ['perm'] < 1) {
            ZSELEX_Util::ajaxOutput(json_encode(array(
                'servicecount' => '0',
                'size' => 0,
                'status' => 'error',
                'info' => 'Not Allowed',
                'msg' => $servicePermission ['message']
            )));
            // return;
        }

        $size = $html5fsize;
        $diskquota = ModUtil::apiFunc('ZSELEX', 'admin', 'checkDiskquota', $args = array(
                    'shop_id' => $shop_id
        ));
        $allsize = $diskquota ['sizeused'] + $size;
        if ($diskquota ['count'] < 1) {
            ZSELEX_Util::ajaxOutput(json_encode(array(
                'diskquota' => '-1',
                'size' => 0,
                'status' => 'error',
                'info' => 'Diskquota Exceeded'
            )));
            // return;
        } else if ($diskquota ['limitover'] < 1) {
            ZSELEX_Util::ajaxOutput(json_encode(array(
                'diskquota' => '-1',
                'size' => 0,
                'status' => 'error',
                'info' => 'Diskquota Exceeded'
            )));
            // return;
        } else if ($allsize >= $diskquota ['sizelimit']) { // if the newly uploaded file size is more than the left diskquota size.
            ZSELEX_Util::ajaxOutput(json_encode(array(
                'diskquota' => '-1',
                'size' => 0,
                'status' => 'error',
                'info' => 'Diskquota Exceeded'
            )));
            // return;
        }

        $basename = basename($fileName, ".pdf");
        // $image_name = preg_replace("/[^A-Za-z0-9_-]/", "", $basename) . "-" . time();
        $image_name = preg_replace("/[^A-Za-z0-9_-]/", "", $basename);
        // $fileName = preg_replace("/[^A-Za-z0-9_-]/", "", $basename) . "-" . time() . ".pdf";
        $allowExt = (empty($_REQUEST ['ax-allow-ext'])) ? array() : explode('|', $_REQUEST ['ax-allow-ext']);
        $uploadPath .= (!in_array(substr($uploadPath, - 1), array(
                    '\\',
                    '/'
                ))) ? DIRECTORY_SEPARATOR : ''; // normalize path

        if (!file_exists($uploadPath) && !empty($uploadPath)) {
            mkdir($uploadPath, 0775, true);
            chmod($uploadPath, 0775);
        }

        if (!file_exists($thumbPath) && !empty($thumbPath)) {
            // mkdir($thumbPath, 0775, true);
        }

        $pdfupload = $uploadPath . "pdfupload/";
        if (!file_exists($pdfupload) && !empty($pdfupload)) {
            mkdir($pdfupload, 0775, true);
            chmod($pdfupload, 0775);
        }

        $thumb = $pdfupload . "thumb/";
        if (!file_exists($thumb) && !empty($thumb)) {
            mkdir($thumb, 0775, true);
            chmod($thumb, 0775);
        }

        $tempfolder = $pdfupload . "tmp/";
        if (!file_exists($tempfolder) && !empty($tempfolder)) {
            mkdir($tempfolder, 0775, true);
            chmod($tempfolder, 0775);
        }

        if (isset($_FILES ['ax-files'])) {
            // exit;
            // for eahc theorically runs only 1 time, since i upload i file per time
            foreach ($_FILES ['ax-files'] ['error'] as $key => $error) {
                if ($error == UPLOAD_ERR_OK) {
                    $newName = !empty($fileName) ? $fileName : $_FILES ['ax-files'] ['name'] [$key];
                    $fullPath = $this->checkFilename($pdfupload, $maxFileSize, $allowExt, $newName, $_FILES ['ax-files'] ['size'] [$key]);

                    if ($fullPath) {
                        move_uploaded_file($_FILES ['ax-files'] ['tmp_name'] [$key], $fullPath);
                        if (!empty($thumbWidth) || !empty($thumbHeight))
                            $this->createThumbGD($pdfupload, $thumbPath, $thumbPostfix, $thumbWidth, $thumbHeight, $thumbFormat);
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
            $fullPath = ($currByte != 0) ? $pdfupload . $fileName : $this->checkFilename($pdfupload, $maxFileSize, $allowExt, $fileName, $html5fsize);

            // echo "fullpath :" . $fullPath; exit;
            // ZSELEX_Util::ajaxOutput(json_encode(array('name' => basename($fullPath), 'size' => $currByte, 'status' => 'not done!', 'info' => 'File/chunk uploaded')));

            if ($fullPath) {

                $explod = explode("/", $fullPath);
                $nums = (count($explod) - 1);
                $new_filename = $explod [$nums];

                $basename = basename($new_filename, ".pdf");
                $image_name = preg_replace("/[^A-Za-z0-9_-]/", "", $basename);

                $flag = ($currByte == 0) ? 0 : FILE_APPEND;
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
                if ($isLast == 'true') {
                    if ($this->createPdfThumb($new_filename, $pdfupload)) {

                        /*
                         * $pdfExist = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $args_c = array(
                         * 'table' => 'zselex_shop_pdf',
                         * 'where' => "pdf_name='" . $new_filename . "'"
                         * ));
                         */
                        // if ($pdfExist < 1) {
                        $item = array(
                            'pdf_name' => $new_filename,
                            'pdf_image' => $image_name,
                            'shop_id' => $shop_id,
                            'user_id' => $user_id,
                            'pdf_description' => '',
                            'keywords' => ''
                        );

                        $args = array(
                            'table' => 'zselex_shop_pdf',
                            'element' => $item,
                            'Id' => 'pdf_id'
                        );
                        $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $args);
                        if ($result) {
                            $serviceupdatearg = array(
                                'user_id' => $user_id,
                                'type' => 'pdfupload',
                                'shop_id' => $shop_id
                            );
                            $serviceavailed = ModUtil::apiFunc('ZSELEX', 'admin', 'updateServiceUsed', $serviceupdatearg);
                        }

                        // }
                    }
                }

                // echo "fullpath :" . $fullPath; exit;
                // ZSELEX_Util::ajaxOutput(json_encode(array('name' => basename($fullPath), 'size' => $currByte, 'status' => 'uploaded', 'servicecount' => $servicecount, 'msg' => ($servicecount < 1) ? $this->__("Your service limit is over for this service") : '', 'info' => 'File/chunk uploaded')));
                ZSELEX_Util::ajaxOutput(json_encode(array(
                    'name' => $new_filename,
                    'size' => $currByte,
                    'status' => 'uploaded',
                    'servicecount' => $exceed,
                    'msg' => ($servicecount < 1) ? $this->__("Your service limit is over for this service") : '',
                    'info' => 'File/chunk uploaded'
                )));
                // ZSELEX_Util::ajaxOutput($output);
            }
        }
    }

    function checkFilename($uploadPath, $maxFileSize, $allowExt, $fileName, $size, $newName = '') {
        // echo $allowExt; exit;
        // global $allowExt, $uploadPath, $maxFileSize;
        // ------------------max file size check from js
        $maxsize_regex = preg_match("/^(?'size'[\\d]+)(?'rang'[a-z]{0,1})$/i", $maxFileSize, $match);
        $maxSize = 4 * 1024 * 1024; // default 4 M
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
            ZSELEX_Util::ajaxOutput(json_encode(array(
                'name' => $fileName,
                'size' => $size,
                'status' => 'error',
                'info' => 'File size not allowed.'
            )));
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
        $badWinChars = array_merge(array_map('chr', range(0, 31)), array(
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
        $fileExt = $fileInfo ['extension'];
        $fileBase = $fileInfo ['filename'];

        // check if legal windows file name
        if (in_array($fileName, $windowsReserved)) {
            ZSELEX_Util::ajaxOutput(json_encode(array(
                'name' => $fileName,
                'size' => 0,
                'status' => 'error',
                'info' => 'File name not allowed. Windows reserverd.'
            )));
            return false;
        }

        // check if is allowed extension
        if (!in_array($fileExt, $allowExt) && count($allowExt)) {
            ZSELEX_Util::ajaxOutput(json_encode(array(
                'name' => $fileName,
                'size' => 0,
                'status' => 'error',
                'info' => "Extension [$fileExt] not allowed."
            )));
            return false;
        }

        $fullPath = $uploadPath . $fileName;
        // echo $fullPath; exit;
        $c = 0;
        while (file_exists($fullPath)) {
            $c ++;
            $fileName = $fileBase . "$c." . $fileExt;
            $fullPath = $uploadPath . $fileName;
        }
        // echo $fullPath; die;
        return $fullPath;

        // return array('filename' => $fileName, 'fullpath' => $fullPath);
    }

    function checkFilename2($uploadPath, $maxFileSize, $allowExt, $fileName, $size, $newName = '') {
        // echo $allowExt; exit;
        // global $allowExt, $uploadPath, $maxFileSize;
        // ------------------max file size check from js
        $maxsize_regex = preg_match("/^(?'size'[\\d]+)(?'rang'[a-z]{0,1})$/i", $maxFileSize, $match);
        $maxSize = 4 * 1024 * 1024; // default 4 M
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
            ZSELEX_Util::ajaxOutput(json_encode(array(
                'name' => $fileName,
                'size' => $size,
                'status' => 'error',
                'info' => 'File size not allowed.'
            )));
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
        $badWinChars = array_merge(array_map('chr', range(0, 31)), array(
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
        $fileExt = $fileInfo ['extension'];
        $fileBase = $fileInfo ['filename'];

        // check if legal windows file name
        if (in_array($fileName, $windowsReserved)) {
            ZSELEX_Util::ajaxOutput(json_encode(array(
                'name' => $fileName,
                'size' => 0,
                'status' => 'error',
                'info' => 'File name not allowed. Windows reserverd.'
            )));
            return false;
        }

        // check if is allowed extension
        if (!in_array($fileExt, $allowExt) && count($allowExt)) {
            ZSELEX_Util::ajaxOutput(json_encode(array(
                'name' => $fileName,
                'size' => 0,
                'status' => 'error',
                'info' => "Extension [$fileExt] not allowed."
            )));
            return false;
        }

        $fullPath = $uploadPath . $fileName;

        $infullsize = $uploadPath . 'fullsize/' . $fileName;
        // echo $fullPath; exit;
        $c = 0;
        while (file_exists($infullsize)) {
            $c ++;
            $fileName = $fileBase . "$c." . $fileExt;
            $fullPath = $uploadPath . $fileName;
        }
        // echo $fullPath; die;
        return $fullPath;

        // return array('filename' => $fileName, 'fullpath' => $fullPath);
    }

    // for image magick
    function createThumbIM($filepath, $thumbPath, $postfix, $maxwidth, $maxheight, $format) {
        $file_name = pathinfo($filepath);
        $thumb_name = $file_name ['filename'] . $postfix . '.' . $format;

        if (empty($thumbPath)) {
            $thumbPath = $file_name ['dirname'];
        }
        $thumbPath .= (!in_array(substr($thumbPath, - 1), array(
                    '\\',
                    '/'
                ))) ? DIRECTORY_SEPARATOR : ''; // normalize path

        $image = new Imagick($filepath);
        $image->thumbnailImage($maxwidth, $maxheight);
        $images->writeImages($thumbPath . $thumb_name);
    }

    // with gd library
    function createThumbGD($filepath, $thumbPath, $postfix, $maxwidth, $maxheight, $format = 'jpg', $quality = 75) {
        if ($maxwidth <= 0 && $maxheight <= 0) {
            return 'No valid width and height given';
        }

        $gd_formats = array(
            'jpg',
            'jpeg',
            'png',
            'gif'
        ); // web formats
        $file_name = pathinfo($filepath);
        if (empty($format))
            $format = $file_name ['extension'];

        if (!in_array(strtolower($file_name ['extension']), $gd_formats)) {
            return false;
        }

        $thumb_name = $file_name ['filename'] . $postfix . '.' . $format;

        if (empty($thumbPath)) {
            $thumbPath = $file_name ['dirname'];
        }
        $thumbPath .= (!in_array(substr($thumbPath, - 1), array(
                    '\\',
                    '/'
                ))) ? DIRECTORY_SEPARATOR : ''; // normalize path
        // Get new dimensions
        list ( $width_orig, $height_orig ) = getimagesize($filepath);
        if ($width_orig > 0 && $height_orig > 0) {
            $ratioX = $maxwidth / $width_orig;
            $ratioY = $maxheight / $height_orig;
            $ratio = min($ratioX, $ratioY);
            $ratio = ($ratio == 0) ? max($ratioX, $ratioY) : $ratio;
            $newW = $width_orig * $ratio;
            $newH = $height_orig * $ratio;

            // Resample
            $thumb = imagecreatetruecolor($newW, $newH);
            $image = imagecreatefromstring(file_get_contents($filepath));

            imagecopyresampled($thumb, $image, 0, 0, 0, 0, $newW, $newH, $width_orig, $height_orig);

            // Output
            switch (strtolower($format)) {
                case 'png' :
                    imagepng($thumb, $thumbPath . $thumb_name, 9);
                    break;

                case 'gif' :
                    imagegif($thumb, $thumbPath . $thumb_name);
                    break;

                default :
                    imagejpeg($thumb, $thumbPath . $thumb_name, $quality);
                    ;
                    break;
            }
            imagedestroy($image);
            imagedestroy($thumb);
        } else {
            return false;
        }
    }

    function check1() {
        $output = "testingggg";
        ZSELEX_Util::ajaxOutput($output);
    }

}

?>
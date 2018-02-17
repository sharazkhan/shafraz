<?php

class ZMAP_Controller_Ajax extends Zikula_Controller_AbstractAjax {

    public function getRoadMap() {


        //  $extraArray = array();
        //  $testArray[] = array('address' => 'cochin,india', 'address2' => 'calicut,india');

        $extraArray = array();
        $_SESSION['extra'] = '';

        //$extraArray = $_SESSION['extra'];
        //$extraArray = $testArray;

        $dom = new DOMDocument("1.0");
        $node = $dom->createElement("markers");
        $parnode = $dom->appendChild($node);
        $where = '';
        $sValuesFinal = array();

        $projectId = FormUtil::getPassedValue("projectId");
        $userId = UserUtil::getVar('uid');

        $groupsql = "SELECT gid FROM group_membership WHERE uid=$userId AND gid!=1";
        $gquery = DBUtil::executeSQL($groupsql);
        $gresult = $gquery->fetch();

        $gid = $gresult[gid];
        if (!empty($projectId)) {

            $where = " AND pid='" . $projectId . "' AND cid=$gid";
        }
        $output = '';

        if (!empty($projectId)) {
            $sql = "SELECT * FROM zmap_roadmap WHERE rid!=''" . " " . $where;

            $results = DBUtil::executeSQL($sql);
            $sValue = $results->fetchAll();
            foreach ($sValue as $key => $value) {
                $sValues[$key] = $value;
                $sValues[$key]['color'] = '#8685F7';
                // array_push($result , array('color'=>'#8685F7'));
            }
        }

        if (!empty($sValues)) {
            $sValuesFinal = array_merge($sValuesFinal, $sValues);
        }

        if (!empty($_SESSION['currentmap'])) {
            // $sValues = array_merge($sValues, $_SESSION['currentmap']);
            $sValuesFinal = array_merge($sValuesFinal, $_SESSION['currentmap']);
        }

        // $_SESSION['finalSession'] = $sValuesFinal;
        //$_SESSION['currentmap'] = $sValues;


        foreach ($sValuesFinal as $val) {
            $array[] = array(
                'lat' => $val['start_lattitude'],
                'lng' => $val['start_longitude'],
                'lat2' => $val['end_lattitude'],
                'lng2' => $val['end_longitude'],
                'address' => $val['start'],
                'address2' => $val['end'],
                'color' => $val['color'],
                'projectId' => $projectId,
                'type' => 'restaurant');
        }


        // echo "<pre>"; print_r($sValues);   echo "</pre>"; exit;

        $count = count($sValues);


        header("Content-type: text/xml");
        /*
          foreach ($array as $row) {
          // ADD TO XML DOCUMENT NODE
          $node = $dom->createElement("marker");
          $newnode = $parnode->appendChild($node);
          $newnode->setAttribute("name", $row['name']);
          $newnode->setAttribute("address", $row['address']);
          $newnode->setAttribute("address2", $row['address2']);
          $newnode->setAttribute("lat", $row['lat']);
          $newnode->setAttribute("lng", $row['lng']);
          $newnode->setAttribute("lat2", $row['lat2']);
          $newnode->setAttribute("lng2", $row['lng2']);
          $newnode->setAttribute("type", $row['type']);
          $newnode->setAttribute("projectId", $row['projectId']);
          // $newnode->setAttribute("sql", $test);
          }
         * 
         */


        //  foreach ($sValuesFinal as $row) {
        //foreach ($_SESSION['finalSession']  as $key => $row) {
        foreach ($sValuesFinal as $key => $row) {

            /*

              $_SESSION['waypoints'][$key] = $row['waypoints'];


              $_SESSION['startendpoints']['startLat'][$key] = $row['start_lattitude'];
              $_SESSION['startendpoints']['startLat']['rid'][$row[rid]] = $row['start_lattitude'];

              $_SESSION['startendpoints']['startLng'][$key] = $row['start_longitude'];
              $_SESSION['startendpoints']['startLng']['rid'][$row[rid]] = $row['start_longitude'];


              $_SESSION['startendpoints']['endLat'][$key] = $row['end_lattitude'];
              $_SESSION['startendpoints']['endLat']['rid'][$row[rid]] = $row['end_lattitude'];


              $_SESSION['startendpoints']['endLng'][$key] = $row['end_longitude'];
              $_SESSION['startendpoints']['endLng']['rid'][$row[rid]] = $row['end_longitude'];

             */


            // $wp1 = $_SESSION['waypoints'][$key];

            $wp1 = $row['waypoints'];

            $wp2 = explode(',(', $wp1);

            //echo "<pre>"; print_r($test1);   echo "</pre>";

            $waypoints = '';

            foreach ($wp2 as $val) {
                // echo $val . '<br>';
                if ($val != '') {
                    $order = array("(", ")");
                    $lnglt = str_replace($order, " ", $val);
                    $waypoints .= "{location:new google.maps.LatLng($lnglt),stopover:false},";
                } else {

                    $waypoints = '12';
                }

                // echo $lnglt . '<br>';
            }
            $waypoints = substr($waypoints, 0, -1);
            //echo $string;
            // ADD TO XML DOCUMENT NODE  
            $node = $dom->createElement("marker");
            $newnode = $parnode->appendChild($node);
            $newnode->setAttribute("rid", $row['rid']);
            $newnode->setAttribute("name", $row['name']);
            $newnode->setAttribute("address", $row['address']);
            $newnode->setAttribute("address2", $row['address2']);
            $newnode->setAttribute("lat", $row['start_lattitude']);
            $newnode->setAttribute("lng", $row['start_longitude']);
            $newnode->setAttribute("lat2", $row['end_lattitude']);
            $newnode->setAttribute("lng2", $row['end_longitude']);
            $newnode->setAttribute("type", $row['type']);
            $newnode->setAttribute("color", $row['color']);
            $newnode->setAttribute("projectId", $row['projectId']);
            $newnode->setAttribute("waypoints", $waypoints);
            // $newnode->setAttribute("sql", $test);
        }

        $output = '';
        $output = $dom->saveXML();

        AjaxUtil::ajaxOutput($output);
    }

    public function saveStartEndPoints() {

        $output = '';

        // $output['test'] = 'testttt';
        // AjaxUtil::output($output);  exit;

        $rid = $_REQUEST['rid'];
        $srtLat = $_REQUEST['strtLat'];
        $srtLng = $_REQUEST['strtLng'];
        $endLat = $_REQUEST['endLat'];
        $endLng = $_REQUEST['endLng'];
        $key = $_REQUEST['key'];


        $countsql = "SELECT COUNT(*) as COUNT FROM 
                          zmap_roadmap_temp 
                          WHERE
                          rid='" . $rid . "'";

        // echo $countsql . '<br>';
        /*
          $countquery = DBUtil::executeSQL($countsql);
          $resultcount = $countquery->fetch();
          $exist = $resultcount['COUNT'];

          if ($exist < 1) {

          $itemroads = array(
          'rid' => $rid,
          'start_lattitude' => $srtLat,
          'start_longitude' => $srtLng,
          'end_lattitude' => $endLat,
          'end_longitude' => $endLng,
          );
          //echo "<pre>"; print_r($itemroads);   echo "</pre>"; exit;
          $saveprojectroads = ModUtil::apiFunc('ZMAP', 'user', 'createElement', array('table' => 'zmap_roadmap_temp', 'element' => $itemroads, 'Id' => 'id'));
          } else {


          $itemroadss = array(
          'rid' => $rid,
          'start_lattitude' => $srtLat,
          'start_longitude' => $srtLng,
          'end_lattitude' => $endLat,
          'end_longitude' => $endLng,
          );

          $updateargss = array(
          'table' => 'zmap_roadmap_temp',
          'IdValue' => $rid,
          'IdName' => 'rid',
          'element' => $itemroadss
          );

          $updateroadss = ModUtil::apiFunc('ZMAP', 'user', 'updateElement', $updateargss);
          } */




        $sql = "UPDATE zmap_roadmap
                SET  
                start_lattitude='" . $srtLat . "' ,
                start_longitude='" . $srtLng . "' , 
                end_lattitude='" . $endLat . "' ,
                end_longitude='" . $endLng . "' 
                WHERE rid='" . $rid . "'";

        $output['query'] = $sql;
        $query = DBUtil::executeSQL($sql);

        $output['values'] = 'rid :' . $rid . "" . "srtLat -" . $srtLat . " " . "srtLng -" . $srtLng . " " . "endLat - " . $endLat . " " . "endLng -" . $endLng;
        //AjaxUtil::output($output);
        // session_register($_SESSION['startendpoints']['startLat'][$key]);
        // session_register($_SESSION['startendpoints']['startLng'][$key]);
        // session_register($_SESSION['startendpoints']['endLat'][$key]);
        // session_register($_SESSION['startendpoints']['endLng'][$key]);
        //  $_SESSION['finalSession'][$key]['startLat'] = $srtLat;
        //  $_SESSION['finalSession'][$key]['startLng'] = $srtLng;
        //  $_SESSION['finalSession'][$key]['endLat'] = $endLat;
        //  $_SESSION['finalSession'][$key]['endLng'] = $endLng;

        $_SESSION['startendpoints']['startLat'][$key] = $srtLat;
        $_SESSION['startendpoints']['startLat']['rid'][$rid] = $srtLat;
        $_SESSION['startendpoints']['startLat']['rid'][$rid] = $srtLat;

        $_SESSION['startendpoints']['startLng'][$key] = $srtLng;
        $_SESSION['startendpoints']['startLng']['rid'][$rid] = $srtLng;
        $_SESSION['startendpoints']['startLng']['rid'][$rid] = $srtLng;


        $_SESSION['startendpoints']['endLat'][$key] = $endLat;
        $_SESSION['startendpoints']['endLat']['rid'][$rid] = $endLat;
        $_SESSION['startendpoints']['endLat']['rid'][$rid] = $endLat;


        $_SESSION['startendpoints']['endLng'][$key] = $endLng;
        $_SESSION['startendpoints']['endLng']['rid'][$rid] = $endLng;
        $_SESSION['startendpoints']['endLng']['rid'][$rid] = $endLng;



        AjaxUtil::output($output);
    }

    function saveWaypoints() {

        // $waypoints = array();
        $output['test'] = '';
        $rid = $_REQUEST['rid'];
        $keyid = $_REQUEST['key'];
        $waypoint = $_REQUEST['waypoint'];
        $waypoints[] = $waypoint;

        $sessionrid = "waypoint" . $rid;

        $_SESSION[$sessionrid] = $waypoint;


        $_SESSION['waypoints']['rid'][$rid] = $waypoint;
        $_SESSION['waypoints'][$keyid] = $waypoint;
        // $_SESSION['waypoints']['rid'][$rid] = array();
        //  $_SESSION['waypoints']['rid'][$rid] = $waypoint;
        //  $_SESSION['finalSession'][$keyid]['waypoints'] = $waypoint;
        /*
          $countsql = "SELECT COUNT(*) as COUNT FROM
          zmap_roadmap_temp
          WHERE
          rid='" . $rid . "'";

          // echo $countsql . '<br>';

          $countquery = DBUtil::executeSQL($countsql);
          $resultcount = $countquery->fetch();
          $exist = $resultcount['COUNT'];

          if ($exist < 1) {

          $itemroads = array(
          'rid' => $rid,
          'waypoints' => $waypoint,
          );
          //echo "<pre>"; print_r($itemroads);   echo "</pre>"; exit;
          $saveprojectroads = ModUtil::apiFunc('ZMAP', 'user', 'createElement', array('table' => 'zmap_roadmap_temp', 'element' => $itemroads, 'Id' => 'id'));
          } else {


          $itemroadss = array(
          'rid' => $rid,
          'waypoints' => $waypoint,
          );

          $updateargss = array(
          'table' => 'zmap_roadmap_temp',
          'IdValue' => $rid,
          'IdName' => 'rid',
          'element' => $itemroadss
          );

          $updateroadss = ModUtil::apiFunc('ZMAP', 'user', 'updateElement', $updateargss);
          }

         */



        $sql = "UPDATE zmap_roadmap SET waypoints='" . $waypoint . "' where rid='" . $rid . "'";
        $query = DBUtil::executeSQL($sql);


        $output['test'] = "rid :" . $rid . " - " . $_SESSION['waypoints']['rid'][$rid];
        AjaxUtil::output($output);
    }

    public function getGps() {

        //$ip = "122.166.29.86";
        $ip = $_SERVER['REMOTE_ADDR'];
        $output = '';
        $json = file_get_contents("http://api.easyjquery.com/ips/?ip=" . $ip . "&full=true");
        $json = json_decode($json, true);
        $id = $_REQUEST['id'];
        // echo "<pre>";   print_r($json);  echo "</pre>";

        $city = $json['CityName'];
        $region = $json['RegionName'];
        $country = $json['CountryName'];

        $output['gps'] = $city . ',' . $region . ',' . $country;
        $output['id'] = $id;
        // AjaxUtil::ajaxOutput('checkking');
        AjaxUtil::output($output);
        //echo "<pre>";   print_r($json);  echo "</pre>";
    }

    function getRoad() {

        $output = '';
        $rid = $_REQUEST['rid'];
        $sql = "SELECT * FROM zmap_roadmap WHERE rid=$rid";
        // $output['sql'] = $sql;
        // AjaxUtil::output($output);
        $result = DBUtil::executeSQL($sql);
        $road = $result->fetch();

        //echo "<pre>";   print_r($road);  echo "</pre>";


        $output['rid'] = $road['rid'];
        $output['name'] = $road['name'];
        $output['desc'] = $road['description'];
        $output['start'] = urldecode($road['start']);
        $output['end'] = urldecode($road['end']);
        $output['startdesc'] = urldecode($road['startdescription']);
        $output['enddesc'] = urldecode($road['enddescription']);
        AjaxUtil::output($output);
    }

}

?>

<?php
function smarty_function_maps($args, &$smarty) {



if ($_REQUEST['print'] == 'map') {

$width = "800px";

$height = "1120px";

} else {

$width = "650px";

$height = "600px";

}





// $_SESSION['finalSession'] =  array();
// $_SESSION['waypoints'] = array();
// $_SESSION['startendpoints'] = array();
// $_SESSION['currentmap'] = array();
//echo "<pre>";  print_r($_SESSION['currentmap']);   echo "</pre>";
//unset($_SESSION['currentmap']);  unset($_SESSION['waypoints']);  unset($_SESSION['startendpoints']);
////////////////////////////////////////////////////////////////////////////////////////////////////
// $testArray[] = array('address' => 'cochin,india', 'address2' => 'delhi,india');
//echo "<pre>";  print_r($_SESSION['waypoints']);   echo "</pre>";
//echo "<pre>";  print_r($_SESSION['waypoints']);   echo "</pre>";
//$finalresult = array();

$where = '';



$projectId = FormUtil::getPassedValue("projectId");

$userId = UserUtil::getVar('uid');



$groupsql = "SELECT gid FROM group_membership WHERE uid=$userId AND gid!=1";

// $query = mysql_query($sql);

$gquery = DBUtil::executeSQL($groupsql);

$gresult = $gquery->fetch();



$gid = $gresult[gid];



//echo "Group Id :" . $gid; 







if ($_POST && isset($_POST['centermap']) && $_POST['centermap'] == __('Center Map')) { // center map
// echo "ZMAP!!!!!!!!!!!!!!!!!!!!!!";
//echo "<pre>";  print_r($_POST);    echo "</pre>";

$start = urlencode($_REQUEST['centeraddr']);

$end = urlencode($_REQUEST['centeraddr']);

$startaddress = urlencode($_REQUEST['startdescription']);

$endaddress = urlencode($_REQUEST['enddescription']);

$width = $_REQUEST['width'];

$city = '';

$state = '';

$country = '';



$geocodeStart = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $start . ',+' . $city . ',+' . $state . ',+' . $country . '&sensor=false');

$outputStart = json_decode($geocodeStart); //Store values in variable



$geocodeEnd = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $end . ',+' . $city . ',+' . $state . ',+' . $country . '&sensor=false');

$outputEnd = json_decode($geocodeEnd); //Store values in variable



$latStart = $outputStart->results[0]->geometry->location->lat;

$longStart = $outputStart->results[0]->geometry->location->lng;



$latEnd = $outputEnd->results[0]->geometry->location->lat;

$longEnd = $outputEnd->results[0]->geometry->location->lng;





$_SESSION['currentmap'][] = array(

'rid' => '-3',
 'color' => 'centercolor',
 //'waypoints' => array(),

'name' => $_POST['roadname'],
 'description' => $_POST['roaddescription'],
 'start' => $_POST['start'],
 'startdescription' => $_POST['startdescription'],
 'end' => $_POST['end'],
 'enddescription' => $_POST['enddescription'],
 'start_lattitude' => $latStart,
 'start_longitude' => $longStart,
 'end_lattitude' => $latEnd,
 'end_longitude' => $longEnd,
);

}



if ($_POST && isset($_POST['normal']) && $_POST['normal'] == 'Normal') {



foreach ($_SESSION['currentmap'] as $key => $val) {



if ($val['rid'] == '-3') {



unset($_SESSION['currentmap'][$key]);

}

}

}







$finalresult = array();

$where = '';



$projectId = FormUtil::getPassedValue("projectId");

if (!empty($projectId)) {



$where = " AND pid='" . $projectId . "' AND cid=$gid";

}

if (!empty($projectId)) {

$sql = "SELECT * FROM zmap_roadmap WHERE rid!=''" . " " . $where;

// $query = mysql_query($sql);

$query = DBUtil::executeSQL($sql);

$results = $query->fetchAll();



foreach ($results as $key => $value) {

$result[$key] = $value;

$result[$key]['color'] = '#8685F7';

// $result[$key]['waypoints'] = array();
// array_push($result , array('color'=>'#8685F7'));

}

//echo "<pre>"; print_r($result);  echo "</pre>";exit;

}

// $result = array_merge($testArray, $result);



if (!empty($result)) {

$finalresult = array_merge($result, $finalresult);

}



if (!empty($_SESSION['currentmap'])) {

// $result = array_merge($_SESSION['currentmap'], $result);

$finalresult = array_merge($_SESSION['currentmap'], $finalresult);

}



foreach ($finalresult as $key => $val) {



// $finalresult[$key]['waypoints'] = $_SESSION['waypoints'][$key];

}





$_SESSION['finalSession'] = $finalresult;





// foreach ($_SESSION['finalSession'] as $key => $val) {
//$_SESSION['finalSession'][$key]['waypoints'] = $_SESSION['waypoints'][$key];
// }
//unset($_SESSION['waypoints']);
//$count = sizeof($finalresult);

$count = sizeof($_SESSION['finalSession']);

//echo "<pre>"; print_r($finalresult);  echo "</pre>";
//echo "<pre>"; print_r($finalresult[1]);  echo "</pre>";
//echo "<pre>"; print_r($_SESSION['finalSession']);  echo "</pre>";
// echo "<pre>";  print_r($_SESSION['waypoints']);   echo "</pre>";
// echo "<pre>";    print_r($_SESSION['waypoints'][1]);  echo "</pre>";
//echo "<pre>"; print_r(json_encode($_SESSION['waypoints']));  echo "</pre>";
?>

<style>

    #map_canvas { width:<?php echo $width ?>; height: <?php echo $height ?>; }

</style>

<script src="http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places"></script>









<script type="text/javascript">

    window.onload = function() {
        initialize()
    };

    var data = {};

    //                 var rendererOptions = {

    //                   draggable: true

    //                  

    //                };

    var directionsService = new google.maps.DirectionsService();

    var directionsDisplay;

    var map;

    var markersArray = [];

    var point;

    var service = new google.maps.DirectionsService();

    var directions = new google.maps.DirectionsRenderer();



<?php
for ($i = 0;
$i < $count;
$i++) {

//foreach ($finalresult as $i => $val) {
?>

    var directionsDisplay<?php echo $i ?>;

    var rendererOptions<?php echo $i ?>;

    var color;

    //	var roadcolor = "<?php echo $finalresult[$i]['color'] ?>";



    var roadcolor = "<?php echo $val['color'] ?>";

    //	alert(roadcolor);

    if (roadcolor == '') {

        color = '#8685F7';

    } else {

        color = roadcolor;

    }

    rendererOptions<?php echo $i ?> = {
        draggable: true,
        polylineOptions: {strokeColor: roadcolor}

    };

<?php
}
?>



    function initialize() {



        var projectId = "<?php echo $_REQUEST['projectId'] ?>";

        var burl = "<?php echo pnGetBaseURL() ?>";

        var savemode = document.getElementById('savemode');

        //alert(projectId);

        var zurl = burl + "index.php?module=ZMAP&type=ajax&func=getRoadMap&projectId=" + projectId;

        //alert(zurl);

        var chicago = new google.maps.LatLng(56.26392, 9.501785);

        var mapOptions = {
            zoom: 1,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            center: chicago

        }

        var infoWindow = new google.maps.InfoWindow;



        map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);

        /////////////////////////////////////////////////////////////////////////////////////////////////

        var input = document.getElementById('startgps');

        var input2 = document.getElementById('endgps');

        var input3 = document.getElementById('centeraddr');



        var autocomplete = new google.maps.places.Autocomplete(input);

        var autocomplete2 = new google.maps.places.Autocomplete(input2);

        var autocomplete3 = new google.maps.places.Autocomplete(input3);

        var waypts = [];

        //    var wayptsss = [];



        downloadUrl(zurl, function(data) {



            var xml = data.responseXML;

            var markers = xml.documentElement.getElementsByTagName("marker");

            //alert(markers.length);

            // document.getElementById("hcount").value = markers.length;

            var centerLoc = xml.documentElement.getElementsByTagName("marker");

            var wayptsss;

            for (var i = 0; i < markers.length; i++) {



                //alert(i);

                // alert(markers[i].getAttribute("address"));

                // alert(markers[i].getAttribute("projectId"));

                //alert(markers[i].getAttribute("address2"));

                //alert(markers[i].getAttribute("color"));

                // alert(markers[i].getAttribute("waypoints"));

                //  alert(markers[i].getAttribute("rid"));



                var waypointss = markers[i].getAttribute("waypoints");

                var name = markers[i].getAttribute("name");

                var address = markers[i].getAttribute("address");

                var address2 = markers[i].getAttribute("address2");

                var type = markers[i].getAttribute("type");

                var lat = markers[i].getAttribute("lat");

                var lng = markers[i].getAttribute("lng");

                var lat2 = markers[i].getAttribute("lat2");

                var lng2 = markers[i].getAttribute("lng2");

                //alert(lat2);

                point = new google.maps.LatLng(
                        parseFloat(markers[i].getAttribute("lat")),
                        parseFloat(markers[i].getAttribute("lng")));

            }



<?php
$j = 1;

for ($i = 0;
$i < $count;
$i++) {

//  foreach ($finalresult as $i => $val) {
?>

            // alert("<?php echo $i ?>");



            //var directionsDisplay<?php echo $i ?>;

            // directionsDisplay<?php echo $i ?> = new google.maps.DirectionsRenderer(rendererOptions<?php echo $i ?>);



            var wayptsss;

            var t = markers[<?php echo $i ?>].getAttribute("color");

            //alert(t);



            // waypts = [wayptsss];



            //alert(markers[<?php echo $i ?>].getAttribute("waypoints"));

            var st = "[";

            var et = "]";



            if (markers[<?php echo $i ?>].getAttribute("waypoints") == 1) {

                wayptsss = [];

            } else {

                wayptsss = st + markers[<?php echo $i ?>].getAttribute("waypoints") + et;

                wayptsss = eval(wayptsss);

            }



            //alert(wayptsss);



            if (t == 'yellow') {



                var rendererOptionss = {
                    //draggable: true,

                    polylineOptions: {strokeColor: 'red'}

                }



            } else if (t == 'centercolor') {



                var rendererOptionss = {
                    //  draggable: true,

                    suppressMarkers: true

                            // polylineOptions:{strokeColor:'green'}

                }



            } else {



                var rendererOptionss = {
                    draggable: true

                }

            }



            directionsDisplay<?php echo $i ?> = new google.maps.DirectionsRenderer(rendererOptionss);

            // directionsDisplay<?php echo $i ?>.suppressMarkers = true;



            var latLngsss = new google.maps.LatLng(8.9712826, 76.71057159999998, 8.775461, 76.80789290000007);



            var request = {
                // origin:markers[<?php echo $i ?>].getAttribute("address"),

                // destination:markers[<?php echo $i ?>].getAttribute("address2"),

                origin: new google.maps.LatLng(
                        parseFloat(markers[<?php echo $i ?>].getAttribute("lat")),
                        parseFloat(markers[<?php echo $i ?>].getAttribute("lng"))),
                destination: new google.maps.LatLng(
                        parseFloat(markers[<?php echo $i ?>].getAttribute("lat2")),
                        parseFloat(markers[<?php echo $i ?>].getAttribute("lng2"))),
                waypoints: [],
                waypoints: wayptsss,
                        travelMode: google.maps.TravelMode.WALKING

            };



            directionsService.route(request, function(result, status) {

                ;

                if (status == google.maps.DirectionsStatus.OK) {



                    directionsDisplay<?php echo $i ?>.setDirections(result);

                    ///////////

                    // var bounds = result.routes[0].bounds;

                    // map.fitBounds(bounds);

                    // map.setCenter(bounds.getCenter());

                    ////////////

                    var route = result.routes[0];



                    if (document.getElementById('directions_panel<?php echo $i ?>')) {

                        var summaryPanel<?php echo $i ?> = document.getElementById('directions_panel<?php echo $i ?>');

                        //var dist<?php echo $i ?> = document.getElementById('dis<?php echo $i ?>');

                        summaryPanel<?php echo $i ?>.innerHTML = '';

                        //dist<?php echo $i ?>.innerHTML = '';

                        // For each route, display summary information.



                        for (var i = 0; i < route.legs.length; i++) {

                            // alert('hii');

                            var routeSegment = i + 1;



                            // summaryPanel<?php echo $i ?>.innerHTML += '<b>Route Segment: ' + routeSegment + '</b><br>';

                            summaryPanel<?php echo $i ?>.innerHTML += '<b>' + Zikula.__('Route Segment:') + '<?php echo $j ?></b><br>';

                            //summaryPanel<?php echo $i ?>.innerHTML += '<b>Route Segment:<?php echo $j ?></b><br>';

                            summaryPanel<?php echo $i ?>.innerHTML += route.legs[i].start_address + ' ' + Zikula.__('to') + ' ';

                            //summaryPanel<?php echo $i ?>.innerHTML += route.legs[i].start_address + ' to ';

                            summaryPanel<?php echo $i ?>.innerHTML += route.legs[i].end_address + '<br>';

                            summaryPanel<?php echo $i ?>.innerHTML += route.legs[i].distance.text + '<br><br>';

                            //dist<?php echo $i ?>.innerHTML += route.legs[i].distance.text + '<br><br>';

                            //dist<?php echo $i ?>.value = route.legs[i].distance.text;

                        }

                    }

                }

            });



            directionsDisplay<?php echo $i ?>.setMap(map);

            // map.setZoom(100);



            google.maps.event.addListener(directionsDisplay<?php echo $i ?>, 'directions_changed', function() {

                //alert('hiii');

                //alert("directionsDisplay"+<?php echo $i ?>);

                // alert(t);



                //alert('checked');



                var rid_new = markers[<?php echo $i ?>].getAttribute("rid");

                //alert(rid_new);

                var waypoints = directionsDisplay<?php echo $i ?>.getDirections().routes[0].legs[0].via_waypoints;



                var strtLat = directionsDisplay<?php echo $i ?>.getDirections().routes[0].legs[0].start_location.lat();

                var strtLng = directionsDisplay<?php echo $i ?>.getDirections().routes[0].legs[0].start_location.lng();

                var start_points = strtLat + "," + strtLng;

                //alert(start_points);

                //  var rid_startend = '<?php echo $_SESSION['finalSession'][$i]['rid'] ?>';      

                //  alert(rid_startend);

                var endLat = directionsDisplay<?php echo $i ?>.getDirections().routes[0].legs[0].end_location.lat();

                var endLng = directionsDisplay<?php echo $i ?>.getDirections().routes[0].legs[0].end_location.lng();



                var end_points = endLat + "," + endLng;

                //alert(end_points);

                //  return true;

                //  alert('hiii');

                // if(savemode.checked){

                saveStartEndPoints(strtLat, strtLng, endLat, endLng, rid_new,<?php echo $i ?>);

                // }



                if (waypoints != '') {

                    //alert(waypoints);

                    // var rid_wypnt = '<?php echo $finalresult[$i]['rid'] ?>';

                    // alert(chc);

                    // if(savemode.checked){

                    saveWaypoints(waypoints, rid_new,<?php echo $i ?>);

                    // }

                }



            });



<?php
$j++;

}
?>



        }); // downloadUrl



    }  // iniatialize ends







    function saveStartEndPoints(strtLat, strtLng, endLat, endLng, rid_startend, key) {



        //   alert(rid_startend); 



        // alert(endLat); exit();



        // alert(key);



        // alert("rid :" + rid_startend + " " + "strtlat :" + strtLng); exit();



        var pars = "module=ZMAP&type=ajax&func=saveStartEndPoints&rid=" + rid_startend + "&strtLat=" + strtLat + "&strtLng=" + strtLng + "&endLat=" + endLat + "&endLng=" + endLng + "&key=" + key;

        // alert(pars);

        var myAjax = new Ajax.Request(
                "ajax.php",
                {
                    method: 'get',
                    parameters: pars,
                    onComplete: saveStartEndPointsResponses

                }

        );



    }





    function saveStartEndPointsResponses(req) {



        // alert('ajax works'); exit();

        if (req.status != 200) {

            pnshowajaxerror(req.responseText);

            return;

        }

        // alert('ajax works1'); exit();

        // alert(req.responseTex);

        var json = pndejsonize(req.responseText);

        //   alert(json.test);

        // alert(json.values);

        // alert(json.query);

        // alert(req.responseText.length);   exit();

        //alert(req.responseText); exit();

        // document.getElementById('ridedit').value = json.rid;

    }





    function saveWaypoints(wypnt, rid, key) {



        //alert(rid); exit();



        // alert(key); exit();



        // alert("rid :" + rid + " " + "waypoint :" + wypnt);



        var pars = "module=ZMAP&type=ajax&func=saveWaypoints&rid=" + rid + "&waypoint=" + wypnt + "&key=" + key;

        var myAjax = new Ajax.Request(
                "ajax.php",
                {
                    method: 'get',
                    parameters: pars,
                    onComplete: saveWaypointsResponses

                }

        );

    }





    function saveWaypointsResponses(req) {



        //alert('ajax works'); exit();

        if (req.status != 200) {

            pnshowajaxerror(req.responseText);

            return;

        }



        var json = pndejsonize(req.responseText);

        // alert(json.test);

        // alert(json.gps);

        // alert(req.responseText.length);   exit();

        //alert(req.responseText); exit();

        // document.getElementById('ridedit').value = json.rid;

    }





    function some_method() {



        //  alert('hii');



    }





    function bindInfoWindow(marker, map, infoWindow, html) {



        markersArray.push(marker);

        google.maps.event.addListener(marker, 'click', function() {

            infoWindow.setContent(html);

            infoWindow.open(map, marker);

        });

    }





    function test1() {



        directionsDisplay2.setMap(map);



    }





    function centerDirection(val) {

        //alert(val);

        val;

        // directionsDisplay2.setMap(map);

    }





    function centerDirection1(val) {



        //   directionsDisplay1.setMap(null);

        // alert(val); exit();

        var n = val.split("+");

        var splitroad = n[1];



        //alert(splitroad);

        eval(splitroad);



    }





    function removeMap() {

        var remve = document.getElementById('rmve').value;

        // alert(remve);

        var n = remve.split(".");

        var n1 = n[0];

        var s = n1.charAt(n1.length - 1);

        // alert(s);



        //document.getElementById('directions_panel'+s).innerHTML = '';



        //  document.getElementById('directions_panel'+s).style.display = 'none';



        var remveroad = remve.concat("(null)");



        eval(remveroad);

        // alert(newurl);

    }





    function recal(width, dis) {



        //alert(width);exit();

        var km = document.getElementById('dis' + dis).value;

        // alert(km);

        var n = km.split(" ");

        var splitkm = n[0];

        //alert(n[0]);

        var meter = splitkm * 1000;

        //alert(meter);

        var formname = 'areas' + dis;

        //alert(formname);

        //document.formname.submit();

        document.getElementById(formname).submit();

    }





    function downloadUrl(url, callback) {

        var request = window.ActiveXObject ?
                new ActiveXObject('Microsoft.XMLHTTP') :
                new XMLHttpRequest;



        request.onreadystatechange = function() {

            if (request.readyState == 4) {

                request.onreadystatechange = doNothing;

                callback(request, request.status);

            }

        };



        request.open('GET', url, true);

        request.send(null);

    }





    function doNothing() {

    }



</script>





<div style="width:100%; display: inline-block; height: auto; min-width:1024px">

    <div id="savemsg"></div>

    <div id="map_canvas"></div>







<?php
if ($_REQUEST['print'] != 'map') {

echo "<form action=\"\" method=\"post\">\n

			<input size=\"40\" type=\"text\" id=\"centeraddr\" name=\"centeraddr\" value=\"\">\n

			<input type=\"submit\" name=\"centermap\" value=\"" . __('Center Map') . "\">\n

			<input type=\"submit\" name=\"normal\" value=\"" . __('Normal') . "\">\n

		</form>\n\n";

}
?>



<?php
if ($_REQUEST['print'] != 'map') {

echo "<div align=\"right\" style=\"margin-left: 600px\">\n  

			<input type=\"button\" name=\"normal\" value=\"" . __('Print') . "\" onClick=\"window.open('" . $_SERVER['REQUEST_URI'] . '/print/map' . "', '_blank');\">\n

		</div>\n";

}
?>



<?php
if ($_REQUEST['print'] != 'map') {
?>

    <div style="width:60%">

<?php
for ($s = 0;
$s < $count;
$s++) {

// echo $s;
?>

        <div id="directions_panel<?php echo $s; ?>" style="margin:20px;background-color:#FFEE77;"></div>

    <?php
    }
    ?>



        <!--<div onclick="test1()">test</div> -->

        <input type="hidden" id="hcount">

        <input type="hidden" id="hcounts" value="<?php echo $count; ?>">

    </div>

    <?php
    }
    ?>

</div>









<?php
}


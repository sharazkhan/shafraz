<!DOCTYPE html >
<style>
    #map {
        width: 500px;
        height: 300px;
        border: 8px solid #808080;
        background: white url('{/literal}{$baseurl}{literal}zselexdata/loadingmap.gif') no-repeat 50% 50%;
    }​
</style>
<input type="hidden" id="cLat"  value="{$lat}">
<input type="hidden" id="cLng"  value="{$long}">

<input type="hidden" id="cLatall"  value="{$lat}">
<input type="hidden" id="cLngall"  value="{$long}">
<input type="hidden" id="centerfunc"  value="{$centerfunc}">
<input type="hidden" id="userCountry"  value="{$userCountry}">

<input type="hidden" id="type"  value="">
<input type="hidden" id="name"  value="">

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
    //<![CDATA[

    var center = null;
    var point;
    var map;
    var currentPopup;
    //var map = null;
    var customIcons = {
        restaurant: {
            icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png',
            shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
        },
        bar: {
            icon: 'http://labs.google.com/ridefinder/images/mm_20_red.png',
            shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
        }
      
    };
     
    
        
    function bindInfoWindow(marker, map, infoWindow, html) {
        google.maps.event.addListener(marker, 'click', function() {
            infoWindow.setContent(html);
            infoWindow.open(map, marker);
        });
    }
    
    


    function load() {
        //exit();
        //alert('helloo');
        var hcountry =  document.getElementById('hcountry').value;
        var hregion =  document.getElementById('hregion').value;
        var hcity =  document.getElementById('hcity').value;
        var hshop =  document.getElementById('hshop').value;
        var harea =  document.getElementById('harea').value;
        var hsearch =  document.getElementById('hsearch').value;
        
        var countryname =  document.getElementById('hcountryname').value;
        var regionname =  document.getElementById('hregionname').value;
        var city_name =  document.getElementById('hcity_name').value;
        var areaname =  document.getElementById('hareaname').value;
        //alert(countryname + " " + regionname + " " + city_name + " " +areaname);
   
        var hcategory =  document.getElementById('hcategory').value;
        var hbranch =  document.getElementById('hbranch').value;
       
        var zurl;
        var catId = document.getElementById("hcategory").value;
        var branch_id = document.getElementById("hbranch").value;
        //alert(catId);
        var mapOptions = { center: new google.maps.LatLng(0, 0), mapTypeId: google.maps.MapTypeId.ROADMAP , zoom : 1};
        map =  new google.maps.Map(document.getElementById("map"), mapOptions);
     
        zurl = "{/literal}{$baseurl}{literal}index.php?module=ZSELEX&type=ajax&func=getShopDetailsMap&shop_id=" + hshop + "&country_id=" + hcountry + 
            "&region_id=" + hregion + "&city_id=" + hcity + "&area_id="+harea+ "&hsearch="+ hsearch +"&category_id=" + hcategory + "&branch_id="+hbranch+"&countryname="+countryname+"&regionname="+regionname+"&city_name="+city_name+"&areaname="+areaname;
     
        //alert(zurl);
        // Change this depending on the name of your PHP file
        var bounds = new google.maps.LatLngBounds();
        downloadUrl(zurl , function(data) {
            //alert(data);
            var xml = data.responseXML;
            var markers = xml.documentElement.getElementsByTagName("marker");
            //alert(markers.length);  
            if(markers.length > 0){
                for (var i = 0; i < markers.length; i++) {
                    //alert(markers[i].getAttribute("name"));
                    //alert(markers[i].getAttribute("sql"));
                    //alert(markers[i].getAttribute("test"));
                    var name = markers[i].getAttribute("name");
                    var address = markers[i].getAttribute("address");
                    var type = markers[i].getAttribute("type");
                    var shop_id = markers[i].getAttribute("shop_id");
                    var urltitle = markers[i].getAttribute("urltitle");
                    //alert(address);
                   // alert(urltitle);
                    // alert("lat :" + markers[i].getAttribute("lat") + "lang :" + markers[i].getAttribute("lng"));
                    if(markers[i].getAttribute("lat") !=''){
                        point = new google.maps.LatLng(
                        parseFloat(markers[i].getAttribute("lat")),
                        parseFloat(markers[i].getAttribute("lng")));
                    }
                    bounds.extend(point);
                    var html = "<h3>" + address + "</h3> <br/>" ;
                    html += "<br>";
                    //alert(shop_id);
                    //html += "<a href='{/literal}{$baseurl}{literal}minishop/shop_id/'"+shop_id+">go to minishop</a>";
                    html += "<a href='{/literal}{$baseurl}{literal}minishop/"+urltitle+"'>go to minishop</a>";
                        
                    if(markers[i].getAttribute("error")!=1){ // only if shop found , add the markers
                        addMarker(point, html , "active" , type); 
                    }
         
                }
                    // alert(markers[0].getAttribute("sql"));
               // alert(markers[0].getAttribute("error"));
                var error =  markers[0].getAttribute("error");  
                center = bounds.getCenter();
                map.fitBounds(bounds);
                map.panToBounds(bounds);
                if(error==1){ // if no shops found , override zoom level
                    map.setZoom(3); 
                }
            }
        });
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

    function doNothing() {}

    //]]>
        
    function addMarker(location, name, active , type) {   
        var icon = customIcons[type] || {};        
        var marker = new google.maps.Marker({
            position: location,
            map: map,
            icon: icon.icon,
            shadow: icon.shadow,
            //title: name,
            title: 'click',
            status: active
        });
        
        var popup = new google.maps.InfoWindow({
            content: name,
            maxWidth: 300
        });
        google.maps.event.addListener(marker, "click", function() {
            if (currentPopup != null) {
                currentPopup.close();
                currentPopup = null;
            }
            popup.open(map, marker);
            currentPopup = popup;
        });
        google.maps.event.addListener(popup, "closeclick", function() {
            map.panTo(center);
            currentPopup = null;
        });
    }
        
        
        
        
        

</script>


<body onload="load()">
    <div id="map"></div>
</body>

</html>


<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
{pageaddvar name="stylesheet" value="themes/CityPilot/style/minishopgooglemap.css"}
<style>
    #map {
       /*width: 500px;*/
       /*height: 300px;*/
        border: 1px solid #808080;
        background: white url('{{$baseurl}}zselexdata/loadingmap.gif') no-repeat 50% 50%;
    }​
</style>

<input type="hidden" id="shopAddress" value="{$mapaddress}">
<input type="hidden" id="shopName" value="{$shop_name}">
<input type="hidden" id="zoom" value="{$zoom}">
<script type="text/javascript">
  
    window.onload=function(){loadMap()};

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
    function loadMap(){
        // Define the latitude and longitude positions
        var lats =     '{{$lat}}';
        var lngs =     '{{$long}}';
        var address = jQuery('#shopAddress').val();
        var shopname =  jQuery('#shopName').val();
        var zoomLevel =  jQuery('#zoom').val();
      
        
        //alert(address);
       // alert(zoomLevel);
        var html = '';
        var latitude = parseFloat(lats); // Latitude get from above variable
        var longitude = parseFloat(lngs); // Longitude from same
        var latlngPos = new google.maps.LatLng(latitude, longitude);
        var infoWindow = new google.maps.InfoWindow;
        // Set up options for the Google map
        var myOptions = {
            zoom: parseFloat(zoomLevel),
            center: latlngPos,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            zoomControlOptions: false,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.LARGE
            }
        };
        // Define the map
        map = new google.maps.Map(document.getElementById("map"), myOptions);
        var icon = customIcons['restaurant'] || {};
        html += "<b>"+shopname+"</b><br>" + address;
        // Add the marker
        var marker = new google.maps.Marker({
            position: latlngPos,
            map: map
            //icon: icon.icon,
            //shadow: icon.shadow
        });
        bindInfoWindow(marker, map, infoWindow, html);
        
    }
    
    function bindInfoWindow(marker, map, infoWindow, html) {
        google.maps.event.addListener(marker, 'click', function() {
            infoWindow.setContent(html);
            infoWindow.open(map, marker);
        });
    }
</script>
{$displaymap}
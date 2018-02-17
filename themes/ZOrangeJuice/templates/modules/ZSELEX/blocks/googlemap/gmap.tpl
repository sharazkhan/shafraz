
 
   
  {pageaddvar name='javascript' value='jquery'}
  {pageaddvar name='javascript' value='zikula.ui'}
  <body onLoad="load()"> 
  {if $perm}
    {if $servicePerm gt 0}
<table class="edit" style="padding-top:17px;padding-left:20px">
    <tr>
        <td>
    {if $vars.displayinfo eq 'yes'}
      <a class="infoclass"  id="gmapInfo" href="{modurl modname='ZSELEX' type='info' func='displayInfo' bid=$bid}" title="{gt text=$info.title}">
          <img  src="{$baseurl}images/icons/extrasmall/info.png">
      </a>
    {/if}
       </td>
       <td>&nbsp;</td>
        <td>
    <a href="{modurl  modname='ZSELEX' type='admin' func='viewshop'}">
      <img title="{gt text=Manage}"  src="{$baseurl}images/icons/extrasmall/configure.png">
      </a>
        </td>
   </tr>
</table>
          {/if}
      {/if}

       <!--div style="clear:both"></div-->
      {$displaymap}
  </body>
  
  <script type="text/javascript">
 var defwindowajax = new Zikula.UI.Window($('gmapInfo'),{resizable: true });
  var $curr = jQuery(".edit");
      $curr = $curr.prev();
      $curr.css("width", "auto");
      $curr.css("float", "left");
      $curr.css("padding-top", "2px");
      $curr.css("background-position", "1px 2px");
 </script>
  
   <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
   <script type="text/javascript">
    //<![CDATA[

    var customIcons = {
      restaurant: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png',
        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
      },
      bar: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_red.png',
        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
      }
      ,
       shop: {
        icon: '{/literal}{$baseurl}{literal}modules/ZSELEX/images/headers_shop.gif',
        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
      }
    };

    function load() {
        var shopId = '{/literal}{$smarty.request.shopId}{literal}';
            //alert(shopId);
      
      var map = new google.maps.Map(document.getElementById("map"), {
        //center: new google.maps.LatLng(47.6145, -122.3418),
        center: new google.maps.LatLng('{/literal}{$lat}{literal}', '{/literal}{$long}{literal}'),
        //center: new google.maps.LatLng(20.593684, 78.96288),
        zoom: 8,
        mapTypeId: 'roadmap'
      });
      var infoWindow = new google.maps.InfoWindow;

      // Change this depending on the name of your PHP file
      downloadUrl('{/literal}{$baseurl}{literal}index.php?module=ZSELEX&type=ajax&func=getShopMapLocation&shopId='+shopId, function(data) {
           
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName("marker");
        for (var i = 0; i < markers.length; i++) {
          // alert(markers[i].getAttribute("name"));
          var name = markers[i].getAttribute("name");
          var address = markers[i].getAttribute("address");
          var type = markers[i].getAttribute("type");
          var point = new google.maps.LatLng(
              parseFloat(markers[i].getAttribute("lat")),
              parseFloat(markers[i].getAttribute("lng")));
          var html = "<h3>" + address + "</h3> <br/>" ;
          html += "<br>";
          html += "<a href='{/literal}{$baseurl}{literal}miniShop/shopId/{/literal}{$smarty.request.shopId}{literal}'>go to minishop</a>";
          var icon = customIcons[type] || {};
          var marker = new google.maps.Marker({
            map: map,
            position: point,
            icon: icon.icon,
            shadow: icon.shadow
          });
          bindInfoWindow(marker, map, infoWindow, html);
        }
      });
    }

    function bindInfoWindow(marker, map, infoWindow, html) {
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
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

  </script>
  


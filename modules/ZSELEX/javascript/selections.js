
var homepage = Zikula.Config.baseURL;
var curr_url = window.location.href;
function regionSelect(regionId, regionName) {   // called on region on change
    //alert('region call'); exit();
    var current_theme = jQuery('#current_theme').val();
    //alert(current_theme);
    if (jQuery('#level').length) {

        jQuery('#level').val('region');
    }



    //jQuery('select#region-combo option[value="'+regionId+'"]').attr("selected",true);

    var regionId_cookie = regionId;
    var regionName_cookie = regionName;
    setCookie('region_cookie', regionId_cookie, '1');
    setCookie('regionname_cookie', regionName_cookie, '1');
    if (regionId != '') {
        var getRegionCookie = regionId;
        var getRegionNameCookie = regionName;
    } else {
        var getRegionCookie = getCookie('region_cookie');
        var getRegionNameCookie = getCookie('regionname_cookie');
    }
    jQuery("#SelectedRegion").html(getRegionNameCookie);
    jQuery("#SelectedCity").html('');
    //jQuery(".MapDivTxt").html(Zikula.__('Select All Cities','module_zselex_js'));
    //  alert(getRegionCookie);
    // document.getElementById("first1").style.fill = "#e45624";

    // document.getElementById('city-combo').value = '';

    setCookie('area_cookie', '', -1);
    setCookie('areaname_cookie', '', -1);
    setCookie('shop_cookie', '', -1);
    setCookie('city_cookie', '', -1);
    setCookie('cityname_cookie', '', -1);
    // closeBcLi('bc_reg');
    // if(!document.getElementById("bc_reg")){
    jQuery("#bc_reg").remove();
    jQuery("#bc_city").remove();
    jQuery("#bc_area").remove();
    if (regionId > 0) {
        jQuery('.BrudcomeTree').append('<li id=bc_reg><a href="#">' + regionName + '</a><img class="bc_button" src="' + Zikula.Config.baseURL + 'themes/' + current_theme + '/images/black_close.png" onclick=closeBcLi("bc_reg")></li>');
    }

    // }
    //document.getElementById('shop-combo').value = '';

    jQuery('#hcity').val('');
    jQuery('#harea').val('');
    jQuery('#hshop').val('');

    jQuery('#hcity_name').val('');
    jQuery('#hareaname').val('');
    jQuery('#hshop_name').val('');



    //document.getElementById('hregion').value = regionId;
    jQuery('#hregion').val(getRegionCookie);
    jQuery('#hregionname').val(getRegionNameCookie);
    jQuery('#setRegion').html(regionName);
    jQuery('#regionCities').html(Zikula.__('loading cities...', 'module_zselex_js'));

    //exit();

    var hcountry = jQuery('#hcountry').val();
    var hregion = jQuery('#hregion').val();
    //  alert('hiii');
    getCityListAll();
    getAreaListAll();
    //getShopsListAll();
    displayBlocks();
    var pars = "module=ZSELEX&type=ajax&func=getCitiesMap&country_id=" + hcountry + "&region_id=" + hregion + "&region_name=" + regionName;
    //alert(pars);
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: false,
                onComplete: regionSelectResponses
            });

}


function regionSelectResponses(req) {


    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }

    var json = pndejsonize(req.responseText);
    //alert(json.data);
    jQuery('#regionCities').html(json.data);
    if (homepage == curr_url) {
        //displayBlocks();
    } else {
        resetAutocomplete();
    }
}


/**
 * Select City
 * 
 * @param {int} cityId
 * @param {string} cityName
 * @returns {Boolean}
 */
function citySelect(cityId, cityName) {
    // alert('helloo');
    // alert(cityId + "-" + cityName); exit();
    var current_theme = jQuery('#current_theme').val();
    if (jQuery('#level').length) {
        jQuery('#level').val('city');

    }


    var cityId_cookie = cityId;
    var cityname_cookie = cityName;
    var hregion = jQuery('#hregion').val();
    if (hregion < 1) {
        // alert(Zikula.__('Please select a region', 'module_zselex_js'));
        //return false;
    }
    setCookie('city_cookie', cityId_cookie, '1');
    setCookie('cityname_cookie', cityname_cookie, '1');
    if (cityId != '') {
        var getCityCookie = cityId;
        var getCityNameCookie = cityName;
    } else {
        var getCityCookie = getCookie('city_cookie');
        var getCityNameCookie = getCookie('cityname_cookie');
    }
    if (hregion != '') {
        var getRegionCookie = hregion;
        var getRegionNameCookie = jQuery('#hregionname').val();
    } else {
        var getRegionCookie = getCookie('region_cookie');
        var getRegionNameCookie = getCookie('regionname_cookie');
    }

    // jQuery("#SelectedCity").html(cityName)
    jQuery("#SelectedRegion").html(getRegionNameCookie);
    if (hregion > 0) {
        jQuery("#SelectedCity").html("&nbsp;&raquo;&nbsp;" + getCityNameCookie);
    } else {
        jQuery("#SelectedCity").html(getCityNameCookie);
    }
    jQuery(".MapDivTxt").html(getCityNameCookie);
    //document.getElementById('region-combo').value = '';
    setCookie('area_cookie', '', -1);
    setCookie('shop_cookie', '', -1);
    jQuery('#harea').val('');
    jQuery('#hshop').val('');

    jQuery('#hareaname').val('');
    jQuery('#hshop_name').val('');


    //document.getElementById('hcity').value = cityId; 
    jQuery('#hcity').val(getCityCookie);
    jQuery('#hcity_name').val(cityName);

    jQuery('#hregion').val(getRegionCookie);
    jQuery('#hregionname').val(getRegionNameCookie);


    //  if(!document.getElementById("bc_city")){
    jQuery("#bc_city").remove();
    jQuery("#bc_area").remove();
    if (cityId > 0) {
        jQuery('.BrudcomeTree').append('<li id=bc_city><a href="#">' + cityName + '</a><img class="bc_button" src="' + Zikula.Config.baseURL + 'themes/' + current_theme + '/images/black_close.png" onclick=closeBcLi("bc_city")></li>');
    }
    // }

    getAreaListAll();
    // getShopsListAll();
    displayBlocks();
    var ID;
    ID = jQuery('#MapDivImg').val();
    var mapCss = jQuery("#MapDivImg").css("background-color");
    // alert(mapCss);
    if (mapCss != "rgb(231, 231, 231)") {
        jQuery("#MapDivImg").css("background-color", "#e7e7e7");
    } else {
        //alert('hii');
        jQuery("#MapDivImg").css("background-color", "");
    }
    jQuery("#MapBlock").toggle();
    return false;
}

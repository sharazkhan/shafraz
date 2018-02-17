var globalCount = 0;
var gcat = 0;
var currFunc = '';

function currentTheme() {
    var currTheme = '';
    if (document.getElementById('curent_theme')) {
        currTheme = document.getElementById('curent_theme').value;
    }
    return currTheme;
}
function getCountry(countryname) {
    document.getElementById('setselectbox').style.display = 'block';
    var pars = "module=ZSELEX&type=ajax&func=getCountry&input=" + countryname;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, onComplete: myFunctiongetCountryResponses});
}
function myFunctiongetCountryResponses(req)
{
    var countries;
    var select;
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    var json = pndejsonize(req.responseText);
    countries = json.values;
    select = json.select;
    document.getElementById('setselectbox').innerHTML = countries;
    document.getElementById('replaceSelect').innerHTML = select;
}
function getVal(id, value) {
    jQuery('#countrytext').val(value);
    document.getElementById('parentcountry').value = id;
    document.getElementById('setselectbox').innerHTML = '';
}
function getlp(id) {
    var val = "#" + id;
    var str = jQuery(val).text();
    document.getElementById('countrytext').value = str;
    document.getElementById('parentcountry').value = id;
    document.getElementById('setselectbox').innerHTML = '';
    document.getElementById('setselectbox').style.display = 'none';
}
function chagecolor(id) {
    document.getElementById(id).style.background = 'white';
}
function hidecolor(id) {
    document.getElementById(id).style.background = '#eee';
}
function out() {
    document.getElementById('setselectbox').innerHTML = '';
    document.getElementById('countrytext').value = 'search...';
    document.getElementById('setselectbox').style.display = 'none';
}
function outSelect() {
    document.getElementById('setselectbox').innerHTML = '';
    document.getElementById('setselectbox').style.display = 'none';
}
function resetCountryDropdown() {
    document.getElementById('setselectbox').innerHTML = '';
    document.getElementById('setselectbox').style.display = 'none';
    var countryname = '';
    var pars = "module=ZSELEX&type=ajax&func=resetCountryDropdown&input=" + countryname;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, onComplete: myFunctionresetCountryDropdownResponses});
}
function myFunctionresetCountryDropdownResponses(req)
{
    var select;
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    var json = pndejsonize(req.responseText);
    select = json.select;
    document.getElementById('replaceSelect').innerHTML = select;
}
function getRegionShopListCombo(region_id, country_id) {
    if (document.getElementById('startval')) {
        document.getElementById('startval').value = '0';
    }
    var hcountry = jQuery('#hcountry').val();
    var hregion = jQuery('#hregion').val();
    var hcity = jQuery('#hcity').val();
    var harea = jQuery('#harea').val();
    var pars = "module=ZSELEX&type=ajax&func=getShopsListAll&area_id=" + harea + "&city_id=" + hcity + "&region_id=" + hregion + "&country_id=" + hcountry;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, onComplete: myFunctiongetRegionShopListComboResponses});
}
function myFunctiongetRegionShopListComboResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    document.getElementById('shop-div').innerHTML = req.responseText;
    if (currentTheme() != 'CityPilotresponsive') {
        jQuery("#shop-combo").ZselexCombo({emptyText: "Choose a shop..."});
    }
}
function getShopss(city_id) {
    var hcountry = jQuery('#hcountry').val();
    var hregion = jQuery('#hregion').val();
    var hcity = jQuery('#hcity').val();
    var harea = jQuery('#harea').val();
    var pars = "module=ZSELEX&type=ajax&func=getShopsListAll&area_id=" + harea + "&city_id=" + hcity + "&region_id=" + hregion + "&country_id=" + hcountry;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, onComplete: myFunctiongetShopssResponses});
}
function myFunctiongetShopssResponses(req) {
    var shops;
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    document.getElementById('shop-div').innerHTML = req.responseText;
    if (currentTheme() != 'CityPilotresponsive') {
        jQuery("#shop-combo").ZselexCombo({emptyText: "Choose a shop..."});
    }
}
function getCitiess(region_id) {
    var hcountry = jQuery('#hcountry').val();
    var pars = "module=ZSELEX&type=ajax&func=getCitiess&region_id=" + region_id + "&country_id=" + hcountry;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, onComplete: myFunctiongetCitiessResponses});
}
function myFunctiongetCitiessResponses(req) {
    var cities;
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    var json = pndejsonize(req.responseText);
    cities = json.cities;
    document.getElementById('city-div').innerHTML = cities;
    if (currentTheme() != 'CityPilotresponsive') {
        jQuery("#city-combo").ZselexCombo({emptyText: "Choose a city..."});
    }
}
function getRegionss() {
    var hcountry = jQuery('#hcountry').val();
    var pars = "module=ZSELEX&type=ajax&func=getRegionss&id=" + hcountry;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, onComplete: myFunctiongetRegionssResponses});
}
function myFunctiongetRegionssResponses(req) {
    var select;
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    var json = pndejsonize(req.responseText);
    select = json.select;
    jQuery("#region-div").html(select);
    if (currentTheme() != 'CityPilotresponsive') {
        jQuery("#region-combo").ZselexCombo({emptyText: "Choose a region..."});
    }
}
function getRegionsAll() {
    var hcountry = jQuery('#hcountry').val();
    var pars = "module=ZSELEX&type=ajax&func=getRegionsAll&id=" + hcountry + "&edit=front";
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, onComplete: getRegionsAllResponses});
}
function getRegionsAllResponses(req) {
    var select;
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    var json = pndejsonize(req.responseText);
    select = json.select;
    jQuery("#region-div").html(select);
    jQuery("#region-combo").chosen({width: "100%"});
}
function getCityListAll() {
    var hcountry = jQuery('#hcountry').val();
    // var hregion = jQuery('#hregion').val();
    var hregion = getCookie('region_cookie');
    var pars = "module=ZSELEX&type=ajax&func=getCityListAllFront&country_id=" + hcountry + "&region_id=" + hregion + "&edit=front";
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, onComplete: getCityListAllResponses});
}
function getCityListAllResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    // alert(req.responseText); exit();
    document.getElementById('city-div').innerHTML = req.responseText;
    var citySelector = "#city-combo1";
    jQuery(citySelector).chosen({width: "100%"});

}
function getCountryCityList(country_id) {
    if (document.getElementById('startval')) {
        document.getElementById('startval').value = '0';
    }
    var pars = "module=ZSELEX&type=ajax&func=getCountryCityList1&country_id=" + country_id;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, onComplete: myFunctiongetCountryCityListResponses});
}
function myFunctiongetCountryCityListResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    document.getElementById('city-div').innerHTML = req.responseText;
    if (currentTheme() != 'CityPilotresponsive') {
        jQuery("#city-combo").ZselexCombo({emptyText: "Choose a city..."});
    }
}
function getCountryShopList(country_id) {
    if (document.getElementById('startval')) {
        document.getElementById('startval').value = '0';
    }
    var hcountry = jQuery('#hcountry').val();
    var hregion = jQuery('#hregion').val();
    var hcity = jQuery('#hcity').val();
    var harea = jQuery('#harea').val();
    var pars = "module=ZSELEX&type=ajax&func=getShopsListAll&area_id=" + harea + "&city_id=" + hcity + "&region_id=" + hregion + "&country_id=" + hcountry;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, onComplete: myFunctiongetCountryShopListResponses});
}
function myFunctiongetCountryShopListResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    document.getElementById('shop-div').innerHTML = req.responseText;
    if (currentTheme() != 'CityPilotresponsive') {
        jQuery("#shop-combo").ZselexCombo({emptyText: "Choose a shop..."});
    }
}
function getShopsListAll() {
    if (document.getElementById('startval')) {
        document.getElementById('startval').value = '0';
    }
    var hcountry = jQuery('#hcountry').val();
    var getRegionCookie = getCookie('region_cookie');
    var getCityCookie = getCookie('city_cookie');
    var getAreaCookie = getCookie('area_cookie');
    var hregion = getRegionCookie;
    var hcity = getCityCookie;
    var harea = getAreaCookie;
    var pars = "module=ZSELEX&type=ajax&func=getShopsListAll&area_id=" + harea + "&city_id=" + hcity + "&region_id=" + hregion + "&country_id=" + hcountry;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, onComplete: getShopsListAllResponses});
}
function getShopsListAllResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    document.getElementById('shop-div').innerHTML = req.responseText;
    if (currentTheme() != 'CityPilotresponsive') {
        jQuery("#shop-combo").ZselexCombo({emptyText: "Choose a shop..."});
    }
}
function getAreaListAll() {
    // alert('area');
    var hcountry = jQuery('#hcountry').val();
    var getRegionCookie = getCookie('region_cookie');
    var getCityCookie = getCookie('city_cookie');
    var hregion = getRegionCookie;
    var hcity = getCityCookie;
    var lang = jQuery('#curr_lang').val();
    var pars = "module=ZSELEX&type=ajax&func=getAreaListAll&lang=" + lang + "&city_id=" + hcity + "&region_id=" + hregion + "&country_id=" + hcountry;
    // alert(pars);
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, onComplete: getAreaListAllResponses});
}
function getAreaListAllResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    document.getElementById('area-div').innerHTML = req.responseText;
    if (currentTheme() != 'CityPilotresponsive') {
        jQuery("#area-combo").ZselexCombo({emptyText: "Choose a area..."});
    }
}
function getCountryAreaList(country_id) {
    if (document.getElementById('startval')) {
        document.getElementById('startval').value = '0';
    }
    var hcountry = jQuery('#hcountry').val();
    var hregion = jQuery('#hregion').val();
    var hcity = jQuery('#hcity').val();
    var pars = "module=ZSELEX&type=ajax&func=getAreaList&city_id=" + hcity + "&region_id=" + hregion + "&country_id=" + hcountry;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, onComplete: myFunctiongetCountryAreaListResponses});
}
function myFunctiongetCountryAreaListResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    document.getElementById('area-div').innerHTML = req.responseText;
    if (currentTheme() != 'CityPilotresponsive') {
        jQuery("#area-combo").ZselexCombo({emptyText: "Choose a area..."});
    }
}
function getRegionAreaList(region_id) {
    if (document.getElementById('startval')) {
        document.getElementById('startval').value = '0';
    }
    var pars = "module=ZSELEX&type=ajax&func=getRegionAreaList&region_id=" + region_id;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, onComplete: myFunctiongetRegionAreaListResponses});
}
function myFunctiongetRegionAreaListResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    document.getElementById('area-div').innerHTML = req.responseText;
    if (currentTheme() != 'CityPilotresponsive') {
        jQuery("#area-combo").ZselexCombo({emptyText: "Choose a area..."});
    }
}
function getCityAreaList(city_id) {
    var hcountry = jQuery('#hcountry').val();
    var hregion = jQuery('#hregion').val();
    var hcity = jQuery('#hcity').val();
    if (document.getElementById('startval')) {
        document.getElementById('startval').value = '0';
    }
    var pars = "module=ZSELEX&type=ajax&func=getAreaList&city_id=" + hcity + "&region_id=" + hregion + "&country_id=" + hcountry;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, onComplete: myFunctiongetCityAreaListResponses});
}
function myFunctiongetCityAreaListResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    document.getElementById('area-div').innerHTML = req.responseText;
    if (currentTheme() != 'CityPilotresponsive') {
        jQuery("#area-combo").ZselexCombo({emptyText: "Choose a area..."});
    }
}
function getAreaShopList(area_id, city_id) {
    if (document.getElementById('startval')) {
        document.getElementById('startval').value = '0';
    }
    var hcountry = jQuery('#hcountry').val();
    var hregion = jQuery('#hregion').val();
    var hcity = jQuery('#hcity').val();
    var harea = jQuery('#harea').val();
    var pars = "module=ZSELEX&type=ajax&func=getShopsListAll&area_id=" + area_id + "&city_id=" + city_id + "&region_id=" + hregion + "&country_id=" + hcountry;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, onComplete: myFunctiongetAreaShopListResponses});
}
function myFunctiongetAreaShopListResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    document.getElementById('shop-div').innerHTML = req.responseText;
    if (currentTheme() != 'CityPilotresponsive') {
        jQuery("#shop-combo").ZselexCombo({emptyText: "Choose a shop..."});
    }
}
function getAllCountry(country_id) {
    if (document.getElementById('startval')) {
        document.getElementById('startval').value = '0';
    }
    var pars = "module=ZSELEX&type=ajax&func=getAllCountry";
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, onComplete: myFunctiongetAllCountryResponses});
}
function myFunctiongetAllCountryResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    document.getElementById('country-div').innerHTML = req.responseText;
    if (currentTheme() != 'CityPilotresponsive') {
        jQuery("#country-combo").ZselexCombo({emptyText: "Choose a country..."});
    }
}

/**
 * Get all categories
 * 
 * @returns {undefined}
 */
function getAllCat() {
    // alert('hii');
    if (jQuery("#startval").length) {
        jQuery("#startval").val('0');
    }
    var lang = jQuery('#curr_lang').val();
    var pars = "module=ZSELEX&type=ajax&func=getAllCat&lang=" + lang;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, onComplete: getAllCatResponse});
}
function getAllCatResponse(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    document.getElementById('cat-div').innerHTML = req.responseText;

    jQuery('#cat-combo').chosen({width: "100%"});
    // Zikula.__('search for...', 'module_zselex_js')

}
function getAllbranch() {
    if (jQuery("#startval").length) {
        jQuery("#startval").val('0');
    }
    var pars = "module=ZSELEX&type=ajax&func=getAllBranch";
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, onComplete: getAllbranchtResponse});
}
function getAllbranchtResponse(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    document.getElementById('branch-div').innerHTML = req.responseText;
    if (currentTheme() != 'CityPilotresponsive') {
        jQuery("#branch-combo").ZselexCombo({emptyText: "Choose a branch..."});
    }
}
function myFunctionOnChangeResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    var json = pndejsonize(req.responseText);
    var lat = json.lat;
    var lng = json.lng;
    var type = json.type;
    var name;
    var id;
    document.getElementById('cLat').value = lat;
    document.getElementById('cLng').value = lng;
    if (type == 'country') {
        name = json.cntry;
        id = jQuery('#hcountry').val()
    } else if (type == 'region') {
        name = json.region;
        id = jQuery('#hregion').val()
    } else if (type == 'city') {
        name = json.city;
        id = jQuery('#hcity').val()
    } else if (type == 'area') {
        name = json.area;
        id = document.getElementById('harea').value
    } else if (type == 'shop') {
        name = json.shop;
        id = document.getElementById('hshop').value
    }
    load(name, id, type);
    getShopDetails(document.getElementById('hshop').value)
    getAdDetails();
    getShopAd();
}
function loadCatMap() {
    if (document.getElementById('type')) {
        var type = document.getElementById('type').value
    }
    if (type == 'country') {
        countryOnChange(document.getElementById('name').value, 'country')
    } else if (type == 'region') {
        regionOnChange(document.getElementById('name').value, 'area')
    } else if (type == 'city') {
        cityOnChange(document.getElementById('name').value, 'city')
    } else if (type == 'shop') {
        shopOnChange(document.getElementById('name').value, 'shop')
    } else if (type == 'area') {
        areaOnChange(document.getElementById('name').value, 'area')
    } else {
        displayBlocks();
    }
}


function getUrlParameter(sParam)
{
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++)
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam)
        {
            return sParameterName[1];
        }
    }
}

function displayBlocks() {
   
    resetAutocomplete();
    if (jQuery("#first_load").val() == false) {
        slideSwitch();
    }
    // slideSwitch();

    if (jQuery("#upcommingevents").length) {
        getupcommingEvents('');
    }

    if (jQuery("#specialdeal_block").length) {
        getSpecialDeals();
    }
    if (jQuery("#pageData").length) {
        // alert('showEvents'); exit();
        // jQuery("#pageData").html('');
        //jQuery("#pageData").html('<img src=' + Zikula.Config.baseURL + 'modules/ZSELEX/images/loading.gif style=padding-left:20px>');
        // exit();
        eventFirstLoad = true;
        showEvents('0');
    }

    if (jQuery("#newShopBlock").length) {
        getNewShops();
    }

    if (jQuery("#BreadCrumHead").length) {
        searchBreadcrums();
    }
    jQuery('#pageload').val(0);
    // location.reload(Zikula.Config.baseURL);

    var homepage = Zikula.Config.baseURL;
    // var curr_url = window.location.href;
    var curr_url = window.location.href.split('?')[0];
    //alert(curr_url); exit();

    if (homepage != curr_url) {
        window.location = Zikula.Config.baseURL;
    }


}

function countryOnChange(country) {
    if (document.getElementById('level')) {
        document.getElementById('level').value = 'country';
    }
    document.getElementById('region-combo').value = '';
    document.getElementById('city-combo').value = '';
    document.getElementById('shop-combo').value = '';
    jQuery('#hregion').val('');
    document.getElementById('hcity').value = '';
    document.getElementById('harea').value = '';
    document.getElementById('hshop').value = '';
    document.getElementById('hregionname').value = '';
    document.getElementById('hcity_name').value = '';
    document.getElementById('hareaname').value = '';
    document.getElementById('hshop_name').value = '';
    if (document.getElementById('country-combo').value > 0) {
        document.getElementById('hcountry').value = document.getElementById('country-combo').value;
        document.getElementById('hcountryname').value = country;
    } else {
        document.getElementById('hcountry').value = document.getElementById('default_country_id').value;
        document.getElementById('hcountryname').value = document.getElementById('default_country_name').value;
    }
    getRegionsAll();
    getCityListAll()
    getAreaListAll();
    getShopsListAll();
    if (document.getElementById('map')) {
    }
    if (document.getElementById('blockshop')) {
    }
    if (document.getElementById('blockadcontent')) {
    }
    if (document.getElementById('blockshopscontent')) {
    }
    if (document.getElementById('newstest')) {
    }
    if (document.getElementById('eventblock')) {
    }
    if (document.getElementById('upcommingevents')) {
    }
    if (document.getElementById('allupcommingevents')) {
    }
    setSelectionSessions();
}
function regionOnChange(region, type) {
    if (document.getElementById('level')) {
        document.getElementById('level').value = 'region';
    }
    document.getElementById('city-combo').value = '';
    document.getElementById('shop-combo').value = '';
    document.getElementById('hcity').value = '';
    document.getElementById('harea').value = '';
    document.getElementById('hshop').value = '';
    document.getElementById('hcity_name').value = '';
    document.getElementById('hareaname').value = '';
    document.getElementById('hshop_name').value = '';
    document.getElementById('hregion').value = document.getElementById('region-combo').value;
    getCityListAll();
    getAreaListAll();
    getShopsListAll();
    displayBlocks();
}
function cityOnChange(city, type) {
    if (document.getElementById('level')) {
        document.getElementById('level').value = 'city';
    }
    jQuery('#harea').val('');
    jQuery('#hshop').val('');
    jQuery('#hareaname').val('');
    jQuery('#hshop_name').val('');

    jQuery('#hcity').val(jQuery('#city-combo').val());
    jQuery('#hcity_name').val(city);

    getAreaListAll();
    getShopsListAll();
    displayBlocks();
}
function areaOnChange(area, type) {
    // alert(area);
    var pageload = jQuery('#pageload').val();
    if (jQuery('#level').length) {
        jQuery('#level').val('area');
    }

    var areaId = jQuery('#area-combo').val();
    //  var areaName = jQuery('#hareaname').val();
    var areaName = area;
    //alert(areaName);
    var current_theme = jQuery('#current_theme').val();
    setCookie('area_cookie', areaId, '1');
    setCookie('areaname_cookie', areaName, '1');
    jQuery("#bc_area").remove();
    if (area != '') {
        jQuery('.BrudcomeTree').append('<li id=bc_area><a href="#">' + areaName + '</a><img class="bc_button" src="' + Zikula.Config.baseURL + 'themes/' + current_theme + '/images/black_close.png" onclick=closeBcLi("bc_area")></li>');
    }
    var getAreaCookie = getCookie('area_cookie');
    jQuery('#harea').val(getAreaCookie);
    jQuery('#hareaname').val(area);
    jQuery('#hshop').val('');
    jQuery('#hshop_name').val('');

    if (pageload == 0) {
        // alert('area load');
        displayBlocks();
    }
}
function shopOnChange(shop) {
    if (document.getElementById('startval')) {
        document.getElementById('startval').value = '0';
    }
    var shopId = document.getElementById('shop-combo').value;
    setCookie('shop_cookie', shopId, '1');
    var getShopCookie = getCookie('shop_cookie');
    document.getElementById('hshop').value = getShopCookie;
    document.getElementById('hshop_name').value = shop;
    if (document.getElementById('level')) {
        document.getElementById('level').value = 'shop';
    }
    displayBlocks();
}
function categoryOnChange(category) {
    //alert('hiii');
    if (document.getElementById('startval')) {
        document.getElementById('startval').value = '0';
    }
    var pars = "module=ZSELEX&type=ajax&func=test4all&category=" + category + "&types=category&shop_id=" + document.getElementById('hcategory').value;
    displayBlocks();
}

/*
 jQuery(function() {
 jQuery("#country-combo").ZselexCombo({emptyText: "Choose a country..."});
 jQuery("#region-combo").ZselexCombo({emptyText: "Choose a region..."});
 jQuery("#city-combo").ZselexCombo({emptyText: "Choose a city..."});
 jQuery("#area-combo").ZselexCombo({emptyText: "Choose a area..."});
 jQuery("#shop-combo").ZselexCombo({emptyText: "Choose a shop..."});
 jQuery("#cat-combo").ZselexCombo({emptyText: "Choose a category..."});
 jQuery("#branch-combo").ZselexCombo({emptyText: "Choose a branch..."});
 });
 */

jQuery(document).ready(function () {

    //alert('hiii');
    // alert(currentTheme());

    if (document.getElementById("cat-combo")) {
        // jQuery("#cat-combo").live('change', function () {
        //   jQuery("#cat-combo").on('change', function () {
        jQuery(".category-select").on('change', '#cat-combo', function () {
            // alert('catss');
            var pageload = jQuery('#pageload').val();
            var current_theme = jQuery('#current_theme').val();
            var categoryId = this.value;
            var catName = jQuery(this).find("option:selected").text();
            setCookie('category_cookie', categoryId, '1');
            setCookie('categoryName_cookie', catName, '1');
            var getCategoryCookie = getCookie('category_cookie');

            if (jQuery('#hcategory').val() > 0 && this.value == 0) {
                // alert('cat1');
                jQuery('#hcategory').val(getCategoryCookie);
                jQuery('#hcatname').val(catName);

                jQuery("#bc_cat").remove();
                displayBlocks();
            }
            if (this.value > 0) {
                // alert('cat2');
                jQuery('#hcategory').val(getCategoryCookie);
                jQuery('#hcatname').val(catName);

                jQuery("#bc_cat").remove();

                /* jQuery('.BrudcomeTree').append('<li id=bc_cat><a href="#">' + jQuery(this).find("option:selected").text() + '</a><img class="bc_button" src="' + Zikula.Config.baseURL + 'themes/' + current_theme + '/images/black_close.png" onclick=closeBcLi("bc_cat")></li>');*/
                jQuery('.BrudcomeTree').append('<li id=bc_cat><a href="#">' + jQuery(this).find("option:selected").text() + '</a><i class="fa fa-times remove-icon" onclick=closeBcLi("bc_cat")></i></li>');
                // alert('cat2s');
                // if (pageload == 0) {
                displayBlocks();
                // }
            }
            //  gcat = getCategoryCookie;
            //  alert(gcat);
            // resetAutocomplete();
        });
    }

    if (document.getElementById("area-combo")) {

        jQuery("#area-combo").on('change', function () {
            //alert('hello area');
            if (jQuery("#harea").val() > 0 && this.value < 1) {
                // alert('area1');
                jQuery("#hareaname").val('');
                jQuery("#name").val(jQuery(this).find("option:selected").text());

                var type;

                if (jQuery("#hcity").val() > 0) {
                    type = 'city';
                } else if (jQuery("#hcity").val() < 1 && jQuery("#hregion").val() > 0 && jQuery("#hcountry").val() > 0) {
                    type = 'region';
                } else if (jQuery("#hcity").val() < 1 && jQuery("#hregion").val() < 1 && jQuery("#hcountry").val() > 0) {
                    type = 'country';
                } else {
                    type = 'nill';
                }
                areaOnChange('', type);
                jQuery("#bc_area").remove();
            }
            if (this.value > 0) {
                // alert('area2');
                jQuery("#type").val('area');
                jQuery("#name").val(jQuery(this).find("option:selected").text());

                areaOnChange(jQuery(this).find("option:selected").text(), 'area');
            }
            // resetAutocomplete();
        });
    }


    if (document.getElementById("region-combo")) {
        jQuery(".region-select").on('change', '#region-combo', function () {
            //var cur_reg_id = this.value;
            // alert('region'); exit();
            if (jQuery('#hregion').val() > 0 && this.value < 1) {
                // alert('region1'); exit();
                regionSelect(this.value, jQuery(this).find("option:selected").text())
            }
            if (this.value > 0) {
                regionSelect(this.value, jQuery(this).find("option:selected").text())
            }

        });
    }


    if (document.getElementById("city-combo1")) {
        jQuery(".city-select").on('change', '#city-combo1', function () {

            // citySelect(this.value, jQuery(this).find("option:selected").text())
            if (jQuery('#hcity').val() > 0 && this.value < 1) {
                // alert('city1');
                citySelect(this.value, jQuery(this).find("option:selected").text())
            }
            if (this.value > 0) {
                // alert('city2');
                citySelect(this.value, jQuery(this).find("option:selected").text())
            }
        });
    }


    jQuery('#searchfield').keypress(function (event) {

        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            //alert('You pressed a "enter" key in textbox');
            if (jQuery('#hsearch').val() != '') {
                displayBlocks();
            }
        }

    });

});


function selectRegion(region_id) {
    var skillsSelect = document.getElementById("region-combo");
    var selectedText = skillsSelect.options[skillsSelect.selectedIndex].text;
    alert(selectedText);
}
function closeBcLi(id) {
    jQuery("#" + id).remove();
    if (id == 'bc_cat') {

        jQuery("#first_load").val('0');
        jQuery("#cat-div").html('<select id="cat-combo" name="category_id" size="1" class="chosen-select-search form-control" ><option value="">' + Zikula.__('search category', 'module_zselex_js') + '</option></select>');
        jQuery("#cat-combo").chosen({width: "100%"});
        setCookie('category_cookie', '', -1);

        jQuery('#hcategory').val('');
        getAllCat();
        displayBlocks();
    } else if (id == 'bc_reg') {
        jQuery("#first_load").val('0');
        setCookie('region_cookie', '', -1);
        jQuery("#hregion").val('');
        jQuery("#hregionname").val('');
        jQuery("#setRegion").html('');

        jQuery("#SelectedRegion").html('');
        setCookie('city_cookie', '', -1);
        jQuery("#hcity").val('');
        jQuery("#hcity_name").val('');
        jQuery("#regionCities").html('');

        jQuery("#SelectedCity").html('');
        jQuery("#bc_city").remove();
        jQuery("#region-div").html('<select id="region-combo" name="region_id" size="1" ><option value="">' + Zikula.__('search region', 'module_zselex_js') + '</option></select>');
        jQuery("#region-combo").chosen({width: "100%"});
        jQuery("#city-div").html('<select id="city-combo1" name="city_id" size="1" ><option value="">' + Zikula.__('search city', 'module_zselex_js') + '</option></select>');
        jQuery("#city-combo1").chosen({width: "100%"});
        //jQuery("#area-div").html('<select id="area-combo" name="area_id" size="1"><option value="">' + Zikula.__('search area', 'module_zselex_js') + '</option></select>');

        setCookie('area_cookie', '', -1);
        jQuery("#harea").val('');
        jQuery("#hareaname").val('');

        jQuery("#bc_area").remove();
        getRegionsAll();
        getCityListAll();
        getAreaListAll();
        displayBlocks();
    } else if (id == 'bc_area') {
        jQuery("#first_load").val('0');
        jQuery("#area-div").html('<select id="area-combo" name="area_id" size="1"><option value="">' + Zikula.__('search area', 'module_zselex_js') + '</option></select>');

        setCookie('area_cookie', '', -1);
        // alert('area close');
        setCookie('areaname_cookie', '', -1);
        jQuery("#harea").val('');
        jQuery("#hareaname").val('');

        getAreaListAll();
    } else if (id == 'bc_city') {
        jQuery("#first_load").val('0');
        setCookie('city_cookie', '', -1);
        jQuery("#hcity").val('');
        jQuery("#hcity_name").val('');
        jQuery("#regionCities").html('');

        jQuery("#SelectedCity").html('');
        jQuery("#city-div").html('<select id="city-combo1" name="city_id" size="1" ><option value="">' + Zikula.__('search city', 'module_zselex_js') + '</option></select>');
        jQuery("#city-combo1").chosen({width: "100%"});
        //  jQuery("#area-div").html('<select id="area-combo" name="area_id" size="1"><option value="">' + Zikula.__('search area', 'module_zselex_js') + '</option></select>');

        setCookie('area_cookie', '', -1);
        jQuery("#harea").val('');
        jQuery("#hareaname").val('');

        jQuery("#bc_area").remove();
        getCityListAll();
        getAreaListAll();
        displayBlocks();
    } else if (id == 'bc_search') {
        setCookie('search_cookie', '', -1);
        jQuery("#first_load").val('0');
        jQuery("#hsearch").val(Zikula.__('search for...', 'module_zselex_js'));
        jQuery("#searchfield").val(Zikula.__('search for...', 'module_zselex_js'));

        displayBlocks();
    } else if (id == 'bc_branch') {
        setCookie('branch_cookie', '', -1);
        jQuery("#hbranch").val('');
        jQuery("#first_load").val('0');
        displayBlocks();
    } else if (id == 'bc_affiliate') {
        setCookie('affiliate_cookie', '', -1);
        jQuery("#aff_id").val('');
        jQuery("#first_load").val('0');
        displayBlocks();
    }
}
jQuery("#branch-combo").live('change', function () {
    var branchId = this.value;
    setCookie('branch_cookie', branchId, '1');
    var getBranchCookie = getCookie('branch_cookie');
    if (jQuery("#hbranch").val() > 0 && this.value == 0) {
        jQuery("#hbranch").val(getBranchCookie);
        loadCatMap();
    }
    if (this.value > 0) {
        jQuery("#hbranch").val(getBranchCookie);
        loadCatMap();
    }
});
jQuery("#country-combo").live('change', function () {
    if (jQuery('#hcountry').val() > 0 && this.value == 0) {
        countryOnChange('');
    }
    if (this.value > 0) {
        document.getElementById('type').value = 'country';
        document.getElementById('name').value = jQuery(this).find("option:selected").text();
        countryOnChange(jQuery(this).find("option:selected").text());
    }
});
/*
 jQuery("#region-combo111").live('change', function() {
 if (jQuery('#hregion').val() > 0 && this.value == 0) {
 document.getElementById('hregionname').value = '';
 document.getElementById('type').value = 'country';
 document.getElementById('name').value = jQuery(this).find("option:selected").text();
 var type;
 regionOnChange('', type)
 }
 if (this.value > 0) {
 document.getElementById('type').value = 'region';
 document.getElementById('hregionname').value = jQuery(this).find("option:selected").text();
 document.getElementById('name').value = jQuery(this).find("option:selected").text();
 regionOnChange(jQuery(this).find("option:selected").text(), 'region')
 }
 });
 jQuery(".region-combo").live('click', function() {
 });
 */
jQuery("#city-combo").live('change', function () {
    if (jQuery('#hcity').val() > 0 && this.value < 1) {
        document.getElementById('hcity_name').value = '';
        document.getElementById('type').value = 'region';
        document.getElementById('name').value = jQuery(this).find("option:selected").text();
        var type;
        if (jQuery('#hregion').val() > 0) {
            type = 'region';
        } else if (jQuery('#hregion').val() < 0 && jQuery('#hcountry').val() > 0) {
            type = 'country';
        } else {
            type = 'nill';
        }
        cityOnChange('', type);
    }
    if (this.value > 0) {
        document.getElementById('type').value = 'city';
        document.getElementById('name').value = jQuery(this).find("option:selected").text();
        cityOnChange(jQuery(this).find("option:selected").text(), 'city');
    }
});

jQuery("#shop-combo").live('change', function () {
    if (document.getElementById('hshop').value > 0 && this.value < 1) {
        document.getElementById('hshop_name').value = '';
        document.getElementById('name').value = jQuery(this).find("option:selected").text();
        var type;
        shopOnChange('', type);
    }
    if (this.value > 0) {
        document.getElementById('type').value = 'shop';
        document.getElementById('name').value = jQuery(this).find("option:selected").text();
        shopOnChange(jQuery(this).find("option:selected").text());
    }
});
var myVar;
function myStopFunction()
{
    jQuery("#fadeshow22").fadeOut('fast');
    window.clearInterval(interval)
}

/**
 * Resets the search
 * 
 * @returns {void}
 */
function resets() {
    //alert('hii');
    //alert(Zikula.Config.baseURL);
    var lock = getCookie('lock_cookie');

    setCookie('shop_cookie', '', -1);
    setCookie('country_cookie', '', -1);
    setCookie('category_cookie', '', -1);

    setCookie('shop_cookie', '', -1);
    setCookie('region_cookie', '', -1);
    setCookie('city_cookie', '', -1);
    setCookie('area_cookie', '', -1);
    setCookie('areaname_cookie', '', -1);
    setCookie('cityname_cookie', '', -1);
    setCookie('regionname_cookie', '', -1);
    setCookie('search_cookie', '', -1);
    setCookie('categoryName_cookie', '', -1);

    if (!lock) {
        setCookie('branch_cookie', '', -1);
        setCookie('affiliate_cookie', '', -1);
    }
    jQuery(".MapDivTxt").html(Zikula.__('Select City', 'module_zselex_js'));
    // if ( jQuery( "#myDiv" ).length )
    // alert(jQuery("#default_country_id").val());
    jQuery("#hcountry").val(jQuery("#default_country_id").val());
    // jQuery('#hcountry').val() = document.getElementById('default_country_id').value;
    jQuery("#hregion").val('');
    jQuery("#hcity").val('');
    jQuery("#harea").val('');
    jQuery("#hcategory").val('');

    jQuery("#hsearch").val(Zikula.__('search for...', 'module_zselex_js'));
    jQuery("#hcountryname").val(jQuery("#default_country_name").val());
    jQuery("#hregionname").val('');
    jQuery("#hcity_name").val('');
    // jQuery("#searchfield").val(Zikula.__('search for...', 'module_zselex_js'));
    jQuery("#searchfield").val('');
    jQuery("#hshop").val('');
    jQuery("#hareaname").val('');
    if (!lock) {
        jQuery("#aff_id").val('');
        jQuery("#hbranch").val('');
    }

    if (jQuery("#level").length) {
        jQuery("#level").val('');
    }
    if (jQuery("#setRegion").length) {
        jQuery("#setRegion").html('');

    }
    if (jQuery("#regionCities").length) {
        jQuery("#regionCities").html('');
    }
    if (jQuery("#SelectedRegion").length) {
        jQuery("#SelectedRegion").html('');
    }
    if (jQuery("#SelectedCity").length) {
        jQuery("#SelectedCity").html('');
    }
    jQuery("#region-div").html('<select id="region-combo" name="region_id" size="1" class="chosen-select-search form-control"><option value="">' + Zikula.__('search region', 'module_zselex_js') + '</option></select>');
    jQuery("#region-combo").chosen({width: "100%"});

    jQuery("#city-div").html('<select id="city-combo1" name="city_id" size="1" class="chosen-select-search form-control"><option value="">' + Zikula.__('search city', 'module_zselex_js') + '</option></select>');
    jQuery("#city-combo1").chosen({width: "100%"});

    jQuery("#cat-div").html('<select id="cat-combo" name="category_id" size="1" class="chosen-select-search form-control"><option value="">' + Zikula.__('search category', 'module_zselex_js') + '</option></select>');
    jQuery("#cat-combo").chosen({width: "100%"});

    jQuery("#area-div").html('<select id="area-combo" name="area_id" size="1" class="chosen-select-search form-control"><option value="">' + Zikula.__('search area', 'module_zselex_js') + '</option></select>');

    // getAllCountry();
    getRegionsAll();
    //  window.setTimeout(getAllCountry(), 0);
    getAllCat();
    // window.setTimeout(getAllCat(), 0);
    getCityListAll();
    getAreaListAll();
    //window.setTimeout(getAreaListAll(), 0);

    if (jQuery("#slideshow").length) {

        jQuery("#old_event_id").val('');
        slideSwitch();
        // window.setTimeout(slideSwitch(), 0);
    }

    if (jQuery("#upcommingevents").length) {
        getupcommingEvents("reset");
        // window.setTimeout(getupcommingEvents("reset"), 0);
    }

    if (jQuery("#specialdeal_block").length) {
        getSpecialDeals();
        // window.setTimeout(getSpecialDeals(), 0);
    }

    if (jQuery("#pageData").length) {
        eventFirstLoad = true;
        jQuery("#pageData").html('');
        showEvents('0');
        // window.setTimeout(showEvents('0'), 0);
    }

    if (jQuery("#newShopBlock").length) {
        getNewShops();
        // window.setTimeout(getNewShops(), 0);
    }


    jQuery('.BrudcomeTree').html('');
    //jQuery("ul.BrudcomeTree").css("padding", "0px");
    resetAutocomplete();

    var homepage = Zikula.Config.baseURL;
    // var curr_url = window.location.href;
    var curr_url = window.location.href.split('?')[0];
    if (homepage != curr_url) {
         window.location = Zikula.Config.baseURL;
    }
    if (history && history.pushState) {
         history.pushState(null, null, Zikula.Config.baseURL);
    }
}
function resets1() {
    setCookie('shop_cookie', '', -1);
    setCookie('country_cookie', '', -1);
    setCookie('category_cookie', '', -1);
    setCookie('branch_cookie', '', -1);
    setCookie('shop_cookie', '', -1);
    setCookie('region_cookie', '', -1);
    setCookie('city_cookie', '', -1);
    setCookie('area_cookie', '', -1);
    setCookie('areaname_cookie', '', -1);
    setCookie('cityname_cookie', '', -1);
    setCookie('regionname_cookie', '', -1);
    setCookie('search_cookie', '', -1);
    setCookie('categoryName_cookie', '', -1);
    jQuery(".MapDivTxt").html(Zikula.__('Select City', 'module_zselex_js'));
    // if ( jQuery( "#myDiv" ).length )
    // alert(jQuery("#default_country_id").val());
    jQuery("#hcountry").val(jQuery("#default_country_id").val());
    // jQuery('#hcountry').val() = document.getElementById('default_country_id').value;
    jQuery("#hregion").val('');
    jQuery("#hcity").val('');
    jQuery("#harea").val('');
    jQuery("#hcategory").val('');
    jQuery("#hbranch").val('');
    jQuery("#hsearch").val(Zikula.__('search for...', 'module_zselex_js'));
    jQuery("#hcountryname").val(jQuery("#default_country_name").val());
    jQuery("#hregionname").val('');
    jQuery("#hcity_name").val('');
    jQuery("#searchfield").val(Zikula.__('search for...', 'module_zselex_js'));
    jQuery("#hshop").val('');
    jQuery("#hareaname").val('');

    if (jQuery("#level").length) {
        jQuery("#level").val('');
    }
    if (jQuery("#setRegion").length) {
        jQuery("#setRegion").html('');

    }
    if (jQuery("#regionCities").length) {
        jQuery("#regionCities").html('');
    }
    if (jQuery("#SelectedRegion").length) {
        jQuery("#SelectedRegion").html('');
    }
    if (jQuery("#SelectedCity").length) {
        jQuery("#SelectedCity").html('');
    }
    jQuery("#cat-div").html('<select id="cat-combo" name="category_id" size="1" ><option value="">' + Zikula.__('search category', 'module_zselex_js') + '</option></select>');
    if (currentTheme() != 'CityPilotresponsive') {
        jQuery("#cat-combo").ZselexCombo({emptyText: Zikula.__('search category', 'module_zselex_js')});
    }
    jQuery("#area-div").html('<select id="area-combo" name="area_id" size="1"><option value="">' + Zikula.__('search area', 'module_zselex_js') + '</option></select>');
    if (currentTheme() != 'CityPilotresponsive') {
        jQuery("#area-combo").ZselexCombo({emptyText: Zikula.__('search area', 'module_zselex_js')});
    }
    getAllCountry();
    getAllCat();
    getAreaListAll();

    if (jQuery("#upcommingevents").length) {
        getupcommingEvents("reset");
    }

    if (jQuery("#specialdeal_block").length) {
        getSpecialDeals();
    }

    if (jQuery("#pageData").length) {
        jQuery("#pageData").html('');
        showEvents('0');
    }

    if (jQuery("#newShopBlock").length) {
        getNewShops();
    }

    if (jQuery("#slideshow").length) {

        jQuery("#old_event_id").val('');
        slideSwitch();
    }
    jQuery('.BrudcomeTree').html('');
    jQuery("ul.BrudcomeTree").css("padding", "0px");
}
function myFunctionresetResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    load();
    getShopDetails(document.getElementById('hshop').value)
    getAdDetails();
    getShopAd();
}
function searchvalue(val) {
    //alert('hii');
    jQuery('#hsearch').val(val);
    setCookie('search_cookie', val, '1');
}
/*
 jQuery(".ui-menu-item").live('click', function (event) {
 var current_theme = jQuery('#current_theme').val();
 jQuery('#hsearch').val(jQuery(this).find("a").text())
 
 setCookie('search_cookie', jQuery(this).find("a").text(), '1');
 jQuery("#bc_search").remove();
 jQuery('.BrudcomeTree').append('<li id=bc_search><a href="#">' + jQuery(this).find("a").text() + '</a><img class="bc_button" src="' + Zikula.Config.baseURL + 'themes/' + current_theme + '/images/black_close.png" onclick=closeBcLi("bc_search")></li>');
 displayBlocks();
 });
 //jQuery("#searchfield").live("focusout keydown", function (e) {
 jQuery("#searchfield").on("focusout keydown", function (e) {
 var current_theme = jQuery('#current_theme').val();
 if (e.keyCode == 13) {
 jQuery('#hsearch').val(this.value)
 
 setCookie('search_cookie', this.value, '1');
 jQuery("#bc_search").remove();
 if (this.value != '') {
 jQuery('.BrudcomeTree').append('<li id=bc_search><a href="#">' + this.value + '</a><img class="bc_button" src="' + Zikula.Config.baseURL + 'themes/' + current_theme + '/images/black_close.png" onclick=closeBcLi("bc_search")></li>');
 displayBlocks();
 }
 }
 });
 */

/*
 window.onkeyup = function (event) {
 if (event.keyCode == 13) {
 //alert('enter');
 if (jQuery('#hsearch').val() != '') {
 displayBlocks();
 }
 }
 }*/


function special_deal_event_block() {
    // jQuery("#specialdeal_block_Events").css("border", "0");
    jQuery("#specialdeal_block_Events").html('<img src=' + Zikula.Config.baseURL + 'modules/ZSELEX/images/loading.gif style=padding-left:20px>');
    // jQuery("#specialdeal_block_Events").html(Zikula.__('Loading...', 'module_zselex_js'));
    if (document.getElementById('level')) {
        var level = document.getElementById('level').value;
    }
    var hcountry = jQuery('#hcountry').val();
    var hregion = jQuery('#hregion').val();
    var hcity = jQuery('#hcity').val();
    var harea = jQuery('#harea').val();
    var hshop = jQuery('#hshop').val();
    var hcategory = jQuery('#hcategory').val();
    var hbranch = jQuery('#hbranch').val();
    var hsearch = jQuery('#hsearch').val();
    if (hsearch == Zikula.__('search for...', 'module_zselex_js')) {
        hsearch = '';
    }
    var aff_id = jQuery('#aff_id').val();
    var getShopCookie;
    var getRegionCookie;
    var getCityCookie;
    var getAreaCookie;
    var getCategoryCookie;
    var getBranchCookie;
    var getSearchCookie;
    if (hshop > 0) {
        getShopCookie = hshop;
    } else {
        getShopCookie = getCookie('shop_cookie');
    }
    if (hregion > 0) {
        getRegionCookie = hregion;
    } else {
        getRegionCookie = getCookie('region_cookie');
    }
    if (hcity > 0) {
        getCityCookie = hcity;
    } else {
        getCityCookie = getCookie('city_cookie');
    }
    if (harea > 0) {
        getAreaCookie = harea;
    } else {
        getAreaCookie = getCookie('area_cookie');
    }
    if (hcategory > 0) {
        getCategoryCookie = hcategory;
    } else {
        getCategoryCookie = getCookie('category_cookie');
    }
    if (hbranch > 0) {
        getBranchCookie = hbranch;
    } else {
        getBranchCookie = getCookie('branch_cookie');
    }
    if (hsearch != '') {
        getSearchCookie = hsearch;
    } else {
        getSearchCookie = getCookie('search_cookie');
    }
    var hregion = getRegionCookie;
    var hcity = getCityCookie;
    var harea = getAreaCookie;
    var hshop = getShopCookie;
    var hcategory = getCategoryCookie;
    var hbranch = getBranchCookie;
    var hsearch = getSearchCookie;
    var amount = document.getElementById('sd_amount').value;
    var limit = document.getElementById('event_amount').value;
    var adtype;
    adtype = document.getElementById('sd_adtype').value;
    var limitshops = '';
    if (document.getElementById('shopadtype')) {
        var shopadtype = document.getElementById('shopadtype').value;
    }
    var lang = jQuery('#curr_lang').val();
    var params = '';
    if (aff_id != '') {
        params += "&aff_id=" + aff_id;
    }
    var pars = "module=ZSELEX&type=ajax&func=special_deal_event_block&lang=" + lang + "&shop_id=" + hshop + "&country_id=" + hcountry + "&region_id=" + hregion + "&city_id=" + hcity + "&area_id=" + harea + "&category_id=" + hcategory + "&branch_id=" + hbranch + "&adtype=" + adtype + "&hsearch=" + hsearch + "&amount=" + amount + "&limitshops=" + limitshops + "&level=" + level + "&limit=" + limit + params;
    //alert(pars);
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, async: true, onComplete: special_deal_event_blockResponses});
}
function special_deal_event_blockResponses(req) {
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    var count;
    var json = pndejsonize(req.responseText);
    var eventsCount = json.events.eventscount;
    var events = json.events.events;
    count = eventsCount;
    if (document.getElementById('specialdeal_block_Events')) {
        $("specialdeal_block_Events").update(events);
        //  jQuery("#specialdeal_block_Events").css("border", "1px solid #E7E7E7");
    }
}
function getSpecialDeals() {
    getSpecialBlockProductHighAd();
    getSpecialBlockProductMidAd();
    getSpecialBlockProductLowAd()
    // getSepecialDealArticleAd();
    special_deal_event_block();
}
function getSpecialDealsResponses(req) {
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    jQuery("#specialdeal_block_loader").html('');
    var count;
    var json = pndejsonize(req.responseText);
    var eventsCount = json.events.eventscount;
    var events = json.events.events;
    count = eventsCount;
    if (count > 0) {
        if (document.getElementById('specialdeal_block')) {
            jQuery("#specialdeal_block_Events").html(events);
        }
    } else {
    }
}
function getNewShops() {
    jQuery("#newShopBlock").html('<img src=' + Zikula.Config.baseURL + 'modules/ZSELEX/images/loading.gif style=padding-left:20px>');
    // jQuery("#newShopBlock").html('Loading...');

    //alert(hsearch);
    //alert(hcategory);
    var searchItem = new searchObjects();

    var hregion = searchItem.hregion;
    var hcity = searchItem.hcity;
    var harea = searchItem.harea;
    var hshop = searchItem.hshop;
    var hcategory = searchItem.hcategory;
    var hbranch = searchItem.hbranch;
    var hsearch = searchItem.hsearch;
    var hcountry = searchItem.hcountry;
    var shopfrontorder = jQuery('#shopfrontorder').val();
    var shopfrontlimit = jQuery('#shopfrontlimit').val();

    //alert(hcategory);


    var params = '';
    if (hshop > 0) {
        params += "&shop_id=" + hshop;
    }
    if (hcountry > 0) {
        params += "&country_id=" + hcountry;
    }
    if (hregion > 0) {
        params += "&region_id=" + hregion;
    }
    if (hcity > 0) {
        params += "&city_id=" + hcity;
    }
    if (harea > 0) {
        params += "&area_id=" + harea;
    }
    if (hcategory > 0) {
        params += "&category_id=" + hcategory;
    }
    if (hbranch > 0) {
        params += "&branch_id=" + hbranch;
    }
    if (hsearch != '') {
        params += "&search=" + hsearch;

    }
    if (shopfrontorder != '') {
        params += "&shopfrontorder=" + shopfrontorder;
    }
    if (shopfrontlimit != '') {
        params += "&shopfrontlimit=" + shopfrontlimit;
    }

    var aff_id = searchItem.aff_id;
    if (aff_id != '') {
        params += "&aff_id=" + aff_id;
    }

    var pars = "module=ZSELEX&type=ajax&func=getNewShops" + params;
    // alert(pars);
    //jQuery("#newShopBlock").load("index.php?" + pars);
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, async: true, onComplete: getNewShopsResponses});
}
function getNewShopsResponses(req) {
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    var json = pndejsonize(req.responseText);
    var count = json.count;

    // alert(json.data);
    $("newShopBlock").update(json.data);
    if (count < 1) {
        // jQuery(".image_list").css("padding", "0");
        jQuery('.image_list').attr('style', 'padding: 0px !important');
        jQuery(".image_list").css("text-align", "center");
    } else {
        jQuery('.image_list').attr('style', 'padding: 15px 0 !important');
        jQuery(".image_list").css("text-align", "");
    }
}
function getSepecialDealArticleAd() {
    jQuery("#specialdeal_block_Articles").css("border", "0");
    jQuery("#specialdeal_block_Articles").html('<img src=' + Zikula.Config.baseURL + 'modules/ZSELEX/images/loading.gif style=padding-left:20px>');
    //jQuery("#specialdeal_block_Articles").html(Zikula.__('Loading...', 'module_zselex_js'));
    if (jQuery('#level').length) {
        var level = jQuery('#level').val();
    }
    var searchItem = new searchObjects();

    var hregion = searchItem.hregion;
    var hcity = searchItem.hcity;
    var harea = searchItem.harea;
    var hshop = searchItem.hshop;
    var hcategory = searchItem.hcategory;
    var hbranch = searchItem.hbranch;
    var hsearch = searchItem.hsearch;
    var hcountry = searchItem.hcountry;

    if (jQuery('#shopadtype').length) {
        var shopadtype = jQuery('#shopadtype').val();
    }
    var pars = "module=ZSELEX&type=ajax&func=getSpecialBlockArticleAd&shop_id=" + hshop + "&country_id=" + hcountry + "&region_id=" + hregion + "&city_id=" + hcity + "&area_id=" + harea + "&category_id=" + hcategory + "&branch_id=" + hbranch + "&hsearch=" + hsearch + "&level=" + level + "&adtype=" + shopadtype;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, async: true, onComplete: getSepecialDealArticleAdResponses});
}
function getSepecialDealArticleAdResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    var articles;
    var count;
    var json = pndejsonize(req.responseText);
    count = json.articlecount
    articles = json.articles;
    var value;
    $("specialdeal_block_Articles").update(json.data);
    jQuery("#specialdeal_block_Articles").css("border", " 1px solid #E7E7E7");
}
function cc() {
    //  alert('hiiiii');
}
function getSpecialBlockProductHighAd1() {
    // alert('high ad');
    jQuery("#specialdeal_block_products").html('<img src=' + Zikula.Config.baseURL + 'modules/ZSELEX/images/loading.gif style=padding-left:20px>');
    //jQuery("#specialdeal_block_products").html(Zikula.__('Loading...', 'module_zselex_js'));
//exit();
    var hcountry = jQuery('#hcountry').val();
    var hregion = jQuery('#hregion').val();
    var hcity = jQuery('#hcity').val();
    var harea = jQuery('#harea').val();
    var hshop = jQuery('#hshop').val();
    var hcategory = jQuery('#hcategory').val();
    var hbranch = jQuery('#hbranch').val();
    var hsearch = jQuery('#hsearch').val();
    var getShopCookie;
    var getRegionCookie;
    var getCityCookie;
    var getAreaCookie;
    var getCategoryCookie;
    var getBranchCookie;
    var getSearchCookie;
    if (hshop > 0) {
        getShopCookie = hshop;
    } else {
        getShopCookie = getCookie('shop_cookie');
    }
    if (hregion > 0) {
        getRegionCookie = hregion;
    } else {
        getRegionCookie = getCookie('region_cookie');
    }
    if (hcity > 0) {
        getCityCookie = hcity;
    } else {
        getCityCookie = getCookie('city_cookie');
    }
    if (harea > 0) {
        getAreaCookie = harea;
    } else {
        getAreaCookie = getCookie('area_cookie');
    }
    if (hcategory > 0) {
        getCategoryCookie = hcategory;
    } else {
        getCategoryCookie = getCookie('category_cookie');
    }
    if (hbranch > 0) {
        getBranchCookie = hbranch;
    } else {
        getBranchCookie = getCookie('branch_cookie');
    }
    if (hsearch != '') {
        getSearchCookie = hsearch;
    } else {
        getSearchCookie = getCookie('search_cookie');
    }
    var hregion = getRegionCookie;
    var hcity = getCityCookie;
    var harea = getAreaCookie;
    var hshop = getShopCookie;
    var hcategory = getCategoryCookie;
    var hbranch = getBranchCookie;
    var hsearch = getSearchCookie;
    var limit = jQuery('#highad_amount').val();
    var params = '';
    if (hshop > 0) {
        params += "&shop_id=" + hshop;
    }
    if (hcountry > 0) {
        params += "&country_id=" + hcountry;
    }
    if (hregion > 0) {
        params += "&region_id=" + hregion;
    }
    if (hcity > 0) {
        params += "&city_id=" + hcity;
    }
    if (harea > 0) {
        params += "&area_id=" + harea;
    }
    if (hcategory > 0) {
        params += "&category_id=" + hcategory;
    }
    if (hbranch > 0) {
        params += "&branch_id=" + hbranch;
    }
    if (hsearch != '') {
        params += "&hsearch=" + hsearch;
    }
    if (limit > 0) {
        params += "&limit=" + limit;
    }

    //alert(params);
    // var pars = "module=ZSELEX&type=ajax&func=getSpecialBlockProductAd&shop_id=" + hshop + "&country_id=" + hcountry + "&region_id=" + hregion + "&city_id=" + hcity + "&area_id=" + harea + "&category_id=" + hcategory + "&branch_id=" + hbranch + "&hsearch=" + hsearch + "&limit=" + limit;
    var pars = "module=ZSELEX&type=ajax&func=getSpecialBlockProductAd" + params;
//alert(pars);
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, async: true, onComplete: getSpecialBlockProductHighAdResponses});
}

function getSpecialBlockProductHighAd() {
    // alert('high ad');
    jQuery("#specialdeal_block_products").html('<img src=' + Zikula.Config.baseURL + 'modules/ZSELEX/images/loading.gif style=padding-left:20px>');

//exit();

    var searchItem = new searchObjects();

    var hregion = searchItem.hregion;
    var hcity = searchItem.hcity;
    var harea = searchItem.harea;
    var hshop = searchItem.hshop;
    var hcategory = searchItem.hcategory;
    var hbranch = searchItem.hbranch;
    var hsearch = searchItem.hsearch;
    var hcountry = searchItem.hcountry;
    var aff_id = searchItem.aff_id;
    var limit = jQuery('#highad_amount').val();
    var params = '';
    if (hshop > 0) {
        params += "&shop_id=" + hshop;
    }
    if (hcountry > 0) {
        params += "&country_id=" + hcountry;
    }
    if (hregion > 0) {
        params += "&region_id=" + hregion;
    }
    if (hcity > 0) {
        params += "&city_id=" + hcity;
    }
    if (harea > 0) {
        params += "&area_id=" + harea;
    }
    if (hcategory > 0) {
        params += "&category_id=" + hcategory;
    }
    if (hbranch > 0) {
        params += "&branch_id=" + hbranch;
    }
    if (hsearch != '') {
        params += "&hsearch=" + hsearch;
    }
    if (limit > 0) {
        params += "&limit=" + limit;
    }

    if (aff_id != '') {
        /*
         }
         var aff_id_obj = JSON.parse(aff_id);
         if(aff_id_obj!=null){
         for (var i = 0; i < aff_id_obj.length; i++) {
         params += "&aff_id[]=" + aff_id_obj[i];
         }
         }
         */
        params += "&aff_id=" + aff_id;
    }


    var lang = jQuery('#curr_lang').val();
    params += "&lang=" + lang;

    //alert(params);
    // var pars = "module=ZSELEX&type=ajax&func=getSpecialBlockProductAd&shop_id=" + hshop + "&country_id=" + hcountry + "&region_id=" + hregion + "&city_id=" + hcity + "&area_id=" + harea + "&category_id=" + hcategory + "&branch_id=" + hbranch + "&hsearch=" + hsearch + "&limit=" + limit;
    var pars = "module=ZSELEX&type=ajax&func=getSpecialBlockProductAd" + params;
    //alert(pars);
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, async: true, onComplete: getSpecialBlockProductHighAdResponses});
    // jQuery("#specialdeal_block_products").load("index.php?" + pars);
    // jQuery("#specialdeal_block_products").load("index.php?module=ZSELEX&type=ajax&func=getSpecialBlockProductAd", {"shop_id": hshop, "country_id": hcountry, "region_id": hregion, "city_id": hcity, "area_id": harea, "category_id": hcategory, "branch_id": hbranch, "hsearch": hsearch, "limit": limit});

}
function getSpecialBlockProductHighAdResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    var json = pndejsonize(req.responseText);
    $("specialdeal_block_products").update(json.data);
}
function getSpecialBlockProductMidAd() {
    // jQuery("#specialdeal_block_products_mid").css("border", "0px");
    jQuery("#specialdeal_block_products_mid").html('<img src=' + Zikula.Config.baseURL + 'modules/ZSELEX/images/loading.gif style=padding-left:20px>');
    // exit();
    // jQuery("#specialdeal_block_products_mid").html(Zikula.__('Loading...', 'module_zselex_js'));

    var searchItem = new searchObjects();

    var hregion = searchItem.hregion;
    var hcity = searchItem.hcity;
    var harea = searchItem.harea;
    var hshop = searchItem.hshop;
    var hcategory = searchItem.hcategory;
    var hbranch = searchItem.hbranch;
    var hsearch = searchItem.hsearch;
    var hcountry = searchItem.hcountry;

    var limit = jQuery('#midad_amount').val();

    var params = '';
    if (hshop > 0) {
        params += "&shop_id=" + hshop;
    }
    if (hcountry > 0) {
        params += "&country_id=" + hcountry;
    }
    if (hregion > 0) {
        params += "&region_id=" + hregion;
    }
    if (hcity > 0) {
        params += "&city_id=" + hcity;
    }
    if (harea > 0) {
        params += "&area_id=" + harea;
    }
    if (hcategory > 0) {
        params += "&category_id=" + hcategory;
    }
    if (hbranch > 0) {
        params += "&branch_id=" + hbranch;
    }
    if (hsearch != '') {
        params += "&hsearch=" + hsearch;
    }
    if (limit > 0) {
        params += "&limit=" + limit;
    }

    var aff_id = searchItem.aff_id;
    if (aff_id != '') {
        params += "&aff_id=" + aff_id;
    }

    var lang = jQuery('#curr_lang').val();
    params += "&lang=" + lang;


    var pars = "module=ZSELEX&type=ajax&func=getSpecialBlockProductMidAd" + params;
    // alert(pars);
    //jQuery("#specialdeal_block_products_mid").load("index.php?" + pars);
    // jQuery("#specialdeal_block_products_mid").css("border", "1px solid #E7E7E7 !important");
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, async: true, onComplete: getSpecialBlockProductMidAdResponses});
}
function getSpecialBlockProductMidAdResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    var json = pndejsonize(req.responseText);
    $("specialdeal_block_products_mid").update(json.data);
    // jQuery("#specialdeal_block_products_mid").css("border", "1px solid #E7E7E7");
}
function getSpecialBlockProductLowAd() {
    // jQuery("#specialdeal_block_products_low").css("border", "0px");
    // jQuery("#specialdeal_block_products_low").html('<img src=' + Zikula.Config.baseURL + 'modules/ZSELEX/images/loading.gif style=padding-left:20px>');
    jQuery("#specialdeal_block_products_low").html('<img src=' + Zikula.Config.baseURL + 'modules/ZSELEX/images/loading.gif style=padding-left:20px>');
    // exit();
    // jQuery("#specialdeal_block_products_low").html(Zikula.__('Loading...', 'module_zselex_js'));


    var searchItem = new searchObjects();

    var hregion = searchItem.hregion;
    var hcity = searchItem.hcity;
    var harea = searchItem.harea;
    var hshop = searchItem.hshop;
    var hcategory = searchItem.hcategory;
    var hbranch = searchItem.hbranch;
    var hsearch = searchItem.hsearch;
    var hcountry = searchItem.hcountry;
    var limit = jQuery('#lowad_amount').val();


    var params = '';
    if (hshop > 0) {
        params += "&shop_id=" + hshop;
    }
    if (hcountry > 0) {
        params += "&country_id=" + hcountry;
    }
    if (hregion > 0) {
        params += "&region_id=" + hregion;
    }
    if (hcity > 0) {
        params += "&city_id=" + hcity;
    }
    if (harea > 0) {
        params += "&area_id=" + harea;
    }
    if (hcategory > 0) {
        params += "&category_id=" + hcategory;
    }
    if (hbranch > 0) {
        params += "&branch_id=" + hbranch;
    }
    if (hsearch != '') {
        params += "&hsearch=" + hsearch;
    }
    if (limit > 0) {
        params += "&limit=" + limit;
    }

    var aff_id = searchItem.aff_id;
    if (aff_id != '') {
        params += "&aff_id=" + aff_id;
    }

    var lang = jQuery('#curr_lang').val();
    params += "&lang=" + lang;

    var pars = "module=ZSELEX&type=ajax&func=getSpecialBlockProductLowAd" + params;
    //alert(pars);

    //  jQuery("#specialdeal_block_products_low").load("index.php?" + pars);
    // jQuery("#specialdeal_block_products_low").css("border", "1px solid #E7E7E7 !important");
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, async: true, onComplete: getSpecialBlockProductLowdResponses});
}
function getSpecialBlockProductLowdResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    var json = pndejsonize(req.responseText);
    $("specialdeal_block_products_low").update(json.data);
    // jQuery("#specialdeal_block_products_low").css("border", "1px solid #E7E7E7");
}
var eventFirstLoad = false;

/**
 * Display Events
 * 
 * @param {int} limit
 * @return {void}
 */
function showEvents(limit) {

    if (eventFirstLoad) {
        jQuery("#pageData").html('<img src=' + Zikula.Config.baseURL + 'modules/ZSELEX/images/loading.gif style=padding-left:229px>');
        //exit();
    } else {
        jQuery(".flash").show();
        jQuery(".flash").fadeIn(400).html('<img src="' + Zikula.Config.baseURL + 'modules/ZSELEX/images/ajax-loading.gif" />');
    }
    if (jQuery('#level').length) {
        var level = jQuery('#level').val();
    }
    var hcountry = jQuery('#hcountry').val();
    var shop_id = jQuery('#shop_id').val();
    var hregion = jQuery('#hregion').val();
    var hcity = jQuery('#hcity').val();
    var harea = jQuery('#harea').val();
    var hshop = jQuery('#hshop').val();
    var hcategory = jQuery('#hcategory').val();
    var hbranch = jQuery('#hbranch').val();
    var hsearch = jQuery('#hsearch').val();
    var getShopCookie;
    var getRegionCookie;
    var getCityCookie;
    var getAreaCookie;
    var getCategoryCookie;
    var getBranchCookie;
    var getSearchCookie;
    if (hshop > 0) {
        getShopCookie = hshop;
    } else {
        getShopCookie = getCookie('shop_cookie');
    }
    if (hregion > 0) {
        getRegionCookie = hregion;
    } else {
        getRegionCookie = getCookie('region_cookie');
    }
    if (hcity > 0) {
        getCityCookie = hcity;
    } else {
        getCityCookie = getCookie('city_cookie');
    }
    if (harea > 0) {
        getAreaCookie = harea;
    } else {
        getAreaCookie = getCookie('area_cookie');
    }
    if (hcategory > 0) {
        getCategoryCookie = hcategory;
    } else {
        getCategoryCookie = getCookie('category_cookie');
    }
    if (hbranch > 0) {
        getBranchCookie = hbranch;
    } else {
        getBranchCookie = getCookie('branch_cookie');
    }
    if (hsearch != '') {
        getSearchCookie = hsearch;
    } else {
        getSearchCookie = getCookie('search_cookie');
    }
    var hregion = getRegionCookie;
    var hcity = getCityCookie;
    var harea = getAreaCookie;
    if (shop_id != '') {
        var hshop = shop_id;
    } else {
        var hshop = getShopCookie;
    }
    var hcategory = getCategoryCookie;
    var hbranch = getBranchCookie;
    var hsearch = getSearchCookie;
    var eventlimit = 10;
    var startlimit = limit;
    // alert(startlimit);
    // alert(eventlimit);
    var lang = jQuery('#curr_lang').val();
    var pars = "module=ZSELEX&type=ajax&func=showEvents&lang=" + lang + "&shop_id=" + hshop + "&country_id=" + hcountry + "&region_id=" + hregion + "&city_id=" + hcity + "&area_id=" + harea + "&category_id=" + hcategory + "&branch_id=" + hbranch + "&hsearch=" + hsearch + "&level=" + level + "&eventlimit=" + eventlimit + "&startlimit=" + startlimit;
    // alert(pars);
    var myAjax = new Ajax.Request("ajax.php", {
        method: 'get',
        parameters: pars,
        async: true,
        onComplete: showEventsResponses});
    return false;
}
function showEventsResponses(req) {
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    //alert('helloo'); exit();
    var json = pndejsonize(req.responseText);
    // alert(json.data); exit()
    if (eventFirstLoad) {
        jQuery("#pageData").html('');
    }
    eventFirstLoad = false;
    jQuery(".load_more_link").addClass('noneLink');
    jQuery("#pageData").append(json.data);
}


function getupcommingEvents(value) {
    //alert('upEvents');
    jQuery("#upcommingevents").html('<img src=' + Zikula.Config.baseURL + 'modules/ZSELEX/images/loading.gif style=padding-left:20px>');
    //jQuery("#upcommingevents").html(Zikula.__('Loading...', 'module_zselex_js'));
    var level = jQuery('#level').val();
    var searchItem = new searchObjects();

    var hregion = searchItem.hregion;
    var hcity = searchItem.hcity;
    var harea = searchItem.harea;
    var hshop = searchItem.hshop;
    var hcategory = searchItem.hcategory;
    var hbranch = searchItem.hbranch;
    var hsearch = searchItem.hsearch;
    var hcountry = searchItem.hcountry;
    var eventlimit = document.getElementById('upcommingeventlimit').value;
    var func;
    func = 'getupcommingEvents';


    var params = '';
    params += "&eventlimit=" + eventlimit;
    params += "&level=" + level;
    params += "&reset=" + value;
    if (hshop > 0) {
        params += "&shop_id=" + hshop;
    }
    if (hcountry > 0) {
        params += "&country_id=" + hcountry;
    }
    if (hregion > 0) {
        params += "&region_id=" + hregion;
    }
    if (hcity > 0) {
        params += "&city_id=" + hcity;
    }
    if (harea > 0) {
        params += "&area_id=" + harea;
    }
    if (hcategory > 0) {
        params += "&category_id=" + hcategory;
    }
    if (hbranch > 0) {
        params += "&branch_id=" + hbranch;
    }
    if (hsearch != '') {
        params += "&hsearch=" + hsearch;
    }

    var aff_id = searchItem.aff_id;
    if (aff_id != '') {
        params += "&aff_id=" + aff_id;
    }

    var lang = jQuery('#curr_lang').val();
    params += "&lang=" + lang;

    var pars = "module=ZSELEX&type=ajax&func=" + func + params;
    // alert(pars);
    // jQuery("#upcommingevents").load("index.php?" + pars);
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, async: true, onComplete: getupcommingEventsResponses});
}
function getupcommingEventsResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    var json = pndejsonize(req.responseText);
    $("upcommingevents").update(json.data);

    if (jQuery('#e_country_id').length) {

        jQuery('#e_country_id').val(jQuery('#hcountry').val());
    }
    if (jQuery('#e_region_id').length) {

        jQuery('#e_region_id').val(jQuery('#hregion').val());
    }
    if (jQuery('#e_city_id').length) {

        jQuery('#e_city_id').val(jQuery('#hcity').val());
    }
    if (jQuery('#e_area_id').length) {

        jQuery('#e_area_id').val(jQuery('#harea').val());
    }
    if (jQuery('#e_shop_id').length) {

        jQuery('#e_shop_id').val(jQuery('#hshop').val());
    }
    if (jQuery('#e_category_id').length) {

        jQuery('#e_category_id').val(jQuery('#hcategory').val());
    }
    if (jQuery('#e_branch_id').length) {
        jQuery('#e_branch_id').val(jQuery('#hbranch').val());

    }
}





/**
 * Search word
 * 
 * @returns {undefined}
 */
function resetAutocomplete() {


    //alert(hsearch);
    //alert('resetAutocomplete');
    var searchItem = new searchObjects();

    var hregion = searchItem.hregion;
    var hcity = searchItem.hcity;
    var harea = searchItem.harea;
    var hshop = searchItem.hshop;
    var hcategory = searchItem.hcategory;
    var hbranch = searchItem.hbranch;
    var hsearch = searchItem.hsearch;
    var hcountry = searchItem.hcountry;
    // var limit = jQuery('#highad_amount').val();
    currFunc = jQuery('#curr_func').val();
    // alert(currFunc); exit();
    var params = '';
    if (hshop > 0) {
        params += "&shop_id=" + hshop;
    }
    if (hcountry > 0) {
        params += "&country_id=" + hcountry;
    }
    if (hregion > 0) {
        params += "&region_id=" + hregion;
    }
    if (hcity > 0) {
        params += "&city_id=" + hcity;
    }
    if (harea > 0) {
        params += "&area_id=" + harea;
    }
    if (hcategory > 0) {
        params += "&category_id=" + hcategory;
    }
    if (hbranch > 0) {
        params += "&branch_id=" + hbranch;
    }
    // alert(currFunc);
    params += "&curr_func=" + currFunc;


    var frm_source = document.location.pnbaseURL + "index.php?module=ZSELEX&type=ajax&func=searchres" + params;
    //alert(frm_source);
    jQuery('#searchfield').autocomplete(
            {
                source: frm_source,
                minLength: 1,
                delay: 0,
                select: function (event, ui) {
                    //alert("Selected ");
                    jQuery('#hsearch').val(ui.item.value);
                    setCookie('search_cookie', ui.item.value, '1');
                    jQuery("#bc_search").remove();
                    jQuery('.BrudcomeTree').append('<li id=bc_search><a href="#">' + ui.item.value + '</a><i class="fa fa-times remove-icon" onclick=closeBcLi("bc_search")></i></li>');
                    displayBlocks();
                }
            });
}



function searchObjects() {
    var hcountry = jQuery('#hcountry').val();
    var hregion = jQuery('#hregion').val();
    var hcity = jQuery('#hcity').val();
    var harea = jQuery('#harea').val();
    var hshop = jQuery('#hshop').val();
    var hcategory = jQuery('#hcategory').val();
    var hbranch = jQuery('#hbranch').val();
    var hsearch = jQuery('#hsearch').val();
    var aff_id = jQuery('#aff_id').val();
    // alert(aff_id); exit();

    if (hsearch == Zikula.__('search for...', 'module_zselex_js')) {
        hsearch = '';
    }
    //alert(hsearch);
    var getShopCookie;
    var getRegionCookie;
    var getCityCookie;
    var getAreaCookie;
    var getCategoryCookie;
    var getBranchCookie;
    var getSearchCookie;
    if (hshop > 0) {
        getShopCookie = hshop;
    } else {
        getShopCookie = getCookie('shop_cookie');
    }
    if (hregion > 0) {
        getRegionCookie = hregion;
    } else {
        getRegionCookie = getCookie('region_cookie');
    }
    if (hcity > 0) {
        getCityCookie = hcity;
    } else {
        getCityCookie = getCookie('city_cookie');
    }
    if (harea > 0) {
        getAreaCookie = harea;
    } else {
        getAreaCookie = getCookie('area_cookie');
    }
    if (hcategory > 0) {
        getCategoryCookie = hcategory;
    } else {
        getCategoryCookie = getCookie('category_cookie');
    }
    if (hbranch > 0) {
        getBranchCookie = hbranch;
    } else {
        getBranchCookie = getCookie('branch_cookie');
    }
    if (hsearch != '') {
        getSearchCookie = hsearch;
    } else {
        getSearchCookie = getCookie('search_cookie');
    }
    var hregion = getRegionCookie;
    var hcity = getCityCookie;
    var harea = getAreaCookie;
    var hshop = getShopCookie;
    var hcategory = getCategoryCookie;
    var hbranch = getBranchCookie;
    var hsearch = getSearchCookie;

    var items = {
        hcountry: hcountry,
        hregion: getRegionCookie,
        hcity: getCityCookie,
        harea: getAreaCookie,
        hshop: getShopCookie,
        hcategory: getCategoryCookie,
        hbranch: getBranchCookie,
        hsearch: getSearchCookie,
        aff_id: aff_id,
    };

    return items; // objects
}

    
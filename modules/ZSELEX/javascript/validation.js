function validate(id) {
    if (id == '1') {
        document.getElementById('shopdetails').style.display = 'none';
        document.getElementById('ecom').style.display = 'none';
        document.getElementById('pluginsDis').style.display = 'none';
        document.getElementById('shops').style.display = 'none';
        document.getElementById('countries').style.display = 'none';
        document.getElementById('branches').style.display = 'none';
        document.getElementById('zshop').style.display = 'none';
    }
    else if (id == '2') {
        document.getElementById('shopdetails').style.display = 'block';
        document.getElementById('ecom').style.display = 'block';
        document.getElementById('pluginsDis').style.display = 'block';
        document.getElementById('countries').style.display = 'none';
        document.getElementById('regions').style.display = 'none';
        document.getElementById('branches').style.display = 'block';
        document.getElementById('zshop').style.display = 'none';
    }
    else if (id == '3') {
        document.getElementById('shopdetails').style.display = 'none';
        document.getElementById('ecom').style.display = 'none';
        document.getElementById('pluginsDis').style.display = 'none';
        document.getElementById('countries').style.display = 'none';
        document.getElementById('regions').style.display = 'none';
        document.getElementById('branches').style.display = 'none';
        document.getElementById('zshop').style.display = 'none';
    }
    else if (id == '4') {
        document.getElementById('shops').style.display = 'none';
        document.getElementById('shopdetails').style.display = 'none';
        document.getElementById('cats').style.display = 'none';
        document.getElementById('ecom').style.display = 'none';
        document.getElementById('pluginsDis').style.display = 'none';
        document.getElementById('countries').style.display = 'none';
        document.getElementById('regions').style.display = 'none';
        document.getElementById('branches').style.display = 'none';
        document.getElementById('zshop').style.display = 'none';
    }
    else if (id == '12') {
        document.getElementById('shops').style.display = 'none';
        document.getElementById('shopdetails').style.display = 'none';
        document.getElementById('cats').style.display = 'none';
        document.getElementById('ecom').style.display = 'none';
        document.getElementById('pluginsDis').style.display = 'none';
        document.getElementById('regions').style.display = 'none';
        document.getElementById('branches').style.display = 'none';
        document.getElementById('zshop').style.display = 'none';
    }
    else {
        document.getElementById('shops').style.display = 'none';
        document.getElementById('shopdetails').style.display = 'none';
        document.getElementById('cats').style.display = 'none';
        document.getElementById('ecom').style.display = 'none';
        document.getElementById('pluginsDis').style.display = 'none';
        document.getElementById('countries').style.display = 'none';
        document.getElementById('regions').style.display = 'none';
        document.getElementById('branches').style.display = 'none';
        document.getElementById('zshop').style.display = 'none';
    }
}
function shoptype(id) {
    if (id == '1') {
        document.getElementById('zshop').style.display = 'block';
    }
    else {
        document.getElementById('zshop').style.display = 'none';
    }
}
function shoptypes(type) {
    if (type == 'zSHOP') {
        document.getElementById('zshop').style.display = 'block';
    }
    else {
        document.getElementById('zshop').style.display = 'none';
    }
}
function shopenable(id) {
    if (id == '1') {
        document.getElementById('zshop').style.display = 'block';
    }
    else {
        document.getElementById('zshop').style.display = 'none';
    }
}
function shopAdLevel(id) {
    if (id == 'COUNTRY') {
        document.getElementById('parentregion').value = '';
        document.getElementById('parentcity').value = '';
        document.getElementById('parentarea').value = '';
        document.getElementById('countries').style.display = 'block';
        document.getElementById('regions').style.display = 'none';
        document.getElementById('cats').style.display = 'none';
        document.getElementById('area').style.display = 'none';
    }
    else if (id == 'REGION') {
        document.getElementById('parentcountry').value = '';
        document.getElementById('parentcity').value = '';
        document.getElementById('parentarea').value = '';
        document.getElementById('countries').style.display = 'none';
        document.getElementById('regions').style.display = 'block';
        document.getElementById('cats').style.display = 'none';
        document.getElementById('area').style.display = 'none';
    }
    else if (id == 'CITY') {
        document.getElementById('parentcountry').value = '';
        document.getElementById('parentregion').value = '';
        document.getElementById('parentarea').value = '';
        document.getElementById('countries').style.display = 'none';
        document.getElementById('regions').style.display = 'none';
        document.getElementById('cats').style.display = 'block';
        document.getElementById('area').style.display = 'none';
    }
    else if (id == 'AREA') {
        document.getElementById('parentcountry').value = '';
        document.getElementById('parentcity').value = '';
        document.getElementById('parentregion').value = '';
        document.getElementById('countries').style.display = 'none';
        document.getElementById('regions').style.display = 'none';
        document.getElementById('cats').style.display = 'none';
        document.getElementById('area').style.display = 'block';
    }
    else {
        document.getElementById('countries').style.display = 'none';
        document.getElementById('regions').style.display = 'none';
        document.getElementById('cats').style.display = 'none';
        document.getElementById('area').style.display = 'none';
    }
}
function changeParent(id) {
    if (id == '1') {
        document.getElementById('cats').style.display = 'block';
        document.getElementById('shops').style.display = 'none';
        document.getElementById('ads').style.display = 'none';
        document.getElementById('regions').style.display = 'none';
        document.getElementById('countries').style.display = 'none';
    }
    else if (id == '2') {
        document.getElementById('shops').style.display = 'block';
        document.getElementById('ads').style.display = 'none';
        document.getElementById('regions').style.display = 'none';
        document.getElementById('cats').style.display = 'none';
        document.getElementById('countries').style.display = 'none';
    }
    else if (id == '3') {
        document.getElementById('ads').style.display = 'block';
        document.getElementById('regions').style.display = 'none';
        document.getElementById('shops').style.display = 'none';
        document.getElementById('cats').style.display = 'none';
        document.getElementById('countries').style.display = 'none';
    }
    else if (id == '4') {
    }
    else if (id == '10') {
        document.getElementById('countries').style.display = 'block';
        document.getElementById('ads').style.display = 'none';
        document.getElementById('shops').style.display = 'none';
        document.getElementById('cats').style.display = 'none';
        document.getElementById('regions').style.display = 'none';
    }
    else if (id == '12') {
        document.getElementById('regions').style.display = 'block';
        document.getElementById('ads').style.display = 'none';
        document.getElementById('shops').style.display = 'none';
        document.getElementById('cats').style.display = 'none';
        document.getElementById('countries').style.display = 'none';
    }
    else {
        document.getElementById('regions').style.display = 'none';
        document.getElementById('ads').style.display = 'none';
        document.getElementById('shops').style.display = 'none';
        document.getElementById('cats').style.display = 'none';
        document.getElementById('countries').style.display = 'none';
    }
}
function getBlockContent(shop_id) {
    var pars = "module=ZSELEX&type=ajax&func=getBlockContent&shop_id=" + shop_id;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, async: true, onComplete: myFunctionContentResponses});
}
function myFunctionContentResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    document.getElementById('blockcenter').innerHTML = req.responseText;
}
function getRegionList(country_id) {
    document.getElementById('startval').value = '0';
    document.getElementById('endval').value = '5';
    document.getElementById('hcountry').value = country_id;
    document.getElementById('hregion').value = '';
    document.getElementById('hcity').value = '';
    document.getElementById('hshop').value = '';
    var hcountry = jQuery('#hcountry').val();
    var hregion = jQuery('#hregion').val();
    var hcity = jQuery('#hcity').val();
    var hshop = jQuery('#hshop').val();
    var pars = "module=ZSELEX&type=ajax&func=getRegionList&country_id=" + country_id;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, async: true, onComplete: myFunctiongetRegionListResponses});
}
function myFunctiongetRegionListResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    document.getElementById('displayregion').innerHTML = req.responseText;
}
function getCityList(region_id) {
    document.getElementById('startval').value = '0';
    document.getElementById('endval').value = '5';
    document.getElementById('hregion').value = region_id;
    document.getElementById('hcity').value = '';
    document.getElementById('hshop').value = '';
    var hcountry = jQuery('#hcountry').val();
    var hregion = jQuery('#hregion').val();
    var hcity = jQuery('#hcity').val();
    var hshop = jQuery('#hshop').val();
    var pars = "module=ZSELEX&type=ajax&func=getCityList&shop_id=" + hshop + "&country_id=" + hcountry + "&region_id=" + region_id + "&city_id=" + hcity;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, async: true, onComplete: myFunctiongetCityListResponses});
}
function myFunctiongetCityListResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    document.getElementById('displaycity').innerHTML = req.responseText;
}
function getRegionShopList(region_id) {
    document.getElementById('startval').value = '0';
    document.getElementById('endval').value = '5';
    var hcountry = jQuery('#hcountry').val();
    var pars = "module=ZSELEX&type=ajax&func=getRegionShopList&region_id=" + region_id + "&country_id=" + hcountry;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, async: true, onComplete: myFunctiongetRegionShopListResponses});
}
function myFunctiongetRegionShopListResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    document.getElementById('displayshop').innerHTML = req.responseText;
}
function getShopFrntend(city_id) {
    document.getElementById('startval').value = '0';
    document.getElementById('endval').value = '5';
    document.getElementById('hcity').value = city_id;
    document.getElementById('hshop').value = '';
    var hcountry = jQuery('#hcountry').val();
    var hregion = jQuery('#hregion').val();
    var hcity = jQuery('#hcity').val();
    var hshop = jQuery('#hshop').val();
    var pars = "module=ZSELEX&type=ajax&func=getShopsList&shop_id=" + hshop + "&country_id=" + hcountry + "&region_id=" + hregion + "&city_id=" + hcity;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, async: true, onComplete: myFunctionShopResponses});
}
function myFunctionShopResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    document.getElementById('displayshop').innerHTML = req.responseText;
}
function shoponchange() {
    document.getElementById('startval').value = '0';
    document.getElementById('endval').value = '5';
}
function getShopDetails(shop_id) {
    jQuery("#blockshop").html('<img src=modules/ZSELEX/images/loading.gif style=padding-left:20px>');
    document.getElementById('hshop').value = getShopCookie;
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
    }
    else {
        getRegionCookie = getCookie('region_cookie');
    }
    if (hcity > 0) {
        getCityCookie = hcity;
    }
    else {
        getCityCookie = getCookie('city_cookie');
    }
    if (harea > 0) {
        getAreaCookie = harea;
    }
    else {
        getAreaCookie = getCookie('area_cookie');
    }
    if (hcategory > 0) {
        getCategoryCookie = hcategory;
    }
    else {
        getCategoryCookie = getCookie('category_cookie');
    }
    if (hbranch > 0) {
        getBranchCookie = hbranch;
    }
    else {
        getBranchCookie = getCookie('branch_cookie');
    }
    if (hsearch != '') {
        getSearchCookie = hsearch;
    }
    else {
        getSearchCookie = getCookie('search_cookie');
    }
    var hregion = getRegionCookie;
    var hcity = getCityCookie;
    var harea = getAreaCookie;
    var hshop = getShopCookie;
    var hcategory = getCategoryCookie;
    var hbranch = getBranchCookie;
    var hsearch = jQuery('#hsearch').val();
    var startval = document.getElementById('startval').value;
    var endval = document.getElementById('endval').value;
    var pars = "module=ZSELEX&type=ajax&func=getShopDetails&shop_id=" + hshop + "&country_id=" + hcountry + "&region_id=" + hregion + "&city_id=" + hcity + "&area_id=" + harea + "&category_id=" + hcategory + "&branch_id=" + hbranch + "&startval=" + startval + "&endval=" + endval + "&hsearch=" + hsearch;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, async: true, onComplete: myFunctiongetShopDetailsResponses});
}
function myFunctiongetShopDetailsResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    jQuery('#blockshop').html(req.responseText);
}
function cat(catId) {
    document.getElementById('hcategory').value = catId;
    document.getElementById('startval').value = '0';
    document.getElementById('endval').value = '5';
}
function paginateNext() {
    var startvals = document.getElementById('startval').value;
    var endvals = document.getElementById('endval').value;
    var startval = parseInt(startvals) + parseInt(endvals);
    var endval = endvals;
    document.getElementById('startval').value = startval;
    document.getElementById('endval').value = endval;
    getShopDetails(document.getElementById('hshop').value);
}
function paginatePrev() {
    var startvals = document.getElementById('startval').value;
    var endvals = document.getElementById('endval').value;
    var startval = parseInt(startvals) - parseInt(endvals);
    var endval = endvals;
    document.getElementById('startval').value = startval;
    document.getElementById('endval').value = endval;
    getShopDetails(document.getElementById('hshop').value);
}
jQuery.fn.scrollView = function() {
    return this.each(function() {
        $('html, body').animate({scrollTop: $(this).offset().top}, 1000);
    });
}
window.onload = function() {
};
function shopLimit(id) {
    document.getElementById("endval").value = id;
}
function setSelectionSessions() {
    var hcountry = jQuery('#hcountry').val();
    var hregion = jQuery('#hregion').val();
    var hcity = jQuery('#hcity').val();
    var hshop = jQuery('#hshop').val();
    var harea = jQuery('#harea').val();
    var hcategory = jQuery('#hcategory').val();
    var hbranch = jQuery('#hbranch').val();
    var hsearch = jQuery('#hsearch').val();
    var pars = "module=ZSELEX&type=ajax&func=setSelectionSessions&shop_id=" + hshop + "&country_id=" + hcountry + "&region_id=" + hregion + "&city_id=" + hcity + "&area_id=" + harea + "&category_id=" + hcategory + "&branch_id=" + hbranch + "&hsearch=" + hsearch;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, async: true});
}
function getAdDetails() {
    var hcountry = jQuery('#hcountry').val();
    var getShopCookie = getCookie('shop_cookie');
    var getRegionCookie = getCookie('region_cookie');
    var getCityCookie = getCookie('city_cookie');
    var getAreaCookie = getCookie('area_cookie');
    var getCategoryCookie = getCookie('category_cookie');
    var getBranchCookie = getCookie('branch_cookie');
    var hregion = getRegionCookie;
    var hcity = getCityCookie;
    var harea = getAreaCookie;
    var hshop = getShopCookie;
    var hcategory = getCategoryCookie;
    var hbranch = getBranchCookie;
    var hsearch = jQuery('#hsearch').val();
    var amount = document.getElementById('amount').value;
    var adtype;
    if (jQuery('#adtype').length > 0) {
        adtype = document.getElementById('adtype').value;
    }
    else {
        adtype = 0;
        return false;
    }
    var pars = "module=ZSELEX&type=ajax&func=getAdDetails1&shop_id=" + hshop + "&country_id=" + hcountry + "&region_id=" + hregion + "&city_id=" + hcity + "&area_id=" + harea + "&category_id=" + hcategory + "&branch_id=" + hbranch + "&adtype=" + adtype + "&hsearch=" + hsearch + "&amount=" + amount;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, async: true, onLoading: loading, onComplete: myFunctiongetAdDetailsResponses});
}
function loading() {
    jQuery("#blockadcontent").html('<img src=modules/ZSELEX/images/loading.gif style=padding-left:20px>');
}
function myFunctiongetAdDetailsResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    var json = pndejsonize(req.responseText);
    jQuery("#blockadcontent").html(json.data);
}
function checkDotdExist(datecheck, hasDate) {
    document.getElementById('errordiv').style.display = 'block';
    document.getElementById('dotdMsg').innerHTML = "Checking Date Availability...";
    var pars = "module=ZSELEX&type=ajax&func=checkDotdExist&dateVal=" + datecheck + "&hasDate=" + hasDate;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, async: true, onComplete: myFunctioncheckExistResponses});
}
function myFunctioncheckExistResponses(req)
{
    var exist;
    var olddate;
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    var json = pndejsonize(req.responseText);
    exist = json.count;
    olddate = json.olddate;
    var msg;
    if (exist > 0) {
        msg = "<font color='red'>This Date is Booked</font>";
        document.getElementById("dotdSpan").style.display = 'none';
    }
    else if (olddate > 0) {
        msg = "<font color='red'>Date cannot be older then current date</font>";
        document.getElementById("dotdSpan").style.display = 'none';
    }
    else {
        msg = "<font color='green'>This Date is Available</font>";
        document.getElementById("dotdSpan").style.display = 'block';
    }
    document.getElementById('errordiv').style.display = 'block';
    document.getElementById('dotdMsg').innerHTML = msg;
}
function getShopAd() {
    jQuery("#shops_ad").html('<img src=modules/ZSELEX/images/loading.gif style=padding-left:20px>');
    var hcountry = jQuery('#hcountry').val();
    var hregion = jQuery('#hregion').val();
    var hcity = jQuery('#hcity').val();
    var harea = jQuery('#harea').val();
    var hshop = jQuery('#hshop').val();
    var hcategory = jQuery('#hcategory').val();
    var hbranch = jQuery('#hbranch').val();
    var hsearch = jQuery('#hsearch').val();
    var limitshops = document.getElementById('limitshops').value;
    var shopadtype = document.getElementById('shopadtype').value;
    var pars = "module=ZSELEX&type=ajax&func=getShopAd&shop_id=" + hshop + "&country_id=" + hcountry + "&region_id=" + hregion + "&city_id=" + hcity + "&area_id=" + harea + "&category_id=" + hcategory + "&branch_id=" + hbranch + "&hsearch=" + hsearch + "&limitshops=" + limitshops + "&adtype=" + shopadtype;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, async: true, onComplete: myFunctiongetShopAdResponses});
}
function myFunctiongetShopAdResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    jQuery("#shops_ad").html(req.responseText);
}
function getArticleAd() {
    if (document.getElementById('level')) {
        var level = jQuery('#level').val();
    }
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
    }
    else {
        getRegionCookie = getCookie('region_cookie');
    }
    if (hcity > 0) {
        getCityCookie = hcity;
    }
    else {
        getCityCookie = getCookie('city_cookie');
    }
    if (harea > 0) {
        getAreaCookie = harea;
    }
    else {
        getAreaCookie = getCookie('area_cookie');
    }
    if (hcategory > 0) {
        getCategoryCookie = hcategory;
    }
    else {
        getCategoryCookie = getCookie('category_cookie');
    }
    if (hbranch > 0) {
        getBranchCookie = hbranch;
    }
    else {
        getBranchCookie = getCookie('branch_cookie');
    }
    if (hsearch != '') {
        getSearchCookie = hsearch;
    }
    else {
        getSearchCookie = getCookie('search_cookie');
    }
    var hregion = getRegionCookie;
    var hcity = getCityCookie;
    var harea = getAreaCookie;
    var hshop = getShopCookie;
    var hcategory = getCategoryCookie;
    var hbranch = getBranchCookie;
    var hsearch = jQuery('#hsearch').val();
    var limitshops = document.getElementById('limitshops').value;
    if (document.getElementById('shopadtype')) {
        var shopadtype = document.getElementById('shopadtype').value;
    }
    var pars = "module=ZSELEX&type=ajax&func=getArticleAd&shop_id=" + hshop + "&country_id=" + hcountry + "&region_id=" + hregion + "&city_id=" + hcity + "&area_id=" + harea + "&category_id=" + hcategory + "&branch_id=" + hbranch + "&hsearch=" + hsearch + "&level=" + level + "&limitshops=" + limitshops + "&adtype=" + shopadtype;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, async: true, onComplete: getArticleAdResponses});
}
function getArticleAdResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    var articles;
    var count;
    var json = pndejsonize(req.responseText);
    count = json.count
    articles = json.data;
    if (count > 0) {
        document.getElementById('newstest').innerHTML = articles;
    }
    else {
        document.getElementById('newstest').innerHTML = "No Articles Found";
    }
}
function getMoreEvents() {
    var elimit = document.getElementById('morelimit').value
    var add = parseInt(elimit) + parseInt(5);
    document.getElementById('morelimit').value = add;
    var eventlimit = document.getElementById('morelimit').value;
    jQuery("#eventblock").html('<img src=modules/ZSELEX/images/loading.gif style=padding-left:20px>');
    var level = jQuery('#level').val();
    var hcountry = jQuery('#hcountry').val();
    var hregion = jQuery('#hregion').val();
    var hcity = jQuery('#hcity').val();
    var harea = jQuery('#harea').val();
    var hshop = jQuery('#hshop').val();
    var hcategory = jQuery('#hcategory').val();
    var hbranch = jQuery('#hbranch').val();
    var hsearch = jQuery('#hsearch').val();
    var eventdate = document.getElementById('eventdate').value;
    var pars = "module=ZSELEX&type=ajax&func=getEvents&shop_id=" + hshop + "&country_id=" + hcountry + "&region_id=" + hregion + "&city_id=" + hcity + "&area_id=" + harea + "&category_id=" + hcategory + "&branch_id=" + hbranch + "&hsearch=" + hsearch + "&level=" + level + "&eventlimit=" + eventlimit + "&eventdate=" + eventdate;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, async: true, onComplete: getEventsResponses});
}
function getEvents() {
    jQuery("#eventblock").html('<img src=modules/ZSELEX/images/loading.gif style=padding-left:20px>');
    var level = jQuery('#level').val();
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
    }
    else {
        getRegionCookie = getCookie('region_cookie');
    }
    if (hcity > 0) {
        getCityCookie = hcity;
    }
    else {
        getCityCookie = getCookie('city_cookie');
    }
    if (harea > 0) {
        getAreaCookie = harea;
    }
    else {
        getAreaCookie = getCookie('area_cookie');
    }
    if (hcategory > 0) {
        getCategoryCookie = hcategory;
    }
    else {
        getCategoryCookie = getCookie('category_cookie');
    }
    if (hbranch > 0) {
        getBranchCookie = hbranch;
    }
    else {
        getBranchCookie = getCookie('branch_cookie');
    }
    if (hsearch != '') {
        getSearchCookie = hsearch;
    }
    else {
        getSearchCookie = getCookie('search_cookie');
    }
    var hregion = getRegionCookie;
    var hcity = getCityCookie;
    var harea = getAreaCookie;
    var hshop = getShopCookie;
    var hcategory = getCategoryCookie;
    var hbranch = getBranchCookie;
    var hsearch = jQuery('#hsearch').val();
    var eventlimit = document.getElementById('eventlimit').value;
    var pars = "module=ZSELEX&type=ajax&func=getEvents&shop_id=" + hshop + "&country_id=" + hcountry + "&region_id=" + hregion + "&city_id=" + hcity + "&area_id=" + harea + "&category_id=" + hcategory + "&branch_id=" + hbranch + "&hsearch=" + hsearch + "&level=" + level + "&eventlimit=" + eventlimit;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, async: true, onComplete: getEventsResponses});
}
function getEventsResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    jQuery('#eventblock').html(req.responseText);
}

function getAllEvents(value) {
    //alert('helloo');
    jQuery("#allupcommingevents").html('<img src=modules/ZSELEX/images/loading.gif style=padding-left:20px>');
    var level = jQuery('#level').val();
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
    }
    else {
        getRegionCookie = getCookie('region_cookie');
    }
    if (hcity > 0) {
        getCityCookie = hcity;
    }
    else {
        getCityCookie = getCookie('city_cookie');
    }
    if (harea > 0) {
        getAreaCookie = harea;
    }
    else {
        getAreaCookie = getCookie('area_cookie');
    }
    if (hcategory > 0) {
        getCategoryCookie = hcategory;
    }
    else {
        getCategoryCookie = getCookie('category_cookie');
    }
    if (hbranch > 0) {
        getBranchCookie = hbranch;
    }
    else {
        getBranchCookie = getCookie('branch_cookie');
    }
    if (hsearch != '') {
        getSearchCookie = hsearch;
    }
    else {
        getSearchCookie = getCookie('search_cookie');
    }
    var hregion = getRegionCookie;
    var hcity = getCityCookie;
    var harea = getAreaCookie;
    var hshop = getShopCookie;
    var hcategory = getCategoryCookie;
    var hbranch = getBranchCookie;
    var hsearch = jQuery('#hsearch').val();
    var eventlimit = document.getElementById('upcommingeventlimit').value;
    var func;
    if (value == 'reset') {
        func = 'getAllEventsReset';
    }
    else {
        func = 'getAllEvents';
    }
    var pars = "module=ZSELEX&type=ajax&func=" + func + "&shop_id=" + hshop + "&country_id=" + hcountry + "&region_id=" + hregion + "&city_id=" + hcity + "&area_id=" + harea + "&category_id=" + hcategory + "&branch_id=" + hbranch + "&hsearch=" + hsearch + "&level=" + level + "&eventlimit=" + eventlimit + "&reset=" + value;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, async: true, onComplete: getAllEventsResponses});
}
function getAllEventsResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    jQuery('#allupcommingevents').html(req.responseText);
}
function insertAdClick(adId) {
    var pars = "module=ZSELEX&type=ajax&func=insertAdClick&adId=" + adId;
    // alert(pars);
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, async: true});
}
function setSessionForDotd(name, value) {
    var pars = "module=ZSELEX&type=ajax&func=setSessionForDotd&name=" + name + "&value=" + value;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars});
}
function setSessionForDotdResponses(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
}
function setSessionForDotdDate(name, value) {
    var pars = "module=ZSELEX&type=ajax&func=setSessionForDotdDate&name=" + name + "&value=" + value;
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, async: true, onComplete: setSessionForDotdDateResponse});
}
function setSessionForDotdDateResponse(req)
{
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
}
function getGps(ID) {
    var pars = "module=Zmap&type=ajax&func=getGps";
    var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, async: true, onComplete: getGpsResponses});
}
function getGpsResponses(req)
{
    alert('hiiii');
    exit();
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
}
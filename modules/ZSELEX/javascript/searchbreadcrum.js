
/*
 * @function searchBreadcrums()
 * create bread crums
 * 
 * @param none
 * @returns {void}
 */
function searchBreadcrums() {
    // alert('helloooo'); exit();
    //  alert(Zikula.Config.baseURL);
    //exit();
    //  alert(jQuery('.BrudcomeTree').html());
    var current_theme = jQuery('#current_theme').val();
    // alert(current_theme);
    var hregion = jQuery('#hregionname').val();
    var hcity = jQuery('#hcity_name').val();
    var harea = jQuery('#hareaname').val();
    var hshop = jQuery('#hshop').val();
    var hcategory = jQuery('#hcategory').val();
    var hbranch = jQuery('#hbranch').val();
    var hsearch = jQuery('#hsearch').val();
    var aff_id = jQuery('#aff_id').val();
    var lock = getCookie('lock_cookie');
    //alert(hsearch);
    // alert(hsearch);
     //alert(hbranch);

    var getShopCookie;
    var getRegionCookie;
    var getCityCookie;
    var getAreaCookie;
    var getCategoryCookie;
    var getBranchCookie;
    var getSearchCookie;
    var getAffiliateCookie;

    var total;
    total = 0;
    //

    //alert(total);

    if (hregion != '') {
        getRegionCookie = jQuery('#hregionname').val();
        //alert('from document - ' + hregion);
    }
    else {
        getRegionCookie = getCookie('regionname_cookie');
        //alert('from cookie - ' + getRegionCookie);
    }
    //alert('from cookie - ' + getRegionCookie);
    if (hcity != '') {
        getCityCookie = jQuery('#hcity_name').val();
    }
    else {
        getCityCookie = getCookie('cityname_cookie');
    }


    if (hcategory > 0) {
        getCategoryCookie = jQuery('#hcatname').val();
    }
    else {
        getCategoryCookie = getCookie('categoryName_cookie');
    }

    if (harea != '') {
        getAreaCookie = jQuery('#hareaname').val();
    }
    else {
        getAreaCookie = getCookie('areaname_cookie');
    }


    if (hsearch != '') {
        getSearchCookie = hsearch;
    }
    else {
        getSearchCookie = getCookie('search_cookie');
    }


    if (hbranch > 0) {
        //getBranchCookie = jQuery('#hbranch').val();
        getBranchCookie = jQuery('#hbranch_name').val();
    }
    else {
        //getBranchCookie = getCookie('branch_cookie');
        getBranchCookie = getCookie('branch_name_cookie');
    }

    if (aff_id.length > 0) {
        getAffiliateCookie = jQuery('#aff_name').val();
    }
    else {
        getAffiliateCookie = getCookie('affNameCookie');
    }

    //alert(getAffiliateCookie);
    //alert("Area :" + getAreaCookie);
    //alert("Cat :" + getCategoryCookie);
    // if(getCookie('category_cookie')>0 || jQuery('#hcategory').val()>0 || getSearchCookie!='' || getCityCookie!='' || getRegionCookie!=''){
    if (getCookie('category_cookie') > 0 || jQuery('#hcategory').val() > 0 || getCookie('region_cookie') > 0 || jQuery('#hregion').val() > 0 || getCookie('city_cookie') > 0 || jQuery('#hcity').val() > 0 || getCookie('area_cookie') > 0 || jQuery('#harea').val() > 0 || getCookie('search_cookie').length > 0 || jQuery('#hsearch').val() > 0 || getBranchCookie > 0 || getAffiliateCookie.length > 0) {
        total = 1;
    }
    else {
        total = 0;
    }

    // alert(getSearchCookie.length);

    // alert("total :" + total + " " + "breadcrum li : " + jQuery('.BrudcomeTree li').length);


    if (jQuery('.BrudcomeTree li').length > 0 || total > 0) {
        //  alert('comes here');
        jQuery("ul.BrudcomeTree").css("padding", "3px");
        jQuery("ul.BrudcomeTree").css("padding-bottom", "24px");
    }
    else {
        jQuery("ul.BrudcomeTree").css("padding", "0px");
    }
    if ((jQuery('#hregion').val() > 0 || getCookie('region_cookie') > 0)) {
        jQuery("#bc_reg").remove();
        jQuery('.BrudcomeTree').append('<li id=bc_reg><a href="#">' + getRegionCookie + '</a><img class="bc_button" src="' + Zikula.Config.baseURL + 'themes/' + current_theme + '/images/black_close.png" onclick=closeBcLi("bc_reg")></li>');
    }

    if ((jQuery('#hcity').val() > 0 || getCookie('city_cookie') > 0)) {
        jQuery("#bc_city").remove();
        jQuery('.BrudcomeTree').append('<li id=bc_city><a href="#">' + getCityCookie + '</a><img class="bc_button" src="' + Zikula.Config.baseURL + 'themes/' + current_theme + '/images/black_close.png" onclick=closeBcLi("bc_city")></li>');
    }



    if ((jQuery('#harea').val() > 0 || getCookie('area_cookie') > 0)) {
        jQuery("#bc_area").remove();
        jQuery('.BrudcomeTree').append('<li id=bc_area><a href="#">' + getAreaCookie + '</a><img class="bc_button" src="' + Zikula.Config.baseURL + 'themes/' + current_theme + '/images/black_close.png" onclick=closeBcLi("bc_area")></li>');
    }

    if ((jQuery('#hcategory').val() > 0 || getCookie('category_cookie') > 0)) {
        jQuery("#bc_cat").remove();
        jQuery('.BrudcomeTree').append('<li id=bc_cat><a href="#">' + getCategoryCookie + '</a><img class="bc_button" src="' + Zikula.Config.baseURL + 'themes/' + current_theme + '/images/black_close.png" onclick=closeBcLi("bc_cat")></li>');
    }


    if ((jQuery('#hsearch').val() != '' && jQuery('#hsearch').val() != Zikula.__('search for...', 'module_zselex_js') || getCookie('search_cookie').len > 0)) {
        jQuery("#bc_search").remove();
        jQuery('.BrudcomeTree').append('<li id=bc_search><a href="#">' + escape(stripslashes(getSearchCookie)) + '</a><img class="bc_button" src="' + Zikula.Config.baseURL + 'themes/' + current_theme + '/images/black_close.png" onclick=closeBcLi("bc_search")></li>');
    }
    // if (getBranchCookie > 0) {
    if ((jQuery('#hbranch').val() > 0 || getCookie('branch_cookie') > 0)) { //branch
       // alert('hii');
        jQuery("#bc_branch").remove();
        if (lock == 1) {
            jQuery('.BrudcomeTree').append('<li id=bc_branch><a href="#">' + getBranchCookie + '</a></li>');
        }
        else {
            jQuery('.BrudcomeTree').append('<li id=bc_branch><a href="#">' + getBranchCookie + '</a><img class="bc_button" src="' + Zikula.Config.baseURL + 'themes/' + current_theme + '/images/black_close.png" onclick=closeBcLi("bc_branch")></li>');
        }
    }

    if (getAffiliateCookie.length > 0) { //aff
        jQuery("#bc_affiliate").remove();
        if (lock == 1) {
            jQuery('.BrudcomeTree').append('<li id=bc_affiliate><a href="#">' + getAffiliateCookie + '</a></li>');
        }
        else {
            jQuery('.BrudcomeTree').append('<li id=bc_affiliate><a href="#">' + getAffiliateCookie + '</a><img class="bc_button" src="' + Zikula.Config.baseURL + 'themes/' + current_theme + '/images/black_close.png" onclick=closeBcLi("bc_affiliate")></li>');
        }
    }


}

function escape(str) {
    //alert(str);
    // stripslashes(str);
    if (typeof (str) == "string") {
        str = str.replace(/&/g, "&amp;"); /* must do &amp; first */
        str = str.replace(/"/g, "&quot;");
        str = str.replace(/'/g, "&#039;");
        str = str.replace(/</g, "&lt;");
        str = str.replace(/>/g, "&gt;");
    }
    //alert(str);
    return str;
}

/*
 * @function stripslashes()
 * 
 * @return string
 */
function stripslashes(str) {

    return (str + '')
            .replace(/\\(.?)/g, function (s, n1) {
                switch (n1) {
                    case '\\':
                        return '\\';
                    case '0':
                        return '\u0000';
                    case '':
                        return '';
                    default:
                        return n1;
                }
            });
}
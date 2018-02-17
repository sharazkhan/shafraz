jQuery.noConflict();
/*
 jQuery(function() {
 sliderCall(); //first initialize
 });
 
 var myVar = setInterval(function(){
 sliderCall()
 },5000);
 */
function slideSwitch() {
    //alert('helloooo');
    // jQuery(".ui-autocomplete").css("z-index", "9");
    // return true;
    if (jQuery("#first_load").val() == true) {
        //  alert('hii');
        //jQuery('#slideshow').html("<img src=" + Zikula.Config.baseURL + "modules/ZSELEX/images/loading.gif style='align:center;padding-top:80px;margin-left:340px'>");
    }
    //jQuery('#slideshow').attr('style', 'height: auto !important');
    if (currentTheme() != 'CityPilotresponsive') {
        jQuery('#slideshow').attr('style', 'min-height: 303px !important');
    }
    //  jQuery('#slideshow').html("<img src=" + Zikula.Config.baseURL + "modules/ZSELEX/images/loading.gif style='align:center;padding-top:80px;margin-left:340px'>");
    if (currentTheme() != 'CityPilotresponsive') {
     //   jQuery('#slideshow').html("<img src=" + Zikula.Config.baseURL + "modules/ZSELEX/images/loading.gif style='margin-top:142px'>");
    }
     jQuery('#slideshow').html('');
    // exit();
    /*
     jQuery('#slideshow').attr('style', 'width: 0px !important');
     jQuery('#slideshow').attr('style', 'height: 0px !important');
     jQuery('#slideshow').html('');
     */
    var hcountry = document.getElementById('hcountry').value;

    var hcountry = document.getElementById('hcountry').value;
    var hregion = document.getElementById('hregion').value;
    var hcity = document.getElementById('hcity').value;
    var harea = document.getElementById('harea').value;
    var hshop = document.getElementById('hshop').value;
    var hcategory = document.getElementById('hcategory').value;
    var hbranch = document.getElementById('hbranch').value;
    //alert(hbranch);
    var hsearch = document.getElementById('hsearch').value;
    var aff_id = jQuery('#aff_id').val();
    //alert(aff_id);
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
        //alert('from document - ' + hregion);
    } else {
        getRegionCookie = getCookie('region_cookie');
        //alert('from cookie - ' + getRegionCookie);
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

    // var hsearch =  document.getElementById('hsearch').value;
    var hsearch = getSearchCookie;
    // var limitshops =  document.getElementById('limitshops').value;
    //var limit =  document.getElementById('highad_amount').value;

    var old_event_id = jQuery('#old_event_id').val();
    var event_count = jQuery('#event_count').val();

    //alert(old_event_id);
    // alert('comes here..'); exit();
    var params = '';
    if (aff_id != '') {
        params += "&aff_id=" + aff_id;
    }
    //alert(params); exit();

    var lang = jQuery('#curr_lang').val();
    var pars = "module=ZSELEX&type=ajax&func=sliderImage&lang=" + lang + "&shop_id=" + hshop + "&country_id=" + hcountry +
            "&region_id=" + hregion + "&city_id=" + hcity + "&area_id=" + harea + "&category_id=" + hcategory + "&branch_id=" + hbranch + "&hsearch=" + hsearch + "&old_event_id=" + old_event_id + "&event_count=" + event_count + params;
    //alert(pars);
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                //onInteractive:loading,
                //onLoading:jQuery("#specialdeal_block_products").html('<img src='+document.location.pnbaseURL+'modules/ZSELEX/images/loading.gif style=padding-left:20px>'),
                onComplete: slideSwitchResponse
            });

}

function slideSwitchResponse(req) {

    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    var json = pndejsonize(req.responseText);
    //alert(json.noresult);
    // if (currentTheme() != 'CityPilotresponsive') {
    if (json.noresult) {
        //alert('empty');

        jQuery('.banner-slider').attr('style', 'width: 0px !important');
        jQuery('.banner-slider').attr('style', 'height: 0px !important');
        // jQuery('#slideshow').attr('style', 'background: none !important');
        jQuery("#slideshow").html('');
        return;
    } else {
        //  jQuery('#slideshow').attr('style', 'width: 940px !important');
        // jQuery('#slideshow').attr('style', 'height: 303px !important');
        jQuery('.banner-slider').attr('style', 'width: auto !important');
        jQuery('.banner-slider').attr('style', 'height: auto !important');
       // jQuery('#slideshow').attr('style', 'max-width: 940px !important');
        // jQuery('#slideshow').attr('style', 'max-height: 303px !important');
       // jQuery('#slideshow').attr('style', 'height: 303px !important');

    }
    // }
    // alert(json.image);
   // alert(json.image2); //exit();
    //alert(json.event_count);
    var autoFlag = true;
    if (json.event_count < 2) {
        autoFlag = false;
    }
    jQuery("#old_event_id").val(json.old_event_id);
    jQuery('#event_count').val(json.event_count);

    jQuery("#first_load").val('0');
    if (currentTheme() == 'CityPilotresponsive') {
        jQuery("#slideshow").html(json.image2);
        jQuery('.bxslider').bxSlider({
            mode: 'fade',
            pager: false,
            controls: false,
            auto: autoFlag
        });
    } else {
        jQuery("#slideshow").html(json.image);
    }




}



function showSlide() {
    jQuery('.exclEvntImg').attr('style', 'display: block !important');
    var $active = jQuery('#slideshow IMG.active');

    if ($active.length == 0)
        $active = jQuery('#slideshow IMG:last');

    // use this to pull the images in the order they appear in the markup
    var $next = $active.next().length ? $active.next() : jQuery('#slideshow IMG:first');


    // uncomment the 3 lines below to pull the images in random order

    // var $sibs  = $active.siblings();
    // var rndNum = Math.floor(Math.random() * $sibs.length );
    // var $next  = $( $sibs[ rndNum ] );


    $active.addClass('last-active');

    $next.css({
        opacity: 0.0
    })
            .addClass('active')
            .animate({
                opacity: 1.0
            }, 1000, function () {
                $active.removeClass('active last-active');
            });


}


function showCycle() {
    jQuery('#slideshow').cycle({
        timeout: 3000
    });
}

jQuery(document).ready(function () {
    slideSwitch();

// setInterval( "slideSwitch()", 4000 );
});

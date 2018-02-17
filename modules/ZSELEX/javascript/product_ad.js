function selectLevel(level, reset) {
    // alert(level);
    var adLimit = jQuery("#service_limit").val();
    var adCost;
    //var reset =  jQuery("#reset_ad").val();
    //alert(adLimit);
    if (reset == 1) {
        var reset = jQuery("#reset_ad").val();
        jQuery("ul#high_ul li").css("border-color", "");
        jQuery("ul#low_ul li").css("border-color", "");
        jQuery("ul#mid_ul li").css("border-color", "");
        jQuery("ul#mid_ul li").css("border-right", "");
        jQuery("ul#low_ul li").css("border-color", "");
        jQuery("ul#low_ul li").css("border-right", "");
        document.getElementById('adCost').innerHTML = '0';
        return;
    }


    if (level == 'A') {
        jQuery("ul#high_ul li").css("border-color", "red");
        // jQuery("ul#high_ul li").css("border-right","1px solid");
        jQuery("ul#mid_ul li").css("border-color", "");
        jQuery("ul#mid_ul li").css("border-right", "");
        jQuery("ul#low_ul li").css("border-color", "");
        jQuery("ul#low_ul li").css("border-right", "");
        showAdCost('A');
        //adCost = adLimit - 3;
    }
    else if (level == 'B') {
        jQuery("ul#high_ul li").css("border-color", "");
        jQuery("ul#low_ul li").css("border-color", "");
        jQuery("ul#low_ul li").css("border-right", "");
        jQuery("ul#mid_ul li").css("border-color", "red");
        jQuery("ul#mid_ul li").css("border-right", "1px solid red");
        showAdCost('B');
    }
    else if (level == 'C') {
        jQuery("ul#high_ul li").css("border-color", "");
        jQuery("ul#low_ul li").css("border-color", "red");
        jQuery("ul#low_ul li").css("border-right", "1px solid red")
        jQuery("ul#mid_ul li").css("border-color", "");
        jQuery("ul#mid_ul li").css("border-right", "");
        showAdCost('C');
    }
    jQuery("#ad_level").val(level);
}


function showAdCost(level) {

    //alert(level);
    jQuery('#submit_ad').hide();
    var pars = "module=ZSELEX&type=ajax&func=showAdCost&level=" + level;
    //alert(pars);
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                onComplete: showAdCostResponses
            });

}


function showAdCostResponses(req)
{
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }
    // alert(req.responseText.length);   exit();
    //alert(req.responseText);  exit();

    //jQuery(".city").html(req.responseText);
    var json = pndejsonize(req.responseText);

    var adCost = json.adcost;
    //   alert(adCost);
    document.getElementById('adCost').innerHTML = adCost;
    jQuery('#submit_ad').show();



}


function selectedLevel(id, clss, reset) {
    //alert(clss);
    // jQuery(".first").css("fill","#e45624");
    jQuery("#map_level").val(id);
    // var reset =  jQuery("#reset_ad").val();

    if (reset == 1) {
        jQuery(".first").css("fill", "rgb(114, 127, 132)");
        jQuery(".second").css("fill", "rgb(114, 127, 132)");
        jQuery(".third").css("fill", "rgb(114, 127, 132)");
        jQuery(".four").css("fill", "rgb(114, 127, 132)");
        jQuery(".five").css("fill", "rgb(114, 127, 132)");
        jQuery(".six").css("fill", "rgb(114, 127, 132)");
        jQuery(".seven").css("fill", "rgb(114, 127, 132)");
        return;
    }

    if (clss == 'first') {
        jQuery(".first").css("fill", "#e45624");
        jQuery(".second").css("fill", "rgb(114, 127, 132)");
        jQuery(".third").css("fill", "rgb(114, 127, 132)");
        jQuery(".four").css("fill", "rgb(114, 127, 132)");
        jQuery(".five").css("fill", "rgb(114, 127, 132)");
        jQuery(".six").css("fill", "rgb(114, 127, 132)");
        jQuery(".seven").css("fill", "rgb(114, 127, 132)");

    }
    else if (clss == 'second') {
        jQuery(".second").css("fill", "#e45624");
        jQuery(".first").css("fill", "rgb(114, 127, 132)");
        jQuery(".third").css("fill", "rgb(114, 127, 132)");
        jQuery(".four").css("fill", "rgb(114, 127, 132)");
        jQuery(".five").css("fill", "rgb(114, 127, 132)");
        jQuery(".six").css("fill", "rgb(114, 127, 132)");
        jQuery(".seven").css("fill", "rgb(114, 127, 132)");

    }
    else if (clss == 'third') {
        jQuery(".third").css("fill", "#e45624");
        jQuery(".second").css("fill", "rgb(114, 127, 132)");
        jQuery(".first").css("fill", "rgb(114, 127, 132)");
        jQuery(".four").css("fill", "rgb(114, 127, 132)");
        jQuery(".five").css("fill", "rgb(114, 127, 132)");
        jQuery(".six").css("fill", "rgb(114, 127, 132)");
        jQuery(".seven").css("fill", "rgb(114, 127, 132)");

    }
    else if (clss == 'four') {
        jQuery(".four").css("fill", "#e45624");
        jQuery(".second").css("fill", "rgb(114, 127, 132)");
        jQuery(".first").css("fill", "rgb(114, 127, 132)");
        jQuery(".third").css("fill", "rgb(114, 127, 132)");
        jQuery(".five").css("fill", "rgb(114, 127, 132)");
        jQuery(".six").css("fill", "rgb(114, 127, 132)");
        jQuery(".seven").css("fill", "rgb(114, 127, 132)");

    }
    else if (clss == 'five') {
        jQuery(".five").css("fill", "#e45624");
        jQuery(".second").css("fill", "rgb(114, 127, 132)");
        jQuery(".first").css("fill", "rgb(114, 127, 132)");
        jQuery(".third").css("fill", "rgb(114, 127, 132)");
        jQuery(".four").css("fill", "rgb(114, 127, 132)");
        jQuery(".six").css("fill", "rgb(114, 127, 132)");
        jQuery(".seven").css("fill", "rgb(114, 127, 132)");

    }
    else if (clss == 'six') {
        //alert('six');
        jQuery(".six").css("fill", "#e45624");
        jQuery(".five").css("fill", "rgb(114, 127, 132)");
        jQuery(".second").css("fill", "rgb(114, 127, 132)");
        jQuery(".first").css("fill", "rgb(114, 127, 132)");
        jQuery(".third").css("fill", "rgb(114, 127, 132)");
        jQuery(".four").css("fill", "rgb(114, 127, 132)");
        jQuery(".seven").css("fill", "rgb(114, 127, 132)");

    }
    else if (clss == 'seven') {
        // alert('comes here');
        jQuery(".seven").css("fill", "#e45624");
        jQuery(".six").css("fill", "rgb(114, 127, 132)");
        jQuery(".five").css("fill", "rgb(114, 127, 132)");
        jQuery(".second").css("fill", "rgb(114, 127, 132)");
        jQuery(".first").css("fill", "rgb(114, 127, 132)");
        jQuery(".third").css("fill", "rgb(114, 127, 132)");
        jQuery(".four").css("fill", "rgb(114, 127, 132)");

    }




}


function setRegionId(regId) {

//alert(regId);

}

function Orange(id, len)
{
    return;
    if (jQuery("#map_level").val() == id) {
        return;
    }
    var name = id.slice(0, -1);
    for (var i = 1; i <= len; i++) {
        document.getElementById(name + i).style.fill = "#e45624";
    }

}

function Gray(id, len) {
    return;
    if (jQuery("#map_level").val() == id) {
        return;
    }
    var name = id.slice(0, -1);
    for (var i = 1; i <= len; i++) {
        document.getElementById(name + i).style.fill = "#727F84";
    }
}

function regionSelect(regionId, regionName) {   // called on region on change

    // alert(regionName); 
    // alert(regionId);
    getCityListAll(regionId);
    jQuery("#region_id").val(regionId);
    jQuery("#region_name").val(regionName);

    setName();

}

function getCityListAll(regionId) {   //CALLED ON ALL

    //alert(region_id);  exit();
    //alert(country_id);  exit();

    var hcountry = '61';
    var hregion = regionId;
    if (regionId > 0) {
        jQuery("#region_id").val(regionId);
    }



    var pars = "module=ZSELEX&type=ajax&func=getCityForProductAd&country_id=" + hcountry + "&region_id=" + hregion;
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                onComplete: getCityListAllResponses
            });

}


function getCityListAllResponses(req)
{
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }
    // alert(req.responseText.length);   exit();
    //alert(req.responseText);  exit();

    //jQuery(".city").html(req.responseText);
    var json = pndejsonize(req.responseText);
    document.getElementById('city-div').innerHTML = json.cities;


    jQuery("#city-combo").ZselexCombo({
        emptyText: Zikula.__('Choose a city...', 'module_zselex_js')
                //autoFill: true
                //triggerSelected: true
    });

}


function setName() {
    var name;
    var value;
    var finalName;
    name = "COUNTRY";
    value = document.getElementById('country_name');
    finalName = name + "-" + value;
    var country_id = document.getElementById('country_id');
    var region_id = jQuery("#region_id").val();
    var city_id = jQuery("#city-combo").val();
    //alert(city_id);
    if (region_id > 0) {
        name = "REGION";
        value = jQuery("#region_name").val();
    }
    if (city_id > 0) {
        name = "CITY";
        value = jQuery("#city_name").val();
    }
    finalName = name + "-" + value;

    jQuery("#ad_name").val(finalName);



}

jQuery("#city-combo").live('change', function() {
    // alert(this.value);
    //alert(this.id);
    if (this.value > 0) {
        var cityName = jQuery(this).find("option:selected").text()
        jQuery("#city_name").val(cityName);
        // if(jQuery("#ad_name").val()==''){
        setName();
    }
//  }
});

function validateProductAd() {
    var adLevel = jQuery("#ad_level").val();
    var error;
    //alert(adLevel);
    if (adLevel == '') {
        alert(Zikula.__('Please choose a level', 'module_zselex_js'));
        return false;
    }
    else {
        return true;
    }
}

function resetProductAd() {
    jQuery("#reset_ad").val('1');
    selectLevel('', '1');
    selectedLevel('', '', '1');
    getCityListAll(0);
    jQuery("#ad_level").val('');
    jQuery("#region_id").val('');
    jQuery("#city_id").val('');
    jQuery("#ad_name").val('');
    return false;
}


function deleteAd()
{
    if (confirm(Zikula.__("Do you really delete this Ad?")) == true)
        return true;
    else
        return false;
}


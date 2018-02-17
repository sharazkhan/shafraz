


jQuery().ready(function () {
    var $scrollingDiv = jQuery("#dowgrade_alert");
    jQuery(window).scroll(function () {
        $scrollingDiv
                .stop()
                .animate({
                    "marginTop": (jQuery(window).scrollTop() + 50) + "px"
                }, "medium");
    });

    //jQuery("#cancelD").click(function () {
    jQuery("#cancelD").live('click', function (event) {
        //  alert("The paragraph was clicked.");
        jQuery('#backshield').hide();
        jQuery('#dowgrade_alert').hide();
        event.preventDefault();
    });

});

var tmpBundleId;
function downgradeConfirm(bundleId) {
    // alert('helloo');  exit();
    // alert(bundleId); exit();
    jQuery('#dowgrade_alert').html(Zikula.__('Processing...', 'module_zselex_js'));
    tmpBundleId = bundleId;
    var shopId = jQuery('#shop_id').val();
    jQuery('#backshield').show();
    jQuery('#dowgrade_alert').show();

    //var marginTop = parseInt(jQuery(window).scrollTop()) + parseInt(50);
    //jQuery("#dowgrade_alert").css("margin-top", marginTop + 'px');

    var pars = "module=ZSELEX&type=shopajax&func=downgradeConfirm&bundle_id=" + tmpBundleId + "&shop_id=" + shopId;
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                onComplete: downgradeConfirmResponses
            });
}

function downgradeConfirmResponses(req)
{
    //alert('comes here'); exit();
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }

    var json = pndejsonize(req.responseText);
    // alert(json.error);
    //  exit();

    jQuery('#dowgrade_alert').html(json.data);
    var marginTop = parseInt(jQuery(window).scrollTop()) + parseInt(50);
    jQuery("#dowgrade_alert").css("margin-top", marginTop + 'px');

    // alert('Error Occured');

}




function downgradeBundle() {
    // alert(tmpBundleId);
    //jQuery('#OrangeBtn').val(Zikula.__('Processing...', 'module_zselex_js'));
    //  alert('helloo');
    jQuery('#dowgrade_alert').html(Zikula.__('Downgrading...', 'module_zselex_js'));
    var shopId = jQuery('#shop_id').val();
    //alert(shopId); exit();
    //exit();
    var pars = "module=ZSELEX&type=shopajax&func=downgradeBundle&bundle_id=" + tmpBundleId + "&shop_id=" + shopId;
    //  alert(pars); exit();
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                onComplete: downgradeBundleResponses
            });
}

function downgradeBundleResponses(req)
{
    //alert('comes here'); exit();
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }

    var json = pndejsonize(req.responseText);
    // alert(json.error);
    //  exit();


    if (!json.error) {
        location.href = "";
        exit();
    }

    alert('Error Occured');

}


function removeConfirm(bundleId) {
    // alert('helloo');
    //alert(bundleId);
    jQuery('#dowgrade_alert').html(Zikula.__('Processing...', 'module_zselex_js'));
    tmpBundleId = bundleId;
    var shopId = jQuery('#shop_id').val();
    jQuery('#backshield').show();
    jQuery('#dowgrade_alert').show();

    //var marginTop = parseInt(jQuery(window).scrollTop()) + parseInt(50);
    //jQuery("#dowgrade_alert").css("margin-top", marginTop + 'px');

    var pars = "module=ZSELEX&type=shopajax&func=removeConfirm&bundle_id=" + tmpBundleId + "&shop_id=" + shopId;
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                onComplete: removeConfirmResponses
            });
}

function removeConfirmResponses(req)
{
    //alert('comes here'); exit();
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }

    var json = pndejsonize(req.responseText);
    // alert(json.error);
    //  exit();

    jQuery('#dowgrade_alert').html(json.data);
    var marginTop = parseInt(jQuery(window).scrollTop()) + parseInt(50);
    jQuery("#dowgrade_alert").css("margin-top", marginTop + 'px');

    // alert('Error Occured');

}


function removeBundle() {
    // alert(tmpBundleId);
    //jQuery('#OrangeBtn').val(Zikula.__('Processing...', 'module_zselex_js'));
    //  alert('helloo');
    jQuery('#dowgrade_alert').html(Zikula.__('Removing...', 'module_zselex_js'));
    var shopId = jQuery('#shop_id').val();
    //alert(shopId); exit();
    //exit();
    var pars = "module=ZSELEX&type=shopajax&func=removeBundle&bundle_id=" + tmpBundleId + "&shop_id=" + shopId;
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                onComplete: removeBundleResponses
            });
}

function removeBundleResponses(req)
{
    //alert('comes here'); exit();
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }

    var json = pndejsonize(req.responseText);
    // alert(json.error);
    //  exit();


    if (!json.error) {
        location.href = "";
        exit();
    }

    alert('Error Occured');

}



function downgradeAdditionalConfirm(bundleId) {
    // alert('helloo');  exit();
    // alert(bundleId); exit();
    jQuery('#dowgrade_alert').html(Zikula.__('Processing...', 'module_zselex_js'));
    tmpBundleId = bundleId;
    var shopId = jQuery('#shop_id').val();
    jQuery('#backshield').show();
    jQuery('#dowgrade_alert').show();

    //var marginTop = parseInt(jQuery(window).scrollTop()) + parseInt(50);
    //jQuery("#dowgrade_alert").css("margin-top", marginTop + 'px');

    var pars = "module=ZSELEX&type=shopajax&func=downgradeAdditionalConfirm&bundle_id=" + tmpBundleId + "&shop_id=" + shopId;
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                onComplete: downgradeAdditionalConfirmResponses
            });
}

function downgradeAdditionalConfirmResponses(req)
{
    //alert('comes here'); exit();
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }

    var json = pndejsonize(req.responseText);
    // alert(json.error);
    //  exit();

    jQuery('#dowgrade_alert').html(json.data);
    var marginTop = parseInt(jQuery(window).scrollTop()) + parseInt(50);
    jQuery("#dowgrade_alert").css("margin-top", marginTop + 'px');

    // alert('Error Occured');

}



function downgradeAdditionalBundle() {
     //alert(tmpBundleId); exit();
    //jQuery('#OrangeBtn').val(Zikula.__('Processing...', 'module_zselex_js'));
    //  alert('helloo');
    jQuery('#dowgrade_alert').html(Zikula.__('Downgrading...', 'module_zselex_js'));
    var shopId = jQuery('#shop_id').val();
    //alert(shopId); exit();
    //exit();
    var pars = "module=ZSELEX&type=shopajax&func=downgradeAdditionalBundle&bundle_id=" + tmpBundleId + "&shop_id=" + shopId;
    //  alert(pars); exit();
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                onComplete: downgradeAdditionalBundleResponses
            });
}

function downgradeAdditionalBundleResponses(req)
{
    //alert('comes here'); exit();
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }

    var json = pndejsonize(req.responseText);
    // alert(json.error);
    //  exit();


    if (!json.error) {
        location.href = "";
        exit();
    }

    alert('Error Occured');

}



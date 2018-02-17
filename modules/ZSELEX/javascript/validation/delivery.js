jQuery(document).ready(function () {
    jQuery("#chng_shippingaddr").click(function () {
        //alert("The paragraph was clicked.");

        jQuery(".new_address").css("display", "block");
        var disabl;
        if (jQuery("#chng_shippingaddr").is(':checked')) {
            jQuery(".checkout3 .checkout_table label").css("color", "#FF5515");
            disabl = "";
        } else {
            jQuery(".new_address").css("display", "none");
            jQuery(".checkout3 .checkout_table label").css("color", "#FFAA8A");
            disabl = "disabled";
        }
        jQuery("#fname").prop("disabled", disabl);
        jQuery("#lname").prop("disabled", disabl);
        jQuery("#address").prop("disabled", disabl);
        jQuery("#phone").prop("disabled", disabl);
        jQuery("#country_code").prop("disabled", disabl);
        jQuery("#telephone").prop("disabled", disabl);
    });
    jQuery("#updatePrice").click(function () {
        // alert(Zikula.__('The entered discount code is not valid for this order', 'module_zselex_js'));//
        //jQuery("#dis_code").val('');
        applyDiscount();
    });

    jQuery("#self_pickup").click(function () {
        // alert('hii');
        selfPickup();
        /*
         var gtotal = jQuery("#gtotalhidden").val();
         var gtotalall = jQuery("#gtotalallhidden").val();
         var shippingTotal = jQuery("#shippinghidden").val();
         // alert(gtotal);
         var checked = jQuery("#self_pickup").is(':checked');
         //alert(checked);
         if (checked) {
         jQuery("#gtotalall").html('DKK ' + gtotal);
         jQuery("#shippingtotal").html('0');
         jQuery("#chng_shippingaddr").prop("disabled", "disabled");
         jQuery("#delevery_alt_hdr").css("color", "grey");
         } else {
         jQuery("#gtotalall").html('DKK ' + gtotalall);
         jQuery("#shippingtotal").html(shippingTotal);
         jQuery("#chng_shippingaddr").prop("disabled", "");
         jQuery("#delevery_alt_hdr").css("color", "#FF5515");
         }
         */
        //var myAjax = new Ajax.Request("ajax.php", {method: 'get', parameters: pars, onComplete: getAreaListAllResponses});
    });
});




function selfPickup() {
    // alert('comes here'); exit();
    var flag = 0;
    var checked = jQuery("#self_pickup").is(':checked');
    if (checked) {
        flag = 1;
    }
    // alert(flag); exit();
    var shop_id = jQuery('#shop_id').val();
    // alert(shop_id); exit();
    var pars = "module=ZSELEX&type=cartajax&func=selfPickup&checked=" + flag + "&shop_id=" + shop_id;
    //alert(pars); exit();
    var myAjax = new Ajax.Request("ajax.php", {
        method: 'get',
        parameters: pars,
        // asynchronous: false,
        onComplete: selfPickupResponse
    });
}
function selfPickupResponse(req)
{
    //  alert('comes here2'); exit();
    // exit();

    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    var json = pndejsonize(req.responseText);

    // alert(json.checked);
    // alert(json.final_price);
    jQuery("#gtotalall").html('DKK ' + json.final_price);
    jQuery("#shippingtotal").html('DKK ' + json.shipping);
    jQuery("#vat_td").html('DKK ' + json.vat);
}




function applyDiscount() {
    // alert('comes here'); exit();
    var code = jQuery('#dis_code').val();
    var shop_id = jQuery('#shop_id').val();
    // alert(shop_id); exit();
    var pars = "module=ZSELEX&type=cartajax&func=applyDiscount&code=" + code + "&shop_id=" + shop_id;
    var myAjax = new Ajax.Request("ajax.php", {
        method: 'get',
        parameters: pars,
        // asynchronous: false,
        onComplete: applyDiscountResponse
    });
}
function applyDiscountResponse(req)
{
    //  alert('comes here2'); exit();
    // exit();

    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    var json = pndejsonize(req.responseText);
   
    //alert(json.error);
    if (json.error) {
        jQuery("#discount_td").html(json.discount + "%");
        jQuery("#gtotalall").html('DKK ' + json.newPrice);
         jQuery("#vat_td").html('DKK ' + json.vat);
        alert(Zikula.__('The entered discount code is not valid for this order', 'module_zselex_js'));
        exit();
    }
   // alert(json.newPrice);
    jQuery("#gtotalall").html('DKK ' + json.newPrice);
    // alert('newPrice :'+json.newPrice)
    // alert(json.discount_value)
    //  alert(json.discount)
    jQuery("#discount_td").html(json.discount + "%");
    jQuery("#vat_td").html('DKK ' + json.vat);
    // alert(Zikula.__('Discount Applied', 'module_zselex_js'));
    //total = json.total
    // return json.total;
}

var total = 0;
var gw = '';
jQuery(document).ready(function () {
    var errorMsg = Zikula.__('We suspect the transaction has been tampered with. Its now invalid and will be terminated. Please try again.', 'module_zselex_js');
    jQuery('.pp_paybtns').click(function (event) {
        gw = 'paypal';
        jQuery(".pp_paybtns").css("display", "none");
        jQuery("#redirecting").css("display", "block");
        var ppTotal = 0;
        /*
         jQuery('.pp_amount').each(function (i, obj) {
         ppTotal += parseInt(obj.value) * jQuery(this).attr('qty');
         });
         */
        var ppTotal = jQuery('#pp_amount').val();
        getTotal();
        //alert(total);
        //alert(ppTotal);
        // alert('ppTotal :' + ppTotal + ' ' + 'total :' + total);   exit();

        if (ppTotal != total) {
            alert(errorMsg);
            //return false;
            window.location.href = jQuery('#deleveryUrl').val();
        } else {
            // alert('Submit'); exit();
           // alert(Zikula.Config.baseURL);
            // exit();
            sendEmail();
            document.forms['payforms'].submit();
        }
    });

    jQuery('.ep_button').click(function (event) {
        jQuery(".paybtns").css("display", "none");
        jQuery("#redirecting").css("display", "block");
        //exit();
        getTotal();
        // alert(total * 100); 
        // var origTotal = total * 100;
        var origTotal = total;
        var epTotal = jQuery('#ep_amount').val();
        // alert(epTotal); exit();

        //alert('epTotal :' + epTotal + ' ' + 'origTotal :' + origTotal);  exit();

        if (origTotal != epTotal) {
            alert(errorMsg);
            //return false;
            window.location.href = jQuery('#deleveryUrl').val();
        } else {
            document.forms['payforms'].submit();
        }
    });

    jQuery('.qp_button').click(function (event) {
        gw = 'quickpay';
        jQuery(".paybtns").css("display", "none");
        jQuery("#redirecting").css("display", "block");
        //exit();
        getTotal();
        // alert(total * 100); 
        //var origTotal = total * 100;
        var origTotal = total;
        var qpTotal = jQuery('#qp_amount').val();
        // alert(epTotal); exit();
        //  alert('qpTotal :' + qpTotal + ' ' + 'origTotal :' + origTotal);  exit();

        if (origTotal != qpTotal) {
            alert(errorMsg);
            window.location.href = jQuery('#deleveryUrl').val();
        } else {
            // alert('Submit'); exit();
            sendEmail();
            document.forms['payforms'].submit();
        }
    });

    jQuery('.na_paybtns').click(function (event) {
        jQuery(".paybtns").css("display", "none");
        jQuery("#redirecting").css("display", "block");
        document.forms['payforms'].submit();
    });

});


function getTotal() {
    if (jQuery("#startval").length) {
        jQuery("#startval").val('0');
    }
    var pars = "module=ZSELEX&type=cartajax&func=getTotal&gw=" + gw;
    var myAjax = new Ajax.Request("ajax.php", {
        method: 'get',
        parameters: pars,
        asynchronous: false,
        onComplete: getTotalResponse
    });
}
function getTotalResponse(req)
{
    //  alert('comes here');
    // exit();

    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    var json = pndejsonize(req.responseText);
    //  alert(json.total); exit();
    // alert(json.error); exit();
    if (json.error == true) {
        // alert('redirect');  exit();

        window.location.href = json.cart_url;
        exit();
    }
    total = json.total
    // return json.total;
}



function sendEmail() {

    var pars = "module=ZSELEX&type=cartajax&func=sendEmail&gw=" + gw;
    // alert(pars); exit();
    var myAjax = new Ajax.Request("ajax.php", {
        method: 'get',
        parameters: pars,
        asynchronous: false
        // onComplete: getTotalResponse
    });
}
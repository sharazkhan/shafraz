function addToCart(pid, shop_id, loggedin, prdview) {
    // addbutton
    //alert(pid); exit();
    //alert(loggedin); exit();
    //  var shop_id = document.getElementById("shop_id").value;
    //alert(prdview);
    var func;
    if (loggedin) {
        func = 'addToCart';
    } else {
        func = 'addToCartGuest';
    }

    func = 'addToCart';

    //jQuery('#addloader'+pid).html('Adding to cart...');
    //jQuery(".BoxId" + pid).css("display", "none");

    if (prdview) {
        jQuery(".BoxId" + pid).css("display", "none");

    } else {
        jQuery(".BoxId" + pid).css("background", "none");
        jQuery("#buytxt" + pid).css("display", "none");
    }
    jQuery('#addloader' + pid).fadeIn(400).html('<img src="' + Zikula.Config.baseURL + 'modules/ZSELEX/images/ajax-loading.gif" />');
    var pars = "module=ZSELEX&type=cartajax&func=" + func + "&pid=" + pid + "&shop_id=" + shop_id + "&loggedin=" + loggedin;
    // alert(pars); exit();
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                onComplete: addToCartResponses
            });


}

function addToCartResponses(req)
{
    //alert('comes heresss'); exit();
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }


    var json = pndejsonize(req.responseText);
    var notLoggedIn = json.notLoggedIn;
    if (notLoggedIn == 1) {
        // alert('hellooo'); exit();
        window.location.href = json.redirect_url;
        return;
    }
    var exist = json.exist;
    var pid = json.pid;
    var cart_total = json.cart_total;
    var cart_count = json.cart_count;
    var counts = '';
    if (cart_count > 0) {
        counts = "(" + cart_count + ")";
    }
    var theme_path = json.theme_path;
    // alert(cart_total);
    //alert(counts);

    if (exist) {
        alert(Zikula.__('This product is already in your cart!', 'module_zselex_js'));
    } else {
        // jQuery('#cartcount').html(cart_total + " DKK " + "<img src=" + Zikula.Config.baseURL + "" + theme_path + "/images/checkout.png />" + counts);
        jQuery('#carts_total').html(cart_total + ' DKK');
        jQuery('#carts_count').html(cart_count);
        alert(Zikula.__('Product added to cart!', 'module_zselex_js'));
    }


    jQuery('#addloader' + pid).html('');
    jQuery(".BoxId" + pid).css("display", "block");
    jQuery(".BoxId" + pid).css("background", "none repeat scroll 0 0 #e65622");
    jQuery("#buytxt" + pid).css("display", "block");

    return true;

//document.getElementById('editEvent').innerHTML = json.data;


}



function addToCartOptionsResponses(req)
{
    //alert('comes here'); exit();
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }


    var json = pndejsonize(req.responseText);
    var pid = json.pid;

    if (json.outofstock) {
        alert(Zikula.__('Product not available in desired quantity!', 'module_zselex_js'));
        jQuery('#addloader' + pid).html('');
        jQuery(".BoxId" + pid).css("display", "block");
        //jQuery(".BoxId" + pid).css("background", "none repeat scroll 0 0 #e65622");
        jQuery("#buytxt" + pid).css("display", "inline-block");
        // alert(Zikula.__('Product not available in desired quantity!', 'module_zselex_js'));
        return true;
    }
    var notLoggedIn = json.notLoggedIn;
    if (notLoggedIn == 1) {
        // alert('hellooo'); exit();
        // window.location.href = json.redirect_url;
        // return;
    }
    var exist = json.exist;
    //var pid = json.pid;
    //alert(pid);
    var cart_total = json.cart_total;
    var cart_count = json.cart_count;
    var counts = '';
    if (cart_count > 0) {
        counts = "(" + cart_count + ")";
    }
    var theme_path = json.theme_path;
    // alert(cart_total);
    //alert(counts);

    if (exist) {
        // alert(Zikula.__('This product is already in your cart!', 'module_zselex_js'));
    }
    // else {
    // jQuery('#cartcount').html(cart_total + " DKK " + "<img src=" + Zikula.Config.baseURL + "" + theme_path + "/images/checkout.png />" + counts);
    //jQuery('#carts_total').html(cart_total + ' DKK');
    //jQuery('.carts_total').html(cart_total + ' DKK');
    jQuery('.carts_total').html(cart_total);
  //  jQuery('#carts_count').html(cart_count);
    alert(Zikula.__('Product added to cart!', 'module_zselex_js'));
    // }


    jQuery('#addloader' + pid).html('');
    jQuery(".BoxId" + pid).css("display", "block");
    // jQuery(".BoxId" + pid).css("background", "none repeat scroll 0 0 #e65622");
    jQuery("#buytxt" + pid).css("display", "inline-block");

    return true;

//document.getElementById('editEvent').innerHTML = json.data;


}


/**
 * Add to Cart
 * 
 * @param {int} pid
 * @param {int} shop_id
 * @param {int} loggedin
 * @param {int} prdview
 * @returns {Boolean}
 */
function addToCartOptions(pid, shop_id, loggedin, prdview) {
    // addbutton
    //alert(pid); exit();
    //alert(loggedin); exit();
    //  var shop_id = document.getElementById("shop_id").value;
    //alert(prdview);
    if (!checkedOptions()) {
        return false;
    }

    if (!checkQuestion()) {
        return false;
    }

    var ques_ans = '';
    if (document.getElementById('ques_validate')) {
        ques_ans = jQuery('#ques_ans').val();
    }

    // alert(selectedOptions); exit();
    // alert(selectedOptionString); return false;
    var func;
    if (loggedin) {
        func = 'addToCart';
    } else {
        func = 'addToCartGuest';
    }

    func = 'addToCart';

    //jQuery('#addloader'+pid).html('Adding to cart...');
    //jQuery(".BoxId" + pid).css("display", "none");

    /*
     if (prdview) {
     jQuery(".BoxId" + pid).css("display", "none");
     
     }
     else {
     jQuery(".BoxId" + pid).css("background", "none");
     jQuery("#buytxt" + pid).css("display", "none");
     }
     */
    jQuery(".BoxId" + pid).css("display", "none");
    jQuery('#addloader' + pid).fadeIn(400).html('<img src="' + Zikula.Config.baseURL + 'modules/ZSELEX/images/ajax-loading.gif" />');
    var pars = "module=ZSELEX&type=cartajax&func=" + func + "&pid=" + pid + "&shop_id=" + shop_id + "&options=" + selectedOptions + "&options2=" + selectedOptionString + "&loggedin=" + loggedin + "&ques_ans=" + ques_ans;
    // alert(pars);  exit();

    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                onComplete: addToCartOptionsResponses
            });


}




function showParentOption(productId, parentOptionId, optionValueId) {
    //alert(productId);
    jQuery('#OrangeBtn').val(Zikula.__('Processing...', 'module_zselex_js'));
    var pars = "module=ZSELEX&type=cartajax&func=getParentOptionValues&parentOptionId=" + parentOptionId + "&optionValueId=" + optionValueId + "&product_id=" + productId;
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                onComplete: showParentOptionResponses
            });
}

function showParentOptionResponses(req)
{
    //alert('comes here'); exit();
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }

    var json = pndejsonize(req.responseText);
    // alert(json.data); exit();

    // jQuery('.parentDisplay').html('test');
    jQuery('#OrangeBtn').val(Zikula.__('Add to Cart', 'module_zselex_js'));
    jQuery('.parentDisplay').html(json.data);





}

var price = [];
var childOptionValueId = '';
var parentOptionValueId = '';
function changePrice(productId, optionId, optionValueId, child, parent, optionType, parentOptionId) {
    //return;
    //alert(optionValueId)
    //alert(child)
    //alert(parent)

    //alert(type); exit();

    //alert(productId); exit();
    // alert(productToOptionId); 
    // alert('hellooo'); exit();



    if (child == 0 && parent == 0) {
        //jQuery('#OrangeBtn').val(Zikula.__('Add to Cart', 'module_zselex_js'));
        return;
    }

    jQuery('#OrangeBtn').val(Zikula.__('Processing...', 'module_zselex_js'));
    document.getElementById("OrangeBtn").disabled = true;
    jQuery('#OrangeBtn').css("background", "none repeat scroll 0 0 grey");

    jQuery('.showPrice').html('');

    if ((child == 1 || child == "1") && (optionValueId == 0 || optionValueId == '0')) {
        //alert('hellooo');
        jQuery('#OrangeBtn').val(Zikula.__('Add to Cart', 'module_zselex_js'));
        jQuery(".parentDisplay :input").attr("disabled", true);
        return;
    }
    if (optionValueId == 0 || optionValueId == '0') {
        // jQuery(".parentDisplay :input").attr("disabled", true);
        jQuery('#OrangeBtn').val(Zikula.__('Add to Cart', 'module_zselex_js'));
        return;
    }



    if (child == 1 || child == '1') {
        childOptionValueId = optionValueId;
        // alert("child");
        //childOptionValueId = 0;
        jQuery('#OrangeBtn').val(Zikula.__('Processing...', 'module_zselex_js'));
        showParentOption(productId, parentOptionId, optionValueId);
    }

    if (parent == 0) {
        jQuery('#OrangeBtn').val(Zikula.__('Add to Cart', 'module_zselex_js'));
        return;
    }

    if (parent == 1 || parent == '1') {
        parentOptionValueId = optionValueId;
        // alert("child");
        //childOptionValueId = 0;
    }

    var parentTypeId = jQuery('#parentTypeId').val();
    if (parentTypeId) {
        if (childOptionValueId != '' && childOptionValueId > 0) {
            // document.getElementById("test-12345").disabled = false;
            // document.getElementById(parentTypeId).disabled = false;

            jQuery('.parents').prop("disabled", "");
        } else {
            // document.getElementById(parentTypeId).disabled = true;
            jQuery('.parents').prop("disabled", "disabled");
        }
    }
    /*else if (child == 0 || child == '0') {
     //alert("not child");
     }
     else {
     childOptionValueId = 0;
     // alert("not child");
     }*/

    /* if (parent == 1 || parent == '1') {
     parentOptionValueId = optionValueId;
     // alert("child");
     }
     else if (parent == 0 || parent == '0') {
     //alert("not child");
     parentOptionValueId = 0;
     }
     else {
     parentOptionValueId = 0;
     // alert("not child");
     }*/

    //alert("optionId :" + optionId + " optionValueId :" + optionValueId + " childOptionValueId :" + childOptionValueId + " parentOptionValueId :" + parentOptionValueId + " child :" + child + " parent :" + parent);exit();


    // if (child != 1) {
    //alert('not child');
    //document.getElementById("OrangeBtn").disabled = true;
    // jQuery('#OrangeBtn').css("background", "none repeat scroll 0 0 grey");


    var pars = "module=ZSELEX&type=cartajax&func=getProductToOptionValueId&productId=" + productId + "&optionId=" + optionId + "&optionValueId=" + optionValueId + "&childOptionValueId=" + childOptionValueId + "&parentOptionValueId=" + parentOptionValueId + "&child=" + child + "&parent=" + parent + "&optionType=" + optionType;
    // alert(pars);  exit();

    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                onComplete: changePriceResponses
            });
    // }



    // alert(childOptionValueId);


}

var allVals = [];
function changePriceResponses(req)
{
    //alert('comes here'); exit();
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }




    var json = pndejsonize(req.responseText);
    // alert(json.data); exit();

    //  alert(json.error);  exit();

    if (json.notAvailable) {
        alert(Zikula.__('Not Available!', 'module_zselex_js'));
        return;
    } else if (json.noQuantity) {
        alert(Zikula.__('Out of stock!', 'module_zselex_js'));
        return;
    }

    //alert(json.newPrice);
    /*
     if (json.newPrice == true) {
     document.getElementById("prdPrice").innerHTML = json.price;
     }
     else {
     document.getElementById("prdPrice").innerHTML = document.getElementById("origPrice").value;
     }
     */

    document.getElementById("OrangeBtn").disabled = false;
    // jQuery('#OrangeBtn').val('Add to Cart');
    jQuery('#OrangeBtn').val(Zikula.__('Add to Cart', 'module_zselex_js'));
    jQuery('#OrangeBtn').css("background", "none repeat scroll 0 0 #e65621");


    //alert(json.price); exit();
    //alert(json.showPrice); exit();
    selectedOptions = json.data;
    if (json.showPrice) {
        jQuery('.showPrice').html(json.price);
    }

    //alert(selectedOptions);  exit();


    /*allVals.push({
     prdToOptionID: res[1],
     valueID: valueid
     });*/
}


var selectedOptions;
var selectedOptionString;


function inArray(needle, haystack) {
    var length = haystack.length;
    for (var i = 0; i < length; i++) {
        if (haystack[i] == needle)
            return true;
    }
    return false;
}

function checkedOptions() {
    var flag = true;
    var id;
    var ids = [];
    var allVal = [];
    var tempId;
    var tempIdArr = [];
    jQuery('.options_select').each(function () {
        if (this.id != tempId) {
            ids.push(this.id);
        }

        /* if (!inArray(this.id, tempIdArr)) {
         
         ids.push(this.id);
         }*/
        // tempIdArr.push(this.id);
        tempId = this.id;
    });
    //alert(ids.length);
    var selected_options = [];
    for (var i = 0; i <= ids.length; i++) {
        // alert(ids[i]);
        var mytype = jQuery("#" + ids[i]).attr('mytype');

        //alert(mytype);
        //  alert(valueid);
        var name = jQuery("#" + ids[i]).attr('name');
        var linked = jQuery("#" + ids[i]).attr('linked');
        //alert(linked);
        if (mytype == 'radio') {
            var res = ids[i].split("-");
            //alert(ids[i]);
            var radioVal = jQuery("input[id=" + ids[i] + "]:checked").val();
            var valueid = jQuery("input[id=" + ids[i] + "]:checked").attr('valueid');
            //alert(valueid);
            //alert(radioVal);
            if (radioVal == '' || radioVal == 'undefined' || !radioVal) {
                // alert('Please select from ' + name);
                flag = false;
                // jQuery("#label-" + res[1]).css('color', 'red');
                jQuery(".label-" + res[1]).css('color', 'red');
                // return false;
            } else {
                if (linked != 1 || linked != '1') {
                    allVal.push({
                        prdToOptionID: res[1],
                        valueID: valueid
                    });
                }
                // selected_options.push(radioVal);
                //  jQuery("#label-" + res[1]).css('color', '');
                jQuery(".label-" + res[1]).css('color', '');
            }
        }
        if (mytype == 'dropdown') {
            var res = ids[i].split("-");
            var dropdownVal = jQuery("#" + ids[i]).val();
            // alert(dropdownVal);

            var valueid = jQuery("#" + ids[i]).find("option:selected").attr('valueid');
            // alert(valueid);
            if (dropdownVal == '' || dropdownVal == 'undefined') {
                // alert('Please select from ' + name);
                flag = false;
                // jQuery("#label-" + res[1]).css('color', 'red');
                jQuery(".label-" + res[1]).css('color', 'red');
                // return false;
            } else {
                if (linked != 1 || linked != '1') {
                    allVal.push({
                        prdToOptionID: res[1],
                        valueID: valueid
                    });
                }
                // selected_options.push(dropdownVal);
                // jQuery("#label-" + res[1]).css('color', '');
                jQuery(".label-" + res[1]).css('color', '');
            }
        }
        if (mytype == 'checkbox') {
            var checkboxValues = '';
            // var checkboxValues = [];
            // var checkboxVal = [];
            var res = ids[i].split("-");
            var checkboxVal = jQuery("input[id=" + ids[i] + "]:checked").val();
            //var valueid = jQuery("input[id=" + ids[i] + "]:checked").attr('valueid');
            // alert(valueid);
            jQuery("input[id=" + ids[i] + "]:checked").each(function () {
                // console.log(this.value);
                // checkboxValues.push(this.value);
                //  var valueid = jQuery("input[id=" + ids[i] + "]:checked").attr('valueid');
                var valueid = jQuery(this).attr('valueid');
                if (linked != 1 || linked != '1') {
                    allVal.push({
                        prdToOptionID: res[1],
                        valueID: valueid
                    });
                }
                // checkboxValues += this.value + '@';
            });
            //checkboxValues = checkboxValues.slice(0, -1);

            if (checkboxVal == '' || checkboxVal == 'undefined' || !checkboxVal) {
                //alert('Please select from ' + name);
                flag = false;
                // jQuery("#label-" + res[1]).css('color', 'red');
                jQuery(".label-" + res[1]).css('color', 'red');
                // return false;
            } else {

                //selected_options.push(checkboxValues);
                //jQuery("#label-" + res[1]).css('color', '');
                jQuery(".label-" + res[1]).css('color', '');
            }
        }

        //alert(flag);

    }
    if (flag == false) {
        alert(Zikula.__('Please select available options', 'module_zselex_js'));
        return false;
    }
    //alert(allVal.length);
    var toJson = '';
    if (allVal.length > 0) {
        toJson = JSON.stringify(allVal);
    }
    //alert(toJson);
    //selectedOptionString = selected_options.join('|');
    selectedOptionString = toJson;


    //alert(selectedOptionString);  return false;

    //return false;
    return true;

}


function checkQuestion() {

    if (!document.getElementById('ques_validate')) {
        return true;
    }
    var ques_validate = jQuery('#ques_validate').val();
    // alert(ques_validate);
    if (ques_validate == false) {
        return true;
    }
    var ques_ans = jQuery('#ques_ans').val();
    // alert(ques_ans.length); exit();
    if (ques_ans.length == 0) {
        jQuery(".quesLabel").css('color', 'red');
        alert(Zikula.__('Please answer question', 'module_zselex_js'));
        return false;
    } else {
        jQuery(".quesLabel").css('color', '');
        return true;
    }
}






function checkedOptions1() {
    var flag = true;
    var id;
    var ids = [];
    var allVal = [];
    var tempId;
    jQuery('.options_select').each(function () {
        if (this.id != tempId) {
            ids.push(this.id);
        }
        tempId = this.id;
    });
    //alert(ids.length);
    var selected_options = [];
    for (var i = 0; i <= ids.length; i++) {
        // alert(ids[i]);
        var mytype = jQuery("#" + ids[i]).attr('mytype');

        //alert(mytype);
        //  alert(valueid);
        var name = jQuery("#" + ids[i]).attr('name');
        if (mytype == 'radio') {
            var res = ids[i].split("-");
            var radioVal = jQuery("input[id=" + ids[i] + "]:checked").val();
            var valueid = jQuery("input[id=" + ids[i] + "]:checked").attr('valueid');
            //alert(valueid);
            // alert(radioVal);
            if (radioVal == '' || radioVal == 'undefined' || !radioVal) {
                // alert('Please select from ' + name);
                flag = false;
                jQuery("#label-" + res[1]).css('color', 'red');
                // return false;
            } else {
                allVal.push({
                    prdToOptionID: res[1],
                    valueID: valueid
                });
                // selected_options.push(radioVal);
                jQuery("#label-" + res[1]).css('color', '');
            }
        }
        if (mytype == 'dropdown') {
            var res = ids[i].split("-");
            var dropdownVal = jQuery("#" + ids[i]).val();
            // alert(dropdownVal);

            var valueid = jQuery("#" + ids[i]).find("option:selected").attr('valueid');
            // alert(valueid);
            if (dropdownVal == '' || dropdownVal == 'undefined') {
                // alert('Please select from ' + name);
                flag = false;
                jQuery("#label-" + res[1]).css('color', 'red');
                // return false;
            } else {
                allVal.push({
                    prdToOptionID: res[1],
                    valueID: valueid
                });
                // selected_options.push(dropdownVal);
                jQuery("#label-" + res[1]).css('color', '');
            }
        }
        if (mytype == 'checkbox') {
            var checkboxValues = '';
            // var checkboxValues = [];
            // var checkboxVal = [];
            var res = ids[i].split("-");
            var checkboxVal = jQuery("input[id=" + ids[i] + "]:checked").val();
            //var valueid = jQuery("input[id=" + ids[i] + "]:checked").attr('valueid');
            // alert(valueid);
            jQuery("input[id=" + ids[i] + "]:checked").each(function () {
                // console.log(this.value);
                // checkboxValues.push(this.value);
                //  var valueid = jQuery("input[id=" + ids[i] + "]:checked").attr('valueid');
                var valueid = jQuery(this).attr('valueid');
                allVal.push({
                    prdToOptionID: res[1],
                    valueID: valueid
                });
                // checkboxValues += this.value + '@';
            });
            //checkboxValues = checkboxValues.slice(0, -1);

            if (checkboxVal == '' || checkboxVal == 'undefined' || !checkboxVal) {
                //alert('Please select from ' + name);
                flag = false;
                jQuery("#label-" + res[1]).css('color', 'red');
                // return false;
            } else {

                //selected_options.push(checkboxValues);
                jQuery("#label-" + res[1]).css('color', '');
            }
        }

        //alert(flag);

    }
    if (flag == false) {
        alert(Zikula.__('Please select available options', 'module_zselex_js'));
        return false;
    }
    //alert(allVal.length);
    var toJson = '';
    if (allVal.length > 0) {
        toJson = JSON.stringify(allVal);
    }
    //alert(toJson);
    //selectedOptionString = selected_options.join('|');
    selectedOptionString = toJson;


    //alert(selectedOptionString);  return false;

    //return false;
    return true;

}


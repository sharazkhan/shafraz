
function changeProductStatus(prdId, status) {
    //alert(prdId); exit();

    if (status == 0) {
        document.getElementById('pstatus' + prdId).innerHTML = '<img src=images/icons/extrasmall/redled.png >';
    } else {
        document.getElementById('pstatus' + prdId).src = '<img src=images/icons/extrasmall/greenled.png >';
    }
}


/*jQuery(".prodTr").live('mouseover', function() {
 // alert(this.id);
 //jQuery("#edit" + this.id).addClass(".hideEdit");
 //jQuery("#edit" + this.id).removeClass("edit");
 jQuery(".editth").css("display", "block");
 jQuery("#edit" + this.id).addClass("ShowEdit");
 jQuery(".ShowEdit").css("display", "block");
 });
 
 jQuery(".prodTr").live('mouseout', function() {
 //alert(this.id);
 jQuery("#edit" + this.id).removeClass("ShowEdit");
 jQuery(".edit").css("display", "none");
 jQuery(".editth").css("display", "none");
 });*/
var noPopUp = true;
var edit_prd;

jQuery(".prd_status").live('mouseover', function () {
    jQuery("#tts").val(this.id);

});
jQuery(".prd_status").live('mouseout', function () {
    jQuery("#tts").val('');

});

jQuery(".prod_ids").live('mouseover', function () {
    //  alert(this.id);
    jQuery("#tts").val(this.id);

});

jQuery(".prod_ids").live('mouseout', function () {
    jQuery("#tts").val('');

});
jQuery(".prd_status").live('click', function () {

    if (!confirm(Zikula.__('Do you really want to change the status?', 'module_zselex_js')) == true)
        return false;
    else
        var id = this.id
    var status = jQuery(this).attr('setstat');
    // alert(id);
    // alert(status);
    if (status == 0) {
        jQuery("#pstatus" + id).html('<img style="cursor:pointer" class="prd_status" src=images/icons/extrasmall/redled.png id="' + id + '" setstat=1>');
    } else {
        jQuery("#pstatus" + id).html('<img style="cursor:pointer" class="prd_status" src=images/icons/extrasmall/greenled.png id="' + id + '" setstat=0>');
    }

    var pars = "module=ZSELEX&type=ajax&func=setProductStaus&status=" + status + "&product_id=" + id;
    // alert(pars);
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars
                        //async: true,
                        //onComplete:productStatusResponses
            });

});



/*jQuery("#iname").live('click', function() {
 jQuery(".inc").append('<div><input autocomplete="off" type="text" name=formElements[optionValues][]><a href="#" class="remove_this btn btn-danger">&nbsp;&nbsp;' + Zikula.__("remove", 'module_zselex_js') + '</a><br></div>');
 return false;
 });
 
 
 jQuery(".remove_this").live('click', function() {
 jQuery(this).parent().remove();
 return false;
 });*/

jQuery("#iname").live('click', function () {
    jQuery(".inc").append('<div>' + Zikula.__('value', 'module_zselex_js') + ': <input class="poptval" autocomplete="off" type="text" name=formElements[optionValues][val][]> ' + Zikula.__('sort order', 'module_zselex_js') + ': <input size="3" autocomplete="off" type="text" name=formElements[optionValues][sort_order][]><a href="#" class="remove_this btn btn-danger">&nbsp;&nbsp;' + Zikula.__("remove", 'module_zselex_js') + '</a><br></div>');
    return false;
});


jQuery(".remove_this").live('click', function () {
    jQuery(this).parent().remove();
    return false;
});

jQuery("#addC").live('click', function () {

    jQuery('#optionToClone').clone().appendTo(".prd_option").show();

});
jQuery(".remove_option").live('click', function () {
    jQuery(this).parent().parent().remove();
    return false;
});


function checkOptValue() {
    var optError = false;
    if (!jQuery(".poptval").hasClass("poptval")) {
        optError = false;
    }
    jQuery(".poptval").each(function () {
        if (this.value != '') {
            optError = true;
            return optError;
        }
    });
    return optError;
}



jQuery(".optionSubmit").live('click', function () {
    validOpt = new Validation('optForm', {useTitles: true, onSubmit: false});
    var result = validOpt.validate();
    if (!result) {
        return false;

    }
    if (checkOptValue() == false) {
        //alert(Zikula.__('value not entered!', 'module_zselex_js'));
        if (confirm(Zikula.__('Values are missing! Do you still want to save and exit?', 'module_zselex_js')) == true) {
            document.forms['optForm'].submit();
            return true;
        } else {
            return false;
        }
    } else {
        document.forms['optForm'].submit();
    }

});


jQuery(".mnfrSubmit").live('click', function () {
    validOpt = new Validation('mnfrForm', {useTitles: true, onSubmit: false});
    var result = validOpt.validate();
    if (!result) {
        return false;

    }
    document.forms['mnfrForm'].submit();

});

jQuery(".prodCatSubmit").live('click', function () {
    validOpt = new Validation('prodCatForm', {useTitles: true, onSubmit: false});
    var result = validOpt.validate();
    if (!result) {
        return false;

    }
    document.forms['prodCatForm'].submit();

});





var idx;


var slectedOptionsFinal = [];
jQuery(".optionList").live('change', function () {
//alert('hiii');
//alert(this.text());
    // slectedOptions = [];
    if (this.value == 0) {

        return;
    }
    slectedOptionsFinal = [];
    var slectedOptions = [];
    var tempId;
    jQuery(".selOptionId").each(function () {
        //alert(this.value);
        if (this.value != tempId) {
            slectedOptions.push(this.value);
            //  slectedOptionsFinal.push(this.value);
        }
        tempId = this.value;


    });

    slectedOptionsFinal = slectedOptionsFinal.concat(slectedOptions);

    var name = jQuery(this).find("option:selected").text();
    // alert(name);
    var val = this.value;
    if (val == 0) {
        // jQuery(this).parent().find(".innerC").html('');
        return;
    }
    var productId = jQuery('#product_id').val();
    // alert(productId);
    jQuery('.busyOpt').html('<b>Please wait..</b>');
//alert(val);
    //jQuery(this).find(".innerC").html("TESTING...");
    var allCount = jQuery('.innerCsub').length;
    //jQuery('#hiddeKey').val(allCount);

    var pars = "module=ZSELEX&type=ajax&func=getProductOption&option_id=" + val + "&product_id=" + productId + "&key=" + allCount;
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                onComplete: optionResponses
            });


    // jQuery(this).parent().find(".innerC").html('loading...');
    // idx = jQuery(this).parent().find(".innerC");

});


function optionResponses(req)
{
    // alert('hellooo');   exit();
    // idx.html('working!...');    exit();
    //idx.html('working2!...');


    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }



    var json = pndejsonize(req.responseText);


    //  idx.html(json.data);
    jQuery('.busyOpt').html('');
    // alert(json.option_id);

    if (json.exist == true) {
        // alert(Zikula.__('This option is already selected', 'module_zselex_js'));
        //return;
    }
    //var temp;
    for (var i = 0; i <= slectedOptionsFinal.length; i++) {
        if (json.option_id > 0) {
            // alert(slectedOptionsFinal[i]);
            if (json.option_id == slectedOptionsFinal[i]) {
                alert(Zikula.__('You have already selected this option', 'module_zselex_js'));
                return;
            }
        }
    }

    if (json.option_id > 0) {
        //slectedOptionsFinal.push(json.option_id);
    }
    jQuery('.innerC').append(json.data).show();
    //var eqtabs2 = new Zikula.UI.Tabs('tabs_example_eq1');


}






function editProduct(id, source) {

    //alert(id);
    //alert(noPopUp);
    //location.href = '#edit_id='+id;
    if (jQuery("#tts").val() == id) {
        return;
    }
    var src;
    if (source == '' || source == 'undefined' || source == undefined) {
        src = '';
    } else {
        src = source;
    }
    var product_id = id
    // alert(product_id);
    var shop_id = document.getElementById("shop_id").value;
    var startnum = document.getElementById("startnum").value;
    //alert(shop_id);
    // alert(startnum);
    // document.getElementById("backshield").style.display = "block";
    jQuery('#backshield').attr('style', 'display: block !important');

    document.getElementById("editProductOuter").style.display = "block";
    document.getElementById("editProduct").style.display = "table";
    document.getElementById("editProduct").innerHTML = "<font size=3>" + Zikula.__('Loading...', 'module_zselex_js') + "</font>";
    //document.getElementById("image_id").value=imageID;

    var pars = "module=ZSELEX&type=PopUp&func=showProductPopUp&product_id=" + product_id + "&shop_id=" + shop_id + "&startnum=" + startnum + '&src=' + src;
    //alert(pars); 
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                onComplete: editProductResponses
            });

    return false;


}

var date_attr = 0;
function editProductResponses(req)
{
    //alert('hellooo');
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        //alert(Zikula.__('some error occured!', 'module_zselex_js'));
        return;
    }

    edit_prd = true;

    var json = pndejsonize(req.responseText);
    //alert(json.noproduct);
    if (json.noproduct) {
        closeWindow();
        alert(Zikula.__('This product does not exist!', 'module_zselex_js'));
        return;
    }

    //jQuery("#editProduct").center();
    //  jQuery('#editProduct').centerMe();
    document.getElementById('editProduct').innerHTML = json.product;
    var marginTop = parseInt(jQuery(window).scrollTop()) + parseInt(50);
    jQuery("#editProduct").css("margin-top", marginTop + 'px');
    date_attr = jQuery("#qty_discounts_count").val();
    date_attr = date_attr++;
    //alert(date_attr);
    var eqtabs = new Zikula.UI.Tabs('tabs_example_eq');
    jQuery(".mcategory").dropdownchecklist({emptyText: Zikula.__('select category', 'module_zselex_js'), icon: {}, width: 190, maxDropHeight: 300});
// location.href = "#editImage";


}


jQuery().ready(function () {


    jQuery('#select_all').click(function (event) {  //on click 
        if (this.checked) { // check select status
            jQuery('.prod_ids').each(function () { //loop through each checkbox
                this.checked = true;  //select all checkboxes"               
            });
        } else {
            jQuery('.prod_ids').each(function () { //loop through each checkbox
                this.checked = false;  //deselect all checkboxes"               
            });
        }
    });

    jQuery('#select_type').change(function (event) {

        if (!ProductIdSelected()) {
            alert(Zikula.__('You have not selected any products', 'module_zselex_js'));
            jQuery('select#select_type option').each(function () {
                this.selected = (this.text == '0');
            });
            return;
        }
        if (confirm(Zikula.__('Do you really want to delete?', 'module_zselex_js')) == true) {
            document.forms['product_form'].submit();
            // return true;
        } else {
            return false;
        }

    });
});


function ProductIdSelected() {
    var shopArray = [];
    jQuery(".prod_ids:checked").each(function () {
        shopArray.push(jQuery(this).val());
    });

    var selected_sid = shopArray.join(',');
    if (selected_sid == '') {
        return false;
    } else {
        return true;
    }
}


function setDiscountPrice(orig_Price, discount) {
    //alert(orig_Price); exit();
    // alert(discount); exit();
    //var m = accounting.unformat("€"+value , ",");
    var origPrice1 = orig_Price;
    //var origPrice = accounting.unformat("DKK"+origPrice1 , ",");
    var origPrice = origPrice1;
    //var test = accounting.unformat("€ 1.000.000,00", ","); // 1000000
    //alert(origPrice); exit();
    if (origPrice > 0) {
        var disPrice;
        var newVal;
        var value = discount;
        //alert(value1);

        var lastChar = value.substr(-1);
        //alert(lastChar);
        if (lastChar == '%') {
            // disPrice = 
            newVal = value.slice(0, -1);
            // alert(newVal);
            disPrice = origPrice - (origPrice * newVal / 100);
            //alert(disPrice);
        } else {
            //disPrice = ((origPrice - value) / origPrice ) * 100;
            disPrice = origPrice - value
        }
        //alert(disPrice);
        // var newPrice1 = accounting.formatMoney(disPrice, "DKK", 2, ".", ","); // €4.999,99
        //var newPrice = removeSring(newPrice1,3)
        var newPrice = disPrice;
        jQuery("#discount_val").css("display", "block");
        jQuery("#prd_price").val(newPrice);
    } else {
        jQuery("#prd_price").val(origPrice);
    }

}




function removeSring(val, num) {
    var str = val.slice(num);
    return str;
}


function closeWindow() {
    //location.href = '#';
    document.getElementById("editProductOuter").style.display = "none";
    document.getElementById('editProduct').style.display = 'none';
    jQuery('#backshield').attr('style', 'display: none !important');
    edit_prd = false;

}

function closeCatWindow() {
    document.getElementById('editCategory').style.display = 'none';
    document.getElementById('editManufacturer').style.display = 'none';
    document.getElementById('showProducts').style.display = 'none';
    // document.getElementById("backshield").style.display="none";
    if (edit_prd) {
        // document.getElementById('editProduct').style.display = 'block';
        document.getElementById('editProduct').style.display = 'table';
    }


}



function editCategory(id) {
    //alert(id); exit();
    var catId = id;
    document.getElementById('editProduct').style.display = 'none';
    // alert(imageID);
    var shop_id = document.getElementById("shop_id").value;
    // alert(shop_id);
    document.getElementById("backshield").style.display = "block";
    document.getElementById("editCategory").style.display = "block";
    document.getElementById("editCategory").innerHTML = "<font size=3>" + Zikula.__('Loading...', 'module_zselex_js') + "</font>";
    //document.getElementById("image_id").value=imageID;

    var pars = "module=ZSELEX&type=PopUp&func=productCategory&event_id=" + catId + "&shop_id=" + shop_id;
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                onComplete: editCategoryResponses
            });


}


function editCategoryResponses(req)
{
    //alert('hellooo');
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }

    var json = pndejsonize(req.responseText);
    //var eventId = "drophere"+json.event_id;
    //alert(eventId);

    document.getElementById('editCategory').innerHTML = json.data;
// triggerFoldings();
//  location.href = "#"+eventId;

}


function editManufacturer() {
    //alert(id); exit();
    //var catId = id;
    document.getElementById('editProduct').style.display = 'none';
    // alert(imageID);
    var shop_id = document.getElementById("shop_id").value;
    // alert(shop_id);
    document.getElementById("backshield").style.display = "block";
    document.getElementById("editManufacturer").style.display = "block";
    document.getElementById("editManufacturer").innerHTML = "<font size=3>" + Zikula.__('Loading...', 'module_zselex_js') + "</font>";
    //document.getElementById("image_id").value=imageID;

    var pars = "module=ZSELEX&type=PopUp&func=manufacturerForm&&shop_id=" + shop_id;
    //alert(pars); exit();
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                onComplete: editManufacturerResponses
            });


}


function editManufacturerResponses(req)
{
    //alert('hellooo');
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }

    var json = pndejsonize(req.responseText);
    //var eventId = "drophere"+json.event_id;
    //alert(eventId);

    document.getElementById('editManufacturer').innerHTML = json.data;
// triggerFoldings();
//  location.href = "#"+eventId;

}




function copyProduct(id) {
    // alert(id);
    var shop_id = document.getElementById("shop_id").value;
    document.getElementById('editProduct').style.display = 'none';
    document.getElementById('showProducts').style.display = 'block';
    document.getElementById("showProducts").innerHTML = "<font size=3>" + Zikula.__('Loading...', 'module_zselex_js') + "</font>";
    var pars = "module=ZSELEX&type=PopUp&func=copyProduct&product_id=" + id + "&shop_id=" + shop_id;
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                onComplete: copyProductResponses
            });
    return false;
}

function copyProductResponses(req)
{
    // alert('hellooo'); exit();
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        // jQuery('#showProducts').html(Zikula.__('An error has occured. Please try again later', 'module_zselex_js'));
        return;
    }

    var json = pndejsonize(req.responseText);
    document.getElementById('showProducts').innerHTML = json.data;

}

function confirmation()
{
    if (confirm(Zikula.__('Do you really want to copy?', 'module_zselex_js')) == true)
        return true;
    else
        return false;
}

function saveCopyProduct() {

    if (confirmation()) {

        var product_id = jQuery('#copy_prd_id').val();
        var curr_prd_id = jQuery('#curr_prd_id').val();
        var shop_id = jQuery('#shop_id').val();

        // alert(product_id); return false;
        // alert(shop_id);
        // alert(curr_prd_id); return false;

        jQuery('#showProducts').html("<font size=3>" + Zikula.__('Copying...', 'module_zselex_js') + "</font>");

        var pars = "module=ZSELEX&type=PopUp&func=saveCopyProduct&product_id=" + product_id + "&shop_id=" + shop_id + "&curr_product_id=" + curr_prd_id;
        var myAjax = new Ajax.Request(
                "ajax.php",
                {
                    method: 'get',
                    parameters: pars,
                    async: true,
                    onComplete: saveCopyProductResponses
                });
    }

    return false;
}

function saveCopyProductResponses(req)
{
    //alert('hellooo');
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        // jQuery('#showProducts').html(Zikula.__('An error has occured. Please try again later', 'module_zselex_js'));
        return;
    }


    jQuery('#showProducts').html("<font color=green size=3>" + Zikula.__('Done! Product Copied Successfully!', 'module_zselex_js') + "</font>");

    var json = pndejsonize(req.responseText);
    setTimeout(function () {
        // Do something after 1 second
        document.getElementById('showProducts').style.display = 'none';
        //document.getElementById('editProduct').style.display = 'none';
        document.getElementById('editProduct').style.display = 'table';
        editProduct(json.curr_product_id);
    }, 1000);

    // document.getElementById("backshield").style.display="none";
    /*document.getElementById('showProducts').style.display = 'none';
     document.getElementById('editProduct').style.display = 'none';
     editProduct(json.curr_product_id);*/

}




function createManufacturer() {
    //alert('hellooo');
    var name = jQuery('#pmnfr_name').val();
    var shopId = jQuery('#shop_id').val();
    var ownerId = jQuery('#owner_id').val();
    var status = jQuery('#mnfr_status').val();
    //alert(status); exit();
    //document.getElementById('editProduct').style.display='block';
    // document.getElementById('testing').value = 'hello world';
    if (name == '') {
        alert(Zikula.__('Please enter a manufacturer name!', 'module_zselex_js'));
        return;
    }
    var pars = "module=ZSELEX&type=PopUp&func=saveManufacturer&name=" + name + "&shopId=" + shopId + "&ownerId=" + ownerId + "&status=" + status;
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                onComplete: createManufacturerResponses
            });
    return false;

}

function createManufacturerResponses(req)
{
    //alert('hellooo');
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }

    var json = pndejsonize(req.responseText);
    if (json.exist) {
        alert(Zikula.__('This manufacturer already exist!', 'module_zselex_js'));
        return;
    }
    document.getElementById('editManufacturer').style.display = 'none';
    // document.getElementById("backshield").style.display="none";
    document.getElementById('editProduct').style.display = 'table';
    document.getElementById('listManuf').innerHTML = json.manufacturer;

}



function createCategory() {
    //alert('hellooo');
    var catName = jQuery('#pcat_name').val();
    //alert(catName); exit();
    var shopId = jQuery('#shop_id').val();
    var productId = jQuery('#product_id').val();
    var ownerId = jQuery('#owner_id').val();
    var status = jQuery('#status').val();

    //alert(productId); exit();
    //document.getElementById('editProduct').style.display='block';
    // document.getElementById('testing').value = 'hello world';
    if (catName == '') {
        alert(Zikula.__('Please enter a category name!', 'module_zselex_js'));
        return;
    }
    var pars = "module=ZSELEX&type=PopUp&func=saveCategory&catName=" + catName + "&shopId=" + shopId + "&ownerId=" + ownerId + "&status=" + status + "&product_id=" + productId;
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                onComplete: createCategoryResponses
            });
    return false;

}

function createCategoryResponses(req)
{
    //alert('hellooo');
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }

    var json = pndejsonize(req.responseText);
    if (json.exist) {
        alert(Zikula.__('This category already exist!', 'module_zselex_js'));
        return;
    }

    //var eventId = "drophere"+json.event_id;
    //  alert(json.cat); 
    document.getElementById('editCategory').style.display = 'none';
    // document.getElementById("backshield").style.display="none";
    document.getElementById('editProduct').style.display = 'table';
    document.getElementById('listCat').innerHTML = json.cat;
    jQuery(".mcategory").dropdownchecklist({emptyText: Zikula.__('select category', 'module_zselex_js'), icon: {}, width: 190, maxDropHeight: 300});

}

function deleteProduct() {

//alert('helloooo');
    if (confirm(Zikula.__('Do you really want to delete this product?', 'module_zselex_js')) == true) {
        // jQuery('#prdc_del').val('deleteproduct');
        //  document.forms['prod_popup_form'].submit();
        document.forms['proddelete_popup_form'].submit();
        //return true;
    } else {
        return false;
    }
}



function deleteExtraProducts() {
    //document.getElementById('drop_images').style.display='none';
    //document.getElementById('images_display').innerHTML = "Updating Images...";
    var shop_id = document.getElementById('shop_id').value;

    var pars = "module=ZSELEX&type=dnd&func=deleteExtraProducts&shop_id=" + shop_id + "&ajax=1";
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                //async: true,
                // onComplete: getEmployeesResponses
            });

}

//alert('hiii'); exit();

//var date_attr = jQuery("#qty_discounts_count").val();

jQuery("#addQty").live('click', function () {
    var dat_attr_val = date_attr++;
    jQuery(".qtyTd").append(
            '<tr><td><input  class="poptval" autocomplete="off" type="text" name=formElements[qtydisount][qty][]></td><td><input class="poptval" autocomplete="off" type="text" name=formElements[qtydisount][discount][]></td><td><input class="qty_date start_date' + dat_attr_val + '"  autocomplete="off" type="text" name=formElements[qtydisount][startdate][] date_attr=' + dat_attr_val + '></td><td><input class="qty_date2 end_date' + dat_attr_val + '"  autocomplete="off" type="text" name=formElements[qtydisount][enddate][] date_attr=' + dat_attr_val + '></td><td><a href="#" class="remove_this2 btn btn-danger">' + Zikula.__("remove", "module_zselex_js") + '</td></tr>'
            );

    return false;
});
jQuery(".remove_this2").live('click', function () {
    jQuery(this).parent().parent().remove();
    return false;
});

jQuery(".qty_date").live('click', function () {
    //alert('hiiii');
    jQuery(this).datepicker({
        showOn: 'focus',
        dateFormat: "yy-mm-dd",
        firstDay: '1',
        onSelect: function (dateText, inst) {
            var start_date = jQuery(this).val();
            //alert(date);
            var num = jQuery(this).attr('date_attr');
            //alert(attr);
            var end_date = jQuery(".end_date" + num).val();
            //alert(start_date);
            if (end_date != '' && (end_date < start_date)) {
                jQuery(this).val('');
                jQuery(".end_date" + num).val('');
                alert(Zikula.__('End date should be greater than Start date', 'module_zselex_js'));
            }

        }
    }).focus();
});


jQuery(".qty_date2").live('click', function () {
    //alert('hiiii');
    jQuery(this).datepicker({
        showOn: 'focus',
        dateFormat: "yy-mm-dd",
        firstDay: '1',
        onSelect: function (dateText, inst) {
            var end_date = jQuery(this).val();
            //alert(date);
            var num = jQuery(this).attr('date_attr');
            //alert(num);
            var start_date = jQuery(".start_date" + num).val();
            //alert(start_date);
            if (start_date == '') {
                jQuery(this).val('')
                alert(Zikula.__('Start date cannot be empty', 'module_zselex_js'));
            } else if (end_date < start_date) {
                jQuery(this).val('')
                alert(Zikula.__('End date should be greater than Start date', 'module_zselex_js'));
            }

        }
    }).focus();
});



window.onkeyup = function (event) {

    if (event.keyCode == 27) {
        //alert('works fine!!!'); 
        closeCatWindow();
        //window.close ();
    }
}



function editSettings() {


    var shopId = document.getElementById("shop_id").value;
    //alert(shopId); exit();

    jQuery('#backshield').attr('style', 'display: block !important');

    document.getElementById("editProductOuter").style.display = "block";
    document.getElementById("editProduct").style.display = "table";
    document.getElementById("editProduct").innerHTML = "<font size=3>" + Zikula.__('Loading...', 'module_zselex_js') + "</font>";


    var pars = "module=ZSELEX&type=PopUp&func=editProductSetting&shop_id=" + shopId;
    // alert(pars); exit();
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                onComplete: editSettingsResponse
            });

    return false;


}


function editSettingsResponse(req)
{
    // alert('editSettingsResponse'); exit();
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        //alert(Zikula.__('some error occured!', 'module_zselex_js'));
        return;
    }



    var json = pndejsonize(req.responseText);
    //alert(json.noproduct);


    //jQuery("#editProduct").center();
    //  jQuery('#editProduct').centerMe();
    document.getElementById('editProduct').innerHTML = json.data;
    var marginTop = parseInt(jQuery(window).scrollTop()) + parseInt(50);
    jQuery("#editProduct").css("margin-top", marginTop + 'px');



}



function saveProductSettings() {
    // alert('saveProductSettings');
    jQuery('#save_bttn').css('display', 'none');
    jQuery('#save_msg').css('display', 'block');
    var formData = jQuery('#product_setting_form').serialize();
    // alert(formData); exit();

    var pars = "module=ZSELEX&type=PopUp&func=saveProductSettings&" + formData;
    // alert(pars); exit();
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'post',
                parameters: pars,
                async: true,
                onComplete: saveProductSettingsResponse
            });

    return false;
}


function saveProductSettingsResponse(req)
{
    // alert('saveProductSettingsResponse'); exit();
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        //alert(Zikula.__('some error occured!', 'module_zselex_js'));
        return;
    }



    var json = pndejsonize(req.responseText);
    //alert(json.noproduct);
    if (json.error) {
        pnshowajaxerror(Zikula.__('Some error occured. Please try later', 'module_zselex_js'));
        jQuery('#save_msg').css('display', 'none');
        jQuery('#save_bttn').css('display', 'block');

        return;
    }

    alert(Zikula.__('Settings saved successfully!', 'module_zselex_js'));
    closeWindow();



}


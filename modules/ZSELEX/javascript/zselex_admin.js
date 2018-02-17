function closeWindow() {
    document.getElementById('editImage').style.display = 'none';
    document.getElementById("backshield").style.display = "none";

}


window.onkeyup = function (event) {

    if (event.keyCode == 27) {
        //alert('works fine!!!'); 
        closeWindow();
        //window.close ();
    }
}

jQuery(document).ready(function () {

    function confirmation(ctext)
    {
        if (confirm(ctext) == true)
            return true;
        else
            return false;
    }


    var $scrollingDiv = jQuery("#updateBundles");
    jQuery(window).scroll(function () {
        $scrollingDiv
                .stop()
                .animate({
                    "marginTop": (jQuery(window).scrollTop() + 50) + "px"
                }, "fast");
    });


    jQuery('#select_all').click(function (event) {  //on click 
        if (this.checked) { // check select status
            jQuery('.shop_ids').each(function () { //loop through each checkbox
                this.checked = true;  //select all checkboxes"               
            });
        } else {
            jQuery('.shop_ids').each(function () { //loop through each checkbox
                this.checked = false;  //deselect all checkboxes"               
            });
        }
    });

    jQuery('#select_type').change(function (event) {

        if (!ShopIdSelected()) {
            alert(Zikula.__('You have not selected any shops', 'module_zselex_js'));
            jQuery('select#select_type option').each(function () {
                this.selected = (this.text == '0');
            });

            return;
        }

        // alert(this.value); exit();
        if (this.value == 'aff') {
            jQuery("#aff").css("display", "block");
            jQuery("#cat").css("display", "none");
            jQuery("#stat").css("display", "none");
            jQuery("#brnch").css("display", "none");
            jQuery("#group").css("display", "none");
        } else if (this.value == 'cat' || this.value == 'rm_cat') {
            jQuery("#aff").css("display", "none");
            jQuery("#stat").css("display", "none");
            jQuery("#cat").css("display", "block");
            jQuery("#brnch").css("display", "none");
            jQuery("#group").css("display", "none");
        } else if (this.value == 'brnch' || this.value == 'rm_brnch') {
            jQuery("#aff").css("display", "none");
            jQuery("#stat").css("display", "none");
            jQuery("#cat").css("display", "none");
            jQuery("#brnch").css("display", "block");
            jQuery("#group").css("display", "none");
        } else if (this.value == 'stat') {
            jQuery("#aff").css("display", "none");
            jQuery("#cat").css("display", "none");
            jQuery("#stat").css("display", "block");
            jQuery("#brnch").css("display", "none");
            jQuery("#group").css("display", "none");
        } else if (this.value == 'del') {
            jQuery("#aff").css("display", "none");
            jQuery("#cat").css("display", "none");
            jQuery("#stat").css("display", "none");
            jQuery("#brnch").css("display", "none");
            jQuery("#group").css("display", "none");

            if (!confirmation(Zikula.__('Do you really want to delete?', 'module_zselex_js'))) {
                jQuery('select#select_type option').each(function () {
                    this.selected = (this.text == '0');
                });

            } else {
                jQuery("#chg_del").prop("disabled", '');
                jQuery('#chg_del').val('1');
                document.forms['zselex_bulkaction_form'].submit();
            }

        } else if (this.value == 'rdemo') {
            /*
             jQuery("#aff").css("display", "none");
             jQuery("#cat").css("display", "none");
             jQuery("#stat").css("display", "none");
             jQuery("#chg_demo").prop("disabled", '');
             jQuery('#chg_demo').val('1');
             if (confirmation(Zikula.__('Do you really want to reactivate demoperiod?', 'module_zselex_js'))) {
             document.forms['zselex_bulkaction_form'].submit();
             }
             */
            //alert('hiii');
            var shopIds = getShopIds();
            // jQuery('#backshield').attr('style', 'display: block !important');
            jQuery('#backshield').show();
            document.getElementById("updateBundles").style.display = "block";
            document.getElementById("updateBundles").innerHTML = "<font size=3>" + Zikula.__('Loading...', 'module_zselex_js') + "</font>";
            var pars = "module=ZSELEX&type=PopUp&func=reactivateDemo&shopIds=" + shopIds;
            // alert(pars);
            var myAjax = new Ajax.Request(
                    "ajax.php",
                    {
                        method: 'post',
                        parameters: pars,
                        //  async: true,
                        onComplete: reactivateDemoResponses
                    });

        } else if (this.value == 'upbundle') {
            // alert('hellooo');
            var shopIds = getShopIds();
            // alert(shopIds); exit();
            // jQuery('#backshield').attr('style', 'display: block !important');
            jQuery('#backshield').show();
            document.getElementById("updateBundles").style.display = "block";
            document.getElementById("updateBundles").innerHTML = "<font size=3>" + Zikula.__('Loading...', 'module_zselex_js') + "</font>";
            var pars = "module=ZSELEX&type=PopUp&func=updateBundlesForShop&shopIds=" + shopIds;
            // alert(pars); exit();
            var myAjax = new Ajax.Request(
                    "ajax.php",
                    {
                        method: 'get',
                        parameters: pars,
                        async: true,
                        onComplete: updateBundleResponses
                    });

            // return false;
        } else if (this.value == 'group') {
            jQuery("#aff").css("display", "none");
            jQuery("#cat").css("display", "none");
            jQuery("#stat").css("display", "none");
            jQuery("#brnch").css("display", "none");
            jQuery("#group").css("display", "block");
        }

    });

    jQuery('#aff').change(function (event) {
        var affId = this.value;

        if (!ShopIdSelected()) {
            alert(Zikula.__('You have not selected any shops', 'module_zselex_js'));
            jQuery('select#aff option').each(function () {
                this.selected = (this.text == '0');
            });
            return;
        } else {
            jQuery("#chg_aff").prop("disabled", '');
            jQuery('#chg_cat').val('0');
            jQuery('#chg_aff').val(affId);
            jQuery('#chg_brnch').val('0');
            jQuery('#chg_group').val('0');
            if (confirmation(Zikula.__('Do you really want to change Affiliate?', 'module_zselex_js'))) {
                document.forms['zselex_bulkaction_form'].submit()
            }
        }
    });

    jQuery('#cat').change(function (event) {
        var catId = this.value;
        if (!ShopIdSelected()) {
            alert(Zikula.__('You have not selected any shops', 'module_zselex_js'));
            jQuery('select#cat option').each(function () {
                this.selected = (this.text == '0');
            });
            return;
        } else {
            jQuery("#chg_cat").prop("disabled", '');
            jQuery('#chg_cat').val(catId);
            jQuery('#chg_aff').val('0');
            jQuery('#chg_brnch').val('0');
            jQuery('#chg_group').val('0');
            if (jQuery('#select_type').val() == 'rm_cat') {
                var catAlert = Zikula.__('Do you really want to delete Category?', 'module_zselex_js');
            } else {
                var catAlert = Zikula.__('Do you really want to change Category?', 'module_zselex_js');
            }
            if (confirmation(catAlert)) {
                document.forms['zselex_bulkaction_form'].submit()
            }
        }
    });

    jQuery('#brnch').change(function (event) {
        // var catId = this.value;
        if (!ShopIdSelected()) {
            alert(Zikula.__('You have not selected any shops', 'module_zselex_js'));
            jQuery('select#brnch option').each(function () {
                this.selected = (this.text == '0');
            });
            return;
        } else {
            jQuery("#chg_brnch").prop("disabled", '');
            jQuery('#chg_brnch').val(this.value);
            jQuery('#chg_aff').val('0');
            jQuery('#chg_cat').val('0');
            jQuery('#chg_group').val('0');
            // alert(jQuery('#select_type').val()); exit();
            if (jQuery('#select_type').val() == 'rm_brnch') {
                var branchAlert = Zikula.__('Do you really want to delete Branch?', 'module_zselex_js');
            } else {
                var branchAlert = Zikula.__('Do you really want to change Branch?', 'module_zselex_js');
            }
            if (confirmation(branchAlert)) {
                document.forms['zselex_bulkaction_form'].submit()
            }
        }
    });


    jQuery('#stat').change(function (event) {
        var stat = this.value;
        if (!ShopIdSelected()) {
            alert(Zikula.__('You have not selected any shops', 'module_zselex_js'));
            jQuery('select#stat option').each(function () {
                this.selected = (this.text == '0');
            });
            return;
        } else {
            jQuery("#chg_stat").prop("disabled", '');
            jQuery('#chg_cat').val('0');
            jQuery('#chg_brnch').val('0');
            jQuery('#chg_aff').val('0');
            jQuery('#chg_stat').val(stat);
            jQuery('#chg_group').val('0');
            //exit();
            if (confirmation(Zikula.__('Do you really want to change Status?', 'module_zselex_js'))) {
                document.forms['zselex_bulkaction_form'].submit()
            }
        }
    });

    jQuery('#group').change(function (event) {
        var group = this.value;
        // alert(group); exit();
        if (!ShopIdSelected()) {
            alert(Zikula.__('You have not selected any shops', 'module_zselex_js'));
            jQuery('select#group option').each(function () {
                this.selected = (this.text == '0');
            });
            return;
        } else {
            jQuery("#chg_group").prop("disabled", '');
            jQuery('#chg_cat').val('0');
            jQuery('#chg_brnch').val('0');
            jQuery('#chg_aff').val('0');
            jQuery('#chg_stat').val('0');
            jQuery('#chg_group').val(group);
            //exit();
            if (confirmation(Zikula.__('Do you want to assign this group?', 'module_zselex_js'))) {
                document.forms['zselex_bulkaction_form'].submit()
            }
        }
    });



    function ShopIdSelected() {
        var shopArray = [];
        jQuery(".shop_ids:checked").each(function () {
            // alert(jQuery(this).val());
            shopArray.push(jQuery(this).val());
        });

        var selected_sid = shopArray.join(',');
        if (selected_sid == '') {
            return false;
        } else {
            return true;
        }
    }


    function getShopIds() {
        var shopArray1 = [];
        jQuery(".shop_ids1:checked").each(function () {
            shopArray1.push(jQuery(this).val());
        });

        var selected_sid1 = shopArray1.join(' , ');
        return selected_sid1;
    }

});


function updateBundleResponses(req)
{
    // alert('hellooo');  exit();

    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        //alert(Zikula.__('some error occured!', 'module_zselex_js'));
        return;
    }

    var json = pndejsonize(req.responseText);
    // alert(json.data); exit();
    document.getElementById('updateBundles').innerHTML = json.data;

}

function reactivateDemoResponses(req)
{
    //alert('reactivateDemoResponses');  exit();

    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        //alert(Zikula.__('some error occured!', 'module_zselex_js'));
        return;
    }

    var json = pndejsonize(req.responseText);
    // alert(json.data); exit();
    document.getElementById('updateBundles').innerHTML = json.data;

}


function updateBundles() {
    return false;
}


function closeWindow() {

    document.getElementById('updateBundles').style.display = 'none';
    jQuery('#backshield').attr('style', 'display: none !important');
    jQuery('select#select_type option').each(function () {
        this.selected = (this.text == '0');
    });


}

    




function editImage(id) {
    var imageID = id
    // alert(imageID);
    var shop_id = document.getElementById("shop_id").value;
    document.getElementById("backshield").style.display = "block";
    document.getElementById("editImage").style.display = "block";
    document.getElementById("editImage").innerHTML = "<font size=3>" + Zikula.__('Loading...', 'module_zselex_js') + "</font>";
    //document.getElementById("image_id").value=imageID;

    var pars = "module=ZSELEX&type=ajax&func=showImagePopUp&image_id=" + imageID + "&shop_id=" + shop_id;
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                onComplete: editImageResponses
            });


}


function editImageResponses(req)
{
    //alert('hellooo');
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }

    var json = pndejsonize(req.responseText);

    document.getElementById('editImage').innerHTML = json.data;
// location.href = "#editImage";
    var marginTop = parseInt(jQuery(window).scrollTop()) + parseInt(50);
    jQuery("#editImage").css("margin-top", marginTop + 'px');

}

/*
 jQuery().ready(function () {
 var $scrollingDiv = jQuery("#editImage");
 jQuery(window).scroll(function () {
 $scrollingDiv
 .stop()
 .animate({
 "marginTop": (jQuery(window).scrollTop() + 50) + "px"
 }, "fast");
 });
 
 var $cropDiv = jQuery("#cropImage");
 jQuery(window).scroll(function () {
 $cropDiv
 .stop()
 .animate({
 "marginTop": (jQuery(window).scrollTop() + 50) + "px"
 }, "fast");
 });
 });*/




function editEmployee(id) {
    var empID = id
    // alert(imageID);
    var shop_id = document.getElementById("shop_id").value;
    document.getElementById("backshield").style.display = "block";
    document.getElementById("editImage").style.display = "block";
    document.getElementById("editImage").innerHTML = "<font size=3>" + Zikula.__('Loading...', 'module_zselex_js') + "</font>";
    //document.getElementById("image_id").value=imageID;

    var pars = "module=ZSELEX&type=ajax&func=showEmployeePopUp&emp_id=" + empID + "&shop_id=" + shop_id;
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                onComplete: editEmployeeResponses
            });


}


function editEmployeeResponses(req)
{
    //alert('hellooo');
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }

    var json = pndejsonize(req.responseText);

    document.getElementById('editImage').innerHTML = json.data;
//location.href = "#editImage";
    var marginTop = parseInt(jQuery(window).scrollTop()) + parseInt(50);
    jQuery("#editImage").css("margin-top", marginTop + 'px');
}



function editAnnouncement() {

    // alert(imageID);
    var shop_id = document.getElementById("shop_id").value;
    document.getElementById("backshield").style.display = "block";
    document.getElementById("editImage").style.display = "block";
    document.getElementById("editImage").innerHTML = "<font size=3>" + Zikula.__('Loading...', 'module_zselex_js') + "</font>";
    //document.getElementById("image_id").value=imageID;

    var pars = "module=ZSELEX&type=ajax&func=showAnouncementPopUp&shop_id=" + shop_id;
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                onComplete: editAnnouncementResponses
            });


}


function editAnnouncementResponses(req)
{
    //alert('hellooo');
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }
    jQuery(document).trigger("date_picker");
    var json = pndejsonize(req.responseText);

    document.getElementById('editImage').innerHTML = json.data;
// location.href = "#editImage";
    var marginTop = parseInt(jQuery(window).scrollTop()) + parseInt(50);
    jQuery("#editImage").css("margin-top", marginTop + 'px');


}

function closeWindow() {
    document.getElementById('editImage').style.display = 'none';
    document.getElementById('cropImage').style.display = 'none';
    document.getElementById('deleteShop').style.display = 'none';
    document.getElementById("backshield").style.display = "none";
    jQuery("#cropImage").css("height", '0px');
    jQuery("#cropImage").css("width", '500px');
    jQuery("#backshield").css("width", '100%');

}

function getImages() {
    // document.getElementById('drop_images').style.display='none';
    //document.getElementById('images_display').innerHTML = "Updating Images...";
    var shop_id = document.getElementById('shop_id').value;

    var pars = "module=ZSELEX&type=admin&func=loadMiniSiteImages&shop_id=" + shop_id + "&ajax=1";
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                //async: true,
                onComplete: getImagesResponses
            });

}

function getImagesResponses(req)
{
    //alert('hellooo');
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }

    var json = pndejsonize(req.responseText);
    // document.getElementById('drop_images').style.display='block';
    //alert(json.data);
    document.getElementById('images_display').innerHTML = json.data;
//document.getElementById('drop_images').style.display='block';
// jQuery('.ax-file-list li:first').remove();
// jQuery(".aj-frame").css("display","none");


}



function getBanner() {
    //document.getElementById('setselectbox').style.display='block';
    //  document.getElementById('drop_banner').style.display='none';
    var shop_id = document.getElementById('shop_id').value;

    var pars = "module=ZSELEX&type=admin&func=loadBanner&shop_id=" + shop_id + "&ajax=1";
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                //async: true,
                onComplete: getBannerResponses
            });

}

function getBannerResponses(req)
{
    //alert('hellooo');
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }

    var json = pndejsonize(req.responseText);
    document.getElementById('drop_banner').style.display = 'block';
    //alert(json.data);
    document.getElementById('load_banner').innerHTML = json.data;
//  jQuery('.ax-file-list li:first').remove();


}


function getEmployees() {
    //document.getElementById('drop_images').style.display='none';
    //document.getElementById('images_display').innerHTML = "Updating Images...";
    var shop_id = document.getElementById('shop_id').value;

    var pars = "module=ZSELEX&type=admin&func=loadEmployees&shop_id=" + shop_id + "&ajax=1";
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                //async: true,
                onComplete: getEmployeesResponses
            });

}


function getEmployeesResponses(req)
{
    //alert('hellooo');
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }

    var json = pndejsonize(req.responseText);
    document.getElementById('drop_employee').style.display = 'block';
    //alert(json.data);
    document.getElementById('employee_display').innerHTML = json.data;
//jQuery('.ax-file-list li:first').remove();

}


function deleteExtraEmployees() {
    //document.getElementById('drop_images').style.display='none';
    //document.getElementById('images_display').innerHTML = "Updating Images...";
    var shop_id = document.getElementById('shop_id').value;

    var pars = "module=ZSELEX&type=dnd&func=deleteExtraEmployees&shop_id=" + shop_id + "&ajax=1";
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                //async: true,
                // onComplete: getEmployeesResponses
            });

}


function deleteExtraImages() {
    //document.getElementById('drop_images').style.display='none';
    //document.getElementById('images_display').innerHTML = "Updating Images...";
    var shop_id = document.getElementById('shop_id').value;

    var pars = "module=ZSELEX&type=dnd&func=deleteExtraImages&shop_id=" + shop_id + "&ajax=1";
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                //async: true,
                // onComplete: getEmployeesResponses
            });

}







function uncheck()
{
    document.getElementById('mainshopyes').checked = false;
}
function check()
{
    document.getElementById('mainshopyes').checked = true;
}


/***** Calender Call ********/
jQuery(function () {
    jQuery("#startdate").live('click', function () {
        jQuery(this).datepicker({
            showOn: 'focus',
            dateFormat: "yy-mm-dd",
            firstDay: '1'
        }).focus();
    });

    jQuery("#enddate").live('click', function () {
        jQuery(this).datepicker({
            showOn: 'focus',
            dateFormat: "yy-mm-dd",
            firstDay: '1'
        }).focus();
    });
});
/***** Calender Call Ends ********/

function IsSpecialChar(strString)
//  check for valid SpecialChar strings
{
    var strValidChars = "<>@!#$%^&*()_+[]{}?:;|'\"\\,./~`-=";
    var strChar;
    var blnResult = true;

    if (strString.length == 0)
        return false;

    //  test strString consists of valid characters listed above
    for (i = 0; i < strString.length && blnResult == true; i++) {
        strChar = strString.charAt(i);
        if (strValidChars.indexOf(strChar) == -1) {
            blnResult = false;
        }
    }
    return blnResult;
}

function copyTime(open, close, ID) {
    //alert('hellooo');
    // alert(IsSpecialChar(strString));
    document.getElementById(ID + "_open").value = open;
    document.getElementById(ID + "_close").value = close;

}


function unlockTitle()
{
    if (jQuery("#urltitle").is(':disabled')) {
        jQuery("#urltitle").prop("disabled", "");
        //jQuery("#unlock").html(Zikula.__('lock', 'module_zselex_js'));
        jQuery("#unlock").html('');
    } else {
        //jQuery("#urltitle").prop("disabled", "disabled");
        // jQuery("#unlock").html(Zikula.__('unlock', 'module_zselex_js'));
    }
}


function deleteImage() {

    if (confirm(Zikula.__("Do you really want to delete this image?")) == true) {
        document.forms['imagedelete_form'].submit();
        return true;
    } else {
        return false;
    }
}

function deleteBanner() {

    if (confirm(Zikula.__("Do you really want to delete this banner?")) == true) {
        document.forms['bannerdelete_form'].submit();
        return true;
    } else {
        return false;
    }
}

function deleteEmployee() {

    if (confirm(Zikula.__("Do you really want to delete this employee?")) == true) {
        document.forms['employeedelete_form'].submit();
        return true;
    } else {
        return false;
    }
}



function closed(ID) {
    // alert(ID);
}

window.onkeyup = function (event) {

    if (event.keyCode == 27) {
        //alert('works fine!!!'); 
        // closeWindow();
        //window.close ();
    }
}


function bannerSetting(val) {

    // 
    //jQuery("#drophere"+textId).html('Updating Page...'); exit();
    var value = val;
    var shop_id = jQuery('#shop_id').val();
    // alert(value); exit();
    jQuery('#image_mode').val(value);
    /*
     if(value==2){
     jQuery("#crop_label").css("display", 'block');
     }
     else{
     jQuery("#crop_label").css("display", 'none');   
     }
     */


    var pars = "module=ZSELEX&type=shopajax&func=bannerSetting&shop_id=" + shop_id + "&value=" + value;
    // alert(pars); exit();
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                //async: false,
                // onComplete: bannerSettingResponse
            });


}

function bannerSettingResponse(req)
{
    //alert('hellooo');
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }
    // alert(req.responseText); exit();

    var data = pndejsonize(req.responseText);
    // alert(data); exit();
    //alert(data.count); exit();
    /*
     if(data.success){
     alert('Banner Setting Saved');
     }
     else{
     alert('Update Failed');
     }
     */
    if (!data.success) {
        alert('Update Failed');
    }


}


function cropImage() {
    // var imageID = id
    // alert(imageID);
    var shop_id = document.getElementById("shop_id").value;
    document.getElementById("backshield").style.display = "block";
    document.getElementById("cropImage").style.display = "block";
    document.getElementById("cropImage").innerHTML = "<font size=3>" + Zikula.__('Loading...', 'module_zselex_js') + "</font>";
    //document.getElementById("image_id").value=imageID;

    var pars = "module=ZSELEX&type=shopajax&func=cropImage&shop_id=" + shop_id;
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                onComplete: cropImageResponses
            });

    return false;


}


function cropImageResponses(req)
{
    //  alert('hellooo'); exit();
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }

    var json = pndejsonize(req.responseText);

    //jQuery("#cropImage").removeClass("basket_content");
    // jQuery("#cropImage").addClass("crop_content");
    var height = parseInt(json.height) + 20;
    var width = parseInt(json.width) + 20;
    // alert(width);
    jQuery("#cropImage").css("height", height + 'px');
    jQuery("#cropImage").css("width", width + 'px');
    if (jQuery('#image_mode').val() == 0) {
        jQuery("#backshield").css("width", width + 'px');
    }


    // jQuery("#cropImage").css("top", '0');
    jQuery("#cropImage").css("right", '0');
    jQuery("#cropImage").css("left", '0');
    jQuery("#cropImage").css("bottom", '0');
    jQuery("#cropImage").css("margin", 'auto');



    document.getElementById('cropImage').innerHTML = json.data;
    var marginTop = parseInt(jQuery(window).scrollTop()) + parseInt(50);
    jQuery("#cropImage").css("margin-top", marginTop + 'px');
    // jQuery(function () {

    jQuery('#cropbox').Jcrop({
        // aspectRatio: 1,
        onSelect: updateCoords
    });
    // });

// location.href = "#editImage";



}


function updateCoords(c)
{
    jQuery('#x').val(c.x);
    jQuery('#y').val(c.y);
    jQuery('#w').val(c.w);
    jQuery('#h').val(c.h);
}


function saveImage() {
    //alert('helloo');
    if (!parseInt(jQuery('#w').val())) {

        alert(Zikula.__('Please select a crop region then press save', 'module_zselex_js'));
        return false;
    }
    var imageMode = jQuery('#image_mode').val();
    // alert(imageMode); exit();
    jQuery('#crop_save').html(Zikula.__('Saving...', 'module_zselex_js')); //exit();

    var shop_id = document.getElementById("shop_id").value;
    var file_name = jQuery('#file_name').val();
    var x = jQuery('#x').val();
    var y = jQuery('#y').val();
    var w = jQuery('#w').val();
    var h = jQuery('#h').val();


    var pars = "module=ZSELEX&type=shopajax&func=saveImage&shop_id=" + shop_id + "&x=" + x + "&y=" + y + "&w=" + w + "&h=" + h + "&file_name=" + file_name + "&image_mode=" + imageMode;
    // alert(pars); exit();

    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                onComplete: saveImageResponse
            });
}


function saveImageResponse(req)
{
    //  alert('hellooo'); exit();
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }

    var json = pndejsonize(req.responseText);
    //alert(json.success); exit();
    location.href = "";


}



function saveStatistics() {

    // 
    //jQuery("#drophere"+textId).html('Updating Page...'); exit();

    jQuery("#stat_save").css("display", 'none');
    jQuery("#stat_save_msg").css("display", 'block')
    var shop_id = jQuery('#shop_id').val();
    //alert(shop_id); exit();
    // var purchaseStat = jQuery('#purchase_stat').val();
    var purchaseStat = jQuery('#purchase_stat').is(":checked");
    // alert(purchaseStat); exit();
    var emailPurchase = jQuery('#email_purchase').is(":checked");
    // alert(emailPurchase); exit();
    if (purchaseStat == true) {
        purchaseStat = 1;
    } else {
        purchaseStat = 0;
    }
    if (emailPurchase == true) {
        emailPurchase = 1;
    } else {
        emailPurchase = 0;
    }

//alert(purchaseStat); exit();



    var pars = "module=ZSELEX&type=shopajax&func=saveStatistics&shop_id=" + shop_id + "&purchaseStat=" + purchaseStat + "&emailPurchase=" + emailPurchase;
    // alert(pars); exit();
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                //async: false,
                onComplete: saveStatisticsResponse
            });


}

function saveStatisticsResponse(req)
{
    //alert('hellooo');
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }
    // alert(req.responseText); exit();

    var data = pndejsonize(req.responseText);

    if (!data.success) {
        alert('Update Failed');
    }
    //  alert('Success');
    jQuery("#stat_save_msg").css("display", 'none')
    jQuery("#stat_save").css("display", 'block');



}



function deleteShopRequest() {

    var shop_id = document.getElementById("shop_id").value;
    document.getElementById("backshield").style.display = "block";
    document.getElementById("deleteShop").style.display = "block";
    document.getElementById("deleteShop").innerHTML = "<font size=3>" + Zikula.__('Loading...', 'module_zselex_js') + "</font>";
    var pars = "module=ZSELEX&type=shopajax&func=deleteShopRequest&shop_id=" + shop_id;
    // alert(pars); exit();
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                onComplete: deleteShopRequestResponse
            });


}

function deleteShopRequestResponse(req)
{
    //alert('deleteShopRequestResponse'); exit();
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }

    var json = pndejsonize(req.responseText);
    //alert(json.status); exit();
    
    if (json.perm_error) {
        alert(Zikula.__("You dont have permission to delete this shop"));
        exit();
    }

    if (json.status < 1) {
        document.getElementById("backshield").style.display = "none";
        document.getElementById("deleteShop").style.display = "none";
        alert(Zikula.__("Deletion has already been requested"));
        exit();
    }
    document.getElementById('deleteShop').innerHTML = json.data;
// location.href = "#editImage";
    var marginTop = parseInt(jQuery(window).scrollTop()) + parseInt(50);
    jQuery("#deleteShop").css("margin-top", marginTop + 'px');

}

/**
 * Delete Shop
 * 
 * @returns void
 */
function deleteShopConfirm() {


    var shop_id = document.getElementById("shop_id").value;
    var deleteDesc = document.getElementById("delete_desc").value;
    // alert(shop_id); exit();
    if (deleteDesc == '') {
        alert(Zikula.__("Please specify a reason for deleting", 'module_zselex_js'));
        exit();
    }
    jQuery('#shop_delete').html(Zikula.__("Deleting...", 'module_zselex_js'));
    //   exit();

    var pars = "module=ZSELEX&type=shopajax&func=deleteShopConfirm&shop_id=" + shop_id + "&delete_desc=" + deleteDesc;
    // alert(pars); exit();
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'post',
                parameters: pars,
                async: true,
                onComplete: deleteShopConfirmResponse
            });


}


function deleteShopConfirmResponse(req)
{
    // alert('deleteShopConfirmResponse'); exit();
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }

    var json = pndejsonize(req.responseText);
    //alert(json.status); exit();

    if (json.perm_error) {
        alert(Zikula.__("You dont have permission to delete this shop"));
        exit();
    }
    if (json.success) {
        document.getElementById("backshield").style.display = "none";
        document.getElementById("deleteShop").style.display = "none";
        alert(Zikula.__("Delete request has been initiated successfully"));
        exit();
    } else {
        alert(Zikula.__("An error occured. Please try later"));
        exit();
    }



}





    
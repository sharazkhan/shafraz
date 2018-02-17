

function editEvent(id, src) {
    //alert(id); exit();
    var eventId = id
    // alert(imageID);
    var shop_id = document.getElementById("shop_id").value;
    document.getElementById("backshield").style.display = "block";
    document.getElementById("editEvent").style.display = "block";
    document.getElementById("editEvent").innerHTML = "<font size=3>" + Zikula.__('Loading...', 'module_zselex_js') + "</font>";
    //document.getElementById("image_id").value=imageID;

    var pars = "module=ZSELEX&type=ajax&func=showEventPopUp&event_id=" + eventId + "&shop_id=" + shop_id + '&src=' + src;
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                onComplete: editEventResponses
            });


}

//var validEvent;
function editEventResponses(req)
{
    //alert('hellooo');
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }

    var json = pndejsonize(req.responseText);
    var eventId = "drophere" + json.event_id;
    //alert(eventId);

    document.getElementById('editEvent').innerHTML = json.data;
    var marginTop = parseInt(jQuery(window).scrollTop()) + parseInt(50);
    jQuery("#editEvent").css("margin-top", marginTop+ 'px');
    triggerFoldings();
    //validEvent = new Validation('event_popup_form', {useTitles: true, onSubmit: false});
//  location.href = "#"+eventId;

}

function validateEvent() {
    // alert('event');  exit();
    var validEvent = new Validation('event_popup_form', {useTitles: true, onSubmit: false});
    var result = validEvent.validate();
    if (result) {
        jQuery('#event_action').val('saveevents');
        document.forms['event_popup_form'].submit();
    }
}

function triggerFoldings() {
    var panel = new Zikula.UI.Panels('panel', {
        headerSelector: '#eventheaders',
        headerClassName: 'z-panel-indicator',
        active: [0]

    });
//alert(jQuery("#editEvent").height());
}





function setActivationDate(date) {
    jQuery("#activation_date").val(date);
}


function closeWindow() {
    document.getElementById('editEvent').style.display = 'none';
    document.getElementById("backshield").style.display = "none";

}






/***** Calender Call ********/
jQuery(function () {
    jQuery("#startdate").live('click', function () {
        //alert('hiiii');
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

    jQuery("#activation_date").live('click', function () {
        jQuery(this).datepicker({
            showOn: 'focus',
            dateFormat: "yy-mm-dd",
            firstDay: '1'
        }).focus();
    });
});
/***** Calender Call Ends ********/


function checkImageSizes(height, width) {
    //alert(height + " , " + width);
    //if (height >= '300' && width >= '900')
    if (height >= 300 && width >= 900)
    {
        return true;
    }
    else {
        alert(Zikula.__('Please upload a image minimum of width=900 and height=300 in size', 'module_zselex_js'));
        document.getElementById("exclusive").checked = false;
    }
}

function deleteEvent() {

    if (confirm(Zikula.__("Do you really want to delete this event?")) == true) {
        //document.forms['event_popup_form'].submit();
        document.forms['eventdelete_popup_form'].submit();
        return true;
    }
    else {
        return false;
    }
}


function deleteExtraEvents() {
    //document.getElementById('drop_images').style.display='none';
    //document.getElementById('images_display').innerHTML = "Updating Images...";
    var shop_id = document.getElementById('shop_id').value;

    var pars = "module=ZSELEX&type=dnd&func=deleteExtraEvents&shop_id=" + shop_id + "&ajax=1";
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                //async: true,
                // onComplete: getEmployeesResponses
            });

}

window.onkeyup = function (event) {

    if (event.keyCode == 27) {
        //alert('works fine!!!'); 
        // closeWindow();
        //window.close ();
    }
}

    
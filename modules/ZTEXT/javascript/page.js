

function editPage(id, purpose) {
    // alert(id); exit();
    //jQuery("#editPages").html('<div id="editPage" class="basket_content" style="display:none"></div>');

    var textId = id
    var divId;
    if (purpose == 'new') {
        divId = id;
        textId = '';
    }
    // alert(imageID);
    //jQuery("#drophere"+textId).html('Updating Page...'); exit();
    var shop_id = jQuery("#shop_id").val();
    document.getElementById("backshield").style.display = "block";
    document.getElementById("editPage").style.display = "block";
    document.getElementById("editPage").innerHTML = "<font size=3>" + Zikula.__('Loading...', 'module_zselex_js') + "</font>";
    //document.getElementById("image_id").value=imageID;

    var pars = "module=ZTEXT&type=ajax&func=pagePopUp&text_id=" + textId + "&shop_id=" + shop_id + "&purpose=" + purpose + "&div_id=" + divId;
    //alert(pars); exit();
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                onComplete: editPageResponses
            });


}

//var validEvent;
function editPageResponses(req)
{
    //alert('hellooo');
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }

    var json = pndejsonize(req.responseText);
    //var eventId = "drophere" + json.event_id;
    //alert(eventId);

    document.getElementById('editPage').innerHTML = json.data;
    //triggerFoldings();
    //validEvent = new Validation('event_popup_form', {useTitles: true, onSubmit: false});
//  location.href = "#"+eventId;

    //triggerCkEditor();

    triggerTinyMceEditor();



}


function triggerCkEditor() {
    ckload = function () {

        var ZTEXTEditor = CKEDITOR.replace('ztext_bodytext', {
            toolbar: "Full",
            language: "en",
            skin: "kama",
            extraPlugins: 'autogrow,stylesheetparser,zikulapagebreak',
            removePlugins: 'resize',
            autoGrow_maxHeight: "400px",
            contentsCss: 'modules/Scribite/style/ckeditor/content.css',
            entities_greek: false,
            entities_latin: false

        });

    }
    ckload();
}

function triggerTinyMceEditor() {
    tinyMCE.init({
        mode: "textareas",
        theme: "advanced",
        language: "en",
        plugins: "",
        selector: "#ztext_bodytext",
        // theme_advanced_buttons3_add : "fullpage",
        content_css: Zikula.Config.baseURL + "modules/Scribite/style/tinymce/style.css",
        cleanup: true,
        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        theme_advanced_statusbar_location: "bottom",
        theme_advanced_resizing: true,
        // Default buttons available in the advanced theme
        theme_advanced_buttons1: "bold,italic,underline,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,outdent,indent,cut,copy,paste,undo,redo,link,unlink,image,cleanup",
        theme_advanced_buttons2: "code,anchor,fontselect,fontsizeselect,sub,sup,forecolor,backcolor,charmap,visualaid,blockquote,hr,removeformat,help",
        // Individual buttons configured in the module's settings
        theme_advanced_buttons3: "",
        // TODO: I really would like to split this into multiple row, but I do not know how
        //theme_advanced_buttons3 : "",

        // Skin options
        skin: "o2k7",
        skin_variant: "silver",
        plugin_insertdate_dateFormat: "%Y-%m-%d",
        plugin_insertdate_timeFormat: "%H:%M:%S",
        paste_auto_cleanup_on_paste: true,
        paste_convert_middot_lists: true,
        paste_strip_class_attributes: "all",
        paste_remove_spans: false,
        paste_remove_styles_if_webkit: true,
        valid_elements: "*[*]",
        invalid_elements: "applet,area,audio,base,basefont,bdo,big,button,canvas,code,command,datalist,del,dfn,dir,embed,figcaption,figure,footer,font,form,header,hgroup,iframe,input,ins,keygen,kbd,map,mark,menu,marquee,meter,nav,nobr,object,optgroup,option,output,param,progress,q,rp,rt,ruby,s,samp,script,section,select,small,source,strike,sup,textarea,thead,time,u,var,video,wbr",
        auto_focus: "ztext_bodytext",
        setup: function (ed) {
            ed.onKeyUp.add(function (ed, e) {
                //alert('Key up event: ' + e.keyCode);
                jQuery('#text_edited').val('1');
            });
        }

        //toolbar_items_size: 'small',
        //height : "100",
        //width : "200"
    });
    // tinymce.get('ztext_bodytext').setContent(''); 
    //jQuery('.mceLayout').attr('style', 'width: 100px !important');



}



function validatePage() {
    // alert('event');  exit();
    var validPage = new Validation('page_popup', {useTitles: true, onSubmit: false});
    var result = validPage.validate();
    if (result) {
        //jQuery('#event_action').val('saveevents');
        //document.forms['event_popup_form'].submit();
        return true;
    }
    return false;
}




jQuery().ready(function () {

    var $scrollingDiv = jQuery("#editPage");

    jQuery(window).scroll(function () {
        //alert(jQuery("#editEvent").height());
        //jQuery("#sample").val(jQuery("#editEvent").height());
        //jQuery("#editEvent").css('height', jQuery("#editEvent").height()+"px");
        $scrollingDiv
                .stop()
                .animate({
                    "marginTop": (jQuery(window).scrollTop() + 50) + "px"
                }, "fast");
    });
});




function closeWindow() {
    jQuery('#editPage').html('');
    document.getElementById('editPage').style.display = 'none';
    //jQuery('#editPage').remove();
    document.getElementById("backshield").style.display = "none";

}
function get_content() {
//var content = tinyMCE.get('ztext_bodytext').getContent();

    var content = tinyMCE.activeEditor.getContent();
//alert(content);

    return content;
}

function savePage() {
    //alert('hellooo'); exit();
    var valid = validatePage();
    if (!valid)
        return false;
    jQuery('#pageSaveBtn').html('<input type=button value=Saving... disabled>');


    //var pform = jQuery('#page_popup').serialize();
    // alert(pform); exit();
    var headertext = jQuery('#headertext').val();
    //alert(catName); exit();
    var shopId = jQuery('#shop_id').val();
    var text_id = jQuery('#elemId').val();
    //var content = jQuery('#ztext_bodytext').val();
    // alert(jQuery('#ztext_bodytext').val()); exit();
    //var content = tinymce.get('ztext_bodytext').getContent();
    var text_edited = jQuery('#text_edited').val();
    var bodytext = jQuery('#ztext_bodytext_hidden').val();
    //alert(text_edited);
    if (text_edited == 1) {
        var content = get_content();
        //alert('cont1 :'+content); 
    }
    else {
        var content = bodytext;
        //alert('cont2 :'+content);
    }

    //alert(content); 
    var status = jQuery('#pagestatus').val();
    var displayonfront = jQuery('#displayonfront').val();
    var link = jQuery('#link').val();
    var sort_order = jQuery('#sort_order').val();
    var div_id = jQuery('#div_id').val();
    //bodytext = 'hellooo';

    var params;
    // var pars = "module=ztext&type=ajax&func=savePage&text_id=" + text_id + "&headertext=" + headertext + "&shop_id=" + shopId + "&bodytext=" + bodytext + "&status=" + status + "&displayonfront=" + displayonfront + "&sort_order=" + sort_order+"&link="+link+"&div_id="+div_id;
    // alert(pars);exit();
    params = {module: 'ZTEXT', type: 'ajax', func: 'savePage', text_id: text_id, headertext: headertext, shop_id: shopId, bodytext: content, status: status, displayonfront: displayonfront, sort_order: sort_order, link: link, div_id: div_id};
    //alert(params);
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'post',
                parameters: params,
                //async: false,
                cache: false,
                onSuccess: function (transport) {
                    if (200 == transport.status) {
                        //vJSONResp = transport.responseText;
                        //alert('Success');
                        //tinyMCE.get('ztext_bodytext').setContent('');

                        var response = pndejsonize(transport.responseText);
                        if (response.create == false) {
                            // alert('edit comes here..');
                            getPage(text_id, shopId);
                        }
                        else {
                            // alert('comes here..');
                            getPage(response.text_id, shopId, response.create, response.div_id);
                        }
                    } else {
                        // log.value += "\n" + transport.status;
                    }
                },
                onComplete: savePageResponses
            });
    return false;

}


function savePageResponses(req)
{
    // alert('hellooo');
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }

    var response = pndejsonize(req.responseText);
    if (response.success) {
        //tinyMCE.activeEditor.setContent('');
        pagesLeft();
        closeWindow();
    }
    else {
        alert(Zikula.__("Error occured please try later"));
    }

}


function getPage(text_id, shopId, create, div_id) {
    //alert(text_id);
    // alert("text_id :" + text_id + " shop_id :"+shopId+" create :" +create+" div_id :"+div_id); exit();
    if (!create) {
        create = 'no';
    }
    else {
        create = 'yes';
    }
    var pars = "module=ztext&type=ajax&func=getPage&text_id=" + text_id + "&shop_id=" + shopId + "&create=" + create;
    // alert(pars); exit();
    var response;
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                onSuccess: function (transport) {
                    if (200 == transport.status) {
                        var response = pndejsonize(transport.responseText);
                        //alert(response.data);
                        if (create == 'no') {
                            //alert('here1');
                            jQuery("#drophere" + text_id).html(response.data);
                        }
                        else {
                            // alert('here2');
                            //jQuery("#divNew"+div_id).html(response.data);
                            jQuery("#divNew" + div_id).remove();
                            jQuery("#listPages").append(response.data);
                        }
                    } else {
                        // log.value += "\n" + transport.status;
                    }
                }
            });
}


function deletePage(text_id) {
    //alert(text_id); exit();
    if (confirm(Zikula.__("Do you really want to delete this page?")) == true) {
        //document.forms['event_popup_form'].submit();
        // document.forms['eventdelete_popup_form'].submit();
        var shop_id = jQuery("#shop_id").val();
        document.getElementById('page_delete').style.display = 'none';
        jQuery('#page_delete_span').html('<input type=button value=Deleting... disabled>');
        var pars = "module=ztext&type=ajax&func=deletePage&text_id=" + text_id + "&shop_id=" + shop_id;
        var response;
        var myAjax = new Ajax.Request(
                "ajax.php",
                {
                    method: 'post',
                    parameters: pars,
                    async: true,
                    onSuccess: function (transport) {
                        if (200 == transport.status) {
                            //vJSONResp = transport.responseText;
                            //alert('Success');
                            response = pndejsonize(transport.responseText);
                            if (response.success == true) {
                                closeWindow();
                                jQuery("#div" + text_id).remove();

                            }
                        } else {
                            // log.value += "\n" + transport.status;
                        }

                    },
                    onComplete: deletePageResponse

                });
        return true;
    }
    else {
        return false;
    }
}


function deletePageResponse(req)
{
    //alert('hellooo');
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }

    // var json = pndejsonize(req.responseText);
    pagesLeft();

}


function getPageByImage(imgName, div_id, shopId) {
    var pars = "module=ZTEXT&type=ajax&func=getTextIdByImage&image=" + imgName + "&ajax=1";
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                onSuccess: function (transport) {
                    if (200 == transport.status) {
                        //vJSONResp = transport.responseText;
                        //alert('Success');
                        response = pndejsonize(transport.responseText);
                        getPage(response.text_id, shopId, true, div_id);
                        pagesLeft();
                    } else {
                        // log.value += "\n" + transport.status;
                    }
                }
            });
}


function pagesLeft() {

    // alert('hellloo'); exit();
    //jQuery("#drophere"+textId).html('Updating Page...'); exit();
    var shop_id = jQuery("#shop_id").val();
    // alert(shop_id); exit();

    var pars = "module=ztext&type=ajax&func=pagesLeft&shop_id=" + shop_id;
    // alert(pars); exit();
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                //async: false,
                onComplete: pagesLeftResponses
            });


}

//var validEvent;
function pagesLeftResponses(req)
{
    //alert('hellooo');
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }

    var json = pndejsonize(req.responseText);
    //var eventId = "drophere" + json.event_id;
    //alert(eventId);

    //document.getElementById('newPages').innerHTML = json.data;
    jQuery('#newPages').html(json.data);
    //alert(json.count);
    for (var i = 1; i <= json.count; i++) {
        //alert(i);
        //triggerDnd();
        var func = "triggerDnd" + i + "()";
        //alert(func);
        eval(func);
    }
    //triggerFoldings();
    //validEvent = new Validation('event_popup_form', {useTitles: true, onSubmit: false});
//  location.href = "#"+eventId;

}

function disablePageIndex(shop_id) {

    // alert('hellloo'); exit();
    //jQuery("#drophere"+textId).html('Updating Page...'); exit();
     var value = document.getElementById("pageindex_disabled").checked;
     var image_disabled = document.getElementById("image_disabled").checked;
     //alert('pageindex_disabled :'+value+" image_disabled :"+image_disabled); exit();
    //alert(vals); exit();
    var val;
    if (value) {
        val = 1;
    }
    else {
        val = 0;
    }
    if (image_disabled) {
        image_disabled = 1;
    }
    else {
        image_disabled = 0;
    }

    var pars = "module=ZTEXT&type=ajax&func=disablePageIndex&shop_id=" + shop_id + "&value=" + val +"&image_disabled="+image_disabled;
    // alert(pars); exit();
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                //async: false,
                onComplete: disablePageIndexResponse
            });


}

function disablePageIndexResponse(req)
{
    //alert('hellooo');
    if (req.status != 200)
    {

        pnshowajaxerror(req.responseText);
        return;
    }

    var json = pndejsonize(req.responseText);


}


function deleteExtraPageService() {
    //document.getElementById('drop_images').style.display='none';
    //document.getElementById('images_display').innerHTML = "Updating Images...";
    var shop_id = document.getElementById('shop_id').value;

    var pars = "module=ZTEXT&type=ajax&func=deleteExtraPageService&shop_id=" + shop_id + "&ajax=1";
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                // onComplete: getEmployeesResponses
            });

}


function deleteImage(text_id) {
    //alert(text_id); exit();
    if (confirm(Zikula.__("Do you really want to delete the image?")) == true) {
        //document.forms['event_popup_form'].submit();
        // document.forms['eventdelete_popup_form'].submit();
        var shop_id = jQuery("#shop_id").val();
        document.getElementById('image_delete').style.display = 'none';
        jQuery('#image_delete_span').html('<input type=button value=Deleting... disabled>');
        var pars = "module=ztext&type=ajax&func=deletePage&text_id=" + text_id + "&shop_id=" + shop_id + "&delete_image=1";
        var response;
        var myAjax = new Ajax.Request(
                "ajax.php",
                {
                    method: 'post',
                    parameters: pars,
                    async: true,
                    onSuccess: function (transport) {
                        if (200 == transport.status) {
                            //vJSONResp = transport.responseText;
                            //alert('Success');
                            response = pndejsonize(transport.responseText);
                            if (response.success == true) {
                                getPage(text_id, shop_id);
                                closeWindow();
                                //jQuery("#div" + text_id).remove();

                            }
                        } else {
                            // log.value += "\n" + transport.status;
                        }

                    },
                    onComplete: deletePageResponse

                });
        return true;
    }
    else {
        return false;
    }
}





window.onkeyup = function (event) {

    if (event.keyCode == 27) {
        //alert('works fine!!!'); 
        // closeWindow();
        //window.close ();
    }
}

    
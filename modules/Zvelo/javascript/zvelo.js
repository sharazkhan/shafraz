function bicycleDetail(id) {


    jQuery("#bicycle_id").val(id);
    jQuery(".chosen").removeClass("active");
    jQuery(".chosen").removeClass("chosen");
    jQuery("#" + id).addClass("chosen");
    jQuery(".chosen").addClass("active");

    jQuery("#bicycleDetail").html('<img src=' + Zikula.Config.baseURL + 'images/ajax/large_flower_black.gif style="padding-left:220px;padding-top:70px">');
    //alert(id);
    var pars = "module=Zvelo&type=ajax&func=getBicycleDetail&bicycle_id=" + id;
    var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                async: true,
                onComplete: bicycleDetailResponses
            });
}


function bicycleDetailResponses(req)
{
    // alert('here');
    if (req.status != 200)
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    var json = pndejsonize(req.responseText);
    //  alert(json.data);
    $("bicycleDetail").update(json.data);
    //jQuery("#specialdeal_block_products_low").css("border", "1px solid #E7E7E7");
}


document.observe("dom:loaded", function() {
    //  alert('hiii');
    // searchUsers();
    // Zikula.UI.Tooltips($$('.tooltips'));
    /*new Ajax.Autocompleter(
     'autocomplete',
     'autocomplete_choices',
     Zikula.Config.baseURL + 'ajax.php?module=zvelo&func=getUsers',
     {}
     );*/

    // alert('hiii');
});


function searchUsers() {
    // alert('hii3');
    //jQuery(".autocompletes").css('display' , 'block');
    //  jQuery(".autocompletes").css("display", "block");
    jQuery('.autocomplete').attr('style', 'border: 1px solid #888 !important');
    var options = Zikula.Ajax.Request.defaultOptions({
        paramName: 'value',
        tokens: ',',
        indicator: 'indicator1',
        minChars: 1,
        afterUpdateElement: getSelectionId
    });
    new Ajax.Autocompleter('autocomplete', 'autocomplete_choices', Zikula.Config.baseURL + 'ajax.php?module=zvelo&func=getUsers', options);
    //return true;
}

function getSelectionId(text, li) {
    //alert(li.id);
    jQuery('#huser').val(li.id);
}
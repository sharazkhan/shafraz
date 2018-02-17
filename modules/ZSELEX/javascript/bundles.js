function closeWindow(){
    document.getElementById('editPurBundle').style.display='none';
    document.getElementById("backshield").style.display="none";
 
}



window.onkeyup = function (event) {
           
    if (event.keyCode == 27) {
        //alert('works fine!!!'); 
        closeWindow();
    //window.close ();
    }
}
var selected_sid;
/*function editPurchasedBundles(sid,shopId,bundleId){
    // alert(bundleId);  exit();
    // alert(imageID);
    // var shop_id = document.getElementById("shop_id").value;
   
    document.getElementById("backshield").style.display="block";
    document.getElementById("editPurBundle").style.display="block";
    document.getElementById("editPurBundle").innerHTML="<font size=3>"+Zikula.__('Loading...','module_zselex_js')+"</font>";
    //document.getElementById("image_id").value=imageID;
    // exit();
    var pars = "module=ZSELEX&type=PopUp&func=editPurchasedBundles&&sid="+sid+"&shop_id="+shopId+"&bundle_id="+bundleId;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            async: true,
            onComplete: editPurchasedBundlesResponses
        });
   
   
}*/

function editPurchasedBundles(){
    // alert(bundleId);  exit();
    // alert(imageID);
    // var shop_id = document.getElementById("shop_id").value;
   
    document.getElementById("backshield").style.display="block";
    document.getElementById("editPurBundle").style.display="block";
    document.getElementById("editPurBundle").innerHTML="<font size=3>"+Zikula.__('Loading...','module_zselex_js')+"</font>";
    //document.getElementById("image_id").value=imageID;
    // exit();
    var pars = "module=ZSELEX&type=PopUp&func=editPurchasedBundles&&sids="+selected_sid
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            async: true,
            onComplete: editPurchasedBundlesResponses
        });
   
   
}


function editPurchasedBundlesResponses (req)    
{
    if (req.status != 200) 
    {
        
        pnshowajaxerror(req.responseText);
        return;
    }
   
    var json = pndejsonize(req.responseText);
   
    document.getElementById('editPurBundle').innerHTML = json.data;
   
}

jQuery(document).ready(function() {
     
    jQuery('#news_select_all').click(function(event) {  //on click 
        //alert('helloo');
        // if(this.checked) { // check select status
        jQuery('.news_checkbox').each(function() { //loop through each checkbox
            this.checked = true;  //select all checkboxes with class "checkbox1"               
        });
    // }
    });
        
    jQuery('#news_deselect_all').click(function(event) {  //on click 
        jQuery('.news_checkbox').each(function() { //loop through each checkbox
            this.checked = false; //deselect all checkboxes with class "checkbox1"                       
        }); 
    });
    
   
   
    jQuery("#news_bulkaction_select").click(function(event) {
        //alert('helloo');
        event.preventDefault();
        var chkArray = [];
        jQuery(".buyed_bundles:checked").each(function() {
            chkArray.push(jQuery(this).val());
        });
        
        selected_sid = chkArray.join(',');
        //alert(selected_sid);
        if(selected_sid == ''){
            alert(Zikula.__('You have not selected' , 'module_zselex_js'));
            return;
        }
        editPurchasedBundles();
    // alert(selected);
    // alert('helloo');
    });
   
   
   
    var $scrollingDiv = jQuery("#editPurBundle");
    jQuery(window).scroll(function(){	
        //alert(jQuery("#editEvent").height());
        //jQuery("#sample").val(jQuery("#editEvent").height());
        //jQuery("#editEvent").css('height', jQuery("#editEvent").height()+"px");
        $scrollingDiv
        .stop()
        .animate({
            "marginTop": (jQuery(window).scrollTop() + 50) + "px"
        }, "fast" );			
    });
});



    
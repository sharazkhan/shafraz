function getServiceCount(val , price , id){
    // alert('hiii');
    var tcnts =  document.getElementById('totalcounts').value;
   
    if(val!=''){
        var value = parseInt(val)*parseInt(price);
        var totalval = parseInt(tcnts)+parseInt(value);
        document.getElementById('total'+id).innerHTML=value;
        document.getElementById('totalcounts').value=totalval;
        document.getElementById('totalcount').value=value;
        document.getElementById('overalltotal').innerHTML = "<b>" + document.getElementById('totalcounts').value + " per month</b>";
    }
    else{
        document.getElementById('total'+id).innerHTML=0; 
    }
}



function addQuantity(plugin_id , val){
    //alert(plugin_id);
    document.getElementById('qty' + plugin_id).value = val;
    document.getElementById('cart_quantity' + plugin_id).value = val;
    document.getElementById('cart_quantitydemo' + plugin_id).value = val;
}




function addToBasket(plugin_id , typ , price , qty , shop_id){
  
    if(qty=='') {
      
        alert('please enter quantity');
        return false;
    }
    // alert('plugin-' + plugin_id + ' price-' + price);
    document.getElementById("backshield").style.display="block";
    var pars = "module=ZSELEX&type=ajax&func=addToBasket&plugin_id=" + plugin_id + "&price=" + price + "&qty=" + qty + "&shop_id=" + shop_id + "&typ=" + typ;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            async: true,
            onComplete: myFunctionaddToBasketResponses
        });
    
}


function myFunctionaddToBasketResponses (req)    
{
    //alert("hii");
   
    if (req.status != 200) 
    {
        
        pnshowajaxerror(req.responseText);
        return;
    }
    showServices('add');
// document.getElementById("supdate").style.display="block";
//document.getElementById('supdate').innerHTML='<font color=green>Service Added Sucessfully!</font>';
//window.setTimeout("closeHelpDiv();", 15000);
   
    
//    if(req.responseText == 'true'){
//        alert("Service Added to Basket");
//    }
//    else if(req.responseText == 'updated'){
//      alert("Service Updated to Basket");
//    }
//    else{
//        alert("Service Adding Failed");   
//    }

}



function addToBasket1(plugin_id , name , typ , price , qty , qtyBased , shop_id){
  
    //alert(shop_id);
    if(qty=='') {
      
        alert('please enter quantity');
        return false;
    }
    //alert(name + " " + qtyBased); exit();
    
    //alert('plugin-' + plugin_id + ' price-' + price); exit();
    document.getElementById("backshield").style.display="block";
    var pars = "module=ZSELEX&type=ajax&func=addToBasket1&plugin_id=" + plugin_id + "&plugin_name="+name+"&price=" + price + "&qty=" + qty + "&qty_based="+qtyBased+"&shop_id=" + shop_id + "&typ=" + typ;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            async: true,
            onComplete: myFunctionaddToBasket1Responses
        });
    
}


function myFunctionaddToBasket1Responses (req)    
{
    var result;
    var count;
    var cart;
    if (req.status != 200) 
    {
        
        pnshowajaxerror(req.responseText);
        return;
    }
    //alert(req.responseText); exit();
    var json = pndejsonize(req.responseText);
   
   
    result = json.result;
    count = json.count;
    //alert(count);  exit();
    cart = "<b>cart("+count+")</b>";
    jQuery('#admCart').html(cart);   
    jQuery('#light').html(result);     
    jQuery('#light').slideDown("medium");
   
//window.setTimeout("closeHelpDiv();", 15000);
  
}



function displayBasket(shop_id){
   
    //alert(shop_id); exit();
    document.getElementById("backshield").style.display="block";
    var pars = "module=ZSELEX&type=ajax&func=displayBasket&shop_id="+shop_id;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            async: true,
            onComplete:displayBasketResponses
        });
   
    
    
}


function displayBasketResponses (req)    
{
    //alert(req.responseText); exit();
    if (req.status != 200) 
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    //alert(req.responseText); 
    //jQuery('#light').show();
    //document.getElementById('light').style.display='block';
    jQuery('#light').html(req.responseText);     
    //document.getElementById('light').innerHTML=req.responseText;
    jQuery('#light').slideDown("slow");
 
}



function showServices1(val){
  
    //alert(val);
    //window.scrollTo(0, 0);
    var pars = "module=ZSELEX&type=ajax&func=getServicesBasketList1&val="+val;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            async: true,
            onComplete:myFunctionshowServices1Responses
        });
   
}


function myFunctionshowServices1Responses (req)    
{
    //alert(req.responseText); exit();
    if (req.status != 200) 
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    //alert(req.responseText); 
    //jQuery('#light').show();
    //document.getElementById('light').style.display='block';
    jQuery('#light').html(req.responseText);     
    //document.getElementById('light').innerHTML=req.responseText;
    jQuery('#light').slideDown("slow");
 
}





function showServices(val){
  
    //alert('hiii');
  
    var pars = "module=ZSELEX&type=ajax&func=getServicesBasketList&val="+val;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            async: true,
            onComplete:myFunctionshowServicesResponses
        });
   
}


function myFunctionshowServicesResponses (req)    
{
 
    if (req.status != 200) 
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    //alert(req.responseText); 
  
    document.getElementById('light').style.display='block';
    document.getElementById('light').innerHTML=req.responseText;
 
}


function closeWindow(){
    document.getElementById('light').style.display='none';
    document.getElementById("backshield").style.display="none";
 
}



function deleteService(id){
    var pars = "module=ZSELEX&type=ajax&func=deleteService&id="+id;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            async: true,
            onComplete: myFunctiondeleteServiceResponses
        });
    
    
}


function myFunctiondeleteServiceResponses (req)    
{
 
    if (req.status != 200) 
    {
        pnshowajaxerror(req.responseText);
        return;
    }
  
    showServices('delete');
// window.setTimeout("closeHelpDiv();", 15000);
    
}


function deleteService1(id , shop_id){
    var pars = "module=ZSELEX&type=ajax&func=deletecart&id="+id+"&shop_id="+shop_id;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            async: true,
            onComplete: myFunctiondeleteService1Responses
        });
    
    
}


function myFunctiondeleteService1Responses (req)    
{
    var count;
 
    if (req.status != 200) 
    {
        pnshowajaxerror(req.responseText);
        return;
    }
  
    //showServices1('delete');
    //window.setTimeout("closeHelpDiv();", 15000);
    var json = pndejsonize(req.responseText);
   
    var result;
    var cart;
    result = json.result;
    count = json.count;
    //alert(result);  exit();
    cart = "<b>cart("+count+")</b>";
    jQuery('#admCart').html(cart);   
    jQuery('#light').html(result);     
    
//jQuery('#light').slideDown("slow");
    
}



function updateService(qty , id , orgprice){
    var pars = "module=ZSELEX&type=ajax&func=updateService&id="+id+"&qty="+qty+"&orgprice="+orgprice;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            async: true,
            onComplete: myFunctionupdateServiceResponses
           
        });
  
}


function myFunctionupdateServiceResponses (req)    
{
 
    if (req.status != 200) 
    {
        pnshowajaxerror(req.responseText);
        return;
    }
  
   
    //document.getElementById("supdate").style.display="block";
    //document.getElementById('supdate').innerHTML='<font color=green><b>Service Updated!</b></font>';
    showServices('update');
//window.setTimeout("closeHelpDiv();", 15000);
   
}



function updatecart(qty , id , orgprice , shop_id){
    
    //alert(qty + " " + id + " " + orgprice); exit();
    var pars = "module=ZSELEX&type=ajax&func=updatecart&id="+id+"&qty="+qty+"&orgprice="+orgprice+"&shop_id="+shop_id;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            async: false,
            onComplete: myFunctionupdatecartResponses
           
        });
  
}


function myFunctionupdatecartResponses (req)    
{
 
    var result;
    if (req.status != 200) 
    {
        pnshowajaxerror(req.responseText);
        return;
    }
  
    
    //showServices1('update');
    //window.setTimeout("closeHelpDiv();", 15000);
    
    var json = pndejsonize(req.responseText);
   
    result = json.result;
    //alert(result);  exit();
   
    //jQuery('#light').slideDown("fast");
    jQuery('#light').html(result);     
   
}


function closeHelpDiv(){
    document.getElementById("supdate").style.display="none";
}



window.onkeyup = function (event) {
           
    if (event.keyCode == 27) {
        //alert('works fine!!!'); 
        closeWindow();
    //window.close ();
    }
}



function hello(){
    
    
    alert('hiiii');
}


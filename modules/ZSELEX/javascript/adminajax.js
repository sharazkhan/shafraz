
//create a function that accepts an input variable (which key was key pressed)
function disableEnterKey(e){
 
//create a variable to hold the number of the key that was pressed     
var key;
 
    //if the users browser is internet explorer
    if(window.event){
      
    //store the key code (Key number) of the pressed key
    key = window.event.keyCode;
      
    //otherwise, it is firefox
    } else {
      
    //store the key code (Key number) of the pressed key
    key = e.which;     
    }
      
    //if key 13 is pressed (the enter key)
    if(key == 13){
      
    //do nothing
    return false;
      
    //otherwise
    } else {
      
    //continue as normal (allow the key press for keys other than "enter")
    return true;
    }
      
//and don't forget to close the function    
} 


function validate(id){
    
    
    //alert('HIIIIIIIIIIIIIIIIIII'); exit();
    
    // alert(id);
    
    if(id=='1'){ //CITY 
        // alert(id);
        // document.getElementById('cats').style.display = 'block';
        //document.getElementById('regions').style.display = 'block';
        document.getElementById('shopdetails').style.display = 'none';
        document.getElementById('ecom').style.display = 'none';
        document.getElementById('pluginsDis').style.display = 'none';
        document.getElementById('shops').style.display = 'none';
        document.getElementById('countries').style.display = 'none'; 
        document.getElementById('branches').style.display = 'none';
        document.getElementById('zshop').style.display = 'none';
    }
    else if(id=='2') { //SHOP
        // document.getElementById('shops').style.display = 'none';
        document.getElementById('shopdetails').style.display = 'block';
        // document.getElementById('cats').style.display = 'block'; 
        document.getElementById('ecom').style.display = 'block';
        document.getElementById('pluginsDis').style.display = 'block';
        document.getElementById('countries').style.display = 'none';
        document.getElementById('regions').style.display = 'none';
        document.getElementById('branches').style.display = 'block';
        document.getElementById('zshop').style.display = 'none';
     
    }
    
    else if(id=='3') { // AD
        //document.getElementById('shops').style.display = 'block'; 
        // document.getElementById('cats').style.display = 'block';
        document.getElementById('shopdetails').style.display = 'none';
        document.getElementById('ecom').style.display = 'none';
        document.getElementById('pluginsDis').style.display = 'none';
        document.getElementById('countries').style.display = 'none'; 
        document.getElementById('regions').style.display = 'none';
        document.getElementById('branches').style.display = 'none';
        document.getElementById('zshop').style.display = 'none';
    }
    else if(id=='4') { //PLUGIN
        document.getElementById('shops').style.display = 'none'; 
        document.getElementById('shopdetails').style.display = 'none';
        document.getElementById('cats').style.display = 'none';
        document.getElementById('ecom').style.display = 'none';
        document.getElementById('pluginsDis').style.display = 'none';
        document.getElementById('countries').style.display = 'none'; 
        document.getElementById('regions').style.display = 'none';
        document.getElementById('branches').style.display = 'none';
        document.getElementById('zshop').style.display = 'none';
    }
    
    else if(id=='12') { //REGION
        // document.getElementById('countries').style.display = 'block'; 
        document.getElementById('shops').style.display = 'none'; 
        document.getElementById('shopdetails').style.display = 'none';
        document.getElementById('cats').style.display = 'none';
        document.getElementById('ecom').style.display = 'none';
        document.getElementById('pluginsDis').style.display = 'none';
        document.getElementById('regions').style.display = 'none';
        document.getElementById('branches').style.display = 'none';
        document.getElementById('zshop').style.display = 'none';
    }
    else{
        
        
        document.getElementById('shops').style.display = 'none'; 
        document.getElementById('shopdetails').style.display = 'none';
        document.getElementById('cats').style.display = 'none';
        document.getElementById('ecom').style.display = 'none';
        document.getElementById('pluginsDis').style.display = 'none'; 
        document.getElementById('countries').style.display = 'none'; 
        document.getElementById('regions').style.display = 'none';
        document.getElementById('branches').style.display = 'none';
        document.getElementById('zshop').style.display = 'none';
        
        
    }
    
    
}



function shoptype(id){
    
    
    
    if(id=='1'){ //ZSHOP 
       
        document.getElementById('zshop').style.display = 'block';
        
    }
    else {
        document.getElementById('zshop').style.display = 'none';
    }
    
}



function shopenable(id){
    
    // alert(id); exit();
    if(id=='1'){ //ZSHOP 
       
        document.getElementById('zshop').style.display = 'block';
        
    }
    else {
        document.getElementById('zshop').style.display = 'none';
    }
}


function changeParent(id){
    
    if(id=='1'){ //CITY 
        // alert(id);
        document.getElementById('cats').style.display = 'block';
        document.getElementById('shops').style.display = 'none';
        
        document.getElementById('ads').style.display = 'none';
        
        
        document.getElementById('regions').style.display = 'none';
      
        document.getElementById('countries').style.display = 'none';
       
       
        
    }
    else if(id=='2') { //SHOP
        document.getElementById('shops').style.display = 'block';
        document.getElementById('ads').style.display = 'none';
        document.getElementById('regions').style.display = 'none';
        document.getElementById('cats').style.display = 'none';
        document.getElementById('countries').style.display = 'none';
       
     
    }
    
    else if(id=='3') { // AD
       
        document.getElementById('ads').style.display = 'block';
        
        
        document.getElementById('regions').style.display = 'none';
      
        document.getElementById('shops').style.display = 'none';
        document.getElementById('cats').style.display = 'none';
        document.getElementById('countries').style.display = 'none';
        
    }
    else if(id=='4') { //PLUGIN
       
    }
    
    else if(id=='10') { //COUNTRY
        // document.getElementById('countries').style.display = 'block'; 
       
        document.getElementById('countries').style.display = 'block';
        document.getElementById('ads').style.display = 'none';
        document.getElementById('shops').style.display = 'none';
        document.getElementById('cats').style.display = 'none';
        document.getElementById('regions').style.display = 'none';
        
    }
    
    else if(id=='12') { //REGION
        // document.getElementById('countries').style.display = 'block'; 
       
        document.getElementById('regions').style.display = 'block';
        document.getElementById('ads').style.display = 'none';
        document.getElementById('shops').style.display = 'none';
        document.getElementById('cats').style.display = 'none';
        document.getElementById('countries').style.display = 'none';
        
    }
    else{
        
        document.getElementById('regions').style.display = 'none';
        document.getElementById('ads').style.display = 'none';
        document.getElementById('shops').style.display = 'none';
        document.getElementById('cats').style.display = 'none';
        document.getElementById('countries').style.display = 'none';
       
        
    }
 
}






/** 
 *function to get the  block content of product list based on drop down selections  
 */

function getBlockContent(shop_id){
    
    //alert(shop_id);  exit();
   
    //alert("city: "+city_id); exit();
    var pars = "module=ZSELEX&type=ajax&func=getBlockContent&shop_id=" + shop_id;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            onComplete:myFunctionContentResponses
        });
   
}

function myFunctionContentResponses (req)    
{
    if (req.status != 200) 
    {
        
        pnshowajaxerror(req.responseText);
        return;
    }
    // alert(req.responseText.length);   exit();
    // alert(req.responseText); exit();
    document.getElementById('blockcenter').innerHTML = req.responseText;
    
}

/** getBlockContent() functions ends ******************************************/






function getRegionList(country_id){  //CALLED ON COUNTRY
    
     
    document.getElementById('startval').value = '0';
    document.getElementById('endval').value = '5' ;
    
 
    document.getElementById('hcountry').value = country_id;
    document.getElementById('hregion').value = '';
    document.getElementById('hcity').value = '';
    document.getElementById('hshop').value = '';
    
   
    
    
    var hcountry =  document.getElementById('hcountry').value;
    var hregion =  document.getElementById('hregion').value;
    var hcity =  document.getElementById('hcity').value;
    var hshop =  document.getElementById('hshop').value;
    // alert(hregion);  
    var pars = "module=ZSELEX&type=ajax&func=getRegionList&country_id=" + country_id;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            onComplete:myFunctiongetRegionListResponses
        });
   
}

function myFunctiongetRegionListResponses (req)    
{
    if (req.status != 200) 
    {
        
        pnshowajaxerror(req.responseText);
        return;
    }
    // alert(req.responseText.length);   exit();
    // alert(req.responseText);  exit();
   
    document.getElementById('displayregion').innerHTML = req.responseText;
    
}



function getCityList(region_id){   //CALLED ON REGION
    
    //alert(region_id);  exit();
    
     
    document.getElementById('startval').value = '0';
    document.getElementById('endval').value = '5' ;
  
    document.getElementById('hregion').value = region_id;
    document.getElementById('hcity').value = '';
    document.getElementById('hshop').value = '';
    
   
    
    
    var hcountry =  document.getElementById('hcountry').value;
    var hregion =  document.getElementById('hregion').value;
    var hcity =  document.getElementById('hcity').value;
    var hshop =  document.getElementById('hshop').value;
    //alert(hcountry);
    //alert("city: "+city_id); exit();
    //var pars = "module=ZSELEX&type=ajax&func=getCityList&region_id=" + region_id;
    
    var pars = "module=ZSELEX&type=ajax&func=getCityList&shop_id=" + hshop + "&country_id=" + hcountry + 
    "&region_id=" + region_id + "&city_id=" + hcity;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            onComplete:myFunctiongetCityListResponses
        });
   
    
    
}


function myFunctiongetCityListResponses (req)    
{
    if (req.status != 200) 
    {
        
        pnshowajaxerror(req.responseText);
        return;
    }
    // alert(req.responseText.length);   exit();
    // alert(req.responseText);  exit();
   
    document.getElementById('displaycity').innerHTML = req.responseText;
    
}




function getCountryCityList(country_id){   //CALLED ON COUNTRY
    
    //alert(region_id);  exit();
    // alert(country_id);  exit();
  
    //alert(hcountry);
    //alert("city: "+city_id); exit();
    document.getElementById('startval').value = '0';
    document.getElementById('endval').value = '5' ;
    
    
    var pars = "module=ZSELEX&type=ajax&func=getCountryCityList&country_id=" + country_id;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            onComplete:myFunctiongetCountryCityListResponses
        });
   
    
    
}


function myFunctiongetCountryCityListResponses (req)    
{
    if (req.status != 200) 
    {
        
        pnshowajaxerror(req.responseText);
        return;
    }
    // alert(req.responseText.length);   exit();
    // alert(req.responseText);  exit();
   
   
    document.getElementById('displaycity').innerHTML = req.responseText;
    
}



function getCountryShopList(country_id){   //CALLED ON COUNTRY
    
    //alert(region_id);  exit();
    //alert(country_id);  exit();
  
    //alert(hcountry);
    //alert("city: "+city_id); exit();
    document.getElementById('startval').value = '0';
    document.getElementById('endval').value = '5' ;
    
    
    var pars = "module=ZSELEX&type=ajax&func=getCountryShopList&country_id=" + country_id;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            onComplete:myFunctiongetCountryShopListResponses
        });
   
    
    
}


function myFunctiongetCountryShopListResponses (req)    
{
    if (req.status != 200) 
    {
        
        pnshowajaxerror(req.responseText);
        return;
    }
    // alert(req.responseText.length);   exit();
    // alert(req.responseText);  exit();
   
   
   
    document.getElementById('displayshop').innerHTML = req.responseText;
    
}



function getRegionShopList(region_id){   //CALLED ON REGION
    
    //alert(region_id);  exit();
    //alert(country_id);  exit();
  
    //alert(hcountry);
    //alert("city: "+city_id); exit();
    
    document.getElementById('startval').value = '0';
    document.getElementById('endval').value = '5' ;
    
    var hcountry =  document.getElementById('hcountry').value;
    
    var pars = "module=ZSELEX&type=ajax&func=getRegionShopList&region_id=" + region_id + "&country_id=" + hcountry;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            onComplete:myFunctiongetRegionShopListResponses
        });
   
    
    
}


function myFunctiongetRegionShopListResponses (req)    
{
    if (req.status != 200) 
    {
        
        pnshowajaxerror(req.responseText);
        return;
    }
    // alert(req.responseText.length);   exit();
    //alert(req.responseText);  exit();
   
   
   
    document.getElementById('displayshop').innerHTML = req.responseText;
    
}



/**
 * function to get the shop list based on city selection on front end block  
 */
function getShopFrntend(city_id){  //CALLED ON CITY
    
    //alert(city_id); exit();
    //document.getElementById('hiddenCity').value = city_id;
    
    document.getElementById('startval').value = '0';
    document.getElementById('endval').value = '5' ;
    
    
    document.getElementById('hcity').value = city_id;
    document.getElementById('hshop').value = '';
   
   
    var hcountry =  document.getElementById('hcountry').value;
    var hregion =  document.getElementById('hregion').value;
    var hcity =  document.getElementById('hcity').value;
    var hshop =  document.getElementById('hshop').value;
    
    // var pars = "module=ZSELEX&type=ajax&func=getShopsList&city_id=" + city_id;
    
    
    var pars = "module=ZSELEX&type=ajax&func=getShopsList&shop_id=" + hshop + "&country_id=" + hcountry + 
    "&region_id=" + hregion + "&city_id=" + hcity;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            onComplete:myFunctionShopResponses
        });
   
}


function myFunctionShopResponses (req)
{
    if (req.status != 200) 
    {
        
        pnshowajaxerror(req.responseText);
        return;
    }
       
    document.getElementById('displayshop').innerHTML = req.responseText;
//alert(req.responseText);
}
/** getShopFrntend() functions ends ******************************************/

function shoponchange(){
    //alert('works!');
    
    document.getElementById('startval').value = '0';
    document.getElementById('endval').value = '5' ;
    
    
}


function getShopDetails(shop_id){
    //alert(shop_id);
    //alert(shop_id);  exit();
    
    document.getElementById('hshop').value = shop_id;

    var hcountry =  document.getElementById('hcountry').value;
    var hregion =  document.getElementById('hregion').value;
    var hcity =  document.getElementById('hcity').value;
    var hshop =  document.getElementById('hshop').value;
    var hcategory =  document.getElementById('hcategory').value;
    
    // alert(hcategory);
    
    var startval = document.getElementById('startval').value;
    var endval = document.getElementById('endval').value;
    
   
    //alert(hshop); 
    
    var pars = "module=ZSELEX&type=ajax&func=getShopDetails&shop_id=" + hshop + "&country_id=" + hcountry + 
    "&region_id=" + hregion + "&city_id=" + hcity + "&category_id=" + hcategory + "&startval="+startval + "&endval="+endval;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            async: true,
            onComplete:myFunctiongetShopDetailsResponses
        });
   
}

function myFunctiongetShopDetailsResponses (req)    
{
   
    if (req.status != 200) 
    {
        
        pnshowajaxerror(req.responseText);
        return;
    }
    // alert(req.responseText.length);   exit();
    // alert(req.responseText);  exit();
    if (req.readyState == 4) 
    {
    
        document.getElementById('blockshop').innerHTML = req.responseText;
    }
    else{
        
        document.getElementById('blockshop').innerHTML = Zikula.__('PLEASE WAIT.....'); 
        
    }
    
}


function cat(catId){
    
    // alert(catId);
    document.getElementById('hcategory').value = catId;
}


function paginateNext(){
    
    var startvals = document.getElementById('startval').value;
    var endvals = document.getElementById('endval').value;
    
    var startval = parseInt(startvals)+parseInt(5);
   
    //var endval = parseInt(endvals)+parseInt(1);
     
    var endval = endvals;
    
    document.getElementById('startval').value = startval;
    document.getElementById('endval').value = endval;
    
    getShopDetails(document.getElementById('hshop').value);
    
//alert("start: "+startval+" end: "+endval);
}


function paginatePrev(){
    
    
    //alert('hellooo'); exit();
    var startvals = document.getElementById('startval').value;
    var endvals = document.getElementById('endval').value;
    
    var startval = parseInt(startvals)-parseInt(5);
   
    //var endval = parseInt(endvals)-parseInt(1);
    
    var endval = endvals;
    
    document.getElementById('startval').value = startval;
    document.getElementById('endval').value = endval;
    
    getShopDetails(document.getElementById('hshop').value);
    
//alert("start: "+startval+" end: "+endval);
}





window.onload = function() {  
    
    //    
    //    var counts =  document.getElementById('countrycounts').value;
    //    //alert(counts);
    //   
    //    if(counts == 1) {
    //        document.getElementById("country").disabled = true;
    //    }
    //    else{
    //        document.getElementById("country").disabled = false;  
    //    }
    ////alert('window');
     
    };  


function shopLimit() {
    alert(document.getElementById("regionsearchid").value);
   
}

function getSuggestion(){
    
// alert('hellooo');
}




var ajaxObj = getAjaxObject();

var targetID = new Array() ;
var searchID = new Array() ;
var inputID = new Array() ;

function autoSuggestCountry(id, targetid, searchid, inputid, e)
{
    
    
   
    var val = "#" +targetid;
    $(val).fadeIn('medium');
  
    //alert('hiii'); exit();
    
    // alert(targetid);
    var keyCode = getKeyCode(e, 'keyup');
    if (keyCode == 40 || keyCode == 38)
    {
        return false;   
    }
   
    autoSugPointer[id] = 0;
   
    targetID[id] = targetid;
    searchID[id] = searchid;
    inputID[id] = inputid;
    countSuggestions[id] = 0;
   
    var searchInput = getElemId(id).value;
   
    //alert(searchInput);
    
    
    //sendRequest(ajaxObj, url, params, handleSuggestResponse, id);
    document.getElementById('temps').value = id;
    var pars = "module=ZSELEX&type=ajax&func=country_autocomplete&input=" + searchInput;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            onComplete:handleSuggestResponse
        });
}


function autoSuggestCity(id, targetid, searchid, inputid, e)
{
    
    var val = "#" +targetid;
    $(val).fadeIn('medium');
  
    //alert('hiii'); exit();
    //alert(targetid);
    // alert(targetid);
    var keyCode = getKeyCode(e, 'keyup');
    if (keyCode == 40 || keyCode == 38)
    {
        return false;   
    }
   
    autoSugPointer[id] = 0;
   
    targetID[id] = targetid;
    searchID[id] = searchid;
    inputID[id] = inputid;
    countSuggestions[id] = 0;
   
    var searchInput = getElemId(id).value;
   
    //alert(searchInput);
    
    
    //sendRequest(ajaxObj, url, params, handleSuggestResponse, id);
    document.getElementById('temps').value = id;
    var pars = "module=ZSELEX&type=ajax&func=city_autocomplete&input=" + searchInput;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            onComplete:handleSuggestResponse
        });
}

function outlist(id){
    
   
    var val = "#" +id;
    $(val).fadeOut('medium');
}


function autoSuggestRegion(id, targetid, searchid, inputid, e)
{
    var val = "#" +targetid;
    $(val).fadeIn('medium');
  
   
    
    // alert(targetid);
    var keyCode = getKeyCode(e, 'keyup');
    if (keyCode == 40 || keyCode == 38)
    {
        return false;   
    }
   
    autoSugPointer[id] = 0;
   
    targetID[id] = targetid;
    searchID[id] = searchid;
    inputID[id] = inputid;
    countSuggestions[id] = 0;
   
    var searchInput = getElemId(id).value;
   
    //alert(searchInput);
    
    
    //sendRequest(ajaxObj, url, params, handleSuggestResponse, id);
    document.getElementById('temps').value = id;
    var pars = "module=ZSELEX&type=ajax&func=region_autocomplete&input=" + searchInput;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            onComplete:handleSuggestResponse
        });
}





function autoSuggestShop(id, targetid, searchid, inputid, e)
{
    var val = "#" +targetid;
    $(val).fadeIn('medium');
  
   
    
    // alert(targetid);
    var keyCode = getKeyCode(e, 'keyup');
    if (keyCode == 40 || keyCode == 38)
    {
        return false;   
    }
   
    autoSugPointer[id] = 0;
   
    targetID[id] = targetid;
    searchID[id] = searchid;
    inputID[id] = inputid;
    countSuggestions[id] = 0;
   
    var searchInput = getElemId(id).value;
   
    //alert(searchInput);
    
    
    //sendRequest(ajaxObj, url, params, handleSuggestResponse, id);
    document.getElementById('temps').value = id;
    var pars = "module=ZSELEX&type=ajax&func=shop_autocomplete&input=" + searchInput;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            onComplete:handleSuggestResponse
        });
}

function autoSuggestCategory(id, targetid, searchid, inputid, e)
{
    
    var val = "#" +targetid;
    $(val).fadeIn('medium');
  
    //alert('hiii'); exit();
    //alert(targetid);
    // alert(targetid);
    var keyCode = getKeyCode(e, 'keyup');
    if (keyCode == 40 || keyCode == 38)
    {
        return false;   
    }
   
    autoSugPointer[id] = 0;
   
    targetID[id] = targetid;
    searchID[id] = searchid;
    inputID[id] = inputid;
    countSuggestions[id] = 0;
   
    var searchInput = getElemId(id).value;
   
    //alert(searchInput);
    
    
    //sendRequest(ajaxObj, url, params, handleSuggestResponse, id);
    document.getElementById('temps').value = id;
    var pars = "module=ZSELEX&type=ajax&func=category_autocomplete&input=" + searchInput;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            onComplete:handleSuggestResponse
        });
}

function autoSuggestAd(id, targetid, searchid, inputid, e)
{
    
    var val = "#" +targetid;
    $(val).fadeIn('medium');
  
    //alert('hiii'); exit();
    //alert(targetid);
    // alert(targetid);
    var keyCode = getKeyCode(e, 'keyup');
    if (keyCode == 40 || keyCode == 38)
    {
        return false;   
    }
   
    autoSugPointer[id] = 0;
   
    targetID[id] = targetid;
    searchID[id] = searchid;
    inputID[id] = inputid;
    countSuggestions[id] = 0;
   
    var searchInput = getElemId(id).value;
   
    //alert(searchInput);
    
    
    //sendRequest(ajaxObj, url, params, handleSuggestResponse, id);
    document.getElementById('temps').value = id;
    var pars = "module=ZSELEX&type=ajax&func=ad_autocomplete&input=" + searchInput;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            onComplete:handleSuggestResponse
        });
}

function autoSuggestPlugin(id, targetid, searchid, inputid, e)
{
    
    var val = "#" +targetid;
    $(val).fadeIn('medium');
  
    //alert('hiii'); exit();
    //alert(targetid);
    // alert(targetid);
    var keyCode = getKeyCode(e, 'keyup');
    if (keyCode == 40 || keyCode == 38)
    {
        return false;   
    }
   
    autoSugPointer[id] = 0;
   
    targetID[id] = targetid;
    searchID[id] = searchid;
    inputID[id] = inputid;
    countSuggestions[id] = 0;
   
    var searchInput = getElemId(id).value;
   
    //alert(searchInput);
    
    
    //sendRequest(ajaxObj, url, params, handleSuggestResponse, id);
    document.getElementById('temps').value = id;
    var pars = "module=ZSELEX&type=ajax&func=plugin_autocomplete&input=" + searchInput;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            onComplete:handleSuggestResponse
        });
}




function handleSuggestResponse(req)
{
    
    var id = document.getElementById('temps').value;
   
    if (req.readyState == 4) 
    {
        // alert(req.responseXML.documentElement);
        if (req.status == 200)
        {
            //alert('200');
            try
            {
                var XMLResponse = req.responseXML.documentElement;
              
                // work with the xml response
                var keywordsTag = XMLResponse.getElementsByTagName('keywords');
	       
                var suggestions = new Array();
			  
                for (var i = 0; i < keywordsTag.length; i++)
                {
                    //alert(i);
                    var keywords = keywordsTag.item(i).firstChild.data.toString();
                    
                   
                    suggestions.push(keywords);
                }
                showSuggestions(suggestions, id);
            }
            catch(e)
            {
                hideSuggestions(id);
                if (trim(req.responseText) !== "")
                    alert(req.responseText);  
            }
        }
    }
}


var countSuggestions = new Array();

function showSuggestions(suggestions, id)
{
    // alert('hiii');
    var listWrapID = getElemId(targetID[id]);
    // alert(listWrapID);
    listWrapID.style.visibility = "visible";
   
    var listID = getElemId(searchID[id]);
    //alert(listID);
    listID.innerHTML = "";
   
    for(var i = 0; i < suggestions.length; i++)
    {
        
        var mySplitResult = suggestions[i].split("+");
        // alert(mySplitResult[0]);
        
        // listID.innerHTML += "<li><a id='"+id + "-" +(i+1)+"' href=\"javascript:void(0);\" onclick=\"insertKeyword(this.innerHTML, '"+id+"');\">" + suggestions[i] + "</a></li>"; 
        listID.innerHTML += "<li><a id='"+id + "-" +(i+1)+"' href=\"javascript:void(0);\" onclick=\"insertKeyword(this.innerHTML,'"+mySplitResult[1]+"' , '"+id+"');\">" + mySplitResult[0] + "</a></li>";      
    }  
   
    countSuggestions[id] = i;
   
}

var autoSugPointer = new Array();

function keyBoardNav(e, id)
{
    
    //alert(id);

    var keyCode = getKeyCode(e, 'keydown');

    if (keyCode == 40)
    {
        if (autoSugPointer[id] >= 0 && autoSugPointer[id] < countSuggestions[id])
        {
            if (autoSugPointer[id] != 0 && autoSugPointer[id] != countSuggestions[id])
            {
                revertAutoSuggestKeyNav(autoSugPointer[id], id);
            }
            autoSugPointer[id] ++;
            changeAutoSuggestKeyNav(autoSugPointer[id], id);
            if (autoSugPointer[id] > 6)
            {
                getElemId(searchID[id]).scrollTop = 30;
            }
        }
    }
    else if (keyCode == 38)
    {
        if (autoSugPointer[id] > 1)
        {
            revertAutoSuggestKeyNav(autoSugPointer[id], id);
            autoSugPointer[id] --;
            changeAutoSuggestKeyNav(autoSugPointer[id], id);
            if (autoSugPointer[id] <= 2)
            {
                getElemId(searchID[id]).scrollTop = 0;
            }
        }
    }
    else if (keyCode == 13 && autoSugPointer[id])
    {
        var str = getElemId(id + "-" + autoSugPointer[id]).innerHTML;
        //alert(str);
        var strid;
        insertKeyword(str , strid, id);
    }

}

function changeAutoSuggestKeyNav(id, ID)
{
    getElemId(ID + "-" + id).style.backgroundColor = "#555";
    getElemId(ID + "-" + id).style.color = "#FFF";   	
}

function revertAutoSuggestKeyNav(id, ID)
{
    getElemId(ID + "-" + id).style.backgroundColor = "#F9F9F9";
    getElemId(ID + "-" + id).style.color = "#006";   	
}


function hideSuggestions(id)
{
    //alert(id);
    try
    {
        var listWrapID = getElemId(targetID[id]);
        // alert(listWrapID.value);
        listWrapID.style.visibility = "hidden";	
        
    // $(listWrapID).fadeOut('fast');
       
    }
    catch(e){}
}

function insertKeyword(value, valueid, inputid)
{

    var list = inputid.split('_'); 
//    alert(getElemId(list[0]).value);
    
    hideSuggestions(inputid);
    getElemId(inputID[inputid]).value = value;
    getElemId(list[0]).value = valueid;
    
    // alert(getElemId(list[0]).value);
     
    getElemId(inputID[inputid]).focus();
}



function getCountry(countryname){
    //alert("domain: " + domain + " " + "host: " + host + " " + "db: " + dbname + " " + username + " " + password + " " + tableprefix); exit();
    //alert(countryname); exit();
    //alert('heloo'); // exit();
    //document.getElementById('errordiv').style.display = 'block';
    //document.getElementById('message').innerHTML = "Checking Connection...";
   
    //$('#countrylist').attr('size',6);
    //    jQuery('#countrylist').attr('height','100px');
    //    jQuery('#countrylist').attr('size',6);
    //    jQuery('#countrylist').attr('z-index','995');
    
    //document.getElementById('setselectbox').innerHTML = 'Please Wait...';
    document.getElementById('setselectbox').style.display='block';
    //document.getElementById('setselectbox').style.border = 'brown solid 1px'; 
    var pars = "module=ZSELEX&type=ajax&func=getCountry&input=" + countryname;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            //async: true,
            onComplete:myFunctiongetCountryResponses
        });
   
}

function myFunctiongetCountryResponses (req)    
{
    
    var countries;
    var select;
   
    if (req.status != 200) 
    {
        
        pnshowajaxerror(req.responseText);
        return;
    }
    //alert('hellooooo'); exit();
    
  
    var json = pndejsonize(req.responseText);
   
   
    countries = json.values;
    select = json.select;
    //alert("myObject is " + error.toSource()); exit();
    //alert(countries);  exit();
    
    //alert(select);
    
   
    document.getElementById('setselectbox').innerHTML = countries;
    //document.getElementById('setselectbox').style.display='block';
    //document.getElementById('setselectbox').style.border = 'brown solid 1px'; 
    document.getElementById('replaceSelect').innerHTML = select;
//openDropdown('countrylist');
//    jQuery('#countrylist').attr('height','100px');
//    jQuery('#countrylist').attr('size',6);
//    jQuery('#countrylist').attr('z-index','995');

    
}




function getVal(id , value){
    
    //alert('hiiii'); exit();
    //alert(value);
    //var val = jQuery('#countrytext').val();
     
    //alert(val);
    jQuery('#countrytext').val(value);
    //document.getElementById('countrytext').value = value;
    document.getElementById('parentcountry').value = id;
    document.getElementById('setselectbox').innerHTML = '';
    
// jQuery('#countrylist').attr('height','100px');
//jQuery('#countrylist').attr('size',0);
//jQuery('#countrylist').attr('top','0');
//jQuery('#countrylist').attr('z-index','995');
//document.getElementById("countrylist").style.top = '0';
//document.getElementById("countrylist").style.height = '22px';
    
}

function getlp(id){
    
    //alert('hiii'); exit();
    //alert(id); 
    var val = "#" +id;
    var str = jQuery(val).text();
    //alert(str); exit();
    document.getElementById('countrytext').value = str;
    document.getElementById('parentcountry').value = id;
    document.getElementById('setselectbox').innerHTML = '';
    document.getElementById('setselectbox').style.display='none';
//alert(str); 

    
//var text = $('#menu_selected').text();
    
//alert(text);
}


function chagecolor(id){
    //alert('hii');
    document.getElementById(id).style.background='white';
}
function hidecolor(id){
    //alert('hii');
    document.getElementById(id).style.background='#eee';
}


function out(){
    
    //jQuery('#countrylist').attr('size',0);
    // document.getElementById("countrylist").style.height = '22px';
    //document.getElementById("countrylist").style.top = '0';
    
    //alert('works...');
    document.getElementById('setselectbox').innerHTML = '';
    document.getElementById('countrytext').value = 'search...';
    document.getElementById('setselectbox').style.display='none';
    
}

function outSelect(){
    
    document.getElementById('setselectbox').innerHTML = '';
    document.getElementById('setselectbox').style.display='none';
    
}



function resetCountryDropdown(){
    //alert('hii');
    document.getElementById('setselectbox').innerHTML = '';
    document.getElementById('setselectbox').style.display='none';
    var countryname = '';
    //document.getElementById('setselectbox').innerHTML = 'Please Wait...';
    //document.getElementById('setselectbox').style.display='block';
    var pars = "module=ZSELEX&type=ajax&func=resetCountryDropdown&input=" + countryname;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            //async: true,
            onComplete:myFunctionresetCountryDropdownResponses
        });
   
}

function myFunctionresetCountryDropdownResponses (req)    
{
    
  
    var select;
   
    if (req.status != 200) 
    {
        
        pnshowajaxerror(req.responseText);
        return;
    }
    //alert('hellooooo'); exit();
    
  
    var json = pndejsonize(req.responseText);
   
   
    select = json.select;
    //alert("myObject is " + error.toSource()); exit();
    //alert(countries);  exit();
    
    //alert(select);
   
    document.getElementById('replaceSelect').innerHTML = select;
//openDropdown('countrylist');
//    jQuery('#countrylist').attr('height','100px');
//    jQuery('#countrylist').attr('size',6);
//    jQuery('#countrylist').attr('z-index','995');

    
}




function getRegionShopListCombo(region_id , country_id){   //CALLED ON REGION
    
    //alert(region_id);  exit();
    //alert(country_id);  exit();
  
    //alert(hcountry);
    //alert("city: "+city_id); exit();
    
    document.getElementById('startval').value = '0';
    //document.getElementById('endval').value = '5' ;
    
    //var hcountry =  document.getElementById('hcountry').value;
    
    var hcountry =  document.getElementById('hcountry').value;
    var hregion =  document.getElementById('hregion').value;
    var hcity =  document.getElementById('hcity').value;
    var harea =  document.getElementById('harea').value;
    
    //var hcountry =  country_id;
    
    //var pars = "module=ZSELEX&type=ajax&func=getRegionShopList&region_id=" + region_id + "&country_id=" + hcountry;
    var pars = "module=ZSELEX&type=ajax&func=getShopsListAll&area_id=" + harea + "&city_id=" + hcity + "&region_id="+hregion+"&country_id="+hcountry;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            onComplete:myFunctiongetRegionShopListComboResponses
        });
 
}


function myFunctiongetRegionShopListComboResponses (req)    
{
    //alert('hiii'); exit();
    if (req.status != 200) 
    {
        
        pnshowajaxerror(req.responseText);
        return;
    }
    // alert(req.responseText.length);   exit();
    //alert(req.responseText);  exit();
  
    document.getElementById('shop-div').innerHTML = req.responseText;
    
    jQuery("#shop-combo").ZselexCombo({
        emptyText: "Choose a shop..."
    //autoFill: true
    //triggerSelected: true
    });
    
//getShopDetails(document.getElementById('hshop').value)
//getAdDetails();
//getShopAd();
    
}


///////////////////////////////////////////////////////////////////////////////// 
function getShopss(city_id){
    //alert(region_id);
    var hcountry =  document.getElementById('hcountry').value;
    var hregion =  document.getElementById('hregion').value;
    var hcity =  document.getElementById('hcity').value;
    var harea =  document.getElementById('harea').value;
    //var pars = "module=ZSELEX&type=ajax&func=getShopsList&city_id=" + city_id;
    var pars = "module=ZSELEX&type=ajax&func=getShopsListAll&area_id=" + harea + "&city_id=" + hcity + "&region_id="+hregion+"&country_id="+hcountry;
    //alert( "country_id :" + value); exit();
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            //async: true,
            onComplete:myFunctiongetShopssResponses
        });
      
}
function myFunctiongetShopssResponses (req)    {  
    //alert('cities'); exit();
    var shops;
    if (req.status != 200) 
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    //var json = pndejsonize(req.responseText);
    //shops = json.cities;
    //alert(cities); exit();
    document.getElementById('shop-div').innerHTML = req.responseText;
    jQuery("#shop-combo").ZselexCombo({
        emptyText: "Choose a shop..."
    //autoFill: true
    //triggerSelected: true
    });
//getShopDetails(document.getElementById('hshop').value)
//getAdDetails();
//getShopAd();
}
/////////////////////////////////////////////////////////////////////////////////////////////
    
///////////////////////////////////////////////////////////////////////////////// 
function getCitiess(region_id , parentCity){
    // alert(region_id);
   
    //alert("city :" + parentCity);
    var hcountry =  document.getElementById('hcountry').value;
    
    // alert(hcountry);
    var pars = "module=ZSELEX&type=ajax&func=getCitiess1&region_id=" + region_id + "&country_id="+hcountry+"&parentCity="+parentCity;
    //alert( "country_id :" + value); exit();
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            //async: true,
            onComplete:myFunctiongetCitiessResponses
        });
      
}
function myFunctiongetCitiessResponses (req)    {  
    //alert('cities'); exit();
    var cities;
    if (req.status != 200) 
    {
        pnshowajaxerror(req.responseText);
        return;
    }
    var json = pndejsonize(req.responseText);
    cities = json.cities;
    //alert(cities); exit();
    // document.getElementById('city-div').innerHTML = cities;
    jQuery(".city").html(cities);
    jQuery("#city-combo").ZselexCombo({
        emptyText: "Choose a city..."
    //autoFill: true
    //triggerSelected: true
    });
    
//getShopDetails(document.getElementById('hshop').value)
//getAdDetails();
//getShopAd();
}
/////////////////////////////////////////////////////////////////////////////////////////////
    
///////////////////////////////////////////////////////////////////////////////////////////// 
function getRegionss(value , parentRegion){
    //alert('works'); exit();
    var pars = "module=ZSELEX&type=ajax&func=getRegionss1&id=" + value + "&parentReg="+parentRegion;
    //alert( "country_id :" + value); exit();
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            //async: true,
            onComplete:myFunctiongetRegionssResponses
        });

}

function myFunctiongetRegionssResponses (req)  {   
    //alert('hiiiii'); exit();

    var select;

    if (req.status != 200) 
    {

        pnshowajaxerror(req.responseText);
        return;
    }

    var json = pndejsonize(req.responseText);

    select = json.select;

    // document.getElementById('region-div').innerHTML = select;
    jQuery(".regions").html(select);

    jQuery("#region-combo").ZselexCombo({
        emptyText: "Choose a region..."
    //autoFill: true
    //triggerSelected: true
    });
    
//getShopDetails(document.getElementById('hshop').value)
//getAdDetails();
//getShopAd();
}

//////////////////////////////////////////////////////////////////////////
       
       



function getCountryCityList(country_id , parentCity){   //CALLED ON COUNTRY
    
    //alert(region_id);  exit();
    //alert(country_id);  exit();
    
    // alert(parentCity);  exit();
  
    
    var pars = "module=ZSELEX&type=ajax&func=getCountryCityList2&country_id=" + country_id + "&parentCity="+parentCity;
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
    //alert(req.responseText);  exit();
   
    jQuery(".city").html(req.responseText);
    //document.getElementById('city-div').innerHTML = req.responseText;
    
    jQuery("#city-combo").ZselexCombo({
        emptyText: "Choose a city..."
    //autoFill: true
    //triggerSelected: true
    });
// getShopDetails(document.getElementById('hshop').value)
//getAdDetails();
//getShopAd();
    
}

function getCountryShopList(country_id){   //CALLED ON COUNTRY
    
    
    
    var hcountry =  document.getElementById('hcountry').value;
    var hregion =  document.getElementById('hregion').value;
    var hcity =  document.getElementById('hcity').value;
    var harea =  document.getElementById('harea').value;
    
    
    //var pars = "module=ZSELEX&type=ajax&func=getCountryShopList&country_id=" + country_id;
    var pars = "module=ZSELEX&type=ajax&func=getShopsListAll&area_id=" + harea + "&city_id=" + hcity + "&region_id="+hregion+"&country_id="+hcountry;
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
  
    document.getElementById('shop-div').innerHTML = req.responseText;
    
    jQuery("#shop-combo").ZselexCombo({
        emptyText: "Choose a shop..."
    //autoFill: true
    //triggerSelected: true
    });
    
//getShopDetails(document.getElementById('hshop').value)
//getAdDetails();
//getShopAd();
    
}



function getCountryAreaList(country_id , parentArea){   //CALLED ON COUNTRY
    
    //alert(region_id);  exit();
    //alert(country_id);  exit();
  
    var currentId = country_id;
 
    var hcountry =  document.getElementById('hcountry').value;
    var hregion =  document.getElementById('hregion').value;
    var hcity =  document.getElementById('hcity').value;
    //alert(hcountry); exit();
    
    var pars = "module=ZSELEX&type=ajax&func=getAreaList1&city_id=" + hcity + "&region_id="+hregion+"&country_id="+hcountry+"&parentArea="+parentArea+"&currentId="+currentId;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            onComplete:myFunctiongetCountryAreaListResponses
        });
   
}


function myFunctiongetCountryAreaListResponses (req)    
{
    if (req.status != 200) 
    {
        
        pnshowajaxerror(req.responseText);
        return;
    }
    // alert(req.responseText.length);   exit();
    // alert(req.responseText);  exit();
  
    //document.getElementById('area-div').innerHTML = req.responseText;
    jQuery(".area").html(req.responseText);
    
    jQuery("#area-combo").ZselexCombo({
        emptyText: "Choose a area..."
    //autoFill: true
    //triggerSelected: true
    });
    
//getShopDetails(document.getElementById('hshop').value)
//getAdDetails();
//getShopAd();
    
}




function getRegionAreaList(region_id , parentArea){   //CALLED ON REGION
    
    //alert(region_id);  exit();
    //alert(country_id);  exit();
  
     var currentId = region_id;
    //alert(parentArea); 
    
    var pars = "module=ZSELEX&type=ajax&func=getRegionAreaList1&region_id=" + region_id + "&parentArea="+parentArea+"&currentId="+currentId;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            onComplete:myFunctiongetRegionAreaListResponses
        });
   
}


function myFunctiongetRegionAreaListResponses (req)    
{
    if (req.status != 200) 
    {
        
        pnshowajaxerror(req.responseText);
        return;
    }
    // alert(req.responseText.length);   exit();
    // alert(req.responseText);  exit();
  
    //document.getElementById('area-div').innerHTML = req.responseText;
    
    jQuery(".area").html(req.responseText);
    
    jQuery("#area-combo").ZselexCombo({
        emptyText: "Choose a area..."
    //autoFill: true
    //triggerSelected: true
    });
    
//getShopDetails(document.getElementById('hshop').value)
//getAdDetails();
//getShopAd();
    
}


function getCityAreaList(city_id , parentArea){   //CALLED ON CITY
    
    //alert(region_id);  exit();
    //alert(country_id);  exit();
    
    //alert(parentArea);  exit();
    var currentId = city_id;
    
    var hcountry =  document.getElementById('hcountry').value;
    var hregion =  document.getElementById('hregion').value;
    var hcity =  document.getElementById('hcity').value;
  
    //alert(hcountry);
    //alert("city: "+hcity); exit();
   
    
   // alert(parentArea); 
    var pars = "module=ZSELEX&type=ajax&func=getAreaList1&city_id=" + hcity + "&region_id="+hregion+"&country_id="+hcountry+"&parentArea="+parentArea+"&currentId="+currentId;
    //var pars = "module=ZSELEX&type=ajax&func=getCityAreaList&city_id=" + hcity + "&parentArea="+parentArea;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            onComplete:myFunctiongetCityAreaListResponses
        });
   
}


function myFunctiongetCityAreaListResponses (req)    
{
    if (req.status != 200) 
    {
        
        pnshowajaxerror(req.responseText);
        return;
    }
    // alert(req.responseText.length);   exit();
    // alert(req.responseText);  exit();
  
    //document.getElementById('area-div').innerHTML = req.responseText;
    
    jQuery(".area").html(req.responseText);
    
    jQuery("#area-combo").ZselexCombo({
        emptyText: "Choose a area..."
    //autoFill: true
    //triggerSelected: true
    });
    
//getShopDetails(document.getElementById('hshop').value)
//getAdDetails();
//getShopAd();
    
}




function getAreaShopList(area_id , city_id){   //CALLED ON AREA
    
    //alert(area_id);  exit();
    //alert(country_id);  exit();
  
    //alert(hcountry);
    //alert("city: "+city_id); exit();
    document.getElementById('startval').value = '0';
    //document.getElementById('endval').value = '5' ;
    
    var hcountry =  document.getElementById('hcountry').value;
    var hregion =  document.getElementById('hregion').value;
    var hcity =  document.getElementById('hcity').value;
    var harea =  document.getElementById('harea').value;
    
    
    //var pars = "module=ZSELEX&type=ajax&func=getAreaShopList&area_id=" + area_id + "&city_id=" + city_id + "&region_id="+hregion+"&country_id="+hcountry;
    var pars = "module=ZSELEX&type=ajax&func=getShopsListAll&area_id=" + area_id + "&city_id=" + city_id + "&region_id="+hregion+"&country_id="+hcountry;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            onComplete:myFunctiongetAreaShopListResponses
        });
   
}


function myFunctiongetAreaShopListResponses (req)    
{
    if (req.status != 200) 
    {
        
        pnshowajaxerror(req.responseText);
        return;
    }
    // alert(req.responseText.length);   exit();
    //alert(req.responseText);  exit();
  
    document.getElementById('shop-div').innerHTML = req.responseText;
    
    jQuery("#shop-combo").ZselexCombo({
        emptyText: "Choose a shop..."
    //autoFill: true
    //triggerSelected: true
    });
    
//getShopDetails(document.getElementById('hshop').value)
//getAdDetails();
//getShopAd();
    
}


function getAllCountry(country_id){   //CALLED ON RESET
    
    //alert(region_id);  exit();
    //alert(country_id);  exit();
  
    //alert(hcountry);
    //alert("city: "+city_id); exit();
    document.getElementById('startval').value = '0';
    //document.getElementById('endval').value = '5' ;
    
    
    var pars = "module=ZSELEX&type=ajax&func=getAllCountry";
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            onComplete:myFunctiongetAllCountryResponses
        });
   
}



function myFunctiongetAllCountryResponses (req)    
{
    if (req.status != 200) 
    {
        
        pnshowajaxerror(req.responseText);
        return;
    }
    // alert(req.responseText.length);   exit();
    // alert(req.responseText);  exit();
  
    document.getElementById('country-div').innerHTML = req.responseText;
    
    jQuery("#country-combo").ZselexCombo({
        emptyText: "Choose a country..."
    //autoFill: true
    //triggerSelected: true
    });
    
    
    
    
//getShopDetails(document.getElementById('hshop').value)
//getAdDetails();
//getShopAd();
    
}



function getAllCat(){  
    
    //alert(region_id);  exit();
    //alert(country_id);  exit();
  
    //alert(hcountry);
    //alert("city: "+city_id); exit();
    document.getElementById('startval').value = '0';
    //document.getElementById('endval').value = '5' ;
    
    
    var pars = "module=ZSELEX&type=ajax&func=getAllCat";
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            onComplete:getAllCatResponse
        });
   
}




function getAllCatResponse (req)    
{
    if (req.status != 200) 
    {
        
        pnshowajaxerror(req.responseText);
        return;
    }
    // alert(req.responseText.length);   exit();
    // alert(req.responseText);  exit();
  
    document.getElementById('category').innerHTML = req.responseText;
   
}






function myFunctionOnChangeResponses (req)    
{
    if (req.status != 200) 
    {
        
        pnshowajaxerror(req.responseText);
        return;
    }
    var json = pndejsonize(req.responseText);
    var lat = json.lat;
    var lng = json.lng;
    var type = json.type;
    var name;
    var id;
    document.getElementById('cLat').value = lat;
    document.getElementById('cLng').value = lng;
    //alert(type);
    //alert(json.region);
    if(type=='country'){
        name = json.cntry;
        id = document.getElementById('hcountry').value
    } 
    else if(type=='region'){
        name = json.region;
        id = document.getElementById('hregion').value
    }
    else if(type=='city'){
        name = json.city;
        id = document.getElementById('hcity').value
    }
    
    else if(type=='area'){
        name = json.area;
        id = document.getElementById('harea').value
    }
    else if(type=='shop'){
        name = json.shop;
        id = document.getElementById('hshop').value
    }
    //alert(lat);
    //alert(name + " " + id + " " + " " +type);
    
    load(name , id , type);
    //initMap(name , id , type);
    getShopDetails(document.getElementById('hshop').value)
    getAdDetails();
    getShopAd();
   
    
}


function loadCatMap(){
    
    var type = document.getElementById('type').value
    
    //alert(type);
    
    if(type == 'country'){
        countryOnChange(document.getElementById('name').value , 'country')
    }
    else if(type == 'region'){
        regionOnChange(document.getElementById('name').value , 'area')
    }
    else if(type == 'city'){
        cityOnChange(document.getElementById('name').value , 'city')
    }
    else if(type == 'shop'){
        shopOnChange(document.getElementById('name').value , 'shop')
    }
    else if(type == 'area'){
        areaOnChange(document.getElementById('name').value , 'area')
    }
    else{
        load();
        getShopDetails(document.getElementById("hshop").value);
        getAdDetails(); 
        getShopAd();
    }
    
}


jQuery("#category").live('change',function(){
      
    //alert('workssss');
    loadCatMap();
}

);


jQuery("#branch").live('change',function(){
      
    //alert('workssss');
    document.getElementById("hbranch").value =  this.value;
    loadCatMap();
}

);




function countryOnChange(country){   // called on country on change
 
  
    document.getElementById('hregion').value = '';
    document.getElementById('hcity').value = '';
    document.getElementById('harea').value = '';
    document.getElementById('hshop').value = '';
    
    var parentCountry =  document.getElementById('parentcountry').value;
    var parentRegion =  document.getElementById('parentregion').value;
    var parentCity =  document.getElementById('parentcity').value;
    var parentArea =  document.getElementById('parentarea').value;
    
    
    
    var parentCountryOld =  document.getElementById('parentcountryold').value;
    var parentRegionOld =  document.getElementById('parentregionold').value;
    var parentCityOld =  document.getElementById('parentcityold').value;
    var parentAreaOld =  document.getElementById('parentareaold').value;
    
    //alert('hiiii'); exit();
    
    //alert(parentRegion); exit();
    
    //alert(parentArea); exit();
    
    document.getElementById('hcountry').value = document.getElementById('country-combo').value; 
  
          
    getRegionss(document.getElementById('country-combo').value , parentRegion);
    if(parentRegion<1 || document.getElementById('country-combo').value < 1){
        getCountryCityList(document.getElementById('country-combo').value , parentCity);
    }
    
   
   
    if(parentCity<1 || (parentCity < 1 && parentRegion <1) || document.getElementById('country-combo').value < 1){
        //alert('hii');
        getCountryAreaList(document.getElementById('country-combo').value , parentArea);
    }
   
    
}






function regionOnChange(region , type){   // called on region on change
    
    
    document.getElementById('hcity').value = '';
    document.getElementById('harea').value = '';
    document.getElementById('hshop').value = '';
    var parentCity = document.getElementById('parentcity').value;
    var parentArea = document.getElementById('parentarea').value;
    
    //alert(parentCity);
    
    document.getElementById('parentcityold').value = document.getElementById('region-combo').value;
    document.getElementById('hregion').value = document.getElementById('region-combo').value;
           
    getCitiess(document.getElementById('region-combo').value , parentCity);
   
    if(parentCity < 1 || document.getElementById('region-combo').value < 1) {
        getRegionAreaList(document.getElementById('region-combo').value , parentArea);
   
    }
  
   
/*
    var pars = "module=ZSELEX&type=ajax&func=test4all&region="+region+"&country="+document.getElementById('hcountryname').value+"&types="+type;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            onComplete:myFunctionOnChangeResponses
        });       
    
    */
}



function cityOnChange(city , type){   // called on city on change
   
    //document.getElementById('region-combo').value = '';
    document.getElementById('harea').value = '';
    
    //alert('hiii');
    document.getElementById('parentcityold').value = document.getElementById('city-combo').value;
    document.getElementById('hcity').value = document.getElementById('city-combo').value; 
   
    var parentArea = document.getElementById('parentarea').value;
      
      //alert(parentArea);
    getCityAreaList(document.getElementById('city-combo').value , parentArea);
    
       
/*
    var pars = "module=ZSELEX&type=ajax&func=test4all&city="+city+"&region="+document.getElementById('hregionname').value+"&country="+document.getElementById('hregionname').value+"&types="+type;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            onComplete:myFunctionOnChangeResponses
        }); 
    */
   
    
    
}

function areaOnChange(area , type){   // called on city on change
   
    //alert(type);
    //document.getElementById('region-combo').value = '';
    document.getElementById('harea').value = document.getElementById('area-combo').value; 
    document.getElementById('hareaname').value = area;
    document.getElementById('hshop').value = '';
    
    document.getElementById('hshop_name').value = '';
    //document.getElementById('harea').value = document.getElementById('area-combo').value; 
      
    getAreaShopList(document.getElementById('area-combo').value , document.getElementById('hcity').value);
    //getShopss(document.getElementById('city-combo').value);
    
    /*
    var pars = "module=ZSELEX&type=ajax&func=test4all&area="+area+"&types="+type;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            onComplete:myFunctionOnChangeResponses
        }); 
    */
   
    load();
    getShopDetails(document.getElementById('hshop').value)
    getAdDetails();
    getShopAd();
    
    
}



function shopOnChange(shop){   // called on shop on change
    document.getElementById('startval').value = '0';
    document.getElementById('hshop').value = document.getElementById('shop-combo').value;
    document.getElementById('hshop_name').value = shop;
   
    
    
    /*
    var pars = "module=ZSELEX&type=ajax&func=test4all&shop="+shop+"&types=shop&shop_id="+document.getElementById('hshop').value;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            onComplete:myFunctionOnChangeResponses
        }); 
    */
   
    load();
    getShopDetails(document.getElementById('hshop').value)
    getAdDetails();
    getShopAd();
        
//getShopDetails(document.getElementById('hshop').value)
//getAdDetails();
//getShopAd();
    
}


function categoryOnChange(category){   // called on shop on change
    document.getElementById('startval').value = '0';
    //document.getElementById('hshop').value = document.getElementById('category').value;
   
    
    var pars = "module=ZSELEX&type=ajax&func=test4all&category="+category+"&types=category&shop_id="+document.getElementById('hcategory').value;
    /*
   var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            onComplete:myFunctionOnChangeResponses
        });  
    */
    load();  
    getShopDetails(document.getElementById('hshop').value)
    getAdDetails();
    getShopAd();
    
}



//////////////////////////////////////////////////////////////////////////    
jQuery(function () {
    //  alert('hii');
    jQuery("#country-combo").ZselexCombo({
        emptyText: "Choose a country..."
    //autoFill: true
    //triggerSelected: true
    });


    jQuery("#region-combo").ZselexCombo({
        emptyText: "Choose a region..."
    //autoFill: true
    //triggerSelected: true
    });

    jQuery("#city-combo").ZselexCombo({
        emptyText: "Choose a city..."
    //autoFill: true
    //triggerSelected: true
    });
    
    jQuery("#area-combo").ZselexCombo({
        emptyText: "Choose a area..."
    //autoFill: true
    //triggerSelected: true
    });
                
    jQuery("#shop-combo").ZselexCombo({
        emptyText: "Choose a shop..."
    //autoFill: true
    //triggerSelected: true
    });
//////////////////////////////////////////////////////////////////////////
      
  
  

}); /////



        
jQuery("#country-combo").live('change',function(){
    //alert(this.value);
    //alert('hellooo');
    if(document.getElementById('hcountry').value > 0 && this.value==0 ){
        //alert('works');    
        countryOnChange('');
    }
    if(this.value > 0) {
        //alert(jQuery(this).find("option:selected").text());
       
        countryOnChange(jQuery(this).find("option:selected").text());
    }
}

);
        
jQuery("#region-combo").live('change',function(){   //called on region on change
    if(document.getElementById('hregion').value > 0 && this.value==0 ){  
       
        var type;
       
        regionOnChange('' , type)
    }
    if(this.value > 0) {
        // alert('works');
        regionOnChange(jQuery(this).find("option:selected").text() , 'region')
    }

});
        
        
jQuery("#city-combo").live('change',function(){    //called on city on change
    //alert(document.getElementById('hcity').value);
    // alert(this.value);
    if(document.getElementById('hcity').value > 0 && this.value<1 ){  
        //alert('works2');
       
        var type;
        
        cityOnChange('' , type);
    }
    if(this.value > 0) {
        //alert('cuty');
        
        cityOnChange(jQuery(this).find("option:selected").text() , 'city');
    }
}); 
    

jQuery("#area-combo").live('change',function(){
    // alert('helooo');
    
    if(document.getElementById('harea').value > 0 && this.value < 1 ){  
        
        var type;
       
        areaOnChange('' , type);
    }
          
    if(this.value > 0) {
        // alert('helloo');
        document.getElementById('type').value = 'area';
        document.getElementById('name').value = jQuery(this).find("option:selected").text();
        areaOnChange(jQuery(this).find("option:selected").text() , 'area');
    }
}); 
               
jQuery("#shop-combo").live('change',function(){
    
    
    if(document.getElementById('hshop').value > 0 && this.value < 1 ){  
        //alert('works2');
        document.getElementById('hshop_name').value = '';
        //document.getElementById('type').value = 'region';
        document.getElementById('name').value = jQuery(this).find("option:selected").text();
        var type;
      
        shopOnChange('' , type);
    }
         
    if(this.value > 0) {
        document.getElementById('type').value = 'shop';
        document.getElementById('name').value = jQuery(this).find("option:selected").text(); 
        shopOnChange(jQuery(this).find("option:selected").text());
    }
                
});

      
      
function resets(){
    // alert('hellooo');
    /*
    document.getElementById('country-combo').value = '';
    document.getElementById('region-combo').value = '';
    document.getElementById('city-combo').value = '';
    document.getElementById('shop-combo').value = '';
      */  
    document.getElementById('hcountry').value = '';
    document.getElementById('hregion').value = '';
    document.getElementById('hcity').value = '';
    document.getElementById('harea').value = '';
    document.getElementById('hshop').value = '';
    document.getElementById('hcategory').value = '';
   
    
    
    getAllCountry();
    getAllCat();
    getRegionss(document.getElementById('hcountry').value);
    getCountryCityList(document.getElementById('hcountry').value);
    getCountryAreaList(document.getElementById('hcountry').value);
    getCountryShopList(document.getElementById('hcountry').value);
     
//load();
// getShopDetails(document.getElementById('hshop').value)
//getAdDetails();
// getShopAd();
                 
                 
//    var pars = "module=ZSELEX&type=ajax&func=resetResponse&test=123";
//    var myAjax = new Ajax.Request(
//        "ajax.php",
//        {
//            method: 'get',
//            parameters: pars,
//            onComplete:myFunctionresetResponses
//        });
      
}
      
      
      
function myFunctionresetResponses (req)    
{
    
    // alert('helloooo');
    if (req.status != 200) 
    {
        
        pnshowajaxerror(req.responseText);
        return;
    }
    
    load();
    getShopDetails(document.getElementById('hshop').value)
    getAdDetails();
    getShopAd();
    
}












 











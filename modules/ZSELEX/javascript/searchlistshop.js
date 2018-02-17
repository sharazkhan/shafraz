


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




function chagecolor(id){
    //alert('hii');
    document.getElementById(id).style.background='white';
}
function hidecolor(id){
    //alert('hii');
    document.getElementById(id).style.background='#eee';
}



//////////////////////////////////////////////////////////////
       
function getCityListAll(){   //CALLED ON ALL
    
    //alert(region_id);  exit();
    //alert(country_id);  exit();
    //alert('helloo');
    var hcountry =  document.getElementById('hcountry').value;
    var hregion =  document.getElementById('hregion').value;
    var post_city_id;
    if(document.getElementById('post_city_id')){
        post_city_id =  document.getElementById('post_city_id').value;
    }
    else{
        post_city_id = '';
    }
  
    
    
    var pars = "module=ZSELEX&type=ajax&func=getCityListAll&country_id=" + hcountry + "&region_id="+hregion+"&post_city_id="+post_city_id;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            onComplete:getCityListAllResponses
        });
   
} 


function getCityListAllResponses (req)    
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





function getAreaListAll(){   //CALLED ON ALL
    
    //alert(region_id);  exit();
    //alert(country_id);  exit();
  
    var hcountry =  document.getElementById('hcountry').value;
    var hregion =  document.getElementById('hregion').value;
    var hcity =  document.getElementById('hcity').value;
    var post_area_id;
    if(document.getElementById('post_area_id')){
        post_area_id =  document.getElementById('post_area_id').value;
    }
    else{
        post_area_id = '';
    }
    //alert(hregion); exit();
    
    var pars = "module=ZSELEX&type=ajax&func=getAreaListAll&city_id=" + hcity + "&region_id="+hregion+"&country_id="+hcountry+"&post_area_id="+post_area_id;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            onComplete:getAreaListAllResponses
        });
   
}


function getAreaListAllResponses (req)    
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





function getAllCountry(country_id){   //CALLED ON RESET
    
    //alert('helloo');  exit();
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





function getRegionsAll(){
    //alert('works'); exit();
    var hcountry =  document.getElementById('hcountry').value;
    var post_region_id;
    if(document.getElementById('post_region_id')){
        post_region_id =  document.getElementById('post_region_id').value;
    }
    else{
        post_region_id = ''; 
    }
    var pars = "module=ZSELEX&type=ajax&func=getRegionsAll&id=" + hcountry + "&post_region_id="+post_region_id;
    //alert( "country_id :" + value); exit();
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            //async: true,
            onComplete:getRegionsAllResponses
        });

}

function getRegionsAllResponses (req)  {   
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
    // jQuery("#region-div").html(select);
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


function countryOnChange(country){   // called on country on change
 

    document.getElementById('hregion').value = '';
    document.getElementById('hcity').value = '';
    document.getElementById('harea').value = '';
    document.getElementById('hshop').value = '';
    
    
    //alert('hiiii'); exit();
    
    document.getElementById('hcountry').value = document.getElementById('country-combo').value; 
  
          
    //getRegionss();
    getRegionsAll();
    //getCountryCityList(document.getElementById('country-combo').value);
    getCityListAll();
    // getCountryAreaList(document.getElementById('country-combo').value);
   
    getAreaListAll();
   
    
}






function regionOnChange(region , type){   // called on region on change
    
    
    document.getElementById('hcity').value = '';
    document.getElementById('harea').value = '';
    document.getElementById('hshop').value = '';
    
    //alert('hellooo');
   
    document.getElementById('hregion').value = document.getElementById('region-combo').value;
           
    //getCitiess(document.getElementById('region-combo').value);
    getCityListAll();
    //getRegionAreaList(document.getElementById('region-combo').value);
    getAreaListAll();
   
       
  
   
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
    document.getElementById('hcity').value = document.getElementById('city-combo').value; 
   
      
      
    //getCityAreaList(document.getElementById('city-combo').value);
    getAreaListAll();
    
       
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
    
    jQuery("#category-combo").ZselexCombo({
        emptyText: "Choose a category..."
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
    //alert('hiii');
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
    

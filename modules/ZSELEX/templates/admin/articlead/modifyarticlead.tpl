

{pageaddvar name='javascript' value='modules/ZSELEX/javascript/zselex_admin_validation.js'}

{pageaddvar name='javascript' value='modules/ZSELEX/javascript/datepicker/protoplasm.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/datepicker/datepicker.js'}
{pageaddvar name='stylesheet' value='modules/ZSELEX/style/datepicker/datepicker.css'}
{pageaddvar name='stylesheet' value='modules/ZSELEX/style/datepicker/protoplasm.css'}



<link rel="stylesheet" type="text/css" href="modules/ZSELEX/style/date/css/datepicker.css">


{shopheader}

<link href="modules/ZSELEX/style/combo/sexy-combo.css" rel="stylesheet" type="text/css" />
<link href="modules/ZSELEX/style/combo/sexy/sexy.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="modules/ZSELEX/javascript/combo/jquery.sexy-combo.js"></script>


<script type="text/javascript" >
    //jQuery.noConflict();
    jQuery(function () {

        //  alert('hii');
        jQuery("#country-combo").ZselexCombo({
            emptyText: Zikula.__("Select Country...")
            //autoFill: true
            //triggerSelected: true
        });
                
        jQuery("#region-combo").ZselexCombo({
            emptyText: Zikula.__("Select Region...")
            //autoFill: true
            //triggerSelected: true
        });
                
        jQuery("#city-combo").ZselexCombo({
            emptyText: Zikula.__("Select City...")
            //autoFill: true
            //triggerSelected: true
        });
                
        jQuery("#area-combo").ZselexCombo({
            emptyText: Zikula.__("Select Area...")
            //autoFill: true
            //triggerSelected: true
        });
                
        jQuery("#category-combo").ZselexCombo({
            emptyText: Zikula.__("Select Category...")
            //autoFill: true
            //triggerSelected: true
        });

    });
            
            
            
    function shopAdLevel(id){
        //alert(id); exit();
        if(id=='COUNTRY'){ //ZSHOP 
    
       
            document.getElementById('countries').style.display = 'block';
            document.getElementById('regions').style.display = 'none';
            document.getElementById('cats').style.display = 'none';
        }
        else if(id=='REGION') {
        
            document.getElementById('countries').style.display = 'none';
            document.getElementById('regions').style.display = 'block';
            document.getElementById('cats').style.display = 'none';
        }
        else if(id=='CITY') {
        
        
            document.getElementById('countries').style.display = 'none';
            document.getElementById('regions').style.display = 'none';
            document.getElementById('cats').style.display = 'block';
        }
        else{
            document.getElementById('countries').style.display = 'none';
            document.getElementById('regions').style.display = 'none';
            document.getElementById('cats').style.display = 'none';
        }
    }

            
        
</script>



<script type="text/javascript">
    function change(id)
    {
    
        //alert('hiiiii');
        ID = document.getElementById(id);
       
    
        if(ID.style.display == "")
        {
            ID.style.display = "none";
        }
        else
        {
            ID.style.display = "";
        }
                
    }
</script>



<style>

    #countriesdiv
    {
        position: relative;
        margin-left: 360px;
    }

    #countrytext
    {
        position: absolute;
        top: 0;
        left: 0;
        z-index: 999;
        padding: 0;
        margin: 0;
    }

    #countrylist
    {
        position: absolute;
        top: 0;
        left: 0;
        padding: 0;
        margin: 0;
    }

    #setselectbox div{
        width:180px;
        border-top:1px solid #ccc;
        cursor:pointer
    }

    #setselectbox  .close{
        border-top:0px;
        cursor:pointer
    }
    .setselectbox{
        display:none;
        width:185px; position:absolute; 
        z-index:999;
        background-color:#eee;
        margin-top: 24px; 

    }
    .AutoFIll label {
        color: #333333;
        display: block;
        float: left;
        font-weight: normal;
        padding: 0.3em 1% 0.3em 0;
        text-align: right;
        width: 100%;
    }
    .AutoFIll textarea{ padding:0px;}

    .AutoFIll input[type="text"],.AutoFIll textarea {
        padding: 0.09em;
    }


    div.sexy {    margin: 0 0 0 0}


    #ajax-container input[type="text"],#ajax-container textarea {
        padding: 0.09em;
    }

    #ajax-container ul {
        margin:0px;
    }
</style>

<div class="z-admin-content-pagetitle">
    {if $item.advertise_id neq ''}
    <h3>{gt text='Update Advertisement'}</h3>
    {else}
    <h3>{gt text='Create Advertisement'}</h3>
    {/if}
</div>

<form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="submitarticlead"}" method="post" enctype="application/x-www-form-urlencoded">
      <div>
        <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
        <input type="hidden" name='formElements[childType]' value="AD" />
        <input type="hidden" name='formElements[elemId]' value="{$item.articlead_id}" />
        <input type='hidden' id='temps' name='temps' value=''>
        <input type="hidden" name='formElements[parentCountry]' id="parentcountry" value="{$item.country_id}" />
        <input type="hidden" name='formElements[parentRegion]' id="parentregion"  value="{$item.region_id}" />
        <input type="hidden" name='formElements[parentCity]' id="parentcity" value="{$item.city_id}" />
        <input type="hidden" name='formElements[parentshop]' id="parentshop"   value="{if $shop_id_fromlist neq  ''}{$shop_id_fromlist}{else}{$item.shop_id}{/if}"/>
        <input type="hidden" name='formElements[shop_id]' id="shop_id" value="{$item.shop_id}" />
        <input type="hidden" name='formElements[levelset]' id="levelset" value="{$item.level}" />

        <fieldset>
            <div class="z-formrow">
                <label for="elemtName">{gt text='Name'}</label>
                <input type='text'  name='formElements[elemtName]' id='elemtName' value='{$item.name}'   />

            </div>

            <div class="z-formrow">
                <label for="elemtDesc">{gt text='Description'}</label>
                <textarea  name='formElements[elemtDesc]' id='elemtDesc' >{$item.description}</textarea>
            </div>


            <div class="z-formrow">
                <label for="keywords">{gt text='Keywords'}</label>
                <textarea  name='formElements[keywords]' id='elemtDesc' >{$item.keywords}</textarea>
            </div>

            {securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADD}
            
            
          <div class="z-formrow">
                 <label for="">{gt text='Level:'}</label>
               <!-- <span onclick="change('changeparents')">modify parents</span>-->
                 <div>{$item.level}</div>
          </div>
          
          {if $item.level neq 'SHOP'} 
          <div class="z-formrow">
                 <label for="">{gt text='Value:'}</label>
               <!-- <span onclick="change('changeparents')">modify parents</span>-->
               {if $item.level eq 'COUNTRY'}
                 <div>{$item.country_name}</div>
                {elseif $item.level eq 'REGION'}
                 <div>{$item.region_name}</div>
                 {elseif $item.level eq 'CITY'}
                 <div>{$item.city_name}</div>
                 {elseif $item.level eq 'AREA'}
                 <div>{$item.area_name}</div>
                 {/if}
                 
          </div>
                 {/if}
            
            <div class="z-formrow">
                <label for="">{gt text='Change above relations'}</label>
                        <input onclick="change('show')" type="checkbox" name="formElements[changelevels]" value="1">
                </div>        

            <div id="show" style="display:none">      

                <div class="z-formrow">

                    <label for="level">{gt text='Select Level'}</label>
                    <select name='formElements[level]' id='level' onChange='shopAdLevel(this.value)'>
                        <option value=''>{gt text='Select Level'}</option>
                        <option value='COUNTRY'  {if $item.level eq 'COUNTRY'} selected='selected' {/if} >{gt text='Country'}</option>
                        <option value='REGION'  {if $item.level eq 'REGION'} selected='selected' {/if} >{gt text='Region'}</option>
                        <option value='CITY'  {if $item.level eq 'CITY'} selected='selected' {/if} >{gt text='City'}</option>
                        <option value='SHOP'  {if $item.level eq 'SHOP'} selected='selected' {/if} >{gt text='Shop'}</option>
                    </select>
                </div>


                <div id="countries" class="AutoFIll" style="height:45px; margin-top: 8px;display:none">
                    <div style="width:28%; float: left;"><label for="country-combo">{gt text='Countries'}</label></div>
                    <div id="ajax-container" style="float:left; padding-left: 10px">
                        <select id="country-combo" name="formElements[country-combo]" size="1">
                            <option value=''>{gt text='Select Country...'}</option>
                            {foreach from=$countries  item='country'}
                            <option value="{$country.country_id}"  {if $item.parentcountry_id eq $country.country_id} selected='selected' {/if}>{$country.country_name|upper}</option>
                            {/foreach}
                        </select>
                    </div>

                </div>



                <div id="regions" class="AutoFIll" style="height:45px; margin-top: 8px;display:none ">
                    <div style="width:28%; float: left;"><label for="region-combo">{gt text='Regions'}</label></div>
                    <div id="ajax-container" class="regions" style="float:left; padding-left: 10px">
                        <select id="region-combo" name="formElements[region-combo]" size="1">
                            <option value=''>{gt text='Select Region...'}</option>
                            {foreach from=$regions  item='region'}
                            <option value="{$region.region_id}"  {if $item.parentregion_id eq $region.region_id} selected='selected' {/if}>{$region.region_name|upper}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>


                <div id="cats"  class="AutoFIll" style="height:45px; margin-top: 8px;display:none ">
                    <div style="width:28%; float: left;"><label for="city-combo">{gt text='City'}</label></div>
                    <div id="ajax-container" class="city"  style="float:left; padding-left: 10px">
                        <select id="city-combo" name="formElements[city-combo]" size="1">
                            <option value=''>{gt text='Select City...'}</option>
                            {foreach from=$cities  item='city'}
                            <option value="{$city.city_id}"  {if $item.parentcity_id eq $city.city_id} selected='selected' {/if}>{$city.city_name|upper}</option>
                            {/foreach}
                        </select>
                    </div>

                </div>

            </div>


            <div class="z-formrow">
                <label for="startdate">{gt text='Start Date'}</label>
                <input type='text'  name='formElements[startdate]' id='startdate' class='startdate' value='{$item.start_date}' />

            </div>


            <div class="z-formrow">
                <label for="enddate">{gt text='End Date'}</label>
                <input type='text'  name='formElements[enddate]' id='enddate'  class='enddate' value='{$item.end_date}' />

            </div>
           

            <div class="z-formrow">
                <label for="typestatus">{gt text='Status'}</label>
                <select id="status" name="formElements[status]" />
                <option value="1" {if $item.adstatus eq '1'} selected='selected' {/if}>{gt text='Active'}</option>
                <option value="0" {if $item.adstatus eq '0'} selected='selected' {/if}>{gt text='InActive'}</option>
                </select>
            </div>
            {/securityutil_checkpermission_block}
            <div class="z-buttons z-formbuttons">
                <button id="zselex_button_submit"  class="z-btgreen" type="submit" onclick="return validate_advertise();" name="action" value="1" title="{gt text='Save this region'}">
                    {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save this Region' }
                    {gt text='Save'}
                </button>
                <a id="zselex_button_cancel" href="{modurl modname="ZSELEX" type="admin" func='viewarticleads' shop_id=$smarty.request.shop_id}" class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
            </div>
    </div>
</form>

<script type="text/javascript">
	
    $('#startdate').DatePicker({
        format:'Y/m/d',
        date: $('#startdate').val(),
        current: $('#startdate').val(),
        starts: 1,
        position: 'r',
        onBeforeShow: function(){
            $('#startdate').DatePickerSetDate($('#startdate').val(), true);
        },
        onChange: function(formated, dates){
            $('#startdate').val(formated);
            if ($('#closeOnSelect input').attr('checked')) {
                $('#startdate').DatePickerHide();
            }
        }
    });



    $('#enddate').DatePicker({
        format:'Y/m/d',
        date: $('#enddate').val(),
        current: $('#enddate').val(),
        starts: 1,
        position: 'r',
        onBeforeShow: function(){
            $('#enddate').DatePickerSetDate($('#enddate').val(), true);
        },
        onChange: function(formated, dates){
            $('#enddate').val(formated);
            if ($('#closeOnSelect input').attr('checked')) {
                $('#enddate').DatePickerHide();
            }
        }
    });
	
</script>



<script type="text/javascript" language="javascript">

    var thisbaseurl='{{$baseurl}}';


    //Control.DatePicker.DateFormat.format(date, format)

    Protoplasm.use('datepicker')
    .transform('.startdate')
    .transform('.enddate')
    document.on('dom:loaded', function () {
        $('daterangepanel').insert(new Control.DatePicker.Panel({
            range: true,
            monthCount: 3,
            icon:thisbaseurl+'zselexdata/calendar.png',
            onSelect: function(start, end) {
                $('daterangelabel').update('<i>Start: '+start+', End: '+end+'</i>');
            }
        }).element);

    });
</script>

{adminfooter}
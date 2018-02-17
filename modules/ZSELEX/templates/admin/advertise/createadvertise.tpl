
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/validation.js'}

<link href="modules/ZSELEX/style/combo/sexy-combo.css" rel="stylesheet" type="text/css" />
<link href="modules/ZSELEX/style/combo/sexy/sexy.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="modules/ZSELEX/javascript/combo/jquery.sexy-combo.js"></script>

<script>
    // jQuery.noConflict();
</script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />

{*{pageaddvar name='javascript' value='modules/ZSELEX/javascript/datepicker/protoplasm.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/datepicker/datepicker.js'}
{pageaddvar name='stylesheet' value='modules/ZSELEX/style/datepicker/datepicker.css'}
{pageaddvar name='stylesheet' value='modules/ZSELEX/style/datepicker/protoplasm.css'}*}

{shopheader}

<style>

    #ajax-container input[type="text"],#ajax-container textarea {
        padding: 0.09em;
    }

    #ajax-container ul {
        margin:0px;
    }
    .AutoFIll textarea{ padding:0px;}

    .AutoFIll input[type="text"],.AutoFIll textarea {
        padding: 0.09em;
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


    div.sexy {    margin: 0 0 0 0}

</style>

<script type="text/javascript" >
    jQuery(function () {
 
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
 
 
    }); 
    
  
    function setLevel(val){
        var adName  = jQuery('#elemtName').val();
        if(val == 'SHOP'){
            var name = jQuery('#shop_name').val();
            //alert(name);
            if(adName==''){
                jQuery('#elemtName').val('SHOP'+'-'+name);
            }
        }
        else{
            return;
        }
  
    }
    
    function setName(val){
        var level = jQuery('#level').val();
        var adName  = jQuery('#elemtName').val();
        // var levelname = jQuery('.levelvalue').val();
        if(level == 'SHOP'){
            var name = jQuery('#shop_name').val();
        }
        else{
            var levelname = val;
            var n=levelname.split("."); 
            var name = n[1];
        }
    
        // alert(level+'-'+name);
        //alert(name);
        if(name!=undefined && adName==''){
            //alert(level+'-'+name);
            jQuery('#elemtName').val(level+'-'+name);
        }
        //eval(countryname);
    
    }
</script>
<div class="z-admin-content-pagetitle">
    {if $item.advertise_id neq ''}
    <h3>{gt text='Update Advertisement'}</h3>
    {else}
    <h3>{gt text='Create Advertisement'}</h3>
    {/if}
</div>

<form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="submitadvertise" shop_id=$smarty.request.shop_id}" method="post" enctype="application/x-www-form-urlencoded">
      <div class="z-panels" id="panel">
        <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
        <input type="hidden" name='formElements[childType]' value="AD" />
        <input type="hidden" id="editID" name='formElements[elemId]' value="{$item.advertise_id}" />
        <input type="hidden" id="adName" name='formElements[adName]' value="{$item.name}" />
        <input type='hidden' id='temps' name='temps' value=''>
        <input type="hidden" name='formElements[parentCountry]' id="parentcountry" value="{$item.country_id}" />
        <input type="hidden" name='formElements[parentRegion]' id="parentregion"  value="{$item.region_id}" />
        <input type="hidden" name='formElements[parentCity]' id="parentcity" value="{$item.city_id}" />
        <input type="hidden" name='formElements[parentArea]' id="parentarea" value="{$item.area_id}" />
        <input type="hidden" name='formElements[parentshop]' id="parentshop"   value="{if $shop_id_fromlist neq  ''}{$shop_id_fromlist}{else}{$item.shop_id}{/if}"/>
        <input type="hidden" name='formElements[shop_id]' id="shop_id" value="{$shop_id}" />
        <input type="hidden" id="shop_name" name='shop_name' value="{$shop_name}" />


        {securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADD}
        <fieldset>
            <legend id="mylegend">{gt text='Basic Settings'}</legend>
            <div> 
                <div class="z-formrow">
                    <label for="level">{gt text='Select Level'}</label>
                    <select  name='formElements[level]' id='level' onChange='shopAdLevel(this.value);setLevel(this.value);'>
                        <option value=''>choose level</option>
                        <option value='COUNTRY'  {if $item.level eq 'COUNTRY'} selected='selected' {/if} >{gt text='COUNTRY'}</option>
                        <option value='REGION'  {if $item.level eq 'REGION'} selected='selected' {/if} >{gt text='REGION'}</option>
                        <option value='CITY'  {if $item.level eq 'CITY'} selected='selected' {/if} >{gt text='CITY'}</option>
                        <option value='SHOP'  {if $item.level eq 'SHOP'} selected='selected' {/if} >{gt text='SHOP'}</option>
                        <option value='AREA'  {if $item.level eq 'AREA'} selected='selected' {/if} >{gt text='AREA'}</option>
                    </select>
                </div>

                <div id="countries" class="AutoFIll" style="height:45px; margin-top: 8px;display:{if $item.level  eq  'COUNTRY'} block {else} none {/if};">
                    <div style="width:28%; float: left;">
                        <label for="country-combo">{gt text='Select Level value'}</label></div>
                    <div id="ajax-container" style="float:left; padding-left: 10px">
                        <select onchange="setName(this.value)" id="country-combo" class="levelvalue" name="formElements[country-combo]" size="1">
                            <option value=''>{gt text='Select Country...'}</option>
                            {foreach from=$countries  item='country'}
                            <option value="{$country.country_id}.{$country.country_name|upper}"  {if $item.country_id eq $country.country_id} selected='selected' {/if}>{$country.country_name|upper}</option>
                            {/foreach}
                        </select>
                    </div>

                </div>
                <div id="regions" class="AutoFIll" style="height:45px; margin-top: 8px;display:{if $item.level  eq  'REGION'} block {else} none {/if};">
                    <div style="width:28%; float: left;">
                        <label for="region-combo">{gt text='Select Level value'}</label></div>
                    <div id="ajax-container" style="float:left; padding-left: 10px">
                        <select onchange="setName(this.value)" id="region-combo" name="formElements[region-combo]" size="1" class="levelvalue">
                            <option value=''>{gt text='Select Region...'}</option>
                            {foreach from=$regions  item='region'}
                            <option value="{$region.region_id}.{$region.region_name|upper}"  {if $item.region_id eq $region.region_id} selected='selected' {/if}>{$region.region_name|upper}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
                <div id="cats" class="AutoFIll" style="height:45px; margin-top: 8px;display:{if $item.level  eq  'CITY'} block {else} none {/if};">
                    <div style="width:28%; float: left;">
                        <label for="city-combo">{gt text='Select Level value'}</label></div>
                    <div id="ajax-container" style="float:left; padding-left: 10px">
                        <select onchange="setName(this.value)" id="city-combo" name="formElements[city-combo]" size="1" class="levelvalue">
                            <option value=''>{gt text='Select City...'}</option>
                            {foreach from=$cities  item='city'}
                            <option value="{$city.city_id}.{$city.city_name|upper}"  {if $item.city_id eq $city.city_id} selected='selected' {/if}>{$city.city_name|upper}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>   

                <div id="area"  class="AutoFIll" style="height:45px; margin-top: 8px;display:{if $item.level  eq  'AREA'} block {else} none {/if};">
                    <div style="width:28%; float: left;">
                        <label for="area-combo">{gt text='Select Level value'}</label></div>
                    <div id="ajax-container" style="float:left; padding-left: 10px">
                        <select onchange="setName(this.value)" id="area-combo" name="formElements[area-combo]" size="1" class="levelvalue">
                            <option value=''>{gt text='Select Area...'}</option>
                            {foreach from=$areas  item='area'}
                            <option value="{$area.area_id}.{$area.area_name|upper}"  {if $item.area_id eq $area.area_id} selected='selected' {/if}>{$area.area_name|upper}</option>
                            {/foreach}
                        </select>
                    </div>

                </div>        

                <div id="adprices"  class="z-formrow">
                    <label for="parentadprice">{gt text='Placement'}</label>
                    <select name='formElements[adprice_id]' id='parentadprice'>
                        <option value='0'>{gt text='Select'}</option>
                        {foreach item='citem' from=$zadprice}
                        <option value="{$citem.adprice_id}" {if $item.adprice_id eq $citem.adprice_id} selected='selected' {/if}  > {$citem.name} </option>
                        {/foreach}
                    </select>
                </div>


                <div class="z-formrow">
                    <label for="elemtName">{gt text='Name'}</label>
                    <input type='text'  name='formElements[elemtName]' id='elemtName' value='{$item.name}'   />

                </div>
            </div>

        </fieldset>    



        <fieldset>
            <legend id="mylegend">{gt text='User Settings'}</legend>
            <div style="display:none">
                <div class="z-formrow">
                    <label for="elemtDesc">{gt text='Description'}</label>
                    <textarea  name='formElements[elemtDesc]' id='elemtDesc' >{$item.description}</textarea>
                </div>


                <div class="z-formrow">
                    <label for="keywords">{gt text='Keywords'}</label>
                    <textarea  name='formElements[keywords]' id='elemtDesc' >{$item.keywords}</textarea>
                </div>
            </div>
        </fieldset>


        <fieldset>
            <legend id="mylegend">{gt text='Advance Settings'}</legend>
            <div style="display:none">
                <div class="z-formrow">
                    <label for="advertise_type">{gt text='Select AD Type'}</label>
                    <select name='formElements[advertise_type]' id='advertise_type' >
                        {*<option value=''>select type</option>*}
                        <option value='productAd'  {if $item.advertise_type eq 'productAd'} selected='selected' {/if} >{gt text='Product AD'}</option>
                        {*<option value='shopAd'  {if $item.advertise_type eq 'shopAd'} selected='selected' {/if} >{gt text='Shop AD'}</option>*}

                    </select>
                </div>


                <div class="z-formrow">
                    <label for="maxviews">{gt text='Max Views'}</label>
                    <input type='text'  name='formElements[maxviews]' id='maxviews' value='{if $item.maxviews eq ''}-1{else}{$item.maxviews}{/if}'   />

                </div>

                <div class="z-formrow">
                    <label for="maxclicks">{gt text='Max Clicks'}</label>
                    <input type='text'  name='formElements[maxclicks]' id='maxclicks' value='{if $item.maxclicks eq ''}-1{else}{$item.maxclicks}{/if}'   />

                </div>


                <div class="z-formrow">
                    <label for="startdate">{gt text='Start Date'}</label>
                    <input type='text' autocomplete="off"   name='formElements[startdate]' id='startdate' class='startdate' value='{if $item.startdate eq '' OR $item.startdate eq  '0000-00-00'}0{else}{$item.startdate}{/if}' />

                </div>


                <div class="z-formrow">
                    <label for="enddate">{gt text='End Date'}</label>
                    <input type='text' autocomplete="off"  name='formElements[enddate]' id='enddate'  class='enddate' value='{if $item.enddate eq '' OR $item.enddate eq '0000-00-00'}0{else}{$item.enddate}{/if}' />

                </div>
            </div>
        </fieldset>


        <fieldset>

            <div class="z-formrow">
                <label for="typestatus">{gt text='Status'}</label>
                <select id="typestatus" name="formElements[status]" />
                <option value="1" {if $item.status eq '1'} selected='selected' {/if}>{gt text='Active'}</option>
                <option value="0" {if $item.status eq '0'} selected='selected' {/if}>{gt text='InActive'}</option>
                </select>
            </div>
        </fieldset>
        {/securityutil_checkpermission_block}
        <div class="z-buttons z-formbuttons">
            <button id="zselex_button_submit"  class="z-btgreen" type="submit" onclick="return validate_advertise();" name="action" value="1" title="{gt text='Save this region'}">
                {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save this Region'}
                {gt text='Save'}
            </button>
            <a id="zselex_button_cancel" href="{modurl modname="ZSELEX" type="admin" func='viewadvertise' shop_id=$shop_id}" class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
        </div>


    </div>
</form>

{adminfooter}


<script src="modules/ZSELEX/javascript/jquerycalendar/jquery-ui.js"></script>
<script>
    jQuery(function() {
        jQuery( "#startdate" ).datepicker({ dateFormat: "yy-mm-dd" });
        jQuery( "#enddate" ).datepicker({ dateFormat: "yy-mm-dd" });
  
    });
</script>

<script type="text/javascript">
    var panel = new Zikula.UI.Panels('panel', {
        headerSelector: '#mylegend',
        headerClassName: 'z-panel-indicator',
        active: [0]
      
    });
    
    
  
</script>

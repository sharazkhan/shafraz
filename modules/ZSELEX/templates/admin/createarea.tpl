
{adminheader}

{pageaddvar name='javascript' value='jquery'}

<link href="modules/ZSELEX/style/combo/sexy-combo.css" rel="stylesheet" type="text/css" />
<link href="modules/ZSELEX/style/combo/sexy/sexy.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="modules/ZSELEX/javascript/combo/jquery.sexy-combo.js"></script>

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
                
                 jQuery("#category-combo").ZselexCombo({
                  emptyText: Zikula.__("Select Category...")
                  //autoFill: true
                  //triggerSelected: true
                });

            }); 
            
        
        </script>



    <div class="z-admin-content-pagetitle">
     {if $item.category_id neq ''}
        <h3>{gt text='Update Category'}</h3>
        {else}
    	<h3>{gt text='Create Area'}</h3>
        {/if}

    </div>
    
    <form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="submitarea"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
            <input type="hidden" name='formElements[childType]' value="AREA" />
            <input type="hidden" name='formElements[elemId]' value="{$item.area_id}" />
            
            <input type="hidden" name='formElements[parentcategory]' id="parentcategory" value="{$item.parentcategory_id}" />
            <fieldset>
            <div class="z-formrow">
                <label for="elemtName">{gt text='Name'}</label>
                <input type='text'  name='formElements[elemtName]' id='elemtName' value='{$item.area_name}'   />
            </div>
            
            
            <div class="AutoFIll" style="height:45px; margin-top: 8px ">
                <div style="width:28%; float: left;"><label for="country-combo">{gt text='Countries'}</label></div>
                <div id="ajax-container" style="float:left; padding-left: 10px">
                    <select id="country-combo" name="formElements[country-combo]" size="1">
                    <option value=''>{gt text='Select Country...'}</option>
                        {foreach from=$countries  item='country'}
                        <option value="{$country.country_id}"  {if $item.country_id eq $country.country_id} selected='selected' {/if}>{$country.country_name|upper}</option>
                        {/foreach}
                    </select>
                </div>
                
            </div>
           
         
            
        
            <div class="AutoFIll" style="height:45px; margin-top: 8px ">
                <div style="width:28%; float: left;"><label for="region-combo">{gt text='Regions'}</label></div>
                <div id="ajax-container" style="float:left; padding-left: 10px">
                    <select id="region-combo" name="formElements[region-combo]" size="1">
                    <option value=''>{gt text='Select Region...'}</option>
                        {foreach from=$regions  item='region'}
                        <option value="{$region.region_id}"  {if $item.region_id eq $region.region_id} selected='selected' {/if}>{$region.region_name|upper}</option>
                        {/foreach}
                    </select>
                </div>
                
            </div>
        
            
              <div class="AutoFIll" style="height:45px; margin-top: 8px ">
                 <div style="width:28%; float: left;"><label for="city-combo">{gt text='City'}</label></div>
                <div id="ajax-container" style="float:left; padding-left: 10px">
                    <select id="city-combo" name="formElements[city-combo]" size="1">
                    <option value=''>{gt text='Select City...'}</option>
                        {foreach from=$cities  item='city'}
                        <option value="{$city.city_id}"  {if $item.city_id eq $city.city_id} selected='selected' {/if}>{$city.city_name|upper}</option>
                        {/foreach}
                    </select>
                </div>
               
            </div>
                    
               <div class="z-formrow">
                <label for="typestatus">{gt text='Status'}</label>
                <select id="typestatus" name="formElements[status]" />
                      <option value="1" {if $item.status eq '1'} selected='selected' {/if}>{gt text='Active'}</option>
                      <option value="0" {if $item.status eq '0'} selected='selected' {/if}>{gt text='InActive'}</option>
                </select>
               </div>
            
          
            <div class="z-buttons z-formbuttons">
             <button id="zselex_button_submit"  class="z-btgreen" type="submit" onclick="return validate_category();" name="action" value="1" title="{gt text='Save this region'}">
            {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save this Region' }
             {gt text='Save'}
            </button>
            <a id="zselex_button_cancel" href="{modurl modname="ZSELEX" type="admin" func='viewarea'}" class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
            </div>
        </div>
	</form>
    <div>

</div>

{adminfooter}

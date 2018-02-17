{adminheader}
<div class="z-admin-content-pagetitle">

        <h3>{gt text='Denmark Regions'}</h3>
      
</div>

<form class="z-form" id="mapform" action="" method="post" enctype="application/x-www-form-urlencoded">
    <div>
        
    <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
    <fieldset>
        <legend>{gt text="Set ID'S"}</legend>
        {*<div class="z-formrow">
            <label for="type_name">{gt text='Denmark'}</label>
            <select id="map_denmark_id" name="map_denmark_id" onchange="document.forms['mapform'].submit();">
                    <option value=''>{gt text='Select Country...'}</option>
                        {foreach from=$countries  item='country'}
                        <option value="{$country.country_id}"  {if $country_id eq $country.country_id} selected='selected' {/if}>{$country.country_name|upper}</option>
                        {/foreach}
             </select>
        </div>*}
             
        <div class="z-formrow">
            <label for="type_name">{gt text='NORDJYLLAND'} ({gt text='region1'})</label>
            <select id="NORDJYLLAND_ID" name="da_region1">
                    <option value=''>{gt text='Select Region...'}</option>
                        {foreach from=$regions  item='region'}
                        <option value="{$region.region_id}"  {if $da_region1 eq $region.region_id} selected='selected' {/if}>{$region.region_name|upper}</option>
                        {/foreach}
             </select>
        </div>
              <div class="z-formrow">
            <label for="type_name">{gt text='MIDTJYLLAND'} ({gt text='region2'})</label>
            <select id="MIDTJYLLAND_ID" name="da_region2">
                    <option value=''>{gt text='Select Region...'}</option>
                        {foreach from=$regions  item='region'}
                        <option value="{$region.region_id}"  {if $da_region2 eq $region.region_id} selected='selected' {/if}>{$region.region_name|upper}</option>
                        {/foreach}
             </select>
        </div>
              <div class="z-formrow">
            <label for="type_name">{gt text='SYDJYLLAND'} ({gt text='region3'})</label>
            <select id="SYDJYLLAND_ID" name="da_region3">
                    <option value=''>{gt text='Select Region...'}</option>
                        {foreach from=$regions  item='region'}
                        <option value="{$region.region_id}"  {if $da_region3 eq $region.region_id} selected='selected' {/if}>{$region.region_name|upper}</option>
                        {/foreach}
             </select>
        </div>
              <div class="z-formrow">
            <label for="type_name">{gt text='FYN'} ({gt text='region4'})</label>
            <select id="FYN_ID" name="da_region4">
                    <option value=''>{gt text='Select Region...'}</option>
                        {foreach from=$regions  item='region'}
                        <option value="{$region.region_id}"  {if $da_region4 eq $region.region_id} selected='selected' {/if}>{$region.region_name|upper}</option>
                        {/foreach}
             </select>
        </div>
              <div class="z-formrow">
            <label for="type_name">{gt text='SJÆLLAND'} ({gt text='region5'})</label>
            <select id="SJÆLLAND_ID" name="da_region5">
                    <option value=''>{gt text='Select Region...'}</option>
                        {foreach from=$regions  item='region'}
                        <option value="{$region.region_id}"   {if $da_region5 eq $region.region_id} selected='selected' {/if}>{$region.region_name|upper}</option>
                        {/foreach}
             </select>
        </div>
              <div class="z-formrow">
            <label for="type_name">{gt text='HOVED-STADS-OMRÅDET'} ({gt text='region6'})</label>
            <select id="HOVED-STADS-OMRÅDET_ID" name="da_region6">
                    <option value=''>{gt text='Select Region...'}</option>
                        {foreach from=$regions  item='region'}
                        <option value="{$region.region_id}"  {if $da_region6 eq $region.region_id} selected='selected' {/if}>{$region.region_name|upper}</option>
                        {/foreach}
             </select>
        </div>
              <div class="z-formrow">
            <label for="type_name">{gt text='BORNHOLM'} ({gt text='region7'})</label>
            <select id="BORNHOLM_ID" name="da_region7">
                    <option value=''>{gt text='Select Region...'}</option>
                        {foreach from=$regions  item='region'}
                        <option value="{$region.region_id}" {if $da_region7 eq $region.region_id} selected='selected' {/if}>{$region.region_name|upper}</option>
                        {/foreach}
             </select>
        </div>
      
    </fieldset>
             
    <div class="z-buttons z-formbuttons">
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save" __name="typetable[action]" __value="save"}
        <a href="{modurl modname="ZSELEX" type="admin" func='modifyconfig'}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    </div>
</form>
{adminfooter}
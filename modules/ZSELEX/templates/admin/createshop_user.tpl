
{ajaxheader imageviewer="true"}
{adminheader}
{ajaxheader modname='ZSELEX' filename='zselex_admin.js' ui=true}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/zselex_admin_validation.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/test1.js'}

<style>

    .basket_content {
        background-color: white;
        border: 16px solid black;
        left: 25%;
        min-height: 100px;
        overflow: auto;
        position: fixed;
        top: 30%;
        width: 750px;
        z-index: 10002;
    }
    .backshield {
        background-color: #333333;
        height: 200%;
        left: 0;
        opacity: 0.8;
        position: absolute;
        top: 0;
        width: 100%;
        z-index: 1000;
    }

</style>

<link rel="stylesheet" type="text/css" href="modules/ZSELEX/style/autosuggest_inquisitor.css">


<div class="z-admin-content-pagetitle">
    {if $item.shop_id neq ''}
    <h3>{gt text='Update Shop'}</h3>
    {else}
    <h3>{gt text='Create Shop'}</h3>
    {/if}
</div>
 
<form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="submitshopuser"}" method="post" enctype="multipart/form-data">
      <div>
        <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
               <input type="hidden" name='formElements[childType]' value="SHOP" />
        <input type="hidden" name='formElements[elemId]' value="{$item.shop_id}" />
        <input type='hidden' id='temps' name='temps' value=''>
        <input type="hidden" name='formElements[parentCountry]' id="parentcountry" value="{$item.parentcountry_id}" />
        <input type="hidden" name='formElements[parentRegion]' id="parentregion"  value="{$item.parentregion_id}" />
        <input type="hidden" name='formElements[parentCity]' id="parentcity" value="{$item.parentcity_id}" />
        <input type="hidden" name='formElements[parentplugin]' id="parentplugin" value="{$item.parentplugin_id}" />
        <input type="hidden" name='formElements[parentcategory]' id="parentcategory" value="{$item.parentcategory_id}" />
        <input type="hidden" name='formElements[parentshop]' id="parentshop" value="{$item.parentshop_id}" />
        <input type="hidden" name='formElements[parentuser]' id="parentuser" value="{$item.parentuser_id}" />

        <input type="hidden" name='formElements[hiddenpicture]' id="parentuser" value="{$item.pictures}" />

        <fieldset>
            <div class="z-formrow">
                <label for="elemtName">{gt text='Name'}</label>
                <input type='text'  name='formElements[elemtName]' id='elemtName' value='{$item.shop_name}'   />
            </div>

            <div class="z-formrow">
                <label for="elemtDesc">{gt text='Description'}</label>
                <textarea  name='formElements[elemtDesc]' id='elemtDesc' >{$item.description}</textarea>
            </div>


            {securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADMIN}
            <div id="users"  class="z-formrow">
                <label for="parentuser">{gt text='Shop Owner'}</label>
                <div>
                    <input name="formElements[parentuser_list]" type="text" id="parentuser_list" value="{$item.parentuser}" size="30" maxlength="1000" onfocus="autoSuggestCountry(this.id, 'listWrap3', 'searchList3', 'parentuser_list', event);"  onkeyup="autoSuggestCountry(this.id, 'listWrap3', 'searchList3', 'parentuser_list', event);" onkeydown="keyBoardNav(event, this.id);" onblur="outlist('listWrap3')" /> 
                </div>
                <div class="listWrap" id="listWrap3">
                    <ul class="searchList" id="searchList3">
                    </ul>
                </div>
            </div>
            {/securityutil_checkpermission_block}  
            {securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADD}    
            <div class="z-formrow">
                <label for="shopadmins">{gt text='Shop Admins'}</label>
                <div>
                    <select name='formElements[shopadmins][]' id='shopadmins' multiple="multiple">

                        {foreach from=$users  item='user'}

                        {if $loguser eq $user.uid}
                        {php}
                        continue;
                        {/php}
                        {/if} 


                        {*{foreach from=$shopuser  item='shopusers'} {if $shopusers.parentId eq $user.uid} selected=selected  {/if}{/foreach}*}
                        <option value="{$user.uid}" {if in_array($user.uid , $puser)}selected=selected{/if}> {$user.uname} </option>
                        {/foreach}
                    </select>
                </div>
            </div>
            {/securityutil_checkpermission_block}  
            {securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADMIN}
            <div id="countries"  class="z-formrow">
                <label for="parentcountry">{gt text='Country'}</label>
                <div>
                    <input name="formElements[parentcountry_list]" type="text" id="parentcountry_list" value="{$item.parentcountry}" size="30" maxlength="1000" onfocus="autoSuggestCountry(this.id, 'listWrap3', 'searchList3', 'parentcountry_list', event);"  onkeyup="autoSuggestCountry(this.id, 'listWrap3', 'searchList3', 'parentcountry_list', event);" onkeydown="keyBoardNav(event, this.id);" onblur="outlist('listWrap3')" /> 
                </div>
                <div class="listWrap" id="listWrap3">
                    <ul class="searchList" id="searchList3">
                    </ul>
                </div>
            </div>
            {/securityutil_checkpermission_block} 
            {securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADMIN}
            <div id="regions"  class="z-formrow">
                <label for="parentRegion">{gt text='Region'}</label>
                <div  id='displayregion'>
                    <input name="formElements[parentregion_list]" type="text" id="parentregion_list"  value="{$item.parentregion}" size="30" maxlength="1000" onfocus="autoSuggestRegion(this.id, 'listWrap1', 'searchList1', 'parentregion_list', event);"  onkeyup="autoSuggestRegion(this.id, 'listWrap1', 'searchList1', 'parentregion_list', event);" onkeydown="keyBoardNav(event, this.id);" onblur="outlist('listWrap1')" /> 
                </div>
                <div class="listWrap" id="listWrap1">
                    <ul class="searchList" id="searchList1">
                    </ul>
                </div>
            </div>


            <div  id="cats"   class="z-formrow">
                <label for="parentCity">{gt text='City'}</label>
                <div>
                    <input name="formElements[parentcity_list]" type="text" id="parentcity_list" value="{$item.parentcity}" size="30" maxlength="1000" onfocus="autoSuggestCity(this.id, 'listWrap2', 'searchList2', 'parentcity_list', event);"  onkeyup="autoSuggestCity(this.id, 'listWrap2', 'searchList2', 'parentcity_list', event);" onkeydown="keyBoardNav(event, this.id);" onblur="outlist('listWrap2')" /> 
                </div>
                <div class="listWrap" id="listWrap2">
                    <ul class="searchList" id="searchList2">
                    </ul>
                </div>
            </div>

            <div id="shops"  class="z-formrow">
                <label for="parentShop">{gt text='parent Shop'}</label>
                <div>
                    <input name="formElements[parentshop_list]" type="text" id="parentshop_list" value="{$item.parentshop}" size="30" maxlength="1000" onfocus="autoSuggestShop(this.id, 'listWrap7', 'searchList7', 'parentshop_list', event);"  onkeyup="autoSuggestShop(this.id, 'listWrap7', 'searchList7', 'parentshop_list', event);" onkeydown="keyBoardNav(event, this.id);" onblur="outlist('listWrap7')" /> 
                </div>
                <div class="listWrap" id="listWrap7">
                    <ul class="searchList" id="searchList7">
                    </ul>
                </div>
            </div>

            <div id="shopdetails">
                <div class="z-formrow">
                    <label for="elemtAddrs">{gt text='Address'}</label>
                    <textarea  name='formElements[elemtAddrs]' id='elemtAddrs' >{$item.address}</textarea>
                </div>

                <div class="z-formrow">
                    <label for="elemtTele">{gt text='Telephone'}</label>
                    <input type='text'  name='formElements[elemtTele]' id='elemtTele' value='{$item.telephone}'   />
                </div>

                <div class="z-formrow">
                    <label for="elemtFax">{gt text='Fax'}</label>
                    <input type='text'  name='formElements[elemtFax]' id='elemtFax' value='{$item.fax}'   />
                </div>

                <div class="z-formrow">
                    <label for="elemtEmail">{gt text='Email'}</label>
                    <input type='text'  name='formElements[elemtEmail]' id='elemtEmail' value='{$item.email}'   />
                </div>

            </div>

            <div  id="branches"   class="z-formrow">
                <label for="branch">{gt text='Branch'}</label>
                <select name='formElements[branch]' id='branch'>
                    <option value='0'>select</option>
                    {foreach from=$zbranch  item='zbranchitem'}
                    <option value="{$zbranchitem.branch_id}" > {$zbranchitem.branch_name} </option>
                    {/foreach}
                </select>
            </div>
            <div  id="ecom"  >
                <div class="z-formrow">
                    <label for="ecommerce">{gt text='Select shop type'}</label>
                    <select name='formElements[ecommerce]' id='ecommerce' onChange='shoptype(this.value)'>
                        <option value='0'>{gt text='Select'}</option>
                        {foreach item='zecitem' from=$zecommerce}
                        <option value="{$zecitem.shoptype_id}" {if $zecitem.shoptype_id eq $item.shoptype_id} selected="selected" {/if}> {$zecitem.shopTypeName} </option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div id="zshop" {if $item.shoptype_id eq 1} style="display:block;" {else} style="display:none;" {/if} >
                 <div class="z-formrow">
                    <label for="ecomDomain">{gt text='Domain'}</label>
                    <input type='text'  name='formElements[ecomDomain]' id='ecomDomain' value='{$item.domain}'   />	
                </div>

                <div class="z-formrow">
                    <label for="ecomHost">{gt text='Host'}</label>
                    <input type='text'  name='formElements[ecomHost]' id='ecomHost' value='{$item.hostname}'   />	
                </div>

                <div class="z-formrow">
                    <label for="ecomDb">{gt text='Database'}</label>
                    <input type='text'  name='formElements[ecomDb]' id='ecomDb' value='{$item.dbname}'   />	
                </div>

                <div class="z-formrow">
                    <label for="ecomUser">{gt text='User Name'}</label>
                    <input type='text'  name='formElements[ecomUser]' id='ecomUser' value='{$item.username}'   />	
                </div>

                <div class="z-formrow">
                    <label for="ecomPswrd">{gt text='Password'}</label>
                    <input type='text'  name='formElements[ecomPswrd]' id='ecomPswrd' value='{$item.password}'   />	
                </div>
                <div class="z-formrow">
                    <label for="table_prefix">{gt text='Table Prefix'}</label>
                    <input type='text'  name='formElements[table_prefix]' id='table_prefix' value='{$item.table_prefix}'   />	
                </div>
            </div>

            <div class="z-formrow">
                <label for="parentCategory">{gt text='Category'}</label>
                <div>
                    <input name="formElements[parentcategory_list]" type="text" id="parentcategory_list" value="{$item.parentcategory}" size="30" maxlength="1000" onfocus="autoSuggestCategory(this.id, 'listWrap5', 'searchList5', 'parentcategory_list', event);"  onkeyup="autoSuggestCategory(this.id, 'listWrap5', 'searchList5', 'parentcategory_list', event);" onkeydown="keyBoardNav(event, this.id);" onblur="outlist('listWrap5')" /> 
                </div>
                <div class="listWrap" id="listWrap5">
                    <ul class="searchList" id="searchList5">
                    </ul>
                </div>
            </div>
            <div  id="pluginsDis"   class="z-formrow">
                <label for="parentAd">{gt text='Plugin'}</label>

                <div>
                    <input name="formElements[parentplugin_list]" type="text" id="parentplugin_list" value="{$item.parentplugin}" size="30" maxlength="1000" onfocus="autoSuggestPlugin(this.id, 'listWrap6', 'searchList6', 'parentplugin_list', event);"  onkeyup="autoSuggestPlugin(this.id, 'listWrap6', 'searchList6', 'parentplugin_list', event);" onkeydown="keyBoardNav(event, this.id);" onblur="outlist('listWrap6')" /> 
                </div>
                <div class="listWrap" id="listWrap6">
                    <ul class="searchList" id="searchList6">
                    </ul>
                </div>
            </div>
            <div class="z-formrow">
                <label for="typestatus">{gt text='Status'}</label>
                <select id="typestatus" name="formElements[status]" />
                <option value="1" {if $item.status eq '1'} selected='selected' {/if}>{gt text='Active'}</option>
                <option value="0" {if $item.status eq '0'} selected='selected' {/if}>{gt text='InActive'}</option>
                </select>
            </div>
            {/securityutil_checkpermission_block} 
  
            {section name=foo loop=$imageQuantity} 
            <div class="z-formrow" id="pic">
                <label for="shopimage">{gt text='Image'}{$smarty.section.foo.iteration}</label>
                <input type="file" name="files[]" id="files" multiple>
            </div>
  
            {/section}
            {foreach item='item' from=$shpImgs}
            <div class="z-formrow">
                <label><a href='#' onclick=window.open('index.php?module=zselex&type=admin&func=changeimage&id={$item.file_id}&shpId={$shop}','new','width=400,height=200,scrollbars=yes');>{gt text='Change'}</a></label>
                <div>
                    <a id="{$item.file_id}" rel="imageviewer[galleryShop]" title="{$item.name}" href="{$baseurl}zselexdata/shops/{$item.name}">
                    <img src="{$baseurl}zselexdata/shops/thumbs/{$item.name}">
                    </a>
                    <input type="radio" name="formElements[defaultimage]" id="image{$item.file_id}" {if $item.defaultImg eq 1} checked="checked" {/if} value="{$item.file_id}" />  <label for="image{$item.file_id}">{gt text='Set default'}</label>
                </div>

            </div>
            {/foreach}
            <div class="z-formrow">
                <label for="plugin">{gt text='Selected Services'}</label>
                {if $shop neq ''}
                {if $serviceCount neq '0'}
                <table width="30%">
                    <tr>
                        <td><b>{gt text='Services'}</b></td>
                        <td><b>{gt text='Quantity'}</b></td>
                        <td><b>{gt text='Availed'}</b></td>
                    </tr>
                    {foreach  item='item' from=$servicesPurchased}
                    <tr>
                        <td>
                            {$item.type}  
                        </td>
                        <td>{$item.quantity}</td>
                        <td>{$item.availed}</td>
                    </tr>
                    {/foreach}
                </table>
                {else}
                <span> {gt text='No Services for this shop'}</span>
                {/if}
                {/if}
            </div>

            <br>
            {securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADD}    
            {if $shop neq ''}
            <div class="z-formrow">
                <label for="plugin">{gt text='Select Services'}</label>
                <table width="30%">
                    <tr>
                        <td><b>{gt text='Services'}</b></td>
                        <td><b>{gt text='Quantity'}</b></td>
                        <td><b>{gt text='Price'}</b></td>
                        <td></td>
                    </tr>

                    {foreach  item='item' from=$plugin}
                        {if $item.type neq 'theme'}
                    <tr>
                        <td>
                            {$item.plugin_name} 
                        </td>
                        <td><input type="text" value="1" id="number" size="2" {if $item.qty_based eq 0} disabled="disabled"
 {/if} onkeyup="addQuantity({$item.plugin_id} , this.value);getServiceCount(this.value , {$item.price} , {$item.plugin_id});"></td>
                        <td id="price{$item.plugin_id}">{$item.price}</td>
                        <td><img class='toolmsg'  title="Add to basket" border='0'  src='{$baseurl}zselexdata/basket.jpg' style="cursor:pointer" onclick="addToBasket({$item.plugin_id} , '{$item.type}' , {$item.price} , document.getElementById('qty'+{$item.plugin_id}).value , document.getElementById('hshop_id').value)" /></td>
                    </tr>
                  {/if}
                    {/foreach}
                </table>
            </div>

            {/if}
            {/securityutil_checkpermission_block}

            <div class="z-formrow">
                <label for="plugin">{gt text='Select Design'}</label>
                <table width="30%">
                    <tr>
                        <td><b>{gt text='Theme'}</b></td>
                        <td><b>{gt text='Price'}</b></td>

                        <td><b>{gt text='Preview'}</b></td>
                        <td></td>
                    </tr>

                    {foreach  item='design' from=$designs}
                    {assign var="images" value="themes/`$design.plugin_name`/images/preview_large.png"}
                    <tr>
                        <td>
                            {$design.plugin_name}    
                        </td>

                        <td id="price{$design.price}">{$design.price}</td>
                        <td> {if file_exists($images)}
                            <a id="{$design.plugin_id}" rel="imageviewer[galleryDesign]" href="{$baseurl}themes/{$design.plugin_name}/images/preview_large.png"  title="{$design.plugin_name|safetext}">{gt text='Preview'}</a>
                            {else}
                            {gt text='not available'}
                            {/if}

                        </td>
                        <td><input type="radio" {if $shopDesign eq $design.plugin_name} checked="checked" {/if} name="formElements[shopdesign]" id="{$design.plugin_name}"  value="{$design.plugin_name}" /></td>
                    </tr>

                    {/foreach}
                     
                </table>
                          
            </div>
        </fieldset>
        <div class="z-buttons z-formbuttons">
            <button id="zselex_button_submit"  class="z-btgreen" type="submit" onclick="return validate_shop();" name="action" value="1" title="{gt text='Save this region'}">
                {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save this Region' }
                {gt text='Save'}
            </button>
            <a id="zselex_button_cancel" href="{modurl modname="ZSELEX" type="admin" func='viewshop'}" class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
        </div>
    </div>
    <input type="hidden" name="hshop_id" id="hshop_id" value="{$shop}"/>
    {foreach  item='item' from=$plugin}
    <input type="hidden" name="qty{$item.plugin_id}" id="qty{$item.plugin_id}" value="1"/>
    {/foreach}
    <input type="hidden" name="totalcounts" id="totalcounts" value="0"/>
    <input type="hidden" name="totalcount" id="totalcount" value="0"/>

</form>


<div id="light" class="basket_content" style="display:none"></div>
<div id="backshield" class="backshield" style="height: 2157px;display:none" onClick='closeWindow();'></div>



<script type="text/javascript">
    // var defaultTooltip = new Zikula.UI.Tooltip($('toolmsg'));

    Zikula.UI.Tooltips($$('.toolmsg'));
</script>

<script type="text/javascript">
    var shopuser_options = {
        script:document.location.pnbaseURL+"/index.php?module=ZSELEX&type=ajax&func=admin_shopuser_autocomplete&",
        varname:"input",
        json:true,
        callback: function (obj) { 
            document.getElementById('parentuser').value = obj.id;
        }
    };
    var as_json = new AutoSuggest('parentuser_list', shopuser_options);



    var country_options = {
        script:document.location.pnbaseURL+"/index.php?module=ZSELEX&type=ajax&func=admin_country_autocomplete&",
        varname:"input",
        json:true,
        callback: function (obj) { 
            document.getElementById('parentcountry').value = obj.id;
        }
    };
    var as_json = new AutoSuggest('parentcountry_list', country_options);

    var region_options = {
        script:document.location.pnbaseURL+"/index.php?module=ZSELEX&type=ajax&func=admin_region_autocomplete&",
        varname:"input",
        json:true,
        callback: function (obj) { 
            document.getElementById('parentregion').value = obj.id;
        }
    };
    var region_as_json = new AutoSuggest('parentregion_list', region_options);

    var city_options = {
        script:document.location.pnbaseURL+"/index.php?module=ZSELEX&type=ajax&func=admin_city_autocomplete&",
        varname:"input",
        json:true,
        callback: function (obj) { 
            document.getElementById('parentcity').value = obj.id;
        }
    };
    var city_as_json = new AutoSuggest('parentcity_list', city_options);

    var shop_options = {
        script:document.location.pnbaseURL+"/index.php?module=ZSELEX&type=ajax&func=admin_shop_autocomplete&",
        varname:"input",
        json:true,
        callback: function (obj) { 
            document.getElementById('parentshop').value = obj.id;
        }
    };
    var shop_as_json = new AutoSuggest('parentshop_list', shop_options);

    var advertise_options = {
        script:document.location.pnbaseURL+"/index.php?module=ZSELEX&type=ajax&func=admin_ad_autocomplete&",
        varname:"input",
        json:true,
        callback: function (obj) { 
            document.getElementById('parentad').value = obj.id;
        }
    };
    var advertise_as_json = new AutoSuggest('parentad_list', advertise_options);

    var plugin_options = {
        script:document.location.pnbaseURL+"/index.php?module=ZSELEX&type=ajax&func=admin_plugin_autocomplete&",
        varname:"input",
        json:true,
        callback: function (obj) { 
            document.getElementById('parentplugin').value = obj.id;
        }
    };
    var plugin_as_json = new AutoSuggest('parentplugin_list', plugin_options);

    var category_options = {
        script:document.location.pnbaseURL+"/index.php?module=ZSELEX&type=ajax&func=admin_category_autocomplete&",
        varname:"input",
        json:true,
        callback: function (obj) { 
            document.getElementById('parentcategory').value = obj.id;
        }
    };
    var category_as_json = new AutoSuggest('parentcategory_list', category_options);


</script>

{adminfooter}
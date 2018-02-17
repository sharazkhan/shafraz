{*{ajaxheader modname='ZSELEX' filename='zselex_admin.js' ui=true}*}

{adminheader}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/zselex_admin.js'}
{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/jquery.jscroll.min.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/jquery.jscroll.js'}
<link href="modules/ZSELEX/style/combo/sexy-combo.css" rel="stylesheet" type="text/css" />
<link href="modules/ZSELEX/style/combo/sexy/sexy.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="modules/ZSELEX/javascript/combo/jquery.sexy-combo.js"></script>

             <script type="text/javascript" >
                     jQuery(function () {
                     jQuery("#country").ZselexCombo({
                        emptyText: Zikula.__("Select Country...")
                        //autoFill: true
                        //triggerSelected: true
                        });
                        
                     jQuery("#region").ZselexCombo({
                        emptyText: Zikula.__("Select Region...")
                        //autoFill: true
                        //triggerSelected: true
                        });
                        
                     jQuery("#city").ZselexCombo({
                        emptyText: Zikula.__("Select City...")
                        //autoFill: true
                        //triggerSelected: true
                        });
                        
                     jQuery("#area").ZselexCombo({
                        emptyText: Zikula.__("Select Area...")
                        //autoFill: true
                        //triggerSelected: true
                        });
                        
                      jQuery("#category").ZselexCombo({
                        emptyText: Zikula.__("Select Category...")
                        //autoFill: true
                        //triggerSelected: true
                        });
                        
                      jQuery("#branch").ZselexCombo({
                        emptyText: Zikula.__("Select Branch...")
                        //autoFill: true
                        //triggerSelected: true
                        });
                      
                      jQuery("#affiliate").ZselexCombo({
                        emptyText: Zikula.__("Select Affiliate...")
                        //autoFill: true
                        //triggerSelected: true
                        });
                        
                       jQuery("#bundle").ZselexCombo({
                        emptyText: Zikula.__("Select Bundle...")
                        //autoFill: true
                        //triggerSelected: true
                        });

                    }); 

                </script>

<style>
#ajax-container input[type="text"],#ajax-container textarea {
padding: 0.09em;
}

#ajax-container ul {
  margin:0px;
}
</style>
<link rel="stylesheet" type="text/css" href="{$stylepath}/selectionbox.css"/>
 {securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADMIN}
<div class="z-admin-content-pagetitle">
    <a href="index.php?module=zselex&amp;type=admin&amp;func=createshop" class="z-iconlink z-icon-es-add">{gt text="Create Shop"}</a>
</div>
 {/securityutil_checkpermission_block}
  <!--
          <div>
            <a  id="defwindowajax1" href="{modurl modname='ZSELEX' type='info' func='testPage'}" title="Information Link">
                <img  src="{$baseurl}images/icons/small/info.png"></a>
          </div>
  -->
       
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='New shops list'}</h3>
</div>

<form class="z-form" id="shop_filter" action="{modurl modname='ZSELEX' type='admin' func='viewshop'}" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset id="zselex_multicategory_filter"{if $filter_active} class='filteractive'{/if}>
        {if $filter_active}{gt text='active' assign=filteractive}{elseif $filter_active neq ''}{gt text='inactive' assign=filteractive}{else $filter_active eq ''}{gt text='All' assign=filteractive}{/if}
        <legend>{gt text='Filter %1$s, %2$s shop listed' plural='Filter %1$s, %2$s shops listed' count=$total_shops tag1=$filteractive tag2=$total_shops}</legend>
        <input type="hidden" name="startnum" value="{$startnum}" />
        <input type="hidden" name="order" value="{$order}" />
        <input type="hidden" name="sdir" value="{$sdir}" />
        
       <!-- <input type="hidden" name="sql" value="{$sql}" />-->
       <div style="float:left; padding-left: 10px">       
		<div style="float:left; padding-left: 10px">
        	<label for="searchtext">{gt text='Shop Name'}</label>
       		<div><input type="text" name="searchtext" value="{$searchtext}" /></div>
        </div>
        
        <div style="float:left; padding-left: 10px">
	        <label for="address">{gt text='Address'}</label>
    	    <div><textarea name="address">{$address}</textarea></div>
        </div>

	<div style="float:left; padding-left: 10px">
	        <label for="telephone">{gt text='Telephone'}</label>
    	    <div><input type="text" name="telephone" value="{$telephone}" /></div>
	</div>
        
	<div style="float:left; padding-left: 10px">
	        <label for="email">{gt text='Email'}</label>
    	    <div><input type="text" name="email" value="{$email}" /></div>
	</div>
       
        {if $admin}
		<div style="float:left; padding-left: 10px">
	        <label for="email">{gt text='Owner'}</label>
    	    <div><input type="text" name="owner" value="{$owner}" /></div>
		</div>
        {/if}
        
		<div style="float:right; padding-left: 100px">
        <span class="z-nowrap z-buttons">
        
            <input class='z-bt-filter' name="submit" type="submit" value="{gt text='Filter'}" />
            <a href="{modurl modname="ZSELEX" type='admin' func='viewshop' clear=1}" title="{gt text="Clear"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Clear" __title="Clear"} {gt text="Clear"}</a>
          
        </span>
		</div>

</div>
<div style="float:left; padding-left: 10px">        
           <div id="ajax-container" style="float:left; padding-left: 10px">
             <label for="country">{gt text='Country'}</label>
              <select id="country" name="country" size="1">
              <option value=''>{gt text='Select Country...'}</option>
                  {foreach from=$countries  item='country'}
                  <option value="{$country.country_name}"  {if $countryname eq $country.country_name} selected='selected' {/if}>{$country.country_name|upper}</option>
                  {/foreach}
              </select>
          </div>
      
        <div id="ajax-container" class="regions" style="float:left; padding-left: 10px">
              <label for="region">{gt text='Region'}</label>
             <select id="region" name="region" size="1">
             <option value=''>{gt text='Select Region...'}</option>
                 {foreach from=$regions  item='region'}
                 <option value="{$region.region_name}"  {if $regionname eq $region.region_name} selected='selected' {/if}>{$region.region_name|upper}</option>
                 {/foreach}
             </select>
         </div>
         <div id="ajax-container" class="city"  style="float:left; padding-left: 10px">
              <label for="city">{gt text='City'}</label>
                    <select id="city" name="city" size="1">
                    <option value=''>{gt text='Select City...'}</option>
                        {foreach from=$cities  item='city'}
                        <option value="{$city.city_name}"  {if $city_name eq $city.city_name} selected='selected' {/if}>{$city.city_name|upper}</option>
                        {/foreach}
                    </select>
         </div>
        <div id="ajax-container" class="area" style="float:left; padding-left: 10px">
            <label for="area">{gt text='Area'}</label>
            <select id="area" name="area" size="1">
            <option value=''>{gt text='Select Area...'}</option>
               {foreach from=$areas  item='area'}
               <option value="{$area.area_name}"  {if $areaname eq $area.area_name} selected='selected' {/if}>{$area.area_name|upper}</option>
               {/foreach}
            </select>
        </div>
            
        <div id="ajax-container" style="float:left; padding-left: 10px">
             <label for="category">{gt text='Category'}</label>
                    <select id="category" name="category" size="1">
                    <option value=''>{gt text='Select Category...'}</option>
                        {foreach from=$categories  item='category'}
                        <option value="{$category.category_id}"  {if $category_name eq $category.category_id} selected='selected' {/if}>{$category.category_name|upper}</option>
                        {/foreach}
                    </select>
        </div>
                    
        <div id="ajax-container" style="float:left; padding-left: 10px">
             <label for="branch">{gt text='Branch'}</label>
                    <select id="branch" name="branch" size="1">
                    <option value=''>{gt text='Select Branch...'}</option>
                        {foreach from=$branches  item='branch'}
                        <option value="{$branch.branch_id}"  {if $branchname eq $branch.branch_id} selected='selected' {/if}>{$branch.branch_name|upper}</option>
                        {/foreach}
                    </select>
         </div>
         <div id="ajax-container" style="float:left; padding-left: 10px">
             <label for="affiliate">{gt text='Affiliate'}</label>
                    <select id="affiliate" name="affiliate" size="1">
                   <option value='0'>{gt text='Select Affiliate...'}</option>
                    {foreach from=$affiliates  item='affiliate'}
                     <option value="{$affiliate.aff_id}" {if $shop_affiliate eq $affiliate.aff_id} selected='selected' {/if}>{$affiliate.aff_name}</option>
                    {/foreach}
                    </select>
         </div>
        <div id="ajax-container" style="float:left; padding-left: 10px">
             <label for="bundle">{gt text='Bundles'}</label>
                    <select id="bundle" name="bundle" size="1">
                   <option value='0'>{gt text='Select Bundle...'}</option>
                    {foreach from=$bundles  item='bundle'}
                     <option value="{$bundle.bundle_id}" {if $shop_bundle eq $bundle.bundle_id} selected='selected' {/if}>{$bundle.bundle_name}</option>
                    {/foreach}
                    </select>
         </div>
       
       <div style="float:left; padding-left: 10px">
        <label for="itemsperpage">{gt text='Show'}</label>
        <div>
         <select id="itemsperpage" name="itemsperpage" size="1">
                   <option value='20' {if $itemsperpage eq '20'} selected='selected' {/if}>20</option>
                   <option value='50' {if $itemsperpage eq '50'} selected='selected' {/if}>50</option>
                   <option value='100' {if $itemsperpage eq '100'} selected='selected' {/if}>100</option>
                   <option value='200' {if $itemsperpage eq '200'} selected='selected' {/if}>200</option>
                    
        </select>
       </div>
     </div>
     <div style="float:left; padding-left: 10px">
        <label for="status">{gt text='Status'}</label>
        <div>{html_options name='status' id='status' options=$itemstatus selected=$status}</div>
     </div>
</div>
        
    </fieldset>
</form>
          
<form class="z-form" id="zselex_bulkaction_form" action="{modurl modname='ZSELEX' type='admin' func='viewshop'}" method="post">
    <div style="overflow:auto;">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
       <table id="zselex_admintable" class="z-datatable">
            <thead>
                <tr>
                    <!--  <th></th> -->
                    <th>#<input type="checkbox" id="select_all"></th>
                    <th>{gt text='Actions'}</th>
                    <th><a class='{$sort.class.shop_id}' href='{$sort.url.shop_id|safetext}'>{gt text='ID'}</a></th>
                    <th><a class='{$sort.class.shop_name}' href='{$sort.url.shop_name|safetext}'>{gt text='Shop'}</a></th>
                    <th>{gt text='Title'}</th>
                    <th>{gt text='Bundle'}</th>
                    <th><a class='{$sort.class.address}' href='{$sort.url.address|safetext}'>{gt text='Address'}</a></th>
                    <th><a class='{$sort.class.telephone}' href='{$sort.url.telephone|safetext}'>{gt text='Telephone'}</a></th>
                   
                    <th><a class='{$sort.class.email}' href='{$sort.url.email|safetext}'>{gt text='Email'}</a></th>
                    <th><a class='{$sort.class.owner}' href='{$sort.url.uname|safetext}'>{gt text='Owner'}</a></th>
                    <th>{gt text='Admin'}</th>
                    <th>{gt text='Affiliate'}</th>
                    <th>{gt text='Shop Type'}</th>
                     <th>{gt text='Fax'}</th>
                    <th><a class='{$sort.class.country_name}' href='{$sort.url.country_name|safetext}'>{gt text='Country'}</a></th>
                    <th><a class='{$sort.class.region_name}' href='{$sort.url.region_name|safetext}'>{gt text='Region'}</a></th>
                    <th><a class='{$sort.class.cityName}' href='{$sort.url.cityName|safetext}'>{gt text='City'}</a></th>
                    <th><a class='{$sort.class.area_name}' href='{$sort.url.area_name|safetext}'>{gt text='Area'}</a></th>
                   {* <th><a class='{$sort.class.categoryName}' href='{$sort.url.categoryName|safetext}'>{gt text='Category'}</a></th>*}
                    <th>{gt text='Category'}</th>
                   {* <th><a class='{$sort.class.branch_name}' href='{$sort.url.branch_name|safetext}'>{gt text='Branch'}</a></th>*}
                    <th>{gt text='Branch'}</th>
                    <th>{gt text='Description'}</th>
                  
                    <th><a class='{$sort.class.status}' href='{$sort.url.status|safetext}'>{gt text='Status'}</th>
                  {*<th>{gt text='Created User'}</th> *}
                    <th><a class='{$sort.class.cr_date}' href='{$sort.url.cr_date|safetext}'>{gt text='Created'}</a></th>
                    {*<th>{gt text='Updated User'}</th>*}
                    <th><a class='{$sort.class.lu_date}' href='{$sort.url.lu_date|safetext}'>{gt text='Updated'}</a></th>
                   
                </tr>
            </thead>
            <tbody>
                {foreach from=$shopsitems item='shopitem'}
                <tr class="{cycle values='z-odd,z-even'}">
                    <!--  <td><input type="checkbox" name="zselex_selected_shops[]" value="{$shopitem.shop_id}" class="zselex_checkbox" /></td> -->
                    <td><input type="checkbox" class="shop_ids shop_ids1" name="shop_ids[]" value="{$shopitem.shop_id}"></td>
                    <td>
                        {assign var='options' value=$shopitem.options}
                        {section name='options' loop=$options}
                        <a href="{$options[options].url|safetext}">{img modname='core' set='icons/extrasmall' src=$options[options].image title=$options[options].title alt=$options[options].title class='tooltips'}</a>
                        {/section}
                    </td>
                    <td>{$shopitem.shop_id|safetext}</td>
                    <td><a href='index.php?module=zselex&type=admin&func=shopinnerview&shop_id={$shopitem.shop_id}'>{$shopitem.shop_name|safetext|stripslashes}</a>
                    </td>
                    <td>{$shopitem.urltitle|safetext}</td>
                    <td>{$shopitem.bundle_name|safetext}</td>
                    <td>{$shopitem.address|safetext}</td>
                    <td>{$shopitem.telephone|safetext}</td>
                   
                    <td>{$shopitem.email|safetext}</td>
                    <td>{$shopitem.owner|safetext}</td>
                    <td>
	                {foreach from=$shopitem.adminnames item='adminname'}
    	                {$adminname.uname|safetext}<br />
	                {/foreach}
                    </td>
                    <td>{$shopitem.aff_name|safetext}</td>
                    <td>{if $shopitem.shoptype neq ''}{$shopitem.shoptype|safetext}{else}<i>{gt text='not configured'}</i>{/if}</td>
                    <td>{$shopitem.fax|safetext}</td>
                    <td>{$shopitem.country_name|safetext}</td>
                    <td>{$shopitem.region_name|safetext}</td>
                    <td>{$shopitem.city_name|safetext}</td>
                    <td>{$shopitem.area_name|safetext}</td>
                    <td>
                        {foreach from=$shopitem.shop_categories item='shopcat'}
                            {$shopcat.category_name|ucfirst|safetext}
                        {/foreach}
                    </td>
                    <td>
                       {foreach from=$shopitem.shop_branches item='shopbranch'}
                            {$shopbranch.branch_name|ucfirst|safetext}
                        {/foreach}
                    </td>
                    <td>{$shopitem.description|safetext}</td>
                   
                    <td>{$aStatus[$shopitem.status]|safetext}</td>
                   {*  <td>{$shopitem.createduser|safetext}</td>  *}
                    <td>{$shopitem.cr_date|safetext}</td>
                    {*  <td>{$shopitem.updateduser|safetext}</td>*}
                    <td>{$shopitem.lu_date|safetext}</td>
                  
                </tr>
                {foreachelse}
                <tr class="z-datatableempty"><td colspan="24">{gt text='No shops currently in database.'}</td></tr>
                {/foreach}
            </tbody>
        </table>
        <!-- 
        <p id='zselex_bulkaction_control'>
            {img modname='core' set='icons/extrasmall' src='2uparrow.png' __alt='doubleuparrow'}<a href="javascript:void(0);" id="zselex_select_all">{gt text="Check all"}</a> / <a href="javascript:void(0);" id="zselex_deselect_all">{gt text="Uncheck all"}</a>
            <select id='zselex_bulkaction_select' name='zselex_bulkaction_select'>
                <option value='0' selected='selected'>{gt text='With selected:'}</option>
                <option value='1'>{gt text='Delete'}</option>
                <option value='2'>{gt text='active'}</option>
            </select>
        </p>
        -->
    </div>
            <input disabled type="hidden" name="chg_cat" id="chg_cat">
            <input disabled type="hidden" name="chg_brnch" id="chg_brnch">
            <input disabled type="hidden" name="chg_aff" id="chg_aff">
            <input disabled type="hidden" name="chg_stat" id="chg_stat">
            <input disabled type="hidden" name="chg_del" id="chg_del">
            <input disabled type="hidden" name="chg_demo" id="chg_demo">
            <input disabled type="hidden" name="chg_type" id="chg_type">
            <input disabled type="hidden" name="chg_group" id="chg_group">
            <p id='news_bulkaction_control'>
   <select id='select_type' name='select_type'>
        <option value='0' selected='selected'>{gt text='With selected:'}</option>
        <option value='aff'>{gt text='Change Affiliate'}</option>
        <option value='cat'>{gt text='Change Category'}</option>
        <option value='rm_cat'>{gt text='Remove Category'}</option>
        <option value='brnch'>{gt text='Change Branch'}</option>
        <option value='rm_brnch'>{gt text='Remove Branch'}</option>
        <option value='stat'>{gt text='Change Status'}</option>
        <option value='rdemo'>{gt text='Reactivate Demo'}</option>
        <option value='upbundle'>{gt text='Update Bundles'}</option>
        <option value='del'>{gt text='Delete'}</option>
        <option value='group'>{gt text='Assign to Group'}</option>
   </select>
</p>
</form>
                       <script type="text/javascript">
                        var defwindowajax = new Zikula.UI.Window($('defwindowajax1'),{resizable: true});
                     
                        </script>
<!-- 
<form class="z-form" action="{modurl modname='ZSELEX' type='admin' func='modifyshop'}" method="post">
    <div>
        <fieldset>
            <label for="directshop_id">{gt text='Access a past shop via its ID'}:</label>
            <input type="text" id="directshop_id" name="shop_id" value="" size="5" maxlength="8" />
            <span class="z-nowrap z-buttons">
                <input class="z-bt-small" name="submit" type="submit" value="{gt text='Go retrieve'}" />
                <input class="z-bt-small" name="reset" type="reset" value="{gt text='Reset'}" />
            </span>
        </fieldset>
    </div>
</form>
-->
<div>

  <select id="cat" style="display:none">
                    <option value=''>{gt text='select category'}</option>
                    {foreach from=$categories  item='category'}
                    <option value="{$category.category_id}" >{$category.category_name}</option>
                    {/foreach}
  </select>
  <select id="brnch"  style="display:none">
                    <option value=''>{gt text='select branch'}</option>
                        {foreach from=$branches  item='branch'}
                        <option value="{$branch.branch_id}" >{$branch.branch_name}</option>
                        {/foreach}
                    </select>
  <select id="aff" style="display:none">
             <option value='0'>{gt text='select affiliate'}</option>
            {foreach from=$affiliates  item='affiliate'}
             <option value="{$affiliate.aff_id}" >{$affiliate.aff_name|upper}</option>
            {/foreach}
  </select>
  <select id="stat" style="display:none">
        <option value=''>{gt text='select status'}</option>
        <option value='1'>{gt text='Active'}</option>
        <option value='0'>{gt text='InActive'}</option>
        
  </select>
  <select id="group" style="display:none">
             <option value='0'>{gt text='select group'}</option>
            {foreach from=$groups  item='group'}
             <option value="{$group.gid}" >{$group.name}</option>
            {/foreach}
  </select>
 </div>           
{pager rowcount=$total_shops limit=$itemsperpage posvar='startnum' maxpages=10}
{adminfooter}
<!-- This form below appears as a formdialog when a bulk action of 'change categories' is selected -->

<script type="text/javascript">
// <![CDATA[
    Zikula.UI.Tooltips($$('.tooltips'));
// ]]>
</script>

<style>
     .cat_content {
       background-color: white;
        /* border: 5px solid #DD511D;*/
        left: 50%;
        min-height: 70px;
        margin-left: -270px;
        /* overflow: hidden;*/
        height: auto;
        position: absolute;
        padding: 20px;
        top: 10%;
        width: 500px;
        z-index: 10002;
    }
    
    
    .backshield {
        background-color: #333333;
        height: 300%;
        left: 0;
        opacity: 0.8;
        position: absolute;
        top: 0;
        width: 100%;
        z-index: 1000;
    }
</style>
<div id="updateBundles" class="cat_content" style="display:none">

</div>
<div id="backshield" class="backshield" style="min-height: 12000px;height:auto;display:none"></div> 
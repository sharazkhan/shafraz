
{shopheader}


<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='Existing owners list'}</h3>
</div>

<form class="z-form" id="plugin_filter" action="{modurl modname="ZSELEX" type="admin" func="existingOwner" shop_id=$shop_id}" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset id="zselex_multicategory_filter"{if $filter_active} class='filteractive'{/if}>
        {if $filter_active}{gt text='active' assign=filteractive}{elseif $filter_active neq ''}{gt text='inactive' assign=filteractive}{else $filter_active eq ''}{gt text='All' assign=filteractive}{/if}
        <legend>{gt text='Filter %1$s, %2$s Owner listed' plural='Filter %1$s, %2$s Owners listed' count=$total_count tag1=$filteractive tag2=$total_count}</legend>
        <input type="hidden" name="startnum" value="{$startnum}" />
        <input type="hidden" name="order" value="{$order}" />
        <input type="hidden" name="sdir" value="{$sdir}" />
        <label for="searchtext">{gt text='Owners'}</label>
        <input type="text" name="searchtext" value="{$searchtext}" />
       
        &nbsp;
        &nbsp;&nbsp;
        <span class="z-nowrap z-buttons">
            <input class='z-bt-filter' name="submit" type="submit" value="{gt text='Filter'}" />
            <a href="{modurl modname="ZSELEX" type="admin" func="existingOwner" shop_id=$shop_id}" title="{gt text="Clear"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Clear" __title="Clear"} {gt text="Clear"}</a>
        </span>
    </fieldset>
</form>

<form class="z-form" id="zselex_bulkaction_form" action="{modurl modname="ZSELEX" type="admin" func="existingOwner" shop_id=$shop_id}" method="post">
    <div style="overflow:auto;">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
       <table id="zselex_admintable" class="z-datatable">
            <thead>
                <tr>
                    <th>{gt text='Select'}</th>
                    <th>{gt text='Owners'}</th>
                    <th>{gt text='Shops'}</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$owners item='owner'}
                <tr class="{cycle values='z-odd,z-even'}">
                  <td>
                      {*<input type="radio" name="shopowners[{$owner.uname}]" value="{$owner.uid}" id="{$owner.uid}" class="zselex_checkbox" />*}
                   <input type="radio" name="shopowners" value="{$owner.uid}" id="{$owner.uid}" class="zselex_checkbox" />
                  <td>
                         <label for={$owner.uid}>
                        {$owner.uname|safetext}
                         </label>
                    </td>
                    <td>
                        <table>
                            <tr>
                                <th>
                                {gt text='#' comment='Translator: This is header text for shop_id in a list of Shop ID, Shop Name and Shop Type'}
                                </th>
                                <th>
                                    {gt text='shop name'}
                                </th>
                                 <th>
                                    {gt text='shop type'}
                                </th>
                                 <th>
                                    {gt text='City'}
                                </th>
                            </tr>
                        {foreach from=$owner.shops item='shop' key='count'}
                            <tr>
                                <td>{$count+1}</td>
                                <td>
                          {$shop.shop_name|safetext} 
                                </td>
                                 <td>
                          {$shop.shoptype|safetext} 
                                </td>
                                 <td>
                          {$shop.city_name|safetext} 
                                </td>
                             </tr>
                             {foreachelse}
                                 <tr>
                                     <td align="center" colspan="2">No Shops</td>
                                 </tr>
                       {/foreach}
                          </table>   
                      
                    </td>
                    
                </tr>
                {foreachelse}
                <tr class="z-datatableempty"><td colspan="10">{gt text='No Owners.'}</td></tr>
                {/foreach}
                <tr>
                    <td colspan="3">
                 <div class="z-buttons z-formbuttons">
                       {if !empty($owners)}
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save" __name="typetable[action]" __value="save"}
                       {/if}
        <a href="{modurl modname="ZSELEX" type="adminusers" func='viewOwner' shop_id=$shop_id}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
                 </div>
                </td>
                
                </tr>
            </tbody>
        </table>
      
    </div>
</form>
{pager rowcount=$total_count limit=$itemsperpage posvar='startnum' maxpages=10}

{adminfooter}

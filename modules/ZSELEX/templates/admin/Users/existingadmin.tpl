
{shopheader}


<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='Existing admins list'}</h3>
</div>


<form class="z-form" id="plugin_filter" action="" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset id="zselex_multicategory_filter"{if $filter_active} class='filteractive'{/if}>
        {if $filter_active}{gt text='active' assign=filteractive}{elseif $filter_active neq ''}{gt text='inactive' assign=filteractive}{else $filter_active eq ''}{gt text='All' assign=filteractive}{/if}
        <legend>{gt text='Filter %1$s, %2$s Admin listed' plural='Filter %1$s, %2$s Admins listed' count=$total_count tag1=$filteractive tag2=$total_count}</legend>
        <input type="hidden" name="startnum" value="{$startnum}" />
        <input type="hidden" name="order" value="{$order}" />
        <input type="hidden" name="sdir" value="{$sdir}" />
        <label for="searchtext">{gt text='Admins'}</label>
        <input type="text" name="searchtext" value="{$searchtext}" />
       
        &nbsp;
        &nbsp;&nbsp;
        <span class="z-nowrap z-buttons">
            <input class='z-bt-filter' name="submit" type="submit" value="{gt text='Filter'}" />
            <a href="" title="{gt text="Clear"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Clear" __title="Clear"} {gt text="Clear"}</a>
        </span>
    </fieldset>
</form>

<form class="z-form" id="zselex_bulkaction_form" action="" method="post">
    <div style="overflow:auto;">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
       <table id="zselex_admintable" class="z-datatable">
            <thead>
                <tr>
                    <th>{gt text='Select'}</th>
                    <th>{gt text='Admins'}</th>
                    <th>{gt text='Shops'}</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$admins item='admin'}
                <tr class="{cycle values='z-odd,z-even'}">
                  <td><input type="checkbox" name="shopadmins[{$admin.uname}]" value="{$admin.uid}" id="{$admin.uid}" class="zselex_checkbox" />
                    <td>
                         <label for={$admin.uid}>
                        {$admin.uname|safetext}
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
                            </tr>
                        {foreach from=$admin.shops item='shop' key='count'}
                            
                            <tr>
                                <td>{$count+1}</td>
                                <td>
                          {$shop.shop_name|safetext} 
                                </td>
                                 <td>
                          {$shop.shoptype|safetext} 
                                </td>
                             </tr>
                             {/foreach}
                          </table>   
                      
                    </td>
                    
                </tr>
                {foreachelse}
                <tr class="z-datatableempty"><td colspan="10">{gt text='No Admins.'}</td></tr>
                {/foreach}
                <tr>
                    <td colspan="3">
                 <div class="z-buttons z-formbuttons">
                     {if !empty($admins)}
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save" __name="action" __value="save"}
                      {/if}
       {* <a href="{modurl modname="ZSELEX" type="adminusers" func='view' shop_id=$shop_id}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a> *}
                 <a href="{modurl modname="ZSELEX" type="adminusers" func='view' shop_id=$shop_id}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
                 </div>
                </td>
                
                </tr>
            </tbody>
        </table>
      
    </div>
</form>

{pager rowcount=$total_count limit=$itemsperpage posvar='startnum' maxpages=10}
{adminfooter}

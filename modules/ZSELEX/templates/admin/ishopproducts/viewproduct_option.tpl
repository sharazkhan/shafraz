 <script>
    
     jQuery(document).ready(function() {
     window.parent.document.getElementById("manage_content").style.backgroundImage = 'none';
   
     });
     
    
</script>
 {securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADD}
<div class="z-admin-content-pagetitle">
    <a href="index.php?module=zselex&amp;type=admin&amp;func=createOption&shop_id={$smarty.request.shop_id}" class="z-iconlink z-icon-es-add">{gt text='Create Option'}</a>
</div>
 {/securityutil_checkpermission_block}
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='Product Option'}</h3>
</div>

<form class="z-form" id="plugin_filter" action="{modurl modname='ZSELEX' type='admin' func='viewshoptypes'}" method="post" enctype="application/x-www-form-urlencoded">
    
</form>
<form class="z-form" id="option_form" action="" method="post">
     <div style="overflow:auto;">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <div align="right">
        <button  id="links"  type="submit"  name="saveType" value="sortOrder" title="{gt text='Save'}">
                     {img modname=core src="filesave.png" set="icons/extrasmall" __alt="Save sort order" __title="Save sort order"}
                      {gt text="Save"}
         </button>
         </div>
       <table id="zselex_admintable" class="z-datatable">
            <thead>
                <tr>
                    <th>{gt text='Actions'}</th>
                    <th><a class='{$sort.class.option_id}' href='{$sort.url.option_id|safetext}'>{gt text='ID'}</a></th>
                    <th><a class='{$sort.class.option_name}' href='{$sort.url.option_name|safetext}'>{gt text='Name'}</a></th>
                    <th>
                        <div class="z-buttons z-formbuttons">  
                         {gt text='Link'}
                         {*  &nbsp; 
                         <button onClick="document.forms['option_form'].submit();" id="links"  type="submit"  name="saveType" value="links" title="{gt text='Save'}">
          {img modname=core src="filesave.png" set="icons/extrasmall" __alt="Save Links" __title="Save Links"}
          {gt text="Save"}
                        </button>*}
                         </div>
             </th>
              <th>
             <div class="z-buttons z-formbuttons">  
                         {gt text='Sort Order'}
                          {* &nbsp; 
                          <button  id="links"  type="submit"  name="saveType" value="sortOrder" title="{gt text='Save'}">
                     {img modname=core src="filesave.png" set="icons/extrasmall" __alt="Save sort order" __title="Save sort order"}
                      {gt text="Save Order"}
                        </button>*}
                         </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$product_options item='item'}
                <tr class="{cycle values='z-odd,z-even'}">
                    <td>
                    <a  href="{modurl modname='ZSELEX' type='admin' func='deleteProductOption' shop_id=$item.shop_id option_id=$item.option_id}">{img modname='core' set='icons/extrasmall' src='14_layer_deletelayer.png' title='Delete' alt="Delete" class='tooltips'}</a>
                    <a href="{modurl modname='ZSELEX' type='admin' func='editOption' option_id=$item.option_id shop_id=$item.shop_id}" >{img modname='core' set='icons/extrasmall' src='xedit.png' title='Edit' alt="Edit" class='tooltips'}</a>
                    </td>
                    <td>
                        {$item.option_id|safetext}
                    </td>
                    <td>
                        {$item.option_name|safetext}
                        {optionValueCheck option_id=$item.option_id}
                    </td>
                     <td align="center">
                         <input type="checkbox" name="linkOptionIds[]" value="{$item.option_id|safetext}"  {if $item.parent_option_id > 0}checked{/if}>
                     </td>
                      <td align="center">
                         <input size=2 type="textbox" name="optionSortOrder[{$item.option_id|safetext}]" value="{$item.sort_order|safetext}">
                     </td>
                </tr>
                {foreachelse}
                <tr class="z-datatableempty"><td colspan="10">{gt text='No product options found.'}</td></tr>
                {/foreach}
            </tbody>
        </table>
      
    </div>
</form>

{pager rowcount=$total limit=$itemsperpage posvar='startnum' maxpages=10}

<div>
   <font color="grey"> <i>{gt text='Note: If you want options in 2 dimensions (typical Size and Color) plz. check those 2 options in the Link column above.'}</i></font>
</div>

<script type="text/javascript">
// <![CDATA[
    Zikula.UI.Tooltips($$('.tooltips'));
// ]]>
</script>
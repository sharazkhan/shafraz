
<!------------------ VALIDATE PLUGIN --------------------------------------->
{pageaddvar name="stylesheet" value="modules/ZSELEX/javascript/validation/style.css"} 
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/validation/fabtabulous.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/validation/validation.js'}
<!---------------------------------------------------------------------->
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/sociallinkshop.js'}
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='Social Links Settings'}</h3>
</div>
{*
<div align="right">
<button class="ProductPageBtn" type="button">{gt text='Create Links'}</button>
</div>
*}
<form action="" class="z-form" id="linkform" name="zselex_form" method="post">
     <input type="hidden" value="{$shop_id}"  name="formElements[shop_id]"/>
<div class="z-panels" id="panel">
     <fieldset>
     <legend id="settingheaders" title="{gt text='Click here to configure image sizes'}">{gt text='Social Links Options'}</legend>
      
     <div id="settingheadersdiv" style="display:none">
         <fieldset>
             <legend>{gt text='Icon Size'}</legend>
        <div class="z-formrow">
            <label for="small">{gt text='Small'}</label>
            <div>
                <input type="radio" value="small" id="small" name="formElements[iconsize]" {if empty($soc_settings.icon_size)}checked="checked"{elseif $soc_settings.icon_size eq 'small'} checked="checked"{/if}/>
            </div>
        </div>
        <div class="z-formrow">
            <label for="medium">{gt text='Medium'}</label>
             <div>
            <input  type="radio" value="medium" id="medium" name="formElements[iconsize]"{if $soc_settings.icon_size eq 'medium'} checked="checked"{/if}/>
             </div>
        </div>
         <div class="z-formrow">
            <label for="large">{gt text='Large'}</label>
             <div>
            <input type="radio" value="large" id="large" name="formElements[iconsize]"{if $soc_settings.icon_size eq 'large'} checked="checked"{/if}/>
            </div>
         </div>
         </fieldset>
      </div>
        
    </fieldset>
</div>
   <fieldset>
     <legend title="{gt text='Click here to configure ztext'}">{gt text='Social Links'}</legend>
      {foreach from=$social_links item='item'}
          {if $item.status eq true}
        <div class="z-formrow">
            <label for="pageindex_disabled">
                <div style="float:left;margin-left:60px;margin-top:-8px"><img src="{$themepath}/images/social_icons/small/{$item.socl_image}" ></div>
                <div >{$item.socl_link_name}</div>
            </label>
            <input type="text" value="{sociallinkshopurl shop_id=$shop_id soc_link_id=$item.socl_link_id}" id="{$item.socl_link_id}" name="formElements[url][{$item.socl_link_id}]" class="validate-url" title='{gt text='Please enter a valid url'}'/>
        </div>
        {/if}
        {/foreach}
        
    </fieldset>
        {if !$socialllink_perm.expired AND $socialllink_perm.perm}
        <div class="z-buttons z-formbuttons">
                     <button id="zselex_button_submit"  class="z-btgreen linksubmit" type="submit"  name="action" value="1" title="{gt text='Save this region'}">
            {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save' }
             {gt text='Save'}
            </button>
                    <a href="{modurl modname="ZSELEX" type="user" func="site" shop_id=$smarty.request.shop_id}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
        </div>
        {/if}
</form>
        
<script type="text/javascript">
    var panel = new Zikula.UI.Panels('panel', {
            headerSelector: '#settingheaders',
            headerClassName: 'z-panel-indicator'
            // active: [~~(anchors.indexOf(window.location.hash) <= -1)]

    });
</script>
<style>
.validation-advice{
   margin-left:168px;
    }
 </style>
{adminfooter}
{ajaxheader ui=true}
{ajaxheader imageviewer="true"}
{pageaddvarblock}
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        Zikula.UI.Tooltips($$('.tooltips'));
    });
</script>
{/pageaddvarblock}
{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="config" size="small"}
    <h3>{gt text='ZSELEX settings'}</h3>
</div>

<div align="right">
 <a href="{modurl modname="ZSELEX" type="admin" func="updateShopKeywords"}">
     <button class="ProductPageBtn"  type="button">{gt text="Update Keywords"} </button>
 </a>
 <a href="{modurl modname="ZSELEX" type="admin" func="updateEventTemp"}">
     <button class="ProductPageBtn"  type="button">{gt text="Update Events"} </button>
 </a>
 </div>
<form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="updateconfig"}" method="post" enctype="application/x-www-form-urlencoded">
      <div>
        <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
               <fieldset>
            <legend>{gt text='General Settings'}</legend>
            {*<div class="z-formrow">
                <label for="themeprice">{gt text='Price'}</label>
                <input type="text" value="{$modvars.ZSELEX.themeprice}" id="themeprice" name="themeprice"/>
            </div>*}
            <div class="z-formrow">
                <label for="default_country_id">{gt text='Default Country ID'}</label>
                <input type="text" value="{$modvars.ZSELEX.default_country_id}" id="default_country_id" name="default_country_id"/>
            </div>
            <div class="z-formrow">
                <label for="default_country_id">{gt text='Default Country Name'}</label>
                <input type="text" value="{$modvars.ZSELEX.default_country_name}" id="default_country_name" name="default_country_name"/>
            </div>
        </fieldset>
        <fieldset>
            <legend>{gt text='Terms and Conditions'}</legend>
            {assign var="termsConditionInfo" value=$modvars.ZSELEX.termsConditionInfo|unserialize} 
             {foreach item='language' from=$languages}
            <div class="z-formrow" >
                <label for="infotitle">{gt text="Terms of trade"}(<b>{$language}</b>)</label>
                <textarea id="{$language}" name="termsConditionInfo[{$language}][termsoftrade]">{$termsConditionInfo.$language.termsoftrade}</textarea>
            </div> 
            {/foreach}
            {foreach item='language' from=$languages}
            <div class="z-formrow" >
                <label for="infotitle">{gt text="RMA"}(<b>{$language}</b>)</label>
                <textarea id="{$language}" name="termsConditionInfo[{$language}][rma]">{$termsConditionInfo.$language.rma}</textarea>
            </div> 
            {/foreach}
            {foreach item='language' from=$languages}
            <div class="z-formrow" >
                <label for="infotitle">{gt text="Delivery prices"}(<b>{$language}</b>)</label>
                <textarea id="{$language}" name="termsConditionInfo[{$language}][deliveryprices]">{$termsConditionInfo.$language.deliveryprices}</textarea>
            </div>
            {/foreach}
            {foreach item='language' from=$languages}
            <div class="z-formrow" >
                <label for="infotitle">{gt text="Delivery time"}(<b>{$language}</b>)</label>
                <textarea id="{$language}" name="termsConditionInfo[{$language}][deliverytime]">{$termsConditionInfo.$language.deliverytime}</textarea>
            </div> 
            {/foreach}
           
             {foreach item='language' from=$languages}
            <div class="z-formrow" >
                <label for="infotitle">{gt text="Privacy"}(<b>{$language}</b>)</label>
                <textarea id="{$language}" name="termsConditionInfo[{$language}][privacy]">{$termsConditionInfo.$language.privacy}</textarea>
            </div> 
            {/foreach}
             {foreach item='language' from=$languages}
            <div class="z-formrow" >
                <label for="infotitle">{gt text="Secure payment"}(<b>{$language}</b>)</label>
                <textarea id="{$language}" name="termsConditionInfo[{$language}][securepayment]">{$termsConditionInfo.$language.securepayment}</textarea>
            </div> 
            {/foreach}
        </fieldset>
        <fieldset>
            <legend>{gt text='Set Image Sizes '}</legend>
            <div class="z-formrow">
                <label for="fullimagewidth">{gt text='max full size width'}</label>
                <input type="text" value="{$modvars.ZSELEX.fullimagewidth}" id="themeprice" name="fullimagewidth"/>
            </div>
            <div class="z-formrow">
                <label for="fullimageheight">{gt text='max full size height'}</label>
                <input type="text" value="{$modvars.ZSELEX.fullimageheight}" id="themeprice" name="fullimageheight"/>
            </div>
            <div class="z-formrow">
                <label for="medimagewidth">{gt text='max medium size width'}</label>
                <input type="text" value="{$modvars.ZSELEX.medimagewidth}" id="themeprice" name="medimagewidth"/>
            </div>
            <div class="z-formrow">
                <label for="themeprice">{gt text='max medium size height'}</label>
                <input type="text" value="{$modvars.ZSELEX.medimageheight}" id="themeprice" name="medimageheight"/>
            </div>
            <div class="z-formrow">
                <label for="thumbimagewidth">{gt text='max thumb size width'}</label>
                <input type="text" value="{$modvars.ZSELEX.thumbimagewidth}" id="themeprice" name="thumbimagewidth"/>
            </div>

            <div class="z-formrow">
                <label for="thumbimageheight">{gt text='max thumb size height'}</label>
                <input type="text" value="{$modvars.ZSELEX.thumbimageheight}" id="themeprice" name="thumbimageheight"/>
            </div>
        </fieldset>
        <fieldset>
            <legend>{gt text='Set Groups'}</legend>
            <div class="z-formrow">
                <label for="shopAdminGroup">{gt text='Choose Shop Admin Group'}</label>
                <select name='shopAdminGroup' id='shopAdminGroup'>
                    <option value='0'>{gt text='Select'}</option>
                    {foreach from=$groups  item='item'}
                    <option value="{$item.gid}" {if $modvars.ZSELEX.shopAdminGroup eq $item.gid} selected="selected"{/if} > {$item.name} </option>
                    {/foreach}
                </select>
            </div>
            <div class="z-formrow">
                <label for="shopOwnerGroup">{gt text='Choose Shop Owner Group'}</label>
                <select name='shopOwnerGroup' id='shopOwnerGroup'>
                    <option value='0'>{gt text='Select'}</option>
                    {foreach from=$groups  item='item'}
                    <option value="{$item.gid}" {if $modvars.ZSELEX.shopOwnerGroup eq $item.gid} selected="selected"{/if} > {$item.name} </option>
                    {/foreach}
                </select>
            </div>
        </fieldset>
        <fieldset>
            <legend>{gt text='Service Configurations'}</legend>
            <div class="z-formrow">
                <label for="servicemessage">{gt text='Service Expiry Reminder Days'}</label>
                <input type="text" value="{$modvars.ZSELEX.serviceexpiryday}" id="serviceexpiryday" name="serviceexpiryday"/>
            </div>
            <div class="z-formrow">
                <label for="diskquotaitem">{gt text='Diskquota each item in MB'}</label>
                <input type="text" value="{$modvars.ZSELEX.diskquotaitem}" id="diskquotaitem" name="diskquotaitem"/>
            </div>
            <div class="z-formrow">
                <label for="remindercontentdays">{gt text='Reminder Content Days'}</label>
                <input type="text" value="{$modvars.ZSELEX.remindercontentdays}" id="remindercontentdays" name="remindercontentdays"/>
            </div>
            <div class="z-formrow">
                <label for="invoiceday">{gt text='Invoice Day Of Month'}</label>
                <input type="text" value="{$modvars.ZSELEX.invoiceday}" id="invoiceday" name="invoiceday"/>
            </div>
        </fieldset>

        <fieldset>
            <legend>{gt text='Shop block on frontpage settings'}</legend>
            <div class="z-formrow">
                <label for="invoiceday">{gt text='Order By'}</label>
                <select name='shoporderby' id='shoporderby' >
                    <option value="">{gt text='Select'}</option>
                    <option value="new" {if $modvars.ZSELEX.shoporderby eq 'new'} selected {/if}>{gt text='New shops'}</option>
                    <option value="top" {if $modvars.ZSELEX.shoporderby eq 'top'} selected {/if}>{gt text='Top shops'}</option>
                    <option value="rank" {if $modvars.ZSELEX.shoporderby eq 'rank'} selected {/if}>{gt text='Top ranking shops'} </option>
                </select>
            </div>
            <div class="z-formrow">
                <label for="shopfrontlimit">{gt text='Shop Limit'}</label>
                <input type="text" value="{$modvars.ZSELEX.shopfrontlimit}" id="shopfrontlimit" name="shopfrontlimit" />
            </div>
        </fieldset>  

        <div class="z-buttons z-formbuttons">
            {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
            <a href="{modurl modname="ZSELEX" type="admin" func='modifyconfig'}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
        </div>
    </div>
</form>
{adminfooter}

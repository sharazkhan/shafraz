{pageaddvar name='javascript' value='modules/ZSELEX/javascript/zselex_admin_validation.js'}
{shopheader}
<div class="z-admin-content-pagetitle">
 {if $item.type_id neq ''}
        <h3>{gt text='Update Type'}</h3>
        {else}
    	<h3>{gt text='Paypal Config'}</h3>
        {/if}
</div>

<form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="paypalConfig" shop_id=$smarty.request.shop_id}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
      
    <input type="hidden" name="paypalinfo[paypalId]" value="{$item.id}" />
    <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
    <fieldset>
        <legend>{gt text='Paypal Config'}</legend>
        <div class="z-formrow">
            <label for="type_name">{gt text='Paypal Email'}</label>
            <input type="text" id="type_name" name="paypalinfo[pemail]" value="{if $pperror eq 1}{$ppemail}{else}{$item.paypal_email|safetext}{/if}" />
        </div>
       
    </fieldset>
    <div class="z-buttons z-formbuttons">
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save" __name="typetable[action]" __value="save"}
        <a href="{modurl modname="ZSELEX" type="admin" func='paymentgateway' shop_id=$smarty.request.shop_id}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    </div>
</form>
{adminfooter}
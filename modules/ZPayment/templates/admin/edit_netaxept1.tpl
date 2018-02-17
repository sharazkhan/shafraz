{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="config" size="small"}
    <h3>{gt text='Netaxept settings'}</h3>
</div>

<form class="z-form" action="{modurl modname="ZPayment" type="admin" func="updateNetaxept1"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
    <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
     <input type="hidden" name="formElement[shop_id]" value="{$shop_id}" />
    
    <fieldset>
        <legend>{gt text='Netaxept settings'}</legend>
         <div class="z-formrow">
			<label for="Netaxept_enabled">{gt text='Enable'}</label>
			<input type="checkbox" value="1" id="Netaxept_enabled" name="formElement[Netaxept_enabled]"{if $netaxept.enabled eq true} checked="checked"{/if}/>
        </div>
        <div class="z-formrow">
			<label for="Netaxept_testmode">{gt text='Test Mode'}</label>
			<input type="checkbox" value="1" id="Netaxept_testmode" name="formElement[Netaxept_testmode]"{if $netaxept.test_mode eq true} checked="checked"{/if}/>
        </div>
       <div class="z-formrow">
			<label for="Netaxept_merchant_id">{gt text='Merchant ID'}</label>
			<input type="text" value="{$netaxept.merchant_id}" id="Netaxept_merchant_id" name="formElement[Netaxept_merchant_id]"/>
        </div>
        <div class="z-formrow">
			<label for="Netaxept_token">{gt text='Token'}</label>
			<input type="text" value="{$netaxept.token}" id="Netaxept_token" name="formElement[Netaxept_token]"/>
        </div>
    </fieldset>
    <div class="z-buttons z-formbuttons">
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
        <a href="{modurl modname="ZSELEX" type="admin" func='paymentgateway' shop_id=$smarty.request.shop_id}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    </div>
</form>
{adminfooter}
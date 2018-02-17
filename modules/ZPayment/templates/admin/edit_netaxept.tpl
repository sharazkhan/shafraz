{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="config" size="small"}
    <h3>{gt text='Netaxept settings'}</h3>
</div>

<form class="z-form" action="{modurl modname="ZPayment" type="admin" func="updateNetaxept"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
    <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
    
    <fieldset>
        <legend>{gt text='Netaxept settings'}</legend>
         <div class="z-formrow">
			<label for="Netaxept_enabled">{gt text='Enable'}</label>
			<input type="checkbox" value="1" id="Netaxept_enabled" name="Netaxept_enabled"{if $modvars.ZPayment.Netaxept_enabled eq true} checked="checked"{/if}/>
        </div>
        <div class="z-formrow">
			<label for="Netaxept_testmode">{gt text='Test Mode'}</label>
			<input type="checkbox" value="1" id="Netaxept_testmode" name="Netaxept_testmode"{if $modvars.ZPayment.Netaxept_testmode eq true} checked="checked"{/if}/>
        </div>
         <div class="z-formrow">
			<label for="Netaxept_merchant_id">{gt text='Test Merchant ID'}</label>
			<input type="text" value="{$modvars.ZPayment.Netaxept_test_merchant_id}" id="Netaxept_test_merchant_id" name="Netaxept_test_merchant_id"/>
        </div>
        <div class="z-formrow">
			<label for="Netaxept_token">{gt text='Test Token'}</label>
			<input type="text" value="{$modvars.ZPayment.Netaxept_test_token}" id="Netaxept_test_token" name="Netaxept_test_token"/>
        </div>
       <div class="z-formrow">
			<label for="Netaxept_merchant_id">{gt text='Merchant ID'}</label>
			<input type="text" value="{$modvars.ZPayment.Netaxept_merchant_id}" id="Netaxept_merchant_id" name="Netaxept_merchant_id"/>
        </div>
        <div class="z-formrow">
			<label for="Netaxept_token">{gt text='Token'}</label>
			<input type="text" value="{$modvars.ZPayment.Netaxept_token}" id="Netaxept_token" name="Netaxept_token"/>
        </div>
    </fieldset>
    <div class="z-buttons z-formbuttons">
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
        <a href="{modurl modname="ZSELEX" type="admin" func='paymentgatewaysettings'}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    </div>
</form>
{adminfooter}
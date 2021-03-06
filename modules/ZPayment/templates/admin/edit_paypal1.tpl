{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="config" size="small"}
    <h3>{gt text='Paypal settings'}</h3>
</div>

<form class="z-form" action="{modurl modname="ZPayment" type="admin" func="updatePaypal1"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
    <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
     <input type="hidden" name="formElement[shop_id]" value="{$shop_id}" />
    
    <fieldset>
        <legend>{gt text='Paypal settings'}</legend>
         <div class="z-formrow">
			<label for="Paypal_disabled">{gt text='Enable'}</label>
			<input type="checkbox" value="1" id="Netaxept_testmode" name="formElement[Paypal_enabled]"{if $paypal.enabled eq true} checked="checked"{/if}/>
        </div>
        <div class="z-formrow">
			<label for="Paypal_testmode">{gt text='Test Mode'}</label>
			<input type="checkbox" value="1" id="Paypal_testmode" name="formElement[Paypal_testmode]"{if $paypal.test_mode eq true} checked="checked"{/if}/>
        </div>
       <div class="z-formrow">
			<label for="Paypal_business_email">{gt text='Business Email'}</label>
			<input type="text" value="{$paypal.business_email}" id="Paypal_business_email" name="formElement[Paypal_business_email]"/>
        </div>
        
    </fieldset>
    <div class="z-buttons z-formbuttons">
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
        <a href="{modurl modname="ZSELEX" type="admin" func='paymentgateway' shop_id=$smarty.request.shop_id}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    </div>
</form>
{adminfooter}
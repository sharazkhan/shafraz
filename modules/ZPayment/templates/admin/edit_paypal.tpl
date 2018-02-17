{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="config" size="small"}
    <h3>{gt text='Paypal settings'}</h3>
</div>

<form class="z-form" action="{modurl modname="ZPayment" type="admin" func="updatePaypal"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
    <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
    
    <fieldset>
        <legend>{gt text='Paypal settings'}</legend>
         <div class="z-formrow">
			<label for="Paypal_disabled">{gt text='Enable'}</label>
			<input type="checkbox" value="1" id="Netaxept_testmode" name="formElement[Paypal_enabled]"{if $modvars.ZPayment.Paypal_enabled eq true} checked="checked"{/if}/>
        </div>
        <div class="z-formrow">
			<label for="Paypal_testmode">{gt text='Test Mode'}</label>
			<input type="checkbox" value="1" id="Paypal_testmode" name="formElement[Paypal_testmode]"{if $modvars.ZPayment.Paypal_testmode eq true} checked="checked"{/if}/>
        </div>
        <div class="z-formrow">
			<label for="Paypal_business_email">{gt text='Business Email'}</label>
			<input type="text" value="{$modvars.ZPayment.Paypal_business_email}" id="Paypal_business_email" name="formElement[Paypal_business_email]"/>
        </div>
        <div class="z-formrow">
                    <label for="Paypal_pdt">{gt text='PDT(Payment Data Transfer)'}</label>
                    <input type="text" value="{$modvars.ZPayment.Paypal_pdt}" id="Paypal_pdt" name="formElement[Paypal_pdt]"/>
        </div>
        
    </fieldset>
    <div class="z-buttons z-formbuttons">
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
        <a href="{modurl modname="ZSELEX" type="admin" func='paymentgatewaysettings'}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    </div>
</form>
{adminfooter}
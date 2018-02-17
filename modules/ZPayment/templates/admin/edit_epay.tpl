{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="config" size="small"}
    <h3>{gt text='Epay settings'}</h3>
</div>

<form class="z-form" action="{modurl modname="ZPayment" type="admin" func="updateEpay"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
    <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
    
    <fieldset>
        <legend>{gt text='ePay settings'}</legend>
         <div class="z-formrow">
			<label for="Epay_enabled">{gt text='Enable'}</label>
			<input type="checkbox" value="1" id="Epay_enabled" name="formElement[Epay_enabled]"{if $modvars.ZPayment.Epay_enabled eq true} checked="checked"{/if}/>
        </div>
        <div class="z-formrow">
			<label for="Epay_testmode">{gt text='Test Mode'}</label>
			<input type="checkbox" value="1" id="Epay_testmode" name="formElement[Epay_testmode]"{if $modvars.ZPayment.Epay_testmode eq true} checked="checked"{/if}/>
        </div>
         <div class="z-formrow">
			<label for="Epay_test_merchant_number">{gt text='ePay Test Merchant Number'}</label>
			<input type="text" value="{$modvars.ZPayment.Epay_test_merchant_number}" id="Epay_test_merchant_number" name="formElement[Epay_test_merchant_number]"/>
        </div>
        
        <div class="z-formrow">
			<label for="Epay_merchant_number">{gt text='ePay Merchant Number'}</label>
			<input type="text" value="{$modvars.ZPayment.Epay_merchant_number}" id="Epay_merchant_number" name="formElement[Epay_merchant_number]"/>
        </div>
        
         <div class="z-formrow">
			<label for="Epay_md5_hash">{gt text='ePay md5 Hash'}</label>
			<input type="text" value="{$modvars.ZPayment.Epay_md5_hash}" id="Epay_md5_hash" name="formElement[Epay_md5_hash]"/>
        </div>
        {*
        <div class="z-formrow">
                    <label for="QuickPay_md5">{gt text='MD5 Secret'}</label>
                    <input type="text" value="{$modvars.ZPayment.QuickPay_md5}" id="QuickPay_md5" name="formElement[QuickPay_md5]"/>
        </div>
        *}
        
    </fieldset>
    <div class="z-buttons z-formbuttons">
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
        <a href="{modurl modname="ZSELEX" type="admin" func='paymentgatewaysettings'}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    </div>
</form>
{adminfooter}
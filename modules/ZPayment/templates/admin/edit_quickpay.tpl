{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="config" size="small"}
    <h3>{gt text='QuickPay settings'}</h3>
</div>

<form class="z-form" action="{modurl modname="ZPayment" type="admin" func="updateQuickPay"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
    <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
    
    <fieldset>
        <legend>{gt text='Paypal settings'}</legend>
         <div class="z-formrow">
			<label for="QuickPay_enabled">{gt text='Enable'}</label>
			<input type="checkbox" value="1" id="Netaxept_testmode" name="formElement[QuickPay_enabled]"{if $modvars.ZPayment.QuickPay_enabled eq true} checked="checked"{/if}/>
        </div>
        {*<div class="z-formrow">
			<label for="QuickPay_testmode">{gt text='Test Mode'}</label>
			<input type="checkbox" value="1" id="Paypal_testmode" name="formElement[QuickPay_testmode]"{if $modvars.ZPayment.QuickPay_testmode eq true} checked="checked"{/if}/>
        </div>*}
        {*<div class="z-formrow">
			<label for="QuickPay_ID">{gt text='QuickPay ID'}</label>
			<input type="text" value="{$modvars.ZPayment.QuickPay_ID}" id="QuickPay_ID" name="formElement[QuickPay_ID]"/>
        </div>*}
        <div class="z-formrow">
			<label for="QuickPay_Merchant_ID">{gt text='Merchant ID'}</label>
			<input type="text" value="{$modvars.ZPayment.QuickPay_Merchant_ID}" id="QuickPay_Merchant_ID" name="formElement[QuickPay_Merchant_ID]"/>
        </div>
         <div class="z-formrow">
			<label for="QuickPay_Agreement_ID">{gt text='Agreement ID'}</label>
			<input type="text" value="{$modvars.ZPayment.QuickPay_Agreement_ID}" id="QuickPay_Agreement_ID" name="formElement[QuickPay_Agreement_ID]"/>
        </div>
       {* <div class="z-formrow">
                    <label for="QuickPay_md5">{gt text='MD5 Secret'}</label>
                    <input type="text" value="{$modvars.ZPayment.QuickPay_md5}" id="QuickPay_md5" name="formElement[QuickPay_md5]"/>
        </div>*}
        
         <div class="z-formrow">
                    <label for="QuickPay_Api_Key">{gt text='Api Key'}</label>
                    <input type="text" value="{$modvars.ZPayment.QuickPay_Api_Key}" id="QuickPay_Api_Key" name="formElement[QuickPay_Api_Key]"/>
        </div>
        
    </fieldset>
    <div class="z-buttons z-formbuttons">
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
        <a href="{modurl modname="ZSELEX" type="admin" func='paymentgatewaysettings'}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    </div>
</form>
{adminfooter}
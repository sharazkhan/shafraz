{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="config" size="small"}
    <h3>{gt text='Directpay settings'}</h3>
</div>

<form class="z-form" action="{modurl modname="ZPayment" type="admin" func="updateDirectPay"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
    <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
     <input type="hidden" name="formElement[shop_id]" value="{$shop_id}" />
    
    <fieldset>
        <legend>{gt text='Directpay settings'}</legend>
         <div class="z-formrow">
			<label for="Directpay_enabled">{gt text='Enable'}</label>
			<input type="checkbox" value="1" id="Directpay_enabled" name="formElement[Directpay_enabled]"{if $directpay.enabled eq true} checked="checked"{/if}/>
        </div>
        
     	
        <div class="z-formrow">
			<label for="Directpay_info">{gt text='Info'}</label>
                        <textarea name="formElement[Directpay_info]">{$directpay.info}</textarea>
                        <span style="padding-left:250px">
                       <i> 
                             {gt text='Note: Text written in this field will be presented to your customer as a selectable payment method. Here you can write something like: “Please make bank transfer to our account xxxx-xxxxxxxx.We will ship your items when we register your payment.”'} 
                        </i>
                       </span>


        </div>
    </fieldset>
    <div class="z-buttons z-formbuttons">
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
        <a href="{modurl modname="ZSELEX" type="admin" func='paymentgateway' shop_id=$smarty.request.shop_id}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    </div>
</form>
{adminfooter}
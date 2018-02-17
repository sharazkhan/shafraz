{ajaxheader imageviewer="true"}
{adminheader}
<h3>Check Out</h3>

 <form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="serviceOrder" id=$shop_id}" method="post" enctype="application/x-www-form-urlencoded">
 
 <fieldset>
        <legend>{gt text='Primary Details'}</legend>
        <div class="z-formrow">
            <label for="type_name"><b>{gt text='Name'} :</b></label>
            <div>
                {$userinfo.uname}
            </div>
        </div>
        <div class="z-formrow">
            <label for="typedescription"><b>{gt text='Email'} :</b></label>
            <div>
                {$userinfo.email}
            </div>
        </div>

    </fieldset>
    <input type="hidden" name="name" value="{$userinfo.uname}">
    <input type="hidden" name="email" value="{$userinfo.email}">
    <input type="hidden" name="user_id" value="{$userinfo.uid}">

    <fieldset>
        <legend>{gt text='Shipping Address'}</legend>
        <div class="z-formrow">
            <label for="city">{gt text='City'}</label>
            <input type="text" id="city" name="city" value="{$city}" />
        </div>

        <div class="z-formrow">
            <label for="street">{gt text='Street'}</label>
            <input type="text" id="street" name="street" value="{$street}" />
        </div>

        <div class="z-formrow">
            <label for="address">{gt text='Address'}</label>
            <textarea id="address" name="address" cols="70" rows="10" />{$address}</textarea>
        </div>
        <div class="z-formrow">
            <label for="phone">{gt text='Phone'}</label>
            <input type="text" id="phone" name="phone" value="{$phone}" />
        </div>
    </fieldset>
    <div class="z-buttons z-formbuttons">
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Place Order"}
        <a href="{modurl modname="ZSELEX" type="user" func='cart'}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>

</form>
    
    {adminfooter}
   
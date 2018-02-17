
    <h1 style="color:red;">{gt text='ALERT!'}</h1>
    {gt text="<p>You are about to remove ALL your services. This leaves you only with the very basic information in your site. It's completely free but you will have very limited editing options!</p><p>Though we will not remove your data you will not be able to edit any of it until you buy a bundle again.</p><p>This means that should you continue with removal you will have the option to buy again later and your data in the system will be preserved so you will be able to edit them again.</p><p>Nevertheless... IF you choose to continue removal that information will not be visible in your site afterwards!<br>Furthermore be aware that removal will take effect immediately!</p><p>Please click <b>Cancel</b> to avoid removal or <b>Continue</b> if you want to proceed with removal of all services...</p>"}

    <div class="z-buttons z-formbuttons">
       
       <button onClick="return removeBundle();" id="crop_save"  type="button"  name="action" value="Continue" title="{gt text='Continue'}">
             {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Continue' __title='Continue'}
             {gt text='Continue'}
       </button>
        <a id="cancelD" href="#"  title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
       </div>
    
  
   <h1 style="color:red;">{gt text='ALERT!'}</h1>
    <p>{gt text='You are about to downgrade from'} <b>{$current_bundle_name}</b> {gt text='bundle to'} <b>{$new_bundle_name}</b> {gt text='bundle'}</p>
        {gt text=' <p>Please be aware that downgrading will result in some services being removed thus not showing the corresponding information anymore!</p><p>Though we will not remove your data from those services you will not be able to edit it until you upgrade your bundle again.</p><p>This means that should you continue with the downgrade you will have the option to upgrade again later and your data in those services will be preserved so you will be able to edit them again.</p><p>Nevertheless... IF you choose to continue with the downgrade that information will not be visible in your site afterwards!<br>Furthermore be aware that the downgrade will take effect immediately but payment will NOT change until next time payment is due!</p><p>Please click <b>Cancel</b> to avoid downgrade or <b>Continue</b> if you want to proceed with downgrade...</p>'}
      <div class="z-buttons z-formbuttons">
       
       <button onClick="return downgradeBundle();" id="crop_save"  type="button"  name="action" value="Continue" title="{gt text='Continue'}">
             {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Continue' __title='Continue'}
             {gt text='Continue'}
       </button>
        <a id="cancelD" href="#"  title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
       </div>
       
     
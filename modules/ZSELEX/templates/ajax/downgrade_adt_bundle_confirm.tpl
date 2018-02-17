   <h1 style="color:red;">{gt text='ALERT!'}</h1>
        <p>{gt text='You are about to downgrade additional bundle'} <b>{$current_bundle_name}</b>.</p>
         {gt text='<p>Though we will not remove your data you will not be able to edit the data related to this bundle until you upgrade the bundle again.</p><p>This means that should you continue with downgrade you will have the option to buy again later and your data related to that bundle will be preserved so you will be able to edit them again.</p><p>Nevertheless... IF you choose to continue downgrade, that information will not be visible in your site afterwards!<br>Furthermore be aware that downgrade will take effect immediately!</p><p>Please click <b>Cancel</b> to avoid downgrade or <b>Continue</b> if you want to proceed with downgrade of bundle...'}
      <div class="z-buttons z-formbuttons">
       
       <button onClick="return downgradeAdditionalBundle();" id="crop_save"  type="button"  name="action" value="Continue" title="{gt text='Continue'}">
             {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Continue' __title='Continue'}
             {gt text='Continue'}
       </button>
        <a id="cancelD" href="#"  title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
       </div> <!-- -->
       
    
       
      
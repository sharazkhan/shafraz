
<div class="z-admin-content-pagetitle">
      <h3>{gt text='Set Demo'}</h3>
</div>
<form class="z-form" id="sid_form" name="sid_form" action="" method="post">
       <div>
          
            <input type="hidden" name="shop_id" id="shop_id" value="{$sids.0.shop_id}">
           {foreach from=$sids item='sid' key='index'}
               <input type="hidden" name="index[]" id="index" value="{$index}">
               <input type="hidden" name="sid[{$index}]" id="sid" value="{$sid.sid}">
               <input type="hidden" name="bundle_id[{$index}]" id="bundle_id" value="{$sid.bundle_id}">
            <div class="z-formrow">
                <label for="demo_period">{gt text='Bundle'}:</label>
                 <div><b>{$sid.bundle_name}</b></div>
            </div>
            <div class="z-formrow">
                <label for="demo_period">{gt text='Demo Period'}:</label>
                <input type="text" name="demo_period[{$index}]" value="{$sid.current_demo_days}">
            </div>
            {/foreach}

       
       <div class="z-buttons z-formbuttons">
            {button src="button_ok.png" set="icons/extrasmall" __alt="Reactivate" __title="Reactivate" __text="Reactivate" __name="reactivate_demo" __value="1"}
            <a href="javascript:closeWindow()"  title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
       </div>

      </div>
 </form>
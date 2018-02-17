
<div class="z-admin-content-pagetitle" align="center">
      <h3>{gt text='Edit Anouncement'}</h3>
</div>
 <form class="z-form" id="bannerdelete_form" action="{modurl modname='ZSELEX' type='admin' func='saveAnnouncement' shop_id=$smarty.request.shop_id}" method="post" enctype="application/x-www-form-urlencoded">
      <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
      <input type="hidden" name="source" value="shopsetting" />
      <input type="hidden" name="formElements[shop_id]" value="{$smarty.request.shop_id}" />
      <input type="hidden" name="action" value="deletebanner">
 </form>
<form class="z-form" id="banner_form" action="{modurl modname='ZSELEX' type='admin' func='saveAnnouncement' shop_id=$smarty.request.shop_id}" method="post" enctype="application/x-www-form-urlencoded">
      <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
      <input type="hidden" name="source" value="shopsetting" />
      <input type="hidden" name="formElements[shop_id]" value="{$smarty.request.shop_id}" />
      <div>
    
        <fieldset>
            <div class="z-formrow">
                <label for="elemtName">{gt text='Text'}</label>
                <textarea  name='formElements[text]' {*maxlength="40"*}>{$item.text}</textarea>
            </div>

            <div class="z-formrow">
                <label for="enddate">{gt text='Start Date'}</label>
                <input type='text' autocomplete="off"  name='formElements[start_date]' id='startdate'  class='startdate' value='{$item.start_date}' />

            </div>
                  
            <div class="z-formrow">
                <label for="enddate">{gt text='End Date'}</label>
                <input type='text' autocomplete="off"  name='formElements[end_date]' id='enddate'  class='enddate' value='{$item.end_date}' />

            </div>
                
         <div class="z-formrow">
            <label for="status">{gt text='Status'}</label>
            <select id="status" name="formElements[status]" />
             <option value="1" {if $item.status eq '1'} selected='selected' {/if}>{gt text='Active'}</option>
             <option value="0" {if $item.status eq '0'} selected='selected' {/if}>{gt text='InActive'}</option>
            </select>
        </div>

            
            <div class="z-buttons z-formbuttons">
                <button id="zselex_button_submit"  class="z-btgreen" type="submit" onclick="return validate_advertise();" name="action" value="saveannouncement" title="{gt text='Save Announcement'}">
                    {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save Announcement'}
                    {gt text='Save'}
                </button>
                 <a href="javascript:closeWindow()"  title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
               {* <a id="zselex_button_cancel" href="{modurl modname="ZSELEX" type="admin" func='viewadvertise' shop_id=$shop_id}" class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>*}
           
          <button onClick="return deleteBanner();" id="banner_delete"  type="button"  name="action" value="deletebanner" title="{gt text='Delete Banner'}">
             {img src='14_layer_deletelayer.png' modname='core' set='icons/extrasmall' __alt='Delete Banner' __title='Delete Banner'}
             {gt text='Delete Banner'}
          </button>
            </div>
             
             </fieldset>
            
    </div>
</form>
   

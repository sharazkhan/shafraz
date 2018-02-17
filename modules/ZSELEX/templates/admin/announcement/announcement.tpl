{pageaddvar name='javascript' value='jquery'}
<script src="modules/ZSELEX/javascript/jquerycalendar/jquery-ui.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />

{shopheader}

<div class="z-admin-content-pagetitle">
     <h3>{gt text='Announcement'}</h3>
</div>

<form class="z-form" action="" method="post" enctype="application/x-www-form-urlencoded">
      <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
      <div>
    
        <fieldset>
            <div class="z-formrow">
                <label for="elemtName">{gt text='Text'}</label>
                <textarea  name='formElements[text]' maxlength="40">{$item.text}</textarea>
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

            {if $error < 1}
                  {if !$expired AND !$servicedisable}
            <div class="z-buttons z-formbuttons">
                <button id="zselex_button_submit"  class="z-btgreen" type="submit" onclick="return validate_advertise();" name="action" value="1" title="{gt text='Save this region'}">
                    {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save this announcement' }
                    {gt text='Save'}
                </button>
               {* <a id="zselex_button_cancel" href="{modurl modname="ZSELEX" type="admin" func='viewadvertise' shop_id=$shop_id}" class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>*}
            </div>
              {/if}
                  {/if}
             </fieldset>
            
    </div>
</form>

{adminfooter}


{*<script src="modules/ZSELEX/javascript/jquerycalendar/jquery-ui.js"></script>*}
<script>
  jQuery(function() {
    jQuery( "#startdate" ).datepicker({ dateFormat: "yy-mm-dd" , firstDay: '1'});
    jQuery( "#enddate" ).datepicker({ dateFormat: "yy-mm-dd" , firstDay: '1'});
  
  });
  </script>
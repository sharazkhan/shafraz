{shopheader}

{pageaddvar name='javascript' value='modules/ZTEXT/javascript/dnd/ajaxupload-min.js'}
{pageaddvar name="stylesheet" value="modules/ZTEXT/style/dnd/classicTheme/style.css"}

{pageaddvar name="stylesheet" value="themes/CityPilot/style/shopsetting_dnd.css"}
{pageaddvar name="stylesheet" value="modules/ZTEXT/style/dnd/alter.css"}
<style>
   .ax-remove, .ax-upload-all, .ax-clear, .ax-legend 
{
    display:none;
}
    </style>
<div class="z-admin-content-pagetitle">
   	<h3>{gt text='Create Page'}</h3>
</div>

<form class="z-form" action="" method="post" enctype="multipart/form-data">
    <div>
      <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
      <input type="hidden" id="shop_id" name="formElements[shop_id]" value="{$shop_id}" />
      
    <fieldset>
        <legend>{gt text='Create Page'}</legend>
        <div class="z-formrow">
            <label for="type_name">{gt text='Title'}</label>
            <input type="text" id="type_name" name="formElements[headertext]" value="{$item.headertext|safetext}" />
        </div>
        <div class="z-formrow">
            <label for="phone">{gt text='Body Text'}({gt text='ID:'} ztext_bodytext)</label>
            <textarea id="ztext_bodytext" name="formElements[bodytext]">{$item.bodytext|safetext}</textarea>
        </div>
        <div class="z-formrow">
            <label for="typestatus">{gt text='Attach Image'}</label>
            <div>
                 <table class="options">
                  <tbody>
                      <tr>
                          <td>
                              <div id="drop_images"  class="SettingDiv">
                                  
                              </div>
                              <div class="SettingBottomOrange">
                                  {*<div class="OrageSettingTextL left">{gt text='Edit This Image'}</div>*}

                                  <div style="padding-right:124px;padding-top:21px" class="OrageSettingTextR right">{gt text='Drag and Drop Image'}</div>

                              </div>
                              <div id="minisite_images" style="width:500px" class="TopAddFile"></div>

                          </td>

                      </tr>
                  </tbody>
              </table> 
            </div>
        </div>
        <div class="z-formrow">
            <label for="typestatus">{gt text='Active'}</label>
            <select id="typestatus" name="formElements[active]" />
             <option value="1" {if $item.active eq '1'} selected='selected' {/if}>{gt text='Active'}</option>
                    <option value="0" {if $item.active eq '0'} selected='selected' {/if}>{gt text='InActive'}</option>
            </select>
        </div>
        <div class="z-formrow">
            <label for="typestatus">{gt text='Display on Front'}</label>
            <select id="typestatus" name="formElements[displayonfront]" />
             <option value="1" {if $item.displayonfront eq '1'} selected='selected' {/if}>{gt text='Yes'}</option>
                    <option value="0" {if $item.displayonfront eq '0'} selected='selected' {/if}>{gt text='No'}</option>
            </select>
        </div>
        <div class="z-formrow">
            <label for="link">{gt text='Link'}</label>
            <input type="text" id="link" name="formElements[link]" value="{$item.link|safetext}" />
        </div>
        <div class="z-formrow">
            <label for="sort_order">{gt text='Sort Order'}</label>
            <input type="text" id="email" name="formElements[sort_order]" value="{$item.sort_order|safetext}" />
        </div>
       
        
    </fieldset>
    <div class="z-buttons z-formbuttons">
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save" __name="formElements[action]" __value="save"}
        <a href="{modurl modname="ZTEXT" type="admin" func='viewPages' shop_id=$shop_id}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    </div>
</form>
       
{adminfooter}


<script type="text/javascript">
    jQuery('#minisite_images').ajaxupload({
        url: 'upload.php',
        remotePath: 'zselexdata/26/',
        maxFiles: 1,
        beforeUploadAll: function (files_arr) {
            alert('hellooo'); exit();
            //VALIDATE FORM HERE
            if (files_arr.length > 5)
                return false;
            else
                return true;

        }
    });
    </script>
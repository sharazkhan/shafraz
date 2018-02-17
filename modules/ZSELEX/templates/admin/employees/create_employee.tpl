
{shopheader}
<div class="z-admin-content-pagetitle">
 {if $item.emp_image neq ''}
        <h3>{gt text='Update Employee'}</h3>
        {else}
    	<h3>{gt text='Create Employee'}</h3>
        {/if}
</div>

<form class="z-form" action="" method="post" enctype="multipart/form-data">
    <div>
      <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
      <input type="hidden" id="shop_id" name="formElements[shop_id]" value="{$smarty.request.shop_id}" />
      <input type="hidden" id="existingImage" name="formElements[existingImage]" value="{$item.emp_image|safetext}" />
    <fieldset>
        <legend>{gt text='Create Employee'}</legend>
        <div class="z-formrow">
            <label for="type_name">{gt text='Employee Name'}</label>
            <input type="text" id="type_name" name="formElements[name]" value="{$item.name|safetext}" />
        </div>
        <div class="z-formrow">
            <label for="phone">{gt text='Phone'}</label>
            <input type="text" id="phone" name="formElements[phone]" value="{$item.phone|safetext}" />
        </div>
        <div class="z-formrow">
            <label for="phone">{gt text='Cell'}</label>
            <input type="text" id="cell" name="formElements[cell]" value="{$item.cell|safetext}" />
        </div>
        <div class="z-formrow">
            <label for="phone">{gt text='Email'}</label>
            <input type="text" id="email" name="formElements[email]" value="{$item.email|safetext}" />
        </div>
        <div class="z-formrow">
            <label for="phone">{gt text='Job'}</label>
          
            <textarea id="job" name="formElements[job]" >{$item.job|safetext}</textarea>
        </div>
        <div class="z-formrow">
                <label for="empimage">{gt text='Employee Image'}</label>
                <input type="file" name="empimage" id="empimage">
        </div>
        {if $item.emp_id neq ''}
        <div class="z-formrow">
         <label for="simage"></label>
          <img src="{$baseurl}zselexdata/{$ownername}/employees/thumb/{$item.emp_image}"></img>
        </div>
        {/if}
        <div class="z-formrow">
            <label for="typestatus">{gt text='Status'}</label>
            <select id="typestatus" name="formElements[status]" />
             <option value="1" {if $item.status eq '1'} selected='selected' {/if}>{gt text='Active'}</option>
                    <option value="0" {if $item.status eq '0'} selected='selected' {/if}>{gt text='InActive'}</option>
            </select>
        </div>
    </fieldset>
    <div class="z-buttons z-formbuttons">
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save" __name="formElements[action]" __value="save"}
        <a href="{modurl modname="ZSELEX" type="admin" func='viewemployees' shop_id=$smarty.request.shop_id}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    </div>
</form>
{adminfooter}
{adminheader}
<div class="z-admin-content-pagetitle">
    <h3>{gt text='Edit Advertise'}</h3>
</div>
<form class="z-form" action="{modurl modname="ZSELEX" type="admin"  func="updateadvertise"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
    <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
    <input type="hidden" name="formElements[advertise_id]" value="{$aditem.advertise_id}" />
    <fieldset>
        <legend>{gt text='Modifiy Ad'}</legend>
        <div class="z-formrow">
            <label for="type_name">{gt text='Type name'}</label>
            <input type="text" id="type_name" value="{$aditem.name|safetext}" name="formElements[name]" />
        </div>
        <div class="z-formrow">
            <label for="description">{gt text='Description'}</label>
            <textarea id="description" name="formElements[description]" cols="70" rows="10" />{$aditem.description|safetext}</textarea>
        </div>

<div  class="z-formrow">
<label for="parentShop">{gt text='parent Shop'}</label>
			
                  <select name='formElements[parentShop]' id='parentShop'>
                  <option value='0'>{gt text='Select'}</option>
                 {foreach item='item' from=$zshops}
                <option value="{$item.shop_id}" {if $item.shop_id  eq  $aditem.shop_id} selected="selected"{/if}> {$item.shop_name} </option>
                   {/foreach}
              </select>
</div>
        <div class="z-formrow">
<label for="parentCity">{gt text='parent City'}</label>
			
                  <select name='formElements[parentCity]' id='parentCity'>
                  <option value='0'>{gt text='Select'}</option>
                 {foreach item='item' from=$zcities}
                <option value="{$item.city_id}" {if $item.city_id  eq  $aditem.city_id} selected="selected"{/if}> {$item.city_name} </option>
                   {/foreach}
              </select>
</div>
    </fieldset>
    <div class="z-buttons z-formbuttons">
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
        <a href="{modurl modname="ZSELEX" type="admin" func='viewadvertise'}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    </div>
</form>
{adminfooter}
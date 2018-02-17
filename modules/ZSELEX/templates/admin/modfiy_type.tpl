{adminheader}
<div class="z-admin-content-pagetitle">
    <h3>{gt text='Modifiy Type'}</h3>
</div>
<form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="updatetype"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
    <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
    <input type="hidden" name="typetable[type_id]" value="{$item.type_id}" />
    <fieldset>
        <legend>{gt text='Modifiy Type'}</legend>
        <div class="z-formrow">
            <label for="type_name">{gt text='Type name'}</label>
            <input type="text" id="type_name" value="{$item.type_name|safetext}" name="typetable[type_name]" />
        </div>
        <div class="z-formrow">
            <label for="typedescription">{gt text='Type description'}</label>
            <textarea id="typedescription" name="typetable[description]" cols="70" rows="10" />{$item.description|safetext}</textarea>
        </div>
        <div class="z-formrow">
            <label for="typestatus">{gt text='Type description'}</label>
            <select id="typestatus" name="typetable[status]" />
            <option value="1" {if $item.status eq 1} selected {/if}>{gt text='Active'}</option>
            <option value="0" {if $item.status eq 0} selected {/if}>{gt text='InActive'}</option>
            </select>
        </div>
    </fieldset>
    <div class="z-buttons z-formbuttons">
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
        <a href="{modurl modname="ZSELEX" type="admin" func='modifyconfig'}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    </div>
</form>
{adminfooter}
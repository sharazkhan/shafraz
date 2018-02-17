{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="config" size="small"}
    <h3>{gt text='Module settings'}</h3>
</div>

<form class="z-form" action="{modurl modname='ZMAP' type='admin' func='updateconfig'}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
    <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
    <fieldset>
        <legend>{gt text='General settings'}</legend>
        <div class="z-formrow">
			<label for="showAdminZMAP">{gt text='Display Admin ZMAP'}</label>
			<input type="checkbox" value="1" id="showAdminZMAP" name="showAdminZMAP"{if $modvars.ZMAP.showAdminZMAP eq true} checked="checked"{/if}/>
        </div>
    </fieldset>
    <div class="z-buttons z-formbuttons">
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
        <a href="{modurl modname='ZMAP' type='admin' func='modifyconfig'}" title="{gt text='Cancel'}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    </div>
</form>
{adminfooter}
{if $smarty.get.shop_id neq ''}
{shopheader}   
{else}
{adminheader}
{/if}
<div class="z-admin-content-pagetitle">
    {icon type="delete" size="small"}
    <h3>{gt text='Delete This Type'}</h3>
</div>

<p class="z-warningmsg">{gt text='Do you really want to delete this type?'}</p>

<form class="z-form" action="{modurl modname='ZSELEX' type='admin' func=$submitFunc}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <input type="hidden" name="confirmation" value="1" />
        <input type="hidden" name="{$IdName|safetext}" value="{$IdValue|safetext}" />
        <input type="hidden" name="shop_id" value="{$shop_id|safetext}" />
        <fieldset>
            <legend>{gt text='Confirmation prompt'}</legend>
            <div class="z-formbuttons z-buttons">
                {button class="z-btgreen" src=button_ok.png set=icons/extrasmall __alt="Delete" __title="Delete" __text="Delete"}
                <a class="z-btred" href="{modurl modname='ZSELEX' type='admin' func=$cancelFunc shop_id=$shop_id}">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel'  __title='Cancel'} {gt text="Cancel"}</a>
            </div>
        </fieldset>
    </div>
{notifydisplayhooks eventname='ZSELEX.ui_hooks.type.form_delete' id=$type_id}
</form>
{adminfooter}

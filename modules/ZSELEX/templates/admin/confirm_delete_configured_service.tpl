{shopheader}
<div class="z-admin-content-pagetitle">
    {icon type="delete" size="small"}
    <h3>{$confirm_title}</h3>
</div>

<p class="z-warningmsg">{$confirm_msg}</p>

<form class="z-form" action="{modurl modname='ZSELEX' type='admin' func=$submitFunc}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <input type="hidden" name="confirmation" value="1" />
        <input type="hidden" name="{$IdName|safetext}" value="{$IdValue|safetext}" />
        <input type="hidden" name="shop_id" value="{$shop_id|safetext}" />
        <fieldset>
            <legend>{gt text='Confirmation prompt'}</legend>
            <div align="center" class="z-formbuttons">
                <h1><font color="red">{$info.serviceIs}&nbsp;{gt text='Service'}</font></h1>
            </div>
            <div class="z-formbuttons">
                <b>{gt text='Note'} :</b>
            </div>
             <div align="center" class="z-formbuttons">
                <h3><font color="green">{$info.message}</font></h3>
            </div>
            <div class="z-formbuttons z-buttons">
                {button class="z-btgreen" src=button_ok.png set=icons/extrasmall __alt="Delete" __title="Delete" __text="Delete"}
                <a class="z-btred" href="{modurl modname='ZSELEX' type='admin' func=$cancelFunc shop_id=$shop_id}">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel'  __title='Cancel'} {gt text="Cancel"}</a>
            </div>
        </fieldset>
    </div>
{notifydisplayhooks eventname='ZSELEX.ui_hooks.type.form_delete' id=$type_id}
</form>
{adminfooter}

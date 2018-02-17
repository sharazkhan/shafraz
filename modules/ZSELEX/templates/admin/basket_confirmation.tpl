{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="confirm" size="small"}
    <h3>{gt text='Confirm This Basket'}</h3>
</div>

<p class="z-warningmsg">{gt text='Do you want to Confirm this Basket?'}</p>

<form class="z-form" action="{modurl modname='ZSELEX' type='admin' func='submitservices' shop_id=$smarty.request.shop_id}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <input type="hidden" name="confirmation" value="1" />
        <fieldset>
            <legend>{gt text='Confirmation prompt'}</legend>
            <div class="z-formbuttons z-buttons">
                {button class="z-btgreen" src=button_ok.png set=icons/extrasmall __alt="Confirm" __title="Confirm" __text="Confirm"}
                <a class="z-btred" href="
                   {if $smarty.request.shop_id neq ''}
                   {modurl modname='ZSELEX' type='admin' func='services' shop_id=$smarty.request.shop_id}
                   {else}
                   {modurl modname='ZSELEX' type='admin' func='viewbasket'}  
                       {/if}
                   ">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel'  __title='Cancel'} {gt text="Cancel"}</a>
            </div>
        </fieldset>
    </div>
{notifydisplayhooks eventname='ZSELEX.ui_hooks.type.form_delete' id=$type_id}
</form>
{adminfooter}

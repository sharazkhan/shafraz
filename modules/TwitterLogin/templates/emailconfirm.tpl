{insert name='getstatusmsg'}
<div class="z-admin-content-pagetitle">
     <h3>{$confirm_title}</h3>
</div>

<p class="z-warningmsg">{$confirm_msg}</p>

<form class="z-form" action="{modurl modname='TwitterLogin' type='user' func='afterConfirmingEmail'}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <input type="hidden" name="confirmation" value="1" />
      
        <input type="hidden" name="twitter_id" value="{$shop_id|safetext}" />
        <fieldset>
            <legend>{gt text='Complete Registration'}</legend>
             <div class="z-formrow">
            <label for="email">{gt text='Email'}</label>
            <input type="text" id="email" name="email" value="{$item.twitteremail}" />
        </div>
            <div class="z-formbuttons z-buttons">
                {button class="z-btgreen" src=button_ok.png set=icons/extrasmall __alt="Save" __title="Save" __text="Save"}
                <a class="z-btred" href="{modurl modname='Users' type='user' func='main'}">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel'  __title='Cancel'} {gt text="Cancel"}</a>
            </div>
        </fieldset>
    </div>

</form>


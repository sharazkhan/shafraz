{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text="Settings"}</h3>
</div>

    <p class="z-warningmsg">{gt text='To get google api keys you need to create app on google'}</p>
    <form id="twitter_config" class="z-form" action="{modurl modname="TwitterLogin" type="admin" func="updateconfig"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
            <fieldset>
                <legend>{gt text="Secrets"}</legend>
                <div class="z-formrow">
                    <label for="twitter_consumerkey">{gt text="Consumer Key"}</label>
                    <input id="twitter_consumerkey" name="consumerkey" type="text" value="{$modvars.TwitterLogin.consumerkey}" />
                </div>
                <div class="z-formrow">
                    <label for="twitter_secretkey">{gt text="Consumer Secret:"}</label>
                    <input id="twitter_consumersecret" name="consumersecret" type="text" value="{$modvars.TwitterLogin.consumersecret}" />
                </div>
                 <div class="z-formrow">
                    <label for="twitter_redirecturi">{gt text="Redirect Url:"}</label>
                    <input id="twitter_redirecturi" name="redirecturi" type="text" value="{$modvars.TwitterLogin.redirecturi}" />
                </div>
            </fieldset>
            <div class="z-formbuttons z-buttons">
                {button src='button_ok.png' set='icons/extrasmall' __alt='Save' __title='Save' __text='Save'}
                <a href="{modurl modname='google' type='admin' func='main'}" title="{gt text='Cancel'}">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
            </div>
        </div>
    </form>
{adminfooter}
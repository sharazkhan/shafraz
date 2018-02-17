
<!-- Row -->
<div class="row">
    <div class="col-md-12">
        <h2>Bruger log ind</h2>
        <p>Vælg hvordan du ønsker at logge ind ved at klikke på én af følgende...</p>
    </div>
    <div class="col-md-12">
        <div class="login-wrap clearfix">
            <div class="col-sm-5">
                <h3>Login with Username and Password</h3>
                <form  action="{modurl modname="Users" type="user" func="login"}" method="post">
                    <input id="users_login_selected_authentication_module" type="hidden" name="authentication_method[modname]" value="{$selected_authentication_method.modname|default:''}" />
                    <input id="users_login_selected_authentication_method" type="hidden" name="authentication_method[method]" value="{$selected_authentication_method.method|default:''}" />
                    <input id="users_login_returnpage" type="hidden" name="returnpage" value="{$returnpage|safetext}" />
                    <input id="users_login_csrftoken" type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
                    <input id="users_login_event_type" type="hidden" name="event_type" value="login_screen" />
                    {if ($modvars.ZConfig.seclevel|lower == 'high')}
                    <input id="users_login_rememberme" type="hidden" name="rememberme" value="0" />
                    {/if}
                    <div class="form-group">
                        <label for="exampleInputEmail1">User name</label>
                        <input type="text" class="form-control" id="users_login_login_id" placeholder="User name" name="authentication_info[login_id]">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" class="form-control" id="users_login_pass" placeholder="Password" name="authentication_info[pass]">
                    </div>
                    {if ($modvars.ZConfig.seclevel|lower != 'high')}
                    <div class="checkbox login-remember">
                        <input type="checkbox" id="check1"> 
                        <label for="check1">Keep me logged in on this computer</label>
                    </div>
                    {/if}
                    {if isset($user_obj) && !empty($user_obj)}
                    {notifyevent eventname='module.users.ui.form_edit.login_screen' id=$user_obj.uid eventsubject=$user_obj assign='eventData'}
                    {else}
                    {notifyevent eventname='module.users.ui.form_edit.login_screen' assign='eventData'}
                    {/if}

                    {foreach item='eventDisplay' from=$eventData}
                    {$eventDisplay}
                    {/foreach}

                    {if isset($user_obj) && !empty($user_obj)}
                    {notifydisplayhooks eventname='users.ui_hooks.login_block.form_edit' id=$user_obj.uid}
                    {else}
                    {notifydisplayhooks eventname='users.ui_hooks.login_block.form_edit' id=null}
                    {/if}
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
            </div>
            {if (count($authentication_method_display_order) > 1)}
            <div class="col-sm-2">
                <div class="or-separator">
                    <span>OR</span>
                </div>
            </div>
            <div class="col-sm-5 login-with-wrap">
                <div class="login-with">
                    {foreach from=$authentication_method_display_order item='authentication_method' name='authentication_method_display_order'}
                    {if $authentication_method.method eq 'Facebook'}
                    <a href="Facebook" class="login-facebook"><i class="fa fa-facebook"></i> <span>Login with Facebok Account</span></a>
                    {/if}
                    {if $authentication_method.method eq 'Google'}
                    <a href="Google" class="login-google"><i class="fa fa-google"></i> <span>Login with Google Account</span></a>
                    {/if}
                    {/foreach}
                </div>
            </div>
            {/if}
        </div>
        <div class="login-bottom clearfix">
            <div class="col-sm-7 forgot-info"><a href="#">Restore account information or password</a></div>
            <div class="col-sm-5 register-btn-wrap"><a href="#" class="btn btn-primary">Register</a></div>
        </div>
    </div>
</div>
<!-- End -->

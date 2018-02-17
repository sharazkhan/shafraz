{strip}
{ajaxheader modname='Users' filename='Zikula.Users.LoginBlock.js'}
{foreach from=$authentication_method_display_order item='authentication_method' name='authentication_method_display_order'}
{if ('Users' != $authentication_method.modname)}
{ajaxheader modname=$authentication_method.modname filename=$authentication_method.modname|cat:'.LoginBlock.js'}
{/if}
{/foreach}


 <script type="text/javascript">
  
</script>
{/strip}

<script>
function hides(){
//alert('hiii'); exit();

    document.getElementById('loginbutton').style.display='none';
    document.getElementById('loginblock').style.display='block';


}


function shows(){

    document.getElementById('loginblock').style.display='none';
    document.getElementById('loginbutton').style.display='block';

}
</script>



<div id="loginbutton" class="greetings1" style="cursor:pointer;" onClick="hides()"><b>{gt text="Log In"}</b></div>



<div id="loginblock" style="text-align:left">
    {strip}
    {assign var='show_login_form' value=false}
    {if (isset($selected_authentication_method) && $selected_authentication_method)}
    {login_form_fields form_type='loginblock' authentication_method=$selected_authentication_method assign='login_form_fields'}
    {if isset($login_form_fields) && $login_form_fields}
    {assign var='show_login_form' value=true}
    {/if}
    {/if}
    {/strip}
    <div id="users_loginblock_waiting" class="z-center z-hide">
        {img modname='core' set='ajax' src='indicator_circle.gif'}
    </div>
    <form id="users_loginblock_login_form" class="z-form z-linear{if !$show_login_form} z-hide{/if}" action="{modurl modname="Users" type="user" func="login"}" method="post">
       <div style="text-align:left">
        <input type="hidden" id="users_loginblock_returnpage" name="returnpage" value="{$returnpage}" />
        <input type="hidden" id="users_loginblock_csrftoken" name="csrftoken" value="{insert name='csrftoken'}" />
        <input id="users_login_event_type" type="hidden" name="event_type" value="login_block" />
        <input type="hidden" id="users_loginblock_selected_authentication_module" name="authentication_method[modname]" value="{if isset($selected_authentication_method) && $selected_authentication_method}{$selected_authentication_method.modname|default:'false'}{/if}" />
        <input type="hidden" id="users_loginblock_selected_authentication_method" name="authentication_method[method]" value="{if isset($selected_authentication_method) && $selected_authentication_method}{$selected_authentication_method.method|default:'false'}{/if}" />
        {if ($modvars.ZConfig.seclevel|lower == 'high')}
        <input id="users_loginblock_rememberme" type="hidden" name="rememberme" value="0" />
        {/if}


        {if !empty($login_form_fields)}
        <div><label  for="users_loginblock_login_id"><!--[gt text="User name" domain='zikula']--></label></div>
        <div> <input  id="users_loginblock_login_id" type="text" value="" name="authentication_info[login_id]" /></div>
        <div><label for="users_loginblock_pass"><!--[gt text="Password" domain='zikula']--></label></div>
        <div><input id="users_loginblock_pass" type="password" name="authentication_info[pass]" /></div>

        {/if}

        {if $modvars.ZConfig.seclevel|lower != 'high'}

        <input id="users_loginblock_rememberme" type="checkbox" name="rememberme" value="1" />
        <label for="users_loginblock_rememberme">{gt text="Remember me"}</label>
        {notifyevent eventname='module.users.ui.form_edit.login_block' assign="eventData"}
        {foreach item='eventDisplay' from=$eventData}
        {$eventDisplay}
        {/foreach}

        {notifydisplayhooks eventname='users.ui_hooks.login_block.form_edit' id=null}

        {/if}
        <div class="SubButtomDIv">
            <input class="z-bt-ok z-bt-small" id="users_loginblock_submit" name="users_loginblock_submit" type="submit" value="{gt text="Log in"}" />
         <span id="hide" style="cursor:pointer" onClick="shows()"><b>{gt text="hide"}</b></span>
        </div>
 
 <h5>{gt text="Do you need to..."}</h5>
{if $modvars.Users.reg_allowreg}


<ul class="UserOption">

    <li class="NewUser">  <a  style="display:block;" href="{modurl modname='Users' type='user' func='register'}">{gt text="New user"}</a> </li>

    <li class="ForgetPass"> <a  style="display:block;" href="{modurl modname='Users' type='user' func='lostpwduname'}">{gt text="Lost password?"}</a> </li>
</ul>
{/if}
</div>
</form>


</div>

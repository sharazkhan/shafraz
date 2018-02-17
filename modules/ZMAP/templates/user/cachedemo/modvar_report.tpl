<div id='zmapcachereport'>
<h3>{gt text='Cache-related modvars'}</h3>
<p class='z-sub'>{gt text='Settings from the Theme module'}</p>
<ul>
    <li{if !$modvars.Theme.render_cache} class='z-errormsg'{/if}>{gt text='enable render cache'}: <strong>{$modvars.Theme.render_cache|yesno}</strong></li>
    <li{if $modvars.Theme.render_lifetime <= 0} class='z-errormsg'{/if}>{gt text='render cache lifetime'}: <strong>{$modvars.Theme.render_lifetime} {gt text='seconds'}</strong></li>
    <li>{gt text='render compile check'}: <strong>{$modvars.Theme.render_compile_check|yesno}</strong></li>
    <li{if $modvars.Theme.render_force_compile} class='z-errormsg'{/if}>{gt text='render force compile'}: <strong>{$modvars.Theme.render_force_compile|yesno}</strong></li>
    <br />
    <li>{gt text='enable theme cache'}: <strong>{$modvars.Theme.enablecache|yesno}</strong></li>
    <li>{gt text='theme  lifetime'}: <strong>{$modvars.Theme.cache_lifetime} {gt text='seconds'}</strong></li>
    <li>{gt text='theme compile check'}: <strong>{$modvars.Theme.compile_check|yesno}</strong></li>
    <li>{gt text='theme force compile'}: <strong>{$modvars.Theme.force_compile|yesno}</strong></li>
</ul>
{if !$modvars.Theme.render_cache or $modvars.Theme.render_force_compile or ($modvars.Theme.render_lifetime <= 0)}
<div class='z-errormsg'>
    {gt text='The current settings may cause some parts<br />of this demo not to function as intended.'}
</div>
{/if}
<a href='{modurl modname='Theme' type='admin' func='modifyconfig'}'>{gt text='Change Theme settings'}</a>
</div>
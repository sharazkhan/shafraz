<h1>{gt text='ZMAP'}</h1>
{nocache}
{modulelinks modname='ZMAP' type='user'}
{/nocache}

<h2>{gt text='Cache Demo: Standard caching'}</h2>

{include file='user/cachedemo/modvar_report.tpl'}

<p><strong>{gt text='Possibly cached current timestamp'}:</strong> {$time} {gt text='seconds'}</p>
<p class='z-sub'>
{if $modvars.Theme.render_cache}
{gt text='Render cache is %s: this number should only advance when the cache lifetime expires. <br />This cache lifetime is set in the Theme module.' tag1='<strong>ENABLED</strong>'}
{else}
{gt text='Render cache is %s: this number should advance with every page refresh.' tag1='<strong>DISABLED</strong>'}
{/if}
</p>

{nocache}
<div>
    <p><strong>{gt text='Uncached current timestamp'}:</strong> {$time} {gt text='seconds'}</p>
    <p class='z-sub'>{gt text='This number should advance with every page refresh, irrespective of cache settings.'}</p>
</div>
{/nocache}

<div class='z-buttons' style='margin-top: 1em;'>
    <a href="javascript:location.reload(true)"><span class='z-icon-es-regenerate'>{gt text='Refresh page'}</span></a>
</div>
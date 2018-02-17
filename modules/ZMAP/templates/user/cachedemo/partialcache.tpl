<h1>{gt text='ZMAP'}</h1>
{nocache}
{modulelinks modname='ZMAP' type='user'}
{/nocache}

<h2>{gt text='Cache Demo: partial cache'}</h2>

{include file='user/cachedemo/modvar_report.tpl'}

<h4>{gt text='Always Cached'}</h4>
<p><strong>{gt text='Cached timestamp'}:</strong> {$time} {gt text='seconds'}</p>
<p class='z-sub'>{gt text='This number should only advance when the module-set cache lifetime expires (%s seconds).' tag1=$localcachelifetime}</p>

{nocache}
<h4>{gt text='Never Cached'}</h4>
<div>
    <p><strong>{gt text='Uncached current timestamp'}:</strong> {$time} {gt text='seconds'}</p>
    <p class='z-sub'>{gt text='This number should advance with every page refresh, irrespective of cache settings.'}</p>
</div>
{/nocache}

<div class='z-buttons' style='margin-top: 1em;'>
    <a href="javascript:location.reload(true)"><span class='z-icon-es-regenerate'>{gt text='Refresh page'}</span></a>
</div>
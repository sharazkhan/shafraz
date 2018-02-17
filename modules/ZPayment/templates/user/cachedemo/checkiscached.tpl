<h1>{gt text='ZPayment'}</h1>
{nocache}
{modulelinks modname='ZPayment' type='user'}
{/nocache}

<h2>{gt text='Cache Demo: is_cached'}</h2>

{include file='user/cachedemo/modvar_report.tpl'}

<h4>{gt text='Always Cached'}</h4>
<p><strong>{gt text='Cached timestamp'}:</strong> {$time} {gt text='seconds'}</p>
<p class='z-sub'>{gt text='This number should only advance when the module-set cache lifetime expires (%s seconds).' tag1=$localcachelifetime}</p>

<p>{gt text='This page will take a long time to load the first time, but on repeated reloads it will load faster due to a check for is_cached(). When the cache is regenerated, it takes an additional 5 seconds.'}</p>

<div class='z-buttons' style='margin-top: 1em;'>
    <a href="javascript:location.reload(true)"><span class='z-icon-es-regenerate'>{gt text='Refresh page'}</span></a>
</div>
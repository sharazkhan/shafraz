<h1>{gt text='ZSELEX'}</h1>
{nocache}
{modulelinks modname='ZSELEX' type='user'}
{/nocache}
<h2>{gt text='Cache Demo: Never cached'}</h2>

{include file='user/cachedemo/modvar_report.tpl'}

<p><strong>{gt text='Current timestamp'}:</strong> {$time} {gt text='seconds'}</p>
<p class='z-sub'>{gt text='This number should advance with every page refresh, irrespective of cache settings.'}</p>

<div class='z-buttons' style='margin-top: 1em;'>
    <a href="javascript:location.reload(true)"><span class='z-icon-es-regenerate'>{gt text='Refresh page'}</span></a>
</div>
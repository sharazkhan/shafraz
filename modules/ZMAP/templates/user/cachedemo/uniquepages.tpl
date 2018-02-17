<h1>{gt text='ZMAP'}</h1>
{nocache}
{modulelinks modname='ZMAP' type='user'}
{/nocache}

<h2>{gt text='Cache Demo: Multiple page caching'}</h2>

{include file='user/cachedemo/modvar_report.tpl'}

<div style='margin-left: 1em; width:50%;'>{insert name="getstatusmsg"}</div>
{* NOTE: when using caching, do not use an 'insert' inside a 'nocache' since the are essentially the same idea.
    inserting one inside the other will produce errors. *}
{nocache}
<form action='{modurl modname='ZMAP' type='user' func='uniquepages'}' method="post" enctype="application/x-www-form-urlencoded">
    <div style='border: 1px solid #AAAAAA; width:50%; margin: 1em; padding: 1em;'>
        <h5>{gt text='Choose page number'}</h5>
        {gt text='Enter integer 1 - 9'}: <input type='text' length='1' maxlength='1' name='page' value='{$page}' />
        <div class="z-buttonrow z-buttons" style='margin-top: .5em;'>
            <button type="submit" class="z-btgreen" name="submit" value="0" title="{gt text='Submit'}">{img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Submit' __title='Submit' } {gt text='Submit'}</button>
            <button type="submit" name="submit" value="-100" title="{gt text='Submit'}">{img src='cache.png' modname='core' set='icons/extrasmall' __alt='Reset template' __title='Reset template' } {gt text='Reset template'}</button>
            <button type="submit" name="submit" value="-200" title="{gt text='Submit'}">{img src='cache.png' modname='core' set='icons/extrasmall' __alt='Reset all templates' __title='Reset all templates' } {gt text='Reset all templates'}</button>
        </div>
    </div>
</form>
{/nocache}

<p id='pagenumber'>{gt text='Current page displayed'}: <span>{$page}</span></p>

<p><strong>{gt text='Cached timestamp'}:</strong> {$time} {gt text='seconds'}</p>
<p class='z-sub'>{gt text='This number should only advance when the module-set cache lifetime expires (current setting: %s seconds).' tag1=$localcachelifetime}</p>

{nocache}
<div>
    <p><strong>{gt text='Uncached current timestamp'}:</strong> {$time} {gt text='seconds'}</p>
    <p class='z-sub'>{gt text='This number should advance with every page refresh, irrespective of cache settings.'}</p>
</div>
<p>{gt text='cache id'}: <strong>{$cacheid|safetext}</strong></p>
{/nocache}

<ul class='hw-statusmsg'>
    <li>{gt text="Resubmit the form multiple times with the same and different values to see the effects of caching on the timestamps."}</li>
    <li>{gt text="Click <strong>reset template</strong> to clear the cache for this page only."}</li>
    <li>{gt text="Click <strong>reset all templates</strong> to clear the cache for all pages using this template."}</li>
</ul>

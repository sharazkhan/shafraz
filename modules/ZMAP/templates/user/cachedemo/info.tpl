<h1>{gt text='ZMAP'}</h1>
{nocache}
{modulelinks modname='ZMAP' type='user'}
{/nocache}

<h2>{gt text='Cache Demo: Information about caching'}</h2>

{include file='user/cachedemo/modvar_report.tpl'}

<h4>{gt text='Demonstrations'}</h4>
<p>
    {gt text="See the menu above (Cache demo) and its sub-items for demonstrations of caching techniques. Be sure to see the PHP and template code to understand how each is achieved."}
</p>
<h4>{gt text='Render Caching'}</h4>
<p>
    {gt text="Caching is a technology which speeds up pageloads by storing a version of a page in the filesystem and loads that page (for a set time period or 'lifetime') instead of reloading all the information from the database. Cached files are stored in %s and are specific to module, cacheId and template." tag1=$cachedir}
</p>
<p><strong>{gt text='Enable render cache'}</strong> -> {gt text='Checkbox (enable/disable)'}<br />
    {gt text="Render caching handles all <strong>module templates</strong>. It does not handle <strong>theme templates</strong>, but the concept is essentially the same for both. When enabled, Zikula will automatically cache templates for the length of time set in 'render cache lifetime'. This means that any data in the module template that is changed will not display as changed until the cache expires. This functionality is not dependant on the module code. The template will be cached automatically, although this behavior can be overridden with module code (see various demos)."}
</p>
<p><strong>{gt text='Render cache lifetime'}</strong> -> {gt text='Integer (default 3600)'}<br />
    {gt text="The render cache lifetime is the number of seconds that the cached template will 'live' or be displayed before being checked for new data. Entering a zero (0) for this value is essentially disabling caching. Entering negative one (-1) will set the cache output to never expire."}
</p>
<h4>{gt text='Render Compilation'}</h4>
<p>
    {gt text="Render compiling is done for all templates, one cannot disable it. Compiling basically converts the special template syntax into PHP and makes it understandable to the PHP rendering engine. Compiled files are stored in %s and are specific to language, theme and module." tag1=$compiledir}
</p>
<p><strong>{gt text='Render compile check'}</strong> -> {gt text='Checkbox (enable/disable)'}<br />
    {gt text="Render compilation is a check to see if the template has changed since its last display. When enabled, Zikula will automatically check to see if templates have changed since their last display and if so, display them even if the cache lifetime has not expired, thus resetting the cache lifetime. If the template has not changed, the cached template is displayed if the lifetime has not expired."}
</p>
<p><strong>{gt text='Render force compile'}</strong> -> {gt text='Checkbox (enable/disable)'}<br />
    {gt text="Enabling force compile will regenerate all cached templates with each page refresh. This essentially disables caching, but allows the mechanism of caching to function. This is essentially only useful for debugging purposes."}
</p>
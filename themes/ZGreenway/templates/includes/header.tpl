<div id="top">

<div id="date">

{if $loggedin eq true}
	<strong>{userwelcome|ucwords}</strong>&nbsp;|&nbsp;
	<a href="{pnmodurl modname=Users}" title="{gt text="My Account Settings"}">{gt text="My Account"}</a>&nbsp;|&nbsp;
	<a href="{pnmodurl modname=Users func=logout}" title="{gt text="Logout of Your Account"}" class="last">{gt text="Logout"}</a>&nbsp;|&nbsp;
	{else}

	<strong><em>{userwelcome|ucwords}</em></strong>&nbsp;|&nbsp;<a href="{pnmodurl modname=Users func=loginscreen}" title="{gt text="Log In to Your Account"}" class="last">{gt text="Log In"}</a>&nbsp;|&nbsp;
	{/if}

{$datetime}{if $pagetype neq 'home'}&nbsp;::&nbsp;<a href="{homepage}">{gt text="Back to Main page"}</a>{/if}

</div>



<div id="icons">
<a href="{homepage}" title="{gt text="Home"}"><img src="{$imagepath}/home.gif" alt="{gt text="Home"}" /></a>
<a href="{pnmodurl modname=Sitemap}" title="{gt text="SiteMap"}"><img src="{$imagepath}/sitemap.gif" alt="{gt text="SiteMap"}" /></a>
</div>


</div>

<div id="masthead">
	<span id="sitename">{sitename}</span><br />
	<span id="slogan">{slogan}</span>
</div>

	<div id="menu">
		<ul>
		<li {if $pagetype eq 'home'} class="current" {/if}><a href="{homepage}"><span>{gt text="Home"}</span></a></li>
		<li {if $module eq 'News'} class="current" {/if}><a href="{pnmodurl modname=News}"><span>{gt text="News"}</span></a></li>
		</ul>
	</div>
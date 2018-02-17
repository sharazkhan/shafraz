	<div id="banner">
		<h4>

{if $loggedin eq true}
	<span id="login"><strong>{userwelcome|ucwords}</strong>&nbsp;|&nbsp;<a href="{pnmodurl modname=Users}" title="{gt text="My Account Settings"}">{gt text="My Account"}</a>&nbsp;|&nbsp;<a href="{pnmodurl modname=Users func=logout}" title="{gt text="Logout of Your Account"}" class="last">{gt text="Logout"}</a>&nbsp;

	{else}

	<span id="logout"><strong><em>{userwelcome|ucwords}</em></strong>&nbsp;|&nbsp;<a href="{pnmodurl modname=Users func=loginscreen}" title="{gt text="Log In to Your Account"}" class="last">{gt text="Log In"}</a>&nbsp;;
	{/if}

</span>

<span id="snacktime">{if $pagetype neq 'home'}<a href="{pngetbaseurl}">{gt text="Back to Main page"}</a>&nbsp;::&nbsp;{/if}{datetime format='%b %d, %Y - %I:%M %p'}</span>

		</h4>
		<h1 id="sitename"><a href="{homepage}" title="{gt text="Home"}">{sitename}</a></h1>

	</div>
	<div id="page_header">
		<h1>{sitename}</h1>	
		<h2>{slogan}</h2> 
	</div>

	<div id="menu_bar">
		<ul>
		<li {if $pagetype eq 'home'} class="current" {/if}><a href="{homepage}"><span>{gt text="Home"}</span></a></li>
		<li {if $module eq 'News'} class="current" {/if}><a href="{pnmodurl modname=News}"><span>{gt text="News"}</span></a></li>
		</ul>
	</div>
<div id="topbar">
	<div id="topbarleft"> </div>
	<div id="topbarright">
	<ul>
	<li {if $pagetype eq 'home'} class="current" {/if}><a href="{homepage}">{gt text="Home"}</a></li>
	<li {if $module eq 'News'} class="current" {/if}><a href="{pnmodurl modname=News}">{gt text="News"}</a></li>
	</ul>
	</div>

	<h1 id="sitename"><a href="{homepage}" title="{gt text="Home"}">{sitename}</a></h1>
	<h2 id="slogan">{slogan}</h2>

	<div id="searchform">
	{search class=search search_class=textbox button_class=button button=Search}
	</div>

</div>

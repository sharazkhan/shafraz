<div id="menu">
	<ul>
<li {if $pagetype eq 'home'} class="current" {/if}><a href="{homepage}" title="{gt text="Home"}">{gt text="Home"}</a></li>
<li {if $module eq 'News'} class="current" {/if}><a href="{pnmodurl modname=News}" title="{gt text="News"}">{gt text="News"}</a></li>
<li {if $module eq 'Sitemap'} class="current" {/if}><a href="{pnmodurl modname=Sitemap}" title="{gt text="Sitemap"}">{gt text="Sitemap"}</a></li>
	</ul>
</div>
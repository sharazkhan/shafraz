{include file='includes/header.tpl'}
  <div align="center" >
            <div>
                <img src="themes/CityPilot/images/Banner.png" width="100%"/>
            </div>
    </div>
<div id="theme_navigation_bar" class="z-clearfix">
   
    {blockposition name=topnav assign=topnavblock}
    {if empty($topnavblock)}
    <ul class="z-floatleft">
        <li><a href="{homepage}" title="{gt text="Go to the site's home page"}">{gt text='Home'}</a></li>
        <li><a href="{modurl modname='Users' type='user' func='main'}" title="{gt text='Go to your account panel'}">{gt text="My Account"}</a></li>
        <li><a href="{modurl modname='Search' type='user' func='main'}" title="{gt text='Search this site'}">{gt text="Site search"}</a></li>
    </ul>
    {else}
    {$topnavblock}
    {/if}
    {blockposition name=search}
</div>
{include file="body/3col_w_centerblock.tpl"}
{include file='includes/footer.tpl'}

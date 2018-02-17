<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{lang}" dir="{langdirection}">
    <head>
         {include file="includes/adminhead.tpl"}
        <!--<link rel="stylesheet" type="text/css" href="{$stylepath}/style.css" media="print,projection,screen" />-->
        {*<link rel="stylesheet" type="text/css" href="{$stylepath}/admin.css" media="print,projection,screen" />*}
        {pageaddvar name="stylesheet" value="themes/CityPilot/style/admin.css"}
        <!--<link rel="stylesheet" type="text/css" href="{$stylepath}/print.css" media="print" />-->
        {pageaddvar name='javascript' value='jquery'}
        {pageaddvar name='javascript' value='jquery.ui'}
        {pageaddvar name='javascript' value='zikula.ui'}
        {pageaddvar name="stylesheet" value="themes/CityPilot/style/shop_services.css"}
    </head>
    
    <body>
        <div id="theme_page_container">
            {include file="includes/adminheader.tpl"}
            <div id="theme_content">
                <div class="inner">
                    <div class="Admin_Left left">
                        {insert name='getstatusmsg'}
                        {$maincontent}
                    </div>
                    <div class="Admin_Right left">
                        {blockposition name='backendmsg-right' assign=backendmsgblock}
                        {if !empty($backendmsgblock)}
                        <div class="admin_right_top">
                            {$backendmsgblock}
                        </div>
                        {/if}

                        {blockposition name='backendguide-right' assign=backendguideblock}
                        {if !empty($backendguideblock)}
                        <div class="admin_right_top">
                            {$backendguideblock}
                        </div>
                        {/if}

                        {blockposition name='backendstats-right' assign=backendstatsblock}
                        {if !empty($backendstatsblock)}
                        <div class="admin_right_top">
                            {$backendstatsblock}
                        </div>
                        {/if}
{*
<!--h4 class="Graph"> {$shop_info.shop_name} {if $shop_info.city_name neq ''}, {$shop_info.city_name} {/if}</h4>
<p class="admin_right_p">
{gt text='I have had'}:<br />
<span class="orange_text">84</span> {gt text='new visitors lately'} <span class="orange_text">1250</span> {gt text='visitors in total'} <br /><br />
<span class="orange_text">3</span> {gt text="rated your page"} <span class="orange_text">16</span> {gt text="has laid a comment"}<br /><br />
{gt text="I've sold"}:<br /><span class="orange_text">19</span> {gt text="Products in"} July
<span class="orange_text">201</span>  {gt text="products in total"}
</p-->
*}
						{if $smarty.request.func neq 'shopsummary'}
							{blockposition name='yourservices'}   
						{/if}
                    </div>

                </div>
            </div>
                     {include file="includes/feedback.tpl"} 
            <div id="CityPilotFotter">
                {blockposition name='citypilotfooter'}   
            </div>
        </div>
    </body>
</html>

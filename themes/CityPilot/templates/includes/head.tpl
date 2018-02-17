<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
{fileversion}
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
{*<meta http-equiv="refresh" content="5"/>*}
<title>{pagegetvar name='title'}</title>
<!--<meta name="viewport" content="width=device-width,initial-scale=1">-->
<meta name="description" content="{$metatags.description}" />
<meta name="keywords" content="{$metatags.keywords}" />

<link rel="shortcut icon" href="{$imagepath}/favicon.ico">
<link rel="apple-touch-icon" sizes="57x57" href="{$imagepath}/apple-touch-icon-57x57.png">
<link rel="apple-touch-icon" sizes="114x114" href="{$imagepath}/apple-touch-icon-114x114.png">
<link rel="apple-touch-icon" sizes="72x72" href="{$imagepath}/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="144x144" href="{$imagepath}/apple-touch-icon-144x144.png">
<link rel="apple-touch-icon" sizes="60x60" href="{$imagepath}/apple-touch-icon-60x60.png">
<link rel="apple-touch-icon" sizes="120x120" href="{$imagepath}/apple-touch-icon-120x120.png">
<link rel="apple-touch-icon" sizes="76x76" href="{$imagepath}/apple-touch-icon-76x76.png">
<link rel="apple-touch-icon" sizes="152x152" href="{$imagepath}/apple-touch-icon-152x152.png">
<link rel="icon" type="image/png" href="{$imagepath}/favicon-196x196.png" sizes="196x196">
<link rel="icon" type="image/png" href="{$imagepath}/favicon-160x160.png" sizes="160x160">
<link rel="icon" type="image/png" href="{$imagepath}/favicon-96x96.png" sizes="96x96">
<link rel="icon" type="image/png" href="{$imagepath}/favicon-16x16.png" sizes="16x16">
<link rel="icon" type="image/png" href="{$imagepath}/favicon-32x32.png" sizes="32x32">
<meta name="msapplication-TileColor" content="#da532c">
<meta name="msapplication-TileImage" content="{$imagepath}/mstile-144x144.png">
<meta name="msapplication-config" content="{$imagepath}/browserconfig.xml">

{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='zikula.ui'}
{pageaddvar name="jsgettext" value="module_zselex_js:ZSELEX"}
{*{pageaddvar name='javascript' value='modules/ZSELEX/javascript/jquerylazy/jquery.lazy.min.js'}*}
{pageaddvar name='javascript' value="modules/ZSELEX/javascript/lazyload/lazyload.js$ver"}
</head>

<script type="text/javascript">
     jQuery(document).ready(function(){
     //jQuery('#pageload').val('1');
     if ( typeof displayBlocks == 'function' ) { 
   // displayBlocks(); 
      }
        jQuery(window).scroll(function(){
            if (jQuery(this).scrollTop() > 100) {
                jQuery('.scrollup').fadeIn();
            } else {
                jQuery('.scrollup').fadeOut();
            }
        }); 
     jQuery('.scrollup').click(function(){
            jQuery("html, body").animate({ scrollTop: 0 }, 600);
            return false;
        });
 
    });
 
 document.observe("dom:loaded", function(){
  //alert('The DOM is loaded!');
  //displayBlocks();
});

window.onload=function(){
jQuery('#pageload').val('0');
 searchBreadcrums();
};

</script>
{pageaddvar name='javascript' value="modules/ZSELEX/javascript/searchbreadcrum.js$ver"} 

{include file="includes/feedback.tpl"}

{include file="includes/analytics/tracking.php"}

<!-- MOBILE NAVIGATION -->
{*<link rel="stylesheet" href="themes/CityPilot/style/smart-navigation.css{$ver}" type="text/css" />
<script>
jQuery(document).ready(function(){
jQuery(".wsite-nav-button").click(function () {
jQuery(".wsite-nav-button,.TopMenu,.smart-shop-menu").toggleClass("open");
});
});
</script>*}
<link rel="stylesheet" href="themes/CityPilot/style/smart-navigation.css{$ver}" type="text/css" />
<script>
jQuery(document).ready(function(){
  jQuery(".wsite-nav-button").click(function () {
  jQuery(".wsite-nav-button,.TopMenu,.smart-shop-menu").toggleClass("open");
  });
  jQuery(".wsite-nav-button2").click(function () {
  jQuery(".wsite-nav-button2,.minisitemenu_block").toggleClass("open");
  });
});
</script>
<!-- MOBILE NAVIGATION END-->


<a href="#" class="scrollup">{gt text='Scroll'}</a>

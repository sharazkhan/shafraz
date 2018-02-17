
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>{pagegetvar name='title'}</title>

    <!-- favicon (favicon-generator.org)-->
    <link rel="apple-touch-icon" sizes="57x57" href="{$themepath}/images/favicons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="{$themepath}/images/favicons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="{$themepath}/images/favicons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="{$themepath}/images/favicons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="{$themepath}/images/favicons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="{$themepath}/images/favicons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="{$themepath}/images/favicons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="{$themepath}/images/favicons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="{$themepath}/images/favicons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="{$themepath}/images/favicons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{$themepath}/images/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="{$themepath}/images/favicons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{$themepath}/images/favicons/favicon-16x16.png">
    <link rel="manifest" href="{$themepath}/images/favicons/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{$themepath}/images/favicons/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- favicon end -->

    <!-- Bootstrap -->
    <link href="{$themepath}/style/bootstrap.min.css" rel="stylesheet">
    <link href="{$themepath}/style/font-awesome.min.css" rel="stylesheet">
    <link href="{$themepath}/style/jquery.bxslider.css" rel="stylesheet">
    <link href="{$themepath}/style/jquery.fancybox.css" rel="stylesheet">
    <link href="{$themepath}/style/chosen.css" rel="stylesheet">
   {* <link href="{$themepath}/style/style.css" rel="stylesheet">
    <link href="{$themepath}/style/responsive.css" rel="stylesheet">*}
    
    {*{pageaddvar name='javascript' value='jquery'}*}
    {*{pageaddvar name='javascript' value="$themepath/js/jquery.min.js"}*}
    <script type="text/javascript" src="{$themepath}/js/jquery.min.js"></script>
    {pageaddvar name='javascript' value='zikula.ui'}
    {pageaddvar name="jsgettext" value="module_zselex_js:ZSELEX"}
    
    


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    {browsersupport}
  </head>
  {pageaddvar name='javascript' value="$themepath/javascript/searchbreadcrum.js"} 
  {*{include file="includes/feedback.tpl"}*}

  {include file="includes/analytics/tracking.php"}
  <input type="hidden" value="CityPilotresponsive" id="curent_theme">
  
  <script>
      window.onload=function(){
jQuery('#pageload').val('0');
 searchBreadcrums();
};
      </script>
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
    </head>
      
    <body>
        
        <div id="theme_page_container">
            {include file="includes/feedback.tpl"} 
            {include file="includes/adminheader.tpl"}
            
            <div id="theme_content">
                <div class="inner">
                    {$maincontent}
                </div>
            </div>
                 
            <div id="CityPilotFotter">
                {blockposition name='citypilotfooter'}   
            </div>
        </div>
    </body>
</html>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{lang}" dir="{langdirection}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset={charset}" />
        <title>{title}</title>
        <meta name="description" content="{slogan}" />
        <meta name="keywords" content="{keywords}" />
        <meta http-equiv="X-UA-Compatible" content="chrome=1" />
        <link rel="stylesheet" type="text/css" href="{$stylepath}/style.css" media="print,projection,screen" />
        <link rel="stylesheet" type="text/css" href="{$stylepath}/print.css" media="print" />
           <link rel="stylesheet" type="text/css" href="{$stylepath}/styleR2.css"/>
           <style>
           #theme_page_container{padding:0px; padding-bottom:0px;}
           
	   .Header{display:table;}
	   #theme_content{ display:table;}
        
          .footer{display:table;  }
           </style>
    </head>
           
    <body style="background: white;">
      &nbsp;
        <div id="theme_page_container">

             {include file="includes/header.tpl"}


            <div id="theme_content" style="width:95%">
               {$maincontent}
            </div>

             {include file="includes/footer.tpl"}

        </div>
    </body>
</html>

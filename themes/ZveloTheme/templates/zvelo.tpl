<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{lang}" dir="{langdirection}">
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset={charset}" />
        <title>{pagegetvar name='title'}</title>
        <meta name="description" content="{$metatags.description}" />
        <meta name="keywords" content="{$metatags.keywords}" />
        <meta http-equiv="X-UA-Compatible" content="chrome=1" />
        {pageaddvar name="stylesheet" value="$stylepath/style2.css"}
        {pageaddvar name="stylesheet" value="$stylepath/common.css"}
        {pageaddvar name='javascript' value='jquery'}
        {pageaddvar name='javascript' value='zikula.ui'}
        {pageaddvar name="jsgettext" value="module_zselex_js:ZSELEX"}
    </head>
	<body>
		<div id="background">
			<div id="BG"><img src="{$themepath}/images/BG.png"></div>
			<div id="Both120"><img src="{$themepath}/images/Both120.png"></div>
			<div id="Rightw150">
                            <img src="{$themepath}/images/Rightw150.png">
                                  <div class="RightContent">
                                  {blockposition name='zvelo-right'}
                                  </div>
                        </div>
			<div id="Leftw220"><img src="{$themepath}/images/Leftw220.png"></div>
			<div id="Toph120"><img src="{$themepath}/images/Toph120.png"></div>
			
                        {$maincontent}
			
                        <div id="ZVelo"><img src="{$themepath}/images/ZVelo.png"></div>
			<div id="version100"><img src="{$themepath}/images/version100.png"></div>
			  <div class="LeftContent">
                 {blockposition name='zvelo-left'}
                           </div>
		</div>
 </body>
 </html>
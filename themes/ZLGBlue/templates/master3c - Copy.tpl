
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{lang}" lang="{lang}" dir="{langdirection}">
<head>
{*{include file="includes/head.tpl"}*}
<title>{title}</title>
<meta http-equiv="Content-Type" content="text/html; charset={charset}" />
<meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1" />
<meta name="Author" content="{sitename}" />
<meta name="description" content="{slogan}" />
<meta name="keywords" content="{keywords}" />
<meta name="Copyright" content="Copyright (c) {'Y'|date} by {sitename}" />
<meta name="Robots" content="index,follow" />
</head>
{*{include file="includes/citypilotheader.tpl"}*}
<link rel="icon" type="image/png" href="{$imagepath}/favicon.png" />
<link rel="icon" type="image/x-icon" href="{$imagepath}/favicon.ico" />{* W3C *}
<link rel="shortcut icon" type="image/ico" href="{$imagepath}/favicon.ico" />{* IE *}
<link rel="alternate" href="{pnmodurl modname='News' type='user' func='view' theme='rss'}" type="application/rss+xml" title="{sitename} Main Feed" />
<link rel="stylesheet" href="{$stylepath}/style.css" type="text/css" media="screen,projection" />
<body>
<div class="content">
 {include file="includes/header.tpl"} 
 	<div id="main">
		<div class="right_side">
			{blockposition name=right}
		</div>
		<div class="center">
			{if $pagetype eq 'home'}
			{blockposition name=center}
			{/if}
			{$maincontent}
          {blockposition name=gallery}
		</div>
		<div class="leftmenu">
			{blockposition name=left}
	 {blockposition name='ZSELEX-minisite-products'}
		</div>
	</div>
	<br />&nbsp;<br />
 {*{include file="includes/footer.tpl"}
 
</div>
</body>
</html>
 {include file="includes/citypilotfooter.tpl"} *}
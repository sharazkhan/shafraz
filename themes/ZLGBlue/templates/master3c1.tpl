<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{lang}" lang="{lang}" dir="{langdirection}">
<head>
{include file="includes/head.tpl"}
</head>
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
{include file="includes/footer.tpl"}
</div>
</body>
</html>
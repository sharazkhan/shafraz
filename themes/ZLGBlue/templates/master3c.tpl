<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{lang}" lang="{lang}" dir="{langdirection}">
<head>
{include file="includes/head.tpl"}
</head>


<body>
   <span id="CityPilotHeader">
{blockposition name='citypilotheader'}
   </span>
<div class="content">
     {*<div align="center">
            <div>
                <img src="themes/CityPilot/images/Banner.png" width="100%"/>
            </div>
    </div>*}
{* {include file="includes/header.tpl"} *}
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
 {*{include file="includes/footer.tpl"}*}
 
</div>
 {include file="includes/citypilotfooter.tpl"}
</body>
</html>
 
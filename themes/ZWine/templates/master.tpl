<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{lang}" lang="{lang}" dir="{langdirection}"-->
<head>
{include file="includes/head.tpl"}
</head>
<body>

<div id="container">
{include file="includes/header.tpl"}

    {if $pagetype neq 'admin'}
		<div id="sidebar">
			{blockposition name=left}
			{blockposition name=right}
			{blockposition name='ZSELEX-minisite-products'}
		</div>
    {/if}

		<div id="content"{if $pagetype eq 'admin'} style="width:100%"{/if}>
			{if $pagetype eq 'home'}
			{blockposition name=center}
			{/if}
			{$maincontent}
                          {blockposition name='minisite-left'}
          {blockposition name=gallery}
		</div>


	<div style="clear:both;"></div>


	<div class="intro">
		{blockposition name=bottomleft}Bottom left block position
	</div>
	<div class="intro2">
		{blockposition name=bottomcenter}Bottom centre block position
	</div>
	<div class="intro3">
		{blockposition name=bottomright}Bottom right block position
	</div>


	<div style="clear:both;"></div>


{include file="includes/footer.tpl"}
</div>
</body>
</html>
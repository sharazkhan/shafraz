<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{lang}" lang="{lang}" dir="{langdirection}">
<head>
{include file="includes/head.tpl"}
</head>
<body>
<div id="page">

{include file="includes/header.tpl"}

<div id="wrapper">

	<div id="right_side">
		<div class="pad">
		<div id="pic"><img src="{$imagepath}/pic.jpg" alt="" /> </div>


		{blockposition name=left}
		{blockposition name=right}
	 {blockposition name='ZSELEX-minisite-products'}
		</div>
	</div>

	<div id="left_side">
	
			<div id="newsbox">{blockposition name=newsbox}</div>

			{if $pagetype eq 'home'}

			<div id="centerblock">{blockposition name=center}</div>
			{/if}
				{$maincontent}
		          {blockposition name=gallery}

	</div><!-- end left side -->
		
</div>
{include file="includes/footer.tpl"}
</div>

</body>
</html>
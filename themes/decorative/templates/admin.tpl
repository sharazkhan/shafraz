<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={charset}" />
<meta name="description" content="{slogan}" />
<meta name="robots" content="index,follow" />
<meta name="resource-type" content="document" />
<meta http-equiv="expires" content="0" />
<meta name="author" content="{sitename}" />
<meta name="copyright" content="Copyright (c) 2004 by {sitename}" />
<meta name="revisit-after" content="1 days" />
<meta name="distribution" content="Global" />
<meta name="rating" content="General" />
<meta name="KEYWORDS" content="{keywords}" />
<title>{title}</title>
{additional_header}
<link rel="stylesheet" type="text/css"  href="{$stylepath}/style.css" />
</head>
<body>
<!-- start header -->
<div id="header">
	<div id="logo">
		<h1><a href="index.php">{sitename}</a></h1>
		
	</div>
	<div id="search">
		<form method="get" action="">
			<fieldset>
			<input id="s" type="text" name="s" value="" class="text" />
			<input id="x" type="submit" value="Search" class="button" />
			</fieldset>
		</form>
	</div>
</div>
<!-- end header -->
<!-- start page -->
<div id="page">
	<!-- start content -->
	<div id="admin">
		
	{$maincontent}
	</div>
	<!-- end content -->
	<!-- start sidebar -->
	<div id="sidebar">
	<ul>
			<li>
			{blockposition name=left}
	</li>		</ul>
			
	</div>
	<!-- end sidebar -->
	<div style="clear: both;">&nbsp;</div>
</div>
<!-- end page -->
<!-- start footer -->
<div id="footer">
	<p>&copy;2007 All Rights Reserved. &nbsp;&bull;&nbsp; Design by <a href="http://www.freecsstemplates.org/">Free CSS Templates</a></p>
</div>
<!-- end footer -->
</body>
</html>

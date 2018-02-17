<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{lang}" lang="{lang}" dir="{langdirection}">
    <head>{include file="includes/head.tpl"}</head>
    <body class="threecols">
        {include file="includes/userheader.tpl"}
        <div id="pagewidth">
            works hereeee
            <div id="wrapper" class="z-clearfix">
                <div id="leftcol">
                    <div id="sidebar">
                        {blockposition name=left}
                           {blockposition name='ZSELEX-shop-service'}
                     </div>
                </div>
                <div id="maincol">
                    {$maincontent}
                     {blockposition name='gallery'}
                      {blockposition name=newslist-top}
                </div>
                <div id="rightcol" width="40px">
                     {blockposition name='ZSELEX-minisite-products'}
                   
                </div>
            </div>
        </div>
        {include file="includes/footer.tpl"}
    </body>
</html>

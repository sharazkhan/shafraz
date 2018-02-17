
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ZVelo</title>
 {pageaddvar name='javascript' value='jquery'}
 {pageaddvar name='javascript' value='zikula.ui'}
 {pageaddvar name="jsgettext" value="module_zselex_js:ZSELEX"}
 {pageaddvar name="stylesheet" value="$stylepath/styles.css"}
</head>
<script>
   
 
    function redirectHome(){
     var url = jQuery("#hurl").val();
     window.location.href=url;
     return true;
    }
</script>

 
<body>
<input type="hidden" id="hurl" value="{homepage}zvelo">
<div class="containter">
	
    <div class="inner">
          {insert name='getstatusmsg'}
    	  <div class="header">
                 <div class="logo" onClick="redirectHome()" style="cursor:pointer">
                 ZV<span class="char_space_sync">elo</span>
                 <span class="logo-version">
                     version {modfunc modname=zvelo type=user func=moduleVersion}
                 </span>
                 </div>
                 
                    
          </div>   
          <div class="content_sec">
          	<div class="content_left">
            	 {leftblock}
                </div>
            <div class="content_middle {if $smarty.request.func eq 'main' OR $smarty.request.func eq 'clientinfo' OR $smarty.request.module eq ''}''{else}no_bg{/if}">
            	
                {if $smarty.request.func eq '' || $smarty.request.module eq ''}
                {modfunc modname=zvelo type=user func=measurement}
                {else}
                {$maincontent}
                {/if}
                
                {* {$maincontent} *}
           
            </div>
            
            <div class="content_right">
               {rightblock}
            </div>
          	
          </div>
                 <div style="height:100px;background: none repeat scroll 0 0 #2e3136;">
                 </div>
    </div>

</div>
</body>
</html>

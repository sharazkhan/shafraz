
   {nocache} 
    <p class="ShopRatingDiv">
   {$shopitem.shop_info|cleantext|nl2br}
    </p> 
     <div class="ShopRatingDiv">
        {foreach from=$ztext_pages item='page'}
            <p>{$page.bodytext}</p>
           
            {if !$page_setting.disable_frontend_image}
               {if $page.extension eq 'pdf'}
                  {assign var="imageExist" value="zselexdata/`$smarty.request.shop_id`/ztext/pdf/medium/`$page.image`"}
                 {if is_file($imageExist)}
                 <p>
                      <a target="_blank" href="{modurl modname="ZTEXT" type="user" func="pdfView" id=$page.text_id}">
                 <img src="{$baseurl}zselexdata/{$smarty.request.shop_id}/ztext/pdf/medium/{$page.image}">
                      </a>
                 </p>
                {/if}
             {else}    
                 {assign var="imageExist" value="zselexdata/`$smarty.request.shop_id`/ztext/medium/`$page.image`"}
                 {if is_file($imageExist)}
                 <p>
                      <a id="my{$page.text_id}" rel="imageviewer[pageGallery]" title="{$page.headertext}" href="{$baseurl}zselexdata/{$smarty.request.shop_id}/ztext/fullsize/{$page.image}">
                 <img  src="{$baseurl}zselexdata/{$smarty.request.shop_id}/ztext/medium/{$page.image}">
                      </a>
                 </p>
                {/if}
             {/if}
           {/if}
        {/foreach}
     </div>
    {if $shopitem.link_to_homepage neq ''}
    <p>
  <a  href="{$shopitem.link_to_homepage}" target="blank">{gt text='Click here to go to our homepage (will open in new page)...'}</a>
     </p>
    {/if}
    
  
   <div>{fblikeservice action='like' url=$url width="500px" height="21px" layout='horizontal' shop_id=$smarty.request.shop_id addmetatags=true metatitle=$shopitem.shop_name metatype="website" metaimage=$shopImage description=$shopitem.shop_info faces=true}</div>
 {*  <div>{fbpostonwall link=$url image=$shopImage title=$shopitem.shop_name caption='' description=$shopitem.shop_info}</div> *}
   <div>{fbshare shop_id=$smarty.request.shop_id  url=$url}</div>
  {* <div style="clear:both"></div>*}
   <br />
    
   {/nocache}
  
    {* <p><a class="infoclass" id="defwindowajax" href="{modurl modname='ZSELEX' type='info' func='myTest'}"  title="Default window title">Open window</a></p>
                        <script type="text/javascript">
                        var defwindowajax = new Zikula.UI.Window($('defwindowajax'),{resizable: true});
                        </script>
                        *}
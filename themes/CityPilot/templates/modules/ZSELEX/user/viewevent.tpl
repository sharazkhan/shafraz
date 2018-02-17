{ajaxheader imageviewer="true"}
<script>
jQuery(document).ready(function() {
    jQuery("img.lazy").lazyload();
});
</script> 
<div class="ContentLeft left">
    
    <div class="EventHeader">
        <!--<h2 id="EventName">{$event.shop_event_name|wordwrap:25:"<br/>"}</h2>-->
        {if $perm}
        <div class="OrageEditSec EditEvent">
                  <a href="{modurl modname="ZSELEX" type="admin" func="events" shop_id=$smarty.request.shop_id event_id=$event.shop_event_id src='view'}">
                      <img src="{$themepath}/images/OrageEdit.png">{gt text='Edit Event'}
                  </a>
       </div>
        {/if}
        <p>
        <h2 id="EventName">
             {if $event.event_link!=''}
                <a {if $event.open_new}target="_blank"{/if} href="{$event.event_link}">
                {$event.shop_event_name|cleantext|wordwrap:19:"<br />":true}
                </a>
                {else}
               {$event.shop_event_name|cleantext|wordwrap:19:"<br />":true}
             {/if}
            
        </h2>
        {*<div class="ShopNameRight">
            <h3>
                {$shop_info.shop_name}&nbsp;<span class="ChatDiv">{$total_ratings}</span><br><span class="Orange"> &nbsp;{$shop_info.city_name}</span> 
            </h3>
        </div>*}
        
    </div>
    
    <div class="DetailProductImage">
      {if $event.showfrom eq 'image'} 
       
         {*<a id="my{$shop_id}" rel="imageviewer[eventGallery]" title="{$event.shop_event_name}" href="{$baseurl}zselexdata/{$shop_id}/events/fullsize/{$event.event_image}">
              <img class="lazy" data-original="{$baseurl}zselexdata/{$shop_id}/events/fullsize/{$event.event_image}" style="width:100%" >  
         </a>*}
            {if $event.call_link_directly eq 2 && $event.event_link!=''} 
              <a title="{$event.shop_event_name}" {if $event.open_new}target="_blank"{/if} href="{$event.event_link}">
              <img class="lazy" data-original="{$baseurl}zselexdata/{$shop_id}/events/fullsize/{$event.event_image}" style="width:100%" >  
               </a>
             {else}
               <a id="my{$shop_id}" rel="imageviewer[eventGallery]" title="{$event.shop_event_name}" href="{$baseurl}zselexdata/{$shop_id}/events/fullsize/{$event.event_image}">
              <img class="lazy" data-original="{$baseurl}zselexdata/{$shop_id}/events/fullsize/{$event.event_image}" style="width:100%" >  
                 </a>
             {/if}  
         {assign var="event_image" value="`$baseurl`zselexdata/`$shop_id`/events/medium/`$event.event_image`"}
     {elseif $event.showfrom eq 'doc'} 
        <br> 
         {if $extension eq 'pdf'}
       {*<img src="{$themepath}/images/pdf.png"> *}
       {* <embed  src="{$baseurl}zselexdata/{$ownername}/events/docs/{$event.event_doc}" width="600" height="400">*}
       <a target="_blank" href="{modurl modname="ZSELEX" type='user' func='pdfViewEvent' shop_id=$smarty.request.shop_id pdf=$event_doc}">
       <img src="{$baseurl}zselexdata/{$shop_id}/events/docs/medium/{$event.pdf_image}.jpg">
       </a>
         {else if $extension eq 'doc'}
         <img src="{$themepath}/images/doc.png">
         {/if}
        <br>
        <a target="_blank" href="{modurl modname="ZSELEX" type='user' func='pdfViewEvent' shop_id=$smarty.request.shop_id pdf=$event_doc}">{gt text='view document'}</a>
        {elseif $event.showfrom eq 'product'}
        {if $shoptype eq 'iSHOP'}
        <a href="{modurl modname="ZSELEX" type='user' func='productview' id=$product.product_id}" target="_blank">
           <img src="{$baseurl}zselexdata/{$shop_id}/products/medium/{$product.prd_image}">
        </a>
         {assign var="event_image" value="`$baseurl`zselexdata/`$shop_id`/products/thumb/`$event.event_image`"}
        <br>
        {gt text='Product name'} : <a href="{modurl modname="ZSELEX" type='user' func='productview' id=$product.product_id}">
                          {$product.product_name|cleantext}
    </a>
    {else}
    <img src="{$baseurl}zselexdata/{$shop_id}/products/thumb/{$product.prd_image}">
    <br><br>
    {*  <a href="{modurl modname="ZSELEX" type='user' func='productview' id=$product.product_id}" target="_blank">{$product.product_name}</a> *}
    <a href='http://{$zencart.domain}/index.php?main_page=product_info&products_id={$product.products_id}' target='_blank'> <img src="http://{$zencart.domain}/images/{$product.products_image}"  {if $product.H  neq  ''} height='{$product.H}'  width='{$product.W}' {else} width='170px'   {/if}/></a>
      {assign var="event_image" value="http://`$zencart.domain`/images/`$product.products_image`"}
    <br><br>
    {gt text='Product name'} :  <a href='http://{$zencart.domain}/index.php?main_page=product_info&products_id={$product.products_id}' target='_blank'> {$product.products_name}</a>
    {/if}   
    {elseif $event.showfrom eq 'article'}
    <h3 class="news_title">{$info.title|safehtml}</h3>
    <div id="news_body" class="news_body">
        {if $modvars.News.picupload_enabled AND $info.pictures gt 0}
        <div class="news_photo news_thumbs" style="float:{$modvars.News.picupload_article_float}">
            <a href="{$modvars.News.picupload_uploaddir}/pic_sid{$info.sid}-0-norm.jpg" rel="imageviewer[sid{$info.sid}]">{*<span></span>*}<img src="{$modvars.News.picupload_uploaddir}/pic_sid{$info.sid}-0-thumb2.jpg" alt="{gt text='Picture %1$s for %2$s' tag1='0' tag2=$info.title|safehtml}" /></a>
        </div>
         {assign var="event_image" value="`$modvars.News.picupload_uploaddir`/pic_sid`$info.sid`-0-norm.jpg" rel="imageviewer[sid`$info.sid`]"}
        {/if}
        <div class="news_hometext">
            {$info.hometext|notifyfilters:'news.hook.articlesfilter.ui.filter'|safehtml}
        </div>
        {$info.bodytext|notifyfilters:'news.hook.articlesfilter.ui.filter'|safehtml}
        <p class="news_footer">
            {$preformat.print}
            {if $modvars.News.pdflink}
            <span class="text_separator">|</span>
            <a title="PDF" href="{modurl modname='News' type='user' func='displaypdf' sid=$info.sid}" target="_blank">PDF <img src="modules/News/images/pdf.gif" width="16" height="16" alt="PDF" /></a>
            {/if}
        </p>
        {if $modvars.News.picupload_enabled AND $info.pictures gt 1}
        <div class="news_pictures"><div><strong>{gt text='Picture gallery'}</strong></div>
            {section name=counter start=1 loop=$info.pictures step=1}
            <div class="news_photoslide news_thumbsslide">
                <a href="{$modvars.News.picupload_uploaddir}/pic_sid{$info.sid}-{$smarty.section.counter.index}-norm.jpg" rel="imageviewer[sid{$info.sid}]"><span></span>
                    <img src="{$modvars.News.picupload_uploaddir}/pic_sid{$info.sid}-{$smarty.section.counter.index}-thumb.jpg" alt="{gt text='Picture %1$s for %2$s' tag1=$smarty.section.counter.index tag2=$info.title}" /></a>
            </div>
            {/section}
        </div>
        {/if}
    </div>
    {/if}   
</div> 
<div>
<h3 class="EventDescription">{*Ecco giver fodmassage til alle der har lyst*}{$event.shop_event_shortdescription}</h3>
<p>
    {*
    Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. <br /><br />
    Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a,venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. 
    *}
    {$event.shop_event_description|cleantext}
</p>
</div>
 <div align="center" class="viewAllEvent">
   <a href="{modurl modname='ZSELEX' type='user' func='showEvents' shop_id=$smarty.request.shop_id}"> {gt text='view all'} </a>
   </div>

             <div>
                 {modurl modname='ZSELEX' type='user' func='viewevent' shop_id=$event.shop_id  eventId=$event.shop_event_id assign="event_link"}
                 {assign var="url" value="`$baseurl`$event_link"}
              
               <span>{fblikeservice action='like' url=$url  width="500px" height="21px" layout='horizontal' shop_id=$event.shop_id  addmetatags=true metatitle=$event.shop_event_name metatype="website" metaimage=$event_image description=$event.shop_event_description faces=true}</span>
              {* <span>{fbpostonwall shop_id=$event.shop_id  link=$url image=$event_image title=$event.shop_event_name caption='' description=$event.shop_event_description}</span>*}
               <span>{fbshare shop_id=$event.shop_id  url=$url}</span>
             </div>
</div>
<div class="ContentRight left">
    <div class="EventDetailsSchedule">
        <ul>
            {*<li id="Calander"><div>Fredag d. 5. Juli</div></li>*}
            
            <li id="Calander"><div>{$date_format_start.weekday} {$date_format_start.mday}. {$date_format_start.month}<br />
            {$date_format_end.weekday} {$date_format_end.mday}{if ($date_format_end.mday != "")}.{/if} {$date_format_end.month}</div></li>
            <li id="Clock"><div>{if $event.shop_event_starthour > 0}{$event.shop_event_starthour} - {$event.shop_event_endhour}{/if}</div></li>
            <li id="Book">
                <div style="padding-top:5px;">
                   
                      {if $event.shop_event_venue neq ''} 
                         {$event.shop_event_venue}
                       {else}
                            {if $event.showfrom eq 'image'}  
                           {gt text='see event image'}
                             {else}
                           {gt text='see event'}  
                              {/if}   
                       {/if}
                       
                </div>
            </li>
            <li id="Mail">
                <div>
                     {if $event.email neq ''} 
                         {$event.email}
                       {else}
                            {if $event.showfrom eq 'image'}  
                           {gt text='see event image'}
                             {else}
                           {gt text='see event'}  
                              {/if}   
                       {/if}
                </div>
            </li>
            <li id="Phone">
                <div>
                    {if $event.phone neq ''} 
                         {$event.phone}
                       {else}
                            {if $event.showfrom eq 'image'}  
                           {gt text='see event image'}
                             {else}
                           {gt text='see event'}  
                              {/if}   
                       {/if}
                </div>
            </li>
            <li id="Price">
                <div>
                    {if $event.price neq ''} 
                    	{if $event.price > 0}
				{displayprice amount=$event.price}
                         {else}
                        	{gt text='FREE'}
                         {/if}
                       {else}
                            {if $event.showfrom eq 'image'}  
                           {gt text='see event image'}
                             {else}
                           {gt text='see event'}  
                              {/if}   
                       {/if}
                </div>
            </li>
            {if $event.event_link!=''}
             <li id="EventSite">
               <a {if $event.open_new}target="_blank"{/if} href="{$event.event_link}">
                <button class="Orange_button" type="button">
                 <span>{gt text='Event Site'}</span><span class="Right_Arrow"></span>
                </button> 
                </a>
           </li>
           {/if}
           
        </ul>
    </div>
</div>
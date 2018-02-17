{pageaddvar name="stylesheet" value="themes/CityPilot/style/viewevent.css"} 
<div class="ContentLeft left">
    <div class="EventHeader">
        <!--<h2 id="EventName">{$event.shop_event_name|wordwrap:25:"<br/>"}</h2>-->
        <h2 id="EventName">{$event.shop_event_name}</h2>
        {*<div class="ShopNameRight">
            <h3>
                {$shop_info.shop_name}&nbsp;<span class="ChatDiv">{$total_ratings}</span><br><span class="Orange"> &nbsp;{$shop_info.city_name}</span> 
            </h3>
        </div>*}
    </div>
    <div class="DetailProductImage">
      {if $event.showfrom eq 'image'} 
        {*<img src="{$themepath}/images/Banner.png" width="100%" />*}
         <img src="{$baseurl}zselexdata/{$ownername}/events/medium/{$event.event_image}">
        {elseif $event.showfrom eq 'doc'} 
        <br> 
         {if $extension eq 'pdf'}
       {*<img src="themes/CityPilot/images/pdf.png"> *}
        <embed  src="{$baseurl}zselexdata/{$ownername}/events/docs/{$event.event_doc}" width="100%" height="100%">
         {else if $extension eq 'doc'}
         <img src="themes/CityPilot/images/doc.png">
         {/if}
        <br>
        <a href="{modurl modname="ZSELEX" type='user' func='pdfViewEvent' shop_id=$smarty.request.shop_id pdf=$event_doc}">view document</a>
        {elseif $event.showfrom eq 'product'}
        {if $shoptype eq 'iSHOP'}
        <a href="{modurl modname="ZSELEX" type='user' func='productview' id=$product.product_id}" target="_blank">
           <img src="{$baseurl}zselexdata/{$ownername}/products/thumb/{$product.prd_image}">
        </a>
        <br>
        Product name : <a href="{modurl modname="ZSELEX" type='user' func='productview' id=$product.product_id}">
                          {$product.product_name}
    </a>
    {else}
    <img src="{$baseurl}zselexdata/{$ownerName}/products/thumb/{$product.prd_image}">
    <br><br>
    {*  <a href="{modurl modname="ZSELEX" type='user' func='productview' id=$product.product_id}" target="_blank">{$product.product_name}</a> *}
    <a href='http://{$zencart.domain}/index.php?main_page=product_info&products_id={$product.products_id}' target='_blank'> <img src="http://{$zencart.domain}/images/{$product.products_image}"  {if $product.H  neq  ''} height='{$product.H}'  width='{$product.W}' {else} width='170px'   {/if}/></a>
    <br><br>
    Product name :  <a href='http://{$zencart.domain}/index.php?main_page=product_info&products_id={$product.products_id}' target='_blank'> {$product.products_name}</a>
    {/if}   
    {elseif $event.showfrom eq 'article'}
    <h3 class="news_title">{$info.title|safehtml}</h3>
    <div id="news_body" class="news_body">
        {if $modvars.News.picupload_enabled AND $info.pictures gt 0}
        <div class="news_photo news_thumbs" style="float:{$modvars.News.picupload_article_float}">
            <a href="{$modvars.News.picupload_uploaddir}/pic_sid{$info.sid}-0-norm.jpg" rel="imageviewer[sid{$info.sid}]">{*<span></span>*}<img src="{$modvars.News.picupload_uploaddir}/pic_sid{$info.sid}-0-thumb2.jpg" alt="{gt text='Picture %1$s for %2$s' tag1='0' tag2=$info.title|safehtml}" /></a>
        </div>
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
    {$event.shop_event_description}
</p>
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
           
        </ul>
    </div>
</div>
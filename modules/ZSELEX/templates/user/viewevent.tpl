
{if $perm}
<a href="{modurl modname="ZSELEX" type="admin" func="events" shop_id=$smarty.request.shop_id event_id=$event.shop_event_id src='view'}" class="edit-link">
    <i class="fa fa-pencil-square" aria-hidden="true"></i>{gt text='Edit Event'}
</a>
{/if}

<div class="col-sm-12">
    {if $event.event_link!=''}
    <h2>
        <a {if $event.open_new}target="_blank"{/if} href="{$event.event_link}">
            {$event.shop_event_name|cleantext|wordwrap:19:"<br />":true} 
        </a>
    </h2>
    {else}
    <h2> {$event.shop_event_name|cleantext|wordwrap:19:"<br />":true} </h2>
    {/if}
    <p></p>
     <ul class="image-lightbox full-width-image clearfix">
        <li>
            {if $event.showfrom eq 'image'} 
            {if $event.call_link_directly eq 2 && $event.event_link!=''} 
            <a title="{$event.shop_event_name}" {if $event.open_new}target="_blank"{/if}  href="{$event.event_link}" >
               <img alt="" class="img-responsive" src="{$baseurl}zselexdata/{$shop_id}/events/medium/{$event.event_image}">
            </a>
            {else}
            <a class="fancybox" rel="gallery1" href="{$baseurl}zselexdata/{$shop_id}/events/fullsize/{$event.event_image}" title="{$event.shop_event_name}">
                <img alt="" class="img-responsive" src="{$baseurl}zselexdata/{$shop_id}/events/medium/{$event.event_image}">
            </a>
            {/if}
            {assign var="event_image" value="`$baseurl`zselexdata/`$shop_id`/events/medium/`$event.event_image`"}
            {elseif $event.showfrom eq 'doc'} 
            {if $extension eq 'pdf'}

            <a target="_blank" href="{modurl modname="ZSELEX" type='user' func='pdfViewEvent' shop_id=$smarty.request.shop_id pdf=$event_doc}">
               <img class="img-responsive" src="{$baseurl}zselexdata/{$shop_id}/events/docs/medium/{$event.pdf_image}.jpg">
            </a>
            {else if $extension eq 'doc'}
            <img class="img-responsive" src="{$themepath}/images/doc.png">
            {/if}
            <br>
            <a target="_blank" href="{modurl modname="ZSELEX" type='user' func='pdfViewEvent' shop_id=$smarty.request.shop_id pdf=$event_doc}">{gt text='view document'}</a>

            {elseif $event.showfrom eq 'product'}
            <a href="{modurl modname="ZSELEX" type='user' func='productview' id=$product.product_id}" target="_blank">
               <img class="img-responsive" src="{$baseurl}zselexdata/{$shop_id}/products/medium/{$product.prd_image}">
            </a>
            {assign var="event_image" value="`$baseurl`zselexdata/`$shop_id`/products/thumb/`$event.event_image`"}
            <br>
            {gt text='Product name'} : <a href="{modurl modname="ZSELEX" type='user' func='productview' id=$product.product_id}">
                                          {$product.product_name|cleantext}
        </a>
        {/if}
    </li>
</ul>


<h4><b>{$event.shop_event_shortdescription|cleantext}</b></h4>
<p>
    {$event.shop_event_description|cleantext}
</p>
<div class="text-right">
    <a href="{modurl modname='ZSELEX' type='user' func='showEvents' shop_id=$smarty.request.shop_id}" class="btn view-all">{gt text='view all'} </a>
</div>
</div>

 {modurl modname='ZSELEX' type='user' func='viewevent' shop_id=$event.shop_id  eventId=$event.shop_event_id assign="event_link"}
                 {assign var="url" value="`$baseurl`$event_link"}
<!-- social share  -->
<div class="social-share col-sm-12">
   {fblikeservice action='like' url=$url  width="500px" height="21px" layout='horizontal' shop_id=$event.shop_id  addmetatags=true metatitle=$event.shop_event_name metatype="website" metaimage=$event_image description=$event.shop_event_description faces=true}
   {fbshare shop_id=$event.shop_id  url=$url}
</div>
<!-- social end -->
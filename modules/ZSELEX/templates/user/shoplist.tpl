<div class="row shop-list-wrap">
      {foreach item='item' key=index from=$shops}
    <div class="col-md-6 shop-lists">
        <div class="media">
            <div class="media-left">
                <a href="{modurl modname='ZSELEX' type='user' func='site'  id=$item.SID}">
                    
                   {if $item.default_img_frm eq 'fromgallery'}
                      {assign var="imagepath" value="zselexdata/`$item.shop_id`/minisitegallery/medium/`$item.image_name`"}
                        {if file_exists($imagepath) && $item.image_name neq ''}
                       {* {imageproportional image=$item.image_name path="`$baseurl`zselexdata/`$item.shop_id`/minisitegallery/medium" height="142" width="147"}*}
                        <img class="media-object" src="{$baseurl}zselexdata/{$item.shop_id}/minisitegallery/medium/{$item.image_name}" {*{$imagedimensions}*}>
                        {else}
                         <img class="media-object" src="{$themepath}/images/no-image.jpg"  height="142" width="147"/>    
                     {/if}    
                   
                  {elseif $item.default_img_frm eq 'fromshop'}
                  {assign var="imagepath" value="zselexdata/`$item.shop_id`/minisiteimages/medium/`$item.name`"}
                  {if file_exists($imagepath) && $item.name neq ''}
                      {*{imageproportional image=$item.name path="`$baseurl`zselexdata/`$item.shop_id`/minisiteimages/medium" height="142" width="147"}*}
                    
                        <img class="media-object" src="{$baseurl}zselexdata/{$item.shop_id}/minisiteimages/medium/{$item.name}" {*{$imagedimensions}*}>
                    
                  {else}
                
                     <img class="media-object" src="{$themepath}/images/no-image.jpg"  height="142" width="147"/>
                  
                  {/if}
               {/if}  
                </a>
            </div>
            <div class="media-body">
                <a href="{modurl modname='ZSELEX' type='user' func='site'  id=$item.SID}">
                    <h4 class="media-heading">{shorttext text=$item.shop_name|cleantext len=39}</h4>
                    <h6>{if $item.city_name neq ''} {shorttext text=$item.city_name|cleantext len=30}{/if}</h6>
                    <p> {shorttext text=$item.shop_desc|cleantext|wordwrap:43:"\n":true len=145}
                    </p>
                </a>
                <div class="shop-nav-right clearfix">
                 <div class="rating-wrap">
                    <div class="rating-star">
                        {*
                        <a href="#"><img alt="" src="{$themepath}/images/activestar.png"></a>
                        <a href="#"><img alt="" src="{$themepath}/images/activestar.png"></a>
                        <a href="#"><img alt="" src="{$themepath}/images/activestar.png"></a>
                        <a href="#"><img alt="" src="{$themepath}/images/inactivestar.png"></a>
                        <a href="#"><img alt="" src="{$themepath}/images/inactivestar.png"></a>
                        *}
                        {section name=starcount loop=$item.rating|round}
                       {assign var=i value=$smarty.section.starcount.iteration|intval}
                       <div class="star" id={$i}></div>
                       {/section}
                    </div>
                    <div class="transparent">
                       <div class="star" id="1"></div>
                       <div class="star" id="2"></div>
                       <div class="star" id="3"></div>
                       <div class="star" id="4"></div>
                       <div class="star" id="5"></div>
                   </div>
                </div>
                    <div class="review-count">{$item.votes}</div>
                    <div class="see-products">
                         {if $item.linktoshop}
                        <a href="{modurl modname='ZSELEX' type='user' func='shop'  shop_id=$item.SID}">{gt text='See Products'}</a>
                        {/if}
                    </div>
                </div> 
            </div>
        </div>
    </div>
    {/foreach}


    <div class="col-sm-12 shop-list-pagination text-center product-pagination">
        <!-- <h2>Shop Lists</h2> -->
        {*<ul class="pagination">
            <li class="p-arrows disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
            <li class="p-arrows disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">&lsaquo;</span></a></li>
            <li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">...</a></li>
            <li><a href="#">4</a></li>
            <li><a href="#">5</a></li>
            <li class="p-arrows"><a aria-label="Next" href="#"><span aria-hidden="true">&rsaquo;</span></a></li>
            <li class="p-arrows"><a aria-label="Next" href="#"><span aria-hidden="true">&raquo;</span></a></li>
        </ul>*}
          {pager rowcount=$total_count limit=$itemsperpage posvar='startnum' maxpages=10}
    </div>
    
</div>
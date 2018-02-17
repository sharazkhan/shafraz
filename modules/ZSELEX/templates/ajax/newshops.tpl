{foreach item='item' from=$newshops}
<div class="col-sm-4 btm-special-product hover-border">
    <div class="thumbnail">
        <a href="{modurl  modname='ZSELEX' type='user' func='site' shop_id=$item.shopid}">
            <div class="btm-product-hed">
                <div class="btm-product-sub-text">
                    <span>{shorttext text=$item.shop_name|cleantext|replace:'<br>':' ' len=23},</span>
                    <span class="sub-name">{shorttext text=$item.city_name len=15}</span>
                </div>
                {if $item.aff_id > 0}
                {assign var="imagename" value=$item.affiliate_image|replace:' ':'%20'}
                {assign var="image" value="modules/ZSELEX/images/affiliates/`$imagename`"}
                {if is_file($image)}
                <span class="icon-member">
                    <img src="{$baseurl}modules/ZSELEX/images/affiliates/{$item.affiliate_image}" alt="" width="49" height="50">
                </span>
                {/if}
                {/if}
            </div>
            <div class="pro-image">
                {if  !$item.no_image}
                {$item.image}
                {/if}
            </div>
        </a>
        <div class="btm-product-caption clearfix">
            <div class="rating-star">
                {*<a href="#">
                    <img src="{$themepath}/images/rating.jpg" alt="" width="94" height="18">
                </a>*}
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
            <div class="rating-right">
                {$item.see_ful_store}
            </div>
        </div>
    </div>
</div>
{foreachelse}
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{gt text='No shops found'} 

{/foreach}    



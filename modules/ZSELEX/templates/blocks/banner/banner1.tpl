<script>
    jQuery(document).ready(function () {
        jQuery('.bxslider-banner').bxSlider({
            mode: 'fade',
            pager: false,
            controls: false,
            auto: false
        });
    });
</script>
{if !empty($getBanner.banner_image)}
{assign var="image" value="zselexdata/`$shop_id`/banner/resized/`$getBanner.banner_image`"}
{if file_exists($image)}
<section class="slider-wrapper">
    <div class="container">
        <div class="banner-slider">
            <ul class="bxslider-banner clearfix">
                <li>
                    <img src="{$baseurl}zselexdata/{$shop_id}/banner/resized/{$getBanner.banner_image|replace:' ':'%20'}" >
                    {blockposition name='minisite-announcement' assign='announcementText'}
                    {if $announcementText}<span class="banner-band">{$announcementText}</span>{/if}
                </li>

            </ul>
        </div>
        {if $perm}
    <a href="{modurl modname="ZSELEX" type="admin" func="shopsettings" shop_id=$shop_id}#aBanner" class="edit-link"><i class="fa fa-pencil-square" aria-hidden="true"></i>
        {gt text='Edit Banner'}
    </a>
         {/if}
    </div>
</section>
{/if}
{/if}


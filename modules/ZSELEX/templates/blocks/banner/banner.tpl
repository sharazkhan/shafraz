
<style>
 .BannerImage{
     text-align:center; 
     height: 320px; 
    /* width: 100%;
     background-size: cover; 
     background-position: center center;
     -webkit-background-size: cover;
     -moz-background-size: cover;
       -o-background-size: cover;
          background-size: cover;*/
   
}
.BannerImage.img{
      width: 100%;
  
}

</style>

{if !empty($getBanner.banner_image)}
    {assign var="image" value="zselexdata/`$shop_id`/banner/resized/`$getBanner.banner_image`"}
{*<img src=zselexdata/{$ownerName}/banner/resized/{$getBanner.banner_image} style="min-width:940px;width:100%;">*}
    {if file_exists($image)}
        {*
    <div class="BannerImage" style='background-image: url({$baseurl}zselexdata/{$shop_id}/banner/resized/{$getBanner.banner_image|replace:' ':'%20'}); '>
    </div>
    *}
    <div class="BannerImage">
        <img src="{$baseurl}zselexdata/{$shop_id}/banner/resized/{$getBanner.banner_image|replace:' ':'%20'}">
    </div>
    {/if}
{else}
<div style="height:30px">
</div>
{/if}
{if $perm}
<div>
    <!---->
    <div class="inner OrageEditSec EditBanner">
        <a href="{modurl modname="ZSELEX" type="admin" func="shopsettings" shop_id=$shop_id}#aBanner">
       <img src="themes/{$current_theme}/images/OrageEdit.png">{gt text='Edit Banner'}</a></div>
</div>
{/if}

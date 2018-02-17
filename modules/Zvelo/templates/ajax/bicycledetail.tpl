
{if $bicycle}
<div class="preview_img_sec">
<div class="preview_main"><img src="{$themepath}/images/BikeImage.png" />
</div>
<div class="preview_decription">
    <h5>{$bicycle.name}</h5>
    <p>{$bicycle.description}</p>
</div>
</div>
<div class="preview_thum_sec">
<div class="thum_preview_img">
        <img src="{$themepath}/images/{$bicycle.imagename}">
</div>
<p>{$bicycle.name}<br />
  {$bicycle.nos}
</p>
</div>
{else}
   <div style="padding-left:220px;padding-top:70px">{gt text='No Bicycle selected'}</div>
 {/if}
<div onClick="editAnnouncement()" >
     {imageproportional image=$minisite_banner.banner_image path="`$baseurl`zselexdata/`$shop_id`/banner/resized" height="120" width="500"}
    <img {$imagedimensions} style="cursor:pointer" width="500" height="120" src="zselexdata/{$shop_id}/banner/resized/{$minisite_banner.banner_image|safetext}">
</div>    
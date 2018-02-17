  <ul class="ImagePrivew">
            {foreach item='item' key=index from=$minisite_images}
            <li onClick="editImage({$item.file_id})" style="background: url({$baseurl}zselexdata/{$shop_id}/minisiteimages/thumb/{$item.name|replace:' ':'%20'}) no-repeat center center;cursor:pointer" >
                <a id="my{$item.file_id}" rel="imageviewer[minisiteimageGallery]" title="{$item.filedescription}" href="{$baseurl}zselexdata/{$shop_id}/minisiteimages/fullsize/{$item.name}" >

                </a>
            </li>
            {/foreach}    
  </ul>
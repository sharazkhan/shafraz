{ajaxheader imageviewer="true"}
{pageaddvar name="stylesheet" value="themes/CityPilot/style/minisiteimages.css"}
<style>
    #myimages{
        text-align:center; 
        height: 98px; 
        width: 98px; 
        border:solid 1px black; 
        
    }
</style>

     
 <div id="minisiteimage_block">
 <h3 class="Skinh3">Arrangementer:</h3>
                       <div class="bodyImage">
                            {foreach item='item' key=index from=$images}
                                 <div class="ImageThumbSec"  style="background: url({$baseurl}zselexdata/{$ownerName}/minisiteimages/medium/{$item.name|replace:' ':'%20'}) no-repeat center center;{if $index+1 > 2}display:none{/if}">
                                 <a id="my{$item.file_id}" rel="imageviewer[minisiteimageGallery]" title="{$item.filedescription}" href="{$baseurl}zselexdata/{$ownerName}/minisiteimages/fullsize/{$item.name}" style="width:238px; height:195px; display: block;">
                                 {if $index+1 eq 2 && $count > 2}<div style="padding-left:10px;margin-top: 165px; font-size:16px;">{gt text='More images'}...</div>{/if} 
                                 </a>
                                 
                                 </div>
                                 
                               {/foreach}
                          </div>
    </div>
                                

 
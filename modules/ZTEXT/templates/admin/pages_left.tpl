{section name=foo loop=$pages_left}
    
<table class="options newevent">
    <tbody>
        <tr>
            <td>
                <div onclick="editPage({$smarty.section.foo.iteration},'new');"  id="drophereNew{$smarty.section.foo.iteration}" style="width:620px !important;height:150px !important; cursor:pointer !important; background: #e8e8e8 !important; border-radius: 8px;  text-align: center !important; color: #707070; font-size: 16px !important; padding-top: 40px !important;">
                    <div style="margin:auto !important; width:400px !important; "><img src="{$baseurl}modules/ZSELEX/images/down_grey.png" class="left"><p style="width:auto">{gt text='Drag and Drop Page image to<br> this box or just click anywhere inside the box'}</p>{gt text='OR'}</div>
                </div>

                <div id="create_pages{$smarty.section.foo.iteration}" class="BottomAddImage"></div>
            </td>
        </tr>
    </tbody>
</table>
 
{/section}

{if $page_limit}
{section name=foo loop=$pages_left}
    <script>
     function triggerDnd{{$smarty.section.foo.iteration}}(){
         //alert('hiii');
    jQuery('#create_pages{{$smarty.section.foo.iteration}}').ajaxupload({
        url: document.location.pnbaseURL+"index.php?module=ZTEXT&type=Dnd&func=uploadPageImage",
        data:"shop_id={{$smarty.request.shop_id}}&purpose=create&text_id={{$item.text_id}}&file_check_folder=fullsize",
       
        maxFiles: 1,
        dropArea:'#drophereNew{{$smarty.section.foo.iteration}}',
        autoStart: true,
        remotePath: document.getElementById('uploadpath').value,
        removeOnSuccess:true,
        beforeUploadAll: function (files_arr) {
          

        },
        finish:function(files, filesObj){
          // alert('finish');
            //window.location.href='';
            // alert('All files has been uploaded:' + filesObj);
           deleteExtraPageService();
           //alert(files[0]);
           getPageByImage(files[0] , {{$smarty.section.foo.iteration}} , {{$smarty.request.shop_id}});
        },
        error:function(txt, obj){
            //alert(txt);
            alert(Zikula.__('Cannot upload image' , 'module_ztext_js'));
        }
    });
    }
    triggerDnd{{$smarty.section.foo.iteration}}();
    </script>
{/section} 

{/if}
    

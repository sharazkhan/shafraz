 <div id="div{$item.text_id}">
<table class="options editevent">
    <tbody>
        <tr>
            <td>
                <div onclick="editPage({$item.text_id},'edit');" id="drophere{$item.text_id}" class="EventDiv">
                 {include file="admin/single_content.tpl"}
                </div>
                <div class="EvenBottomOrange">
                    <div class="OrageEvenTextL left">
                        {gt text='Edit This Page'}<br>

                    </div>

                    <div style="padding-right:124px;padding-top:17px" class="OrageEvenTextR right">{gt text='Drag and Drop Image'}</div>

                </div>

                <div id="page{$item.text_id}" style="" class="TopAddevent"></div>
            </td>
        </tr>
    </tbody>
</table>
            
   <script type="text/javascript">
    jQuery('#page{{$item.text_id}}').ajaxupload({
        url: document.location.pnbaseURL+"index.php?module=ztext&type=Dnd&func=uploadPageImage",
        data:"shop_id={{$smarty.request.shop_id}}&purpose=edit&text_id={{$item.text_id}}&file_check_folder=fullsize",
        maxFiles: 1,
        dropArea:'#drophere{{$item.text_id}}',
        autoStart: true,
        remotePath: 'zselexdata/26/ztext',
        removeOnSuccess:true,
        maxConnections:1,
        beforeUploadAll: function (files_arr) {
          

        },
        finish:function(files, filesObj){
          // alert('finish');
            //window.location.href='';
            deleteExtraPageService();
            getPage({{$item.text_id}},{{$smarty.request.shop_id}});
        },
         error:function(txt, obj){
            //alert(txt);
            alert(Zikula.__('Cannot upload image' , 'module_ztext_js'));
        }
    });
    </script>
    </div>
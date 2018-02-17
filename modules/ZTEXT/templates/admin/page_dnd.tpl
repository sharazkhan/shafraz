{pageaddvar name='javascript' value='jquery'}
{pageaddvar name="jsgettext" value="module_ztext_js:ZTEXT"}
{jsvalidator}
{pageaddvar name='javascript' value='modules/ZTEXT/javascript/dnd/ajaxupload-min.js'}
{pageaddvar name="stylesheet" value="modules/ZTEXT/style/dnd/classicTheme/style.css"}

{pageaddvar name="stylesheet" value="themes/CityPilot/style/event_dnd.css"}
{pageaddvar name="stylesheet" value="modules/ZTEXT/style/page_dnd.css"}

{pageaddvar name='javascript' value='modules/Scribite/includes/tinymce/tiny_mce.js'}
{pageaddvar name='javascript' value='modules/ZTEXT/javascript/page.js'}

   
<div id="backshield" class="backshield" style="height: 10157px;display:none"></div>
<div id="editPage" class="basket_content" style="display:none">

</div>
{if $smarty.request.text_id > 0}
    
   <script>
       jQuery( window ).load(function() {
       //alert('hiii');
      editPage({{$smarty.request.text_id}},'edit');
      });
      </script>
 {/if}
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='Manage Pages'}</h3>
</div>
<form class="z-form" id="zselex_form" name="zselex_form">
<div class="z-panels" id="panel">
     <fieldset>
        <legend id="settingheaders" title="{gt text='Click here to configure ztext'}">{gt text='Settings'}</legend>
      <div id="settingheadersdiv" style="display:none">
        <div class="z-formrow">
            <label for="pageindex_disabled">{gt text='Disable Page Index'}</label>
            <input onclick="disablePageIndex({$smarty.request.shop_id})" type="checkbox" value="1" id="pageindex_disabled" name="pageindex_disabled"{if $page_setting.disable_page_index eq true} checked="checked"{/if}/>
        </div>
        <div class="z-formrow">
            <label for="image_disabled">{gt text='Disable images in frontpage'}</label>
            <input onclick="disablePageIndex({$smarty.request.shop_id})" type="checkbox" value="1" id="image_disabled" name="image_disabled"{if $page_setting.disable_frontend_image eq true} checked="checked"{/if}/>
        </div>
        
      </div>
    </fieldset>
</div>
</form>
        
<script type="text/javascript">
    var panel = new Zikula.UI.Panels('panel', {
            headerSelector: '#settingheaders',
            headerClassName: 'z-panel-indicator'
            // active: [~~(anchors.indexOf(window.location.hash) <= -1)]

    });
</script>

    <input type="hidden" id="shop_id" name="shop_id" value="{$smarty.request.shop_id}" />
    <input type="hidden" id="uploadpath" value="{$upload_path}" />
    <div id="listPages">
{foreach item='item' from=$pages key='key'}
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
    {if $page_limit}
    <script type="text/javascript">
    jQuery('#page{{$item.text_id}}').ajaxupload({
        url: document.location.pnbaseURL+"index.php?module=ZTEXT&type=Dnd&func=uploadPageImage",
        data:"shop_id={{$smarty.request.shop_id}}&purpose=edit&text_id={{$item.text_id}}&file_check_folder=fullsize",
       
        maxFiles: {{$page_limit}},
        dropArea:'#drophere{{$item.text_id}}',
        autoStart: true,
        remotePath: document.getElementById('uploadpath').value,
        removeOnSuccess:true,
        removeOnError: true,
        maxConnections:1,
        beforeUploadAll: function (files_arr) {
          

        },
        finish:function(files, filesObj){
          // alert('finish');
            //window.location.href='';
            // alert('All files has been uploaded:' + files[0]);
            deleteExtraPageService();
            getPage({{$item.text_id}},{{$smarty.request.shop_id}});
        },
       error:function(txt, obj){
            //alert(txt);
            alert(Zikula.__('Cannot upload' , 'module_ztext_js'));
        }
    });
    </script>
    {/if}
    </div>
{/foreach}
    </div>
<div id="newPages">
{if !$service.expired AND $service.perm}
 {include file="admin/pages_left.tpl"}
{/if}
  </div>
{adminfooter}
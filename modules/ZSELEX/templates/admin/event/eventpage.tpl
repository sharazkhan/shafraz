
{pageaddvar name='javascript' value='jquery'}
{*{pageaddvar name='javascript' value='modules/ZSELEX/javascript/dndfiles/ajaxupload.js'}*}

<!------------------ VALIDATE PLUGIN --------------------------------------->
{jsvalidator}
<!---------------------------------------------------------------------->


<!------------------ DND PLUGIN --------------------------------------->
{*{pageaddvar name='javascript' value='modules/ZSELEX/javascript/DND/jquery.js'}*}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/DND/ajaxupload-min.js'}
{pageaddvar name="stylesheet" value="modules/ZSELEX/style/DND/classicTheme/style.css"}
<!---------------------------------------------------------------------->

{pageaddvar name="stylesheet" value="themes/$current_theme/style/event_dnd.css"}

{*
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/jquery.jscroll.min.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/jquery.jscroll.js'}
*}

{jscroll file_name='modules/ZSELEX/javascript/event_scroll.js'}

<script src="modules/ZSELEX/javascript/jquerycalendar/jquery-ui.js"></script>

{pageaddvar name='javascript' value='modules/ZSELEX/javascript/shopsetting/event.js'}

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
{pageaddvar name="stylesheet" value="themes/$current_theme/style/minisiteimages.css"}
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />

<input type="hidden" id="servicelimit" value="{$servicelimit}" />
<input type="hidden" id="quantity" value="{$quantity}" />
<input type="hidden" id="uploadpath" value="{$uploadpath}" />
<input type="hidden" id="shop_id" name="shop_id" value="{$smarty.request.shop_id}" />


<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='Manage Events'}</h3>
</div>
{securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_EDIT}

{/securityutil_checkpermission_block}
<style>
    {*  .aj-frame{display:none;} *}
    {*.ax-legend{display:none;} 
    .ax-button{display:none;} 
    .aj-frame{border:none !important; background: none !important} *}


    .ax-legend{display:none;} 
    .aj-frame{border:none !important; background: none !important}
    .ax-upload-all{display:none;} 
    .ax-clear{display:none;}

    .ax-button {color:#000; border:none; background:none; box-shadow:none;}
    {*.ax-button{font-size:1.2em}*}
    a.ax-button:hover{color:#000;}
    a.ax-button:visted{color:#000;}
    .ax-progress-info{height:auto}
    .OrageEvenTextR{ padding-top:5px}
    .ax-browse{border:none;}
    .EvenBottomOrange{height:45px;}
    .TopAddevent a.ax-button{ color:#FFF;  margin-left: 500px; margin-top:-60px}
    .BottomAddImage a.ax-button {margin-left:270px; margin-top:-96px}


    .ax-button{
    background: linear-gradient(180deg, rgb(164, 164, 164) 30%, rgb(145, 145, 145) 60%) repeat scroll 0 0 rgba(0, 0, 0, 0);
    border: 2px solid #8b8b8b;
    border-radius: 4px;
    color: #fff;
    cursor: pointer;
    font-weight: bold;
   }
    a.ax-button:hover{opacity:1;text-decoration:none}


     span.ax-plus-icon{ display:none;}

    .basket_content {
        background-color: white;
        /* border: 5px solid #DD511D;*/
        left: 50%;
        min-height: 50px;
        margin-left: -270px;
        overflow: auto;
        max-height: 550px;
        /*height: auto;*/
        position: absolute;
        padding: 20px;
        top: 3%;
        width: 500px;
        z-index: 10002;
    }
    .backshield {
        background-color: #333333;
        height: 200%;
        left: 0;
        opacity: 0.8;
        position: absolute;
        top: 0;
        width: 100%;
        z-index: 1000;
    }
</style>
<div id="backshield" class="backshield" style="height: 10157px;display:none"></div>
<br>
<!--<form class="z-form" id="zselex_event_form" name="zselex_form" action="" method="post">-->
 {if $smarty.request.event_id > 0}
   <script>
       jQuery( window ).load(function() {
  //alert('hiii');
        editEvent('{{$smarty.request.event_id}}' , '{{$smarty.request.src}}')
      });
      </script>
      {/if}
{foreach item='item' from=$events key='key'}
<table class="options editevent">
    <tbody>
        <tr>
            <td>
                 <div onclick="editEvent({$item.shop_event_id});" id="drophere{$item.shop_event_id}" class="EventDiv">
                    {* {if !$expired}{gt text='<b>Update This Event</b> : Drag and Drop new Event image or document to this box or click anywhere to edit Event information'}{/if}*}
                    <table id="zselex_admintable" class="z-datatable" style="width:600px;table-layout:fixed; margin: 0px;">
                        <thead>
                            <tr>
                                <th>{gt text='Event Name'}</th>
                                <th>{gt text='Short Desription'}</th>
                                <th>{gt text='Desription'}</th>
                                <th>{gt text='Start Date'}</th>
                                <th>{gt text='End Date'}</th>
                                <th></th>
                            </tr>
                        </thead>  
                        <tbody>
                            <tr class="{cycle values='z-odd,z-even'}">
                                <td width="1px">
                                    {*{$item.shop_event_name|cleantext}*}
                                     {shorttext text=$item.shop_event_name|strip_tags|nl2br|wordwrap:14:"\n":true len=20}
                                </td>
                                <td width="1px">
                                    {*{$item.shop_event_shortdescription|cleantext|wordwrap:15:"\n":true}*}
                                    {shorttext text=$item.shop_event_shortdescription|strip_tags|nl2br|wordwrap:14:"\n":true len=50}
                                </td>
                                <td width="1px">
                                    {*{$item.shop_event_description|cleantext|wordwrap:15:"\n":true}*}
                                    {shorttext text=$item.shop_event_description|strip_tags|nl2br|wordwrap:14:"\n":true len=50}
                                </td>
                                <td>{$item.shop_event_startdate}</td>
                                <td>{$item.shop_event_enddate}</td>
                                <td>
                                  {if $item.showfrom eq 'image'} 
                                    {if $item.event_image neq ''}
                                    {assign var="image" value="zselexdata/`$shop_id`/events/thumb/`$item.event_image`"}
                                    {if is_file($image)}
                                    {assign var="evnt_image" value="`$baseurl`zselexdata/`$shop_id`/events/thumb/`$item.event_image`"}
                                    <img style="max-height:93px;max-width:93px" src="{$baseurl}zselexdata/{$shop_id}/events/thumb/{$item.event_image}"></a>
                                    {/if}
                                    {/if}
                                  {elseif $item.showfrom eq 'doc'} 
                                    {if $item.event_doc neq ''}
                                    {assign var="docmnt" value="zselexdata/`$shop_id`/events/docs/`$item.event_doc`"}
                                    {assign var="evnt_image" value=""}
                                    {if is_file($docmnt)}

                                    <br>
                                    {if $item.docExt eq 'pdf'}
                                    <img style="max-height:93px;max-width:93px" src="{$baseurl}zselexdata/{$shop_id}/events/docs/thumb/{$item.fileName}.jpg"></a>
                                    {elseif $item.docExt eq 'doc'}
                                    <img style="max-height:93px;max-width:93px"  src="{$baseurl}modules/ZSELEX/images/doc.png"></a>    
                                    {/if}
                                    {/if}
                                    {/if}
                                 {elseif $item.showfrom eq 'product'} 
                                         {if $item.shoptype eq 'iSHOP'}
                                          <img style="max-height:93px;max-width:93px" src="{$baseurl}zselexdata/{$shop_id}/products/thumb/{$item.productImage}">
                                         {elseif $item.shoptype eq 'zSHOP'}
                                          <img style="max-height:93px;max-width:93px" src="http://{$item.zencart.domain}/images/{$item.productImage}">
                                         {/if}
                                  {/if}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="EvenBottomOrange">
                    <div class="OrageEvenTextL left">
                        {gt text='Edit This Event'}<br>

                    </div>

                    <div style="padding-right:124px;padding-top:17px" class="OrageEvenTextR right">{gt text='Drag and Drop Image/Doc'}</div>

                </div>

                <div id="minisite_events{$item.shop_event_id}" style="" class="TopAddevent"></div>
            </td>
        </tr>
    </tbody>
</table>
<!-- fb post to wall -->           
<div style="margin-top:-28px;margin-left:4px">
    {modurl modname='ZSELEX' type='user' func='viewevent' eventId=$item.shop_event_id shop_id=$item.shop_id assign="evnt_link"}
    {assign var="event_link" value="`$baseurl`$evnt_link"}
    {*  {fblikeservice action='like' url=$event_link  width="500px" height="21px" layout='horizontal' shop_id=$item.shop_id   addmetatags=true metatitle=$item.shop_event_name metatype="website" metaimage=$evnt_image description=$item.shop_event_description faces=true}*}
  {*  {fbpostonwall shop_id=$item.shop_id  link=$event_link image=$evnt_image title=$item.shop_event_name caption='' description=$item.shop_event_description id=$key} *}
     {fbshare shop_id=$item.shop_id  url=$event_link}
</div>
<!-- -->    



{securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_EDIT}
{if !$expired}
{if !$image_service.expired}
<script type="text/javascript">
    /* jQuery('#minisite_events{{$item.shop_event_id}}').ajaxupload({
url:document.location.pnbaseURL+"index.php?module=ZSELEX&type=ajax&func=upload_minisite_event",
//allowExt:['jpg','JPG'],
remotePath: document.getElementById('uploadpath').value ,
dropArea:'#drophere{{$item.shop_event_id}}' ,
autoStart:true,
//editFilename:true,
data : {{$item.shop_event_id}},
maxFiles:1,
form:'#zselex_event_form'
});*/
        
        
    jQuery('#minisite_events{{$item.shop_event_id}}').ajaxupload({
        url:document.location.pnbaseURL+"index.php?module=ZSELEX&type=Dnd&func=upload_event",
        data:"shop_id={{$smarty.request.shop_id}}&purpose=edit&event_id={{$item.shop_event_id}}&file_check_folder=fullsize",
        editFilename:true,
        allowExt:['jpg','JPG','jpeg','JPEG','pdf','doc','docx','png','PNG'],
        maxFiles:{{$event_limit}},
        dropArea:'#drophere{{$item.shop_event_id}}',
        dropColor: 'red',
        autoStart: true,
        remotePath:document.getElementById('uploadpath').value + "events",
        //form:'#zselex_bulkaction_product_form',
        
        finish:function(files, filesObj){
           
            window.location.href='';
        },
       
        error:function(txt, obj){
            //alert(txt);
            alert(Zikula.__('Cannot upload image' , 'module_zselex_js'));
        }
				
    });

    function sizeExceeds(){
        return 0
    }
</script>  
{/if}
{/if}
{/securityutil_checkpermission_block}
{foreachelse}  
{gt text='No Events Found'}
{/foreach}

{if !$expired AND $event_perm}
{section name=foo loop=$events_left} 
<table class="options newevent">
    <tbody>
        <tr>
            <td>
                <div onclick="editEvent('new');"  id="drophereNew{$smarty.section.foo.iteration}" style="width:620px;height:150px; cursor:pointer; background: #e8e8e8; border-radius: 8px;  text-align: center; color: #707070; font-size: 16px; padding-top: 40px;  ">
                    <div style="margin:auto; width:400px; "><img src="{$baseurl}modules/ZSELEX/images/down_grey.png" class="left"><p style="width:auto">{gt text='Drag and Drop Event image or document to<br> this box or just click anywhere inside the box'}</p>{gt text='OR'}</div>
                </div>

                <div id="create_events{$smarty.section.foo.iteration}" class="BottomAddImage"></div>
            </td>
        </tr>
    </tbody>
</table>
           
{if !$image_service.expired}
<script type="text/javascript">
    /* jQuery('#create_events{{$smarty.section.foo.iteration}}').ajaxupload({
        url:document.location.pnbaseURL+"index.php?module=ZSELEX&type=ajax&func=upload_minisite_event",
        //allowExt:['jpg','JPG'],
        remotePath: document.getElementById('uploadpath').value ,
        dropArea:'#drophereNew{{$smarty.section.foo.iteration}}' ,
        autoStart:true,
        //editFilename:true,
        data : 'new',
        maxFiles:1,
        form:'#zselex_event_form'
    });*/
        
                    
    jQuery('#create_events{{$smarty.section.foo.iteration}}').ajaxupload({
        url:document.location.pnbaseURL+"index.php?module=ZSELEX&type=Dnd&func=upload_event&file_check_folder=fullsize",
        data:"shop_id={{$smarty.request.shop_id}}&purpose=create",
        allowExt:['jpg','JPG','jpeg','JPEG','pdf','doc','docx','png','PNG'],
        editFilename:true,
        maxFiles:{{$event_limit}},
        dropArea:'#drophereNew{{$smarty.section.foo.iteration}}',
        dropColor: 'red',
        autoStart: true,
        remotePath:document.getElementById('uploadpath').value + "events",
        //form:'#zselex_bulkaction_product_form',
        
        finish:function(files, filesObj){
            deleteExtraEvents();
            window.location.href='';
        },
        beforeUpload: function(filename, fileobj){
         // alert(fileobj.size); exit();
          //diskquoataExceeded('{{$smarty.request.shop_id}}' , fileobj.size);
           var sizeExceeded = 0;
          //var sizeExceeded = document.getElementById('exceeded').value;
         // alert(sizeExceeded); exit();
            if(sizeExceeded){
                return false; //file will not be uploaded
            }
            else
            {
       
                return true; //file will be uploaded
            }
        },
        error:function(txt, obj){
            alert(Zikula.__('Cannot upload : ' + txt , 'module_zselex_js'));
        }
				
    });
</script>  
{/if}
{/section}
{/if}
   
<div class="z-buttons z-formbuttons">
    <a href="{modurl modname="ZSELEX" type="user" func="site" shop_id=$smarty.request.shop_id}" title="{gt text="Back"}">{img modname='ZSELEX' src="icon_cp_backtoshoplist.png" __alt="Back" __title="Back"} {gt text="Back"}</a>
</div>


<!--</form>-->
<div id="editEvent" class="basket_content" style="display:none">

</div>



{adminfooter}




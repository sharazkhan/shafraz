{ajaxheader modname='ZSELEX' filename='zselex_admin.js' ui=true}

{shopheader}

<script type="text/javascript" src="modules/ZSELEX/javascript/dndfiles/jquery.js"></script>
<script type="text/javascript" src="modules/ZSELEX/javascript/dndfiles/ajaxupload.js"></script>
<link href="modules/ZSELEX/style/dndcss/classicTheme/style.css" rel="stylesheet" type="text/css" />



{if !$bannerExist}
     {if $servicecount > 0 AND !$servicedisable}
<div class="z-admin-content-pagetitle">
    <a href="{modurl modname="ZSELEX" type="admin" func="createbanner" shop_id=$smarty.request.shop_id}" class="z-iconlink z-icon-es-add">Create Banner</a>
</div>

 <input type="hidden" id="servicelimit" value="1" />
 <input type="hidden" id="quantity" value="{$quantity}" />
 <input type="hidden" id="uploadpath" value="{$uploadpath}" />
 <input type="hidden" id="shop_id" name="shop_id" value="{$smarty.request.shop_id}" />
<table class="options">
    <tbody>
         <tr>
                 <td>
                         <div id="drophere" style="width:580px;height:200px;border: 1px solid black;">{gt text='Drag and Drop files here'}</div>
                         <div id="minisite_banner" style="width:500px"></div>

                 </td>

         </tr>
    </tbody>
</table>
     {/if}
{/if}



<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='Shop Banner'}</h3>
</div>


<form class="z-form" id="zselex_bulkaction_minisitbanner_form" action="" method="post">
     <div style="overflow:auto;">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
       <table id="zselex_admintable" class="z-datatable">
            <thead>
                <tr>
                   <!--   <th></th> -->
                    {if !$expired AND !$servicedisable}
                    <th>{gt text='Actions'}</th>
                    {/if}
                    <th>{gt text='Banner'}</th>
                   
                </tr>
            </thead>
            <tbody>
             
                {if $bannerExist}
                <tr class="{cycle values='z-odd,z-even'}">
                      {if !$expired AND !$servicedisable}
                    <td>
                        {assign var='options' value=$banner.options}
                        {section name='options' loop=$options}
                        <a href="{$options[options].url|safetext}">{img modname='core' set='icons/extrasmall' src=$options[options].image title=$options[options].title alt=$options[options].title class='tooltips'}</a>
                        {/section}
                    </td>
                    {/if}
                    <td><img width="400" height="200" src="zselexdata/{$ownername}/banner/resized/{$banner.banner_image|safetext}"></td>
                    
                </tr>
                {else}
                    <tr class="{cycle values='z-odd,z-even'}">
                       
                    </tr>
                    
                 {/if}
                
            </tbody>
        </table>
       
    </div>
</form>
                 
<script type="text/javascript">
      jQuery('#minisite_banner').ajaxupload({
              url:document.location.pnbaseURL+"index.php?module=ZSELEX&type=ajax&func=upload_minisite_banner",
              allowExt:['jpg','JPG','png','gif'],
              remotePath: document.getElementById('uploadpath').value ,
              dropArea:'#drophere' ,
              editFilename:true,
              maxFiles:1,
              form:'#zselex_bulkaction_minisitbanner_form'
      });
</script>




{adminfooter}

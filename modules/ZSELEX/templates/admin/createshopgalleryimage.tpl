
{shopheader}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/jqueryfordragdrop.js'}
{if $id neq ''}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/dragdropimagesedit.js'}
{else}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/dragdropimagescreate.js'}
 {/if}
<link href="modules/ZSELEX/style/dragdrop.css" rel="stylesheet" type="text/css" />
<div class="z-admin-content-pagetitle">
 {if $id neq ''}
        <h3>{gt text='Update Shop Gallery Image'}</h3>
        {else}
    	<h3>{gt text='Create Shop Gallery Image'}</h3>
        {/if}
</div>

<form class="z-form" action="{modurl modname="ZSELEX" type="admin" func=$func}" method="post" enctype="multipart/form-data">
    <div>
        
    <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
    <input type="hidden" id="shop_id" name="shop_id" value="{$shop_id}" />
  
    {if $id neq ''}
    <input type="hidden" id="url" name="url" value="updategalleryImage" />
    {else}
    <input type="hidden" id="url" name="url" value="creategalleryImage" />
   {/if}
    <input type="hidden" id="sectype" name="sectype" value="create" />
    <input type="hidden" name="gallery_id" value="{$id}" />
    <input type="hidden" name="id" value="{$id}" />
     <input type="hidden" id="item_id" name="id" value="{$id}" />
    <input type="hidden" id="existingImage" name="existingImage" value="{$item.image_name}" />
    <fieldset>
        <legend>{gt text='Create Type'}</legend>
        <div class="z-formrow">
            <label for="description">{gt text='Image Description'}</label>
          
            <textarea name="description" >{$item.image_description|safetext}</textarea>
        </div>
        <div class="z-formrow">
            <label for="keywords">{gt text='Keywords'}</label>
          
            <textarea name="keywords" >{$item.keywords|safetext}</textarea>
        </div>
       
        <div class="z-formrow">
            <label for="simage">{if $id eq ''}{gt text='Upload Image'}{else}{gt text='Change Image'}{/if}</label>
            <input type="file" name="simage" id="simage">
        </div>
           
        {if $id neq ''}
        <div class="z-formrow">
          <label for="simage"></label>
          <img src="{$baseurl}zselexdata/{$ownerName}/minisitegallery/thumb/{$item.image_name}"></img>
        </div>
        {/if}
    </fieldset>
    <div class="z-buttons z-formbuttons">
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save" __name="typetable[action]" __value="save"}
        <a href="{modurl modname="ZSELEX" type="admin" func='viewshopgalleryimages' shop_id=$smarty.request.shop_id}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    </div>
     <div class="z-formrow" id="or">
        <label for=""></label>
          <font size="3"><b>{gt text='OR'}</b></font>
       </div>
     <div class="z-formrow">
         <label for=""></label>
    <div id="uploaddone"></div>
    </div>
     <div class="z-formrow">
         <label for=""></label>
    <div id="limover12"></div>
    </div>
</form>
        
 <div id="dragimage" style="padding-left:180px">  
  <form class="z-form" action="{modurl modname="ZSELEX" type="admin" func=$func}" method="post" enctype="multipart/form-data">
    <fieldset>
   
     <div id="limover"></div>
     <div class="content">
           <div id="loading">
                <div id="loading-bar">
                    <div class="loading-color"> </div>
                </div>
                <div id="loading-content">{gt text='Uploading file.jpg'}</div>
            </div>
            <div id="drop-files" ondragover="return false">
                {gt text='Drop Images Here'}
            </div>

            <div id="uploaded-holder">
                <div id="dropped-files">
                    <div id="upload-button">
                        <a href="#" class="upload">{gt text='Upload!'}</a>
                        <a href="#" class="delete">{gt text='Delete'}</a>
                        <span>{gt text='0 Files'}</span>
                    </div>
                </div>
                <div id="extra-files">
                    <div class="number">
                        0
                    </div>
                    <div id="file-list">
                        <ul></ul>
                    </div>
                </div>
            </div>

          

            <div style="display:none" id="file-name-holder">
                <ul id="uploaded-files">
                    <h1>{gt text='Uploaded Files'}</h1>
                </ul>
            </div>
        </div>
       
    
    </fieldset>
    </form>
     </div>
{adminfooter}

{shopheader}

<div class="z-admin-content-pagetitle">
 {if $id neq ''}
        <h3>{gt text='Update Shop Image'}</h3>
        {else}
    	<h3>{gt text='Create Shop Image'}</h3>
        {/if}
</div>

<form class="z-form" action="{modurl modname="ZSELEX" type="admin" func=$func}" method="post" enctype="multipart/form-data">
    <div>
        
    <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
    <input type="hidden" id="shop_id" name="shop_id" value="{$shop_id}" />
    {if $id neq ''}
    <input type="hidden" id="url" name="url" value="updateImage" />
    {else}
    <input type="hidden" id="url" name="url" value="createImage" />
   {/if}
    <input type="hidden" id="sectype" name="sectype" value="create" />
    <input type="hidden"  id="file_id" name="file_id" value="{$id}" />
    <input type="hidden" id="item_id" name="id" value="{$id}" />
    <input type="hidden" id="existingImage" name="existingImage" value="{$item.name}" />
    <fieldset>
        <legend>{gt text='Create Image'}</legend>
       
        <div class="z-formrow">
            <label for="description">{gt text='Image Description'}</label>
          
            <textarea name="description" {$disabled}>{$item.filedescription|safetext}</textarea>
        </div>
        
         <div class="z-formrow">
            <label for="keywords">{gt text='Keywords'}</label>
          
            <textarea name="keywords"  {$disabled}>{$item.keywords|safetext}</textarea>
        </div>
       
        <div class="z-formrow">
            <label for="simage">{if $id eq ''}{gt text='Upload Image'}{else}{gt text='Change Image'}{/if}</label>
            <input type="file"  name="simage" id="simage"  {$disabled} multiple>
        </div>
       
        {if $id neq ''}
        <div class="z-formrow">
            <label for="simage"></label>
          <img src="{$baseurl}zselexdata/{$ownerName}/minisiteimages/thumb/{$item.name}"></img>
        </div>
        {/if}
    </fieldset>
   {if $servicePermission > 0 AND !$servicedisable}
    <div class="z-buttons z-formbuttons">
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save" __disabled="disabled" __name="typetable[action]" __value="save"}
        <a href="{modurl modname="ZSELEX" type="admin" func='viewshopimages' shop_id=$smarty.request.shop_id}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    {/if}
    
    </div>
    
</form>
 
  
       
{adminfooter}
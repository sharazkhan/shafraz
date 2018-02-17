{pageaddvar name='javascript' value='modules/ZSELEX/javascript/zselex_admin_validation.js'}
{shopheader}
<div class="z-admin-content-pagetitle">
 {if $item.pdf_id neq ''}
        <h3>{gt text='Update Shop Pdf Image'}</h3>
        {else}
    	<h3>{gt text='Create Shop Pdf Image'}</h3>
        {/if}
</div>

<form class="z-form" action="{modurl modname="ZSELEX" type="admin" func=$func}" method="post" enctype="multipart/form-data">
    <div>
        
    <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
    <input type="hidden" name="shop_id" value="{$shop_id}" />
    <input type="hidden" name="pdf_id" value="{$id}" />
    <input type="hidden" name="id" value="{$id}" />
    <input type="hidden" name="exitingPdf" value="{$item.pdf_name}" />
    <input type="hidden" name="existingImage" value="{$item.pdf_image}" />
    <fieldset>
        <legend>{gt text='Upload PDF'}</legend>
        <div class="z-formrow">
            <label for="description">{gt text='Pdf Description'}</label>
          
            <textarea name="description" >{$item.pdf_description|safetext}</textarea>
        </div>
        
         <div class="z-formrow">
            <label for="keywords">{gt text='Keywords'}</label>
          
            <textarea name="keywords" >{$item.keywords|safetext}</textarea>
        </div>
       
        <div class="z-formrow">
            <label for="pdf_image">{if $id eq ''}{gt text='Upload Pdf'}{else}{gt text='Change Pdf'}{/if}</label>
            <input type="file" name="pdf_image" id="pdf_image">
        </div>
           
        {if $id neq ''}
        <div class="z-formrow">
            <label for="simage"></label>
          <img src="{$baseurl}zselexdata/{$ownerName}/pdfupload/thumb/{$item.pdf_image}.jpg"></img>
        </div>
        {/if}
    </fieldset>
    <div class="z-buttons z-formbuttons">
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save" __name="typetable[action]" __value="save"}
        <a href="{modurl modname="ZSELEX" type="admin" func='viewshoppdf' shop_id=$smarty.request.shop_id}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    </div>
</form>
{adminfooter}
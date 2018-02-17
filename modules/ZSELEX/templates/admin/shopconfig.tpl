{ajaxheader ui=true}
{ajaxheader imageviewer="true"}
{pageaddvarblock}
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        Zikula.UI.Tooltips($$('.tooltips'));
    });
    
    function uncheck()
    {
        document.getElementById('mainshopyes').checked = false;        
    }
    function check()
    {
        document.getElementById('mainshopyes').checked = true;        
    }
    
</script>
{/pageaddvarblock}
{shopheader}
<div class="z-admin-content-pagetitle">
    {icon type="config" size="small"}
    <h3>{gt text='Shop settings'}</h3>
</div>
{$modvars.ZSELEX.shopDefaultImage $shop_id}
<form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="updateshopconfig" shop_id=$shop_id}" method="post" enctype="application/x-www-form-urlencoded">
      <div>
        <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
               <input type="hidden" name="user_id" value="{$obj.user_id}" />
        <input type="hidden" name="owner_id" value="{$owner}" />
        <fieldset>
            <legend>{gt text='Set Shop Default Image'}</legend>
            <div class="z-formrow">

                <label for="default_img_frm1">{gt text='From Shop Default Image'}</label>
                <div>
                    <input type="radio" value="fromshop" id="default_img_frm1" name="default_img_frm" {if $obj.default_img_frm eq fromshop} checked="checked"{/if}/>
                </div>
                <label for="default_img_frm2">{gt text='From Gallery Default Image'}</label>
                <div>
                    <input type="radio" value="fromgallery" id="default_img_frm2" name="default_img_frm" {if $obj.default_img_frm eq fromgallery} checked="checked"{/if}/>
                </div>

            </div>
        </fieldset>

        {if $ownerExist gt 0}                    
        <fieldset>
            <legend>{gt text='Set as main shop'}</legend>
            <div class="z-formrow">

                <label for="mainshopyes"></label>
                <div>
                    <input type="radio" onclick='radioCheck()' value="1" id="mainshopyes" name="mainshop" {if $obj.main eq 1} checked="checked"{/if}/>

                           <input type="button"  onclick="check();" value="set">
                    <input type="button" onclick="uncheck();" value="unset">
                </div>


            </div>
        </fieldset>
        {/if}


        <fieldset>
            <div class="z-formrow">
                <label for="shop_info">{gt text='Shop Information'}</label>
                <textarea  name='shop_info' id='shop_info' >{$obj.shop_info}</textarea>
            </div>

        </fieldset>


        <fieldset>
            <div class="z-formrow">
                <label for="article">{gt text='Select Article'}</label>
                <select {if !$articleServiceExist} disabled {/if} name='formElements[article]' id='article' class="icon-menu" >
                    <option style="padding-left:0px;" value="">{gt text='Select Article'}</option>
                    {foreach item='article' from=$articles}
                    <option  value="{$article.sid}" {if $item.news_article_id eq $article.sid} selected='selected' {/if}> {$article.title} </option>
                    {/foreach}
                </select>
            </div>
        </fieldset>

        <fieldset>
            <div class="z-formrow">
                <label for="opening_hours">{gt text='Opening Hours'}</label>
                <textarea  name='opening_hours' id='opening_hours' >{$obj.opening_hours}</textarea>
            </div>

        </fieldset>


        {*
        {if $servicePerm gt 0}
        <fieldset>

            <legend>{gt text='Select Design'}</legend>
            <div class="z-formrow">

                <table width="50%" align="center" style="padding-left: 200px">
                    <tr>
                        <td><b>{gt text='Theme'}</b></td>
                        <td><b>{gt text='Preview'}</b></td>
                        <td></td>
                    </tr>

                    {if !empty($designs)}
                    {foreach  item='design' key='id' from=$designs}
                    {assign var="images" value="themes/`$design`/images/preview_large.png"}
                    <tr>
                        <td>
                            {$design}    
                        </td>

                        <td> {if file_exists($images)}
                            <a id="{$id}" rel="imageviewer[galleryDesign]" href="{$baseurl}themes/{$design}/images/preview_large.png"  title="{$design|safetext}">{gt text='Preview'}</a>
                            {else}
                            {gt text='not available'}
                            {/if}

                        </td>
                        <td><input type="radio" {if $obj.theme eq $design} checked="checked" {/if} name="shopdesign" id="{$design}"  value="{$design}" /></td>
                    </tr>
                    {/foreach}
                    {else}  

                    {/if}
                </table>
            </div>

        </fieldset>
        {/if}

        *}




        <div class="z-buttons z-formbuttons">
            {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
            <a href="{modurl modname="ZSELEX" type="admin" func='shopinnerview' shop_id=$smarty.request.shop_id}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
        </div>
    </div>
</form>
{adminfooter}

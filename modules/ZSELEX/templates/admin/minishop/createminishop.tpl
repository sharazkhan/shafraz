
{shopheader}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/validation.js'}
{jsvalidator}
<script>

jQuery(function() {
//alert('hiii');
   var docHeight = jQuery(document).height();

   jQuery("#disablingDiv").append("<div id='overlay'></div>");

   jQuery("#overlay")
      .height(docHeight)
      .css({
         'opacity' : 0.4,
         'position': 'absolute',
         'top': 0,
         'left': 0,
         'background-color': 'black',
         'width': '100%',
         'z-index': 5000
      });

});

</script>

<style>

#disablesdivw{
    
        background:#777; 
        opacity: 0.1;
        z-index:988;
        width:100%; 
        height:270px;
        border-radius:4px; margin-top:-252px; margin-bottom:0px;
     
    
    }



</style>

    <div class="z-admin-content-pagetitle">
     {if $item.shop_id neq ''}
        <h3>{gt text='Update Mini Shop'}</h3>
        {else}
    	<h3>{gt text='Configure Mini Shop'}</h3>
        {/if}
    </div>
 
    <form id="minishopForm" class="z-form" action="{modurl modname="ZSELEX" type="admin" func="minishop" shop_id=$smarty.request.shop_id}" method="post" enctype="multipart/form-data">
    <div id="mydiv">
                <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
                <input type="hidden" name='formElements[minishop_id]' value="{$minishop.id}" />
                <input type="hidden" name='formElements[shop_id]' value="{$smarty.request.shop_id}" />
                <input type="hidden" name='formElements[configShopId]' id="configShopId" value="{$item.shoptype_id}" />
     
            <fieldset>
            <div class="z-formrow">
                <label for="elemtName">{gt text='Name'}</label>
                <input  type='text' name='formElements[elemtName]' id='elemtName' value='{$minishop.minishop_name}'   />
            </div>
            <div class="z-formrow">
                <label for="elemtDesc">{gt text='Description'}</label>
                <textarea  name='formElements[elemtDesc]' id='elemtDesc' >{$minishop.description}</textarea>
            </div>
            <div id="ecom">
            <div class="z-formrow">
                <label for="ecommerce">{gt text='Shop type'}</label>
                <select class="validate-selection" title="{gt text='Shop type required'}" name='formElements[shoptype]' id='ecommerce' onChange='shoptypes(this.value)'>
                <option value='0'>select</option>
                {foreach item='shoptype' from=$shoptypes}
                <option value="{$shoptype.shoptype}" {if $shoptype.shoptype eq $minishop.shoptype}selected{/if}> {$shoptype.shoptype} </option>
                {/foreach}
                </select>
            </div>
            </div>
            <div id="zshop" {if $minishop.shoptype eq 'zSHOP'} style="display:block;" {else} style="display:none;" {/if} >
            <div class="z-formrow">
                <label for="ecomDomain">{gt text='Domain'}</label>
                <input type='text'  name='formElements[domain]' id='ecomDomain' value='{$minishop.zshop.domain}'   />	
            </div>
            
            <div class="z-formrow">
                <label for="ecomHost">{gt text='Host'}</label>
                <input type='text'  name='formElements[host]' id='ecomHost' value='{$minishop.zshop.hostname}'   />	
            </div>
            
            <div class="z-formrow">
                <label for="ecomDb">{gt text='Database'}</label>
                <input type='text'  name='formElements[database]' id='ecomDb' value='{$minishop.zshop.dbname}'   />	
            </div>
            
            <div class="z-formrow">
                <label for="ecomUser">{gt text='User Name'}</label>
                <input type='text'  name='formElements[username]' id='ecomUser' value='{$minishop.zshop.username}'   />	
            </div>
            
            <div class="z-formrow">
                <label for="ecomPswrd">{gt text='Password'}</label>
                <input type='text'  name='formElements[password]' id='ecomPswrd' value='{$minishop.zshop.password}'   />	
            </div>
            <div class="z-formrow">
                <label for="table_prefix">{gt text='Table Prefix'}</label>
                <input type='text'  name='formElements[tableprefix]' id='table_prefix' value='{$minishop.zshop.table_prefix}' />	
            </div>
            </div>
             <div class="z-formrow">
                <label for="typestatus">{gt text='Status'}</label>
                <select id="typestatus" name="formElements[status]" />
                      <option value="1" {if $item.status eq '1'} selected='selected' {/if}>{gt text='Active'}</option>
                      <option value="0" {if $item.status eq '0'} selected='selected' {/if}>{gt text='InActive'}</option>
                </select>
           </div>
             
            </fieldset>
        {if $error < 1}
              {if !$expired AND !$servicedisable}
        <div class="z-buttons z-formbuttons">
             <button id="zselex_button_submit"  class="z-btgreen" type="submit" onclick="return validate_shop();" name="action" value="1" title="{gt text='Save'}">
            {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save this Region'}
             {gt text='Save'}
            </button>
            <a id="zselex_button_cancel" href="{modurl modname="ZSELEX" type="admin" func='viewshop'}" class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
        </div>
        {/if}
         {/if}
		</div>
            <div id="disablesdiv" style=""></div>
            
             <input type="hidden" name="totalcounts" id="totalcounts" value="0"/>
             <input type="hidden" name="totalcount" id="totalcount" value="0"/>
	</form>
            
                 <style>
            .validation-advice{
               margin-left:152px;
                }
            </style>
                <script>
                 var valid = new Validation('minishopForm', {useTitles:true});
                </script>

{adminfooter}
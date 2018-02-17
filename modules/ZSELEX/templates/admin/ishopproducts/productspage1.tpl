

{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='zikula.ui'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/dropdownlist/jquery-1.6.1.min.js'}

{*{pageaddvar name='javascript' value='modules/ZSELEX/javascript/currency/accounting.min.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/currency/accounting.js'}*}
{*{ajaxheader modname='ZSELEX' filename='product_admin.js' ui=true}*}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/product_admin.js'}


<!------------------ DND PLUGIN --------------------------------------->
{*{pageaddvar name='javascript' value='modules/ZSELEX/javascript/DND/jquery.js'}*}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/DND/ajaxupload-min.js'}
{pageaddvar name="stylesheet" value="modules/ZSELEX/style/DND/classicTheme/style.css"}
<!---------------------------------------------------------------------->

{pageaddvar name="stylesheet" value="themes/$current_theme/style/product_dnd.css"}

{pageaddvar name='javascript' value='modules/ZSELEX/javascript/jquery.jscroll.min.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/jquery.jscroll.js'}


{pageaddvar name="stylesheet" value="modules/ZSELEX/javascript/dropdownlist/jquery-ui-1.8.13.custom.css"}
{pageaddvar name='stylesheet' value='modules/ZSELEX/javascript/dropdownlist/ui.dropdownchecklist.themeroller.css'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/dropdownlist/jquery-ui-1.8.13.custom.min.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/dropdownlist/ui.dropdownchecklist.js'}

{pageaddvar name='javascript' value='modules/ZSELEX/javascript/lazyload/lazyload.js'}
<style>

    .basket_content {
       background-color: white;
        /* border: 5px solid #DD511D;*/
        
        min-height: 50px;
        /*margin-left: -270px;*/
        /* overflow: hidden;*/
        height: auto;
        padding: 20px;
        min-width: 500px;
        max-width: 800px;
        max-height: 600px;
        z-index: 10002;
        margin:auto;
        margin-top:20px;
        overflow:auto;
      
    }
    
    .cat_content {
       background-color: white;
        /* border: 5px solid #DD511D;*/
        left: 50%;
        min-height: 70px;
        margin-left: -270px;
        /* overflow: hidden;*/
        height: auto;
        position: absolute;
        padding: 20px;
        top: 10%;
        width: 500px;
        z-index: 10002;
    }
    .backshield {
        background-color: #333333;
        height: 300%;
        left: 0;
        opacity: 0.8;
        position: absolute;
        top: 0;
        width: 100%;
        z-index: 1000;
    }

    {*.ax-legend{display:none;} 
    .ax-button{display:none;} 
    .aj-frame{border:none !important; background: none !important}*}
    
     .ax-legend{display:none;} 
     .aj-frame{border:none !important; background: none !important}
     .ax-upload-all{display:none;} 
     .ax-clear{display:none;}
      .ax-button {color:#000; border:none; background:none; box-shadow:none;}
     {* .ax-button{font-size:1.2em}*}
      a.ax-button:hover{color:#000;}
      a.ax-button:visted{color:#000;}
     .OrageEvenTextR{ padding-top:5px}
     .ax-browse{border:none;}
     .ProductBottomOrange{height:51px;}
     .TopAddFile a.ax-button{ color:#FFF;  margin-left: 497px; margin-top:-74px}
     .BottomAddImage a.ax-button {margin-left:270px; margin-top:-45px}
     span.ax-plus-icon{ display:none;}
      .ax-progress-info{height:auto}
.ax-button{
    background: linear-gradient(180deg, rgb(164, 164, 164) 30%, rgb(145, 145, 145) 60%) repeat scroll 0 0 rgba(0, 0, 0, 0);
    border: 2px solid #8b8b8b;
    border-radius: 4px;
    color: #fff;
    cursor: pointer;
    font-weight: bold;
   }
  a.ax-button:hover{opacity:1;text-decoration:none}
</style>
<div id="backshield" class="backshield" style="min-height: 12000px;height:auto;display:none"></div> 

<div id="editProductOuter" style="display:none;position:absolute; z-index: 9999; width: 100%; height: 100%; top: -5%; left:0px;">

    <div id="editProduct" class="basket_content" style="display:none">
</div>
  
    </div>



<div id="editCategory" class="cat_content" style="display:none">

</div>
<div id="editManufacturer" class="cat_content" style="display:none">

</div>
<div id="showProducts" class="cat_content" style="display:none">

</div>


<script>
     jQuery(document).ready(function() {
     // editProduct('218');
      jQuery("img.lazy").lazyload();
      });
      
      
      
    </script>

<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='Products'}</h3>
</div>


{securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_EDIT}

{if $serviceerror < 1 AND !$servicedisable}

<input type="hidden" id="servicelimit" value="{$servicelimit}" />
<input type="hidden" id="quantity" value="{$quantity}" />
<input type="hidden" id="uploadpath" value="{$uploadpath}" />
<input type="hidden" id="shop_id" name="shop_id" value="{$smarty.request.shop_id}" />
<input type="hidden" id="startnum" name="startnum" value="{$smarty.request.startnum}" />
<span id="prod_error"></span>
<table class="options">
    <tbody>
        <tr>
            <td>
                <div id="drophere" class="ProductDiv">
                   {* {gt text='Drag and Drop minishop products here'} *}
                </div>
                 <div class="ProductBottomOrange">
                        <div class="OrageProductTextL left"></div>

                        <div class="OrageProductTextR right" style="padding-right:124px;padding-top:17px">{gt text='Drag and Drop Product Image'}</div>

                    </div>
                <div id="product_images" style="width:500px" class="TopAddFile"></div>

            </td>

        </tr>
    </tbody>
</table>
{/if} 


{/securityutil_checkpermission_block}


{if $servicecount > 0 AND !$servicedisable}
<form class="z-form" id="product_filter" name="product_filter" action="{modurl modname='ZSELEX' type='admin' func='products' shop_id=$smarty.request.shop_id}" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset id="zselex_multicategory_filter"{if $filter_active} class='filteractive'{/if}>
              {if $filter_active}{gt text='active' assign=filteractive}{elseif $filter_active neq ''}{gt text='inactive' assign=filteractive}{else $filter_active eq ''}{gt text='All' assign=filteractive}{/if}
              <legend>{gt text='Filter %1$s, %2$s product listed' plural='Filter %1$s, %2$s products listed' count=$total_products tag1=$filteractive tag2=$total_products}</legend>

        <input type="hidden" name="startnum" value="{$startnum}" />
        <input type="hidden" name="order" value="{$order}" />
        <input type="hidden" name="sdir" value="{$sdir}" />
        <input type="hidden" name="category" value="{$category}" />
        <input type="hidden" name="manufacturer" value="{$manufacturer}" />
        <label for="searchtext">{gt text='Product Name'}</label>
        <input type="text" name="searchtext" value="{$searchtext}" />
        <label for="status">{gt text='Status'}</label>
        {html_options name='status' id='status' options=$itemstatus selected=$status}
        &nbsp;
        &nbsp;&nbsp;
          <label for="cat">{gt text='Category'}</label>
         <select id="cat" name="category">
                    <option value=''>{gt text='select category'}</option>
                    {foreach from=$categories  item='cat_item'}
                    <option value="{$cat_item.prd_cat_id}"  {if $cat_item.prd_cat_id eq $category} selected='selected' {/if}>{$cat_item.prd_cat_name|upper}</option>
                    {/foreach}
         </select>
        <br><br>
         <label for="manufacturer">{gt text='Manufacturers'}</label>
         <select id="manufacturer" name="manufacturer">
                    <option value=''>{gt text='select manufacturer'}</option>
                    {foreach from=$manufacturers  item='mnf_item'}
                    <option value="{$mnf_item.manufacturer_id}"  {if $mnf_item.manufacturer_id eq $manufacturer} selected='selected' {/if}>{$mnf_item.manufacturer_name|upper}</option>
                    {/foreach}
         </select>
         <br><br>
        <span class="z-nowrap z-buttons">
            <input class='z-bt-filter' name="submit" type="submit" value="{gt text='Filter'}" />
            <a href="{modurl modname="ZSELEX" type='admin' func='products' shop_id=$smarty.request.shop_id}" title="{gt text="Clear"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Clear" __title="Clear"} {gt text="Clear"}</a>
        </span>

    </fieldset>
</form>
{/if}

<input type="hidden" id="hidden_url" value="{$baseurl}{modurl modname="ZSELEX" type='admin' func='products' shop_id=$smarty.request.shop_id}">
<script>
function close(){
     jQuery('#backshield').attr('style', 'display: none !important');
     document.getElementById("manage_content").style.display = 'none';
    if (history && history.pushState){
     history.pushState(null, null, jQuery('#hidden_url').val());
     }
    }
</script>
   
{if $smarty.request.manage neq ''}
    <style>
    .manage_content {
        background:url(images/ajax/loading.gif) center center no-repeat;
        background-color: white;
        left: 40%;
        margin-left: -270px;
        min-height:400px;
        height: auto;
        position: absolute;
        padding: 20px;
        top: 10%;
        width: 700px;
        z-index: 10002;
        display:inline-block
    }
    .backshield{
        display:block !important
    }
    #iFrameWrapper{
           min-height:400px;
           height:auto;
        }
        .inner_manage{width:800px !important}
     
      
    </style>
    <div class="manage_content" id="manage_content">
      
    {if $smarty.request.manage eq 'manufacturer'}
  <iframe id="iFrameWrapper" src="{modurl modname="ZSELEX" type='admin' func='manufacturers' shop_id=$smarty.request.shop_id}" width="100%" frameborder="0"></iframe>
    {elseif $smarty.request.manage eq 'prod_cat'}
   <iframe id="iFrameWrapper" src="{modurl modname="ZSELEX" type='admin' func='productCategories' shop_id=$smarty.request.shop_id}" width="100%" frameborder="0"></iframe>
   {elseif $smarty.request.manage eq 'productOption'}
   <iframe id="iFrameWrapper" src="{modurl modname="ZSELEX" type='admin' func='productOption' shop_id=$smarty.request.shop_id}" width="100%" frameborder="0"></iframe>
    {/if}
  <div class="z-buttons z-formbuttons">  
   <span  align="right" style="float:right;font-size: 15px"> 
       <a href="javascript:close()">
          {* {img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"}*}
          <img width="16" height="16" title="Back" alt="Back" src="{$themepath}/images/icon_cp_backtoshoplist.png">
          {gt text="Back"}
       </a>
   </span>
    </div>
   
    </div>
{/if}
<!--<form class="z-form" id="zselex_bulkaction_product_form" name="zselex_bulkaction_product_form" action="" method="post">-->
{*
 <span><a href="{modurl modname="ZSELEX" type='admin' func='productCategories' shop_id=$smarty.request.shop_id}"> {gt text="Manage Categories"} </a></span> |
 <span><a href="{modurl modname="ZSELEX" type='admin' func='manufacturers' shop_id=$smarty.request.shop_id}"> {gt text="Manage Manufacturers"} </a></span>
 *}
 {*
 <span><a href="{modurl modname="ZSELEX" type='admin' func='products' shop_id=$smarty.request.shop_id manage="prod_cat"}"> {gt text="Manage Categories"} </a></span> |
 <span><a href="{modurl modname="ZSELEX" type='admin' func='products' shop_id=$smarty.request.shop_id manage="manufacturer"}"> {gt text="Manage Manufacturers"} </a></span> |
 <span><a href="{modurl modname="ZSELEX" type='admin' func='products' shop_id=$smarty.request.shop_id manage="productOption"}"> {gt text="Product Options"} </a></span>
*}
 <a href="{modurl modname="ZSELEX" type='admin' func='products' shop_id=$smarty.request.shop_id manage="prod_cat"}">
     <button class="ProductPageBtn"  type="button">{gt text="Manage Categories"} </button>
 </a>
  <a href="{modurl modname="ZSELEX" type='admin' func='products' shop_id=$smarty.request.shop_id manage="manufacturer"}">
     <button class="ProductPageBtn"  type="button">{gt text="Manage Manufacturers"} </button>
 </a>
  <a href="{modurl modname="ZSELEX" type='admin' func='products' shop_id=$smarty.request.shop_id manage="productOption"}">
     <button class="ProductPageBtn"  type="button"> {gt text="Product Options"} </button>
 </a>
 
 <input type="hidden" id="tts">
  <select id='select_type'>
        <option value='0' selected='selected'>{gt text='With selected:'}</option>
        <option value='del'>{gt text='Delete'}</option>
   </select>
 <div style="overflow:auto;">
    <form class="z-form" id="product_form" action="" method="post">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <input type="hidden" id="url" name="url" value="createProductWithImage" />
        <input type="hidden" id="servicelimit" value="{$servicelimit}" />
        <input type="hidden" id="quantity" value="{$quantity}" />
        <input type="hidden" id="shop_id" value="{$smarty.request.shop_id}" />
        <table id="zselex_admintable" class="z-datatable">
            <thead>
                <tr>
                    <!--  <th></th> -->
                    {*
                    {if !$expired AND !$servicedisable}
                    <th>{gt text='Actions'}</th>
                    {/if}
                    *}
                    {* <th><a class='{$sort.class.product_id}' href='{$sort.url.product_id|safetext}'>{gt text='ID'}</a></th>*}
                    <th>#<input type="checkbox" id="select_all"></th>
                    <th><a class='{$sort.class.product_name}' href='{$sort.url.product_name|safetext}'>{gt text='Product Name'}</a></th>
                    <th>{gt text='Image'}</th>

                    <th>{gt text='Description'}</th>
                    <th>{gt text='Quantity'}</th>
                    <th>{gt text='Price'}</th>
                    <th>{gt text='Discount'}</th>
                    <th>{gt text='Status'}</th>
                   {*<th class="editth" style="display:block"></th>*}

                </tr>
            </thead>
            <tbody>
                {foreach from=$productItems item='item' key='key'}
                <tr id="{$item.product_id}" onClick="editProduct('{$item.product_id}')" style="cursor:pointer" class="{cycle values='z-odd,z-even'} prodTr prodTrs{$item.product_id}">
                    <!--  <td><input type="checkbox" name="zselex_selected_shops[]" value="{$shopitem.shop_id}" class="zselex_checkbox" /></td> -->

                    {*{if !$expired AND !$servicedisable}
                    <td>

                        {assign var='options' value=$item.options}
                        {section name='options' loop=$options}
                        <a href="{$options[options].url|safetext}">
                            {img modname='core' set='icons/extrasmall' src=$options[options].image title=$options[options].title alt=$options[options].title class='tooltips'}
                        </a>
                        {/section}

                    </td>
                    {/if}*}

                    {*  <td>{$item.product_id|safetext}</td>*}
                   <td>
                       <input type="checkbox" id="{$item.product_id}" class="prod_ids" name="prod_ids[]" value="{$item.product_id}">
                   </td>
                   
                    <td>
                        {$item.product_name|safetext} 
                       
                    </td>
                    <td>
                        {* <a id="{$item.file_id}" rel="imageviewer[productimage]" title="{$item.prd_image}" href="{$baseurl}zselexdata/{$ownerName}/products/fullsize/{$item.prd_image}">
                            <img src="{$baseurl}zselexdata/{$ownerName}/products/thumb/{$item.prd_image}">
                        </a>*}
                        <img class="lazy" data-original="{$baseurl}zselexdata/{$item.shop_id}/products/thumb/{$item.prd_image}">
                    </td>

                    <td>{$item.prd_description|safetext}</td>
                    <td>{$item.prd_quantity|safetext}</td>
                    <td>{displayprice amount=$item.prd_price|safetext}</td>
                    <td>{$item.discount|safetext}</td>

                    <td align="center" id="pstatus{$item.product_id}" >
                        {if $item.prd_status eq 1}
                        <img class="prd_status" style="cursor:pointer"  setstat=0 id="{$item.product_id}" src="{$baseurl}images/icons/extrasmall/greenled.png" >
                        {elseif $item.prd_status eq 0}
                        <img class="prd_status" style="cursor:pointer"  setstat=1 id="{$item.product_id}" src="{$baseurl}images/icons/extrasmall/redled.png" >    
                        {/if} 
                        {*{$aStatus[$item.prd_status]|safetext}*}
                    </td>
                    {* <td align="center" id="edit{$item.product_id}" class="edit" style="padding-top:25px;cursor:pointer;display:block">
                        <a href="" onClick="return editProduct('{$item.product_id}')">{gt text='Edit'}</a>
                    </td>*}
                    {*<td><a href="" onClick="return editProduct('{$item.product_id}')">{gt text='Edit'}</a></td>*}

                </tr>
                {*<tr id="edit{$item.product_id}" class="edit" onClick="editProducts('{$item.product_id}')" style="cursor:pointer;display:none">
                <td colspan="7" valign="center"><b>{gt text='Edit'}</b></td>
                </tr>*}
                <tr>
                    <td colspan="7">
                   {assign var="prod_image" value="`$baseurl`zselexdata/`$item.shop_id`/products/medium/`$item.prd_image`"}
                   {modurl modname='ZSELEX' type='user' func='productview' id=$item.product_id assign="prod_link"}
                   {assign var="product_link" value="`$baseurl`$prod_link"}
                  
                       {* {fbpostonwall id=$key shop_id=$smarty.request.shop_id link=$product_link image=$prod_image title=$item.product_name caption='' description=$item.prd_description} *}
                    {fbshare shop_id=$smarty.request.shop_id  url=$product_link}
                    </td>
                </tr>
                {foreachelse}
                <tr class="z-datatableempty"><td colspan="16">{gt text='No products found.'}</td></tr>
                {/foreach}
            </tbody>
        </table>
         </form>
    </div>
            
          

    <div class="z-buttons z-formbuttons">
        <a href="{modurl modname="ZSELEX" type="user" func="site" shop_id=$smarty.request.shop_id}" title="{gt text="Back"}">{img modname='ZSELEX' src="icon_cp_backtoshoplist.png" __alt="Back" __title="Back"} {gt text="Back"}</a>
    </div>

<!--</form>-->

<script type="text/javascript">
   function productSubmit(){
    // document.forms['product_filter'].submit();
    jQuery("#product_filter").submit();
   }
    
    jQuery('#product_images').ajaxupload({
        url:document.location.pnbaseURL+"index.php?module=ZSELEX&type=Dnd&func=upload_products",
        data:"shop_id={{$smarty.request.shop_id}}&file_check_folder=fullsize&product_cat_id={{$category}}&manufacturer_id={{$manufacturer}}",
        editFilename:true,
        maxFiles:{{$servicelimit}},
        dropArea:'#drophere',
        dropColor: 'red',
        autoStart: true,
        remotePath:document.getElementById('uploadpath').value + "products",
        form:'#product_filter',
        finish:function(files, filesObj){
           // window.location.href='';
          // productSubmit();
            //alert('all uploads done');
            deleteExtraProducts();
       
        },
     
        error:function(txt, obj){
           //alert(Zikula.__('Cannot upload : ' + txt , 'module_zselex_js'));
          //jQuery('#prod_error').html(Zikula.__('Cannot upload : ' + txt , 'module_zselex_js'));
           jQuery('#prod_error').html(Zikula.__('Cannot upload : Unexpected error occured'));
        }
				
    });
</script>
{pager rowcount=$total_products limit=$itemsperpage posvar='startnum'}

{adminfooter}


<script type="text/javascript">
    // <![CDATA[
  //  Zikula.UI.Tooltips($$('.tooltips'));
    // ]]>
</script>
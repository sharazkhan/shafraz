
{ajaxheader imageviewer="true"}
{if $smarty.request.shop_id neq ''}
{shopheader}
{else}
{adminheader}  
{/if}

{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='zikula.ui'}
{ajaxheader modname='ZSELEX' filename='product_admin.js' ui=true}
{*<script type="text/javascript" src="modules/ZSELEX/javascript/dndfiles/jquery.js"></script>
<script type="text/javascript" src="modules/ZSELEX/javascript/dndfiles/ajaxupload.js"></script>
<link href="modules/ZSELEX/style/dndcss/classicTheme/style.css" rel="stylesheet" type="text/css" />*}

<!------------------ DND PLUGIN --------------------------------------->
{*{pageaddvar name='javascript' value='modules/ZSELEX/javascript/DND/jquery.js'}*}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/DND/ajaxupload-min.js'}
{pageaddvar name="stylesheet" value="modules/ZSELEX/style/DND/classicTheme/style.css"}
<!---------------------------------------------------------------------->

<style>
   

    .basket_content {
        background-color: white;
        border: 5px solid #DD511D;
        left: 25%;
        min-height: 50px;
        overflow: hidden;
        height: auto;
        position: absolute;
        top: 1%;
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
    
    .ax-plus-icon
{
    /* background: url(icons.png) no-repeat 0px -332px;*/
   /* background: url(modules/ZSELEX/images/ikon_plus.png) no-repeat ;*/
}
</style>
<div id="backshield" class="backshield" style="height: 2157px;display:none" onClick='closeWindow();'></div> 
<div id="editProduct" class="basket_content" style="display:none">
</div>    
    
    
    

{securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADD}

<div class="z-admin-content-pagetitle">
    {if $serviceerror < 1 AND !$servicedisable}
    <a href="index.php?module=zselex&amp;type=admin&amp;func=addproducts&shop_id={$smarty.request.shop_id}" class="z-iconlink z-icon-es-add">{gt text='Create Product'}</a>
    {/if}
    &nbsp; &nbsp;
    <!--a href="index.php?module=zselex&amp;type=admin&amp;func=deleteIshop&mid={$minId}&shop_id={$smarty.request.shop_id}" class="z-iconlink z-icon-es-delete">{gt text='Unsubscribe from this Service'}</a-->
</div>

{if $serviceerror < 1 AND !$servicedisable}

<input type="hidden" id="servicelimit" value="{$servicelimit}" />
<input type="hidden" id="quantity" value="{$quantity}" />
<input type="hidden" id="uploadpath" value="{$uploadpath}" />
<input type="hidden" id="shop_id" name="shop_id" value="{$smarty.request.shop_id}" />

<table class="options">
    <tbody>
        <tr>
            <td>
                <div id="drophere" style="width:580px;height:200px;border: 1px solid black;">{gt text='Drag and Drop files here'}</div>
                <div id="product_images" style="width:500px"></div>

            </td>

        </tr>
    </tbody>
</table>
{/if} 


{/securityutil_checkpermission_block}

<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='New products list'}</h3>
</div>
{if $servicecount > 0 AND !$servicedisable}
<form class="z-form" id="shop_filter" action="{modurl modname='ZSELEX' type='admin' func='viewproducts' shop_id=$smarty.request.shop_id}" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset id="zselex_multicategory_filter"{if $filter_active} class='filteractive'{/if}>
              {if $filter_active}{gt text='active' assign=filteractive}{elseif $filter_active neq ''}{gt text='inactive' assign=filteractive}{else $filter_active eq ''}{gt text='All' assign=filteractive}{/if}
              <legend>{gt text='Filter %1$s, %2$s product listed' plural='Filter %1$s, %2$s products listed' count=$total_products tag1=$filteractive tag2=$total_products}</legend>

        <input type="hidden" name="startnum" value="{$startnum}" />
        <input type="hidden" name="order" value="{$order}" />
        <input type="hidden" name="sdir" value="{$sdir}" />
        <label for="searchtext">{gt text='Product Name'}</label>
        <input type="text" name="searchtext" value="{$searchtext}" />
        <label for="status">{gt text='Status'}</label>
        {html_options name='status' id='status' options=$itemstatus selected=$status}
        &nbsp;
        &nbsp;&nbsp;
        <span class="z-nowrap z-buttons">
            <input class='z-bt-filter' name="submit" type="submit" value="{gt text='Filter'}" />
            <a href="{modurl modname="ZSELEX" type='admin' func='viewproducts'}" title="{gt text="Clear"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Clear" __title="Clear"} {gt text="Clear"}</a>
        </span>

    </fieldset>
</form>
{/if}
<form class="z-form" id="zselex_bulkaction_product_form" name="zselex_bulkaction_product_form" action="" method="post">
    <div style="overflow:auto;">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <input type="hidden" id="url" name="url" value="createProductWithImage" />
        <input type="hidden" id="servicelimit" value="{$servicelimit}" />
        <input type="hidden" id="quantity" value="{$quantity}" />
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
                    <th><a class='{$sort.class.product_name}' href='{$sort.url.product_name|safetext}'>{gt text='Product Name'}</a></th>
                    <th>{gt text='Image'}</th>
                    <th>{gt text='Shop Name'}</th>
                    <th>{gt text='Description'}</th>
                    <th>{gt text='Keywords'}</th>
                    <th>{gt text='Price'}</th>
                    <th>{gt text='Status'}</th>
                    {*<th>{gt text='Created User'}</th> *}
                    <th><a class='{$sort.class.cr_date}' href='{$sort.url.cr_date|safetext}'>{gt text='Created'}</a></th>
                    {*<th>{gt text='Updated User'}</th>*}
                    <th><a class='{$sort.class.lu_date}' href='{$sort.url.lu_date|safetext}'>{gt text='Updated'}</a></th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$productItems item='item'}
                <tr class="{cycle values='z-odd,z-even'}">
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
                    <td>{$item.product_name|safetext} </td>
                    <td>
                       {* <a id="{$item.file_id}" rel="imageviewer[productimage]" title="{$item.prd_image}" href="{$baseurl}zselexdata/{$ownerName}/products/fullsize/{$item.prd_image}">
                            <img src="{$baseurl}zselexdata/{$ownerName}/products/thumb/{$item.prd_image}">
                        </a>*}
                        <img src="{$baseurl}zselexdata/{$ownerName}/products/thumb/{$item.prd_image}">
                    </td>

                    <td>{$item.shop_name|safetext} </td>

                    <td>{$item.prd_description|safetext}</td>
                    <td>{$item.keywords|safetext}</td>
                    <td>{$item.prd_price|safetext}</td>

                    <td align="center" id="pstatus{$item.product_id}">
                    {if $item.prd_status eq 1}
                    <img style="cursor:pointer" class="prd_status" setstat=0 id="{$item.product_id}" src="{$baseurl}images/icons/extrasmall/greenled.png" >
                   {elseif $item.prd_status eq 0}
                    <img style="cursor:pointer" class="prd_status" setstat=1 id="{$item.product_id}" src="{$baseurl}images/icons/extrasmall/redled.png" >    
                    {/if} 
                    </td>
                    {*  <td>{$shopitem.createduser|safetext}</td>  *}
                    <td>{$item.cr_date|safetext}</td>
                    {*  <td>{$shopitem.updateduser|safetext}</td>*}
                    <td>{$item.lu_date|safetext}</td>
                </tr>
                {foreachelse}
                <tr class="z-datatableempty"><td colspan="16">{gt text='No products found.'}</td></tr>
                {/foreach}
            </tbody>
        </table>
        <!-- 
        <p id='zselex_bulkaction_control'>
            {img modname='core' set='icons/extrasmall' src='2uparrow.png' __alt='doubleuparrow'}<a href="javascript:void(0);" id="zselex_select_all">{gt text="Check all"}</a> / <a href="javascript:void(0);" id="zselex_deselect_all">{gt text="Uncheck all"}</a>
            <select id='zselex_bulkaction_select' name='zselex_bulkaction_select'>
                <option value='0' selected='selected'>{gt text='With selected:'}</option>
                <option value='1'>{gt text='Delete'}</option>
                <option value='2'>{gt text='active'}</option>
            </select>
        </p>
        -->
    </div>
</form>
<script type="text/javascript">
    /*jQuery('#product_images').ajaxupload({
        url:document.location.pnbaseURL+"index.php?module=ZSELEX&type=ajax&func=upload_product_images",
        allowExt:['jpg' , 'png' , 'jpeg' , 'gif'],
        remotePath: document.getElementById('uploadpath').value ,
        dropArea:'#drophere',
        editFilename:true,
        maxFiles:{{$servicelimit}},
        form:'#zselex_bulkaction_product_form'
    });*/
    
    jQuery('#product_images').ajaxupload({
				url:document.location.pnbaseURL+"index.php?module=ZSELEX&type=ajax&func=upload_products",
                                data:"shop_id={{$smarty.request.shop_id}}",
			        editFilename:true,
                                maxFiles:{{$servicelimit}},
                                dropArea:'#drophere',
                                dropColor: 'red',
                                autoStart: true,
                                remotePath:document.getElementById('uploadpath').value + "products",
                                 //form:'#zselex_bulkaction_product_form',
                                 finish:function(files, filesObj){
                                 window.location.href='';
                                   },
                                error:function(txt, obj){
                                 //alert('An error occour '+ txt);
                                     }
				
			});
</script>
{pager rowcount=$total_products limit=$itemsperpage posvar='startnum'}

{adminfooter}


<script type="text/javascript">
    // <![CDATA[
    Zikula.UI.Tooltips($$('.tooltips'));
    // ]]>
</script>
<script type="text/javascript" src="modules/ZSELEX/javascript/combo/jquery-1.3.2.min.js"></script>
  <script type="text/javascript">
            $(document).ready(function() {

                var pw = window.opener;
                if(pw){
                       var dotName = pw.document.getElementById('elemtName').value;
                       var dotDate = pw.document.getElementById('dotddate').value;
                       document.getElementById('dotd_name').value=dotName;
                       document.getElementById('dotd_date').value=dotDate;
                        //alert(dotName);
                        //alert(dotDate);
                }


            });
</script>




<form class="z-form" id="shop_filter" action="{modurl modname='ZSELEX' type='admin' func='chooseZshopDodtProduct' shop_id=$smarty.request.shop_id}" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset id="zselex_multicategory_filter"{if $filter_active} class='filteractive'{/if}>
        {if $filter_active}{gt text='active' assign=filteractive}{elseif $filter_active neq ''}{gt text='inactive' assign=filteractive}{else $filter_active eq ''}{gt text='All' assign=filteractive}{/if}
        <legend>{gt text='Filter %1$s, %2$s product listed' plural='Filter %1$s, %2$s products listed' count=$total_count tag1=$filteractive tag2=$total_count}</legend>
        <input type="hidden" name="startnum" value="{$startnum}" />
        <input type="hidden" name="order" value="{$order}" />
        <input type="hidden" name="sdir" value="{$sdir}" />
         <input type="hidden" name="redirect" value="{$smarty.request.redirect}" />
         <input type="hidden" name='dotdId' value="{$smarty.request.dotdId}" />
        <label for="searchtext">{gt text='Product Name'}</label>
        <input type="text" name="searchproduct" value="{$searchproduct}" />
       
        &nbsp;
        &nbsp;&nbsp;
        <span class="z-nowrap z-buttons">
        
            <input class='z-bt-filter' name="submit" type="submit" value="{gt text='Filter'}" />
            <a href="{modurl modname="ZSELEX" type='admin' func='chooseZshopDodtProduct' shop_id=$smarty.request.shop_id}" title="{gt text="Clear"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Clear" __title="Clear"} {gt text="Clear"}</a>
        </span>
    </fieldset>
</form>

<form class="z-form" id="zselex_bulkaction_form" action="{modurl modname='ZSELEX' type='admin' func='chooseZshopDodtProduct' shop_id=$smarty.request.shop_id}" method="post">
    <div style="overflow:auto;">
         <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
         <input type="hidden" name='dotd_name' id='dotd_name' />
         <input type="hidden" name='dotd_date' id='dotd_date' />
         <input type="hidden" name="redirect" value="{$smarty.request.redirect}" />
         <input type="hidden" name='dotdId' value="{$smarty.request.dotdId}" />
         
       <table id="zselex_admintable" class="z-datatable">
            <thead>
                <tr>
                    <!--  <th></th> -->
                    <th><a class='{$sort.class.shop_id}' href='{$sort.url.shop_id|safetext}'>{gt text='ID'}</a></th>
                    <th><a class='{$sort.class.shop_name}' href='{$sort.url.shop_name|safetext}'>{gt text='Product Name'}</a></th>
                   
                    <th>{gt text='Image'}</th>
                     <th>
                      <div class="z-buttons z-formbuttons">
             <button id="zselex_button_submit"  class="z-btgreen" type="submit" onclick="return validate_shop();" name="saveproduct" value="1" title="{gt text='Save'}">
            {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save' }
             {gt text='Save'}
            </button>
           
            </div>
                   </th>
                    <th>{gt text='Actions'}</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$items item='item'}
                <tr class="{cycle values='z-odd,z-even'}">
                  
                    <td>{$item.products_id|safetext}</td>
                  
                    <td>{$item.products_name|safetext}</td>
                    <td>
                     <img src="http://{$item.domain}/images/{$item.products_image}" {if $item.H  neq  ''} height='{$item.H}'  width='{$item.W}' {else} width='170px'   {/if}>
                    </td>
                    <td><input type="radio" name="zenproduct" id="zenproduct" value="{$item.products_id}"></td>
                    <td></td>
                 
                </tr>
                {foreachelse}
                <tr class="z-datatableempty"><td colspan="16">{gt text='No products currently in database.'}</td>
                </tr>
                {/foreach}
            </tbody>
            </table>
      
    </div>
</form>


{pager rowcount=$total_count limit=$itemsperpage posvar='startnum'}

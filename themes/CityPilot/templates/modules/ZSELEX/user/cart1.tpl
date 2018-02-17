{pageaddvar name="stylesheet" value="themes/CityPilot/style/checkout.css"} 

     {insert name='getstatusmsg'}
     {include file="user/cartmenu.tpl"}<br><br>
    
      <div class="checkout_list_containter">    
                    <h2>{gt text='Shopping Cart'}</h2>
    
  
             {foreach  from=$products  key=cart_shop_id item=v}
                 
                  {assign var="outOfStock" value=0}
                  {setsellername value=$products key=$cart_shop_id}
                 
                    <form name='updatecart{$k}' action='index.php?module=zselex&type=user&func=updateUserCart&shop_id={$cart_shop_id}' method='post'>
                  <div style="margin-top:25px; margin-bottom: 5px; font-size: small; display: block; background: #f0f0f0; padding: 10px 5px;" class="txt-black45bold bluegrayboxld">
                      <b>{gt text='Seller'} : {$sellername} </b>&nbsp;&nbsp;|&nbsp;&nbsp;<b>{gt text='Shop'} : <a href='{modurl modname="ZSELEX" type="user" func="site" shop_id=$shop_id}'>{$shopname}</a></b>
                  </div>
                   <span id="cart_msg"></span>
                    <div class="checkouttableBlock">
                        
                        <table class="checkout_table_list">
                            <colgroup class="firstcol"/>
                            <colgroup class="ProductImage"/>
                            <colgroup class="ProductName"/>
                            <colgroup class="amount1"/>
                            <colgroup class="amount2"/>
                            <colgroup class="close"/>
                            <thead>
                                <tr>
                                    <th>{gt text='Quantity'}:</th>
                                    <th></th> 
                                    <th class="TextLeft">{gt text='Product'}:</th>
                                    <th>{gt text='Price'}:</th> 
                                    <th>{gt text='Total'}:</th>
                                    <th></th>    
                                </tr>
                            </thead>
                            <tbody>
                                 {foreach  from=$products.$cart_shop_id  key=k1 item=v1}  
                                 <tr>
                                    <td>
                                        <input autocomplete="off" name="quantity[{$v1.cart_id}]"  value='{$v1.quantity}' size='3' class="Quantity">&nbsp;&nbsp;<!--<img src="{$themepath}/images/refresh_chart.jpg" />-->
                                    </td>
                                    <td>
                                          <a href="{modurl modname="ZSELEX" type="user" func="productview" id=$v1.product_id}">
                                        {assign var="imagepath" value="zselexdata/`$cart_shop_id`/products/thumb/`$v1.prd_image`"}
                                        {if file_exists($imagepath)  && $v1.prd_image neq ''} 
                                        <img alt="{$v1.product_name}" src="zselexdata/{$cart_shop_id}/products/thumb/{$v1.prd_image}"  width="100" />
                                         {else}
                                        <img alt="{$v1.product_name}"  src="zselexdata/nopreview.jpg"  width="100" />
                                          {/if}  
                                          </a>
                                    </td> 
                                    <td class="TextLeft">{$v1.product_name|cleantext}{if $v1.outofstock}<font color="red">&nbsp;***</font>{/if}<br />
                                        <span class="font_Gray">{$v1.prd_description|cleantext}</span>
                                        {if $v1.cart_content!=''}
                                            <br>
                                            {displayoptions options=$v1.cart_content}
                                        {/if}
                                         {if $v1.prd_answer!=''}
                                             <br>
                                             <b>{$v1.prd_question}</b>:<br>
                                             {$v1.prd_answer|safetext|cleantext}
                                         {/if}
                                    </td>
                                    <td class="TextLeft">DKK {displayprice amount=$v1.price}</td> 
                                    <td class="TextLeft">DKK {displayprice amount=$v1.final_price}</td>
                                    <td class="TextLeft">
                                         <a class="cart_td" href='index.php?module=zselex&type=user&func=deleteUserCart&id={$v1.cart_id}&shop_id={$cart_shop_id}'>
                                            <img src="{$themepath}/images/Close_cart.jpg" class="Clos_img"/> &nbsp;{gt text='delete'}
                                         </a>   
                                    </td>      
                                </tr>
                                   {if $v1.outofstock}
                                    {assign var="outOfStock" value=1}
                                   {/if}
                                  {/foreach}
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3"></td>
                                    <td>{gt text='Total price'}:   &nbsp; &nbsp; &nbsp; = &nbsp; &nbsp; &nbsp;</td> 
                                    <td class="TextLeft">DKK {displayprice amount=$GRANDSUM}</td>
                                    <td></td>

                                </tr>
                            </tfoot>     

                        </table>
                    </div>
                    <!--a href="">
                    <button type="button" class="gray_button left">
                        <span class="Left_Arrow"></span>{gt text='Continue Shopping'}</span>
                    </button></a-->
                <a href="{modurl modname="ZSELEX" type="user" func="shop" shop_id=$cart_shop_id}"><button type="button" class="gray_button left"><span class="Left_Arrow"></span>{gt text='Continue Shopping'}</button></a>
                    
                    <button type="submit" class="Orange_button left" style="margin-left:20px">
                        <span><!--GÃ¥ til levering-->{gt text='Update Cart'}</span>
                    </button>
                  
                        </form>     
                   {* {if $cantbuy > 0}
                          <form name='checkout' action='{modurl modname="ZSELEX" type="user" func="checkout" shop_id=$cart_shop_id}' method='post'>
                          <input type="hidden" name="cart_shop_id" value="{$cart_shop_id}">
                       <button type="submit" class="Orange_button right">
                        <span><!--GÃ¥ til levering-->{gt text='Send Order'}</span><span class="Right_Arrow"></span>
                      </button> 
                         </form>
                    {else}    
                    <form name='checkout' action='{modurl modname="ZSELEX" type="user" func="checkout"}' method='post'>   
                         <input type="hidden" name="cart_shop_id" value="{$cart_shop_id}">
                    <button type="submit" class="Orange_button right">
                        <span><!--GÃ¥ til levering-->{gt text='Check Out'}</span><span class="Right_Arrow"></span>
                    </button>
                   </form>
                    {/if}*}
                   {* outofstock : {$outOfStock}*}
                    {if !$outOfStock}
                     <form name='checkout' action='{modurl modname="ZSELEX" type="user" func="checkout"}' method='post'>   
                         <input type="hidden" name="cart_shop_id" value="{$cart_shop_id}">
                    <button type="submit" class="Orange_button right">
                        <span><!--GÃ¥ til levering-->{gt text='Check Out'}</span><span class="Right_Arrow"></span>
                    </button>
                   </form>
                    {/if}
                    
                         <br></br>   
                        {foreachelse}  
                            <h3>{gt text='No Items In Cart'}</h3>
                <a href="index.php"><button type="button" class="gray_button left"><span class="Left_Arrow"></span>{gt text='Back to frontpage'}</button></a>
                        {/foreach}        
                </div> 
                
                <span class="z-buttons z-formbuttons"  align="right" style="float:right;font-size: 15px"> 
                   
                    <a href=" {if $last_shop_id > 0}{modurl modname="ZSELEX" type="user" func="site" shop_id=$last_shop_id}{else}{homepage}{/if}">
                     <img width="16" height="16" title="Back" alt="Back" src="{$themepath}/images/icon_cp_backtoshoplist.png">
                    {gt text="Back"}
                     </a>
                   </span>


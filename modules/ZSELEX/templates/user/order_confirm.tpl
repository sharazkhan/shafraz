{pageaddvar name="stylesheet" value="themes/CityPilot/style/checkout.css"} 
     {insert name='getstatusmsg'}
     {include file="user/cartmenu.tpl"}<br><br>
     
    
          <div class="checkout_list_containter"> 
               
                    <h2>{gt text='Order Summary'}</h2>
             <div>
                 <b>
                     <font size='4'>
                     {gt text='Congratulations on your order has gone through - you will receive an order confirmation by email.  If you have not received it within 10 minutes, please contact us.'}
                     </font>
                 </b>
             </div>
                  <div style="margin-top:25px; margin-bottom: 5px; font-size: small; display: block; background: #f0f0f0; padding: 10px 5px;" class="txt-black45bold bluegrayboxld">
                      <b>Seller : {$owner_name}</b>
                  </div>
                    <div class="checkouttableBlock">
                        <table class="checkout_table_list">
                           
                            <colgroup class="ProductImage"/>
                            <colgroup class="ProductName"/>
                            <colgroup class="amount1"/>
                            <colgroup class="amount2"/>
                         
                            <thead>
                                <tr>
                                    <th>Number:</th>
                                   
                                    <th class="TextLeft">{gt text='Product'}:</th>
                                    <th>{gt text='Price'}:</th> 
                                    <th>{gt text='Sub Total'}:</th>
                                     
                                </tr>
                            </thead>
                            <tbody>
                                 {foreach  from=$items key=k1 item=v1}  
                                 <tr>
                                   
                                    <td>
                                        {assign var="imagepath" value="zselexdata/`$owner_name`/products/thumb/`$v1.prd_image`"}
                                        {if file_exists($imagepath)  && $v1.prd_image neq ''} 
                                        <img src="zselexdata/{$owner_name}/products/thumb/{$v1.prd_image}"  width="100" />
                                         {else}
                                        <img src="zselexdata/nopreview.jpg"  width="100" />
                                          {/if}   
                                    </td> 
                                    <td class="TextLeft">{$v1.product_name}<br />
                                        <span class="font_Gray">{$v1.prd_description}</span>
                                    </td>
                                    <td class="TextLeft">DKK {displayprice amount=$v1.prd_price}</td> 
                                    <td class="TextLeft">DKK {displayprice amount=$v1.total}</td>
                                      
                                </tr>
                                  {/foreach}
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2"></td>
                                    <td>{gt text='Total price'}:   &nbsp; &nbsp; &nbsp; = &nbsp; &nbsp; &nbsp;</td> 
                                    <td class="TextLeft">{if $order_info.cartstatus eq -1}{gt text='Ask In Shop'}{else}DKK {displayprice amount=$GRANDTOTAL}{/if}</td>
                                    <td></td>

                                </tr>
                            </tfoot>     

                        </table>
                    </div>
                  
                </div> 


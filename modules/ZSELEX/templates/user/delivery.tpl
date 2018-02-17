{pageaddvar name="stylesheet" value="modules/ZSELEX/javascript/validation/style.css"} 
{pageaddvar name='javascript' value='http://ajax.googleapis.com/ajax/libs/prototype/1.6.0.3/prototype.js'}
{pageaddvar name='javascript' value='http://ajax.googleapis.com/ajax/libs/scriptaculous/1.8.2/effects.js'}
{pageaddvar name='javascript' value="modules/ZSELEX/javascript/validation/fabtabulous.js"}
{pageaddvar name='javascript' value="modules/ZSELEX/javascript/validation/validation.js"}
{pageaddvar name='javascript' value="$themepath/javascript/delivery.js"}
{pageaddvar name='stylesheet' value="modules/ZSELEX/lib/jquery-nicemodal-1.0/jquery-nicemodal.css?v=1.0"}
{pageaddvar name='javascript' value="modules/ZSELEX/lib/jquery-nicemodal-1.0/jquery-nicemodal.js"} 



<div class="row">
    <div class="col-md-12">
         {include file="user/cartmenu.tpl"}
        <h2>{gt text='Delivery Address'}</h2>

        <!-- delivery -->
        <form id="payments" name="payments" method="post" action="{modurl modname="ZSELEX" type="user" func="paymentoptions" shop_id=$smarty.request.shop_id}" class="form-horizontal">
         <input type="hidden" id="shop_id" value="{$smarty.request.shop_id}">
            <div class="delivery-wrap">
            <div class="delivery-address-details">
                
                    <div class="row">
                        <div class="col-sm-6">
                           {if $showCheckbox > 0}
                            <div class="checkbox">
                                <input {if $smarty.session.checkoutinfo.self_pickup}checked{/if} type="checkbox" id="self_pickup" name="self_pickup" value="1">
                                <label for="self_pickup">{gt text='I will pick up the order in your shop myself'}</label>
                            </div>
                          
                            <div class="delivery-time-info">
                                <p>{gt text='Your order will be delivered to you within'}&nbsp;{$delivery_time}</p>
                            </div>
                            <div class="delivery-address">
                                 <b>{$userinfo.fname} {$userinfo.lname}<br />{$userinfo.address}<br />{$userinfo.zip} {$userinfo.city}
                            </br>{$userinfo.country}</b>
                            </div>
                              {/if}
                        </div>


                        <div class="col-sm-6">
                            {if $showCheckbox > 0}
                            <div class="checkbox">
                                <input type="checkbox" id="chng_shippingaddr" name="chng_shippingaddr" value="1">
                                <label for="chng_shippingaddr">{gt text='Choose a different delivery address'}</label>
                            </div>
                           {/if}

                            <div class="form-horizontal delivery-new-address new_address" style="display: none;">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="">{gt text='First Name'}: </label>
                                    <div class="col-sm-8">
                                        <input title="{gt text='Please enter your first name'}" name="diffAddr[fname]" type="text" placeholder="" id="fname" class="form-control required" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="">{gt text='Last Name'}: </label>
                                    <div class="col-sm-8">
                                        <input type="text" title="{gt text='Please enter your last name'}" placeholder="" name="diffAddr[lname]" id="lname" class="form-control required" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="">{gt text='Address'}: </label>
                                    <div class="col-sm-8">
                                        <textarea name="diffAddr[address]" title="{gt text='Please enter your address'}" id="address" class="form-control required" disabled></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="">{gt text='Phone'}: </label>
                                    <div class="col-sm-8">
                                        <input type="text" placeholder="" id="phone" name="diffAddr[phone]" class="form-control required validate-digits" title="{gt text='Please enter a valid phone number'}" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 col-xs-12 control-label" for="">{gt text='ZIP code. city'}: </label>
                                    <div class="col-sm-3 col-xs-4">
                                        <input type="text" placeholder="" id="country_code" name="diffAddr[zip]" class="form-control required" title="{gt text='Please enter zip code'}" disabled>
                                    </div>
                                    <div class="col-sm-5 col-xs-8">
                                        <input type="text" placeholder="" id="telephone" name="diffAddr[city]" class="form-control required" title="{gt text='Please enter city'}" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="promo-code">
                                <h4>{gt text='Discount code'}:</h4>
                                <p> {gt text='Enter discount code. Each voucher can only be used once. There can only be used one voucher per order:'} </p>
                            </div>
                            <div class="col-sm-12 update-price">
                                <div class="form-inline">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="dis_code" {if $smarty.session.checkoutinfo.discount_code}value="{$smarty.session.checkoutinfo.discount_code}"{/if} placeholder="{gt text='Enter Code..'}">
                                    </div>
                                    <button id="updatePrice" type="button" class="btn btn-default btn-update-price"><i class="fa fa-refresh"></i> {gt text='Update price'}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input id="gtotalhidden" type="hidden" value="{displayprice amount=$GRANDTOTAL}">
                    <input id="gtotalallhidden" type="hidden" value="{displayprice amount=$GRANDTOTAL_ALL}">
                    <input id="shippinghidden" type="hidden" value="{displayprice amount=$SHIPPING}">
              
            </div>
            <div class="delivery-pricing-details">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{gt text='Your order'}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{gt text='Price'}:</td>
                                    <td id="gtotal" class="price-dtl">DKK {displayprice amount=$GRANDTOTAL}</td>
                                </tr>
                                <tr class="active-color">
                                    <td>{gt text='Discount'}:</td>
                                    <td class="price-dtl" id="discount_td">{$DISCOUNT}%</td>
                                </tr>
                                {if $showCheckbox > 0}
                                <tr>
                                    <td>{gt text='Shipping'}:</td>
                                    <td class="price-dtl" id="shippingtotal">DKK {displayprice amount=$SHIPPING}</td>
                                </tr>
                                {/if}
                                <tr>
                                    <td>{gt text='VAT is'}:</td>
                                    <td class="price-dtl" id="vat_td">DKK {displayprice amount=$VAT}</td>
                                </tr>
                                <tr>
                                    <td>{gt text='Total'}:</td>
                                    <td class="price-dtl" id="gtotalall">DKK {displayprice amount=$final_price} </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">
                                        <div class="checkbox">
                                            <input type="checkbox" title="{gt text='Please select the terms and conditions'}" id="termscondition" class="required">
                                            <label for="termscondition"> {gt text='I have read and accept this shops'} <a href="#"><span class="delivery-pop-up" data-url="{$baseurl}{modurl modname='ZSELEX' type='info' func='footerLink' key='termsoftrade' shop_id=$smarty.request.shop_id}">{gt text='terms and conditions'}</span></a></label>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <div class="checkout-btn-wrap clearfix">
            <a href="{modurl modname="ZSELEX" type="user" func="shop" shop_id=$shop_id}" class="btn btn-warning cs-btn btn-info"><i class="fa fa-angle-left"></i> {gt text='Continue Shopping'}</a>
           {* <a href="#" class="btn btn-primary checkout-btn"> *}
               
           <button type="submit" class="btn btn-primary checkout-btn">
                {gt text='Go to payment'} 
                <i class="fa fa-arrow-right"></i>
           </button>
          {*  </a> *}
        </div>
          </form>
        <!-- delivery -->
    </div>
</div>
        
         <script type="text/javascript">
		var valid2 = new Validation('payments', {useTitles:true});
                 jQuery('.delivery-pop-up').nicemodal({
        width: '500px',
        height: '500px',
        keyCodeToClose: 27,
        defaultCloseButton: true,
        closeOnClickOverlay: true,
        closeOnDblClickOverlay: false,
        // onOpenModal: function(){
        //     alert('Opened');
        // },
        // onCloseModal: function(){
        //     alert('Closed');
        // }
    });
            </script>
<div class="wizard">
            <a href="{modurl modname="ZSELEX" type="user" func="cart" shop_id=$smarty.request.shop_id}" {if $smarty.request.func eq 'cart'} class="current" {/if}><span class="badge">1</span> {gt text='Cart'}</a>
           <a href="{if in_array("checkout" , $smarty.session.cart_menu)}{modurl modname="ZSELEX" type="user" func="checkout" shop_id=$smarty.request.shop_id}{else}#{/if}" {if $smarty.request.func eq 'checkout'}class="current"{/if}>
                <span class="badge">2</span> {gt text='Customer Information'}
            </a>
            <a href="{if in_array("delivery", $smarty.session.cart_menu)}{modurl modname="ZSELEX" type="user" func="delivery" shop_id=$smarty.request.shop_id}{else}#{/if}" {if $smarty.request.func eq 'delivery'}class="current"{/if}>
                <span class="badge">3</span> {gt text='Delivery'}
            </a>
           <a href="{if in_array("paymentoptions" , $smarty.session.cart_menu)}{modurl modname="ZSELEX" type="user" func="paymentoptions" shop_id=$smarty.request.shop_id}{else}#{/if}"   {if $smarty.request.func eq 'paymentoptions'}class="current"{/if}>
                <span class="badge">4</span> {gt text='Payment'}
            </a>
             <a href="#" {if $smarty.request.func eq 'order' OR $smarty.request.func eq 'payPalReturn' OR $smarty.request.func eq 'orderConfirmation' OR $smarty.request.func eq 'paymentStatus'}class="current"{/if}>
                <span class="badge">5</span> {gt text='Order Confirmation'}
            </a>
        </div>
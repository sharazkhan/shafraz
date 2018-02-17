{*<h3>Thank you for your payment.<h3>

<br>

<b>Your Reciept :</b>
<br><br>

{$reciept}
*}
{pageaddvar name="stylesheet" value="themes/CityPilot/style/checkout.css"} 
{include file="user/cartmenu.tpl"}<br><br>
  <div class="HalfSec">
<div class="checkout_form">
        <h3>{gt text='Your Payment has been processed.Please check you mail for the Order details'}</h3>
 {*Lorem ipsum dolor sit amet Aenean massa. Cum sociis natoque<br>
 penatibus et magnis dis parturient montes, nascetur ridiculus<br>
 mus. Aenean massa. Cum sociis natoque penatibus et magnis dis *}
  <div class="ButtonDiv left">
      <a href="{modurl modname="ZSELEX" type="user" func="site" shop_id=$shop_id}">
   <button type="submit" class="Orange_button">
       <span class="Left_Arrow"></span>{gt text='Continue Shopping'}
   </button>
      </a>
   </div>
   </div>
</div>
 
   <style>
   .HalfSec{width:60%;}
   </style>
<br>

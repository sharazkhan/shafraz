{pageaddvar name="stylesheet" value="themes/CityPilot/style/checkout.css"} 
{include file="user/cartmenu.tpl"}<br><br>

<div class="HalfSec">
<div class="checkout_form">
        <h2>{gt text='Sorry - Your Payment Cannot Be Processed'}</h2>
      {gt text='Sorry your order cannot be processes. some error has occured. please try again!'}
 <div class="ButtonDiv left">
      <a href="{modurl modname="ZSELEX" type="user" func="paymentoptions"}">
   <button type="submit" class="Orange_button">
       <span class="Left_Arrow"></span>{gt text='Go to payment'}
   </button>
      </a>
   </div>
</div>
 
   <style>
   .HalfSec{width:60%;}
   </style>

<br>
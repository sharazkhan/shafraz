<div class="row">
    <div class="col-md-12">
        {include file="user/cartmenu.tpl"}
        <h2>{gt text='Sorry - Your Payment has been cancelled'}</h2>
        
        <br><br>
        
        <a href="{modurl modname="ZSELEX" type="user" func="paymentoptions"}"  class="btn btn-primary checkout-btn">
            {gt text='Go to Payment'} <i class="fa fa-arrow-right"></i>
        </a>
    </div>
    
</div>
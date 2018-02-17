
{pageaddvar name='stylesheet' value="$themepath/style/style.css?v=1.5"}
{pageaddvar name='stylesheet' value="$themepath/style/responsive.css?v=1.5"}
{pageaddvar name='stylesheet' value="modules/ZSELEX/lib/jquery-nicemodal-1.0/jquery-nicemodal.css?v=1.1"}
{pageaddvar name='javascript' value="modules/ZSELEX/lib/jquery-nicemodal-1.0/jquery-nicemodal.js"} 

<footer class="footer">
    <div class="container">
        <div class="footer-top clearfix">
            <div class="footer-logo"><img src="{$themepath}/images/FooterLogo.png" alt="" width="153" height="36"></div>
            <div class="mobi-icons">
                <h4>FØLG OS </h4>
                <ul>
                    <li><a href="#"><i class="fa fa-facebook-square"></i></a></li>
                    <li><a href="#"><i class="fa fa-twitter-square"></i></a></li>
                    <li><a href="#"><i class="fa fa-pinterest-square"></i></a></li>
                    <li><a href="#"><i class="fa fa-google-plus-square"></i></a></li>
                    <li><a href="#"><i class="fa fa-share-alt-square"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="row footer-contents">
            <div class="col-sm-3 f-links mobi-click-1">
                {assign var="termsConditionInfo" value=$modvars.ZSELEX.termsConditionInfo|unserialize}
                <h4 class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{gt text='RETURN AND PAYMENT'}</h4>
                <ul class="mobi-nav-1 dropdown-menu">
                    <li>
                        <a href="#">
                        <span class="footer-pop-up" data-url="{$baseurl}{modurl modname='ZSELEX' type='info' func='footerLink' key='rma'  shop_id=$smarty.request.shop_id}">
                            {gt text='RMA'} 
                        </span>
                        </a>
                    </li>
                    <li>
                         <a href="#">
                        <span  class="footer-pop-up" data-url="{$baseurl}{modurl modname='ZSELEX' type='info' func='footerLink' key='deliveryprices'  shop_id=$smarty.request.shop_id}">
                            {gt text='Delivery prices'} 
                        </span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                        <span  class="footer-pop-up" data-url="{$baseurl}{modurl modname='ZSELEX' type='info' func='footerLink' key='deliverytime'  shop_id=$smarty.request.shop_id}">
                            {gt text='Delivery time'} 
                        </span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-sm-3 f-links mobi-click-2">
                <h4 class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{gt text='TERMS AND CONDITIONS'}</h4>
                <ul class="mobi-nav-2 dropdown-menu">
                    <li>
                        <a href="#">
                        <span class="footer-pop-up" data-url="{$baseurl}{modurl modname='ZSELEX' type='info' func='footerLink' key='termsoftrade' shop_id=$smarty.request.shop_id}">
                            {gt text='Terms of trade'} 
                        </span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                        <span class="footer-pop-up" data-url="{$baseurl}{modurl modname='ZSELEX' type='info' func='footerLink' key='privacy' shop_id=$smarty.request.shop_id}">
                            {gt text='Privacy'}
                        </span>
                         </a>
                    </li>
                    <li>
                        <a href="#">
                        <span class="footer-pop-up" data-url="{$baseurl}{modurl modname='ZSELEX' type='info' func='footerLink' key='securepayment' shop_id=$smarty.request.shop_id}">
                            {gt text='Secure payment'}
                        </span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-md-3 col-sm-6 f-form">
                {*
                <h4>TILMELD DIG VORES NYHEDSBREV </h4>
                <form action="" class="form-inline">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="DIN EMAILADRESSE">
                    </div>
                    <button type="submit" class="btn btn-default">OK</button>
                </form>
                <p>Når du tilmelder dig vores nyhedsbrev får du automatisk tilsendt relevant information om CityPilot. </p>
                *}
                {blockposition name='bot2-03'}
            </div>
            <div class="col-md-3 col-sm-12 f-social-icons">
                <!--<h4>{gt text='Follow Us'} </h4>
                <ul>
                    <li><a href="#"><i class="fa fa-facebook-square"></i></a></li>
                    <li><a href="#"><i class="fa fa-twitter-square"></i></a></li>
                    <li><a href="#"><i class="fa fa-pinterest-square"></i></a></li>
                    <li><a href="#"><i class="fa fa-google-plus-square"></i></a></li>
                    <li><a href="#"><i class="fa fa-share-alt-square"></i></a></li>
                </ul>-->
                 {blockposition name='follow-us'}
            </div>
        </div>
    </div>
</footer>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <!--<script src="{$themepath}/js/jquery.min.js"></script>-->
  <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>-->
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="{$themepath}/js/bootstrap.min.js"></script>
<script src="{$themepath}/js/jquery.bxslider.min.js"></script>
<script src="{$themepath}/js/jquery.mousewheel-3.0.6.pack.js"></script>
<script src="{$themepath}/js/jquery.fancybox.js"></script>
<script src="{$themepath}/js/chosen.jquery.js"></script>
<script src="{$themepath}/js/custom.js"></script>
<script type="text/javascript" src="{$baseurl}/modules/ZSELEX/javascript/bigtext/bigtext.js"></script>
<script>
    //Initialize Font sizxe auto resizing
    //var $ = jQuery;
    var bt = BigText.noConflict(true);
    jQuery.fn.bt = bt.jQueryMethod;

    jQuery('#shopTitleDiv').bt({maxfontsize: 50});
    
     jQuery('.footer-pop-up').nicemodal({
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
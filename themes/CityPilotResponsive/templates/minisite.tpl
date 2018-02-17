<!DOCTYPE html>
<html lang="en">
    {include file="includes/head.tpl"}
    <body class="shop-page">
        <!-- alert -->
        {include file="includes/alert_message.tpl"}
        <!-- alert end -->

        <!-- Header -->
        <div class="cp-header">
            {blockposition name=citypilotheader}
        </div>
        <!-- Search End -->

        <!-- Header End -->

       {include file="includes/shopname.tpl"}

        <!-- Banner Slider-->
        <div class="banner">
         {blockposition name=minisite-banner}
        </div>
        <!-- Banner End -->

        <!-- Contents -->
        <section class="contents-wrap">
            <div class="container">

                <!-- Special Row -->
                <div class="row">
                    <div class="col-md-8 contents-left">
                        <!-- Shop navigation -->
                         {if !empty($smarty.request.shop_id)}
                       {include file="includes/shopheader.tpl"}
                         {/if}  
                        <!-- Shop navigation End -->

                        <div class="contents-box clearfix">
                            <div class="col-sm-12">
                                 {$maincontent}
                            </div>

                            <!-- image lightbox -->
                            <div class="minisite-images">
                             {blockposition name='ministe-images'}
                            </div>
                            <!-- end image lightbox -->
                        </div>
                    </div>

                    <!-- shop sidebar -->
                    <div class="col-md-4 contents-right">
                        <div class="clearfix">
                            <!-- shop address -->
                            <div class="shopaddress">
                            {blockposition name='shopaddress'}
                            </div>
                            <!-- shop address ends -->
                           <div class="sociallinks">
                            {blockposition name='sociallinks'}
                           </div>
                           
                           <!-- Event -->
                           <div class="minisite-event">
                            {blockposition name='minisite-event'}
                           </div>
                           <!-- Event ends -->
                        </div>
                    </div>
                    <!-- end shop sidebar -->
                </div>
                <!-- Special End -->

                <!-- Sub Special Row  -->
                <div class="minisite-products">
                  {blockposition name='ZSELEX-minisite-products'}
                </div>
                <!-- Sub Special Row End-->
            </div>
        </section>
        <!-- Contents End -->

        <!-- footer -->
        {include file="includes/footer.tpl"}
        <!-- Footer End -->

        {*<div id="top-link-block" class="hidden">
            <a href="#top" class="back-top-top"  onclick="$('html,body').animate({scrollTop: 0}, 'slow');return false;">
                <i class="glyphicon glyphicon-chevron-up"></i></a>
        </div>*}



    </body>
    </html>

<!DOCTYPE html>
<html lang="en">
    {include file="includes/head.tpl"}
    <body class="shop-page">
        <!-- alert -->
        {include file="includes/alert_message.tpl"}
        <!-- alert end -->

        <!-- Header -->
        <div class="cp-header">
           {* {blockposition name=citypilotheader} *}
        </div>
        <!-- Header End -->
        {include file="includes/shopname.tpl"}

        <!-- Banner Slider-->
       {* <div class="banner">
            {blockposition name=minisite-banner}
        </div>*}
        <!-- Banner End -->

        <!-- Contents -->
        <section class="contents-wrap">
            <div class="container">

                <!-- Special Row -->
                <div class="row">
                    <div class="col-md-12 contents-left">
                        <!-- Shop navigation -->
                        {if !empty($smarty.request.shop_id)}
                        {include file="includes/shopheader.tpl"}
                        {/if}  
                        <!-- Shop navigation End -->

                        <div class="contents-box clearfix product-details"> <!-- main content starts -->
                            {$maincontent}
                        </div> <!-- main content ends -->
                    </div>
                </div>
                <!-- Special End -->
            </div>
        </section>
        <!-- Contents End -->

        <!-- footer -->
        {include file="includes/footer.tpl"}
        <!-- Footer End -->


    </body>
</html>
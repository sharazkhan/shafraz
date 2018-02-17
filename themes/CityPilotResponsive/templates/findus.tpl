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

                    <div class="contents-box clearfix product-details">
                    	<div class="clearfix product-details-wrapper">
                            <div class="col-sm-12">
                                {$maincontent}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- shop sidebar -->
                <div class="col-md-4 contents-right">
                    <div class="clearfix">
                          <div class="shopaddress">
                            {blockposition name='shopaddress'}
                            </div>
                    </div>
                </div>
                <!-- end shop sidebar -->
            </div>
            <!-- Special End -->

            <!-- Employees  -->
           
               
            <div class="employees">
                        {blockposition name='employees'} 
            </div>
            <!-- employees -->


        </div>
    </section>
    <!-- Contents End -->

    <!-- footer -->
    {include file="includes/footer.tpl"}
    <!-- Footer End -->

  </body>
</html>
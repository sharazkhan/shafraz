<!DOCTYPE html>
<html lang="en">
  {include file="includes/head.tpl"}
  <body class="home-page">
   
   {include file="includes/alert_message.tpl"}
    <!-- Header -->
   {* {include file="includes/header.tpl"} *}
   <div class="cp-header">
     {blockposition name=citypilotheader}
   </div>
    <!-- Header End -->

    <!-- Banner Slider-->
    <div class="exclusive-events">
         {blockposition name='exclusive_events'}
    </div>
    <!-- Banner End -->

    <!-- Contents -->
    <section class="contents-wrap">
        <div class="container">

            <!-- Special Row -->
            <div class="row">
                
                <!-- Special Deals -->
                <div class="special-deal-block">
               {blockposition name='specialdeals'}
                </div>
                <!-- Special Deal Ends -->
                <div class="upcomming-events">
                 {blockposition name='upcomming-events'}
                </div>
                
            </div>
            <!-- Special End -->
            
            <!-- Sub Special Row  -->
            <div class="row new-shops">
                  {blockposition name='newshops'}
            </div>
            <!-- Sub Special Row End-->
        </div>
    </section>
    <!-- Contents End -->

    <!-- footer -->
     {include file="includes/footer.tpl"}
    <!-- Footer End -->

    {*<div id="top-link-block" class="hidden">
        <a href="#top" class="back-top-top"  onclick="$('html,body').animate({scrollTop:0},'slow');return false;">
            <i class="glyphicon glyphicon-chevron-up"></i></a>
    </div>*}


  
  </body>
  </html>
<!DOCTYPE html>
<html lang="en">
  {include file="includes/head.tpl"}
  <body class="home-page">
      {include file="includes/alert_message.tpl"}

    <!-- Header -->
    <div class="cp-header">
     {blockposition name=citypilotheader}
   </div>
    <!-- Header End -->

    <!-- Banner Slider-->
    
    <!-- Banner End -->

    <!-- Contents -->
    <section class="contents-wrap">
        <div class="container">
            {insert name='getstatusmsg'}
                 {$maincontent}
          
        </div> <!-- Container Ends -->
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

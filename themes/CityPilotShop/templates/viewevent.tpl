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
                    <div class="col-md-8 contents-left">
                        <!-- Shop navigation -->
                        {if !empty($smarty.request.shop_id)}
                        {include file="includes/shopheader.tpl"}
                        {/if}  
                        <!-- Shop navigation End -->

                        <div class="contents-box clearfix">
                            {$maincontent}
                        </div>
                    </div>

                    <!-- shop sidebar -->
                    <div class="col-md-4 contents-right">
                        <div class="event-date-time">
                            <ul>
                                <li>
                                    <i class="fa fa-calendar"></i>
                                    {$smarty.request.start_weekday} {$smarty.request.start_mday}. {$smarty.request.start_month}
                                    <br>
                                    {$smarty.request.end_weekday} {$smarty.request.end_mday}. {$smarty.request.end_month}
                                </li>
                                <li><i class="fa fa-clock-o"></i>{$smarty.request.shop_event_starthour} - {$smarty.request.shop_event_endhour}</li>
                                <li><i class="fa fa-map-marker"></i>
                                    {if $smarty.request.shop_event_venue neq ''} 
                                        {$smarty.request.shop_event_venue}
                                    {else}
                                        {if $smarty.request.showfrom eq 'image'}  
                                            {gt text='see event image'}
                                        {else}
                                            {gt text='see event'}  
                                        {/if}   
                                    {/if}
                                </li>
                                <li><i class="fa fa-envelope"></i>{$smarty.request.email}</li>
                                <li><i class="fa fa-phone-square"></i>{$smarty.request.phone}</li>
                                <li><i class="fa fa-money"></i>
                                    {if $smarty.request.price neq ''} 
                                        {if $smarty.request.price > 0}
                                        {displayprice amount=$smarty.request.price}
                                        {else}
                                        {gt text='FREE'}
                                        {/if}
                                    {else}
                                        {if $smarty.request.showfrom eq 'image'}  
                                        {gt text='see event image'}
                                        {else}
                                        {gt text='see event'}  
                                        {/if}   
                                    {/if}
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- end shop sidebar -->
                </div>
                <!-- Special End -->
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
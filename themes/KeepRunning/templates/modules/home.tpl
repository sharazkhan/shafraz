<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Keep Running</title>

    </head>
    <link rel="stylesheet" type="text/css" href="{$stylepath}/styleR2.css"/>
    <body id="Landing"  onLoad="displayBlocks();">

        <div class="Containter">
           {include file="includes/header.tpl"}
           <noscript>
            <span style=color:red;font-size:20px;padding-left:40%;>please enable java script</span>
           </noscript>
            <div class="Contbody">
                <div class="flash">
                    <div class="FlashPop" id="h4hide">
                        <!-- GÃ¥ til keeprunningshop-->
                        {blockposition name=top-homelink}
                     </div>
                    <div class="FlashSect">
                        <p class="Phead">Inspiration til fysisk <br />og mental udfoldelse</p>
                        <p class="Pcontents">
                            KeepRunning rÃ¥der og positive og faglige kompetente under<br>visere og foredragsholdere
                        </p>

                    </div> 
                </div> <!-- flash Div ends here-->

                <div class="NewCenter">
                <div class="ArticleBlock1">
                    
                   {blockposition name=newslist-top}

                </div> 
                </div>  <!--Keeprunning div ends sec -->

                <div class="ActualBody"> <!-- this div has to controll  -->

                    <div class="ContentMiddle" id="HomeCenterMiddle">
                       {blockposition name='selectionbox'}
                       {blockposition name=center-home}
                  
                    </div><!--End Content Millde -->
                    <div class="ContentRight"  id="HomeRight">
                       {blockposition name='googlemapzselex'} 
                       {blockposition name=right-home}
                       {blockposition name=zencart-products}
                     
                    </div>

                </div>  
                <!-- actual body ends--> 
                <!--End purchase sec -->

            </div> <!-- Contentbody Div ends here-->


            <div class="BotImgSec">
                <div class="BotImgSecMid">
                   {blockposition name=newslist-bot}

                </div>


            </div>
            <!--Bottom Image section -->

            {include file="includes/footer.tpl"}
            <!--end of footer -->


        </div>   <!--end of Containter -->

    </body>
</html>

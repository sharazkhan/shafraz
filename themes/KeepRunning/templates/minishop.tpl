<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Keep Running</title>

    </head>
    <link rel="stylesheet" type="text/css" href="{$stylepath}/styleR2.css"/>
    <body>

        <div class="Containter">
           {include file="includes/header.tpl"}
           <noscript>
            <span style=color:red;font-size:20px;padding-left:40%;>please enable java script</span>
           </noscript>
            <div class="Contbody">
                <div class="flash1">
                    <div class="FlashPop"  id="h4hide">
                        <!-- GÃ¥ til ArticleBlock1shop-->
                       {blockposition name=top-homelink}
                    </div>
                    <div class="FlashSect1">
                        <p class="Phead">ArticleBlock1 sit<br />
                            amet adipiscing </p>

                        <p class="Pcontents">
                            Morbi eleifend, diam sed consequat<br /> ultrices, orci justo adipiscing eros, </p>

                    </div> 
                </div>
               

                <div class="ActualBody Minishop"> <!-- this div has to controll  -->

                    <div class="ContentMiddle" id="HomeCenterMiddle">
 
                      {$maincontent} 
                       
                     {blockposition name='ZSELEX-minishop-products'}
                     
                    </div><!--End Content Midlle -->
                    
                     <div style="width:195px; height: auto; float:left">
                         
                     
                       
                     
                    </div>
                    

                </div>  
                <!-- actual body ends--> 
                <!--End purchase sec -->

            </div> <!-- Contentbody Div ends here-->


              <div class="ArticleBlock1Outer"> 
                    <div class="NewCenter"> 
                        <div class="ArticleBlock1" id="h4hide">
                           {* {blockposition name=newslist-top} *}
                        </div>
                    </div>
                </div>
            <!--Bottom Image section -->

            {include file="includes/footer.tpl"}
            <!--end of footer -->


        </div>   <!--end of Containter -->

    </body>
</html>

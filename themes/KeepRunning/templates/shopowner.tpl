<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Keep Running</title>
        <link rel="stylesheet" type="text/css" href="{$stylepath}/styleR2.css" media="print,projection,screen" />
        <style type="text/css">
        </style>
    </head>

    <body>
      
        <div class="Containter">
            {include file="includes/header.tpl"}
         
            <div class="Contbody">
                <div class="flash1">
                    <div class="FlashPop">
                        <!-- GÃ¥ til ArticleBlock1shop-->
                    </div>
                    <div class="FlashSect1">
                        <p class="Phead">ArticleBlock1 sit<br />
                            amet adipiscing </p>

                        <p class="Pcontents">
                            Morbi eleifend, diam sed consequat<br /> ultrices, orci justo adipiscing eros, </p>

                    </div> 
                </div>

                <div class="ActualBody">    <!-- this div has to controll  -->
                    <div class="ContentsLeft">
                        {blockposition name=left}
                       
                    </div>
                    <div class="ContentMiddle">
                        {$maincontent}
                    </div><!--End Content Millde -->
                    <div class="ContentRight" id="MasterRight">


                    </div>
                </div>  
                <!-- actual body ends--> 
                <div class="ArticleBlock1Outer"> 
                    <div class="NewCenter"> 
                        <div class="ArticleBlock1" id="h4hide">
                            {blockposition name=newslist-top}
                        </div>
                    </div>
                </div>
            </div>


            <div class="BotImgSec">
                <div class="BotImgSecMid">

                    {blockposition name=newslist-bot}

                </div>  


            </div><!--Bottom Image section -->

            <!--Footer Section -->
            {include file="includes/footer.tpl"}

            <!--end of footer -->


        </div>

    </body>
</html>

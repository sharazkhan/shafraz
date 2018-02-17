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
                <div class="flash1"id="flash3">
                    <div class="FlashPop">
                        <!-- GÃ¥ til ArticleBlock1shop-->
                    </div>
                    <div class="FlashSect1" id="FlashSect111">
                        <p class="Phead">Er skaden <br />
                            sket?</p>

                        <p class="Pcontents">
                            Lorem ipsum dolor sit amet consectetuer<br /> adipiscing elit. Proin tempor bibendum dignissim. </p>

                    </div> 
                </div>

                <div class="ActualBody">

                    <div class="ContentsLeft">
                       {blockposition name=left}
                    </div><!--End Content Left -->

                    <div class="ContentMiddle">
                       {blockposition name=center}
                    </div><!--End Content Millde -->

                    <div class="ContentRight">
                       {blockposition name=right}

                    </div>


                </div>  
                <!-- actual body ends-->    
                <div class="ArticleBlock1Outer"> 
                   <div class="NewCenter">
                    <div class="ArticleBlock1">



                        <div class="Grade"></div> 

                        <div class="Sec1">
                            <img src="{$imagepath}/img1.jpg"/>
                            <p class="sec1H">Er skaden  sket?</p>
                            <p class="sec1T">To eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque</p>
                            <p class="sec1L"><a href="#">Se hvordan du kommer hurtigt over din skade<img src="{$imagepath}/ArrSmall.jpg" /></a></p>


                        </div> 

                        <div class="Sec1">
                            <img src="{$imagepath}/img2.jpg"/>
                            <p class="sec1H">Kom igang med at lÃ¸be</p>
                            <p class="sec1T">Justo odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas </p>
                            <p class="sec1L"><a href="#">VÃ¦lg 5K eller 10K trÃ¦nings program<img src="{$imagepath}/ArrSmall.jpg" /></a></p>


                        </div> 

                        <div class="Sec1">
                            <img src="{$imagepath}/img3.jpg"/>
                            <p class="sec1H">Bestil en lÃ¸betest</p>
                            <p class="sec1T">I alle vores butikker tilbyder vi lÃ¸betests lorem ipsum dolor sit amet consectetuer adipiscing elit. aliquam in eros nis.</p>
                            <p class="sec1L"><a href="#">Se de mange gode trÃ¦ningsrÃ¥d<img src="{$imagepath}/ArrSmall.jpg" /></a></p>


                        </div> 


                    </div>
                    </div>
                </div> 
            </div>


            <div class="BotImgSec">
                <div class="BotImgSecMid">

                    <div class="BotImg">
                        <div class="Botimgdiv">
                            <img src="{$imagepath}/BotImg1.jpg" />           
                        </div>
                        <div class="BotparaHead">
                            <h5>Team high end Sport</h5>
                            <p class="botimgpara">
                                ArticleBlock1 er sponsorer for bÃ¥de et lÃ¸be og et cykel hold. lorem ipsum dolor sit amet consectetuer adipiscing elit. 
                            </p>

                            <p class="botimgparaLInk">
                                <a href="#">LÃ¦s mere om Team high end sport&nbsp;<img src="{$imagepath}/ArrSmall.jpg" /></a>
                            </p>
                        </div> 

                    </div> <!-- End of Bot Image One -->

                    <div class="BotImg">
                        <div class="Botimgdiv">
                            <img src="{$imagepath}/BotImg2.jpg" />           
                        </div>
                        <div class="BotparaHead">
                            <h5>Dansk special  trÃ¦ning</h5>
                            <p class="botimgpara">
                                Dansk special trÃ¦ning tilbyder  salg af kurser og foredrag. vi har ogsÃ¥ erfaring med fysisk tÃ¦ning til sportsklubber.
                            </p>

                            <p class="botimgparaLInk">
                                <a href="#">LÃ¦s mere DST &nbsp;<img src="{$imagepath}/ArrSmall.jpg" /></a>
                            </p>
                        </div> 

                    </div><!-- End of Bot Image Two -->

                    <div class="BotImg">
                        <div class="Botimgdiv">
                            <img src="{$imagepath}/BotImg3.jpg" />           
                        </div>
                        <div class="BotparaHead">
                            <h5>tilmeld dig vores Nyhedsbrev...</h5>
                            <p class="botimgpara">
                                ...og modtag gode lÃ¸betips og tilbud fra ArticleBlock1shop. 
                            </p>

                            <p class="botimgparaLInk" class="LE">
                                <input type="text" value="Indtast email"  />
                                <img src="{$imagepath}/Email.jpg" id="email" />
                            </p>
                        </div> 

                    </div><!-- End of Bot Image Two -->


                </div>


            </div><!--Bottom Image section -->

            <!--Footer Section -->
           {include file="includes/footer.tpl"}
            <!--end of footer -->


        </div>

    </body>
</html>

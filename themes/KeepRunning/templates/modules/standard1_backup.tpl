<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Keep Running</title>
      
          <link rel="stylesheet" type="text/css" href="{$stylepath}/styleR2.css"/>
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
                    <!--End Content Left -->

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
                       {blockposition name=newslist-top}


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

                            <p class="botimgparaLInk" style="float:left">
                                <input type="text" value="Indtast email"  />
                                <img src="{$imagepath}/Email.jpg" id="email" />
                            </p>
                        </div> 

                    </div><!-- End of Bot Image Two -->


                </div>


            </div><!--Bottom Image section -->

            <div class="footer"><!--Footer Section -->
                <div class="footerContainer">
                    <div class="footerSec"><!-- footer Sec Left-->
                        <h3>ArticleBlock1</h3>
                        <p>
                            <b>Vestergade 15, 8000 Aarhus C<br>
                                    Tlf.   86 18 00 48</b><br /><br />
                            <span>Man-Tors 10-17:30, Fre til kl.18, LÃ¸r til kl 14<br />
                                Du kan reservere tid til en lÃ¸betest fra kl 8 til 10. <br>
                                    Kontakt os i den normale Ã¥bningstid for at bestille tid.</span>

                        </p>
                    </div>

                    <div class="footerSec"><!-- footer Sec Middle-->
                        <h6><a href="#">Sitemap</a>&nbsp;
                            <a href="#">FAQ</a>
                        </h6> 
                        <p>
                            <b>Prinsessegade 18, 7000 Fredericia<br>
                                    Tlf.   75 92 03 45</b><br /><br />
                            <span>Man-Fre 12-17, LÃ¸r  10-13<br>
                                    Du kan reservere tid til en lÃ¸betest mandag til fredag<br>
                                        fra 9-12, ring og hÃ¸r nÃ¦rmere.</span>

                                        </p>
                                        </div>

                                        <div class="footerSec" style="margin-left:15px; width:300px"><!-- footer Sec right-->
                                            <h6>Â© 2011 ArticleBlock1  </h6>
                                            <p>
                                                <b>NÃ¸rreskovbakke 14, 8600 Silkeborg<br />
                                                    Tlf.   24 60 39 60</b><br /><br />
                                                <span>
                                                    Man-Fre 10-17<br />
                                                    Du kan ringe og bestille tid til en lÃ¸betest, sÃ¥ afsÃ¦tter<br />
                                                    vi tid til at give dig den rigtige vejledning.
                                                </span>

                                            </p>
                                        </div><!-- end of footter sedction -->



                                        </div>
                                        </div> <!--end of footer -->


                                        </div>

                                        </body>
                                        </html>

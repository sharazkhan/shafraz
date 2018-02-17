{include file="includes/head.tpl"}

<body>
    <div class="Containter">
        <div id="CityPilotHeader">
            {blockposition name=citypilotheader}
        </div>
        <div class="BannerBlock BorderTopGrey">
           {*<img src="{$themepath}/images/Banner1.jpg" width="100%" />*}
           {blockposition name=minisite-banner}
        </div>
        <div class="announcement">
           {blockposition name='minisite-announcement'}
        </div> 
       {* <div class="CircleContainer">
            <div class="CircleBanner">
                <div class="BannerSloppedText">
                    <h3>10-15%</h3>
                    <h6>OPEN BY NIGHT</h6>
                    <p>Fredag d. 5. Juli</p>
                </div>
            </div>
        </div>*}
        <div class="BodyBlock">
            <div class="inner Contents">
                <div class="BGContainer">
                    <div class="ContentLeft left" >
                        <div>
                            {include file="includes/shopheader.tpl"}
                            
                        </div>
                       
                      
                   </div>
                    <div class="ContentRight left">
                       
                    </div>
                </div>
            </div>
        </div>
        <div class="ImageBlock">
            <div class="inner">
                <div class="BGContainer">	
                    {$maincontent}
                 </div>
            </div>
        </div>
    <div id="CityPilotFotter">
        {blockposition name='citypilotfooter'}
    </div>
    </div>
   
   
</body>

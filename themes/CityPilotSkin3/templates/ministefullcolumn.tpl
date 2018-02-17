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
      
        <div class="BodyBlock">
            <div class="inner Contents">
                <div class="BGContainer">
                        <div class="shopheader">
                            {include file="includes/shopheader.tpl"}
                            
                        </div>
                      
                            {$maincontent}
                     
                </div>
            </div>
        </div>
        
    <div id="CityPilotFotter">
        {blockposition name='citypilotfooter'}
    </div>
    </div>
   
    
</body>

{include file="includes/head.tpl"}
{fileversion}
{pageaddvar name="stylesheet" value="themes/CityPilot/style/CityBody.css$ver"} 
<body id="body" {*onLoad="displayBlocks();"*}>
     {****  CityPilot Header    ****}
    <div id="CityPilotHeader">
    {blockposition name=citypilotheader}
    </div>
     {****  CityPilot Header Ends    ****}
    <div class="Containter">
       {searchbreadcrum}
         <div class="BannerBlock">
            
          <div class="Banner">
          {include file="includes/shopname.tpl"}
          {*{blockposition name=minisite-banner}
          {blockposition name='minisite-announcement'}*}
          </div>
           
        </div>
        <div class="BodyBlock">
            <div class="inner Contents">
                 <div style="width:630px">{insert name='getstatusmsg'}</div>
                <div id="productview" class="Fullwidth">
                    {if !empty($smarty.request.shop_id)}
                       {include file="includes/shopheader.tpl"}
                     {/if}  
                       {$maincontent}
                </div>
            </div>
        </div>

        <div class="ImageBlock">
            <div class="inner">
                <div class="ImageSection"></div>
            </div>
        </div>

       {* {include file="includes/footer.tpl"} *}

    </div>
       
        {****  CityPilot Footer    ****}
    <div id="CityPilotFotter">
               {blockposition name='citypilotfooter'}
    </div>
    
     {****  CityPilot Footer Ends   ****}
</body>
</body>
</html>

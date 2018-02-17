{include file="includes/head.tpl"}

<!--MINISITE PAGE-->
{pageaddvar name="stylesheet" value="themes/CityPilot/style/CityBody.css"}
<body>

    <div class="Containter">
        {****  CityPilot Header    ****}
        <div id="CityPilotHeader">
            {blockposition name=citypilotheader}
        </div>
        {****  CityPilot Header Ends    ****}
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
                <div class="ContentLeft left"><!----  Center   ---->
                    {if !empty($smarty.request.shop_id)}
                       {include file="includes/shopheader.tpl"}
                     {/if}  
                     
                    {$maincontent}
                    
                    {blockposition name='minisite-left'}

                </div>
                <div class="ContentRight left"> <!----  Right   ---->
                    <div class="sub_right_inner">
                      
                        {blockposition name='shopaddress'}
                        <div class="sociallinks">
                        {blockposition name='sociallinks'}
                        </div>
                        <div class="ministeEvent">
                           {blockposition name='page-index'}
                        </div>
                        
                        {*{blockposition name='minisite-right'}*}
                    </div>        
                </div>
                <div style="clear:both"></div>
            </div>
        </div>

        <div class="ImageBlock">
            <div class="inner">
                

               
            </div>
        </div>
    </div>
    {****  CityPilot Footer    ****}
    <div id="CityPilotFotter">
        {blockposition name='citypilotfooter'}
    </div>
    {****  CityPilot Footer Ends   ****}
</body>
</html>

{include file="includes/head.tpl"}
{fileversion}
{pageaddvar name="stylesheet" value="themes/CityPilot/style/CityBody.css$ver"}
<body id="CityPilotBody" {*onLoad="displayBlocks();"*}>
    {****  CityPilot Header    ****}
    <div id="CityPilotHeader">
        {blockposition name=citypilotheader}
    </div>
    {****  CityPilot Header Ends    ****}
    <div class="Containter">
        {*{include file="includes/header.tpl"}*}
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
                <div class="Fullwidth minishop_fullwidth">
                    {if !empty($smarty.request.shop_id)}
                       {include file="includes/shopheader.tpl"}
                     {/if}  

                    <div class="sub_right_inner">
                    		
					</div>

                 
                    {$maincontent}
                    <div id="minishop_products_block">
                    {*{blockposition name='ZSELEX-minishop-products'}*}
                    </div>
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
</html>

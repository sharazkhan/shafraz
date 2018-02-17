{include file="includes/head.tpl"}
{fileversion}
<!--MINISITE PAGE-->
{pageaddvar name="stylesheet" value="themes/CityPilot/style/CityBody.css$ver"}
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
                {*<div class="shop-name-head">
                    <div class="inner" id="shopTitleDiv">
                      <span id="shops_span" align="center">{$smarty.request.shopName}</span>
                    </div>
                </div>*}
                {include file="includes/shopname.tpl"}
                {blockposition name=minisite-banner}
                {blockposition name='minisite-announcement'}
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
                    {blockposition name='ministe-images'}
                    {blockposition name='minisite-left'}

                </div>
                <div class="ContentRight left"> <!----  Right   ---->
                    <div class="sub_right_inner">
                        
                       <!-- {usergroupid}
                        {if ($ZXusergroupid==$modvars.ZSELEX.shopOwnerGroup) || ($ZXusergroupid==$modvars.ZSELEX.shopAdminGroup ) || ($ZXusergroupid==2)}
                        <a href="{modurl modname='ZSELEX' type='admin' func='shopsettings' shop_id=$smarty.request.shop_id}"><img src="modules/ZSELEX/images/F2B_EDIT_button.png" width="220px" title="{gt text='Edit Shopsettings'}" /></a>
                        <br /><br />
                        {* {blockposition name='shopadmin-settings'} *}
                        {/if}-->
                        {blockposition name='shopaddress'}
                        <div class="sociallinks">
                        {blockposition name='sociallinks'}
                        </div>
                        <div class="ministeEvent">
                            {blockposition name='minisite-event'}
                            <!--  {if ($ZXusergroupid==$modvars.ZSELEX.shopOwnerGroup) || ($ZXusergroupid==$modvars.ZSELEX.shopAdminGroup ) || ($ZXusergroupid==2)}
                            <a href="{modurl modname='ZSELEX' type='admin' func='events' shop_id=$smarty.request.shop_id}"><img src="modules/ZSELEX/images/F2B_EDIT_button.png" width="220px" title="{gt text='Edit Events'}" /></a>
                            <br />
                            {* {blockposition name='shopadmin-events'} *}
                            {/if}-->
                        </div>
                        {blockposition name='minisite-right'}
                    </div>        
                </div>
                <div style="clear:both"></div>
            </div>
        </div>

        <div class="ImageBlock">
            <div class="inner">
                {blockposition name='ZSELEX-minisite-products'}

                <!-- Comments -->
                {if $smarty.request.shop_id eq '38'}
                <h4>Kommentarer</h4> 
                <div class="Discussion">
                    <div class="DiscSection">
                        <div class="DiscSectionLeft">
                            <h6>    Anne Pedersen - 19. Juni kl. 10.35</h6>
                            <p>Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum.</p>
                        </div>
                        <div class="DiscSectionRight">
                            <img src="{$themepath}/images/DownArow.png" class="ShiftRight" />
                            <div class="Comment">
                                <h6>Trine Andersen - 19. Juni kl. 14.05</h6>
                                <p> Maecenas tempus, tellus eget condimentum rhoncus, sem.</p>
                            </div>
                        </div>
                    </div>
                    <div class="DiscSection">
                        <div class="DiscSectionLeft">
                            <h6>    Anne Pedersen - 19. Juni kl. 10.35</h6>
                            <p>Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum.</p>
                        </div>
                        <div class="DiscSectionRight">
                            <img src="{$themepath}/images/RightArow.png" class="ShiftRight" />
                            <div class="Comment">
                                <h6>Trine Andersen - 19. Juni kl. 14.05</h6>
                            </div>

                        </div>
                    </div>
                    <div class="DiscSection">
                        <div class="DiscSectionLeft">
                            <h6>    Anne Pedersen - 19. Juni kl. 10.35</h6>
                            <p>Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum.</p>

                        </div>
                    </div>

                </div>
                <div class="FormDiv">

                    <input type="text" value="Dit navn" />

                    <textarea rows="10" cols="50">Vi vil gerne høre hvad du har at sige..
                    </textarea>

                    <input type="button" value="Send" />

                </div>
                {/if}
                <!-- -->

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


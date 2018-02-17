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
                        <p class="bodyText">
                            {$maincontent}
                        </p>
                        <div class="Events">
                        {blockposition name='minisite-event'}
                    		
                        </div>
                   </div>
                    <div class="ContentRight left">
                             {if ($ZXusergroupid==$modvars.ZSELEX.shopOwnerGroup) || ($ZXusergroupid==$modvars.ZSELEX.shopAdminGroup ) || ($ZXusergroupid==2)}
								<a href="{modurl modname='ZSELEX' type='admin' func='shopsettings' shop_id=$smarty.request.shop_id}"><img src="modules/ZSELEX/images/F2B_EDIT_button.png" width="220px" class="skinContentRightDiv" title="{gt text='Edit Shopsettings'}" /></a>
								<br /><br />
	                             {* {blockposition name='shopadmin-settings'} *}
							 {/if}
                        {blockposition name='shopaddress'}
                        <div class="skinContentRightDiv"><!---  minisite images -->
                              {blockposition name='ministe-images'}
                        </div> <!-- minisite images Ends -->
                    </div>
                </div>
            </div>
        </div>
        <div class="ImageBlock">
            <div class="inner">
                <div class="BGContainer">	
                    <div class="ImageSection minisiteproducts"> <!--  Products starts -->
                        {blockposition name='ZSELEX-minisite-products'}
                    </div><!--  Products ends -->

				<!-- Comments -->
		        {if $smarty.request.shop_id eq '38'}
                    <h4>Comments</h4> 
                    <div class="Discussion">
                        <div class="DiscSection">
                            <div class="DiscSectionLeft">
                                <h6>    Anne Pedersen - 19. Juni kl. 10.35</h6>
                                <p>Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum.</p>
                            </div>
                            <div class="DiscSectionRight">
                                <img src="{$themepath}/images/Pointing.png" class="ShiftRight" />
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

                                <img src="{$themepath}/images/PointingActive.png" class="ShiftRight" />
                                <div class="Comment ActivComment">
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
                                <img src="{$themepath}/images/Pointing.png" class="ShiftRight" />
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
				<!-- Comments -->

                </div>
            </div>
        </div>
                                 <div id="CityPilotFotter">
        {blockposition name='citypilotfooter'}
    </div>
    </div>
   
    
</body>

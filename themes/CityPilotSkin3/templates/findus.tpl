{include file="includes/head.tpl"}

<body>
    <div class="Containter">
        <div id="CityPilotHeader">
            {blockposition name=citypilotheader}
        </div>
        <div class="BannerBlock BorderTopGrey">
           {*<img src="{$themepath}/images/Banner1.jpg" width="100%" />*}
           {displaybanner shop_id=$smarty.request.shop_id width=true}
        </div>
       {announcement shop_id=$smarty.request.shop_id} 
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
                        <div class="bodyText findus">
                              {blockposition name='minisite-googlemap'}
                        </div>
                       
                   </div>
                    <div class="ContentRight left">
                    		 {usergroupid}
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
                  <!---  employee list -->
                  <div class="Employees">
                        {blockposition name='employees'}
                    </div>
                    
                </div>
            </div>
        </div>
     <div id="CityPilotFotter">
        {blockposition name='citypilotfooter'}
    </div>
    </div>
    
   
</body>

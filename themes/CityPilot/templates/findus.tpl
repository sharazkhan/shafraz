{include file="includes/head.tpl"}
{fileversion}
{pageaddvar name="stylesheet" value="themes/CityPilot/style/CityBody.css$ver"}
<body id="CityPilotBody">
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
          {blockposition name=minisite-banner}
          {blockposition name='minisite-announcement'}
          </div>
          
        </div>
        <div class="BodyBlock">
            <div class="inner Contents">
                <div class="ContentLeft left"><!----  Center   ---->
                    {if !empty($smarty.request.shop_id)}
                       {include file="includes/shopheader.tpl"}
                     {/if}  
                    {*<div id="minisitegooglemap_block">
                    {blockposition name='minisite-googlemap'}
                    </div>*}
                    {$maincontent}
                </div>
                <div class="ContentRight left"> <!----  Right   ---->
                   <div class="sub_right_inner">
                   		 {usergroupid}
                         {if ($ZXusergroupid==$modvars.ZSELEX.shopOwnerGroup) || ($ZXusergroupid==$modvars.ZSELEX.shopAdminGroup ) || ($ZXusergroupid==2)}
							<!--a href="{modurl modname='ZSELEX' type='admin' func='shopsettings' shop_id=$smarty.request.shop_id}"><img src="modules/ZSELEX/images/F2B_EDIT_button.png" width="220px" title="{gt text='Edit Shopsettings'}" /></a>
							<br /><br /-->
                             {* {blockposition name='shopadmin-settings'} *}
						 {/if}
                    {blockposition name='shopaddress'}
                   </div>
                </div>
            </div>
        </div>
                <div><br></div>
        <div class="ImageBlock">
            <div class="inner">
                {*{blockposition name='ZSELEX-minisite-products'}*}
                   <div class="Employees">
                        {blockposition name='employees'} 
                    </div>
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

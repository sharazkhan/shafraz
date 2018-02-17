{*{include file="includes/feedback.tpl"}*}



<div class="AdminHead">
              <div id="top_headmenu">
                    <div class="inner">	
                        {shiftshop shop_id=$smarty.request.shop_id}
                           
                        <div class="right">
                            {*
                            <ul class="navlist">
                                <li><a href="#">Om CityPilot</a></li>
                                <li><a href="#">Spørgsmå</a></li>
                                <li><a href="#">Kontakt os</a></li>
                            </ul>
                            *}
                           
                             {blockposition name='verytop-right'}
                            
                        </div>
                    </div>	
                </div>
                <div class="top_Logo_Container">
                    <div class="inner">
                        <div class="Logo_Section">
                            <div class="Logo_Admin"><img src="{$imagepath}/Logo_Admin.png" /></div>
                            <div id="Logo_Section_menu">
                               {* <ul>
                                    <li><a href="{homepage}">{gt text='Home'}</a></li>
                                    <li{if $module eq 'Search'} class="selected"{/if}><a href="{modurl modname=Search}">{gt text='Search'}</a></li>
                                    {if $loggedin eq true}
                                    <li{if $module eq 'Users'} class="selected"{/if}><a href="{modurl modname=Users}">{gt text='User account panel'}</a></li>
                                    {/if}
                                </ul>*}
                                 {if $smarty.request.shop_id neq ''}
                                 {* {blockposition name='minisitemenu'} *}
                                 {blockposition name='backendmenu'}
                                 {/if}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                            
            <div class="ServiceMenuSection">
                <div class="inner">
                    {*<ul class="ServiceMenu">
                        <li><a href="#" class="active">Services</a></li>
                        <li><a href="#">Rediger </a></li>
                        <li><a href="#">Avanceret</a></li>
                    </ul>*}
                    <ul>
                    <li><a href="{modurl modname='ZSELEX' type='admin' func='serviceCart'}"><img src="{$themepath}/images/GreyBasket.png" /><span class="Grey">{gt text='Cart'}({servicecartcount})</span></a></li>
                    </ul>
                     {*{blockposition name='lowertopmenu'}*}
                     {if $smarty.request.func eq 'shopsummary'}
                     {blockposition name='shopsummary-menu'}
                     {elseif $smarty.request.func eq 'shopservices'}
                     {blockposition name='shopservices-menu'}    
                     {/if}
                     
                </div>
            </div> 

	        {*
                {if $smarty.request.func eq 'shopsummary'}
			<div>
                <div class="inner owner-content">
    	            {blockposition name='owner-content'} <!-- new position added  -->
				</div>
    	    </div>
        	{/if}
                *}
                
                
{*
<div id="PopUpBG" class="PopUpBG" style="display:none">

</div>


<div id="PopUpCenter" class="PopUpCenter" style="display:none">
    <h3 class="PopUpBGH3">Opret Annonce <span class="right ClosePopeup"><img onClick="shiftShop('hide')" src="{$themepath}/images/Close_cart.jpg"  height="10"/></span></h3>
    <ul class="ShopSection">
         <li><a href="">Skoringen Fredericia <span>Ejer</span></a></li>
         <li><a href="">Skoringen Vissenbjerg <span>Administrator</span></a></li>
         <li><a href="">Skoringen1 Vissenbjerg <span>Administrator</span></a></li>
         <li><a href="">Skoringen2 Vissenbjerg <span>Administrator</span></a></li>
         <li><a href="">Skoringen3 Vissenbjerg <span>Administrator</span></a></li>
    </ul>
</div>

*}

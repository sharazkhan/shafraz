 {fileversion}
{pageaddvar name="stylesheet" value="themes/$current_theme/style/Footer.css$ver"}
<div class="FooterBlock">
    <div class="inner">
        <div class="FotterTop">
            <div class="FotterLogo left">
                {*
                <img src="{$themepath}/images/FotterLogo.png" />
                *}
                {blockposition name='bot1-left'}
               
                
            </div>
            <div class="FotterLogo left" style="display: block; width:220px;text-align: left;color:#d2d2d2">
                     {blockposition name='bot1-center'}
                    
                    
            </div>
               
           
            
            <div id="creditcard" class="FotterPayment right" style="display: block; width:470px;text-align: left">
               {* {blockposition name='bot1-right'}*}
                {*
                <ul class="Payments">
                    <li><img src="{$themepath}/images/Electron.png" /></li>
                    <li><img src="{$themepath}/images/Master.png" /></li>
                    <li><img src="{$themepath}/images/DK.png" /></li>
                    <li><img src="{$themepath}/images/Visa.png" /></li>
                </ul>
                *}
               {cardsaccepted shop_id=$smarty.request.shop_id footer=1}
            </div>
           
        </div>
        <div class="FotterBot">
            <div class="FooterSubBlock1 left">
              
              {*  <h4>RETUR OG BETALING</h4>
                <ul>
                    <li>Returvare</li>
                    <li>Leveringspriser</li>
                    <li>Leveringstid</li>
                </ul>*}
                {assign var="termsConditionInfo" value=$modvars.ZSELEX.termsConditionInfo|unserialize}
                 <h4>{gt text='RETURN AND PAYMENT'}</h4>
                <ul>
                    <li>
                        <a  id="footer_windowajax1" href="{modurl modname='ZSELEX' type='info' func='footerLink' key='rma' shop_id=$smarty.request.shop_id shop_name=$smarty.request.shop_name}"  title="{gt text='RMA'} ">
                       {gt text='RMA'} 
                        </a>
                    </li>
                    <li>
                         <a  id="footer_windowajax2" href="{modurl modname='ZSELEX' type='info' func='footerLink' key='deliveryprices' shop_id=$smarty.request.shop_id shop_name=$smarty.request.shop_name}"  title="{gt text='Delivery prices'} ">
                         {gt text='Delivery prices'} 
                         </a>
                    </li>
                    <li>
                         <a  id="footer_windowajax3" href="{modurl modname='ZSELEX' type='info' func='footerLink' key='deliverytime' shop_id=$smarty.request.shop_id shop_name=$smarty.request.shop_name}"  title="{gt text='Delivery time'} ">
                         {gt text='Delivery time'} 
                         </a>
                    </li>
                   
                </ul>
                
               {* {blockposition name='bot2-01'} *}
            </div>                    
            <div class="FooterSubBlock1 left"> 
                {*
                <h4>BETINGELSER OG VILK�R</h4>  
                <ul>
                    <li>Handelsbetingelser</li><li>Behandling af data</li><li>Sikker bataling</li>
                </ul>
                *}
                
                 <h4>{gt text='TERMS AND CONDITIONS'}</h4>  
                <ul>
                    <li>
                        <a id="footer_windowajax4" href="{modurl modname='ZSELEX' type='info' func='footerLink' key='termsoftrade' shop_id=$smarty.request.shop_id shop_name=$smarty.request.shop_name}"  title="{gt text='Terms of trade'}">
                         {gt text='Terms of trade'} 
                        </a>
                    </li>
                    <li>
                         <a id="footer_windowajax5" href="{modurl modname='ZSELEX' type='info' func='footerLink' key='privacy' shop_id=$smarty.request.shop_id shop_name=$smarty.request.shop_name}"  title="{gt text='Privacy'}">
                        {gt text='Privacy'}
                         </a>
                    </li>
                    <li>
                         <a id="footer_windowajax6" href="{modurl modname='ZSELEX' type='info' func='footerLink' key='securepayment' shop_id=$smarty.request.shop_id shop_name=$smarty.request.shop_name}"  title="{gt text='Secure payment'}">
                         {gt text='Secure payment'}
                         </a>
                    </li>
                </ul>
              {* {blockposition name='bot2-02'} *}
            </div>
            <div class="FooterSubBlock1 left">
                {*
                <h4>Tilmeld dig vores nyhedsbrev</h4>
                <div class="Newletter">
                    <input type="text" value=" DIN EMAILADRESSE" />
                    <input type="button" value="OK" />
                </div>
                <div class="NewletterBot">
                    N�r du tilmelder dig vores nyhedsbrev f�r du automatisk tilsendt alle vores gode tibud, samt vores specielle medlemstilbud.
                </div>
                *}
               {blockposition name='bot2-03'}
            </div>

            <div class="FooterSubBlock2 left">
                <div class="FotterRightSec followus">
                    {*
                    <h4>F�lg os</h4>
                    <ul class="socialIcons">
                        <li><a href="#" id="fb"></a></li>
                        <li><a href="#" id="tw"></a></li>
                        <li><a href="#" id="Pinterest"></a></li>
                        <li><a href="#" id="GPluse"></a></li>
                        <li class="Zero"><a href="#" id="Share"></a></li>
                    </ul>
                    *}
                    {blockposition name='bot2-04'}
                </div>
            </div>
        </div>
    </div>
                
                <!--<div align='center'>
                     <a href='http://www.hit-counts.com'><img src='http://www.hit-counts.com/counter.php?t=MTI2MDgzMQ==' border='0' alt='Web Counter'></a>
                 </div>-->
</div> 
     <!-- Hide the h4 for footer block by js -->
      <script type="text/javascript" src="modules/ZSELEX/javascript/bigtext/bigtext.js"></script>
   <script> 
    //jQuery("#CityPilotFotter").children('div:first').children('h4').stop(true, true).css("display", "none");
    var defwindowajax = new Zikula.UI.Window($('footer_windowajax1'),{resizable: true});
    var defwindowajax = new Zikula.UI.Window($('footer_windowajax2'),{resizable: true});
    var defwindowajax = new Zikula.UI.Window($('footer_windowajax3'),{resizable: true});
    var defwindowajax = new Zikula.UI.Window($('footer_windowajax4'),{resizable: true});
    var defwindowajax = new Zikula.UI.Window($('footer_windowajax5'),{resizable: true});
    var defwindowajax = new Zikula.UI.Window($('footer_windowajax6'),{resizable: true});
  
        //Initialize Font sizxe auto resizing
        //var $ = jQuery;
        var bt = BigText.noConflict(true);
        jQuery.fn.bt = bt.jQueryMethod;

        jQuery('#shopTitleDiv').bt({maxfontsize:50});
   </script>
    <!-------------------------------------------->
   

 
{fileversion}
<!--  selection box js & css  -->
{pageaddvar name='javascript' value="modules/ZSELEX/javascript/searchlist.js$ver"}
{pageaddvar name='javascript' value="modules/ZSELEX/javascript/selections.js$ver"}
{pageaddvar name='javascript' value="modules/ZSELEX/javascript/selectioncookies.js$ver"}
{pageaddvar name='javascript' value="modules/ZSELEX/javascript/jqueryautocomplete/js/jquery-ui-1.8.2.custom.min.js$ver"} 
{*{pageaddvar name='javascript' value='modules/ZSELEX/javascript/validation.js'} *}
{pageaddvar name='javascript' value="modules/ZSELEX/javascript/combo/jquery.sexy-combo.js$ver"}
{*
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/searchlist.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/selections.js'}
*}
{pageaddvar name="stylesheet" value="modules/ZSELEX/javascript/jqueryautocomplete/css/smoothness/jquery-ui-1.8.2.custom.css$ver"}
{pageaddvar name="stylesheet" value="modules/ZSELEX/style/combo/sexy-combo.css$ver"}
{pageaddvar name="stylesheet" value="modules/ZSELEX/style/combo/sexy/sexy.css$ver"}
{pageaddvar name="stylesheet" value="themes/CityPilot/style/selectionbox.css$ver"}
<!--  selection box js & css ends  -->

{pageaddvar name="stylesheet" value="themes/CityPilot/style/Header.css$ver"}
<script type="text/javascript" src="themes/CityPilot/javascript/themejs.js{$ver}"></script>

<a href="#" class="scrollup">Scroll</a>
<div class="HeaderBlock">
     <div class="TopMenu">
                 <div class="inner">
                     <div class="CitySelect left">
                           <span id="SelectedRegion" class="Orange">{if !empty($smarty.cookies.region_cookie)}{$smarty.cookies.regionname_cookie}{/if}</span>
                           <span id="SelectedCity" class="Orange">{if !empty($smarty.cookies.city_cookie)}&nbsp;&raquo;&nbsp;{$smarty.cookies.cityname_cookie}{/if}</span>
                     </div>
                        <div class="right">
                             <div class="navi-outer">
                              <div class="top-menu-inner">
                           {blockposition name='verytop-right'}
                              </div>
                               <div class="smart-shop-menu">
                                 <!-- place shop menu shortcode here -->
                                  {blockposition name='minisitemenu'}
                              </div>
                             </div>
                        </div>
                 </div>
    </div>
                        
    <div class="LogoSection">
        <div class="inner">
           {*<img src="{$themepath}/images/Logo.png" class="logo" />*}
            {blockposition name='top-left'}
            <div class="top-center-block">
             {blockposition name='top-center'}
            </div>
            {include file="blocks/citypilottheme/map.tpl"}
             <!-- MENU BUTTON -->
             <button class="wsite-nav-button">Menu</button>
            
        </div>
         {*<div style="width:400px;margin-left:426px;height:77px;word-wrap: break-word;">
             {blockposition name='top-center'}
         </div>*}
         
    </div>

</div>
<div class="SearchBlock">
            <div class="inner">
                <div class="SearchContent left">
                  {blockposition name='selectionbox'}
                </div>
            </div>
</div>
    <!-- Hide the h4 for footer block by js -->
     <script> 
          //  jQuery("#CityPilotHeader").children('div:first').children('h4').stop(true, true).css("display", "none");
     </script>
    <!-------------------------------------------->
               

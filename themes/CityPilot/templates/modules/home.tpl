{include file="includes/head.tpl"}
{fileversion}
{pageaddvar name="stylesheet" value="themes/CityPilot/style/CityBody.css$ver"}

<body id="CityPilotBody" >
     {****  CityPilot Header    ****}
    <div id="CityPilotHeader">
    {blockposition name=citypilotheader}
    </div>
     {****  CityPilot Header Ends    ****}
    <div class="Containter">
           {searchbreadcrum}
        {*{include file="includes/header.tpl"}*}
         <div class="BannerBlock">
            <div class="inner">
                
                {* <img src="{$themepath}/images/Banner.png" />*}
                  {blockposition name='exclusive_events'}
                
            </div>
         </div>

        <div class="BodyBlock">
            <div class="inner Contents">
                <div class="ContentLeft left left_home">
                   {* {blockposition name=center-home} *}
                   {*{blockposition name='product-ad'}*}
                   {blockposition name='specialdeals'}
                   {blockposition name=center-home}
                 </div>
                <div class="ContentRight left right_home">
                    
                       {blockposition name='product-ad'}
                       {blockposition name='upcomming-events'}
                       {blockposition name=right-home}
                      
                       {*
                       {blockposition name='googlemapzselex'} 
                       {blockposition name=right-home}
                       {blockposition name=zencart-products}
                       *}
                   
                </div>
            </div>
        </div>

        <div class="ImageBlock">
            <div class="inner">
                <div class="ImageSection">
                    <!---- full width  ----->
                </div>
                 {blockposition name='newshops'}
            </div>
        </div>

       {* {include file="includes/footer.tpl"}  *}

    </div>
     {****  CityPilot Footer    ****}
    <div id="CityPilotFotter">
               {blockposition name='citypilotfooter'}
    </div>
    
     {****  CityPilot Footer Ends   ****}
</body>
</html>

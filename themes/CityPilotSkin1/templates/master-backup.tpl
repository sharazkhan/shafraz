{include file="includes/head.tpl"}
<script>
 document.observe("dom:loaded", function(){
  //alert('The DOM is loaded!');
  displayBlocks();
});
</script>
{pageaddvar name="stylesheet" value="themes/CityPilot/style/CityBody.css"}
<body id="body" {*onLoad="displayBlocks();"*}>
    {****  CityPilot Header    ****}
    <div id="CityPilotHeader">
    {blockposition name=citypilotheader}
    </div>
    
     {****  CityPilot Header Ends    ****}
    <div class="Containter">
       {* {include file="includes/header.tpl"} *}
         <div class="BannerBlock">
            <div class="inner">
                <img src="{$themepath}/images/Banner.png" />
            </div>
        </div>
        <div class="BodyBlock">
            <div class="inner Contents">
                <div class="ContentLeft ContentLeftMaster left">
                      {$maincontent}
                      
                </div>
                <div class="ContentRight right">
                   {*{blockposition name='left'}*}
                    {blockposition name='upcomming-events'}
                </div>
            </div>
        </div>
        <div class="ImageBlock">
            <div class="inner">
                <div class="ImageSection"></div>
            </div>
        </div>

        {*{include file="includes/footer.tpl"}*}

    </div>
         {****  CityPilot Footer    ****}
    <div id="CityPilotFotter">
               {blockposition name='citypilotfooter'}
    </div>
    
     {****  CityPilot Footer Ends   ****}
</body>
</html>

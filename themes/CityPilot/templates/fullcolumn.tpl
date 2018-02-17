{include file="includes/head.tpl"}
{fileversion}
{pageaddvar name="stylesheet" value="themes/CityPilot/style/CityBody.css$ver"} 

<input type="hidden" id="crrntFunc" value="{$smarty.request.func}">
<script>
    /*
    document.observe("dom:loaded", function(){
    //searchBreadcrums();
    if (jQuery('div.z-errormsg').length &&  jQuery('#crrntFunc').val() == 'login') {
  //alert('The DOM is loaded!');
  
  jQuery(".z-menulinks").addClass("ErrorClass");
  }
    else if (jQuery('#crrntFunc').val() == 'lostpassword' && !jQuery('div.z-errormsg').length) {
     jQuery(".z-menulinks").addClass("ErrorClass");
    }
    else if(jQuery('#crrntFunc').val() == 'lostpassword' && jQuery('div.z-errormsg').length){
      jQuery(".z-menulinks").addClass("lostpasswordError");
    }
     
   else if (jQuery('#crrntFunc').val() == 'lostpasswordcode' && !jQuery('div.z-errormsg').length || jQuery('#crrntFunc').val() == 'lostPasswordCode' && !jQuery('div.z-errormsg').length ) {
     //alert('hellloo');
     jQuery(".z-menulinks").addClass("lostpasswordcode");
    }
     else if(jQuery('#crrntFunc').val() == 'lostPasswordCode' && jQuery('div.z-errormsg').length){
      //alert('hiii');
      jQuery(".z-menulinks").addClass("lostPasswordCodeError");
    }
    
    else if (jQuery('#crrntFunc').val() == 'register') {
      //jQuery(".z-gap").css("padding","0px ,important");
      jQuery('.z-gap').attr('style', 'padding: 0px !important');
    }
  
  else if (jQuery('#crrntFunc').val() == 'verifyRegistration' && !jQuery('div.z-errormsg').length) {
      //jQuery(".z-gap").css("padding","0px ,important");
       jQuery(".z-menulinks").addClass("verifyRegistration");
      
    }
    else if (jQuery('#crrntFunc').val() == 'verifyRegistration' && jQuery('div.z-errormsg').length) {
      //jQuery(".z-gap").css("padding","0px ,important");
       jQuery(".z-menulinks").addClass("verifyRegistrationError");
      
    }
  
});

 */
 
</script>
{if $smarty.request.func eq 'verifyRegistration'}
    <style>
        .z-menulinks{display:none !important}
    </style>
{/if}
<body id="body" {*onLoad="displayBlocks();"*}>
     {****  CityPilot Header    ****}
    <div id="CityPilotHeader">
    {blockposition name=citypilotheader}
    </div>
     {****  CityPilot Header Ends    ****}
    <div class="Containter">
       {* {include file="includes/header.tpl"} *}
        {if $smarty.request.module neq 'Users'}
                    {searchbreadcrum}
                {/if}   
         <div class="BannerBlock">
            <div class="inner">
                <!--<img src="{$themepath}/images/Banner.png" />-->
                
            </div>
        </div>
        <div class="BodyBlock">
            <div class="inner Contents">
                <div style="width:630px">{insert name='getstatusmsg'}</div>
                <div class="Fullwidth">
                      {$maincontent}
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
</body>
</html>

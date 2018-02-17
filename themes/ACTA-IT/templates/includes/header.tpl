<!-- Header-->
<div class="HeaderBlock">

    <div class="Header">
        <div class="TopBannerSection">
            <div class="Logo"> 
                <a href="{homepage}"><img src="{$imagepath}/Logo.jpg" align="ACTA-IT"/></a>
            </div>
            <div class="Flash">
                 {blockposition name='acta-flash'} 
                
            </div>
           
            <div class="LogoRight">
                <div class="TopRightmenu">
                   	

               {blockposition name='very-top-menu'} 

                </div>   

                <div class="MenuFlag">
                  {*  <dt>
                    <dl><a href="#"><img src="{$imagepath}/Denmark.jpg" /></a></dl>
                    <dl><a href="#"><img src="{$imagepath}/UK.jpg" /></a></dl>
                    <dl><a href="#"><img src="{$imagepath}/Germany.jpg" /></a></dl>
                    </dt> *}
                    
                    {blockposition name='language-block'}
             
                </div>  

                    <div class="SearchBlock" >
                    <div class="search">
                        <form class="search" action="index.php?module=search&amp;func=search" method="post">
                            <div>
                                <input type="hidden" name="overview" value="1" />
                                <input type="hidden" name="bool" value="AND" />
                                <input id="search_plugin_q" type="text" name="q" value="{gt text="search..."}" size="12" tabindex="0" class="textbox" onblur="if(this.value=='') this.value='search...';" onfocus="if(this.value=='{gt text="search..."}') this.value='';"  />
                                <input type="submit" value="" tabindex="0"class="button"  />
                            </div>
                        </form>


                    </div>

                </div>               


            </div>           
        </div>
        <div class="Menu_Section">   
            {blockposition name=top-menu}     


        </div>    

      
    </div>
</div>
<!-- End of Header-->  


 <!--Banner-->  
            <div class="Banner_Container">
                <div class="Banner">
                    <div class="BannerImage">
                          {blockposition name='product-flash'} 
                       
                       
                    </div>
                    <div class="BannerRightLogin" id="BannerRightLogin">

        
                     {blockposition name=login-front}
                        
                        {if $uid eq true}
                        <div id="greetings">
                            {displaygreeting} 
                            <br>
                                <form action="{pnmodurl modname='users' func='logout'}" method="post">
                                    <input type="submit" value="">
                                </form>
                                <a href="{pnmodurl modname='users'}">{gt text="user account"}</a>
                        </div>
                        {/if}
                    </div>  

                </div>    
            </div>
            <!-- End of Banner--> 


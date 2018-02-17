<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>ACTA-IT</title>

    </head>
    <link rel="stylesheet" type="text/css" href="{$stylepath}/style.css"/>
    <body id="Landing">

        <div class="Containter">
            {include file="includes/header.htm"}
          
          <!--Banner-->  
    <div class="Banner_Container">
    	<div class="Banner">
        	<div class="BannerImage">
        	<img src="{$imagepath}/Banner.jpg" />
        	</div>
            <div class="BannerRightLogin">
        
             {blockposition name=login-block}
					{if $uid eq true}
                     <div id="greetings">
					 {displaygreeting} 
                     <br>
                         <form action="{pnmodurl modname='users' func='logout'}" method="post">
                            <input type="submit" value="">
                        </form>
                         <a href="{pnmodurl modname='users'}">user account</a>
						 </div>
                     {/if}
        	</div>  
        
        </div>    
    </div>
<!-- End of Banner--> 


<!--Content Section-->  
    <div class="Content_Section">
    	<div class="Content">
        		 <!--Content Left-->
                <div class="ContentLeftStd">                         
       			</div>
                 <!--End of Content Left-->
                
                <!--Content Middle-->
                <div class="ContentMiddleStd"> </div> 
                <!--End of Content Middle-->
                 
        		<!--Content right-->
                <div class="ContentRightStd">
                
                </div>
                <!---End of Content Right-->
                
        </div>    
    </div>
<!-- End of Content Section-->  

<!--ImageBox Section-->  
    <div class="ImageBox_Section">
    	<div class="Sec1">
        	
            
            <div class="ImageBox1" >
            <img src="{$imagepath}/Img1.jpg" />
            <h2>IMAGE SEC 1</h2>
            <p>ACTA-IT designer, installerer og konfigurerer
netvÃ¦rksinstallationer, Ã¸konomi- og weblÃ¸sninger
efter kundens behov.
            </p>
            <p class="link"><a href="#">Read More...</a></p>
            </div> 
            
            <div class="ImageBox1" >
            <img src="{$imagepath}/Img2.jpg" />
            <h2>IMAGE SEC 2</h2>
            <p>ACTA-IT designer, installerer og konfigurerer
netvÃ¦rksinstallationer, Ã¸konomi- og weblÃ¸sninger
efter kundens behov.
            </p>
            <p class="link"><a href="#">Read More...</a></p>
            </div> 
            
            
            
            <div class="ImageBox1" >
            <img src="{$imagepath}/Img3.jpg" />
            <h2>IMAGE SEC 3</h2>
            <p>ACTA-IT designer, installerer og konfigurerer
netvÃ¦rksinstallationer, Ã¸konomi- og weblÃ¸sninger
efter kundens behov.
            </p>
            <p class="link"><a href="#">Read More...</a></p>
            </div> 

            
                    
            
            

        </div>    
    </div>
<!-- End of ImageBox-->  

            {include file="includes/footer.htm"}
            <!--end of footer -->


        </div>   <!--end of Containter -->

    </body>
</html>

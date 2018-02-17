<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>ACTA-IT</title>

    </head>
    <link rel="stylesheet" type="text/css" href="{$stylepath}/style.css"/>
    <body id="Landing" class="Master">

        <div class="Containter">
            {include file="includes/header.tpl"}

           


            <!--Content Section-->  
            <div class="Content_Section">
                <div class="Content">
                    <div class="ContentLeft">
                        {blockposition name=left}

                    </div>  

		     <div class="ContentMiddle">
                        {$maincontent}
                        {blockposition name=center}

                    </div>  

                    <div class="ContentRight">
                         {blockposition name=right}


                    </div>
                </div>    
            </div>
            <!-- End of Content Section-->  

            <!--ImageBox Section-->  
            <div class="ImageBox_Section">
                <div class="ImageBox">


                     {blockposition name=newslist-bot}

                </div>    
            </div>
            <!-- End of ImageBox-->  

            {include file="includes/footer.tpl"}
            <!--end of footer -->


        </div>   <!--end of Containter -->

    </body>
</html>

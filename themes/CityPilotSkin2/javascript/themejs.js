jQuery(document).ready(function(){
    jQuery(window).scroll(function(){
        if (jQuery(this).scrollTop() > 100) {
            jQuery('.scrollup').fadeIn();
        } else {
            jQuery('.scrollup').fadeOut();
        }
    }); 
    jQuery('.scrollup').click(function(){
        jQuery("html, body").animate({
            scrollTop: 0
        }, 600);
        return false;
    });
    
    
    /*******************MAP***********************/ 
    jQuery("#mapImg").click(function(){
        ID = document.getElementById("MapDivImg");
        var mapCss = jQuery("#MapDivImg").css("background-color");
        // alert(mapCss);
        if(mapCss !=  "rgb(231, 231, 231)"){
            jQuery("#MapDivImg").css("background-color","#e7e7e7");
        }
        else{
            //alert('hii');
            jQuery("#MapDivImg").css("background-color","");
        }
        jQuery("#MapBlock").toggle();
    });
    
    
  
    
    window.onkeyup = function (event) { // for escape key
        if (event.keyCode == 27) {
            var mapCss1 = jQuery("#MapBlock").css("display");
            if(mapCss1 == "block"){
                jQuery("#MapDivImg").css("background-color","");
                jQuery("#MapBlock").toggle();
           
            }
        }
    }
    
    
/*******************MAP ENDS***********************/  
 
});
/*******************MAP***********************/ 
function Orange(id,len)
{
    var name = id.slice(0, -1);
    for(var i=1; i<=len ;i++){
        document.getElementById(name+i).style.fill = "#e45624";
    }

}

function Gray(id,len){
    var name = id.slice(0, -1);
    for(var i=1; i<=len ;i++){
        document.getElementById(name+i).style.fill = "#727F84";
    }
}
/*******************MAP ENDS***********************/  
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>City Pilot</title>

{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='jquery.ui'}
{pageaddvar name='javascript' value='zikula.ui'}
{pageaddvar name="stylesheet" value="themes/CityPilotSkin1/style/skinBody.css"}
{pageaddvar name="stylesheet" value="themes/CityPilotSkin1/style/skin1.css"}

</head>
<script type="text/javascript">
     jQuery(document).ready(function(){ 
        jQuery(window).scroll(function(){
            if (jQuery(this).scrollTop() > 100) {
                jQuery('.scrollup').fadeIn();
            } else {
                jQuery('.scrollup').fadeOut();
            }
        }); 
     jQuery('.scrollup').click(function(){
            jQuery("html, body").animate({ scrollTop: 0 }, 600);
            return false;
        });
 
    });
</script>
<a href="#" class="scrollup">Scroll</a>


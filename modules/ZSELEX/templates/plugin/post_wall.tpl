
  <style type="text/css">
      
      button.FbShare{ background:#4965b5;      
       padding:1px; color:white; border:1px solid  #CCC; border-radius:5px; font-size:12px;
                font-family: "Adelle Regular";}
            button.FbShare:hover {
                background: #6580b3; /* Old browsers */
                background: -moz-linear-gradient(top,  #6580b3 0%, #5773ac 45%, #3d5c9e 100%); /* FF3.6+ */
                background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#6580b3), color-stop(45%,#5773ac), color-stop(100%,#3d5c9e)); /* Chrome,Safari4+ */
                background: -webkit-linear-gradient(top,  #6580b3 0%,#5773ac 45%,#3d5c9e 100%); /* Chrome10+,Safari5.1+ */
                background: -o-linear-gradient(top,  #6580b3 0%,#5773ac 45%,#3d5c9e 100%); /* Opera 11.10+ */
                background: -ms-linear-gradient(top,  #6580b3 0%,#5773ac 45%,#3d5c9e 100%); /* IE10+ */
                background: linear-gradient(to bottom,  #6580b3 0%,#5773ac 45%,#3d5c9e 100%); /* W3C */
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#6580b3', endColorstr='#3d5c9e',GradientType=0 ); /* IE6-9 */

               
            }
            .FbShare img{ vertical-align:top}

        </style>
       
        <script>
            var ecnodeVar;
            ecnodeVar{{$id}} = '{{$encode}}';
             var test = ecnodeVar{{$id}};
            
        </script>

<div id="fb_div">
   
     {* <input type="button" value="Post on Wall" onClick="post_on_wall();" />*}
       <button class="FbShare" onClick="post_on_wall('{$id}')"> <img src="{$themepath}/images/facebook.png" />
           {gt text='Post on wall'}

       </button>
</div>
<input type="hidden" id="link{$id}" value="{$info.link}">
<input type="hidden" id="image{$id}" value="{$info.image}">
<input type="hidden" id="title{$id}" value="{$info.title}">
<input type="hidden" id="caption{$id}" value="{$info.caption}">
<input type="hidden" id="description{$id}" value="{$info.description}">

<script>
    {ldelim}
    window.fbAsyncInit = function()
    {
        FB.init({
            appId  : '{{$appId}}',
            status : true, // check login status
            cookie : true, // enable cookies to allow the server to access the session
            xfbml  : true , // parse XFBML
            oauth : true // Enable oauth authentication
        });
 
 
    };
 
    function post_on_wall(id)
    {
         // alert('helloo'); exit();
        // alert(ecnodeVar0); exit();
         /*  var tes = eval(encode);
           //alert(tes); exit();
           //var encode = '{{$encode}}';
           //var encode = encode;
           var json = pndejsonize(tes);
           //alert(json.link); exit();
           var link = json.link;
           var image =  json.image;
           var title = json.title;
           var caption = json.caption;
           var description  = json.description; */
           
           var link = jQuery("#link"+id).val();
           //alert(link); exit();
           var image =  jQuery("#image"+id).val();
           var title = jQuery("#title"+id).val();
           var caption = jQuery("#caption"+id).val();
           var description  = jQuery("#description"+id).val();
        
       // alert(title + '-' + image); exit();
        var opts = {
            method: 'feed',
           // message : document.getElementById('fb_message').value,
            name : title,
            link : link,
            description : description,
            picture : image
        };
        FB.ui(opts, callback_one);
 
      
    }
   
    
    function callback_one(response)
    {
        //alert('posted to face book successfully!');
        if (response && response.post_id) {
            alert(Zikula.__('Post was published', 'module_zselex_js'));
        } else {
           // alert('Post was not published.');
        }
    }
    {rdelim}
</script>

<div id="fb-root"></div>

<script>
    (function() {
        var e = document.createElement('script');
        // replacing with an older version until FB fixes the cancel-login bug
        e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
        //e.src = 'scripts/all.js';
        e.async = true;
        document.getElementById('fb-root').appendChild(e);
    }());
</script>
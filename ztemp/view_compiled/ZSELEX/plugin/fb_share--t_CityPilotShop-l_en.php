<?php /* Smarty version 2.6.28, created on 2017-10-29 16:07:19
         compiled from plugin/fb_share.tpl */ ?>
<div id="fb-root"></div>
<script><?php echo '
    jQuery( window ).load(function() {
    (function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/da_DK/sdk.js#xfbml=1&appId='; ?>
<?php echo $this->_tpl_vars['appId']; ?>
<?php echo '&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, \'script\', \'facebook-jssdk\'));
 });
'; ?>
</script>

<div class="fb-share-button" data-href="<?php echo $this->_tpl_vars['url']; ?>
" data-type="button"></div>
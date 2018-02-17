<?php /* Smarty version 2.6.28, created on 2017-09-30 14:23:41
         compiled from includes/head.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'fileversion', 'includes/head.tpl', 4, false),array('function', 'pagegetvar', 'includes/head.tpl', 7, false),array('function', 'pageaddvar', 'includes/head.tpl', 30, false),array('function', 'gt', 'includes/head.tpl', 97, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo smarty_function_fileversion(array(), $this);?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo smarty_function_pagegetvar(array('name' => 'title'), $this);?>
</title>
<!--<meta name="viewport" content="width=device-width,initial-scale=1">-->
<meta name="description" content="<?php echo $this->_tpl_vars['metatags']['description']; ?>
" />
<meta name="keywords" content="<?php echo $this->_tpl_vars['metatags']['keywords']; ?>
" />

<link rel="shortcut icon" href="<?php echo $this->_tpl_vars['imagepath']; ?>
/favicon.ico">
<link rel="apple-touch-icon" sizes="57x57" href="<?php echo $this->_tpl_vars['imagepath']; ?>
/apple-touch-icon-57x57.png">
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo $this->_tpl_vars['imagepath']; ?>
/apple-touch-icon-114x114.png">
<link rel="apple-touch-icon" sizes="72x72" href="<?php echo $this->_tpl_vars['imagepath']; ?>
/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="144x144" href="<?php echo $this->_tpl_vars['imagepath']; ?>
/apple-touch-icon-144x144.png">
<link rel="apple-touch-icon" sizes="60x60" href="<?php echo $this->_tpl_vars['imagepath']; ?>
/apple-touch-icon-60x60.png">
<link rel="apple-touch-icon" sizes="120x120" href="<?php echo $this->_tpl_vars['imagepath']; ?>
/apple-touch-icon-120x120.png">
<link rel="apple-touch-icon" sizes="76x76" href="<?php echo $this->_tpl_vars['imagepath']; ?>
/apple-touch-icon-76x76.png">
<link rel="apple-touch-icon" sizes="152x152" href="<?php echo $this->_tpl_vars['imagepath']; ?>
/apple-touch-icon-152x152.png">
<link rel="icon" type="image/png" href="<?php echo $this->_tpl_vars['imagepath']; ?>
/favicon-196x196.png" sizes="196x196">
<link rel="icon" type="image/png" href="<?php echo $this->_tpl_vars['imagepath']; ?>
/favicon-160x160.png" sizes="160x160">
<link rel="icon" type="image/png" href="<?php echo $this->_tpl_vars['imagepath']; ?>
/favicon-96x96.png" sizes="96x96">
<link rel="icon" type="image/png" href="<?php echo $this->_tpl_vars['imagepath']; ?>
/favicon-16x16.png" sizes="16x16">
<link rel="icon" type="image/png" href="<?php echo $this->_tpl_vars['imagepath']; ?>
/favicon-32x32.png" sizes="32x32">
<meta name="msapplication-TileColor" content="#da532c">
<meta name="msapplication-TileImage" content="<?php echo $this->_tpl_vars['imagepath']; ?>
/mstile-144x144.png">
<meta name="msapplication-config" content="<?php echo $this->_tpl_vars['imagepath']; ?>
/browserconfig.xml">

<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => 'jquery'), $this);?>

<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => 'zikula.ui'), $this);?>

<?php echo smarty_function_pageaddvar(array('name' => 'jsgettext','value' => "module_zselex_js:ZSELEX"), $this);?>

<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => "modules/ZSELEX/javascript/lazyload/lazyload.js".($this->_tpl_vars['ver'])), $this);?>

</head>

<script type="text/javascript"><?php echo '
     jQuery(document).ready(function(){
     //jQuery(\'#pageload\').val(\'1\');
     if ( typeof displayBlocks == \'function\' ) { 
   // displayBlocks(); 
      }
        jQuery(window).scroll(function(){
            if (jQuery(this).scrollTop() > 100) {
                jQuery(\'.scrollup\').fadeIn();
            } else {
                jQuery(\'.scrollup\').fadeOut();
            }
        }); 
     jQuery(\'.scrollup\').click(function(){
            jQuery("html, body").animate({ scrollTop: 0 }, 600);
            return false;
        });
 
    });
 
 document.observe("dom:loaded", function(){
  //alert(\'The DOM is loaded!\');
  //displayBlocks();
});

window.onload=function(){
jQuery(\'#pageload\').val(\'0\');
 searchBreadcrums();
};

'; ?>
</script>
<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => "modules/ZSELEX/javascript/searchbreadcrum.js".($this->_tpl_vars['ver'])), $this);?>
 

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "includes/feedback.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "includes/analytics/tracking.php", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<!-- MOBILE NAVIGATION -->
<link rel="stylesheet" href="themes/CityPilot/style/smart-navigation.css<?php echo $this->_tpl_vars['ver']; ?>
" type="text/css" />
<script><?php echo '
jQuery(document).ready(function(){
  jQuery(".wsite-nav-button").click(function () {
  jQuery(".wsite-nav-button,.TopMenu,.smart-shop-menu").toggleClass("open");
  });
  jQuery(".wsite-nav-button2").click(function () {
  jQuery(".wsite-nav-button2,.minisitemenu_block").toggleClass("open");
  });
});
'; ?>
</script>
<!-- MOBILE NAVIGATION END-->


<a href="#" class="scrollup"><?php echo smarty_function_gt(array('text' => 'Scroll'), $this);?>
</a>
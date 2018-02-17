<?php /* Smarty version 2.6.28, created on 2017-12-10 15:16:05
         compiled from includes/head.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'pagegetvar', 'includes/head.tpl', 7, false),array('function', 'pageaddvar', 'includes/head.tpl', 41, false),array('function', 'browsersupport', 'includes/head.tpl', 48, false),)), $this); ?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo smarty_function_pagegetvar(array('name' => 'title'), $this);?>
</title>

    <!-- favicon (favicon-generator.org)-->
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo $this->_tpl_vars['themepath']; ?>
/images/favicons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo $this->_tpl_vars['themepath']; ?>
/images/favicons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo $this->_tpl_vars['themepath']; ?>
/images/favicons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo $this->_tpl_vars['themepath']; ?>
/images/favicons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo $this->_tpl_vars['themepath']; ?>
/images/favicons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo $this->_tpl_vars['themepath']; ?>
/images/favicons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo $this->_tpl_vars['themepath']; ?>
/images/favicons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo $this->_tpl_vars['themepath']; ?>
/images/favicons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $this->_tpl_vars['themepath']; ?>
/images/favicons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo $this->_tpl_vars['themepath']; ?>
/images/favicons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $this->_tpl_vars['themepath']; ?>
/images/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo $this->_tpl_vars['themepath']; ?>
/images/favicons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $this->_tpl_vars['themepath']; ?>
/images/favicons/favicon-16x16.png">
    <link rel="manifest" href="<?php echo $this->_tpl_vars['themepath']; ?>
/images/favicons/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?php echo $this->_tpl_vars['themepath']; ?>
/images/favicons/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- favicon end -->

    <!-- Bootstrap -->
    <link href="<?php echo $this->_tpl_vars['themepath']; ?>
/style/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $this->_tpl_vars['themepath']; ?>
/style/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo $this->_tpl_vars['themepath']; ?>
/style/jquery.bxslider.css" rel="stylesheet">
    <link href="<?php echo $this->_tpl_vars['themepath']; ?>
/style/jquery.fancybox.css" rel="stylesheet">
    <link href="<?php echo $this->_tpl_vars['themepath']; ?>
/style/chosen.css" rel="stylesheet">
       
            <script type="text/javascript" src="<?php echo $this->_tpl_vars['themepath']; ?>
/js/jquery.min.js"><?php echo ''; ?>
</script>
    <?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => 'zikula.ui'), $this);?>

    <?php echo smarty_function_pageaddvar(array('name' => 'jsgettext','value' => "module_zselex_js:ZSELEX"), $this);?>

    
    


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <?php echo smarty_function_browsersupport(array(), $this);?>

  </head>
  <?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => ($this->_tpl_vars['themepath'])."/javascript/searchbreadcrum.js"), $this);?>
 
  
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "includes/analytics/tracking.php", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <input type="hidden" value="CityPilotresponsive" id="curent_theme">
  
  <script><?php echo '
      window.onload=function(){
jQuery(\'#pageload\').val(\'0\');
 searchBreadcrums();
};
      '; ?>
</script>
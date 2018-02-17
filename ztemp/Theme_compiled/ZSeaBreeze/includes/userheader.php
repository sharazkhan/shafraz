<?php /* Smarty version 2.6.28, created on 2017-10-01 12:38:28
         compiled from includes/userheader.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'userwelcome', 'includes/userheader.tpl', 2, false),array('function', 'blockposition', 'includes/userheader.tpl', 3, false),array('function', 'homepage', 'includes/userheader.tpl', 6, false),array('function', 'gt', 'includes/userheader.tpl', 11, false),array('function', 'modurl', 'includes/userheader.tpl', 12, false),array('modifier', 'ucwords', 'includes/userheader.tpl', 2, false),)), $this); ?>
<div id="topline" class="z-clearfix">
    <div class="z-floatleft"><?php echo ((is_array($_tmp=smarty_function_userwelcome(array(), $this))) ? $this->_run_mod_handler('ucwords', true, $_tmp) : ucwords($_tmp));?>
</div>
    <div class="z-floatright"><?php echo smarty_function_blockposition(array('name' => 'search'), $this);?>
</div>
</div>
<div id="header" class="z-clearfix">
    <h1><a href="<?php echo smarty_function_homepage(array(), $this);?>
" title="<?php echo $this->_tpl_vars['modvars']['ZConfig']['slogan']; ?>
"><?php echo $this->_tpl_vars['modvars']['ZConfig']['sitename']; ?>
</a></h1>
    <?php echo smarty_function_blockposition(array('name' => 'topnav','assign' => 'topnavblock'), $this);?>

    <?php if (empty ( $this->_tpl_vars['topnavblock'] )): ?>
    <div id="navi" class="z-clearer">
        <ul id="nav">
            <li class="page_item"><a href="<?php echo smarty_function_homepage(array(), $this);?>
" title="<?php echo smarty_function_gt(array('text' => "Go to the site's home page"), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Home'), $this);?>
</a></li>
            <li class="page_item"><a href="<?php echo smarty_function_modurl(array('modname' => 'Users','type' => 'user','func' => 'main'), $this);?>
" title="<?php echo smarty_function_gt(array('text' => 'Go to your account panel'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'My Account'), $this);?>
</a></li>
            <li class="page_item"><a href="<?php echo smarty_function_modurl(array('modname' => 'Search','type' => 'user','func' => 'main'), $this);?>
" title="<?php echo smarty_function_gt(array('text' => 'Search this site'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Site search'), $this);?>
</a></li>
        </ul>
    </div>
    <?php else: ?>
    <?php echo $this->_tpl_vars['topnavblock']; ?>

    <?php endif; ?>
</div>
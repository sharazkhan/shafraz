<?php /* Smarty version 2.6.28, created on 2017-09-30 14:39:21
         compiled from includes/adminheader.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'userwelcome', 'includes/adminheader.tpl', 2, false),array('function', 'blockposition', 'includes/adminheader.tpl', 3, false),array('function', 'homepage', 'includes/adminheader.tpl', 6, false),array('function', 'gt', 'includes/adminheader.tpl', 10, false),array('function', 'modurl', 'includes/adminheader.tpl', 13, false),array('modifier', 'ucwords', 'includes/adminheader.tpl', 2, false),)), $this); ?>
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
    <div id="navi" class="z-clearer">
        <ul id="nav">
            <li class="page_item">
                <a href="<?php echo smarty_function_homepage(array(), $this);?>
"><span><?php echo smarty_function_gt(array('text' => 'Home'), $this);?>
</span></a>
            </li>
            <li class="page_item">
                <a href="<?php echo smarty_function_modurl(array('modname' => 'Settings','type' => 'admin','func' => 'main'), $this);?>
"><span><?php echo smarty_function_gt(array('text' => 'Settings'), $this);?>
</span></a>
            </li>
            <li class="page_item">
                <a href="<?php echo smarty_function_modurl(array('modname' => 'Extensions','type' => 'admin','func' => 'main'), $this);?>
"><span><?php echo smarty_function_gt(array('text' => 'Extensions'), $this);?>
</span></a>
            </li>
            <li class="page_item">
                <a href="<?php echo smarty_function_modurl(array('modname' => 'Blocks','type' => 'admin','func' => 'main'), $this);?>
"><span><?php echo smarty_function_gt(array('text' => 'Blocks'), $this);?>
</span></a>
            </li>
            <li class="page_item">
                <a href="<?php echo smarty_function_modurl(array('modname' => 'Users','type' => 'admin','func' => 'main'), $this);?>
"><span><?php echo smarty_function_gt(array('text' => 'Users'), $this);?>
</span></a>
            </li>
            <li class="page_item">
                <a href="<?php echo smarty_function_modurl(array('modname' => 'Groups','type' => 'admin','func' => 'main'), $this);?>
"><span><?php echo smarty_function_gt(array('text' => 'Groups'), $this);?>
</span></a>
            </li>
            <li class="page_item">
                <a href="<?php echo smarty_function_modurl(array('modname' => 'Permissions','type' => 'admin','func' => 'main'), $this);?>
"><span><?php echo smarty_function_gt(array('text' => 'Permission rules'), $this);?>
</span></a>
            </li>
            <li class="page_item">
                <a href="<?php echo smarty_function_modurl(array('modname' => 'Theme','type' => 'admin','func' => 'main'), $this);?>
"><span><?php echo smarty_function_gt(array('text' => 'Themes'), $this);?>
</span></a>
            </li>
            <li class="page_item">
                <a href="<?php echo smarty_function_modurl(array('modname' => 'Categories','type' => 'admin','func' => 'main'), $this);?>
"><span><?php echo smarty_function_gt(array('text' => 'Categories'), $this);?>
</span></a>
            </li>
        </ul>
    </div>
</div>
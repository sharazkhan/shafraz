<?php /* Smarty version 2.6.28, created on 2017-09-30 14:18:42
         compiled from body/3col_w_centerblock.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'blockposition', 'body/3col_w_centerblock.tpl', 3, false),)), $this); ?>
<div id="theme_content" class="z-clearfix">
    <div id="theme_leftcol" class="grid_3">
        <?php echo smarty_function_blockposition(array('name' => 'left'), $this);?>

    </div>
    <div id="theme_maincontent" class="grid_9">
        <?php echo smarty_function_blockposition(array('name' => 'center'), $this);?>

        <?php echo $this->_tpl_vars['maincontent']; ?>

    </div>
    <div id="theme_rightcol" class="grid_4">
        <?php echo smarty_function_blockposition(array('name' => 'right'), $this);?>

    </div>
</div>
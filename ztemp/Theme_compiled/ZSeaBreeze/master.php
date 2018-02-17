<?php /* Smarty version 2.6.28, created on 2017-10-01 12:38:27
         compiled from master.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'lang', 'master.tpl', 2, false),array('function', 'langdirection', 'master.tpl', 2, false),array('function', 'blockposition', 'master.tpl', 11, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo smarty_function_lang(array(), $this);?>
" lang="<?php echo smarty_function_lang(array(), $this);?>
" dir="<?php echo smarty_function_langdirection(array(), $this);?>
">
    <head><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "includes/head.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></head>
    <body class="threecols">
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "includes/userheader.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
       
        <div id="pagewidth">
            <div id="wrapper" class="z-clearfix">
                <div id="leftcol">
                    <div id="sidebar">
                        <?php echo smarty_function_blockposition(array('name' => 'left'), $this);?>

                     </div>
                </div>
                <div id="maincol">
                    <?php echo $this->_tpl_vars['maincontent']; ?>

                </div>
                <div id="rightcol"><?php echo smarty_function_blockposition(array('name' => 'right'), $this);?>
</div>
            </div>
        </div>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "includes/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </body>
</html>
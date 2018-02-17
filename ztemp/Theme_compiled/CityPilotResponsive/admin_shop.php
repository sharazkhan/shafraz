<?php /* Smarty version 2.6.28, created on 2017-09-30 14:28:05
         compiled from admin_shop.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'lang', 'admin_shop.tpl', 2, false),array('function', 'langdirection', 'admin_shop.tpl', 2, false),array('function', 'pageaddvar', 'admin_shop.tpl', 7, false),array('function', 'blockposition', 'admin_shop.tpl', 25, false),array('insert', 'getstatusmsg', 'admin_shop.tpl', 21, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo smarty_function_lang(array(), $this);?>
" dir="<?php echo smarty_function_langdirection(array(), $this);?>
">
    <head>
         <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "includes/adminhead.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <!--<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['stylepath']; ?>
/style.css" media="print,projection,screen" />-->
                <?php echo smarty_function_pageaddvar(array('name' => 'stylesheet','value' => "themes/CityPilot/style/admin.css"), $this);?>

        <!--<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['stylepath']; ?>
/print.css" media="print" />-->
        <?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => 'jquery'), $this);?>

        <?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => 'jquery.ui'), $this);?>

        <?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => 'zikula.ui'), $this);?>

        <?php echo smarty_function_pageaddvar(array('name' => 'stylesheet','value' => "themes/CityPilot/style/shop_services.css"), $this);?>

    </head>
    
    <body>
        <div id="theme_page_container">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "includes/adminheader.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <div id="theme_content">
                <div class="inner">
                    <div class="Admin_Left left">
                        <?php require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'getstatusmsg')), $this); ?>

                        <?php echo $this->_tpl_vars['maincontent']; ?>

                    </div>
                    <div class="Admin_Right left">
                        <?php echo smarty_function_blockposition(array('name' => 'backendmsg-right','assign' => 'backendmsgblock'), $this);?>

                        <?php if (! empty ( $this->_tpl_vars['backendmsgblock'] )): ?>
                        <div class="admin_right_top">
                            <?php echo $this->_tpl_vars['backendmsgblock']; ?>

                        </div>
                        <?php endif; ?>

                        <?php echo smarty_function_blockposition(array('name' => 'backendguide-right','assign' => 'backendguideblock'), $this);?>

                        <?php if (! empty ( $this->_tpl_vars['backendguideblock'] )): ?>
                        <div class="admin_right_top">
                            <?php echo $this->_tpl_vars['backendguideblock']; ?>

                        </div>
                        <?php endif; ?>

                        <?php echo smarty_function_blockposition(array('name' => 'backendstats-right','assign' => 'backendstatsblock'), $this);?>

                        <?php if (! empty ( $this->_tpl_vars['backendstatsblock'] )): ?>
                        <div class="admin_right_top">
                            <?php echo $this->_tpl_vars['backendstatsblock']; ?>

                        </div>
                        <?php endif; ?>
						<?php if ($_REQUEST['func'] != 'shopsummary'): ?>
							<?php echo smarty_function_blockposition(array('name' => 'yourservices'), $this);?>
   
						<?php endif; ?>
                    </div>

                </div>
            </div>
                     <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "includes/feedback.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> 
            <div id="CityPilotFotter">
                <?php echo smarty_function_blockposition(array('name' => 'citypilotfooter'), $this);?>
   
            </div>
        </div>
    </body>
</html>
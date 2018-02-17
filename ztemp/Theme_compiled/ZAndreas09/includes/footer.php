<?php /* Smarty version 2.6.28, created on 2017-10-10 21:45:53
         compiled from includes/footer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'gt', 'includes/footer.tpl', 2, false),array('function', 'modurl', 'includes/footer.tpl', 2, false),array('function', 'pagerendertime', 'includes/footer.tpl', 3, false),array('function', 'blockposition', 'includes/footer.tpl', 7, false),array('block', 'nocache', 'includes/footer.tpl', 3, false),)), $this); ?>
<?php $this->_cache_serials['ztemp/Theme_compiled/ZAndreas09/includes/footer.inc'] = 'dbc9c58f1a30bceb9b8a6adc8fe4c54f'; ?>                <div id="theme_footer">
                    <!--p><?php echo smarty_function_gt(array('text' => 'Powered by'), $this);?>
 <a href="http://zikula.org">Zikula</a><?php if ($this->_tpl_vars['modvars']['Theme']['enable_mobile_theme']): ?> | <a href="<?php echo smarty_function_modurl(array('modname' => 'Theme','type' => 'User','func' => 'enableMobileTheme'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Mobile version'), $this);?>
</a><?php endif; ?></p-->
                    <?php if ($this->caching && !$this->_cache_including): echo '{nocache:dbc9c58f1a30bceb9b8a6adc8fe4c54f#0}'; endif;$this->_tag_stack[] = array('nocache', array()); $_block_repeat=true;Zikula_View_Resource::block_nocache($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo smarty_function_pagerendertime(array(), $this);?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo Zikula_View_Resource::block_nocache($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); if ($this->caching && !$this->_cache_including): echo '{/nocache:dbc9c58f1a30bceb9b8a6adc8fe4c54f#0}'; endif;?>
                </div>
            </div>
             <div id="CityPilotFotter">
               <?php echo smarty_function_blockposition(array('name' => 'citypilotfooter'), $this);?>

             </div>
    </body>
</html>
<?php /* Smarty version 2.6.28, created on 2017-10-01 12:38:28
         compiled from includes/footer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'nocache', 'includes/footer.tpl', 6, false),array('function', 'pagerendertime', 'includes/footer.tpl', 6, false),)), $this); ?>
<?php $this->_cache_serials['ztemp/Theme_compiled/ZSeaBreeze/includes/footer.inc'] = 'fb09c5792950be3de3b0ef5e1eddaf89'; ?><div id="footer" class="z-clearer">
    <p>
           </p>
    <?php if ($this->caching && !$this->_cache_including): echo '{nocache:fb09c5792950be3de3b0ef5e1eddaf89#0}'; endif;$this->_tag_stack[] = array('nocache', array()); $_block_repeat=true;Zikula_View_Resource::block_nocache($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo smarty_function_pagerendertime(array(), $this);?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo Zikula_View_Resource::block_nocache($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); if ($this->caching && !$this->_cache_including): echo '{/nocache:fb09c5792950be3de3b0ef5e1eddaf89#0}'; endif;?>
</div>
<?php /* Smarty version 2.6.28, created on 2017-10-29 15:00:52
         compiled from blocks/sociallinks/sociallinks.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'switch', 'blocks/sociallinks/sociallinks.tpl', 4, false),array('block', 'case', 'blocks/sociallinks/sociallinks.tpl', 5, false),)), $this); ?>


<?php $this->assign('limit', 3); ?>
<?php $this->_tag_stack[] = array('switch', array('expr' => $this->_tpl_vars['icon_size'])); $_block_repeat=true;smarty_block_switch($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <?php $this->_tag_stack[] = array('case', array('expr' => 'large')); $_block_repeat=true;smarty_block_case($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <?php $this->assign('limit', '3'); ?>
    <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_case($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
    <?php $this->_tag_stack[] = array('case', array('expr' => 'medium')); $_block_repeat=true;smarty_block_case($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <?php $this->assign('limit', '4'); ?>
    <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_case($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
    <?php $this->_tag_stack[] = array('case', array('expr' => 'small')); $_block_repeat=true;smarty_block_case($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <?php $this->assign('limit', '6'); ?>
    <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_case($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_switch($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $this->assign('counter', 1); ?>
<div class="shop-social col-md-12 col-sm-5 col-md-pull-0 col-sm-pull-6 col-xs-5 col-xs-pull-6">

    <ul>
                <?php $_from = $this->_tpl_vars['social_links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
            <?php if ($this->_tpl_vars['item']['status'] == true): ?>
                <?php if ($this->_tpl_vars['item']['link_url'] != ''): ?>
                    <li>
                        <a href="<?php echo $this->_tpl_vars['item']['link_url']; ?>
" target="_blank">
                            <img src="<?php echo $this->_tpl_vars['themepath']; ?>
/images/social_icons/<?php echo $this->_tpl_vars['icon_size']; ?>
/<?php echo $this->_tpl_vars['item']['socl_image']; ?>
" title="<?php echo $this->_tpl_vars['item']['socl_link_name']; ?>
" alt="<?php echo $this->_tpl_vars['item']['socl_link_name']; ?>
">
                        </a>
                    </li>

                    <?php $this->assign('counter', $this->_tpl_vars['counter']+1); ?>
                    <?php if ($this->_tpl_vars['counter'] > $this->_tpl_vars['limit']): ?>
                        <?php $this->assign('counter', 1); ?>
                        
                    <?php endif; ?>  
                <?php endif; ?>    
            <?php endif; ?>    
        <?php endforeach; endif; unset($_from); ?>
    </ul>
</div>
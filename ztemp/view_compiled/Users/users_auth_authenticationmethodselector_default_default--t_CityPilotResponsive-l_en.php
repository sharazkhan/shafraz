<?php /* Smarty version 2.6.28, created on 2018-02-17 19:13:42
         compiled from users_auth_authenticationmethodselector_default_default.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lower', 'users_auth_authenticationmethodselector_default_default.tpl', 1, false),array('insert', 'csrftoken', 'users_auth_authenticationmethodselector_default_default.tpl', 3, false),array('function', 'gt', 'users_auth_authenticationmethodselector_default_default.tpl', 7, false),)), $this); ?>
<form id="authentication_select_method_form_<?php echo ((is_array($_tmp=$this->_tpl_vars['authentication_method']['modname'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
_<?php echo ((is_array($_tmp=$this->_tpl_vars['authentication_method']['method'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
" class="authentication_select_method" method="post" action="<?php echo $this->_tpl_vars['form_action']; ?>
" enctype="application/x-www-form-urlencoded">
    <div>
        <input type="hidden" id="authentication_select_method_csrftoken_<?php echo ((is_array($_tmp=$this->_tpl_vars['authentication_method']['modname'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
_<?php echo ((is_array($_tmp=$this->_tpl_vars['authentication_method']['method'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
" name="csrftoken" value="<?php require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'csrftoken')), $this); ?>
" />
        <input type="hidden" id="authentication_select_method_selector_<?php echo ((is_array($_tmp=$this->_tpl_vars['authentication_method']['modname'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
_<?php echo ((is_array($_tmp=$this->_tpl_vars['authentication_method']['method'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
" name="authentication_method_selector" value="1" />
        <input type="hidden" id="authentication_select_method_module_<?php echo ((is_array($_tmp=$this->_tpl_vars['authentication_method']['modname'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
_<?php echo ((is_array($_tmp=$this->_tpl_vars['authentication_method']['method'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
" name="authentication_method[modname]" value="<?php echo $this->_tpl_vars['authentication_method']['modname']; ?>
" />
        <input type="hidden" id="authentication_select_method_method_<?php echo ((is_array($_tmp=$this->_tpl_vars['authentication_method']['modname'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
_<?php echo ((is_array($_tmp=$this->_tpl_vars['authentication_method']['method'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
" name="authentication_method[method]" value="<?php echo $this->_tpl_vars['authentication_method']['method']; ?>
" />
        <input type="submit" id="authentication_select_method_submit_<?php echo ((is_array($_tmp=$this->_tpl_vars['authentication_method']['modname'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
_<?php echo ((is_array($_tmp=$this->_tpl_vars['authentication_method']['method'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
" class="authentication_select_method_button<?php if ($this->_tpl_vars['is_selected']): ?> authentication_select_method_selected<?php endif; ?>" name="submit" value="<?php if ($this->_tpl_vars['authentication_method']['method'] == 'email'): ?><?php echo smarty_function_gt(array('text' => 'E-mail address and password'), $this);?>
<?php else: ?><?php echo smarty_function_gt(array('text' => 'User name and password'), $this);?>
<?php endif; ?>" />
    </div>
</form>
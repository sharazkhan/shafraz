<?php /* Smarty version 2.6.28, created on 2017-10-29 14:50:17
         compiled from theme_admin_setasdefault.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'adminheader', 'theme_admin_setasdefault.tpl', 1, false),array('function', 'icon', 'theme_admin_setasdefault.tpl', 3, false),array('function', 'gt', 'theme_admin_setasdefault.tpl', 4, false),array('function', 'modurl', 'theme_admin_setasdefault.tpl', 8, false),array('function', 'button', 'theme_admin_setasdefault.tpl', 22, false),array('function', 'img', 'theme_admin_setasdefault.tpl', 23, false),array('function', 'adminfooter', 'theme_admin_setasdefault.tpl', 28, false),array('modifier', 'safetext', 'theme_admin_setasdefault.tpl', 7, false),array('modifier', 'gt', 'theme_admin_setasdefault.tpl', 22, false),array('insert', 'csrftoken', 'theme_admin_setasdefault.tpl', 10, false),)), $this); ?>
<?php echo smarty_function_adminheader(array(), $this);?>

<div class="z-admin-content-pagetitle">
    <?php echo smarty_function_icon(array('type' => 'edit','size' => 'small'), $this);?>

    <h3><?php echo smarty_function_gt(array('text' => 'Theme confirmation prompt'), $this);?>
</h3>
</div>

<p class="z-warningmsg"><?php echo smarty_function_gt(array('text' => "Do you really want to set '%s' as the active theme for all site users?",'tag1' => ((is_array($_tmp=$this->_tpl_vars['themename'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp))), $this);?>
</p>
<form class="z-form" action="<?php echo smarty_function_modurl(array('modname' => 'Theme','type' => 'admin','func' => 'setasdefault'), $this);?>
" method="post" enctype="application/x-www-form-urlencoded">
    <div>
        <input type="hidden" name="csrftoken" value="<?php require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'csrftoken')), $this); ?>
" />
        <input type="hidden" name="themename" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['themename'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
" />
        <input type="hidden" name="confirmation" value="1" />
        <fieldset>
            <legend><?php echo smarty_function_gt(array('text' => 'Confirmation prompt'), $this);?>
</legend>
            <?php if ($this->_tpl_vars['theme_change']): ?>
            <div class="z-formrow">
                <label for="themeswitcher_theme_change"><?php echo smarty_function_gt(array('text' => "Override users' theme settings"), $this);?>
</label>
                <input id="themeswitcher_theme_change" name="resetuserselected" type="checkbox" value="1"  />
            </div>
            <?php endif; ?>
            <div class="z-buttons z-formbuttons">
                <?php echo smarty_function_button(array('class' => "z-btgreen",'src' => "button_ok.png",'set' => "icons/extrasmall",'alt' => ((is_array($_tmp='Accept')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'title' => ((is_array($_tmp='Accept')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'text' => ((is_array($_tmp='Accept')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view']))), $this);?>

                <a class="z-btred" href="<?php echo smarty_function_modurl(array('modname' => 'Theme','type' => 'admin','func' => 'view'), $this);?>
" title="<?php echo smarty_function_gt(array('text' => 'Cancel'), $this);?>
"><?php echo smarty_function_img(array('modname' => 'core','src' => "button_cancel.png",'set' => "icons/extrasmall",'alt' => ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'title' => ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view']))), $this);?>
 <?php echo smarty_function_gt(array('text' => 'Cancel'), $this);?>
</a>
            </div>
        </fieldset>
    </div>
</form>
<?php echo smarty_function_adminfooter(array(), $this);?>
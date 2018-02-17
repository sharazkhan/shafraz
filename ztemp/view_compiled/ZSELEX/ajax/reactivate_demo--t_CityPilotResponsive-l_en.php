<?php /* Smarty version 2.6.28, created on 2017-12-10 15:13:51
         compiled from ajax/reactivate_demo.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'gt', 'ajax/reactivate_demo.tpl', 3, false),array('function', 'modurl', 'ajax/reactivate_demo.tpl', 6, false),array('function', 'img', 'ajax/reactivate_demo.tpl', 29, false),array('modifier', 'gt', 'ajax/reactivate_demo.tpl', 29, false),)), $this); ?>

    <div class="z-admin-content-pagetitle">
           <h3><?php echo smarty_function_gt(array('text' => 'Reactivate Demo'), $this);?>
</h3>
     </div>
    
    <form class="z-form" action="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'shop','func' => 'reactivateDemo'), $this);?>
" method="post" enctype="application/x-www-form-urlencoded">
        <div>
          
            <fieldset>
                                    
                <div class="z-formrow">
                     <label for="typestatus" style="float:left"><?php echo smarty_function_gt(array('text' => 'Demo Period'), $this);?>
</label>
                    
                     <input type="text" name="demo_period" value="">
                     <input type="hidden" name="shop_ids" value="<?php echo $this->_tpl_vars['shopIds']; ?>
">
                
                </div>
          
             
                    
            <div class="z-buttons z-formbuttons">
             <button id="zselex_button_submit"  class="z-btgreen" type="submit" onclick="return validate_category();" name="action" value="1" title="<?php echo smarty_function_gt(array('text' => 'Submit'), $this);?>
">
             <?php echo smarty_function_img(array('src' => 'button_ok.png','modname' => 'core','set' => 'icons/extrasmall','alt' => ((is_array($_tmp='Submit')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'title' => ((is_array($_tmp='Submit')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view']))), $this);?>

             <?php echo smarty_function_gt(array('text' => 'Submit'), $this);?>

            </button>
            <a id="zselex_button_cancel" href="javascript:closeWindow()" shop_id=$smarty.request.shop_id}" class="z-btred"><?php echo smarty_function_img(array('modname' => 'core','src' => 'button_cancel.png','set' => 'icons/extrasmall','alt' => ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'title' => ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view']))), $this);?>
 <?php echo smarty_function_gt(array('text' => 'Cancel'), $this);?>
</a>
            </div>
            </fieldset>
        </div>
	</form>
    <div>

</div>




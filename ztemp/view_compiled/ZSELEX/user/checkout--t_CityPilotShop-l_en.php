<?php /* Smarty version 2.6.28, created on 2017-10-29 15:16:10
         compiled from user/checkout.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'pageaddvar', 'user/checkout.tpl', 2, false),array('function', 'gt', 'user/checkout.tpl', 10, false),array('function', 'modurl', 'user/checkout.tpl', 14, false),array('modifier', 'cleantext', 'user/checkout.tpl', 20, false),)), $this); ?>

<?php echo smarty_function_pageaddvar(array('name' => 'stylesheet','value' => "modules/ZSELEX/javascript/validation/style.css".($this->_tpl_vars['ver'])), $this);?>
 
<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => 'http://ajax.googleapis.com/ajax/libs/prototype/1.6.0.3/prototype.js'), $this);?>

<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => 'http://ajax.googleapis.com/ajax/libs/scriptaculous/1.8.2/effects.js'), $this);?>

<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => "modules/ZSELEX/javascript/validation/fabtabulous.js".($this->_tpl_vars['ver'])), $this);?>

<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => "modules/ZSELEX/javascript/validation/validation.js".($this->_tpl_vars['ver'])), $this);?>

<div class="row">
    <div class="col-md-12">
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "user/cartmenu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <h2><?php echo smarty_function_gt(array('text' => 'Enter Customer Information'), $this);?>
</h2>

        <!-- Checkout -->
        <div class="checkout-wrap clearfix">
            <form id="delivery" name="delivery" method="post" action="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'delivery','shop_id' => $_REQUEST['shop_id']), $this);?>
" class="form-horizontal clearfix">
                  <div class="col-sm-6">

                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label"><?php echo smarty_function_gt(array('text' => 'First Name'), $this);?>
: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control required" title="<?php echo smarty_function_gt(array('text' => 'Please enter your first name'), $this);?>
" name="fname" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['info']['first_name'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
" id="" placeholder="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label"><?php echo smarty_function_gt(array('text' => 'Last Name'), $this);?>
: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control required" title="<?php echo smarty_function_gt(array('text' => 'Please enter your last name'), $this);?>
" name="lname" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['info']['last_name'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
" id="" placeholder="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label"><?php echo smarty_function_gt(array('text' => 'Address'), $this);?>
: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control required" title="<?php echo smarty_function_gt(array('text' => 'Please enter your address'), $this);?>
" name="address" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['info']['address'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
" id="" placeholder="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-4 col-xs-12 control-label"><?php echo smarty_function_gt(array('text' => 'ZIP code. city'), $this);?>
: </label>
                        <div class="col-sm-3 col-xs-4">
                            <input type="text" title="<?php echo smarty_function_gt(array('text' => 'Please enter your zip code'), $this);?>
" class="form-control required" name="zip" value="<?php echo $this->_tpl_vars['info']['zip']; ?>
" id="" placeholder="">
                        </div>
                        <div class="col-sm-5 col-xs-8">
                            <input type="text" class="form-control required"  title="<?php echo smarty_function_gt(array('text' => 'Please enter your city'), $this);?>
" name="city" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['info']['city'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
" id="" placeholder="">
                        </div>
                    </div>

                </div>
                <div class="col-sm-6">

                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label"><?php echo smarty_function_gt(array('text' => 'Phone'), $this);?>
: </label>
                        <div class="col-sm-8">
                            <input type="tel" title="<?php echo smarty_function_gt(array('text' => 'Please enter a valid phone number'), $this);?>
" class="form-control required" name="phone" value="<?php echo $this->_tpl_vars['info']['mobile']; ?>
" id="" placeholder="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label"><?php echo smarty_function_gt(array('text' => 'Email address'), $this);?>
: </label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control required validate-email" title="<?php echo smarty_function_gt(array('text' => 'Please enter a valid email'), $this);?>
" name="email" value="<?php echo $this->_tpl_vars['info']['email']; ?>
" id="" placeholder="">
                        </div>
                    </div>
                    <div class="form-group checkbox-wrap">
                        <label for="" class="col-sm-4 control-label"><?php echo smarty_function_gt(array('text' => 'Subscribe To Newsletter'), $this);?>
: </label>
                        <div class="col-sm-8">
                            <div class="checkbox-click">
                                <input type="checkbox" id="check2" class="btn" name="subscribe" value="1" checked="checked">
                                <label for="check2"></label>
                            </div>
                        </div>
                    </div>
                                        <input type="hidden" name="country" value="<?php echo $this->_tpl_vars['info']['country']; ?>
"/>
                    <input type="hidden" name="state" value="<?php echo $this->_tpl_vars['info']['state']; ?>
"/>

                </div>
            </form>
        </div>
        <div class="checkout-btn-wrap clearfix">
            <a href="#" class="btn btn-warning cs-btn btn-info"><i class="fa fa-angle-left"></i> <?php echo smarty_function_gt(array('text' => 'Continue Shopping'), $this);?>
</a>
            <a href="#" onClick="return submitCheckout()" class="btn btn-primary checkout-btn"><?php echo smarty_function_gt(array('text' => 'Go to delivery'), $this);?>
 <i class="fa fa-arrow-right"></i></a>
        </div>
        <!-- Checkout -->
    </div>
</div>

<script type="text/javascript"><?php echo '
    // var valid2 = new Validation(\'delivery\', {useTitles:true});
    var valid2 = new Validation(\'delivery\', {useTitles: true, onSubmit: false});


    function submitCheckout() {
        //alert(\'submitCheckout\'); exit();
       var result = valid2.validate();
      // var result = true;
        if (result) {
            jQuery(\'#delivery\').submit();
            return false;
        }

    }
'; ?>
</script>
<?php /* Smarty version 2.6.28, created on 2018-02-17 19:33:09
         compiled from blocks/specialdeals/specialdeals.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'gt', 'blocks/specialdeals/specialdeals.tpl', 17, false),)), $this); ?>

<input type='hidden' id='sd_adtype' value='<?php echo $this->_tpl_vars['vars']['sd_adtype']; ?>
'>
<input type='hidden' id='sd_amount' value='<?php echo $this->_tpl_vars['vars']['sd_amount']; ?>
'>

<input type='hidden' id='sd_articlead_val' value=''>
<input type='hidden' id='sd_productad_val' value=''>

<input type='hidden' id='highad_amount' value='<?php echo $this->_tpl_vars['vars']['highad_amount']; ?>
'>
<input type='hidden' id='midad_amount' value='<?php echo $this->_tpl_vars['vars']['midad_amount']; ?>
'>
<input type='hidden' id='lowad_amount' value='<?php echo $this->_tpl_vars['vars']['lowad_amount']; ?>
'>
<input type='hidden' id='event_amount' value='<?php echo $this->_tpl_vars['vars']['event_amount']; ?>
'>
<input type='hidden' id='article_amount' value='<?php echo $this->_tpl_vars['vars']['article_amount']; ?>
'>



<div class="col-md-8 contents-left">
    <h2><?php echo smarty_function_gt(array('text' => 'Special Deals'), $this);?>
</h2>
    <div id="specialdeal_block">
    <div class="highad-products" id="specialdeal_block_products">
         <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "ajax/productadhigh.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> 
    </div> <!-- high ad ends here ->

    <!-- 4 thumb row -->
    <div class="thumb-row">
        <div class="thumbslider clearfix">
            <div class="midad-products" id="specialdeal_block_products_mid"> <!-- mid ad starts -->
                 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "ajax/productadmid.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            </div> <!-- mid ad products ends -->

        </div>
    </div>
    <div class="thumb-row">
        <div class="thumbslider clearfix">
            <div class="midad-products" id="specialdeal_block_Events"> <!-- mid ad starts -->
                 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "ajax/specialdealevents.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            </div> <!-- mid ad products ends -->

        </div>
    </div>
    <div class="thumb-row">
        <div class="thumbslider clearfix">
            <div class="midad-products" id="specialdeal_block_products_low"> <!-- mid ad starts -->
                 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "ajax/productadlow.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            </div> <!-- mid ad products ends -->

        </div>
    </div>
</div>          
    <!-- 4 thumb row end -->
</div> 
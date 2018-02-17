<?php /* Smarty version 2.6.28, created on 2017-10-10 23:46:52
         compiled from blocks/employees/employees.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'modurl', 'blocks/employees/employees.tpl', 2, false),array('function', 'gt', 'blocks/employees/employees.tpl', 3, false),)), $this); ?>
 <?php if ($this->_tpl_vars['perm']): ?>
<a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'admin','func' => 'shopsettings','shop_id' => $_REQUEST['shop_id']), $this);?>
#aEmployees" class="edit-link"><i class="fa fa-pencil-square" aria-hidden="true"></i>
    <?php echo smarty_function_gt(array('text' => 'Edit Employees'), $this);?>

</a>
<?php endif; ?>
<div class="products-wrap clearfix col-sm-12 employees-list">
    <div class="product-head clearfix">
        <h3 class="pull-left"><?php echo smarty_function_gt(array('text' => 'Employees'), $this);?>
</h3>
    </div>
    <div class="row">
        <?php $_from = $this->_tpl_vars['employees']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['index'] => $this->_tpl_vars['item']):
?>
        <?php $_from = $this->_tpl_vars['item']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['employee']):
?>
        <div class="col-sm-6 hover-border">
            <div class="thumbnail clearfix">
                <div class="pro-image">
                    <img src="<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $_REQUEST['shop_id']; ?>
/employees/thumb/<?php echo $this->_tpl_vars['employee']['emp_image']; ?>
" class="img-responsive" alt="">
                </div>
                <div class="btm-product-name">
                    <h4><?php echo $this->_tpl_vars['employee']['name']; ?>
</h4>
                    <p><?php echo $this->_tpl_vars['employee']['email']; ?>
</p>
                    <?php if ($this->_tpl_vars['employee']['phone']): ?><p><?php echo $this->_tpl_vars['employee']['phone']; ?>
<?php endif; ?>
                        <?php if ($this->_tpl_vars['employee']['phone'] && $this->_tpl_vars['employee']['cell']): ?>,&nbsp;<?php else: ?><?php if ($this->_tpl_vars['employee']['phone']): ?></p><?php endif; ?><?php if ($this->_tpl_vars['employee']['cell']): ?><p><?php endif; ?><?php endif; ?>
                        <?php if ($this->_tpl_vars['employee']['cell']): ?><?php echo $this->_tpl_vars['employee']['cell']; ?>
</p><?php endif; ?>
                    <p><?php echo $this->_tpl_vars['employee']['job']; ?>
</p>
                </div>
            </div>
        </div>
        <?php endforeach; endif; unset($_from); ?>
        <?php endforeach; endif; unset($_from); ?>


    </div>
</div>
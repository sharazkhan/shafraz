<?php /* Smarty version 2.6.28, created on 2017-10-29 15:00:52
         compiled from blocks/shopaddress/shopaddress.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'modurl', 'blocks/shopaddress/shopaddress.tpl', 3, false),array('function', 'gt', 'blocks/shopaddress/shopaddress.tpl', 3, false),array('modifier', 'unserialize', 'blocks/shopaddress/shopaddress.tpl', 6, false),array('modifier', 'nl2br', 'blocks/shopaddress/shopaddress.tpl', 132, false),array('modifier', 'wordwrap', 'blocks/shopaddress/shopaddress.tpl', 156, false),)), $this); ?>

<?php if ($this->_tpl_vars['perm']): ?>
<a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'admin','func' => 'shopsettings','shop_id' => $_REQUEST['shop_id']), $this);?>
#aAddress" class="edit-link"><i class="fa fa-pencil-square" aria-hidden="true"></i><?php echo smarty_function_gt(array('text' => 'Edit Content'), $this);?>
</a>
<?php endif; ?>

<?php $this->assign('time', ((is_array($_tmp=$this->_tpl_vars['shopinfo']['opening_hours'])) ? $this->_run_mod_handler('unserialize', true, $_tmp) : unserialize($_tmp))); ?>
<div class="shop-address col-md-12 col-sm-6 col-md-push-0 col-sm-push-6 col-xs-6 col-xs-push-6">
    <?php if ($this->_tpl_vars['time']['mon']['open'] != '' || $this->_tpl_vars['time']['tue']['open'] != '' || $this->_tpl_vars['time']['wed']['open'] != '' || $this->_tpl_vars['time']['thu']['open'] != '' || $this->_tpl_vars['time']['fri']['open'] != '' || $this->_tpl_vars['time']['sat']['open'] != '' || $this->_tpl_vars['time']['sun']['open'] != '' || $this->_tpl_vars['time']['comment'] != ''): ?>
    <p>
        <b><?php echo smarty_function_gt(array('text' => 'Opening Hours'), $this);?>
</b>
    </p>
    <table width="100%" class="opening-time">
        <thead>
            <tr>
                <th width="20%"></th>
                <th width="10%"></th>
                <th width="5%"></th>
                <th width="60%"></th>
            </tr>
        </thead>
        <tbody>
            <?php if (! $this->_tpl_vars['time']['mon']['closed']): ?>
            <?php if ($this->_tpl_vars['time']['mon']['open'] != '' || $this->_tpl_vars['time']['mon']['close'] != ''): ?>
            <tr>
                <td><?php echo smarty_function_gt(array('text' => 'Monday'), $this);?>
</td>
                <?php if ($this->_tpl_vars['time']['mon']['closed']): ?>
                <td><?php echo smarty_function_gt(array('text' => 'Closed'), $this);?>
</td>
                <?php else: ?>  
                <td><?php echo $this->_tpl_vars['time']['mon']['open']; ?>
</td>
                <td>-</td>
                <td><?php echo $this->_tpl_vars['time']['mon']['close']; ?>
</td>
                <td width="50%"></td>
                <?php endif; ?>
            </tr>
            <?php endif; ?>
            <?php endif; ?>

            <?php if (! $this->_tpl_vars['time']['tue']['closed']): ?>
            <?php if ($this->_tpl_vars['time']['tue']['open'] != '' || $this->_tpl_vars['time']['tue']['close'] != ''): ?>
            <tr>
                <td><?php echo smarty_function_gt(array('text' => 'Tuesday'), $this);?>
</td>
                <?php if ($this->_tpl_vars['time']['tue']['closed']): ?>
                <td><?php echo smarty_function_gt(array('text' => 'Closed'), $this);?>
</td>
                <?php else: ?> 
                <td><?php echo $this->_tpl_vars['time']['tue']['open']; ?>
</td>
                <td>-</td>
                <td><?php echo $this->_tpl_vars['time']['tue']['close']; ?>
</td>
                <td width="50%"></td>
                <?php endif; ?>
            </tr>
            <?php endif; ?>
            <?php endif; ?>
            <?php if (! $this->_tpl_vars['time']['wed']['closed']): ?>
            <?php if ($this->_tpl_vars['time']['wed']['open'] != '' || $this->_tpl_vars['time']['wed']['close'] != ''): ?>
            <tr>
                <td><?php echo smarty_function_gt(array('text' => 'Wednesday'), $this);?>
</td>
                <?php if ($this->_tpl_vars['time']['wed']['closed']): ?>
                <td><?php echo smarty_function_gt(array('text' => 'Closed'), $this);?>
</td>
                <?php else: ?> 
                <td><?php echo $this->_tpl_vars['time']['wed']['open']; ?>
</td>
                <td>-</td>
                <td><?php echo $this->_tpl_vars['time']['wed']['close']; ?>
</td>
                <td width="50%"></td>
                <?php endif; ?>
            </tr>
            <?php endif; ?>
            <?php endif; ?>
            <?php if (! $this->_tpl_vars['time']['thu']['closed']): ?>
            <?php if ($this->_tpl_vars['time']['thu']['open'] != '' || $this->_tpl_vars['time']['thu']['close'] != ''): ?>
            <tr>
                <td><?php echo smarty_function_gt(array('text' => 'Thursday'), $this);?>
</td>
                <?php if ($this->_tpl_vars['time']['thu']['closed']): ?>
                <td><?php echo smarty_function_gt(array('text' => 'Closed'), $this);?>
</td>
                <?php else: ?> 
                <td><?php echo $this->_tpl_vars['time']['thu']['open']; ?>
</td>
                <td>-</td>
                <td><?php echo $this->_tpl_vars['time']['thu']['close']; ?>
</td>
                <td width="50%"></td>
                <?php endif; ?>
            </tr>
            <?php endif; ?>
            <?php endif; ?>
            <?php if (! $this->_tpl_vars['time']['fri']['closed']): ?>
            <?php if ($this->_tpl_vars['time']['fri']['open'] != '' || $this->_tpl_vars['time']['fri']['close'] != ''): ?>
            <tr>
                <td><?php echo smarty_function_gt(array('text' => 'Friday'), $this);?>
</td>
                <?php if ($this->_tpl_vars['time']['fri']['closed']): ?>
                <td><?php echo smarty_function_gt(array('text' => 'Closed'), $this);?>
</td>
                <?php else: ?> 
                <td><?php echo $this->_tpl_vars['time']['fri']['open']; ?>
</td>
                <td>-</td>
                <td><?php echo $this->_tpl_vars['time']['fri']['close']; ?>
</td>
                <td width="50%"></td>
                <?php endif; ?>
            </tr>
            <?php endif; ?>
            <?php endif; ?>
            <?php if (! $this->_tpl_vars['time']['sat']['closed']): ?>
            <?php if ($this->_tpl_vars['time']['sat']['open'] != '' || $this->_tpl_vars['time']['sat']['close'] != ''): ?>
            <tr>
                <td><?php echo smarty_function_gt(array('text' => 'Saturday'), $this);?>
</td>
                <?php if ($this->_tpl_vars['time']['sat']['closed']): ?>
                <td><?php echo smarty_function_gt(array('text' => 'Closed'), $this);?>
</td>
                <?php else: ?>   
                <td><?php echo $this->_tpl_vars['time']['sat']['open']; ?>
</td>
                <td>-</td>
                <td><?php echo $this->_tpl_vars['time']['sat']['close']; ?>
</td>
                <td width="50%"></td>
                <?php endif; ?>
            </tr>
            <?php endif; ?>
            <?php endif; ?>
            <?php if (! $this->_tpl_vars['time']['sun']['closed']): ?>
            <?php if ($this->_tpl_vars['time']['sun']['open'] != '' || $this->_tpl_vars['time']['sun']['close'] != ''): ?>
            <tr>
                <td><?php echo smarty_function_gt(array('text' => 'Sunday'), $this);?>
</td>
                <?php if ($this->_tpl_vars['time']['sun']['closed']): ?>
                <td><?php echo smarty_function_gt(array('text' => 'Closed'), $this);?>
</td>
                <?php else: ?>   
                <td><?php echo $this->_tpl_vars['time']['sun']['open']; ?>
</td>
                <td>-</td>
                <td><?php echo $this->_tpl_vars['time']['sun']['close']; ?>
</td>
                <td width="50%"></td>
                <?php endif; ?>
            </tr>
            <?php endif; ?>
            <?php endif; ?>
            <?php if ($this->_tpl_vars['time']['comment'] != ''): ?>
                <tr>
                   <td colspan="5">
                                              <br><?php echo ((is_array($_tmp=$this->_tpl_vars['time']['comment'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

                   </td>
               </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?php endif; ?>
    <p>
        <b><?php echo smarty_function_gt(array('text' => 'Contact Us'), $this);?>
</b>
    </p>
    <p>
        <?php if ($this->_tpl_vars['shopinfo']['address'] != ''): ?>
    <div><?php echo ((is_array($_tmp=$this->_tpl_vars['shopinfo']['address'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</div>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['shopinfo']['email'] != ''): ?>
    <div><?php echo smarty_function_gt(array('text' => 'Email'), $this);?>
 : <?php echo $this->_tpl_vars['shopinfo']['email']; ?>
</div>
    <?php endif; ?>
    <?php if (! empty ( $this->_tpl_vars['shopinfo']['fax'] )): ?>
    <div><?php echo smarty_function_gt(array('text' => 'Fax'), $this);?>
: <?php echo $this->_tpl_vars['shopinfo']['fax']; ?>
</div>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['shopinfo']['telephone'] != ''): ?>
    <div><?php echo smarty_function_gt(array('text' => 'Tel'), $this);?>
 : <?php echo $this->_tpl_vars['shopinfo']['telephone']; ?>
</div>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['shopinfo']['vat_number'] != ''): ?>
    <div><?php echo smarty_function_gt(array('text' => 'VAT#'), $this);?>
 : <?php echo ((is_array($_tmp=$this->_tpl_vars['shopinfo']['vat_number'])) ? $this->_run_mod_handler('wordwrap', true, $_tmp, 30, "<br/>", true) : smarty_modifier_wordwrap($_tmp, 30, "<br/>", true)); ?>
</div>
    <?php endif; ?>
</p>
</div>
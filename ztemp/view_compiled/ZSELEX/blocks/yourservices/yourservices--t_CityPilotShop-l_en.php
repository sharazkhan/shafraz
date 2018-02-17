<?php /* Smarty version 2.6.28, created on 2017-10-29 15:28:35
         compiled from blocks/yourservices/yourservices.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'gt', 'blocks/yourservices/yourservices.tpl', 2, false),)), $this); ?>
 <div class="admin_right_top">
        <h4 class="dine"> <?php echo smarty_function_gt(array('text' => 'Your Services'), $this);?>
 </h4>
        <table class="DineTable">
            <tr>
                <th><?php echo smarty_function_gt(array('text' => 'Service'), $this);?>
:</th><th><?php echo smarty_function_gt(array('text' => 'Expires'), $this);?>
:</th>
            </tr>
             <?php $_from = $this->_tpl_vars['services']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['index'] => $this->_tpl_vars['service']):
?>
                 <?php if ($this->_tpl_vars['service']['remind'] == 1): ?>
                    <tr class="Orange">
                      <td>
                          <?php echo $this->_tpl_vars['service']['service_name']; ?>
 <?php if ($this->_tpl_vars['service']['qty_based']): ?>(<?php echo $this->_tpl_vars['service']['quantity']; ?>
)<?php endif; ?>
                      </td>
                      <td>
                          <span class="Exclaim"></span><?php echo $this->_tpl_vars['service']['DIFF']; ?>

                      </td>
                    </tr>
                    <?php else: ?>
                     <tr>
                        <td>
                            <?php echo $this->_tpl_vars['service']['service_name']; ?>
 <?php if ($this->_tpl_vars['service']['qty_based']): ?>(<?php echo $this->_tpl_vars['service']['quantity']; ?>
)<?php endif; ?>
                        </td>
                        <td><?php echo $this->_tpl_vars['service']['DIFF']; ?>

                        </td>
                    </tr> 
                  <?php endif; ?>   
             <?php endforeach; else: ?>
                 
                   <tr>
                       <td colspan="2" align="left"><?php echo smarty_function_gt(array('text' => 'No Services'), $this);?>
</td>
                    </tr> 
                 
              <?php endif; unset($_from); ?>   
     </table>
    </div>
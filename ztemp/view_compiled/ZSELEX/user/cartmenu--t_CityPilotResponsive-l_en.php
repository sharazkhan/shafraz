<?php /* Smarty version 2.6.28, created on 2018-02-03 08:17:21
         compiled from user/cartmenu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'modurl', 'user/cartmenu.tpl', 2, false),array('function', 'gt', 'user/cartmenu.tpl', 2, false),)), $this); ?>
<div class="wizard">
            <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'cart','shop_id' => $_REQUEST['shop_id']), $this);?>
" <?php if ($_REQUEST['func'] == 'cart'): ?> class="current" <?php endif; ?>><span class="badge">1</span> <?php echo smarty_function_gt(array('text' => 'Cart'), $this);?>
</a>
           <a href="<?php if (in_array ( 'checkout' , $_SESSION['cart_menu'] )): ?><?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'checkout','shop_id' => $_REQUEST['shop_id']), $this);?>
<?php else: ?>#<?php endif; ?>" <?php if ($_REQUEST['func'] == 'checkout'): ?>class="current"<?php endif; ?>>
                <span class="badge">2</span> <?php echo smarty_function_gt(array('text' => 'Customer Information'), $this);?>

            </a>
            <a href="<?php if (in_array ( 'delivery' , $_SESSION['cart_menu'] )): ?><?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'delivery','shop_id' => $_REQUEST['shop_id']), $this);?>
<?php else: ?>#<?php endif; ?>" <?php if ($_REQUEST['func'] == 'delivery'): ?>class="current"<?php endif; ?>>
                <span class="badge">3</span> <?php echo smarty_function_gt(array('text' => 'Delivery'), $this);?>

            </a>
           <a href="<?php if (in_array ( 'paymentoptions' , $_SESSION['cart_menu'] )): ?><?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'paymentoptions','shop_id' => $_REQUEST['shop_id']), $this);?>
<?php else: ?>#<?php endif; ?>"   <?php if ($_REQUEST['func'] == 'paymentoptions'): ?>class="current"<?php endif; ?>>
                <span class="badge">4</span> <?php echo smarty_function_gt(array('text' => 'Payment'), $this);?>

            </a>
             <a href="#" <?php if ($_REQUEST['func'] == 'order' || $_REQUEST['func'] == 'payPalReturn' || $_REQUEST['func'] == 'orderConfirmation' || $_REQUEST['func'] == 'paymentStatus'): ?>class="current"<?php endif; ?>>
                <span class="badge">5</span> <?php echo smarty_function_gt(array('text' => 'Order Confirmation'), $this);?>

            </a>
        </div>
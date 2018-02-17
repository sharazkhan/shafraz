<?php /* Smarty version 2.6.28, created on 2017-09-30 14:23:38
         compiled from includes/adminheader.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'shiftshop', 'includes/adminheader.tpl', 8, false),array('function', 'blockposition', 'includes/adminheader.tpl', 19, false),array('function', 'modurl', 'includes/adminheader.tpl', 54, false),array('function', 'gt', 'includes/adminheader.tpl', 54, false),array('function', 'servicecartcount', 'includes/adminheader.tpl', 54, false),)), $this); ?>



<div class="AdminHead">
              <div id="top_headmenu">
                    <div class="inner">	
                        <?php echo smarty_function_shiftshop(array('shop_id' => $_REQUEST['shop_id']), $this);?>

                           
                        <div class="right">
                                                       
                             <?php echo smarty_function_blockposition(array('name' => 'verytop-right'), $this);?>

                            
                        </div>
                    </div>	
                </div>
                <div class="top_Logo_Container">
                    <div class="inner">
                        <div class="Logo_Section">
                            <div class="Logo_Admin"><img src="<?php echo $this->_tpl_vars['imagepath']; ?>
/Logo_Admin.png" /></div>
                            <div id="Logo_Section_menu">
                                                                <?php if ($_REQUEST['shop_id'] != ''): ?>
                                                                  <?php echo smarty_function_blockposition(array('name' => 'backendmenu'), $this);?>

                                 <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                            
            <div class="ServiceMenuSection">
                <div class="inner">
                                        <ul>
                    <li><a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'admin','func' => 'serviceCart'), $this);?>
"><img src="<?php echo $this->_tpl_vars['themepath']; ?>
/images/GreyBasket.png" /><span class="Grey"><?php echo smarty_function_gt(array('text' => 'Cart'), $this);?>
(<?php echo smarty_function_servicecartcount(array(), $this);?>
)</span></a></li>
                    </ul>
                                          <?php if ($_REQUEST['func'] == 'shopsummary'): ?>
                     <?php echo smarty_function_blockposition(array('name' => 'shopsummary-menu'), $this);?>

                     <?php elseif ($_REQUEST['func'] == 'shopservices'): ?>
                     <?php echo smarty_function_blockposition(array('name' => 'shopservices-menu'), $this);?>
    
                     <?php endif; ?>
                     
                </div>
            </div> 

	                        
                
<?php /* Smarty version 2.6.28, created on 2018-02-17 19:13:55
         compiled from blocks/citypilottheme/footer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'fileversion', 'blocks/citypilottheme/footer.tpl', 1, false),array('function', 'pageaddvar', 'blocks/citypilottheme/footer.tpl', 2, false),array('function', 'blockposition', 'blocks/citypilottheme/footer.tpl', 10, false),array('function', 'cardsaccepted', 'blocks/citypilottheme/footer.tpl', 32, false),array('function', 'gt', 'blocks/citypilottheme/footer.tpl', 46, false),array('function', 'modurl', 'blocks/citypilottheme/footer.tpl', 49, false),array('modifier', 'unserialize', 'blocks/citypilottheme/footer.tpl', 45, false),)), $this); ?>
 <?php echo smarty_function_fileversion(array(), $this);?>

<?php echo smarty_function_pageaddvar(array('name' => 'stylesheet','value' => "themes/".($this->_tpl_vars['current_theme'])."/style/Footer.css".($this->_tpl_vars['ver'])), $this);?>

<div class="FooterBlock">
    <div class="inner">
        <div class="FotterTop">
            <div class="FotterLogo left">
                                <?php echo smarty_function_blockposition(array('name' => 'bot1-left'), $this);?>

               
                
            </div>
            <div class="FotterLogo left" style="display: block; width:220px;text-align: left;color:#d2d2d2">
                     <?php echo smarty_function_blockposition(array('name' => 'bot1-center'), $this);?>

                    
                    
            </div>
               
           
            
            <div id="creditcard" class="FotterPayment right" style="display: block; width:470px;text-align: left">
                                              <?php echo smarty_function_cardsaccepted(array('shop_id' => $_REQUEST['shop_id'],'footer' => 1), $this);?>

            </div>
           
        </div>
        <div class="FotterBot">
            <div class="FooterSubBlock1 left">
              
                              <?php $this->assign('termsConditionInfo', ((is_array($_tmp=$this->_tpl_vars['modvars']['ZSELEX']['termsConditionInfo'])) ? $this->_run_mod_handler('unserialize', true, $_tmp) : unserialize($_tmp))); ?>
                 <h4><?php echo smarty_function_gt(array('text' => 'RETURN AND PAYMENT'), $this);?>
</h4>
                <ul>
                    <li>
                        <a  id="footer_windowajax1" href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'info','func' => 'footerLink','key' => 'rma','shop_id' => $_REQUEST['shop_id'],'shop_name' => $_REQUEST['shop_name']), $this);?>
"  title="<?php echo smarty_function_gt(array('text' => 'RMA'), $this);?>
 ">
                       <?php echo smarty_function_gt(array('text' => 'RMA'), $this);?>
 
                        </a>
                    </li>
                    <li>
                         <a  id="footer_windowajax2" href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'info','func' => 'footerLink','key' => 'deliveryprices','shop_id' => $_REQUEST['shop_id'],'shop_name' => $_REQUEST['shop_name']), $this);?>
"  title="<?php echo smarty_function_gt(array('text' => 'Delivery prices'), $this);?>
 ">
                         <?php echo smarty_function_gt(array('text' => 'Delivery prices'), $this);?>
 
                         </a>
                    </li>
                    <li>
                         <a  id="footer_windowajax3" href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'info','func' => 'footerLink','key' => 'deliverytime','shop_id' => $_REQUEST['shop_id'],'shop_name' => $_REQUEST['shop_name']), $this);?>
"  title="<?php echo smarty_function_gt(array('text' => 'Delivery time'), $this);?>
 ">
                         <?php echo smarty_function_gt(array('text' => 'Delivery time'), $this);?>
 
                         </a>
                    </li>
                   
                </ul>
                
                           </div>                    
            <div class="FooterSubBlock1 left"> 
                                
                 <h4><?php echo smarty_function_gt(array('text' => 'TERMS AND CONDITIONS'), $this);?>
</h4>  
                <ul>
                    <li>
                        <a id="footer_windowajax4" href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'info','func' => 'footerLink','key' => 'termsoftrade','shop_id' => $_REQUEST['shop_id'],'shop_name' => $_REQUEST['shop_name']), $this);?>
"  title="<?php echo smarty_function_gt(array('text' => 'Terms of trade'), $this);?>
">
                         <?php echo smarty_function_gt(array('text' => 'Terms of trade'), $this);?>
 
                        </a>
                    </li>
                    <li>
                         <a id="footer_windowajax5" href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'info','func' => 'footerLink','key' => 'privacy','shop_id' => $_REQUEST['shop_id'],'shop_name' => $_REQUEST['shop_name']), $this);?>
"  title="<?php echo smarty_function_gt(array('text' => 'Privacy'), $this);?>
">
                        <?php echo smarty_function_gt(array('text' => 'Privacy'), $this);?>

                         </a>
                    </li>
                    <li>
                         <a id="footer_windowajax6" href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'info','func' => 'footerLink','key' => 'securepayment','shop_id' => $_REQUEST['shop_id'],'shop_name' => $_REQUEST['shop_name']), $this);?>
"  title="<?php echo smarty_function_gt(array('text' => 'Secure payment'), $this);?>
">
                         <?php echo smarty_function_gt(array('text' => 'Secure payment'), $this);?>

                         </a>
                    </li>
                </ul>
                          </div>
            <div class="FooterSubBlock1 left">
                               <?php echo smarty_function_blockposition(array('name' => 'bot2-03'), $this);?>

            </div>

            <div class="FooterSubBlock2 left">
                <div class="FotterRightSec followus">
                                        <?php echo smarty_function_blockposition(array('name' => 'bot2-04'), $this);?>

                </div>
            </div>
        </div>
    </div>
                
                <!--<div align='center'>
                     <a href='http://www.hit-counts.com'><img src='http://www.hit-counts.com/counter.php?t=MTI2MDgzMQ==' border='0' alt='Web Counter'></a>
                 </div>-->
</div> 
     <!-- Hide the h4 for footer block by js -->
      <script type="text/javascript" src="modules/ZSELEX/javascript/bigtext/bigtext.js"><?php echo ''; ?>
</script>
   <script><?php echo ' 
    //jQuery("#CityPilotFotter").children(\'div:first\').children(\'h4\').stop(true, true).css("display", "none");
    var defwindowajax = new Zikula.UI.Window($(\'footer_windowajax1\'),{resizable: true});
    var defwindowajax = new Zikula.UI.Window($(\'footer_windowajax2\'),{resizable: true});
    var defwindowajax = new Zikula.UI.Window($(\'footer_windowajax3\'),{resizable: true});
    var defwindowajax = new Zikula.UI.Window($(\'footer_windowajax4\'),{resizable: true});
    var defwindowajax = new Zikula.UI.Window($(\'footer_windowajax5\'),{resizable: true});
    var defwindowajax = new Zikula.UI.Window($(\'footer_windowajax6\'),{resizable: true});
  
        //Initialize Font sizxe auto resizing
        //var $ = jQuery;
        var bt = BigText.noConflict(true);
        jQuery.fn.bt = bt.jQueryMethod;

        jQuery(\'#shopTitleDiv\').bt({maxfontsize:50});
   '; ?>
</script>
    <!-------------------------------------------->
   
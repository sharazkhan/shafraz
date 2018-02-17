<?php /* Smarty version 2.6.28, created on 2017-12-10 15:16:07
         compiled from includes/footer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'pageaddvar', 'includes/footer.tpl', 2, false),array('function', 'gt', 'includes/footer.tpl', 25, false),array('function', 'modurl', 'includes/footer.tpl', 29, false),array('function', 'blockposition', 'includes/footer.tpl', 87, false),array('modifier', 'unserialize', 'includes/footer.tpl', 24, false),)), $this); ?>

<?php echo smarty_function_pageaddvar(array('name' => 'stylesheet','value' => ($this->_tpl_vars['themepath'])."/style/style.css?v=1.5"), $this);?>

<?php echo smarty_function_pageaddvar(array('name' => 'stylesheet','value' => ($this->_tpl_vars['themepath'])."/style/responsive.css?v=1.5"), $this);?>

<?php echo smarty_function_pageaddvar(array('name' => 'stylesheet','value' => "modules/ZSELEX/lib/jquery-nicemodal-1.0/jquery-nicemodal.css?v=1.1"), $this);?>

<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => "modules/ZSELEX/lib/jquery-nicemodal-1.0/jquery-nicemodal.js"), $this);?>
 

<footer class="footer">
    <div class="container">
        <div class="footer-top clearfix">
            <div class="footer-logo"></div>
            <div class="mobi-icons">
                <h4>FØLG OS </h4>
                <ul>
                    <li><a href="#"><i class="fa fa-facebook-square"></i></a></li>
                    <li><a href="#"><i class="fa fa-twitter-square"></i></a></li>
                    <li><a href="#"><i class="fa fa-pinterest-square"></i></a></li>
                    <li><a href="#"><i class="fa fa-google-plus-square"></i></a></li>
                    <li><a href="#"><i class="fa fa-share-alt-square"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="row footer-contents">
            <div class="col-sm-3 f-links mobi-click-1">
                <?php $this->assign('termsConditionInfo', ((is_array($_tmp=$this->_tpl_vars['modvars']['ZSELEX']['termsConditionInfo'])) ? $this->_run_mod_handler('unserialize', true, $_tmp) : unserialize($_tmp))); ?>
                <h4 class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo smarty_function_gt(array('text' => 'RETURN AND PAYMENT'), $this);?>
</h4>
                <ul class="mobi-nav-1 dropdown-menu">
                    <li>
                        <a href="#">
                        <span class="footer-pop-up" data-url="<?php echo $this->_tpl_vars['baseurl']; ?>
<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'info','func' => 'footerLink','key' => 'rma','shop_id' => $_REQUEST['shop_id']), $this);?>
">
                            <?php echo smarty_function_gt(array('text' => 'RMA'), $this);?>
 
                        </span>
                        </a>
                    </li>
                    <li>
                         <a href="#">
                        <span  class="footer-pop-up" data-url="<?php echo $this->_tpl_vars['baseurl']; ?>
<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'info','func' => 'footerLink','key' => 'deliveryprices','shop_id' => $_REQUEST['shop_id']), $this);?>
">
                            <?php echo smarty_function_gt(array('text' => 'Delivery prices'), $this);?>
 
                        </span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                        <span  class="footer-pop-up" data-url="<?php echo $this->_tpl_vars['baseurl']; ?>
<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'info','func' => 'footerLink','key' => 'deliverytime','shop_id' => $_REQUEST['shop_id']), $this);?>
">
                            <?php echo smarty_function_gt(array('text' => 'Delivery time'), $this);?>
 
                        </span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-sm-3 f-links mobi-click-2">
                <h4 class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo smarty_function_gt(array('text' => 'TERMS AND CONDITIONS'), $this);?>
</h4>
                <ul class="mobi-nav-2 dropdown-menu">
                    <li>
                        <a href="#">
                        <span class="footer-pop-up" data-url="<?php echo $this->_tpl_vars['baseurl']; ?>
<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'info','func' => 'footerLink','key' => 'termsoftrade','shop_id' => $_REQUEST['shop_id']), $this);?>
">
                            <?php echo smarty_function_gt(array('text' => 'Terms of trade'), $this);?>
 
                        </span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                        <span class="footer-pop-up" data-url="<?php echo $this->_tpl_vars['baseurl']; ?>
<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'info','func' => 'footerLink','key' => 'privacy','shop_id' => $_REQUEST['shop_id']), $this);?>
">
                            <?php echo smarty_function_gt(array('text' => 'Privacy'), $this);?>

                        </span>
                         </a>
                    </li>
                    <li>
                        <a href="#">
                        <span class="footer-pop-up" data-url="<?php echo $this->_tpl_vars['baseurl']; ?>
<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'info','func' => 'footerLink','key' => 'securepayment','shop_id' => $_REQUEST['shop_id']), $this);?>
">
                            <?php echo smarty_function_gt(array('text' => 'Secure payment'), $this);?>

                        </span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-md-3 col-sm-6 f-form">
                                <?php echo smarty_function_blockposition(array('name' => 'bot2-03'), $this);?>

            </div>
            <div class="col-md-3 col-sm-12 f-social-icons">
                <!--<h4><?php echo smarty_function_gt(array('text' => 'Follow Us'), $this);?>
 </h4>
                <ul>
                    <li><a href="#"><i class="fa fa-facebook-square"></i></a></li>
                    <li><a href="#"><i class="fa fa-twitter-square"></i></a></li>
                    <li><a href="#"><i class="fa fa-pinterest-square"></i></a></li>
                    <li><a href="#"><i class="fa fa-google-plus-square"></i></a></li>
                    <li><a href="#"><i class="fa fa-share-alt-square"></i></a></li>
                </ul>-->
                            </div>
        </div>
    </div>
</footer>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <!--<script src="<?php echo $this->_tpl_vars['themepath']; ?>
/js/jquery.min.js"><?php echo ''; ?>
</script>-->
  <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"><?php echo ''; ?>
</script>-->
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?php echo $this->_tpl_vars['themepath']; ?>
/js/bootstrap.min.js"><?php echo ''; ?>
</script>
<script src="<?php echo $this->_tpl_vars['themepath']; ?>
/js/jquery.bxslider.min.js"><?php echo ''; ?>
</script>
<script src="<?php echo $this->_tpl_vars['themepath']; ?>
/js/jquery.mousewheel-3.0.6.pack.js"><?php echo ''; ?>
</script>
<script src="<?php echo $this->_tpl_vars['themepath']; ?>
/js/jquery.fancybox.js"><?php echo ''; ?>
</script>
<script src="<?php echo $this->_tpl_vars['themepath']; ?>
/js/chosen.jquery.js"><?php echo ''; ?>
</script>
<script src="<?php echo $this->_tpl_vars['themepath']; ?>
/js/custom.js"><?php echo ''; ?>
</script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['baseurl']; ?>
/modules/ZSELEX/javascript/bigtext/bigtext.js"><?php echo ''; ?>
</script>
<script><?php echo '
    //Initialize Font sizxe auto resizing
    //var $ = jQuery;
    var bt = BigText.noConflict(true);
    jQuery.fn.bt = bt.jQueryMethod;

    jQuery(\'#shopTitleDiv\').bt({maxfontsize: 50});
    
     jQuery(\'.footer-pop-up\').nicemodal({
        width: \'500px\',
        height: \'500px\',
        keyCodeToClose: 27,
        defaultCloseButton: true,
        closeOnClickOverlay: true,
        closeOnDblClickOverlay: false,
        // onOpenModal: function(){
        //     alert(\'Opened\');
        // },
        // onCloseModal: function(){
        //     alert(\'Closed\');
        // }
    });

'; ?>
</script>
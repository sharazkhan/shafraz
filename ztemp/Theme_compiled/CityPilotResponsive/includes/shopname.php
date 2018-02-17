<?php /* Smarty version 2.6.28, created on 2017-07-09 14:33:13
         compiled from includes/shopname.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'cleantext', 'includes/shopname.tpl', 3, false),)), $this); ?>
  <section class="shop-name">
            <div class="container" id="shopTitleDiv">
                <h2><?php echo ((is_array($_tmp=$_REQUEST['shopName'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
 <small><?php echo $_REQUEST['city_name']; ?>
</small></h2>
            </div>
        </section>
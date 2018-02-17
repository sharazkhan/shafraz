<?php /* Smarty version 2.6.28, created on 2017-09-30 14:24:38
         compiled from includes/shopheader.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'shopdetails', 'includes/shopheader.tpl', 25, false),array('function', 'modurl', 'includes/shopheader.tpl', 28, false),array('function', 'gt', 'includes/shopheader.tpl', 29, false),array('function', 'shoprating', 'includes/shopheader.tpl', 62, false),array('function', 'blockposition', 'includes/shopheader.tpl', 82, false),array('modifier', 'cleantext', 'includes/shopheader.tpl', 33, false),array('modifier', 'replace', 'includes/shopheader.tpl', 51, false),)), $this); ?>



   <style><?php echo '
     a.activehref{color:#e65621;}
     a.deactivehref{color:#717D82;}
 '; ?>
</style>

<?php echo smarty_function_shopdetails(array('shop_id' => $_REQUEST['shop_id']), $this);?>

 <?php if ($this->_tpl_vars['perm']): ?>
<div class="OrageEditSec mainHeadEdit">
    <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'admin','func' => 'shopsettings','shop_id' => $_REQUEST['shop_id']), $this);?>
#aInformation">
        <img src="<?php echo $this->_tpl_vars['themepath']; ?>
/images/OrageEdit.png"><?php echo smarty_function_gt(array('text' => 'Edit Content'), $this);?>
</a></div>
  <?php endif; ?>
<div class="ShopRatingDiv">
    <div id="ShopName" style="width:630px">
    <!--<span id="shops_span"> <?php echo ((is_array($_tmp=$this->_tpl_vars['shop_name'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
 </span>
        <br>-->
        <div id="city_div"> <span id="city_span">&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['city_name'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
</span> </div>
    </div>
    <!--  Shop Rating show --->
    <div class="Rating" style="padding-top:72px;">
        <!--h5><?php echo smarty_function_gt(array('text' => 'You can rate shop'), $this);?>
</h5-->
        
                 <span align="right">
            <?php if ($this->_tpl_vars['aff_id'] > 0): ?>
              <?php $this->assign('imagename', ((is_array($_tmp=$this->_tpl_vars['aff_image'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '%20') : smarty_modifier_replace($_tmp, ' ', '%20'))); ?>
              <?php $this->assign('image', "modules/ZSELEX/images/affiliates/".($this->_tpl_vars['imagename'])); ?>
                 <?php if (is_file ( $this->_tpl_vars['image'] )): ?>
                    <div class="ShopHeaderAff">
                        <img src="modules/ZSELEX/images/affiliates/<?php echo $this->_tpl_vars['aff_image']; ?>
">
                    </div>
                <?php endif; ?>
             <?php endif; ?>   
        </span>
        <div class="ajax" title="<?php echo smarty_function_gt(array('text' => 'You can rate shop'), $this);?>
">
                 <?php echo smarty_function_shoprating(array('shop_id' => $_REQUEST['shop_id'],'user_id' => $this->_tpl_vars['uid']), $this);?>

        </div>
    </div>
    <!--  Shop Rating show ends   -->

    <!--  Shop Menu    -->
       
      
       <!-- MENU BUTTON -->
        <button class="wsite-nav-button2">Menu</button>
        <div id="minisitemenu_block" class="minisitemenu_block">
        <?php echo smarty_function_blockposition(array('name' => 'minisitemenu'), $this);?>

        </div>
    <!--  Shop Menu Ends    --> 
 </div>
       
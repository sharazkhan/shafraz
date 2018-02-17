<?php /* Smarty version 2.6.28, created on 2018-02-03 06:44:20
         compiled from ajax/specialdealevents.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'counter', 'ajax/specialdealevents.tpl', 2, false),array('function', 'modurl', 'ajax/specialdealevents.tpl', 4, false),array('function', 'imageproportional', 'ajax/specialdealevents.tpl', 13, false),array('function', 'eventimage', 'ajax/specialdealevents.tpl', 20, false),array('function', 'shorttext', 'ajax/specialdealevents.tpl', 45, false),array('function', 'gt', 'ajax/specialdealevents.tpl', 49, false),array('function', 'displayprice', 'ajax/specialdealevents.tpl', 49, false),array('modifier', 'replace', 'ajax/specialdealevents.tpl', 10, false),array('modifier', 'pathinfo', 'ajax/specialdealevents.tpl', 33, false),array('modifier', 'cat', 'ajax/specialdealevents.tpl', 35, false),array('modifier', 'cleantext', 'ajax/specialdealevents.tpl', 45, false),)), $this); ?>

<?php echo smarty_function_counter(array('assign' => 'idx_evnt','start' => 0,'print' => 0), $this);?>

<?php $_from = $this->_tpl_vars['events']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['event']):
?>
<a <?php if ($this->_tpl_vars['event']['event_link'] != '' && $this->_tpl_vars['event']['call_link_directly'] == 1): ?><?php if ($this->_tpl_vars['event']['open_new']): ?>target="_blank"<?php endif; ?><?php endif; ?> href="<?php if ($this->_tpl_vars['event']['event_link'] != '' && $this->_tpl_vars['event']['call_link_directly'] == 1): ?><?php echo $this->_tpl_vars['event']['event_link']; ?>
<?php else: ?><?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'viewevent','shop_id' => $this->_tpl_vars['event']['shop_id'],'eventId' => $this->_tpl_vars['event']['shop_event_id']), $this);?>
<?php endif; ?>">
    <div class="col-sm-3 col-xs-6 special-sub-product hover-border nopadding">

        <div class="thumbnail">
            <div class="pro-image">
                <?php if ($this->_tpl_vars['event']['showfrom'] == 'image'): ?> 
                <?php $this->assign('image1', ((is_array($_tmp=$this->_tpl_vars['event']['event_image'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '%20') : smarty_modifier_replace($_tmp, ' ', '%20'))); ?>
                <?php $this->assign('image', "zselexdata/".($this->_tpl_vars['event']['shop_id'])."/events/medium/".($this->_tpl_vars['image1'])); ?>
                <?php if (file_exists ( $this->_tpl_vars['image'] )): ?>
                <?php echo smarty_function_imageproportional(array('image' => $this->_tpl_vars['event']['event_image'],'path' => ($this->_tpl_vars['baseurl'])."zselexdata/".($this->_tpl_vars['event']['shop_id'])."/events/thumb",'height' => '90','width' => '128'), $this);?>

                <img class="lazy"  <?php echo $this->_tpl_vars['imagedimensions']; ?>
   src="<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $this->_tpl_vars['event']['shop_id']; ?>
/events/thumb/<?php echo ((is_array($_tmp=$this->_tpl_vars['event']['event_image'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '%20') : smarty_modifier_replace($_tmp, ' ', '%20')); ?>
" >
                <?php endif; ?>

                <?php elseif ($this->_tpl_vars['event']['showfrom'] == 'product'): ?>
                <?php if ($this->_tpl_vars['event']['shoptype'] == 'iSHOP'): ?>
                <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'productview','id' => $this->_tpl_vars['event']['product_id']), $this);?>
" target="_blank">
                   <?php echo smarty_function_eventimage(array('shoptype' => $this->_tpl_vars['event']['shoptype'],'shop_id' => $this->_tpl_vars['event']['shop_id'],'id' => $this->_tpl_vars['event']['product_id'],'from' => $this->_tpl_vars['event']['showfrom']), $this);?>

                   <?php echo smarty_function_imageproportional(array('image' => $this->_tpl_vars['eventimage'],'path' => $this->_tpl_vars['path'],'height' => '90','width' => '128'), $this);?>

                   <img class="lazy" <?php echo $this->_tpl_vars['imagedimensions']; ?>
   src="<?php echo ((is_array($_tmp=$this->_tpl_vars['eventfullpath'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '%20') : smarty_modifier_replace($_tmp, ' ', '%20')); ?>
" >
                </a>
                <?php elseif ($this->_tpl_vars['event']['shoptype'] == 'zSHOP'): ?>
                <?php echo smarty_function_eventimage(array('shoptype' => $this->_tpl_vars['event']['shoptype'],'shop_id' => $this->_tpl_vars['event']['shop_id'],'id' => $this->_tpl_vars['event']['product_id'],'from' => $this->_tpl_vars['event']['showfrom']), $this);?>

                <?php echo smarty_function_imageproportional(array('image' => $this->_tpl_vars['eventimage'],'path' => $this->_tpl_vars['path'],'height' => '90','width' => '128'), $this);?>

                <a href='http://<?php echo $this->_tpl_vars['zencart']['domain']; ?>
/index.php?main_page=product_info&products_id=<?php echo $this->_tpl_vars['event']['product_id']; ?>
' target='_blank'>
                    <img class="lazy" <?php echo $this->_tpl_vars['imagedimensions']; ?>
  src="<?php echo ((is_array($_tmp=$this->_tpl_vars['eventfullpath'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '%20') : smarty_modifier_replace($_tmp, ' ', '%20')); ?>
" >
                </a>
                <?php endif; ?>  

                <?php elseif ($this->_tpl_vars['event']['showfrom'] == 'doc'): ?> 
                <?php if (((is_array($_tmp=$this->_tpl_vars['event']['event_doc'])) ? $this->_run_mod_handler('pathinfo', true, $_tmp, @PATHINFO_EXTENSION) : pathinfo($_tmp, @PATHINFO_EXTENSION)) == 'pdf'): ?>
                <?php $this->assign('pdfimage', ((is_array($_tmp=$this->_tpl_vars['event']['event_doc'])) ? $this->_run_mod_handler('pathinfo', true, $_tmp, @PATHINFO_FILENAME) : pathinfo($_tmp, @PATHINFO_FILENAME))); ?>
                <?php echo smarty_function_imageproportional(array('image' => ((is_array($_tmp=$this->_tpl_vars['pdfimage'])) ? $this->_run_mod_handler('cat', true, $_tmp, ".jpg") : smarty_modifier_cat($_tmp, ".jpg")),'path' => ($this->_tpl_vars['baseurl'])."zselexdata/".($this->_tpl_vars['event']['shop_id'])."/events/docs/thumb",'height' => '90','width' => '128'), $this);?>


                <img class="lazy"  <?php echo $this->_tpl_vars['imagedimensions']; ?>
  src="zselexdata/<?php echo $this->_tpl_vars['event']['shop_id']; ?>
/events/docs/thumb/<?php echo ((is_array($_tmp=$this->_tpl_vars['event']['event_doc'])) ? $this->_run_mod_handler('pathinfo', true, $_tmp, @PATHINFO_FILENAME) : pathinfo($_tmp, @PATHINFO_FILENAME)); ?>
.jpg" >

                <?php elseif (((is_array($_tmp=$this->_tpl_vars['event']['event_doc'])) ? $this->_run_mod_handler('pathinfo', true, $_tmp, @PATHINFO_EXTENSION) : pathinfo($_tmp, @PATHINFO_EXTENSION)) == 'doc'): ?> 
                <?php endif; ?>   
                <?php endif; ?>
            </div>
            <div class="product-caption">
                <h3>
                    <?php echo smarty_function_shorttext(array('text' => ((is_array($_tmp=$this->_tpl_vars['event']['shop_event_name'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)),'len' => 25), $this);?>

                </h3>
                <div class="pro-sub-row clearfix">
                    <div class="product-amount">
                        <?php if ($this->_tpl_vars['event']['price'] < 1): ?><?php echo smarty_function_gt(array('text' => 'FREE'), $this);?>
<?php else: ?><?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['event']['price']), $this);?>
<?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</a>
<?php endforeach; endif; unset($_from); ?>

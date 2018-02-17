<?php /* Smarty version 2.6.28, created on 2017-10-29 15:00:52
         compiled from blocks/minisiteevent/minisiteevent.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'modurl', 'blocks/minisiteevent/minisiteevent.tpl', 3, false),array('function', 'gt', 'blocks/minisiteevent/minisiteevent.tpl', 3, false),array('modifier', 'replace', 'blocks/minisiteevent/minisiteevent.tpl', 16, false),)), $this); ?>

<?php if ($this->_tpl_vars['perm']): ?>
<a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'admin','func' => 'events','shop_id' => $_REQUEST['shop_id']), $this);?>
" class="edit-link"><i class="fa fa-pencil-square" aria-hidden="true"></i><?php echo smarty_function_gt(array('text' => 'Edit Events'), $this);?>
</a>
<?php endif; ?>
<div class="shop-event-wrap col-md-12 col-sm-5 col-md-pull-0 col-sm-pull-6 col-xs-5 col-xs-pull-6">
    <?php if ($this->_tpl_vars['count'] > 0): ?>
    <h3><?php echo smarty_function_gt(array('text' => 'Event'), $this);?>
:</h3>
    <?php endif; ?>
    <div class="shop-event">
        <?php $_from = $this->_tpl_vars['events']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['index'] => $this->_tpl_vars['item']):
?>
            <?php if ($this->_tpl_vars['index'] == 1): ?>
            <?php break; ?>
            <?php endif; ?>
            <?php if ($this->_tpl_vars['item']['showfrom'] == 'image'): ?> 
            <a <?php if ($this->_tpl_vars['item']['event_link'] != '' && $this->_tpl_vars['item']['call_link_directly'] == 1): ?><?php if ($this->_tpl_vars['item']['open_new']): ?>target="_blank"<?php endif; ?><?php endif; ?> href="<?php if ($this->_tpl_vars['item']['event_link'] != '' && $this->_tpl_vars['item']['call_link_directly'] == 1): ?><?php echo $this->_tpl_vars['item']['event_link']; ?>
<?php else: ?><?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'viewevent','shop_id' => $_REQUEST['shop_id'],'eventId' => $this->_tpl_vars['item']['shop_event_id']), $this);?>
<?php endif; ?>">
                <?php $this->assign('image1', ((is_array($_tmp=$this->_tpl_vars['item']['event_image'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '%20') : smarty_modifier_replace($_tmp, ' ', '%20'))); ?>
                <?php $this->assign('image', "zselexdata/".($this->_tpl_vars['shop_id'])."/events/medium/".($this->_tpl_vars['image1'])); ?>
                <?php if (file_exists ( $this->_tpl_vars['image'] )): ?>
                <img alt="" src="<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $this->_tpl_vars['shop_id']; ?>
/events/medium/<?php echo ((is_array($_tmp=$this->_tpl_vars['item']['event_image'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '%20') : smarty_modifier_replace($_tmp, ' ', '%20')); ?>
" class="img-responsive">
                <?php endif; ?>
            </a>
            <?php elseif ($this->_tpl_vars['item']['showfrom'] == 'doc'): ?> 
            <?php if ($this->_tpl_vars['item']['extension'] == 'pdf'): ?>
            <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'pdfViewEvent','shop_id' => $_REQUEST['shop_id'],'pdf' => $this->_tpl_vars['item']['event_doc']), $this);?>
" target="_blank">
               <img class="img-responsive" src="<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $this->_tpl_vars['shop_id']; ?>
/events/docs/thumb/<?php echo $this->_tpl_vars['item']['pdf_image']; ?>
.jpg">
            </a>
            <?php else: ?>
            <img class="img-responsive" src="<?php echo $this->_tpl_vars['themepath']; ?>
/images/doc.png" > 
            <?php endif; ?>
            <?php elseif ($this->_tpl_vars['item']['showfrom'] == 'product'): ?>
                <?php if ($this->_tpl_vars['shoptype'] == 'iSHOP'): ?>
                <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'productview','id' => $this->_tpl_vars['item']['product_id']), $this);?>
" target="_blank">
                     <img class="img-responsive"  src="<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $this->_tpl_vars['shop_id']; ?>
/products/thumb/<?php echo ((is_array($_tmp=$this->_tpl_vars['item']['ishopProduct']['prd_image'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '%20') : smarty_modifier_replace($_tmp, ' ', '%20')); ?>
" >
                </a>
                <?php elseif ($this->_tpl_vars['shoptype'] == 'zSHOP'): ?>
                <a href='http://<?php echo $this->_tpl_vars['zencart']['domain']; ?>
/index.php?main_page=product_info&products_id=<?php echo $this->_tpl_vars['product']['products_id']; ?>
' target='_blank'>
                       <img class="img-responsive"  src="http://<?php echo $this->_tpl_vars['zencart']['domain']; ?>
/images/<?php echo ((is_array($_tmp=$this->_tpl_vars['item']['zshopProduct']['products_image'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '%20') : smarty_modifier_replace($_tmp, ' ', '%20')); ?>
" >
                </a>
                <?php endif; ?> 
            <?php elseif ($this->_tpl_vars['item']['showfrom'] == 'article'): ?> 
                <img class="img-responsive"  src="<?php echo $this->_tpl_vars['baseurl']; ?>
<?php echo $this->_tpl_vars['modvars']['News']['picupload_uploaddir']; ?>
/pic_sid<?php echo $this->_tpl_vars['item']['article']['sid']; ?>
-0-norm.jpg" >
            <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
    </div>
      <?php if ($this->_tpl_vars['count'] > 0): ?>
    <div class="all-events">
        <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'showEvents','shop_id' => $_REQUEST['shop_id']), $this);?>
" class="view-all"><?php echo smarty_function_gt(array('text' => 'view all'), $this);?>
</a>
    </div>
     <?php endif; ?>
</div>
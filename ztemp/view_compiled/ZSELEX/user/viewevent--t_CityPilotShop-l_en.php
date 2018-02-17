<?php /* Smarty version 2.6.28, created on 2017-10-10 23:48:09
         compiled from user/viewevent.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'modurl', 'user/viewevent.tpl', 3, false),array('function', 'gt', 'user/viewevent.tpl', 4, false),array('function', 'fblikeservice', 'user/viewevent.tpl', 71, false),array('function', 'fbshare', 'user/viewevent.tpl', 72, false),array('modifier', 'cleantext', 'user/viewevent.tpl', 12, false),array('modifier', 'wordwrap', 'user/viewevent.tpl', 12, false),)), $this); ?>

<?php if ($this->_tpl_vars['perm']): ?>
<a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'admin','func' => 'events','shop_id' => $_REQUEST['shop_id'],'event_id' => $this->_tpl_vars['event']['shop_event_id'],'src' => 'view'), $this);?>
" class="edit-link">
    <i class="fa fa-pencil-square" aria-hidden="true"></i><?php echo smarty_function_gt(array('text' => 'Edit Event'), $this);?>

</a>
<?php endif; ?>

<div class="col-sm-12">
    <?php if ($this->_tpl_vars['event']['event_link'] != ''): ?>
    <h2>
        <a <?php if ($this->_tpl_vars['event']['open_new']): ?>target="_blank"<?php endif; ?> href="<?php echo $this->_tpl_vars['event']['event_link']; ?>
">
            <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['event']['shop_event_name'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)))) ? $this->_run_mod_handler('wordwrap', true, $_tmp, 19, "<br />", true) : smarty_modifier_wordwrap($_tmp, 19, "<br />", true)); ?>
 
        </a>
    </h2>
    <?php else: ?>
    <h2> <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['event']['shop_event_name'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)))) ? $this->_run_mod_handler('wordwrap', true, $_tmp, 19, "<br />", true) : smarty_modifier_wordwrap($_tmp, 19, "<br />", true)); ?>
 </h2>
    <?php endif; ?>
    <p></p>
     <ul class="image-lightbox full-width-image clearfix">
        <li>
            <?php if ($this->_tpl_vars['event']['showfrom'] == 'image'): ?> 
            <?php if ($this->_tpl_vars['event']['call_link_directly'] == 2 && $this->_tpl_vars['event']['event_link'] != ''): ?> 
            <a title="<?php echo $this->_tpl_vars['event']['shop_event_name']; ?>
" <?php if ($this->_tpl_vars['event']['open_new']): ?>target="_blank"<?php endif; ?>  href="<?php echo $this->_tpl_vars['event']['event_link']; ?>
" >
               <img alt="" class="img-responsive" src="<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $this->_tpl_vars['shop_id']; ?>
/events/medium/<?php echo $this->_tpl_vars['event']['event_image']; ?>
">
            </a>
            <?php else: ?>
            <a class="fancybox" rel="gallery1" href="<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $this->_tpl_vars['shop_id']; ?>
/events/fullsize/<?php echo $this->_tpl_vars['event']['event_image']; ?>
" title="<?php echo $this->_tpl_vars['event']['shop_event_name']; ?>
">
                <img alt="" class="img-responsive" src="<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $this->_tpl_vars['shop_id']; ?>
/events/medium/<?php echo $this->_tpl_vars['event']['event_image']; ?>
">
            </a>
            <?php endif; ?>
            <?php $this->assign('event_image', ($this->_tpl_vars['baseurl'])."zselexdata/".($this->_tpl_vars['shop_id'])."/events/medium/".($this->_tpl_vars['event']['event_image'])); ?>
            <?php elseif ($this->_tpl_vars['event']['showfrom'] == 'doc'): ?> 
            <?php if ($this->_tpl_vars['extension'] == 'pdf'): ?>

            <a target="_blank" href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'pdfViewEvent','shop_id' => $_REQUEST['shop_id'],'pdf' => $this->_tpl_vars['event_doc']), $this);?>
">
               <img class="img-responsive" src="<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $this->_tpl_vars['shop_id']; ?>
/events/docs/medium/<?php echo $this->_tpl_vars['event']['pdf_image']; ?>
.jpg">
            </a>
            <?php else: ?>
            <img class="img-responsive" src="<?php echo $this->_tpl_vars['themepath']; ?>
/images/doc.png">
            <?php endif; ?>
            <br>
            <a target="_blank" href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'pdfViewEvent','shop_id' => $_REQUEST['shop_id'],'pdf' => $this->_tpl_vars['event_doc']), $this);?>
"><?php echo smarty_function_gt(array('text' => 'view document'), $this);?>
</a>

            <?php elseif ($this->_tpl_vars['event']['showfrom'] == 'product'): ?>
            <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'productview','id' => $this->_tpl_vars['product']['product_id']), $this);?>
" target="_blank">
               <img class="img-responsive" src="<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $this->_tpl_vars['shop_id']; ?>
/products/medium/<?php echo $this->_tpl_vars['product']['prd_image']; ?>
">
            </a>
            <?php $this->assign('event_image', ($this->_tpl_vars['baseurl'])."zselexdata/".($this->_tpl_vars['shop_id'])."/products/thumb/".($this->_tpl_vars['event']['event_image'])); ?>
            <br>
            <?php echo smarty_function_gt(array('text' => 'Product name'), $this);?>
 : <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'productview','id' => $this->_tpl_vars['product']['product_id']), $this);?>
">
                                          <?php echo ((is_array($_tmp=$this->_tpl_vars['product']['product_name'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>

        </a>
        <?php endif; ?>
    </li>
</ul>


<h4><b><?php echo ((is_array($_tmp=$this->_tpl_vars['event']['shop_event_shortdescription'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
</b></h4>
<p>
    <?php echo ((is_array($_tmp=$this->_tpl_vars['event']['shop_event_description'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>

</p>
<div class="text-right">
    <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'showEvents','shop_id' => $_REQUEST['shop_id']), $this);?>
" class="btn view-all"><?php echo smarty_function_gt(array('text' => 'view all'), $this);?>
 </a>
</div>
</div>

 <?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'viewevent','shop_id' => $this->_tpl_vars['event']['shop_id'],'eventId' => $this->_tpl_vars['event']['shop_event_id'],'assign' => 'event_link'), $this);?>

                 <?php $this->assign('url', ($this->_tpl_vars['baseurl']).($this->_tpl_vars['event_link'])); ?>
<!-- social share  -->
<div class="social-share col-sm-12">
   <?php echo smarty_function_fblikeservice(array('action' => 'like','url' => $this->_tpl_vars['url'],'width' => '500px','height' => '21px','layout' => 'horizontal','shop_id' => $this->_tpl_vars['event']['shop_id'],'addmetatags' => true,'metatitle' => $this->_tpl_vars['event']['shop_event_name'],'metatype' => 'website','metaimage' => $this->_tpl_vars['event_image'],'description' => $this->_tpl_vars['event']['shop_event_description'],'faces' => true), $this);?>

   <?php echo smarty_function_fbshare(array('shop_id' => $this->_tpl_vars['event']['shop_id'],'url' => $this->_tpl_vars['url']), $this);?>

</div>
<!-- social end -->
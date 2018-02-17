<?php /* Smarty version 2.6.28, created on 2017-10-29 15:00:52
         compiled from blocks/minisiteimagesblock/minisiteimages.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'modurl', 'blocks/minisiteimagesblock/minisiteimages.tpl', 5, false),array('function', 'gt', 'blocks/minisiteimagesblock/minisiteimages.tpl', 6, false),array('function', 'imageproportional', 'blocks/minisiteimagesblock/minisiteimages.tpl', 17, false),array('modifier', 'replace', 'blocks/minisiteimagesblock/minisiteimages.tpl', 14, false),)), $this); ?>


<?php if ($this->_tpl_vars['perm']): ?>
<a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'admin','func' => 'shopsettings','shop_id' => $_REQUEST['shop_id']), $this);?>
#aImages" class="edit-link"><i class="fa fa-pencil-square" aria-hidden="true"></i>
    <?php echo smarty_function_gt(array('text' => 'Edit Pictures'), $this);?>

</a>
<?php endif; ?>

<?php if ($this->_tpl_vars['count'] > 0): ?>
<div class="col-sm-12">
<ul class="image-lightbox clearfix">
     <?php $_from = $this->_tpl_vars['images']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['index'] => $this->_tpl_vars['item']):
?>
          <?php $this->assign('image1', ((is_array($_tmp=$this->_tpl_vars['item']['name'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '%20') : smarty_modifier_replace($_tmp, ' ', '%20'))); ?>
          <?php $this->assign('image', "zselexdata/".($this->_tpl_vars['shop_id'])."/minisiteimages/thumb/".($this->_tpl_vars['image1'])); ?>
           <?php if (is_file ( $this->_tpl_vars['image'] )): ?>
                <?php echo smarty_function_imageproportional(array('image' => $this->_tpl_vars['item']['name'],'path' => ($this->_tpl_vars['baseurl'])."zselexdata/".($this->_tpl_vars['shop_id'])."/minisiteimages/thumb",'height' => '100','width' => '150'), $this);?>

    <li style="<?php if ($this->_tpl_vars['index']+1 > 4): ?>display:none<?php endif; ?>">
         <a class="fancybox" id="my<?php echo $this->_tpl_vars['item']['file_id']; ?>
" rel="imageviewer[minisiteimageGallery]" title="<?php echo $this->_tpl_vars['item']['filedescription']; ?>
" href="<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $this->_tpl_vars['shop_id']; ?>
/minisiteimages/fullsize/<?php echo $this->_tpl_vars['item']['name']; ?>
" >
            <img  src="<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $this->_tpl_vars['shop_id']; ?>
/minisiteimages/thumb/<?php echo ((is_array($_tmp=$this->_tpl_vars['item']['name'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '%20') : smarty_modifier_replace($_tmp, ' ', '%20')); ?>
" alt="" class="img-responsive"/>
            <?php if ($this->_tpl_vars['index']+1 == 4 && $this->_tpl_vars['count'] > 4): ?> <span><?php echo smarty_function_gt(array('text' => 'more images'), $this);?>
...</span><?php endif; ?>
         </a>
         
    </li>
    <?php endif; ?>
   
    <?php endforeach; endif; unset($_from); ?>
</ul>
</div>
<?php endif; ?>
<?php /* Smarty version 2.6.28, created on 2017-10-29 15:00:07
         compiled from user/shop.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'cleantext', 'user/shop.tpl', 2, false),array('modifier', 'nl2br', 'user/shop.tpl', 2, false),array('function', 'modurl', 'user/shop.tpl', 13, false),array('function', 'gt', 'user/shop.tpl', 33, false),array('function', 'fblikeservice', 'user/shop.tpl', 39, false),array('function', 'fbshare', 'user/shop.tpl', 40, false),)), $this); ?>
<p>
    <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['shopitem']['shop_info'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

</p>
<p>
    <?php $_from = $this->_tpl_vars['ztext_pages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['page']):
?>
<p><?php echo $this->_tpl_vars['page']['bodytext']; ?>
</p>

<?php if (! $this->_tpl_vars['page_setting']['disable_frontend_image']): ?>
<?php if ($this->_tpl_vars['page']['extension'] == 'pdf'): ?>
<?php $this->assign('imageExist', "zselexdata/".($_REQUEST['shop_id'])."/ztext/pdf/medium/".($this->_tpl_vars['page']['image'])); ?>
<?php if (is_file ( $this->_tpl_vars['imageExist'] )): ?>
<p>
    <a target="_blank" href="<?php echo smarty_function_modurl(array('modname' => 'ZTEXT','type' => 'user','func' => 'pdfView','id' => $this->_tpl_vars['page']['text_id']), $this);?>
">
       <img src="<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $_REQUEST['shop_id']; ?>
/ztext/pdf/medium/<?php echo $this->_tpl_vars['page']['image']; ?>
">
    </a>
</p>
<?php endif; ?>
<?php else: ?>    
<?php $this->assign('imageExist', "zselexdata/".($_REQUEST['shop_id'])."/ztext/medium/".($this->_tpl_vars['page']['image'])); ?>
<?php if (is_file ( $this->_tpl_vars['imageExist'] )): ?>
<p>
    <a id="my<?php echo $this->_tpl_vars['page']['text_id']; ?>
" rel="imageviewer[pageGallery]" title="<?php echo $this->_tpl_vars['page']['headertext']; ?>
" href="<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $_REQUEST['shop_id']; ?>
/ztext/fullsize/<?php echo $this->_tpl_vars['page']['image']; ?>
">
        <img  src="<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $_REQUEST['shop_id']; ?>
/ztext/medium/<?php echo $this->_tpl_vars['page']['image']; ?>
">
    </a>
</p>
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</p>
<?php if ($this->_tpl_vars['shopitem']['link_to_homepage'] != ''): ?>
<p>
    <a  href="<?php echo $this->_tpl_vars['shopitem']['link_to_homepage']; ?>
" target="blank"><?php echo smarty_function_gt(array('text' => 'Click here to go to our homepage (will open in new page)...'), $this);?>
</a>
</p>
<?php endif; ?>

<!-- social share  -->
<div class="social-share">
   <?php echo smarty_function_fblikeservice(array('action' => 'like','url' => $this->_tpl_vars['url'],'width' => '500px','height' => '21px','layout' => 'horizontal','shop_id' => $_REQUEST['shop_id'],'addmetatags' => true,'metatitle' => $this->_tpl_vars['shopitem']['shop_name'],'metatype' => 'website','metaimage' => $this->_tpl_vars['shopImage'],'description' => $this->_tpl_vars['shopitem']['shop_info'],'faces' => true), $this);?>

   <?php echo smarty_function_fbshare(array('shop_id' => $_REQUEST['shop_id'],'url' => $this->_tpl_vars['url']), $this);?>

</div>
<!-- social end -->
<br><br>